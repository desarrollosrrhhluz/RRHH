<?php
//error_reporting(0);
/*  --------------------------------------------------------------------------------------------
		Nombre              : cons_nomina.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creaci?n      : 25/02/2008
		Objetivo / Resumen  : consulta de información de nomina
		Ultima Modificaci?n : 15/01/2013
------------------------------------------------------------------------------------------------ */
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
include_once(LUZ_PATH_CLASS    . '/PaginaWeb.class.php');
include_once(LUZ_PATH_CLASS    . '/Formulario.class.php');
include_once(LUZ_PATH_INCLUDE  . '/FormItems.inc.php');
include_once(LUZ_PATH_INCLUDE  . '/Funciones.inc.php');
include_once(LUZ_PATH_SECURITY . "/verificaAccesoUsuario.inc.php");

$appName         ='RRHH';
$urlError        = "errorRRHH.html";
$urlDeshabilitado = "deshabilitadoRRHH.html";
verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);

$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];


//------------------------------------------------------------------------------ se defines los argumentos de la pagina web
$js = array("../FrameWork/js/jquery.js","js/consultaNomina.js","../FrameWork/js/funcion.js"); //------------------------------------------------ archivos javascript
$css = array("css/estructuras.css","css/formularios.css"); //--------------- plantillas de estilos
$pagina = new PaginaWeb("Oscar Amesty","Consulta de N&oacute;mina",$js,$css); //----------------- instancia de PaginaWeb
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
$form = new Formulario("forma01","post","#","",$atributos); //---------------------------------------------- crea instancia para formulario
$form->setFieldSet("Consulta de Mi n&oacute;mina");

//---------------------------------------------todas las opciones habiles
//	$cual = array("pdf"            ,"detalle_especial"       , "arc", "eps"                              , "eips");
//	$tcual= array("Detalle de pago", "N&oacute;mina especial", "ARC", "ESTIMADO de Prestaciones Sociales", "ESTIMADO de Intereses Prestaciones Sociales");

	$cual = array("pdf"            ,"detalle_especial"       , "eps"                              , "eips");
	$tcual= array("Detalle de pago", "N&oacute;mina especial", "ESTIMADO de Prestaciones Sociales", "ESTIMADO de Intereses Prestaciones Sociales");



	if($_POST['consulta_intereses'])
	{
		$tipo_personal=$_POST['tipo_personal'];
		$MO_INT_ACUMULADO=consulta_intereses($tipo_personal);
		
		$form->addField("","Monto estimado de intereses sobre prestaciones sociales al 31/12/2011: ".number_format($MO_INT_ACUMULADO, 2, ",", ".")."");
		
	}
	else
	{
		$form->addField("Consulta:",frm_select('cual', $tcual, $cual, "", 'class="fs" onchange="showConsulta(this)"'));
		
		$ano    = anos();
		$meses  = meses();
		$nmeses = nmeses();
		$form->addField("","<label id='anomes'>A&ntilde;o / mes:</label>".frm_select('ano', $ano, $ano, "", 'class="fs" onchange="showEspecial(this)"') . frm_select('mes', $meses, $nmeses, "", 'class="fs"'));
		$form->addField("",'<div id="cmbnomesp"></div>');
		
		$tipo_personal  = array("Administrativo","Docente");
		$tipo_personal1 = array(2,1);
		$form->addField("","<label id='tipo_p'>Tipo de Personal:</label>".frm_select('tipo_personal', $tipo_personal, $tipo_personal1, "", 'class="fs" onchange="showEspecial(this)"') );
	}
	$form->addField("",'<input id="boton" onclick="ventana()" type="button" name="submit" value="Consultar"/>');
	
	$form->addField("",'<input id="boton_submit"  type="submit" name="consulta_intereses" value="Consultar"/>');// ---Agregado para consultar intereses sobre prestaciones sociales
	
$cuerpo .= $form->getForm(); //------------------------------------------------- coloca el formulario en el contenido

//------------------------------------------------------------------------------
$cuerpo .='<div id="pie">2010. Universidad del Zulia - RRHH - Diticluz</div>';

$pagina->setBody($cuerpo); //------------------------------------------------------- asigna el contenido de la pagina
$pagina->showPage(); //------------------------------------------------------------- presenta la pagina completa


