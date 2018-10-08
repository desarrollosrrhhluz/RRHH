var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){
$("#loading img").ajaxStart(function(){
   $(this).show();
   bloquear();
 }).ajaxStop(function(){
   $(this).hide();
   desbloquear();
 });

$("#cont_sitio").hide();
$("#form_fam #nivel_estudio").change(function () {
    if($(this).val()==3){	
	$("#cont_sitio").fadeIn();
		}else{
		$("#cont_sitio").hide();
			}
        });
$("#marco_hijos").hide();
$("#error").hide();
campos_inactivos();
camposInac_inclusion();
din_lista();
carga_grados();
select_desplegable();
des_estudio();
elementos_ocultos();
actualizadatos();
muestra_operaciones_principal();
$("#1").click(muestra_operaciones_principal);
$("#2").click(muestra_reactivacion_cedula);
$("#3").click(muestra_reporte_exclusion_cedula);
$("#4").click(muestra_reporte_inclusion_cedula);
$("#5").click(muestra_reporte_reactivacion_cedula);
$("#6").click(muestra_reporte_NominaGeneral);
$("#7").click(muestra_reporte_NominaRecalculo);
$("#8").click(muestra_reporte_NominaEliminar);
$("#9").click(muestra_manten_estatusSadia);
$("#10").click(muestra_manten_motivoRetiro);
$("#11").click(muestra_manten_motivoIncapacidad);
$("#12").click(muestra_mantn_sitioEstudio);
$("#13").click(muestra_actualizaEstatus);
$("#14").click(muestra_excluyeEdad);
$("#15").click(muestra_excluyeCE);
$("#btn_cancelar_familiar").click(cancelar_familiar);
mascaras();
select_estatus();
}
//*************************************************
//*************************************************
function cancelar_familiar(){
	var validator = $("#form_fam").validate();
validator.resetForm();
campos_inactivos();
camposInac_inclusion();
$("#form_fam #fe_nac_fa").removeAttr("disabled");
$("#form_fam #op_familiar").attr("value","guarda_familiar");

	}
//***********************************************
function mascaras(){
	$("#fe_nac_fam").mask("99/99/9999");
	$("#d1").mask("99/99/9999");
	$("#d2").mask("99/99/9999");
	$("#d3").mask("99/99/9999");
	$("#cad_const_est").mask("99/99/9999");
	$("#cad_const_esp").mask("99/99/9999");
	$("#retiro_sidial").mask("99/99/9999");
	$("#fec_retiro").mask("99/99/9999");
	
	}
//*************************************************
function camposInac_inclusion(){
	
	$("#form_fam #motivo_retiro").attr("disabled","disabled");
	$("#form_fam #motivo_incapacidad").attr("disabled","disabled");
	$("#form_fam #cad_const_esp").attr("disabled","disabled");
	$("#form_fam #cad_const_est").attr("disabled","disabled");
	//$("#form_fam #retiro_sidial").attr("disabled","disabled");
	//$("#form_fam #fec_retiro").attr("disabled","disabled");
	
	}
//*************************************************
function elementos_ocultos(){

$("#reactivacion_cedula").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_nominaGeneral").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();

$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	

$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();

	}
