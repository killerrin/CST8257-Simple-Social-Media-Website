<?php
include_once("../Common/IncludeAll.php");
header('Content-Type: application/json');

$loggedInUserID = $_GET["loggedInUserID"];
$albumUserID = $_GET["albumUserID"];
$pictureID = $_GET["pictureID"];

if (empty($loggedInUserID)) { die(); }
if (empty($albumUserID)) { die(); }
if (empty($pictureID)) { die(); }

$data = array();

$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);
$pictureRepo = new DBPictureRepository($dbManager);
$commentRepo = new DBCommentRepository($dbManager);
$friendshipRepo = new DBFriendshipRepository($dbManager);
$albumRepo = new DBAlbumRepository($dbManager);

$dbManager->connect();
$loggedInUser = $userRepo->getID($loggedInUserID);
$albumUser = $userRepo->getID($albumUserID);
$picture = $pictureRepo->getID($pictureID);

if ($loggedInUser->User_Id == $albumUser->User_Id) {
    $comments = $picture->GetAllComments($commentRepo);
    foreach ($comments as $value)
    {
        array_push($data, $value);
    }
}
else {
    if (Friendship::AreUsersFriends($friendshipRepo, $loggedInUser, $albumUser)) {
        $album = $albumRepo->getID($picture->Album_Id);
        if ($album->Accessibility_Code == "shared") {
            $comments = $picture->GetAllComments($commentRepo);
            foreach ($comments as $value)
            {
                array_push($data, $value);
            }
        }
    }
}

$dbManager->close();

echo json_encode($data);

?>