<?php
    if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
        if($_COOKIE['loggedUserRole'] == 'Admin') {
            header('Location: '.'..\\video\\all-videos.php');
        }
    } else {
        header('Location: '.'..\\user\\login.php');
    }
?>
<?php $pageTitle = "VA | Upload video"; ?>
<?php include '../common/header.html'; ?>
<link rel="stylesheet" href="../../resources/css/upload-video.css">
<h1>Upload video</h1>
<form enctype="multipart/form-data" id="uploadFileForm" >
    <input id="hidden-input" name="uploaded-file" accept="video/*" type="file" hidden="hidden"></input>
    <div id="whole-button">
        <i class="fas fa-upload"></i>
        <button id="btn" type="button">Choose a File</button>
    </div>
    <p id="message">No file chosen</p>
    <button id="btn-upload" type="submit">Upload</button>
</form>
<script type="text/javascript" src="../../resources/js/video/upload-video.js"></script>
<?php include '../common/footer.html' ?>