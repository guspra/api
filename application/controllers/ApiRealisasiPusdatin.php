<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiRealisasiPusdatin extends CI_Controller {

	var $table_name = 'api_realisasi_pusdatin';

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

	public function TotalRealisasiJenisBelanja(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->total_realisasi_jenis_belanja();
	    				json_output($response['status'],$resp);
		        	}
			}
		}
	}

	public function TotalRealisasiJenisBelanjaByKodeSatker($kode_satker){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->total_realisasi_jenis_belanja_by_kode_satker($kode_satker);
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

		$data_api = [];
		$counter = 0;

		// 51: belanja pegawai
		// 52: belanja barang
		// 53: belanja modal
		$total_belanja_pegawai = 0;
		$total_belanja_barang = 0;
		$total_belanja_modal = 0;

		foreach($array_dipa as $key => $value){
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
			$data_api['nominal_akun'] = (empty($value->{'AMOUNT'}) ? 0 : $value->{'AMOUNT'});
			
			$data_api['sumber_dana'] = (empty($value->{'SUMBER_DANA'}) ? "null" : $value->{'SUMBER_DANA'});
			$data_api['cara_tarik'] = (empty($value->{'CARA_TARIK'}) ? "null" : $value->{'CARA_TARIK'});
			$data_api['budget_type'] = (empty($value->{'BUDGET_TYPE'}) ? "null" : $value->{'BUDGET_TYPE'});
			$data_api['tanggal'] = (empty($value->{'TANGGAL'}) ? "null" : $value->{'TANGGAL'});


			$resp = $this->MyModel->insert_to_table($this->table_name,$data_api);
			$counter++;

			// echo $data_api['nominal_akun']."\n";

			if($data_api['kode_satker'] === "409226")
			if(strpos($data_api['kode_akun'], "51") !== false){
				$total_belanja_pegawai += $data_api['nominal_akun'];
			} else if(strpos($data_api['kode_akun'], "52") !== false){
				$total_belanja_barang += $data_api['nominal_akun'];
			} else if(strpos($data_api['kode_akun'], "53") !== false){
				$total_belanja_modal += $data_api['nominal_akun'];
			}


		}

		// json_output(400,$counter);
		json_output(400,"jumlah_baris: $counter --- total_belanja_pegawai: $total_belanja_pegawai --- total_belanja_barang: $total_belanja_barang --- total_belanja_modal: $total_belanja_modal");
	}

}