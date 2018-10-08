var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){

consultaHijosRegistradosNacimientos();

$('#form_ayuda_naci').submit(function () {

   $.ajax({
                url: 'AyudaNacimientoBT.php',
                type: "POST",
                data: $('#form_ayuda_naci').serialize(),
                beforeSend: function (datos) {
					
                $("#resultadosayudahijo").show();
                var x = $("#resultadosayudahijo");
                x.html('<img src="images/cargando.gif">');
				
                },
                success: function (datos) {
					
					$("#resultadosayudahijo").fadeOut("slow");
			
					if(datos=="1"){
					alert("Su hijo se ha registrado satisfactoriamente");
					consultaHijosRegistradosNacimientos();
					 $('#form_ayuda_naci')[0].reset();
					 $('#div_form_fam').fadeOut("slow");
					}
					
					if(datos=="2"){
					alert("Datos actualizados satisfactoriamente");
					consultaHijosRegistradosNacimientos();
					 $('#form_ayuda_naci')[0].reset();
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
function consultaHijosRegistradosNacimientos(){

$.post("AyudaNacimientoBT.php",{op:"consultaInicialHijos"},function(datos) {  

if(datos!="1")
{
$("#HijosRegistradosNacimiento").fadeIn("slow");
$('#div_form_fam').fadeOut("slow");

$("#HijosRegistradosNacimiento").html("<fieldset><legend><h2><img src='images/group.png' width='32' height='32' />Hijos Registrados</h2></legend>"+datos+"</fieldset>");
}else{
$("#HijosRegistradosNacimiento").fadeOut("slow");
$('#div_form_fam').fadeIn("slow");
	} 
  }); 	
}	
	
	
function EliminarHijoRegistrado(id)
{
	
// Pedimos confirmación
if (confirm("Seguro que quiere eliminar este hijo ?")) {

$.post("AyudaNacimientoBT.php",{op:"EliminarHijoRegistrado",id:id},function(datos) {  

if(datos=="1")
{

alert("Registro eliminado con \u00e9xito");
consultaHijosRegistradosNacimientos();
}else{

alert("Problemas al realizar la operaci\u00f3n");
	
}	
});
}
}

function NuevoRegistroHijo()
{
	$('#form_ayuda_naci')[0].reset();
	$("#sexo_fam option[value='']").attr("selected","selected");
	$('#div_form_fam').fadeIn("slow");
	$('#op').val("NuevoHijo");

}

function CancelarNuevoRegistroHijo()
{

	$('#div_form_fam').fadeOut("slow");
	$('#form_ayuda_naci')[0].reset();
	$("#sexo_fam option[value='']").attr("selected","selected");

}


function EditaHijoRegistrado(id)
{ 

 $('#op').val('EditarNuevoHijo');
 
 $.ajax({
                url: 'AyudaNacimientoBT.php',
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

				$('#div_form_fam').fadeIn("slow");
					}
});
}


function HijoPlanillaRegistrado(id)
{ 


window.open("AyudaNacimientoPDF.php?id=" + id);
}
//***************************************************************
