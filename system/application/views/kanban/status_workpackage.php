<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>The '<?php echo $projectname; ?>' Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
	<link type="text/css" href="/assets/ticker/styles/ticker-style.css" rel="stylesheet" />
	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

	<script type="text/javascript" src="/assets/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.ui.touch-punch.js"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dojo/dojo.xd.js"
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
				
				$tmpstr = "var goal  = [ ";
				foreach ($diagramgoal as $row)
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
				var chart = new dojox.charting.Chart2D("earnedvaluechart");

				// Set the theme
				chart.setTheme(dojox.charting.themes.MiamiNice);

				// Add the only/default plot 
				chart.addPlot("default", {
					type: "Lines",
					markers: false,
					animate:{duration: 1000} 
				});
				
				function parseDate(input) {
					  var parts = input.match(/(\d+)/g);
					  // new Date(year, month [, date [, hours[, minutes[, seconds[, ms]]]]])
					  return new Date(parts[0], parts[1]-1, parts[2]); // months are 0-based
				}

				var months = [ 'Jan', 'Feb', 'Mar', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec' ];
				var startDateString = "<?php echo $startdate; ?>";
				var startDate = parseDate( startDateString );
				var startDateAsMilliSecondsSinceEPOC = startDate.getTime();
				var oneDayInMilliSeconds = 3600 * 24 * 1000;
				var firstMonthDisplayed = 0;
				
				// Add axes
				var myLabelFunc = function(text, value, precision){
					var dateInMilliSecondsSinceEPOC = startDateAsMilliSecondsSinceEPOC + ( value * 	oneDayInMilliSeconds );
					var theDate = new Date( dateInMilliSecondsSinceEPOC );
					var dayOfMonth = theDate.getDate();
					if( firstMonthDisplayed <= 0 || dayOfMonth % 10 == 0 || dayOfMonth == 1 ) {
						var month = months[ theDate.getMonth() ];
						firstMonthDisplayed = 1;
						return dayOfMonth + ' ' + month;
					} 
					return dayOfMonth;
				};
				chart.addAxis("x",{  min: 0, labelFunc: myLabelFunc   });
				chart.addAxis("y", {  min: 0, vertical: true, fixLower: "major", fixUpper: "major"  });

				// Add the series of data
				chart.addSeries("Goal",goal, {plot: "Lines", stroke: {color:"green"} });
				chart.addSeries("Progress",progress, {plot: "Lines", stroke: {color:"blue", style: "Solid"} });
				chart.addSeries("Projected",projected, {plot: "Lines", stroke: {color:"#2E64FE", style: "Dash"} });
				chart.addSeries("Plan",plan, {plot: "Lines", stroke: {color:"red"} });
				
				// Render the chart!
				chart.render();
				// Add Legend to the bottom
				var outflowinflowlegend = new dojox.charting.widget.Legend({chart: chart}, "earnedvaluechartlegend");
				
			});

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
<body bgcolor=white>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanban/newboard/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
		<li><a href="<?php echo site_url( '/kanban/status/'.$projectid.'/'.$sprintid ); ?>">Sprint-Status</a></li>	
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

			<h2>Project Status</h2>
			
			<h2>Earned Value Chart</h2>
				<div id="earnedvaluechart" class="diagram"></div>
				<div id="earnedvaluechartlegend"></div>
			<br>

			
			<h2>Workpackages</h2>
			<br>

<table>
<tr><th>WorkPackage</th><th>Sprint</th><th>Task</th><th>Prio</th><th>Orig Estimation</th><th>Left</th><th>Progess</th></tr>
<?php
	$wpname="";
	$wpestimate = 0;
	$wpnewestimate = 0;
	foreach ($wporder as $itemid)
	{							
		if( $wpname != $wpmatrix[ $itemid ][ 0 ] ) {
			if( $wpname != "" ) {
				$tmp = 100-(round((100*$wpnewestimate/$wpestimate),1));
				echo "<tr><td></td><td></td><td></td><td></td><td>".$wpestimate."</td><td>".$wpnewestimate."</td><td>".$tmp."%</td></tr>";
				echo "<tr><td><br></td></tr>";
			}
			$wpname = $wpmatrix[ $itemid ][ 0 ];
			echo "<tr><td><b>".$wpname."</b></td>";
			$wpestimate = 0;
			$wpnewestimate = 0;
		} else {
			echo "<tr><td></td>";
		}
		
		echo "<td>".$wpmatrix[ $itemid ][ 6 ]."</td>"; // Sprint
		echo "<td>".$wpmatrix[ $itemid ][ 2 ]."</td>"; // Task
		echo "<td>".$wpmatrix[ $itemid ][ 3 ]."</td>"; // Prio
		echo "<td>".$wpmatrix[ $itemid ][ 4 ]."</td>"; // Orig Estimation
		echo "<td>".$wpmatrix[ $itemid ][ 5 ]."</td>"; // Latest Estimation
		
		$tmp1 = intval( $wpmatrix[ $itemid ][ 4 ] );
		
		$wpestimate = $wpestimate + $tmp1;
		
		$tmp2 = intval( $wpmatrix[ $itemid ][ 5 ] );
		
		$wpnewestimate = $wpnewestimate + $tmp2;
		if( $tmp1 > 0 ) {
			$tmp3 = 100-(round((100*$tmp2/$tmp1),2));
			echo "<td>".($tmp3)."%</td>"; // Progress
		} else {
			echo "<td>N/A</td>"; // Progress
		}
		echo "</tr>";
	}
	if( $wpname != "" ) {
		$tmp = 100-(round((100*$wpnewestimate/$wpestimate),1));
		echo "<tr><td></td><td></td><td></td><td></td><td>".$wpestimate."</td><td>".$wpnewestimate."</td><td>".$tmp."%</td></tr>";
	}
?>
</table>


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


