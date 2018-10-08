<?php

 header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();  

include_once('../app_class/DataStore.class.php'); 
 $db="desarrolloRRHH"; 
 
 
$sqlverfica="select upper(TRIM(rif_sitio_estudio)) as rif from mst_familiares_beneficios where rif_sitio_estudio is not null
and rif_sitio_estudio!=''  
and (nombre_sitio_estudio is null or nombre_sitio_estudio='' ) group by rif_sitio_estudio ";
$dsx  = new DataStore($db, $sqlverfica);
if($dsx->getNumRows()<=0){

		echo 'No Existen Datos<br><br>'; 
		
		  }else{
		$i=0;
		$j=$dsx->getNumRows();
		while($i<$j){
		$row = $dsx->getValues($i);
		
		$rif= $row['rif'];
		///////////////////////////////////////
		$val= str_replace('-', '', $rif);
 $_url = 'http://contribuyente.seniat.gob.ve/getContribuyente/getrif?rif='.$val.'';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $_url);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
 $nombre=utf8_decode($seniat['nombre']);

 echo $update="UPDATE   public.mst_familiares_beneficios  
SET 
  rif_sitio_estudio = '$rif',
  nombre_sitio_estudio = '$nombre'
  WHERE 
  upper(trim(rif_sitio_estudio)) ='$rif';<br>";

}else{
		$res[0]['resultado']=0;
//echo json_encode($res);
	
	
	
	}
		
		
		///////////////////////////////////////
		
		$i++;
		
		}
		  }

/**/
?>