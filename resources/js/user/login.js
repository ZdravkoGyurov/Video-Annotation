var form = document.getElementById("loginForm");

form.addEventListener("submit", submitLoginForm);

function submitLoginForm() {
    event.preventDefault();

    var email = document.getElementById('email');
    var password = document.getElementById('password');
    
    var emailError = document.getElementById('emailError');
    var passwordError = document.getElementById('passwordError');

    validateEmail(email, emailError);
    validatePassword(password, passwordError);
    
    if(!emailError.innerHTML && !passwordError.innerHTML) {
        var formData = JSON.stringify({email:email.value, password:password.value});

        $.ajax({
            type: "POST",
            url: "../../api/api.php/login",
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
                    location.replace("../video/all-videos.php");
                }
            },
            error: function() {
                alert("CONNECTION ERROR");
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
        successMessage.innerHTML = "Registration successful";
        document.getElementsByTagName("h1")[0].prepend(successMessage);
    } else if(restore) {
        var successMessage = document.createElement('p');
        successMessage.id = "success-message";
        successMessage.innerHTML = "Password restoration successful";
        document.getElementsByTagName("h1")[0].prepend(successMessage);
    }
});

function displayErrorField(field, fieldError, errorMessage) {
    fieldError.innerHTML = errorMessage;
    fieldError.style.display = "block";
    field.value = "";
}

function validateEmail(email, emailError) {
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!pattern.test(email.value)) {
        displayErrorField(email, emailError, "Email is invalid");
    } else {
        emailError.innerHTML = "";
    }
}

function validatePassword(password, passwordError) {
    if(password.value.length <= 0) {
        displayErrorField(password, passwordError, "Password is too short");
    } else {
        passwordError.innerHTML = "";
    }
}
