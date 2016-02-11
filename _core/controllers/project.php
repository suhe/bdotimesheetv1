<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends MY_Controller{
	
	function __construct()
	{
		parent::__construct();	
		$this->load->model('projectModel');
	}
	
	function index($type=1, $pg=1, $limit=0) 	{
		$this->getMenu();
		$form = array();
		$data = array('client_name','project_no','project');
		if($type==1) {
			$this->session->unset_userdata($data);
		}
		elseif($type==2) {
			$this->session->unset_userdata($data);		
			
			if($this->input->post('client_name'))		$form['client_name'] = $this->input->post('client_name');
			if($this->input->post('project_no'))		$form['project_no']  = $this->input->post('project_no');
			if($this->input->post('project')) 			$form['project']   	 = $this->input->post('project');
			
			$this->session->set_userdata($form);
		}
		
		if($this->session->userdata('client_name')) $form['client_name'] = $this->session->userdata('client_name');
		if($this->session->userdata('project_no')) 	$form['project_no']	= $this->session->userdata('project_no');
		if($this->session->userdata('project')) 	$form['project']   	= $this->session->userdata('project');
		
		if($limit) {
			$this->session->set_userdata('rpp', $limit);
			$this->rpp = $limit;
		}
		$limit				 	= $limit ? $limit : $this->rpp;
		$totalRow			 	= $this->projectModel->getProject($form);
		$this->data['pg']	 	= $this->setPaging($totalRow, $pg, $limit);
		$this->data['table']	= $this->projectModel->getProject($form, $limit, $this->data['pg']['o']);
		$this->data['review']= $this->projectModel->getProjectReview($form);
		$this->load->view('project',$this->data);
	} // END PROJECT	
	
	
	/*-------------------------------------------------------------------------------------*/
	//  projectEdit
	/*-------------------------------------------------------------------------------------*/
	function Edit($id, $msg='') 	{
		$this->getMenu() ;
		$this->data['form']	= $this->projectModel->getProjectDetail($id);
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
		//bki
		$this->data['pic']      = $this->projectModel->getProjectUser('pic');
		$this->data['gc']       = $this->projectModel->getProjectUser('gc');
		$this->data['mic']      = $this->projectModel->getProjectUser('mic');
		$this->data['aic']      = $this->projectModel->getProjectUser('aic');	
		$this->data['ot']       = $this->projectModel->getProjectUser('ot');
		
		$this->data['form_pic'] = $this->projectModel->getProjectTeamBKI($id,'01',10);
		$this->data['form_gc']  = $this->projectModel->getProjectTeamBKI($id,'02',10);
		$this->data['form_mic'] = $this->projectModel->getProjectTeamBKI($id,'03',10);
		$this->data['form_aic'] = $this->projectModel->getProjectTeamBKI($id,'041');
		$this->data['form_ass'] = $this->projectModel->getProjectTeamBKI($id,'042');
		$this->data['form_ot']  = $this->projectModel->getProjectTeamBKI($id,'777');
		
		$this->data['form']['message']=$msg;
		$this->data['back']		 = $this->data['site'] .'/project';
		$this->data['approve']	 = $this->data['site'] .'/project/request/'.$id;
		$this->data['cclient'] 	 = "";
		$this->data['client'] 	 = $this->projectModel->getClientOption();
		$this->data['jobtype'] 	 = $this->projectModel->getJobType();

		$aTeam = $this->projectModel->getProjectTeamStructure($id);
		
		$team  = "";
		$x = 0;
		for ($i = 0; $i < count( $aTeam ) ; $i++) {
			$level = '';
			$x ++;
			
			if ($aTeam[$i]['lookup_code'] ==='01')	$level = 'PIC';
			if ($aTeam[$i]['lookup_code'] ==='02')	$level = 'GC';
			if ($aTeam[$i]['lookup_code'] ==='03')	$level = 'MIC';
			if ($aTeam[$i]['lookup_code'] ==='041') $level = 'AIC';
			if ($aTeam[$i]['lookup_code'] ==='042')	$level = 'ASS';
			if ($aTeam[$i]['lookup_code'] ==='777')	$level = 'OSC';
			
			if ($aTeam[$i]['lookup_code'] ==='777')
			    $required = "* Only For BKI ";
			else
				$required = "* Required ";
			
			$team .= "	    <input type=hidden name=teamid[] value=".$aTeam[$i]['teamid'].">
							<input type=hidden name=project_title[] value='".$aTeam[$i]['lookup_code']."'>
							<tr>
							<td>".$x."
							<td>".$aTeam[$i]['lookup_label']. "(" .$aTeam[$i]['tipe'] .")
					$required.
					<td>";

			if (($aTeam[$i]['lookup_code'] === '042') ){
				$team .= "";
				$aAssistant = $this->projectModel->getAssistantList($id);

				for ($ii = 0; $ii < count( $aAssistant ) ; $ii++) {
						$team .= $aAssistant[$ii]['employeefirstname'] . " " . $aAssistant[$ii]['employeemiddlename']." " . $aAssistant[$ii]['employeelastname'] ."<br>";
				}
  		    } else  {
			    //$name='', $id='', $filter='',$true=TRUE
				//if($this->)
				$team .= $this->htmlEmployeeList('employee_id[]',$aTeam[$i]['employee_id'],$level) ;  		  
			}

		}
		$this->data['team'] 			= $team;
		$this->data['header_team']      = $aTeam;
		
		$aoTeam = $this->projectModel->getProjectTeamStructureOther($id);
		$oteam = "";
		$y = 0;
		
		for ($i = 0; $i < count( $aoTeam ) ; $i++) {
			  $checked = "";
			  if 	($aoTeam[$i]['project_title']===$aoTeam[$i]['lookup_code']){
				$checked = " checked ";
			  }

			if ($y ===0 ){
  			$oteam .= "
  				<input type=hidden name=teamotherid[] value=".$aoTeam[$i]['teamid'].">
  				<tr>
  				<td>
  				<td>Special Assignment
  				<td><input type=checkbox $checked name=project_title_other[] value='".$aoTeam[$i]['lookup_code']."'>".$aoTeam[$i]['lookup_label']. " 
  				";
			} else {
			  $oteam .= "
				<input type=hidden name=teamotherid[] value=".$aoTeam[$i]['teamid'].">
				<tr>
				<td colspan=2>
				<td><input type=checkbox $checked name=project_title_other[] value='".$aoTeam[$i]['lookup_code']."'>".$aoTeam[$i]['lookup_label']. " 
			";
			}
			$y++;
		}
		$this->data['oteam'] 	    = $oteam;
		$this->data['table_job'] 	= $this->projectModel->getProjectJob($id);
		$this->data['table'] 		= $this->projectModel->getProjectAuditor($id);
		$this->data['budgetTotal']  = $this->projectModel->getBugetTotal($id);
		$this->data['budgetOther']  = $this->projectModel->getBugetOther($id);
		$this->load->view('project_edit',$this->data);
	} // END PROJECT EDIT
	
	
	/*-------------------------------------------------------------------------------------*/
	//  projectEdit
	/*-------------------------------------------------------------------------------------*/
	function ViewPartner($id, $msg='') 	{
		if($this->session->userdata('acl')=='03') redirect('project/View/'.$id);
		if($this->session->userdata('acl')=='041') redirect('project/View/'.$id);
		if($this->session->userdata('acl')=='042') redirect('project/View/'.$id);
		
		$this->getMenu() ;
		$this->data['form']	= $this->projectModel->getProjectDetail($id);
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
		//bki
		$this->data['pic']      = $this->projectModel->getProjectUser('pic');
		$this->data['gc']       = $this->projectModel->getProjectUser('gc');
		$this->data['mic']      = $this->projectModel->getProjectUser('mic');
		$this->data['aic']      = $this->projectModel->getProjectUser('aic');	
		$this->data['ot']       = $this->projectModel->getProjectUser('ot');
		
		$this->data['form_pic'] = $this->projectModel->getProjectTeamBKI($id,'01',10);
		$this->data['form_gc']  = $this->projectModel->getProjectTeamBKI($id,'02',10);
		$this->data['form_mic'] = $this->projectModel->getProjectTeamBKI($id,'03',10);
		$this->data['form_aic'] = $this->projectModel->getProjectTeamBKI($id,'041');
		$this->data['form_ass'] = $this->projectModel->getProjectTeamBKI($id,'042');
		$this->data['form_ot']  = $this->projectModel->getProjectTeamBKI($id,'777');
		
		$this->data['form']['message']=$msg;
		$this->data['back']		 = $this->data['site'] .'/project';
		$this->data['approve']	 = $this->data['site'] .'/project/request/'.$id;
		$this->data['cclient'] 	 = "";
		$this->data['client'] 	 = $this->projectModel->getClientOption();
		$this->data['jobtype'] 	 = $this->projectModel->getJobType();

		$aTeam = $this->projectModel->getProjectTeamStructure($id);
		
		$team  = "";
		$x = 0;
		for ($i = 0; $i < count( $aTeam ) ; $i++) {
			$level = '';
			$x ++;
			
			if ($aTeam[$i]['lookup_code'] ==='01')	$level = 'PIC';
			if ($aTeam[$i]['lookup_code'] ==='02')	$level = 'GC';
			if ($aTeam[$i]['lookup_code'] ==='03')	$level = 'MIC';
			if ($aTeam[$i]['lookup_code'] ==='041') $level = 'AIC';
			if ($aTeam[$i]['lookup_code'] ==='042')	$level = 'ASS';
			if ($aTeam[$i]['lookup_code'] ==='777')	$level = 'OSC';
			
			if ($aTeam[$i]['lookup_code'] ==='777')
			    $required = "* Only For BKI ";
			else
				$required = "* Required ";
			
			$team .= "	    <input type=hidden name=teamid[] value=".$aTeam[$i]['teamid'].">
							<input type=hidden name=project_title[] value='".$aTeam[$i]['lookup_code']."'>
							<tr>
							<td>".$x."
							<td>".$aTeam[$i]['lookup_label']. "(" .$aTeam[$i]['tipe'] .")
					$required.
					<td>";

			if (($aTeam[$i]['lookup_code'] === '042') ){
				$team .= "";
				$aAssistant = $this->projectModel->getAssistantList($id);

				for ($ii = 0; $ii < count( $aAssistant ) ; $ii++) {
						$team .= $aAssistant[$ii]['employeefirstname'] . " " . $aAssistant[$ii]['employeemiddlename']." " . $aAssistant[$ii]['employeelastname'] ."<br>";
				}
  		    } else  {
			    //$name='', $id='', $filter='',$true=TRUE
				//if($this->)
				$team .= $this->htmlEmployeeList('employee_id[]',$aTeam[$i]['employee_id'],$level) ;  		  
			}

		}
		$this->data['team'] 			= $team;
		$this->data['header_team']      = $aTeam;
		
		$aoTeam = $this->projectModel->getProjectTeamStructureOther($id);
		$oteam = "";
		$y = 0;
		
		for ($i = 0; $i < count( $aoTeam ) ; $i++) {
			  $checked = "";
			  if 	($aoTeam[$i]['project_title']===$aoTeam[$i]['lookup_code']){
				$checked = " checked ";
			  }

			if ($y ===0 ){
  			$oteam .= "
  				<input type=hidden name=teamotherid[] value=".$aoTeam[$i]['teamid'].">
  				<tr>
  				<td>
  				<td>Special Assignment
  				<td><input type=checkbox $checked name=project_title_other[] value='".$aoTeam[$i]['lookup_code']."'>".$aoTeam[$i]['lookup_label']. " 
  				";
			} else {
			  $oteam .= "
				<input type=hidden name=teamotherid[] value=".$aoTeam[$i]['teamid'].">
				<tr>
				<td colspan=2>
				<td><input type=checkbox $checked name=project_title_other[] value='".$aoTeam[$i]['lookup_code']."'>".$aoTeam[$i]['lookup_label']. " 
			";
			}
			$y++;
		}
		$this->data['oteam'] 	    = $oteam;
		$this->data['table_job'] 	= $this->projectModel->getProjectJob($id);
		$this->data['table'] 		= $this->projectModel->getProjectAuditor($id);
		$this->data['budgetTotal']  = $this->projectModel->getBugetTotal($id);
		$this->data['budgetOther']  = $this->projectModel->getBugetOther($id);
		$this->load->view('project_view_partner',$this->data);
	} // END PROJECT VIEW Partner
	
	
	/*-------------------------------------------------------------------------------------*/
	//  projectView
	/*-------------------------------------------------------------------------------------*/
	function View($id, $msg='') 	{
		$this->getMenu();
		$this->data['form']	= $this->projectModel->getProjectDetail($id);
		$this->data['project_id'] = $id;
		if ( count($this->data['form'])	== 0 ) {
			$this->data['project_id'] = 0;
			$this->data['form']['message']					= '';
			$this->data['form']['client_id']			    = 0;
			$this->data['form']['jobtype_id']				= '';
			$this->data['form']['project_id']				= 0;
			$this->data['form']['project_no']				= '';
			$this->data['form']['project']					= '';
			$this->data['form']['location']					= '';
			$this->data['form']['year_end']					= '';
			$this->data['form']['start_date']				= '';
			$this->data['form']['finish_date']				= '';
			$this->data['form']['contract_no']				= '';
			$this->data['form']['client_approval']			= '';	
			$this->data['form']['client_approval_date']		= '';
			$this->data['form']['status_collection']		= '';
			$this->data['form']['project_status']			= '';	
			$this->data['form']['budget_hour']				= '';
			$this->data['form']['hour']						= '';
			$this->data['form']['budget_cost']				= '';
			$this->data['form']['cost']						= '';
			$this->data['form']['createuser']				= '';
			$this->data['form']['createdate']				= '';
			$this->data['form']['creator']				    = '';
			$this->data['form']['project_note']				= '';
			
		}
		$this->data['form']['message'] = $msg;
		$this->data['back']	 	= $this->data['site'] .'/project';
		$this->data['approve']	= $this->data['site'] .'/project/request/'. $id;
		$this->data['client'] 	= $this->projectModel->getClientOption();
		$this->data['cclient'] 	= "";
		$aTeam = $this->projectModel->getProjectTeamStructure($id);
		
		$team = "";
		$x = 0;
		for ($i = 0; $i < count( $aTeam ) ; $i++) {
			$level = '';
			$x ++;
			
			if ($aTeam[$i]['lookup_code'] ==='01')	$level = 'PIC';
			if ($aTeam[$i]['lookup_code'] ==='02')	$level = 'GC';
			if ($aTeam[$i]['lookup_code'] ==='03')	$level = 'MIC';
			if ($aTeam[$i]['lookup_code'] ==='041') $level = 'AIC';
			if ($aTeam[$i]['lookup_code'] ==='042')	$level = 'ASS';
			if ($aTeam[$i]['lookup_code'] ==='777')	$level = 'OT';
			
			/*$team .= "	    <input type=hidden name=teamid[] value=".$aTeam[$i]['teamid'].">
					    <input type=hidden name=project_title[] value='".$aTeam[$i]['lookup_code']."'>
					    <tr>
							<td>".$x."
							<td>".$aTeam[$i]['lookup_label']. " ( " .$aTeam[$i]['tipe'] ." )
						<td>";*/
	                $team .= "<tr>
							<td>".$x."
							<td>".$aTeam[$i]['lookup_label']. " ( " .$aTeam[$i]['tipe'] ." )
						<td>";
			if ($aTeam[$i]['lookup_code'] === '041'){
					$team .= "";
					$aAIC = $this->projectModel->getAICList($id);

					for ($ii = 0; $ii < count( $aAIC ) ; $ii++)
						$team .= $aAIC[$ii]['employeefirstname'] . " " . $aAIC[$ii]['employeemiddlename']." " . $aAIC[$ii]['employeelastname'] ."<br>";
			}	
			elseif ($aTeam[$i]['lookup_code'] === '042'){
					$team .= "";
					$aAssistant = $this->projectModel->getAssistantList($id);

					for ($ii = 0; $ii < count( $aAssistant ) ; $ii++)
						$team .= $aAssistant[$ii]['employeefirstname'] . " " . $aAssistant[$ii]['employeemiddlename']." " . $aAssistant[$ii]['employeelastname'] ."<br>";
			} else if ($aTeam[$i]['lookup_code'] === '777'){
					$team .= "";
					$Outsource = $this->projectModel->getOutsourceList($id);

					for ($ii = 0; $ii < count( $Outsource ) ; $ii++)
						$team .= $Outsource[$ii]['team_description']."<br>";
			} else {
					$team .= $this->htmlEmployeeListView('employee_id[]',$aTeam[$i]['employee_id'],$level) ;  		  
			}

		}
		$this->data['team'] 			= $team;

		$this->data['header_team']      = $aTeam;
		
		$aoTeam = $this->projectModel->getProjectTeamStructureOther($id);
		$oteam = "";
		$y = 0;
		
		for ($i = 0; $i < count( $aoTeam ) ; $i++) {
			$checked = "";
			if 	($aoTeam[$i]['project_title']===$aoTeam[$i]['lookup_code']){
			  $checked = " checked ";
			}

			if ($y ===0 ){
  			$oteam .= "
  				<input type=hidden name=teamotherid[] value=".$aoTeam[$i]['teamid'].">
  				<tr>
  				<td>8
  				<td>Special Assignment
  				<td><input type=checkbox $checked name=project_title_other[] value='".$aoTeam[$i]['lookup_code']."'>".$aoTeam[$i]['lookup_label']. " 
  				";
			} else {
			  $oteam .= "
				<input type=hidden name=teamotherid[] value=".$aoTeam[$i]['teamid'].">
				<tr>
				<td colspan=2>
				<td><input type=checkbox $checked name=project_title_other[] value='".$aoTeam[$i]['lookup_code']."'>".$aoTeam[$i]['lookup_label']. " 
			";
			}
			$y++;
		}
				$this->data['oteam'] 			= $oteam;

		$this->data['table_job'] 	= $this->projectModel->getProjectJob($id);
		$this->data['table'] 		= $this->projectModel->getProjectAuditor($id);
		$this->data['budgetTotal'] = $this->projectModel->getBugetTotal($id);
		$this->data['budgetOther'] = $this->projectModel->getBugetOther($id);

		$this->load->view('project_view',$this->data);
	} // END PROJECT VIEW

	function request($id, $msg='')	{
		$this->projectModel->requestProject($id);
		$this->View($id);
	}
	
	function Reviewed($id, $msg='') 	{
		$this->projectModel->reviewProject($id);
		$form['project_id']	= $this->input->post('project_id');
		$form['id']		      = $this->input->post('id');
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
		$form['777_hour']		= $this->input->post('777_hour');
		$form['777_cost']		= $this->input->post('777_cost');

		$form['id-oth']		            = $this->input->post('id-oth');
		$form['other_budget_hour']		= $this->input->post('other_budget_hour');
		$form['other_budget_cost']		= $this->input->post('other_budget_cost');
		$form['other_actual_hour']		= $this->input->post('other_actual_hour');
		$form['other_actual_cost']		= $this->input->post('other_actual_cost');


		$this->projectModel->saveProjectBudgetCost($form);
		$this->View($id);
	}	
	
	/*-------------------------------------------------------------------------------------*/
	//  projectUpdate
	/*-------------------------------------------------------------------------------------*/
	function Update() 	{
		$this->getMenu() ;
		$form['client_id']				= $this->input->post('client_id');
		$form['jobtype_id']				= $this->input->post('jobtype_id');
		$form['project_id']				= $this->input->post('project_id');
		$form['project_no']				= $this->input->post('project_no');
		$form['project']					= $this->input->post('project');
		$form['support']					= $this->input->post('support');
		$form['year_end']					= $this->input->post('year_end');
		$form['start_date']				= $this->input->post('start_date');
		$form['finish_date']				= $this->input->post('finish_date');
		$form['contract_no']				= $this->input->post('contract_no');
		$form['client_approval']		= $this->input->post('client_approval');
		$form['client_approval_date']	= $this->input->post('client_approval_date');
		$form['status_collection']		= $this->input->post('status_collection');
		$form['teamid']					= $this->input->post('teamid');
		$form['project_status']			= $this->input->post('project_status');
		$form['project_title']			= $this->input->post('project_title');
		$form['employee_id']				= $this->input->post('employee_id');
		$form['teamotherid']					= $this->input->post('teamotherid');
		$form['project_title_other']	= $this->input->post('project_title_other');
		
		
		if($this->session->userdata('department_id')==7){
			$form['pic'] 		  =  $this->input->post('pic');
			$form['gc']  		  =  $this->input->post('gc');
			$form['mic'] 		  =  $this->input->post('mic');
			$form['aic'] 		  =  $this->input->post('aic');
			$form['ot'] 		  =  $this->input->post('ot');
			$form['project_note'] = $this->input->post('note');
		}
		$project_id = $this->projectModel->saveProject($form);
		redirect('/project/Edit/'.$project_id.'/SAVED');
	} // END PROJECT UPDATE
	
	
	/*-------------------------------------------------------------------------------------*/
	//  projectUpdate Partner
	/*-------------------------------------------------------------------------------------*/
	public function UpdatePartner(){
		$project['project_id'] = $this->input->post('project_id');
		$top_level = array('01'=>$this->input->post('pic'),
				   '02'=>$this->input->post('gc'),
				   '03'=>$this->input->post('mic'));
		
		//top level management
		foreach($top_level as $key => $user){
			//check per level
			$check_level = $this->projectModel->getProjectTeam($project['project_id'],$key);
			if($check_level) $this->projectModel->getProjectTeamLevelSave($project['project_id'],$key,$user);
			else $this->db->query("insert into project_team (project_id,employee_id,approval_id,project_title) values ($project[project_id],$user,$user,'$key')");
		}
		
		//lower/staff level management
		$staff_level = array('041'=>$this->input->post('aic'),
				             '042'=>$this->input->post('ass'));
								
		foreach($staff_level as $key => $user){
		//if($user!=''){	
		//pindah budget cost
			$budget = $this->projectModel->getBudgetLevelTotal($project['project_id'],$key);
			$project['bhour']  = $budget?$budget['bhour']:0;
			$project['bcost']  = $budget?$budget['bcost']:0;
			$project['brate']  = $budget?$budget['brate']:0;
			$project['bdays']  = $budget?$budget['bdays']:0;
			$project['employee_title'] = $key;
			$total = count($user);
			if($total){
				$remove_user = $this->projectModel->getDeleteProjectTeam($project);
				for($i=0;$i<$total;$i++){
				    if(($i<$total) && ($user[$i]) ){	
					//cek aktual hour jika ada
					$actual = $this->projectModel->getActualCostEmployee($project['project_id'],$user[$i]);
					$project['employee_id'] = $user[$i];
					$project['team_description'] = $key;
					$project['approval_id'] = $this->projectModel->approvaluser($user[$i]);
					$project['actual_hour'] = $actual?$actual['xhour']:'0';
					$project['actual_cost'] = $actual?$actual['xcost']:'0';
					
					$insert_user = $this->projectModel->getSaveProjectTeamUser($project);
				    }
				}
			}
		//}	
		}
		
		if($this->session->userdata('department_id')==7){
			$outsource['employee_title'] = '777';
			$outsource['project_id']     = $project['project_id'];
			//delete outsource 
			$remove_user = $this->projectModel->getDeleteProjectTeam($outsource);
			$ot = $this->input->post('ot');
			$total = count($ot);
			if(!$total) $total=1; 
			for($i=0;$i<$total;$i++){
				$outsource['employee_id'] = 777777;
				$outsource['team_description'] = $ot[$i];
				$outsource['approval_id'] = $this->input->post('mic')?$this->input->post('mic'):0;
				$outsource['bhour']  = 0;
				$outsource['bcost']  = 0;
				$outsource['brate']  = 0;
				$outsource['bdays']  = 0;
				$outsource['actual_hour'] = 0;
				$outsource['actual_cost'] = 0;
				$insert_user = $this->projectModel->getSaveProjectTeamUser($outsource);
			}
		}
		
		//echo $project['project_id'];
		//redirect($this->input->server('HTTP_REFERER'),301);
		redirect ('/project/ViewPartner/'.$project['project_id'].'/SAVED',301);
	} // END PROJECT UPDATE Partner
	
	
	public function reviewproject($id,$status){
		$this->projectModel->reviewProjectByID($id,$status);
		redirect('/project/ViewPartner/'.$id.'/SAVED');	
	}
	
	public function delete_team(){
		$id = $this->input->post('id');
		$this->db->where('teamid',$id);
		$this->db->delete('project_team');
	}

	/*-------------------------------------------------------------------------------------*/
	// projectJobEdit UPDATE DARI IPOEL LINE 
	/*-------------------------------------------------------------------------------------*/
	function JobEdit($id, $mode, $jobtype_id='') 	{
		$this->getMenu() ;
		$this->data['id']		= $id;
		$this->data['mode']		= $mode;
		$this->data['back']	= $this->data['site'] .'/project/Edit/'.$id;
		
		if ( $mode=='add'){
			//$this->data['table'] = $this->projectModel->getJobList($id);
			//$this->data['table'] = $this->projectModel->getJobListType($id, $jobtype_id);
			$this->data['table']=$this->projectModel->getJobListType($id,$jobtype_id);
			
		}

		if ( $mode=='del'){
			$this->data['table'] = $this->projectModel->getJobListDel($id);
		}
		$this->load->view('project_job_edit',$this->data);
	} // END PROJECT JOB EDIT


	/*-------------------------------------------------------------------------------------*/
	//  projectJobUpdate
	/*-------------------------------------------------------------------------------------*/
	function JobUpdate() 	{
		$this->getMenu() ;
		$form['mode']			= $this->input->post('mode');
		$form['project_id']	= $this->input->post('project_id');
		$form['job_id']		= $this->input->post('job_id');
		if ( count( $form['job_id']) >0){
			foreach ($form['job_id'] as $k=>$v) {
				$this->projectModel->saveProjectJob($form['mode'], $form['project_id'], $v);
			}
		}
		redirect('/project/Edit/'. $form['project_id'] .'/SAVED');
	} // END PROJECT JOB UPDATE

	/*-------------------------------------------------------------------------------------*/
	//  projectUpdate
	/*-------------------------------------------------------------------------------------*/
	function BudgetCost() 	{
		$this->getMenu() ;
		$form['project_id']	    = $this->input->post('project_id');
		$form['id']		        = $this->input->post('id');
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
		$form['777_hour']		= $this->input->post('777_hour');
		$form['777_cost']		= $this->input->post('777_cost');
		$form['id-oth']		            = $this->input->post('id-oth');
		$form['other_budget_hour']		= $this->input->post('other_budget_hour');
		$form['other_budget_cost']		= $this->input->post('other_budget_cost');
		$form['other_actual_hour']		= $this->input->post('other_actual_hour');
		$form['other_actual_cost']		= $this->input->post('other_actual_cost');

		$this->projectModel->saveProjectBudgetCost($form);
		redirect ('/project/Edit/'.$form['project_id'].'/SAVED');
	} // END PROJECT UPDATE
	
	
	/*-------------------------------------------------------------------------------------*/
	//  projectUpdate
	/*-------------------------------------------------------------------------------------*/
	function UpdateBudgetCost() 	{
		$form['project_id']	        = $this->input->post('project_id');
		$form['id']		        = $this->input->post('id');
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
		$form['777_hour']		= $this->input->post('777_hour');
		$form['777_cost']		= $this->input->post('777_cost');

		$form['id-oth']= $this->input->post('id-oth');
		$form['other_budget_hour']= $this->input->post('other_budget_hour');
		$form['other_budget_cost']= $this->input->post('other_budget_cost');
		$form['other_actual_hour']= $this->input->post('other_actual_hour');
		$form['other_actual_cost']= $this->input->post('other_actual_cost');
		$this->projectModel->UpdateProjectBudgetCost($form);
		redirect ('/project/ViewPartner/'.$form['project_id'].'/SAVED');
	} // END PROJECT UPDATE
		
	
	public function htmlEmployeeList($name='', $id='', $filter='',$true=TRUE){
		$tmp_data = $this->projectModel->getEmployeeList($filter);
		$tmp = '';
		$selected = '';
		
		if ( count( $tmp_data  ) > 0 ) {
			if (strlen($id) == 0) {
				$selected = ' selected ';
			}
			$tmp .= "<select name=".$name." style='visible=$true' ><option value='0'>Choose one..</option>";
			foreach ($tmp_data as $k=>$v) {
				$name = $v['employeefirstname'] .' '. $v['employeemiddlename'] .' '. $v['employeelastname'] ;
				if ( strlen( $v['employeefirstname'] ) == 0) {
					$name = $v['name'] ;
				}

				$selected = '';
				if ( $v['employee_id'] === $id ) {
					$selected = ' selected ';
				} 
				$tmp .= '<option value='.$v['employee_id'] . $selected .'>'. $name  .'</option>';
			}
			$tmp .= '</select>';
		}
	
		return $tmp;
		
	}

	public function htmlEmployeeListView($name='', $id='', $filter='',$true=TRUE){
		$tmp_data = $this->projectModel->getEmployeeList($filter);
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
		}
		return $tmp;
	}

	/*-------------------------------------------------------------------------------------*/
	function ajaxClientNo($client_id) 	{

		$tmp = "";
		if (strlen( $client_id ) > 0 ) {
		  $data = $this->projectModel->getClientNo($client_id);
		  if ( count( $data  ) > 0 ) {
		      if (strlen( $data[0]['client_no'] ) > 0 ){
		       $tmp =  $data[0]['client_no'];
		      }
		  }
		}
		echo $tmp;
	} 

	/*-------------------------------------------------------------------------------------*/
	function ajaxJobTypeNo($jobtype_id) 	{

		$tmp = "";
		if (strlen( $jobtype_id ) > 0 ) {
		  $data = $this->projectModel->getJobtypeNo($jobtype_id);
		  if ( count( $data  ) > 0 ) {
		      if (strlen( $data[0]['jobtype_no'] ) > 0 ){
		       $tmp =  $data[0]['jobtype_no'];
		      }
		  }
		}
		echo $tmp;
	}
	
	
	public function Refresh(){
		$query = $this->projectModel->getMyProject();
		foreach($query as $row){
			$id = $row['project_id'];
			
			$cost = 0;
			$hour = 0;
			
			$qtemp = $this->projectModel->getMyProjectTeam($id);
			foreach($qtemp as $row2){
				$cost+=$row2['budget_cost'];
				$hour+=$row2['budget_hour'];
			}
			$query = $this->projectModel->getUpdateBudget($id,$cost,$hour);
		}
		
		redirect($this->input->server('HTTP_REFERER'),301);
		
	}


}	