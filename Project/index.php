<?php //session_start(); ?>
<?php include "Common/Header.php"; ?>

<?php
$LoggedInUser = $_SESSION["LoggedInUser"];
?>

<div class="container">
    <h1>Welcome to Algonquin Social Media Website</h1>

    <?php if (empty($LoggedInUser)) : ?>
    <p>
        If you have never used this before, you have to
        <a href="NewUser.php">sign up</a>&nbsp;first.
    </p>
    <p>
        If you have already signed up, you can
        <a href="Login.php">login</a>&nbsp;now.
    </p>
    <?php else : ?>
    <p>
        Welcome back, 
        <strong>
            <?php echo $LoggedInUser->name; ?>!
        </strong>(not you? change user
        <a href="Logout.php">here</a>)
    </p>
    <?php endif; ?>
</div>

<?php include "Common/Footer.php"; ?>