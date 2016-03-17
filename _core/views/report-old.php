<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends MY_Controller{
	
	function __construct()
	{
		parent::__construct();	
		$this->load->model('reportModel');
		$this->load->model('projectModel');
		$this->load->model('timesheetModel');
        ini_set('max_execution_time',300);
	}
	
	
	/*-------------------------------------------------------------------------------------*/
	//  report
	/*-------------------------------------------------------------------------------------*/
	function index() 	{
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
		$this->data['back']	= $this->data['site'] .'/report';
		$this->data['row'] = "";

		if ( strlen( $this->data['form']['employee_id'] > 0)) {
			$rows = $this->reportModel->getReportEmployee($this->data['form']);

      if ( count( $rows ) > 0 ) {
      	$i = 1;
        $total_hour = 0;
        $total_overtime = 0;
		$rs_totalhour = 0;
		$v_hour = 0;
        $rs_overtime = 0;
        $normal=0; 
        $total_l=0; 
		  
      	foreach ($rows as $k=>$v) {
      	  $info    = "";
      	  $client  = "";
      	  $project = "";
      	  $job_no  = "";
      	  $job		 = "";
      	  $class  = ( $i % 2 == 0) ? $class= 'class="odd"' : ' ';
          $total_overtime += $v['overtime'];
          $total = $v['hour'] + $v['overtime'];
          $total_all = $total_hour + $total_overtime;
          $normal = $v['hour'] - $v['overtime']  ;
		  $overtime = $v['overtime'];
		  $total_hour +=$normal;
		  $total_l +=$v['hour'] ;
		   
          $rows_project = $this->reportModel->getReportEmployeeProject($this->data['form']['employee_id'],$v['date']);      		
          
          if ( count( $rows_project ) > 0 ) {
            foreach ($rows_project as $k1=>$v1) {
              $client .= $v1['client_name'] . ", ";
              $project .= $v1['project_no'] . ", ";
            }
            
            if (strlen($client) > 0) $client = substr($client, 0, strlen($client) - 2);
            if (strlen($project) > 0) $project = substr($project, 0, strlen($project) - 2);
            
          }
      		
      		$this->data['row'] .= "     		
      		<tr $class >
      				<td>$i </td>
      				<td>$v[hari]</td>
      				<td>$v[tanggal]</td>
      				<td>$client</td>
      				<td>$project</td>
      				<td>$job_no</td>
      				<td>$job</td>
					    <td class=currency>$v[hour]</td> 
					    <td class=currency>$normal</td> 
      				<td class=currency>$v[overtime]</td>     				
      				<td></td>
      		</tr>";
      		$i++;
			
      	}
      		$this->data['row'] .= "     		
      		<tr>
      				<td colspan=7 class='currency'><b>Total</b></td>
      				<td class=currency><b>$total_l</b></td>
      				<td class=currency><b>$total_hour</b></td>
					 <td class=currency><b>$total_overtime</b></td>
      				
      				<td></td>
      		</tr>";

      }
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_employee',$this->data);
	} // END reportEmployee
	
	/*-------------------------------------------------------------------------------------*/
	//  reportEmployee
	/*-------------------------------------------------------------------------------------*/
	function reportEmployeeSummary() 	{
		$this->getMenu() ;
		$this->data['form']['date_from']	= $this->input->post('date_from');
		$this->data['form']['date_to']	= $this->input->post('date_to');
		$this->data['back']		= $this->data['site'] .'/report';
		$this->data['row'] = "";

		if ( strlen( $this->data['form']['date_from'] > 0)) {
			$rows = $this->reportModel->getReportEmployeeSummary($this->data['form']);

      if ( count( $rows ) > 0 ) {
      	$i = 1;
      	$total = 0;
        $total_hour = 0;
        $total_overtime = 0;
		$rs_totalhour = 0;
		$v_hour = 0;
        $rs_overtime = 0;  
        $total_all1 = 0;
        $normal=0;
      	foreach ($rows as $k=>$v) {
      	  $info    = "";
      	  $client  = "";
      	  $project = "";
      		$class  = ( $i % 2 == 0) ? $class= 'class="odd"' : ' ';
          //$total_hour     += $v['hour'];
          //$total_overtime += $v['overtime'];
          //$total = $v['hour'] + $v['overtime'];
          //$total_all = $total_hour + $total_overtime;
		      
          $total = $v['hour'] + $v['overtime'];
          $total_all = $rs_totalhour + $total_overtime;
      	  $normal = $v['hour'] - $v['overtime']  ;
		      $overtime = $total - $normal;
		      $total_all1 += $v['hour'];
		      $total_hour +=$normal;
		      $total_overtime += $v['overtime'];
      		$this->data['row'] .= "     		
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
      		$i++;
      	}

      		$this->data['row'] .= "     		
      		<tr $class >
      				<td></td>
      				<td></td>
      				<td colspan=3 class='currency'><b>Total</b>
      				<td class=currency><b>$total_all1</b></td>
      				<td class=currency><b>$total_hour</b></td>			
					<td class=currency><b>$total_overtime</b></td> 
      		</tr>";
      }
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_employee_summary',$this->data);
	} // END reportEmployee
	
	/*-------------------------------------------------------------------------------------*/
	//  reportTimesheetCompletion
	/*-------------------------------------------------------------------------------------*/
	function business_days($start_date, $end_date, $holidays = array()) {
		$business_days = 0;
		$current_date = strtotime($start_date);
		$end_date = strtotime($end_date);
		while ($current_date <= $end_date) {
			if (date('N', $current_date) < 6 && !in_array(date('d-m-Y', $current_date), $holidays)) {
				$business_days++;
			}
			if ($current_date <= $end_date) {
				$current_date = strtotime('+1 day', $current_date);
			}
		}
		return $business_days;
	}
    
    function reportTimesheetCompletionSummary() 	
	{
		$this->getMenu() ;
		$this->data['form']['date_from']	= $this->input->post('date_from');
		$this->data['form']['date_to']		= $this->input->post('date_to');
		$this->data['back']					= $this->data['site'] .'/report';
		$this->data['row']					= "";
	
		if ( strlen( $this->data['form']['date_from'] > 0)) {
			$rows = $this->reportModel->getReportTimesheetCompletionSummary($this->data['form']);
			
        if ( count( $rows) > 0 ) {
            $i = 1;
            $total_day=0; //day
      	    $total_hour_app=0;
            $total_hour_wait=0;
            $total_hour_re=0;
            $total_hour_null=0;
               
            $total_work_app=0;
            $total_work_wait=0;
            $total_work_re=0;
            $total_work_null=0;
               
            $total_ot_app= 0;
            $total_ot_wait=0;
            $total_ot_re=0;
            $total_ot_null=0;
            
            $total_day_app= 0;
            $total_day_wait=0;
            $total_day_re=0;
            $total_day_null=0;
               
            foreach ($rows as $k=>$v) {
      	       $total_day+= $v['tday'];
               $total_hour_app+= $v['hour_app'];
               $total_hour_wait+=$v['hour_wait'];
               $total_hour_re+=$v['hour_re'];
               $total_hour_null+=$v['hour_null'];
               
               $total_work_app+= $v['work_app'];
               $total_work_wait+=$v['work_wait'];
               $total_work_re+=$v['work_re'];
               $total_work_null+=$v['work_null'];
               
               $total_ot_app+= $v['ot_app'];
               $total_ot_wait+=$v['ot_wait'];
               $total_ot_re+=$v['ot_re'];
               $total_ot_null+=$v['ot_null'];
               
               $total_day_app+= $v['day_app'];
               $total_day_wait+=$v['day_wait'];
               $total_day_re+=$v['day_re'];
               $total_day_null+=$v['day_null'];
                              
               $class  = ( $i % 2 == 0) ? $class= 'class="odd"' : ' ';
	
               $this->data['row'] .= "     		
      		   <tr $class >
      				<td>$i</td>
      				<td>$v[employeeid]</td>
      				<td>$v[employee]</td>
      				<td>$v[approval]</td>
                    <td class=currency>".Number($v['tday'])."</td>
                    
					<td class=currency>".Number($v['work_app'])."</td> 
					<td class=currency>".Number($v['ot_app'])."</td>
      				<td class=currency>".Number($v['hour_app'])."</td>
                    <td class=currency>".Number($v['day_app'])."</td>
                    
      				<td class=currency>".Number($v['work_wait'])."</td>
      				<td class=currency>".Number($v['ot_wait'])."</td>
                    <td class=currency>".Number($v['hour_wait'])."</td>
                    <td class=currency>".Number($v['day_wait'])."</td>
                    
      				<td class=currency>".Number($v['work_re'])."</td>
      				<td class=currency>".Number($v['ot_re'])."</td>
                    <td class=currency>".Number($v['hour_re'])."</td>
                    <td class=currency>".Number($v['day_re'])."</td>
                    
      				<td class=currency>".Number($v['work_null'])."</td>
      				<td class=currency>".Number($v['ot_null'])."</td>
                    <td class=currency>".Number($v['hour_null'])."</td>
                    <td class=currency>".Number($v['day_null'])."</td>
              </tr>";
      		$i++;
      	}
			  
      		$this->data['row'] .= "     		
      		<tr $class >
      				<td></td>
      				<td colspan=3 class='currency'><b>Total</b>
                    <td class=currency><b>".Number($total_day)."</b></td>
                    
      				<td class=currency><b>".Number($total_work_app)."</b></td>
      				<td class=currency><b>".Number($total_ot_app)."</b></td>
      				<td class=currency><b>".Number($total_hour_app)."</b></td>
                    <td class=currency><b>".Number($total_day_app)."</b></td>
                    
      				<td class=currency><b>".Number($total_work_wait)."</b></td>
      				<td class=currency><b>".Number($total_ot_wait)."</b></td>
					<td class=currency><b>".Number($total_hour_wait)."</b></td>
                    <td class=currency><b>".Number($total_day_wait)."</b></td>
                    
      				<td class=currency><b>".Number($total_work_re)."</b></td>
      				<td class=currency><b>".Number($total_ot_re)."</b></td>
                    <td class=currency><b>".Number($total_hour_re)."</b></td>
                    <td class=currency><b>".Number($total_day_re)."</b></td>
                    
      				<td class=currency><b>".Number($total_work_null)."</b></td>
      				<td class=currency><b>".Number($total_ot_null)."</b></td>
                    <td class=currency><b>".Number($total_hour_null)."</b></td>
                    <td class=currency><b>".Number($total_day_null)."</b></td>
      		</tr>";

      } 
	  	
      
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_timesheet_completionSummary',$this->data);
	} // END reportTimesheetCompletion
		
	function reportTimesheetCompletion() 	
	{
		$this->getMenu() ;
		$this->data['form']['date_from']	= $this->input->post('date_from');
		$this->data['form']['date_to']		= $this->input->post('date_to');
		$this->data['back']								= $this->data['site'] .'/report';
		$this->data['row']								= "";
	
		if ( strlen( $this->data['form']['date_from'] > 0)) {
			$rows = $this->reportModel->getReportTimesheetCompletion($this->data['form']);
			
      if ( count( $rows) > 0 ) {
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
		    $total_hour_y=0;
		    $day = 0;
		    $v_approv=0;
		    $total_all_y =0;
		    $day_all_y=0;
		    $total_y=0;
		    $Tot_day=0;
		    $harikerja=0;
		    $total_all=0;
      	foreach ($rows as $k=>$v) {
      	  $info    = "";
      	  $client  = "";
      	  $project = "";
      		$class  = ( $i % 2 == 0) ? $class= 'class="odd"' : ' ';

			$v_approv =$v['timesheet_approval']; 
			    if ($v_approv=='2')
			    {
          	$total_overtime += $v['overtime'];
          	$total = $v['hour'];
            $normal = $v['hour'] - $v['overtime'];
		  	    $overtime = $v['overtime'];
		  	    $total_hour     += $normal;
		  	    $total_all = $total_hour + $total_overtime;
			    } 
			    else if ($v_approv=='1'){
			
          	$total_overtime_w += $v['overtime'];
          	$total_w = $v['hour'];
          	
           	$normal_w = $v['hour'] - $v['overtime'] ;
		  	    $overtime_w = $v['overtime'];
		  	    $total_hour_w  += $normal_w;
		  	    $total_all_w = $total_hour_w + $total_overtime_w;
		  	  
			    }
			  
			$harikerja= ($normal+$normal_w);
			$start_date=$this->data['form']['date_from']; 
			$end_date=$this->data['form']['date_to'];
			$businessDays = $this->business_days($start_date,$end_date)*8;
		 	$Tot_day +=$businessDays;
			$total_y = $businessDays- $harikerja;
			$day = $total_y / 8;
			$total_all_y +=$total_y;
			$day_all_y +=$day;
				
      		$this->data['row'] .= "     		
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
      		$i++;
      	}
			  
      		$this->data['row'] .= "     		
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
	  	
      
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_timesheet_completion',$this->data);
	} // END reportTimesheetCompletion
	
//==================================================================================================================================//
		function reportTimesheetCompletionW() 	
	{
		$this->getMenu() ;
		$this->data['form']['date_from']	= $this->input->post('date_from');
		$this->data['form']['date_to']		= $this->input->post('date_to');
		$this->data['back']								= $this->data['site'] .'/report';
		$this->data['row']								= "";
		
		
		if ( strlen( $this->data['form']['date_from'] > 0)) {
			$rows = $this->reportModel->getReportTimesheetCompletion2($this->data['form']);
			
      if ( count( $rows) > 0 ) {
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
		    $total_hour_y=0;
		    $day = 0;
		    $v_approv=0;
		    $total_all_y =0;
		    $day_all_y=0;
		    $total_y=0;
		    $Tot_day=0;
		    $harikerja=0;
		    $total_all=0;
      	foreach ($rows as $k=>$v) {
      	  $info    = "";
      	  $client  = "";
      	  $project = "";
      		$class  = ( $i % 2 == 0) ? $class= 'class="odd"' : ' ';
		
			$v_approv =$v['timesheet_approval']; 
			    if ($v_approv=='2')
			    {
			
			$total_hour     += $v['hour'];
          	$total_overtime += $v['overtime'];
          	$total = $v['hour'];
          	$total_all = $total_hour + $total_overtime;
            $normal = $v['hour'] - $v['overtime'];
		  	$overtime = $v['overtime'];
			    }
			
			    else if ($v_approv=='1'){
						      
          	$total_overtime_w += $v['overtime'];
          	$total_w = $v['hour'];
           	$normal_w = $v['hour'] - $v['overtime'] ;
		  	$overtime_w = $v['overtime'];
		  	$total_hour_w     += $normal_w;
		  	$total_all_w = $total_hour_w + $total_overtime_w;
			}				
			$harikerja= ($normal+$normal_w);
			$start_date=$this->data['form']['date_from']; 
			$end_date=$this->data['form']['date_to'];
			$businessDays = $this->business_days($start_date,$end_date)*8;
		 	$Tot_day +=$businessDays;
			$total_y = $businessDays- $harikerja;
			$day = $total_y / 8;
			$total_all_y +=$total_y;
			$day_all_y +=$day;
				
      		$this->data['row'] .= "     		
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
      		$i++;
      	}
			  
      		$this->data['row'] .= "     		
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
	  	
      
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_timesheet_completion_waiting',$this->data);
	} // END reportTimesheetCompletion
	
	//=================================================================================================//
function reportEmployeeOvertime() 	
{ 
		$this->getMenu() ;
		$this->data['form']['date_from']	= $this->input->post('date_from');
		$this->data['form']['date_to']	= $this->input->post('date_to');
		$this->data['back']	= $this->data['site'] .'/report';
		$this->data['row'] = "";

		if ( strlen( $this->data['form']['date_from'] > 0)) {
			$rows = $this->reportModel->getReportEmployeeOvertime($this->data['form']);

      if ( count( $rows ) > 0 ) {
      	$i = 1;
			$total_overtime = 0;
          
				foreach ($rows as $k=>$v) {
					$info    = "";
					$client  = "";
					$project = "";
					$mic		= "";
					$class  = ( $i % 2 == 0) ? $class= 'class="odd"' : ' ';
					$total_overtime += $v['overtime'];

					/*$rows_project = $this->reportModel->getReportEmployeeProjectOvertime($v['employee_id'],$v['date']);      		
					if ( count( $rows_project ) > 0 ) {
						foreach ($rows_project as $k1=>$v1) {
						  $client .= $v1['client_name'] . ",<br> ";
						  $project .= $v1['project_no'] . ",<br> ";
						  $mic .= $v1['employee'] . ",<br> ";
						}
					  if (strlen($client) > 0) $client = substr($client, 0, strlen($client) - 6);
					  if (strlen($project) > 0) $project = substr($project, 0, strlen($project) - 6);
					  if (strlen($mic) > 0) $mic = substr($mic, 0, strlen($mic) - 6);
					}*/
				 
					
					$this->data['row'] .= "     		
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
					$i++;
				}

      		$this->data['row'] .= "     		
      		<tr>
      				<td colspan='6' class=currency><b>Total  </b></td>
      				<td class=currency><b>$total_overtime</b></td>
      		</tr>";
			}
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_employee_overtime',$this->data);
	} // END reportEmployee	

	/*-------------------------------------------------------------------------------------*/
	//  reportEmployee
	/*-------------------------------------------------------------------------------------*/
	function reportEmployeeAbsent() 	{
		$this->getMenu() ;
		$this->data['form']['date_from']	= $this->input->post('date_from');
		$this->data['form']['date_to']	= $this->input->post('date_to');
        $this->session->set_userdata('date_from',$this->data['form']['date_from']); 
		$this->session->set_userdata('date_to',$this->data['form']['date_to']);
        $this->data['back']		= $this->data['site'] .'/report';
		$this->data['row'] = "";

		if ( strlen( $this->data['form']['date_from'] > 0)) {
			$rows = $this->reportModel->getReportEmployeeAbsent($this->data['form']);

      if ( count( $rows ) > 0 ) {
      	$i = 1;
        $total_sakit = 0;
        $total_leave = 0;
          
      	foreach ($rows as $k=>$v) {
      		$class  = ( $i % 2 == 0) ? $class= 'class="odd"' : ' ';
          $total_sakit += $v['countsakit'];
          $total_leave += $v['countonleave'];
          
      		
      		$this->data['row'] .= "     		
      		<tr $class >
      				<td>$i </td>
      				<td>$v[employeeid]</td>
      				<td>$v[employeefirstname] $v[employeemiddlename] $v[employeelastname]</td>
					<td>$v[hari]</td>
      				<td>$v[date]</td>
      				<td>$v[sakit]</td>
      				<td>$v[onleave]</td>
      		</tr>";
      		$i++;
      	}

      		$this->data['row'] .= "     		
      		<tr>
      				<td colspan=5 class='currency'><b>Total</b>
      				<td><b>$total_sakit</b></td>
      				<td><b>$total_leave</b></td>
      		</tr>";

      }
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_employee_absent',$this->data);
	} // END reportEmployee
	
    //** Summary Absent **/
    function reportEmployeeAbsentSummaryExcel(){
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel,"Excel2007");
        $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        
        /** Page Setup **/
        $objWorksheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objWorksheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5);
        $objWorksheet->getPageSetup()->setScale(93);
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Trebuchet MS')
                                                  ->setSize(8);
        /** Page Border **/
        $border = array( 'borders' => array( 'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )));
        $fill = array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'rotation'   => 0,
                        'startcolor' => array(
                        'rgb'        => 'CCCCCC'),
                        'endcolor'   => array(
                        'argb'       => 'CCCCCC'));
        // We'll be outputting an excel file     
        $data['date_from'] = $this->session->userdata('date_from');
		$data['date_to']   = $this->session->userdata('date_to');
        $users = $this->reportModel->getAbsentByEmployeeSummary($data);
        
        $col=0;$row=1;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'Laporan Absen');
        $row++;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'Periode :'.$data['date_from'].' / '.$data['date_to']);
        
        $row++;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'Name');
        $objWorksheet->getColumnDimensionByColumn($col)->setWidth(30);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col,$row+1)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col,$row+2)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->mergeCellsByColumnAndRow($col+0,$row+0,$col+0,$row+2);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                        
        $objWorksheet->setCellValueByColumnAndRow($col+1,$row,'NIK');
        $objWorksheet->getColumnDimensionByColumn($col+1)->setWidth(10);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row+1)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row+2)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->mergeCellsByColumnAndRow($col+1,$row+0,$col+1,$row+2);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                
        
        $objWorksheet->setCellValueByColumnAndRow($col+2,$row,'TOTAL ABSEN');
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWorksheet->mergeCellsByColumnAndRow($col+2,$row+0,$col+8,$row+0);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->applyFromArray($border);
        
        $row++;
        $objWorksheet->setCellValueByColumnAndRow($col+2,$row,"Cuti \n Tahunan");
        $objWorksheet->getColumnDimensionByColumn($col+2)->setWidth(15);
        $objWorksheet->mergeCellsByColumnAndRow($col+2,$row+0,$col+2,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row+1)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);
                                                                          
        $objWorksheet->setCellValueByColumnAndRow($col+3,$row,"Cuti \n Bersama");
        $objWorksheet->getColumnDimensionByColumn($col+3)->setWidth(15);
        $objWorksheet->mergeCellsByColumnAndRow($col+3,$row+0,$col+3,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row+1)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
                                                                          
        $objWorksheet->setCellValueByColumnAndRow($col+4,$row,"Cuti \n Tanggugan");
        $objWorksheet->getColumnDimensionByColumn($col+4)->setWidth(15);
        $objWorksheet->mergeCellsByColumnAndRow($col+4,$row+0,$col+4,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+4,$row+1)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+4,$row)->getFill()->applyFromArray($fill);
                                                                          
        $objWorksheet->setCellValueByColumnAndRow($col+5,$row,"Cuti \n Khusus");
        $objWorksheet->getColumnDimensionByColumn($col+5)->setWidth(15);
        $objWorksheet->mergeCellsByColumnAndRow($col+5,$row+0,$col+5,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row+1)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+5,$row)->getFill()->applyFromArray($fill);
                                                                          
        $objWorksheet->setCellValueByColumnAndRow($col+6,$row,"Sakit");
        $objWorksheet->getColumnDimensionByColumn($col+6)->setWidth(15);
        $objWorksheet->mergeCellsByColumnAndRow($col+6,$row+0,$col+6,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row+1)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+6,$row)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+7,$row,"Izin");
        $objWorksheet->getColumnDimensionByColumn($col+7)->setWidth(15);
        $objWorksheet->mergeCellsByColumnAndRow($col+7,$row+0,$col+7,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row+1)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+7,$row)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+8,$row,"Haid");
        $objWorksheet->getColumnDimensionByColumn($col+8)->setWidth(15);
        $objWorksheet->mergeCellsByColumnAndRow($col+8,$row+0,$col+8,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row+1)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+8,$row)->getFill()->applyFromArray($fill);
        
        $row=$row+2;
        foreach($users as $u):
            /** Name **/
            $objWorksheet->setCellValueByColumnAndRow($col+0,$row,$u['employeefirstname'].' '.$u['employeemiddlename'].' '.$u['employeelastname']);
            $objWorksheet->getStyleByColumnAndRow($col+0,$row)->applyFromArray($border);
            /** NIK **/
            $objWorksheet->setCellValueByColumnAndRow($col+1,$row,$u['employeeid']);
            $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);
            /** Cuti Tahunan **/
            $objWorksheet->setCellValueByColumnAndRow($col+2,$row,$u['cuti_tahunan']>0 ? $u['cuti_tahunan'] : "");
            $objWorksheet->getStyleByColumnAndRow($col+2,$row)->applyFromArray($border);
            /** Cuti Bersama **/
            $objWorksheet->setCellValueByColumnAndRow($col+3,$row,$u['cuti_bersama']>0 ? $u['cuti_bersama']:"");
            $objWorksheet->getStyleByColumnAndRow($col+3,$row)->applyFromArray($border);
            /** Cuti Tanggungan **/
            $objWorksheet->setCellValueByColumnAndRow($col+4,$row,$u['cuti_tanggungan']>0 ? $u['cuti_tanggungan']:"");
            $objWorksheet->getStyleByColumnAndRow($col+4,$row)->applyFromArray($border);
            /** Cuti Khusus **/
            $objWorksheet->setCellValueByColumnAndRow($col+5,$row,$u['cuti_khusus']>0 ? $u['cuti_khusus']:"" );
            $objWorksheet->getStyleByColumnAndRow($col+5,$row)->applyFromArray($border);
            /** Sakit **/
            $objWorksheet->setCellValueByColumnAndRow($col+6,$row,$u['sakit']>0 ? $u['sakit']:"");
            $objWorksheet->getStyleByColumnAndRow($col+6,$row)->applyFromArray($border);
            /** Izin **/
            $objWorksheet->setCellValueByColumnAndRow($col+7,$row,$u['izin']>0 ? $u['izin']:"");
            $objWorksheet->getStyleByColumnAndRow($col+7,$row)->applyFromArray($border);
            /** Haid **/
            $objWorksheet->setCellValueByColumnAndRow($col+8,$row,$u['haid']>0 ? $u['haid']:"");
            $objWorksheet->getStyleByColumnAndRow($col+8,$row)->applyFromArray($border);
            $row++;
        endforeach;
        
        $objWriter->save("./media/Absent-Summary.xlsx"); 
        redirect('../media/Absent-Summary.xlsx');
    }    
    //** End Of Summary Absent **/
    
    function reportEmployeeAbsentExcel() {
		$this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel,"Excel2007");
		$objPHPExcel->getProperties()->setTitle("Mantap")
					->setDescription("description");   
		$objPHPExcel->setActiveSheetIndex(0);
        
        $objWorksheet = $objPHPExcel->getActiveSheet();       
        $objWorksheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objWorksheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5);
        $objWorksheet->getPageSetup()->setScale(93);
        
        $border = array( 'borders' => array( 'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )));
        $fill = array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'rotation'   => 0,
                        'startcolor' => array(
                                'rgb' => 'CCCCCC'
                        ),
                        'endcolor'   => array(
                                'argb' => 'CCCCCC'
                        ));     
        $objPHPExcel->getDefaultStyle()->getFont()
                                                    ->setName('Trebuchet MS')
                                                    ->setSize(8);
        $objWorksheet = $objPHPExcel->setActiveSheetIndex();
        // We'll be outputting an excel file     
        $data['date_from'] = $this->session->userdata('date_from');
		$data['date_to']   = $this->session->userdata('date_to');
        $users = $this->reportModel->getAbsentByEmployee($data);
        
        $col=0;$row=1;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'Name');
        $objWorksheet->getColumnDimensionByColumn($col)->setWidth(30);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->mergeCellsByColumnAndRow($col,$row,$col,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                                                            
        $objWorksheet->setCellValueByColumnAndRow($col+1,$row,'NIK');
        $objWorksheet->getColumnDimensionByColumn($col+1)->setWidth(10);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $row++;
        $objWorksheet->getStyleByColumnAndRow($col,$row)->applyFromArray($border);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);
        
        $year_start   = substr($data['date_from'],6,4);
		$month_start  = substr($data['date_from'],3,2);
		$day_start  = substr($data['date_from'],0,2);
		
		$year_end     = substr($data['date_to'],6,4);
		$month_end   = substr($data['date_to'],3,2);
		$day_end    = substr($data['date_to'],0,2);
        
        $total = getRangeDate($day_start,$month_start,$year_start,$day_end,$month_end,$year_end);
        $x=1;
        $y=1;
        for($i=0;$i<=$total;$i++):
            $date  = $year_start.'-'.$month_start.'-'.$day_start;
            $ndate = date("d-M", strtotime("$date +$i day"));
            $objWorksheet->setCellValueByColumnAndRow($col+1+($x),$row-1,$ndate);
            $objWorksheet->getStyleByColumnAndRow($col+1+($x),$row-1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+1+($x),$row-1)->getFill()->applyFromArray($fill);
            $objWorksheet->mergeCellsByColumnAndRow($col+1+($x),$row-1,$col+1+($x+1),$row-1);
            
            $objWorksheet->setCellValueByColumnAndRow($col+1+($y),$row,"Sakit");
            $objWorksheet->getColumnDimensionByColumn($col+1+($y))->setWidth(5);
            $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->applyFromArray($border);
            $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->getFill()->applyFromArray($fill);
            $y++;
            $objWorksheet->setCellValueByColumnAndRow($col+1+($y),$row,"Izin");
            $objWorksheet->getColumnDimensionByColumn($col+1+($y))->setWidth(4);
            $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->applyFromArray($border);
            $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->getFill()->applyFromArray($fill);
            $x=$x+2;
            $y++;
        endfor;
        
        $col=0;$row++;
        foreach($users as $user => $u):
            $objWorksheet->setCellValueByColumnAndRow($col+0,$row,$u['employeefirstname'].' '.$u['employeemiddlename'].' '.$u['employeelastname']);
            $objWorksheet->getStyleByColumnAndRow($col+0,$row)->applyFromArray($border);
            
            $objWorksheet->setCellValueByColumnAndRow($col+1,$row,$u['employeeid']);
            $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);
            
            //$x=1;
            $y=1;
            for($i=0;$i<=$total;$i++):
                
                $objWorksheet->setCellValueByColumnAndRow($col+1+($y),$row,$u['S'.$i]);
                $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->applyFromArray($border);
                
                $y++;
                $objWorksheet->setCellValueByColumnAndRow($col+1+($y),$row,$u['I'.$i]);
                $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objWorksheet->getStyleByColumnAndRow($col+1+($y),$row)->applyFromArray($border);
                
                $y++;
            endfor;
            $row++;
        endforeach;
        $objWriter->save("./media/Absent.xlsx");
        //force_download("Transport",$file); 
        redirect('../media/Absent.xlsx');
	}
    
	/*-------------------------------------------------------------------------------------*/
	//  reportEmployeeAbsenWeek
	/*-------------------------------------------------------------------------------------*/
	function reportEmployeeTotal() 	{
		$this->getMenu() ;
		$this->data['form']['date_from']	= $this->input->post('date_from');
		$this->data['form']['date_to']	= $this->input->post('date_to');
		$this->data['back']		= $this->data['site'] .'/report';
		$this->data['row'] = "";

		if ( strlen( $this->data['form']['date_from'] > 0)) {
			$rows = $this->reportModel->getReportEmployeeTotal($this->data['form']);

      if ( count( $rows ) > 0 ) {
      	$i = 1;
        $total_sakit = 0;
        $total_leave = 0;
          
      	foreach ($rows as $k=>$v) {
      		$class  = ( $i % 2 == 0) ? $class= 'class="odd"' : ' ';
          $total_sakit += $v['countsakit'];
          $total_leave += $v['countonleave'];
          
      		
      		$this->data['row'] .= "     		
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
      		$i++;
      	}

      		$this->data['row'] .= "     		
      		<tr>
      		</tr>";

      }
		} 
		else {
			$this->data['table'] = array();
		}
		$this->load->view('report_employee_total',$this->data);
	} // END reportEmployee
	
	
	function test(){
		$y=34;
		$x=34;
		 for($is=$y;$is<=$x;$is++): 
			if($is<=52){
				 echo $i=$is;
					
			}
			else {
				 echo $i=$is-52;
					
			} 	
		endfor;		
		
	}
	/*-------------------------------------------------------------------------------------*/
	//  reportEmployeeWeek
	/*-------------------------------------------------------------------------------------*/
	function reportEmployeeWeek() 	{
		$this->getMenu() ;
		$this->data['form']['date_from']= $this->input->post('date_from');
		$this->data['form']['date_to']	= $this->input->post('date_to');
		$this->data['form']['week']	= $this->input->post('week');
		$this->data['form']['week2']	= $this->input->post('week2');
		$this->data['back']		= $this->data['site'] .'/report';
		$this->data['row'] 		= "";
        
		if($this->data['form']['date_from']):
		    $arr = array( 'wdate_from' => $this->data['form']['date_from'],
				  'wdate_to'   => $this->data['form']['date_to'],
				  'wweek'      => $this->data['form']['week'],
				  'wweek2'     => $this->data['form']['week2']);
		    $this->session->set_userdata($arr);
		endif;
			
		$xstart = $this->data['form']['week'];
		$xend   = $this->data['form']['week2'];
		
		if(!$xstart){
			$xstart = 1;
			$xend   = 1;
		}
        
		if(($xstart<=$xend))
		{
			$xstart = $xstart;
			$xend   = $xend;
		} else {
			$xstart = $xstart;
			$xend   = $xend+52;
		}
		
		$this->data['y'] = $xstart;
		$this->data['x'] = $xend;
		
		if($this->session->userdata('wdate_from')){
			$table ='';
		        $dep = array('KAP','BKI','BO');
			$x = $this->data['x'] - $this->data['y'];
			foreach($dep as $key){
			$table .= '<tr>
			<td colspan="'.(8+(($x*8)+12)).'" >'.$key.'</td>
			</tr>';
			
			$employee = $this->reportModel->getEmployeeWeek($key);
			$no=1;
			foreach ($employee as $k=>$v){
				$table .= '<tr>';
				$table .= '<td>'.$no.'</td>';
				$table .= '<td>'.$v['employeeid'].'</td>';
				$table .= '<td >'.$v['employeename'].'</td>';
				$table .= '<td>'.$v['employeetitle'].'</td>';
				
				//hitung angka looping
				$dk = 0;
				$lk = 0;
				$s = 0;
				$ij = 0;
				$c = 0;
				$li = 0;
				$le=0;
				for($i=$this->data['y'];$i<=$this->data['x'];$i++){
					$row = $this->reportModel->getTMPEmployeeWeek($v['employee_id'],$i);
					
				     $table .= '<td class="center">'.($row?$row['dk']:'').'</td>';
				     $table .= '<td class="center">'.($row?$row['lk']:'').'</td>';
				     $table .= '<td class="center">'.($row?$row['s']:'').'</td>';
				     $table .= '<td class="center">'.($row?$row['i']:'').'</td>';
				     $table .= '<td class="center">'.($row?$row['c']:'').'</td>';
				     $table .= '<td class="center">'.($row?$row['li']:'').'</td>';
				     $table .= '<td class="center">-</td>';
				     $table .= '<td class="center">'.($row?$row['le']:'').'</td>';
				     $dk+= ($row?$row['dk']:0);
				     $lk+= ($row?$row['lk']:0);
				     $s+= ($row?$row['s']:0);
				     $ij+= ($row?$row['i']:0);
				     $c+= ($row?$row['c']:0);
				     $li+= ($row?$row['li']:0);
				     $le+= ($row?$row['le']:0);
				}
				
				$table .= '<td class="center">'.($dk).'</td>';
				$table .= '<td class="center">'.($lk).'</td>';
				$table .= '<td class="center">'.($s).'</td>';
				$table .= '<td class="center">'.($ij).'</td>';
				$table .= '<td class="center">'.($c).'</td>';
				$table .= '<td class="center">'.($li).'</td>';
				$table .= '<td class="center">-</td>';
				$table .= '<td class="center">'.($le).'</td>';
				
				$table .= '';
				$table .= '</tr>';
				$no++;
			}
			}
			
		} else {
			$table='';
		}
		$this->data['content_report'] = $table;
		//$this->data['rowskap']   = $this->reportModel->getReportEmployeeWeek($this->data['form'],'KAP');
		//$this->data['rowsbki']   = $this->reportModel->getReportEmployeeWeek($this->data['form'],'BKI');
		//$this->data['rowsbot']   = $this->reportModel->getReportEmployeeWeek($this->data['form'],'BO');
		if($this->data['form']['date_from']) $this->data['holidays']   = $this->reportModel->getReportHolidayWeek($this->data['form']);
		$this->load->view('report_employee_week',$this->data);
	} 
	// END reportEmployee
	
	
    
    /*-------------------------------------------------------------------------------------*/
	//  report Excell
	/*-------------------------------------------------------------------------------------*/
	function reportEmployeeWeekExcel() {
		/** Data **/
        $this->data['form']['date_from']= $this->session->userdata('wdate_from');
		$this->data['form']['date_to']	= $this->session->userdata('wdate_to');
        $this->data['form']['week']	    = $this->session->userdata('wweek');
        $this->data['form']['week2']	= $this->session->userdata('wweek2');
        
		$start = $this->data['form']['week'];
        $end =   $this->data['form']['week2'];
		
		if(!$start){
			$start = 0;
			$end   = 0;
		}
        
		if(($start<=$end)){
			$start = $start;
			$end   = $end;
		} else {
			$start = $start;
			$end   = $end+52;
		}
		
        $rows = $this->reportModel->getReportEmployeeWeek($this->data['form']);
        $holidays = $this->reportModel->getReportHolidayWeek($this->data['form']);
        
        $this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel,"Excel2007");
		$objPHPExcel->getProperties()->setTitle("Mantap")
					->setDescription("description");   
		$objPHPExcel->setActiveSheetIndex(0);
        
        $objWorksheet = $objPHPExcel->getActiveSheet();       
        $objWorksheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objWorksheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5);
        $objWorksheet->getPageSetup()->setScale(93);
        
        $border = array( 'borders' => array( 'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN )));
        $fill = array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'rotation'   => 0,
                        'startcolor' => array(
                                'rgb' => 'CCCCCC'
                        ),
                        'endcolor'   => array(
                                'argb' => 'CCCCCC'
                        ));     
        $objPHPExcel->getDefaultStyle()->getFont()  ->setName('Trebuchet MS')
                                                    ->setSize(8);
        
        $col=0;$row=1;                                            
        
        $objWorksheet->setCellValueByColumnAndRow($col,$row,"EMPLOYEE REPORT BY WEEK");
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+0,$col+3,$row+0);
            
        $row++;
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'PERIODE : '.$this->data['form']['date_from'].' to '.$this->data['form']['date_to']);
        $objWorksheet->mergeCellsByColumnAndRow($col,$row+0,$col+3,$row+0);
        
        $col=$col;$row+=2;
        
        $cc = 3 +((($end+1)-$start)*8) + 5;
        
        $desc= "Ket : DK : Dalam Kota Per Hari,LK : Luar Kota Per Hari,S  : Sakit Per Hari,I  : Ijin Per Jam ,C  : Cuti Per Hari & Ijin >=4 jam, L  : Libur Per Hari, OT : Lembur Per Jam"; 
        
        $objWorksheet->setCellValueByColumnAndRow($col,$row,$desc);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);       
        $objWorksheet->mergeCellsByColumnAndRow($col,$row,$col+$cc,$row);
        
        $row++;
        
        if(isset($holidays)):  
          $str = '';
          foreach ($holidays as $k=>$v):
            $str.= $v['date'].':'.$v['descr'].','; 
          endforeach;
        endif;
        
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'Libur : '.$str);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);       
        $objWorksheet->mergeCellsByColumnAndRow($col,$row,$col+$cc,$row);
        
        
        $col=$col;$row+=2;
        
        $objWorksheet->setCellValueByColumnAndRow($col,$row,'No');
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col,$row)->applyFromArray($border);                                                                
        $objWorksheet->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col)->setWidth(5);
        $objWorksheet->mergeCellsByColumnAndRow($col,$row,$col,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col,$row+1)->applyFromArray($border);                                                            
        
        $objWorksheet->mergeCellsByColumnAndRow($col,$row,$col,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col,$row+1)->getFill()->applyFromArray($fill);
        
        $objWorksheet->setCellValueByColumnAndRow($col+1,$row,'Name');
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->applyFromArray($border);                                                                
        $objWorksheet->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+1)->setWidth(30);
        $objWorksheet->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+1,$row+1)->applyFromArray($border);                                                            
        
        
        $objWorksheet->setCellValueByColumnAndRow($col+2,$row,'NIK');
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->applyFromArray($border);                                                                
        $objWorksheet->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+2)->setWidth(10);
        $objWorksheet->mergeCellsByColumnAndRow($col+2,$row,$col+2,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+2,$row+1)->applyFromArray($border);                                                            
        
        $objWorksheet->setCellValueByColumnAndRow($col+3,$row,'Jabatan');
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                          ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->applyFromArray($border);                                                                
        $objWorksheet->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+3)->setWidth(15); 
        $objWorksheet->mergeCellsByColumnAndRow($col+3,$row,$col+3,$row+1);
        $objWorksheet->getStyleByColumnAndRow($col+3,$row+1)->applyFromArray($border);                                                            
           
        
        $xi=0;
        $xo=7;
        for($is=$start;$is<=$end;$is++):
		    
			if($is<=52){
				$i = $is;
			}
			else {
				$i = $is-52;
			} 		
	
            $objWorksheet->setCellValueByColumnAndRow($col+4+$xi,$row,'Minggu '.$i);
            $objWorksheet->mergeCellsByColumnAndRow($col+4+$xi,$row,$col+4+$xi+$xo,$row);
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                              ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                 
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row)->getFill()->applyFromArray($fill);
            for($j=0;$j<8;$j++):
                $objWorksheet->getStyleByColumnAndRow($col+4+$xi+$j,$row)->applyFromArray($border);
            endfor;
            
            $objWorksheet->setCellValueByColumnAndRow($col+4+$xi,$row+1,'DK');
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row+1)->applyFromArray($border);                                                                
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row+1)->getFill()->applyFromArray($fill);
            $objWorksheet->getColumnDimensionByColumn($col+4+$xi)->setWidth(5);    
            
            $objWorksheet->setCellValueByColumnAndRow($col+4+$xi+1,$row+1,'LK');
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+1,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+1,$row+1)->applyFromArray($border);                                                                
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+1,$row+1)->getFill()->applyFromArray($fill);
            $objWorksheet->getColumnDimensionByColumn($col+4+$xi+1)->setWidth(5);    
            
            $objWorksheet->setCellValueByColumnAndRow($col+4+$xi+2,$row+1,'S');
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+2,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+2,$row+1)->applyFromArray($border);                                                                
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+2,$row+1)->getFill()->applyFromArray($fill);
            $objWorksheet->getColumnDimensionByColumn($col+4+$xi+2)->setWidth(5);    
            
            $objWorksheet->setCellValueByColumnAndRow($col+4+$xi+3,$row+1,'I');
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+3,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+3,$row+1)->applyFromArray($border);                                                                
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+3,$row+1)->getFill()->applyFromArray($fill);
            $objWorksheet->getColumnDimensionByColumn($col+4+$xi+3)->setWidth(5);
            
            $objWorksheet->setCellValueByColumnAndRow($col+4+$xi+4,$row+1,'C');
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+4,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+4,$row+1)->applyFromArray($border);                                                                
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+4,$row+1)->getFill()->applyFromArray($fill);
            $objWorksheet->getColumnDimensionByColumn($col+4+$xi+4)->setWidth(5);
            
            $objWorksheet->setCellValueByColumnAndRow($col+4+$xi+5,$row+1,'L');
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+5,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+5,$row+1)->applyFromArray($border);                                                                
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+5,$row+1)->getFill()->applyFromArray($fill);
            $objWorksheet->getColumnDimensionByColumn($col+4+$xi+5)->setWidth(5);
            
            $objWorksheet->setCellValueByColumnAndRow($col+4+$xi+6,$row+1,'TK');
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+6,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+6,$row+1)->applyFromArray($border);                                                                
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+6,$row+1)->getFill()->applyFromArray($fill);
            $objWorksheet->getColumnDimensionByColumn($col+4+$xi+6)->setWidth(5);    
            
            $objWorksheet->setCellValueByColumnAndRow($col+4+$xi+7,$row+1,'OT');
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+7,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+7,$row+1)->applyFromArray($border);                                                                
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+7,$row+1)->getFill()->applyFromArray($fill);
            $objWorksheet->getColumnDimensionByColumn($col+4+$xi+7)->setWidth(5);    
        
            $xi+=8;
        endfor;
        
        $objWorksheet->setCellValueByColumnAndRow($col+4+$xi,$row,'Total');
        $objWorksheet->mergeCellsByColumnAndRow($col+4+$xi,$row,$col+4+$xi+$xo,$row);
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                                                              ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);                                                                 
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row)->getFill()->applyFromArray($fill);
        for($j=0;$j<8;$j++):
            $objWorksheet->getStyleByColumnAndRow($col+4+$xi+$j,$row)->applyFromArray($border);
        endfor;
        
        $objWorksheet->setCellValueByColumnAndRow($col+4+$xi,$row+1,'DK');
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row+1)->applyFromArray($border);                                                                
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi,$row+1)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+4+$xi)->setWidth(5);    
            
        $objWorksheet->setCellValueByColumnAndRow($col+4+$xi+1,$row+1,'LK');
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi+1,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi+1,$row+1)->applyFromArray($border);                                                                
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi+1,$row+1)->getFill()->applyFromArray($fill);
        $objWorksheet->getColumnDimensionByColumn($col+4+$xi+1)->setWidth(5);    
            
        $objWorksheet->setCellValueByColumnAndRow($col+4+$xi+2,$row+1,'S');
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi+2,$row+1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWorksheet->getStyleByColumnAndRow($col+4+$xi+2,$row+1)->applyFromArray($border);                                                                
 