<?php  
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHAMT';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];

session_start();
$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$_SESSION['fe_inicio_bc']="2013-10-01";
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
<script language="javascript"  src="./js/AyudaMatrimonioBT.js"></script>
<h3><img src="images/document_layout.png" width="32" height="32">Ayuda por Matrimonio</h3><hr>
<div id="HijosRegistradosNacimiento" style="display:none">

</div>

<div id="div_form_fam" >
<fieldset><legend><h3><img src="images/user_edit.png" width="32" height="32" />Datos del Familiar</h3></legend>
<form action="" method="post" name="form_ayuda_naci" id="form_ayuda_naci">
<div class="row">
<div class="col-md-6">
<div class="form-group">
<label>Fecha de Nacimiento:</label>
<input name="fe_nac_fam" id="fe_nac_fam" type="text" class="form-control" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" placeholder="Ej.02/06/2013" title="Escriba fecha de nacimiento en formato d&iacute;a/mes/a&ntilde;o"/>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label>C.I. Conyugue:</label>
<input name="ce_familiar" id="ce_familiar" required="required" type="text" class="form-control" pattern="[0-9]+" title="Escriba la cédula del familiar, si no posee escriba 0"/>
</div>
</div>	
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">Primer Nombre:</label>
			<input name="nombre1" type="text" class="form-control" id="nombre1" required="required" placeholder="Ej.Carlos"  pattern="[a-zA-Z'? ?]+"  title="Escriba el primer nombre"/>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
		<label class="control-label">Segundo Nombre:</label>
		<input name="nombre2" type="text" class="form-control" id="nombre2" required="required"  pattern="[a-zA-Z'? ?]+" placeholder="Ej.Luis"  title="Escriba el segundo nombre"/>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group">
		<label>Primer Apellido:</label>
		<input name="apellido1" type="text" class="form-control" id="apellido1" required="required" placeholder="Ej.Prieto" pattern="[a-zA-Z'? ?]+"   title="Escriba el primer apellido"/>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
		<label>Segundo Apellido:</label>
		<input name="apellido2" type="text" class="form-control" id="apellido2" required="required" pattern="[a-zA-Z'? ?]+"  placeholder="Ej.Garcia"  title="Escriba el segundo apellido"/>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
		<label for="sexo">Sexo:</label>
		<select name="sexo_fam" id="sexo_fam" class="form-control" required title="Seleccione el sexo de familiar" >
  <option value="">-Seleccione-</option>
  <option value="F">Femenino</option>
  <option value="M">Masculino</option>
</select>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
		<label>Fecha de Matrimonio:</label>
		<input name="fe_mat_fam" id="fe_mat_fam" type="text" class="form-control" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" placeholder="Ej.02/06/2013" title="Escriba fecha de la boda en formato d&iacute;a/mes/a&ntilde;o"/>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
		<label>Acta de Matrimonio:</label>
		<input name="actanacimiento" id="actanacimiento" type="text" class="form-control" required="required" pattern="\d{2}[\-]\d{2}[\-]\d{4}[\-]\d{4}" placeholder="Ej.02-06-2000-2345"  title="Ejemplo: 02-06-2000-2345 (dia-mes-ano-Acta ) "/>
		</div>
	</div>
</div>



<div align="center">
<input type="hidden" name="op" id="op" value="NuevoHijo"/>
<input type="hidden" name="id_familiar_editar" id="id_familiar_editar"/>
<input type="hidden" name="registra" id="registra" value="<?php echo $_SESSION['cedula']; ?>"/>
<input type="hidden" name="sexor" id="sexor" value="<?php echo $_SESSION['sexo']; ?>"/>
<div id="resultadosayudahijo"  style='top:40'   ALIGN="center"></div>
<input name="btn_guardar_familiar" id="btn_guardar_familiar" type="submit" value="Guardar" class="btn btn-success" />
<input name="btn_cancelar_familiar" id="btn_cancelar_familiar" type="button" onclick="CancelarNuevoRegistroHijo();" value="Cancelar" class="btn btn-default"  />
</div>
</form>
</fieldset>
<div id="destino"></div>
</div>