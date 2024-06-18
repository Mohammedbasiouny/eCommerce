<?php include 'init.php'; ?>

<div class="container login-page">
    <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
    <form class="login">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username" required>
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type your password">
        </div>
        <div class="input-container">
            <input class="btn btn-primary" type="submit" value="Login">
        </div>
    </form>
    <form class="signup">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username">
        <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type a complex password">
        <input class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type a password again">
        <input class="form-control" type="email" name="email" autocomplete="off" placeholder="Type a valid email">
        <input class="btn btn-success" type="submit" value="Signup">
    </form>
</div>

<?php include $tpl . 'footer.php'; ?>