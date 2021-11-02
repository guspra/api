<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {

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

	public function index()
	{
		// $this->MyModel->coba();
		// echo $this->MyModel->get_total_pagu("409226");
		$a = "1992963250";
		$s2 = "5";

		// echo $s1 + $s2;

		// echo (strpos("511122", "52") !== false);

		// $str = "99999977706";
		// $bigInt = gmp_init($str);
		// $bigIntVal = gmp_intval($bigInt);
		// echo $bigIntVal;

		// $a = 2147483648999999999;
		// $b = 2147483647;
		// $a = 0+$a;

		
		echo empty("1");
	}

}