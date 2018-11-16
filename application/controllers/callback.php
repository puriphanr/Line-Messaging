<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Callback extends CI_Controller {

	
	public function __construct(){
		parent::__construct();
		$this->load->model('messages_model');
	}
	
	public function index(){
		$this->messages_model->readMessage();
	}
	
	
	
	public function testApi(){
		$this->messages_model->testVerify();
	}
	
	public function phpinfo(){
		var_dump(extension_loaded('curl'));
	}
	
}

/* End of file callback.php */
/* Location: ./application/controllers/callback.php */