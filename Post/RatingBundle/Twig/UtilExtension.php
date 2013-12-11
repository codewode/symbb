<?
namespace SymBB\Post\RatingBundle\Twig;

class UtilExtension extends \Twig_Extension
{
    protected $em;
    
    protected $securityContect;
    
    public function __construct($em, $securityContext) {
        $this->em               = $em;
        $this->securityContect  = $securityContext;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getSymbbLikeCount', array($this, 'getSymbbLikeCount')),
            new \Twig_SimpleFunction('getSymbbDislikeCount', array($this, 'getSymbbDislikeCount')),
            new \Twig_SimpleFunction('getLikeCssClass', array($this, 'getLikeCssClass')),
            new \Twig_SimpleFunction('getDislikeCssClass', array($this, 'getDislikeCssClass'))
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
    
    public function getLikeCssClass(\SymBB\Core\ForumBundle\Entity\Post $post){
        $css = '';
        if(is_object($this->getCurrentUser())){
            $found = $this->em->getRepository('SymBBPostRatingBundle:Like', 'symbb')
            ->findOneBy(array('user' => $this->getCurrentUser(), 'post' => $post));
            if(is_object($found)){
                $css = 'btn-success';
            }
        }
        return $css;
    }
    
    public function getDislikeCssClass(\SymBB\Core\ForumBundle\Entity\Post $post){
        $css = '';
        if(is_object($this->getCurrentUser())){
            $found = $this->em->getRepository('SymBBPostRatingBundle:Dislike', 'symbb')
            ->findOneBy(array('user' => $this->getCurrentUser(), 'post' => $post));
            if(is_object($found)){
                $css = 'btn-success';
            }
        }
        return $css;
    }
    
    public function getCurrentUser(){
        $user   = null;
        $token  = $this->securityContect->getToken();
        if(is_object($token)){
            $user  = $token->getUser();
        }
        return $user;
    }
    
    public function getName()
    {
        return 'symbb_post_rating_util';
    }
}