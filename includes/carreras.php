<?php

 header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();  

include_once('../app_class/DataStore.class.php'); 
 $db="desarrolloRRHH"; 
 
 

		///////////////////////////////////////
		$val= str_replace('-', '', $rif);
 $_url = 'http://loeu.opsu.gob.ve/controladores/ctrl_carreras_pnf.php?objeto=carrerasYpnf&carrera=%25+%25&tipoDependencia=';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $_url);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
 $result = curl_exec ($ch);
 /* $xml = simplexml_load_string($result);*/

// if(!is_bool($xml)) {
// 	$elements = $xml->children('rif');
// 	$seniat = array();
   
// 	foreach($elements as $key => $node) {
// 		$index = strtolower($node->getName());
// 		$seniat[$index] = (string)$node;
// 	}
	
	
// 	$res[0]['nombre']=$seniat['nombre'];
// 	$res[0]['resultado']=1;
//  $nombre=utf8_decode($seniat['nombre']);

$array=json_decode($result, true);

$i=count($array);
$j=0;

while ($j < $i) {

echo "INSERT INTO 
  opciones.tab_carrera_universidad
(

  co_carrera,
  co_universidad,
  da_carrera
)
VALUES (
  
  '".$array[$j]['cod_carrera']."',
  '".$array[$j]['cod_institucion']."',
  '".$array[$j]['nombre_carrera']."'
);<br>";

	
	
	$j++;
}

/**/
?>