//*************************************************
function actualizadatos(){
	$("#ce_trabajador").keypress(function (e) {
										 var sel=$(this).val();
										 var rel=$('#relacion').val();
										
										  if(e.which==13){
										 $.getJSON("aplicacionPrimaPorHijosV2.php",{op:"DatosTrabajador",id:sel, rel:rel},function(datos){									
										
										  var arreglo = datos;	
										    if(arreglo[0].filas==2){			
												
		new Boxy("<div align='justify'><b>Antes de comenzar debe seleccionar una opcion:</b>"+arreglo[0].html+"</div>",{title: "Personal con doble ubicacion", 
		fixed: false,
		closeable:true,
		closeText:'cerrar',
		modal:true
		});
		
		var options = { 
        target:        '#destino', 
        //beforeSubmit:  showRequestSE, 
       	success:       showResponseSE,  
        dataType:  'json', 
		resetForm: true       
  }; 
				$(".opciones_ubicaciones").ajaxForm(options);
				
											}

										  if(arreglo[0].filas==1){
										  $("#nombre_trabajador").attr("value", arreglo[0].NOMBRES);
										  $("#ubicacion").attr("value", arreglo[0].DE_UBICACION);
										  $("#tipoplargo").attr("value", arreglo[0].DE_TIPOPERSONAL);
										  $("#prima_sistema").attr("value", arreglo[0].cantidad);
										  $("#prima_sidial").attr("value", arreglo[0].sidial);
										  consultaInicialHijos();
										  buscaotropadre();
										  cancelar_familiar();
										  consulta_hijos_historicos();
										  $.post("aplicacionPrimaPorHijosV2.php",{op:"selectQuienCobra"},function(datos) { $("#quien_cobra").html(datos) ; } ); 
										
										$.post("aplicacionPrimaPorHijosV2.php",{op:"consultaHijoReporte", id:"X"},function(datos) { 
									      $("#hijos_exclusion").html(datos) ;
										  $("#generar_pdfX").click(generar_pdf_acciones_excluir);
										  });  
										  $.post("aplicacionPrimaPorHijosV2.php",{op:"consultaHijoReporte", id:"I"},function(datos) { 
												  $("#hijos_inclusion").html(datos) ; 										 
												  $("#generar_pdfI").click(generar_pdf_acciones);
												  }); 
										  $.post("aplicacionPrimaPorHijosV2.php",{op:"consultaHijoReporte", id:"R"},function(datos) { 
												  $("#hijos_reactivacion").html(datos) ; 										 
												  $("#generar_pdfR").click(generar_pdf_acciones_reactiva);
												  }); 
										 $.post("aplicacionPrimaPorHijosV2.php",{op:"consultaHijoReporte", id:"P"},function(datos) { 
												  $("#hijos_prestaciones").html(datos) ; 										 
												  $("#generar_pdfP").click(generar_pdf_prestaciones);
												  }); 
										   
										  }else{
										  $("#marco_hijos").fadeOut();
										  $("#nombre_trabajador").attr("value","");
										  $("#ubicacion").attr("value", "");
										  $("#tipoplargo").attr("value","");
										  $("#prima_sistema").attr("value","");
										  $("#prima_sidial").attr("value","");
										  }
										  
										}); 
										  }
									 });

	
	
	}

function showResponseSE(data){
	$('.close').trigger("click");
	jQuery.noticeAdd({
                        text: "Resuelto",
                        stay: false
                });	
	
	var indice=data.valor;

	 $.getJSON("aplicacionPrimaPorHijosV2.php",{op:"DatosTrabajador_doble",id:indice},function(datos){									
										
										  var arreglo = datos;	
										
										 // if(arreglo[0].filas==1){
										  $("#nombre_trabajador").attr("value", arreglo[0].NOMBRES);
										  $("#ubicacion").attr("value", arreglo[0].DE_UBICACION);
										  $("#tipoplargo").attr("value", arreglo[0].DE_TIPOPERSONAL);
										  $("#prima_sistema").attr("value", arreglo[0].cantidad);
										  $("#prima_sidial").attr("value", arreglo[0].sidial);
				                       
										  consultaInicialHijos();
										   buscaotropadre();
										   cancelar_familiar();
										  consulta_hijos_historicos();
										  $.post("aplicacionPrimaPorHijosV2.php",{op:"selectQuienCobra"},function(datos) { $("#quien_cobra").html(datos) ; } ); 
										  
										  $.post("aplicacionPrimaPorHijosV2.php",{op:"consultaHijoReporte", id:"X"},function(datos) { 
									      $("#hijos_exclusion").html(datos) ;
										  $("#generar_pdfX").click(generar_pdf_acciones_excluir);
								
										  }); 
										   $.post("aplicacionPrimaPorHijosV2.php",{op:"consultaHijoReporte", id:"R"},function(datos) { 
									      $("#hijos_reactivacion").html(datos) ;
										  $("#generar_pdfR").click(generar_pdf_acciones_reactiva);
								
										  }); 
										  
										   $.post("aplicacionPrimaPorHijosV2.php",{op:"consultaHijoReporte", id:"I"},function(datos) { 
												  $("#hijos_inclusion").html(datos) ; 										 
												  $("#generar_pdfI").click(generar_pdf_acciones);
												  }); 
										     $.post("aplicacionPrimaPorHijosV2.php",{op:"consultaHijoReporte", id:"P"},function(datos) { 
												  $("#hijos_prestaciones").html(datos) ; 										 
												  $("#generar_pdfP").click(generar_pdf_prestaciones);
												  }); 
											
											
											
										/*  }else{
										  $("#marco_hijos").fadeOut();
										  $("#nombre_trabajador").attr("value","");
										  $("#ubicacion").attr("value", "");
										  $("#tipoplargo").attr("value","");
										  $("#prima_sistema").attr("value","");
										  $("#prima_sidial").attr("value","");
										  }*/
										  
										}); 
	
	
	}

//*************************************************
function buscaotropadre(){
	$("#ce_otro_p").change(function () {
										 var sel=$(this).val();
										 $.getJSON("aplicacionPrimaPorHijosV2.php",{op:"agregaOptionQuienCobra",id:sel},function(datos){									
										  var arreglo = datos;	
										  if(arreglo[0].filas<1){
										 $("#quien_cobra").find("option[id='option2']").remove();
										  }else{
				$("#quien_cobra").find("option[id='option2']").remove();
				 $('#quien_cobra').append('<option id="option2" value="'+arreglo[0].CE_TRABAJADOR+'">'+arreglo[0].CE_TRABAJADOR+' - '+arreglo[0].NOMBRES+'</option>');
										  
											 }
										}); 
									 });
	}

	
