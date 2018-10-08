var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos()
{ 
$("#div_bata").hide();
$("#laboratorio").bootstrapSwitch({
											
											onText:'SI',
											offText: 'NO', 
											onColor:'warning', 
											inverse:true,
											onSwitchChange: function(event, state) {
											if(state==true){

												$("#div_bata").show();
												$("#talla4").attr("required", true);

											}else{

												$("#div_bata").hide();
												$("#talla4").attr("required", false);
												$("#talla4 option").removeAttr("selected");
													}

												}
											});


$("#clic_planilla").hide();
$('#form_uniformes').submit(envioFormulario);
$.getJSON("censoUniformesAdm.php",{op:"DatosTrabajador"},function(datos){									
										
										  var arreglo = datos;
										 if(arreglo[0].filas== 1){
										$("#form_uniformes #talla1 option[value="+arreglo[0].talla1+"]").attr("selected","selected");
										$("#form_uniformes #talla2 option[value="+arreglo[0].talla2+"]").attr("selected","selected");
										$("#form_uniformes #talla3 option[value="+arreglo[0].talla3+"]").attr("selected","selected");
										
										$("#clic_planilla").show();
										$("#clic_planilla").click(function(){
											window.open('./censoUniformesPDF.php','Planilla','menubar=no,scrollbars=yes,toolbar=no,location=no,directories=no,resizable=no,top=0,left=0');
											});
										
										var bata=arreglo[0].talla4;

										if(bata.length > 0 ){
										$("#laboratorio").bootstrapSwitch('state',true);
										$("#div_bata").show();
										$("#talla4").attr("required", true);
										$("#form_uniformes #talla4 option[value="+arreglo[0].talla4+"]").attr("selected","selected");	
										}
											

											}

									


										});
}


////////////////////////////////////////////////////////////////////////	

function envioFormulario(){


  var values = $("#form_uniformes").serialize();

         $.ajax({
               url: 'censoUniformesAdm.php',
               type: "POST",
               data:values,
			   dataType: "json",
               beforeSend: antesUniformes,
               success: despuesUniformes
           });
       
       return false;
	 }

function antesUniformes() {
      
             
			   }  
function despuesUniformes(datos) {
 	alert(datos.message);	                    
 $.getJSON("censoUniformesAdm.php",{op:"DatosTrabajador"},function(datos){									
										
										  var arreglo = datos;
										 if(arreglo[0].filas== 1){
											//alert( arreglo[0].talla1);
										$("#form_uniformes #talla1 option[value="+arreglo[0].talla1+"]").attr("selected","selected");
										$("#form_uniformes #talla2 option[value="+arreglo[0].talla2+"]").attr("selected","selected");
										$("#form_uniformes #talla3 option[value="+arreglo[0].talla3+"]").attr("selected","selected");
										$("#clic_planilla").show();
										$("#clic_planilla").click(function(){
											window.open('./censoUniformesPDF.php','Planilla','menubar=no,scrollbars=yes,toolbar=no,location=no,directories=no,resizable=no,top=0,left=0');
											});	
											
										var bata=arreglo[0].talla4;
										if(bata.length > 0 ){
										$("#laboratorio").bootstrapSwitch('state',true);
										$("#div_bata").show();
										$("#talla4").attr("required", true);
										$("#form_uniformes #talla4 option[value="+arreglo[0].talla4+"]").attr("selected","selected");	
										}



											}
										});                            
                                   
               }			   