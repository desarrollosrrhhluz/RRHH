<?php 
 $enlace = @sybase_connect('SIDIAL_SRV','consultaphp','a123456')
       or die("¡No pudo conectarse!");
		  //echo "Se conectó satisfactoriamnete";
      $res=sybase_query("SELECT distinct (CO_UBICACION_CORTA), DESCRIPCION_CORTA from V_UBICACION order by CO_UBICACION_CORTA");
     echo "<option value=''>-Seleccione-</option>"; 
	 echo "<option  value='TODOS'>-TODOS-</option>";
	while  ( $reg=sybase_fetch_array  ($res)){
	
	echo "<option value=".$reg['CO_UBICACION_CORTA'].">".$reg['DESCRIPCION_CORTA']."</option>";

	}
	
  
?>