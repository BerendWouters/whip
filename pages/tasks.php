<?php

if(isset($_GET['task']))
{
	//zoek project in db
	$sql = "SELECT * FROM tasks WHERE id = '" . mysql_escape_string($_GET['task']) . "'";
	$result = mysqli_query($SQL_conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if (mysqli_num_rows($result) > 0)
	{
		$taskid = $row['id'];
		$taskowner = $row['owner'];
		$taskparent = $row['parent'];
		$taskstartdatum = $row['startdatum'];
		$taskdeadline = $row['deadline'];
		$tasksluitingsdatum = $row['sluitingsdatum'];
		$tasksmalltext = $row['smalltext'];
		$taskbigtext = $row['bigtext'];
		$taskcompleted = $row['completed'];
		
		//zoek owner info
		$sql = "SELECT * FROM users WHERE userid = '" . $taskowner . "'";
		$result = mysqli_query($SQL_conn, $sql);
		$row = mysqli_fetch_assoc($result);
		$leadname = $row['user_name'];
		$leadusername = $row['user_username'];
		$leadmail = $row['user_mail'];
		//zoek taken onder project
		$sql = "SELECT * FROM tasks WHERE parent = '" . $projectid . "'";
		$result = mysqli_query($SQL_conn, $sql);
		$taskids = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result))
			{			
				array_push($taskids, $row['id']);
			}
		}
		$alletaken = count($taskids);
		$currenttimestamp = strtotime(date('Y-m-d'));
		
	}else{
		echo "		<div id=\"page-inner\">\n";
		echo "			<div class=\"row\">\n";
		echo "				<div class=\"col-md-12\">\n";
		echo "					<h2>Project niet gevonden!</h2>\n";
		echo "		</div>\n";
		echo "	</div>\n";
		echo "</div>\n";
	}
}else{
	die("no project?");
}
?>
<div id="page-inner">
    <div class="row">
		<div class="col-md-12">
			<h2><?php echo($projectnaam); ?></h2>
		</div>
	</div>
	<!-- /. ROW  -->
	<hr />
	<div class="row">
		<div class="col-md-8">