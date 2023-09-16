<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Desember 2017
 * File Name	= C_item_group.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_item_group extends CI_Controller  
{
 	function index()
	{
		$this->load->model('m_inventory/m_itemgroup/m_itemgroup', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_item_group/get_itemgroup/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function get_itemgroup()
	{
		$this->load->model('m_inventory/m_itemgroup/m_itemgroup', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 				= $this->session->userdata('Emp_ID');
					
			$data['title'] 		= $appName;
			$data['main_view'] 	= 'v_inventory/v_itemgroup/item_group';
			
			$num_rows 			= $this->m_itemgroup->count_all_num_rows();
			$data['countIC'] 	= $num_rows;
	 		$data["MenuCode"] 	= 'MN335 ';
			$data['viewitemgrp'] = $this->m_itemgroup->get_itemgroup()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN335';
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
			
			$this->load->view('v_inventory/v_itemgroup/item_group', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add()
	{
		$this->load->model('m_inventory/m_itemgroup/m_itemgroup', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_inventory/c_item_group/add_process');
			$data['backURL'] 		= site_url('c_inventory/c_item_group/');			
			$data["MenuCode"] 		= 'MN335 ';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN335';
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
			
			$this->load->view('v_inventory/v_itemgroup/item_group_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{ 
		$this->load->model('m_inventory/m_itemgroup/m_itemgroup', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$itemgrp = array('IG_Num' 	=> $this->input->post('IG_Num'),
							'IG_Code'	=> $this->input->post('IG_Code'),
							'IG_Name'	=> $this->input->post('IG_Name'));
		
			$this->m_itemgroup->add($itemgrp);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('IG_Num');
				$MenuCode 		= 'MN335';
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
			
			redirect('c_inventory/c_item_group/');
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update()
	{
		$this->load->model('m_inventory/m_itemgroup/m_itemgroup', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$IG_Num	= $_GET['id'];
			$IG_Num	= $this->url_encryption_helper->decode_url($IG_Num);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_inventory/c_item_group/update_process');
			$data['backURL'] 		= site_url('c_inventory/c_item_group/');
			$data["MenuCode"] 		= 'MN335 ';
			
			$getitemgroup = $this->m_itemgroup->get_itemgrp_by_code($IG_Num)->row();
			
			$data['default']['IG_Num'] 	= $getitemgroup->IG_Num;
			$data['default']['IG_Code'] = $getitemgroup->IG_Code;
			$data['default']['IG_Name'] = $getitemgroup->IG_Name;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $IG_Num;
				$MenuCode 		= 'MN335';
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
			
			$this->load->view('v_inventory/v_itemgroup/item_group_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{ 
		$this->load->model('m_inventory/m_itemgroup/m_itemgroup', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$itemgrp = array('IG_Num' 	=> $this->input->post('IG_Num'),
							'IG_Code'	=> $this->input->post('IG_Code'),
							'IG_Name'	=> $this->input->post('IG_Name'));
		
			$this->m_itemgroup->update($this->input->post('IG_Num'), $itemgrp);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('IG_Num');
				$MenuCode 		= 'MN335';
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
			
			redirect('c_inventory/c_item_group/');
		}
		else
		{
			redirect('Auth');
		}
	}
		
	function getTheCode($IG_Num) // OK
	{ 	
		$sqlApp 	= "tbl_itemgroup WHERE IG_Num = '$IG_Num'";
		$countCode	= $this->db->count_all($sqlApp);
		echo $countCode;
	}
}