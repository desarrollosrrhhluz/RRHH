<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include('includes/Funciones.inc.php');

date_default_timezone_set("America/Caracas");
session_start();

$id = $_REQUEST['id'];
	
$dbr="rrhh";
$db2="sidial";	

global $dbs,$dbr,$SumaCARDINALES,$SumaAREA,$TotalGENERAL;



	class MYPDF extends TCPDF {
	//Page header
	public function Header() {
 		$this->Cell(0,7,utf8_encode('Fecha: '.date('d-m-y h:i:s A').''),0,1,'R');
		$this->Cell(100,7,utf8_encode("REPÚBLICA BOLIVARIANA DE VENEZUELA"),0,1,'L');
		$this->SetFont('helvetica', 'B', 12);
        $this->Cell(80,7,'UNIVERSIDAD DEL ZULIA',0,1,'C');
        $this->Image('images/logo_bn_luz_peq.jpg',53,25,15);
		

		$this->SetFont('helvetica', '', 12);
		$this->Ln(20);
        $this->Cell(100,7,utf8_encode('DIRECCIÓN DE RECURSOS HUMANOS'),0,1,'L');
        $this->Cell(0,7,utf8_encode('EVALUACIÓN DE DESEMPEÑO'),0,1,'C');
	
	}
	
	public function Footer() {
		$this->SetY(-15);
		$this->SetFont('helvetica', '', 10);
	
$this->Cell(0,10,'Page '.$this->PageNo().'',0,0,'C');
	}
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetTitle('Forma ED');
$pdf->SetSubject('14-02');
$pdf->SetKeywords('14-02');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(20,60, 20);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 
		//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();

$PonderaIten='
<br/>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td  bgcolor="#bbbbbb"><b>Alternativas</b></td>
    <td  bgcolor="#bbbbbb"><b>Límites</b></td>
    <td  bgcolor="#bbbbbb"><b>Categoría</b></td>
  </tr>
  <tr>
    <td>Siempre</td>
    <td align="center">4.21 > <b>TC</b> < 5.00</td>
    <td>Muy Alto Dominio</td>
  </tr>
  <tr>
    <td>Casi siempre</td>
    <td align="center">3.41 > <b>TC</b> < 4.20</td>
    <td>Alto Dominio</td>
  </tr>
  <tr>
    <td>Algunas Veces</td>
    <td align="center">2.61 > <b>TC</b> < 3.40</td>
    <td>Moderado Dominio</td>
  </tr>
  <tr>
    <td>Rara Vez</td>
    <td align="center">1.81 > <b>TC</b> < 2.60</td>
    <td>Bajo Dominio</td>
  </tr>
  <tr>
    <td>Nunca</td>
    <td align="center">1.00 > <b>TC</b> < 1.80</td>
    <td>Muy Bajo Dominio</td>
  </tr>
</table>';


 $pdf->writeHTML(utf8_encode($PonderaIten), true, false, true, false, '');	


$sql ="SELECT 
e.id_compete_descripcion,e.titulo, sum(c.valor)
FROM 
  tra_eval_resultados_evaluacion as a
  INNER JOIN tra_desempeno_evaluacion AS b ON (a.id_desempeno= b.id_desempeno) 
  INNER JOIN def_ponderacion AS c ON (a.id_ponderacion= c.id_ponderacion) 
  INNER JOIN tab_comportamiento_evaluacion AS d ON (a.id_comportamiento= d.id_comportamiento)
  INNER JOIN def_compete_descrip_evaluacion AS e ON (d.id_competencia_descri= e.id_compete_descripcion)
  where b.cedula=".$_SESSION['ce_trabajadorevaluado']." and e.id_competencia=".$_SESSION['id_competencia1']."
  GROUP BY e.id_compete_descripcion,e.titulo
  order by e.id_compete_descripcion asc";
	
$ds = new DataStore($dbr, $sql);	

$sql2 ="SELECT 
e.id_compete_descripcion,e.titulo, sum(c.valor)
FROM 
  tra_eval_resultados_evaluacion as a
  INNER JOIN tra_desempeno_evaluacion AS b ON (a.id_desempeno= b.id_desempeno) 
  INNER JOIN def_ponderacion AS c ON (a.id_ponderacion= c.id_ponderacion) 
  INNER JOIN tab_comportamiento_evaluacion AS d ON (a.id_comportamiento= d.id_comportamiento)
  INNER JOIN def_compete_descrip_evaluacion AS e ON (d.id_competencia_descri= e.id_compete_descripcion)
  where b.cedula=".$_SESSION['ce_trabajadorevaluado']." and e.id_competencia=".$_SESSION['id_competencia2']."
  GROUP BY e.id_compete_descripcion,e.titulo
  order by e.id_compete_descripcion asc";
	
$ds2 = new DataStore($dbr, $sql2);	

//***********************************************************/
	$html='<table width="100%" border="1" cellspacing="0" cellpadding="0">
	  <tr>
    <td width="40%" bgcolor="#bbbbbb" ><b>COMPETENCIAS CARDINALES</b></td>
	<td width="10%" bgcolor="#bbbbbb" ><b>TC</b></td>
    <td width="40%" bgcolor="#bbbbbb" ><b>COMPETENCIAS DE AREA</b></td>
 	<td width="10%" bgcolor="#bbbbbb" ><b>TC</b></td>
  </tr>
	';
	
	$i=0;
	$rows=$ds->getNumRows();


	
	while($rows>$i){
	
	$fila = $ds->getValues($i);
	$fila2 = $ds2->getValues($i);
	
	$html.='<tr>
    <td width="40%">'.($fila['titulo']).'</td>
	 <td width="10%">'.($fila['sum']/4).'</td>
     <td width="40%">'.($fila2['titulo']).'</td>
	 <td width="10%">'.(($fila2['sum']/4) ? ($fila2['sum']/4) : "").'</td>
  </tr>	'; 
  
$SumaCARDINALES=$SumaCARDINALES+$fila['sum'];

$SumaAREA=$SumaAREA+$fila2['sum'];

  $i++;
}

$SumaAREA=$SumaAREA+20;

$TotalGENERAL= number_format(($SumaCARDINALES+$SumaAREA)/2);
$html.='</table>';

	//**********************************************************
	
	//$pdf->writeHTMLCell(100, 0, 0, 100, utf8_encode($html), 0, 0, 0, true); 
	
	 $pdf->writeHTML(utf8_encode($html), true, false, true, false, '');	
	/************************************************************************/

		if($TotalGENERAL>=91 && $TotalGENERAL <= 100){
			
		$categoriaoptenida="SOBRESALIENTE";
			
		}else if($TotalGENERAL >= 81 && $TotalGENERAL <= 90 ){
			
		$categoriaoptenida="EXCELENTE";
			
		}else if($TotalGENERAL >= 51 && $TotalGENERAL <= 80 ){
			
		$categoriaoptenida="SATISFACTORIO";
			
		}else if($TotalGENERAL >= 21 && $TotalGENERAL <= 50 ){
			
		$categoriaoptenida="ACORDE CON LAS EXPECTATIVAS";
			
		}else if($TotalGENERAL >= 0 && $TotalGENERAL <= 20 ){
		
		$categoriaoptenida="DESACORDE CON LAS EXPECTATIVAS";	
			
		}



$Tatalizar ='<table  width="100%" border="1" cellspacing="0" cellpadding="0">

<tr>
    <td bgcolor="#bbbbbb" width="50%"><b>TOTAL COMPETENCIAS CARDINALES:</b></td>
	  <td width="50%">'.$SumaCARDINALES.'</td>
  </tr>	
  <tr>
   <td bgcolor="#bbbbbb" width="50%"><b>TOTAL COMPETENCIAS DE AREA:</b></td>
	<td width="50%">'.$SumaAREA.'</td>
  </tr>	
  <tr>
     <td bgcolor="#bbbbbb" width="50%"><b>TOTAL GLOBAL:</b></td>
	 <td width="50%">'.$TotalGENERAL.'</td>
  </tr>	
   <tr>
     <td bgcolor="#bbbbbb" width="50%"><b>INTERPRETACIÓN DE LOS RESULTADOS PARA LA APLICACIÓN EN EL BAREMO DE CONCURSOS:</b></td>
	 <td width="50%">'.$categoriaoptenida.'</td>
  </tr>

</table>' ;	 


$pdf->writeHTML(utf8_encode($Tatalizar), true, false, true, false, '');	

				//***********************************************************/
	$html1 ='<table  width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="40%" bgcolor="#bbbbbb"><strong>OBSERVACIONES EVALUADOR</strong></td>
    <td width="60%">'.$_REQUEST['observacionevaluador'].'</td>
   
  </tr>
  <tr>
    <td width="40%"  bgcolor="#bbbbbb" ><strong>OPORTUNIDADES DE MEJORA</strong></td>
    <td width="60%">'.$_REQUEST['oportunidadmejora'].'</td>
   
  </tr>
  <tr >
    <td width="40%"  bgcolor="#bbbbbb"><strong>ACCIONES</strong></td>
    <td width="60%">'.$_REQUEST['accioneseval'].'</td>
  
  </tr>
    <tr >
    <td width="40%"  bgcolor="#bbbbbb"><strong>RECOMENDACIONES GLOBALES</strong></td>
    <td width="60%">'.$_REQUEST['recomendacionGlobal'].'</td>
  </tr>
  
   <tr>
    <td width="40%"  bgcolor="#bbbbbb"><strong>'.utf8_encode('PRÓXIMA EVALUACIÓN').'</strong></td>
    <td width="60%">'.$_REQUEST['proximaevaluacionstatus'].'</td>
  </tr>
 
</table>';

 
 	$html1 .= '
	
         <table width="100%" border="1" cellspacing="0" cellpadding="0">
                <tr bgcolor="#bbbbbb" >
				<th width="20%" ><b></b></th>
				<th width="50%" ><b>NOMBRE Y APELLIDO</b></th>
                <th width="15%" ><b>FIRMA</b></th>
				<th width="15%" ><b>FECHA</b></th>
                    </tr>
';
 

												   
	$sql =  "select *,'1' as orden from V_DATPER where CE_TRABAJADOR = ".$_SESSION['ce_trabajadorjefe']." UNION  select *,'2' as orden  from V_DATPER where CE_TRABAJADOR =".$_SESSION['ce_trabajadorevaluado']." order by orden desc";
	
	$ds     = new DataStore($db2, $sql);
	
	$dev = $ds->getValues(0);
     $dev1 = $ds->getValues(1);
												   
												   
		   $html1 .= '<tr>
		   	<td width="20%">EVALUADO</td>
		   <td width="50%">'.utf8_encode($dev['NOMBRES']).'</td>
		<td width="15%"></td>
		<td width="15%">'.date("d-m-Y").'</td>
	
		</tr>
		
		<tr>
		
		<td width="20%">EVALUADOR</td>
		<td width="50%">'.utf8_encode($dev1['NOMBRES']).'</td>
		<td width="15%"></td>
		<td width="15%">'.date("d-m-Y").'</td>
	
		</tr>
		</table>
		' ;	 

 //$pdf->Ln(45);
$pdf->SetFont('helvetica', '', 10);

	 $pdf->writeHTML($html1, true, false, true, false, '');	

//$pdf->writeHTMLCell  (185, 250, 16, 50, utf8_encode($html1), 0, 0, 0, true); 
       
/*************************************************************************/

	 $fe_letras = fecha_texto(date("Y-m-d"));
	$pdf->setPrintFooter(true);

//*******************************************************************************
$pdf->Output("Evaluacion.pdf",'I');

?>