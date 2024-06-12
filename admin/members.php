<?php

/*
===================================================
== Manage Members Page
== You Can Add | Edit | Delete Members From Here
===================================================
*/

session_start();
$pageTitle = 'Members';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if ($do == 'Manage') {          // Manage Members Page

        // Select All Users Except Admin
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1");

        // Execute The Statement
        $stmt->execute();

        // Assign To Variable
        $rows = $stmt->fetchAll();
?>
        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registered Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['UserID'] . "</td>";
                        echo "<td>" . $row['Username'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['FullName'] . "</td>";
                        echo "<td>" . "" . "</td>";
                        echo "<td>
                            <a href='?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
        </div>

    <?php
    } elseif ($do == 'Add') {       // Add Member Page 
    ?>
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start Username Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-8">
                        <input type="text" name="username" class="form-control" required="required" placeholder="Username To Login" autocomplete="off">
                    </div>
                </div>
                <!-- Start Password Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-8">
                        <input type="password" name="password" class="password form-control" required="required" placeholder="Password Must Be Hard & Complex" autocomplete="new-password">
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <!-- Start Email Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid">
                    </div>
                </div>
                <!-- Start Full Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-8">
                        <input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page">
                    </div>
                </div>
                <!-- Start Submit Field -->
                <div class="form-group" form-group-lg>
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                    </div>
                </div>
            </form>
        </div>
        <?php
    } elseif ($do == 'Insert') {    // Insert Member Page

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Insert New Member</h1>";
            echo "<div class='container'>";

            // Get Variables From The Form
            $user   = $_POST['username'];
            $pass   = $_POST['password'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];

            $hashedPass = sha1($_POST['password']);

            // Validate The Form
            $formErrors = array();

            if (empty($user)) {
                $formErrors[] = 'Username Can\'t Be <strong>Empty</strong>';
            }

            if (strlen($user) < 4) {
                $formErrors[] = 'Username Can\'t Be Less Than <strong>4 Characters</strong>';
            }

            if (strlen($user) > 20) {
                $formErrors[] = 'Username Can\'t Be More Than <strong>20 Characters</strong>';
            }

            if (empty($pass)) {
                $formErrors[] = 'Password Can\'t Be <strong>Empty</strong>';
            }

            if (empty($email)) {
                $formErrors[] = 'Email Can\'t Be <strong>Empty</strong>';
            }

            if (empty($name)) {
                $formErrors[] = 'Full Name Can\'t Be <strong>Empty</strong>';
            }

            // Loop Into Errors Array And Echo It
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check If There's No Error Proceed The Update Operation
            if (empty($formErrors)) {

                // Insert User Info In Database
                $stmt = $con->prepare("INSERT INTO 
                                            users(Username, Password, Email, FullName)
                                        VALUES
                                            (:zuser, :zpass, :zmail, :zname)");
                $stmt->execute(array(
                    'zuser' => $user,
                    'zpass' => $hashedPass,
                    'zmail' => $email,
                    'zname' => $name
                ));

                // Echo Success Message
                echo "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated';
            }
        } else {
            redirectHome("You Can't Browse This Page Directly", 5);
        }

        echo "</div>";
    } elseif ($do == 'Edit') {      // Edit Page

        // Check If Get Request userid Is Numeric & Get The Integer Value Of It
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
        // Execute Query
        $stmt->execute(array($userid));
        // Fetch The Data
        $row = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();

        // If There's Such ID Show The Form
        if ($count > 0) { ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off">
                        </div>
                    </div>
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change">
                        </div>
                    </div>
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" required="required">
                        </div>
                    </div>
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="full" class="form-control" value="<?php echo $row['FullName'] ?>" required="required">
                        </div>
                    </div>
                    <!-- Start Submit Field -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" name="Save" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                </form>
            </div>
<?php
            // If There's No Such ID Show Error Message
        } else {
            echo 'Theres No Such ID';
        }
    } elseif ($do == 'Update') {    // Update Page

        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form
            $id     = $_POST['userid'];
            $user   = $_POST['username'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];

            // Password Trick
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            // Validate The Form
            $formErrors = array();

            if (empty($user)) {
                $formErrors[] = 'Username Can\'t Be <strong>Empty</strong>';
            }

            if (strlen($user) < 4) {
                $formErrors[] = 'Username Can\'t Be Less Than <strong>4 Characters</strong>';
            }

            if (strlen($user) > 20) {
                $formErrors[] = 'Username Can\'t Be More Than <strong>20 Characters</strong>';
            }

            if (empty($email)) {
                $formErrors[] = 'Email Can\'t Be <strong>Empty</strong>';
            }

            if (empty($name)) {
                $formErrors[] = 'Full Name Can\'t Be <strong>Empty</strong>';
            }

            // Loop Into Errors Array And Echo It
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check If There's No Error Proceed The Update Operation
            if (empty($formErrors)) {

                // Update The Database With This Info
                $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                $stmt->execute(array($user, $email, $name, $pass, $id));

                // Echo Success Message
                echo "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated';
            }
        } else {
            echo 'Sorry You Can\'t Browse This Page Directly';
        }

        echo "</div>";
    } elseif ($do == 'Delete') {    // Delete Member Page

        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

        // Check If Get Request userid Is Numeric & Get The Integer Value Of It
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");

        // Execute Query
        $stmt->execute(array($userid));

        // The Row Count
        $check = $stmt->rowCount();

        // If There's Such ID Show The Form
        if ($stmt->rowCount() > 0) {

            // Delete The Database With This Info
            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

            // Bind Parameter
            $stmt->bindParam(":zuser", $userid);

            // Execute Query
            $stmt->execute();

            echo "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted';
        } else {
            echo 'This ID Is Not Exist';
        }

        echo "</div>";
    }
    include $tpl . 'footer.php';    // Include Footer File
} else {

    header('Location: index.php');

    exit();
}
