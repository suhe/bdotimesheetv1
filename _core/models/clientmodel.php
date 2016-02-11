<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class clientModel extends CI_Model {

	public function __construct() {
		parent:: __construct();
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


	//  getClient
	/*-------------------------------------------------------------------------------------*/
	public  function getClient($filter, $limit=0, $offset=0) {
		$whereClause 	= "";
		//if ( $this->session->userdata('acl') == 2) $whereClause .='	and client_id in ( select client_id from client_department where manager_id = '.$this->session->userdata('manager_id').' )';
		//if ( $this->session->userdata('acl') == 3) $whereClause .='	and client_id in ( select client_id from client_department where department_id = '.$this->session->userdata('department_id').' )';

		$selectClause	= $limit ? "*" : "count(*) total";
		$limitClause	= $limit ? "order by client_name limit $limit offset $offset" : "";
		if(isset($filter['client_no'])) $whereClause .= " and client_no like '%$filter[client_no]%'";
		if(isset($filter['client_name'])) $whereClause .= " and client_name like '%$filter[client_name]%'";
		if(isset($filter['address'])) $whereClause .= " and address like '%$filter[address]%'";
		
		$sql = "select $selectClause from client where 1=1 $whereClause $limitClause";
		return $limit ? $this->rst2Array($sql) : $this->rst2Array($sql, 11);
	}

	//  getClientDetail
	/*-------------------------------------------------------------------------------------*/
	public  function getClientDetail($client_id) {
		$sql = "select * from client where client_id ='$client_id'";
		return $this->rst2Array($sql,10);
	}

	//  saveClient
	/*-------------------------------------------------------------------------------------*/	
	public  function saveClient($form) {
		if ( $form['client_id'] === '0' ) {
			$sql = "insert into client ( client_no, client_name, address, phone, fax, contact,
						contact_email, website, lob, sysdate, sysuser) 
					values ('$form[client_no]', '$form[client_name]' , '$form[address]',
							 '$form[phone]', '$form[fax]', '$form[contact]',
							'$form[contact_email]', '$form[website]', '$form[lob]', now(),
							".$this->session->userdata('employee_id').")"; 
		}
		else {
			$sql = "update client set client_no ='$form[client_no]',
					client_name ='$form[client_name]' , 
					address='$form[address]',
					phone='$form[phone]',  
					fax='$form[fax]', 
					contact ='$form[contact]', 
					contact_email = '$form[contact_email]',
					website ='$form[website]',
					lob ='$form[lob]',
					sysdate = '".date('Y-m-d H:i:s')."',
					sysuser = ".$this->session->userdata('employee_id')."
					where client_id = $form[client_id]";
		}
		$this->db->query($sql);		

		if ( $form['client_id'] === '0' ) {
			$id = $this->db->insert_id();
/*
			$sql = "insert into client_department ( client_id, manager_id, department_id )
					values ('". $id ."', '".$this->session->userdata('manager_id')."' , 
					'".$this->session->userdata('department_id')."')"; 
			$this->db->query($sql);		
*/
		} 
		else {
			$id = $form['client_id'];	
		}
		redirect('/client/Edit/'.$id .'/SAVED');
	}	


}
/* End of file mainModel.php */