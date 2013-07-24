<?php

namespace SymBB\Core\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Übersicht', array('route' => '_symbb_acp'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Foren', array('route' => '_symbb_acp'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Benutzer', array('route' => '_symbb_acp'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Aussehen', array('route' => '_symbb_acp'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Einstellungen', array('route' => '_symbb_acp'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Erweiterungen', array('route' => '_symbb_acp'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Wartung', array('route' => '_symbb_acp'))->setExtra('translation_domain', 'menu');
        $menu->addChild('Forum anzeigen', array('route' => '_symbb_acp'))->setExtra('translation_domain', 'menu');

        return $menu;
    }
}