<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
$db = "desarrolloRRHH";
$ds = new DataStore($db);

$request = trim(strtolower($_REQUEST['ce_familiar']));

$ds->executesql("SELECT ce_familiar FROM  mst_familiares_beneficios where ce_familiar= '$request'");
if($ds->getNumRows()<=0){
	$valid = 'true';
	echo $valid;
}else{
	$valid = 'false';
	echo $valid;
}

?>
