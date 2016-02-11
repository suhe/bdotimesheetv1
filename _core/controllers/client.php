<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends MY_Controller{
	
	function __construct()
	{
		parent:: __construct();	
		$this->load->model('clientModel');
	}

	/*-------------------------------------------------------------------------------------*/
	//  client
	/*-------------------------------------------------------------------------------------*/
	function index($type=1, $pg=1, $limit=0) 	{
		$this->getMenu();
		$form = array();
		if($type==1) {
			$this->session->unset_userdata('client_no');
			$this->session->unset_userdata('client_name');
			$this->session->unset_userdata('address');
		}
		elseif($type==2) {
			$this->session->unset_userdata('client_no');
			$this->session->unset_userdata('client_name');
			$this->session->unset_userdata('address');
			
			if($this->input->post('client_no')) 		$form['client_no']   = $this->input->post('client_no');
			if($this->input->post('client_name'))		$form['client_name'] = $this->input->post('client_name');
			if($this->input->post('address'))			$form['address']   	 = $this->input->post('address');
			$this->session->set_userdata($form);
		}
		
		if($this->session->userdata('client_no')) 	$form['client_no']   = $this->session->userdata('client_no');
		if($this->session->userdata('client_name')) $form['client_name'] = $this->session->userdata('client_name');
		if($this->session->userdata('address')) 	$form['address']	 = $this->session->userdata('address');
		
		if($limit) {
			$this->session->set_userdata('rpp', $limit);
			$this->rpp = $limit;
		}
		$limit					= $limit ? $limit : $this->rpp;
		$totalRow			 	= $this->clientModel->getClient($form);
		$this->data['pg']	 	= $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->clientModel->getClient($form, $limit, $this->data['pg']['o']);
		$this->load->view('client',$this->data);
	} // END CLIENT
	
	
	/*-------------------------------------------------------------------------------------*/
	//  clientEdit
	/*-------------------------------------------------------------------------------------*/
	function Edit($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->clientModel->getClientDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['message']		= '';
			$this->data['form']['client_id']	= 0;
			$this->data['form']['client_no']	= '';
			$this->data['form']['client_name']	= '';
			$this->data['form']['address']		= '';
			$this->data['form']['phone']		= '';
			$this->data['form']['fax']			= '';
			$this->data['form']['contact']		= '';
			$this->data['form']['contact_email']		= '';
			$this->data['form']['website']		= '';
			$this->data['form']['lob']		= '';
		}
		$this->data['back']	= $this->data['site'] .'/client/';
		$this->data['form']['message']=$msg;

		$this->load->view('client_edit',$this->data);
	} // END CLIENT EDIT


	/*-------------------------------------------------------------------------------------*/
	//  clientUpdate
	/*-------------------------------------------------------------------------------------*/
	function Update() 	{
		$this->getMenu() ;
		$form['client_id']	= $this->input->post('client_id');
		$form['client_no']	= $this->input->post('client_no');
		$form['client_name']= $this->input->post('client_name');
		$form['address']	= $this->input->post('address');
		$form['phone']		= $this->input->post('phone');
		$form['fax']		= $this->input->post('fax');
		$form['contact']	= $this->input->post('contact');
		$form['contact_email']	= $this->input->post('contact_email');
		$form['lob']	= $this->input->post('lob');
		$form['website']	= $this->input->post('website');

		$this->clientModel->saveClient($form);
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
	} // END CLIENT UPDATE

}	