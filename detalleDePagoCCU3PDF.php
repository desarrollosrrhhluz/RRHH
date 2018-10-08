<?php

include_once('../FrameWork/Include/Defines.inc.php');
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios


require(LUZ_PATH_3PARTY.'/fpdf/fpdf.php');
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
//include_once(LUZ_PATH_CLASS.'/CodigoBarras.class.php');
include_once(LUZ_PATH_INCLUDE.'/Funciones.inc.php');


class PDF extends FPDF {

    public $cedula;
    public $ano;
    public $mes;
    public $primera;
    public $segunda;
    public $paralela;
    public $id;
    public $fe_solicitud;


    function Header() {
        $db     = "sidial";
        $sql    = "SELECT * FROM VW_CABECERA_HISTORICO WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND MES = $this->mes";
        $ds     = new DataStore($db, $sql);
        $fila = $ds->getValues(0);
        $this->Image('images/logo_bn_luz.jpg',25,8,20);
        $this->SetFont('Arial','B',11);
        $this->Cell(70);
        $this->Cell(30,5,'Universidad del Zulia',0,0,'C');
        $this->Ln();
        $this->Cell(70);
        $this->Cell(30,5,utf8_decode('Rectoría'),0,0,'C');
        $this->Ln();
        $this->Cell(70);
        $this->Cell(30,5,utf8_decode('Dirección de Recursos Humanos'),0,0,'C');
        $this->Ln();
        $this->Cell(70);
        $this->Cell(30,5,utf8_decode('Proceso de Nómina'),0,0,'C');
        $this->Ln();
        $this->Cell(70);
        $this->Cell(30,5,utf8_decode('Dirección de Tecnologías de Información y Comunicación'),0,0,'C');
        $this->Ln(10);
        $this->SetFont('Arial','B',8);
        $this->Cell(18,5,utf8_decode('Cédula'),1,0,'C',true);
        $this->Cell(77,5,'Beneficiario',1,0,'C',true);
        $this->Cell(17,5,utf8_decode('Ubicación'),1,0,'C',true);
        $this->Cell(25,5,'Tipo de personal',1,0,'C',true);
        $this->Cell(23,5,'Fecha',1,0,'C',true);
        $this->Cell(15,5,utf8_decode('Página'),1,0,'C',true);
        $this->Ln();
        $this->Cell(18,5,number_format($fila["CE_TRABAJADOR"],0,',','.'),1,0,'C');
        $this->SetFont('Arial','B',6);
        $this->Cell(77,5,trim($fila["NOMBRES"]),1,0,'L');
        $this->SetFont('Arial','B',8);
        $this->Cell(17,5,substr($fila["UBICACION"],0,5),1,0,'C');
        $this->Cell(25,5,substr($fila["TIPPER"],0,6),0,0,'C');
        $this->Cell(23,5, de_mes($this->mes) ." ".$this->ano,1,0,'C');
        $this->Cell(15,5,$this->PageNo().'/{nb}',1,0,'C');
        $this->Ln();
        $this->Cell(85,5,'Conceptos',1,0,'C',true);
        $this->Cell(30,5,'Asignaciones',1,0,'C',true);
        $this->Cell(30,5,'Deducciones',1,0,'C',true);
        $this->Cell(30,5,'Saldo',1,0,'C',true);
        $this->Ln();
        $this->SetTitle($fila["NOMBRES"]);
    }

    function Detail(){
        $db     = "sidial";
        $sql = "select * from VW_HIS_DETALLE_CCUIII where CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND MES = $this->mes";        
        
        $ds     = new DataStore($db, $sql);
    	if($ds->getNumRows()==0){
            $this->SetFont('Arial','B',14);
            $this->Cell(80,10,'No hay datos para la consulta',0,0,'C');
        } else {
            $this->SetFont('Arial','',8);
            $this->primera = 0;
            $this->segunda = 0;
            $this->paralela = 0;
            for($i=0 ; $i < $ds->getNumRows() ; $i++){
                $fila = $ds->getValues($i);
                $this->Cell(80,4,$fila["DESCRIPCION"],0,0,'L');
                
                $monto = ($fila["MO_CONCEPTO"]==0) ? $monto = "" : number_format($fila["MO_CONCEPTO"],2,',','.');
                $this->Cell(30,4,$monto,0,0,'R');
                $total_asig += $fila["MO_CONCEPTO"];
                
                $monto = ($fila["MO_SALDO"]==0) ? $monto = "" : number_format($fila["MO_SALDO"],2,',','.');
                $this->Cell(30,4,$monto,0,0,'R');

                $this->Ln();
            }
            $this->SetFont('Arial','B',8);
            $this->Cell(85,5,'TOTAL DE ASIGNACIONES',0,0,'L');
            $this->Cell(30,5,number_format($total_asig,2,',','.'),0,0,'R');
        }
    }

    function Footer() {
        $this->SetY(-65);
        $this->SetFont('Arial','B',7);
        $this->SetFont('Arial','I',8);
        $this->Cell(180,3,('La información mostrada en este reporte es personal.'),0,1,'C');
        $this->Cell(180,3,'Procesado por Diticluz',0,1,'R');
    }
    
}

//Creaci�n del objeto de la clase heredada
$pdf=new PDF('P','mm','letter');

$nid = getVar("cid");

if(strcmp($nid,"")!=0) {
	$pdf->cedula = $nid;
	$pdf->id     = 0;
} else {
	session_start();
	$pdf->cedula = $_SESSION["cedula"];
	$pdf->id     = $_SESSION["id_user"];
}
$pdf->ano = getVar("ano");
$pdf->mes = getVar("mes");
$pdf->SetCreator("aplicacionWebLUZ");
$pdf->SetAuthor("DiTICLUZ - oamesty");
$pdf->SetSubject("Detalle de Pago CCUIII");
$pdf->SetLeftMargin(20);
$pdf->SetFillColor(220);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Detail();
$pdf->Output();


