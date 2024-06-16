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

        // Select All Items Except Admin
        $stmt = $con->prepare("SELECT
                                    items.*, 
                                    categories.Name As category_name,
                                    users.Username 
                                FROM 
                                    items 
                                INNER JOIN  
                                    categories 
                                ON 
                                    categories.CatID = items.Cat_ID 
                                INNER JOIN 
                                    users 
                                ON 
                                    users.UserID = items.Member_ID
                                Order By 
                                    Item_ID DESC");

        // Execute The Statement
        $stmt->execute();

        // Assign To Variable
        $items = $stmt->fetchAll();

        if (!empty($items)) {
?>
            <h1 class="text-center">Manage Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Descriptionc</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($items as $item) {
                            echo "<tr>";
                            echo "<td>" . $item['Item_ID'] . "</td>";
                            echo "<td>" . $item['Name'] . "</td>";
                            echo "<td>" . $item['Description'] . "</td>";
                            echo "<td>" . $item['Price'] . "</td>";
                            echo "<td>" . $item['Add_Date'] . "</td>";
                            echo "<td>" . $item['category_name'] . "</td>";
                            echo "<td>" . $item['Username'] . "</td>";
                            echo "<td>
                            <a href='?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                            if ($item['Approve'] == 0) {
                                echo "<a href='?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
                <a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>
            </div>

        <?php

        } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Items To Show</div>';
            echo '<a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>';
            echo '</div>';
        }
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
                        <input type="text" name="description" class="form-control" required="required" placeholder="Description Of The Item">
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
                        <input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made">
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

            if (empty($desc)) {
                $formErrors[] = 'Description Can\'t Be <strong>Empty</strong>';
            }

            if (empty($price)) {
                $formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';
            }

            if (empty($country)) {
                $formErrors[] = 'Country Can\'t Be <strong>Empty</strong>';
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

            // Check If There's No Error Proceed The Update Operation
            if (empty($formErrors)) {

                // Insert User Info In Database
                $stmt = $con->prepare("INSERT INTO 
                                            items(Name, Description, Price, Add_Date, Country_Made, Status, Member_ID, Cat_ID)
                                        VALUES
                                            (:zname, :zdesc, :zprice, now(), :zcountry, :zstatus, :zmember, :zcat)");
                $stmt->execute(
                    array(
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zprice' => $price,
                        'zcountry' => $country,
                        'zstatus' => $status,
                        'zmember' => $member,
                        'zcat' => $category
                    )
                );

                // Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                redirectHome($theMsg, 'back');
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
    } elseif ($do == 'Edit') {

        // Check If Get Request itemid Is Numeric & Get The Integer Value Of It
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
        // Execute Query
        $stmt->execute(array($itemid));
        // Fetch The Data
        $item = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();

        // If There's Such ID Show The Form
        if ($count > 0) { ?>

            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="form-control" placeholder="Name Of The Item" value="<?php echo $item['Name'] ?>">
                        </div>
                    </div>
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
                            <input type="text" name="description" class="form-control" placeholder="Description Of The Item" value="<?php echo $item['Description'] ?>">
                        </div>
                    </div>
                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-8">
                            <input type="text" name="price" class="form-control" placeholder="Price Of The Item" value="<?php echo $item['Price'] ?>">
                        </div>
                    </div>
                    <!-- Start Country Made Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-8">
                            <input type="text" name="country" class="form-control" placeholder="Country Of Made" value="<?php echo $item['Country_Made'] ?>">
                        </div>
                    </div>
                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-8">
                            <select name="status">
                                <option value="1" <?php if ($item['Status'] == 1) {
                                                        echo 'selected';
                                                    } ?>>New</option>
                                <option value="2" <?php if ($item['Status'] == 2) {
                                                        echo 'selected';
                                                    } ?>>Like New</option>
                                <option value="3" <?php if ($item['Status'] == 3) {
                                                        echo 'selected';
                                                    } ?>>Used</option>
                            </select>
                        </div>
                    </div>
                    <!-- Start Members Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-8">
                            <select name="member">
                                <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                    echo "<option value='" . $user['UserID'] . "'";
                                    if ($item['Member_ID'] == $user['UserID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $user['Username'] . "</option>";
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
                                <?php
                                $stmt2 = $con->prepare("SELECT * FROM categories");
                                $stmt2->execute();
                                $cats = $stmt2->fetchAll();
                                foreach ($cats as $cat) {
                                    echo "<option value='" . $cat['CatID'] . "'";
                                    if ($item['Cat_ID'] == $cat['CatID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $cat['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- Start Submit Field -->
                    <div class="form-group" form-group-lg>
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Item" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                </form>
                <?php

                // Select All Users Except Admin
                $stmt = $con->prepare("SELECT
                                            comments.*, users.Username AS User_Name
                                        FROM
                                            comments
                                        INNER JOIN
                                            users
                                        ON
                                            users.UserID = comments.User_ID
                                        WHERE Item_ID = ?");
                // Execute The Statement
                $stmt->execute(array($itemid));
                // Assign To Variable
                $rows = $stmt->fetchAll();

                if (!empty($rows)) {

                ?>
                    <h1 class="text-center">Manage "<?php echo $item['Name']; ?>" Comments</h1>
                    <div class="container">
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered">
                                <tr>
                                    <td>Comment</td>
                                    <td>Username</td>
                                    <td>Added Date</td>
                                    <td>Control</td>
                                </tr>
                                <?php
                                foreach ($rows as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $row['Comment'] . "</td>";
                                    echo "<td>" . $row['User_Name'] . "</td>";
                                    echo "<td>" . $row['Comment_Date'] . "</td>";
                                    echo "<td>
                            <a href='?do=Edit&comid=" . $row['C_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='?do=Delete&comid=" . $row['C_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                    if ($row['Status'] == 0) {
                                        echo "<a href='?do=Approve&comid=" . $row['C_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                <?php } ?>

            </div>
<?php
            // If There's No Such ID Show Error Message
        } else {

            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
            redirectHome($theMsg);
            echo "</div>";
        }
    } elseif ($do == 'Update') {

        echo "<h1 class='text-center'>Update Item</h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form
            $id = $_POST['itemid'];
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

            if (empty($desc)) {
                $formErrors[] = 'Description Can\'t Be <strong>Empty</strong>';
            }

            if (empty($price)) {
                $formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';
            }

            if (empty($country)) {
                $formErrors[] = 'Country Can\'t Be <strong>Empty</strong>';
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

            // Check If There's No Error Proceed The Update Operation
            if (empty($formErrors)) {

                // Update The Database With This Info
                $stmt = $con->prepare("UPDATE 
                                            items 
                                        SET 
                                            Name = ?, Description = ?, Price = ?, Country_Made = ?, Status = ?, Cat_ID = ?, Member_ID = ? WHERE Item_ID = ?");
                $stmt->execute(array($name, $desc, $price, $country, $status, $category, $member, $id));

                // Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                redirectHome($theMsg, 'back');
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
    } elseif ($do == 'Delete') {

        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";

        // Check If Get Request itemid Is Numeric & Get The Integer Value Of It
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // Select All Data Depend On This ID
        $check = checkItem('Item_ID', 'items', $itemid);

        // If There's Such ID Show The Form
        if ($check > 0) {

            // Delete The Database With This Info
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zitem");

            // Bind Parameter
            $stmt->bindParam(":zitem", $itemid);

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
    } elseif ($do == 'Approve') { // Approve Item Page

        echo "<h1 class='text-center'>Approve Item</h1>";
        echo "<div class='container'>";

        // Check If Get Request itemid Is Numeric & Get The Integer Value Of It
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // Select All Data Depend On This ID
        $check = checkItem('Item_ID', 'items', $itemid);

        // If There's Such ID Show The Form
        if ($check > 0) {

            // Update The Database With This Info
            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");

            // Execute Query
            $stmt->execute(array($itemid));

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

    include $tpl . 'footer.php';
} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output
