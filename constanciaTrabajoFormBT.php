<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHCT';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];

///////////////////////
$ci = $_SESSION['cedula'];

$enlace = @sybase_connect('10.4.208.10:6100','consultaphp','a123456')
or die("¡No pudo conectarse!");
//$db     = "sidial";
//echo "Se conectó satisfactoriamnete";
$res=sybase_query("SELECT  * FROM VW_SUMA_ASIGNACIONES WHERE CE_TRABAJADOR =$ci ");
 $cant=sybase_num_rows($res);
$html="<p class='text-primary'>Por favor, seleccione el Tipo de Personal y Ubicaci&oacute;n</p>  ";
if($cant>1){
while($reg=sybase_fetch_array  ($res)){
	$html.="<input type='checkbox' name='tipo[]' id='tipo' value= '".$reg['CO_UBICACION']."-".$reg['CO_TIPOPERSONAL']."'> ".ucwords(strtolower($reg['DE_TIPOPERSONAL']))." de ".ucwords(strtolower($reg['DE_UBICACION']))."<br>";
	}
}else{
	$reg=sybase_fetch_array  ($res);
	$_SESSION['co_tp']= $reg['CO_TIPOPERSONAL'];
	$_SESSION['co_ub']= $reg['CO_UBICACION'];
	$t= $_SESSION['co_tp'];
	}
?>
<script language="javascript" type="text/javascript" src="./js/constanciaTrabajoBT.js"></script> 
 <fieldset>
  <legend>


  <h3> <img src="images/client_account_template.png" width="32" height="32" /> Constancia de Trabajo</h3></legend>
  <form name="form_destinatario" id="form_destinatario" action="constanciaTrabajoBT.php" method="post"  > 
  <?php echo ($cant>1?$html: '');

   ?>
   <p class="text-primary">Por favor, seleccione el destinatario de la carta de trabajo</p>  
<p><input name="btndestino" class=""   type="radio" value="1" onChange="chequear()" onFocus="chequear()" > A Quien Pueda Interesar </p>
  <p>
  <div class="row">  <div class="col-lg-3"><input name="btndestino" class=""  type="radio" value="2" onChange="chequear2()" onFocus="chequear2()">
    A un Tercero  </div>  
   <div class="col-lg-4">
    <input name="txtcdestinatario" class="form-control" type="text" id="txtcdestinatario" required="required" title="Ingrese un destinatario Ejemplo: 'Banco de Venezuela'"   size="30" placeholder="Ingrese un destinatario" style="text-transform:uppercase" onKeyDown="boton_act()" > 
    </div>
    </div>
  </p><br />
    <input type="hidden" id="op" name="op" value="creaConstacia">
     <input type="hidden" id="cantidad" name="cantidad" value="<?php echo $cant; ?>">
    <input name="btnsolicitar"  type="submit" id="btnsolicitar" class="btn btn-success" value="Solicitar" >
    <input name="btncancelar" type="reset" id="btncancelar" class="btn btn-default" value="Cancelar">
   </form>
  
</fieldset>
<small class="text-muted" style="font-size:12px;">En caso de presentar alguna discrepancia en la informaci&oacute;n, por favor dir&iacute;jase al Departamento de N&oacute;mina ubicado en el edificio Antiguo Rectorado.</small>

