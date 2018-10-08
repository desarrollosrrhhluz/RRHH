<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
//error_reporting(0);
session_start();    

include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');
 require './includes/class.phpmailer.php';
$db="desarrolloRRHH";
$dbs="sidial2";
$db1="sidial";
$dbr="rrhh";
$cedula=$_SESSION['cedula'];
$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
	        
			 case 'carga_conceptos':
	         carga_conceptos();
			 break;
			 
			  case 'carga_especifica':
	         carga_especifica();
			 break;
			 
			  case 'ordinaria':
	         ordinaria();
			 break;
			
			 		 
			 }

//****************************************

//*******************************************
function carga_conceptos(){
global $db, $dbs, $ced ;
$rif=strtoupper(str_replace("-",'',$_SESSION['rif']));

//J-31262078-1
$dbs='sidial2';
 $sql =  "select * from VW_CONCEPTO_BENEFICIARIO where RIF_SIALUZ='$rif'";
    $ds     = new DataStore($dbs, $sql);
    if($ds->getNumRows()<=0){
        
    } else {
		$i=0;
          while($i < $ds->getNumRows()){
			 $fila=$ds->getValues($i);
			 echo '<option value='.$fila['CO_CONCEPTO'].'>'.$fila['CO_CONCEPTO'].'-'.$fila['DESCRIPCION'].'</option>';
			  
			  $i++;
			  }
    }

		  
}

//****************************************************
function carga_especifica(){
global $db, $dbs, $ced ;
$rif=$_SESSION['rif'];

$dbs='sidial2';
$sql =  "select * from TAB_TIPO_NOMINA where TIPO_NOMINA in (SELECT TIPO_NOMINA from HIS_DETALLE_ESPECIAL where CO_CONCEPTO='X932' and ANO=2011 and MES=10";
    $ds     = new DataStore($dbs, $sql);
    if($ds->getNumRows()<=0){
        
    } else {
		$i=0;
          while($i < $ds->getNumRows()){
			 $fila=$ds->getValues($i);
			 echo '<option value='.$fila['CO_CONCEPTO'].'>'.$fila['CO_CONCEPTO'].'-'.$fila['DESCRIPCION'].'</option>';
			  
			  $i++;
			  }
    }

		  
}
//***************************************************
function ordinaria(){
	global $db, $dbs,$db1, $ced ;
$rif=strtoupper(str_replace("-",'',$_SESSION['rif']));
extract($_POST);
	//select SUM(MO_CONCEP) as TOTAL , count(CE_TRABAJADOR) as CANTIDAD,IN_NOMINA, (select  DESCRIPCION from TAB_CONCEPTO where CO_CONCEPTO='X934')as CONCEPTO  from HIS_DETALLE_PAGO where CO_CONCEPTO='X934' and ANO=2011 and MES=11 and STATUS_DEDUCCION!=3 group by IN_NOMINA;
	
	$sql =  "select SUM(DEDUCCIONES) as TOTAL , count(CE_TRABAJADOR) as CANTIDAD,IN_NOMINA,
(select count(*)  from VW_DETALLE_PAGO_DEDUCCIONES where CO_CONCEPTO='$conceptos' and ANO=$anno and MES=$mes
and STATUS_DEDUCCION=1 and IN_NOMINA= A.IN_NOMINA) as FALTANTES,
case (select  DESCUENTO from VW_CONCEPTO_BENEFICIARIO where CO_CONCEPTO='$anno') 
 when 'S' then SUM(DEDUCCIONES)*0.05
        when 'N' then 0 END  
 as DESCUENTO,(select  DESCRIPCION from VW_CONCEPTO_BENEFICIARIO where CO_CONCEPTO='$conceptos')as CONCEPTO 
 from VW_DETALLE_PAGO_DEDUCCIONES  as A
where CO_CONCEPTO='$conceptos'
and ANO=$anno and MES=$mes
and STATUS_DEDUCCION!=1 
group by IN_NOMINA";
    $ds     = new DataStore($dbs, $sql);
    if($ds->getNumRows()<=0){
        echo "<h2>No se encontraron resultados asociados a su busqueda</h2>";
    } else {
		$i=0;
		echo "<fieldset><h2>Resumen de Deducciones</h2>";
		echo '<table  border="0"  class="table">
  <tr >
    <th  ><strong>CODIGO - DESCRIPCION</strong></th>
    <th class="visible-lg" ><strong>MONTO</strong></th>
    <th ><strong>Nro. PERSONAS</strong></th>
     <th ><strong>Opciones</strong></th>
    <th class="visible-lg" ><strong>SOBREGIROS</strong></th>
    
  </tr>';
          while($i < $ds->getNumRows()){
			 $fila=$ds->getValues($i); 
			 if($fila['IN_NOMINA']=='1'){ $texto="Pers. Docente";}
			 if($fila['IN_NOMINA']=='2'){ $texto="Pers. Administrativo";}
			 if($fila['IN_NOMINA']=='3'){ $texto="Pers. Obrero";}
	echo '
  <tr>
    <td>'.$fila['CONCEPTO'].' <strong>'.$texto.'</strong> </td>
    <td class="visible-lg" style="text-align:rigth">'.number_format($fila['TOTAL'],2,',','.' ).'</td>
    <td style="text-align:center">'.$fila['CANTIDAD'].'</strong> </td>
    <td style="text-align:center"><a href="imprimeDeduccionesPDF.php?ano='.$anno.'&mes='.$mes.'&tip='.$fila['IN_NOMINA'].'&cod='.$conceptos.'" target="_blank">Imprimir</a>/ <a href="imprimeDeduccionesTXT.php?ano='.$anno.'&mes='.$mes.'&tip='.$fila['IN_NOMINA'].'&cod='.$conceptos.'" target="_blank">Descargar</a></td>
    <td class="visible-lg">',($fila['FALTANTES']>0 ?'<a href="imprimeDeduccionesFaltantesPDF.php?ano='.$anno.'&mes='.$mes.'&tip='.$fila['IN_NOMINA'].'&cod='.$conceptos.'" target="_blank">'.$fila['FALTANTES'].' </a>': $fila['FALTANTES'] ),'</td>
  </tr>
';
	$i++;
		  }
		echo '</table>';  
		  }
	echo "</fieldset>";
	}
?>