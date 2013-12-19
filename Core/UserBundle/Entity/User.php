<?php

namespace SymBB\Core\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="type", type="string", length=10))
     */
    protected $symbbType = 'custom';
    
     /**
     * @ORM\ManyToMany(targetEntity="\SymBB\Core\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="user_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
        
    /**
     * @ORM\OneToMany(targetEntity="\SymBB\Core\ForumBundle\Entity\Topic", mappedBy="author")
     */
    private $topics;
        
    /**
     * @ORM\OneToMany(targetEntity="\SymBB\Core\ForumBundle\Entity\Post", mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\OneToOne(targetEntity="\SymBB\Core\UserBundle\Entity\User\Data")
     * @ORM\JoinColumn(name="data_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $symbbData;

    public function __construct()
    {
        parent::__construct();
        $this->topics = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }
    
    ############################################################################
    # Default Get and Set
    ############################################################################
    public function getTopics(){return $this->topics;}
    public function getPosts(){return $this->posts;}
    public function setSymbbData(\SymBB\Core\UserBundle\Entity\User\Data  $value){ $this->symbbData = $value;}
    public function getSymbbData(){ return $this->symbbData; }
    public function getEmail() {return parent::getEmail();}
    public function getId() {return parent::getId();}
    public function getUsername() {return parent::getUsername();}
    public function getGroups(){return $this->groups;}
    public function setGroups($value){$this->groups = $value;}
    public function setType($value){$this->type = $value;}
    public function getType(){return $this->type;}
    ############################################################################
}