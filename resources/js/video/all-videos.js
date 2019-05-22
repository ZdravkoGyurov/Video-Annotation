document.addEventListener("DOMContentLoaded", function(){
    showAllVideos();
});

function showAllVideos() {
    event.preventDefault();

    $.ajax({
        type: "GET",
        url: "../../api/api.php/all-videos",
        success: function(response) {
            var list = document.getElementById('list-all-videos');
            for(key in response.data) {
                var value = response.data[key];

                var item = document.createElement('li');
                item.appendChild(document.createTextNode(value.name));
                list.appendChild(item);

                var decodedCookieRole = decodeURIComponent(getCookie('loggedUserRole'));

                if(decodedCookieRole == "Admin") {
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