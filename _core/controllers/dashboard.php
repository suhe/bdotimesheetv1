<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller{
	
	function __construct()
	{
		parent::MY_Controller();	
		//$this->load->model('clients_model');
	}
	
	
	function index() 	{
		
		$data = $this->getLoader() ;
		$this->load->view('dashboard',$data);
	} // END INDEX

}	
