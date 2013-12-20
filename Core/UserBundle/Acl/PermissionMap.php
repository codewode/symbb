<?php

namespace SymBB\Core\UserBundle\Acl;

use \Symfony\Component\Security\Acl\Permission\BasicPermissionMap;

class PermissionMap extends BasicPermissionMap {
    
    public function getMasks($permission, $object = null) {
        return parent::getMasks($permission, $object);
    }
    
}