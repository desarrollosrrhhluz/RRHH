<?php

include_once('app_class/DataStore.class.php');
include_once('includes/Funciones.inc.php');

    
  $dbs="sidial2";

		$ano = $_GET['ano'];
		$mes = $_GET['mes'];
		$cod = $_GET['cod'];
		$tip = $_GET['tip'];
		
		$nombre=generar_contrasena();
 		
	
		$sql =  "select *,(SELECT DISTINct NOMBRES FROM V_DATPER where CE_TRABAJADOR=VW_DETALLE_PAGO_DEDUCCIONES.CE_TRABAJADOR) as NOMBRES from VW_DETALLE_PAGO_DEDUCCIONES where CO_CONCEPTO='$cod' and ANO=$ano AND MES=$mes and IN_NOMINA='$tip' and  STATUS_DEDUCCION!=1 ";
        $dss     = new DataStore($dbs, $sql);
    	if($dss->getNumRows()<=0){
           
        } else {
			
		$i=0;
		$contenido='';
		$j=$dss->getNumRows();
		while($i < $j){
			$fila = $dss->getValues($i);
			
			$cadena=  number_format($fila['CE_TRABAJADOR'],0,'','').';'. utf8_encode($fila['NOMBRES']).';'.$ano.';'.$mes.';'.$cod.';'.number_format($fila['DEDUCCIONES'],2,',','.').';'.number_format($fila['MO_SALDO'],2,',','.').';'.chr(13).chr(10).'';	
			$contenido=$contenido.''.$cadena;
	    $i++;
	
			}
		}

    
$nombre_archivo = ''.$nombre.'.txt';

fopen($nombre_archivo, 'a+');


if (is_writable($nombre_archivo)) {

   
   if (!$gestor = fopen($nombre_archivo, 'a')) {
        // echo "No se puede abrir el archivo ($nombre_archivo)";
         exit;
   }

   // Escribir $contenido a nuestro arcivo abierto.
   if (fwrite($gestor, $contenido) === FALSE) {
     //  echo "No se puede escribir al archivo ($nombre_archivo)";
       exit;
   }
   

   
   fclose($gestor);
   header("Content-type: text/txt");
header("Content-Disposition: attachment; filename=".$nombre.".txt");

readfile(''.$nombre.'.txt'); 
unlink(''.$nombre.'.txt');
}

?>
