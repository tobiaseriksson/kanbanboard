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
	<link type="text/css" href="/assets/css/postits.css" rel="stylesheet" />	

	<script type="text/javascript" src="/assets/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.ui.touch-punch.js"></script>

    <script type="text/javascript" src="/assets/js/raphael-min.js"></script>  
    <script type="text/javascript">
        window.onload = function() { 
        	var width = 1000;
        	var height = 100;
        	var pixelsPerDay = 15;
        	var pixelsHeightDay = 3;
        	var pixelsHeightWeek = 6;
        	var pixelsHeightMonth = 9;
        	var yAxesPosition = height / 2;
        	var xStep = width / 2; // pixels left/right when clicking 
        	
		    var paper = new Raphael(document.getElementById('timelinecanvas'), width, height);  
			
			var notes = [];
			// notes[0] = '2011-10-01:Fika paus!';
<?php
			$i=0;
			foreach ($timelineitems as $item) {	
				echo "notes[".$i++."] = '".$item['date'].":".$item['headline']."';\n";
			}
?>

			var sortedNotes = [];
			for( var i = 0; i < notes.length; i++ ) {
				// $('body').append('<br>n['+i+'] = '+notes[i] );
				sortedNotes[i] = notes[i].split( ':' );
			}
			// add TODAY
			var today = new Date();
			sortedNotes[ sortedNotes.length ] = new Array( today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate(), 'Today' );

			sortedNotes.sort( function(a,b) { 
   				if( a[0] == b[0] ) return 0; 
   				if( a[0] < b[0] ) return 1; 
   				return -1; 
   			}	
   			);

			//for( var i = 0; i < sortedNotes.length; i++ ) {
			//	$('body').append('<br>sortedNotes['+i+'] = '+sortedNotes[i] );
			//}
			
			var currentXPosition = 0;
			var monthNames = [ 'Jan', 'Feb', 'Mar' ,'Apr', 'May' , 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
						
			// find limits start and end
			var startDate = Number.MAX_VALUE;
			var endDate = Number.MIN_VALUE;
			
			for( var i = 0; i < sortedNotes.length; i++ ) {
				var d = sortedNotes[i][0].split('-');
				var tmpDate = new Date(0);
				tmpDate.setFullYear( d[0] );
				tmpDate.setMonth( d[1]-1 );
				tmpDate.setDate( d[2] );
				var t  = tmpDate.getTime();
				if( t > endDate ) endDate = t;
				if( t < startDate ) startDate = t;
			}
			
			// allways start on a Monday
			var tmpDate = new Date( startDate );
			if( tmpDate.getDay() == 0 ) {
				startDate = startDate - (1000*3600*24*6); // minus 6 days
			} else {
				startDate = startDate - (1000*3600*24*(tmpDate.getDay()-1)); // minus n days
			}
			endDate = endDate + (1000*3600*24*7); // plus 7 days
				
			var totalDays = (endDate - startDate) / (1000*3600*24);
			
			// Draw timeline
			var timeline = paper.set();
			var x1 = 0;
			var xMax = totalDays * pixelsPerDay;
			var x = x1;
			var currentDay = startDate;
			var path = "M"+x1+" "+yAxesPosition+"L"+xMax+" "+yAxesPosition;
			while( x <= xMax ) {    
		    	// always set a marker per day
		    	path = path.concat( "M", x, " ", yAxesPosition-pixelsHeightDay, "L", x, " ", yAxesPosition+pixelsHeightDay );
		    	var tmpDate = new Date( currentDay );
		    	// set a DATE number every Monday
		    	if( tmpDate.getDay() == 1 ) {
		    		path = path.concat( "M", x, " ", yAxesPosition-pixelsHeightWeek, "L", x, " ", yAxesPosition+pixelsHeightWeek );
		    		var dayString = ''+tmpDate.getDate();
		    		timeline.push( paper.text( x, yAxesPosition+pixelsHeightWeek*2, dayString ) );
		    	}
		    	// set a MONTH name every 1;st in the month
		    	if( tmpDate.getDate() == 1 ) {
		    		path = path.concat( "M", x, " ", yAxesPosition-pixelsHeightMonth, "L", x, " ", yAxesPosition+pixelsHeightMonth );
		    		var monthString = ''+monthNames[ tmpDate.getMonth() ];
		    		timeline.push( paper.text( x, yAxesPosition-pixelsHeightMonth*2, monthString ) );
		    		if( tmpDate.getMonth() == 0 ) {
		    			var yearString = ''+tmpDate.getFullYear();
			    		timeline.push( paper.text( x, yAxesPosition-pixelsHeightMonth*3, yearString ) );
			    	}
		    	}
		    	x = x + pixelsPerDay;
		    	currentDay = currentDay + (1000*3600*24);
		    }
		    var timelineLine = paper.path( path );
			timelineLine.attr({stroke: '#f00', 'stroke-width': 1});  
			timeline.push( timelineLine );
			
			// Position the notes at the dates
			var textPositionOffsets = [ 0.5 * height / 2 , -0.5 * height / 2, 0.75 * height / 2, -0.75 * height / 2 ];
			var textOffsetIndex = 0;
			path="";
			var over = true;
			for( var i = 0; i < sortedNotes.length; i++ ) {
				var d = sortedNotes[i][0].split('-');
				var tmpDate = new Date(0);
				tmpDate.setFullYear( d[0] );
				tmpDate.setMonth( d[1]-1 );
				tmpDate.setDate( d[2] );
				var t  = tmpDate.getTime();
				var days = (t - startDate) / (1000*3600*24);
				x = days * pixelsPerDay;
				var noteString = sortedNotes[i][1];
				if( textOffsetIndex >= textPositionOffsets.length ) textOffsetIndex = 0;
				var textOffset = textPositionOffsets[ textOffsetIndex ];
				var yTextPosition = yAxesPosition + textOffset;
				var xTextPosition = x+((textOffset>0)?-textOffset:textOffset);
				textOffsetIndex++;
			    timeline.push( paper.text( xTextPosition, yTextPosition, noteString ) );
			    path = path.concat( "M", x, " ", yAxesPosition, "L", xTextPosition, " ", yTextPosition );
			}
			var notePaths = paper.path( path );
			notePaths.attr({stroke: '#f00', 'stroke-width': 1});  
			timeline.push( notePaths );	
			
			// Left and Right arrows
			var leftArrowPath = "M16,30.534c8.027,0,14.534-6.507,14.534-14.534c0-8.027-6.507-14.534-14.534-14.534C7.973,1.466,1.466,7.973,1.466,16C1.466,24.027,7.973,30.534,16,30.534zM18.335,6.276l3.536,3.538l-6.187,6.187l6.187,6.187l-3.536,3.537l-9.723-9.724L18.335,6.276z";
			var leftArrow = paper.path( leftArrowPath );
		    leftArrow.attr({ fill: "#ccc", stroke: "none" } );
		    var rightArrowPath = "M16,1.466C7.973,1.466,1.466,7.973,1.466,16c0,8.027,6.507,14.534,14.534,14.534c8.027,0,14.534-6.507,14.534-14.534C30.534,7.973,24.027,1.466,16,1.466zM13.665,25.725l-3.536-3.539l6.187-6.187l-6.187-6.187l3.536-3.536l9.724,9.723L13.665,25.725z";
			var rightArrow = paper.path( rightArrowPath );
		    rightArrow.attr({ fill: "#ccc", stroke: "none" } );
		    rightArrow.transform( "t"+(width-32)+",0" );
			
			var editIconPath = "M16,1.466C7.973,1.466,1.466,7.973,1.466,16c0,8.027,6.507,14.534,14.534,14.534c8.027,0,14.534-6.507,14.534-14.534C30.534,7.973,24.027,1.466,16,1.466zM24.386,14.968c-1.451,1.669-3.706,2.221-5.685,1.586l-7.188,8.266c-0.766,0.88-2.099,0.97-2.979,0.205s-0.973-2.099-0.208-2.979l7.198-8.275c-0.893-1.865-0.657-4.164,0.787-5.824c1.367-1.575,3.453-2.151,5.348-1.674l-2.754,3.212l0.901,2.621l2.722,0.529l2.761-3.22C26.037,11.229,25.762,13.387,24.386,14.968z";
			var editIcon = paper.path( editIconPath );
		    editIcon.attr({ fill: "#ccc", stroke: "none" } );
		    editIcon.transform( "t"+(width-32)+","+(height-32) );
			
			// Animate to TODAY
			var initialXMove = pixelsPerDay * ( (today.getTime()-startDate) / (1000*3600*24) );
			currentXPosition = currentXPosition - (initialXMove - width/2);
			if( currentXPosition >=  xStep ) currentXPosition = xStep;
			if( currentXPosition <= (2*xStep)-xMax ) currentXPosition = (2*xStep)-xMax;
			var t = "t"+currentXPosition+",0";
            timeline.animate( { transform: t}, xStep, ">" );
			
			// Left and Right buttons
		    leftArrow.click(function () {
		    	if( currentXPosition >=  xStep ) return;
		    	currentXPosition = currentXPosition + xStep;
		    	var t = "t"+currentXPosition+",0";
            	timeline.animate( { transform: t}, xStep, ">" );
            });
            rightArrow.click(function () {
            	if( currentXPosition <= (2*xStep)-xMax ) return; 
		    	currentXPosition = currentXPosition - xStep;
		    	var t = "t"+currentXPosition+",0";
            	timeline.animate( { transform: t}, xStep, ">" );
            });        
			
            editIcon.click(function () {
            	// http://kanban.softhouse.se/kanban/timeline/28/60
            	window.location = '<?php echo site_url( '/kanban/timeline/'.$projectid.'/'.$sprintid ); ?>';
            	//window.location = 'http://www.yourdomain.com'
            }); 
		}  
        
        </script>  

	<style type="text/css">  
            #timelinecanvas {  
                width: 1000px;  
                height: 100px;
                border: 0px solid #aaa;  
            }  
    </style> 

	<style type="text/css">	
