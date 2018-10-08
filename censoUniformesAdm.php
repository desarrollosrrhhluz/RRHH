<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();     
include_once('app_class/DataStore.class.php');
include('includes/Funciones.inc.php');
//$db="desarrolloRRHH";
$dbs="sidial";
$db="rrhh";
$_SESSION['cedula'];
$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
	       
			 case 'DatosTrabajador':
	         DatosTrabajador();
			 break;
			 case 'update_tallas':
	         update_tallas();
			 break;

			 }



function DatosTrabajador(){
global $dbs, $db;
extract($_POST);
$cedula_trabajador=$_SESSION['cedula'];

 $sql =  "select * from mst_tallas_uniformes where ce_trabajador=$cedula_trabajador  ";
         $ds     = new DataStore($db, $sql);
    	$j=$ds->getNumRows();
    	if($j<0){
		$res[0]['filas']=0;
							echo json_encode($res) ;
	
		  }else{
		  $rows=$ds->getValues(0);
						   $res[]=$rows;
						   $res[0]['filas']=1;
							echo json_encode($res) ;
		  
				   }
      
}

//**************************************************************
function update_tallas(){
global $dbs, $db;
extract($_POST);
$cedula_trabajador=$_SESSION['cedula'];
$fecharegistro=date("d/m/Y");
$sql =  "select * from mst_tallas_uniformes where ce_trabajador=$cedula_trabajador";
        $ds1     = new DataStore($db, $sql);	
    	if($ds1->getNumRows()<=0){

 $updateDatos="INSERT INTO 
  public.mst_tallas_uniformes
( ce_trabajador,  talla1,  talla2,  talla3, talla4 ,  actualizado,  fecha, ce_responsable) 
VALUES ($cedula_trabajador, '$talla1', '$talla2', ".( empty ( $talla3 ) ? "null" : "'" . $talla3  . "'" ).", ".( empty ( $talla4 ) ? "null" : "'" . $talla4  . "'" ).",  '1', '$fecharegistro', '$cedula_trabajador')";
  
$ds	       = new DataStore($db);
$rs        = $ds->executesql($updateDatos); 

if($rs>0){
echo '{ "message": "Sus datos fueron actualizados exitosamente" }'; 
}else{
echo '{ "message": "Ha ocurrido un error! verifique sus datos y vuelva a intentarlo " }'; 
}			
	
			
			
		}else{
			

$updateDatos="UPDATE 
  public.mst_tallas_uniformes  
SET 
  talla1 = upper('$talla1'),
  talla2 = '$talla2',
  talla3 = ".( empty ( $talla3 ) ? "null" : "'" . $talla3  . "'" ).",
  talla4 = ".( empty ( $talla4 ) ? "null" : "'" . $talla4  . "'" ).",
  actualizado = '1',
  ce_responsable ='$cedula_trabajador',
  fecha = '$fecharegistro'
 
WHERE 
  ce_trabajador = $cedula_trabajador";
  
$ds	       = new DataStore($db);
$rs        = $ds->executesql($updateDatos); 
if($rs>0){
echo '{ "message": "Sus datos fueron actualizados exitosamente" }'; 
}else{
echo '{ "message": "Ha ocurrido un error! verifique sus datos y vuelva a intentarlo 1" }'; 
}

		}
}
?>
