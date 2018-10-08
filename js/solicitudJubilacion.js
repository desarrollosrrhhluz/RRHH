var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 
$("#error").hide();
$("#formulario").hide();
$("#informacion").hide();
verificaSolicitud();
$("#img_ayuda").click(function(){
	$('#myAyuda').modal('show');
	
	});
$("#form_jubilacion #ver_planilla").hide();
$("#form_jubilacion").submit(form_datos1);
$(".fila_encabezado").hide();
$(".fila_repetir").hide();
$("#servicios").change(function(){
						if($(this).val()=='s'){
							$(".fila_encabezado").fadeIn();
			$(".fila_repetir").fadeIn();
			$(".tipo").change(function(){
				 var sel=$(this).val();
				 var com=$(this);
					if(sel=='D'){	
           com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").remove();
		com.parent().parent().find("td").eq(0).append('<input name="organismo[]" value="PROGRAMA BECA EMPLEO" type="text" placeholder="Nombre del Organismo" placeholder="Nombre del Organismo"  id="organismo" class="form-control organismo"  />');
		  com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").attr("readonly",true);
		 //  alert(com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").val())
				}else{
		   if( com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").val()=='PROGRAMA BECA EMPLEO'){
						 com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").remove();
			             com.parent().parent().find("td").eq(0).append('<input name="organismo[]" type="text" placeholder="Nombre del Organismo" id="organismo" class="form-control organismo"   />');
						}else{
		   
					}
	  			}
	         });	
			
			}else{
				$(".fila_encabezado").hide();
			$(".fila_repetir").hide();
			$("input:text[name^='fe_desde']").attr("value","");
			$("input:text[name^='fe_hasta']").attr("value","");
			 $("#anos_ap").attr("value",'0');
			$("#meses_ap").attr("value",'0');
			$("#dias_ap").attr("value",'0');
			$("#anos_total").attr("value",parseInt($("#anoluz").val()));
			$("#meses_total").attr("value",parseInt($("#mesluz").val()));
			$("#dias_total").attr("value",parseInt($("#dialuz").val()));
			}					
								});
							
								
BuscaDatosPersonales2();
mascaras();
habilita_boton();
activa_enviar();

$("#destino").hide();

mascaras();
$("#form_asegurado #ver_planilla").attr("disabled","disabled");

	
}
function verificaSolicitud(){
	 $.post("solicitudJubilacion.php",{op:"HistoriaSolicitud"},function(datos){									
										
					  var arreglo = datos;
					if(datos==0){
						$("#formulario").show();
						$("#informacion").hide();
						}else{
						$("#formulario").hide();
						$("#informacion").html(datos);
						$("#informacion").show();	
						$("#ver_planilla").click(muestra_planilla);		
							}  
					
					  }); 
					
	
	}


//****************************************************************
function mascaras(){
	
	$("#anoluz").attr("value",$("#anos_luz").val());
	$("#mesluz").attr("value",$("#mes_luz").val());
	$("#dialuz").attr("value",$("#dia_luz").val());
	
	
	}
	
