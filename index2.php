<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHH';
$urlError        = "errorRRHH.html";
$urlDeshabilitado = "deshabilitadoRRHH.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);

$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];

 
session_start();
$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$si     = "siaca";
$ce     = "cesta";
$ci = $_SESSION['cedula'];
	

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal de servicios en linea de la direccion de recursos humanos de la Univerisidad del Zulia">
    <meta name="author"  content="Ing. Tonny Medina">
    <link rel="shortcut icon" href="bootstrap/assets/ico/favicon.png">

    <title>Direccion de Recursos Humanos</title>

    <!-- Bootstrap core CSS -->
   <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">
   <link href="bootstrap/dist/css/bootstrap-switch.min.css" rel="stylesheet">
    <!--   <link href="bootstrap/css/bootstrap.css" rel="stylesheet">-->

    <!-- Custom styles for this template -->
    <link href="navbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
    <style>
    #publicidad {
    width:100% ;
    height: 70px;
    background-image: url('images/rrhh_tile.gif');
    background-repeat: repeat-x;
    text-align: right;
    color: #114d7f;
    font-size: 12pt;
  
}

span .error{
	font-size:12px;
	font-style:italic;
	font-weight:normal;
	color: #b94a48;
    display: inline-block;
    *display: inline;
    padding-left: 5px;
    vertical-align: middle;
    *zoom: 1;}

#logotipo   {
    float:left;
    background-image: url('images/rrhh_head.jpg');
    height: 70px;
    width:152px ;
}

    </style>
  </head>

 <body>
<?php

$sql2 =  "select * from opciones.tab_opciones where id_padre=19 and estatus=TRUE";
        $ds     = new DataStore($dbr, $sql2);
    	$row = $ds->getValues(0);
    	echo '<input type="hidden" id="verificar" value="'.$row['id'].'">';		
?>

 <input type="hidden" id="cedula" value=<?php echo "".$ci.""; ?> >
<div id="cabecera" class="hidden-xs" ><div id="logotipo"></div><div id="publicidad"  ><span class="hidden-xs hidden-sm">Sistema de Servicios en L&iacute;nea de la Universidad del Zulia </span></div></div>

<?php

