<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_Controller extends CI_Controller {
	
 // curl init session
    protected $app_session;
    protected $options = array();
    protected $url;
	
	public static $verify_base_url = 'http://localhost/cap-ho/index.php/Account/validate_passport';


    function __construct() {
        parent::__construct();

	}
	
	public function index() {
		
	}
	
	/**
	 * @param 	$sql		Query de sql
	 * @return EXECUTE;
	 */
	public function execute_query($sql){
		$query = $this->db->query($sql);
		return $query;
	}
		
	 /**
     *
     * output verify base url
     *
     * @param string
     */
    public function verify_url($params, $type = "request")
    {
        return self::$verify_base_url ."/" . $params;
    }
	
	/**
     * request data
     *
     * @param array
     * return string
     */
    protected function request($method, $url, $params = array())
    {
        $this->create($url);
        $this->_request_url = $url;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLINFO_HEADER_OUT] = true;

        $this->options($options);

        return $this->execute();
    }

	 protected function options($options = array())
    {
        curl_setopt_array($this->app_session, $options);

        return $this;
    }

    protected function create($url)
    {
        $this->url = $url;

        $this->app_session = curl_init($this->url);

        return $this;
    }
	
	/**
     * get http response (json)
     * @return json
     */
    protected function execute()
    {
		
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$this->url);
	
	$result=curl_exec($ch);

	$this->_http_response =$result;
	$this->_http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);

   return $this->response();
    }
	
	/**
     * get http response (json)
     * @return json
     */
    protected function response()
    {
		$response_obj = json_decode($this->_http_response , FALSE);
        return $response_obj;
    }
}
 