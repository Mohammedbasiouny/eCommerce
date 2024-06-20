<?php

session_start();
$pageTitle = 'Login';

// If Session Exist Redirect To Dashboard Page
if (isset($_SESSION['user'])) {
    header('Location: index.php');
}

include 'init.php';

// Check If User Coming From HTTP Post Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
}
?>

<div class="container login-page">
    <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span
            data-class="signup">Signup</span></h1>
    <!-- Start Login Form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username"
                required>
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password"
                placeholder="Type your password" required>
        </div>
        <div class="input-container">
            <input class="btn btn-primary" type="submit" value="Login">
        </div>
    </form>
    <!-- End Login Form -->
    <!-- Start Signup Form -->
    <form class="signup">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username"
                required>
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password"
                placeholder="Type a complex password" required>
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password2" autocomplete="new-password"
                placeholder="Type a password again" required>
        </div>
        <div class="input-container">
            <input class="form-control" type="email" name="email" autocomplete="off" placeholder="Type a valid email"
                required>
        </div>
        <div class="input-container">
            <input class="btn btn-success" type="submit" value="Signup">
        </div>
    </form>
    <!-- End Signup Form -->
</div>

<?php include $tpl . 'footer.php'; ?>