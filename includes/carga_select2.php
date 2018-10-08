<?php
//Creado por Cesar Walter Gerez en Micodigobeta.com.ar
//A manera de ejemplo solo lo realizo con array, pero para que realmente sea dinamico se debería traer las opciones de una base de datos.

$options = " host='10.4.15.55' port='5432' user='desarrollorrhh' password='a123456' dbname='rrhhdb' ";
$conn= pg_connect($options) or die ("Error al crear la conexion");
$area= $_REQUEST["id"];
//realizamos la consulta
	
switch($area){
     case 0:
		echo 
		'<option value="0">Seleccione</option>';
		break; 
     case 1:
		resultado();
		break; 
	case 2:
		resultado();
		break; 
	case 3:	
		echo 
		'<option value="0">Otro Motivo</option>';
		break; 
	
}		
function resultado(){
global $conn, $area;
  $consulta = 'SELECT * FROM de_permisos_laborales WHERE id_t='.$area.''; 
    $x_consulta = pg_query($conn, $consulta) or die ("No se puede consultar el id "); 
	//$res = pg_fetch_array($x_consulta);
  while($res = pg_fetch_array($x_consulta)){
  echo '<option value="'.$res['id_m'].'">'.$res['descripcion'].'</option>';
    }
  }

?>