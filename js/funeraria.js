var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 
$('#form_fam').submit(guarda_datos_familiar);

//var edad=calcularEdad('07/06/1987');

$("#modalAyuda").modal("show");

consultaInicialHijos();
$("#error").hide();
$("#mensaje_finalizado").hide();
$("#tipo_incapacidad").hide();

$("#div_form_fam").hide();
 $("#contenedor_solicitud").hide();
$("#iniciar_solicitud").hide();
$("#btn_formalizar").hide();

$("#btn_cancelar_familiar").click(ocultaFormAgregaFam);
$("#cont_sitio").hide();
$("input .clic_planilla").click(clic_planilla);	

$("#ce_familiar").change(function(event) {
	event.preventDefault();
  var sel= $(this).val();
  $.post('funeraria.php', {op: 'consultaSidial', ced:sel, tipo:1}, function(data, textStatus, xhr) {
	if(data==1){
		alert("El familiar que intenta registrar es trabajador de LUZ");
		$("input[type=text], select").val("");
		return false;
	}
	if(data==2){
		alert("El familiar que intenta registrar ya existe");
		$("input[type=text], select").val("");
		return false;
	}
});


});

$("#fe_nac_fam").change(function(event) {
	event.preventDefault();

	var edad=calcularEdad($(this).val());
	if(edad>=12){

		$("#ce_familiar").attr('required', 'required');

	}else{
		$("#ce_familiar").removeAttr('required');

	}

});

$("#btnNuevoFamiliar").click(nuevoFamiliar);

 parentesPrincipales='<option value="">-Seleccione-</option> <option value="A">Padre/Madre</option> <option value="C">Conyuge</option> <option value="D">Hijo(a)</option>';
 
var tip=$("#tipopersonal").val();


parentesAdicionales='<option value="">-Seleccione-</option> <option value="DM">Hijo(a) mayor de 25</option><option value="N">Nieto(a)</option> <option value="H">Hermano(a)</option> <option value="DH">Sobrino(a)</option><option value="S">Suegro(a)</option>';


}
//***************************************************************





//*********************************************************
//*******carga inicial de hijos***************************
function consultaInicialHijos(){
	 $.post("funeraria.php",{op:"consultaInicialHijos"},function(datos) { 
	
									         $("#familiares_existentes").html(datos) ;
										//$(".clic_eliminar").tooltip({ position: "center left", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });
										//$(".clic_editar").tooltip({ position: "center right", offset: [-2, 10],  effect: "fade", opacity: 0.7,  tip: '.tooltip' });
										
										$(".clic_editar_p").click(editaFamSel);
										$(".clic_editar_a").click(editaFamAdicSel);
										$("#sol_bene").click(sol_bene);
										$(".clic_planilla").click(clic_planilla);
										$(".clic_eliminar_a").click(eliminaFamiliarAdicionalSel);

										$("#numero").html($("#datoNumero").val());
											 //$("#btn_nuevaM").click(muestraDivNM);
											
											 }); 	
	}
	
	

//*******************movimiento de divs contenedores*******.

//******************************************************	
function nuevoFamiliar(){



$("#datos_padre").hide();
$("#form_fam #ce_otro_p").removeAttr("required");
$("#form_fam #nomb_otro_p").removeAttr("required");
$("#parentesco_fam").html(parentesAdicionales);
$("#parentesco_fam").removeAttr('disabled');
$("#form_fam #fe_nac_fam").removeAttr('disabled');
$("input[type=text], select").val("");
$("#form_fam #op_familiar").attr("value","guarda_adicional");
$("#form_fam #id_fo").attr("value","");
$("#modalFuneraria").modal("show");									

	}
	
//*******************movimiento de divs contenedores*******
function ocultaFormAgregaFam(){
$("input[type=text], select").val("");
$("#form_fam #op_familiar").attr("value","");
$("#form_fam #id_fo").attr("value","");
$("#modalFuneraria").modal("hide");

	}
//*********************************************************


//***********envio del formulario de datos del familiar
function guarda_datos_familiar(){
	
	    var values = $("#form_fam").serialize();

		 $.ajax({
                url: 'funeraria.php',
                type: "POST",
                data:values,
                beforeSend: showRequestDP2 ,
                success: showResponseDP2,
				dataType: 'json'
            });
	
        return false;
	
	}

