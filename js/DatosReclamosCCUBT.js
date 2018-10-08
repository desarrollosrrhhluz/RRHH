var x;
x = $(document);
x.ready(inicializarEventos);

//************************************************* 
function inicializarEventos() {

	 $.ajax({
     url: 'DatosReclamoCCUBT.php',
        type: "POST",
        dataType: "json",
        data: "op=DatosRegistroFormulario",
        beforeSend: function (datos) {

        },
        success: function (datos) {

	  $("#idusuarioReclamoCCU").val(datos.id_user);
      $("#correoUsuarioReclamoCCU").val(datos.da_e_mail);

		  }
		});

$("#divFormularioReclamoCCU").show();
  $("#divReclamoCCUYaRealizado").hide();

 /*$.ajax({
     url: 'DatosReclamoCCUBT.php',
        type: "POST",
  
        data: "op=VerificaYaRealizoReclamo",
        beforeSend: function (datos) {

        },
        success: function (datos) {

    if(datos==1){
 
  $("#divFormularioReclamoCCU").hide();
  $("#divReclamoCCUYaRealizado").show();

  }else if(datos==0){
  
  $("#divFormularioReclamoCCU").show();
  $("#divReclamoCCUYaRealizado").hide();
     }


    }
});
*/

$('#formReclamoCCU').unbind();

    //*** Formulario Resgitro Datos
    $('#formReclamoCCU').submit(function () {

   var values = $("#formReclamoCCU").serialize();


		$.ajax({
                url: 'DatosReclamoCCUBT.php',
                type: "POST",
                data:values,
                beforeSend: function (datos) {
                
         		$("#datosregistroformimgCCU").show();
        		var x = $("#datosregistroformimgCCU");
         		x.html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<img 		 src="images/cargando.gif">');

$("#enviarDPReclamo").attr('disabled', true); 
          
                },
                success: function (datos) {
					
$("#enviarDPReclamo").attr('disabled', false); 


					
  if(datos==1){
						
 
 $("#datosregistroformimgCCU").hide();
 $("#divFormularioReclamoCCU").html('');

 $("#divFormularioReclamoCCU").html('<div class="alert alert-success" role="alert"><h2>Recibimos su solicitud con éxito.</h2></div>');

	}else if(datos==0){

   alert("Problemas al realizar la operaci\u00f3n");
  $("#datosregistroformimgCCU").hide();

	}

  }
});
	
        return false;
});
	
	
	
	 
}
//**Fin 

/******************************FIN******************************/