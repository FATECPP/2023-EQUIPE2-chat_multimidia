<?php

namespace App\DAO;

class GroupDAO
{
    private $db;
    public function __construct($db){
        $this->db = $db;
    }
    
    public function findGroupById($id){
        return iterator_to_array($this->db->chat->groups->findOne(['id' => $id]));
    }
    public function findUserById($id){
        return iterator_to_array($this->db->chat->users->findOne(['id' => $id]));
    }
    public function findUsers(){
        return iterator_to_array($this->db->chat->users->find());
    }
    public function findAllGroups(){
        return iterator_to_array($this->db->chat->groups->find());
    }
    public function getGroupsByIdUser(){
        return iterator_to_array($this->db->chat->groups->find());
    }
}