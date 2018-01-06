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
    const BASE_FOLDER = "Pictures";
    const ORIGINAL_FOLDER = "Original";
    const ALBUM_FOLDER = "Album";
    const THUMBNAIL_FOLDER = "Thumbnail";
    const THUMBNAIL_WIDTH = 100;
    const THUMBNAIL_HEIGHT = 100;
    const ALBUM_WIDTH = 1024;
    const ALBUM_HEIGHT = 800;

    public $User;

    public function __construct(User $user) {
        $this->User = $user;
        $this->CreateFolderStructure();
    }

    public function CreateFolderStructure() {
        $rootPath = $this->GetRootFolder();
        if (!is_dir($rootPath)) { mkdir($rootPath, 0755, true); }

        $originalPath = $this->GetOriginalFolder();
        if (!is_dir($originalPath)) { mkdir($originalPath, 0755, true); }

        $albumPath = $this->GetAlbumFolder();
        if (!is_dir($albumPath)) { mkdir($albumPath, 0755, true); }

        $thumbPath = $this->GetThumbnailFolder();
        if (!is_dir($thumbPath)) { mkdir($thumbPath, 0755, true); }
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