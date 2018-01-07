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
    const GALLERY_FOLDER = "Gallery";
    const THUMBNAIL_FOLDER = "Thumbnail";
    const THUMBNAIL_WIDTH = 100;
    const THUMBNAIL_HEIGHT = 100;
    const GALLERY_WIDTH = 1024;
    const GALLERY_HEIGHT = 800;

    // File Organization
    public $User_Id;
    public $Album_Id;

    // Database
    public $DBManager;
    public $AlbumRepo;
    public $PictureRepo;
    public $UserRepo;

    public function __construct($userID, $albumID, DBManager $dbManager, $createFolders) {
        $this->User_Id = $userID;
        $this->Album_Id = $albumID;

        // Create the Repos
        $this->DBManager = $dbManager;
        $this->AlbumRepo = new DBAlbumRepository($this->DBManager);
        $this->PictureRepo = new DBPictureRepository($this->DBManager);
        $this->UserRepo = new DBUserRepository($this->DBManager);

        if ($createFolders) {
            $this->CreateFolderStructure();
        }
    }

    public function GetRootFolder() : string { return ImageManipulation::BASE_FOLDER; }
    public function GetRootUserFolder() : string { return ImageManipulation::BASE_FOLDER."/".$this->User_Id; }
    public function GetRootAlbumFolder() : string { return ImageManipulation::BASE_FOLDER."/".$this->User_Id."/".$this->Album_Id; }
    public function GetOriginalFolder() : string { return ImageManipulation::BASE_FOLDER."/".$this->User_Id."/".$this->Album_Id."/".ImageManipulation::ORIGINAL_FOLDER; }
    public function GetGalleryFolder() : string { return ImageManipulation::BASE_FOLDER."/".$this->User_Id."/".$this->Album_Id."/".ImageManipulation::GALLERY_FOLDER; }
    public function GetThumbnailFolder() : string { return ImageManipulation::BASE_FOLDER."/".$this->User_Id."/".$this->Album_Id."/".ImageManipulation::THUMBNAIL_FOLDER; }
    public function CreateFilePath($folderPath, $fileName) : string { return $folderPath."/".$fileName; }
    public function CreateFolderStructure()
    {
        // Create Roots
        $rootPath = $this->GetRootUserFolder();
        $rootUserPath = $this->GetRootUserFolder();
        $rootAlbumPath = $this->GetRootAlbumFolder();
        if (!is_dir($rootPath)) { mkdir($rootPath, 0755, true); }
        if (!is_dir($rootUserPath)) { mkdir($rootUserPath, 0755, true); }
        if (!is_dir($rootAlbumPath)) { mkdir($rootAlbumPath, 0755, true); }

        // Create Internal Seperators
        $originalPath = $this->GetOriginalFolder();
        $galleryPath = $this->GetGalleryFolder();
        $thumbPath = $this->GetThumbnailFolder();
        if (!is_dir($originalPath)) { mkdir($originalPath, 0755, true); }
        if (!is_dir($galleryPath)) { mkdir($galleryPath, 0755, true); }
        if (!is_dir($thumbPath)) { mkdir($thumbPath, 0755, true); }
    }

    public function CountPictures() : int
    {
        $files = scandir($this->GetOriginalFolder());
        return count($files);
    }

    public function CopyImageAndSave($originalFilePath, $savePath) : bool
    {
        // Copy the Image
        $newImage = ImageManipulation::CopyImage($originalFilePath);
        if ($newImage == NULL) return false;

        // Save the Image
        $imageInfo = getimagesize($originalFilePath);
        if ($imageInfo) {
            switch ($imageInfo[2]) {
                case IMAGETYPE_PNG:
                    imagepng($newImage, $savePath);
                    break;
                case IMAGETYPE_JPEG:
                    imagejpeg($newImage, $savePath);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($newImage, $savePath);
                    break;
                default:
                    return false;
            }

            return true;
        }

        return false;
    }

    public function SavePictures($tmpFilePath, Picture $picture)
    {
        //echo $tmpFilePath; // For Testing
        $originalFilePath = $this->CreateFilePath($this->GetOriginalFolder(), $picture->FileName);
        $galleryFilePath = $this->CreateFilePath($this->GetGalleryFolder(), $picture->FileName);
        $albumThumbnailFilePath = $this->CreateFilePath($this->GetThumbnailFolder(), $picture->FileName);

        // Copy/Move the file out of the temporary location and into the Original Folder
        if ($this->CopyImageAndSave($tmpFilePath, $originalFilePath)) { //if (move_uploaded_file($tmpFilePath, $originalFilePath)) {
            // Gather Image Information for further steps
            $imageInfo = getimagesize($originalFilePath);
            if ($imageInfo) {
                // Load the Image
                $originalImage = ImageManipulation::GetImageData($originalFilePath);
                $originalWidth = imagesx($originalImage);
                $originalHeight = imagesY($originalImage);

                // Create the Thumbnail and put in folder
                $thumbnailImage = imagecreatetruecolor(ImageManipulation::THUMBNAIL_WIDTH, ImageManipulation::THUMBNAIL_HEIGHT);
                imagecopyresampled($thumbnailImage, $originalImage, 0, 0, 0, 0, ImageManipulation::THUMBNAIL_WIDTH, ImageManipulation::THUMBNAIL_HEIGHT, $originalWidth, $originalHeight);

                // Create the Album and put in folder
                $albumImage = imagecreatetruecolor(ImageManipulation::GALLERY_WIDTH, ImageManipulation::GALLERY_HEIGHT);
                imagecopyresampled($albumImage, $originalImage, 0, 0, 0, 0, ImageManipulation::GALLERY_WIDTH, ImageManipulation::GALLERY_HEIGHT, $originalWidth, $originalHeight);

                // Save the Image
                switch ($imageInfo[2]) {
                    case IMAGETYPE_PNG:
                        imagepng($albumImage, $galleryFilePath);
                        imagepng($thumbnailImage, $albumThumbnailFilePath);
                        break;
                    case IMAGETYPE_JPEG:
                        imagejpeg($albumImage, $galleryFilePath);
                        imagejpeg($thumbnailImage, $albumThumbnailFilePath);
                        break;
                    case IMAGETYPE_GIF:
                        imagegif($albumImage, $galleryFilePath);
                        imagegif($thumbnailImage, $albumThumbnailFilePath);
                        break;
                    default:
                        return;
                }

                // Add the picture to the Database
                $tmpPicture = $this->PictureRepo->getID($picture->Picture_Id);
                if (count($tmpPicture) == 0) {
                    $this->PictureRepo->insert($picture);
                }
            }
        }
    }

    public function DeletePictures(Picture $picture)
    {
        // Get the files
        $originalFilePath = $this->CreateFilePath($this->GetOriginalFolder(), $picture->FileName);
        $galleryFilePath = $this->CreateFilePath($this->GetGalleryFolder(), $picture->FileName);
        $albumThumbnailFilePath = $this->CreateFilePath($this->GetThumbnailFolder(), $picture->FileName);

        // Delete the files
        unlink($originalFilePath);
        unlink($galleryFilePath);
        unlink($albumThumbnailFilePath);

        // Remove from DB
        $tmpPicture = $this->PictureRepo->getID($picture->Picture_Id);
        $this->PictureRepo->delete($tmpPicture[0]);
    }

    public function DownloadPicture($fileName) {
        $originalFilePath = $this->CreateFilePath($this->GetOriginalFolder(), $fileName);
        $fileLength = filesize($originalFilePath);

        $this->mime = (function() {
            switch($this->imageSize[2]) {
                case IMAGETYPE_JPEG:
                    return 'image/jpeg';
                case IMAGETYPE_GIF:
                    return 'image/gif';
                case IMAGETYPE_PNG:
                    return 'image/png';
                default:
                    return 'application/octet-stream';
            }
        })();

        header("Content-Type: $this->mime");
        header("Content-Disposition: attachment; filename = \"$fileName\" ");
        header("Content-Length: $fileLength" );
        header("Content-Description: File Transfer");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: private");

        ob_clean();
        flush();
        readfile($originalFilePath);
        flush();
    }

    /* ====================================================================================
     * ====================================================================================
     * ==================================================================================== */

    public static function GetFileNameFromPath($filePath) : string
    {
        $ind = strrpos($filePath, "/");
        $fileName = substr($filePath, $ind);
        return $fileName;
    }

    public static function GetImageType($filePath) : int
    {
        return exif_imagetype($filePath);
    }

    public static function GetImageData($filePath)
    {
        $image = NULL;
        $imageInfo = getimagesize($filePath);
        if ($imageInfo) {
            // Load the Image
            switch ($imageInfo[2]) {
                case IMAGETYPE_PNG:
                    //echo "Its a PNG";
                    $image = imagecreatefrompng($filePath);
                    break;
                case IMAGETYPE_JPEG:
                    //echo "Its a JPEG";
                    $image = imagecreatefromjpeg($filePath);
                    break;
                case IMAGETYPE_GIF:
                    //echo "Its a GIF";
                    $image = imagecreatefromgif($filePath);
                    break;
                default:
                    break;
            }
        }
        return $image;
    }

    public static function CopyImage($filePath)
    {
        $imageInfo = getimagesize($filePath);
        if ($imageInfo) {
            $originalImage = ImageManipulation::GetImageData($filePath);
            $originalWidth = imagesx($originalImage);
            $originalHeight = imagesY($originalImage);

            $newImage = imagecreatetruecolor($originalWidth, $originalHeight);
            imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $originalWidth, $originalHeight, $originalWidth, $originalHeight);
            return $newImage;
        }
        return NULL;
    }

    public static function RotateImage($imageResource, float $rotationAngle) {
        return imagerotate($imageResource, $rotationAngle, 0);
    }
    public static function ScaleImage($imageResource, int $newWidth, int $newHeight = -1, int $mode = IMG_BILINEAR_FIXED) {
        return imagescale($imageResource, $newWidth, $newHeight, $mode);
    }
    public static function ResizeImage($imageResource, int $newWidth, int $newHeight) {
        $originalWidth = imagesx($imageResource);
        $originalHeight = imagesY($imageResource);

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $imageResource, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        return $newImage;
    }


}