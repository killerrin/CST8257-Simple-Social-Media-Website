<?php
    include "../Common/Classes/Image/Picture.php";

    $action = $_GET["action"];
    $filePath = '../'.$_GET["filePath"];

    //echo "<p>$action</p>";
    //echo "<p>$filePath</p>";

    if(!isset($filePath))
    {
        return;
    }

    $fileName = Picture::getFileNameFromPath($filePath);
    $imageType = Picture::getImageType($filePath);
    $newImage = Picture::copyImage($filePath);

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