var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 
$('#error').hide();
$('#muestra_circular').hide();
consultaInicial();

}
function consultaInicial(){
actualiza_correo();		

BuscaConcursos();
													
	}
	
////////////////////////////////////////	
function actualiza_correo(){
	
	  //$('#caja_correo').boxy();

    /* new Boxy($('#caja_correo'), {title: "Actualizacion de Correo Electr&oacute;nico", closeable: true, modal:true, closeText:''});
 
var options = { 
        target:        '#destino', // elemento destino que se actualizará 
       // beforeSubmit:  showRequestDP,  //  respuesta antes de llamarpre-submit callback 
        success:       showResponseCorreo,  //  respuesta después de llamar 
        //dataType:  'json', 
		resetForm: false       // reinicia el formulario después de ser enviado correctamente 
  }; 
	
				$('#act_correo').ajaxForm(options);*/
				


	}
function showResponseCorreo(){
		$('.close').trigger("click");
		}

/////////////////////////////////////////////
function BuscaConcursos(){

 $.post("concursos.php",{op:"buscaConcursos"},function(datos) { 
									         $("#cuerpo_concursos").html(datos) ;
											 muestra_cita();
											$("#form_postular").submit(postularse);
											 $(".externo").tooltip();
											 $(".igual").tooltip();
											 		$("#btn_confirmado").click(function(){
													var values = $("#form_postular").serialize();
													$.ajax({
														url: 'concursos.php',
														type: "POST",
														data:values,
														success: showResponseDP,
														dataType: 'json'
													});
													
													$("#btn_cancelar_confirm").attr("disabled",true);
													$("#btn_confirmado").attr("disabled",true);
													$("#btn_confirmado").html("Procesando...");
											
												return false;
													
													});
					

																 }); 		
	
	}

function postularse(){
var values = $("#form_postular").serialize();

		 $.ajax({
                url: 'concursos.php',
                type: "POST",
                data:values,
                beforeSend: showRequestDP ,
                success: showResponseDP,
				dataType: 'json'
            });
	
        return false;

	}

function showRequestDP(){
	
	var arraySMO = new Array();
	$(".opcion").each(function(i){
	var  sel = $(this).attr("id"); 							 
	if($("#"+sel).is(':checked')) {
    // var  sel = $(this).attr("id"); 
	arraySMO[i]=sel; 
	}
}); 
	var cadenaSMO = arraySMO.join(',');
	if(cadenaSMO!=""){
	  $.post("concursos.php",{op:"crea_circular", id:cadenaSMO},function(datos) { 
									         $("#cuerpo_circular").html(datos) ;
											 $('#muestra_circular').show();
											 $('#cuerpo_concursos').hide();
											 // muestra la cirdular del concurso
										    $("#btn_cancelar").click(function(){$('#muestra_circular').hide(); $('#cuerpo_concursos').show();});
											
											
											$("#btn_confirmacion").click(function(){
												// Muestra dialogo de confimacion
												$('#dialogo_confirmacion').modal('show');
												//bonton de confirmacion final
											

																				  
															});
														 }); 	
	return false;
	}else{
	//	alert('Debe seleccionar un concurso');
		return false;
		}
		return false;
	}

function showResponseDP(data){
	$("#btn_cancelar_confirm").removeAttr("disabled");
	$("#btn_confirmado").removeAttr("disabled");
	$("#btn_confirmado").html("Si, estoy seguro");
	$('#dialogo_confirmacion').modal('hide');
	$('#muestra_circular').hide(); $('#cuerpo_concursos').show();
	$("#div_mensaje").html(data.message);
	$('#dialogoMensaje').modal('show');
//	 data.message,
                       
BuscaConcursos();
	}
	
function muestra_cita(){
	 $.post("concursos.php",{op:"muestra_cita"},function(datos) { 
									         $("#texto_cita").html(datos) ;
											 						

																 }); 
	
	
	}