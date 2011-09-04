<html>
<head>
<title>Json Test</title>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>

<script>
$(document).ready(function(){

$("#result").html("hmmm!");
 
alert("hej");

$.get('kanban/get', function(data) {
  $('#result').html("jaa!");
  alert('Load was performed.');
});


});
</script>
</head>
<body>
<h2>JSON Example</h2>
<div id="result"></div>
</body>
</html>

