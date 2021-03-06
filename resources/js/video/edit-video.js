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

var imageModal = document.getElementById("image-modal");
var subtitleModal = document.getElementById("subtitle-modal");

var canvas = document.getElementById("canvas");
var subCanvas = document.getElementById("sub-canvas");
var context = canvas.getContext("2d");
var subContext = subCanvas.getContext("2d");
var ratio, w, h;
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

    ratio = video.videoWidth / video.videoHeight;
    w = video.videoWidth - 100;
    h = parseInt(w / ratio, 10);
    canvas.width = w;
    canvas.height = h;
    subCanvas.width = w;
    subCanvas.height = h;
}, false);

function takeScreenShot() {
    context.fillRect(0, 0, w, h);
    context.drawImage(video, 0, 0, w, h);
    imageModal.style.display = "block";
}

function takeScreenShotForSubtitle() {
    subContext.fillRect(0, 0, w, h);
    subContext.drawImage(video, 0, 0, w, h);
    document.getElementById("label-subtitle-current-time").innerHTML = "Current time: " + video.currentTime.toString().toHHMMSS();
    subtitleModal.style.display = "block";
}

var imageBtn = document.getElementById("caption-image-btn");
var subtitlesBtn = document.getElementById("caption-subtitles-btn");

var videoUserIdForForm;
var videoNameForForm;
var videoIdForForm;
imageBtn.addEventListener("click", function() {
    iconPlayPause.className = "fas fa-play";
    video.pause();
    takeScreenShot();
});

subtitlesBtn.addEventListener("click", function() {
    iconPlayPause.className = "fas fa-play";
    video.pause();
    takeScreenShotForSubtitle();
});

var closeImageModalBtn = document.getElementById("image-modal-close-btn");
closeImageModalBtn.addEventListener("click", function() {
    imageModal.style.display = "none";
});

var closeSubtitleModalBtn = document.getElementById("subtitle-modal-close-btn");
closeSubtitleModalBtn.addEventListener("click", function() {
    subtitleModal.style.display = "none";
});

var saveImageBtn = document.getElementById("save-image-button");
saveImageBtn.addEventListener("click", function() {
    event.preventDefault();
    var canvasDataUrl = canvas.toDataURL("image/png");
    document.getElementById("input-image-imagedata").value = canvasDataUrl;

    document.getElementById("input-image-videouserid").value = videoUserIdForForm;
    document.getElementById("input-image-videoname").value = videoNameForForm;
    document.getElementById("input-image-videoid").value = videoIdForForm;
    document.getElementById("input-image-timestamp").value = parseFloat(video.currentTime.toFixed(2));

    var formData = new FormData(document.getElementById("submitImageForm"));
    
    $.ajax({
        type: "POST",
        url: "../../api/api.php/upload-image",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(response) {
            if(response.errors) {
                var generalError = document.getElementById('generalError');
                generalError.style.display = "block";
                generalError.innerText = "";
                for(key in response.errors) {
                    generalError.innerText += response.errors[key] + "\n";
                }
            } else {
                location.reload(true);
            }
        },
        error: function() {
            alert("CONNECTION ERROR");
        }
    });

    document.getElementById("input-image-annotation").value = "";
});

