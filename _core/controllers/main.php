<?php
/*
Update for ROLL UP Project
update project,
(
  SELECT
    project_id,
    sum(budget_hour) budget_hour,
    sum(budget_cost) budget_cost
  FROM project_team p
  group by project_id
) b
set
  project.budget_hour = b.budget_hour,
  project.budget_cost = b.budget_cost
where project.project_id = b.project_id


** TIMESHEET
- TIMESHEET POSTING TABLE : - PROJECT 
							- WEEK / YAR 
- Create Project SElection / combobox ( project name ) di project info							
- Update onchange di Week tuk parsing Timeshee posting table
- tambah delete untuk timesheet yg blum di approved
- view only untuk timesheet yg approved
- Tampilin timsheheet table berdasarkan project dan periode week / year baik approved maupun non approved


*/
class Main extends Controller {
	public $data;

	function __construct() 	{
		parent::Controller();
		$this->data['base_url'] = $this->config->item('base_url');
		$this->data['site'] 	= $this->data['base_url'] . $this->config->item('index_page');
		$this->data['is_auth'] 	= $this->session->userdata('is_auth');
		$this->data['nik'] 	= $this->session->userdata('nik');
		$this->data['err'] 	= '';
		$this->rpp = $this->session->userdata('rpp') ? $this->session->userdata('rpp') : 10;
	}

	function index($msg='') 	{
		$this->load->library('encrypt');
		//echo $this->encrypt->encode('123456');

		$this->data['msg']		= $msg;
		
		if ($this->data['is_auth'])		{
			redirect('main/dashboard/');
		}
		$this->load->view('main_login',$this->data);
	} // END INDEX


	/*-------------------------------------------------------------------------------------*/
	//  login  
	/*-------------------------------------------------------------------------------------*/
	function login($err='') 	{
		$this->load->library('encrypt');
		$this->getMenu() ;
		$this->data['msg']  = '';
		$nik 				= $this->input->post('nik');
		$result				= $this->modelMain->getLogin($nik);
		
		if($result && $this->input->post('pass')==$this->encrypt->decode($result['pass'])) {
		//if($result) {
//			$this->session->set_userdata('nik', $result['nik']);
			$this->session->set_userdata('employee', $result['employeefirstname'] .' ' . $result['employeemiddlename'] .' ' .  $result['employeelastname']);
			$this->session->set_userdata('acl', $result['acl']);
			$this->session->set_userdata('user_id', $result['user_id']);
			$this->session->set_userdata('employee_id', $result['employee_id']);
			$this->session->set_userdata('department_id', $result['department_id']);
			$this->session->set_userdata('manager_id', $result['manager_id']);
			$this->session->set_userdata('is_auth', TRUE);
/*			
			if ( $this->encrypt->decode($result['pass']) =='123456'){
				//redirect('/main/changePassword/');
			}
			else {
*/
				if ($result['acl']=="5"){
					redirect('main/user/');
				} 
				elseif ($result['acl']=="4" || $result['acl']=="3" ){
					redirect('main/project/');
				} else {
					redirect('main/timesheet/');
				}	
				
	//		}	
		}
		else {
			$this->data['msg']   		= 'Invalid NIK or Password';
			$this->load->view('main_login',$this->data);
		}
	}	// END LOGIN


	/*-------------------------------------------------------------------------------------*/
	//  logout
	/*------------------------------------------------------------------------------------*/
	function logout() 	{
		//$data	= $this->data;
		$this->session->unset_userdata('nik');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('acl');
		$this->session->unset_userdata('manager_id');
		$this->session->unset_userdata('department_id');
		$this->session->unset_userdata('is_auth');
		$this->session->sess_destroy();
		redirect();
	}	// END LOGOUT


	/*-------------------------------------------------------------------------------------*/
	//  dashboard
	/*-------------------------------------------------------------------------------------*/
	function dashboard() 	{
		$this->getMenu() ;
		$this->load->view('dashboard',$this->data);
	} // END DASHBOARD

	
	/*-------------------------------------------------------------------------------------*/
	//  client
	/*-------------------------------------------------------------------------------------*/
	function client($type=1, $pg=1, $limit=0) 	{
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
		$limit				 = $limit ? $limit : $this->rpp;
		$totalRow			 = $this->modelMain->getClient($form);
		$this->data['pg']	 = $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->modelMain->getClient($form, $limit, $this->data['pg']['o']);
		$this->load->view('client',$this->data);
	} // END CLIENT
	

	/*-------------------------------------------------------------------------------------*/
	//  clientEdit
	/*-------------------------------------------------------------------------------------*/
	function clientEdit($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->modelMain->getClientDetail($id);
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
		$this->data['back']	= $this->data['site'] .'/main/client';
		$this->data['form']['message']=$msg;

		$this->load->view('client_edit',$this->data);
	} // END CLIENT EDIT


	/*-------------------------------------------------------------------------------------*/
	//  clientUpdate
	/*-------------------------------------------------------------------------------------*/
	function clientUpdate() 	{
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

		$this->modelMain->saveClient($form);
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
	} // END CLIENT UPDATE


