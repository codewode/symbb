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
    
    
    protected function getTemplateBundleName($for = 'forum'){
        if($this->templateBundle === null){
            $config = $this->container->getParameter('symbb_config');
            $this->templateBundle = $config['template'][$for];
        }
        return $this->templateBundle;
    }
    
    
}