//*************************************************
function consultaInicialHijos(){
	 $.post("aplicacionPrimaPorHijosV2.php",{op:"consultaInicialHijos"},function(datos) { 
									      $("#contenedor_hijos").html(datos) ;
										  $("#marco_hijos").fadeIn();
										
										  $(".clic_editar").click(editaFamSel);

											 }); 	
	}
//*************************************************
function din_lista(){
	$("#form_fam #condicion_fam").change(function () {
    if($(this).val()!=2){	
		$("#form_fam #motivo_incapacidad").rules("remove");
		$("#form_fam #cad_const_esp").rules("remove");
		$("#form_fam #cad_const_esp").attr("disabled","disabled");
		$("#form_fam #motivo_incapacidad").attr("disabled","disabled");
		}else{
		$("#form_fam #motivo_incapacidad").removeAttr("disabled");
		$("#form_fam #cad_const_esp").removeAttr("disabled");
		$("#form_fam #cad_const_esp").rules("add", {required:true });
		$("#form_fam #motivo_incapacidad").rules("add", {required:true });
			}
        });
	
	}
//*************************************************
function actuliza_primaSistema(){
	$.getJSON("aplicacionPrimaPorHijosV2.php",{op:"actuliza_primaSistema"},function(datos){
																				 var arreglo = datos;	
																				 $("#prima_sistema").attr("value", arreglo[0].cantidad); });
	}
//*************************************************
function carga_grados(){
	$("#nivel_estudio").change(function(){
		$.post("./includes/searchGrado.php",{ id:$(this).val() },function(data){$("#grado_estudio").html(data);})	});	
	}
//*************************************************
function select_desplegable(){
	$.post("includes/searchSitioEstudio.php",function(datos) { $("#sitio_estudio").html(datos) ; } ); 
	$.post("includes/searchMotivoRetiro.php",function(datos) { $("#motivo_retiro").html(datos) ; } ); 	
	}
//*************************************************
function campos_inactivos(){
	$("#form_fam #nivel_estudio").attr("disabled","disabled");
	$("#form_fam #sitio_estudio").attr("disabled","disabled");
	$("#form_fam #regimen").attr("disabled","disabled");
	$("#form_fam #grado_estudio").attr("disabled","disabled");
	}
//*************************************************	
function des_estudio(){
	$("#form_fam #estudia").change(function () {
    if($(this).val()!=1){
	campos_inactivos();
	$("#form_fam #nivel_estudio").rules("remove");
    $("#form_fam #sitio_estudio").rules("remove");
    $("#form_fam #regimen").rules("remove");
    $("#form_fam #grado_estudio").rules("remove");
	$("#form_fam #cad_const_est").attr("disabled","disabled");
		}else{
	$("#form_fam #nivel_estudio").rules("add", {required:true });
	
	$("#form_fam #regimen").rules("add", {required:true });
	$("#form_fam #grado_estudio").rules("add", {required:true, number:true });
	$("#form_fam #nivel_estudio").removeAttr("disabled");
	$("#form_fam #sitio_estudio").removeAttr("disabled");
	$("#form_fam #regimen").removeAttr("disabled");
	$("#form_fam #grado_estudio").removeAttr("disabled");
	$("#form_fam #cad_const_est").removeAttr("disabled");
	$("#form_fam #cad_const_est").rules("add", {required:true});
			}
        });
	}
//*****************************************************
function select_estatus(){
	$("#form_fam #estatus_prima").change(function () {
    if($(this).val()!=1){
	$("#form_fam #motivo_retiro").removeAttr("disabled");
	//$("#form_fam #retiro_sidial").removeAttr("disabled");
	//$("#form_fam #fec_retiro").removeAttr("disabled");
	$("#form_fam #motivo_retiro").rules("add", {required:true });
	$("#form_fam #retiro_sidial").rules("add", {required:true });
	$("#form_fam #fec_retiro").rules("add", {required:true });
		}else{
	$("#form_fam #motivo_retiro").attr("disabled","disabled");
	//$("#form_fam #retiro_sidial").attr("disabled","disabled");
	//$("#form_fam #fec_retiro").attr("disabled","disabled");
	$("#form_fam #motivo_retiro").rules("remove");
	$("#form_fam #retiro_sidial").rules("remove");
	$("#form_fam #fec_retiro").rules("remove");
			}
        });
	}

	
