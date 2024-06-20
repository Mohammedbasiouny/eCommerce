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
            if (isset($_SESSION['user'])) {
                echo 'Welcome ' . $sessionUser . ' ';

                echo '<a href="profile.php"><span class="pull-right">My Profile</span></a>';

                echo '<a href="logout.php"><span class="pull-right">Logout</span></a>';

                if (checkUserStatus($sessionUser) == 1) {
                    echo 'Your Membership Need Activation By Admin';
                }
            } else {
                echo 'Welcome Guest';

                echo '<a href="login.php"><span class="pull-right">Login/Singup</span></a>';
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
                <a class="navbar-brand" href="dashboard.php">Home Page</a>
            </div>
            <div class="collapse navbar-collapse navbar-right" id="app-nav">
                <ul class="nav navbar-nav">
                    <?php
                    foreach (getCat() as $cat) {
                        echo '<li>
                                    <a href="categories.php?pageid=' . $cat['CatID'] . '&pagename=' . str_replace(' ', '-', $cat['Name']) . '">
                                        ' . $cat['Name'] . '
                                    </a>
                                </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>