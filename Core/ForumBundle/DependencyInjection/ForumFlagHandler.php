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
use \SymBB\Core\ForumBundle\Entity\Forum;

class ForumFlagHandler extends \SymBB\Core\ForumBundle\DependencyInjection\AbstractFlagHandler
{
    
    public function findOne($object, UserInterface $user, $flag)
    {
        $flagObject = $this->em->getRepository('SymBBCoreForumBundle:Forum\Flag', 'symbb')->findOneBy(array(
                'forum' => $object,
                'user' => $user,
                'flag' => $flag
            ));
        
        return $flagObject;
    }
    
    public function createNewFlag($object, UserInterface $user, $flag)
    {
        $flagObject = new \SymBB\Core\ForumBundle\Entity\Forum\Flag();
        $flagObject->setForum($object);
        $flagObject->setUser($user);
        $flagObject->setFlag($flag);
        return $flagObject;
    }
    
    protected function getMemcacheKey($flag, $forum){
        $key = 'symbb.forum.'.$forum->getId().'.flag.'.$flag;
        return $key;
    }

    public function findFlagsByObjectAndFlag($object, $flag)
    {
        $flags = $this->em->getRepository('SymBBCoreForumBundle:Forum\Flag', 'symbb')->findBy(array(
                'forum' => $object,
                'flag' => $flag
            ));
        return $flags;
    }
}
