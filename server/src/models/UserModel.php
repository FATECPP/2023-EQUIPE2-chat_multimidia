<?php
namespace App\models;
class UserModel
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUserById($id){
        $user = $this->db->findUserById($id);
        unset($user["_id"]);
        return $user;
    }
    public function getAllUsers(){
        $users = $this->db->findAllUsers();
        $groups = $this->db->findAllGroups();
        foreach($users as $user){
            unset($user["_id"]);
        }
        $newArrayForUsers = [];
        foreach ($users as $user) 
        {
            $groupsObjects = [];
            foreach ($user['groups'] as $groupUser) 
            {
                foreach ($groups as $group)
                {
                    if ($groupUser == $group['id']) 
                    {
                        array_push($groupsObjects, $group['name']);
                    }
                }
            }
            unset($user['password']);
            $user['groups'] = $groupsObjects;
            usort($user['groups'], function($a, $b) {
                return strcmp($a, $b);
            });
            array_push($newArrayForUsers, $user);
        }
        return $newArrayForUsers;
    }
    public function getAllUsersByGroupId($id){
        $users = $this->db->findAllUsers();
        $isOnArray = [];
        foreach($users as $user){
            foreach($user['groups'] as $groupId){
                if($groupId == $id){
                    unset($user["_id"]);
                    unset($user["userType"]);
                    unset($user["password"]);
                    unset($user["groups"]);
                    unset($user["id"]);
                    $isOnArray[] = $user;
                }
            }

        }
        return $isOnArray;
    }
}