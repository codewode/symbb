<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Post\BBCodeBundle\EventListener;

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