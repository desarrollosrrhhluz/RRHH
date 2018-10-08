var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){

consultaHijosRegistradosMuertes();

$('#form_ayuda_muerte').submit(function () {

   $.ajax({
                url: 'AyudaMuerteBT.php',
                type: "POST",
                data: $('#form_ayuda_muerte').serialize(),
                beforeSend: function (datos) {
					
                $("#resultadosayudamuerte").show();
                var x = $("#resultadosayudamuerte");
                x.html('<img src="images/cargando.gif">');
				
                },
                success: function (datos) {
					
					$("#resultadosayudamuerte").fadeOut("slow");
			
					if(datos=="1"){
					alert("Operaci\u00f3n realizada con \u00e9xito");
					consultaHijosRegistradosMuertes();
					 $('#form_ayuda_muerte')[0].reset();
					 $('#div_form_fam').fadeOut("slow");
					}
					
					if(datos=="2"){
					alert("Datos actualizados satisfactoriamente");
					consultaHijosRegistradosMuertes();
					 $('#form_ayuda_muerte')[0].reset();
					 $('#div_form_fam').fadeOut("slow");
					 
					}
					if(datos=="0"){
					alert("Problemas al realizar la operaci\u00f3n");
					}
                }
            });
 
        return false;
    });
}

//*********************************************************
//*******carga inicial de hijos***************************
function consultaHijosRegistradosMuertes(){

$.post("AyudaMuerteBT.php",{op:"consultaInicialHijos"},function(datos) {  




if(datos!="1")
{
$("#HijosRegistradosMuerte").fadeIn("slow");
$('#div_form_fam').fadeOut("slow");

$("#HijosRegistradosMuerte").empty();

$("#HijosRegistradosMuerte").html("<fieldset><legend><h2><img src='images/group.png' width='32' height='32' />Familiares Registrados</h2></legend>"+datos+"</fieldset>");
}else{
$("#HijosRegistradosMuerte").fadeIn("slow");
$("#HijosRegistradosMuerte").append('<h3>Usted no tiene familiares  registrados</h3>');



$('#div_form_fam_muerte').fadeOut("slow");
	} 
  }); 	
}	
	
	
function EliminarHijoRegistrado(id)
{
	
// Pedimos confirmación
if (confirm("Seguro que quiere eliminar este hijo ?")) {

$.post("AyudaMuerteBT.php",{op:"EliminarHijoRegistrado",id:id},function(datos) {  

if(datos=="1")
{

alert("Registro eliminado con \u00e9xito");
consultaHijosRegistradosMuertes();
}else{

alert("Problemas al realizar la operaci\u00f3n");
	
}	
});
}
}

function NuevoRegistroHijo()
{
	$('#form_ayuda_muerte')[0].reset();
	$("#sexo_fam option[value='']").attr("selected","selected");
	$('#div_form_fam').fadeIn("slow");
	$('#op').val("NuevoHijo");

}

function CancelarNuevoRegistroHijo()
{

	$('#div_form_fam').fadeOut("slow");
	$('#form_ayuda_muerte')[0].reset();
	$("#sexo_fam option[value='']").attr("selected","selected");

}


function EditaHijoRegistrado(id)
{ 

 $('#op').val('EditarNuevoFamiliar');
 
 $.ajax({
                url: 'AyudaMuerteBT.php',
                type: "POST",
				dataType: "json",
                data: "op=datoshijoeditar&id="+id,
                beforeSend: function (datos) {
					
				
                },
                success: function (datos) {

				$('#ce_otro_p').val(datos.ce_otro_p);
				$('#nomb_otro_p').val(datos.nombre_conyuge);
				$('#fe_nac_fam').val(datos.fe_nacimiento);
				$('#nombre1').val(datos.nombre1);
				$('#nombre2').val(datos.nombre2);
				$('#apellido1').val(datos.apellido1);
				$('#apellido2').val(datos.apellido2);
				$("#sexo_fam option[value="+datos.sexo+"]").attr("selected","selected");
				$('#actanacimiento').val(datos.acta);
				$('#id_familiar_editar').val(datos.id_familiar);
				
				$('#actadefuncion').val(datos.acta_defuncion);
				$('#fe_falle_fam').val(datos.fe_muerte);
			

				$('#div_form_fam').fadeIn("slow");
					}
});
}


function HijoPlanillaRegistrado(id)
{ 
window.open("AyudaMuertePDF.php?id=" + id);
}
//***************************************************************
