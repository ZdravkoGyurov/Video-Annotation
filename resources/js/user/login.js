var form = document.getElementById("loginForm");

form.addEventListener("submit", submitLoginForm);

function submitLoginForm() {
    event.preventDefault();

    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var formData = JSON.stringify({email:email, password:password});

    // validate fields

    if(true) { // no errors
        $.ajax({
            type: "POST",
            url: "../../api/api.php/login",
            dataType: "json",
            data: formData,
            success: function(response) {
                if(response.errors) {
                    console.log(response.errors);
                } else {
                    location.replace("../video/all-videos.php");
                }
                // console.log(response.roleId);
            },
            error: function(response) {
                location.reload();
                console.log(response);
            }
        });
    }
};