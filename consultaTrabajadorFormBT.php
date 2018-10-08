<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
//include_once("../Seguridad/verificaSesion.inc.php");
  session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
include_once('includes/Funciones.inc.php');


$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";


       $ci = $_SESSION['cedula'];
	
		?>
<style>
.font_12{
	
	font-size:14px;}
</style>
  <script language="javascript"  src="./js/consultaTrabajador.js"></script>
   <script language="javascript" src="plugins/jquery.tablePagination.0.2.min.js"></script>
<legend><div class="row">
<div class="col-md-9"><h2><img src="images/user_edit.png" width="32" height="32" />Consulta a Sidial</h2></div>
<div align="right" class="col-md-3"><span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" id="img_ayuda" title="Ayuda"></span></div>
</div>
</legend>
<div id="formulario" >
<form name="form_consulta" id="form_consulta" method="post">
 <div class="row"><div class="col-lg-2"><label>Buscar:</label></div></div>
  <div class="row">
 <div class="col-lg-4"><input name="valor" type="text" id="valor" class="form-control" required="required"/></div></div>
<!-- <div align="center">-->
 <input type="hidden" name="op" id="op_form" value="buscar" />
 <input type="submit" value="Enviar" id="btn_guardar" class="btn btn-success" /> 
</form>
<!--</div>-->

  <div id="destino"></div>
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title"><b>Advertencia</b></h3>
      </div>
      <div class="modal-body">
        <p class="small">Terminada esta operacion estara oficialmente solicitando el inicio de su proceso de jubilacion</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Volver</button>
        <button type="button" class="btn btn-primary" id="btn_comfirma" >Continuar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="myAyuda">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><span class="glyphicon glyphicon-info-sign"></span>  Informaci&oacute;n Importante</h4>
      </div>
      <div class="modal-body">
        <div class="small" style="font-size:12px">
        <strong>Informacion de Interes:</strong><br />
<ul>
<li>Los que hayan cumplido 25 años de servicio.</li>
<li>Los mayores de 55 años de edad al cumplir 20 años de servicio.</li>
<li>Los mayores de 60 años de edad al cumplir 15 años de servicio.</li>
</ul>

</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="datos_personales">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><span class="glyphicon glyphicon-info-sign"></span>  Datos Personales</h4>
      </div>
      <div class="modal-body">
        <div class="small" style="font-size:12px" id="contenido_datos_personales">
      

</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="datos_del_cargo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><span class="glyphicon glyphicon-info-sign"></span>  Datos del Cargo</h4>
      </div>
      <div class="modal-body">
        <div class="small" style="font-size:12px" id="contenido_datos_cargo">
      

</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


 <div id="error" class="error"></div>


