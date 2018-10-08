
var x;
x=$(document);
x.ready(inicializarEventos);

	//************************************************* 
	function inicializarEventos(){ 

		$('#form_fam').submit(guarda_datos_familiar);
		buscaTerminadas();
		consultaInicialHijos();
		$("#error").hide();
		$("#mensaje_finalizado").hide();
		$("#tipo_incapacidad").hide();
		consultActualizacion();
		$("#div_form_fam").hide();
		$("#contenedor_solicitud").hide();
		$("#iniciar_solicitud").hide();
		$("#btn_formalizar").hide();
		$("#btn_cancelar_familiar").click(ocultaFormAgregaFam);
		$("#cont_sitio").hide();
		$("input .clic_planilla").click(clic_planilla);	
		muetra_boton_terminar();
		carga_grados();
	}
	//***************************************************************

	function carga_grados(){
		$("#nivel_estudio").change(function(){
		$.post("./includes/searchGrado.php",{ id:$(this).val() },function(data){$("#grado_estudio").html(data);})	});	 
	}

	//**********con si estan actualizados los registros********
	function consultActualizacion(){
		
		/*	$.getJSON("BeneficiosContractualesBT.php",{op:"consultaActualizacion"},function(datos){
											 											
											  var arreglo = datos;	
											
										 if(arreglo==1){
												 $("#iniciar_solicitud").click(iniciaCuadroBeneficios);
												 $("#iniciar_solicitud").show();
												 }
											if(arreglo==2){ $("#iniciar_solicitud").hide(); }
											if(arreglo==0){ $("#iniciar_solicitud").hide();}
												
											}); 	*/ 
	}
	//*********************************************************

	//*******carga inicial de hijos***************************
	function consultaInicialHijos(){
		
		$.post("BeneficiosContractualesBT.php",{op:"consultaInicialHijos"},function(datos) { 
		$("#familiares_existentes").html(datos) ;
		//$(".clic_eliminar").tooltip({ position: "center left", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });
		//$(".clic_editar").tooltip({ position: "center right", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });
											
		$(".clic_editar").click(editaFamSel);
		$("#sol_bene").click(sol_bene);
		$(".clic_planilla").click(clic_planilla);
		$('.boton_negado').tooltip();
		//$(".clic_eliminar").click(eliminaFamSel);
		//$("#btn_nuevaM").click(muestraDivNM);
		}); 	
	}
	
	//**************combo dependiente condicion personal***********
	//**************************************************************
	function din_lista(){

		$("#condicion_fam").change(function () {
		    
		    if($(this).val()==2) {
			
				//$("#motivo_incapacidad").rules("add", {required:true });
				$("#tipo_incapacidad").show();
				//$("#conapdis").attr("required",true);
			
			} else {

				$("#tipo_incapacidad").hide();
				//$("#conapdis").removeAttr("required");
				//	$("#motivo_incapacidad").rules("remove");
			}
	    });
	}

	//**************actividad del div cond atos de estudio***********
	//***************************************************************
	function des_estudio() {

		$("#estudia").change(function () {
					
			if($(this).val()==1) {

				$("#campos_estudio").show();
				$("#nivel_estudio").attr("required",true);
				$("#regimen").attr("required",true);
				$("#grado_estudio").attr("required",true);
				$("#sitio_estudio").attr("required",true);
				$("#rif").attr("required",true);
				$("#tipo_sitio").attr("required",true);
					
			} else {

				$("#campos_estudio").hide();
				$("#nivel_estudio").removeAttr("required");
				$("#sitio_estudio").removeAttr("required");
				$("#rif").removeAttr("required");
				$("#regimen").removeAttr("required");
				$("#grado_estudio").removeAttr("required");
				$("#tipo_sitio").removeAttr("required");
					
			}
	    });

		/*$("#rif").change(function(){
			var val=$(this).val();
			
			$.getJSON("includes/seniat.php",{rif:val},function(datos){ 
			if(datos[0].resultado>0){
			$("#sitio_estudio").attr("value",datos[0].nombre);
			}else{
				$("#rif").attr("value","");
				$("#sitio_estudio").attr("value","");
				alert("El rif no es valido");
				
				}
			});
			});*/

	}

	//****************carga select universidades*****************	
	function select_desplegable(){
		$.post("includes/searchSitioEstudio.php",function(datos) { $("#sitio_estudio").html(datos) ; } ); 
	}

	function select_desplegable2(){
		$.post("includes/searchSitioEstudioColegios.php",function(data){$("#sitio_estudio").html(data);});
	}
	
	//*******************movimiento de divs contenedores*******.
	//******************************************************	
	function muestraFormAgregaFam(){

		$("#campos_estudio").hide();
		$("#tipo_incapacidad").hide();
		$("#div_form_fam").fadeIn();
		$("#familiaresRegistrados").hide();
		$("#form_fam #op_familiar").attr("value","guarda_familiar");
		$("#form_fam #fe_nac_fam").removeAttr("disabled");
		din_lista();
		des_estudio();
	}
	
	//*******************movimiento de divs contenedores*******
	function ocultaFormAgregaFam(){

		$('#form_fam')[0].reset();
		$("#campos_estudio").hide();
		$("#tipo_incapacidad").hide();
		$("#div_form_fam").fadeOut();
		$("#form_fam #op_familiar").attr("value","guarda_familiar");
		$("#form_fam #fe_nac_fam").removeAttr("disabled");
		din_lista();
		des_estudio();
		$("#iniciar_solicitud").removeAttr("disabled");
		$("#familiaresRegistrados").show();
	}
	//*********************************************************

	//***********envio del formulario de datos del familiar
	function guarda_datos_familiar(){
		
		var values = $("#form_fam").serialize();
		$.ajax({
			url: 'BeneficiosContractualesBT.php',
			type: "POST",
			data:values,
			beforeSend: showRequestDP2 ,
			success: showResponseDP2,
			dataType: 'json'
	    });
		
	    return false;
	}

	function showRequestDP2(data){

		if($("#form_fam #registra").val() == $("#form_fam #ce_otro_p").val()){
			alert("La cedula del otro Progenitor no puede ser igual al la cedula del trabajador");
			$("#form_fam #ce_otro_p").attr("value","");
			$("#form_fam #nomb_otro_p").attr("value","");
			return false;
		}

		if($("#nivel_estudio").val()==1 && $("#nivel_estudio").val()==2 ){

			if($("#rif").val()=='G-20008806-0'){
				alert("No puede Usar el RIF de la Universidad del Zulia en un nivel de estudio distinto al Universitario");
				$("#rif").attr("value","");
				$("#sitio_estudio").attr("value","");
				return false;
			}
		}
	}

	function showResponseDP2(data){

		alert(data.message);
		ocultaFormAgregaFam();
		consultaInicialHijos();
		$("#form_fam #op_familiar").attr("value","guarda_familiar");
		$("#form_fam #fe_nac_fam").removeAttr("disabled");
		consultActualizacion();
	}
	
	//******************************************************************
	//*******************prepara el fomulario pàra editar datos*********
	function editaFamSel(){
		
		$('#form_fam')[0].reset();
		var  sel = $(this).attr("id");

		$.getJSON("BeneficiosContractualesBT.php",{op:"query_jsonFamiliar",id:sel},function(datos){ 											
			
			var arreglo = datos;	
			var niv=arreglo[0].nivel;

			$("#form_fam #fe_nac_fam").attr("value",arreglo[0].fe_nacimiento);
				
			$.post("./includes/searchGrado.php",{ id:niv  },function(data){$("#grado_estudio").html(data);

			$("#form_fam #grado_estudio option[value="+arreglo[0].grad_sem_trim+"]").attr("selected", true);
			});														  
											  
			$("#form_fam #ce_familiar").attr("value",arreglo[0].ce_familiar);
			$("#form_fam #nombre1").attr("value",arreglo[0].nombre1);
			$("#form_fam #nombre2").attr("value",arreglo[0].nombre2);
			$("#form_fam #apellido1").attr("value",arreglo[0].apellido1);
			$("#form_fam #apellido2").attr("value",arreglo[0].apellido2);
			$("#form_fam #sexo_fam option[value='"+arreglo[0].sexo+"']").attr("selected",true);
			$("#form_fam #condicion_fam option[value="+arreglo[0].condicion_personal+"]").attr("selected",true);
			$("#form_fam #ce_otro_p").attr("value",arreglo[0].ce_otro_padre);
												
			var ced2=$("#registra").val();

			if(ced2==arreglo[0].ce_otro_padre){
			
				$("#form_fam #ce_otro_p").attr("value",arreglo[0].ce_trabajador);
				$("#form_fam #nomb_otro_p").attr("value",arreglo[0].nombre_trabajador);
			
			} else {
			
				$("#form_fam #ce_otro_p").attr("value",arreglo[0].ce_otro_padre);	
				$("#form_fam #nomb_otro_p").attr("value",arreglo[0].nombre_conyuge);									
			}
			
			$("#tipo_sitio").val("");
			
			if(arreglo[0].nuevo==0) {
				
				$("#form_fam #fe_nac_fam").attr("disabled","disabled");
			
			} else {
				
				$("#form_fam #fe_nac_fam").removeAttr("disabled");
			
			}
			
			$("#form_fam #estudia option[value="+arreglo[0].activo_estudio+"]").attr("selected",true);
			
			if(arreglo[0].activo_estudio==2){

				$("#campos_estudio").hide();
				$("#nivel_estudio").removeAttr("required");
				$("#sitio_estudio").removeAttr("required");
				$("#tipo_sitio").removeAttr("required");
				$("#rif").removeAttr("required");
				$("#regimen").removeAttr("required");
				$("#grado_estudio").removeAttr("required");

			}else{

				$("#campos_estudio").show();
				$("#cont_sitio").show();
				$("#rif").attr("value",arreglo[0].rif_sitio_estudio);
				$("#tipo_sitio option[value="+arreglo[0].tipo_sitio_estudio+"]").attr("selected",true);
				$("#sitio_estudio").attr("value",arreglo[0].nombre_sitio_estudio);
				$("#nivel_estudio").attr("required",true);
				$("#regimen").attr("required",true);
				$("#grado_estudio").attr("required",true);
				$("#tipo_sitio").attr("required",true);
				$("#sitio_estudio").attr("required",true);
				$("#rif").attr("required",true);
			}
											
			$("#form_fam #nivel_estudio option[value="+niv+"]").attr("selected", true);

			if(arreglo[0].condicion_personal==1){

				$("#tipo_incapacidad").hide();	
				//$("#conapdis").removeAttr("required");

			}else{

				//	$("#conapdis").attr("required",true);
				$("#conapdis").attr('value',arreglo[0].conapdis);
				$("#tipo_incapacidad").show();//$("#form_fam #motivo_incapacidad").attr("value",arreglo[0].motivo_incapacidad);										

			}

			$("#form_fam #regimen option[value="+arreglo[0].regimen_educativo+"]").attr("selected", true);
			//;
																					
			$("#form_fam #id_fo").attr("value",sel);
			$("#form_fam #op_familiar").attr("value","UpdateFamiliar");

		}); 
		
		muestraFormAgregaFam();
	}
	//**********************************************************************

	//**************Muestra la opcion de terminar solicitud******	
	function muetra_boton_terminar() {
		
		$.getJSON("BeneficiosContractualesBT.php",{op:"tieneBeneficios"},function(datos){
			
			//alert(datos);										
			var arreglo = datos;	
			
			if (arreglo<1) {
				
				$("#btn_formalizar").hide();
			
			} else {

				$("#btn_formalizar").click(cierraSolicitud);	
				$("#btn_formalizar").show();

			}
		}); 	
	}

	//*********************************************************
	//*********termina solicitud*******************************		
	function sol_bene(){
		
		var arrayContrato = new Array();
		var arrayDisabled = new Array();
		var indice=0;
		var ind=0;
		
		$(".checkFam").each(function(i){
			var  sel = $(this).attr("value"); 			 
			if($(this).is(':checked')) {
		    	// var  sel = $(this).attr("id"); 
				arrayContrato[indice]=sel; 
				//alert(arrayContrato[i]);
				indice=indice+1;
			}
		}); 

		$(".checkFam").each(function(i) {
			var  sel = $(this).attr("value"); 			 
			if($(this).is(':disabled')) {
		    	// var  sel = $(this).attr("id"); 
				arrayDisabled[ind]=sel; 
				//alert(arrayContrato[i]);
				ind=ind+1;
			}
		}); 

		if( parseInt(arrayDisabled.length) + parseInt(arrayContrato.length) > 6 ){
			
			var cant= 6 - parseInt(arrayDisabled.length);
			
			if(cant<=0){ 
				var mensaje='No puede Solicitar mas beneficios';
			} else { 
				var mensaje='Solo puede solicitar beneficios para '+cant+' familiar(es) mas';
			}
			alert("Ha Solicitado beneficios para "+parseInt(arrayDisabled.length)+" familiar(es).\n  "+mensaje+"");
		
		}else{
		 
			if (arrayContrato.length>6){

				alert("Solo puede solicitar beneficios hasta un maximo de 6 Hijos(as)");
				
			} else {
				
				var cadena = arrayContrato.join(',');
					  
				if(cadena!=""){
						
					if(confirm('Esta seguro de continuar con la solicitud de beneficios?')){
						
						$("#sol_bene").attr("disabled", "disabled");
						$.getJSON("BeneficiosContractualesBT.php",{op:"insertaBeneficios", id:cadena},function(datos) { 
								alert(datos.message);
								consultaInicialHijos();
						});	
				    	return true;
					
					}else{
						//desbloquear();	
						return false;	
					}	   
								   
								  
				}else{
					alert("Debe Seleccionar Por los Menos 1 Familiar");
				}
				
			}
		}
	}
	
	//********************************************************
	function clic_planilla(){

		var fam= $(this).attr('id');
		window.open('./BeneficiosContractualesPlanillaPdfBT.php?id='+fam+'','Planilla','menubar=no,scrollbars=yes,toolbar=no,location=no,directories=no,resizable=no,top=0,left=0');
	}
	
	//************Busca si la solicitud ya fue terminada******
	function buscaTerminadas(){
		
		$.getJSON("BeneficiosContractualesBT.php",{op:"buscaTerminada"},function(datos){
			
			var arreglo = datos;
			//alert(arreglo[0].resultado);									
											  	
			if (arreglo[0].resultado<1) {
				$("#mensaje_finalizado").hide();
				//MuestraAyuda();
			} else {
				$("#familiaresRegistrados").hide();	
				$("#contenedor_solicitud").hide();
				ocultaFormAgregaFam();
				$("#enlace").attr("href","BeneficiosContractualesPlanillaPDF.php?id="+arreglo[0].id_solicitud+"&pass="+arreglo[0].password+"&anno="+arreglo[0].anno+"")
				$("#mensaje_finalizado").show();										
			}
		}); 	 
	}	
