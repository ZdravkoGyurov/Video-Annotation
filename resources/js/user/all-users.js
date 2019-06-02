document.addEventListener("DOMContentLoaded", function(){
    showAllUsers();
});

function showAllUsers() {
    event.preventDefault();

    $.ajax({
        type: "GET",
        url: "../../api/api.php/all-users",
        success: function(response) {
            var list = document.getElementById('list-all-users');
            for(key in response.data) {
                var value = response.data[key];

                var item = document.createElement('li');
                item.id = "li-" + value.id;
                item.appendChild(document.createTextNode("ID: " + value.id + " | Email: " + value.email + " | Name: " + value.name + " " + value.surname + "(" + value.roleName + ")"));
                list.appendChild(item);

                var btn = document.createElement('button');
                btn.id = "btn-delete-" + value.id;
                btn.innerHTML = "Delete";
                btn.setAttribute("email", value.email);
                btn.addEventListener("click", function(e)  {
                    if(e.target && e.target.id.startsWith("btn-")) {
                        removeUser(e.target.getAttribute("email"));
                    }
                });
                item.appendChild(btn);

                var btn = document.createElement('button');
                btn.id = "btn-show-" + value.id;
                btn.innerHTML = "Show videos";
                btn.setAttribute("usrId", value.id);
                btn.setAttribute("email", value.email);
                btn.setAttribute("videos-hidden", "true");
                btn.addEventListener("click", function(e)  {
                    if(e.target && e.target.id.startsWith("btn-")) {
                        if(e.target.getAttribute("videos-hidden") == "true") {
                            showUserVideos(e.target.getAttribute("usrId"), e.target.getAttribute("email"), e.target);
                        } else {
                            hideUserVideos(e.target.getAttribute("usrId"), e.target);
                        }
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

function hideUserVideos(id, btn) {
    var list = document.getElementById('ul-' + id);
    list.remove();
    btn.innerHTML = "Show videos";
    btn.setAttribute("videos-hidden", "true");
}

function showUserVideos(id, email, btn) {
    event.preventDefault();

    var url = "../../api/api.php/find-user-videos/" + email;

    $.ajax({
        type: "GET",
        url: url,
        success: function(response) {
            if(!response.errors) {
                var list = document.getElementById('li-' + id);
                var vidList = document.createElement("ul");
                vidList.id = "ul-" + id;
    
                for(key in response.data) {
                    var value = response.data[key];
    
                    var item = document.createElement('li');
                    item.appendChild(document.createTextNode(value.name));
                    vidList.appendChild(item);
                }
                list.appendChild(vidList);
                btn.innerHTML = "Hide videos";
                btn.setAttribute("videos-hidden", "false");
            }
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        }
    });
};

function removeUser(email) {
    event.preventDefault();

    var url = "../../api/api.php/delete-user/" + email;

    $.ajax({
        type: "DELETE",
        url: url,
        success: function(response) {
            console.log(response);
            location.reload(true);
        },
        error: function(response) {
            console.log(response);
        }
    });
};