<?

namespace SymBB\Core\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FrontendTopicController  extends Controller 
{
    
    protected $templateBundle = null;
    protected $topic = null;
    protected $post = null;


    /**
     * show a Topic
     * @param type $name
     * @param type $id
     * @return type
     */
    public function showAction($name, $id){
        
        $topic  = $this->getTopicById($id);
        $post   = null;
        $form   = $this->getPostForm($topic, $post); 
        
        $em     = $this->get('doctrine')->getManager('symbb');
        $qb     = $em->createQueryBuilder();
        $qb     ->select('p')
                ->from('SymBB\Core\ForumBundle\Entity\Post', 'p')
                ->where('p.topic = '.$topic->getId())
                ->orderBy('p.created', 'ASC');
        $dql    = $qb->getDql();
        $query  = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            20/*limit per page*/
        );
        $pagination->setTemplate($this->getTemplateBundleName('forum').':Pagination:pagination.html.twig');
        
        $params                 = array();
        $params['topic']        = $topic;
        $params['form']         = $form->createView();
        $params['bbcodes']      = $this->getBBCodes();
        $params['pagination']   = $pagination;

        return $this->render($this->getTemplateBundleName('forum').':Topic:show.html.twig', $params);
    }
    
    /**
     * edit a post
     * @param type $name
     * @param type $topic
     * @param type $post
     * @return type
     */
    public function editPostAction($name, $topic, $post){
        $topic      = $this->getTopicById($topic);
        $post       = $this->getPostById($post);
        
        $form       = null;
        $saved      = $this->handlePostRequest($form, $topic, $post);
        
        $params = array('topic' => $topic);
        $params['form']     = $form->createView();
        $params['bbcodes']  = $this->getBBCodes();
        $params['saved']    = $saved;
        $params['post']     = $post;
        
        return $this->render($this->getTemplateBundleName('forum').':Post:edit.html.twig', $params);
    }
    
    public function newPostAction($name, $topic){
        $response   = $this->editPostAction($name, $topic, 0);
        $currUser   = $this->getUser();
        $em         = $this->getDoctrine()->getManager('symbb');
        
        if(is_object($currUser)){
            // adding user topic flags
            $users      = $this->get('doctrine')->getRepository('SymBBCoreUserBundle:User', 'symbb')->findAll();
            $topic      = $this->getTopicById($topic);
            $postCount  = $topic->getPostCount();
            foreach($users as $user){
                if($user->getId() != $currUser->getId()){
                    $flag = new \SymBB\Core\ForumBundle\Entity\Topic\Flag();
                    $flag->setTopic($topic);
                    $flag->setUser();
                    $flag->setFlag('new');
                    $em->persist($flag);
                } else if($postCount > 1) {
                    $flag = new \SymBB\Core\ForumBundle\Entity\Topic\Flag();
                    $flag->setTopic($topic);
                    $flag->setUser();
                    $flag->setFlag('answered');
                    $em->persist($flag);
                }
            }
            $em->flush();
        }
        
        return $response;
    }
    
    public function deletePostAction(\SymBB\Core\ForumBundle\Entity\Post $post){
        $em         = $this->getDoctrine()->getManager('symbb');
        $topic      = $post->getTopic();
        $firstPost  = $topic->getPosts()->first();
        if($firstPost->getId() == $post->getId()){
            return $this->removeAction($topic->getId());
        }
        $em->remove($post);
        $em->flush();
        return $this->render($this->getTemplateBundleName('forum').':Post:delete.html.twig', array('topic' => $topic));
    }
    
    public function removeAction($topic){
        $em         = $this->getDoctrine()->getManager('symbb');
        $topic      = $this->getTopicById($topic);
        $forum      = $topic->getForum();
        $em->remove($topic);
        $em->flush();
        return $this->render($this->getTemplateBundleName('forum').':Topic:delete.html.twig', array('forum' => $forum));
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

        $form->handleRequest($this->get('request'));
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager('symbb');
            $em->persist($post);
            $em->flush();
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
            $post = new \SymBB\Core\ForumBundle\Entity\Post();
            $post->setTopic($topic);
            $post->setAuthor($this->getUser());
            $post->setName($topic->getName());
        }
        
        // create form
        $form = $this->createFormBuilder($post)
            ->add('text', 'textarea', array('attr' => array('class' => 'symbb_bbcode_editor_textarea')))
            ->add('save', 'submit')
            ->add('id', 'hidden')
            ->setAction($this->generateUrl('_symbb_new_post', array('name' => $topic->getSeoName(), 'topic' => $topic->getId())))
            ->getForm();

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