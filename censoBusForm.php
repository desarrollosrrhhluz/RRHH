<?php 

	header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
	header( "Cache-Control: post-check=0, pre-check=0", false );
	header( "Pragma: no-cache" );
	
	error_reporting(1);
	session_start();

	include_once('./includes/verificaRoles.php');
	include_once("../FrameWork/Include/Defines.inc.php");

	$appName = 'CENSOBUS';
	$urlDeshabilitado = "finalizadoLoad.html";

	/** Se verifica si el censo esta activo */
    if(strcmp($appName,"")!=0){
        $sql = "SELECT flg_activo FROM def_aplicacion where co_aplicacion= '$appName'";
	$ds = new DataStore('system',$sql);
   	if($ds->getNumRows()>0) {
            $fila = $ds->getValueCol(0,"flg_activo");
            if(strcmp($fila,"A")!=0){
                header("Location: ".$urlDeshabilitado);
                exit;
            }
        } else {
            header("Location: ".$urlDeshabilitado);
            exit;
        }
    }

	$ced=$_SESSION["cedula"];
	$db="desarrolloRRHH";
	$dbs="sidial";
	$dbr="rrhh";

	$sql = "SELECT *FROM public.tab_censo_bus WHERE ce_trabajador = $ced;";
	$ds  = new DataStore($dbr, $sql);

    $decision = "";
	
	if($ds->getNumRows() > 0)
	{	
		$row = $ds->getValues(0);
		$decision = $row['decision'];
	}
?>

<script language="javascript" src="js/censoBus.js"></script>

<fieldset>

	<h2>Censo de La Bolsa Universitaria Solidaria (BUS)</h2></legend> 

	<form action="censoBusAdm.php" method="post" name="form_bus" id="form_bus">
		<p>
			Acepto que, LUZ me descuente del Beneficio Bono de Alimentación la cantidad de Bs. 300.000,00 Bsf  para el pago de la  BUS.
			<br> Seleccione una opción y haga clic sobre el botón con el texto "Guardar"
		</p>

		<div class="row" >
			<div class="col-md-3">
				<div class="form-group">
				<label>Decisión</label>
					<select name="decisionUsr" id="decisionUsr" class="form-control" required>
					<option value="">Seleccione</option>
					<option value="S" <?php if($decision == 'S'){ echo "selected='selected'"; } ?> >Si</option>
					<option value="N" <?php if($decision == 'N'){ echo "selected='selected'"; } ?> >No</option>
					</select>
				</div>
			</div>
		</div>

		<input type="hidden" name="op" value="update_bus" /><input type="submit" name="guardar" value="Guardar" class="btn btn-success" /> 
	</form>

</fieldset>