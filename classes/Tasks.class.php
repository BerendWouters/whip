<?php
require_once("classes/Database.class.php");
require_once("classes/Users.class.php");
class Tasks
{
	private $conn = null;
	private $users = null;

	public function __construct()
	{
		$database = new Database();
		$this->users = new Users();
		$this->conn = $database->GetDatabase();
	}

	public function GetAllMyTasks($userId)
	{
		$sql = "SELECT * FROM tasks WHERE completed = 0 AND owner = " . $userId . " ORDER BY id";
		$result = mysqli_query($this->conn, $sql);
		$tasks = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($task = mysqli_fetch_object($result))
			{
				$task->user = $this->users->GetUser($task->owner);
				if(array_key_exists($task->task_parent, $tasks)){
					$tasks[$task->task_parent]->tasks[$task->id] = $task;
				}else{
					$tasks[$task->id] = $task;
				}
				
			}
		}
		return $tasks;
	}

	public function GetTask($taskId)
	{
		$sql = "SELECT * FROM tasks WHERE id = '" . $taskId . "'";
		$result = mysqli_query($this->conn, $sql);
		if (mysqli_num_rows($result) > 0)
		{
			$task = mysqli_fetch_object($result);
			$task->user = $this->users->GetUser($task->owner);
			return $task;
		}
	}
	
	public function GetAllTasksFromProject($projectId, $completionStatus = null)
	{
		$append = "";
		if($completionStatus != NULL){
			$append = " AND completed = ".$completionStatus ." ";
		}
		$sql = "SELECT * FROM tasks WHERE parent = " . $projectId ." ". $append . "ORDER BY id";
		$result = mysqli_query($this->conn, $sql);
		$tasks = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($task = mysqli_fetch_object($result))
			{
				$task->user = $this->users->GetUser($task->owner);
				if(array_key_exists($task->task_parent, $tasks)){
					$tasks[$task->task_parent]->tasks[$task->id] = $task;
				}else{
					$tasks[$task->id] = $task;
				}
				
			}
		}
		return $tasks;
	}	

	public function renderTasks($taskList)
	{
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
		$sql = "SELECT * FROM assignments WHERE task = " . $taskId . " ORDER BY id";
		$result = mysqli_query($this->conn, $sql);
		$userIds = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($userId = mysqli_fetch_object($result))
			{
					$userIds[$userId->id] = $this->users->GetUser($userId->user);			
			}
		}
		return $userIds;
	}
	
	public function GetAllTasksFromParent($parentId)
	{
		$sql = "SELECT * FROM tasks WHERE task_parent = " . $parentId . " ORDER BY id";
		$result = mysqli_query($this->conn, $sql);
		$tasks = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($task = mysqli_fetch_object($result))
			{
					$task->user = $this->users->GetUser($task->owner);
					$tasks[$task->id] = $task;
			}
		}
		return $tasks;
	}
	
	public function GetAllTasksFromUser($userId)
	{
		$sql = "SELECT * FROM tasks WHERE owner = " . $userId . " ORDER BY id";
		$result = mysqli_query($this->conn, $sql);
		$tasks = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($task = mysqli_fetch_object($result))
			{
					$task->user = $this->users->GetUser($task->owner);
					$tasks[$task->id] = $task;
			}
		}
		return $tasks;
	}	

	public function GetAllTasksWhereUserIsAssigned($userId)
	{
		$sql = "SELECT * FROM assignments WHERE user = " . $userId . " ORDER BY id";
		$result = mysqli_query($this->conn, $sql);
		$tasks = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($task = mysqli_fetch_object($result))
			{
					$task->user = $this->users->GetUser($task->user);
					$tasks[$task->id] = $this->GetTask($task->task);
			}
		}
		return $tasks;
	}		

}
	

?>