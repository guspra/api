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
		// var_dump($this->MyModel->get_last_id("pelaksanaan_anggaran"));
		// echo $this->MyModel->get_last_id("pelaksanaan_anggaran");

	}

}