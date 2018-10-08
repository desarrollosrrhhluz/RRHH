<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once('app_class/DataStore.class.php');
include_once('includes/Funciones.inc.php');
class MYPDF extends TCPDF {
public $cedula;
	//Page header
	public function Header() {
		$this->SetFont('helvetica', '', 9);
		 $this->Ln();
		
        $this->Image('images/logo_bn_luz_peq.jpg',14,10,15); 
		$this->SetY(10);
		$this->SetX(30);
		$this->Cell(120,5,"REPÚBLICA BOLIVARIANA DE VENEZUELA",0,0,'L');$this->Cell(40,5,'Pagina '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(),0,1,'L');
		$this->SetX(30);
        $this->Cell(70,5,'UNIVERSIDAD DEL ZULIA',0,0,'L');
		$this->Cell(50,5,'',0,0,'L');
		$this->Cell(40,5,date('d-m-Y'),0,1,'L');
        $this->SetX(30);
		//$this->SetFont('helvetica', '', 12);
		//$this->Ln(15);
        $this->Cell(120,5,'DIRECCIÓN DE RECURSOS HUMANOS',0,0,'L');
		$this->tip = $_GET['tip'];
		   if($this->tip=='1'){ $texto="Personal Docente";}
			   if($this->tip=='2'){ $texto="Personal Administrativo";}
			   if($this->tip=='3'){ $texto="Personal Obrero";}
			 $this->SetFont('helvetica', 'B', 9);
		$this->Cell(40,5,$texto,0,1,'L');
		$this->SetFont('helvetica', '', 9);
        $this->Ln(15);
		$this->Cell(170,5,'LISTADO DE DEDUCCIONES GENERALES AL MES DE:',0,1,'C');
			$this->SetFont('helvetica', 'B', 9);
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
		$this->ano = $_GET['ano'];
		$this->mes = $_GET['mes'];
		$this->Cell(170,5,''.strtoupper($a[$this->mes]).' de '.$this->ano.' ',0,1,'C');
		$this->SetFont('helvetica', 'B', 9);
		$this->cod = $_GET['cod'];
		
		$dbs='sidial2';
		$sql =  "select * from VW_CONCEPTO_BENEFICIARIO where CO_CONCEPTO='$this->cod'";
    $ds     = new DataStore($dbs, $sql);
    if($ds->getNumRows()<=0){
        
    } else {
		$i=0;
			 $fila=$ds->getValues($i);
			 $this->Ln(5);
			$this->Cell(170,5,$fila['CO_CONCEPTO'] .' '. $fila['DESCRIPCION'] ,0,1,'C'); 
			
	}
	$style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
	 $this->Ln(10);
	$this->Line(15, 70, 200, 70, $style2);	
	$this->Line(15, 76, 200, 76, $style2);
	$this->Cell(25,5,'CEDULA',0,0,'C'); 
	$this->Cell(110,5,'   APELLIDOS Y NOMBRES',0,0,'L'); 
	$this->Cell(25,5,'DEDUCCION',0,0,'L'); 
	$this->Cell(25,5,'SALDO',0,0,'L'); 
	}
	
	public function Footer() {
		$this->SetY(-15);
		$this->SetFont('helvetica', 'I', 8);
		//$this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
	}
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RRHH-TonnyMedina');
$pdf->SetTitle('Detalle Deducciones');
$pdf->SetSubject('Detalle Deducciones');
$pdf->SetKeywords('Detalle '.date('Y').'');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//$pdf->SetMargins(13,60, 13);
//$pdf->SetMargins(13,75, 13);
//$pdf->SetMargins(15, 75, 15);
$pdf->SetMargins(15,77, 15);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//$pdf->SetFooterMargin(10);
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak(TRUE, 20);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 
$dbs="sidial2";
$pdf->AddPage();
		$pdf->ano = $_GET['ano'];
		$pdf->mes = $_GET['mes'];
		$pdf->cod = $_GET['cod'];
		$pdf->tip = $_GET['tip'];
		
		
 		
	
		$sql =  "select *,(SELECT DISTINct NOMBRES FROM V_DATPER where CE_TRABAJADOR=VW_DETALLE_PAGO_DEDUCCIONES.CE_TRABAJADOR) as NOMBRES from VW_DETALLE_PAGO_DEDUCCIONES where CO_CONCEPTO='$pdf->cod' and ANO=$pdf->ano AND MES=$pdf->mes and IN_NOMINA='$pdf->tip' and  STATUS_DEDUCCION!=1 ";
        $dss     = new DataStore($dbs, $sql);
    	if($dss->getNumRows()==0){
           $pdf->SetFont('helvetica', '', 14);
            $pdf->Cell(180,10,'No hay datos para la consulta',0,0,'C');
        } else {
			$pdf->SetFont('helvetica', '', 8);
		$i=0;
		$j=$dss->getNumRows();
		$suma=0;
		$saldo=0;
		while($i < $j){
			 $pdf->SetX(18);
	$fila = $dss->getValues($i);
	$pdf->Cell(20,5,number_format($fila['CE_TRABAJADOR'],0,',','.'),0,0,'R'); 
	$pdf->Cell(105,5,"   ".$fila['NOMBRES'],0,0,'L'); 
	$pdf->Cell(20,5,number_format($fila['DEDUCCIONES'],2,',','.'),0,0,'R'); 
	$pdf->Cell(20,5,number_format($fila['MO_SALDO'],2,',','.'),0,1,'R'); 
	$suma=$suma+$fila['DEDUCCIONES'];
	$saldo=$saldo+$fila['MO_SALDO'];
	$i++;
			}
	 $pdf->SetX(18);
	$pdf->SetFont('helvetica', 'B', 8);
	$pdf->Cell(20,5,$i,0,0,'R'); 
	$pdf->SetFont('helvetica', '', 8);
	$pdf->Cell(105,5,'Total Concepto:',0,0,'R'); 
	$pdf->SetFont('helvetica', 'B', 8);
	$pdf->Cell(20,5,number_format($suma,2,',','.'),0,0,'R'); 
	$pdf->Cell(20,5,number_format($saldo,2,',','.'),0,1,'R'); 
		}
		$pdf->lastPage();

//$pdf->AddPage();






/*
$pdf->SetFont('helvetica', '', 10);

$y = $pdf->getY();
//$pdf->writeHTML(utf8_encode($html), true, 0, 0, 0,'J');
$pdf->writeHTMLCell(180, '', '', $y, $html2, 0, 0,2, true, 'J', true);

*/



		
		
       

////////////////////////////////////////////////////////////
$pdf->Output("detalleDeducciones.pdf",'I');


?>
