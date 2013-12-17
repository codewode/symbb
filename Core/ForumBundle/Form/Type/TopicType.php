<?

namespace SymBB\Core\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TopicType extends AbstractType
{
    protected $url;
    
    public function __construct($url = '') {
        $this->url = $url;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('text', 'textarea', array('attr' => array('class' => 'symbb_bbcode_editor_textarea')))
                ->add('save', 'submit')
                ->add('id', 'hidden')
                ->setAction($this->url);
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