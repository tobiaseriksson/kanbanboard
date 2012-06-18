<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta charset="UTF-8" />
	<! base href="http://kanban.tsoft.se/" />
	<base href="<?php echo site_url( '/' ); ?>" />
	<title>The Kanban Blog</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.8.17.custom.css" rel="stylesheet" />

	<script type="text/javascript" src="/assets/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.ui.touch-punch.js"></script>
	
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
<body bgcolor=white>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanbanblog'); ?>">Home</a></li>
		<li><a href="<?php echo site_url( '/kanbanblog/tutorials'); ?>">Tutorials</a></li>
		<li><a href="<?php echo site_url( '/kanbanblog/about'); ?>">About</a></li>
		<li class="last"><a href="<?php echo site_url( '/kanban/login'); ?>">Login / Register</a></li>
	</ul>
</div>

<div class="banner">
	<div class="logo"></div>
	<div class="projecttitle">The Kanban Blog</div>
	<div class="subtitle">bla bla bla...</div>
</div>

<div id="errordiv"></div>

<div id="wrapper">

<div id="settingsdiv">

<span>
<h2>ABOUT!</h2>
<i>My Task Board</i> is developed by Tobias Eriksson at TSOFT.SE<br>
If you have any questions or suggestions on how to improve the tool, send me an e-mail; tobias AT tsoft.se	<br>
Currently it is running on a HTTPS accessed server at <a href="https://kanban.softhouse.se">https://kanban.softhouse.se</a>, please feel free to take a look and use it for free !
</span> 
<br><br>

<br>


</div>
</div>

</body>
</html>


