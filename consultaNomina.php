<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : consulta_nomina.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creaci�n      : 25/02/2008
		Objetivo / Resumen  : consulta de nomina
		Ultima Modificaci�n : 23/04/2008
------------------------------------------------------------------------------------------------ */
/**/
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
include_once(LUZ_PATH_CLASS    . '/PaginaWeb.class.php');
include_once(LUZ_PATH_CLASS    . '/Formulario.class.php');
include_once(LUZ_PATH_INCLUDE  . '/FormItems.inc.php');
include_once(LUZ_PATH_INCLUDE  . '/Funciones.inc.php');

//------------------------------------------------------------------------------ se defines los argumentos de la pagina web
$js = array("../FrameWork/js/funcion.js"); //------------------------------------------------ archivos javascript
$css = array("css/estructuras.css","css/consultas.css"); //--------------- plantillas de estilos
$pagina = new PaginaWeb("Oscar Amesty","Consulta de Nomina",$js,$css); //----------------- instancia de PaginaWeb
$cuerpo="";   //---------------------------------------------------------------- variable para almacenar el contenido
//------------------------------------------------------------------------------- aqui va el trabajo con la sesion
session_start();
//if(isset($_SESSION['cedula']))
	$cedula  = $_SESSION['cedula'];
//else
//	$cedula = getVar('ci');
$cual    = getVar('cual');
$mes     = getVar('mes');
$ano     = getVar('ano');
switch ($cual) {
    case "detalle":
        $cuerpo .= cabecera($cedula, $ano, $mes);
        $cuerpo .= detalle_pago($cedula, $ano, $mes);
        break;
    case "pdf":
    case "arc":
    case "detalle_especial":
        header ("Location: consultaNominaForm.php");
        exit();
        break;
    case "resumen":
        $cuerpo .= cabecera_resumen($cedula, $ano);
        $cuerpo .= resumen_asignacion($cedula, $ano);
        break;
    case "deudas":
        $cuerpo .= deudas($cedula);
        break;
    case "bono2011":
        $cuerpo .= bono2011($cedula);
        break;
    case "aguinaldo":
        $cuerpo .= aguinaldo($cedula);
        break;
    case "fide":
        $cuerpo .= fideicomiso($cedula);
        break;
    default:
        break;
}
$cuerpo .= '<br /><br /><a href="JavaScript:imprimir()">Imprimir consulta</a><br/><br/>';

//------------------------------------------------------------------------------
$pagina->setBody($cuerpo); //------------------------------------------------------- asigna el contenido de la pagina
$pagina->showPage(); //------------------------------------------------------------- presenta la pagina completa

function cabecera($cedula, $ano, $mes){
	$cuerpo = "";
	$db     = "sidial";
	$sql    = "SELECT * FROM VW_CABECERA_HISTORICO WHERE CE_TRABAJADOR = $cedula AND ANO = $ano AND MES = $mes";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
		$fila = $ds->getValues(0);
	  $cuerpo .= '<table class="consulta">';
//	  $cuerpo .= '<tr class="par"><th>Apellidos y nombres</th><td>' . $fila["NOMBRES"]  . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Ubicaci&oacute;n</th><td>' . $fila["UBICACION"] . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Tipo de personal</th><td>' . $fila["TIPPER"] . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Dedicaci&oacute;n</th><td>' . $fila["DEDICACION"] . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Categor&iacute;a o cargo</th><td>' . $fila["CATEGORIA"] . "</td></tr>";
//	  $cuerpo .= '<tr class="par"><th>Fecha de Ingreso</th><td>' . $fila["FE_INGRESO"] . "</td></tr>";
//	  $cuerpo .= '<tr class="par"><th>Fecha de Jubilaci�n</th><td>' . $fila["FE_JUBILACION"] . "</td></tr>";
	  $cuerpo .= "</table>";
	}
	return $cuerpo;
}

