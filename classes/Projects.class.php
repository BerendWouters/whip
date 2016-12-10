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
		$sql = "SELECT * FROM projects WHERE completed = :status";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":status", $status);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Project');
		$projects = array();
		foreach ($results as $project) {
			$project->user = $this->users->GetUser($project->lead);
			$projects[$project->id] = $project;
		}
		return $projects;
	}


	public function GetProject($id){
		$sql = "SELECT * FROM projects WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
		$project = $stmt->fetch();
		if ($project != null)
		{
			$project->user = $this->users->GetUser($project->lead);
			//zoek taken onder project
			$sql = "SELECT COUNT(id) as taskCount FROM tasks WHERE parent = :projectId AND task_parent = 0";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(":projectId", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			$taskids = array();
			if ($result != null)
			{
				$project->taskCount = $result[0];
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