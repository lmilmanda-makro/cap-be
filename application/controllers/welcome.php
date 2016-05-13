<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
	
	public function index() {
		$this->allstores();
	}
	
	public function execute($sql)
	{
		$query = $this->db->query($sql);
		return $query;
	}	

	public function allstores() {	
	
		$sql = "SELECT * FROM CAP_STORE;";

		$result = $this->execute($sql);
		$r = $result->result_array();
		
		$data = array();
		$data['context'] = 'data';
		$data['result'] = $r;

		$this->load->view('json',$data);
	}
	

}
