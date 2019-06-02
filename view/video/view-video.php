<?php
    if(!isset($_COOKIE['loggedUserEmail']) || empty(isset($_COOKIE['loggedUserEmail']))) {
        header('Location: '.'..\\user\\login.php');
    }
?>
<?php $pageTitle = "VA | View video"; ?>
<?php include '../common/header.html'; ?>
<link rel="stylesheet" href="../../resources/css/view-video.css">
<h1 id="page-header"></h1>
<div id="video-container">
    <div id="c-video">
        <video id="video" src=""></video>
        <div id="video-controls">
            <div id="video-time-bar">
                <div id="video-time-bar-filler"></div>
            </div>
            <div id="video-buttons">
                <button id="video-button-play-pause"><i id="video-button-play-pause-icon" class="fas fa-play"></i></button>
                <span id="video-current-time-duration-time"></span>
                <button id="video-button-volume"><i id="video-button-volume-icon" class="fas fa-volume-up"></i></button>
                <button id="video-button-expand-compress"><i id="video-button-expand-compress-icon" class="fas fa-expand-arrows-alt"></i></button>
            </div>
        </div>
    </div>
</div>
<div id="images-container">
</div>
<script type="text/javascript" src="../../resources/js/video/view-video.js"></script>
<?php include '../common/footer.html' ?>