function cabecera_especial($cedula){
	$cuerpo = "";
	$db     = "sidial";
    $cadena = $_REQUEST['cadena'];
    $ano = substr($cadena,0,4);
    $mes = substr($cadena,4,2);
    $nomina_general = substr($cadena,6,3);
    $nomina_especifica = substr($cadena,9,12);
	$sql  = "SELECT * FROM VW_CABECERA_HIS_ESPECIAL WHERE CE_TRABAJADOR = $cedula AND ANO = $ano AND ";
  $sql .= "MES = $mes AND TIPO_NOMINA = $nomina_general AND TIPO_NOMINA_ESPECIFICA = $nomina_especifica";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
		$fila = $ds->getValues(0);
	  $cuerpo .= '<table class="consulta">';
//	  $cuerpo .= '<tr class="par"><th>Apellidos y Nombres</th><td>' . $fila["NOMBRES"]  . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Ubicaci&iacute;n</th><td>' . $fila["UBICACION"] . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Tipo de personal</th><td>' . $fila["TIPPER"] . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Dedicaci&oacute;n</th><td>' . $fila["DEDICACION"] . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Categor&iacute;a o cargo</th><td>' . $fila["CATEGORIA"] . "</td></tr>";
//	  $cuerpo .= '<tr class="par"><th>Fecha de Ingreso</th><td>' . $fila["FE_INGRESO"] . "</td></tr>";
//	  $cuerpo .= '<tr class="par"><th>Fecha de Jubilaci�n</th><td>' . $fila["FE_JUBILACION"] . "</td></tr>";
	  $cuerpo .= "</table>";
	}
	return $cuerpo;
}

function cabecera_resumen($cedula, $ano){
	$cuerpo = "";
	$db     = "sidial";
	$sql    = "SELECT * FROM VW_CABECERA_HISTORICO WHERE CE_TRABAJADOR = $cedula AND ANO = $ano";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
		$fila = $ds->getValues(0);
	  $cuerpo .= '<table class="consulta">';
//	  $cuerpo .= '<tr class="par"><th>Apellidos y nombres</th><td>' . $fila["NOMBRES"]  . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Ubicaci&oacute;n</th><td>' . $fila["UBICACION"] . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Tipo de personal</th><td>' . $fila["TIPPER"] . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Dedicaci&oacute;n</th><td>' . $fila["DEDICACION"] . "</td></tr>";
	  $cuerpo .= '<tr class="par"><th>Categor&iacute;a o cargo</th><td>' . $fila["CATEGORIA"] . "</td></tr>";
//	  $cuerpo .= '<tr class="par"><th>Fecha de Ingreso</th><td>' . $fila["FE_INGRESO"] . "</td></tr>";
//	  $cuerpo .= '<tr class="par"><th>Fecha de Jubilaci�n</th><td>' . $fila["FE_JUBILACION"] . "</td></tr>";
	  $cuerpo .= "</table>";
	}
	return $cuerpo;
}

function detalle_pago($cedula, $ano, $mes){
	$tmes = de_mes($mes);
	$cuerpo = "<h3>Consulta Detalle de Pago - $tmes $ano </h3>";

	$cuerpo .= "<table class=consulta><tr><th>Concepto</th><th>Asignaciones</th><th>Deducciones</th><th>Saldo</th></tr>";
	$db     = "sidial";
	$sql =  " SELECT * FROM VW_DETALLE_PAGO	WHERE CE_TRABAJADOR = $cedula AND ANO = $ano AND MES = $mes ORDER BY 4";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
		for($i=0 ; $i < $ds->getNumRows() ; $i++){
			$fila = $ds->getValues($i);
			$tr = cambia_color($tr);
			$cuerpo .= $tr;
			$cuerpo .= "<td>" . $fila["DESCRIPCION"]  . "</td>";
			$cuerpo .= "<td align='right'>" . number_format($fila["ASIGNACIONES"],2,',','.') . "</td>";
			$cuerpo .= "<td align='right'>" . number_format($fila["DEDUCCIONES"],2,',','.') . "</td>";
			$cuerpo .= "<td align='right'>" . number_format($fila["MO_SALDO"],2,',','.') . "</td></tr>";
		  $total_asig += $fila["ASIGNACIONES"];
		  $total_deduc += $fila["DEDUCCIONES"];
		}
		$cuerpo .= "<tr><th>TOTAL DE ASIGNACIONES Y DEDUCCIONES</th>";
		$cuerpo .= "<th align='right'>". number_format($total_asig,2,',','.') . "</th>";
		$cuerpo .= "<th align='right'>". number_format($total_deduc,2,',','.') . "</th>";
		$cuerpo .= "<th></th></tr>";
		$cuerpo .= "<tr><th>TOTAL ASIGNACIONES - DEDUCCIONES</th>";
		$cuerpo .= "<th colspan='3'>". number_format(($total_asig-$total_deduc),2,',','.') . "</th>";
		$cuerpo .= "</tr></table>";
	}
	return $cuerpo;
}

