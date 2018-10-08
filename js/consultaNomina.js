$(document).ready(inicializarEventos);
var valor = null;
var cantidad = 0;
var nm ="";
function inicializarEventos() {
    $("#nomesp").hide();
    $("#c3").hide();
    $("#c2").hide();
    $("#c1").hide();
    $("#tipo_personal").hide();
    $("#tipo_p").hide();
    $("#boton_submit").hide();
}



function ventana(){
    var cano = document.getElementById("ano");
    var cmes = document.getElementById("mes");
    var ano = cano.options[cano.selectedIndex].value;
    var mes = cmes.options[cmes.selectedIndex].value;
//    var cual = document.getElementById("cual");
//    var opt = cual.options[cual.selectedIndex].value;
    var opt = $( '#cual option:selected' ).val();
    var cad = $( '#cadena option:selected' ).val();
    if(opt=="pdf") {
        var w = window.open('detalleDePagoPDF.php?ano='+ano+'&mes='+mes,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    } else if(opt=="arc") {
        var x = window.open('arcPDF.php?ano='+ano,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    } else if(opt=="detalle_especial") {
        var y = window.open('detalleDePagoEspecialPDF.php?cadena='+cad,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    } else if(opt=="resumen" || opt=="aguinaldo" || opt=="bono" || opt=="fide") {
        var z = window.open('consultaNomina.php?cual='+opt+'&ano='+ano,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    } else if(opt=="eps") {
        var q = window.open('estimadoPrestacionesSocialesPDF.php','_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    }

}
function ventana0(){
    var nid = document.getElementById("nid");
    var cid = nid.value;
    var z = window.open('estimadoPrestacionesSocialesPDF.php?cid='+cid,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
}

function ventana3(){
    var cano = document.getElementById("ano");
    var cmes = document.getElementById("mes");
    var nid = document.getElementById("nid");
    var cid = nid.value;
    var ano = cano.options[cano.selectedIndex].value;
    var mes = cmes.options[cmes.selectedIndex].value;
    var cual = document.getElementById("cual");
    var opt = cual.options[cual.selectedIndex].value;
    var cad = $( '#cadena option:selected' ).val();
    switch(opt){
        case "pdf":
        case "contrato":
             bloquear();
             $.post("cuantosDetallesMes.php",'ano='+ano+'&mes='+mes+'&cid='+cid+'&opt='+opt,
                 function(rt){
                     var r = eval(rt);
                     var cantidad = r[0].cantidad;
                     var nm = r[0].nombre;
                     alert(nm+ " ya ha solicitado " +cantidad+" copias de detalles para el "+mes+" del "+ano);
                 }, "json");
             desbloquear();
            break;

        case "resumen":
        case "arc":
        case "deudas":
        case "bono":
        case "aguinaldo":
        case "eps":
        case "eips":
            break;	
    }
    switch(opt){
         case "detalle_especial":
            var y = window.open('detalleDePagoEspecialPDF.php?cadena='+cad+'&cid='+cid,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
            break;
        case "pdf":
            var w = window.open('detalleDePagoPDF.php?ano='+ano+'&mes='+mes+'&cid='+cid,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
            break;
        case "contrato":
            var x = window.open('detalleDePagoContratadoPDF.php?ano='+ano+'&mes='+mes+'&cid='+cid,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
            break;
        case "arc":
            var z = window.open('arcPDF.php?ano='+ano+'&cid='+cid,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
            break;
       case "eps":
         var q = window.open('constanciaEstimadoPrestacionesSocialesPDF.php?cid='+cid,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
         break;
    }
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
            $("#tipo_personal").hide();
            $("#tipo_p").hide();
             $("#boton_submit").hide();
             $("#boton").show();
            break;
        case "detalle_especial":
            $("#mes").hide();
            $("#ano").show();
            showEspecial(document.getElementById("ano"));
            $("#anomes").show();
            $("#cmbnomesp").show();
            $("#tipo_personal").hide();
            $("#tipo_p").hide();
             $("#boton_submit").hide();
             $("#boton").show();
            break;
        case "resumen":
        case "arc":
            $("#mes").hide();
            $("#ano").show();
            $("#anomes").show();
            $("#cmbnomesp").hide();
            $("#tipo_personal").hide();
            $("#tipo_p").hide();
             $("#boton_submit").hide();
             $("#boton").show();
            break;
        case "deudas":
        case "bono":
        case "aguinaldo":
        case "eps":
            $("#mes").hide();
            $("#ano").hide();
            $("#anomes").hide();
            $("#cmbnomesp").hide();
            $("#tipo_personal").hide();
            $("#tipo_p").hide();
             $("#boton_submit").hide();
             $("#boton").show();
            break;
        case "eips":
			 $("#mes").hide();
            $("#ano").hide();
            $("#anomes").hide();
            $("#cmbnomesp").hide();
            $("#tipo_personal").show();
            $("#tipo_p").show();
             $("#boton_submit").show();
             $("#boton").hide();
             
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
           timeout:10000,
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



