<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class adminModel extends CI_Model {

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
	//  getUser
	/*-------------------------------------------------------------------------------------*/
	public  function getUser($filter, $limit=0, $offset=0) {
		$whereClause 	= "";
		$selectClause	= $limit ? "a.user_id, a.pass, a.employee_id, a.email, a.acl, a.user_last_login, CONCAT(app.employeefirstname,' ',app.employeemiddlename,' ',app.employeelastname) as approval,
									b.employeeid,IF(a.user_active=1,'Active','In-Active') as status,b.employeenickname, b.employeefirstname, b.employeemiddlename, b.employeelastname,b.employeetitle, 
									c.department, d.aclname title " : "count(*) total";
		$limitClause	= $limit ? "order by b.employeenickname limit $limit offset $offset" : "";
		if(isset($filter['nik'])) $whereClause .= " and b.employeeid like '%$filter[nik]%'";
		if(isset($filter['nickname'])) $whereClause .= " and ( b.employeefirstname like '%$filter[nickname]%' or b.employeemiddlename like '%$filter[nickname]%' or b.employeelastname like '%$filter[nickname]%')";
		if(isset($filter['group'])) $whereClause .= " and c.department like  '%$filter[group]%'";
		if(isset($filter['approval'])) $whereClause .= " and CONCAT(app.employeefirstname,' ',app.employeemiddlename,' ',app.employeelastname) like  '%$filter[approval]%'";
		
		$sql = "select $selectClause  
					from sys_user a
					inner join employee b on b.employee_id  = a.employee_id
					inner join department c on b.department_id = c.department_id
					left join employee app on app.employee_id = b.approval_id
					inner join acl d on a.acl = d.acl 
				where 1=1 $whereClause $limitClause";
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}
    
    /** getVacationUser **/
    public function getUserVacation(){
        $sql = " SELECT b.employeeid,b.employee_id,b.employeenickname, b.employeefirstname,b.employeemiddlename,
                 b.employeelastname,c.department, d.aclname title,
                 (SELECT SUM(vacation_total) FROM employee_vacation ev WHERE ev.vacation_year=2013 AND ev.employee_id=b.employee_id) AS total   
                 FROM employee b
                 INNER JOIN sys_user u ON u.employee_id=b.employee_id
                 inner join department c on b.department_id = c.department_id
				 inner join acl d on d.acl = u.acl 
                 WHERE u.user_active=1    
                 ORDER BY b.employeefirstname,b.employeemiddlename,b.employeelastname
               ";
        $Q = $this->db->query($sql);
        return $Q->result_array();       
    }
    
    public function getVacationData($user,$year){
        $sql = " SELECT * FROM employee_vacation WHERE employee_id=".$user;
        $sql.= " AND vacation_year='".$year."'";
        $Q=$this->db->query($sql);
        return $Q->row_array();
    }
    
    public function getSaveVacation($user,$year,$total){
        $value = array('employee_id'=>$user,'vacation_year'=>$year,'vacation_total'=>$total);
        $this->db->insert('employee_vacation',$value);
    } 
    
    public function getUpdateVacation($user,$year,$total){
        $value = array('vacation_total'=>$total);
        $this->db->where('employee_id',$user);
        $this->db->where('vacation_year',$year);
        $this->db->update('employee_vacation',$value);
    }

	//  getUserDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getUserDetail($user_id) {
		$sql = "select a.*, b.*,c.Department 
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
				from employee a 
				where employee_id not in ( select employee_id from sys_user )
				order by  employeefirstname, employeemiddlename, employeelastname ";
		return $this->rst2Array($sql);
	}
	

	//  getUserEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getUserEmployeeNIK($employee_id) {
		$sql = "select employeeid nik from employee where employee_id =$employee_id ";
		return $this->rst2Array($sql,10);
	}


	//  getUserEmployee
	/*-------------------------------------------------------------------------------------*/
	public  function getUserEmployeeActive() {
		$sql = "select employee_id, employeefirstname, employeemiddlename, employeelastname 
				from employee a 
				where employee_id in ( select employee_id from sys_user )
				order by  employeefirstname, employeemiddlename, employeelastname ";
		return $this->rst2Array($sql);
	}
	
	
	//  getUserACL
	/*-------------------------------------------------------------------------------------*/
	public  function getUserACL() {
		$sql = "select * from acl order by id";
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
			//$this->load->library('encrypt');
			$nik = '';
			$aNik = $this->getUserEmployeeNIK($form['employee_id']);
      if (count($aNik) > 0){
			  $nik = $aNik['nik'];
		  }
		  /*
			$sql = "insert into sys_user ( pass, employee_id, acl, user_active, sysdate, sysuser) 
				values ('".$this->encrypt->encode($nik)."','$form[employee_id]', '$form[acl]',
				'$form[user_active]', now(), '".$this->session->userdata('user_id')."')"; 
			*/	
			$sql = "insert into sys_user ( passtext, employee_id, acl, user_active, sysdate, sysuser) 
				values ('".$nik."','$form[employee_id]', '$form[acl]',
				'$form[user_active]','".date('Y-m-d H:i:s')."', '".$this->session->userdata('user_id')."')"; 

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
		redirect('/admin/userEdit/'.$id .'/SAVED');
	}	
	
	
		//  savePassword
	/*-------------------------------------------------------------------------------------*/
	public function savePassword($password){
		//$this->load->library('encrypt');
		/*
		$sql = "update sys_user set pass ='".$this->encrypt->encode($password)."',
					sysuser	= '".$this->session->userdata('user_id')."' , 
					sysdate = now()
				where user_id = '".$this->session->userdata('user_id')."'";
		*/		
		$sql = "update sys_user set passtext ='".$password."',
					sysuser	= '".$this->session->userdata('user_id')."' , 
					sysdate = '".date('Y-m-d H:i:s')."'
				where user_id = '".$this->session->userdata('user_id')."'";

		$this->db->query($sql);		
	}

		//  savePassword
	/*-------------------------------------------------------------------------------------*/
	public function resetPassword($user_id, $nik){
		//$this->load->library('encrypt');
		/*
		$sql = "update sys_user set pass ='".$this->encrypt->encode($nik)."',
					sysuser	= '".$this->session->userdata('user_id')."' , 
					sysdate = now()
				where user_id = '".$user_id."'";
		*/
		$sql = "update sys_user set passtext ='".$nik."',
					sysuser	= '".$this->session->userdata('user_id')."' , 
					sysdate = '".date('Y-m-d H:i:s')."'
				where user_id = '".$user_id."'";
		
		$this->db->query($sql);		
	}





	/*-------------------------------------------------------------------------------------*/
	public  function getUserSync() {
		//$sql = "select employee_id, nik from employee ";
		$sql = "select employee_id, employeeid from employee ";
		return $this->rst2Array($sql);
	}


	/*-------------------------------------------------------------------------------------*/
	public function syncPassword($employee_id, $password){
		$this->load->library('encrypt');
		$sql = "update sys_user set pass ='".$this->encrypt->encode($password)."'
				where employee_id = '".$employee_id."'";
		$this->db->query($sql);		
	}
	
	// added ilham@21april2011
	public function getAdminmenu(){
		$sql = "select * from sys_menu where parentid ='admin' and lactive = 1";
		return $this->rst2Array($sql);
	}
	

}
/* End of file mainModel.php */