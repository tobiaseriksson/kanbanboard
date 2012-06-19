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
    <script type="text/javascript" src="/assets/js/timeline.js"></script> 
    <script type="text/javascript">
        
        window.onload = function() { 
        	
        	 
        <?php
			$i=0;
			$headlines="";
			foreach ($timelineitems as $item) {	
				$headlines=$headlines."['".$item['date']."','".$item['headline']."'],";
			}
			$headlines = rtrim( $headlines, ',' );
		?>
			var headlines = [ <?php echo $headlines; ?> ];
        	
       	 	var timeline = new TimeLine('timelinecanvas',1000,100);
			timeline.setHeadlines( headlines );
			timeline.setEditIconURL( '<?php echo site_url( '/kanban/timeline/'.$projectid.'/'.$sprintid ); ?>' );
       	 	timeline.create();
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

			function updateWIPWarningForGroup( groupID, showAlert ) {
				if( showAlert === undefined ) showAler = true;
				var wipLimits = [];
				<?php foreach ($groups as $group) {		
				echo "wipLimits['".$group['id']."'] = ".$group['wip'].";\n";
				}
				?>
				if( wipLimits[''+groupID] <= 0 ) return;
				if( $('#sortable'+groupID).children().size()-1 > wipLimits[''+groupID] ) {
					$('#grouptitle'+groupID+' span:first').show();
					if( showAlert == true ) alert("WIP Limit reached!");
				} else {
					$('#grouptitle'+groupID+' span:first').hide();
				}
				
			}

			function updateTaskCountForGroup( groupID ) {
				var obj = $('#sortable'+groupID);
				var count = $(obj).children().size()-1;
				$('li:first span:last',obj).text("("+count+")");
			}

			function updateTaskCountForGroups() {
				// var groups = $('#kanbanboard ul');
				// $('li:first div:last',groups).text("oops");
				var wipLimits = [];
				<?php foreach ($groups as $group) {		
				echo "wipLimits['".$group['id']."'] = ".$group['wip'].";\n";
				}
				?>
				$('#kanbanboard ul').each( function(index) {
					var count = $(this).children().size()-1;
					$('li:first span:last',this).text("("+count+")");
					groupID = $(this).attr("id").replace( "sortable", "" );
					if( wipLimits[''+groupID] > 0 && count > wipLimits[''+groupID] ) {
						$('li:first span:first',this).show();
					} else {
						$('li:first span:first',this).hide();
					}
				});
			}
			
		</script>
	
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
						updateWIPWarningForGroup( from, false );
						updateWIPWarningForGroup( to, true );
						updateTaskCountForGroup( from );
						updateTaskCountForGroup( to );
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
			$( "#dialog-task-comments" ).dialog({
			width: 600,
			resizable: true,
			modal: true,
			autoOpen: false,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		   });
		});

		$('#taskcommentform').submit(function() {
			var dataString = $("#taskcommentform").serialize();
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/taskcommentsformandlegend",  
				  data: dataString,  
				  success: function(data) {  				     
						loadTaskComments( $("#taskcommentform input[name=taskid]").val() );
						$("#taskcommentform").reset();
				  },
				  error: function(x,e) {  
					    $("#errordiv").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
					  }
				});
			  return false;
			});		
	
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
	
	function loadTaskComments( taskid ) {
		var projectid = <?php echo $projectid; ?>;
		$("#dialog-task-comments").load("/kanban/taskcommentsformandlegend/"+projectid+"/"+taskid, function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "Sorry but there was an error: ";
			    $("#errordiv").html(msg + xhr.status + " " + xhr.statusText);
			  }
		});
	}
	
	
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
				// The intention with this is to make all the groups equally tall, so that it should be easy to move elements/tasks between groups
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
				updateTaskCountForGroups();
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
	echo '<li id="grouptitle'.$group['id'].'" class="ui-state-default rubrik"><span title="WIP Limit Reached" class="ui-icon ui-icon-alert wip-warning-icon"></span><span class="grouptitle">'.$group['name'].'</span><span>()</span></li>';
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
<div class="inside-postit-footer-commenticon" title="Comments">
<span class="ui-icon ui-icon-comment" onclick="loadTaskComments('.$row['taskid'].'); $(\'#dialog-task-comments\').dialog(\'open\'); return false; "></span>'.$row['comments'].'
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



<div id="dialog-task-comments" title="Comments">	
</div>

</body>
</html>

