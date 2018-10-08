<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : cons_nomina.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creaci�n      : 25/02/2008
		Objetivo / Resumen  : Demostrar funcionamiento de las nuevas clases
		Ultima Modificaci?n : 23/04/2008
------------------------------------------------------------------------------------------------ */
include_once("../Seguridad/verificaSesionRoles.inc.php");
$enable          = false;
$appName         ='EPS';
$urlError        = "errorRRHH.html";
$urlDeshabilitado = "deshabilitadoRRHH.html";
$rolesPermitidos = array(14);
verificaActivo($appName, $urlDeshabilitado);
verificaSesion();
verificaRoles($rolesPermitidos, $urlError);

//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
include_once("../FrameWork/Class/PaginaWeb.class.php");
include_once("../FrameWork/Class/Formulario.class.php");
include_once("../FrameWork/Class/DataStore.class.php");
include_once("../FrameWork/Include/FormItems.inc.php");
include_once("../FrameWork/Include/Funciones.inc.php");

//------------------------------------------------------------------------------ se defines los argumentos de la pagina web
$js = array("../FrameWork/js/jquery.js","js/consultaNomina.js","../FrameWork/js/funcion.js"); //------------------------------------------------ archivos javascript
$css = array("css/estructuras.css","css/formularios.css"); //--------------- plantillas de estilos
$pagina = new PaginaWeb("Oscar Amesty","Consulta Estimado de Prestaciones Sociales",$js,$css); //----------------- instancia de PaginaWeb
$cuerpo="";   //---------------------------------------------------------------- variable para almacenar el contenido
//------------------------------------------------------------------------------- aqui va el trabajo con la sesion
session_start();
$cuerpo = 
$atributos= "";
$cuerpo.='<div id="c3" style="position:absolute; left:40%; top:40%; background-color:red; width:200px; height:40px"><strong>Error con el servidor...</strong></div>
<div id="c2" style="position:absolute; left:45%; top:45%; background-color:yellow; width:100px; height:20px">Cargando...</div>
<div id="c1" style="position:absolute; left:0px; top:0px; width:100%; height:100%"></div>';
$atributos= "";

$cuerpo.='        <fieldset>
            <legend>Consulta Estimado Prestaciones Sociales</legend>
            <label>C&eacute;dula de identidad:</label>
                <input type="text" name="nid" id="nid" size="10" maxlength="10" class="fif" onchange ="return ventana0()" />
				<input id="boton" onClick ="return ventana0()" type="button" value="Consultar">
        </fieldset>';
$pagina->setBody($cuerpo); //------------------------------------------------------- asigna el contenido de la pagina
$pagina->showPage(); //------------------------------------------------------------- presenta la pagina completa
?>
