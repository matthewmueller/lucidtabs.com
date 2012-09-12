<?php require_once "../Jeeves.php";
	$J = new Jeeves("Create");


	$action = isset($_GET['action']) ? $_GET['action'] : 'main';	
	$view = view();
	
	function_exists($action) ? $action($view) : error();
	
	exit(0);
	
	function main($view = null) {
		global $J;
		
		$J->theme("classic");
		$J->css("tab, guitar, strut, stretchable, _Search");
		$J->javascript("Create, Guitar, Strut, Stretchable");
		
		// Get the Guitar file
		ob_start();
		require_once("includes/guitar.php");
		$guitar_tpl = ob_get_clean();
		// Clean up the HTML & prep to send to Javascript
		$guitar_tpl = str_replace(array("\r","\n","\t"), "", $guitar_tpl);
		$guitar_tpl = addslashes($guitar_tpl);
		
		$J->to("t", "title", "Create");
		// Send to javascript
		$J->to("j", "guitar_tpl", $guitar_tpl);
				
		// Temporary!
		$J->to("t", "login_btn", '');
		$J->to("t", "signup_btn", 'down');
		$J->to("t", "new", $view['new']);
		$J->to("t", "find", $view['find']);
				
		$J->display();
	}
	
	function view() {
		$view = array();
		// Defaults
		$view['new'] = 'none';
		$view['find'] = '';
		if(!isset($_GET['view'])) return $view;
		
		if ($_GET['view'] == "new") {
			$view['new'] = '';
			$view['find'] = 'none';
		}	
		
		return $view;
	}
	
	function create() {
		models("Artist, Song, Tab, Session");
		
		if(isset($_POST['artist']) && isset($_POST['song'])) {
			// Get the artist and route him to the Artist Model
			$Artist = Artist::add($_POST['artist']);
			// Get the song and route him to the Song Model
			$Song = Song::add($_POST['song'], $Artist->id);
			
			// Unset both of them.
			unset($_POST['artist']);
			unset($_POST['song']);
			// Send ids to Tab
			$_POST['artist_id'] = $Artist->id;
			$_POST['song_id'] = $Song->id;
		}

		// Send the rest of the post information to model.
		if($_POST) {
			$user_data = Session::require_login();
			$_POST['user_id'] = $user_data['id'];
			
			$uniqueID = Tab::save($_POST);
			
			// Send back to Javascript.
			echo $uniqueID;
		}
	}
	
	function save() {
		models("Guitar, Session");
		
		// Parse JSON into associative array - dunno how stripslashes is so intelligent.. but rock on!
		// Ahh. Probably only strips one set of slashes each. So '//' becomes '/' - Genius!
		$_POST['guitars'] = stripslashes($_POST['guitars']);
		$_POST['guitars'] = json_decode($_POST['guitars'], true);

		// Send all the post information to model. Don't need ID sent back as of now.
		$IDs = Guitar::save($_POST);

		// ID *should* always be an array but check anyways
		if(is_array($IDs))
			echo json_encode($IDs);
	
	}
	
	// Removes the tab
	function discard() {}
	
	// This removes a guitar - not the tab
	function remove() {
		models("Guitar");
		Guitar::remove($_POST['id']);
		echo "Successfully Removed";
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