	/*-------------------------------------------------------------------------------------*/
	//  project
	/*-------------------------------------------------------------------------------------*/
	function project($type=1, $pg=1, $limit=0) 	{
		$this->getMenu();
		$form = array();
		if($type==1) {
			$this->session->unset_userdata('client_name');
			$this->session->unset_userdata('project_no');
			$this->session->unset_userdata('project');
			
		}
		elseif($type==2) {
			$this->session->unset_userdata('client_name');
			$this->session->unset_userdata('project_no');
			$this->session->unset_userdata('project');			
			
			if($this->input->post('client_name'))		$form['client_name'] = $this->input->post('client_name');
			if($this->input->post('project_no'))		$form['project_no']  = $this->input->post('project_no');
			if($this->input->post('project')) 			$form['project']   = $this->input->post('project');
			
			$this->session->set_userdata($form);
		}
		
		if($this->session->userdata('client_name')) $form['client_name'] = $this->session->userdata('client_name');
		if($this->session->userdata('project_no')) 	$form['project_no']	 = $this->session->userdata('project_no');
		if($this->session->userdata('project')) 	$form['project']   = $this->session->userdata('project');
		
		if($limit) {
			$this->session->set_userdata('rpp', $limit);
			$this->rpp = $limit;
		}
		$limit				 = $limit ? $limit : $this->rpp;
		$totalRow			 = $this->modelMain->getProject($form);
		$this->data['pg']	 = $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->modelMain->getProject($form, $limit, $this->data['pg']['o']);
		$this->load->view('project',$this->data);
	} // END PROJECT

	/*-------------------------------------------------------------------------------------*/
	//  projectEdit
	/*-------------------------------------------------------------------------------------*/
	function projectView($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->modelMain->getProjectDetail($id);
		
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['message']		= '';
			$this->data['form']['client_id']	= 0;
			$this->data['form']['project_id']	= 0;
			$this->data['form']['project_no']	= '';
			$this->data['form']['project']	= '';
			$this->data['form']['location']	= '';
			$this->data['form']['year_end']		= '';
			$this->data['form']['start_date']		= '';
			$this->data['form']['finish_date']			= '';
			$this->data['form']['contract_no']			= '';
			$this->data['form']['client_approval']		= '';	
			$this->data['form']['client_approval_date']	= '';
			$this->data['form']['status_collection']	= '';
			$this->data['form']['project_status']		= '';	
			$this->data['form']['budget_hour']		= '';
			$this->data['form']['hour']			= '';
			$this->data['form']['budget_cost']			= '';
			$this->data['form']['cost']			= '';
		}
		$this->data['back']	= $this->data['site'] .'/main/project';
		$this->data['approve']	= $this->data['site'] .'/main/projectApprove/'. $id;
		
		$this->data['form']['message']=$msg;
		$this->data['client'] = $this->modelMain->getClientOption();
		$this->data['cclient'] ="";
		$aTeam = $this->modelMain->getProjectTeamStructure($id);
		
		$team ="";
		$x =0;
		
		for ($i = 0; $i < count( $aTeam ) ; $i++) {
			$level = '';
			$x ++;
			
			if ($aTeam[$i]['lookup_code'] =='01'){
				$level = 'PIC';
			}
			
			if ($aTeam[$i]['lookup_code'] > '041'){
				$level = 'ASS';
			}
			
			$team .= "
					
					<tr>
					<td>".$x."
					<td>".$aTeam[$i]['lookup_label']. " ( " .$aTeam[$i]['tipe'] ." )
					<td>".$this->htmlEmployeeListView('employee_id[]',$aTeam[$i]['employee_id'],$level) ;
		}
		$this->data['team'] = $team;
		$this->data['header_team'] = $aTeam;
		
		$this->data['table_job'] = $this->modelMain->getProjectJob($id);
		$this->data['table'] = $this->modelMain->getProjectAuditor($id);
		
