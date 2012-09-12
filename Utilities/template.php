<?php

function _init($Jeeves) {
	$GLOBALS['J'] = $Jeeves;
}

function route($tag) {
	$tag = trim($tag);
	// If its a variable that needs to be passed through..
	if(substr($tag,0,1) == "$") {
		global $J;
		
		$tag = substr($tag, 1);
		$pieces = explode(".", $tag);
		$var = $J->variables["t"];

		foreach ($pieces as $piece) {
			$var = @$var[$piece];
		}
		if (!isset($var)) error(__FUNCTION__, "No such variable: {\$$tag} Error");

		return $var;
	}
	
	$pieces = explode(" ", $tag);
	$tag = array_shift($pieces);
	
	if(count($pieces) == 1) {global $J;
		$assoc = explode(":", $pieces[0]);
		if(count($assoc) >= 2) {
			$assoc[0] = trim($assoc[0], "\" ");
			$assoc[1] = trim($assoc[1], "\" ");
			$params[$assoc[0]] = $assoc[1];
		}
		else {
			$params = trim($pieces[0], "\" ");
		}
	}
	else if(count($pieces) > 1) {
		foreach ($pieces as $piece) {
			$assoc = explode(":", $piece);
			if(!isset($assoc[1])) return error(__FUNCTION__);
			$assoc[0] = trim($assoc[0], "\" ");
			$assoc[1] = trim($assoc[1], "\" ");
			$params[$assoc[0]] = $assoc[1];
		}
		
	}
	
	
	if(count($pieces) > 0)
		return $tag($params);
		// return call_user_func("template\\$tag", $params);
	else
		return $tag();
		// return call_user_func("template\\$tag");
}

function javascript($params=null, $type="1,0") {
	verify($params, $type, __FUNCTION__);
	global $J;
	$output = "";

	if(is($params, "1")) {
	 $output .= "<script src=\"".$J->JAVASCRIPT_DIR."$params.js\" type=\"text/javascript\" charset=\"utf-8\"></script>"; 
	 }

	else if(is($params, "0")) {
		$javascripts = $J->javascript;
		
		foreach ($javascripts as $javascript) {
			$output.= "<script src=\"$javascript\" type=\"text/javascript\" charset=\"utf-8\"></script>";
		}
		
		$output .= _jsInit();
	}
	
	return $output;
}

function _jsInit() {
	global $J;
	$output = "<script type=\"text/javascript\" charset=\"utf-8\">";
	
	// If there is a theme initialize the object.
	if (isset($J->theme)) {
		$class = ucwords($J->theme);
		$output .= "var Theme = new $class();";
	}
	
	$class = $J->NAME;
	$instance = substr($class,0,1);
	$output .= "var $instance = new $class();";
	
	// Give theme the master
	if (isset($J->theme))
		$output .= "Theme.master = $instance;";
		
	$output .= "$(document).ready(function() {";
	foreach ($J->variables['j'] as $key => $value) {
		if(is_string($value)) $output .= "$instance.$key = \"$value\";";
		elseif(is_array($value)) $output .= "$instance.$key = eval(".json_encode($value).");";
		elseif(is_numeric($value)) $output .= "$instance.$key = $value;";
		elseif(is_bool($value)) {
			if($value) $output .= "$instance.$key = true;";
			else $output .= "$instance.$key = false;"; 
		}
		else error(__FUNCTION__, "Cannot translate php value: $value to javascript");
	}
	
	// Append the inits
	foreach ($J->jsInits as $init) {
		ob_start();
		require_once($init);
		$output .= ob_get_clean();
	}
	$output .= "});";
	$output .= "</script>";
	return $output;
}

function css($params=null, $type="0") {
	verify($params, $type, __FUNCTION__);
	global $J;
	$output = "";
	
	// if(is($params, "0")) {
	$stylesheets = $J->stylesheets;
	foreach ($stylesheets as $stylesheet) {
		$output.="<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\" media=\"screen\" title=\"no title\" charset=\"utf-8\">";
	}
	// }

	return $output;
}

function master($params=null, $type="0") {
	verify($params, $type, __FUNCTION__);
	global $J;
	$J->CURRENT = $J->NAME;
	return $J->fetch();
}

function i($file, $dir = null, $type="1") {
	verify($file, $type, __FUNCTION__);
	global $J;
	
	// A directory will be supplied if there is a partial present
	if($dir) {
		$file = $dir."$file.php";
		return $J->fetch($file);
	}
	else 
		return $J->fetch($file, true);
}

function partial($partial, $type="1") {
	verify($partial, $type, __FUNCTION__);
	global $J;
	$partial = ucwords($partial);
	$dir = $J->PARTIALS_DIR."$partial/";
	$J->partial($partial);
	$J->to("t", "caller", $J->CURRENT);
	return i("_", $dir);
}


function is($params, $type = null) {
	if($type == null) return true;
	
	// if($params) {
	// 	print_r($params);
	// 	echo "<br/>";
	// }
	
	elseif($type == "0") {
		if($params) return false;
		else return true;
	}

	// Verify that $params only holds a string (one parameter)
	elseif($type == "1") {
		// if(is_string($params)) echo "True";
		// else echo "false";
		if(is_string($params)) return true;
		else return false;
	}

	// Verify that $params is an associative array
	elseif(substr($type, 0, 1) == "A") {
		$min = substr($type, 1);
		if(!is_array($params)) return false;
		if(!$min) return true;
		if(count($params) < $min) return false;

		return true;
	}
	
	return false;
}

function verify($params, $type=null, $fn = null) {
	$error = null;
	if (!$type) return;
	$types = explode(",", $type);
	$errors = array();
	foreach ($types as $type) {
		// Verify that there are no parameters
		// echo "<hr/>Calling is ($type): <br/>";
		if(is($params, $type)) return;
	}
	
	error($fn);
}

function error($fn, $reason="Templating Error") {
	global $J;
	$J->error($reason, $fn);
}

?>