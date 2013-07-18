<?php

namespace SymBB\Core\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Übersicht', array('route' => '_symbb_acp'));
        $menu->addChild('Foren', array('route' => '_symbb_acp'));
        $menu->addChild('Benutzer', array('route' => '_symbb_acp'));
        $menu->addChild('Aussehen', array('route' => '_symbb_acp'));
        $menu->addChild('Einstellungen', array('route' => '_symbb_acp'));
        $menu->addChild('Erweiterungen', array('route' => '_symbb_acp'));
        $menu->addChild('Wartung', array('route' => '_symbb_acp'));
        $menu->addChild('Forum anzeigen', array('route' => '_symbb_acp'));

        return $menu;
    }
}