		$this->load->view('project_view',$this->data);
	} // END PROJECT VIEW



	function projectApprove($id, $msg='') 	{
		$this->modelMain->approveProject($id);
		$this->projectView($id);
	}	
	/*-------------------------------------------------------------------------------------*/
	//  projectEdit
	/*-------------------------------------------------------------------------------------*/
	function projectEdit($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->modelMain->getProjectDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['message']		= '';
			$this->data['form']['client_id']	= 0;
			$this->data['form']['project_id']	= 0;
			$this->data['form']['project_no']	= '';
			$this->data['form']['project']	= '';
			$this->data['form']['location']	= '';
			$this->data['form']['year_end']		= '';
			$this->data['form']['start_date']		= '';
			$this->data['form']['finish_date']			= '';
			$this->data['form']['contract_no']			= '';
			$this->data['form']['client_approval']		= '';	
			$this->data['form']['client_approval_date']	= '';
			$this->data['form']['status_collection']	= '';
			$this->data['form']['project_status']		= '';	
			$this->data['form']['budget_hour']		= '';
			$this->data['form']['hour']			= '';
			$this->data['form']['budget_cost']			= '';
			$this->data['form']['cost']			= '';
		}
		$this->data['back']	= $this->data['site'] .'/main/project';
		$this->data['approve']	= $this->data['site'] .'/main/projectApprove/'. $id;
		
		$this->data['form']['message']=$msg;
		$this->data['client'] = $this->modelMain->getClientOption();
		$this->data['cclient'] ="";
		$aTeam = $this->modelMain->getProjectTeamStructure($id);
		
		$team ="";
		$x =0;
		
		for ($i = 0; $i < count( $aTeam ) ; $i++) {
			$level = '';
			$x ++;
			
			if ($aTeam[$i]['lookup_code'] =='01'){
				$level = 'PIC';
			}
			
			if ($aTeam[$i]['lookup_code'] > '041'){
				$level = 'ASS';
			}

			$team .= "		<input type=hidden name=teamid[] value=".$aTeam[$i]['teamid'].">
							<input type=hidden name=project_title[] value='".$aTeam[$i]['lookup_code']."'>
							<tr>
							<td>".$x."
							<td>".$aTeam[$i]['lookup_label']. " ( " .$aTeam[$i]['tipe'] ." )
					<td>".$this->htmlEmployeeList('employee_id[]',$aTeam[$i]['employee_id'],$level) ;
		}
		$this->data['team'] = $team;
		$this->data['header_team'] = $aTeam;
		
		$this->data['table_job'] = $this->modelMain->getProjectJob($id);
		$this->data['table'] = $this->modelMain->getProjectAuditor($id);
		
		$this->load->view('project_edit',$this->data);
	} // END PROJECT EDIT
	
	
	/*-------------------------------------------------------------------------------------*/
	//  projectUpdate
	/*-------------------------------------------------------------------------------------*/
	function projectUpdate() 	{
		$this->getMenu() ;
		$form['client_id']		= $this->input->post('client_id');
		$form['project_id']		= $this->input->post('project_id');
		$form['project_no']		= $this->input->post('project_no');
		$form['project']			= $this->input->post('project');
		$form['location']		= $this->input->post('location');
		$form['year_end']			= $this->input->post('year_end');
		$form['start_date']			= $this->input->post('start_date');
		$form['finish_date']				= $this->input->post('finish_date');
		$form['contract_no']		= $this->input->post('contract_no');
		$form['client_approval']		= $this->input->post('client_approval');
		$form['client_approval_date']		= $this->input->post('client_approval_date');
		$form['status_collection']		= $this->input->post('status_collection');
		$form['teamid']				= $this->input->post('teamid');
		$form['project_status']		= $this->input->post('project_status');
		$form['project_title']		= $this->input->post('project_title');
		$form['employee_id']		= $this->input->post('employee_id');
		//$form['budget_hour']		= $this->input->post('budget_hour');
		//$form['budget_cost']		= $this->input->post('budget_cost');
		
		$this->modelMain->saveProject($form);
		//$this->load->view('projectEdit/'.$form['[project_id'].'/SAVED');
	} // END PROJECT UPDATE
	
	
	/*-------------------------------------------------------------------------------------*/
	// projectJobEdit
	/*-------------------------------------------------------------------------------------*/
	function projectJobEdit($id, $mode) 	{
		$this->getMenu() ;
		$this->data['id']		= $id;
		$this->data['mode']		= $mode;
		$this->data['back']	= $this->data['site'] .'/main/projectEdit/'.$id;
		
		if ( $mode=='add'){
			$this->data['table'] = $this->modelMain->getJobList($id);
		}
		
		if ( $mode=='del'){
			$this->data['table'] = $this->modelMain->getJobListDel($id);
		}
		$this->load->view('project_job_edit',$this->data);
	} // END PROJECT JOB EDIT
	
	
	/*-------------------------------------------------------------------------------------*/
	//  projectJobUpdate
	/*-------------------------------------------------------------------------------------*/
	function projectJobUpdate() 	{
		$this->getMenu() ;
		$form['mode']			= $this->input->post('mode');
		$form['project_id']	= $this->input->post('project_id');
		$form['job_id']		= $this->input->post('job_id');
		if ( count( $form['job_id']) >0){
			foreach ($form['job_id'] as $k=>$v) {
				$this->modelMain->saveProjectJob($form['mode'], $form['project_id'], $v);
			}
		}
		redirect('/main/projectEdit/'. $form['project_id'] .'/SAVED');
	} // END PROJECT JOB UPDATE



	/*-------------------------------------------------------------------------------------*/
	//  projectUpdate
	/*-------------------------------------------------------------------------------------*/
	function projectBudgetCost() 	{
		$this->getMenu() ;
		
		$form['project_id']		= $this->input->post('project_id');
		$form['id']		= $this->input->post('id');
		$form['01_hour']		= $this->input->post('01_hour');
		$form['01_cost']		= $this->input->post('01_cost');
		$form['02_hour']		= $this->input->post('02_hour');
		$form['02_cost']		= $this->input->post('02_cost');
		$form['03_hour']		= $this->input->post('03_hour');
		$form['03_cost']		= $this->input->post('03_cost');
		$form['041_hour']		= $this->input->post('041_hour');
		$form['041_cost']		= $this->input->post('041_cost');
		$form['042_hour']		= $this->input->post('042_hour');
		$form['042_cost']		= $this->input->post('042_cost');
		$form['043_hour']		= $this->input->post('043_hour');
		$form['043_cost']		= $this->input->post('043_cost');
		$form['044_hour']		= $this->input->post('044_hour');
		$form['044_cost']		= $this->input->post('044_cost');

		$this->modelMain->saveProjectBudgetCost($form);
		redirect ('/main/projectEdit/'.$form['project_id'].'/SAVED');
	} // END PROJECT UPDATE
		
	/*-------------------------------------------------------------------------------------*/
	//  timesheet
	/*-------------------------------------------------------------------------------------*/
	function timesheet($type=1, $pg=1, $limit=0) 	{
		$this->getMenu();
		$form = array();
		if($type==1) {
			$this->session->unset_userdata('client_no');
			$this->session->unset_userdata('client_name');
			$this->session->unset_userdata('project_no');
		}
		elseif($type==2) {
			$this->session->unset_userdata('client_no');
			$this->session->unset_userdata('client_name');
			$this->session->unset_userdata('project_no');
			
			if($this->input->post('client_no')) 		$form['client_no']   = $this->input->post('client_no');
			if($this->input->post('client_name'))		$form['client_name'] = $this->input->post('client_name');
			if($this->input->post('project_no'))		$form['project_no']  = $this->input->post('project_no');
			$this->session->set_userdata($form);
		}
		
		if($this->session->userdata('client_no')) 	$form['client_no']   = $this->session->userdata('client_no');
		if($this->session->userdata('client_name')) $form['client_name'] = $this->session->userdata('client_name');
		if($this->session->userdata('project_no')) 	$form['project_no']	 = $this->session->userdata('project_no');
		
		if($limit) {
			$this->session->set_userdata('rpp', $limit);
			$this->rpp = $limit;
		}
		$limit				 = $limit ? $limit : $this->rpp;
		$totalRow			 = $this->modelMain->getProject($form);
		$this->data['pg']	 = $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->modelMain->getProject($form, $limit, $this->data['pg']['o']);
		//$this->data['table_weekly'] = $this->modelMain->getTimesheetStatus();
		
		$this->data['waiting'] = $this->modelMain->getTimesheetWaiting();
		$this->data['request'] = $this->modelMain->getTimesheetRequest();
		$this->data['active'] = $this->modelMain->getTimesheetActive();
		$this->data['done'] = $this->modelMain->getTimesheetDone();
		
		$this->load->view('timesheet',$this->data);
	} // END TIMESHEET
	

	/*-------------------------------------------------------------------------------------*/
	//  timesheetEdit
	/*-------------------------------------------------------------------------------------*/
	function timesheetEdit($id, $msg='') 	{
		$id	= $this->uri->segment(3);
		$week = $this->uri->segment(4);
		$year = $this->uri->segment(5);
		$msg  = $this->uri->segment(6);
		
		$this->getMenu() ;
		//$this->data['form']	= $this->modelMain->getProjectDetail($project_id);
		$this->data['form']	= $this->modelMain->getTimesheetWeekDetail($id);
		
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['id']			= $id;
			$this->data['form']['project_id']= '';
			$this->data['form']['job_id']		= '';
			$this->data['form']['week']		= $week;
			$this->data['form']['year']		= $year;
			$this->data['form']['timesheetdate']		= '';
			$this->data['form']['hour']		= '';
			$this->data['form']['cost']		= '';
			
			$this->data['form']['notes']		= '';
		}
		//$this->data['form']	= $this->modelMain->getTimesheetProject($id);
		
		$this->data['back']	= $this->data['site'] .'/main/timesheet';
		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->modelMain->getTimesheetProject($id);
		
		$this->load->view('timesheet_edit',$this->data);
	} // END TIMESHEET EDIT

	
	/*-------------------------------------------------------------------------------------*/
	//  timesheetWeeklyView
	/*-------------------------------------------------------------------------------------*/
	function timesheetWeeklyView( $week, $year) 	{
		$this->getMenu() ;
		$this->data['week']	= $week;
		$this->data['year']	= $year;
		$this->data['back']	= $this->data['site'] .'/main/timesheet';
		$this->data['table'] = $this->modelMain->getTimesheetWeekView($week, $year);
		$this->data['flag']	= $this->modelMain->getTimesheetRequest($week, $year) ;
		$this->data['flag_approval']	= $this->modelMain->getTimesheetApproval($week, $year) ;
		
		//$this->data['request'] = $this->data['flag'] ;
		$this->load->view('timesheet_weekly_view',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	

	/*-------------------------------------------------------------------------------------*/
	//  timesheetWeeklyView
	/*-------------------------------------------------------------------------------------*/
	function timesheetActive( $id ) 	{
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'/main/timesheet';
		$this->data['table'] = $this->modelMain->getTimesheetActiveStatus($id);
		//$this->data['flag']	= $this->modelMain->getTimesheetRequest($week, $year) ;
		//$this->data['flag_approval']	= $this->modelMain->getTimesheetApproval($week, $year) ;
		
		//$this->data['request'] = $this->data['flag'] ;
		$this->load->view('timesheet_active_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW


	/*-------------------------------------------------------------------------------------*/
	//  timesheetWeeklyView
	/*-------------------------------------------------------------------------------------*/
	function timesheetApproved( $id ) 	{
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'/main/timesheet';
		$this->data['table'] = $this->modelMain->getTimesheetActiveStatus($id);
		//$this->data['flag']	= $this->modelMain->getTimesheetRequest($week, $year) ;
		//$this->data['flag_approval']	= $this->modelMain->getTimesheetApproval($week, $year) ;
		
		//$this->data['request'] = $this->data['flag'] ;
		$this->load->view('timesheet_approved_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW


	/*-------------------------------------------------------------------------------------*/
	//  timesheetWeeklyView
	/*-------------------------------------------------------------------------------------*/
	function timesheetWaiting( $id ) 	{
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'/main/timesheet';
		$this->data['table'] = $this->modelMain->getTimesheetActiveStatus($id);
		//$this->data['flag']	= $this->modelMain->getTimesheetRequest($week, $year) ;
		//$this->data['flag_approval']	= $this->modelMain->getTimesheetApproval($week, $year) ;
		
		//$this->data['request'] = $this->data['flag'] ;
		$this->load->view('timesheet_waiting_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW

	/*-------------------------------------------------------------------------------------*/
	//  timesheetWeeklyView
	/*-------------------------------------------------------------------------------------*/
	function timesheetApprove( $id ) 	{
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'/main/timesheet';
		$this->data['table'] = $this->modelMain->getTimesheetActiveStatus($id);
		//$this->data['flag']	= $this->modelMain->getTimesheetRequest($week, $year) ;
		//$this->data['flag_approval']	= $this->modelMain->getTimesheetApproval($week, $year) ;
		
		//$this->data['request'] = $this->data['flag'] ;
		$this->load->view('timesheet_approve_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW



	/*-------------------------------------------------------------------------------------*/
	//  timesheetProjectEdit
	/*-------------------------------------------------------------------------------------*/
	function timesheetProjectEdit( $id, $project_id, $week, $year, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->modelMain->getTimesheetDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['timesheetid']			= $id;
			$this->data['form']['project_id']= $project_id ;
			$this->data['form']['job_id']		= '';
			$this->data['form']['week']		= date('W');
			$this->data['form']['year']		= date('Y');
			$this->data['form']['timesheetdate']		= '';
			$this->data['form']['hour']		= '';
			$this->data['form']['cost']		= '';
			$this->data['form']['notes']		= '';
		}
		//$this->data['form']	= $this->modelMain->getTimesheetProject($id);
		
		$this->data['project']	= $this->modelMain->getProjectDetail($project_id);
		if ( count($this->data['project'])	== 0 ) {
			//$this->data['project']['id']			= $id;
			$this->data['project']['project_id']	= 0;
			$this->data['project']['project_no']	= '';
			$this->data['project']['project']		= '';
			$this->data['project']['year_end']		= '';
			$this->data['project']['start_date']			= '';
			$this->data['project']['finish_date']			= '';
			$this->data['project']['budget_hour']	= '';
			$this->data['project']['hour']			= '';
			$this->data['project']['budget_cost']	= '';
			$this->data['project']['cost']			= '';
		}
		$this->data['back']	= $this->data['site'] .'/main/timesheet';
		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->modelMain->getTimesheetProject($project_id);
		$this->data['job'] = $this->modelMain->getProjectJob($project_id);
		
		$this->load->view('timesheet_project_edit',$this->data);
	} // END TIMESHEET PROJECT EDIT
	
	
	/*-------------------------------------------------------------------------------------*/
	//  timesheetUpdate
	/*-------------------------------------------------------------------------------------*/
	function timesheetUpdate() 	{
		$this->getMenu() ;
		$form['id']				= $this->input->post('id');
		$form['project_id']		= $this->input->post('project_id');
		$form['week']			= $this->input->post('week');
		$form['year']			= $this->input->post('year');
		$form['job_id']			= $this->input->post('job_id');
		$form['notes']			= $this->input->post('notes');
		$form['timesheetdate']	= $this->input->post('timesheetdate');
		$form['hour']			= $this->input->post('hour');
		$form['cost']			= $this->input->post('cost');
		$EmployeeWeek = $this->modelMain->checkTimesheetWeek($form['week'],$form['year']);
		$timesheet_status_id = 0;
		if ( count($EmployeeWeek) == 0){
			$timesheet_status_id = $this->modelMain->insertTimesheetWeekly($form['week'],$form['year']);
		} 
		else {
			$timesheet_status_id =  $EmployeeWeek['timesheet_status_id'] ;
		}

		///echo $timesheet_status_id;
		$this->modelMain->saveTimesheet($form,$timesheet_status_id);
		redirect('main/timesheetProjectEdit/0/'.$this->input->post('project_id').'/0/2009/');
	} // END TIMESHEET UPDATE
	
	
	/*-------------------------------------------------------------------------------------*/
	//  requestApproval
	/*-------------------------------------------------------------------------------------*/
	function requestApproval() 	{
		$this->getMenu() ;
		$id		= $this->input->post('id');
		$this->modelMain->saveTimesheetRequest($id);
		//redirect ('main/timesheetWeeklyView/'.$week.'/'.$year);
		redirect ('main/timesheet/');
	} // END REQUEST APPROVAL

	/*-------------------------------------------------------------------------------------*/
	//  requestApproval
	/*-------------------------------------------------------------------------------------*/
	function approveTimesheet() 	{
		$this->getMenu() ;
		$id		= $this->input->post('id');
		$this->modelMain->saveApproveTimesheet($id);
		//redirect ('main/timesheetWeeklyView/'.$week.'/'.$year);
		redirect ('main/timesheet/');
	} // END REQUEST APPROVAL
	

	/*-------------------------------------------------------------------------------------*/
	//  changePassword
	/*-------------------------------------------------------------------------------------*/
	function changePassword($err=null) 	{
		$this->data['form']['err']	=	$err;
		$this->data['back']	= $this->data['site'] .'/main/dashboard';
		
		$this->load->view('change_password',$this->data);
	} // END CHANGE PASSWORD
	
	
	
	/*-------------------------------------------------------------------------------------*/
	//  passwordUpdate
	/*-------------------------------------------------------------------------------------*/
	function passwordUpdate() 	{
		$password		= $this->input->post('password_new');
		$this->modelMain->savePassword($password);

		$this->session->unset_userdata('nik');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('acl');
		$this->session->unset_userdata('manager_id');
		$this->session->unset_userdata('department_id');
		$this->session->unset_userdata('is_auth');
		$this->session->sess_destroy();
		redirect('/main/index/PASSWORD-CHANGED');
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
		$totalRow			 = $this->modelMain->getUser($form);
		$this->data['pg']	 = $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->modelMain->getUser($form, $limit, $this->data['pg']['o']);
		$this->load->view('user',$this->data);
	} // END user
	
	
	
	/*-------------------------------------------------------------------------------------*/
	//  userEdit
	/*-------------------------------------------------------------------------------------*/
	function userEdit($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->modelMain->getUserDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['message']		= '';
			$this->data['form']['user_id']		= 0;
			$this->data['form']['employee_id']	= 0;
			$this->data['form']['approval']		= '';
			$this->data['form']['acl']			= '';
			$this->data['form']['email']		= '';
			$this->data['form']['user_active']		= '';
		}
		$this->data['back']		= $this->data['site'] .'/main/user';
		$this->data['reset']	= $this->data['site'] .'/main/resetPassword/'.$id;
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

		$this->modelMain->saveUser($form);
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
		//$this->load->view('clientEdit/'.$form['client_id'].'/SAVED');
	} // END userUpdate


	
	/*-------------------------------------------------------------------------------------*/
	//  employee
	/*-------------------------------------------------------------------------------------*/
	function employee($type=1, $pg=1, $limit=0) 	{
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
		$totalRow			 = $this->modelMain->getEmployee($form);
		$this->data['pg']	 = $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] = $this->modelMain->getEmployee($form, $limit, $this->data['pg']['o']);
		$this->load->view('employee',$this->data);
	} // END user
	
	
	
	/*-------------------------------------------------------------------------------------*/
	//  employeeEdit
	/*-------------------------------------------------------------------------------------*/
	function employeeEdit($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->modelMain->getEmployeeDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['message']		= '';
			$this->data['form']['employee_id']	= 0;
			$this->data['form']['employeefirstname']	= '';
			$this->data['form']['employeemiddlename']	= '';
			$this->data['form']['employeelastname']		= '';
			$this->data['form']['employeenickname']		= '';
			$this->data['form']['employeetitle']		= '';
			$this->data['form']['employeeid']			= '';
			$this->data['form']['employeeemail']		= '';
			$this->data['form']['department_id']		= '';
		}
		$this->data['back']		= $this->data['site'] .'/main/employee';
		$this->data['form']['message']=$msg;
		
		$this->load->view('employee_edit',$this->data);
	} // END employeeEdit
	
	
	/*-------------------------------------------------------------------------------------*/
	//  employeeUpdate
	/*-------------------------------------------------------------------------------------*/
	function employeeUpdate() 	{
		$this->getMenu() ;
		$form['employee_id']		= $this->input->post('employee_id');
		$form['employeefirstname']	= $this->input->post('employeefirstname');
		$form['employeemiddlename']	= $this->input->post('employeemiddlename');
		$form['employeelastname']	= $this->input->post('employeelastname');
		$form['employeenickname']	= $this->input->post('employeenickname');
		$form['employeetitle']		= $this->input->post('employeetitle');
		$form['employeeid']			= $this->input->post('employeeid');
		$form['employeeemail']		= $this->input->post('employeeemail');
		$form['department_id']		= $this->input->post('department_id');
		
		$this->modelMain->saveEmployee($form);
	} // END employeeUpdate
	
	
	/*-------------------------------------------------------------------------------------*/
	//  department
	/*-------------------------------------------------------------------------------------*/
	function department( $department_id=0, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->modelMain->getDepartmentDetail($department_id);
		
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['department_id']	= $department_id;
			$this->data['form']['departmentcode']	= '';
			$this->data['form']['department']	= '';
			$this->data['form']['company_id']		= '';
		}
		
		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->modelMain->getDepartment();
		
		$this->load->view('department',$this->data);
	} // END department
	
	
		function departmentUpdate() 	{
			$this->getMenu() ;
			$form['department_id']	= $this->input->post('department_id');
			$form['departmentcode']	= $this->input->post('departmentcode');
			$form['department']		= $this->input->post('department');
			
			$this->modelMain->saveDepartment($form);
			redirect('main/department/0/');
	} // END departmentUpdate
	


	
	
	/*-------------------------------------------------------------------------------------*/
	//  job
	/*-------------------------------------------------------------------------------------*/
	function job( $job_id=0, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->modelMain->getJobDetail($job_id);
		
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['job_id']	= $job_id;
			$this->data['form']['job_no']	= '';
			$this->data['form']['job']		= '';
		}
		
		$this->data['form']['message']=$msg;
		$this->data['table'] = $this->modelMain->getJob();
		
		$this->load->view('job',$this->data);
	} // END department
	
	
	/*-------------------------------------------------------------------------------------*/
	//  departmentUpdate
	/*-------------------------------------------------------------------------------------*/
	function jobUpdate() 	{
		$this->getMenu() ;
		$form['job_id']		= $this->input->post('job_id');
		$form['job_no']		= $this->input->post('job_no');
		$form['job']		= $this->input->post('job');
		
		$this->modelMain->saveJob($form);
		//redirect('main/job/0/');
	} // END departmentUpdate
	
	
	/*-------------------------------------------------------------------------------------*/
	//  report
	/*-------------------------------------------------------------------------------------*/
	function report() 	{
		$this->getMenu() ;
		$this->load->view('report',$this->data);
	} // END report
	
	
	/*-------------------------------------------------------------------------------------*/
	//  reportEmployee
	/*-------------------------------------------------------------------------------------*/
	function reportEmployee() 	{
		$this->getMenu() ;
		$this->data['form']['employee_id']	= $this->input->post('employee_id');;
		$this->data['form']['date_from']	= $this->input->post('date_from');
		$this->data['form']['date_to']	= $this->input->post('date_to');
		$this->data['back']		= $this->data['site'] .'/main/report';
		
		if ( strlen( $this->data['form']['employee_id'] > 0)) {
			$this->data['table'] = $this->modelMain->getReportEmployee($this->data['form']);
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_employee',$this->data);
	} // END reportEmployee
	
	
	/*-------------------------------------------------------------------------------------*/
	//  reportEmployee
	/*-------------------------------------------------------------------------------------*/
	function reportGroup() 	{
		$this->getMenu() ;
		$this->data['form']['department_id']	= $this->input->post('department_id');;
		$this->data['form']['date_from']	= $this->input->post('date_from');
		$this->data['form']['date_to']	= $this->input->post('date_to');
		$this->data['back']		= $this->data['site'] .'/main/report';
		
		if ( strlen( $this->data['form']['department_id'] > 0)) {
			$this->data['table'] = $this->modelMain->getReportGroup($this->data['form']);
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_group',$this->data);
	} // END reportEmployee
	
	
	
	/*-------------------------------------------------------------------------------------*/
	//  PRIVATE :: GETMENU
	/*-------------------------------------------------------------------------------------*/
	private function getMenu() {
		$this->data['menu'] = "";
		switch ($this->session->userdata('acl') ){
			case 0:
                $acl = array( '04000000', '99010000');
                break;
			case 1:
                $acl = array( '04000000', '99010000');
                break;
			case 2:
				$acl = array( '04000000', '99010000');
				break;
			case 3:
				$acl = array( '01000000', '02000000','03000000','04000000','05000000','05010000','06000000', '99010000');
				break;
			case 4:
				$acl = array( '01000000', '02000000','03000000','04000000','05000000','05010000','06000000', '07000000', '08000000', '99000000');
				break;
			case 5:
				$acl = array( '04000000','08000000', '99000000');
				break;
            case '05':
                $acl = array( '04000000','08000000', '99000000');
				break;     
              
		}		
		
		if ( $this->data['is_auth']) {;
			$rows	= $this->modelMain->getMenu();
			
			if($rows) {
				$this->data['menu'] = '<ul id="nav">';
				
				foreach ($rows as $k=>$v) {
					if (in_array( $v['menuid'], $acl)) {
						$this->data['li_url']	= "/main/" . $v['menu'];
						$this->data['li_label']	= $v['label'];
						
						$this->data['menu'] .= $this->load->view('li',$this->data, true );
						
						$rowsChild	= $this->modelMain->getMenuChild($v['menu']);
						if($rowsChild) {
							$this->data['menu'] .= '<ul>';
							
							foreach ($rowsChild as $k0=>$v0) {
								//$this->data['li_url']	= "main/" . $v['menu'] .'/'. $v0['menu'];
								$this->data['li_url']	= "/main/". $v0['menu'];
								$this->data['li_label']	= $v0['label'];
								
								$this->data['menu'] .= $this->load->view('li',$this->data, true )  .'</li>';
								
							}
							$this->data['menu'] .= '</ul>';
							
						}
						$this->data['menu'] .= '</li>';
					}
					
				}
				
				$this->data['menu'] .= '
						<li class="secondary" >
						<a href="'. $this->data['site'] .'/main/logout/" title="logout">
						<img src="'. $this->data['base_url'] .'/images/logout.gif" align="middle" border="0" style="vertical-align: middle; text-align: center; padding-right:3px; padding-bottom:3px;" />
						Logoutx
						</a>
						</li>
						<li class="secondary">
						<b>'.$this->session->userdata('employee') .'</b>
						
						</li>
						</ul>';
			}
		}
		else {
			$unlocked = array('login', 'logout','help');
			if ($this->uri->segment(1)=='main' && !in_array(strtolower($this->uri->segment(2)), $unlocked) ) {
				redirect();
			}
		}
	} //END GETMENU



	/*-------------------------------------------------------------------------------------*/
	//  SETPAGING
	/*-------------------------------------------------------------------------------------*/
	private function setPaging($totalRow, $cPage, $limit=0) {
		$pg['r']	= $this->rpp;
		$pg['t']	= $totalRow;
		$pg['l']	= ceil($totalRow/$limit);
		$pg['c']	= $cPage;
		$pg['p']	= $pg['c'] > 1 ? $pg['c'] - 1 : 1;
		$pg['n']	= $pg['c'] + 1 == $pg['l'] ? $pg['l'] : $pg['c'] + 1;
		$pg['n']	= $pg['n'] > $pg['l'] ? $pg['l'] : $pg['n'];
		$pg['o']	= $limit * $pg['c'] - $limit;
		return $pg;
	} // END SETPAGING




	/*-------------------------------------------------------------------------------------*/
	//  GETWEEK
	/*-------------------------------------------------------------------------------------*/
	public function getWeek($week) {
		$tmp ='<select name=week>';
		
		for ($i=1; $i<=52; $i++) {	
			$selected = '';
			if ( $i == $week ) {
				$selected = ' selected ';
			} 
			$tmp .= '<option value='.$i . $selected .'>'. $i .'</option>';
		}
		$tmp .= '</selected>';
		return $tmp;
	} // END GETWEEK
	
	
	public function htmlEmployeeList($name='', $id='', $filter=''){
		//echo "$name = $id";
		$tmp_data = $this->modelMain->getEmployeeList($filter);
		$tmp = '';
		$selected = '';
		
		if ( count( $tmp_data  ) > 0 ) {
			if (strlen($id) == 0) {
				$selected = ' selected ';
			}
			$tmp .= "<select name=".$name.">
					<option value='0'>Choose one..</option>";
					
			
			foreach ($tmp_data as $k=>$v) {
				$selected = '';
				if ( $v['employee_id'] === $id ) {
					$selected = ' selected ';
				} 
				$tmp .= '<option value='.$v['employee_id'] . $selected .'>'. $v['employeefirstname'] .' '. $v['employeemiddlename'] .' '. $v['employeelastname'] .'</option>';
			}
			$tmp .= '</select>';
			return 	$tmp;
		}
	}

	public function htmlEmployeeListView($name='', $id='', $filter=''){
		//echo "$name = $id";
		$tmp_data = $this->modelMain->getEmployeeList($filter);
		$tmp = '';
		$selected = '';
		
		if ( count( $tmp_data  ) > 0 ) {
			if (strlen($id) == 0) {
				$selected = ' selected ';
			}
			
			
			foreach ($tmp_data as $k=>$v) {
				$selected = '';
				if ( $v['employee_id'] === $id ) {
					$tmp .= $v['employeefirstname'] .' '. $v['employeemiddlename'] .' '. $v['employeelastname'] ;
				} 
				
			}
			return 	$tmp;
		}
	}
	
	function data() 	{
		redirect('/main/employee/');
	}	

	function admin() 	{
		redirect('/main/user/');
	}	


	PUblic function xDate($DateValue=null, $DateFormat="date") {
		
		$oDate	= new Date($DateValue);
		switch (lcase($DateFormat)){
			case "fulldatestring":
				return $oDate->format("%d-%b-%Y %H:%M:%S");
				break;
			
			case "fulldate":
				return $oDate->format("%d-%m-%Y %H:%M:%S");
				break;
			
			case "datetime":
				return $oDate->format("%d-%m-%Y %H:%M");
				break;
			
			case "datestring":
				return $oDate->format("%d %b %Y");
				break;
			
			case "date":
				return $oDate->format("%d-%m-%y");
				break;
			
			case "entrystring":
				return $oDate->format("%y %b %d");
				break;
			
			case "entry":
				return $oDate->format("%d/%m/%y");
				break;
			
			
			case "monthyear":
				return $oDate->format("%B %Y");
				break;
			
			case "monyear":
				return $oDate->format("%b %Y");
				break;
			
			case "time":
				if (len($DateValue) > 0){
					return $oDate->format("%H:%M");
				}
				break;
			
			case "timesec":
				return $oDate->format("%H:%M:%S");
				break;
			
			case "sql":
				if(lcase(DB_TYPE)=="mysql"){
					//return $oDate->format("%Y-%m-%d");
					return $oDate->format("%d-%m-%y");
				} elseif (lcase(DB_TYPE=="mssql")){
					return $oDate->format("%m/%y/%d");
				}
				
				break;
			
			case "periode":
				// Full Version
				// return date("Ym");
				
				// Demo Version Only
				return  "200506";
				break;
			case "periode_string":
				return $oDate->format("% %b %d");;
				break;
			
			case "periode_post":
				return $oDate->format("%d%m");
				break;
			
			case "periodestring":
				// Full Version
				// return date("Ym");
				
				// Demo Version Only
				return  "Mei 2005";
				break;
			
			case "file":
				return $oDate->format("%Y/%m/%d");
				break;
		}
		
	}
}