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
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        }
    });
});