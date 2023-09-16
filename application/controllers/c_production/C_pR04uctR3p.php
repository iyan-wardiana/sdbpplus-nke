<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Agustus 2020
 * File Name	= C_pR04uctR3p.php
 * Location		= -
*/

class C_pR04uctR3p extends CI_Controller  
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
		
		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
			if(isset($_GET['id']))
			{
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
			}
			else
			{
				$EXP_COLLD1		= '';
			}

			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
 	function r3p0m5etr3p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_pR04uctR3p/r_r3p0m5etr3px/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_r3p0m5etr3px() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$appName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($appName);
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			$data['title'] 			= $appName;
				
			// START : GET MENU NAME
				$mnCode				= 'MN383';
				$data["MenuApp"] 	= 'MN383';
				$data["MenuCode"] 	= 'MN383';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_production/c_pR04uctR3p/r_vwr3p0m5etr3px/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_production/v_report/r_omsetharian/r_omsetharian', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vwr3p0m5etr3px() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'Laporan Omset Harian';
			}
			else
			{
				$data['h1_title'] 	= 'Daily Turnover Report';
			}

			$PRJCODE		= $this->input->post('PRJCODE');
			$COLREFPRJ 		= '';
			if($PRJCODE != '')
			{
				$refStep	= 0;
				
				foreach ($PRJCODE as $REFPRJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFPRJ	= "'$REFPRJ'";
					}
					else
					{
						$COLREFPRJ	= "$COLREFPRJ,'$REFPRJ'";
					}
				}
			}

			$data['COLREFPRJ'] 		= $COLREFPRJ;
			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('End_Date'))));
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$this->load->view('v_production/v_report/r_omsetharian/r_omsetharian_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function r3pr0duc710n() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_pR04uctR3p/r3pr0duc710nX/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r3pr0duc710nX() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$appName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($appName);
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			$data['title'] 			= $appName;
				
			// START : GET MENU NAME
				$mnCode				= 'MN290';
				$data["MenuApp"] 	= 'MN290';
				$data["MenuCode"] 	= 'MN290';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_production/c_pR04uctR3p/r_vwr3pr0duc710nX/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_production/v_report/r_prodharian/r_prodharian', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vwr3pr0duc710nX() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			$data['PRJCODE'] 		= $this->input->post('PRJCODE');
			$data['CATEG'] 			= $this->input->post('CATEG');
			$CATEG					= $this->input->post('CATEG');
			$data['MONTH'] 			= $this->input->post('MONTH');
			$data['YEAR'] 			= $this->input->post('YEAR');
			$PRJCODE1				= $this->input->post('PRJCODE');
			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('End_Date'))));
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			if($CATEG == 1)
			{
				if($LangID == 'IND')
				{
					$data['h1_title'] 	= 'LAPORAN PRODUKSI HARIAN';
				}
				else
				{
					$data['h1_title'] 	= 'DAILY PRODUCTION REPORT';
				}
				$this->load->view('v_production/v_report/r_prodharian/r_prodharian_report', $data);
			}
			elseif($CATEG == 2)
			{
				if($LangID == 'IND')
				{
					$data['h1_title'] 	= 'LAPORAN HARIAN REPROSES';
				}
				else
				{
					$data['h1_title'] 	= 'DAILY REPROCESS REPORT';
				}
				$this->load->view('v_production/v_report/r_prodharian/r_prodharian_report_reproses', $data);
			}
			elseif($CATEG == 3)
			{
				if($LangID == 'IND')
				{
					$data['h1_title'] 	= 'LAPORAN HARIAN RETUR';
				}
				else
				{
					$data['h1_title'] 	= 'DAILY RETUR REPORT';
				}
				$this->load->view('v_production/v_report/r_prodharian/r_prodharian_report_returso', $data);
			}
			elseif($CATEG == 4)
			{
				if($LangID == 'IND')
				{
					$data['h1_title'] 	= 'LAPORAN HARIAN SALDO FINISHED GOODS';
				}
				else
				{
					$data['h1_title'] 	= 'DAILY FINISHED GOODS REPORT';
				}
				$this->load->view('v_production/v_report/r_prodharian/r_prodharian_report_fg', $data);
			}
			elseif($CATEG == 5)
			{
				if($LangID == 'IND')
				{
					$data['h1_title'] 	= 'LAPORAN HARIAN SALDO GREIGE';
				}
				else
				{
					$data['h1_title'] 	= 'DAILY GREIGE REPORT';
				}
				$this->load->view('v_production/v_report/r_prodharian/r_prodharian_report_greige', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function r3pr0ducf9() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_pR04uctR3p/r3pr0ducf9X/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r3pr0ducf9X() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$appName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($appName);
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			$data['title'] 			= $appName;
				
			// START : GET MENU NAME
				$mnCode				= 'MN246';
				$data["MenuApp"] 	= 'MN246';
				$data["MenuCode"] 	= 'MN246';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_production/c_pR04uctR3p/r_vwr3pr0ducf9X/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_production/v_report/v_fgreceipt/v_fgreceipt', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vwr3pr0ducf9X() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			$data['PRJCODE'] 		= $this->input->post('PRJCODE');
			$data['CATEG'] 			= $this->input->post('CATEG');
			$CATEG					= $this->input->post('CATEG');
			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'LAPORAN PRODUKSI';
			}
			else
			{
				$data['h1_title'] 	= 'PRODUCTION REPORT';
			}
			$this->load->view('v_production/v_report/v_fgreceipt/v_fgreceipt_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}