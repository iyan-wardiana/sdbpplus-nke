<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Juli 2018
 * File Name	= C_r3p.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_r3p extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();

		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
	}

    function index()
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/Am4/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function Am4() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN351';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN351';
				$data["MenuApp"] 	= 'MN351';
				$data["MenuCode"] 	= 'MN351';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/am1h0db2VwR3p/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_amandemen/v_amandemen', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function am1h0db2VwR3p() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN RINGKASAN AMANDEMEN';
				$data['h2_title']	= 'Report';
			}
			else
			{
				$data['h1_title']	= 'BUDGET SUMMARY REPORT';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 		= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] = $rowPRJ->PRJNAME;
			endforeach;
			
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			
			$data['Start_Date'] = $Start_Date;
			$data['End_Date'] 	= $End_Date;
			$data['CFType'] 	= $this->input->post('CFType');		// List or Summary or Detail
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			$LangID 	= $this->session->userdata['LangID'];
			if($CFType == 1)
			{
				$data['CFStat'] 		= $this->input->post('CFStat');
				if($LangID == 'IND')
				{
					$data['h1_title']	= 'LAPORAN DAFTAR AMANDEMEN';
				}
				else
				{
					$data['h1_title']	= 'AMENDMENT LIST REPORT';
				}
				$this->load->view('v_project/v_report/v_amandemen/v_amandemen_report', $data);
			}
			else if($CFType == 2)
			{
				if($LangID == 'IND')
				{
					$data['h1_title']	= 'LAPORAN RINGKASAN AMANDEMEN';
				}
				else
				{
					$data['h1_title']	= 'AMENDMENT SUMMARY REPORT';
				}
				$this->load->view('v_project/v_report/v_amandemen/v_amandemen_report_sum', $data);
			}
			else
			{
				$data['ITM_CODE'] 		= $this->input->post('ITM_CODE');
				if($LangID == 'IND')
				{
					$data['h1_title']	= 'LAPORAN DETIL ITEM AMANDEMEN';
				}
				else
				{
					$data['h1_title']	= 'AMENDMENT ITEM DETAIL REPORT';
				}
				$this->load->view('v_project/v_report/v_amandemen/v_amandemen_report_det', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function spk() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/s5pK/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function s5pK() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN352';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Laporan SPK';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'SPK Report';
				$data['h2_title']	= 'Report';
			}
			$data['form_action'] 	= site_url('c_project/c_r3p/s5pKvw/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_spk/v_spk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s5pKvw() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Laporan SPK';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'SPK Report';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE'";
			$qProject 		= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] = $rowPRJ->PRJNAME;
			endforeach;
			
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			
			$data['Start_Date'] = $Start_Date;
			$data['End_Date'] 	= $End_Date;
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			$this->load->view('v_project/v_report/v_spk/v_spk_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s70ck0p() // G
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/s70ck0px/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s70ck0px() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN304';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN304';
				$data["MenuApp"] 	= 'MN304';
				$data["MenuCode"] 	= 'MN304';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/s70ck0pvw/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_stock_opname/v_stock_opname', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s70ck0pvw() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN STOK OPNAME';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'STOCK OPNAME REPORT';
				$data['h2_title']	= 'Report';
			}			
			
			$data['PRJCODE']		= $this->input->post('PRJCODE');
			$data['ITM_CODE'] 		= $this->input->post('ITM_CODE');
			
			$datePeriod1			= $this->input->post('datePeriod');
			$datePeriod2			= explode(" - ", $datePeriod1);
			$data['datePeriod']		= $datePeriod1;
			$data['Start_Date']		= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']		= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));

			// $data['ITM_GROUP'] 	= $this->input->post('ITM_GROUP');
			// $datePeriod1		= $this->input->post('datePeriod');
			// $datePeriod2		= explode(" - ", $datePeriod1);
			// $Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			// $End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			// $data['datePeriod'] = $datePeriod1;
			// $data['Start_Date'] = $Start_Date;
			// $data['End_Date'] 	= $End_Date;
			$viewPrice 			= $this->input->post('viewPrice');	// View PRICE => ISDELETE = 1
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			if($viewPrice == 1) $this->load->view('v_project/v_report/v_stock_opname/v_stock_opname_price_report', $data);
			else $this->load->view('v_project/v_report/v_stock_opname/v_stock_opname_report_sum', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pRj1N() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/pRj1Nx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pRj1Nx() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN137';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN137';
				$data["MenuApp"] 	= 'MN137';
				$data["MenuCode"] 	= 'MN137';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/pRj1NVwR3p/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_projinv/v_projinv', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pRj1NVwR3p() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN FAKTUR PROYEK';
				$data['h2_title']	= 'Report';
			}
			else
			{
				$data['h1_title']	= 'PROJECT INVOICE REPORT';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('viewProj');
			$getproject = "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
							WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') 
							AND PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 		= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] = $rowPRJ->PRJNAME;
			endforeach;
			
			//$datePeriod1		= $this->input->post('datePeriod');
			//$datePeriod2		= explode(" - ", $datePeriod1);
			//$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			//$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			
			//$data['Start_Date'] = $Start_Date;
			//$data['End_Date'] 	= $End_Date;
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			$this->load->view('v_project/v_report/v_projinv/v_projinv_report', $data);
			
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function pr073ctr3p()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/pr073ctr3px/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y'); 
		}
    }
	
    function pr073ctr3px()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN130';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN130';
				$data["MenuApp"] 	= 'MN130';
				$data["MenuCode"] 	= 'MN130';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/pr073ctr3pvw/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_project/v_project', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function pr073ctr3pvw() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['SuppStat'] 	= $this->input->post('SuppStat');
			$data['SortBy'] 	= $this->input->post('SortBy');
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$SortBy		= $data['SortBy'];
			
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'DAFTAR PROYEK';
			}
			else
			{
				$data['h1_title'] 	= 'PEOJECT LIST';
			}
			
			$this->load->view('v_project/v_report/v_project/v_project_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function M47R3qR3p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$url			= site_url('c_project/c_r3p/M47R3qR3p_1n4/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function M47R3qR3p_1n4() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN131';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN131';
				$data["MenuApp"] 	= 'MN131';
				$data["MenuCode"] 	= 'MN131';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/M47R3qR3p2VwR3p/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_budgetmtring/v_budgetmtring', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function M47R3qR3p2VwR3p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN MONITORING ANGGARAN';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'BUDGET MONITORING REPORT';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$PRJNAME			= '';
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 			= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$PRJNAME		= $rowPRJ->PRJNAME;
			endforeach;
			$data['PRJNAME'] 	= $rowPRJ->PRJNAME;
			
			$data['ITM_GROUP'] 	= $this->input->post('ITM_GROUP');
			$data['JOBPARENT'] 	= $this->input->post('JOBPARENT');
			$JOBPARENT 			= $this->input->post('JOBPARENT');
			$ITM_GROUP			= $this->input->post('ITM_GROUP');
			if($ITM_GROUP == 'All')
			{
				$ITMGRP_NM		= "All Item";
			}
			else
			{
				$ITMGRP_NM		= '';
				$sqlITMGRP		= "SELECT IG_Name FROM tbl_itemgroup WHERE IG_Code = '$ITM_GROUP' LIMIT 1";
				$resITMGRP		= $this->db->query($sqlITMGRP)->result();
				foreach($resITMGRP as $rowITMGRP) :
					$ITMGRP_NM 	= $rowITMGRP->IG_Name;
				endforeach;
			}
			$data['ITMGRP_NM'] 	= $ITMGRP_NM;
			
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['CFType'] 	= $this->input->post('CFType');		// 1. Summary or 2. Request
			$CFType				= $this->input->post('CFType');		// Summary or Request
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			$viewType 			= $this->input->post('viewType');	// View or Excel
			
			// if($CFType == 1) $this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_sum', $data);
			// else if($CFType == 2) $this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_jobdet', $data);
			// else if($CFType == 3) $this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_jobdetonly', $data);
			// else $this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_req', $data);

			if($CFType == 1)
			{
				if($viewType == 1)
					$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_sum_excel', $data);
				else
					$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_sum', $data);
			} 
			else if($CFType == 2)
			{
				if($viewType == 1)
					$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_jobdet_excel', $data);
				else
					$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_jobdet', $data);
			}
			else if($CFType == 3)
			{
				if($viewType == 1)
					$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_jobdetonly_excel', $data);
				else
					$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_jobdetonly', $data);
				
			}
			else
			{
				if($viewType == 1)
					$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_req_excel', $data);
				else
					$this->load->view('v_project/v_report/v_budgetmtring/v_sdbp_report_req', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function i73MbUdGR3p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN433';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN433';
				$data["MenuApp"] 	= 'MN433';
				$data["MenuCode"] 	= 'MN433';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/i73MbUdGR3p_VwR3p/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_itembudet_sum/v_itembudet_sum', $data);
			// $this->load->view('page_uc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i73MbUdGR3p_VwR3p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN MONITORING ANGGARAN';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'BUDGET MONITORING REPORT';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$PRJNAME			= '';
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 			= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$PRJNAME		= $rowPRJ->PRJNAME;
			endforeach;
			$data['PRJNAME'] 	= $rowPRJ->PRJNAME;
			
			$data['ITM_GROUP'] 	= $this->input->post('ITM_GROUP');
			$ITM_GROUP			= $this->input->post('ITM_GROUP');
			if($ITM_GROUP == 'All')
			{
				$ITMGRP_NM		= "All Item";
			}
			else
			{
				$ITMGRP_NM		= '';
				$sqlITMGRP		= "SELECT IG_Name FROM tbl_itemgroup WHERE IG_Code = '$ITM_GROUP' LIMIT 1";
				$resITMGRP		= $this->db->query($sqlITMGRP)->result();
				foreach($resITMGRP as $rowITMGRP) :
					$ITMGRP_NM 	= $rowITMGRP->IG_Name;
				endforeach;
			}
			
			$data['ITMGRP_NM'] 	= $ITMGRP_NM;
			$data['ITM_CODE'] 	= $this->input->post('ITM_CODE');
			
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['CFType'] 	= $this->input->post('CFType');		// 1. Summary or 2. Request
			$CFType				= $this->input->post('CFType');		// Summary or Request
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			if($CFType == 1) $this->load->view('v_project/v_report/v_itembudet_sum/v_itembudet_sum_report', $data);
			else $this->load->view('v_project/v_report/v_itembudet_sum/v_itembudet_req_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i7M43t41L() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname LIMIT 1";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/i7M43t41Lx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function i7M43t41Lx() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['appName'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Detil Budget Item";
				$data["h2_title"] 	= "Laporan";
			}
			else
			{
				$data["h1_title"] 	= "Item Budget Detail";
				$data["h2_title"] 	= "Report";
			}
			
			$data['form_action'] 	= site_url('c_project/c_r3p/ri7M43t41Lx_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_itembudet/v_itembudet', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function ri7M43t41Lx_0fv13w() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];

			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "LAPORAN BUDGET - DETAIL";
				$data["h2_title"] 	= "Detail Budget Detail";
			}
			else
			{
				$data["h1_title"] 	= "BUDGET REPORT - DETAIL";
				$data["h2_title"] 	= "Detail Budget Detail";
			}
			
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$data['JOBPARENT'] 	= $this->input->post('JOBPARENT');
			$data['ITM_CODE'] 	= $this->input->post('ITM_CODE');
			// $TransType 	        = $this->input->post('TransType');	// Request or Realization
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			$this->load->view('v_project/v_report/v_itembudet/v_itembudet_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getJOBLEV()
	{
		$PRJCODE    = $this->input->post('PRJCODE');
		$this->db->select("IS_LEVEL");
		$this->db->distinct("IS_LEVEL");
		$this->db->from("tbl_joblist_detail");
		$this->db->where(['PRJCODE' => $PRJCODE]);
		$this->db->order_by('IS_LEVEL', 'ASC');
		$data = $this->db->get()->result();
		echo json_encode($data);
	}

	function getJOBPARENT1()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE    = $this->input->post('PRJCODE');
			$this->db->from("tbl_joblist_detail");
			$this->db->where(['PRJCODE' => $PRJCODE, 'ISLAST' => 0]);
			$this->db->order_by('ORD_ID','ASC');
			$data = $this->db->get()->result();
		    echo json_encode($data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getJOBPARENT()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE    = $this->input->post('PRJCODE');
			$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			// $JOBLEV    	= $this->input->post('JOBLEV');
			// $this->db->from("tbl_joblist_detail");
			// $this->db->where(['PRJCODE' => $PRJCODE, 'ISLAST' => 0]);
			// if($JOBLEV[0] != 0) $this->db->where_in("IS_LEVEL", $JOBLEV);
			// $this->db->order_by('ORD_ID','ASC');
			// get JOBPARENT BY PRJCODE
			$getJOBPAR 	= "SELECT * FROM tbl_joblist_detail_$PRJCODEVW WHERE ISLAST = 0 ORDER BY ORD_ID ASC";
			$data 		= $this->db->query($getJOBPAR)->result();
		    echo json_encode($data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getITM_CODE()
	{
	    if ($this->session->userdata('login') == TRUE)
		{
		    $JOBPARENT    	= $this->input->post('JOBPARENT');
		    $PRJCODE    	= $this->input->post('PRJCODE');
			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

			// GET JOBPARENT
				$JOBCODEID 	= [];
				$getJOBPAR 	= "SELECT JOBCODEID FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID LIKE '$JOBPARENT%' AND ISLASTH = 1";
				$resJOBPAR 	= $this->db->query($getJOBPAR);
				if($resJOBPAR->num_rows() > 0)
				{
					foreach($resJOBPAR->result() as $rJOB):
						$JOBCODEID[] = $rJOB->JOBCODEID;
					endforeach;
				}

				$JoinJOBPAR 	= join("','", $JOBCODEID);
		    // $qJOBITEM 		= "SELECT A.JOBCODEID, A.JOBDESC, A.ITM_CODE, B.ITM_NAME FROM tbl_joblist_detail_$PRJCODEVW A 
		    // 					INNER JOIN tbl_item_$PRJCODEVW B ON B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE
		    // 					WHERE A.JOBPARENT = '$JOBPARENT' AND ISLAST = 1 
		    // 					ORDER BY ORD_ID ASC";

			$qJOBITEM 		= "SELECT ITM_CODE, ITM_NAME FROM tbl_item_$PRJCODEVW
								WHERE ITM_CODE IN (SELECT DISTINCT ITM_CODE FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBPARENT IN ('$JoinJOBPAR') AND ISLAST = 1)
								ORDER BY ITM_CODE, ITM_GROUP ASC";
		    $resJOBITEM 	= $this->db->query($qJOBITEM);
		    $data 			= $resJOBITEM->result();
		    echo json_encode($data);
		}
		else
		{
		    redirect('__l1y');
		}
	}
	
	function getITM_CODE_NEW()
	{
	    if ($this->session->userdata('login') == TRUE)
		{
		    // $ITM_GROUP    	= $this->input->post('ITM_GROUP');
		    // $PRJCODE    	= $this->input->post('PRJCODE');
			$PRJCODE 		= $_GET['id'];
			$ITM_GROUP 		= $_GET['ITM_GROUP'];
			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

			if($ITM_GROUP == 'All')
			{
				$qJOBITEM 	= "SELECT ITM_CODE, ITM_NAME FROM tbl_item_$PRJCODEVW
								WHERE PRJCODE = '$PRJCODE'
								ORDER BY ITM_GROUP, ITM_CODE ASC";
			}
			else
			{
				$qJOBITEM 	= "SELECT ITM_CODE, ITM_NAME FROM tbl_item_$PRJCODEVW
								WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP = '$ITM_GROUP'
								ORDER BY ITM_GROUP, ITM_CODE ASC";
			}
		    $resJOBITEM 	= $this->db->query($qJOBITEM);
		    $data 			= $resJOBITEM->result();
		    echo json_encode($data);
		}
		else
		{
		    redirect('__l1y');
		}
	}

	function getAllITM_CODE()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE    	= $this->input->post('PRJCODE');
			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			$ITM_GROUP 		= $this->input->post('ITM_GROUP');
			$joinITMG 		= join("','", $ITM_GROUP);

			$qJOBITEM 		= "SELECT ITM_CODE, ITM_NAME FROM tbl_item_$PRJCODEVW
								WHERE ITM_CODE IN (SELECT DISTINCT ITM_CODE FROM tbl_joblist_detail_$PRJCODEVW WHERE ISLAST = 1)
								AND ITM_GROUP IN ('$joinITMG')
								ORDER BY ITM_NAME, ITM_GROUP ASC";
		    $resJOBITEM 	= $this->db->query($qJOBITEM);
		    $data 			= $resJOBITEM->result();
		    echo json_encode($data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getAllJOBPARENT()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE    = $this->input->post('PRJCODE');
			$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			$ITM_CODE 	= $this->input->post('ITM_CODE');

			// $joinITM 	= join("','", $ITM_CODE);

			// $JOBLEV    	= $this->input->post('JOBLEV');
			// $this->db->from("tbl_joblist_detail");
			// $this->db->where(['PRJCODE' => $PRJCODE, 'ISLAST' => 0]);
			// if($JOBLEV[0] != 0) $this->db->where_in("IS_LEVEL", $JOBLEV);
			// $this->db->order_by('ORD_ID','ASC');
			// get JOBPARENT BY PRJCODE
			$getJOBPAR 	= "SELECT * FROM tbl_joblist_detail_$PRJCODEVW 
							WHERE ISLASTH = 1
							AND JOBCODEID IN (SELECT JOBPARENT FROM tbl_joblist_detail_$PRJCODEVW WHERE ISLAST = 1 AND ITM_CODE = '$ITM_CODE')
							ORDER BY ORD_ID ASC";
			$data 		= $this->db->query($getJOBPAR)->result();
		    echo json_encode($data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getITM_CODE_HOLD221121()
	{
	    if ($this->session->userdata('login') == TRUE)
		{
		    $JOBPARENT    	= $this->input->post('JOBPARENT');
		    $PRJCODE    	= $this->input->post('PRJCODE');
			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

			// GET JOBPARENT
				$JOBCODEID 	= [];
				$getJOBPAR 	= "SELECT JOBCODEID FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID LIKE '$JOBPARENT%' AND ISLASTH = 1";
				$resJOBPAR 	= $this->db->query($getJOBPAR);
				if($resJOBPAR->num_rows() > 0)
				{
					foreach($resJOBPAR->result() as $rJOB):
						$JOBCODEID[] = $rJOB->JOBCODEID;
					endforeach;
				}

			$JoinJOBPAR 	= join("','", $JOBCODEID);

			// get ITM_GROUP
			$qITMGROUP 		= "SELECT IG_Code, IG_Name FROM tbl_itemgroup 
								WHERE IG_Code IN (SELECT DISTINCT ITM_GROUP FROM tbl_item_$PRJCODEVW
													WHERE ITM_CODE IN (SELECT DISTINCT ITM_CODE FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBPARENT IN ('$JoinJOBPAR') AND ISLAST = 1))";
			$resITMGROUP 	= $this->db->query($qITMGROUP);
			if($resITMGROUP->num_rows() > 0)
			{
				foreach($resITMGROUP->result() as $ITMG):
					$IG_Code 	= $ITMG->IG_Code;
					$IG_Name 	= $ITMG->IG_Name;

					// get ITM
					$qJOBITEM 		= "SELECT ITM_CODE, ITM_NAME FROM tbl_item_$PRJCODEVW
										WHERE ITM_CODE IN (SELECT DISTINCT ITM_CODE FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBPARENT IN ('$JoinJOBPAR') AND ISLAST = 1) AND ITM_GROUP = '$IG_Code'
										ORDER BY ITM_GROUP ASC";
					$resJOBITEM 	= $this->db->query($qJOBITEM);
					if($resJOBITEM->num_rows() > 0)
					{
						foreach($resJOBITEM->result() as $rITM):
							$ITM_CODE = $rITM->ITM_CODE;
							$ITM_NAME = $rITM->ITM_NAME;
							$chData[] = ["id" => $ITM_CODE, "text" => "$ITM_CODE - $ITM_NAME"];
						endforeach;
					}

					$data[] 	= ["text" => $IG_Name, "children" => $chData];
				endforeach;
			}

			// $qJOBITEM 		= "SELECT ITM_CODE, ITM_NAME FROM tbl_item_$PRJCODEVW
			// 					WHERE ITM_CODE IN (SELECT DISTINCT ITM_CODE FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBPARENT IN ('$JoinJOBPAR') AND ISLAST = 1)
			// 					ORDER BY ITM_CODE, ITM_GROUP ASC";
		    // $resJOBITEM 	= $this->db->query($qJOBITEM);
		    // $data 			= $resJOBITEM->result();
		    echo json_encode($data);
		}
		else
		{
		    redirect('__l1y');
		}
	}

	function getITM()
	{
	    if ($this->session->userdata('login') == TRUE)
		{
		    $PRJCODE    	= $this->input->post('PRJCODE');
			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		    $qJOBITEM 		= "SELECT DISTINCT ITM_CODE, ITM_NAME FROM tbl_item_$PRJCODEVW
		    					ORDER BY ITM_CODE, ITM_GROUP ASC";
		    $resJOBITEM 	= $this->db->query($qJOBITEM);
		    $data 			= $resJOBITEM->result();
		    echo json_encode($data);
		}
		else
		{
		    redirect('__l1y');
		}
	}
	
	function s1R3p_1n4() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['MenuCode']		= 'MN132';
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN132';
				$data["MenuApp"] 	= 'MN132';
				$data["MenuCode"] 	= 'MN132';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_project/c_r3p/s1R3p2VwR3p/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_si_project/v_si_project', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s1R3p2VwR3p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN DETIL PEKERJAAN TAMBAH/KURANG';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'DETAIL OF SITE INSTRUCTION REPORT';
				$data['h2_title']	= 'Report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$PRJNAME			= '';
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 			= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$PRJNAME		= $rowPRJ->PRJNAME;
			endforeach;
			$data['PRJNAME'] 	= $rowPRJ->PRJNAME;
			
			$data['CFType'] 	= $this->input->post('CFType');		// 1. Summary or 2. Detail --> Always Summary
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			$this->load->view('v_project/v_report/v_si_project/v_si_project_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_monitoringspk()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['form_action']= site_url('c_project/c_r3p/r_monitoringspk_view/?id='.$this->url_encryption_helper->encode_url($appName));
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'Monitoring SPK/Opname';
				$data['h2_title'] 	= 'Laporan';
			}
			else
			{
				$data['h1_title'] 	= 'Monitoring SPK/Opname';
				$data['h2_title'] 	= 'Report';
			}

			$this->load->view('v_project/v_report/r_monitoringspk/r_monitoringspk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_monitoringspk_view()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'Laporan Monitoring SPK/Opname';
			}
			else
			{
				$data['h1_title'] 	= 'Laporan Monitoring SPK/Opname';
			}
			
			$data['PRJCODE']	= $this->input->post('PRJCODE');
			$data['SPLCODE']	= $this->input->post('SPLCODE');
			$data['IDXSHOW']	= $this->input->post('IDXSHOW');
			$SPLCODE			= $this->input->post('SPLCODE');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['viewType'] 	= $this->input->post('viewType');

			$this->load->view('v_project/v_report/r_monitoringspk/r_monitoringspk_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function i7MbUdG43t41L() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname LIMIT 1";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_r3p/i7MbUdG43t41Lx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function i7MbUdG43t41Lx() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['appName'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Detil Budget Item";
				$data["h2_title"] 	= "Laporan";
			}
			else
			{
				$data["h1_title"] 	= "Item Budget Detail";
				$data["h2_title"] 	= "Report";
			}
			
			$data['form_action'] 	= site_url('c_project/c_r3p/ri7MbUdG43t41Lx_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_itembudget_det/v_itembudget_det', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function ri7MbUdG43t41Lx_0fv13w() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];

			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "LAPORAN BUDGET - DETAIL";
				$data["h2_title"] 	= "Detail Budget Detail";
			}
			else
			{
				$data["h1_title"] 	= "BUDGET REPORT - DETAIL";
				$data["h2_title"] 	= "Detail Budget Detail";
			}
			
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			// $data['JOBPARENT'] 	= $this->input->post('JOBPARENT');
			$data['ITM_CODE'] 	= $this->input->post('ITM_CODE');
			// $TransType 	        = $this->input->post('TransType');	// Request or Realization
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			$this->load->view('v_project/v_report/v_itembudget_det/v_itembudgetdet_rep', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function i7MbUdGR3Q() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['appName'] 		= $appName;
			
			// START : GET MENU NAME
				$mnCode				= 'MN515';
				$data["MenuApp"] 	= 'MN515';
				$data["MenuCode"] 	= 'MN515';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME
			
			$data['form_action'] 	= site_url('c_project/c_r3p/i7MbUdGR3Q_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_project/v_report/v_itembudg_detreq/v_itembudg_detreq', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i7MbUdGR3Q_0fv13w() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			// START : GET MENU NAME
				$mnCode				= 'MN515';
				$data["MenuApp"] 	= 'MN515';
				$data["MenuCode"] 	= 'MN515';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME
			
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE 			= $this->input->post('PRJCODE');
			$data['JOBPARENT'] 	= $this->input->post('JOBPARENT');
			$data['ITM_CODE'] 	= $this->input->post('ITM_CODE');
			$ITM_CODE 			= $this->input->post('ITM_CODE');
			$TransType 	        = $this->input->post('TransType');	// Detail Pekerjaan or Detail Item
			$ReportType 	    = $this->input->post('ReportType');	// Detail Request or Detail Realisasi
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));

			$DATES				= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$DATEE				= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));

			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			if($TransType == 1)
			{
				if($ReportType == 0) $this->load->view('v_project/v_report/v_itembudg_detreq/v_jobbudg_detreqrep', $data);
				else $this->load->view('v_project/v_report/v_itembudg_detreq/v_jobbudg_detrealizrep', $data);
			}		
			else 
			{
				if($ReportType == 0)
					$this->load->view('v_project/v_report/v_itembudg_detreq/v_itembudg_detreqrep', $data);
				elseif($ReportType == 1)
					$this->load->view('v_project/v_report/v_itembudg_detreq/v_itembudg_detrealizrep', $data);
				elseif($ReportType == 2)		// detail transaksi basic
				{
					$data['PRJCODE'] 		= $PRJCODE;
					$data['ITM_CODE'] 		= $ITM_CODE;
					$data['DATES'] 			= $DATES;
					$data['DATEE'] 			= $DATEE;
					
					$this->load->view('v_project/v_report/v_itembudet_sum/v_itembudet_detil_hist', $data);
				}
				elseif($ReportType == 3)		// detail transaksi basic
				{
					$data['PRJCODE'] 		= $PRJCODE;
					$data['ITM_CODE'] 		= $ITM_CODE;
					$data['DATES'] 			= $DATES;
					$data['DATEE'] 			= $DATEE;
					
					$this->load->view('v_project/v_report/v_itembudet_sum/v_itembudet_detil_hist_anom', $data);
				}
				else
					$this->load->view('v_project/v_report/v_itembudg_detreq/v_itembudg_detrealizrep', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function get_AllReportData_sum()
	{
		$POST 		= $this->input->post();
		$PRJCODE 	= $POST['PRJCODE'];
		$JOBPARENT 	= $POST['JOBPARENT'];
		$Start_Date = $POST['Start_Date'];
		$End_Date 	= $POST['End_Date'];

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$JOBPARENT 	= trim($JOBPARENT);
		
		if($JOBPARENT == 'All')
		{
			$addQJOB_ID 	= "";
			$addQJOB_P 		= "";
		} 
		else 
		{
			$addQJOB_ID 	= "AND JOBCODEID = '$JOBPARENT'";
			$addQJOB_P 	= "AND JOBCODEID LIKE '$JOBPARENT%'";
		}

		$getJOBD 		= "SELECT ORD_ID, JOBCODEID, JOBPARENT, PRJCODE, JOBDESC, ITM_GROUP, ITM_CODE, 
							ITM_UNIT, ITM_VOLM,	ITM_PRICE, ITM_LASTP, ITM_AVGP, ITM_BUDG, IS_LEVEL, ISLASTH,
							ISLAST, WBSD_STAT
							FROM tbl_joblist_detail_$PRJCODEVW
							WHERE ISLAST = 0 $addQJOB_P  ORDER BY JOBCODEID, ORD_ID ASC";
		$resJOBD 		= $this->db->query($getJOBD);
		if($resJOBD->num_rows() > 0)
		{
			$GTITM_BUDG 		= 0;
			$GTADD_TOTAL 		= 0;
			$GTADD_TOTAL_KOM 	= 0;
			$GTITM_BUDG2 		= 0;
			$GTREQ_AMOUNT 		= 0;
			$GTREQ_AMOUNT_KOM 	= 0;
			$GTITM_USED_AM 		= 0;
			$GTITM_USED_AM_KOM	= 0;
			$GTREMREQ_AMOUNT 	= 0;
			$GTREMREALZ_AMOUNT 	= 0;
			foreach($resJOBD->result() as $rJOBD):
				$ORD_ID 		= $rJOBD->ORD_ID;
				$JOBCODEID 		= $rJOBD->JOBCODEID;
				$JOBPARENT 		= $rJOBD->JOBPARENT;
				$PRJCODE 		= $rJOBD->PRJCODE;
				$JOBDESC 		= $rJOBD->JOBDESC;
				$ITM_GROUP 		= $rJOBD->ITM_GROUP;
				$ITM_CODE 		= $rJOBD->ITM_CODE;
				$ITM_UNIT 		= $rJOBD->ITM_UNIT;
				$ITM_VOLM 		= $rJOBD->ITM_VOLM;
				$ITM_PRICE 		= $rJOBD->ITM_PRICE;
				$ITM_LASTP 		= $rJOBD->ITM_LASTP;
				$ITM_AVGP 		= $rJOBD->ITM_AVGP;
				$ITM_BUDG 		= $rJOBD->ITM_BUDG;
				$IS_LEVEL 		= $rJOBD->IS_LEVEL;
				$ISLASTH 		= $rJOBD->ISLASTH;
				$ISLAST 		= $rJOBD->ISLAST;
				$WBSD_STAT 		= $rJOBD->WBSD_STAT;

				$ITM_VOLMBG 	= $ITM_VOLM;

				if($ITM_UNIT == '') $ITM_UNIT = 'LS';

				// SPACE
					$spaceLev 		= "";
					if($IS_LEVEL == 1)
						$spaceLev 	= 0;
					elseif($IS_LEVEL == 2)
						$spaceLev 	= 15;
					elseif($IS_LEVEL == 3)
						$spaceLev 	= 30;
					elseif($IS_LEVEL == 4)
						$spaceLev 	= 45;
					elseif($IS_LEVEL == 5)
						$spaceLev 	= 60;
					elseif($IS_LEVEL == 6)
						$spaceLev 	= 75;
					elseif($IS_LEVEL == 7)
						$spaceLev 	= 90;
					elseif($IS_LEVEL == 8)
						$spaceLev 	= 105;
					elseif($IS_LEVEL == 9)
						$spaceLev 	= 120;
					elseif($IS_LEVEL == 10)
						$spaceLev 	= 135;
					elseif($IS_LEVEL == 11)
						$spaceLev 	= 150;
					elseif($IS_LEVEL == 12)
						$spaceLev 	= 165;

				$JobView1		= "$JOBCODEID - $JOBDESC";
				$JobView		= wordwrap($JobView1, 90, "<br>", true);

				$REMREQ_VOLM 		= 0;
				$REMREQ_AMOUNT 		= 0;
				$REMREALZ_VOLM 		= 0;
				$REMREALZ_AMOUNT 	= 0;
				// Get BUDGET => PERIODE INI
					/*$get_RQRLZ 	= "SELECT SUM(AMD_VOL+AMD_VOL_R) AS ADDVOLM, 
									SUM(AMD_VAL+AMD_VAL_R) AS ADD_TOTAL,
									SUM(PO_VOL+PO_VOL_R+WO_VOL+WO_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REQ_VOLM,
									SUM(PO_VAL+PO_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REQ_AMOUNT,
									-- SUM(IR_VOL+IR_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
									SUM(IR_VOL+IR_VOL_R) AS REALZIR_VOLM,
									SUM(UM_VOL+UM_VOL_R) AS REALZUM_VOLM,
									SUM(OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
									-- SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT
									SUM(IR_VAL+IR_VAL_R) AS REALZIR_AMOUNT,
									SUM(UM_VAL+UM_VAL_R) AS REALZUM_AMOUNT,
									SUM(OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT
									FROM tbl_joblist_report_$PRJCODEVW
									WHERE JOBCODEID LIKE '$JOBCODEID%'
									AND PERIODE BETWEEN '$Start_Date' AND '$End_Date'";
					$res_RQRLZ 	= $this->db->query($get_RQRLZ);*/

					// SAAT INI REALISASI DISIAPKAN DARI LPM TERLEBIH DAHULU
					$get_RQRLZ 	= "SELECT 	IFNULL(SUM(AMD_VOL - AMDM_VOL),0) AS AMD_VOL, IFNULL(SUM(AMD_VAL - AMDM_VAL),0) AS AMD_VAL,
											IFNULL(SUM(PR_VOL+WO_VOL+VCASH_VOL+VLK_VOL+PPD_VOL+PR_VOL_R+WO_VOL_R+VCASH_VOL_R+VLK_VOL_R+PPD_VOL_R-PR_CVOL-WO_CVOL),0) AS REQ_VOLM,
											IFNULL(SUM(PO_VAL+WO_VAL+VCASH_VAL+VLK_VAL+PPD_VAL+PO_VAL_R+WO_VAL_R+VCASH_VAL_R+VLK_VAL_R+PPD_VAL_R-PO_CVAL-WO_CVAL),0) AS REQ_AMOUNT,
											IFNULL(SUM(IR_VOL+IR_VOL_R),0) AS REALZIR_VOLM,
											IFNULL(SUM(IR_VAL+IR_VAL_R),0) AS REALZIR_AMOUNT,
											IFNULL(SUM(UM_VOL+UM_VOL_R),0) AS REALZUM_VOLM,
											IFNULL(SUM(UM_VAL+UM_VAL_R),0) AS REALZUM_AMOUNT,
											-- SUM(UM_VOL+UM_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
											-- SUM(UM_VAL+UM_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT,
											IFNULL(SUM(IR_VOL+IR_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R),0) AS REALZ_VOLM,
											IFNULL(SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R),0) AS REALZ_AMOUNT
									FROM tbl_item_logbook_$PRJCODEVW
									WHERE JOBCODEID LIKE '$JOBCODEID%' AND DOC_DATE BETWEEN '$Start_Date' AND '$End_Date'";
					$res_RQRLZ 	= $this->db->query($get_RQRLZ);
					foreach($res_RQRLZ->result() as $rRQRLZ):
						// Addendum
							$ADDVOLM 		= $rRQRLZ->AMD_VOL;
							$ADD_TOTAL 		= $rRQRLZ->AMD_VAL;

						// REQUEST :
							$REQ_VOLM 		= $rRQRLZ->REQ_VOLM;
							$REQ_AMOUNT 	= $rRQRLZ->REQ_AMOUNT;

						// REALISASI
							// $ITM_USED 		= $rRQRLZ->REALZ_VOLM;
							// $ITM_USED_AM 	= $rRQRLZ->REALZ_AMOUNT;
							if($ITM_GROUP == 'M' || $ITM_GROUP == 'T')
							{
								$ITM_USED 		= ($rRQRLZ->REALZ_VOLM+$rRQRLZ->REALZUM_VOLM);
								$ITM_USED_AM 	= ($rRQRLZ->REALZ_AMOUNT+$rRQRLZ->REALZUM_AMOUNT);
							}
							else
							{
								$ITM_USED 		= ($rRQRLZ->REALZ_VOLM+$rRQRLZ->REALZIR_VOLM);
								$ITM_USED_AM 	= ($rRQRLZ->REALZ_AMOUNT+$rRQRLZ->REALZIR_AMOUNT);
							}

					endforeach;

				// Get BUDGET => KOMULATIF
				// SAAT INI REALISASI DISIAPKAN DARI LPM TERLEBIH DAHULU
				$get_LRQRLZ 	= "SELECT 	IFNULL(SUM(AMD_VOL - AMDM_VOL),0) AS AMD_VOL, IFNULL(SUM(AMD_VAL - AMDM_VAL),0) AS AMD_VAL,
											IFNULL(SUM(PR_VOL+WO_VOL+VCASH_VOL+VLK_VOL+PPD_VOL+PR_VOL_R+WO_VOL_R+VCASH_VOL_R+VLK_VOL_R+PPD_VOL_R-PR_CVOL-WO_CVOL),0) AS REQ_VOLM,
											IFNULL(SUM(PO_VAL+WO_VAL+VCASH_VAL+VLK_VAL+PPD_VAL+PO_VAL_R+WO_VAL_R+VCASH_VAL_R+VLK_VAL_R+PPD_VAL_R-PO_CVAL-WO_CVAL),0) AS REQ_AMOUNT,
											IFNULL(SUM(IR_VOL+IR_VOL_R),0) AS REALZIR_VOLM,
											IFNULL(SUM(IR_VAL+IR_VAL_R),0) AS REALZIR_AMOUNT,
											IFNULL(SUM(UM_VOL+UM_VOL_R),0) AS REALZUM_VOLM,
											IFNULL(SUM(UM_VAL+UM_VAL_R),0) AS REALZUM_AMOUNT,
											-- SUM(UM_VOL+UM_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
											-- SUM(UM_VAL+UM_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT,
											IFNULL(SUM(IR_VOL+IR_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R),0) AS REALZ_VOLM,
											IFNULL(SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R),0) AS REALZ_AMOUNT
									FROM tbl_item_logbook_$PRJCODEVW
									WHERE JOBCODEID LIKE '$JOBCODEID%'
									AND DOC_DATE <= '$End_Date'";
				$res_LRQRLZ 	= $this->db->query($get_LRQRLZ);
				foreach($res_LRQRLZ->result() as $rLRQRLZ):
					// Addendum
						$ADDVOLM_KOM	= $rLRQRLZ->AMD_VOL;
						$ADD_TOTAL_KOM	= $rLRQRLZ->AMD_VAL;

					// REQUEST :
						$REQ_VOLM_KOM	= $rLRQRLZ->REQ_VOLM;
						$REQ_AMOUNT_KOM	= $rLRQRLZ->REQ_AMOUNT;

					// REALISASI
						// $ITM_USED_KOM	= $rLRQRLZ->REALZ_VOLM;
						// $ITM_USED_AM_KOM= $rLRQRLZ->REALZ_AMOUNT;
						if($ITM_GROUP == 'M' || $ITM_GROUP == 'T')
						{
							$ITM_USED_KOM 		= ($rLRQRLZ->REALZ_VOLM+$rLRQRLZ->REALZUM_VOLM);
							$ITM_USED_AM_KOM 	= ($rLRQRLZ->REALZ_AMOUNT+$rLRQRLZ->REALZUM_AMOUNT);
						}
						else
						{
							$ITM_USED_KOM 		= ($rLRQRLZ->REALZ_VOLM+$rLRQRLZ->REALZIR_VOLM);
							$ITM_USED_AM_KOM 	= ($rLRQRLZ->REALZ_AMOUNT+$rLRQRLZ->REALZIR_AMOUNT);
						}

				endforeach;

				// after addendum
					$ITM_VOLM2 		= $ITM_VOLMBG + $ADDVOLM_KOM;
					$ITM_BUDG2 		= $ITM_BUDG + $ADD_TOTAL_KOM;

				// SISA BUDG THD REQ
					$REMREQ_VOLM 	= $ITM_VOLM2 - $REQ_VOLM_KOM;
					$REMREQ_AMOUNT 	= $ITM_BUDG2 - $REQ_AMOUNT_KOM;
				
				// SISA BUDG THD REALISASI
					$REMREALZ_VOLM 	= $ITM_VOLM2 - $ITM_USED_KOM;
					$REMREALZ_AMOUNT= $ITM_BUDG2 - $ITM_USED_AM_KOM;

				if($ISLASTH == 1)
				{
					$CELL_COL	= "font-weight:normal;";
					$GTITM_BUDG = $GTITM_BUDG + $ITM_BUDG;

					// TOTAL
						$GTADD_TOTAL 		= $GTADD_TOTAL + $ADD_TOTAL;
						$GTADD_TOTAL_KOM	= $GTADD_TOTAL_KOM + $ADD_TOTAL_KOM;
						$GTITM_BUDG2 		= $GTITM_BUDG2 + $ITM_BUDG2;
						$GTREQ_AMOUNT 		= $GTREQ_AMOUNT + $REQ_AMOUNT;
						$GTREQ_AMOUNT_KOM 	= $GTREQ_AMOUNT_KOM + $REQ_AMOUNT_KOM;
						$GTITM_USED_AM 		= $GTITM_USED_AM + $ITM_USED_AM;
						$GTITM_USED_AM_KOM 	= $GTITM_USED_AM_KOM + $ITM_USED_AM_KOM;
						$GTREMREQ_AMOUNT	= $GTREMREQ_AMOUNT + $REMREQ_AMOUNT;
						$GTREMREALZ_AMOUNT 	= $GTREMREALZ_AMOUNT + $REMREALZ_AMOUNT;
				}
				else
				{
					$CELL_COL	= "font-weight:bold;";	
				}

				$output['data'][] 	= array($JobView,
											"<span style='".$CELL_COL."'>".$ITM_UNIT."</span>",
											$ISLASTH,
											$IS_LEVEL,
											$ITM_VOLM,
											$ITM_BUDG,
											$ADDVOLM,
											$ADD_TOTAL,
											$ADDVOLM_KOM,
											$ADD_TOTAL_KOM,
											$ITM_VOLM2,
											$ITM_BUDG2,
											$REQ_VOLM,
											$REQ_AMOUNT,
											$REQ_VOLM_KOM,
											$REQ_AMOUNT_KOM,
											$ITM_VOLM2,
											$REMREQ_AMOUNT);
			endforeach;

			echo json_encode($output);
		}
	}

	function get_AllReportData_jobdet()
	{
		$POST 		= $this->input->post();
		$PRJCODE 	= $POST['PRJCODE'];
		$JOBPARENT 	= $POST['JOBPARENT'];
		$Start_Date = $POST['Start_Date'];
		$End_Date 	= $POST['End_Date'];

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$JOBPARENT 	= trim($JOBPARENT);

		if($JOBPARENT == 'All')
		{
			$addQJOB_ID = "";
			$addQJOB_P 	= "";
		} 
		else 
		{
			$addQJOB_ID = "WHERE JOBCODEID = '$JOBPARENT'";
			$addQJOB_P 	= "WHERE JOBCODEID LIKE '$JOBPARENT%'";
		}

		$getJOBD 		= "SELECT ORD_ID, JOBCODEID, JOBPARENT, PRJCODE, JOBDESC, ITM_GROUP, ITM_CODE, 
							ITM_UNIT, ITM_VOLM,	ITM_PRICE, ITM_LASTP, ITM_AVGP, ITM_BUDG, IS_LEVEL, ISLASTH,
							ISLAST, WBSD_STAT
							FROM tbl_joblist_detail_$PRJCODEVW
							$addQJOB_P ORDER BY JOBCODEID, ORD_ID ASC";
		$resJOBD 		= $this->db->query($getJOBD);
		if($resJOBD->num_rows() > 0)
		{
			$GTITM_BUDG 		= 0;
			$GTADD_TOTAL 		= 0;
			$GTADD_TOTAL_KOM 	= 0;
			$GTITM_BUDG2 		= 0;
			$GTREQ_AMOUNT 		= 0;
			$GTREQ_AMOUNT_KOM 	= 0;
			$GTITM_USED_AM 		= 0;
			$GTITM_USED_AM_KOM	= 0;
			$GTREMREQ_AMOUNT 	= 0;
			$GTREMREALZ_AMOUNT 	= 0;
			foreach($resJOBD->result() as $rJOBD):
				$ORD_ID 		= $rJOBD->ORD_ID;
				$JOBCODEID 		= $rJOBD->JOBCODEID;
				$JOBPARENT 		= $rJOBD->JOBPARENT;
				$PRJCODE 		= $rJOBD->PRJCODE;
				$JOBDESC 		= $rJOBD->JOBDESC;
				$ITM_GROUP 		= $rJOBD->ITM_GROUP;
				$ITM_CODE 		= $rJOBD->ITM_CODE;
				$ITM_UNIT 		= $rJOBD->ITM_UNIT;
				$ITM_VOLM 		= $rJOBD->ITM_VOLM;
				$ITM_PRICE 		= $rJOBD->ITM_PRICE;
				$ITM_LASTP 		= $rJOBD->ITM_LASTP;
				$ITM_AVGP 		= $rJOBD->ITM_AVGP;
				$ITM_BUDG 		= $rJOBD->ITM_BUDG;
				$IS_LEVEL 		= $rJOBD->IS_LEVEL;
				$ISLASTH 		= $rJOBD->ISLASTH;
				$ISLAST 		= $rJOBD->ISLAST;
				$WBSD_STAT 		= $rJOBD->WBSD_STAT;

				$ITM_VOLMBG 	= $ITM_VOLM;

				if($ITM_UNIT == '') $ITM_UNIT = 'LS';

				$JobView1		= "$JOBCODEID - $JOBDESC";
				$JobView		= wordwrap($JobView1, 90, "<br>", true);

				$REMREQ_VOLM 		= 0;
				$REMREQ_AMOUNT 		= 0;
				$REMREALZ_VOLM 		= 0;
				$REMREALZ_AMOUNT 	= 0;
				if($ISLAST == 0)
					$ADDQRY_01 		= "JOBCODEID LIKE '$JOBCODEID%'";
				else
					$ADDQRY_01 		= "JOBCODEID = '$JOBCODEID'";

				// Get BUDGET => PERIODE INI
				// SAAT INI REALISASI DISIAPKAN DARI LPM TERLEBIH DAHULU
					$get_RQRLZ 	= "SELECT 	IFNULL(SUM(AMD_VOL - AMDM_VOL),0) AS ADDVOLM, IFNULL(SUM(AMD_VAL - AMDM_VAL),0) AS ADD_TOTAL,
											IFNULL(SUM(PR_VOL+WO_VOL+VCASH_VOL+VLK_VOL+PPD_VOL+PR_VOL_R+WO_VOL_R+VCASH_VOL_R+VLK_VOL_R+PPD_VOL_R-PR_CVOL-WO_CVOL),0) AS REQ_VOLM,
											IFNULL(SUM(PO_VAL+WO_VAL+VCASH_VAL+VLK_VAL+PPD_VAL+PO_VAL_R+WO_VAL_R+VCASH_VAL_R+VLK_VAL_R+PPD_VAL_R-PO_CVAL-WO_CVAL),0) AS REQ_AMOUNT,
											IFNULL(SUM(IR_VOL+IR_VOL_R),0) AS REALZIR_VOLM,
											IFNULL(SUM(IR_VAL+IR_VAL_R),0) AS REALZIR_AMOUNT,
											IFNULL(SUM(UM_VOL+UM_VOL_R),0) AS REALZUM_VOLM,
											IFNULL(SUM(UM_VAL+UM_VAL_R),0) AS REALZUM_AMOUNT,
											-- SUM(UM_VOL+UM_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
											-- SUM(UM_VAL+UM_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT,
											IFNULL(SUM(IR_VOL+IR_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R),0) AS REALZ_VOLM,
											IFNULL(SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R),0) AS REALZ_AMOUNT
									FROM tbl_item_logbook_$PRJCODEVW
									WHERE $ADDQRY_01 AND DOC_DATE BETWEEN '$Start_Date' AND '$End_Date'";
					$res_RQRLZ 	= $this->db->query($get_RQRLZ);
					foreach($res_RQRLZ->result() as $rRQRLZ):
						// Addendum
							$ADDVOLM 		= $rRQRLZ->ADDVOLM;
							$ADD_TOTAL 		= $rRQRLZ->ADD_TOTAL;

						// REQUEST :
							$REQ_VOLM 		= $rRQRLZ->REQ_VOLM;
							$REQ_AMOUNT 	= $rRQRLZ->REQ_AMOUNT;

						// REALISASI
							// $ITM_USED 		= $rRQRLZ->REALZ_VOLM;
							// $ITM_USED_AM 	= $rRQRLZ->REALZ_AMOUNT;
							if($ITM_GROUP == 'M' || $ITM_GROUP == 'T')
							{
								$ITM_USED 		= ($rRQRLZ->REALZ_VOLM+$rRQRLZ->REALZUM_VOLM);
								$ITM_USED_AM 	= ($rRQRLZ->REALZ_AMOUNT+$rRQRLZ->REALZUM_AMOUNT);
							}
							else
							{
								$ITM_USED 		= ($rRQRLZ->REALZ_VOLM+$rRQRLZ->REALZIR_VOLM);
								$ITM_USED_AM 	= ($rRQRLZ->REALZ_AMOUNT+$rRQRLZ->REALZIR_AMOUNT);
							}

					endforeach;

				// Get BUDGET => KOMULATIF
				// SAAT INI REALISASI DISIAPKAN DARI LPM TERLEBIH DAHULU
					$get_LRQRLZ	= "SELECT 	IFNULL(SUM(AMD_VOL - AMDM_VOL),0) AS ADDVOLM, IFNULL(SUM(AMD_VAL - AMDM_VAL),0) AS ADD_TOTAL,
											IFNULL(SUM(PR_VOL+WO_VOL+VCASH_VOL+VLK_VOL+PPD_VOL+PR_VOL_R+WO_VOL_R+VCASH_VOL_R+VLK_VOL_R+PPD_VOL_R-PR_CVOL-WO_CVOL),0) AS REQ_VOLM,
											IFNULL(SUM(PO_VAL+WO_VAL+VCASH_VAL+VLK_VAL+PPD_VAL+PO_VAL_R+WO_VAL_R+VCASH_VAL_R+VLK_VAL_R+PPD_VAL_R-PO_CVAL-WO_CVAL),0) AS REQ_AMOUNT,
											IFNULL(SUM(IR_VOL+IR_VOL_R),0) AS REALZIR_VOLM,
											IFNULL(SUM(IR_VAL+IR_VAL_R),0) AS REALZIR_AMOUNT,
											IFNULL(SUM(UM_VOL+UM_VOL_R),0) AS REALZUM_VOLM,
											IFNULL(SUM(UM_VAL+UM_VAL_R),0) AS REALZUM_AMOUNT,
											-- SUM(UM_VOL+UM_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
											-- SUM(UM_VAL+UM_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT,
											IFNULL(SUM(IR_VOL+IR_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R),0) AS REALZ_VOLM,
											IFNULL(SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R),0) AS REALZ_AMOUNT
									FROM tbl_item_logbook_$PRJCODEVW
									WHERE $ADDQRY_01 AND DOC_DATE <= '$End_Date'";
				$res_LRQRLZ 	= $this->db->query($get_LRQRLZ);
				foreach($res_LRQRLZ->result() as $rLRQRLZ):
					// Addendum
						$ADDVOLM_KOM	= $rLRQRLZ->ADDVOLM;
						$ADD_TOTAL_KOM	= $rLRQRLZ->ADD_TOTAL;

					// REQUEST :
						$REQ_VOLM_KOM	= $rLRQRLZ->REQ_VOLM;
						$REQ_AMOUNT_KOM	= $rLRQRLZ->REQ_AMOUNT;

					// REALISASI
						// $ITM_USED_KOM	= $rLRQRLZ->REALZ_VOLM;
						// $ITM_USED_AM_KOM= $rLRQRLZ->REALZ_AMOUNT;
						if($ITM_GROUP == 'M' || $ITM_GROUP == 'T')
						{
							$ITM_USED_KOM 		= ($rLRQRLZ->REALZ_VOLM+$rLRQRLZ->REALZUM_VOLM);
							$ITM_USED_AM_KOM 	= ($rLRQRLZ->REALZ_AMOUNT+$rLRQRLZ->REALZUM_AMOUNT);
						}
						else
						{
							$ITM_USED_KOM 		= ($rLRQRLZ->REALZ_VOLM+$rLRQRLZ->REALZIR_VOLM);
							$ITM_USED_AM_KOM 	= ($rLRQRLZ->REALZ_AMOUNT+$rLRQRLZ->REALZIR_AMOUNT);
						}

				endforeach;

				// after addendum
					$ITM_VOLM2 		= $ITM_VOLMBG + $ADDVOLM_KOM;
					$ITM_BUDG2 		= $ITM_BUDG + $ADD_TOTAL_KOM;

				// SISA BUDG THD REQ
					$REMREQ_VOLM 	= $ITM_VOLM2 - $REQ_VOLM_KOM;
					$REMREQ_AMOUNT 	= $ITM_BUDG2 - $REQ_AMOUNT_KOM;
				
				// SISA BUDG THD REALISASI
					$REMREALZ_VOLM 	= $ITM_VOLM2 - $ITM_USED_KOM;
					$REMREALZ_AMOUNT= $ITM_BUDG2 - $ITM_USED_AM_KOM;

				$output['data'][] 	= array($JobView,
											$ITM_UNIT,
											$ISLASTH,
											$ISLAST,
											$IS_LEVEL,
											$ITM_VOLM,
											$ITM_BUDG,
											$ADDVOLM,
											$ADD_TOTAL,
											$ADDVOLM_KOM,
											$ADD_TOTAL_KOM,
											$ITM_VOLM2,
											$ITM_BUDG2,
											$REQ_VOLM,
											$REQ_AMOUNT,
											$REQ_VOLM_KOM,
											$REQ_AMOUNT_KOM,
											$ITM_VOLM2,
											$REMREQ_AMOUNT);

			endforeach;

			echo json_encode($output);

		}
	}

	function get_AllReportData_jobdetonly()
	{
		$POST 		= $this->input->post();
		$PRJCODE 	= $POST['PRJCODE'];
		$JOBPARENT 	= $POST['JOBPARENT'];

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$JOBPARENT 	= trim($JOBPARENT);

		if($JOBPARENT == 'All')
		{
			$addQJOB_ID = "";
			$addQJOB_P 	= "";
		} 
		else 
		{
			$addQJOB_ID = "WHERE (ISLAST = 1 OR ISLASTH = 1) AND JOBCODEID = '$JOBPARENT'";
			$addQJOB_P 	= "WHERE (ISLAST = 1 OR ISLASTH = 1) AND JOBCODEID LIKE '$JOBPARENT%'";
		}

		$getJOBD 		= "SELECT ORD_ID, JOBCODEID, JOBPARENT, PRJCODE, JOBDESC, ITM_GROUP, ITM_CODE, 
							ITM_UNIT, ITM_VOLM,	ITM_PRICE, ITM_LASTP, ITM_AVGP, ITM_BUDG, IS_LEVEL, ISLASTH,
							ISLAST, WBSD_STAT
							FROM tbl_joblist_detail_$PRJCODEVW
							$addQJOB_P ORDER BY JOBCODEID, ORD_ID ASC";
		$resJOBD 		= $this->db->query($getJOBD);
		if($resJOBD->num_rows() > 0)
		{
			$RAPT_VOL 		= 0;
			$RAPT_VAL 		= 0;
			$AMDT_VOL 		= 0;
			$AMDT_VAL 		= 0;
			$RAPT_VOL2 		= 0;
			$RAPT_VAL2 		= 0;
			$REQT_VOL 		= 0;
			$REQT_VAL 		= 0;
			$REMT_VOL 		= 0;
			$REMT_VAL 		= 0;
			foreach($resJOBD->result() as $rJOBD):
				$ORD_ID 		= $rJOBD->ORD_ID;
				$JOBCODEID 		= $rJOBD->JOBCODEID;
				$JOBPARENT 		= $rJOBD->JOBPARENT;
				$PRJCODE 		= $rJOBD->PRJCODE;
				$JOBDESC 		= $rJOBD->JOBDESC;
				$ITM_GROUP 		= $rJOBD->ITM_GROUP;
				$ITM_CODE 		= $rJOBD->ITM_CODE;
				$ITM_UNIT 		= $rJOBD->ITM_UNIT;
				$ITM_VOLM 		= $rJOBD->ITM_VOLM;
				$ITM_PRICE 		= $rJOBD->ITM_PRICE;
				$ITM_LASTP 		= $rJOBD->ITM_LASTP;
				$ITM_AVGP 		= $rJOBD->ITM_AVGP;
				$ITM_BUDG 		= $rJOBD->ITM_BUDG;
				$IS_LEVEL 		= $rJOBD->IS_LEVEL;
				$ISLASTH 		= $rJOBD->ISLASTH;
				$ISLAST 		= $rJOBD->ISLAST;
				$WBSD_STAT 		= $rJOBD->WBSD_STAT;

				$ITM_VOLMBG 	= $ITM_VOLM;

				if($ITM_UNIT == '') $ITM_UNIT = 'LS';

				// SPACE
					$spaceLev 		= "";
					if($IS_LEVEL == 1)
						$spaceLev 	= 0;
					elseif($IS_LEVEL == 2)
						$spaceLev 	= 15;
					elseif($IS_LEVEL == 3)
						$spaceLev 	= 30;
					elseif($IS_LEVEL == 4)
						$spaceLev 	= 45;
					elseif($IS_LEVEL == 5)
						$spaceLev 	= 60;
					elseif($IS_LEVEL == 6)
						$spaceLev 	= 75;
					elseif($IS_LEVEL == 7)
						$spaceLev 	= 90;
					elseif($IS_LEVEL == 8)
						$spaceLev 	= 105;
					elseif($IS_LEVEL == 9)
						$spaceLev 	= 120;
					elseif($IS_LEVEL == 10)
						$spaceLev 	= 135;
					elseif($IS_LEVEL == 11)
						$spaceLev 	= 150;
					elseif($IS_LEVEL == 12)
						$spaceLev 	= 165;

				$JobView1		= "$JOBCODEID - $JOBDESC";
				$JobView		= wordwrap($JobView1, 90, "<br>", true);

				$CELL_COL	= "";
				if($ISLAST == 0)
					$ADDQRY_01 		= "JOBCODEID LIKE '$JOBCODEID%'";
				else
					$ADDQRY_01 		= "JOBCODEID = '$JOBCODEID'";

				$RAP_VOL2 		= 0;
				$RAP_VAL2 		= 0;
				$REQ_VOL 		= 0;
				$REQ_VAL 		= 0;
				$REQ_VOL_R 		= 0;
				$REQ_VAL_R 		= 0;
				$USED_VOL 		= 0;
				$USED_VAL 		= 0;
				$USED_VOL_R		= 0;
				$USED_VAL_R		= 0;
				$RAPT_VOL 		= 0;
				$RAPT_VAL 		= 0;
				$RAP_REM_VOL 	= 0;
				$RAP_REM_VAL 	= 0;
				$get_QRY 		= "SELECT 	IFNULL(SUM(AMD_VOL 	- AMDM_VOL),0) AS ADD_VOL,
										IFNULL(SUM(AMD_VAL 	- AMDM_VAL),0) AS ADD_VAL,
										IFNULL(SUM(PR_VOL 		- PR_CVOL),0) AS PR_VOL,
										IFNULL(SUM(PR_VAL 		- PR_CVAL),0) AS PR_VAL,
										IFNULL(SUM(PR_VOL_R),0) 	AS PR_VOL_R,
										IFNULL(SUM(PR_VAL_R),0) 	AS PR_VAL_R,

										IFNULL(SUM(PO_VOL 		- PO_CVOL),0) AS PO_VOL,
										IFNULL(SUM(PO_VAL 		- PO_CVAL),0) AS PO_VAL,
										IFNULL(SUM(PO_VOL_R),0) 	AS PO_VOL_R,
										IFNULL(SUM(PO_VAL_R),0) 	AS PO_VAL_R,

										IFNULL(SUM(IR_VOL),0) 	AS IR_VOL,
										IFNULL(SUM(IR_VAL),0) 	AS IR_VAL,
										IFNULL(SUM(IR_VOL_R),0) 	AS IR_VOL_R,
										IFNULL(SUM(IR_VAL_R),0) 	AS IR_VAL_R,

										IFNULL(SUM(UM_VOL),0) 	AS UM_VOL,
										IFNULL(SUM(UM_VAL),0) 	AS UM_VAL,
										IFNULL(SUM(UM_VOL_R),0) 	AS UM_VOL_R,
										IFNULL(SUM(UM_VAL_R),0) 	AS UM_VAL_R,

										IFNULL(SUM(WO_VOL 		- WO_CVOL),0) AS WO_VOL,
										IFNULL(SUM(WO_VAL 		- WO_CVAL),0) AS WO_VAL,
										IFNULL(SUM(WO_VOL_R),0) 	AS WO_VOL_R,
										IFNULL(SUM(WO_VAL_R),0) 	AS WO_VAL_R,

										IFNULL(SUM(OPN_VOL),0) 	AS OPN_VOL,
										IFNULL(SUM(OPN_VAL),0) 	AS OPN_VAL,
										IFNULL(SUM(OPN_VOL_R),0) 	AS OPN_VOL_R,
										IFNULL(SUM(OPN_VAL_R),0) 	AS OPN_VAL_R,

										IFNULL(SUM(VCASH_VOL),0) 		AS VCASH_VOL,
										IFNULL(SUM(VCASH_VAL),0) 		AS VCASH_VAL,
										IFNULL(SUM(VCASH_VOL_R),0) 	AS VCASH_VOL_R,
										IFNULL(SUM(VCASH_VAL_R),0) 	AS VCASH_VAL_R,

										IFNULL(SUM(VLK_VOL),0) 		AS VLK_VOL,
										IFNULL(SUM(VLK_VAL),0) 		AS VLK_VAL,
										IFNULL(SUM(VLK_VOL_R),0) 		AS VLK_VOL_R,
										IFNULL(SUM(VLK_VAL_R),0) 		AS VLK_VAL_R,

										IFNULL(SUM(PPD_VOL),0) 		AS PPD_VOL,
										IFNULL(SUM(PPD_VAL),0) 		AS PPD_VAL,
										IFNULL(SUM(PPD_VOL_R),0) 		AS PPD_VOL_R,
										IFNULL(SUM(PPD_VAL_R),0) 		AS PPD_VAL_R
								FROM tbl_item_logbook_$PRJCODEVW
								WHERE $ADDQRY_01";
				$res_QRY 		= $this->db->query($get_QRY);
				foreach($res_QRY->result() as $rQRY):
					$ADD_VOL 		= $rQRY->ADD_VOL;
					$ADD_VAL 		= $rQRY->ADD_VAL;
					
					$PR_VOL 		= $rQRY->PR_VOL;
					$PR_VAL 		= $rQRY->PR_VAL;
					$PR_VOL_R 		= $rQRY->PR_VOL_R;
					$PR_VAL_R 		= $rQRY->PR_VAL_R;
					
					$PO_VOL 		= $rQRY->PO_VOL;
					$PO_VAL 		= $rQRY->PO_VAL;
					$PO_VOL_R 		= $rQRY->PO_VOL_R;
					$PO_VAL_R 		= $rQRY->PO_VAL_R;
					
					$IR_VOL 		= $rQRY->IR_VOL;
					$IR_VAL 		= $rQRY->IR_VAL;
					$IR_VOL_R 		= $rQRY->IR_VOL_R;
					$IR_VAL_R 		= $rQRY->IR_VAL_R;
					
					$UM_VOL 		= $rQRY->UM_VOL;
					$UM_VAL 		= $rQRY->UM_VAL;
					$UM_VOL_R 		= $rQRY->UM_VOL_R;
					$UM_VAL_R 		= $rQRY->UM_VAL_R;
					
					$WO_VOL 		= $rQRY->WO_VOL;
					$WO_VAL 		= $rQRY->WO_VAL;
					$WO_VOL_R 		= $rQRY->WO_VOL_R;
					$WO_VAL_R 		= $rQRY->WO_VAL_R;
					
					$OPN_VOL 		= $rQRY->OPN_VOL;
					$OPN_VAL 		= $rQRY->OPN_VAL;
					$OPN_VOL_R 		= $rQRY->OPN_VOL_R;
					$OPN_VAL_R 		= $rQRY->OPN_VAL_R;
					
					$VCASH_VOL 		= $rQRY->VCASH_VOL;
					$VCASH_VAL 		= $rQRY->VCASH_VAL;
					$VCASH_VOL_R 	= $rQRY->VCASH_VOL_R;
					$VCASH_VAL_R 	= $rQRY->VCASH_VAL_R;
					
					$VLK_VOL 		= $rQRY->VLK_VOL;
					$VLK_VAL 		= $rQRY->VLK_VAL;
					$VLK_VOL_R 		= $rQRY->VLK_VOL_R;
					$VLK_VAL_R 		= $rQRY->VLK_VAL_R;
					
					$PPD_VOL 		= $rQRY->PPD_VOL;
					$PPD_VAL 		= $rQRY->PPD_VAL;
					$PPD_VOL_R 		= $rQRY->PPD_VOL_R;
					$PPD_VAL_R 		= $rQRY->PPD_VAL_R;
				endforeach;

				// AFTER ADDENDUM
					$RAP_VOL2 		= $ITM_VOLM + $ADD_VOL;
					$RAP_VAL2 		= $ITM_BUDG + $ADD_VAL;

				// REQ TOTAL
					$REQ_VOL 		= $PR_VOL + $WO_VOL + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
					$REQ_VAL 		= $PO_VAL + $WO_VAL + $VCASH_VAL + $VLK_VAL + $PPD_VAL;
					$REQ_VOL_R 		= $PR_VOL_R + $WO_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;
					$REQ_VAL_R 		= $PO_VAL_R + $WO_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;

				// USED TOTAL
					/*$USED_VOL 	= $UM_VOL + $OPN_VOL + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
					$USED_VAL 		= $UM_VAL + $OPN_VAL + $VCASH_VAL + $VLK_VAL + $PPD_VAL;
					$USED_VOL_R		= $UM_VOL_R + $OPN_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;
					$USED_VAL_R		= $UM_VAL_R + $OPN_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;*/

					// SAAT INI REALISASI DISIAPKAN DARI LPM TERLEBIH DAHULU
					$USED_VOL 		= $IR_VOL + $OPN_VOL + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
					$USED_VAL 		= $IR_VAL + $OPN_VAL + $VCASH_VAL + $VLK_VAL + $PPD_VAL;
					$USED_VOL_R		= $IR_VOL_R + $OPN_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;
					$USED_VAL_R		= $IR_VAL_R + $OPN_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;
				
				// SISA BUDG THD REALISASI
					$RAP_REM_VOL 	= $RAP_VOL2 - $REQ_VOL;
					$RAP_REM_VAL 	= $RAP_VAL2 - $REQ_VAL;

				// BUDGET TOTAL
					$RAPT_VOL 		= $RAPT_VOL + $ITM_VOLM;
					$RAPT_VAL 		= $RAPT_VAL + $ITM_BUDG;

				// AMD TOTAL
					$AMDT_VOL 		= $AMDT_VOL + $ADD_VOL;
					$AMDT_VAL 		= $AMDT_VAL + $ADD_VAL;

				// BUDGET TOTAL AFTER AMD
					$RAPT_VOL2 		= $RAPT_VOL2 + $RAP_VOL2;
					$RAPT_VAL2 		= $RAPT_VAL2 + $RAP_VAL2;

				// REQUESTED TOTAL
					$REQT_VOL 		= $REQT_VOL + $REQ_VOL + $REQ_VOL_R;
					$REQT_VAL 		= $REQT_VAL + $REQ_VAL + $REQ_VAL_R;

				// REQUESTED TOTAL
					$REMT_VOL 		= $REMT_VOL + $RAP_REM_VOL;
					$REMT_VAL 		= $REMT_VAL + $RAP_REM_VAL;

				$s_isLS = "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				$r_isLS = $this->db->count_all($s_isLS);

				$vwAMDD		= "$JOBCODEID~$PRJCODE~ADD~$r_isLS";
				$vwREQD		= "$JOBCODEID~$PRJCODE~REQ~$r_isLS";
				$vwUSED		= "$JOBCODEID~$PRJCODE~USE~$r_isLS";
				$secvwAMDD 	= site_url('c_project/c_r3p/shwItm_H15tDETPERJOB/?id='.$this->url_encryption_helper->encode_url($vwAMDD));
				$secvwREQD 	= site_url('c_project/c_r3p/shwItm_H15tDETPERJOB/?id='.$this->url_encryption_helper->encode_url($vwREQD));
				$secvwUSED 	= site_url('c_project/c_r3p/shwItm_H15tDETPERJOB/?id='.$this->url_encryption_helper->encode_url($vwUSED));

				$alrtStyl1 		= "";
				$alrtStyl2 		= "";
				if(round($RAP_REM_VOL, 2) < 0 && $r_isLS == 0)
				{
					$alrtStyl1 	= "background-color: gray;";
				}

				if(round($RAP_REM_VAL, 2) < 0)
				{
					$alrtStyl2 	= "background-color: gray;";
				}

				$output['data'][] 	= array($JobView,
										$ITM_UNIT,
										$ISLASTH,
										$r_isLS,
										$IS_LEVEL,
										$ISLAST,
										$secvwAMDD,
										$secvwREQD,
										$secvwUSED,
										$ITM_VOLM,
										$ITM_BUDG,
										$ADD_VOL,
										$ADD_VAL,
										$RAP_VOL2,
										$RAP_VAL2,
										$REQ_VOL,
										$REQ_VAL,
										$RAP_REM_VOL,
										$RAP_REM_VAL);
				
			endforeach;

			echo json_encode($output);
		}
	}
	
    function shwItm_H15t() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		$ITM_CODE	= $data1[0];
		$PRJCODE	= $data1[1];
		$DATES		= $data1[2];
		$DATEE		= $data1[3];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h1_title'] 		= 'Profit and Loss Report Detail';
			$data['h2_title'] 		= 'Profit and Loss Report';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Laporan Detil Transaksi Item';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Item Detail Transaction Report';
			}
			
			$TOTPROJ				= 1;
		
			$data['PRJCODE'] 		= $PRJCODE;
			$data['JOBPARENT'][]	= 1;
			$data['ITM_CODE'] 		= $ITM_CODE;
			$data['DATES'] 			= $DATES;
			$data['DATEE'] 			= $DATEE;
			$data['viewType'] 		= 0;
			
			$this->load->view('v_project/v_report/v_itembudet_sum/v_itembudet_detil_hist', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function shwItm_H15tDET() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		$ITM_CODE	= $data1[0];
		$PRJCODE	= $data1[1];
		$DOCCATEG	= $data1[2];
		$r_isLS 	= $data1[3];
		$Start_Date	= $data1[4];
		$End_Date 	= $data1[5];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Laporan Detil Transaksi';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Transaction Detail Report';
			}
			
			$TOTPROJ				= 1;
		
			$data['PRJCODE'] 		= $PRJCODE;
			$data['DOC_CATEG'] 		= $DOCCATEG;
			$data['ITM_CODE'] 		= $ITM_CODE;
			$data['ISLS'] 			= $r_isLS;
			$data['Start_Date'] 	= $Start_Date;
			$data['End_Date'] 		= $End_Date;
			$data['viewType'] 		= 0;
			
			$this->load->view('v_project/v_report/v_stock_opname/v_itembudet_detil_hist_itm', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function shwItm_H15tDETPERJOB() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		$JOBCODEID	= $data1[0];
		$PRJCODE	= $data1[1];
		$DOCCATEG	= $data1[2];
		$r_isLS 	= $data1[3];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Lap. Detil Transaksi Per Pekerjaan';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Transaction Detail Report Per Job';
			}
			
			$TOTPROJ				= 1;
		
			$data['PRJCODE'] 		= $PRJCODE;
			$data['DOCCATEG'] 		= $DOCCATEG;
			$data['JOBCODEID'] 		= $JOBCODEID;
			$data['ISLS'] 			= $r_isLS;
			$data['viewType'] 		= 0;
			
			$this->load->view('v_project/v_report/v_stock_opname/v_itembudet_detil_hist_itmpj', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
}