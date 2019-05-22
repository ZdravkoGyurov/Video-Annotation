var form = document.getElementById("restorePasswordForm");

form.addEventListener("submit", submitRestorePasswordForm);

function submitRestorePasswordForm() {
    event.preventDefault();

    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var passwordRepeat = document.getElementById('passwordRepeat').value;
    var formData = JSON.stringify({email:email, password:password, passwordRepeat:passwordRepeat});

    // validate fields

    if(true) { // no errors
        $.ajax({
            type: "POST",
            url: "../../api/api.php/update-password",
            dataType: "json",
            data: formData,
            success: function(response) {
                if(response.errors) {
                    console.log(response.errors);
                } else {
                    location.replace("../user/login.php?restore=true");
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
};