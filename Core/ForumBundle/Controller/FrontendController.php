<?

namespace SymBB\Core\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FrontendController  extends Controller 
{
    
    protected $templateBundle = null;
    
    public function indexAction(){
        $forumList = $this->getDoctrine()
            ->getRepository('SymBBForumBundle:Forum')
            ->findBy(array('parent' => null));
        
        $params = array('forumList' => $forumList);
        
        return $this->render($this->getTemplateBundleName().':'.$this->entityName.':edit.html.twig', $params);
    }
    
    protected function getTemplateBundleName(){
        if($this->templateBundle === null){
            $config = $this->container->getParameter('symbb_config');
            $this->templateBundle = $config['template']['frontend'];
        }
        return $this->templateBundle;
    }
}