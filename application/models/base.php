<?php
class base extends CI_Model {

	public function __construct()
	{
		 $this->db = $this->load->database('MKTFAB1',TRUE);
	}
	
}
