<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class hrdModel extends CI_Model {

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

	

	//  getOvertimeWaiting
	/*-------------------------------------------------------------------------------------*/	
	public function getOvertimeWaiting() {
		$sql = "
			select a.overtime_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				b.employeenickname approval, a.overtime_approval,sum(ifnull(overtime,0)) overtime
			from overtime_status a
			inner join overtime e on a.overtime_status_id = e.overtime_status_id 
			left join employee b on a.employee_id = b.employee_id
			where a.overtime_approval = 1 
			group by a.overtime_status_id
			order by b.employeenickname, a.year desc, a.week";
						//order by drequest desc";

		return $this->rst2Array($sql);
	}
	
	//  getOvertimeWaiting
	/*-------------------------------------------------------------------------------------*/	
	public function getOvertimeApproved() {
		$sql = "
			select a.overtime_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				b.employeenickname requestor, a.overtime_approval,sum(ifnull(overtime,0)) overtime,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				y.employeenickname approval 
			from overtime_status a
			inner join overtime e on a.overtime_status_id = e.overtime_status_id 
			left join employee b on a.employee_id = b.employee_id
			left join employee y on a.approval_id = y.employee_id
			where a.overtime_approval = 2
			group by a.overtime_status_id
			order by b.employeenickname, a.year desc, a.week";

			//order by drequest desc";
		return $this->rst2Array($sql);
	}
	
	//  getOvertimeDone
	/*-------------------------------------------------------------------------------------*/	
	public function getOvertimeDone() {
		$sql = "
			select a.overtime_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				y.employeenickname approval, x.employeenickname requestor, 
				a.overtime_approval
			from overtime_status a
			left  join employee x on a.employee_id = x.employee_id
			left join employee y on a.approval_id = y.employee_id
			where overtime_approval=2 
			order by drequest desc";
		return $this->rst2Array($sql);
	}
	
		//  getOvertimeWaiting
	/*-------------------------------------------------------------------------------------*/	
	public function getOvertimeRequest() {
		$sql = "
			select a.overtime_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				b.employeenickname approval, c.employeenickname  requestor, a.overtime_approval
			from overtime_status a
			left join employee b on a.employee_id = b.employee_id
			left join employee c on a.overtime_approval_employee = c.employee_id
			where overtime_approval = 1 
				and a.employee_id = ".$this->session->userdata('employee_id') ."
			order by drequest desc";
		return $this->rst2Array($sql);
	}


	/*-------------------------------------------------------------------------------------*/	
	public function getOvertimeDetail($overtime_status_id) {
		$sql = "
			select * 
			from overtime
			where overtime_status_id = $overtime_status_id
			order by overtimedate  desc";
		return $this->rst2Array($sql);
	}	
	
		/*-------------------------------------------------------------------------------------*/	
	public function getOvertimeEditList($overtime_status_id) {
		$sql = "
			select * 
			from overtime
			where overtime_status_id = $overtime_status_id
			order by overtimedate  desc";
		return $this->rst2Array($sql);
	}	
	
			/*-------------------------------------------------------------------------------------*/	
	public function getOvertimeEditDetail($overtimeid) {
		$sql = "
			select * 
			from overtime
			where overtimeid = $overtimeid";
		return $this->rst2Array($sql);
	}	


//  saveTimesheet
	/*-------------------------------------------------------------------------------------*/
	function saveOvertime($form, $overtime_status_id) {
		$overtimedate= preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $form['overtimedate']);

		if ( isset( $_POST['return']) ) {
			if ( count($_POST['return']) > 0 ) {
				foreach ($_POST['return'] as $k=>$v) {
					$sql = "
						update timesheet set timesheet_approval = 3
						where timesheetid = ".$_POST['return'][$k]; 
					$this->db->query($sql);		
				}
			}
		}
	}	

//  getOvertimeWaiting
	/*-------------------------------------------------------------------------------------*/	
	public function getJobWaiting($limit=0, $offset=0) {
		$selectClause = $limit ? "a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				b.employeenickname approval, DATE_FORMAT(a.drequest, '%W') hari,
				a.timesheet_approval " : "count(*) total";
		$limitClause	= $limit ? "order by a.drequest desc limit $limit offset $offset" : "";
		$sql = "
			 	select $selectClause
			from timesheet_status a
			left join employee b on a.employee_id = b.employee_id
			where a.timesheet_approval = 1 and a.approval_id is null
			$limitClause";
			//order by drequest desc";
			//b.employeenickname, 
			//order by drequest desc";
			
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}	

	public function getJobDetail($timesheet_status_id) {
		$sql = "
			select * 
			from timesheet
			where timesheet_status_id = $timesheet_status_id
			order by timesheetdate  desc";
		return $this->rst2Array($sql);
	}	

	/*-------------------------------------------------------------------------------------*/
	public  function getJobTimesheet($id) {
		$sql = "
			select a.*,b.
			project_no, b.project,c.client_no, c.client_name, d.job, 
			e.employeenickname,e.employeefirstname, e.employeemiddlename, e.employeelastname, x.drequest,
			if(a.transport_type<3,'DK','LK') as type,(a.hour-a.overtime) as work_hour
			from timesheet_status x
			inner join timesheet a on a.timesheet_status_id = x.timesheet_status_id
			left join project b on a.project_id = b.project_id 
			left join client c on b.client_id = c.client_id 
			left join job d on a.job_id = d.job_id
			left join employee e on a.employee_id = e.employee_id
			where x.timesheet_status_id='$id'
			and a.timesheet_approval=2
			order by a.timesheetdate,a.year desc, a.week desc ";
		return $this->rst2Array($sql);
	}	


	public function getJobTimesheetApproved($filter, $limit=0, $offset=0) {
		$whereClause 	= '';
		$selectClause	= $limit ? "a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				y.employeenickname approval, x.employeenickname requestor, 
				a.timesheet_approval " : "count(*) total";
		//$limitClause	= $limit ? "order by x.employeenickname, a.year desc, a.week  limit $limit offset $offset" : "";
		$limitClause	= $limit ? "order by a.dapproval desc limit $limit offset $offset" : "";
		//$limitClause	= $limit ? "order by a.timesheetdate asc, a.dapproval desc limit $limit offset $offset" : "";
		if(isset($filter['request_by'])) $whereClause .= " and x.employeenickname like '%$filter[request_by]%'";
		if(isset($filter['week_at'])) $whereClause .= " and a.week like '%$filter[week_at]%'";
		if(isset($filter['year_at'])) $whereClause .= " and a.year like '%$filter[year_at]%'";
		if(isset($filter['approve_by'])) $whereClause .= " and y.employeenickname like '%$filter[approve_by]%'";
		$sql = "
			select $selectClause
			from timesheet_status a
			
			inner join employee x on a.employee_id = x.employee_id
			inner join employee y on a.approval_id = y.employee_id
			where a.timesheet_approval=2 $whereClause
			$limitClause";
			//order by drequest desc";
			//echo $sql;
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
	
	public function getAging() {
		$sql = "
			select a.timesheet_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
             DATE_FORMAT(a.drequest, '%W') hari,
				y.employeenickname approval, x.employeenickname requestor, a.timesheet_approval
			from timesheet_status a
			inner join employee x on a.employee_id = x.employee_id
			inner join employee y on a.approval_id = y.employee_id
			where a.timesheet_approval=1 or a.timesheet_approval is null
			order by drequest desc";
		return $this->rst2Array($sql);
	}
	
	public function getAdminmenu(){
		$sql = "select * from sys_menu where parentid ='administration' and lactive = 1";
		return $this->rst2Array($sql);
	}
}	