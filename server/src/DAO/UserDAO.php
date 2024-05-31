<?php
namespace App\DAO;
class UserDAO
{
    private $db;
    public function __construct($db){
        $this->db = $db;
    }

    public function findUserById($id){
        return iterator_to_array($this->db->chat->users->findOne(['id' => $id]));
    }
    public function findAllUsers(){
        return iterator_to_array($this->db->chat->users->find());
    }
    
    public function findAllGroups(){
        return iterator_to_array($this->db->chat->groups->find());
    }
}