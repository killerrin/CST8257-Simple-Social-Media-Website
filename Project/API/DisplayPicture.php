<?php
    include_once("../Common/IncludeAll.php");

    $action = $_GET["action"];          //echo "<p>$action</p>";
    $filePath = "../".urldecode($_GET["filePath"]);      //echo "<p>$filePath</p>";
    //$userID = $_GET["userID"];          //echo "<p>$userID</p>";
    //$albumID = $_GET["albumID"];        //echo "<p>$albumID</p>";

    if(!isset($filePath)) { return; }
    //if(!isset($userID)) { return; }
    //if(!isset($albumID)) { return; }

    //$dbManager = new DBManager();
    //$imageManipulation = new ImageManipulation($userID, $albumID, $dbManager, false);

    $fileName = ImageManipulation::GetFileNameFromPath($filePath);
    $imageType = ImageManipulation::GetImageType($filePath);
    $newImage = ImageManipulation::CopyImage($filePath);

    //echo "<p>$fileName</p>";
    //echo "<p>$imageType</p>";

    switch ($action)
    {
        case "rotate":
        case "rotateLeft":
        case "rotateRight":
            $rotation = $_GET["rotation"];
            //echo "<p>$rotation</p>";
            $newImage = ImageManipulation::RotateImage($newImage, $rotation);
            break;
        case "scale":
            $scaleWidth = $_GET["newWidth"];
            $scaleHeight = $_GET["newHeight"]; // -1 To Maintain Aspect Ratio
            //echo "<p>$scaleWidth, $scaleHeight</p>";
            $newImage = ImageManipulation::ScaleImage($newImage, $scaleWidth, $scaleHeight);
            break;
        case "resize":
            $resizeWidth = $_GET["newWidth"];
            $resizeHeight = $_GET["newHeight"];
            $newImage = ImageManipulation::ResizeImage($newImage, $resizeWidth, $resizeHeight);
            break;
        default: break;
    }

    // Set the Header to display as image for rendering
    // Output the Image
    switch ($imageType)
    {
        case IMAGETYPE_PNG:
            header('Content-Type: image/png');
            imagepng($newImage);
            break;
        case IMAGETYPE_JPEG:
            header('Content-Type: image/jpeg');
            imagejpeg($newImage);
            break;
        case IMAGETYPE_GIF:
            header('Content-Type: image/gif');
            imagegif($newImage);
            break;
        default:
            echo "Error";
            break;
    }

    // Delete the Image
    imagedestroy($newImage);
?>