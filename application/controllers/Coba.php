<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        /*
        	$check_auth_client = $this->MyModel->check_auth_client(),
		if($check_auth_client != true){
			die($this->output->get_output()),
		}
		*/
    }

	public function index()
	{
		// var_dump($this->MyModel->get_last_id("pelaksanaan_anggaran")),
		// echo $this->MyModel->get_last_id("pelaksanaan_anggaran"),

		$arr = [
			409220,
			409221,
			409222,
			409223,
			409224,
			409225,
			409226,
			409227,
			409228,
			407613,
			407644,
			408649,
			652923,
			683373,
			418351,
			407638,
			407622,
			407653,
			407607,
			653182,
			632734,
			653417,
			408247,
			652412,
			418938
		];
// echo "\"\"",
		foreach($arr as $key => $val){
			if($key <= 8)continue;
			$id = $key + 89;
			$key1 = $key+1;
			echo 
			"\"$id\",\"kpa$key1\",\"\$1\$Dtqyvz7/\$wZSaZbfHgn0UbLlVi1HHp0\",\"KPA\",\"2021-10-15 14:27:50\",\"2021-10-15 14:27:50\",\"2021-10-15 14:27:50\",\"kpa\",\"upt\",\"$val\"
			"
			;
		}
	}

}