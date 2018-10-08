<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(ALL); 
  
session_start();     
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');

$db="rrhh";
$dbs     = "sidial";
$cedula=$_SESSION['cedula'];
$sexo=$_SESSION['sexo'];
$_SESSION['tipopersonal'];
$nombreTrabajador=$_SESSION['nombres'];
$tipop=substr($_SESSION['tipopersonal'], 0,1 );
$anno=$_SESSION['ano_proceso']=2017;
$op= $_REQUEST['op'];

//***************************************** 
     switch($op) { 
	 //***********************************	 
	         case 'consultaInicialHijos':
	         consultaInicialHijos();
			 break;	
			 case 'guarda_adicional':
	        guarda_adicional();
			 break;	
			 case 'eliminaFamiliarAdicionalSel':
			 $idf=$_REQUEST['id'];
	         eliminaFamiliarAdicionalSel($idf);
			 break;	
			 case 'query_jsonFamiliar':
			 $idf=$_REQUEST['id'];
	         query_jsonFamiliar($idf);
			 break;	
			 case 'query_jsonFamiliarAdicional':
			 $idf=$_REQUEST['id'];
	         query_jsonFamiliarAdicional($idf);
			 break;
			 case 'UpdateFamiliar':
			 UpdateFamiliar();
			 break;

			 case 'UpdateFamiliarAdicional':
			 UpdateFamiliarAdicional();
		   	 break;
			 //*************************
				

			 //*******************
			 case 'insertaPoliza':
			 $cadena=$_REQUEST['id'];
	         insertaPoliza($cadena);
			 break;
			 case 'consultaSidial':
			 $ced=$_REQUEST['ced'];
			 $tipo=$_REQUEST['tipo'];
	         consultaSidial($ced, $tipo);
			 break;
			
			 
			  
			 }

