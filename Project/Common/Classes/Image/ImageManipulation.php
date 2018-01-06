<?php

/**
 * Image short summary.
 *
 * Image description.
 *
 * @version 1.0
 * @author andre
 */
class ImageManipulation
{
    public const BASE_FOLDER = "Pictures";
    public const ORIGINAL_FOLDER = "Original";
    public const ALBUM_FOLDER = "Album";
    public const THUMBNAIL_FOLDER = "Thumbnail";
    public const THUMBNAIL_WIDTH = 100;
    public const THUMBNAIL_HEIGHT = 100;
    public const ALBUM_WIDTH = 1024;
    public const ALBUM_HEIGHT = 800;

    public $User;

    public function __construct(User $user) {
        $this->User = $user;
        $this->CreateFolderStructure();
    }

    public function CreateFolderStructure() {
        $rootPath = "../../../".$this->GetRootFolder();
        if (!is_dir($rootPath)) { mkdir($rootPath); }

        $originalPath = "../../../".$this->GetOriginalFolder();
        if (!is_dir($originalPath)) { mkdir($originalPath); }

        $albumPath = "../../../".$this->GetAlbumFolder();
        if (!is_dir($albumPath)) { mkdir($albumPath); }

        $thumbPath = "../../../".$this->GetThumbnailFolder();
        if (!is_dir($thumbPath)) { mkdir($thumbPath); }
    }

    public function GetRootFolder() {
        return ImageManipulation::BASE_FOLDER."/".$this->User->User_Id;
    }
    public function GetOriginalFolder() {
        return ImageManipulation::BASE_FOLDER."/".$this->User->User_Id."/".ORIGINAL_FOLDER;
    }
    public function GetAlbumFolder() {
        return ImageManipulation::BASE_FOLDER."/".$this->User->User_Id."/".ALBUM_FOLDER;
    }
    public function GetThumbnailFolder() {
        return ImageManipulation::BASE_FOLDER."/".$this->User->User_Id."/".THUMBNAIL_FOLDER;
    }

}