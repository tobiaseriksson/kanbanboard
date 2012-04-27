<html>
<head> 
<title>Welcome To My Task Board</title>

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
	<h3>Login</h3>
	<a href="<?php echo site_url( '/kanban/register'); ?>"><i>register here!</i></a>
	<div class="errortext"><?php echo $errormessage."<br>"; ?></div>
	<form name="login" action="<?php echo site_url( '/kanban/login');?>" method="post">
		<input type="hidden" name="id" value="0" />
		<input type="hidden" name="redirect_to_url" value="<?php echo $redirect_to_url; ?>" />
		<table>
			<tr><td>Team Login</td><td><input type="text" name="login" size="15"></td></tr>
			<tr><td>Password</td><td><input type="password" name="password" size="15"></td></tr>
			<tr><td></td><td align="right"><input type="submit" name="loginbutton" value="login"></td><td></td></tr>
		</table>
	</form>


</div>

</center> 

</td> 
</tr> 
</table> 

</body>
</html>




