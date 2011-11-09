<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>The '<?php echo $projectname; ?>' Kanban Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.8.1.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="/assets/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.8.1.custom.min.js"></script>
	<script src="/assets/js/raphael-min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="/assets/js/g.raphael-min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="/assets/js/g.line-min.js" type="text/javascript" charset="utf-8"></script> 

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
		
		.lefthandside {
			vertical-align: top;
		}
		.righthandside {
			vertical-align: top;
		}
		
		.settingsleftside {
			text-align: right;
			vertical-align: top;
			width: 100px;
		}

		.settingsrightside {
			text-align: left;
			vertical-align: top;
			width: 100px;
		}


		.settingstable {
			border: 0px;
			vertical-align: top;
		}
			
		.settingsmiddleseparator {
			width: 50px;
		}
		
		.sprintlist {
			white-space: nowrap;
		}
		
	</style>

	<script type="text/javascript">
		function deleteTicker(tickerid) {
			dataString=tickerid;
			$.ajax({  
			  dataType: "json",  
			  url: "/kanban/deleteticker/"+tickerid,  
			  data: dataString,  
			  success: function(data) {  			
				$('#ticker'+tickerid).remove();
				$("#result").html("deleted!");
			  }, 
			  error: function(x,e) {  
				$("#result").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
			  }  
		});  
	}
	</script>

</head>
<body>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanban/project/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
		<li><a href="<?php echo site_url( '/kanban/status/'.$projectid.'/'.$sprintid ); ?>">Status</a></li>
		<li><a href="<?php echo site_url( '/kanban/settings/'.$projectid.'/'.$sprintid ); ?>">Settings</a></li>	
		<li><a href="<?php echo site_url( '/kanban/selectproject'); ?>">Projects</a></li>
		<li><a href="<?php echo site_url( '/kanban/about/'.$projectid.'/'.$sprintid ); ?>">About</a></li>
		<li class="last"><a href="<?php echo site_url( '/kanban/logout'); ?>">Logout</a></li>
	</ul>
</div>

<div class="banner">
	<div class="logo"></div>
	<div class="projecttitle">The '<?php echo $projectname; ?>' Board</div>
	<div class="subtitle">Sprint: <?php echo $sprintname; ?> <?php echo $startdate; ?> through <?php echo $enddate; ?></div>
</div>

<div id="errordiv"></div>

<div id="wrapper">

<div id="settingsdiv">

<h2>Ticker Messages</h2>
<form id="newticker" name="newticker" action="/kanban/addticker" method="post">
<input type="hidden" name="projectid" id="projectid" value="<?php echo $projectid; ?>">
<input type="hidden" name="sprintid" id="sprintid" value="<?php echo $sprintid; ?>">
<table>
<tr><td>Message</td><td><input type="text" id=msg name=msg value="anything..."> </td></tr>
<tr><td>Enddate</td><td><input type="text" id=enddate name=enddate value="<?php echo date("y-m-d"); ?>"> </td></tr>
<tr><td></td><td><input type="submit" id=submit name=submit value="Add"> </td></tr>

</table>
</form>
<hr>

<table>
<tr><td>#</td><td>Enddate</td><td>Message</td><td></td></tr>
<?php
$i=1;
foreach ($tickers as $ticker) {	
	echo '<tr id="ticker'.$ticker['id'].'"><td>'.$i.'</td><td>'.$ticker['enddate'].'</td><td>'.$ticker['message'].'</td><td><span class="ui-icon ui-icon-closethick" onclick="deleteTicker('.$ticker['id'].'); return false;"></td></tr>';
	$i=$i+1;
}
?>
</table>
<div id="#result"></div>
</div>
</div>

</body>
</html>

