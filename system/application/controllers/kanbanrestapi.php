<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  RESTful API for the Kanban-tool
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Kanbanrestapi extends REST_Controller
{
    function Kanbanrestapi()
    {
        parent::__construct('json');
        $this->load->database();     
        $this->load->helper('xml');    
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Kanbanmodel','kanbanmodel',TRUE);
    }

	function task_get()
    {
        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }

        // $user = $this->some_model->getSomething( $this->get('id') );
    	$users = array(
			1 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
			2 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!'),
		);
		
    	$user = @$users[$this->get('id')];
    	
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
    
    //
    // Create new Task
    //
    function task_post()
    {
        // print_r($this->post());
        $projectid = $this->post('projectid');
        if( is_numeric( $projectid ) != TRUE || $projectid <= 0 ) {
            $this->response(array('error' => 'Project ID is invalid!('.$projectid.')' ), 405);
            return;
        }  
        $heading = $this->post('heading');
        $description = $this->post('description');
        $group = $this->post('group');
        if( is_numeric( $group ) != TRUE )  $group = 0;
        $priority = $this->post('priority');
        if( is_numeric( $priority ) != TRUE )  $priority = 0;
        $estimation = $this->post('estimation');
        if( is_numeric( $estimation ) != TRUE )  $estimation = 0;
        $colortag = $this->post('colortag');
        $workpackage_id = $this->post('workpackage_id');
        $sprintid = $this->post('sprintid');
        if( is_numeric( $sprintid ) != TRUE || $sprintid <= 0 ) {
            $this->response(array('error' => 'Task can not change to an invalid sprint!('.$sprintid.')' ), 406);
            return;
        } 

        $today = date( "Y-m-d" );

        $data = array(          
            'heading' => $heading,
            'description' => $description,
            'priority' => $priority,
            'estimation' => $estimation,
            'colortag' => $colortag,
            'sprint_id' => $sprintid,
            'workpackage_id' => $workpackage_id,
            'project_id' => $projectid,
            'group_id' => $group,
            'added' => $today,
            'startdate' => '0000-00-00',
            'enddate' => '0000-00-00'
            );

        $taskid = $this->kanbanmodel->addTask($data,$projectid);

        $change = "Task Created\n";
        $change = $change."ID: ".$taskid."\n";
        $change = $change."Heading: ".$heading."\n";
        $change = $change."Priority: ".$priority."\n";
        $change = $change."Estimation: ".$estimation."\n";
        $change = $change."Description: ".$description."\n";
        
        $this->kanbanmodel->addToHistory($projectid,$change);
    
        $message = array('id' => $taskid, 'taskid' => $taskid );
        
        $this->response($message, 200); // 200 being the HTTP response code
    }

    //
    // UPDATE a Task
    //
    function task_put()
    {

        $taskid = $this->put('id');
        if( is_numeric( $taskid ) != TRUE || $taskid <= 0 ) {
            $this->response(array('error' => 'Task ID is invalid!('.$taskid.')'), 403);
            return;
        }  
        $projectid = $this->put('projectid');
        if( is_numeric( $projectid ) != TRUE || $projectid <= 0 ) {
            $this->response(array('error' => 'Project ID is invalid!('.$projectid.')' ), 405);
            return;
        }  
        $heading = $this->put('heading');
        $description = $this->put('description');
        $group = $this->put('group');
        if( is_numeric( $group ) != TRUE )  $group = 0;
        $priority = $this->put('priority');
        if( is_numeric( $priority ) != TRUE )  $priority = 0;
        $estimation = $this->put('estimation');
        if( is_numeric( $estimation ) != TRUE )  $estimation = 0;
        $todays_estimation = $this->put('todays_estimation');
        if( is_numeric( $todays_estimation ) != TRUE )  $todays_estimation = 0;
        $todays_time_reporting = $this->put('todays_time_reporting');
        if( is_numeric( $todays_time_reporting ) != TRUE )  $todays_time_reporting = 0;
        $colortag = $this->put('colortag');
        $workpackage_id = $this->put('workpackage_id');
        $newsprintid = $this->put('sprintid');
        if( is_numeric( $newsprintid ) != TRUE || $newsprintid <= 0 ) {
            $this->response(array('error' => 'Task can not change to an invalid sprint!('.$newsprintid.')' ), 406);
            return;
        } 
        $ownerid = $this->put('ownerid');
        if( is_numeric( $ownerid ) != TRUE )  $ownerid = 0;

        $groups = $this->kanbanmodel->getGroups($projectid);
        $numberOfGroups = count( $groups );
        if( $numberOfGroups >  1 ) {
            if( $groups[ $numberOfGroups-1 ]['id'] == $group ) $todays_estimation = 0;
        }

        $oldTaskDetails = $this->kanbanmodel->taskDetailsAsAnArray($projectid,$taskid);
        $today = date( "Y-m-d" );

        $data = array(          
            'heading' => $heading,
            'description' => $description,
            'priority' => $priority,
            'estimation' => $estimation,
            'colortag' => $colortag,
            'sprint_id' => $newsprintid,
            'workpackage_id' => $workpackage_id,
            'group_id' => $group,
            'owner_id' => $ownerid
            );

        $this->kanbanmodel->updateTask($data,$taskid);
        $this->kanbanmodel->updateProgressForTask($taskid,$todays_estimation,$today);
        $this->kanbanmodel->updateTimeReportingForToday($taskid,$todays_time_reporting,$today);


        $resources = $this->kanbanmodel->getAvailableResources($projectid);
        $previousOwner = 'Anyone';
        $newOwner = 'Anyone';
        foreach( $resources as $owner ) {
            if( $owner['id'] == $ownerid ) $newOwner = $owner['name'];
            if( $owner['id'] == $oldTaskDetails['ownerid'] ) $previousOwner = $owner['name'];
        }
        $change = "Task Updated\n";
        $change = $change."From :\n";
        $change = $change."ID: ".$taskid."\n";
        $change = $change."Heading: ".$oldTaskDetails['heading']."\n";
        $change = $change."Owner: ".$previousOwner."\n";
        $change = $change."Priority: ".$oldTaskDetails['priority']."\n";
        $change = $change."Estimation: ".$oldTaskDetails['estimation']."\n";
        $change = $change."Todays Estimation: ".$oldTaskDetails['todays_estimation']."\n";
        $change = $change."Reported Hours Today: ".$oldTaskDetails['todays_time_reporting']."\n";
        $change = $change."Description: ".$oldTaskDetails['description']."\n";
        $change = $change."Group: ".$oldTaskDetails['group_id']."\n";
        $change = $change."\nTo :\n";
        $change = $change."Heading: ".$heading."\n";
        $change = $change."Owner: ".$newOwner."\n";
        $change = $change."Priority: ".$priority."\n";
        $change = $change."Estimation: ".$estimation."\n";
        $change = $change."Todays Estimation: ".$todays_estimation."\n";
        $change = $change."Reported Hours Today: ".$todays_time_reporting."\n";
        $change = $change."Description: ".$description."\n";
        $change = $change."Group: ".$group."\n";
        
        $this->kanbanmodel->addToHistory($projectid,$change);
    
        $message = array('message' => 'Task updated!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function task_delete()
    {
        $projectid = $this->session->userdata('projectid');
        if( is_numeric( $projectid ) != TRUE || $projectid <= 0 ) {
            $this->response(array('error' => 'Project ID is invalid!('.$projectid.')' ), 405);
            return;
        }  
        $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $tmp = explode( '/', $urlPath );
        $taskid = $tmp[ count($tmp)-1 ];
        if( is_numeric( $taskid ) != TRUE || $taskid <= 0 ) {
            $this->response(array('error' => 'Task ID is invalid!('.$taskid.')'.$urlPath.','.$tmp), 403);
            return;
        }
        $taskDetails = $this->kanbanmodel->taskDetailsAsAnArray($projectid,$taskid);
        $this->kanbanmodel->deleteTask($taskid);
        $change = "Deleted Task\n";
        $change = $change."ID: ".$taskid."\n";
        $change = $change."Heading: ".$taskDetails['heading']."\n";
        $this->kanbanmodel->addToHistory($projectid,$change);   

        $message = array('message' => $change);
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function users_get()
    {
        //$users = $this->some_model->getSomething( $this->get('limit') );
        $users = array(
			array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
			array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
			array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com'),
		);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }
}