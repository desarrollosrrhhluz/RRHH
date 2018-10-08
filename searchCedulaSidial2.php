<?php
include_once("../FrameWork/Include/Defines.inc.php");
include_once(LUZ_PATH_CLASS.'/DataStore.class.php');
 $db  = "sidial";
 $db2 = "rrhh";
global $dbs,$dbr;
   header( "Cache-Control: no-store, no-cache, must-revalidate" ); 
   header( "Cache-Control: post-check=0, pre-check=0", false );
   header( "Pragma: no-cache" );
   error_reporting(0); 

   $id=$_REQUEST['id'];  


 $sql2 =  "select NOMBRES from V_DATPER where CE_TRABAJADOR=$id and ESTATUS='A'";

       $ds     = new DataStore($db, $sql2);
           if($ds->getNumRows()<=0){
               
			   echo "";
               }else{
			  
				
            $fila = $ds->getValues(0);
			echo utf8_encode($fila['NOMBRES']);
	
			   }

?>