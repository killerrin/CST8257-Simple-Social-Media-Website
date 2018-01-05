<?php


namespace Other {
    include_once "../../ConstraintsAndSettings.php";

    class Picture
    {
        private $fileName;
        private $id;

        public static function countPictures()
        {
            $files = scandir(ALBUM_THUMBNAILS_DIR);
            return count($files);
        }

        public static function getFileNameFromPath($filePath)
        {
            $ind = strrpos($filePath, "/");
            $fileName = substr($filePath, $ind);
            return $fileName;
        }

        public static function getPictures()
        {
            $picturesArray = array();
            $filesArray = scandir(ALBUM_THUMBNAILS_DIR);
            $numFiles = count($filesArray);
            if ($numFiles > 2) {
                for ($i = 2; $i < $numFiles; $i++) {
                    $ind = strrpos($filesArray[$i], "/");
                    $fileName = substr($filesArray[$i], $ind);
                    $picture = new Picture($fileName, $i);
                    array_push($picturesArray, $picture);
                    //$picturesArray["$i"] = $picture;
                }
            }
            return $picturesArray;
        }

        public static function getPicture($fileName)
        {
            $picturesArray = Picture::getPictures();
            foreach ($picturesArray as $value) {
                if ($value->getThumbnailFilePath() == Picture::createThumbnailFilePath($fileName)) {
                    return $value;
                }
            }
            return NULL;
        }

        public static function getImageData($filePath)
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

        public static function getImageType($filePath)
        {
            $imageInfo = getimagesize($filePath);
            return $imageInfo[2];
        }

        public static function copyImage($filePath)
        {
            $imageInfo = getimagesize($filePath);
            if ($imageInfo) {
                $originalImage = Picture::getImageData($filePath);
                $originalWidth = imagesx($originalImage);
                $originalHeight = imagesY($originalImage);

                $newImage = imagecreatetruecolor($originalWidth, $originalHeight);
                imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $originalWidth, $originalHeight, $originalWidth, $originalHeight);
                return $newImage;
            }
            return NULL;
        }

        public static function deletePicture($fileName)
        {
            $originalFilePath = "./" . Picture::createOriginalFilePath($fileName);
            $albumFilePath = "./" . Picture::createAlbumFilePath($fileName);
            $albumThumbnailFilePath = "./" . Picture::createThumbnailFilePath($fileName);

            unlink($originalFilePath);
            unlink($albumFilePath);
            unlink($albumThumbnailFilePath);
        }

        public static function savePicture($filePath, $fileName)
        {
            // Precreate the new filepaths
            $originalFilePath = "./" . Picture::createOriginalFilePath($fileName);
            $albumFilePath = "./" . Picture::createAlbumFilePath($fileName);
            $albumThumbnailFilePath = "./" . Picture::createThumbnailFilePath($fileName);

            // Move the Original File
            if (move_uploaded_file($filePath, $originalFilePath)) {
                // Gather Image Information for further steps
                $imageInfo = getimagesize($originalFilePath);
                if ($imageInfo) {
                    // Load the Image
                    $originalImage = Picture::getImageData($originalFilePath);
                    $originalWidth = imagesx($originalImage);
                    $originalHeight = imagesY($originalImage);

                    // Create the Thumbnail and put in folder
                    $thumbnailImage = imagecreatetruecolor(THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT);
                    imagecopyresampled($thumbnailImage, $originalImage, 0, 0, 0, 0, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT, $originalWidth, $originalHeight);

                    // Create the Album and put in folder
                    $albumImage = imagecreatetruecolor(IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT);
                    imagecopyresampled($albumImage, $originalImage, 0, 0, 0, 0, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT, $originalWidth, $originalHeight);

                    // Save the Image
                    switch ($imageInfo[2]) {
                        case IMAGETYPE_PNG:
                            imagepng($albumImage, $albumFilePath);
                            imagepng($thumbnailImage, $albumThumbnailFilePath);
                            break;
                        case IMAGETYPE_JPEG:
                            imagejpeg($albumImage, $albumFilePath);
                            imagejpeg($thumbnailImage, $albumThumbnailFilePath);
                            break;
                        case IMAGETYPE_GIF:
                            imagegif($albumImage, $albumFilePath);
                            imagegif($thumbnailImage, $albumThumbnailFilePath);
                            break;
                        default:
                            return;
                    }
                }
            }
        }

        public function __construct($fileName, $id)
        {
            $this->fileName = $fileName;
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getFileName()
        {
            return $this->fileName;
        }

        public function getName()
        {
            $ind = strrpos($this->fileName, ".");
            $name = substr($this->fileName, 0, $ind);
            return $name;
        }

        public static function createAlbumFilePath($fileName)
        {
            return ALBUM_PICTURES_DIR . "/" . $fileName;
        }

        public static function createThumbnailFilePath($fileName)
        {
            return ALBUM_THUMBNAILS_DIR . "/" . $fileName;
        }

        public static function createOriginalFilePath($fileName)
        {
            return ORIGINAL_PICTURES_DIR . "/" . $fileName;
        }

        public function getAlbumFilePath()
        {
            return Picture::createAlbumFilePath($this->fileName);
        }

        public function getThumbnailFilePath()
        {
            return Picture::createThumbnailFilePath($this->fileName);
        }

        public function getOriginalFilePath()
        {
            return Picture::createOriginalFilePath($this->fileName);
        }
    }
}
?>