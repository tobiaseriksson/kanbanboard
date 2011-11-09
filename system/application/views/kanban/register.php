<html>
<head>
<title>Welcome To My Task Board</title>


</head>
<body>
<table width="100%" height="100%" valign="middle"> 
<tr> 
<td> 
<center> 
<img src="/assets/images/boardfirstpage.png">
<div>
	<h3>Register</h3>
	<div class="errortext"><?php echo $errormessage."<br>"; ?></div>
	<form name="register" action="<?php echo site_url( '/kanban/register');?>" method="post">
		 
		<table>
			<tr><td>login</td><td><input type="text" name="login" size="15" value="<?php echo $login; ?>"></td></tr>
			<tr><td>password</td><td><input type="password" name="password" size="15" value="<?php echo $password; ?>"></td></tr>
			<tr><td>repeat password</td><td><input type="password" name="password2" size="15" value="<?php echo $password2; ?>"></td></tr>
			<tr><td></td><td align="right"><input type="submit" name="registerbutton" value="register"></td><td></td></tr>
		</table>
	</form>

</div>

</center> 

</td> 
</tr> 
</table> 

</body>
</html>