//*****************************************************
//******************operaciones con el menu principal
//*************operaciones*****************************
function muestra_operaciones_principal(){
	var options = { 
        target:        '#destino', 
        success:       showResponseDP,  
        dataType:  'json', 
		resetForm: true       
  }; 
  
	  $('#form_fam').validate({
			messages: {
						nombres_fam: {required: 'Debe escribir apellidos y nombres separados por coma(,)'},	
						sexo_fam: { required: 'Debe seleccionar el sexo del familiar'},
						motivo_incapacidad: { required: 'Debe seleccionar un motivo de incapacidad'},
						condicion_fam: { required:'Debe seleccionar la condicion personal de familiar'},
						estudia: { required:'Debe indicar si su familiar se encuentra estudiando'},
						fe_nac_fam: { required: 'Ingrese la fecha de nacimiento', date:'Escriba una fecha utilizando el formato dd/mm/aaaa'},
						cad_const_est: { required: 'Ingrese la fecha de caducidad de constancia de estudio', date:'Escriba una fecha utilizando el formato dd/mm/aaaa'},
						sitio_estudio: { required: 'Seleccione sitio de estudio'},
		ce_otro_p: { required: 'Escriba la cedula del otro progenitor del familiar', number:'Escriba solo numeros',  minlength:'escriba un numero de cédula válido'},	
						nivel_estudio: { required: 'Seleccione un nivel de estudio'},
						regimen: { required: 'Seleccione el regimen educativa bajo el cual estudia'},
						grado_estudio: { required: 'Seleccione el grado, semestre o trimestre que cursa su familiar', number:'Escriba solo numeros'},	
					ce_familiar: {required:'Ingrese el numero de cedula del familiar', number:'Escriba solo numeros' }
						
							 },
			errorLabelContainer: $("#error"),
		    wrapper: 'li',							 
				rules: {
						nombres_fam: {required: true},	
						sexo_fam: { required: true},
						condicion_fam: { required: true},
						estudia: { required: true},
						fe_nac_fam: { required: true},
						ce_otro_p: { required: true, number:true, minlength: 6},	
						ce_familiar: {required: true,number:true }
						
							 },//end rules							   
				submitHandler: function(form) {
				$('#form_fam').ajaxSubmit(options);
				}
				}); 
$("#titulo_seccion").html("Operaciones generales");
$("#datos_trabajador").show();
$("#cuerpo_principal").show();
$("#reactivacion_cedula").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_nominaGeneral").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();

$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();
$("#error").hide();
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();
	if($("#form_fam #nivel_estudio").val()==3){$("#cont_sitio").show();$("#sitio_estudio").rules("add", {required:true });}else{$("#cont_sitio").hide();$("#nivel_estudio").rules("remove");
		$("#sitio_estudio").rules("remove");
		$("#regimen").rules("remove");
		$("#grado_estudio").rules("remove");}
	}
//***************************************************
function showResponseDP(data){
	jQuery.noticeAdd({
                        text: data.message,
                        stay: false
                });	
	consultaInicialHijos();
	cancelar_familiar();
	actuliza_primaSistema();
	}
