<?php

namespace SymBB\Core\UserBundle\Acl;

use \Symfony\Component\Security\Acl\Permission\MaskBuilder as BaseMaskBuilder;

class MaskBuilder extends BaseMaskBuilder {
    
    const MASK_VIEW         = 1;          // 1 << 0
    const MASK_CREATE       = 2;          // 1 << 1
    const MASK_EDIT         = 4;          // 1 << 2
    const MASK_DELETE       = 8;          // 1 << 3
    const MASK_WRITE        = 16;         // 1 << 4
    const MASK_OPERATOR     = 32;         // 1 << 5
    const MASK_MASTER       = 64;         // 1 << 6
    const MASK_OWNER        = 128;        // 1 << 7
    const MASK_IDDQD        = 1073741823; // 1 << 0 | 1 << 1 | ... | 1 << 30

    const CODE_VIEW         = 'V';
    const CODE_CREATE       = 'C';
    const CODE_EDIT         = 'E';
    const CODE_DELETE       = 'D';
    const CODE_WRITE        = 'W';
    const CODE_OPERATOR     = 'O';
    const CODE_MASTER       = 'M';
    const CODE_OWNER        = 'N';
    
}