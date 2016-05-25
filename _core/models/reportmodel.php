<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class reportModel extends CI_Model {
	public function __construct() {
		parent::__construct ();
	}
	private function rst2Array($sql, $all = '') {
		$result = array ();
		$data = $this->db->query ( $sql );
		if ($data->num_rows () > 0) {
			$rows = $data->result_array ();
			$data->free_result ();
			if ($rows)
				switch ($all) {
					case 10 :
						// single row
						$result = $rows [0];
						break;
					case 11 :
						// single first cell
						$keys = array_keys ( $rows [0] );
						$result = $rows [0] [$keys [0]];
						break;
					default :
						$result = $rows;
						break;
				}
		}
		return $result;
	}
	
	// getReportEmployee
	/* ------------------------------------------------------------------------------------- */
	public function getReportEmployeeDate($data) {
		$sql = "select	
		DATE_FORMAT(a.timesheetdate, '%d/%m/%Y') date,
		DATE_FORMAT(a.timesheetdate, '%W') day_name
		from timesheet a
		where a.employee_id = '" . $data ['employee_id'] . "'
		and timesheet_approval= 2
		and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
		and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
		group by timesheetdate
		order by timesheetdate asc";
		return $this->rst2Array ( $sql );
	}
	public function getReportEmployee($data) {
		$sql = "select	
		DATE_FORMAT(a.timesheetdate, '%d/%m/%Y') date,
		DATE_FORMAT(a.timesheetdate, '%d - %m - %Y') tanggal,
		DATE_FORMAT(a.timesheetdate, '%W') hari,
		ifnull(sum(a.hour),0) hour, ifnull(sum(a.overtime),0) overtime
		from timesheet a
		where a.employee_id = '" . $data ['employee_id'] . "'
		AND a.department_id NOT IN(777)		
		and timesheet_approval= 2
		and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
		and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
		group by timesheetdate";
		return $this->rst2Array ( $sql );
	}
	
	// getReportEmployee
	/* ------------------------------------------------------------------------------------- */
	public function getReportEmployeeProject($employee_id, $date) {
		$sql = "select c.client_name, p.project_no, j.job_no, j.job,t.hour,t.overtime,(t.hour - t.overtime) as work_hour,t.cost,
		ea.employeefirstname as approval,IF(t.transport_type<3,'DK','LK') as type,DATE_FORMAT(ts.dapproval,'%d/%m/%Y %H:%i:%s') dapproval
		from timesheet t
		inner join project p on p.project_id = t.project_id
		inner join client c on c.client_id= p.client_id
		inner join job j on j.job_id = t.job_id
		inner join timesheet_status ts on ts.timesheet_status_id = t.timesheet_status_id
		left join employee ea on ea.employee_id = ts.approval_id
		where t.employee_id =  '" . $employee_id . "'
		and t.timesheet_approval = 2
		and t.timesheetdate = STR_TO_DATE('" . $date . "', '%d/%m/%Y')
		";
		return $this->rst2Array ( $sql );
	}
	
	/* ------------------------------------------------------------------------------------- */
	public function getReportEmployeeProjectOvertime($employee_id, $date) {
		$sql = "select b.client_name, a.project_no,
			  CONCAT(d.employeefirstname,' ', d.employeemiddlename, ' ', d.employeelastname) employee
        from project a
        inner join client b on a.client_id = b.client_id
		  left join project_team c on a.project_id = c.project_id and c.project_title = '03'
		  inner join employee d on c.employee_id = d.employee_id
        where a.project_id in (
            select distinct project_id
            from timesheet
            where employee_id =  '" . $employee_id . "'
            and timesheet_approval = 2
            and timesheetdate = STR_TO_DATE('" . $date . "', '%d/%m/%Y')
        )";
		
		// echo "$sql <br><br>";
		return $this->rst2Array ( $sql );
	}
	
	// getReportEmployee
	/* ------------------------------------------------------------------------------------- */
	public function getReportEmployeeSummary($data) {
		$sql = "select b.employeeid, c.project, c.project_no,
               CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname) employee,
                          ifnull(sum(a.hour),0) hour, ifnull(sum(a.overtime),0) overtime
                        from timesheet a
            left join project c on a.project_id = c.project_id
            inner join employee b on a.employee_id = b.employee_id
                        where timesheet_approval=2
                        and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
                        and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
                        group by a.employee_id
            order by CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname)";
		// echo $sql;
		return $this->rst2Array ( $sql );
	}
	
	// getReportTimesheetCompletion
	/* ------------------------------------------------------------------------------------- */
	public function getEmployeeGroup($department_id = '') {
		$sql = "select CONCAT(e.employeefirstname,' ', e.employeemiddlename, ' ', e.employeelastname) as name,e.EmployeeID,e.employee_id,e.EmployeeTitle,
				DATE_FORMAT(EmployeeHireDate,'%d %M %Y') as EmployeeHireDate
				from employee e
				inner join sys_user u on u.employee_id = e.employee_id
				where u.user_active = 1  and a.department_id NOT IN(777) ";
		if ($department_id)
			$sql .= " and e.department_id = " . ($department_id ? $department_id : 0) . " ";
		
		$sql .= " order by CONCAT(e.employeefirstname,' ', e.employeemiddlename, ' ', e.employeelastname)";
		// echo $sql;
		return $this->rst2Array ( $sql );
	}
	
	// getReportTimesheetCompletion
	/* ------------------------------------------------------------------------------------- */
	public function getTimesheetSummaryByProject($employee_id, $date_from,$date_to) {
		$sql = "select p.project_no,c.client_name,
				SUM(t.hour-t.overtime) as work_hour,SUM(t.overtime) as overtime_hour,SUM(t.hour) as total_hour 
				from timesheet t
				inner join project p on p.project_id = t.project_id
				inner join client c on c.client_id = p.client_id
				where t.employee_id = " .$employee_id. "
				and t.timesheet_approval = 2 and t.timesheetdate >= STR_TO_DATE('" . $date_from . "', '%d-%m-%Y')
                and t.timesheetdate <= STR_TO_DATE('" . $date_to . "', '%d-%m-%Y')		
				group by t.project_id		
				order by c.client_name ASC,p.project_no ASC";
			return $this->rst2Array ( $sql );
	}
	
	// getReportTimesheetCompletion
	/* ------------------------------------------------------------------------------------- */
	public function getTimesheetValue($date, $employee_id) {
		$sql = "select sum(hour) as hour,job_id
				from timesheet
				where employee_id = " . $employee_id . "
				and timesheetdate = '" . $date . "'
				and timesheet_approval = 2";
		return $this->rst2Array ( $sql, 10 );
	}
	
	// getReportTimesheetCompletion
	/* ------------------------------------------------------------------------------------- */
	public function getHoliday($date) {
		$sql = "select holiday_date
				from holiday
				where holiday_date = '" . $date . "'";
		return $this->rst2Array ( $sql, 10 );
	}
	
	// getReportTimesheetCompletion
	/* ------------------------------------------------------------------------------------- */
	public function getReportTimesheetCompletionSummary($data) {
		$sql = "
			   SELECT result.employeeid,result.employee,result.approval,SUM(tday) as tday,
			   SUM(hour_wait) as hour_wait,SUM(work_wait) as work_wait,SUM(ot_wait) as ot_wait,SUM(result.day_wait) as day_wait,
			   SUM(hour_app) as hour_app,SUM(work_app) as work_app,SUM(ot_app) as ot_app,SUM(result.day_app) as day_app,
			   SUM(hour_re) as hour_re,SUM(work_re) as work_re,SUM(ot_re) as ot_re,SUM(result.day_re) as day_re,
			   SUM(hour_null) as hour_null,SUM(work_null) as work_null,SUM(ot_null) as ot_null,SUM(result.day_null) as day_null
			   
			   FROM
			   (select b.employeeid,b.approval_id,su.employee_id,
				CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname) employee,
				y.employeenickname approval,
				
				COUNT(DISTINCT(a.timesheetdate)) as tday,
				
				SUM(IF(a.timesheet_approval=2,a.hour,0)) as hour_app,
				SUM(IF(a.timesheet_approval=2,a.hour-a.overtime,0)) as work_app,
				SUM(IF(a.timesheet_approval=2,a.overtime,0)) as ot_app,
				(SELECT COUNT(DISTINCT(tt.timesheetdate)) 
				 FROM timesheet tt 
				 WHERE tt.employee_id=a.employee_id 
				 AND tt.timesheet_approval=2
				 AND tt.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
				 AND tt.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y') ) as day_app,
				
				SUM(IF(a.timesheet_approval=1,a.hour,0)) as hour_wait,
				SUM(IF(a.timesheet_approval=1,a.hour-a.overtime,0)) as work_wait,
				SUM(IF(a.timesheet_approval=1,a.overtime,0)) as ot_wait,
				(SELECT COUNT(DISTINCT(tt.timesheetdate)) 
				 FROM timesheet tt 
				 WHERE tt.employee_id=a.employee_id 
				 AND tt.timesheet_approval=1
				 AND tt.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
				 AND tt.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y') ) as day_wait,
				
				SUM(IF(a.timesheet_approval=3,a.hour,0)) as hour_re,
				SUM(IF(a.timesheet_approval=3,a.hour-a.overtime,0)) as work_re,
				SUM(IF(a.timesheet_approval=3,a.overtime,0)) as ot_re,
				(SELECT COUNT(DISTINCT(tt.timesheetdate)) 
				 FROM timesheet tt 
				 WHERE tt.employee_id=a.employee_id 
				 AND tt.timesheet_approval=3
				 AND tt.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
				 AND tt.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y') ) as day_re,
				
				SUM(IF(a.timesheet_approval IS NULL,a.hour,0)) as hour_null,
				SUM(IF(a.timesheet_approval IS NULL,a.hour-a.overtime,0)) as work_null,
				SUM(IF(a.timesheet_approval IS NULL,a.overtime,0)) as ot_null,
				(SELECT COUNT(DISTINCT(tt.timesheetdate)) 
				 FROM timesheet tt 
				 WHERE tt.employee_id=a.employee_id 
				 AND tt.timesheet_approval IS NULL
				 AND tt.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
				 AND tt.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y') ) as day_null
				 
				from timesheet a
				inner join employee b on b.employee_id=a.employee_id
				inner join sys_user su on su.employee_id = b.employee_id
				inner join employee y on b.approval_id = y.employee_id
				where su.user_active=1 
				and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
				and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y')
				and b.department_id <> 10
				and b.department_id <> 777
				group by b.employee_id
				
				UNION
				
				select ee.employeeid,ee.approval_id,ee.employee_id,
				CONCAT(ee.employeefirstname,' ', ee.employeemiddlename, ' ', ee.employeelastname) employee,
				a.employeenickname approval,
				
				'0' as tday,
				
				'0' as hour_app,
				'0' as work_app,
				'0' as ot_app,
				'0' as day_app,
				
				'0' as hour_wait,
				'0' as work_wait,
				'0' as ot_wait,
				'0' as day_wait,
				
				'0' as hour_re,
				'0' as work_re,
				'0' as ot_re,
				'0' as day_re,
				
				'0' as hour_null,
				'0' as work_null,
				'0' as ot_null,
				'0' as day_null
				
				from employee ee
				inner join sys_user suu on suu.employee_id = ee.employee_id
				inner join employee a on a.employee_id = ee.approval_id
				where suu.user_active = 1
				and ee.department_id <> 10
				and ee.department_id <> 777
				group by ee.employee_id) result
				group by result.employee_id
				order by result.employee ASC	
			";
		return $this->rst2Array ( $sql );
	}
	
	// getReportTimesheetCompletion
	/* ------------------------------------------------------------------------------------- */
	public function getReportTimesheetCompletion($data) {
		$sql = "select b.employeeid, b.approval_id, c.project, c.project_no,sys_user.employee_id,sys_user.user_active,a.timesheet_approval,
               CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname) employee,
                          ifnull(sum(a.hour),0) hour, ifnull(sum(a.overtime),0) overtime,
                          '' approval, y.employeenickname approval
                        from timesheet a
			            left join sys_user on a.employee_id = sys_user.employee_id
			            left join project c on a.project_id = c.project_id
			            inner join employee b on a.employee_id = b.employee_id
			            inner join employee y on b.approval_id = y.employee_id
                        where timesheet_approval=2
                        and sys_user.user_active=1
                        and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
                        and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y')
                        group by a.employee_id
            order by CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname)";
		// echo $sql;
		return $this->rst2Array ( $sql );
	}
	
	// getReportTimesheetCompletion2
	/* ------------------------------------------------------------------------------------- */
	public function getReportTimesheetCompletion2($data) {
		$sql = "select b.employeeid, b.approval_id, c.project, c.project_no,sys_user.employee_id,sys_user.user_active,a.timesheet_approval,
               CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname) employee,
                          ifnull(sum(a.hour),0) hour, ifnull(sum(a.overtime),0) overtime,
                          '' approval, y.employeenickname approval
                        from timesheet a
			            left join sys_user on a.employee_id = sys_user.employee_id
			            left join project c on a.project_id = c.project_id
			            inner join employee b on a.employee_id = b.employee_id
			            inner join employee y on b.approval_id = y.employee_id
                        where timesheet_approval=1 
                        and sys_user.user_active=1
                        and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
                        and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y')
                        group by a.employee_id
            order by CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname)";
		// echo $sql;
		return $this->rst2Array ( $sql );
	}
	public function getReportTimesheetCompletion3($data) {
		$sql = "select b.employeeid, b.approval_id, c.project, c.project_no,sys_user.employee_id sys_user_employee_id ,sys_user.user_active,a.timesheet_approval,
               CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname) employee,
                          ifnull(sum(a.hour),0) hour, ifnull(sum(a.overtime),0) overtime,
                          '' approval, y.employeenickname approval,
                          DATE_FORMAT(a.timesheetdate, '%d - %m - %Y') date,b.employee_id
                        from employee b
                inner join sys_user on b.employee_id = sys_user.employee_id 
                left join timesheet a  on b.employee_id=a.employee_id 
                left join project c on c.project_id=a.project_id 
                left join employee y on y.employee_id=b.Approval_ID 
                        where timesheet_approval=null
                        and sys_user.user_active=1
                        and STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
                        and STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y')
                        and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
                        and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y')
                        group by a.employee_id
            order by CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname)";
		// echo $sql;
		return $this->rst2Array ( $sql );
	}
	
	// getReportEmployee
	
	/*
	 * -------------------------------------------------------------------------------------
	 * DATE_FORMAT(a.timesheetdate, '%d/%m/%Y') date,
	 * DATE_FORMAT(a.timesheetdate, '%d - %m - %Y') tanggal,
	 * DATE_FORMAT(a.timesheetdate, '%W') hari
	 */
	public function getReportEmployeeOvertime($data) {
		$sql = "select a.employee_id, b.employeeid,
               CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname) employee,b.employeetitle,
              SUM(a.overtime) AS overtime,
			  COUNT(DISTINCT(a.timesheetdate)) AS overday,
              d.department as department
            from timesheet a
            inner join employee b on a.employee_id = b.employee_id
            inner join department d on d.department_id=b.department_id
                        where timesheet_approval=2 and overtime > 0 and overtime is not null
                        and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
                        and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
                        and (b.employeetitle='Senior-2' OR  b.employeetitle='Senior-1' OR  b.employeetitle='Assistant')
                        group by a.employee_id
            order by CONCAT(b.employeefirstname,' ', b.employeemiddlename, ' ', b.employeelastname)";
		return $this->rst2Array ( $sql );
	}
	
	// getReportEmployee
	/* ------------------------------------------------------------------------------------- */
	public function getReportEmployeeAbsent($data) {
		$sql = "select timesheetid, employeeid, employeefirstname, employeemiddlename, employeelastname,
              a.job_id, b.job,
              DATE_FORMAT(a.timesheetdate, '%d/%m/%Y') date,
			  DATE_FORMAT(a.timesheetdate, '%W') hari,
               case when a.job_id=2 then b.job end sakit,
               case when a.job_id=2 then 1 else 0 end countsakit,
               case when a.job_id <>2 then b.job end onleave,
               case when a.job_id<>2 then 1 else 0 end countonleave   
            from timesheet a
            inner join job b on a.job_id = b.job_id
            inner join employee c on a.employee_id = c.employee_id
            where a.timesheet_approval=2
            and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
            and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
            and b.jobtype='HRD'
            and b.job_id  not in (470,498, 499, 500 ) 
            order by timesheetdate desc";
		return $this->rst2Array ( $sql );
	}
	public function getAbsentByEmployee($data) {
		$year_start = substr ( $data ['date_from'], 6, 4 );
		$month_start = substr ( $data ['date_from'], 3, 2 );
		$day_start = substr ( $data ['date_from'], 0, 2 );
		
		$year_end = substr ( $data ['date_to'], 6, 4 );
		$month_end = substr ( $data ['date_to'], 3, 2 );
		$day_end = substr ( $data ['date_to'], 0, 2 );
		
		$total = getRangeDate ( $day_start, $month_start, $year_start, $day_end, $month_end, $year_end );
		
		$sql = "
            SELECT employeeid,";
		for($i = 0; $i <= $total; $i ++) :
			$date = $year_start . '-' . $month_start . '-' . $day_start;
			$ndate = date ( "Y-m-d", strtotime ( "$date +$i day" ) );
			$sql .= " (SELECT SUM(aa.hour-aa.overtime) FROM timesheet aa WHERE aa.timesheetdate='" . $ndate . "' AND aa.timesheet_approval=2 AND aa.job_id=2 AND aa.employee_id=a.employee_id) AS S$i, ";
			$sql .= " (SELECT SUM(aa.hour-aa.overtime) FROM timesheet aa WHERE aa.timesheetdate='" . $ndate . "' AND aa.timesheet_approval=2 AND aa.job_id>=4 AND aa.job_id<=9 AND aa.employee_id=a.employee_id) AS I$i, ";
		endfor
		;
		$sql .= " employeefirstname, employeemiddlename, employeelastname ";
		$sql .= " from timesheet a
            inner join job b on a.job_id = b.job_id
            inner join employee c on a.employee_id = c.employee_id
            WHERE a.timesheet_approval=2
            and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
            and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
            and b.jobtype='HRD'
            and b.job_id  not in (470,498,499,500)
            GROUP BY employeeid 
            ORDER BY employeefirstname, employeemiddlename, employeelastname;";
		return $this->rst2Array ( $sql );
	}
	public function getAbsentByEmployeeSummary($data) {
		$year_start = substr ( $data ['date_from'], 6, 4 );
		$month_start = substr ( $data ['date_from'], 3, 2 );
		$day_start = substr ( $data ['date_from'], 0, 2 );
		
		$year_end = substr ( $data ['date_to'], 6, 4 );
		$month_end = substr ( $data ['date_to'], 3, 2 );
		$day_end = substr ( $data ['date_to'], 0, 2 );
		
		$total = getRangeDate ( $day_start, $month_start, $year_start, $day_end, $month_end, $year_end );
		
		$sql = " SELECT employeeid,";
		$sql .= " COUNT(CASE WHEN a.job_id=11 THEN 1 END) as cuti_tahunan, ";
		$sql .= " COUNT(CASE WHEN a.job_id=500 THEN 1 END) as cuti_bersama, ";
		$sql .= " COUNT(CASE WHEN a.job_id=12 THEN 1 END) as cuti_tanggungan, ";
		$sql .= " COUNT(CASE WHEN a.job_id=10 THEN 1 END) as cuti_khusus, ";
		$sql .= " COUNT(CASE WHEN a.job_id=2 THEN 1 END) as sakit, ";
		$sql .= " COUNT(CASE WHEN a.job_id>=4 AND a.job_id<=9 THEN 1 END) AS izin, ";
		$sql .= " COUNT(CASE WHEN a.job_id=3 THEN 1 END) AS haid, ";
		$sql .= " employeefirstname, employeemiddlename, employeelastname ";
		$sql .= " from timesheet a
            inner join job b on a.job_id = b.job_id
            inner join employee c on a.employee_id = c.employee_id
            WHERE a.timesheet_approval=2
            and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
            and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
            and b.jobtype='HRD'
            GROUP BY employeeid 
            ORDER BY employeefirstname, employeemiddlename, employeelastname;";
		return $this->rst2Array ( $sql );
	}
	
	// getReportEmployee
	/* ------------------------------------------------------------------------------------- */
	public function getReportEmployeeTotal($data) {
		$sql = "select timesheetid, employeeid, employeefirstname, employeemiddlename, 
    				employeelastname, EmployeeTitle,
              a.job_id, b.job,
              DATE_FORMAT(a.timesheetdate, '%d/%m/%Y') date,
			  DATE_FORMAT(a.timesheetdate, '%W') hari,
               case when a.job_id=2 then b.job end sakit,
               case when a.job_id=2 then 1 else 0 end countsakit,
               case when a.job_id <>2 then b.job end cuti,
               case when a.job_id <>2 then 1 else 0 end countcuti,
               case when a.job_id <>2 then b.job end onleave,
               case when a.job_id<>2 then 1 else 0 end countonleave   
            from timesheet a
            inner join job b on a.job_id = b.job_id
            inner join employee c on a.employee_id = c.employee_id
            where a.timesheet_approval=2
            and a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
            and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
            and b.jobtype='HRD'
            and b.job_id  not in (470,498, 499, 500 ) 
            order by timesheetdate desc";
		return $this->rst2Array ( $sql );
	}
	
	// getReportEmployeeWeek
	/* ------------------------------------------------------------------------------------- */
	/**
	 * public function getReportEmployeeWeek1($data) {
	 * $sql = "select timesheetid, employeeid, employeefirstname, employeemiddlename,
	 * employeelastname, EmployeeTitle,
	 * a.job_id, b.job,
	 * DATE_FORMAT(a.timesheetdate, '%d/%m/%Y') date,
	 * DATE_FORMAT(a.timesheetdate, '%W') hari,
	 * case when a.job_id=2 then b.job end sakit,
	 * case when a.job_id=2 then 1 else 0 end countsakit,
	 * case when a.job_id <>2 then b.job end onleave,
	 * case when a.job_id<>2 then 1 else 0 end countonleave
	 * from timesheet a
	 * inner join job b on a.job_id = b.job_id
	 * inner join employee c on a.employee_id = c.employee_id
	 * where a.timesheet_approval = 2
	 * and a.timesheetdate >= STR_TO_DATE('".$data['date_from']."', '%d/%m/%Y')
	 * and a.timesheetdate <= STR_TO_DATE('".$data['date_to']."', '%d/%m/%Y')
	 * and b.jobtype='HRD'
	 * and b.job_id not in (470,498, 499, 500 )
	 * order by timesheetdate desc";
	 * return $this->rst2Array($sql);
	 * }*
	 */
	public function getReportEmployeeWeek($data, $option = '') {
		$y = $data ['week'] ? $data ['week'] : 0;
		$x = $data ['week2'] ? $data ['week2'] : 0;
		$year = substr ( $data ['date_from'], 6, 4 ); // 20/12/2012
		
		if (($x <= $y)) {
			$xstart = $y;
			$xend = 52 + $x;
		} else {
			$xstart = $y;
			$xend = $x;
		}
		
		$sql = "select c.employeeid, c.employeefirstname, c.employeemiddlename,c.department_id,
    		   c.employeelastname,";
		
		for($is = $xstart; $is <= $xend; $is ++) :
			if ($is <= 52) {
				$i = $is;
				$year = $year;
			} else {
				$i = $is - 52;
				$year = 2014;
			}
			
			$sql .= "
		(SELECT COUNT(DISTINCT(aa.timesheetdate)) FROM timesheet aa INNER JOIN job bb ON aa.job_id = bb.job_id  WHERE aa.timesheet_approval=2 AND (aa.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y') AND aa.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')) AND  aa.week='$i' AND aa.year='$year' AND (aa.transport_type=1 OR transport_type=2) AND bb.jobtype<>'HRD' AND aa.employee_id=c.employee_id) as week_0$i,
                (SELECT COUNT(DISTINCT(aa.timesheetdate)) FROM timesheet aa INNER JOIN job bb ON aa.job_id = bb.job_id  WHERE aa.timesheet_approval=2 AND (aa.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y') AND aa.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')) AND  aa.week='$i' AND aa.year='$year' AND (aa.transport_type=3) AND bb.jobtype<>'HRD' AND aa.employee_id=c.employee_id) as lweek_0$i,			   
                (SELECT COUNT(DISTINCT(aa.timesheetdate)) FROM timesheet aa INNER JOIN job bb ON aa.job_id = bb.job_id  WHERE aa.timesheet_approval=2 AND aa.week='$i' AND aa.year='$year' AND (bb.job_id=2 OR bb.job_id=3) AND aa.employee_id=c.employee_id) AS sweek_0$i,
                (SELECT SUM(IF(aa.hour>=4,0,aa.hour)) FROM timesheet aa INNER JOIN job bb ON aa.job_id = bb.job_id  WHERE aa.timesheet_approval=2 AND (aa.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y') AND aa.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y') ) AND aa.week='$i' AND aa.year='$year' AND ((bb.job_id>=4 AND bb.job_id<=9) OR bb.job=17) AND aa.employee_id=c.employee_id) AS iweek_0$i,
		(SELECT COUNT(IF(aa.hour>=4,1,0)) FROM timesheet aa INNER JOIN job bb ON aa.job_id = bb.job_id  WHERE aa.timesheet_approval=2 AND (aa.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y') AND aa.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y') ) AND aa.week='$i' AND aa.year='$year' AND ((bb.job_id>=4 AND bb.job_id<=9) OR bb.job=17) AND aa.employee_id=c.employee_id) AS icweek_0$i,
                (SELECT COUNT(DISTINCT(aa.timesheetdate)) FROM timesheet aa INNER JOIN job bb ON aa.job_id = bb.job_id  WHERE aa.timesheet_approval=2 AND (aa.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y') AND aa.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y') ) AND aa.week='$i' AND aa.year='$year' AND (bb.job_id>=10 AND bb.job_id<=12) AND aa.employee_id=c.employee_id) AS cweek_0$i,
                (SELECT COUNT(DISTINCT(aa.timesheetdate)) FROM timesheet aa INNER JOIN job bb ON aa.job_id = bb.job_id  WHERE aa.timesheet_approval=2 AND (aa.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y') AND aa.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y') )AND aa.week='$i' AND aa.year='$year' AND bb.job_id=499 AND aa.employee_id=c.employee_id) AS liweek_0$i,
                (SELECT COUNT(DISTINCT(aa.timesheetdate)) FROM timesheet aa INNER JOIN job bb ON aa.job_id = bb.job_id  WHERE aa.timesheet_approval=2 AND (aa.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y') AND aa.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y') )AND aa.week='$i' AND aa.year='$year' AND (aa.transport_type=0) AND aa.employee_id=c.employee_id) as tkweek_0$i,
                (SELECT SUM(aa.overtime) FROM timesheet aa WHERE aa.timesheet_approval=2 AND (aa.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y') AND aa.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y') ) AND aa.week='$i' AND aa.year='$year' AND aa.employee_id=c.employee_id) as oweek_0$i,";
		endfor
		;
		$sql .= "c.EmployeeTitle from employee c
                inner join sys_user su ON su.employee_id=c.employee_id
                inner join department d on d.department_id = c.department_id
                where su.user_active=1
				and c.department_id NOT IN(777) 
                and c.employee_id <> 914
                and c.employee_id <> 9996
                and d.department_id<>8
                and d.department_id<>10
                and d.department_id<>19
                and d.department_id<>20
                and d.department_id<>21
                and d.department_id<>22
                and d.department_id<>120
                and d.department_id<>129
				and d.department_id<>134
				and d.department_id<>777
                ";
		if (($option))
			$option == 'KAP' ? $sql .= " and d.department_id<>7 and d.department_id<>18 " : "";
			$option == 'BKI' ? $sql .= " and d.department_id=7 " : "";
			$option == 'BO' ? $sql .= " and d.department_id=18" : "";
		
		$sql .= " group by c.employeeid
                order by CONCAT(employeefirstname,employeemiddlename,employeelastname) ASC ";
		// and d.department_id<>126
		return $this->rst2Array ( $sql );
	}
	public function getReportHolidayWeek($data) {
		$sql = " select DATE_FORMAT(holiday_date,'%d/%m') as date,holiday_desc as descr 
                 from holiday 
                 where holiday_date >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
                 and holiday_date <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')";
		return $this->rst2Array ( $sql );
	}
	public function getReportDepartment() {
		$sql = "select d.department,d.department_id
                from department d
                where department<>'Audit-XX' 
                and department<>'PT BDO XX'
                and department<>'AUDIT-TEST'
                and department_id=126
                and department_id=10
				and department_id<>777
                order by d.department ASC
                ";
		return $this->rst2Array ( $sql );
	}
	
	// where a.timesheet_approval=2
	/*
	 * SUM(CASE WHEN a.week = $i THEN a.overtime END) AS oweek_0$i,";
	 * INNER join job b on a.job_id = b.job_id
	 * RIGHT join employee c on a.employee_id = c.employee_id
	 * and a.timesheetdate >= STR_TO_DATE('".$data['date_from']."', '%d/%m/%Y')
	 * and a.timesheetdate <= STR_TO_DATE('".$data['date_to']."', '%d/%m/%Y')
	 * group by a.employee_id
	 * order by employeefirstname,employeemiddlename,employeelastname
	 */
	public function getTransportType($id, $type, $week, $year) {
		$this->db->select ( 'COUNT(transport_type) AS total' );
		$this->db->where ( 'employee_id', $id );
		$this->db->where ( 'transport_type', $type );
		$this->db->where ( 'week', $week );
		$this->db->where ( 'year', $year );
		$this->db->group_by ( 'employee_id' );
		$Q = $this->db->get ( 'timesheet' );
		$data = $Q->row_array ();
		return $data ['total'];
	}
	
	// getReportEmployee
	/* ------------------------------------------------------------------------------------- */
	public function getReportTransport($data) {
		$sql = " SELECT DISTINCT(timesheetdate),employeeid, employeefirstname, employeemiddlename, employeelastname,
					 SUM(IF(transport_type=1,IF(hour>8,8,hour),0)) AS office,
					 (SUM(IF(transport_type= 1,a.cost,0))) AS office_cost,
					 SUM(IF(transport_type=2,IF(hour>8,8,hour),0)) AS intown,
					 (SUM(IF(transport_type= 2,a.cost,0))) AS intown_cost,
					 SUM(IF(transport_type=3,IF(hour>8,8,hour),0)) AS outtown,
					 (SUM(IF(transport_type= 3,a.cost,0))) AS outtown_cost,
					 SUM(IF(transport_type=0,IF(hour>8,8,hour),0)) AS uknown,
					 (SUM(IF(transport_type=0,a.cost,0))) AS uknown_cost,
					 SUM(a.hour) as total,
					 SUM(cost) as cost,
					 SUM(cost) as actual
					 FROM timesheet a
					 INNER JOIN employee c on a.employee_id = c.employee_id
					 WHERE a.timesheet_approval = 2
					 AND a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
					 AND a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
			   ";
		
		if ($data ['paid'] != '')
			$sql .= " AND a.transport_paid=" . $data ['paid'];
		
		$sql .= " group by employeeid order by employeefirstname, employeemiddlename, employeelastname ";
		return $this->rst2Array ( $sql );
	}
	
	// add by ram 02-2010
	public function getReportTransportEmployee($data) {
		$sql = "select timesheetid,employee_id,date,project,client,charge,partner,
              office,
              intown,
              outtown,
              uknown,
              transport_cost,
			  cost,transport_paid AS paid,
			        hari
          from (
                select timesheetid, a.employee_id,c.project,d.client_name as client,a.cost,
				  case WHEN d.client_no LIKE '%-PT%' THEN 'BKI' ELSE 'KAP' end charge,
				  CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as partner,
                  case when a.transport_type='1' then 1 else 0 end office,
                  case when a.transport_type='2' then 1 else 0 end intown,
                  case when a.transport_type='3' then 1 else 0 end outtown,
                  case when a.transport_type='0' then 1 else 0 end uknown,
                transport_cost,transport_paid,DATE_FORMAT(a.timesheetdate, '%d/%m/%Y') as date,
                DATE_FORMAT(a.timesheetdate, '%W') hari
                from timesheet a
                  inner join project c on a.project_id = c.project_id 
				  inner join client d on c.client_id=d.client_id
				  left join employee e on e.employee_id = c.approveuser
                  where a.timesheet_approval=2
                  AND a.cost>0
                  and (a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
                  and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')) 
			            and a.employee_id='" . $data ['employee_id'] . "'
               
				  
               ) x
           order by date";
		return $this->rst2Array ( $sql );
	}
	public function getUpdateTransportPaid($ID, $val) {
		$value ['transport_paid'] = $val;
		$this->db->where ( 'timesheetid', $ID );
		$this->db->update ( 'timesheet', $value );
	}
	public function getAuditorFromTimesheet($data) {
		$sql = " SELECT a.employee_id,employeefirstname,employeemiddlename,employeelastname
                 FROM timesheet a
                 INNER JOIN employee c on a.employee_id = c.employee_id
                 WHERE a.timesheet_approval=2
                 AND  a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
                 AND  a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')";
		if ($data ['paid'] != '')
			$sql .= " AND a.transport_paid=" . $data ['paid'];
		
		$sql .= " GROUP BY a.employee_id
                 HAVING SUM(a.cost)>0
                 ORDER BY employeefirstname ASC; 
               ";
		return $this->rst2Array ( $sql );
	}
	public function getTransportDetailsByEmployee($data) {
		$sql = " SELECT DATE_FORMAT(a.timesheetdate,'%d/%m/%Y') AS date,
                 DAYNAME(a.timesheetdate) AS dayname,
                 c.client_name AS client,c.address,
                 IF(a.transport_type=1,'1','') AS office,
                 IF(a.transport_type=2,'1','') AS intown,
                 IF(a.transport_type=3,'1','') AS outtown,
                 IF(a.transport_type=0,'1','') AS uknown,
				 case WHEN c.client_no LIKE '%-PT%' THEN 'BKI' ELSE 'KAP' end charge,
                 a.cost,a.notes
                 FROM timesheet a
                 INNER JOIN project p on p.project_id = a.project_id 
                 INNER JOIN client c on c.client_id=p.client_id
                 WHERE a.timesheet_approval=2
                 AND (a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
                 AND a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')) 
                 AND a.employee_id='" . $data ['employee_id'] . "'
                 AND a.cost>0 ";
		if ($data ['paid'])
			$sql .= " AND a.transport_paid=" . $data ['paid'];
		$sql .= " ORDER BY date ASC ";
		return $this->rst2Array ( $sql );
	}
	public function getClientProjectName($data) {
		$sql = "SELECT client_name
             FROM client
             WHERE client_id=$data[client_id]
            ";
		$Q = $this->db->query ( $sql );
		$data = $Q->row_array ();
		return $data;
	}
	public function getReportTransportbyClient($data) {
		/*
		 * $sql ="select d.client_id,d.client_name,address,
		 * SUM(a.cost) as cost
		 * from timesheet a
		 * inner join project c on c.project_id = a.project_id
		 * inner join client d on c.client_id=d.client_id
		 * where a.timesheet_approval=2
		 * AND a.cost>0
		 * and (a.timesheetdate >= STR_TO_DATE('".$data['date_from']."', '%d/%m/%Y')
		 * and a.timesheetdate <= STR_TO_DATE('".$data['date_to']."', '%d/%m/%Y'))
		 * GROUP BY d.client_id
		 * order by d.client_name ASC;";
		 */
		$sql = " SELECT project_id,project_no,DATE_FORMAT(start_date,'%d/%m/%Y') as start_date,project,
              DATE_FORMAT(year_end,'%d/%m/%Y') as year_end,
              DATE_FORMAT(finish_date,'%d/%m/%Y') as finish_date,address,budget_cost,client_name ,client.address 
              FROM project
              INNER JOIN client ON client.client_id=project.client_id
              WHERE project.client_id=$data[client_id]
              AND YEAR(year_end)>='$data[year]' 
              ORDER BY YEAR(year_end) ASC
            ";
		return $this->rst2Array ( $sql );
	}
	
	/*
	 * public function getReportTransportbyProject($data)
	 * {
	 * $sql ="select c.project_id,c.project_no,DATE_FORMAT(c.year_end,'%d-%m-%Y') as year_end,
	 * SUM(a.cost) as cost
	 * from timesheet a
	 * inner join project c on c.project_id = a.project_id
	 * where a.timesheet_approval=2
	 * AND a.cost>0
	 * and (a.timesheetdate >= STR_TO_DATE('".$data['date_from']."', '%d/%m/%Y')
	 * and a.timesheetdate <= STR_TO_DATE('".$data['date_to']."', '%d/%m/%Y'))
	 * and c.client_id=".$data['client_id']."
	 * GROUP BY c.project_id
	 * order by c.year_end ASC;";
	 * return $this->rst2Array($sql);
	 * }
	 */
	public function getReportTransportbyEmployee($data) {
		/**
		 * and (a.timesheetdate >= STR_TO_DATE('".$data['date_from']."', '%d/%m/%Y')
		 * and a.timesheetdate <= STR_TO_DATE('".$data['date_to']."', '%d/%m/%Y'))
		 */
		$sql = " select e.employeefirstname,e.employeemiddlename,e.employeelastname,
            DATE_FORMAT(a.timesheetdate,'%d/%m/%Y') as timesheetdate,a.cost,
            CASE 
                WHEN a.transport_type=1 THEN 'Office'
                WHEN a.transport_type=2 THEN 'In Town Client'
                WHEN a.transport_type=3 THEN 'Out Town Client'
                WHEN a.transport_type=0 THEN 'Uknown'  
            END AS transporttype,notes,
            CASE a.timesheet_approval
                WHEN 0 THEN 'Waiting'
                WHEN 1 THEN 'Return'
                WHEN 2 THEN 'Approve'
            END as status 
            from timesheet a
            inner join employee e on e.employee_id = a.employee_id 
            where a.timesheet_approval=2
            AND a.cost>0
            AND a.project_id=" . $data ['project_id'] . " 
            order by a.timesheetdate ASC,e.employeefirstname ASC;";
		return $this->rst2Array ( $sql );
	}
	public function getReportProject($client_id) {
		$sql = "select a.project_id, project_no 
  				from project a 
  				where a.client_id = $client_id and project_approval = 3
  				order by a.project_no asc";
		return $this->rst2Array ( $sql );
	}
	public function getReportProject1($data) {
		$sql = "select a.project_id, project_no, budget_hour, hour, budget_cost, cost, year_end, 
				c.client_id, c.client_name, b.jobtype_id, b.jobtype,
				'' approval, y.employeenickname approval
  				from project a 
				left join client c on a.client_id = c.client_id
				left join job_type b on a.jobtype_id = b.jobtype_id
				inner join employee y on a.createuser = y.employee_id				
  				where a.project_approval = 3
				and a.year_end >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
				and a.year_end <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y')
				group by a.project_id
  				order by a.project_no asc";
		return $this->rst2Array ( $sql );
	}
	public function getReportProjectJob($project_id) {
		$sql = "select b.job_id, b.job_no, b.job 
  				from project_job a
				inner join job b on a.job_id = b.job_id
  				where a.project_id = $project_id 
  				order by b.job_no asc";
		return $this->rst2Array ( $sql );
	}
	public function getReportProjectJobDetail($job_id, $project_id) {
		$sql = "Select a.project_id, a.project_title, e.lookup_label level,
					a.employee_id, d.employeeid,
					d.employeefirstname, d.employeemiddlename, d.employeelastname,
					01_hour, 02_hour, 03_hour, 041_hour, 042_hour, 043_hour, 044_hour,
					ifnull(sum(hour),0) actual, ifnull(sum(overtime),0) overtime
				 from
				 project_team a
				 inner join project_job b on a.project_id= b.project_id and b.job_id = $job_id
				 left join timesheet c on c.project_id = a.project_id and c.job_id = b.job_id and c.employee_id = a.employee_id and c.timesheet_approval = 2
				 inner join employee d on d.employee_id = a.employee_id
				 inner join lookup e on a.project_title = e.lookup_code and e.lookup_group='project_title'
				 
				 where a.project_id = $project_id and a.employee_id <> 0
				 group by a.employee_id
				 order by a.project_id, a.project_title ";
		/*
		 * $sql="select b.employeeid, b.employeefirstname, b.employeemiddlename, b.employeelastname,
		 * c.lookup_label level, a.project_title,
		 * ifnull(a.budget_hour,0) budget_hour, ifnull(a.actual_hour,0) actual_hour
		 *
		 * from project_team a
		 * inner join employee b on a.employee_id = b.employee_id
		 * inner join lookup c on a.project_title = c.lookup_code and c.lookup_group='project_title'
		 * where a.project_id = $project_id
		 * order by a.project_title, b.employeefirstname, b.employeemiddlename, b.employeelastname";
		 */
		return $this->rst2Array ( $sql );
	}
	public function getReportPartner($data) {
		$whereClause = " and a.project_id in ( select distinct project_id from project_team where employee_id = '" . $data ['employee_id'] . "') ";
		
		$sql = "
			select a.*, b.*, c.jobtype,
				DATE_FORMAT(a.year_end, '%d - %m - %Y') year_end,DATE_FORMAT(a.year_end, '%W') hari,
				(
				select ifnull(sum(hour),0) actual
				from timesheet x
				where x.timesheet_approval=2
					and x.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
					and x.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
			  and x.project_id =a.project_id
			 ) actual,
			 (select ifnull(y.employee_id,0) mic from lookup z left join project_team y on z.lookup_code =y.project_title where 
z.lookup_group = 'project_title' and z.lookup_code = '03' and y.project_id=a.project_id) mic
			from project a 
			inner join client b on a.client_id =b.client_id
			left join job_type c on a.jobtype_id =c.jobtype_id
			where 1=1 and project_id <> 1  $whereClause 
			order by a.project, b.client_name  			";
		// echo $sql;
		return $this->rst2Array ( $sql );
	}
	public function getProject($client_id) {
		if (strlen ( $client_id ) > 0) {
			$whereClause = ' and a.project_id in ( select distinct project_id from project_team where employee_id = ' . $this->session->userdata ( 'employee_id' ) . ' )';
			
			$sql = "
				select a.*, b.*
				from project a 
				inner join client b on a.client_id =b.client_id 
				where 1=1 and project_id <> 1 and b.client_id= $client_id  $whereClause 
				order by a.project, b.client_name  			";
			return $this->rst2Array ( $sql );
		} else {
			return null;
		}
	}
	
	// getProjectDetail
	/* ------------------------------------------------------------------------------------- */
	public function getProjectDetail($project_id) {
		$sql = "
			select a.*,b.client_no, b.client_name, c.jobtype  
			from project a 
			left join client b on a.client_id=b.client_id 
			left join job_type c on a.jobtype_id =c.jobtype_id 
			where project_id = $project_id";
		return $this->rst2Array ( $sql, 10 );
	}
	
	/* ------------------------------------------------------------------------------------- */
	public function getProjectJobDetail($job_id, $project_id) {
		$sql = "
			select a.*,b.client_no, b.client_name, c.jobtype, d.job_no, d.job  
			from project a
			inner join client b on a.client_id=b.client_id
			inner join project_job x on a.project_id =x.project_id and x.job_id = $job_id
			left join job_type c on a.jobtype_id =c.jobtype_id
			left join job d on x.job_id =d.job_id
			where x.job_id = $job_id and x.project_id = $project_id";
		return $this->rst2Array ( $sql, 10 );
	}
	
	// getReportGroup
	/* ------------------------------------------------------------------------------------- */
	public function getReportGroup($data) {
		$sql = "select b.employeeid,b.employeefirstname, b.employeemiddlename, b.employeelastname,
						b.employeetitle, 
					 '' timecome, '' timehome,
					sum(a.latein) latein, sum(a.earlyout) earlyout, sum(a.overtime) overtime, sum(a.totalot) totalot, 
				sum(a.totalhour) totalhour, 0 actual, 0 budget, 0 balance
				from personalcalendar a
				inner join employee b on a.fingerprintid = b.employee_id
				where b.department_id= '" . $data ['department_id'] . "'
				and a.personalcalendardate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
				and a.personalcalendardate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')
				group by employee_id
				";
		return $this->rst2Array ( $sql );
	}
	
	// getDepartment
	/* ------------------------------------------------------------------------------------- */
	public function getDepartment() {
		$sql = "select department_id, departmentcode, department,
						a.company_id, b.company
				from department a
				left  join company b on a.company_id = b.company_id
				order by departmentcode";
		return $this->rst2Array ( $sql );
	}
	
	// getDepartment
	/* ------------------------------------------------------------------------------------- */
	public function getDepartmentById($id = 0) {
		$sql = "select department_id, department
				from department d
				where d.department_id = ".($id ? $id : 0);
		return $this->rst2Array ( $sql,10 );
	}
	
	// getUserEmployee
	/* ------------------------------------------------------------------------------------- */
	public function getUserEmployee() {
		$sql = "select a.employee_id, a.employeefirstname, a.employeemiddlename, a.employeelastname ,a.department_id
				from employee a 
				inner join sys_user su on su.employee_id = a.employee_id
				where a.department_id NOT IN(777) and user_active = 1
				order by  a.employeefirstname, a.employeemiddlename, a.employeelastname ";
		return $this->rst2Array ( $sql );
	}
	
	public function getPartner() {
		$sql = "select a.employee_id, employeefirstname, employeemiddlename, employeelastname 
				from employee a
				inner join sys_user b on a.employee_id = b.employee_id
				where b.acl='01'
				order by  employeefirstname, employeemiddlename, employeelastname ";
		return $this->rst2Array ( $sql );
	}
	public function getClient() {
		$whereClause = ' and a.project_id in ( select distinct project_id from project_team where employee_id = ' . $this->session->userdata ( 'employee_id' ) . ' )';
		$sql = "
			    select distinct b.client_id, b.client_name
			    from project a 
			    inner join client b on a.client_id =b.client_id 
			    where 1=1 and project_id <> 1  $whereClause 
			    order by b.client_name  			";
		// echo $sql;
		return $this->rst2Array ( $sql );
	}
	
	// getReportTimesheetBudget
	/* ------------------------------------------------------------------------------------- */
	public function getReportTimesheetBudgetActual($data) {
		$sql = " SELECT app.employee_id,CONCAT(app.employeefirstname,' ',app.employeemiddlename) AS employeename ,app.employeetitle,
                 SUM(pp.budget_hour) as budget_hour,SUM(pp.budget_cost) as budget_cost,
                 SUM(pp.hour) as actual_hour,SUM(pp.cost) as actual_cost,
                 COUNT(DISTINCT(pp.project_id)) as project
                 FROM project_team p
                 INNER JOIN employee e ON e.employee_id=p.employee_id
                 INNER JOIN employee app ON app.employee_id=e.approval_id
                 INNER JOIN project pp ON pp.project_id=p.project_id
                 WHERE pp.year_end >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
    	         AND   pp.year_end <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y')
                 AND  (app.employeetitle='Manager' OR app.employeetitle='Senior Manager')
                 GROUP BY e.approval_id
                 ORDER BY e.employeefirstname ASC,e.employeemiddlename ASC
           ";
		// echo $sql;
		return $this->rst2Array ( $sql );
	}
	
	// getReportProjectbyEmployee
	/* ------------------------------------------------------------------------------------- */
	public function getReportProjectbyEmployee($data) {
		$sql = " SELECT pp.project_no,cc.client_name,
                 SUM(pp.budget_hour) as budget_hour,SUM(pp.budget_cost) as budget_cost,
                 SUM(pp.hour) as actual_hour,SUM(pp.cost) as actual_cost   
                 FROM project_team pt
                 INNER JOIN project pp ON pp.project_id=pt.project_id
                 INNER JOIN client cc ON cc.client_id = pp.client_id
                 WHERE pp.year_end >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
    	         AND   pp.year_end <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y')
                 AND   pt.approval_id = " . $data ['employee'] . " 
                 GROUP BY pp.project_id
                 ORDER BY cc.client_name
           ";
		return $this->rst2Array ( $sql );
	}
	
	// getReportTimesheetBudget
	/* ------------------------------------------------------------------------------------- */
	public function getReportActualEmployee($data) {
		$sql = " SELECT e.employee_id,e.employeeid,CONCAT(e.employeefirstname,' ',e.employeemiddlename) AS employeename,
                 CONCAT(app.employeefirstname,' ',app.employeemiddlename) AS employeeapproval,
                 DATE_FORMAT(e.employeehiredate,'%d/%m/%Y') as employeehiredate,
                 SUM(IF(j.jobtype='HRD',t.hour,0)) as hhour,
                 SUM(IF(j.jobtype<>'HRD',t.hour,0)) as phour,
                 SUM(t.hour) as hour
                 FROM timesheet t
                 INNER JOIN employee e ON e.employee_id=t.employee_id
                 INNER JOIN sys_user u ON u.employee_id=e.employee_id
                 INNER JOIN employee app ON app.employee_id=e.approval_id
                 INNER JOIN job j ON j.job_id=t.job_id
                 WHERE t.timesheet_approval=2
                 AND u.user_active=1 
				 AND e.department_id NOT IN (10)
                 AND t.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d-%m-%Y')
    	         AND   t.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d-%m-%Y')
                 GROUP BY e.employee_id
                 ORDER BY CONCAT(e.employeefirstname,' ',e.employeemiddlename,' ',e.employeelastname) ASC;";
		// echo $sql;
		return $this->rst2Array ( $sql );
	}
	public function getReporAbsentByEmployee($data) {
		$sql = "   select timesheetid, employee_id,c.project,d.client_name as client,c.project_no,a.hour,a.overtime,a.notes,
			   case a.transport_type
			      when  1 then 'Office'
				  when  2 then 'In Town'
				  when  3 then  'Out Town'
               end as transport_type,j.job,
                DATE_FORMAT(a.timesheetdate, '%d/%m/%Y') as date,
                DATE_FORMAT(a.timesheetdate, '%W') hari,
				DATE_FORMAT(a.sysdate, '%d/%m/%Y %H:%i:%s') as cdate
                from timesheet a
                inner join project c on a.project_id = c.project_id 
				inner join client d on c.client_id=d.client_id
				inner join job j on j.job_id = a.job_id
                where (a.timesheetdate >= STR_TO_DATE('" . $data ['date_from'] . "', '%d/%m/%Y')
                and a.timesheetdate <= STR_TO_DATE('" . $data ['date_to'] . "', '%d/%m/%Y')) 
			    and a.employee_id='" . $data ['employee_id'] . "'
				order by date";
		return $this->rst2Array ( $sql );
	}
	
	public function getEmployeeWeekDetails($employee_id, $week, $year) {
		$sql = "
			SELECT DATE_FORMAT(timesheetdate,'%d/%m/%Y') as timesheetdate,timesheetdate as tdate,t.employee_id,t.week,j.`JOBTYPE`,transport_type,t.hour,t.job_id,t.overtime
			FROM timesheet t
			INNER JOIN job j ON j.job_id = t.job_id
			WHERE timesheet_approval = 2  
			AND employee_id = $employee_id
			AND WEEK = $week
		";
		
		if (($week == 52) || ($week == 53))
			$sql .= " AND (YEAR = " . $year . " OR YEAR = " . ($year + 1) . ") ";
		else
			$sql .= " AND YEAR = " . $year . " ";
		
		$sql .= " ORDER BY timesheetdate ASC";
		
		return $this->rst2Array ( $sql );
	}
	
	public function getEmployeeTimesheetHoliday($employee_id,$timesheetdate,$job_id) {
		$sql = "
			SELECT transport_type,hour
			FROM timesheet t
			WHERE timesheet_approval = 2
			AND employee_id = $employee_id
			AND timesheetdate = '".$timesheetdate."'
			AND job_id <> ".$job_id."
			ORDER BY t.hour DESC
		";
		return $this->rst2Array ( $sql ,10);
	}
	
	public function getEmployeeTimeSheetDate($employee_id, $timesheetdate) {
		$sql = "
			SELECT DATE_FORMAT(timesheetdate,'%d/%m/%Y') as timesheetdate,t.employee_id,t.week,j.`JOBTYPE`,transport_type,t.hour,t.job_id,t.overtime
			FROM timesheet t
			INNER JOIN job j ON j.job_id = t.job_id
			WHERE timesheet_approval = 2
			AND employee_id = $employee_id
			AND timesheetdate = '$timesheetdate'
			ORDER BY timesheetdate ASC";
		return $this->rst2Array ($sql);
	}
	public function getTMPEmployeeWeek($employee_id, $week) {
		$sql = "SELECT t.employee_id,t.week,
		IF(j.jobtype<>'HRD' AND t.transport_type<3,COUNT(DISTINCT(timesheetdate)),0) AS dk,
		IF(j.jobtype<>'HRD' AND t.transport_type=3,COUNT(DISTINCT(timesheetdate)),0) AS lk,
		CEIL(SUM(IF(t.hour<=24 AND j.job_id<=3,t.hour-t.overtime,0))/8) AS s,
		SUM(IF(t.hour<4 AND ((j.job_id>=4 AND j.job_id<=9) OR j.job_id=17),t.hour-t.overtime,0)) AS i,
		CEIL(SUM(IF(t.hour>=4 AND ((j.job_id>=4 AND j.job_id<=9) OR j.job_id=17 OR (j.job_id>=10 AND j.job_id<=12) ),t.hour-t.overtime,0))/8) AS c,
		CEIL(SUM(IF(t.hour>=8 AND j.job_id=499,t.hour-t.overtime,0))/8) AS li,
		SUM(IF(t.overtime>0,t.overtime,0)) AS le
		FROM timesheet t
		INNER JOIN job j ON j.job_id = t.job_id
		WHERE timesheet_approval=2  
		AND timesheetdate > '2014-12-01'   
		AND employee_id=$employee_id
		AND WEEK=$week
		GROUP BY t.employee_id,t.week
		ORDER BY t.employee_id,t.week;
		
		";
		return $this->rst2Array ( $sql, 10 );
	}
	public function getEmployeeWeek($department_id) {
		$sql = "
		select e.employee_id,e.employeeid,
		CONCAT(employeefirstname,' ',employeemiddlename,' ',employeelastname) AS employeename,employeetitle
		from employee e
		inner join sys_user su on su.employee_id=e.employee_id
		where su.user_active = 1
		and e.department_id NOT IN (9996,8,10,19,20,21,22,120,129,134)
		";
		$department_id == 'KAP TSFR' ? $sql .= " and e.department_id NOT IN (7,18,777) " : "";
		$department_id == 'PT BDO KONSULTAN INDONESIA' ? $sql .= " and e.department_id IN (7) " : "";
		$department_id == 'PT BDO Konsultan Indonesia Outsource' ? $sql .= " and e.department_id IN (777) " : "";
		$department_id == 'PT BDO MANAJEMEN INDONESIA' ? $sql .= " and e.department_id IN (18) " : "";
		
		$sql .= " order by CONCAT(employeefirstname,' ',employeemiddlename,' ',employeelastname) ASC LIMIT 350";
		return $this->rst2Array ( $sql );
	}
	
	public function getTMPEmployeeWeekInsert($form) {
		return $this->db->insert ( 'tmp_employee_week', $form );
	}
	
	//  getProject
	/*-------------------------------------------------------------------------------------*/
	public  function getAllowance($filter = array()) {
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
			inner join department d on d.department_id = e.department_id	
			where a.id > 0
		";
		if(isset($filter['department_id'])) {
			if($filter['department_id']) $sql.=" AND d.department_id = ".$filter['department_id']."";
		}		
		if(isset($filter['date_from']) && isset($filter['date_to'])) {
			$date_from = preg_replace ( '!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$filter['date_from'] );
			$date_to = preg_replace ( '!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$filter['date_to'] );
			$sql.=" AND date_realization>='".$date_from."' AND date_realization<='".$date_to."' ";
		}
		
		$sql.= " group by a.id order by date_from asc ";
		return $this->rst2Array($sql);
	}
}
/* End of file mainModel.php */