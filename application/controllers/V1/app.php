<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App extends CI_Controller {
	
    function __construct() {
        parent::__construct();

		// load library
        $this->load->library('nexmo');
        
		// set response format: xml or json, default json
        $this->nexmo->set_format('json');
		
  }
	
	public function index() {
		
		$from = '40756693916';
        $to = '40756693916';
  
		  $message = array(
            'text' => 'Aproveche las ofertas',
        );
        $response = $this->nexmo->send_message($from, $to, $message);
        echo "<h1>Text Message</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: ".$this->nexmo->get_http_status()."</h3>";

	}
	
	/**
	 * @param 	$sql		Query de sql
	 * @return EXECUTE;
	 */
	public function execute($sql){
		$query = $this->db->query($sql);
		return $query;
	}	
	
	/**
	 *@param 	$passport		Pasaporte
	 *@param 	$email			Email
	 *@param 	$phone		    Telefono
	 *@param 	$device_id		ID de dispositivo
	 *@return BOOLEAN;
	 */
	public function sign_up() {	

	$body = json_decode(file_get_contents("php://input"));
	
		try {      
			
			$sql = "EXEC [dbo].[sp_user_exist] '".$body->passport."','".$body->email."','".$body->phone."','".$body->device_id."'";	
			$query = $this->execute($sql);
			$row = $query->row();

			if($row->DATA == 0 )
			{
				$return = $this->create_token();
				if($return==0)
					$return = $this->sign_in("1", $body->passport,$body->email,$body->phone,$body->device_id);				
			}
			else
				$return = $row->DATA;
			
			///SEND SMS
			
			$data = array();
			$data['context'] = 'data';
			$data['result'] = $return;
			
			$this->load->view('json',$data);
		
		} catch (Exception $e) {
			log_message('error', 'Error sign_up'.$e.message);
			$this->load->view('json',$data);
		}
	}
	
	/**
	 *@param 	$code			Codigo token
	 *@param 	$passport		Pasaporte
	 *@param 	$email			Email
	 *@param 	$phone		    Telefono
	 *@param 	$device_id		ID de dispositivo
	 *@return BOOLEAN;
	 */
	public function sign_in($code='',$passport='',$email='',$phone='',$device_id='') {		
		try {      
			
			$sql = "EXEC [dbo].[sp_sign_in] '".$passport."','".$email."','".$phone."','".$device_id."';";	
			
			$query = $this->execute($sql);
			$row = $query->row();
			
			return $row;
		
		} catch (Exception $e) {
			log_message('error', 'Error sign_in'.$e.message);
			$this->load->view('json',$data);
		}
	}

	/**
	 *@return INTEGER;
	 */
	public function create_token() {	
		return 0;
	}
}
 