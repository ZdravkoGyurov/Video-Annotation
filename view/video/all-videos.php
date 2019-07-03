<?php
    if(!isset($_COOKIE['loggedUserEmail']) || empty(isset($_COOKIE['loggedUserEmail']))) {
        header('Location: '.'..\\user\\login.php');
    }
?>
<?php $pageTitle = "VA | All videos"; ?>
<?php include '../common/header.html'; ?>
<link rel="stylesheet" href="../../resources/css/all-videos.css">
<h1>All videos</h1>
<p id="generalError" class="error"></p>
<ul id="list-all-videos">
</ul>
<script type="text/javascript" src="../../resources/js/video/all-videos.js"></script>
<?php include '../common/footer.html' ?>