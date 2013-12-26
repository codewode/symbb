<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Template\SimpleBundle\Twig;

class FormExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            'form_errorstable' => new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html'))),
            'form_rowtable' => new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html'))),
            'form_resttable' => new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html'))),
            
        );
    }
    
    public function getName()
    {
        return 'symbb_template_simple_form';
    }
}