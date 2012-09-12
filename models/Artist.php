<?php 

class Artist {
	public $artist;
	public $id;
	public static $tableName = "Artists";
	
	// Class Constructor
	function __construct($artist, $ID) {
		$this->artist = $artist;
		$this->id = $ID;
	}
	
	public static function add($artist) {
		global $database;

		$database->hi();
		$artist = $database->clean($artist);
		
		$sql = "INSERT INTO ".self::$tableName." (artist) VALUES ('{$artist}');";
		$results = $database->query($sql);
		$database->bye();
		
		return new Artist($artist, $database->id);
	}
	
	public static function find($query, $limit=1, $strict=false) {
			global $database;
			$database->hi();

			$query = $database->clean($query);

			// Find artists for the query
			$strict = ($strict) ? "" : "%";
			$sql = "SELECT * FROM ".self::$tableName." WHERE artist LIKE '{$query}$strict' LIMIT $limit";
			
			$artists = $database->query($sql);

			$database->bye();

			if($database->num_rows == 1) $artists = array($artists);
			
			return $artists;
	}
	
	public static function findByID($id) {
		global $database;
		$database->hi();

		// Find artists for the query
		$sql = "SELECT artist FROM ".self::$tableName." WHERE id = $id";

		$artist = $database->query($sql);
		$database->bye();
		
		return $artist;
	}
}

//print_R( Artist::find("jac") );

?>