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

var subtitleSrc;
video.addEventListener("loadedmetadata", function() {
    track = document.createElement("track");
    track.kind = "subtitles";
    track.label = "English";
    track.srclang = "en";
    track.src = subtitleSrc;
    track.default = true;
    track.addEventListener("load", function() {
       this.mode = "showing";
       video.textTracks[0].mode = "showing";
    });
    this.appendChild(track);
});

document.addEventListener("DOMContentLoaded", function(){
    loadVideo();
});

function loadVideo() {
    var urlString = window.location.href;
    var url = new URL(urlString);
    var videoName = url.searchParams.get("videoName");
    var urlForAJax = "../../api/api.php/find-video/" + videoName;

    var generalError = document.getElementById('generalError');

    validateVideoName(videoName, generalError);

    if(!generalError.innerHTML) {
        $.ajax({
            type: "GET",
            url: urlForAJax,
            success: function(response) {
                if(response.errors) {
                    generalError.style.display = "block";
                    generalError.innerText = "";
                    for(key in response.errors) {
                        generalError.innerText += response.errors[key] + "\n";
                    }
                } else {
                    var subtitlesParts = response.subtitle.path.split("\\");
                    subtitleSrc = "..\\..\\uploaded-videos\\" + response.video.name + "\\" + subtitlesParts[subtitlesParts.length - 1] + "?rnd" + (new Date().valueOf());

                    var pageHeader = document.getElementById("page-header");
                    pageHeader.innerHTML = response.video.name;
                    
                    var video = document.getElementById("video");
                    var parts = response.video.path.split("\\");
                    video.src = "..\\..\\uploaded-videos\\" + parts[parts.length - 1];

                    var imagesContainer = document.getElementById("images-container");
                    for(key in response.images.data) {
                        var value = response.images.data[key];

                        var imgContainer = document.createElement("div");
                        imgContainer.className = "img-container set-time";
                        imgContainer.setAttribute("timestamp", value.timestamp);

                        var annotation = document.createElement("span");
                        annotation.innerHTML = value.annotation;
                        annotation.className = "img-annotation set-time";
                        annotation.setAttribute("timestamp", value.timestamp);

                        var timestamp = document.createElement("span");
                        timestamp.innerHTML = value.timestamp.toString().toHHMMSS();
                        timestamp.className = "img-timestamp set-time";
                        timestamp.setAttribute("timestamp", value.timestamp);

                        var img = document.createElement("img");

                        var imgSrcParts = value.path.split("\\");
                        img.src = "..\\..\\uploaded-videos\\" + imgSrcParts[imgSrcParts.length - 2] + "\\" + imgSrcParts[imgSrcParts.length - 1];
                        img.className = "set-time";
                        img.setAttribute("timestamp", value.timestamp);

                        imgContainer.addEventListener("click", function(e) {
                            if(e.target && e.target.classList.contains("set-time")) {
                                video.currentTime = e.target.getAttribute("timestamp");
                                window.scrollTo(0, 0);
                            }
                        });
                        annotation.addEventListener("click", function(e) {
                            if(e.target && e.target.classList.contains("set-time")) {
                                video.currentTime = e.target.getAttribute("timestamp");
                                window.scrollTo(0, 0);
                            }
                        });
                        timestamp.addEventListener("click", function(e) {
                            if(e.target && e.target.classList.contains("set-time")) {
                                video.currentTime = e.target.getAttribute("timestamp");
                                window.scrollTo(0, 0);
                            }
                        });
                        img.addEventListener("click", function(e) {
                            if(e.target && e.target.className == "set-time") {
                                video.currentTime = e.target.getAttribute("timestamp");
                                window.scrollTo(0, 0);
                            }
                        });

                        imgContainer.appendChild(annotation);
                        imgContainer.appendChild(timestamp);
                        imgContainer.appendChild(img);
                        imagesContainer.appendChild(imgContainer);
                    }
                }
            },
            error: function() {
                alert("CONNECTION ERROR");
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
            window.scrollTo(0, 0);
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
        e.preventDefault();
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

function validateVideoName(videoName, generalError) {
    var pattern = /^[a-z0-9]+$/;
    if(pattern.test(videoName)) {
        if(!generalError.innerText.includes("Video name can contain only letters and numbers")) {
            generalError.innerText += "Video name can contain only letters and numbers\n";
        }
        generalError.style.display = "block";
    } else {
        generalError.innerText = generalError.innerText.replace("Video name can contain only letters and numbers\n", "");
    }
}