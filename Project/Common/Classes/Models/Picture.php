<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 2018-01-04
 * Time: 19:47
 */

class Picture
{
    public $Picture_Id;
    public $Album_Id;
    public $FileName;
    public $Title;
    public $Description;
    public $Date_Added;

    public function __construct($pictureId, $albumId, $fileName, $title, $description, $dateAdded)
    {
        $this->Picture_Id = $pictureId;
        $this->Album_Id = $albumId;
        $this->FileName = $fileName;
        $this->Title = $title;
        $this->Description = $description;
        $this->Date_Added = $dateAdded;
    }

    public function GetAlbum(DBAlbumRepository $repo) {
        return;
    }
}