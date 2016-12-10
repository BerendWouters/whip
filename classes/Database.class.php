<?php
class Database{
	public function GetDatabase(){
		$user = "root";
		$pass = "root";
		$dbh = new PDO('mysql:host=localhost;dbname=whip', $user, $pass);
		return $dbh;
	}
}
?>