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
		$a = 'aa';
		$b = 'bb';

		// echo $a.$b;
		$ar=[];
		// $ar[0] = 0;
		echo (sizeof($ar) > 0 ? "lebih dari nol" : "nol");
	}

}