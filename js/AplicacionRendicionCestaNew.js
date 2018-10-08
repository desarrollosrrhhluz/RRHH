var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 

$('#error').hide();
$('#cuerpo_encabezado').hide();
parametros_rendicion();
desactiva_opcion();
$('#cuerpo_encabezado').fadeIn();
$('#buscaPersonal').submit(envio_form);
}
function desactiva_opcion(){
	
	$.getJSON("AplicacionRendicionCestaNew.php",{op:"desactiva_opcion"},function(datos){									
										
										  var arreglo = datos;
										  if (arreglo[0].cadena.indexOf('DOC')!=-1) {
												$('#buscaPersonal #docente').attr("disabled","disabled");
												//$('#buscaPersonal #buscar_personal').attr("disabled","disabled");
$("#buscaPersonal #mensaje_docente").html("<a target='_blank'  href='AplicacionRendicionCestaPDF.php?xyz=1&sem="+arreglo[0].mes+"&onna="+arreglo[0].anno+"&cou="+arreglo[0].cou+"'>(ver resumen)</a>");
												}
												if (arreglo[0].cadena.indexOf('ADM')!=-1) {
												$('#buscaPersonal #administrativo').attr("disabled","disabled");
												//$('#buscaPersonal #buscar_personal').attr("disabled","disabled");
$("#buscaPersonal #mensaje_administrativo").html("<a target='_blank' href='AplicacionRendicionCestaPDF.php?xyz=2&sem="+arreglo[0].mes+"&onna="+arreglo[0].anno+"&cou="+arreglo[0].cou+"'>(ver resumen)</a>");}
												
										 if (arreglo[0].cadena.indexOf('OBR')!=-1) {
												$('#buscaPersonal #obrero').attr("disabled","disabled");
												$('#buscaPersonal #buscar_personal').attr("disabled","disabled");
												
$("#buscaPersonal #mensaje_obrero").html("<a  target='_blank' href='AplicacionRendicionCestaPDF.php?xyz=3&sem="+arreglo[0].mes+"&onna="+arreglo[0].anno+"&cou="+arreglo[0].cou+"'>(ver resumen)</a>");
												}
											if (arreglo[0].cadena.indexOf('OBR')!=-1 && arreglo[0].cadena.indexOf('ADM')!=-1 && arreglo[0].cadena.indexOf('DOC')!=-1){
												$('#buscaPersonal #buscar_personal').attr("disabled","disabled");
												}	else{
													$('#buscaPersonal #buscar_personal').removeAttr("disabled");
													}
										
										}); 
	
	}

