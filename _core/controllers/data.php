<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MY_Controller{
	
	function __construct()
	{
		parent::__construct();	
		$this->load->model(array('dataModel','vacationModel'));
	}
	
	function index($type=1, $pg=1, $limit=0) 	{
		redirect("/data/employee/".$type."/". $pg=1 ."/". $limit);
	}
	
	
	/*-------------------------------------------------------------------------------------*/
	//  employee
	/*-------------------------------------------------------------------------------------*/
	function employee($type=1, $pg=1, $limit=0) 	{
		$this->getMenu();
		$form = array();
		if($type==1) {
			$this->session->unset_userdata('nik');
			$this->session->unset_userdata('nickname');
			$this->session->unset_userdata('position');
			$this->session->unset_userdata('group');
		}
		elseif($type==2) {
			$this->session->unset_userdata('nik');
			$this->session->unset_userdata('nickname');
			$this->session->unset_userdata('position');
			$this->session->unset_userdata('group');
			
			if($this->input->post('nik')) 			$form['nik']		= $this->input->post('nik');
			if($this->input->post('nickname'))		$form['nickname']	= $this->input->post('nickname');
			if($this->input->post('position'))		$form['position']	= $this->input->post('position');
			if($this->input->post('group'))			$form['group']   	= $this->input->post('group');
			$this->session->set_userdata($form);
		}
		
		if($this->session->userdata('nik')) 		$form['nik']		= $this->session->userdata('nik');
		if($this->session->userdata('nickname'))	$form['nickname']	= $this->session->userdata('nickname');
		if($this->session->userdata('position')) 	$form['position']	= $this->session->userdata('position');
		if($this->session->userdata('group')) 		$form['group']		= $this->session->userdata('group');
		
		if($limit) {
			$this->session->set_userdata('rpp', $limit);
			$this->rpp = $limit;
		}
		$limit				 = $limit ? $limit : $this->rpp;
		$totalRow			 = $this->dataModel->getEmployee($form);
		$this->data['pg']	 = $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->dataModel->getEmployee($form, $limit, $this->data['pg']['o']);
		$this->load->view('employee',$this->data);
	} // END user
	
	/*-------------------------------------------------------------------------------------*/
	//  employeeEdit
	/*-------------------------------------------------------------------------------------*/
	function employeeEdit($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->dataModel->getEmployeeDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['employeeid']	= $this->dataModel->getLastNikEmployee();
			$this->data['form']['message']		= '';
			$this->data['form']['employee_id']	= 0;
			$this->data['form']['approval_id']	= 0;
			$this->data['form']['employeehiredate']	= date('d/m/Y');
			$this->data['form']['employeestatus']	= '';
			$this->data['form']['employeefirstname']	= '';
			$this->data['form']['project_title']	= '';
			$this->data['form']['employeemiddlename']	= '';
			$this->data['form']['employeelastname']		= '';
			$this->data['form']['employeenickname']		= '';
			$this->data['form']['employeetitle']		= '';
			//$this->data['form']['employeeid']			= '';
			$this->data['form']['employeeemail']		= '';
			$this->data['form']['department_id']		= '';
		}
		$this->data['back'] = $this->data['site'] .'/data/employee';
		$this->data['form']['message']=$msg;
		$this->load->view('employee_edit',$this->data);
	} // END employeeEdit
	
	
	/*-------------------------------------------------------------------------------------*/
	//  employeeUpdate
	/*-------------------------------------------------------------------------------------*/
	function employeeUpdate() 	{
		$this->getMenu() ;
		$form['employee_id']		= $this->input->post('employee_id');
        $form['employeehiredate']	= $this->input->post('hiredate');
        $form['employeestatus']	    = $this->input->post('status');
		$form['approval_id']		= $this->input->post('approval_id');
		$form['employeefirstname']	= $this->input->post('employeefirstname');
		$form['employeemiddlename']	= $this->input->post('employeemiddlename');
		$form['employeelastname']	= $this->input->post('employeelastname');
		$form['employeenickname']	= $this->input->post('employeenickname');
		$form['employeetitle']		= $this->input->post('employeetitle');
		$form['project_title']		= $this->input->post('project_title');
		
		$form['employeeid']			= $this->input->post('employeeid');
		$form['employeeemail']		= $this->input->post('employeeemail');
		$form['department_id']		= $this->input->post('department_id');
		
		$this->dataModel->saveEmployee($form);
	} // END employeeUpdate
	
	
	/*-------------------------------------------------------------------------------------*/
	//  department
	/*-------------------------------------------------------------------------------------*/
	function department( $department_id=0, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->dataModel->getDepartmentDetail($department_id);
		
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['department_id']	= $department_id;
			$this->data['form']['departmentcode']	= '';
			$this->data['form']['department']	= '';
			$this->data['form']['company_id']		= '';
		}
		
		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->dataModel->getDepartment();
		
		$this->load->view('department',$this->data);
	} // END department
	
	
		function departmentUpdate() 	{
			$this->getMenu() ;
			$form['department_id']	= $this->input->post('department_id');
			$form['departmentcode']	= $this->input->post('departmentcode');
			$form['department']		= $this->input->post('department');
			
			$this->dataModel->saveDepartment($form);
			redirect('data/department/0/');
	} // END departmentUpdate
	


	
	
	/*-------------------------------------------------------------------------------------*/
	//  job
	/*-------------------------------------------------------------------------------------*/
	function job( $job_id=0, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->dataModel->getJobDetail($job_id);
		
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['job_id']	= $job_id;
			$this->data['form']['job_no']	= '';
			$this->data['form']['job']		= '';
			$this->data['form']['jobtype_id']		= '';
		}
		
		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->dataModel->getJob();
		
		$this->load->view('job',$this->data);
	} // END department
	
	
	/*-------------------------------------------------------------------------------------*/
	//  departmentUpdate
	/*-------------------------------------------------------------------------------------*/
	function jobUpdate() 	{
		$this->getMenu() ;
		$form['job_id']				= $this->input->post('job_id');
		$form['job_no']				= $this->input->post('job_no');
		$form['job']					= $this->input->post('job');
		$form['jobtype_id']		= $this->input->post('jobtype_id');
		
		$this->dataModel->saveJob($form);
	} // END departmentUpdate
	
		

	/*-------------------------------------------------------------------------------------*/
	//  job
	/*-------------------------------------------------------------------------------------*/
	function jobtype( $jobtype_id=0, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->dataModel->getJobTypeDetail($jobtype_id);
		
		
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['jobtype_id']	= $jobtype_id;
			$this->data['form']['jobtype_no']	= '';
			$this->data['form']['jobtype']		= '';
			// iman edit 2
			//$this->data['form']['department_id']		= '';
		}
		
		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->dataModel->getJobType();
		//print_r($this->data);
		$this->load->view('jobtype',$this->data);
	} // END department
	
	
	/*-------------------------------------------------------------------------------------*/
	//  departmentUpdate
	/*-------------------------------------------------------------------------------------*/
	function jobTypeUpdate() 	{
		$this->getMenu() ;
		$form['jobtype_id']		= $this->input->post('jobtype_id');
		$form['jobtype_no']		= $this->input->post('jobtype_no');
		$form['jobtype']		= $this->input->post('jobtype');
		// iman edit 2
		$form['department_id']		= $this->input->post('department_id');
		
		$this->dataModel->saveJobType($form);
	} // END departmentUpdate
    
    /*-------------------------------------------------------------------------------------*/
	//  Holiday
	/*-------------------------------------------------------------------------------------*/
	function holiday( $jobtype_id=0, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->dataModel->getJobTypeDetail($jobtype_id);
		
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['holiday_id']	= $jobtype_id;
			$this->data['form']['holiday_desc']	= '';
			$this->data['form']['holiday_date']		= '';
		}
		
		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->dataModel->getHoliday();
		$this->load->view('vholiday',$this->data);
	} // END holiday
    
    function HolidayUpdate() 	{
		$this->getMenu() ;
		$form['holiday_id']		= $this->input->post('holiday_id');
		$form['holiday_date']	= $this->input->post('holiday_date');
		$form['holiday_desc']	= $this->input->post('holiday_desc');
		$this->dataModel->saveHoliday($form);
	}
    
    function removeholiday($id)
    {
        $this->dataModel->getRemoveHoliday($id);
        $msg = 'Data Successfuly Deleted !';
        $this->data['form']['message']=$msg;
        redirect($this->input->server('HTTP_REFERER'),301);
    }
    
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

	function vacationapp($req=''){ 
        $this->getMenu();
        if($req=='approval')
            $records = $this->vacationModel->getApprovalVacation('HRD',0);
        elseif($req=='cancel')
            $records = $this->vacationModel->getApprovalVacation('',1);    
        else    
            $records = $this->vacationModel->getReqVacationList('',$req);
 
        $this->data['records'] = $records ;
        $this->data['balanced'] = $this->vacationModel->getUserBalanced($this->session->userdata('employee_id'));
        $this->data['req'] = $req;
        $count = COUNT($this->vacationModel->getReqVacationList('',$req));
		$this->data['req_count'] = $count > 0 ? '<b style="color:blue">('.$count.')</b>': ''; 
        $this->load->view('vacation_app',$this->data);
    }
    
    function approvalVacation($user,$day,$month,$year,$APP=''){ 
        $date = $year.'-'.$month.'-'.$day;
            
        $this->vacationModel->getAppVacationByHRD($user,$date);
        if ($APP)
            redirect('data/vacationapp/'.$APP,301);
        else    
            redirect($this->input->server('HTTP_REFERER'),301);
    }
    
    function testemail()
    {
        $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'smtp.bdo.co.id',
        'smtp_port' => 587,
        'smtp_user' => 'ssuhendar@bdo.co.id',
        'smtp_pass' => 'Bdo12345',
        'mailtype'  => 'html', 
        'charset'   => 'iso-8859-1'
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('ssuhendar@bdo.co.id','Suhendar');
        $this->email->to('hendarsyah@gmail.com'); 
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');  
        $result = $this->email->send();
        echo $this->email->print_debugger();
    }    
}	