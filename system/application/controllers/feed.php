<?php

class feed extends Controller {
    function feed()    {
        parent::Controller();   
		$this->load->database();     
		$this->load->helper('xml');    
		$this->load->helper('url');
	}
	
	function index()    {
		feed::anychange();
	}
	
	function anychange() {
                $data['encoding'] = 'utf-8';
                $data['feed_name'] = 'SDP ICP-Content RSS';
                $data['feed_url'] = site_url( '/sdp' );
          	$data['page_description'] = 'New Releases, Changed Releases, new content...';
          	$data['page_language'] = 'en-ca';
          	$data['creator_email'] = 'tobias.e.eriksson@ericsson.se';
          	$data['changes'] = feed::getLatestChanges();
          	header("Content-Type: application/rss+xml");
          	$this->load->view('feeds/rss', $data);
	}

	function deleted() {
                $data['encoding'] = 'utf-8';
                $data['feed_name'] = 'Notification of Deleted items from SDP ICP-Content RSS';
                $data['feed_url'] = site_url( '/sdp' );
          	$data['page_description'] = 'Notification of anything deleted from a release/package...';
          	$data['page_language'] = 'en-ca';
          	$data['creator_email'] = 'tobias.e.eriksson@ericsson.se';
          	$data['changes'] = feed::getLatestChanges('Delete');
          	header("Content-Type: application/rss+xml");
          	$this->load->view('feeds/rss', $data);
	}

        function getLatestChanges($keyword='') {
                 $messagesearch='';
                 if( strlen( trim( $keyword ) ) > 0 ) {
                     $messagesearch = " AND message like '%".$keyword."%' ";
                 }
		$sql = 'SELECT submitted, submitted_by, r.header as title, r.id as release_id, message FROM sdp_comment c, sdp_release r WHERE c.release_id = r.id '.$messagesearch.' ORDER BY submitted DESC LIMIT 20';
		$query = $this->db->query( $sql );
		$i=0;
		foreach ($query->result_array() as $row)
		{
			$changes[$i] = $row;
			$i++;
		}
		return $changes;
	}

}
?>