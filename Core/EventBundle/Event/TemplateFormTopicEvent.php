<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\EventBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class TemplateFormTopicEvent extends Event
{
    

    protected $form;
    protected $env;
    protected $html = '';


    public function __construct($env, $form) {
        $this->form = $form;
        $this->env = $env;
    }
    
    public function getForm(){
        return $this->form;
    }
    
    public function render($templateName, $params){
        $html = $this->env->render(
            $templateName,
            $params
        );
        $this->html = $html;
    }
    
    public function getHtml(){
        return $this->html;
    }
}