function showRequestDP2(data){

	if($("#parentesco_fam").val()=='DM'){
	var edad=calcularEdad($("#fe_nac_fam").val());
	if(edad<25){
	$("#fe_nac_fam").val("");
	 alert("Solo pude agregar Hijos mayores de 25 a\u00f1os");
		return false;
	}

	}

$("#btn_guardar_familiar").attr('disabled', 'disabled');

 /*if($("#form_fam #registra").val() == $("#form_fam #ce_otro_p").val()){
	alert("La cedula del otro Progenitor no puede ser igual al la cedula del trabajador");
	 $("#form_fam #ce_otro_p").attr("value","");
	  $("#form_fam #nomb_otro_p").attr("value","");
	 return false;
	 }
*/
	
	}

function showResponseDP2(data){
	$("#btn_guardar_familiar").removeAttr('disabled');

	alert(data.message);
	ocultaFormAgregaFam();
	consultaInicialHijos();
	
	
    $("#form_fam #op_familiar").attr("value","guarda_adicional");
    $("#form_fam #fe_nac_fam").removeAttr("disabled");

	}
	
//******************************************************************
//*******************prepara el fomulario pàra editar datos*********
function editaFamSel(){

	var  sel = $(this).attr("id");


	$.getJSON("funeraria.php",{op:"query_jsonFamiliar",id:sel},function(datos){ 											
										  var arreglo = datos;	
										  var niv=arreglo[0].nivel;
										  $("#form_fam #fe_nac_fam").attr("value",arreglo[0].fe_nacimiento);
										  
											$("#form_fam #ce_familiar").attr("value",arreglo[0].ce_familiar);
											
											$("#form_fam #nombre1").attr("value",arreglo[0].nombre1);
											$("#form_fam #nombre2").attr("value",arreglo[0].nombre2);
											$("#form_fam #apellido1").attr("value",arreglo[0].apellido1);
											$("#form_fam #apellido2").attr("value",arreglo[0].apellido2);
										    $("#form_fam #sexo_fam option[value='"+arreglo[0].sexo+"']").attr("selected",true);
											
											$("#form_fam #ce_otro_p").attr("value",arreglo[0].ce_otro_padre);
											
											var ced2=$("#registra").val();
											if(ced2==arreglo[0].ce_otro_padre){
											$("#form_fam #ce_otro_p").attr("value",arreglo[0].ce_trabajador);
											$("#form_fam #nomb_otro_p").attr("value",arreglo[0].nombre_trabajador);
											}else{
											$("#form_fam #ce_otro_p").attr("value",arreglo[0].ce_otro_padre);	
											$("#form_fam #nomb_otro_p").attr("value",arreglo[0].nombre_conyuge);
											
												}
										
											if(arreglo[0].nuevo==0){
										    $("#form_fam #fe_nac_fam").attr("disabled","disabled");
												}else{
											$("#form_fam #fe_nac_fam").removeAttr("disabled");
												}
											$("#parentesco_fam").attr('disabled', 'disabled');
											$("#parentesco_fam").html(parentesPrincipales);
											$("#form_fam #parentesco_fam option[value="+arreglo[0].parentesco+"]").attr("selected",true);
											if(arreglo[0].parentesco!='D'){

												$("#datos_padre").hide();
												$("#form_fam #ce_otro_p").removeAttr("required");
												$("#form_fam #nomb_otro_p").removeAttr("required");

											}else{

												$("#datos_padre").show();
												$("#form_fam #ce_otro_p").attr("required", "required");
												$("#form_fam #nomb_otro_p").attr("required", "required");

											}
												
											
											$("#form_fam #id_fo").attr("value",sel);
											$("#form_fam #op_familiar").attr("value","UpdateFamiliar");
											$("#modalFuneraria").modal("show");

										}); 
											
	}
