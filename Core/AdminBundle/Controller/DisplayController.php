<?

namespace SymBB\Core\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;


class DisplayController extends Controller 
{
    
	public function indexAction(){
        $container      = $this->container;
        $config         = $container->getParameter('symbb_config');
        $acpTemplate    = $config['template']['acp'];
        return $this->render(
            $acpTemplate.'::layout.html.twig',
            array()
        );
	}
    
}