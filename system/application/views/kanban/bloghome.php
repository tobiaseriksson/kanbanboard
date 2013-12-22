<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta charset="UTF-8" />
	<title>The '<?php echo $projectname; ?>' Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" />

	<script type="text/javascript" src="/assets/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.ui.touch-punch.js"></script>
	
	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

	<style type="text/css">
		#wrapper {
			width: 800px; 
			margin-right: auto;
			margin-left: auto;
		}
				
		#settingsdiv {
			padding: 30px 20px 30px 20px;
			border-color:#CCCCCC;
			border-style: solid;
			border-width:thin;
		}
		
		#lefthandside {
			float: left;
			width: 300px;
			padding-left: 10px;
			border-left: 1px dashed #999999;
		}
		#righthandside {
			float: right;
			width: 300px;
			padding-right: 10px;
			border-right: 1px dashed #999999;
		}
			
    </style>

		<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31228295-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body bgcolor=white>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanbanblog'); ?>">Home</a></li>
		<li><a href="<?php echo site_url( '/kanbanblog/tutorials'); ?>">Tutorials</a></li>
		<li><a href="<?php echo site_url( '/kanbanblog/about'); ?>">About</a></li>
		<li class="last"><a href="<?php echo site_url( '/kanban/login'); ?>">Login / Register</a></li>
	</ul>
</div>

<div class="banner">
	<div class="logo"></div>
	<div class="projecttitle">The Kanban Blog</div>
	<div class="subtitle">bla bla bla...</div>
</div>

<div id="errordiv"></div>

<div id="wrapper">

<div id="settingsdiv">


<span>
<center><h2>The KANBAN Board</h2></center>
<i>The KANBAN Board</i> is a yet another Task Board tool. There are many out there but we believe that this does it a little bit different. The intention has been to try and mimic a real white board as much as possible, where you move PostIt notes around to display progress. In this tool we also incorporate burndown charts together with inflow and outflow diagrams. In fact we have also added resource management so that the burndown chart reflects the actual real world situation as much as possible.<br>
<h3>Features</h3>
<img src="/assets/images/board-with-reflection.jpg" align="right" width="400px" height="270px" />
<ul>
	<li>White board feeling, moving PostIt notes around</li>
	<li>Touch Support for e.g. iPad and Android Surfboards</li>
	<li>Adding new Tasks dynamically, with Title, short text, Priority, Original Estimation, Color-tag</li>
	<li>PostIt's / Tasks can be assigned 6 different colors, e.g. to visualize priority or grouping.</li>
	<li>Adding groups/buckets dynamically, e.g Unassinged, Analysis, Implementation, Test, Done</li>
	<li>Adding Sprints dynamically, with start and end dates</li>
	<li>Moving unfinished tasks from one sprint to another</li>
	<li>Moving and Deleting induvidual tasks</li>
	<li>Importing Tasks to a Sprint through a CSV format</li>
	<li>The concept of WorkPackages, so that several Tasks can be grouped together(and followuped up)</li>
	<li>Resource management on a Sprint by Sprint basis</li>
	<li>Possibility to specify the initial effeiciency level, not all teams are 100% productive...</li>
	<li>Sprint Status Followup</li>
	<ul>
		<li>Summary of Velocity, Average Efficiency, Sprint Duration and Days left in sprint</li>
		<li>Burndown chart</li>
		<li>Inflow / Outflow diagram</li>
		<li>Effeciency diagram, on a week by week basis</li>
		<li>Induvidual Task progress change matrix</li>
		<li>List of Sprint task status</li>		
	</ul>
	<li>Project Status Followup</li>
	<ul>
		<li>Earned value graph, project progress </li>
		<li>List of Workpackages with their progress and induvidual Task progress</li>
		<li>Complete list of all Sprints and Tasks</li>
	</ul>
	<li>Ability to give daily Task progress, gives better granularity in the Burndown chart</li>
	<li>Each Tasks is presented with an Age, to easily spot old and new Tasks on thr board</li>
	<li>A TimeLine to display/share important dates</li>
	<li>WIP (Work In Progress) limit support (each group can be assigned a limit)</li>
	<li>Multiple comments can be added to each Task</li>
</ul>
</span> 
<br><br>

<br>


</div>
</div>

</body>
</html>


