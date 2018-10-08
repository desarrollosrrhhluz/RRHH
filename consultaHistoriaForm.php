<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : cons_nomina.php
		Autor               : Ing. Miguel Briceño
		Fecha Creaci?n      : 25/02/2008
		Objetivo / Resumen  : Demostrar funcionamiento de las nuevas clases
		Ultima Modificaci?n : 23/04/2008
------------------------------------------------------------------------------------------------ */
//------------------------------------------------------------------------
// ejemplo basico de uso de las clases de pagina,consulta y tablatabla
//------------------------------------------------------------------------
//include_once("../Seguridad/verificaSesionMambo.inc.php");
//include_once("../Seguridad/verificaSesion.inc.php");
    include_once("../Seguridad/verificaSesionRoles.inc.php");
$enable = true;
$urlError = "errorRRHH.html";
$urlDeshabilitado = "deshabilitadoRRHH.html";
$rolesPermitidos = array(2,3,4);//array("2","3","4");
$appName = 'RRHH';
verificaActivo($appName, $urlDeshabilitado);
verificaSesion();
//verificaSesion($enable, $urlDeshabilitado);
verificaRoles($rolesPermitidos, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];



//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
include_once("../FrameWork/Class/PaginaWeb.class.php");
include_once("../FrameWork/Class/Formulario.class.php");
include_once("../FrameWork/Class/DataStore.class.php");
include_once("../FrameWork/Include/FormItems.inc.php");
include_once("../FrameWork/Include/Funciones.inc.php");

//------------------------------------------------------------------------------ se defines los argumentos de la pagina web
$js = array("../FrameWork/js/jquery.js","js/consultaNomina.js","../FrameWork/js/funcion.js"); //------------------------------------------------ archivos javascript
$css = array("css/estructuras.css","css/formularios.css"); //--------------- plantillas de estilos
$pagina = new PaginaWeb("Oscar Amesty","Consulta de Historia Laboral",$js,$css); //----------------- instancia de PaginaWeb
$cuerpo="";   //---------------------------------------------------------------- variable para almacenar el contenido
//------------------------------------------------------------------------------- aqui va el trabajo con la sesion
session_start();
include_once("menu.inc.php");
$cuerpo ='<noscript><meta http-equiv="refresh" content="0; URL=/RRHH/errorJs.html" /></noscript> <div id="cabecera"><div id="logotipo"></div><div id="publicidad">Sistema de Servicios en L&iacute;nea de la Universidad del Zulia - Consulta de N&oacute;mina</div></div>';
//$cuerpo .= '<div class="contenedor">';
$cuerpo .= menu_0("N");
$cuerpo .='<div id="c3" style="position:absolute; left:40%; top:40%; background-color:red; width:200px; height:40px"><strong>Error con el servidor...</strong></div>
<div id="c2" style="position:absolute; left:45%; top:45%; background-color:yellow; width:100px; height:20px">Cargando...</div>
<div id="c1" style="position:absolute; left:0px; top:0px; width:100%; height:100%"></div>';
$atributos= "";

//$form = new Formulario("forma01","post","consultaNomina.php","",$atributos); //---------------------------------------------- crea instancia para formulario
$form = new Formulario("forma01","post","../reportes_excel/historia_laboral.php","",$atributos); //---------------------------------------------- crea instancia para formulario
	$form->setFieldSet("Consulta de Historia Laboral de Jubilados");
	$j=0;
	for($i=1975;$i<=2011;$i++)
	{
		$ano[$j]=$i;
		$j++;	
	}
	if($_GET['error']==1)
	{
		echo "<script>alert('Datos no encontrados');</script>";	
		
	}	
	
	
	//$ano = anos();
	$tipo_personal=array("Administrativo","Docente");
	$tipo_personal1=array(2,1);
	$form->addField("","<label id='tipo_personal'>Tipo de Personal:</label>".frm_select('tipo_personal', $tipo_personal, $tipo_personal1, "", 'class="fs" onchange="showEspecial(this)"') );
	//$form->addField("","<label id='anomes'>A&ntilde;o:</label>".frm_select('anio', $ano, $ano, "", 'class="fs" onchange="showEspecial(this)"') );
	$form->addField("",'<div id="cmbnomesp"></div>');
	$form->addField("",'<input id="boton"  type="submit" name="submit" value="Consultar"/>');
$cuerpo .= $form->getForm(); //------------------------------------------------- coloca el formulario en el contenido

//------------------------------------------------------------------------------
$cuerpo .='<div id="pie">2010. Universidad del Zulia - RRHH - Diticluz</div>';

$pagina->setBody($cuerpo); //------------------------------------------------------- asigna el contenido de la pagina
$pagina->showPage(); //------------------------------------------------------------- presenta la pagina completa
?>
