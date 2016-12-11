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
?>