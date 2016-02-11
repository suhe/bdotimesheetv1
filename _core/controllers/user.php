<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller{
	
	function __construct()
	{
		parent::MY_Controller();	
		$this->load->model('adminModel');
	}
	
	function index($type=1, $pg=1, $limit=0) 	{
		redirect("/user/user/".$type."/". $pg=1 ."/". $limit);
	}
	
	
	
	/*-------------------------------------------------------------------------------------*/
	//  user
	/*-------------------------------------------------------------------------------------*/
	function user($type=1, $pg=1, $limit=0) 	{
		$this->getMenu();
		$form = array();
		if($type==1) {
			$this->session->unset_userdata('nik');
			$this->session->unset_userdata('nickname');
			$this->session->unset_userdata('group');
		}
		elseif($type==2) {
			$this->session->unset_userdata('nik');
			$this->session->unset_userdata('nickname');
			$this->session->unset_userdata('group');
			
			if($this->input->post('nik')) 			$form['nik']		= $this->input->post('nik');
			if($this->input->post('nickname'))		$form['nickname']	= $this->input->post('nickname');
			if($this->input->post('group'))			$form['group']   	= $this->input->post('group');
			$this->session->set_userdata($form);
		}
		
		if($this->session->userdata('nik')) 		$form['nik']		= $this->session->userdata('nik');
		if($this->session->userdata('nickname'))	$form['nickname']	= $this->session->userdata('nickname');
		if($this->session->userdata('group')) 		$form['group']		= $this->session->userdata('group');
		
		if($limit) {
			$this->session->set_userdata('rpp', $limit);
			$this->rpp = $limit;
		}
		$limit				 = $limit ? $limit : $this->rpp;
		$totalRow			 = $this->dataModel->getUser($form);
		$this->data['pg']	 = $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->dataModel->getUser($form, $limit, $this->data['pg']['o']);
		$this->load->view('user',$this->data);
	} // END user
	
	
	
	/*-------------------------------------------------------------------------------------*/
	//  userEdit
	/*-------------------------------------------------------------------------------------*/
	function userEdit($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->dataModel->getUserDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['message']		= '';
			$this->data['form']['user_id']		= 0;
			$this->data['form']['employee_id']	= 0;
			$this->data['form']['approval']		= '';
			$this->data['form']['acl']			= '';
			$this->data['form']['email']		= '';
			$this->data['form']['user_active']		= '';
		}
		$this->data['back']		= $this->data['site'] .'/data/user';
		$this->data['reset']	= $this->data['site'] .'/data/resetPassword/'.$id;
		$this->data['form']['message']=$msg;

		$this->load->view('user_edit',$this->data);
	} // END userEdit


	/*-------------------------------------------------------------------------------------*/
	//  userUpdate
	/*-------------------------------------------------------------------------------------*/
	function userUpdate() 	{
		$this->getMenu() ;
		$form['user_id']		= $this->input->post('user_id');
		$form['employee_id']	= $this->input->post('employee_id');
		$form['approval']		= $this->input->post('approval');
		$form['acl']			= $this->input->post('acl');
		$form['user_active']	= $this->input->post('user_active');

		$this->dataModel->saveUser($form);
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
	} // END userUpdate

		


	/*-------------------------------------------------------------------------------------*/
	//  changePassword
	/*-------------------------------------------------------------------------------------*/
	function changePassword($err=null) 	{
		$this->data['form']['err']	=	$err;
		$this->data['back']	= $this->data['site'] .'/dashboard/';
		
		$this->load->view('change_password',$this->data);
	} // END CHANGE PASSWORD
	
	
	
	/*-------------------------------------------------------------------------------------*/
	//  passwordUpdate
	/*-------------------------------------------------------------------------------------*/
	function passwordUpdate() 	{
		$password		= $this->input->post('password_new');
		$this->dataModel->savePassword($password);

		$this->session->unset_userdata('nik');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('acl');
		$this->session->unset_userdata('manager_id');
		$this->session->unset_userdata('department_id');
		$this->session->unset_userdata('is_auth');
		$this->session->sess_destroy();
		redirect('/home/login/PASSWORD-CHANGED');
	} // END passwordUpdate
	
}	