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
            var list = document.getElementById('list-all-videos');
            for(key in response.data) {
                var value = response.data[key];

                var item = document.createElement('li');
                item.appendChild(document.createTextNode(value.name));
                list.appendChild(item);

                var btn = document.createElement('button');
                btn.id = "btn-" + value.id;
                btn.innerHTML = "Delete";
                btn.setAttribute("name", value.name)
                btn.addEventListener("click", function(e)  {
                    if(e.target && e.target.id.startsWith("btn-")) {
                        removeVideo(e.target.getAttribute("name"));
                    }
                });
                item.appendChild(btn);
            }
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        }
    });
};

function removeVideo(name) {
    event.preventDefault();
    alert(name);
    var url = "../../api/api.php/delete-video/" + name;

    $.ajax({
        type: "DELETE",
        url: url,
        success: function(response) {
            console.log(response);
            location.reload();
        },
        error: function(response) {
            console.log(response);
        }
    });
};