function detalle_pago_especial($cedula){

  $cadena = $_POST['cadena'];
  $ano = substr($cadena,0,4);
  $mes = substr($cadena,4,2);
  $nomina_general = substr($cadena,6,3);
  $nomina_especifica = substr($cadena,9,12);

	$de_mes = de_mes($mes);
	$cuerpo = "<h3>Consulta de n�mina especial de $de_mes $ano</h3>";

  $sql  = "SELECT * FROM VW_DETALLE_PAGO_ESPECIAL WHERE CE_TRABAJADOR = $cedula AND ANO = $ano AND MES = $mes AND ";
  $sql .= "TIPO_NOMINA = $nomina_general AND TIPO_NOMINA_ESPECIFICA = $nomina_especifica ORDER BY 4";
	$db     = "sidial";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
		$cuerpo .= "<table class=consulta ><tr><th>Concepto</th><th>Asignaciones</th><th>Deducciones</th><th>Saldo</th></tr>";
		for($i=0 ; $i < $ds->getNumRows() ; $i++){
			$fila = $ds->getValues($i);
	   	$tr = cambia_color($tr);
	   	$cuerpo .= $tr . "<td>" . $fila["DESCRIPCION"]  . "</td>";
	   	$cuerpo .= "<td align='right'>" . number_format($fila["ASIGNACIONES"],2,',','.') . "</td>";
	   	$cuerpo .= "<td align='right'>" . number_format($fila["DEDUCCIONES"],2,',','.') . "</td>";
	   	$cuerpo .= "<td align='right'>" . number_format($fila["MO_SALDO"],2,',','.') . "</td></tr>";
     	$total_asig += $fila["ASIGNACIONES"] ;
     	$total_deduc += $fila["DEDUCCIONES"];
		}
		$cuerpo .="<tr><th>TOTAL DE ASIGNACIONES Y DEDUCCIONES</th>";
		$cuerpo .="<th align='right'>". number_format($total_asig,2,',','.') . "</th>";
		$cuerpo .="<th align='right'>". number_format($total_deduc,2,',','.') . "</th>";
		$cuerpo .="<th></th>";
		$cuerpo .="</tr>";
		$cuerpo .="<tr><th>TOTAL ASIGNACIONES - DEDUCCIONES</th>";
		$cuerpo .="<th colspan='3'>". number_format(($total_asig-$total_deduc),2,',','.') . "</th></tr></table>";
	}
	return $cuerpo;
}

