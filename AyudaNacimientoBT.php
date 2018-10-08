<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();     
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');

$db="desarrolloRRHH";
$cedula=$_SESSION['cedula'];
$sexo=$_SESSION['sexo'];
$_SESSION['tipopersonal'];
$tipop=substr($_SESSION['tipopersonal'], 0,1 );
$anno=$_SESSION['ano_proceso'];
$op= $_REQUEST['op'];
$ce_trabajador=$_SESSION["cedula"];
$nombre_trabajador=$_SESSION["nombres"];
//***************************************** 
     switch($op) { 
	 //***********************************	 
	         case 'NuevoHijo':
	         NuevoHijo();
			 break;	
			 case 'consultaInicialHijos':
	         consultaInicialHijos();
			 break;	
			 case 'EliminarHijoRegistrado':
	         EliminarHijoRegistrado();
			 break;	
			 case 'datoshijoeditar':
	         datoshijoeditar();
			 break;	
			 case 'EditarNuevoHijo':
	         EditarNuevoHijo();
			 break;	
			

			 }

//****************************************
function NuevoHijo(){
global $db, $cedula, $anno,$ce_trabajador,$nombre_trabajador;
extract($_POST);

 $fecha = date('d-m-y');

$nombrecompleto=$nombre1.' '.$nombre2.' '.$apellido1.' '.$apellido2;

$sql = "
INSERT INTO 
  public.mst_familiares_beneficios
(
  ce_trabajador,
  ce_familiar,
  nombres,
  sexo,
  fe_nacimiento,
  condicion_personal,
  ce_otro_padre,
 parentesco,
  modificado,
  nuevo,
  fecha_actualizacion,
  estatus,
  responsable,
  nombre_conyuge,
  nombre_trabajador,
  nombre1,
  nombre2,
  apellido1,
  apellido2,
  acta,
  verificado
) 
VALUES (
  $ce_trabajador,
  0,
  '$nombrecompleto',
  '$sexo_fam',
  '$fe_nac_fam',
  1
  ,
  $ce_otro_p,
  'D',
  1,
  1,
  '$fecha',
  1,
  $ce_trabajador,
  '$nomb_otro_p',
  '$nombre_trabajador',
  '$nombre1',
  '$nombre2',
  '$apellido1',
  '$apellido2',
  '$actanacimiento',
  0
);";
    
$ds = new DataStore($db, $sql);

	if ($ds->getNumRows() > 0) 
	{
echo "1";
	}else{
echo "0";
	}	
}


//****************************************
function consultaInicialHijos(){
global $db, $cedula, $anno,$ce_trabajador;
extract($_POST);

 $sql2 ="SELECT 
 id_familiar,
  nombres,
  sexo,
  fe_nacimiento
  FROM 
  public.mst_familiares_beneficios where ce_trabajador=$ce_trabajador and nuevo=1 and verificado=0 and parentesco='D'";
  
        $ds = new DataStore($db, $sql2);
		$rows = $ds->getNumRows();
                        $i    = 0;
    	if($ds->getNumRows()==0){
		echo "1";
		}else{
		
echo "<table  cellpadding='0' cellspacing='0' border='0' class='' id='' style='font-size:10px' width='100%'>
      
                <tr>
                <th><b>Nombre</b></th>
				<th><b>Sexo</b></th>
				<th><b>Fecha Nacimiento</b></th>
				<th></th>
				<th></th>
				</tr>";

                        while ($i < $rows) {
                            $fila = $ds->getValues($i);
                            echo "<tr>";
                            echo '<td>'.$fila['nombres'].'</td>';
							echo '<td>'.$fila['sexo'].'</td>';
							echo '<td>'.$fila['fe_nacimiento'].'</td>';
							echo "<td><span><a onClick='EditaHijoRegistrado(" . $fila['id_familiar'] . "); return false'><img src='images/user_edit.png' width='32' height='32' title='Editar informaci&oacute;n del hijo' /></span></td>";
							echo "<td><span><a onClick='EliminarHijoRegistrado(" . $fila['id_familiar'] . "); return false'><img src='images/user_delete.png' width='32' height='32' title='Eliminar ' /></span></td>";
							echo "<td><span><a onClick='HijoPlanillaRegistrado(" . $fila['id_familiar'] . "); return false'><img src='images/page_white_acrobat.png' width='32' height='32' title='Carta' /></span></td>";
						
                            echo '</tr>';
                            
                            $i++;
           }
             echo '</table></br><div align="center"><input type="button" title="Registrar nuevo hijo"  value="Nuevo" onclick="NuevoRegistroHijo();" class="btn btn-success" ></div>';
        }	
	}
	
function  EliminarHijoRegistrado()
{	
global $db, $cedula, $anno,$ce_trabajador;
extract($_POST);

    $sql = "DELETE FROM mst_familiares_beneficios WHERE id_familiar=$id and parentesco='D'";
    $ds  = new DataStore($db, $sql);
	
	$aux = $ds->getNumRows();
	
	 if ($aux >0) {
          echo "1";
        } else {
          echo "0";
        }

}

function datoshijoeditar(){
global $db, $cedula, $anno,$ce_trabajador;
extract($_POST);
	
$sql2 ="SELECT 
*
  FROM 
  public.mst_familiares_beneficios where id_familiar=$id and nuevo=1 and verificado=0 and parentesco='D'";
  
   $ds = new DataStore($db, $sql2);
   $rows = $ds->getNumRows();
      
   $fila = $ds->getValues(0);
	
	$jsondata = array();

    $jsondata['nombre1'] = $fila['nombre1'];
    $jsondata['nombre2'] = $fila['nombre2'];
    $jsondata['apellido1'] = $fila['apellido1'];
	$jsondata['apellido2'] = $fila['apellido2'];
	$jsondata['sexo'] = $fila['sexo'];
	$jsondata['fe_nacimiento'] = $fila['fe_nacimiento'];
    $jsondata['ce_otro_p'] = $fila['ce_otro_padre'];
    $jsondata['acta'] = $fila['acta'];
	$jsondata['nombre_conyuge'] = $fila['nombre_conyuge'];
	$jsondata['id_familiar'] = $fila['id_familiar'];
    
    echo json_encode($jsondata);

	}
	
function EditarNuevoHijo()
{
global $db, $cedula, $anno,$ce_trabajador;
extract($_POST);

$fecha = date('d-m-y');
$nombrecompleto=$nombre1.' '.$nombre2.' '.$apellido1.' '.$apellido2;

  $sql = "UPDATE 
  public.mst_familiares_beneficios  
  SET 
  nombres = '$nombrecompleto',
  sexo =   '$sexo_fam',
  fe_nacimiento = '$fe_nac_fam',
  ce_otro_padre =  $ce_otro_p,
  fecha_actualizacion = '$fecha',
  nombre_conyuge = '$nomb_otro_p',
  nombre_trabajador =  '$nombre_trabajador',
  nombre1 = '$nombre1',
  nombre2 = '$nombre2',
  apellido1 = '$apellido1',
  apellido2 = '$apellido2',
  acta = '$actanacimiento'
  WHERE 
  id_familiar=$id_familiar_editar";
  
    $ds  = new DataStore($db, $sql);
	
	$aux = $ds->getNumRows();
	
	 if ($aux >0) {
          echo "2";
        } else {
          echo "0";
        }
	}
?>