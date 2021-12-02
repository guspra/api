<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiRealisasiMonsakti extends CI_Controller {

	var $table_name = 'api_realisasi_monsakti';
	var $semua_kode_satker = ["407607","407613","407622","407638","407644","407653",
							  "408247","408649","409220","409221","409222","409223",
							  "409224","409225","409226","409227","409228","418351",
							  "418938","632734","652412","652923","653182","653417","683373"];
	
	var $string_to_number = [
		"01" => 1, "02" => 2, "03" => 3, "04" => 4, "05" => 5, "06" => 6, 
		"07" => 7, "08" => 8, "09" => 9, "10" => 10, "11" => 11, "12" => 12
	];
	
	var $month_to_number = [
		"JAN" => 1, "FEB" => 2, "MAR" => 3, "APR" => 4, "MAY" => 5, "JUN" => 6, 
		"JUL" => 7, "AUG" => 8, "SEP" => 9, "OCT" => 10, "NOV" => 11, "DEC" => 12
	];

	var $month_to_string_number = [
		"JAN" => "01", "FEB" => "02", "MAR" => "03", "APR" => "04", "MAY" => "05", "JUN" => "06", 
		"JUL" => "07", "AUG" => "08", "SEP" => "09", "OCT" => "10", "NOV" => "11", "DEC" => "12"
	];

	var $number_to_jenis_belanja = [
		"51" => "pegawai", "52" => "barang", "53" => "modal"
	];

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
	
	public function tes(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		// $resp = $this->MyModel->total_realisasi_monsakti();
						$resp= ["januari_pegawai" => "1111",
								"januari_barang" => 2222];
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

	public function TotalRealisasiJenisBelanjaPerbulan(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->total_realisasi_jenis_belanja_perbulan_monsakti();
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

	public function TotalRealisasiJenisBelanjaPerbulanByKodeSatker($kode_satker){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->total_realisasi_jenis_belanja_perbulan_by_kode_satker_monsakti($kode_satker);
	    				json_output($response['status'],$resp);
		        	}
			}
		}
	}

	public function realiasiPerBulanPerKodeSatker($kode_satker){
		$data_realisasi = [
			"pegawai" => [0,0,0,0,0,0,0,0,0,0,0,0],
			"barang" => [0,0,0,0,0,0,0,0,0,0,0,0],
			"modal" => [0,0,0,0,0,0,0,0,0,0,0,0]	
		];

		$resp = $this->MyModel->total_realisasi_jenis_belanja_perbulan_by_kode_satker_monsakti($kode_satker);

		foreach($resp as $key => $value){
			// var_dump($value);exit;
			// if ($value->bulan_realisasi == "00"){ var_dump($value); exit;}
			$jenis_belanja = $this->number_to_jenis_belanja[$value->jenis_belanja];
			$bulan = $this->string_to_number[$value->bulan_realisasi];
			$data_realisasi[$jenis_belanja][$bulan-1] = $value->total_realisasi;
		}

		// return 1234;
		// return json_encode($data_realisasi);
		return $data_realisasi;
	}

	public function dataGrafikDeviasiRpdRealisasi($kode_satker){
		$data_realisasi = [
			"pegawai" => [0,0,0,0,0,0,0,0,0,0,0,0],
			"barang" => [0,0,0,0,0,0,0,0,0,0,0,0],
			"modal" => [0,0,0,0,0,0,0,0,0,0,0,0]	
		];
		
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$resp = $this->MyModel->total_realisasi_jenis_belanja_perbulan_by_kode_satker_monsakti($kode_satker);

						foreach($resp as $key => $value){
							$jenis_belanja = $this->number_to_jenis_belanja[$value->jenis_belanja];
							$bulan = $this->string_to_number	[$value->bulan_realisasi];
							$data_realisasi[$jenis_belanja][$bulan-1] = $value->total_realisasi;
						}

						json_output($response['status'],$data_realisasi);
		        	}
			}
		}
	}

	public function dataGrafikDeviasiRpdRealisasiSemuaSatker(){
		// $arr = [
		// 	"409226" => 1,
		// 	"409225" => []
		// ];

		// $arr["409226"] = json_encode($this->dataGrafikDeviasiRpdRealisasi(409226));
		// $arr["409226"] = $this->dataGrafikDeviasiRpdRealisasi(409226);
		// echo $this->dataGrafikDeviasiRpdRealisasi(409226);
		// json_output(200, $this->realiasiPerBulanPerKodeSatker(409226));
		// json_encode($this->dataGrafikDeviasiRpdRealisasi(409226));

		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->MyModel->check_auth_client();
			if($check_auth_client == true){
		        	$response = $this->MyModel->auth();
		        	if($response['status'] == 200){
		        		$arr["407607"] = $this->realiasiPerBulanPerKodeSatker(407607);
						$arr["407613"] = $this->realiasiPerBulanPerKodeSatker(407613);
						$arr["407622"] = $this->realiasiPerBulanPerKodeSatker(407622);
						$arr["407638"] = $this->realiasiPerBulanPerKodeSatker(407638);
						$arr["407644"] = $this->realiasiPerBulanPerKodeSatker(407644);
						$arr["407653"] = $this->realiasiPerBulanPerKodeSatker(407653);
						$arr["408247"] = $this->realiasiPerBulanPerKodeSatker(408247);
						$arr["408649"] = $this->realiasiPerBulanPerKodeSatker(408649);
						$arr["409220"] = $this->realiasiPerBulanPerKodeSatker(409220);
						$arr["409221"] = $this->realiasiPerBulanPerKodeSatker(409221);
						$arr["409222"] = $this->realiasiPerBulanPerKodeSatker(409222);
						$arr["409223"] = $this->realiasiPerBulanPerKodeSatker(409223);
						$arr["409224"] = $this->realiasiPerBulanPerKodeSatker(409224);
						$arr["409225"] = $this->realiasiPerBulanPerKodeSatker(409225);
						$arr["409226"] = $this->realiasiPerBulanPerKodeSatker(409226);
						$arr["409227"] = $this->realiasiPerBulanPerKodeSatker(409227);
						$arr["409228"] = $this->realiasiPerBulanPerKodeSatker(409228);
						$arr["418351"] = $this->realiasiPerBulanPerKodeSatker(418351);
						$arr["418938"] = $this->realiasiPerBulanPerKodeSatker(418938);
						$arr["632734"] = $this->realiasiPerBulanPerKodeSatker(632734);
						$arr["652412"] = $this->realiasiPerBulanPerKodeSatker(652412);
						$arr["652923"] = $this->realiasiPerBulanPerKodeSatker(652923);
						$arr["653182"] = $this->realiasiPerBulanPerKodeSatker(653182);
						$arr["653417"] = $this->realiasiPerBulanPerKodeSatker(653417);
						$arr["683373"] = $this->realiasiPerBulanPerKodeSatker(683373);


						$arr2 = [
							"pegawai" => [],
							"barang" => [],
							"modal" => []
						];

						foreach($arr as $key => $val){
							$arr2["pegawai"][$key] = $val["pegawai"];
						}
						foreach($arr as $key => $val){
							$arr2["barang"][$key] = $val["barang"];
						}
						foreach($arr as $key => $val){
							$arr2["modal"][$key] = $val["modal"];
						}
						
						json_output($response['status'],$arr2);
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
			$tanggal_realisasi = (empty($value->{'TANGGAL_REALISASI'}) ? "01-JAN-20" : $value->{'TANGGAL_REALISASI'});
			$data_api['tanggal_realisasi'] = "20".substr($tanggal_realisasi,7,2).$this->month_to_string_number[substr($tanggal_realisasi,3,3)].substr($tanggal_realisasi,0,2);
			$resp = $this->MyModel->insert_to_table($this->table_name,$data_api);
			// if($data_api['tanggal_realisasi'] == "0000-00-00"){ 
			// 	echo "value->JUMLAH_REALISASI:".$value->{'TANGGAL_REALISASI'};
			// 	echo "\ntanggal realisasi:".$tanggal_realisasi;
			// 	echo "\ndata_api[tanggal_realisasi]:".$data_api['tanggal_realisasi'];
			// 	exit;
			// }
			// echo $data_api['tanggal_realisasi'];
			// echo "###";
			// if($counter == 3) break;
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