function resumen_asignacion($cedula, $ano){
$sql  = "SELECT MES, STATUS_NOMINA='O', SUMA_ASIGNACIONES = MO_DEVENGADO,	'N�mina Ordinaria' AS DESCRIPCION FROM VW_ASIGNACIONES_ANUALES
WHERE CE_TRABAJADOR = $cedula AND ANO = $ano UNION
SELECT DISTINCT MES, STATUS_NOMINA='E', SUM(ASIGNACIONES), TAB_TIPO_NOMINA_ESPECIFICA.DESCRIPCION FROM VW_DETALLE_PAGO_ESPECIAL, TAB_TIPO_NOMINA_ESPECIFICA
WHERE CE_TRABAJADOR = $cedula AND ANO = $ano AND VW_DETALLE_PAGO_ESPECIAL.TIPO_NOMINA not in ( 5,6,7,8,9,10,12,13,14,15,22,23,24,27 ) and
TAB_TIPO_NOMINA_ESPECIFICA.TIPO_NOMINA = VW_DETALLE_PAGO_ESPECIAL.TIPO_NOMINA and
TAB_TIPO_NOMINA_ESPECIFICA.TIPO_NOMINA_ESPECIFICA = VW_DETALLE_PAGO_ESPECIAL.TIPO_NOMINA_ESPECIFICA
GROUP BY MES,	VW_DETALLE_PAGO_ESPECIAL.TIPO_NOMINA_ESPECIFICA, VW_DETALLE_PAGO_ESPECIAL.TIPO_NOMINA, TAB_TIPO_NOMINA_ESPECIFICA.DESCRIPCION
ORDER BY 1 ASC ";
	$cuerpo = "<h3>Resumen de asignaciones del a&ntilde;o $ano</h3>";
	$db     = "sidial";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
		$cuerpo .= "<table class=consulta><tr><th></th><th>Monto</th></tr>";
		for($i=0 ; $i < $ds->getNumRows() ; $i++){
			$fila = $ds->getValues($i);
	   	$status_nomina = $fila["STATUS_NOMINA"];
	    if ($status_nomina=='O') $total_o += $fila["SUMA_ASIGNACIONES"];
	    if ($status_nomina=='E') $total_e += $fila["SUMA_ASIGNACIONES"];
	  }
		$cuerpo .= "<tr><th align='right'>TOTAL GENERAL ANUAL: </th>";
		$cuerpo .= "<th align='right'>". number_format($total_o,2,',','.') . "</th></tr></table>";
	}
	return $cuerpo;
}

function consulta_intpre($cedula){
$cuerpo = "<h3>Consulta Diferencia de prestaciones 2002-2006 </h3>";
	$db     = "web_query";
	$sql =  " SELECT * FROM DIFERENCIA_PRESTACIONES_0206	WHERE CEDULA = $cedula";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
		$cuerpo .= "<table class=consulta><tr><th>Nombre</th><th>Neto A Pagar</th></tr>";
		for($i=0 ; $i < $ds->getNumRows() ; $i++){
			$fila = $ds->getValues($i);
	   	$tr = cambia_color($tr);
	   	$cuerpo .= $tr . "<td>" . $fila["NOMBRE"] . "</td><td align='right'>" . number_format($fila["MONTO"],2,',','.') . "</td></tr>";
		}
		$cuerpo .= "</table>";
	}
	return $cuerpo;
}

