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

    // Check If User Coming From Login Form
    if (isset($_POST['login'])) {

        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hashedPass = sha1($pass);

        // Check If The User Exist In Database
        $stmt = $con->prepare("SELECT 
                                        UserID, Username, Password 
                                    FROM 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        Password = ?");
        // Execute Query
        $stmt->execute(array($user, $hashedPass));

        // Fetch The Data
        $get = $stmt->fetch();

        // Fetch The Data
        $count = $stmt->rowCount();

        // If Count > 0 This Mean The Database Contain Record About This Username
        if ($count > 0) {

            $_SESSION['user'] = $user; // Register Session Name
            
            $_SESSION['uid'] = $get['UserID']; // Register User ID in Session

            header('Location: index.php'); // Redirect To Dashboard Page
            exit();
        }
    } else {

        $formErrors = array();

        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $email = $_POST['email'];

        if (isset($username)) {
            $filteredUser = filter_var($username, FILTER_SANITIZE_STRING);

            if (strlen($filteredUser) < 4) {
                $formErrors[] = 'Username Must Be Larger Than 4 Characters';
            }
        }

        if (isset($password) && isset($password2)) {

            if (empty($password)) {
                $formErrors[] = 'Sorry Password Can\'t Be Empty';
            }

            if (sha1($password) !== sha1($password2)) {
                $formErrors[] = 'Sorry Password Is Not Match';
            }
        }

        if (isset($email)) {
            $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

            if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] = 'This Email Is Not Valid';
            }
        }

        // Check If There's No Error Proceed The User Add
        if (empty($formErrors)) {

            // Check If User Exist In Database
            $check = checkItem("Username", "users", $username);

            if ($check == 1) {

                $formErrors[] = 'Sorry This User Is Exists';
            } else {

                // Insert User Info In Database
                $stmt = $con->prepare("INSERT INTO 
                                            users(Username, Password, Email, RegStatus, Date)
                                        VALUES
                                            (:zuser, :zpass, :zmail, 0, now())");
                $stmt->execute(array(
                    'zuser' => $username,
                    'zpass' => sha1($password),
                    'zmail' => $email
                ));

                // Echo Success Message
                $successMsg = 'Congrats You Are Now Register User';
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

        if (isset($successMsg)) {
            echo '<div class="msg success">' . $successMsg . '</div>';
        }
        ?>
    </div>
</div>

<?php

include $tpl . 'footer.php';
ob_end_flush();

?>