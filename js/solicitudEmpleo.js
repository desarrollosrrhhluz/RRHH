$(document).ready(inicializarEventos);
var arrDP = new Array('id_personal','da_tipo_documento','co_documento','da_nacionalidad','da_nombre1','da_nombre2','da_apellido1','da_apellido2','da_sexo','fe_nacimiento','da_lugar_nacimiento','da_direccion','da_lugar','da_edo_civil','da_telefono');
var arrCU = new Array('id_curso','id_personal','da_descripcion','nu_duracion','da_participacion','fe_inicio','fe_final','da_lugar','da_patrocinante');
var arrFM = new Array('id_familiar','id_personal','da_nombre','da_parentesco','fe_nacimiento','da_sexo','co_documento', 'co_cedula_otro_padre');
var arrED = new Array('id_educacion','id_personal','da_nivel','da_instituto','da_lugar','fe_inicio','fe_final','da_titulo','da_estado','da_observacion');
var arrEX = new Array('id_exp','id_personal','da_lugar_trabajo','da_lugar','da_cargo','fe_inicio','fe_final','da_descripcion_trabajo');
var contador = 0;

function inicializarEventos() {
    $("a[rel]").overlay();
    $(".invisible").hide();
    $(".ver").hide();

    $("#saveDP").click(function(){guardar("DP");});
    $("#saveFM").click(function(){guardar("FM");});
    $("#saveED").click(function(){guardar("ED");});
    $("#saveEX").click(function(){guardar("EX");});
    $("#saveCU").click(function(){guardar("CU");});

    $("#delFM").click(function(){eliminar("FM");});
    $("#delED").click(function(){eliminar("ED");});
    $("#delEX").click(function(){eliminar("EX");});
    $("#delCU").click(function(){eliminar("CU");});

    $("#enviarEM0").hide();
    $("#cancelDP").click(function(){ verDatos("DP"); vision("DP");});
    $("#cancelFM").click(function(){ verDatos("FM"); vision("FM");});
    $("#cancelED").click(function(){ verDatos("ED"); vision("ED");});
    $("#cancelEX").click(function(){ verDatos("EX"); vision("EX");});
    $("#cancelCU").click(function(){ verDatos("CU"); vision("CU");});
    activarDiv();
}

function vision(tbl){
    var division = cualDiv(tbl);
    $("#edit"+division).hide();
    $("#ver"+division).show();
    $("#btnver"+division).show();
}

function edicion(tbl){
    var division = cualDiv(tbl);
    $("#edit"+division).show();
    $("#ver"+division).hide();
    $("#btnver"+division).hide();
}

function guardar(tbl){
    if(confirm('¿Está seguro de los datos escritos?')){
        guardarDatos(tbl);
        verDatos(tbl);
        vision(tbl);
        if(tbl=="DP" || tbl=="ED" || tbl=="EX") {
            document.location.href = document.location.href;
        }
    }
}
function eliminar(tbl){
    if(confirm('¿Está seguro que desea eliminar esta información?')){
        eliminarDatos(tbl);
        verDatos(tbl);
        vision(tbl);
        if(tbl=="ED" || tbl=="EX") {
            document.location.href = document.location.href;
        }
    }
}

function enviarSolicitud1(){
    if(confirm('Primera confirmación: ¿Está seguro de enviar solicitud?, despues NO podra cambiar los datos del formulario')){
        if(confirm('Última confirmación: ¿Está seguro de enviar solicitud?')){
            bloquear();
            var id = $("#editdatospersonales #id_personal").attr("value");
            var tipo = $("#editempleo #tipooferta").attr("value");
            var num = $("#num_cbx").attr("value");
            var areas = "";
            var j=0;
            for (var i = 0; i < num; i++){
                if($("#cbxGrupo"+i+"").attr("checked")) {
                    areas = areas + "&co_area"+j+"="+$("#cbxGrupo"+i+"").attr("value");
                    j++;
                }
            }
            $.post("guardarSolicitudEmpleo.php","id_personal="+id+"&tipo="+tipo+"&j="+j+areas,
                    function(rt){ 
                        alert(rt);
                        desbloquear();
                    }, "text");
        document.location.href = document.location.href;
        }
    }
}

function editar(tabla,id){
    leerDatos(tabla,id);
    edicion(tabla);
}

function agregar(tabla){
    limpiarCampos(tabla);
    propagarID($("#editdatospersonales #id_personal").attr("value"));
    edicion(tabla);
}

