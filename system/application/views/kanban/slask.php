{
				change: function(event, ui) { 
alert("ok);
					/*var grouporder='';
					$('#sortablegroup li').each(function(i) {
						grouporder=grouporder+i.id+',';
					});
					$("#groupresult").html("order; "+grouporder);
					*/
				}
			}




		<script type="text/javascript">
			$(function() {
				$("<?php 
					$first=1; 
					foreach ($groups as $row) {
						if( $first < 1 ) echo ",";
						$first=0;		
						echo "#sortable".$row['id']." ";
					}
					?>").sortable({
				connectWith: '.connectedSortable',
				items: 'li:not(.rubrik)',
				receive: function(event, ui) { 
						$("#mertext").append( "Event:"+ui.sender.attr("id")+", item="+ui.item.attr("id")+", placeholder="+ui.placeholder.attr("id")+", event.target = "+event.target.id+"<br>" ); 
						var from = ui.sender.attr("id").replace( "sortable", "" );
						var to = event.target.id.replace( "sortable", "" );
						var task = ui.item.attr("id").replace( "task", "" );
						var dataString = 'from='+ from + '&to=' + to + "&task=" + task;
						$("#quote").html("res="+dataString);
						// alert (dataString);return false;
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
			}).disableSelection();
		});
	</script>
	
	<script type="text/javascript">
		$(function() {
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
		});
			
	</script>

	<script type="text/javascript">
		$(function() {
			$('#newgroup').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#newgroup").serialize();
				$("#quote").html("res="+dataString);
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
			};	
		};
			
	</script>

