var form = document.getElementById("registerForm");

form.addEventListener("submit", submitRegisterForm);

function submitRegisterForm() {
    event.preventDefault();

    var email = document.getElementById('email');
    var name = document.getElementById('name');
    var surname = document.getElementById('surname');
    var password = document.getElementById('password');
    var passwordRepeat = document.getElementById('passwordRepeat');

    var emailError = document.getElementById('emailError');
    var nameError = document.getElementById('nameError');
    var surnameError = document.getElementById('surnameError');
    var passwordError = document.getElementById('passwordError');
    var passwordRepeatError = document.getElementById('passwordRepeatError');

    validateEmail(email, emailError);
    validateName(name, nameError);
    validateSurname(surname, surnameError);
    validatePassword(password, passwordError);
    validatePasswordRepeat(password, passwordRepeat, passwordRepeatError);

    if(!emailError.innerHTML && !nameError.innerHTML && !surnameError.innerHTML && !passwordError.innerHTML && !passwordRepeatError.innerHTML) {
        var formData = JSON.stringify({email:email.value, name:name.value, surname:surname.value, password:password.value, passwordRepeat:passwordRepeat.value});

        $.ajax({
            type: "POST",
            url: "../../api/api.php/register",
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
                    location.replace("../user/login.php?register=true");
                }
            },
            error: function() {
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

function validateName(name, nameError) {
    if(name.value.length <= 0) {
        displayErrorField(name, nameError, "Name is too short");
    } else {
        nameError.innerHTML = "";
    }
}

function validateSurname(surname, surnameError) {
    if(surname.value.length <= 0) {
        displayErrorField(surname, surnameError, "Surname is too short");
    } else {
        surnameError.innerHTML = "";
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
