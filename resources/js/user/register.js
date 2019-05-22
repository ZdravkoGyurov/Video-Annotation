var form = document.getElementById("registerForm");

form.addEventListener("submit", submitRegisterForm);

function submitRegisterForm() {
    event.preventDefault();

    var email = document.getElementById('email').value;
    var name = document.getElementById('name').value;
    var surname = document.getElementById('surname').value;
    var password = document.getElementById('password').value;
    var passwordRepeat = document.getElementById('passwordRepeat').value;
    var formData = JSON.stringify({email:email, name:name, surname:surname, password:password, passwordRepeat:passwordRepeat});

    // validate fields

    if(true) { // no errors
        $.ajax({
            type: "POST",
            url: "../../api/api.php/register",
            dataType: "json",
            data: formData,
            success: function(response) {
                if(response.errors) {
                    console.log(response.errors);
                } else {
                    location.replace("../user/login.php?register=true");
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
};