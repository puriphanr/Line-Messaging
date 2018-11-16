<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->user_model->isLoggedin();
		$this->load->model('messages_model');
	}

	public function index(){
		redirect('form/vote','refresh');
	}
	
	
	public function vote(){
		$this->db->order_by('create_time','DESC');
		$data = $this->db->get('vote');
		
		$pass['data'] = $data->result_array();
		
		$data = array(
						'body'=>$this->load->view('form/vote/index',$pass , TRUE),
					 );
		$this->parser->parse('template/default', $data);
	}
	
	
	public function updateVote($step = 1,$id = ''){
		if(empty($id)){
			if($step == 1){
				
			}
			else{
				
			}
		}
		else{
			if($step == 1){
				$this->db->where('vote_id',$id );
				$data = $this->db->get('vote');
				$pass['vote'] = $data->row_array();
				
				$this->db->where('vote_id',$id );
				$data = $this->db->get('vote_answers');
				$pass['answers'] = $data->result_array();
		
				$data = array(
								'body'=>$this->load->view('form/vote/update',$pass , TRUE),
							 );
				$this->parser->parse('template/default', $data);
			}
			else{
				
				$dataArray = array(
								'vote_title'=>$this->input->post('vote_title'),
								'question_prefix'=>$this->input->post('question_prefix'),
								'question_answers'=>$this->input->post('question_answers'),
								'start_time'=>$this->input->post('start_time'),
								'finish_time'=>$this->input->post('finish_time'),
								'status'=>$this->input->post('status'),
								'manual'=>$this->input->post('manual')
							);
				$this->db->where('vote_id',$id);
				$this->db->update('vote',$dataArray);
				redirect('form/vote','refresh');
				
			}
		}
	}
	
	public function removeVote($id){
		
	}
	
	

}

/* End of file messages.php */
/* Location: ./application/controllers/messages.php */