<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>The '<?php echo $projectname; ?>' Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
	<link type="text/css" href="/assets/ticker/styles/ticker-style.css" rel="stylesheet" />
	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

	<script type="text/javascript" src="/assets/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.ui.touch-punch.js"></script>

<?php $request_uri = getenv("REQUEST_URI"); ?>

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
			$('#generalsettingsform').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#generalsettingsform").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/editgeneralsettings",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});

	</script>

	<script type="text/javascript">
		
		$(function() {
			$('#editproject').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#editproject").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/editprojectname",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});

		$(function() {
			$('#deleteproject').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#deleteproject").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/deleteproject",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
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
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/addgroup",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
		
		$(function() {
			$('#editgroup').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#editgroup").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/editgroup",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});

		$(function() {
			$('#deletegroup').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#deletegroup").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/deletegroup",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
			
	</script>
	
	<script type="text/javascript">
		$(function() {
			$('#newgworkpackage').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#newgworkpackage").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/addworkpackage",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
		
		$(function() {
			$('#editworkpackage').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#editworkpackage").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/editworkpackage",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});

		$(function() {
			$('#deleteworkpackage').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#deleteworkpackage").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/deleteworkpackage",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
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
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/addsprint",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
		
		$(function() {
			$('#editsprint').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#editsprint").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/editsprint/<?php echo $projectid; ?>",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});

		$(function() {
			$('#deletesprint').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#deletesprint").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/deletesprint",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});
			
		$(function() {
			$('#movetasksbetweensprints').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#movetasksbetweensprints").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/movetasksbetweensprints",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }  
				});  
			  	return false;
			});	
		});

		$(function() {
			$('#importtaskstosprint').submit(function() {
				// alert("form="+$(this).attr('id')+"res="+$(this).serialize());				
				var dataString = $("#importtaskstosprint").serialize();
				$("#debugresult").html("res="+dataString);
				//alert (dataString);return false;  
				$.ajax({  
				  type: "POST",  
				  url: "/kanban/importtaskstosprint",  
				  data: dataString,  
				  success: function(data) {  
				    $("#debugresult").html("This is the result"+data);  				     
				  }, 
				  error: function(x,e) {  
				    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
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
					$("#debugresult").html("order; "+grouporder);
					var dataString = 'grouporder='+grouporder+'&'+'projectid='+<?php echo $projectid; ?>;
					$.ajax({  
					  type: "POST",  
					  url: "/kanban/changegrouporder",  
					  data: dataString,  
					  success: function(data) {  
					    $("#debugresult").html("This is the result"+data);  				     
					  }, 
					  error: function(x,e) {  
					    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
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
		function fillInGroupDetails(selectedIndex) {
			var projectid = <?php echo $projectid; ?>;
			var groupID = $("#editgroup_groupid").val();
			if( groupID <= 0 ) {		
				$("#editgroup_name").val( 'not a valid selection' );
				$("#editgroup_wip").val( '0' );
				return;
			}
			var dataString = "";
			$.ajax({  
			  dataType: "json",  
			  url: "/kanban/groupdetails/"+projectid+"/"+groupID,  
			  data: dataString,  
			  success: function(data) {  			
				$("#editgroup_name").val( data.name );
				$("#editgroup_wip").val( data.wip );
			  }, 
			  error: function(x,e) {  
			    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
			  }  
			});  
		}

		function fillInSprintDetails(selectedIndex) {
			var projectid = <?php echo $projectid; ?>;
			var sprintid = $("#editsprint_sprintid").val();
			if( sprintid <= 0 ) {
				$("#editsprint_name").val( "N/A" );
				$("#editsprint_startdate").val( "" );
				$("#editsprint_enddate").val( "" );
				return;
			}
			var dataString = "";
			$("#debugresult").html("req: /kanban/sprintdetails/"+projectid+"/"+sprintid );
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
			    $("#debugresult").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
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
		// $("#debugresult").html("sprints = "+numberOfSprints);
		if( numberOfSprints <= 1 ) {
			$('#deletesprint input[type=submit]', this).attr('disabled', 'disabled');
		}
	});
	
	</script>

	<script>
		$(function() {
			$( "#tabs" ).tabs();
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
<body>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanban/newboard/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
		<li><a href="<?php echo site_url( '/kanban/status/'.$projectid.'/'.$sprintid ); ?>">Status</a></li>
		<li><a href="<?php echo site_url( '/kanban/timeline/'.$projectid.'/'.$sprintid ); ?>">Timeline</a></li>	
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
	
		<div id="tabs">
			<ul>
				<li><a href="<?=$request_uri?>#general-tab">General</a></li>
				<li><a href="<?=$request_uri?>#group-tab">Groups</a></li>
				<li><a href="<?=$request_uri?>#wp-tab">Work Packages</a></li>
				<li><a href="<?=$request_uri?>#sprints-tab">Sprints</a></li>
				<li><a href="<?=$request_uri?>#import-tab">Import</a></li>
				<li><a href="<?=$request_uri?>#projects-tab">Projects</a></li>
			</ul>

			<div id="general-tab">
				<?php echo $generaltab; ?>	
			</div>
			<div id="group-tab">
				<?php echo $grouptab; ?>	
			</div>
			<div id="wp-tab">
				<?php echo $workpackagetab; ?>	
			</div>	
					
			<div id="sprints-tab">
				<?php echo $sprinttab; ?>	
			</div>	
			
			<div id="import-tab">
				<?php echo $importtab; ?>		
			</div>
	
			<div id="projects-tab">
				<?php echo $projecttab; ?>	
			</div>	
	
		</div>
		
		<div id="debugresult">
		</div>
		
		
	</div>

</div>

</body>
</html>

	
