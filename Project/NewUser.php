<?php //session_start(); ?>
<?php include "Common/Header.php"; ?>

<?php
if ($_POST) {
    $studentID = $_POST["inputStudentNumber"];
    $name = $_POST["inputName"];
    $phone = $_POST["inputPhone"];
    $password = $_POST["inputPassword"];
    $password2 = $_POST["inputPassword2"];

    if (empty($studentID)) { $studentIDError = "Student ID can not be blank"; }
    if (empty($name)) { $nameError = "Name can not be blank"; }
    if (empty($phone)) { $phoneError = "Phone number can not be blank"; }
    if (empty($password)) { $passwordError = "Password can not be blank"; }
    if (empty($password2)) { $password2Error = "Password can not be blank"; }

    if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)) { $phoneError = "Your phone number does not fit the format of (nnn-nnn-nnnn)"; }
    if ($password != $password2) { $password2Error = "Your passwords do not match"; }

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    if(!$uppercase || !$lowercase || !$number || strlen($password) < 6) {
        $passwordError = "Password must be atleast 6 characters long and contain 1 Uppercase, 1 Lowercase and 1 Digit";
    }

    // Create the DB and get the Users
    $dbManager = new DBManager();
    $studentRepo = new DBStudentRepository($dbManager);
    $dbManager->connect();

    $student = $studentRepo->getID($studentID);
    if (!empty($student)) {
        $studentIDError = "A student with this ID already exists";
    }

    if (empty($studentIDError) && empty($nameError) && empty($phoneError) && empty($passwordError) && empty($password2Error)) {
        $student = new Student($studentID, $name, $phone, $password);
        $dbInsertResult = $studentRepo->insert($student);
    }

    $dbManager->close();

    if ($dbInsertResult) {
        $_SESSION["LoggedInUser"] = $student;

        header("Location: CourseSelection.php");
        die();
    }
}
?>

<div class="container">
    <h1>New User</h1>
    <p>
        If you have an account,
        <a href="Login.php">login</a>
    </p>

    <hr />

    <p>All fields are required</p>

    <form class="form-horizontal" action="NewUser.php" method="post">
        <div class="form-group">
            <label for="inputStudentNumber" class="col-sm-2 control-label">StudentID</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="inputStudentNumber" name="inputStudentNumber" placeholder="Student ID" value="<?php echo $studentID; ?>" required />
            </div>
            <div class="col-sm-6">
                <p class="error">
                    <?php echo $studentIDError; ?>
                </p>
            </div>
        </div>
        <div class="form-group">
            <label for="inputName" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="inputName" name="inputName" placeholder="Terry Fox" value="<?php echo $name; ?>" required />
            </div>
            <div class="col-sm-6">
                <p class="error">
                    <?php echo $nameError; ?>
                </p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2">
                <label for="inputPhone" class="control-label">Phone Number</label>
                <p>(nnn-nnn-nnnn)</p>
            </div>
            <div class="col-sm-4">
                <input type="tel" class="form-control" id="inputPhone" name="inputPhone" placeholder="123-456-7890" value="<?php echo $phone; ?>" required />
            </div>
            <div class="col-sm-6">
                <p class="error">
                    <?php echo $phoneError; ?>
                </p>
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password" value="<?php echo $password; ?>" required />
            </div>
            <div class="col-sm-6">
                <p class="error">
                    <?php echo $passwordError; ?>
                </p>
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword2" class="col-sm-2 control-label">Password Again</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" id="inputPassword2" name="inputPassword2" placeholder="Repeat Password" value="<?php echo $password2; ?>" required />
            </div>
            <div class="col-sm-6">
                <p class="error"><?php echo $password2Error; ?></p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Submit</button>
                <button type="reset" class="btn btn-default">Clear</button>
            </div>
        </div>
    </form>
</div>

<?php include "Common/Footer.php"; ?>