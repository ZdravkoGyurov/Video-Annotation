document.addEventListener("DOMContentLoaded", function(){
    showYourVideos();
});

function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) {
        return parts.pop().split(";").shift();
    } else {
        return "";
    }
}

function showYourVideos() {
    event.preventDefault();

    var decodedCookieEmail = decodeURIComponent(getCookie('loggedUserEmail'));
    var url = "../../api/api.php/find-user-videos/" + decodedCookieEmail;

    $.ajax({
        type: "GET",
        url: url,
        success: function(response) {
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        }
    });
};