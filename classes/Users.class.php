<?php
class User{
	public $userid;
	public $username;
	public $user_name;
	public $usermail;
}

class UserLogin extends User{
	public $userpass;
	public $salt;
}

class Users{

	private $conn = null;

	function __construct(){
		$database = new Database();
		$this->conn = $database->GetDatabase();
	}
	public function GetUser($userId){
		$sql = "SELECT * FROM users WHERE userid = :userId";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":userId", $userId);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
		$user = $stmt->fetch();
		if($user == null)
		{
			$user = new stdclass;
			$user->user_name = "Geen";
			$user->username = "Geen";
		}
		return $user;
	}


	public function GetAllVerifiedUsers(){
		$sql = "SELECT * FROM users WHERE verified = 1";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
		$users = $stmt->fetchAll();
		return $users;
	}

	public function Verify($token){
		$sql = "SELECT * FROM mailtokens WHERE token = :token";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":token", $token);
		$stmt->setFetchMode(PDO::FETCH_CLASS, "UserLogin");
		$user = $stmt->fetch();
		if (user == null)
		{			
			return "<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Token bestaat niet!</p></center>";
		}else{
			//update db
			$sql = "UPDATE users SET verified = 1 WHERE userid = :userId";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(":userId", $user->userid);
			$result = $stmt->execute();
			if(!$result)
				return "<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Updaten status mislukt!</p></center>";
			
			//delete mailtoken
			$sql = "DELETE FROM mailtokens WHERE tokenid = :tokenId";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(":tokenId", $user->tokenid);
			$result = $stmt->execute();
			if(!$result)
				return "<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Verwijderen mailtoken mislukt!</p></center>";
			return "<center><p class=\"message\"><img src=\"images/success.png\" width=16 height=16>&nbsp;&nbsp;Registratie voltooid! Gelieve in te loggen:</p></center>";
		}
	}

	public function login($username, $password){
		$sql = "SELECT * FROM users WHERE username = :username";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":username", $username);
		$stmt->setFetchMode(PDO::FETCH_CLASS, "User");
		$stmt->execute();
		$user = $stmt->fetch();
		if ($user != null)
		{
			$salt = $user->salt;
			$userid = $user->userid;
			if(md5(md5($password) . md5($salt)) == $user->userpass)
			{
				//user is ingelogd.
				$_SESSION['whip_username'] = $user->username;
				$_SESSION['whip_userid'] = $user->userid;
				echo("<center><p class=\"message\"><img src=\"images/success.png\" width=16 height=16>&nbsp;&nbsp;Gegevens kloppen.</p></center>");
				die('<META HTTP-EQUIV=REFRESH CONTENT="1; index.php">');
			}
		}
	}
}