$sql2 =  "select * from autenticacion.tab_negar_accesos where ce_trabajador=$ci and internet is true";
        $ds     = new DataStore($dbr, $sql2);
        $i=$ds->getNumRows();
    	if($i>0){
		
?>
<br>
<div class="container">
<div class="jumbotron" id="cuerpo">
        <h2 class="">Atención:</h2>
        <p align="justify">El  Consejo Técnico de esta Dirección aprobó que quienes no retiraron su nombramiento deberán acudir al siguiente acto público pautado para la segunda quincena de octubre, en consecuencia, Usted no podrá participar en concursos, ascensos y se mantendrán en la nómina de contratados de LUZ. Además, ha sido suspendido su acceso al portal de servicios de RRHH.</p>
        <div class="text-right"><a href="../Seguridad/login.php?accion=bye" >Terminar Sesion</a></div>
      
      </div>
</div>

<?php
exit;
}else{
?>

    <div class="container">

      <!-- Static navbar -->
      <div class="navbar navbar-default">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
           <a class="navbar-brand"  href="#">Men&uacute;</a>          
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
         <!--   <li class="active"><a href="#" id='minomina'>Mi N&oacute;mina</a></li>-->
            <!--<li><a href="#">Carta de Trabajo</a></li>-->
           
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mis Servicios<b class="caret"></b></a>
              <ul class="dropdown-menu">
             <li><a href="#" id="constanciaTrabajo">Constancia de Trabajo </a></li>
              <li><a href="#" id="beneficiosContra1">Beneficios Contractuales</a></li>
              <li><a href="#" id="hcm" >Consulta H.C.M (Solo Obreros)</a></li>
              <li><a href="#" id="feVida" >Consulta Fe de Vida(Jubilados)</a></li>
              <li><a href="#" id="sol_jubilacion" class="hidden-xs" >Solicitud de Jubilaci&oacute;n </a></li>
               <li><a href="#" id="concursos" class="hidden-xs" >Concursos de Credenciales <span class="badge ">Nuevo</span></a></li>
              <li><a href="#" id="evaluaciondesempeno">Evaluacion de desempeño </a></li>
                 <!--  -->
           		<li><a href="#" id="censoUniformes">Censo de Uniformes <span class="badge">Adm.</span></a></li> 
              
              
             <!-- <li><a href="#" id="funeraria" >Prevision Funeraria</a></li>-->
                 <!-- <li><a href="#" id="censoUniformes">Censo de Uniformes</a></li>-->
                <li class="divider"></li>
              <?php  
			  
if($_SESSION['cedula']==18285620 or $_SESSION['cedula']==17834399 or $_SESSION['cedula']==11283131 or $_SESSION['cedula']==7978700){
	
			  echo '<li class="dropdown-header"></li>
  				<li><a href="#" id="ayudanacimiento1" >Ayuda por Nacimiento</a></li>
				<li><a href="#" id="ayudaMatrimonio" >Ayuda por Matrimonio</a></li>
				<li><a href="#" id="ayudamuerte1" >Ayuda por Muerte</a></li>

				';
				
} 
				


?>    

              </ul>
            </li>
            
			<li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Consultas de Nomina <b class="caret"></b></a>
              <ul class="dropdown-menu">
              
              <li><a href="#" id="detallesPago">Mi Nomina</a></li> 
               <li><a href="#" id="CalculoARI">ARI</a></li>  
              <!--  <li><a href="#" id="CalculoARC">ARC <span class="badge">Nuevo</span></a></li>  -->
       
                 <?php 
if($_SESSION['cedula']==7817981 or $_SESSION['cedula']==17834399 or $_SESSION['cedula']==11283131 or $_SESSION['cedula']==18285620)
	{
			echo '<li><a href="#" id="sidial">Sidial<span class="badge">Nuevo</span></a></li> ';	
			/*echo '<li><a href="#" id="CalculoARI">ARI <span class="badge">Nuevo</span></a></li>';*/
	}
	
	      
			?>             
              </ul>
            </li>
          </ul>
          <?php
		/*  $arreglo=array(17834399,11283131);
		 if(in_array($ci, $arreglo)){ */
		  $ci=$_SESSION['cedula'];
		  
		   ?>
          
          <ul class="nav navbar-nav navbar-right">
           <!-- <li ><a href="./">Default</a></li>
            <li><a href="../navbar-static-top/">Static top</a></li>-->
           
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/user_orange.png" width="24" height="24" /><?php echo ucwords(strtolower($_SESSION['usuario'])) ; ?><b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#" id='miregistro'>Datos Personales</a></li>
                <li><a href="#" id="cambiocontrasena">Cambio de Contraseña</a></li>
                <li><a href="../Seguridad/login.php?accion=bye" >Terminar Sesion</a></li>
              </ul>
            </li>
            
          </ul>
        </div><!--/.nav-collapse -->
      </div>

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron" id="cuerpo">
        <h2 class="visible-xs">Bienvenid@</h2>
        <p class="visible-xs">Bienvenido al Sistema de Servicios en Línea de la Dirección de Recursos Humanos de la Universidad del Zulia. A partir de este momento usted podrá hacer uso de diversos servicios que ofrecemos de forma digital para agilizar la gestión de sus solicitudes, garantizando un servicio eficiente incluso desde nuestra plataforma web.</p>
       
        <img src="images/horariowebDOS.jpg" width="100%" class="img-responsive img-rounded hidden-xs" alt="">
      
      </div>

      <div class="modal fade" id="modalTelefono" tabindex="-1" role="dialog">
      <form action="enviarSMS.php" method="POST" id="formEnviarSms">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title">Verificación de Teléfono Móvil</h4>
      </div>
      <div class="modal-body">
        <p class="text-muted">Por favor, ingrese un número de teléfono móvil</p>
        <div class="row">
        <div class="col-md-6"><input type="text" name="telefono" id="telefono" class="form-control" required="required"></div>
        </div>
       <br>
        <p class="text-muted text-justify">A continuación le enviaremos un código de 4 caracteres el cual deberá suministrar posteriormente, esta operación puede tardar algunos segundos. Si presenta algun inconveniente puede dirigirse a la Dirección de Recursos Humanos</p>
       
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Enviar Código</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->
<div class="modal fade" id="modalCodigo" tabindex="-1" role="dialog">
      <form action="enviarSMS.php" method="POST" id="formCodigo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title">Verificación de Teléfono Móvil</h4>
      </div>
      <div class="modal-body">
        <p class="text-muted">Hemos enviado un código al número <span id="show_telf"></span>. <br>Por favor, introduza el código enviado al teléfono móvil ingresado </p>
        <div class="row">
        <div class="col-md-6"><input type="text" name="codigo" id="codigo" class="form-control" required="required"></div>
        </div>
       <br>
       <p class="text-danger" id="error-codigo">* El código ingresado no es correcto, por favor vuelva a intentarlo</p>
      <p class="text-muted">Tienes problemas con el código de verificación? Puedes enviarlo nuevamente <a href="#" id="reenviar">aquí</a></p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Verificar Código</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->

<div class="modal fade" id="modalExito" tabindex="-1" role="dialog">
    
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title">Operacion Exitosa</h4>
      </div>
      <div class="modal-body">
      <br>
      <h4 class="text-muted">Hemos verificado exitosamente el número telefónico suministrado</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Continuar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

</div><!-- /.modal -->

 <div class="modal fade" id="modalCorreo" tabindex="-1" role="dialog">
  <form action="enviarSMS.php" method="POST" id="formEnviarCorreo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title">Validación de Correo Electrónico</h4>
      </div>
      <div class="modal-body">
        <p class="text-muted">Por favor, ingrese una dirección de correo electrónico válida.</p>
        <div class="row">
        <div class="col-md-6"><input type="email" name="email" id="email" value="<?php echo $_SESSION['email']; ?>" class="form-control" required="required"></div>
        </div>
       <br>
        <p class="text-muted text-justify">A continuación le enviaremos un código de 4 caracteres el cual deberá suministrar posteriormente, esta operación puede tardar algunos segundos. Si presenta algun inconveniente puede dirigirse a la Dirección de Recursos Humanos</p>
       <p class="text-success text-justify" id="text-omitir" > Usted ha validado recientemente su direccion de correo electrónico, si aun lo conserva puede hacer hacer clic en el botón con el texto <b>¡Saltar Este Paso!</b></p>
      </div>
      <div class="modal-footer">
       <button type="button" id="btnOmitir" class="btn btn-success">¡Saltar Este Paso!</button>
        <button type="submit" id="btnEnviarCodigo" class="btn btn-primary">Enviar Código</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->

<div class="modal fade" id="modalCodigoCorreo" tabindex="-1" role="dialog">
      <form action="enviarSMS.php" method="POST" id="formCodigoCorreo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title">Validación de Correo Electronico</h4>
      </div>
      <div class="modal-body">
        <p class="text-muted">Hemos enviado un código al correo <span id="show_email"></span>. <br>Por favor, introduza el código enviado al correo electronico ingresado </p>
        <div class="row">
        <div class="col-md-6"><input type="text" name="codigo" id="codigo" class="form-control" required="required"></div>
        </div>
       <br>
       <p class="text-danger" id="error-codigo">* El código ingresado no es correcto, por favor vuelva a intentarlo</p>
      <p class="text-muted">Tienes problemas con el código de verificación? Puedes enviarlo nuevamente <a href="#" id="reenviar">aquí</a></p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Verificar Código</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->

<div class="modal fade" id="modalExitoCorreo" tabindex="-1" role="dialog">
    
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title">Operacion Exitosa</h4>
      </div>
      <div class="modal-body">
      <br>
      <h4 class="text-muted">Hemos validado exitosamente la dirección de correo elecrónico suministrada</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Continuar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

</div><!-- /.modal--> 


  
    </div> <!-- /container -->
    <?php
    } ?>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/js/jquery-1.9.0.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap-switch.min.js"></script>
    <script src="bootstrap/js/tooltip.js"></script>
    <script src="bootstrap/js/transition.js"></script>
    <script src="plugins/jquery.maskedinput.js"></script>
     
 <script>
 