//****************************************************
function editaFamSel(){
	var  sel = $(this).attr("id");
	
	$.getJSON("aplicacionPrimaPorHijosV2.php",{op:"query_jsonFamiliar",id:sel},function(datos){
										 									
										    var arreglo = datos;	
										    var niv=arreglo[0].nivel;
										    $.post("./includes/searchGrado.php",{ id:niv  },function(data){$("#grado_estudio").html(data);
											$("#form_fam #grado_estudio").attr("value",arreglo[0].grad_sem_trim); });
											$("#form_fam #ce_familiar").attr("value",arreglo[0].ce_familiar);
											$("#form_fam #nombres_fam").attr("value",arreglo[0].nombres);
										    $("#form_fam #sexo_fam").attr("value",arreglo[0].sexo);
											$("#form_fam #condicion_fam").attr("value",arreglo[0].condicion_personal);
											$("#form_fam #ce_otro_p").attr("value",arreglo[0].ce_otro_padre);
											$("#form_fam #nivel_estudio").attr("value",arreglo[0].nivel);
											$("#form_fam #estudia").attr("value",arreglo[0].activo_estudio);
											if($("#form_fam #nivel_estudio").val()==3){
											$("#cont_sitio").show();
											$("#sitio_estudio").rules("add", {required:true });
											}else{
												$("#cont_sitio").hide();
												$("#sitio_estudio").rules("remove");
		
												}
		
										 var otrop=$("#form_fam #ce_otro_p").val();
										 $.getJSON("aplicacionPrimaPorHijosV2.php",{op:"agregaOptionQuienCobra",id:otrop},function(datos){									
										  var array = datos;	
										  if(array[0].filas<1){
										 $("#quien_cobra").find("option[id='option2']").remove();
										 $("#form_fam #quien_cobra").attr("value",arreglo[0].cobra_prima);
										  }else{
				$("#quien_cobra").find("option[id='option2']").remove();
				 $('#quien_cobra').append('<option id="option2" value="'+array[0].cedula+'">'+array[0].cedula+' - '+array[0].NOMBRES+'</option>');
										  $("#form_fam #quien_cobra").attr("value",arreglo[0].cobra_prima);
											 }
											 
										}); 
											
								
									
											if(arreglo[0].condicion_personal==1){
											$("#form_fam #motivo_incapacidad").attr("disabled","disabled");
											$("#form_fam #cad_const_esp").attr("disabled","disabled");
											
												}else{
											$("#form_fam #motivo_incapacidad").removeAttr("disabled");
											$("#form_fam #cad_const_esp").removeAttr("disabled");
											$("#form_fam #motivo_incapacidad").attr("value",arreglo[0].motivo_incapacidad);
											
												}
											
											/*if(arreglo[0].nuevo==0){
											$("#form_fam #fe_nac_fam").attr("disabled","disabled");
												}else{*/
											$("#form_fam #fe_nac_fam").removeAttr("disabled");
												//}
											if(arreglo[0].activo_estudio==2){
											campos_inactivos();
											$("#form_fam #nivel_estudio").rules("remove");
											$("#form_fam #sitio_estudio").rules("remove");
											$("#form_fam #regimen").rules("remove");
											$("#form_fam #grado_estudio").rules("remove");
											$("#form_fam #cad_const_est").attr("disabled","disabled");
												}else{
											$("#form_fam #nivel_estudio").rules("add", {required:true });
		
											$("#form_fam #regimen").rules("add", {required:true });
											$("#form_fam #grado_estudio").rules("add", {required:true, number:true });
											$("#form_fam #sitio_estudio").removeAttr("disabled");
											$("#form_fam #nivel_estudio").removeAttr("disabled");
											$("#form_fam #regimen").removeAttr("disabled");
											$("#form_fam #grado_estudio").removeAttr("disabled");
											$("#form_fam #cad_const_est").removeAttr("disabled");
											$("#form_fam #cad_const_est").rules("add", {required:true});
												}
											$("#form_fam #fe_nac_fam").attr("value",arreglo[0].fe_nacimiento);
											$("#form_fam #sitio_estudio").attr("value",arreglo[0].sitio_estudio);
											$("#form_fam #regimen").attr("value",arreglo[0].regimen_educativo);
											$("#form_fam #grado_estudio").attr("value",arreglo[0].grad_sem_trim);	
											$("#form_fam #cad_const_est").attr("value",arreglo[0].fe_cad_ce);	
											$("#form_fam #cad_const_esp").attr("value", arreglo[0].fecha_esp );
											
											$("#form_fam #estatus_prima").removeAttr("disabled");
											$("#form_fam #motivo_retiro").removeAttr("disabled");
											$("#form_fam #retiro_sidial").removeAttr("disabled");
											$("#form_fam #fec_retiro").removeAttr("disabled");
											
											if(arreglo[0].estatus_prima!=1){
											$("#form_fam #motivo_retiro").removeAttr("disabled");
											$("#form_fam #retiro_sidial").removeAttr("disabled");
											$("#form_fam #fec_retiro").removeAttr("disabled");
											$("#form_fam #motivo_retiro").rules("add", {required:true });
											$("#form_fam #retiro_sidial").rules("add", {required:true });
											$("#form_fam #fec_retiro").rules("add", {required:true });
												}else{
											$("#form_fam #motivo_retiro").attr("disabled","disabled");
											//$("#form_fam #retiro_sidial").attr("disabled","disabled");
											//$("#form_fam #fec_retiro").attr("disabled","disabled");
											$("#form_fam #motivo_retiro").rules("remove");
											$("#form_fam #retiro_sidial").rules("remove");
											$("#form_fam #fec_retiro").rules("remove");
													}								
											$("#form_fam #estatus_prima").attr("value",arreglo[0].estatus_prima);
											$("#form_fam #motivo_retiro").attr("value",arreglo[0].motivo_retiro);
											$("#form_fam #retiro_sidial").attr("value",arreglo[0].fe_retiro_sidial);
											$("#form_fam #fec_retiro").attr("value",arreglo[0].fe_retiro);
											$("#form_fam #d1").attr("value", arreglo[0].d1);
											$("#form_fam #d2").attr("value", arreglo[0].d2);
											$("#form_fam #d3").attr("value", arreglo[0].d3);
											$("#form_fam #observaciones_prima").attr("value", arreglo[0].observaciones);
											$("#form_fam #id_fo").attr("value",sel);
											$("#form_fam #op_familiar").attr("value","UpdateFamiliarPrima");
										}); 
	
	}
	
