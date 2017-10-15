<?php
//Including necessary files
require 'includes/config.php';
require_once 'includes/functions.php';
$language = $config['language'];
require 'includes/' . $language . '.php';
//Decide which view will be extracted
if (!isset($_GET['image'])){
	if (!isset($_GET['page'])){
		$id = 'home';
	} else {
		$id = $_GET['page'];
	}
	$html = 'views/'. $id .'.php';
	if (is_file($html)){
		include $html;
	}else {
		echo createHTML($error['page'], 'h1');
	}
}else {
	//Passing the requested image to viewphoto.php file
	$image = strtolower(htmlentities($_GET['image']));
	$html = 'views/viewphoto.php';
	if(is_file($html)){
		include $html;
	}else {
		echo createHTML($error['page'], 'h1');
	}
}
?>