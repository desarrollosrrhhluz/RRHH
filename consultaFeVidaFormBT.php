<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHFV';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];

$ci=$_SESSION['cedula'];




?>
 <fieldset>
 <legend>
  <h3> <img src="images/client_account_template.png" width="32" height="32" /> Consulta de Fe de Vida</h3></legend>
 <?php

 $tipo=['FP'=>' Fecha de Presencia',
 		'VD'=>'Fecha Visita Domiciliaria',
 		'VH'=>'Fecha Visita Hospitalaria',
 		'FV'=>'Fecha Entrega Fe de Vida',
 		'CV'=>'Fecha Constancia de Viudez',
 		'CS'=>'Fecha Constancia Solteria',
 		'CE'=>'Fecha Constancia de Estudio'];

$db     = "sidial";
		$sql3 =  "select * from V_DATPER where CE_TRABAJADOR=$ci and SUBSTRING(TIPOPERSONAL,4,1) in ('8','9')";
        $ds3     = new DataStore($db, $sql3);
    	if($ds3->getNumRows()==0){
		

		echo "<h4>Usted no forma parte del personal Jubilado y/o Incapacitado </h4><br>";
		
		}else{
		$sql2 =  "select *,convert(char(10), FECHA, 103) as FE_VIDA from VW_FE_DE_VIDA where CEDULA=$ci";
        $ds     = new DataStore($db, $sql2);
        $i=$ds->getNumRows();
        $j=0;
    	if($i==0){		
		echo "<h4>Usted no tiene registros de Fe de Vida  en el año en curso</h4><br>";
		}else{
			echo "<h4>Sus registros de fe de vida son los siguientes </h4><br>";
			while ( $j < $i) {
				$row = $ds->getValues($j);

				echo "- <b>".$row['FE_VIDA']." ".$tipo[$row['CODIGO']]."</b><br>";



				$j++;
			}

			echo 'Puede Imprimir un comprobate de esta consulta para sus archivos haciendo clic <a href="comprobanteFeVidaPDF.php"> aquí </a>';		
	
		}
	}

  ?>
  
</fieldset>
<small class="text-muted" style="font-size:12px;">En caso de presentar alguna discrepancia en la informaci&oacute;n, por favor dir&iacute;jase la oficina de Atencion al Jubilado.</small>

