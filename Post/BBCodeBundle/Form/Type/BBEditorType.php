<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

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