<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2017
 * File Name	= Item_receipt.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Item_receipt  extends CI_Controller
{
 	function index() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_item_receipt/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0) // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'item receipt';
			
			$num_rows 					= $this->m_itemreceipt->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_itemreceipt->get_last_ten_project()->result();
			
			$this->load->view('v_inventory/v_itemreceipt/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_IR() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
			$data['h2_title'] 		= 'Item Receipt';
			$data['h3_title'] 		= 'inventory';
			//$linkBack				= site_url('c_inventory/c_item_receipt/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
			//$data['link'] 		= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_inventory/c_item_receipt/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
			$num_rows 				= $this->m_itemreceipt->count_all_num_rows($PRJCODE);
			
			$data["recordcount"] 	= $num_rows;
			$data["PRJCODE"] 		= $PRJCODE;
	 		$data["MenuCode"] 		= 'MN067';
			
			$data['viewIR'] 		= $this->m_itemreceipt->get_last_ten_IR($PRJCODE)->result();
			
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function addDir() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
			$data['h2_title']		= 'Item Receipt';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_item_receipt/addDir_process');
			
			$data['backURL'] 		= site_url('c_inventory/c_item_receipt/get_last_ten_IR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data["MenuCode"] 		= 'MN067';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL']		= $this->m_itemreceipt->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemreceipt->viewvendor()->result();
			
			$MenuCode 				= 'MN067';
			$data['viewDocPattern'] = $this->m_itemreceipt->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt_form_dir', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallitem() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'List Item';
			$data['h2_title'] 			= 'item receipt';
			$data['PRJCODE'] 			= $PRJCODE;
			
			$data['countAllItem'] = $this->m_itemreceipt->count_all_num_rowsAllItem($PRJCODE);
			$data['vwAllItem'] 		= $this->m_itemreceipt->viewAllItem($PRJCODE)->result();
					
			$this->load->view('v_inventory/v_itemreceipt/item_list_selectitem', $data);
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
		
		$IRDate		= date('Y-m-d',strtotime($this->input->post('IRDate')));
		$year		= date('Y',strtotime($this->input->post('IRDate')));
		$month 		= (int)date('m',strtotime($this->input->post('IRDate')));
		$date 		= (int)date('d',strtotime($this->input->post('IRDate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_ir_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_ir_header
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
		$DocNumber 			= "$PattCode$PRJCODEX$groupPattern-$lastPatternNumb";
		echo "$DocNumber~$lastPatternNumb";
	}
	
	function addDir_process() // U
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
			
			$IR_NUM_BEF		= $this->input->post('IR_NUM_BEF');			
			$IR_NUM			= $this->input->post('IR_NUM');
			$PRJCODE		= $this->input->post('PRJCODE');
			 //return false;
			if($IR_NUM_BEF == $IR_NUM) // IF EDIT 
			{
				$IR_CODE		= $this->input->post('IR_CODE');
				$IR_DATE		= date('Y-m-d',strtotime($this->input->post('IR_DATE')));
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
										WHERE PO_NUM = '$PO_NUM'
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
								'IR_SOURCE'		=> $IR_SOURCE,
								'PRJCODE'		=> $PRJCODE,
								'SPLCODE'		=> $SPLCODE,
								'PO_NUM'		=> $PO_NUM,
								'PR_NUM'		=> $PR_NUM,
								'IR_REFER'		=> $IR_CODE,
								'IR_AMOUNT'		=> $IR_AMOUNT,
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
				$this->m_itemreceipt->add($insRR);
				
				foreach($_POST['data'] as $d)
				{
					$this->db->insert('tbl_ir_detail',$d);
				}
			}
			else // IF NEW
			{
				$IR_CODE		= $this->input->post('IR_CODE');
				$IR_DATE		= date('Y-m-d',strtotime($this->input->post('IR_DATE')));
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
										WHERE PO_NUM = '$PO_NUM'
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
								'IR_SOURCE'		=> $IR_SOURCE,
								'PRJCODE'		=> $PRJCODE,
								'SPLCODE'		=> $SPLCODE,
								'PO_NUM'		=> $PO_NUM,
								'PR_NUM'		=> $PR_NUM,
								'IR_REFER'		=> $IR_CODE,
								'IR_AMOUNT'		=> $IR_AMOUNT,
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
				$this->m_itemreceipt->addDir($insRR);
				
				foreach($_POST['data'] as $d)
				{
					$this->db->insert('tbl_ir_detail',$d);
				}
			}
			
			//return false;
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('IR_STAT');			// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
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
			
			$url			= site_url('c_inventory/c_item_receipt/get_last_ten_IR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // U
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$IR_NUM	= $_GET['id'];
			$IR_NUM	= $this->url_encryption_helper->decode_url($IR_NUM);
					
			$getrr 					= $this->m_itemreceipt->get_RR_by_number($IR_NUM)->row();			
			$PRJCODE				= $getrr->PRJCODE;
			$data["MenuCode"] 		= 'MN067';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Item Receipt';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_item_receipt/update_process');
			//$linkBack				= site_url('c_inventory/c_item_receipt/get_last_ten_IR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 		= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_inventory/c_item_receipt/get_last_ten_IR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 			= $PRJCODE;
			//$data['recordcountVend'] 	= $this->m_itemreceipt->count_all_num_rowsVend();
			//$data['viewvendor'] 		= $this->m_itemreceipt->viewvendor()->result();			
			
			$data['default']['IR_NUM'] 		= $getrr->IR_NUM;
			$data['default']['IR_CODE'] 	= $getrr->IR_CODE;
			$data['default']['IR_SOURCE'] 	= $getrr->IR_SOURCE;
			$data['default']['IR_DATE'] 	= $getrr->IR_DATE;
			$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
			$data['default']['SPLCODE'] 	= $getrr->SPLCODE;
			$data['default']['IR_REFER'] 	= $getrr->IR_REFER;
			$data['default']['PO_NUM'] 		= $getrr->PO_NUM;
			$data['default']['IR_AMOUNT'] 	= $getrr->IR_AMOUNT;
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
			
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt_form_dir', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // U
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
									WHERE PO_NUM = '$PO_NUM'
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
			
			$updIR = array('IR_NUM' 		=> $IR_NUM,
							'IR_CODE' 		=> $IR_CODE,
							'IR_DATE'		=> $IR_DATE,
							'IR_SOURCE'		=> $IR_SOURCE,
							'PRJCODE'		=> $PRJCODE,
							'SPLCODE'		=> $SPLCODE,
							'PO_NUM'		=> $PO_NUM,
							'PR_NUM'		=> $PR_NUM,
							'IR_REFER'		=> $IR_CODE,
							'IR_AMOUNT'		=> $IR_AMOUNT,
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
			$this->m_itemreceipt->updateIR($IR_NUM, $updIR);
			
			$this->m_itemreceipt->deleteIRDetail($IR_NUM);	
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_ir_detail',$d);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
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
			
			$url			= site_url('c_inventory/c_item_receipt/get_last_ten_IR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
    function indexInb()  // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_inventory/c_item_receipt/indexInbox/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
    }

    function indexInbox() // U
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
	 		$data["MenuCode"] 	= 'MN067';
			
			$data['countIR_OUT']= $this->m_itemreceipt->count_all_IR_OUT($DefEmp_ID);
			$data['viewIR_OUT'] = $this->m_itemreceipt->get_all_IR_OUT($DefEmp_ID)->result();
			
			$this->load->view('v_inventory/v_itemreceipt/inb_itemreceipt', $data);
		}
		else
		{
			redirect('Auth');
		}
    }
	
	function update_inbox() // U
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$IR_NUM	= $_GET['id'];
			$IR_NUM	= $this->url_encryption_helper->decode_url($IR_NUM);
					
			$getrr 					= $this->m_itemreceipt->get_RR_by_number($IR_NUM)->row();			
			$PRJCODE				= $getrr->PRJCODE;
			$data['countSUPL']		= $this->m_itemreceipt->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemreceipt->viewvendor()->result();
			$data["MenuCode"] 		= 'MN067';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Approve IR';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_item_receipt/update_inbox_process');
			
			$data['backURL'] 		= site_url('c_inventory/c_item_receipt/indexInb/');
			
			$data['default']['IR_NUM'] 		= $getrr->IR_NUM;
			$data['default']['IR_CODE'] 	= $getrr->IR_CODE;
			$data['default']['IR_SOURCE'] 	= $getrr->IR_SOURCE;
			$data['default']['IR_DATE'] 	= $getrr->IR_DATE;
			$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
			$data['default']['SPLCODE'] 	= $getrr->SPLCODE;
			$data['default']['IR_REFER'] 	= $getrr->IR_REFER;
			$data['default']['PO_NUM'] 		= $getrr->PO_NUM;
			$data['default']['IR_AMOUNT'] 	= $getrr->IR_AMOUNT;
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
			
			$this->load->view('v_inventory/v_itemreceipt/inb_itemreceipt_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_inbox_process() // U
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			$ISDIRECT 		= $this->input->post('ISDIRECT');
			
			if($IR_SOURCE == 1)		// Direct
			{
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$PR_NUM			= '';
				$PR_CODE		= '';
			}
			elseif($IR_SOURCE == 3)	// PO
			{
				$Ref_Number 	= $this->input->post('Ref_Number');
				$PR_NUM			= '';
				$PR_CODE		= '';
				$getRefMRfPO	= "SELECT PR_NUM, PR_CODE
									FROM tbl_po_header
									WHERE PO_NUM = '$PO_NUM'
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
			$IR_APPROVED	= date('Y-m-d H:i:s');
			$IR_APPROVER	= $DefEmp_ID;
				
			$updIR = array(//'IR_NUM' 		=> $IR_NUM,
							//'IR_CODE' 		=> $IR_CODE,
							//'IR_DATE'		=> $IR_DATE,
							//'IR_SOURCE'		=> $IR_SOURCE,
							//'PRJCODE'		=> $PRJCODE,
							//'SPLCODE'		=> $SPLCODE,
							//'PO_NUM'		=> $PO_NUM,
							//'PR_NUM'		=> $PR_NUM,
							//'IR_REFER'		=> $IR_CODE,
							//'IR_AMOUNT'		=> $IR_AMOUNT,
							'IR_STAT'		=> $IR_STAT,
							'APPROVE'		=> $IR_STAT,
							//'INVSTAT'		=> $INVSTAT,
							//'IR_NOTE'		=> $IR_NOTE,
							'IR_NOTE2'		=> $IR_NOTE2,
							//'WH_CODE'		=> $WH_CODE,
							//'IR_CREATED'	=> $IR_CREATED,
							//'IR_CREATER'	=> $IR_CREATER,
							'IR_APPROVED'	=> $IR_APPROVED,
							'IR_APPROVER'	=> $IR_APPROVER
							//'Patt_Date'		=> $Patt_Date,
							//'Patt_Month'	=> $Patt_Month,
							//'Patt_Year'		=> $Patt_Year,
							//'Patt_Number'	=> $this->input->post('Patt_Number')
							);
			$this->m_itemreceipt->updateIR($IR_NUM, $updIR);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			if($IR_STAT == 3)
			{
				$DOCSource		= '';
				if($IR_SOURCE == 2)		// MR
				{
					$DOCSource		= "MRXXXXXXXX";
				}
				elseif($IR_SOURCE == 3)	// PO
				{
					$Ref_Number 	= $this->input->post('Ref_Number');
					$PO_NUM			= $Ref_Number;
					$DOCSource		= $PO_NUM;
					$this->m_itemreceipt->updatePO($PO_NUM);	// UPDATE PO STATUS IR
					
					foreach($_POST['data'] as $d) //  Dijalankan saat diapprove
					{
						$PRJCODE 		= $PRJCODE;
						$IR_NUM 		= $IR_NUM;
						$IR_CODE 		= $IR_CODE;
						$ITM_CODE 		= $d['ITM_CODE'];
						$ITM_QTY 		= $d['ITM_QTY'];
						$ITM_PRICE 		= $d['ITM_PRICE'];
						$ACC_ID 		= $d['ACC_ID'];
						
						$parameters = array(
							'PO_NUM'	=> $PO_NUM,
							'PRJCODE' 	=> $PRJCODE,
							'IR_NUM' 	=> $IR_NUM,
							'IR_CODE' 	=> $IR_CODE,
							'ITM_CODE' 	=> $ITM_CODE,
							'ITM_QTY' 	=> $ITM_QTY,
							'ITM_PRICE' => $ITM_PRICE
						);
						$this->m_itemreceipt->updatePOQTY($parameters);
					}
				}
				
				// Start : Pembuatan Journal Header
					if($IR_STAT == 3 || $IR_STAT == 4 || $IR_STAT == 5)
					{
						$parameters = array('JournalH_Code' 	=> $IR_NUM,
										'JournalType'			=> 'IR',
										'JournalH_Date' 		=> $APPDATE,
										'Source'				=> $DOCSource,
										'Emp_ID'				=> $DefEmp_ID,
										'LastUpdate'			=> $IR_APPROVED,	
										'KursAmount_tobase'		=> 1,
										'WHCODE'				=> 1,
										'Reference_Number'		=> $SPPCODE,
										'proj_Code'				=> $PRJCODE);
						//$this->M_itemreceipt_sd->createJournalH($JournalH_Code, $parameters);
					}
				// End : Pembuatan Journal Header
						
				if($IR_STAT == 3 || $IR_STAT == 4 || $IR_STAT == 5)
				{
					foreach($_POST['data'] as $d)
					{
						$IR_CODE 		= $d['IR_CODE'];
						$CSTCODE 		= $d['CSTCODE'];
						$ACC_ID 		= $d['ACC_ID'];
						$CSTUNIT 		= $d['CSTUNIT'];
						$LPMVOLM 		= $d['LPMVOLM'];
						$CSTPUNT 		= $d['CSTPUNT'];
						$CSTDISP 		= $d['CSTDISP'];
						$CSTDISC 		= $d['CSTDISC'];
						$CSTCOST 		= $d['CSTCOST'];
						
						// Update Qty RR for Project Plan per Item Per Project
						$parameters = array(
							'PO_NUM'	=> $PO_NUM,
							'OP_CODE'	=> $OP_CODE,
							'SPPCODE'	=> $SPPCODE,
							'IR_CODE' 	=> $IR_CODE,
							'LPMDATE'	=> $LPMDATE,
							'RRSource'	=> $RRSource,
							'WHCODE'	=> $WHCODE,
							'CSTCODE' 	=> $CSTCODE,
							'CSTUNIT' 	=> $CSTUNIT,
							'LPMVOLM' 	=> $LPMVOLM,
							'CSTPUNT' 	=> $CSTPUNT,
							'CSTDISP' 	=> $CSTDISP,
							'CSTDISC' 	=> $CSTDISC,
							'CSTCOST' 	=> $CSTCOST
						);
						//$this->M_itemreceipt_sd->createJournalD($IR_NUM, $IR_CODE, $PRJCODE, $parameters);
					}
				}
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_item_receipt/indexInbox/');
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
		$getProj_Name 	= $this->M_itemreceipt_sd->getProjName($myLove_the_an);
		echo $getProj_Name;
	}
	
	// 16 Nopember 2014
	function addDirect()
	{	
		if ($this->session->userdata('login') == TRUE)
		{
			$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
				
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $MyAppName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Item Receipt | Add Direct';
			$data['main_view'] 		= 'v_inventory/v_itemreceipt/itemreceipt_sdDirect_form'; //itemreceipt_sdDirect_form
			$data['form_action']	= site_url('c_inventory/itemreceipt_sd/add_process');
			$data['link'] 			= array('link_back' => anchor('c_inventory/itemreceipt_sd/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['recordcountVend'] 	= $this->M_itemreceipt_sd->count_all_num_rowsVend();
			$data['viewvendor'] 	= $this->M_itemreceipt_sd->viewvendor()->result();
			$data['recordcountDept'] 	= $this->M_itemreceipt_sd->count_all_num_rowsDept();
			$data['viewDepartment'] 	= $this->M_itemreceipt_sd->viewDepartment()->result();
			$data['recordcountEmpDept'] 	= $this->M_itemreceipt_sd->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->M_itemreceipt_sd->viewEmployeeDept()->result();
			
			$MenuCode 				= 'MN067';
			$data['viewDocPattern'] 	= $this->M_itemreceipt_sd->getDataDocPat($MenuCode)->result();
	
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}