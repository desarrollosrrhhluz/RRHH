<?php 
$options = " host='10.4.15.55' port='5432' user='desarrollorrhh' password='a123456' dbname='desarrolloRRHH' ";
$conn_pg= pg_connect($options) or die ("Error al crear la conexion");

 $sql = "SELECT * FROM mst_sitios_estudio where  nivel=3 order by nombre";
 $x_sql= pg_query($conn_pg, $sql); 
 echo "<option value=''>- Seleccione -</option>";
 while ($arr = pg_fetch_array($x_sql)){	
 echo "<option value=".$arr['id_sitio'].">".trim($arr['nombre'])." (".$arr['ubicacion'].")</option>";
 }  
?>