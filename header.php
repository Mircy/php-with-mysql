<?php
//Creating the header
$css = $config['css'];
$logo = createLogo('Photo', 'Gallery');
$links = array('index.php' =>'Home',
				'index.php?page=upload' =>'Upload'
				);
$menu = createMenu($links);
$head = 'templates/header.html';
$tplHead = file_get_contents($head);
$placeHolders = array('[+title+]','[+css+]','[+logo+]','[+menu+]');
$data = array($pageTitle, $css, $logo, $menu);
$header = str_replace($placeHolders, $data, $tplHead);
?>