var x;
x=$(document);
x.ready(inicializarEventos);
//************************************************* 
function inicializarEventos(){ 
$("#formCodigoCorreo #error-codigo").hide();
$("#formCodigo #error-codigo").hide();
var cedula=$("#cedula").val();
if($("#verificar").val()==22){
verificaTelefono(cedula);
}
if($("#verificar").val()==21){
$("#formEnviarCorreo #btnOmitir").hide();
$("#formEnviarCorreo #text-omitir").hide();
verificaEmail(cedula);
}
$("#formEnviarSms #telefono").mask("04999999999");
$("formCodigo #codigo").mask("9999");
//*******************verificacion de telefonos
$("#formEnviarSms").submit(function(event) {
event.preventDefault();
var telefono = $("#formEnviarSms #telefono").val();
 $.getJSON('verificaSMS.php', {op:'enviarSMS', cedula: cedula , telefono: telefono}, function(data) {
 
  $("#modalTelefono").modal("hide");
  verificaTelefono(cedula);
  });

});
$("#formCodigo #reenviar").click(function(event) {
event.preventDefault();
$("#modalCodigo").modal("hide");
$("#modalTelefono").modal({backdrop: 'static',
                        keyboard: false, 
                        show: true});
});
$("#formCodigo").submit(function(event) {
event.preventDefault();
var codigo = $("#formCodigo #codigo").val();
 $.getJSON('verificaSMS.php', {op:'verificar', cedula: cedula , codigo: codigo}, function(data) {
  if(data==0){
    $("#formCodigo #error-codigo").show();
  }else{
  $("#formCodigo #error-codigo").hide();
  $("#modalCodigo").modal("hide");
   $("#modalExito").modal({backdrop: 'static',
                        keyboard: false, 
                        show: true});
  verificaTelefono(cedula);

  }
  });

});

//**********************verificacion de emails
$("#formEnviarCorreo").submit(function(event) {
event.preventDefault();
$("#btnEnviarCodigo").attr('disabled', 'disabled');
  var email = $("#formEnviarCorreo #email").val();
  $.getJSON('verificaEmailModal.php', {op:'enviarEmail', cedula: cedula , email: email}, function(data) {
  $("#modalCorreo").modal("hide");
  verificaEmail(cedula);
  $("#btnEnviarCodigo").removeAttr('disabled');
 });

});
$("#formCodigoCorreo #reenviar").click(function(event) {
event.preventDefault();
$("#modalCodigoCorreo").modal("hide");
$("#modalCorreo").modal({backdrop: 'static',
                        keyboard: false, 
                        show: true});
});

$("#formEnviarCorreo #btnOmitir").click(function(event) {
 event.preventDefault();
$.getJSON('verificaEmailModal.php', {op:'omitirEmail', cedula: cedula }, function(data) {
  $("#modalCorreo").modal("hide");
  verificaEmail(cedula);
  });

});

$("#formCodigoCorreo").submit(function(event) {
event.preventDefault();
var codigo = $("#formCodigoCorreo #codigo").val();
 $.getJSON('verificaEmailModal.php', {op:'verificar', cedula: cedula , codigo: codigo}, function(data) {
  if(data==0){
    $("#formCodigoCorreo #error-codigo").show();
  }else{
  $("#formCodigoCorreo #error-codigo").hide();
  $("#modalCodigoCorreo").modal("hide");
   $("#modalExitoCorreo").modal({backdrop: 'static',
                        keyboard: false, 
                        show: true});
  verificaEmail(cedula);

  }
  });

});

$("#detallesPago").click(function(){
	$( "#cuerpo" ).load( "consultaNominaRRHHBoot.php" );
	});
$("#miregistro").click(function(){
	$( "#cuerpo" ).load( "DatosRegistroFormBT.php" );
	});

$("#constanciaTrabajo").click(function(){
	$( "#cuerpo" ).load( "constanciaTrabajoFormBT.php" );
	});
	
$("#censoUniformes").click(function(){
	$( "#cuerpo" ).load( "censoUniformesAdmForm.php" );
	});
	
$("#cambiocontrasena").click(function(){
	$( "#cuerpo" ).load( "DatosCambioContrasenaFormBT.php" );
	});

$("#beneficiosContra1").click(function(){
	$( "#cuerpo" ).load( "BeneficiosContractualesFormBT.php" );
	});
	
$("#ayudanacimiento1").click(function(){
	$( "#cuerpo" ).load( "AyudaNacimientoFormBT.php" );
	});
	
$("#ayudamuerte1").click(function(){
	$( "#cuerpo" ).load("AyudaMuerteFormBT.php" );
	});
$("#ayudaMatrimonio").click(function(){
	$( "#cuerpo" ).load("AyudaMatrimonioFormBT.php" );
	});

$("#ReporteDeducciones").click(function(){
	$( "#cuerpo" ).load( "consultaDeduccionesForm.php" );
	});	
	
$("#sol_jubilacion").click(function(){
	$( "#cuerpo" ).load( "solicitudJubilacionForm.php" );
	});	
	
$("#evaluaciondesempeno").click(function(){
	$( "#cuerpo" ).load( "EvaluacionDesempenoFormBT.php" );
	});	
$("#RendicionBA").click(function(){
	$( "#cuerpo" ).load( "AplicacionCestaFormNew.php" );
	});	
$("#sidial").click(function(){
	$( "#cuerpo" ).load( "consultaTrabajadorFormBT.php" );
	});	
$("#feVida").click(function(){
	$( "#cuerpo" ).load( "consultaFeVidaFormBT.php" );
	});		
	
$("#CalculoARI").click(function(){
	$( "#cuerpo" ).load( "ARIFormBT.php" );
	});	

$("#CalculoARC").click(function(){
        var w = window.open('arcPDF.php?ano=2015','_blank','width=640,height=500,toolbar=no,menubar=no,location=no,directories=no,status=no,scrollbars=yes');
	});	
$("#concursos").click(function(){
	$( "#cuerpo" ).load( "ConcursosForm.php" );
	})	;
$("#hcm").click(function(){
  $( "#cuerpo" ).load( "hcmFormBT.php" );
  });
$("#funeraria").click(function(){
  $( "#cuerpo" ).load( "funerariaForm.php" );
  }) ;  

	
}

