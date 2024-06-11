<?php
session_start();

if (isset($_SESSION['Username'])) {

    include 'init.php';

    echo 'Welcome to Admin Dashboard';

    include $tpl . 'footer.php'; // Include Footer File
} else {

    header('Location: index.php');

    exit();
}
