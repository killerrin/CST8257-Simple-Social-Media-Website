<?php session_start(); ?>
<?php include "Common/Header.php"; ?>

<?php
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: Login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

// Get Accessibility Modes from DB
$dbManager = new DBManager();
$dbManager->connect();
$accessibilityRepo = new DBAccessibilityRepository($dbManager);
$accessibilityMode = $accessibilityRepo->getAll();
$dbManager->close();

?>

    <div class="container">
        <h1>Create New Album</h1>
        <p>
            Welcome back,
            <strong>
                <?php echo $LoggedInUser->Name; ?>!
            </strong>(not you? change user
            <a href="Logout.php">here</a>)
        </p>
        <form action="AddAlbum.php" method="post" name="albumForm" class="form-horizontal">
            <div class="form-group">
                <label for="title" class="col-xs-3 control-label">Title:</label>
                <div class="col-xs-9">
                    <input type="text" id="title" name="title" class="form-control" placeholder="Title" />
                </div>
            </div>
            <div class="form-group">
                <label for="accessibility" class="col-xs-3 control-label">Accessibility:</label>
                <div class="col-xs-9">
                    <select name="accessibility" id="accessibility" class="form-control">
                        <?php foreach($accessibilityMode as $mode): ?>
                        <option value=<?php echo "'$mode->Accessibility_Code' >$mode->Description"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-xs-3 control-label">Description:</label>
                <div class="col-xs-9">
                    <textarea id="description" name="description" class="form-control" placeholder="Description" rows="8"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9">
                    <input type="submit" value="Submit" class="btn btn-large btn-primary" />
                    <input type="reset" value="Clear" class="btn btn-large btn-info" />
                </div>
            </div>
        </form>
    </div>

<?php include "Common/Footer.php"; ?>