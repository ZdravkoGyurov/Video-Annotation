<?php $pageTitle = "VA | Edit video" ?>
<?php include '../common/header.html' ?>
<link rel="stylesheet" href="../../resources/css/edit-video.css">
<h1 id="page-header"></h1>
<div id="image-modal">
    <div id="image-modal-content">
        <i id="image-modal-close-btn" class="fas fa-times close-modal"></i>
        <canvas id="canvas"></canvas>
        <form id="submitImageForm" method="POST">
            <label for="input-image-annotation">Caption</label>
            <input id="input-image-annotation" name="annotation" type="text" placeholder="Write a caption here."></input>
            <input id="input-image-timestamp" name="timestamp" type="hidden"></input>
            <input id="input-image-videoid" name="videoid" type="hidden"></input>
            <input id="input-image-videoname" name="videoname" type="hidden"></input>
            <input id="input-image-videouserid" name="videouserid" type="hidden"></input>
            <input id="input-image-imagedata" name="imagedata" type="hidden"></input>
        </form>
        <button id="save-image-button">Save Image</button>
    </div>
</div>
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
<div id="caption-container">
    <h2>Caption this moment via:</h2>
    <div id="btn-container">
        <button id="caption-image-btn" class="caption-btn"><i class="fas fa-images caption-icon"></i>Image</button>
        <button id="caption-subtitles-btn" class="caption-btn"><i class="fas fa-align-center caption-icon"></i>Subtitles</button>
    </div>
</div>
<script type="text/javascript" src="../../resources/js/video/edit-video.js"></script>
<?php include '../common/footer.html' ?>