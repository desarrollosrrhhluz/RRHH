<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHPP';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];

$ci=$_SESSION['cedula'];





?>
 <fieldset>
 <legend>
 
 <?php

 $tipo=['6'=>'Técnico Superior Universitario',
 		'7'=>'Licenciatura o equivalente',
 		'8'=>'Especialidad',
 		'9'=>'Maestría',
 		'10'=>'Doctorado'];
$porc =['6'=>'12%',
 		'7'=>'14%',
 		'8'=>'16%',
 		'9'=>'18%',
 		'10'=>'20%'];

$db     = "sidial";
$dbr	= "rrhh";
		$sql3 =  "select * from V_DATPER where CE_TRABAJADOR=$ci and ESTATUS not in ('R') and PRINCIPAL='S'";
        $ds3     = new DataStore($db, $sql3);
    	if($ds3->getNumRows()==0){
		$fila = $ds3->getValues(0);

		echo '<h3> <img src="images/client_account_template.png" width="32" height="32" /> Prima por Profesionalización / Especialización - CCU III </h3></legend>';

		echo "<h4>Usted no forma parte del personal activo de la Universidad del Zulia </h4><br>";
		
		}else{
		$fila = $ds3->getValues(0);

		if(substr($fila['TIPOPERSONAL'],0,1)==1){

		echo '<h3> <img src="images/client_account_template.png" width="32" height="32" /> Prima por Especialización, Maestría o Doctorado - CCU III </h3></legend>';

		}else{

		echo '<h3> <img src="images/client_account_template.png" width="32" height="32" /> Prima por Profesionalización - CCU III </h3></legend>';

		}


		$sql2 =  "select * from admon_personal.tmp_prima_profesional where ce_trabajador=$ci";
        $ds     = new DataStore($dbr, $sql2);
        $i=$ds->getNumRows();
        $j=0;
    	if($i==0){		

		echo "<h4>Usted no tiene registros de Titulos Universitarios en ".(substr($fila['TIPOPERSONAL'],0,1)==1 ? 'CEDIA' : 'la Dirección de Recursos Humanos' )." </h4><br>";
		}else{
		
				$row = $ds->getValues(0);

			if(substr($fila['TIPOPERSONAL'],3,1)=='8' || substr($fila['TIPOPERSONAL'],3,1)=='9'){

				if (substr($fila['TIPOPERSONAL'],3,2)=='91' || substr($fila['TIPOPERSONAL'],3,2)=='92'  ){
					$var='pensión';
				}else{
					$var='jubilación';
				}

			echo "Su último nivel académico registrado en el Sistema Integrado de Recursos Humanos antes de su fecha de ".$var.", y que será tomado en cuenta para los cálculos relacionados a la Tercera Convención Colectiva del sector Universitario es:<b> ".$tipo[$row['nivel']]." </b>. Correspondiendole por este concepto el <b>".$porc[$row['nivel']]."</b> de su salario básico." ;

			}else{

			echo "Su último nivel académico registrado en el Sistema Integrado de Recursos Humanos, y que será tomado en cuenta para los cálculos relacionados a la Tercera Convención Colectiva del sector Universitario es:<b> ".$tipo[$row['nivel']]." </b>. Correspondiendole por este concepto el <b>".$porc[$row['nivel']]."</b> de su salario básico." ;

			}
				
			}
	
	
		}
	

  ?>
  
</fieldset><br><br>
<small class="text-muted" style="font-size:12px;">¿ Que debe hacer en caso de tener discrepancias con la información presentada ? clic <a  href="#" class="" title="Ayuda" data-toggle="modal" data-target="#modalAyuda"> aquí </a></small>

<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4  id="myModalLabel">Informaci&oacute;n Importante</h4>
      </div>
      <div class="modal-body text-justify">
     <b> Recaudos</b>
<ul>
    <li>Solicitud formal dirigida al director (a) de Recursos Humanos con atención al Sub-proceso de Formación de Talento Humano.</li>
    <li>Presentar ante esta dirección el título universitario obtenido en fondo negro certificado por el Cedia.</li>
</ul>
<b>Procedimiento</b>
<br>
Consignar los recaudos antes mencionados en la taquilla del proceso de apoyo Organización y Administración de la Información (archivo) ubicada en la planta baja de la antigua sede rectoral de LUZ.
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
	var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 


//$("#modalAyuda").modal("show");
};

</script>