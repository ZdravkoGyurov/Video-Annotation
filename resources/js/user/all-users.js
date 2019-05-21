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
                item.appendChild(document.createTextNode(value.id + " " + value.email + " " + value.name + " " + value.surname + " " + value.roleName));
                list.appendChild(item);

                var btn = document.createElement('button');
                    btn.id = "btn-" + value.id;
                    btn.innerHTML = "Delete";
                    btn.setAttribute("email", value.email)
                    btn.addEventListener("click", function(e)  {
                        if(e.target && e.target.id.startsWith("btn-")) {
                            removeUser(e.target.getAttribute("email"));
                        }
                    });
                list.appendChild(btn);
            }
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
            location.reload();
        },
        error: function(response) {
            console.log(response);
        }
    });
};