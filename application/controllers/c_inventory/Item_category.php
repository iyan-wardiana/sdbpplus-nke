<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 4 April 2017
 * File Name	= Item_category.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Item_category  extends CI_Controller  
{
 	function index()
	{
		$this->load->model('m_inventory/m_itemcategory/m_itemcategory', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/item_category/get_last_ten_itemcat/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function get_last_ten_itemcat()
	{
		$this->load->model('m_inventory/m_itemcategory/m_itemcategory', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 				= $this->session->userdata('Emp_ID');
					
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Item Category';
			$data['h3_title']	= 'inventory';
			$data['main_view'] 	= 'v_inventory/v_itemcategory/item_category';
			
			$num_rows 			= $this->m_itemcategory->count_all_num_rows();
			$data['countIC'] 	= $num_rows;
	 		$data["MenuCode"] 	= 'MN222';
			$data['viewitemcat'] = $this->m_itemcategory->get_last_ten_itemcat()->result();
			
			$this->load->view('v_inventory/v_itemcategory/item_category', $data);
		}
		else
		{
			redirect('Auth');
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
			$data['h2_title'] 		= 'Add Item Category';
			$data['h3_title'] 		= 'inventory';
			$data['form_action']	= site_url('c_inventory/item_category/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_inventory/item_category/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_inventory/item_category/');
			
			$data["MenuCode"] 		= 'MN222';
			
			$this->load->view('v_inventory/v_itemcategory/item_category_form', $data);
		}
		else
		{
			redirect('Auth');
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
			$itemcat = array('itemCategory_code' 	=> $this->input->post('itemCategory_code'),
							'ItemCategory_name'		=> $this->input->post('ItemCategory_name'));
		
			$this->m_itemcategory->add($itemcat);
			
			redirect('c_inventory/item_category/');
		}
		else
		{
			redirect('Auth');
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
			$IC_CODE	= $_GET['id'];
			$IC_CODE	= $this->url_encryption_helper->decode_url($IC_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Item Category';
			$data['h3_title'] 		= 'inventory';
			$data['form_action']	= site_url('c_inventory/item_category/update_process');
			//$data['link'] 			= array('link_back' => anchor('c_inventory/item_category/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_inventory/item_category/');
			$data["MenuCode"] 		= 'MN222';
			
			$getitemcategory = $this->m_itemcategory->get_itemcat_by_code($IC_CODE)->row();
			
			$data['default']['itemCategory_code'] = $getitemcategory->itemCategory_code;
			$data['default']['ItemCategory_name'] = $getitemcategory->ItemCategory_name;
			
			$this->load->view('v_inventory/v_itemcategory/item_category_form', $data);
		}
		else
		{
			redirect('Auth');
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
			$itemcat = array('itemCategory_code' 	=> $this->input->post('itemCategory_code'),
							'ItemCategory_name'		=> $this->input->post('ItemCategory_name'));
		
			$this->m_itemcategory->update($this->input->post('itemCategory_code'), $itemcat);
			
			redirect('c_inventory/item_category/');
		}
		else
		{
			redirect('Auth');
		}
	}
}