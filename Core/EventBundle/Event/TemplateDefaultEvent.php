<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\EventBundle\Event;

class TemplateDefaultEvent extends BaseTemplateEvent
{
    
    public function setHtml($html){
        $this->html = $html;
    }
    
}
