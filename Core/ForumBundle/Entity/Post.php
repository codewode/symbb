<?php

namespace SymBB\Core\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="forum_topic_posts")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $created;

    /**
     * @ORM\Column(type="date")
     */
    private $changed;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="Topic", inversedBy="posts")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\UserBundle\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;
    
    /**
     * @ORM\OneToMany(targetEntity="SymBB\Core\ForumBundle\Entity\Topic\Like", mappedBy="post", cascade={"persist"})
     * @var ArrayCollection
     */
    private $likes;
    
    /**
     * @ORM\OneToMany(targetEntity="SymBB\Core\ForumBundle\Entity\Topic\Dislike", mappedBy="post", cascade={"persist"})
     * @var ArrayCollection
     */
    private $dislikes;


    public function __construct() {
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
    }


    ############################################################################
    # Default Get and Set
    ############################################################################
    public function getId(){return $this->id;}
    public function setId($value){$this->id = $value;}
    public function getName(){return $this->name;}
    public function setName($value){$this->name = $value;}
    public function getText(){return $this->text;}
    public function setText($value){$this->text = $value;}
    public function setTopic($object){$this->topic = $object;}
    public function getTopic(){return $this->topic;}
    public function setAuthor($object){$this->author = $object;}
    public function getAuthor(){return $this->author;}
    public function getCreated(){return $this->created;}
    public function getChanged(){return $this->changed;}
    ############################################################################
    
    /**
    * @ORM\PrePersist
    */
    public function setCreatedValue()
    {
       $this->created = new \DateTime();
    }

    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function setChangedValue()
    {
       $this->changed = new \DateTime();
    }
    
    public function getSeoName(){
        $name = $this->getName();
        $name = preg_replace('/\W+/', '-', $name);
        $name = strtolower(trim($name, '-'));
        return $name;
    }
    
    
    /**
     * @return Topic\Like
     */
    public function getLikes(){return $this->likes;}
    /**
     * @return Topic\Dislike
     */
    public function getDislikes(){return $this->dislikes;}
    
    
    public function addLike(\SymBB\Core\UserBundle\Entity\UserInterface $user, $asDislike = false){
        
        
        $likes      = $this->getLikes();
        $dislikes   = $this->getDislikes();
        
        $myLikes    = array();
        $myDislikes = array();
        
        foreach($likes as $like){
            if($like->getUser()->getId() === $user->getId()){
                $myLikes[] = $like;
                break;
            }
        }
        
        foreach($dislikes as $dislike){
            if($dislike->getUser()->getId() === $user->getId()){
                $myDislikes[] = $dislike;
                break;
            }
        }
        
        // if the user "like" it
        if(!$asDislike){
            
            // remove "dislikes"
            foreach($myDislikes as $myDislike){
                $this->dislikes->removeElement($myDislike);
            }
            
            // create a new "like" if no one exist
            if(empty($myLikes)){
                $myLike = new Topic\Like();
                $myLike->setUser($user);
                $myLike->setPost($this);
            }
            
            // if we create a new "like" then add it
            if(isset($myLike)){
                $this->likes->add($myLike);
            }
           
        } else {
            // remove "likes"
            foreach($myLikes as $myLike){
                $this->likes->removeElement($myLike);
            }
            
            // create a new "dislike" if no one exist
            if(empty($myDislikes)){
                $myDislike = new Topic\Dislike();
                $myDislike->setUser($user);
                $myDislike->setPost($this);
            }
            
            // if we create a new "dislike" then add it
            if(isset($myDislike)){
                $this->dislikes->add($myDislike);
            }
        }
        
    }
    
    
    public function addDislike($user){
        return $this->addLike($user, true);
    }
    
    public function removeLike($user){
        $likes = $this->getLikes();
        foreach($likes as $like){
            if($like->getAuthor()->getId() === $user->getId()){
                $this->likes->removeElement($like);
                break;
            }
        }
    }
    
    public function removeDislike($user){
        $likes = $this->getDislikes();
        foreach($likes as $like){
            if($like->getAuthor()->getId() === $user->getId()){
                $this->dislikes->removeElement($like);
                break;
            }
        }
    }
}