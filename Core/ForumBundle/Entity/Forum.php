<?php

namespace SymBB\Core\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="forums")
 * @ORM\Entity()
 */
class Forum
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10))
     */
    private $type = 'forum';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $countLinkCalls = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    
    /**
     * @ORM\OneToMany(targetEntity="Forum", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Forum", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Topic", mappedBy="forum")
     */
    private $topics;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $showSubForumList = false;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $topicsPerPage = 20;


    public function __construct() {
        $this->children = new ArrayCollection();
    }

    ############################################################################
    # Default Get and Set
    ############################################################################
    public function getId(){return $this->id;}
    public function setId($value){$this->id = $value;}
    public function getName(){return $this->name;}
    public function setName($value){$this->name = $value;}
    public function getLink(){return $this->link;}
    public function setLink($value){$this->link = $value;}
    public function getCountLinkCalls(){return $this->countLinkCalls;}
    public function setCountLinkCalls($value){$this->countLinkCalls = $value;}
    public function getDescription(){return $this->description;}
    public function setDescription($value){$this->description = $value;}
    public function getActive(){return $this->active;}
    public function setActive($value){$this->active = $value;}
    public function getType(){return $this->type;}
    public function setType($value){$this->type = $value;}
    public function getShowSubForumList(){return $this->showSubForumList;}
    public function setShowSubForumList($value){$this->showSubForumList = $value;}
    public function getTopicsPerPage(){return $this->topicsPerPage;}
    public function setTopicsPerPage($value){$this->topicsPerPage = $value;}
    public function setParent($object){$this->parent = $object;}
    public function getParent(){return $this->parent;}
    public function getChildren(){return $this->children;}
    public function getTopics(){return $this->topics;}
    ############################################################################
    
    
}