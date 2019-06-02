var form = document.getElementById("restorePasswordForm");

form.addEventListener("submit", submitRestorePasswordForm);

function submitRestorePasswordForm() {
    event.preventDefault();

    var email = document.getElementById('email');
    var password = document.getElementById('password');
    var passwordRepeat = document.getElementById('passwordRepeat');
    
    var emailError = document.getElementById('emailError');
    var passwordError = document.getElementById('passwordError');
    var passwordRepeatError = document.getElementById('passwordRepeatError');
    
    validateEmail(email, emailError);
    validatePassword(password, passwordError);
    validatePasswordRepeat(password, passwordRepeat, passwordRepeatError);
    
    if(!emailError.innerHTML && !passwordError.innerHTML && !passwordRepeatError.innerHTML) {
        var formData = JSON.stringify({email:email.value, password:password.value, passwordRepeat:passwordRepeat.value});
        
        $.ajax({
            type: "POST",
            url: "../../api/api.php/update-password",
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
                    location.replace("../user/login.php?restore=true");
                }
            },
            error: function(response) {
                alert("CONNECTION ERROR");
            }
        });
    }
};

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

function validatePasswordRepeat(password, passwordRepeat, passwordRepeatError) {
    if(password.value != passwordRepeat.value) {
        displayErrorField(passwordRepeat, passwordRepeatError, "Passwords don't match");
    } else {
        passwordRepeatError.innerHTML = "";
    }
}