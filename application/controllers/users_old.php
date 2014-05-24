<?php
class Users extends CI_Controller {
	public function logout(){
		$this->session->sess_destroy();
		header('Location: '.base_url().'index.php/pages/view/login');
	}
	public function login()
	{	
		$msg;
		$userid;
		$this->session->unset_userdata('ACCOUNT');
		$account = $this->input->post("account");
		$password = $this->input->post("password");
		//$this->load->database();
		$query = $this->db->query("SELECT * FROM `user` WHERE  `user_id` = '$account' 
					   AND `password` = SUBSTR('$password', 1, 30)");
		$result = $query->result_array();

		if(sizeof($result) == 0){
			//wrong id/password , do something
			$msg['status'] = 'error';
			$msg['type'] = 'Account or password error';
			/////////////////////////////////
		}
		else{
			$userid = $result[0]["user_id"];
		}
		//if variable userid is set up, login ok, set up session
		if(isset($userid)){
			$session_data = array('ACCOUNT' => "$userid");
			$this->session->set_userdata($session_data);
		}

		if($this->session->userdata('ACCOUNT') ){
			header('Location: ' . base_url(). '/index.php/pages/view/searchtable');
		}
		else
		{
			header('Location: ' . base_url(). '/index.php/pages/view/login');
		}
		
		

			//header("Content-type: application/json");
            //    echo json_encode($msg);
	}
	public function googlelogin(){
		require 'application/third_party/openid.php';
		$openid = new LightOpenID(base_url());   //we use baseurl for reverse proxy
		if(!$openid->mode) {
					$openid->identity = 'https://www.google.com/accounts/o8/id';
			$openid->required = array('contact/email', 'namePerson/first', 'namePerson/last');

				header('Location: ' . $openid->authUrl());
		}
		else{
			if($openid->validate() == true){
				$user_data = $openid->getAttributes();
				$mail = $user_data["contact/email"];
				$session_data = array('ACCOUNT' => "$mail");
				$this->session->set_userdata($session_data);
				header('Location: ' . base_url(). 'index.php/pages/view/searchtable');
			}
			else{
				header('Content-Type: text/html');
				echo '<html><head></head><body>Google Open ID 有些問題，請重試。</body></html>';
			}
		}
		//for openid success, redirect		
	}
	public function register(){
		$account = $this->input->post('account');
		$password = $this->input->post('password');
		$confirm = $this->input->post('confirm');
		$email = $this->input->post('email');
		//check if the account is already in use
		$query = $this->db->query("SELECT * FROM `user` WHERE  `user_id` = '$account'");
		$result = $query->result_array();
		$response = array('data'=>'','status'=>'success');
		if(sizeof($result)!=0){
			$response['data'] = '已經有人使用'.$account.'這個帳號名稱.';
			$response['status'] = 'error';
		}
		//check if the password matches confirm
		if($password!=$confirm){
			$response['data'] .= '密碼與確認密碼不符.';
			$response['status'] = 'error';
		}
		if($password==''||$confirm==''){
			$response['data'] .= '密碼/確認密碼不可為空.';
			$response['status'] = 'error';
		}
		if($account==''){
			$response['data'] .= '帳號不可為空.';
			$response['status'] = 'error';
		}
		if($email==''){
			$response['data'] .= '信箱不可為空.';
			$response['status'] = 'error';
		}
		if($response['status']==='error'){
			header("Content-type: application/json");
                	echo json_encode($response);
			return;
		}
		//try to insert to db
		$this->db->query("INSERT INTO `user` (`user_id`, `password`, `email`) 
									  VALUES ('$account','$password','$email');");
		$query = $this->db->query("SELECT * FROM `user` WHERE  `user_id` = '$account'");
		$result = $query->result_array();
		if(sizeof($result)==0){
			$response['data'] .= '寫入資料庫失敗，請重試\n';
			$response['status'] = 'error';
		}
		if($response['status']!='success'){
			$response['data'] .= '帳號創造成功，請輸入帳號密碼';
			$response['status'] = 'success';
		}
		//send the response
		header("Content-type: application/json");
        	echo json_encode($response);
		
	}
	public function forgotpw(){
		$account = $this->input->post('account');
		$response;
		//try to get the user's email
		$query = $this->db->query("SELECT * FROM `user` WHERE  `user_id` = '$account'");
		$result = $query->result_array();
		if(sizeof($result)!=0){
			$password = $result[0]['password'];
			$email = $result[0]['email'];
			$msg = '<html><head></head><body>你的NSC帳號為： '.$account.'<br>你的NSC密碼為： '.$password.'</body></html>';
			//$msg = 'testing';
			//mail($email,'NSC系統帳號密碼取回',$msg,headers,parameters);
			$this->load->library('email');
			$config['protocol'] = 'sendmail';
			$config['mailpath'] = '/usr/sbin/sendmail';
			$config['charset'] = 'big5';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			
			$this->email->from('dontreply@ubuntu12004.iis.sinica.edu.tw', 'NSC system');
			$this->email->to($email); 
			$this->email->subject('NSC account info');
			//$this->email->subject('NSC系統帳號資訊');
			$this->email->message($msg);	
			$this->email->send();
			//mail($email,"NSC account/pw",$msg,"From: 'root@ubuntu12004.iis.sinica.edu.tw'\n");
			$response['status'] = 'success';
			$response['data'] = '以寄信到您的信箱 '.$email;
		}else{
			$response['status'] = 'error';
			$response['data'] = '查無此帳號';
		}
		
		header("Content-type: application/json");
        	echo json_encode($response);
	}
	public function test(){
		echo 'gay';
	}
}
?>
