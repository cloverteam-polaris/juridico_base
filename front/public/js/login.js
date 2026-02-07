$(document).ready(function(){

    $("#login-btn").click(() => {

    let usuario = $("#username").val();
    let password = $("#password").val();
    let rest = $("#rest").val();

    if (usuario === "" || usuario.length < 5) {
        alert("Debe ingresar un usuario válido.");
        return;
    }

    if (password === "" || password.length < 8) {
        alert("Debe ingresar la contraseña.");
        return;
    }

    let formdata = new FormData();
    formdata.append("username", usuario);
    formdata.append("password", password);

    fetch(rest + "users/login", {
        method: "POST",
        body: formdata
    })
    .then(async response => {

        // Si el backend responde error
        if (!response.ok) {
            const text = await response.text();
            throw new Error(text || "Credenciales inválidas");
        }

        // Respuesta correcta: JSON puro
        return response.json();
    })
    .then(data => {

        $("#response").html(
            '<div class="alert alert-success" role="alert">¡Bienvenido!</div>'
        );

        $.post("/front/admin/register", { token: data.access_token })
            .done(() => {
                location.href = "admin/modules";
            })
            .fail(() => {
                $("#response").html(
                    '<div class="alert alert-danger" role="alert">Error al establecer la sesión.</div>'
                );
            });

    })
    .catch(error => {

        $("#response").html(
            '<div class="alert alert-danger" role="alert">' + error.message + '</div>'
        );

    });

});
});