//****************************************
function consultaInicialHijos(){
global $db, $cedula, $anno;

$var=0;

$parent=["D"=>"Hijo(a)","A"=>"Padre/Madre","C"=>"Conyuge","H"=>"Hermano(a)","N"=>"Nieto(a)","S"=>"Suegro(a)", "DH"=>"Sobrino(a)", "DM"=>"Hijo(a)"];

echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='table table-condensed '> <thead><tr><td><b>Nombres</b></td><td class='hidden-xs hidden-sm'><b>Cedula</b></td><td class='hidden-xs hidden-sm'><b>Sexo</b></td><td class='hidden-xs hidden-sm'><b>Parentesco</b></td><td class='hidden-xs hidden-sm'><b>Fecha Nac.</b></td><td></td><td></td><td></td></tr> </thead><tbody>";


$sql2 ="SELECT id_familiar,nombres,ce_familiar,sexo, parentesco ,fe_nacimiento ,EXTRACT(year FROM age(timestamp 'now()',date(fe_nacimiento) ) ) as edad, 
 (select b.ce_trabajador  from admon_personal.tab_detalle_funeraria as a, admon_personal.tra_planilla_funeraria as b 
where a.id_planilla=b.id
and a.id_familiar=admon_personal.mst_familiares_beneficios.id_familiar
and a.tab_origen='mst_familiares_beneficios'
and b.anno=$anno
 group by b.ce_trabajador) as tiene_cobertura
 FROM admon_personal.mst_familiares_beneficios where   nuevo!=1 
 and estatus=1 
 and (ce_trabajador=$cedula or ce_otro_padre=$cedula) 
 order by id_familiar ";
        $ds     = new DataStore($db, $sql2);
    	if($ds->getNumRows()<=0){
		//echo "Aun no tiene familiares registrados";
		}else{
		$i=$ds->getNumRows();
		$j=0;
		$concat="";
		while ($j<$i){
		$row = $ds->getValues($j);
		
		
	if( $row['tiene_cobertura'] == $cedula ){
		$var++;	
				
		$boton="<a href='#' title='Ya posee cobertura'><span class='glyphicon glyphicon-check'></span></a>  ";  
	
		}else{

			if(is_null($row['tiene_cobertura'])){
		     $boton="<button type='button' id=".$row['id_familiar']." class='clic_editar_p btn btn-primary btn-sm '><span class='glyphicon glyphicon-pencil'></span> </button> ";  

			}else{ 
		     $boton="<button type='button' id=".$row['tiene_cobertura']." class='clic_otro_padre btn btn-warning btn-sm'><span class='glyphicon glyphicon-warning-sign' title='Solicitado Otro Padre'></span> Solicitado</button>";  

			}
		}
		

   
	   if(!is_null($row['tiene_cobertura'])){
		   if($row['tiene_cobertura'] == $cedula){
		   	
			     $check="<input name='solicita' type='checkbox' disabled='disabled' class='checkFam' id='p_".$row['id_familiar']."' value='p_".$row['id_familiar']."' />";
			   }else{
				 $check=" - ";    
				   }   
		   }else{
			$check="<input name='solicita' type='checkbox'  class='checkFam' id='p_".$row['id_familiar']."' value='p_".$row['id_familiar']."' />";   
			   }

			  	$trab=0;
	   if($row['edad'] > 18){
	   	if(!empty($row['ce_familiar'])){
	   	$trab=consultaSidial($row['ce_familiar'],2);
	   	if($trab==1){
        $check="-";
        $boton="<button type='button' class=' btn btn-warning btn-sm' title='Es personal de LUZ'><span class='glyphicon glyphicon-warning-sign' ></span> </button>";  	
	   	}
	  
	   	}
	   }
	   
  ///////////////////////////// 
echo"<tr><td>".(empty($row['nombre1'])? "".strtoupper($row['nombres'])."":"".strtoupper("".strtoupper($row['apellido1'])." ".strtoupper($row['apellido2'])." ".strtoupper($row['nombre1'])." ".strtoupper($row['nombre2'])."")."")."</td>
<td class='hidden-xs hidden-sm '>".$row['ce_familiar']."</td>
<td class='hidden-xs hidden-sm'>".$row['sexo']."</td>
<td class='hidden-xs hidden-sm'>".$parent[$row['parentesco']]."</td>
<td class='hidden-xs hidden-sm'>".date("d/m/Y",strtotime($row['fe_nacimiento']))."</td>
<td>".$situacion."</td>
<td align='center' >".$boton." </td><td align='center'>".$check."</td></tr>";


 $j++;
         
		  }

}
// **************************familiares adicionales*************************

	 $sqlA ="SELECT id_familiar,nombres,ce_familiar,sexo, parentesco, fe_nacimiento ,EXTRACT(year FROM age(timestamp 'now()',date(fe_nacimiento) ) ) as edad, 
 (select b.ce_trabajador  from admon_personal.tab_detalle_funeraria as a, admon_personal.tra_planilla_funeraria as b 
where a.id_planilla=b.id
and a.id_familiar=admon_personal.mst_familiares_adicionales.id_familiar
and a.tab_origen='mst_familiares_adicionales'
and b.anno=$anno
 group by b.ce_trabajador) as tiene_cobertura
 FROM admon_personal.mst_familiares_adicionales where 
  estatus=1 
 and ce_trabajador=$cedula 

 order by id_familiar 
";
        $ds2     = new DataStore($db, $sqlA);
    	if($ds2->getNumRows()==0){
		
		}else{
		$l=$ds2->getNumRows();
		$k=0;
		$concat="";

		while ($k<$l){
		$fila = $ds2->getValues($k);
		
		
	if( $fila['tiene_cobertura'] == $cedula ){
			$var++;		
		
			$boton="<a href='#' title='Ya posee cobertura'><span class='glyphicon glyphicon-check'></span></a>  "; 
		}else{
			if(is_null($fila['tiene_cobertura'])){
							
		     $boton="<button type='button' id=".$fila['id_familiar']." class='clic_editar_a btn btn-primary btn-sm '><span class='glyphicon glyphicon-pencil'></span></button>
		     <button type='button' id=".$fila['id_familiar']." class='clic_eliminar_a btn btn-danger btn-sm '><span class='glyphicon glyphicon-trash'></span></button>  ";  
		 }
		}

	   if(!is_null($fila['tiene_cobertura'])){
		   if($fila['tiene_cobertura'] == $cedula){
			     $check="<input name='solicita' type='checkbox' disabled='disabled' class='checkFam' id='a_".$fila['id_familiar']."' value='a_".$fila['id_familiar']."' />";
			   }else{
				 $check=" - ";    
				   }   
		   }else{
			    $check="<input name='solicita' type='checkbox'  class='checkFam' id='a_".$fila['id_familiar']."' value='a_".$fila['id_familiar']."' />";   
			   }
	   
		$trab=0;
	   if($fila['edad'] > 18){
	   	if(!empty($fila['ce_familiar'])){
	   	$trab=consultaSidial($fila['ce_familiar'],2);
	   	if($trab==1){
        $check="-";
        $boton="<button type='button' class=' btn btn-warning btn-sm' title='Es personal de LUZ'><span class='glyphicon glyphicon-warning-sign' ></span> </button>";  	
	   	}
	   	}
	   }
  ///////////////////////////// 
echo"<tr><td>".(empty($fila['nombre1'])? "".strtoupper($fila['nombres'])."":"".strtoupper("".strtoupper($fila['apellido1'])." ".strtoupper($fila['apellido2'])." ".strtoupper($fila['nombre1'])." ".strtoupper($fila['nombre2'])."")."")."</td>
<td class='hidden-xs hidden-sm '>".$fila['ce_familiar']."</td>
<td class='hidden-xs hidden-sm'>".$fila['sexo']."</td>
<td class='hidden-xs hidden-sm'>".$parent[$fila['parentesco']]."</td>
<td class='hidden-xs hidden-sm'>".date("d/m/Y",strtotime($fila['fe_nacimiento']))."</td>
<td>".$situacion."</td>
<td align='center' >".$boton."</td><td align='center'>".$check."</td></tr>";
 
 
 $k++;
         
		  }

}


/*****************************************************************************/
	$numero=$var;
   if($var<=10){
	$botoSolicitud="<button type='button' id='sol_bene'  class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-star-empty'></span> Asegurar</button>";
		if($var>0){

	$botonPlanilla="<button type='button' id='clic_planilla'  class='clic_planilla btn btn-warning btn-sm'><span class='glyphicon glyphicon-star-empty'></span> Ver Planilla</button>";		
		}	
		}else{
	$botoSolicitud="<button type='button' disabled='disabled'  class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-star-empty'></span> Asegurar</button>";	
	}	
		
		  
	echo "</tbody>
	<tfoot><tr><td></td><td class='hidden-xs hidden-sm'></td><td class='hidden-xs hidden-sm'></td><td class='hidden-xs hidden-sm'></td><td></td><td></td><td>".$botoSolicitud." ".$botonPlanilla."</td></tr> </tfoot></table><input type='hidden' id='datoNumero' value='".(10 - $numero)."' >";	  
		  
		


}
//****************************************************
function guarda_adicional(){
global $db, $cedula, $sexo, $anno;
extract($_POST);
$ds	       = new DataStore($db);


$nombres_mayus=addslashes(strtoupper($nombre1.' '.$nombre2.' '.$apellido1.' '.$apellido2));


$fecharegistro=date("d/m/Y");
 $sql="INSERT INTO
  admon_personal.mst_familiares_adicionales
( ce_trabajador, ce_familiar, nombres, nombre1, nombre2, apellido1, apellido2, fe_nacimiento, sexo, parentesco, created_at , estatus)
VALUES 
($cedula,".( empty ( $ce_familiar ) ? 'NULL' :  $ce_familiar  ).", '$nombres_mayus', '".addslashes($nombre1)."', '".( empty ( $nombre2 ) ? NULL :  addslashes(($nombre2))  )."' , '".addslashes($apellido1)."', '".( empty ( $apellido2 ) ?  NULL :  addslashes(($apellido2)) )."', '$fe_nac_fam', '$sexo_fam', '$parentesco_fam','$fecharegistro', 1 )";
$rs        = $ds->executesql($sql);

if($rs>0){
echo '{ "message": "Datos de familiar almacenados con exito." }'; 
}else{
echo '{ "message": "Ha ocurrido un error en el proceso, vuelva a intentarlo" }'; 
} 
}
//**************************************************
//


