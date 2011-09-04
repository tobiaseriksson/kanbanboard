<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>jQuery UI Tabs - Default functionality</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.8.1.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="/assets/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.8.1.custom.min.js"></script>
	<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
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
			div.kanbanactions { background-color: white; color: black; border-style: none; }
			div.kanbanboard { display: block; background-color: red; color: black; border-style: none; }
		</style>
	<script type="text/javascript">
	$(function() {
		$("#sortable1, #sortable2").sortable({
			connectWith: '.connectedSortable'
		}).disableSelection();
	});
	</script>


		<style type="text/css">
#sortable1 ,#sortable2 ,#sortable3 ,#sortable4 ,#sortable5  { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; }
#sortable1 li ,#sortable2 li ,#sortable3 li ,#sortable4 li ,#sortable5 li  { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 120px; }

		</style>
		<script type="text/javascript">
			$(function() {
				$("#sortable1 ,#sortable2 ,#sortable3 ,#sortable4 ,#sortable5 ").sortable(
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

		
		</script>
</head>
<body>

<div class="demo">

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Add Task</a></li>
		<li><a href="#tabs-2">Add Group</a></li>
		<li><a href="#tabs-3">Preferences</a></li>
	</ul>
	<div id="tabs-1">
		<p>
<div class="kanbanactions">
	<h2 class="demoHeaders">Add New Task</h2>
	<form id="newtask" name="newtask" action="">
		<div>
			<table>
				<tr>
					<td> Team :</td>
					<td>
						<select name="group"><option value="1">unassigned</option><option value="2">unassigned</option><option value="3">team 10</option><option value="4">team umi</option><option value="5">finished</option></select>
					</td>
				</tr>
				<tr><td>New Task/TR :</td><td><input name="heading"  style="z-index: 100; position: relative" title="type &quot;a&quot;" /></td></tr>
				<tr><td>Text :</td><td><textarea name="taskdescription" rows="10" cols="30" style="z-index: 100; position: relative" title="type &quot;a&quot;" ></textarea></td></tr>
				<tr><td> Priority :</td><td>
					<select name="priority">
					<option value="1">Very Important</option>
					<option value="2">Normal</option>
					<option value="3">Below Normal</option>
					</select>
				</td></tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" name="g" value="Submit" id="g" /> <button id="start">Button with icon only</button>
					</td>
				</tr>
			</table>
		</div>
	</form>

</div>


		</p>
	</div>
	<div id="tabs-2">
		<p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
	</div>
	<div id="tabs-3">
		<p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
		<p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
	</div>
</div>

</div><!-- End demo -->

<div class="demo-description">

<p>Click tabs to swap between content that is broken into logical sections.</p>

</div><!-- End demo-description -->



<div class="kanbanboard">

<ul id="sortable1" class="connectedSortable">
	<li class="ui-state-default">Item 1</li>
	<li class="ui-state-default">Item 2</li>
	<li class="ui-state-default">Item 3</li>
	<li class="ui-state-default">Item 4</li>
	<li class="ui-state-default">Item 5</li>
</ul>

<ul id="sortable2" class="connectedSortable">
	<li class="ui-state-highlight">Item 1</li>
	<li class="ui-state-highlight">Item 2</li>
	<li class="ui-state-highlight">Item 3</li>
	<li class="ui-state-highlight">Item 4</li>
	<li class="ui-state-highlight">Item 5</li>
</ul>

</div><!-- End demo -->

<div class="demo-description">

<p>
	Sort items from one list into another and vice versa, by passing a selector into
	the <code>connectWith</code> option. The simplest way to do this is to
	group all related lists with a CSS class, and then pass that class into the
	sortable function (i.e., <code>connectWith: '.myclass'</code>).
</p>

</div><!-- End demo-description -->



</body>
</html>