function limpiarCampos(tbl){
    var arreglo = new Array();
    switch(tbl){
        case "DP":
            arreglo = arrDP;
            break;
        case "FM":
            arreglo = arrFM;
            break;
        case "ED":
            arreglo = arrED;
            break;
        case "EX":
            arreglo = arrEX;
            break;
        case "CU":
            arreglo = arrCU;
            break;
        case "EM":
            arreglo = arrEM;
            break;
    }
    for (var i = 0; i < arreglo.length; i++) {
        var campo = arreglo[i];
        $("#edit"+cualDiv(tbl)+" #"+campo).attr("value","");
    }
}

function verDatos(tbl){
    bloquear();
    $.post("datosCV.php", "tabla="+tbl, function(texto){ $("#ver"+cualDiv(tbl)).html(texto);desbloquear(); }, "text");
}

function leerDatos(tbl,id){
    bloquear();
    $.post("buscarDatos.php","frame="+tbl+"&id="+id,
        function(rt){
            var arreglo = eval(rt);
            for (var i = 0; i < arreglo.length; i++) {
                var campo = arreglo[i].campo;
                var valor = arreglo[i].valor;
                $("#edit"+cualDiv(tbl)+" #"+campo).attr("value",valor);
            }
            desbloquear();
        }, "json");
}

function buscarRegistro(ci){
    bloquear();
    $.post("buscarDatosRegistro.php","ci="+ci,
        function(rt){
            var arreglo = eval(rt);
            for (var i = 0; i < arreglo.length; i++) {
                var campo = arreglo[i].campo;
                var valor = arreglo[i].valor;
                $("#editdatospersonales #"+campo).attr("value",valor);
            }
            desbloquear();
        }, "json");
}

function guardarDatos(tbl){
    bloquear();
    var vJson = null;
    var arreglo = new Array();
    switch(tbl){
        case "DP":
            arreglo = arrDP;
            break;
        case "FM":
            arreglo = arrFM;
            break;
        case "ED":
            arreglo = arrED;
            break;
        case "EX":
            arreglo = arrEX;
            break;
        case "CU":
            arreglo = arrCU;
            break;
    }

    vJson = "";
    for (var i = 0; i < arreglo.length; i++) {
        var campo = arreglo[i];
        var valor = $("#edit"+cualDiv(tbl)+" #"+campo).attr("value");
        var tJson = campo+'='+valor;
        vJson = vJson + tJson + '&';
    }
    vJson = vJson.substring(0,vJson.length-1);
	
    $.post("guardarDatos.php","tbl="+tbl+"&"+vJson,
            function(rt){ alert(rt);  }, "text");
}
function eliminarDatos(tbl){
    bloquear();
    var arreglo = new Array();
    switch(tbl){
        case "FM":
            arreglo = arrFM;
            break;
        case "ED":
            arreglo = arrED;
            break;
        case "EX":
            arreglo = arrEX;
            break;
        case "CU":
            arreglo = arrCU;
            break;
    }
    var valorid = $("#edit"+cualDiv(tbl)+" #"+arreglo[0]).attr("value");
    $.post("eliminarDatos.php","tbl="+tbl+"&id="+valorid,
            function(rt){ alert(rt); desbloquear(); }, "text");
}

function activarDiv(){
    bloquear();
    $.post("cualesDatosTienes.php", "", activarDivSuccess, "json");
}

function lzverificarOferta(arreglo){
    var lz = false;
    for(var i = 0; i < arreglo.length; i++) {
        var item = arreglo[i].tdiv;
        if(item=="ED" || item=="EX") lz= true;
    }
    if(lz) verificarOferta(arreglo[arreglo.length -1].id_personal);
}

function activarDivSuccess(rt){
    var arreglo = eval(rt);
    var desactiva = false;
    if(arreglo.length>1) propagarID(arreglo[1].id_personal);
    if(arreglo.length==1) buscarRegistro(arreglo[0].ci);
    $("#editempleo").hide();
    $("#verempleo").hide();
    $("#btnverempleo").hide();
    for(var i = 0; i < arreglo.length; i++) {
        var item = arreglo[i].tdiv;
        var ci = arreglo[i].ci;
        if(item=="00") $("#editdatospersonales #co_documento").attr("value",ci)
        else{
            verDatos(item);
            vision(item);
            if(item=="EM") desactiva = true;
        }
    }
    if(arreglo.length>1 && !desactiva) lzverificarOferta(arreglo);
    if(desactiva) desactivarBotones();
    desbloquear();
}

