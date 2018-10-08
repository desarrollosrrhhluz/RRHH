<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();     
include_once("../FrameWork/Include/Defines.inc.php");
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once(LUZ_PATH_INCLUDE.'/Funciones.inc.php');
$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$dbw="system";

$array = $_GET['cedula'];
		$cedula = explode(",",$array); $i = count($cedula);
		 $j=0;
 		while($j<$i){
$sql4 =  "select * from mst_registro_usuario where co_cedula=$cedula[$j]"; 
						$ds4    = new DataStore($dbw, $sql4);
						$res=$ds4->getNumRows();
						if($res<0){
						 echo "<br>";
						  }else{
						   $dev = $ds4->getValues(0);
						   echo $dev['da_e_mail']." ,<br>";
						  }
				$j++;		  
			}						  
?>