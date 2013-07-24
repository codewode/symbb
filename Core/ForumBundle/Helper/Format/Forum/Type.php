<?php

namespace SymBB\Core\ForumBundle\Helper\Format\Forum;

use Seyon\EAjaxCRUDBundle\Helper\Format\FormatInterface;

class Type implements FormatInterface {
    
    protected $translator;
    
    public function setTranslator($translator){
        $this->translator = $translator;
    }
    
    public function format($value){
        switch ($value) {
            case 'forum':
                $value = $this->translator->trans('Forum', array(), 'options');
                break;
            case 'link':
                $value = $this->translator->trans('Link', array(), 'options');
                break;
            case 'category':
                $value = $this->translator->trans('Kategorie', array(), 'options');
                break;
            default:
                $value = $this->translator->trans('Unbekannt', array(), 'options');
                break;
        }
        return $value;
    }
    
}