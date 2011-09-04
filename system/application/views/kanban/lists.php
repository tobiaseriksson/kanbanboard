<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Tobias Kanban Board</title>
		<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.8.1.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="/assets/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/assets/js/jquery-ui-1.8.1.custom.min.js"></script>
		<script type="text/javascript">

		</script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}
			.demoHeaders { margin-top: 2em; }
			#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 4px;}
			div.kanbanactions { background-color: blue; color: black; border-style: solid; }
			div.kanbanboard { display: block; background-color: red; color: black; border-style: solid; }
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
echo " { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 120px; }\n";

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
}
?>
").sortable(
						{
						connectWith: '.connectedSortable',
						items: 'li:not(.rubrik)',
						receive: function(event, ui) { 
								$("#mertext").append( "Event:"+ui.sender.attr("id")+", item="+ui.item.attr("id")+", placeholder="+ui.placeholder.attr("id")+", event.target = "+event.target.id+"<br>" ); 
				var from = ui.sender.attr("id").replace( "sortable", "" );
				var to = event.target.id.replace( "sortable", "" );
				var task = ui.item.attr("id").replace( "task", "" );
				var dataString = 'from='+ from + '&to=' + to + "&task=" + task;
				$("#quote").html("res="+dataString);
				//alert (dataString);return false;  

				$.ajax({  
				  type: "POST",  
				  url: "/kanban/move",  
				  data: dataString,  
				  success: function(data) {  
				    $("#quote").html("This is the result"+data);  				     
				  }  
				});  
				return false;

							}			
						}
					).disableSelection();

				}
			);
// +event+",UI:"+ui.text()+";<br>" ); 
		$(function() {
			$("#start").button({
		    		icons: {
		        		primary: 'ui-icon-gear'
		    		},
		    		text: false
        		}).click( function() {
				var priority = $("#priority").val();  
				var heading = $("#heading").val();  
				// var dataString = 'heading='+ heading + '&priority=' + priority;				
				var dataString = $("#newtask").serialize();
				$("#quote").html("res="+dataString);
				// alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/add",  
				  data: dataString,  
				  success: function(data) {  
				    $("#quote").html("This is the result"+data);  				     
				  }  
				});  
				return false;  
				// $("#quote").load("http://localhost/kanban/add");
				// $("#mertext").append( "hmmm!<br>" );
				// $("#sortable1").append('<li class="ui-state-default">'+$("#taskname").val()+'<br>'+$("#taskdescription").val()+'</li>');
			})

		});			
			
		$(function() {
			$("#sortable1").live();
		 });	

		
		</script>
	
	</head>
	<body>
	<h1>Kanban-Board!</h1>
	

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Add Task</a></li>
		<li><a href="#tabs-2">Add Group</a></li>
		<li><a href="#tabs-3">More</a></li>
	</ul>
	<div id="tabs-1">
		<p>tab 1 text</p>
	</div>
	<div id="tabs-2">
		<p>tab 2 text.</p>
	</div>
	<div id="tabs-3">
		<p>tab 3 text</p>
	</div>
</div>




	<div class="kanbanboard">
	
<?php
$i=1;
$groupid=-1;
foreach ($groups as $group) {		
	echo '<ul id="sortable'.$group['id'].'" class="connectedSortable">';
	echo '<li class="ui-state-default rubrik">'.$group['name'].'</li>';
	$groupid = $group['id'];
	foreach ($tasks as $row)
	{					 
		if( $groupid == $row['group_id'] ) {
			echo '<li id="task'.$row['taskid'].'" class="ui-state-default">'.$row['heading'].',<br>bla bla bla</li>';
		}

	}
	echo "</ul>\n";
}
?>


	</div><!-- End demo -->


	
mertext
	<div  id="mertext">
sdfsdfsdf
	</div>

quote
	<div id="quote"></div>


<div class="kanbanactions">
		<h2 class="demoHeaders">Add New Task</h2>
		<form id="newtask" name="newtask" action="">
		<div>
			<table>
			<tr><td> Team :</td><td>
<select name="group">
<?php
foreach ($groups as $row) {			
	echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
?>
</select>
			</td></tr>
			<tr><td>New Task/TR :</td><td><input name="heading"  style="z-index: 100; position: relative" title="type &quot;a&quot;" /></td></tr>
			<tr><td>Text :</td><td><textarea name="taskdescription" rows="10" cols="30" style="z-index: 100; position: relative" title="type &quot;a&quot;" ></textarea></td></tr>
			<tr><td> Priority :</td><td>
<select name="priority">
<option value="1">Very Important</option>
<option value="2">Normal</option>
<option value="3">Below Normal</option>
</select>
			</td></tr>
<tr><td><div>
    <input type="submit" name="g" value="Submit" id="g" />
  </div>
</td></tr>
			</table>
		</div>
		<button id="start">Button with icon only</button>
		</form>
		<br>
	</div>

	</body>
<script  type="text/javascript">
$(function() {
			$("#tabs").tabs();
		});

$('#newtask').submit(function() {
			  // alert("form="+$(this).attr('id')+"res="+$(this).serialize());
				var dataString = $("#newtask").serialize();
				$("#quote").html("res="+dataString);
				// alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/add",  
				  data: dataString,  
				  success: function(data) {  
				    $("#quote").html("This is the result"+data);  				     
				  }  
				});  
			  return false;
			});
</script>
</html>

