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
	        case 'buscar':
	        buscar();
			 break;	
			 
			  case 'datosPersonales':
			 $idf=$_REQUEST['id'];
	         datosPersonales($idf);
			 break;	
			 
			  case 'datosCargo':
			 $idf=$_REQUEST['id'];
	         datosCargo($idf);
			 break;			 
			 }

//****************************************
function buscar(){
global $cedula, $dbr, $db, $dbs;
extract($_POST);
if(is_numeric($valor)){
	
	    
		$sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where CE_TRABAJADOR=$valor  ";
        $ds     = new DataStore($dbs, $sql2);
    	if($ds->getNumRows()<=0){
		echo "No se encuentran valores asociados a su busqueda";
		}else{
		$j=$ds->getNumRows();	
			$i=0;
			echo '<table class="table font_12">
  <tr>
    <th >CEDULA</td>
    <th >NOMBRES</td>
    <th >SEXO</td>
    <th class="visible-lg">FE_NACIMIENTO</td>
    <th class="visible-lg">FE_INGRESO</td>
    <th class="visible-lg">TIPO_PERSONAL</td>
    <th >ESTATUS</td>
    <th class="visible-lg">UBICACION</td>
    <th >OPCIONES</td>
  </tr>';
			while($i<$j){
			$fila = $ds->getValues($i);	
			$cadena=$fila['CE_TRABAJADOR'].'-'.$fila['TIPOPERSONAL'].'-'.$fila['CO_UBICACION'];
				echo ' <tr>
    <td >'.$fila['CE_TRABAJADOR'].'</td>
    <td >'.utf8_encode($fila['NOMBRES']).'</td>
    <td >'.$fila['SEXO'].'</td>
    <td class="visible-lg">'.formato_fecha($fila['FE_NACIMIENTO']).'</td>
    <td class="visible-lg">'.formato_fecha($fila['FE_INGRESO']).'</td>
    <td class="visible-lg">'.$fila['TIPOPERSONAL'].'</td>
    <td >'.$fila['ESTATUS'].'</td>
    <td class="visible-lg">'.$fila['DE_UBICACION'].'</td>
    <td ><img src="images/user_orange.png" width="25" height="25" title="Datos Personales" class="datper" id='.$cadena.' />
	  <img src="images/client_account_template.png" width="25" height="25" title="Datos del Cargo" class="datcargo" id='.$cadena.' />
	</td>
  </tr>';
				$i++;
				}
			echo "</table>";
			}
	}else{
		$valor=str_replace(" ","%",$valor);
		$valor=strtoupper($valor);
		$sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where NOMBRES like '%$valor%'  ";
        $ds     = new DataStore($dbs, $sql2);
    	if($ds->getNumRows()<=0){
		echo "No se encuentran valores asociados a su busqueda";
		}else{
		$j=$ds->getNumRows();	
			$i=0;
			echo '<table class="table font_12" id="tabla_p">
  <tr>
    <th >CEDULA</td>
    <th >NOMBRES</td>
    <th >SEXO</td>
    <th class="visible-lg">FE_NACIMIENTO</td>
    <th class="visible-lg">FE_INGRESO</td>
    <th class="visible-lg">TIPO_PERSONAL</td>
    <th >ESTATUS</td>
    <th class="visible-lg">UBICACION</td>
    <th >OPCIONES</td>
  </tr>';
			while($i<$j){
			$fila = $ds->getValues($i);	
			$cadena=$fila['CE_TRABAJADOR'].'-'.$fila['TIPOPERSONAL'].'-'.$fila['CO_UBICACION'];
				echo ' <tr>
    <td >'.$fila['CE_TRABAJADOR'].'</td>
    <td >'.utf8_encode($fila['NOMBRES']).'</td>
    <td >'.$fila['SEXO'].'</td>
    <td class="visible-lg">'.formato_fecha($fila['FE_NACIMIENTO']).'</td>
    <td class="visible-lg">'.formato_fecha($fila['FE_INGRESO']).'</td>
    <td class="visible-lg">'.$fila['TIPOPERSONAL'].'</td>
    <td >'.$fila['ESTATUS'].'</td>
    <td class="visible-lg">'.$fila['DE_UBICACION'].'</td>
    <td ><img src="images/user_orange.png" width="25" height="25" title="Datos Personales" class="datper" id='.$cadena.' />
	  <img src="images/client_account_template.png" width="25" height="25" title="Datos del Cargo" class="datcargo" id='.$cadena.' />
	</td>
  </tr>';
				$i++;
				}
			echo '</table><div id="tablePagination" class="font_12" ><div class="row"><div class="col-lg-1"><select id="tablePagination_rowsPerPage" class="form-control"></select></div><div class="col-lg-2"><span id="tablePagination_perPage" >Por p&aacute;gina</span></div>
			 <span id="tablePagination_paginater">
			  <div class="col-lg-1">
			 <img id="tablePagination_firstPage" src="images/sprevious.png">
			 <img id="tablePagination_prevPage" src="images/previous.png">
			 </div>
			  <div class="col-lg-1">
			  <input id="tablePagination_currPage" class="form-control"  value="1" size="1" type="input"> 
			  </DIV>
			  <div class="col-lg-2">de  <span id="tablePagination_totalPages"></span>
			 
			  <img id="tablePagination_nextPage" src="images/next.png">
			  <img id="tablePagination_lastPage" src="images/snext.png"> </div>
			  </span>
			 
			  
			  </div></div>';
			}
		
		

		}
}

