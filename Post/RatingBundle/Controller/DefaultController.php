<?

namespace SymBB\post\RatingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController  extends Controller 
{
    
    public function ratePostAction($name, $id, $like){
        
        $post = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Post', 'symbb')
            ->find($id);
        
        $user   = $this->getUser();
        
        if($like === 'like'){
            $this->addPostLike($post, $user);
        } else {
            $this->addPostLike($post, $user, true);
        }
        
        $response = $this->forward('SymBBCoreForumBundle:FrontendTopic:show', array(
            'name'  => '',
            'id' => $post->getTopic()->getId(),
        ));
        
        return $response;
    }
    
    protected function addPostLike(
            \SymBB\Core\ForumBundle\Entity\Post $post, 
            \SymBB\Core\UserBundle\Entity\UserInterface $user, 
            $asDislike = false
    ){
        
        $likes      = $this->get('doctrine')->getRepository('SymBBPostRatingBundle:Like', 'symbb')
            ->findBy(array('post' => $post, 'user' => $user));
        
        $dislikes   = $this->get('doctrine')->getRepository('SymBBPostRatingBundle:Dislike', 'symbb')
            ->findBy(array('post' => $post, 'user' => $user));
        
        $myLikes    = array();
        $myDislikes = array();
        
        $em         = $this->get('doctrine')->getManager('symbb');
        
        foreach($likes as $like){
            if($like->getUser()->getId() === $user->getId()){
                $myLikes[] = $like;
            }
        }
        
        foreach($dislikes as $dislike){
            if($dislike->getUser()->getId() === $user->getId()){
                $myDislikes[] = $dislike;
            }
        }
        
        // if the user "like" it
        if(!$asDislike){
            
            // remove "dislikes"
            foreach($myDislikes as $myDislike){
                //$post->removeDislike($myDislike);
                $em->remove($myDislike);
            }
            
            // create a new "like" if no one exist
            if(empty($myLikes)){
                $myLike = new \SymBB\Post\RatingBundle\Entity\Like();
                $myLike->setUser($user);
                $myLike->setPost($post);
            }
            
            // if we create a new "like" then add it
            if(isset($myLike)){
                $em->persist($myLike);
            }
           
        } else {
            // remove "likes"
            foreach($myLikes as $myLike){
                //$post->removeLike($myLike);
                $em->remove($myLike);
            }
            
            // create a new "dislike" if no one exist
            if(empty($myDislikes)){
                $myDislike = new \SymBB\Post\RatingBundle\Entity\Dislike();
                $myDislike->setUser($user);
                $myDislike->setPost($post);
            }
            
            // if we create a new "dislike" then add it
            if(isset($myDislike)){
                $em->persist($myDislike);
            }
        }
        
        $em->flush();
    
    }
    
}