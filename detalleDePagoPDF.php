<?php
include_once("../FrameWork/Include/Defines.inc.php");
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
require(LUZ_PATH_3PARTY.'/fpdf/fpdf.php');
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
include_once(LUZ_PATH_CLASS.'/CodigoBarras.class.php');
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
$sql = "SELECT '' as NOMINA,
               CE_TRABAJADOR,
               ANO,
               MES,ORDEN,
               CO_CONCEPTO,
               DESCRIPCION,
               ASIGNACIONES,
               DEDUCCIONES,
               MO_SALDO,
               1 as TIPO_NOMINA,
               1 as TIPO_NOMINA_ESPECIFICA 
        FROM VW_DETALLE_PAGO	
	WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND MES = $this->mes 
--union
--SELECT '*' as NOMINA,CE_TRABAJADOR,ANO,MES,CO_CONCEPTO,DESCRIPCION,ASIGNACIONES,DEDUCCIONES,MO_SALDO,TIPO_NOMINA,TIPO_NOMINA_ESPECIFICA FROM VW_DETALLE_PAGO_ESPECIAL 
--WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND MES = $this->mes AND TIPO_NOMINA = 33 AND TIPO_NOMINA_ESPECIFICA = 1	
ORDER BY 5,6";        
        
//        $sql =  " SELECT * FROM VW_DETALLE_PAGO	WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND MES = $this->mes ORDER BY 4";
        $ds     = new DataStore($db, $sql);
        //echo $sql;
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
                if(strcmp($fila["CO_CONCEPTO"],"X500")==0) {
                    $this->primera += $fila["DEDUCCIONES"];
                }
                $this->Cell(5,4,$fila["NOMINA"],0,0,'L');
                $this->Cell(80,4,$fila["DESCRIPCION"],0,0,'L');
                
                $monto = ($fila["ASIGNACIONES"]==0) ? $monto = "" : number_format($fila["ASIGNACIONES"],2,',','.');
                $this->Cell(30,4,$monto,0,0,'R');
                
                $monto = ($fila["DEDUCCIONES"]==0) ? $monto = "" : number_format($fila["DEDUCCIONES"],2,',','.');
                $this->Cell(30,4,$monto,0,0,'R');
                
                $monto = ($fila["MO_SALDO"]==0) ? $monto = "" : number_format($fila["MO_SALDO"],2,',','.');
                $this->Cell(30,4,$monto,0,0,'R');

                $this->Ln();
                $total_asig += $fila["ASIGNACIONES"];
                $total_deduc += $fila["DEDUCCIONES"];
                if (strcmp($fila["NOMINA"],'*') == 0) {
                  $this->paralela = $this->paralela + ($fila["ASIGNACIONES"] - $fila["DEDUCCIONES"]);
                } else {                 
                  $this->segunda = $this->segunda + ($fila["ASIGNACIONES"] - $fila["DEDUCCIONES"]);
                }
            }
            $this->SetFont('Arial','B',8);
            $this->Cell(85,5,'TOTAL DE ASIGNACIONES Y DEDUCCIONES',0,0,'L');
            $this->Cell(30,5,number_format($total_asig,2,',','.'),0,0,'R');
            $this->Cell(30,5,number_format($total_deduc,2,',','.'),0,0,'R');
            $this->segunda = $total_asig -$total_deduc;
        }
    }
    //Pie de p�gina
    function Footer() {
	    $db     = "sidial";
        $sql    = "SELECT * FROM VW_CABECERA_HISTORICO WHERE CE_TRABAJADOR = $this->cedula AND ANO = $this->ano AND MES = $this->mes";
        $ds     = new DataStore($db, $sql);
        $fila = $ds->getValues(0);
        $this->SetY(-65);
//            $this->Cell(180,5,'(*) CONCEPTO CORRESPONDIENTE AL INCREMENTO SALARIAL VIGENTE A PARTIR DE 1 DE MAYO DEL 2.011',0,1,'L');
        $this->SetFont('Arial','B',7);
            //$this->MultiCell(180,5,'(*) CONCEPTO CORRESPONDIENTE AL INCREMENTO SALARIAL VIGENTE A PARTIR DE LA PRIMERA CONVENCI�N COLECTIVA UNICA 2013-2014');
        $this->SetFont('Arial','I',8);
        $this->Cell(65,5,'Abono en banco',1,0,'C');
        $this->Cell(40,5,'No. de cuenta',1,0,'C');
        $this->Cell(25,5,'1era.quincena',1,0,'C');
        $this->Cell(25,5,'2da. quincena',1,0,'C');
        $this->Cell(25,5,'Asig. del mes',1,1,'C');
//        $this->Ln();
        $this->SetFont('Arial','BI',8);
        $this->Cell(65,5,$fila["BANCO"],1,0,'L');
        $this->Cell(40,5,$fila["CUENTA"],1,0,'C');
        $this->Cell(25,5,number_format($this->primera,2,',','.'),1,0,'C');
        $this->Cell(25,5,number_format($this->segunda,2,',','.'),1,0,'C');
//        $this->Cell(25,5,number_format($this->primera + $this->segunda + $this->paralela,2,',','.'),1,0,'C');
        $this->Cell(25,5,number_format($this->primera + $this->segunda,2,',','.'),1,1,'C');
//        $this->Ln();
        $this->SetFont('Arial','B',8);
        $this->Cell(180,5,"V  A  L  I  D  A  D  O  R",1,1,'C',true);
//        $this->Ln();
        $codigoBarras = new CodigoBarras();
        $id_digital = $this->id;//ano.str_repeat('0',2 - strlen($this->mes)).$this->mes;
        list($year, $month, $day) = split("-", $this->fe_solicitud);
        $date = date('dd/mm/YY', mktime(0, 0, 0, $month, $day, $year));
        $codigoBarras->setCodigoBarras("DP",$id_digital,$date);//date("d/m/Y"));
        $this->SetFont('Arial','B',10);
        $this->Cell(90,4,'ID digital: '.$codigoBarras->getCodigo(),0,0,'L');
        $this->Cell(90,4,utf8_decode('Fecha de impresión: ').$this->fe_solicitud,0,1,'R');
//        $this->Ln();
        $this->Image($codigoBarras->getHTMLImage(), 90, null,40,0,"png");
        $this->SetFont('Arial','I',6);
        $this->Cell(180,3,'Para confirmar la validez de este comprobante, ingrese en nuestro sitio web www.luz.edu.ve/validador y siga las instrucciones.',0,1,'C');
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
$db  = "rrhh";
$ds  = new DataStore($db);
$sql = "select id, fe_solicitud from tra_validador_documento where da_ano = $pdf->ano and da_mes = $pdf->mes and ce_trabajador = $pdf->cedula and da_tipo_tramite='DP'";
$ds->executesql($sql);
if($ds->getNumRows()>0){
    $fila              = $ds->getValues(0);
} else {
    $sql = "insert into tra_validador_documento (da_ano, da_mes, ce_trabajador, da_tipo_tramite) values ($pdf->ano, $pdf->mes,$pdf->cedula,'DP')";
    $ds->executesql($sql);
    $sql = "select id, fe_solicitud from tra_validador_documento where da_ano = $pdf->ano and da_mes = $pdf->mes and ce_trabajador = $pdf->cedula and da_tipo_tramite='DP'";
    $ds->executesql($sql);
    $fila              = $ds->getValues(0);
}
$pdf->id           = $fila["id"];
$pdf->fe_solicitud = $fila["fe_solicitud"];

$pdf->SetCreator("aplicacionWebLUZ");
$pdf->SetAuthor("DiTICLUZ - oamesty");
$pdf->SetSubject("Detalle de Pago");
$pdf->SetLeftMargin(20);
$pdf->SetFillColor(220);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Detail();
$pdf->Output();

?>
