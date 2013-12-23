<?php

namespace SymBB\Post\BBCodeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BBEditorType extends AbstractType
{
    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'bbeditor';
    }
}