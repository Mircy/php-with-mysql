<?php
//Importing database class and database connect file
require 'classes/mydb.php';
require 'includes/db_connect.php';
//Adding the header
$pageTitle = "Gallery: Upload";
include 'includes/header.php';
echo $header .PHP_EOL;
//Creating the main content of the view
$heading = $pageHeading['upload'];
$clean = array();
$errors = array();
$formSubmitted = false;
$errorsDetected = false;
$newName = '';
$tmpName = '';
$feedback = '';
$errorReport = '';
//Validate user input
if (isset($_POST['fileupload'])){
	$formSubmitted = true;
	$upError = $_FILES['userfile']['error'];
	//Check if file is uploaded
	if($upError == UPLOAD_ERR_OK){
		if(is_uploaded_file($_FILES['userfile']['tmp_name'])){
			//Preparing uploaded file for resize and saving
			$tmpName = $_FILES['userfile']['tmp_name'];
			$upDir = dirname(__FILE__).'/../uploads/';
			$thumbDir = dirname(__FILE__).'/../thumbs/';
			$prefix = 'image_';
			$extention = '.jpg';
			//Generate a random name
			$randomName = strtolower(uniqid());
			$upFilename = $prefix.$randomName.$extention;
			$newName = $upDir.$upFilename;
			$thumbName = $thumbDir.$upFilename;
			//Check if file is jpg
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mimeType = finfo_file($finfo, $_FILES['userfile']['tmp_name']);
			if($mimeType != 'image/jpeg'){
				$errorsDetected = true;
				$errors['file'] = $fileError['type'];
			}else {
				$clean['filename'] = $upFilename;
			}
		}
	} elseif ($upError == UPLOAD_ERR_INI_SIZE){
		$errorsDetected = true;
		$errors['file'] =  $fileError['size'];
	} elseif ($upError == UPLOAD_ERR_FORM_SIZE){
		$errorsDetected = true;
		$errors['file'] = $fileError['size'];
	} elseif ($upError == UPLOAD_ERR_PARTIAL){
		$errorsDetected = true;
		$errors['file'] =  $fileError['part'];
	} else {
		$errorsDetected = true;
		$errors['file'] = $fileError['no'];
	}
	//Validating title
	if(isset($_POST['title']) && strlen($_POST['title']) > 0){
        $tit = strtolower(trim($_POST['title']));
        if (
            ctype_alpha(str_replace(" ","",$tit)) == true &&
            strlen($tit) <= 50 
            ) {
            $clean['title'] = $tit;
        }else {
            $errorsDetected = true;
            $errors['title'] = $titleError['inv'];
        }
    }else {
        $errorsDetected = true;
        $errors['title'] = $titleError['req'];
    }
	//Validating description
	if(isset($_POST['description']) && strlen($_POST['description']) > 0){
        $desc = trim($_POST['description']);
        if (strlen($desc) <= 250 ) {
            $clean['description'] = $desc;
        }else {
            $errorsDetected = true;
            $errors['description'] = $descError['inv'];
        }
    }else {
        $errorsDetected = true;
        $errors['description'] = $descError['req'];
    }
}
//Saving valid input
if($errorsDetected == false && $formSubmitted == true){
	$feedback = '';
	//Escaping user input before use in a query
	$title = htmlentities($clean['title']);
	$safeTitle = $db->real_escape_string($title);
	$description = htmlentities($clean['description']);
	$safeDescription = $db->real_escape_string($description);
	$filename = htmlentities($clean['filename']);
	$safeFilename = $db->real_escape_string($filename);
	//Write info into database
	$sql = "INSERT INTO image_info
			VALUES (NULL,  '$safeTitle', '$safeDescription', '$safeFilename');
			";
	$result = $db->query($sql);
	//Resize image and saving it
	if($result == true){
		$test = false;
		if(is_dir($upDir) && is_writable($upDir)){
			resizeImage($tmpName, 600, 600, $newName);
			if(file_exists($newName)){
				if(is_dir($thumbDir) && is_writable($thumbDir)){
					resizeImage($newName, 150, 150, $thumbName);
					if(file_exists($thumbName)){
						$test = true;
					}
				}
			}
		}
		if($test == true){
			$feedback = createHTML($fileSuccess['done'], 'paragraph');
		}else {
			//Deleting image and data from database associated with it if it fails to be saved in both folders
			$feedback = createHTML($fileError['fail'], 'paragraph');
			if(file_exists($newName)){
				unlink($newName);
			}
			$sql = "DELETE FROM image_info
					WHERE filename = '$safeFilename';
					";
			$db->query($sql);
		}
	}else {
		$feedback = createHTML($error['db'], 'paragraph');
	}
}else {
	//Creating feedback with invalid input
	foreach($errors as $value){
		$errorReport .= createHTML($value, 'paragraph').PHP_EOL;
   	}
	//Creating the upload form
	$self = htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
	$method = 'post';
	//Keeping user valid input in fields
	if(isset($clean['title'])){
		$tValue = htmlentities($clean['title']);
	}else {
		$tValue = '';
	}
	if(isset($clean['description'])){
		$dValue = htmlentities($clean['description']);
	}else {
		$dValue = '';
	}
	$info = $form['info'];
	$tName = $form['title'];
	$dName = $form['description'];
	$sName = $form['submit'];
	$upName = $form['file'];		
	$file = 'templates/uploadform.html';
	$tpl = file_get_contents($file);
	$templates = array('[+action+]', '[+method+]', '[+info+]', '[+file+]', '[+title+]', '[+tvalue+]', '[+description+]', '[+dvalue+]', '[+submit+]');
	$values = array($self, $method, $info, $upName, $tName, $tValue, $dName, $dValue, $sName);
	$form = str_replace($templates, $values, $tpl);
	$upload = createMain($heading, $form);
	echo $upload .PHP_EOL;
}
echo $feedback;
echo $errorReport;
//Adding the footer
include 'includes/footer.php';
echo $footer;
//Closing database connection
$db->close();
?>