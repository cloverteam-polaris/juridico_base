$(document).ready(function(){

    $("#upload-file").click(async () => {
        const fileInput = document.getElementById('document-analisys');
        const file = fileInput.files[0]; // Obtener el archivo seleccionado
        $("#ai-resp").html('<div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>');
        $("#save-doc-resp").css("display", "none");

        if (!file) {
            alert('Por favor, selecciona un archivo para subir.');
            return;
        }

        // Crear un objeto FormData
        const formData = new FormData();
        formData.append('archivo-analizar', file); // Agrega el archivo al formData con el nombre 'myFile'

        try {
            const response = await fetch('/universe/jupiter/operativo/analizar-documento', {
            method: 'POST',
            body: formData, // Enviar el objeto FormData en el cuerpo de la solicitud
            });

            if (response.ok) {
                const result = await response.text(); // O response.text() dependiendo de la respuesta del servidor
                $("#ai-resp").html("<p>"+result+"</p>");
                $("#save-doc-resp").css("display", "block");
            } else {
                let resp = await response.json();
                console.log(resp.message)
                
                throw new Error(resp.message);
            }
        } catch (error) {
            console.error(error);
            alert(error);
        }
    });


    $("#save-doc-resp").click(function(){
        let texto = $("#informacion pre").text();
        
        let datos = JSON.parse(texto);
        let msj = "";
        if(!datos.identificacion){
            msj = "No existen datos suficientes para asociar el documento."
        }else{
            msj = "Quieres guardar el "+datos.tipo_documento+",\na nombre de "+datos.nombre+",\ncon documento "+datos.identificacion;
        }
        Swal.fire({
            title: "¿Estás seguro?",
            text: msj,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, continuar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {


                 fetch("savearchivo", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            nombre: nombre,
                            tipo: tipo,
                            grilla: grilla
                        }),
                        dataType: "json",
                        encode: true,
                    })
                    .then(async (response) => {
                            const contentType = response.headers.get('content-type');
                            
                            if (!response.ok) {
                                const errorData = await response.json();
                                throw new Error(errorData.message);
                            }
                            
                            if (contentType && contentType.includes('application/json')) {
                            return response.json().then(data => ({ tipo: 'json', data }));
                            } else {
                            return response.text().then(data => ({ tipo: 'texto', data }));
                            }
                        })
                        .then(data => {
                            // Process the parsed data (e.g., display it on the page)
                            $("#response").html('<div class="alert alert-success" role="alert">Campo creado con exito!!</div>');
                            getListadoCampo();
                            
                        })
                        .catch((error) => {
                            // Handle any errors that occurred during the fetch operation
                            $("#response").html('<div class="alert alert-danger" role="alert">'+error+'</div>');
                            getListadoCampo();
                            //console.error('Error fetching data:', error);
                        });



            } else if (result.dismiss === Swal.DismissReason.cancel) {
                
            }
            });
        //console.log(datos)
    })


    /*
    * Crear campo database
    */

    $("#campo-guardar-btn").click(() =>{
        
       $("#campo-guardar-btn").css("display", "none");
       $("#campos-lista").html('<div class="spinner-grow text-danger" role="status"><span class="sr-only">Guardando!</span></div>');
       $("#response").html('');
       
       let nombre = $("#campo-nombre").val();
       let tipo = $("#campo-tipo").val();
       let grilla = $("#campo-grilla").prop("checked");
       let flag = 0;    


       if(nombre == ""){
          $("#campo-nombre").addClass("is-invalid");
          flag += 1;
       }else{
          $("#campo-nombre").removeClass("is-invalid");
       }

       if(tipo == 0){
          $("#campo-tipo").addClass("is-invalid");
          flag += 1;
       }else{
          $("#campo-tipo").removeClass("is-invalid");  
       }

       if(flag > 0){
            $("#campo-guardar-btn").css("display", "block");
            $("#campos-lista").html('');
       }else{


        fetch("savecampo", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombre: nombre,
                tipo: tipo,
                grilla: grilla
            }),
            dataType: "json",
            encode: true,
          })
          .then(async (response) => {
                const contentType = response.headers.get('content-type');
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message);
                }
                
                if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => ({ tipo: 'json', data }));
                } else {
                return response.text().then(data => ({ tipo: 'texto', data }));
                }
            })
            .then(data => {
                // Process the parsed data (e.g., display it on the page)
                 $("#response").html('<div class="alert alert-success" role="alert">Campo creado con exito!!</div>');
                 getListadoCampo();
                 
            })
            .catch((error) => {
                // Handle any errors that occurred during the fetch operation
                $("#response").html('<div class="alert alert-danger" role="alert">'+error+'</div>');
                getListadoCampo();
                //console.error('Error fetching data:', error);
            });
            

       }

    });
    
});


function getListadoCampo(){
    fetch("getcamposlist", {
            method: "GET",
            headers: {
                'Content-Type': 'application/json',
            },
            dataType: "json",
            encode: true,
          })
          .then(async (response) => {
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message);
                }
                return response.text(); 
            })
            .then(data => {
                $("#campo-guardar-btn").css("display", "block");
                $("#campos-lista").html(data);
                $("#campo-nombre").val("");
                $('#campo-tipo').val($('#campo-tipo option[selected]').val());
            })
            .catch((error) => {
                // Handle any errors that occurred during the fetch operation
                $("#response").html('<div class="alert alert-danger" role="alert">FFFFF: '+error+'</div>');
                $("#campo-guardar-btn").css("display", "block");
                //console.error('Error fetching data:', error);
    });
}