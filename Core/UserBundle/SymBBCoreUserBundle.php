<?php

namespace SymBB\Core\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymBBCoreUserBundle extends Bundle
{
    
    public function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new Security\GuestListenerCompilerPass());
    }
    
}
