<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>The '<?php echo $projectname; ?>' Kanban Board</title>	
	<link href="/assets/ticker/1.4.2version/MinifiedVersion/css/min-style.css" rel="stylesheet" type="text/css" />
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.8.1.custom.css" rel="stylesheet" />

	<script type="text/javascript" src="/assets/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.8.1.custom.min.js"></script>
	<script src="/assets/js/raphael-min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="/assets/js/g.raphael.js" type="text/javascript" charset="utf-8"></script>
	<script src="/assets/js/g.line-min.js" type="text/javascript" charset="utf-8"></script> 
	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

	<style type="text/css">
<?php

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
echo " { margin: 0 0 0 0; padding: 5px; font-size: 1.1em; width: 120px; }\n";

?>
	</style>
	
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
						var to = event.target.id.replace( "sortable", "" );
						var task = ui.item.attr("id").replace( "task", "" );
						var last = <?php echo $lastgroupid; ?>;
						var dataString = 'from='+ from + '&to=' + to + "&task=" + task + "&last=" + last;
						// $("#errordiv").html("res="+dataString);
						// alert ("moved");return false;
						$.ajax({  
						  type: "POST",  
						  url: "/kanban/move",  
						  data: dataString,  
						  success: function(data) {  
						    	// $("#errordiv").html("This is the result"+data);
							  	location.reload();
						  }, 
					  error: function(x,e) {  
					    $("#errordiv").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
					  }   
						});  
						return false;
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
		$(function() {
			$( "#dialog-edit-task" ).dialog({
			width: 250,
			resizable: false,
			modal: false,
			autoOpen: false,
			buttons: {
				Cancel: function() {
					$( this ).dialog( "close" );
				},
				Ok: function() {
					var dataString = $("#updatetask").serialize();
					//$("#errordiv").html("res="+dataString);
					// alert (dataString);return false;  
					$.ajax({  
					  type: "POST",  
					  url: "/kanban/updatetask",  
					  data: dataString,  
					  success: function(data) {  
					    // $("#errordiv").html("This is the result"+data);  				     
					    location.reload();
					  },
					  error: function(x,e) {  
						    $("#errordiv").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
						  }
					});  
					$( this ).dialog( "close" );
				   //location.reload();
				},
				Del: function() {
					var dataString = $("#updatetask").serialize();
					//$("#errordiv").html("res="+dataString);
					// alert (dataString);return false;  
					$.ajax({  
					  type: "POST",  
					  url: "/kanban/deletetask",  
					  data: dataString,  
					  success: function(data) {  
					    // $("#errordiv").html("This is the result"+data);  				     
					    location.reload();
					  },
					  error: function(x,e) {  
						    $("#errordiv").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
						  }
					});  
					$( this ).dialog( "close" );
				   //location.reload();
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
					// $("#errordiv").html("res="+dataString);
					// alert (dataString);return false;  
					$.ajax({  
					  type: "POST",  
					  url: "/kanban/addtask",  
					  data: dataString,  
					  success: function(data) {  
					    // $("#errordiv").html("This is the result"+data);  				     
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
			$( "#dialog-diagrams" ).dialog({
			modal: false,
			width: 550,
			height: 450,
			autoOpen: false,
			buttons: {
				"Close": function() {
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
			$("#priority").val( data.priority);
			$("#projectid").val( data.projectid);
			$("#sprintid").val( data.colortag);
			$("#taskid").val( data.taskid);
			$("#colortag").val( data.colortag);			
			$("#sprintid").val( data.sprintid);			
			$("#newsprintid").val( data.sprintid);						
		   $("#updatetask").fill(data);  				     
		  }, 
		  error: function(x,e) {  
		    $("#groupresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
		  }  
		});  
	}
	</script>

<script type="text/javascript">
			$(function() {
                var r = Raphael("diagram");
                r.g.txtattr.font = "12px 'Fontin Sans', Fontin-Sans, sans-serif";

		<?php
			$str = "";
			for( $i = 0; $i < count( $days ); $i++ ) {
				$str = $str.$days[ $i ].",";				
			}
			$daysstring=substr($str,0,-1);
		
			$str = "";
			for( $i = 0; $i < count( $diagrambaseline ); $i++ ) {
				$str = $str.$diagrambaseline[ $i ].",";				
			}
			$expected=substr($str,0,-1);
			
			$str = "";
			for( $i = 0; $i < count( $diagramactual ); $i++ ) {
				$str = $str.$diagramactual[ $i ].",";				
			}
			$actual=substr($str,0,-1);
		?>          
				var days = [ <?php echo $daysstring ?> ];
				var expected = [ <?php echo $expected ?> ];
				var actual = [ <?php echo $actual ?> ];
		      	if( actual.length <= 1 ) {
					actual = [];
				}
				if( expected.length <= 1 ) {
					expected = [];
				}
                r.g.text(160, 10, "Burn Down Chart ");
 
                r.g.linechart(30, 20, 450, 300, days, [actual,expected], { axis: "0 0 1 1"});
			});

		</script>
		
		<script src="/assets/ticker/DocumentationExample/includes/jquery.ticker.min.js" type="text/javascript"></script>
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
				var allgroups = $('#kanbanboard').children('ul');
				var maxheight=0;
				allgroups.each(function() {
					var height = $(this).height();					
					if( height > maxheight ) {
						maxheight = height;
					}
				});
				maxheight = maxheight + 120;				
				allgroups.each(function() {
					$(this).css( 'height',maxheight );
				});
				
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
		<li><a href="" onclick="$('#dialog-diagrams').dialog('open'); return false; ">Charts</a></li>		
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

<div class="kanbanboard" id="kanbanboard">

<div id="ticker-wrapper" class="no-js">
		<ul id="js-news" class="js-hidden">
			<li class="news-item">The '<?php echo $projectname; ?>' Board. Sprint: <?php echo $sprintname; ?> <?php echo $startdate; ?> through <?php echo $enddate; ?> </li>
<?php
foreach ($tickers as $ticker) {	
	echo '<li class="news-item">'.$ticker['message'].'</li>';
}
?>					
		</ul>		
</div>

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
<div class="inside-postit-header">'.$row['heading'].'</div>
<div class="inside-postit-text">'.$row['description'].'</div>
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


<div id="dialog-diagrams" title="Burndown">	
	<div id="diagram">
	</div>
</div>


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
					<input name="priority" id="priority" value="100" />
				</td></tr>
				<tr><td> Estimate :</td><td>
					<input name="estimation" id="estimation" value="0" />
				</td></tr>
				<tr><td> Color Tag :</td><td>
					<select name="colortag" id="colortag"><option value="1">Yellow</option><option value="2">Green</option><option value="3">Red</option><option value="4">Blue</option><option value="5">Pink</option></select>
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
						<input name="priority" />
					</td></tr>
					<tr><td> Estimate :</td><td>
						<input name="estimation" />
					</td></tr>
				    <tr><td> Color Tag :</td><td>
					<select name="colortag" ><option value="1">Yellow</option><option value="2">Green</option><option value="3">Red</option><option value="4">Blue</option><option value="5">Pink</option></select>
					</td></tr>										
				</table>
			</div>
		</form>

	</div>
</div>


</body>
</html>

