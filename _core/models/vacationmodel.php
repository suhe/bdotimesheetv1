<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class vacationModel extends CI_Model {

	public function __construct() {
		parent:: __construct();
	}

    function getVacationSummary($ID,$Date){
        $sql = " SELECT DATE_FORMAT(created_date,'%d/%m/%Y') as created_date,
                 MIN(DATE_FORMAT(vacation_date,'%d/%m/%Y')) as date_from,
                 MAX(DATE_FORMAT(vacation_date,'%d/%m/%Y')) as date_to,
                 vacation_desc,vacation_address,COUNT(vacation_date) as total,
                 CASE vacation_status
                    WHEN '0' THEN 'Approval'
                    WHEN '1' THEN 'Cancel'
                    WHEN '2' THEN 'Progress'
                 END AS status,
                 vacation_acl as acl,
                 vacation.employee_id,employeefirstname,employeelastname,mic_approval,aic_approval,pic_approval    
                 FROM vacation
                 INNER JOIN employee ON employee.employee_id=vacation.employee_id
                 WHERE vacation.employee_id=".$ID."
                 AND created_date = '".$Date."'
                 GROUP BY created_date,vacation.employee_id
                 ORDER BY created_date ASC LIMIT 1  
               ";
          $Q = $this->db->query($sql);
          return $Q->row_array();     
    }
    
    function getVacationDetails($ID,$Date){
        $sql = " SELECT employee.employee_id,vacation_id,DATE_FORMAT(created_date,'%d/%m/%Y') as created_date,
                 DATE_FORMAT(vacation_date,'%d/%m/%Y') as vacation_date,
                 vacation_date as date,
                 vacation_desc,vacation_address,
                 CASE vacation_status
                    WHEN '0' THEN 'Approval'
                    WHEN '1' THEN 'Cancel'
                    WHEN '2' THEN 'Progress'
                 END AS status,
                 created_date as cdate,
                 vacation_acl as acl,
                 employeefirstname,employeelastname,posisi    
                 FROM vacation
                 INNER JOIN employee ON employee.employee_id=vacation.employee_id
                 WHERE vacation.employee_id=".$ID."
                 AND created_date = '".$Date."'
                 ORDER BY ABS(vacation_id) ASC  
               ";
          $Q = $this->db->query($sql);
          return $Q->result_array();     
    }
    
    function getVacationList($user){
        $sql = " SELECT DATE_FORMAT(created_date,'%d/%m/%Y') as created_date,
                 MIN(DATE_FORMAT(vacation_date,'%d/%m/%Y')) as date_from,
                 MAX(DATE_FORMAT(vacation_date,'%d/%m/%Y')) as date_to,
                 vacation_desc,vacation_address,COUNT(vacation_date) as total,
                 CASE vacation_status
                    WHEN '0' THEN 'Approval'
                    WHEN '1' THEN 'Cancel'
                    WHEN '2' THEN 'Progress'
                 END AS status,
                 vacation_acl as acl,vacation.employee_id    
                 FROM vacation
                 INNER JOIN employee ON employee.employee_id=vacation.employee_id
                 WHERE vacation.employee_id=".$user."
                 GROUP BY created_date,vacation.employee_id
                 ORDER BY created_date ASC  
               ";
        $Q = $this->db->query($sql);
        return $Q->result_array();      
    }
    
    function getReqVacationList($dep='',$acl=''){
        /** If Group Coordinator switch to Partner **/
        if($acl=='Group Coordinator')
            $acl = 'Partner';
        
        $sql = " SELECT vacation.employee_id,employeefirstname,employeelastname,
                 DATE_FORMAT(created_date,'%d/%m/%Y') as created_date,
                 MIN(DATE_FORMAT(vacation_date,'%d/%m/%Y')) as date_from,
                 MAX(DATE_FORMAT(vacation_date,'%d/%m/%Y')) as date_to,
                 vacation_desc,vacation_address,COUNT(vacation_date) as total,
                 CASE vacation_status
                    WHEN '0' THEN 'Approval'
                    WHEN '1' THEN 'Cancel'
                    WHEN '2' THEN 'Progress'
                 END AS status,
                 vacation_acl as acl    
                 FROM vacation
                 INNER JOIN employee ON employee.employee_id=vacation.employee_id 
                 WHERE vacation_status=2 ";
        if($dep) 
            $sql.=" AND department_id=".$dep;
        $sql.=" AND vacation_acl LIKE '%".$acl."%' ";
        $sql.=" AND vacation.employee_id <> ".$this->session->userdata('employee_id');       
        $sql.=" GROUP BY created_date,vacation.employee_id ORDER BY created_date ASC ";
        $Q = $this->db->query($sql);
        return $Q->result_array();      
    }
    
    function getApprovalVacation($req='',$status=2){
        $sql = " SELECT v.employee_id,e.employeefirstname,e.employeelastname,
                 DATE_FORMAT(v.created_date,'%d/%m/%Y') as created_date,
                 MIN(DATE_FORMAT(v.vacation_date,'%d/%m/%Y')) as date_from,
                 MAX(DATE_FORMAT(v.vacation_date,'%d/%m/%Y')) as date_to,
                 v.vacation_desc,v.vacation_address,COUNT(v.vacation_date) as total,
                 CASE v.vacation_status
                    WHEN '0' THEN 'Approval'
                    WHEN '1' THEN 'Cancel'
                    WHEN '2' THEN 'Progress'
                 END AS status,
                 v.vacation_acl as acl,e2.employeefirstname as CancelName,e3.employeefirstname as app_name    
                 FROM vacation v
                 INNER JOIN employee e ON e.employee_id=v.employee_id
                 LEFT JOIN employee e2 ON e2.employee_id=v.cancel_approval
                 LEFT JOIN employee e3 ON e3.employee_id=v.hrd_approval
                 WHERE e.employee_id<>0 ";
          if($req)
                $sql.= " AND v.vacation_acl LIKE '%".$req."%'";  
          if($status==0)
                $sql.= " AND v.vacation_status=0";
          else
                $sql.= " AND v.vacation_status=".$status;              
          $sql.=" GROUP BY v.created_date,v.employee_id
                 ORDER BY v.created_date ASC ";
        $Q = $this->db->query($sql);
        return $Q->result_array();
    }
    
    function getAppVacationList($user){
        $sql = " SELECT vacation.employee_id,employeefirstname,employeelastname,
                 DATE_FORMAT(created_date,'%d/%m/%Y') as created_date,
                 MIN(DATE_FORMAT(vacation_date,'%d/%m/%Y')) as date_from,
                 MAX(DATE_FORMAT(vacation_date,'%d/%m/%Y')) as date_to,
                 vacation_desc,vacation_address,COUNT(vacation_date) as total,
                 CASE vacation_status
                    WHEN '0' THEN 'Approval'
                    WHEN '1' THEN 'Cancel'
                    WHEN '2' THEN 'Progress'
                 END AS status,
                 vacation_acl as acl    
                 FROM vacation
                 INNER JOIN employee ON employee.employee_id=vacation.employee_id
                 WHERE department_id=".$user."
                 AND (vacation_status=2 OR vacation_status=0) ";
        if($this->session->userdata('pos')<>'Manager')
            $sql.= " AND vacation.employee_id <> ".$this->session->userdata('employee_id');       
        
        if(($this->session->userdata('aclname')=='Partner') || ($this->session->userdata('aclname')=='Group Coordinator'))
            $sql.= " AND pic_approval = ".$this->session->userdata('employee_id');
        elseif($this->session->userdata('aclname')=='Manager In Charge')
            $sql.= " AND mic_approval = ".$this->session->userdata('employee_id');
        elseif($this->session->userdata('aclname')=='Senior In Charge')
            $sql.= " AND aic_approval = ".$this->session->userdata('employee_id');      
             
        $sql.=" GROUP BY created_date,vacation.employee_id
                 ORDER BY created_date ASC ";
        $Q = $this->db->query($sql);
        return $Q->result_array();      
    }
    
    function getUserBalanced($user){
        $sql = " SELECT vacation_total FROM employee_vacation WHERE employee_id=".$user;
        $sql.= " AND vacation_year=2013";
        $Q = $this->db->query($sql);
        $data = $Q->row_array();
        if($Q->num_rows()>0)
            $balanced = $data['vacation_total'];
        else
            $balanced = 0;
        return $balanced;          
    }
    
    function saveVacation($date,$status){
        $request = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $this->input->post('created'));
        /*$date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $form['date_from']);
        $date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $form['date_to']);
        */
        $value = array(
            'employee_id'      => $this->session->userdata('employee_id'),
            'vacation_date'    => $date,
            'vacation_status'  => 2,
            'vacation_desc'    => $this->input->post('content'),
            'vacation_address' => $this->input->post('address'),
            'created_date'     => $request,
            'vacation_acl'     => $this->session->userdata('vacation_acl')   
        );
        
        if($status='Save'):
            $this->db->insert('vacation',$value);
        else:
            $this->db->where('created_date',$date);
            $this->db->where('employee_id',$this->session->userdata('employee_id'));
            $this->db->update('vacation',$value);
        endif;
    }
    
    function getAppVacation($user,$date){
        switch($this->session->userdata('aclname')):
            case 'Auditor In Charge'   : $acl="Manager In Charge";
                                         $s=$this->session->userdata('employee_id');
                                         break;
            case 'Manager In Charge'   : $acl="Partner";
                                         $m=$this->session->userdata('employee_id');
                                         break;
            case 'Partner'             : $acl="HRD In Charge";
                                         $p=$this->session->userdata('employee_id');
                                          break;
            case 'Group Coordinator'   : $acl="HRD In Charge";
                                         $p=$this->session->userdata('employee_id');
                                         break;                              
            default : $acl = "HRD In Charge";
                      $h = $this->session->userdata('employee_id');  
                      break;                                                  
        endswitch;
            
        $value['vacation_acl'] = $acl;
        
        if($this->session->userdata('aclname')=='Partner')
            $value['pic_approval'] = $p;
        elseif($this->session->userdata('aclname')=='Group Coordinator')
            $value['pic_approval'] = $p;    
        elseif($this->session->userdata('aclname')=='Manager In Charge')
            $value['mic_approval'] = $m;
        elseif($this->session->userdata('aclname')=='Auditor In Charge')
            $value['aic_approval'] = $s;    
        else
            $value['hrd_approval'] = $h;
                
        $this->db->where('employee_id',$user);
        $this->db->where('created_date',$date);
        $this->db->update('vacation',$value);
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
    
    function getAppVacationByHRD($user,$date){
        
        $value['vacation_acl'] = "HRD";
        $value['hrd_approval'] = $this->session->userdata('employee_id');
        $value['vacation_status'] = 0;
        $this->db->where('employee_id',$user);
        $this->db->where('created_date',$date);
        //$this->db->update('vacation',$value);
        
        $vacation = $this->getVacationDetails($user,$date);
        foreach($vacation as $row):
            //$date = '04/30/2009';
            $ts = strtotime($row['date']);
            echo $dow = date('w',$ts); // calculate the number of days since Monday
            $form['year'] = substr($row['date'],0,4);
            $form['week'] = $dow;
            $EmployeeWeek = $this->checkTimesheetWeek($form['week'],$form['year']);
            $timesheet_status_id = 0;
            if ( count($EmployeeWeek) == 0)
			     $timesheet_status_id = $this->insertTimesheetWeekly($form['week'],$form['year']);
            else 
			     $timesheet_status_id = $EmployeeWeek['timesheet_status_id'] ;
        
            $val['timesheet_status_id'] = $timesheet_status_id;
            $val['project_id']          = '1';
            $val['employee_id']         = $row['employee_id'];
            $val['week']                = $form['week'];
            $val['year']                = $form['year'];        
            $val['job_id']              = 11;
            $val['notes']               = $row['vacation_desc'];
            $val['timesheetdate']       = $row['date'];
            $val['hour']                = 8;
            $val['transport_type']      = 1;
            $val['sysdate']             = $row['cdate'];
            $val['sysuser']             = $row['employee_id'];
            $val['timesheetstatus']     = 2;
            $this->db->insert('timesheet',$val);
        endforeach;
        /*$sql = "insert into timesheet( timesheet_status_id, project_id, employee_id, week, year, job_id, notes, timesheetdate,hour, overtime, cost, transport_type,  sysdate,sysuser) 
					values ($timesheet_status_id,'$form[project_id]','".$this->session->userdata('employee_id')."', '$form[week]' , '$form[year]',
					'$form[job_id]', '$form[notes]', '$timesheetdate', $form[hour], $form[overtime],  $form[cost], $form[transport_type],
					now(),'".$this->session->userdata('employee_id')."')";
        */
        //$this->db->query($sql);            
    }
    
    function getCancelVacation($user,$date){
        $value['vacation_status'] = 1;
        $value['cancel_approval'] = $this->session->userdata('employee_id');
        $this->db->where('employee_id',$user);
        $this->db->where('created_date',$date);
        $this->db->update('vacation',$value);
    }
    
    function getDeleteVacation($ID,$date){
        $this->db->where('employee_id',$ID);
        $this->db->where('created_date',$date);
        $this->db->delete('vacation');
    }
    
    function getDeleteVacationDetails($ID){
        $this->db->where('SHA1(vacation_id)',$ID);
        $this->db->delete('vacation');
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
    
    //  insertTimesheetWeekly
	/*-------------------------------------------------------------------------------------*/
	public function insertTimesheetWeekly( $week, $year)  {
		$sql = "insert into timesheet_status (week, year, employee_id, sysdate, sysuser)
			values ('". $week ."', '". $year ."','".$this->session->userdata('employee_id')."',now(),'".$this->session->userdata('employee_id')."')"; 
		$this->db->query($sql);			
		return  $this->db->insert_id();;
	}
}
