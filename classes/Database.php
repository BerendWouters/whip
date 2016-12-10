<?php
class Database{
	public function GetDatabase(){
		$mysqli = new mysqli("localhost", "root", "root", "whip");
		if ($mysqli->connect_errno) {
		    printf("Connect failed: %s\n", $mysqli->connect_error);
		    exit();
		}
		return $mysqli;

	}
}
?>