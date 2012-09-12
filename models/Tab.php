<?php
/**
* Tab Class
*/
class Tab implements Model {
	// Instance Variables
	public $data;
	
	// Class Constructor
	function __construct($data) {
		$this->data = $data;
	}
	
	public static function save($data) {
		return isset($data['id']) ? self::update($data) : self::create($data);
	}
	
	public static function find($id) {
		global $database;
		$table = "Tabs";
		
		$database->hi();
		$sql = "SELECT * FROM `$table` WHERE ID=$id LIMIT 1";
		$data = $database->query($sql);
		
		$database->bye();

		return new Tab($data);
	}
	
	public static function findAll($songID, $artistID, $limit=3) {
		if(!$songID || !$artistID) return;
		
		global $database;
		$table = "Tabs";
		
		$database->hi();
		$sql = "SELECT id FROM `$table` WHERE song_id=$songID AND artist_id=$artistID ORDER BY (rating * num_ratings) DESC LIMIT $limit";
		
		$data = $database->query($sql);
		
		$database->bye();
		
		$data = ($database->num_rows == 1) ? array($data) : $data;
		
		return $data;
	}
	
	public static function rate($info) {
		global $database;
		$table = "Tabs";
		$database->hi();
		
		$sql = "SELECT rating, num_ratings FROM $table WHERE id = {$info['id']}";
		$data = $database->query($sql);
		
		$num = ++$data['num_ratings'];
		$rating = (1/$num)*$info['rating'] + (($num-1)/$num)*$data['rating'];
		
		//echo "$rating = ({$info['rating']} + {$data['rating']} * $num) / ($num + 1);";
		
		
		$sql = "UPDATE $table SET `rating` = $rating,`num_ratings` = $num WHERE $table.id = {$info['id']} LIMIT 1";
		
		$database->query($sql);
		$database->bye();
		
		return $rating;
	}
	
	public static function remove($id) {}
	
	private static function update($data) {
		global $database;
		$table = "Tabs";
		$database->hi();
		
		$sql = "UPDATE  `$database->database`.`$table` SET ";
		
		$sqlData = array();
		foreach ($data as $key => $value) {
			if(!($key === 'id')) {
				$value = mysql_escape_string($value);
				$sqlData[] = "`$key` = '$value'"; 
			}
		}
		$sql .= implode(',', $sqlData);
		$sql .= " WHERE `$table`.`id` = {$data['id']} LIMIT 1";
		
		//echo $sql;
		$database->query($sql);
		$database->bye();
		
		return $data['id'];
	}
	
	private static function create($data) {
		global $database;
		$table = "Tabs";
		$database->hi();
		
		// Clean out the input.
		foreach ($data as $key => $value) {
			$data[$key] = mysql_escape_string($value);
			if(!$value) unset($data[$key]);
		}
		
		$sql = "INSERT INTO  `$database->database`.`$table` ";
		$sql .= "(`".implode('`,`', array_keys($data))."`)";
		$sql .= " VALUES ";
		$sql .= "('".implode('\',\'', array_values($data))."');";
		
		$database->query($sql);
		$database->bye();
		
		return $database->id;
	}
	
	public function toJSON() {
		return json_encode($this->data);
	}
	
	public function __toString() {
		return "Tab Class";
	}
}

// $data = array();
// $data['song'] = "Under the Bridge";
// $data['artist'] = "Red Hot Chili Peppers";
// $data['thumbnail'] = "John Mayer.jpg";
// $data['id'] = "2";
// 
// print_r(Tab::find(2));
?>