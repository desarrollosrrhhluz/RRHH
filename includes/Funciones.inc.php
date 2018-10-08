<?php


function redondear ($numero, $decimales) {
    $factor = pow(10, $decimales);
    return (round($numero*$factor)/$factor);
}


function diferenciaFecha($base_date = '', $current_date = ''){

    if($base_date == '') return false;
    if($current_date == '') $fechafinal = time();

    $base_day = date ("j",$base_date);
    $base_mon = date ("n",$base_date);
    $base_yr = date ("Y",$base_date);

    $current_day = date ("j",$current_date);
    $current_mon = date ("n",$current_date);
    $current_yr = date ("Y",$current_date);

    // overflow is always caused by max days of $base_mon
    // so we need to know how many days $base_mon had
    $base_mon_max = date ("t",mktime (0,0,0,$base_mon,$base_day,$base_yr));

    // days left till the end of that month
    $base_day_diff = $base_mon_max - $base_day;

    // month left till end of that year
    // substract one to handle overflow correctly
    $base_mon_diff = 12 - $base_mon - 1;

    // start on jan 1st of the next year
    $start_day = 1;
    $start_mon = 1;
    $start_yr = $base_yr + 1;

    // difference to that 1st of jan
    $day_diff = ($current_day - $start_day) + 1; // add today
    $mon_diff = ($current_mon - $start_mon) + 1; // add current month
    $yr_diff = ($current_yr - $start_yr);

    // and add the rest of $base_yr
    $day_diff = $day_diff + $base_day_diff;
    $mon_diff = $mon_diff + $base_mon_diff;

    // handle overflow of days
    if ($day_diff >= $base_mon_max) {
        $day_diff = $day_diff - $base_mon_max;
        $mon_diff = $mon_diff + 1;
    }

    // handle overflow of years
    if ($mon_diff >= 12) {
        $mon_diff = $mon_diff - 12;
        $yr_diff = $yr_diff + 1;
    }

    return array("ano"=>$yr_diff,"mes"=>$mon_diff,"dia"=>$day_diff);
}

function fecha_texto($fecha = ''){
	static $mes = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	if ($fecha == '') $fecha = time();
    if ((int)$fecha > 9999) $fecha = date('Y-m-d', $fecha);
	list($a,$m,$d) = explode('-', $fecha);
	$d = (int)$d;
	$m = (int)$m;
	return "$d de $mes[$m] de $a";
}
function meses(){
	$mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	return $mes;
}
function nmeses(){
	$mes = array(1,2,3,4,5,6,7,8,9,10,11,12);
	return $mes;
}
function de_mes($mes){
    $ames = meses();
    return $ames[$mes-1];
}
function nu_mes($mes){
    $ames = meses();
    for($i=0; $i<12; $i++)
        if(strcmp($ames[$mes],ucfirst(strtolower($mes)))==0) return $mes+1;
}

function anos(){
    $fecha = time();
    $fecha = date('Y-m-d', $fecha);
    list($a,$m,$d) = explode('-', $fecha);
    $ano=(int)$a;
    for ($i=$ano; $i>=($ano - 6); $i--)  {
      $a_ano[] = $i;
    }
    return $a_ano;
}

function dateadd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0){
  //$date_r = getdate(strtotime($date));
  $date_r = getdate($date);
  $date_result = date("d/m/Y h:i:s", mktime(($date_r["hours"]+$hh),($date_r["minutes"]+$mn),($date_r["seconds"]+$ss),($date_r["mon"]+$mm),($date_r["mday"]+$dd),($date_r["year"]+$yy)));
  return $date_result;
}

function getvar($name){
    global $_GET, $_POST;
    if (isset($_GET[$name])) return $_GET[$name];
    else if (isset($_POST[$name])) return $_POST[$name];
    else return false;
}

