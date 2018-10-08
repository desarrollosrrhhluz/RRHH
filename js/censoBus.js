var x;
x = $(document);
x.ready(inicializarEventos);

//************************************************* 
function inicializarEventos() {

    //*** Formulario Resgitro Datos
    $('#form_bus').submit(function () {

    	var decision = $('decision').val();

    	if(decision != " "){

    		alert("Debe seleccionar una opción");

    	}else{

	   		var values = $("#form_bus").serialize();
			$.ajax({
	                url: 'censoBusAdm.php',
	                type: "POST",
	                data:values,
	                success: function (datos) {
										
						if(datos==1){
						
							alert("Decision Registradas con exito.")						

						}else if(datos==0){
						    alert("Problemas al realizar la operaci\u00f3n.");
						}
	  				}
			});
    	}

        return false;
	});

	 
}
/******************************FIN******************************/