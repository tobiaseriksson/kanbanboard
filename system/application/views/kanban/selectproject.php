<html>
<head>
<title>Select Project</title>


	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

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
		
		#lefthandside {
			float: left;
			width: 300px;
			padding-left: 10px;
			border-left: 1px dashed #999999;
		}
		#righthandside {
			float: right;
			width: 300px;
			padding-right: 10px;
			border-right: 1px dashed #999999;
		}
			
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
<table width="100%" height="100%" valign="middle"> 
<tr> 
<td> 
<center> 
<img src="/assets/images/boardfirstpage.png">
<div>
<?php
foreach ($projects as $project) {		
	echo '<A HREF="/kanban/newboard/'.$project['id'].'">'.$project['name'].'</A>';
	echo '<br>';
}
?>	


	<h2>Add New Project</h2>
	<form id="newproject" name="newproject" action="/kanban/addproject" method="post">	
		<div>
			<table>
				<tr><td>Project Name :</td><td><input name="name"  style="z-index: 100; position: relative" title="type &quot;a&quot;" /></td></tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" name="g" value="Submit" id="g" /> 
					</td>
				</tr>
			</table>
		</div>
	</form>

</div></center> 
</td> 
</tr> 
</table> 

</body>
</html>

