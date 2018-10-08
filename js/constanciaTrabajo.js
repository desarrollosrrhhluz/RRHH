var x;
x=$(document);
x.ready(inicializarEventos);
 
function inicializarEventos()
{ 
//*************************************************
//  Barra Cargando
$("#loading img").ajaxStart(function(){
   $(this).show();
 }).ajaxStop(function(){
   $(this).hide();
 });

var options = { 
        target:        '#destino', // elemento destino que se actualizará 
        beforeSubmit:  showRequest,  //  respuesta antes de llamarpre-submit callback 
        success:       showResponse,  //  respuesta después de llamar 
		//url:       'proc_benef.php',         // override for form's 'action' attribute 
        //type:      'post',        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  'script', 
		resetForm: true       // reinicia el formulario después de ser enviado correctamente 
  }; 
	$('#form_destinatario').ajaxForm(options);	 
	
   $("#invisible").hide();
  // $("#destino").hide();
   $("#btnsolicitar").attr("disabled","disabled");
   $("#txtcdestinatario").attr("disabled","disabled");
  
  
  
//*************************************************  
 
}


//************************************************** 
function chequear(){
if ($("#btndestino").attr("value","1")){
	$("#btnsolicitar").removeAttr("disabled");
	 $("#txtcdestinatario").attr("disabled","disabled");
	 $("#btncancelar").click(cancelar);
	// $("#btnsolicitar").click(imprimir_planilla);

	}

}

function chequear2(){
if ($("#btndestino").attr("value","2")){
	$("#btnsolicitar").attr("disabled", "disabled");
	 $("#txtcdestinatario").removeAttr("disabled");
	 	}
	}
//******************
function boton_act(){
	if(document.form_destinatario.txtcdestinatario.value.length == 0){
	$("#btnsolicitar").attr("disabled", "disabled");	
    }else{
	$("#btnsolicitar").removeAttr("disabled");
	 $("#btncancelar").click(cancelar);
	// $("#btnsolicitar").click(imprimir_planilla);		
	}
	
	}



	/*$('#form_solicitud').submit(function() { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(options); 
  
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); */
	 
	
//*************************************************
 function showRequest(formData, jqForm) {    
    bloquear();
	if(confirm('¿ Esta seguro de continuar con la solicitud de Constancia de Trabajo?')){
	desbloquear();
    return true;
	
	}else{
	desbloquear();	
	return false;	
		}
		
		
		
}
//************************************************** 
function showResponse(responseText, statusText)  {        
       
	
/*window.open('./impresion.php','Constancia','width=800,height=600,menubar=no,scrollbars=yes,toolbar=no,location=no,directories=no,resizable=no,top=0,left=0');*/
      
}

 
function cancelar()
{
   $("#btndestino").attr("checked",""); 
	 $("#txtcdestinatario").attr("disabled","disabled");
	 $("#btnsolicitar").attr("disabled","disabled");
     }

 

//*************************************************
function inicioEnvio() // ejemplo
{
  var x=$("#resultados");
  x.html('<img src="./images/loading4.gif">');
}
//************************************
function llegadaDatos(datos) // ejemplo
{
	if(datos.length > 200){		
      $("#frm").html(datos);
	  $("#msj").text(""); // limpia el msj de registro
	  $("#resultados").text(""); // quita el gif. de carga
	  $("#resultados").addClass("mas");
       var x;
       x=$("#buscar");
       x.click(presionaBuscar);
	    x=$("#cancelar");
       x.click(presionaCancelar);
	}else{		
	   $("#passw").attr("value","");
	   $("#passw").focus();
	   $("#resultados").text("* Nombre de Usuario O Clave incorrecta Verifique...").addClass("error");
	}
}

//************************************
function problemas()
{
  $("#resultados").text('Problemas en el servidor.');
}

function bloquear() {
    $("#capaMsj").html();
//    $("#capaMsj").html('<img src="images/spinner.gif"><br/>Cargando...');
//    $("#capaMsj").html('<img src="images/loader.gif"><br/>Cargando...');
    $("#capaMsj").show();
    $("#capaBloqueo").show();
}
function desbloquear() {
    $("#capaMsj").hide();
    $("#capaBloqueo").hide();
}


