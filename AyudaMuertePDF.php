<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once('includes/Funciones.inc.php');
session_start();
class MYPDF extends TCPDF {
public $cedula;
	//Page header
	public function Header() {
		$this->SetFont('helvetica', '', 12);
        $this->Cell(180,7,utf8_encode("REPÚBLICA BOLIVARIANA DE VENEZUELA"),0,1,'L');
		$this->SetFont('helvetica', 'B', 12);
        $this->Cell(80,7,'UNIVERSIDAD DEL ZULIA',0,1,'C');
        $this->Image('images/logo_bn_luz_peq.jpg',45,22,20);
		$this->SetFont('helvetica', '', 12);
		$this->Ln(28);
        $this->Cell(180,7,utf8_encode('DIRECCIÓN DE RECURSOS HUMANOS'),0,1,'L');
        $this->Ln();
	}
	
	public function Footer() {
	$this->SetY(-20);
		$this->SetFont('helvetica', '', 7);
		
		
		$estilo=array('width' => 0.15, 'cap' => 'square', 'join' => 'miter', 'dash' => 0);
		$this->Line(15, 273,190, 273, $estilo);
		$this->Cell(170,3,utf8_encode('Av. 16 (Guajira) Esquina Calle 66 - Edificio Antigua Sede Rectorado Planta Alta. Telfs (0261) 4124504'),0,1,'C');
		$this->Cell(170,3,utf8_encode('Maracaibo Edo Zulia. Página WEB http://www.rrhh.luz.edu.ve E-mail: direccion@rrhh.luz.edu.ve'),0,1,'C');
		$this->Cell(170,3,utf8_encode('AL CONTESTAR SE AGRADECE HACER REFERENCIA A ESTA COMUNICACIÓN'),0,1,'C');
	}
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RRHH-Joseph-Fernandez');
$pdf->SetTitle('Ayuda Nacimiento 2013');
$pdf->SetSubject('Ayuda');
$pdf->SetKeywords('Ayuda Nacimiento 2013');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15,55, 15);
//$pdf->SetMargins(15, 70, 15);
//$pdf->SetMargins(13, 65, 13);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//$pdf->SetFooterMargin(10);
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 



$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(180,5,'Maracaibo, '.fecha_oficio().'',0,1,'R');
$pdf->Ln();
$pdf->Cell(80,5,utf8_encode('Dra Ixora Gómez Salazar'),0,1,'L');
$pdf->Cell(80,5,'Directora de Recursos Humanos',0,1,'L');
$pdf->Cell(80,5,'Su Despacho',0,1,'L');

$db="desarrolloRRHH";

extract($_GET);

$nombretrabajador=$_SESSION['nombres'];

$sql2 ="SELECT *
FROM 
public.mst_familiares_beneficios where id_familiar=$id";
  
   $ds = new DataStore($db, $sql2);
   $fila = $ds->getValues(0);
	
	
	
$html='<div align="justify"> 
<br><br>Por medio de la presente informo el fallecimiento de mi hijo '.strtoupper(trim($fila['nombres'])).' cuya fecha de fallecimiento fue el dia '.$fila['fe_muerte'].' numero de acta de defunción '.$fila['acta_defuncion'].', por la cual solicito me conceda el beneficio de bonificación de conformidad a lo establecido en la PRIMERA CONVENCIÓN COLECTIVA ÚNICA DEL SECTOR UNIVERSITARIO.
<br><br>
Sin más a que hacer referencia,
<br><br>
<br><br>

<div align="center">
Atentamente

<br><br>
___________________________________
<br><br>

 '.ucwords( trim($nombretrabajador)).'
<br><br>
C.I '.$fila['ce_trabajador'].'
</div>
</div>';
$pdf->SetFont('helvetica', '', 11);
$pdf->writeHTMLCell  (180, 0, 14, 100, utf8_encode($html), 0, 0, 0, true); 
$pdf->setPrintFooter(true);
$pdf->Output("HCM_".$cedula."_".date("Y-m-d").".pdf",'I');
?>
