<?
namespace SymBB\Core\EventBundle\Twig;

use \SymBB\Core\EventBundle\Event\PostTemplateEvent;
use \SymBB\Core\EventBundle\Event\DefaultTemplateEvent;

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
            new \Twig_SimpleFunction('executeSymbbTemplateEvent', array($this, 'executeSymbbTemplateEvent'), array(
                'is_safe' => array('html')
            )),
        );
    }

    public function executeSymbbPostEvent($eventName, \SymBB\Core\ForumBundle\Entity\Post $post)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
        $event      = new PostTemplateEvent($this->env, $post);
        $dispatcher->dispatch('symbb.forum.template.'.$eventName, $event);
        $html       = $event->getHtml();
        return $html;
    }

    public function executeSymbbTemplateEvent($eventName)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
        $event      = new DefaultTemplateEvent($this->env);
        $dispatcher->dispatch('symbb.forum.template.'.$eventName, $event);
        $html       = $event->getHtml();
        return $html;
    }
    
    public function getName()
    {
        return 'symbb_core_event';
    }
}