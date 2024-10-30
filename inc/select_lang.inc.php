<?php
//attempt to find the current page language, and set $qlang accordingly
//check for PolyLang
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugin = 'polylang/polylang.php';
$obj_id = get_queried_object_id();
$current_url = get_permalink( $obj_id );
if(is_plugin_active($plugin)) { $qlang = ICL_LANGUAGE_CODE; }

//check for a session variable
elseif(isset($_SESSION['lang'])) { $qlang = $_SESSION['lang'];  } 

//check for permalink language setting: /en/ etc.
elseif (substr_count($current_url,"/de/") == 1) { $qlang = "de"; }
elseif(substr_count($current_url,"/it/") == 1) { $qlang = "it"; }
elseif(substr_count($current_url,"/fr/") == 1) { $qlang = "fr"; }
elseif(substr_count($current_url,"/es/") == 1) { $qlang = "es"; }
elseif(substr_count($current_url,"/en/") == 1) { $qlang = "en"; }

//if language still not set, use website default language
if(!isset($qlang)) { $locale = get_locale(); $qlang = substr($locale,0,2); }
if(!isset($qlang)) { $locale = get_bloginfo("language"); $qlang = substr($locale,0,2); }

//if language still not set, default to English
if(!isset($qlang)) { $qlang="en"; }

//check the $qlang is in the list of catered languages
$all_langs = array("en", "de", "it", "fr", "es");
if(!in_array($qlang , $all_langs)) { $qlang="en"; }

//check if widgit language has been set in widgit manager, and if so override $qlang accordingly
if(isset($instance['widget_language'])) { 
	switch($instance['widget_language']) {
	case("English"): $qlang = 'en'; break;
	case("German"): $qlang = 'de'; break;
	case("Italian"): $qlang = 'it'; break;
	case("French"): $qlang = 'fr'; break;
	case("Spanish"): $qlang = 'es'; break;
	}
} 

//Set title to identified language. You may change these if desired.
switch($qlang) {
	case('de'): $cQ_title = 'Zitat des Tages'; break;
	case('it'): $cQ_title = 'Citazione del giorno'; break;
	case('fr'): $cQ_title = 'Citation du jour'; break;
	case('es'): $cQ_title = 'Cita del d&iacute;a'; break;
	default: $cQ_title = 'Quote of the Day'; 
} 
?>