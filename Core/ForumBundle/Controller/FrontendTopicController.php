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
        return $this->render($this->getTemplateBundleName('forum').':Topic:show.html.twig', $params);
    }
    
    public function evaluatePostAction($id, $like){
        
        $post = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Post', 'symbb')
            ->find($id);
        
        $user = $this->getUser();
        
        if($like){
            $post->addLike($user);
        } else {
            $post->addDislike($user);
        }
        
        $em = $this->get('doctrine')->getEntityManager('symbb');
        $em->persist($post);
        $em->flush();
        
        $response = $this->forward('SymBBCoreForumBundle:FrontendTopic:show', array(
            'name'  => '',
            'id' => $post->getTopic()->getId()
        ));
        
        return $response;
    }
    
    protected function getTemplateBundleName($for = 'forum'){
        if($this->templateBundle === null){
            $config = $this->container->getParameter('symbb_config');
            $this->templateBundle = $config['template'][$for];
        }
        return $this->templateBundle;
    }
    
    
}