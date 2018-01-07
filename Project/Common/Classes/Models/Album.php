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

    public function __construct(int $id, string $title, string $description, $dateUpdated, string $ownerId, string $accessibilityCode)
    {
        $this->Album_Id = $id;
        $this->Title = $title;
        $this->Accessibility_Code = $accessibilityCode;
        $this->Description = $description;
        $this->Owner_Id = $ownerId;
        $this->Date_Updated = $dateUpdated;
    }

    public function GetAccessibility(DBAccessibilityRepository $repo) {
        return $repo->getID($this->Accessibility_Code);
    }

    public function GetUserOwner(DBUserRepository $repo) {
        return $repo->getID($this->Owner_Id);
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