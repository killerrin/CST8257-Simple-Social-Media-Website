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

    public function __construct(?string $id, string $description) {
        $this->Status_Code = htmlspecialchars($id);
        $this->Description = htmlspecialchars($description);
    }
}