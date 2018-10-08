<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
error_reporting(0);
session_start();    
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
include('includes/Funciones.inc.php');

$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$cedula=$_SESSION['cedula'];


	
	 $sql = "SELECT * FROM tab_telefonos where estatus=FALSE and created_at <='2016-12-11' and telefono!='' and telefono!='04_________' and  telefono not like '%000000%' ";
				$ds2    = new DataStore($dbr, $sql);
				if($ds2->getNumRows()<=0){
				
				  }else{

				  $i=$ds2->getNumRows();
				  while ( $i>$j ) {

				  	$row = $ds2->getValues($j);
				  	$ced=$row['ce_trabajador'];
				  	$telefono=$row['telefono'];
				  	$textoSMS="Ya puede acceder a nuestros servicios en linea. RRHH-LUZ. No responder este SMS";



			echo $sqlCodigo="UPDATE public.tab_telefonos SET estatus = TRUE WHERE ce_trabajador = $ced;<br>";
			echo $sqlSMS='INSERT INTO outbox ("DestinationNumber", "TextDecoded", "SenderID", "CreatorID", "Coding","Class")
						VALUES ('."'$telefono','$textoSMS','ZTE','ZTE','Default_No_Compression',-1);<br>";

					

				  	$j++;
				  	# code...
				  }



				echo $j;

					

				   }     

// function enviarSMS($cedula, $telefono){
// global  $dbr, $db, $dbs;
// $codigo=generarCodigo(4);
//  $sql = "SELECT * FROM tab_telefonos where ce_trabajador= $cedula";
// 				$ds2    = new DataStore($dbr, $sql);
// 				if($ds2->getNumRows()<=0){
// 				//insert 
// 				$sqlCodigo="INSERT INTO 
//   public.tab_telefonos (ce_trabajador, codigo, telefono, estatus )
// 				VALUES ( $cedula, '$codigo', '$telefono', FALSE );";
// 				  }else{
// 				//update
// 				$sqlCodigo="UPDATE 
//   public.tab_telefonos 
// SET 
//   codigo = '$codigo',
//   telefono = '$telefono',
//   estatus = FALSE
// WHERE 
//   ce_trabajador = $cedula;";
// 				   } 

// 	$textoSMS="Su codigo de verificacion RRHH-LUZ es: ".$codigo;
// 	//insert sms
// $sqlSMS='INSERT INTO outbox ("DestinationNumber", "TextDecoded", "SenderID", "CreatorID", "Coding","Class")
// 		VALUES ('."'$telefono','$textoSMS','ZTE','ZTE','Default_No_Compression',-1)";



// 			$ds3  = new DataStore($dbr);
// 			$rs        = $ds3->executesql($sqlCodigo);
// 			$r2        = $ds3->executesql($sqlSMS);
// 			echo json_encode(1);
			



// }

// function verificar($cedula, $codigo){
// global $cedula, $dbr, $db, $dbs;
// $sql = "SELECT * FROM tab_telefonos where ce_trabajador= $cedula and codigo='$codigo'";
// $ds2    = new DataStore($dbr, $sql);
// if($ds2->getNumRows()<=0){
// 				//insert 
// 	echo json_encode(0);
// }else{
// 				//update
// 	$sqlCodigo="UPDATE 
// 	public.tab_telefonos 
// 	SET 
// 	estatus = TRUE
// 	WHERE 
// 	ce_trabajador = $cedula";

// 	$ds3  = new DataStore($dbr);
// 	$rs        = $ds3->executesql($sqlCodigo);
// 	echo json_encode(1);
// } 


// }

// function generarCodigo($longitud) {
//  $key = '';
//  $pattern = '1234567890';
//  $max = strlen($pattern)-1;
//  for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
//  return $key;
// }

?>