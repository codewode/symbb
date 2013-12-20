<?

namespace SymBB\Core\UserBundle\DependencyInjection;

use \Symfony\Component\Security\Core\SecurityContextInterface;
use \SymBB\Core\UserBundle\Entity\UserInterface;
use \Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use \Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use \Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use \Symfony\Component\Security\Acl\Exception\NoAceFoundException;
use \SymBB\Core\UserBundle\Acl\PermissionMap;
use \Symfony\Component\Security\Core\Util\ClassUtils;
use \Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

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
    
    /**
     * 
     * @param array|int $mask
     * @param object $object
     * @param \SymBB\Core\UserBundle\Entity\UserInterface $identity
     * @throws Exception
     */
    public function grantAccess($mask, $object, $identity = null){
  
        $objectIdentity     = ObjectIdentity::fromDomainObject($object);
        
        try {
            $acl            = $this->aclProvider->findAcl($objectIdentity);
        } catch (AclNotFoundException $exc) {
            $acl            = $this->aclProvider->createAcl($objectIdentity);
        }
        
        if($identity === null){
            $identity               = $this->getUser();
        }
        
        if($identity instanceof UserInterface){
            $securityIdentity   = UserSecurityIdentity::fromAccount($identity);
        } else if($identity instanceof \SymBB\Core\UserBundle\Entity\Group){
            $securityIdentity   = $this->getUserGroupIdentity($identity);
        } else {
            throw new Exception('Unknown Security Indentity for '.ClassUtils::getRealClass($identity));
        }
        
        // or class with SecurityIdentityInterface implementation...
        foreach((array)$mask as $currMask){
            $acl->insertObjectAce($securityIdentity, $currMask);
        }
        
        $this->aclProvider->updateAcl($acl);
    }
    
    /**
     * 
     * @param object $object
     * @param int $mask PermissionMap::PERMISSION_EDIT, MaskBuilder::PERMISSION_VIEW,...
     * @param SecurityIdentityInterface $indentity
     */
    public function addAccessCheck($permission, $object, $indentityObject = null){
        
        $indentity = null;
        
        if(!is_object($indentityObject)){
            $indentityObject    = $this->getUser();
        }
        
        if($indentityObject instanceof UserInterface){
            $indentity          = UserSecurityIdentity::fromAccount($indentityObject);
            $groups             = $indentityObject->getGroups();
            foreach($groups as $group){
                $this->addAccessCheck($permission, $object, $group);
            }
        } else if($indentityObject instanceof \SymBB\Core\UserBundle\Entity\Group){
            $indentity          = $this->getUserGroupIdentity($indentityObject);
        }
        
        if(is_object($indentity)){
            $this->accessChecks[] = array(
                'object'        => $object,
                'permission'    => $permission,
                'indentity'     => $indentity
            );
        }
        
        // If we check a "post" than we must also check the "forum" because a MOD can have acces to "edit" / "delete" the Forum 
        // is he has also acces to the "posts"
        if(
            $object instanceof \SymBB\Core\ForumBundle\Entity\Post
        ){
            $this->addAccessCheck($permission, $object->getTopic()->getForum(), $indentityObject);
        }
    }
    
    protected function getUserGroupIdentity($group){
        $indentity          = new UserSecurityIdentity($group->getId(), ClassUtils::getRealClass($group));
        return $indentity;
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
                //$access             = false;
            } catch (AclNotFoundException $exc) {
                //$access             = false;
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
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }
}