function parametros_rendicion(){
 $.getJSON("AplicacionRendicionCestaNew.php",{op:"levanta_parametros"},function(datos){									
										
										  var arreglo = datos;	
										 if(datos.message==1){
											 
							 var array=["","Enero","Febrero","Marzo","Abril","Mayo","Junio", "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
											 var indice=parseInt(datos.mes);
											
											 $('#titulo').html('Rendici&oacute;n del Bono Alimentaci&oacute;n  '+array[indice]+' - '+datos.anno);
											 $('#cuerpo_encabezado').fadeIn();
											 }else{
														//$('#titulo').html('Rendici&oacute;n del Bono Alimentaci&oacute;n');
												$('#cuerpo_encabezado').html('<fieldset><legend><h2><img src="images/group.png" width="32" height="32" />Fuera de Proceso</h2></legend><span class="glyphicon glyphicon-exclamation-sign"></span> No esta dentro de un periodo de rendion activo</fieldset>');
												$('#cuerpo_encabezado').fadeIn();
												 }
									 
										}); 	
	
	}
function envio_form(){
	
	 var values = $("#buscaPersonal").serialize();

		 $.ajax({
                url: 'AplicacionRendicionCestaNew.php',
                type: "POST",
                data:values,
                beforeSend: showRequestNOP ,
                success: showResponseNOP,
				dataType: 'html'
            });
	
        return false;
	
	}
	
function  showRequestNOP(){
	$("#buscar_personal").attr("value","Cargando...");
	$("#buscaPersonal #buscar_personal").attr("disabled",true);
 /*jQuery.noticeAdd({
      text: "<div id='imagen'><img src='images/Gnome-Logviewer-32.png' /></div><div id='textn'><b>Listando resultados</b></div>",
                        stay: false
                });	*/
	}	
//******************************************
function  showResponseNOP(datos){
	$("#destino").html(datos);
/*	$(".inicia_variacion").tooltip({ position: "center right", offset: [-2, 10], effect: "fade", opacity: 0.7, tip: '.tooltip'});		
    $(".ver_conceptos").tooltip({ position: "center right", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });	*/
	
	
	$(".inicia_variacion").click(muestra_ventana);
	$("input:button[name^='rendir']").click(cierra_proceso);
	$(".ver_conceptos").click(ver_conceptos);
	
	$("#cancelar_rendicion").click(function(){$("#buscar_personal").removeAttr("disabled"); $("#destino").html(""); });
	$('#lista').tablePagination({ currPage : 1, 
              ignoreRows : $('tbody tr:odd', $('#menuTable2')),
              optionsForRows : [10,15,20,25,50,100,200],
              rowsPerPage : 40,
              firstArrow : (new Image()).src="app_images/sprevious.png",
              prevArrow : (new Image()).src="app_images/previous.png",
              lastArrow : (new Image()).src="app_images/snext.png",
              nextArrow : (new Image()).src="app_images/next.png"});
$("#buscar_personal").attr("value","Iniciar");
$("#buscaPersonal #buscar_personal").attr("disabled",true);
	}
	
//******************************************
function muestra_ventana(){

	
	var  sel = $(this).attr("id"); 	
	var id = $('.fila'+sel+'').find("td").eq(1).html();
	var cou = $('.fila'+sel+'').find("td").eq(2).html();
	var tip = $('.fila'+sel+'').find("td").eq(3).html();
	/*var tip = $('.fila'+sel+'').find("td").eq(3).html();
	var tip = $('.fila'+sel+'').find("td").eq(3).html();*/
	/*<option value='1'>Sumar</option>*/
	$("#cuerpo_variacion").html("");
	$("#cuerpo_variacion").html("<form id='form_variacion"+sel+"' action='AplicacionRendicionCestaNew.php' method='post' name='form_variacion' class='form_variacion' ><div class='row'><div class='col-lg-3'><label>Trabajador:</label></div><div class='col-lg-8'><input class='form-control' required name='problemas' value='"+id+"' type='text' id='problemas'/></div></div><br/><div class='row'><div class='col-lg-3'><label style='width:60px'>Accion:</label></div><div class='col-lg-8'><select required name='accion' id='accion' class='form-control'><option value=''>-Seleccione-</option><option value='0'>Restar</option></select></div></div><br/><div class='row'><div class='col-lg-3'><label style='width:60px'>Observacion:</label></div><div class='col-lg-8'><textarea required  class='form-control' name='solucion'  rows='2' id='solucion'></textarea></div></div><br><div class='row'><div class='col-lg-3'><label style='width:60px'>Seleccione dias:</label></div><div class='col-lg-7'><input class='form-control' size='35' required disabled='disabled'  name='cantidad2' type='text' id='cantidad2'  /><input class='form-control' size='35' required name='cantidad' type='hidden' id='cantidad'  /></div><div class='col-lg-1'><img src='images/calendario.png' width='16' id='f_btn1' name='f_btn1' height='13' /></div></div><br><div class='row'><div class='col-lg-3'><label style='width:60px'>Cantidad</label></div><div class='col-lg-4'><input class='form-control' disabled='disabled' required size='5' name='numero' type='text' id='numero' value='0'  /></div></div><br><div id='call'></div><br><br><div align='center'><input name='ce_trabajador' id='ce_trabajador' type='hidden' value='"+sel+"'/><input name='tip' id='tip' type='hidden' value='"+tip+"'/><input name='cou' id='cou' type='hidden' value='"+cou+"'/><input name='dias' id='dias' type='hidden' /><input name='op' id='op_solucion' type='hidden' value='guardar_variacion'/><input type='submit' name='guardar_variacion' class='btn btn-primary' id='guardar_variacion' value='Guardar'/> <input type='reset' name='cancelar' value='Cancelar' class='btn btn-default'/></div></form>");
	
	
	$("#form_variacion"+sel+"").submit(function(){
	var values=$("#form_variacion"+sel+"").serialize();
		 $.ajax({
                url: 'AplicacionRendicionCestaNew.php',
                type: "POST",
                data:values,
                beforeSend:showRequestSE,
                success: showResponseSE,
				dataType: 'json'
            });
	
      
		return false;
		});
		
		$('#ventanaVariacion').modal('show');
	/*																		
	 new Boxy("<form id='form_variacion"+sel+"' action='AplicacionRendicionCestaNew.php' method='post' name='form_variacion' class='form_variacion' ><label style='width:60px'>Trabajador:</label><input size='40' class='fif' name='problemas' value='"+id+"' type='text' id='problemas'/><br><label style='width:60px'>Accion:</label><select name='accion' id='accion'><option value=''>-Seleccione-</option><option value='0'>Restar</option></select><br><label style='width:60px'>Observacion:</label><textarea class='required' name='solucion' cols='30' rows='2' id='solucion'></textarea><br><label style='width:60px'>Seleccione dias:</label><input class='fif' size='35' disabled='disabled'  name='cantidad2' type='text' id='cantidad2'  /><input class='fif' size='35'  name='cantidad' type='hidden' id='cantidad'  /><img src='images/calendario.png' width='16' id='f_btn1' name='f_btn1' height='13' /><br><br><label style='width:60px'>Cantidad</label><input class='fif' disabled='disabled' size='5' name='numero' type='text' id='numero' value='0'  /><br><div id='call'></div><br><br><br><br><div id='error1' class='error'></div><div align='center'><input name='ce_trabajador' id='ce_trabajador' type='hidden' value='"+sel+"'/><input name='tip' id='tip' type='hidden' value='"+tip+"'/><input name='cou' id='cou' type='hidden' value='"+cou+"'/><input name='dias' id='dias' type='hidden' /><input name='op' id='op_solucion' type='hidden' value='guardar_variacion'/><input type='submit' name='guardar_variacion' id='guardar_variacion' value='Guardar'/> <input type='reset' name='cancelar' value='Cancelar' class='close'/></div></form>",{title: "Realizar variaciones", 
		fixed: false,
		closeable:false,
		closeText:'cerrar',
		modal:true
		});
	$(".error").hide();
	$.getJSON("AplicacionRendicionCestaNew.php",{op:"ver_variaciones", id:sel, tipo:2},function(datos){									
										
										   var arreglo= datos;
										  
										 if((jQuery.inArray( "R445", arreglo ))>0){$("#accion").find("option[value='1']").remove();}
										 if((jQuery.inArray( "X445", arreglo ))>0){$("#accion").find("option[value='0']").remove();} 
										  });*/	
	

			 
//***************************************************	 
var currentTime = new Date();
var m= currentTime.getMonth() + 1;
var d = currentTime.getDate();
var y = currentTime.getFullYear();

/*var DISABLED_DATES = {
    20110502: true,
    20110505: true,
    20110510: true,
    20110511: true
};*/

	
	
      Calendar.setup({
        inputField : "cantidad2",
        trigger    : "f_btn1",
        onSelect   : function() { this.hide() },
        showTime   : 12,
		selectionType : Calendar.SEL_MULTIPLE,
        selection     : Calendar.dateToInt(new Date()),
		/*disabled : function(date) {
        date = Calendar.dateToInt(date);
        return date in DISABLED_DATES;
    },*/


        dateFormat : "%Y-%m-%d",
		onSelect      : function() {
        var count = this.selection.countDays();
		 $("#form_variacion"+sel+" #dias").attr("value",count);
		  $("#form_variacion"+sel+" #numero").attr("value",count);
		   $("#form_variacion"+sel+" #cantidad").attr("value", $("#form_variacion"+sel+" #cantidad2").val());
		  
        if (count == 1) {
            var date = this.selection.get()[0];
            date = Calendar.intToDate(date);
            date = Calendar.printDate(date, "%A, %B %d, %Y");
            $("calendar-info").innerHTML = date;
        } else {
            $("calendar-info").innerHTML = Calendar.formatString(
                "${count:no date|one date|two dates|# dates} selected",
                { count: count }
            );
        }
    },
    onTimeChange  : function(cal) {
        var h = cal.getHours(), m = cal.getMinutes();
        // zero-pad them
        if (h < 10) h = "0" + h;
        if (m < 10) m = "0" + m;
        $("calendar-info").innerHTML = Calendar.formatString("Time changed to ${hh}:${mm}", {
            hh: h,
            mm: m
        });
    }

      });

			

//******************************************************

	}


function showRequestSE(){
	
	
	}



function showResponseSE(data){
	$("#cuerpo_variacion").html("");
	$('#ventanaVariacion').modal('hide');
/*	$('.close').trigger("click");
	jQuery.noticeAdd({
      text: "<div id='imagen'><img src='images/"+data.image+"' /></div><div id='textn'><b>"+data.message+"</b></div>",
                        stay: false
                });	*/
	$.post("AplicacionRendicionCestaNew.php",{op:"consulta_cedula", id:data.ced},function(datos) { 
									          	$('.fila'+data.ced+'').css( "color", "#EE0000" );
												$('.fila'+data.ced+'').hide();$('.fila'+data.ced+'').html(datos);$('.fila'+data.ced+'').fadeIn("slow");	
												
								/*				
												
												$(".inicia_variacion").tooltip({ position: "center right", offset: [-2, 10], effect: "fade", opacity: 0.7, tip: '.tooltip'});		
$(".ver_conceptos").tooltip({ position: "center right", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });	*/
	$("#buscaPersonal #buscar_personal").attr("disabled","disabled");
	$(".inicia_variacion").click(muestra_ventana);
	$("input:button[name^='rendir']").click(cierra_proceso);
	$(".ver_conceptos").click(ver_conceptos);
													 }); 
	
	}

//**********************************************************
function cierra_proceso(){
	var sel= $(this).attr("id");	
var textResal="\xBFEsta seguro de formalizar la rendicion del personal "+sel+"?";
var textCont="-Recuerde que este proceso es totalmente irreversible -";
$("#cuerpo_consolidar").html(textResal+"<br/>"+textCont);
$('#ventanaConsolidar').modal('show');
$("#btn_consolidar").click(function(){
	
	$.getJSON("AplicacionRendicionCestaNew.php",{op:"consolida_informacion", tipo:sel},function(datos){									
										  var arreglo = datos;	
																			 
										  if(datos.exito==1){
											  $("#destino").html("");
											  //desactiva_opcion();
										desactiva_opcion();																																	   
										$("#buscar_personal").removeAttr("disabled"); $("#destino").html("");	  
											  }
											  
											  
										  }); 	
							
										
	});

	}
	
//*********************************************************
function ver_conceptos(){
	$(".boxy-wrapper").html("");

	var  sel = $(this).attr("id"); 	
	var id = $('.fila'+sel+'').find("td").eq(1).html();
	$.post("AplicacionRendicionCestaNew.php",{op:"ver_variaciones", id:sel, tipo:1},function(datos) {
		
		$("#cuerpo_conceptos").html(datos); 			
	$(".quitar_variacion").click(eliminar_variacion);	
			$('#ventanaConceptos').modal('show')
						 
									          									   
													 }); 
	
	
	
	}
//**********************************************************
function eliminar_variacion(){
	var  sel = $(this).attr("id"); 	
	var con = $(this).attr("name");
	$.post("AplicacionRendicionCestaNew.php",{op:"eliminar_variacion", id:sel, tipo:con},function(datos){$('.close').trigger("click"); });	
	
	
	$.post("AplicacionRendicionCestaNew.php",{op:"consulta_cedula", id:sel},function(datos) { 
									          	$('.fila'+sel+'').css( "color", "#EE0000" );
												$('.fila'+sel+'').hide();
												$('.fila'+sel+'').html(datos);
												$('.fila'+sel+'').fadeIn("slow");
/*$(".inicia_variacion").tooltip({ position: "center right", offset: [-2, 10], effect: "fade", opacity: 0.7, tip: '.tooltip'});		
$(".ver_conceptos").tooltip({ position: "center right", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });	
*/	$("#buscaPersonal #buscar_personal").attr("disabled","disabled");
	$(".inicia_variacion").click(muestra_ventana);
	$("input:button[name^='rendir']").click(cierra_proceso);
	$(".ver_conceptos").click(ver_conceptos);
													 });
	
	}
	
function bloquear() {
    mensaje('<div align="center"><img width="260px" height="40px" src="images/ajax-loader.gif" /><br/><blink>Procesando...</blink></div>','info');
}
function desbloquear() {
    $("#mdMsg").modal("hide");
}
function problemas() {
    mensaje('Error al Conectar con el servidor...','warning');
}
function mensaje(msg,tipo) {
  var clase = "alert alert-success";
  switch(tipo){
      case "success":
      case "info":
      case "warning":
      case "danger":
        clase="alert alert-"+tipo;
	      break;
  }
  $("#divMsg").html("<h3 class=\""+clase+"\">"+msg+"</h3>");
  $("#mdMsg").modal("show");
}
