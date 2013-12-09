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
     * @ORM\JoinColumn(name="data_id", referencedColumnName="id")
     */
    private $symbbData;

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
    public function setSymbbData(\SymBB\Core\UserBundle\Entity\User\Data  $value){ $this->symbbData = $value;}
    ############################################################################
    
    public function getSymbbData(){
        $data = $this->symbbData;
        if(!$data){
            $this->symbbData = new User\Data();
        }
        return $this->symbbData;
    }
}