<?php

namespace SymBB\Core\UserBundle\Entity;

interface UserInterface {
    
    public function getId();
    public function getUsername();
    public function getEmail();
    public function getTopics();
    public function getPosts();
    public function getGroups();
    
    /**
     * @return \SymBB\Core\UserBundle\Entity\User\Data 
     */
    public function getSymbbData();
    public function setSymbbData(\SymBB\Core\UserBundle\Entity\User\Data  $value);
    
}