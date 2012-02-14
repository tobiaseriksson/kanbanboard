<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<! base href="http://kanban.tsoft.se/" />
	<base href="<?php echo site_url( '/' ); ?>" />
	<title>The '<?php echo $projectname; ?>' Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
	<link type="text/css" href="/assets/ticker/styles/ticker-style.css" rel="stylesheet" />
	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

	<script type="text/javascript" src="/assets/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.ui.touch-punch.js"></script>

	<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dojo/dojo.xd.js"
				data-dojo-config="isDebug: false,parseOnLoad: true">
	</script>
	
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
			
		.diagram {
			width: 700px;
			height:500px;
			overflow: hidden;
		}
		
    </style>
    
<script>
			// http://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dojo/dojo.xd.js
			// Require the basic 2d chart resource: Chart2D
			dojo.require("dojox.charting.Chart2D");

			// Require the theme of our choosing
			//"Claro", new in Dojo 1.6, will be used
			dojo.require("dojox.charting.themes.MiamiNice");
			
			dojo.require("dojox.charting.widget.Legend");

			//
			// Burndown chart
			//
			// Define the data
			<?php 
				$tmpstr = "var baseline  = [ ";
				foreach ($diagrambaseline as $row)
				{			
					$tmpstr = $tmpstr."{ x: ".$row[0].",y: ".$row[1]." },";
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
				
				$tmpstr = "var progress  = [ ";
				foreach ($diagramactual as $row)
				{			
					$tmpstr = $tmpstr."{ x: ".$row[0].",y: ".$row[1]." },";
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
				
				$tmpstr = "var projected  = [ ";
				foreach ($diagramprojected as $row)
				{			
					$tmpstr = $tmpstr."{ x: ".$row[0].",y: ".$row[1]." },";
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
				
				$tmpstr = "var plan  = [ ";
				foreach ($diagrameffort as $row)
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
				var chart = new dojox.charting.Chart2D("burndownchart");

				// Set the theme
				chart.setTheme(dojox.charting.themes.MiamiNice);

				// Add the only/default plot 
				chart.addPlot("default", {
					type: "Lines",
					markers: false,
					animate:{duration: 1000} 
				});
				
				// Add axes
				chart.addAxis("x",{  min: 0, fixLower: "major", fixUpper: "major"   });
				chart.addAxis("y", {  min: 0, vertical: true, fixLower: "major", fixUpper: "major"  });

				// Add the series of data
				chart.addSeries("Baseline",baseline, {plot: "Lines", stroke: {color:"green"} });
				chart.addSeries("Progress",progress, {plot: "Lines", stroke: {color:"blue", style: "Solid"} });
				chart.addSeries("Projected",projected, {plot: "Lines", stroke: {color:"#2E64FE", style: "Dash"} });
				chart.addSeries("Plan",plan, {plot: "Lines", stroke: {color:"red"} });
				
				// Render the chart!
				chart.render();
				// Add Legend to the bottom
				var outflowinflowlegend = new dojox.charting.widget.Legend({chart: chart}, "burndownchartlegend");
				
			});

			// 
			// Efficiency
			// 
			<?php 
				$tmpstr = "\nvar efficiencydiagram  = [ ";
				$weeknumbers = ", labels: [ ";
				$i = 1;
				foreach ($efficiencydiagram as $row)
				{			
					$tmpstr = $tmpstr." ".$row[1].",";
					$weeknumbers = $weeknumbers.'{ value: '.$i.', text: "w'.$row[0].'"},';
					$i++;
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				$weeknumbers = rtrim( $weeknumbers, "," );
				$weeknumbers = $weeknumbers." ] ";
				echo $tmpstr;
				
			?>
			
			// When the DOM is ready and resources are loaded...
			dojo.ready(function() {
				var chart = new dojox.charting.Chart2D("efficiencydiagram");
				chart.addPlot("default", {type:"ClusteredColumns",gap:2,animate:{duration: 1000} });
				chart.addAxis("x",{  min: 0 <?php echo $weeknumbers; ?>  });
				chart.addAxis("y",{ vertical : true, min: 0, fixLower: "major", fixUpper: "major" });
				chart.addSeries("Efficiency",efficiencydiagram);
				// Set the theme
				chart.setTheme(dojox.charting.themes.MiamiNice);
				// Render the chart!
				chart.render();
				var efficiencydiagramlegend = new dojox.charting.widget.Legend({chart: chart}, "efficiencydiagramlegend");
				
			});

			
			// 
			// Inflow / Outflow chart
			// 
			<?php 
				$tmpstr = "\nvar diagraminflow  = [ ";
				foreach ($diagraminflow as $row)
				{			
					$tmpstr = $tmpstr." ".$row.",";
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
				$tmpstr = "var diagramoutflow  = [ ";
				foreach ($diagramoutflow as $row)
				{			
					$tmpstr = $tmpstr." ".$row.",";
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
			?>
			
			// When the DOM is ready and resources are loaded...
			dojo.ready(function() {
				var chart = new dojox.charting.Chart2D("inflowoutflowchart");
				chart.addPlot("default", {type:"ClusteredColumns",gap:2,animate:{duration: 1000} });
				chart.addAxis("x",{  min: 0 });
				chart.addAxis("y",{ vertical : true, min: 0, fixLower: "major", fixUpper: "major"   });
				chart.addSeries("Inflow",diagraminflow);
				chart.addSeries("Outflow",diagramoutflow);
				// Set the theme
				chart.setTheme(dojox.charting.themes.MiamiNice);
				// Render the chart!
				chart.render();
				// Add Legend to the bottom
				var outflowinflowlegend = new dojox.charting.widget.Legend({chart: chart}, "inflowoutflowlegend");
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

Current velocity is <?php echo round( $velocity, 1); ?> points / day 
</h3>
</span> 
<br><br>
</center>
<br>
<h2>Burndown Chart</h2>
<div id="burndownchart" class="diagram"></div>
<div id="burndownchartlegend"></div>
<br>
<h2>Progress History Matrix</h2>
<?php 
		echo "<table border=1px >";
		echo "<th>ID</th><th>Heading</th>";
		for( $i=1; $i<=$days; $i++) echo "<th>".$i."</th>";
		foreach( $progressmatrix as $id => $arr ) {
			echo "<tr><th>".$id."</th><th>".$tasklookup[ $id ]."</th>";
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo ",(".$day.")";
				echo "<td>".$value."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
?>
<br>
<h2>Weekly Efficiency</h2>
<div id="efficiencydiagram" class="diagram"></div>
<div id="efficiencydiagramlegend"></div>
<br>
<h2>Inflow / Outflow Chart</h2>
<div id="inflowoutflowchart" class="diagram"></div>
<div id="inflowoutflowlegend"></div>
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


