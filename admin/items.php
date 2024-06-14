<?php

/*
================================================
== Items Page
================================================
*/

ob_start(); // Output Buffering Start

session_start();

$pageTitle = 'Items';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        echo 'Welcome To Manage Page';
    } elseif ($do == 'Add') {

?>

        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-8">
                        <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Item">
                    </div>
                </div>
                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-8">
                        <input type="text" name="description" class="form-control" placeholder="Description Of The Item">
                    </div>
                </div>
                <!-- Start Price Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-8">
                        <input type="text" name="price" class="form-control" required="required" placeholder="Price Of The Item">
                    </div>
                </div>
                <!-- Start Country Made Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-8">
                        <input type="text" name="country" class="form-control" placeholder="Country Of Made">
                    </div>
                </div>
                <!-- Start Status Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-8">
                        <select name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                        </select>
                    </div>
                </div>
                <!-- Start Members Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-8">
                        <select name="member">
                            <option value="0">...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                    echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- Start Categories Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-8">
                        <select name="category">
                            <option value="0">...</option>
                            <?php
                                $stmt2 = $con->prepare("SELECT * FROM categories");
                                $stmt2->execute();
                                $cats = $stmt2->fetchAll();
                                foreach ($cats as $cat) {
                                    echo "<option value='" . $cat['CatID'] . "'>" . $cat['Name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- Start Submit Field -->
                <div class="form-group" form-group-lg>
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Item" class="btn btn-primary btn-lg">
                    </div>
                </div>
            </form>
        </div>
<?php

    } elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Insert New Item</h1>";
            echo "<div class='container'>";

            // Get Variables From The Form
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['category'];

            // Validate The Form
            $formErrors = array();

            if (empty($name)) {
                $formErrors[] = 'Name Can\'t Be <strong>Empty</strong>';
            }

            if (empty($price)) {
                $formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';
            }

            if ($status == 0) {
                $formErrors[] = 'You Must Choose The <strong>Status</strong>';
            }

            if ($member == 0) {
                $formErrors[] = 'You Must Choose The <strong>Member</strong>';
            }

            if ($category == 0) {
                $formErrors[] = 'You Must Choose The <strong>Category</strong>';
            }

            // Loop Into Errors Array And Echo It
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check If There's No Error Proceed The Update Operation
            if (empty($formErrors)) {

                // Insert User Info In Database
                $stmt = $con->prepare("INSERT INTO 
                                            items(Name, Description, Price, Add_Date, Country_Made, Status, Member_ID, Cat_ID)
                                        VALUES
                                            (:zname, :zdesc, :zprice, now(), :zcountry, :zstatus, :zmember, :zcat)");
                $stmt->execute(
                    array(
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zprice'    => $price,
                        'zcountry'  => $country,
                        'zstatus'   => $status,
                        'zmember'   => $member,
                        'zcat'      => $category
                    )
                );

                // Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                redirectHome($theMsg, 'back');
            }
        } else {

            // Echo Error Message
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Sorry You Can\'t Browse This Page Directly</div>';
            redirectHome($theMsg);
            echo "</div>";
        }

        echo "</div>";
    } elseif ($do == 'Edit') {
    } elseif ($do == 'Update') {
    } elseif ($do == 'Delete') {
    } elseif ($do == 'Approve') {
    }

    include $tpl . 'footer.php';
} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output
