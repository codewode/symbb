<?

namespace SymBB\Core\EventBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use \SymBB\Core\UserBundle\Entity\UserInterface;
use \SymBB\Core\ForumBundle\Entity\Post;

class DefaultTemplateEvent extends Event
{
    
    protected $env;
    protected $html = '';


    public function __construct($env) {
        $this->env = $env;
    }
    
    public function render($templateName, $params){
        $html = $this->env->render(
            $templateName,
            $params
        );
        $this->html = $html;
    }
    
    public function setHtml($html){
        $this->html = $html;
    }
    
    public function getHtml(){
        return $this->html;
    }
}
