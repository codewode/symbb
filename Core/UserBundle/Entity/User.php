<?php

namespace SymBB\Core\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
     /**
     * @ORM\ManyToMany(targetEntity="SymBB\Core\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="user_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
        
    /**
     * @ORM\OneToMany(targetEntity="SymBB\Core\ForumBundle\Entity\Topic", mappedBy="author")
     */
    private $topics;
        
    /**
     * @ORM\OneToMany(targetEntity="SymBB\Core\ForumBundle\Entity\Post", mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $signature;

    public function __construct()
    {
        parent::__construct();
        $this->topics = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }
    
    ############################################################################
    # Default Get and Set
    ############################################################################
    public function getTopics(){return $this->topics;}
    public function getPosts(){return $this->posts;}
    public function getSignature(){return $this->signature;}
    public function setSignature($value){ $this->signature = $value;}
    ############################################################################
}