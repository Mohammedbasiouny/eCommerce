<?php

ob_start();
session_start();
include 'init.php';

?>

<div class="container">
    <div class="row">
        <?php
        if (isset($_GET['name'])) {

            $tag = $_GET['name'];
            echo '<h1 class="text-center">#' . $tag . '</h1>';

            $tagItems = getAllFrom("*", "items", "WHERE tags LIKE '%$tag%'", "AND Approve = 1", "Item_ID");
            foreach ($tagItems as $tagItem) {
                echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="thumbnail item-box">';
                echo '<span class="price-tag">$' . $tagItem['Price'] . '</span>';
                echo '<img class="img-responsive" src="img.png" alt="" />';
                echo '<div class="caption">';
                echo '<h3><a href="items.php?itemid=' . $tagItem['Item_ID'] . '">' . $tagItem['Name'] . '</a></h3>';
                echo '<p>' . $tagItem['Description'] . '</p>';
                echo '<div class="date">' . $tagItem['Add_Date'] . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="alert alert-danger">You Must Enter Tag Name</div>';
        }
        ?>
    </div>
</div>

<?php

include $tpl . 'footer.php';
ob_end_flush();

?>