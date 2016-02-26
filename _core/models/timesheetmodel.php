<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class timesheetModel extends CI_Model {

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
					$result	= $rows[0][$keys[0]];
					break;
				default:
					$result = $rows;
					break;
			}
		}
		return $result;
	}

	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getProject($filter=null, $limit=0, $offset=0) {
   	$whereClause 	= ' or ( a.project_id in ( select distinct project_id from project_team where employee_id = '.$this->session->userdata('employee_id').' ) and project_approval = 3 )';
		$selectClause	= $limit ? "a.*, b.*  " : "count(*) total";
		$limitClause	= $limit ? "order by a.year_end desc,a.project, b.client_name  limit $limit offset $offset" : "";
		if(isset($filter['project'])) $whereClause .= " and a.project like '%$filter[project]%'";
		if(isset($filter['client_name'])) $whereClause .= " and b.client_name like '%$filter[client_name]%'";
		if(isset($filter['project_no'])) $whereClause .= " and a.project_no like '%$filter[project_no]%'";
		$sql = "select $selectClause from project a 
				inner join client b on a.client_id =b.client_id 
				where  project_id = 1 $whereClause $limitClause";
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
	


		//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectPosting($filter=null, $limit=0, $offset=0) {
   	$whereClause 	= ' and ( a.project_id = 1 or (a.project_id in ( select distinct project_id from project_team where employee_id = '.$this->session->userdata('employee_id').' ) and a.project_approval = 3))';
		$selectClause	= $limit ? "a.*, b.*  " : "count(*) total";
		$limitClause	= $limit ? "order by a.project, b.client_name  limit $limit offset $offset" : "";
		if(isset($filter['project'])) $whereClause .= " and a.project like '%$filter[project]%'";
		if(isset($filter['client_name'])) $whereClause .= " and b.client_name like '%$filter[client_name]%'";
		if(isset($filter['project_no'])) $whereClause .= " and a.project_no like '%$filter[project_no]%'";
		$sql = "select $selectClause from project a 
				inner join client b on a.client_id =b.client_id 
				where 1=1  $whereClause ";
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
	
	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectAll($client_id) {
		$sql = "select * from project a 
				where a.client_id = $client_id and a.project_id <> 1  and project_approval = 3 
				      and a.project_id not in ( select distinct project_id from project_team where employee_id = '".$this->session->userdata('employee_id')."'  )
				order by project_no";
		return $this->rst2Array($sql) ;
	}
	
	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectByClient($client_id) {
		$sql = "select p.project_id,p.project_no 
				from project p
				inner join project_team pt on pt.project_id = p.project_id
				where p.client_id = ".($client_id ? $client_id : 0)."
				and pt.employee_id = ".$this->session->userdata('employee_id')."
				group by p.project_id 
				order by p.project_no";
		return $this->rst2Array($sql) ;
	}
	
	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getEmployeeProjectByApproval($project_id) {
		$sql = "select e.employee_id,CONCAT(e.EmployeeID,' ',e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as approval_name
				from project_team pt
				inner join employee e on e.employee_id = pt.employee_id
				where pt.project_id = ".($project_id ? $project_id : 0)." 
				group by pt.employee_id 
				order by e.EmployeeFirstName ASC";
		return $this->rst2Array($sql) ;
	}
	


	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getClientAll() {
		$sql = "select * from client order by client_name ";
		return $this->rst2Array($sql) ;
	}

	
	//  getTimesheetWaiting
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetWaiting() {
		/*
		$sql = "
			select a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				b.employeenickname approval, c.employeenickname  requestor, a.timesheet_approval
			from timesheet_status a
			left join employee b on a.employee_id = b.employee_id
			left join employee c on a.timesheet_approval_employee = c.employee_id
			where timesheet_approval = 1 
				and a.approval_id = ".$this->session->userdata('employee_id') ."
			order by a.year desc, a.week desc, drequest desc limit 0, 30";
		*/	
		/*		$sql = "
			select a.timesheet_status_id,a.week, a.year, a.drequest,
				a.dapproval,
				b.employeenickname approval, c.employeenickname  requestor, a.timesheet_approval
			from timesheet_status a
			left join employee b on a.employee_id = b.employee_id
			left join employee c on a.timesheet_approval_employee = c.employee_id
			where timesheet_approval = 1 
				and a.approval_id = ".$this->session->userdata('employee_id') ."
			order by a.year desc, a.week desc, drequest desc limit 0, 30";
			
		*/
		$sql = "
			select a.timesheet_status_id,a.week, a.year, a.drequest,
				a.dapproval,
				'' approval, '' requestor, 
				y.employeenickname approval, x.employeenickname requestor,
				a.timesheet_approval
			from timesheet_status a
			inner join employee x on a.employee_id = x.employee_id
			inner join employee y on a.approval_id = y.employee_id
			where timesheet_approval = 1 
				and a.approval_id = '".$this->session->userdata('employee_id') ."'
			order by a.year desc, a.week desc, drequest desc limit 0, 30";
		
		

		//echo $sql;
		return $this->rst2Array($sql);
	}

	//  getTimesheetRequest
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetRequest() {
		/*
		$sql = "
			select a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				b.employeenickname approval, c.employeenickname  requestor, a.timesheet_approval
			from timesheet_status a
			left join employee b on a.employee_id = b.employee_id
			left join employee c on a.timesheet_approval_employee = c.employee_id
			where timesheet_approval = 1 
				and a.employee_id = ".$this->session->userdata('employee_id') ."
			order by a.year desc, a.week desc, drequest desc";
			//order by drequest desc";
	*/
	/*
			$sql = "
			select a.timesheet_status_id,a.week, a.year, a.drequest,
				a.dapproval,
				b.employeenickname approval, c.employeenickname  requestor, a.timesheet_approval
			from timesheet_status a
			left join employee b on a.employee_id = b.employee_id
			left join employee c on a.timesheet_approval_employee = c.employee_id
			where timesheet_approval = 1 
				and a.employee_id = ".$this->session->userdata('employee_id') ."
			order by a.year desc, a.week desc, drequest desc limit 0, 30";
	*/
		$sql = "
			select a.timesheet_status_id,a.week, a.year, a.drequest,
				a.dapproval,
				''  approval, '' requestor, 
				y.employeenickname approval, x.employeenickname requestor,
				a.timesheet_approval
			from timesheet_status a
			inner join employee x on a.employee_id = x.employee_id
			inner join employee y on a.approval_id = y.employee_id
			where timesheet_approval = 1 
				and a.employee_id = '".$this->session->userdata('employee_id') ."'
			order by a.year desc, a.week desc, drequest desc limit 0, 30";
		
		return $this->rst2Array($sql);
	}

	//  getTimesheetApproved
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetActive() {			
		$sql = "
			select a.timesheet_status_id, a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				'' approval, '' requestor, 
				a.timesheet_approval
			from timesheet_status a
			where timesheet_approval is null
				and a.employee_id = '".$this->session->userdata('employee_id') ."'
			order by a.year desc, a.week desc, drequest desc limit 0, 30";
		return $this->rst2Array($sql);
	}
	
	//  getTimesheetApproved
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetReturn() {			
		$sql = "
			select a.timesheet_status_id, a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				'' approval, '' requestor, 
				a.timesheet_approval
			from timesheet_status a
			where timesheet_approval = 3
				and a.employee_id = '".$this->session->userdata('employee_id') ."'
			order by a.year desc, a.week desc, drequest desc limit 0, 30";
		return $this->rst2Array($sql);
	}
	
	//  getTimesheetApproved
	/*-------------------------------------------------------------------------------------*/	
	public function getTimesheetDone() {
		/*
		$sql = "
			select a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				y.employeenickname approval, x.employeenickname requestor, 
				a.timesheet_approval
			from timesheet_status a
			inner join employee x on a.employee_id = x.employee_id
			inner join employee y on a.approval_id = y.employee_id
			where timesheet_approval=2 
				and a.employee_id = '".$this->session->userdata('employee_id') ."'
			order by a.year desc, a.week desc, drequest desc";
		*/	
		/*
		$sql = "
			select a.timesheet_status_id,a.week, a.year, a.drequest,
				a.dapproval, 
				y.employeenickname approval, x.employeenickname requestor, 
				a.timesheet_approval
			from timesheet_status a
			inner join employee x on a.employee_id = x.employee_id
			inner join employee y on a.approval_id = y.employee_id
			where timesheet_approval=2 
				and a.employee_id = '".$this->session->userdata('employee_id') ."'
			order by a.year desc, a.week desc, drequest desc limit 0, 30";
		*/
		$sql = "
			select a.timesheet_status_id,a.week, a.year, a.drequest,
				a.dapproval, 
				'' approval, '' requestor, 
				y.employeenickname approval, x.employeenickname requestor,
				a.timesheet_approval
			from timesheet_status a
			inner join employee x on a.employee_id = x.employee_id
			inner join employee y on a.approval_id = y.employee_id
			where timesheet_approval=2 
				and a.employee_id = '".$this->session->userdata('employee_id') ."'
			order by a.dapproval desc limit 0, 30";
		//echo $sql;
		return $this->rst2Array($sql);
	}
	
	/*-------------------------------------------------------------------------------------*/
	public  function getTimesheetActiveStatus($id) {
		$sql = "
			select a.*,b.
				project_no, b.project,c.client_no, c.client_name, d.job, 
				e.employeenickname, x.drequest 
			from timesheet_status x
			inner join timesheet a on a.timesheet_status_id = x.timesheet_status_id
			left join project b on a.project_id = b.project_id 
			left join client c on b.client_id = c.client_id 
			left join job d on a.job_id = d.job_id
			left join employee e on a.employee_id = e.employee_id
			where x.timesheet_status_id='$id' 
				and x.employee_id = ".$this->session->userdata('employee_id')."
			order by a.project_id, a.year desc, a.week desc, a.timesheetdate";
		return $this->rst2Array($sql);
	}

	public function getTimesheetActiveStatusX($id){
		$sql = "
			SELECT week, year, employee_id
			FROM timesheet_status
			where timesheet_status_id = $id ";
		$xtmp = $this->rst2Array($sql, 10);

		
		$sql = "
			SELECT 
				c.timesheetid, c.timesheet_approval, c.timesheetdate, 
				case 
				when a.personalcalendardate is null then c.timesheetdate
				else a.personalcalendardate
				end personalcalendardate, 
				case
				when a.totalhour is null then 0
				else a.totalhour
				end totalhour, 
				d.project, d.project_no, 
				e.client_name, b.employeenickname, c.week, c.year, c.hour,c.overtime,  c.cost,c.notes, 
				f.job_no, f.job
			FROM timesheet c
			inner join employee b on b.employee_id = c.employee_id
			left join personalcalendar a on a.fingerprintid = b.fingerprintid and a.personalcalendardate = c.timesheetdate
			inner join project d on d.project_id = c.project_id 
			inner join client e on e.client_id = d.client_id 
			inner join job f on f.job_id = c.job_id
			where c.timesheet_approval <> 3 AND c.timesheet_status_id in
			(
				SELECT distinct timesheet_status_id
				FROM timesheet_status
				where
				week = $xtmp[week] and
				year = $xtmp[year] and
				employee_id = $xtmp[employee_id] and 
				drequest is not null
			)			
			order by 3";
		return $this->rst2Array($sql);
	}

	public function getTimesheetCalenderDetail(){
		$sql ="SELECT * from timesheet where timesheet_status_id=14 and b.employee_id ='801'";
		return $this->rst2Array($sql);
	}

	//  getTimesheetDetail
	/*-------------------------------------------------------------------------------------*/	
	public  function getTimesheetDetail($id) {
		//$sql = "select * from timesheet where timesheetid ='$id'";
		$sql = "select timesheetid, project_id, job_id, week, year, timesheetdate, hour, overtime,
										cost, transport_type, transport_cost, notes
					 from timesheet where timesheetid ='$id'";
		return $this->rst2Array($sql, 10);
	}

	//  getProjectDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectDetail($project_id) {
		$sql = "
			select a.*,b.client_no, b.client_name  
			from project a 
			left join client b on a.client_id=b.client_id 
			where project_id ='$project_id'";
		return $this->rst2Array($sql, 10);
	}


	//  getTimesheetProject
	/*-------------------------------------------------------------------------------------*/
	public  function getTimesheetProject($project_id,$datestart='',$dateend='') {
		$sql = "
			select a.timesheetid id, a.project_id, c.project_no, a.job_id, a.timesheet_approval, 
				a.cost, a.employee_id,a.week, a.year, a.hour, a.overtime,CONCAT(a.client_name_description,' - ', a.notes) as notes, a.timesheetdate,a.client_name_description, 
				b.job_no, b.job , d.lookup_label transport_type, a.transport_cost,DATE_FORMAT(a.sysdate,'%d/%m/%Y %H:%i:%s') as sysdate
			from timesheet a 
			inner join job b on a.job_id = b.job_id 
			left join project c on a.project_id = c.project_id
			left join lookup d on a.transport_type = d.lookup_code and d.lookup_group = 'tranport'
			where a.project_id ='$project_id' 
				and a.employee_id = '".$this->session->userdata('employee_id') ."' 
				and a.timesheetdate>= STR_TO_DATE('".$datestart."', '%d/%m/%Y')
                and a.timesheetdate<= STR_TO_DATE('".$dateend."', '%d/%m/%Y') 
			order by a.timesheetdate";
		//echo $sql;
		return $this->rst2Array($sql);
	}

	//  getProjectJob
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectJob($project_id) {
		$sql = "
			select a.*, b.job_no, b.job  
			from project_job a 
			inner join job b on a.job_id= b.job_id 
			where a.project_id = '$project_id' 
			and b.active = 1
			order by b.job_no asc";
		return $this->rst2Array($sql);
	}

	//  checkTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function checkTimesheetWeek($week, $year) {
		$sql = "
			select * 
			from timesheet_status a
			where timesheet_approval is null 
				and a.employee_id='".$this->session->userdata('employee_id') ."' 
				and a.week ='$week' and a.year='$year' ";
		return $this->rst2Array($sql, 10);
	}
	
	//  checkTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function checkTimesheet($id) {
		$sql = "
			select * 
			from timesheet
			where timesheetid=".$id;
		return $this->rst2Array($sql, 10);
	}
	
	//  checkTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function checkAllTimesheet($id) {
		$sql = "
			select * 
			from timesheet
			where timesheet_status_id=".$id;
		return $this->rst2Array($sql);
	}
	
	//  checkTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function getAllTimesheetByStatus($id) {
		$sql = "
			select *
			from timesheet
			where timesheet_status_id=".$id;
		return $this->rst2Array($sql);
	}
	
	//  checkTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function getTimesheetById($id) {
		$sql = "
			select *
			from timesheet
			where timesheetid=".$id;
		return $this->rst2Array($sql,11);
	}
	
	//  checkLeaveTimesheet
	/*-------------------------------------------------------------------------------------*/
	public function checkLeaveTimesheet($date) {
		$sql = "
			select *
			from leaves
			where leave_range LIKE '%".$date."%'
			and leave_status < 10
			and employee_id=".$this->session->userdata('employee_id');
		return $this->rst2Array($sql, 10);
	}
	
	//  checkLeaveTimesheet
	/*-------------------------------------------------------------------------------------*/
	public function sumEmployeeLeave($employee_id) {
		//Leaves
		$sql = "
			select sum(leave_total) as total
			from leaves
			where leave_status = 1 and employee_id=".$employee_id;
		$ldata =  $this->rst2Array($sql, 10);
		
		$leave_total = 0;
		if($ldata) {
			$leave_total+=$ldata["total"]; 
		} 
		
		
		//Leaves
		$sql = "
			select sum(leave_balance_total) as total
			from leave_balance where employee_id =".$employee_id;
		$bdata =  $this->rst2Array($sql, 10);
		
		$balance_total = 0;
		if($bdata) {
			$balance_total+=$bdata["total"];
		}
		
		$data = array (
			'balance' => $balance_total,
			'leave' => $leave_total	
		);
		
		return $data;
		
	}

	//  insertTimesheetWeekly
	/*-------------------------------------------------------------------------------------*/
	public function insertTimesheetWeekly( $week, $year)  {
		$sql = "insert into timesheet_status (week, year, employee_id, sysdate, sysuser)
			values ('". $week ."', '". $year ."','".$this->session->userdata('employee_id')."',".date('Y-m-d').",'".$this->session->userdata('employee_id')."')"; 
		$this->db->query($sql);			
		return  $this->db->insert_id();;
	}

	//  saveTimesheet
	/*-------------------------------------------------------------------------------------*/
	public function saveTimesheet($form, $timesheet_status_id) {
		$timesheetdate= preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $form['timesheetdate']);
		$note = $this->db->escape_str($form['notes']);
		$client_name_descripiton = $this->db->escape_str($form['client_name_description']);
		
        if($form['transport_type']==3)
            $form['cost']= 0;
            
                   
        if ( $form['id'] === '0' ) {
			$sql = "insert into timesheet( timesheet_status_id, project_id, employee_id, week, year, job_id,client_name_description, notes, timesheetdate,hour, overtime, cost, transport_type,  sysdate,sysuser) 
					values ($timesheet_status_id,'$form[project_id]','".$this->session->userdata('employee_id')."', '$form[week]' , '$form[year]',
					'$form[job_id]','$client_name_descripiton', '".$note."', '$timesheetdate', $form[hour], $form[overtime],  $form[cost], $form[transport_type],
					'".date('Y-m-d H:i:s')."','".$this->session->userdata('employee_id')."')"; 
			
			$is_leave = false;
			$leave_type = 0;
			
			/**
			 * 1 = Cuti Bersama
			 * 2 = Tahunan
			 * 3 = Tambahan
			 * 4 = Khusus (Melahirkan)
			 * 5 = Izin CUti
			 */
			
			if($form['job_id'] == 500) {
				$is_leave = true;
				$leave_type = 1;
			}else if($form['job_id'] == 713) {
				$is_leave = true;
				$leave_type = 5;
			}else if($form['job_id'] == 10) {
				$is_leave = true;
				$leave_type = 4;
			}else if($form['job_id'] == 11) {
				$is_leave = true;
				$leave_type = 2;
			}else if($form['job_id'] == 12) {
				$is_leave = true;
				$leave_type = 2;
			}
			
			if($is_leave) {
				$data = array (
					'employee_id' => $this->session->userdata('employee_id'),
					'leave_type' => $leave_type,
					'leave_date' => date('Y-m-d'),
					'leave_date_from' => $timesheetdate ,
					'leave_date_to' => $timesheetdate,
					'leave_total' => 1,
					'leave_range' => $form['timesheetdate'].',',
					'leave_description' => $note,
					'leave_address' => '',
					'leave_created_by' => $this->session->userdata('employee_id'),
					'leave_created_date' => date('Y-m-d H:i:s'),
					'leave_app_user1' => 0,
					'leave_app_user1_status' => 1,
					'leave_app_user2' => 0,
					'leave_app_user2_status' => 1,
					'leave_app_hrd' => 0,
					'leave_app_hrd_status' => 1,
					'leave_app_pic' => 0,
					'leave_app_pic_status' => 1,
					'leave_request' => 6,
					'leave_status' => 5, //request 
					'leave_source' => 1
				);
				$id = $this->db->insert_id('leaves',$data);
				
				//save log
				$log = array (
					'leave_id' => $id,
					'leave_log_date' => date('Y-m-d H:i:s'),
					'leave_log_status' => 5,
					'leave_log_title' =>'By Timesheet',
					'leave_log_desc' => '',
					'leave_log_approval_id' => '',
					'leave_log_approval_name' => '',	
				);
				$this->db->insert('leave_log',$log);
			}
			
		}
		else {
			$sql = "update timesheet set 
				project_id 		= '$form[project_id]',
				week			= '$form[week]',
				year			= '$form[year]',
				employee_id 	= ".$this->session->userdata('employee_id') .",
				job_id			= '$form[job_id]', 
				notes			= '$form[notes]', 
				timesheetdate	= '$timesheetdate', 
				notes			= '".$note."', 
				transport_type  = '$form[transport_type]', 
				hour			= $form[hour],
				overtime		= $form[overtime],
				cost			= $form[cost],
				sysdate			= '".date('Y-m-d H:i:s')."',
				sysuser			= '".$this->session->userdata('employee_id')."'
				where timesheetid	= $form[id]";
			
			//remove if the same date & employee
			$this->db->where('leave_date_from',$timesheetdate);
			$this->db->where('leave_date_to',$timesheetdate);
			$this->db->where('employee_id',$this->session->userdata('employee_id'));
			$this->db->delete('leaves');
			
			if($form['job_id'] == 500) {
				$is_leave = true;
				$leave_type = 1;
			}else if($form['job_id'] == 713) {
				$is_leave = true;
				$leave_type = 5;
			}else if($form['job_id'] == 10) {
				$is_leave = true;
				$leave_type = 4;
			}else if($form['job_id'] == 11) {
				$is_leave = true;
				$leave_type = 2;
			}else if($form['job_id'] == 12) {
				$is_leave = true;
				$leave_type = 2;
			}
				
			if($is_leave) {
				$data = array (
						'employee_id' => $this->session->userdata('employee_id'),
						'leave_type' => $leave_type,
						'leave_date' => date('Y-m-d'),
						'leave_date_from' => $timesheetdate ,
						'leave_date_to' => $timesheetdate,
						'leave_total' => 1,
						'leave_range' => $form['timesheetdate'].',',
						'leave_description' => $note,
						'leave_address' => '',
						'leave_created_by' => $this->session->userdata('employee_id'),
						'leave_created_date' => date('Y-m-d H:i:s'),
						'leave_app_user1' => 0,
						'leave_app_user1_status' => 1,
						'leave_app_user2' => 0,
						'leave_app_user2_status' => 1,
						'leave_app_hrd' => 0,
						'leave_app_hrd_status' => 1,
						'leave_app_pic' => 0,
						'leave_app_pic_status' => 1,
						'leave_request' => 6,
						'leave_status' => 5, //request
						'leave_source' => 1
				);
				$id = $this->db->insert_id('leaves',$data);
			
				//save log
				$log = array (
						'leave_id' => $id,
						'leave_log_date' => date('Y-m-d H:i:s'),
						'leave_log_status' => 5,
						'leave_log_title' =>'By Timesheet',
						'leave_log_desc' => '',
						'leave_log_approval_id' => '',
						'leave_log_approval_name' => '',
				);
				$this->db->insert('leave_log',$log);
			}
			
		}
		
		//updat timesheet
		$this->db->query($sql);		
		
		if ( $form['id'] === '0' ) {
			$id = $this->db->insert_id();
		} 
		else {
			$id = $form['id'];	
		}
	}
	
	function deleteTimeSheet($id) {
			$sql = "delete from timesheet where timesheetid = '".$id."' ";
			$timesheet = $this->checkTimesheet($id);
			if($timesheet)
			{
				if(($timesheet['job_id']==10) || ($timesheet['job_id']==11) || ($timesheet['job_id']==12))
				{
					//$sql = "DELETE FROM leaves WHERE (date_from)"
					//$this->db->query($sql);	
					$this->db->where('leave_date_from',$timesheet['timesheetdate']);
					$this->db->where('leave_date_to',$timesheet['timesheetdate']);
					$this->db->where('leave_status',6);
					$this->db->where('leave_request',6);
					$this->db->where('employee_id',$this->session->userdata('employee_id'));
					$this->db->delete('leaves');
				}
			}
			//echo $sql;
			$this->db->query($sql);
			
	}
	
	function TimeSheetDelete($id) {
			$sql = "delete from timesheet where timesheetid = '".$id."' ";
			//echo $sql;
			$this->db->query($sql);
			/*
			if ($this->db->affected_rows() !== 1)
			{
				//return FALSE;
			}
			else
			{
				$sql = "
					delete timesheet_status
					from timesheet_status
					left join timesheet on 
						timesheet_status.timesheet_status_id = timesheet.timesheet_status_id
					where timesheet.timesheet_status_id is null
					";
				//echo $sql;
				$this->db->query($sql);
			}
			*/
	}
	
	//  saveTimesheetRequest
	/*-------------------------------------------------------------------------------------*/	
	public  function saveTimesheetRequest($id) {
		$xappr = '02';
		if ( $this->session->userdata('acl') > "03" ) { $xappr = '03'; }
		if ( $this->session->userdata('acl') === "02" ) { $xappr = '01'; }

		
		$sql = "update timesheet_status, 
					( select approval_id from employee where employee_id = '". $this->session->userdata('employee_id') ."' ) b 
					set timesheet_status.timesheet_approval = 1, 
					timesheet_status.drequest = '".date('Y-m-d H:i:s')."', 
					timesheet_status.approval_id = b.approval_id 
				where timesheet_status_id = '$id'";
				
		/* end here */

		
		$this->db->query($sql);		
		
		$sql = "update timesheet 
			set timesheet_approval = 1 
			where timesheet_status_id ='$id'";
		$this->db->query($sql);		
	}
	
	//  saveTimesheetRequest
	/*-------------------------------------------------------------------------------------*/	
	public function saveApproveTimesheet($id) {
		$sql = "update timesheet_status
			set timesheet_approval = 2,
			dapproval = '".date('Y-m-d H:i:s')."'
			where timesheet_status_id = '$id'";
		$this->db->query($sql);		

		$sql = "update timesheet
			set timesheet_approval = 2
			where timesheet_status_id = '$id'";
		$this->db->query($sql);	
		
		/**
		 * Synch To Leave
		 * timesheet to leave
		*/
		$timesheet = $this->getAllTimesheetByStatus($id);
		if($timesheet) {
			foreach($timesheet as $row) {
				/**
				 * 1 = Cuti Bersama
				 * 2 = Tahunan
				 * 3 = Tambahan
				 * 4 = Khusus (Melahirkan)
				 * 5 = Izin CUti
				 */
				$is_leave = false;
				$leave_type = 0;
				$form["job_id"] = $row["job_id"];
					
				if($form['job_id'] == 500) {
					$is_leave = true;
					$leave_type = 1;
				}else if($form['job_id'] == 713) {
					$is_leave = true;
					$leave_type = 5;
				}else if($form['job_id'] == 10) {
					$is_leave = true;
					$leave_type = 4;
				}else if($form['job_id'] == 11) {
					$is_leave = true;
					$leave_type = 2;
				}else if($form['job_id'] == 12) {
					$is_leave = true;
					$leave_type = 2;
				}
					
				if($is_leave) {
					$data = array('leave_status' => 1);
					$this->db->where('leave_source',1);
					$this->db->where('employee_id',$row["employee_id"]);
					$this->db->where('leave_date_from',$row["timesheetdate"]);
					$this->db->where('leave_date_to',$row["timesheetdate"]);
					$update = $this->db->update('leaves',$data);
					
					if($update) {
						//calculate employee leave
						$sum = $this->sumEmployeeLeave($row["employee_id"]);
						$balance_total = $sum["balance"];
						$leave_total = $sum["leave"];
						/** Update Employee Total **/
						$eval = array(
							'EmployeeLeaveTotal' => $balance_total,
							'EmployeeLeaveUse' => $leave_total	
						);
						$this->db->where('employee_id',$row["employee_id"]);
						$this->db->update('employee',$eval);
					}
				}	
			}
		}
		
	
		if ( isset( $_POST['return']) ) {
			if ( count($_POST['return']) > 0 ) {
				foreach ($_POST['return'] as $k=>$v) {
					$sql = "
						update timesheet set timesheet_approval = 3
						where timesheetid = ".$_POST['return'][$k]; 
					$this->db->query($sql);		
					
					$tm = $this->getTimesheetById($_POST['return'][$k]);
					if($tm) {
						$eval["leave_status"] = 0;
						$this->db->where('leave_source',1);
						$this->db->where('employee_id',$tm["employee_id"]);
						$this->db->where('leave_date_from',$tm["timesheetdate"]);
						$this->db->where('leave_date_to',$tm["timesheetdate"]);
						$update = $this->db->update('leaves',$eval);
						
						if($update) {
						//calculate employee leave
							$sum = $this->sumEmployeeLeave($tm["employee_id"]);
							$balance_total = $sum["balance"];
							$leave_total = $sum["leave"];
								
							/** Update Employee Total **/
							$eval = array(
									'EmployeeLeaveTotal' => $balance_total,
									'EmployeeLeaveUse' => $leave_total
							);
							$this->db->where('employee_id',$tm["employee_id"]);
							$this->db->update('employee',$eval);
						}
						
					}
					
				}
			}
		}
		
		// Project Team
		$sql = "
			update project_team x,
			(
				select
					a.project_id, b.project_title, 
					sum(a.hour) ahour, sum(a.cost) acost
				from timesheet a
				left join project_team b on a.project_id = b.project_id 
					and a.employee_id = b.employee_id
				where timesheet_approval = 2 and timesheet_status_id = $id
				group by a.project_id, b.project_title
			) c
			set
				x.actual_hour = c.ahour,
				x.actual_cost = c.acost
			where x.project_id = c.project_id
				and x.project_title = c.project_title";
		$this->db->query($sql);		

		// Project Job
		$sql = "
			select 
				a.project_id, b.project_title, a.job_id, 
				sum(a.hour) ahour, sum(a.cost) acost
			from timesheet a
			left join project_team b on a.project_id = b.project_id 
				and a.employee_id = b.employee_id
			where timesheet_approval = 2 and timesheet_status_id = $id
			group by a.project_id, b.project_title, a.job_id";
		$data	= $this->db->query($sql);
		if($data->num_rows() > 0) {
			$rows = $data->result_array();
			if ( count($rows ) > 0) {
				foreach ($rows as $k=>$v) {
				   if (strlen($v['project_title']) > 0 ) {
					   if (strtolower( substr( $v['job_id'],0,3)) != "999"){
		   					$sql = "
		   						update project_job 
		   						set  ".$v['project_title']."_hour_act = ".$v['ahour']."
		   						where project_id = ".$v['project_id']."
		   							and job_id = ".$v['job_id'];
		   					$this->db->query($sql);		
	   					}
   					}
				}			
			}
		}				
		
		// Project
		$sql="
			update project,
			(
				select project_id, sum(hour) ahour, sum(cost) acost
				from timesheet 
				where timesheet_approval = 2 and LEFT('job_id', 3) <>  '999'
				group by project_id 
			) b
			set
				hour = b.ahour,
				cost = b.acost
			where project.project_id = b.project_id";
		$this->db->query($sql);		
	}
	
	function getJob($project_id) {
		$tmp = "<option value=''>- Pilihan -</option>";
		$sql="select a.job_id, b.job_no , b.job
				from project_job a 
				left join job b on a.job_id = b.job_id 
				where a.project_id = $project_id order by b.job_no asc";
		$data = $this->rst2Array($sql);			
		if ($data) {
			foreach ( $data as $k =>$v ) {
				$tmp .= "<option value=$v[job_id]>$v[job_no] - $v[job] </option>";	
			}	
		}
		
		$sql ="select a.project_no,a.project, a.contract_no, b.client_name,b.client_no,
			project, year_end, start_date, finish_date, budget_hour, hour, 
			budget_cost, cost  
			from project a
			left join client b on a.client_id = b.client_id
			where project_id = $project_id";
		$data = $this->rst2Array($sql, 10);
		
		if ($data) {
			$tmp .= "|
			 	<tr>
					<td class=label>Project Number : </td>
					<td>".$data['project_no']."</td>
				</td>
				<tr>
					<td class=label>Project Name : </td>
					<td>".$data['project']."</td>
				</tr>
				<tr>
					<td class=label>Client : </td>
					<td>".$data['client_no']." / ".$data['client_name']."</td>
				</td>			
				<tr>
					<td class=label>Year End: </td>
					<td>".$data['year_end']." </td>
				</tr>
				<tr>
					<td class=label>Start Date: </td>
					<td>".$data['start_date']." </td>
				</tr>
				<tr>
					<td class=label>Finish Date: </td>
					<td>".$data['finish_date']." </td>
				</tr>
				<tr>
					<td class=label>Budget Hour: </td>
					<td>".number_format($data['budget_hour'])." </td>
				</tr>
				<tr>
					<td class=label>Actual Hour: </td>
					<td>".number_format($data['hour'])."</td>
				</tr>
				<tr>
					<td class=label>Budget Cost: </td>
					<td>".number_format($data['budget_cost'])." </td>
				</tr>
				<tr>
					<td class=label>Actual Cost: </td>
					<td>".number_format($data['cost'])." </td>
				</tr>
			";
		}
		return $tmp;
	}

  function tranport(){
    $sql = " select * from lookup where lookup_group = 'tranport' order by lookup_label"; 
 		return $this->rst2Array($sql); 
  }
  
  //  getTimesheetByEmployee add by ram 01-2010
  /*-------------------------------------------------------------------------------------*/	
	function getTimesheetByEmployee($data) {
		$sql = "select b.project,DATE_FORMAT(a.timesheetdate, '%d/%m/%Y') timesheetdate,
		 DATE_FORMAT(a.timesheetdate, '%W') hari,
		 a.hour,a.overtime,a.transport_cost 
		 from timesheet a
		 left join project b on a.project_id = b.project_id 
		 where a.employee_id='".$data['employee_id']."'
     and timesheetdate>= STR_TO_DATE('".$data['date_from']."', '%d/%m/%Y')
     and timesheetdate<= STR_TO_DATE('".$data['date_to']."', '%d/%m/%Y') 
     and timesheet_approval=2
     order by a.timesheetdate";
			//order by drequest desc";
		return $this->rst2Array($sql);
	}
	
	//  getProject
	/*-------------------------------------------------------------------------------------*/
	function getAllowance($filter=null, $limit=100, $offset=0) {
		$sql = "
			select  a.id,DATE_FORMAT(date_from,'%d/%m/%Y') as date_from,DATE_FORMAT(date_to,'%d/%m/%Y') as date_to,
			c.client_name,p.project_no,total_day,total_employee,DATE_FORMAT(date_realization,'%d/%m/%Y') as date_realization,
			CONCAT(e.EmployeeFirstName,' ',EmployeeLastName) as approval_name,	
			if(date_approved!='0000-00-00',DATE_FORMAT(date_from,'%d/%m/%Y'),'-') as date_approved,total	
			from allowances a
			inner join project p on p.project_id = a.project_id
			inner join project_team pt on pt.project_id = p.project_id	
			inner join client c on c.client_id = p.client_id 
			inner join employee e on e.employee_id = a.approval_id	
			where pt.employee_id = ".$this->session->userdata('employee_id')."	
		";
		if(isset($filter['client_name']))
			$sql.=" AND client_name LIKE '%".$filter['client_name']."%' ";
		if(isset($filter['project_no']))
			$sql.=" AND project_no LIKE '%".$filter['project_no']."%' ";
		
		$sql.= " group by a.id order by date_from desc limit ".$offset.",".$limit." ";
		
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
	
	//  getAllowanceDetail
	/*-------------------------------------------------------------------------------------*/
	function getAllowanceDetail($id) {
		$sql = "
			select id,p.client_id,a.project_id,a.approval_id,DATE_FORMAT(date_from,'%d/%m/%Y') as date_from,
			DATE_FORMAT(date_from,'%d/%m/%Y') as date_to,total_employee,total_day,total,
			DATE_FORMAT(date_realization,'%d/%m/%Y') as date_realization,created_by,
			if(date_approved!='0000-00-00',DATE_FORMAT(date_from,'%d/%m/%Y'),'') as date_approved
			from allowances a 
			inner join project p on p.project_id = a.project_id	
			where id = ".$id;
		return $this->rst2Array($sql, 10);
	}
	
	/** 
	 * Get List All Client
	 *  
	 */
	
	function clientListDropdown($value,$id) {
		$sql = "
			select * from client c
			inner join project p on p.client_id = c.client_id
			inner join project_team pt on pt.project_id = p.project_id
			where pt.employee_id = ".$this->session->userdata('employee_id')."
			group by c.client_id
			order by c.client_name ASC		
		";
		$query = $this->rst2Array($sql);
		$lists = [];
		if($query) {
			foreach($query as $key => $row) {
				$lists[$row[$id]] = $row[$value];
			}
		}
		return $lists;
	}
	
	function isHoliday($date) {
		$sql = "
			select * from holiday
			where holiday_date = '".$date."'
		";
		$q = $this->rst2Array($sql,11);
		if(count($q)) 
			return true;
		else
			return false;
	}
	
}

