<?php
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once('includes/Funciones.inc.php');

$dbs="sidial";

$cedula=explode(",", $_GET['cedula']);

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
$_url = 'http://www.ivss.gob.ve:28080/Pensionado/PensionadoCTRL?cedula='.$ced.'&nacionalidad=V&y1='.$a.'&m1='.$m.'&d1='.$d.'';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $_url);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
 $result = curl_exec ($ch);

$texto=strip_tags($result);

$resultado = strpos($texto, "Vejez");

if($resultado !== FALSE){
$cadena.= "<tr><td>".$ced."</td><td></td><td>SI</td></tr>";
}else{
	$cadena.= "<tr><td>".$ced."</td><td></td><td>NO</td></tr>";

}
  

// $array = explode('<table', $result) ;

// $array2 = explode('<tr align="left" class="datos">', $array[3]) ;
// $numero_patronal = explode('<td colspan = "3" bordercolor="#000000">', $array2[5]) ;
// $estatus = explode('<td', $array2[8]) ;
// $num=strip_tags($numero_patronal[1]);
// $est=substr(strip_tags($estatus[4]),13);

	
 
	


$i++;
}

 echo $cadena.="</table>";
?>