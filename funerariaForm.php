<?php  
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHPF';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];


$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$_SESSION['fe_inicio_bc']="2016-10-04";
 $ci = $_SESSION['cedula'];
 

///////////////////////////////////////////////////////	
		$db     = "sidial";
		$sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where CE_TRABAJADOR=$ci and ESTATUS in ('A','C','F') and ESTATUS!='R' and ESTATUS!='S' ";
        $ds     = new DataStore($db, $sql2);
    	if($ds->getNumRows()==0){
		
		// header ('Location: errorRRHH.html');
		 //exit;
		}else{
      
		$row = $ds->getValues(0);
		$_SESSION['sexo']=$row['SEXO'];
		$_SESSION['tipopersonal']=substr($row['TIPOPERSONAL'], 0, 1);
		$_SESSION['coubicacion']=$row['CO_UBICACION'];
		$_SESSION['ano_proceso']=2017;
		$_SESSION['estatus']=$row['ESTATUS'];
		$_SESSION['nombres']=trim(utf8_encode($row['NOMBRES']));
        }
?>
<script language="javascript"  src="./js/funeraria.js"></script>

<input type="hidden" name="tipopersonal" id="tipopersonal" value="<?php echo $_SESSION['tipopersonal']; ?>" >
<div class="row">
<div class="col-md-10"><h2><img src="images/document_layout.png" width="20" height="20">Previsi&oacute;n Gastos Funerarios</h2></div>
<div class="col-md-2 text-right"><a href="#" class="btn-lg" title="Ayuda" data-toggle="modal" data-target="#modalAyuda">
  <i class="glyphicon glyphicon-question-sign"></i>
  </a></div>
</div>

<div id="familiaresRegistrados">
<fieldset><legend><!-- <h4><img src="images/group.png" width="32" height="32" />Familiares Registrados</h4> -->

<div class="row">
<div class="col-md-6"><input type="button" class="btn btn-primary" id="btnNuevoFamiliar" value="Incluir"></div>
<div class="col-md-6 text-right"> <h4>Cupos Disponibles: <span id="numero"></span> </h2></div>
</div>
</legend>

<div id="familiares_existentes" ></div>
</fieldset>

</div>
<div id="div_form_fam" >


<div id="destino"></div>

</div>

<form action="" method="post" name="form_fam" id="form_fam">
<div class="modal fade" id="modalFuneraria" tabindex="-1" role="dialog">
    
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title">Datos del Familiar</h4>

      </div>
      <div class="modal-body">
<div class="row" id="datos_padre">
<div class="col-md-6">
<div class="form-group">
<label>C.I. Otro Progenitor:</label>
<!-- <select name="nac1" id="nac1" size="0"  class="form-control input-sm"><option value="V" selected>V</option><option value="E">E</option></select> -->
<input name="ce_otro_p"  placeholder="9999999" pattern="[0-9]{5,8}" id="ce_otro_p" type="text" class="form-control input-sm" required="required"  title="Debe escribir n&uacute;mero de c&eacute;dula del otro progenitor (OBLIGATORIO). Debe ser distinto de <?php echo $_SESSION['cedula'] ?> "/>
</div>
</div>
<div class="col-md-6"><div class="form-group">
<label>Nombre Otro Prog.:</label>
<input name="nomb_otro_p" type="text" class="form-control input-sm" pattern="[\W\s\D]+"  placeholder="Ejemplo: Garcia Bracho Pedro Jose" required="required" id="nomb_otro_p" size="40" title="Escriba nombres y apellidos separados por coma(,)"/>
</div></div>
</div>
<div class="row">
<div class="col-md-6">
  <div class="form-group">
<label>Fec. de Nac.</label>
<input name="fe_nac_fam" id="fe_nac_fam" type="text" pattern="(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d" class="form-control input-sm" placeholder="Dia/Mes/a&ntilde;o" title="Escriba fecha de nacimiento en formato d&iacute;a/mes/a&ntilde;o"/>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label>Parentesco:</label>
<select name="parentesco_fam" id="parentesco_fam" disabled="disabled" class="form-control input-sm" required title="Seleccione el parentesco" >
  
