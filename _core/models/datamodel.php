<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dataModel extends CI_Model {

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
	
	public function getLastNikEmployee(){
		$sql_id = " SELECT SUBSTR(EmployeeID,3,3) as id FROM employee ORDER BY ABS(employee_id) DESC ";
		$q = $this->db->query($sql_id);
        $row = $q->row_array();
		if($row)
			$id = autocode(number_format($row['id'],0)+1);
		else
			$id = autocode(0);
		return  substr(date('Y'),2,2).$id;	
	}

	//  getEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getEmployee($filter, $limit=0, $offset=0) {
		$whereClause 	= "";
		$selectClause	= $limit ? " b.*,c.*, b.project_title,su.passtext as passtext,d.aclname position, b.employeeid,b.employeenickname,DATE_FORMAT(b.employeehiredate,'%d-%m-%Y') as employeehiredate,IF(employeestatus=0,'Permanent','Contract') as employeestatus, b.employeefirstname, b.employeemiddlename, b.employeelastname,b.employeetitle,b.employeeemail,c.department " : "count(*) total";
		$limitClause	= $limit ? "order by b.employeenickname limit $limit offset $offset" : "";
		if(isset($filter['nik'])) $whereClause .= " and b.employeeid like '%$filter[nik]%'";
		if(isset($filter['nickname'])) $whereClause .= " and b.employeenickname like '%$filter[nickname]%' or b.employeefirstname like '%$filter[nickname]%' or b.employeelastname like '%$filter[nickname]%' ";
		if(isset($filter['position'])) $whereClause .= " and d.aclname like '%$filter[position]%'";
		if(isset($filter['group'])) $whereClause .= " and c.department like  '%$filter[group]%'";
		
		$sql = "select $selectClause  
				from employee b 
				left join sys_user su ON su.employee_id=b.employee_id
                left join department c on b.department_id = c.department_id
				left join acl d on d.acl = b.project_title
				where 1=1 $whereClause $limitClause";
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
//	
	
	/*-------------------------------------------------------------------------------------*/
	//  getUserEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getUserEmployee($filter = array()) {
		$sql = "select a.employee_id,a.employeefirstname,a.employeemiddlename,a.employeelastname 
			from employee a inner join sys_user su on su.employee_id=a.employee_id
			where su.user_active=1
			".(isset($filter["department_id"]) ? "and department_id = ".$filter["department_id"] : "")." 	
			order by  a.employeefirstname,a.employeemiddlename,a.employeelastname";
		return $this->rst2Array($sql);
	}

	//  getEmployeeDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getEmployeeDetail($employee_id) {
		$sql = "select employee.employee_id, approval_id,  project_title, employeefirstname,employeemiddlename,employeelastname,sys_user.user_active,passtext,
				employeenickname,employeetitle,employeeid,DATE_FORMAT(employeehiredate,'%d/%m/%Y') as employeehiredate,employeestatus,employeeemail,department_id,employeestatus
				from employee 
				left join sys_user on sys_user.employee_id = employee.employee_id
				where employee.employee_id  ='$employee_id'";
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
		$date = substr($form['employeehiredate'],6,4).'-'.substr($form['employeehiredate'],3,2).'-'.substr($form['employeehiredate'],0,2);
        if ( $form['employee_id'] === '0' ) {
			$sql = "insert into employee ( employeeid, approval_id,  project_title, employeefirstname, employeemiddlename, 
					employeelastname, employeenickname,employeetitle,employeeemail, department_id,employeehiredate,employeestatus,
					sysdate, sysuser) 
					values ('$form[employeeid]','$form[approval_id]','$form[project_title]', '$form[employeefirstname]' , '$form[employeemiddlename]',
					'$form[employeelastname]', '$form[employeenickname]','$form[employeetitle]',
					'$form[employeeemail]','$form[department_id]','$date',$form[employeestatus],'".date('Y-m-d H:i:s')."','".$this->session->userdata('user_id')."')"; 
		}
		else { 
			$sql = "update employee set employeeid ='$form[employeeid]',
					project_title= '$form[project_title]' , 
					approval_id= '$form[approval_id]' , 
                    employeehiredate	= '$date',
                    employeestatus	    = '$form[employeestatus]' ,
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
		redirect('/data/employeeEdit/'.$id .'/SAVED');
	}	
	
	//  saveOutsource
	/*-------------------------------------------------------------------------------------*/
	public  function saveOutsource($form) {
		$date = substr($form['employeehiredate'],6,4).'-'.substr($form['employeehiredate'],3,2).'-'.substr($form['employeehiredate'],0,2);
		
		if ( $form['employee_id'] === '0' ) {
			$sql = "insert into employee ( employeeid, approval_id,  project_title, employeefirstname, employeemiddlename,
			employeelastname, employeenickname,employeetitle,employeeemail, department_id,employeehiredate,employeestatus,
			sysdate, sysuser)
			values ('$form[employeeid]','$form[approval_id]','$form[project_title]', '$form[employeefirstname]' , '$form[employeemiddlename]',
			'$form[employeelastname]', '$form[employeenickname]','$form[employeetitle]',
			'$form[employeeemail]','$form[department_id]','$date',$form[employeestatus],'".date('Y-m-d H:i:s')."','".$this->session->userdata('user_id')."')";
		}
		else {
			$sql = "update employee set employeeid ='$form[employeeid]',
			project_title= '$form[project_title]' ,
			approval_id= '$form[approval_id]' ,
			employeehiredate	= '$date',
			employeestatus	    = '$form[employeestatus]' ,
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
		
		/**
		 * Sys User Update
		 */
		if ( $form['employee_id'] === '0' ) {
			$id = $this->db->insert_id();
			
			//update user
			$this->data = array(
				'employee_id' => $id,
				'acl' => '777',
				'user_active' => $form['user_active'],	
				'passtext' => $form['passtext'],	
				'sysuser' => $this->session->userdata('employee_id'),
				'sysdate' => date('Y-m-d H:i:s')
			);
			$this->db->insert('sys_user',$this->data);
			
		}
		else {
			$id = $form['employee_id'];
			$this->data = array(
					'acl' => '777',
					'user_active' => $form['user_active'],
					'sysuser' => $this->session->userdata('employee_id'),
					'sysdate' => date('Y-m-d H:i:s')
			);
			$this->db->where('employee_id',$form["employee_id"]);
			$this->db->update('sys_user',$this->data);
		}
		redirect('/data/outsourceEdit/'.$id .'/SAVED');
	}
	
	
	//  getDepartment
	/*-------------------------------------------------------------------------------------*/
	public  function getDepartment() {
		$sql = "select department_id, departmentcode, department
				from department a
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
		redirect('/data/department/0');
	}	


	//  getJob
	/*-------------------------------------------------------------------------------------*/
	public  function getJob() {
		$sql = "select a.job_id, a.job_no, a.job, b.jobtype
						from job a
						left join job_type b on a.jobtype_id = b.jobtype_id
						order by a.job_no";
		return $this->rst2Array($sql);
	} 
	
	
	//  getJobDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getJobDetail($job_id) {
		$sql = "select job_id, job_no, job, jobtype_id
				from job where job_id  ='$job_id'";
		return $this->rst2Array($sql,10);
	}
	
	
	//  saveJob
	/*-------------------------------------------------------------------------------------*/	
	public  function saveJob($form) {
		if ( $form['job_id'] === '0' ) {
			//$sql = "insert into job ( job_id, job_no, job, jobtype_id, sysuser, sysdate) 
			$sql = "insert into job ( job_id, job_no, job,jobtype_id, sysuser, sysdate) 
					values ('$form[job_id]', '$form[job_no]','$form[job]',$form[jobtype_id],
					'".date('Y-m-d H:i:s')."', '".$this->session->userdata('user_id')."')"; 
		}
		else {
			$sql = "update job  set 
					job_no					='$form[job_no]',
					job							= '$form[job]' , 
					jobtype_id 			='$form[jobtype_id]',
					sysuser	= '".$this->session->userdata('user_id')."', 
					sysdate	= '".date('Y-m-d H:i:s')."' 
					where job_id = $form[job_id]";
		}
		$this->db->query($sql);		
		
		if ( $form['job_id'] === '0' ) {
			$id = $this->db->insert_id();
   		if (strtolower(substr($form['job_no'], 0, 3)) == "hrd"){
   		  $sql="insert into project_job ( project_id, job_id, sysdate, sysuser) 
   		         values (1, $id,'".date('Y-m-d H:i:s')."', '".$this->session->userdata('user_id')."')"; 
            $this->db->query($sql);		
            //echo $sql;   		         
   		}
		} 

		redirect('/data/job/0');
	}	

	//  getJob -- iman edit 2
	/*-------------------------------------------------------------------------------------*/
	public  function getJobType() {
		$sql = "select a.jobtype_id, a.jobtype_no, a.jobtype, b.department
						from job_type a
						left join department b on a.department_id = b.department_id
						order by a.jobtype_no";
		return $this->rst2Array($sql);
	} 
    
    //  getHoliday -- 2
	/*-------------------------------------------------------------------------------------*/
	public  function getHoliday() {
		$sql = "select DATE_FORMAT(holiday_date,'%d/%m/%Y') as date,holiday_desc,sha1(holiday_id) as holiday_id
		        from holiday
		        order by holiday_date DESC";
		return $this->rst2Array($sql);
	} 
    
    public function getRemoveHoliday($id){
        $this->db->where('sha1(holiday_id)',$id);
        $this->db->delete('holiday');
    }
	
	//  getJobDetail -- iman edit 2
	/*-------------------------------------------------------------------------------------*/
	public  function getJobTypeDetail($jobtype_id) {
		$sql = "select jobtype_id, jobtype_no, jobtype, department_id
				from job_type where jobtype_id  ='$jobtype_id'";
		return $this->rst2Array($sql,10);
	}
	
	// getJobDepartment -- iman edit 2
	public  function getJobDepartment() {
		$sql = "select department_id, department
				from department order by  department";
		return $this->rst2Array($sql);
	}
	
	
	//  saveJob
	/*-------------------------------------------------------------------------------------*/	
	public  function saveJobType($form) {
		if ( $form['jobtype_id'] === '0' ) {
			$sql = "insert into job_type ( jobtype_id, jobtype_no, jobtype, department_id,  sysdate, sysuser) 
					values ('$form[jobtype_id]', '$form[jobtype_no]', '$form[jobtype]', '$form[department_id]',		
					'".date('Y-m-d H:i:s')."', '".$this->session->userdata('user_id')."')"; 
		}
		else {
			$sql = "update job_type  set 
					jobtype_no ='$form[jobtype_no]',
					jobtype		= '$form[jobtype]' , 
					department_id		= '$form[department_id]' , 
					sysuser	= '".$this->session->userdata('user_id')."', 
					sysdate	= '".date('Y-m-d H:i:s')."' 
					where jobtype_id = $form[jobtype_id]";
		}
		$this->db->query($sql);		
		

		redirect('/data/jobtype/0');
	}
    
    public  function saveHoliday($form) {
        $date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $form['holiday_date']);
        
		if ( $form['holiday_id'] === '0' ) {
			$sql = "insert into holiday ( holiday_id,holiday_date,holiday_desc) 
					values ('$form[holiday_id]', '$date','$form[holiday_desc]')"; 
		}
		else {
			$sql = "update holiday  set 
					holiday_date ='$date',
					holiday_desc ='$form[holiday_desc]' 
					where holiday_id = $form[holiday_id]";
		}
		$this->db->query($sql);		
		
		redirect('/data/holiday/0');
	}	

	//  getJobDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getProjectTitle() {
		$sql = "select acl project_title, aclname project_title_label  from acl where acl not in ( '008', '009') order by acl ";
		return $this->rst2Array($sql);
	}
	
}
/* End of file mainModel.php */