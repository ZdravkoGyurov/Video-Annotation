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
            console.log(response);
            location.reload();
        },
        error: function(response) {
            console.log(response);
        }
    });
};