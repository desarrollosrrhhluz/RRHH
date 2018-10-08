<?php
echo $ci=str_replace(".","",$_REQUEST['cedula']);
//extract data from the post
//set POST variables
$url = 'http://http://contribuyente.seniat.gob.ve/BuscaRif/BuscaRif.jsp';
$fields = array(
	'p_cedula' => urlencode($_REQUEST['cedula']),
	'codigo'=>'',
	'p_rif' => urlencode($_REQUEST['rif'])
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
echo $result = curl_exec($ch);

//close connection
curl_close($ch);
     
?>
