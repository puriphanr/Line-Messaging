<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authen extends CI_Controller {

	public function index()
	{
		$this->parser->parse('template/login',array());
	}
	
	public function login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$login = $this->user_model->checkLogin($email,$password);
		
		if($login === false){
			echo false;
		}
		else{
			$this->session->set_userdata('is_logged_in',$login);
			echo true;
		}
	}
	
	public function logout()
	{
		$this->session->unset_userdata('is_logged_in');
		redirect('auth', 'refresh');
	}
	
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */