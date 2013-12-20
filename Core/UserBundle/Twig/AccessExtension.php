<?
namespace SymBB\Core\UserBundle\Twig;

use \SymBB\Core\UserBundle\DependencyInjection\UserAccess;

class AccessExtension extends \Twig_Extension
{
    /**
     *
     * @var UserAccess 
     */
    protected $userAccess;
    
    public function __construct(UserAccess $userAccess) {
        $this->userAccess = $userAccess;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('hasSymbbAccess', array($this, 'hasSymbbAccess'))
        );
    }
    
    public function hasSymbbAccess($permissionString, $element)
    {
        $permissionString   = strtoupper($permissionString);
        $permission         = '\SymBB\Core\UserBundle\Acl\PermissionMap::PERMISSION_'.$permissionString;
        $permission         = constant($permission);
        $this->userAccess->addAccessCheck($permission, $element);
        $access             = $this->userAccess->hasAccess();
        return $access;
    }
    

    public function getName()
    {
        return 'symbb_user_access';
    }
}