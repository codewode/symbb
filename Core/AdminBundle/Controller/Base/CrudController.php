<?

namespace SymBB\Core\AdminBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


abstract class CrudController extends Controller 
{
    private $formEntity;
    protected $entityBundle = 'SymBBCoreForumBundle';
    protected $templateBundle = 'SymBBTemplateAcpBundle';
    protected $entityName = '';
    protected $formClass = '';

    

    public function listAction(){
        $entityList = $this->findListEntities();
        
        $params = array('entityList' => $entityList);
        $params = $this->addListParams($params);
        
        return $this->render(
            $this->templateBundle.':'.$this->entityName.':list.html.twig',
            $params
        );
	}

    public function newAction()
	{
        return $this->editAction();
	}
    
    public function editAction()
	{
        $request = $this->getRequest();
        if($request->isMethod('POST'))
        {
            return $this->saveAction();
        } else {
            $form   = $this->getForm();
            $entity = $this->getFormEntity();
            return $this->editCallback($form, $entity);
        }
	}
    

    public function saveAction()
    {
        $request                = $this->getRequest();
        $form                   = $this->getForm();
        
        if($request->isMethod('POST'))
        {
            $form->bind($request);
            $entity = $this->getFormEntity();
            
            if($form->isValid())
            {
                $em = $this->get('doctrine')->getEntityManager('symbb');
                $em->persist($entity);
                $em->flush();
                
                return $this->listAction();
            }
            else
            {
                return $this->editCallback($form, $entity);
            }
        }
    }
    
        /**
     * Entity object for the form
     * Dont load the object twice and load from this method
     * 
     * @return Object
     */
    protected function getFormEntity()
    {
        if($this->formEntity === null)
        {
            $request                = $this->getRequest();
            $entityId               = (int)$request->get('id');
            $repository             = $this->getRepository();

            if($entityId > 0)
            {
                // edit form
                $entity				= $repository->findOneById($entityId);
            }
            else
            {    
                // new form, return empty entity
                $entity_class_name  = $repository->getClassName();
                $entity				= new $entity_class_name();
            }

            $this->formEntity      = $entity;
        }
        
        return $this->formEntity;
    }

    protected function getForm()
    {
        $entity                 = $this->getFormEntity();        
        $form = $this->createForm(new $this->formClass, $entity);
        return $form;
    }
    
    
    protected function addListParams($params){
        return $params;
    }
    
    protected function addFormParams($params){
        return $params;
    }

    protected function getRepository(){
        $repo = $this->get('doctrine')->getRepository($this->entityBundle.':'.$this->entityName, 'symbb');
        return $repo;
    }
    
    protected function findListEntities(){
        $entityList = $this->getRepository()->findAll();  
        return $entityList;
    }
    
    protected function editCallback($form, $entity){
        
        $params = array(
                        'entity' => $entity,
                        'form'  => $form->createView(),
                    );
        $params = $this->addFormParams($params);
        
        return $this->render($this->templateBundle.':'.$this->entityName.':edit.html.twig', $params);
    }
}