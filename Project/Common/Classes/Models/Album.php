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
    public $Accessibility_Code;
    public $Description;
    public $Date_Updated;
    public $Owner_Id;


    public function __construct($id, $title, $description, $dateUpdated, $ownerId, $accessibilityCode)
    {
        $this->Album_Id = $id;
        $this->Title = $title;
        $this->Accessibility_Code = $accessibilityCode;
        $this->Description = $description;
        $this->Owner_Id = $ownerId;
        $this->Date_Updated = $dateUpdated;
    }

    public function GetAccessibility(DBAccessibilityRepository $repo) {
        return;
    }

    public function GetUserOwner(DBUserRepository $repo) {
        return;
    }

    public function GetPictures(DBPictureRepository $repo) {
        $pictures = $repo->getAll();
        $array = array();
        foreach ($pictures as $picture) {
            if ($picture->Album_Id == $this->Album_Id)
                array_push($array, $picture);
        }
        return $array;
    }
}