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
 parentesco,
  modificado,
  nuevo,
  fecha_actualizacion,
  estatus,
  responsable,
  nombre_trabajador,
  nombre1,
  nombre2,
  apellido1,
  apellido2,
  acta_matrimonio,
  verificado,
  fe_matrimonio
) 
VALUES (
  $ce_trabajador,
  '$ce_familiar',
  '$nombrecompleto',
  '$sexo_fam',
  '$fe_nac_fam',
  1
  ,
  'C',
  1,
  1,
  '$fecha',
  1,
  $ce_trabajador,
  '$nombre_trabajador',
  '$nombre1',
  '$nombre2',
  '$apellido1',
  '$apellido2',
  '$actanacimiento',
  0,
  '$fe_mat_fam'
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
  public.mst_familiares_beneficios where ce_trabajador=$ce_trabajador and nuevo=1 and verificado=0 and parentesco='C';";
  
        $ds = new DataStore($db, $sql2);
		$rows = $ds->getNumRows();
                        $i    = 0;
    	if($ds->getNumRows()==0){
		echo "1";
		}else{
		
echo "<table  cellpadding='0' cellspacing='0' border='0' class='display' id='' style='font-size:10px' width='100%'>
      
                <tr>
                <th><h3><b>Nombre</b></h3></th>
				<th><h3><b>Sexo</b></h3></th>
				<th><h3><b>Fecha Nacimiento</b></h3></th>
				<th></th>
				<th></th>
				</tr>";

                        while ($i < $rows) {
                            $fila = $ds->getValues($i);
                            echo "<tr>";
                            echo '<td><h3>'.$fila['nombres'].'</h3></td>';
							echo '<td><h3>'.$fila['sexo'].'</h3></td>';
							echo '<td><h3>'.$fila['fe_nacimiento'].'</h3></td>';
							echo "<td><span><a onClick='EditaHijoRegistrado(" . $fila['id_familiar'] . "); return false'><img src='images/user_edit.png' width='32' height='32' title='Editar informaci&oacute;n del familiar' /></span></td>";
							echo "<td><span><a onClick='EliminarHijoRegistrado(" . $fila['id_familiar'] . "); return false'><img src='images/user_delete.png' width='32' height='32' title='Eliminar ' /></span></td>";
							echo "<td><span><a onClick='HijoPlanillaRegistrado(" . $fila['id_familiar'] . "); return false'><img src='images/page_white_acrobat.png' width='32' height='32' title='Carta' /></span></td>";
						
                            echo '</tr>';
                            
                            $i++;
           }
             echo '</table></br>';
			 
			 //<div align="center"><input type="button" title="Registrar nuevo hijo"  value="Nuevo" onclick="NuevoRegistroHijo();" class="btn btn-success" ></div>
        }	
	}
	
function  EliminarHijoRegistrado()
{	
global $db, $cedula, $anno,$ce_trabajador;
extract($_POST);

    $sql = "DELETE FROM mst_familiares_beneficios WHERE id_familiar=$id";
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
  public.mst_familiares_beneficios where id_familiar=$id and nuevo=1 and verificado=0 and parentesco='C';";
  
   $ds = new DataStore($db, $sql2);
   $rows = $ds->getNumRows();
      
   $fila = $ds->getValues(0);
	$jsondata[]=$fila;    
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
  fecha_actualizacion = '$fecha',
  nombre_trabajador =  '$nombre_trabajador',
  nombre1 = '$nombre1',
  nombre2 = '$nombre2',
  apellido1 = '$apellido1',
  apellido2 = '$apellido2',
  acta_matrimonio = '$actanacimiento',
  fe_matrimonio = '$fe_mat_fam'
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