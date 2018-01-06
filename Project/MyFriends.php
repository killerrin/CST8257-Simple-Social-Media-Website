<?php //session_start(); ?>
<?php include "Common/Header.php"; ?>

<?php
// If user is logged in, assign Student object to $LoggedInUser, otherwise redirect to login and die (self-executing function)
$LoggedInUser = isset($_SESSION["LoggedInUser"]) ? $_SESSION["LoggedInUser"] : (function() { header("Location: Login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])); die();})();

$dbManager = new DBManager();
$dbManager->connect();
$friendRepo = new DBFriendshipRepository($dbManager);
$userRepo = new DBUserRepository($dbManager);

$friendships = $friendRepo->getAllForUser($LoggedInUser->User_Id);

$friends = (function ($friendships, $userId, $userRepo) {
    $array = array();
    foreach ($friendships as $friendship) {
        if ($friendship->Status_Code == 'accepted') {
            if ($friendship->Friend_RequesterId == $userId) {
                array_push($array, $userRepo->getID($friendship->Friend_RequesteeId));
            }
            else {
                array_push($array, $userRepo->getID($friendship->Friend_RequesterId));
            }
        }
    }
    return $array;
})($friendships, $LoggedInUser->User_Id, $userRepo);

$requests = (function ($friendships, $userId) {
    $array = array();
    foreach ($friendships as $friendship) {
        if ($friendship->Status_Code == 'request' && $friendship->Friend_RequesteeId == $userId)
            array_push($array, $friendship);
    }
    return $array;
})($friendships, $LoggedInUser->User_Id);

$dbManager->close();
?>

<div class="container">
    <h1>My Friends</h1>
    <p>
        Welcome
        <strong>
            <?php echo $LoggedInUser->Name; ?>!
        </strong>(not you? change user
        <a href="Logout.php">here</a>)
    </p>
    <div class="col-xs-3">
        <p>Friends:</p>
    </div>
    <div class="col-xs-offset-6 col-xs-3">
        <a href="AddFriend.php">Add Friends</a>
    </div>
    <form action="MyFriends.php" method="post">
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Shared Albums</th>
                <th>Defriend</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($friends as $friend):
                $dbManager = new DBManager();
                $dbManager->connect();
                $albumRepo = new DBAlbumRepository($dbManager);

                $sharedAlbums = count($albumRepo->getAllForUserAccessibility($friend->User_Id, 'shared'));
                ?>
                <tr>
                    <td><?php echo $friend->Name; ?></td>
                    <td><?php echo $sharedAlbums; ?></td>
                    <td><input type="checkbox" name="defriend[]" value="<?php echo $friend->User_Id; ?>" /></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($friends) == 0): ?>
            <tr>
                <td colspan="3" align="center">There are no friends to display!</td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <input type="submit" class="btn btn-primary col-xs-offset-10" value="Defriend Selected" />
    </form>
    <br />
    <form action="MyFriends.php" method="post">
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Select</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($requests as $request): ?>
                <tr>
                    <td><?php $dbManager->connect(); echo $userRepo->getID($request->Friend_RequesterId)->Name; ?></td>
                    <td><input type="checkbox" name="request[]" value="<?php echo $request->Friend_RequesterId; ?>" /></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($requests) == 0): ?>
                <tr>
                    <td colspan="3" align="center">There are no friend requests to display!</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <input type="submit" class="btn btn-primary col-xs-offset-9" name="accept" value="Accept Selected" />
        <input type="submit" class="btn btn-danger" name="reject" value="Reject Selected" />
    </form>
</div>

<?php include "Common/Footer.php"; ?>