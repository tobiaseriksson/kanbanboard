<?php

class Kanbanblog extends Controller {

	function Kanbanblog()
	{
		parent::Controller();
		$this->load->database();     
		$this->load->helper('xml');    
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	function index()
	{
		$pagedata = array();
		$pagedata['errormessage'] = '';
		$this->load->view('kanban/bloghome',$pagedata);
	}
	
	function tutorials()
	{
		$pagedata = array();
		$pagedata['errormessage'] = '';
		$this->load->view('kanban/blogtutorial',$pagedata);
	}
	
	function about()
	{
		$pagedata = array();
		$pagedata['errormessage'] = '';
		$this->load->view('kanban/blogabout',$pagedata);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
?>
