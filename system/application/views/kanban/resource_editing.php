<!DOCTYPE html>
<html lang="en">
<head>
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<meta charset="UTF-8" />
	<title>The '<?php echo $projectname; ?>' Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
	<link type="text/css" href="/assets/ticker/styles/ticker-style.css" rel="stylesheet" />
	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

	<script type="text/javascript" src="/assets/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.ui.touch-punch.js"></script>
	
	<link type="text/css" href="assets/css/kanban.css" rel="stylesheet" />	

	<style type="text/css">
		#wrapper {
			margin-right: 20px;
			margin-left: 20px;
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
		
		
		table.resourcetablestyle
		{
		    border-width: 0 0 1px 1px;
		    border-spacing: 0;
		    border-collapse: collapse;
		    border-style: solid;
		}
		
		.resourcetablestyle td, .resourcetablestyle th
		{
		    margin: 0;
		    padding: 4px;
		    border-width: 1px 1px 0 0;
		    border-style: solid;
		}
		
		.resourcetablestyle th 
		{
			background-color:#eeeeee;
		}

		.ui-menu { width: 150px; }
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
<body>
	<div id="dock">
		<ul>
			<li><a href="<?php echo site_url( '/kanban/project/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
			<li><a href="<?php echo site_url( '/kanban/status/'.$projectid.'/'.$sprintid ); ?>">Status</a></li>
			<li><a href="<?php echo site_url( '/kanban/settings/'.$projectid.'/'.$sprintid ); ?>">Settings</a></li>	
			<li><a href="<?php echo site_url( '/kanban/tickers/'.$projectid.'/'.$sprintid ); ?>">Tickers</a></li>	
			<li><a href="<?php echo site_url( '/kanban/selectproject'); ?>">Projects</a></li>
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
				<h2>Resource Editing</h2>
				<table border=0>
					<tr><td>Populate with points/day :</td><td><input id="hourperday" type="text" value="8" size="2" /></td><td width=20px></td><td rowspan=5><img src="/assets/images/edit-resource-cell.png"><br><i>Edit individual cells by clicking on them.</i></td><td width=20px></td><td rowspan=5><img src="/assets/images/edit-resource-popup-menu.png"><br><i>Rename or Delete Resources by clicking on the name.</i></td></tr>
					<tr><td>Day Of Week :</td><td>
					M<input type="checkbox" name="dow" value="1" />
					T<input type="checkbox" name="dow" value="2" />
					W<input type="checkbox" name="dow" value="3" />
					T<input type="checkbox" name="dow" value="4" />
					F<input type="checkbox" name="dow" value="5" />
					S<input type="checkbox" name="dow" value="6" />
					S<input type="checkbox" name="dow" value="7" />
					</td></tr>
					<tr><td>Who :</td><td>
					<select id="updateselection">
						<option value="0">All</option>
					</select>
					</td></tr>
					<tr><td>Start Date :</td><td>
					<input id="startdate"  name="startdate" value="<?=$startdate?>" size=12 />
					</td></tr>
					<tr><td>End Date :</td><td>
					<input id="enddate"  name="enddate" value="<?=$enddate?>" size=12 />
					</td></tr>
					<tr><td></td><td>
					<button id="updatedays">Update</button>
					</td></tr>
				</table>
				<br>
				<br/>
				<table id="ttab" class="resourcetablestyle" border="1px">
				<?php 
				 $endtime = strtotime($enddate);
				 $starttime = strtotime($startdate);
				 $totaldays = floor( ($endtime - $starttime) / 86400 ); 
				 
				 $monthsumhtml = '<tr><th>Monthly Sum</th>';
				 $weeksumhtml = '<tr><th>Weekly Sum</th>';
				 
				 $monthhtml = '<tr><th>Month</th>';
				 $dayhtml = '<tr><th>Date</th>';
				 $weekhtml = '<tr><th>Week</th>';
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
						$monthsumhtml = $monthsumhtml . '<th colspan='.($colspanmonth-1).' >?</th>';
						$monthhtml = $monthhtml . '<th colspan='.($colspanmonth-1).' >'.date("M",($t-86400)).'</th>';
						$currentMonth = $month;
						$monthsteparraystring = $monthsteparraystring.($colspanmonth-1).',';
						$colspanmonth = 1;
					}
				 	if( $week != $currentWeek ) {
						$weeksumhtml = $weeksumhtml . '<th colspan='.($colspanweek-1).' >?</th>';
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
			  	 $weeksumhtml = $weeksumhtml . '<th colspan='.($colspanweek).' >?</th>';
				 $monthsumhtml = $monthsumhtml . '<th colspan='.($colspanmonth).' >?</th>';
			  	 $weekhtml = $weekhtml . '<th colspan='.($colspanweek).' >'.date("W",$t).'</th>';
				 $monthhtml = $monthhtml . '<th colspan='.($colspanmonth).' >'.date("M",$t).'</th>';
				 echo $monthhtml.'<th></th></tr>';
				 echo $weekhtml.'<th></th></tr>';
				 echo $dayhtml.'<th>Sum</th></tr>';
				
				$firsttime = 0;
				$id = 0;
				$t = $starttime;
				if( count( $plan ) > 0 ) {
					foreach ($plan as $planitem) {		
					
						if( $id != $planitem['id'] ) {
							if( $firsttime == 0 ) {
								$firsttime = 1;
							} else {
								while( $t <= $endtime ) {
									echo '<td>0</td>';
					 				$t = strtotime( "+1 day", $t );
								}
								echo '<th></th></tr>';
							}
							echo '<tr><th id="'.$planitem['id'].'">'.$planitem['name'].'</th>';
							$t = $starttime;
							$id = $planitem['id'];
						}	
						$date = $planitem['date'];
						$dateTime = strtotime( $date );
						if( $t < $dateTime ) {
							// fast forward until the date, and place ZERO's for the dates we do not have any information about
							do {
								echo '<td>0</td>';
					 			$t = strtotime( "+1 day", $t );
							} while( $t < $dateTime );
							
						} 
						echo '<td>'.$planitem['effort'].'</td>';
						$t = strtotime( "+1 day", $t );
					}	
					while( $t <= $endtime ) {
						echo '<td>0</td>';
		 				$t = strtotime( "+1 day", $t );
					}
					echo '<th></th></tr>';
				} 
				echo $weeksumhtml.'<th></th></tr>';
				echo $monthsumhtml.'<th></th></tr>';
				
			?>
			
				<tr><th nowrap><input id="newname" type="text" value="name" size="4" /><button id="addResource">Add</button></th><th colspan="<?php echo ($totaldays+2); ?>"></th></tr>
				</table>
				<br>
				<button id="submitchanges">Submit Changes</button><img id="progressimage" width="20" height="20" src="assets/images/ajax-loader_transp.gif"></img>
				
				<br/>
				


		<script type="text/javascript"> 	

		var monthStepArray = Array( <?php echo rtrim( $monthsteparraystring, ',' ); ?> );
		var weekStepArray = Array( <?php echo rtrim( $weeklysteparraystring, ',' ); ?> );
		var projectid = <?php echo $projectid; ?>;
		var dateStr = '<?php echo $startdate; ?>';
		var dateArray = dateStr.split('-'); // 2011-12-28
		var startDate = new Date(dateArray[0],dateArray[1]-1,dateArray[2]);
		var dayOfWeekForStartDate = startDate.getDay();
		var numberOfDays = <?php echo $totaldays; ?>;

		//
		// Colorizes the table, such that 0 is RED and >0 is GREEN
		//
		function colorize() {
			var i = 1;
			$('#ttab tr:gt(2)').each(function(){
					$(this).find('td').each(function(){
						var value = parseInt( $(this).html().replace(/[^\d.,]/g, "") );
						if( value == 0 ) {
							$(this).css("background-color", "#ffbbbb");
						} else {
							$(this).css("background-color", "#bbffbb");
						} 
					});
				});
		}
		
		//
		// Goes through all the cells, and summarizes the "hours" by week and month
		//
		function summarize() {
			var i = 1;
			var monthlySumArray = new Array();
			var weeklySumArray = new Array();
			for( i = 0; i < monthStepArray.length; i++ ) monthlySumArray[i] = 0;
			for( i = 0; i < weekStepArray.length; i++ ) weeklySumArray[i] = 0;
			var w = 0;
			var m = 0;
			var monthStep = 0;
			var weekStep = 0;
			var total = 0;
			$('#ttab tr').not(':lt(3)').not(':last').each(function(){
					var sum = 0;
					m = 0;
					w = 0;
					monthStep = monthStepArray[m]-1;
					weekStep = weekStepArray[w]-1;
					i = 16;
					$(this).find('td').each(function(){
						var value = parseInt( $(this).html().replace(/[^\d.]/g, "") );
						sum = sum + value;
						total = total + value;
						monthlySumArray[m] = monthlySumArray[m] + value;
						weeklySumArray[w] = weeklySumArray[w] + value;
						// i++;
						// if( i > 30 ) i = 1;
						// $('body').append( '<br>i = '+i+',m = '+m+',w = '+w+',value = '+value+',msa = '+monthlySumArray[m]+',wsa = '+weeklySumArray[w]+',ms = '+monthStep+',ws = '+weekStep);
						monthStep--;
						weekStep--;
						if( monthStep < 0 ) {
							m++
							monthStep = monthStepArray[m]-1;
						}
						if( weekStep < 0 ) {
							w++;
							weekStep = weekStepArray[w]-1;
						}
							
					});
					$( 'th:last', this ).text( sum );
			});
			var sum = 0;
			$($('#ttab tr:last').prev().prev()).each(function(){
				var w = -1;
				$(this).find('th').not(':last').each(function(){
					if( w >= 0 ) {
						$(this).text( weeklySumArray[w] );
						sum = sum + weeklySumArray[w];
					}
					w++;
				});
			});
			$('th:last', $('#ttab tr:last').prev().prev()).text( sum );
			sum = 0;
			$($('#ttab tr:last').prev()).each(function(){
				var m = -1;
				$(this).find('th').not(':last').each(function(){
					if( m >= 0 ) {
						$(this).text( monthlySumArray[m] );
						sum = sum + monthlySumArray[m];
					}
					m++;
				});
			});
			$('th:last', $('#ttab tr:last').prev()).text( sum );
		}
		
		function inArray( value, arr ) {
			for( var i=0; i < arr.length; i++ ) {
				if( arr[i] == value ) return 0;
			}
			return -1;
		}

		//
		// Tries to parse an integer and if it fails, it will return the default value
		//
		function validateNumber( value, defaultValue ) {
			var tmp = parseInt( value );
			return isNaN(tmp) ? defaultValue : tmp;
		}

		//
		// Make the cells editable, but only those that 
		//
		function makeCellsEditable(onlyLastRow) {
			var selection = "#ttab tr:gt(2) td";
			if( onlyLastRow ) selection = "#ttab tr:nth-last-child(4) td"; // as a matter of fact, the last 3 rows contains summary etc, so 4;th from the last is what is meant
			$(selection).click(function () {
				var originalValue = $(this).text();
				// $(this).addClass("cellEditing");
				$(this).html("<input type='text' size=2 value='" + originalValue + "' />");
				$(this).children().first().focus();
				$(this).children().first().keypress(function (e) {
				if (e.which == 13) {
					var value = $(this).val();
					$(this).parent().text( validateNumber( value, originalValue ) );
					colorize();
					summarize();
					// $(this).parent().removeClass("cellEditing");
				}
				});

				$(this).children().first().blur(function(){
					var value = $(this).val();
					$(this).parent().text( validateNumber( value, originalValue ) );
					colorize();
					summarize();
					// $(this).parent().removeClass("cellEditing");
				});
			});
		}

		//
		// Makes all the resource names clickable, i.e. a menu with Rename and Delete will pop-up
		//
		var selectedResource = null;
		function makeMenuAppearOnClick( onlyLastRow ) {
			var selection = "#ttab tr:gt(2) th[id]";
			if( onlyLastRow == true ) selection = "#ttab tr:nth-last-child(4) th[id]";
			$(selection).click(function (e) {
				$("#menu").show();
		        $("#menu").offset({left:e.pageX,top:e.pageY});
		        selectedResource = $(this);
			});
		}

		$(function () {
			makeCellsEditable(false);
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

			var oneDayInMilliSeconds = 3600 * 24 * 1000;
			var startDateString = "<?php echo $startdate; ?>";
			var startDate = parseDate( startDateString );

			var endDateString = "<?php echo $enddate; ?>";
			var endDate = parseDate( endDateString );

	/* English/UK initialisation for the jQuery UI date picker plugin. */
/* Written by Stuart. */
		jQuery(function($){
			$.datepicker.regional['en-GB'] = {
				closeText: 'Done',
				prevText: 'Prev',
				nextText: 'Next',
				currentText: 'Today',
				monthNames: ['January','February','March','April','May','June',
				'July','August','September','October','November','December'],
				monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
				'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
				dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
				dayNamesMin: ['Su','Mo','Tu','We','Th','Fr','Sa'],
				weekHeader: 'Wk',
				dateFormat: 'yy-mm-dd',
				firstDay: 1,
				isRTL: false,
				showMonthAfterYear: false,
				yearSuffix: ''};
			$.datepicker.setDefaults($.datepicker.regional['en-GB']);
		});

			$(function() {
				$.datepicker.setDefaults($.datepicker.regional['en-GB']);
				$( "#startdate" ).datepicker({ minDate: startDate, maxDate: endDateString });
				$( "#enddate" ).datepicker({ minDate: startDate, maxDate: endDateString });
				$( "#startdate").datepicker('setDate', startDate);
				$( "#enddate").datepicker('setDate', endDateString);
			});


			$(function() {
				var numberOfSprints = $("#deletesprint_sprintid option").size();
				// $("#debugresult").html("sprints = "+numberOfSprints);
				if( numberOfSprints <= 1 ) {
					$('#deletesprint input[type=submit]', this).attr('disabled', 'disabled');
				}
			});
		
			function populateUserSelection() {
				$('#updateselection').html('<option value="0">All</option>');
				var i = 1;
				$('#ttab tr:gt(2)').each(function(){
					if ($('th:first', this).attr('id') > 0) {
						$('#updateselection').append('<option value="' + (i++) + '">' + $('th:first', this).text() + '</select>');
					}
				})
			}

			$(function() {
				$('#progressimage').hide();
								
				populateUserSelection();

				colorize();
				
				summarize();
						
				$("#submitchanges").click( function() {
					$('#progressimage').show();
					var data = '';		
					$('#ttab tr:gt(2)').each(function(){
						var found = 0;
						$(this).find('th[id]:first').each(function(){
					   		data = data + $(this).attr('id') + ',' + $(this).text();
							found = 1;
	   					 });
						 if (found == 1) {
						 	$(this).find('td').each(function(){
						 			var value = parseInt( $(this).html().replace(/[^\d.,]/g, "") );
									if( isNaN( value ) ) value = 0;
									data = data + ',' + value;
							});
							data = data + ';';
						}
					})
					// $('body').append( 'data = '+data );
					var dataStr = 'data='+data+'&startdate=<?php echo $startdate; ?>';
					$.ajax({  
				 		type: "POST",  
				  		url: "/kanban/updateschedule/"+projectid,  
				  		data: dataStr,  
				  		complete: function() {
							$('#progressimage').hide();
						},
				  		success: function(data) {  
							// $('body').append( '<br>result = '+data );
							populateUserSelection();			     
				  		}, 
				  		error: function(x,e) {  
				    		$('body').append( "failed with; "+x.status+", e="+e+", response="+x.responseText );
				  		}  
					});  
				});
				
				$("#addResource").click( function() {
					var newname = $('#newname').val();
					var id = -1;
					var dataStr = 'newname='+ newname ;
					$.ajax({  
						dataType: "json", 
				 		type: "POST",  
				  		url: "/kanban/addresource/"+projectid,  
				  		data: dataStr,  
				  		success: function(data) {  
							// $('body').append( 'new id = '+data);
							var cells = '';
							for( var i=0;i<=numberOfDays;i++) {
								cells = cells + '<td>0</td>';
							}
							$('<tr><th id="'+data.id+'">'+newname+'</th>'+cells+'<th>0</th></tr>').insertBefore( $('#ttab tbody>tr:last').prev().prev() );
							populateUserSelection();
							colorize();
							makeCellsEditable(true);    
							makeMenuAppearOnClick(true); 
				  		}, 
				  		error: function(x,e) {  
				    		$('body').append( "failed with; "+x.status+", e="+e+", response="+x.responseText );
				  		}  
					}); 
					
				});
					
				$("#updatedays").click( function() {
					var dow = [];
					var i = 0;
					$("input[name='dow']").each( function() {
							if ((this).checked) {
								
								dow[ i++ ] = parseInt( $(this).val() );
							}
					});					
					var value = $('#hourperday').val();
					value = parseInt( value );
					if( isNaN( value ) ) value = 0;		

					var periodStartDate = parseDate( $('#startdate').val() );
					if( periodStartDate < startDate ) periodStartDate = startDate;
					if( periodStartDate > endDate ) periodStartDate = endDate;
					if( periodEndDate < startDate ) periodEndDate = startDate;
					if( periodEndDate > endDate ) periodEndDate = endDate;
					
					var periodEndDate = parseDate( $('#enddate').val() );
					var startDay = (periodStartDate.getTime() - startDate.getTime()) / oneDayInMilliSeconds;
					var endDay = (periodEndDate.getTime() - startDate.getTime()) / oneDayInMilliSeconds;
					var selectedUser = $('#updateselection option:selected').attr('value');
					// $('body').append( 'sel = '+selectedUser );
					var i = 1;
					$('#ttab tr:gt(2)').each(function(){
						var x = dayOfWeekForStartDate;
						var day = 0;
						if (selectedUser == 0 || selectedUser == i) {
							$(this).find('td').each(function(){
								if (inArray(x++, dow) == 0 && day >= startDay && day <= endDay ) {
									$(this).html(value);
								}
								if (x > 7) 
									x = 1;
								day++;
							});
						}
						i++;
					});
					colorize();
					summarize();
				});

			});
		</script> 


<script>
//
// This section handles the popup-menu for the resources (names)
// and makes it possible to rename and delete resources 
//
$(function() {

	$("#dialog-confirm-delete-resource").dialog({
		width: 300,
		resizable: false,
		modal: true,
		autoOpen: false,
		buttons: {
			Cancel: function() {
				$( this ).dialog( "close" );
				console.log("Delete canceled");
			},
			Ok: function() {
				$( this ).dialog( "close" );
				console.log("Delete AJAX call ...");
				var newname = $('#newname').val();
				var id = $("#resource-to-delete-id").val();
				var dataStr = 'id='+id;
				$.ajax({  
					dataType: "text", 
			 		type: "POST",  
			  		url: "/kanban/deleteresource/"+projectid,  
			  		data: dataStr,  
			  		success: function(data) {  
						$( "#"+$("#resource-to-delete-id").val() ).parent().remove();
						populateUserSelection();
						console.log("Delete OK");
			  		}, 
			  		error: function(x,e) {  
			    		$('body').append( "Delete of resource failed with; "+x.status+", e="+e+", response="+x.responseText );
			    		console.log("Delete failed...");
			  		}  
				}); 
			}
		}
	});

	$("#dialog-rename-resource").dialog({
		width: 300,
		resizable: false,
		modal: true,
		autoOpen: false,
		buttons: {
			Cancel: function() {
				$( this ).dialog( "close" );
				console.log("Rename canceled");
			},
			Ok: function() {
				$( this ).dialog( "close" );
				console.log("Rename AJAX call ...");
				var newname = $("#resource-new-name").val();
				var id = $("#resource-to-rename-id").val();
				var dataStr = 'id='+id+'&newname='+newname;
				$.ajax({  
					dataType: "text", 
			 		type: "POST",  
			  		url: "/kanban/renameresource/"+projectid,  
			  		data: dataStr,  
			  		success: function(data) {  
						$( "#"+$("#resource-to-rename-id").val() ).text( $("#resource-new-name").val() );
						populateUserSelection();
						console.log("Rename OK");
			  		}, 
			  		error: function(x,e) {  
			    		$('body').append( "Rename of resource failed with; "+x.status+", e="+e+", response="+x.responseText );
						console.log("Rename failed...");
			  		}  
				}); 
			}
		}
	});

	$( "#menu" ).menu( {
		select: function( event, ui ) {
			var menuSelection = $(ui.item).text();
			// console.log("ui:"+menuSelection );
			if( menuSelection == "Rename" ) {
				console.log( "Renaming: "+$(selectedResource).text()+", id = "+$(selectedResource).attr("id") );
				$("#dialog-rename-resource").dialog( "open" );
				$("#resource-to-rename").text( $(selectedResource).text() );
				$("#resource-new-name").val($(selectedResource).text() );
				$("#resource-to-rename-id").val($(selectedResource).attr("id"));
			}
			if( menuSelection == "Delete" ) {
				console.log( "Deleting; "+$(selectedResource).text()+", id = "+$(selectedResource).attr("id") );
				$("#dialog-confirm-delete-resource").dialog( "open" );
				$("#resource-to-delete").text( $(selectedResource).text() );
				$("#resource-to-delete-id").val($(selectedResource).attr("id"));

			}
			selectedResource = null;
			$( "#menu" ).hide();
		}
	});
	$( "#menu" ).hide();
	$( "#menu" ).on( "mouseleave", function() {
		$( "#menu" ).hide();
		selectedResource = null;
	});

	makeMenuAppearOnClick( false );

});
</script>

		</div>
	</div>



<ul id="menu">
<li><a href="#">Rename</a></li>
<li><a href="#">Delete</a></li>
</ul>


<div id="dialog-confirm-delete-resource" title="Delete Resource?">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This will delete the resource "<span id="resource-to-delete"></span>". Are you sure?</p><input type=hidden id="resource-to-delete-id" value=0 />
</div>

<div id="dialog-rename-resource" title="Rename Resource?">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Please specify the new name for "<span id="resource-to-rename"></span>". <input type=text id="resource-new-name" value="" size="10"/></p><input type=hidden id="resource-to-rename-id" value=0 />
</div>



</body>
</html>


