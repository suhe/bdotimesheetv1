<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller{
	
	function __construct()
	{
		parent::__construct();	
		$this->load->model('adminModel');
	}
	
	function index()
    {
		$this->getMenu();
		$this->data['list'] 	= $this->adminModel->getAdminmenu();
		$this->load->view('system_administration_view',$this->data);
	}
	
	/*-------------------------------------------------------------------------------------*/
	//   App Timesheet | Leave Online
	/*-------------------------------------------------------------------------------------*/
	public function app($err=null) 	{
		$this->getMenu();
		$this->data['form']['err']	=	$err;
		$this->data['back']	= $this->data['site'] .'timesheet/';
		$this->load->view('app',$this->data);
	} 
	/*-------------------------------------------------------------------------------------*/
	
	/*-------------------------------------------------------------------------------------*/
	//  Choice App Timesheet | Leave Online
	/*-------------------------------------------------------------------------------------*/
	public function app_choice($app) 	{
		$result['acl'] = $this->session->userdata ( 'acl' );
		if ($result ['acl'] == "09") {
			redirect ( 'admin/user/' );
		} elseif ($result ['acl'] == "008" || $result ['acl'] == "01" || $result ['acl'] == "02" || $result ['acl'] == "03") {
			redirect ( 'project/' );
		} else {
			redirect ( 'timesheet/' );
		}
	} 
	/*-------------------------------------------------------------------------------------*/
	
	//  passwordUpdate
	
	
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
			if($this->input->post('approval'))		$form['approval']   = $this->input->post('approval');
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
		$totalRow			 = $this->adminModel->getUser($form);
		$this->data['pg']	 = $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->adminModel->getUser($form, $limit, $this->data['pg']['o']);
		$this->load->view('user',$this->data);
	} // END user
	
	
	
	/*-------------------------------------------------------------------------------------*/
	//  userEdit
	/*-------------------------------------------------------------------------------------*/
	function userEdit($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->adminModel->getUserDetail($id);
		
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['message']		= '';
			$this->data['form']['user_id']		= 0;
			$this->data['form']['employee_id']	= 0;
			$this->data['form']['acl']			= '';
			$this->data['form']['email']		= '';
			$this->data['form']['user_active']		= '';
		}
		$this->data['back']		= $this->data['site'] .'/admin/user';
		$this->data['reset']	= $this->data['site'] .'/admin/resetPassword/'.$id;
		$this->data['form']['message']=$msg;

		$this->load->view('user_edit',$this->data);
	} // END userEdit



	/*-------------------------------------------------------------------------------------*/
	//  userUpdate
	/*-------------------------------------------------------------------------------------*/
	function userUpdate() 	{
		$this->getMenu() ;
		$form['user_id']		= $this->input->post('user_id');
		$form['nik']		= $this->input->post('nik');
		$form['employee_id']	= $this->input->post('employee_id');
		$form['approval']		= $this->input->post('approval');
		$form['acl']			= $this->input->post('acl');
		$form['user_active']	= $this->input->post('user_active');

		$this->adminModel->saveUser($form);
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
	} // END userUpdate

	/*-------------------------------------------------------------------------------------*/
	//  changePassword
	/*-------------------------------------------------------------------------------------*/
	function changePassword($err=null) 	{
		$this->data['form']['err']	=	$err;
		$this->data['back']	= $this->data['site'] .'timesheet/';
		$this->load->view('change_password',$this->data);
	} // END CHANGE PASSWORD
	/*-------------------------------------------------------------------------------------*/
	//  passwordUpdate

	/*-------------------------------------------------------------------------------------*/

	function passwordUpdate() 	{
		$password		= $this->input->post('password_new');
		$this->adminModel->savePassword($password);
		$this->session->unset_userdata('nik');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('acl');
		$this->session->unset_userdata('manager_id');
		$this->session->unset_userdata('department_id');
		$this->session->unset_userdata('is_auth');
		$this->session->sess_destroy();
		redirect('/home/login/Password has been changed');
	} // END passwordUpdate
	/*-------------------------------------------------------------------------------------*/
	//  userEdit
	/*-------------------------------------------------------------------------------------*/
	function userReset($id,$employee_id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->adminModel->getUserDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['message']		= '';
			$this->data['form']['user_id']		= 0;
			$this->data['form']['EmployeeID']		= 0;
			$this->data['form']['employee_id']	= 0;
			$this->data['form']['acl']			= '';
			$this->data['form']['email']		= '';
			$this->data['form']['user_active']		= '';
		}
		$this->data['back']		= $this->data['site'] .'/admin/user';
		$this->data['reset']	= $this->data['site'] .'/admin/resetPassword/'.$id;
		$this->data['form']['message']=$msg;

		$this->load->view('user_reset',$this->data);
	} // END userEdit

	/*-------------------------------------------------------------------------------------*/
	function resetUpdate() 	{
		$user_id      = $this->input->post('user_id');
		$employee_id  = $this->input->post('employee_id');
		$nik          = $this->input->post('nik');
		$this->adminModel->resetPassword($user_id, $nik);

		redirect('/admin/userReset/'.$user_id.'/'.$employee_id.'/Password has been Reset to Default Password');
	} // END 

	/*-------------------------------------------------------------------------------------*/
	function syncPassword() 	{
		$data = $this->adminModel->getUserSync();
		if ( count($data)	> 0 ) {
			foreach ($data as $k=>$v) {
			  if ( strlen( $v['employeeid'] ) > 0 ){
			    $this->adminModel->syncPassword($v['employee_id'], $v['employeeid']);
			  }
	    }
	  }
	  
	  echo "SYNC...............";
	} // END 
    
    
    /*-------------------------------------------------------------------------------------*/
	//  user vacation
	/*-------------------------------------------------------------------------------------*/
	function vacation($type=1,$pg=1,$limit=0) 	{
		$this->getMenu();
		$this->data['users'] = $this->adminModel->getUserVacation();
		$this->load->view('user_vacation',$this->data);
	} // END user vacation
    
    function saveVacation(){
        $user = $this->input->post('ID');
        $year = '2013';
        $vacation= $this->input->post('vacation');
        
        $total = COUNT($user);
        for($i=0;$i<$total;$i++):
            if($i<$total):
                if($vacation[$i]!=''):
                    $check = $this->adminModel->getVacationData($user[$i],$year);
                    if($check)
                        $this->adminModel->getUpdateVacation($user[$i],$year,$vacation[$i]);
                    else
                        $this->adminModel->getSaveVacation($user[$i],$year,$vacation[$i]);   
                endif;
            endif;
        endfor;
        redirect($this->input->server('HTTP_REFERER'),301);
    }
}	