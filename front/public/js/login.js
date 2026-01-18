$(document).ready(function(){

    $("#login-btn").click(() => {

       
        let usuario = $("#username").val();
        let password = $("#password").val();
        let rest = $("#rest").val();
        let flag = 0;

        if(usuario == "" || usuario.length < 5){
            alert("Debe ingresar un usuario valido.");
            return false;
        }
        if(password == "" || password.length < 10){
            alert("Debe ingresar la contraseña.");
            return false;
        }

        let formdata = new FormData();
        
        
        formdata.append('username', usuario);
        formdata.append('password', password);
        

        fetch(rest+"users/login", {
            method: "POST",
            body: formdata,
            processData: false, 
            contentType: false,
            dataType: "json",
            encode: true,
          })
          .then(async (response) => {
                // Check if the request was successful (status code 200-299)
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.detail);
                }
                // Parse the response body as JSON
                
                return response.json(); 
            })
            .then(data => {
                // Process the parsed data (e.g., display it on the page)
                 $("#response").html('<div class="alert alert-success" role="alert">Bienvenido!.</div>');

                
                $.post("/universe/sun/admin/register", { token: data.access_token}, function (data) {
                    location.href='admin/modules';    
                }).fail(() => {
                    $("#response").html('<div class="alert alert-danger" role="alert">Error al establecer la sesion.</div>');
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            })
            .catch((error) => {
                // Handle any errors that occurred during the fetch operation
                $("#response").html('<div class="alert alert-danger" role="alert">'+error.message+'</div>');
                //console.error('Error fetching data:', error);
            });

    });
});