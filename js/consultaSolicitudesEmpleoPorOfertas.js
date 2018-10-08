$(document).ready(inicializarEventos);

function inicializarEventos() {
    $("a[rel]").overlay();
    $(".invisible").hide();
    $("#go").click(function(){leerAreas();});

}

function consultarOfertas(){
    bloquear();
    var nivel = $('#da_nivel option:selected').val();
    var tipo = $('#da_tipo option:selected').val();
    var estado = $('#da_estado option:selected').val();
    var num = $("#num_cbx").attr("value");
    var areas = "";
    var j=0;
    for (var i = 0; i < num; i++){
        if($("#cbxGrupo"+i+"").attr("checked")) {
            areas = areas + "&co_area"+j+"="+$("#cbxGrupo"+i+"").attr("value");
            j++;
        }
    }
    var caracteristicas = "height=700,width=800,scrollTo,resizable=1,scrollbars=1,location=0";
    var nw = window.open("consultaSolicitudesEmpleoPorOfertas.php?tipo="+tipo+"&nivel="+nivel+"&estado="+estado+"&j="+j+areas, 'Popup', caracteristicas);

/*    $.post("consultaSolicitudesEmpleoPorOfertas.php","tipo="+tipo+"&nivel="+nivel+"&estado="+estado+"&j="+j+areas,
        function(rt){ $("#reporte").html(rt);}, "text");
*/
desbloquear();
}

function leerAreas(){
    var nivel = $('#da_nivel option:selected').val();
    var tipo = $('#da_tipo option:selected').val();
    var estado = $('#da_estado option:selected').val();
    bloquear();
    $.post("leerAreasOferta.php", { ni: nivel, es: estado, ti: tipo },
        function(rt){ $("#areas").html(rt);}, "text");
    desbloquear();
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

function ayuda(){
    $("#myOverlay").html('<div class="contenido">'+$("#ayuda").html()+'</div>');
}