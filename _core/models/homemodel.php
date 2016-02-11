<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Homemodel extends CI_Model {

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

	public  function getLogin($nik) {
		$sql = "select a.*,b.nik,b.project_title, c.aclname, b.employeeid,b.employeefirstname, b.employeemiddlename, b.employeelastname,
						b.department_id,b.posisi
						from sys_user a 
						inner join employee b on a.employee_id=b.employee_id 
						inner join acl c on a.acl=c.acl
						where b.employeeid ='$nik'";
		return $this->rst2Array($sql,10);
	}
	
	public  function getMenu() {
		$sql = "select * from sys_menu where parentid = '0' order by menuid";
		return $this->rst2Array($sql);
	}

	public  function getMenuChild($parentID) {
		$sql = "select * from sys_menu where parentid = '". $parentID ."' and menu <> '". $parentID ."'";
		return $this->rst2Array($sql);
	}


}
