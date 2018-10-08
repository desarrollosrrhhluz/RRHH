<?php
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

session_start();


include_once("../FrameWork/Include/Defines.inc.php");
include_once("../Seguridad/verificaAccesoUsuario.inc.php");
$appName         ='RRHHCFU';
$urlError        = "errorLoad.html";
$urlDeshabilitado = "deshabilitadoLoad.html";

verificaAccesoUsuario($appName, $urlDeshabilitado, $urlError);
$_SESSION["retorno"] = $_SERVER["SCRIPT_NAME"];



$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";
$ci =$_SESSION['cedula'];
////////////////////////////////////////////////////////

?>
<h3><img src="images/document_layout.png" width="32" height="32">Trabajador y Familiares Previsión Funeraria </h3><hr>

<fieldset>
<?php 
$sql2 =  " select * from admon_personal.tab_resumen_funeraria where ce_relacion=$ci and anno+mes=(select max(anno+mes) from admon_personal.tab_resumen_funeraria) order by parentesco DESC";
        $ds     = new DataStore($dbr, $sql2);
      if($ds->getNumRows()<=0){
    echo "Aun no tiene familiares hijos registrados<br>";
    }else{
    $i=$ds->getNumRows();
    $j=0;

    $cadena= '<table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Cedula</th>
            <th>Nombres</th>
            <th>Sexo</th>
            <th>Fe. Nacimiento</th>
            <th>Parentesco</th>
            </th>
        </thead>
    ';
    while ($j<$i){
    $row = $ds->getValues($j);
    $cadena.= '<tr>
            <td>'.$row['ce_beneficiario'].'</td>
            <td>'.strtoupper($row['nombres']).'</td>
            <td>'.strtoupper($row['sexo']).'</td>
            <td>'.strtoupper($row['fe_nacimiento']).'</td>
            <td>'.strtoupper($row['parentesco']).'</td>
        </tr>
    ';
    $j++;

    }

$cadena.='</table><div class="text-right"><a href="consultaFunerariaPDF.php" class="btn btn-warning" target="_blank"><i class="fa fa-download"></i> Ver Planilla</a></div>
';
  
    echo $cadena;


    }


 ?>



</fieldset>
   

