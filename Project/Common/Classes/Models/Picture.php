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

    public function __construct(?int $pictureId, int $albumId, string $fileName, string $title, string $description, $dateAdded)
    {
        $this->Picture_Id = $pictureId;
        $this->Album_Id = $albumId;
        $this->FileName = $fileName;
        $this->Title = $title;
        $this->Description = $description;
        $this->Date_Added = $dateAdded;
    }

    public function GetAlbum(DBAlbumRepository $repo) {
        return $repo->getID($this->Album_Id);
    }

    public function GetAllComments(DBCommentRepository $repo) {
        $comments = $repo->getAll();
        $array = array();
        foreach ($comments as $comment) {
            if ($comment->Picture_Id == $this->Picture_Id)
                array_push($array, $comment);
        }
        return $array;
    }
}