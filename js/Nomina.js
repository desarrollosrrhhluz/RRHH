$(document).ready(inicializarEventos);
var valor = null;
var cantidad = 0;
var nm ="";
function inicializarEventos() {
    $("#nomesp").hide();
    $("#c3").hide();
    $("#c2").hide();
    $("#c1").hide();
	$("#destino").html("");
	var options = { 
        target:        '#destino', 
        success:       showResponseDP,  
        //dataType:  'json', 
		resetForm: true       
  }; 
  $('#forma01').ajaxForm(options);
}

function showResponseDP(){
	$("#mes").show();
            $("#ano").show();
            $("#anomes").show();
            $("#cmbnomesp").hide();
	
	}

function ventana(){
	$("#destino").html("");
    var cano = document.getElementById("ano");
    var cmes = document.getElementById("mes");
    var ano = cano.options[cano.selectedIndex].value;
    var mes = cmes.options[cmes.selectedIndex].value;
    var cual = document.getElementById("cual");
    var opt = cual.options[cual.selectedIndex].value;
    if(opt=="pdf") {
        var w = window.open('detalleDePagoPDF.php?ano='+ano+'&mes='+mes,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
        return false;
    } else if(opt=="arc") {
        var w = window.open('arcPDF.php?ano='+ano,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
        return false;
    } else return true;
}



function showConsulta(e){
    valor = e.options[e.selectedIndex].value;
    switch(valor){
        case "detalle":
        case "pdf":
            $("#mes").show();
            $("#ano").show();
            $("#anomes").show();
            $("#cmbnomesp").hide();
            break;
        case "detalle_especial":
            $("#mes").hide();
            $("#ano").show();
            showEspecial(document.getElementById("ano"));
            $("#anomes").show();
            $("#cmbnomesp").show();
            break;
        case "resumen":
        case "arc":
            $("#mes").hide();
            $("#ano").show();
            $("#anomes").show();
            $("#cmbnomesp").hide();
            break;
        case "deudas":
            $("#mes").hide();
            $("#ano").hide();
            $("#anomes").hide();
            $("#cmbnomesp").hide();
            break;
    }
}

function showEspecial(e){
    var ano = e.options[e.selectedIndex].value;
    if(valor=="detalle_especial"){
    $.ajax({
           async:true,
           type: "POST",
           dataType: "html",
           contentType: "application/x-www-form-urlencoded",
           url:"cmbNominaEspecial.php",
           data:"ano="+ano,
           beforeSend:inicioNomEsp,
           success:finNomEsp,
           timeout:4000,
           error:problemas
         });
    }
    return false;
}
function inicioNomEsp() {
    bloquear();
}
function finNomEsp(combo) {
    $("#cmbnomesp").html(combo);
    desbloquear();
}
function bloquear() {
    $("#c3").hide();
    $("#c2").fadeIn("slow");
    $("#c1").show();
}
function desbloquear() {
    $("#c2").fadeOut("slow");
    $("#c1").hide();
}
function problemas() {
    desbloquear();
    $("#c3").show();
}



