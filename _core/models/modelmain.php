<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class modelMain extends Model {

	public function __construct() {
		parent::Model();
	}

	private function rst2Array($sql, $all='') {
		$result = array();
		$data	= $this->db->query($sql);
		if($data->num_rows() > 0) {
			$rows = $data->result_array();
			$data->free_result();
			if($rows)
			switch ($all) {
				case 10:
					// single row
					$result = $rows[0];
					break;
				case 11:
					// single first cell
					$keys	= array_keys($rows[0]);
					$result	= $rows[0][$keys[0]];
					break;
				default:
					$result = $rows;
					break;
			}
		}
		return $result;
	}


	//  getLogin
	/*-------------------------------------------------------------------------------------*/
	public  function getLogin($nik, $type=1) {
		$sql = "select a.*,b.employeeid,b.employeefirstname, b.employeemiddlename, b.employeelastname,
			0 department_id
			from sys_user a inner join employee b on a.employee_id=b.employee_id where b.employeeid ='$nik'";
		//echo $sql ;
		return $this->rst2Array($sql, 10);
	}
	
	
	
	//  getMenu
	/*-------------------------------------------------------------------------------------*/
	public  function getMenu() {
		$sql = "select * from sys_menu where parentid = '0' and lactive=1 order by menuid";
		return $this->rst2Array($sql);
	}


	//  getMenuChild
	/*-------------------------------------------------------------------------------------*/
	public  function getMenuChild($parentID) {
		$sql = "select * from sys_menu where parentid = '". $parentID ."' and menu <> '". $parentID ."' and lactive=1 ";
		return $this->rst2Array($sql);
	}
		
	
	//  getClient
	/*-------------------------------------------------------------------------------------*/
	public  function getClient($filter, $limit=0, $offset=0) {
		$whereClause 	= "";
		if ( $this->session->userdata('acl') == 2) $whereClause .='	and client_id in ( select client_id from client_department where manager_id = '.$this->session->userdata('manager_id').' )';
		if ( $this->session->userdata('acl') == 3) $whereClause .='	and client_id in ( select client_id from client_department where department_id = '.$this->session->userdata('department_id').' )';

		$selectClause	= $limit ? "*" : "count(*) total";
		$limitClause	= $limit ? "order by client_name limit $limit offset $offset" : "";
		if(isset($filter['client_no'])) $whereClause .= " and client_no like '%$filter[client_no]%'";
		if(isset($filter['client_name'])) $whereClause .= " and client_name like '%$filter[client_name]%'";
		if(isset($filter['address'])) $whereClause .= " and address like '%$filter[address]%'";
		
		$sql = "select $selectClause from client where 1=1 $whereClause $limitClause";
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
		
	
	//  getClientOption
	/*-------------------------------------------------------------------------------------*/
	public  function getClientOption() {
		$whereClause 	= "";
		//if ( $this->session->userdata('acl') == 2) $whereClause .='	and client_id in ( select client_id from client_department where manager_id = '.$this->session->userdata('manager_id').' )';
		//if ( $this->session->userdata('acl') == 3) $whereClause .='	and client_id in ( select client_id from client_department where department_id = '.$this->session->userdata('department_id').' )';
		
		$sql = "select client_id, client_name from client where 1=1 $whereClause ";
		return $this->rst2Array($sql) ;
	}
		
	
	//  getClientDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getClientDetail($client_id) {
		$sql = "select * from client where client_id ='$client_id'";
		return $this->rst2Array($sql,10);
	}
		
	
	//  saveClient
	/*-------------------------------------------------------------------------------------*/	
	public  function saveClient($form) {
		if ( $form['client_id'] === '0' ) {
			$sql = "insert into client ( client_no, client_name, address, phone, fax, contact,
						contact_email, website, lob, sysdate, sysuser) 
					values ('$form[client_no]', '$form[client_name]' , '$form[address]',
							 '$form[phone]', '$form[fax]', '$form[contact]',
							'$form[contact_email]', '$form[website]', '$form[lob]', '".date('Y-m-d H:i:s')."',
							".$this->session->userdata('employee_id').")"; 
		}
		else {
			$sql = "update client set client_no ='$form[client_no]',
					client_name ='$form[client_name]' , 
					address='$form[address]',
					phone='$form[phone]',  
					fax='$form[fax]', 
					contact ='$form[contact]', 
					contact_email = '$form[contact_email]',
					website ='$form[website]',
					lob ='$form[lob]',
					sysdate = '".date('Y-m-d H:i:s')."',
					sysuser = ".$this->session->userdata('employee_id')."
					where client_id = $form[client_id]";
		}
		$this->db->query($sql);		

		if ( $form['client_id'] === '0' ) {
			$id = $this->db->insert_id();
/*
			$sql = "insert into client_department ( client_id, manager_id, department_id )
					values ('". $id ."', '".$this->session->userdata('manager_id')."' , 
					'".$this->session->userdata('department_id')."')"; 
			$this->db->query($sql);		
*/
		} 
		else {
			$id = $form['client_id'];	
		}
		redirect('/main/clientEdit/'.$id .'/SAVED');
	}	


	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getProject($filter, $limit=0, $offset=0) {
		//$whereClause 	= "";
		$whereClause ='	and a.project_id in ( select project_id from project_team where employee_id = '.$this->session->userdata('employee_id').' )';
		
		$selectClause	= $limit ? "a.*, b.*  " : "count(*) total";
		$limitClause	= $limit ? "order by a.project, b.client_name  limit $limit offset $offset" : "";
		if(isset($filter['project'])) $whereClause .= " and a.project like '%$filter[project]%'";
		if(isset($filter['client_name'])) $whereClause .= " and b.client_name like '%$filter[client_name]%'";
		if(isset($filter['project_no'])) $whereClause .= " and a.project_no like '%$filter[project_no]%'";
		
		$sql = "select $selectClause 
				from project a 
				inner join client b on a.client_id =b.client_id 
				where 1=1 $whereClause $limitClause";
//		echo $sql ;
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
	

	//  getProjectDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectDetail($project_id) {
		$sql = "select a.*,b.client_no, b.client_name  from project a left join client b on a.client_id=b.client_id where project_id ='$project_id'";
		
		return $this->rst2Array($sql, 10);
	}
	

	//  getProjectDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectStructure($project_id, $project_title, $employee_id ) {
		$sql = "select * from project_team 
				where project_id = $project_id 
						and project_title = '$project_title'  
						and employee_id = $employee_id ";  
		return $this->rst2Array($sql, 10);
	}
	

	//  getProjectDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectTeamStructure($project_id ) {
		$sql = "select
				a.lookup_code, a.lookup_label,a.tipe,  ifnull(b.employee_id,0) employee_id,ifnull(b.teamid,0) teamid, ifnull(b.budget_hour,0) budget_hour, ifnull(b.budget_cost,0) budget_cost
			from
				(select a.lookup_code, a.lookup_label,a.tipe  from lookup a where lookup_group = 'project_title') a
			left join
				(select * from project_team a where a.project_id = $project_id) b
			on a.lookup_code = b.project_title";
		return $this->rst2Array($sql);
	}


	//  getProjectJob
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectJob($project_id) {
		//$sql = "select a.timesheetid id,b.job_id, b.job_no, b.job, a.cost, a.hour from timesheet a inner join job b on a.job_id= b.job_id where a.project_id ='$project_id' order by b.job_no";
		$sql = "select a.*, b.job_no, b.job  from project_job a inner join job b on a.job_id= b.job_id where a.project_id ='$project_id' order by b.job_no";
		return $this->rst2Array($sql);
	}


	//  getProjectAuditor
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectAuditor($project_id) {
		//$sql = "select a.id, a.employee_id, b.employeetitle, b.employeenickname, b.employeefirstname, b.employeemiddlename, b.employeelastname, a.budget_hour, a.hour from project_auditor a inner join employee b on a.employee_id = b.employee_id where a.project_id ='$project_id' order by a.title";
		$sql = "select a.employee_id, b.tipe, b.lookup_code from project_team a inner join lookup b on a.project_title = b.lookup_code  and b.lookup_group='project_title' where a.project_id ='$project_id' order by b.lookup_code";
		
		return $this->rst2Array($sql);
	}


	//  saveProject
	/*-------------------------------------------------------------------------------------*/
	public  function saveProject($form) {
		$project_id = '0';

		if ( strlen( $form['year_end']) >0) {
			$year_end = "{d '".date("Y-m-d",strtotime($form['year_end'])) ."'}";
		} else {
			$year_end ='null';
		}
		
		
		if ( strlen( $form['start_date']) >0) {
			$start_date  = "{d '".date("Y-m-d",strtotime($form['start_date'])) ."'}";
		} else {
			$start_date  ='null';
		}
		
		if ( strlen( $form['finish_date']) >0) {
			$finish_date = "{d '".date("Y-m-d",strtotime($form['finish_date'])) ."'}";
		} else {
			$finish_date ='null';
		}
		
		if ( strlen( $form['client_approval_date']) >0) {
			$client_approval_date  = "{d '".date("Y-m-d",strtotime($form['client_approval_date'])) ."'}";
		} else {
			$client_approval_date  ='null';
		}
	
		if ( $form['project_id'] === '0' ) {
			$sql = "select * from project 
					where project_no ='$form[project_no]' or project='$form[project]'";
			$is_in_project = $this->rst2Array($sql);

			if ( count( $is_in_project ) ==0 ) {
				$sql = "insert into project ( 
						client_id, project_no,project, project_status,
						location, year_end, start_date, finish_date,
						contract_no, client_approval, client_approval_date, 
						status_collection, budget_hour, hour, budget_cost, cost, 
						sysdate, sysuser) 
						values (
						'$form[client_id]', '$form[project_no]', '$form[project]', '$form[project_status]',
						'$form[location]', 
						$year_end, 
						$start_date, 
						$finish_date,
						'$form[contract_no]', '$form[client_approval]',
						$client_approval_date,
						'$form[status_collection]', 0,0,0,0,
						'".date('Y-m-d H:i:s')."','".$this->session->userdata('employee_id')."')"; 
				$this->db->query($sql);			
				$project_id = $this->db->insert_id();
			}
			else {
				redirect('/main/projectEdit/'.$project_id .'/project_number_or_project_name_already_exist');
				//projectEdit/0/SAVED //echo "Project Number or Project Name already exist in system...., <br>please klik back.. ";
			}
		}
		else {
			
			$sql = "update project set project_no ='$form[project_no]',
					project		= '$form[project]' , 
					project_status	= '$form[project_status]',
					location	= '$form[location]',
					year_end		= $year_end,  
					start_date		= $start_date, 
					finish_date			= $finish_date, 
					contract_no = '$form[contract_no]',
					client_id = '$form[client_id]', 
					client_approval	= '$form[client_approval]', 
					client_approval_date= $client_approval_date, 
					status_collection	= '$form[status_collection]',
					sysdate = '".date('Y-m-d H:i:s')."', sysuser='".$this->session->userdata('employee_id')."'
					where project_id = $form[project_id]";
			$this->db->query($sql);		
			$project_id = $form['project_id'];	
		}
		
		if ( $project_id !='0' && count( $form['teamid']) >0){
			foreach ($form['teamid'] as $k=>$v) {
				$approval_id = '0';
				if ($k > 0){
					if ($v=='041' || $v=='042' || $v=='043' || $v=='044' || $v=='045'){
						$approval_id = $form['employee_id'][2];
					}
					else {	
						$approval_id = $form['employee_id'][$k-1];
					}
				}
				$employee_id = $form['employee_id'][$k];
				if (strlen($employee_id) > 0 ) {	

					if ( $form['teamid'][$k] == 0 ) {
						$mode = 0;
					} 
					else {
						$mode = 1;
						if ($employee_id == 0) {
							$mode = 2;	
						}
					}
					
					//$this->modelMain->saveProjectStructure($mode, $form['teamid'][$k],$project_id, $employee_id, $approval_id,$form['project_title'][$k], $form['budget_hour'][$k], $form['budget_cost'][$k]);
					$this->modelMain->saveProjectStructure($mode, $form['teamid'][$k],$project_id, $employee_id, $approval_id,$form['project_title'][$k]);
					
				}
			}
			
			$sql ="update project,
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
				where project.project_id = b.project_id and b.project_id=$project_id";
			//$this->db->query($sql);		
		}
		//echo $sql;
		redirect('/main/projectEdit/'.$project_id .'/SAVED');
	}	
	
	//  saveProjectJob
	/*-------------------------------------------------------------------------------------*/
	public  function saveProjectStructure($mode, $teamid, $project_id, $employee_id, $approval_id,$project_title) {
		if ($mode =='0'){
			$sql = "insert into project_team ( project_id, employee_id, approval_id, project_title) 
					values ('$project_id', '$employee_id','$approval_id','$project_title')"; 
					$this->db->query($sql);		
		}
		
		if ($mode =='1'){
			$sql = "update project_team set employee_id ='$employee_id',
					approval_id = '$approval_id',
					project_title = '$project_title',
					sysdate= '".date('Y-m-d H:i:s')."',
					sysuser		= '".$this->session->userdata('employee_id')."'
					where teamid= '$teamid'";
			$this->db->query($sql);		
		}
		if ($mode =='2'){
			$sql = "delete from project_team where teamid= '$teamid'";
			$this->db->query($sql);		
		}
	}	
	

		public function approveProject($id){
			$sql = "update project set project_approval  =1
					where project_id		= $id";
		$this->db->query($sql);		
		}


	//  saveProjectBudgetCost
	/*-------------------------------------------------------------------------------------*/
	public function saveProjectBudgetCost($form){
		
		if ( count( $form['id']) >0){
			foreach ($form['id'] as $k=>$v) {
				if (strlen($form['02_hour'][$k]) == 0) $form['02_hour'][$k] =0;
				if (strlen($form['02_cost'][$k]) == 0) $form['02_cost'][$k] =0;
				
				if (strlen($form['03_hour'][$k]) == 0) $form['03_hour'][$k] =0;
				if (strlen($form['03_cost'][$k]) == 0) $form['03_cost'][$k] =0;

				if (strlen($form['041_hour'][$k]) == 0) $form['041_hour'][$k] =0;
				if (strlen($form['041_cost'][$k]) == 0) $form['041_cost'][$k] =0;

				if (strlen($form['042_hour'][$k]) == 0) $form['042_hour'][$k] =0;
				if (strlen($form['042_cost'][$k]) == 0) $form['042_cost'][$k] =0;

				if (strlen($form['043_hour'][$k]) == 0) $form['043_hour'][$k] =0;
				if (strlen($form['043_cost'][$k]) == 0) $form['043_cost'][$k] =0;

				if (strlen($form['044_hour'][$k]) == 0) $form['044_hour'][$k] =0;
				if (strlen($form['044_cost'][$k]) == 0) $form['044_cost'][$k] =0;

				if (strlen($form['01_hour'][$k]) == 0) $form['01_hour'][$k] =0;
				if (strlen($form['01_cost'][$k]) == 0) $form['01_cost'][$k] =0;
				
				//$form['02_cost'][$k] ? == (strlen($form['02_cost'][$k] == 0)?0,?$form['02_cost'][$k]; 
				//strlen(1_hour == 0),0, ;
				//if (
				$sql = "update project_job set 
							01_hour = '".$form['01_hour'][$k]."',
							01_cost = '".$form['01_cost'][$k]."',
						02_hour = ".$form['02_hour'][$k].",
						02_cost = ".$form['02_cost'][$k].",
						03_hour = '".$form['03_hour'][$k]."',
						03_cost = '".$form['03_cost'][$k]."',
						041_hour = '".$form['041_hour'][$k]."',
						041_cost = '".$form['041_cost'][$k]."',
						042_hour = '".$form['042_hour'][$k]."',
						042_cost = '".$form['042_cost'][$k]."',
						043_hour = '".$form['043_hour'][$k]."',
						043_cost = '".$form['043_cost'][$k]."',
						044_cost = '".$form['044_cost'][$k]."',
						044_hour = '".$form['044_hour'][$k]."'
						where id = ".$form['id'][$k];
						
				//echo "$sql <br>";
				$this->db->query($sql);		
				//$this->modelMain->saveProjectBudgetCost($form, $mode, $form['teamid'][$k],$project_id, $employee_id, $approval_id,$form['project_title'][$k]);
			}
		}		

	}
	//  getJobList
	/*-------------------------------------------------------------------------------------*/
	public function getJobList($project_id){
		//$sql = "select * from job where department_id is null or department_id  like '%". $this->session->userdata('department_id') ."%'  and job_id not in ( select job_id from project_job where project_id ='". $project_id ."' ) order by job_no";
		$sql = "select * from job where job_id not in ( select job_id from project_job where project_id ='". $project_id ."' ) order by job_no";
		return $this->rst2Array($sql);
	}


	//  getJobListDel
	/*-------------------------------------------------------------------------------------*/
	public function getJobListDel($project_id){
		$sql = "select a.id job_id,b.job_no, b.job from project_job a left join job b on a.job_id = b.job_id where a.project_id ='". $project_id ."' order by a.job_id";
		return $this->rst2Array($sql);
	}
	
	
	public function getLookup($name){
		$sql = "select a.id job_id,b.job_no, b.job from project_job a left join job b on a.job_id = b.job_id where a.project_id ='". $project_id ."' order by a.job_id";
		return $this->rst2Array($sql);
		
	}

	//  getPICList
	/*-------------------------------------------------------------------------------------*/
	public function getPICList($project_id){
		$sql = "select a.employee_id, a.employeefirstname, a.employeemiddlename, a.employeelastname, a.employeetitle, b.department  from employee a left join department b on a.department_id = b.departmentid where a.department_id  ='". $this->session->userdata('department_id') ."'  or departmentid in (8,10) and a.employee_id not in ( select employee_id from project_auditor where project_id ='". $project_id ."' ) order by b.departmentcode, a.employeefirstname, a.employeemiddlename, a.employeelastname";
		return $this->rst2Array($sql);
	}
	

	//  getPICListDel
	/*-------------------------------------------------------------------------------------*/
	public function getPICListDel($project_id){
		$sql = "select a.id employee_id, b.employeetitle, b.employeenickname, b.employeefirstname, b.employeemiddlename, b.employeelastname, b.employeetitle,  a.budget_hour, a.hour, c.department from project_auditor a inner join employee b on a.employee_id = b.employee_id left join department c on b.department_id =c.departmentid where a.project_id ='$project_id' order by b.employeefirstname, b.employeemiddlename, b.employeelastname";
		return $this->rst2Array($sql);
	}
		

	//  saveProjectJob
	/*-------------------------------------------------------------------------------------*/
	public  function saveProjectJob($mode, $project_id, $job_id) {
		if ( $project_id !== '0' ) {
			if ($mode =='add'){
				$sql = "insert into project_job ( project_id, job_id ) 
						values ('$project_id', '$job_id')"; 
			}
			
			if ($mode =='del'){
				$sql = "delete from project_job where id ='$job_id'"; 
			}
			
			$this->db->query($sql);		
		}
	}	
	

	//  saveProjectPIC
	/*-------------------------------------------------------------------------------------*/
	public  function saveProjectPIC($mode, $project_id, $employee_id) {
		if ( $project_id !== '0' ) {
			if ($mode =='add'){
				$sql = "insert into project_auditor ( project_id, employee_id ) 
						values ('$project_id', '$employee_id')"; 
			}
			
			if ($mode =='del'){
				$sql = "delete from project_auditor where id ='$employee_id'"; 
			}
			
			$this->db->query($sql);		
		}
	}	
		
	//  getTimesheetRequest
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetWaiting() {
		$sql = "select a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				b.employeenickname approval, c.employeenickname  requestor, a.timesheet_approval
				from timesheet_status a
				left join employee b on a.employee_id = b.employee_id
				left join employee c on a.timesheet_approval_employee = c.employee_id
				where timesheet_approval = 1 and a.approval_id = '".$this->session->userdata('employee_id') ."'
				order by drequest desc";
		return $this->rst2Array($sql);
	}
	

	//  getTimesheetRequest
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetRequest() {
		$sql = "select a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
					DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
					b.employeenickname approval, c.employeenickname  requestor, a.timesheet_approval
				from timesheet_status a
				left join employee b on a.employee_id = b.employee_id
				left join employee c on a.timesheet_approval_employee = c.employee_id
				where timesheet_approval = 1 and a.employee_id = '".$this->session->userdata('employee_id') ."'
				order by drequest desc";
		return $this->rst2Array($sql);
	}
	

	//  getTimesheetApproved
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetApproved() {
		$sql = "select a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
					DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
					b.employeenickname approval, c.employeenickname  requestor, a.timesheet_approval
				from timesheet_status a
				left join employee b on a.employee_id = b.employee_id
				left join employee c on a.timesheet_approval_employee = c.employee_id
				where timesheet_approval = 2 and a.employee_id = '".$this->session->userdata('employee_id') ."'
				order by drequest desc";
		return $this->rst2Array($sql);
	}
	

	//  getTimesheetApproved
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetActive() {
		$sql = "select a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				'' approval, '' requestor, a.timesheet_approval
				from timesheet_status a
				where timesheet_approval is null and a.employee_id = '".$this->session->userdata('employee_id') ."'
				order by drequest desc";
		return $this->rst2Array($sql);
	}
	
	
	//  getTimesheetWeeklyStatus
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetStatus() {
		$whereClause 	= "";

		$sql = "select a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest, 
					DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval, 
				b.employeenickname approval, c.employeenickname  requestor, a.timesheet_approval
				from timesheet_status a
				left join employee b on a.employee_id = b.employee_id
				left join employee c on a.timesheet_approval_employee = c.employee_id
				where 1=1 $whereClause order by a.year, a.week desc, a.sysdate";
		return $this->rst2Array($sql);
	}


	//  getTimesheetProject
	/*-------------------------------------------------------------------------------------*/
	public  function getTimesheetProject($project_id) {
		$sql = "select a.timesheetid id, a.project_id, a.job_id, a.timesheet_approval, a.cost, a.employee_id,a.week, a.year, a.hour, a.notes, a.timesheetdate, b.job_no, b.job 
				from timesheet a inner join job b on a.job_id = b.job_id where a.project_id ='$project_id' and a.employee_id = '".$this->session->userdata('employee_id') ."' order by a.year desc, a.week desc, a.timesheetdate desc";
		//echo $sql ;
		return $this->rst2Array($sql);
	}


	//  getTimesheetNik
	/*-------------------------------------------------------------------------------------*/	
	public  function getTimesheetNik() {
		$sql = "select a.*, b.* from project_timesheet a inner join job b on a.job_id = b.job_id where a.employee_id='".$this->session->userdata('nik') ."' order by a.timesheetdate";
		return $this->rst2Array($sql);
	}
	

	//  getTimesheetDetail
	/*-------------------------------------------------------------------------------------*/	
	public  function getTimesheetDetail($id) {
		//$sql = "select * from timesheet where timesheetid ='$id'";
		$sql = "select timesheetid from timesheet where timesheetid ='$id'";
		return $this->rst2Array($sql, 10);
	}
	

	//  getTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function getTimesheetWeek($week, $year) {
		$sql = "select timesheetid, project_id, job_id, week, year, timesheetdate, hour, overtime,
										cost, transport_type, transport_cost, notes
					 from timesheet where week ='$week' and year='$year'";
		return $this->rst2Array($sql, 10);
	}
	

	//  getTimesheetWeekView
	/*-------------------------------------------------------------------------------------*/
	public  function getTimesheetWeekView($week, $year) {
		$sql = "select a.*,b.project_no, b.project,c.client_no, c.client_name, d.job  
					from timesheet a 
					left join project b on a.project_id = b.project_id 
					left join client c on b.client_id = c.client_id 
					left join job d on a.job_id = d.job_id
					where a.week ='$week' and a.year='$year'";
		
		return $this->rst2Array($sql);
	}
		
	
	//  getTimesheetWeekView
	/*-------------------------------------------------------------------------------------*/
	public  function getTimesheetActiveStatus($id) {
		$sql = "select a.*,b.project_no, b.project,c.client_no, c.client_name, d.job  
				from timesheet_status x
				inner join timesheet a on a.timesheet_status_id = x.timesheet_status_id
				left join project b on a.project_id = b.project_id 
				left join client c on b.client_id = c.client_id 
				left join job d on a.job_id = d.job_id
				where x.timesheet_status_id='$id'";
		
		return $this->rst2Array($sql);
	}

	//  getTimesheetApproved
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetDone() {
		$sql = "select a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				y.employeenickname approval, x.employeenickname requestor, a.timesheet_approval
				from timesheet_status a
				inner join employee x on a.employee_id = x.employee_id
				inner join employee y on a.approval_id = y.employee_id
				where timesheet_approval=2 and a.employee_id = '".$this->session->userdata('employee_id') ."'
				order by drequest desc";
		return $this->rst2Array($sql);
	}
	
	


	//  getTimesheetRequest
	/*-------------------------------------------------------------------------------------*/	
