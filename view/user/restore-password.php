<?php $pageTitle = "VA | Restore password" ?>
<?php include '../common/header.html' ?>
<link rel="stylesheet" href="../../resources/css/restore-password.css">
<form id="restorePasswordForm">
    <h1>Restore password</h1>
    
    <label for="email">Email</label>
    <input id="email" type="email" placeholder="Email">

    <label for="password">Password</label>
    <input id="password" type="password" placeholder="Password">

    <label for="passwordRepeat">Repeat password</label>
    <input id="passwordRepeat" type="password" placeholder="Repeat password">

    <input type="submit" value="Restore">
</form>
<?php include '../common/footer.html' ?>