<?php require_once "../Jeeves.php";
	$J = new Jeeves("View");


	$action = isset($_GET['action']) ? $_GET['action'] : 'main';	
	function_exists($action) ? $action() : error("Cannot find action");
	
	exit(0);

	function main() {
		global $J;
		models("Tab", "Guitar");
		$J->theme("classic");
		$J->css("main");
		$J->javascript("View");
		
		// Find the tabs that match the song
		if(!isset($_GET['t'])) error("No song or artist specified");
		
		models("Song", "Artist");
		$data = explode("_", $_GET['t']);
		if(empty($data)) error("Error with your URL");
		
		$a_name = ucwords(str_replace("-", " ", $data[1]));
		$artist = @array_pop(Artist::find($a_name, 1, true));
		if(empty($artist)) error("Couldn't find your Artist");
		$J->to("t","artist", $a_name);
		
		$s_name = ucwords(str_replace("-", " ", $data[0]));
		$tab = @array_pop(Song::find($s_name, $artist['id'], 1, true));
		if(empty($tab)) error("Couldn't find your Tab");
		$J->to("tp", "song", $s_name);
		
		$tabs = Tab::findAll($tab['id'], $tab['artist_id']);
		if(empty($tabs)) error("No tabs");
	
		// $J->to("t","artist", $a_name);
		// $J->to("tp", "song", $s_name);
	
		// Find tab information
		$tab = Tab::find($tabs[0]);
		// Grab all the guitars for the given tab
		$guitars = Guitar::find($tabs[0]);
		
		if(count($tabs) == 1)	
			array_shift($tabs);
		
		// Temporary!
		$J->to("t", "login_btn", '');
		$J->to("t", "signup_btn", 'down');
		
		$J->to("t", "title", "View");
		$J->to("pj", "alternatives", $tabs);		
		$J->to("t", "scale", $tab->data["scale"]);		
		$J->to("t", "capo", $tab->data["capo"]);
		$J->to("pt", "num_ratings", $tab->data["num_ratings"]);
		$J->to("j", "rating", $tab->data["rating"]);
		$J->to("jt", "id", $tab->data["id"]);
		
		// print_R($guitars);
		
		// Give the guitars to the template
		$J->to("pt", "guitars", $guitars);
				
		$J->display();				
	}

	function rate() {
		models("Tab");
		
		// Echoes back the new rating
		echo Tab::rate($_POST);
	}
	
	function alternative() {
		$id = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];
		if(!isset($id)) error("Cannot find your ID");
		
		models("Tab", "Guitar");
		global $J;
		
		$tab = Tab::find($id);
		$J->to("p", "alternatives", json_decode($_POST['alternatives']));		
		$J->to("t", "scale", $tab->data["scale"]);		
		$J->to("t", "capo", $tab->data["capo"]);
		$J->to("pt", "num_ratings", $tab->data["num_ratings"]);
		
		echo $J->fetch("menu", true);
		echo "<!--Split-->";
		
		$guitars = Guitar::find($id);

		// Pass guitars to view
		$J->to("p", "guitars", $guitars);
		
		// Convert tabs to something useful for javascript
		echo $J->fetch("guitar", true);
		echo "<!--Split-->";
		echo $tab->data['rating'];
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