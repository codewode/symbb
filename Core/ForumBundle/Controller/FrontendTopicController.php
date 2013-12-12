<?

namespace SymBB\Core\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FrontendTopicController  extends Controller 
{
    
    protected $templateBundle = null;
    
    
    public function showAction($name, $id){
        
        $topic = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Topic', 'symbb')
            ->find($id);
        
        $params = array('topic' => $topic);
        
            
            $post = new \SymBB\Core\ForumBundle\Entity\Post();
            $post->setTopic($topic);
            $post->setAuthor($this->getUser());
            $post->setName($topic->getName());

            $form = $this->getPostForm($post);

            $form->handleRequest($this->get('request'));
            
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager('symbb');
                $em->persist($post);
                $em->flush();

                return $this->redirect($this->generateUrl('_symbb_forum_topic_show', array('name' => $topic->getSeoName(), 'id' => $topic->getId() )));

            }
            
        
        return $this->render($this->getTemplateBundleName('forum').':Topic:show.html.twig', $params);
    }
    
    public function createPostAction(\SymBB\Core\ForumBundle\Entity\Topic $topic){
        
        $post = new \SymBB\Core\ForumBundle\Entity\Post();
        $post->setTopic($topic);
        $post->setAuthor($this->getUser());
        $post->setName($topic->getName());
        
        $form = $this->getPostForm($post);
        
        return $this->render($this->getTemplateBundleName().':Post:new.html.twig', array(
            'form' => $form->createView(),
            'bbcodes' => $this->getBBCodes()
        ));
        
    }
    
    protected function getPostForm($post){
        $topic = $post->getTopic();
        $form = $this->createFormBuilder($post)
            ->add('text', 'textarea', array('attr' => array('class' => 'symbb_bbcode_editor_textarea')))
            ->add('save', 'submit')
            ->setAction($this->generateUrl('_symbb_forum_topic_show', array('name' => $topic->getSeoName(), 'id' => $topic->getId())))
            ->getForm();
        return $form;
    }


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
    
    protected function getTemplateBundleName($for = 'forum'){
        if($this->templateBundle === null){
            $config = $this->container->getParameter('symbb_config');
            $this->templateBundle = $config['template'][$for];
        }
        return $this->templateBundle;
    }
    
    
}