//****************************************************
//*********A C C I O N E S   D E L   M E N U  ********

function muestra_reactivacion_cedula(){
$("#titulo_seccion").html("Reactivacion desde el historial");
$("#error").hide();
$("#datos_trabajador").show();
$("#reactivacion_cedula").fadeIn();	
$("#cuerpo_principal").hide();
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_nominaGeneral").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();

	}
//***************************************************
//*************inicio reportes***********************
function muestra_reporte_exclusion_cedula(){
$("#titulo_seccion").html("Reporte Exclusion");
$("#datos_trabajador").show();
$("#error").hide();
$("#reporte_exclusion").show();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_nominaGeneral").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();
	}
	
//****************************************************
function muestra_reporte_inclusion_cedula(){
$("#titulo_seccion").html("Reporte inclusion");
$("#datos_trabajador").show();
$("#error").hide();
$("#reporte_inclusion").show();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_nominaGeneral").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();
	
	}
//***************************************************
function muestra_reporte_reactivacion_cedula(){
	$("#error").hide();
	$("#titulo_seccion").html("Reporte Reactivacion");
	$("#datos_trabajador").show();
$("#reporte_reactivacion").show();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaGeneral").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();

	}
//*****************************************************

function muestra_reporte_NominaGeneral(){
	$("#error").hide();
	$("#datos_trabajador").show();
	 
$("#reporte_nominaGeneral").show();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();	
	}
//*****************************************************

function muestra_reporte_NominaRecalculo(){
	$("#error").hide();
	$("#datos_trabajador").show();
$("#reporte_nominaGeneral").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").show();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();	
	
	}
//********************************************************
function muestra_reporte_NominaEliminar(){
	$("#error").hide();
	$("#datos_trabajador").show();
$("#reporte_nominaGeneral").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").show();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();	
	
	}
//*******fin reportes*************************************
//********************************************************
//************inicio mantenimiento************************
function muestra_manten_estatusSadia(){
	
	$("#datos_trabajador").hide();
	$("#error").hide();
$("#reporte_nominaGeneral").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").show();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();	
	
	
	}
function muestra_manten_motivoRetiro(){
	$("#datos_trabajador").hide();
	$("#error").hide();
$("#reporte_nominaGeneral").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").show();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();	
	}

function muestra_manten_motivoIncapacidad(){
	$("#datos_trabajador").hide();
	$("#error").hide();
$("#reporte_nominaGeneral").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").show();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();	
	}
function muestra_mantn_sitioEstudio(){
	$("#datos_trabajador").hide();
	$("#error").hide();
$("#reporte_nominaGeneral").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").show();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();	

	}

//********************************************************
//******************fin mantenimiento*********************
//******************inicio acciones***********************
function muestra_actualizaEstatus(){
	$("#datos_trabajador").hide();
	$("#error").hide();
$("#reporte_nominaGeneral").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").show();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();
	
	}
function muestra_excluyeEdad(){
	$("#datos_trabajador").hide();
	$("#error").hide();
	$("#reporte_nominaGeneral").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").show();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").hide();
$("#extraer_data").hide();
	}
function muestra_excluyeCE(){
	$("#datos_trabajador").hide();
	$("#error").hide();
	$("#reporte_nominaGeneral").hide();	
$("#reporte_reactivacion").hide();	
$("#reporte_inclusion").hide();	
$("#reporte_exclusion").hide();
$("#cuerpo_principal").hide();
$("#reactivacion_cedula").hide();	
$("#reporte_nominaRecalculo").hide();	
$("#reporte_nominaEliminar").hide();	
$("#mantenimiento_estatus_sadia").hide();	
$("#mantenimiento_motivo_retiro").hide();	
$("#mantenimiento_motivo_incapacidad").hide();	
$("#mantenimiento_sitio_estudio").hide();	
$("#excluye_edad").hide();	
$("#actualiza_estatus").hide();	
$("#excluye_ce_caduca").show();
$("#extraer_data").hide();

	}

//**********F I N   A C C I O N E S ******************
//********************************************************
//**************R E P O R T E S*************************

//***********************************************************	
//***************************************************************
function generar_pdf_acciones(){
	
	var arrayReporte = new Array();
	var array= new Array();
	$(".I").each(function(i){
	var  sel = $(this).attr("name"); 							 
	if($("#I"+sel).is(':checked')) {
    // var  sel = $(this).attr("id"); 
	arrayReporte[i]=sel; 
	}
	
}); 
 var cadena = arrayReporte.join(',');
 
	if(cadena!=""){
		$.getJSON("aplicacionPrimaPorHijosV2.php",{op:"enviaArreglo",arreglo:cadena},function(datos){
										 											
										  var arreglo = datos;
										 var valor= $("#reportes_varios #accion_oculta").val();
										 window.open('reportePrimaHijoPDFV2.php?accion=1&arreglo='+arreglo[0].resultado,'_blank');
										}); 
		}
	}
