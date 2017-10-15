<?php
//Passing database credentials to MyDB class
$db = new MyDB(
				$config['db_host'],
				$config['db_user'],
				$config['db_pass'],
				$config['db_name']
				);
?>