<?php

session_start();

$pageTitle = 'Profile';

include 'init.php';

?>


<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Login Name</span> : <?php echo $_SESSION['user'] ?>
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <span>Email</span> : <?php echo $_SESSION['email'] ?>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Full Name</span> : <?php echo $_SESSION['full'] ?>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Register Date</span> : <?php echo $_SESSION['date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Fav Category</span> :
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Items</div>
            <div class="panel-body">
                <div class="row">
                   ads
                </div>
            </div>
        </div>
    </div>
</div>

<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                comm
            </div>
        </div>
    </div>
</div>





<?php include $tpl . 'footer.php'; ?>