//**********************************************************************
function editaFamAdicSel(){
	

	var  sel = $(this).attr("id");


	$.getJSON("funeraria.php",{op:"query_jsonFamiliarAdicional",id:sel},function(datos){ 											
										  var arreglo = datos;	
										  var niv=arreglo[0].nivel;
										  $("#form_fam #fe_nac_fam").attr("value",arreglo[0].fe_nacimiento);
										  
											$("#form_fam #ce_familiar").attr("value",arreglo[0].ce_familiar);
											
											$("#form_fam #nombre1").attr("value",arreglo[0].nombre1);
											$("#form_fam #nombre2").attr("value",arreglo[0].nombre2);
											$("#form_fam #apellido1").attr("value",arreglo[0].apellido1);
											$("#form_fam #apellido2").attr("value",arreglo[0].apellido2);
										    $("#form_fam #sexo_fam option[value='"+arreglo[0].sexo+"']").attr("selected",true);
											
											
											var ced2=$("#registra").val();
										
											
											$("#form_fam #fe_nac_fam").removeAttr("disabled");
												
											
											$("#parentesco_fam").html(parentesAdicionales);
											$("#parentesco_fam").removeAttr('disabled');
											$("#form_fam #parentesco_fam option[value="+arreglo[0].parentesco+"]").attr("selected",true);
											

											$("#datos_padre").hide();
											$("#form_fam #ce_otro_p").removeAttr("required");
											$("#form_fam #nomb_otro_p").removeAttr("required");

											
											$("#form_fam #id_fo").attr("value",sel);
											$("#form_fam #op_familiar").attr("value","UpdateFamiliarAdicional");
											$("#modalFuneraria").modal("show");

										}); 
											
	}


//**************Muestra la opcion de terminar solicitud******
function eliminaFamiliarAdicionalSel(){
var  sel = $(this).attr("id");

if(confirm('Esta seguro de eliminar el familiar seleccionado? \nEste proceso es irreversible.')){
		$("#sol_bene").attr("disabled", "disabled");
					  $.getJSON("funeraria.php",{op:"eliminaFamiliarAdicionalSel", id:sel},function(datos) { 
					  alert(datos.message);
					  consultaInicialHijos();
					   });	
    return true;
	
	}else{
	//desbloquear();	
	return false;	
		}	   

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
$(".checkFam").each(function(i){
	var  sel = $(this).attr("value"); 			 
	if($(this).is(':disabled')) {
    // var  sel = $(this).attr("id"); 
	arrayDisabled[ind]=sel; 
	//alert(arrayContrato[i]);
	ind=ind+1;
	}
}); 
 if( parseInt(arrayDisabled.length) + parseInt(arrayContrato.length) > 10 ){
 var cant= 10 - parseInt(arrayDisabled.length);
 if(cant<=0){ var mensaje='No puede incluir mas de 10 familiares';}else{ var mensaje='Solo puede incluir '+cant+' familiar(es) mas';}
	 alert("Ha incluido "+parseInt(arrayDisabled.length)+" familiar(es).\n  "+mensaje+"");
	 }else{
 
if (arrayContrato.length>10){
	alert("Solo puede incluir hasta un maximo de 10 familiares");
	}else{
		  var cadena = arrayContrato.join(',');
		  
		  if(cadena!=""){
	if(confirm('Esta seguro de formalizar su solicitud? \nEste proceso es irreversible.')){
		$("#sol_bene").attr("disabled", "disabled");
					  $.getJSON("funeraria.php",{op:"insertaPoliza", id:cadena},function(datos) { 
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
	window.open('./funerariaPDF.php','Planilla','menubar=no,scrollbars=yes,toolbar=no,location=no,directories=no,resizable=no,top=0,left=0');
	
	}
	
//************Busca si la solicitud ya fue terminada******

function calcularEdad(fecha)

{

   
 

        var values=fecha.split("/");

        var ano = values[2];

        var mes = values[1];

        var dia = values[0];

 

        // cogemos los valores actuales

        var fecha_hoy = new Date();

        var ahora_ano = fecha_hoy.getYear();

        var ahora_mes = fecha_hoy.getMonth()+1;

        var ahora_dia = fecha_hoy.getDate();

 

        // realizamos el calculo

        var edad = (ahora_ano + 1900) - ano;

        if ( ahora_mes < mes )

        {

            edad--;

        }

        if ((mes == ahora_mes) && (ahora_dia < dia))

        {

            edad--;

        }

        if (edad > 1900)

        {

            edad -= 1900;

        }

 



        return edad;


     //   document.getElementById("result").innerHTML="Tienes "+edad+" años, "+meses+" meses y "+dias+" días";

  

}