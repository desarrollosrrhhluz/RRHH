<?php
include_once("../FrameWork/Include/Defines.inc.php");
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
require(LUZ_PATH_3PARTY.'/fpdf/fpdf.php');
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once(LUZ_PATH_CLASS.'/CodigoBarras.class.php');
include_once(LUZ_PATH_INCLUDE.'/Funciones.inc.php');

class PDF extends FPDF {
    //Cabecera de página
    public $cedula;
    public $ano;
    public $mes;
    public $asignacion;
    public $deduccion;
    public $id;
    public $nomina_general;
    public $nomina_especifica;
    public $fe_solicitud;

    function Header() {
        $db     = "sidial";
	$sql  = "SELECT * FROM VW_CABECERA_HIS_ESPECIAL WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND ";
        $sql .= "MES = $this->mes AND TIPO_NOMINA = $this->nomina_general AND TIPO_NOMINA_ESPECIFICA = $this->nomina_especifica";
        $ds     = new DataStore($db, $sql);
        $fila = $ds->getValues(0);

        $this->Image('images/logo_bn_luz.jpg',25,8,20);
        $this->SetFont('Arial','B',11);
        $this->Cell(70);
        $this->Cell(30,5,'Universidad del Zulia',0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,('Dirección de Recursos Humanos'),0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,('Proceso de Nómina'),0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,('Dirección de Tecnologías de Información y Comunicación'),0,1,'C');
        $this->Cell(70);
        $this->Cell(30,5,('Detalle de pago de Nómina especial'),0,0,'C');

        $this->Ln(10);
        $this->SetFont('Arial','B',8);
        $this->Cell(20,5,('Cédula'),1,0,'C',true);
        $this->Cell(65,5,'Beneficiario',1,0,'C',true);
        $this->Cell(20,5,('Ubicación'),1,0,'C',true);
        $this->Cell(25,5,'Tipo de personal',1,0,'C',true);
        $this->Cell(25,5,'Fecha',1,0,'C',true);
        $this->Cell(20,5,("Página"),1,0,'C',true);
        $this->Ln();
        $this->Cell(20,5,number_format($fila["CE_TRABAJADOR"],0,',','.'),1,0,'C');
        $this->SetFont('Arial','B',6);
        $this->Cell(65,5,$fila["NOMBRES"],1,0,'L');
        $this->SetFont('Arial','B',8);
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

    function Detail(){
        $db     = "sidial";
	 $sql  = "SELECT * FROM VW_DETALLE_PAGO_ESPECIAL WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND ";
        $sql .= "MES = $this->mes AND TIPO_NOMINA = $this->nomina_general AND TIPO_NOMINA_ESPECIFICA = $this->nomina_especifica ORDER BY 4";
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
                //if(strcmp($fila["CO_CONCEPTO"],"X500")==0) $this->primera = $fila["DEDUCCIONES"];

                $this->Cell(85,4,$fila["DESCRIPCION"],0,0,'L');
                
                $monto = ($fila["ASIGNACIONES"]==0) ? $monto = "" : number_format($fila["ASIGNACIONES"],2,',','.');
                $this->Cell(30,4,$monto,0,0,'R');
                
                $monto = ($fila["DEDUCCIONES"]==0) ? $monto = "" : number_format($fila["DEDUCCIONES"],2,',','.');
                $this->Cell(30,4,$monto,0,0,'R');
                
                $monto = ($fila["MO_SALDO"]==0) ? $monto = "" : number_format($fila["MO_SALDO"],2,',','.');
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
          //  $this->Cell(85,5,'TOTAL ASIGNACION',0,0,'L');
            $t                = $total_asig -$total_deduc;
            $this->asignacion = $total_asig;
            $this->deduccion  = $total_deduc;
            //$this->Cell(30,5,number_format($t,2,',','.'),0,0,'R');
        }
    }

    //Pie de pï¿½gina
    function Footer() {
        $this->SetY(-65);
        $this->SetFont('Arial','I',8);
        $this->Cell(155,5,'',1,0,'C');
        $this->Cell(25,5,'Asignacion',1,1,'C');
        $this->SetFont('Arial','BI',8);
        $this->Cell(155,5,'',1,0,'C');
        $this->Cell(25,5,number_format($this->asignacion - $this->deduccion,2,',','.'),1,1,'C');
        $this->SetFont('Arial','B',8);
        $this->Cell(180,5,"V  A  L  I  D  A  D  O  R",1,1,'C',true);
        $codigoBarras = new CodigoBarras();
        $id_digital = $this->id;//ano.str_repeat('0',2 - strlen($this->mes)).$this->mes;
        list($year, $month, $day) = split("-", $this->fe_solicitud);
        $date = date('dd/mm/YY', mktime(0, 0, 0, $month, $day, $year));
        $codigoBarras->setCodigoBarras("DPE",$id_digital,$date);//date("d/m/Y"));
        $this->SetFont('Arial','B',10);
        $this->Cell(90,4,'ID digital: '.$codigoBarras->getCodigo(),0,0,'L');
        $this->Cell(90,4,'Fecha de impresión: '.$this->fe_solicitud,0,1,'R');
        $this->Image($codigoBarras->getHTMLImage(), 90, null,40,0,"png");
        $this->SetFont('Arial','I',6);
        $this->Cell(180,3,'Para confirmar la validez de este comprobante, ingrese en nuestro sitio web www.luz.edu.ve/validador y siga las instrucciones.',0,1,'C');
        $this->Cell(180,3,'La información mostrada en este reporte es personal.',0,1,'C');
        $this->Cell(180,3,'Procesado por Diticluz',0,1,'R');

    }
}

//Creación del objeto de la clase heredada
$pdf=new PDF('P','mm','letter');
session_start();
$nid = getVar("cid");
if(strcmp($nid,"")!=0) {
	$pdf->cedula = $nid;
} else {
	session_start();
	$pdf->cedula = $_SESSION["cedula"];
}
$cadena = getVar("cadena");
$pdf->ano = substr($cadena,0,4);
$pdf->mes = substr($cadena,4,2);
$pdf->nomina_general = substr($cadena,6,3);
$pdf->nomina_especifica = substr($cadena,9,12);

$db  = "rrhh";
$ds  = new DataStore($db);
$sql = "select id, fe_solicitud from tra_validador_documento where "
        . "da_ano = $pdf->ano and da_mes = $pdf->mes and "
        . "ce_trabajador = $pdf->cedula and "
        . "da_tipo_tramite='DPE' and codigo = '$cadena'";
$ds->executesql($sql);
if($ds->getNumRows()>0){
    $fila              = $ds->getValues(0);
} else {
    $sql = "insert into tra_validador_documento (da_ano, da_mes, ce_trabajador, da_tipo_tramite, codigo) "
            . "values ($pdf->ano, $pdf->mes,$pdf->cedula,'DPE','$cadena')";
    $ds->executesql($sql);
    $sql = "select id, fe_solicitud from tra_validador_documento where "
            . "da_ano = $pdf->ano and da_mes = $pdf->mes and "
            . "ce_trabajador = $pdf->cedula and "
            . "da_tipo_tramite='DPE' and codigo = '$cadena'";
    $ds->executesql($sql);
    $fila              = $ds->getValues(0);
}
$pdf->id           = $fila["id"];
$pdf->fe_solicitud = $fila["fe_solicitud"];

$pdf->SetCreator("aplicacionWebLUZ");
$pdf->SetAuthor("DiTICLUZ - oamesty");
$pdf->SetSubject("Detalle de Pago Nomina Especial");
$pdf->SetLeftMargin(20);
$pdf->SetFillColor(220);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Detail();
$pdf->Output();