function BuscaDatosPersonales2(){
verificaSolicitud();
	res="";
	$.getJSON("solicitudJubilacion.php",{op:"DatoSolicitud"},function(datos){									
										
										
										  var arreglo = datos;
										//  alert(arreglo[0].admon_publica);
										res=arreglo[0].admon_publica;
					/*  if(arreglo[0].fecha_efectiva==null){
					  $("#form_jubilacion #fe_efectividad").attr("value","");
					  }else{*/
					  $("#form_jubilacion #fe_efectividad").attr("value",arreglo[0].fecha_efectiva);
					//  }
										   $("#form_jubilacion #servicios option[value='"+arreglo[0].admon_publica+"']").attr("selected",true); 
										  if(arreglo[0].admon_publica=='s'){
										
											
											$("#form_jubilacion #op_form").attr("value","update");
											}else{
												if(arreglo[0].admon_publica=='n'){ 
											
												$("#form_jubilacion #op_form").attr("value","update");
												}else{
												$("#form_jubilacion #servicios").removeAttr("selected");	
												
													}
												}
												
										 
										/******************************************************/	
										 if(arreglo[0].concurso=='s'){
											$("#form_jubilacion #concurso1").attr("checked","checked");
											$("#form_jubilacion #op_form").attr("value","update");
											}else{
												if(arreglo[0].concurso=='n'){ $("#form_jubilacion #concurso2").attr("checked","checked");
												$("#form_jubilacion #op_form").attr("value","update");
												}else{
												$("#form_jubilacion #concurso1").removeAttr("checked");	
												$("#form_jubilacion #concurso2").removeAttr("checked");	
													}
												}
										 if(arreglo[0].faov=='s'){
											$("#form_jubilacion #faov1").attr("checked","checked");
											$("#form_jubilacion #op_form").attr("value","update");
											}else{
												if(arreglo[0].faov=='n'){ $("#form_jubilacion #faov2").attr("checked","checked");
												$("#form_jubilacion #op_form").attr("value","update");
												}else{
												$("#form_jubilacion #faov1").removeAttr("checked");	
												$("#form_jubilacion #faov2").removeAttr("checked");	
													}
												}
														
										        $("#form_jubilacion #id_sol_oculto").attr("value",arreglo[0].id_solicitud);
		/*/////////////////////////////////////////////////////////////////////////////*/
					
						
											
		/*////////////////////////////////////////////////////////////////////////////*/
						}); 
	
			/***************************************************/
				 $.post("solicitudJubilacion.php",{op:"buscaServicios"},function(datos){									
					  
						  //var arreglo = datos;
						  //alert(datos);
						  $("#tabla1").append(datos);
						 
						  calculatiempo2();
						  calculatiempo();
						  if(res=='s'){$(".fila_encabezado").show();$(".fila_repetir").show();}
						  if(res=='n'){$(".fila_encabezado").hide();$(".fila_repetir").hide(); }
						  if(res!='s'){$(".fila_encabezado").hide();$(".fila_repetir").hide();}	
						  $("#servicios").change(function(){
						  if($(this).val()=='s'){
						  $(".fila_encabezado").fadeIn();
						  $(".fila_repetir").fadeIn();
						  }else{
						  $(".fila_encabezado").hide();
						  $(".fila_repetir").hide();
						  $("#anos_ap").attr("value",'0');
						  $("#meses_ap").attr("value",'0');
						  $("#dias_ap").attr("value",'0');
						  $("#anos_total").attr("value",parseInt($("#anoluz").val()));
						  $("#meses_total").attr("value",parseInt($("#mesluz").val()));
						  $("#dias_total").attr("value",parseInt($("#dialuz").val()));
						    activa_enviar();
						  }					
						  });

		
									  
						$(".agregar_nuevo").click(function(){
						$("#tabla1").append(' <tr class="fila_repetir"><td><input name="organismo[]" type="text" id="organismo" class="form-control organismo" placeholder="Nombre del Organismo"   /></td><td><select name="tipo[]" id="tipo" class="form-control tipo"><option value="">--</option><option value="P">Admon Pública</option><option value="D">DIDSE</option></select></td> <td><input name="fe_desde[]" type="text" id="fe_desde"  class="form-control fecha" placeholder="00/00/0000"  /></td><td><input name="fe_hasta[]" type="text" id="fe_hasta"  class="form-control fecha" placeholder="00/00/0000"  /></td><td><select class="form-control" name="cobro[]" id="cobro"> <option value="s">Si</option> <option value="n">No</option></select></td><td><input name="monto[]" type="text" id="monto" size="8" class="form-control"   /></td><td> <img src="images/Gnome-Window-Close-32.png" width="32" height="32" class="eliminar_este" title="Eliminar esta Fila"></td></tr>');  mascaras(); 
					$(".tipo").change(function(){
				 var sel=$(this).val();
				 var com=$(this);
					if(sel=='D'){	
           com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").remove();
			com.parent().parent().find("td").eq(0).append('<input name="organismo[]" value="PROGRAMA BECA EMPLEO" type="text" placeholder="Nombre del Organismo"  id="organismo" class="form-control organismo"  />');
		  com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").attr("readonly",true);
		 //  alert(com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").val())
				}else{
					if( com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").val()=='PROGRAMA BECA EMPLEO'){
						 com.parent().parent().find("td").eq(0).find("input:text[name^='organismo']").remove();
			             com.parent().parent().find("td").eq(0).append('<input name="organismo[]" type="text" placeholder="Nombre del Organismo" id="organismo" class="form-control organismo"   />');
						}else{
		   
					}
		
		//	alert(com.parent().parent().find("td").eq(0).find("input:text").val());
	  			}
	         });	
						calculatiempo2();
						calculatiempo();
						$(".eliminar_este").click(function(){ $(this).parent().parent().remove(); calculatiempo2();   activa_enviar();	});			
								  });
  
									
										
										}); 	 

										
							//calculatiempo2();			

	/***********************************************************************************/
	
	 $.getJSON("solicitudJubilacion.php",{op:"DatosTrabajador"},function(datos){									
										
					  var arreglo = datos;
					   $("#form_jubilacion #direccion").val(arreglo[0].direccion); 
					  if(arreglo[0].telefono_celular==null){
					  $("#form_jubilacion #tel_celular").attr("value","");
					  }else{
					  $("#form_jubilacion #tel_celular").attr("value",arreglo[0].telefono_celular);
					  }
					  if(arreglo[0].telefono_local==null){
					  $("#form_jubilacion #tel_habitacion").attr("value","");
					  }else{
					  $("#form_jubilacion #tel_habitacion").attr("value",arreglo[0].telefono_local);
					  }
					  if(arreglo[0].email==null){
					  $("#form_jubilacion #correo").attr("value","");
					  }else{
					  $("#form_jubilacion #correo").attr("value",arreglo[0].email);
					  }
					  
					  
					 
					  }); 
					
					  
}
	
