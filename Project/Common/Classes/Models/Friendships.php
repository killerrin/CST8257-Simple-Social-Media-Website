<?php

/**
 * Accessibility short summary.
 *
 * Accessibility description.
 *
 * @version 1.0
 * @author andre
 */
class Friendships
{
    public $Friend_RequesterId;
    public $Friend_RequesteeId;
    public $Status_Code;

    public function __construct($friendRequesterId, $friendRequesteeId, $statusCode) {
        $this->Friend_RequesterId = $friendRequesterId;
        $this->Friend_RequesteeId = $friendRequesteeId;
        $this->Status_Code = $statusCode;
    }

    public function GetUserRequester() {

    }
    public function GetUserRequestee() {

    }
    public function GetFriendshipStatus() {

    }
}