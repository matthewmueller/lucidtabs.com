<?php

class User {
	// Instance Variables
	public $data;
	protected static $tableName = "Users";
	
	// Class Constructor
	function __construct($data) {
		$this->data = $data;
	}
	
	public static function find($id=null) {
		if(!isset($id) || !(is_numeric($id) || is_array($id))) return null;
		global $database;
		$table = self::$tableName;
		$database->hi();
		
		if(is_array($id)) {
			$ids = array();
			$query = "SELECT * FROM `$table` WHERE ";
			foreach ($id as $userID) {
				$ids[] = "id = $userID";
			}
			$query .= implode(" OR ", $ids);
			$query .= " LIMIT ".count($id).";";
			$usersData = $database->query($query);
			$database->bye();
			
			if(empty($usersData)) return false;
			
			$Users = array();
			foreach ($usersData as $userData) {
				$Users[] = new User($userData);
			}
			
			return $Users;
		}
		else {
			$query = "SELECT * FROM `$table` WHERE id = $id LIMIT 1;";
			$data = $database->query($query);
			$database->bye();
			
			if(empty($data)) return false;
			return new User($data);
		}
	}
	
	public static function create($data) {
		global $database;
		$table = self::$tableName;
		$database->hi();
		
		$email = $data['email'];
		$pass = $data['password'];
		$name = $data['name'];
		
		$email = $database->clean($email);
		$pass = $database->clean($pass);
		$name = $database->clean($name);
		
		$pass = sha1($pass);
		
		$sql = "SELECT id FROM $table WHERE email = '{$email}' LIMIT 1";
		$rs = $database->query($sql);
		if(!empty($rs)) return -1;
		
		$sql = "INSERT INTO  `$database->database`.`$table` (`id` , `email` , `password` , `name` ) VALUES (NULL ,  '$email',  '$pass',  '$name');";
		
		$database->query($sql);
		$database->bye();
		
		return $database->id;	
	}
	
	public static function authenticate($data) {
		global $database;
		$table = self::$tableName;
		$database->hi();
		$username = $database->clean($data['email']);
		$password = $database->clean($data['password']);
		$password = sha1($password);
		
		$sql = "SELECT * FROM $table WHERE email = '{$username}' AND password = '{$password}' LIMIT 1";
		
		$user = null;
		$result = ($database->query($sql));
		
		if (isset($result['id'])) {
			$user = $result;
		}
		
		$database->bye();
		
		return $user;
	}
}
?>