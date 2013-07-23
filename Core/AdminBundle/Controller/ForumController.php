<?

namespace SymBB\Core\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;


class ForumController extends Controller 
{
    
    protected $acpTemplate  = '';
    
    public function __construct() {
        $container          = $this->container;
        $config             = $container->getParameter('symbb_config');
        $this->acpTemplate  = $config['template']['acp'];
    }

    
}