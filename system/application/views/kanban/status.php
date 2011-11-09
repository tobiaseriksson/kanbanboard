<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>The '<?php echo $projectname; ?>' Kanban Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.8.1.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="/assets/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.8.1.custom.min.js"></script>
	<script src="/assets/js/dojo/dojo.js" 
				data-dojo-config="isDebug: false,parseOnLoad: true">
	</script>
	
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
    
<script>
			// http://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dojo/dojo.xd.js
			// Require the basic 2d chart resource: Chart2D
			dojo.require("dojox.charting.Chart2D");

			// Require the theme of our choosing
			//"Claro", new in Dojo 1.6, will be used
			dojo.require("dojox.charting.themes.Claro");
			
			// Define the data
			// var chartData = [ 10000,9200,11811,12000,7662,13887,14200,12222,12000,10009,11288,12099];
			var chartData  = [ {x:1.2,y:9000},{x:2.9,y:400},{x:3,y:2811},{x:4.6,y:2000} ];
			var chartData2 = [ {x:0.1,y:5423},{x:2,y:4000},{x:3.3,y:1900},{x:5.1,y:2700} ];
			<?php 
				$tmpstr = "var diagrambaseline  = [ ";
				foreach ($diagrambaseline as $row)
				{			
					$tmpstr = $tmpstr."{ x: ".$row[0].",y: ".$row[1]." },";
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
				$tmpstr = "var diagramactual  = [ ";
				foreach ($diagramactual as $row)
				{			
					$tmpstr = $tmpstr."{ x: ".$row[0].",y: ".$row[1]." },";
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
			?>
			
			// When the DOM is ready and resources are loaded...
			dojo.ready(function() {
				
				// Create the chart within it's "holding" node
				var chart = new dojox.charting.Chart2D("dojochart");

				// Set the theme
				chart.setTheme(dojox.charting.themes.Claro);

				// Add the only/default plot 
				chart.addPlot("default", {
					type: "Lines",
					markers: true
				});
				
				// Add axes
				chart.addAxis("x",{  min: 0, fixLower: "major", fixUpper: "major"   });
				//chart.addAxis("y", { min: 5000, max: 15000, vertical: true, fixLower: "major", fixUpper: "major" });
				chart.addAxis("y", {  min: 0, vertical: true, fixLower: "major", fixUpper: "major"  });

				// Add the series of data
				chart.addSeries("Expected",diagrambaseline);
				chart.addSeries("Actual",diagramactual);

				//var tip = new dojox.charting.action2d.Tooltip(chart, "default");

				// Render the chart!
				chart.render();
				
			});
			
		</script>

		
</head>
<body bgcolor=white>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanban/project/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
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

<h2>Status</h2>
<center>
<span>
<h3>Currently <?php echo $daysleft; ?> days left<br><br>

Sprint duration is <?php echo $totaldays; ?> days<br><br>

Current velocity is <?php echo $velocity; ?> points / day 
</h3>
</span> 
<br><br>
</center>
<br>
<h2>Burndown Chart</h2>
<div id="dojochart" style="width:700px;height:500px;"></div>

<br>

<h2>Task List</h2>
<table>
<tr><td>Sprint</td><td>Started</td><td>Finished</td><td>Leadtime</td><td>Priority</td><td>Estimation</td><td>Task</td></tr>
<?php
	$name="";
	foreach ($legend as $row)
	{							
		if( $name != $row['sprintname'] ) {
			echo "<tr><td><b>".$row['sprintname']."</b></td>";
			$name = $row['sprintname'];
		} else {
			echo "<tr><td></td>";
		}
		if( $row['startdate'] == '0000-00-00' ) echo "<td></td>";
		else echo "<td>".$row['startdate']." (".$row['startweeknumber'].")</td>";
		if( $row['enddate'] == '0000-00-00' ) echo "<td></td>";
		else echo "<td>".$row['enddate']." (".$row['finishedweeknumber'].")</td>";
		echo "<td>".$row['leadtime']."</td>";
		echo "<td>".$row['priority']."</td>";
		echo "<td>".$row['estimation']."</td>";
		echo "<td>".$row['heading']."</td>";
		echo "</tr>";
	}
?>
</table>

</div>
</div>

</body>
</html>


