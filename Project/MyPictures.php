<?php //session_start(); ?>
<?php $pageTitle = "My Pictures"; include "Common/Header.php"; ?>

<?php
// If user is logged in, assign Student object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"]) ? $_SESSION["LoggedInUser"] : (function() { header("Location: Login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])); die();})();

$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);
$albumRepo = new DBAlbumRepository($dbManager);
$pictureRepo = new DBPictureRepository($dbManager);
$commentRepo = new DBCommentRepository($dbManager);

$dbManager->connect();
$albums = $albumRepo->getAllForUser($LoggedInUser->User_Id);

// Handle delete action
if (isset($_GET['action']) && isset($_GET['pictureID'])) {
    $picture = $pictureRepo->getID(urldecode($_GET['pictureID']));
    $imageManipulation = new ImageManipulation($LoggedInUser->User_Id, $picture->Album_Id, $dbManager, false);
    
    if ($_GET['action'] == 'delete') {
        if (isset($picture)) {
            $imageManipulation->DeletePictures($picture);
        }
    }

    if ($_GET['action'] == 'save' && isset($_GET['rotation'])) {
        if (isset($picture)) {
            $album = $albumRepo->getID($picture->Album_Id);
            $url = str_replace("\\",'/',"http://".$_SERVER['HTTP_HOST'].substr(getcwd(),strlen($_SERVER['DOCUMENT_ROOT'])))."/API/DisplayPicture.php?filePath=".urlencode($imageManipulation->CreateFilePath($imageManipulation->GetOriginalFolder(), $picture->FileName))."&action=rotate&rotation=".urldecode($_GET['rotation']);
            $imageManipulation->RotateAndSavePictures($picture, $_GET['rotation']);
        }
    }
    header("Location: MyPictures.php");
    die();
}

$dbManager->close();
?>
    <div class="container">
        <h1>My Pictures</h1>
        <?php if (count($albums) == 0): ?>
            <div class="alert alert-danger">
                <p><span class="glyphicon glyphicon-thumbs-down"></span> You do not have any albums!</p>
            </div>
        <?php else: ?>
            <div class="form-group">
                <input type="hidden" id="userId" value="<?php echo $LoggedInUser->User_Id; ?>" />
                <input type="hidden" id="ownerId" value="<?php echo $LoggedInUser->User_Id; ?>" />
                <select id="albumSelect" class="form-control">
                    <?php foreach($albums as $album): ?>
                    <option value=<?php echo '"'.$album->Album_Id.'"'.((isset($_GET['album']) && $_GET['album'] == $album->Album_Id) ? "selected " : "").'>'.$album->Title." — updated on ".$album->Date_Updated; ?></option>
                        <?php endforeach; ?>
                </select>
            </div>
            <div>
                <h2 id="imageTitle"></h2>
                <div class="col-xs-9" id="images">
                    <div class="img-container">
                        <img class="img-responsive" id="displayImage" src="#" />
                        <div id="currentImageLinkContainer" class="img-link-container">
                            <a id="rotateLeftLink" class="imageButton" data-action="rotateLeft" href="#">
                                <span class="glyphicon glyphicon-repeat gly-flip-horizontal"></span>
                            </a>
                            <a id="rotateRightLink" class="imageButton" data-action="rotateRight" href="#">
                                <span class="glyphicon glyphicon-repeat"></span>
                            </a>
                            <a id="downloadLink" class="imageButton" data-action="download" href="" download>
                                <span class="glyphicon glyphicon-save"></span>
                            </a>
                            <a id="deleteLink" class="imageButton" data-action="delete" href="#">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                            <a id="saveLink" class="imageButton" data-action="save" href="#">
                                <span class="glyphicon glyphicon-floppy-disk"></span>
                            </a>
                        </div>
                    </div>
                    <div id="carousel">

                    </div>
                </div>
                <div class="col-xs-3" id="text">
                    <div id="descriptionContainer">
                        <h4>Description:</h4>
                        <p id="description"></p>
                    </div>
                    <div>
                        <h4>Comments:</h4>
                        <div id="commentsContainer" style="overflow-y: scroll;">

                        </div>
                        <div id="commentForm" style="position: sticky; bottom: 0">
                            <div class="form-group">
                                <textarea id="commentText" placeholder="Leave comment..." class="form-control" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <button id="submitComment" class="btn btn-primary">Add Comment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="Scripts/gallery.js"></script>
<?php include "Common/Footer.php"; ?>