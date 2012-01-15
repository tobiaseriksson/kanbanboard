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
		
		.lefthandside {
			vertical-align: top;
		}
		.righthandside {
			vertical-align: top;
		}
		
		.settingsleftside {
			text-align: right;
			vertical-align: top;
			width: 100px;
		}

		.settingsrightside {
			text-align: left;
			vertical-align: top;
			width: 100px;
		}


		.settingstable {
			border: 0px;
			vertical-align: top;
		}
			
		.settingsmiddleseparator {
			width: 50px;
		}
		
		.sprintlist {
			white-space: nowrap;
		}
		
	</style>
	
	
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
			$('#newgroup').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#newgroup").serialize();
				$("#sprintresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/addgroup",  
				  data: dataString,  
				  success: function(data) {  
				    $("#groupresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#groupresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
		
		$(function() {
			$('#editgroup').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#editgroup").serialize();
				$("#sprintresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/editgroup",  
				  data: dataString,  
				  success: function(data) {  
				    $("#groupresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#groupresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});

		$(function() {
			$('#deletegroup').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#deletegroup").serialize();
				$("#sprintresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/deletegroup",  
				  data: dataString,  
				  success: function(data) {  
				    $("#groupresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#groupresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
			
	</script>
	
		<script type="text/javascript">
		$(function() {
			$('#newsprint').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#newsprint").serialize();
				$("#sprintresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/addsprint",  
				  data: dataString,  
				  success: function(data) {  
				    $("#sprintresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#sprintresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
		
		$(function() {
			$('#editsprint').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#editsprint").serialize();
				$("#sprintresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/editsprint",  
				  data: dataString,  
				  success: function(data) {  
				    $("#sprintresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#sprintresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});

		$(function() {
			$('#deletesprint').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#deletesprint").serialize();
				$("#sprintresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/deletesprint",  
				  data: dataString,  
				  success: function(data) {  
				    $("#sprintresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#sprintresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
			
		$(function() {
			$('#movetasksbetweensprints').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#movetasksbetweensprints").serialize();
				$("#sprintresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/movetasksbetweensprints",  
				  data: dataString,  
				  success: function(data) {  
				    $("#sprintresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#sprintresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});

		$(function() {
			$('#importtaskstosprint').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#importtaskstosprint").serialize();
				$("#sprintresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/importtaskstosprint",  
				  data: dataString,  
				  success: function(data) {  
				    $("#sprintresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#sprintresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
												
	</script>

	<script type="text/javascript">
		$(function() {
			$("#sortablegroup").sortable({
				stop: function(event, ui) { 
					
					var grouporder='';
					$('#sortablegroup li').each(function() {
						grouporder=grouporder+$(this).attr('id').replace('sgroup','')+',';
					});
					$("#groupresult").html("order; "+grouporder);
					var dataString = 'grouporder='+grouporder+'&'+'projectid='+<?php echo $projectid; ?>;
					$.ajax({  
					  type: "POST",  
					  url: "/kanban/changegrouporder",  
					  data: dataString,  
					  success: function(data) {  
					    $("#groupresult").html("This is the result"+data);  				     
					  }, 
					  error: function(x,e) {  
					    $("#groupresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
					  }  
					});  
					
				}
			});

			$("#sortablegroup").disableSelection();
		});
		
		
		

	</script>


	<script type="text/javascript">
		$(function() {
			$("#sortablegroup").sortable();
			$("#sortablegroup").disableSelection();
		});
	</script>

	<script type="text/javascript">
		

		function fillInSprintDetails(sprintid) {
		var projectid = <?php echo $projectid; ?>;
		var sprintid = $("#editsprint_sprintid").val();
		if( sprintid <= 0 ) {
			$("#editsprint_name").val( "N/A" );
			$("#editsprint_startdate").val( "" );
			$("#editsprint_enddate").val( "" );
			return;
		}
		var dataString = "";
		$("#sprintresult").html("req: /kanban/sprintdetails/"+projectid+"/"+sprintid );
		$.ajax({  
		  dataType: "json",  
		  url: "/kanban/sprintdetails/"+projectid+"/"+sprintid,  
		  data: dataString,  
		  success: function(data) {  			
			$("#editsprint_name").val( data.name );
			$("#editsprint_startdate").val( data.startdate );
			$("#editsprint_enddate").val( data.enddate );
		  }, 
		  error: function(x,e) {  
		    $("#sprintresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
		  }  
		});  
	}
	</script>


<script>

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
		$( "#editsprint_startdate" ).datepicker();
		$( "#editsprint_enddate" ).datepicker();
		$( "#newsprint_startdate" ).datepicker();
		$( "#newsprint_enddate" ).datepicker();
		$( "#newsprint_startdate").datepicker('setDate', new Date());
		var nowplus30 = new Date();
		nowplus30.setDate(nowplus30.getDate()+30);
		$( "#newsprint_enddate").datepicker('setDate', nowplus30);
	});


	$(function() {
		var numberOfSprints = $("#deletesprint_sprintid option").size();
		// $("#sprintresult").html("sprints = "+numberOfSprints);
		if( numberOfSprints <= 1 ) {
			$('#deletesprint input[type=submit]', this).attr('disabled', 'disabled');
		}
	});
	
	</script>

</head>
<body>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanban/project/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
		<li><a href="<?php echo site_url( '/kanban/status/'.$projectid.'/'.$sprintid ); ?>">Status</a></li>
		<li><a href="<?php echo site_url( '/kanban/tickers/'.$projectid.'/'.$sprintid ); ?>">Tickers</a></li>	
		<li><a href="<?php echo site_url( '/kanban/resources/'.$projectid.'/'.$sprintid ); ?>">Resources</a></li>	
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

<h2>Settings</h2>

<table class="settingstable">
  <tr>
    <td class="lefthandside" ><h3>Groups</h3>
        <table class="settingstable" >
          <tr>
            <td ><form id="newgroup" name="newgroup" action="">
                <input type="hidden" name="newgroup_projectid" id="newgroup_projectid" value="<?php echo $projectid; ?>" />
                <table >
                  <tr>
                    <td class="settingsrightside"><h4>Add New Group :</h4></td>
                  </tr>
                  <tr>
                    <td class="settingsleftside">Name:</td>
                    <td><input name="newgroup_name" id="newgroup_name" value="any name" /></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td class="settingsleftside"><input type="submit"  value="Add"  /></td>
                  </tr>
                </table>
              </form>
                <br>
                <hr>
                <form id="editgroup" name="editgroup" action="">
                  <input type="hidden" id="editgroup_projectid" name="editgroup_projectid" value="<?php echo $projectid; ?>" />
                  <table >
                    <tr>
                      <td class="settingsrightside"><h4>Edit Group Name :</h4></td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">Select:</td>
                      <td class="settingsrightside"><select name="editgroup_groupid" id="editgroup_groupid">
                          <?php						
							foreach ($groups as $group) {		
							
								echo ' <option value="'.$group['id'].'">'.$group['name'].'</option>';
														
							}						
							?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">New name:</td>
                      <td class="settingsrightside"><input name="editgroup_name" id="editgroup_name" value="any name" /></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td class="settingsleftside"><input type="submit"  value="Update"  /></td>
                    </tr>
                  </table>
                </form>
                <br>
                <hr>
                <form id="deletegroup" name="deletegroup" action="">
                  <input type="hidden" id="deletegroup_projectid" name="deletegroup_projectid" value="<?php echo $projectid; ?>" />
                  <input type="hidden" id="deletegroup_firstgroupid" name="deletegroup_firstgroupid" value="<?php echo $groups[0]['id']; ?>" />
                  <table >
                    <tr>
                      <td class="settingsrightside"><h4>Delete Group :</h4></td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">Select:</td>
                      <td class="settingsrightside"><select name="deletegroup_groupid" id="deletegroup_groupid">
                          <?php						
							foreach ($groups as $group) {		
							
								echo ' <option value="'.$group['id'].'">'.$group['name'].'</option>';
														
							}						
							?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td colspan=2> Note! All Tasks will be moved to 'unassigned'. </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td class="settingsleftside"><input type="submit"  value="Delete"  /></td>
                    </tr>
                  </table>
                </form>
                <br>
                <hr>
                <table>
                  <tr>
                    <td colspan=2><h4> The Group Order :</h4></td>
                  </tr>
                  <tr>
                    <td class="settingsleftside"></td>
                    <td class="settingsrightside"><ul id="sortablegroup" >
                        <?php						
							foreach ($groups as $group) {		
							
								echo ' <li id="sgroup'.$group['id'].'"  class="ui-state-highlight">'.$group['name'].'</li>'."\n";
														
							}						
							?>
                    </ul></td>
                    <td>    
                  </tr>
              </table>
			  </td>
          </tr>
		  <tr>
		  	<td>
					  <table>					
						<tr><td> Result :</td></tr>
						<tr><td >
							<div id="groupresult">
							</div>
						</td></tr>
					</table>
			</td>
		  </tr>
		  
		  
      </table>
  </td>
<td class="settingsmiddleseparator"></td>
<td>x</td>
<td class="settingsmiddleseparator"></td>
  <td class="righthandside" >
  <h3>Sprints</h3>
        <table class="settingstable">
		
		<tr>
				<td >
					<form id="newsprint" name="newsprint" action="">
					<input type="hidden" id="newsprint_projectid" name="newsprint_projectid" value="<?php echo $projectid; ?>" />
					<table >
                      <tr>
                        <td><h4>Add New Sprint :</h4></td>
                      </tr>
                      <tr>
                        <td class="settingsleftside">Name:</td>
                        <td class="settingsrightside"><input id="newsprint_name" name="newsprint_name" value="any name" /></td>
                      </tr>
                      <tr>
                        <td class="settingsleftside">Start:</td>
                        <td class="settingsrightside"><input id="newsprint_startdate"  name="newsprint_startdate" value="2010-11-01" /></td>
                      </tr>
                      <tr>
                        <td class="settingsleftside">End:</td>
                        <td class="settingsrightside"><input id="newsprint_enddate"  name="newsprint_enddate" value="2010-12-01" /></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td class="settingsleftside"><input type="submit"  value="Add"  /></td>
                      </tr>
                    </table>
					</form>
					<br>
					<hr>
					<form id="editsprint" name="editsprint" action="">
					<input type="hidden" id="editsprint_projectid" name="editsprint_projectid" value="<?php echo $projectid; ?>" />
					<table >					
						<tr><td><h4>Edit Sprint :</h4></td></tr>
						<tr><td class="settingsleftside">Select : </td><td class="settingsrightside"><select name="editsprint_sprintid" id="editsprint_sprintid" onChange="fillInSprintDetails(this.selectedIndex)">
							<option value="0">Select Sprint</option>
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
						</td></tr>
						<tr><td class="settingsleftside">Name:</td><td class="settingsrightside"><input id="editsprint_name" name="editsprint_name" value="any name" /></td></tr>
						<tr><td class="settingsleftside">Start:</td><td class="settingsrightside"><input id="editsprint_startdate" name="editsprint_startdate" value="2010-11-01" /></td></tr>
						<tr><td class="settingsleftside">End:</td><td class="settingsrightside"><input id="editsprint_enddate" name="editsprint_enddate" value="2010-12-01" /></td></tr>
						<tr><td class="settingsleftside"></td><td class="settingsleftside"><input type="submit"  value="Update"  /></td></tr>
					</table>
					</form>
					<br>
					<hr>
					
					<form id="deletesprint" name="deletesprint" action="">
					<input type="hidden" id="deletesprint_projectid" name="deletesprint_projectid" value="<?php echo $projectid; ?>" />					
					<table>					
					<tr><td><h4>Delete Sprint :</h4></td></tr>
						<tr>
						  <td class="settingsleftside">Select:</td>
						  <td class="settingsrightside"><select name="deletesprint_sprintid" id="deletesprint_sprintid">
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
							</td></tr>
						<tr><td colspan=2>Note! All Tasks for this sprint will be deleted !!!.</td></tr>
						<tr><td></td><td class="settingsleftside"><input type="submit"  value="Delete"  /></td></tr>
					</table>
					</form>
					<br>
					<hr>
					<form id="movetasksbetweensprints" name="movetasksbetweensprints" action="">
					<input type="hidden" id="movetasksbetweensprints_projectid" name="movetasksbetweensprints_projectid" value="<?php echo $projectid; ?>" />					
					<input type="hidden" id="movetasksbetweensprints_lastgroupid" name="movetasksbetweensprints_lastgroupid" value="<?php echo $groups[count($groups)-1]['id']; ?>" />
					<table>					
					<tr><td><h4>Move Tasks Between Sprints :</h4></td></tr>
						<tr>
						  <td class="settingsleftside">From :</td>
						  <td class="settingsrightside"><select name="movetasksbetweensprints_from" id="movetasksbetweensprints_from">
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
							</td></tr>
						 <tr>
							<td class="settingsleftside">To :</td>
							<td class="settingsrightside"><select name="movetasksbetweensprints_to" id="movetasksbetweensprints_to">
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
							</td></tr>
						<tr><td colspan=2></td></tr>
						<tr><td></td><td class="settingsleftside"><input type="submit"  value="Move"  /></td></tr>
					</table>
					</form>
					<br>
					<hr>
					<table>					
						<tr><td class="settingsrightside"><h4>Select Sprint  :</h4></td></tr>
					  <tr><td></td><td class="settingsrightside">
					  	<table class="sprintlist">
							<tr><td>Name</td><td>Start</td><td>End</td></tr>
							<?php						
							foreach ($sprints as $sprint) {									
								echo '<tr><td><a href="/kanban/sprint/'.$projectid.'/'.$sprint['id'].'">'.$sprint['name'].'</a> </td><td> '.$sprint['startdate'].' </td><td> '.$sprint['enddate']."</td></tr>\n";
							}						
							?>
						</table>
						</td>
						</tr>
					</table>
					<br>
					<hr>
					<form id="importtaskstosprint" name="importtaskstosprint" action="">
					<input type="hidden" id="importtaskstosprint_projectid" name="importtaskstosprint_projectid" value="<?php echo $projectid; ?>" />					
					<table>					
					<tr><td><h4>Import Tasks :</h4></td></tr>
						<tr>
						  <td class="settingsleftside">To Sprint :</td>
						  <td class="settingsrightside"><select name="importtaskstosprint_sprintid" id="importtaskstosprint_sprintid">
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
							</td></tr>
						 <tr>
							<td class="settingsleftside">Text :</td>
						<td class="settingsrightside"><textarea name="importtaskstosprint_text" id="importtaskstosprint_text" cols="40" rows="20">
heading;description;priority;estimate;color;added
where
heading is text 
description is text
priority an integer 0=low 100=high
estimate an integer 
color an integer, 1=yellow,2=green,3=red
added is a date(YYYY-MM-DD)
						</textarea>
							</td></tr>
						<tr><td colspan=2></td></tr>
						<tr><td></td><td class="settingsleftside"><input type="submit"  value="Import"  /></td></tr>
					</table>
					</form>
				</td>
				</tr>
				
				<tr>
				<td >
					<table>					
						<tr><td> Result :</td></tr>
						<tr><td >
							<div id="sprintresult">
							</div>
						</td></tr>
					</table>
				</td>
		</tr>
		
        </table>		
	</td>
  </tr>
</table>
</div>
</div>

</body>
</html>


