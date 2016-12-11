<?php
// Task details
$tasks = new Tasks();
if(isset($_GET['task']))
{
	//zoek taak in db
	$task = $tasks->getTask($_GET['task']);
	$currenttimestamp = strtotime(date('Y-m-d'));
}else{
	die("no task?");
}
?>
<div id="page-inner">
    <div class="row">
		<div class="col-md-12">
			<h2><?php echo($task->smalltext); ?></h2>
			<h6>Verantwoordelijke: <?php echo($task->user->user_name); ?></h6>		
		</div>
	</div>
	<!-- /. ROW  -->
	<hr />
	<div class="row">
		<div class="col-md-8">
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#overzicht" data-toggle="tab">Overzicht</a></li>
				<li class=""><a href="#taken" data-toggle="tab">Subtaken <span class="badge"><?php echo(count($tasks->GetAllTasksFromParent($task->id))); ?></span></a></li>
				<li class=""><a href="#berichten" data-toggle="tab">Berichten <span class="badge">todo</span></a></li>
				<li class=""><a href="#team" data-toggle="tab">Team<span class="badge"><?php echo(count($tasks->GetAllTaskUsers($task->id))); ?></span></a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active in" id="overzicht">
					<h4>Omschrijving taak:</h4>			
					<?php echo($task->smalltext); ?><hr>
					<?php echo($task->bigtext); ?><hr>
					<h4>Doelstelling:</h4>
					Tijd verstreken tot deadline: <b><?php echo(date('Y-m-d', $task->deadline));?></b>
					<div class="progress">
					  <?php
					  $percent_verschil = round((($currenttimestamp - $task->startdatum) / ($task->deadline - $task->startdatum) *100),0);
					  ?>
					  <div class="progress-bar progress-bar-<?php
						if ($percent_verschil <= 25)
						{
							echo "success";
						}
						if ($percent_verschil > 25 && $percent_verschil <= 75)
						{
							echo "info";
						}						
						if ($percent_verschil > 75 && $percent_verschil <= 100)
						{
							echo "warning";
						}				
						if ($percent_verschil > 100)
						{
							echo "danger";
						}							
					  ?>
					  " style="width: <?php echo($percent_verschil);?>%">
						<?php echo($percent_verschil) . "%";?>
					 </div>
				</div>
			</div>
			<div class="tab-pane fade" id="taken">
				<h4>Taken:</h4>	
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Projectnaam</th>
							<th>Eigenaar</th>
							<th>Deadline</th>
							<th>Tijd  verstreken</th>
						</tr>
					</thead>
					<tbody>					
					<?php
						//var_dump($tasks->GetAllTasksFromParent($task->id));
						$childTasks = $tasks->GetAllTasksFromParent($task->id);
						$teller = 0;
						foreach($childTasks as $childTask)
						{
							$deadline = $childTask->deadline;
							if($currenttimestamp < $deadline)
							{
								echo "<tr onclick=\"window.document.location='?task=" . $childTask->id . "';\" class=\"success\">\n";
							}else{
								echo "<tr onclick=\"window.document.location='?task=" . $childTask->id . "';\" class=\"danger\">\n";
							}
							echo "	<td>" . $teller . "</td>\n";
							$teller++;
							echo "	<td>" . $childTask->user->username . " (" . $childTask->user->user_name . ")</td>\n";
							echo "	<td>" . date('Y-m-d', $deadline) . "</td>\n";
							echo "	<td>" . $childTask->smalltext . "</td>\n";	
							$percent_verschil = round((($currenttimestamp - $childTask->startdatum) / ($deadline - $childTask->startdatum) *100),0);
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
			</div>	
			<div class="tab-pane fade" id="berichten">
				<h4>Berichten komen hier:</h4>	
				<?php
					
				?>
			</div>				
			<div class="tab-pane fade" id="team">
				<h4>Team:</h4>
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>Naam</th>
								<th>Gebruikersnaam</th>
								<th>E-mail</th>
							</tr>
						</thead>
						<tbody>				
				<?php
					$teamMembers = $tasks->GetAllTaskUsers($task->id);
					foreach($teamMembers as $teamMember)
					{
						echo "<tr onclick=\"window.document.location='?user=" . $teamMember->userid . "';\">\n";								
						echo "	<td>" . $teamMember->user_name . "</td>\n";
						echo "	<td>" . $teamMember->username . "</td>\n";
						echo "	<td>" . $teamMember->usermail . "</td>\n";											
						echo "</tr>\n";						
					}
				?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>