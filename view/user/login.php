<?php
    if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
        if($_COOKIE['loggedUserRole'] == 'User') {
            header('Location: '.'..\\video\\all-videos.php');
        } else {
            header('Location: '.'..\\video\\all-videos.php');
        }
    }
?>
<?php $pageTitle = "VA | Login"; ?>
<?php include '../common/header.html'; ?>
<link rel="stylesheet" href="../../resources/css/login.css">
<form id="loginForm">
    <h1>Login</h1>
    <p id="generalError" class="error"></p>

    <label for="email">Email</label>
    <p id="emailError" class="error"></p>
    <input id="email" type="email" placeholder="Email">

    <label for="password">Password</label>
    <p id="passwordError" class="error"></p>
    <input id="password" type="password" placeholder="Password">

    <p>Don't have an account?<a class="small-links" href="register.php">Click here.</a></p>
    <p>Forgot password?<a class="small-links" href="restore-password.php">Click here.</a></p>

    <input type="submit" value="Login">
</form>
<script type="text/javascript" src="../../resources/js/user/login.js"></script>
<?php include '../common/footer.html' ?>