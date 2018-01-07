<?php $pageTitle = "Add Friend"; include "Common/Header.php"; ?>
<?php session_start(); ?>
<?php
$LoggedInUser = isset($_SESSION["LoggedInUser"])?$_SESSION["LoggedInUser"]:(function(){header("Location: Login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI']));die();})();

if (!empty($_POST)) {
    $friendId = $_POST['friendId'];
    $errorMessage;
    if (!isset($friendId)) {
        $errorMessage = "Friend ID is required!";
    }
    elseif ($friendId == $LoggedInUser->User_Id) {
        $errorMessage = "You cannot send a friend request to yourself!";
    }
    if (!isset($errorMessage)) {
        $dbManager = new DBManager();
        $dbManager->connect();
        $userRepo = new DBUserRepository($dbManager);
        $friendRepo = new DBFriendshipRepository($dbManager);

        $friend = $userRepo->getID($friendId);
        if ($friend == null) {
            $errorMessage = "User $friendId does not exist!";
        }

        if (!isset($errorMessage) && Friendship::AreUsersFriends($friendRepo, $LoggedInUser, $friend)) {
            $errorMessage = "You are already friends!";
        }

        if (!isset($errorMessage)) {
            $friendship = $friendRepo->getID($friend->User_Id, $LoggedInUser->User_Id, 'request');
            if (!empty($friendship)) {
                $friendship->Status_Code = 'accepted';
                $success =$friendRepo->update($friendship);
            }
            else {
                $friendship = new Friendship($LoggedInUser->User_Id, $friend->User_Id, 'request');
                $success = $friendRepo->insert($friendship);
            }
        }
        $dbManager->close();
    }
}
?>

<div class="container">
    <h1>Add Friend</h1>
    <p>
        Welcome
        <strong>
            <?php echo $LoggedInUser->Name; ?>!
        </strong>(not you? change user
        <a href="Logout.php">here</a>)
    </p>
    <p>Enter the ID of the user you would like to be friends with:</p>
    <br />
    <?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger">
        <p>
            <span class="glyphicon glyphicon-thumbs-down"></span><?php echo $errorMessage; ?>
        </p>
    </div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
    <div class="alert alert-success">
        <p>
            <span class="glyphicon glyphicon-thumbs-up"></span>Your friend request has been sent to <?php echo "$friend->Name (ID: $friend->User_Id). Once $friend->Name accepts your request, you and $friend->Name will be friends and be able to view each other's shared albums."; ?>
        </p>
    </div>
    <?php endif; ?>
    <form action="AddFriend.php" method="post" class="form-inline center-block">
        <div class="form-group">
            <label for="friendId">ID:</label>
            <input type="text" class="form-control" name="friendId" id="friendId" placeholder="Friend ID" value="<?php echo $_GET["prefillUserID"];?>" />
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Send Friend Request" />
        </div>
    </form>
</div>

<?php include "Common/Footer.php"; ?>