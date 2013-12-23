<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Core\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TopicType extends AbstractType
{
    protected $url;
    
    /**
     * @var \SymBB\Core\ForumBundle\Entity\Post 
     */
    protected $post;
    protected $dispatcher;
    
    public function __construct($url, \SymBB\Core\ForumBundle\Entity\Post $post, $dispatcher) {
        $this->url = $url;
        $this->post = $post;
        $this->dispatcher = $dispatcher;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('text', 'textarea')
                ->add('save', 'submit')
                ->add('id', 'hidden')
                ->setAction($this->url);
        
        // create Event to manipulate Post Form
        $event      = new \SymBB\Core\EventBundle\Event\PostFormEvent($this->post, $builder);
        $this->dispatcher->dispatch('symbb.forum.topic.form', $event);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SymBB\Core\ForumBundle\Entity\Post',
        ));
    }

    public function getName()
    {
        return 'topic';
    }
}