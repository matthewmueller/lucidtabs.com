<?php

class Database {
	public $conn;
	private $user;
	private $password;
	public $database;
	public $id;
	public $num_rows;
	
	function __construct($user='mattmuel_lucid', $password='Goodluck2009', $database='mattmuel_Lucid') {
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
		$this->rs = null;
	}
	
	public function hi() {
		$this->conn = @mysql_connect("localhost", $this->user, $this->password)
			or die ("Unable to connect to SQL Database with username: $this->user & password: $this->password");
			
		@mysql_select_db ($this->database, $this->conn) or die ("Err: Connection Error");
	}
	
	public function query($sql) {
		$rs = @mysql_query($sql, $this->conn) or die("Query Error: ".mysql_error());
		$this->id = @mysql_insert_id($this->conn);
		$this->num_rows = @mysql_num_rows($rs);
		
		if($this->num_rows == 0) return array();
		
		return ($this->num_rows > 1) ? $this->fetchAll($rs) : $this->fetch($rs);
	}
	
	public function clean($value) {
		$magic_quotes_active = get_magic_quotes_gpc();
		if($magic_quotes_active) $value = stripslashes($value);
		
		return mysql_real_escape_string($value);
	}
	
	private function fetch($rs) {
		if(!isset($rs)) return null;
		
		$row = @mysql_fetch_assoc($rs);

		if(@mysql_num_fields($rs) == 1) {
			return @array_pop(array_values($row)); 
		}
			
		return $row;
	}
	
	private function fetchAll($rs) {
		if(!isset($rs)) return null;
		$rows = array();
		
		if(@mysql_num_fields($rs) == 1) {
			while($row = @mysql_fetch_assoc($rs))
				$rows[] = @array_pop(array_values($row));
		}
		else {
			while($row = @mysql_fetch_assoc($rs))
				$rows[] = $row;
		}
		
		return $rows;
	}
	
	public function bye() {
		@mysql_close($this->conn);
	}
	
	public function __toString() {
		return "Database Object";
	}
}

$database = new Database();

?>