<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 2018-01-04
 * Time: 19:32
 */

class Album
{
    public $Album_Id;
    public $Title;
    public $AccessibilityCode;
    public $Description;
    public $Date_Updated;
    public $Owner_Id;


    public function __construct($id, $ownerId, $title, $accessibilityCode, $description)
    {
        $this->Album_Id = $id;
        $this->Title = $title;
        $this->AccessibilityCode = $accessibilityCode;
        $this->Description = $description;
        $this->Owner_Id = $ownerId;
    }

    public function GetAccessibility() {
        return;
    }

    public function GetOwner() {
        return;
    }
}