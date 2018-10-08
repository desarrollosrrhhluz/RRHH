$(document).ready(inicializarEventos);
var valor;
function inicializarEventos() {
    $("#nomesp").hide();
     $("#cmbnomesp").hide();

             

//**************trabajar los años

//******************************
}
function bloquear() {
    mensaje('Procesando...<br/><img width="100%" height="80px" src="images/progressbar.gif">','info');
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

function ventana(){
    var cano = document.getElementById("ano");
    var cmes = document.getElementById("mes");
    var ano = cano.options[cano.selectedIndex].value;
    var mes = cmes.options[cmes.selectedIndex].value;
    var opt = $( '#cual option:selected' ).val();
    var cad = $( '#cadena option:selected' ).val();
    if(opt=="pdf") {
        var w = window.open('detalleDePagoPDF.php?ano='+ano+'&mes='+mes,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    } else if(opt=="arc") {
        var x = window.open('arcPDF.php?ano='+ano,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    } else if(opt=="detalle_especial") {
        var y = window.open('detalleDePagoEspecialPDF.php?cadena='+cad,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    } else if(opt=="eps") {
        var q = window.open('estimadoPrestacionesSocialesPDF.php','_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    } else if(opt=="ccu3") {
        var m = window.open('detalleDePagoCCU3PDF.php?ano='+ano+'&mes='+mes,'_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
    }
}

function showConsulta(e){
    valor = e.options[e.selectedIndex].value;
    switch(valor){
        case "pdf":
            $("#mes").show();
            $("#cmbnomesp").hide();
        
            // $("#ano option:first").before("'<option value="+ano+" selected='selected'>"+ano+"</option>'");
            anos();
           
            break;
        case "detalle_especial":
            anos();
            //$("#mes").hide();
            //showEspecial(document.getElementById("ano"));
            $("#mes").show();
            showEspecial(document.getElementById("ano"),document.getElementById("mes"));
            
            $("#cmbnomesp").show();
            break;
        case "arc":
            $("#mes").hide();
            $("#cmbnomesp").hide();
            $('#ano > option:nth-child(1)').remove();
         
            break;
    }
}

function showEspecial(e,f){
    var ano = e.options[e.selectedIndex].value;
    var mes = f.options[f.selectedIndex].value;
    bloquear();
    $.post("controladorRRHH.php","accion=cmbnomesp&ano="+ano+"&mes="+mes,
        function(rt){
            var rs = JSON.parse(rt);
            $("#cmbnomesp").html(rs.msg);
        }, "text");
    desbloquear();
    return false;
}

function anos(){
    $('#ano > option').remove();
var ano = new Date().getFullYear();
          
          var cadena=""
           for (var i = ano   ; i >=ano -5 ; i--) {
            console.log(i);
           
           cadena= cadena +"<option value="+i+" >"+i+"</option>";
         
           }
            $("#ano").html(cadena);

}



