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
		}else{
			$user = new stdclass;
			$user->user_name = "Geen";
			$user->username = "Geen";

		}
		return $user;
	}


	public function GetAllVerifiedUsers(){
		$sql = "SELECT * FROM users WHERE verified = 1";
		$result = mysqli_query($this->conn, $sql);
		$users = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($user = mysqli_fetch_object($result)){
				$users[$user->userid] = $user;
			}
		}
		return $users;
	}

	public function Verify($token){
		$sql = "SELECT * FROM mailtokens WHERE token = '" . $token . "'";
		$result = mysqli_query($this->conn, $sql);
		if (mysqli_num_rows($result) == 0)
		{			
			return "<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Token bestaat niet!</p></center>";
		}else{
			$user = mysqli_fetch_object($result);
			$userid = $user->userid;
			$tokenid = $user->tokenid;
			
			//update db
			$sql = "UPDATE users SET verified = 1 WHERE userid = " . $userid . "";
			$result = mysqli_query($this->conn, $sql);
			if(!$result)
				return "<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Updaten status mislukt!</p></center>";
			
			//delete mailtoken
			$sql = "DELETE FROM mailtokens WHERE tokenid = " . $tokenid . "";
			$result = mysqli_query($this->conn, $sql);
			if(!$result)
				return "<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Verwijderen mailtoken mislukt!</p></center>";
			return "<center><p class=\"message\"><img src=\"images/success.png\" width=16 height=16>&nbsp;&nbsp;Registratie voltooid! Gelieve in te loggen:</p></center>";
		}
	}

	public function login($username, $password){
		$sql = "SELECT * FROM users WHERE username = '" . $username . "'";
		$result = mysqli_query($this->conn, $sql);
		
		if (mysqli_num_rows($result) > 0)
		{
			$user = mysqli_fetch_object($result);
			$salt = $user->salt;
			$userid = $user->userid;
			if(md5(md5($password) . md5($salt)) == $user->userpass)
			{
				//user is ingelogd.
				$_SESSION['whip_username'] = $username;
				$_SESSION['whip_username'] = $username;
				$_SESSION['whip_username'] = $username;
				$_SESSION['whip_userid'] = $userid;
				$_SESSION['whip_userid'] = $userid;
				$_SESSION['whip_userid'] = $userid;
				echo("<center><p class=\"message\"><img src=\"images/success.png\" width=16 height=16>&nbsp;&nbsp;Gegevens kloppen.</p></center>");
				die('<META HTTP-EQUIV=REFRESH CONTENT="1; index.php">');
			}
		}
	}
}