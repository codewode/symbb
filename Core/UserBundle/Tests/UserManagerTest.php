<?php

namespace SymBB\Core\UserBundle\Tests;

use \SymBB\Core\UserBundle\DependencyInjection\UserManager;

class UserManagerTest extends \PHPUnit_Framework_TestCase
{
   /**
    *
    * @var UserManager 
    */
    protected $userManager;
    
    public function setUp()
    {
        

        $container  = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $om         = $this->getMock('Doctrine\ORM\EntityManager');
        $security   = $this->getMock('SymBB\Core\UserBundle\Entity\UserInterface');
        $config     = array('usermanager' => array('user_class' => 'SymBB\Core\UserBundle\Entity\User'));
        
        $container->expects($this->any())
            ->method('get')
            ->with('doctrine.orm.symbb_entity_manager')
            ->will($this->returnValue($om));

        $container->expects($this->any())
            ->method('get')
            ->with('security.encoder_factory')
            ->will($this->returnValue($security));

        $container->expects($this->any())
            ->method('getParameter')
            ->with('symbb_config')
            ->will($this->returnValue($config));
        
        $this->userManager = new UserManager($container);
    }

    public function testDeleteUser()
    {
        $user = $this->userManager->createUser();
        $this->assertInstanceOf('SymBB\Core\UserBundle\Entity\User', $user);
    }
}