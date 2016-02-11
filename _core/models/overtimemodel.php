<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class overtimeModel extends CI_Model {

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
	public  function syncOvertimeStatus() {
		$sql = "insert into overtime_status( week, year, employee_id)
				select distinct week, year, employee_id
				from timesheet_status a
				where employee_id = ".$this->session->userdata('employee_id')."
				and week not in ( 
					select week from overtime_status where employee_id = ".$this->session->userdata('employee_id')."
					and year=a.year)";
		$this->db->query($sql);;
	}
	

	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function syncOvertimeDetail($overtime_status_id, $week, $year) {
		$sql = "insert into overtime(overtimedate, week, year, employee_id, overtime_status_id, hour, office, overtime,notes)
select distinct timesheetdate, week, year, employee_id, '".$overtime_status_id ."', sum(ifnull(hour,0)) hour, 8, sum(ifnull(hour,0)) -8 overtime, notes
from timesheet a
where employee_id = ".$this->session->userdata('employee_id')."
and week = (select week from overtime_status a where overtime_status_id = ".$overtime_status_id .")
and year = (select year from overtime_status a where overtime_status_id = ".$overtime_status_id .")
and timesheetdate not in ( select overtimedate from overtime where overtime_status_id = ".$overtime_status_id ." )
group by timesheetdate";
		$this->db->query($sql);;
	}

	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getOvertime() {
		$selectClause	= "a.overtime_status_id, a.week, a.year, a.drequest, a.overtime_approval,
							sum(ifnull(hour,0)) hour,   sum(ifnull(office,0)) office,   sum(ifnull(overtime,0)) overtime  ";
		$limitClause	= "order by a.year desc, a.week desc";
		$sql = "select $selectClause
				from overtime_status a
				left join overtime b on a.overtime_status_id = b.overtime_status_id
				where a.employee_id = ".$this->session->userdata('employee_id')."
				group by a.overtime_status_id  $limitClause";
		//echo $sql;
		return $this->rst2Array($sql) ;
	}
	
	//  getOvertimeWaiting
	/*-------------------------------------------------------------------------------------*/	
	public function getOvertimeWaiting() {
		$sql = "
			select a.overtime_status_id,a.week, a.year, DATE_FORMAT(a.drequest, '%d - %m - %Y') drequest,
				DATE_FORMAT(a.dapproval, '%d - %m - %Y') dapproval,
				b.employeenickname approval, c.employeenickname  requestor, a.overtime_approval
			from overtime_status a
			left join employee b on a.employee_id = b.employee_id
			left join employee c on a.overtime_approval_employee = c.employee_id
			where overtime_approval = 1 
				and a.approval_id = ".$this->session->userdata('employee_id') ."
			order by drequest desc";
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
				and a.employee_id = '".$this->session->userdata('employee_id') ."'
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

		
/*
		if ( $form['overtimeid'] === '0' ) {
			$sql = "insert into overtime( over_status_id, project_id, employee_id, week, year, job_id, notes, timesheetdate,hour, cost, sysdate,sysuser) 
					values ($timesheet_status_id,'$form[project_id]','".$this->session->userdata('employee_id')."', '$form[week]' , '$form[year]',
					'$form[job_id]', '$form[notes]', '$timesheetdate', $form[hour],   $form[cost], 
					now(),'".$this->session->userdata('employee_id')."')"; 
		}
		else {
			$sql = "update timesheet set 
				project_id 		= '$form[project_id]',
				week				= '$form[week]',
				year				= '$form[year]',
				employee_id 	= ".$this->session->userdata('employee_id') .",
				job_id			= '$form[job_id]', 
				notes				= '$form[notes]', 
				timesheetdate	= '$timesheetdate', 
				notes				= '$form[notes]', 
				hour				= $form[hour],
				cost				= $form[cost],
				sysdate			= now(),
				sysuser			= '".$this->session->userdata('employee_id')."'
				where timesheetid	= $form[id]";
		}
		$this->db->query($sql);		
		if ( $form['id'] === '0' ) {
			$id = $this->db->insert_id();
		} 
		else {
			$id = $form['id'];	
		}
		echo $sql;
*/
		}	
}