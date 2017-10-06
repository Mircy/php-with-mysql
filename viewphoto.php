<?php
//Importing database class and database connect file
require 'classes/mydb.php';
require 'includes/db_connect.php';
//Adding the header
$pageTitle = "Gallery: Home";
include 'includes/header.php';
echo $header.PHP_EOL;
//Creating the main content of the view
$title = '';
$description = '';
$viewPhoto = '';
$heading = $pageHeading['imageview'];
//Getting title and description from database associated with image requested
$safeImage = $db->real_escape_string($image);
$sql = "Select title as 'Title', description as 'Description'
			From image_info
			Where  filename = '$safeImage'";
$result = $db->query($sql);
if ($result == false) {
	$viewPhoto = createHTML($error['db'], 'paragraph').PHP_EOL;
}else {	
	$row = $result->fetch_assoc();
	$title =  ucwords(htmlentities($row['Title']));
	$description =  htmlentities($row['Description']);	
	$result->free();
}
//Generate final HTML output
$href = 'index.php';
$src = "uploads/".$image;
$file = 'templates/viewphoto.html';
$tpl = file_get_contents($file);
$templates = array('[+title+]', '[+href+]', '[+src+]', '[+alt+]', '[+desc+]');
$values = array($title, $href, $src, $title, $description);
$viewPhoto .= str_replace($templates, $values, $tpl);
$main = createMain($heading, $viewPhoto);
echo $main;
//Adding the footer
include 'includes/footer.php';
echo $footer;
//Closing database connection
$db->close();
?>