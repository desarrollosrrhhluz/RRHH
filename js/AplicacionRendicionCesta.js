var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 



$('#error').hide();
$('#cuerpo_encabezado').hide();
parametros_rendicion();
desactiva_opcion();
envio_form();

}
function desactiva_opcion(){
	
	$.getJSON("AplicacionRendicionCesta.php",{op:"desactiva_opcion"},function(datos){									
										
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
 $.getJSON("AplicacionRendicionCesta.php",{op:"levanta_parametros"},function(datos){									
										
										  var arreglo = datos;	
										 if(datos.message==1){
											 
											 jQuery.noticeAdd({
      text: "<div id='imagen'><img src='images/"+datos.image+"' /></div><div id='textn'><b>Iniciando rendicion</b></div>",
                        stay: false
                });	
											
											 var array=["","Enero","Febrero","Marzo","Abril","Mayo","Junio", "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
											 var indice=parseInt(datos.mes);
											
											 $('#titulo').html('Rendici&oacute;n del Bono Alimentaci&oacute;n  '+array[indice]+' - '+datos.anno);
											 $('#cuerpo_encabezado').fadeIn();
											 }else{
												jQuery.noticeAdd({
      text: "<div id='imagen'><img src='images/Gnome-Process-Stop-32.png' /></div><div id='textn'><b>No esta dentro de un periodo de rendicion activo!</b></div>",
                        stay: false
                });	 
												$('#titulo').html('Rendici&oacute;n del Bono Alimentaci&oacute;n');
												$('#cuerpo_encabezado').html('<fieldset><legend>Fuera de Proceso</legend><br><br>No esta dentro de un periodo de rendion activo');
												$('#cuerpo_encabezado').fadeIn();
												 }
									 
										}); 	
	
	}
function envio_form(){
	
		var options = { 
        target:        '#destino', // elemento destino que se actualizará 
        beforeSubmit:  showRequestNOP,  //  respuesta antes de llamarpre-submit callback 
        success:       showResponseNOP,  //  respuesta después de llamar 
		url:       'AplicacionRendicionCesta.php',         // override for form's 'action' attribute 
        //type:      'post',        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  'json', 
		resetForm: true       // reinicia el formulario después de ser enviado correctamente 
  }; 
	 $('#buscaPersonal').validate({
					messages: {
						personal: {  required: 'Debe seleccionar un tipo de personal' }
			},					   
				errorLabelContainer: $("#error"),
		    wrapper: 'li',										   
				rules: {
						personal: {required: true  }
							 },//end rules					   

				submitHandler: function(form) {
				$('#buscaPersonal').ajaxSubmit(options);
				}
				}); 
	
	}
	
function  showRequestNOP(){
 jQuery.noticeAdd({
      text: "<div id='imagen'><img src='images/Gnome-Logviewer-32.png' /></div><div id='textn'><b>Listando resultados</b></div>",
                        stay: false
                });	
	}	
//******************************************
function  showResponseNOP(){
	
	$(".inicia_variacion").tooltip({ position: "center right", offset: [-2, 10], effect: "fade", opacity: 0.7, tip: '.tooltip'});		
    $(".ver_conceptos").tooltip({ position: "center right", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });	
	$("#buscaPersonal #buscar_personal").attr("disabled","disabled");
	
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
	}
	
//******************************************
function muestra_ventana(){
$(".boxy-wrapper").html("");
	
	var  sel = $(this).attr("id"); 	
	var id = $('.fila'+sel+'').find("td").eq(1).html();
	var cou = $('.fila'+sel+'').find("td").eq(2).html();
	var tip = $('.fila'+sel+'').find("td").eq(3).html();
	/*var tip = $('.fila'+sel+'').find("td").eq(3).html();
	var tip = $('.fila'+sel+'').find("td").eq(3).html();*/
	/*<option value='1'>Sumar</option>*/
	
																		
	 new Boxy("<form id='form_variacion"+sel+"' action='AplicacionRendicionCesta.php' method='post' name='form_variacion' class='form_variacion' ><label style='width:60px'>Trabajador:</label><input size='40' class='fif' name='problemas' value='"+id+"' type='text' id='problemas'/><br><label style='width:60px'>Accion:</label><select name='accion' id='accion'><option value=''>-Seleccione-</option><option value='0'>Restar</option></select><br><label style='width:60px'>Observacion:</label><textarea class='required' name='solucion' cols='30' rows='2' id='solucion'></textarea><br><label style='width:60px'>Seleccione dias:</label><input class='fif' size='35' disabled='disabled'  name='cantidad2' type='text' id='cantidad2'  /><input class='fif' size='35'  name='cantidad' type='hidden' id='cantidad'  /><img src='images/calendario.png' width='16' id='f_btn1' name='f_btn1' height='13' /><br><br><label style='width:60px'>Cantidad</label><input class='fif' disabled='disabled' size='5' name='numero' type='text' id='numero' value='0'  /><br><div id='call'></div><br><br><br><br><div id='error1' class='error'></div><div align='center'><input name='ce_trabajador' id='ce_trabajador' type='hidden' value='"+sel+"'/><input name='tip' id='tip' type='hidden' value='"+tip+"'/><input name='cou' id='cou' type='hidden' value='"+cou+"'/><input name='dias' id='dias' type='hidden' /><input name='op' id='op_solucion' type='hidden' value='guardar_variacion'/><input type='submit' name='guardar_variacion' id='guardar_variacion' value='Guardar'/> <input type='reset' name='cancelar' value='Cancelar' class='close'/></div></form>",{title: "Realizar variaciones", 
		fixed: false,
		closeable:false,
		closeText:'cerrar',
		modal:true
		});
	$(".error").hide();
	$.getJSON("AplicacionRendicionCesta.php",{op:"ver_variaciones", id:sel, tipo:2},function(datos){									
										
										   var arreglo= datos;
										  
										 if((jQuery.inArray( "R445", arreglo ))>0){$("#accion").find("option[value='1']").remove();}
										 if((jQuery.inArray( "X445", arreglo ))>0){$("#accion").find("option[value='0']").remove();} 
										  });	
	
	
 var options = { 
        target:        '#destino', // elemento destino que se actualizará 
        //beforeSubmit:  showRequestSE,  //  respuesta antes de llamarpre-submit callback 
       	success:       showResponseSE,  //  respuesta después de llamar 
        dataType:  'json', 
		resetForm: true       // reinicia el formulario después de ser enviado correctamente 
  }; 

  $("#form_variacion"+sel+"").validate({
					messages: {
						accion: {  required: 'Debe seleccionar una accion' },
						cantidad: {  required: 'Seleccione por lo menos un dia' },
						solucion: {  required: 'Debe escribir una observacion' }
			},					   
				errorLabelContainer: $("#error1"),
		    wrapper: 'li',										   
				rules: {
					accion: {  required: true },
					cantidad: {  required: true },
						solucion: {required: true  }
							 },//end rules					   

				submitHandler: function(form) {
				$("#form_variacion"+sel+"").ajaxSubmit(options);
				}
				}); 
 
				//$(".boxy-content").ajaxForm(options);
			 
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

function showResponseSE(data){
	$('.close').trigger("click");
	jQuery.noticeAdd({
      text: "<div id='imagen'><img src='images/"+data.image+"' /></div><div id='textn'><b>"+data.message+"</b></div>",
                        stay: false
                });	
	$.post("AplicacionRendicionCesta.php",{op:"consulta_cedula", id:data.ced},function(datos) { 
									          	$('.fila'+data.ced+'').css( "color", "#EE0000" );
												$('.fila'+data.ced+'').hide();$('.fila'+data.ced+'').html(datos);$('.fila'+data.ced+'').fadeIn("slow");	
												
												
												
												$(".inicia_variacion").tooltip({ position: "center right", offset: [-2, 10], effect: "fade", opacity: 0.7, tip: '.tooltip'});		
$(".ver_conceptos").tooltip({ position: "center right", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });	
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
Boxy.confirm("<div id='image'><img src='images/Gnome-Document-Save-32.png' width='44' height='38'/></div><div id='text'><b>"+textResal+"</b><br>"+textCont+"</div>", function(){
							
							$.getJSON("AplicacionRendicionCesta.php",{op:"consolida_informacion", tipo:sel},function(datos){									
										  var arreglo = datos;	
																			 
										  if(datos.exito==1){
											  $("#destino").html("");
											  //desactiva_opcion();
											  }
										  }); 	
							
										desactiva_opcion();																																	   
										$("#buscar_personal").removeAttr("disabled"); $("#destino").html("");																																	   
																																											   }, {title: 'Atencion'});
    return false;	
	
	}
	
//*********************************************************
function ver_conceptos(){
	$(".boxy-wrapper").html("");

	var  sel = $(this).attr("id"); 	
	var id = $('.fila'+sel+'').find("td").eq(1).html();
	$.post("AplicacionRendicionCesta.php",{op:"ver_variaciones", id:sel, tipo:1},function(datos) { 
			 new Boxy("<div id='cuerpo_conceptos'>"+datos+"</div>",{title: "Variacones del Trabajador", 
		fixed: false,
		closeable:true,
		closeText:'cerrar',
		modal:true
		});
	$('.error').hide();				
	$(".quitar_variacion").click(eliminar_variacion);										 
									          									   
													 }); 
	
	
	
	}
//**********************************************************
function eliminar_variacion(){
	var  sel = $(this).attr("id"); 	
	var con = $(this).attr("name");
	$.post("AplicacionRendicionCesta.php",{op:"eliminar_variacion", id:sel, tipo:con},function(datos){$('.close').trigger("click"); });	
	
	
	$.post("AplicacionRendicionCesta.php",{op:"consulta_cedula", id:sel},function(datos) { 
									          	$('.fila'+sel+'').css( "color", "#EE0000" );
												$('.fila'+sel+'').hide();
												$('.fila'+sel+'').html(datos);
												$('.fila'+sel+'').fadeIn("slow");
												$(".inicia_variacion").tooltip({ position: "center right", offset: [-2, 10], effect: "fade", opacity: 0.7, tip: '.tooltip'});		
$(".ver_conceptos").tooltip({ position: "center right", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });	
	$("#buscaPersonal #buscar_personal").attr("disabled","disabled");
	$(".inicia_variacion").click(muestra_ventana);
	$("input:button[name^='rendir']").click(cierra_proceso);
	$(".ver_conceptos").click(ver_conceptos);
													 });
	
	}