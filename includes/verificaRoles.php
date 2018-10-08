<?php 
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 
  
session_start();  
  
include_once("../FrameWork/Include/Defines.inc.php");
//------------------------------------------------------------------------------ incluimos las clases e includes necesarios
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
$db="system_q";
$ci=$_SESSION['cedula'];


function verificaRoles(){
global $db, $ci;
$númargs = func_num_args();
$arg_list = func_get_args();

    for ($i = 0; $i < $númargs; $i++) {
		
$sqlverfica="select id_rol from mst_registro_usuario as a, mst_rol_usuario as b 
where a.id_user=b.id_user and a.co_cedula=$ci and b.id_rol=".$arg_list[$i]." ";
$dsx  = new DataStore($db, $sqlverfica);
if($dsx->getNumRows()<=0){  
	  if($i== $númargs-1){
		 echo "<h2>Usted NO esta Autorizado</h2><p>Usted no posee los permisos adecuados, no puede acceder a esta aplicación.</p>";
		 exit;
		  }
    }else{ 
		$_SESSION['acceso']='OK';
		 break;
		}
	}
}

?>