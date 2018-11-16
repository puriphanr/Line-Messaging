<?php
Class Messages_model extends CI_Model
{
	
	
	function apiVerify($url){
		$headers = array('Authorization: Bearer ' . LINE_ACCESS_TOKEN);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	function readMessage2(){
		date_default_timezone_set("Asia/Bangkok");
		$postdata = file_get_contents("php://input");
		$field = json_decode($postdata);
		$data = "";
		$json = $field->result;
		
		//var_dump($json);
		foreach($json as $key=>$row){
			$this->load->helper('date');
			$from = $row->content->from;
			$id = $row->content->id;
			$text = $row->content->text;
			$profile_url = 'https://api.line.me/v2/bot/profile/'.$from;
			$user = json_decode($this->apiVerify($profile_url));
			$displayName = $user->displayName;
			$pictureUrl = $user->pictureUrl;

			if($text == null){
				$text = $this->getSticker($row->content->contentMetadata->STKID);
			}
			if($row->content->contentType == 1){ //$row->content->contentType == 8 Sticker
			$data = array(
						'message_id'=>$id,
						'contentType'=>$row->content->contentType,
						'fromID'=>$from,
						'displayName'=>$displayName,
						'pictureUrl'=>$pictureUrl,
						'createdTime'=>mdate('%d-%m-%Y %H:%i:%s',$row->content->createdTime/1000),
						'text'=>$text
					);
			
			$this->db->insert('messages',$data);
			}
		}
	}
	
	
	function readMessage(){
		date_default_timezone_set("Asia/Bangkok");
		$this->load->helper('date');
		$postdata = file_get_contents("php://input");
		$field = json_decode($postdata);
		
		foreach($field->events as $key=>$row){
			
			$user = $this->getProfile($row->source->userId);
			
			if($row->type == 'message'){
					$data = array(
						'msg_id'=>$row->message->id,
						'reply_token'=>$row->replyToken,
						'type'=>$row->type,
						'timestamp'=>mdate('%Y-%m-%d %H:%i:%s',$row->timestamp/1000),
						'text'=>$row->message->text,
						'user_id'=>$row->source->userId,
						'profile_name'=>$user->displayName,
						'profile_url'=>$user->pictureUrl
					);	
					$this->db->insert('msg',$data);
			}
						
		}
	}
	
	function getSticker($id){
		return "http://dl.stickershop.line.naver.jp/stickershop/v1/sticker/".$id."/android/sticker.png";
	}
	
	function getProfile($id){
		return json_decode($this->apiVerify('https://api.line.me/v2/bot/profile/'.$id));
	}
	
	function moretext($message,$length = 150){
		
		if(mb_strlen($message) > $length){
			return iconv_substr($message, 0, $length). "..." ;
		}
		else{
			return $message;
		}
	}
	
	function utf8_for_xml($string)
	{
		return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
	}
 
 
}
?>