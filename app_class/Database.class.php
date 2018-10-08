<?php
/*  --------------------------------------------------------------------------------------------
		Nombre              : Database.class.php
		Autor               : Ing. Oscar Amesty V.
		Fecha Creación      : 25/02/2008
		Objetivo / Resumen  : Clase que maneja las funciones para Acceder a BBDD
		                      permite conexiones con Sybase,Postgres, MySql
		Ultima Modificación : 25/02/2008
------------------------------------------------------------------------------------------------ */
include_once('Config.class.php'); // configuracion de base de datos

class Db {

	private $conexion = 0;
	private $host, $user, $pass, $db, $type, $resultset;
	
//---------------------------------------------------------	Variables y Definiciones para Sybase
	private $sybaseMsgnumber = 0;
	private $sybaseSeverity = 0;
	private $sybaseState = 0;
	private $sybaseLine = 0;
	private $sybaseText = "";

	function sybaseMsgHandler($msgnumber, $severity, $state, $line, $text)  {
		$this->sybaseMsgnumber = $msgnumber;
		$this->sybaseSeverity = $severity;
		$this->sybaseState = $state;
		$this->sybaseLine = $line;
		$this->sybaseText = $text;
	}

	function sybaseMsgErr(){
		var_dump($this->sybaseMsgnumber, $this->sybaseSeverity, $this->sybaseState, $this->sybaseLine, $this->sybaseText);
	}
//---------------------------------------------------------	Variables y Definiciones para MySql
	private $mysqlText = "";

	function mysqlMsgErr(){
	}
//---------------------------------------------------------	Variables y Definiciones para PostGresSql
	private $pgsqlText = "";

	function pgMsgErr($sql){
		//echo $sql;
	}
//---------------------------------------------------------------
	function query($sql) {
		if($_SESSION["debug"] == "debug")	echo $sql;
		switch ($this->type) {
			case "sybase":
				$r = sybase_query($sql, $this->conexion) or $this->sybaseMsgErr();
				break;
			case "mysql":
				$r = mysql_query($sql, $this->conexion) or $this->mysqlMsgErr();
				break;
			case "pgsql":
				$r = pg_query($this->conexion, $sql) or $this->pgMsgErr($sql);
				break;
		}
		if(!$r){
			$this->resultset = array(array());
			 return -1;
		}
		else {
			$this->resultset = $r;
			return $this->getNumRows();
		}
		
	}

	function fetch($i) {
		if ($i < 0) return "";
		if($this->getNumRows()>0) {
		switch ($this->type) {
			case "sybase":
				sybase_data_seek($this->resultset, $i); // or sybase_msg_err(); 
				$fila = sybase_fetch_array($this->resultset);
				break;
			case "mysql":
				mysql_data_seek($this->resultset, $i); // or mysql_msg_err(); 
				$fila = mysql_fetch_array($this->resultset);
				break;
			case "pgsql":
				$fila = pg_fetch_array($this->resultset, $i); // or pg_msg_err(); ;
				break;
		}
		} else $fila = array();
		return $fila;
	}
	
	function getNumRows() {
//        if(is_null($this->resultset) || count($this->resultset) < 1) $total = $this->getAffectedRows();
        if($this->getAffectedRows() > 0) $total = $this->getAffectedRows();
        else
            switch ($this->type) {
                case "sybase":
                    $total = sybase_num_rows($this->resultset);
                    break;
                case "mysql":
                    $total = mysql_num_rows($this->resultset);
                    break;
                case "pgsql":
                    $total = pg_num_rows($this->resultset);
                    break;
            }
		return $total;
	}

    function getAffectedRows() {
		switch ($this->type) {
			case "sybase":
				$total = sybase_affected_rows($this->conexion);
				break;
			case "mysql":
				$total = mysql_affected_rows($this->conexion);
				break;
			case "pgsql":
				$total = pg_affected_rows($this->resultset);
				break;
		}
		return $total;
	}

	function getNumCols() {
		switch ($this->type) {
			case "sybase":
				$total = sybase_num_fields($this->resultset); 
				break;
			case "mysql":
				$total = mysql_num_fields($this->resultset); 
				break;
			case "pgsql":
				$total = pg_num_fields($this->resultset); 
				break;
		}
		return $total;
	}

