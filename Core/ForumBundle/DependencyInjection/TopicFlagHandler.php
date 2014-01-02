<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Core\ForumBundle\DependencyInjection;

use \SymBB\Core\UserBundle\Entity\UserInterface;
use \SymBB\Core\ForumBundle\DependencyInjection\ForumFlagHandler;

class TopicFlagHandler extends \SymBB\Core\ForumBundle\DependencyInjection\AbstractFlagHandler
{
    /**
     * @var ForumFlagHandler 
     */
    protected $forumFlagHandler;
    
    public function setForumFlagHandler(ForumFlagHandler $handler){
        $this->forumFlagHandler = $handler;
    }

    public function insertFlag($object, $flag, UserInterface $user = null, $flushEm = true)
    {
        $ignore = false;
        
        // if we add a topic "new" flag, we need to check if the user ignore the complete forum or not
        if($flag === 'new'){
            $ignore = $this->forumFlagHandler->checkFlag($object->getForum(), 'ignore', $user);
        }
        
        if(!$ignore){
            parent::insertFlag($object, $flag, $user, $flushEm);
        }
    }
    
    public function findOne($object, UserInterface $user, $flag)
    {
        $flagObject = $this->em->getRepository('SymBBCoreForumBundle:Topic\Flag', 'symbb')->findOneBy(array(
                'topic' => $object,
                'user' => $user,
                'flag' => $flag
            ));
        
        return $flagObject;
    }
    
    public function createNewFlag($object, UserInterface $user, $flag)
    {
        $flagObject = new \SymBB\Core\ForumBundle\Entity\Topic\Flag();
        $flagObject->setTopic($object);
        $flagObject->setUser($user);
        $flagObject->setFlag($flag);
        return $flagObject;
    }

    public function checkFlag($element, $flag, UserInterface $user = null){
        $check = false;

        if(!$user){
            $user = $this->getUser();
        }
        
        if(
           $user instanceof \SymBB\Core\UserBundle\Entity\UserInterface && 
           $user->getSymbbType() === 'user'
        ){

            if($element instanceof \SymBB\Core\ForumBundle\Entity\Topic){
                
                $users  = $this->getUsersForFlag($flag, $element);
                foreach($users as $userId){
                    if(
                        $userId == $user->getId()
                    ){
                        $check = true;
                        break;
                    }
                }
                
            } else if($element instanceof \SymBB\Core\ForumBundle\Entity\Forum){
                
                $check = false;
                    
                $topics = $element->getTopics();

                foreach($topics as $topic){
                    $check = $this->checkFlag($topic, $flag, $user);
                    if($check){
                        break;
                    }
                }
                
                if(!$check){
                    $childs = $element->getChildren();
                    foreach($childs as $child){

                        $check = $this->checkFlag($child, $flag, $user);
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
    
    protected function getMemcacheKey($flag, $topic){
        $key = 'symbb.topic.'.$topic->getId().'.flag.'.$flag;
        return $key;
    }

    public function findFlagsByObjectAndFlag($object, $flag)
    {
        $flags = $this->em->getRepository('SymBBCoreForumBundle:Topic\Flag', 'symbb')->findBy(array(
                'topic' => $object,
                'flag' => $flag
            ));
        return $flags;
    }
}
