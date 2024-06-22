<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getTitle() ?></title>
    <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>fontawesome.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="<?php echo $css ?>front.css" />
</head>

<body>
    <div class="upper-bar">
        <div class="container">
            <?php
            if (isset($_SESSION['user'])) { ?>

                <img class="img-thumbnail img-circle my-img" src="img.png" alt="...">
                <div class="btn-group my-info">
                    <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <?php echo $sessionUser ?>
                        <span class="caret"></span>
                    </span>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="newad.php">New Item</a></li>
                        <li><a href="profile.php#my-ads">My Items</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>

            <?php


            } else {
            ?>
                <a href="login.php">
                    <span class="pull-right">Login/Signup</span>
                </a>

            <?php
            }
            ?>
        </div>
    </div>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Home Page</a>
            </div>
            <div class="collapse navbar-collapse navbar-right" id="app-nav">
                <ul class="nav navbar-nav">
                    <?php

                    $cats = getAllFrom("*", "categories", "", "", "CatID", "ASC");

                    foreach ($cats as $cat) {
                        echo '<li>
                                    <a href="categories.php?pageid=' . $cat['CatID'] . '">
                                        ' . $cat['Name'] . '
                                    </a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>