function desactivarBotones(){
    $(".botonera").hide();
    $("#principal").html("Ya realizó su solicitud NO puede modificar los datos");
    $(".edit").hide();
    $(".ver").show();
}

function propagarID(id_personal){
    var tbl = new Array("DP","ED","EX","CU","FM");
    for (var i = 0; i < tbl.length; i++) {
        $("#edit"+cualDiv(tbl[i])+" #id_personal").attr("value",id_personal);
    }
}

function cualDiv(tbl){
    var division = null;
    switch(tbl){
        case "DP":
            division = "datospersonales";
            break;
        case "FM":
            division = "familiares";
            break;
        case "ED":
            division = "educacion";
            break;
        case "EX":
            division = "experiencia";
            break;
        case "RE":
            division = "reconocimientos";
            break;
        case "CU":
            division = "cursos";
            break;
        case "EM":
            division = "empleo";
            break;
    }
    return division;
}

function activarAreas(id_personal){
    var anoex = 0;
    var mesex = 0;
    bloquear();
    $.post("tiempoExperiencia.php", "id_personal="+id_personal,
        function(rt){
            var arreglo = eval(rt);
            anoex = arreglo.anoexp;
            mesex = arreglo.mesexp;
            $.post("divEmpleo.php", "id_personal="+id_personal+"&anoexp="+anoex+"&mesexp="+mesex,
                    function(rt){ $("#editempleo").html(rt);
                           desbloquear();
                    }, "text");
            $("#editempleo").show();
        }, "json");
}

function activarAreasO(id_personal){
    var anoex = 0;
    var mesex = 0;
    bloquear();
    $.post("tiempoExperiencia.php", "id_personal="+id_personal,
        function(rt){
            var arreglo = eval(rt);
            anoex = arreglo.anoexp;
            mesex = arreglo.mesexp;
            $.post("divEmpleoO.php", "id_personal="+id_personal+"&anoexp="+anoex+"&mesexp="+mesex,
                    function(rt){ $("#editempleo").html(rt);
                        desbloquear();
                    }, "text");
            $("#editempleo").show();
        }, "json");
}

function verificarOferta(id_personal){
    bloquear();
    $.post("nivelEducacion.php", "id_personal="+id_personal,
            function(rt){ 
                if(rt=="2") {
                    var cadena = '<br/><br/><br/><div class="contenedor botonera"><div class="izq">';
                    cadena = cadena + '<a class="boton" id="obrero" onclick="activarAreasO('+id_personal+')">Ofrecer como personal obrero</a>';
                    cadena = cadena + '</div><div class="der"><a class="boton" id="administrativo" onclick="activarAreas('+id_personal+')">Ofrecer como personal administrativo</a></div></div>';
                    $("#editempleo").html(cadena);
                    $("#editempleo").show();
                    desbloquear();

                } else {
                    activarAreas(id_personal);
                }
            }, "text");
}

//------------------------------------------------- bloqueo de pantalla
function bloquear() {
    $("#capaMsj").html('<img src="images/processingbar.gif"><br/>Cargando...');
//    $("#capaMsj").html('<img src="images/spinner.gif"><br/>Cargando...');
//    $("#capaMsj").html('<img src="images/loader.gif"><br/>Cargando...');
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

function activaAcepto() {
    if( $("#declaracionVeracidad").attr("checked") &&
        $("#condiciontramite").attr("checked")){
        $("#enviarEM0").show();
    }else
        $("#enviarEM0").hide();

}

function validaAcepto(check) {
    if(contador==0){
        alert('Debe seleccionar por lo menos un área de oferta');
        check.checked=false;
    } else {
        if( $("#declaracionVeracidad").attr("checked") &&
            $("#condiciontramite").attr("checked")){
            $("#enviarEM0").show();
        }else
            $("#enviarEM0").hide();
    }

}

function llenaAyuda(tbl){
    $("#myOverlay").html('<div class="contenido">'+$("#ayuda"+tbl+"").html()+'</div>');
}

function validarCheckbox(check) {
   var maximo = 100;
   if (check.checked==true){
       contador++;
       if (contador>maximo) {
          alert('No se pueden elegir más de '+maximo+' casillas a la vez.');
          check.checked=false;
          contador--;
       }
   }else {
       contador--;
   }
}

