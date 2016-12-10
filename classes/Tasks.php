<?php
require_once("classes/Database.php");

class Tasks{
	private $conn = null;

	function __construct(){
		$database = new Database();
		$this->conn = $database->GetDatabase();
	}

	public function GetAllMyTasks($userId){
		$sql = "SELECT * FROM tasks WHERE completed = 0 AND owner = " . $userId . " ORDER BY id";
		$result = mysqli_query($this->conn, $sql);
		$tasks = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($task = mysqli_fetch_object($result))
			{
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
			 
			//zoek owner info
			$sql = "SELECT * FROM users WHERE userid = '" . $task->owner . "'";
			$result = mysqli_query($this->conn, $sql);
			$user = mysqli_fetch_object($result);
			$task->user = $user;
			//zoek taken onder project
			//$sql = "SELECT * FROM tasks WHERE parent = '" . $projectid . "'";
			//$result = mysqli_query($this->conn, $sql);
			//$taskids = array();
			//if (mysqli_num_rows($result) > 0)
			//{
			//	while($row = mysqli_fetch_assoc($result))
			//	{			
			//		array_push($taskids, $row['id']);
			//	}
			//}
			//$alletaken = count($taskids);
			//$currenttimestamp = strtotime(date('Y-m-d'));
			return $task;
		}
	}
	
	public function GetAllTasksFromProject($projectId){
		$sql = "SELECT * FROM tasks WHERE parent = " . $projectId . " ORDER BY id";
		$result = mysqli_query($this->conn, $sql);
		$tasks = array();
		if (mysqli_num_rows($result) > 0)
		{
			while($task = mysqli_fetch_object($result))
			{
				if(array_key_exists($task->task_parent, $tasks)){
					$tasks[$task->task_parent]->tasks[$task->id] = $task;
				}else{
					$tasks[$task->id] = $task;
				}
				
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
	

}

?>