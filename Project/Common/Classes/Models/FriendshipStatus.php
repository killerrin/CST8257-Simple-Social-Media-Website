<?php

/**
 * Accessibility short summary.
 *
 * Accessibility description.
 *
 * @version 1.0
 * @author andre
 */
class FriendshipStatus
{
    public $Status_Code;
    public $Description;

    public function __construct($id, $description) {
        $this->Status_Code = $id;
        $this->Description = $description;
    }
}