<?

namespace SymBB\Core\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FrontendTopicController  extends Controller 
{
    
    protected $templateBundle = null;
    
    
    public function showAction($name, $id){
        $topic = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Topic', 'symbb')
            ->find($id);
        $params = array('topic' => $topic);
        return $this->render($this->getTemplateBundleName('forum').':Topic:show.html.twig', $params);
    }
    
    public function evaluatePostAction($name, $id, $like){
        
        $post = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Post', 'symbb')
            ->find($id);
        
        
        $user   = $this->getUser();
        
        if($like === 'like'){
            $this->addPostLike($post, $user);
        } else {
            $this->addPostLike($post, $user, true);
        }
        
        $response = $this->showAction('', $post->getTopic()->getId());
        
        return $response;
    }
    
    protected function getTemplateBundleName($for = 'forum'){
        if($this->templateBundle === null){
            $config = $this->container->getParameter('symbb_config');
            $this->templateBundle = $config['template'][$for];
        }
        return $this->templateBundle;
    }
    
    protected function addPostLike(
            \SymBB\Core\ForumBundle\Entity\Post $post, 
            \SymBB\Core\UserBundle\Entity\UserInterface $user, 
            $asDislike = false
    ){
        
        $likes      = $post->getLikes();
        $dislikes   = $post->getDislikes();
        
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
                $myLike = new \SymBB\Core\ForumBundle\Entity\Topic\Like();
                $myLike->setUser($user);
                $myLike->setPost($post);
            }
            
            // if we create a new "like" then add it
            if(isset($myLike)){
                $post->addLike($myLike);
            }
           
        } else {
            // remove "likes"
            foreach($myLikes as $myLike){
                //$post->removeLike($myLike);
                $em->remove($myLike);
            }
            
            // create a new "dislike" if no one exist
            if(empty($myDislikes)){
                $myDislike = new \SymBB\Core\ForumBundle\Entity\Topic\Dislike();
                $myDislike->setUser($user);
                $myDislike->setPost($post);
            }
            
            // if we create a new "dislike" then add it
            if(isset($myDislike)){
                $post->addDislike($myDislike);
            }
        }
        
        $em->flush();
    
    }
    
}