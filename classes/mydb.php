<?php
//Class will connect to a mysqli database and check if connection is successful
class MyDB extends mysqli {
	public function __construct($dbHost, $dbUser, $dbPass, $dbName){
		parent::__construct($dbHost, $dbUser, $dbPass, $dbName);						
		if($this->connect_errno){
			exit($this->connect_error);
		}
	}
}
?>