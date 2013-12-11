<?
namespace SymBB\Post\RatingBundle\Twig;

class UtilExtension extends \Twig_Extension
{
    protected $em;

    public function __construct($em) {
        $this->em       = $em;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getSymbbLikeCount', array($this, 'getSymbbLikeCount')),
            new \Twig_SimpleFunction('getSymbbDislikeCount', array($this, 'getSymbbDislikeCount')),
        );
    }

    public function getSymbbLikeCount(\SymBB\Core\ForumBundle\Entity\Post $post)
    {
        $likes = $this->em->getRepository('SymBBPostRatingBundle:Like', 'symbb')
            ->findBy(array('post' => $post));
        $count = count($likes);
        return $count;
    }

    public function getSymbbDislikeCount(\SymBB\Core\ForumBundle\Entity\Post $post)
    {
        $likes      = $this->em->getRepository('SymBBPostRatingBundle:Dislike', 'symbb')
            ->findBy(array('post' => $post));
        $count = count($likes);
        return $count;
    }
    
    public function getName()
    {
        return 'symbb_post_rating_util';
    }
}