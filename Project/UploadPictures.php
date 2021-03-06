<?php //session_start(); ?>
<?php $pageTitle = "Upload Pictures"; include "Common/Header.php"; ?>

<?php
// If user is logged in, assign Student object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"]) ? $_SESSION["LoggedInUser"] : (function() { header("Location: Login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])); die();})();

$dbManager = new DBManager();
$dbManager->connect();
$albumRepo = new DBAlbumRepository($dbManager);

$emptyFieldError = false;

// Handle form submission
if (!empty($_POST)) {
    if (empty($_POST['album']) || empty($_FILES['file']))
        $emptyFieldError = true;
    if (!$emptyFieldError) {
        $imageManipulation = new ImageManipulation($LoggedInUser->User_Id, $_POST['album'], $dbManager, true);
        foreach ((array) $_FILES['file']['name'] as $index => $value) {
            $rand = rand(1, 99).rand(1, 99).rand(1, 9);
            $imageManipulation->SavePictures(((array) $_FILES['file']['tmp_name'])[$index], new Picture(null, $_POST['album'], $rand.$value, $_POST['title'], $_POST['description'], date('Y-m-d H:i:s')));

        }
        $updatedAlbum = $albumRepo->getID($_POST['album']);
        $updatedAlbum->Date_Updated = date('Y-m-d H:i:s');
        $albumRepo->update($updatedAlbum);
        $uploadSuccess = true;
    }
}

$albums = (function($LoggedInUser, $albumRepo) {
    $array = array();
    foreach ($albumRepo->getAll() as $album) {
        if ($album->Owner_Id == $LoggedInUser->User_Id)
            array_push($array, $album);
    }
    return $array;
})($LoggedInUser, $albumRepo);

$dbManager->close();
?>

<div class="container">
    <h1>Upload Pictures</h1>
    <p>Accepted picture types: JPG (JPEG), GIF and PNG.</p>
    <p>You can upload multiple pictures at a time by pressing the shift key when selecting pictures.</p>
    <p>When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
    <br />
    <?php if ($emptyFieldError): ?>
    <div class="alert alert-danger">
        <p><span class="glyphicon glyphicon-thumbs-down"></span> All fields are required!</p>
    </div>
    <?php endif; ?>
    <?php if ($uploadSuccess): ?>
    <div class="alert alert-success">
        <p><span class="glyphicon glyphicon-thumbs-up"></span> Pictures uploaded successfully!</p>
    </div>
    <?php endif; ?>
    <form action="UploadPictures.php" class="form-horizontal" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label class="col-xs-3 control-label" for="album">Upload to Album:</label>
            <div class="col-xs-9">
                <select id="album" name="album" class="form-control" <?php if (count($albums) == 0) echo "disabled"; ?>>
                    <?php foreach($albums as $album): ?>
                    <option value="<?php echo $album->Album_Id; ?>"><?php echo $album->Title; ?></option>
                    <?php endforeach; ?>
                    <?php if (count($albums) == 0): ?>
                    <option>Please add an album first!</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label" for="file">File to Upload:</label>
            <div class="col-xs-9">
                <input type="file" id="file" name="file[]" multiple accept="image/png, image/jpeg, image/gif" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label" for="title">Title:</label>
            <div class="col-xs-9">
                <input type="text" id="title" name="title" class="form-control" placeholder="Title" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label" for="description">Description:</label>
            <div class="col-xs-9">
                <textarea name="description" id="description" class="form-control" rows="8" placeholder="Description"></textarea>
            </div>
        </div>
        <div class="form-group">
            <input type="submit" value="Submit" class="btn btn-primary btn-lg" <?php if (count($albums) == 0) echo "disabled"; ?>/>
            <input type="reset" value="Clear" class="btn btn-default btn-lg" />
        </div>
    </form>
</div>

<?php include "Common/Footer.php"; ?>