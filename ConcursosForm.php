<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHCC';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];




  $ci = $_SESSION['cedula']; 
	$db1     = "rrhh";
 $sql1 =  "select a.ce_trabajador, a.fecha_efectividad, a.cod_autorizacion, 'promocion' as tipo  from mst_promociones as a
 where a.fecha_efectividad is not  null 
 and EXTRACT(YEAR FROM age(now(),date(a.fecha_efectividad) ) )<1 and a.ce_trabajador::integer=$ci
 UNION
 select b.ce_trabajador, b.fecha_efectividad, b.cod_autorizacion, 'adecuacion' as tipo from mst_adecuaciones as b
 where b.fecha_efectividad is not  null 
 and EXTRACT(YEAR FROM age(now(),date(b.fecha_efectividad) ) )<1 and b.ce_trabajador::integer=$ci
 UNION
 select b.ce_trabajador, b.fecha_efectividad, '' as fecha_efectividad , 'concurso' as tipo from mst_crecas_crecos as b
 where status!=13 and status!=6  and b.ce_trabajador::integer=$ci
 UNION
 select b.ce_trabajador, b.fecha_efectividad, '' as fecha_efectividad, 'promocion' as tipo from mst_promociones as b
 where status!=13 and status!=6 and b.ce_trabajador::integer=$ci
 UNION
 select b.ce_trabajador, b.fecha_efectividad, '' as fecha_efectividad, 'adecuacion' as tipo from mst_adecuaciones as b
 where status!=13 and status!=6 and b.ce_trabajador::integer=$ci

 ";

 $ds1     = new DataStore($db1, $sql1);
 if($ds1->getNumRows()<=0){

  $db     = "sidial";
  $sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where CE_TRABAJADOR=$ci and ESTATUS='A' and (TIPOPERSONAL  LIKE '2%' or  TIPOPERSONAL  LIKE '3%') and
  ( SUBSTRING ( TIPOPERSONAL, 4 , 1 ) !='8' 
  and  SUBSTRING ( TIPOPERSONAL, 4 , 1 ) !='9'  )";
  $ds     = new DataStore($db, $sql2);
  if($ds->getNumRows()<=0){
				  /* header ('Location: ErrorConcursos.html');
         exit;*/
       }else{
        $row = $ds->getValues(0);
        $_SESSION['nombre_usuario']=$row['NOMBRES'];
        $_SESSION['tipopersonal']=$row['TIPOPERSONAL'];
        $_SESSION['co_ubicacion']=$row['CO_UBICACION'];	
        $_SESSION['de_ubicacion']=$row['DE_UBICACION'];	
        $_SESSION['gdo_salarial']=$row['GDO_SALARIAL'];	 

        $fechaIngresoAux=date('Y-m-d', strtotime($row['FE_INGRESO']));
        $fechaActual=date('Y-m-d');
        $fecha1 = new DateTime($fechaIngresoAux);
        $fecha2 = new DateTime($fechaActual);
        $fecha3 = $fecha1->diff($fecha2);
        if($fecha3->y<3){

          echo '<fieldset>
          <legend id="principal">Este servicio  NO est&aacute; disponible para usted</legend>
          <p class="small text-danger">Su solicitud NO puede ser procesada, dado que no ha transcurrido los 3 
            años reglamentario entre su fecha de ingreso y la 
            presente fecha de postulación </p>
            <p class="small text-danger"><strong>Gracias por visitarnos. </strong>
            </p>
          </fieldset>';


          exit;
        }
					  
					  }
		
		}else{
			 //**************************************
			 $row = $ds1->getValues(0);

       if($row['fecha_efectividad']=='' ){

       echo "<fieldset>
        <legend id='principal'>Este servicio  NO est&aacute; disponible para usted</legend>
        <p class='small text-danger'>Su solicitud NO puede ser procesada, dado que se encuentra en un proceso de ".$row['tipo']." activo </p>
      </fieldset>";
        exit;

       }else{
			
     echo "<fieldset>
        <legend id='principal'>Este servicio  NO est&aacute; disponible para usted</legend>
        <p class='small text-danger'>Su solicitud NO puede ser procesada, dado que no ha transcurrido el 
          año reglamentario entre la efectividad de su ultimo nombramiento y la 
          presente fecha de postulación.<br>
          Su ultimo nombramiento presenta efectividad desde  ".$row['fecha_efectividad']."  seg&uacute;n oficio ".$row['cod_autorizacion']."</p>
          <p class='small text-danger'><strong>Gracias por visitarnos. </strong>
          </p>
      </fieldset>";
           
            exit; 
            
          }
			 
			 ///////////////////////////////////////
			exit;
			}
/*			
	header ('Location: ProcesoTerminado.html');
					  exit;		*/
			
?>


<script language="javascript" src="js/concursos.js"></script>
<style type="text/css">
.Estilo2 {font-size: 14}
.Estilo3 {	font-size: 18px;
	font-weight: bold;
	color: #666;
}


</style>
</head>

<fieldset><legend> <h3> <img src="images/Gnome-Application-Certificate-32.png" /> Concursos de Credenciales</h3> </legend>
<br />
<div id="cuerpo_concursos" align="center" style="font-size:16px"></div>
<div id="texto_cita" style="font-size:16px"></div>
<div id="muestra_circular" align="center" style="font-size:12px">

<div id="cuerpo_circular" align="center"></div>
</div>
<div id="destino"></div>
</fieldset>
<div id="caja_correo" style=" display:none;">
<form action="concursos.php" method="post" name="act_correo"  id="act_correo">
<label>Es su correo electronico</label><input style="width:250px" name="email" id="email" type="text" value="<?php echo $_SESSION['email'];  ?>" /><br /><br />
<div align="center"><input type="hidden" name="op"  value="act_correo" /><input type="submit" name="Actualizar"  value="Actualizar" /></div>
</form>
</div>

<div class="modal fade" id="dialogo_confirmacion">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">¿Esta seguro de continuar?</h4>
      </div>
      <div class="modal-body">
        <p style="font-size:14px">-Terminada esta operacion estara oficialmente postulado al concurso seleccionado-</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn_cancelar_confirm" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btn_confirmado" class="btn btn-primary">Si, estoy seguro</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="dialogoMensaje">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Mensaje</h4>
      </div>
      <div class="modal-body" id="div_mensaje" style="font-size:14px">
         </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
     
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->