function form_datos1(){

 var values = $("#form_jubilacion").serialize();
		 $.ajax({
                url: 'solicitudJubilacion.php',
                type: "POST",
                data:values,
                beforeSend: showRequestDP
              //  success: showResponseDP,
				//dataType: 'json'
            });
        return false;
	}
/////////////////////////////////////////////////////////
function showRequestDP(data){
	
	$('#myModal').modal('show');
	$("#btn_comfirma").click(function(){
		////////////////////////////////////////
		$('#myModal').modal('hide');
		///////////////////////////////////////
		 if($("#servicios").val()=='s'){
									 $(".fila_repetir").each(function(e){
												  var organismo = $(this).find("td").eq(0).find("input:text").val();
												  var desde = $(this).find("td").eq(2).find("input").val();
												  var hasta = $(this).find("td").eq(3).find("input").val();
												  var cobro = $(this).find("td").eq(4).find("select").val();
												  var monto = $(this).find("td").eq(5).find("input").val();
												  cant=0;
												  if(isNaN(monto)){ 
												var textCont='El campo monto debe ser numerico, las posiciones decimales deben ir separadas por punto ejemplo: 3500.12';
												alert(textCont);
												 
												  band=1;
												  return false;
												  
												  }
												  if(organismo.length>1){ cant=cant+1;}
												  if(desde.length>1){ cant=cant+1;}
												  if(hasta.length>1){ cant=cant+1;}
												  if(cobro.length>0){ cant=cant+1;}
												  if(monto.length>0){ cant=cant+1;}
												  
												  if(cant<5){
													var textCont2='-Completar todos los campos relacionados a su actividad en otros organismos publicos';  
													alert(textCont2);  
										
												  band=1;
												  return false;
												  
												  }else{
												  band=0;
												  return true;
												  }
												  
												  
												  });
												  
												  if(band==1){ 
												  return false;  
												
												    }else{
														
											     var values = $("#form_jubilacion").serialize();
												 $.ajax({
														url: 'solicitudJubilacion.php?op=guardar',
														type: "POST",
														data:values,
														//beforeSend: showRequestDP
													    success: showResponseDP
														//dataType: 'json'
													});
							
												  return false; 
													  
													  }
												  
			}else{ 
											
											
												var values = $("#form_jubilacion").serialize();
												 $.ajax({
														url: 'solicitudJubilacion.php',
														type: "POST",
														data:values,
														//beforeSend: showRequestDP
													    success: showResponseDP,
														dataType: 'json'
													});
							
												  return false;
									
												  }	
											
		
		
		
		///////////////////////////////////////
		});

	
	

																																						   
							 		  
												
												 
							
return false;

	}

