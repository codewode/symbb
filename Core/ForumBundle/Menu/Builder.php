<?php

namespace SymBB\Core\ForumBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function subMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Übersicht', array('route' => '_symbb_acp'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Neues Forum', array('route' => '_symbb_acp_forum_new'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Neue Kategorie', array('route' => '_symbb_acp_forum_new_category'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Neuer Link', array('route' => '_symbb_acp_forum_new_link'))->setExtra('translation_domain', 'menu');

        return $menu;
    }
}