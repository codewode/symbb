<?php

namespace SymBB\Core\ForumBundle\Entity\Topic;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="forum_topic_likes")
 * @ORM\Entity()
 */
class Like
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\ForumBundle\Entity\Post", inversedBy="likes")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", unique=true)
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    ############################################################################
    # Default Get and Set
    ############################################################################
    public function getId(){return $this->id;}
    public function setId($value){$this->id = $value;}
    public function setUser($object){$this->user = $object;}
    public function getUser(){return $this->user;}
    public function setPost($object){$this->post = $object;}
    public function getPost(){return $this->post;}
    ############################################################################
    
}