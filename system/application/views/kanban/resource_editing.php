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
	</style>
	
	<script type="text/javascript"> 	
			// $(function() {
			//	document.execCommand("enableObjectResizing", false, false);
			// });
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
				Populate with points/day : <input id="hourperday" type="text" value="8" size="2" />
				M<input type="checkbox" name="dow" value="1" />
				T<input type="checkbox" name="dow" value="2" />
				W<input type="checkbox" name="dow" value="3" />
				T<input type="checkbox" name="dow" value="4" />
				F<input type="checkbox" name="dow" value="5" />
				S<input type="checkbox" name="dow" value="6" />
				S<input type="checkbox" name="dow" value="7" />
				<select id="updateselection">
					<option value="0">All</option>
				</select>
				<button id="updatedays">Update</button>
				<br/>
				<table id="ttab" class="resourcetablestyle" border="1px" contenteditable="true">
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
				<button id="submitchanges">Submit Changes</button><img id="progressimage" width="20" height="20" src="assets/images/ajax-loader_transp.gif"></img>
				<button id="updatecolors">Colorize</button>
				<button id="summarize">Summarize</button>
				
				<br/>
				
				
		<script type="text/javascript"> 	
			$(function() {
				$('#progressimage').hide();
				var monthStepArray = Array( <?php echo rtrim( $monthsteparraystring, ',' ); ?> );
				var weekStepArray = Array( <?php echo rtrim( $weeklysteparraystring, ',' ); ?> );
				var projectid = <?php echo $projectid; ?>;
				var dateStr = '<?php echo $startdate; ?>';
				var dateArray = dateStr.split('-'); // 2011-12-28
				var startDate = new Date(dateArray[0],dateArray[1]-1,dateArray[2]);
				// $('body').append( 'startdate = '+startDate );
				var dayOfWeekForStartDate = startDate.getDay();
				// if( dayOfWeekForStartDate == 0 ) dayOfWeekForStartDate = 7;
				// $('body').append( 'dow = '+dayOfWeekForStartDate );
				
				// var numberOfDays = $('#ttab tbody>tr:eq(2) th').length - 1;
				var numberOfDays = <?php echo $totaldays; ?>;

				// $('body').append( 'number of days = '+numberOfDays );
				
				populateUserSelection();

				colorize();
				
				summarize();
				
				function populateUserSelection() {
					$('#updateselection').html('<option value="0">All</option>');
					var i = 1;
					$('#ttab tr:gt(2)').each(function(){
						if ($('th:first', this).attr('id') > 0) {
							$('#updateselection').append('<option value="' + (i++) + '">' + $('th:first', this).text() + '</select>');
						}
					})
				}
				
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
							$('body').append( '<br>result = '+data );
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
					var selectedUser = $('#updateselection option:selected').attr('value');
					// $('body').append( 'sel = '+selectedUser );
					var i = 1;
					$('#ttab tr:gt(2)').each(function(){
						var x = dayOfWeekForStartDate;
						if (selectedUser == 0 || selectedUser == i) {
							$(this).find('td').each(function(){
								if (inArray(x++, dow) == 0) {
									$(this).html(value);
								}
								if (x > 7) 
									x = 1;
							});
						}
						i++;
					});
					colorize();
					summarize();
				});


				$("#updatecolors").click( function() {
						colorize();
					});

				$("#summarize").click( function() {
					summarize();
				});

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
			});
		</script> 
		
			
		</div>
	</div>

</body>
</html>


