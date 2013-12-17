<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<! base href="http://kanban.tsoft.se/" />
	<base href="<?php echo site_url( '/' ); ?>" />
	<title>The '<?php echo $projectname; ?>' Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
	<link type="text/css" href="/assets/ticker/styles/ticker-style.css" rel="stylesheet" />
	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

	<script type="text/javascript">
    var djConfig = {
        parseOnLoad: false,
        isDebug: false,
        modulePaths: {
            "dojo": "https://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dojo",
            "dijit": "https://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dijit",
            "dojox": "https://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dojox" 
        }
    };
	</script>

	<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.8.23.custom.min.js"></script>
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
		
		.historymatrix {
			overflow:scroll;
    		overflow-y:hidden;
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
					if( $row[1] < 0 ) break;
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
				
				$tmpstr = "var progress  = [ ";
				foreach ($diagramactual as $row)
				{			
					$tmpstr = $tmpstr."{ x: ".$row[0].",y: ".$row[1]." },";
					if( $row[1] < 0 ) break;
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
				
				$tmpstr = "var projected  = [ ";
				foreach ($diagramprojected as $row)
				{			
					$tmpstr = $tmpstr."{ x: ".$row[0].",y: ".$row[1]." },";
					if( $row[1] < 0 ) break;
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
				
				$tmpstr = "var projected2  = [ ";
				foreach ($diagramprojected2 as $row)
				{			
					$tmpstr = $tmpstr."{ x: ".$row[0].",y: ".$row[1]." },";
					if( $row[1] < 0 ) break;
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
				
				$tmpstr = "var plan  = [ ";
				foreach ($diagrameffort as $row)
				{			
					$tmpstr = $tmpstr."{ x: ".$row[0].",y: ".$row[1]." },";
					if( $row[1] < 0 ) break;
				}
				$tmpstr = trim( $tmpstr, "," );
				$tmpstr = $tmpstr." ];\n";
				echo $tmpstr;
			?>
			
			var months = [ 'Jan', 'Feb', 'Mar', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec' ];
			var startDateString = "<?php echo $startdate; ?>";
			var oneDayInMilliSeconds = 3600 * 24 * 1000;

			//
			// Plot 7-Day Week Burndown Chart
			//
			dojo.ready(function() {
				
				// Create the chart within it's "holding" node
				var chart = new dojox.charting.Chart2D("burndownchart",{ title: "7 Day Week" } );

				// Set the theme
				chart.setTheme(dojox.charting.themes.MiamiNice);

				// Add the only/default plot 
				chart.addPlot("default", {
					type: "Lines",
					markers: false,
					animate:{duration: 1000} 
				});

				var startDate = parseDate( startDateString );
				var startDateAsMilliSecondsSinceEPOC = startDate.getTime();
				var oneDayInMilliSeconds = 3600 * 24 * 1000;
				var firstMonthDisplayed = 0;

				var projectStartDayOfWeek = startDate.getDay(); // Sunday = 0, Monday = 1
				if( projectStartDayOfWeek <= 0 ) projectStartDayOfWeek = 7; // Translate Sunday to 7 thus, Monday = 1, Tuesday = 2, ... Sunday = 7

				// Add axes
				var myLabelFunc = function(text, value, precision){
					var dateInMilliSecondsSinceEPOC = startDateAsMilliSecondsSinceEPOC + ( value * 	oneDayInMilliSeconds );
					var theDate = new Date( dateInMilliSecondsSinceEPOC );
					var dayOfMonth = theDate.getDate();
					//if( firstMonthDisplayed <= 0 || dayOfMonth % 10 == 0 || dayOfMonth == 1 ) {
					var month = months[ theDate.getMonth() ];
					//	firstMonthDisplayed = 1;
					return dayOfMonth + ' ' + month;
					//} 
					//return dayOfMonth;
				};
				chart.addAxis("x",{  min: 0, fixUpper: "major", labelFunc: myLabelFunc   });
				chart.addAxis("y", {  min: 0, vertical: true, fixLower: "major", fixUpper: "major"  });

				// Add the series of data
				chart.addSeries("Baseline",baseline, {plot: "Lines", stroke: {color:"green"} });
				chart.addSeries("Progress",progress, {plot: "Lines", stroke: {color:"blue", style: "Solid"} });
				chart.addSeries("<?php echo round( $initialteamefficiency, 0); ?>%-efficiency",projected, {plot: "Lines", stroke: {color:"#2E64FE", style: "Dash"} });
				chart.addSeries("<?php echo round( $teamefficiency, 0); ?>%-efficiency",projected2, {plot: "Lines", stroke: {color:"#EE0012", style: "Dash"} });
				chart.addSeries("Plan",plan, {plot: "Lines", stroke: {color:"red"} });
				
				// Render the chart!
				chart.render();
				// Add Legend to the bottom
				var outflowinflowlegend = new dojox.charting.widget.Legend({chart: chart}, "burndownchartlegend");
				
			});


			
			/*
			* parses a date yyyy-mm-dd  e.g. 2012-09-21 as in 21;st of September 2012
			*
			*/
			function parseDate(input) {
				  var parts = input.match(/(\d+)/g);
				  // new Date(year, month [, date [, hours[, minutes[, seconds[, ms]]]]])
				  return new Date(parts[0], parts[1]-1, parts[2]); // months are 0-based
			}

			/*
			* returns the Day Of Week as 1 = Monday and 7 Sunday
			*/
			function dayOfWeek(anyDate) {
				var dayOfWeek = anyDate.getDay(); // Sunday = 0, Monday = 1
				if( dayOfWeek <= 0 ) dayOfWeek = 7; // Translate Sunday to 7 thus, Monday = 1, Tuesday = 2, ... Sunday = 7
				return dayOfWeek;
			}

			/*
			* truncate an integer
			*/
			function truncateInt( _value ) {
				if (_value<0) return Math.ceil(_value);
  				else return Math.floor(_value);
			}

			function printArray( arr, name ) {
				$("#errordiv").append("xxxxxxxxxxxx-START-"+name+"-xxxxxxxxxxxx<br>");
				var index=0;
				while( index < arr.length ) {
					$("#errordiv").append(index.toString()+"="+arr[index].x+","+arr[index].y+"<br>");
					index++;
				}
				$("#errordiv").append("xxxxxxxxxxxx-END-xxxxxxxxxxxx");
			}

			/*
			* This will create a matrix (array of arrays with a week separator for every Monday)
			* weekSize is either 5 or 7
			*/
			function createWeekLines(startDow,weekSize,baseline) {
				var result = new Array();
				var maxDays = baseline[1].x;
				var maxY = baseline[0].y;
				var dow = startDow;
				console.log("dow"+dow);
				var day = 0;
				if( dow > 1) day = day + ((weekSize+1)-dow);
				while(day<maxDays) {
					result.push( { weekName: 'day '+day, values: new Array({x:day,y:0},{x:day,y:maxY}) } ); 
					console.log("day "+day);
					day=day+weekSize;
				}
				return result;
			}


			/*
			*
			* Draw the 5-day Week Burndown
			*
			*/
			dojo.ready(function() {
				
				// Create the chart within it's "holding" node
				var chart = new dojox.charting.Chart2D("burndownchart5day",{ title: "5 Day Week" } );

				// Set the theme
				chart.setTheme(dojox.charting.themes.MiamiNice);

				// Add the only/default plot 
				chart.addPlot("default", {
					type: "Lines",
					markers: false,
					animate:{duration: 1000} 
				});

				var startDate = parseDate( startDateString );
				var startDateAsMilliSecondsSinceEPOC = startDate.getTime();
				var firstMonthDisplayed = 0;
				var projectStartDayOfWeek = dayOfWeek(startDate);

				/*
				* returns the week offset based on the day-of-week for a 5-day-week
				* e.g. if the startDayOfWeek is Friday (5), and the dayOffset is 3 i.e. Monday
				* then this function will return 1 as this is the number of weeks since start 
				* i.e. the next coming week...
				*/
				function fiveDayWeekOffset( startDayOfWeek, dayOffset ) {
					var x = startDayOfWeek - 1;
					var weekOffset = truncateInt((dayOffset + x) / 5);
					return weekOffset;
				}

				/*
				* returns the week offset based on the day-of-week for a 7-day-week
				* e.g. if the startDayOfWeek is Friday (5), and the dayOffset is 3 i.e. Monday
				* then this function will return 1 as this is the number of weeks since start 
				* i.e. the next coming week...
				*/
				function sevenDayWeekOffset( startDayOfWeek, dayOffset ) {
					var x = startDayOfWeek - 1;
					var weekOffset = truncateInt((dayOffset + x) / 7);
					return weekOffset;
				}				

				/*
				*
				* the array is indexed like 0,1,2,3,4... this will return the day offset when the array is based on 5 day week into an offset for a 7 day week
				* e.g. 
				* index 0 = Friday ===> offset 0
				* thus 
				* index 1 = Monday ===> offset 3
				* index 2 = Tuesday ====> offset 4
				*/
				function convert5DayWeekArrayIndexTo7DayWeekOffset( startDayOfWeek, index ) {
					var weekOffset = weekOffset( startDayOfWeek, index );
					var dayOffset = index + (weekOffset * 2);
					return dayOffset;
				}
				
				/*
				*
				* This will create an array (which is based on 5 days) based on the input array (which is 7 days)
				* Removing Saturdays and Sundays, results in that an array that looks like this
				* when x = 0 Means Monday
				* 0 => x = 0 , y = 100
				* 1 => x = 1 , y = 90
				* 2 => x = 2 , y = 80
				* 3 => x = 3 , y = 70
				* 4 => x = 4 , y = 60
				* 5 => x = 5 , y = 50
				* 6 => x = 6 , y = 40 (Saturday)
				* 7 => x = 7 , y = 30 (Sunday)
				* 8 => x = 8 , y = 20
				* 9 => x = 9 , y = 10
				* 10 => x = 10 , y = 0
				*
				* will look like this
				*
				* 0 => x = 0 , y = 100
				* 1 => x = 1 , y = 90
				* 2 => x = 2 , y = 80
				* 3 => x = 3 , y = 70
				* 4 => x = 4 , y = 60
				* 5 => x = 5 , y = 50
				* 6 => x = 6 <==, y = 20 <===
				* 7 => x = 7 <==, y = 10 <===
				* 8 => x = 8 <==, y = 0 <===
				* 
				* As you can see the day-offset changes as well
				*
				*/
				function removeSaturdaysAndSundaysFrom7DayWeekArray( startDayOfWeek, arr ) {
					// console.log("remove saturdays and Sundays");
					if( arr.length <= 0 ) return arr;
					var resultArray = new Array();
					var index = 0;
					if( arr[index].x <= 0 ) dow = 1;
					var dow = startDayOfWeek + arr[index].x - (truncateInt( arr[index].x / 7 ) * 7);
					var newArrayIndex = -1;
					while( index < arr.length ) {
						dow = startDayOfWeek + arr[index].x - (sevenDayWeekOffset( projectStartDayOfWeek, arr[index].x) * 7);
						if( dow <= 5 ) {
							newArrayIndex = arr[index].x - (sevenDayWeekOffset( projectStartDayOfWeek, arr[index].x) * 2);
							resultArray.push( { x : newArrayIndex, y : arr[index].y } );
							if( arr[index].y < 0 ) break;
						}
						index++;
					}
					return resultArray;
				}

				function convertBaselineTo5DayWeek( startDayOfWeek, arr ) {
					var k = (arr[1].y - arr[0].y) / (arr[1].x - arr[0].x);
					var result = new Array();
					newArrayIndex = arr[0].x - (sevenDayWeekOffset( projectStartDayOfWeek, arr[0].x) * 2);
					result.push( {x: newArrayIndex, y: arr[0].y } );
					newArrayIndex = arr[1].x - (sevenDayWeekOffset( projectStartDayOfWeek, arr[1].x) * 2);
					result.push( {x: newArrayIndex, y: arr[1].y } );
					return result;
				}

				function shiftStartDateInArrayForward( arr, numberOfDaysToShiftForward ) {
					var result = new Array();
					var index = 0;
					while( index < arr.length ) {
						var tmpX = arr[index].x - numberOfDaysToShiftForward;
						if( tmpX >= 0 ) {
							result.push( {x:tmpX,y:arr[index].y} );
						}
						index++;
					}
					return result;
				}

				if( projectStartDayOfWeek > 5 ) {
					// If the first day of the project is either a Saturday or a Sunday then we 
					// need to transform all the arrays so that they start on the next coming Monday
					numberOfDaysToShiftForward = 8-projectStartDayOfWeek;
					startDateAsMilliSecondsSinceEPOC = startDateAsMilliSecondsSinceEPOC + (numberOfDaysToShiftForward*oneDayInMilliSeconds); // start-date shifted forward 1 or 2 days
					startDate = new Date( startDateAsMilliSecondsSinceEPOC );
					projectStartDayOfWeek = dayOfWeek(startDate);

					progress = shiftStartDateInArrayForward( progress, numberOfDaysToShiftForward );
					projected = shiftStartDateInArrayForward( projected, numberOfDaysToShiftForward);
					projected2 = shiftStartDateInArrayForward( projected2, numberOfDaysToShiftForward );
					plan = shiftStartDateInArrayForward( plan, numberOfDaysToShiftForward );
					baseline[1].x = baseline[1].x - numberOfDaysToShiftForward;
				}

				var progress5DayArray = removeSaturdaysAndSundaysFrom7DayWeekArray( projectStartDayOfWeek, progress );
				var projected5DayArray = removeSaturdaysAndSundaysFrom7DayWeekArray( projectStartDayOfWeek, projected );
				var projected5DayArray2 = removeSaturdaysAndSundaysFrom7DayWeekArray( projectStartDayOfWeek, projected2 );
				var plan5DayArray = removeSaturdaysAndSundaysFrom7DayWeekArray( projectStartDayOfWeek, plan );
				var baseline5Day = convertBaselineTo5DayWeek( projectStartDayOfWeek, baseline );

				function convert5DayTo7DayWeek( arr ) {
					var i = 0;
					var result = new Array();
					while( i < arr.length ) {
						var tmp = arr[i].x + fiveDayWeekOffset( projectStartDayOfWeek, arr[i].x ) * 2;
						result.push( { x: tmp, y:arr[i].y } );
						i++;
					}
					return result;
				}

				var tmpArray = convert5DayTo7DayWeek( projected5DayArray2 );

				// printArray( projected2, "projected2" );
				// printArray( projected5DayArray2, "projected5Day2" );
				// printArray( tmpArray, "converted back to 7 day week...")

				// Add axes
				var lastMonth=-1;
				var myLabelFuncFor5DayWeekArray = function(text, value, precision){
					var x = value + fiveDayWeekOffset( projectStartDayOfWeek, value ) * 2;
					var dateInMilliSecondsSinceEPOC = startDateAsMilliSecondsSinceEPOC + ( x * 	oneDayInMilliSeconds );
					var theDate = new Date( dateInMilliSecondsSinceEPOC );
					var dayOfMonth = theDate.getDate();
					var month = months[ theDate.getMonth() ];
					// if( lastMonth < 0 || month != lastMonth ) {
					//	lastMonth = month;
					return dayOfMonth + ' ' + month;
					// } 
					// return dayOfMonth;
				};

				chart.addAxis("x",{  min: 0, fixUpper: "major", labelFunc: myLabelFuncFor5DayWeekArray   });
				chart.addAxis("y", {  min: 0, vertical: true, fixLower: "major", fixUpper: "major"  });

				// Add the series of data
				chart.addSeries("Baseline",baseline5Day, {plot: "Lines", stroke: {color:"green"} });
				chart.addSeries("Progress",progress5DayArray, {plot: "Lines", stroke: {color:"blue", style: "Solid"} });
				chart.addSeries("<?php echo round( $initialteamefficiency, 0); ?>%-efficiency",projected5DayArray, {plot: "Lines", stroke: {color:"#2E64FE", style: "Dash"} });
				chart.addSeries("<?php echo round( $teamefficiency, 0); ?>%-efficiency",projected5DayArray2, {plot: "Lines", stroke: {color:"#EE0012", style: "Dash"} });
				chart.addSeries("Plan",plan5DayArray, {plot: "Lines", stroke: {color:"red"} });

				weekSize=5;
				weekSeparators = createWeekLines(projectStartDayOfWeek,weekSize,baseline5Day);
				for( var i = 0; i < weekSeparators.length; i++ ){
					// console.log("week="+weekSeparators[i].weekName+",x1="+weekSeparators[i].values[0].x);
					chart.addSeries(weekSeparators[i].weekName, weekSeparators[i].values, {plot: "Lines", stroke: {color:"#eeeeee"} });					
				}

				// Render the chart!
				chart.render();
				// Add Legend to the bottom
				// var outflowinflowlegend = new dojox.charting.widget.Legend({chart: chart}, "burndownchartlegend");
				
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
				chart.addAxis("x",{  minorTicks: false, min: 0 <?php echo $weeknumbers; ?>  });
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

<script type="text/javascript">
	$(function() {
		$( "#burndown-tabs" ).tabs({
			event: "mouseover"
		});
	});
</script>

</head>
<body bgcolor=white>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanban/project/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
		<li><a href="<?php echo site_url( '/kanban/wpstatus/'.$projectid.'/'.$sprintid ); ?>">Project-Status</a></li>	
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

<h2>Sprint Status</h2>
<center>
<span>
<h3>Currently <?php echo $daysleft; ?> days left<br><br>

Sprint duration is <?php echo $totaldays; ?> days<br><br>

Sprint Estimation is <?php echo $totalestimation; ?> points<br><br>

Current velocity is <?php echo round( $velocity, 1); ?> points / day<br><br> 

Average Team Efficiency is <?php echo round( $teamefficiency, 1); ?> %<br>(based on progress / available resources)<br> 
</h3>
</span> 
<br><br>
</center>
<br>
<h2>Burndown Chart</h2>
<div id="burndown-tabs">
	<ul>
		<li><a href="#fiveday-tab">5 Day Burndown</a></li>
		<li><a href="#sevenday-tab">7 Day Burndown</a></li>
	</ul>
	<div id="fiveday-tab">
		<div id="burndownchart5day" class="diagram">
		</div>
	</div>	
	<div id="sevenday-tab">
		<div id="burndownchart" class="diagram">
		</div>
	</div>
</div>
<div id="burndownchartlegend"></div>

<br>
<h2>Progress History Matrix</h2>
<?php 
		echo '<div id="historymatix" class="historymatrix">';
		echo '<table border=1px >';
		
		 $endtime = strtotime($enddate);
		 $starttime = strtotime($startdate);
		 $totaldays = floor( ($endtime - $starttime) / 86400 ); 
		 
		 $monthhtml = '<tr><th></th><th>Month</th>';
		 $weekhtml = '<tr><th></th><th>Week</th>';
		 $dayhtml = '<tr><th>Heading</th><th>Date<br>Orig Est.</th>';
		 $monthsteparraystring = '';
		 $weeklysteparraystring = '';
		 $colspanmonth = 1;
		 $colspanweek = 1;
		 $t = $starttime;
		 $currentMonth = date( "m", $t);
		 $currentWeek = date( "W", $t);
		 while( $t < $endtime ) {
		 	$month = date( "m", $t);
		 	$week = date( "W", $t);
		 	$date = date("d", $t);
		 	$dayhtml = $dayhtml.'<th>'.$date.'</th>';
			if( $month != $currentMonth ) {
				$monthhtml = $monthhtml . '<th colspan='.($colspanmonth-1).' >'.date("M",($t-86400)).'</th>';
				$currentMonth = $month;
				$monthsteparraystring = $monthsteparraystring.($colspanmonth-1).',';
				$colspanmonth = 1;
			}
		 	if( $week != $currentWeek ) {
				$weekhtml = $weekhtml . '<th colspan='.($colspanweek-1).' >'.date("W",($t-86400)).'</th>';
				$currentWeek = $week;
				$weeklysteparraystring = $weeklysteparraystring.($colspanweek-1).',';
				$colspanweek = 1;
			}
		 	$colspanmonth = $colspanmonth + 1;
		 	$colspanweek = $colspanweek + 1;
		 	$t = strtotime( "+1 day", $t );
		 }
		 
		 $monthsteparraystring = $monthsteparraystring.$colspanmonth.',';
		 $weeklysteparraystring = $weeklysteparraystring.$colspanweek.',';
		 $dayhtml = $dayhtml.'<th>'.date("d", $t).'</th>';
	  	 $weekhtml = $weekhtml . '<th colspan='.($colspanweek).' >'.date("W",$t).'</th>';
		 $monthhtml = $monthhtml . '<th colspan='.($colspanmonth).' >'.date("M",$t).'</th>';
		 echo $monthhtml.'</tr>';
		 echo $weekhtml.'</tr>';
		 echo $dayhtml.'</tr>';		
		##$emptycells="";
		##for( $i = 0; $i<=$totaldays; $i++) $emptycells=$emptycells."<td></td>";
		##echo "<tr><th>Heading</th><th>Orig Est.</th>".$emptycells."</tr>";
		
		foreach( $progressmatrix as $id => $arr ) {
			echo "<tr><td nowrap title=\"".substr($tasklookup[ $id ][2],0,30)."\">".$tasklookup[ $id ][0]."</td><td>".$tasklookup[ $id ][1]."</td>";
			$previousvalue=$tasklookup[ $id ][1];
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo ",(".$day.")";
				$style = '';
				if( $value > $previousvalue ) $style = 'style="background-color:red; color:white;"';
				if( $value < $previousvalue ) $style = 'style="background-color:green; color:white;"';
				echo "<td ".$style.">".$value."</td>";
				$previousvalue=$value;
			}
			echo "</tr>";
		}
		echo "<tr><td>Total : </td><td>".$totalestimation."</td>";
		$previousvalue=$totalestimation;
		for( $day = 0; $day < count($progressmatrixtotal); $day++ ) {
			$value = intval( $progressmatrixtotal[ $day ] );
			// echo ",(".$day.")";
			$style = '';
			if( $value > $previousvalue ) $style = 'style="background-color:red; color:white;"';
			if( $value < $previousvalue ) $style = 'style="background-color:green; color:white;"';
			echo "<td ".$style.">".$value."</td>";
			$previousvalue=$value;
		}
		echo "</tr>";
		echo "</table>";
		echo "</div>";
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


