<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : Config.class.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creación      : 25/02/2008
		Objetivo / Resumen  : Definir las BBDD con sus argumentos de conexion.
		Ultima Modificación : 25/02/2008
------------------------------------------------------------------------------------------------ */
class Dns{
	public $dns = array (
				
				"sidial" => array ( 
							"dbhost" => "SIDIAL_SRV",
							"db"     => "sidialdb",
							"dbuser" => "consultaphp",
							"dbpass" => "a123456",
							"type" => "sybase"
				),
				"desarrolloRRHH" => array ( 
							"dbhost" => "10.4.15.55",
							"db"     => "desarrolloRRHH",
							"dbuser" => "desarrollorrhh",
							"dbpass" => "a123456",
							"type" => "pgsql"
				),
				"rrhh" => array ( 
							"dbhost" => "10.4.15.55",
							"db"     => "rrhhdb",
							"dbuser" => "actualiza",
							"dbpass" => "a123456",
							"type" => "pgsql"
				),
				"web" => array ( 
							"dbhost" => "10.4.15.55",
							"db"     => "webdb",
							"dbuser" => "desarrollorrhh",
							"dbpass" => "a123456",
							"type" => "pgsql"
				),
			
				"siacaweb" => array (
							"dbhost" => "SIDIAL_SRV",
							"db"     => "siacadb",
							"dbuser" => "siaca_web",
							"dbpass" => "d34t89w1",
							"type" => "sybase"
				),
				"sidial2" => array ( 
							"dbhost" => "SIDIAL_SRV",
							"db"     => "sidialdb",
							"dbuser" => "rrhhweb",
							"dbpass" => "@58tm*18-",
							"type" => "sybase"
				),
				
				"system" => array ( 
							"dbhost" => "10.4.15.55",
							"db"     => "webdb",
							"dbuser" => "actualiza",
							"dbpass" => "a123456",
							"type" => "pgsql"
				),
				"cestaphp" => array (
							"dbhost" => "SIDIAL_SRV",
							"db"     => "sidialdb",
							"dbuser" => "cestaphp",
							"dbpass" => "p15@L32*",
							"type" => "sybase"
				)
 );
 	public function getDNS($db){
		return $this->dns[$db];
 }

}
?>
