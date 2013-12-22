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
<center><h2>The KANBAN Board Tutorial</h2></center>
<h3>The PostIt Notes</h3>
	<br/>
	<img src="/assets/images/postit-example.png"/>
	<br/>
	<ul>
	<li><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> This icon describes the priority of the Task, high value equals high priority</li>
	<li><span class="ui-icon ui-icon-clock"></span> This icon describes the estimated time/effort in points (typically you would choose hours or days)</li>
	<li><span class="ui-icon ui-icon-calendar"></span> This icon describes how long agoe in days it was since this particular Task was added, it helps to keep track of Task that have been lying arounf for too long</li>
	<li><span class="ui-icon ui-icon-comment"></span> This icon shows if there are any comments associated with the Task, and you can clock on it to add / view comments.</li>
	<li><span class="ui-icon ui-icon-wrench"></span>Click on this icon to edit the details, and / or give current estimation updates</li>
	</ul>
<h3>The Edit Dialog</h3>
	<br/>
	<img src="/assets/images/editdialog-example.png"/>
	<br/>
	In this dialog you can edit the following
	<ul>
	<li>Task/Title - The title, needs to further explaination :-)</li>
	<li>Text - Describing the Task/Title a little bit further</li>
	<li>Priority - An integer value from 0 and up, where high number equals high prioority</li>
	<li>Estimate - This is the original estimatation that was made during the spring planning game</li>
	<li>Remaining Today - This is the number of points that remains "Today". Here it is possible to come in every day and give a new update on the estimate, this will be reflected in the progress diagram / burndown chart in the status page. If not used it will regard the task to be unfinished until it has reached the final group at which point it's remaining will be set to 0 automatically.</li>
	<li>Color Tag - Currently 5 colors are supported (quite useful apparently...)</li>
	<li>Workpackage - This is a way to group Tasks together into Work Packages, so that it is possible in the status page to monitor progress on a Work Packge basis.</li>
	<li>Move To Sprint -  Here it is possible to move this particular Task to another Sprint.</li>
	</ul>
</span> 
<br><br>

<br>


</div>
</div>

</body>
</html>


