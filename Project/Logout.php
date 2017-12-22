<?php //session_start(); ?>
<?php include "Common/Header.php"; ?>

<div class="container">
    <h1>Logout</h1>
</div>

<?php
// Completely annihilate the session
session_unset();
session_destroy();

// Redirect to Index.php
header("Location: Login.php");
die();
?>

<?php include "Common/Footer.php"; ?>