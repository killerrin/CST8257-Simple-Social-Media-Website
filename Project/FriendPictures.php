<?php //session_start(); ?>
<?php $pageTitle = "Friend Pictures"; include "Common/Header.php"; ?>

<?php
// If user is logged in, assign Student object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"]) ? $_SESSION["LoggedInUser"] : (function() { header("Location: Login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])); die();})();

$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);
$albumManager = new DBAlbumRepository($dbManager);
$pictureRepo = new DBPictureRepository($dbManager);
$friendshipRepo = new DBFriendshipRepository($dbManager);
$commentRepo = new DBCommentRepository($dbManager);

$dbManager->connect();
$friend = $userRepo->getID($_GET["id"]);
$friendAlbums = $albumRepo->getAllForUserAccessibility($friend->User_Id, "shared");
$dbManager->close();
?>

<div class="container">

</div>

<?php include "Common/Footer.php"; ?>