<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 4 April 2017
 * File Name	= Item_category.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_item_category extends CI_Controller  
{
 	function index()
	{
		$this->load->model('m_inventory/m_itemcategory/m_itemcategory', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_item_category/get_itemcategory/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function get_itemcategory()
	{
		$this->load->model('m_inventory/m_itemcategory/m_itemcategory', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 				= $this->session->userdata('Emp_ID');
					
			$data['title'] 		= $appName;
			$data['main_view'] 	= 'v_inventory/v_itemcategory/item_category';
			
			$num_rows 			= $this->m_itemcategory->count_all_num_rows();
			$data['countIC'] 	= $num_rows;
	 		$data["MenuCode"] 	= 'MN222';
			$data['viewitemcat'] = $this->m_itemcategory->get_itemcategory()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN222';
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
			
			$this->load->view('v_inventory/v_itemcategory/item_category', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add()
	{
		$this->load->model('m_inventory/m_itemcategory/m_itemcategory', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_inventory/c_item_category/add_process');
			$data['backURL'] 		= site_url('c_inventory/c_item_category/');			
			$data["MenuCode"] 		= 'MN222';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN222';
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
			
			$this->load->view('v_inventory/v_itemcategory/item_category_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process()
	{ 
		$this->load->model('m_inventory/m_itemcategory/m_itemcategory', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$itemcat = array('IC_Num' 	=> $this->input->post('IC_Num'),
							'IG_Code'	=> $this->input->post('IG_Code'),
							'IC_Code'	=> $this->input->post('IC_Code'),
							'IC_Name'	=> $this->input->post('IC_Name'));
		
			$this->m_itemcategory->add($itemcat);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('IC_Num');
				$MenuCode 		= 'MN222';
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
			
			redirect('c_inventory/c_item_category/');
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update()
	{
		$this->load->model('m_inventory/m_itemcategory/m_itemcategory', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$IC_Code	= $_GET['id'];
			$IC_Code	= $this->url_encryption_helper->decode_url($IC_Code);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_inventory/c_item_category/update_process');
			$data['backURL'] 		= site_url('c_inventory/c_item_category/');
			$data["MenuCode"] 		= 'MN222';
			
			$getitemcat = $this->m_itemcategory->get_itemcat_by_code($IC_Code)->row();
			
			$data['default']['IC_Num'] 	= $getitemcat->IC_Num;
			$data['default']['IG_Code'] = $getitemcat->IG_Code;
			$data['default']['IC_Code'] = $getitemcat->IC_Code;
			$data['default']['IC_Name'] = $getitemcat->IC_Name;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getitemcat->IC_Num;
				$MenuCode 		= 'MN222';
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
			
			$this->load->view('v_inventory/v_itemcategory/item_category_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{ 
		$this->load->model('m_inventory/m_itemcategory/m_itemcategory', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$itemcat = array('IC_Num' 	=> $this->input->post('IC_Num'),
							'IG_Code'	=> $this->input->post('IG_Code'),
							'IC_Code'	=> $this->input->post('IC_Code'),
							'IC_Name'	=> $this->input->post('IC_Name'));
		
			$this->m_itemcategory->update($this->input->post('IC_Num'), $itemcat);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('IC_Num');
				$MenuCode 		= 'MN222';
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
			
			redirect('c_inventory/c_item_category/');
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTheCode($IC_Num) // OK
	{ 	
		$sqlApp 	= "tbl_itemcategory WHERE IC_Num = '$IC_Num'";
		$countCode	= $this->db->count_all($sqlApp);
		echo $countCode;
	}
}