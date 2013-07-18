<?

namespace SymBB\Core\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;


class DisplayController extends Controller 
{
    
	public function indexAction(){
        $container      = $this->container;
        $config         = $container->getParameter('sym_bb_core_config');
        $acpTemplate    = $config['template']['acp'];
        return $this->render(
            $acpTemplate.'::layout.html.twig',
            array()
        );
	}
    
}