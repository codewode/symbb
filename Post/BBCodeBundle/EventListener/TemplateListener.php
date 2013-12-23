<?php

namespace SymBB\Post\BBCodeBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class TemplateListener
{
    
    public function stylesheets(\SymBB\Core\EventBundle\Event\DefaultTemplateEvent $event)
    {
        $event->render('SymBBPostBBCodeBundle::stylesheets.html.twig', array());
    }
    
    public function javascripts(\SymBB\Core\EventBundle\Event\DefaultTemplateEvent $event)
    {
        $event->render('SymBBPostBBCodeBundle::javascripts.html.twig', array());
    }
}