<?php
$ci=str_replace(".","",$_REQUEST['cedula']);
if(empty($_REQUEST['nac'])){
	$nac="V";
	}else{
	$nac=$_REQUEST['nac'];	
		}

$url="http://www.cne.gov.ve/web/registro_electoral/ce.php?nacionalidad=$nac&cedula=$ci";
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // almacene en una variable
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


$xxx1 = curl_exec($ch);
curl_close($ch);
$xx2=str_replace("strong", "b", $xxx1);
$xxx = explode('<b>', $xx2);
//print_r($xxx);
//echo $xxx[1];
if(strstr(trim($xxx[1]), 'DATOS PERSONALES')== FALSE ){
   if(strstr( trim($xxx[1]), 'SERVICIO')== FALSE ){
    if(strstr( trim($xxx[1]), 'DATOS DEL ELECTOR')== FALSE ){
   echo $ci." ERROR EN CEDULA";
   }else{
	$ciDatos = explode('<td align="left">', $xxx1) ;
	//print_r($ciDatos);
	echo trim(utf8_decode(strip_tags($ciDatos[4])));
	   }
   }else{
	$ciDatos = explode('<td align="left">', $xxx1) ;
	//print_r($ciDatos);
	echo trim(utf8_decode(strip_tags($ciDatos[4])));
	   }
	}else{
$ciDatos = explode('</strong>', $xxx1) ;
	//print_r($ciDatos);
	$apellido1 = explode('<', $ciDatos[5]) ;
		$apellido2 = explode('<', $ciDatos[6]) ;
		$nombre1 = explode('<', $ciDatos[3]) ;
		$nombre2 = explode('<', $ciDatos[4]) ;
	
 echo  trim(utf8_decode(trim($nombre1[0])." ".trim($nombre2[0])." ".trim($apellido1[0])." ".trim($apellido2[0]))) ; 
}


     
?>
