<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
 set_time_limit(2000);
session_start();
ini_set('memory_limit','256M'); 
include_once('app_class/DataStore.class.php');
include('includes/Funciones.inc.php');
 require './includes/class.phpmailer.php';

$dbs="sidial";
$dbr="rrhh";
$db="rrhh";
$dbw="web";
$cedula=$_SESSION['cedula'];
$op= $_REQUEST['op'];

//***************************************** 

// }

//
 $sql =  "select * from mst_cargos_tareas where co_cargo=10044";

        $ds     = new DataStore($dbr, $sql);
    	if($ds->getNumRows()<=0){
		 
	
		  }else{
		  $inicio='';
		$i=0;
	$cadena="<table>
		<caption>Tareas Cargos</caption>
		<thead>
			<tr>
			   
				<th>cargo</th>
				<th>tarea</th>
			</tr>
		</thead><tbody>
		";
		$a=$ds->getNumRows() ;

		 while( $i < $a){
		 $fila=$ds->getValues($i);
		 for ($j=1; $j <= 80 ; $j++) { 
		 	$indice='t'.$j;
		 	if(strlen($fila[$indice])>10){
		 	$cadena.=" 
		 	<tr>
				<td>".$fila['co_cargo']."</td>
				<td>".utf8_encode(trim($fila[$indice]))."</td>
			</tr>";
			}
		 }



				
		 $i++;
		 }
		 $cadena.="</tbody>	</table> FIN";
		  
		  echo $cadena;
		  }

