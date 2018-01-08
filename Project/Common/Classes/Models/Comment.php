<?php

/**
 * Accessibility short summary.
 *
 * Accessibility description.
 *
 * @version 1.0
 * @author andre
 */
class Comment
{
    public $Comment_Id;
    public $Author_Id;
    public $Picture_Id;
    public $Comment_Text;
    public $Date;

    public function __construct(?int $comment_Id, string $author_Id, int $picture_Id, string $comment_Text, $date) {
        $this->Comment_Id = $comment_Id;
        $this->Author_Id = $author_Id;
        $this->Picture_Id = $picture_Id;
        $this->Comment_Text = $comment_Text;
        $this->Date = $date;
    }

    public function GetUserAuthor(DBUserRepository $repo) {
        return $repo->getID($this->Author_Id);
    }
    public function GetPicture(DBPictureRepository $repo) {
        return $repo->getID($this->Picture_Id);
    }
}