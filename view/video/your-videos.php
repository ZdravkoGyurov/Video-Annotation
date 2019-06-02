<?php
    if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
        if($_COOKIE['loggedUserRole'] == 'Admin') {
            header('Location: '.'..\\video\\all-videos.php');
        }
    } else {
        header('Location: '.'..\\user\\login.php');
    }
?>
<?php $pageTitle = "VA | All videos"; ?>
<?php include '../common/header.html'; ?>
<link rel="stylesheet" href="../../resources/css/your-videos.css">
<h1>Your videos</h1>
<ul id="list-all-videos">
</ul>
<script type="text/javascript" src="../../resources/js/video/your-videos.js"></script>
<?php include '../common/footer.html' ?>