<?php
require('../3party/fpdf/fpdf.php');
include_once('../FrameWork/Class/DataStore.class.php');
include_once('../FrameWork/Class/CodigoBarras.class.php');
include_once('../FrameWork/Include/Funciones.inc.php');


class PDF extends FPDF {
    public $cedula;

    function Header() {
        $this->Image('images/logo_bn_luz.jpg',25,8,20);
        $this->SetFont('Arial','B',10);
        $this->Cell(70);
        $this->Cell(30,5,'Universidad del Zulia',0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,'Direcci'.utf8_decode('贸').'n de Recursos Humanos',0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,'Proceso Egreso - Subproceso de Prestaciones Sociales e Intereses.',0,1,'C');
        $this->Cell(70);
       // $this->Cell(30,5,'Direcci'.utf8_decode('贸').'n de Tecnolog'.utf8_decode('铆').'as de Informaci'.utf8_decode('贸').'n y Comunicaci'.utf8_decode('贸').'n',0,1,'C');
       // $this->Cell(70);
        $this->Cell(30,5,'Estimado de Prestaciones Sociales',0,1,'C');
        $this->Ln();
    }

    function Detail(){
        $db     = "sidial";
        $sql    = "SELECT *, convert(char(12), FE_INGRESO, 103) as fecha FROM VW_CABECERA_ACTUAL WHERE CE_TRABAJADOR = $this->cedula";
        $ds0     = new DataStore($db, $sql);
    	if($ds0->getNumRows()==0){
            $this->SetFont('Arial','B',14);
            $this->Cell(80,10,'No hay datos para la consulta',0,0,'C');
        } else {
			$fila = $ds0->getValues(0);
			$this->SetFont('Arial','',10);
			$this->Cell(40,5,'Beneficiario',1,0,'C');
			$this->Cell(100,5,$fila["NOMBRES"],1,0,'L');
			$this->Cell(20,5,'C'.utf8_decode('茅').'dula',1,0,'CL');
			$this->Cell(20,5,number_format($fila["CE_TRABAJADOR"],0,',','.'),1,1,'L');
			$this->Cell(40,5,'Dependencia',1,0,'C');
			$this->Cell(140,5,$fila["UBICACION"],1,1,'L');
			$this->Cell(40,5,'Tipo de personal',1,0,'C');
			$this->Cell(140,5,$fila["TIPPER"],1,1,'L');
			$this->Ln();

			$this->SetFont('Arial','B',12);
			$this->Cell(180,5,'Detalle de calculo',0,1,'C');

			$this->SetFont('Arial','',10);
            $this->Cell(50,5);
			$this->Cell(40,5,'Fecha de Ingreso LUZ',1,0,'C');
			$this->Cell(30,5,$fila["fecha"],1,1,'R');
            $this->Cell(50,5);
			$this->Cell(40,5,'Fecha de Estimaci'.utf8_decode('贸').'n',1,0,'C');
			$this->Cell(30,5,date('d/m/Y'),1,1,'R');
			$this->SetTitle($fila["NOMBRES"]);
			$db     = "sidial";
			$sql    =  "exec ESTIMADO_PS $this->cedula";
	//        $sql    =  "select estimado_ps($this->cedula) as monto from dummy";
	//        $sql    =  "select *,estimado_ps(7715570,0,0,0,0,0,0) as monto from vw_prestaciones where ce_trabajador = 7715570";
			$ds     = new DataStore($db);
			$ds->executesql($sql);
			if($ds->getNumRows()==0){
				$this->SetFont('Arial','B',14);
				$this->Cell(80,10,'No hay datos para la consulta',0,0,'C');
			} else {
				$this->Cell(50,5);
				$this->Cell(40,5,'A駉s de servicio',1,0,'C');
				$this->Cell(30,5,$ds->getValueCol(0, 7),1,1,'R');
				$this->Cell(50,5);
				$this->Cell(40,5,'Salario integral',1,0,'C');
				$this->Cell(30,5,number_format($ds->getValueCol(0, 0),2,',','.'),1,1,'R');
				$this->Cell(50,5);
				$this->Cell(40,5,'Salario diario',1,0,'C');
				$this->Cell(30,5,number_format($ds->getValueCol(0, 1),2,',','.'),1,1,'R');
				$this->Cell(50,5);
				$this->Cell(40,5,'Antiguedad (dias)',1,0,'C');
				$this->Cell(30,5,number_format($ds->getValueCol(0, 2),2,',','.'),1,1,'R');
				$this->Cell(50,5);
				$this->Cell(40,5,'Estimado bruto',1,0,'C');
				$this->Cell(30,5,number_format($ds->getValueCol(0, 3),2,',','.'),1,1,'R');
				$this->Cell(50,5);
				$this->Cell(40,5,'Total anticipos',1,0,'C');
				$this->Cell(30,5,number_format($ds->getValueCol(0, 4),2,',','.'),1,1,'R');
				$this->Cell(50,5);
				$this->SetFont('Arial','B',10);
				$this->Cell(40,5,'Total estimado',1,0,'C');
				$this->Cell(30,5,number_format($ds->getValueCol(0, 5),2,',','.'),1,1,'R');
			}
		}
    }
    function Footer() {
        $this->SetFont('Arial','I',10);
        //$this->SetY(-25);
        $this->SetY(120);
		$this->Ln();
        $this->Cell(60,3,'_____________',0,0,'C');
        $this->Cell(60,3,'_____________',0,0,'C');
        $this->Cell(60,3,'_______________',0,0,'C');
		$this->Ln();
		$this->Ln();
        $this->Cell(60,3,'Preparado por:',0,0,'C');
        $this->Cell(60,3,'Revisado por:',0,0,'C');
        $this->Cell(60,3,'Conformado por:',0,0,'C');
		$this->Ln();
		$this->Ln();
		$this->Ln();
        $this->SetFont('Arial','I',6);
        $this->Cell(180,3,'Procesado por Diticluz / SICAPS',0,1,'R');
    }
}

$pdf=new PDF('P','mm','letter');
$pdf->cedula = getVar("cid");
$pdf->SetCreator("aplicacionWebLUZ");
$pdf->SetAuthor("DiTICLUZ - oamesty");
$pdf->SetSubject("Constancia Estimado de Prestaciones Sociales");
$pdf->SetLeftMargin(20);
$pdf->SetFillColor(220);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Detail();
$pdf->Output();

?>
