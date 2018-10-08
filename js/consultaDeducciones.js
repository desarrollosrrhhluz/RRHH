var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 
$('#error').hide();

consultaInicial();
carga_especifica();
$('#form_consulta').submit(envioForm);
}
function consultaInicial(){

 $.post("consultaDeducciones.php",{op:"carga_conceptos"},function(datos) { 
									         $("#conceptos").html(datos) ;	 }); 		
	}
////////////////////////////////////////////////////////////////	
	function envioForm(){
		 var values = $("#form_consulta").serialize();

		 $.ajax({
                url: 'consultaDeducciones.php',
                type: "POST",
                data:values,
                success: showResponseNC,
				dataType: 'html'
            });
	
        return false;
				
		}
	
	function showResponseNC(data){
		 $("#destino").html(data);
		}
	
////////////////////////////////////////////////////////////////	
function carga_especifica(){
	
	$("#anno").change(function(){
							var val=$("#op").val();
							if(val=='especial'){ llena_select();}
							
							   });
	$("#conceptos").change(function(){
							var val=$("#op").val();
							if(val=='especial'){ llena_select();}
							
							   });
	$("#mes").change(function(){
							var val=$("#op").val();
							if(val=='especial'){ llena_select();}
							
							   });
	$("#op").change(function(){
							var val=$(this).val();
							if(val=='especial'){ llena_select();}
							
							   });
	
	}
//////////////////////////////////////////////////////
function llena_select(){
	var anno=$("#anno").val();
	var conceptos=$("#conceptos").val();
	var mes=$("#mes").val();
	$.post("consultaDeducciones.php",{op:"carga_especifica"},function(datos) { 
									         $("#especifica").html(datos) ;	 }); 
	}
	