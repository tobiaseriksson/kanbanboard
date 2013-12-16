<script type="text/javascript">
$(function() {
	
	$('#taskcommentform').submit(function() {
		var dataString = $("#taskcommentform").serialize();
			$.ajax({  
			  type: "POST",  
			  url: "/kanban/addtaskcomment",  
			  data: dataString,  
			  success: function(data) {  				     
					loadTaskComments( $("#taskcommentform input[name=taskid]").val() );
					$("#taskcommentform").reset();
			  },
			  error: function(x,e) {  
				    $("#errordiv").html("failed with; "+x.status+", e="+e+", response="+x.responseText);
				  }
		});
	  return false;
	});		

});
</script>


<form id="taskcommentform" action="" method="post">
	<input type="hidden" name="projectid" id="projectid" value="<?php echo $projectid; ?>" />
	<input type="hidden" name="taskid" id="taskid" value="<?php echo $taskid; ?>" />
	<div class="taskcommentform">
		Name : <input type="text" name="who" id="who" value="name" size="30"/><br/>
		Comment :<br/>
		<textarea name="comment" id="comment" cols="80" rows="7">Some comment...</textarea>
		<br/>
		<input type="submit" name="Submit" value="submit" />
	</div>
</form>

<?php						
foreach ($taskcomments as $comment) {					
?>

	<div class="taskcomment" id="c1">
		<p class="info">Comment by <?php echo htmlentities($comment['who']); ?> | <time datetime="<?php echo $comment['timestamp']; ?>" class="date"><?php echo $comment['timestamp']; ?></time></p>
		<div class="comment">
			<?php echo nl2br( htmlentities($comment['comment']) ); ?>
		</div>
	</div>


<?php
}
?>
