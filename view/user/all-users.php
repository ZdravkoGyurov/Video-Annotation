<?php
    if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
        if($_COOKIE['loggedUserRole'] == 'User') {
            header('Location: '.'..\\video\\all-videos.php');
        }
    } else {
        header('Location: '.'..\\user\\login.php');
    }
?>
<?php $pageTitle = "VA | All users"; ?>
<?php include '../common/header.html'; ?>
<link rel="stylesheet" href="../../resources/css/all-users.css">
<h1>All users</h1>
<ul id="list-all-users">
</ul>
<script type="text/javascript" src="../../resources/js/user/all-users.js"></script>
<?php include '../common/footer.html' ?>