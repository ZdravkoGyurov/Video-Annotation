var video = document.getElementById("video");
var videoContainer = document.getElementById('c-video');
var videoTimeBar = document.getElementById("video-time-bar");
var videoTimeBarFiller = document.getElementById("video-time-bar-filler");

var buttonPlayPause = document.getElementById('video-button-play-pause');
var iconPlayPause = document.getElementById('video-button-play-pause-icon');

var buttonMuteUnmute = document.getElementById('video-button-volume');
var iconMuteUnmute = document.getElementById('video-button-volume-icon');

var currentTimeDisplay = document.getElementById('video-current-time-duration-time');

var buttonExpandCompress = document.getElementById('video-button-expand-compress');
var iconExpandCompress = document.getElementById('video-button-expand-compress-icon');

document.addEventListener("DOMContentLoaded", function(){
    loadVideo();
});

function loadVideo() {
    var urlString = window.location.href;
    var url = new URL(urlString);
    var videoName = url.searchParams.get("videoName");
    var urlForAJax = "../../api/api.php/find-video/" + videoName;

    // validate video name

    if(true) { // no errors
        $.ajax({
            type: "GET",
            url: urlForAJax,
            success: function(response) {
                console.log(response);
                if(response.errors) {
                    console.log(response.errors);
                } else {
                    var pageHeader = document.getElementById("page-header");
                    pageHeader.innerHTML = response.name;
                    
                    var video = document.getElementById("video");
                    var parts = response.path.split("\\");
                    video.src = "..\\..\\uploaded-videos\\" + parts[parts.length - 1];
                    console.log(response.path);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
}

function togglePlayPause() {
    if(video != null) {
        if(video.paused) {
            if(video.currentTime == video.duration) {
                video.currentTime = 0;
            }
            iconPlayPause.className = "fas fa-pause";
            video.play();
            video.style.filter = "none";
        } else {
            iconPlayPause.className = "fas fa-play";
            video.pause();
        }
    }
}

function toggleMuteUnmute() {
    if(video != null) {
        if(video.muted) {
            iconMuteUnmute.className = "fas fa-volume-up";
            video.muted = false;
        } else {
            iconMuteUnmute.className = "fas fa-volume-mute";
            video.muted = true;
        }
    }
}

function toggleExpandCompress() {
    if(video != null) {
        if(videoContainer.style.maxWidth == "none") {
            iconExpandCompress.className = "fas fa-expand-arrows-alt";
            videoContainer.style.maxWidth = "800px";
        } else {
            iconExpandCompress.className = "fas fa-compress-arrows-alt";
            videoContainer.style.maxWidth = "none";
            window.scrollTo(0,document.body.scrollHeight);
        }
    }
}

video.addEventListener("timeupdate", function() {
    var timeFillerPosition = video.currentTime / video.duration;
    videoTimeBarFiller.style.width = timeFillerPosition * 100 + "%";

    if(video.ended) {
        iconPlayPause.className = "fas fa-play";
        video.style.filter = "brightness(40%)";
    }
});

videoTimeBar.addEventListener('click', function (e) {
    var rect = e.target.getBoundingClientRect();
    var x = e.clientX - rect.left;
    var updatedProgress = x * 100 / this.clientWidth;

    videoTimeBarFiller.style.width = updatedProgress + "%";
    var updatedTime = updatedProgress * video.duration / 100;
    video.currentTime = updatedTime;
});

buttonPlayPause.addEventListener("click", togglePlayPause);
video.addEventListener("click", togglePlayPause);

document.body.onkeydown = function(e) {
    if(e.keyCode == 32) {
        togglePlayPause();
    } else if(e.keyCode == 77) {
        toggleMuteUnmute();
    } else if(e.keyCode == 70) {
        toggleExpandCompress();
    } else if(e.keyCode == 37) {
        video.currentTime -= 5;
    } else if(e.keyCode == 39) {
        video.currentTime += 5;
    }
}

buttonMuteUnmute.addEventListener("click", toggleMuteUnmute);

buttonExpandCompress.addEventListener("click", toggleExpandCompress);

String.prototype.toHHMMSS = function () {
    var sec_num = parseInt(this, 10);
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    return hours+':'+minutes+':'+seconds;
}

video.onloadeddata = function() {
    currentTimeDisplay.innerHTML = video.currentTime.toString().toHHMMSS() + " / " + video.duration.toString().toHHMMSS();
};

video.addEventListener("timeupdate", function() {
    currentTimeDisplay.innerHTML = video.currentTime.toString().toHHMMSS() + " / " + video.duration.toString().toHHMMSS();
});