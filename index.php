<?php

ob_start();
session_start();

$pageTitle = 'Homepage';

include 'init.php';

include $tpl . 'footer.php';
ob_end_flush();
