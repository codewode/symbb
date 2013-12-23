<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Post\BBCodeBundle\DependencyInjection;

class BBCodeManager {
    
    protected $decodaManager;
    protected $translator;


    public function __construct($decodaManager, $translator) {
        $this->decodaManager = $decodaManager;
        $this->translator = $translator;
    }
    
    /**
     * get a list of grouped BBCodes
     * @return array
     */
    public function getBBCodes(){
        $bbcodes     = array();
        $decoda    = $this->decodaManager->get('filters', 'default');
        $filters    = $decoda->getFilters();
        foreach($filters as $filterKey => $filter){
            $tags = $filter->getTags();
            $groupName = $this->translator->trans($filterKey, array(), 'symbb_bbcode_groups');
            foreach($tags as $tag => $conf){
                if(
                    (
                        $filterKey == 'email' && $tag == 'mail'
                    ) ||
                    (
                        $filterKey == 'url' && $tag == 'link'
                    ) ||
                    (
                        $filterKey == 'image' && $tag == 'img'
                    )
                ){
                    // double bbcode...
                    continue;
                }
                $bbcodes[$groupName][] = array('tag' => $tag, 'name' => $tag);
            }
        }
        return $bbcodes;
    }
}