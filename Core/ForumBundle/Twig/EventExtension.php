<?
namespace SymBB\Core\ForumBundle\Twig;

use \SymBB\Core\ForumBundle\Event\PostTemplateEvent;

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
            new \Twig_SimpleFunction('executeSymbbEvent', array($this, 'executeSymbbEvent'), array(
                'is_safe' => array('html')
            )),
        );
    }

    public function executeSymbbEvent($eventName, \SymBB\Core\ForumBundle\Entity\Post $post)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
        $event      = new PostTemplateEvent($this->env, $post);
        $dispatcher->dispatch('symbb.forum.'.$eventName, $event);
        $html       = $event->getHtml();
        return $html;
    }

    public function getName()
    {
        return 'symbb_forum_posttemplate_event';
    }
}