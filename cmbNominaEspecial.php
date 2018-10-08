<?php
    header('Content-Type: text/html; charset=iso-8859-1');
include_once("../FrameWork/Include/Defines.inc.php");
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once(LUZ_PATH_INCLUDE.'/Funciones.inc.php');
include_once(LUZ_PATH_INCLUDE.'/FormItems.inc.php');

    $ano= getVar("ano");
    session_start();
    $ci = $_SESSION["cedula"];
    $db     = "sidial";
    $sql =  "SELECT * FROM VW_TIPO_NOMINA WHERE CE_TRABAJADOR = $ci and ANO = $ano and CO_TIPO_NOMINA not like '______002001'  order by CO_TIPO_NOMINA";
    $ds     = new DataStore($db, $sql);
    if($ds->getNumRows()==0){
          $matriz_descripcion = "No Tiene definida n&oacute;mina especial";
          $matriz_tipo_nomina = "0";
    } else {
          $matriz_descripcion = $ds->getValuesCol("DE_TIPO_NOMINA");
          $matriz_tipo_nomina = $ds->getValuesCol("CO_TIPO_NOMINA");
    }
    for($i=0;$i<count($matriz_descripcion);$i++){
        $matriz_descripcion[$i] = str_replace("A&ntilde;O: ".$ano." - ","",$matriz_descripcion[$i]);
    }

    echo '<label>N&oacute;mina especial:</label>'.frm_select('cadena',$matriz_descripcion , $matriz_tipo_nomina,'','class=fs');
?>
