<?php

/**
 * Accessibility short summary.
 *
 * Accessibility description.
 *
 * @version 1.0
 * @author andre
 */
class Friendship
{
    public $Friend_RequesterId;
    public $Friend_RequesteeId;
    public $Status_Code;

    public function __construct(?string $friendRequesterId, string $friendRequesteeId, string $statusCode) {
        $this->Friend_RequesterId = $friendRequesterId;
        $this->Friend_RequesteeId = $friendRequesteeId;
        $this->Status_Code = $statusCode;
    }

    public function GetUserRequester(DBUserRepository $repo) {
        return $repo->getID($this->Friend_RequesterId);
    }
    public function GetUserRequestee(DBUserRepository $repo) {
        return $repo->getID($this->Friend_RequesteeId);
    }
    public function GetFriendshipStatus(DBFriendshipStatusRepository $repo) {
        return $repo->getID($this->Status_Code);
    }

    public static function AreUsersFriends(DBFriendshipRepository $friendRepo, User $u1, User $u2) {
        $friendships = $friendRepo->getAllForUser($u1->User_Id);
        foreach ($friendships as $friend)
        {
            if ($friend->Status_Code != "accepted") continue;
        	if ($friend->Friend_RequesterId == $u2->User_Id || $friend->Friend_RequesteeId == $u2->User_Id)
            {
                return true;
            }
        }
        return false;
    }
}