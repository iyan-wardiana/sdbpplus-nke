<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 11 November 2017
 * File Name	= C_invoice_selection.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_invoice_selection extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_invoice_selection/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN141';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_invoice_selection/get_all_invoice/?id=";
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN141';
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
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_all_invoice() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
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
			
			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN141';
			$data["PRJCODE"] 	= $PRJCODE;
			$data['procURL'] 	= site_url('c_finance/c_invoice_selection/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_finance/c_invoice_selection/');
			$data['form_action']= site_url('c_finance/c_invoice_selection/add_process');
			$data["countPRJ"]	= $this->m_projectlist->count_all_project($EmpID);
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($EmpID)->result();
			
			$data["countINV"] 	= $this->m_invoice_selection->count_all_INV($PRJCODE);
			$data['vwINV'] 		= $this->m_invoice_selection->get_last_INV($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN141';
				$TTR_CATEG		= 'INV-ALL';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_finance/v_invoice_selection/v_invoice_selection', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$selectedD 		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$colSelINV 		= $this->input->post('colSelINV');
			
			$cArray			= count(explode("~", $colSelINV));
			$INV_IDX		= explode("~", $colSelINV);
			$INV_ID0		= $INV_IDX[0];
			
			$collData		= '';
			if($cArray == 1)
			{
				$updINV 	= "UPDATE tbl_pinv_header SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD' WHERE INV_ID = $INV_ID0";
				$this->db->query($updINV);	
				$collData	= $INV_ID0;				
			}
			else
			{
				foreach ($INV_IDX as $valINVID) :
					$INV_ID		= $valINVID;
					$collData	= "$collData~$INV_ID";
					$updINV 	= "UPDATE tbl_pinv_header SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD' WHERE INV_ID = $INV_ID";
					$this->db->query($updINV);
				endforeach;
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $collData;
				$MenuCode 		= 'MN141';
				$TTR_CATEG		= 'PROC';
				
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
			
			$url	= site_url('c_finance/c_invoice_selection/get_all_invoice/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
}