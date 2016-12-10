<?php
$projects = new Projects();
$tasks = new Tasks();
if(isset($_GET['project']))
{
	//zoek project in db
	$project = $projects->getProject($_GET['project']);
	$currenttimestamp = strtotime(date('Y-m-d'));
	// }else{
	// 	echo "		<div id=\"page-inner\">\n";
	// 	echo "			<div class=\"row\">\n";
	// 	echo "				<div class=\"col-md-12\">\n";
	// 	echo "					<h2>Project niet gevonden!</h2>\n";
	// 	echo "		</div>\n";
	// 	echo "	</div>\n";
	// 	echo "</div>\n";
	// }
}else{
	die("no project?");
}
?>
<div id="page-inner">
    <div class="row">
		<div class="col-md-12">
			<h2><?php echo($project->naam); ?></h2>
		</div>
	</div>
	<!-- /. ROW  -->
	<hr />
	<div class="row">
		<div class="col-md-8">
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#overzicht" data-toggle="tab">Overzicht</a>
				</li>
				<li class=""><a href="#taken" data-toggle="tab">Taken <span class="badge"><?php echo($project->taskCount);?></span></a>
				</li>
				<li class=""><a href="#berichten" data-toggle="tab">Berichten <span class="badge">todo</span></a>
				</li>	
				<li class=""><a href="#team" data-toggle="tab">Team <span class="badge">todo</span></a>
				</li>		
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active in" id="overzicht">
					<h4>Omschrijving project:</h4>			
					<?php echo($project->omschrijving); ?><hr>
					<h4>Status:</h4>
					<?php
						//tel open en gesloten taken
						$sql = "SELECT * FROM tasks WHERE parent = '" . $project->id . "' AND completed = 0 AND task_parent = 0";
						$result = mysqli_query($SQL_conn, $sql);
						$opentaken = mysqli_num_rows($result);
						$sql = "SELECT * FROM tasks WHERE parent = '" . $project->id . "' AND completed = 1  AND task_parent = 0";
						$result = mysqli_query($SQL_conn, $sql);
						$geslotentaken = mysqli_num_rows($result);		
						echo $geslotentaken . " melding(en) gesloten van de " . $project->taskCount . ".";
						$percentueel_gesloten = round((($geslotentaken / $project->taskCount)*100),0);
					?>						
					<div class="progress">
					  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo($geslotentaken); ?>" aria-valuemin="0" aria-valuemax="<?php echo($project->taskCount);?>" style="width: <?php echo($percentueel_gesloten);?>%;">
							<?php echo($percentueel_gesloten) . "%";?>
					  </div>
					</div>	
					<hr>
					<h4>Overzicht deadlines:</h4>
					<?php
						//overloop taken
						$takenopenbinnendeadline = 0;
						$takenopenoverdeadline = 0;
						$takengeslotenbinnendeadline = 0;
						$takengeslotenoverdeadline = 0;						
						$sql = "SELECT * FROM tasks WHERE parent = '" . $project->id . "'  AND task_parent = 0";
						$result = mysqli_query($SQL_conn, $sql);
						$taskids = array();
						if (mysqli_num_rows($result) > 0)
						{
							while($row = mysqli_fetch_assoc($result))
							{	
								if($row['completed'] == 0)
								{
									//taak is nog lopende
									if($currenttimestamp <= $row['deadline'])
									{
										//binnen deadline
										$takenopenbinnendeadline ++;
									}else{
										//over deadline
										$takenopenoverdeadline ++;
									}
								}else{
									//taak is gesloten
									if($row['sluitingsdatum'] <= $row['deadline'])
									{
										//binnen deadline
										$takengeslotenbinnendeadline ++;
									}else{
										//over deadline
										$takengeslotenoverdeadline ++;
									}										
								}
							}
						}
						$percent_opengoed = round((($takenopenbinnendeadline / $project->taskCount)*100),0);
						$percent_openslecht = round((($takenopenoverdeadline / $project->taskCount)*100),0);
						$percent_geslotengoed = round((($takengeslotenbinnendeadline / $project->taskCount)*100),0);
						$percent_geslotenslecht = round((($takengeslotenoverdeadline / $project->taskCount)*100),0);
						
						echo "<div>";
						echo "<span class=\"label label-success\">Gesloten binnen deadline:" . $takengeslotenbinnendeadline . " (" . $percent_geslotengoed . "%)</span>&nbsp;";						
						echo "<span class=\"label label-info\">Open binnen deadline:" . $takenopenbinnendeadline . " (" . $percent_opengoed . "%)</span>&nbsp;";
						echo "<span class=\"label label-danger\">Open buiten deadline:" . $takenopenoverdeadline . " (" . $percent_openslecht . "%)</span>&nbsp;";
						echo "<span class=\"label label-warning\">Gesloten buiten deadline:" . $takengeslotenoverdeadline . " (" . $percent_geslotenslecht . "%)</span>&nbsp;";
						echo "</div><br>";

					?>
					<div class="progress">
					  <div class="progress-bar progress-bar-success" style="width: <?php echo($percent_geslotengoed);?>%">
						<?php echo($takengeslotenbinnendeadline);?>
					  </div>
					  <div class="progress-bar progress-bar-info" style="width: <?php echo($percent_opengoed);?>%">
						<?php echo($takenopenbinnendeadline);?>
					  </div>					  
					  <div class="progress-bar progress-bar-danger" style="width: <?php echo($percent_openslecht);?>%">
						<?php echo($takenopenoverdeadline);?>
					  </div>
					  <div class="progress-bar progress-bar-warning" style="width: <?php echo($percent_geslotenslecht);?>%">
						<?php echo($takengeslotenoverdeadline);?>
					  </div>
					</div>
					<br>
					<h4>Inzet:</h4>
					<?php
						// zoek mvp
						$sql = "SELECT owner, COUNT(id) AS aantal FROM tasks WHERE task_parent = 0 GROUP BY owner ORDER BY aantal DESC LIMIT 0,1";
						$result = mysqli_query($SQL_conn, $sql);
						$row = mysqli_fetch_assoc($result);
						$mvpid = $row['owner'];
						//resolve username
						$sql = "SELECT * FROM users WHERE userid='" . $mvpid . "'";
						$result = mysqli_query($SQL_conn, $sql);
						$row = mysqli_fetch_assoc($result);					
						$mvpnaam = $row['user_name'];
						$mvpusername = $row['username'];
						$mvpmail = $row['usermail'];
					?>
					Teamlid met de meeste taken: <a href="?user=<?php echo($mvpid);?>"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;<?php echo($mvpnaam . "(" . $mvpusername . ")");?></a>
				</div>
				<div class="tab-pane fade" id="taken">
					<h4>Overzicht open taken</h4>
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Eigenaar</th>
								<th>Deadline</th>
								<th>Omschrijving</th>
								<th>Tijd verstreken</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$teller = 1;
							$taskList = $tasks->GetAllTasksFromProject($project->id, 0);
							foreach ($taskList as $task) {
								$deadline = $task->deadline;
								if($currenttimestamp < $task->deadline)
								{
									echo "<tr onclick=\"window.document.location='?task=" . $task->id . "';\" class=\"success\">\n";
								}else{
									echo "<tr onclick=\"window.document.location='?task=" . $task->id . "';\" class=\"danger\">\n";
								}
								echo "	<td>" . $teller . "</td>\n";
								$teller++;
								echo "	<td>" . $task->user->username . " (" . $task->user->user_name . ")</td>\n";
								echo "	<td>" . date('Y-m-d', $deadline) . "</td>\n";
								echo "	<td>" . $task->smalltext . "</td>\n";	
								
							
								$percent_verschil = round((($currenttimestamp - $task->startdatum) / ($deadline - $task->startdatum) *100),0);
								echo "	<td>\n";
								echo "		<div class=\"progress\">\n";
								echo "			<div class=\"";
								if($percent_verschil <= 25)
								{
									echo "progress-bar progress-bar-success";
								}
								if($percent_verschil > 25 && $percent_verschil <= 75)
								{
									echo "progress-bar progress-bar-info";
								}
								if($percent_verschil > 75 && $percent_verschil <= 100)
								{
									echo "progress-bar progress-bar-warning";
								}
								if($percent_verschil > 100)
								{
									echo "progress-bar progress-bar-danger";
								}											
								echo "\" role=\"progressbar\" aria-valuenow=\"" . $percent_verschil . "\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: " . $percent_verschil . "%\">\n";
								echo "				" . $percent_verschil . "%\n";
								echo "			</div>\n";
								echo "		</div>\n";
								$dagen_verschil = round((abs($currenttimestamp - $deadline)/60/60/24),1);
								if($currenttimestamp > $deadline)
								{
									echo "<span class=\"label label-danger\">" . $dagen_verschil . " dag(en) over deadline.</span>";											
								}else{
									echo "<span class=\"label label-info\">" . $dagen_verschil . " dag(en) tot deadline.</span>";											
								}
								echo "	</td>\n";												
								echo "</tr>\n";
						}
							?>
						</tbody>
					</table>			
					<hr>
					<h4>Overzicht Gesloten taken</h4>
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Eigenaar</th>
								<th>Deadline</th>
								<th>Gesloten op</th>
								<th>Omschrijving</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$teller = 1;
							$taskList = $tasks->GetAllTasksFromProject($project->id, 1);
							foreach ($taskList as $task) {
								if($task->sluitingsdatum <= $task->deadline)
								{
									echo "<tr onclick=\"window.document.location='?task=" . $task->id . "';\" class=\"success\">\n";
								}else{
									echo "<tr onclick=\"window.document.location='?task=" . $task->id . "';\" class=\"danger\">\n";
								}
								echo "	<td>" . $teller . "</td>\n";
								$teller++;
								echo "	<td>" . $task->user->username . " (" . $task->user->user_name . ")</td>\n";
								echo "	<td>" . date('Y-m-d', $task->deadline) . "<sluitingsdatum/td>\n";
								echo "	<td>" . date('Y-m-d', $task->sluitingsdatum) . "</td>\n";
								echo "	<td>" . $task->smalltext . "</td>\n";			
								echo "</tr>\n";								
							}
							?>
						</tbody>
					</table>										
				</div>
				<div class="tab-pane fade" id="berichten">
					<h4>Overzicht berichten</h4>			
				</div>
				<div class="tab-pane fade" id="team">
					<h4>Overzicht team</h4>			
				</div>				
			</div>
		</div>
	</div>
</div>