function encriptar($as_cadena,$ai_modo){
	$encdata = '';
	$largo = strlen($as_cadena);
	for ($i=1; $i<=$largo; $i++)  {
		$codigo = ord(substr($as_cadena,$i-1,1));
		$acumulado = abs(30 * round(sin($i * $largo)));
		if ($ai_modo == 1) 
			$encdata = $encdata . chr($codigo + $acumulado);
		else 
			$encdata = $encdata . chr($codigo - $acumulado) ;
	}
	return $encdata;
		/*if ($ai_modo == 1) 
			$encriptado = mcrypt_ecb ( MCRYPT_TripleDES, "web", $as_cadena, MCRYPT_ENCRYPT);
		else 
			$encriptado = mcrypt_ecb ( MCRYPT_TripleDES, "web", $as_cadena, MCRYPT_DECRYPT);
		return $encriptado;*/
}

function generar_contrasena(){
	$pwd = rand(10000000,20000000);
	$pwd = encriptar($pwd,1);
	return $pwd;
}

function enviar_correo($destinatario,$passwd){
		$titulo = "Registro en linea al Portal de LUZ";
		$mensaje = "Gracias por registrarse en el Portal de La Universidad del Zulia.\n\n";
		$mensaje .= "Contraseñaa: $passwd\n\n";
		$mensaje .= "Gracias!!\n";
		ini_set("SMTP", "smtp.gmail.com"); // valores; localhost, 10.1.15.252, mail.cantv.net
		ini_set("smtp_port", "465");        //           mercury     no se       tampoco se
		ini_set("sendmail_from", "webmaster@ditic.luz.edu.ve");
		mail($destinatario,$titulo,$mensaje);
}

function digito_chequeo_carnet($ano,$ci,$prd) {
	$ls_ckdg = sprintf("%09d",$ci).sprintf("%04d",$ano).sprintf("%03d",ord($prd));
	$ls_md13='2765432765432765';
	$total=0;
	for($i=0;$i<16;$i++)
		$total= $total + ($ls_md13[$i] * $ls_ckdg[$i]);
	switch(fmod($total,11)) {
	case 0:
		$li_digito= 1;
		break;
	case 1:
		$li_digito= 0;
		break;
	default:
		$li_digito=11-fmod($total,11);
	}
	return $li_digito;
}
	

function check_email_mx($email) { 
	if( (preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email)) || 
		(preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/',$email)) ) { 
    			return true;
	}
	return false;
}


function cambia_color($tr){
  	if($tr == "<tr class=par>") $tr = "<tr class=impar>";
  	else $tr = "<tr class=par>";
	return $tr;
}


////////////////////////////////////////////////


////////////////////////////////////////////////


//*****************************************
function formato_fecha($fec){

	$meses=array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	$len=strlen($fec);
	$mes=substr($fec,0,3);

	if ($len ==18){
	 $dia=str_pad(substr($fec,4,1),2,0,STR_PAD_LEFT);
	 $anno=substr($fec,6,4);
	}else{
	 $dia=str_pad(substr($fec,4,2),2,0,STR_PAD_LEFT);
	 $anno=substr($fec,7,4);
	}

	$m= array_keys($meses,$mes); // busca mes
	$m_f=str_pad($m[0] + 1,2,0,STR_PAD_LEFT) ; // le suma uno para dar exacto

	$f=$dia ."/".$m_f."/".$anno ;

    return $f;
}

//**************************************
function cambiaf_a_normal($fecha){ 
    ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
    $lafecha = $mifecha[3]."/".$mifecha[2]."/".$mifecha[1] ; 
    return $lafecha; 
} 

//**************************************
function cambiaf_a_mysql($fecha){ 
    ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
    $lafecha = $mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    return $lafecha; 
} 
//**************************************
function restaFechas($dFecIni, $dFecFin)
{
    $dFecIni = str_replace("-","",$dFecIni);
    $dFecIni = str_replace("/","",$dFecIni);
    $dFecFin = str_replace("-","",$dFecFin);
    $dFecFin = str_replace("/","",$dFecFin);

    ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
    ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);

    $date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
    $date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);

    return round(($date2 - $date1) / (60 * 60 * 24));
}
//****************************************
function fecha_espanol(){
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
$fecha="a los ".date("d")." días del mes de ".$a[date(n)]." de ".date("Y")."";
return $fecha; 
}

