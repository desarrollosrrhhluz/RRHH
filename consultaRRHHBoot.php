<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : gestionSeguridad.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creaci&oacute;n      : 08/06/2011
		Objetivo / Resumen  : administraci&oacute;n de seguridad web
		Ultima Modificaci&oacute;n : 07/06/2011
------------------------------------------------------------------------------------------------ */
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
include_once(LUZ_PATH_INCLUDE  . '/FormItems.inc.php');
include_once(LUZ_PATH_INCLUDE  . '/Fecha.inc.php');
include_once(LUZ_PATH_SECURITY . "/verificaAccesoUsuario.inc.php");

$appName         ='RRHH';
$urlError        = "errorRRHH.html";
$urlDeshabilitado = "deshabilitadoRRHH.html";
verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);

$cual = array("pdf"            ,"detalle_especial"       , "arc", "eps"                              );
$tcual= array("Detalle de pago", "N&oacute;mina especial", "ARC", "ESTIMADO de Prestaciones Sociales");
//$cual = array("pdf"            ,"detalle_especial"       , "eps"                              );
//$tcual= array("Detalle de pago", "N&oacute;mina especial",  "ESTIMADO de Prestaciones Sociales");

$ano    = anos();
$meses  = meses();
$nmeses = nmeses();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="oamesty" >
    <title>Sistema de Servicios en L&iacute;nea de la Universidad del Zulia - Recursos Humanos</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/rrhh.css" rel="stylesheet">
  </head>
  <body>
    <noscript><meta http-equiv="Refresh" content="0;url=errorJavaScript.html"/></noscript>
    <header class="navbar navbar-default navbar-fixed-top">
        <div id="cabecera">
            <div id="title-left"></div>
            <div id="title-right"></div>
            <div id="title-middle"></div>
        </div>

      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><span class="hidden-xs hidden-sm">Sistema de Servicios en L&iacute;nea de la Universidad del Zulia - </span>Modulo de Recursos Humanos</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown sessionOK">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo utf8_encode($_SESSION['usuario']); ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo LUZ_URL_SECURITY; ?>/logout.php" data-toggle=\"collapse\" data-target=\".navbar-collapse\">Cerrar sesi&oacute;n</a></li>
                <li><a href="#" data-toggle=\"collapse\" data-target=\".navbar-collapse\">Cambiar Correo-E</a></li>
              </ul>
            </li>
            <li><a data-toggle="modal" href="#mdContact">Contactos</a></li>
            <li><a data-toggle="modal" href="#mdAbout">Acerca de...</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </header>
    
    <main id="wrap" class="container">
      <div id="apps" class="form-app">
        <h3 class="form-signin-heading">Consultas</h3>
        <?php echo frm_select('cual', $tcual, $cual, "", "class=\"form-control\" onchange=\"showConsulta(this)\" placeholder=\"Seleccione opci&oacute;n\" autofocus"); ?>
	<label id='anomes'>A&ntilde;o / mes:</label> 
        <?php echo frm_select('ano', $ano, $ano, "", "class=\"form-control\" onchange=\"showEspecial(this)\""). frm_select('mes', $meses, $nmeses, "", "class=\"form-control\""); ?>
        <div id="cmbnomesp"></div>
        <input id="boton" onclick="ventana()" class="form-control btn btn-primary btn-block" type="button" name="submit" value="Consultar"/>
      </div>
    </main>

    <footer>
      <div class="container">
        <p class="text-muted credit">2014. <a href="http://www.luz.edu.ve">Universidad del Zulia</a> - <a href="http://www.rrhh.luz.edu.ve">RRHH</a> - <a href="http://www.ditic.luz.edu.ve">Diticluz</a>.</p>
      </div>
    </footer>


<div class="modal fade" id ="mdMsg">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      </div>
      <div id="divMsg" class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id ="mdContact">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" data-toggle="collapse" data-target=".navbar-collapse">&times;</button>
        <h4 class="modal-title">Contactos</h4>
      </div>
      <div class="modal-body">
            <ul>
            <li>Director: </li>
            <li>Lider Depto de Nomina ...</li>
            </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="collapse" data-target=".navbar-collapse">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id ="mdAbout">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" data-toggle="collapse" data-target=".navbar-collapse">&times;</button>
        <h4 class="modal-title">Acerca de...</h4>
      </div>
      <div class="modal-body">
        <img class="modal-image" src="images/logoditicluz.jpg" alt="DITICLUZ" />
        <p></p>
        <p>Sistema de Servicios en L&iacute;nea de la Universidad del Zulia</p>
        <p>Modulo de Recursos Humanos</p>
        <p>Desarrollado por: Ing. Oscar Amesty V. Mgs.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="collapse" data-target=".navbar-collapse">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id ="mdChgMail">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Cambio de correo electronico</h4>
      </div>
      <div class="modal-body">
        <form class="form">
          <input id="da_mail_new" type="email" class="form-control" placeholder="Correo-e nuevo">
          <input id="da_mail_new2" type="email" class="form-control" placeholder="Repita correo-e nuevo">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="goUserChgMail"  type="button" class="btn btn-primary" data-dismiss="modal">Enviar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    <!-- nucleo JavaScript  -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/rrhh.js"></script>
  </body>
</html>