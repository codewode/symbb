<?

namespace SymBB\Core\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;


class DisplayController extends Controller 
{
    
	public function indexAction(){
        return $this->render(
            'SymBBTemplateAcpBundle:Forum:list.html.twig',
            array()
        );
	}
    
}