<?php
//Importing database class and database connect file
require 'classes/mydb.php';
require 'includes/db_connect.php';
//Adding the header
$pageTitle = "Gallery: Home";
include 'includes/header.php';
echo $header .PHP_EOL;
//Creating the main content of the view
$heading = $pageHeading['home'];
$images = array ();
$title = '';
$home = ''; 
$imagesTotal = 0;
$dir = 'thumbs';
	//Extract all jpg filenames from Thumbs folder
    if (is_dir($dir)) { 
        $dirHandle = opendir($dir);
        if ($dirHandle === false) { 
           $home = createHTML($error['dir'], 'paragraph').PHP_EOL;
        } else {
            while(false !== ($file = readdir($dirHandle))){ 
                $pathParts = pathinfo(strtolower($file));
                if ($pathParts['extension'] == 'jpg' ) { 
                    $imagesTotal++;
                    $images [] = "$file";
                }
            }
            if ($imagesTotal == 0){ 
                $home = createHTML($error['image'], 'paragraph').PHP_EOL;
            }
        }
        closedir($dirHandle);
    } else {
        $home = createHTML($error['dir'], 'paragraph').PHP_EOL;
    }
$line = '';
//Getting title and create list line output
foreach($images as $link ){
	//Get title from database for each jpg gile
	$safeLink = $db->real_escape_string($link);
	$sql = "Select title as 'Title'
			From image_info
			Where  filename = '$safeLink'";
	$result = $db->query($sql);
	if ($result == false) {
		$home = createHTML($error['db'], 'paragraph').PHP_EOL;
	}else {	
		$row = $result->fetch_assoc();
		$title =  ucwords(htmlentities($row['Title']));	
		$result->free();
	}
	//Create list line
	$file = 'templates/link.html';
	$tpl = file_get_contents($file);
	$href = 'index.php?image='.$link;
	$src = $dir."/".$link;
	$templates = array('[+title+]', '[+href+]', '[+src+]', '[+alt+]');
	$values = array($title, $href, $src, $title);
	$link = str_replace($templates, $values, $tpl);
	$line .= createHTML($link, 'line').PHP_EOL;
}
//Generate final HTML output
$list = createHTML($line, 'list');
$home .= createMain($heading, $list);
echo $home;
//Adding the footer
include 'includes/footer.php';
echo $footer;
//Closing database connection
$db->close();
?>