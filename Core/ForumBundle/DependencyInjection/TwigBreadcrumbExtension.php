<?
namespace SymBB\Core\ForumBundle\DependencyInjection;

class TwigBreadcrumbExtension extends \Twig_Extension
{
    protected $translator;
    protected $router;


    public function __construct($translator, $router) {
        $this->translator = $translator;
        $this->router = $router;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getSymbbBreadcrumb', array($this, 'getSymbbBreadcrumb'), array(
                'is_safe' => array('html')
            )),
        );
    }

    public function getSymbbBreadcrumb(\SymBB\Core\ForumBundle\Entity\Forum $forum)
    {
        
        $breadcrumb = array();
        
         while (is_object($forum)) {
            if(is_object($forum)){
                $uri = $this->router->generate('_symbb_forum_show', array('id' => $forum->getId(), 'name' => $forum->getSeoName()));
                $breadcrumb[] = '<a href="'.$uri.'">'.$forum->getName().'</a>';
            }
            $forum = $forum->getParent();
        };
        
        
        $home = $this->translator->trans('Overview', array(), 'symbbfrontend');
        $uri = $this->router->generate('_symbb_forum_index');
        
        $breadcrumb[] = '<a href="'.$uri.'">'.$home.'</a>';
        
        $breadcrumb = array_reverse($breadcrumb);
        
        return implode(' - ', $breadcrumb);
    }

    public function getName()
    {
        return 'symbb_forum_breadcrumb';
    }
}