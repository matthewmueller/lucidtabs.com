<?php

function models() {
	$models = func_get_args();
	if(count($models) == 0) return;
	elseif(count($models) == 1) $models = explode(",", $models[0]);
	
	if(!isset($GLOBALS['database'])) {
		// Autoload the database & Model interface
		require_once("../models/Database.php");
		require_once("../models/Model.php");
		$GLOBALS['database'] = $database;
	}
	
	foreach ($models as $model) {	
		$model = trim($model);
		require_once "../models/$model.php";
	}
	
}

/**
* Jeeves Class
*/
class Jeeves
{
	public $NAME = "";
	public $CURRENT = "";
	public $INCLUDES_DIR = "";
	private $CSS_DIR = "";
	public $JAVASCRIPT_DIR = "";
	public $PARTIALS_DIR = "";
	public $THEMES_DIR = "";
	public $UTILS_DIR = "";
	public $theme = null;
	public $master = "";
	public $stylesheets = array();
	public $javascript = array();
	public $includes = array();
	public $variables = array("j"=>array(), "p"=>array(), "t"=>array());
	public $jsInits = array();
	private $themeRendered = false;
	
	function __construct($name)
	{
		$this->NAME = $name;
		$this->UTILS_DIR = "../Utilities/";
		$this->INCLUDES_DIR = "includes/";
		$this->CSS_DIR = "css/";
		$this->JAVASCRIPT_DIR = "javascript/";
		$this->PARTIALS_DIR = "../partials/";
		$this->THEMES_DIR = "../Themes/";
		
		// Autoload some stuff //
		$this->_autoload();
	}
	
	private function _autoload() {
		$jQuery = array("jquery", "jquery-ui", "bind", "jquery.json","jquery.corners");
		$javascripts = array("utils", "google_analytics");
		
		foreach ($jQuery as $javascript) {
			$this->javascript[] = $this->UTILS_DIR."jQuery/$javascript.js";
		}
		
		foreach ($javascripts as $javascript) {
			$this->javascript[] = $this->UTILS_DIR."$javascript.js";
		}
		
		// Append the init file
		$this->jsInits[] = $this->JAVASCRIPT_DIR.'_init.js';
	}
	
	public function css() {
		$stylesheets = $this->parse(func_get_args());
		
		foreach ($stylesheets as $css) {
			$css = trim($css);
			$this->stylesheets[] = $this->CSS_DIR."$css.css";
		}
	}
	
	public function theme($theme = null) {
		if(!isset($theme)) $this->error("Could not find theme!", __FUNCTION__);
		if($theme > 1) $this->error("Too many themes applied!", __FUNCTION__);
		$dir = $this->THEMES_DIR.$theme.'/';
		
		$this->theme = $theme;
		$this->javascript[] = $dir.ucwords($theme).'.js';
		$this->stylesheets[] = $dir.$theme.'.css';
		// Theme init.js will be called before other init - More hierarchical?
		array_unshift($this->jsInits, $dir.'_init.js');
	}
	
	public function javascript() {
		$javascripts = $this->parse(func_get_args());
		
		foreach ($javascripts as $javascript) {
			$javascript = trim($javascript);
			$this->javascript[] = $this->JAVASCRIPT_DIR."$javascript.js";
		}
	}
	
	// Send to-   a: all   t: template   p: php   j: javascript
	public function to($str="tpj", $key, $value) {
		if($str == "" || !isset($str) || $str=="a") $str = "tpj";
		for ($i=0; $i < strlen($str); $i++) { 
			$this->variables[$str[$i]][$key] = $value;
		}
	}
	
	public function partial($partial) {
		$dir = $this->PARTIALS_DIR."$partial/";
		$this->javascript[] = $dir."_.js";
		$this->stylesheets[] = $dir."_.css";
		
		if($this->CURRENT == "Theme")
			$this->stylesheets[] = $this->THEMES_DIR.$this->theme.'/_'.$partial.'.css';
		else
			$this->css("_$partial");
	}
	
	private function extract($str, $s="{", $e="}", $tags=null, $o=0) {
		$tags = ($tags) ? $tags : array();
		$pos = stripos($str, $s, $o);
		if($pos===false) return $tags;
		$str = substr($str, $pos);
		$str_two = substr($str, strlen($s));
		$second_pos = stripos($str_two, $e);
		$str_three = substr($str_two, 0, $second_pos);

		$tags[] = $str_three;
		
		return $this->extract($str, $s, $e, $tags, $second_pos);
	}
	
	private function inject($str, $content, $tags) {
		return @str_replace($tags, $content, $str);
	}
	
	private function render($content="master.php") {
		require_once $this->UTILS_DIR."template.php";
		
		if(isset($this->theme) && !$this->themeRendered) {
			$content = $this->THEMES_DIR.$this->theme.'/'.$this->theme.'.php';
			// Will be taken care of right away, then nullify it so we don't keep rendering it.
			$this->themeRendered = true;
		}
		
		$this->CURRENT = ucwords($this->getCurrent($content));
		// Allow variables to be used in the PHP and the HTML
		extract($this->variables["p"], EXTR_OVERWRITE);	

		ob_start();
		require_once($content);
		$content = ob_get_clean();
				
		$tags = $this->extract($content);
		
		$originals = array();
		$replacements = array();

		// Initialize template (put Jeeves in template)
		// template\_init($this);
		_init($this);
		
		$cssTag = false;
		$jsTag = false;

		foreach ($tags as $tag) {
			if($tag == "css") {$cssTag=true; continue;}
			if($tag == "javascript") {$jsTag=true; continue;}
			
			$originals[] = '{'.$tag.'}';			
			// $replacements[] = call_user_func('template\route', $tag);
			$replacements[] = route($tag);
		}
		
		// Append to the end.
		if($cssTag) {
			$originals[] = '{css}';
			// $replacements[] = call_user_func('template\route', "css");
			$replacements[] = route("css");
			
		}
		
		if($jsTag) { 
			$originals[] = '{javascript}';
			// $replacements[] = call_user_func('template\route', "javascript"); 
			$replacements[] = route("javascript");
		}		
		
		$output = $this->inject($content, $replacements, $originals);
		return $output;
	}
	
	public function fetch($content="master.php", $include=false) {
		if($include) $content = $this->NAME."/".$this->INCLUDES_DIR."$content.php";
		return $this->render($content);
	}
	
	public function display($content="master.php", $include=false) {
		if($include) $content = $this->NAME."/".$this->INCLUDES_DIR."$content.php";
		echo $this->render($content);
	}
	
	public function parse($args) {
		if(count($args) == 0) return array();
		elseif(count($args) == 1) return explode(",", $args[0]);
		else return $args;
	}
	
	private function getCurrent($current) {
		// If its a theme just return it as a theme.
		if(strpos($current, "Theme")) return "Theme";
		
		$output = "";
		$fileTypes = array("php", "html", "js", "css");
		for ($i=0; $i < strlen($current); $i++) { 
			if ($current[$i] == "/") {
				$output = "";
			}
			else if ($current[$i] == "." && in_array(substr($current, $i+1), $fileTypes)) {
				return $output;
			}
			else 
				$output .= $current[$i];
		}
		return $output;
	}
	
	public function error($type, $fn) {
		echo "<center><h3>A <span style='color:#DA0000'>$type</span> has occured when we called <span style='color:#DA0000'>$fn</span> function!</h3></center>";
		exit(1);
	}
}

