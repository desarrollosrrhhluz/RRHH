<?php 
header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );
error_reporting(0);
session_start();
//include_once('app_class/DataStore.class.php');
 include_once('./includes/verificaRoles.php');
//codigo 4 obrero, 3 administrativo, 2 docente // cambiar tambien el sql el like por numero de tipo personal
verificaRoles(3);
$ced=$_SESSION["cedula"];
$db="desarrolloRRHH";
$dbs="sidial";
$dbr="rrhh";

$sqlverfica="select  CE_TRABAJADOR, TIPOPERSONAL, CO_UBICACION, NOMBRES, SEXO, convert( char(10),
 FE_NACIMIENTO,103 ) as FECHA_NACIMIENTO,
 (select DESCRIPCION_CORTA from V_UBICACION where CO_UBICACION=V_DATPER.CO_UBICACION ) 
  , convert( char(10), FE_INGRESO,103 ) as FECHA_INGRESO  from V_DATPER  
  WHERE  ESTATUS in ('A', 'C') and TIPOPERSONAL  LIKE '2%'   and ( SUBSTRING ( TIPOPERSONAL, 4 , 1 ) !='8'
   and SUBSTRING ( TIPOPERSONAL, 4 , 1 ) !='9'  and SUBSTRING ( TIPOPERSONAL, 4 , 1 ) !='4' ) 
    and CE_TRABAJADOR=$ced";

$dsx  = new DataStore($dbs, $sqlverfica);
if($dsx->getNumRows()<=0){
 echo '<fieldset><legend><h2><img src="images/shirt_polo.png"   /> Censo de Uniformes</h2></legend> 
<p>Usted no pertence al personal docente/administrativo activo, no puede acceder a esta aplicación.</p></fieldset>';
	exit;
		  }else{
		  	
		 $row = $dsx->getValues(0);
		 $_SESSION['SEXO']=$row['SEXO'];
		 $_SESSION['TIPOPERSONAL']=substr($row['TIPOPERSONAL'],0,1);
}



?>
<script language="javascript" src="js/censoUniformesAdm.js"></script>
 <fieldset>
 <h2><img src="images/shirt_polo.png"   /> Censo de Uniformes</h2></legend> 
 <form action="censoUniformesAdm.php" method="post" name="form_uniformes" id="form_uniformes">
<p>Ingrese sus tallas y haga clic sobre el boton con el texto "Guardar"</p>

<?php  
if($_SESSION['TIPOPERSONAL']== 1){
echo '<div class="row">
<div class="col-lg-3"><label for="laboratorio">Pers. de Laboratorio:</label></div>
<div class="col-lg-4">
<input type="checkbox" name="laboratorio" id="laboratorio"   />	
</div>	
</div>';
}
?>
<div class="row" >
<div class="col-md-3">
<div class="form-group">
<label><?php if($_SESSION['TIPOPERSONAL']== 3) echo"Chemise:"; else echo"Camisa:"; ?></label><select name="talla1" id="talla1" class="form-control" required>
<option value="">Seleccione</option>
<option value="SS">SS</option>
<option value="S">S</option>
<option value="M">M</option>
<option value="L">L</option>
<option value="XL">XL</option>
<option value="2XL">2XL</option>
<option value="3XL">3XL</option>
<option value="4XL">4XL</option>
<option value="5XL">5XL</option>
<option value="6XL">6XL</option>
</select></div></div>
</div>

<?php 

 if($_SESSION['TIPOPERSONAL']!= 3){
echo '<div class="row">
<div class="col-md-3">
<div class="form-group">
<label>Chaqueta:</label><select name="talla3" id="talla3" class="form-control" required>
<option value="">Seleccione</option>
<option value="SS">SS</option>
<option value="S">S</option>
<option value="M">M</option>
<option value="L">L</option>
<option value="XL">XL</option>
<option value="2XL">2XL</option>
<option value="3XL">3XL</option>
<option value="4XL">4XL</option>
<option value="5XL">5XL</option>
<option value="6XL">6XL</option>
</select></div>
</div>
</div>';
}

 if($_SESSION['TIPOPERSONAL']== 1){
echo '<div class="row" id="div_bata">
<div class="col-md-3">
<div class="form-group">
<label>Bata:</label><select name="talla4" id="talla4" class="form-control">
<option value="">Seleccione</option>
<option value="SS">SS</option>
<option value="S">S</option>
<option value="M">M</option>
<option value="L">L</option>
<option value="XL">XL</option>
<option value="2XL">2XL</option>
<option value="3XL">3XL</option>
<option value="4XL">4XL</option>
<option value="5XL">5XL</option>
<option value="6XL">6XL</option>
</select></div></div></div>';
	
	}  ?>


<div class="row">
<div class="col-md-3">
<div class="form-group">
<label>Pantalon:</label>
<select name="talla2" id="talla2" class="form-control" required>
<option value="">Seleccione</option>
<?php
if($_SESSION['SEXO']=='M'){
	$i=28;
	while( $i<=56){
	$cadena.= '<option value="'.$i.'">'.$i.'</option>';
	$i=$i+2;
	}
	echo $cadena;
	}else{
if($_SESSION['TIPOPERSONAL']!= 3 and $_SESSION['SEXO']=='F'){
echo '<option value="SS">SS</option>
<option value="S">S</option>
<option value="M">M</option>
<option value="L">L</option>
<option value="XL">XL</option>
<option value="2XL">2XL</option>
<option value="3XL">3XL</option>
<option value="4XL">4XL</option>
<option value="5XL">5XL</option>
<option value="6XL">6XL</option>';	
}else{
	$i=8;
	while( $i<=32){
	$cadena.= '<option value="'.$i.'">'.$i.'</option>';
	$i=$i+2;
	}
	echo $cadena;	
		}
	}

?>
</select></div></div></div>
<?php  
if($_SESSION['TIPOPERSONAL']== "3" ){
$cadena="";
if($_SESSION['SEXO']=='M'){
	for($i=37; $i<=50; $i++){
	$cadena.= '<option value="'.$i.'">'.$i.'</option>';
	}
	}else{
	for($i=35; $i<=42; $i++){
	$cadena.= '<option value="'.$i.'">'.$i.'</option>';
	}	
		
		}	
	
echo '<div class="row">
<div class="col-lg-3">
<div class="form-group">
<label>Zapatos:</label><select name="talla3" id="talla3" class="form-control" required>
<option value="">Seleccione</option>'.$cadena.'
</select></div></div></div><br />';
	
	
	}  ?>

</select></div></div><br/>
<input type="hidden" name="op" value="update_tallas" /><input type="submit" name="guardar" value="Guardar" class="btn btn-success" /> 
<button type='button' id='clic_planilla' class='clic_planilla btn btn-warning' title='Ver Planilla'><span class='glyphicon glyphicon-download-alt'></span> Ver Planilla</button>
</form>
 </fieldset>