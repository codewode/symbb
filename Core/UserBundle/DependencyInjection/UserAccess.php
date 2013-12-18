<?

namespace SymBB\Core\UserBundle\DependencyInjection;

use \Symfony\Component\Security\Core\SecurityContextInterface;
use \SymBB\Core\UserBundle\Entity\UserInterface;
use \Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use \Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use \Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use \Symfony\Component\Security\Acl\Exception\NoAceFoundException;
use SymBB\Core\UserBundle\Acl\PermissionMap;

class UserAccess
{
    protected $em;
    
    /**
     *
     * @var \Symfony\Component\Security\Acl\Model\AclProviderInterface 
     */
    protected $aclProvider;
    
    /**
     * @var UserInterface
     */
    protected $user;
    
    protected $accessChecks = array();


    /**
     * 
     * @param type $em
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    public function __construct($em, SecurityContextInterface $securityContext, $aclProvider) {
        $this->em = $em;
        $this->securityContext = $securityContext;
        $this->aclProvider = $aclProvider;
    }
    
    /**
     * get the current user or null if the user is not logged in
     * @return UserInterface|null
     */
    public function getUser(){
        if($this->user === null){
            $token                  = $this->securityContext->getToken();
            if(is_object($token)){  
                $this->user         = $token->getUser();
            }
        }
        return $this->user;
    }
    
    /**
     * check if the user are logged in or not
     * @return boolean
     */
    public function isAnonymous(){
        if(!is_object($this->user)) {
            return true;
        }
        return false;
    }
    
    public function grantAccess($mask, $object, UserInterface $user = null){
        $objectIdentity     = ObjectIdentity::fromDomainObject($object);
        try {
            $acl            = $this->aclProvider->findAcl($objectIdentity);
        } catch (AclNotFoundException $exc) {
            $acl            = $this->aclProvider->createAcl($objectIdentity);
        }
        $user               = $this->getUser($user);
        $securityIdentity   = UserSecurityIdentity::fromAccount($user);
        // or class with SecurityIdentityInterface implementation...
        $acl->insertObjectAce($securityIdentity, $mask);
        $this->aclProvider->updateAcl($acl);
    }
    
    /**
     * 
     * @param object $object
     * @param int $mask PermissionMap::PERMISSION_EDIT, MaskBuilder::PERMISSION_VIEW,...
     * @param \SymBB\Core\UserBundle\DependencyInjection\SecurityIdentityInterface $indentity
     */
    public function addAccessCheck($permission, $object, SecurityIdentityInterface $indentity = null){
        if(!is_object($indentity)){
            $user               = $this->getUser();
            $indentity          = UserSecurityIdentity::fromAccount($user);
        }
        if(is_object($indentity)){
            $this->accessChecks[] = array(
                'object'        => $object,
                'permission'    => $permission,
                'indentity'     => $indentity
            );
        }
    }
    
    public function hasAccess(){
        $access             = false;
        foreach($this->accessChecks as $data){
            $object             = $data['object'];
            $indentity          = $data['indentity'];
            $permission         = $data['permission'];
            $objectIdentity     = ObjectIdentity::fromDomainObject($object);
            try {
                $permissionMap      = new PermissionMap();
                $masks              = $permissionMap->getMasks($permission, $object);
                $acl                = $this->aclProvider->findAcl($objectIdentity);
                $access             = $acl->isGranted($masks, array($indentity)); 
            } catch (NoAceFoundException $exc) {
                $access             = false;
            } catch (AclNotFoundException $exc) {
                $access             = false;
            }
            if($access === true){
                break;
            }
        }
        $this->accessChecks = array();
        return $access;
    }
    
    public function checkAccess(){
        $access = $this->hasAccess();
        if (false === $access) {
            $this->throwExceptionForLastCheck();
        }
        return $access;
    }
    
    public function throwExceptionForLastCheck(){
        throw new \Exception('no access');
    }
}
