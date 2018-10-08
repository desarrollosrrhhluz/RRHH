<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
include_once('./includes/verificaRoles.php');
verificaRoles(2,3,4);
 
session_start();
$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";



?>
<script type="text/javascript" src="js/DatosRegistroBT.js"></script>
<fieldset>
<legend><h2><img src="images/change_password.png" />&nbsp;&nbsp;Cambio de Contraseña</h2></legend>
<form id="formCambiaContrasena" name="formCambiaContrasena" >
<div class="row">
<div class="col-lg-4"><label>Contraseña Actual:</label><input type="password" name="contrasenaactual" id="contrasenaactual" class="form-control" placeholder="Contraseña actual"  required="required" pattern="|^([a-zA-Z&ntilde;&Ntilde;.,\-_&amp;0-9]+\s*)+$" title="Solo letras, números y los siguientes caracteres . , - _ & están permitidos." ></div>
</div>

<div class="row">
<div class="col-lg-4"><label>Nueva Contraseña:</label><input type="password" name="nuevacontrasena" id="nuevacontrasena" class="form-control" placeholder="Nueva Contraseña"  required="required" pattern="|^([a-zA-Z&ntilde;&Ntilde;.,\-_&amp;0-9]+\s*)+$" title="Solo letras, números y los siguientes caracteres . , - _ & están permitidos."></div>
<div class="col-lg-4"><label>Repita Contraseña:</label><input type="password" name="repitacontrasena" id="repitacontrasena" pattern="|^([a-zA-Z&ntilde;&Ntilde;.,\-_&amp;0-9]+\s*)+$" class="form-control" placeholder="Repita Contraseña"  required="required" title="Solo letras, números y los siguientes caracteres . , - _ & están permitidos."></div>
</div>

<input type="hidden" name="idusuariocambiocontra" id="idusuariocambiocontra"  >

<br />

<div id="datosregistroformimg2" ></div>

<input type="submit" name="enviarDP" id="enviarDP" class="btn btn-success" value="Guardar" /> &nbsp;&nbsp;<input type="reset" name="resetDP" id="resetDP" class="btn btn-default" value="Cancelar"/>
<input type="hidden" name="op" id="op" value="CambiaContrasena" >
</div>
</form>
</fieldset>