<?php
    if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
        if($_COOKIE['loggedUserRole'] == 'User') {
            header('Location: '.'..\\video\\all-videos.php');
        } else {
            header('Location: '.'..\\video\\all-videos.php');
        }
    }
?>
<?php $pageTitle = "VA | Register"; ?>
<?php include '../common/header.html'; ?>
<link rel="stylesheet" href="../../resources/css/register.css">
<form id="registerForm">
    <h1>Register</h1>

    <label for="email">Email</label>
    <input id="email" type="email" placeholder="Email">

    <label for="name">Name</label>
    <input id="name" type="text" placeholder="Name">

    <label for="surname">Surname</label>
    <input id="surname" type="text" placeholder="Surname">

    <label for="password">Password</label>
    <input id="password" type="password" placeholder="Password">

    <label for="passwordRepeat">Repeat password</label>
    <input id="passwordRepeat" type="password" placeholder="Repeat password">

    <p>Forgot password?<a class="small-links" href="restore-password.php">Click here.</a></p>

    <input type="submit" value="Register">
</form>
<script type="text/javascript" src="../../resources/js/user/register.js"></script>
<?php include '../common/footer.html' ?>