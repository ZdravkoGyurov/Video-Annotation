document.addEventListener("DOMContentLoaded", function(){
    showAllVideos();
});

function showAllVideos() {
    event.preventDefault();

    $.ajax({
        type: "GET",
        url: "../../api/api.php/all-videos",
        success: function(response) {
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        }
    });
};