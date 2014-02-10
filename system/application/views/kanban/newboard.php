<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>The '<?php echo $projectname; ?>' Board</title>	
	<link type="text/css" href="/assets/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	
	<link type="text/css" href="/assets/css/postits.css" rel="stylesheet" />	

	<script type="text/javascript" src="/assets/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.ui.touch-punch.js"></script>
  <script src="/assets/js/jquery.cookie.js"></script>
  <script src="/assets/js/underscore-min.js"></script>
  <script src="/assets/js/backbone-min.js"></script>
  <script type="text/javascript" src="/assets/js/raphael-min.js"></script>
  <script type="text/javascript" src="/assets/js/bind.js"></script>  <!--  this is included to overcome a limitation in Safaris mobile javascript engine that does not have the bind-funtion implemented  -->
  <script type="text/javascript" src="/assets/js/timeline.js"></script> 
    
	<script type="text/javascript">
  window.onload = function() { 
  <?php
      $i=0;
      $headlines="";
      foreach ($timelineitems as $item) { 
        $headlines=$headlines."['".$item['date']."','".$item['headline']."'],";
      }
      $headlines = rtrim( $headlines, ',' );
    ?>
      var headlines = [ <?php echo $headlines; ?> ];
      var timeline = new TimeLine('timelinecanvas',1000,100);
      timeline.setHeadlines( headlines );
      timeline.setEditIconURL( '<?php echo site_url( '/kanban/timeline/'.$projectid.'/'.$sprintid ); ?>' );
      timeline.create();
  }   
  </script>    

  <style type="text/css">  
  #timelinecanvas {  
      width: 1000px;  
      height: 100px;
      border: 0px solid #aaa;  
  }  
</style> 


	<link type="text/css" href="/assets/css/kanban.css" rel="stylesheet" />	

	<style type="text/css">

    /* A class used by the jQuery UI CSS framework for their dialogs. */
    .ui-front {
      z-index:1000000 !important; /* The default is 100. !important overrides the default. */
    }

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
<style>
  .draggableTask { 
    width: 120px; 
    height: 120px; 
    overflow: hidden;
    padding: 2px;
    position:absolute;
  }
 
  p { clear:both; margin:0; padding:0; }

.body {
    background-color: #F0F0F0;  
}

.board {
    border-width: 1px;
    border-style: solid;
    border-color: #000000;
    background-color: #FEFEFE;
    width: 1200px; 
    height: 1200px; 
    float: left;
    padding: 5px;
    position:absolute;
    overflow: hidden;
}

.group {
    border-width: 1px;
    border-style: solid;
    border-color: #000000;
    background-color: #EEEEEE;
    width: 130px; 
    height: 15px; 
    float: left;
    padding: 5px;
    position:absolute;
    top:10px;
    text-align: center;
    vertical-align: middle;
    overflow: hidden;
}

.owner-area {
    z-index: 0;
    position:absolute;
    border-width: 1px;
    border-style: solid;
    border-color: #000000;
    background-color: #FAFAFA;
}

.owner-area-inside {
    height: 100px;
    width: 20px;
    position: absolute;
    overflow: hidden;
    margin: -50px 0 0 0;
    top: 50%;
}

.owner-area-rotated-title {
    width: 150px;
    height: 20px;
    position:absolute;
    bottom:0px;
    overflow: hidden;
    color: black;
    -webkit-transform-origin: left;
    -webkit-transform: translate(50px, 10px) rotate(-90deg) translate(0px, -40px);
    
    -moz-transform:-origin: left;
    -moz-transform: translate(50px, 10px) rotate(-90deg) translate(0px, -40px);

    -o-transform-origin: left; 
    -o-transform: translate(50px, 10px) rotate(-90deg) translate(0px, -40px);
    
    -ms-transform-origin: left; 
    -ms-transform: translate(50px, 10px) rotate(-90deg) translate(0px, -40px);

    transform-origin: left;
    transform: translate(50px, 10px) rotate(-90deg) translate(0px, -40px);
}


.shadow {
  -moz-box-shadow: 5px 5px 5px #888;
  -webkit-box-shadow: 5px 5px 5px #888;
  box-shadow: 5px 5px 5px #888;
  }


