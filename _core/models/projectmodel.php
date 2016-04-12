<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class projectModel extends CI_Model {

	public function __construct() {
		parent::__construct();
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
					$result = $rows[0][$keys[0]];
					break;
				default:
					$result = $rows;
					break;
			}
		}
		return $result;
	}
	
	public function getProjectTeamBKI($project_id,$project_title,$arrsql=''){
        $sql = "SELECT pt.teamid,pt.employee_id,pt.project_employee_outsource,pt.team_description
		        From project_team pt
			left join employee e on e.employee_id=pt.employee_id
				WHERE project_id = " .$project_id." AND pt.project_title = '".$project_title."' order by  CONCAT(e.employeefirstname,' ',e.employeemiddlename,' ',e.employeelastname) ASC ";
        return $this->rst2Array($sql,$arrsql);
    }

	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getProject($filter, $limit=0, $offset=0) {
		
		$whereClause 	= ' and a.project_id in ( select distinct project_id from project_team where employee_id = '.$this->session->userdata('employee_id').' )';
		$selectClause	= $limit ? "a.project_id,a.project_no,  a.project, 
															budget_hour, hour, budget_cost, cost, project_approval, 
															year_end, start_date,finish_date, b.client_name " : "count(project_id) total";
		$limitClause	= $limit ? "order by a.year_end DESC, a.project, b.client_name  limit $limit offset $offset" : "";
		if(isset($filter['project'])) $whereClause .= " and a.project like '%$filter[project]%'";
		if(isset($filter['client_name'])) $whereClause .= " and b.client_name like '%$filter[client_name]%'";
		if(isset($filter['project_no'])) $whereClause .= " and a.project_no like '%$filter[project_no]%'";
		$sql = "
			select $selectClause 
			from project a 
			inner join client b on a.client_id =b.client_id 
			where year_end>='2011-01-01' AND 1=1 $whereClause $limitClause";
	  //echo $sql;
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
	
	
	
	//  getProjectStatus
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectStatus($status) {
		
		$whereClause 	= ' and a.project_id in ( select distinct project_id from project_team where employee_id = '.$this->session->userdata('employee_id').' )';
		$selectClause	= "a.project_id,a.project_no,  a.project, 
															budget_hour, hour, budget_cost, cost, project_approval, 
															year_end, start_date,finish_date, b.client_name ";
		$limitClause	= "order by a.project, b.client_name ";
		if(strlen($status) > 0) $whereClause .= " and a.project_approval = '$status' ";
		
		$sql = "
			select $selectClause 
			from project a 
			inner join client b on a.client_id =b.client_id 
			where 1=1 $whereClause";
		return $this->rst2Array($sql);
	}
	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectReview() {
		if ($this->session->userdata('acl')==='01') {
			$whereClause 	= ' and (a.project_approval in (1,2) and a.project_id in ( select distinct project_id from project_team where employee_id = '.$this->session->userdata('employee_id').' and project_title=\'01\')  or (a.project_approval = 1 and a.project_id in ( select distinct project_id from project_team where employee_id = '.$this->session->userdata('employee_id').' and project_title=\'02\' ))  )' ;
		}
		else if ($this->session->userdata('acl')==='02') { 
		   $whereClause 	= ' and a.project_approval in (1,2) and a.project_id in ( select distinct project_id from project_team where employee_id = '.$this->session->userdata('employee_id').' )';
		}  
		else {
		   $whereClause 	= ' and a.project_approval = 1 and a.project_id in ( select distinct project_id from project_team where employee_id = '.$this->session->userdata('employee_id').' )';		
		}

		$selectClause	= "a.project_id,a.project_no,  a.project, 
															budget_hour, hour, budget_cost, cost, project_approval, 
															year_end, start_date,finish_date, b.client_name ";
		$limitClause	= "order by a.project, b.client_name";
		$sql = "
			select $selectClause 
			from project a 
			inner join client b on a.client_id =b.client_id 
			where 1=1 $whereClause $limitClause";
		return $this->rst2Array($sql);
	}
	
	//  getProjectDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectDetail($project_id) {
		$sql = "
			select a.project_id, a.project_no, a.project_approval, a.start_date, a.finish_date,a.project_note,
			a.contract_no, year_end, client_approval_date, createdate,project_status,client_approval,status_collection, location, project
			,b.client_id, b.client_no, b.client_name, c.jobtype_id, c.jobtype, d.employeenickname creator   
			from project a 
			left join client b on a.client_id=b.client_id 
			left join job_type c on a.jobtype_id =c.jobtype_id
			left join employee d on a.createuser = d.employee_id 
			where project_id = $project_id";
		return $this->rst2Array($sql, 10);
	}

	//  getProjectDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectTeamStructure($project_id ) {
		if($this->session->userdata('department_id')==7) $arr_user = "'01','02','03','041','042','777'"; else $arr_user = "'01','02','03','041','042'";
		
		$sql = "
			select
				a.lookup_code, a.lookup_label,a.tipe, ifnull(b.employee_id,0) employee_id,
				ifnull(b.teamid,0) teamid, ifnull(b.budget_hour,0) budget_hour, 
				ifnull(b.budget_cost,0) budget_cost
			from
				(select a.lookup_code, a.lookup_label,a.tipe  from lookup a where lookup_group = 'project_title' and lookup_code in ($arr_user)) a
			left join
				(select project_id, project_title, employee_id, teamid, budget_hour , budget_cost from project_team a where a.project_id = $project_id) b
			on a.lookup_code = b.project_title
			group by a.lookup_code";
	    //echo $sql;
		return $this->rst2Array($sql);
	}


	//  getProjectDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectTeamStructureOther($project_id ) {
		$sql = "
			select
				ifnull(b.project_title,0) project_title,a.lookup_code, a.lookup_label,a.tipe, ifnull(b.employee_id,0) employee_id,
				ifnull(b.teamid,0) teamid, ifnull(b.budget_hour,0) budget_hour, 
				ifnull(b.budget_cost,0) budget_cost
			from
				(select a.lookup_code, a.lookup_label,a.tipe  from lookup a where lookup_group = 'project_title_other') a
			left join
				(select project_id, project_title, employee_id, teamid, budget_hour , budget_cost from project_team a where a.project_id = $project_id) b
			on a.lookup_code = b.project_title";
		return $this->rst2Array($sql);
	}

	public  function getBugetOther($project_id ) {
		$sql = "
			select
				ifnull(b.project_title,0) project_title,a.lookup_code, a.lookup_label,a.tipe, ifnull(b.employee_id,0) employee_id,
				ifnull(b.teamid,0) teamid, ifnull(b.budget_hour,0) budget_hour, 
				ifnull(b.budget_days,0) budget_days, 
				ifnull(b.budget_rate,0) budget_rate, 
				ifnull(b.budget_cost,0) budget_cost,
				ifnull(b.actual_hour,0) actual_hour,
				ifnull(b.actual_cost,0) actual_cost

			from
				(select a.lookup_code, a.lookup_label,a.tipe  from lookup a where lookup_group = 'project_title_other') a
			inner join
				(select teamid, project_title, employee_id,budget_hour,budget_days,budget_rate,budget_cost, actual_hour,actual_cost from project_team a where a.project_id = $project_id) b
			on a.lookup_code = b.project_title";
		return $this->rst2Array($sql);
	}


	//  getProjectJob
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectJob($project_id) {
		$sql = "select a.id, 01_hour, 02_hour, 03_hour, 041_hour, 042_hour, 043_hour,777_hour,01_hour_act, 02_hour_act, 03_hour_act, 041_hour_act, 042_hour_act,043_hour_act,777_hour_act, b.job_no, b.job  from project_job a inner join job b on a.job_id= b.job_id where a.project_id ='$project_id' order by b.job_no";
		return $this->rst2Array($sql);
	}
	
	//  getProjectJob
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectJobDetails($project_id) {
		$sql = " SELECT 
				j.`job_no`,j.`job`,
				pj.`01_hour`,
				SUM(IF(pt.project_title='01',t.hour,0)) AS 01_hour_act,
				pj.`02_hour`,
				0 AS 02_hour_act,
				pj.`03_hour`,
				SUM(IF(pt.project_title='03',t.hour,0)) AS 03_hour_act,
				pj.`041_hour`,
				SUM(IF(pt.project_title='041',t.hour,0)) AS 041_hour_act,
				pj.`042_hour`,
				SUM(IF(pt.project_title='042',t.hour,0)) AS 042_hour_act,
				pj.`043_hour`,
				SUM(IF(pt.project_title='043',t.hour,0)) AS 043_hour_act
				FROM timesheet t
				INNER JOIN project p ON p.`project_id`=t.`project_id`
				INNER JOIN project_job pj ON pj.`project_id`=p.`project_id`
				INNER JOIN project_team pt ON pt.`project_id`=p.`project_id`
				INNER JOIN job j ON j.`job_id`=t.`job_id`
				WHERE t.project_id=".$project_id." AND t.timesheet_approval=2 AND pt.`employee_id`= t.`employee_id` AND pj.`job_id`=j.`job_id`
				GROUP BY j.`job_id`
				ORDER BY j.`job_no`
		";
		return $this->rst2Array($sql);
	}

	//  getProjectAuditor
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectAuditor($project_id) {
		$sql = "select a.employee_id, b.tipe, b.lookup_code from project_team a inner join lookup b on a.project_title = b.lookup_code  and b.lookup_group='project_title' where a.project_id ='$project_id' order by b.lookup_code";
		return $this->rst2Array($sql);
	}

	//  saveProject
	/*-------------------------------------------------------------------------------------*/
	public  function saveProject($form) {
		$project_id = '0';
		$year_end	= preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $form['year_end']);
		$start_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $form['start_date']);
		$finish_date= preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $form['finish_date']);
		
		if($this->session->userdata('department_id') == 7)
			$note = $form['project_note'];
		else
			$note = '';
			
		if ( strlen($year_end)== 0) {
			$year_end ='null';
		} else {
		   $year_end ="'".$year_end."'";
		}

		if ( strlen($start_date)== 0) {
			$start_date  ='null';
		} else {
		   $start_date  ="'".$start_date."'";
		}
    
		if ( strlen($finish_date)== 0) {
			$finish_date  ='null';
		} else {
		   $finish_date  ="'".$finish_date."'";  
		}
        //jobtype_id
		$support = $form['support'];
		if ( strlen($support)== 0) {
			$support ="'0'";
		} else {
		   $support ="'1'";
		}
		
		if ( $form['project_id'] === '0' ) {

				$sql = "insert into project( 
						client_id, jobtype_id, project_no,project, 
						year_end, start_date, finish_date,location, 
						contract_no,  budget_hour, hour, budget_cost, cost, project_note ,
						sysdate, sysuser,createdate, createuser) 
						values (
						'$form[client_id]', '$form[jobtype_id]', '$form[project_no]', '',
						$year_end, 
						$start_date, 
						$finish_date,$support,
						'$form[contract_no]', 0,0,0,0,'$note',
						'".date('Y-m-d H:i:s')."','".$this->session->userdata('employee_id')."',
						'".date('Y-m-d H:i:s')."','".$this->session->userdata('employee_id')."')"; 
				$this->db->query($sql);			
				$project_id = $this->db->insert_id();

		}
		else {
			$sql = "
				update project set 
					project_no 	= '$form[project_no]',
					year_end		= $year_end,  
					start_date	= $start_date, 
					finish_date	= $finish_date, 
					location		= $support,
					contract_no = '$form[contract_no]',
					jobtype_id 	= '$form[jobtype_id]', 
					client_id 	= '$form[client_id]', 
					project_note= '$note',
					sysdate     = '".date('Y-m-d H:i:s')."', 
					sysuser		= '".$this->session->userdata('employee_id')."'
				where project_id = $form[project_id]";
			$this->db->query($sql);		
			$project_id = $form['project_id'];	
		}
		//** End Of Update **//
		
		if($this->session->userdata('department_id')==7){
			$this->saveProjectBKITeam($project_id,$form);
		}
		else{
			$this->saveProjectTeam($project_id,$form);
		}
		
		return $project_id;
		//redirect('/project/Edit/'.$project_id .'/SAVED');
	}	
	
	
	public  function saveProjectStructureTeam($form) {
		if($this->session->userdata('department_id') == 7)
			$this->saveProjectBKITeam($form['project_id'],$form);
		else
			$this->saveProjectTeam($form['project_id'],$form);
	}
	
	public function saveProjectBKITeam($project_id,$form){
		$pic = $form['pic'];
		$gc  = $form['gc'];
		$mic = $form['mic'];
		$aic = $form['aic'];
		$ot  = $form['ot'];
		
		$total_aic = count($aic);
		$total_ot  = count($ot);
		
		$this->projectModel->saveProjectStructure(3,0,$project_id,$pic,0,'01');	
		$this->projectModel->saveProjectStructure(0,0,$project_id,$pic,$this->approvaluser($pic),'01');
		$this->projectModel->saveProjectStructure(0,0,$project_id,$gc,$this->approvaluser($gc),'02');
		$this->projectModel->saveProjectStructure(0,0,$project_id,$mic,$this->approvaluser($mic),'03');
		
		//AIC
		if($total_aic){
			for($i=0;$i<$total_aic;$i++){
				if(strlen($aic[$i]) >=3 ) {
					$employee = $aic[$i];
					$auditor = $this->approvaldepartment($employee);
					$this->projectModel->saveProjectStructure(0,0,$project_id,$employee,$this->approvaluser($employee),'041',0);
				}
			}
		}
		
		//OT
		if($total_ot){
			for($i=0;$i<$total_ot;$i++){
				$employee = $ot[$i];
				$this->projectModel->saveProjectStructure(0,0,$project_id,$employee,$this->approvaluser($employee),'777',1);
			}
		}
		
	}
	
	//check approval user
    public function approvaluser($user_id){
	    if(!$user_id)
			$user_id = 0;
        $sql = " SELECT approval_id FROM employee WHERE employee_id=".$user_id;
        $data = $this->rst2Array($sql,10);
		if($data)
			$id = $data['approval_id'];
		else
			$id = 0;
		return $id;
    }
	
	//check approval user
    public function approvaldepartment($user_id){
	    if(!$user_id) $user_id = 0;
        $sql = " SELECT department_id FROM employee WHERE employee_id=".$user_id;
        $data = $this->rst2Array($sql,10);
		if($data) $id = $data['department_id'];
		else $id = 0;
		return $id;
    }
	
	/*-------------------------------------------------------------------------------------*/
	public  function saveProjectStructure($mode, $teamid, $project_id, $employee_id, $approval_id,$project_title,$outsource=0,$desc='') {
		if ($mode =='0'){
			$sql = "insert into project_team ( project_id, employee_id, approval_id, project_title,project_employee_outsource,team_description) 
					values ('$project_id', '$employee_id','$approval_id','$project_title',$outsource,'$desc')"; 
			$this->db->query($sql);		
		}
		if ($mode =='1'){
			$sql = "
				update project_team 
				set 
					employee_id   = '$employee_id',
					approval_id   = '$approval_id',
					project_title = '$project_title',					
					sysdate		  = '".date('Y-m-d H:i:s')."',
					sysuser		  = '".$this->session->userdata('employee_id')."'
				where teamid      = '$teamid'";
			$this->db->query($sql);		
		}
		if ($mode =='2'){
			$sql = "delete from project_team where teamid = $teamid";
			$this->db->query($sql);		
		}
		
		if ($mode =='3'){
			$sql = "delete from project_team where project_id=$project_id";
			$this->db->query($sql);		
		}
		//echo "$sql<br>";
	}	
	
	public function saveProjectTeam($project_id,$form){
	   if ( $project_id !='0' && count( $form['teamid']) >0){
    		  //print_r($form['teamid']);
    			foreach ($form['teamid'] as $k=>$v) {
    				$approval_id = '0';
    				if ($k > 0){
    					if ($k == 3  ) {
                if ( $form['employee_id'][2] == "0" ) {
                     if ( $form['employee_id'][1] == "0" ) {
                        $approval_id = $form['employee_id'][0];
                     } else  {
                        $approval_id = $form['employee_id'][1];
                     }
                } else {
    						    $approval_id = $form['employee_id'][2];
    						}
    					}
    					else {	
    						$approval_id = $form['employee_id'][$k-1];
    					}
    
    				}
    				if ($k < 4 ) { 
    					$employee_id = $form['employee_id'][$k];
    					if (strlen($employee_id) > 0 && $employee_id !='0') {	
    						if ( $form['teamid'][$k] == 0 ) {
    							$mode = 0;
    						} 
    						else {
    							$mode = 1;
    							if ($employee_id == 0) {
    								$mode = 2;	
    							}
    						}
    						$this->projectModel->saveProjectStructure($mode, $form['teamid'][$k],$project_id, $employee_id, $approval_id,$form['project_title'][$k]);
    					}
    				}
    			}
    	}
		
		
		if ( $project_id !='0' && count( $form['teamotherid']) >0){
			$ProjectOther = $this->projectModel->getProjectTeamStructureOther($project_id);
			if (count($ProjectOther) > 0 ) {
				foreach ($ProjectOther as $k=>$v) {
				  if ( is_array( $form['project_title_other'])) {
					  if (in_array($ProjectOther[$k]['lookup_code'], $form['project_title_other'])) {
						  if ($ProjectOther[$k]['project_title']==0){
							$this->projectModel->saveProjectStructureOther(0, 0, $project_id, $ProjectOther[$k]['lookup_code']) ;
						  }
					  }

					  if (!in_array($ProjectOther[$k]['lookup_code'], $form['project_title_other'])) {
						  if ($ProjectOther[$k]['project_title']!=0){
							//$this->projectModel->saveProjectStructureOther(0, 0, $project_id, $ProjectOther[$k]['lookup_code']) ;
							$this->projectModel->saveProjectStructureOther(2, $form['teamotherid'][$k], $project_id, $ProjectOther[$k]['lookup_code']) ;
						  }
					  }
					  
				  } else {
					  if ($ProjectOther[$k]['project_title']!=0){
						$this->projectModel->saveProjectStructureOther(2, $form['teamotherid'][$k], $project_id, $ProjectOther[$k]['lookup_code']) ;
					  }
				  }
				}
			} else {
			  if ( count( $form['project_title_other']) >0) {
				foreach ($form['project_title_other'] as $k=>$v) {
				  $this->projectModel->saveProjectStructureOther(0, 0, $project_id, $form['project_title_other'][$k]) ;
				}
			  }
			}
		}
	}
	
	
	
	

	


	/*-------------------------------------------------------------------------------------*/
	public  function saveProjectStructureOther($mode, $teamid, $project_id, $project_title) {
		if ($mode =='0'){
			$sql = "insert into project_team ( project_id, project_title) 
					values ('$project_id', '$project_title')"; 
			$this->db->query($sql);		
		}
		if ($mode =='1'){
			$sql = "
				update project_team 
				set 
					project_title = '$project_title',					
					sysdate		  = '".date('Y-m-d H:i:s')."',
					sysuser		  = '".$this->session->userdata('employee_id')."'
				where teamid= '$teamid'";
			$this->db->query($sql);		
		}
		if ($mode =='2'){
			$sql = "delete from project_team where teamid = $teamid";
			$this->db->query($sql);		
		}
	}	
	
	//  getJobList
	/*-------------------------------------------------------------------------------------*/
	public function getProjectTitleOther($project_id){
		$sql = "SELECT teamid, project_id, project_title FROM project_team
              where project_id = $project_id
              and project_title in ('051','052','053','054','055')";
		return $this->rst2Array($sql);
	}


	//  getJobList
	/*-------------------------------------------------------------------------------------*/
	public function getJobList($project_id){
		//ARCS
		$filter=" and JOBTYPE = 'AUDIT' ";

		if ($this->session->userdata('department_id') == '19'){
			$filter=" and JOBTYPE = 'PPR'";
		}	

		if ($this->session->userdata('department_id') == '20'){
			$filter=" and JOBTYPE = 'ARCS' ";
		}	
		if ($this->session->userdata('department_id') == '21'){
			$filter=" and JOBTYPE = 'BATA'";
		}	
		
		
		$sql = "
			select job_id, job_no, job 
			from job 
			where job_id not in 
			( 
				select job_id 
				from project_job 
				where project_id = $project_id 
			)  and left( job_no,3) <> 'HRD'
			$filter
			order by job_no";
		return $this->rst2Array($sql);
		
	}

	//  getJobListDel
	/*-------------------------------------------------------------------------------------*/
	public function getJobListDel($project_id){
		$sql = "
			select a.id job_id,b.job_no, b.job 
			from project_job a 
			left join job b on a.job_id = b.job_id 
			where a.project_id = $project_id 
			order by a.job_id";
		return $this->rst2Array($sql);
	}	
	
	//  getJobList UPDATE BARU DARI IPOEL
	/*-------------------------------------------------------------------------------------*/
	public function getJobListType($project_id, $jobtype_id){
		//ARCS
		/*
		$filter=" and JOBTYPE = 'AUDIT' ";

		if ($this->session->userdata('department_id') == '19'){
			$filter=" and JOBTYPE = 'PPR'";
		}	

		if ($this->session->userdata('department_id') == '20'){
			$filter=" and JOBTYPE = 'ARCS' ";
		}	
		if ($this->session->userdata('department_id') == '21'){
			$filter=" and JOBTYPE = 'BATA'";
		}	
		*/
		
		$sql = "
			select job_id, job_no, job 
			from job 
			where job_id not in 
			( 
				select job_id 
				from project_job 
				where project_id = $project_id 
			)  and left( job_no,3) <> 'HRD'
			AND jobtype_id = $jobtype_id  
			order by job_no";
			//echo $sql;
		return $this->rst2Array($sql);
	}
	
	//  saveProjectJob
	/*-------------------------------------------------------------------------------------*/
	public  function saveProjectJob($mode, $project_id, $job_id) {
		if ( $project_id !== '0' ) {
			if ($mode =='add'){
				$sql = "insert into project_job ( project_id, job_id ) 
						values ( $project_id, $job_id )"; 
				$this->db->query($sql);		
			}
			
			if ($mode =='del'){
				$sql = "delete from project_job where id = $job_id"; 
				$this->db->query($sql);		
				
				$job  = $this->getBugetJob($project_id);
				$rate = $this->getBugetTotal($project_id);

				$amount[0] = $rate[0]['budget_rate'] * $job['01_day'];
				$amount[1] = $rate[1]['budget_rate'] * $job['02_day'];
				$amount[2] = $rate[2]['budget_rate'] * $job['03_day'];
				$amount[3] = $rate[3]['budget_rate'] * $job['041_day'];
				$amount[4] = $rate[4]['budget_rate'] * $job['042_day'];

				if (strlen($amount[0]) == 0) $amount[0] =0;
				if (strlen($amount[1]) == 0) $amount[1] =0;
				if (strlen($amount[2]) == 0) $amount[2] =0;
				if (strlen($amount[3]) == 0) $amount[3] =0;
				if (strlen($amount[4]) == 0) $amount[4] =0;

				if (strlen($job['01_hour']) == 0) $job['01_hour'] =0;
				if (strlen($job['02_hour']) == 0) $job['02_hour'] =0;
				if (strlen($job['03_hour']) == 0) $job['03_hour'] =0;
				if (strlen($job['041_hour']) == 0) $job['041_hour'] =0;
				if (strlen($job['042_hour']) == 0) $job['042_hour'] =0;
				
				if (strlen($job['01_day']) == 0) $job['01_day'] =0;
				if (strlen($job['02_day']) == 0) $job['02_day'] =0;
				if (strlen($job['03_day']) == 0) $job['03_day'] =0;
				if (strlen($job['041_day']) == 0) $job['041_day'] =0;
				if (strlen($job['042_day']) == 0) $job['042_day'] =0;


				$sql = "update project_team set 
						budget_hour = ".$job['01_hour'].",
						budget_days= ".$job['01_day'].",
						budget_cost= ".$amount[0]."
					where project_id = ".$project_id ." and project_title ='01'";
				$this->db->query($sql);								

				$sql = "update project_team set 
						budget_hour = ".$job['02_hour'].",
						budget_days= ".$job['02_day'].",
						budget_cost= ".$amount[1]."
					where project_id = ".$project_id ." and project_title ='02'";
				$this->db->query($sql);								

				$sql = "update project_team set 
						budget_hour = ".$job['03_hour'].",
						budget_days= ".$job['03_day'].",
						budget_cost= ".$amount[2]."
					where project_id = ".$project_id." and project_title ='03'";
				$this->db->query($sql);								

				$sql = "update project_team set 
						budget_hour = ".$job['041_hour'].",
						budget_days= ".$job['041_day'].",
						budget_cost= ".$amount[3]."
					where project_id = ".$project_id." and project_title ='041'";
				$this->db->query($sql);								

				$sql = "update project_team set 
						budget_hour = ".$job['042_hour'].",
						budget_days= ".$job['042_day'].",
						budget_cost= ".$amount[4]."
					where project_id = ".$project_id." and project_title ='042'";
				$this->db->query($sql);								
/*
				$sql = "update project_team set 
						budget_hour = ".$job['043_hour'].",
						budget_days= ".$job['043_day'].",
						budget_cost= ".$amount[5]."
					where project_id = ".$project_id." and project_title ='043'";
				$this->db->query($sql);								

				$sql = "update project_team set 
						budget_hour = ".$job['044_hour'].",
						budget_days= ".$job['044_day'].",
						budget_cost= ".$amount[6]."
					where project_id = ".$project_id." and project_title ='044'";
				$this->db->query($sql);								
*/
			}
		}
	}	

	//  saveProjectBudgetCost
	/*-------------------------------------------------------------------------------------*/
	public function saveProjectBudgetCost($form){
	  $dept = true;
	  $total_budget = 0;
	  $total_hour = 0;
	  if($this->session->userdata('department_id')==7) $dept = TRUE; else $dept = FALSE;
	  if (!empty($form['id'])) {
  		if ( count($form['id']) >0){
  			foreach ($form['id'] as $k=>$v) {
  				if (strlen($form['01_hour'][$k]) == 0) $form['01_hour'][$k] =0;
  				if (strlen($form['02_hour'][$k]) == 0) $form['02_hour'][$k] =0;
  				if (strlen($form['03_hour'][$k]) == 0) $form['03_hour'][$k] =0;
  				if (strlen($form['041_hour'][$k]) == 0) $form['041_hour'][$k] =0;
  				if (strlen($form['042_hour'][$k]) == 0) $form['042_hour'][$k] =0;
				if($dept==TRUE)
				   if (strlen($form['777_hour'][$k]) == 0) $form['777_hour'][$k] =0;
  				
				if($dept==FALSE) $form['777_hour'][$k] = 0;
				
  				$sql = "update project_job set 
  						01_hour = ".$form['01_hour'][$k].",
  						02_hour = ".$form['02_hour'][$k].",
  						03_hour = ".$form['03_hour'][$k].",
  						041_hour = ".$form['041_hour'][$k].",
  						042_hour = ".$form['042_hour'][$k].",
						777_hour = ".$form['777_hour'][$k]."
  						where id = ".$form['id'][$k];
  				$this->db->query($sql);		
  
  				$sql = "update project_job set 
  						jobhour = ifnull(01_hour,0) + 
  									 ifnull(02_hour,0) + 
  									 ifnull(03_hour,0) + 
  									 ifnull(041_hour,0) + 
									 ifnull(777_hour,0) + 
  									 ifnull(042_hour,0)
  						where id = ".$form['id'][$k];
  				$this->db->query($sql);	
  			}
  			
  			// Project Team
  			$job = $this->getBugetJob($form['project_id']);
  			if (strlen($job['01_day']) == 0) $job['01_day'] =0;
  			if (strlen($job['02_day']) == 0) $job['02_day'] =0;
  			if (strlen($job['03_day']) == 0) $job['03_day'] =0;
  			if (strlen($job['041_day']) == 0) $job['041_day']=0;
  			if (strlen($job['042_day']) == 0) $job['042_day']=0;
			if (strlen($job['777_day']) == 0) $job['777_day']=0;
  
  			if (strlen($job['01_hour']) == 0) $job['01_hour'] =0;
  			if (strlen($job['02_hour']) == 0) $job['02_hour'] =0;
  			if (strlen($job['03_hour']) == 0) $job['03_hour'] =0;
  			if (strlen($job['041_hour']) == 0) $job['041_hour']=0;
  			if (strlen($job['042_hour']) == 0) $job['042_hour']=0;
			if (strlen($job['777_hour']) == 0) $job['777_hour']=0;
  
  
  			$amount[0] = $_POST['01_rate'] * $job['01_day'];
  			$amount[1] = $_POST['02_rate'] * $job['02_day'];
  			$amount[2] = $_POST['03_rate'] * $job['03_day'];
  			$amount[3] = $_POST['041_rate'] * $job['041_day'];
  			$amount[4] = $_POST['042_rate'] * $job['042_day'];
			if($dept==TRUE)
			  $amount[5] = $_POST['777_rate'] * $job['777_day'];
  
  			$sql = "update project_team set 
  					budget_hour = ".$job['01_hour'].",
  					budget_days= ".$job['01_day'].",
  					budget_rate = ".$_POST['01_rate'].",
  					budget_cost = ".$amount[0]."
  				where project_id = ".$form['project_id'] ." and project_title ='01'";
  			$this->db->query($sql);								
  
  			$sql = "update project_team set 
  					budget_hour = ".$job['02_hour'].",
  					budget_days= ".$job['02_day'].",
  					budget_rate = ".$_POST['02_rate'].",
  					budget_cost= ".$amount[1]."
  				where project_id = ".$form['project_id'] ." and project_title ='02'";
  			$this->db->query($sql);								
  
  			$sql = "update project_team set 
  					budget_hour = ".$job['03_hour'].",
  					budget_days= ".$job['03_day'].",
  					budget_rate = ".$_POST['03_rate'].",
  					budget_cost= ".$amount[2]."
  				where project_id = ".$form['project_id'] ." and project_title ='03'";
  			$this->db->query($sql);								
		
		
		
	/** In Charge **/
        $sql = "select * from project_team 
                where project_id = ".$form['project_id']." and project_title='041'";
        $team_ass = $this->rst2Array($sql) ;
		
        if (count($team_ass) == 0){
        	 $sql = "insert into project_team ( project_id, employee_id, approval_id, project_title) 
      					values (".$form['project_id'].",0,0, '041')"; 
      			$this->db->query($sql);	
        }
        
  			$sql = "update project_team set 
  					budget_hour = ".$job['041_hour'].",
  					budget_days= ".$job['041_day'].",
  					budget_rate = ".$_POST['041_rate'].",
  					budget_cost= ".$amount[3]."
  				where project_id = ".$form['project_id'] ." and project_title ='041'";
  			$this->db->query($sql);		

			/**In Charge**/	
        
		/** Assistant **/
        $sql = "select * from project_team 
                where project_id = ".$form['project_id']." and project_title='042'";
        $team_ass = $this->rst2Array($sql) ;
		
        if (count($team_ass) == 0){
        	 $sql = "insert into project_team ( project_id, employee_id, approval_id, project_title) 
      					values (".$form['project_id'].",0,0, '042')"; 
      			$this->db->query($sql);	
        }
        
  			$sql = "update project_team set 
  					budget_hour = ".$job['042_hour'].",
  					budget_days= ".$job['042_day'].",
  					budget_rate = ".$_POST['042_rate'].",
  					budget_cost= ".$amount[4]."
  				where project_id = ".$form['project_id'] ." and project_title ='042'";
  			$this->db->query($sql);		

		/** Assistant **/	
		
		//total budget & cost
		$total_budget += ($amount[0] + $amount[1]+$amount[2]+$amount[3]+$amount[4]);
		$total_hour+= ($job['01_hour']+$job['02_hour']+$job['03_hour']+$job['041_hour']+$job['042_hour']);
		
		/** Outsource **/
		if($dept==TRUE){
			$sql = "select * from project_team 
					where project_id = ".$form['project_id']." and project_title='777'";
			$team_ass = $this->rst2Array($sql) ;
			
			if (count($team_ass) == 0){
				 $sql = "insert into project_team ( project_id, employee_id, approval_id, project_title) 
							values (".$form['project_id'].",0,0, '777')"; 
					$this->db->query($sql);	
			}
			
				$sql = "update project_team set 
						budget_hour = ".$job['777_hour'].",
						budget_days= ".$job['777_day'].",
						budget_rate = ".$_POST['777_rate'].",
						budget_cost= ".$amount[5]."
					where project_id = ".$form['project_id'] ." and project_title ='777'";
				$this->db->query($sql);	
			$total_budget += $amount[5];
			$total_hour += $job['777_hour'];
		}
		/** Outsource **/
			
		
  		}
		
		
		
  }
		if (isset($form['id-oth'])){
			if (!empty($form['id-oth'])) {
				if ( count($form['id-oth']) >0){
					  $oth_budget = 0;
					  $oth_hour =0;
					  foreach ($form['id-oth'] as $k=>$v) {
						if (strlen($form['other_budget_hour'][$k]) == 0) $form['other_budget_hour'][$k] =0;
						if (strlen($form['other_actual_hour'][$k]) == 0) $form['other_actual_hour'][$k] =0;
						if (strlen($form['other_actual_cost'][$k]) == 0) $form['other_actual_cost'][$k] =0;
						if (strlen($form['other_budget_cost'][$k]) == 0) $form['other_budget_cost'][$k] =0;
		  
						 //
						$sql = "update project_team set 
							budget_hour = ".$form['other_budget_hour'][$k].",
							actual_hour = ".$form['other_actual_hour'][$k].",
							actual_cost = ".$form['other_actual_cost'][$k].",
							budget_cost = ".$form['other_budget_cost'][$k]."
							where teamid = ".$form['id-oth'][$k];
					        $this->db->query($sql);
						$oth_budget+=$form['other_budget_cost'][$k];
						$oth_hour+=$form['other_budget_hour'][$k];
					}
					
					//update budget cost
					$total_budget+=$oth_budget;
					$total_hour+=$oth_hour;
				 }
				 
			}	 
		}
		
		/**Update Project Budget Hour */
		
		$sql = "update project set 
			budget_cost = ".$total_budget.",
			budget_hour = ".$total_hour."
			where project_id = ".$form['project_id'];
		$this->db->query($sql);
			
	}
	
	//  saveProjectBudgetCost
	/*-------------------------------------------------------------------------------------*/
	public function UpdateProjectBudgetCost($form){
	  $dept = true;
	  $total_budget = 0;
	  $total_hour = 0;
	  if($this->session->userdata('department_id')==7) $dept = TRUE; else $dept = FALSE;
	  if (!empty($form['id'])) {
  		if ( count($form['id']) >0){
  			foreach ($form['id'] as $k=>$v) {
  				if (strlen($form['01_hour'][$k]) == 0) $form['01_hour'][$k] =0;
  				if (strlen($form['02_hour'][$k]) == 0) $form['02_hour'][$k] =0;
  				if (strlen($form['03_hour'][$k]) == 0) $form['03_hour'][$k] =0;
  				if (strlen($form['041_hour'][$k]) == 0) $form['041_hour'][$k] =0;
  				if (strlen($form['042_hour'][$k]) == 0) $form['042_hour'][$k] =0;
				if($dept==TRUE)
				   if (strlen($form['777_hour'][$k]) == 0) $form['777_hour'][$k] =0;
  				
				if($dept==FALSE) $form['777_hour'][$k] = 0;
				
  				$sql = "update project_job set 
  						01_hour = ".$form['01_hour'][$k].",
  						02_hour = ".$form['02_hour'][$k].",
  						03_hour = ".$form['03_hour'][$k].",
  						041_hour = ".$form['041_hour'][$k].",
  						042_hour = ".$form['042_hour'][$k].",
						777_hour = ".$form['777_hour'][$k]."
  						where id = ".$form['id'][$k];
  				$this->db->query($sql);		
  
  				$sql = "update project_job set 
  						jobhour = ifnull(01_hour,0) + 
  									 ifnull(02_hour,0) + 
  									 ifnull(03_hour,0) + 
  									 ifnull(041_hour,0) + 
									 ifnull(777_hour,0) + 
  									 ifnull(042_hour,0)
  						where id = ".$form['id'][$k];
  				$this->db->query($sql);	
  			}
  			
  			// Project Team
  			$job = $this->getBugetJob($form['project_id']);
  			if (strlen($job['01_day']) == 0) $job['01_day'] =0;
  			if (strlen($job['02_day']) == 0) $job['02_day'] =0;
  			if (strlen($job['03_day']) == 0) $job['03_day'] =0;
  			if (strlen($job['041_day']) == 0) $job['041_day']=0;
  			if (strlen($job['042_day']) == 0) $job['042_day']=0;
			if (strlen($job['777_day']) == 0) $job['777_day']=0;
  
  			if (strlen($job['01_hour']) == 0) $job['01_hour'] =0;
  			if (strlen($job['02_hour']) == 0) $job['02_hour'] =0;
  			if (strlen($job['03_hour']) == 0) $job['03_hour'] =0;
  			if (strlen($job['041_hour']) == 0) $job['041_hour']=0;
  			if (strlen($job['042_hour']) == 0) $job['042_hour']=0;
			if (strlen($job['777_hour']) == 0) $job['777_hour']=0;
  
  
  			$amount[0] = $_POST['01_rate'] * $job['01_day'];
  			$amount[1] = $_POST['02_rate'] * $job['02_day'];
  			$amount[2] = $_POST['03_rate'] * $job['03_day'];
  			$amount[3] = $_POST['041_rate'] * $job['041_day'];
  			$amount[4] = $_POST['042_rate'] * $job['042_day'];
			if($dept==TRUE)
			  $amount[5] = $_POST['777_rate'] * $job['777_day'];
  
  			$sql = "update project_team set 
  					budget_hour = ".$job['01_hour'].",
  					budget_days= ".$job['01_day'].",
  					budget_rate = ".$_POST['01_rate'].",
  					budget_cost = ".$amount[0]."
  				where project_id = ".$form['project_id'] ." and project_title ='01'";
  			$this->db->query($sql);								
  
  			$sql = "update project_team set 
  					budget_hour = ".$job['02_hour'].",
  					budget_days= ".$job['02_day'].",
  					budget_rate = ".$_POST['02_rate'].",
  					budget_cost= ".$amount[1]."
  				where project_id = ".$form['project_id'] ." and project_title ='02'";
  			$this->db->query($sql);								
  
  			$sql = "update project_team set 
  					budget_hour = ".$job['03_hour'].",
  					budget_days= ".$job['03_day'].",
  					budget_rate = ".$_POST['03_rate'].",
  					budget_cost= ".$amount[2]."
  				where project_id = ".$form['project_id'] ." and project_title ='03'";
  			$this->db->query($sql);								
		
		
		/** In Charge **/
        $sql = "select * from project_team 
                where project_id = ".$form['project_id']." and project_title='041'";
        $team_ass = $this->rst2Array($sql) ;
		
        if (count($team_ass) == 0){
        	 $sql = "insert into project_team ( project_id, employee_id, approval_id, project_title) 
      					values (".$form['project_id'].",0,0, '041')"; 
      			$this->db->query($sql);	
        }
        
  	$sql = "update project_team set 
  		budget_hour = ".$job['041_hour'].",
  		budget_days= ".$job['041_day'].",
  		budget_rate = ".$_POST['041_rate'].",
  		budget_cost= ".$amount[3]."
  		where project_id = ".$form['project_id'] ." and project_title ='041'";
  	$this->db->query($sql);		

			/**In Charge**/	
        
		/** Assistant **/
        $sql = "select * from project_team 
                where project_id = ".$form['project_id']." and project_title='042'";
        $team_ass = $this->rst2Array($sql) ;
		
        if (count($team_ass) == 0){
        	 $sql = "insert into project_team ( project_id, employee_id, approval_id, project_title) 
      					values (".$form['project_id'].",0,0, '042')"; 
      			$this->db->query($sql);	
        }
        
  	$sql = "update project_team set 
  		budget_hour = ".$job['042_hour'].",
  		budget_days= ".$job['042_day'].",
  		budget_rate = ".$_POST['042_rate'].",
  		budget_cost= ".$amount[4]."
  		where project_id = ".$form['project_id'] ." and project_title ='042'";
  	$this->db->query($sql);		

		/** Assistant **/	
		
	//total budget & cost
	$total_budget += ($amount[0] + $amount[1]+$amount[2]+$amount[3]+$amount[4]);
	$total_hour+= ($job['01_hour']+$job['02_hour']+$job['03_hour']+$job['041_hour']+$job['042_hour']);
		
		/** Outsource **/
		if($dept==TRUE){
			$sql = "select * from project_team 
					where project_id = ".$form['project_id']." and project_title='777'";
			$team_ass = $this->rst2Array($sql) ;
			
			if (count($team_ass) == 0){
				 $sql = "insert into project_team ( project_id, employee_id, approval_id, project_title) 
							values (".$form['project_id'].",0,0, '777')"; 
					$this->db->query($sql);	
			}
			
				$sql = "update project_team set 
						budget_hour = ".$job['777_hour'].",
						budget_days= ".$job['777_day'].",
						budget_rate = ".$_POST['777_rate'].",
						budget_cost= ".$amount[5]."
					where project_id = ".$form['project_id'] ." and project_title ='777'";
				$this->db->query($sql);	
			$total_budget += $amount[5];
			$total_hour += $job['777_hour'];
		}
		/** Outsource **/
			
		
  		}
		
		
		
	}
		if (isset($form['id-oth'])){
			if (!empty($form['id-oth'])) {
				if ( count($form['id-oth']) >0){
					  $oth_budget = 0;
					  $oth_hour =0;
					  foreach ($form['id-oth'] as $k=>$v) {
						if (strlen($form['other_budget_hour'][$k]) == 0) $form['other_budget_hour'][$k] =0;
						if (strlen($form['other_actual_hour'][$k]) == 0) $form['other_actual_hour'][$k] =0;
						if (strlen($form['other_actual_cost'][$k]) == 0) $form['other_actual_cost'][$k] =0;
						if (strlen($form['other_budget_cost'][$k]) == 0) $form['other_budget_cost'][$k] =0;
		  
						 //
						$sql = "update project_team set 
							budget_hour = ".$form['other_budget_hour'][$k].",
							actual_hour = ".$form['other_actual_hour'][$k].",
							actual_cost = ".$form['other_actual_cost'][$k].",
							budget_cost = ".$form['other_budget_cost'][$k]."
							where teamid = ".$form['id-oth'][$k];
					        $this->db->query($sql);
						$oth_budget+=$form['other_budget_cost'][$k];
						$oth_hour+=$form['other_budget_hour'][$k];
					}
					
					//update budget cost
					$total_budget+=$oth_budget;
					$total_hour+=$oth_hour;
				 }
				 
			}	 
		}
		
		/**Update Project Budget Hour */
		
		$sql = "update project set 
			budget_cost = ".$total_budget.",
			budget_hour = ".$total_hour."
			where project_id = ".$form['project_id'];
		$this->db->query($sql);
			
	}
	
	/*-------------------------------------------------------------------------------------*/
	public  function getClientOption() {
		$whereClause 	= "";
		//if ( $this->session->userdata('acl') == 2) $whereClause .='	and client_id in ( select client_id from client_department where manager_id = '.$this->session->userdata('manager_id').' )';
		//if ( $this->session->userdata('acl') == 3) $whereClause .='	and client_id in ( select client_id from client_department where department_id = '.$this->session->userdata('department_id').' )';
		
		$sql = "select client_id, client_name from client where 1=1 $whereClause ORDER BY client_name";
		return $this->rst2Array($sql) ;
	}


	/*-------------------------------------------------------------------------------------*/
	public  function getJobType() {
		$whereClause 	= "";
		//if ( $this->session->userdata('acl') == 2) $whereClause .='	and client_id in ( select client_id from client_department where manager_id = '.$this->session->userdata('manager_id').' )';
		//if ( $this->session->userdata('acl') == 3) $whereClause .='	and client_id in ( select client_id from client_department where department_id = '.$this->session->userdata('department_id').' )';
		
		$sql = "select * from job_type order by jobtype";
		return $this->rst2Array($sql) ;
	}



	/*-------------------------------------------------------------------------------------*/
	public  function getClientNo($client_id) {
		$sql = "select * from client where client_id = ". $client_id ;
		return $this->rst2Array($sql) ;
	}

	/*-------------------------------------------------------------------------------------*/
	public  function getJobtypeNo($jobtype_id) {
		$sql = "select * from job_type where jobtype_id = ". $jobtype_id ;
		return $this->rst2Array($sql) ;
	}



	//  getEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getEmployeeList($filter=null ) {
		$whereClause = "";

		if ( strlen( $filter ) > 0 && strtolower($filter)=='pic') $whereClause = " and project_title='01' ";
		if ( strlen( $filter ) > 0 && strtolower($filter)=='gc') $whereClause = " and project_title in ('02','01') ";
		if ( strlen( $filter ) > 0 && strtolower($filter)=='mic') $whereClause = " and project_title in( '03','02') ";
		if ( strlen( $filter ) > 0 && strtolower($filter)=='aic') $whereClause = " and (project_title='03' or  project_title='041' or project_title='042' or  project_title='043' or project_title='044' ) ";
		if ( strlen( $filter ) > 0 && strtolower($filter)=='ass') $whereClause = " and (project_title='03' or project_title='041' or project_title='042' or  project_title='043' or project_title='044' ) ";

