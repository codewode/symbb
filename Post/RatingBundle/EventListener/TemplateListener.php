<?php

namespace SymBB\Post\RatingBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class TemplateListener
{
    public function afterText(\SymBB\Core\ForumBundle\DependencyInjection\PostTemplateEvent $event)
    {
        $post = $event->getPost();
        $event->render('SymBBPostRatingBundle:Post:rating.html.twig', array('object' => $post));
    }
}