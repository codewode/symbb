<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\ForumBundle\Twig;

class ForumDataExtension extends \Twig_Extension
{

    protected $flagHandler;

    public function __construct($forumFlagHandler) {
        $this->flagHandler = $forumFlagHandler;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('checkSymbbForIgnoreFlag', array($this, 'checkSymbbForIgnoreFlag')),
            new \Twig_SimpleFunction('checkSymbbForFlag', array($this, 'checkForFlag')),
        );
    }
    
    public function checkSymbbForIgnoreFlag($element)
    {
        return $this->checkForFlag($element, 'ignore');
    }
    
    public function checkForFlag(\SymBB\Core\ForumBundle\Entity\Forum $element, $flag)
    {
        return $this->flagHandler->checkFlag($element, $flag);
    }
        
    public function getName()
    {
        return 'symbb_forum_data';
    }
        
}