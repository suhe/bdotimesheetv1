<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site 
{

	function __construct()
	{
		$this->obj =& get_instance();
	}

	public function is_auth()
	{
		$is_logged = FALSE;
		
		if ($this->obj->session) {

			//If user has valid session, and such is logged in
			if ($this->obj->session->userdata('is_auth'))
			{
				$is_logged = true;
			}
		}
		return $is_logged;
	} 
	
	function test_sitex() {
		echo 'Response Test SITE CLASS';	
	}
}