function query_jsonFamiliar($idf){
global $db, $cedula, $anno;
$sql =  "SELECT  * FROM admon_personal.mst_familiares_beneficios where id_familiar= $idf";
        $ds     = new DataStore($db, $sql);
    	if($ds->getNumRows()<=0){
		$res=0; 
		 echo json_encode($res) ;	
		  }else{
		   $rows=$ds->getValues(0);
		   		   $res[]=$rows;
		   		   $res[0]['fe_nacimiento']=cambiaf_a_normal($rows['fe_nacimiento']);
					echo json_encode($res) ;	

		   }
}

function query_jsonFamiliarAdicional($idf){
global $db, $cedula, $anno;
$sql =  "SELECT  * FROM admon_personal.mst_familiares_adicionales where id_familiar= $idf";
        $ds     = new DataStore($db, $sql);
    	if($ds->getNumRows()<=0){
		$res=0; 
		 echo json_encode($res) ;	
		  }else{
		   $rows=$ds->getValues(0);
		   		   $res[]=$rows;
		   		   $res[0]['fe_nacimiento']=cambiaf_a_normal($rows['fe_nacimiento']);
					echo json_encode($res) ;	

		   }
}



//**************************************************
function  UpdateFamiliar(){
global $db, $cedula, $sexo, $anno,$nombreTrabajador;
extract($_POST);
$ds	       = new DataStore($db);
$fecharegistro=date("d/m/Y");
$nombres_mayus=strtoupper($nombre1.' '.$nombre2.' '.$apellido1.' '.$apellido2);


//

 $sql="UPDATE 
  admon_personal.mst_familiares_beneficios  
SET 
  ce_familiar = $ce_familiar,
  nombre_trabajador='".str_replace("'", " ", $nombreTrabajador)."',
  nombre_conyuge='".addslashes($nomb_otro_p)."',
  sexo = '$sexo_fam',
  nombres= '$nombres_mayus',
  ce_trabajador = $cedula,
  responsable = $cedula,
  ce_otro_padre = $ce_otro_p,
  modificado=2,
  nombre1='".addslashes(($nombre1))."',
  nombre2=".( empty ( $nombre2 ) ? "null" : "'" . addslashes(($nombre2))  . "'" ).",
  apellido1='".addslashes(($apellido1))."',
  apellido2='".addslashes(($apellido2))."',
  fecha_actualizacion = '$fecharegistro'
WHERE 
  id_familiar = $id_fo";


$rs        = $ds->executesql($sql);
if($rs>0){
echo '{ "message": "Datos almacenados con exito" }';  

}else{
echo '{ "message": "Ha ocurrido un error en el proceso, vuelva a intentarlo   " }'; 
} 


	   
	   
	

}

