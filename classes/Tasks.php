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


	public function GetSubTasks($userId, $parentId){
		$sql = "SELECT * FROM tasks WHERE completed = 0  AND task_parent = " . $parentId . " AND owner = " . $userId;
		$result = mysqli_query($this->conn, $sql);	
		return $result;
	}
}

?>