<?php 

$first=1;
foreach ($groups as $row) {	
	if( $first < 1 ) echo ",";
	$first=0;		
	echo "#grouptitle".$row['id']." ";
}
echo " {  height: 15px; }\n";
$first=1;
foreach ($groups as $row) {	
	if( $first < 1 ) echo ",";
	$first=0;		
	echo "#grouptitle".$row['id']." li ";
}
echo " { height: 12px; }\n";
$first=1;
foreach ($groups as $row) {	
	if( $first < 1 ) echo ",";
	$first=0;		
	echo "#sortable".$row['id']." ";
}
echo " { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; }\n";
$first=1;
foreach ($groups as $row) {			
	if( $first < 1 ) echo ",";
	$first=0;		
	echo "#sortable".$row['id']." li ";
}
echo " { margin: 10px 10px 0px 0px; padding: 0px 0px 0px 0px; font-size: 1.1em; width: 120px; height: 120px; }\n";
$first=1;
foreach ($groups as $row) {			
	if( $first < 1 ) echo ",";
	$first=0;		
	echo "#sortable".$row['id']." li:first-child";
}
echo " { height: 20px; }\n";

?>
	</style>
	
		<script type="text/javascript">
			$(function() {
				jQuery.fn.ForceNumericOnly =
					function()
					{
					    return this.each(function()
					    {
					        $(this).keydown(function(e)
					        {
					            var key = e.charCode || e.keyCode || 0;
					            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
					            return (
					                key == 8 || 
					                key == 9 ||
					                key == 46 ||
					                (key >= 37 && key <= 40) ||
					                (key >= 48 && key <= 57) ||
					                (key >= 96 && key <= 105));
					        });
					    });
					};

					$("#priority").ForceNumericOnly();
					$("#estimation").ForceNumericOnly();
					$("#todays_estimation").ForceNumericOnly();

					$("#newtask_priority").ForceNumericOnly();
					$("#newtask_estimation").ForceNumericOnly();
					
			});
			
			</script>
	
	
		<script type="text/javascript">
			$(function() {
				$("<?php
					$first=1; 
					foreach ($groups as $row) {
						if( $first < 1 ) echo ",";
						$first=0;		
						echo "#sortable".$row['id']." ";
						$lastgroupid=$row['id'];
					}
					?>").sortable({
				connectWith: '.connectedSortable',
				items: 'li:not(.rubrik)',
				receive: function(event, ui) { 
					    // $("#errordiv").append( "Event:"+ui.sender.attr("id")+", item="+ui.item.attr("id")+", placeholder="+ui.placeholder.attr("id")+", event.target = "+event.target.id+"<br>" );
						var from = ui.sender.attr("id").replace( "sortable", "" );
						var to = this.id.replace( "sortable", "" );
						var toObj = this;
						var task = ui.item.attr("id").replace( "task", "" );
						var last = <?php echo $lastgroupid; ?>;
						var dataString = 'from='+ from + '&to=' + to + "&task=" + task + "&last=" + last;
						// $("#errordiv").append("res="+dataString);
						if( to == "" ) {
							$("#errordiv").append("Could not move Task, it does not have a target/destination.");
							return;
						}
						if( task == "" ) {
							$("#errordiv").append("Could not move Task, it does not have a valid Task ID.");
							return;
						}
						// alert ("moved");return false;
						$.ajax({  
						  type: "POST",  
						  url: "/kanban/move",  
						  data: dataString,  
						  success: function(data) {  
						    	// $("#errordiv").html("This is the result"+data);
							  	// location.reload();
							    resortGroup( toObj );
						  }, 
					      error: function(x,e) {  
					            $("#errordiv").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
					      }   
						}); 
				}
			}).disableSelection();
		});
	</script>
	
	<script type="text/javascript">
		$(function() {
			$('#newtask').submit(function() {				
			  	return false;
			});	
		});
			
	</script>


	<script type="text/javascript">
			
		function resortGroup( group ) {
			var arrayOfTasks = [];
			var i = 0;
			var html = '';
			// find all Tasks (LI-elements)
			$( 'li', group ).not(':first').each( function() {
					var prio = $('div.inside-postit-footer-prio',this).text(); // .match( /\d+$/ );
					prio = parseInt( prio.replace(/[^\d.,]/g, "") );
					html = $(this);
					arrayOfTasks[ i++ ] = new Array( prio, html );
				} );
			arrayOfTasks.sort( sortOnPrioAlgorithm );
			arrayOfTasks.reverse(); // Highest prio first
			html = $( 'li:first', group ); // Group-Header first
			$(group).html( html );
			// add the Tasks back one by one in the right order
			$.each( arrayOfTasks, function( key, val) { 
				$(group).append( val[1] ); 
				} 
			);
			
		}

		// objX is array( prio, obj ) 
		function sortOnPrioAlgorithm( objA, objB ) {
			if( objA[0] < objB[0] ) return -1;
			if( objA[0] > objB[0] ) return 1;
			return 0;
		} 
			
	</script>


	
	<script type="text/javascript">
		$(function() {
			$( "#dialog-edit-task" ).dialog({
			width: 300,
			resizable: false,
			modal: false,
			autoOpen: false,
			buttons: {
				Del: function() {
					var dataString = $("#updatetask").serialize();
					$.ajax({  
					  type: "POST",  
					  url: "/kanban/deletetask",  
					  data: dataString,  
					  success: function(data) {  				     
					    // location.reload();
						$('#task'+$('#taskid').val()).remove();
					  },
					  error: function(x,e) {  
						    $("#errordiv").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
						  }
					});  
					$( this ).dialog( "close" );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				},
				Ok: function() {
					var dataString = $("#updatetask").serialize();
					$.ajax({  
					  type: "POST",  
					  url: "/kanban/updatetask",  
					  data: dataString,  
					  success: function(data) {  				     
					    location.reload();
					  },
					  error: function(x,e) {  
						    $("#errordiv").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
						  }
					});  
					$( this ).dialog( "close" );
				}
			}
		   });
		});

		$(function() {
			$( "#dialog-new-task" ).dialog({
			modal: false,
			autoOpen: false,
			buttons: {
				Cancel: function() {
					$( this ).dialog( "close" );
				},
				Ok: function() {
					var dataString = $("#newtask").serialize();
					$.ajax({  
					  type: "POST",  
					  url: "/kanban/addtask",  
					  data: dataString,  
					  success: function(data) {  				     
						location.reload();
					  },
					  error: function(x,e) {  
					    $("#errordiv").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
					  }  
					});  
					$( this ).dialog( "close" );
				}
			}
		   });
		});

	</script>

	<script type="text/javascript">
	
	function fillInFormTaskDetails( taskid ) {
		var projectid = <?php echo $projectid; ?>;
		var dataString = "";
		$.ajax({  
		  dataType: "json",  
		  url: "/kanban/taskdetails/"+projectid+"/"+taskid,  
		  data: dataString,  
		  success: function(data) {  
			$("#groupresult").html("success="+data.heading);
			$("#heading").val( data.heading);
			$("#taskdescription").val( data.taskdescription);
			$("#priority").val( data.priority);
			$("#estimation").val( data.estimation);
			$("#todays_estimation").val( data.todays_estimation);
			$("#priority").val( data.priority);
			$("#projectid").val( data.projectid);
			$("#sprintid").val( data.colortag);
			$("#taskid").val( data.taskid);
			$("#colortag").val( data.colortag);			
			$("#sprintid").val( data.sprintid);			
			$("#newsprintid").val( data.sprintid);  	
			$("#workpackage_id").val( data.workpackage_id);  				     
		  }, 
		  error: function(x,e) {  
		    $("#groupresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
		  }  
		});  
	}
	</script>


		
		<script src="/assets/ticker/includes/jquery.ticker.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(function () {
				$('#js-news').ticker({
					controls: false,
					fadeInSpeed: 600,
					titleText: 'Latest !'
				});
			});
		</script>	

		<script type="text/javascript">
			$(function () {
				// $("#errordiv").append("resizing...");
				// The intention with this is to make all the groups equally tall, so that it should be eas to move elements/tasks between groups
				var allgroups = $('#kanbanboard').children('ul');
				var maxheight=0;
				var maxwidth=0;
				allgroups.each(function() {
					var height = $(this).height();
					var width = $(this).width();			
					if( height > maxheight ) {
						maxheight = height;
					}
					if( width > maxwidth ) {
						maxwidth = width;
					}
				});
				maxheight = maxheight + 120;				
				allgroups.each(function() {
					$(this).css( 'height',maxheight );
				});
				// the intention here is to make sure that the groups are not wrapped within the kanbanboard-DIV when the browser window is smaller than the kanbanboard-DIV
				var width = allgroups.length * (maxwidth+10); 
				$('#kanbanboard').width( width );
			});
		</script>	

		<script type="text/javascript">
			$(function () {
				<?php 
				foreach ($groups as $group) {		
					echo "$('#grouptitle".$group['id']."').append(' ('+ ($('#sortable".$group['id']."').children().size()-1)  +')');";
				}
				?>
			});
		</script>	