function  UpdateFamiliarAdicional(){
global $db, $cedula, $sexo, $anno,$nombreTrabajador;
extract($_POST);
$ds	       = new DataStore($db);
$fecharegistro=date("d/m/Y");
$nombres_mayus=addslashes(strtoupper($nombre1.' '.$nombre2.' '.$apellido1.' '.$apellido2));


//

 $sql="UPDATE 
  admon_personal.mst_familiares_adicionales  
SET 
  ce_familiar = ".(empty($ce_familiar)? 'NULL' : $ce_familiar).",
  sexo = '$sexo_fam',
  nombres= '$nombres_mayus',
  fe_nacimiento='$fe_nac_fam',
  ce_trabajador = $cedula,
  nombre1='".addslashes(($nombre1))."',
  nombre2=".( empty ( $nombre2 ) ? "null" : "'" . addslashes(($nombre2))  . "'" ).",
  apellido1='".addslashes(($apellido1))."',
  apellido2='".addslashes(($apellido2))."',
  parentesco='$parentesco_fam'

WHERE 
  id_familiar = $id_fo";


$rs        = $ds->executesql($sql);
if($rs>0){
echo '{ "message": "Datos almacenados con exito" }';  

}else{
echo '{ "message": "Ha ocurrido un error en el proceso, vuelva a intentarlo   " }'; 
} 


	   
	   
	

}