var saveSubtitleBtn = document.getElementById("save-subtitle-button");
saveSubtitleBtn.addEventListener("click", function() {
    event.preventDefault();

    var duration = document.getElementById("input-subtitle-duration").value;
    var startTime = video.currentTime.toFixed(3).toString();
    var endTime = (video.currentTime + parseInt(duration)).toFixed(3).toString();
    var startTimeFormatted = startTime.toHHMMSS() + startTime.substr(startTime.length - 4);
    var endTimeFormatted = endTime.toHHMMSS() + endTime.substr(endTime.length - 4);
    var annotation = document.getElementById("input-subtitle-annotation").value;
    
    var captionSubtitleError = document.getElementById('captionSubtitleError');
    
    validateStartTime(startTimeFormatted, captionSubtitleError);
    validateEndTime(endTimeFormatted, captionSubtitleError);
    validateAnnotation(annotation, captionSubtitleError);
    validateVideoId(videoIdForForm, captionSubtitleError);
    validateUserId(videoUserIdForForm, captionSubtitleError);
    
    if(!captionSubtitleError.innerHTML) {
        var formData = JSON.stringify({startTime:startTimeFormatted, endTime:endTimeFormatted, annotation:annotation, videoId:videoIdForForm, videoUserId:videoUserIdForForm});
        
        $.ajax({
            type: "POST",
            url: "../../api/api.php/write-subtitle",
            dataType: "json",
            data: formData,
            success: function(response) {
                if(response.errors) {
                    captionSubtitleError.style.display = "block";
                    captionSubtitleError.innerText = "";
                    for(key in response.errors) {
                        captionSubtitleError.innerText += response.errors[key] + "\n";
                    }
                } else {
                    location.reload(true);
                }
            },
            error: function() {
                alert("CONNECTION ERROR");
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function(){
    loadVideo();
    imageModal.style.display = "none";
    subtitleModal.style.display = "none";
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

                    videoIdForForm = response.video.id;
                    videoUserIdForForm = response.video.userId;
                    videoNameForForm = response.video.name;

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
                        imgContainer.addEventListener("click", function(e) {
                            if(e.target && e.target.className == "fas fa-trash-alt") {
                                deleteImage(e.target.getAttribute("timestamp"), e.target.getAttribute("videoid"));
                            }
                        });

                        imgContainer.appendChild(annotation);
                        imgContainer.innerHTML += '<i class="fas fa-trash-alt" timestamp="' + value.timestamp + '" videoid="' + response.video.id + '"></i>';
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
    if(imageModal.style.display == "none" && subtitleModal.style.display == "none") {
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
    if(e.keyCode == 27) {
        imageModal.style.display = "none";
        subtitleModal.style.display = "none";
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
    imageModal.style.display = "none";
    currentTimeDisplay.innerHTML = video.currentTime.toString().toHHMMSS() + " / " + video.duration.toString().toHHMMSS();
};

video.addEventListener("timeupdate", function() {
    currentTimeDisplay.innerHTML = video.currentTime.toString().toHHMMSS() + " / " + video.duration.toString().toHHMMSS();
});

function deleteImage(timestamp, videoId) {
    event.preventDefault();

    var generalError = document.getElementById('generalError');
    
    validateVideoId(videoId, generalError);
    validateTimestamp(timestamp, generalError);
    
    if(!generalError.innerHTML) {
        var formData = JSON.stringify({videoId:videoId, timestamp:timestamp});
        
        $.ajax({
            type: "DELETE",
            url: "../../api/api.php/delete-image",
            dataType: "json",
            data: formData,
            success: function(response) {
                if(response.errors) {
                    var generalError = document.getElementById('generalError');
                    generalError.style.display = "block";
                    generalError.innerText = "";
                    for(key in response.errors) {
                        generalError.innerText += response.errors[key] + "\n";
                    }
                } else {
                    location.reload(true);
                }
            },
            error: function() {
                alert("CONNECTION ERROR");
            }
        });
    }
}

var deleteSubtitleBtn = document.getElementById("delete-subtitle-button");
deleteSubtitleBtn.addEventListener("click", function() {
    deleteSubtitle();
});

function deleteSubtitle() {
    event.preventDefault();

    var subtitleText = document.getElementById("delete-subtitle-text-input").value;
    
    var generalError = document.getElementById('generalError');

    validateSubtitleText(subtitleText, generalError);
    validateVideoId(videoIdForForm, generalError);
    validateUserId(videoUserIdForForm, generalError);
    
    if(!generalError.innerHTML) {
        var formData = JSON.stringify({subtitleText:subtitleText, videoId:videoIdForForm, videoUserId:videoUserIdForForm});
        
        $.ajax({
            type: "POST",
            url: "../../api/api.php/erase-subtitle",
            dataType: "json",
            data: formData,
            success: function(response) {
                if(response.errors) {
                    var generalError = document.getElementById('generalError');
                    generalError.style.display = "block";
                    generalError.innerText = "";
                    for(key in response.errors) {
                        generalError.innerText += response.errors[key] + "\n";
                    }
                } else {
                    location.reload(true);
                }
            },
            error: function() {
                alert("CONNECTION ERROR");
            }
        });
    }
}

function validateStartTime(startTimeFormatted, captionSubtitleError) {
    var pattern = /[0-9]{2}:[0-9]{2}:[0-9]{2}/;
    if(!pattern.test(startTimeFormatted)) {
        if(!captionSubtitleError.innerText.includes("Time format for start time is invalid")) {
            captionSubtitleError.innerText += "Time format for start time is invalid\n";
        }
        captionSubtitleError.style.display = "block";
    } else {
        captionSubtitleError.innerText = captionSubtitleError.innerText.replace("Time format for start time is invalid\n", "");
    }
}

function validateEndTime(endTimeFormatted, captionSubtitleError) {
    var pattern = /[0-9]{2}:[0-9]{2}:[0-9]{2}/;
    if(!pattern.test(endTimeFormatted)) {
        if(!captionSubtitleError.innerText.includes("Time format for end time is invalid")) {
            captionSubtitleError.innerText += "Time format for end time is invalid\n";
        }
        captionSubtitleError.style.display = "block";
    } else {
        captionSubtitleError.innerText = captionSubtitleError.innerText.replace("Time format for end time is invalid\n", "");
    }
}

function validateAnnotation(annotation, captionSubtitleError) {
    if(annotation.length <= 0) {
        if(!captionSubtitleError.innerText.includes("Annotation is empty")) {
            captionSubtitleError.innerText += "Annotation is empty\n";
        }
        captionSubtitleError.style.display = "block";
    } else {
        captionSubtitleError.innerText = captionSubtitleError.innerText.replace("Annotation is empty\n", "");
    }
}

function validateVideoId(videoIdForForm, captionSubtitleError) {
    if(!/^\d+$/.test(videoIdForForm)) {
        if(!captionSubtitleError.innerText.includes("Video id can contain only numbers")) {
            captionSubtitleError.innerText += "Video id can contain only numbers\n";
        }
        captionSubtitleError.style.display = "block";
    } else {
        captionSubtitleError.innerText = captionSubtitleError.innerText.replace("Video id can contain only numbers\n", "");
    }
}

function validateUserId(videoUserIdForForm, captionSubtitleError) {
    if(!/^\d+$/.test(videoIdForForm)) {
        if(!captionSubtitleError.innerText.includes("User id can contain only numbers")) {
            captionSubtitleError.innerText += "User id can contain only numbers\n";
        }
        captionSubtitleError.style.display = "block";
    } else {
        captionSubtitleError.innerText = captionSubtitleError.innerText.replace("User id can contain only numbers\n", "");
    }
}

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

function validateTimestamp(timestamp, generalError) {
    var pattern = /[a-z]/;
    if(pattern.test(timestamp)) {
        if(!generalError.innerText.includes("Timestamp cannot contain letters")) {
            generalError.innerText += "Timestamp cannot contain letters\n";
        }
        generalError.style.display = "block";
    } else {
        generalError.innerText = generalError.innerText.replace("Timestamp cannot contain letters\n", "");
    }
}

function validateSubtitleText(subtitleText, generalError) {
    if(subtitleText.length <= 0) {
        if(!generalError.innerText.includes("Subtitle text is empty")) {
            generalError.innerText += "Subtitle text is empty\n";
        }
        generalError.style.display = "block";
    } else {
        generalError.innerText = generalError.innerText.replace("Subtitle text is empty\n", "");
    }
}