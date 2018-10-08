var x;
x=$(document);
x.ready(inicializarEventos);
 
function inicializarEventos()
{ 
//*************************************************
//  Barra Cargando
//$('#txtcdestinatario').tooltip('toggle'); 
	$('#form_destinatario').submit(form_destinatario);
   $("#invisible").hide();
  // $("#destino").hide();
   $("#btnsolicitar").attr("disabled","disabled");
   $("#txtcdestinatario").attr("disabled","disabled");
//$("input[type='checkbox']").length
//*************************************************  
}
function form_destinatario(){
	   var values = $("#form_destinatario").serialize();

		 $.ajax({
                url: 'constanciaTrabajoBT.php',
                type: "POST",
                data:values,
                beforeSend: showRequest ,
                success: showResponse,
				dataType: 'json'
            });
	
        return false;
	
	
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
  // bloquear();
 if( $("input[type='checkbox']").length < 1 ){
  
  
	if(confirm('Esta seguro de continuar con la solicitud de Constancia de Trabajo?')){
	//desbloquear();
    return true;
	
	}else{
	//desbloquear();	
	return false;	
		}
 }else{
	if($("input[type='checkbox']:checked").length>0){
		if(confirm('Esta seguro de continuar con la solicitud de Constancia de Trabajo?')){
		return true;
		}else{	
		return false;	
			}
		}else{
			alert("Debe Seleccionar por lo menos un Tipo de personal / Ubicacion");
			return false;	
			}
	 }
		
		
}
//************************************************** 
function showResponse(data)  {        
       
	//alert(data[0].codigo);
window.open('./constanciaTrabajoPDFnew.php?tipo='+data[0].tipoc+'&tipop='+data[0].tipop+'&cou='+data[0].cou+'&cedula='+data[0].cedula+'&destino='+data[0].destino+'&destinatario='+data[0].destinatario+'','Constancia','width=800,height=600,menubar=no,scrollbars=yes,toolbar=no,location=no,directories=no,resizable=no,top=0,left=0');
    chequear();
	chequear2();  
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
 
}
//************************************
function llegadaDatos(datos) // ejemplo
{
	
}

//************************************