//***********************************************************		
//*********************************************************	
function generar_pdf_acciones_excluir(){
	var arrayReporte = new Array();
	var array= new Array();
	$(".X").each(function(i){
	var  sel = $(this).attr("name"); 							 
	if($("#X"+sel).is(':checked')) {
    // var  sel = $(this).attr("id"); 
	arrayReporte[i]=sel; 
	}
}); 
 var cadena = arrayReporte.join(',');
	if(cadena!=""){
		$.getJSON("aplicacionPrimaPorHijosV2.php",{op:"enviaArreglo",arreglo:cadena},function(datos){											
										  var arreglo = datos;
										 window.open('reportePrimaHijoPDFV2.php?accion=0&arreglo='+arreglo[0].resultado,'_blank');
										}); 
		
		}
	}
	
	
function generar_pdf_acciones_reactiva(){
	var arrayReporte = new Array();
	var array= new Array();
	$(".R").each(function(i){
	var  sel = $(this).attr("name"); 							 
	if($("#R"+sel).is(':checked')) {
    // var  sel = $(this).attr("id"); 
	arrayReporte[i]=sel; 
	}
}); 
 var cadena = arrayReporte.join(',');
	if(cadena!=""){
		$.getJSON("aplicacionPrimaPorHijosV2.php",{op:"enviaArreglo",arreglo:cadena},function(datos){											
										  var arreglo = datos;
										 window.open('reportePrimaHijoPDFV2.php?accion=2&arreglo='+arreglo[0].resultado,'_blank');
										}); 
		
		}
	
	}
	
function generar_pdf_prestaciones(){
	
	var arrayReporte = new Array();
	var array= new Array();
	$(".P").each(function(i){
	var  sel = $(this).attr("name"); 							 
	if($("#P"+sel).is(':checked')) {
    // var  sel = $(this).attr("id"); 
	arrayReporte[i]=sel; 
	//alert(arrayReporte[i]);
	}
	
}); 
	//var  cadena="";	
//	if(arrayReporte.length>0){
var cadena = arrayReporte.join(',');
/*	}else{
		var  cadena="";
		}*/
	if(cadena!=""){
		$.getJSON("aplicacionPrimaPorHijosV2.php",{op:"enviaArreglo",arreglo:cadena},function(datos){											
										  var arreglo = datos;
										 window.open('reportePrimaHijoPresPDFV2.php?accion=2&arreglo='+arreglo[0].resultado,'_blank');
										}); 
		
		}else{
			$.getJSON("aplicacionPrimaPorHijosV2.php",{op:"enviaArreglo",arreglo:'0'},function(datos){											
										  var arreglo = datos;
										 window.open('reportePrimaHijoPresPDFV2.php?accion=2&arreglo='+arreglo[0].resultado,'_blank');
										}); 
			}
	
	}	
//***********F I N    R E P O R T E S ***********************	
//***********************************************************
//*************I N I C I O   R E A C T I V A C I O N ********
function consulta_hijos_historicos(){
	 $.post("aplicacionPrimaPorHijosV2.php",{op:"consultaHijosHistoricos"},function(datos) { 
									      $("#contenedor_hijos_historial").html(datos) ;
										  $(".reactiva").click(muestraFormReactiva);
										  $(".examina_historico").click(muestraInforHistorico);

											 }); 	
	
	}

