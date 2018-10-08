<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
 $appName         ='RRHHRCCU';
 $urlError        = "errorLoad.html";
 $urlDeshabilitado = "deshabilitadoLoad.html";

 verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError); 
 $_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];

$correo = $_SESSION['email'];
$ci = $_SESSION['cedula'];

$db     = "sidial";

$sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION 

from V_DATPER where CE_TRABAJADOR=$ci and ESTATUS='A' and PRINCIPAL = 'S' ";

$ds     = new DataStore($db, $sql2);

if($ds->getNumRows()==0){



}else{
 
 $row = $ds->getValues(0);

 $_SESSION['tipopersonal']=$row['TIPOPERSONAL'];
 $_SESSION['coubicacion']=$row['CO_UBICACION'];
 $_SESSION['nombretrabajador']=$row['NOMBRES'];

 $cuartaposicion=substr($row['TIPOPERSONAL'],4,1);

}

?>
<script type="text/javascript" src="js/DatosReclamosCCUBT.js"></script>
<fieldset>
<legend><h2><img src="images/Gnome-Edit-Select-All-32.png" />&nbsp;&nbsp;Módulo de atención al usuario</h2></legend>
 <div class="alert alert-info" role="alert"><h4>Bienvenido al módulo de atención al usuario de la Dirección de
Recursos Humanos de LUZ. Puede plantear su duda en el cuadro de texto, y una vez que sea procesada su inquietud, le será enviada una
respuesta a su correo electrónico.<h4>
  </div>
<div id="divFormularioReclamoCCU" style="display:none" >

<form id="formReclamoCCU" name="formReclamoCCU" >


<div class="form-group">
<label>Asunto:</label>
<input type="text" name="asuntoReclamoRRHH" id="asuntoReclamoRRHH" required="required" class="form-control" placeholder=""  >
</div>


<div class="form-group">
<label>Descripción:</label>
<textarea id="TextareaCampoReclamo" name="TextareaCampoReclamo" title="Solo letras, números y los siguientes caracteres."  class="form-control"  rows="4"   required="required"  placeholder=""   >
</textarea> 

</div>


<input type="hidden" name="correoUsuarioReclamoCCU" id="correoUsuarioReclamoCCU"  >
<input type="hidden" name="idusuarioReclamoCCU" id="idusuarioReclamoCCU"  >
<input type="hidden" name="op" id="op" value="InsertReclacoCCU" >


<div id="datosregistroformimgCCU" >
</div>
<br/>

<input type="submit" name="enviarDPReclamo" id="enviarDPReclamo" class="btn btn-success" value="Guardar" /> 
&nbsp;&nbsp;
</form>

</div>



<div id="divReclamoCCUYaRealizado" style="display:none" >
 <div class="alert alert-success" role="alert">
 <h2>Ya estamos procesando su solicitud.</h2>
 </div>
</div>

</fieldset>