</head>
<body bgcolor=white>
<div id="dock">
	<ul>
		<li><a href="" onclick="$('#dialog-new-task').dialog('open'); return false; ">New Task</a></li>	
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

<div id="timelinecanvas"></div>  

<div class="kanbanboard" id="kanbanboard">

<?php
$i=1;
$groupid=-1;
foreach ($groups as $group) {		
	echo '<ul id="sortable'.$group['id'].'" class="connectedSortable">';
	echo '<li id="grouptitle'.$group['id'].'" class="ui-state-default rubrik">'.$group['name'].'</li>';
	$groupid = $group['id'];
	foreach ($tasks as $row)
	{					 
		if( $groupid == $row['group_id'] ) {
			echo '
<li id="task'.$row['taskid'].'" class="postit'.$row['colortag'].'">
<div class="inside-postit">
<div class="inside-postit-header-and-text">
<div class="inside-postit-header">'.$row['heading'].'</div>
<div class="inside-postit-text">'.$row['description'].'</div>
</div>
<div class="inside-postit-footer">
<div class="inside-postit-footer-prio" title="Prio">
<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$row['priority'].'
</div>
<div class="inside-postit-footer-estimation" title="Estimation">
<span class="ui-icon ui-icon-clock"></span>'.$row['estimation'].'
</div>
<div class="inside-postit-footer-age" title="Age in days">
<span class="ui-icon ui-icon-calendar"></span>'.$row['age'].'
</div>
<div class="inside-postit-footer-editicon" title="Click to edit">
<span class="ui-icon ui-icon-wrench" onclick="fillInFormTaskDetails('.$row['taskid'].'); $(\'#dialog-edit-task\').dialog(\'open\'); return false; "></span>
</div>
</div>
</li>';
		}

	}
	echo "</ul>\n";
}
?>

