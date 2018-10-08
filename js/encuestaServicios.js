var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos()
{ 
$(".error").hide();
;
$.post("encuestaServicios.php",{op:"consultaInicialDatos"},function(datos) { 
									         $("#datos_trabajador").html(datos) ;
											consultaRespondio()
																 }); 

}

function BuscaCursos(){
	$.post("encuestaServicios.php",{op:"buscaCursos"},function(datos) { 
									         $("#cuerpo_cursos").html(datos) ;carga_limites();
																											 });
	
	
	}
function carga_limites(){
	
	var options = { 
        target:        '#destino', // elemento destino que se actualizará 
        beforeSubmit:  showRequestNC,  //  respuesta antes de llamarpre-submit callback 
        success:       showResponseNC,  //  respuesta después de llamar 
		//url:       'proc_benef.php',         // override for form's 'action' attribute 
        type:      'post',        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  'script', 
		resetForm: true       // reinicia el formulario después de ser enviado correctamente 
  }; 
	 $('#form').validate({
					
			errorLabelContainer: $("#error"),
		    wrapper: 'span',					   
				
				submitHandler: function(form) {
				$('#form').ajaxSubmit(options);
				}
				}); 
	
	
	$(".opcion").each(function(i){
	var  sel = $(this).attr("id");
	$("#"+sel).rules("add", {
required: true,
messages: {
   required: "Debe responder todas las Preguntas"
 }
});
			 
});
	
	
	}	

function showRequestNC(){
	//alert("Hola popis22");
//	return true;
	}

function showResponseNC(){
	$.getJSON("encuestaServicios.php",{op:"consultaRespondio"},function(datos){
										 											
										  var arreglo = datos;	
										 
										 if(arreglo==1){
						$("#cuerpo_principal").html(' <fieldset style="width:800px"><legend>Gracias </legend><div id="cuerpo_cursos" align="center"><h2>Tus respuestas fueron almacenadas exitosamente, Gracias por hacernos llegar tu opinion</h2></div></div></fieldset>')
											 

											 }
										
											
										}); 	
	}
///******************************************
function consultaRespondio(){
	$.getJSON("encuestaServicios.php",{op:"consultaRespondio"},function(datos){
										 											
										  var arreglo = datos;	
										 
										 if(arreglo==1){
						$("#cuerpo_principal").html(' <fieldset style="width:800px"><legend>Gracias </legend><div id="cuerpo_cursos" align="center"><h2>Ya completaste la encuesta, Gracias por hacernos llegar tu Opinion</h2></div></div></fieldset>')
											 

											 }else{
												  BuscaCursos();
												 }
										
											
										}); 	
	}