//FUNCION PARA CONSULTAR EL ACUMULADO DE LOS INTERESES SOBRE PRESTACIONES SOCIALES AL 31/12/2011
function consulta_intereses($tipo_personal)
{
	$cedula=$_SESSION["cedula"];
	//$cedula=3379195;	
	$db="sidial_excel";
	$sql="SELECT name FROM sysobjects ORDER BY name ASC";
	$ds	= new DataStore($db, $sql);
	//COMPROBACION PARA VERIFICAR QUE DEBE ESTAR EN UNO DE LOS LISTADOS 1998-2009   2010-2011   DOCENTES 1975-1997
	
	$sql="
			SELECT DISTINCT 
			  MST_TRABAJADOR.CE_TRABAJADOR,
			  YEAR(case WHEN  MST_CARGOS.FE_JUBILACION IS NULL THEN MST_CARGOS.FE_FIN_PRESTACIONES ELSE MST_CARGOS.FE_JUBILACION END) AS ano, 
			  ( LEFT(MST_CARGOS.TIPOPERSONAL,1)) AS TIPOP 
			FROM MST_CARGOS, MST_TRABAJADOR
		WHERE ( MST_TRABAJADOR.CE_TRABAJADOR = MST_CARGOS.CE_TRABAJADOR ) AND
			  ( SUBSTRING(MST_CARGOS.TIPOPERSONAL,4,2) <> '92' ) AND 
			  ( SUBSTRING(MST_CARGOS.TIPOPERSONAL,1,3) <> '140' ) AND
			  ( LEFT(MST_CARGOS.TIPOPERSONAL,1) = '$tipo_personal' ) AND
			  ( case WHEN  MST_CARGOS.FE_JUBILACION IS NULL THEN MST_CARGOS.FE_FIN_PRESTACIONES ELSE MST_CARGOS.FE_JUBILACION END IS NOT NULL ) AND
			  ( YEAR(case WHEN  MST_CARGOS.FE_JUBILACION IS NULL THEN MST_CARGOS.FE_FIN_PRESTACIONES ELSE MST_CARGOS.FE_JUBILACION END) BETWEEN 1998 AND 2011) AND 
			  ( MST_CARGOS.EDO_CARGO in ('A','F','R'))		  AND
			  ( MST_TRABAJADOR.CE_TRABAJADOR = $cedula )
	UNION
			SELECT DISTINCT 
				   MST_TRABAJADOR.CE_TRABAJADOR, 
				   YEAR(MST_CARGOS.FE_JUBILACION) AS ano, 
				   ( LEFT(MST_CARGOS.TIPOPERSONAL,1)) AS TIPOP 
			FROM MST_CARGOS, MST_TRABAJADOR
			WHERE ( MST_TRABAJADOR.CE_TRABAJADOR = MST_CARGOS.CE_TRABAJADOR ) AND	  
			  ( SUBSTRING(MST_CARGOS.TIPOPERSONAL,4,2) <> '92' ) AND 
			  ( SUBSTRING(MST_CARGOS.TIPOPERSONAL,1,3) <> '140' ) AND
			  ( LEFT(MST_CARGOS.TIPOPERSONAL,1) in ('1','2') ) AND
			  ( MST_TRABAJADOR.CE_TRABAJADOR IN (3933968,114369,35982,244073,132581,679107,249221,681896,1078900,101159,1052975,2091522,1092707,1643395,483638,293457,1670457,5813080,1637465,1637465,1656850,1382359,1065923,4145884,2466910,1669389,1689427,1685308,2874802,2096532,1691834,1962335,2871423,2871423,1666380,182263,329096,746969,142490,173098,1896904,1092378,3511460,1656583,2867184,1002253,1864558,3279568,1821423,2857785,1664183,1656829,1659258,1687227,2467208,2866511,1699977,1826819,2883516,2463505,873808,1962909,1670337,1657139,1936224,1069567,3272522,1937788,3509013,1058443,1421254,720148,1813383,1636415,1087526,3466550,2868263,986448,2817905,2874923,948051,1642541,1648008,1656112,2876406,1690773,1647539,1684711,1077182,2822145,2873680,2865496,2865496,1053937,1657595,1638230,2894078,1690806,2875505,2150844,3925136,3112870,1070094,2868196,2868196,2052862,1393187,3273723,3117961,2868120,1828935,1657071,1657708,1640153,1079351,2771176,3385888,1691923,2884368,1660359,2872781,2884063,1657016,1928058,3112467,3112467,1669754,102111,1596665,2882253,5840274,3114317,1697097,3384276,2615425,2772981,1808564,3549204,623000,1642632,3216047,3216047,3452259,2143069,2869481,1045110,2873319,3774735,2860981,1669510,1630481,2874708,2052523,1695316,7874916,2865414,1093201,3116899,1264086,3271391,2134926,2455791,5839228,2888884,1932516,1806083,3272281,2768929,682313,1653951,2877777,3379108,1646390,3351316,3651728,1406220,1656749,1671331,2869480,1099546,3103835,3643490,1821310,5845060,5841223,1644433,3272362,2756670,1636463,1636463,2885067,1669414,1904851,2874657,7813832,3647895,189946,1828799,3644154,2108386,2883448,3034515,2073729,3109689,5808707,3114779,3114929,2552063,3127646,3277192,3379641,2875004,630809,630809,2881400,4013426,3384915,2773317,3114318,3453391,3453391,2883406,3117188,1534688,3108641,2737889,3113473,2095163,3264718,641592,641592,3274123,3033918,2818607,4269195,3933874,2878798,3115114,1693461,3508301,3379498,3379783,3385668,4203679,3430341,3929217,1657495,3930047,393229,1650967,6005362,3578024,2686926,2867272,2773057,1826477,2052658,2743543,2869481,4150615,3929211,1656813,2884451,3110402,2626932,4522373,3926426,2626030,3078023,3107324,529896,3093473,3643185,3644519,4152751,3510876,3093473,1829277,3508075,1088991,1693764,1698737,3679377,3119021,2898488,3276637,3379821,3772750,4172440,2815083,3928054,3930244,2882357,3279863,3106568,3117188,4014967,1092093,2574801) )
				"; 
			$ds->executesql($sql);
			$todos=$ds->getValuesCol("CE_TRABAJADOR");
			//echo count($todos);
			//LOS 280 DE 1975-1998 
			if(count($todos)<=282)
			{
				echo "<head><meta http-equiv=\"refresh\" content=\"0; url=../RRHH/consultaNominaForm.php\"></head>"; 
				exit;	
				
			}
			else
			{
				$sql=" SELECT MO_INT_ACUMULADO 
				      from MST_PRESTACIONES_DETALLE, MST_CARGOS
		WHERE ( MST_PRESTACIONES_DETALLE.CE_TRABAJADOR = MST_CARGOS.CE_TRABAJADOR ) AND
			  ( SUBSTRING(MST_CARGOS.TIPOPERSONAL,4,2) <> '92' ) AND 
			  ( SUBSTRING(MST_CARGOS.TIPOPERSONAL,1,3) <> '140' ) AND
			  ( LEFT(MST_CARGOS.TIPOPERSONAL,1) = MST_PRESTACIONES_DETALLE.TIPO_REGISTRO ) AND
			  ( LEFT(MST_CARGOS.TIPOPERSONAL,1) = '$tipo_personal' ) AND
			  ( case WHEN  MST_CARGOS.FE_JUBILACION IS NULL THEN MST_CARGOS.FE_FIN_PRESTACIONES ELSE MST_CARGOS.FE_JUBILACION END IS NOT NULL ) AND
			  ( YEAR(case WHEN  MST_CARGOS.FE_JUBILACION IS NULL THEN MST_CARGOS.FE_FIN_PRESTACIONES ELSE MST_CARGOS.FE_JUBILACION END) BETWEEN 1998 AND 2011) AND 
			  ( MST_CARGOS.EDO_CARGO in ('A','F','R'))		  AND
				      MST_PRESTACIONES_DETALLE.CE_TRABAJADOR = $cedula 
						AND FE_HASTA = '2011-12-31' AND 
                       ( MST_PRESTACIONES_DETALLE.MO_INT_ACUMULADO > 0) ";
				$ds->executesql($sql);
				$MO_INT_ACUMULADO=$ds->getValueCol(0,"MO_INT_ACUMULADO");
		//		return($MO_INT_ACUMULADO);
				return(0);
			}		
	
	
}

?>
