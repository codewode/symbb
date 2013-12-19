<?

namespace SymBB\Core\ForumBundle\DependencyInjection;

use \SymBB\Core\UserBundle\Entity\UserInterface;
use \SymBB\Core\ForumBundle\Entity\Topic;

/**
 * @TODO for performance save the flags into memcache and dont use database ;)
 */
class TopicFlagHandler
{
    
    protected $em;
    protected $userManager;
    protected $securityContext;
    
    /**
     *
     * @var UserInterface
     */
    protected $user;

    public function __construct($em, $userManager, $securityContext) {
        $this->em               = $em;
        $this->userManager      = $userManager;
        $this->securityContext  = $securityContext;
    }
    
    public function getUser(){
        if(!is_object($this->user)){
            $this->user = $this->securityContext->getToken()->getUser();
        }
        return $this->user;
    }
    
    public function removeFlag(Topic $topic, $flag, UserInterface $user = null){
        
        if($user === null){
            $user = $this->getUser();
        }
        
        $flagObject = $this->em->getRepository('SymBBCoreForumBundle:Topic\Flag', 'symbb')->findOneBy(array(
            'topic' => $topic,
            'user' => $user,
            'flag' => $flag
        ));
        
        if(is_object($flagObject)){
            $this->em->remove($flagObject);  
            $this->em->flush();  
        }
        
    }
    
    
    /**
     * @param Topic $topic
     * @param type $flag
     */
    public function insertFlags(Topic $topic, $flag = 'new'){
        
        if(is_object($this->getUser())){
            // adding user topic flags
            $users          = $this->userManager->findUsers();
            foreach($users as $user){
                if(
                    $flag !== 'new' ||
                    $user->getId() != $this->getUser()->getId() // new flag only by "other" users
                ){

                    $this->insertFlag($topic, $flag, $user, false);
                }
            }
            $this->em->flush();  
        }
        
    }
    
    /**
     * 
     * @param \SymBB\Core\ForumBundle\Entity\Topic $topic
     * @param \SymBB\Core\UserBundle\Entity\UserInterface $user
     * @param \SymBB\Core\UserBundle\Entity\UserInterface $user
     */
    public function insertFlag(Topic $topic, $flag, UserInterface $user = null, $flushEm = true){
        if($user === null){
            $user = $this->getUser();
        }
        // only for real "users"
        if($user->getSymbbType() == 'user'){
            $flagObject = $this->em->getRepository('SymBBCoreForumBundle:Topic\Flag', 'symbb')->findOneBy(array(
                'topic' => $topic,
                'user' => $user,
                'flag' => $flag
            ));
            if(!is_object($flagObject)){
                $flagObject = new \SymBB\Core\ForumBundle\Entity\Topic\Flag();
                $flagObject->setTopic($topic);
                $flagObject->setUser($user);
                $flagObject->setFlag($flag);
                $this->em->persist($flagObject);
            }
        }
        if($flushEm){
            $this->em->flush();
        }
    }
    
    public function checkFlag($element, $flag){
        $check = false;

        
        if($this->getUser() instanceof \SymBB\Core\UserBundle\Entity\UserInterface){

            if($element instanceof \SymBB\Core\ForumBundle\Entity\Topic){
                $qb     = $this->em->createQueryBuilder();
                $qb     ->select('f')
                        ->from('SymBB\Core\ForumBundle\Entity\Topic\Flag', 'f')
                        ->where("f.topic = ".$element->getId()." AND f.user = ".$this->getUser()->getId()." AND f.flag = '".$flag."'");
                $dql    = $qb->getDql();
                $query  = $this->em->createQuery($dql);
                $result = $query->getOneOrNullResult();
        
                if($result !== null){
                    $check = true;
                }
            } else if($element instanceof \SymBB\Core\ForumBundle\Entity\Forum){
                
                $check = false;
                    
                $topics = $element->getTopics();

                foreach($topics as $topic){
                    $check = $this->checkFlag($topic, $flag);
                    if($check){
                        break;
                    }
                }
                
                if(!$check){
                    $childs = $element->getChildren();
                    foreach($childs as $child){

                        $check = $this->checkFlag($child, $flag);
                        if($check){
                            break;
                        }
                    }
                }
                
            } else {
                throw new Exception('checkSymbbForNewPosts don´t know the object');
            }
        }
        
        return $check;
    }
}
