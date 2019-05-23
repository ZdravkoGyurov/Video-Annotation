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
                var link = document.createElement('a');
                link.appendChild(document.createTextNode(value.name));
                link.href = "../video/view-video.php?videoName=" + value.name;
                link.className = "video-link";
                item.appendChild(link);
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

                var editLink = document.createElement('a');
                editLink.id = "link-edit-" + value.id;
                editLink.innerHTML = "Edit";
                editLink.href = "../video/edit-video.php?videoName=" + value.name;
                editLink.className = "edit-link";
                item.appendChild(editLink);
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
    var url = "../../api/api.php/delete-video/" + name;

    $.ajax({
        type: "DELETE",
        url: url,
        success: function(response) {
            console.log(response);
            if(!response.errors) {
                if(!window.location.href.includes("?deleted=" + name) && !window.location.href.includes("?deleted=")) {
                    window.location.href += "?deleted=" + name;
                } else if(!window.location.href.includes("?deleted=" + name)) {
                    var urlWithoutParameters = window.location.href.substring(0, window.location.href.indexOf('?'));
                    window.location.href = urlWithoutParameters + "?deleted=" + name;
                } else {
                    location.reload();
                }
            } else {
                if(!document.getElementById("fail-message")) {
                    var failMessage = document.createElement('p');
                    failMessage.id = "fail-message";
                    failMessage.innerHTML = "Failed to delete video!";
                    document.getElementsByTagName("h1")[0].prepend(failMessage);
                }
            }
        },
        error: function(response) {
            console.log(response);
        }
    });
};

document.addEventListener("DOMContentLoaded", function(){
    var urlString = window.location.href;
    var url = new URL(urlString);
    var deleted = url.searchParams.get("deleted");

    if(deleted) {
        var successMessage = document.createElement('p');
        successMessage.id = "success-message";
        successMessage.innerHTML = "Video " + "'" + deleted + "'" + " successfully deleted!";
        document.getElementsByTagName("h1")[0].prepend(successMessage);
    }
});