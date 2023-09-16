<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= Item_list.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Item_list  extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/item_list/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0) // OK
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'inventory';
			
			$num_rows 					= $this->m_joblist->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_joblist->get_last_ten_project()->result();
			
			$this->load->view('v_inventory/v_itemlist/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_item() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 				= $this->session->userdata('Emp_ID');
				
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Item List';
			$data['h3_title']		= 'inventory';
			$data['main_view'] 		= 'v_inventory/v_itemlist/item_list';
			
			$num_rows 				= $this->m_itemlist->count_all_num_rows($PRJCODE);
			$data["recordcount"] 	= $num_rows;
			$data['PRJCODE'] 		= $PRJCODE;
			$data["MenuCode"] 		= 'MN188';
			$data['viewitemlist'] 	= $this->m_itemlist->get_last_ten_item($PRJCODE)->result();
			
			$this->load->view('v_inventory/v_itemlist/item_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			$LangID = $this->session->userdata['LangID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Item';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/item_list/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_inventory/item_list/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_inventory/item_list/');
			$data["MenuCode"] 		= 'MN188';
					
			$data['recordcountUnit'] 	= $this->m_itemlist->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_itemlist->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_itemlist->count_all_num_rowsCateg();
			$data['viewCateg'] 			= $this->m_itemlist->viewCateg()->result();
			$data['PRJCODE'] 			= $PRJCODE;
			
			$this->load->view('v_inventory/v_itemlist/item_list_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$ITM_CATEG 	= $this->input->post('ITM_CATEG');
			$PRJCODE 	= $this->input->post('PRJCODE');
			$ITM_CODE 	= $this->input->post('ITM_CODE');
			
			$ISRENT		= 0;
			$ISPART		= 0;
			$ISFUEL		= 0;
			$ISLUBRIC	= 0;
			$ISFASTM	= 0;
			$ISWAGE		= 0;
			$ISMTRL		= 0;
			$ITM_KIND	= $this->input->post('ITM_KIND');
			if($ITM_KIND == 1)
				$ISRENT	= 1;
			if($ITM_KIND == 2)
				$ISPART	= 1;
			if($ITM_KIND == 3)
				$ISFUEL	= 1;
			if($ITM_KIND == 4)
				$ISLUBRIC	= 1;
			if($ITM_KIND == 5)
				$ISFASTM	= 1;
			if($ITM_KIND == 6)
				$ISWAGE	= 1;
			if($ITM_KIND == 7)
				$ISMTRL	= 1;
			
			$itemPar 	= array('PRJCODE' 		=> $PRJCODE,
							'ITM_CODE'			=> $ITM_CODE,
							'ITM_CATEG'			=> $this->input->post('ITM_CATEG'),
							'ITM_NAME'			=> $this->input->post('ITM_NAME'),
							'ITM_DESC'			=> $this->input->post('ITM_DESC'),
							'ITM_TYPE'			=> $this->input->post('ITM_TYPE'),
							'ITM_UNIT'			=> $this->input->post('ITM_UNIT'),
							'UMCODE'			=> $this->input->post('ITM_UNIT'),
							'ITM_CURRENCY'		=> $this->input->post('ITM_CURRENCY'),
							'ITM_PRICE'			=> $this->input->post('ITM_PRICE'),
							'ITM_VOLM'			=> $this->input->post('ITM_VOLM'),
							'STATUS'			=> $this->input->post('STATUS'),
							'ISRENT'			=> $ISRENT,
							'ISPART'			=> $ISPART,
							'ISFUEL'			=> $ISFUEL,
							'ISLUBRIC'			=> $ISLUBRIC,
							'ISFASTM'			=> $ISFASTM,
							'ISWAGE'			=> $ISWAGE,
							'ISMTRL'			=> $ISMTRL,
							'ITM_KIND'			=> $ITM_KIND,
							'LASTNO'			=> $this->input->post('LASTNO'));
							
			$this->m_itemlist->add($itemPar);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/item_list/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$ITM_CODE	= $_GET['id'];
			$ITM_CODE	= $this->url_encryption_helper->decode_url($ITM_CODE);
			
			$ITM_CODEA	= explode("~", $ITM_CODE);
			$PRJCODE	= $ITM_CODEA[0];
			$ITM_CODE	= $ITM_CODEA[1];
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Item';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/item_list/update_process');
			//$data['link'] 			= array('link_back' => anchor('c_inventory/item_list/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_inventory/item_list/');
			$data["MenuCode"] 		= 'MN188';
					
			$data['recordcountUnit'] 	= $this->m_itemlist->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_itemlist->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_itemlist->count_all_num_rowsCateg();
			$data['viewCateg'] 			= $this->m_itemlist->viewCateg()->result();
			
			$geITEM = $this->m_itemlist->get_Item_by_Code($ITM_CODE, $PRJCODE)->row();			
			
			$data['default']['ITM_CODE'] 		= $geITEM->ITM_CODE;
			$data['default']['ITM_NAME'] 		= $geITEM->ITM_NAME;
			$data['default']['ITM_CATEG'] 		= $geITEM->ITM_CATEG;
			$data['default']['ITM_DESC'] 		= $geITEM->ITM_DESC;
			$data['default']['ITM_TYPE'] 		= $geITEM->ITM_TYPE;
			$data['default']['ITM_UNIT'] 		= $geITEM->ITM_UNIT;
			$data['default']['ITM_CURRENCY'] 	= $geITEM->ITM_CURRENCY;
			$data['default']['ITM_VOLM'] 		= $geITEM->ITM_VOLM;
			$data['default']['ITM_IN'] 			= $geITEM->ITM_IN;
			$data['default']['ITM_OUT'] 		= $geITEM->ITM_OUT;
			$data['default']['ITM_PRICE']		= $geITEM->ITM_PRICE;
			$data['default']['UMCODE'] 			= $geITEM->UMCODE;
			$data['default']['Unit_Type_Name'] 	= $geITEM->Unit_Type_Name;
			$data['default']['UMCODE'] 			= $geITEM->UMCODE;
			$data['default']['STATUS'] 			= $geITEM->STATUS;
			$data['default']['LASTNO'] 			= $geITEM->LASTNO;
			$data['default']['ISMTRL'] 			= $geITEM->ISMTRL;
			$data['default']['ISRENT'] 			= $geITEM->ISRENT;
			$data['default']['ISPART'] 			= $geITEM->ISPART;
			$data['default']['ISFUEL'] 			= $geITEM->ISFUEL;
			$data['default']['ISLUBRIC'] 		= $geITEM->ISLUBRIC;
			$data['default']['ISFASTM'] 		= $geITEM->ISFASTM;
			$data['default']['ISWAGE'] 			= $geITEM->ISWAGE;
			$data['default']['ITM_KIND'] 		= $geITEM->ITM_KIND;
			$data['default']['PRJCODE'] 		= $geITEM->PRJCODE;
			$data['PRJCODE'] 					= $geITEM->PRJCODE;
			
			$this->load->view('v_inventory/v_itemlist/item_list_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$ITM_CATEG 	= $this->input->post('ITM_CATEG');
			$PRJCODE 	= $this->input->post('PRJCODE');
			$ITM_CODE 	= $this->input->post('ITM_CODE');
			
			$ISRENT		= 0;
			$ISPART		= 0;
			$ISFUEL		= 0;
			$ISLUBRIC	= 0;
			$ISFASTM	= 0;
			$ISWAGE		= 0;
			$ISMTRL		= 0;
			$ITM_KIND	= $this->input->post('ITM_KIND');
			
			if($ITM_KIND == 1)
				$ISRENT		= 1;
			if($ITM_KIND == 2)
				$ISPART		= 1;
			if($ITM_KIND == 3)
				$ISFUEL		= 1;
			if($ITM_KIND == 4)
				$ISLUBRIC	= 1;
			if($ITM_KIND == 5)
				$ISFASTM	= 1;
			if($ITM_KIND == 6)
				$ISWAGE		= 1;
			if($ITM_KIND == 7)
				$ISMTRL		= 1;
			//return false;
			
			$itemPar 	= array('PRJCODE' 		=> $PRJCODE,
							'ITM_CODE'			=> $ITM_CODE,
							'ITM_CATEG'			=> $this->input->post('ITM_CATEG'),
							'ITM_NAME'			=> $this->input->post('ITM_NAME'),
							'ITM_DESC'			=> $this->input->post('ITM_DESC'),
							'ITM_TYPE'			=> $this->input->post('ITM_TYPE'),
							'ITM_UNIT'			=> $this->input->post('ITM_UNIT'),
							'UMCODE'			=> $this->input->post('ITM_UNIT'),
							'ITM_CURRENCY'		=> $this->input->post('ITM_CURRENCY'),
							'ITM_PRICE'			=> $this->input->post('ITM_PRICE'),
							'ITM_VOLM'			=> $this->input->post('ITM_VOLM'),
							'STATUS'			=> $this->input->post('STATUS'),
							'ISRENT'			=> $ISRENT,
							'ISPART'			=> $ISPART,
							'ISFUEL'			=> $ISFUEL,
							'ISLUBRIC'			=> $ISLUBRIC,
							'ISFASTM'			=> $ISFASTM,
							'ISWAGE'			=> $ISWAGE,
							'ISMTRL'			=> $ISMTRL,
							'ITM_KIND'			=> $ITM_KIND,
							'LASTNO'			=> $this->input->post('LASTNO'));
							
			$this->m_itemlist->update($ITM_CODE, $itemPar);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/item_list/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function changeStatusItem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CodeItem 			= $this->input->post('chkDetail');
			$gesd_tcost 		= $this->m_itemlist->get_Item_by_Code($CodeItem)->row();
			
			$ItemStatus			= $gesd_tcost->Status;
			if($ItemStatus == 'Active')
			{
				$NItemStatus = 'InActive';
			}
			else
			{
				$NItemStatus = 'Active';
			}
							
			$this->m_itemlist->updateStatus($CodeItem, $NItemStatus);
			
			redirect('c_inventory/item_list/');
		}
		else
		{
			redirect('login');
		}
	}
	
	function delete($Item_Code)
	{
		$this->m_itemlist->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_inventory/item_list/');
	}
	
	function popupallitem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $MyAppName;;
			$data['h2_title'] 		= 'Select Item';
			$data['main_view'] 		= 'v_inventory/v_itemlist/Itemlist_sd_form';
			$data['form_action']	= site_url('c_inventory/item_list/update_process');
			
			$data['recordcountAllItem'] = $this->m_itemlist->count_all_num_rowsAllItem();
			$data['viewAllItem'] 	= $this->m_itemlist->viewAllItem()->result();
					
			$this->load->view('v_inventory/v_itemlist/purchase_reqselecsd_tcost_sd', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
    function inbox($offset=0)
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $MyAppName;
			$data['h2_title']	= 'Item List Inbox';
			$data['main_view'] 	= 'v_inventory/v_itemlist/Itemlist_inbox_sd';

			$num_rows = $this->m_itemlist->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_inventory/item_list/get_last_ten_item');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 2;
			$config["uri_segment"] = 3;
				
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 		
			$this->pagination->initialize($config);
	 		
			$data['viewpurreq'] = $this->m_itemlist->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
    }
}