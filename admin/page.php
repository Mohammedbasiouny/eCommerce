<?php

/*
    Categories => [ Manage | Edit | Update | Add | Insert | Delete | Stats ]
    Items => [ Manage | Edit | Update | Add | Insert | Delete | Stats ]
    Members => [ Manage | Edit | Update | Add | Insert | Delete | Stats ]
*/

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// If the page is the main page
if ($do == 'Manage') {
    echo 'Welcome You Are In Manage Category Page';
    echo '<a href="?do=Insert">Add New Category +</a>';
} elseif ($do == 'Add') {
    echo 'Welcome You Are In Add Category Page';
} elseif ($do == 'Insert') {
    echo 'Welcome You Are In Insert Category Page';
} else {
    echo 'Error There\'s No Page With This Name';
}