function deudas($cedula){
$cuerpo = "<h3>Deudas por diversos conceptos</h3>";
$sql  =  " SELECT * FROM TMP_INTPRE_WEB WHERE CE_TRABAJADOR = $cedula "; //AND TIPOPERSONAL  = '$tipper' ";
$db = "sidial";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
		$cuerpo .= "<table class=consulta>";
		for($i=0 ; $i < $ds->getNumRows() ; $i++){
			$fila = $ds->getValues($i);
	   	$tr = cambia_color($tr);
	   	$cuerpo .= $tr . "<td>" . $fila["DESCRIPCION"] . "</td><td align='right'>" . number_format($fila["MONTOC"],2,',','.') . "</td></tr>";
		}
		$cuerpo .= "</table>";
	}
	return $cuerpo;
}
function bono2011($cedula){
    //if($cedula!=7978700) return "";
    //if($cedula==7978700) $cedula = 8507940;
	$cuerpo = "";
	$db     = "sidial";
	$sql    = "SELECT * FROM V_DATPER WHERE CE_TRABAJADOR = $cedula";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
            $db     = "rrhh";
            $sql    = "SELECT * FROM tmp_bono2011 WHERE ci = $cedula";
            $ds2     = new DataStore($db, $sql);
            if($ds2->getNumRows()==0){
                    $cuerpo .= "<h4>No hay datos para la consulta</h4>";;
            } else {
		$fila = $ds->getValues(0);
              $cuerpo .= '<table class="consulta">';
              $cuerpo .= '<tr class="par"><th>Apellidos y nombres</th><td>' . $fila["NOMBRES"]  . "</td></tr>";
		for($i=0 ; $i < $ds2->getNumRows() ; $i++){
                    $fila2 = $ds2->getValues($i);
                    $tr = cambia_color($tr);
                    $cuerpo .= $tr.'<th>Monto ESTIMADO Bono Vacacional '.tipoPersonal($fila2["fill"]).'</th><td><strong>' . number_format($fila2["mo_bono"],2,',','.') . "</strong></td></tr>";
                }
              $cuerpo .= "</table><br/>";

              $cuerpo .= '<table class="consulta">';
              $cuerpo .= '<tr class="par"><th>FORMULA PARA EL CALCULO DEL BONO VACACIONAL</th></tr>';
              $cuerpo .= '<tr class="par"><td></td></tr>';
              $cuerpo .= '<tr class="par"><th>FORMULA GENERAL</th></tr>';
              $cuerpo .= '<tr class="par"><td>((SUELDO BAS. + 10% SUELDO BAS. + PRIMAS) +((SUELDO BAS. +10 % SUELDO BAS. + PRIMAS)/30*90/12))/30*90</td></tr>';
              $cuerpo .= '<tr class="par"><th>EXPRESION MATEMATICA DE LA FORMULA</th></tr>';
              $cuerpo .= '<tr class="par"><td>SB * 4,125 + PRIMAS * 3,750 = BONO VACACIONAL</td></tr>';
              $cuerpo .= '<tr class="par"><th>PERSONAL ADMINISTRATIVO</th></tr>';
              $cuerpo .= '<tr class="par"><td>(SB + P.ANTIG + P.PROF. + P.DIR) * 4,125 + (P. HO + P. HIJO + P.JERAR. + AUM X MER + B. COMP. + O.PRIMAS) * 3,750</td></tr>';
              $cuerpo .= '<tr class="par"><th>PERSONAL DOCENTE</th></tr>';
              $cuerpo .= '<tr class="par"><td>(SUELDO + P. TITULAR + P.DIRECTIVA) * 4,125 + (P.HOGAR + P.HIJO) * 3,750</td></tr>';
              $cuerpo .= '<tr class="par"><th>PERSONAL OBRERO</th></tr>';
              $cuerpo .= '<tr class="par"><td>(SUEL + P.ANTIG + COMP.SAL. + P.INC.EST) * 4,125 + (P.HO + P.HIJO + P.REFRIG + B.COMP. + OTRAS P) * 3,750</td></tr>';
              $cuerpo .= '<tr class="par"><td></td></tr>';
              $cuerpo .= '<tr class="par"><td>Nota: Cuando el trabajador no esta inscrito en Caja de Ahorros, se multiplican todas las asignaciones por la constante 3,750</td></tr>';
              $cuerpo .= "</table>";
            }
	}
	return $cuerpo;
}
function aguinaldo($cedula){
    //if($cedula!=7978700) return "";
    //if($cedula==7978700) $cedula = 8507940;
	$cuerpo = "";
	$db     = "sidial";
	$sql    = "SELECT * FROM V_DATPER WHERE CE_TRABAJADOR = $cedula";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
            $db     = "rrhh";
            $sql    = "SELECT * FROM tmp_aguinaldo WHERE ci = $cedula";
            $ds2     = new DataStore($db, $sql);
            if($ds2->getNumRows()==0){
                    $cuerpo .= "<h4>No hay datos para la consulta</h4>";;
            } else {
		$fila = $ds->getValues(0);
              $cuerpo .= '<table class="consulta">';
              $cuerpo .= '<tr class="par"><th>Apellidos y nombres</th><td>' . $fila["NOMBRES"]  . "</td></tr>";
		for($i=0 ; $i < $ds2->getNumRows() ; $i++){
                    $fila2 = $ds2->getValues($i);
                    $tr = cambia_color($tr);
                    $cuerpo .= $tr.'<th>Monto ESTIMADO Aguinaldo '.tipoPersonal($fila2["fill"]).'</th><td><strong>' . number_format($fila2["mo_aguinaldo"],2,',','.') . "</strong></td></tr>";
                }
              $cuerpo .= "</table><br/>";

              $cuerpo .= '<table class="consulta">';
              $cuerpo .= '<tr class="par"><th>FORMULA PARA EL CALCULO DEL AGUINALDO</th></tr>';
              $cuerpo .= '<tr class="par"><td></td></tr>';
              $cuerpo .= '<tr class="par"><th>FORMULA GENERAL</th></tr>';
              $cuerpo .= '<tr class="par"><td>((SUELDO BAS. + 10% SUELDO BAS. + PRIMAS) +((SUELDO BAS. +10 % SUELDO BAS. + PRIMAS)/30*90/12))/30*90</td></tr>';
              $cuerpo .= '<tr class="par"><th>EXPRESION MATEMATICA DE LA FORMULA</th></tr>';
              $cuerpo .= '<tr class="par"><td>SB * 4,125 + PRIMAS * 3,750 = BONO VACACIONAL</td></tr>';
              $cuerpo .= '<tr class="par"><th>PERSONAL ADMINISTRATIVO</th></tr>';
              $cuerpo .= '<tr class="par"><td>(SB + P.ANTIG + P.PROF. + P.DIR) * 4,125 + (P. HO + P. HIJO + P.JERAR. + AUM X MER + B. COMP. + O.PRIMAS) * 3,750</td></tr>';
              $cuerpo .= '<tr class="par"><th>PERSONAL DOCENTE</th></tr>';
              $cuerpo .= '<tr class="par"><td>(SUELDO + P.TITULAR + P.DIRECTIVA.PROM + P.CARGO) * 4,125 + (P.HOGAR + P.HIJO) * 3,750</td></tr>';
              $cuerpo .= '<tr class="par"><th>PERSONAL OBRERO</th></tr>';
              $cuerpo .= '<tr class="par"><td>(SUEL + P.ANTIG + COMP.SAL. + P.INC.EST) * 4,125 + (P.HO + P.HIJO + P.REFRIG + B.COMP. + OTRAS P) * 3,750</td></tr>';
              $cuerpo .= '<tr class="par"><td></td></tr>';
              $cuerpo .= '<tr class="par"><td>Nota: Cuando el trabajador no esta inscrito en Caja de Ahorros, se multiplican todas las asignaciones por la constante 3,750</td></tr>';
              $cuerpo .= "</table>";
            }
	}
	return $cuerpo;
}

