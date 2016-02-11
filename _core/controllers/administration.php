<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administration extends MY_Controller{
	
	function __construct()
	{
		parent::__construct();	
		$this->load->model('overtimeModel');
	}
	
			/*-------------------------------------------------------------------------------------*/
	//  timesheet
	/*-------------------------------------------------------------------------------------*/
	function index() 	{
		$this->getMenu();
		$this->load->model('hrdModel');
		$this->data['list'] 	= $this->hrdModel->getAdminmenu();

		$this->load->view('administration_view',$this->data);
	
	} // END TIMESHEET
	
		/*-------------------------------------------------------------------------------------*/
	//  timesheet
	/*-------------------------------------------------------------------------------------*/
	function overtime($type=1, $pg=1, $limit=0) 	{
		$this->getMenu();
		$this->overtimeModel->syncOvertimeStatus();
		
		$this->data['back']		= $this->data['site'] .'/administration/overtime';
		$this->data['table'] 	= $this->overtimeModel->getOvertime();
		$this->data['waiting'] 	= $this->overtimeModel->getOvertimeWaiting();
		$this->data['request'] 	= $this->overtimeModel->getOvertimeRequest();
		$this->data['done'] 	= $this->overtimeModel->getOvertimeDone();
	
		$this->load->view('overtime',$this->data);
	
	} // END TIMESHEET
	

	/*-------------------------------------------------------------------------------------*/
	//  
	/*-------------------------------------------------------------------------------------*/
	function overtimeWaiting( $id ) 	{
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'/administration/overtime';
		$this->data['table'] = $this->overtimeModel->getOvertimeDetail($id);
		$this->load->view('overtime_waiting_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	
		/*-------------------------------------------------------------------------------------*/
	//  
	/*-------------------------------------------------------------------------------------*/
	function overtimeRequest( $id ) 	{
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'/administration/overtime';
		$this->data['table'] = $this->overtimeModel->getOvertimeDetail($id);
		$this->load->view('overtime_request_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	
	function overtimeApproved( $id, $overtime_status_id ) 	{
		$this->getMenu() ;
		$this->data['back']	= $this->data['site'] .'/administration/overtime';
		$this->data['table'] = $this->overtimeModel->getOvertimeDetail($overtime_status_id);
		$this->load->view('overtime_approved_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	

	/*-------------------------------------------------------------------------------------*/
	//  overtimeEdit
	/*-------------------------------------------------------------------------------------*/
	function overtimeEdit($overtimeid,$overtime_status_id,$week, $year, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->overtimeModel->getOvertimeEditDetail($overtimeid);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['overtimeid']	 = $overtimeid;
			$this->data['form']['overtime_status_id']	= $overtime_status_id ;
			$this->data['form']['week']			 = date('W');
			$this->data['form']['year']			 = date('Y');
			$this->data['form']['overtimedate']  = date('Y/m/d',now());
			$this->data['form']['hour']			 = '';
			$this->data['form']['notes']			 = '';
		}

		$this->overtimeModel->syncOvertimeDetail($this->data['form']['overtime_status_id'],$this->data['form']['week'],$this->data['form']['year']);
		
		$this->data['back']	= $this->data['site'] .'/administration/overtime';
		$this->data['request']	= $this->data['site'] .'/administration/overtimeStatusRequest/'.$overtime_status_id;

		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->overtimeModel->getOvertimeEditList($overtime_status_id);
		$this->load->view('overtime_edit',$this->data);
	} // END TIMESHEET PROJECT EDIT


	/*-------------------------------------------------------------------------------------*/
	//  overtimeEdit
	/*-------------------------------------------------------------------------------------*/
	function overtimeView($overtimeid,$overtime_status_id,$week, $year, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->overtimeModel->getOvertimeEditDetail($overtimeid);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['overtimeid']	 = $overtimeid;
			$this->data['form']['overtime_status_id']	= $overtime_status_id ;
			$this->data['form']['week']			 = date('W');
			$this->data['form']['year']			 = date('Y');
			$this->data['form']['overtimedate'] = date('Y/m/d',now());
			$this->data['form']['hour']			 = '';
			$this->data['form']['notes']			 = '';
		}

		
		$this->data['back']	= $this->data['site'] .'/administration/overtime';
		$this->data['request']	= $this->data['site'] .'/administration/overtimeStatusRequest/'.$overtime_status_id;

		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->overtimeModel->getOvertimeEditList($overtime_status_id);
		$this->load->view('overtime_view',$this->data);
	} // END TIMESHEET PROJECT EDIT	
	
	/*-------------------------------------------------------------------------------------*/
	//  timesheetUpdate
	/*-------------------------------------------------------------------------------------*/
	function overtimeUpdate() 	{
		$this->getMenu() ;
		$form['overtimeid']			= $this->input->post('overtimeid');
		$form['overtime_status_id']	= $this->input->post('overtime_status_id');
		$form['hour']	= $this->input->post('hour');

		if ( isset( $_POST['hour']) ) {
			if ( count($_POST['hour']) > 0 ) {
				foreach ($_POST['hour'] as $k=>$v) {
					$sql = "
						update overtime  set hour  = ".$_POST['hour'][$k].", office=8, overtime = ".$_POST['hour'][$k]." - 8
						where overtimeid = ".$_POST['overtimeid'][$k]; 
					$this->db->query($sql);		
				}
			}
		}
		
		//$this->overtimeModel->saveOvertime($form);
		redirect('administration/overtimeEdit/0/'.$form['overtime_status_id'].'/0/0/');
	} // END TIMESHEET UPDATE
	
	/*-------------------------------------------------------------------------------------*/
	//  timesheetUpdate
	/*-------------------------------------------------------------------------------------*/
	function overtimeDel($overtimeid,$overtime_status_id) 	{
		$this->getMenu() ;

		if ( isset( $overtimeid ) ) {
			if ( strlen( $overtimeid ) > 0 ) {
					$sql = "delete  from overtime where overtimeid = ".$overtimeid; 
					$this->db->query($sql);		
			}
		}
		
		//$this->overtimeModel->saveOvertime($form);
		redirect('administration/overtimeEdit/0/'.$overtime_status_id.'/0/0/');
	} // END TIMESHEET UPDATE
	
		/*-------------------------------------------------------------------------------------*/
	//  timesheetUpdate
	/*-------------------------------------------------------------------------------------*/
	function overtimeStatusRequest($overtime_status_id) 	{
		$this->getMenu() ;
		$sql = "update overtime_status  set overtime_approval  = 1, drequest='".date('Y-m-d H:i:s')."'
			where overtime_status_id  = ".$overtime_status_id; 
		$this->db->query($sql);		


		$sql = "update overtime set overtime_approval  = 1
			where overtime_status_id  = ".$overtime_status_id; 
		$this->db->query($sql);		

		redirect('administration/overtime/');
	} // END TIMESHEET UPDATE
	
	
	function hrd() {
		$this->getMenu() ;
		$this->load->view('hrd',$this->data);
	}
	
/*-------------------------------------------------------------------------------------*/
	//  
	/*-------------------------------------------------------------------------------------*/
	function hrdOvertime() 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['back']	= $this->data['site'] .'/administration/hrd';
		$this->data['table'] = $this->hrdModel->getOvertimeWaiting();
		$this->load->view('hrd_overtime_waiting',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	
	
	/*-------------------------------------------------------------------------------------*/
	function hrdOvertimeApprove($overtime_status_id) 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['back']	= $this->data['site'] .'/administration/hrd';
		$this->data['approve']	= $this->data['site'] .'/administration/hrdOvertimeSaveApprove/'.$overtime_status_id;
		$this->data['table'] = $this->hrdModel->getOvertimeDetail($overtime_status_id);
		$this->load->view('hrd_overtime_approve',$this->data);
	} // END TIMESHEET WEEKLY VIEW

	/*-------------------------------------------------------------------------------------*/	
	function hrdOvertimeSaveApprove($overtime_status_id) 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');

		$this->getMenu() ;
		$sql = "update overtime_status  set overtime_approval  = 2, dapproval='".date('Y-m-d H:i:s')."', approval_id = ".$this->session->userdata('employee_id') ."
			where overtime_status_id  = ".$overtime_status_id; 
		$this->db->query($sql);		


		$sql = "update overtime set overtime_approval  = 2
			where overtime_status_id  = ".$overtime_status_id; 
		$this->db->query($sql);		

		redirect('administration/hrdOvertime/');
		
	} // END TIMESHEET WEEKLY VIEW	
	
	
		
	/*-------------------------------------------------------------------------------------*/
	function hrdOvertimeApproved() 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['back']	= $this->data['site'] .'/administration/hrd';
		$this->data['table'] = $this->hrdModel->getOvertimeApproved();
		$this->load->view('hrd_overtime_approved',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	
	
		/*-------------------------------------------------------------------------------------*/
	function hrdOvertimeInfo($overtime_status_id) 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['back']	= $this->data['site'] .'/administration/hrdOvertimeApproved';
		$this->data['table'] = $this->hrdModel->getOvertimeDetail($overtime_status_id) ;
		$this->load->view('hrd_overtime_info',$this->data);
	} // END TIMESHEET WEEKLY VIEW


/*-------------------------------------------------------------------------------------*/
	//  
	/*-------------------------------------------------------------------------------------*/
	function hrdJob($type=1, $pg=1, $limit=0) 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['back']	= $this->data['site'] .'/administration/hrd';
		$form = array();
		if($type==1) {
			$this->session->unset_userdata('request_by');
			$this->session->unset_userdata('week_at');
			$this->session->unset_userdata('year_at');
			$this->session->unset_userdata('approve_by');			
		}
		elseif($type==2) {
			$this->session->unset_userdata('request_by');
			$this->session->unset_userdata('week_at');
			$this->session->unset_userdata('year_at');
			$this->session->unset_userdata('approve_by');			
			
			if($this->input->post('request_by')){	$form['request_by'] = $this->input->post('request_by');$this->session->set_userdata('request_by', $form['request_by']);}
			if($this->input->post('week_at')){		$form['week_at'] = $this->input->post('week_at');$this->session->set_userdata('week_at', $form['week_at']);}
			if($this->input->post('year_at')){		$form['year_at'] = $this->input->post('year_at');$this->session->set_userdata('year_at', $form['year_at']);}
			if($this->input->post('approve_by')){	$form['project_by'] = $this->input->post('approve_by');$this->session->set_userdata('project_by', $form['project_by']);}
		}
		
		if($this->session->userdata('request_by')) 	$form['request_by'] = $this->session->userdata('request_by');
		if($this->session->userdata('week_at')) 	$form['week_at']	= $this->session->userdata('week_at');
		if($this->session->userdata('year_at')) 	$form['year_at']   	= $this->session->userdata('year_at');
		if($this->session->userdata('approve_by')) 	$form['approve_by']	= $this->session->userdata('approve_by');
		if($limit) {
			$this->session->set_userdata('rpp', $limit);
			$this->rpp = $limit;
		}
		$limit				 	= $limit ? $limit : $this->rpp;
		$totalRow			 	= $this->hrdModel->getJobWaiting($form);
		$this->data['pg']	 	= $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->hrdModel->getJobWaiting($limit, $this->data['pg']['o']);
		$this->load->view('hrd_jobs_waiting',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	
/*-------------------------------------------------------------------------------------*/
	function hrdJobApprove($timesheet_status_id) 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['id']	= $timesheet_status_id ;
		$this->data['back']	= $this->data['site'] .'/administration/hrdJob';
		$this->data['approve']	= $this->data['site'] .'/administration/hrdJobSaveApprove/'.$timesheet_status_id;
		$this->data['return']	= $this->data['site'] .'/administration/hrdJobSaveApprove/'.$timesheet_status_id.'/return';
	
		$this->data['table'] = $this->hrdModel->getJobTimesheet($timesheet_status_id);
		$this->load->view('hrd_job_waiting_detail',$this->data);
		} // END TIMESHEET WEEKLY VIEW
		
	function hrdJobSaveApprove($timesheet_status_id,$vreturn=0) 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$timesheet_approval=($vreturn) ? 2 : 3; // 3 Return , 2 Approval (3 :)
		$sql = "update timesheet_status  set timesheet_approval  =$timesheet_approval, dapproval='".date('Y-m-d H:i:s')."', approval_id = ".$this->session->userdata('employee_id') ."
			where timesheet_status_id  = ".$timesheet_status_id; 
		$this->db->query($sql);		


		$sql = "update timesheet set timesheet_approval  = $timesheet_approval
			where timesheet_status_id  = ".$timesheet_status_id; 
		$this->db->query($sql);		

		redirect('administration/hrdJob/');
			
	}

	/*-------------------------------------------------------------------------------------*/
	function hrdJobApproved($type=1, $pg=1, $limit=0) 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['back']	= $this->data['site'] .'/administration/hrd';
		$form = array();
		if($type==1) {
			$this->session->unset_userdata('request_by');
			$this->session->unset_userdata('week_at');
			$this->session->unset_userdata('year_at');
			$this->session->unset_userdata('approve_by');			
		}
		
		if($this->session->userdata('request_by')) 	$form['request_by'] = $this->session->userdata('request_by');
		if($this->session->userdata('week_at')) 	$form['week_at']	= $this->session->userdata('week_at');
		if($this->session->userdata('year_at')) 	$form['year_at']   	= $this->session->userdata('year_at');
		if($this->session->userdata('approve_by')) 	$form['approve_by']	= $this->session->userdata('approve_by');
		
		if($limit) {
			$this->session->set_userdata('rpp', $limit);
			$this->rpp = $limit;
		}
		$limit				 	= $limit ? $limit : $this->rpp;
		$totalRow			 	= $this->hrdModel->getJobTimesheetApproved($form);
		
		$this->data['pg']	 	= $this->setPaging($totalRow, $pg, $limit);
		$this->data['table']	= $this->hrdModel->getJobTimesheetApproved($form, $limit, $this->data['pg']['o']);
		
		$this->load->view('hrd_jobs_approved',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	
	
	/** Search HRDJobApproved **/
	function searchhrdJobApproved() {
		$this->session->unset_userdata('request_by');
		$this->session->unset_userdata('week_at');
		$this->session->unset_userdata('year_at');
		$this->session->unset_userdata('approve_by');			
			
		if($this->input->post('request_by')){	$form['request_by'] = $this->input->post('request_by');$this->session->set_userdata('request_by', $form['request_by']);}
		if($this->input->post('week_at')){		$form['week_at']    = $this->input->post('week_at');$this->session->set_userdata('week_at', $form['week_at']);}
		if($this->input->post('year_at')){		$form['year_at']    = $this->input->post('year_at');$this->session->set_userdata('year_at', $form['year_at']);}
		if($this->input->post('approve_by')){	$form['approve_by'] = $this->input->post('approve_by');$this->session->set_userdata('approve_by', $form['approve_by']);}
		
		redirect('administration/hrdJobApproved/2',301);
	}
	
	/*-------------------------------------------------------------------------------------*/
	function hrdJobInfo($timesheet_status_id) 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['id']	= $timesheet_status_id ;
		$this->data['back']	= $this->data['site'] .'/administration/hrdJobApproved';
		$this->data['table'] = $this->hrdModel->getJobTimesheet($timesheet_status_id);
		$employee_name=$this->data['table']['0']['employeefirstname'] .' '. $this->data['table']['0']['employeemiddlename'] .' '. $this->data['table']['0']['employeelastname'];
		$this->data['employee_name']=$employee_name;
		$this->load->view('hrd_job_approved_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW


	
	/*-------------------------------------------------------------------------------------*/
	function Aging() 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['back']	= $this->data['site'] .'/administration/hrd';
		$this->data['table'] = $this->hrdModel->getAging();
		$this->load->view('hrd_aging',$this->data);
	} // END TIMESHEET WEEKLY VIEW	
	

	/*-------------------------------------------------------------------------------------*/
	function AgingInfo($timesheet_status_id) 	{
		$this->getMenu() ;
		$this->load->model('hrdModel');
		$this->data['id']	= $timesheet_status_id ;
		$this->data['back']	= $this->data['site'] .'/administration/hrdJobApproved';
	
		$this->data['table'] = $this->hrdModel->getJobTimesheet($timesheet_status_id);
		$this->load->view('hrd_job_approved_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW	
}	