</div><!-- End kanbanboard -->


<div id="dialog-edit-task" title="Edit Task">	
	<p>
	<form id="updatetask" name="updatetask" action="">
		<input type="hidden" name="projectid" id="projectid" value="<?php echo $projectid; ?>" />
		<input type="hidden" name="sprintid" value="<?php echo $sprintid; ?>" />		
		<input type="hidden" name="taskid" id="taskid" value="0" />
		<div>
			<table>
				<tr><td>Task :</td><td><input name="heading" id="heading"/></td></tr>
				<tr><td>Text :</td><td><textarea name="taskdescription"  id="taskdescription" rows="3" cols="20"></textarea></td></tr>
				<tr><td> Priority :</td><td>
					<input name="priority" id="priority" value="100" size="3" />
				</td></tr>
				<tr><td> Estimate :</td><td>
					<input name="estimation" id="estimation" value="0"  size="3" />
				</td></tr>
				<tr><td> Remaining Today :</td><td>
					<input name="todays_estimation" id="todays_estimation" value="0"  size="3" />
				</td></tr>
				<tr><td> Color Tag :</td><td>
					<select name="colortag" id="colortag"><option value="1">Yellow</option><option value="2">Green</option><option value="3">Red</option><option value="4">Blue</option><option value="5">Pink</option></select>
					</td></tr>
				<tr><td nowrap> Workpackage :</td><td>
					<select name="workpackage_id" id="workpackage_id">
                           <?php						
							foreach ($workpackages as $wp) {		
							
								echo ' <option value="'.$wp['id'].'">'.$wp['name'].'</option>';
														
							}						
							?>
                        </select>
				</td></tr>
				<tr><td> Move To Sprint :</td><td>
					<select name="newsprintid" id="newsprintid">
					<?php						
					foreach ($sprints as $sprint) {		
						echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
					}						
					?>	
					</select>
				</td></tr>
			</table>
		</div>
	</form>		
	</p>
