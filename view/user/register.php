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
    <p id="generalError" class="error"></p>

    <label for="email">Email</label>
    <p id="emailError" class="error"></p>
    <input id="email" type="email" placeholder="Email">

    <label for="name">Name</label>
    <p id="nameError" class="error"></p>
    <input id="name" type="text" placeholder="Name">

    <label for="surname">Surname</label>
    <p id="surnameError" class="error"></p>
    <input id="surname" type="text" placeholder="Surname">

    <label for="password">Password</label>
    <p id="passwordError" class="error"></p>
    <input id="password" type="password" placeholder="Password">

    <label for="passwordRepeat">Repeat password</label>
    <p id="passwordRepeatError" class="error"></p>
    <input id="passwordRepeat" type="password" placeholder="Repeat password">

    <p>Forgot password?<a class="small-links" href="restore-password.php">Click here.</a></p>

    <input type="submit" value="Register">
</form>
<script type="text/javascript" src="../../resources/js/user/register.js"></script>
<?php include '../common/footer.html' ?>