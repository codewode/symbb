<?php

namespace SymBB\Post\BBCodeBundle\Twig;

class BBCodeManagerExtension extends \Twig_Extension
{
    /**
     *
     * @var \SymBB\Post\BBCodeBundle\DependencyInjection\BBCodeManager 
     */
    protected $bbcodeManager;

    public function __construct($bbcodeManager) {
        $this->bbcodeManager   = $bbcodeManager;
    }
    
    public function getFunctions()
    {
        return array(
                new \Twig_SimpleFunction('getSymbbBBCodes', array($this, 'getSymbbBBCodes')
            )
        );
    }
    
    public function getSymbbBBCodes(){
        return $this->bbcodeManager->getBBCodes();
    }
    
    public function getName(){
        return 'symbb_post_bbcode_manager';
    }
}