<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('messages_model');
	}
	
	public function friendly_url($string){
		$string = str_replace(array('[\', \']'), '', $string);
		$string = preg_replace('/\[.*\]/U', '', $string);
		$string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
		$string = htmlentities($string, ENT_COMPAT, 'utf-8');
		$string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
		$string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
		return trim($string, '-');
	}


	public function messages2(){
		$this->messages_model->readMessage();
		$this->db->order_by("id","desc");
		$this->db->limit(XML_LIMIT);
		$getMessage = $this->db->get('messages');
	
		header("Content-type: text/xml");
		$xml = '<?xml version="1.0" encoding="UTF-8"?><data>';
		
			foreach($getMessage->result_array() as $key=>$row){
				if($row['contentType'] == 8){
					$type = 'sticker';
				}
				else{
					$type = 'text';
				}
				
				
				$xml .= '<message id="'.$row['message_id'].'">
							<ts>'.date(DATE_RFC2822,strtotime($row['createdTime'])).'</ts>
							<media>line</media>
							<sender>
								<name>'.htmlspecialchars($row['displayName']).'</name>
								<img>
								'.$row['pictureUrl'].'
								</img>
							</sender>
							<'.$type.'>
							'.htmlspecialchars($row['text']).'
							</'.$type.'>
						</message>';
			}
			$xml .= '</data>';
			echo $xml;
	}
	
	public function messages(){
		$this->messages_model->readMessage();
		$this->db->order_by("timestamp","desc");
		$this->db->limit(XML_LIMIT);
		$getMessage = $this->db->get('msg');
	
		header("Content-type: text/xml");
		$xml = '<?xml version="1.0" encoding="UTF-8"?><data>';
		
			foreach($getMessage->result_array() as $key=>$row){
				if($row['type'] == 'message'){
				
					
					$xml .= '<message id="'.$row['msg_id'].'">
								<ts>'.date(DATE_RFC2822,strtotime($row['timestamp'])).'</ts>
								<media>line</media>
								<sender>
									<name>'.$this->messages_model->utf8_for_xml(htmlspecialchars($row['profile_name'])).'</name>
									<img>
									'.$row['profile_url'].'
									</img>
								</sender>
								<text>
								'.$this->messages_model->utf8_for_xml(htmlspecialchars($row['text'])).'
								</text>
							</message>';
						
				}
			}
			$xml .= '</data>';
			echo $xml;
	}
	
	
	public function vote($mode = 'xml'){
		
			$prefix 	= "c";
			$startTime 	= "2018-07-11 21:30:00";
			$endTime 	= "2018-07-11 23:59:59";
			$answers = array(1,2,3,4);
			$now = date('Y-m-d H:i:s');
			$vote_id = 1;
			
		if($mode == 'xml'){
			$this->db->select('manual,manual_answers');
			$this->db->where('vote_id',$vote_id);
			$data = $this->db->get('vote');
			$result = $data->row_array();
			
			if($result['manual'] == 0){
				$sql = "SELECT user_id, TRIM(LOWER(text)) text FROM msg 
						WHERE ";
				foreach($answers as $key=>$row){
					if($key == 0){
						$sql .= "(TRIM(LOWER(text)) = '".$prefix.$row."'";
					}
					else{
						$sql .= "OR TRIM(LOWER(text)) = '".$prefix.$row."'";
					}
				
					if($key == count($answers)-1){
						$sql .= ')';
					}
				}
				$sql .= " AND timestamp >= '".$startTime."' 
						 AND timestamp <= '".$endTime."' 
						 GROUP BY user_id 
						 ORDER BY text";
				
				$voteData = $this->db->query($sql);
				$totalRow = $voteData->num_rows();
				
				$group = array();
				$percent = array();
				$preLast = 0;
				foreach($answers as $skey=>$srow){
					foreach($voteData->result_array() as $key=>$row){
						if($prefix.$srow == $row['text']){
							$group[$srow][] = $row;
						}
					}
					$percent[$srow] = round((count($group[$srow])/$totalRow) * 100 , 0 );
					if($srow < count($answers)){
						$preLast += $percent[$srow];
					}
				}
			
				header("Content-type: text/xml");
				$xml = '<?xml version="1.0" encoding="UTF-8"?>
						 <data>
							<topic>test</topic>
							<prefix>'.$prefix.'</prefix>
							<valid_from>'.strtotime($startTime).'</valid_from>
							<valid_until>'.strtotime($endTime).'</valid_until>
							<ts>'.strtotime($now).'</ts>
							<answer_items>
								<item>';
								foreach($answers as $skey=>$srow){
									if(array_sum($percent) > 0){
										if($srow < count($answers)){
											$finalPercent = $percent[$srow];
										}
										else{
											$finalPercent = 100 - $preLast;
										}
									}
									else{
										$finalPercent = 0;
									}
									$xml .= '<percent'.$srow.'>'.$finalPercent.'</percent'.$srow.'>';
								}
						$xml .='</item>
							</answer_items>
						</data>';
				echo $xml;
			}
			else{
				$explode = explode(",",$result['manual_answers']);
				
				header("Content-type: text/xml");
				$xml = '<?xml version="1.0" encoding="UTF-8"?>
						 <data>
							<topic>test</topic>
							<prefix>'.$prefix.'</prefix>
							<valid_from>'.strtotime($startTime).'</valid_from>
							<valid_until>'.strtotime($endTime).'</valid_until>
							<ts>'.strtotime($now).'</ts>
							<answer_items>
								<item>';
								foreach($explode as $skey=>$srow){
									$xml .= '<percent'.($skey+1).'>'.$srow.'</percent'.($skey+1).'>';
								}
						$xml .='</item>
							</answer_items>
						</data>';
				echo $xml;
				
			}
		}
		elseif($mode == "sum"){
			$sql = "SELECT user_id, TRIM(LOWER(text)) text FROM msg 
					WHERE ";
			foreach($answers as $key=>$row){
				if($key == 0){
					$sql .= "(TRIM(LOWER(text)) = '".$prefix.$row."'";
				}
				else{
					$sql .= "OR TRIM(LOWER(text)) = '".$prefix.$row."'";
				}
			
				if($key == count($answers)-1){
					$sql .= ')';
				}
			}
			$sql .= " AND timestamp >= '".$startTime."' 
					 AND timestamp <= '".$endTime."' 
					 ORDER BY text";
			
			$voteData = $this->db->query($sql);
			echo "All : ".$voteData->num_rows()."<br/>";
			
			$sql2 = "SELECT user_id, TRIM(LOWER(text)) text FROM msg 
					WHERE ";
			foreach($answers as $key=>$row){
				if($key == 0){
					$sql2 .= "(TRIM(LOWER(text)) = '".$prefix.$row."'";
				}
				else{
					$sql2 .= "OR TRIM(LOWER(text)) = '".$prefix.$row."'";
				}
			
				if($key == count($answers)-1){
					$sql2 .= ')';
				}
			}
			$sql2 .= " AND timestamp >= '".$startTime."' 
					 AND timestamp <= '".$endTime."' 
					 GROUP BY user_id 
					 ORDER BY text";
			$voteData2 = $this->db->query($sql2);
			
			echo "Unique : ".$voteData2->num_rows()."<br/>";
		}
		else{
			
			$this->db->select('manual,manual_answers');
			$this->db->where('vote_id',$vote_id);
			$data = $this->db->get('vote');
			$result = $data->row_array();
			$explode = explode(",",$result['manual_answers']);
			?>
			<!DOCTYPE html>
			<html>
			<head>
			<title>Manual Vote</title>
			</head>

			<body>
				<form action="<?php echo site_url('api/updateManual/'.$vote_id)?>" method="POST">
					<div>
						<input type="checkbox" name="manual" value="1" <?php echo $result['manual'] == 1 ? 'checked' : NULL ?> > Manual
					</div>
					<?php foreach($answers as $skey=>$srow){ ?>
					<div>
						<label><?php echo $prefix.$srow ?> : <input type="text" class="voteInput" onkeyup="sumInputs()"  name="vote[]" value="<?php echo !empty($explode) ? $explode[$skey] : NULL ?>" />
					<div>
					<?php } ?>
					<div id="total"></div>
					<div>
						<button type="submit">Update</button>
					<div>
				<form>
				<script>
				function sumInputs() {
					var inputs = document.getElementsByClassName('voteInput'),
						result = document.getElementById('total'),
						sum = 0;            

					for(var i=0; i<inputs.length; i++) {
						var ip = inputs[i];

						if (ip.name && ip.name.indexOf("total") < 0) {
							sum += parseInt(ip.value) || 0;
						}

					}

					result.innerHTML = 'Total : ' + sum;
				}
				sumInputs();
				
				</script>
			</body>

			</html>
			
			<?php
		}
	}
	
	public function updateManual($id){
		$implode = implode(",",$this->input->post('vote'));
		$this->db->update('vote',array('manual'=>$this->input->post('manual'),'manual_answers'=>$implode));
		redirect('api/vote/manual','refresh');
	}
	
	public function countMessage($dateStart,$timeStart,$dateEnd,$timeEnd){
		$this->db->where('timestamp >=', $dateStart.' '.$timeStart.':00');
		$this->db->where('timestamp <=', $dateEnd.' '.$timeEnd.':00');
		$data = $this->db->get('msg');
		echo 'From : '.$dateStart.' '.$timeStart.'<br>';
		echo 'To : '.$dateEnd.' '.$timeEnd.'<br><br>';
		echo '<b>Message Total : '.number_format($data->num_rows()).'</b>';
	}
	
}
/* End of file api.php */
/* Location: ./application/controllers/api.php */