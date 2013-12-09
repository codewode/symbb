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
    public function getLikes(){
        return $this->likes;
    }
    /**
     * @return Topic\Dislike
     */
    public function getDislikes(){
        return $this->dislikes;
    }
    
    public function addLike($like){
        $this->likes->add($like);
    }
    
    public function addDislike($like){
        $this->dislikes->add($like);
    }
    
    public function removeLike($like){
        $this->likes->removeElement($like);
    }
    
    public function removeDislike($like){
        $this->dislikes->removeElement($like);
    }
}