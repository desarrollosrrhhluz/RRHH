var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){

consultaHijosRegistradosNacimientos();

$('#form_ayuda_naci').submit(function () {

   $.ajax({
                url: 'AyudaMatrimonioBT.php',
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
					alert("Su familiar se ha registrado satisfactoriamente");
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

$.post("AyudaMatrimonioBT.php",{op:"consultaInicialHijos"},function(datos) {  

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
if (confirm("Seguro que quiere eliminar este familiar ?")) {

$.post("AyudaMatrimonioBT.php",{op:"EliminarHijoRegistrado",id:id},function(datos) {  

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
                url: 'AyudaMatrimonioBT.php',
                type: "POST",
				dataType: "json",
                data: "op=datoshijoeditar&id="+id,
                beforeSend: function (datos) {
					
				
                },
                success: function (datos) {
				$('#fe_nac_fam').val(datos[0].fe_nacimiento);
				$('#fe_mat_fam').val(datos[0].fe_matrimonio);
				$('#nombre1').val(datos[0].nombre1);
				$('#ce_familiar').val(datos[0].ce_familiar);
				$('#nombre2').val(datos[0].nombre2);
				$('#apellido1').val(datos[0].apellido1);
				$('#apellido2').val(datos[0].apellido2);
				$("#sexo_fam option[value="+datos[0].sexo+"]").attr("selected","selected");
				$('#actanacimiento').val(datos[0].acta_matrimonio);
				$('#id_familiar_editar').val(datos[0].id_familiar);

				$('#div_form_fam').fadeIn("slow");
					}
});
}


function HijoPlanillaRegistrado(id)
{ 
window.open("AyudaMatrimonioPDF.php?id=" + id);
}
//***************************************************************
