<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 November 2017
 * File Name	= C_coa.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_coa extends CI_Controller
{
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_gl/c_coa/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
    function index1() // OK
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$getAppName = $this->m_coa->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{	 
			$data['viewCOA'] = $this->m_coa->get_all_ofCOADef()->result();
			$MenuCode 			= 'MN105';
			$data["MenuCode"] 	= 'MN105';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
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
			
			$this->load->view('v_gl/v_coa/coa', $data);
		}
		else
		{
			redirect('login');
		}
    }

	function get_all_ofCOA($LinkAcc) // OK
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$getAppName = $this->m_coa->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{	 
			$data['viewCOA'] = $this->m_coa->get_all_ofCOA($LinkAcc)->result();
			
			$this->load->view('v_gl/v_coa/coa', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$Acc_ID	= $_GET['id'];
			$Acc_ID	= $this->url_encryption_helper->decode_url($Acc_ID);
		
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_gl/c_coa/update_process');
			$data['backURL'] 		= site_url('c_gl/c_coa/?id='.$this->url_encryption_helper->encode_url($appName));
			$MenuCode 				= 'MN105';
			$data["MenuCode"] 		= 'MN105';
			
			$getdepartment = $this->m_coa->get_coa_by_code($Acc_ID)->row();
			
			$data['default']['Acc_ID'] 				= $getdepartment->Acc_ID;
			$data['default']['PRJCODE'] 			= $getdepartment->PRJCODE;
			$data['default']['Account_Class'] 		= $getdepartment->Account_Class;
			$data['default']['Account_Number'] 		= $getdepartment->Account_Number;
			$data['default']['Account_NameEn'] 		= $getdepartment->Account_NameEn;
			$data['default']['Account_NameId'] 		= $getdepartment->Account_NameId;
			$data['default']['Account_Category'] 	= $getdepartment->Account_Category;
			$data['default']['Account_Level'] 		= $getdepartment->Account_Level;
			$data['default']['Acc_DirParent'] 		= $getdepartment->Acc_DirParent;
			$data['default']['Acc_ParentList'] 		= $getdepartment->Acc_ParentList;
			$data['default']['Acc_StatusLinked'] 	= $getdepartment->Acc_StatusLinked;
			$data['default']['Default_Acc'] 		= $getdepartment->Default_Acc;
			$data['default']['Currency_id'] 		= $getdepartment->Currency_id;
			$data['default']['Base_Debet'] 			= $getdepartment->Base_Debet;
			$data['default']['Base_Kredit'] 		= $getdepartment->Base_Kredit;
			$data['default']['Base_Debet2'] 		= $getdepartment->Base_Debet2;
			$data['default']['Base_Kredit2'] 		= $getdepartment->Base_Kredit2;
			$data['default']['Base_OpeningBalance'] = $getdepartment->Base_OpeningBalance;
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
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
			
			$this->load->view('v_gl/v_coa/coa_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{	
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
				
		$this->db->trans_begin();
		
		$Acc_ID			= $this->input->post('Acc_ID');
		$collData		= $this->input->post('Acc_DirParent');
		$splitCode 		= explode("~", $collData);
		$Acc_DirParent	= $splitCode[0];
		$Acc_ParentList1= $splitCode[1];
		$Acc_ParentList	= "$Acc_ParentList1;$Acc_DirParent";
				
		$coaUpd 	= array('Account_Class'		=> $this->input->post('Account_Class'),
							'Account_Number'	=> $this->input->post('Account_Number'),
							'Account_NameEn'	=> $this->input->post('Account_NameEn'),
							'Account_NameId'	=> $this->input->post('Account_NameId'),
							'Account_Category'	=> $this->input->post('Account_Category'),
							'Account_Level'		=> $this->input->post('Account_Level'),
							'Acc_DirParent'		=> $Acc_DirParent,
							'Acc_ParentList'	=> $Acc_ParentList,
							'Default_Acc'		=> $this->input->post('Default_Acc'),
							'Base_OpeningBalance'	=> $this->input->post('Base_OpeningBalance'));
						
		$this->m_coa->update($Acc_ID, $coaUpd);
				
		$data['viewCOA'] = $this->m_coa->get_all_ofCOADef()->result();
		$MenuCode 			= 'MN105';
		$data["MenuCode"] 	= 'MN105';
		
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'MN105';
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
		
		$this->load->view('v_gl/v_coa/coa', $data);
	}

	function get_all_ofCOADef()
	{
		$getAppName = $this->m_coa->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$data['title'] = $appName;
		$data['h2_title'] = 'Chart of Account';
		$data['main_view'] = 'v_gl/v_coa/coa';
		
		$getGlobalSetting = $this->m_coa->globalSetting()->result();
		foreach($getGlobalSetting as $therow) :
			$Display_Rows = $therow->Display_Rows;		
		endforeach;
 
        $data['viewCOA'] = $this->m_coa->get_all_ofCOADef()->result();
        
		
		$this->load->view('template', $data);
	}
}