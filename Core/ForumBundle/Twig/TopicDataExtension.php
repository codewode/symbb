<?
namespace SymBB\Core\ForumBundle\Twig;

class TopicDataExtension extends \Twig_Extension
{

    protected $paginator;
    protected $em;

    public function __construct($em, $paginator) {
        $this->paginator  = $paginator;
        $this->em         = $em;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getTopicPagination', array($this, 'getTopicPagination')),
        );
    }
    
    public function getTopicPagination($forum){
        
        $qb     = $this->em->createQueryBuilder();
        $qb     ->select('t')
                ->from('SymBB\Core\ForumBundle\Entity\Topic', 't')
                ->where('t.forum = '.$forum->getId())
                ->orderBy('p.created', 'ASC');
        $dql    = $qb->getDql();
        $query  = $this->em->createQuery($dql);

        $pagination = $this->paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            $forum->getEntriesPerPage()/*limit per page*/
        );
        
        $pagination->setTemplate($this->getTemplateBundleName('forum').':Pagination:pagination.html.twig');
        
        return $pagination;
    }
        
        
    public function getName()
    {
        return 'symbb_forum_topic_data';
    }
        
}