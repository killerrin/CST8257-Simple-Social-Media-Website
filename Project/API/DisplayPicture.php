<?php
    include_once("../Common/IncludeAll.php");

    $action = $_GET["action"];          //echo "<p>$action</p>";
    $filePath = $_GET["filePath"];      //echo "<p>$filePath</p>";

    if(!isset($fileName)) { return; }

    $dbManager = new DBManager();
    $imageManipulation = new ImageManipulation($userID, $albumID, $dbManager, false);

    $fileName = $imageManipulation->GetFileNameFromPath($filePath);
    $imageType = $imageManipulation->GetImageType($filePath);
    $newImage = $imageManipulation->CopyImage($filePath);

    //echo "<p>$fileName</p>";
    //echo "<p>$imageType</p>";

    switch ($action)
    {
        case "rotateLeft":
        case "rotateRight":
            $rotation = $_GET["currentRotation"];
            //echo "<p>$rotation</p>";

            $newImage = imagerotate($newImage, $rotation, 0);
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