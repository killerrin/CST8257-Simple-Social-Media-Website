<?php //session_start(); ?>
<?php $pageTitle = "Friend Pictures"; include "Common/Header.php"; ?>

<?php
// If user is logged in, assign Student object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"]) ? $_SESSION["LoggedInUser"] : (function() { header("Location: Login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])); die();})();

$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);
$albumRepo = new DBAlbumRepository($dbManager);
$pictureRepo = new DBPictureRepository($dbManager);
$friendshipRepo = new DBFriendshipRepository($dbManager);
$commentRepo = new DBCommentRepository($dbManager);

$dbManager->connect();
$friend = $userRepo->getID($_GET["id"]);
$friendAlbums = $albumRepo->getAllForUserAccessibility($friend->User_Id, "shared");
$areFriends = Friendship::AreUsersFriends($friendshipRepo, $LoggedInUser, $friend);
$dbManager->close();
?>
<div class="container">
    <?php if($areFriends) :?>
        <h1><?php echo $friend->Name; ?>'s Pictures</h1>
        <?php if (count($friendAlbums) == 0): ?>
        <div class="alert alert-danger">
            <p><span class="glyphicon glyphicon-thumbs-down"></span> This user does not have any shared albums!</p>
        </div>
        <?php else: ?>
        <div class="form-group">
            <input type="hidden" id="userId" value="<?php echo $LoggedInUser->User_Id; ?>" />
            <input type="hidden" id="ownerId" value="<?php echo $friend->User_Id; ?>" />
            <select id="albumSelect" class="form-control">
            <?php foreach($friendAlbums as $album): ?>
                <option value=<?php echo '"'.$album->Album_Id.'">'.$album->Title." — updated on ".$album->Date_Updated; ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <div>
            <h2 id="imageTitle"></h2>
            <div class="col-xs-9" id="images">
                <div>
                    <img class="img-responsive" id="displayImage" src="#" />
                </div>
                <div id="carousel">

                </div>
            </div>
            <div class="col-xs-3" id="text">
                <div id="descriptionContainer">
                    <h5>Description:</h5>
                    <p id="description"></p>
                </div>
                <div>
                    <h5>Comments:</h5>
                    <div id="commentsContainer">

                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php else : ?>
    <h1>Access Denied</h1>
    <p>You do not have access to this album.</p>
    <p>
        <a href="AddFriend.php?prefillUserID=<?php echo $friend->User_Id; ?>">Request this person be added to your friends list</a>in order to continue
    </p>
    <?php endif;?>
</div>

<script src="Scripts/gallery.js"></script>
<?php include "Common/Footer.php"; ?>