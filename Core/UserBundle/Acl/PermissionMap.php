<?php

namespace SymBB\Core\UserBundle\Acl;

use \Symfony\Component\Security\Acl\Permission\BasicPermissionMap;

class PermissionMap extends BasicPermissionMap {
    
    const PERMISSION_VIEW        = 'VIEW';
    const PERMISSION_EDIT        = 'EDIT';
    const PERMISSION_CREATE      = 'CREATE';
    const PERMISSION_DELETE      = 'DELETE';
    const PERMISSION_WRITE       = 'WRITE';
    const PERMISSION_OPERATOR    = 'OPERATOR';
    const PERMISSION_MASTER      = 'MASTER';
    const PERMISSION_OWNER       = 'OWNER';
    
    public function __construct()
    {
        $this->map = array(
            self::PERMISSION_VIEW => array(
                MaskBuilder::MASK_VIEW,
                MaskBuilder::MASK_EDIT,
                MaskBuilder::MASK_OPERATOR,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_EDIT => array(
                MaskBuilder::MASK_EDIT,
                MaskBuilder::MASK_OPERATOR,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_CREATE => array(
                MaskBuilder::MASK_CREATE,
                MaskBuilder::MASK_OPERATOR,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_DELETE => array(
                MaskBuilder::MASK_DELETE,
                MaskBuilder::MASK_OPERATOR,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_WRITE => array(
                MaskBuilder::MASK_WRITE,
                MaskBuilder::MASK_OPERATOR,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_OPERATOR => array(
                MaskBuilder::MASK_OPERATOR,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_MASTER => array(
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_OWNER => array(
                MaskBuilder::MASK_OWNER,
            ),
        );
    }
    
    public function getMasks($permission, $object = null) {
        return parent::getMasks($permission, $object);
    }
    
}