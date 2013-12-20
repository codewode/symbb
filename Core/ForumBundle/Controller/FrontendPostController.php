<?

namespace SymBB\Core\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SymBB\Core\UserBundle\Acl\PermissionMap;
use SymBB\Core\UserBundle\Acl\MaskBuilder;

class FrontendPostController  extends Controller 
{
    
    protected $templateBundle = null;
    protected $topic = null;
    protected $post = null;
    protected $forum = null;
    
    /**
     * edit a post
     * @param type $name
     * @param type $topic
     * @param type $post
     * @return type
     */
    public function editAction($name, $topic, $post){

        $post       = $this->getPostById($post);
        $topic      = $this->getTopicById($topic);
        
        // check only for "edit" case
        if(is_object($post) && $post->getId() > 0){
            $accessService  = $this->get('symbb.core.user.access');
            // check if you have access to edit the post
            $accessService->addAccessCheck('edit', $post);
            // or if you have the access to edit the forum
            $accessService->addAccessCheck('edit', $topic->getForum());
            $accessService->checkAccess();
        } else {
            $accessService  = $this->get('symbb.core.user.access');
            $accessService->addAccessCheck('write', $topic->getForum());
            $accessService->checkAccess();
        }
        
        $form       = null;
        $saved      = $this->handlePostRequest($form, $topic, $post);
        
        $params = array('topic' => $topic);
        $params['form']     = $form->createView();
        $params['bbcodes']  = $this->getBBCodes();
        $params['saved']    = $saved;
        $params['post']     = $post;
        
        return $this->render($this->getTemplateBundleName('forum').':Post:edit.html.twig', $params);
    }
    
    /**
     * @param type $name
     * @param type $topic
     * @return type
     */
    public function newAction($name, $topic){
        
        $response   = $this->editAction($name, $topic, 0);
        $currUser   = $this->getUser();
        $em         = $this->getDoctrine()->getManager('symbb');
        $topic      = $this->getTopicById($topic);
        
        return $response;
    }
    
    /**
     * 
     * @param \SymBB\Core\ForumBundle\Entity\Post $post
     * @return type
     */
    public function deleteAction(\SymBB\Core\ForumBundle\Entity\Post $post){
        
        $accessService  = $this->get('symbb.core.user.access');
        $accessService->addAccessCheck('delete', $post);
        $accessService->addAccessCheck('delete', $post->getTopic()->getForum());
        $accessService->checkAccess();
        
        $em         = $this->getDoctrine()->getManager('symbb');
        $topic      = $post->getTopic();
        $firstPost  = $topic->getPosts()->first();
        if($firstPost->getId() == $post->getId()){
            return $this->forward('SymBBCoreForumBundle:FrontendTopic:remove', array(
                'id'  => $topic->getId()
            ));
        }
        $em->remove($post);
        $em->flush();
        return $this->render($this->getTemplateBundleName('forum').':Post:delete.html.twig', array('topic' => $topic));
    }
    
    /**
     * 
     * @param type $topic
     * @return \SymBB\Core\ForumBundle\Entity\Forum
     */
    protected function getForumById($forum){
        if($this->forum === null){
            $this->forum      = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')->find($forum);
        }
        return $this->forum;
    }
    
    /**
     * 
     * @param type $topic
     * @return \SymBB\Core\ForumBundle\Entity\Topic
     */
    protected function getTopicById($topic){
        if($this->topic === null){
            $this->topic      = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Topic', 'symbb')->find($topic);
        }
        return $this->topic;
    }
    
    /**
     * 
     * @param type $post
     * @return \SymBB\Core\ForumBundle\Entity\Post
     */
    protected function getPostById($post){
        if($this->post === null){
            $this->post      = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Post', 'symbb')->find($post);
        }
        return $this->post;
    }

    
    /**
     * handle the Post Request and save it
     * @param type $form
     * @param \SymBB\Core\ForumBundle\Entity\Topic $topic
     * @param type $post
     * @return type
     */
    protected function handlePostRequest(&$form, \SymBB\Core\ForumBundle\Entity\Topic &$topic, &$post){
        
        
        $form   = $this->getPostForm($topic, $post);
        $oldId  = $post->getId();

        $form->handleRequest($this->get('request'));
        
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager('symbb');
            $em->persist($post);
            $em->flush();
            
            //only if the id was "0" ( only by "new" )
            if($oldId === null){
                // set access to owner
                $accessService  = $this->get('symbb.core.user.access');
                $accessService->grantAccess('owner', $post);
                // adding new flags to all user and a answered flag for the current user
                $this->get('symbb.core.forum.topic.flag')->insertFlags($topic, 'new');
                $this->get('symbb.core.forum.topic.flag')->insertFlag($topic, 'answered');
            }
            return true;
        }
        return false;
    }

    
    /**
     * generate a new Form object
     * @param type $topic
     * @param type $post
     * @return type
     */
    protected function getPostForm($topic, &$post){
        
        // check if form was submited
        $postId = $this->get('request')->get('form_id');
        
        if($postId > 0){
            $post   = $this->getPostById($postId);
        }
   
        // if no post information than create an new empty one
        if($post === null){
            $post = \SymBB\Core\ForumBundle\Entity\Post::createNew($topic, $this->getUser());
        }
        
        if($post->getId() > 0){
            $url    = $this->generateUrl('_symbb_edit_post', array('name' => $topic->getSeoName(), 'topic' => $topic->getId(), 'post' => $post->getId()));
        } else {
            $url    = $this->generateUrl('_symbb_new_post', array('name' => $topic->getSeoName(), 'topic' => $topic->getId()));
        }
        
        $form   = $this->createForm(new \SymBB\Core\ForumBundle\Form\Type\PostType($url), $post);
        
        return $form;
    }

    /**
     * get a list of grouped BBCodes
     * @return array
     */
    protected function getBBCodes(){
        $bbcodes     = array();
        $decoda    = $this->get('fm_bbcode.decoda_manager')->get('filters', 'default');
        $filters    = $decoda->getFilters();
        foreach($filters as $filterKey => $filter){
            $tags = $filter->getTags();
            $groupName = $this->get('translator')->trans($filterKey, array(), 'symbb_bbcode_groups');
            foreach($tags as $tag => $conf){
                if(
                    (
                        $filterKey == 'email' && $tag == 'mail'
                    ) ||
                    (
                        $filterKey == 'url' && $tag == 'link'
                    ) ||
                    (
                        $filterKey == 'image' && $tag == 'img'
                    )
                ){
                    // double bbcode...
                    continue;
                }
                $bbcodes[$groupName][] = array('tag' => $tag, 'name' => $tag);
            }
        }
        return $bbcodes;
    }
    
    /**
     * get the Template Bundle name
     * @param string $for
     * @return string
     */
    protected function getTemplateBundleName($for = 'forum'){
        if($this->templateBundle === null){
            $config = $this->container->getParameter('symbb_config');
            $this->templateBundle = $config['template'][$for];
        }
        return $this->templateBundle;
    }
    
    
}