function verificaEmail(cedula){

  $.getJSON('verificaEmailModal.php', {op:'verificaOK',cedula: cedula}, function(data) {
  if (data.estatus==0) {
    $("#modalCorreo").modal({backdrop: 'static',
                        keyboard: false, 
                        show: true});
       }
  if (data.estatus=="t" && data.omitir==1) {
  		// revalidar
  	$("#formEnviarCorreo #btnOmitir").show();
    $("#formEnviarCorreo #text-omitir").show();
  	$("#modalCorreo").modal({backdrop: 'static',
                        keyboard: false, 
                        show: true});
       }
  if (data.estatus=="t" && data.omitir==0) {
    return false;
       }
       		
       
  if (data.estatus=="f") {
  $("#show_email").html("<b>"+data.email+"</b>");
  $("#modalCodigoCorreo").modal({backdrop: 'static',
                        keyboard: false, 
                        show: true});
       }
  });

}




function verificaTelefono(cedula){

  $.getJSON('verificaSMS.php', {op:'verificaOK',cedula: cedula}, function(data) {
  if (data.estatus==0) {
    $("#modalTelefono").modal({backdrop: 'static',
                        keyboard: false, 
                        show: true});
       }
  if (data.estatus=="t") {
    return false;
       }
  if (data.estatus=="f") {
  $("#show_telf").html("<b>"+data.telefono+"</b>");
  $("#modalCodigo").modal({backdrop: 'static',
                        keyboard: false, 
                        show: true});
       }
  });

}

	 </script>
  </body>
</html>
