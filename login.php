<?php
session_start();
require_once("classes/Database.class.php");
require_once("classes/Users.class.php");
require_once("models/User.model.php");
$users = new Users();
?>
<html>
	<head>
		<title>Whip Login</title>
	</head>
	<style>
		@import url(https://fonts.googleapis.com/css?family=Roboto:300);

		.login-page {
			width: 360px;
			padding: 8% 0 0;
			margin: auto;
		}
		.form {
			position: relative;
			z-index: 1;
			background: #FFFFFF;
			max-width: 360px;
			margin: 0 auto 100px;
			padding: 45px;
			text-align: center;
			box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
		}
		.form input {
			font-family: "Roboto", sans-serif;
			outline: 0;
			background: #f2f2f2;
			width: 100%;
			border: 0;
			margin: 0 0 15px;
			padding: 15px;
			box-sizing: border-box;
			font-size: 14px;
		}
		.form button {
			font-family: "Roboto", sans-serif;
			text-transform: uppercase;
			outline: 0;
			background: #4CAF50;
			width: 100%;
			border: 0;
			padding: 15px;
			color: #FFFFFF;
			font-size: 14px;
			-webkit-transition: all 0.3 ease;
			transition: all 0.3 ease;
			cursor: pointer;
		}
		.form button:hover,.form button:active,.form button:focus {
			background: #43A047;
		}
		.form .message {
			margin: 15px 0 0;
			color: #b3b3b3;
			font-size: 12px;
		}
		.form .message a {
			color: #4CAF50;
			text-decoration: none;
		}
		.form .register-form {
			display: none;
		}
		.container {
			position: relative;
			z-index: 1;
			max-width: 300px;
			margin: 0 auto;
		}
		.container:before, .container:after {
			content: "";
			display: block;
			clear: both;
		}
		.container .info {
			margin: 50px auto;
			text-align: center;
		}
		.container .info h1 {
			margin: 0 0 15px;
			padding: 0;
			font-size: 36px;
			font-weight: 300;
			color: #1a1a1a;
		}
		.container .info span {
			color: #4d4d4d;
			font-size: 12px;
		}
		.container .info span a {
			color: #000000;
			text-decoration: none;
		}
		.container .info span .fa {
			color: #EF3B3A;
		}
		body {
			background: #76b852; /* fallback for old browsers */
			background: -webkit-linear-gradient(right, #76b852, #8DC26F);
			background: -moz-linear-gradient(right, #76b852, #8DC26F);
			background: -o-linear-gradient(right, #76b852, #8DC26F);
			background: linear-gradient(to left, #76b852, #8DC26F);
			font-family: "Roboto", sans-serif;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;      
		}
	</style>
		
	<body>
	<?php
		require("mail.php");
		if(isset($_GET['token']) && !isset($_POST['username']))
		{
			$response = $users->verify($_GET['token']);
			echo $response;
			
		}
	?>
		<div class="login-page">
			<div class="form">
				<form class="register-form" method="post">
					<input type="text" placeholder="Naam " name="register_name"/>
					<input type="text" placeholder="Gebruikersnaam " name="register_username"/>
					<input type="password" placeholder="Wachtwoord " name="register_password"/>
					<input type="text" placeholder="Email adres" name="register_mail"/>
					<button>Aanmaken</button>
					<p class="message">Al een account? <a href="#">Log In</a></p>
				</form>
				<form class="login-form" method="post">
					<input type="text" placeholder="Gebruikersnaam " name="username"/>
					<input type="password" placeholder="Wachtwoord " name="password"/>
					<button>login</button>
					<p class="message">Nog geen account? <a href="#">Maak er een aan</a></p>
				</form>
			</div>
		</div>
		<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
		<script src="resources/login.js"></script>
	<?php

		if(isset($_POST['register_username']))
		{
			//variabelen
			$reg_name = mysql_escape_string($_POST['register_name']);
			$reg_username = mysql_escape_string($_POST['register_username']);
			$reg_pass = mysql_escape_string($_POST['register_password']);
			$reg_mail = mysql_escape_string($_POST['register_mail']);
			//controle op lege invoer
			$fout = false;
			if(strlen($reg_username) == 0)
			{
				echo("<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Gebruikersnaam mag niet leeg zijn!</p></center>");
				$fout = true;
			}
			if(strlen($reg_pass) == 0)
			{
				echo("<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Wachtwoord mag niet leeg zijn!</p></center>");
				$fout = true;
			}
			if(strlen($reg_mail) == 0)
			{
				echo("<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Email adres mag niet leeg zijn!</p></center>");
				$fout = true;
			}
			if($fout == true)
			{
				die();
			}
			
			//zoek op of user in db zit
			$sql = "SELECT * FROM users WHERE username = '" . $reg_username . "'";
			$result = mysqli_query($SQL_conn, $sql);
			$row = mysqli_fetch_assoc($result);
			if (mysqli_num_rows($result) > 0)
			{
				die("<center><p class=\"message\"><img src=\"images/exclamation-circle.png\" width=16 height=16>&nbsp;&nbsp;Gebruikersnaam bestaat al!</p></center>");
			}else{
				//generate information
				$rndalphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789$$$@@@";
				//generate salt (50 characters)
				$pwsalt = "";
				for($i=0;$i<50;$i++)
				{
					$pwsalt = $pwsalt . substr($rndalphabet,rand(1,(strlen($rndalphabet)-1)),1);
				}
				
				//generate token for mail (50 characters)
				$mailtoken = "";
				for($i=0;$i<50;$i++)
				{
					$mailtoken = $mailtoken . substr($rndalphabet,rand(1,(strlen($rndalphabet)-1)),1);
				}
				//encode pw
				$password = md5(md5($reg_pass) . md5($pwsalt));
				
				//enter information into db
				$sql = "INSERT INTO users (userid,user_name,username,userpass,salt,usermail,usergroup,slackid,verified) VALUES('','" . $reg_name . "','" . $reg_username . "','" . $password . "','" . $pwsalt . "','" . $reg_mail . "',0,'',0)";
				$result = mysqli_query($SQL_conn, $sql);
				
				//now get the id for this user
				$sql = "SELECT * FROM users WHERE username = '" . $reg_username . "'";
				$result = mysqli_query($SQL_conn, $sql);
				$row = mysqli_fetch_assoc($result);				
				$userid = $row['userid'];

				$sql = "INSERT INTO mailtokens (tokenid,token,userid) VALUES('','" . $mailtoken . "','" . $userid . "')";
				$result = mysqli_query($SQL_conn, $sql);				
				
				//send registration mail
				sendmail($reg_mail,"Bevestig je registratie","Beste " . $reg_name . ",<br><br>Jij (hopelijk) hebt je ingeschreven op ons systeem, klik op de link onderaan om je inschrijving te bevestigen<br><br><a href=\"http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?token=" . $mailtoken . "\">http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?token=" . $mailtoken . "</a><br><br>Vriendelijke groeten,<br>Het systeem");		
				
				die("<center><p class=\"message\"><img src=\"images/success.png\" width=16 height=16>&nbsp;&nbsp;Registratie in behandeling, controleer je e-mail om te bevestigen!</p></center>");
			}
		}
		if(isset($_POST['username']) && isset($_POST['password']))
		{		
			$users->login($_POST['username'], $_POST['password']);			
		}
	?>	
	</body>	
</html>