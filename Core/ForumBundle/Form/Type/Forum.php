<?

namespace SymBB\Core\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Forum extends AbstractType
{
    protected $translator;
    protected $repo;
    
    public function __construct($translator, $repo) {
        $this->translator = $translator;
        $this->repo = $repo; 
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
            ->add('parent', 'entity', array(
                'class' => 'SymBBCoreForumBundle:Forum',
                'choices' => $this->getParentList()
            ))
            ->add('type', 'choice', array('choices' => $aTypes, 'attr' => array('onchange' => 'submit();')));
        
        $builder->addEventSubscriber(new \SymBB\Core\ForumBundle\Form\EventListener\AddForumFieldSubscriber());
    }
    
    private function getParentList(){

        $repo = $this->repo;
        
        $list = array();

        $entries = $repo->findBy(array('parent' => null), array('name' => 'ASC')); // retrieve your accounts in group1.
        foreach($entries as $entity){
            $list[$entity->getId()] = $entity;
            $this->addChildsToArray($entity, $list);
        }

        return $list;
    } 
    
    private function addChildsToArray($entity, &$array){
        $childs = $entity->getChildren();
        if(!empty($childs) && count($childs) > 0){
            foreach($childs as $child){
                $array[$child->getId()] = $child;
                $this->addChildsToArray($child, $array);
            }
        }
    }

    public function getName()
    {
        return 'forum';
    }
}