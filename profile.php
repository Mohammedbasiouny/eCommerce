<?php

ob_start();
session_start();
$pageTitle = 'Profile';

include 'init.php';

if (isset($_SESSION['user'])) {
    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
    $userid = $info['UserID'];
    // $stmt = $con->prepare("SELECT * FROM items WHERE Member_ID = ?");
    // $stmt->execute(array($userid));
    // $items = $stmt->fetchAll();
    // $stmt2 = $con->prepare("SELECT * FROM comments WHERE User_ID = ?");
    // $stmt2->execute(array($userid));
    // $comments = $stmt2->fetchAll();
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
                            <span>Login Name</span> : <?php echo $info['Username'] ?>
                        </li>
                        <li>
                            <i class="fa fa-envelope fa-fw"></i>
                            <span>Email</span> : <?php echo $info['Email'] ?>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Full Name</span> : <?php echo $info['FullName'] ?>
                        </li>
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Register Date</span> : <?php echo $info['Date'] ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Fav Category</span> :
                        </li>
                    </ul>
                    <a href="#" class="btn btn-default">Edit Information</a>
                </div>
            </div>
        </div>
    </div>

    <div id="my-ads" class="my-ads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Items</div>
                <div class="panel-body">
                    <?php

                    $items = getAllFrom("*", "items", "WHERE Member_ID = $userid", "", "Item_ID", "DESC");

                    if (!empty($items)) {
                        echo '<div class="row">';
                        foreach ($items as $item) {
                            echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                            if ($item['Approve'] == 0) {
                                echo '<span class="approve-status">Waiting Approval</span>';
                            } else {
                                echo '<span class="approve-status">Approved</span>';
                            }
                            echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                            echo '<img class="img-responsive" src="img.png" alt="...">';
                            echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo 'There\'s No Ads To Show, Create <a href="newad.php">New Ad</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="my-comments block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Latest Comments</div>
                <div class="panel-body">
                    <?php

                    $myComments = getAllFrom("*", "comments", "WHERE User_ID = $userid", "", "C_ID", "DESC");
                    if (!empty($myComments)) {
                        foreach ($myComments as $comment) {
                            echo '<p>' . $comment['Comment'] . '</p>';
                        }
                    } else {
                        echo 'There\'s No Comments To Show';
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