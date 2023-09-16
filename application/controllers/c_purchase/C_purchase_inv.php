<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 November 2017
 * File Name	= C_purchase_inv.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_purchase_inv extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_purchase/c_purchase_inv/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function index1() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN009';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_purchase_inv/get_all_pinv/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function get_all_pinv() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Purchase Invoice';
			$data['h3_title']	= 'Purchase';
			$data['PRJCODE']	= $PRJCODE;
			$data["MenuCode"] 	= 'MN009';
			$data['backURL'] 	= site_url('c_purchase/c_purchase_inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$num_rows 			= $this->m_purchase_inv->count_all_pinv($PRJCODE);
			$data["reccount"]	= $num_rows;	 
			$data['viewpinv']	= $this->m_purchase_inv->get_all_pinv($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN009';
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
			
			$this->load->view('v_purchase/v_purchase_inv/v_pinv_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_purchase/c_purchase_inv/add_process');
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_purchase_inv->count_all_vend($PRJCODE);
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_all_vend($PRJCODE)->result();
			
			$MenuCode 				= 'MN009';
			$data["MenuCode"] 		= 'MN009';
			$data['viewDocPattern'] = $this->m_purchase_inv->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN009';
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
	
			$this->load->view('v_purchase/v_purchase_inv/v_pinv_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function addDir() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_purchase/c_purchase_inv/add_process');
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_purchase_inv->count_all_vendDir($PRJCODE);
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_all_vendDir($PRJCODE)->result();
			
			$MenuCode 				= 'MN009';
			$data["MenuCode"] 		= 'MN009';
			$data['viewDocPattern'] = $this->m_purchase_inv->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN009';
				$TTR_CATEG		= 'A-DIR';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
	
			$this->load->view('v_purchase/v_purchase_inv/v_pinvDir_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			
			// START : CHECK THE CODE
				$INV_NUM 		= $this->input->post('INV_NUM');
				$INV_CODE 		= $this->input->post('INV_CODE');
				$INV_DATE		= date('Y-m-d',strtotime($this->input->post('INV_DATE')));
				$PRJCODE 		= $this->input->post('PRJCODE');
				$Patt_Year		= date('Y',strtotime($this->input->post('INV_DATE')));
				$Patt_Number	= $this->input->post('Patt_Number');
				
				$DOC_NUM		= $INV_NUM;
				$DOC_CODE		= $INV_CODE;
				$DOC_DATE		= $INV_DATE;
				$MenuCode 		= 'MN009';
				$TABLE_NAME		= 'tbl_pinv_header';
				$FIELD_NAME		= 'INV_NUM';
				
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$paramCODE 	= array('DOC_NUM' 		=> $DOC_NUM,
									'PRJCODE' 		=> $PRJCODE,
									'TABLE_NAME' 	=> $TABLE_NAME,
									'FIELD_NAME' 	=> $FIELD_NAME);
				$countCode	= $this->m_updash->count_CODE($paramCODE);
				
				if($countCode > 0)
				{
					$getSetting	= $this->m_updash->getDataDocPat($MenuCode)->row();				
					$PattCode	= $getSetting->Pattern_Code;
					$PattLength	= $getSetting->Pattern_Length;
					$useYear	= $getSetting->useYear;
					$useMonth	= $getSetting->useMonth;
					$useDate	= $getSetting->useDate;
					
					$SettCode	= array('PRJCODE'		=> $PRJCODE,
										'DOC_DATE'		=> $DOC_DATE,
										'TABLE_NAME' 	=> $TABLE_NAME,
										'PattCode' 		=> $PattCode,
										'PattLength' 	=> $PattLength,
										'useYear'		=> $useYear,
										'useMonth'		=> $useMonth,
										'useDate'		=> $useDate);
					$getNewCode	= $this->m_updash->get_NewCode($SettCode);
					$splitCode 	= explode("~", $getNewCode);
					$DOC_NUM	= $splitCode[0];
					if($DOC_CODE == '')
					{
						$DOC_CODE	= $splitCode[1];
					}
					$Patt_Number= $splitCode[2];
				}
				
				$INV_NUM		= $DOC_NUM;
				$INV_CODE		= $DOC_CODE;
			// END : CHECK CODE
			
			$INV_DUEDATE	= date('Y-m-d',strtotime($this->input->post('INV_DUEDATE')));
			$INV_CODE		= $this->input->post('INV_CODE');
			$INV_TYPE		= $this->input->post('INV_TYPE');
			$PO_NUM			= $this->input->post('PO_NUM');
			$IR_NUM			= $this->input->post('Ref_Number');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$INV_CURRENCY	= 'IDR';
			$INV_TAXCURR	= 'IDR';
			$INV_AMOUNT		= $this->input->post('INV_AMOUNT');
			$INV_TERM		= $this->input->post('INV_TERM');
			$INV_STAT 		= $this->input->post('INV_STAT');
			$INV_PAYSTAT	= 'NP';
			$COMPANY_ID		= $COMPANY_ID;
			$VENDINV_NUM	= $this->input->post('VENDINV_NUM');
			$INV_NOTES		= $this->input->post('INV_NOTES');
			$CREATED		= date('Y-m-d H:i:s');
			$CREATER		= $DefEmp_ID;
			
			// Check setting WH_ID
			$sqlGS		= "SELECT whIsPrjCode FROM tglobalsetting WHERE ID = 1";
			$ressqlGS 	= $this->db->query($sqlGS)->result();			
			foreach($ressqlGS as $rowGS) :
				$whIsPrjCode 	= $rowGS->whIsPrjCode;
			endforeach;
			
			if($whIsPrjCode == 1)
			{
				$wh_id = $this->input->post('PRJCODE');
			}
			else
			{
				// HARUS ADA SETTINGAN SUSULAN
				$wh_id = $this->input->post('PRJCODE');
			}
			
			$s = strtotime($INV_DATE);
			$e = strtotime($INV_DUEDATE);
			
			$INV_TERM 	= ($e - $s)/ (24 * 3600);
			
			$insertINV 	= array('INV_NUM' 			=> $INV_NUM,
								'INV_CODE'			=> $INV_CODE,
								'INV_TYPE'			=> $INV_TYPE,
								'PO_NUM'			=> $PO_NUM,
								'IR_NUM'			=> $IR_NUM,
								'PRJCODE'			=> $PRJCODE,
								'INV_DATE'			=> $INV_DATE,
								'INV_DUEDATE'		=> $INV_DUEDATE,
								'SPLCODE'			=> $SPLCODE,
								'INV_CURRENCY'		=> $INV_CURRENCY,
								'INV_TAXCURR'		=> $INV_TAXCURR,
								'INV_AMOUNT'		=> $INV_AMOUNT,
								'INV_AMOUNT_BASE'	=> $INV_AMOUNT,
								'INV_TERM'			=> $INV_TERM,
								'INV_STAT'			=> $INV_STAT,
								'INV_PAYSTAT'		=> $INV_PAYSTAT,
								'COMPANY_ID'		=> $VENDINV_NUM,
								'VENDINV_NUM'		=> $VENDINV_NUM,
								'INV_NOTES'			=> $INV_NOTES,
								'CREATED'			=> $CREATED,
								'CREATER'			=> $CREATER,
								'Patt_Number'		=> $Patt_Number,
								'Patt_Year'			=> $Patt_Year);							
			$this->m_purchase_inv->add($insertINV);			
			
			// Pembuatan Journal Header
			if($INV_STAT == 2 || $INV_STAT == 3)
			{
				// START : JOURNAL HEADER
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $INV_NUM;
					$JournalType	= 'PINV';
					$JournalH_Date	= $INV_DATE;
					$Company_ID		= 'NKE';
					$DOCSource		= $IR_NUM;
					$LastUpdate		= $CREATED;
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= $PO_NUM;
					$RefType		= 'IR';
					$PRJCODE		= $PRJCODE;
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $DOCSource,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE);
					$this->m_journal->createJournalH($JournalH_Code, $parameters);
				// END : JOURNAL HEADER
			}
				
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_pinv_detail',$d);
				
				// Pembuatan Journal Detail antara Invoice dengan lawannya
				if($INV_STAT == 2 || $INV_STAT == 3)
				{
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $INV_NUM;
					$JournalType	= 'PINV';
					$JournalH_Date	= $INV_DATE;
					$Company_ID		= 'NKE';
					$Currency_ID	= 'IDR';
					$DOCSource		= $IR_NUM;
					$LastUpdate		= $CREATED;
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= $PO_NUM;
					$RefType		= 'IR';
					$JSource		= 'PINV';
					$PRJCODE		= $PRJCODE;
					
					$ITM_CODE 		= $d['ITM_CODE'];
					$ACC_ID 		= $d['ACC_ID'];
					$ITM_UNIT 		= $d['ITM_UNIT'];
					$ITM_QTY 		= $d['ITM_QTY'];
					$ITM_PRICE 		= $d['ITM_UNITP'];
					$ITM_DISC 		= $d['ITM_DISC'];
					$TAXCODE1 		= $d['TAXCODE1'];
					$TAXPRICE1		= $d['TAX_AMOUNT_PPn1'];
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $Company_ID,
										'Currency_ID' 		=> $Currency_ID,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE,
										'JSource'			=> $JSource,
										'TRANS_CATEG' 		=> 'PINV',			// PINV = PURCHASE INVOICE
										'ITM_CODE' 			=> $ITM_CODE,
										'ACC_ID' 			=> $ACC_ID,
										'ITM_UNIT' 			=> $ITM_UNIT,
										'ITM_QTY' 			=> $ITM_QTY,
										'ITM_PRICE' 		=> $ITM_PRICE,
										'ITM_DISC' 			=> $ITM_DISC,
										'TAXCODE1' 			=> $TAXCODE1,
										'TAXPRICE1' 		=> $TAXPRICE1);												
					$this->m_journal->createJournalD($JournalH_Code, $parameters);
				}
			}
			
			// Update status PO dan RR bahwa sudah dibuatkan invoice
			if($INV_STAT == 2 || $INV_STAT == 3)
			{
				$parameters = array(
					'INV_STAT' 	=> $INV_STAT,
					'PO_NUM' 	=> $PO_NUM,
					'IR_NUM' 	=> $IR_NUM,
					'PRJCODE' 	=> $PRJCODE
				);
				$this->m_purchase_inv->updatePO_RR($INV_NUM, $parameters);
				
				// START : TRACK FINANCIAL TRACK
					$this->load->model('m_updash/m_updash', '', TRUE);
					$paramFT = array('DOC_NUM' 		=> $INV_NUM,
									'DOC_DATE' 		=> $INV_DATE,
									'DOC_EDATE' 	=> $INV_DUEDATE,
									'PRJCODE' 		=> $PRJCODE,
									'FIELD_NAME1' 	=> 'FT_AP',
									'FIELD_NAME2' 	=> 'FM_AP',
									'TOT_AMOUNT'	=> $INV_AMOUNT);
					$this->m_updash->finTrack($paramFT);
				// END : TRACK FINANCIAL TRACK
			}
			return false;
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');			// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $INV_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,			// PROJECT
										'TR_TYPE'		=> "PINV",				// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_pinv_header",	// TABLE NAME
										'KEY_NAME'		=> "INV_NUM",			// KEY OF THE TABLE
										'STAT_NAME' 	=> "INV_STAT",			// NAMA FIELD STATUS
										'STATDOC' 		=> $INV_STAT,			// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,		// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PINV",			// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_PINV_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_PINV_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_PINV_HP",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_PINV_FP",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_PINV_R",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_PINV_CL");		// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $INV_NUM;
				$MenuCode 		= 'MN009';
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
			
			$url			= site_url('c_purchase/c_purchase_inv/get_all_pinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
		
	function popupall_IR() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$collID			= $_GET['id'];
		$collID			= $this->url_encryption_helper->decode_url($collID);
		$splitCode 		= explode("~", $collID);
		$PRJCODE		= $splitCode[0];
		$SPLCODE		= $splitCode[1];
		
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Select Receipt / LPM';
		$data['txtRefference'] 	= '';
		$data['resultCount']	= 0;
		$data['pageFrom']		= 'IR';
		$data['SPLCODE']		= $SPLCODE;
		$data['PRJCODE']		= $PRJCODE;
		
		$data['countAllIR'] 	= $this->m_purchase_inv->count_all_IR($SPLCODE, $PRJCODE);
		$data['viewAllIR'] 		= $this->m_purchase_inv->viewAllIR($SPLCODE, $PRJCODE)->result();
				
		$this->load->view('v_purchase/v_purchase_inv/v_pinv_sel_ir', $data);
	}
	
	function update() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			$splitCode 	= explode("~", $CollID);
			$INV_NUM	= $splitCode[0];
			$ISDIRECT	= $splitCode[1];
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_purchase_inv/update_process');
			
			$MenuCode 			= 'MN009';
			$data["MenuCode"] 	= 'MN009';
			
			$getINV				= $this->m_purchase_inv->get_INV_by_number($INV_NUM)->row();
			$data['default']['INV_NUM'] 	= $getINV->INV_NUM;
			$data['default']['INV_CODE'] 	= $getINV->INV_CODE;
			$data['default']['INV_TYPE'] 	= $getINV->INV_TYPE;
			$data['default']['PO_NUM'] 		= $getINV->PO_NUM;
			$data['default']['IR_NUM'] 		= $getINV->IR_NUM;
			$data['default']['PRJCODE'] 	= $getINV->PRJCODE;
			$PRJCODE						= $getINV->PRJCODE;
			$data['default']['INV_DATE'] 	= $getINV->INV_DATE;
			$data['default']['INV_DUEDATE'] = $getINV->INV_DUEDATE;
			$data['default']['SPLCODE'] 	= $getINV->SPLCODE;
			$SPLCODE						= $getINV->SPLCODE;
			$data['default']['INV_CURRENCY']= $getINV->INV_CURRENCY;
			$data['default']['INV_TAXCURR'] = $getINV->INV_TAXCURR;
			$data['default']['INV_AMOUNT'] 	= $getINV->INV_AMOUNT;
			$data['default']['INV_AMOUNT_BASE'] = $getINV->INV_AMOUNT_BASE;
			$data['default']['INV_TERM'] 	= $getINV->INV_TERM;
			$data['default']['INV_STAT'] 	= $getINV->INV_STAT;
			$data['default']['INV_PAYSTAT'] = $getINV->INV_PAYSTAT;
			$data['default']['COMPANY_ID'] 	= $getINV->COMPANY_ID;
			$data['default']['VENDINV_NUM'] = $getINV->VENDINV_NUM;
			$data['default']['INV_NOTES'] 	= $getINV->INV_NOTES;
			$data['default']['Patt_Number'] = $getINV->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getINV->INV_NUM;
				$MenuCode 		= 'MN009';
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
			
			if($ISDIRECT == 0)
			{
				$data['countSUPL'] 	= $this->m_purchase_inv->count_all_vendUP($SPLCODE);
				$data['vwSUPL'] 	= $this->m_purchase_inv->view_all_vendUP($SPLCODE)->result();
				$this->load->view('v_purchase/v_purchase_inv/v_pinv_form', $data);	
			}
			else
			{
				$data['countSUPL'] 	= $this->m_purchase_inv->count_all_vendDir($PRJCODE);
				$data['vwSUPL'] 	= $this->m_purchase_inv->view_all_vendDir($PRJCODE)->result();
				$this->load->view('v_purchase/v_purchase_inv/v_pinvDir_form', $data);
			}
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			
			$INV_NUM 		= $this->input->post('INV_NUM');
			$INV_CODE 		= $this->input->post('INV_CODE');
			echo "$INV_NUM";
			//return false;
			$INV_DATE		= date('Y-m-d',strtotime($this->input->post('INV_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');			
			$INV_DUEDATE	= date('Y-m-d',strtotime($this->input->post('INV_DUEDATE')));
			$INV_CODE		= $this->input->post('INV_CODE');
			$INV_TYPE		= $this->input->post('INV_TYPE');
			$PO_NUM			= $this->input->post('PO_NUM');
			$IR_NUM			= $this->input->post('Ref_Number');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$INV_CURRENCY	= 'IDR';
			$INV_TAXCURR	= 'IDR';
			$INV_AMOUNT		= $this->input->post('INV_AMOUNT');
			$INV_TERM		= $this->input->post('INV_TERM');
			$INV_STAT 		= $this->input->post('INV_STAT');
			$INV_PAYSTAT	= 'NP';
			$COMPANY_ID		= $COMPANY_ID;
			$VENDINV_NUM	= $this->input->post('VENDINV_NUM');
			$INV_NOTES		= $this->input->post('INV_NOTES');
			$CREATED		= date('Y-m-d H:i:s');
			$CREATER		= $DefEmp_ID;
			$Patt_Year		= date('Y',strtotime($this->input->post('INV_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			// Check setting WH_ID
			$sqlGS		= "SELECT whIsPrjCode FROM tglobalsetting WHERE ID = 1";
			$ressqlGS 	= $this->db->query($sqlGS)->result();			
			foreach($ressqlGS as $rowGS) :
				$whIsPrjCode 	= $rowGS->whIsPrjCode;
			endforeach;
			
			if($whIsPrjCode == 1)
			{
				$wh_id = $this->input->post('PRJCODE');
			}
			else
			{
				// HARUS ADA SETTINGAN SUSULAN
				$wh_id = $this->input->post('PRJCODE');
			}
			
			$s = strtotime($INV_DATE);
			$e = strtotime($INV_DUEDATE);
			
			$INV_TERM 	= ($e - $s)/ (24 * 3600);
			
			$updINV 	= array('INV_NUM' 			=> $INV_NUM,
								'INV_CODE'			=> $INV_CODE,
								'INV_TYPE'			=> $INV_TYPE,
								'PO_NUM'			=> $PO_NUM,
								'IR_NUM'			=> $IR_NUM,
								'PRJCODE'			=> $PRJCODE,
								'INV_DATE'			=> $INV_DATE,
								'INV_DUEDATE'		=> $INV_DUEDATE,
								'SPLCODE'			=> $SPLCODE,
								'INV_CURRENCY'		=> $INV_CURRENCY,
								'INV_TAXCURR'		=> $INV_TAXCURR,
								'INV_AMOUNT'		=> $INV_AMOUNT,
								'INV_AMOUNT_BASE'	=> $INV_AMOUNT,
								'INV_TERM'			=> $INV_TERM,
								'INV_STAT'			=> $INV_STAT,
								'INV_PAYSTAT'		=> $INV_PAYSTAT,
								'COMPANY_ID'		=> $VENDINV_NUM,
								'VENDINV_NUM'		=> $VENDINV_NUM,
								'INV_NOTES'			=> $INV_NOTES,
								'CREATED'			=> $CREATED,
								'CREATER'			=> $CREATER,
								'Patt_Number'		=> $Patt_Number,
								'Patt_Year'			=> $Patt_Year);
			//$this->m_purchase_inv->updateINV($INV_NUM, $updINV);
			
			//$this->m_purchase_inv->deleteINVDet($INV_NUM);	
			
			foreach($_POST['data'] as $d)
			{
				//$this->db->insert('tbl_pinv_detail',$d);
			}		
			
			// Pembuatan Journal Header
			if($INV_STAT == 2 || $INV_STAT == 3)
			{
				// START : JOURNAL HEADER
					/*$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $INV_NUM;
					$JournalType	= 'PINV';
					$JournalH_Date	= $INV_DATE;
					$Company_ID		= 'NKE';
					$DOCSource		= $IR_NUM;
					$LastUpdate		= $CREATED;
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= $PO_NUM;
					$RefType		= 'IR';
					$PRJCODE		= $PRJCODE;
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $DOCSource,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE);
					$this->m_journal->createJournalH($JournalH_Code, $parameters);*/
				// END : JOURNAL HEADER
			}
			if($INV_STAT == 2 || $INV_STAT == 3)
			{
				// START : JOURNAL HEADER
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $INV_NUM;
					$JournalType	= 'PINV';
					$JournalH_Date	= $INV_DATE;
					$Company_ID		= 'NKE';
					$DOCSource		= $IR_NUM;
					$LastUpdate		= $CREATED;
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= $PO_NUM;
					$RefType		= 'IR';
					$PRJCODE		= $PRJCODE;
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $DOCSource,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE);
					$this->m_journal->createJournalH($JournalH_Code, $parameters);
				// END : JOURNAL HEADER
			}
				
			foreach($_POST['data'] as $d)
			{				
				// Pembuatan Journal Detail antara Invoice dengan lawannya
				if($INV_STAT == 2 || $INV_STAT == 3)
				{
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $INV_NUM;
					$JournalType	= 'PINV';
					$JournalH_Date	= $INV_DATE;
					$Company_ID		= 'NKE';
					$Currency_ID	= 'IDR';
					$DOCSource		= $IR_NUM;
					$LastUpdate		= $CREATED;
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= $PO_NUM;
					$RefType		= 'IR';
					$JSource		= 'PINV';
					$PRJCODE		= $PRJCODE;
					
					$ITM_CODE 		= $d['ITM_CODE'];
					$ACC_ID 		= $d['ACC_ID'];
					$ITM_UNIT 		= $d['ITM_UNIT'];
					$ITM_QTY 		= $d['ITM_QTY'];
					$ITM_PRICE 		= $d['ITM_UNITP'];
					$ITM_DISC 		= $d['ITM_DISC'];
					$TAXCODE1 		= $d['TAXCODE1'];
					$TAXPRICE1		= $d['TAX_AMOUNT_PPn1'];
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $Company_ID,
										'Currency_ID' 		=> $Currency_ID,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE,
										'JSource'			=> $JSource,
										'TRANS_CATEG' 		=> 'PINV',			// PINV = PURCHASE INVOICE
										'ITM_CODE' 			=> $ITM_CODE,
										'ACC_ID' 			=> $ACC_ID,
										'ITM_UNIT' 			=> $ITM_UNIT,
										'ITM_QTY' 			=> $ITM_QTY,
										'ITM_PRICE' 		=> $ITM_PRICE,
										'ITM_DISC' 			=> $ITM_DISC,
										'TAXCODE1' 			=> $TAXCODE1,
										'TAXPRICE1' 		=> $TAXPRICE1);												
					$this->m_journal->createJournalD($JournalH_Code, $parameters);
				}
			}
			
			// Update status PO dan RR bahwa sudah dibuatkan invoice
			if($INV_STAT == 2 || $INV_STAT == 3)
			{
				$parameters = array(
					'INV_STAT' 	=> $INV_STAT,
					'PO_NUM' 	=> $PO_NUM,
					'IR_NUM' 	=> $IR_NUM,
					'PRJCODE' 	=> $PRJCODE
				);
				$this->m_purchase_inv->updatePO_RR($INV_NUM, $parameters);
				
				// START : TRACK FINANCIAL TRACK
					$this->load->model('m_updash/m_updash', '', TRUE);
					$paramFT = array('DOC_NUM' 		=> $INV_NUM,
									'DOC_DATE' 		=> $INV_DATE,
									'DOC_EDATE' 	=> $INV_DUEDATE,
									'PRJCODE' 		=> $PRJCODE,
									'FIELD_NAME1' 	=> 'FT_AP',
									'FIELD_NAME2' 	=> 'FM_AP',
									'TOT_AMOUNT'	=> $INV_AMOUNT);
					$this->m_updash->finTrack($paramFT);
				// END : TRACK FINANCIAL TRACK
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');			// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $INV_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,			// PROJECT
										'TR_TYPE'		=> "PINV",				// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_pinv_header",	// TABLE NAME
										'KEY_NAME'		=> "INV_NUM",			// KEY OF THE TABLE
										'STAT_NAME' 	=> "INV_STAT",			// NAMA FIELD STATUS
										'STATDOC' 		=> $INV_STAT,			// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,		// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PINV",			// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_PINV_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_PINV_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_PINV_HP",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_PINV_FP",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_PINV_R",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_PINV_CL");		// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $INV_NUM;
				$MenuCode 		= 'MN009';
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
			
			$url			= site_url('c_purchase/c_purchase_inv/get_all_pinv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$INV_NUM	= $_GET['id'];
		$INV_NUM	= $this->url_encryption_helper->decode_url($INV_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getINV			= $this->m_purchase_inv->get_INV_by_number($INV_NUM)->row();
			
			$data['default']['INV_NUM'] 	= $getINV->INV_NUM;
			$data['default']['INV_CODE'] 	= $getINV->INV_CODE;
			$data['default']['INV_TYPE'] 	= $getINV->INV_TYPE;
			$data['default']['PO_NUM'] 		= $getINV->PO_NUM;
			$data['default']['IR_NUM'] 		= $getINV->IR_NUM;
			$data['default']['PRJCODE'] 	= $getINV->PRJCODE;
			$PRJCODE						= $getINV->PRJCODE;
			$data['default']['INV_DATE'] 	= $getINV->INV_DATE;
			$data['default']['INV_DUEDATE'] = $getINV->INV_DUEDATE;
			$data['default']['SPLCODE'] 	= $getINV->SPLCODE;
			$SPLCODE						= $getINV->SPLCODE;
			$data['default']['INV_CURRENCY']= $getINV->INV_CURRENCY;
			$data['default']['INV_TAXCURR'] = $getINV->INV_TAXCURR;
			$data['default']['INV_AMOUNT'] 	= $getINV->INV_AMOUNT;
			$data['default']['INV_AMOUNT_BASE'] = $getINV->INV_AMOUNT_BASE;
			$data['default']['INV_TERM'] 	= $getINV->INV_TERM;
			$data['default']['INV_STAT'] 	= $getINV->INV_STAT;
			$data['default']['INV_PAYSTAT'] = $getINV->INV_PAYSTAT;
			$data['default']['COMPANY_ID'] 	= $getINV->COMPANY_ID;
			$data['default']['VENDINV_NUM'] = $getINV->VENDINV_NUM;
			$data['default']['INV_NOTES'] 	= $getINV->INV_NOTES;
			
			$this->load->view('v_purchase/v_purchase_inv/print_inv', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}