<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>The '<?php echo $projectname; ?>' Kanban Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.8.1.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="/assets/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.8.1.custom.min.js"></script>
	<script src="/assets/js/raphael-min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="/assets/js/g.raphael-min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="/assets/js/g.line-min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="/assets/js/g.bar.js" type="text/javascript" charset="utf-8"></script> 

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
			$(function() {
                var r = Raphael("diagram");
                r.g.txtattr.font = "12px 'Fontin Sans', Fontin-Sans, sans-serif";

		<?php
			$str = "";
			for( $i = 0; $i < count( $days ); $i++ ) {
				$str = $str.$days[ $i ].",";				
			}
			$daysstring=substr($str,0,-1);
		
			$str = "";
			for( $i = 0; $i < count( $diagrambaseline ); $i++ ) {
				$str = $str.$diagrambaseline[ $i ].",";				
			}
			$expected=substr($str,0,-1);
			
			$str = "";
			for( $i = 0; $i < count( $diagramactual ); $i++ ) {
				$str = $str.$diagramactual[ $i ].",";				
			}
			$actual=substr($str,0,-1);
		?>          
				var days = [ <?php echo $daysstring ?> ];
				var expected = [ <?php echo $expected ?> ];
				var actual = [ <?php echo $actual ?> ];
		      	if( actual.length <= 1 ) {
					actual = [];
				}
				if( expected.length <= 1 ) {
					expected = [];
				}
                r.g.text(160, 10, "Burn Down Chart for <?php echo $sprintname; ?>");
 				if( actual.length <= 0 ) {
	                r.g.linechart(30, 20, 450, 300, days, expected, { axis: "0 0 1 1"});						
				} else {
				    r.g.linechart(30, 20, 450, 300, days, [actual,expected], { axis: "0 0 1 1"});						
				}
			});
		</script>


<script type="text/javascript">

		<?php
			$str = "";
			$str2 = "";
			for( $i = 0; $i < count( $diagraminflow ); $i++ ) {
				$str = $str.$days[ $i ].",";				
				$str2 = $str2."' ',";				
			}
			$str=substr($str,0,-1);
			$xaxis = $str;
			$str2=substr($str2,0,-1);
			$xaxis2 = $str2;
			
			$str = "";
			for( $i = 0; $i < count( $diagraminflow ); $i++ ) {
				$str = $str.$diagraminflow[ $i ].",";				
			}
			$str=substr($str,0,-1);
			$inflow=$str;
			
			$str = "";
			for( $i = 0; $i < count( $diagramoutflow ); $i++ ) {
				$str = $str.$diagramoutflow[ $i ].",";				
			}
			$str=substr($str,0,-1);
			$outflow = $str;
		?>                

			$(function() {
				var xaxis = [[<?php echo $xaxis; ?>],[<?php echo $xaxis2; ?>]];
				var yaxis = [[<?php echo $outflow; ?>],[<?php echo $inflow; ?>]];

				var r = Raphael("diagram2"),
                fin = function () {
                    this.flag = r.g.popup(this.bar.x, this.bar.y, this.bar.value || "0").insertBefore(this);
                },
                fout = function () {
                    this.flag.animate({opacity: 0}, 300, function () {this.remove();});
                },
                fin2 = function () {
                    var y = [], res = [];
                    for (var i = this.bars.length; i--;) {
                        y.push(this.bars[i].y);
                        res.push(this.bars[i].value || "0");
                    }
                    this.flag = r.g.popup(this.bars[0].x, Math.min.apply(Math, y), res.join(", ")).insertBefore(this);
                };
                
            r.g.txtattr.font = "12px 'Fontin Sans', Fontin-Sans, sans-serif";
            
            r.g.text(160, 10, "Inflow / Outflow");
            
            var barchart = r.g.barchart(10, 10, 450, 300, yaxis);
            barchart.hover(fin, fout);
            barchart.label(xaxis,true);

			});

		</script>
		
		
<script type="text/javascript">

		<?php
			$str = "";
			$str2 = "";
			for( $i = 0; $i < count( $diagramoutflowperweek ); $i++ ) {
				$str = $str."'".$diagramoutflowperweek[ $i ]['yearweek']."',";				
				$str2 = $str2.$diagramoutflowperweek[ $i ]['total'].",";				
			}
			$str=substr($str,0,-1);
			$xaxis = $str;
			$str2=substr($str2,0,-1);
			$yaxis = $str2;
		?>                

			$(function() {
				var xaxis = [<?php echo $xaxis; ?>];
                var r = Raphael("diagram3"),
					data1 = [<?php echo $yaxis; ?>],
					fin = function () {
                        this.flag = r.g.popup(this.bar.x, this.bar.y, this.bar.value || "0").insertBefore(this);
                    },
                    fout = function () {
                        this.flag.animate({opacity: 0}, 300, function () {this.remove();});
                    };
					
                r.g.txtattr.font = "12px 'Fontin Sans', Fontin-Sans, sans-serif";

                r.g.text(160, 10, "Outflow per week for <?php echo $projectname; ?>");
				var barchart = r.g.barchart(30, 20, 450, 300, data1);
				barchart.label(xaxis,true);
				barchart.hover(fin, fout); 
			});

		</script>
		
</head>
<body bgcolor=white>
<div id="dock">
	<ul>
		<li><a href="<?php echo site_url( '/kanban/project/'.$projectid.'/'.$sprintid ); ?>">Board</a></li>	
		<li><a href="<?php echo site_url( '/kanban/settings/'.$projectid.'/'.$sprintid ); ?>">Settings</a></li>	
		<li><a href="<?php echo site_url( '/kanban/selectproject'); ?>">Projects</a></li>
		<li><a href="<?php echo site_url( '/kanban/about/'.$projectid.'/'.$sprintid ); ?>">About</a></li>
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

<h2>Status</h2>
<center>
<span>
<h3>Currently <?php echo $daysleft; ?> days left<br><br>

Sprint duration is <?php echo $totaldays; ?> days<br><br>

Current velocity is <?php echo $velocity; ?> points / day 
</h3>
</span> 
<br><br>
</center>
<br>

<div id="diagram" class="diagram"> 
</div>
<br>

<div id="diagram2" class="diagram"> 
</div>
<br>

<div id="diagram3" class="diagram"> 
</div>
<br>

<h2>Task List</h2>
<table>
<tr><td>Sprint</td><td>Started</td><td>Finished</td><td>Priority</td><td>Estimation</td><td>Task</td></tr>
<?php
	$name="";
	foreach ($legend as $row)
	{							
		if( $name != $row['sprintname'] ) {
			echo "<tr><td><b>".$row['sprintname']."</b></td>";
			$name = $row['sprintname'];
		} else {
			echo "<tr><td></td>";
		}
		if( $row['startdate'] == '0000-00-00' ) echo "<td></td>";
		else echo "<td>".$row['startdate']." (".$row['startweeknumber'].")</td>";
		if( $row['enddate'] == '0000-00-00' ) echo "<td></td>";
		else echo "<td>".$row['enddate']." (".$row['finishedweeknumber'].")</td>";
		echo "<td>".$row['priority']."</td>";
		echo "<td>".$row['estimation']."</td>";
		echo "<td>".$row['heading']."</td>";
		echo "</tr>";
	}
?>
</table>

</div>
</div>

</body>
</html>


