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

    public function __construct(?string $userID, string $name, string $phone, string $password) {
        $this->User_Id = $userID;
        $this->Name = $name;
        $this->Phone = $phone;
        $this->Password = $password;
    }

    public static function HashPassword(string $password) : string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function VerifyPassword(string $password, string $hash) : bool {
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
    public function GetIncommingFriendRequests(DBFriendshipRepository $repo) {
        $friends = $repo->getAllForUser($this->User_Id);
        $array = array();
        foreach ($friends as $friend) {
            if ($friend->Status_Code == "request" && $friend->Friend_RequesteeId == $this->User_Id)
                array_push($array, $friend);
        }
        return $array;
    }
}