<?php
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once('includes/Funciones.inc.php');

$dbs="sidial";

$cedula=array(17834399,14922765,9703056);

$count=count($cedula);
$i=0;
$cadena="<table>";
while($i<$count){
	 $ced=$cedula[$i];
			$sql =  "select FE_NACIMIENTO from V_DATPER where CE_TRABAJADOR = $ced";
			$ds     = new DataStore($dbs, $sql);
			$fila = $ds->getValues(0);
			$fec= formato_fecha_aaaa($fila['FE_NACIMIENTO']);
			$fecha=explode("/", $fec);
			
		    $a=$fecha[0];
			$m=$fecha[1];
			$d=$fecha[2];
$_url = 'http://www.ivss.gob.ve:28083/CuentaIndividualIntranet/CtaIndividual_PortalCTRL?cedula_aseg='.$ced.'&nacionalidad_aseg=V&y='.$a.'&m='.$m.'&d='.$d.'';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $_url);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
 $result = curl_exec ($ch);
  

$array = explode('<table', $result) ;

$array2 = explode('<tr align="left" class="datos">', $array[3]) ;
$numero_patronal = explode('<td colspan = "3" bordercolor="#000000">', $array2[5]) ;
$estatus = explode('<td', $array2[8]) ;
$num=strip_tags($numero_patronal[1]);
$est=substr(strip_tags($estatus[4]),13);

	
	$cadena.= "	<tr>
			<td>".$ced."</td>
			<td>".$num."</td>
			<td>".$est."</td>
		</tr>";
	


$i++;
}
echo $cadena.="</table>";
?>