function fideicomiso($cedula){
	$cuerpo = "";
	$db     = "sidial";
	$sql    = "SELECT * FROM V_DATPER WHERE CE_TRABAJADOR = $cedula";
	$ds     = new DataStore($db, $sql);
	if($ds->getNumRows()==0){
		$cuerpo .= "<h4>No hay datos para la consulta</h4>";;
	} else {
            $db     = "rrhh";
            $sql    = "SELECT * FROM tmp_fideicomiso WHERE ci = $cedula";
            $ds2     = new DataStore($db, $sql);
            if($ds2->getNumRows()==0){
                    $cuerpo .= "<h4>No hay datos para la consulta - solo esta disponible para obreros</h4>";;
            } else {
				$fila = $ds->getValues(0);
				$cuerpo .= '<table class="consulta">';
				$cuerpo .= '<tr class="par"><th>Apellidos y nombres</th><td>' . $fila["NOMBRES"]  . "</td></tr>";
				for($i=0 ; $i < $ds2->getNumRows() ; $i++){
                    $fila2 = $ds2->getValues($i);
                    $tr = cambia_color($tr);
                    $cuerpo .= $tr.'<th>Monto Fideicomiso '.tipoPersonal($fila2["fill"]).'</th><td><strong>' . number_format($fila2["mo_fideicomiso"],2,',','.') . "</strong></td></tr>";
                }
				$cuerpo .= "</table><br/>";
            }
	}
	return $cuerpo;
}

function tipoPersonal($t){

switch ($t) {
    case "1":
        return "Personal Docente";
        break;
    case "2":
        return "Personal Administrativo";
        break;
    case "3":
        return "Personal Obrero";
        break;
    default:
        return "";
        break;
}

}
?>
