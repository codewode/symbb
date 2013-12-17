<?
namespace SymBB\Core\ForumBundle\Twig;

class NewPostCheckExtension extends \Twig_Extension
{
    protected $securityContext;
    protected $user;
    protected $em;

    public function __construct(\Symfony\Component\Security\Core\SecurityContextInterface $securityContext, $em) {
        $this->securityContext  = $securityContext;
        $this->em               = $em;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('checkSymbbForNewPostFlag', array($this, 'checkSymbbForNewPostFlag')),
            new \Twig_SimpleFunction('checkSymbbForAnsweredPostFlag', array($this, 'checkSymbbForAnsweredPostFlag')),
        );
    }

    public function checkSymbbForNewPostFlag($element)
    {
        return $this->checkForFlag($element, 'new');
    }
    
    public function checkSymbbForAnsweredPostFlag($element)
    {
        return $this->checkForFlag($element, 'answered');
    }
    
    public function checkForFlag($element, $flag)
    {
        $check = false;
        
        if($this->user === null){
            $token                  = $this->securityContext->getToken();
            if(is_object($token)){  
                $this->user         = $token->getUser();
            }
        }
        
        if($this->user instanceof \SymBB\Core\UserBundle\Entity\UserInterface){

            if($element instanceof \SymBB\Core\ForumBundle\Entity\Topic){
                $qb     = $this->em->createQueryBuilder();
                $qb     ->select('f')
                        ->from('SymBB\Core\ForumBundle\Entity\Topic\Flag', 'f')
                        ->where("f.topic = ".$element->getId()." AND f.user = ".$this->user->getId()." AND f.flag = '".$flag."'");
                $dql    = $qb->getDql();
                $query  = $this->em->createQuery($dql);
                $result = $query->getOneOrNullResult();
        
                if($result !== null){
                    $check = true;
                }
            } else {
                throw new Exception('checkSymbbForNewPosts don´t know the object');
            }
        }
        
        return $check;
    }

    public function getName()
    {
        return 'symbb_forum_check_new_post_flag';
    }
}