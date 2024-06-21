<?php

ob_start();
session_start();
$pageTitle = 'Login';

// If Session Exist Redirect To Dashboard Page
if (isset($_SESSION['user'])) {
    header('Location: index.php');
}

include 'init.php';

// Check If User Coming From HTTP Post Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['login'])) {

        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hashedPass = sha1($pass);

        // Check If The User Exist In Database
        $stmt = $con->prepare("SELECT 
                                        Username, Password 
                                    FROM 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        Password = ?");

        $stmt->execute(array($user, $hashedPass));

        $count = $stmt->rowCount();

        // If Count > 0 This Mean The Database Contain Record About This Username
        if ($count > 0) {
            $_SESSION['user'] = $user; // Register Session Name
            header('Location: index.php'); // Redirect To Dashboard Page
            exit();
        }
    } else {

        $formErrors = array();

        if (isset($_POST['username'])) {
            $filteredUser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);

            if (strlen($filteredUser) < 4) {
                $formErrors[] = 'Username Must Be Larger Than 4 Characters';
            }
        }

        if (isset($_POST['password']) && isset($_POST['password2'])) {

            if (empty($_POST['password'])) {
                $formErrors[] = 'Sorry Password Can\'t Be Empty';
            }

            $pass1 = sha1($_POST['password']);
            $pass2 = sha1($_POST['password2']);

            if ($pass1 !== $pass2) {
                $formErrors[] = 'Sorry Password Is Not Match';
            }
        }

        if (isset($_POST['email'])) {
            $filteredEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

            if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] = 'This Email Is Not Valid';
            }
        }
    }
}
?>

<div class="container login-page">
    <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
    <!-- Start Login Form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required>
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type your password" required>
        </div>
        <div class="input-container">
            <input class="btn btn-primary" name="login" type="submit" value="Login">
        </div>
    </form>
    <!-- End Login Form -->
    <!-- Start Signup Form -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input pattern=".{4,}" title="Username Must Be Larger Than 4 Characters" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required>
        </div>
        <div class="input-container">
            <input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type a complex password" required>
        </div>
        <div class="input-container">
            <input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type a password again" required>
        </div>
        <div class="input-container">
            <input class="form-control" type="email" name="email" autocomplete="off" placeholder="Type a valid email" required>
        </div>
        <div class="input-container">
            <input class="btn btn-success" name="signup" type="submit" value="Signup">
        </div>
    </form>
    <!-- End Signup Form -->
    <div class="the-errors text-center">

        <?php
        if (!empty($formErrors)) {
            foreach ($formErrors as $error) {
                echo '<div class="msg error">' . $error . '</div>';
            }
        }
        ?>
    </div>
</div>

<?php

include $tpl . 'footer.php';
ob_end_flush();

?>