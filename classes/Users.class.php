<?php
class Users{

	private $conn = null;

	function __construct(){
		$database = new Database();
		$this->conn = $database->GetDatabase();
	}
	public function GetUser($userId){
		$sql = "SELECT * FROM users WHERE userid = ".$userId;
		$result = mysqli_query($this->conn, $sql);
		if (mysqli_num_rows($result) > 0)
		{
			$user = mysqli_fetch_object($result);
			return $user;
		}
	}
}