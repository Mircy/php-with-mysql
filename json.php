<?php
//Including necessary files
require 'includes/config.php';
require 'classes/mydb.php';
require 'includes/db_connect.php';
$language = $config['language'];
require 'includes/' . $language . '.php';
//Send appropriate header
header('Content-type: application/json');
$imageInfo = array();
//Check if a photo details have been requested
if (isset($_GET['photo']) && strlen($_GET['photo']) > 0 ){
	$filename = htmlentities($_GET['photo']);
	$photo = "uploads/".$filename;
	//If requested file exist, extract and save data into array
	if(is_file($photo)){
		$safeFile = $db->real_escape_string($filename);
		$imageInfo['filename'] = $safeFile;
		$sql = "Select title as 'Title', description as 'Description'
				From image_info
				Where  filename = '$safeFile'";
		$result = $db->query($sql);
		if ($result == false) {
			$imageInfo['error'] = $error['db'];
		}else {	
			$row = $result->fetch_assoc();
			$title =  htmlentities($row['Title']);
			$description =  htmlentities($row['Description']);
			$imageInfo['title'] = $title;
			$imageInfo['description'] = $description;			
			$result->free();
		}
		$details = getimagesize($photo);
		if ($details !== false){
			$width = $details[0];
			$height = $details[1];
			$imageInfo['width'] = $width;
			$imageInfo['height'] = $height;
		}
	}else {
		$imageInfo['error'] = $jsonError['par'];
	}
} else {
	$imageInfo['error'] = $jsonError['par'];
}
//Creating JSON output
$json = json_encode($imageInfo);
echo $json;
//Closing database connection
$db->close();
?>