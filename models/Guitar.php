<?php

/**
* Guitar Class
*/
class Guitar implements Model {
	// Instance Variables
	public $info;
	public $board;	
	
	// Class Constructor
	function __construct($info, $board) {
		$this->info = $info;
		$this->board = $board;
	}
	
	// Saves the POST data
	public static function save($data) {
		$data = self::split($data);
		
		// Start with empty arrays so it doesn't complain about undefined variables
		$creates = array();
		$updates = array();
				
		// Divy up the data to know which ones to update, which ones to save.
		if(!empty($data['update']))
			$updates = self::update($data['update']);
		if(!empty($data['create']))
			$creates = self::create($data['create']);
			
		return array_merge($updates, $creates);
	}
	
	// 
	public static function find($tabID) {
		global $database;
		$table = "Guitars";
		$doodads = array("Stretchables", "Struts");
				
		$database->hi();
		$sql = "SELECT `id`,`title` FROM `$table` WHERE tab_id=$tabID";
		$data = $database->query($sql);
		if($database->num_rows==1) $data = array($data);
		$guitars = array();
		
		if(empty($data)) return array();
		foreach ($data as $guitar) {
			$board = array();
			
			foreach ($doodads as $doodad) {
				if(!isset($guitar['id'])) $guitar['id'] = $guitar;
				$sql = "SELECT * FROM `$doodad` WHERE guitar_id={$guitar['id']} AND enabled=1";
			//	echo $sql."<br>";
			//	exit(0);
				$pieces = $database->query($sql);
				if(empty($pieces))
				 	$board[$doodad] = array();
				elseif($database->num_rows == 1)
					$board[$doodad] = array($pieces);
				else
					$board[$doodad] = $pieces;
			}
			// Quick substitute
			$guitars[] = array("id"=>$guitar['id'], "title"=>$guitar['title'],"board"=>array_merge($board));
		}
		$database->bye();
		return $guitars;
	}

	public static function remove($id) {
		global $database;
		$table = "Guitars";
		$database->hi();
		
		$sql = "UPDATE 
				`$database->database`.`$table`
		 		SET 
				tab_id = null
				WHERE
				id = $id";
		
		$database->query($sql);
		$database->bye();
		
		return $id;
	}

	private static function update($data) {
		global $database;
		$table = "Guitars";
		$database->hi();
		$IDs = array();

		foreach ($data as $guitar) {
			// Grab boards to do special for on it, but also remove it from $guitar array
			$board = $guitar['board'];
			unset($guitar['board']);
			
			$sql = "UPDATE  `$database->database`.`$table` SET ";
			$sqlData = array();
			
			// Grabbed guitar_id, now unset it.
			$guitar_id = $guitar['id'];
			unset($guitar['id']);
			
			foreach ($guitar as $field => $entry) {
					$entry = mysql_escape_string($entry);
					$sqlData[] = "`$field` = '$entry'"; 
			}
			
			$sql .= implode(',', $sqlData);
			$sql .= " WHERE `$table`.`id` = $guitar_id LIMIT 1";

			$database->query($sql);
			
			// All the ids from inserted doodads
			$doodad_ids = array();
			
			// Now run through all the Doodads in the guitar
			foreach ($board as $doodad) {
				// Convert doodad from json to associative array
				$doodad = json_decode($doodad, true);
				
				// Clean out Doodads
				foreach ($doodad as $field => $entry) {
					$doodad[$field] = mysql_escape_string($entry);
				}
				
				// If its set, then we update, otherwise we create
				if ($doodad['id']) {
					$doodad_type = $doodad['type'];
					$sql = "UPDATE  `$database->database`.`{$doodad_type}s` SET ";
					unset($doodad['type']);
					
					// Grabbed doodad_id, now unset it.
					$doodad_id = $doodad['id'];
					unset($doodad['id']);
					
					$sqlData = array();
					foreach ($doodad as $field => $entry) {
						$sqlData[] = "`$field` = '$entry'"; 
					}
					
					$sql .= implode(',', $sqlData);
					$sql .= " WHERE `{$doodad_type}s`.`id` = $doodad_id LIMIT 1";
					
					$database->query($sql);
				}
				else {
					$sql = " INSERT INTO  `$database->database`.`{$doodad['type']}s` ";
					
					// Used 'type' now unset it
					unset($doodad['type']);

					// Add the foreign key
					$doodad['guitar_id'] = $guitar_id;

					// Put in values for each type
					$sql .= "(`".implode('`,`', array_keys($doodad))."`)";
					$sql .= " VALUES ";
					$sql .= "('".implode('\',\'', array_values($doodad))."');";

					$database->query($sql);
					$doodad_ids[] = $database->id;
				}
			}
			$IDs[] = array( "Guitar" => $guitar_id, "Doodads" => $doodad_ids );
		}
			
		$database->bye();
		return $IDs;
	}

	private static function create($data) {
		global $database;
		$table = "Guitars";
		$database->hi();
		
		// All the updated information
		$IDs = array();
		
		foreach ($data as $guitar) {
			// Grab boards to do special for on it, but also remove it from $guitar array
			$board = $guitar['board'];
			unset($guitar['board']);
			
			// This foreach loop just cleans out the input.
			foreach ($guitar as $key => $value) {
				$guitar[$key] = mysql_escape_string($value);
			}
			
			// Add general information about the guitar to the Guitars Table
			$sql = " INSERT INTO  `$database->database`.`$table` ";
			$sql .= "(`".implode('`,`', array_keys($guitar))."`)";
			$sql .= " VALUES ";
			$sql .= "('".implode('\',\'', array_values($guitar))."');";
			$database->query($sql);
			// ID that results from query - used in the foreign keys
			$guitar_id = $database->id;
			
			// All the ids from inserted doodads
			$doodad_ids = array();
			
			// Add information about each Doodad from the board to their respected databases 
			foreach ($board as $doodad) {
				// Convert doodad from json to associative array
				$doodad = json_decode($doodad, true);
				
				// Clean out Doodads
				foreach ($doodad as $key => $value) {
					$doodad[$key] = mysql_escape_string($value);
				}

				// Send to respected database
				$sql = " INSERT INTO  `$database->database`.`{$doodad['type']}s` ";
				
				// Used 'type' now unset it
				unset($doodad['type']);
				
				// Add the foreign key
				$doodad['guitar_id'] = $guitar_id;
								
				// Put in values for each type
				$sql .= "(`".implode('`,`', array_keys($doodad))."`)";
				$sql .= " VALUES ";
				$sql .= "('".implode('\',\'', array_values($doodad))."');";
				
				$database->query($sql);
				
				$doodad_ids[] = $database->id;
			}
			
			$IDs[] = array( "Guitar" => $guitar_id, "Doodads" => $doodad_ids );
		}
		$database->bye();
		
		return $IDs;
	}
	
	// Utilities
	private static function split($data) {
		$dataArr = array("update"=>array(), "create"=>array());
		
		$tabID = $data['tab_id'];
		foreach ($data['guitars'] as $guitar) {
			$guitar['tab_id'] = $tabID;
			
			// Checks for the 
			if(isset($guitar["id"]))
				$dataArr['update'][] = $guitar;
			else {
				// Get rid of key if its null anyways.
				unset($guitar["id"]);
				$dataArr['create'][] = $guitar;
			}
		}
		
		return $dataArr;
	}
	
	public function toJSON() {
		return json_encode($this->data);
	}

	public function __toString() {
		return "Guitar Class";
	}
}



?>