//**************************************************

//**************************************************
 function eliminaFamiliarAdicionalSel($idf){
 global $db, $cedula, $anno;
 $ds	       = new DataStore($db);
$sql="DELETE FROM admon_personal.mst_familiares_adicionales WHERE  id_familiar = $idf; DELETE FROM admon_personal.tab_detalle_funeraria WHERE  id_familiar = $idf and id_planilla in(select id from admon_personal.tra_planilla_funeraria where ce_trabajador=$cedula and anno=$anno)";
$rs        = $ds->executesql($sql);


echo '{ "message": "Datos eliminados con exito" }';  




 }




//*****************************************************
function insertaPoliza($cadena){
global $db, $cedula, $anno,$tipop;
$letras = '0x1o2m3b4r5a6H7b8c9dZ'; // letras y numeros que usaremos 
srand((double)microtime()*1000000);
$i = 1;
$largo_clave = 6; // tamaño maximo de clave generada
$largo = strlen($letras);
$clave_usuario='';
while ($i <= $largo_clave){ 
	 $lee = rand( 1,$largo);
     $clave_usuario .= substr($letras, $lee, 1); 
    $i++;                 
  }
$clave_usuario = trim($clave_usuario);
$pass = md5($clave_usuario); 

 $sql ="INSERT INTO admon_personal.tra_planilla_funeraria
 (  ce_trabajador, fe_operacion, anno, created_at, updated_at ) 
 VALUES (  $cedula, now(), $anno, NULL, NULL )";

 $ds  = new DataStore($db);
 $rs        = $ds->executesql($sql);
$last="SELECT currval('admon_personal.tra_planilla_funeraria_id_seq') as id";
$ds7     = new DataStore($db,$last);
$fila=$ds7->getValues(0);
$id_planilla=$fila['id'];
// 				//return $id;



$arreglo=explode(',', $cadena);

$j=count($arreglo);
$i=0;
$sqlFuneraria="";

while($i<$j){

	$idfam=substr($arreglo[$i],2);

	$tabla=(substr($arreglo[$i],0,1)=='a'? 'mst_familiares_adicionales':'mst_familiares_beneficios' );


 	$sqlFuneraria.="INSERT INTO admon_personal.tab_detalle_funeraria 
	( id_planilla, id_familiar, tab_origen, created_at )
	VALUES ( $id_planilla,$idfam,'$tabla', now() );";

	$i++;

}

$sqlFinal="BEGIN;".$sqlFuneraria." COMMIT;";
$ds3     = new DataStore($db);
$rs      = $ds3->executesql($sqlFinal);


if($rs==0){

	echo '{ "message": "Solictud almacenada con exito " }';  

}else{

	echo '{ "message": "Ha ocurrido un error en el proceso, vuelva a intentarlo" }'; 
} 


}


function consultaSidial($ced,$tipo){
global $db, $dbs,  $anno,$tipop;


$sql2 =  "select CE_TRABAJADOR from V_DATPER where CE_TRABAJADOR=$ced and ESTATUS in ('A','F') and ESTATUS!='R' and ESTATUS!='S' ";
$ds     = new DataStore($dbs, $sql2);
if($ds->getNumRows()<=0){
	
	if($tipo==1){

		$sql="select ce_familiar FROM admon_personal.mst_familiares_adicionales where ce_familiar=$ced
		union
		select ce_familiar FROM admon_personal.mst_familiares_prima_new where ce_familiar=$ced";

		$ds2     = new DataStore($db, $sql);
		if($ds2->getNumRows()<=0){
			echo json_encode(0);
		}else{
			echo json_encode(2);

		}
		
	}else{
		return 0;

	}		
}else{
	if($tipo==1){
		echo json_encode(1);
	}else{
		return 1;

	}	
}

}
?>