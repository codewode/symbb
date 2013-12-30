<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\SystemBundle\DependencyInjection;

class TwigTemplateConfigExtension extends \Twig_Extension
{
    protected $config;

    public function __construct($container) {
        $config         = $container->getParameter('symbb_config');
        $this->config   = $config['template'];
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getSymbbTemplateConfig', array($this, 'getSymbbTemplateConfig'))
        );
    }
    
    public function getSymbbTemplateConfig($config)
    {
        return $this->config[$config];
    }

    public function getName()
    {
        return 'symbb_config_template';
    }
}