	function getColumnsNames(){
	/*  blob:         $meta->blob
		max_length:   $meta->max_length
		multiple_key: $meta->multiple_key
		name:         $meta->name
		not_null:     $meta->not_null
		numeric:      $meta->numeric
		primary_key:  $meta->primary_key
		table:        $meta->table
		type:         $meta->type
		default:      $meta->def
		unique_key:   $meta->unique_key
		unsigned:     $meta->unsigned
		zerofill:     $meta->zerofill
	*/
		switch ($this->type) {
			case "sybase":
				for($i = 0;$i < sybase_num_fields($this->resultset); $i++){
				    $meta = sybase_fetch_field($this->resultset, $i);
				    $cols[] = $meta->name;
				}
				break;
			case "mysql":
				for($i = 0;$i < mysql_num_fields($this->resultset); $i++){
				    $meta = mysql_fetch_field($this->resultset, $i);
				    $cols[] = $meta->name;
				}
				break;
			case "pgsql":
				for($i = 0;$i < pg_num_fields($this->resultset); $i++){
				    $meta = pg_field_name($this->resultset, $i);
				    $cols[] = $meta;
				}
				break;
		}
		return $cols;		
	}

	function getColumnsLen(){
	/*  blob:         $meta->blob
		max_length:   $meta->max_length
		multiple_key: $meta->multiple_key
		name:         $meta->name
		not_null:     $meta->not_null
		numeric:      $meta->numeric
		primary_key:  $meta->primary_key
		table:        $meta->table
		type:         $meta->type
		default:      $meta->def
		unique_key:   $meta->unique_key
		unsigned:     $meta->unsigned
		zerofill:     $meta->zerofill
	*/
		switch ($this->type) {
			case "sybase":
				for($i = 0;$i < sybase_num_fields($this->resultset); $i++){
				    $meta = sybase_fetch_field($this->resultset, $i);
				    $cols[] = $meta->max_length;
				}
				break;
			case "mysql":
				for($i = 0;$i < mysql_num_fields($this->resultset); $i++){
				    $meta = mysql_fetch_field($this->resultset, $i);
				    $cols[] = $meta->max_length;
				}
				break;
			case "pgsql":
				for($i = 0;$i < pg_num_fields($this->resultset); $i++){
				    $meta = pg_field_prtlen($this->resultset, $i);
				    $cols[] = $meta;
				}
				break;
		}
		return $cols;		
	}
	
	function getColumnName($index){
		switch ($this->type) {
			case "sybase":
			    $meta = sybase_fetch_field($this->resultset, $index);
				break;
			case "mysql":
			    $meta = mysql_fetch_field($this->resultset, $index);
				break;
			case "pgsql":
			    $meta->name = pg_field_name($this->resultset, $index);
				break;
		}
	    return $meta->name;
	}
	

	function destroy() {
		$this->freeResult();
		$this->close();
	}
	
	function __construct($bd) {
		$d = new Dns();
		$this->host = $d->dns[$bd]['dbhost'];
		$this->user = $d->dns[$bd]['dbuser'];
		$this->pass = $d->dns[$bd]['dbpass'];
		$this->db   = $d->dns[$bd]['db'];
		$this->type = $d->dns[$bd]['type'];
		switch ($this->type) {
			case "sybase":
				//sybase_set_message_handler(array($this, 'sybaseMsgHandler'));   // elimina y controla los mensajes de la base de datos
				$this->conexion = sybase_pconnect($this->host,$this->user,$this->pass)
					or die('No Conectado:'.$this->sybaseMsgErr());
				break;
			case "mysql":
				$this->conexion = mysql_connect($this->host,$this->user,$this->pass);
//					or die('No Conectado:'.mysql_error());
				break;
			case "pgsql":
				$this->conexion = pg_pconnect("host=$this->host dbname=$this->db user=$this->user password=$this->pass");
	//				or die('No Conectado:'.pg_error());
				break;
			default:
				$this->conexion = false;
				break;
		}
		if(!$this->conexion) { 
			echo "Error de $this->type: No se pudo conectar con el servidor $bd.";
			return -1;
		}

		switch ($this->type) {
			case "sybase":
				$r = sybase_select_db($this->db, $this->conexion);
				break;
			case "mysql":
				$r = mysql_select_db($this->db, $this->conexion);
				break;
			case "pgsql":
				$r = true;
				break;
			default:
				$this->conexion = false;
				break;
		}
		if(!$r) {
			echo "Error de $this->type: No se pudo conectar con BD:$this->db.";
			return -1;
		}
	}

	function freeResult() {
		switch ($this->type) {
			case "sybase":
				sybase_free_result($this->resultset);
				break;
			case "mysql":
				mysql_free_result($this->resultset);
				break;
			case "pgsql":
				pg_free_result($this->resultset);
				break;
		}

	}

	function close() {
		switch ($this->type) {
			case "sybase":
				sybase_close($this->conexion);
				break;
			case "mysql":
				mysql_close($this->conexion);
				break;
			case "pgsql":
				pg_close($this->conexion);
				break;
		}
	}
	
	function getResultSet(){
		for($i=0 ; $i< $this->getNumRows() ; $i++){
			$fila = $this->fetch($i); 
			for($j=0 ; $j< $this->getNumCols() ; $j++){
				$resultset[$i][$this->getColumnName($j)] = $fila[$this->getColumnName($j)];
			}
		}
		return $resultset;		
	}
	
}
?>
