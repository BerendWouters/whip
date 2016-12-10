<?php
require_once("classes/Database.php");

class Project{
	private $conn = null;

	function __construct(){
		$database = new Database();
		$this->conn = $database->GetDatabase();
	}

	public function GetProject($id){
		$sql = "SELECT * FROM projects WHERE id = ".  $id;
		$result = mysqli_query($this->conn, $sql);
		if (mysqli_num_rows($result) == 1)
		{

			$project = mysqli_fetch_object($result);
			
			//zoek lead info
			$sql = "SELECT * FROM users WHERE userid = " . $project->lead;
			$result = mysqli_query($this->conn, $sql);
			$user = mysqli_fetch_object($result);
			$project->user = $user;
			//zoek taken onder project
			$sql = "SELECT COUNT(id) as taskCount FROM tasks WHERE parent = '" . $project->id . "' AND task_parent = 0";
			$result = mysqli_query($this->conn, $sql);
			$taskids = array();
			if (mysqli_num_rows($result) == 1)
			{
				$tasks = mysqli_fetch_object($result);
				$project->taskCount = $tasks->taskCount;
			}else{
				$project->taskCount = 0;
			}
			return $project;
		}else{
			echo "No project with id ". $id. " found";
		}
	}
}
?>