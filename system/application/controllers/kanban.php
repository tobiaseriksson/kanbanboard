<?php

class kanban extends Controller {

    function kanban()    {
        parent::Controller();
		$this->load->database();     
		$this->load->helper('xml');    
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Kanbanmodel','kanbanmodel',TRUE);
		
	}
	
	function blog()
	{
		$pagedata = array();
		$pagedata['errormessage'] = '';
		$this->load->view('kanban/bloghome',$pagedata);
	}
	
	function tabs()
	{
		$pagedata = array();
		$pagedata['errormessage'] = '';
		$this->load->view('kanban/tabs',$pagedata);
	}
	
	function index() {
		$pagedata = array();
		$pagedata['errormessage'] = '';
		$pagedata['redirect_to_url'] = '';

		$projects = array();

		$query = $this->db->query('SELECT id,name FROM kanban_project WHERE deleted = 0');
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$projects[$i]=$row;
			$i++;
		}
		$pagedata['projects'] = $projects;	
		$this->load->view('kanban/login', $pagedata);
	}
	
	function about($projectid,$sprintid=0) {
		$pagedata = array();
		$pagedata['projectid'] = $projectid;
		$pagedata['sprintid'] = $sprintid;	
		$projectname = "no-name";
		$projectstartdate = '2010-01-01';
		$query = $this->db->query('SELECT id,name,startdate FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectname = $res[0]['name'];	
			$projectstartdate =  $res[0]['startdate'];	
		} 
		$pagedata['projectname'] = $projectname;	
		
		$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ? ',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		} 
		$pagedata['sprintid'] = $sprintid;	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	
		$this->load->view('kanban/about', $pagedata);
	}
	
	
	function logout() {
		$this->session->sess_destroy();
        kanban::index();
	}

	function login($redirect_to_url='')
	{
		$pagedata['errormessage'] = '';
		$pagedata['redirect_to_url'] = $redirect_to_url;
		
		if( strlen( trim( $redirect_to_url ) ) <= 0 ) {
			$redirect_to_url = "/kanban/selectproject";
			$pagedata['redirect_to_url'] = $redirect_to_url;
		}
		if( strlen( trim( $this->input->post('redirect_to_url') ) ) > 0 ) {
			$pagedata['redirect_to_url'] = $this->input->post('redirect_to_url');
			$redirect_to_url = $this->input->post('redirect_to_url');
		}

		// check to see if we are loging in or if we are viewing the login page
		if( $this->input->post('login') == FALSE ) {
			$pagedata['errormessage'] = 'Sorry wrong login or password!';
			$this->load->view('kanban/login',$pagedata);
			return;
		}
		
		
		// verify username and password in the database
		$md5password = md5( $this->input->post('password') );
		$query = $this->db->query("SELECT id,login FROM kanban_user WHERE password = ? AND login = ?",array($md5password,$this->input->post('login')));
		if ($query->num_rows() <= 0)
		{
			// Oops! no such user
			$pagedata['errormessage'] = 'Sorry wrong login or password!';
			$this->load->view('kanban/login',$pagedata);
			return;
		}
		$res = $query->result_array();
		$id = $res[0]['id'];	
		// Set session / login OK
		$this->session->set_userdata('login', $this->input->post('login') );
		$this->session->set_userdata('id', $id );
		$this->session->set_userdata('logged_in', 'TRUE');
		$this->session->set_userdata('sess_expiration', '0');

		header("Location: ".$redirect_to_url );
	}
	
	
	function register()
	{
		$pagedata['errormessage'] = '';
		$pagedata['login'] = 'loginname';
		$pagedata['password'] = 'passwd';
		$pagedata['password2'] = 'passwd2';
		
		// check to see if we are loging in or if we are viewing the login page
		if( $this->input->post('login') == FALSE ) {
			$pagedata['errormessage'] = '';
			$this->load->view('kanban/register',$pagedata);
			return;
		}
		
		$pagedata['login'] = $this->input->post('login');
		$pagedata['password'] = $this->input->post('password');
		$pagedata['password2'] = $this->input->post('password2');
		
		if( $this->input->post('password') != $this->input->post('password2')  ) {
			$pagedata['errormessage'] = 'Oops! the password was not equal ! Try again.';
			$this->load->view('kanban/register',$pagedata);
			return;
		}
		// verify if the login allready exists in the database
		$query = $this->db->query("SELECT id FROM kanban_user WHERE login = ?",array($this->input->post('login')));
		if ($query->num_rows() >= 1)
		{
			// Oops! there is allreadt a user with that login
			$pagedata['errormessage'] = 'Sorry login allready exists, please choose a different login-name!';
			$this->load->view('kanban/register',$pagedata);
			return;
		}
		// Store in DB
		$md5password = md5( $this->input->post('password') );
		$data = array(
					   'login' => $this->input->post('login'),
					   'password' => $md5password
					);			
		$this->db->insert('kanban_user', $data); 
		
		// Retrieve the ID from the db for the new user
		$query = $this->db->query("SELECT id FROM kanban_user WHERE password = ? AND login = ?",array($md5password,$this->input->post('login')));
		if ($query->num_rows() <= 0)
		{
			// Oops! no such user
			$pagedata['errormessage'] = 'Oops! database problems, unable to find the NEW user...';
			$this->load->view('kanban/register',$pagedata);
			return;
		}
		$res = $query->result_array();
		$id = $res[0]['id'];	
		// Set session / login OK
		$this->session->set_userdata('login', $this->input->post('login') );
		$this->session->set_userdata('id', $id );
		$this->session->set_userdata('logged_in', 'TRUE');
		$this->session->set_userdata('sess_expiration', '0');
		$redirect_to_url = "/kanban/selectproject";
		header("Location: ".$redirect_to_url );
	}
	
	
	function selectproject() {
		if ($this->session->userdata('logged_in') != TRUE) {
			kanban::login( uri_string() );
			return;
	    }
		$id = $this->session->userdata('id');
		$pagedata = array();

		$projects = array();

		$query = $this->db->query('SELECT id,name FROM kanban_project WHERE deleted = 0 AND user_id = ?',array($id));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$projects[$i]=$row;
			$i++;
		}
		$pagedata['projects'] = $projects;	
		$this->load->view('kanban/selectproject', $pagedata);
	}
	
	
	
	function hasAccess( $project_id ) {
		if ( $this->session->userdata('logged_in') != TRUE )
		{
			return FALSE;
		}
		$user_id = $this->session->userdata('id');
		$sql="SELECT * FROM kanban_project where user_id = ? and id = ?";
		$query = $this->db->query( $sql, array( $user_id, $project_id ) );
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		return FALSE;
	}
	
	function redirectIfNoAccess( $project_id ) {
		if( kanban::hasAccess(  $project_id ) != TRUE ) {
			$redirect_to_url = "/kanban/logout";
			header("Location: ".$redirect_to_url );
			return;
		}
	}
	
	function wpstatus($projectid,$sprintid=0) {
	
		kanban::redirectIfNoAccess( $projectid );
	
		$pagedata = array();
		
		// **************************************
		// Project Properties
		$pagedata['initialteamefficiency'] = 80;	// Defaul value
		
		$sql="SELECT `key`,`value` FROM `kanban_project_properties` WHERE project_id = ? ";
		$query = $this->db->query($sql,array( $projectid ) );
		foreach ($query->result_array() as $row)
		{
			$pagedata[ $row['key'] ]=$row['value'];
		}
		
		
		if( $sprintid <=0 ) {
			$query = $this->db->query('SELECT max(id) as id FROM `kanban_sprint` where project_id = ?',array($projectid));
			$sprintid=0;
			if ($query->num_rows() > 0)	{
				$res = $query->result_array();		
				$sprintid = $res[0]['id'];
			} 
		}
		
		$pagedata['projectid'] = $projectid;
		$projectname = "no-name";
		$projectstartdate = '2010-01-01';
		$query = $this->db->query('SELECT id,name,startdate FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectname = $res[0]['name'];	
			$projectstartdate =  $res[0]['startdate'];	
		} 
		$pagedata['projectname'] = $projectname;	
		
		$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ?',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		} 
		$pagedata['sprintid'] = $sprintid;	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	
		
		//
		// Workpackage Summary
		$wpmatrix = array();
		$wporder = array();
		$sql="SELECT w.name AS wp, i.id AS item_id, i.heading AS heading, priority AS prio, estimation, s.name AS sprint_name FROM  kanban_item i, kanban_workpackage w, kanban_sprint s WHERE i.sprint_id = s.id AND i.workpackage_id = w.id AND i.project_id = ? ORDER BY w.name, priority, i.heading";
		$query = $this->db->query( $sql, array($projectid) );
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$wpmatrix[ $row['item_id'] ] = array( $row['wp'], $row['item_id'], $row['heading'], $row['prio'], $row['estimation'], $row['estimation'], $row['sprint_name']  );
			$wporder[ $i ] = $row['item_id'];
			// echo "task = ".$row['heading']."<br>";
			$i++;
		}
	
		$sql="SELECT p.item_id AS item_id, p.date_of_progress AS latest, p.new_estimate AS estimate FROM (SELECT item_id AS item_id, max(date_of_progress) AS latest FROM kanban_progress GROUP BY item_id )  as x inner join kanban_progress p, kanban_item i WHERE  p.item_id = x.item_id AND p.item_id = i.id AND p.date_of_progress = x.latest AND i.project_id = ?";
		$query = $this->db->query( $sql, array($projectid) );
		$i=0;
		foreach ($query->result_array() as $row)
		{		
			$wpmatrix[ $row['item_id'] ][ 5 ] = $row['estimate'];
		}
		
		// For those Tasks that has completed, we need to fill in the value 0 as the last estimate
		$sql="SELECT id FROM kanban_item WHERE project_id = ? AND not enddate = '0000-00-00' ORDER BY id";
		$query = $this->db->query( $sql, array($projectid) );
		if ($query->num_rows() > 0)	{
			foreach ($query->result_array() as $row)
			{	 
				$wpmatrix[ $row['id'] ][ 5 ] = 0;
			}
		}
		
		$pagedata['wpmatrix'] = $wpmatrix;	
		
		$pagedata['wporder'] = $wporder;	
		
		//
		// Project start and end
		//
		
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
		$end = strtotime($projectenddate);
		$start = strtotime($projectstartdate);
		$totaldays = floor( ($end - $start) / 86400 ); // strtotime($enddate) ÌÄå± strtotime($startdate);
		$pagedata['totaldays'] = $totaldays;
		
		$now = date( "Y-m-d" );
		$noworend = strtotime($now);
		if( $noworend > $end ) $noworend = $end;
		$days = floor( ($noworend - $start) / 86400 ); // strtotime($enddate) ÌÄå± strtotime($startdate);
		if( $days < 0 ) {
			$days = 0;
		}
		// Build up a Earned Value Matrix that has the following shape
		// maxtrix[ item-id ] = array( day1, day2, day3, day4, .... , day n )
		// where day = earned value for that item that day
		$projectmatrix = array();
		$itemlist = array();
		$totalestimation = 0;
		$sql="SELECT  id,  workpackage_id,  estimation FROM  kanban_item WHERE project_id = ?";
		$query = $this->db->query( $sql, array($projectid) );
		if ($query->num_rows() > 0)	{
			foreach ($query->result_array() as $row)
			{
				$id = intval( $row['id'] );	 
				$estimation = intval ( $row['estimation'] );
				$itemlist[ $id ] = array($id,$row['workpackage_id'],$estimation);
				$projectmatrix[ $id ] = array();
				// Fill in -1 for all days
				for( $dayIndex = 0; $dayIndex < $days; $dayIndex++ ) {
					$projectmatrix[ $id ][ $dayIndex ] = -1;
				}
				$projectmatrix[ $id ][ 0 ] = 0;
				$totalestimation = $totalestimation + $estimation; 
			}
		}
	
		// Fill in daily updates
		$sql="SELECT p.item_id as id,datediff(p.date_of_progress, ?) days_since_start,p.new_estimate as estimation FROM `kanban_item` i, kanban_progress p WHERE p.item_id = i.id AND i.project_id = ? ORDER BY item_id,days_since_start";
		$query = $this->db->query( $sql,array($projectstartdate,$projectid) );
		if ($query->num_rows() > 0)	{
			foreach ($query->result_array() as $row)
			{	
				$id = intval( $row['id'] );
				$dailyestimation = intval ( $row['estimation'] );
				$earned = $itemlist[ $id ][2] - $dailyestimation;
				$dayIndex = intval( $row['days_since_start'] );
				$arraySize = count( $projectmatrix[ $id ] );
				if( $dayIndex <= 0 ) {
					$dayIndex = 0;
				}
				if( $dayIndex >= $arraySize ) {
					$dayIndex = $arraySize-1; 	
				} 				
				if( $projectmatrix[ $id ][ $dayIndex ] < 0 || $dailyestimation < $projectmatrix[ $id ][ $dayIndex ] ) $projectmatrix[ $id ][ $dayIndex ] = $earned; 
			}
		}
		
		
		/*
		echo "<h2>fill in new daily</h2>";
		echo "<table border=1px >";
		echo "<th>ID</th><th>Heading</th>";
		for( $i=1; $i<=$days; $i++) echo "<th>".$i."</th>";
		foreach( $matrix as $id => $arr ) {
			echo "<tr><th>".$id."</th><th>".$tasklookup[ $id ]."</th>";
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo ",(".$day.")";
				echo "<td>".$value."</td>";
			}
			echo "</tr>>";
		}
		echo "</table>";
		*/
		
		// For those Tasks that has completed, we need to fill in the value FULL-original-estimate for THAT day (date)
		$sql="SELECT id, estimation,datediff(enddate, ?) days_since_start  FROM kanban_item WHERE project_id = ? AND not enddate = '0000-00-00' ORDER BY id";
		$query = $this->db->query( $sql,array($projectstartdate,$projectid) );
		if ($query->num_rows() > 0)	{
			foreach ($query->result_array() as $row)
			{	
				$id = intval( $row['id'] );
				$dayIndex = intval( $row['days_since_start'] );
				$arraySize = count( $projectmatrix[ $id ] );
				if( $dayIndex >= $arraySize ) $dayIndex = $arraySize-1; 	
				if( $dayIndex <= 0 ) $dayIndex = 0;
				$projectmatrix[ $id ][ $dayIndex ] = $itemlist[ $id ][2]; 
			}
		}
		
		
		/*
		echo "<h2>Fill in Completed</h2>";
		echo "<table border=1px >";
		echo "<th>ID</th><th>Heading</th>";
		for( $i=1; $i<=$days; $i++) echo "<th>".$i."</th>";
		foreach( $matrix as $id => $arr ) {
			echo "<tr><th>".$id."</th><th>".$tasklookup[ $id ]."</th>";
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo ",(".$day.")";
				echo "<td>".$value."</td>";
			}
			echo "</tr>>";
		}
		echo "</table>";
		*/
		
		// Now fill in all the blanks(-1), i.e. the values inbetween the values we know we fill in with the values of the previous value
		foreach( $projectmatrix as $id => $arr ) {
			$oldValue = $arr[0];
			for( $i = 0; $i < count( $arr ); $i++ ) {
				$value = intval( $arr[ $i ] );			
				if( $value < 0 ) $arr[ $i ] = $oldValue; 	
				$oldValue = $arr[ $i ];
			}
			$projectmatrix[$id] = $arr;
		}
		
		
		$pagedata['totaldays'] = $totaldays;
		$pagedata['progressmatrix'] = $projectmatrix;
		$pagedata['itemlist'] = $itemlist;
		
		// Now finally create the diagram array of (x,y)-values where x = DAY since start-date
		$diagramactual = array();
		for( $i = 0; $i < $days; $i++ ) {
				$diagramactual[ $i ] = array(  $i,  0 );
		}
		
		// Summarize column by column
		foreach( $projectmatrix as $id => $arr ) {
			// echo "<br>ID = ".$id;
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo "day = ".$day." = ".$value;
				$diagramactual[ $day ][ 1 ] = $diagramactual[ $day ][ 1 ] + $value;
			}
		}
		
		$pagedata['diagramactual'] = $diagramactual;
		
		// Projected path, i.e what will the future look like...
		// and
		// Original plan accoriding to the resource schedule
		$sql="SELECT datediff( s.date, ?) as day, sum( s.effort ) as tot FROM kanban_resource r , kanban_resource_schedule s WHERE r.id = s.resource_id AND r.project_id = ? AND s.date >= ? AND s.date <= ? group by s.date order by s.date";
		$diagramprojected = array();
		$query = $this->db->query( $sql, array($projectstartdate,$projectid,$projectstartdate,$projectenddate) );
		$i=0;
		$x=0;
		$points = 0;
		$effortpoints = 0;
		if( $days > 0 ) $points = $diagramactual[ $days-1 ][1];
		$diagramprojected[$i] =  array( $days-1, $points );
		$diagrameffort = array();
		$i = $i + 1;
		foreach ($query->result_array() as $row)
		{	
			$value = $row['tot'];
			$day = $row['day'];
			if( $day > ($days-1) ) {
				$points = $points + ( $value * ($pagedata['initialteamefficiency'] / 100) );
				$diagramprojected[$i] =  array(  $day,  $points );
				$i = $i + 1;
			}
			$x = $x + 1;
			$effortpoints = $effortpoints + ( $value * ($pagedata['initialteamefficiency'] / 100) );
			$diagrameffort[ $x ] =  array(  $day,  $effortpoints );
		}
		$pagedata['diagramprojected'] = $diagramprojected;
		$pagedata['diagrameffort'] = $diagrameffort;
		
		$diagramgoal = array();
		$diagramgoal[ 0 ] = array(  0,  $totalestimation );
		$diagramgoal[ 1 ] = array(  $totaldays,  $totalestimation );
		$pagedata['diagramgoal'] = $diagramgoal;
		
		#
		# Legend / History  *******************************************************************
		#
		$legend=array();
		$sql="SELECT i.id,i.heading,s.name as sprintname,i.enddate,week(i.enddate) as finishedweeknumber,i.startdate,week(i.startdate) as startweeknumber,estimation,priority, DATEDIFF( i.enddate, i.startdate ) as leadtime FROM kanban_item i,kanban_sprint s  where i.project_id = ".$projectid." and sprint_id = s.id order by sprintname,priority";
		$query = $this->db->query( $sql );
		$i=0;
		foreach ($query->result_array() as $row)
		{			
			$legend[$i] = $row;
			$i++;			
		}		
		$pagedata['legend'] = $legend;
		
		$this->load->view('kanban/status_workpackage', $pagedata);
	}
	
	
	function status($projectid,$sprintid=0) {
	
		kanban::redirectIfNoAccess( $projectid );
	
		$pagedata = array();
		
		if( $sprintid <=0 ) {
			$query = $this->db->query('SELECT max(id) as id FROM `kanban_sprint` where project_id = ?',array($projectid));
			$sprintid=0;
			if ($query->num_rows() > 0)	{
				$res = $query->result_array();		
				$sprintid = $res[0]['id'];
			} 
		}
		
		// **************************************
		// Project Properties
		$pagedata['initialteamefficiency'] = 80;	// Defaul value
		
		$sql="SELECT `key`,`value` FROM `kanban_project_properties` WHERE project_id = ? ";
		$query = $this->db->query($sql,array( $projectid ) );
		foreach ($query->result_array() as $row)
		{
			$pagedata[ $row['key'] ]=$row['value'];
		}
		
		$pagedata['projectid'] = $projectid;
		$projectname = "no-name";
		$projectstartdate = '2010-01-01';
		$query = $this->db->query('SELECT id,name,startdate FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectname = $res[0]['name'];	
			$projectstartdate =  $res[0]['startdate'];	
		} 
		$pagedata['projectname'] = $projectname;	
		
		$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ?',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		} 
		$pagedata['sprintid'] = $sprintid;	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	
		
		$e = strtotime($enddate);
		$s = strtotime($startdate);
		$totaldays = floor( ($e - $s) / 86400 ); // strtotime($enddate) ÌÄå± strtotime($startdate);
		$pagedata['totaldays'] = $totaldays;
		
		$now = date( "Y-m-d" );
		$e = strtotime($now);
		$s = strtotime($enddate);
		$daysleft = floor( ($s - $e) / 86400 ); // strtotime($enddate) ÌÄå± strtotime($startdate);
		if( $daysleft < 0 ) {
			$daysleft = 0;
		}
		$pagedata['daysleft'] = $daysleft;
		
		//
		// Inflow/Outflow for sprint *******************************************************************
		//
		$diagraminflow = array();
		$sql = "SELECT datediff(added,?) as day,count(added) as total FROM kanban_item where  sprint_id = ?  and added > ? and not added = '0000-00-00' group by day order by day";
		$query = $this->db->query( $sql, array($startdate,$sprintid,$startdate) );
		$i=0;
		foreach ($query->result_array() as $row)
		{		
			$day = $row['day'];
			while( $i < $day && $i < $totaldays ) {
				$diagraminflow[ $i ] = 0;
				$i = $i + 1;
			}
			$diagraminflow[ $i ] = $row['total'];
			$i = $i + 1;	
		}
		while( $i < $totaldays ) {
			$diagraminflow[ $i ] = 0;
			$i = $i + 1;
		}
		$pagedata['diagraminflow'] = $diagraminflow;
		
		$diagramoutflow = array();
		$sql = "SELECT datediff(enddate,?) as day,count(enddate) as total FROM kanban_item where  sprint_id = ?  and enddate > ? and not enddate = '0000-00-00' group by day order by day";
		$query = $this->db->query( $sql,array($startdate,$sprintid,$startdate) );
		$i=0;
		foreach ($query->result_array() as $row)
		{		
			$day = $row['day'];
			while( $i < $day && $i < $totaldays ) {
				$diagramoutflow[ $i ] = 0;
				$i = $i + 1;
			}
			$diagramoutflow[ $i ] = $row['total'];
			$i = $i + 1;	
		}
		while( $i < $totaldays ) {
				$diagramoutflow[ $i ] = 0;
				$i = $i + 1;
			}
		$pagedata['diagramoutflow'] = $diagramoutflow;
				
		
		#
		# Legend / History  *******************************************************************
		#
		$legend=array();
		$sql="SELECT i.id,i.heading,s.name as sprintname,i.enddate,week(i.enddate) as finishedweeknumber,i.startdate,week(i.startdate) as startweeknumber,estimation,priority, DATEDIFF( i.enddate, i.startdate ) as leadtime FROM kanban_item i,kanban_sprint s  where i.sprint_id = ? and sprint_id = s.id order by sprintname,priority";
		$query = $this->db->query( $sql, array( $sprintid ) );
		$i=0;
		foreach ($query->result_array() as $row)
		{			
			$legend[$i] = $row;
			$i++;			
		}		
		$pagedata['legend'] = $legend;
		
		#
		# Burndown  *******************************************************************
		#
		// Estimation
		$sql="SELECT sum(estimation) as total FROM `kanban_item` where sprint_id = ?";
		$query = $this->db->query( $sql,array($sprintid) );
		$totalestimation=0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			$totalestimation = $res[0]['total'];	
		} 
		$pagedata['totalestimation'] = $totalestimation;
		
		// Effort
		$sql="SELECT sum( s.effort ) as total FROM kanban_resource r , kanban_resource_schedule s WHERE r.id = s.resource_id AND r.project_id = ? AND s.date >= ? AND s.date <= ?";
		$query = $this->db->query( $sql,array($projectid,$startdate,$enddate) );
		$totaleffort=0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			$totaleffort = $res[0]['total'];	
		} 
		$pagedata['totaleffort'] = $totaleffort;
		$sql="SELECT datediff( s.date, ? ) as day, sum( s.effort ) as tot FROM kanban_resource r , kanban_resource_schedule s WHERE r.id = s.resource_id AND r.project_id = ? AND s.date >= ? AND s.date <= ? group by s.date order by s.date";
		$diagrameffort = array();
		$query = $this->db->query( $sql, array($startdate,$projectid,$startdate,$enddate) );
		$i=0;
		$points = $totalestimation;
		$diagrameffort[$i] =  array( 0, $points );
		$i = $i + 1;
		foreach ($query->result_array() as $row)
		{		
			$points = $points - ($row['tot'] * ($pagedata['initialteamefficiency'] / 100) );	
			$diagrameffort[$i] =  array(  $row['day'],  $points );
			$i = $i + 1;
		}
		$pagedata['diagrameffort'] = $diagrameffort;
		
		$totalnumberoftasks = 0;
		$sql="SELECT id,estimation,heading,description FROM kanban_item where sprint_id = ? ORDER BY id";
		$query = $this->db->query( $sql,array($sprintid) );
		$totalnumberoftasks = $query->num_rows();
		
		$endtime = strtotime($enddate);
		$starttime = strtotime($startdate);
		$nowtime = strtotime("now");
		$t = $nowtime;
		if( $endtime < $nowtime ) $t = $endtime;
		$days = ceil( ($t-$starttime) / 86400 ); 
		if( $days <= 0 ) $days = 1;	
		// prepare the matrix, with TASK number of rows, and DAYS number of columns, where the first value is the initial estimation
		$matrix = array();
		$tasklookup = array();
		foreach ($query->result_array() as $row)
		{	
			$id = intval( $row['id'] );
			$estimation = intval ( $row['estimation'] );
			$heading = $row['heading'];
			$description = $row['description'];
			$tasklookup[ $id ] = array( $heading, $estimation, $description );
			$matrix[ $id ] = array();
			$reported_hours[ $id ] = array();
			for( $dayIndex = 0; $dayIndex < $days; $dayIndex++ ) {
				$matrix[ $id ][ $dayIndex ] = -1;
				$reported_hours[ $id ][ $dayIndex ] = 0;
			}
			$matrix[ $id ][ 0 ] = $estimation; 
		}
		
		$sql='SELECT r.item_id as id,r.hours as hours, r.reporting_date, DATEDIFF(r.reporting_date,?) as days_since_reporting FROM kanban_time_reporting r,kanban_item i WHERE i.id = r.item_id AND i.sprint_id = ? AND r.reporting_date >= ? ORDER BY i.id,days_since_reporting';
		$query = $this->db->query( $sql,array($startdate,$sprintid,$startdate) );
		if ($query->num_rows() > 0)	{
			foreach ($query->result_array() as $row)
			{	
				$id = intval( $row['id'] );
				$hours = intval ( $row['hours'] );
				$dayIndex = intval( $row['days_since_reporting'] );
				$arraySize = count( $matrix[ $id ] );
				if( $dayIndex >= 0 && $dayIndex <= $arraySize ) {
					$reported_hours[ $id ][ $dayIndex ] = $hours; 
				} 				
			}
		}

		$pagedata['reported_hours'] = $reported_hours;

		/*
		echo "<h2>Presetup</h2>";
		echo "<table border=1px >";
		echo "<th>ID</th><th>Heading</th>";
		for( $i=1; $i<=$days; $i++) echo "<th>".$i."</th>";
		foreach( $matrix as $id => $arr ) {
			echo "<tr><th>".$id."</th><th>".$tasklookup[ $id ]."</th>";
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo ",(".$day.")";
				echo "<td>".$value."</td>";
			}
			echo "</tr>>";
		}
		echo "</table>";
		*/
		
		
		// fill in the 'new' daily estimations for each day we have information  
		$sql="SELECT p.item_id as id,datediff(p.date_of_progress, ?) days_since_sprint_start,p.new_estimate as estimation FROM `kanban_item` i, kanban_progress p WHERE p.item_id = i.id AND i.sprint_id = ? ORDER BY item_id,days_since_sprint_start";
		$query = $this->db->query( $sql,array($startdate,$sprintid,$startdate) );
		if ($query->num_rows() > 0)	{
			foreach ($query->result_array() as $row)
			{	
				$id = intval( $row['id'] );
				$estimation = intval ( $row['estimation'] );
				$dayIndex = intval( $row['days_since_sprint_start'] );
				$arraySize = count( $matrix[ $id ] );
				if( $dayIndex <= 0 ) {
					$dayIndex = 0;
				}
				if( $dayIndex >= $arraySize ) {
					$dayIndex = $arraySize-1; 	
				} 				
				$matrix[ $id ][ $dayIndex ] = $estimation; 
			}
		}
		
		
		/*
		echo "<h2>fill in new daily</h2>";
		echo "<table border=1px >";
		echo "<th>ID</th><th>Heading</th>";
		for( $i=1; $i<=$days; $i++) echo "<th>".$i."</th>";
		foreach( $matrix as $id => $arr ) {
			echo "<tr><th>".$id."</th><th>".$tasklookup[ $id ]."</th>";
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo ",(".$day.")";
				echo "<td>".$value."</td>";
			}
			echo "</tr>>";
		}
		echo "</table>";
		*/
		
		// For those Tasks that has completed, we need to fill in the value 0 for THAT day (date)
		$sql="SELECT id, estimation,datediff(enddate, ?) days_since_sprint_start  FROM kanban_item WHERE sprint_id = ? AND not enddate = '0000-00-00' ORDER BY id";
		$query = $this->db->query( $sql, array($startdate,$sprintid) );
		if ($query->num_rows() > 0)	{
			foreach ($query->result_array() as $row)
			{	
				$id = intval( $row['id'] );
				$dayIndex = intval( $row['days_since_sprint_start'] );
				$arraySize = count( $matrix[ $id ] );
				if( $dayIndex >= $arraySize ) $dayIndex = $arraySize-1; 	
				if( $dayIndex <= 0 ) $dayIndex = 0;
				$matrix[ $id ][ $dayIndex ] = 0; 
			}
		}
		
		
		/*
		echo "<h2>Fill in Completed</h2>";
		echo "<table border=1px >";
		echo "<th>ID</th><th>Heading</th>";
		for( $i=1; $i<=$days; $i++) echo "<th>".$i."</th>";
		foreach( $matrix as $id => $arr ) {
			echo "<tr><th>".$id."</th><th>".$tasklookup[ $id ]."</th>";
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo ",(".$day.")";
				echo "<td>".$value."</td>";
			}
			echo "</tr>>";
		}
		echo "</table>";
		*/
		
		// Now fill in all the blanks(-1), i.e. the values inbetween the values we know we fill in with the values of the previous value
		foreach( $matrix as $id => $arr ) {
			$oldValue = $arr[0];
			for( $i = 0; $i < count( $arr ); $i++ ) {
				$value = intval( $arr[ $i ] );			
				if( $value < 0 ) $arr[ $i ] = $oldValue; 	
				$oldValue = $arr[ $i ];
			}
			$matrix[$id] = $arr;
		}
		
		/*
		echo "<h2>Fill in Blanks(-1)</h2>";
		echo "<table border=1px >";
		echo "<th>ID</th><th>Heading</th>";
		for( $i=1; $i<=$days; $i++) echo "<th>".$i."</th>";
		foreach( $matrix as $id => $arr ) {
			echo "<tr><th>".$id."</th><th>".$tasklookup[ $id ]."</th>";
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo ",(".$day.")";
				echo "<td>".$value."</td>";
			}
			echo "</tr>>";
		}
		echo "</table>";
		*/
		
		$pagedata['days'] = $days;
		$pagedata['progressmatrix'] = $matrix;
		$pagedata['tasklookup'] = $tasklookup;
		
		// Now finally create the diagram array of (x,y)-values where x = DAY since start-date
		$diagramactual = array();
		$progressmatrixtotal = array();
		for( $i = 0; $i < $days; $i++ ) {
				$diagramactual[ $i ] = array(  $i,  0 );
				$progressmatrixtotal[ $i ] = 0;
		}
		
		// Summarize column by column
		foreach( $matrix as $id => $arr ) {
			// echo "<br>ID = ".$id;
			for( $day = 0; $day < count($arr); $day++ ) {
				$value = intval( $arr[ $day ] );
				// echo "day = ".$day." = ".$value;
				$diagramactual[ $day ][ 1 ] = $diagramactual[ $day ][ 1 ] + $value;
				$progressmatrixtotal[ $day ] = $progressmatrixtotal[ $day ] + $value;
			}
		}
		
		$pagedata['diagramactual'] = $diagramactual;
		
		$pagedata['progressmatrixtotal'] = $progressmatrixtotal;
		
		// 
		// Weekly Efficency diagram
		//
		// the value of the last day of the week is the startvalue of the current week,
		// and thus the average effeciency is the diff between the "start" and end of the week / the available resoures for that week
		$i = 0;
		$starttime = strtotime($startdate);
		// $totaldays = floor( ($e - $s) / 86400 );
		$diagrameffiency = array();
		$currentweek = 0;
		$startvalue = $totalestimation;
		$endvalue = $totalestimation;
		foreach ($diagramactual as $row)
		{		
			$t = $starttime + ( 86400 * ($row[0] - 1) );
			$week = date( "W", $t );
			// echo "date; ".date("Y-M-d W",$t).", points = ".$row[1]."<br>";
			if( $week  != $currentweek ) {
				if( $currentweek > 0 ) $diagrameffiency[ intval($currentweek) ] = 100 * ($startvalue - $endvalue) ;
				// if( $currentweek > 0 )  echo "w; ".$currentweek.", points = ".$diagrameffiency[ intval($currentweek) ]."<br>";
				$startvalue = $endvalue;
				$currentweek = $week;
				
			}
			$endvalue = $row[1];
		}
		$currentweek = $week;
		$diagrameffiency[ intval($currentweek) ] = 100 * ($startvalue - $endvalue);
		//echo "w; ".$currentweek.", points = ".$diagrameffiency[ intval($currentweek) ]."<br>";
		$totaldiff = ($totalestimation-$endvalue);
		if( $days <= 0 ) $pagedata['velocity'] = 0;
		else $pagedata['velocity'] = $totaldiff / $days;
		
		$endtime = strtotime($enddate);
		$nowtime = strtotime("now");
		$t = $nowtime;
		if( $nowtime > $endtime ) $t = $endtime; 
		$sql="SELECT week( s.date, 3 ) as weeknum, sum( s.effort ) as tot FROM kanban_resource r , kanban_resource_schedule s WHERE r.id = s.resource_id AND r.project_id = ? AND s.date >= ? AND s.date <= ? group by weeknum order by s.date";
		$diagrameffort = array();
		$query = $this->db->query( $sql, array( $projectid, $startdate, date("Y-m-d",$t) ) );
		$i=0;
		$points = $totalestimation;
		$diagrameffort[$i] =  array( 0, $totalestimation );
		$i = $i + 1;
		$totalresourcediff = 0;
		foreach ($query->result_array() as $row)
		{
			$currentweek = intval( $row['weeknum'] );
			$tot = intval( $row['tot'] );
			//echo "w=".$currentweek.",t=".$tot."<br>";
			if( key_exists( $currentweek, $diagrameffiency) ) {
				if( $tot > 0 ) $diagrameffiency[ $currentweek ] = $diagrameffiency[ $currentweek ] / $tot;	
				else $diagrameffiency[ $currentweek ] = 0;
				$totalresourcediff = $totalresourcediff + $tot;	
				//echo "w".$currentweek.",".$diagrameffiency[ $currentweek ];
			}
		}
		//if( $totalresourcediff > 0 ) $teamefficiency = 100 * $totaldiff / $totalresourcediff;
		$teamefficiency = $pagedata['initialteamefficiency'];
		// echo "teamefficiency=".$teamefficiency;
		
		$efficiencydiagram = array();
		$i=0;
		foreach( $diagrameffiency as $week => $efficiency ) {
			$efficiencydiagram[$i++] = array( $week, $efficiency );
		}
		
		$pagedata['efficiencydiagram'] = $efficiencydiagram;
		if( $totalresourcediff > 0 ) $pagedata['teamefficiency'] = 100 * $totaldiff / $totalresourcediff;
		else $pagedata['teamefficiency'] = $teamefficiency;
		
		// Projected path
		$sql="SELECT datediff( s.date, ? ) as day, sum( s.effort ) as tot FROM kanban_resource r , kanban_resource_schedule s WHERE r.id = s.resource_id AND r.project_id = ? AND s.date >= ? AND s.date <= ? group by s.date order by s.date";
		$diagramprojected = array();
		$query = $this->db->query( $sql, array($startdate,$projectid,date("Y-m-d",$nowtime),$enddate) );
		$i=0;
		$points = 0;
		if( $days > 0 ) $points = $diagramactual[ $days-1 ][1];
		$diagramprojected[$i] =  array( $days-1, $points );
		$i = $i + 1;
		//echo "days=".$days."<br>";
		foreach ($query->result_array() as $row)
		{	
			$value = $row['tot'];
			$day = $row['day'];
			if( $day > ($days-1) ) {
				$points = $points - ( $value * $pagedata['initialteamefficiency'] / 100 );
				$diagramprojected[$i] =  array(  $day,  $points );
				$i = $i + 1;
				// echo "TeamInitialEfficiency: d=".$day.",v=".$value.",calc=".$value.",".$teamefficiency.",p=".$points."<br>";
			}
		}
		$pagedata['diagramprojected'] = $diagramprojected;
		
		## one more time, with the calculated team efficiency 
		$diagramprojected2 = array();
		$i=0;
		$points = 0;
		if( $days > 0 ) $points = $diagramactual[ $days-1 ][1];
		$diagramprojected2[$i] =  array( $days-1, $points );
		$i = $i + 1;
		//echo "days=".$days."<br>";
		foreach ($query->result_array() as $row)
		{	
			$value = $row['tot'];
			$day = $row['day'];
			if( $day > ($days-1) ) {
				$points = $points - ( $value * $pagedata['teamefficiency'] / 100 );
				$diagramprojected2[$i] =  array(  $day,  $points );
				$i = $i + 1;
				// echo "TeamActualEffeciency: d=".$day.",v=".$value.",calc=".$value.",".$teamefficiency.",p=".$points."<br>";
			}
		}
		$pagedata['diagramprojected2'] = $diagramprojected2;
		
		$diagramSprintGoal = array();
		$diagramSprintGoal[0] = array( $totaldays, 0 );
		$diagramSprintGoal[1] = array( $totaldays, $totalestimation  );
		
		$pagedata['diagramSprintGoal'] = $diagramSprintGoal;
		
		$this->load->view('kanban/status', $pagedata);
	}
	
	function tasklist($projectid,$sprintid=0) {

		kanban::redirectIfNoAccess( $projectid );
	
	
	
		$pagedata = array();
		
		if( $sprintid <=0 ) {
			$query = $this->db->query('SELECT max(id) as id FROM `kanban_sprint` where project_id = ?', array($projectid));
			$sprintid=0;
			if ($query->num_rows() > 0)	{
				$res = $query->result_array();		
				$sprintid = $res[0]['id'];
			} 
		}
		
		$pagedata['projectid'] = $projectid;
		$projectname = "no-name";
		$projectstartdate = '2010-01-01';
		$query = $this->db->query('SELECT id,name,startdate FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectname = $res[0]['name'];	
			$projectstartdate =  $res[0]['startdate'];	
		} 
		$pagedata['projectname'] = $projectname;	
		
		$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ?',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		} 
		$pagedata['sprintid'] = $sprintid;	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	
		
		$e = strtotime($enddate);
		$s = strtotime($startdate);
		$totaldays = floor( ($e - $s) / 86400 ); // strtotime($enddate) ÌÄå± strtotime($startdate);
		$pagedata['totaldays'] = $totaldays;
		
		$legend=array();
		$sql="SELECT i.id,i.heading,s.name as sprintname,i.enddate,week(i.enddate) as finishedweeknumber,i.startdate,week(i.startdate) as startweeknumber,estimation,priority FROM kanban_item i,kanban_sprint s  where i.project_id = ? and sprint_id = s.id order by s.enddate,i.enddate";
		$query = $this->db->query( $sql,array($projectid) );
		$i=0;
		foreach ($query->result_array() as $row)
		{			
			$legend[$i] = $row;
			$i++;			
		}		
		$pagedata['legend'] = $legend;
		$this->load->view('kanban/tasklist', $pagedata);
	
	}
	
	function settings($projectid,$sprintid=0) {
	

		kanban::redirectIfNoAccess( $projectid );
	
		$user_id = $this->session->userdata('id');
		
		$pagedata = array();
		
		$query = $this->db->query('SELECT id,name FROM kanban_project WHERE deleted = 0 AND user_id = ?', array($user_id));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$projects[$i]=$row;
			$i++;
		}
		$pagedata['projects'] = $projects;
		
		// **************************************
		// Project Properties
		$pagedata['initialteamefficiency'] = 80;	
		
		$sql="SELECT `key`,`value` FROM `kanban_project_properties` WHERE project_id = ? ";
		$query = $this->db->query($sql,array( $projectid ) );
		foreach ($query->result_array() as $row)
		{
			$pagedata[ $row['key'] ]=$row['value'];
		}
		
		if( $sprintid <=0 ) {
			$query = $this->db->query('SELECT max(id) as id FROM `kanban_sprint` where project_id = ?',array($projectid));
			$sprintid=0;
			if ($query->num_rows() > 0)	{
				$res = $query->result_array();		
				$sprintid = $res[0]['id'];
			} 
		}
		
		$pagedata['projectid'] = $projectid;
		$projectname = "no-name";
		$query = $this->db->query('SELECT id,name FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectname = $res[0]['name'];	
		} 
		$pagedata['projectname'] = $projectname;	
		
		$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ?',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		} 
		$pagedata['sprintid'] = $sprintid;	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	

		$sprints = array();

		$sql='SELECT id, name, startdate, enddate FROM `kanban_sprint` where project_id = ? ORDER BY startdate';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$sprints[$i]=$row;			
			$i++;
		}
		$pagedata['sprints'] = $sprints;

		$groups = array();
		$sql='SELECT id,name FROM kanban_group WHERE project_id = ? ORDER BY displayorder';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$groups[$i]=$row;			
			$i++;
		}
		$pagedata['groups'] = $groups;

		$workpackages = array();
		$sql='SELECT id,name FROM kanban_workpackage WHERE project_id = ? ORDER BY name';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$workpackages[$i]=$row;			
			$i++;
		}
		$pagedata['workpackages'] = $workpackages;
		
		$pagedata['generaltab'] = $this->load->view('kanban/settings_general', $pagedata, true);
		$pagedata['sprinttab'] = $this->load->view('kanban/settings_sprints', $pagedata, true);
		$pagedata['workpackagetab'] = $this->load->view('kanban/settings_workpackages', $pagedata, true);
		$pagedata['grouptab'] = $this->load->view('kanban/settings_groups', $pagedata, true);
		$pagedata['importtab'] = $this->load->view('kanban/settings_import', $pagedata, true);
		$pagedata['projecttab'] = $this->load->view('kanban/settings_project', $pagedata, true);

		$this->load->view('kanban/settings', $pagedata);
	}


	function newboard($projectid,$sprintid=0) {

		kanban::redirectIfNoAccess( $projectid );
	
		if( $sprintid==0 ) $sprintid = $this->kanbanmodel->getCurrentSprintIDOrDefaultToLast( $projectid );

		$pagedata = array();
		##
		## Project Details
		##
		$pagedata['projectid'] = $projectid;
		$pagedata['sprintid'] = $sprintid;	
		$res = $this->kanbanmodel->getProjectDetails( $projectid );
		$projectname = $res[0]['name'];	
		$projectstartdate =  $res[0]['startdate'];	
		$pagedata['projectname'] = $projectname;	
		##
		## Sprint Details
		##
		$res = $this->kanbanmodel->getSprintDetails($sprintid);
		$sprintname = $res[0]['name'];
		$startdate = $res[0]['startdate'];
		$enddate = $res[0]['enddate'];	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	
		##
		## Groups
		##
		$pagedata['groups'] = $this->kanbanmodel->getGroups($projectid);
		##
		## Tasks
		##
		$tasks = $this->kanbanmodel->getTasks($sprintid);
		##
		## Comment Count
		##
		$commentcounts = $this->kanbanmodel->getCommentCountForAllTasksInASprint($sprintid);
		foreach( $commentcounts as $cc ) {
			$tasks[ $cc['taskid'] ]['comments'] = $cc['count'];
		}
		##
		## List all known estimations (picking the latest)
		##
		$estimations = $this->kanbanmodel->getLatestEstimations($sprintid);
		foreach( $estimations as $est ) {
			$tasks[ $est['item_id'] ]['latest_estimation'] = $est['new_estimate'];
		}
		##
		## List all Time Reports for TODAY
		##
		$timereports = $this->kanbanmodel->getTodaysTimeReports($sprintid);
		foreach( $timereports as $tr ) {
			$tasks[ $tr['item_id'] ]['todays_time_reporting'] = $tr['hours'];
		}
		##
		## Workpages (list)
		##
		$pagedata['workpackages'] = $this->kanbanmodel->getAvailableWorkPackages($projectid);
		##
		## list of Resources
		##
		$pagedata['resources'] = $this->kanbanmodel->getAvailableResources($projectid);
		##
		## List all Sprints in this project
		##
		$pagedata['sprints'] = $this->kanbanmodel->getAvailableSprints($projectid);

		##
		## List all the timeline items
		##
		$projectStartAndEnd = $this->kanbanmodel->getProjectStartAndEndDates($projectid);
		$pagedata['timelineitems'] = $this->kanbanmodel->getTimeLineItems($projectid,$projectStartAndEnd[0],$projectStartAndEnd[1]);

		##
		## Render View (the board)
		##
		$pagedata['tasks'] = $tasks;
		$this->load->view('kanban/newboard', $pagedata);
	}

	function project($projectid,$sprintid=0)    {
	

		kanban::redirectIfNoAccess( $projectid );
	
		    
		if( $sprintid <=0 ) {
		
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
		}
		kanban::sprint($projectid,$sprintid);
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
	

	function sprint($projectid,$sprintid)    {

		kanban::redirectIfNoAccess( $projectid );
	
		    
		$pagedata = array();
		$pagedata['projectid'] = $projectid;
		$projectname = "no-name";
		$query = $this->db->query('SELECT id,name FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectname = $res[0]['name'];	
		} 
		$pagedata['projectname'] = $projectname;	
		
		$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ?',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		} 
		$pagedata['sprintid'] = $sprintid;	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	

		$sprints = array();

		$sql='SELECT id, name, startdate, enddate FROM `kanban_sprint` where project_id = ? ORDER BY startdate';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$sprints[$i]=$row;			
			$i++;
		}
		$pagedata['sprints'] = $sprints;

		
		$workpackages = array();
		$sql='SELECT id,name FROM kanban_workpackage WHERE project_id = ? ORDER BY name';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$workpackages[$i]=$row;			
			$i++;
		}
		$pagedata['workpackages'] = $workpackages;
	
		$groups = array();

		$sql='SELECT id,name,wip FROM kanban_group WHERE project_id = ? ORDER BY displayorder';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$groups[$i]=$row;			
			$i++;
		}
		$pagedata['groups'] = $groups;

		$commentcount = array();
		$sql = 'SELECT c.item_id as taskid, COUNT( c.id ) as count FROM kanban_item_comment c, kanban_item i WHERE c.item_id = i.id AND i.sprint_id = ? GROUP BY c.item_id';
		$query = $this->db->query( $sql, array($sprintid) );
		foreach ($query->result_array() as $row)
		{
			$commentcount[ $row['taskid'] ] =  $row['count'];
		}
		
		$now = strtotime("now");
		$tasks = array();
		$sql='SELECT k.id as taskid,heading,description,group_id,name as groupname,priority,estimation,colortag,added,enddate,owner_id FROM kanban_item k,kanban_group g WHERE group_id = g.id AND k.project_id = ? AND k.sprint_id = ? ORDER BY displayorder,priority DESC,added ASC,heading DESC';
		$query = $this->db->query($sql,array($projectid,$sprintid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			if( $row['enddate'] == '0000-00-00' ) {
				$row['age'] = kanban::countDays( strtotime($row['added']), $now );
			} else {
				$row['age'] = kanban::countDays( strtotime($row['added']), strtotime($row['enddate']) );
			}
			if( array_key_exists($row['taskid'], $commentcount ) ) {
				$row['comments'] = $commentcount[ $row['taskid'] ];
			} else {
				$row['comments'] = 0;
			}
			
			$tasks[$i]=$row;
			$i++;
		}
		$pagedata['tasks'] = $tasks;
		
		$tickers = array();

		$sql='SELECT id, enddate, message FROM `kanban_ticker` where project_id = ?  ORDER BY enddate';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$tickers[$i]=$row;			
			$i++;
		}
		$pagedata['tickers'] = $tickers;	
		
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
		
		$pagedata['timelineitems'] = $timelineitems;	
		
		$sql="SELECT sum(estimation) as total FROM `kanban_item` where sprint_id = ?";
		$query = $this->db->query( $sql,array($sprintid) );
		$totalestimation=0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			$totalestimation = $res[0]['total'];	
		} 
		$pagedata['totalestimation'] = $totalestimation;
		$e = strtotime($enddate);
		$s = strtotime($startdate);
		$totaldays = round( ($e - $s) / 86400 ); 
		$pagedata['totaldays'] = $totaldays;
		$pointsperday = round( $totalestimation / $totaldays, 2 );
		$diagramSprintGoal = array();
		$days = array();
		$sprintGoal = $totalestimation;
		for( $i = 0; $i < $totaldays; $i++ ) {
			$diagramSprintGoal[ $i ] = round( $sprintGoal, 2 );
			$days[ $i ] = $i;
			$sprintGoal = $sprintGoal - $pointsperday;
		}
		$pagedata['diagramSprintGoal'] = $diagramSprintGoal;
		$pagedata['days'] = $days;
		$sql="SELECT sum(estimation) as tot,datediff(enddate,?) as day FROM `kanban_item` where sprint_id = ? and not enddate = '0000-00-00' group by day";
		$diagramactual = array();
		$query = $this->db->query( $sql,array($startdate,$sprintid) );
		$i=0;
		$points = $totalestimation;
		$diagramactual[$i] =  $points;
		foreach ($query->result_array() as $row)
		{		
			$day = $row['day'];
			$pointsperday = round( $row['tot'] / ($day+1-$i), 2 );
			do {
				$points = $points - $pointsperday;	
				$diagramactual[$i] =  $points;
				$i=$i+1;
			} while( $i <= $day && $i < $totaldays );			
		}
		$pagedata['diagramactual'] = $diagramactual;

		$this->load->view('kanban/board', $pagedata);
	}

	function taskDetailsAsAnArray($projectid,$taskid) {
//		$projectid = $this->input->post('projectid');
//		$taskid = $this->input->post('taskid');
		$sql = 'SELECT heading,priority,description,estimation,colortag,sprint_id,workpackage_id,owner_id FROM kanban_item WHERE project_id = ? AND id = ?';
		$query = $this->db->query( $sql,array($projectid,$taskid) );
		$taskDetails = array();
		if ($query->num_rows() > 0)	{
			$row = $query->row();	
			$taskDetails[ 'heading' ] = $row->heading;
			$taskDetails[ 'taskdescription' ] = $row->description;
			$taskDetails[ 'priority' ] = $row->priority;
			$taskDetails[ 'estimation' ] = $row->estimation;	
			$taskDetails[ 'projectid' ] = $projectid;		
			$taskDetails[ 'sprintid' ] = $row->sprint_id;	
			$taskDetails[ 'taskid' ] = $taskid;		
			$taskDetails[ 'colortag' ] = $row->colortag;
			$taskDetails[ 'workpackage_id' ] = $row->workpackage_id;			
			$taskDetails[ 'ownerid' ] = $row->owner_id;			
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
		$sql = 'SELECT hours FROM kanban_time_reporting WHERE item_id = ? AND reporting_date <= CURDATE()';
		$query = $this->db->query( $sql, array( $taskid ) );
		$taskDetails[ 'todays_time_reporting' ] = 0;
		if ($query->num_rows() > 0)	{
			$row = $query->row();	
			$taskDetails[ 'todays_time_reporting' ] = $row->hours;
		}
		return $taskDetails;
	}
	
	function taskdetails($projectid,$taskid) {
		$taskDetails = kanban::taskDetailsAsAnArray($projectid,$taskid);
		$jsondata = json_encode( $taskDetails );
		echo $jsondata; 
	}

	function addtask() {
		$projectid = $this->input->post('projectid');
		$sprintid = $this->input->post('sprintid');
		$group = $this->input->post('group');
		$heading = $this->input->post('heading');
		$priority = $this->input->post('priority');
		if( is_numeric( $priority ) != TRUE )  $priority = 0;
		$taskdescription = $this->input->post('taskdescription');
		$estimation = $this->input->post('estimation');
		if( is_numeric( $estimation ) != TRUE )  $estimation = 0;
		$colortag = $this->input->post('colortag');
		$workpackage_id = $this->input->post('workpackage_id');
		$today = date( "Y-m-d" );
		echo "proj id=".$projectid."<br>";
		echo "group=".$group."<br>";
		echo "head=".$heading."<br>";
		echo "prio=".$priority."<br>";	
		echo "desc=".$taskdescription."<br>";
		echo "est=".$estimation."<br>";
		echo "tag=".$colortag."<br>";
		$data = array(
			'project_id' => $projectid,
			'group_id' => $group,
			'heading' => $heading,
			'priority' => $priority,
			'description' => $taskdescription,
			'estimation' => $estimation,
			'colortag' => $colortag,
			'added' => $today,
			'startdate' => '0000-00-00',
			'enddate' => '0000-00-00',
			'sprint_id' => $sprintid,
		    'workpackage_id' => $workpackage_id
			);
		$this->db->insert('kanban_item', $data);	
		$query = $this->db->query('SELECT max(id) as lastid FROM kanban_item');
		$taskid = 0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$taskid = $res[0]['lastid'];	
		} 
		$change = "Added new Task\n";
		$change = $change."ID: ".$taskid."\n";
		$change = $change."Heading: ".$heading."\n";
		$change = $change."Priority: ".$priority."\n";
		$change = $change."Estimation: ".$estimation."\n";
		$change = $change."Description: ".$taskdescription."\n";
		kanban::addToHistory($projectid,$change);
	}
	
	function addToHistory($project_id,$change) {
		$data = array(
			'project_id' => $project_id,
			'who' => 'N/A',
			'change' => $change
		);
		$this->db->insert('kanban_history',$data);
	}

	function deletetask() {		
		$projectid = $this->input->post('projectid');
		$taskid = $this->input->post('taskid');
		$heading = $this->input->post('heading');
		$this->db->where('id', $taskid);
		$this->db->delete('kanban_item');	
		$change = "Deleted Task\n";
		$change = $change."ID: ".$taskid."\n";
		$change = $change."Heading: ".$heading."\n";
		kanban::addToHistory($projectid,$change);					
	}

	function updatetask() {
		$projectid = $this->input->post('projectid');
		$taskid = $this->input->post('taskid');
		$heading = $this->input->post('heading');
		$priority = $this->input->post('priority');
		if( is_numeric( $priority ) != TRUE )  $priority = 0;
		$taskdescription = $this->input->post('taskdescription');
		$estimation = $this->input->post('estimation');
		if( is_numeric( $estimation ) != TRUE )  $estimation = 0;
		$todays_estimation = $this->input->post('todays_estimation');
		if( is_numeric( $todays_estimation ) != TRUE )  $todays_estimation = 0;
		$todays_time_reporting = $this->input->post('todays_time_reporting');
		if( is_numeric( $todays_time_reporting ) != TRUE )  $todays_time_reporting = 0;
		$colortag = $this->input->post('colortag');
		$workpackage_id = $this->input->post('workpackage_id');
		$newsprintid = $this->input->post('newsprintid');
		$today = date( "Y-m-d" );

		$oldTaskDetails = kanban::taskDetailsAsAnArray($projectid,$taskid);

		$data = array(			
			'heading' => $heading,
			'priority' => $priority,
			'description' => $taskdescription,
			'estimation' => $estimation,
			'colortag' => $colortag,
			'sprint_id' => $newsprintid,
			'workpackage_id' => $workpackage_id
			);
		$this->db->where('id', $taskid);
		$this->db->update('kanban_item', $data);			
		
		$sql="INSERT INTO kanban_progress (item_id,new_estimate,date_of_progress) VALUES (?,?,?) ON DUPLICATE KEY UPDATE item_id =?, new_estimate = ?, date_of_progress = ?";
		$query = $this->db->query($sql,array( $taskid,$todays_estimation,$today,$taskid,$todays_estimation,$today) ); 

		$sql="INSERT INTO kanban_time_reporting (item_id,hours,reporting_date) VALUES (?,?,?) ON DUPLICATE KEY UPDATE item_id =?, hours = ?, reporting_date = ?";
		$query = $this->db->query($sql,array( $taskid,$todays_time_reporting,$today,$taskid,$todays_time_reporting,$today) ); 

		$change = "Task Updated\n";
		$change = $change."From :\n";
		$change = $change."ID: ".$taskid."\n";
		$change = $change."Heading: ".$oldTaskDetails['heading']."\n";
		$change = $change."Priority: ".$oldTaskDetails['priority']."\n";
		$change = $change."Estimation: ".$oldTaskDetails['estimation']."\n";
		$change = $change."Todays Estimation: ".$oldTaskDetails['todays_estimation']."\n";
		$change = $change."Description: ".$oldTaskDetails['taskdescription']."\n";
		$change = $change."\nTo :\n";
		$change = $change."Heading: ".$heading."\n";
		$change = $change."Priority: ".$priority."\n";
		$change = $change."Estimation: ".$estimation."\n";
		$change = $change."Todays Estimation: ".$todays_estimation."\n";
		$change = $change."Description: ".$taskdescription."\n";
		
		kanban::addToHistory($projectid,$change);
	}

	function move() {
		$projectid = $this->input->post('projectid');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$last = $this->input->post('last');
		$taskid = $this->input->post('task');
		$today = date( "Y-m-d" );
		echo "from=".$from."<br>";
		echo "to=".$to."<br>";
		echo "task=".$taskid."<br>";	
		echo "last=".$last."<br>";	
		if( $to == $last ) {
			$data = array(
				'group_id' => $to,
				'enddate' => $today				
				);		
		} else {
			$data = array(
				'group_id' => $to,
				'startdate' => $today,
				'enddate' => '0000-00-00'
				);		
		}
		$this->db->where('id', $taskid);
		$this->db->update('kanban_item', $data);
		echo "moved in db!";
		$fromTitle = 'N/A';
		$toTitle = 'N/A';
		$taskidDetails = kanban::taskDetailsAsAnArray($projectid,$taskid);
		$groupDetails = kanban::groupsAsAnArray($projectid);
		foreach( $groupDetails as $groupDetail )  {
			if( $groupDetail['id'] == $from ) $fromTitle = $groupDetail['name'];
			if( $groupDetail['id'] == $to ) $toTitle = $groupDetail['name'];
		}
		$change = "Task Moved\n";
		$change = $change."ID: ".$taskid."\n";
		$change = $change."Heading: ".$taskidDetails['heading']."\n";
		$change = $change."From: ".$fromTitle."\n";
		$change = $change."To: ".$toTitle."\n";
		kanban::addToHistory($projectid,$change);
	}
	
	function editworkpackage() {
		$name = $this->input->post('editworkpackage_name');
		$wpid = $this->input->post('editworkpackage_id');
		$projectid = $this->input->post('editworkpackage_projectid');
		$data = array(
			'name' => $name
		);
		$this->db->where('id', $wpid);
		$this->db->update('kanban_workpackage', $data);		
	}
	
	function editprojectname() {
		$name = $this->input->post('editproject_name');
		$id = $this->input->post('editproject_id');
		$data = array(
			'name' => $name
		);
		$this->db->where('id', $id);
		$this->db->update('kanban_project', $data);		
		echo 'updated '+$id;	
	}
	
	function deleteproject() {
		$id = $this->input->post('deleteproject_id');
		$data = array(
			'deleted' => 1
		);
		$this->db->where('id', $id);
		$this->db->update('kanban_project', $data);	
		echo 'deleted '+$id;	
	}
	
	function editgroup() {
		$name = $this->input->post('editgroup_name');
		$wip = $this->input->post('editgroup_wip');
		$groupid = $this->input->post('editgroup_groupid');
		$projectid = $this->input->post('editgroup_projectid');
		$data = array(
			'name' => $name,
			'wip' => $wip
		);
		$this->db->where('id', $groupid);
		$this->db->update('kanban_group', $data);		
	}
	
	function deleteworkpackage() {
		$wpid = $this->input->post('deleteworkpackage_id');
		$projectid = $this->input->post('deletegroup_projectid');
		//
		// First lets delete all the tasks in this workpackage
		// 
		$this->db->where('workpackage_id', $wpid);
		$this->db->delete('kanban_item'); 	
		
		//
		// Delete the workpackage
		// 
		$this->db->where('id', $wpid);
		$this->db->delete('kanban_workpackage'); 
	}
	
	function deletegroup() {
		$groupid = $this->input->post('deletegroup_groupid');
		$firstgroupid = $this->input->post('deletegroup_firstgroupid');
		$projectid = $this->input->post('deletegroup_projectid');
		//
		// First lets move the tasks in this group before we delete it
		// 
		$data = array(
			'group_id' => $firstgroupid
		);
		$this->db->where('group_id', $groupid);
		$this->db->update('kanban_item', $data);		
		
		//
		// Delete the group
		// 
		$this->db->where('id', $groupid);
		$this->db->delete('kanban_group'); 
	}
	
	function addworkpackage_helper($name,$projectid) {
		$data = array(
			'name' => $name,
			'project_id' => $projectid
			);
		$this->db->insert('kanban_workpackage', $data);	
		$id = 0;
		$sql="SELECT id FROM kanban_workpackage WHERE name = ? AND project_id = ?";
		$query = $this->db->query( $sql, array($name,$projectid) );
		if ($query->num_rows() > 0)	{
			$row = $query->row();	
			$id = $row->id;
		}
		return $id;
	}
	
	function addworkpackage() {
		$name = $this->input->post('newgworkpackage_name');
		echo "name=".$name."<br>";
		$projectid = $this->input->post('newgworkpackage_projectid');
		echo "projectid=".$projectid."<br>";
		
		kanban::addworkpackage_helper( $name, $projectid );
		
		echo "inserted into db!";
	}



	function addgroup() {
		$wip = $this->input->post('newgroup_wip');
		$name = $this->input->post('newgroup_name');
		echo "name=".$name."<br>";
		$projectid = $this->input->post('newgroup_projectid');
		echo "projectid=".$projectid."<br>";
		$sql='SELECT id FROM kanban_group WHERE project_id = ? order by displayorder,id;';
		$query = $this->db->query( $sql,array($projectid) );
		//
		// renumber all the existing groups
		//
		$order=1;
		foreach ($query->result_array() as $row)
		{
		    $data = array(
				'displayorder' => $order
				);
			$this->db->where('id', $row['id']);
			$this->db->update('kanban_group', $data);	
			$order=$order+1;
			if($order==2) {
				$order=3; 
			}
		}
		//
		// add the NEW group to the second position
		//
		kanban::addgroup_helper( $projectid, $name, 2, $wip );
	}
	
	
	function addgroup_helper( $projectid, $name, $displayorder, $wip ) {
		$data = array(
			'name' => $name,
			'wip' => $wip,
			'project_id' => $projectid,
			'displayorder' => $displayorder
			);
		$this->db->insert('kanban_group', $data);	
		echo "inserted into db!";
	}

	function changegrouporder() {
		$grouporder = rtrim( $this->input->post('grouporder'), ',');
		echo "grouporder=".$grouporder."<br>";
		$projectid = $this->input->post('projectid');
		echo "projectid=".$projectid."<br>";
		$listofgroups = explode( ",", $grouporder );
		$index = 1;
		foreach( $listofgroups as $groupid ) {
			$data = array(
				'displayorder' => $index
				);
			$this->db->where('id', $groupid);
			$this->db->update('kanban_group', $data);	
			echo "id=".$groupid.",order=".$index."\n<br>";
			$index++;
		}
 
	}

	function groupdetails($projectid,$groupid) {

		$sql = 'SELECT name, wip FROM kanban_group WHERE id = ?';
		$query = $this->db->query( $sql,array($groupid) );
		$jsonarray = array();
		if ($query->num_rows() > 0)	{
			$row = $query->row();	
			$jsonarray[ 'name' ] = $row->name;
			$jsonarray[ 'wip' ] = $row->wip;
		} 
		$jsondata = json_encode($jsonarray);
		echo $jsondata; 
	}
	
	function editgeneralsettings() {
		$projectid = $this->input->post('generalsettingsform_projectid');
		
		
		$initialteamefficiency = $this->input->post('generalsettingsform_initialteamefficiency');				
		$sql="INSERT INTO `kanban_project_properties`(`project_id`, `key`, `value`) VALUES (?,?,?)  ON DUPLICATE KEY UPDATE  value = ?";
		$query = $this->db->query($sql,array( $projectid, 'initialteamefficiency', $initialteamefficiency, $initialteamefficiency ) );
		
		echo "General Settings updated!";
	}
	
	

	function addsprint() {
		$name = $this->input->post('newsprint_name');
		$startdate = $this->input->post('newsprint_startdate');
		$enddate = $this->input->post('newsprint_enddate');
		$projectid = $this->input->post('newsprint_projectid');
		
		$data = array(
			'name' => $name,
			'project_id' => $projectid,
			'startdate' => $startdate,
			'enddate' => $enddate
			);
		$this->db->insert('kanban_sprint', $data);	
		$sql='SELECT id FROM `kanban_resource` where project_id = ?';
		$query = $this->db->query($sql,array($projectid));
		foreach ($query->result_array() as $row)
		{
			kanban::populateResourceSchedule( $projectid, $row['id'] );			
		}
	}
	
	function editsprint($projectid) {
		$name = $this->input->post('editsprint_name');
		$sprintid = $this->input->post('editsprint_sprintid');
		$startdate = $this->input->post('editsprint_startdate');
		$enddate = $this->input->post('editsprint_enddate');
		
		$query = $this->db->query('SELECT name, startdate, enddate FROM `kanban_sprint` where id = ?',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			$oldsprintname = $res[0]['name'];
			$oldstartdate = $res[0]['startdate'];
			$oldenddate = $res[0]['enddate'];	
		} 
		
		$data = array(
			'name' => $name,
			'startdate' => $startdate,
			'enddate' => $enddate
		);
		$this->db->where('id', $sprintid);
		$this->db->update('kanban_sprint', $data);
		
		if( $startdate != $oldstartdate || $enddate != $oldenddate ) {
			$sql='SELECT id FROM `kanban_resource` where project_id = ?';
			$query = $this->db->query($sql,array($projectid));
			foreach ($query->result_array() as $row)
			{
				kanban::populateResourceSchedule( $projectid, $row['id'] );			
			}
			
		}
				
	}

	function deletesprint() {
		$sprintid = $this->input->post('deletesprint_sprintid');
		$projectid = $this->input->post('deletesprint_projectid');
		//
		// First lets delete all tasks that belongs to this sprint
		// 
		$this->db->where('sprint_id', $sprintid);
		$this->db->delete('kanban_item');		
		
		//
		// Delete the sprint
		// 
		$this->db->where('id', $sprintid);
		$this->db->delete('kanban_sprint'); 
	}
	
	function movetasksbetweensprints() {
		$from = $this->input->post('movetasksbetweensprints_from');
		$to = $this->input->post('movetasksbetweensprints_to');
		$projectid = $this->input->post('movetasksbetweensprints_projectid');

		// We do not create doubles
		if( $to == $from ) return;

		// Figure out which is the last group, i.e. containing the ones we should NOT move
		$sql = 'SELECT id,displayorder FROM kanban_group WHERE project_id = ? order by displayorder asc limit 1';
		$query = $this->db->query( $sql,array($projectid) );
		$firstgroupid = 0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$firstgroupid = $res[0]['id'];	
		} 

		// Figure out which is the last group, i.e. containing the ones we should NOT move
		$sql = 'SELECT id,displayorder FROM kanban_group WHERE project_id = ? order by displayorder desc limit 1';
		$query = $this->db->query( $sql,array($projectid) );
		$lastgroupid = 0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$lastgroupid = $res[0]['id'];	
		} 
		
		// SELECT k.id as taskid,heading,description,group_id,name as groupname,priority,estimation,colortag,k.startdate,k.enddate,k.added FROM kanban_item k,kanban_group g WHERE group_id = g.id AND k.project_id = 3 AND k.sprint_id = 1 AND not k.group_id = 5 ORDER BY displayorder,priority
		$sql = 'SELECT k.id as taskid,heading,description,group_id,name as groupname,priority,estimation,colortag,k.startdate,k.enddate,k.added,workpackage_id FROM kanban_item k,kanban_group g WHERE group_id = g.id AND k.project_id = ? AND k.sprint_id = ? AND not k.group_id = ? ORDER BY displayorder,priority';
		$query = $this->db->query($sql,array($projectid,$from,$lastgroupid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$tasks[$i]=$row;
			$sprintid = $to;
			$group = $row['group_id']; 
			// we move to the first group, as that is where we always start a sprint
			// $group = $firstgroupid;
			$heading = $row['heading'];
			$priority = $row['priority'];
			$taskdescription = $row['description'];
			$estimation  = $row['estimation'];
			$colortag  = $row['colortag'];
			$startdate = $row['startdate'];
			$enddate= $row['enddate'];
			$added = $row['added'];
			$workpackage_id = $row['workpackage_id'];
			kanban::addtaskwithdetails( $projectid,$sprintid,$group,$heading,$priority,$taskdescription,$estimation,$colortag,$startdate,$enddate,$added,$workpackage_id );
			$i++;
		}
		echo "Moved ".$i." tasks";
	}
	
	function addtaskwithdetails( $projectid,$sprintid,$group,$heading,$priority,$taskdescription,$estimation,$colortag,$startdate,$enddate,$added,$workpackage_id  ) 
	{
		$today = date( "Y-m-d" );
		echo "proj id=".$projectid."<br>";
		echo "group=".$group."<br>";
		echo "head=".$heading."<br>";
		if( is_numeric( $priority ) != TRUE )  $priority = 0;
		echo "prio=".$priority."<br>";	
		echo "desc=".$taskdescription."<br>";
		if( is_numeric( $estimation ) != TRUE )  $estimation = 0;
        echo "est=".$estimation."<br>";
		echo "tag=".$colortag."<br>";
		$data = array(
			'project_id' => $projectid,
			'group_id' => $group,
			'heading' => $heading,
			'priority' => $priority,
			'description' => $taskdescription,
			'estimation' => $estimation,
			'colortag' => $colortag,
			'added' => $added,
			'startdate' => $startdate,
			'enddate' => '0000-00-00',
			'sprint_id' => $sprintid,
			'workpackage_id' => $workpackage_id
			);
		$this->db->insert('kanban_item', $data);	
		$query = $this->db->query('SELECT max(id) as lastid FROM kanban_item');
		$taskid = 0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$taskid = $res[0]['lastid'];	
		} 
 		echo "task-id=".$taskid."<br>";	
		echo "inserted into db!";
	}
	
	function sprintdetails($projectid,$sprintid) {

		$sql = 'SELECT name, startdate, enddate FROM kanban_sprint WHERE id = ?';
		$query = $this->db->query( $sql,array($sprintid));
		$jsonarray = array();
		if ($query->num_rows() > 0)	{
			$row = $query->row();	
			$jsonarray[ 'name' ] = $row->name;
			$jsonarray[ 'startdate' ] = $row->startdate;
			$jsonarray[ 'enddate' ] = $row->enddate;
		} 
		$jsondata = json_encode($jsonarray);
		echo $jsondata; 
	}
	
	function importtaskstosprint() {
		$projectid = $this->input->post('importtaskstosprint_projectid');
		$sprintid = $this->input->post('importtaskstosprint_sprintid');
		$text = $this->input->post('importtaskstosprint_text');
		$today = date( "Y-m-d" );
		$query = $this->db->query('SELECT id FROM kanban_group WHERE project_id = ? order by displayorder,id limit 1;',array($projectid));
		$groupid = 1; 
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$groupid = $res[0]['id'];	
		} 
		
		$workpackages = array();
		$sql='SELECT id,name FROM kanban_workpackage WHERE project_id = ? ORDER BY name';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$workpackages[$row['name']]=$row['id'];			
			$i++;
		}
		
		$textlines = explode("\n",$text);
		$row=0;
		foreach($textlines as $line){
			$heading='';
			$description='';
			$priority=0;
			$estimation=0;
			$colortag=0; // 1=Yellow,2=Green,3=Red
			$added='0000-00-00';
			$workpackagename='';
			$line=trim($line);
			if(strlen($line)<=0) continue;
			list($heading,$description,$priority,$estimation,$colortag,$added,$workpackagename) = explode( ";", $line ) + Array( "heading","",0,0,0,"0000-00-00","Default WP");
			if( $colortag < 1 || $colortag > 5 ) {
				echo "WARNING! colortag not supported ($colortag) will default to 1(Yellow)<br>\n";
				$colortag = 1;
			}
			if( $added == '0000-00-00' || $added == '' ) {
				$added = $today;
			}
			$workpackage_id = 0;
			if( array_key_exists( $workpackagename, $workpackages ) ) {
				$workpackage_id = $workpackages[ $workpackagename ];
			} else {
				$workpackage_id = kanban::addworkpackage_helper( $workpackagename, $projectid );
				$workpackages[ $workpackagename ] = $workpackage_id;
			}
			echo $row." line ".$line."\n";
			echo $row.":".$heading."|".$description."|".$priority."|".$estimation."|".$colortag."|".$added."|".$workpackagename."<br>\n";
			$data = array(
			'project_id' => $projectid,
			'group_id' => $groupid,
			'heading' => $heading, 
			'priority' => $priority,
			'description' => $description,
			'estimation' => $estimation,
			'colortag' => $colortag,
			'added' => $added,
			'startdate' => '0000-00-00',
			'enddate' => '0000-00-00',
			'sprint_id' => $sprintid,
			'workpackage_id' => $workpackage_id,
			);
			$this->db->insert('kanban_item', $data);
			echo "added to db!\n<br>";	
			$row=$row+1;
		}
	}

	
	function addproject() {
		$name = $this->input->post('name');
		// echo "name=".$name."<br>";
		$user_id = $this->session->userdata('id');
		//
		// Add project
		//
		$today = date( "Y-m-d" );
		$data = array(
			'name' => $name,
			'user_id' => $user_id,
			'startdate' => $today
			);
		$this->db->insert('kanban_project', $data);	
		$query = $this->db->query('SELECT max(id) as lastid FROM kanban_project');
		$projectid = 0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectid = $res[0]['lastid'];	
		} 
		
		//
		// Add a initial sprint, with dummy values
		// 
		$today = date( "Y-m-d" );
		$sprintname = "first sprint";
		$startdate = $today;
		$in4weeks = date( "Y-m-d" , (time()  + (4 * 7 * 24 * 60 * 60) ) );
		$enddate = $in4weeks;
		$data = array(
			'name' => $name,
			'project_id' => $projectid,
			'startdate' => $startdate,
			'enddate' => $enddate
			);
		$this->db->insert('kanban_sprint', $data);	
		$query = $this->db->query('SELECT max(id) as lastid FROM kanban_sprint');
		$sprintid = 0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			$sprintid = $res[0]['lastid'];	
		} 
		

		//
		// Add 'unassigned', 'Ongoing' and 'finished' groups
		//		
		$wip = 0;
		$name = 'Unassigned';
		$displayorder = 1;
		kanban::addgroup_helper( $projectid, $name, $displayorder, $wip );
		
		$name = 'Ongoing';
		$displayorder = 2;
		kanban::addgroup_helper( $projectid, $name, $displayorder, $wip );
		
		$name = 'Finished';
		$displayorder = 3;
		kanban::addgroup_helper( $projectid, $name, $displayorder, $wip );
		
		//
		// Add default workpackage
		// 
		kanban::addworkpackage_helper( "Default", $projectid );
		
		$redirect_to_url="/kanban/project/".$projectid;
		header("Location: ".$redirect_to_url );
	}


	function tickers($projectid,$sprintid)    {
		$pagedata = array();
		$pagedata['projectid'] = $projectid;
		$projectname = "no-name";
		$query = $this->db->query('SELECT id,name FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectname = $res[0]['name'];	
		} 
		$pagedata['projectname'] = $projectname;	
		
		$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ?',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		} 
		$pagedata['sprintid'] = $sprintid;	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	

		$tickers = array();

		$sql='SELECT id, enddate, message FROM `kanban_ticker` where project_id = ? ORDER BY enddate';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$tickers[$i]=$row;			
			$i++;
		}
		$pagedata['tickers'] = $tickers;		
		
		$this->load->view('kanban/tickers', $pagedata);
	}

	
	function timeline($projectid,$sprintid)    {
		$pagedata = array();
		$pagedata['projectid'] = $projectid;
		$projectname = "no-name";
		$query = $this->db->query('SELECT id,name FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectname = $res[0]['name'];	
		} 
		$pagedata['projectname'] = $projectname;	
		
		$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ?',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		} 
		$pagedata['sprintid'] = $sprintid;	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	

		$timelineitems = array();

		$sql='SELECT id, date, headline FROM `kanban_timeline` where project_id = ? ORDER BY date';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$timelineitems[$i]=$row;			
			$i++;
		}
		$pagedata['timelineitems'] = $timelineitems;		
		
		$this->load->view('kanban/timeline_admin', $pagedata);
	}
	
	

	function deleteticker($tickerid) {		
		$this->db->where('id', $tickerid);
		$this->db->delete('kanban_ticker');						
	}
	
	
	function addticker() {
		$msg = $this->input->post('msg');
		$enddate = $this->input->post('enddate');
		$projectid = $this->input->post('projectid');
		$sprintid = $this->input->post('sprintid');
		
		$data = array(
			'message' => $msg,
			'project_id' => $projectid,
			'enddate' => $enddate
			);
		$this->db->insert('kanban_ticker', $data);	
		
		$redirect_to_url="/kanban/tickers/".$projectid."/".$sprintid;
		header("Location: ".$redirect_to_url );
	}
	
	function deletetimeline($projectid,$timelineid) {		
		$this->db->where('id', $timelineid);	
		$this->db->where('project_id', $projectid);
		$this->db->delete('kanban_timeline');						
	}
	
	function addtimelinemessage() {
		$msg = $this->input->post('msg');
		$date = $this->input->post('date');
		$projectid = $this->input->post('projectid');
		$sprintid = $this->input->post('sprintid');
		
		$data = array(
			'headline' => $msg,
			'project_id' => $projectid,
			'date' => $date
			);
		$this->db->insert('kanban_timeline', $data);	
		
		$redirect_to_url="/kanban/timeline/".$projectid."/".$sprintid;
		header("Location: ".$redirect_to_url );
	}
	
	
	
	function resources($projectid,$sprintid=0) {
	
		kanban::redirectIfNoAccess( $projectid );
	
		$pagedata = array();
		
		if( $sprintid <=0 ) {
			$query = $this->db->query('SELECT max(id) as id FROM `kanban_sprint` WHERE project_id = ?',array($projectid));
			$sprintid=0;
			if ($query->num_rows() > 0)	{
				$res = $query->result_array();		
				$sprintid = $res[0]['id'];
			} 
		}
		
		$pagedata['projectid'] = $projectid;
		$projectname = "no-name";
		$projectstartdate = '2010-01-01';
		$query = $this->db->query('SELECT id,name,startdate FROM kanban_project WHERE id = ?',array($projectid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$projectname = $res[0]['name'];	
			$projectstartdate =  $res[0]['startdate'];	
		} 
		$pagedata['projectname'] = $projectname;	
		
		$query = $this->db->query('SELECT id, name, startdate, enddate FROM `kanban_sprint` where id = ?',array($sprintid));
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$sprintid = $res[0]['id'];
			$sprintname = $res[0]['name'];
			$startdate = $res[0]['startdate'];
			$enddate = $res[0]['enddate'];	
		} 
		$pagedata['sprintid'] = $sprintid;	
		$pagedata['sprintname'] = $sprintname;	
		$pagedata['startdate'] = $startdate;	
		$pagedata['enddate'] = $enddate;	
		
		
		$sql='SELECT r.id as id, r.name as name, s.date as date, s.effort as effort FROM kanban_resource_schedule s, kanban_resource r WHERE r.project_id = ? AND r.id = s.resource_id AND s.date >= ? AND s.date <= ? ORDER BY r.id,s.date';
		$query = $this->db->query($sql, array( $projectid, $startdate, $enddate ) );
		$i=0;
		$plan = array();
		foreach ($query->result_array() as $row)
		{
			$plan[$i]=$row;			
			$i++;
		}
		$pagedata['plan'] = $plan;		
		
		$this->load->view('kanban/resource_editing', $pagedata);
	}
	
	function updateschedule($projectid) {
	
		kanban::redirectIfNoAccess( $projectid );
	
		$startdate = $this->input->post('startdate');
		$starttime = strtotime($startdate);
		$data = $this->input->post('data');
		
		$arrayOfResources = explode( ';', $data );
		
		foreach( $arrayOfResources as $resourceData ) {
			$arr = explode( ',', $resourceData );
			$id = $arr[0];
			$t = $starttime;
			for( $i=2; $i<count( $arr ); $i++ ) {
				// echo $id.':'.date('Y-m-d',$t).' = '.$arr[ $i ].'<br>';
				$sql="INSERT INTO kanban_resource_schedule (resource_id,date,effort) VALUES (?,?,?) ON DUPLICATE KEY UPDATE resource_id = ?,date = ?,effort = ?";
				$query = $this->db->query($sql,array( $id, date('Y-m-d',$t), $arr[ $i ], $id, date('Y-m-d',$t), $arr[ $i ]));
				// echo "<br>QUERY: ".$this->db->last_query();
				$t = strtotime( "+1 day", $t );
			}
		}
		// echo "<br>RESULT: OK!";
	}
	
	function deleteresource($projectid) {
	
		kanban::redirectIfNoAccess( $projectid );
	
		$id = $this->input->post('id');

		$this->db->where('id', $id );
		$this->db->delete('kanban_resource');	


		$this->db->where('resource_id', $id );
		$this->db->delete('kanban_resource_schedule');	
	}

	function renameresource($projectid) {
	
		kanban::redirectIfNoAccess( $projectid );
	
		$id = $this->input->post('id');
		$newname = $this->input->post('newname');


		$data = array(			
			'name' => $newname
			);
		$this->db->where('id', $id);
		$this->db->update('kanban_resource', $data);

	}
	
	function populateResourceSchedule( $projectid, $resourceid ) {
		
		$sql='SELECT min( startdate ) as start, max( enddate ) as end FROM kanban_sprint WHERE project_id = ?';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		$plan = array();
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();	
			$startdate = $res[0]['start'];	
			$enddate = $res[0]['end'];	
			$starttime = strtotime($startdate);
			$endtime = strtotime($enddate);
			$t = $starttime;
			while( $t <= $endtime ) {
				$data = array(
					$resourceid,
					date( 'Y-m-d', $t ),
					0
				);
				$sql="INSERT IGNORE INTO kanban_resource_schedule (resource_id,date,effort) VALUES (?,?,?)";
				$query = $this->db->query($sql,$data);
				$t = strtotime( "+1 day", $t );
				$i++;
				if( $i > 1000 ) break; // safety so that we do not fill up the db forever !!!!
			}
			
		}
	} 
	
	function addresource($projectid) {
	
		kanban::redirectIfNoAccess( $projectid );
	
		$newname = $this->input->post('newname');
		$data = array(
			'project_id' => $projectid,
			'name' => $newname
			);
		$this->db->insert('kanban_resource', $data);	
		$query = $this->db->query('SELECT max(id) as id FROM kanban_resource');
		$resourceid = 0;
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();	
			$resourceid = $res[0]['id'];	
		} 
 		$jsonarray = array();
		$jsonarray[ 'id' ] = $resourceid;
 		$jsondata = json_encode($jsonarray);
		
 		kanban::populateResourceSchedule( $projectid, $resourceid );
		
		echo $jsondata; 
	}
	
	function taskcommentsformandlegend($projectid,$taskid) {
		$pagedata=array();
		$pagedata['projectid'] = $projectid;
		$pagedata['taskid'] = $taskid;
		$sql = 'SELECT id,item_id,timestamp,who,comment FROM kanban_item_comment WHERE item_id = ? ORDER BY timestamp DESC';
		$query = $this->db->query( $sql, array($taskid) );
		$comments = array();
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$comments[$i]=$row;
			$i++;
		}
		$pagedata['taskcomments'] = $comments;
		$this->load->view('kanban/taskcomments', $pagedata);
	}
	
	function addtaskcomment() {
	 	$projectid = $this->input->post('projectid');
		if( kanban::hasAccess(  $projectid ) != TRUE ) {
			return;
		}
	 	$taskid = $this->input->post('taskid'); 
		$who = $this->input->post('who');
		$comment = $this->input->post('comment');
		$data = array(
			'item_id' => $taskid,
			'who' => $who,
			'comment' => $comment
			);
		$this->db->insert('kanban_item_comment', $data);	
		
		
		$taskidDetails = kanban::taskDetailsAsAnArray($projectid,$taskid);
		$change = "Task Comment added\n";
		$change = $change."ID: ".$taskid."\n";
		$change = $change."Heading".$taskidDetails['heading']."\n";
		$change = $change."New Comment: ".$comment."\n";
		kanban::addToHistory($projectid,$change);
	}
	
	function deletetaskcomment($projectid, $commentid ) {
		if( kanban::hasAccess(  $projectid ) != TRUE ) {
			return;
		}
		
		$sql = 'SELECT id,item_id,timestamp,who,comment FROM kanban_item_comment WHERE id = ? ORDER BY timestamp DESC';
		$query = $this->db->query( $sql, array($commentid) );
		if ($query->num_rows() > 0)	{
			$res = $query->result_array();		
			// print_r( $release );		
			$commentid = $res[0]['id'];
			$taskid = $res[0]['item_id'];
			$comment = $res[0]['comment'];	
		} 
	
		$this->db->where('id', $commentid);
		$this->db->delete('kanban_item_comment');	
		
		$taskidDetails = kanban::taskDetailsAsAnArray($projectid,$taskid);
		$change = "Task Comment deleted\n";
		$change = $change."ID: ".$taskid."\n";
		$change = $change."Heading".$taskidDetails['heading']."\n";
		$change = $change."Deleted Comment: ".$comment."\n";
		kanban::addToHistory($projectid,$change);
	}
	
	function groupsAsAnArray($projectid) {
		$sql='SELECT id,name,wip FROM kanban_group WHERE project_id = ? ORDER BY displayorder';
		$query = $this->db->query($sql,array($projectid));
		$i=0;
		$groups = array();
		foreach ($query->result_array() as $row)
		{
			$groups[$i]=$row;			
			$i++;
		}
		return $groups;
	}

}

?>
