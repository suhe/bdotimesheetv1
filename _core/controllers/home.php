<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller{
	public $data;
	function __construct() 	{
		parent::__construct();	
	}
	
	function index($log='',$msg='') 	{
		if($log=='')
			redirect('home/index/'.sha1('bdoki'),301);
		$this->data['msg']		= $msg;
		$this->load->view('home_login',$this->data);
	}
		
		/* ------------------------------------------------------------------------------------- */
		// page login
		/* ------------------------------------------------------------------------------------- */
	public function login($msg = null) {
		$this->data ['msg'] = $msg;
		$nik = $this->input->post ( 'nik' );
		$this->data ['nik'] = $nik;
		$result = $this->homeModel->getLogin ( $nik );
		
		if (strlen ( $this->data ['msg'] ) > 0) {
			$this->load->view ( 'home_login', $this->data );
		} else {
			if ($result) {
				
				if ($this->input->post ( 'pass' ) == $result ['passtext']) {
					$this->session->set_userdata ( 'user_id', $result ['user_id'] );
					$this->session->set_userdata ( 'employee_id', $result ['employee_id'] );
					$this->session->set_userdata ( 'employeeid', $result ['employeeid'] );
					$this->session->set_userdata ( 'passtext', $result ['passtext'] );
					$this->session->set_userdata ( 'employee', $result ['employeefirstname'] . ' ' . $result ['employeemiddlename'] . ' ' . $result ['employeelastname'] );
					$this->session->set_userdata ( 'acl', $result ['acl'] );
					$this->session->set_userdata ( 'aclname', $result ['aclname'] );
					$this->session->set_userdata ( 'project_title', $result ['project_title'] );
					$this->session->set_userdata ( 'department_id', $result ['department_id'] );
					$this->session->set_userdata ( 'is_auth', TRUE );
					
					if ($result ['passtext'] == $result ['employeeid']) {
						redirect ( 'admin/changePassword/' );
					} else {
						redirect ( 'admin/app/' );
						/*if ($result ['acl'] == "09") {
							redirect ( 'admin/user/' );
						} elseif ($result ['acl'] == "008" || $result ['acl'] == "01" || $result ['acl'] == "02" || $result ['acl'] == "03") {
							redirect ( 'project/' );
						} else {
							redirect ( 'timesheet/' );
						}*/
					}
				} else {
					$this->data ['msg'] = 'Invalid Password';
					$this->load->view ( 'home_login', $this->data );
				}
				// }
			} else {
				$this->data ['msg'] = 'Invalid NIK or Password';
				$this->load->view ( 'home_login', $this->data );
			}
		}
	}	// END LOGIN
	/*-------------------------------------------------------------------------------------*/
	//  logout
	/*------------------------------------------------------------------------------------*/
	function logout() 	{
		$this->session->sess_destroy();
		redirect();
	}	// END LOGOUT

}