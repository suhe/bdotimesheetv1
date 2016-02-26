<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

Class MY_Controller extends CI_Controller {
	public $data;
	
	public function __construct()	{
		parent::__construct();
		$this->load->model('homeModel');
		$this->data['base_url'] = $this->config->item('base_url');
		$this->data['site'] 	= $this->data['base_url'];
		$this->data['is_auth']  = $this->session->userdata('is_auth');
		$this->data['nik'] 		= $this->session->userdata('nik');
		$this->data['err'] 		= '';
		$this->rpp 	  			= $this->session->userdata('rpp') ? $this->session->userdata('rpp') : 20;

		$unlocked = array('home','login');
		if ( ! $this->site->is_auth() AND ! in_array(strtolower(get_class($this)), $unlocked)) 		{
			redirect('home/login/');
		}

	}
	
	/*-------------------------------------------------------------------------------------*/
	//  SETPAGING
	/*-------------------------------------------------------------------------------------*/
	public function setPaging($totalRow, $cPage, $limit=0) {
		$pg['r']	= $this->rpp;
		$pg['t']	= $totalRow;
		$pg['l']	= ceil($totalRow/$limit);
		$pg['c']	= $cPage;
		$pg['p']	= $pg['c'] > 1 ? $pg['c'] - 1 : 1;
		$pg['n']	= $pg['c'] + 1 == $pg['l'] ? $pg['l'] : $pg['c'] + 1;
		$pg['n']	= $pg['n'] > $pg['l'] ? $pg['l'] : $pg['n'];
		$pg['o']	= $limit * $pg['c'] - $limit;
		return $pg;
	} // END SETPAGING

	/*-------------------------------------------------------------------------------------*/
	//  PRIVATE :: GETMENU
	/*-------------------------------------------------------------------------------------*/
	public  function getMenu() {
		$this->data['menu'] = "";
		switch ( $this->session->userdata('acl') ){
			//ASS + AIC
			case '041':
			case '042':
				//$acl = array( '04000000', '05000000','07000000', '99000000');
                $acl = array( '04000000','07000000', '99000000');
				break;

			//Outspirce
			case '777':
				//$acl = array( '04000000', '05000000','07000000', '99000000');
				$acl = array( '04000000','07000000', '99000000');
				break;
				//MIC
			case '03':
				$acl = array( '02000000', '03000000', '04000000', '07000000','99000000');
                //$acl = array( '02000000', '03000000','05000000', '07000000', '99000000');
				break;
			
			//GC
			case '02':
				//$acl = array( '01000000', '02000000','03000000','04000000','05000000','07000000', '99000000');
                $acl = array( '01000000', '02000000','03000000','04000000','07000000', '99000000');
				break;

			//PARTNER
			case '01':
				$acl = array( '01000000', '02000000','03000000','04000000','05000000','07000000', '08000000', '99000000');
				break;
				
			//SUPER_USER
			case '008':
				//$acl = array( '01000000','02000000','03000000','04000000','05000000','07000000', '08000000', '99000000');
                $acl = array('01000000','02000000','04000000','05000000','05010000','07000000', '08000000','99000000');
				break;
			//SYS ADMIN	
			case '009':
				$acl = array( '02000000','03000000','05000000','08000000', '99000000');
				break;
		    //CASIER
			case '0010':
				$acl = array( '06000000','99000000');
				break;
            //Risk Supervisor
			case '011':
				$acl = array('02000000','04000000','07000000', '99000000');
				//$acl = array( '01000000', '02000000','03000000','04000000','05000000','05010000','05020000','07000000', '08000000', '99000000');
                break;    
		    //CASIER
			case '05':
				$acl = array( '07000000','99000000');
				break;
            default: 
                $acl = array( '07000000','99000000');
				break;   
				
		}		
		
		if ( $this->data['is_auth']) {;
			$rows	= $this->homeModel->getMenu();
			
			if($rows) {
				$this->data['menu'] = '<ul id="nav">';
				
				foreach ($rows as $k=>$v) {
					if (in_array( $v['menuid'],$acl)) {
						$this->data['li_url']	= $v['menu'];
						$this->data['li_label']	= $v['label'];
						$this->data['menu'] .= $this->load->view('li',$this->data, true );
						
						
						
						$rowsChild	= $this->homeModel->getMenuChild($v['menu']);
						if($rowsChild) {
							$this->data['menu'] .= '<ul>';
							
							foreach ($rowsChild as $k0=>$v0) {
								$this->data['li_url']	=  $v['menu'] ."/".$v0['menu'];
								$this->data['li_label']	= $v0['label'];
								
								if ($v0['menuid']=="99020000"){
								   if ($this->session->userdata('acl') == "008" || $this->session->userdata('acl') == "009") {
										$this->data['menu'] .= $this->load->view('li',$this->data, true )  .'</li>';
									}
  							 
								} 
								elseif ($v0['menuid']=="04010000"){
									if ( $this->session->userdata('acl') == "1" || $this->session->userdata('acl') == "2" || $this->session->userdata('acl') == "3" || $this->session->userdata('acl') == "4" ) {
											$this->data['menu'] .= $this->load->view('li',$this->data, true )  .'</li>';
									}
								}
								
  							
  							else {
  							  $this->data['menu'] .= $this->load->view('li',$this->data, true )  .'</li>';
  							}
								
							}
							$this->data['menu'] .= '</ul>';
							
						}
						$this->data['menu'] .= '</li>';
					}
					
				}
				
				
				if (($this->session->userdata('acl') == "03") && ($this->session->userdata('department_id') == "7")){
					$this->data['li_url']	= "data/job";
					$this->data['li_label']	= "Data Reference";
					$this->data['menu'] .= $this->load->view('li',$this->data,true);
					
					$this->data['menu'] .= '<ul>';
					
					$this->data['li_url']	= "data/job";
					$this->data['li_label']	= "Job Details";
					$this->data['menu'] .= $this->load->view('li',$this->data,true);
					
					$this->data['li_url']	= "data/jobtype";
					$this->data['li_label']	= "Job Type";
					$this->data['menu'] .= $this->load->view('li',$this->data,true);
					
					$this->data['li_url']	= "data/outsource";
					$this->data['li_label']	= "Outsource";
					$this->data['menu'] .= $this->load->view('li',$this->data,true);
					
					$this->data['menu'] .= '</ul>';
				}
				
				
				$this->data['menu'] .= '
						<li class="secondary" >
						<a href="'. $this->data['site'] .'home/logout/" title="logout">
						<img src="'. $this->data['base_url'] .'images/logout.gif" align="middle" border="0" style="vertical-align: middle; text-align: center; padding-right:3px; padding-bottom:3px;" />
						Logout
						</a>
						</li>
						<li class="secondary">
						<b>'.$this->session->userdata('employee') .' ( '. $this->session->userdata('aclname') .' )</b>
						
						</li>
						</ul>
                        <!--
                        <ul id="nav">
                            <marquee>Notification :</marquee>
                        </ul>
                        -->
                        ';
                            
			}
		}
		else {
			$unlocked = array('login', 'logout','help');
			if ($this->uri->segment(1)=='home' && !in_array(strtolower($this->uri->segment(2)), $unlocked) ) {
				redirect();
			}
		}
	} //END GETMENU
	

}