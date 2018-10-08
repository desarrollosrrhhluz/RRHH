<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHED';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];
$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$_SESSION['fe_inicio_bc']="2014-10-01";
 $ci = $_SESSION['cedula'];

		$db     = "sidial";
		$sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where CE_TRABAJADOR=$ci and ESTATUS='A' and ESTATUS!='F' and ESTATUS!='R' ";
		
        $ds     = new DataStore($db, $sql2);
    	if($ds->getNumRows()==0){
		
		// header ('Location: errorRRHH.html');
		 //exit;
		}else{

		$row = $ds->getValues(0);
		$_SESSION['sexo']=$row['SEXO'];
		$_SESSION['tipopersonal']=$row['TIPOPERSONAL'];
		$_SESSION['coubicacion']=$row['CO_UBICACION'];
		$_SESSION['ano_proceso']=2014;
		$_SESSION['nombres']=$row['NOMBRES'];
    
}
?>
<script language="javascript"  src="./js/EvaluacionDesempenoBT.js"></script>
<h2><img src="images/document_layout.png" width="32" height="32">Evaluación de desempeño</h2><hr>


<div id="div_form_fam" >

<input name="cedula_trabajadorEvaluacion" type="hidden" value="<?php echo $ci; ?>" id="cedula_trabajadorEvaluacion" title="Ej:17896358" pattern="[0-9]+"/>

<div id="destino" ></div>
<div id="EncabezadoEvaluando" ></div>
<div style="display:none"  align="center" id="DivEvaluar"></div>
<div id="resultadosConsultaEvaluacion"  style='top:40'   ALIGN="center"></div>
</div>



<div id="Evaluaciondialogo" style='top:50' ></div>
<div id="Evaluaciondialogo2"  style='top:50' ></div>
<div id="EvaluacionTareas"  style='top:50' ></div>


<div class="modal fade" id="myModal">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title"><b>Advertencia</b></h3>
      </div>
      <div class="modal-body">
        <p class="small">Desea terminar la evaluación?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Volver</button> 
        <button type="button" class="btn btn-primary" id="btn_comfirma_evaluacion" >Continuar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="myModalMensajeExtito">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title"><b></b></h3>
      </div>
      <div class="modal-body">
        <p class="small">EVALUACIÓN CULMINADA CON ÉXITO</p>
      </div>
     
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->