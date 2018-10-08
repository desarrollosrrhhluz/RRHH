<?php  
  
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
 
 session_start();  
 session_destroy();
 session_unset(); 
 session_start(); 

?> 
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.:DRRHH-Sistema de uso Interno:.</title>
<script language="javascript" src="plugins/jquery-1.3.2.min.js"></script>
<script language="javascript" src="plugins/jquery.form.js"></script>
<script language="javascript" src="plugins/jquery.validate.min.js"></script>
<script language="javascript" src="plugins/messages_es.js"></script>
<script language="javascript" src="js/ControlAcceso.js"></script>
<script>
		/*var width=825 - 40;
		var heigth=740 - 40;
		var moveToWidth = (parseInt(screen.availWidth) - parseInt(width))/2;
		var moveToHeigth = (parseInt(screen.availHeight) - parseInt(heigth))/2;

		self.resizeTo(width,heigth);
		self.moveTo(moveToWidth, moveToHeigth);	*/
</script>

<link href="css/demo.css" rel="stylesheet" type="text/css" />

<link href="css/site.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {
	color: #000000;
	font-weight: bold;
}
-->
</style>
</head>

<body class="bodyluz">
<table width="100%" height="210" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="16%" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF" id="escudo_luz"><a href="http://www.luz.edu.ve" title="Home de LUZ Web" name="luz"><img src="http://www.rrhh.luz.edu.ve/templates/luz_portada1b/images/cabecera.jpg" alt="Universidad del Zulia" height="100" width="190"></a></td>

    <td bgcolor="#FFFFFF" id="nombre_sitio" ><div id="nombre_sitio_div"><a href="/index.php" title="Volver al inicio">
					Dirección de Recursos Humanos</a>

	</div></td>
    <td width="15%" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" ><p>&nbsp;</p>
      <p>&nbsp;</p>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"  id="tabla_central">
        <tr >
          <td width="49%" style="text-align:center" height="366"><div class="mensaje" style="width:400px" id="div">
            <p align="center" class="Estilo1">Por favor, vuelve a introducir tu usuario y contrase&ntilde;a </p>
            <p align="center">&nbsp;</p>
            <p align="center">Por favor, verifica tus datos de acceso y aseg&uacute;rate de que  el bloqueo de may&uacute;sculas no est&aacute; activado e int&eacute;ntalo de nuevo</p>
          </div></td>
          <td width="51%" ><div id="div-regForm">
            <div class="form-title">Acceso al Sistema </div>
            <div class="form-sub-title">Ingrese sus datos </div>
            <form id="regForm" name="regForm" action="ControlAcceso.php" method="post">
              <table>
                <tbody>
                  <tr>
                    <td><label for="fname">Usuario:</label></td>
                    <td><div class="input-container">
                      <input name="fname" type="text" id="fname" maxlength="15" />
                    </div></td>
                  </tr>
                  <tr>
                    <td height="54"><label for="lname">Contrase&ntilde;a:</label></td>
                    <td><div class="input-container">
                      <input name="lname" type="password" id="lname" maxlength="15" />
                    </div></td>
                  </tr>
				  <tr>
                    <td height="54"><label for="lname">Sistema:</label></td>
                    <td><div class="input-container">
                      <select name="tipo_acceso">
					  <option value="1">RRHH</option>
					  <option value="2">Siaca_web</option>
					  </select>
                    </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>
					<input type="hidden" name="op" id="op_login" value="iniciar_session"/>
					<input name="submit" type="submit" class="greenButton" value="Iniciar" />                      </td>
                  </tr>
				  <tr>
                    <td colspan="2"><label>&iquest;Olvidaste tu contrase&ntilde;a?<br />
                     &iquest;Deseas cambiar tu contrase&ntilde;a?</label></td>
                    </tr>
                </tbody>
              </table>
			   <div id="error" class="error"></div>
            </form>
			
           
          </div></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="55" colspan="4"><div>
      <div align="center" id="div-footer">&copy; Universidad del Zulia 2010, Derechos Reservados. Maracaibo, Venezuela.</div>
    </div></td>
  </tr>
</table>
</body>
</html>
