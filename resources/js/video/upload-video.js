var hiddenInput = document.getElementById("hidden-input");
var btn = document.getElementById("whole-button");
var message = document.getElementById("message");

btn.addEventListener("click", function() {
    hiddenInput.click();
});

hiddenInput.addEventListener("change", function() {
    if(hiddenInput.value) {
        message.innerHTML = "Chosen file: " + hiddenInput.files[0].name;
    } else {
        message.innerHTML = "No file chosen";
    }
});

var uploadFileForm = document.getElementById("uploadFileForm");

uploadFileForm.addEventListener("submit", function(e) {
    e.preventDefault();

    var formData = new FormData(uploadFileForm);

    $.ajax({
        type: "POST",
        url: "../../api/api.php/upload-video",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(response) {
            if(!response.errors) {
                if(!window.location.href.includes("?uploaded=true")) {
                    window.location.href += "?uploaded=true";
                } else {
                    var urlWithoutParameters = window.location.href.substring(0, window.location.href.indexOf('?'));
                    window.location.href = urlWithoutParameters + "?uploaded=true";
                }
            } else {
                if(document.getElementById("success-message")) {
                    document.getElementById("success-message").remove();
                }
                if(!document.getElementById("fail-message")) {
                    var failMessage = document.createElement('p');
                    failMessage.id = "fail-message";
                    failMessage.innerHTML = "Failed to upload video!";
                    document.getElementsByTagName("h1")[0].prepend(failMessage);
                }
            }
        },
        error: function(response) {
            console.log(response);
        }
    });
});

document.addEventListener("DOMContentLoaded", function(){
    var urlString = window.location.href;
    var url = new URL(urlString);
    var uploaded = url.searchParams.get("uploaded");

    if(uploaded) {
        var successMessage = document.createElement('p');
        successMessage.id = "success-message";
        successMessage.innerHTML = "Video successfully uploaded!";
        document.getElementsByTagName("h1")[0].prepend(successMessage);
    }
});