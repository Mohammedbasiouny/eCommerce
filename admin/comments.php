<?php

/*
===================================================
== Manage Comments Page
== You Can Edit | Delete Members From Here
===================================================
*/

session_start();
$pageTitle = 'Comments';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if ($do == 'Manage') {          // Manage Comments Page

        // Select All Users Except Admin
        $stmt = $con->prepare("SELECT 
                                    comments.*, items.Name AS Item_Name, users.Username AS User_Name
                                FROM 
                                    comments
                                INNER JOIN 
                                    items
                                ON 
                                    items.Item_ID = comments.Item_ID
                                INNER JOIN 
                                    users
                                ON 
                                    users.UserID = comments.User_ID
                                ORDER BY 
                                    C_ID DESC");
        // Execute The Statement
        $stmt->execute();

        // Assign To Variable
        $comments = $stmt->fetchAll();

        if (!empty($comments)) {
?>
            <h1 class="text-center">Manage Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>Username</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($comments as $comment) {
                            echo "<tr>";
                            echo "<td>" . $comment['C_ID'] . "</td>";
                            echo "<td>" . $comment['Comment'] . "</td>";
                            echo "<td>" . $comment['Item_Name'] . "</td>";
                            echo "<td>" . $comment['User_Name'] . "</td>";
                            echo "<td>" . $comment['Comment_Date'] . "</td>";
                            echo "<td>
                            <a href='?do=Edit&comid=" . $comment['C_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='?do=Delete&comid=" . $comment['C_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                            if ($comment['Status'] == 0) {
                                echo "<a href='?do=Approve&comid=" . $comment['C_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

        <?php

        } else {

            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Comments To Show</div>';
            echo '</div>';
        }
    } elseif ($do == 'Edit') {      // Edit Page

        // Check If Get Request comid Is Numeric & Get The Integer Value Of It
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT * FROM comments WHERE C_ID = ?");
        // Execute Query
        $stmt->execute(array($comid));
        // Fetch The Data
        $row = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();

        // If There's Such ID Show The Form
        if ($count > 0) { ?>
            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid ?>">
                    <!-- Start Comment Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" required="required" name="comment"><?php echo $row['Comment'] ?></textarea>
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

        echo "<h1 class='text-center'>Update Comment</h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form
            $id = $_POST['comid'];
            $comment = $_POST['comment'];

            // Update The Database With This Info
            $stmt = $con->prepare("UPDATE comments SET Comment = ? WHERE C_ID = ?");
            $stmt->execute(array($comment, $id));

            // Echo Success Message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
            redirectHome($theMsg, 'back');
        } else {

            $theMsg = '<div class="alert alert-danger">Sorry You Can\'t Browse This Page Directly</div>';
            redirectHome($theMsg);
        }

        echo "</div>";
    } elseif ($do == 'Delete') {    // Delete Comment Page

        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";

        // Check If Get Request comid Is Numeric & Get The Integer Value Of It
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        // Select All Data Depend On This ID
        $check = checkItem('C_ID', 'comments', $comid);

        // If There's Such ID Show The Form
        if ($check > 0) {

            // Delete The Database With This Info
            $stmt = $con->prepare("DELETE FROM comments WHERE C_ID = :zcom");

            // Bind Parameter
            $stmt->bindParam(":zcom", $comid);

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
    } elseif ($do == 'Approve') {    // Approve Comment Page

        echo "<h1 class='text-center'>Approve Comment</h1>";
        echo "<div class='container'>";

        // Check If Get Request comid Is Numeric & Get The Integer Value Of It
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        // Select All Data Depend On This ID
        $check = checkItem('C_ID', 'comments', $comid);

        // If There's Such ID Show The Form
        if ($check > 0) {

            // Delete The Database With This Info
            $stmt = $con->prepare("Update comments SET Status = 1 WHERE C_ID = ?");

            // Execute Query
            $stmt->execute(array($comid));

            // Echo Success Message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved</div>';
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
