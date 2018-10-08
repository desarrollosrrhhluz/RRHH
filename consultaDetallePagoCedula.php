<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : cons_nomina.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creaci�n      : 25/02/2008
		Objetivo / Resumen  : Demostrar funcionamiento de las nuevas clases
		Ultima Modificaci?n : 23/04/2008
------------------------------------------------------------------------------------------------ */
//------------------------------------------------------------------------
// ejemplo basico de uso de las clases de pagina,consulta y tablatabla
//------------------------------------------------------------------------
//include_once("../Seguridad/verificaSesionMambo.inc.php");
//include_once("../Seguridad/verificaSesion.inc.php");

//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
include_once(LUZ_PATH_CLASS    . '/PaginaWeb.class.php');
include_once(LUZ_PATH_CLASS    . '/Formulario.class.php');
include_once(LUZ_PATH_INCLUDE  . '/FormItems.inc.php');
include_once(LUZ_PATH_INCLUDE  . '/Funciones.inc.php');
include_once(LUZ_PATH_SECURITY . "/verificaAccesoUsuario.inc.php");
//------------------------------------------------------------------------------- aqui va el trabajo con la sesion
$appName         ='RRHH';
$urlError        = "errorRRHH.html";
$urlDeshabilitado = "deshabilitadoRRHH.html";
session_start();
verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
//------------------------------------------------------------------------------ se defines los argumentos de la pagina web
$js = array("../FrameWork/js/jquery.js","js/consultaNomina.js","../FrameWork/js/funcion.js"); //------------------------------------------------ archivos javascript
$css = array("css/estructuras.css","css/formularios.css"); //--------------- plantillas de estilos
$pagina = new PaginaWeb("Oscar Amesty","Consulta Detalle de Pago",$js,$css); //----------------- instancia de PaginaWeb
$cuerpo="";   //---------------------------------------------------------------- variable para almacenar el contenido

$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];
$cuerpo .='<div id="c3" style="position:absolute; left:40%; top:40%; background-color:red; width:200px; height:40px"><strong>Error con el servidor...</strong></div>
<div id="c2" style="position:absolute; left:45%; top:45%; background-color:yellow; width:100px; height:20px">Cargando...</div>
<div id="c1" style="position:absolute; left:0px; top:0px; width:100%; height:100%"></div>';

$form = new Formulario("forma01","post","consultaDetallePagoCedula.php","",$atributos); //---------------------------------------------- crea instancia para formulario
	$form->setFieldSet("Consulta Detalle de Pago");
//	$cual=array("pdf","contrato","arc","eps");
//	$tcual=array("Detalle de pago","Detalle de pago (contratados)", "ARC","Constancia de Estimado de Prestaciones");
	$cual=array("pdf","detalle_especial","contrato","arc");
	$tcual=array("Detalle de pago", "N&oacute;mina especial","Detalle de pago (contratados)", "ARC");
	$form->addField("C&eacute;dula:",frm_text('nid', "", 10, 12, 'class=fif'));
	$form->addField("Consulta:",frm_select('cual', $tcual, $cual, "", 'class=fs onchange="showConsulta(this)"'));
	$ano = anos();
	$meses = meses();
	$nmeses = nmeses();
	$form->addField("","<label id='anomes'>A&ntilde;o / mes:</label>".frm_select('ano', $ano, $ano, "", 'class=fs onchange="showEspecial(this)"') . frm_select('mes', $meses, $nmeses, "", 'class=fs'));
	$form->addField("",'<div id="cmbnomesp"></div>');
	$form->addField("",'<input id="boton" onClick ="return ventana3()" type="button" value="Consultar">');
$cuerpo .= $form->getForm(); //------------------------------------------------- coloca el formulario en el contenido
//------------------------------------------------------------------------------
$pagina->setBody($cuerpo); //------------------------------------------------------- asigna el contenido de la pagina
$pagina->showPage(); //------------------------------------------------------------- presenta la pagina completa
?>
