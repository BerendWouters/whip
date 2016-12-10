<?php
$timestamp = strtotime(date('Y-m-d'));
$projects = new Projects();
$tasks = new Tasks();
$users = new Users();
?>
<div id="page-inner">
    <div class="row">
		<div class="col-md-12">
			<h2>Overzicht</h2>
		</div>
	</div>
	<!-- /. ROW  -->
	<hr />
	<div class="row">	
		<div class="col-md-10">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#projecten" data-toggle="tab">Projecten</a>
				</li>
				<li class=""><a href="#taken" data-toggle="tab">Taken</a>
				</li>
				<li class=""><a href="#berichten" data-toggle="tab">Berichten</a>
				</li>
				<li class=""><a href="#herinneringen" data-toggle="tab">Herinneringen</a>
				</li>
				<li class=""><a href="#team" data-toggle="tab">Team</a>
				</li>				
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active in" id="projecten">
					<h4>Openstaande Projecten&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-success">Nieuw project</a></h4>
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
							$teller = 1;
							$projectList = $projects->GetProjects(0);
							foreach ($projectList as $project) {
								if($timestamp < $project->deadline)
								{
									echo "<tr onclick=\"window.document.location='?project=" . $project->id . "';\" class=\"success\">\n";
								}else{
									echo "<tr onclick=\"window.document.location='?project=" . $project->id . "';\" class=\"danger\">\n";
								}
								echo "	<td>" . $teller . "</td>\n";
								$teller++;
								echo "	<td>" . $project->naam . "</td>\n";
								echo "	<td>" . $project->user->user_name . " (" . $project->user->username . ")</td>\n";
								echo "	<td>" . date('Y-m-d', $project->deadline) . "</td>\n";
								$percent_verschil = round((($timestamp - $project->startdatum) / ($project->deadline - $project->startdatum) *100),0);
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
								$dagen_verschil = round((abs($timestamp - $project->deadline)/60/60/24),1);
								if($timestamp > $project->deadline)
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
				<div class="tab-pane fade" id="taken">
					<h4>Openstaande Taken</h4>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#mijntaken" data-toggle="tab">Mijn taken</a>
						</li>
						<li class=""><a href="#alletaken" data-toggle="tab">Alle taken</a>
						</li>					
					</ul>
					<div class="tab-content">					
						<div class="tab-pane fade active in" id="mijntaken">
							<h4>Mijn taken&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-success">Nieuwe taak</a></h4>
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Eigenaar</th>
										<th>Deadline</th>
										<th>Omschrijving</th>
										<th>Project</th>
										<th>Tijd verstreken</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$teller = 1;
									$taskList = $tasks->GetAllMyParentTasks($_SESSION['whip_userid']);
									foreach ($taskList as $task) {
										if($timestamp < $task->deadline)
										{
											echo "<tr onclick=\"window.document.location='?task=" . $task->id . "';\" class=\"success\">\n";
										}else{
											echo "<tr onclick=\"window.document.location='?task=" . $task->id . "';\" class=\"danger\">\n";
										}
										echo "	<td>" . $teller . "</td>\n";
										$teller++;
										echo "	<td>" . $task->user->user_name . "(" . $task->user->username . ")</td>\n";
										echo "	<td>" . date('Y-m-d', $task->deadline) . "</td>\n";
										echo "	<td>" . $task->smalltext . "</td>\n";	
										
										
										echo "<td>" . $task->project->naam . "</td>\n";
										
										$percent_verschil = round((($timestamp - $task->startdatum) / ($task->deadline - $task->startdatum) *100),0);
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
										$dagen_verschil = round((abs($timestamp - $task->deadline)/60/60/24),1);
										if($timestamp > $task->deadline)
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
						<div class="tab-pane fade" id="alletaken">
							<h4>Alle taken&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-success">Nieuwe taak</a></h4>
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Eigenaar</th>
										<th>Deadline</th>
										<th>Omschrijving</th>
										<th>Project</th>
										<th>Tijd verstreken</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$teller = 1;
									$taskList = $tasks->GetAllTasks();
									foreach ($taskList as $task) {
										if($timestamp < $task->deadline)
										{
											echo "<tr onclick=\"window.document.location='?task=" . $task->id . "';\" class=\"success\">\n";
										}else{
											echo "<tr onclick=\"window.document.location='?task=" . $task->id . "';\" class=\"danger\">\n";
										}
										echo "	<td>" . $teller . "</td>\n";
										$teller++;
										echo "	<td>" . $task->user->user_name . "(" . $task->user->username . ")</td>\n";
										echo "	<td>" . date('Y-m-d', $task->deadline) . "</td>\n";
										echo "	<td>" . $task->smalltext . "</td>\n";	
										
										
										echo "<td>" . $task->project->naam . "</td>\n";
										
										$percent_verschil = round((($timestamp - $task->startdatum) / ($task->deadline - $task->startdatum) *100),0);
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
										$dagen_verschil = round((abs($timestamp - $task->deadline)/60/60/24),1);
										if($timestamp > $task->deadline)
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
				
				<div class="tab-pane fade" id="berichten">
					<h4>Berichten</h4>
					<p >
						Hier komen de berichten
					</p>

				</div>
				<div class="tab-pane fade" id="herinneringen">
					<h4>Herinneringen</h4>
					<p >
						Hier komen de herinneringen
					</p>

				</div>			
				<div class="tab-pane fade" id="team">
					<h4>Team</h4>
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
							$teller = 1;
							$userList = $users->GetAllVerifiedUsers();							
							foreach ($userList as $user) {
								echo "<tr>\n";								
								echo "	<td>" . $user->user_name . "</td>\n";
								echo "	<td>" . $user->username . "</td>\n";
								echo "	<td>" . $user->usermail . "</td>\n";											
								echo "</tr>\n";
							}									
							?>
						</tbody>
						</table>

				</div>				

			</div>
		</div>

	</div>
	<!-- /. ROW  -->
</div>