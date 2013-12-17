<?php

namespace SymBB\Core\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
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
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", onDelete="cascade")
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\UserBundle\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;


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
    /**
     * 
     * @return Topic
     */
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
}