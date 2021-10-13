<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function login()
	{
		// json_output(200,"haha");
		// echo 'haha';
		// echo 'hihi';
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			// echo 'hoho';
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
				$params = json_decode(file_get_contents('php://input'), TRUE);
		        $username = $params['username'];
		        $password = $params['password'];
		        
				// echo $password;
				// echo crypt('Admin123$', 'rpp');
				//echo crypt('1234', 'rpp'); // hasilnya => rplpwJDqgNj3o
				// echo crypt('1234', 'rplpwJDqgNj3o');
				// echo password_hash('Admin123$', PASSWORD_DEFAULT);


		        $response = $this->MyModel->login($username,$password);
				json_output($response['status'],$response);
				
				
			}
		}
	}

	public function logout()
	{	
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->logout();
				json_output($response['status'],$response);
			}
		}
	}
	
}
