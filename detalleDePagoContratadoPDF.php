<?php
require('../3party/fpdf/fpdf.php');
include_once('../FrameWork/Class/DataStore.class.php');
include_once('../FrameWork/Class/CodigoBarras.class.php');
include_once('../FrameWork/Include/Funciones.inc.php');

class PDF extends FPDF {
    //Cabecera de página
    public $cedula;
    public $ano;
    public $mes;
    public $primera;
    public $segunda;
    public $id;

    var $angle=0;
    function Rotate($angle,$x=-1,$y=-1) {
        if($x==-1) $x=$this->x;
        if($y==-1) $y=$this->y;
        if($this->angle!=0) $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0) {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    }
    function _endpage() {
        if($this->angle!=0) {
            $this->angle=0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
    function RotatedText($x, $y, $txt, $angle) {
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

    function Header() {
        $db     = "sidial";
        $sql    = "SELECT distinct * FROM VW_CABECERA_HIS_ESPECIAL WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND TIPO_NOMINA = 50";// AND TIPO_NOMINA_ESPECIFICA = $this->mes";
        $ds     = new DataStore($db, $sql);
        $fila = $ds->getValues(0);
/*
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        $this->RotatedText(35,190,'N o   e s   c o n s t a n c i a',45);
        $this->SetTextColor(0,0,0);
*/
        $this->Image('images/logo_bn_luz.jpg',25,8,20);
        $this->SetFont('Arial','B',11);
        $this->Cell(70);
        $this->Cell(30,5,'Universidad del Zulia',0,0,'C');
        $this->Ln();
/*        $this->Cell(70);
        $this->Cell(30,5,'Rectoría',0,0,'C');
        $this->Ln();*/
        $this->Cell(70);
        $this->Cell(30,5,'Dirección de Recursos Humanos',0,0,'C');
        $this->Ln();
        $this->Cell(70);
        $this->Cell(30,5,'Suministro de personal Administrativo y Obrero',0,0,'C');
        $this->Ln();
        $this->Cell(70);
        $this->Cell(30,5,'Módulo gestión de contratos',0,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',13);
        $this->Cell(70);
        $this->Cell(30,5,'Personal Contratado',0,0,'C');
        $this->Ln(10);
        $this->SetFont('Arial','B',8);
        $this->Cell(25,5,'Cédula',1,0,'C',true);
        $this->Cell(60,5,'Beneficiario',1,0,'C',true);
        $this->Cell(20,5,'Ubicación',1,0,'C',true);
        $this->Cell(25,5,'Tipo de personal',1,0,'C',true);
        $this->Cell(25,5,'Fecha',1,0,'C',true);
        $this->Cell(20,5,"Página",1,0,'C',true);
        $this->Ln();
        $this->Cell(25,5,number_format($fila["CE_TRABAJADOR"],0,',','.'),1,0,'C');
        $this->Cell(60,5,$fila["NOMBRES"],1,0,'C');
        $this->Cell(20,5,substr($fila["UBICACION"],0,5),1,0,'C');
        $this->Cell(25,5,substr($fila["TIPPER"],0,6),0,0,'C');
        $this->Cell(25,5, de_mes($this->mes) ." ".$this->ano,1,0,'C');
        $this->Cell(20,5,$this->PageNo().'/{nb}',1,0,'C');
        $this->Ln();
        $this->Cell(85,5,'Conceptos',1,0,'C',true);
        $this->Cell(30,5,'Asignaciones',1,0,'C',true);
        $this->Cell(30,5,'Deducciones',1,0,'C',true);
        $this->Cell(30,5,'Saldo',1,0,'C',true);
        $this->Ln();
        $this->SetTitle($fila["NOMBRES"]);

    }

    //Pie de página
    function Detail(){
        $db     = "sidial";
//        $sql =  " SELECT distinct * FROM VW_DETALLE_PAGO_ESPECIAL WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND TIPO_NOMINA = 50 AND TIPO_NOMINA_ESPECIFICA = $this->mes ORDER BY 4";
        $sql =  " SELECT distinct * FROM VW_DETALLE_PAGO_ESPECIAL WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND TIPO_NOMINA = 50 AND MES = $this->mes ORDER BY 4";
        $ds     = new DataStore($db, $sql);
        $total_asig = 0;
        $total_deduc = 0;
    	if($ds->getNumRows()==0){
            $this->SetFont('Arial','B',14);
            $this->Cell(80,10,'No hay datos para la consulta',0,0,'C');
        } else {
            $this->SetFont('Arial','',8);
            $this->primera = 0;
            for($i=0 ; $i < $ds->getNumRows() ; $i++){
                $fila = $ds->getValues($i);
                if(strcmp($fila["CO_CONCEPTO"],"X500")==0) $this->primera = $fila["DEDUCCIONES"];

                $this->Cell(85,4,$fila["DESCRIPCION"],0,0,'L');
                $monto = number_format($fila["ASIGNACIONES"],2,',','.');
                if ($fila["ASIGNACIONES"]==0) $monto = "";
                $this->Cell(30,4,$monto,0,0,'R');
                $monto = number_format($fila["DEDUCCIONES"],2,',','.');
                if ($fila["DEDUCCIONES"]==0) $monto = "";
                $this->Cell(30,4,$monto,0,0,'R');
                $monto = number_format($fila["MO_SALDO"],2,',','.');
                if ($fila["MO_SALDO"]==0) $monto = "";
                $this->Cell(30,4,$monto,0,0,'R');
                $this->Ln();
                $total_asig += $fila["ASIGNACIONES"];
                $total_deduc += $fila["DEDUCCIONES"];
            }
            $this->SetFont('Arial','B',8);
            $this->Cell(85,5,'TOTAL DE ASIGNACIONES Y DEDUCCIONES',0,0,'L');
            $this->Cell(30,5,number_format($total_asig,2,',','.'),0,0,'R');
            $this->Cell(30,5,number_format($total_deduc,2,',','.'),0,0,'R');
            $this->Ln();
            $this->segunda = $total_asig -$total_deduc;
        }
    }
    //Pie de página
    function Footer() {
        $db     = "sidial";
        $sql    = "SELECT distinct * FROM VW_CABECERA_HIS_ESPECIAL WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND TIPO_NOMINA = 50 AND TIPO_NOMINA_ESPECIFICA = $this->mes";
        $ds     = new DataStore($db, $sql);
        $fila = $ds->getValues(0);
        //Posición: a 1,5 cm del final
        $this->SetY(-65);
        $this->SetFont('Arial','I',8);
        $this->Cell(65,5,'Abono en banco',1,0,'C');
        $this->Cell(40,5,'No. de cuenta',1,0,'C');
        $this->Cell(25,5,'',1,0,'C');
        $this->Cell(25,5,'',1,0,'C');
/*        $this->Cell(25,5,'1era.quincena',1,0,'C');
        $this->Cell(25,5,'2da. quincena',1,0,'C');*/
        $this->Cell(25,5,'Asig. del mes',1,0,'C');
        $this->Ln();
        $this->SetFont('Arial','BI',8);
        $this->Cell(65,5,$fila["BANCO"],1,0,'L');
        $this->Cell(40,5,$fila["CUENTA"],1,0,'C');
        $this->Cell(25,5,"",1,0,'C');
        $this->Cell(25,5,"",1,0,'C');
/*        $this->Cell(25,5,number_format($this->primera,2,',','.'),1,0,'C');
        $this->Cell(25,5,number_format($this->segunda,2,',','.'),1,0,'C');*/
        $this->Cell(25,5,number_format($this->segunda,2,',','.'),1,0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',8);
        $this->Cell(180,5,"V  A  L  I  D  A  D  O  R",1,0,'C',true);
        $this->Ln();
        $codigoBarras = new CodigoBarras();
        $id_digital = $this->id;
        list($year, $month, $day) = split("-", $this->fe_solicitud);
        $date = date('dd/mm/YY', mktime(0, 0, 0, $month, $day, $year));
        $codigoBarras->setCodigoBarras("DPC",$id_digital,$date);//date("d/m/Y"));
        $this->SetFont('Arial','B',10);
        $this->Cell(90,4,'ID digital: '.$codigoBarras->getCodigo(),0,0,'L');
        $this->Cell(90,4,'Fecha de impresión: '.$this->fe_solicitud,0,0,'R');//$codigoBarras->getFecha(),0,0,'R');
        $this->Ln();
        $this->Image($codigoBarras->getHTMLImage(), 90, null,40,0,"png");
        $this->SetFont('Arial','I',6);
        $this->Cell(180,3,'Para confirmar la validez de este comprobante, ingrese en nuestro sitio web www.luz.edu.ve/validador y siga las instrucciones.',0,1,'C');
        $this->Cell(180,3,'La información mostrada en este reporte es personal.',0,1,'C');
        $this->Cell(180,3,'Procesado por Diticluz',0,1,'R');
    }
}

//Creación del objeto de la clase heredada
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

$db  = "rrhh";
$ds  = new DataStore($db);
$sql = "insert into tra_validador_documento (da_ano, da_mes, ce_trabajador, da_tipo_tramite) values ($pdf->ano, $pdf->mes,$pdf->cedula,'DPC')";
$ds->executesql($sql);
$sql = "select id, fe_solicitud from tra_validador_documento where da_ano = $pdf->ano and da_mes = $pdf->mes and ce_trabajador = $pdf->cedula and da_tipo_tramite='DPC'";
$ds->executesql($sql);
$fila              = $ds->getValues(0);
$pdf->id           = $fila["id"];
$pdf->fe_solicitud = $fila["fe_solicitud"];

$pdf->SetCreator("aplicacionWebLUZ");
$pdf->SetAuthor("DiTICLUZ - oamesty");
$pdf->SetSubject("Detalle de Pago para contratados");
$pdf->SetLeftMargin(20);
$pdf->SetFillColor(220);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Detail();
$pdf->Output();

?>