function fecha_oficio(){
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
$fecha="".date("d")." de ".$a[date(n)]." de ".date("Y")."";
return $fecha; 
}
//******************************************************
function suma_fechas($fecha,$ndias){


if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))


list($dia,$mes,$ano)=split("/", $fecha);


if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))


list($dia,$mes,$ano)=split("-",$fecha);
$nueva = mktime(0,0,0, $mes,$dia,$ano) + $ndias * 24 * 60 * 60;
$nuevafecha=date("d/m/Y",$nueva);


return ($nuevafecha);


} 
//*****************************************
//$fecha_nac ="1973/06/01";
function edad($fecha_nac){
$dia=date("j");
$mes=date("n");
$anno=date("Y");
$dia_nac=substr($fecha_nac, 8, 2);
$mes_nac=substr($fecha_nac, 5, 2);
$anno_nac=substr($fecha_nac, 0, 4);
if($mes_nac>$mes){
$calc_edad= $anno-$anno_nac-1;
}else{
if($mes==$mes_nac AND $dia_nac>$dia){
$calc_edad= $anno-$anno_nac-1;
}else{
$calc_edad= $anno-$anno_nac;
}
}
return $calc_edad;
}
//***********************************************
function formato_fecha_aaaa($fec){

	$meses=array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	$len=strlen($fec);
	$mes=substr($fec,0,3);

	if ($len ==18){
	 $dia=str_pad(substr($fec,4,1),2,0,STR_PAD_LEFT);
	 $anno=substr($fec,6,4);
	}else{
	 $dia=str_pad(substr($fec,4,2),2,0,STR_PAD_LEFT);
	 $anno=substr($fec,7,4);
	}

	$m= array_keys($meses,$mes); // busca mes
	$m_f=str_pad($m[0] + 1,2,0,STR_PAD_LEFT) ; // le suma uno para dar exacto

	//$f=$dia ."/".$m_f."/".$anno ;
	$f=$anno."/".trim($m_f)."/".trim($dia);

    return $f;
}
//***********************************************
function edad_mes($fecha_nac){
$dia=30;
$mes=07;
$anno=2010;
$dia_nac=substr($fecha_nac, 8, 2);
$mes_nac=substr($fecha_nac, 5, 2);
$anno_nac=substr($fecha_nac, 0, 4);
if($mes_nac>$mes){
$calc_edad= $anno-$anno_nac-1;
}else{
if($mes==$mes_nac AND $dia_nac>$dia){
$calc_edad= $anno-$anno_nac-1;
}else{
$calc_edad= $anno-$anno_nac;
}
}
return $calc_edad;
}
//echo edad($fecha_nac);
/////////////////////////////////////////////////////////
function str_to_upper($str){
// return strtr($str,
// "abcdefghijklmnopqrstuvwxyz".
// "\x9C\x9A\xE0\xE1\xE2\xE3".
// "\xE4\xE5\xE6\xE7\xE8\xE9".
// "\xEA\xEB\xEC\xED\xEE\xEF".
// "\xF0\xF1\xF2\xF3\xF4\xF5".
// "\xF6\xF8\xF9\xFA\xFB\xFC".
// "\xFD\xFE\xFF",
// "ABCDEFGHIJKLMNOPQRSTUVWXYZ".
// "\x8C\x8A\xC0\xC1\xC2\xC3\xC4".
// "\xC5\xC6\xC7\xC8\xC9\xCA\xCB".
// "\xCC\xCD\xCE\xCF\xD0\xD1\xD2".
// "\xD3\xD4\xD5\xD6\xD8\xD9\xDA".
// "\xDB\xDC\xDD\xDE\x9F");
}



function fecha_espanol2(){
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
$fecha="a los ".date("d")." días del mes de ".$a[date(n)]." de ".date("Y")."";
return $fecha; 
	
	}
?>