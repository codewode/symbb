<?

namespace SymBB\Core\EventBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use \SymBB\Core\ForumBundle\Entity\Post;
use Symfony\Component\Form\FormBuilderInterface;

class PostFormEvent extends Event
{
    
    /**
     * @var Post 
     */
    protected $post;
    
    /**
     *
     * @var FormBuilderInterface 
     */
    protected $builder;


    public function __construct(Post $post, FormBuilderInterface $builder) {
        $this->post     = $post;
        $this->builder = $builder;
    }
    
    public function getPost(){
        return $this->post;
    }
    
    public function getBuilder(){
        return $this->builder;
    }
    
}
