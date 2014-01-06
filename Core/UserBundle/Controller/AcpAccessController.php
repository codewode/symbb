<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AcpAccessController extends Controller 
{
    protected $templateBundle;
    
    public function groupAction(){
        
        $groups = $this->get('doctrine')->getRepository('SymBBCoreUserBundle:Group', 'symbb')
            ->findAll();
        
        $forumList = $this->get('symbb.core.forum.manager')->getSelectList();
        
        return $this->render(
            $this->getTemplateBundleName().':Acp:Group\access.html.twig',
            array('groups' => $groups, 'forumList' => $forumList)
        );
	}

    protected function getTemplateBundleName()
    {
        if ($this->templateBundle === null) {
            $config = $this->container->getParameter('symbb_config');
            $this->templateBundle = $config['template']['acp'];
        }
        return $this->templateBundle;

    }
    
}