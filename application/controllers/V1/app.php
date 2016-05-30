<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App extends CI_Controller {
	
    function __construct() {
        parent::__construct();

	}
	
	public function index() {
		
	}
		
	/**
	 *@param 	$passport		Pasaporte
	 *@return BOOLEAN;
	 */
	public function passport($passport='0') {	
		try {      
			
			//$data = array();
			//$data['context'] = 'data';
			//$data['result'] = $return;
			
			//$this->load->view('json',$data);
		
		} catch (Exception $e) {
			//log_message('error', 'Error sign_up'.$e.message);
			//$this->load->view('json',$data);
		}
	}
}
 