<?

namespace SymBB\Core\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Forum extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('description')
            ->add('type', 'choice', array('choices' => array('forum' => 'Forum', 'link' => 'Link', 'category' => 'Kategorie')));
    }

    public function getName()
    {
        return 'forum';
    }
}