function muestraFormReactiva(){
	
	var  sel = $(this).attr("id");
	
	$.getJSON("aplicacionPrimaPorHijosV2.php",{op:"query_jsonFamiliarHistorico",id:sel},function(datos){
										 									
										    var arreglo = datos;	
										  
										 
											
											$("#form_fam #ce_familiar").attr("value",arreglo[0].ce_familiar);
											$("#form_fam #nombres_fam").attr("value",arreglo[0].nombres);
										    $("#form_fam #sexo_fam").attr("value","");
											$("#form_fam #condicion_fam").attr("value","");
											$("#form_fam #ce_otro_p").attr("value","");
											$("#form_fam #nivel_estudio").attr("value","");
											$("#form_fam #estudia").attr("value","");
											
										 var otrop=$("#form_fam #ce_otro_p").val();
										 $.getJSON("aplicacionPrimaPorHijosV2.php",{op:"agregaOptionQuienCobra",id:otrop},function(datos){									
										  var array = datos;	
										  if(array[0].filas<1){
										 $("#quien_cobra").find("option[id='option2']").remove();
										 $("#form_fam #quien_cobra").attr("value",arreglo[0].cobra_prima);
										  }else{
				$("#quien_cobra").find("option[id='option2']").remove();
				 $('#quien_cobra').append('<option id="option2" value="'+array[0].cedula+'">'+array[0].cedula+' - '+array[0].NOMBRES+'</option>');
										  $("#form_fam #quien_cobra").attr("value",arreglo[0].cobra_prima);
											 }
											 
										}); 
											
											
									
											if(arreglo[0].condicion_personal==1){
											$("#form_fam #motivo_incapacidad").attr("disabled","disabled");
											$("#form_fam #cad_const_esp").attr("disabled","disabled");
											
												}else{
											$("#form_fam #motivo_incapacidad").removeAttr("disabled");
											$("#form_fam #cad_const_esp").removeAttr("disabled");
											$("#form_fam #motivo_incapacidad").attr("value","");
											
												}
											
											if(arreglo[0].nuevo==0){
											$("#form_fam #fe_nac_fam").attr("disabled","disabled");
												}else{
											$("#form_fam #fe_nac_fam").removeAttr("disabled");
												}
											if(arreglo[0].activo_estudio==2){
											campos_inactivos();
											$("#form_fam #nivel_estudio").rules("remove");
											$("#form_fam #sitio_estudio").rules("remove");
											$("#form_fam #regimen").rules("remove");
											$("#form_fam #grado_estudio").rules("remove");
											$("#form_fam #cad_const_est").attr("disabled","disabled");
												}else{
											$("#form_fam #nivel_estudio").rules("add", {required:true });
											$("#form_fam #sitio_estudio").rules("add", {required:true });
											$("#form_fam #regimen").rules("add", {required:true });
											$("#form_fam #grado_estudio").rules("add", {required:true, number:true });
											$("#form_fam #nivel_estudio").removeAttr("disabled");
											$("#form_fam #sitio_estudio").removeAttr("disabled");
											$("#form_fam #regimen").removeAttr("disabled");
											$("#form_fam #grado_estudio").removeAttr("disabled");
											$("#form_fam #cad_const_est").removeAttr("disabled");
											$("#form_fam #cad_const_est").rules("add", {required:true});
												}
											$("#form_fam #fe_nac_fam").attr("value",arreglo[0].fe_nacimiento);
											$("#form_fam #sitio_estudio").attr("value","");
											$("#form_fam #regimen").attr("value","");
											$("#form_fam #grado_estudio").attr("value","");	
											$("#form_fam #cad_const_est").attr("value",arreglo[0].fe_ce);	
											$("#form_fam #cad_const_esp").attr("value", arreglo[0].fe_esp );
											
											$("#form_fam #estatus_prima").removeAttr("disabled");
											$("#form_fam #motivo_retiro").removeAttr("disabled");
											$("#form_fam #retiro_sidial").removeAttr("disabled");
											$("#form_fam #fec_retiro").removeAttr("disabled");
											
											if(arreglo[0].estatus_prima!=1){
											$("#form_fam #motivo_retiro").removeAttr("disabled");
											$("#form_fam #retiro_sidial").removeAttr("disabled");
											$("#form_fam #fec_retiro").removeAttr("disabled");
											$("#form_fam #motivo_retiro").rules("add", {required:true });
											$("#form_fam #retiro_sidial").rules("add", {required:true });
											$("#form_fam #fec_retiro").rules("add", {required:true });
												}else{
											$("#form_fam #motivo_retiro").attr("disabled","disabled");
											$("#form_fam #retiro_sidial").attr("disabled","disabled");
											$("#form_fam #fec_retiro").attr("disabled","disabled");
											$("#form_fam #motivo_retiro").rules("remove");
											$("#form_fam #retiro_sidial").rules("remove");
											$("#form_fam #fec_retiro").rules("remove");
													}
																					
											$("#form_fam #estatus_prima").attr("value",arreglo[0].estatus);
											$("#form_fam #motivo_retiro").attr("value","");
											$("#form_fam #retiro_sidial").attr("value",arreglo[0].fe_rs);
											$("#form_fam #fec_retiro").attr("value",arreglo[0].fe_retiro);
											$("#form_fam #d1").attr("value", arreglo[0].f1);
											$("#form_fam #d2").attr("value", arreglo[0].f2);
											$("#form_fam #d3").attr("value", arreglo[0].f3);
											$("#form_fam #observaciones_prima").attr("value", arreglo[0].observaciones);
											 
										    muestra_operaciones_principal();
											
											
											
										
										
										}); 
	
	}
//***********************************************************
function muestraInforHistorico(){
	var  sel = $(this).attr("id");
     $.post("aplicacionPrimaPorHijosV2.php",{ op:"historia_hijo", id:sel},function(data){$("#info_hist").html(data);	});
	}
//*************F I N   R E A C T I V A C I O N **************
//***********************************************************
//*******************************************************
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
