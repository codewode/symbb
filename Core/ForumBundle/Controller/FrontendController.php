<?

namespace SymBB\Core\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FrontendController  extends Controller 
{
    
    protected $templateBundle = null;
    
    public function indexAction(){
        return $this->portalAction();
    }
    
    public function portalAction(){
        $forumList = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
            ->findBy(array('parent' => null));
        $params = array('forumList' => $forumList);
        return $this->render($this->getTemplateBundleName('portal').':Portal:index.html.twig', $params);
    }
    
    public function forumAction(){
        
        $forumList = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
            ->findBy(array('parent' => null), array('position' => 'asc'));
        $params = array('forumList' => $forumList);
        return $this->render($this->getTemplateBundleName('forum').':Forum:index.html.twig', $params);
    }
    
    public function forumShowAction($name, $id){
        $forum = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
            ->find($id);
        $params = array('forum' => $forum);
        return $this->render($this->getTemplateBundleName('forum').':Forum:show.html.twig', $params);
    }
    
    public function topicShowAction($name, $id, $post){
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