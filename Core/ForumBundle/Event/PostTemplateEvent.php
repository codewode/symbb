<?

namespace SymBB\Core\ForumBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use \SymBB\Core\UserBundle\Entity\UserInterface;
use \SymBB\Core\ForumBundle\Entity\Post;

class PostTemplateEvent extends Event
{
    
    /**
     * @var Post 
     */
    protected $post;
    protected $env;
    protected $html = '';


    public function __construct($env, Post $post) {
        $this->post = $post;
        $this->env = $env;
    }
    
    public function getPost(){
        return $this->post;
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
