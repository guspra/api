<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiDipaPusdatin extends CI_Controller {

	var $table_name = 'api_dipa_pusdatin';

	public function __construct()
    {
        parent::__construct();
        /*
        	$check_auth_client = $this->MyModel->check_auth_client();
		if($check_auth_client != true){
			die($this->output->get_output());
		}
		*/
    }

	public function index(){
		echo 'nothin todo here, hehe';
	}

	public function TotalPagu(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->total_pagu();
	    				json_output($response['status'],$resp);
		        	}
			}
		}
	}

	public function TotalPaguByKodeSatker($kode_satker){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->total_pagu_by_kode_satker($kode_satker);
	    				json_output($response['status'],$resp);
		        	}
			}
		}
	}

	public function TotalPaguJenisBelanjaByKodeSatker($kode_satker){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->pagu_per_jenis_belanja_per_kode_satker($kode_satker);
						$arr = [
							'pegawai' => 0,
							'barang' => 0,
							'modal' => 0
						];
						foreach($resp as $key => $value){
							$arr[$value->jenis_belanja] = $value->total_pagu;
						}
	    				json_output($response['status'],$arr);
		        	}
			}
		}
	}

	public function TotalPaguJenisBelanja(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->pagu_per_jenis_belanja();
						$arr = [];
						foreach($resp as $key => $value){
							$arr[$value->kode_satker] = [
								'pegawai' => 0,
								'barang' => 0,
								'modal' => 0
							];
						}
						foreach($resp as $key => $value){
							$arr[$value->kode_satker][$value->jenis_belanja] = $value->total_pagu;
						}
	    				json_output($response['status'],$arr);
		        	}
			}
		}
	}



	public function insert()
	{
		$this->MyModel->delete_all_rows($this->table_name); //DELETE DULU SEMUA DATANYA

		$url = 'https://de.kemenkumham.go.id/kanwil/ntb/api/anggaran/dipa';
		$json = file_get_contents($url);
		$objek = json_decode($json);
		$array_dipa = $objek->data->data;

		// $kode_gabungan_unitorg_unitkerja = array();
		// $kode_satker = [];
		// $satker = array();
		$data_api = [];
		$counter = 0;
		foreach($array_dipa as $key => $value){
			// $kode_gabungan = $value->{'KODE GABUNGAN'};
			// $kode_satker = $value->{'KODE SATKER'};
			// array_push($kode_gabungan_unitorg_unitkerja, $kode_gabungan);
			// array_push($kode_satker, $value->{'KODE SATKER'});
			// $satker[$kode_gabungan] = $value->{'NAMA SATKER'};
			$data_api['kode_satker'] = (empty($value->{'KODE SATKER'}) ? "null" : $value->{'KODE SATKER'});
			$data_api['kode_kementerian'] = (empty($value->{'kddept'}) ? "null" : $value->{'kddept'});
			$data_api['kode_eselon_satu'] = (empty($value->{'kdunit'}) ? "null" : $value->{'kdunit'});
			$data_api['kode_program'] = (empty($value->{'kdprogram'}) ? "null" : $value->{'kdprogram'});
			$data_api['kode_kegiatan'] = (empty($value->{'kdgiat'}) ? "null" : $value->{'kdgiat'});
			$data_api['kode_kro'] = (empty($value->{'kdoutput'}) ? "null" : $value->{'kdoutput'});
			$data_api['kode_akun'] = (empty($value->{'AKUN'}) ? "null" : $value->{'AKUN'});
			$data_api['nama_satker'] = (empty($value->{'NAMA SATKER'}) ? "null" : $value->{'NAMA SATKER'});
			$data_api['nama_program'] = (empty($value->{'nama_program'}) ? "null" : $value->{'nama_program'});
			$data_api['nama_kegiatan'] = (empty($value->{'nama_kegiatan'}) ? "null" : $value->{'nama_kegiatan'});
			$data_api['nama_kro'] = (empty($value->{'nmoutput'}) ? "null" : $value->{'nmoutput'});
			$data_api['nominal_akun'] = (empty($value->{'AMOUNT'}) ? "null" : $value->{'AMOUNT'});

			$resp = $this->MyModel->insert_to_table($this->table_name,$data_api);
			$counter++;
		}

		json_output(400,$counter);
		// echo sizeof($kode_gabungan_unitorg_unitkerja);
		// echo "\n";

		// echo sizeof($satker);
		// echo "\n";

		
		// foreach($satker as $key => $value){
		// 	echo $key; echo '----'; echo $satker[$key];
		// 	echo "\n";
			
		// }
	}

}