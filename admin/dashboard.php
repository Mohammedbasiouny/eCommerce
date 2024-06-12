<?php
session_start();

if (isset($_SESSION['Username'])) {

    $pageTitle = 'Dashboard';

    include 'init.php';

    
    include $tpl . 'footer.php'; // Include Footer File
} else {

    header('Location: index.php');

    exit();
}