</div>

<div id="dialog-new-task" title="New Task">	
	<p>
	<div class="kanbanactions">
		<h2>Add New Task</h2>
		<form id="newtask" name="newtask" action="">
		<input type="hidden" name="projectid" value="<?php echo $projectid; ?>" />
		<input type="hidden" name="sprintid" value="<?php echo $sprintid; ?>" />
		<input type="hidden" name="group" value="<?php echo $groups[0]['id']; ?>" />

			<div>
				<table>					
					<tr><td>New Task :</td><td><input name="heading" ></td></tr>
					<tr><td>Text :</td><td><textarea name="taskdescription"  rows="3" cols="25"></textarea></td></tr>
					<tr><td> Priority :</td><td>
						<input name="priority" id="newtask_priority" value="100" size="3" />
					</td></tr>
					<tr><td> Estimate :</td><td>
						<input name="estimation" id="newtask_estimation" value="0"  size="3"  />
					</td></tr>
				    <tr><td> Color Tag :</td><td>
					<select name="colortag" ><option value="1">Yellow</option><option value="2">Green</option><option value="3">Red</option><option value="4">Blue</option><option value="5">Pink</option></select>
					</td></tr>			
					<tr><td nowrap> Workpackage :</td><td>
					<select name="workpackage_id" id="workpackage_id">
                           <?php						
							foreach ($workpackages as $wp) {		
							
								echo ' <option value="'.$wp['id'].'">'.$wp['name'].'</option>';
														
							}						
							?>
                        </select>
					</td></tr>
												
				</table>
			</div>
		</form>

	</div>
</div>


</body>
</html>

