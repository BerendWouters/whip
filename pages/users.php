<?php
// Task details
$users = new Users();
if(isset($_GET['user']))
{
	//zoek iser in db
	$user = $users->getUser($_GET['user']);
	$currenttimestamp = strtotime(date('Y-m-d'));
}else{
	die("no user?");
}
?>
<div id="page-inner">
    <div class="row">
		<div class="col-md-12">
			<h2><?php echo("Gebruiker: " . $user->username . " (" . $user->user_name . ")"); ?></h2>
		</div>
	</div>
	<!-- /. ROW  -->
	<hr />
	<div class="row">
		<div class="col-md-8">
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#overzicht" data-toggle="tab">Overzicht</a></li>
				<li class=""><a href="#projecten" data-toggle="tab">Projecten<span class="badge">todo</span></a></li>				
				<li class=""><a href="#taken" data-toggle="tab">taken <span class="badge"><?php echo(count($tasks->GetAllTasksFromUser($user->userid))+count($tasks->GetAllTasksWhereUserIsAssigned($user->userid)));?></span></a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active in" id="overzicht">
				</div>
				<div class="tab-pane fade" id="projecten">
				</div>
				<div class="tab-pane fade" id="taken">
					<h4>Taken waar <?php echo($user->user_name);?> aan werkt:</h4>
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
							$tasks_owner = $tasks->GetAllTasksFromUser($user->userid);
							$teller = 0;
							foreach($tasks_owner as $task)
							{
								$deadline = $task->deadline;
								if($currenttimestamp < $deadline)
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
							//GetAllTasksWhereUserIsAssigned
							$tasks_assigned = $tasks->GetAllTasksWhereUserIsAssigned($user->userid);
							foreach($tasks_assigned as $task)
							{
								$deadline = $task->deadline;
								if($currenttimestamp < $deadline)
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
					
				</div>
			</div>
		</div>
	</div>
</div>