/////////////////////////////////////////////////////////
function showResponseDP(data){
	//alert(data);
	
	$(".fila_encabezado").remove();$(".fila_repetir").remove();
BuscaDatosPersonales2();
	//alert("Hola");

	/*BuscaDatosPersonales2();*/
	habilita_boton();
	verificaSolicitud();
	}
//*************************************************
function habilita_boton(){
	/* $.getJSON("solicitudJubilacion.php",{op:"consultaSol"},function(datos){									
										 
										  var arreglo = datos;	
										if(datos>0){
											$("#form_jubilacion #ver_planilla").show();
											$("#form_jubilacion #ver_planilla").removeAttr("disabled");
											$("#form_jubilacion #ver_planilla").click(muestra_planilla);
											}else{
												$("#form_jubilacion #ver_planilla").hide();
											$("#form_Jubilacion #ver_planilla").attr("disabled","disabled");	
												}
										
										}); */
	
	
	}

function activa_enviar(){

//alert($("#edad").val() +" "+$("#anos_luz").val()+" "+$("#mes_luz").val()+" "+$("#anos_total").val() );
var edad=parseInt($("#edad").val());

/*var mesluz=parseInt($("#mes_luz").val());
var anostotal=parseInt($("#anos_total").val());
var mesestotal=parseInt($("#meses_total").val());*/

if($("#mes_luz").val().length > 1){
var mesLuzAux=$("#mes_luz").val();
}else{
var mesLuzAux="0"+$("#mes_luz").val();
}
if($("#meses_total").val().length > 1){
var mesTotalAux=$("#meses_total").val();
}else{
var mesTotalAux="0"+$("#meses_total").val();
}




var cadena=$("#anos_luz").val()+"."+mesLuzAux;
var cadena2=$("#anos_total").val()+"."+mesTotalAux;
var anosluz=parseFloat(cadena);
var anostotal=parseFloat(cadena2);

annostotal=parseInt($("#anos_total").val());
mesestotal2=parseInt($("#meses_total").val());



		if( anostotal>=24.06){
			$("#btn_guardar").removeAttr("disabled");
				
		}else{
		if( edad >=60 && anosluz >=14.08 ){
		$("#btn_guardar").removeAttr("disabled");

		}else{
		   if(edad>=55 && anostotal>=19.08 ){
			   $("#btn_guardar").removeAttr("disabled");	
		}else{
				$("#btn_guardar").attr("disabled", "disabled");	
				}
			}
		
		}
	
}



