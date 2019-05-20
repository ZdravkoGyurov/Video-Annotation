<?php $pageTitle = "VA | Login" ?>
<?php include '../common/header.html' ?>
<link rel="stylesheet" href="../../resources/css/login.css">
<form id="loginForm">
    <h1>Login</h1>

    <label for="email">Email</label>
    <input id="email" type="email" placeholder="Email">

    <label for="password">Password</label>
    <input id="password" type="password" placeholder="Password">

    <p>Don't have an account?<a class="small-links" href="register.php">Click here.</a></p>
    <p>Forgot password?<a class="small-links" href="restore-password.php">Click here.</a></p>

    <input type="submit" value="Login">
</form>
<script type="text/javascript" src="../../resources/js/user/login.js"></script>
<?php include '../common/footer.html' ?>