<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'base_controller.php';

class App extends Base_Controller {
	
	// curl init session
    protected $app_session;
    protected $options = array();
    protected $url;
	
	public static $verify_base_url = 'http://localhost/cap-be-ho/index.php/V1/App/Passport';
	
    function __construct() {
        parent::__construct();

		// Carga libreria 
        $this->load->library('nexmo');
        
		// Setea el formato: xml or json, default json
        $this->nexmo->set_format('json');
		
		$this->_ci = & get_instance();
        $this->_ci->load->config('nexmo');

		// Instancio la constantes.		
        $this->brand = $this->_ci->config->item("api_brand");
		$this->sender_id = $this->_ci->config->item("api_sender_id");
		$this->code_length = $this->_ci->config->item("api_code_length");
		$this->lg = $this->_ci->config->item("api_lg");
		$this->request_id = $this->_ci->config->item("api_request_id");	
  }
	
	public function index() {
		
	}
		
	/**
	 *@param 	$passport		Pasaporte
	 *@param 	$email			Email
	 *@param 	$phone		    Telefono
	 *@param 	$device_id		ID de dispositivo
	 *@return BOOLEAN;
	 */
	public function sign_up() {	

		$data = array();
		$data['context'] = 'data';
		$data['result'] = null;
			
		$body = json_decode(file_get_contents("php://input"));
	
		try {      
			
			 $params = array(
				'passport'       => $body->passport, //'0147067214'
			);
		
			$return = $this->request('get', $this->verify_url($params), null);
				
			if(isset($return))
			{
				$return_token = $this->create_token($body->phone);
				//if($return_token == 0)
				//	$this->sign_in("NEWID()", $body->passport,$body->email,$body->phone,$body->device_id);				
			}
					
			$data['result'] = $return->result;
			
			$this->load->view('json',$data);	
			
		} catch (Exception $e) {
			log_message('error', 'Error sign_up'.$e.message);
			$this->load->view('json',$e);
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
		
			$sql = "EXEC [dbo].[sp_sign_in] '".$passport."','".$email."','".$phone."','".$device_id."','".$this->request_id."';";	
			
			$query = $this->execute_query($sql);
			$row = $query->row();
			
			return $row;
		
		} catch (Exception $e) {
			log_message('error', 'Error sign_in'.$e.message);
			$this->load->view('json',$data);
		}
	}

	/**
	 *@param 	$phone		    Telefono
	*@return INTEGER;
	*/
	public function create_token($phone= '') {	
		try {  

			$response = $this->nexmo->verify_request($phone, $this->brand, $this->sender_id, $this->code_length, $this->lg,null);
		
			if($response['request_id'] ==0)
				$this->request_id= $response['request_id'];

			return $response['status'];
		} catch (Exception $e) {
			log_message('error', 'Error create_token'.$e.message);
			return 97;
		}
	}
	
	/**
	 *@param 	$passport		Pasaporte
	 *@param 	$device_id	    Nro de Dispositivo
	 *@param 	$code			Codigo token
	 *@return INTEGER;
	*/
	public function validate_token($passport='',$device_id='',$code='') {	
	
		$body = json_decode(file_get_contents("php://input"));
		
		try {
			$sql = "EXEC [dbo].[sp_user_token] '".$body->passport."','".$body->device_id."'";	
				
			$query = $this->execute_query($sql);
			$row = $query->row();
			
			$response = $this->nexmo->verify_check($row->DATA,$body->code);
	
		return $response['status'];		
		
		} catch (Exception $e) {
			log_message('error', 'Error validate_token'.$e.message);
			return 96;
		}	
	}
	
	 /**
     *
     * output verify base url
     *
     * @param string
     */
    public function verify_url($params, $type = "request")
    {
        return self::$verify_base_url ."/" . $params["passport"];
    }
}
 