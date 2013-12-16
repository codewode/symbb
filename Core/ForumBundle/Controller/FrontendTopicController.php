<?

namespace SymBB\Core\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FrontendTopicController  extends Controller 
{
    
    protected $templateBundle = null;
    
    /**
     * show a Topic
     * @param type $name
     * @param type $id
     * @return type
     */
    public function showAction($name, $id){
        
        $topic = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Topic', 'symbb')
            ->find($id);
        
        $post   = null;
        $form   = $this->getPostForm($topic, $post); 
        
        $params             = array();
        $params['topic']    = $topic;
        $params['form']     = $form->createView();
        $params['bbcodes']  = $this->getBBCodes();

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
        $topic      = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Topic', 'symbb')->find($topic);
        $post       = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Post', 'symbb')->find($post);
        
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
        return $this->editPostAction($name, $topic, 0);
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
            $post   = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Post', 'symbb')->find($postId);
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