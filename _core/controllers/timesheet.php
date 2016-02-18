<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timesheet extends MY_Controller{
	
	function __construct()
	{
		parent::__construct();	
		$this->load->model(array('timesheetModel','vacationModel'));
	}
	
	
	
		/*-------------------------------------------------------------------------------------*/
	//  timesheet
	/*-------------------------------------------------------------------------------------*/
	function index($type=1, $pg=1, $limit=25) 	{
	    //echo $this->session->userdata('acl');
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
		$limit		= $limit ? $limit : $this->rpp;
		$totalRow	= $this->timesheetModel->getProject($form);
		
		$this->data['pg']	 		= $this->setPaging($totalRow, $pg, $limit);
		$this->data['table'] 	= $this->timesheetModel->getProject($form, $limit, $this->data['pg']['o']);
		$this->data['active'] 	= $this->timesheetModel->getTimesheetActive();
		$this->data['return'] 	= $this->timesheetModel->getTimesheetReturn();
			
		$this->load->view('timesheet',$this->data);
	
	} // END TIMESHEET
	

	
	/*-------------------------------------------------------------------------------------*/
	//  
	/*-------------------------------------------------------------------------------------*/
	function Waiting( $id ) 	{
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'/timesheet/waitingApproval';
		$this->data['table'] = $this->timesheetModel->getTimesheetActiveStatus($id);
		$this->load->view('timesheet_waiting_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW


	/*-------------------------------------------------------------------------------------*/
	// 
	/*-------------------------------------------------------------------------------------*/
	function Approved( $id ) 	{
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'/timesheet/approvedTimesheet';
		$this->data['table'] = $this->timesheetModel->getTimesheetActiveStatus($id);

		//$this->data['table'] = $this->timesheetModel->getTimesheetActiveStatusX($id);
		
		$this->load->view('timesheet_approved_detail',$this->data);
	} 

		/*-------------------------------------------------------------------------------------*/
	//  timesheetProjectEdit
	/*-------------------------------------------------------------------------------------*/
	function projectEdit( $id, $project_id, $week, $year, $msg='') 	{	
		$this->getMenu() ;
		$this->data['form']	= $this->timesheetModel->getTimesheetDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['timesheetid']	 = $id;
			$this->data['form']['project_id']	 = $project_id ;
			$this->data['form']['job_id']			 = '';
			$this->data['form']['client_name_description']= '';
			$this->data['form']['week']			 = ((date('m')==12) && (date('d')>28) & (date('Y')==2014))?1:date('W');
			$this->data['form']['year']			 = ((date('m')==12) && (date('d')>28) & (date('Y')==2014))?(date('Y')+1):date('Y');;
			$this->data['form']['timesheetdate'] = date('Y/m/d',now());
			$this->data['form']['hour']			 = '0';
			$this->data['form']['overtime']			 = '0';
			$this->data['form']['cost']			 = '0';
			$this->data['form']['transport_type']			 = '0';
			$this->data['form']['transport_cost']			 = '0';
			$this->data['form']['notes']			 = '';
		}

		//print_r($this->data['form']);

		//$this->data['form']	= $this->modelMain->getTimesheetProject($id);
		$this->data['projectlist']	= $this->timesheetModel->getProjectPosting(null,10);
		
		$this->data['project']	= $this->timesheetModel->getProjectDetail($project_id);
		if ( count($this->data['project'])	== 0 ) {
			//$this->data['project']['id']			= $id;
			$this->data['project']['project_id']	= 0;
			$this->data['project']['project_no']	= '';
			$this->data['project']['client_no']		= '';
			$this->data['project']['client_name']		= '';
			//$this->data['project']['client_name_description'] = '';
			$this->data['project']['project']		= '';
			$this->data['project']['year_end']		= '';
			$this->data['project']['start_date']	= '';
			$this->data['project']['finish_date']	= '';
			$this->data['project']['budget_hour']	= '';
			$this->data['project']['hour']			= '0';
			$this->data['project']['budget_cost']	= '0';
			$this->data['project']['cost']			= '0';
			$this->data['project']['overtime']			= '0';
		}
		$this->data['back']	= $this->data['site'] .'/timesheet';
		$this->data['form']['message']=$msg;
		$date_start = $this->session->userdata('date_start');
		$date_end = $this->session->userdata('date_end');
		
		if($date_start){
			$datestart = $date_start;
			$dateend   = $date_end;
		} else {
			$datestart = config_item('date_start');
			$dateend   = config_item('date_end');
		}
		
		$this->data['date_start'] = $datestart;
		$this->data['date_end']   = $dateend;
		$this->data['table']      = $this->timesheetModel->getTimesheetProject($project_id,$datestart,$dateend);
		$this->data['job'] 	      = $this->timesheetModel->getProjectJob($project_id);
		$this->data['tranport'] 	= $this->timesheetModel->tranport();
		
		$this->load->view('timesheet_project_edit',$this->data);
	} // END TIMESHEET PROJECT EDIT
	
	
	function getSearchPosting()
	{
		$search  = array( 'date_start'   =>  $this->input->post('date_from'), 'date_end' => $this->input->post('date_to'));
		$this->session->set_userdata($search);
		redirect($this->input->server('HTTP_REFERER'),301);
		
	}
	
	
	/*-------------------------------------------------------------------------------------*/
	//  timesheetUpdate
	/*-------------------------------------------------------------------------------------*/
	function Update() 	{
		$this->getMenu() ;
		$form['id']					= $this->input->post('id');
		$form['project_id']		= $this->input->post('project_id');
		$form['week']				= $this->input->post('week');
		$form['year']				= $this->input->post('year');
		$form['job_id']			= $this->input->post('job_id');
		$form['client_name_description'] = $this->input->post('job_id') ?  $this->input->post('client_name_description') : "";
		if (strlen( $form['job_id'] )=== 0) $form['job_id'] = "0";
		$form['notes']				= $this->input->post('notes');
		$form['timesheetdate']	= $this->input->post('timesheetdate');
		$form['hour']				= $this->input->post('hour');
		$form['overtime']				= $this->input->post('overtime');
		$form['transport_type']				= $this->input->post('transport_type');
		$form['transport_cost']				= $this->input->post('transport_cost');
		$form['cost']				= strlen($this->input->post('cost'))== 0 ? 0:$this->input->post('cost');
		
		$EmployeeWeek = $this->timesheetModel->checkTimesheetWeek($form['week'],$form['year']);
		$timesheet_status_id = 0;
		if ( count($EmployeeWeek) == 0){
			$timesheet_status_id = $this->timesheetModel->insertTimesheetWeekly($form['week'],$form['year']);
		} 
		else {
			$timesheet_status_id = $EmployeeWeek['timesheet_status_id'] ;
		}

		///echo $timesheet_status_id;
		$this->timesheetModel->saveTimesheet($form,$timesheet_status_id);
		redirect('timesheet/ProjectEdit/0/'.$this->input->post('project_id').'/0/2009/');

	} // END TIMESHEET UPDATE

	function ProjectDel($id, $project_id)	{
		if ($this->timesheetModel->deleteTimeSheet($id)) {
			//redirect('timesheet/ProjectEdit/0/'.$project_id.'/0/2009/');
			echo "alert('loaded');";
		}
	}	

	function returnDel($id, $timesheet_status_id)	{
		if ($this->timesheetModel->deleteTimeSheet($id)) {
			redirect('timesheet/approved/'.$timesheet_status_id);
		}
	}	

	function TimesheetDelete($id, $ProjectID)	{
		$this->timesheetModel->deleteTimeSheet($id);
		
	 $table  = $this->timesheetModel->getTimesheetProject($ProjectID);
	 //print_r($response);
	 
	 
	 
			$hour = 0;
			$overtime = 0;
			$cost = 0;
			$transport = 0;
			if ( count( $table) > 0 ) {
				$i = 1;
				foreach ($table as $k=>$v) {
					$class = '';
					$hour += $v['hour'];
					$overtime += $v['overtime'];
					$cost += $v['cost'];
					$transport += $v['transport_cost'];
					
					if ( $i % 2 == 0) $class= 'class="odd"';
				
					$status = '';
					if ($v['timesheet_approval'] == '1' ){
						$status = 'Waiting Approval';
					}
					if ($v['timesheet_approval'] == '2' ){
						$status = 'Approved';
						$link ="";
					}
			
					if ($v['timesheet_approval'] == '3' ){
						$status = 'Returned';
						$link ="";
					}
					
					$timesheetdate ='';
					if ( strlen( $v['timesheetdate']) >0 ) {
						$timesheetdate = date("d/m/Y",strtotime($v['timesheetdate'])) ;
					}
					echo "<tr $class>
								<td>$i</td>
								<td nowrap>$timesheetdate</td>
								<td>$v[week] - $v[year]</td>
								<td>$v[project_no]</td>
								<td>$v[job]</td>
								<td class='currency'>".number_format($v['hour'],2)."</td>
								<td class='currency'>".number_format($v['overtime'],2)."</td>
								<td class='currency'>".number_format($v['cost'])."</td>
								<td class='currency' nowrap>".$v['transport_type']."</td>
								<td class='currency'>".number_format($v['transport_cost'])."</td>
			
								<td>$v[notes]</td>
								<td>$status</td>";
					if ($status=='') {
						$link = "<a href='".$this->data['site'] ."/timesheet/projectEdit/$v[id]/".$ProjectID."/0/0'>[ Edit ]</a>";
						$del = "<a id='".$v['id']."'  class='hapus' style='cursor:pointer;'>[ Del ]</a>";
						echo "<td class='currency' nowrap>$link - $del</td>";
					}
					else
					{
						echo "<td class='currency' nowrap></td>";
					}
					echo "</tr>";
					$i++;
				}
			}		
			
			echo "|
			<tr>
				<td colspan='4'></td>
				<th>Total</th>
				<th class='currency'>". number_format($hour,2) ."</th>
				<th class='currency'>". number_format($overtime,2)."</th>
				<th class='currency'>". number_format($cost)."</th>
				<th class='currency'>&nbsp;</th>
				<th class='currency'>".  number_format($transport)."</th>
				<td colspan='4'></td>
			</tr>";
		
		//if ($this->timesheetModel->deleteTimeSheet($id)) {
			//redirect('timesheet/ProjectEdit/0/'.$project_id.'/0/2009/');
			//echo "alert('loaded');";
		//}
	}	

	/*-------------------------------------------------------------------------------------*/
	//  timesheetWeeklyView
	/*-------------------------------------------------------------------------------------*/
	function Active( $id ) 	{
		$tableData = $this->timesheetModel->getTimesheetActiveStatus($id);
		if(!$tableData){
			redirect('timesheet',301);
		}
		
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'/timesheet';
		$this->data['table']  = $tableData;
		$this->load->view('timesheet_active_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	
	
	/*-------------------------------------------------------------------------------------*/
	//  requestApproval
	/*-------------------------------------------------------------------------------------*/
	function requestApproval() 	{
		$this->getMenu() ;
		$id = $this->input->post('id');
		$this->timesheetModel->saveTimesheetRequest($id);
		redirect ('/timesheet/');
	} // END REQUEST APPROVAL
	
	/*-------------------------------------------------------------------------------------*/
	//  timesheetWeeklyView
	/*-------------------------------------------------------------------------------------*/
	function Approve( $id,$week,$year ) 	{
		$this->getMenu() ;
		$this->data['id']	= $id ;
		$this->data['back']	= $this->data['site'] .'timesheet/tobeApproved';
		$this->data['table'] = $this->timesheetModel->getTimesheetActiveStatusX($id,$week,$year);
		$this->load->view('timesheet_approve_detail',$this->data);
	} // END TIMESHEET WEEKLY VIEW
	

	/*-------------------------------------------------------------------------------------*/
	//  requestApproval
	/*-------------------------------------------------------------------------------------*/
	function approveTimesheet() 	{
		$this->getMenu() ;
		$id = $this->input->post('id');
		$this->timesheetModel->saveApproveTimesheet($id);
		redirect ('timesheet');
	} // END REQUEST APPROVAL
	
	function getJob(){
		$project_id = $this->input->post('project_id');
		echo $this->timesheetModel->getJob($project_id);
	}

  function timesheetProjectAdd($client_id=null){
    $this->getMenu() ;
		$this->data['back']	= $this->data['site'] .'/timesheet';
		
		if (strlen($this->input->post('client_id'))  == 0) {
      $this->data['form']['client_id']	 = $client_id ;		 
		} else {
      $this->data['form']['client_id']	= $this->input->post('client_id');
		}
		
    $this->data['client'] = $this->timesheetModel->getClientAll()  ;
		
		if ( strlen( $this->data['form']['client_id'] > 0)) {
			$this->data['project'] = $this->timesheetModel->getProjectAll($this->data['form']['client_id'])  ;
		} 
		else {
			$this->data['project'] = array();
		}

		$this->load->view('timesheet_project_add',$this->data);
  }

  function timesheetProjectAddUpdate(){
		$project_id = $this->input->post('project_id');
		$client = $this->input->post('clientid');
    if (count($project_id ) > 0 ){

			foreach ($project_id as $k=>$v) {
	    $approval_id = "0";
		  $query = "select * from project_team where project_id = $v and project_title = '03'";
		  $data	= $this->db->query($query);
      if($data->num_rows() > 0) {
			  $rows = $data->result_array();
  			$data->free_result();
				$approval_id = $rows[0]['employee_id'];
			}
		    
            /**  
			$sql = "insert into project_team ( project_id, employee_id, approval_id, project_title) 
					values ('$v', '".$this->session->userdata('employee_id') ."','".$approval_id."','042')"; 
			**/
            $sql = "insert into project_team ( project_id, employee_id, approval_id, project_title) 
					values ('$v', '".$this->session->userdata('employee_id') ."','".$approval_id."','".$this->session->userdata('acl')."')"; 
            $this->db->query($sql);		
			//echo "$sql .<br>";
			
     }
   }
   
   redirect ('timesheet/timesheetProjectAdd/'.$client);

  }

  function timesheetProjectDel(){
    $this->getMenu() ;
		$this->data['back']	= $this->data['site'] .'/timesheet';
		//$this->data['table'] = $this->timesheetModel->getTimesheetActiveStatusX($id);
		$this->load->view('timesheet_project_del',$this->data);
  }


	/*-------------------------------------------------------------------------------------*/
	//  timesheet
	/*-------------------------------------------------------------------------------------*/
	function tobeApproved($type=1, $pg=1, $limit=25) 	{
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
		$limit		= $limit ? $limit : $this->rpp;
		$this->data['waiting'] 	= $this->timesheetModel->getTimesheetWaiting();
	
		$this->load->view('timesheet_tobeApproved',$this->data);
	
	} // END TIMESHEET
	
	/*-------------------------------------------------------------------------------------*/
	//  timesheet
	/*-------------------------------------------------------------------------------------*/
	function waitingApproval($type=1, $pg=1, $limit=25) 	{
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
		$limit		= $limit ? $limit : $this->rpp;
		$this->data['request'] 	= $this->timesheetModel->getTimesheetRequest();
	
		$this->load->view('timesheet_waitingApproval',$this->data);
	
	} // END TIMESHEET
	
	/*-------------------------------------------------------------------------------------*/
	//  timesheet
	/*-------------------------------------------------------------------------------------*/
	function approvedTimesheet($type=1, $pg=1, $limit=25) 	{
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
		$limit		= $limit ? $limit : $this->rpp;
		$this->data['done'] 		= $this->timesheetModel->getTimesheetDone();
	
		$this->load->view('timesheet_approvedTimesheet',$this->data);  
	} // END TIMESHEET
	
	
	/*-------------------------------------------------------------------------------------*/
	//  Allowences
	/*-------------------------------------------------------------------------------------*/
	public function allowance($pg=1, $limit=0) {
		$this->getMenu();
		$form = array();
		$data = array('client_name','project_no','project');
		if($this->input->post('client_name')) $form['client_name'] = $this->input->post('client_name');
		if($this->input->post('project_no'))$form['project_no']  = $this->input->post('project_no');
		if($this->input->post('project')) $form['project']   	 = $this->input->post('project');
			
		$this->session->set_userdata($form);
		if($this->session->userdata('client_name')) $form['client_name'] = $this->session->userdata('client_name');
		if($this->session->userdata('project_no')) 	$form['project_no']	= $this->session->userdata('project_no');
		if($this->session->userdata('project')) 	$form['project']   	= $this->session->userdata('project');
		
		if($limit) {
			$this->session->set_userdata('rpp', $limit);
			$this->rpp = $limit;
		}
		$limit				 	= $limit ? $limit : $this->rpp;
		$totalRow			 	= count($this->timesheetModel->getAllowance($form));
		$this->data['pg']	 	= $this->setPaging($totalRow, $pg, $limit);
		$this->data['table']	= $this->timesheetModel->getAllowance($form, $limit, $this->data['pg']['o']);
		$this->load->view('timesheet_allowance',$this->data);
	}
	
	/*-------------------------------------------------------------------------------------*/
	//  Allowance Form
	/*-------------------------------------------------------------------------------------*/
	public function allowance_form($id = 0, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->timesheetModel->getAllowanceDetail($id);
		if ( count($this->data['form'])	== 0 ) {
			$this->data['form']['message']					= '';
			$this->data['form']['client_id']					= 0;
			$this->data['form']['project_id']				= 0;
			$this->data['form']['jobtype_id']					= 0;			
			$this->data['form']['project_no']				= '';
			$this->data['form']['project']					= '';
			$this->data['form']['location']					= '';
			$this->data['form']['year_end']					= '';
			$this->data['form']['start_date']				= '';
			$this->data['form']['finish_date']				= '';
			$this->data['form']['contract_no']				= '';
			$this->data['form']['client_approval']			= '';	
			$this->data['form']['client_approval_date']	= '';
			$this->data['form']['status_collection']		= '';
			$this->data['form']['project_status']			= '';	
			$this->data['form']['budget_hour']				= '';
			$this->data['form']['hour']						= '';
			$this->data['form']['budget_cost']				= '';
			$this->data['form']['cost']						= '';
			$this->data['form']['project_approval'] 		= '';
			$this->data['form']['location'] 			    = '0';
			$this->data['form']['createuser']			    = '';
			$this->data['form']['createdate']				= '';
			$this->data['form']['creator']					= '';
			$this->data['form']['project_note']				= '';
		}
		
		
		$this->data['form']['message']=$msg;
		$this->data['back']		 = $this->data['site'] .'/project';
		$this->data['approve']	 = $this->data['site'] .'/project/request/'.$id;
		$this->data['cclient'] 	 = "";
		//$this->data['client'] 	 = $this->projectModel->getClientOption();
		//$this->data['jobtype'] 	 = $this->projectModel->getJobType();
		//$this->data['table'] 		= $this->projectModel->getProjectAuditor($id);
		//$this->data['budgetTotal']  = $this->projectModel->getBugetTotal($id);
		//$this->data['budgetOther']  = $this->projectModel->getBugetOther($id);
		$this->load->view('timesheet_allowance_form',$this->data);
	} // END PROJECT EDIT
    
    
}	