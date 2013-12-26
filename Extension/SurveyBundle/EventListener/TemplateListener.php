<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Extension\SurveyBundle\EventListener;

class TemplateListener
{

    public function addPostTab($event){
        $event->render('SymBBExtensionSurveyBundle:Post:tab.html.twig', array('form' => $event->getForm()));
    }

    public function addPostTabContent($event){
        $event->render('SymBBExtensionSurveyBundle:Post:tabcontent.html.twig', array('form' => $event->getForm()));
    }

    public function addPostFormPart($event){
        
    }
    
    
    public function addTopicTab($event){
        $this->addPostTab($event);
    }

    public function addTopicTabContent($event){
        $event->render('SymBBExtensionSurveyBundle:Topic:tabcontent.html.twig', array('form' => $event->getForm()));
    }

    public function addTopicFormPart($event){
        
    }
}