//***************************************************************
function muestra_planilla(){
	/*alert("hola");
	$("#destino").html('<a href="http://www.servicios.luz.edu.ve/RRHH/14-02.php" target="_blank" id="enlace">sadasd</a>');
    $("#enlace").trigger("click");*/
	window.open('solicitudJubilacionPDF.php','_blank','toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
	
	}

function calculatiempo(){

	$("input:text[name^='fe_desde']").change(function(){
			var ano_ap=0;
 var mes_ap=0;
 var dia_ap=0;
				$(".fila_repetir").each(function(i){
											
										 var desde = $(this).find("td").eq(2).find("input").val();
										 var hasta = $(this).find("td").eq(3).find("input").val();		 
												 
					                     if(desde.length>5 && hasta.length>5 ){
										  var curday = hasta.substring(0,2);
										  var curmon = hasta.substring(3,5);
										  var curyear = hasta.substring(6,10);
										  var calday = desde.substring(0,2);
										  var calmon = desde.substring(3,5);
										  var calyear =desde.substring(6,10);
										 
										  var curd = new Date(curyear,curmon-1,curday);
										  var cald = new Date(calyear,calmon-1,calday);
										  
										  var diff =  Date.UTC(curyear,curmon,curday,0,0,0)
										  - Date.UTC(calyear,calmon,calday,0,0,0);
										  
										  var dife = datediff(curd,cald);
										  
										 // alert(dife[0]+" years, "+dife[1]+" months, and "+dife[2]+" days");
									 
										   ano_ap= ano_ap+ parseInt(dife[0]);
										  mes_ap=mes_ap+ parseInt(dife[1]);
										  dia_ap=dia_ap+ parseInt(dife[2]);
										  
										  $("#anos_ap").attr("value","");
										  $("#meses_ap").attr("value","");
										  $("#dias_ap").attr("value","");
										  $("#anos_ap").attr("value",ano_ap);
										  $("#meses_ap").attr("value",mes_ap);
										  $("#dias_ap").attr("value",dia_ap);
										  
										 var ano_total= ano_ap+parseInt($("#anoluz").val());
										 var mes_total= mes_ap+parseInt($("#mesluz").val());
										 var dias_total= dia_ap+parseInt($("#dialuz").val());
										 var total_dias=0;
										 var total_mes=0;
										 var total_mes2=0;
										 var total_ano=0;
										 if(dias_total>=30){ 
										 total_mes=mes_total+parseInt(dias_total/30);  total_dias=dias_total%30; 
										 }else{ total_mes=mes_total; total_dias=dias_total;  }
										 if(total_mes>=12){
											 total_ano=ano_total+parseInt(total_mes/12);  total_mes2=total_mes%12; 
											 
											 }else{total_ano=ano_total; total_mes2=total_mes;  }
										
										 
										  $("#anos_total").attr("value",total_ano);
										  $("#meses_total").attr("value",total_mes2);
										  $("#dias_total").attr("value",total_dias);
										 activa_enviar();										  
										  
									}
																
													});											  
													  
													  
										  
													  });
	

	
	$("input:text[name^='fe_hasta']").change(function(){
			var ano_ap=0;
 var mes_ap=0;
 var dia_ap=0;
				$(".fila_repetir").each(function(i){
											
										 var desde = $(this).find("td").eq(2).find("input").val();
										 var hasta = $(this).find("td").eq(3).find("input").val();		 
												 
					                     if(desde.length>5 && hasta.length>5 ){
										  var curday = hasta.substring(0,2);
										  var curmon = hasta.substring(3,5);
										  var curyear = hasta.substring(6,10);
										  var calday = desde.substring(0,2);
										  var calmon = desde.substring(3,5);
										  var calyear =desde.substring(6,10);
										 
										  var curd = new Date(curyear,curmon-1,curday);
										  var cald = new Date(calyear,calmon-1,calday);
										  
										  var diff =  Date.UTC(curyear,curmon,curday,0,0,0)
										  - Date.UTC(calyear,calmon,calday,0,0,0);
										  
										  var dife = datediff(curd,cald);
										  
										 // alert(dife[0]+" years, "+dife[1]+" months, and "+dife[2]+" days");
									 
										   ano_ap= ano_ap+ parseInt(dife[0]);
										  mes_ap=mes_ap+ parseInt(dife[1]);
										  dia_ap=dia_ap+ parseInt(dife[2]);
										  
										  $("#anos_ap").attr("value","");
										  $("#meses_ap").attr("value","");
										  $("#dias_ap").attr("value","");
										  $("#anos_ap").attr("value",ano_ap);
										  $("#meses_ap").attr("value",mes_ap);
										  $("#dias_ap").attr("value",dia_ap);
										  
										 var ano_total= ano_ap+parseInt($("#anoluz").val());
										 var mes_total= mes_ap+parseInt($("#mesluz").val());
										 var dias_total= dia_ap+parseInt($("#dialuz").val());
										 var total_dias=0;
										 var total_mes=0;
										 var total_mes2=0;
										 var total_ano=0;
										 if(dias_total>=30){ 
										 total_mes=mes_total+parseInt(dias_total/30);  total_dias=dias_total%30; 
										 }else{ total_mes=mes_total; total_dias=dias_total;  }
										 if(total_mes>=12){
											 total_ano=ano_total+parseInt(total_mes/12);  total_mes2=total_mes%12; 
											 
											 }else{total_ano=ano_total; total_mes2=total_mes;  }
										
										 
										  $("#anos_total").attr("value",total_ano);
										  $("#meses_total").attr("value",total_mes2);
										  $("#dias_total").attr("value",total_dias);
										 										  
										activa_enviar();  
									}
																
													});											  
													  
									  
													  });
	
	
	}

function calculatiempo2(){
var ano_ap=0;
 var mes_ap=0;
 var dia_ap=0;
				$(".fila_repetir").each(function(i){
											
										 var desde = $(this).find("td").eq(2).find("input").val();
										 var hasta = $(this).find("td").eq(3).find("input").val();		 
												 
					                     if(desde.length>5 && hasta.length>5 ){
										  var curday = hasta.substring(0,2);
										  var curmon = hasta.substring(3,5);
										  var curyear = hasta.substring(6,10);
										  var calday = desde.substring(0,2);
										  var calmon = desde.substring(3,5);
										  var calyear =desde.substring(6,10);
										 
										  var curd = new Date(curyear,curmon-1,curday);
										  var cald = new Date(calyear,calmon-1,calday);
										  
										  var diff =  Date.UTC(curyear,curmon,curday,0,0,0)
										  - Date.UTC(calyear,calmon,calday,0,0,0);
										  
										  var dife = datediff(curd,cald);
										  
										 // alert(dife[0]+" years, "+dife[1]+" months, and "+dife[2]+" days");
									 
										   ano_ap= ano_ap+ parseInt(dife[0]);
										  mes_ap=mes_ap+ parseInt(dife[1]);
										  dia_ap=dia_ap+ parseInt(dife[2]);
										  
										   $("#anos_ap").attr("value","");
										  $("#meses_ap").attr("value","");
										  $("#dias_ap").attr("value","");
										  $("#anos_ap").attr("value",ano_ap);
										  $("#meses_ap").attr("value",mes_ap);
										  $("#dias_ap").attr("value",dia_ap);
										  
										 var ano_total= ano_ap+parseInt($("#anoluz").val());
										 var mes_total= mes_ap+parseInt($("#mesluz").val());
										 var dias_total= dia_ap+parseInt($("#dialuz").val());
										 var total_dias=0;
										 var total_mes=0;
										 var total_mes2=0;
										 var total_ano=0;
										 if(dias_total>=30){ 
										 total_mes=mes_total+parseInt(dias_total/30);  total_dias=dias_total%30; 
										 }else{ total_mes=mes_total; total_dias=dias_total;  }
										 if(total_mes>=12){
											 total_ano=ano_total+parseInt(total_mes/12);  total_mes2=total_mes%12; 
											 
											 }else{total_ano=ano_total; total_mes2=total_mes;  }
										
										 
										  $("#anos_total").attr("value",total_ano);
										  $("#meses_total").attr("value",total_mes2);
										  $("#dias_total").attr("value",total_dias);
										
	
							  activa_enviar();
										  
									}
																
													});									  
													
	
	
	
	
	}



function datediff(date1, date2) {
    var y1 = date1.getFullYear(), m1 = date1.getMonth(), d1 = date1.getDate(),
	 y2 = date2.getFullYear(), m2 = date2.getMonth(), d2 = date2.getDate();

    if (d1 < d2) {
        m1--;
        d1 += DaysInMonth(y2, m2);
    }
    if (m1 < m2) {
        y1--;
        m1 += 12;
    }
    return [y1 - y2, m1 - m2, d1 - d2];
}
function DaysInMonth(Y, M) {
    with (new Date(Y, M, 1, 12)) {
        setDate(0);
        return getDate();
    }
}