<?php
Class User_model extends CI_Model
{
	function isLoggedin(){
		if(!$this->session->userdata('is_logged_in')){
			redirect('authen'); 
		}
	}
	 
	function checkLogin($email, $password)
	{
	   $this->db->where('email',$email);
	   $this->db->where('password',md5($password));
	   $query = $this->db->get('user');
	  
	   if($query->num_rows() > 0){
		   return $query->result();
	   }
	   else{
		   return false;
	   }
	}
}
?>