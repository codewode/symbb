<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AcpAccessController extends Controller 
{
    protected $templateBundle;
    
    public function groupAction($step){
        $name = "groupStep".(int)$step.'Action';
        return $this->$name($step);
    }
    
    public function groupStep1Action(){
        
        $groups      = $this->get('doctrine')->getRepository('SymBBCoreUserBundle:Group', 'symbb')->findAll();
        $forumList   = $this->get('symbb.core.forum.manager')->getSelectList();
        $groupList   = array();
        foreach($groups as $group){
            $groupList[$group->getId()] = $group->getName();
        }
        
        $defaultData = array('name' => 'step1');
        $form = $this->get('form.factory')->createNamedBuilder('step1', 'form', $defaultData, array('translation_domain' => 'symbb_backend'))
            ->setAction($this->generateUrl('_symbbcoreuserbundle_group_access', array('step' => 1)))
            ->add('group', 'choice', array('choices' => $groupList, 'constraints' => array(
                    new NotBlank()
                )))
            ->add('forum', 'choice', array(
                'choices' => $forumList, 
                'multiple' => true,
                'constraints' => array(
                    new NotBlank()
                )))
            ->add('subforum', 'checkbox', array('required' => false))
            ->add('next', 'submit', array('attr' => array('class' => 'btn-success')))
            ->add('back', 'submit', array('attr' => array('class' => 'btn-danger')))
            ->getForm();
        
        $form->handleRequest($this->get('request'));
        
        if ($form->isValid()) {
            return $this->groupStep2Action();
        } else {
            return $this->render(
                $this->getTemplateBundleName().':Acp:Group\accessS1.html.twig',
                array('form' => $form->createView())
            );
        }
	}
    
    protected function groupStep2Action(){
        
        $request    = $this->get('request');
        $data       = $request->get('step1');
        $groupId    = $data['group'];
        
        if(is_array($data['forum']) && !empty($data['forum']) && $groupId > 0){
            
            $group = $this->get('doctrine')->getRepository('SymBBCoreUserBundle:Group', 'symbb')
                ->find($groupId);
            
            $defaultData = array();
            $form = $this->get('form.factory')->createNamedBuilder('step2', 'form', $defaultData, array('translation_domain' => 'symbb_backend'))
                ->add('next', 'submit', array('attr' => array('class' => 'btn-success')))
                ->add('back', 'submit', array('attr' => array('class' => 'btn-danger')))
                ->getForm();

            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {

                if($form->get('back')->isClicked()){
                    return $this->redirect($this->generateUrl('_symbbcoreuserbundle_group_access', array('step' => 1)));
                } 

            }
            
            $forumList = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
                ->findBy(array('id' => $data['forum']));

            if(isset($data['subforum']) && $data['subforum']){
               $subforum = true; 
            } else {
                $subforum = false;
            }
            

            return $this->render(
                $this->getTemplateBundleName().':Acp:Group\accessS2.html.twig',
                array(
                    'group' => $group, 
                    'forumList' => $forumList, 
                    'subforum' => $subforum ,
                    'form' => $form->createView(), 
                    'accessManager' => $this->get('symbb.core.access.manager')
                    )
            );
            
        } else {
            return $this->redirect($this->generateUrl('_symbbcoreuserbundle_group_access', array('step' => 1)));
        }
        
    }

    protected function getTemplateBundleName()
    {
        if ($this->templateBundle === null) {
            $config = $this->container->getParameter('symbb_config');
            $this->templateBundle = $config['template']['acp'];
        }
        return $this->templateBundle;

    }
    
}