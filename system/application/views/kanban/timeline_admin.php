<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<! base href="http://kanban.tsoft.se/" />
	<base href="<?php echo site_url( '/' ); ?>" />
	<title>The '<?php echo $projectname; ?>' Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
	<link type="text/css" href="/assets/ticker/styles/ticker-style.css" rel="stylesheet" />
	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

	<script type="text/javascript" src="/assets/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.ui.touch-punch.js"></script>

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
		function deleteTicker(id) {
			dataString=id;
			project_id=<?php echo $projectid;?>;
			$.ajax({  
			  dataType: "json",  
			  url: "/kanban/deletetimeline/"+project_id+"/"+id,  
			  data: dataString,  
			  success: function(data) {  			
				$('#timeline'+id).remove();
				$("#result").html("deleted!");
			  }, 
			  error: function(x,e) {  
				$("#result").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
			  }  
		});  
	}
	</script>
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
<body>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanban/newboard/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
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

<h2>TimeLine Messages</h2>
<form id="newticker" name="newticker" action="/kanban/addtimelinemessage" method="post">
<input type="hidden" name="projectid" id="projectid" value="<?php echo $projectid; ?>">
<input type="hidden" name="sprintid" id="sprintid" value="<?php echo $sprintid; ?>">
<table>
<tr><td>Message</td><td><input type="text" id=msg name=msg value="anything..."> </td></tr>
<tr><td>Date</td><td><input type="text" id=date name=date value="<?php echo date("y-m-d"); ?>"> </td></tr>
<tr><td></td><td><input type="submit" id=submit name=submit value="Add"> </td></tr>

</table>
</form>
<hr>

<table>
<tr><td>#</td><td>Date</td><td>Heading</td><td></td></tr>
<?php
$i=1;
foreach ($timelineitems as $item) {	
	echo '<tr id="timeline'.$item['id'].'"><td>'.$i.'</td><td>'.$item['date'].'</td><td>'.$item['headline'].'</td><td><span class="ui-icon ui-icon-closethick" onclick="deleteTicker('.$item['id'].'); return false;"></td></tr>';
	$i=$i+1;
}
?>
</table>
<div id="#result"></div>
</div>
</div>

</body>
</html>

