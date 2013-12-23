<?php

namespace SymBB\FOS\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymBBFOSUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
