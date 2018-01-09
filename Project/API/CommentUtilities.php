<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 2018-01-08
 * Time: 11:37
 */

include_once("../Common/IncludeAll.php");
header("Content-Type: application/json");

if (!(empty($_GET) xor empty($_POST))) die("Don't GET and POST at the same time");

$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);
$pictureRepo = new DBPictureRepository($dbManager);
$commentRepo = new DBCommentRepository($dbManager);
$albumRepo = new DBAlbumRepository($dbManager);
$friendshipRepo = new DBFriendshipRepository($dbManager);


$dbManager->connect();
if (empty($_POST)) {
    switch (count($_GET)) {
        case 1:
            switch (array_keys($_GET)[0]) {
                case "userID":
                    $name = $userRepo->getID($_GET['userID'])->Name;

                    echo json_encode($name);
                    break;
                default:
                    break;
            }
            break;
        default:
            break;
    }
}

// get user from userID, get albumOwnerID from picture and album, then user from albumOwnerID, then check friendship, then insert comment
elseif (empty($_GET)) {
    if (isset($_POST['userID']) && isset($_POST['pictureID']) && isset($_POST['comment'])) {
        $pictureID = $_POST['pictureID'];
        $userID = $_POST['userID'];
        $user = $userRepo->getID($userID);
        $comment = $_POST['comment'];
        $picture = $pictureRepo->getID($pictureID);
        $albumID = $picture->Album_Id;
        $album = $albumRepo->getID($albumID);
        $ownerID = $album->Owner_Id;
        $owner = $userRepo->getID($ownerID);

        if (Friendship::AreUsersFriends($friendshipRepo, $user, $owner) && $album->Accessibility_Code == 'shared') {
            $result = $commentRepo->insert(new Comment(null, $userID, $pictureID, $comment, date('Y-m-d H:i:s')));

            echo json_encode($result);
        }
    }
}


$dbManager->close();