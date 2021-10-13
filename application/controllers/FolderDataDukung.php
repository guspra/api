<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FolderDataDukung extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->folderdatadukung_all_data();
	    				json_output($response['status'],$resp);
		        	}
			}
		}
	}

    public function create()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	$respStatus = $response['status'];
		        	if($response['status'] == 200){
						$params = json_decode(file_get_contents('php://input'), TRUE);
						if (empty($params['uraian'])) {//jika kosong
							$respStatus = 400;
							$resp = array('status' => 400,'message' =>  'Uraian folder tidak boleh kosong');
						} else {
								$resp = $this->MyModel->insert_to_table('folder_data_dukung',$params);
						}
						json_output($respStatus,$resp);
		        	}
			}
		}
	}
}