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

    public function __construct($friendRequesterId, $friendRequesteeId, $statusCode) {
        $this->Friend_RequesterId = $friendRequesterId;
        $this->Friend_RequesteeId = $friendRequesteeId;
        $this->Status_Code = $statusCode;
    }

    public function GetUserRequester() {
        return;
    }
    public function GetUserRequestee() {
        return;
    }
    public function GetFriendshipStatus() {
        return;
    }
}