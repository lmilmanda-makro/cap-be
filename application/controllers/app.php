<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
	
	public function index() {
		//$this->sign_in('666','javier@gmail.com','15','16');
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
	public function sign_in($passport='',$email='',$phone='',$device_id='') {	

	$body = json_decode(file_get_contents("php://input"));
	
		try {      
			$sql = "EXEC [dbo].[sp_sign_in] '".$body->passport."','".$body->email."','".$body->phone."','".$body->device_id."';";	
			$result = $this->execute($sql);
			$data = array();
			$data['context'] = 'data';
	
			if($result->num_rows > 0)
				$data['result'] = TRUE ;
			else
				$data['result'] = FALSE;	
			
			$this->load->view('json',$data);
		
		} catch (Exception $e) {
			$data['result'] = FALSE;
			$this->load->view('json',$data);
		}
	}
	
	 /**
	 *@param 	$passport		Pasaporte
	 *@param 	$email			Email
	 *@param 	$phone		    Telefono
	 *@param 	$device_id		ID de dispositivo
	 *@return BOOLEAN;
	 */
	public function sign_up($passport='',$email='',$phone='',$device_id='') {	
		
		$data = array();
		$data['context'] = 'data';
		$data['result'] = FALSE;
	
		$this->load->view('json',$data);
	}
	
	
	/**
	 *@param 	$passport		Pasaporte
	 *@param 	$device_id		ID de dispositivo
	 *@param 	$code		 	Codigo de activacion
	 *@return BOOLEAN;
	 */
	public function confirm_code($passport='',$device_id='',$code='') {	
		
		$body = json_decode(file_get_contents("php://input"));

		$sql = "SELECT CC.SIGN_IN ,ISNULL(MAX(CC.CONFIRMATION),0) AS 'Maxim' "
		$sql .=  "FROM CAP_CONFIRMATION CC INNER JOIN CAP_USER CU ON CC.SIGN_IN =CC.SIGN_IN "
		$sql .=  "WHERE  CU.PASSPORT = ".$passport." AND CU.DEVICE_ID = ".$device_id." GROUP BY CC.SIGN_IN; ";

		$result = $this->execute($sql);
		$r = $result->result_array();
		
		$data = array();
		$data['context'] = 'data';
		$data['result'] = $r;

		$this->load->view('json',$data););
	}
	
	/**
	 *@param 	$passport		Pasaporte
	 *@param 	$device_id		ID de dispositivo
	 *@param 	$code		 	Codigo de activacion
	 *@return BOOLEAN;
	 */
	public function send_code($passport='',$device_id='',$code='') {	
	
	$body = json_decode(file_get_contents("php://input"));
	
		try {      
			$sql = "EXEC [dbo].[sp_send_code] '".$body->passport."','".$body->device_id."','".$body->code."';";	
			$result = $this->execute($sql);
			$data = array();
			$data['context'] = 'data';
	
			if($result->num_rows > 0)
				$data['result'] = TRUE ;
			else
				$data['result'] = FALSE;	
			
			$this->load->view('json',$data);
		
		} catch (Exception $e) {
			$data['result'] = FALSE;
			$this->load->view('json',$data);
		}
	}
	
	/**
	 * @return TRUE;
	 */
	public function is_supported()
	{
		return TRUE;
	}
}
 