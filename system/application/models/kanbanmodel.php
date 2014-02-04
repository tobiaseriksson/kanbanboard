<?php
class Kanbanmodel extends Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getCurrentSprintIDOrDefaultToLast($projectid)
    {
		$sprintid = 0;
		$today = date( "Y-m-d" );
		$sql = 'SELECT id FROM `kanban_sprint` where project_id = ? AND startdate <= ? AND enddate >= ?';
		$query = $this->db->query( $sql,array($projectid,$today,$today) );
		
		$sprintid=0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			$sprintid = $res[0]['id'];
		}  else {
			$query = $this->db->query('SELECT max(id) as id FROM `kanban_sprint` where project_id = ?',array($projectid));
			$sprintid=0;
			if ($query->num_rows() > 0)	{
				$res = $query->result_array();		
				$sprintid = $res[0]['id'];
			} 
		}
		return $sprintid;
    }

    function getSprintDetails($sprintid) {
    	$res = array();
    	$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ? ',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		}

		return $res;
    }

    function getProjectDetails($projectid)
    {
    	$res=null;
        $query = $this->db->query('SELECT id,name,startdate FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
		} 
		return $res;
    }

    function getProjectStartAndEndDates($projectid)
    {
    	$projectstartdate = '2012-01-01';	
		$projectenddate = '2012-01-02';	
		$sql='SELECT min( startdate ) as start, max( enddate ) as end FROM kanban_sprint WHERE project_id = ?';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		$plan = array();
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();	
			$projectstartdate = $res[0]['start'];	
			$projectenddate = $res[0]['end'];				
		}
		return array($projectstartdate,$projectenddate);
    }

    function getGroups($projectid)
    {
    	$groups = array();
        $sql='SELECT id,name,wip,displayorder FROM kanban_group WHERE project_id = ? ORDER BY displayorder';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$groups[$i]=$row;			
			$i++;
		}
		return $groups;
    }


	// a and b as unix time i.e. seconds since EPOC
	function countDays( $a, $b )
	{
	    // First we need to break these dates into their constituent parts:
	    $gd_a = getdate( $a );
	    $gd_b = getdate( $b );
	 
	    // Now recreate these timestamps, based upon noon on each day
	    // The specific time doesn't matter but it must be the same each day
	    $a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
	    $b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
	 
	    // Subtract these two numbers and divide by the number of seconds in a
	    //  day. Round the result since crossing over a daylight savings time
	    //  barrier will cause this time to be off by an hour or two.
	    return round( abs( $a_new - $b_new ) / 86400 );
	}

    function getTasks($sprintid)
    {
		$now = strtotime("now");
		$tasks = array();
		$sql='SELECT id as taskid,heading,description,group_id,priority,estimation,colortag,added,enddate,owner_id,workpackage_id,sprint_id FROM kanban_item WHERE sprint_id = ?';
		$query = $this->db->query($sql,array($sprintid));
		foreach ($query->result_array() as $row)
		{
			if( $row['enddate'] == '0000-00-00' ) {
				$row['age'] = $this->countDays( strtotime($row['added'] ), $now );
			} else {
				$row['age'] = $this->countDays( strtotime($row['added'] ), strtotime($row['enddate']) );
			}
			$row['comments'] = 0;
			$row['ownername'] = 'anyone!';
			$row['latest_estimation'] = $row['estimation'];
			$row['todays_time_reporting'] = 0;
			
			$tasks[$row['taskid']]=$row;
		}
    	return $tasks;
    }

	function taskDetailsAsAnArray($projectid,$taskid) 
	{
//		$projectid = $this->input->post('projectid');
//		$taskid = $this->input->post('taskid');
		$sql = 'SELECT heading,priority,description,estimation,colortag,sprint_id,workpackage_id,owner_id,group_id FROM kanban_item WHERE project_id = ? AND id = ?';
		$query = $this->db->query( $sql,array($projectid,$taskid) );
		$taskDetails = array();
		if ($query->num_rows() > 0)	{
			$row = $query->row();	
			$taskDetails[ 'heading' ] = $row->heading;
			$taskDetails[ 'description' ] = $row->description;
			$taskDetails[ 'priority' ] = $row->priority;
			$taskDetails[ 'estimation' ] = $row->estimation;	
			$taskDetails[ 'projectid' ] = $projectid;		
			$taskDetails[ 'sprintid' ] = $row->sprint_id;	
			$taskDetails[ 'taskid' ] = $taskid;		
			$taskDetails[ 'colortag' ] = $row->colortag;
			$taskDetails[ 'workpackage_id' ] = $row->workpackage_id;			
			$taskDetails[ 'ownerid' ] = $row->owner_id;					
			$taskDetails[ 'group_id' ] = $row->group_id;			
		} 
		$today = date( "Y-m-d" );
		$sql = 'SELECT new_estimate FROM kanban_progress WHERE item_id = ? AND date_of_progress <= CURDATE() ORDER BY date_of_progress DESC LIMIT 1';
		$query = $this->db->query( $sql, array( $taskid ) );
		$taskDetails[ 'todays_estimation' ] = $taskDetails[ 'estimation' ];
		if ($query->num_rows() > 0)	{
			$row = $query->row();	
			$taskDetails[ 'todays_estimation' ] = $row->new_estimate;
		}
		## Picks up the todays reported hours if they exists so that it is possible to update it, otherwise 0
		$sql = 'SELECT hours FROM kanban_time_reporting WHERE item_id = ? AND reporting_date = CURDATE()';
		$query = $this->db->query( $sql, array( $taskid ) );
		$taskDetails[ 'todays_time_reporting' ] = 0;
		if ($query->num_rows() > 0)	{
			$row = $query->row();	
			$taskDetails[ 'todays_time_reporting' ] = $row->hours;
		}
		return $taskDetails;
	}

	function addTask($data,$projectId)
	{
		$this->db->insert('kanban_item', $data);	
		$query = $this->db->query('SELECT max(id) as lastid FROM kanban_item WHERE project_id = ?', array($projectId) );
		$taskid = 0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$taskid = $res[0]['lastid'];	
		} 
		return $taskid;
	}

	function deleteTask($taskid)
	{
		$this->db->where('id', $taskid);
		// $this->db->where('project_id', $projectId);
        $this->db->delete('kanban_item');   
	}

    function updateTask($data,$taskid) 
    {
    	$this->db->where('id', $taskid);
		$this->db->update('kanban_item', $data);			
    }

    function updateProgressForTask($taskid,$todays_estimation,$today)
    {
		$sql="INSERT INTO kanban_progress (item_id,new_estimate,date_of_progress) VALUES (?,?,?) ON DUPLICATE KEY UPDATE item_id =?, new_estimate = ?, date_of_progress = ?";
		$query = $this->db->query($sql,array( $taskid,$todays_estimation,$today,$taskid,$todays_estimation,$today) ); 
	}

	function updateTimeReportingForToday($taskid,$todays_time_reporting,$today)
	{
		$sql="INSERT INTO kanban_time_reporting (item_id,hours,reporting_date) VALUES (?,?,?) ON DUPLICATE KEY UPDATE item_id =?, hours = ?, reporting_date = ?";
		$query = $this->db->query($sql,array( $taskid,$todays_time_reporting,$today,$taskid,$todays_time_reporting,$today) ); 

    }

    function addToHistory($project_id,$change) 
    {
		$data = array(
			'project_id' => $project_id,
			'who' => 'N/A',
			'change' => $change
		);
		$this->db->insert('kanban_history',$data);
	}

    function getCommentCountForAllTasksInASprint($sprintid) 
    {
		$commentcount = array();
		$sql = 'SELECT c.item_id as taskid, COUNT( c.id ) as count FROM kanban_item_comment c, kanban_item i WHERE c.item_id = i.id AND i.sprint_id = ? GROUP BY c.item_id';
		$query = $this->db->query( $sql, array($sprintid) );
		$index=0;
		foreach ($query->result_array() as $row)
		{
			$commentcount[ $index ] = $row;
			$index++;
		}
		return $commentcount;
    }
    
    function getAvailableWorkPackages($projectid)
    {
		$workpackages = array();
		$sql='SELECT id,name FROM kanban_workpackage WHERE project_id = ? ORDER BY name';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$workpackages[$i]=$row;			
			$i++;
		}
    	return $workpackages;
    }

    function getAvailableResources($projectid)
    {
    	$resources = array();
        $sql='SELECT id,name FROM kanban_resource WHERE project_id = ? ORDER BY name';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$resources[$i]=$row;			
			$i++;
		}
		return $resources;
    }

    function getLatestEstimations($sprintid) {
    	$estimations = array();
    	$sql = 'SELECT p1.item_id as item_id,p1.new_estimate as new_estimate FROM kanban_progress p1 LEFT JOIN kanban_progress p2 ON (p1.item_id = p2.item_id AND p1.date_of_progress < p2.date_of_progress) WHERE p2.date_of_progress IS NULL AND p1.item_id IN (SELECT id FROM kanban_item WHERE sprint_id = ?)';
		$query = $this->db->query( $sql, array( $sprintid ) );
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$estimations[$i]=$row;
			$i++;
		}
		return $estimations;
    }

	function getTodaysTimeReports($sprintid) {
    	$timereports = array();
    	$sql = 'SELECT item_id,reporting_date,hours FROM kanban_time_reporting WHERE reporting_date = curdate() AND item_id IN (SELECT id FROM kanban_item WHERE sprint_id = ?)';
		$query = $this->db->query( $sql, array( $sprintid ) );
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$timereports[$i]=$row;
			$i++;
		}
		return $timereports;
    }

    function getAvailableSprints($projectid)
    {
    	$sprints = array();
    	$sql = 'SELECT id,name FROM kanban_sprint WHERE project_id = ?';
		$query = $this->db->query( $sql, array( $projectid ) );
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$sprints[$i]=$row;
			$i++;
		}
		return $sprints;	
    }

    function getTimeLineItems($projectid, $projectstartdate, $projectenddate) 
    {
    	$sql='SELECT date, headline FROM `kanban_timeline` where project_id = ? ORDER BY date';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$timelineitems[$i]=$row;			
			$i++;
		}
		$timelineitems[$i++] = array( 'date' => $projectstartdate, 'headline' => 'Project Start' );			
		$timelineitems[$i++] = array( 'date' => $projectenddate, 'headline' => 'Project End' );			
		return $timelineitems;
    }

}

?>