document.addEventListener("DOMContentLoaded", function(){
    showAllUsers();
});

function showAllUsers() {
    event.preventDefault();

    $.ajax({
        type: "GET",
        url: "../../api/api.php/all-users",
        success: function(response) {
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        }
    });
};