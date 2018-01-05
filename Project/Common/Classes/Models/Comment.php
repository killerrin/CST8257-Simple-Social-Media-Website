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

    public function __construct($comment_Id, $author_Id, $picture_Id, $comment_Text) {
        $this->Comment_Id = $comment_Id;
        $this->Author_Id = $author_Id;
        $this->Picture_Id = $picture_Id;
        $this->Comment_Text = $comment_Text;
    }

    public function GetUserAuthor() {
        return;
    }
    public function GetPicture() {
        return;
    }
}