<?php 

error_reporting(0);	 
session_start();
//include_once('./includes/verificaRoles.php');
//verificaRoles(1,2,3,4);
//echo $rif=strtoupper(str_replace("-",'',$_SESSION['rif']));
?>

<!--<script language="javascript" src="plugins/jquery-1.3.2.min.js"></script>
<script language="javascript" src="plugins/jquery.form.js"></script>
<script language="javascript" src="plugins/jquery.validate.min.js"></script>
<script language="javascript" src="plugins/messages_es.js"></script>
<script language="javascript"  src="plugins/jquery.notice.js"></script>
<script language="javascript"  src="plugins/jquery.boxy.js"></script>
<script language="javascript"  src="plugins/jquery.maskedinput-1.2.2.min.js"></script>
<script language="javascript" src="plugins/jquery.tools.min.js"></script>
<script language="javascript" src="plugins/jquery.limit-1.2.source.js"></script>-->
<script language="javascript" src="./js/consultaDeducciones.js"></script>

<style type="text/css">


div.error{
	background-color:#ffebe8;
	border:1px solid #dd3c10;
	padding:7px 3px;
	text-align: left;
	margin-top:10px;
	margin-left:15px;
	-moz-border-radius: 5px; -webkit-border-radius: 5px;	
}
div.destino{
	background-color: #DBF3FD;
	border:1px solid #3b5998;
	padding:7px 3px;
	text-align:left;
	margin-top:10px;
	margin-left:15px;
	width:90%;
	-moz-border-radius: 5px; -webkit-border-radius: 5px;
}
li{ margin-left:15px;}
label.error {
	color: #000000;
	position: relative;
	font-style: italic;
	text-align: left;
	float: none;
	display:inline;
	width:auto;
	margin-left:5px;	
}

</style>
</head>
<body>
 <fieldset>
 <legend><h2><img src="images/coins.png" width="32" height="32" />Consulta de Deducciones</h2></legend>
 <form action="consultaDeducciones.php" method="post" name="form_consulta" id="form_consulta">
<p>Seleccione valores para los parametros Tipo de Nomina, a&ntilde;o, mes y concepto or el cual desea consultar las deducciones realizadas a su favor</p>
<div class="row">
<div class="col-lg-3"><label class="control-label">Concepto:</label></div><div class="col-lg-3"><select name="conceptos" id="conceptos" class="form-control" required="required" ><option value="">-Seleccione-</option>
</select></div></div>
<div class="row">
<div class="col-lg-3"><label class="control-label">A&ntilde;o</label></div><div class="col-lg-3">
<select name="anno" id="anno" class="form-control" required="required"><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option></select></div></div>
<div class="row">
<div class="col-lg-3"><label class="control-label">Mes:</label></div><div class="col-lg-3"><select name="mes" id="mes" class="form-control" required="required"><option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option><option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option><option value="7">Julio</option><option value="8">Agosto</option><option value="9">Septiembre</option><option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option></select></div></div>
<div class="row">
<div class="col-lg-3"><label class="control-label">Tipo Nomina</label></div><div class="col-lg-3"><select name="op" id="op" class="form-control" required="required"><option value="ordinaria">Nomina Ordinaria</option><!--<option value="especial">Nomina Especial</option>--></select></div></div>
<!--<label>Nomina Especifica</label><select name="especifica" id="especifica" class="fs"></select><br />-->
<div align="center"><input type="submit" name="guardar" value="Consultar" class="btn btn-success" /></div>
</form>
 </fieldset>
<div id="destino" style="font-size:14px"></div>



