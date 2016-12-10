<?php
require_once("classes/Database.class.php");
require_once("classes/Users.class.php");
require_once("classes/Projects.class.php");

class Task{
	public $id;
	public $owner;
	public $parent;
	public $task_parent;
	public $startdatum;
	public $sluitingsdatum;
	public $deadline;
	public $smalltext;
	public $bigtext;
	public $completed;

	public $project;
	public $user;
}

class Tasks{
	private $conn = null;
	private $users = null;

	public function __construct(){
		$database = new Database();
		$this->users = new Users();
		$this->projects = new Projects();
		$this->conn = $database->GetDatabase();
	}

	public function GetAllTasks(){
		$sql = 
		$stmt = $this->conn->prepare("SELECT * FROM tasks WHERE completed = 0 AND task_parent = 0");
		$stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Task');
		$tasks = array();
		foreach ($results as $task) {
			$task->project = $this->projects->GetProject($task->parent);
			$task->user = $this->users->GetUser($task->owner);
			$tasks[$task->id] = $task;
		}
		return $tasks;
	}

	public function GetAllMyTasks($userId){
		$sql = "SELECT * FROM tasks WHERE completed = 0 AND owner = :userId ORDER BY id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":userId", $userId);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Task');
		$tasks = array();
		foreach ($results as $task) {
			$task->user = $this->users->GetUser($task->owner);
			
			if(array_key_exists($task->task_parent, $tasks)){
				$tasks[$task->task_parent]->tasks[$task->id] = $task;
			}else{
				$tasks[$task->id] = $task;
			}
		}
		return $tasks;
	}

	public function GetAllMyParentTasks($userId){
		$sql = "SELECT * FROM tasks WHERE completed = 0  AND task_parent = 0 AND owner = :userId";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":userId", $userId);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Task');
		$tasks = array();
		foreach ($results as $task) {
			$task->project = $this->projects->GetProject($task->parent);
			$task->user = $this->users->GetUser($task->owner);
			$tasks[$task->id] = $task;
		}
		return $tasks;
	}

	public function GetTask($taskId)
	{
		$sql = "SELECT * FROM tasks WHERE id = '" . $taskId . "'";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":userId", $userId);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Task');
		$task = $stmt->fetch();
		if ($task != null)
		{
			$task->user = $this->users->GetUser($task->owner);
			return $task;
		}
	}
	
	public function GetAllTasksFromProject($projectId, $completionStatus = null){
		$append = "";
		if($completionStatus != NULL){
			$append = " AND completed = :completionStatus";
		}
		$sql = "SELECT * FROM tasks WHERE parent = :projectId". $append . "ORDER BY id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":projectId", $projectId);
		$stmt->bindParam(":completionStatus", $completionStatus);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Task');
		$tasks = array();
		foreach ($results as $task) {
			$task->user = $this->users->GetUser($task->owner);
			if(array_key_exists($task->task_parent, $tasks)){
				$tasks[$task->task_parent]->tasks[$task->id] = $task;
			}else{
				$tasks[$task->id] = $task;
			}
		}
		return $tasks;
	}	

	public function renderTasks($taskList){
		$currenttimestamp = strtotime(date('Y-m-d'));
		foreach ($taskList as $task) {
			if($currenttimestamp > $task->deadline)
				{
					echo "<li>\n";
					echo "<a href=\"?task=" . $task->id . "\"><span class=\"label label-danger\">" . $task->smalltext . "</span></a>\n";
					echo "</li>\n";
				}else{
					echo "<li>\n";
					echo "<a href=\"?task=" . $task->id . "\"><span class=\"label label-success\">" . $task->smalltext . "</span></a>\n";
					echo "</li>\n";											
				}
			if(!empty($task->tasks))
				$this->renderTasks($task->tasks);
		}
	}
	public function GetAllTaskUsers($taskId)
	{
		$sql = "SELECT * FROM assignments WHERE task = :taskId ORDER BY id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":taskId", $taskId);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Task');
		$assignments = array();
		foreach ($results as $assignment) {
			$assignments[$assignment->id] = $assignment;			
		}
		return $assignments;
	}
}

?>