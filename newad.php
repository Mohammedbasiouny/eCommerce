<?php

ob_start();
session_start();
$pageTitle = 'Create New Item';
include 'init.php';
if (isset($_SESSION['user'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $formErrors = array();

        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);

        if (strlen($name) < 4) {
            $formErrors[] = 'Item Title Must Be At Least 4 Characters';
        }
        if (strlen($desc) < 10) {
            $formErrors[] = 'Item Description Must Be At Least 10 Characters';
        }
        if (strlen($country) < 2) {
            $formErrors[] = 'Item Country Must Be At Least 2 Characters';
        }
        if (empty($price)) {
            $formErrors[] = 'Item Price Must Be Number and Not Empty';
        }
        if (empty($status)) {
            $formErrors[] = 'Item Status Must Be Not Empty';
        }
        if (empty($category)) {
            $formErrors[] = 'Item Category Must Be Not Empty';
        }

        if (empty($formErrors)) {
            // Insert Userinfo In Database
            $stmt = $con->prepare("INSERT INTO 
                                    items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID) 
                                    VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember)");
            $stmt->execute(array(
                'zname' => $name,
                'zdesc' => $desc,
                'zprice' => $price,
                'zcountry' => $country,
                'zstatus' => $status,
                'zcat' => $category,
                'zmember' => $_SESSION['uid']
            ));
            // Echo Success Message
            if ($stmt) {
                $successMsg = 'Item Has Been Added';
            }
        }
    }
?>
    <h1 class="text-center"><?php echo $pageTitle ?></h1>
    <div class="create-ad block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $pageTitle ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                <!-- Start Name Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-10 col-md-10">
                                        <input pattern=".{4,}" title="This Field Require At Least 4 Characters" type="text" name="name" class="form-control live" required="required" placeholder="Name Of The Item" data-class=".live-title">
                                    </div>
                                </div>
                                <!-- Start Description Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10 col-md-10">
                                        <input pattern=".{10,}" title="This Field Require At Least 10 Characters" type="text" name="description" class="form-control live" required="required" placeholder="Description Of The Item" data-class=".live-desc">
                                    </div>
                                </div>
                                <!-- Start Price Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Price</label>
                                    <div class="col-sm-10 col-md-10">
                                        <input type="text" name="price" class="form-control live" required="required" placeholder="Price Of The Item" data-class=".live-price">
                                    </div>
                                </div>
                                <!-- Start Country Made Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-10 col-md-10">
                                        <input pattern=".{2,}" title="This Field Require At Least 2 Characters" type="text" name="country" class="form-control" required="required" placeholder="Country Of Made">
                                    </div>
                                </div>
                                <!-- Start Status Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-10 col-md-10">
                                        <select name="status" required>
                                            <option value="">...</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Used</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Start Categories Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Category</label>
                                    <div class="col-sm-10 col-md-10">
                                        <select name="category" required>
                                            <option value="">...</option>
                                            <?php
                                            $cats = getAllFrom('*', 'categories', '', '', 'CatID');
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
                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
                                <span class="price-tag">$<span class="live-price">0</span></span>
                                <img class="img-responsive" src="img.png" alt="...">
                                <div class="caption">
                                    <h3 class="live-title">Title</h3>
                                    <p class="live-desc">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start Looping Through Errors -->
                    <?php
                    if (!empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
                    if (isset($successMsg)) {
                        echo '<div class="alert alert-success">' . $successMsg . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    header('Location: login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();

?>