/*
	public  function getTimesheetRequest($week, $year) {
			$sql = "select flag_approval saveTimesheetRequest project_weekly	where week ='$week' and year='$year' and employee_id='".$this->session->userdata('employee_id') ."' ";
		return $this->rst2Array($sql, 11);
	}
*/

	//  getTimesheetApproval
	/*-------------------------------------------------------------------------------------*/
	public  function getTimesheetApproval($week, $year) {
		$sql = "select flag_approval from project_weekly	where week ='$week' and year='$year' and nik_approval='".$this->session->userdata('employee_id') ."' ";
		return $this->rst2Array($sql, 11);
	}
	

	//  saveTimesheetRequest
	/*-------------------------------------------------------------------------------------*/	
	public  function saveTimesheetRequest($id) {
		//$sql = "update timesheet_status	set timesheet_approval = 1, drequest=now() where timesheet_status_id ='$id'";
		
		$sql = "update timesheet_status,
(select max(c.approval_id) approval_id from timesheet a
inner join project_team c on a.project_id = c.project_id and a.employee_id = c.employee_id
where a.timesheet_status_id = '$id'
 ) b

  set timesheet_status.timesheet_approval = 1,
    timesheet_status.drequest='".date('Y-m-d H:i:s')."',
    timesheet_status.approval_id = b.approval_id
where timesheet_status_id ='$id'";
	//	echo $sql ;
		$this->db->query($sql);		
	}


	//  saveTimesheetRequest
	/*-------------------------------------------------------------------------------------*/	
	public  function saveApproveTimesheet($id) {
		//$sql = "update timesheet_status	set timesheet_approval = 1, drequest=now() where timesheet_status_id ='$id'";
		
		$sql = "update timesheet_status
  set timesheet_approval = 2,
    dapproval ='".date('Y-m-d H:i:s')."'
	where timesheet_status_id ='$id'";
		//echo $sql ;
		$this->db->query($sql);		
	}
	

	//  getTimesheetEmployeeWeek
	/*-------------------------------------------------------------------------------------*/
	public  function getTimesheetEmployeeWeek($week, $year) {
		$sql = "select * from project_timesheet where employee_id='".$this->session->userdata('employee_id') ."' and week ='$week' and year='$year'";
		return $this->rst2Array($sql, 10);
	}
	

	//  checkTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function checkTimesheetWeek($week, $year) {
		$sql = "select * from timesheet_status where employee_id='".$this->session->userdata('employee_id') ."' and week ='$week' and year='$year'";
		return $this->rst2Array($sql, 10);
	}


	//  insertTimesheetWeekly
	/*-------------------------------------------------------------------------------------*/
	public function insertTimesheetWeekly( $week, $year)  {
		$sql = "insert into timesheet_status ( week, year, employee_id, sysdate, sysuser)
				values ('". $week ."', '". $year ."','".$this->session->userdata('employee_id')."','".date('Y-m-d H:i:s')."','".$this->session->userdata('employee_id')."')"; 
		$this->db->query($sql);			
		return  $this->db->insert_id();;
	}


	//  saveTimesheet
	/*-------------------------------------------------------------------------------------*/
	public  function saveTimesheet($form, $timesheet_status_id) {
		if ( strlen( $form['timesheetdate']) >0) {
			$timesheetdate = "{d '".date("Y-m-d",strtotime($form['timesheetdate'])) ."'}";
		} else {
			$timesheetdate ='null';
		}
		
		if ( $form['id'] === '0' ) {
			$sql = "insert into timesheet( timesheet_status_id, project_id, employee_id, week, year, job_id, notes, timesheetdate,hour, cost, sysdate,sysuser) 
					values ($timesheet_status_id,'$form[project_id]','".$this->session->userdata('employee_id')."', '$form[week]' , '$form[year]',
					'$form[job_id]', '$form[notes]', $timesheetdate, $form[hour],   $form[cost], 
					'".date('Y-m-d H:i:s')."','".$this->session->userdata('employee_id')."')"; 
		}
		else {
			
			$sql = "update timesheet set project_id ='$form[project_id]',
					week			= '$form[week]',
					year			= '$form[year]',
					employee_id = ".$this->session->userdata('employee_id') .",
					job_id		= '$form[job_id]', 
					notes			= '$form[notes]', 
					timesheetdate	= $timesheetdate, 
					notes			= '$form[notes]', 
					hour			= $form[hour],
					cost			= $form[cost],
					sysdate		    = '".date('Y-m-d H:i:s')."',
					sysuser		    = '".$this->session->userdata('employee_id')."'
					where timesheetid		= $form[id]";
		}
		$this->db->query($sql);		
		//echo $sql;
		if ( $form['id'] === '0' ) {
			$id = $this->db->insert_id();
		} 
		else {
			$id = $form['id'];	
		}
	}	
	
	
	//  getAllCalendarTimesheet
	/*-------------------------------------------------------------------------------------*/
	public function getAllCalendarTimesheet(){
		$sql = "select a.id, a.project_id, a.job_id, a.employee_id, d.personalcalendardate, a.hour, a.notes, a.timesheetdate,
					b.job_no, b.job,
					c.fingerprintid
				from project_timesheet a
				inner join job b on a.job_id = b.job_id
				inner join employee c on a.employee_id =c.employee_id
				inner join personalcalendar d on c.fingerprintid = d.fingerprintid and date(a.timesheetdate) = date(d.personalcalendardate)
				where a.project_id ='11' and a.employee_id = '125'
				order by a.timesheetdate";
	}
		
	
	//  savePassword
	/*-------------------------------------------------------------------------------------*/
	public function savePassword($password){
		$this->load->library('encrypt');
		$sql = "update sys_user set pass ='".$this->encrypt->encode($password)."',
					sysuser	= '".$this->session->userdata('user_id')."' , 
					sysdate = '".date('Y-m-d H:i:s')."'
				where user_id = '".$this->session->userdata('user_id')."'";
		$this->db->query($sql);		
	}
	
	
	//  getUser
	/*-------------------------------------------------------------------------------------*/
	public  function getUser($filter, $limit=0, $offset=0) {
		$whereClause 	= "";
		$selectClause	= $limit ? "a.*,b.*,c.*, b.employeeid,b.employeenickname, b.employeefirstname, b.employeemiddlename, b.employeelastname, c.department " : "count(*) total";
		$limitClause	= $limit ? "order by b.employeenickname limit $limit offset $offset" : "";
		if(isset($filter['nik'])) $whereClause .= " and b.employeeid like '%$filter[nik]%'";
		if(isset($filter['nickname'])) $whereClause .= " and b.employeenickname like '%$filter[nickname]%'";
		if(isset($filter['group'])) $whereClause .= " and c.department like  '%$filter[group]%'";
		
		$sql = "select $selectClause  
					from sys_user a
					inner join employee b on b.employee_id  = a.employee_id
					inner join department c on b.department_id = c.department_id
				where 1=1 $whereClause $limitClause";
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
	
	
	//  getUserDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getUserDetail($user_id) {
		$sql = "select a.*, b.* 
					from sys_user a
					inner join employee b on b.employee_id  = a.employee_id
					inner join department c on b.department_id = c.department_id
				where a.user_id ='$user_id'";
		return $this->rst2Array($sql,10);
	}
	
	
	//  getUserEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getUserEmployee() {
		$sql = "select employee_id, employeefirstname, employeemiddlename, employeelastname 
				from employee a order by  employeefirstname, employeemiddlename, employeelastname ";
		return $this->rst2Array($sql);
	}
	
	
	//  getUserACL
	/*-------------------------------------------------------------------------------------*/
	public  function getUserACL() {
		$sql = "select lookup_code, lookup_label from lookup where lookup_group='auth' order by 1";
		return $this->rst2Array($sql);
	}
	
	
	//  getUserActive
	/*-------------------------------------------------------------------------------------*/
	public  function getUserActive() {
		$sql = "select lookup_code, lookup_label from lookup where lookup_group='status_active' order by 1";
		return $this->rst2Array($sql);
	}


	//  saveUser
	/*-------------------------------------------------------------------------------------*/	
	public  function saveUser($form) {
		if ( $form['user_id'] === '0' ) {
			$this->load->library('encrypt');
			$sql = "insert into sys_user ( pass, employee_id, acl, user_active, sysdate, sysuser) 
				values ('".$this->encrypt->encode('123456')."','$form[employee_id]', '$form[acl]',
				'$form[user_active]', '".date('Y-m-d H:i:s')."', '".$this->session->userdata('user_id')."')"; 
		}
		else {
			$sql = "update sys_user set employee_id ='$form[employee_id]',
					acl			= '$form[acl]',
					user_active	= '$form[user_active]',  
					sysuser		= '".$this->session->userdata('user_id')."', 
					sysdate		= '".date('Y-m-d H:i:s')."'
					where user_id = $form[user_id]";
		}
		$this->db->query($sql);		
		
		if ( $form['user_id'] === '0' ) {
			$id = $this->db->insert_id();
		} 
		else {
			$id = $form['user_id'];	
		}
		redirect('/main/userEdit/'.$id .'/SAVED');
	}	
	
	
	//  getEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getEmployee($filter, $limit=0, $offset=0) {
		$whereClause 	= "";
		$selectClause	= $limit ? " b.*,c.*, b.employeeid,b.employeenickname, b.employeefirstname, b.employeemiddlename, b.employeelastname, c.department " : "count(*) total";
		$limitClause	= $limit ? "order by b.employeenickname limit $limit offset $offset" : "";
		if(isset($filter['nik'])) $whereClause .= " and b.employeeid like '%$filter[nik]%'";
		if(isset($filter['nickname'])) $whereClause .= " and b.employeenickname like '%$filter[nickname]%'";
		if(isset($filter['group'])) $whereClause .= " and c.department like  '%$filter[group]%'";
		
		$sql = "select $selectClause  
				from employee b 
				inner join department c on b.department_id = c.department_id
				where 1=1 $whereClause $limitClause";
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
	
	
	//  getEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getEmployeeList($filter=null ) {
		/*
		$whereClause 	= "";
		if ( strlen( $filter ) > 0 ) {
			$whereClause = " and department_id = '$filter' ";
		}
		*/
		$whereClause = " and department_id = '".$this->session->userdata('department_id')."' ";
		
		if ( strlen( $filter ) > 0 && strtolower($filter)=='ass') {
			
			$whereClause = " and department_id = 8 ";
		}
		

		if ( strlen( $filter ) > 0 && strtolower($filter)=='pic') {
			
			$whereClause = " and employeetitle='Partner' ";
		}

		$sql = "select employee_id, employeefirstname, employeemiddlename, employeelastname 
				from employee 
				where 1=1 $whereClause
				order by employeefirstname, employeemiddlename, employeelastname ";
		return $this->rst2Array($sql) ;
	}
	
	
	//  getEmployeeDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getEmployeeDetail($employee_id) {
		$sql = "select employee_id, employeefirstname,employeemiddlename,employeelastname,
				employeenickname,employeetitle,employeeid, employeeemail, department_id
				from employee  where employee_id  ='$employee_id'";
		return $this->rst2Array($sql,10);
	}
	
		
	//  getEmployeeDepartment
	/*-------------------------------------------------------------------------------------*/
	public  function getEmployeeDepartment() {
		$sql = "select department_id, department
				from department order by  department";
		return $this->rst2Array($sql);
	}
	
	
	//  saveEmployee
	/*-------------------------------------------------------------------------------------*/	
	public  function saveEmployee($form) {
		if ( $form['employee_id'] === '0' ) {
			$sql = "insert into employee ( employeeid, employeefirstname, employeemiddlename, 
					employeelastname, employeenickname,employeetitle,employeeemail, department_id,
					sysdate, sysuser) 
					values ('$form[employeeid]', '$form[employeefirstname]' , '$form[employeemiddlename]',
					'$form[employeelastname]', '$form[employeenickname]','$form[employeetitle]',
					'$form[employeeemail]','$form[department_id]',					
					'".date('Y-m-d H:i:s')."', '".$this->session->userdata('user_id')."')"; 
		}
		else {
			$sql = "update employee set employeeid ='$form[employeeid]',
					employeefirstname	= '$form[employeefirstname]' , 
					employeemiddlename	= '$form[employeemiddlename]',
					employeelastname	= '$form[employeelastname]',  
					employeenickname	= '$form[employeenickname]',  
					employeetitle		= '$form[employeetitle]',  
					employeeemail		= '$form[employeeemail]',  
					department_id		= '$form[department_id]',  
					sysuser				= '".$this->session->userdata('user_id')."', 
					sysdate				= '".date('Y-m-d H:i:s')."'
					where employee_id = $form[employee_id]";
		}
		$this->db->query($sql);		
		
		if ( $form['employee_id'] === '0' ) {
			$id = $this->db->insert_id();
		} 
		else {
			$id = $form['employee_id'];	
		}
		redirect('/main/employeeEdit/'.$id .'/SAVED');
	}	
	
	
	
	//  getDepartment
	/*-------------------------------------------------------------------------------------*/
	public  function getDepartment() {
		$sql = "select department_id, departmentcode, department,
						a.company_id, b.company
				from department a
				left  join company b on a.company_id = b.company_id
				order by departmentcode";
		return $this->rst2Array($sql);
	} 
	
	
	//  getDepartmentDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getDepartmentDetail($department_id) {
		$sql = "select department_id, departmentcode, department, company_id
				from department  where department_id  ='$department_id'";
		return $this->rst2Array($sql,10);
	}
	
	
	//  saveDepartment
	/*-------------------------------------------------------------------------------------*/	
	public  function saveDepartment($form) {
		if ( $form['department_id'] === '0' ) {
			$sql = "insert into department ( company_id, departmentcode, department,  sysdate, sysuser) 
					values (3, '$form[departmentcode]', '$form[department]', 		
					'".date('Y-m-d H:i:s')."', '".$this->session->userdata('user_id')."')"; 
		}
		else {
			$sql = "update department set departmentcode ='$form[departmentcode]',
					department			= '$form[department]' , 
					sysuser				= '".$this->session->userdata('user_id')."', 
					sysdate				= '".date('Y-m-d H:i:s')."' 
					where department_id = $form[department_id]";
		}
		$this->db->query($sql);		

		if ( $form['department_id'] === '0' ) {
			$id = $this->db->insert_id();
		} 
		else {
			$id = $form['department_id'];	
		}
		redirect('/main/department/0');
	}	
	
	
	//  getJob
	/*-------------------------------------------------------------------------------------*/
	public  function getJob() {
		$sql = "select job_id, job_no, job
				from job order by job_no";
		return $this->rst2Array($sql);
	} 
	
	
	//  getJobDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getJobDetail($job_id) {
		$sql = "select job_id, job_no, job
				from job where job_id  ='$job_id'";
		return $this->rst2Array($sql,10);
	}
	
	
	//  saveJob
	/*-------------------------------------------------------------------------------------*/	
	public  function saveJob($form) {
		if ( $form['job_id'] === '0' ) {
			$sql = "insert into job ( job_id, job_no, job,  sysdate, sysuser) 
					values ('$form[job_id]', '$form[job_no]', '$form[job]', 		
					'".date('Y-m-d H:i:s')."', '".$this->session->userdata('user_id')."')"; 
		}
		else {
			$sql = "update job  set job_no ='$form[job_no]',
					job		= '$form[job]' , 
					sysuser	= '".$this->session->userdata('user_id')."', 
					sysdate	= '".date('Y-m-d H:i:s')."'
					where job_id = $form[job_id]";
		}
		$this->db->query($sql);		
		
		redirect('/main/job/0');
	}	
	
	
	//  getReportEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getReportEmployee($data) {
		$sql = "select DATE_FORMAT(a.personalcalendardate, '%d - %m - %Y') personalcalendardate,a.timecome, a.timehome,
					a.latein, a.earlyout, a.overtime, a.totalot, a.totalhour, 0 actual, 0 budget, 0 balance
				from personalcalendar a
				where a.fingerprintid = '". $data['employee_id'] ."'
				and a.personalcalendardate >= STR_TO_DATE('".$data['date_from']."', '%d/%m/%Y')
				and a.personalcalendardate <= STR_TO_DATE('".$data['date_to']."', '%d/%m/%Y')
				";
		return $this->rst2Array($sql);
	} 


	//  getReportGroup
	/*-------------------------------------------------------------------------------------*/
	public  function getReportGroup($data) {
		$sql = "select b.employeeid,b.employeefirstname, b.employeemiddlename, b.employeelastname,
						b.employeetitle, 
					 '' timecome, '' timehome,
					sum(a.latein) latein, sum(a.earlyout) earlyout, sum(a.overtime) overtime, sum(a.totalot) totalot, 
				sum(a.totalhour) totalhour, 0 actual, 0 budget, 0 balance
				from personalcalendar a
				inner join employee b on a.fingerprintid = b.employee_id
				where b.department_id= '". $data['department_id'] ."'
				and a.personalcalendardate >= STR_TO_DATE('".$data['date_from']."', '%d/%m/%Y')
				and a.personalcalendardate <= STR_TO_DATE('".$data['date_to']."', '%d/%m/%Y')
				group by employee_id
				";
		return $this->rst2Array($sql);
	} 	


	//  getJob
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectTitle() {
		$sql = "select lookup_code, lookup_label
				from lookup where lookup_group='project_title' order by lookup_code";
		return $this->rst2Array($sql);
	} 

}
/* End of file mainModel.php */