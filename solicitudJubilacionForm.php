<?php 

header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHSJ';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];
include_once('includes/Funciones.inc.php');
include_once('includes/DiaHabil.inc.php');

$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";



     $ci = $_SESSION['cedula'];
	
	$db     = "sidial";
$sql2 =  'select *,
(select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION,
datediff(year ,FE_NACIMIENTO,getdate()) AS EDAD,
datediff(day,FE_INGRESO,getdate()) / 365 AS ANOS
 from V_DATPER where  ESTATUS in ("A") and CE_TRABAJADOR='.$ci.'  and
( SUBSTRING ( TIPOPERSONAL, 4 , 1 ) !="8" 
and  SUBSTRING ( TIPOPERSONAL, 4 , 1 ) !="9"  )  and ( TIPOPERSONAL  LIKE "2%" or  TIPOPERSONAL  LIKE "3%" ) and convert(numeric, datediff(day ,FE_INGRESO, current_date() ))/365  >=14';

//
        $ds     = new DataStore($db, $sql2);
    	if($ds->getNumRows()<=0){
		echo '<legend><div class="row">
<div class="col-md-9"><h2><img src="images/user_edit.png" width="32" height="32" />Solicitud de Jubilaci&oacute;n</h2></div>
</div>

</legend>
<div  class="col-md-12"><span class="glyphicon glyphicon-warning-sign" data-toggle="tooltip"></span> Usted No cumple con los requisitos m&iacute;nimos para realizar la solicitud de Jubilacion en linea</div>
';
		
		exit; 
		}else{
		$row = $ds->getValues(0);
	 $_SESSION['sexo']=$row['SEXO'];
		$_SESSION['tipopersonal']=$row['TIPOPERSONAL'];
		$_SESSION['coubicacion']=$row['CO_UBICACION'];
		$_SESSION['anos']=$row['ANOS'];
		$_SESSION['edad']=$row['EDAD'];
		
$fechaIngresoAux=date('Y-m-d', strtotime($row['FE_INGRESO']));
$fechaActual=date('Y-m-d');
$fecha1 = new DateTime($fechaIngresoAux);
$fecha2 = new DateTime($fechaActual);
		$fecha3 = $fecha1->diff($fecha2);
		$row['DIAS']=$fecha3->d;
		$row['MESES']=$fecha3->m;


		
		if($row['MESES']>0){
			$_SESSION['meses']=$row['MESES'];
			}else{
			$_SESSION['meses']=0;	
			}
		
		if($row['DIAS']>0){
				$_SESSION['dias']=$row['DIAS'];
			}else{
				$_SESSION['dias']=0;	
				}	
			
		$_SESSION['ano_proceso']=2015;
	$cantidad=60;
	$fecha0=date("d/m/Y");	
	$fe_efectvidad= diaHabil($fecha0, $cantidad);
		

		}
		?>
     <script language="javascript"  src="./js/solicitudJubilacion.js"></script>
<legend><div class="row">
<div class="col-md-9"><h2><img src="images/user_edit.png" width="32" height="32" />Solicitud de Jubilaci&oacute;n</h2></div>
<div align="right" class="col-md-3"><span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" id="img_ayuda" title="Ayuda"></span></div>
</div>
</legend>
<!--glyphicon glyphicon-question-sign-->

<div id="informacion"></div>
<div id="formulario" >
<form name="form_jubilacion" id="form_jubilacion" method="post">
  <p>&nbsp;</p>
  <table border="0" id="tabla1" class="table">
    <tr>
      <td colspan="3"><small style="width:auto">&iquest;Se encuentra actualmente postulado para un concurso de credenciales?</small></td>
      <td width="92"><input required="required" name="concurso" id="concurso1" type="radio" value="s" />
        Si</td>
      <td width="62"><input name="concurso" required="required" id="concurso2" type="radio" value="n"  />
No</td>
      <td width="34">&nbsp;</td>
      </tr>
   <!-- <tr>
      <td colspan="3"><small>&iquest;Posee en la actualidad pr&eacute;stamo hipotecario a trav&eacute;s del Fondo de Ahorro Obligatorio para la Vivienda (FAOV)?</small></td>
      <td><input name="faov" id="faov1" type="radio" value="s" required="required"  />
        Si</td>
      <td><input name="faov" id="faov2" type="radio" value="n" required="required"/>
No</td>
      <td>&nbsp;</td>
      </tr>-->
      <tr>
      <td width="403"><strong>&iquest;Prest&oacute; servicios en otra  administraci&oacute;n p&uacute;blica / Programa Beca Empleo de DIDSE?</strong></td>
      <td width="103"><select name="servicios" id="servicios" class="form-control" required >
        <option value="">- -</option>
        <option value="s">Si</option>
        <option value="n">No</option>
      </select>
</td>
      <td width="102">
</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    
  </table>
<br>
  <table width="634" height="85" border="0"  class="table">
    <tr>
      <td><strong>C&oacute;mputo de Antig&uuml;edad</strong></td>
      <td><strong>A&ntilde;os</strong></td>
      <td><strong>Meses</strong></td>
      <td><strong>Dias</strong></td>
    </tr>
    <tr>
      <td align="left"><small>Universidad del Zulia</small></td>
      <td><input name="anoluz" type="text" id="anoluz" size="10" maxlength="10" value="<?php echo $_SESSION['anos']; ?>" readonly class="form-control" /></td>
      <td><input name="mesluz" type="text" id="mesluz" size="10" maxlength="10" value="<?php echo $_SESSION['meses']; ?>" readonly class="form-control" /></td>
      <td><input name="dialuz" type="text" id="dialuz" size="10" maxlength="10" value="<?php echo $_SESSION['dias']; ?>" readonly class="form-control"/></td>
    </tr>
    <tr>
      <td align="left"><small>Administraci&oacute;n P&uacute;blica / DIDSE</small></td>
      <td><input name="anos_ap" type="text" id="anos_ap" size="10" maxlength="10" readonly class="form-control"/></td>
      <td><input name="meses_ap" type="text" id="meses_ap" size="10" maxlength="10" readonly class="form-control"/></td>
      <td><input name="dias_ap" type="text" id="dias_ap" size="10" maxlength="10" readonly class="form-control"/></td>
    </tr>
    <tr>
      <td align="left"><strong>Total</strong></td>
      <td><input name="anos_total" type="text" id="anos_total" size="10" maxlength="10" readonly class="form-control"/></td>
      <td><input name="meses_total" type="text" id="meses_total" size="10" maxlength="10" readonly class="form-control"/></td>
      <td><input name="dias_total" type="text" id="dias_total" size="10" maxlength="10" readonly class="form-control"/></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="823" border="0" >
  <tr>
      <td width="158" align="right"><label >Fecha de Efectividad:</label></td>
      <td width="397"><input type="text" name="fe_efectividad" id="fe_efectividad" placeholder="00/00/0000" readonly="readonly" class="form-control" required="required" value="<?php echo $fe_efectvidad; ?>"   /></td>
      </tr>
    <tr>
    <tr>
      <td width="158" align="right"><label >Correo Electr&oacute;nico:</label></td>
      <td width="397"><input type="email" name="correo" id="correo" class="form-control" placeholder="correo@ejemplo.com"  required="required" value="<?php echo $_SESSION['email']; ?>" /></td>
      </tr>
    <tr>
      <td align="right"><label >Tel&eacute;fono Celular:</label></td>
      <td><input type="text" name="tel_celular" id="tel_celular"   pattern="[0-9]+" maxlength="11" class="form-control" placeholder="Teléfono Celular" required="required" title="Solo numeros, Ejemplo: 04000000000"></td>
      </tr>
    <tr>
      <td align="right"><label >Tel&eacute;fono Habitacion:</label></td>
      <td><input type="text" name="tel_habitacion" id="tel_habitacion"   pattern="[0-9]+" maxlength="11" class="form-control" placeholder="Teléfono Habitacion" required="required" title="Solo numeros, Ejemplo: 04000000000"></td>
      </tr>
    <tr>
      <td align="right"><label >Direcci&oacute;n de Habitaci&oacute;n:</label></td>
      <td><textarea name="direccion" cols="50" rows="3" id="direccion" required="required" placeholder="Escriba aqu&iacute; su direcciòn" class="form-control"></textarea>
      </td>
      </tr>
  </table>
 <div align="center">
 
 <input type="hidden" name="tipopersonal" id="tipopersonal" value="<?php echo substr($_SESSION['tipopersonal'],0,1); ?>" />
<input type="hidden" name="sexo" id="sexo" value="<?php echo $_SESSION['sexo']; ?>" />
<input type="hidden" name="edad" id="edad" value="<?php echo $_SESSION['edad']; ?>" />
<input type="hidden" name="anos_luz" id="anos_luz" value="<?php echo $_SESSION['anos']; ?>" />
<input type="hidden" name="mes_luz" id="mes_luz" value="<?php echo $_SESSION['meses']; ?>" />
<input type="hidden" name="dia_luz" id="dia_luz" value="<?php echo $_SESSION['dias']; ?>" />
<input type="hidden" name="fe_ingreso" id="fe_ingreso" value="<?php echo $row['FE_INGRESO']; ?>" />
<input type="hidden" name="id_sol_oculto" id="id_sol_oculto" />
<input type="hidden" name="op" id="op_form" value="guardar" />

 <input type="submit" value="Enviar" id="btn_guardar" class="btn btn-success" disabled="disabled" />  
 <input type="reset" value="Cancelar" id="btn_cancelar" class="btn btn-default" /></div>
</form>
</div>
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
        <strong>CONDICIONES PARA OBTENER EL BENEFICIO DE LA JUBILACIÓN:</strong><br />
<ul>
<li>Los que hayan cumplido 25 años de servicio.</li>
<li>Los mayores de 55 años de edad al cumplir 20 años de servicio.</li>
<li>Los mayores de 60 años de edad al cumplir 15 años de servicio.</li>
</ul>

Para efectos de la jubilación, la Universidad conviene en computar los años de servicio prestados 

por el trabajador a tiempo completo en la Administración Pública, previa demostración del 

interesado ante la Dirección de Recursos Humanos, siempre y cuando el trabajador tenga un lapso 

mínimo de quince (15) años prestando servicios a la Universidad.
<br />
<strong>BASE LEGAL:</strong> 
<br />
<ul>
<li>Cláusula 102 del VI Convenio de Trabajo LUZ/ASDELUZ.</li>

<li>Cláusula 94 del VI Contrato LUZ/SOLUZ.</li>

<li>Artículo 7, 9 y 10 del Reglamento de Jubilaciones y Pensiones del Personal Administrativo 

de la Universidad del Zulia.</li></ul>
<strong>NOTA:</strong> <br /><p style="color:#FF0000">La fecha de efectividad de la jubilación, deberá ser mayor o igual a dos (2) meses, contados a partir de la fecha de la solicitud realizada a través del Formato Único de la Solicitud de Jubilación.</p> 
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


 <div id="error" class="error"></div>
  <div id="destino"></div>

