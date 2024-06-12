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

    if ($do == 'Manage') {

        // Manage Page

    } elseif ($do == 'Edit') {  // Edit Page
?>

        <h1 class="text-center">Edit Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">

                <!-- Start Username Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" name="username" class="form-control" autocomplete="off">
                    </div>
                </div>
                <!-- Start Password Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                    </div>
                </div>
                <!-- Start Email Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
                <!-- Start Full Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="full" class="form-control">
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

<?php }

    include $tpl . 'footer.php'; // Include Footer File
} else {

    header('Location: index.php');

    exit();
}
