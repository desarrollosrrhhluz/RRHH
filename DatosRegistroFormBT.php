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
<legend><h2><img src="images/reseller_account_template.png" />&nbsp;&nbsp;Mis Datos de registro</h2></legend>
<form id="formDatosRegistro" name="formDatosRegistro">
<div class="row">
<div class="col-lg-4"><label>Primer Nombre:</label><input type="text" name="primernombre" id="primernombre" class="form-control"placeholder="Primer Nombre" pattern="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,48}" required="required" title="Solo letras, Ejemplo: Andrés"></div>
<div class="col-lg-4"><label>Segundo Nombre:</label><input type="text" name="segundonombre" id="segundonombre" class="form-control" placeholder="Segundo Nombre" pattern="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,48}" required="required" title="Solo letras, Ejemplo: Andrés"></div>
</div>
<div class="row">
<div class="col-lg-4"><label>Primer Apellido:</label><input type="text" name="primerapellido" id="primerapellido" class="form-control" placeholder="Primer Apellido" pattern="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,48}" required="required" title="Solo letras, Ejemplo: Fernandez"></div>
<div class="col-lg-4"><label>Segundo Apellido:</label><input type="text" name="segundoapellido" id="segundoapellido" class="form-control" placeholder="Segundo Apellido" pattern="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,48}"  title="Solo letras, Ejemplo: Fernandez"></div>
</div>
<div class="row">
<div class="col-lg-4"><label>Teléfono:</label><input type="text" name="telefono" id="telefono"  pattern="[0-9]+" maxlength="11" class="form-control" placeholder="Teléfono" required="required" title="Solo numeros, Ejemplo: 0261987654"></div>
<div class="col-lg-4"><label>Dirección:</label><input type="text" name="direccion" id="direccion" class="form-control" placeholder="direccion" required="required"></div>
</div>
<div class="row">
<div class="col-lg-4"><label>Pregunta Secreta:</label><input type="text" name="preguntasecreta" id="preguntasecreta" class="form-control" placeholder="Pregunta Secreta"   required="required" title="Solo letras, Ejemplo:  Cuál es el nombre de mi mascota"></div>
<div class="col-lg-4"><label>Respuesta:</label><input type="text" name="respuesta" id="respuesta" class="form-control" placeholder="Respuesta"  title="Solo letras, Ejemplo: Pelusa" ></div>
</div>

<div class="row">
<div class="col-lg-4"><label>Correo Actual:</label><input type="text" name="correo" id="correo" class="form-control" placeholder="Correo Electronico"  required="required"  disabled="disabled"></div>
<div class="col-lg-4"><label>Cambiar Correo:</label><input type="text" name="cambiocorreo" id="cambiocorreo" class="form-control" placeholder="Correo Electronico" pattern="[a-zA-Z0-9.+_-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9.-]+" title="Ejemplo:correo@gmail.com, correo@hotmail.com" &></div>
</div>

<input type="hidden" name="idusuario" id="idusuario"  >

<br/>

<div id="datosregistroformimg" ></div>

<input type="submit" name="enviarDP" id="enviarDP" class="btn btn-success" value="Guardar" /> &nbsp;&nbsp;<input type="reset" name="resetDP" id="resetDP" class="btn btn-default" value="Cancelar"/>
<input type="hidden" name="op" id="op" value="RegistroFormulario" >
</div>
</form>
</fieldset>