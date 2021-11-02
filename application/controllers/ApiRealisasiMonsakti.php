<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiRealisasiMonsakti extends CI_Controller {

	var $table_name = 'api_monsakti_realisasi';

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

		// Create a stream
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'header'=>"Accept-language: en\r\n" .
						"Cookie: foo=bar\r\n".
						"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3IiOiJLRU1FTlRFUklBTiBIVUtVTSBEQU4gSEFLIEFTQVNJIE1BTlVTSUEgUkkiLCJ1aWQiOiJIQU0iLCJyb2wiOiJ3ZWJzZXJ2aWNlIiwia2RzIjoiS0wwMTMiLCJrZGIiOiJLTDAxMyIsImtkdCI6IjIwMjEiLCJpYXQiOjE2MzA5OTg3NDcsIm5iZiI6MTYzMDk5ODE0Nywia2lkIjoiSEFNIn0.7-kPxLtXiLSD9erzNKiDLIrUwrsEofQj_EhrY6zWrA0"
			)
		);
		
		$context = stream_context_create($opts);
		
		// Open the file using the HTTP headers set above
		$json = file_get_contents('https://monsakti.kemenkeu.go.id/sitp-monsakti-omspan/webservice/API/KL013/realisasi_omspan/409226', false, $context);

		$objek = json_decode($json);
		$array_dipa = $objek;

		$data_api_dipa = [];
		$counter = 0;
		
		// 51: belanja pegawai
		// 52: belanja barang
		// 53: belanja modal
		$total_belanja_pegawai = 0;
		$total_belanja_barang = 0;
		$total_belanja_modal = 0;

		foreach($array_dipa as $key => $value){
			$data_api_dipa['kode_kementerian'] = (empty($value->{'KODE_KEMENTERIAN'}) ? "null" : $value->{'KODE_KEMENTERIAN'});
			$data_api_dipa['kode_satker'] = (empty($value->{'KDSATKER'}) ? "null" : $value->{'KDSATKER'});
			$data_api_dipa['kode_program'] = (empty($value->{'PROGRAM'}) ? "null" : $value->{'PROGRAM'});
			$data_api_dipa['kode_kegiatan'] = (empty($value->{'KEGIATAN'}) ? "null" : $value->{'KEGIATAN'});
			$data_api_dipa['kode_kro'] = (empty($value->{'OUTPUT'}) ? "null" : $value->{'OUTPUT'});
			$data_api_dipa['kode_sumber_dana'] = (empty($value->{'SUMBER_DANA'}) ? "null" : $value->{'SUMBER_DANA'});
			$data_api_dipa['kode_akun'] = (empty($value->{'AKUN'}) ? "null" : $value->{'AKUN'});
			$data_api_dipa['jumlah_realisasi'] = (empty($value->{'JUMLAH_REALISASI'}) ? 0 : $value->{'JUMLAH_REALISASI'});
			$data_api_dipa['tanggal_realisasi'] = (empty($value->{'TANGGAL_REALISASI'}) ? "null" : $value->{'TANGGAL_REALISASI'});

			$resp = $this->MyModel->insert_to_table($this->table_name,$data_api_dipa);
			$counter++;

			
			// var_dump($data_api_dipa['jumlah_realisasi']);
			// $jumlah_realisasi = 0 + $data_api_dipa['jumlah_realisasi'];	
			if(strpos($data_api_dipa['kode_akun'], "51") !== false){
				$total_belanja_pegawai += $data_api_dipa['jumlah_realisasi'];
			} else if(strpos($data_api_dipa['kode_akun'], "52") !== false){
				$total_belanja_barang += $data_api_dipa['jumlah_realisasi'];
			} else if(strpos($data_api_dipa['kode_akun'], "53") !== false){
				$total_belanja_modal += $data_api_dipa['jumlah_realisasi'];
			}
		}

		json_output(400,"jumlah_baris: $counter --- total_belanja_pegawai: $total_belanja_pegawai --- total_belanja_barang: $total_belanja_barang --- total_belanja_modal: $total_belanja_modal");
	}

}