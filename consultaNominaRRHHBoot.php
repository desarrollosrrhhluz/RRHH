<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : gestionSeguridad.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creaci&oacute;n      : 08/06/2011
		Objetivo / Resumen  : administraci&oacute;n de seguridad web
		Ultima Modificaci&oacute;n : 07/06/2011
------------------------------------------------------------------------------------------------ */

header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHDP';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];
include_once(LUZ_PATH_INCLUDE  . '/FormItems.inc.php');
include_once(LUZ_PATH_INCLUDE  . '/Fecha.inc.php');
/*include_once(LUZ_PATH_SECURITY . "/verificaAccesoUsuario.inc.php");

$appName         ='RRHH';
$urlError        = "errorRRHH.html";
$urlDeshabilitado = "deshabilitadoRRHH.html";
verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);*/

$cual = array("","pdf"            ,"detalle_especial"       , "arc", "eps","ccu3"                              );
$tcual= array("-Seleccione-","Detalle de pago", "N&oacute;mina especial", "ARC", "ESTIMADO de Prestaciones Sociales","Conceptos de CCU3");
$ano    = anos();
$meses  = meses();
$nmeses = nmeses();

 $ano=array_splice($ano, 1);
?>
<!--<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="oamesty" >
    <link href="css/bootstrap.css" rel="stylesheet">
  </head>
  <body>
    <noscript><meta http-equiv="Refresh" content="0;url=errorJavaScript.html"/></noscript>
    <header class="navbar navbar-default navbar-fixed-top">-->
      <div class="container">    
    <main id="wrap" class="container">
      <div id="apps" class="form-app">
      <fieldset><legend> <h2 class="form-signin-heading"><img src="images/coins.png" width="32" height="32" /> Mi N&oacute;mina</h2></legend>
    <div class="row"> <div class="col-md-2"><label id='consulta'>Consulta:</label> </div><div class="col-md-6">   <?php echo frm_select('cual', $tcual, $cual, "", "class=\"form-control\" onchange=\"showConsulta(this)\" placeholder=\"Seleccione opci&oacute;n\" autofocus"); ?></div></div>
	<div class="row"><div class="col-md-2"><label id='anomes'>A&ntilde;o / mes:</label> </div>
     <div class="col-md-6">   <?php echo frm_select('ano', $ano, $ano, "", "class=\"form-control\" onchange=\"showEspecial(document.getElementById('ano'),document.getElementById('mes'))\""). frm_select('mes', $meses, $nmeses, "", "class=\"form-control\" onchange=\"showEspecial(document.getElementById('ano'),document.getElementById('mes'))\""); ?></div></div>
       <div class="row"><div class="col-md-2"></div> <div id="cmbnomesp" class="col-md-6"></div></div>
        <div class="row">
        <div class="col-md-2"></div><div class="col-md-6"><input id="boton" onclick="ventana()" class="form-control btn btn-success " type="button" name="submit" value="Consultar"/></div>
     </div>
      </div>
      </fieldset>
    </main>
<div class="modal fade" id ="mdMsg">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      </div>
      <div id="divMsg" class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script src="js/rrhh.js"></script>
 