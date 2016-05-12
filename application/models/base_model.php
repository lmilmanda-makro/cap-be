<?php
abstract class BASE_Model  extends CI_Model {

	public function __construct()
	{
		 $this->db = $this->load->database($this->config->item('DB'),TRUE);
	}
}

