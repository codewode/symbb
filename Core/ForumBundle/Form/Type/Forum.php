<?

namespace SymBB\Core\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Forum extends AbstractType
{
    protected $translator;
    
    public function __construct($translator) {
        $this->translator = $translator;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\SymBB\Core\ForumBundle\Entity\Forum'
        ));
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
     
        $helperTyp = new \SymBB\Core\ForumBundle\Helper\Format\Forum\Type();
        $helperTyp->setTranslator($this->translator);
        $aTypes    = $helperTyp->getArray();
        
        $builder->add('name')
            ->add('parent')
            ->add('type', 'choice', array('choices' => $aTypes, 'attr' => array('onchange' => 'submit();')));
        
        $builder->addEventSubscriber(new \SymBB\Core\ForumBundle\Form\EventListener\AddForumFieldSubscriber());
    }

    public function getName()
    {
        return 'forum';
    }
}