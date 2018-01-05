<?php //session_start(); ?>
<?php include "Common/Header.php"; ?>

<?php
// If user is logged in, assign Student object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: Login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

$dbManager = new DBManager();
$dbManager->connect();
$albumRepo = new DBAlbumRepository($dbManager);
$accessibilityRepo = new DBAccessibilityRepository($dbManager);

$accessibilityMode = $accessibilityRepo->getAll();

// Handle form submission
if (!empty($_POST)) {
    foreach ($_POST['albumId'] as $index => $value) {
        $newAlbum = $albumRepo->getID($value)[0];
        $newAlbum->Accessibility_Code = $_POST['newAccessibility'][$index];
        $result = $albumRepo->update($newAlbum);
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $result = $albumRepo->delete($albumRepo->getID($_GET['delete'])[0]);
}

// self executing function fetches albums and filters without polluting the global namespace 8^)
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
    <h1>My Albums</h1>
    <p>Welcome <strong><?php echo $LoggedInUser->Name; ?>!</strong> (not you? change user <a href="Logout.php">here</a>.)</p>
    <?php if(isset($result) && !$result): ?>
    <div class="alert alert-danger">
        <p><span class="glyphicon-thumbs-down glyphicon"></span> An error occurred!</p>
    </div>
    <?php endif;
    if(isset($result) && $result): ?>
    <div class="alert alert-success">
        <p><span class="glyphicon-thumbs-up glyphicon"></span> Albums updated!</p>
    </div>
    <?php endif; ?>
    <div class="col-xs-offset-9 col-xs-3">
        <a href="AddAlbum.php">Create a New Album</a>
    </div>
    <form action="MyAlbums.php" class="form-horizontal" method="post">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date Updated</th>
                    <th>Number of Pictures</th>
                    <th>Accessibility</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($albums) > 0): ?>
                <?php foreach($albums as $album): ?>
                <tr>
                    <td><a href="MyPictures.php?album=<?php echo $album->Album_Id; ?>" ><?php echo $album->Title; ?></td>
                    <td><?php echo $album->Date_Updated; ?></td>
                    <td><?php echo 0;//TODO: count($album->getPictures()); ?></td>
                    <td>
                        <input type="hidden" name="albumId[]" value="<?php echo $album->Album_Id; ?>" />
                        <select name="newAccessibility[]" class="form-control">
                            <?php foreach($accessibilityMode as $mode): ?>
                                <option value=<?php echo "'$mode->Accessibility_Code' ".($mode->Accessibility_Code == $album->Accessibility_Code ? "selected='selected'":"")." >$mode->Description"; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <a href="?delete=<?php echo $album->Album_Id; ?>" onclick="return confirm('Are you sure you wish to delete the album <?php echo $album->Title; ?>?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif;
                if (count($albums) == 0): ?>
                <tr>
                    <td colspan="5" align="center">There are no albums to display!</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="col-xs-offset-9 col-xs-3">
            <input type="submit" class="btn btn-primary btn-large" value="Save Changes" />
        </div>
    </form>
</div>

<?php include "Common/Footer.php"; ?>