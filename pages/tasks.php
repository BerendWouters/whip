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
		</div>
	</div>
	<!-- /. ROW  -->
	<hr />
	<div class="row">
		<div class="col-md-8">
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#overzicht" data-toggle="tab">Overzicht</a></li>
				<li class=""><a href="#berichten" data-toggle="tab">Berichten <span class="badge">todo</span></a></li>
				<li class=""><a href="#team" data-toggle="tab">Team<span class="badge">todo</span></a></li>
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
			<div class="tab-pane fade" id="berichten">
				<h4>Berichten komen hier:</h4>	
				<?php
					
				?>
			</div>				
			<div class="tab-pane fade" id="team">
				<h4>Team:</h4>	
				<?php
					var_dump($tasks->GetAllTaskUsers($task->id));
					
				?>
			</div>
		</div>
	</div>
</div>