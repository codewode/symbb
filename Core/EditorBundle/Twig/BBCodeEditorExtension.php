<?
namespace SymBB\Core\EditorBundle\Twig;

class BBCodeEditorExtension extends \Twig_Extension
{

    protected $env;
    protected $translator;
    protected $router;
    protected $decodaManager;
    protected $config = array();
    
    public function __construct($container, $decodaManager) {
        $this->translator       = $container->get('translator');
        $this->router           = $container->get('router');
        $config                 = $container->getParameter('symbb_config');
        $this->config           = $config;
        $this->decodaManager    = $decodaManager;
    }
    
    public function initRuntime(\Twig_Environment $environment){
        parent::initRuntime($environment);
        $this->env = $environment;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getSymcodeBBCodeEditor', array($this, 'getSymcodeBBCodeEditor'), array(
                'is_safe' => array('html')
            )),
        );
    }
    
    public function getSymcodeBBCodeEditor()
    {
        $params     = array();
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
                $params['bbcodes'][$groupName][] = array('tag' => $tag, 'name' => $tag);
            }
        }
        return $this->render('bbcode.html.twig', $params);
    }
    
    public function render($templateName, $params){
        $html = $this->env->render(
            $this->config['template']['forum'].':Editor:'.$templateName,
            $params
        );
        return $html;
    }
    
    public function getName()
    {
        return 'symbb_core_editor_bbcode';
    }
    
}