<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once('includes/Funciones.inc.php');
error_reporting(0);
session_start();

class MYPDF extends TCPDF {
	
    public $cedula;

	public function Header() {
		
		$this->Image('images/encabezado_aniversario.jpg',15,-5,100);
        $this->Ln(35);
		$this->SetFont('helvetica', 'B',10);
	
	}
	
	public function Footer() {
		
		$this->SetY(-28);
		$this->SetFont('helvetica', '', 8);
		$this->Cell(170,3,utf8_encode('LP/AP/RN'),0,1,'L');
		$this->Image('images/pie_aniversario.jpg',10,265,190);

	}
	
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RRHH-Joseph-Fernandez');
$pdf->SetTitle('Calculo ARI 2015');
$pdf->SetSubject('ARI');
$pdf->SetKeywords('Calculo ARI 2015');


$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);


$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

extract($_GET);



$nombretrabajador=$_SESSION['nombres'];

$cedula=$_SESSION['cedula'];
$sexo=$_SESSION['sexo'];
$_SESSION['tipopersonal'];
$tipop=$_SESSION['tipopersonal'];
$anovariacion=$_SESSION['ano_proceso'];
$mesvariacion=$_SESSION['mes_proceso'];
$op= $_REQUEST['op'];
$ce_trabajador=$_SESSION["cedula"];
$nombre_trabajador=$_SESSION["nombres"];
$co_ubicacion=$_SESSION['coubicacion'];


$a[1] = "Enero"; 
$a[2] = "Febrero"; 
$a[3] = "Marzo"; 
$a[4] = "Abril"; 
$a[5] = "Mayo"; 
$a[6] = "Junio"; 
$a[7] = "Julio"; 
$a[8] = "Agosto"; 
$a[9] = "Septiembre"; 
$a[10] = "Octubre"; 
$a[11] = "Noviembre";
$a[12] = "Diciembre";

if(empty($ano)){
  $ano=$anovariacion;
}

if(empty($mes)){
  $mes=$mesvariacion;
}

$html='


<br/>

<div align="center"><h1>ESTIMACIÓN DE INGRESOS ANUALES (ARI)</h1></div>
<br/>

';
$empresa_aux = explode("-", $empresa);
$montos_aux = explode("-", $montos);

$html_aux='<tr>
      <td  width="50%"><b>EMPRESA U ORGANIZACIÓN</b></td>
	  <td width="50%"> <b>MONTO ANUAL</b></td>
  </tr>	';



for($i=0;$i<count($empresa_aux)-1;$i++)
{
    
	$html_aux.='<tr>
      <td  width="50%"><b>'.utf8_decode($empresa_aux[$i]).'</b></td>
	  <td width="50%"> '.$montos_aux[$i].'</td>
  </tr>	';
	
	$TotalMontosEmpreza=$TotalMontosEmpreza+$montos_aux[$i];
}



$html_aux.='<tr>
      <td  width="50%" ><b>TOTAL</b></td>
	  <td width="50%" > '.$TotalMontosEmpreza.' </td>
  </tr>	';




$html.='
<table  width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td  width="50%"><b>NOMBRE</b></td>
	  <td width="50%"> '.$nombre_trabajador.'</td>
  </tr>	
  <tr>
  
   <td  width="50%"><b>CEDULA</b></td>
	<td width="50%"> '.$cedula.'</td>
  </tr>	
  
</table>
<br/>


';



$html.='

<div align="center"><h4>REMUNERACIONES QUE ESTIMA PERCIBIR EN EL AÑO GRAVABLE</h4></div>
<br/>

';


$html.='

<table  width="100%" border="1" cellspacing="0" cellpadding="0">
  
 

'.$html_aux.'

   
  
</table>
<br/>


';


$html.='

<table  width="100%" border="1" cellspacing="0" cellpadding="0">
 <tr>
     <td  width="50%"><b>VALOR DE LA UNIDAD TRIBUTARIA</b></td>
	 <td width="50%"> '.$valorUT.'</td>
  </tr>	
 <tr>
     <td  width="50%"><b>DESGRAVAMEN</b></td>
	 <td width="50%"> '.$desgravamen.'</td>
  </tr>	
 
  <tr>
     <td  width="50%"><b>CARGA FAMILIAR</b></td>
	 <td width="50%"> '.$cargafamiliarimput.'</td>
  </tr>	

   <tr>
     <td  width="50%"><b>IMPUESTOS RETENIDOS DE MAS EN AÑOS ANTERIORES</b></td>
	 <td width="50%"> '.$impuestoretenidosimput.'</td>
  </tr>	

 
  
   <tr>
     <td  width="50%"><b>MONTO A RETENER (PORCENTUAL)</b></td>
	 <td width="50%"> '.$totalmensualretenerinput.'</td>
  </tr>	
   <tr>
     <td  width="50%"><b>FECHA PROCESO</b></td>
	 <td width="50%"> '.date("d/m/Y", strtotime($fecha)).'</td>
  </tr>
  <tr>
     <td  width="50%"><b> MES Y AÑO DE VARIACION</b></td>
	 <td width="50%"> '.$a[$mes].' - '.$ano.'</td>
  </tr>	
  
 

</table>
<br/>

<b>Fecha de Impresion:</b> '.date("d/m/Y H:m:s  a").'


';





$pdf->SetFont('helvetica', '', 11);
$pdf->writeHTMLCell (180, 0, 14, 35, utf8_encode($html), 0, 0, 0, true); 




		$pdf->SetXY(85,200);
	$pdf->SetFont('helvetica', 'I', 7);
	$style = array(
    'position' => 'S',
    'border' => false,
    'padding' => 4,
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, 
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 7,
    'stretchtext' => 6
);
	
		$pdf->write1DBarcode(str_repeat("0",10-strlen($cedula)).$cedula, 'C128B', '', '', 30, 20, 0.4, $style, 'N');
		
		
		
		
$pdf->setPrintFooter(true);
$pdf->Output("ARI_".$cedula."_".date("Y-m-d").".pdf",'D');


?>
