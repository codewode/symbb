<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Extension\SurveyBundle\EventListener;

class FormListener
{
    
    public function addPostFormPart($event)
    {
       $builder = $event->getBuilder();
       $builder->add('surveyQuestion', 'text', array('mapped' => false, 'label' => 'The question', 'attr' => array()));
       $builder->add('surveyAnswers', 'textarea', array('mapped' => false, 'label' => 'The answers', 'attr' => array(
           'placeholder' => 'Give each answer on a separate line'
           )));
       $builder->add('surveyChoices', 'number', array('mapped' => false, 'data' => 1 ,'label' => 'Options per user', 'attr' => array()));
       $builder->add('surveyChoiceChanges', 'checkbox', array('mapped' => false ,'label' => 'Allow changing the voting', 'attr' => array()));
    }
    
}