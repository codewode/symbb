<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\EventBundle\Twig;

use \SymBB\Core\EventBundle\Event\TemplatePostEvent;
use \SymBB\Core\EventBundle\Event\TemplateFormPostEvent;
use \SymBB\Core\EventBundle\Event\TemplateFormTopicEvent;
use \SymBB\Core\EventBundle\Event\TemplateDefaultEvent;

class EventExtension extends \Twig_Extension
{
    protected $container;
    protected $env;
    protected $config = array();

    public function __construct($container) {
        $this->container    = $container;
        $config             = $container->getParameter('symbb_config');
        $this->config       = $config['template'];
    }

    public function initRuntime(\Twig_Environment $environment){
        parent::initRuntime($environment);
        $this->env = $environment;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('executeSymbbPostEvent', array($this, 'executeSymbbPostEvent'), array(
                'is_safe' => array('html')
            )),
            new \Twig_SimpleFunction('executeSymbbTemplateFormTopicEvent', array($this, 'executeSymbbTemplateFormTopicEvent'), array(
                'is_safe' => array('html')
            )),
            new \Twig_SimpleFunction('executeSymbbTemplateFormPostEvent', array($this, 'executeSymbbTemplateFormPostEvent'), array(
                'is_safe' => array('html')
            )),
            new \Twig_SimpleFunction('executeSymbbTemplateEvent', array($this, 'executeSymbbTemplateEvent'), array(
                'is_safe' => array('html')
            )),
        );
    }

    public function executeSymbbTemplateFormTopicEvent($eventName, $form)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
        $event      = new TemplateFormTopicEvent($this->env, $form);
        $dispatcher->dispatch('symbb.topic.template.form.'.$eventName, $event);
        $html       = $event->getHtml();
        return $html;
    }
    
    public function executeSymbbTemplateFormPostEvent($eventName, $form)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
        $event      = new TemplateFormPostEvent($this->env, $form);
        $dispatcher->dispatch('symbb.post.template.form.'.$eventName, $event);
        $html       = $event->getHtml();
        return $html;
    }

    public function executeSymbbPostEvent($eventName, \SymBB\Core\ForumBundle\Entity\Post $post)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
        $event      = new TemplatePostEvent($this->env, $post);
        $dispatcher->dispatch('symbb.post.template.'.$eventName, $event);
        $html       = $event->getHtml();
        return $html;
    }

    public function executeSymbbTemplateEvent($eventName)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
        $event      = new TemplateDefaultEvent($this->env);
        $dispatcher->dispatch('symbb.forum.template.'.$eventName, $event);
        $html       = $event->getHtml();
        return $html;
    }
    
    public function getName()
    {
        return 'symbb_core_event';
    }
}