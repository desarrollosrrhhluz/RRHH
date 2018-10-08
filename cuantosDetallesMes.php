<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : cuantosDetallesmes.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creación      : 25/02/2008
		Objetivo / Resumen  : Indica cantidad de detalles impresos
		Ultima Modificación : 23/04/2008
------------------------------------------------------------------------------------------------ */

//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
include_once("../FrameWork/Class/DataStore.class.php");
include_once("../FrameWork/Include/Funciones.inc.php");
session_start();
$ci = getvar("cid");;
$mes = getvar("mes");
$ano = getvar("ano");
//------------------------------------------------------ se define los elementos de una consulta
$db		= "rrhh";
$sql = "select id, fe_solicitud from tra_validador_documento where da_ano = $ano and da_mes = $mes and ce_trabajador = $ci and da_tipo_tramite='DP'";
$ds		= new DataStore($db, $sql);
if($ds->getNumRows()>0) {
    $cant   = $ds->getNumRows();
    $db	    = "sidial";
    $sql    = "select distinct NOMBRES from VW_SUMA_ASIGNACIONES WHERE CE_TRABAJADOR =$ci";
    $ds2    = new DataStore($db, $sql);
    $values = $ds2->getValues(0);
    $nm     = $values["NOMBRES"];
    $arr[]  = array("cantidad"=>"$cant","nombre"=>"$nm");
} else $arr[]  = array("cantidad"=>"0","nombre"=>"");


header("Content-type: application/json");
    echo '('.json_encode($arr).')';
?>