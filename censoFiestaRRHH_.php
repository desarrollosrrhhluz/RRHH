<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();     
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
//include('includes/Funciones.inc.php');


$cedula=$_SESSION['cedula'];
$sexo=$_SESSION['sexo'];
$_SESSION['tipopersonal'];
$nombreTrabajador=$_SESSION['nombres'];
$tipop=substr($_SESSION['tipopersonal'], 0,1 );
$anno=$_SESSION['ano_proceso']=2016;
$op= $_REQUEST['op'];

$dbr="rrhh";
//***************************************** 
     switch($op) { 
	 //***********************************	 
	         case 'consultaCenso':
	         consultaCenso();
			 break;	
			 case 'nuevo':
       $id=$_REQUEST['id'];
       $id2=$_REQUEST['id2'];
	     nuevo($id, $id2);
			 break;
			
			 
			  
			 }

//****************************************

function  consultaCenso(){
global $dbr, $cedula;
$sql =  "select * from public.tab_fiesta_rrhh where ce_trabajador=$cedula";
        $ds2     = new DataStore($dbr, $sql);
      if($ds2->getNumRows()<=0){//No se ha censado
     echo utf8_encode('<div class="text-left "><h3 class="text-success">A continuación, deberá elegir cuál de los siguientes horarios es el de su preferencia:</h3><br>
      <form id="form" method="post" accept-charset="utf-8">
      <div class="text-left" >
      <input type="radio" name="opcion" value="1" required> De 7:30 am a 2:30 pm <br>
      <input type="radio" name="opcion" value="2" required> De 8:00 am a 3:00 pm <br>
      </div>
      <h3 class="text-success">¿Es imprescindible para usted ausentarse a la hora del mediodía, lo cual le impediría cumplir con la jornada corrida?</h3><br>
      <div class="text-left" >
      <input type="radio" name="opcion2" value="3" required> Si <br>
      <input type="radio" name="opcion2" value="4" required> No<br>
      </div>

      <div class="text-center">
      <button type="button" id="nuevo" class="btn btn-lg btn-primary"><i class="glyphicon glyphicon-inbox"></i> Votar</button>
      </div>
      
    </form>
      </div>');
      }else{// Censado
     echo '<div class="text-center"><h2>Ya participaste en la encuesta!</h2>';
      }


}

function  nuevo($id,$id2){
global $dbr, $cedula;

$sql =  " INSERT INTO public.tab_fiesta_rrhh (ce_trabajador, respuesta) VALUES ($cedula, $id)";
$sql2 =  " INSERT INTO public.tab_fiesta_rrhh (ce_trabajador, respuesta) VALUES ($cedula, $id2)";
$ds = new DataStore($dbr, $sql);
$ds2 = new DataStore($dbr, $sql2);

     

}

?>