<?php
namespace App\models;
class GroupModel
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
      function compareByName($a, $b)
    {
        return strcmp($a["name"], $b["name"]);
    }
    public function getGroupsByIdUser($id){
        $user = $this->db->findUserById($id);
        $groups = $this->db->findAllGroups();
        $groupsObjects = [];
        foreach ($user['groups'] as $groupUser) {
            foreach ($groups as $group) {
                if ($groupUser == $group['id']) {
                    array_push($groupsObjects, $group);
                }
            }
        }
        usort($groupsObjects, [$this, "compareByName"]);
        return $groupsObjects;
    }

    public function getAllGroups(){
        $groups = $this->db->findAllGroups();
        foreach($groups as $group){
            unset($group["_id"]);
        }
        return $groups;
    }

  public function getUsersOfGroup($id){
        // $group = $this->db->findGroupById($id);
        $users = $this->db->findUsers();
        $userNewArray = [];
        foreach ($users as $user){
          foreach($user['groups'] as $group){
            if($group == $id){
              unset($user["_id"]);
              unset($user["id"]);
              unset($user["groups"]);
              unset($user["userType"]);
              unset($user["password"]);
              $userNewArray[] = $user;
            }
          }
            
        }
    return $userNewArray;
  }
}