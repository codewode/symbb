<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\SystemBundle\DependencyInjection;

use \Symfony\Component\Security\Core\SecurityContextInterface;
use \SymBB\Core\UserBundle\Entity\UserInterface;
use \Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use \Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use \Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use \Symfony\Component\Security\Acl\Exception\NoAceFoundException;
use \Symfony\Component\Security\Core\Util\ClassUtils;
use \Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;
use \Symfony\Component\Security\Acl\Model\AclProviderInterface;

class AccessManager {
    
    /**
     * @var \Doctrine\ORM\EntityManager 
     */
    protected $em;
    
    /**
     *
     * @var AclProviderInterface 
     */
    protected $aclProvider;
    
    /**
     * @var UserInterface
     */
    protected $user;
    
    protected $accessChecks     = array();
    protected $aclManager       = array();

    /**
     * 
     * @param type $em
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    public function __construct($em, SecurityContextInterface $securityContext, $aclProvider) {
        $this->em               = $em;
        $this->securityContext  = $securityContext;
        $this->aclProvider      = $aclProvider;
    }
    
    public function addAclManager($aclManager){
        $this->aclManager[] = $aclManager;
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
     * @param array $mask
     * @param object $object
     * @param \SymBB\Core\UserBundle\Entity\UserInterface $identity
     * @throws Exception
     */
    public function grantAccess($masks, $object, $identity = null){
        
        $objects = array();
        foreach((array)$masks as $mask){
            foreach($this->aclManager as $aclManager){
                $mask   = explode('#', $mask);
                $prefix = reset($mask).'#';
                $mask   = end($mask);
                if($aclManager->checkPrefix($prefix)){
                    $domainObject                           = $aclManager->createDomainObject($prefix, $object);
                    if(is_object($object)){
                        $objects[$prefix]['object']         = $domainObject;
                        $objects[$prefix]['masks'][]        = $mask;
                        $objects[$prefix]['maskBuilder']    = $aclManager->getMaskBuilder($prefix);
                    }
                    break;
                }
            }
        }
   
        foreach($objects as $objectData){
            
            $masks              = (array)$objectData['masks'];
            $builder            = $objectData['maskBuilder'];
            $object             = $objectData['object'];
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

            foreach((array)$masks as $currMask){
                $builder->add($currMask);
            }
            $finalMask = $builder->get();
            
            $acl->insertObjectAce($securityIdentity, $finalMask);

            $this->aclProvider->updateAcl($acl);
        }
        
    }
    
    /**
     * 
     * @param object $object
     * @param int $mask PermissionMap::PERMISSION_EDIT, MaskBuilder::PERMISSION_VIEW,...
     * @param SecurityIdentityInterface $indentity
     */
    public function addAccessCheck($permission, $object, $indentityObject = null, $checkAdditional = true){
        
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
        
        foreach($this->aclManager as $aclManager){
            $checks             = $aclManager->createAccessChecks($permission, $object, $indentity);
            $this->accessChecks += $checks;
            // check if we need some additional acces checks for the given access
            if($checkAdditional){
               $additionalChecks = $aclManager->getAdditionalAccessCheck($permission, $object);
                foreach($additionalChecks as $additional){
                    $this->addAccessCheck($additional['permission'], $additional['object'], $indentityObject, false);
                } 
            }
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
            $permissionMap      = $data['permissionMap'];
            $permission         = strtoupper($data['permission']);
            $objectIdentity     = ObjectIdentity::fromDomainObject($object);
            
            try {
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
