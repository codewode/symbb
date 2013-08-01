<?

namespace SymBB\Core\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Forum extends AbstractType
{
    protected $translator;
    
    public function __construct($translator) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
     
        $helperTyp = new \SymBB\Core\ForumBundle\Helper\Format\Forum\Type();
        $helperTyp->setTranslator($this->translator);
        $aTypes    = $helperTyp->getArray();
        
        $builder->add('name')
            ->add('parent')
            ->add('description')
            ->add('type', 'choice', array('choices' => $aTypes));
    }

    public function getName()
    {
        return 'forum';
    }
}