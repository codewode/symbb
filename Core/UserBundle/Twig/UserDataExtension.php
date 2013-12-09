<?
namespace SymBB\Core\UserBundle\Twig;

class UserDataExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getSymbbUserData', array($this, 'getSymbbUserData')),
            new \Twig_SimpleFunction('getSymbbUserAvatar', array($this, 'getSymbbUserAvatar')),
            new \Twig_SimpleFunction('getSymbbUserSignature', array($this, 'getSymbbUserSignature'))
        );
    }
    
    public function getSymbbUserData(\SymBB\Core\UserBundle\Entity\UserInterface $user)
    {
        $data = $user->getSymbbData();
        return $data;
    }
    
    public function getSymbbUserSignature(\SymBB\Core\UserBundle\Entity\UserInterface $user)
    {
        $data = $user->getSymbbData();
        // TODO BBCODE
        return $data->getSignature();
    }
    
    public function getSymbbUserAvatar(\SymBB\Core\UserBundle\Entity\UserInterface $user)
    {
        $data   = $user->getSymbbData();
        $avatar = $data->getAvatarUrl();
        if(empty($avatar)){
            $gravatar = new \SymBB\Core\UserBundle\GravatarApi();
            $check = $gravatar->exists($user->getEmail());
            if($check){
                $avatar = $gravatar->getUrl($user->getEmail());
            }
        }
        if(empty($avatar)){
            $avatar = '/bundles/symbbtemplatesimple/images/avatar/empty.gif';
        }
        return $avatar;
    }

    public function getName()
    {
        return 'symbb_user_data';
    }
}