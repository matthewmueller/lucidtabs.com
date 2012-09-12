<?php 

class Song {
	public $song;
	public $artistID;
	public static $tableName = "Songs";
	
	// Class Constructor
	function __construct($song, $songID, $artistID) {
		$this->song = $song;
		$this->artist = $artistID;
		$this->id = $songID;
	}
	
	public static function add($song, $artistID) {
		global $database;
		$database->hi();
		$song = $database->clean($song);
		$sql = "INSERT INTO ".self::$tableName." (artist_id, song) VALUES ($artistID, '{$song}');";
		$results = $database->query($sql);
		$database->bye();
		
		return new Song($song, $database->id, $artistID);
	}
	
	public static function find($query, $artistID=null, $limit=4, $strict=false) {
		global $database;
		$database->hi();
		
		$query = $database->clean($query);
		
		// Find songs for the query
		$strict = ($strict) ? "" : "%";
		$artistID = ($artistID) ? " AND artist_id=$artistID " : ""; 
		
		$sql = "SELECT id,song,artist_id FROM ".self::$tableName." WHERE song LIKE '{$query}$strict' $artistID LIMIT $limit";

		$songs = $database->query($sql);
		$database->bye();
		
		if($database->num_rows == 1) $songs = array($songs);
		return $songs;
	}
	
	public static function findByID($id) {
		global $database;
		$database->hi();
		
		// Find songs for the query
		$sql = "SELECT song FROM ".self::$tableName." WHERE id=$id";
		$songs = $database->query($sql);
		$database->bye();
		
		return $songs;
	}
}

// $song = Song::find("Rod");
// print_r( $song);

?>