function submitLoginForm() {
    event.preventDefault();

    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var formData = JSON.stringify({email:email, password:password});

    $.ajax({
        type: "POST",
        url: "../../api/api.php/login",
        dataType: "json",
        data: formData,
        success: function(response) {
            if(response.errors == null) {
                setSuccessMessages(response, "Logged in", "Logged in as");
                clearInputFields();
                showMessages();
                getLoginInfo();
            } else {
                setErrorMessages(response);
                showMessages();
            }
        },
        error: function() {
            setErrorMessageNoResponse("No response from server");
            showMessages();
        }
    });
};

function submitLogoutForm() {
    event.preventDefault();

    $.ajax({
        type: "GET",
        url: "../../api/api.php/logout",
        success: function(response) {
            if(response.errors == null) {
                setSuccessMessages(response, "Logged out", "Logged out user was");
                showMessages();
                getLoginInfo();
            } else {
                setErrorMessages(response);
                showMessages();
            }
        },
        error: function() {
            setErrorMessageNoResponse("No response from server");
            showMessages();
        }
    });
};

function submitFindAllForm() {
    event.preventDefault();

    $.ajax({
        type: "GET",
        url: "../../api/api.php/users",
        success: function(response) {
            if(response.errors == null) {
                setSuccessMessagesForResponseArray(response, "All users successfully listed");
                showMessages();
            } else {
                setErrorMessages(response);
                showMessages();
            }
        },
        error: function() {
            setErrorMessageNoResponse("No response from server");
            showMessages();
        }
    });
};

function submitCreateForm() {
    event.preventDefault();

    var email = document.getElementById('email').value;
    var name = document.getElementById('name').value;
    var surname = document.getElementById('surname').value;
    var password = document.getElementById('password').value;
    var formData = JSON.stringify({email:email, name:name, surname:surname, password:password});

    $.ajax({
        type: "POST",
        url: "../../api/api.php/register",
        dataType: "json",
        data: formData,
        success: function(response) {
            if(response.errors == null) {
                setSuccessMessages(response, "User successfully registered", "New user registered");
                clearInputFields();
                showMessages();
            } else {
                setErrorMessages(response);
                showMessages();
            }
        },
        error: function() {
            setErrorMessageNoResponse("No response from server");
            showMessages();
        }
    });
};

function submitRemoveForm() {
    event.preventDefault();

    var email = document.getElementById('email').value;
    var url = "../../api/api.php/user/" + email;

    $.ajax({
        type: "DELETE",
        url: url,
        success: function(response) {
            if(response.errors == null) {
                setSuccessMessages(response, "User successfully removed", "Removed user");
                clearInputFields();
                showMessages();
            } else {
                setErrorMessages(response);
                showMessages();
            }
        },
        error: function() {
            setErrorMessageNoResponse("No response from server");
            showMessages();
        }
    });
};

function showMessages() {
    $('#errorsInfo').fadeIn('slow', function(){
        $('#errorsInfo').delay(5000).fadeOut(); 
    });

    $('#successInfo').fadeIn('slow', function(){
        $('#successInfo').delay(2500).fadeOut(); 
    });
}

function setErrorMessageNoResponse(message) {
    var errorsInfo = document.getElementById('errorsInfo');
    var successInfo = document.getElementById('successInfo');
    var responseInfo = document.getElementById('responseInfo');
    errorsInfo.innerHTML = successInfo.innerHTML = responseInfo.innerHTML = "";

    errorsInfo.innerHTML += message;
}

function setErrorMessages(response) {
    var errorsInfo = document.getElementById('errorsInfo');
    var successInfo = document.getElementById('successInfo');
    var responseInfo = document.getElementById('responseInfo');
    errorsInfo.innerHTML = successInfo.innerHTML = responseInfo.innerHTML = "";

    for(key in response.errors) {
        var value = response.errors[key];
        errorsInfo.innerHTML += value + "<br>";
    }
}

function setSuccessMessages(response, successMessage, responseMessage) {
    var errorsInfo = document.getElementById('errorsInfo');
    var successInfo = document.getElementById('successInfo');
    var responseInfo = document.getElementById('responseInfo');
    errorsInfo.innerHTML = successInfo.innerHTML = responseInfo.innerHTML = "";

    successInfo.innerHTML = successMessage;

    responseInfo.innerHTML += 
    responseMessage + ": " + response.name + " " + response.surname + "(" + response.type + "), " + response.email;
}

function setSuccessMessagesForResponseArray(response, successMessage) {
    var errorsInfo = document.getElementById('errorsInfo');
    var successInfo = document.getElementById('successInfo');
    var responseInfo = document.getElementById('responseInfo');
    errorsInfo.innerHTML = successInfo.innerHTML = responseInfo.innerHTML = "";

    successInfo.innerHTML = successMessage;

    for(key in response.data) {
        var value = response.data[key];
                    
        responseInfo.innerHTML += value.name + " " + value.surname + "(" + value.type + "), " + value.email + "<br>";
    }
}

function clearInputFields() {
    var email = document.getElementById('email');
    if(email != null) {
        email.value = "";
    }

    var name = document.getElementById('name');
    if(name != null) {
        name.value = "";
    }

    var surname = document.getElementById('surname');
    if(surname != null) {
        surname.value = "";
    }

    var password = document.getElementById('password');
    if(password != null) {
        password.value = "";
    }
}

function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) {
        return parts.pop().split(";").shift();
    } else {
        return "";
    }
  }

function getLoginInfo() {
    var decodedCookie = decodeURIComponent(getCookie('loginInfoToDisplay'));
    document.getElementById("loginInfo").innerHTML = decodedCookie.replace("+", " ");
}
