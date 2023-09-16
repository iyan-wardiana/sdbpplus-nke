<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Desember 2017
 * File Name	= C_cashbank_list.php
 * Location		= -
*/

class C_cashbank_list extends CI_Controller
{
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_cashbank_list/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1($offset=0)
	{
		$this->load->model('m_finance/m_cashbank_list/m_cashbank_list', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['secAddURL'] 			= site_url('c_finance/c_cashbank_list/add/?id='.$this->url_encryption_helper->encode_url($appName));			
			$num_rows 					= $this->m_cashbank_list->count_all_cashbank();
			$data["recordcount"] 		= $num_rows;
			$data['viewcashbank']		= $this->m_cashbank_list->get_all_cashbank()->result();
			$MenuCode 					= 'MN150';
			$data['MenuCode'] 			= 'MN150';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'Cash Bank';
				$MenuCode 		= 'MN150';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
						
			$this->load->view('v_finance/v_cashbank/v_cashbank_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_finance/m_cashbank_list/m_cashbank_list', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_finance/c_cashbank_list/add_process');
			$data['backURL'] 		= site_url('c_finance/c_cashbank_list/');		
			$proj_Currency			= 'IDR';		
			$data['countAcc'] 		= $this->m_cashbank_list->count_all_Acc($proj_Currency);
			$data['vwAcc'] 			= $this->m_cashbank_list->view_all_Acc($proj_Currency)->result();
					
			$MenuCode 				= 'MN150';
			$data['MenuCode'] 		= 'MN150';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'Cash Bank';
				$MenuCode 		= 'MN150';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_finance/v_cashbank/v_cashbank_form', $data);
		}
		else
		{
			redirect('Auth'); 
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_finance/m_cashbank_list/m_cashbank_list', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			$InCashBank = array('B_CODE' 	=> $this->input->post('B_CODE'),
								'B_NAME'	=> $this->input->post('B_NAME'),
								'B_DESC'	=> $this->input->post('B_DESC'),
								'B_REKNO'	=> $this->input->post('B_REKNO'),
								'B_LOC'		=> $this->input->post('B_LOC'),
								'ACC_ID'	=> $this->input->post('ACC_ID'),
								'B_STAT'	=> $this->input->post('B_STAT'));
			$this->m_cashbank_list->add($InCashBank);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('B_CODE');
				$MenuCode 		= 'MN150';
				$TTR_CATEG		= 'C';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
		
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_finance/c_cashbank_list/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_finance/m_cashbank_list/m_cashbank_list', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$B_CODE		= $_GET['id'];
		$B_CODE		= $this->url_encryption_helper->decode_url($B_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_finance/c_cashbank_list/update_process');
			$data['backURL'] 		= site_url('c_finance/c_cashbank_list/');			
			$MenuCode 				= 'MN150';
			$data['MenuCode'] 		= 'MN150';
			
			$data['backURL'] 		= site_url('c_finance/c_cashbank_list/');
			
			$getCASHABNK 			= $this->m_cashbank_list->get_CASHBANK_by_code($B_CODE)->row();			
			$data['default']['B_CODE'] 	= $getCASHABNK->B_CODE;
			$data['default']['B_NAME']	= $getCASHABNK->B_NAME;
			$data['default']['B_DESC'] 	= $getCASHABNK->B_DESC;		
			$data['default']['B_REKNO']	= $getCASHABNK->B_REKNO;
			$data['default']['B_BRAND'] = $getCASHABNK->B_BRAND;
			$data['default']['B_LOC'] 	= $getCASHABNK->B_LOC;
			$data['default']['ACC_ID'] 	= $getCASHABNK->ACC_ID;
			$data['default']['B_STAT'] 	= $getCASHABNK->B_STAT;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getCASHABNK->B_CODE;
				$MenuCode 		= 'MN150';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_finance/v_cashbank/v_cashbank_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{	
		$this->load->model('m_finance/m_cashbank_list/m_cashbank_list', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			$B_CODE		= $this->input->post('B_CODE');
			$UpCashBank = array('B_NAME'	=> $this->input->post('B_NAME'),
								'B_DESC'	=> $this->input->post('B_DESC'),
								'B_REKNO'	=> $this->input->post('B_REKNO'),
								'B_LOC'		=> $this->input->post('B_LOC'),
								'ACC_ID'	=> $this->input->post('ACC_ID'),
								'B_STAT'	=> $this->input->post('B_STAT'));
			$this->m_cashbank_list->update($B_CODE, $UpCashBank);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('B_NAME');
				$MenuCode 		= 'MN150';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
		
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_finance/c_cashbank_list/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	/*function delete($B_CODE)
	{
		$owner = array('own_Status'		=> $this->input->post('own_Status'));
		$this->m_cashbank_list->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data succesfull deleted.');
		
		redirect('c_finance/c_cashbank_list/');
	}*/
	
	/*function get_last_ten_owner_src($offset=0) // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$MyAppName    			= $this->session->userdata['SessAppTitle']['app_title_name'];
			//$DefProj_Code			= $this->session->userdata['dtSessSrc2']['selSearchproj_Code'];
			$DefProj_Code			= $this->session->userdata['userSessProject']['userprojSession'];
								
			$data['title'] 			= $MyAppName;
			$data['h2_title'] 		= 'Project Owner';
			$data['main_view'] 		= 'v_project/v_project_owner/project_owner_sd';	
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset'] 		= 0;
			
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_finance/c_cashbank_list_sd'),'get_last_ten_owner_src');
			
			$data['selSearchType'] 	= $this->input->post('selSearchType');
			$data['txtSearch'] 		= $this->input->post('txtSearch');
			$data['selOwnStatus']	= $this->input->post('selOwnStatus');
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchType	= $this->input->post('selSearchType');
				$txtSearch 		= $this->input->post('txtSearch');
				$selOwnStatus 	= $this->input->post('selOwnStatus');
				$VendStat	 	= $this->input->post('selOwnStatus');
				
				$dataSessSrc = array(
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'),
					'selOwnStatus' => $this->input->post('selOwnStatus'));
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
				
				$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			}
			else
			{
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];

				$selOwnStatus      = $this->session->userdata['dtSessSrc1']['selOwnStatus'];
				
				$dataSessSrc = array(
					'selSearchType' => $this->session->userdata['dtSessSrc1']['selSearchType'],
					'txtSearch' => $this->session->userdata['dtSessSrc1']['txtSearch'],
					'selOwnStatus' => $this->session->userdata['dtSessSrc1']['selOwnStatus']);
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			}
			
			if($selSearchType == 'OwnCode')
			{
				$num_rows = $this->m_cashbank_list->count_all_num_rows_VCode($txtSearch, $VendStat);
			}
			else
			{
				$num_rows = $this->m_cashbank_list->count_all_num_rows_VName($txtSearch, $VendStat);
			}			
			
			$data["recordcount"] = $num_rows;
			
			// Start of Pagination
			$config 					= array();
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_finance/c_cashbank_list/get_last_ten_owner');				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open']	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
			// End of Pagination
	 
			$this->pagination->initialize($config);
	 
			//$data['viewvendor'] = $this->m_cashbank_list->get_last_ten_owner($config["per_page"], $offset)->result();
			
			if($selSearchType == 'OwnCode')
			{
				$data['viewOwner'] = $this->m_cashbank_list->get_last_ten_owner_VCode($config["per_page"], $offset, $txtSearch, $VendStat)->result();
			}
			else
			{
				$data['viewOwner'] = $this->m_cashbank_list->get_last_ten_owner_VName($config["per_page"], $offset, $txtSearch, $VendStat)->result();
			}
			
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth'); // by. DH on 16 Maret 14 : Failed, so ... load back to login
		}
	}*/
}