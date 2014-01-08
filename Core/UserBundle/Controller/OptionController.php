<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\UserBundle\Controller;

use SymBB\Core\UserBundle\Form\Type\Option;

class OptionController extends \SymBB\Core\SystemBundle\Controller\AbstractController 
{

    public function indexAction(){
        
        $user = $this->getUser();
        $data = $user->getSymbbData();
        
        $avatar = $data->getAvatar();
        
        if(empty($avatar)){
            $gravatar   = new \SymBB\Core\UserBundle\GravatarApi();
            $check      = $gravatar->exists($user->getEmail());
            if($check){
                $avatar     = $gravatar->getUrl($user->getEmail());
                $data->setAvatar($avatar);
            }
        }
        
        $form = $this->createForm(new Option(), $data);
        
        $form->handleRequest($this->get('request'));
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager('symbb');
            $em->persist($data);
            $em->flush();
        }
        
        return $this->render(
            $this->getTemplateBundleName('forum').':User:options.html.twig',
            array('form' => $form->createView(), 'avatar' => $avatar)
        );
	}
    
}