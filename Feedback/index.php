<?php require_once "../Jeeves.php";
	$J = new Jeeves("Feedback");

	$action = isset($_GET['action']) ? $_GET['action'] : 'main';	
	function_exists($action) ? $action() : error("Cannot find action");
	
	exit(0);
	
	function main() {
		global $J;
		$J->theme("classic");
		$J->css("main");
		$J->javascript("Feedback");
		
		$J->to("t", "title", "Feedback");
		
		// Temporary!
		$J->to("t", "login_btn", '');
		$J->to("t", "signup_btn", 'down');
		
		
		$J->display();
	}
	
	function submit() {
		$feedback = $_POST['feedback'];
		$name = ucwords($_POST['name']);
		$email = $_POST['email'];
		
		// $feedback .= "\n\n Email $name back at $email.";
		
		return mail("lucidtabs@gmail.com", "Feedback from $name:", $feedback, "Reply-To: $email");
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
	
	// function error($msg=false) {
	// 	if($msg) {
	// 		echo "<center><h3>The follow error occurred: <span style='color:#DA0000'>$msg</span>!</h3></center>";
	// 		exit(1);
	// 	}
	// 	else {
	// 		echo "<center><h3>An error has occurred!</h3></center>";
	// 		exit(1);
	// 	}
	// }
?>