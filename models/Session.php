<?php

class Session {
	
	private $logged_in = false;
	public $user_id;
	
	// Class Constructor
	function __construct() {
		
	}
	
	public static function require_login() {
		session_start();
		if (!isset($_SESSION['user_id'])) {
			return null;
		} else {
			$id = $_SESSION['user_id'];
			$name = $_SESSION['name'];
			return (array('id' => $id, 'name' => $name));
		}
	}
	
	public static function login($user) {
		if($user) {
			session_start();
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['name'] = $user['name'];
		}
	}
	
	public function logout() {
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$this->logged_in = false;
	}
	
	private function check_login() {
		if(isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		} else {
			unset($this->user_id);
			$this->logged_in = false;
		}
	}
}

?>