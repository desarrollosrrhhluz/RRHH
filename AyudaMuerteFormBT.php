<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHAM';
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
		//$_SESSION['parentesco']=$row['NOMBRES'];
}
?>
<script language="javascript"  src="./js/AyudaMuerteBT.js"></script>

<h3><img src="images/document_layout.png" width="32" height="32">Ayuda por Muerte</h3><hr>
<div id="HijosRegistradosMuerte" style="display:none">

</div>

<div id="div_form_fam_muerte" >
<fieldset><legend><h2><img src="images/user_edit.png" width="32" height="32" />Datos del Familiar</h2></legend>
<form action="" method="post" name="form_ayuda_muerte" id="form_ayuda_muerte">

<div class="row">
	<div class="col-md-6">
	<div class="form-group">
	<label for="">C.I. Otro Prog.:</label>
	<input name="ce_otro_p" pattern="[0-9]{5,8}" id="ce_otro_p" type="text" class="form-control" placeholder="Ej.18263589" required="required" title="Debe escribir n&uacute;mero de c&eacute;dula del otro progenitor (OBLIGATORIO)"/>
	</div>	
	</div>
	<div class="col-md-6">
	<div class="form-group">
	<label class="control-label">Nombre Otro Prog:</label>
	<input name="nomb_otro_p" type="text" class="form-control" pattern="[a-zA-Z'? ?]+" required="required" id="nomb_otro_p"  title="Escriba nombres y apellidos separados por coma(,)" placeholder="Ej.Carlos Fernandez"/>
	</div>	
	</div>

</div>
<div class="row">
	<div class="col-md-6">
	<div class="form-group">
	<label>Fecha de Nacimiento:</label>
	<input name="fe_nac_fam" id="fe_nac_fam" type="text" class="form-control" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" placeholder="Ej.02/06/2013" required title="Escriba fecha de nacimiento en formato d&iacute;a/mes/a&ntilde;o"/>
	</div>
	</div>
	<div class="col-md-6">
	<div class="form-group">
	<label>Sexo:</label>
<select name="sexo_fam" id="sexo_fam" class="form-control" required title="Seleccione el sexo de familiar" >
  <option value="">-Seleccione-</option>
  <option value="F">Femenino</option>
  <option value="M">Masculino</option>
</select>
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
	<input name="nombre2" type="text" class="form-control" id="nombre2"  pattern="[a-zA-Z'? ?]+" placeholder="Ej.Luis"  title="Escriba el segundo nombre"/>
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
	<input name="apellido2" type="text" class="form-control" id="apellido2"  pattern="[a-zA-Z'? ?]+"  placeholder="Ej.Garcia"  title="Escriba el segundo apellido"/>
	</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
	<div class="form-group">
	<label>Acta de Defunción:</label>
	<input name="actadefuncion" id="actadefuncion" type="text" class="form-control" required="required" pattern="\d{2}[\-]\d{2}[\-]\d{4}[\-]\d{4}" placeholder="Ej.02-06-2000-2345"  title="Ejemplo: 02-06-2000-2345 (dia-mes-ano-Acta ) "/>
	</div>
	</div>
	<div class="col-md-6">
	<div class="form-group">
	<label>Fecha Fallecimiento:</label>
	<input name="fe_falle_fam" id="fe_falle_fam" type="text" class="form-control" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" required="required" placeholder="Ej.02/06/2013" title="Escriba fecha de fallecimiento en formato d&iacute;a/mes/a&ntilde;o"/>
	</div>
	</div>

</div>



<div align="center">
<input type="hidden" name="op" id="op" value="NuevaSolicitudmuerte"/>
<input type="hidden" name="id_familiar_editar" id="id_familiar_editar"/>
<input type="hidden" name="registra" id="registra" value="<?php echo $_SESSION['cedula']; ?>"/>
<input type="hidden" name="sexor" id="sexor" value="<?php echo $_SESSION['sexo']; ?>"/>
<div id="resultadosayudamuerte"  style='top:40'   ALIGN="center"></div>
<input name="btn_guardar_familiar" id="btn_guardar_familiar" type="submit" value="Guardar" class="btn btn-success" />
<input name="btn_cancelar_familiar" id="btn_cancelar_familiar" type="button" onclick="CancelarNuevoRegistroHijo();" value="Cancelar" class="btn btn-default"  />
</div>
</form>
</fieldset>
<div id="destino"></div>
</div>