</select>
</div>
</div>
</div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<label>C.I. familiar:</label>
<input name="ce_familiar" id="ce_familiar"  type="text" class="form-control input-sm" pattern="[0-9]{6,8}" title="Escriba la c&eacute;dula del familiar"/>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label>Sexo:</label>
<select name="sexo_fam" id="sexo_fam" class="form-control input-sm" required title="Seleccione el sexo de familiar" >
  <option value="">-Seleccione-</option>
  <option value="F">F</option>
  <option value="M">M</option>
</select>
</div>
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<label class="control-label">Primer Nombre:</label>
<input name="nombre1" type="text" class="form-control input-sm" id="nombre1" pattern="[\W\s\D]+"  required="required" placeholder="Carlos"   title="Escriba el primer nombre"/>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label class="control-label">Segundo Nombre:</label>
<input name="nombre2" type="text" class="form-control input-sm" id="nombre2" pattern="[\W\s\D]+"  placeholder="Luis"  title="Escriba el segundo nombre"/>
</div>
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<label>Primer Apellido:</label>
<input name="apellido1" type="text" class="form-control input-sm" id="apellido1" pattern="[\W\s\D]+" required="required" placeholder="Prieto"    title="Escriba el primer apellido"/>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label>Segundo Apellido:</label>
<input name="apellido2" type="text" class="form-control input-sm" id="apellido2" pattern="[\W\s\D]+"  placeholder="Garcia"  title="Escriba el segundo apellido"/>
</div>
</div>
</div>
<!--  <div class="col-md-6">
<div class="form-group">
</div>
</div>


<div class="row">
<div class="col-lg-3 col-md-3">
<label>Condicion personal:</label></div><div class="col-lg-3 col-md-3">
<select name="condicion_fam" id="condicion_fam" class="form-control input-sm" required="required" title="Seleccione la condición personal de su familiar">
  <option value="">-Seleccione-</option>
  <option value="1">Sin Discapacidad</option>
  <option value="2">Con Discapacidad</option>
</select>
</div>
<span id="tipo_incapacidad">
<div class="col-lg-3 col-md-3"><label>Numero CONAPDIS:</label></div>
<div class="col-lg-2 col-md-2"><input name="conapdis" type="text" placeholder="0000" pattern="[0-9]+" class="form-control input-sm" id="conapdis"  title="Codigo CONAPDIS "/></div>
</span>
</div>
 -->



     
      </div>
      <div class="modal-footer">
       <input type="hidden" name="op" id="op_familiar" value="guarda_familiar"/>
<input type="hidden" name="id_fo" id="id_fo"/>
<input type="hidden" name="registra" id="registra" value="<?php echo $_SESSION['cedula']; ?>"/>
<input type="hidden" name="sexor" id="sexor" value="<?php echo $_SESSION['sexo']; ?>"/>
<input name="btn_guardar_familiar" id="btn_guardar_familiar" type="submit" value="Guardar" class="btn btn-success" />

<input name="btn_cancelar_familiar" id="btn_cancelar_familiar" type="reset" value="Cancelar" class="btn btn-default"  />

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

</div><!-- /.modal -->

<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4  id="myModalLabel">Informaci&oacute;n Importante</h4>
      </div>
      <div class="modal-body">
      <ul class="small">
        <li>Si presenta alg&uacute;n inconveniente relacionado a sus familiares (padre, madre,
c&oacute;nyuge o persona con la que mantiene uni&oacuten estable de hecho, hijos) acudir a la Direcci&oacute;n de Recursos Humanos a trav&eacute;s del proceso de Administraci&oacute;n de Seguridad y Bienestar Social </li>
        <li>Si tiene hijos mayores de 26 a&ntilde;os y no aparecen en la lista
presentada, puede agregarlos haciendo clic en el bot&oacute;n incluir</li>
        <li>Puede incluir familiares como hermanos, sobrinos y nietos sin l&iacute;mite
de edad (el titular que no tenga a sus padres vivos podr&aacute; incluir a
los suegros sin importar el g&eacute;nero)</li>
        <li>Su grupo familiar no puede exceder de 10 personas</li>
        <li>NO puede incluir familiares que trabajen en LUZ o que posean condici&oacute;n de
jubilados o pensionados, debido a que ya poseen el beneficio</li>
      </ul>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


</form>