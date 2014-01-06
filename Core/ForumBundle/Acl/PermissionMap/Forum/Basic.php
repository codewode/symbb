<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Core\ForumBundle\Acl\PermissionMap\Forum;

use \Symfony\Component\Security\Acl\Permission\BasicPermissionMap;
use \SymBB\Core\ForumBundle\Acl\MaskBuilder\Forum\Basic as MaskBuilder;
use \SymBB\Core\ForumBundle\Acl\Manager;

class Basic extends BasicPermissionMap {
    
    const PERMISSION_VIEW        = 'VIEW';
    const PERMISSION_CREATE_TOPIC= 'CREATE_TOPIC';
    const PERMISSION_CREATE_POST = 'CREATE_POST';
    const PERMISSION_EDIT_TOPIC  = 'EDIT_TOPIC';
    const PERMISSION_EDIT_POST   = 'EDIT_POST';
    const PERMISSION_DELETE_TOPIC= 'DELETE_TOPIC';
    const PERMISSION_DELETE_POST = 'DELETE_POST';
    const PERMISSION_MASTER      = 'MASTER';
    const PERMISSION_OWNER       = 'OWNER';
    
    public function __construct()
    {
        $this->map = array(
            self::PERMISSION_VIEW => array(
                MaskBuilder::MASK_VIEW,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_CREATE_TOPIC => array(
                MaskBuilder::CODE_CREATE_TOPIC,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_CREATE_POST => array(
                MaskBuilder::MASK_CREATE_POST,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_EDIT_TOPIC => array(
                MaskBuilder::MASK_EDIT_TOPIC,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_EDIT_POST => array(
                MaskBuilder::MASK_EDIT_POST,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_DELETE_TOPIC => array(
                MaskBuilder::MASK_DELETE_TOPIC,
                MaskBuilder::MASK_MASTER,
                MaskBuilder::MASK_OWNER,
            ),

            self::PERMISSION_DELETE_POST => array(
                MaskBuilder::MASK_DELETE_POST,
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
    
    public function getParentMap(){
        return array(
            self::PERMISSION_CREATE_TOPIC => array(
                Manager::SYMBB_POST_BASIC
            )
        );
    }
    
}