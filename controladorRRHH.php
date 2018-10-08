<?php
include_once("../FrameWork/Include/Defines.inc.php");

include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
include_once(LUZ_PATH_INCLUDE  . '/Cadena.inc.php');
include_once(LUZ_PATH_INCLUDE  . '/Miscelaneo.inc.php');
include_once(LUZ_PATH_INCLUDE  . '/FormItems.inc.php');
include_once(LUZ_PATH_SECURITY . "/verificaAccesoUsuario.inc.php");

session_start();
$ci       = $_SESSION["cedula"];
$accion   = getVar('accion');
$ano      = getVar('ano');
$mes      = getVar('mes');


/*if(!chkSesionAjax()){
    $accion = "noAjax";
}*/

switch($accion) {
    case "cmbnomesp":
//        $rs = array("err"=>0,"msg"=>leerCmbNominaEspecial($ci,$ano));
        $rs = array("err"=>0,"msg"=>leerCmbNominaEspecial2($ci,$ano,$mes));
        break;
    case "noAjax":
        $rs = array("err"=>1,"msg"=>"Sesi&oacute;n cerrada por inactividad recargue la pagina principal...");
        break;
    default:
        $rs = array("err"=>1,"msg"=>"funcion no soportada");
}
echo json_encode($rs);


function leerCmbNominaEspecial($ci,$ano){
    $db     = "sidial";
    $sql =  "SELECT * FROM VW_TIPO_NOMINA WHERE CE_TRABAJADOR = $ci and ANO = $ano and CO_TIPO_NOMINA not like '______002001' order by CO_TIPO_NOMINA";
    $ds     = new DataStore($db, $sql);
    if($ds->getNumRows()==0){
          $matriz_descripcion[] = "No Tiene definida n&oacute;mina especial";
          $matriz_tipo_nomina[] = "0";
    } else {
          $matriz_descripcion = $ds->getValuesCol("DE_TIPO_NOMINA");
          $matriz_tipo_nomina = $ds->getValuesCol("CO_TIPO_NOMINA");
    }
    for($i=0;$i<count($matriz_descripcion);$i++){
        $matriz_descripcion[$i] = str_replace("AÑO: ".$ano." - ","",utf8_encode($matriz_descripcion[$i]));
    }

    return '<div class="col-lg-3"></div>'.frm_select('cadena',$matriz_descripcion , $matriz_tipo_nomina," ",'class="form-control"');

}
function leerCmbNominaEspecial2($ci,$ano,$mes){
    $db     = "sidial";
    if(strlen($mes)==1) {
        $mes = '0'.$mes;
    }
    $sql =  "SELECT * FROM VW_TIPO_NOMINA WHERE CE_TRABAJADOR = $ci and ANO = $ano and CO_TIPO_NOMINA like '".$ano.$mes."______' and CO_TIPO_NOMINA not like '______002001' order by CO_TIPO_NOMINA";
    $ds     = new DataStore($db, $sql);
    if($ds->getNumRows()==0){
          $matriz_descripcion[] = "No Tiene definida n&oacute;mina especial";
          $matriz_tipo_nomina[] = "0";
    } else {
          $matriz_descripcion = $ds->getValuesCol("DE_TIPO_NOMINA");
          $matriz_tipo_nomina = $ds->getValuesCol("CO_TIPO_NOMINA");
    }
    for($i=0;$i<count($matriz_descripcion);$i++){
        $matriz_descripcion[$i] = str_replace("AÑO: ".$ano." - ","",utf8_encode($matriz_descripcion[$i]));
    }

    return '<div class="col-lg-3"></div>'.frm_select('cadena',$matriz_descripcion , $matriz_tipo_nomina," ",'class="form-control"');

}
