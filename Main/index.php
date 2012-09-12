<?php require_once "../Jeeves.php";
	$J = new Jeeves("Main");


	$action = isset($_GET['action']) ? $_GET['action'] : 'main';	
	$view = view();
			
	function_exists($action) ? $action($view) : error();

	exit(0);
	
	function main($view=null) {
		global $J;
		$J->theme("classic");
		$J->css("main");
		$J->javascript("Main");
		$J->to("t", "title", "Welcome!");
		
		$J->to("t", "login", $view['login']);
		$J->to("t", "login_btn", $view['login_btn']);
		$J->to("t", "login_status", $view['login_status']);
		$J->to("t", "signup", $view['signup']);
		$J->to("t", "signup_btn", $view['signup_btn']);
		
		$J->display();
	}
	
	function view() {
		$view = array();
		// Defaults
		$view['login'] = 'none';
		$view['login_btn'] = '';
		$view['login_status'] = 'none';
		$view['signup'] = '';
		$view['signup_btn'] = 'down';
		if (!isset($_GET['view'])) return $view;
		if ($_GET['view']=='login') {
			$view['login'] = '';
			$view['login_btn'] = 'down';
			$view['signup'] = 'none';
			$view['signup_btn'] = '';
			
		}
		elseif($_GET['view']=='signup') {
			$view['login'] = 'none';
			$view['login_btn'] = '';
			$view['signup'] = '';
			$view['signup_btn'] = 'down';
		}
		
		elseif ($_GET['view']=='wrong') {
			$view['login'] = '';
			$view['login_btn'] = 'down';
			$view['signup'] = 'none';
			$view['signup_btn'] = '';
			$view['login_status'] = '';
		}
		
		return $view;
	}
	
	function create() {
		models("User", "Session");
		$user_id = User::create($_POST);
		
		if ($user_id < 0) {
			echo false;
			return;
		}
		
		$_POST['id'] = $user_id;
		Session::login($_POST);
		
		echo true;
	}
	
	function login() {
		models("User, Session");
		
		$user = User::authenticate($_POST);
		
		if($user) {
			Session::login($user);
			$user_data = Session::require_login($user);
			header("Location: index.php");
		} else {
			header("Location: ?view=wrong");
		}

	}
	
	function logout() {
		session_start();
		session_destroy();
		header("Location: index.php");
	}
	
	function search($q=null) {
		models("Artist, Song");
		
		$query = isset($_POST['query']) ? $_POST['query'] : $q;
		if(!$query) return;
		
		$results = Song::find($_POST['query']);
		for ($i=0; $i < count($results); $i++) { 
			$results[$i]['artist'] = Artist::findByID($results[$i]['artist_id']);
			$results[$i]['song_id'] = $results[$i]['id'];
			unset($results[$i]['id']);
		}
		
		echo json_encode($results);
	}

	// function error() {
	// 	echo "<h2>Could not find action!</h2>";
	// 	exit(1);
	// }
?>