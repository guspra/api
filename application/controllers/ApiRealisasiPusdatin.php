<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiRealisasiPusdatin extends CI_Controller {

	var $table_name = 'data_api_realisasi';

	public function __construct()
    {
        parent::__construct();
    }

	public function index(){
		echo 'nothin todo here, hehe';
	}

	public function TotalRealisasi(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->total_realisasi();
	    				json_output($response['status'],$resp);
		        	}
			}
		}
	}

	public function TotalRealisasiByKodeSatker($kode_satker){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->total_realisasi_by_kode_satker($kode_satker);
	    				json_output($response['status'],$resp);
		        	}
			}
		}
	}

	public function insert()
	{
		$this->MyModel->delete_all_rows($this->table_name); //DELETE DULU SEMUA DATANYA

		$url = 'https://de.kemenkumham.go.id/kanwil/ntb/api/anggaran/realisasi';
		$json = file_get_contents($url);
		$objek = json_decode($json);
		$array_dipa = $objek->data->data;

		$data_api_dipa = [];
		$counter = 0;
		foreach($array_dipa as $key => $value){
			$data_api_dipa['kode_satker'] = (empty($value->{'KODE SATKER'}) ? "null" : $value->{'KODE SATKER'});
			$data_api_dipa['kode_kementerian'] = (empty($value->{'kddept'}) ? "null" : $value->{'kddept'});
			$data_api_dipa['kode_eselon_satu'] = (empty($value->{'kdunit'}) ? "null" : $value->{'kdunit'});
			$data_api_dipa['kode_program'] = (empty($value->{'kdprogram'}) ? "null" : $value->{'kdprogram'});
			$data_api_dipa['kode_kegiatan'] = (empty($value->{'kdgiat'}) ? "null" : $value->{'kdgiat'});
			$data_api_dipa['kode_kro'] = (empty($value->{'kdoutput'}) ? "null" : $value->{'kdoutput'});
			$data_api_dipa['kode_akun'] = (empty($value->{'AKUN'}) ? "null" : $value->{'AKUN'});
			$data_api_dipa['nama_satker'] = (empty($value->{'NAMA SATKER'}) ? "null" : $value->{'NAMA SATKER'});
			$data_api_dipa['nama_program'] = (empty($value->{'nama_program'}) ? "null" : $value->{'nama_program'});
			$data_api_dipa['nama_kegiatan'] = (empty($value->{'nama_kegiatan'}) ? "null" : $value->{'nama_kegiatan'});
			$data_api_dipa['nama_kro'] = (empty($value->{'nmoutput'}) ? "null" : $value->{'nmoutput'});
			$data_api_dipa['nominal_akun'] = (empty($value->{'AMOUNT'}) ? "null" : $value->{'AMOUNT'});
			
			$data_api_dipa['sumber_dana'] = (empty($value->{'SUMBER_DANA'}) ? "null" : $value->{'SUMBER_DANA'});
			$data_api_dipa['cara_tarik'] = (empty($value->{'CARA_TARIK'}) ? "null" : $value->{'CARA_TARIK'});
			$data_api_dipa['budget_type'] = (empty($value->{'BUDGET_TYPE'}) ? "null" : $value->{'BUDGET_TYPE'});
			$data_api_dipa['tanggal'] = (empty($value->{'TANGGAL'}) ? "null" : $value->{'TANGGAL'});


			$resp = $this->MyModel->insert_to_table($this->table_name,$data_api_dipa);
			$counter++;
		}

		json_output(400,$counter);
	}

}