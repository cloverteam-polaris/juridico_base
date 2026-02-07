$(document).ready(function(){



    
     $('#btnEditarUsuario').click(function () {

        let valido = true;

        // Limpiar errores previos SOLO del form
        $('#formEditarUsuario .form-control').removeClass('is-invalid');
        $('#formEditarUsuario .invalid-feedback').text('');

        // Campos obligatorios
        const campos = [
            { id: '#usuarioEdit', mensaje: 'El usuario es obligatorio' },
            { id: '#nombreEdit', mensaje: 'El nombre es obligatorio' },
            { id: '#documentoEdit', mensaje: 'El documento es obligatorio' },
            { id: '#correoEdit', mensaje: 'El correo es obligatorio' },
            { id: '#telefonoEdit', mensaje: 'El teléfono es obligatorio' },
            { id: '#tipo_perfilEdit', mensaje: 'Debe seleccionar un perfil' }
        ];

        campos.forEach(campo => {
            const valor = $(campo.id).val();

            if (!valor || valor.trim() === '') {
                $(campo.id).addClass('is-invalid');
                $(campo.id).closest('.form-group').find('.invalid-feedback').text(campo.mensaje);
                valido = false;
            }
        });

        // Validar correo
        const correo = $('#correoEdit').val().trim();
        const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (correo !== '' && !regexCorreo.test(correo)) {
            $('#correoEdit').addClass('is-invalid');
            $('#correoEdit').closest('.form-group').find('.invalid-feedback').text('Formato de correo inválido');
            valido = false;
        }

        // Contraseña opcional
        const contrasena = $('#contrasenaEdit').val().trim();
        const confirmar = $('#confirmar_contrasenaEdit').val().trim();

        if (contrasena || confirmar) {

            if (contrasena === '') {
                $('#contrasenaEdit').addClass('is-invalid');
                $('#contrasenaEdit').closest('.form-group').find('.invalid-feedback').text('Ingrese la contraseña');
                valido = false;
            }

            if (confirmar === '') {
                $('#confirmar_contrasenaEdit').addClass('is-invalid');
                $('#confirmar_contrasenaEdit').closest('.form-group').find('.invalid-feedback').text('Confirme la contraseña');
                valido = false;
            }

            if (contrasena !== '' && confirmar !== '' && contrasena !== confirmar) {
                $('#confirmar_contrasenaEdit').addClass('is-invalid');
                $('#confirmar_contrasenaEdit').closest('.form-group').find('.invalid-feedback').text('Las contraseñas no coinciden');
                valido = false;
            }
        }

        // Si todo está OK => enviar form
        if (valido) {
            $('#formEditarUsuario')[0].submit(); // envío real
        }
    });

    // Quitar error al escribir o cambiar
    $('#formEditarUsuario .form-control').on('input change', function () {
    $(this).removeClass('is-invalid');
    $(this).closest('.form-group').find('.invalid-feedback').text('');
});




 document.querySelectorAll(".btnEditarTipoProceso").forEach(btn => {

    btn.addEventListener("click", async function () {

      const id = this.dataset.id;

      try {
      const res = await fetch(`${BASE_URL}administracion/getTipoProcesoEdit/${id}`);
        const data = await res.json();

        // llenar inputs
        document.getElementById("edit_idtipoproceso").value = data.idtipoproceso;
        document.getElementById("nombreTipoProcesoEdit").value = data.descripcion;

      } catch (error) {
        alert("Error trayendo información del tipo proceso");
        console.error(error);
      }
    });

  });



 document.querySelectorAll(".btnEditarMacroetapa").forEach(btn => {
  btn.addEventListener("click", async function () {

    const idetapa = this.dataset.id;

    try {
      // 1) Traer info de la macroetapa
      const res = await fetch(`${BASE_URL}administracion/getMacroetapaEdit/${idetapa}`);

      if (!res.ok) throw new Error("Error HTTP (getMacroetapaEdit): " + res.status);

      const data = await res.json();
      console.log("Macroetapa:", data);

      // Llenar inputs del modal/form
      document.getElementById("idMacroetapaEdit").value = data[0].idmacroetapa ?? data[0].idetapa ?? idetapa;
      document.getElementById("tipoProcesoEdit").value = data[0].idtipoproceso ?? "";
      document.getElementById("nombreMacroetapaEdit").value = data[0].descripcion ?? "";
      document.getElementById("diasNotificacionEdit").value = data[0].diasnotificacion ?? "";

      // 2) Segundo fetch: traer macroetapas por tipo de proceso
      const idTipoProceso = data[0].idtipoproceso;

      if (!idTipoProceso) {
        console.warn("No viene idtipoproceso en la respuesta, no se puede consultar getMacroPorTipo");
        return;
      }

      const res2 = await fetch(`${BASE_URL}administracion/getMacroPorTipo/${idTipoProceso}`);

      if (!res2.ok) throw new Error("Error HTTP (getMacroPorTipo): " + res2.status);

      const data2 = await res2.json();

      let lista = [];

      // 1) Si ya es array
      if (Array.isArray(data2)) {
        lista = data2;
      }
      // 2) Si viene dentro de una propiedad (muy común)
      else if (Array.isArray(data2.data)) {
        lista = data2.data;
      }
      else if (Array.isArray(data2.macroetapas)) {
        lista = data2.macroetapas;
      }
      // 3) Si viene un solo objeto
      else if (typeof data2 === "object" && data2 !== null) {
        lista = [data2];
      }

      let options = `<option value="1">Etapa Inicial</option>`;

      if (lista.length > 0) {
        lista.forEach(item => {
          options += `<option value="${item.idmacroetapa}">Despues de ${item.descripcion}</option>`;
        });
      } else {
        options += `<option value="">No hay macroetapas</option>`;
      }

      $("#ordenMacroproceso, #ordenMacroprocesoEdit").html(options);
      document.getElementById("ordenMacroprocesoEdit").value = data.idorden ?? "";

    } catch (error) {
      alert("Error trayendo información de la macroetapa");
      console.error(error);
    }
  });
});


$("#tipoProceso, #tipoProcesoEdit").on("change", function () {

  let idTipoProceso = $(this).val();

  $("#ordenMacroproceso, #ordenMacroprocesoEdit").html(`<option value="">Cargando...</option>`);

  if (idTipoProceso === "") {
    $("#ordenMacroproceso").html(`<option value="1">Etapa Inicial</option>`);
    $("#ordenMacroprocesoEdit").html(`<option value="1">Etapa Inicial</option>`);    
    return;
  }

  $.ajax({
    url: `${BASE_URL}administracion/getMacroPorTipo/${idTipoProceso}`,
    type: "GET",
    dataType: "json",
    success: function (data) {

      console.log("Respuesta backend:", data);

      let lista = [];

      // 1) Si ya es array
      if (Array.isArray(data)) {
        lista = data;
      }
      // 2) Si viene dentro de una propiedad (muy común)
      else if (Array.isArray(data.data)) {
        lista = data.data;
      }
      else if (Array.isArray(data.macroetapas)) {
        lista = data.macroetapas;
      }
      // 3) Si viene un solo objeto
      else if (typeof data === "object" && data !== null) {
        lista = [data];
      }

      let options = `<option value="1">Etapa Inicial</option>`;

      if (lista.length > 0) {
        lista.forEach(item => {
          options += `<option value="${item.idmacroetapa}">Despues de ${item.descripcion}</option>`;
        });
      } else {
        options += `<option value="">No hay macroetapas</option>`;
      }

      $("#ordenMacroproceso, #ordenMacroprocesoEdit").html(options);
    },
    error: function (xhr, status, error) {
      console.error("Error trayendo macroetapas:", error);
      console.log("Respuesta servidor:", xhr.responseText);
      $("#ordenMacroproceso, #ordenMacroprocesoEdit").html(`<option value="">Error cargando datos</option>`);
    }
  });

});











$("#tipoProcesoM").on("change", function () {

  let idTipoProceso = $(this).val();

  $("#macroetapa").html(`<option value="">Cargando...</option>`);

  if (idTipoProceso === "") {
    $("#macroetapa").html(`<option value="1">Etapa Inicial</option>`);    
    return;
  }

  $.ajax({
    url: `${BASE_URL}administracion/getMacroPorTipo/${idTipoProceso}`,
    type: "GET",
    dataType: "json",
    success: function (data) {

      console.log("Respuesta backend:", data);

      let lista = [];

      // 1) Si ya es array
      if (Array.isArray(data)) {
        lista = data;
      }
      // 2) Si viene dentro de una propiedad (muy común)
      else if (Array.isArray(data.data)) {
        lista = data.data;
      }
      else if (Array.isArray(data.macroetapas)) {
        lista = data.macroetapas;
      }
      // 3) Si viene un solo objeto
      else if (typeof data === "object" && data !== null) {
        lista = [data];
      }

      let options = `<option value="">Elija una macroetapa</option>`;

      if (lista.length > 0) {
        lista.forEach(item => {
          options += `<option value="${item.idmacroetapa}">${item.descripcion}</option>`;
        });
      } else {
        options += `<option value="">No hay macroetapas</option>`;
      }

      $("#macroetapa").html(options);
    },
    error: function (xhr, status, error) {
      console.error("Error trayendo macroetapas:", error);
      console.log("Respuesta servidor:", xhr.responseText);
      $("#macroetapa").html(`<option value="">Error cargando datos</option>`);
    }
  });

});





document.querySelectorAll(".btnEditarMicroetapa").forEach(btn => {
  btn.addEventListener("click", async function () {

    const id = this.dataset.id;

    try {

      // 1️⃣ Get microetapa
      const res = await fetch(`${BASE_URL}administracion/getMicroetapaEdit/${id}`);
      if (!res.ok) throw new Error("Error HTTP (getMicroetapaEdit): " + res.status);
      const data = await res.json();
      console.log("Microetapa:", data);

      // fill form
      document.getElementById("idMicroetapaEdit").value = data[0].idmicroetapa ?? "";
      document.getElementById("macroetapaEditMi").value = data[0].idetapa ?? "";
      document.getElementById("nombreMicroetapaEdit").value = data[0].descripcion ?? "";
      document.getElementById("diasNotificacionEdit").value = data[0].diasrevision ?? "";

      const idetapa = data[0].idetapa;
      if (!idetapa) {
        console.warn("No viene idetapa, cancelando request extra");
      }

      // 2️⃣ Get the macroetapa to know its tipo proceso
      let proceso;
      if (idetapa) {
        const resMacro = await fetch(`${BASE_URL}administracion/getMacroetapaEdit/${idetapa}`);
        if (!resMacro.ok) throw new Error("Error HTTP getMacroetapaEdit: " + resMacro.status);
        const macroResp = await resMacro.json();
        console.log("Respuesta getMacroetapaEdit:", macroResp);

        // normalizar
        proceso = Array.isArray(macroResp) && macroResp[0]
                   ? macroResp[0]
                   : macroResp;
      }

      // 3️⃣ obtener tipo de proceso con ese id
      let tipoProcesoObj;
      if (proceso && proceso.idtipoproceso) {
        const resTipo = await fetch(`${BASE_URL}administracion/getTipoProcesoEdit/${proceso.idtipoproceso}`);
        if (!resTipo.ok) throw new Error("Error HTTP getTipoProcesoEdit: " + resTipo.status);
        tipoProcesoObj = await resTipo.json();
        console.log("Respuesta getTipoProcesoEdit:", tipoProcesoObj);

        // fill select / input
        document.getElementById("tipoProcesoEditMi").value = tipoProcesoObj.idtipoproceso ?? "";
      } else {
        console.warn("No viene idtipoproceso para consultar tipo proceso");
      }

      // 4️⃣ reset macroetapa select based on tipo proceso
      if (tipoProcesoObj && tipoProcesoObj.idtipoproceso) {
        const resp = await fetch(`${BASE_URL}administracion/getMacroPorTipo/${tipoProcesoObj.idtipoproceso}`);
        if (!resp.ok) throw new Error("Error HTTP getMacroPorTipo: " + resp.status);
        const data3 = await resp.json();
        console.log("Respuesta getMacroPorTipo:", data3);

        let listaTipo = [];
        if (Array.isArray(data3)) listaTipo = data3;
        else if (Array.isArray(data3.data)) listaTipo = data3.data;
        else if (Array.isArray(data3.macroetapas)) listaTipo = data3.macroetapas;
        else if (typeof data3 === "object" && data3 !== null) listaTipo = [data3];

        let optionsTipo = `<option value="">Elija una macroetapa</option>`;
        listaTipo.forEach(item => {
          optionsTipo += `<option value="${item.idmacroetapa}">${item.descripcion}</option>`;
        });

        $("#macroetapaEditMi").html(optionsTipo);
      }

      // 5️⃣ Microetapas por macro (orden)
      const res2 = await fetch(`${BASE_URL}administracion/getMicroetapasPorMacro/${idetapa}`);
      if (!res2.ok) throw new Error("Error HTTP getMicroetapasPorMacro: " + res2.status);
      const data2 = await res2.json();
      console.log("Microetapas por macro:", data2);

      let listaMicro = [];
      if (Array.isArray(data2)) listaMicro = data2;
      else if (Array.isArray(data2.data)) listaMicro = data2.data;
      else if (Array.isArray(data2.microetapas)) listaMicro = data2.microetapas;
      else if (typeof data2 === "object" && data2 !== null) listaMicro = [data2];

      let optionsMicro = `<option value="1">Etapa Inicial</option>`;
      listaMicro.forEach(item => {
        optionsMicro += `<option value="${item.idmicroetapa}">Después de ${item.descripcion}</option>`;
      });

      $("#ordenMicroetapaEdit").html(optionsMicro);
      document.getElementById("ordenMicroetapaEdit").value = data[0].idorden ?? "";
      document.getElementById("macroetapaEditMi").value = data[0].idetapa ?? "";

    } catch (error) {
      alert("Error trayendo información de la microetapa");
      console.error(error);
    }

  });
});



$("#tipoProcesoEditMi").on("change", function () {

  let idTipoProceso = $(this).val();

  $("#macroetapaEditMi").html(`<option value="">Cargando...</option>`);

  if (idTipoProceso === "") {
    $("#macroetapaEditMi").html(`<option value="1">Etapa Inicial</option>`);    
    return;
  }

  $.ajax({
    url: `${BASE_URL}administracion/getMacroPorTipo/${idTipoProceso}`,
    type: "GET",
    dataType: "json",
    success: function (data) {

      console.log("Respuesta backend:", data);

      let lista = [];

      // 1) Si ya es array
      if (Array.isArray(data)) {
        lista = data;
      }
      // 2) Si viene dentro de una propiedad (muy común)
      else if (Array.isArray(data.data)) {
        lista = data.data;
      }
      else if (Array.isArray(data.macroetapas)) {
        lista = data.macroetapas;
      }
      // 3) Si viene un solo objeto
      else if (typeof data === "object" && data !== null) {
        lista = [data];
      }

      let options = `<option value="">Elija una macroetapa</option>`;

      if (lista.length > 0) {
        lista.forEach(item => {
          options += `<option value="${item.idmacroetapa}">${item.descripcion}</option>`;
        });
      } else {
        options += `<option value="">No hay macroetapas</option>`;
      }

      $("#macroetapaEditMi").html(options);
    },
    error: function (xhr, status, error) {
      console.error("Error trayendo macroetapas:", error);
      console.log("Respuesta servidor:", xhr.responseText);
      $("#macroetapaEditMi").html(`<option value="">Error cargando datos</option>`);
    }
  });

});








$("#macroetapa").on("change", function () {

  let idTipoProceso = $(this).val();

  $("#ordenMicroetapa").html(`<option value="">Cargando...</option>`);

  if (idTipoProceso === "") {
    $("#ordenMicroetapa").html(`<option value="1">Etapa Inicial</option>`);
    return;
  }

  $.ajax({
    url: `${BASE_URL}administracion/getMicroetapasPorMacro/${idTipoProceso}`,
    type: "GET",
    dataType: "json",
    success: function (data) {

      console.log("Respuesta backend:", data);

      let lista = [];

      // 1) Si ya es array
      if (Array.isArray(data)) {
        lista = data;
      }
      // 2) Si viene dentro de una propiedad
      else if (Array.isArray(data.data)) {
        lista = data.data;
      }
      else if (Array.isArray(data.microetapas)) {
        lista = data.microetapas;
      }
      // 3) Si viene un solo objeto (pero puede venir vacío {})
      else if (typeof data === "object" && data !== null) {

        // Si viene objeto vacío {}
        if (Object.keys(data).length === 0) {
          lista = [];
        } else {
          lista = [data];
        }
      }

      // Siempre arranca con Etapa Inicial
      let options = `<option value="1">Etapa Inicial</option>`;

      // Solo agrega opciones si realmente hay microetapas
      if (lista.length > 0) {
        lista.forEach(item => {
          options += `<option value="${item.idmicroetapa}">Despues de ${item.descripcion}</option>`;
        });
      }

      $("#ordenMicroetapa").html(options);
    },
    error: function (xhr, status, error) {
      console.error("Error trayendo microetapas:", error);
      console.log("Respuesta servidor:", xhr.responseText);

      // Si falla igual deja solo etapa inicial
      $("#ordenMicroetapa").html(`<option value="1">Etapa Inicial</option>`);
    }
  });

});








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


  

    $('#btnCrearUsuario').click(function () {

        // Limpiar errores previos
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        let valido = true;

        function marcarError(id, mensaje) {
            $('#' + id).addClass('is-invalid');
            $('#' + id).next('.invalid-feedback').text(mensaje);
            valido = false;
        }

        const usuario    = $('#usuario').val().trim();
        const contrasena = $('#contrasena').val();
        const confirmar  = $('#confirmar_contrasena').val();
        const nombre     = $('#nombre').val().trim();
        const documento  = $('#documento').val().trim();
        const correo     = $('#correo').val().trim();
        const telefono   = $('#telefono').val().trim();
        const perfil     = $('#tipo_perfil').val();

        if (!usuario) marcarError('usuario', 'El usuario es obligatorio');
        if (!contrasena) marcarError('contrasena', 'La contraseña es obligatoria');
        if (!confirmar) marcarError('confirmar_contrasena', 'Debe confirmar la contraseña');
        if (!nombre) marcarError('nombre', 'El nombre es obligatorio');
        if (!documento) marcarError('documento', 'El documento es obligatorio');
        if (!correo) marcarError('correo', 'El correo es obligatorio');
        if (!telefono) marcarError('telefono', 'El teléfono es obligatorio');
        if (!perfil) marcarError('tipo_perfil', 'Debe seleccionar un tipo de perfil');

        // Contraseñas iguales
        if (contrasena && confirmar && contrasena !== confirmar) {
            marcarError('confirmar_contrasena', 'Las contraseñas no coinciden');
        }

        // Validar correo
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (correo && !emailRegex.test(correo)) {
            marcarError('correo', 'El correo no tiene un formato válido');
        }

        if (valido) {
            $('#formCrearUsuario').submit();
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
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre: nombre, tipo: tipo, grilla: grilla }),
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
          $("#response").html('<div class="alert alert-success" role="alert">Campo creado con exito!!</div>');
          getListadoCampo();
      })
      .catch((error) => {
          $("#response").html('<div class="alert alert-danger" role="alert">'+error+'</div>');
          getListadoCampo();
      });

   }

}); // ✅ cierre correcto del click


function getListadoCampo(){
  fetch("getcamposlist", {
      method: "GET",
      headers: { 'Content-Type': 'application/json' },
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
      $("#response").html('<div class="alert alert-danger" role="alert">FFFFF: '+error+'</div>');
      $("#campo-guardar-btn").css("display", "block");
  });
}

}); // ✅ cierre final del document.ready