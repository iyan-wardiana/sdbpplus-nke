<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Desember 2017
 * File Name	= C_item_usage.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_item_usage  extends CI_Controller
{
 	function index() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_item_usage/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function projectlist() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_inventory/c_item_usage/get_last_ten_UM/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_UM() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;			
			$data['backURL'] 		= site_url('c_inventory/c_item_usage/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
			$num_rows 				= $this->m_itemusage->count_all_UM($PRJCODE);
			
			$data["recordcount"] 	= $num_rows;
			$data["PRJCODE"] 		= $PRJCODE;
	 		$data["MenuCode"] 		= 'MN189';
			
			$data['viewIR'] 		= $this->m_itemusage->get_last_ten_UM($PRJCODE)->result();
			
			$this->load->view('v_inventory/v_itemusage/itemusage', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_inventory/c_item_usage/add_process');
			
			$data['backURL'] 		= site_url('c_inventory/c_item_usage/get_last_ten_UM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data["MenuCode"] 		= 'MN189';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL']		= $this->m_itemusage->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemusage->viewvendor()->result();
			
			$MenuCode 				= 'MN189';
			$data['vwDocPatt']		= $this->m_itemusage->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_inventory/v_itemusage/itemusage_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
					
			$IR_NUM			= $this->input->post('IR_NUM');			
			$IR_CODE		= $this->input->post('IR_CODE');
			$IR_DATE		= date('Y-m-d',strtotime($this->input->post('IR_DATE')));
			$TERM_PAY		= $this->input->post('TERM_PAY');
			$IR_DUEDATE1	= strtotime ("+$TERM_PAY day", strtotime ($IR_DATE));
			$IR_DUEDATE		= date('Y-m-d', $IR_DUEDATE1);
			$Patt_Date		= date('d',strtotime($this->input->post('IR_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('IR_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('IR_DATE')));
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			
			// START : CHECK THE CODE	
				$DOC_NUM		= $IR_NUM;
				$DOC_CODE		= $IR_CODE;
				$DOC_DATE		= $IR_DATE;
				$MenuCode 		= 'MN189';
				$TABLE_NAME		= 'tbl_UM_header';
				$FIELD_NAME		= 'IR_NUM';
				
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
				
				$IR_NUM			= $DOC_NUM;
				$IR_CODE		= $DOC_CODE;
			// END : CHECK CODE
				
			if($IR_SOURCE == 1)		// Direct
			{
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$PR_NUM			= '';
				$PR_CODE		= '';
			}
			elseif($IR_SOURCE == 2)	// MR
			{
				$Ref_Number 	= $this->input->post('Ref_NumberMR');
				$PO_NUM 		= $this->input->post('Ref_NumberMR');
				$PR_NUM			= $this->input->post('Ref_NumberMR');
				$PR_CODE		= $this->input->post('Ref_NumberMR');
			}
			elseif($IR_SOURCE == 3)	// PO
			{
				$Ref_Number 	= $this->input->post('Ref_NumberPO');
				$PR_NUM			= '';
				$PR_CODE		= '';
				$getRefMRfPO	= "SELECT PR_NUM, PR_CODE
									FROM tbl_po_header
									WHERE PO_NUM = '$Ref_Number'
									AND PRJCODE = '$PRJCODE'";
				$resgetMRfPO 	= $this->db->query($getRefMRfPO)->result();
				foreach($resgetMRfPO as $rowPO) :
					$PR_NUM 	= $rowPO->PR_NUM;
					$PR_CODE 	= $rowPO->PR_CODE;
				endforeach;
				$PO_NUM			= $Ref_Number;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PR_CODE;
			}
			$IR_REFER		= $this->input->post('IR_REFER');
			$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
			$IR_STAT		= $this->input->post('IR_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$INVSTAT		= 'NI';
			$IR_NOTE		= $this->input->post('IR_NOTE');			
			$WH_CODE		= $this->input->post('WH_CODE');
			$IR_CREATED		= date('Y-m-d H:i:s');
			$IR_CREATER		= $DefEmp_ID;
			
			$insRR = array('IR_NUM' 		=> $IR_NUM,
							'IR_CODE' 		=> $IR_CODE,
							'IR_DATE'		=> $IR_DATE,
							'IR_DUEDATE'	=> $IR_DUEDATE,
							'IR_SOURCE'		=> $IR_SOURCE,
							'PRJCODE'		=> $PRJCODE,
							'SPLCODE'		=> $SPLCODE,
							'PO_NUM'		=> $PO_NUM,
							'PR_NUM'		=> $PR_NUM,
							'IR_REFER'		=> $IR_CODE,
							'IR_AMOUNT'		=> $IR_AMOUNT,
							'TERM_PAY'		=> $TERM_PAY,
							'IR_STAT'		=> $IR_STAT,
							'INVSTAT'		=> $INVSTAT,
							'IR_NOTE'		=> $IR_NOTE,
							'WH_CODE'		=> $WH_CODE,
							'IR_CREATED'	=> $IR_CREATED,
							'IR_CREATER'	=> $IR_CREATER,
							'Patt_Date'		=> $Patt_Date,
							'Patt_Month'	=> $Patt_Month,
							'Patt_Year'		=> $Patt_Year,
							'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_itemusage->add($insRR);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_UM_detail',$d);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('IR_STAT');			// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_UM_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_UM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_UM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_UM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_UM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_UM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_UM_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_UM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_item_usage/get_last_ten_UM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$IR_NUM	= $_GET['id'];
			$IR_NUM	= $this->url_encryption_helper->decode_url($IR_NUM);
					
			$getrr 					= $this->m_itemusage->get_UM_by_number($IR_NUM)->row();			
			$PRJCODE				= $getrr->PRJCODE;
			$data["MenuCode"] 		= 'MN189';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Item Receipt';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_item_usage/update_process');
			//$linkBack				= site_url('c_inventory/c_item_usage/get_last_ten_UM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 		= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_inventory/c_item_usage/get_last_ten_UM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL']		= $this->m_itemusage->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemusage->viewvendor()->result();
			
			$data['default']['IR_NUM'] 		= $getrr->IR_NUM;
			$data['default']['IR_CODE'] 	= $getrr->IR_CODE;
			$data['default']['IR_DATE'] 	= $getrr->IR_DATE;
			$data['default']['IR_DUEDATE'] 	= $getrr->IR_DUEDATE;
			$data['default']['IR_SOURCE'] 	= $getrr->IR_SOURCE;
			$IR_SOURCE						= $getrr->IR_SOURCE;
			$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
			$data['default']['SPLCODE'] 	= $getrr->SPLCODE;
			$SPLCODE						= $getrr->SPLCODE;
			$data['default']['PO_NUM'] 		= $getrr->PO_NUM;
			$data['default']['PO_NUMX'] 	= $getrr->PO_NUM;
			$data['default']['PR_NUM'] 		= $getrr->PR_NUM;
			$data['default']['IR_REFER'] 	= $getrr->IR_REFER;
			$data['default']['IR_AMOUNT'] 	= $getrr->IR_AMOUNT;
			$data['default']['TERM_PAY'] 	= $getrr->TERM_PAY;
			$data['default']['TRXUSER'] 	= $getrr->TRXUSER;
			$data['default']['APPROVE'] 	= $getrr->APPROVE;
			$data['default']['IR_STAT'] 	= $getrr->IR_STAT;
			$data['default']['INVSTAT'] 	= $getrr->INVSTAT;
			$data['default']['IR_NOTE'] 	= $getrr->IR_NOTE;
			$data['default']['IR_NOTE2'] 	= $getrr->IR_NOTE2;
			$data['default']['REVMEMO']		= $getrr->REVMEMO;
			$data['default']['WH_CODE']		= $getrr->WH_CODE;
			$data['default']['Patt_Year'] 	= $getrr->Patt_Year;
			$data['default']['Patt_Number'] = $getrr->Patt_Number;
			
			if($IR_SOURCE == 1)
				$this->load->view('v_inventory/v_itemusage/itemusage_form_dir', $data);
			elseif($IR_SOURCE == 3)
				$this->load->view('v_inventory/v_itemusage/itemusage_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
			
			$IR_NUM			= $this->input->post('IR_NUM');
			$IR_CODE		= $this->input->post('IR_CODE');
			$IR_DATE		= date('Y-m-d',strtotime($this->input->post('IR_DATE')));
			$TERM_PAY		= $this->input->post('TERM_PAY');
			$IR_DUEDATE1	= strtotime ("+$TERM_PAY day", strtotime ($IR_DATE));
			$IR_DUEDATE		= date('Y-m-d', $IR_DUEDATE1);
			$Patt_Date		= date('d',strtotime($this->input->post('IR_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('IR_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('IR_DATE')));
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			
			if($IR_SOURCE == 1)		// Direct
			{
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$PR_NUM			= '';
				$PR_CODE		= '';
			}
			elseif($IR_SOURCE == 2)	// MR
			{
				$Ref_Number 	= $this->input->post('Ref_NumberMR');
				$PO_NUM 		= $this->input->post('Ref_NumberMR');
				$PR_NUM			= $this->input->post('Ref_NumberMR');
				$PR_CODE		= $this->input->post('Ref_NumberMR');
			}
			elseif($IR_SOURCE == 3)	// PO
			{
				$Ref_Number 	= $this->input->post('Ref_NumberPO');
				$PR_NUM			= '';
				$PR_CODE		= '';
				$getRefMRfPO	= "SELECT PR_NUM, PR_CODE
									FROM tbl_po_header
									WHERE PO_NUM = '$Ref_Number'
									AND PRJCODE = '$PRJCODE'";
				$resgetMRfPO 	= $this->db->query($getRefMRfPO)->result();
				foreach($resgetMRfPO as $rowPO) :
					$PR_NUM 	= $rowPO->PR_NUM;
					$PR_CODE 	= $rowPO->PR_CODE;
				endforeach;
				$PO_NUM			= $Ref_Number;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PR_CODE;
			}
			$IR_REFER		= $this->input->post('IR_REFER');
			$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
			
			$IR_STAT		= $this->input->post('IR_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$INVSTAT		= 'NI';
			$IR_NOTE		= $this->input->post('IR_NOTE');			
			$WH_CODE		= $this->input->post('WH_CODE');
			//$IR_CREATED		= date('Y-m-d H:i:s');
			//$IR_CREATER		= $DefEmp_ID;
			
			$updIR = array('IR_NUM' 		=> $IR_NUM,
							'IR_CODE' 		=> $IR_CODE,
							'IR_DATE'		=> $IR_DATE,
							'IR_DUEDATE'	=> $IR_DUEDATE,
							'IR_SOURCE'		=> $IR_SOURCE,
							'PRJCODE'		=> $PRJCODE,
							'SPLCODE'		=> $SPLCODE,
							'PO_NUM'		=> $PO_NUM,
							'PR_NUM'		=> $PR_NUM,
							'IR_REFER'		=> $IR_CODE,
							'IR_AMOUNT'		=> $IR_AMOUNT,
							'TERM_PAY'		=> $TERM_PAY,
							'IR_STAT'		=> $IR_STAT,
							'INVSTAT'		=> $INVSTAT,
							'IR_NOTE'		=> $IR_NOTE,
							'WH_CODE'		=> $WH_CODE);
			$this->m_itemusage->updateIR($IR_NUM, $updIR);
			
			$this->m_itemusage->deleteIRDetail($IR_NUM);	
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_UM_detail',$d);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_UM_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_UM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_UM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_UM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_UM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_UM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_UM_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_UM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_item_usage/get_last_ten_UM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallpo() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
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
			
			$data['title'] 		= $appName;
			$data['pageFrom']	= 'PO';
			$data['PRJCODE']	= $PRJCODE;
					
			$this->load->view('v_inventory/v_itemusage/itemusage_sel_po', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function genCode() // OK
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$PattCode	= $this->input->post('Pattern_Code');
		$PattLength	= $this->input->post('Pattern_Length');
		$useYear	= $this->input->post('useYear');
		$useMonth	= $this->input->post('useMonth');
		$useDate	= $this->input->post('useDate');
		$ISDIRECT	= $this->input->post('ISDIRECT');
		
		$IRDate		= date('Y-m-d',strtotime($this->input->post('IRDate')));
		$year		= date('Y',strtotime($this->input->post('IRDate')));
		$month 		= (int)date('m',strtotime($this->input->post('IRDate')));
		$date 		= (int)date('d',strtotime($this->input->post('IRDate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_UM_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_um_header
					WHERE Patt_Year = $year AND PRJCODE = '$PRJCODEX'";
		$result = $this->db->query($sql)->result();
		if($myCount>0)
		{
			$myMax	= 0;
			foreach($result as $row) :
				$myMax = $row->maxNumber;
				$myMax = $myMax+1;
			endforeach;
		}	
		else
		{
			$myMax = 1;
		}
		
		$thisMonth = $month;
	
		$lenMonth = strlen($thisMonth);
		if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
		$pattMonth = $nolMonth.$thisMonth;
		
		$thisDate = $date;
		$lenDate = strlen($thisDate);
		if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
		$pattDate = $nolDate.$thisDate;
		
		// group year, month and date
		$year = substr($year,2,2);
		if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$year$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$year$pattMonth";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$year$pattDate";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "$year";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$pattMonth";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$pattDate";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "";
			
		$lastPatternNumb = $myMax;
		$lastPatternNumb1 = $myMax;
		$len = strlen($lastPatternNumb);
		
		if($PattLength==2)
		{
			if($len==1) $nol="0";
		}
		elseif($PattLength==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($PattLength==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($PattLength==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($PattLength==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		elseif($PattLength==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
		}
		$lastPatternNumb	= $nol.$lastPatternNumb;
		if($ISDIRECT == 1)
			$DocNumber 			= "$PattCode$PRJCODEX$groupPattern-$lastPatternNumb"."-D";
		else
			$DocNumber 			= "$PattCode$PRJCODEX$groupPattern-$lastPatternNumb";
			
		echo "$DocNumber~$lastPatternNumb";
	}
	
	function popupallitem() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['h2_title'] 		= 'item receipt';
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['countAllItem']	= $this->m_itemusage->count_allItem($PRJCODE);
			$data['vwAllItem'] 		= $this->m_itemusage->viewAllItem($PRJCODE)->result();
					
			$this->load->view('v_inventory/v_itemusage/item_list_selectitem', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

    function indexInbox() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Item Receipt';
			$data['h3_title'] 	= 'approval';
	 		$data["MenuCode"] 	= 'MN189';
			
			$data['countIR_OUT']= $this->m_itemusage->count_all_UM_OUT($DefEmp_ID);
			$data['viewIR_OUT'] = $this->m_itemusage->get_all_UM_OUT($DefEmp_ID)->result();
			
			$this->load->view('v_inventory/v_itemreceipt/inb_itemreceipt', $data);
		}
		else
		{
			redirect('Auth');
		}
    }
	
	function update_inbox() // OK
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$CollID			= $_GET['id'];
		$CollID			= $this->url_encryption_helper->decode_url($CollID);
		
		$splitCode 		= explode("~", $CollID);
		$IR_NUM			= $splitCode[0];
		$ISDIRECT		= $splitCode[1];				// 1. Direct, 2. MR, 3. PO
		
		if ($this->session->userdata('login') == TRUE)
		{					
			$getrr 					= $this->m_itemusage->get_UM_by_number($IR_NUM)->row();			
			$PRJCODE				= $getrr->PRJCODE;
			$data['countSUPL']		= $this->m_itemusage->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemusage->viewvendor()->result();
			$data["MenuCode"] 		= 'MN189';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Approve IR';
			$data['h3_title']		= 'inventory';
			
			$data['backURL'] 		= site_url('c_inventory/c_item_usage/indexInb/');
			
			$data['default']['IR_NUM'] 		= $getrr->IR_NUM;
			$data['default']['IR_CODE'] 	= $getrr->IR_CODE;
			$data['default']['IR_DATE'] 	= $getrr->IR_DATE;
			$data['default']['IR_DUEDATE'] 	= $getrr->IR_DUEDATE;
			$data['default']['IR_SOURCE'] 	= $getrr->IR_SOURCE;
			$IR_SOURCE						= $getrr->IR_SOURCE;
			$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
			$data['default']['SPLCODE'] 	= $getrr->SPLCODE;
			$SPLCODE						= $getrr->SPLCODE;
			$data['default']['PO_NUM'] 		= $getrr->PO_NUM;
			$data['default']['PO_NUMX'] 	= $getrr->PO_NUM;
			$data['default']['PR_NUM'] 		= $getrr->PR_NUM;
			$data['default']['IR_REFER'] 	= $getrr->IR_REFER;
			$data['default']['IR_AMOUNT'] 	= $getrr->IR_AMOUNT;
			$data['default']['TERM_PAY'] 	= $getrr->TERM_PAY;
			$data['default']['TRXUSER'] 	= $getrr->TRXUSER;
			$data['default']['APPROVE'] 	= $getrr->APPROVE;
			$data['default']['IR_STAT'] 	= $getrr->IR_STAT;
			$data['default']['INVSTAT'] 	= $getrr->INVSTAT;
			$data['default']['IR_NOTE'] 	= $getrr->IR_NOTE;
			$data['default']['IR_NOTE2'] 	= $getrr->IR_NOTE2;
			$data['default']['REVMEMO']		= $getrr->REVMEMO;
			$data['default']['WH_CODE']		= $getrr->WH_CODE;
			$data['default']['Patt_Year'] 	= $getrr->Patt_Year;
			$data['default']['Patt_Number'] = $getrr->Patt_Number;
			
			if($ISDIRECT == 1)
			{
				$data['form_action']	= site_url('c_inventory/c_item_usage/update_inbox_process');
				$this->load->view('v_inventory/v_itemreceipt/inb_itemreceipt_form_dir', $data);
			}
			else
			{
				$data['form_action']	= site_url('c_inventory/c_item_usage/update_inbox_process');
				$this->load->view('v_inventory/v_itemreceipt/inb_itemreceipt_form', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_inbox_process() // U
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		$APPDATE 				= date('Y-m-d H:i:s');
			
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
			
			$IR_NUM			= $this->input->post('IR_NUM');
			$IR_CODE		= $this->input->post('IR_CODE');
			$IR_DATE		= date('Y-m-d', strtotime($this->input->post('IR_DATE')));
			$IR_DUEDATE		= date('Y-m-d', strtotime($this->input->post('IR_DUEDATE')));
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			$ISDIRECT		= $this->input->post('ISDIRECT');
			
			
			
			$IR_REFER		= $this->input->post('IR_REFER');
			$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
			$TERM_PAY		= $this->input->post('TERM_PAY');
			$IR_STAT		= $this->input->post('IR_STAT'); 	// 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$INVSTAT		= 'NI';
			$IR_NOTE		= $this->input->post('IR_NOTE');
			$IR_NOTE2		= $this->input->post('IR_NOTE2');
			$WH_CODE		= $this->input->post('WH_CODE');		
			$WH_CODE		= $this->input->post('WH_CODE');
			$IR_APPROVED	= date('Y-m-d H:i:s');
			$IR_APPROVER	= $DefEmp_ID;
				
			$updIR = array('IR_STAT'		=> $IR_STAT,
							'APPROVE'		=> $IR_STAT,
							'IR_NOTE2'		=> $IR_NOTE2,
							'IR_APPROVED'	=> $IR_APPROVED,
							'IR_APPROVER'	=> $IR_APPROVER);
			$this->m_itemusage->updateIR($IR_NUM, $updIR);
			
			if($IR_STAT == 3)
			{
				$DOCSource		= '';
				if($IR_SOURCE == 2)		// MR
				{
					$DOCSource		= "MRXXXXXXXX";
				}
				elseif($IR_SOURCE == 3)	// PO
				{
					$Ref_Number 	= $this->input->post('Ref_NumberPO');
					$PO_NUM			= $Ref_Number;
					$DOCSource		= $PO_NUM;
					$this->m_itemusage->updatePO($IR_NUM, $PRJCODE, $PO_NUM, $ISDIRECT); // UPDATE JOBD ETAIL DAN PR
				}
				elseif($IR_SOURCE == 1)	// DIRECTS
				{
					$Ref_Number 	= '';
					$PO_NUM			= '';
					$DOCSource		= 'Direct';
					$this->m_itemusage->updateJOBDET($IR_NUM, $PRJCODE, $ISDIRECT); // UPDATE JOBD ETAIL DAN PR
				}
			}
				
			if($IR_STAT == 3 || $IR_STAT == 4 || $IR_STAT == 5)
			{
				// START : TRACK FINANCIAL TRACK
					$this->load->model('m_updash/m_updash', '', TRUE);
					$paramFT = array('DOC_NUM' 		=> $IR_NUM,
									'DOC_DATE' 		=> $IR_DATE,
									'DOC_EDATE' 	=> $IR_DUEDATE,
									'PRJCODE' 		=> $PRJCODE,
									'FIELD_NAME1' 	=> 'FT_COP',
									'FIELD_NAME2' 	=> 'FM_COP',
									'TOT_AMOUNT'	=> $IR_AMOUNT);
					$this->m_updash->finTrack($paramFT);
				// END : TRACK FINANCIAL TRACK
								
				// START : JOURNAL HEADER
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $IR_NUM;
					$JournalType	= 'IR';
					$JournalH_Date	= $IR_DATE;
					$Company_ID		= 'NKE';
					$DOCSource		= $DOCSource;
					$LastUpdate		= $IR_APPROVED;
					$WH_CODE		= $WH_CODE;
					$Refer_Number	= $PR_NUM;
					$RefType		= 'WBSD';
					$PRJCODE		= $PRJCODE;
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $Company_ID,
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
				
				// START : JOURNAL DETAIL
					foreach($_POST['data'] as $d)
					{
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$ITM_CODE 		= $d['ITM_CODE'];
						$ACC_ID 		= $d['ACC_ID'];
						$ITM_UNIT 		= $d['ITM_UNIT'];
						$ITM_QTY 		= $d['ITM_QTY'];
						$ITM_PRICE 		= $d['ITM_PRICE'];
						$ITM_DISC 		= $d['ITM_DISC'];
						$TAXCODE1 		= $d['TAXCODE1'];
						$TAXPRICE1 		= $d['TAXPRICE1'];
						
						$JournalH_Code	= $IR_NUM;
						$JournalType	= 'IR';
						$JournalH_Date	= $IR_DATE;
						$Company_ID		= 'NKE';
						$Currency_ID	= 'IDR';
						$LastUpdate		= $IR_APPROVED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $PR_NUM;
						$RefType		= 'WBSD';
						$JSource		= 'WBSD';
						$PRJCODE		= $PRJCODE;
						
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
											'JRSource'			=> $JSource,
											'ITM_CODE' 			=> $ITM_CODE,
											'ACC_ID' 			=> $ACC_ID,
											'ITM_UNIT' 			=> $ITM_UNIT,
											'ITM_QTY' 			=> $ITM_QTY,
											'ITM_PRICE' 		=> $ITM_PRICE,
											'ITM_DISC' 			=> $ITM_DISC,
											'TAX_CODE' 			=> $TAXCODE1,
											'TAX_AMOUNT' 		=> $TAXPRICE1);
						$this->m_journal->createJournalD($JournalH_Code, $parameters);
						
						// START : RECORD TO ITEM HISTORY
							$this->m_journal->createITMHistPlus($JournalH_Code, $parameters);
						// START : RECORD TO ITEM HISTORY
					}
				// END : JOURNAL DETAIL
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_UM_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_UM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_UM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_UM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_UM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_UM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_UM_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_UM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_item_usage/indexInbox/');
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
		
	function getProjName($myLove_the_an) // U
	{ 
		// check exixtensi projcewt code
		$getProj_Name 	= $this->m_itemusage_sd->getProjName($myLove_the_an);
		echo $getProj_Name;
	}
}