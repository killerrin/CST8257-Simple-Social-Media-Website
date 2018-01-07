<?php
include_once("../Common/IncludeAll.php");
header('Content-Type: application/json');

$loggedInUserID = $_GET["loggedInUserID"];
$albumUserID = $_GET["albumUserID"];

if (empty($loggedInUserID)) { die(); }
if (empty($albumUserID)) { die(); }

$data = array();

$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);
$albumRepo = new DBAlbumRepository($dbManager);
$friendshipRepo = new DBFriendshipRepository($dbManager);

$dbManager->connect();
$loggedInUser = $userRepo->getID($loggedInUserID);
$albumUser = $userRepo->getID($albumUserID);

if ($loggedInUser->User_Id == $albumUser->User_Id) {
    $albums = $albumRepo->getAllForUser($loggedInUser->User_Id);
    foreach ($albums as $value)
    {
        array_push($data, $value);
    }
}
else {
    if (Friendship::AreUsersFriends($friendshipRepo, $loggedInUser, $albumUser)) {
        $albums = $albumRepo->getAllForUserAccessibility($albumUser->User_Id, "shared");
        foreach ($albums as $value)
        {
            array_push($data, $value);
        }
    }
}

$dbManager->close();

echo json_encode($data);

?>