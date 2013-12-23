<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Extension\RatingBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class TemplateListener
{
    public function afterText(\SymBB\Core\EventBundle\Event\PostTemplateEvent $event)
    {
        $post = $event->getPost();
        $event->render('SymBBExtensionRatingBundle:Post:rating.html.twig', array('object' => $post));
    }
    
    public function topicStylesheets(\SymBB\Core\EventBundle\Event\DefaultTemplateEvent $event){
        $event->render('SymBBExtensionRatingBundle::stylesheets.html.twig', array());
    }
}