function datosPersonales($idf){
global $cedula, $dbr, $db, $dbs;
  $array=explode('-',$idf);
  
 $sql2 =  "select *, 
(select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION ,
(select DESCRIPCION from TAB_TIPO_PERSONAL where TIPOPERSONAL=V_DATPER.TIPOPERSONAL) as DE_TIPOPERSONAL 
from V_DATPER where CE_TRABAJADOR=".$array[0]." and TIPOPERSONAL='".$array[1]."' and CO_UBICACION=".$array[2]."  ";
        $ds     = new DataStore($dbs, $sql2);
    	if($ds->getNumRows()<=0){
		echo "No se encuentran valores asociados a su busqueda";
		}else{
			$fila = $ds->getValues(0);
			$arreglo=array("A"=>"AHORRO","E"=>"EFECTIVO","C"=>"CORRIENTE" );
			$forma=trim($fila['FORMAPAGO']);
			$forma=$arreglo[$forma];
		echo '<div class="row"><div class="col-lg-2">Cedula</div><div class="col-lg-3"><input id="" class="form-control"  value="'.$fila['CE_TRABAJADOR'].'"  type="text"/></div><div class="col-lg-2">RIF</div><div class="col-lg-3"><input id="" class="form-control"  value="'.$fila['NU_RIF'].'"  type="text"/></div></div>';	
		echo '<div class="row"><div class="col-lg-2">Estatus:</div><div class="col-lg-3"><input id="" class="form-control"  value="'.$fila['ESTATUS'].'"  type="text"/></div></div>';
		echo '<div class="row"><div class="col-lg-2">Nombres:</div><div class="col-lg-8"><input id="" class="form-control"  value="'.utf8_encode($fila['NOMBRES']).'"  type="text"/></div></div>';
		echo '<div class="row"><div class="col-lg-2">Sexo:</div><div class="col-lg-3"><input id="" class="form-control"  value="'.$fila['SEXO'].'"  type="text"/></div><div class="col-lg-2">Edo. Civil</div><div class="col-lg-3"><input id="" class="form-control"  value=""  type="text"/></div></div>';
		echo '<div class="row"><div class="col-lg-2">Fecha Nacimiento:</div><div class="col-lg-3"><input id="" class="form-control"  value="'.formato_fecha($fila['FE_NACIMIENTO']).'"  type="text"/></div></div>';
		echo '<div class="row"><div class="col-lg-2">Direccion:</div><div class="col-lg-8"><input id="" class="form-control"  value="'.$fila['DIRECCION'].'"  type="text"/></div></div>';
		echo '<div class="row"><div class="col-lg-2">Telefonos:</div><div class="col-lg-8"><input id="" class="form-control"  value="'.$fila['TELEFONOS'].'"  type="text"/></div></div><br><br>';
		echo '<div class="row"><div class="col-lg-2">Banco:</div><div class="col-lg-8"><input id="" class="form-control"  value="'.$fila['DE_BANCO'].'"  type="text"/></div></div>';
		echo '<div class="row"><div class="col-lg-2">Cuenta:</div><div class="col-lg-8"><input id="" class="form-control"  value="'.$fila['CTABANCO'].'"  type="text"/></div></div>';
		echo '<div class="row"><div class="col-lg-2">Forma Pago:</div><div class="col-lg-8"><input id="" class="form-control"  value="'.$forma.'"  type="text"/></div></div>';
		}
	
	}


?>