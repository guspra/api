<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RPD extends CI_Controller {
	var $table_name = 'rpd';

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
		        		$resp = $this->MyModel->get_all_rows_table($this->table_name);//GET SEMUA ROW TABEL
	    				json_output($response['status'],$resp);
		        	}
			}
		}
	}

	public function detail($id) //parameter didapet dari url
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->get_row_detail($this->table_name,$id);
						json_output($response['status'],$resp);
		        	}
			}
		}
	}
	public function getDetailByDipa($id_dipa) //parameter didapet dari url
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->get_row_detail_by_foreignkey($this->table_name,$id_dipa,"id_dipa");
						json_output($response['status'],$resp);
		        	}
			}
		}
	}

	public function mappingDataRpd($id_dipa){
		// echo $id_dipa . "\n";
		$arr = [
			"pegawai" => [0,0,0,0,0,0,0,0,0,0,0,0],
			"barang" => [0,0,0,0,0,0,0,0,0,0,0,0],
			"modal" => [0,0,0,0,0,0,0,0,0,0,0,0]
		];
		// $arr = $resp2;

		$return_modal = $this->MyModel->get_row_detail_by_foreignkey($this->table_name,$id_dipa,"id_dipa");
		$resp = (sizeof($return_modal) > 0) ? $return_modal[0] : $arr;
		
		// var_dump($resp);

		if(sizeof($return_modal) > 0){
			$arr["pegawai"] =
			[
				$resp->januari_pegawai, $resp->februari_pegawai, $resp->maret_pegawai,
				$resp->april_pegawai, $resp->mei_pegawai, $resp->juni_pegawai,
				$resp->juli_pegawai, $resp->agustus_pegawai, $resp->september_pegawai,
				$resp->oktober_pegawai, $resp->november_pegawai, $resp->desember_pegawai
			];
			$arr["barang"] =
			[
				$resp->januari_barang, $resp->februari_barang, $resp->maret_barang,
				$resp->april_barang, $resp->mei_barang, $resp->juni_barang,
				$resp->juli_barang, $resp->agustus_barang, $resp->september_barang,
				$resp->oktober_barang, $resp->november_barang, $resp->desember_barang
			];
			$arr["modal"] =
			[
				$resp->januari_modal, $resp->februari_modal, $resp->maret_modal,
				$resp->april_modal, $resp->mei_modal, $resp->juni_modal,
				$resp->juli_modal, $resp->agustus_modal, $resp->september_modal,
				$resp->oktober_modal, $resp->november_modal, $resp->desember_modal
			];
		}
		

		// json_output($response['status'],$arr);
		return $arr;
	}

	public function dataGrafikDeviasi($id_dipa){
		
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		// $resp = $this->MyModel->get_row_detail_by_foreignkey($this->table_name,$id_dipa,"id_dipa")[0];

						// $arr["pegawai"] =
						// [
						// 	$resp->januari_pegawai, $resp->februari_pegawai, $resp->maret_pegawai,
						// 	$resp->april_pegawai, $resp->mei_pegawai, $resp->juni_pegawai,
						// 	$resp->juli_pegawai, $resp->agustus_pegawai, $resp->september_pegawai,
						// 	$resp->oktober_pegawai, $resp->november_pegawai, $resp->desember_pegawai
						// ];
						// $arr["barang"] =
						// [
						// 	$resp->januari_barang, $resp->februari_barang, $resp->maret_barang,
						// 	$resp->april_barang, $resp->mei_barang, $resp->juni_barang,
						// 	$resp->juli_barang, $resp->agustus_barang, $resp->september_barang,
						// 	$resp->oktober_barang, $resp->november_barang, $resp->desember_barang
						// ];
						// $arr["modal"] =
						// [
						// 	$resp->januari_modal, $resp->februari_modal, $resp->maret_modal,
						// 	$resp->april_modal, $resp->mei_modal, $resp->juni_modal,
						// 	$resp->juli_modal, $resp->agustus_modal, $resp->september_modal,
						// 	$resp->oktober_modal, $resp->november_modal, $resp->desember_modal
						// ];

						json_output($response['status'],$this->mappingDataRpd($id_dipa));
		        	}
			}
		}
	}

	

	public function dataGrafikDeviasiSemuaSatker(){
		// json_output(200, $this->MyModel->all_kode_satker());
		// $kode_satker = [];
		$arr = [];
		foreach($this->MyModel->all_kode_satker() as $key => $value){
			$kode_satker = $value->id;
			$data_mapping = $this->mappingDataRpd($kode_satker);

			$arr["pegawai"][$value->id] = $data_mapping["pegawai"];
			$arr["barang"][$value->id] = $data_mapping["barang"];
			$arr["modal"][$value->id] = $data_mapping["modal"];
		}

		json_output(200, $arr);
	}

	public function getDetailByDipaByRevisi($id_dipa,$revisi_ke) //parameter didapet dari url
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->get_row_detail_by_two_foreignkey($this->table_name,$id_dipa, $revisi_ke, "id_dipa", "revisi_ke");
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
						// echo isset($params['id_dipa']) && isset($params['revisi_ke']);
						if (!isset($params['id_dipa']) || !isset($params['revisi_ke']) ) {//ISI NAMA PARAMETER INPUT POST NYA
							$respStatus = 400;
							// echo 'wkwk';
							$resp = array('status' => 400,'message' =>  'Input form masih salah, silahkan coba lagi');
						} else {
								$resp = $this->MyModel->insert_to_table($this->table_name,$params);
						}
						json_output($respStatus,$resp);
		        	}
			}
		}
	}

    public function update($id)
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'PUT' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	$respStatus = $response['status'];
				if($response['status'] == 200){
					$params = json_decode(file_get_contents('php://input'), TRUE);
					$params['updated_at'] = date('Y-m-d H:i:s');
					if (!isset($params['id_dipa']) || !isset($params['revisi_ke']) ) { //CEK PARAMETER INPUT NYA
						$respStatus = 400;
						$resp = array('status' => 400,'message' =>  'Input form masih salah, silahkan coba lagi');
					} else {
		        			$resp = $this->MyModel->update_data_table($this->table_name,$id,$params);
					}
					json_output($respStatus,$resp);
		       	}
			}
		}
	}

    public function delete($id)
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'DELETE' || $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->delete_data_table($this->table_name,$id); //DELETE SINGLE ROW
					json_output($response['status'],$resp);
		        	}
			}
		}
	}
}