.blue-card {
  /* fallback */
  background-color: #A6B3ED;
  background-image: url(images/radial_bg.png);
  background-position: center center;
  background-repeat: no-repeat;

  /* Safari 4-5, Chrome 1-9 */
  /* Can't specify a percentage size? Laaaaaame. */
  background: -webkit-gradient(radial, center center, 0, center center, 460, from(#F1F2DA), to(#A6B3ED));

  /* Safari 5.1+, Chrome 10+ */
  background: -webkit-radial-gradient(circle, #F1F2DA, #A6B3ED);

  /* Firefox 3.6+ */
  background: -moz-radial-gradient(circle, #F1F2DA, #A6B3ED);

  /* IE 10 */
  background: -ms-radial-gradient(circle, #F1F2DA, #A6B3ED);

  /* Opera couldn't do radial gradients, then at some point they started supporting the -webkit- syntax, how it kinda does but it's kinda broken (doesn't do sizing) */
}

.green-card {
  /* fallback */
  background-color: #B8F0A5;
  background-image: url(images/radial_bg.png);
  background-position: center center;
  background-repeat: no-repeat;

  /* Safari 4-5, Chrome 1-9 */
  /* Can't specify a percentage size? Laaaaaame. */
  background: -webkit-gradient(radial, center center, 0, center center, 460, from(#F1F2DA), to(#B8F0A5));

  /* Safari 5.1+, Chrome 10+ */
  background: -webkit-radial-gradient(circle, #F1F2DA, #B8F0A5);

  /* Firefox 3.6+ */
  background: -moz-radial-gradient(circle, #F1F2DA, #B8F0A5);

  /* IE 10 */
  background: -ms-radial-gradient(circle, #F1F2DA, #B8F0A5);

  /* Opera couldn't do radial gradients, then at some point they started supporting the -webkit- syntax, how it kinda does but it's kinda broken (doesn't do sizing) */
}


.yellow-card {
  /* fallback */
  background-color: #EBF090;
  background-image: url(images/radial_bg.png);
  background-position: center center;
  background-repeat: no-repeat;

  /* Safari 4-5, Chrome 1-9 */
  /* Can't specify a percentage size? Laaaaaame. */
  background: -webkit-gradient(radial, center center, 0, center center, 460, from(#F1F2DA), to(#EBF090));

  /* Safari 5.1+, Chrome 10+ */
  background: -webkit-radial-gradient(circle, #F1F2DA, #EBF090);

  /* Firefox 3.6+ */
  background: -moz-radial-gradient(circle, #F1F2DA, #EBF090);

  /* IE 10 */
  background: -ms-radial-gradient(circle, #F1F2DA, #EBF090);

  /* Opera couldn't do radial gradients, then at some point they started supporting the -webkit- syntax, how it kinda does but it's kinda broken (doesn't do sizing) */
}


.pink-card {
  /* fallback */
  background-color: #FF66CC;
  background-image: url(images/radial_bg.png);
  background-position: center center;
  background-repeat: no-repeat;

  /* Safari 4-5, Chrome 1-9 */
  /* Can't specify a percentage size? Laaaaaame. */
  background: -webkit-gradient(radial, center center, 0, center center, 460, from(#F1F2DA), to(#FF66CC));

  /* Safari 5.1+, Chrome 10+ */
  background: -webkit-radial-gradient(circle, #F1F2DA, #FF66CC);

  /* Firefox 3.6+ */
  background: -moz-radial-gradient(circle, #F1F2DA, #FF66CC);

  /* IE 10 */
  background: -ms-radial-gradient(circle, #F1F2DA, #FF66CC);

  /* Opera couldn't do radial gradients, then at some point they started supporting the -webkit- syntax, how it kinda does but it's kinda broken (doesn't do sizing) */
}

.red-card {
  /* fallback */
  background-color: #FF3300;
  background-image: url(images/radial_bg.png);
  background-position: center center;
  background-repeat: no-repeat;

  /* Safari 4-5, Chrome 1-9 */
  /* Can't specify a percentage size? Laaaaaame. */
  background: -webkit-gradient(radial, center center, 0, center center, 460, from(#F1F2DA), to(#FF3300));

  /* Safari 5.1+, Chrome 10+ */
  background: -webkit-radial-gradient(circle, #F1F2DA, #FF3300);

  /* Firefox 3.6+ */
  background: -moz-radial-gradient(circle, #F1F2DA, #FF3300);

  /* IE 10 */
  background: -ms-radial-gradient(circle, #F1F2DA, #FF3300);

  /* Opera couldn't do radial gradients, then at some point they started supporting the -webkit- syntax, how it kinda does but it's kinda broken (doesn't do sizing) */
}


.orange-card {
  /* fallback */
  background-color: #FF9933;
  background-image: url(images/radial_bg.png);
  background-position: center center;
  background-repeat: no-repeat;

  /* Safari 4-5, Chrome 1-9 */
  /* Can't specify a percentage size? Laaaaaame. */
  background: -webkit-gradient(radial, center center, 0, center center, 460, from(#F1F2DA), to(#FF9933));

  /* Safari 5.1+, Chrome 10+ */
  background: -webkit-radial-gradient(circle, #F1F2DA, #FF9933);

  /* Firefox 3.6+ */
  background: -moz-radial-gradient(circle, #F1F2DA, #FF9933);

  /* IE 10 */
  background: -ms-radial-gradient(circle, #F1F2DA, #FF9933);

  /* Opera couldn't do radial gradients, then at some point they started supporting the -webkit- syntax, how it kinda does but it's kinda broken (doesn't do sizing) */
}

.grey-card {
  /* fallback */
  background-color: #BDBDC2;
  background-image: url(images/radial_bg.png);
  background-position: center center;
  background-repeat: no-repeat;

  /* Safari 4-5, Chrome 1-9 */
  /* Can't specify a percentage size? Laaaaaame. */
  background: -webkit-gradient(radial, center center, 0, center center, 460, from(#F1F2DA), to(#BDBDC2));

  /* Safari 5.1+, Chrome 10+ */
  background: -webkit-radial-gradient(circle, #F1F2DA, #BDBDC2);

  /* Firefox 3.6+ */
  background: -moz-radial-gradient(circle, #F1F2DA, #BDBDC2);

  /* IE 10 */
  background: -ms-radial-gradient(circle, #F1F2DA, #BDBDC2);

  /* Opera couldn't do radial gradients, then at some point they started supporting the -webkit- syntax, how it kinda does but it's kinda broken (doesn't do sizing) */
}


.purple-card {
  /* fallback */
  background-color: #CC33FF;
  background-image: url(images/radial_bg.png);
  background-position: center center;
  background-repeat: no-repeat;

  /* Safari 4-5, Chrome 1-9 */
  /* Can't specify a percentage size? Laaaaaame. */
  background: -webkit-gradient(radial, center center, 0, center center, 460, from(#F1F2DA), to(#CC33FF));

  /* Safari 5.1+, Chrome 10+ */
  background: -webkit-radial-gradient(circle, #F1F2DA, #CC33FF);

  /* Firefox 3.6+ */
  background: -moz-radial-gradient(circle, #F1F2DA, #CC33FF);

  /* IE 10 */
  background: -ms-radial-gradient(circle, #F1F2DA, #CC33FF);

  /* Opera couldn't do radial gradients, then at some point they started supporting the -webkit- syntax, how it kinda does but it's kinda broken (doesn't do sizing) */
}

  </style>
  <script>

  
  
  var groupBy = false;
  var dense = true;
  var DENSEFRACTION = 0.8;
  var draggingOnGoing = false;
  var groups = new Array();
  var defaultTaskWidth = 120;
  var defaultTaskHeight = 120;
  var defaultSpace = 20;
  var taskCountForXXL = 5;
  var cheatingMaxY = 0;

  function getColor(index) {
    var colors=[];
    colors[0]='yellow';
    colors[1]='yellow';
    colors[2]='green';
    colors[3]='red';
    colors[4]='blue';
    colors[5]='pink';
    colors[6]='purple';
    colors[7]='orange';
    colors[8]='grey';
    if( index < 0 || index > 8 ) index = 0;
    return colors[index];
  }

  function getOwner(index){
    var owners=[];
    owners[0]='N/A';
<?php           
              foreach ($resources as $rs) {   
              
                print 'owners['.$rs['id'].']="'.$rs['name'].'";';
                            
              }           
              ?>
    return owners[index];
  }

  function rotate(object, degrees) {
      object.css({
    '-webkit-transform' : 'rotate('+degrees+'deg)',
       '-moz-transform' : 'rotate('+degrees+'deg)',  
        '-ms-transform' : 'rotate('+degrees+'deg)',  
         '-o-transform' : 'rotate('+degrees+'deg)',  
            'transform' : 'rotate('+degrees+'deg)',  
                 'zoom' : 1
      });
  }


  var Owner = Backbone.Model.extend({
    defaults: {
      id: 0,
      owner: 'Name',
      maxCountInAnyGroup: 0,
      maxTaskHeight : 0,
      arrayOfGroupTaskCount: new Array(),
      arrayOfXXLPerGroup: new Array()
    }
  });

  var OwnerList = Backbone.Collection.extend({
    model: Owner
  });

  var Task = Backbone.Model.extend({
    defaults: {
      heading: 'Title Not Set',
      description: '',
      priority: 0,
      estimation: 0,
      todays_estimation: 0, 
      todays_time_reporting: 0, 
      age: 0,
      comments: 0,
      colortag: 1,
      color: 'yellow',
      preferredZIndex: 0,
      group: 0,
      ownerid: 0,
      owner: 'N/A',
      sprintid: 0,
      workpackage_id: 0,
      projectid: <?php echo $projectid; ?>
    },
    urlRoot: '/kanbanrestapi/task',
    updateEditDialog: function() {
      console.log('updating...'+this.get('heading') );
      var dialog = $("#dialog-edit-task");
      $("#heading",dialog).val( this.get('heading') );
      $("#description",dialog).val( this.get('description') );
      $("#priority",dialog).val( this.get('priority') );
      $("#estimation",dialog).val( this.get('estimation') );
      $("#todays_estimation",dialog).val( this.get('todays_estimation') );
      $("#todays_time_reporting",dialog).val( this.get('todays_time_reporting') );
      $("#taskid",dialog).val( this.get('id') );
      $("#colortag",dialog).val( this.get('colortag') );  
      $("#newsprintid",dialog).val( this.get('sprintid') );   
      $("#workpackage_id",dialog).val( this.get('workpackage_id') );    
      $("#ownerid",dialog).val( this.get('ownerid') );  
    },
    updateTaskHTML : function() {
      var obj = $("#draggableTask"+this.get('id'));
      // console.log('found '+$(obj).attr('id'));
      // console.log('found '+$(obj).attr('class'));
      $(obj).attr("class","ui-widget-content draggableTask shadow ui-draggable "+getColor(this.get('colortag'))+"-card");
      // console.log('found '+$(obj).attr('class'));
      $("#heading",obj).html(this.get('heading'));
      $("#description",obj).html(this.get('description'));
      $("#priority",obj).html(this.get('priority'));
      $("#comments",obj).html(this.get('comments'));
      $("#age",obj).html(this.get('age'));
      $("#estimation",obj).html(this.get('estimation'));
    },
    validate : function(attributes) {
      if( parseInt( attributes.projectid ) != <?php echo $projectid; ?> ) return "Oops! the project ID is invalid ("+attributes.projectid+" expected <?php echo $projectid; ?>)";
      if( parseInt( attributes.sprintid ) <= 0 ) return "Oops! the Sprint ID is invalid ("+attributes.sprintid+" expected <?php echo $sprintid; ?>)";
      if( parseInt( attributes.group ) <= 0 ) return "Oops! the Sprint ID is invalid ("+attributes.sprintid+")";
    }
  });

  var TaskList = Backbone.Collection.extend({
    model: Task,
    comparator: 'priority'
  });

  var Group = Backbone.Model.extend({
    defaults: {
      id: 0,
      title: 'Group Name',
      order: 0
    }
  });

  var GroupList = Backbone.Collection.extend({
    model: Group,
    comparator: 'order'
  });

  var taskList = new TaskList(
    [
 <?php
 	$colors=array();
 	$colors[0]='yellow';
 	$colors[1]='yellow';
 	$colors[2]='green';
 	$colors[3]='red';
 	$colors[4]='blue';
 	$colors[5]='pink';
 	$colors[6]='purple';
 	$colors[7]='orange';
 	$colors[8]='grey';
    $html="";
    foreach ($tasks as $task)
	{		
		## $sql='SELECT k.id as taskid,heading,description,group_id,name as groupname,priority,estimation,colortag,added,enddate,ownerid FROM kanban_item k,kanban_group g WHERE group_id = g.id AND k.sprint_id = ? ORDER BY displayorder,priority DESC,added ASC,heading DESC';	
		if( $task['colortag'] <= 0 && $task['colortag'] >= 9 ) $color = 'yellow';
		else $color = $colors[ $task['colortag'] ];
    $description = str_replace(array("\r\n", "\r", "\n"), '\n', $task['description']);
    // trim(preg_replace('/\s+/', ' ', nl2br($task['description'])));
		$html=$html."new Task({id:".$task['taskid'].",heading:'".$task['heading']."',description:'".$description."',priority:".$task['priority'].",color:'".$color."',colortag:'".$task['colortag']."',group:".$task['group_id'].",ownerid:".$task['owner_id'].",owner: getOwner(".$task['owner_id']."),estimation:".$task['estimation'].",added:'".$task['added']."',enddate:'".$task['enddate']."',comments:".$task['comments'].",age:".$task['age'].",workpackage_id:".$task['workpackage_id'].",todays_estimation:".$task['latest_estimation'].",todays_time_reporting:".$task['todays_time_reporting'].",sprintid:".$task['sprint_id']."}),\n";
	}
	$html=rtrim($html, ",\n");
	echo $html;
?>
    ]
  );

  var groupList = new GroupList(
    [
<?php
    $html="";
    foreach ($groups as $group)
	{		
		$html=$html."new Group({id:".$group['id'].", title:'".$group['name']."',order:".$group['displayorder']."}),\n";
	}
	$html=rtrim($html, ",\n");
	echo $html;
?>
    ]
  );

$(function() {
  function taskBelongsToWhichGroup( taskElement ) {
    var taskXMiddlePosition = taskElement.position().left+taskElement.width()/2;
    var theGroup = null; 
    groupList.each( function( group ){ 
      var groupId = group.get('id');
      var element = $('#group'+groupId);
      var groupXLeftPosition = element.position().left;
      var groupXRightPosition =  groupXLeftPosition+element.outerWidth()+defaultSpace;
      if( taskXMiddlePosition >= groupXLeftPosition && taskXMiddlePosition <= groupXRightPosition ) {
        theGroup = group;
        console.log(group.get('title')+'-Group Selected')
      }
    });
    if( theGroup == null ) {
      console.log('Outside any group');
      var task = taskList.get({id:taskElement.attr('taskid')});
      if( task != null ) {
        var groupId = task.get('group');
        theGroup = groupList.get({id:groupId});         
      }
      if( theGroup == null ) {
        console.log('Oops! seems like that group ('+groupId+') actually does not exists...' );
      }
    }
    return theGroup;
  }
  
  function renderGroupsAndTheirTasks() {
    var groupTemplate = _.template( $('#group-template').html() );
    var taskTemplate = _.template( $('#task-template').html() );
    groupList.each( function( group ){ 
      $('#kanbanboard').append( groupTemplate( group.toJSON() ) );
    });
    taskList.each( function( task ) {
        $('#kanbanboard').append( taskTemplate( task.toJSON() ) );
    });
  }

  function renderOwnerArea( owner ) {
    if( $('#owner-area-'+owner.get('id')).length > 0 ) return;
    var ownerAreaTemplate = _.template( $('#owner-area-template').html() )
    $('#kanbanboard').append( ownerAreaTemplate( owner.toJSON() ) );
  }

  function layoutOwnerArea( owner, top, left, width, height ) {
    $('#owner-area-'+owner.get('id')).css({ "top": top, "left": left, "width": width, "height": height });
  }

function layoutGroupsAndTheirTasks() {
    var xPosition = defaultSpace*2;
    var yPosition = defaultSpace;
    var ownerList = findTasksPerOwnerPerGroup();
    cheatingMaxY = 0;
    if( groupBy == true ) {
      var denseFraction=DENSEFRACTION;
      if( dense ) denseFraction = 4;
      var element = $('.group').first();
      var top = yPosition + element.outerHeight() + defaultSpace;
      var left = 10;
      var width = $('#kanbanboard').width() - 10;
      ownerList.each( function( owner ){
        var maxTaskHeight = owner.get('maxTaskHeight');
        var element = $('.draggableTask').first();
        if( element == null ) height = defaultTaskHeight;
        else {
          height = maxTaskHeight * (element.outerHeight() / denseFraction);
          if( dense == true ) height = height + element.outerHeight();
        }
        renderOwnerArea(owner);
        layoutOwnerArea(owner,top-10,left,width,height+10);
        top = top + height + defaultSpace;
      });
    }

    groupList.each( function( group ){ 
      var groupId = group.get('id');
      var element = $('#group'+groupId);
      var xxl = false;
      if( groupBy == true ) {
        var owner = ownerList.first();
        var arrayOfXXLPerGroup = owner.get('arrayOfXXLPerGroup');
        xxl = arrayOfXXLPerGroup[ groupId ];
      } else {
        xxl = taskList.where({group:groupId}).length >= taskCountForXXL ? true : false;
      }

      var width = defaultTaskWidth;
      element.css( { top: yPosition, left: xPosition });
      if( xxl == true ){
        width = defaultTaskWidth*2+defaultSpace;
      }
      element.css( { width: width } );
      var taskStartTopPosition = yPosition + element.outerHeight() + defaultSpace;
      layoutTasks( groupId, xPosition, taskStartTopPosition, xxl, ownerList );
      xPosition = xPosition + width + defaultSpace*2;
    });
    var boardWidth = $('#kanbanboard').position().left+$('#kanbanboard').width();
    if( xPosition != boardWidth ) resizeBoard();
  }

  function sortArrayOfTasksBasedOnPrio( x, y ) {
    var xPrio = x.get('priority');
    var yPrio = y.get('priority');
    if( xPrio > yPrio ) return -1;
    if( xPrio < yPrio ) return 1;
    return 0;
  }

  function layoutTasksSub(tasks,xPositionForGroup,yPosition,xxl){
    if( dense ) denseFraction = 4;
    else denseFraction=DENSEFRACTION;
    var index = 100;
    var toggle = 1;
    _.each( tasks, function( task ) {
      var xPosition = xPositionForGroup; 
      var taskId = task.get('id');
      var element = $('#draggableTask'+taskId);
      if( xxl == true && (index % 2) != 0 ) {
        xPosition = xPosition + defaultSpace + element.outerWidth();
      }
      element.animate( { top: yPosition, left: xPosition }, 500, "easeOutExpo" )
      element.css({ "z-index": index });
      element.attr("preferredZIndex", index );
      if( xxl == true ) {
        if( index % 2 != 0  ) {
          toggle = toggle * -1;
          yPosition = yPosition + (element.height() / denseFraction);
        }
      } else {
        toggle = toggle * -1;
        yPosition = yPosition + (element.height() / denseFraction);
      }
      if( (yPosition+ element.outerHeight()) > cheatingMaxY ) cheatingMaxY = yPosition + element.outerHeight();
      // rotate( element, toggle*1 );
      index++;
    });
    return yPosition;
  }

  function findTasksPerOwnerPerGroup() {
    var arrayOfXXLPerGroup = [];
    groupList.each( function( group ){ 
      var groupId = group.get('id');
      arrayOfXXLPerGroup[ groupId ] = false;
    });
    var ownerList = new OwnerList();
    var uniqueOwnerNames = _.uniq( taskList.pluck('owner') );
    var index=0;
    _.each( uniqueOwnerNames, function( ownerName ) { 
      var arrayOfGroupTaskCount = [];
      var max = 0;
      // console.log('name: '+ownerName);
      groupList.each( function( group ){ 
        var groupId = group.get('id');
        var tasks = taskList.where({owner: ownerName, group: groupId });
        arrayOfGroupTaskCount[ groupId ] = tasks.length;
        if( tasks.length >= taskCountForXXL ) arrayOfXXLPerGroup[ groupId ] = true;
        if( tasks.length > max ) max = tasks.length;
      });
      var tmp = new Owner({id:index,owner:ownerName,arrayOfGroupTaskCount:arrayOfGroupTaskCount,maxCountInAnyGroup:max});
      ownerList.add( tmp );
      index=index+1;
    });
    ownerList.each( function( owner ){
      owner.set({arrayOfXXLPerGroup:arrayOfXXLPerGroup})
      var maxTaskHeight = 0;
      var arrayOfGroupTaskCount = owner.get('arrayOfGroupTaskCount');
      groupList.each( function(group){
        var groupId = group.get('id');
        var groupTaskCount = arrayOfGroupTaskCount[ groupId ];
        var taskHeight = Math.ceil( groupTaskCount / ( arrayOfXXLPerGroup[ groupId ] == true ? 2 : 1) );
        if( taskHeight > maxTaskHeight ) maxTaskHeight = taskHeight;
      });
      owner.set({maxTaskHeight:maxTaskHeight})
    });
    return ownerList;
  }

  function layoutTasks( groupId, xPositionForGroup, yPosition, xxl, ownerList ) {  
    if( groupBy == true ) {
      ownerList.each( function( owner ){
        var yPosition = $('#owner-area-'+owner.get('id')).position().top + 10;
        var tasks = taskList.where( {group:groupId,owner:owner.get('owner')} );
        var sortedTasks = tasks.sort( sortArrayOfTasksBasedOnPrio );
        var taskLastYPosition = layoutTasksSub(sortedTasks,xPositionForGroup,yPosition,xxl);
      });
    }
    else {
      var tasks = taskList.where( {group:groupId} );
      var sortedTasks = tasks.sort( sortArrayOfTasksBasedOnPrio );
      // _.each( sortedTasks, function( t ) { console.log( t.get('heading')+' : '+t.get('priority') ); } );
      layoutTasksSub(sortedTasks,xPositionForGroup,yPosition,xxl);
    }
  }

  function makeTaskDraggable(taskId) {
    var taskSelection=".draggableTask";
    if( taskId!=0 ) taskSelection="#draggableTask"+taskId;
    $( taskSelection ).draggable({
        helper: "original",
        containment: 'parent',
        drag: function( event, ui ) { 
          draggingOnGoing = true;
        },
        stop: function( event, ui ) { 
          var taskId = $(this).attr('taskid');
          var newGroup = taskBelongsToWhichGroup( $(this) );
          if( newGroup == null ) {
            $(this).remove();
            return;
          }
          var task = taskList.get(taskId);
          if( task != null ) {
            task.save({group:newGroup.get('id')},{
              wait: true,
              success:function(model,response) 
              {
                console.log('Model stored on server!');
                task.updateTaskHTML();
                layoutGroupsAndTheirTasks();
              },
              error:function(model,response) 
              {
                console.log('FAILED to Move Task, server; ');
                var reasonForFailure = '';
                for( var propName in response ) {
                  reasonForFailure = reasonForFailure+', ' + propName+' = '+response[propName];
                }
                $("#error-message").html("Failed to update; "+reasonForFailure);
                $('#dialog-error-message').dialog('open');
              }
            });
          }
          draggingOnGoing = false;
        } 
    });

    $( taskSelection ).mouseenter( function() {
        if( draggingOnGoing ) return;
        $(this).css({ "z-index": 1000 });
    });
    
    $( taskSelection ).mouseleave( function() {
        if( draggingOnGoing ) return;
        $(this).css({ "z-index": $(this).attr("preferredZIndex") });
    });
  }

  function resizeBoard() {
    var xMax = 0;
    var yMax = 0;
    $(".group").each( function(index)
      { 
        var xPos = $(this).position().left + $(this).outerWidth(); 
        if( xPos > xMax) xMax = xPos; 
    });
    /*
    $(".draggableTask").each( function(index)
      { 
        // var yPos = $(this).position().top + $(this).outerHeight(); 
        var yPos = parseInt($(this).css('top')) + $(this).outerHeight(); 
        console.log($(this).attr('id')+' : yPos '+yPos);
        if( yPos > yMax ) yMax = yPos;
    });
*/
    $('#kanbanboard').css( { width: xMax+defaultSpace, height: cheatingMaxY+defaultSpace } );
    if( groupBy == true ) {
      $('.owner-area').css({ width: xMax+defaultSpace-10 });
    }
  }

  $("#dense").click( function() {
    console.log("clicked dense button");
    if( dense ) dense = false;
    else dense = true;
    $.cookie('dense', dense );
    layoutGroupsAndTheirTasks();
  });

  function toggleGroupBy() {
    var groupByCookieValue = 'N/A';
    if( groupBy ) {
      groupBy = false;
      $('.owner-area').remove();
      groupByCookieValue = 'no grouping';
    }else {
      groupBy = true; 
      groupByCookieValue = 'owner';
    }
    $.cookie('groupBy', groupByCookieValue );
    layoutGroupsAndTheirTasks();
  }

  $("#groupby").click( function() {
    console.log("clicked Group By button");
    toggleGroupBy();
  });

  var denseCookieValue = $.cookie('dense');
  if( denseCookieValue == undefined ) {
    dense = false;
  } else {
    if( denseCookieValue == 'true' ) dense = true;
    else dense = false;
  }

  var groupByCookieValue = $.cookie('groupBy');
  if( groupByCookieValue == undefined ) {
    groupBy = false;
  } else {
    if( groupByCookieValue == 'owner' ) groupBy = true;
    else groupBy = false;
  }

  renderGroupsAndTheirTasks();
  makeTaskDraggable(0);
  layoutGroupsAndTheirTasks();
  resizeBoard();
  
		$( "#dialog-task-comments" ).dialog({
			width: 600,
			resizable: true,
			modal: false,
			autoOpen: false
   		});

   		$( "#dialog-error-message" ).dialog({
		   	width: 400,
			resizable: true,
			modal: true,
			autoOpen: false,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});


		


		$( "#dialog-edit-task" ).dialog({
			width: 300,
			resizable: false,
			modal: false,
			autoOpen: false,
			buttons: {
				Del: function() {
          var taskId = $('#taskid').val();
          var task = taskList.get({id:taskId});
          taskList.remove(task);
          task.destroy({
            error: function(model, response) {
              var reasonForFailure = '';
              for( var propName in response ) {
                reasonForFailure = reasonForFailure+', ' + propName+' = '+response[propName];
              }
              $("#error-message").html("Failed to delete; "+reasonForFailure);
              $('#dialog-error-message').dialog('open');
            },
            success: function(model, response) {
              $("#draggableTask"+taskId).remove();
              layoutGroupsAndTheirTasks();
            }
          });
          $( this ).dialog( "close" );
          $('#dialog-task-comments').dialog('close');
          return;
				},
				Cancel: function() {
					$( this ).dialog( "close" );
          $('#dialog-task-comments').dialog('close');
					return;
				},
				Ok: function() {
					if( $("#taskid").val() <= 0 ) {
						$("#error-message").html("Failed to update, the task information was corrupted!");
				    	$('#dialog-error-message').dialog('open');
					}
					var taskId = $('#taskid').val();
          var task = taskList.get({id:taskId});
          var oldOwner = task.get('owner');
          var objWithProperties = { id: taskId };
          $( "#dialog-edit-task input,#dialog-edit-task select,#dialog-edit-task textarea" ).each( function(){
            objWithProperties[ $(this).attr("id") ] = $(this).val();
            // console.log($(this).attr("name")+' / '+$(this).attr("id")+" : "+$(this).val() );
          });
          objWithProperties['color'] = getColor( objWithProperties['colortag'] );
          objWithProperties['owner'] = getOwner( objWithProperties['ownerid'] );
          task.save(objWithProperties,{
            wait: true,
            success:function(model,response) 
            {
              console.log('Model stored on server!');
              task.updateTaskHTML();
              var owner = task.get('owner');
              if( oldOwner != owner ) {
                toggleGroupBy();
                toggleGroupBy();
              } 
              else {
                layoutGroupsAndTheirTasks();
              }
            },
            error:function(model,response) 
            {
                console.log('FAILED to update model on server!');
                var reasonForFailure = '';
                for( var propName in response ) {
                  reasonForFailure = reasonForFailure+', ' + propName+' = '+response[propName];
                }
                $("#error-message").html("Failed to update; "+reasonForFailure);
                $('#dialog-error-message').dialog('open');
            }
          });
          $( this ).dialog( "close" );
          $('#dialog-task-comments').dialog('close');
				}
			}
	   });

		$( "#dialog-new-task" ).dialog({
			modal: false,
			autoOpen: false,
			buttons: {
				Cancel: function() {
					$( this ).dialog( "close" );
					return;
				},
				Ok: function() {
          var objWithProperties = {};
          $( "#dialog-new-task input,#dialog-new-task select,#dialog-new-task textarea" ).each( function(){
            objWithProperties[ $(this).attr("id") ] = $(this).val();
            // console.log($(this).attr("name")+' / '+$(this).attr("id")+" : "+$(this).val() );
          });
          objWithProperties['color'] = getColor( objWithProperties['colortag'] );
          var task = new Task(objWithProperties);
          task.save(objWithProperties,{
            wait: true,
            success:function(model,response) 
            {
              console.log('Model stored on server!');
              task.set('id', parseInt(response['id']));
              task.set('group', parseInt( task.get('group')));
              taskList.add( task );
              var taskTemplate = _.template( $('#task-template').html() )
              $('#kanbanboard').append( taskTemplate( task.toJSON() ) );
              makeTaskDraggable(task.get('id'));
              layoutGroupsAndTheirTasks();
            },
            error:function(model,response) 
            {
              console.log('FAILED to store Task on server!');var reasonForFailure = '';
                for( var propName in response ) {
                  reasonForFailure = reasonForFailure+', ' + propName+' = '+response[propName];
                }
                $("#error-message").html("Failed to update; "+reasonForFailure);
              $('#dialog-error-message').dialog('open');
            }
          });
          $( this ).dialog( "close" );
          $('#newtask').trigger("reset"); 
          return;
				}
			}
		});

  $('#searchfield').click( function(event) {
    $('#searchfield').val('');
  });

  $('#searchfield').keyup( function(event) {
    // console.log("Text:"+$('#searchfield').val());
    var searchString = $('#searchfield').val();
    taskList.each( function( task ) {
        var title = task.get('heading');        
        var result = title.search( new RegExp(searchString, "i") );
        // console.log(title+', '+searchString+', res='+result);
        if( result < 0 ) {
          var description = task.get('description');
          result = description.search( new RegExp(searchString, "i") );
        }
        if( result < 0 ) {
          $('#draggableTask'+task.get('id')).hide();
        } else {
          $('#draggableTask'+task.get('id')).show();
        }
    });
  });

  function printCookies() {
    var cookies = $.cookie();
    console.log('List of cookies:');
    for( var prop in cookies ) {
      console.log(prop+' '+cookies[prop]);
    }
    console.log('Done!');
  }

	});


	function loadTaskComments( taskid ) {
		var projectid = <?php echo $projectid; ?>;
		$("#dialog-task-comments").load("/kanban/taskcommentsformandlegend/"+projectid+"/"+taskid, function(response, status, xhr) {
		 	if (status == "error") {
		    var msg = "Sorry but there was an error: ";
			$("#error-message").html(msg + xhr.status + " " + xhr.statusText);
	    	$('#dialog-error-message').dialog('open');
		  }
		});
	}

	function fillInFormTaskDetails( taskid ) {
    var task = taskList.get({id:taskid});
    task.updateEditDialog();
    return;
	}

  function displayEditDialogWithComments(taskid) {
    // fillInFormTaskDetails(<%- id %>); $('#dialog-edit-task').dialog('open');
    fillInFormTaskDetails(taskid);
    loadTaskComments(taskid);
    $('#dialog-edit-task').dialog('open');
    // $('#dialog-edit-task').dialog('option','position',[10,100]);
    $('#dialog-edit-task').dialog('widget').position({
      my: 'center center',
      at: 'center-300 center',
      of: window,
      collision: 'none flip'
    });
    $('#dialog-task-comments').dialog('open');
    $('#dialog-task-comments').dialog('widget').position({
      my: 'left+20 top',
      at: 'right top',
      of: $("#dialog-edit-task"),
      collision: 'none flip'
    });
  }
	
	</script>

	<script type="text/javascript">
	//
	// Google Analytics
	//
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-31228295-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	//
	// Google Analytics
	//
	</script>

</head>
<body bgcolor=white>
<div id="dock">
	<ul>
    <li><a href="" onclick="$('#dialog-new-task').dialog('open'); return false; ">New Task</a></li> 
		<li><a href="<?php echo site_url( '/kanban/project/'.$projectid.'/'.$sprintid ); ?>">Old Board</a></li>	
		<li><a href="<?php echo site_url( '/kanban/status/'.$projectid.'/'.$sprintid ); ?>">Status</a></li>
		<li><a href="<?php echo site_url( '/kanban/settings/'.$projectid.'/'.$sprintid ); ?>">Settings</a></li>		
		<li><a href="<?php echo site_url( '/kanban/selectproject'); ?>">Projects</a></li>
		<li class="last"><a href="<?php echo site_url( '/kanban/logout'); ?>">Logout</a></li>
	</ul>
</div>

<div class="banner">
	<div class="logo"></div>
	<div class="projecttitle">The '<?php echo $projectname; ?>' Board</div>
	<div class="subtitle">Sprint: <?php echo $sprintname; ?> <?php echo $startdate; ?> through <?php echo $enddate; ?></div>
</div>
<br/>
<div id="timelinecanvas"></div>  
<br/>
<div id="errordiv"></div>

<button id="dense">Dense/Collapse</button>
<button id="groupby">Group By Owner</button>
<input id="searchfield" type=text value="Search"/>

<div id="kanbanboard" class="board">
</div>

 <script type="text/template" id="task-template">
  <div id="draggableTask<%- id %>" taskid="<%- id %>" prio="<%- priority %>" preferredZIndex="<%- preferredZIndex %>" class="ui-widget-content draggableTask <%- color %>-card shadow">    
    <div class="inside-postit">
		<div class="inside-postit-header-and-text">
			<div class="inside-postit-header" id="heading"><%- heading %></div>
			<div class="inside-postit-text" id="description"><%- description %></div>
		</div>
		<div class="inside-postit-footer">
			<div class="inside-postit-footer-prio" title="Prio">
				<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span id="priority"><%- priority %></span>
			</div>
		<div class="inside-postit-footer-estimation" title="Estimation">
			<span class="ui-icon ui-icon-clock"></span><span id="estimation"><%- estimation %></span>
		</div>
		<div class="inside-postit-footer-age" title="Age in days">
			<span class="ui-icon ui-icon-calendar"></span><span id="age"><%- age %></span>
		</div>
		<div class="inside-postit-footer-commenticon" title="Comments">
			<span class="ui-icon ui-icon-comment"></span><span id="comments"><%- comments %></span>
		</div>
		<div class="inside-postit-footer-editicon" title="Click to edit">
			<span class="ui-icon ui-icon-wrench" onclick="displayEditDialogWithComments(<%- id %>); return false; "></span>
		</div>
	</div>
  </div>
</script>

<script type="text/template" id="group-template">
  <div id="group<%- id %>" order="<%- order %>" class="group shadow"><%- title %></div>
</script>

<script type="text/template" id="owner-area-template">
  <div id="owner-area-<%- id %>" class="owner-area">
    <div class="owner-area-inside">
      <div class="owner-area-rotated-title">
        <%- owner %>
      </div>
    </div>
</div>
</script>



<div id="dialog-edit-task" title="Edit Task">	
	<p>
	<form id="updatetask" name="updatetask" action="">
		<input type="hidden" name="projectid" id="projectid" value="<?php echo $projectid; ?>" />
		<input type="hidden" name="sprintid" id="sprintid" value="<?php echo $sprintid; ?>" />		
		<input type="hidden" name="taskid" id="taskid" value="0" />
		<div>
			<table>
				<tr><td>Task :</td><td><input name="heading" id="heading"/></td></tr>
				<tr><td>Text :</td><td><textarea name="description"  id="description" rows="3" cols="20"></textarea></td></tr>
				<tr><td> Priority :</td><td>
					<input name="priority" id="priority" value="100" size="3" />
				</td></tr>
				<tr><td> Estimate :</td><td>
					<input name="estimation" id="estimation" value="0"  size="3" />
				</td></tr>
				<tr><td> Remaining Today :</td><td>
					<input name="todays_estimation" id="todays_estimation" value="0"  size="3" />
				</td></tr>
				<tr><td> Used Time Today :</td><td>
					<input name="todays_time_reporting" id="todays_time_reporting" value="0"  size="3" />
				</td></tr>
				<tr><td> Color Tag :</td><td>
					<select name="colortag" id="colortag"><option value="1">Yellow</option><option value="2">Green</option><option value="3">Red</option><option value="4">Blue</option><option value="5">Pink</option><option value="6">Purple</option><option value="7">Orange</option><option value="8">Grey</option></select>
					</td></tr>

				<tr><td nowrap> Owner :</td><td>
					<select name="ownerid" id="ownerid">
              <option value=0>- Anyone -</option>
                           <?php						
							foreach ($resources as $rs) {		
							
								echo ' <option value="'.$rs['id'].'">'.$rs['name'].'</option>';
														
							}						
							?>
                        </select>
				</td></tr>

				<tr><td nowrap> Workpackage :</td><td>
					<select name="workpackage_id" id="workpackage_id">
                           <?php						
							foreach ($workpackages as $wp) {		
							
								echo ' <option value="'.$wp['id'].'">'.$wp['name'].'</option>';
														
							}						
							?>
                        </select>
				</td></tr>
				<tr><td> Move To Sprint :</td><td>
					<select name="newsprintid" id="newsprintid">
					<?php						
					foreach ($sprints as $sprint) {		
						echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
					}						
					?>	
					</select>
				</td></tr>
			</table>
		</div>
	</form>		
	</p>
</div>

<div id="dialog-new-task" title="New Task">	
	<p>
	<div class="kanbanactions">
		<h2>Add New Task</h2>
		<form id="newtask" name="newtask" action="">
		<input type="hidden" name="projectid" id="projectid" value="<?php echo $projectid; ?>" />
		<input type="hidden" name="sprintid" id="sprintid" value="<?php echo $sprintid; ?>" />
		<input type="hidden" name="group" id="group" value="<?php echo $groups[0]['id']; ?>" />

			<div>
				<table>					
					<tr><td>New Task :</td><td><input name="heading" id="heading" ></td></tr>
					<tr><td>Text :</td><td><textarea name="description" id="description"  rows="3" cols="25"></textarea></td></tr>
					<tr><td> Priority :</td><td>
						<input name="priority" id="priority" value="100" size="3" />
					</td></tr>
					<tr><td> Estimate :</td><td>
						<input name="estimation" id="estimation" value="0"  size="3"  />
					</td></tr>
				    <tr><td> Color Tag :</td><td>
					<select name="colortag" id="colortag"><option value="1">Yellow</option><option value="2">Green</option><option value="3">Red</option><option value="4">Blue</option><option value="5">Pink</option><option value="6">Purple</option><option value="7">Orange</option><option value="8">Grey</option></select>
					</td></tr>			
					<tr><td nowrap> Workpackage :</td><td>
					<select name="workpackage_id" id="workpackage_id">
                           <?php						
							foreach ($workpackages as $wp) {		
							
								echo ' <option value="'.$wp['id'].'">'.$wp['name'].'</option>';
														
							}						
							?>
                        </select>
					</td></tr>
												
				</table>
			</div>
		</form>

	</div>
</div>

<div id="dialog-error-message" title="Error!">
	<p>
			<div class="ui-widget">
				<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
					<strong>Alert:</strong> <div id="error-message">bla bla bla!</div> </p>
				</div>
			</div>
	</p>
</div>

<div id="dialog-task-comments" title="Comments">	
</div>



</body>
</html>


