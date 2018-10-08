var x;
x = $(document);
x.ready(inicializarEventos);

//************************************************* 
function inicializarEventos() {

	 $.ajax({
        url: 'DatosRegistroBT.php',
        type: "POST",
        dataType: "json",
        data: "op=DatosRegistroFormulario",
        beforeSend: function (datos) {

        },
        success: function (datos) {
			
			
			$("#primernombre").val(datos.da_primer_nombre);
			$("#segundonombre").val(datos.da_segundo_nombre);
			$("#primerapellido").val(datos.da_primer_apellido);
			$("#segundoapellido").val(datos.da_segundo_apellido);
			$("#telefono").val(datos.da_telefonos);
			$("#direccion").val(datos.de_direccion);
			$("#preguntasecreta").val(datos.de_pregunta);
			$("#respuesta").val(datos.de_respuesta);
			$("#correo").val(datos.da_e_mail);
			$("#idusuario").val(datos.id_user);

//para el cambio de contraseña
			$("#idusuariocambiocontra").val(datos.id_user);

		  }
		});

    //*** Formulario Resgitro Datos
    $('#formDatosRegistro').submit(function () {

   var values = $("#formDatosRegistro").serialize();

		 $.ajax({
                url: 'DatosRegistroBT.php',
                type: "POST",
                data:values,
                beforeSend: function (datos) {
         		$("#datosregistroformimg").show();
        		var x = $("#datosregistroformimg");
         		x.html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<img 		 src="images/cargando.gif">');
                },
                success: function (datos) {
					

					
  if(datos==1){
						
  alert("Operaci\u00f3n Realizada con \u00e9xito.");
  $("#datosregistroformimg").hide();

	}else if(datos==0){
   alert("Problemas al realizar la operaci\u00f3n");
  $("#datosregistroformimg").hide();
		 }else if(datos==2){
    alert("Operaci\u00f3n Realizada con \u00e9xito.\nEnviamos un mensaje a su correo para que verifique el cambio de su correo");
  $("#datosregistroformimg").hide();
		 }
		 
		 
		 
		 
                }
            });
	
        return false;
    });
	
	
	
	  //*** Formulario Cambiar Contraseña
    $('#formCambiaContrasena').submit(function () {

	if($("#nuevacontrasena").val()==$("#repitacontrasena").val()){

   var values = $("#formCambiaContrasena").serialize();

		 $.ajax({
                url: 'DatosRegistroBT.php',
                type: "POST",
                data:values,
                beforeSend: function (datos) {
                   $("#datosregistroformimg2").show();
                    var x = $("#datosregistroformimg2");
                    x.html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<img src="images/cargando.gif">');
                },
                success: function (datos) {

					if(datos==1){
						
   alert("Operacion Realizada con exito");
   $("#datosregistroformimg2").hide();
   $('#formCambiaContrasena')[0].reset();
   }else if(datos==0){
   alert("Su contraseña actual en incorrecta");
   $("#datosregistroformimg2").hide();
   }else if(datos==2){
   alert("Problemas al realizar la operación");
   $("#datosregistroformimg2").hide();
   $('#formCambiaContrasena')[0].reset();
		}
	}
});
			}else{
				 alert("Las contraseñas no coinciden");
				 }
        return false;
    });
}
//**Fin 

/******************************FIN******************************/