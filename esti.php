<?php
include_once("../FrameWork/Include/Defines.inc.php");
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
require(LUZ_PATH_3PARTY.'/fpdf/fpdf.php');
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once(LUZ_PATH_CLASS.'/CodigoBarras.class.php');
include_once(LUZ_PATH_INCLUDE.'/Funciones.inc.php');

class PDF extends FPDF {
    //Cabecera de p�gina
    public $cedula;
    public $ano;
    public $mes;
    public $primera;
    public $segunda;
    public $id;
    public $fe_solicitud;


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

        $this->SetFont('Arial','B',40);
        $this->SetTextColor(255,192,203);
        $this->RotatedText(35,120,'N o   e s   c o n s t a n c i a',30);
        $this->SetTextColor(0,0,0);
        $this->Image('images/logo_bn_luz.jpg',25,8,20);
        $this->SetFont('Arial','B',11);
        $this->Cell(70);
        $this->Cell(30,5,'Universidad del Zulia',0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,'Direcci'.utf8_decode('ó').'n de Recursos Humanos',0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,'Proceso de Egreso y Seguridad Social',0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,'Direcci'.utf8_decode('ó').'n de Tecnolog'.utf8_decode('í').'as de Informaci'.utf8_decode('ó').'n y Comunicaci'.utf8_decode('ó').'n',0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,'Estimado de Prestaciones Sociales',0,1,'C');
        $this->Ln();
    }

    function Detail(){
//        $db     = "sidial";
//        $sql    = "SELECT *, convert(char(12), FE_INGRESO, 103) as fecha FROM VW_CABECERA_ACTUAL WHERE CE_TRABAJADOR = $this->cedula";
//        $ds0     = new DataStore($db, $sql);
//    	if($ds0->getNumRows()==0){
//            $this->SetFont('Arial','B',14);
//            $this->Cell(80,10,'No hay datos para la consulta',0,0,'C');
//        } else {
//			$fila = $ds0->getValues(0);
//			$this->SetFont('Arial','',10);
//			$this->Cell(40,5,'Beneficiario',1,0,'C');
//			$this->Cell(100,5,$fila["NOMBRES"],1,0,'L');
//			$this->Cell(20,5,'C'.utf8_decode('é').'dula',1,0,'CL');
//			$this->Cell(20,5,number_format($fila["CE_TRABAJADOR"],0,',','.'),1,1,'L');
//			$this->Cell(40,5,'Dependencia',1,0,'C');
//			$this->Cell(140,5,$fila["UBICACION"],1,1,'L');
//			$this->Cell(40,5,'Tipo de personal',1,0,'C');
//			$this->Cell(140,5,$fila["TIPPER"],1,1,'L');
//			$this->Ln();
//
//			$this->SetFont('Arial','B',12);
//			$this->Cell(180,5,'Detalle de calculo',0,1,'C');
//
//			$this->SetFont('Arial','',10);
//            $this->Cell(50,5);
//			$this->Cell(40,5,'Fecha de Ingreso LUZ',1,0,'C');
//			$this->Cell(30,5,$fila["fecha"],1,1,'R');
//            $this->Cell(50,5);
//			$this->Cell(40,5,'Fecha de Estimaci'.utf8_decode('ó').'n',1,0,'C');
//			$this->Cell(30,5,date('d/m/Y'),1,1,'R');
//			$this->SetTitle($fila["NOMBRES"]);
                        $db     = "sidial";
                        $sql    = "exec ESTIMADO_PS $this->cedula";
                        $ds0     = new DataStore($db);
                        $ds0->executesql($sql);
                    	if($ds0->getNumRows()==0){
                            $this->SetFont('Arial','B',14);
                            $this->Cell(80,10,'No hay datos para la consulta',0,0,'C');
                        } else {
				$this->Cell(50,5);
				$this->Cell(40,5,'A'.utf8_decode('ñ').'os de servicio',1,0,'C');
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
//		}
    }
    //Pie de p�gina
    function Footer() {
        $this->SetFont('Arial','I',10);
        //$this->SetY(-25);
        $this->SetY(120);
        $this->Cell(180,3,'Esta informaci'.utf8_decode('ó').'n alude a un estimado y es para uso particular del interesado',0,1,'C');
		$this->Ln();
 //       $this->Cell(180,3,'para uso particular del interesado.',0,1,'C');
//        $this->Cell(180,3,'La informaci'.utf8_decode('ó').'n mostrada en este reporte es solo para uso informativo.',0,1,'C');
//        $this->Cell(180,3,'Y esta sujeta a modificaciones.',0,1,'C');
        $this->SetFont('Arial','I',8);
        $this->Cell(180,3,'Procesado por Diticluz / SICAPS',0,1,'R');
    }
}

//Creaci�n del objeto de la clase heredada
$pdf=new PDF('P','mm','letter');//array(220,140));//'letter');
$nid = getVar("cid");
if(strcmp($nid,"")!=0) {
	$pdf->cedula = $nid;
	$pdf->id     = 0;
} else {
	session_start();
	$pdf->cedula = 17836293;
	$pdf->id     = 16974;
}

$pdf->SetCreator("aplicacionWebLUZ");
$pdf->SetAuthor("DiTICLUZ - oamesty");
$pdf->SetSubject("Estimado de Prestaciones Sociales");
$pdf->SetLeftMargin(20);
$pdf->SetFillColor(220);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Detail();
$pdf->Output();

?>
