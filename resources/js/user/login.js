var form = document.getElementById("loginForm");

form.addEventListener("submit", submitLoginForm);

function submitLoginForm() {
    event.preventDefault();

    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var formData = JSON.stringify({email:email, password:password});

    $.ajax({
        type: "POST",
        url: "../../api/api.php/login",
        dataType: "json",
        data: formData,
        success: function(response) {
            console.log(response);
            location.reload();
        },
        error: function(response) {
            console.log(response);
        }
    });
};