<?

namespace SymBB\Core\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AcpController extends \SymBB\Core\AdminBundle\Controller\Base\CrudController
{
    protected $entityBundle = 'SymBBCoreForumBundle';
    protected $templateBundle = 'SymBBTemplateAcpBundle';
    protected $entityName = 'Forum';
    protected $formClass = '\SymBB\Core\ForumBundle\Form\Type\Forum';

    public function newCategoryAction(){
        $entity = $this->getFormEntity();
        $entity->setType('category');
        return $this->editAction();
    }
    
    public function newLinkAction(){
        $entity = $this->getFormEntity();
        $entity->setType('link');
        return $this->editAction();
    }
    
    protected function getForm()
    {
        $entity = $this->getFormEntity();        
        $form   = $this->createForm(new $this->formClass($this->get('translator'), $this->getRepository()), $entity);
        return $form;
    }
    
    protected function findListEntities($parent = null){
        $entityList = $this->getRepository()->findBy(array('parent' => $parent), array('position' => 'ASC'));  
        return $entityList;
    }
    
    protected function addListParams($params){
        $formatType = new \SymBB\Core\ForumBundle\Helper\Format\Forum\Type();
        $formatType->setTranslator($this->get('translator'));
        $params['helper']['type'] = $formatType;
        return $params;
    }
    
    protected function addFormParams($params){
        return $params;
    }
}