<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();

include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHHCM';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];


$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$ci =$_SESSION['cedula'];
////////////////////////////////////////////////////////
		$db     = "sidial";
		$sql2 =  "select *, (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION) as DE_UBICACION from V_DATPER where CE_TRABAJADOR=$ci and ESTATUS in ('A','C','F') and TIPOPERSONAL like '3%' and ESTATUS!='R' and ESTATUS!='S' ";
        $ds     = new DataStore($db, $sql2);
    	if($ds->getNumRows()==0){
		
		echo "<h3><img src='images/group.png' width='32' height='32' />Usted NO esta Autorizado</h3><p>Usted no posee los permisos adecuados, no puede acceder a esta aplicaci&oacute;n.</p>";
		exit;
		}else{
		$row = $ds->getValues(0);
		$_SESSION['sexo']=$row['SEXO'];
		$_SESSION['tipopersonal']=$row['TIPOPERSONAL'];
		$_SESSION['coubicacion']=$row['CO_UBICACION'];
		$_SESSION['estatus']=$row['ESTATUS'];
		$_SESSION['nombres']=trim(($row['NOMBRES']));
    }
?>
<h2><img src="images/document_layout.png" width="32" height="32">Trabajador y Familiares poliza H.C.M </h2><hr>

<fieldset><legend><h3><img src="images/group.png" width="32" height="32" />Familiares Registrados</h3></legend>
<?php 
$db="desarrolloRRHH";
$sql2 =  "SELECT *,
    (select estatus from admon_personal.mst_hcm_obreros where  id_familiar=mst_familiares_prima_new.id_familiar) as principal,
    (select ce_trabajador from admon_personal.mst_hcm_obreros where id_familiar=mst_familiares_prima_new.id_familiar) as titular,
    EXTRACT(year FROM age(timestamp 'now()',date(fe_nacimiento) ) ) as edad 
    FROM admon_personal.mst_familiares_prima_new where  (ce_trabajador=$ci or ce_otro_padre=$ci) and mst_familiares_prima_new.estatus=1  order by  nombres ";
        $ds     = new DataStore($dbr, $sql2);
      if($ds->getNumRows()<=0){
    echo "Aun no tiene hijos registrados<br>";
    }else{
    $i=$ds->getNumRows();
    $j=0;
    echo "<table width='90%' border='0' cellspacing='0' cellpadding='0' id='tabla_familiares' class='table table-striped table-hover'><tr><td><b>Nombres</b></td><td><b>CI</b></td><td class='hidden-xs'><b>Sexo</b></td><td class='hidden-xs'><b>Parentesco</b></td><td class='hidden-xs'><b>Edad</b></td><td class='hidden-xs'><b>Fecha de nacimiento</b></td><td><b>Titular</b></td><td><b>Estatus</b></td></tr>";
    while ($j<$i){
    $row = $ds->getValues($j);
    
    $titular=$row['titular'];
    if(!empty($titular)){
     $sql =  "select CE_TRABAJADOR, NOMBRES from V_DATPER where CE_TRABAJADOR=$titular";
    $ds1     = new DataStore($dbs, $sql);
    $resultado = $ds1->getValues(0);
    $nombre_titular=utf8_encode($resultado['NOMBRES']);
    }
    
    
    if($row['principal']==1){ $estatus='<img src="images/Dialog-Apply-32.png" width="15" height="15"   title="Asegurado por '.trim($nombre_titular).' " />'; }else{$estatus='<img src="images/equis.png" width="15" height="15"   title="Familiar Inactivo" />';}
    // if($cedula==$row['ce_trabajador']){
    //  $trabajador=$estatus ;
    //  }
    //  if($cedula==$row['ce_otro_padre']){
    // $trabajador=$estatus ;
    // }
  
echo"<tr><td>".($row['estatus']!=1?'<img src="images/Gnome-Dialog-Warning-32.png" width="15" height="15"   title="Familiar inactivo" />':'')." ".strtoupper($row['nombres'])."</td><td>".$row['ce_familiar']."</td><td class='hidden-xs'>".$row['sexo']."</td><td class='hidden-xs'>".$row['parentesco']."</td><td class='hidden-xs'>".$row['edad']."</td><td class='hidden-xs'>".$row['fe_nacimiento']."</td><td>".$nombre_titular."</td><td>".$estatus ."</td></tr>";
 $j++;
          }
  
      
  echo "</table>";    
      
    }


?>
</fieldset>
   

