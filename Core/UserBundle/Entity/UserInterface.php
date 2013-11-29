<?php

namespace SymBB\Core\UserBundle\Entity;

interface UserInterface {
    
    public function getTopics();
    public function getPosts();
    public function getSignature();
    public function setSignature($value);
    
}