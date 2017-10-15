<?php
//Function to create logo
function createLogo($first,$second) {
	$file = 'templates/logo.html';
	$tpl = file_get_contents($file);
	$logo1 = str_replace('[+first+]', $first, $tpl);
	$logo = str_replace('[+second+]', $second, $logo1);
	return $logo;
}

//Function to create main html output
function createMain($heading, $content) {
	$file = 'templates/main.html';
	$tpl = file_get_contents($file);
	$main1 = str_replace('[+heading+]', $heading, $tpl);
	$main = str_replace('[+content+]', $content, $main1);
	return $main;
}
//Function to create HTML snippets
function createHTML ($string, $template){
	$file = 'templates/'. $template .'.html';
	$tpl = file_get_contents($file);
	$html = str_replace('[+content+]', $string, $tpl);
	return $html;
}
//Function to create navigation menu
function createMenu($links){
	$file = 'templates/menu.html';
	$tpl = file_get_contents($file);
	$menu = '';
	foreach ($links as $link => $name) {
		$menu1 = str_replace('[+link+]', $link, $tpl);
		$menu .= str_replace('[+name+]', $name, $menu1);
	}
	return $menu;
}
/*Function to resize images
$original - location of the original file
$maxWidth - width size passed
$maxHeight - height size passed
$newname - new location where file will be saved
*/
function resizeImage($original, $maxWidth, $maxHeight, $newName) {
	$details = getimagesize($original);
	if ($details !== false) {
		switch ($details[2]) {
			case IMAGETYPE_JPEG:
				$src = imagecreatefromjpeg($original);
				break;
		}
		$width = $details[0];
		$height = $details[1];
		if($width < $maxWidth && $height < $maxHeight) {
			$newWidth = $width;
			$newHeight = $height;
		}else {
			$ratio = $width/$height;
			if( $width > $height) {
				$newWidth = $maxWidth;
				$newHeight = round($newWidth/$ratio);
			} else {
				$newHeight = $maxHeight;
				$newWidth = round($newHeight*$ratio);
			}
		}
		$new = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($new, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		imagejpeg($new, $newName, 100);
		imagedestroy($src);
		imagedestroy($new);
	}
}	
?>