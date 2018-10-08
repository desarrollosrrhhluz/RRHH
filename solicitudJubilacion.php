<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
error_reporting(0);
session_start();    
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS    . '/DataStore.class.php');
include('includes/Funciones.inc.php');

$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$cedula=$_SESSION['cedula'];
$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
	        case 'DatosTrabajador':
	         DatosTrabajador();
			 break;	
			 case 'actualiza_datos':
	         actualiza_datos();
			 break;	
			 case 'guardar':
	         guardar();
			 break;
			 case 'update':
	         update();
			 break;
			 case 'DatoSolicitud':
	         DatoSolicitud();
			 break;
			 case 'buscaServicios':
	         buscaServicios();
			 break;
			 case 'consultaSol':
	         consultaSol();
			 break;
			 case 'HistoriaSolicitud':
	         HistoriaSolicitud();

			 
			 		 
			 }

//****************************************
function DatosTrabajador(){
global $cedula, $dbr, $db, $dbs;

$fecharegistro=date("d/m/Y");
$sqlExiste="select * from mst_dato_personal_sso where ce_trabajador=$cedula";
$dsr  = new DataStore($dbr,$sqlExiste);
        if($dsr->getNumRows()<=0){
				$sqlDatos="select * from V_DATPER where CE_TRABAJADOR=$cedula and ESTATUS='A'";
				$dsx  = new DataStore($dbs,$sqlDatos);
				if($dsx->getNumRows()<=0){
				 }else{
				$row = $dsx->getValues(0);
				$fe_nacimiento=formato_fecha($row['FE_NACIMIENTO']);
				$fe_ingreso=formato_fecha($row['FE_INGRESO']);
				if(empty($row['FE_JUBILACION'])){ $fe_jubilacion=''; }else{$fe_jubilacion=formato_fecha($row['FE_JUBILACION']);}	
				$tipopersonal=$row['TIPOPERSONAL'];
				$sexo=$row['SEXO'];
				$cargo=utf8_encode($row['DESCRIPCION']);
				$direccion=utf8_encode($row['DIRECCION']);
				
				$inserReg="INSERT INTO   public.mst_dato_personal_sso
(  ce_trabajador,  nombre1,  nombre2,  apellido1,  apellido2,  sexo,nacionalidad,  fe_nacimiento,fe_ingreso,fe_jubilacion,  tipopersonal,  cargo,  direccion,  telefono_local,  telefono_celular,  zurdo,  sueldo_semanal,  fecha_actualizacion,  fecha_jornada, actualizado, email) 
VALUES (  $cedula,  ". ( empty ( $row['NOMBRE1'] ) ? "null" : "'" . utf8_encode($row['NOMBRE1'] ). "'" ) . ",  ". ( empty ( $row['NOMBRE2'] ) ? "null" : "'" . utf8_encode($row['NOMBRE2']) . "'" ) . ",  ". ( empty ( $row['APELLIDO1'] ) ? "null" : "'" . utf8_encode(str_replace("'"," ",$row['APELLIDO1'])) . "'" ) . ",  ". ( empty ( $row['APELLIDO2'] ) ? "null" : "'" .utf8_encode( $row['APELLIDO2']) . "'" ) . ",'$sexo',NULL,'$fe_nacimiento','$fe_ingreso',". ( empty ( $fe_jubilacion ) ? "null" : "'" .$fe_jubilacion . "'" ) . ", $tipopersonal,'$cargo','$direccion',  NULL,  NULL,  NULL,  284, '$fecharegistro',  '$fecharegistro', 0, NULL)";
$ds	       = new DataStore($dbr);
$rs        = $ds->executesql($inserReg);

		$sql = "SELECT * FROM public.mst_dato_personal_sso where ce_trabajador= $cedula";
				$ds2    = new DataStore($dbr, $sql);
				if($ds2->getNumRows()<=0){
				$res=0; 
				echo json_encode($res) ;	
				  }else{
				   $rows=$ds2->getValues(0);
						   $res[]=$rows;
							echo json_encode($res) ;	
				   }



				//echo '{ "message": "vergatario" }'; 
				}		 
		  }else{
		   $sql = "SELECT * FROM public.mst_dato_personal_sso where ce_trabajador= $cedula";
				$ds2    = new DataStore($dbr, $sql);
				if($ds2->getNumRows()<=0){
				$res=0; 
				echo json_encode($res) ;	
				  }else{
				   $rows=$ds2->getValues(0);
						   $res[]=$rows;
							echo json_encode($res) ;	
				   }     
		  }
}
//********************************************
function DatoSolicitud(){
global $cedula, $dbr, $db, $dbs;	
	 $sql = "SELECT * FROM tra_solicitud_jubilacion where ce_trabajador= $cedula";
				$ds2    = new DataStore($dbr, $sql);
				if($ds2->getNumRows()<=0){
				$res=0; 
				   $_SESSION['id_solicitud']=0;
				echo json_encode($res) ;	
				  }else{
				   $rows=$ds2->getValues(0);
				   $_SESSION['id_solicitud']=$rows['id_solicitud'];
				   $res[]=$rows;
					echo json_encode($res);	
				   }     
	}
///////////////////////////////////////////////////////////////////	
function consultaSol(){
global $cedula, $dbr, $db, $dbs;	
	 $sql = "SELECT * FROM tra_solicitud_jubilacion where ce_trabajador= $cedula";
				$ds2    = new DataStore($dbr, $sql);
				if($ds2->getNumRows()<=0){
				$res=0; 
				 $_SESSION['id_solicitud']=0;
				echo json_encode($res) ;	
				  }else{
				   $rows=$ds2->getValues(0);
				   $_SESSION['id_solicitud']=$rows['id_solicitud'];
						   $res=1;
							echo json_encode($res) ;	
				   }     
	}
	
////////////////////////////////////////////////////////////////////
function buscaServicios(){
	global $cedula, $dbr, $db, $dbs;	
	
	if($_SESSION['id_solicitud']==0){
		
			echo '<tr class="fila_encabezado">
      <td ><small><strong>Desempe&ntilde;o en la administracion Publica</strong></small></td>
	  <td ><small><div align="center">Tipo</div></small></td>
      <td ><small><div align="center">Fecha Ingreso</div></small></td>
      <td ><small align="center">Fecha Egreso</small></td>
      <td ><small align="center">&iquest;Deveng&oacute; Prestaciones Sociales?</small></td>
      <td ><small align="center">Monto en BsF</small></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="fila_repetir">
      <td> <input type="hidden" name="valores[]"  value="1" /><input name="organismo[]" type="text" id="organismo" class="form-control organismo" placeholder="Nombre del Organismo" /></td>
       <td><select name="tipo[]" id="tipo"  class="form-control tipo">
	    <option value="">--</option>
        <option value="P">Admon Pública</option>
        <option value="D">DIDSE</option>
      </select></td>
	  <td><input name="fe_desde[]" type="text" id="fe_desde" class="form-control fecha" placeholder="00/00/0000"  /></td>
      <td><input name="fe_hasta[]" type="text" id="fe_hasta"  class="form-control fecha" placeholder="00/00/0000"  /></td>
      <td><select name="cobro[]" id="cobro" class="form-control">
	    <option value="">--</option>
        <option value="s">Si</option>
        <option value="n">No</option>
      </select></td>
      <td><input name="monto[]" type="text" id="monto" size="8" class="form-control" placeholder=""  /></td>
      <td><img src="images/Gnome-List-Add-32.png" width="32" height="32" class="agregar_nuevo" title="Agregar Nuevo"></td>
    </tr>';
		
		}else{
			
			$id=$_SESSION['id_solicitud'];
		 $sql = "SELECT * FROM mst_detalle_jubilacion where id_solicitud=$id";
				$ds2    = new DataStore($dbr, $sql);
				 $ds2->getNumRows();
				if($ds2->getNumRows()<=0){
					echo '<tr class="fila_encabezado">
      <td ><small><strong>Desempe&ntilde;o en la administracion Publica</strong></small></td>
	  <td ><small><div align="center">Tipo</div></small></td>
      <td ><small><div align="center">Fecha Ingreso</div></small></td>
      <td ><small align="center">Fecha Egreso</small></td>
      <td ><small align="center">&iquest;Deveng&oacute; Prestaciones Sociales?</small></td>
      <td ><small align="center">Monto en BsF</small></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="fila_repetir">
      <td> <input type="hidden" name="valores[]"  value="1" /><input name="organismo[]" type="text" id="organismo" class="form-control organismo"   /></td>
       <td><select name="tipo[]" id="tipo" class="form-control tipo">
	    <option value="">--</option>
        <option value="P">Admon Pública</option>
        <option value="D">DIDSE</option>
      </select></td>
	  <td><input name="fe_desde[]" type="text" id="fe_desde"  class="form-control fecha" placeholder="00/00/0000"  /></td>
      <td><input name="fe_hasta[]" type="text" id="fe_hasta"  class="form-control fecha" placeholder="00/00/0000"  /></td>
      <td><select name="cobro[]" class="form-control" id="cobro">
	    <option value="">--</option>
        <option value="s">Si</option>
        <option value="n">No</option>
      </select></td>
      <td><input name="monto[]" type="text" id="monto" size="8" class="form-control" placeholder=""  /></td>
      <td><img src="images/Gnome-List-Add-32.png" width="32" height="32" class="agregar_nuevo" title="Agregar Nuevo"></td>
    </tr>';
				  }else{
					  $i=0;
					 $j=$ds2->getNumRows();
					  echo '<tr class="fila_encabezado">
      <td ><small><strong>Desempe&ntilde;o en la administracion Publica</strong></small></td>
	  <td ><small><div align="center">Tipo</div></small></td>
      <td ><small><div align="center">Fecha Ingreso</div></small></td>
      <td ><small align="center">Fecha Egreso</small></td>
      <td ><small align="center">&iquest;Deveng&oacute; Prestaciones Sociales?</small></td>
      <td ><small align="center">Monto en BsF</small></td>
      <td>&nbsp;</td>
    </tr>';
					  
					  while($i<$j){
				      $rows=$ds2->getValues($i);
				   if($i<1){
					   echo ' <tr class="fila_repetir">
      <td> <input type="hidden" name="valores[]"  value="1" /><input name="organismo[]" type="text" id="organismo" class="form-control organismo" size="60" value="'.$rows['institucion'].'"  /></td>
        <td><select name="tipo[]" id="tipo" class="form-control tipo">
	    <option value="">--</option>
        <option value="P" '.($rows['tipo']=="P"? 'selected="selected"' :' ').'>Admon Pública</option>
        <option value="D" '.($rows['tipo']=="D"? 'selected="selected"' :' ').'>DIDSE</option>
      </select></td>
	  <td><input name="fe_desde[]" type="text" id="fe_desde" size="15" class="form-control fecha" placeholder="00/00/0000" value='.$rows['fe_desde'].'  /></td>
      <td><input name="fe_hasta[]" type="text" id="fe_hasta" size="15" class="form-control fecha" placeholder="00/00/0000"  value='.$rows['fe_hasta'].' /></td>
      <td><select name="cobro[]" id="cobro" class="form-control">
	     <option value="" >--</option>
        <option value="s" '.($rows['cobro']=="s"? 'selected="selected"' :' ').'>Si</option>
        <option value="n" '.($rows['cobro']=="n"? 'selected="selected"' :' ').'>No</option>
      </select></td>
      <td><input name="monto[]" type="text" id="monto" size="8" class="form-control" placeholder="" value="'.$rows['monto'].'"  /></td>
      <td><img src="images/Gnome-List-Add-32.png" width="32" height="32" class="agregar_nuevo" title="Agregar Nuevo"></td>
    </tr>';
					   }else{
				echo ' <tr class="fila_repetir"><td> <input type="hidden" name="valores[]"  value="1" /><input name="organismo[]" type="text" id="organismo" class="form-control organismo" size="60" value="'.$rows['institucion'].'"  /></td>
       <td><select name="tipo[]" id="tipo" class="form-control tipo">
	    <option value="">--</option>
        <option value="P" '.($rows['tipo']=="P"? 'selected="selected"' :' ').'>Admon Pública</option>
        <option value="D" '.($rows['tipo']=="D"? 'selected="selected"' :' ').'>DIDSE</option>
      </select></td>
	  <td><input name="fe_desde[]" type="text" id="fe_desde"  class="form-control fecha" placeholder="00/00/0000" value="'.$rows['fe_desde'].'"  /></td>
      <td><input name="fe_hasta[]" type="text" id="fe_hasta" class="form-control fecha" placeholder="00/00/0000"  value="'.$rows['fe_hasta'].'" /></td>
      <td><select name="cobro[]" id="cobro" class="form-control">
	   <option value="" >--</option>
        <option value="s" '.($rows['cobro']=="s"? 'selected="selected"' :' ').'>Si</option>
        <option value="n" '.($rows['cobro']=="n"? 'selected="selected"' :' ').'>No</option>
      </select></td>
      <td><input name="monto[]" type="text" id="monto" size="8" class="form-control" placeholder="" value="'.$rows['monto'].'"  /></td><td> <img src="images/Gnome-Window-Close-32.png" width="32" height="32" class="eliminar_este" title="Eliminar esta Fila"></td></tr>';		   
						   
						   }
				   
				      $i++;
					  }
				  	
				   }     
			
			
			}
	
	}


////////////////////////////////////////////////////
function guardar(){
global $cedula, $dbr, $db, $dbs;

extract($_POST);


		  $sql ="INSERT INTO  tra_solicitud_jubilacion ( ce_trabajador, fe_solicitud,concurso, admon_publica,fecha_efectiva, estatus) 
			VALUES ( $cedula, now(),'$concurso','$servicios','$fe_efectividad', 'SOLICITADO')";

		      /* $sql ="INSERT INTO  tra_solicitud_jubilacion ( ce_trabajador, fe_solicitud,concurso, admon_publica,fecha_efectiva, estatus) 
				VALUES ( $cedula, now(),'$concurso','$servicios',now(), 'SOLICITADO')";*/

				$ds  = new DataStore($dbr);
				$rs        = $ds->executesql($sql);	
				$last="SELECT currval('tra_solicitud_jubilacion_id_solicitud_seq') as id2";
				$ds7     = new DataStore($dbr,$last);
				$fila=$ds7->getValues(0);
			 	$id=$fila['id2'];
				$_SESSION['solicitud']=$id;
				if($servicios=='s'){
				 $i=count($organismo);
				$j=0;
				while($j<$i){

					$institucion=$organismo[$j];
					$fedesde=$fe_desde[$j];
					$fehasta=$fe_hasta[$j];
					$montobs=$monto[$j];
					$cobrobs=$cobro[$j];
					$tipo=$tipo[$j];
					
					 $ins="INSERT INTO mst_detalle_jubilacion ( id_solicitud, institucion, fe_desde, fe_hasta,cobro,monto, tipo) 
					 VALUES (  $id, '$institucion', '$fedesde', '$fehasta','$cobrobs',$montobs, '$tipo' )";
					 $rs        = $ds->executesql($ins);
					
					$j++;
					}
				}


$updateDatos="UPDATE 
  public.mst_dato_personal_sso  
SET 
  direccion = '$direccion',
  telefono_local = '$tel_habitacion',
  telefono_celular = '$tel_celular',
  actualizado = 1,
  email='$correo'
WHERE 
  ce_trabajador = $cedula";
  
$ds	       = new DataStore($dbr);
$rs        = $ds->executesql($updateDatos); 

 $res=1;
							echo json_encode($res) ;	

	
	}
	
///////////////////////////////////////////////////////////////////
function update(){
	global $cedula, $dbr, $db, $dbs;

extract($_POST);


				 $sql ="UPDATE tra_solicitud_jubilacion  SET 
  concurso = '$concurso',
  faov = '$faov',
  admon_publica = '$servicios'
WHERE 
  id_solicitud = $id_sol_oculto";
				$ds  = new DataStore($dbr);
				$rs1        = $ds->executesql($sql);
				
		 	$last="DELETE FROM mst_detalle_jubilacion WHERE  id_solicitud = $id_sol_oculto";
			 $rs2       = $ds->executesql($last);
				
				
				$i=count($organismo);
				
				$j=0;
				if($servicios=='s'){
	
				while($j<$i){
					$institucion=$organismo[$j];
					$fedesde=$fe_desde[$j];
					$fehasta=$fe_hasta[$j];
					$montobs=$monto[$j];
					$cobrobs=$cobro[$j];
					
				$ins="INSERT INTO mst_detalle_jubilacion ( id_solicitud, institucion, fe_desde, fe_hasta, cobro, monto) 
					VALUES (  $id_sol_oculto, '$institucion', '$fedesde', '$fehasta','$cobrobs',$montobs)";
					$rs        = $ds->executesql($ins);
					
				$j++;
					}
				}


$updateDatos="UPDATE 
  public.mst_dato_personal_sso  
SET 
  direccion = '$direccion',
  telefono_local = '$tel_habitacion',
  telefono_celular = '$tel_celular',
  actualizado = 1,
  email='$correo'
WHERE 
  ce_trabajador = $cedula";
  
$ds	       = new DataStore($dbr);
$rs3        = $ds->executesql($updateDatos); 


	
	}
function HistoriaSolicitud(){
	global $cedula, $dbr, $db, $dbs;	
	 $sql = "SELECT * FROM his_solicitud_jubilacion where ce_trabajador= $cedula";
				$ds2    = new DataStore($dbr, $sql);
				if($ds2->getNumRows()<=0){
				$res=0; 
				
				echo $res=0 ;	
				  }else{
					  $i=0;
					  $j=$ds2->getNumRows();
					  $cadena="";
					  while($i < $j){
				      $rows=$ds2->getValues($i);
					  $_SESSION['solicitud']=$rows['id_solicitud'];
					  if($i==0){
						 $cadena.="<span class='text-danger'> *  Usted puede consultar continuamente, a travès de este sistema, el
estatus de su solicitud de jubilaciòn</span><br><span class='has-success'>".$rows['fecha']."</span> Solicitud de Jubilaci&oacute;n Recibida <button type='button' id='ver_planilla' class='btn btn-warning btn-sm'>Ver</button><br>"; 
						  }else{

							$cadena.="".$rows['fecha']." El estatus de su solicitud ha cambiado a <strong>".$rows['estatus']."</strong><br>";
							if($rows['estatus']=='JUBILACION APROBADA'){
								$cadena.="<span class='text-danger'>* Cuenta usted con 30 dias hàbiles, para retirar ante la direcciòn de
recursos humanos, la resoluciòn de jubilaciòn, los dias martes y jueves en horario comprendido de 8:00am a 11:30am y de 3:00pm  a
4:30pm en el proceso de egreso de Personal Docente, Administrativo y Obrero, con el Sr. Victor Burgos </span>";

							}


							  }
							  
							 
				      $i++;
					  }
				 echo $cadena;
				   }     
	
	
	}


?>