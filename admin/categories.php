<?php

/*
================================================
== Category Page
================================================
*/

ob_start(); // Output Buffering Start

session_start();

$pageTitle = 'Category';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        echo 'Welcome To Manage Page';
    } elseif ($do == 'Add') {
?>

        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-8">
                        <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" autocomplete="off">
                    </div>
                </div>
                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-8">
                        <input type="text" name="description" class="form-control" placeholder="Describe The Category">
                    </div>
                </div>
                <!-- Start Ordering Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-8">
                        <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories">
                    </div>
                </div>
                <!-- Start Visibility Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1">
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- Start Commenting Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" checked>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1">
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- Start Ads Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked>
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1">
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- Start Submit Field -->
                <div class="form-group" form-group-lg>
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
                    </div>
                </div>
            </form>
        </div>
<?php
    } elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Insert New Category</h1>";
            echo "<div class='container'>";

            // Get Variables From The Form
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $order      = $_POST['ordering'];
            $visible    = $_POST['visibility'];
            $comment    = $_POST['commenting'];
            $ads        = $_POST['ads'];

            // Check If User Exist In Database
            $check = checkItem("Name", "categories", $name);

            if ($check == 1) {

                $theMsg = "<div class='alert alert-danger'>Sorry This Category Is Exist</div>";
                redirectHome($theMsg, 'back');
            } else {

                // Insert User Info In Database
                $stmt = $con->prepare("INSERT INTO 
                                            categories(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads)
                                        VALUES
                                            (:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads)");
                $stmt->execute(array(
                    'zname'     => $name,
                    'zdesc'     => $desc,
                    'zorder'    => $order,
                    'zvisible'  => $visible,
                    'zcomment'  => $comment,
                    'zads'      => $ads
                ));

                // Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                redirectHome($theMsg, 'back');
            }
        } else {

            // Echo Error Message
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Sorry You Can\'t Browse This Page Directly</div>';
            redirectHome($theMsg, 'back');
            echo "</div>";
        }

        echo "</div>";
    } elseif ($do == 'Edit') {
    } elseif ($do == 'Update') {
    } elseif ($do == 'Delete') {
    }

    include $tpl . 'footer.php';
} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output
