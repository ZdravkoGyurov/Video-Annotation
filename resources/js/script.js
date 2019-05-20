function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) {
        return parts.pop().split(";").shift();
    } else {
        return "";
    }
  }
  
document.addEventListener("DOMContentLoaded", function(){
    showLoginInfo();
    showCorrectHeader();
});

function showLoginInfo() {
    var decodedCookieName = decodeURIComponent(getCookie('loggedUserName')).replace("+", " ");
    var decodedCookieRole = decodeURIComponent(getCookie('loggedUserRole'));

    if(decodedCookieRole == "Admin") {
        document.getElementById("logout-message3").innerHTML = decodedCookieName + "(" + decodedCookieRole + ")";
    } else if(decodedCookieRole == "User") {
        document.getElementById("logout-message2").innerHTML = decodedCookieName + "(" + decodedCookieRole + ")";
    }

}

function showCorrectHeader() {
    var decodedCookieRole = decodeURIComponent(getCookie('loggedUserRole'));
    var header1 = document.getElementById("header-1");
    var header2 = document.getElementById("header-2");
    var header3 = document.getElementById("header-3");

    if(decodedCookieRole == "Admin") {
        header1.style.display = "none";
        header2.style.display = "none";
        header3.style.display = "block";
    } else if(decodedCookieRole == "User") {
        header1.style.display = "none";
        header2.style.display = "block";
        header3.style.display = "none";
    } else {
        header1.style.display = "block";
        header2.style.display = "none";
        header3.style.display = "none";
    }
}

var logoutBtn = decodeURIComponent(getCookie('loggedUserRole')) == "Admin" ? document.getElementById("logout-btn3") : document.getElementById("logout-btn2");
if(logoutBtn) {
    logoutBtn.addEventListener("click", submitLogoutForm);
}

function submitLogoutForm() {
    event.preventDefault();

    $.ajax({
        type: "GET",
        url: "../../api/api.php/logout",
        success: function(response) {
            console.log(response);
            location.replace("../user/login.php");
        },
        error: function(response) {
            console.log(response);
        }
    });
};