<?php

$val= str_replace('-', '', strtoupper($_REQUEST['rif']));
$_url = 'http://contribuyente.seniat.gob.ve/getContribuyente/getrif?rif='.$val.'';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $_url);
//curl_setopt($ch, CURLOPT_TIMEOUT, 300);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

if(curl_exec($ch) === false)
{
    echo 'Curl error: ' . curl_error($ch);
}
else
{



 $result = curl_exec ($ch);
  $xml = simplexml_load_string($result);

if(!is_bool($xml)) {
	$elements = $xml->children('rif');
	$seniat = array();
   
	foreach($elements as $key => $node) {
		$index = strtolower($node->getName());
		$seniat[$index] = (string)$node;
	}
	
	
	$res[0]['nombre']=$seniat['nombre'];
	$res[0]['resultado']=1;
echo json_encode($res);
}else{
		$res[0]['resultado']=0;
echo json_encode($res);
	
	}

}
?>