<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiRealisasiMonsakti extends CI_Controller {

	var $table_name = 'api_realisasi_monsakti';
	var $semua_kode_satker = ["407607","407613","407622","407638","407644","407653",
							  "408247","408649","409220","409221","409222","409223",
							  "409224","409225","409226","409227","409228","418351",
							  "418938","632734","652412","652923","653182","653417","683373"];

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
		        		$resp = $this->MyModel->total_realisasi_monsakti();
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
		        		$resp = $this->MyModel->total_realisasi_by_kode_satker_monsakti($kode_satker);
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
		        		$resp = $this->MyModel->total_realisasi_jenis_belanja_monsakti();
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
		        		$resp = $this->MyModel->total_realisasi_jenis_belanja_by_kode_satker_monsakti($kode_satker);
	    				json_output($response['status'],$resp);
		        	}
			}
		}
	}

	public function insertall(){
		$this->MyModel->delete_all_rows($this->table_name); //DELETE DULU SEMUA DATANYA

		foreach($this->semua_kode_satker as $key => $val){
			$this->insert($val);
		}

		json_output(400,"sukses");
		
	}

	public function insert($kode_satker)
	{
		$this->MyModel->delete_where($this->table_name, "kode_satker", $kode_satker);

		$array_dipa = $this->apidata($kode_satker);

		$data_api = [];
		$counter = 0;
		
		// 51: belanja pegawai
		// 52: belanja barang
		// 53: belanja modal
		$total_belanja_pegawai = 0;
		$total_belanja_barang = 0;
		$total_belanja_modal = 0;

		foreach($array_dipa as $key => $value){
			$data_api['kode_kementerian'] = (empty($value->{'KODE_KEMENTERIAN'}) ? "null" : $value->{'KODE_KEMENTERIAN'});
			$data_api['kode_satker'] = (empty($value->{'KDSATKER'}) ? "null" : $value->{'KDSATKER'});
			$data_api['kode_program'] = (empty($value->{'PROGRAM'}) ? "null" : $value->{'PROGRAM'});
			$data_api['kode_kegiatan'] = (empty($value->{'KEGIATAN'}) ? "null" : $value->{'KEGIATAN'});
			$data_api['kode_kro'] = (empty($value->{'OUTPUT'}) ? "null" : $value->{'OUTPUT'});
			$data_api['kode_sumber_dana'] = (empty($value->{'SUMBER_DANA'}) ? "null" : $value->{'SUMBER_DANA'});
			$data_api['kode_akun'] = (empty($value->{'AKUN'}) ? "null" : $value->{'AKUN'});
			$data_api['jumlah_realisasi'] = (empty($value->{'JUMLAH_REALISASI'}) ? 0 : $value->{'JUMLAH_REALISASI'});
			$data_api['tanggal_realisasi'] = (empty($value->{'TANGGAL_REALISASI'}) ? "null" : $value->{'TANGGAL_REALISASI'});

			$resp = $this->MyModel->insert_to_table($this->table_name,$data_api);
			$counter++;

			
			
			if(substr($data_api['kode_akun'],0,2) === "51"){
				$total_belanja_pegawai += $data_api['jumlah_realisasi'];
			} else if(substr($data_api['kode_akun'],0,2) === "52"){
				$total_belanja_barang += $data_api['jumlah_realisasi'];
			} else if(substr($data_api['kode_akun'],0,2) === "53"){
				$total_belanja_modal += $data_api['jumlah_realisasi'];
			}
			
			
			// if(strpos($data_api['kode_akun'], "51") !== false){
			// 	$total_belanja_pegawai += $data_api['jumlah_realisasi'];
			// } else if(strpos($data_api['kode_akun'], "52") !== false){
			// 	$total_belanja_barang += $data_api['jumlah_realisasi'];
			// } else if(strpos($data_api['kode_akun'], "53") !== false){
			// 	$total_belanja_modal += $data_api['jumlah_realisasi'];
			// }

			//ABSOLUTE
			// if(strpos($data_api['kode_akun'], "51") !== false){
			// 	$total_belanja_pegawai += abs($data_api['jumlah_realisasi']);
			// } else if(strpos($data_api['kode_akun'], "52") !== false){
			// 	$total_belanja_barang += abs($data_api['jumlah_realisasi']);
			// } else if(strpos($data_api['kode_akun'], "53") !== false){
			// 	$total_belanja_modal += abs($data_api['jumlah_realisasi']);
			// }

			//MINUS TIDAK DIHITUNG
			// $jumlah_realisasi = 0 + $data_api['jumlah_realisasi'];
			// if($jumlah_realisasi < 0){ $jumlah_realisasi = 0;}
			// if(strpos($data_api['kode_akun'], "51") !== false){
			// 	$total_belanja_pegawai += $jumlah_realisasi;
			// } else if(strpos($data_api['kode_akun'], "52") !== false){
			// 	$total_belanja_barang += $jumlah_realisasi;
			// } else if(strpos($data_api['kode_akun'], "53") !== false){
			// 	$total_belanja_modal += $jumlah_realisasi;
			// }
		}

		echo "KODE_SATKER: $kode_satker --- jumlah_baris: $counter --- pegawai: $total_belanja_pegawai --- barang: $total_belanja_barang --- modal: $total_belanja_modal\n\n";
	}

	public function apidata($kode_satker){
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
		$json = file_get_contents("https://monsakti.kemenkeu.go.id/sitp-monsakti-omspan/webservice/API/KL013/realisasi_omspan/$kode_satker", false, $context);
		
		return json_decode($json);
	}

	// public function totalRealisasi(){
	// 	echo $this->getApiRealisasiData();
	// }

	public function total_realisasi_jenis_belanja2(){
		echo $this->getApiRealisasiData();
	}

}