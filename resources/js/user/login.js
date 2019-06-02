var form = document.getElementById("loginForm");

form.addEventListener("submit", submitLoginForm);

function submitLoginForm() {
    event.preventDefault();

    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var formData = JSON.stringify({email:email, password:password});

    // validate fields

    if(true) { // no errors
        $.ajax({
            type: "POST",
            url: "../../api/api.php/login",
            dataType: "json",
            data: formData,
            success: function(response) {
                if(response.errors) {
                    console.log(response.errors);
                } else {
                    location.replace("../video/all-videos.php");
                }
            },
            error: function(response) {
                location.reload(true);
                console.log(response);
            }
        });
    }
};

document.addEventListener("DOMContentLoaded", function(){
    var urlString = window.location.href;
    var url = new URL(urlString);
    var register = url.searchParams.get("register");
    var restore = url.searchParams.get("restore");

    if(register) {
        var successMessage = document.createElement('p');
        successMessage.id = "success-message";
        successMessage.innerHTML = "Registration successful!";
        document.getElementsByTagName("h1")[0].prepend(successMessage);
    } else if(restore) {
        var successMessage = document.createElement('p');
        successMessage.id = "success-message";
        successMessage.innerHTML = "Password restoration successful!";
        document.getElementsByTagName("h1")[0].prepend(successMessage);
    }
});