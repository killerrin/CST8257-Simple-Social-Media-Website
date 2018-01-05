<?php

/**
 * User short summary.
 *
 * User description.
 *
 * @version 1.0
 * @author andre
 */
class User
{
    public $User_Id;
    public $Name;
    public $Phone;
    public $Password;

    public function __construct($userID, $name, $phone, $password) {
        $this->User_Id = $userID;
        $this->Name = $name;
        $this->Phone = $phone;
        $this->Password = $password;
    }

    public static function HashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function VerifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function GetFriends(DBFriendshipRepository $repo) {
        $friends = $repo->getAllForUser($this->User_Id);
        $array = array();
        foreach ($friends as $friend) {
            if ($friend->Status_Code == "accepted")
                array_push($array, $friend);
        }
        return $array;
    }
    public function GetRequestedFriends(DBFriendshipRepository $repo) {
        $friends = $repo->getAllForUser($this->User_Id);
        $array = array();
        foreach ($friends as $friend) {
            if ($friend->Status_Code == "request")
                array_push($array, $friend);
        }
        return $array;
    }
}