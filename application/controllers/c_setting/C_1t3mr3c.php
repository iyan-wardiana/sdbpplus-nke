<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Juni 2023
 * File Name	= C_1t3mr3c.php
 * Location		= -
*/

class C_1t3mr3c extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
	}

 	function index() // G
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_1t3mr3c/add/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function add() // G
	{
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN096';
				$data["MenuApp"] 	= 'MN096';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_setting/c_1t3mr3c/add_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_1t3mr3c/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_1t3mr3c/');
			$data["MenuCode"] 		= 'MN097';
			
			$this->load->view('v_setting/v_itemrec/v_itemrec_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTheCode($Unit_Type_Code) // G
	{ 	
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		$countMLCode 	= $this->m_unittype->count_ml_code($Unit_Type_Code);
		echo $countMLCode;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
	
			$InsML	= array('Unit_Type_Code'	=> $this->input->post('Unit_Type_Code'),
							'UMCODE'			=> $this->input->post('Unit_Type_Code'),
							'Unit_Type_Name'	=> htmlspecialchars($this->input->post('Unit_Type_Name'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
							'Unit_Type_Desc'	=> htmlspecialchars($this->input->post('Unit_Type_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5));
	
			$this->m_unittype->add($InsML);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_setting/c_1t3mr3c/iN4x/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update()
	{
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$Unit_Type_ID		= $_GET['id'];
		$Unit_Type_ID		= $this->url_encryption_helper->decode_url($Unit_Type_ID);
		$data["MenuCode"] 	= 'MN097';
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Master Unit";
				$data['h2_title'] 	= 'pengaturan';
			}
			else
			{
				$data["h1_title"] 	= "Master Unit";
				$data['h2_title'] 	= 'setting';
			}
			
			$data['form_action']	= site_url('c_setting/c_1t3mr3c/update_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_1t3mr3c/','<input type="button" name="btnCancel" id="btnCancel" value="Can cel" class="btn btn-danger" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_1t3mr3c/');
			$getMLang 				= $this->m_unittype->get_unit($Unit_Type_ID)->row();
			
			$data['default']['Unit_Type_ID']	= $getMLang->Unit_Type_ID;
			$data['default']['Unit_Type_Code']	= $getMLang->Unit_Type_Code;
			$data['default']['Unit_Type_Name'] 	= $getMLang->Unit_Type_Name;
			$data['default']['Unit_Type_Desc'] 	= $getMLang->Unit_Type_Desc;
			
			$this->load->view('v_setting/v_itemrec/v_itemrec_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{		
		$this->load->model('m_setting/m_unittype/m_unittype', '', TRUE);
		
		$Unit_Type_Code	= $this->input->post('Unit_Type_Code');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
	
			$UpdML	= array('Unit_Type_Code'	=> $this->input->post('Unit_Type_Code'),
							'UMCODE'			=> $this->input->post('Unit_Type_Code'),
							'Unit_Type_Name'	=> htmlspecialchars($this->input->post('Unit_Type_Name'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
							'Unit_Type_Desc'	=> htmlspecialchars($this->input->post('Unit_Type_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5));
							
			$this->m_unittype->update($Unit_Type_Code, $UpdML);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_setting/c_1t3mr3c/iN4x/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}