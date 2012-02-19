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

		</script>

		
</head>
<body bgcolor=white>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanban/project/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
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
			<h3>Workpackages</h3>
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
		
		$tmp3 = 100-(round((100*$tmp2/$tmp1),2));
		echo "<td>".($tmp3)."%</td>"; // Progress
		
		echo "</tr>";
	}
	if( $wpname != "" ) {
		$tmp = 100-(round((100*$wpnewestimate/$wpestimate),1));
		echo "<tr><td></td><td></td><td></td><td></td><td>".$wpestimate."</td><td>".$wpnewestimate."</td><td>".$tmp."%</td></tr>";
	}
?>
</table>


		</div>
	</div>
	
</body>
</html>


