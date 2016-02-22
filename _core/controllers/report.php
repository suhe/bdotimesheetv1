<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Report extends MY_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'reportModel' );
		$this->load->model ( 'projectModel' );
		$this->load->model ( 'timesheetModel' );
		$this->load->model ( 'dataModel' );
		error_reporting(E_ALL);
		ini_set ( 'max_execution_time', 3600 );
		ini_set('display_errors', '1');
	}
	
	/* ------------------------------------------------------------------------------------- */
	// report
	/* ------------------------------------------------------------------------------------- */
	public function index() {
		$this->getMenu ();
		$this->load->view ( 'report', $this->data );
	} // END report
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	public function reportEmployee() {
		$this->getMenu ();
		$this->data ['form'] ['employee_id'] = $this->input->post ( 'employee_id' );
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['employee_id'] > 0 )) {
			$rows = $this->reportModel->getReportEmployeeDate ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				// $total_hour = 0;
				// $total_overtime = 0;
				// $rs_totalhour = 0;
				// $v_hour = 0;
				// $rs_overtime = 0;
				// $normal=0;
				// $total_l=0;
				
				$grandtotal_hour = 0;
				$grandtotal_work_hour = 0;
				$grandtotal_overtime = 0;
				$grandtotal_cost = 0;
				foreach ( $rows as $k => $v ) {
					// $info = "";
					// $client = "";
					// $project = "";
					// $job_no = "";
					// $job = "";
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					// $total_overtime += $v['overtime'];
					// $total = $v['hour'] + $v['overtime'];
					// $total_all = $total_hour + $total_overtime;
					// $normal = $v['hour'] - $v['overtime'] ;
					// $overtime = $v['overtime'];
					// $total_hour +=$normal;
					// $total_l +=$v['hour'] ;
					
					/*
					 * if (count( $rows_project ) > 0 ) {
					 * foreach ($rows_project as $k1=>$v1) {
					 * $client .= $v1['client_name'] . ", ";
					 * $project .= $v1['project_no'] . ", ";
					 * }
					 *
					 * if (strlen($client) > 0) $client = substr($client, 0, strlen($client) - 2);
					 * if (strlen($project) > 0) $project = substr($project, 0, strlen($project) - 2);
					 * }
					 */
					
					$this->data ['row'] .= "     		
					<tr $class >
						<td>$i</td>
						<td colspan=10><strong>" . $v ['date'] . " - " . $v ['day_name'] . "</strong></td>
					</tr>";
					
					$rows_project = $this->reportModel->getReportEmployeeProject ( $this->data ['form'] ['employee_id'], $v ['date'] );
					
					$j = 1;
					$subtotal_hour = 0;
					$subtotal_work_hour = 0;
					$subtotal_overtime = 0;
					$subtotal_cost = 0;
					foreach ( $rows_project as $kp => $row ) {
						$this->data ['row'] .= "     		
						<tr $class >
							<td>$i." . ".$j</td>
							<td>" . $row ['client_name'] . "</td>
							<td>" . $row ['project_no'] . "</td>
							<td>" . $row ['type'] . "</td>
							<td>" . $row ['job_no'] . "</td>
							<td>" . $row ['job'] . "</td>
							<td class=currency>" . $row ['hour'] . "</td>
							<td class=currency>" . $row ['work_hour'] . "</td>
							<td class=currency>" . $row ['overtime'] . "</td>
							<td class=currency>" . number_format ( $row ['cost'], 0 ) . "</td>
							<td>" . $row ['approval'] . "</td>
						</tr>";
						$subtotal_hour += $row ['hour'];
						$subtotal_work_hour += $row ['work_hour'];
						$subtotal_overtime += $row ['overtime'];
						$subtotal_cost += $row ['cost'];
						$j ++;
					}
					
					$this->data ['row'] .= "     		
					<tr>
						<td colspan=6 class='currency'><b>Total</b></td>
						<td class=currency><b>" . $subtotal_hour . "</b></td>
						<td class=currency><b>" . $subtotal_work_hour . "</b></td>
						<td class=currency><b>" . $subtotal_overtime . "</b></td>
						<td class=currency><b>" . number_format ( $subtotal_cost, 0 ) . "</b></td> 
						<td></td>
					</tr>";
					
					// counter for grouping days
					$grandtotal_hour += $subtotal_hour;
					$grandtotal_work_hour += $subtotal_work_hour;
					$grandtotal_overtime += $subtotal_overtime;
					$grandtotal_cost += $subtotal_cost;
					$i ++;
				}
				
				$this->data ['row'] .= "     		
				  <tr>
					<td colspan=6 class='currency'><b>Grand Total</b></td>
					<td class=currency><b>" . $grandtotal_hour . "</b></td>
					<td class=currency><b>" . $grandtotal_work_hour . "</b></td>
					<td class=currency><b>" . $grandtotal_overtime . "</b></td>
					<td class=currency><b>" . number_format ( $grandtotal_cost, 0 ) . "</b></td> 
					<td></td>
				  </tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		
		$this->load->view ( 'report_employee', $this->data );
	} // END reportEmployee
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	public function reportEmployeeSummary() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportEmployeeSummary ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total = 0;
				$total_hour = 0;
				$total_overtime = 0;
				$rs_totalhour = 0;
				$v_hour = 0;
				$rs_overtime = 0;
				$total_all1 = 0;
				$normal = 0;
				foreach ( $rows as $k => $v ) {
					$info = "";
					$client = "";
					$project = "";
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					// $total_hour += $v['hour'];
					// $total_overtime += $v['overtime'];
					// $total = $v['hour'] + $v['overtime'];
					// $total_all = $total_hour + $total_overtime;
					
					$total = $v ['hour'] + $v ['overtime'];
					$total_all = $rs_totalhour + $total_overtime;
					$normal = $v ['hour'] - $v ['overtime'];
					$overtime = $total - $normal;
					$total_all1 += $v ['hour'];
					$total_hour += $normal;
					$total_overtime += $v ['overtime'];
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i </td>
      				<td>$v[employeeid]</td>
      				<td>$v[employee]</td>
      				<td>$v[project]</td>
      				<td>$v[project_no]</td>
      				<td class=currency>$v[hour]</td> 
					    <td class=currency>$normal</td> 
      				<td class=currency>$v[overtime]</td> 
					
      				
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "     		
      		<tr $class >
      				<td></td>
      				<td></td>
      				<td colspan=3 class='currency'><b>Total</b>
      				<td class=currency><b>$total_all1</b></td>
      				<td class=currency><b>$total_hour</b></td>			
					<td class=currency><b>$total_overtime</b></td> 
      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_employee_summary', $this->data );
	} // END reportEmployee
	
	/* ------------------------------------------------------------------------------------- */
	// reportTimesheetCompletion
	/* ------------------------------------------------------------------------------------- */
	function business_days($start_date, $end_date, $holidays = array()) {
		$business_days = 0;
		$current_date = strtotime ( $start_date );
		$end_date = strtotime ( $end_date );
		while ( $current_date <= $end_date ) {
			if (date ( 'N', $current_date ) < 6 && ! in_array ( date ( 'd-m-Y', $current_date ), $holidays )) {
				$business_days ++;
			}
			if ($current_date <= $end_date) {
				$current_date = strtotime ( '+1 day', $current_date );
			}
		}
		return $business_days;
	}
	
	/** 
	 * Completion All Summary Timesheet
	 */
	public function reportTimesheetCompletionSummary() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportTimesheetCompletionSummary ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total_day = 0; // day
				$total_hour_app = 0;
				$total_hour_wait = 0;
				$total_hour_re = 0;
				$total_hour_null = 0;
				
				$total_work_app = 0;
				$total_work_wait = 0;
				$total_work_re = 0;
				$total_work_null = 0;
				
				$total_ot_app = 0;
				$total_ot_wait = 0;
				$total_ot_re = 0;
				$total_ot_null = 0;
				
				$total_day_app = 0;
				$total_day_wait = 0;
				$total_day_re = 0;
				$total_day_null = 0;
				
				foreach ( $rows as $k => $v ) {
					$total_day += $v ['tday'];
					$total_hour_app += $v ['hour_app'];
					$total_hour_wait += $v ['hour_wait'];
					$total_hour_re += $v ['hour_re'];
					$total_hour_null += $v ['hour_null'];
					
					$total_work_app += $v ['work_app'];
					$total_work_wait += $v ['work_wait'];
					$total_work_re += $v ['work_re'];
					$total_work_null += $v ['work_null'];
					
					$total_ot_app += $v ['ot_app'];
					$total_ot_wait += $v ['ot_wait'];
					$total_ot_re += $v ['ot_re'];
					$total_ot_null += $v ['ot_null'];
					
					$total_day_app += $v ['day_app'];
					$total_day_wait += $v ['day_wait'];
					$total_day_re += $v ['day_re'];
					$total_day_null += $v ['day_null'];
					
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					
					$this->data ['row'] .= "     		
	      		   <tr $class >
	      				<td>$i</td>
	      				<td>$v[employeeid]</td>
	      				<td>$v[employee]</td>
	      				<td>$v[approval]</td>
	                    <td class=currency>" . Number ( $v ['tday'] ) . "</td>
	                    
						<td class=currency>" . Number ( $v ['work_app'] ) . "</td> 
						<td class=currency>" . Number ( $v ['ot_app'] ) . "</td>
	      				<td class=currency>" . Number ( $v ['hour_app'] ) . "</td>
	                    <td class=currency>" . Number ( $v ['day_app'] ) . "</td>
	                    
	      				<td class=currency>" . Number ( $v ['work_wait'] ) . "</td>
	      				<td class=currency>" . Number ( $v ['ot_wait'] ) . "</td>
	                    <td class=currency>" . Number ( $v ['hour_wait'] ) . "</td>
	                    <td class=currency>" . Number ( $v ['day_wait'] ) . "</td>
	                    
	      				<td class=currency>" . Number ( $v ['work_re'] ) . "</td>
	      				<td class=currency>" . Number ( $v ['ot_re'] ) . "</td>
	                    <td class=currency>" . Number ( $v ['hour_re'] ) . "</td>
	                    <td class=currency>" . Number ( $v ['day_re'] ) . "</td>
	                    
	      				<td class=currency>" . Number ( $v ['work_null'] ) . "</td>
	      				<td class=currency>" . Number ( $v ['ot_null'] ) . "</td>
	                    <td class=currency>" . Number ( $v ['hour_null'] ) . "</td>
	                    <td class=currency>" . Number ( $v ['day_null'] ) . "</td>
	              </tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "     		
		      		<tr $class >
		      				<td></td>
		      				<td colspan=3 class='currency'><b>Total</b>
		                    <td class=currency><b>" . Number ( $total_day ) . "</b></td>
		                    
		      				<td class=currency><b>" . Number ( $total_work_app ) . "</b></td>
		      				<td class=currency><b>" . Number ( $total_ot_app ) . "</b></td>
		      				<td class=currency><b>" . Number ( $total_hour_app ) . "</b></td>
		                    <td class=currency><b>" . Number ( $total_day_app ) . "</b></td>
		                    
		      				<td class=currency><b>" . Number ( $total_work_wait ) . "</b></td>
		      				<td class=currency><b>" . Number ( $total_ot_wait ) . "</b></td>
							<td class=currency><b>" . Number ( $total_hour_wait ) . "</b></td>
		                    <td class=currency><b>" . Number ( $total_day_wait ) . "</b></td>
		                    
		      				<td class=currency><b>" . Number ( $total_work_re ) . "</b></td>
		      				<td class=currency><b>" . Number ( $total_ot_re ) . "</b></td>
		                    <td class=currency><b>" . Number ( $total_hour_re ) . "</b></td>
		                    <td class=currency><b>" . Number ( $total_day_re ) . "</b></td>
		                    
		      				<td class=currency><b>" . Number ( $total_work_null ) . "</b></td>
		      				<td class=currency><b>" . Number ( $total_ot_null ) . "</b></td>
		                    <td class=currency><b>" . Number ( $total_hour_null ) . "</b></td>
		                    <td class=currency><b>" . Number ( $total_day_null ) . "</b></td>
		      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_timesheet_completionSummary', $this->data );
	} // END reportTimesheetCompletion
	
	public function reportTimesheetGroup() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['form'] ['department_id'] = $this->input->post ( 'department_id' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		$date_from = preg_replace ( '!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $this->data ['form'] ['date_from'] );
		$date_to = preg_replace ( '!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $this->data ['form'] ['date_to'] );
		$range_date = ((abs ( strtotime ( $date_to ) - strtotime ( $date_from ) )) / (60 * 60 * 24));
		$users = $this->reportModel->getEmployeeGroup ( $this->data ['form'] ['department_id'] );
		
		if ((strlen ( $this->data ['form'] ['date_from'] > 0 )) && ($users)) {
			$rowspan = $range_date + 4;
			$this->data ['table'] = '<thead>';
			$this->data ['table'] .= '<tr><th class="table-head" colspan="' . $rowspan . '">Timesheet Group Per Periode</th></tr>';
			$this->data ['table'] .= '<tr>
									<th>No</th>
									<th>NIK</th>
									<th>Name</th>';
			$date = $date_from;
			for($i = 0; $i <= $range_date; $i ++) {
				$styleh = 'text-align:center;';
				$newdate = strtotime ( "+$i day", strtotime ( $date ) ); // counter + 1 day
				$newdate = date ( 'd/m', $newdate ); // untuk menyimpan ke dalam
				$this->data ['table'] .= '<th style="' . $styleh . '">' . $newdate . '</th>';
			}
			$this->data ['table'] .= '</tr>';
			
			$this->data ['table'] .= '<tbody>';
			$no = 1;
			foreach ( $users as $key => $v ) {
				$class = ($no % 2 == 0) ? $class = 'class="odd"' : ' ';
				
				$this->data ['table'] .= '    		
					<tr ' . $class . '>
						<td>' . $no . '</td>
						<td>' . $v ['name'] . '</td>
						<td>' . $v ['EmployeeID'] . '</td>';
				
				$date = $date_from;
				for($i = 0; $i <= $range_date; $i ++) {
					$newdate = strtotime ( "+$i day", strtotime ( $date ) ); // counter + 1 day
					                                                         // $newdate = date('d/m',$newdate); //untuk menyimpan ke dalam
					$timesheetdate = date ( 'Y-m-d', $newdate ); // untuk menyimpan ke dalam
					$timesheet = $this->reportModel->getTimesheetValue ( $timesheetdate, $v ['employee_id'] );
					$holiday = $this->reportModel->getHoliday ( $timesheetdate );
					
					$style = 'text-align:center;';
					$weekend = date ( "D", $newdate );
					if (($weekend == 'Sat') || ($weekend == 'Sun') || (count ( $holiday ) > 0))
						$style .= 'background:red;font-weight:bold;';
					
					if ($timesheet) {
						if ($timesheet ['job_id'] == 499)
							$style .= 'color:blue;';
					}
					
					$this->data ['table'] .= '<td style="' . $style . '">' . ($timesheet ? $timesheet ['hour'] : '') . '</td>';
				}
				$this->data ['table'] .= '</tr>';
				$no ++;
			}
			
			$this->data ['table'] .= '</tbody>';
		} else {
			$this->data ['table'] = '';
		}
		$this->load->view ( 'report_timesheet_completion_group', $this->data );
	} // END reportTimesheetCompletion
	
	public function reportProjectGroup() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['form'] ['department_id'] = $this->input->post ( 'department_id' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['department'] = $this->reportModel->getDepartmentById($this->data ['form'] ['department_id']);
		$this->data ['department_name'] = $this->data ['department'] ? $this->data ['department']['department'] : ""; 
		$this->data ['row'] = "";
	
		//$date_from = preg_replace ( '!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $this->data ['form'] ['date_from'] );
		//$date_to = preg_replace ( '!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $this->data ['form'] ['date_to'] );
		$users = $this->reportModel->getEmployeeGroup($this->data ['form'] ['department_id']);
	
		if ((strlen ( $this->data ['form'] ['date_from'] > 0 )) && ($users)) {
			$no = 1;
			$i = 1;
			$this->data['table'] = "";
			foreach($users as $key => $user ) {
				$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
				$this->data['table'].= '<tr ' . $class . '>';
				$this->data['table'].= '<td>'.$no.'</td>';
				$this->data['table'].= '<td>'.$user["name"].'</td>';
				$this->data['table'].= '<td>'.$user["EmployeeID"].'</td>';
				$this->data['table'].= '<td>'.$user["EmployeeTitle"].'</td>';
				$this->data['table'].= '<td>'.$user["EmployeeHireDate"].'</td>';
				$this->data['table'].= '<td></td>';
				$this->data['table'].= '<td></td>';
				$this->data['table'].= '<td></td>';
				$this->data['table'].= '</tr>';
				$no ++;
				
				//project
				$projects = $this->reportModel->getTimesheetSummaryByProject($user['employee_id'],$this->data ['form'] ['date_from'],$this->data ['form'] ['date_to']);
				$i++;
				$total_work = 0;
				$total_overtime = 0;
				$total_hour = 0;
				
				foreach($projects as $key => $project ) {
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : '';
					$this->data['table'].= '<tr ' . $class . '>';
					$this->data['table'].= '<td></td>';
					$this->data['table'].= '<td colspan="4">'.$project["project_no"].' '.$project['client_name'].'</td>';
					$this->data['table'].= '<td class="currency">'.number_format($project["work_hour"]).'</td>';
					$this->data['table'].= '<td class="currency">'.number_format($project["overtime_hour"]).'</td>';
					$this->data['table'].= '<td class="currency">'.number_format($project["total_hour"]).'</td>';
					$this->data['table'].= '</tr>';
					$i++;		
					$total_work+=$project["work_hour"];
					$total_overtime+=$project["overtime_hour"];
					$total_hour+=$project["total_hour"];
				}
				
				$this->data['table'].= '<tr ' . $class . '>';
				$this->data['table'].= '<td></td>';
				$this->data['table'].= '<td class="currency" colspan="4">Subtotal</td>';
				$this->data['table'].= '<td class="currency">'.number_format($total_work).'</td>';
				$this->data['table'].= '<td class="currency">'.number_format($total_overtime).'</td>';
				$this->data['table'].= '<td class="currency">'.number_format($total_hour).'</td>';
				$this->data['table'].= '</tr>';
				
				$this->data['table'].= '<tr ' . $class . '>';
				$this->data['table'].= '<td colspan="8" style="padding:10px"></td>';
				$this->data['table'].= '</tr>';
				
				
				
			}
				
			
		} else {
			$this->data ['table'] = '';
		}
		$this->load->view ( 'report_project_group', $this->data );
	} // END reportTimesheetCompletion
	
	
	public function reportAllowance() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['form'] ['department_id'] = $this->input->post ( 'department_id' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['department'] = $this->reportModel->getDepartmentById($this->data ['form'] ['department_id']);
		$this->data ['department_name'] = $this->data ['department'] ? $this->data ['department']['department'] : "";
		//$date_from = preg_replace ( '!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $this->data ['form'] ['date_from'] );
		//$date_to = preg_replace ( '!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $this->data ['form'] ['date_to'] );
		$records = $this->reportModel->getAllowance($this->data ['form']);
	
		if ((strlen ( $this->data ['form'] ['date_from'] > 0 )) && ($records)) {
			$no = 1;
			$i = 1;
			$total_days = 0;
			$total_employee = 0;
			$total_cost = 0;
			$this->data['table'] = "";
			foreach($records as $key => $rec ) {
				$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
				$this->data['table'].= '<tr ' . $class . '>';
				$this->data['table'].= '<td>'.$no.'</td>';
				$this->data['table'].= '<td>'.$rec["date_from"].' - '.$rec["date_to"].'</td>';
				$this->data['table'].= '<td class="currency">'.$rec["total_day"].'</td>';
				$this->data['table'].= '<td>'.$rec["client_name"].'</td>';
				$this->data['table'].= '<td>'.$rec["project_no"].'</td>';
				$this->data['table'].= '<td>'.$rec["approval_name"].'</td>';
				$this->data['table'].= '<td class="currency">'.$rec["total_employee"].'</td>';
				$this->data['table'].= '<td>'.$rec["date_realization"].'</td>';
				$this->data['table'].= '<td>'.$rec["date_approved"].'</td>';
				$this->data['table'].= '<td>'.($rec["date_approved"] == '-'?'Waiting':'Approved').'</td>';
				$this->data['table'].= '<td class="currency">'.number_format($rec["total"],2).'</td>';
				$this->data['table'].= '</tr>';
				$no ++;
				$total_days+=$rec['total_day'];
				$total_employee+=$rec['total_employee'];
				$total_cost+=$rec['total'];
			}
			//total summary
			$this->data['table'].= '<tr ' . $class . '>';
			$this->data['table'].= '<td class="currency" colspan="2">Total Days</td>';
			$this->data['table'].= '<td class="currency">'.number_format($total_days).'</td>';
			$this->data['table'].= '<td class="currency" colspan="3">Total Employee</td>';
			$this->data['table'].= '<td class="currency">'.number_format($total_employee).'</td>';
			$this->data['table'].= '<td class="currency" colspan="3">Total Cost</td>';
			$this->data['table'].= '<td class="currency">'.number_format($total_cost,2).'</td>';
			$this->data['table'].= '</tr>';
			
			
				
		} else {
			$this->data ['table'] = '';
		}
		$this->load->view ( 'report_allowance', $this->data );
	} 
	
	function reportTimesheetCompletion() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportTimesheetCompletion ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total = 0;
				$total_hour = 0;
				$total_overtime = 0;
				$normal = 0;
				$overtime = 0;
				$total_w = 0;
				$normal_w = 0;
				$overtime_w = 0;
				$total_hour_w = 0;
				$total_overtime_w = 0;
				$total_all_w = 0;
				$v_hour = 0;
				$normal_y = 0;
				$total_hour_y = 0;
				$day = 0;
				$v_approv = 0;
				$total_all_y = 0;
				$day_all_y = 0;
				$total_y = 0;
				$Tot_day = 0;
				$harikerja = 0;
				$total_all = 0;
				foreach ( $rows as $k => $v ) {
					$info = "";
					$client = "";
					$project = "";
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					
					$v_approv = $v ['timesheet_approval'];
					if ($v_approv == '2') {
						$total_overtime += $v ['overtime'];
						$total = $v ['hour'];
						$normal = $v ['hour'] - $v ['overtime'];
						$overtime = $v ['overtime'];
						$total_hour += $normal;
						$total_all = $total_hour + $total_overtime;
					} else if ($v_approv == '1') {
						
						$total_overtime_w += $v ['overtime'];
						$total_w = $v ['hour'];
						
						$normal_w = $v ['hour'] - $v ['overtime'];
						$overtime_w = $v ['overtime'];
						$total_hour_w += $normal_w;
						$total_all_w = $total_hour_w + $total_overtime_w;
					}
					
					$harikerja = ($normal + $normal_w);
					$start_date = $this->data ['form'] ['date_from'];
					$end_date = $this->data ['form'] ['date_to'];
					$businessDays = $this->business_days ( $start_date, $end_date ) * 8;
					$Tot_day += $businessDays;
					$total_y = $businessDays - $harikerja;
					$day = $total_y / 8;
					$total_all_y += $total_y;
					$day_all_y += $day;
					
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i </td>
      				<td>$v[employeeid]</td>
      				<td>$v[employee]</td>
      				<td>$v[approval]</td>
      				<td class=currency>$total</td>
					<td class=currency>$normal</td> 
					<td class=currency>$overtime</td>
      				<td class=currency>$total_w</td>
      				<td class=currency>$normal_w</td>
      				<td class=currency>$overtime_w</td>
					<td class=currency>$businessDays</td>
      				<td class=currency>$total_y</td>
      				<td class=currency>$day</td>
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "     		
      		<tr $class >
      				<td></td>
      				<td colspan=3 class='currency'><b>Total</b>
      				<td class=currency><b>$total_all</b></td>
      				<td class=currency><b>$total_hour</b></td>
      				<td class=currency><b>$total_overtime</b></td>
      				<td class=currency><b>$total_all_w</b></td>
      				<td class=currency><b>$total_hour_w</b></td>
      				<td class=currency><b>$total_overtime_w</b></td>
					<td class=currency><b>$Tot_day</b></td>
      				<td class=currency><b>$total_all_y</b></td>
      				<td class=currency><b>$day_all_y</b></td>
      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_timesheet_completion', $this->data );
	} // END reportTimesheetCompletion
	  
	// ==================================================================================================================================//
	function reportTimesheetCompletionW() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportTimesheetCompletion2 ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total = 0;
				$total_hour = 0;
				$total_overtime = 0;
				$normal = 0;
				$overtime = 0;
				$total_w = 0;
				$normal_w = 0;
				$overtime_w = 0;
				$total_hour_w = 0;
				$total_overtime_w = 0;
				$total_all_w = 0;
				$v_hour = 0;
				$normal_y = 0;
				$total_hour_y = 0;
				$day = 0;
				$v_approv = 0;
				$total_all_y = 0;
				$day_all_y = 0;
				$total_y = 0;
				$Tot_day = 0;
				$harikerja = 0;
				$total_all = 0;
				foreach ( $rows as $k => $v ) {
					$info = "";
					$client = "";
					$project = "";
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					
					$v_approv = $v ['timesheet_approval'];
					if ($v_approv == '2') {
						
						$total_hour += $v ['hour'];
						$total_overtime += $v ['overtime'];
						$total = $v ['hour'];
						$total_all = $total_hour + $total_overtime;
						$normal = $v ['hour'] - $v ['overtime'];
						$overtime = $v ['overtime'];
					} 

					else if ($v_approv == '1') {
						
						$total_overtime_w += $v ['overtime'];
						$total_w = $v ['hour'];
						$normal_w = $v ['hour'] - $v ['overtime'];
						$overtime_w = $v ['overtime'];
						$total_hour_w += $normal_w;
						$total_all_w = $total_hour_w + $total_overtime_w;
					}
					$harikerja = ($normal + $normal_w);
					$start_date = $this->data ['form'] ['date_from'];
					$end_date = $this->data ['form'] ['date_to'];
					$businessDays = $this->business_days ( $start_date, $end_date ) * 8;
					$Tot_day += $businessDays;
					$total_y = $businessDays - $harikerja;
					$day = $total_y / 8;
					$total_all_y += $total_y;
					$day_all_y += $day;
					
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i </td>
      				<td>$v[employeeid]</td>
      				<td>$v[employee]</td>
      				<td>$v[approval]</td>
      				<td class=currency>$total</td>
					<td class=currency>$normal</td> 
					<td class=currency>$overtime</td>
      				<td class=currency>$total_w</td>
      				<td class=currency>$normal_w</td>
      				<td class=currency>$overtime_w</td>
					<td class=currency>$businessDays</td>
      				<td class=currency>$total_y</td>
      				<td class=currency>$day</td>
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "     		
      		<tr $class >
      				<td></td>
      				<td colspan=3 class='currency'><b>Total</b>
      				<td class=currency><b></b></td>
      				<td class=currency><b></b></td>
      				<td class=currency><b></b></td>
      				<td class=currency><b>$total_all_w</b></td>
      				<td class=currency><b>$total_hour_w</b></td>
      				<td class=currency><b>$total_overtime_w</b></td>
					<td class=currency><b>$Tot_day</b></td>
      				<td class=currency><b>$total_all_y</b></td>
      				<td class=currency><b>$day_all_y</b></td>
      				
      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_timesheet_completion_waiting', $this->data );
	} // END reportTimesheetCompletion
	  
	// =================================================================================================//
	function reportEmployeeOvertime() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportEmployeeOvertime ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total_overtime = 0;
				
				foreach ( $rows as $k => $v ) {
					$info = "";
					$client = "";
					$project = "";
					$mic = "";
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					$total_overtime += $v ['overtime'];
					
					/*
					 * $rows_project = $this->reportModel->getReportEmployeeProjectOvertime($v['employee_id'],$v['date']);
					 * if ( count( $rows_project ) > 0 ) {
					 * foreach ($rows_project as $k1=>$v1) {
					 * $client .= $v1['client_name'] . ",<br> ";
					 * $project .= $v1['project_no'] . ",<br> ";
					 * $mic .= $v1['employee'] . ",<br> ";
					 * }
					 * if (strlen($client) > 0) $client = substr($client, 0, strlen($client) - 6);
					 * if (strlen($project) > 0) $project = substr($project, 0, strlen($project) - 6);
					 * if (strlen($mic) > 0) $mic = substr($mic, 0, strlen($mic) - 6);
					 * }
					 */
					
					$this->data ['row'] .= "     		
					<tr $class >
							<td>$i </td>
							<td>$v[employeeid]</td>
							<td>$v[employee]</td>
                            <td>$v[employeetitle]</td>
                            <td>$v[department]</td>
							<!--<td>$project</td>
							<td>$client</td>
							<td>$mic</td>-->
                            <td class=currency>$v[overday]</td>
							<td class=currency>$v[overtime]</td>
					</tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "     		
      		<tr>
      				<td colspan='6' class=currency><b>Total  </b></td>
      				<td class=currency><b>$total_overtime</b></td>
      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_employee_overtime', $this->data );
	} // END reportEmployee
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportEmployeeAbsent() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->session->set_userdata ( 'date_from', $this->data ['form'] ['date_from'] );
		$this->session->set_userdata ( 'date_to', $this->data ['form'] ['date_to'] );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportEmployeeAbsent ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total_sakit = 0;
				$total_leave = 0;
				
				foreach ( $rows as $k => $v ) {
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					$total_sakit += $v ['countsakit'];
					$total_leave += $v ['countonleave'];
					
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i </td>
      				<td>$v[employeeid]</td>
      				<td>$v[employeefirstname] $v[employeemiddlename] $v[employeelastname]</td>
					<td>$v[hari]</td>
      				<td>$v[date]</td>
      				<td>$v[sakit]</td>
      				<td>$v[onleave]</td>
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "     		
      		<tr>
      				<td colspan=5 class='currency'><b>Total</b>
      				<td><b>$total_sakit</b></td>
      				<td><b>$total_leave</b></td>
      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_employee_absent', $this->data );
	} // END reportEmployee
	  
	// ** Summary Absent **/
	function reportEmployeeAbsentSummaryExcel() {
		$this->load->library ( 'PHPExcel' );
		$objPHPExcel = new PHPExcel ();
		$objWriter = new PHPExcel_Writer_Excel2007 ( $objPHPExcel, "Excel2007" );
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		
		/**
		 * Page Setup *
		 */
		$objWorksheet->getPageSetup ()->setOrientation ( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
		$objWorksheet->getPageSetup ()->setPaperSize ( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5 );
		$objWorksheet->getPageSetup ()->setScale ( 93 );
		$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Trebuchet MS' )->setSize ( 8 );
		/**
		 * Page Border *
		 */
		$border = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN 
						) 
				) 
		);
		$fill = array (
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'rotation' => 0,
				'startcolor' => array (
						'rgb' => 'CCCCCC' 
				),
				'endcolor' => array (
						'argb' => 'CCCCCC' 
				) 
		);
		// We'll be outputting an excel file
		$data ['date_from'] = $this->session->userdata ( 'date_from' );
		$data ['date_to'] = $this->session->userdata ( 'date_to' );
		$users = $this->reportModel->getAbsentByEmployeeSummary ( $data );
		
		$col = 0;
		$row = 1;
		$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Laporan Absen' );
		$row ++;
		$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Periode :' . $data ['date_from'] . ' / ' . $data ['date_to'] );
		
		$row ++;
		$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Name' );
		$objWorksheet->getColumnDimensionByColumn ( $col )->setWidth ( 30 );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row + 2 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 0, $row + 0, $col + 0, $row + 2 );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, 'NIK' );
		$objWorksheet->getColumnDimensionByColumn ( $col + 1 )->setWidth ( 10 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row + 2 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 1, $row + 0, $col + 1, $row + 2 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, 'TOTAL ABSEN' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 2, $row + 0, $col + 8, $row + 0 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 8, $row )->applyFromArray ( $border );
		
		$row ++;
		$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, "Cuti \n Tahunan" );
		$objWorksheet->getColumnDimensionByColumn ( $col + 2 )->setWidth ( 15 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 2, $row + 0, $col + 2, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getFill ()->applyFromArray ( $fill );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 3, $row, "Cuti \n Bersama" );
		$objWorksheet->getColumnDimensionByColumn ( $col + 3 )->setWidth ( 15 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 3, $row + 0, $col + 3, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getFill ()->applyFromArray ( $fill );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4, $row, "Cuti \n Tanggugan" );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 )->setWidth ( 15 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 4, $row + 0, $col + 4, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getFill ()->applyFromArray ( $fill );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 5, $row, "Cuti \n Khusus" );
		$objWorksheet->getColumnDimensionByColumn ( $col + 5 )->setWidth ( 15 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 5, $row + 0, $col + 5, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->getFill ()->applyFromArray ( $fill );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, "Sakit" );
		$objWorksheet->getColumnDimensionByColumn ( $col + 6 )->setWidth ( 15 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 6, $row + 0, $col + 6, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getFill ()->applyFromArray ( $fill );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 7, $row, "Izin" );
		$objWorksheet->getColumnDimensionByColumn ( $col + 7 )->setWidth ( 15 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 7, $row + 0, $col + 7, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getFill ()->applyFromArray ( $fill );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 8, $row, "Haid" );
		$objWorksheet->getColumnDimensionByColumn ( $col + 8 )->setWidth ( 15 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 8, $row + 0, $col + 8, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 8, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 8, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 8, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 8, $row )->getFill ()->applyFromArray ( $fill );
		
		$row = $row + 2;
		foreach ( $users as $u ) :
			/**
			 * Name *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 0, $row, $u ['employeefirstname'] . ' ' . $u ['employeemiddlename'] . ' ' . $u ['employeelastname'] );
			$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->applyFromArray ( $border );
			/**
			 * NIK *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, $u ['employeeid'] );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
			/**
			 * Cuti Tahunan *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, $u ['cuti_tahunan'] > 0 ? $u ['cuti_tahunan'] : "" );
			$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
			/**
			 * Cuti Bersama *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 3, $row, $u ['cuti_bersama'] > 0 ? $u ['cuti_bersama'] : "" );
			$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
			/**
			 * Cuti Tanggungan *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4, $row, $u ['cuti_tanggungan'] > 0 ? $u ['cuti_tanggungan'] : "" );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->applyFromArray ( $border );
			/**
			 * Cuti Khusus *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 5, $row, $u ['cuti_khusus'] > 0 ? $u ['cuti_khusus'] : "" );
			$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->applyFromArray ( $border );
			/**
			 * Sakit *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, $u ['sakit'] > 0 ? $u ['sakit'] : "" );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border );
			/**
			 * Izin *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 7, $row, $u ['izin'] > 0 ? $u ['izin'] : "" );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
			/**
			 * Haid *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 8, $row, $u ['haid'] > 0 ? $u ['haid'] : "" );
			$objWorksheet->getStyleByColumnAndRow ( $col + 8, $row )->applyFromArray ( $border );
			$row ++;
		endforeach
		;
		
		$objWriter->save ( "./media/Absent-Summary.xlsx" );
		redirect ( '../media/Absent-Summary.xlsx' );
	}
	// ** End Of Summary Absent **/
	function reportEmployeeAbsentExcel() {
		$this->load->library ( 'PHPExcel' );
		$objPHPExcel = new PHPExcel ();
		$objWriter = new PHPExcel_Writer_Excel2007 ( $objPHPExcel, "Excel2007" );
		$objPHPExcel->getProperties ()->setTitle ( "Mantap" )->setDescription ( "description" );
		$objPHPExcel->setActiveSheetIndex ( 0 );
		
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		$objWorksheet->getPageSetup ()->setOrientation ( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
		$objWorksheet->getPageSetup ()->setPaperSize ( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5 );
		$objWorksheet->getPageSetup ()->setScale ( 93 );
		
		$border = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN 
						) 
				) 
		);
		$fill = array (
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'rotation' => 0,
				'startcolor' => array (
						'rgb' => 'CCCCCC' 
				),
				'endcolor' => array (
						'argb' => 'CCCCCC' 
				) 
		);
		$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Trebuchet MS' )->setSize ( 8 );
		$objWorksheet = $objPHPExcel->setActiveSheetIndex ();
		// We'll be outputting an excel file
		$data ['date_from'] = $this->session->userdata ( 'date_from' );
		$data ['date_to'] = $this->session->userdata ( 'date_to' );
		$users = $this->reportModel->getAbsentByEmployee ( $data );
		
		$col = 0;
		$row = 1;
		$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Name' );
		$objWorksheet->getColumnDimensionByColumn ( $col )->setWidth ( 30 );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, 'NIK' );
		$objWorksheet->getColumnDimensionByColumn ( $col + 1 )->setWidth ( 10 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 1, $row, $col + 1, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$row ++;
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
		
		$year_start = substr ( $data ['date_from'], 6, 4 );
		$month_start = substr ( $data ['date_from'], 3, 2 );
		$day_start = substr ( $data ['date_from'], 0, 2 );
		
		$year_end = substr ( $data ['date_to'], 6, 4 );
		$month_end = substr ( $data ['date_to'], 3, 2 );
		$day_end = substr ( $data ['date_to'], 0, 2 );
		
		$total = getRangeDate ( $day_start, $month_start, $year_start, $day_end, $month_end, $year_end );
		$x = 1;
		$y = 1;
		for($i = 0; $i <= $total; $i ++) :
			$date = $year_start . '-' . $month_start . '-' . $day_start;
			$ndate = date ( "d-M", strtotime ( "$date +$i day" ) );
			$objWorksheet->setCellValueByColumnAndRow ( $col + 1 + ($x), $row - 1, $ndate );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($x), $row - 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($x), $row - 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->mergeCellsByColumnAndRow ( $col + 1 + ($x), $row - 1, $col + 1 + ($x + 1), $row - 1 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 1 + ($y), $row, "Sakit" );
			$objWorksheet->getColumnDimensionByColumn ( $col + 1 + ($y) )->setWidth ( 5 );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->getFill ()->applyFromArray ( $fill );
			$y ++;
			$objWorksheet->setCellValueByColumnAndRow ( $col + 1 + ($y), $row, "Izin" );
			$objWorksheet->getColumnDimensionByColumn ( $col + 1 + ($y) )->setWidth ( 4 );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->getFill ()->applyFromArray ( $fill );
			$x = $x + 2;
			$y ++;
		endfor
		;
		
		$col = 0;
		$row ++;
		foreach ( $users as $user => $u ) :
			$objWorksheet->setCellValueByColumnAndRow ( $col + 0, $row, $u ['employeefirstname'] . ' ' . $u ['employeemiddlename'] . ' ' . $u ['employeelastname'] );
			$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->applyFromArray ( $border );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, $u ['employeeid'] );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
			
			// $x=1;
			$y = 1;
			for($i = 0; $i <= $total; $i ++) :
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 1 + ($y), $row, $u ['S' . $i] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->applyFromArray ( $border );
				
				$y ++;
				$objWorksheet->setCellValueByColumnAndRow ( $col + 1 + ($y), $row, $u ['I' . $i] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1 + ($y), $row )->applyFromArray ( $border );
				
				$y ++;
			endfor
			;
			$row ++;
		endforeach
		;
		$objWriter->save ( "./media/Absent.xlsx" );
		// force_download("Transport",$file);
		redirect ( '../media/Absent.xlsx' );
	}
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployeeAbsenWeek
	/* ------------------------------------------------------------------------------------- */
	function reportEmployeeTotal() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportEmployeeTotal ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total_sakit = 0;
				$total_leave = 0;
				
				foreach ( $rows as $k => $v ) {
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					$total_sakit += $v ['countsakit'];
					$total_leave += $v ['countonleave'];
					
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i</td>
      				<td>$v[employeeid]</td>
      				<td>$v[employeefirstname] $v[employeemiddlename] $v[employeelastname]</td>
							<td>$v[EmployeeTitle]</td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td></td>
      				<td>$v[countcuti]</td>
      				<td>$v[countsakit]</td>
      				<td></td>
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "     		
      		<tr>
      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_employee_total', $this->data );
	} // END reportEmployee
	function test() {
		$y = 34;
		$x = 34;
		for($is = $y; $is <= $x; $is ++) :
			if ($is <= 52) {
				echo $i = $is;
			} else {
				echo $i = $is - 52;
			}
		endfor
		;
	}
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployeeWeek
	/* ------------------------------------------------------------------------------------- */
	public function reportEmployeeWeek() {
		//phpinfo();
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['form'] ['week'] = $this->input->post ( 'week' );
		$this->data ['form'] ['week2'] = $this->input->post ( 'week2' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		if ($this->data ['form'] ['date_from']) :
			$arr = array (
					'wdate_from' => $this->data ['form'] ['date_from'],
					'wdate_to' => $this->data ['form'] ['date_to'],
					'wweek' => $this->data ['form'] ['week'],
					'wweek2' => $this->data ['form'] ['week2'] 
			);
			$this->session->set_userdata ( $arr );
		endif;
		
		$xstart = $this->data ['form'] ['week'];
		$xend = $this->data ['form'] ['week2'];
		$xyear = substr ( $this->data ['form'] ['date_from'], 6, 4 );
		
		if (! $xstart) {
			$xstart = 1;
			$xend = 1;
			$xyear = date ( 'Y' );
		}
		
		// select year
		switch ($xyear) {
			case "2009" :
				$xmin = 53;
				break;
			case "2010" :
				$xmin = 52;
				break;
			case "2011" :
				$xmin = 52;
				break;
			case "2012" :
				$xmin = 52;
				break;
			case "2013" :
				$xmin = 52;
				break;
			case "2014" :
				$xmin = 52;
				break;
			case "2015" :
				$xmin = 53;
				break;
			case "2016" :
				$xmin = 52;
				break;
			case "2017" :
				$xmin = 52;
				break;
			case "2018" :
				$xmin = 52;
				break;
			case "2019" :
				$xmin = 52;
				break;
			case "2020" :
				$xmin = 53;
				break;
			default :
				$xmin = 52;
				break;
		}
		
		if (($xstart <= $xend)) {
			$xstart = $xstart;
			$xend = $xend;
		} else {
			$xstart = $xstart; // 52
			$xend = $xend + $xmin; // 3 + 52
		}
		
		$this->data ['y'] = $xstart;
		$this->data ['x'] = $xend;
		
		$table = '';
		
		if ($this->session->userdata ( 'wdate_from' )) {
			$department = array (
					'KAP TSFR',
					'PT BDO KONSULTAN INDONESIA',
					'PT BDO MANAJEMEN INDONESIA' 
			);
			$x = $this->data ['x'] - $this->data ['y'];
			foreach ( $department as $key ) {
				$table .= '<tr>';
				$table .= '<td colspan="' . (9 + (($x * 9) + 13)) . '" >' . $key . '</td>';
				$table .= '</tr>';
				
				$employee = $this->reportModel->getEmployeeWeek ( $key );
				
				$no = 1;
				foreach ( $employee as $k => $v ) {
					$table .= '<tr>';
					$table .= '<td>' . $no . '</td>';
					$table .= '<td>' . $v ['employeeid'] . '</td>';
					$table .= '<td >' . $v ['employeename'] . '</td>';
					$table .= '<td>' . $v ['employeetitle'] . '</td>';
					
					$tdk = 0;
					$tlk = 0;
					$tss = 0;
					$ts = 0;
					$tij = 0;
					$tc = 0;
					$tli = 0;
					$tot = 0;
					$ttk = 0;
					
					for($i = $this->data ['y']; $i <= $this->data ['x']; $i ++) {
						$week = $i;
						$year = $xyear;
						
						if ($i > $xmin) {
							// if 53 -52 = 1, 54-52 = 2 ..etc
							$week = $i - $xmin;
							$year = $xyear + 1;
						}
						
						$days = array (
								0 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "1" ) ),
								1 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "2" ) ),
								2 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "3" ) ),
								3 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "4" ) ),
								4 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "5" ) ) 
						);
						
						$timesheetdate = '';
						$dk = 0;
						$lk = 0;
						$ss = 0;
						$s = 0;
						$sdk = 0;
						$slk = 0;
						$ij = 0;
						$c = 0;
						$cdk = 0;
						$clk = 0;
						$li = 0;
						$lidk = 0;
						$lilk = 0;
						$ot = 0;
						$tk = 0;
						$tk_mon = $days [0];
						$tk_tue = $days [1];
						$tk_wed = $days [2];
						$tk_thu = $days [3];
						$tk_fri = $days [4];
						$tkdescription = "TK : ";
						
						$rows = $this->reportModel->getEmployeeWeekDetails ( $v ['employee_id'], $week, $year );
						
						foreach ( $rows as $key => $row ) {
							switch ($row ["timesheetdate"]) {
								case $days [0] :
									$tk_mon = "";
									break;
								case $days [1] :
									$tk_tue = "";
									break;
								case $days [2] :
									$tk_wed = "";
									break;
								case $days [3] :
									$tk_thu = "";
									break;
								case $days [4] :
									$tk_fri = "";
									break;
							}
							
							// $day_name = date ( "D", strtotime ( $row ['timesheetdate'] ) );
							// Dalam Kota
							if (($row ['transport_type'] < 3) && ($row ["JOBTYPE"] != "HRD")) {
								if ($timesheetdate != $row ['timesheetdate'])
									$dk += 1;
							}
							
							// Luar Kota
							if (($row ['transport_type'] == 3) && ($row ['JOBTYPE'] != 'HRD')) {
								if ($timesheetdate != $row ['timesheetdate'])
									$lk += 1;
							}
							
							// count of sakit / sick
							if (($row ['job_id'] == 470)) {
								$ss += $row ['hour'];
							}
							
							// count of sakit / sick
							if (($row ['job_id'] <= 3) && ($row ['hour'] >= 4)) {
								$s += 8;
							}
							
							// count of izin
							if (($row ["hour"] < 4) and (($row ["job_id"] >= 4 and $row ["job_id"] <= 9) or $row ["job_id"] == 17)) {
								$ij += $row ["hour"];
							}
							
							// count of cuti
							if (($row ["hour"] >= 4) and (($row ["job_id"] >= 4 and $row ["job_id"] <= 9) or $row ["job_id"] == 17 or ($row ["job_id"] >= 10 and $row ["job_id"] <= 12))) {
								$c += 8;
							}
							
							// count of libur
							if ($row ["job_id"] == 499) {
								$li += 8;
							}
							
							if ($row ["overtime"] > 0)
								$ot += $row ["overtime"];
							
							$timesheetdate = $row ['timesheetdate'];
						}
						
						$s = ceil ( $s > 0 ? ($s / 8) : 0 );
						$c = ceil ( $c > 0 ? ($c / 8) : 0 );
						$li = ceil ( $li > 0 ? ($li / 8) : 0 );
						
						$total_week = $dk + $lk + $s + $c + $li;
						if ($total_week <= 5)
							$tk = 5 - $total_week;
						if ($tk < 1)
							$tkdescription = "";
						else
							$tkdescription .= $tk_mon . " " . $tk_tue . " " . $tk_wed . " " . $tk_thu . " " . $tk_fri;
						
						$table .= '<td class="center">' . $dk . '</td>';
						$table .= '<td class="center">' . $lk . '</td>';
						$table .= '<td class="center">' . $ss . '</td>';
						$table .= '<td class="center">' . $s . '</td>';
						$table .= '<td class="center">' . $ij . '</td>';
						$table .= '<td class="center">' . $c . '</td>';
						$table .= '<td class="center">' . $li . '</td>';
						$table .= '<td class="center">' . $tk . '</td>';
						$table .= '<td class="center">' . $ot . '</td>';
						
						$tdk += ($dk > 0 ? $dk : 0);
						$tlk += ($lk > 0 ? $lk : 0);
						$tss += ($ss > 0 ? $ss : 0);
						$ts += ($s > 0 ? $s : 0);
						$tij += ($ij > 0 ? $ij : 0);
						$tc += ($c > 0 ? $c : 0);
						$tli += ($li > 0 ? $li : 0);
						$tot += ($ot > 0 ? $ot : 0);
						$ttk += ($tk > 0 ? $tk : 0);
					}
					
					$table .= '<td class="center">' . $tdk . '</td>';
					$table .= '<td class="center">' . $tlk . '</td>';
					$table .= '<td class="center">' . $tss . '</td>';
					$table .= '<td class="center">' . $ts . '</td>';
					$table .= '<td class="center">' . $tij . '</td>';
					$table .= '<td class="center">' . $tc . '</td>';
					$table .= '<td class="center">' . $tli . '</td>';
					$table .= '<td class="center">' . $ttk . '</td>';
					$table .= '<td class="center">' . $tot . '</td>';
					$table .= '';
					$table .= '</tr>';
					
					$table .= '</tr>';
					$no ++;
				}
			}
		}
		
		$this->data ['content_report'] = $table;
		$this->data ['xmin'] = $xmin;
		if ($this->data ['form'] ['date_from'])
			$this->data ['holidays'] = $this->reportModel->getReportHolidayWeek ( $this->data ['form'] );
		$this->load->view ( 'report_employee_week', $this->data );
	}
	// END reportEmployee
	
	/* ------------------------------------------------------------------------------------- */
	// report Excell
	/* ------------------------------------------------------------------------------------- */
	function reportEmployeeWeekExcel() {
		/**
		 * Data *
		 */
		$this->data ['form'] ['date_from'] = $this->session->userdata ( 'wdate_from' );
		$this->data ['form'] ['date_to'] = $this->session->userdata ( 'wdate_to' );
		$this->data ['form'] ['week'] = $this->session->userdata ( 'wweek' );
		$this->data ['form'] ['week2'] = $this->session->userdata ( 'wweek2' );
		
		$start = $this->data ['form'] ['week'];
		$end = $this->data ['form'] ['week2'];
		$xyear = substr ( $this->data ['form'] ['date_from'], 6, 4 );
		
		if (! $start) {
			$start = 0;
			$end = 0;
		}
		
		// select year
		switch ($xyear) {
			case "2009" :
				$xmin = 53;
				break;
			case "2010" :
				$xmin = 52;
				break;
			case "2011" :
				$xmin = 52;
				break;
			case "2012" :
				$xmin = 52;
				break;
			case "2013" :
				$xmin = 52;
				break;
			case "2014" :
				$xmin = 52;
				break;
			case "2015" :
				$xmin = 53;
				break;
			case "2016" :
				$xmin = 52;
				break;
			case "2017" :
				$xmin = 52;
				break;
			case "2018" :
				$xmin = 52;
				break;
			case "2019" :
				$xmin = 52;
				break;
			case "2020" :
				$xmin = 53;
				break;
			default :
				$xmin = 52;
				break;
		}
		
		if (($start <= $end)) {
			$start = $start;
			$end = $end;
		} else {
			$start = $start;
			$end = $end + $xmin;
		}
		
		$this->load->library ( 'PHPExcel' );
		$objPHPExcel = new PHPExcel ();
		$objWriter = new PHPExcel_Writer_Excel2007 ( $objPHPExcel, "Excel2007" );
		$objPHPExcel->getProperties ()->setTitle ( "Mantap" )->setDescription ( "description" );
		$objPHPExcel->setActiveSheetIndex ( 0 );
		
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		$objWorksheet->getPageSetup ()->setOrientation ( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
		$objWorksheet->getPageSetup ()->setPaperSize ( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5 );
		$objWorksheet->getPageSetup ()->setScale ( 93 );
		
		$border = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN 
						) 
				) 
		);
		$fill = array (
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'rotation' => 0,
				'startcolor' => array (
						'rgb' => 'CCCCCC' 
				),
				'endcolor' => array (
						'argb' => 'CCCCCC' 
				) 
		);
		
		$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Trebuchet MS' )->setSize ( 8 );
		
		$col = 0;
		$row = 1;
		
		$objWorksheet->setCellValueByColumnAndRow ( $col, $row, "EMPLOYEE REPORT BY WEEK" );
		$objWorksheet->mergeCellsByColumnAndRow ( $col, $row + 0, $col + 3, $row + 0 );
		
		$row ++;
		$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'PERIODE : ' . $this->data ['form'] ['date_from'] . ' to ' . $this->data ['form'] ['date_to'] );
		$objWorksheet->mergeCellsByColumnAndRow ( $col, $row + 0, $col + 3, $row + 0 );
		
		$col = $col;
		$row += 2;
		
		$cc = 3 + ((($end + 1) - $start) * 9) + 5;
		
		$desc = "Ket : DK : Dalam Kota Per Hari,LK : Luar Kota Per Hari,S  : Sakit Per Hari,I  : Ijin Per Jam ,C  : Cuti Per Hari & Ijin >=4 jam, L  : Libur Per Hari, OT : Lembur Per Jam";
		
		$objWorksheet->setCellValueByColumnAndRow ( $col, $row, $desc );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col + $cc, $row );
		
		$row ++;
		
		$holidays = $this->reportModel->getReportHolidayWeek ( $this->data ['form'] );
		
		if (isset ( $holidays )) :
			$str = '';
			foreach ( $holidays as $k => $v ) :
				$str .= $v ['date'] . ':' . $v ['descr'] . ',';
			endforeach
			;
		
        endif;
		
		$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Libur : ' . $str );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col + $cc, $row );
		
		$col = $col;
		$row += 2;
		
		$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'No' );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col )->setWidth ( 5 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row + 1 )->applyFromArray ( $border );
		
		$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col, $row + 1 )->getFill ()->applyFromArray ( $fill );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, 'Name' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 1 )->setWidth ( 30 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 1, $row, $col + 1, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row + 1 )->applyFromArray ( $border );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, 'NIK' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 2 )->setWidth ( 10 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 2, $row, $col + 2, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row + 1 )->applyFromArray ( $border );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 3, $row, 'Jabatan' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 3 )->setWidth ( 15 );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 3, $row, $col + 3, $row + 1 );
		$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row + 1 )->applyFromArray ( $border );
		
		$xi = 0;
		$xo = 9;
		for($is = $start; $is <= $end; $is ++) :
			
			if ($is <= $xmin) {
				$i = $is;
			} else {
				$i = $is - $xmin;
			}
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi, $row, 'Minggu ' . $i );
			$objWorksheet->mergeCellsByColumnAndRow ( $col + 4 + $xi, $row, $col + 4 + $xi + $xo, $row );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row )->getFill ()->applyFromArray ( $fill );
			
			for($j = 0; $j < ($xo + 1); $j ++) {
				$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + $j, $row )->applyFromArray ( $border );
			}
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi, $row + 1, 'DK' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 1, $row + 1, 'LK' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 1, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 1, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 1, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 1 )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 2, $row + 1, 'SS' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 2, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 2, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 2, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 2 )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 3, $row + 1, 'S' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 3, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 3, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 3, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 3 )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 4, $row + 1, 'I' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 4, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 4, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 4, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 4 )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 5, $row + 1, 'C' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 5, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 5, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 5, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 5 )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 6, $row + 1, 'L' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 6, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 6, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 6, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 6 )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 7, $row + 1, 'TK' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 7, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 7, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 7, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 7 )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 8, $row + 1, 'OT' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 8, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 8, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 8, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 8 )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 9, $row + 1, 'Keterangan' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 9, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 9, $row + 1 )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 9, $row + 1 )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 9 )->setWidth ( 50 );
			
			$xi += 10;
		endfor
		;
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi, $row, 'Total' );
		$objWorksheet->mergeCellsByColumnAndRow ( $col + 4 + $xi, $row, $col + 4 + $xi + $xo - 1, $row );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row )->getFill ()->applyFromArray ( $fill );
		
		for($j = 0; $j < 9; $j ++) {
			$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + $j, $row )->applyFromArray ( $border );
		}
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi, $row + 1, 'DK' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi, $row + 1 )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi )->setWidth ( 5 );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 1, $row + 1, 'LK' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 1, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 1, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 1, $row + 1 )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 1 )->setWidth ( 5 );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 2, $row + 1, 'SS' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 2, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 2, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 2, $row + 1 )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 2 )->setWidth ( 5 );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 3, $row + 1, 'S' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 3, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 3, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 3, $row + 1 )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 3 )->setWidth ( 5 );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 4, $row + 1, 'I' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 4, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 4, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 4, $row + 1 )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 4 )->setWidth ( 5 );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 5, $row + 1, 'C' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 5, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 5, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 5, $row + 1 )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 5 )->setWidth ( 5 );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 6, $row + 1, 'L' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 6, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 6, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 6, $row + 1 )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 6 )->setWidth ( 5 );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 7, $row + 1, 'TK' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 7, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 7, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 7, $row + 1 )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 7 )->setWidth ( 5 );
		
		$objWorksheet->setCellValueByColumnAndRow ( $col + 4 + $xi + 8, $row + 1, 'OT' );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 8, $row + 1 )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 8, $row + 1 )->applyFromArray ( $border );
		$objWorksheet->getStyleByColumnAndRow ( $col + 4 + $xi + 8, $row + 1 )->getFill ()->applyFromArray ( $fill );
		$objWorksheet->getColumnDimensionByColumn ( $col + 4 + $xi + 8 )->setWidth ( 5 );
		
		$col = $col;
		$row += 2;
		
		$cc = 3 + ((($end + 1) - $start) * 10) + 9;
		
		$department = array (
				'KAP TSFR',
				'PT BDO KONSULTAN INDONESIA',
				'PT BDO MANAJEMEN INDONESIA' 
		);
		
		foreach ( $department as $d => $key ) {
			
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, $key );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->applyFromArray ( $border );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col + $cc, $row );
			
			for($j = 0; $j < $cc + 1; $j ++) :
				$objWorksheet->getStyleByColumnAndRow ( $col + $j, $row )->applyFromArray ( $border );
			endfor
			;
			
			$row += 1;
			
			$no = 1;
			$employee = $this->reportModel->getEmployeeWeek ( $key );
			
			foreach ( $employee as $k => $v ) :
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 0, $row, $no );
				$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->applyFromArray ( $border );
				$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
				$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, $v ['employeename'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, "'" . $v ['employeeid'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
				$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
				$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 3, $row, $v ['employeetitle'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
				$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
				$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				
				$xi = 4;
				$vw = 0;
				$vl = 0;
				$vs = 0;
				$vi = 0;
				$vc = 0;
				$vl = 0;
				$vtk = 0;
				$vo = 0;
				
				$tdk = 0;
				$tlk = 0;
				$tss = 0;
				$ts = 0;
				$tij = 0;
				$tc = 0;
				$tli = 0;
				$tot = 0;
				$ttk = 0;
				
				for($is = $start; $is <= $end; $is ++) :
					
					$week = $is;
					$year = $xyear;
					
					if ($is > $xmin) {
						$week = $is - $xmin;
						$year = $xyear + 1;
					}
					
					$days = array (
							0 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "1" ) ),
							1 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "2" ) ),
							2 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "3" ) ),
							3 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "4" ) ),
							4 => date ( "d/m/Y", strtotime ( $year . "W" . digit ( $week ) . "5" ) ),
					);
					
					$timesheetdate = '';
					$dk = 0;
					$lk = 0;
					$ss = 0;
					$s = 0;
					$ij = 0;
					$c = 0;
					$li = 0;
					$ot = 0;
					$tk = 0;
					$tk_mon = $days [0];
					$tk_tue = $days [1];
					$tk_wed = $days [2];
					$tk_thu = $days [3];
					$tk_fri = $days [4];
					$description = "";
					$tkdescription = "TK :";
					$ldescription = "Libur :";
					$cdescription = "Cuti :";
					$idescription = "Izin :";
					$sdescription = "Sakit :";
					
					$timesheet = $this->reportModel->getEmployeeWeekDetails ( $v ['employee_id'], $week, $year );
					
					foreach ( $timesheet as $key => $val ) {
						switch ($val ["timesheetdate"]) {
							case $days [0] :
								$tk_mon = "";
								break;
							case $days [1] :
								$tk_tue = "";
								break;
							case $days [2] :
								$tk_wed = "";
								break;
							case $days [3] :
								$tk_thu = "";
								break;
							case $days [4] :
								$tk_fri = "";
								break;
						}
						
						// Dalam Kota
						if (($val ['transport_type'] < 3) && ($val ["JOBTYPE"] != "HRD")) {
							if ($val ['timesheetdate'] != $timesheetdate)
								$dk += 1;
						}
						
						// Luar Kota
						if (($val ['transport_type'] == 3) && ($val ['JOBTYPE'] != 'HRD')) {
							
							if ($val ['timesheetdate'] != $timesheetdate)
								$lk += 1;
						}
						
						// count of izin
						if (($val ["job_id"] == 470)) {
							$ss += $val ["hour"];
						}
						
						// count of sakit / sick
						if (($val ['job_id'] <= 3) && ($val ['hour'] >= 4)) {
							$s += 8;
							$sdescription .= $val ["timesheetdate"] . ", ";
						}
						
						// count of izin
						if (($val ["hour"] < 4) and (($val ["job_id"] >= 4 and $val ["job_id"] <= 9) or $val ["job_id"] == 17)) {
							$ij += $val ["hour"];
							$idescription .= $val ["timesheetdate"] . ", ";
						}
						
						// count of cuti
						if (($val ["hour"] >= 4) and (($val ["job_id"] >= 4 and $val ["job_id"] <= 9) or $val ["job_id"] == 17 or ($val ["job_id"] >= 10 and $val ["job_id"] <= 12))) {
							$c += 8;
							$cdescription .= $val ["timesheetdate"] . ", ";
						}
						
						// count of libur
						if ($val ["job_id"] == 499) {
							$li += 8;
							$ldescription .= $val ["timesheetdate"] . ", ";
						}
						
						if ($val ["overtime"] > 0)
							$ot += $val ["overtime"];
						
						$timesheetdate = $val ['timesheetdate'];
					}
					
					$s = ceil ( $s > 0 ? ($s / 8) : 0 );
					$c = ceil ( $c > 0 ? ($c / 8) : 0 );
					$li = ceil ( $li > 0 ? ($li / 8) : 0 );
					
					$total_week = $dk + $lk + $s + $c + $li;
					$tk = 0;
					if ($total_week < 5) {
						$tk = 5 - $total_week;
						$tkdescription .=$tk_mon ?  $tk_mon.", " : " ";
						$tkdescription .=$tk_tue ?  $tk_tue.", " : " ";
						$tkdescription .=$tk_wed ?  $tk_wed.", " : " ";
						$tkdescription .=$tk_thu ?  $tk_thu.", " : " ";
						$tkdescription .=$tk_fri ?  $tk_fri.", " : " ";
					}
					
					if ($s > 0)
						$description .= $sdescription." ";
					if ($ij > 0)
						$description .= $idescription." ";
					if ($c > 0)
						$description .= $cdescription." ";
					if ($li > 0)
						$description .= $ldescription." ";
					if ($tk > 0)
						$description .= $tkdescription." ";
					
					//if ($description)
						//$description = substr($description,0,(strlen($description) -1));
					
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $dk );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					
					$xi ++;
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $lk );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					
					$xi ++;
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $ss );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					
					$xi ++;
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $s );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					
					$xi ++;
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $ij );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					
					$xi ++;
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $c );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					
					$xi ++;
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $li );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					
					$xi ++;
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $tk );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					
					$xi ++;
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $ot );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					
					$xi ++;
					$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $description );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
					$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->getAlignment ()->setWrapText ( true );
					
					$xi ++;
					
					$tdk += ($dk > 0 ? $dk : 0);
					$tlk += ($lk > 0 ? $lk : 0);
					$tss += ($ss > 0 ? $ss : 0);
					$ts += ($s > 0 ? $s : 0);
					$tij += ($ij > 0 ? $ij : 0);
					$tc += ($c > 0 ? $c : 0);
					$tli += ($li > 0 ? $li : 0);
					$tot += ($ot > 0 ? $ot : 0);
					$ttk += ($tk > 0 ? $tk : 0);
				endfor
				;
				
				$xi = $xi;
				$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $tdk );
				$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
				
				$xi ++;
				$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $tlk );
				$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
				
				$xi ++;
				$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $tss );
				$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
				
				$xi ++;
				$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $ts );
				$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
				
				$xi ++;
				$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $tij );
				$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
				
				$xi ++;
				$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $tc );
				$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
				
				$xi ++;
				$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $tli );
				$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
				
				$xi ++;
				$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $tk );
				$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
				
				$xi ++;
				$objWorksheet->setCellValueByColumnAndRow ( $col + $xi, $row, $tot );
				$objWorksheet->getStyleByColumnAndRow ( $col + $xi, $row )->applyFromArray ( $border );
				
				$row ++;
				$no ++;
			endforeach
			;
			$row ++;
		}
		
		$file = "./media/EmployeeWeek.xlsx";
		$objWriter->save ( $file );
		redirect ( '../media/EmployeeWeek.xlsx' );
	}
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportTransport() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['form'] ['paid'] = $this->input->post ( 'paid' );
		$this->session->set_userdata ( 'date_from', $this->data ['form'] ['date_from'] );
		$this->session->set_userdata ( 'date_to', $this->data ['form'] ['date_to'] );
		$this->data ['form'] ['paid'] = $this->input->post ( 'paid' );
		$this->session->set_userdata ( 'paid', $this->data ['form'] ['paid'] );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportTransport ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total_office = 0;
				$total_office_cost = 0;
				$total_intown = 0;
				$total_intown_cost = 0;
				$total_outtown = 0;
				$total_outtown_cost = 0;
				$total_uknown = 0;
				$total_uknown_cost = 0;
				$total_n = 0;
				$total_cost = 0;
				$cost = 0;
				$cost1 = 0;
				$actual = 0;
				foreach ( $rows as $k => $v ) {
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					$total_office += $v ['office'];
					$total_office_cost += $v ['office_cost'];
					
					$total_intown += $v ['intown'];
					$total_intown_cost += $v ['intown_cost'];
					
					$total_outtown += $v ['outtown'];
					$total_outtown_cost += $v ['outtown_cost'];
					
					$total_uknown += $v ['uknown'];
					$total_uknown_cost += $v ['uknown_cost'];
					
					$total_n += $v ['total'];
					$total_cost += $v ['cost'];
					
					$cost = $v ['cost'];
					$office = $v ['office'] > 0 ? NumberFormat ( $v ['office'] ) : "";
					$off_cost = $v ['office_cost'] > 0 ? number_format ( $v ['office_cost'], 2 ) : "";
					$intown = $v ['intown'] > 0 ? NumberFormat ( $v ['intown'] ) : "";
					$intown_cost = $v ['intown_cost'] > 0 ? number_format ( $v ['intown_cost'], 2 ) : "";
					$outtown = $v ['outtown'] > 0 ? NumberFormat ( $v ['outtown'] ) : "";
					$outtown_cost = $v ['outtown_cost'] > 0 ? number_format ( $v ['outtown_cost'], 2 ) : "";
					$uknown = $v ['uknown'] > 0 ? NumberFormat ( $v ['uknown'] ) : "";
					$uknown_cost = $v ['uknown_cost'] > 0 ? number_format ( $v ['uknown_cost'], 2 ) : "";
					// $total = $v['total'] > 0 ? NumberFormat($v['total']) : "";
					$total = $office + $intown + $outtown + $uknown;
					
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i</td>
      				<td>$v[employeeid]</td>
      				<td>$v[employeefirstname] $v[employeemiddlename] $v[employeelastname]</td>
      				<td class='currency'>$office</td>
					<td class='currency'>$off_cost</td>
      				<td class='currency'>$intown</td>
					<td class='currency'>$intown_cost</td>
      				<td class='currency'>$outtown</td>
					<td class='currency'>$outtown_cost</td>
					<td class='currency'>$uknown</td>
					<td class='currency'>$uknown_cost</td>
					<td class='currency'>$total</td>
					<td class='currency'>" . number_format ( $cost, "2" ) . "</td>
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "     		
      		<tr>
      				<td colspan=3 class='currency'><b>Total</b>
      				<td class='currency'><b>" . NumberFormat ( $total_office ) . "</b></td>
					<td class='currency'><b>" . number_format ( $total_office_cost, 2 ) . "</b></td>
      				<td class='currency'><b>" . NumberFormat ( $total_intown ) . "</b></td>
					<td class='currency'><b>" . number_format ( $total_intown_cost, 2 ) . "</b></td>
      				<td class='currency'><b>" . NumberFormat ( $total_outtown ) . "</b></td>
					<td class='currency'><b>" . number_format ( $total_outtown_cost, 2 ) . "</b></td>
					<td class='currency'><b>" . NumberFormat ( $total_uknown ) . "</b></td>
					<td class='currency'><b>" . number_format ( $total_uknown_cost, 2 ) . "</b></td>
					<td class='currency'><b>" . NumberFormat ( $total_n ) . "</b></td>
					<td class='currency'><b>" . number_format ( $total_cost, 2 ) . "</b></td>
      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_transport', $this->data );
	} // END reportEmployee
	
	/* ------------------------------------------------------------------------------------- */
	// report Excell
	/* ------------------------------------------------------------------------------------- */
	function reportTransportExcelDetails() {
		$this->load->library ( 'PHPExcel' );
		$objPHPExcel = new PHPExcel ();
		$objWriter = new PHPExcel_Writer_Excel2007 ( $objPHPExcel, "Excel2007" );
		$objPHPExcel->getProperties ()->setTitle ( "Mantap" )->setDescription ( "description" );
		$objPHPExcel->setActiveSheetIndex ( 0 );
		
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		$objWorksheet->getPageSetup ()->setOrientation ( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
		$objWorksheet->getPageSetup ()->setPaperSize ( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5 );
		$objWorksheet->getPageSetup ()->setScale ( 97 );
		
		$objWorksheet->getPageMargins ()->setTop ( 0.75 )->setRight ( 0.4 )->setBottom ( 0.75 )->setLeft ( 0.4 );
		
		$border = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN 
						) 
				) 
		);
		$fill = array (
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'rotation' => 0,
				'startcolor' => array (
						'rgb' => 'CCCCCC' 
				),
				'endcolor' => array (
						'argb' => 'CCCCCC' 
				) 
		);
		$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Trebuchet MS' )->setSize ( 8 );
		
		$data ['paid'] = $this->session->userdata ( 'paid' );
		$data ['date_from'] = $this->session->userdata ( 'date_from' );
		$data ['date_to'] = $this->session->userdata ( 'date_to' );
		$users = $this->reportModel->getAuditorFromTimesheet ( $data );
		
		$col = 0;
		$row = 1;
		foreach ( $users as $user => $u ) :
			// $objWorksheet->setCellValueByColumnAndRow($col,$row,$u['employeefirstname'].' '.$u['employeemiddlename'].' '.$u['employeelastname']);
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, "BUKTI PENGGANTIAN TRANSPORT DALAM KOTA" );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row + 0, $col + 7, $row + 0 );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			
			$row ++;
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'PERIODE : ' . $data ['date_from'] . ' to ' . $data ['date_to'] );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row + 0, $col + 7, $row + 0 );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			
			$row = $row + 1;
			if ($data ['paid'] == 1)
				$desc = 'Non Valid';
			elseif ($data ['paid'] == 0)
				$desc = 'Valid';
			else
				$desc = 'All Valid & Non Valid';
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'STATUS :' . strtoupper ( $desc ) );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row + 0, $col + 7, $row );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			
			/**
			 * Column Header *
			 */
			$row = $row + 2;
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'No' );
			// $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			// ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col )->setWidth ( 3 );
			// $objWorksheet->mergeCellsByColumnAndRow($col,$row,$col,$row+1);
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, 'Date' );
			// $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			// ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 1 )->setWidth ( 11 );
			// $objWorksheet->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+1);
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, 'Day' );
			// $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			// ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 2 )->setWidth ( 8 );
			// $objWorksheet->mergeCellsByColumnAndRow($col+2,$row,$col+2,$row+1);
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 3, $row, 'Client' );
			// $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			// ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 3 )->setWidth ( 30 );
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4, $row, 'Charge' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 )->setWidth ( 8 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 5, $row, 'Address' );
			// $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			// ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 5 )->setWidth ( 28 );
			
			// $objWorksheet->setCellValueByColumnAndRow($col+4,$row,'Transport');
			// $objWorksheet->mergeCellsByColumnAndRow($col+4,$row,$col+7,$row);
			// $objWorksheet->getStyleByColumnAndRow($col+4,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			// ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			// $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
			// $objWorksheet->getStyleByColumnAndRow($col+4,$row)->getFill()->applyFromArray($fill);
			
			// $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
			// $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
			// $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
			
			// $objWorksheet->getStyleByColumnAndRow($col+0,$row+1)->applyFromArray($border);
			// $objWorksheet->getStyleByColumnAndRow($col+1,$row+1)->applyFromArray($border);
			// .$objWorksheet->getStyleByColumnAndRow($col+2,$row+1)->applyFromArray($border);
			// $objWorksheet->getStyleByColumnAndRow($col+3,$row+1)->applyFromArray($border);
			
			/*
			 * $objWorksheet->setCellValueByColumnAndRow($col+4,$row+1,'Office');
			 * $objWorksheet->getStyleByColumnAndRow($col+4,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			 * ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			 * $objWorksheet->getStyleByColumnAndRow($col+4,$row+1)->applyFromArray($border);
			 * $objWorksheet->getStyleByColumnAndRow($col+4,$row+1)->getFill()->applyFromArray($fill);
			 * $objWorksheet->getColumnDimensionByColumn($col+4)->setWidth(10);
			 *
			 *
			 * $objWorksheet->setCellValueByColumnAndRow($col+5,$row+1,'In Town Client');
			 * $objWorksheet->getStyleByColumnAndRow($col+5,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			 * $objWorksheet->getStyleByColumnAndRow($col+5,$row+1)->applyFromArray($border);
			 * $objWorksheet->getStyleByColumnAndRow($col+5,$row+1)->getFill()->applyFromArray($fill);
			 * $objWorksheet->getColumnDimensionByColumn($col+5)->setWidth(16);
			 *
			 * $objWorksheet->setCellValueByColumnAndRow($col+6,$row+1,'Out Town Client');
			 * $objWorksheet->getStyleByColumnAndRow($col+6,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			 * $objWorksheet->getStyleByColumnAndRow($col+6,$row+1)->applyFromArray($border);
			 * $objWorksheet->getStyleByColumnAndRow($col+6,$row+1)->getFill()->applyFromArray($fill);
			 * $objWorksheet->getColumnDimensionByColumn($col+6)->setWidth(16);
			 *
			 * $objWorksheet->setCellValueByColumnAndRow($col+7,$row+1,'Uknown');
			 * $objWorksheet->getStyleByColumnAndRow($col+7,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			 * $objWorksheet->getStyleByColumnAndRow($col+7,$row+1)->applyFromArray($border);
			 * $objWorksheet->getStyleByColumnAndRow($col+7,$row+1)->getFill()->applyFromArray($fill);
			 * $objWorksheet->getColumnDimensionByColumn($col+7)->setWidth(11);
			 *
			 *
			 */
			
			/**
			 * Add Description *
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, 'Description' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 6 )->setWidth ( 28 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 7, $row, 'Cost' );
			$objWorksheet->getColumnDimensionByColumn ( $col + 7 )->setWidth ( 17 );
			// $objWorksheet->mergeCellsByColumnAndRow($col+8,$row,$col+8,$row+1);
			// $objWorksheet->getStyleByColumnAndRow($col+8,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			// ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getFill ()->applyFromArray ( $fill );
			// $objWorksheet->getStyleByColumnAndRow($col+5,$row+1)->applyFromArray($border);
			
			/**
			 * Column Header *
			 */
			/* Data */
			$data ['employee_id'] = $u ['employee_id'];
			$vars = $this->reportModel->getTransportDetailsByEmployee ( $data );
			$col = $col;
			$row = $row + 1;
			$i = 1;
			$office = 0;
			$intown = 0;
			$outtown = 0;
			$uknown = 0;
			$cost = 0;
			foreach ( $vars as $var => $v ) :
				
				$office += $v ['office'];
				$intown += $v ['intown'];
				$outtown += $v ['outtown'];
				$uknown += $v ['uknown'];
				// $notes = $v['notes'];
				if (($v ['office'] > 0) || ($v ['intown'] > 0))
					$cost += $v ['cost'];
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 0, $row, $i );
				$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, $v ['date'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, $v ['dayname'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 3, $row, $v ['client'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getAlignment ()->setWrapText ( true );
				$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
				$objWorksheet->setCellValueByColumnAndRow ( $col + 4, $row, $v ['charge'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
				$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getAlignment ()->setWrapText ( true );
				$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->applyFromArray ( $border );
				$objWorksheet->setCellValueByColumnAndRow ( $col + 5, $row, $v ['address'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->getAlignment ()->setWrapText ( true );
				$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, $v ['notes'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getAlignment ()->setWrapText ( true );
				$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border );
				/*
				 * $objWorksheet->setCellValueByColumnAndRow($col+4,$row,$v['office']);
				 * $objWorksheet->getStyleByColumnAndRow($col+4,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				 * $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
				 *
				 * $objWorksheet->setCellValueByColumnAndRow($col+5,$row,$v['intown']);
				 * $objWorksheet->getStyleByColumnAndRow($col+5,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				 * $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
				 *
				 * $objWorksheet->setCellValueByColumnAndRow($col+6,$row,$v['outtown']);
				 * $objWorksheet->getStyleByColumnAndRow($col+6,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				 * $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
				 *
				 * $objWorksheet->setCellValueByColumnAndRow($col+7,$row,$v['uknown']);
				 * $objWorksheet->getStyleByColumnAndRow($col+7,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				 * $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
				 */
				$objWorksheet->setCellValueByColumnAndRow ( $col + 7, $row, $v ['cost'] );
				if (($v ['outtown'] > 0) || ($v ['uknown'] > 0))
					$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getFont ()->setStrikethrough ( TRUE );
					// $objWorksheet->getStyleByColumnAndRow($col+8,$row)->applyFromArray()->setStrikethrough(TRUE);
				$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getNumberFormat ()->setFormatCode ( '#,##0' );
				$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
				
				$row ++;
				$i ++;
			endforeach
			;
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 0, $row, 'Total' );
			$objWorksheet->mergeCellsByColumnAndRow ( $col + 0, $row, $col + 3, $row );
			$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT )->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
			$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border ); // $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
			/*
			 * $objWorksheet->setCellValueByColumnAndRow($col+4,$row,$office);
			 * $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
			 *
			 * $objWorksheet->setCellValueByColumnAndRow($col+5,$row,$intown);
			 * $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
			 *
			 * $objWorksheet->setCellValueByColumnAndRow($col+6,$row,$outtown);
			 * $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
			 *
			 * $objWorksheet->setCellValueByColumnAndRow($col+7,$row,$uknown);
			 * $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
			 */
			$objWorksheet->setCellValueByColumnAndRow ( $col + 7, $row, $cost );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getNumberFormat ()->setFormatCode ( '#,##0' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
			
			$row = $row + 2;
			$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, "Jakarta, ................." . date ( 'Y' ) );
			$objWorksheet->mergeCellsByColumnAndRow ( $col + 6, $row, $col + 7, $row );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
			
			$row = $row + 5;
			$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, '( ' . $u ['employeefirstname'] . ' ' . $u ['employeemiddlename'] . ' ' . $u ['employeelastname'] . ' )' );
			$objWorksheet->mergeCellsByColumnAndRow ( $col + 6, $row, $col + 7, $row );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
			
			$objWorksheet->setBreakByColumnAndRow ( $col, $row, PHPExcel_Worksheet::BREAK_ROW );
			$row = $row + 2;
		endforeach
		;
		
		// We'll be outputting an excel file
		$objWriter->save ( "./media/Transport.xlsx" );
		// force_download("Transport",$file);
		redirect ( '../media/Transport.xlsx' );
	}
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportTransportEmployee() {
		$this->getMenu ();
		if ($this->input->post ( 'date_from' )) :
			$value = array (
					'date_from' => $this->input->post ( 'date_from' ),
					'date_to' => $this->input->post ( 'date_to' ),
					'employee_id' => $this->input->post ( 'employee_id' ) 
			);
			$this->session->set_userdata ( $value );
		
		
		
		
        endif;
		/*
		 * $this->data['form']['date_from'] = $this->input->post('date_from');
		 * $this->data['form']['date_to'] = $this->input->post('date_to');
		 * $this->data['form']['employee_id'] = $this->input->post('employee_id');
		 */
		$this->data ['form'] ['date_from'] = $this->session->userdata ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->session->userdata ( 'date_to' );
		$this->data ['form'] ['employee_id'] = $this->session->userdata ( 'employee_id' );
		
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['employee_id'] > 0 )) {
			$rows = $this->reportModel->getReportTransportEmployee ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total_office = 0;
				$total_intown = 0;
				$total_outtown = 0;
				$total_uknown = 0;
				$cost = 0;
				$total_data = COUNT ( $rows );
				$total_paid = 0;
				$total_on_paid = 0;
				$total_on_non_paid = 0;
				$total_bki = 0;
				$total_kap = 0;
				foreach ( $rows as $k => $v ) {
					if ($v ['paid'] == 1) :
						$checkbox = form_checkbox ( 'ID[]', $v ['timesheetid'], false );
						$total_paid = $total_paid + 0;
						$paid = 0;
						$total_on_paid += 0;
						$non_paid = $v ['cost'];
						$total_on_non_paid += $non_paid;
					 else :
						$checkbox = '<a href="' . site_url ( 'report/reportTransportNonPaid/' . $v ['timesheetid'] ) . '">Paid</a>';
						$total_paid = $total_paid + 1;
						$paid = $v ['cost'];
						$total_on_paid += $paid;
						$non_paid = 0;
						$total_on_non_paid += $non_paid;
					endif;
					$button = form_submit ( 'paid', 'Paid' );
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					$total_office += $v ['office'];
					$total_intown += $v ['intown'];
					$total_outtown += $v ['outtown'];
					$total_uknown += $v ['uknown'];
					if ($v ['charge'] == 'BKI')
						$total_bki += $v ['cost'];
					else
						$total_kap += $v ['cost'];
						// $cost += $v['transport_cost'];
					$v3 = $v ['transport_cost'] + $v ['cost'];
					$cost += $v3;
					$office = $v ['office'] > 0 ? $v ['office'] : "";
					$intown = $v ['intown'] > 0 ? $v ['intown'] : "";
					$outtown = $v ['outtown'] > 0 ? $v ['outtown'] : "";
					$uknown = $v ['uknown'] > 0 ? $v ['uknown'] : "";
					
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i </td>
					<td>$v[hari]</td>
      				<td>$v[date]</td>
      				<!--<td>$v[project]</td>-->
					<td>$v[client]</td>					<td>$v[charge]</td>					<!--<td>$v[partner]</td>-->
      				<td class='currency'>$office</td>
      				<td class='currency'>$intown</td>
      				<td class='currency'>$outtown</td>
                    <td class='currency'>$uknown</td>
      				<td class='currency'>" . number_format ( $v3, "0" ) . "</td>
                    <td class='currency'>" . number_format ( $paid, "0" ) . "</td>
                    <td class='currency'>" . number_format ( $non_paid, "0" ) . "</td>
                    <td class='currency'>$checkbox</td>
      		</tr>";
					$i ++;
				}
				
				if ($total_paid == $total_data)
					$button = '';
				else
					$button = $button;
				$this->data ['row'] = "
			<tbody>" . $this->data ['row'] . "</tbody>
      		<tr>
      				<td colspan=5 class='currency'><b>Total</b>
      				<td class='currency'><b>$total_office</b></td>
      				<td class='currency'><b>$total_intown</b></td>
      				<td class='currency'><b>$total_outtown</b></td>
                    <td class='currency'><b>$total_uknown</b></td>
      				<td class='currency'><b>" . number_format ( $cost, "0" ) . "</b></td>
                    <td class='currency'><b>" . number_format ( $total_on_paid, "0" ) . "</b></td>
      				<td class='currency'><b>" . number_format ( $total_on_non_paid, "0" ) . "</b></td>
                    <td class='currency'></td>
      		</tr>						<tr>      				<td colspan=5 class='currency'><b>Total KAP</b>      				<td class='currency'><b>" . number_format ( $total_kap, "0" ) . "</b></td>      				<td class='currency'></td>      				<td class='currency'></td>                    <td class='currency'></td>      				<td class='currency'></td>                    <td class='currency'></td>      				<td class='currency'></td>                    <td class='currency'></td>      		</tr>						<tr>      				<td colspan=5 class='currency'><b>Total BKI</b>      				<td class='currency'><b>" . number_format ( $total_bki, "0" ) . "</b></td>      				<td class='currency'></td>      				<td class='currency'></td>                    <td class='currency'></td>      				<td class='currency'></td>                    <td class='currency'></td>      				<td class='currency'></td>                    <td class='currency'>$button</td>      		</tr>
			<tr><td colspan=13><i>printed date " . date ( "d/m/Y H:i:s" ) . "</i></td></tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_transport_employee', $this->data );
	} // END reportEmployee
	function reportTransportPaid() {
		$paid = $this->input->post ( 'ID' );
		$total = count ( $paid );
		if ($total)
			for($i = 0; $i < $total; $i ++)
				$this->reportModel->getUpdateTransportPaid ( $paid [$i], 0 );
		redirect ( $this->input->server ( 'HTTP_REFERER' ), 301 );
	}
	function reportTransportNonPaid($id) {
		$this->reportModel->getUpdateTransportPaid ( $id, 1 );
		redirect ( $this->input->server ( 'HTTP_REFERER' ), 301 );
	}
	function reportTransportEmployeeStatus() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		$this->data ['form'] ['employee_id'] = $this->input->post ( 'employee_id' );
		;
		
		if (strlen ( $this->data ['form'] ['employee_id'] > 0 )) {
			$rows = $this->reportModel->getReportTransportEmployee ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total_office = 0;
				$total_intown = 0;
				$total_outtown = 0;
				$cost = 0;
				
				foreach ( $rows as $k => $v ) {
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					$total_office += $v ['office'];
					$total_intown += $v ['intown'];
					$total_outtown += $v ['outtown'];
					// $cost += $v['transport_cost'];
					$v3 = $v ['transport_cost'] + $v ['cost'];
					$cost += $v3;
					$office = $v ['office'] > 0 ? $v ['office'] : "";
					$intown = $v ['intown'] > 0 ? $v ['intown'] : "";
					$outtown = $v ['outtown'] > 0 ? $v ['outtown'] : "";
					
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i </td>
					<td>$v[hari]</td>
      				<td>$v[date]</td>
      				<td>$v[project]</td>
					<td>$v[client]</td>
      				<td class='currency'>$office</td>
      				<td class='currency'>$intown</td>
      				<td class='currency'>$outtown</td>
      				<td class='currency'>" . number_format ( $v3, "2" ) . "</td>
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] = "
			<tbody>" . $this->data ['row'] . "</tbody>
      		<tr>
      				<td colspan=5 class='currency'><b>Total</b>
      				<td class='currency'><b>$total_office</b></td>
      				<td class='currency'><b>$total_intown</b></td>
      				<td class='currency'><b>$total_outtown</b></td>
      				<td class='currency'><b>" . number_format ( $cost, "2" ) . "</b></td>
      				
      		</tr>
			<tr><td colspan=9><i>printed date " . date ( "d/m/Y H:i:s" ) . "</i></td></tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_transport_employee_status', $this->data );
	} // END reportEmployee
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportTransportClient() {
		$this->getMenu ();
		/*
		 * $this->data['form']['date_from'] = $this->input->post('date_from');
		 * $this->data['form']['date_to'] = $this->input->post('date_to');
		 * $this->data['form']['paid'] = $this->input->post('paid');
		 * $this->session->set_userdata('date_from',$this->data['form']['date_from']);
		 * $this->session->set_userdata('date_to',$this->data['form']['date_to']);
		 * $this->data['form']['paid'] = $this->input->post('paid');
		 * $this->session->set_userdata('paid',$this->data['form']['paid']);
		 */
		/**
		 * Search by *
		 */
		$this->data ['form'] ['client_id'] = $_POST ? $this->input->post ( 'client_id' ) : 0;
		$client = $this->reportModel->getClientProjectName ( $this->data ['form'] );
		$this->data ['form'] ['client'] = ! $client ? '' : $client ['client_name'];
		$this->data ['form'] ['year'] = $_POST ? $this->input->post ( 'year' ) : 0;
		
		/**
		 * Register to session *
		 */
		$this->session->set_userdata ( 'tclient', $this->data ['form'] ['client_id'] );
		$this->session->set_userdata ( 'tyear', $this->data ['form'] ['year'] );
		
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		$this->data ['client'] = $this->projectModel->getClientOption ();
		
		if (strlen ( $this->data ['form'] ['client_id'] > 0 )) {
			$rows = $this->reportModel->getReportTransportbyClient ( $this->data ['form'] );
			if (count ( $rows ) > 0) {
				$i = 1;
				foreach ( $rows as $k => $v ) {
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					$this->data ['row'] .= "     		
      		<tr>
      				<td>$i</td>
      				<td>$v[start_date] to $v[finish_date]</td>
                    <td>$v[year_end]</td>
                    <td>$v[project_no]</td>
                    <td>$v[project]</td>
                    <td  colspan='3'>$v[address]</td>
                    <td style='text-align:right'>" . number_format ( $v ['budget_cost'], 2 ) . "</td>
      		</tr>";
					$i ++;
					
					$this->data ['row'] .= "     		
      		<tr>
      				<td>No</td>
      				<td colspan='2'>Nama Karyawan</td>
                    <td>Tanggal</td>
                    <td>Type</td>
                    <td>Keterangan</td>
                    <td>Status</td>
                    <td>Cost</td>
                    <td style='text-align:right'>Saldo</td>
      		</tr>";
					
					$this->data ['form'] ['project_id'] = $v ['project_id'];
					$users = $this->reportModel->getReportTransportbyEmployee ( $this->data ['form'] );
					$no = 1;
					$cost = 0;
					$saldo = $v ['budget_cost'];
					foreach ( $users as $u ) {
						$this->data ['row'] .= "     		
     		        <tr class='odd' style='font-weight:normal'>
          				<td>$no</td>
          				<td colspan='2'>$u[employeefirstname] $u[employeemiddlename] $u[employeelastname]</td>
                        <td>$u[timesheetdate]</td>
                        <td>$u[transporttype]</td>
                        <td>$u[notes]</td>
                        <td>$u[status]</td>
                        <td style='text-align:right'>" . number_format ( $u ['cost'], 0 ) . "</td>
                        <td style='text-align:right'>" . number_format ( $saldo = $saldo - $u ['cost'], 0 ) . "</td>
          		    </tr>";
						$no ++;
						$cost = $cost + $u ['cost'];
					}
					
					$this->data ['row'] .= "     		
  		        <tr class='odd'>
          				<td colspan='7' style='text-align:right'>Total &amp; Sisa Saldo </td>
                        <td style='text-align:right'>" . number_format ( $cost, 0 ) . "</td>
                        <td style='text-align:right'>" . number_format ( $v ['budget_cost'] - $cost, 0 ) . "</td>
      		    </tr>";
				}
				
				/*
				 * $this->data['row'] .= "
				 * <tr>
				 * <td colspan=3 class='currency'><b>Total</b>
				 * <td class='currency'><b>".NumberFormat($total_office)."</b></td>
				 * <td class='currency'><b>".number_format($total_office_cost,2)."</b></td>
				 * <td class='currency'><b>".NumberFormat($total_intown)."</b></td>
				 * <td class='currency'><b>".number_format($total_intown_cost,2)."</b></td>
				 * <td class='currency'><b>".NumberFormat($total_outtown)."</b></td>
				 * <td class='currency'><b>".number_format($total_outtown_cost,2)."</b></td>
				 * <td class='currency'><b>".NumberFormat($total_uknown)."</b></td>
				 * <td class='currency'><b>".number_format($total_uknown_cost,2)."</b></td>
				 * <td class='currency'><b>".NumberFormat($total_n)."</b></td>
				 * <td class='currency'><b>".number_format($total_cost,2)."</b></td>
				 * </tr>";
				 */
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_transportClient', $this->data );
	} // END reportEmployee
	
	/* ------------------------------------------------------------------------------------- */
	// report Excell
	/* ------------------------------------------------------------------------------------- */
	function reportTransportClientExcel() {
		$this->load->library ( 'PHPExcel' );
		$objPHPExcel = new PHPExcel ();
		$objWriter = new PHPExcel_Writer_Excel2007 ( $objPHPExcel, "Excel2007" );
		$objPHPExcel->getProperties ()->setTitle ( "Mantap" )->setDescription ( "description" );
		$objPHPExcel->setActiveSheetIndex ( 0 );
		
		$objWorksheet = $objPHPExcel->getActiveSheet ();
		$objWorksheet->getPageSetup ()->setOrientation ( PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT );
		$objWorksheet->getPageSetup ()->setPaperSize ( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4 );
		$objWorksheet->getPageSetup ()->setScale ( 97 );
		/**
		 * Margin *
		 */
		$objWorksheet->getPageMargins ()->setTop ( 1.0 );
		$objWorksheet->getPageMargins ()->setRight ( 0.5 );
		$objWorksheet->getPageMargins ()->setBottom ( 1.0 );
		$objWorksheet->getPageMargins ()->setLeft ( 0.5 );
		
		$border = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN 
						) 
				) 
		);
		$fill = array (
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'rotation' => 0,
				'startcolor' => array (
						'rgb' => 'CCCCCC' 
				),
				'endcolor' => array (
						'argb' => 'CCCCCC' 
				) 
		);
		$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Trebuchet MS' )->setSize ( 8 );
		
		$data ['client_id'] = $this->session->userdata ( 'tclient' );
		$data ['year'] = $this->session->userdata ( 'tyear' );
		$projects = $this->reportModel->getReportTransportbyClient ( $data );
		
		$col = 0;
		$row = 1;
		foreach ( $projects as $projectClient => $project ) :
			
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, "Periode" );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col + 1, $row );
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, ' : ' . $project ['start_date'] . ' s/d ' . $project ['finish_date'] );
			
			$row ++;
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Nama Klien' );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col + 1, $row );
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, ' : ' . $project ['client_name'] );
			
			$row ++;
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Kode Project' );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col + 1, $row );
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, ' : ' . $project ['project_no'] );
			
			$row ++;
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Tahun Buku' );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col + 1, $row );
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, ' : ' . $project ['year_end'] );
			
			$row ++;
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Alamat' );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col + 1, $row );
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, ' : ' . $project ['address'] );
			
			$row ++;
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'Project' );
			$objWorksheet->mergeCellsByColumnAndRow ( $col, $row, $col + 1, $row );
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, ' : ' . $project ['project'] );
			
			/**
			 * Column Header *
			 */
			$row = $row + 2;
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, 'No' );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col )->setWidth ( 5 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, 'Nama Karyawan' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 1 )->setWidth ( 23 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, 'Tanggal' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 2 )->setWidth ( 12 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 3, $row, 'Type' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 3 )->setWidth ( 15 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4, $row, 'Keterangan' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 4 )->setWidth ( 39 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 5, $row, 'Status' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 5 )->setWidth ( 10 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, 'Cost' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 6 )->setWidth ( 10 );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 7, $row, 'Saldo' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getFill ()->applyFromArray ( $fill );
			$objWorksheet->getColumnDimensionByColumn ( $col + 7 )->setWidth ( 13 );
			/**
			 * Column Header *
			 */
			
			/* Data */
			$this->data ['forms'] ['project_id'] = $project ['project_id'];
			$users = $this->reportModel->getReportTransportbyEmployee ( $this->data ['forms'] );
			
			$col = $col;
			$row = $row + 1;
			$i = 1;
			$saldo = $project ['budget_cost'];
			$desc = 'Project Budget Cost';
			$cost = 0;
			
			$objWorksheet->setCellValueByColumnAndRow ( $col, $row, $i );
			$objWorksheet->getStyleByColumnAndRow ( $col, $row )->applyFromArray ( $border );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, '' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, '' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 3, $row, '' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 4, $row, $desc );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->applyFromArray ( $border );
			$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getAlignment ()->setWrapText ( true );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 5, $row, '' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->applyFromArray ( $border );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, '' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 7, $row, $saldo );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getNumberFormat ()->setFormatCode ( '#,##0' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
			
			$row ++;
			
			$i = 2;
			$cost = 0;
			foreach ( $users as $var => $u ) :
				$saldo -= $u ['cost'];
				$cost += $u ['cost'];
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 0, $row, $i );
				$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 1, $row, $u ['employeefirstname'] . ' ' . $u ['employeemiddlename'] . ' ' . $u ['employeelastname'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 1, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 2, $row, $u ['timesheetdate'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 2, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 3, $row, $u ['transporttype'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 3, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 4, $row, $u ['notes'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->getAlignment ()->setWrapText ( true );
				$objWorksheet->getStyleByColumnAndRow ( $col + 4, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 5, $row, $u ['status'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 5, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, $u ['cost'] );
				$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getNumberFormat ()->setFormatCode ( '#,##0' );
				$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
				$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border );
				
				$objWorksheet->setCellValueByColumnAndRow ( $col + 7, $row, $saldo );
				$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getNumberFormat ()->setFormatCode ( '#,##0' );
				$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
				$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_TOP );
				$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
				
				$row ++;
				$i ++;
			endforeach
			;
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 0, $row, 'Total' );
			$objWorksheet->mergeCellsByColumnAndRow ( $col + 0, $row, $col + 5, $row );
			$objWorksheet->getStyleByColumnAndRow ( $col + 0, $row )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
			for($x = 0; $x <= 5; $x ++) :
				$objWorksheet->getStyleByColumnAndRow ( $col + $x, $row )->applyFromArray ( $border );
			endfor
			;
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 6, $row, $cost );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->getNumberFormat ()->setFormatCode ( '#,##0' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 6, $row )->applyFromArray ( $border );
			
			$objWorksheet->setCellValueByColumnAndRow ( $col + 7, $row, $project ['budget_cost'] - $cost );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->getNumberFormat ()->setFormatCode ( '#,##0' );
			$objWorksheet->getStyleByColumnAndRow ( $col + 7, $row )->applyFromArray ( $border );
			
			$objWorksheet->setBreakByColumnAndRow ( $col, $row, PHPExcel_Worksheet::BREAK_ROW );
			$row = $row + 2;
		endforeach
		;
		
		// We'll be outputting an excel file
		$objWriter->save ( "./media/Transport-Klien.xlsx" );
		redirect ( '../media/Transport-Klien.xlsx' );
	}
	function reportProject() {
		$this->getMenu ();
		// $this->data['project_list'] = $this->timesheetModel->getProject();
		$this->data ['form'] ['client_id'] = $this->input->post ( 'client_id' );
		$this->data ['form'] ['project_id'] = $this->input->post ( 'project_id' );
		
		$this->data ['back'] = $this->data ['site'] . '/report';
		$project_id = 0;
		if (strlen ( $this->data ['form'] ['project_id'] > 0 )) {
			$id = $this->data ['form'] ['project_id'];
			$project_id = $id;
			$this->data ['form'] = $this->projectModel->getProjectDetail ( $id );
			
			$this->data ['back'] = $this->data ['site'] . '/project';
			$this->data ['client'] = $this->projectModel->getClientOption ();
			$this->data ['cclient'] = "";
			$aTeam = $this->projectModel->getProjectTeamStructure ( $id );
			
			$team = "";
			$x = 0;
			for($i = 0; $i < count ( $aTeam ); $i ++) {
				$level = '';
				$x ++;
				
				if ($aTeam [$i] ['lookup_code'] === '01')
					$level = 'PIC';
				if ($aTeam [$i] ['lookup_code'] === '02')
					$level = 'GC';
				if ($aTeam [$i] ['lookup_code'] === '03')
					$level = 'MIC';
				if ($aTeam [$i] ['lookup_code'] === '041')
					$level = 'AIC';
				if ($aTeam [$i] ['lookup_code'] > '041')
					$level = 'ASS';
				
				$team .= "
  				<tr>
  				<td>" . $x . "</td>
  				<td>" . $aTeam [$i] ['lookup_label'] . " ( " . $aTeam [$i] ['tipe'] . " )</td>
  				<td>" . $this->htmlEmployeeListView ( 'employee_id[]', $aTeam [$i] ['employee_id'], $level ) . "</td>
				</tr>";
			}
			$this->data ['project_id'] = $project_id;
			$this->data ['team'] = $team;
			$this->data ['header_team'] = $aTeam;
			
			$this->data ['table_job'] = $this->projectModel->getProjectJobDetails ( $id );
			$this->data ['table'] = $this->projectModel->getProjectAuditor ( $id );
			$this->data ['budgetTotal'] = $this->projectModel->getBugetTotal ( $id );
		} else {
			$this->data ['project_id'] = $project_id;
			$this->data ['table'] = array ();
			$this->data ['team'] = array ();
			$this->data ['header_team'] = array ();
			$this->data ['table_job'] = array ();
			$this->data ['table'] = array ();
			$this->data ['budgetTotal'] = array ();
		}
		$this->load->view ( 'report_project', $this->data );
	}
	function reportJob() {
		$this->getMenu ();
		$this->data ['project_list'] = $this->timesheetModel->getProject ();
		$this->data ['form'] ['client_id'] = $this->input->post ( 'client_id' );
		$this->data ['form'] ['project_id'] = $this->input->post ( 'project_id' );
		$this->data ['form'] ['job_id'] = $this->input->post ( 'job_id' );
		
		$this->data ['back'] = $this->data ['site'] . '/report';
		$project_id = 0;
		$job_id = 0;
		if (strlen ( $this->data ['form'] ['job_id'] > 0 )) {
			$id = $this->data ['form'] ['project_id'];
			$project_id = $id;
			
			$this->data ['project'] = $this->reportModel->getProjectJobDetail ( $this->data ['form'] ['job_id'], $id );
			$this->data ['table'] = $this->reportModel->getReportProjectJobDetail ( $this->data ['form'] ['job_id'], $id );
		} else {
			$this->data ['project'] = array ();
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_job', $this->data );
	}
	function reportPartner() {
		$this->getMenu ();
		$this->data ['form'] ['employee_id'] = $this->input->post ( 'employee_id' );
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['employee_id'] > 0 )) {
			$rows = $this->reportModel->getReportPartner ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$total_bgt = 0;
				$total_actual = 0;
				
				foreach ( $rows as $k => $v ) {
					$info = "";
					$client = "";
					$project = "";
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					$total_bgt += $v ['budget_hour'];
					$total_actual += $v ['actual'];
					
					$showmic = $this->htmlEmployeeListView ( 'employee_id[]', $v ['mic'], 'MIC' );
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i </td>
      				<td>$v[client_name]</td>
      				<td>$v[project_no]</td>
					<td>$showmic</td>
      				<td>$v[jobtype]</td>
					<td>$v[hari]</td>
					<td>$v[year_end]</td>
      				<td class=currency>$v[budget_hour]</td>
      				<td class=currency>$v[actual]</td>
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "	
      		<tr >
      				<td colspan=7 class='currency'><b>Total</b>
      				<td class='currency'><b>$total_bgt</b></td>
      				<td class='currency'><b>$total_actual</b></td>
      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_partner', $this->data );
	} // END reportEmployee
	function reportClosed() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		$rows = $this->projectModel->getProjectStatus ( '4', '0', '0' );
		
		if (count ( $rows ) > 0) {
			$i = 1;
			$budget_hour = 0;
			$hour = 0;
			$budget_cost = 0;
			$cost = 0;
			foreach ( $rows as $k => $v ) {
				$budget_hour += $v ['budget_hour'];
				$hour += $v ['hour'];
				$budget_cost += $v ['budget_cost'];
				$cost += $v ['cost'];
				
				$class = '';
				
				if ($i % 2 == 0)
					$class = 'class="odd"';
				
				$status = '';
				if ($v ['project_approval'] == 1) {
					$status = 'Waiting for Review';
				} elseif ($v ['project_approval'] == 2) {
					$status = 'Reviewed';
				} elseif ($v ['project_approval'] == 3) {
					$status = 'Approved';
				} elseif ($v ['project_approval'] == 4) {
					$status = 'Closed';
				}
				
				$year_end = '';
				if (strlen ( $v ['year_end'] ) > 0) {
					$year_end = date ( "d M Y", strtotime ( $v ['year_end'] ) );
					if ($v ['year_end'] == '0000-00-00' || $v ['year_end'] == '1970-01-01')
						$year_end = '';
				}
				
				$start = '';
				if (strlen ( $v ['start_date'] ) > 0) {
					$start = date ( "d M Y", strtotime ( $v ['start_date'] ) );
					if ($v ['start_date'] == '0000-00-00' || $v ['start_date'] == '1970-01-01')
						$start = '';
				}
				
				$finish = '';
				if (strlen ( $v ['finish_date'] ) > 0) {
					$finish = date ( "d M Y", strtotime ( $v ['finish_date'] ) );
					if ($v ['finish_date'] == '0000-00-00' || $v ['finish_date'] == '1970-01-01')
						$finish = '';
				}
				$this->data ['row'] .= "<tr $class >
      					<td>" . $i . "</td>
      					<td>$v[project_no]</td>
      					<td>$v[client_name]</td>
      					<td>$status</td>
      					<td nowrap>" . $year_end . "</td>
      					<td class=currency style='padding-right:30px;'>$v[budget_hour]</td>
      					<td class=currency style='padding-right:30px;'>$v[hour]</td>
      					<td class=currency style='padding-right:30px;'>" . number_format ( $v ['budget_cost'], 2 ) . "</td>
      					<td class=currency style='padding-right:30px;'>" . number_format ( $v ['cost'], 2 ) . "</td>
      			</tr>";
				$i ++;
			}
			
			$this->data ['row'] .= "	
      		<tr >
      				<td colspan=5 class='currency'><b>Total</b>
   					<th class=currency style='padding-right:30px;'>$budget_hour</th>
   					<th class=currency style='padding-right:30px;'>$hour</th>
   					<th class=currency style='padding-right:30px;'>" . number_format ( $budget_cost, 2 ) . "</th>
   					<th class=currency style='padding-right:30px;'>" . number_format ( $cost, 2 ) . "</th>
      		</tr>";
		} 

		else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_closed', $this->data );
	} // END reportEmployee
	function excel() {
		header ( "Content-type: application/vnd.ms-excel" );
		header ( "Content-Disposition: attachment; filename=excel.xls" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
		header ( "Pragma: public" );
		echo "
    <html>
    <head></head>
    <body>" . $_POST ['exportdata'] . "
    </body>
    </html>";
	}
	function getProject() {
		$client_id = $this->input->post ( 'client_id' );
		$project_id = $this->input->post ( 'project_id' );
		$tmp = "<option value=''>Please Choose</option>";
		
		if (strlen ( $client_id ) > 0) {
			$data = $this->reportModel->getReportProject ( $client_id );
			if ($data) {
				foreach ( $data as $k => $v ) {
					$selected = " ";
					if ($v ['project_id'] == $project_id)
						$selected = " selected ";
					$tmp .= "<option value=$v[project_id] $selected>$v[project_no] </option>";
				}
			}
		}
		echo $tmp;
	}
	function getProjectJob() {
		$project_id = $this->input->post ( 'project_id' );
		$job_id = $this->input->post ( 'job_id' );
		
		$tmp = "<option value=''>Please Choose</option>";
		
		if (strlen ( $project_id ) > 0) {
			$data = $this->reportModel->getReportProjectJob ( $project_id );
			if ($data) {
				foreach ( $data as $k => $v ) {
					$selected = " ";
					if ($v ['job_id'] == $job_id)
						$selected = " selected ";
					$tmp .= "<option value=$v[job_id] $selected>$v[job_no] - $v[job]</option>";
				}
			}
		}
		echo $tmp;
	}
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportGroup() {
		$this->getMenu ();
		$this->data ['form'] ['department_id'] = $this->input->post ( 'department_id' );
		;
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		
		if (strlen ( $this->data ['form'] ['department_id'] > 0 )) {
			$this->data ['table'] = $this->reportModel->getReportGroup ( $this->data ['form'] );
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_group', $this->data );
	} // END reportEmployee
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportBudget() {
		$this->getMenu ();
		$this->data ['project_list'] = $this->timesheetModel->getProject ();
		;
		$this->data ['form'] ['project_id'] = $this->input->post ( 'project_id' );
		$this->data ['form'] ['client_id'] = $this->input->post ( 'client_id' );
		$this->data ['form'] ['project_no'] = $this->input->post ( 'project_no' );
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$project_id = 0;
		if (strlen ( $this->data ['form'] ['project_id'] > 0 )) {
			$id = $this->data ['form'] ['project_id'];
			$project_id = $id;
			$this->data ['form'] = $this->projectModel->getProjectDetail ( $id );
			$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
			$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
			
			$this->data ['back'] = $this->data ['site'] . '/project';
			$this->data ['client'] = $this->projectModel->getClientOption ();
			$this->data ['cclient'] = "";
			$aTeam = $this->projectModel->getProjectTeamStructure ( $id );
			
			$team = "";
			$x = 0;
			for($i = 0; $i < count ( $aTeam ); $i ++) {
				$level = '';
				$x ++;
				
				if ($aTeam [$i] ['lookup_code'] === '01')
					$level = 'PIC';
				if ($aTeam [$i] ['lookup_code'] === '02')
					$level = 'GC';
				if ($aTeam [$i] ['lookup_code'] === '03')
					$level = 'MIC';
				if ($aTeam [$i] ['lookup_code'] === '041')
					$level = 'AIC';
				if ($aTeam [$i] ['lookup_code'] > '041')
					$level = 'ASS';
					
					/*
				 * $team .= "
				 * <input type=hidden name=teamid[] value=".$aTeam[$i]['teamid'].">
				 * <input type=hidden name=project_title[] value='".$aTeam[$i]['lookup_code']."'>
				 * <tr>
				 * <td>".$x."
				 * <td>".$aTeam[$i]['lookup_label']. " ( " .$aTeam[$i]['tipe'] ." )
				 * <td>".$this->htmlEmployeeListView('employee_id[]',$aTeam[$i]['employee_id'],$level);
				 */
				$team .= "	<input type=hidden name=teamid[] value=" . $aTeam [$i] ['teamid'] . ">
							<input type=hidden name=project_title[] value='" . $aTeam [$i] ['lookup_code'] . "'>
							<tr>
							<td>" . $x . "
							<td>" . $aTeam [$i] ['lookup_label'] . " ( " . $aTeam [$i] ['tipe'] . " )
					<td>";
				
				if ($aTeam [$i] ['lookup_code'] === '042') {
					$team .= "";
					$aAssistant = $this->projectModel->getAssistantList ( $id );
					
					for($ii = 0; $ii < count ( $aAssistant ); $ii ++) {
						$team .= $aAssistant [$ii] ['employeefirstname'] . " " . $aAssistant [$ii] ['employeemiddlename'] . " " . $aAssistant [$ii] ['employeelastname'] . "<br>";
					}
				} else {
					$team .= $this->htmlEmployeeListView ( 'employee_id[]', $aTeam [$i] ['employee_id'], $level );
				}
			}
			
			$this->data ['project_id'] = $project_id;
			$this->data ['team'] = $team;
			$this->data ['header_team'] = $aTeam;
			$this->data ['table_job'] = $this->projectModel->getProjectJob ( $id );
			$this->data ['table'] = $this->projectModel->getProjectAuditor ( $id );
			$this->data ['budgetTotal'] = $this->projectModel->getBugetTotal ( $id );
			$this->data ['budgetOther'] = $this->projectModel->getBugetOther ( $id );
		} else {
			$this->data ['project_id'] = $project_id;
			$this->data ['table'] = array ();
			$this->data ['team'] = array ();
			$this->data ['header_team'] = array ();
			$this->data ['table_job'] = array ();
			$this->data ['table'] = array ();
			$this->data ['budgetTotal'] = array ();
			$this->data ['budgetOther'] = array ();
		}
		$this->load->view ( 'report_budget', $this->data );
	} // END
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportBudgetXLS($id) {
		$this->getMenu ();
		$this->data ['project_list'] = $this->timesheetModel->getProject ();
		$this->data ['form'] ['project_id'] = $this->input->post ( 'project_id' );
		$this->data ['form'] ['client_id'] = $this->input->post ( 'client_id' );
		$this->data ['form'] ['project_no'] = $this->input->post ( 'project_no' );
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		
		$this->data ['back'] = $this->data ['site'] . '/report';
		if (strlen ( $id ) > 0) {
			$this->data ['form'] = $this->projectModel->getProjectDetail ( $id );
			$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
			$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
			
			$this->data ['back'] = $this->data ['site'] . '/project';
			$this->data ['client'] = $this->projectModel->getClientOption ();
			$this->data ['cclient'] = "";
			$aTeam = $this->projectModel->getProjectTeamStructure ( $id );
			
			$team = "";
			$x = 0;
			for($i = 0; $i < count ( $aTeam ); $i ++) {
				$level = '';
				$x ++;
				
				if ($aTeam [$i] ['lookup_code'] === '01')
					$level = 'PIC';
				if ($aTeam [$i] ['lookup_code'] === '02')
					$level = 'GC';
				if ($aTeam [$i] ['lookup_code'] === '03')
					$level = 'MIC';
				if ($aTeam [$i] ['lookup_code'] === '041')
					$level = 'AIC';
				if ($aTeam [$i] ['lookup_code'] > '041')
					$level = 'ASS';
				
				$team .= "
  				<input type=hidden name=teamid[] value=" . $aTeam [$i] ['teamid'] . ">
  				<input type=hidden name=project_title[] value='" . $aTeam [$i] ['lookup_code'] . "'>
  				<tr>
  				<td>" . $x . "
  				<td>" . $aTeam [$i] ['lookup_label'] . " ( " . $aTeam [$i] ['tipe'] . " )
  				<td>" . $this->htmlEmployeeListView ( 'employee_id[]', $aTeam [$i] ['employee_id'], $level );
			}
			$this->data ['team'] = $team;
			$this->data ['header_team'] = $aTeam;
			$this->data ['table_job'] = $this->projectModel->getProjectJob ( $id );
			$this->data ['table'] = $this->projectModel->getProjectAuditor ( $id );
			$this->data ['budgetTotal'] = $this->projectModel->getBugetTotal ( $id );
		} else {
			$this->data ['table'] = array ();
			$this->data ['team'] = array ();
			$this->data ['header_team'] = array ();
			$this->data ['table_job'] = array ();
			$this->data ['table'] = array ();
			$this->data ['budgetTotal'] = array ();
		}
		$this->load->view ( 'report_project_budgetXLS', $this->data );
	} // END
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportBudgetActual() {
		$this->getMenu ();
		$this->data ['project_list'] = $this->timesheetModel->getProject ();
		$this->data ['form'] ['project_id'] = $this->input->post ( 'project_id' );
		$this->data ['form'] ['client_id'] = $this->input->post ( 'client_id' );
		$this->data ['form'] ['project_no'] = $this->input->post ( 'project_no' );
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		
		$this->data ['back'] = $this->data ['site'] . '/report';
		$project_id = 0;
		if (strlen ( $this->data ['form'] ['project_id'] > 0 )) {
			$id = $this->data ['form'] ['project_id'];
			$project_id = $id;
			$this->data ['form'] = $this->projectModel->getProjectDetail ( $id );
			$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
			$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
			
			$this->data ['back'] = $this->data ['site'] . '/project';
			$this->data ['client'] = $this->projectModel->getClientOption ();
			$this->data ['cclient'] = "";
			$aTeam = $this->projectModel->getProjectTeamStructure ( $id );
			
			$team = "";
			$x = 0;
			for($i = 0; $i < count ( $aTeam ); $i ++) {
				$level = '';
				$x ++;
				
				if ($aTeam [$i] ['lookup_code'] === '01')
					$level = 'PIC';
				if ($aTeam [$i] ['lookup_code'] === '02')
					$level = 'GC';
				if ($aTeam [$i] ['lookup_code'] === '03')
					$level = 'MIC';
				if ($aTeam [$i] ['lookup_code'] === '041')
					$level = 'AIC';
				if ($aTeam [$i] ['lookup_code'] > '041')
					$level = 'ASS';
				
				$team .= "
  				<input type=hidden name=teamid[] value=" . $aTeam [$i] ['teamid'] . ">
  				<input type=hidden name=project_title[] value='" . $aTeam [$i] ['lookup_code'] . "'>
  				<tr>
  				<td>" . $x . "
  				<td>" . $aTeam [$i] ['lookup_label'] . " ( " . $aTeam [$i] ['tipe'] . " )
  				<td>" . $this->htmlEmployeeListView ( 'employee_id[]', $aTeam [$i] ['employee_id'], $level );
			}
			$this->data ['project_id'] = $project_id;
			$this->data ['team'] = $team;
			$this->data ['header_team'] = $aTeam;
			
			$this->data ['table_job'] = $this->projectModel->getProjectJob ( $id );
			$this->data ['table'] = $this->projectModel->getProjectAuditor ( $id );
			$this->data ['budgetTotal'] = $this->projectModel->getBugetTotal ( $id );
		} else {
			$this->data ['project_id'] = $project_id;
			$this->data ['table'] = array ();
			$this->data ['team'] = array ();
			$this->data ['header_team'] = array ();
			$this->data ['table_job'] = array ();
			$this->data ['table'] = array ();
			$this->data ['budgetTotal'] = array ();
		}
		$this->load->view ( 'report_budget_actual', $this->data );
	} // END
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportBudgetActualXLS($id) {
		$this->data ['project_list'] = $this->timesheetModel->getProject ();
		$this->data ['form'] ['project_id'] = $this->input->post ( 'project_id' );
		$this->data ['form'] ['client_id'] = $this->input->post ( 'client_id' );
		$this->data ['form'] ['project_no'] = $this->input->post ( 'project_no' );
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		
		$this->data ['back'] = $this->data ['site'] . '/report';
		
		if (strlen ( $id > 0 )) {
			$this->data ['form'] = $this->projectModel->getProjectDetail ( $id );
			$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
			$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
			
			$this->data ['back'] = $this->data ['site'] . '/project';
			$this->data ['client'] = $this->projectModel->getClientOption ();
			$this->data ['cclient'] = "";
			$aTeam = $this->projectModel->getProjectTeamStructure ( $id );
			
			$team = "";
			$x = 0;
			for($i = 0; $i < count ( $aTeam ); $i ++) {
				$level = '';
				$x ++;
				
				if ($aTeam [$i] ['lookup_code'] === '01')
					$level = 'PIC';
				if ($aTeam [$i] ['lookup_code'] === '02')
					$level = 'GC';
				if ($aTeam [$i] ['lookup_code'] === '03')
					$level = 'MIC';
				if ($aTeam [$i] ['lookup_code'] === '041')
					$level = 'AIC';
				if ($aTeam [$i] ['lookup_code'] > '041')
					$level = 'ASS';
				
				$team .= "
  				<input type=hidden name=teamid[] value=" . $aTeam [$i] ['teamid'] . ">
  				<input type=hidden name=project_title[] value='" . $aTeam [$i] ['lookup_code'] . "'>
  				<tr>
  				<td>" . $x . "
  				<td>" . $aTeam [$i] ['lookup_label'] . " ( " . $aTeam [$i] ['tipe'] . " )
  				<td>" . $this->htmlEmployeeListView ( 'employee_id[]', $aTeam [$i] ['employee_id'], $level );
			}
			$this->data ['team'] = $team;
			$this->data ['header_team'] = $aTeam;
			
			$this->data ['table_job'] = $this->projectModel->getProjectJob ( $id );
			$this->data ['table'] = $this->projectModel->getProjectAuditor ( $id );
			$this->data ['budgetTotal'] = $this->projectModel->getBugetTotal ( $id );
		} else {
			$this->data ['table'] = array ();
			$this->data ['team'] = array ();
			$this->data ['header_team'] = array ();
			$this->data ['table_job'] = array ();
			$this->data ['table'] = array ();
			$this->data ['budgetTotal'] = array ();
		}
		$this->load->view ( 'report_project_budget_actualXLS', $this->data );
	} // END
	function reportSummaryproject() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportproject1 ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				$project_no = "";
				$client = "";
				$project_status = "";
				$project = "";
				
				foreach ( $rows as $k => $v ) {
					$info = "";
					$client = "";
					$project = "";
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					
					$project_no = $v ['project_no'];
					$client = $v ['client_name'];
					$project = $v ['jobtype'];
					$project_status = "Approved";
					
					$year_end = '';
					if (strlen ( $v ['year_end'] ) > 0) {
						$year_end = date ( "d M Y", strtotime ( $v ['year_end'] ) );
						if ($v ['year_end'] == '0000-00-00' || $v ['year_end'] == '1970-01-01')
							$year_end = '';
					}
					
					$budget_hour = $v ['budget_hour'];
					$actual_hour = $v ['hour'];
					$budget_cost = $v ['budget_cost'];
					$actual_cost = $v ['cost'];
					
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i</td>
      				<td>$project_no</td>
      				<td>$client</td>
      				<td>$project</td>
					<td>$project_status</td>
					<td>$v[approval]</td>
      				<td>$year_end</td>
      				<td>$budget_hour</td>
      				<td>$actual_hour</td>
      				<td>$budget_cost</td>
      				<td>$actual_cost</td>
      		      			
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] .= "     		
      		<tr $class >
      				<td></td>
      				
      		</tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_Summary_project', $this->data );
	}
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportTimesheet() {
		$this->getMenu ();
		$this->data ['waiting'] = $this->timesheetModel->getTimesheetWaiting ();
		$this->data ['request'] = $this->timesheetModel->getTimesheetRequest ();
		$this->data ['active'] = $this->timesheetModel->getTimesheetActive ();
		$this->data ['done'] = $this->timesheetModel->getTimesheetDone ();
		$this->data ['back'] = $this->data ['site'] . '/report';
		
		$this->load->view ( 'report_timesheet', $this->data );
	} // END reportEmployee
	function reportTimesheetEmployee() {
		$this->data ['total_hour'] = '';
		$this->data ['total_overtime'] = '';
		$this->data ['total_transport_cost'] = '';
		$this->getMenu ();
		$this->data ['form'] ['employee_id'] = $this->input->post ( 'employee_id' );
		;
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['done'] = "";
		if (strlen ( $this->data ['form'] ['employee_id'] > 0 )) {
			$this->data ['done'] = $this->timesheetModel->getTimesheetByEmployee ( $this->data ['form'] );
		}
		$this->load->view ( 'report_timesheet_employee', $this->data );
	} // END
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportTimesheetDetail($id) {
		$this->getMenu ();
		$this->data ['id'] = $id;
		$this->data ['table'] = $this->timesheetModel->getTimesheetActiveStatus ( $id );
		$this->data ['back'] = $this->data ['site'] . '/report/reportTimesheet';
		$this->load->view ( 'report_timesheet_detail', $this->data );
	} // END reportEmployee
	function reportTimesheetDetailXLS($id) {
		// $this->getMenu() ;
		$this->data ['id'] = $id;
		$this->data ['table'] = $this->timesheetModel->getTimesheetActiveStatus ( $id );
		$this->load->view ( 'report_timesheet_detailXLS', $this->data );
	} // END reportEmployee
	public function htmlEmployeeListView($name = '', $id = '', $filter = '') {
		$tmp_data = $this->projectModel->getEmployeeList ( $filter );
		$tmp = '';
		$selected = '';
		
		if (count ( $tmp_data ) > 0) {
			if (strlen ( $id ) == 0) {
				$selected = ' selected ';
			}
			foreach ( $tmp_data as $k => $v ) {
				$selected = '';
				if ($v ['employee_id'] === $id) {
					$tmp .= $v ['employeefirstname'] . ' ' . $v ['employeemiddlename'] . ' ' . $v ['employeelastname'];
				}
			}
		}
		return $tmp;
	}
	function reportTimesheetBudget($employee = '') {
		$this->getMenu ();
		if ($this->input->post ( 'date_from' )) {
			$this->search = array (
					'bdate_from' => $this->input->post ( 'date_from' ),
					'bdate_to' => $this->input->post ( 'date_to' ) 
			);
			$this->session->set_userdata ( $this->search );
		}
		
		/*
		 * $this->data['form']['date_from'] = $this->input->post('date_from');
		 * $this->data['form']['date_to'] = $this->input->post('date_to');
		 */
		$this->data ['form'] ['date_from'] = $this->session->userdata ( 'bdate_from' );
		$this->data ['form'] ['date_to'] = $this->session->userdata ( 'bdate_to' );
		
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportTimesheetBudgetActual ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				foreach ( $rows as $k => $v ) {
					
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					
					$this->data ['row'] .= "     		
      		   <tr $class >
      				<td>$i</td>
      				<td>$v[employeename]</td>
      				<td>$v[employeetitle]</td>
                    <td style=text-align:right>$v[project]</td>
					<td class=currency>" . Number ( number_format ( $v ['budget_hour'], 0 ) ) . "</td> 
					<td class=currency>" . Number ( number_format ( $v ['actual_hour'], 0 ) ) . "</td>
      				<td class=currency>" . Number ( number_format ( $v ['budget_hour'] - $v ['actual_hour'], 0 ) ) . "</td>
                    
      				<td class=currency>" . Number ( number_format ( $v ['budget_cost'], 0 ) ) . "</td> 
					<td class=currency>" . Number ( number_format ( $v ['actual_cost'], 0 ) ) . "</td>
      				<td class=currency>" . Number ( number_format ( $v ['budget_cost'] - $v ['actual_cost'], 0 ) ) . "</td>";
					if ($employee == $v ['employee_id']) {
						$this->data ['row'] .= "<td style=text-align:right><a href=" . site_url ( 'report/reportTimesheetBudget/' ) . ">Close</a></td>
                     </tr>";
					} else {
						$this->data ['row'] .= "<td style=text-align:right><a href=" . site_url ( 'report/reportTimesheetBudget/' . $v ['employee_id'] ) . ">View</a></td>
                    </tr>";
					}
					
					if ($employee == $v ['employee_id']) {
						$this->data ['form'] ['employee'] = $v ['employee_id'];
						$rowdetails = $this->reportModel->getReportProjectbyEmployee ( $this->data ['form'] );
						$j = 1;
						foreach ( $rowdetails as $k => $project ) {
							$class = ($j % 2 == 0) ? $class = 'class="odd"' : ' ';
							$this->data ['row'] .= "
                  <tr $class style=font-weight:normal>
                    <td>" . $i . "." . $j . "</td>
                    <td>$project[client_name]</td>
                    <td colspan=2>$project[project_no]</td>
                    
                    <td class=currency>" . Number ( number_format ( $project ['budget_hour'], 0 ) ) . "</td> 
					<td class=currency>" . Number ( number_format ( $project ['actual_hour'], 0 ) ) . "</td>
      				<td class=currency>" . Number ( number_format ( $project ['budget_hour'] - $project ['actual_hour'], 0 ) ) . "</td>
                    
      				<td class=currency>" . Number ( number_format ( $project ['budget_cost'], 0 ) ) . "</td> 
					<td class=currency>" . Number ( number_format ( $project ['actual_cost'], 0 ) ) . "</td>
      				<td class=currency>" . Number ( number_format ( $project ['budget_cost'] - $project ['actual_cost'], 0 ) ) . "</td>
                    <td style=text-align:right></td>
                  </tr>  
                  ";
							$j ++;
						}
					}
					
					$i ++;
				}
				
				/*
				 * $this->data['row'] .= "
				 * <tr $class >
				 * <td></td>
				 * <td colspan=3 class='currency'><b>Total</b>
				 * <td class=currency><b>".Number($total_day)."</b></td>
				 *
				 * <td class=currency><b>".Number($total_work_app)."</b></td>
				 * <td class=currency><b>".Number($total_ot_app)."</b></td>
				 * <td class=currency><b>".Number($total_hour_app)."</b></td>
				 * <td class=currency><b>".Number($total_day_app)."</b></td>
				 *
				 * <td class=currency><b>".Number($total_work_wait)."</b></td>
				 * <td class=currency><b>".Number($total_ot_wait)."</b></td>
				 * <td class=currency><b>".Number($total_hour_wait)."</b></td>
				 * <td class=currency><b>".Number($total_day_wait)."</b></td>
				 *
				 * <td class=currency><b>".Number($total_work_re)."</b></td>
				 * <td class=currency><b>".Number($total_ot_re)."</b></td>
				 * <td class=currency><b>".Number($total_hour_re)."</b></td>
				 * <td class=currency><b>".Number($total_day_re)."</b></td>
				 *
				 * <td class=currency><b>".Number($total_work_null)."</b></td>
				 * <td class=currency><b>".Number($total_ot_null)."</b></td>
				 * <td class=currency><b>".Number($total_hour_null)."</b></td>
				 * <td class=currency><b>".Number($total_day_null)."</b></td>
				 * </tr>";
				 */
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_timesheet_budget', $this->data );
	} // END reportTimesheetBudget
	function reportActualEmployee() {
		$this->getMenu ();
		$this->data ['form'] ['date_from'] = $this->input->post ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->input->post ( 'date_to' );
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['date_from'] > 0 )) {
			$rows = $this->reportModel->getReportActualEmployee ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				foreach ( $rows as $k => $v ) {
					
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					
					$this->data ['row'] .= "     		
      		   <tr $class >
      				<td>$i</td>
      				<td>$v[employeename]</td>
      				<td>$v[employeeid]</td>
	                <td>$v[employeeapproval]</td>
                    <td>$v[employeehiredate]</td>
					<td class=currency>" . Number ( number_format ( $v ['hhour'], 0 ) ) . "</td> 
					<td class=currency>" . Number ( number_format ( $v ['phour'], 0 ) ) . "</td>
      				<td class=currency>" . Number ( number_format ( $v ['hour'], 0 ) ) . "</td>
              </tr>";
					$i ++;
				}
				
				/*
				 * $this->data['row'] .= "
				 * <tr $class >
				 * <td></td>
				 * <td colspan=3 class='currency'><b>Total</b>
				 * <td class=currency><b>".Number($total_day)."</b></td>
				 *
				 * <td class=currency><b>".Number($total_work_app)."</b></td>
				 * <td class=currency><b>".Number($total_ot_app)."</b></td>
				 * <td class=currency><b>".Number($total_hour_app)."</b></td>
				 * <td class=currency><b>".Number($total_day_app)."</b></td>
				 *
				 * <td class=currency><b>".Number($total_work_wait)."</b></td>
				 * <td class=currency><b>".Number($total_ot_wait)."</b></td>
				 * <td class=currency><b>".Number($total_hour_wait)."</b></td>
				 * <td class=currency><b>".Number($total_day_wait)."</b></td>
				 *
				 * <td class=currency><b>".Number($total_work_re)."</b></td>
				 * <td class=currency><b>".Number($total_ot_re)."</b></td>
				 * <td class=currency><b>".Number($total_hour_re)."</b></td>
				 * <td class=currency><b>".Number($total_day_re)."</b></td>
				 *
				 * <td class=currency><b>".Number($total_work_null)."</b></td>
				 * <td class=currency><b>".Number($total_ot_null)."</b></td>
				 * <td class=currency><b>".Number($total_hour_null)."</b></td>
				 * <td class=currency><b>".Number($total_day_null)."</b></td>
				 * </tr>";
				 */
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_actual_employee', $this->data );
	} // END reportTimesheetBudget
	
	/* ------------------------------------------------------------------------------------- */
	// reportEmployee
	/* ------------------------------------------------------------------------------------- */
	function reportAbsentByEmployee() {
		$this->getMenu ();
		if ($this->input->post ( 'date_from' )) :
			$value = array (
					'date_from' => $this->input->post ( 'date_from' ),
					'date_to' => $this->input->post ( 'date_to' ),
					'employee_id' => $this->input->post ( 'employee_id' ) 
			);
			$this->session->set_userdata ( $value );
		
		
		
		
        endif;
		
		$this->data ['form'] ['date_from'] = $this->session->userdata ( 'date_from' );
		$this->data ['form'] ['date_to'] = $this->session->userdata ( 'date_to' );
		$this->data ['form'] ['employee_id'] = $this->session->userdata ( 'employee_id' );
		
		$this->data ['back'] = $this->data ['site'] . '/report';
		$this->data ['row'] = "";
		
		if (strlen ( $this->data ['form'] ['employee_id'] > 0 )) {
			$rows = $this->reportModel->getReporAbsentByEmployee ( $this->data ['form'] );
			
			if (count ( $rows ) > 0) {
				$i = 1;
				foreach ( $rows as $k => $v ) {
					$class = ($i % 2 == 0) ? $class = 'class="odd"' : ' ';
					$this->data ['row'] .= "     		
      		<tr $class >
      				<td>$i</td>
					<td>$v[hari]</td>
      				<td>$v[date]</td>
      				<td>$v[project_no]</td>
					<td>$v[client]</td>
					<td>$v[job]</td>
					<td>$v[notes]</td>
      				<td class='center'>$v[transport_type]</td>
					<td class='currency'>$v[hour]</td>
					<td class='currency'>$v[overtime]</td>
                    <td class='currency'>$v[cdate]</td>
      		</tr>";
					$i ++;
				}
				
				$this->data ['row'] = "
			<tbody>" . $this->data ['row'] . "</tbody>
      		
			<tr><td colspan=14><i>printed date " . date ( "d/m/Y H:i:s" ) . "</i></td></tr>";
			}
		} else {
			$this->data ['table'] = array ();
		}
		$this->load->view ( 'report_absent_employee', $this->data );
	} // END reportEmployee
	
	
	
	
}	