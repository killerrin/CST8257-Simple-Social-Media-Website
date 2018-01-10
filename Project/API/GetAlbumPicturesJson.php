<?php
include_once("../Common/IncludeAll.php");
header('Content-Type: application/json');

$loggedInUserID = $_GET["loggedInUserID"];
$albumUserID = $_GET["albumUserID"];
$albumID = $_GET["albumID"];

if (empty($loggedInUserID)) { die(); }
if (empty($albumUserID)) { die(); }
if (empty($albumID)) { die(); }

$data = array();

$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);
$albumRepo = new DBAlbumRepository($dbManager);
$pictureRepo = new DBPictureRepository($dbManager);
$friendshipRepo = new DBFriendshipRepository($dbManager);

$dbManager->connect();
$loggedInUser = $userRepo->getID($loggedInUserID);
$albumUser = $userRepo->getID($albumUserID);
$album = $albumRepo->getID($albumID);

if (!empty($album)) {
    if ($loggedInUser->User_Id == $albumUser->User_Id) {
        $pictures = $album->GetPictures($pictureRepo);
        foreach ($pictures as $value)
        {
            array_push($data, $value);
        }
    }
    else {
        if (Friendship::AreUsersFriends($friendshipRepo, $loggedInUser, $albumUser)) {
            if ($album->Accessibility_Code == "shared") {
                $pictures = $album->GetPictures($pictureRepo);
                foreach ($pictures as $value)
                {
                    array_push($data, $value);
                }
            }
        }
    }
}

$dbManager->close();

echo json_encode($data);

?>