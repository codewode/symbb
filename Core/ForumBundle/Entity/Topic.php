<?php

namespace SymBB\Core\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="forum_topics")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Topic
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
     * @ORM\OneToMany(targetEntity="SymBB\Core\ForumBundle\Entity\Post", mappedBy="topic")
     * @ORM\OrderBy({"changed" = "DESC", "created" = "DESC"})
     */
    private $posts;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\ForumBundle\Entity\Forum", inversedBy="topics")
     * @ORM\JoinColumn(name="forum_id", referencedColumnName="id")
     */
    private $forum;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\UserBundle\Entity\User", inversedBy="topics")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;


    public function __construct() {
        $this->posts = new ArrayCollection();
    }

    ############################################################################
    # Default Get and Set
    ############################################################################
    public function getId(){return $this->id;}
    public function setId($value){$this->id = $value;}
    public function getName(){return $this->name;}
    public function setName($value){$this->name = $value;}
    public function setForum($object){$this->forum = $object;}
    public function getForum(){return $this->forum;}
    public function setAuthor($object){$this->author = $object;}
    public function getAuthor(){return $this->author;}
    public function getPosts(){return $this->posts;}
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
    
    
    
    public function hasPosts(){
        $posts = $this->getPosts();
        if($posts->count() > 0){
            return true;
        }
        return false;
    }
    
    public function getSeoName(){
        $name = $this->getName();
        $name = preg_replace('/\W+/', '-', $name);
        $name = strtolower(trim($name, '-'));
        return $name;
    }
    
    public function getLastPost(){
        return $this->getPosts()->last();
    }
    
    public function getPostCount(){
        $count = $this->getPosts()->count() - 1;
        return $count;
    }
}