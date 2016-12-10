<?php
session_start();
require("db_connect.php");
require("mail.php");
require_once("classes/Tasks.class.php");
require_once("classes/Projects.class.php");

$tasks = new Tasks();
if (!isset($_SESSION['whip_username']))
{
	die('<META HTTP-EQUIV=REFRESH CONTENT="0; login.php">');
}
?>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Whip it... Whip it good!</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="resources/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="resources/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="resources/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
	<div id="wrapper">
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="images/whip.png" width=30 height=30></img>&nbsp;WHIP</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">!Placeholder</a></li>
                        <li><a href="#">!Placeholder</a></li>
                        <li><a href="#">!Placeholder</a></li>
                    </ul>
                </div>

            </div>
        </div>
		<!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center user-image-back">
                        <img src="images/brixel_logo.png" class="img-responsive" />
                     
                    </li>


                    <li>
                        <a href="index.php"><i class="fa fa-desktop "></i>Overzicht</a>
                    </li>
					<li>
                        <a href="#"><i class="fa fa-tasks "></i>Mijn taken<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
							<?php
								$taskList = $tasks->GetAllMyTasks($_SESSION['whip_userid']);
								$tasks->renderTasks($taskList);
							?>
                        </ul>
                    </li>
                </ul>
            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
			<?php
				//include page where needed
				if(isset($_GET['project']))
				{
					include("pages/projects.php");
				}elseif(isset($_GET['task']))
				{
					include("pages/tasks.php");
				}elseif(isset($_GET['user']))
				{
					include("pages/users.php");
				}else{
					include("pages/overview.php");
				}
			?>
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="resources/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="resources/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="resources/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="resources/js/custom.js"></script>
</body>
</html>