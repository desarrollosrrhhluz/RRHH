var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos()
{ 
$(".error").hide();
 /*$.getJSON("censoUniformes.php",{op:"DatosTrabajador"},function(datos){									
										
										  var arreglo = datos;	
										$("#form_uniformes #talla1").attr("value",arreglo[0].talla1);
										$("#form_uniformes #talla2").attr("value",arreglo[0].talla2);
										$("#form_uniformes #talla3").attr("value",arreglo[0].talla3);
										
										});*/
busquedaCedula();

}
function busquedaCedula(){
	var options = { 
        target:        '#destino1', // elemento destino que se actualizará 
       // beforeSubmit:  showRequestNC,  //  respuesta antes de llamarpre-submit callback 
        success:       showResponseNC,  //  respuesta después de llamar 
		//url:       'proc_benef.php',         // override for form's 'action' attribute 
        //type:      'post',        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  'script', 
		resetForm: false       // reinicia el formulario después de ser enviado correctamente 
  }; 
	 $('#form_BusquedaCedula').validate({
									   
				rules: {
						
						cedula_trabajador: {
						   required: true
								  }
							 },//end rules					   
				
				submitHandler: function(form) {
				$('#form_BusquedaCedula').ajaxSubmit(options);
				}
				}); 
	
	
	}
	
function showResponseNC(){
	form_datos();
	
	}


function form_datos(){
	var options = { 
        target:        '#destino', // elemento destino que se actualizará 
       // beforeSubmit:  showRequestDP,  //  respuesta antes de llamarpre-submit callback 
        success:       showResponseDP,  //  respuesta después de llamar 
        dataType:  'json', 
		resetForm: false       // reinicia el formulario después de ser enviado correctamente 
  }; 
  
	  $('#form_uniformes').validate({
			messages: {
			
				    telefono_local: { required: 'Ingrese un numero de telefono residencial'},
					 email: { required: 'Ingrese un correo electronico' },
				    telefono_celular: {required:'Ingrese un numero de telefono celular'}
						
							 },
			errorLabelContainer: $("#error"),
		    wrapper: 'li',							 
				rules: {
				
					talla1: { required: true},
						talla2: { required: true},	
						talla3: {required: true }
							 },//end rules							   
				submitHandler: function(form) {
				$('#form_uniformes').ajaxSubmit(options);
				}
				}); 
	
	
	}
	
function showResponseDP(data){
	jQuery.noticeAdd({
                        text: data.message,
                        stay: false
                });	
	/*$.getJSON("censoUniformes.php",{op:"DatosTrabajador"},function(datos){									
										
										  var arreglo = datos;	
										$("#form_uniformes #talla1").attr("value",arreglo[0].talla1);
										$("#form_uniformes #talla2").attr("value",arreglo[0].talla2);
										$("#form_uniformes #talla3").attr("value",arreglo[0].talla3);
										
										}); */
	}