/*
    versi sebelum 21 oct 
		if ( strlen( $filter ) > 0 && strtolower($filter)=='pic') $whereClause = " and project_title='01' ";
		if ( strlen( $filter ) > 0 && strtolower($filter)=='gc') $whereClause = " and project_title='02' ";
		if ( strlen( $filter ) > 0 && strtolower($filter)=='mic') $whereClause = " and project_title='03' ";
		if ( strlen( $filter ) > 0 && strtolower($filter)=='aic') $whereClause = " and (project_title='041' or project_title='042' or  project_title='043' or project_title='044' ) ";
		if ( strlen( $filter ) > 0 && strtolower($filter)=='ass') $whereClause = " and (project_title='041' or project_title='042' or  project_title='043' or project_title='044' ) ";
*/
/*
		$sql = "
			select a.employee_id, a.employeefirstname, a.employeemiddlename, a.employeelastname, a.name 
			from employee a
			where 1=1  $whereClause
			order by a.employeefirstname, a.employeemiddlename, a.employeelastname";
*/
		$sql = "
			select distinct a.employee_id, a.employeefirstname, a.employeemiddlename, a.employeelastname, a.name 
			from employee a left join sys_user  b on a.employee_id = b.employee_id
			where 1=1  and b.user_active = 1 $whereClause
			order by a.employeefirstname, a.employeemiddlename, a.employeelastname";

		return $this->rst2Array($sql) ;
	}
	

	//  getEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getAssistantList($project_id=null ) {

  
		$sql = "
			select a.employee_id, a.employeefirstname, a.employeemiddlename, a.employeelastname, a.name 
			from employee a inner join project_team b on a.employee_id = b.employee_id 
			where 1=1  and b.project_id = $project_id and b.project_title in ('042','043')
			order by a.employeefirstname, a.employeemiddlename, a.employeelastname";

		return $this->rst2Array($sql) ;
	}
	
	//  getEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getAICList($project_id=null ) {

  
		$sql = "
			select a.employee_id, a.employeefirstname, a.employeemiddlename, a.employeelastname, a.name 
			from employee a inner join project_team b on a.employee_id = b.employee_id 
			where 1=1  and b.project_id = $project_id and b.project_title in ('041')
			order by a.employeefirstname, a.employeemiddlename, a.employeelastname";

		return $this->rst2Array($sql) ;
	}
	
	//  getEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getOutsourceListBackup($project_id=null ) {

  
		/*$sql = "
			select a.employee_id, a.employeefirstname, a.employeemiddlename, a.employeelastname, a.name 
			from employee a inner join project_team b on a.employee_id = b.employee_id 
			where 1=1  and b.project_id = $project_id and b.project_title in ('777')
			order by a.employeefirstname, a.employeemiddlename, a.employeelastname";*/
		$sql = "select team_description from project_team b where b.project_id = $project_id and b.project_title in ('777')";	

		return $this->rst2Array($sql) ;
	}
	
	//  getEmployee
	/*-------------------------------------------------------------------------------------*/
	public function getOutsourceList($project_id = null ) {
	
	
		$sql = "
		select a.employee_id, a.employeefirstname, a.employeemiddlename, a.employeelastname, a.name
		from employee a inner join project_team b on a.employee_id = b.employee_id
		where 1=1  and b.project_id = $project_id and b.project_title in ('777')
		order by a.employeefirstname, a.employeemiddlename, a.employeelastname";
	
		return $this->rst2Array($sql) ;
	}

	public function getBugetJob($id) {
		$sql = "
			select
				sum(ifnull(01_hour,0)) 01_hour,
				sum(ifnull(02_hour,0)) 02_hour,
				sum(ifnull(03_hour,0)) 03_hour,
				sum(ifnull(041_hour,0)) 041_hour,
				sum(ifnull(042_hour,0)) 042_hour,
				sum(ifnull(043_hour,0)) 043_hour,
				sum(ifnull(044_hour,0)) 044_hour,
				sum(ifnull(777_hour,0)) 777_hour,
				CEILING((sum(ifnull(01_hour,0))/8)) 01_day,
				CEILING((sum(ifnull(02_hour,0))/8)) 02_day,
				CEILING((sum(ifnull(03_hour,0))/8)) 03_day,
				CEILING((sum(ifnull(041_hour,0))/8)) 041_day,
				CEILING((sum(ifnull(042_hour,0))/8)) 042_day,
				CEILING((sum(ifnull(043_hour,0))/8)) 043_day,
				CEILING((sum(ifnull(044_hour,0))/8)) 044_day,
				CEILING((sum(ifnull(777_hour,0))/8)) 777_day
			from project_job
			where project_id = $id";
		return $this->rst2Array($sql,10);
	}

	public function getBugetTotal($id) {
		if($this->session->userdata('department_id')==7) $user_arr = "'01','02','03','041','042','777'";
		else $user_arr = "'01','02','03','041','042'";
		
		$sql ="
			select a.project_title,
				ifnull(b.budget_hour,0) budget_hour,
				ifnull(b.budget_days,0) budget_days,
				ifnull(b.budget_rate,0) budget_rate,
				ifnull(b.budget_cost,0) budget_cost,
				ifnull(b.actual_hour,0) actual_hour,
				ifnull(b.actual_cost,0) actual_cost
			from
			(
			  select lookup_code project_title
			  from lookup
			  where lookup_group = 'project_title' and lookup_code in ($user_arr) 
			) a
			left join 
			(
			select teamid,budget_hour,budget_days,budget_rate, budget_cost,actual_hour, actual_cost, project_title
			from project_team where project_id = $id
			) b on a.project_title = b.project_title
			group by a.project_title
			order by a.project_title";
      			
		return $this->rst2Array($sql);
	}
	
	public function requestProject($id){
		$sql = "update project set project_approval=1 where project_id = $id";
		$this->db->query($sql);		
	}
	
	public function reviewProject($id){
	   //flagapproval
 		if (isset($_POST['flagapproval'])) {
			if (strlen($_POST['flagapproval']) > 0 ){
				if ($_POST['flagapproval'] === "2" ){
         		$sql = "update project set project_approval=2, 
         		        reviewdate = '".date('Y-m-d H:i:s')."', reviewuser = '".$this->session->userdata('employee_id')."' 
         		        where project_id	= $id";
         		$this->db->query($sql);		
				}

            if ($_POST['flagapproval'] === "3" ){
         		$sql = "update project set project_approval=3,  
         		         approvedate = '".date('Y-m-d H:i:s')."', approveuser = '".$this->session->userdata('employee_id')."' 
         		        where project_id	= $id";
         		$this->db->query($sql);		
				}
				
				
            if ($_POST['flagapproval'] === "4" ){
         		$sql = "update project set project_approval=4,  
         		         closedate= '".date('Y-m-d H:i:s')."', closeuser = '".$this->session->userdata('employee_id')."' 
         		        where project_id	= $id";
         		$this->db->query($sql);		
				}
			}
		}		


		/*$sql = "
			update project, 
			(
				select sum(budget_cost) acost, sum(budget_hour) ahour
				from project_team
				where project_id = $id
			) b set 
				budget_hour = b.ahour, 
				budget_cost = b.acost
			where project_id = $id";
		$this->db->query($sql);
		*/
		if (isset($_POST['unlock'])) {
			if (strlen($_POST['unlock']) > 0 ){
				if ($_POST['unlock'] === "1" ){
					$sql = "update project set project_approval=1 where project_id = $id";
					$this->db->query($sql);		
				}
			}
		}		
	}
	
	public function reviewProjectByID($id,$status){
		$sql = "update project set
				project_approval=$status, 
         		        reviewdate = '".date('Y-m-d H:i:s')."',
				reviewuser = '".$this->session->userdata('employee_id')."' 
         		        where project_id = $id";
         	$this->db->query($sql);
	
		/*$sql = "
			update project, 
			(
				select sum(budget_cost) acost, sum(budget_hour) ahour
				from project_team
				where project_id = $id
			) b set 
				budget_hour = b.ahour, 
				budget_cost = b.acost
			where project_id = $id";
		$this->db->query($sql);
		*/
		/*if ($status === "3" ){
			$sql = "update project
				set project_approval=1.
				
				where project_id = $id";
			$this->db->query($sql);		
		}*/
			
	}
	
	public function getProjectUser($acl){
        if ($acl=='pic') $whereClause  = " and project_title='01'";
		if ($acl=='gc')  $whereClause  = " and project_title in ('02','01')";
		if ($acl=='mic') $whereClause = "  and project_title in( '03','02')";
		if ($acl=='aic') $whereClause = "  and project_title in('03','041','042','043','044','441')";
		if ($acl=='ass') $whereClause = "  and project_title in('03','041','042','043','044','441')";
		if ($acl=='ot')  $whereClause = "  and project_title in('777') ";
        $sql = " SELECT e.employee_id,CONCAT(e.employeefirstname,' ',e.employeemiddlename,' ',e.employeelastname) as employeename ,e.department_id,CONCAT('- Group :',d.department) as department
                 FROM employee e 
                 INNER JOIN sys_user su ON su.employee_id=e.employee_id
				 INNER JOIN department d ON d.department_id = e.department_id
                 WHERE su.user_active=1 ".$whereClause."
                 ORDER BY CONCAT(e.employeefirstname,' ',e.employeemiddlename,' ',e.employeelastname) ASC ";
        return $this->rst2Array($sql);
    }
    
    /**
     * Patch For Edit Partner Update
     * - project Team
     * - project Update
    **/
    public function getProjectTeam($project,$level){
	$sql = "SELECT project_title,employee_id,project_id 
		FROM project_team
		WHERE project_id=$project AND project_title='$level';";
	return $this->rst2Array($sql);	
    }
    
    /**
     *Patch from Refresh Project auto error budget cost and hour
    **/
    public function getMyProject(){
	$sql = "
		SELECT p.project_id,p.`project_no`,p.`budget_hour`,p.`budget_cost`
		FROM project_team pt
		INNER JOIN project p ON p.`project_id` = pt.`project_id` 
		WHERE pt.`employee_id` = ".$this->session->userdata('employee_id')."
		GROUP BY p.`project_id`;
	";
	return $this->rst2Array($sql);	
    }
    
    public function getMyProjectTeam($project_id){
	$sql = "SELECT MAX(budget_hour) AS budget_hour,MAX(budget_cost) budget_cost
		FROM project_team
		WHERE project_id=".$project_id."
		GROUP BY project_title";
	return $this->rst2Array($sql);	
    }
    
    public function getUpdateBudget($id,$cost,$hour){
	$data = array (
		'budget_cost' => $cost,
		'budget_hour' => $hour
	);
	$this->db->where('project_id',$id);
	$this->db->update('project',$data);
    }
    
    
    public function getBudgetLevelTotal($project,$level){
	$sql = "SELECT MAX(budget_hour) as bhour,MAX(budget_cost) as bcost,MAX(budget_rate) as brate,CEIL((MAX(budget_hour)/8)) as bdays
		FROM project_team
		WHERE project_id=$project AND project_title='$level'
		GROUP BY project_id,project_title";
	return $this->rst2Array($sql,10);	
    }
    
    public function getActualCostEmployee($project_id,$employee_id){
	$sql = "SELECT SUM(hour) as xhour,SUM(cost) as xcost
		FROM timesheet
		WHERE timesheet_approval=2 AND project_id=$project_id AND employee_id=$employee_id";
	return $this->rst2Array($sql,10);
    }
    
    public function getProjectTeamLevelSave($project_id,$level,$user){
	$sql = "UPDATE project_team SET employee_id=$user,approval_id=$user WHERE project_id=$project_id AND project_title='$level' ";
	return $this->db->query($sql);
    }
    
    public function getSaveProjectTeamUser($project){
	if($project['employee_id']==0) return false;
	
	$data['project_id']  = $project['project_id'];
	$data['employee_id'] = $project['employee_id'];
	$data['approval_id'] = $project['approval_id'];
	$data['project_title'] = $project['employee_title'];
	$data['team_description'] = $project['team_description'];
	$data['budget_hour'] = $project['bhour'];
	$data['budget_cost'] = $project['bcost'];
	$data['budget_rate'] = $project['brate'];
	$data['budget_days'] = $project['bdays'];
	$data['actual_hour'] = $project['actual_hour'];
	$data['actual_cost'] = $project['actual_hour'];
	$project['sysdate'] = date('Y-m-d H:i:s');
	$project['sysuser'] = $this->session->userdata('employee_id');
	$this->db->insert('project_team',$data);
	
	//if($this->session->userdata('department_id')==7){
	$data2['project_note'] = $this->input->post('note');
	$this->db->where('project_id',$project['project_id']);
	$this->db->update('project',$data2);
	//}
    }
    
    public function getDeleteProjectTeam($project){
	$this->db->where('project_id',$project['project_id']);
	$this->db->where('project_title',$project['employee_title']);
	$this->db->where_not_in('project_title',array('051','052','053','054','055'));
	$this->db->delete('project_team');
    }
    
}
/* End of file mainModel.php */