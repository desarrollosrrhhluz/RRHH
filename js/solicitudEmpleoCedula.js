$(document).ready(inicializarEventos);

function inicializarEventos() {
	$("#buscar").click(function(){leerDatos();});

}


function leerDatos(){
	bloquear();
	var ci = $("#co_cedula").attr("value");

	$.post("solicitudEmpleoCedula.php", "co_cedula="+ci, 
		function(texto){ 
			$("#solicitudes").html(texto);
			desbloquear(); 
		}, "text");
}

//------------------------------------------------- bloqueo de pantalla
function bloquear() {
    $("#capaMsj").html('<img src="images/processingbar.gif"><br/>Cargando...');
    $("#capaMsj").show();
    $("#capaBloqueo").show();
}
function desbloquear() {
    $("#capaMsj").hide();
    $("#capaBloqueo").hide();
}

function problemas() {
    desbloquear();
    $("#capaMsj").html("Error al Conectar con el servidor...");
    $("#capaMsj").show();
}
function isNumber(evt){
	var nav4 = window.Event ? true : false;
	// Backspace = 8, Enter = 13, ‘0' = 48, ‘9' = 57, ‘.’ = 46
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
}


