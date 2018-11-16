<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->user_model->isLoggedin();
		$this->load->model('messages_model');
	}

	public function index(){
		$this->messages_model->readMessage();

		$getMessage = $this->db->query("SELECT * FROM msg 
										WHERE type = 'message'
										ORDER BY timestamp DESC 
										LIMIT 50");
		
		$pass['data'] = $getMessage->result_array();
		
		$data = array(
						'body'=>$this->load->view('messages/push/index', $pass , TRUE),
					 );
		$this->parser->parse('template/default', $data);
	}
	
	
	public function searchMessage(){
		$post = $this->input->post();
		$getMessage = $this->db->query("SELECT * FROM msg
						  WHERE (profile_name LIKE '%".$post['display_name']."%'
							OR  text LIKE '%".$post['display_name']."%')
							AND timestamp >= '".date('Y-m-d H:i:s',strtotime($post['start_range']))."' 
							AND timestamp <= '".date('Y-m-d H:i:s',strtotime($post['end_range']))."'");
	
		//echo $this->db->last_query();
		$pass['data'] = $getMessage->result_array();
		
		$data = array(
						'body'=>$this->load->view('messages/push/index', $pass , TRUE),
					 );
		$this->parser->parse('template/default', $data);
	}
	
	public function push(){
		
		$sendData = array(
						'to' => $this->input->post('fromID'),  //userid
						'messages' => array(array(  //message
										'type' => 'text',
										'text' => trim($this->input->post('text'))
									))
					);
					
		$data_string = json_encode($sendData);
		
		$ch = curl_init('https://api.line.me/v2/bot/message/push');                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type:application/json',                                                                                
			'Authorization: Bearer ' . LINE_ACCESS_TOKEN)                                                                       
		);                                                                                                                   
																															 
		$result = curl_exec($ch);
		redirect('messages','refresh');
		
	}
	
	
	
}

/* End of file messages.php */
/* Location: ./application/controllers/messages.php */