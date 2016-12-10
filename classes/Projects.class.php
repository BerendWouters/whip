<?php

class Projects{
	private $conn = null;	
	private $users = null;

	function __construct(){
		$database = new Database();
		$this->users = new Users();
		$this->conn = $database->GetDatabase();
	}

	public function GetProjects($status){
		$sql = "SELECT * FROM projects WHERE completed = ".$status;
		$result = mysqli_query($this->conn, $sql);
		$projects = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($project = mysqli_fetch_object($result)){
				$project->user = $this->users->GetUser($project->lead);
				$projects[$project->id] = $project;
			}
		}
		return $projects;
	}


	public function GetProject($id){
		$sql = "SELECT * FROM projects WHERE id = ".  $id;
		$result = mysqli_query($this->conn, $sql);
		if (mysqli_num_rows($result) == 1)
		{

			$project = mysqli_fetch_object($result);
			$project->user = $this->users->GetUser($project->lead);
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
		}else{
			$project = new stdClass;
			$project->taskCount = 0;
			$project->naam = "Geen project";
		}
		return $project;
	}
}
?>