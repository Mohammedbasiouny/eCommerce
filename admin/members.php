<?php

/*
===================================================
== Manage Members Page
== You Can Add | Edit | Delete Members From Here
===================================================
*/
ob_start();
session_start();
$pageTitle = 'Members';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if ($do == 'Manage') {          // Manage Members Page

        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        // Select All Users Except Admin
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");

        // Execute The Statement
        $stmt->execute();

        // Assign To Variable
        $rows = $stmt->fetchAll();

        if (!empty($rows)) {
?>
            <h1 class="text-center">Manage Members</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-members text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
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
                            echo "<td>";
                            if (empty($row['Avatar'])) {
                                echo "<img src='uploads/avatars/default.png' alt=''>";
                            } else {
                                echo "<img src='uploads/avatars/" . $row['Avatar'] . "' alt=''>";
                            }
                            echo "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                            <a href='?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                            if ($row['RegStatus'] == 0) {
                                echo "<a href='?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
                <a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
            </div>

        <?php
        } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Members To Show</div>';
            echo '<a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus fa-lg"></i> New Member</a>';
            echo '</div>';
        }
    } elseif ($do == 'Add') {       // Add Member Page 
        ?>
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
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
                <!-- Start Avatar Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">User Avatar</label>
                    <div class="col-sm-8">
                        <input type="file" name="avatar" class="form-control" required="required">
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

            // Upload Variables
            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            // List Of Allowed File Typed To Upload
            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

            // Get Avatar Extension
            $avatarArray = explode(".", $avatarName);
            $avatarExtension = strtolower(end($avatarArray));

            // Get Variables From The Form
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['full'];

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

            if (empty($avatarName)) {
                $formErrors[] = 'Avatar Is <strong>Required</strong>';
            }

            if ($avatarSize > 4194304) {
                $formErrors[] = 'Avatar Can\'t Be Larger Than <strong>4MB</strong>';
            }

            if (!empty($avatarName) && !in_array($avatarExtension, $avatarAllowedExtension)) {
                $formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
            }


            // Check If There's No Error Proceed The Update Operation
            if (empty($formErrors)) {

                $avatar = rand(0, 1000000000) . '_' . $avatarName;
                move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

                // Check If User Exist In Database
                $check = checkItem("Username", "users", $user);

                if ($check == 1) {

                    $theMsg = "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                    redirectHome($theMsg, 'back');
                } else {

                    // Insert User Info In Database
                    $stmt = $con->prepare("INSERT INTO 
                                                users(Username, Password, Email, FullName, RegStatus, Date, Avatar)
                                            VALUES
                                                (:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar)");
                    $stmt->execute(
                        array(
                            'zuser' => $user,
                            'zpass' => $hashedPass,
                            'zmail' => $email,
                            'zname' => $name,
                            'zavatar' => $avatar
                        )
                    );

                    // Echo Success Message
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                    redirectHome($theMsg, 'back');
                }
            } else {
                // Loop Into Errors Array And Echo It
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                redirectHome('', 'back');
            }
        } else {

            // Echo Error Message
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Sorry You Can\'t Browse This Page Directly</div>';
            redirectHome($theMsg);
            echo "</div>";
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
                <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-8">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off">
                        </div>
                    </div>
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change">
                        </div>
                    </div>
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" required="required">
                        </div>
                    </div>
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="full" class="form-control" value="<?php echo $row['FullName'] ?>" required="required">
                        </div>
                    </div>
                    <!-- Start Avatar Field -->
                    <div class="form-group form-group-lg">
                        <label for="avatar" class="col-sm-2 control-label">Choose avatar</label>
                        <div class="col-sm-8">
                            <?php if (!empty($row['Avatar'])) : ?>
                                <!-- Show current avatar -->
                                <div class="custom-file mb-2">
                                    <input type="file" name="avatar" class="custom-file-input form-control" id="avatar">
                                </div>
                                <img src="uploads/avatars/<?php echo $row['Avatar']; ?>" alt="Avatar" class="avatar-preview img-thumbnail mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_avatar" id="remove_avatar">
                                    <label class="form-check-label" for="remove_avatar">
                                        Remove current avatar
                                    </label>
                                </div>
                            <?php else : ?>
                                <!-- Show default avatar upload -->
                                <div class="custom-file mb-2">
                                    <input type="file" name="avatar" class="custom-file-input form-control" id="avatar" required="required">
                                </div>
                                <img src="uploads/avatars/default.png" alt="Default Avatar" class="avatar-preview img-thumbnail">
                            <?php endif; ?>
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

            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
            redirectHome($theMsg);
            echo "</div>";
        }
    } elseif ($do == 'Update') {    // Update Page

        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form
            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];
            $avatar = $_FILES['avatar']; // File input for avatar
            $removeAvatar = isset($_POST['remove_avatar']); // Checkbox to remove current avatar

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

            // Check If There's No Error Proceed The Update Operation
            if (empty($formErrors)) {

                $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");

                $stmt2->execute(array($user, $id));

                $count = $stmt2->rowCount();

                if ($count == 1) {

                    $theMsg = "<div class='alert alert-danger'>Sorry This Username Is Exist</div>";
                    redirectHome($theMsg, 'back');
                } else {

                    // Fetch current avatar name from database
                    $stmtAvatar = $con->prepare("SELECT Avatar FROM users WHERE UserID = ?");
                    $stmtAvatar->execute(array($id));
                    $row = $stmtAvatar->fetch();

                    $currentAvatar = $row['Avatar'];

                    // Determine the avatar update action
                    if ($removeAvatar) {
                        // User wants to remove current avatar
                        // Remove avatar file from server
                        if (!empty($currentAvatar) && $currentAvatar != 'default.png') {
                            $avatarPath = 'uploads/avatars/' . $currentAvatar;
                            if (file_exists($avatarPath)) {
                                unlink($avatarPath); // Delete the file from the server
                            }
                        }

                        $avatarName = ''; // Set avatar name to empty
                    } else {
                        // User uploads a new avatar
                        if (!empty($avatar['name'])) {
                            // Delete old avatar if it exists
                            if (!empty($currentAvatar) && $currentAvatar != 'default.png') {
                                $avatarPath = 'uploads/avatars/' . $currentAvatar;
                                if (file_exists($avatarPath)) {
                                    unlink($avatarPath); // Delete the file from the server
                                }
                            }

                            // Upload new avatar
                            $avatarName = uniqid('avatar_') . '_' . $avatar['name'];
                            $avatarTmp = $avatar['tmp_name'];
                            $avatarPath = 'uploads/avatars/' . $avatarName;
                            move_uploaded_file($avatarTmp, $avatarPath);
                        } else {
                            // No new avatar uploaded, keep current avatar
                            $avatarName = $currentAvatar;
                        }
                    }

                    // Update The Database With This Info
                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ?, Avatar = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $avatarName, $id));

                    // Echo Success Message
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                    redirectHome($theMsg, 'back');
                }
            } else {
                // Loop Into Errors Array And Echo It
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                redirectHome('', 'back');
            }
        } else {

            $theMsg = '<div class="alert alert-danger">Sorry You Can\'t Browse This Page Directly</div>';
            redirectHome($theMsg);
        }

        echo "</div>";
    } elseif ($do == 'Delete') {    // Delete Member Page

        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

        // Check If Get Request userid Is Numeric & Get The Integer Value Of It
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Select All Data Depend On This ID
        $check = checkItem('UserID', 'users', $userid);

        // If There's Such ID Show The Form
        if ($check > 0) {

            // Fetch current avatar name from database
            $stmtAvatar = $con->prepare("SELECT Avatar FROM users WHERE UserID = ?");
            $stmtAvatar->execute(array($userid));
            $row = $stmtAvatar->fetch();
            $currentAvatar = $row['Avatar'];

            // Delete avatar file from server if it exists and is not the default avatar
            if (!empty($currentAvatar) && $currentAvatar != 'default.png') {
                $avatarPath = 'uploads/avatars/' . $currentAvatar;
                if (file_exists($avatarPath)) {
                    unlink($avatarPath); // Delete the file from server
                }
            }

            // Delete The Database With This Info
            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

            // Bind Parameter
            $stmt->bindParam(":zuser", $userid);

            // Execute Query
            $stmt->execute();

            // Echo Success Message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
            redirectHome($theMsg, 'back');
        } else {

            // Echo Error Message
            $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
            redirectHome($theMsg);
        }

        echo "</div>";
    } elseif ($do == 'Activate') {    // Activate Member Page

        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";

        // Check If Get Request userid Is Numeric & Get The Integer Value Of It
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Select All Data Depend On This ID
        $check = checkItem('UserID', 'users', $userid);

        // If There's Such ID Show The Form
        if ($check > 0) {

            // Delete The Database With This Info
            $stmt = $con->prepare("Update users SET RegStatus = 1 WHERE UserID = ?");

            // Execute Query
            $stmt->execute(array($userid));

            // Echo Success Message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
            redirectHome($theMsg, 'back');
        } else {

            // Echo Error Message
            $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
            redirectHome($theMsg);
        }

        echo "</div>";
    }
    include $tpl . 'footer.php';    // Include Footer File
} else {

    header('Location: index.php');

    exit();
}

ob_end_flush();
