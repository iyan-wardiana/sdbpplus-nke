<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 27 April 2018
 * File Name	= C_tsf1h0d2g.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_bp0c07180851 extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_bp0c07180851/getbp0c07180851/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function getbp0c07180851() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$appName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($appName);
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			$data['title'] 		= $appName;
			$data['appName'] 	= $appName;
			$data["MenuCode"] 	= 'MN144';
			$data['addURL'] 	= site_url('c_finance/c_bp0c07180851/addbp0c07180851/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action']= site_url('c_finance/c_bp0c07180851/add_process');
			$data["countPRJ"]	= $this->m_projectlist->count_all_project($EmpID);
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($EmpID)->result();
			
			$data["countBP"] 	= $this->m_bank_payment->count_all_BP();
			$data['vwBP'] 		= $this->m_bank_payment->get_last_BP()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN144';
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
			
			$this->load->view('v_finance/v_bank_payment/v_bank_payment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addbp0c07180851() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$appName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($appName);
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_finance/c_bp0c07180851/add_process');
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/getbp0c07180851/?id='.$this->url_encryption_helper->encode_url($appName));			
			
			$proj_Currency		= 'IDR';
			$data['countAcc'] 	= $this->m_bank_payment->count_all_Acc($proj_Currency);
			$data['vwAcc'] 		= $this->m_bank_payment->view_all_Acc($proj_Currency)->result();
			$data['countSPL'] 	= $this->m_bank_payment->count_all_SPL(); // ProjCode nya dihilangkan
			$countSPL 			= $this->m_bank_payment->count_all_SPL(); // ProjCode nya dihilangkan
			$data['vwSPL'] 		= $this->m_bank_payment->view_all_SPL()->result();
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN144';
			$data["MenuCode"] 	= 'MN144';
			$data['vwDocPatt'] 	= $this->m_bank_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN144';
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
			
			$this->load->view('v_finance/v_bank_payment/v_bank_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTheValueBG()
	{
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CB_CHEQNO	= $_GET['id'];
			//$CB_CHEQNO	= $this->url_encryption_helper->decode_url($CB_CHEQNO);
		
			// check exixtensi projcewt code
			$recCounrBG		= $this->m_bank_payment->count_all_BG($CB_CHEQNO);
			$getAmmountBG 	= $this->m_bank_payment->getAmmountBG($CB_CHEQNO)->result();
			
			$this->db->select('Display_Rows,decFormat');
			$resGlobal = $this->db->get('tglobalsetting')->result();
			foreach($resGlobal as $row) :
				$Display_Rows = $row->Display_Rows;
				$decFormat = $row->decFormat;
			endforeach;
	
			if($recCounrBG > 0)
			{
				foreach($getAmmountBG as $row) :
					$BGAmmount	= $row->BGAmmount;
				endforeach;
				print number_format($BGAmmount, $decFormat);
			}
			else
			{
				$BGAmmount		= 0;
				print $BGAmmount;
			}
		}
		else
		{
			redirect('login');
		}
	}
		
	function getTheValueUseBG()
	{
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CB_CHEQNO	= $_GET['id'];
			//$CB_CHEQNO	= $this->url_encryption_helper->decode_url($CB_CHEQNO);
		
			// check exixtensi projcewt code
			if($CB_CHEQNO != '')
			{
				$recUseCounrBG		= $this->m_bank_payment->count_all_UseBG($CB_CHEQNO);
				$getUseAmmountBG 	= $this->m_bank_payment->getAmmountUseBG($CB_CHEQNO)->result();
				
				$this->db->select('Display_Rows,decFormat');
				$resGlobal = $this->db->get('tglobalsetting')->result();
				foreach($resGlobal as $row) :
					$Display_Rows = $row->Display_Rows;
					$decFormat = $row->decFormat;
				endforeach;
		
				if($recUseCounrBG > 0)
				{			
					foreach($getUseAmmountBG as $row2) :
						$UseBGAmmount	= $row2->TotUsedAmmount;
					endforeach;
					print number_format($UseBGAmmount, $decFormat);
				}
				else
				{
					$UseBGAmmount		= 0;
					print $UseBGAmmount;
				}
			}
			else
			{
				$UseBGAmmount		= 0;
				print $UseBGAmmount;
			}
		}
		else
		{
			redirect('login');
		}
	}
		
	function getTheValueRemBG()
	{
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CB_CHEQNO	= $_GET['id'];
			//$CB_CHEQNO	= $this->url_encryption_helper->decode_url($CB_CHEQNO);
		
			$recCounrBG		= $this->m_bank_payment->count_all_BG($CB_CHEQNO);
			$getAmmountBG 	= $this->m_bank_payment->getAmmountBG($CB_CHEQNO)->result();
			
			$this->db->select('Display_Rows,decFormat');
			$resGlobal = $this->db->get('tglobalsetting')->result();
			foreach($resGlobal as $row) :
				$Display_Rows = $row->Display_Rows;
				$decFormat = $row->decFormat;
			endforeach;
	
			if($recCounrBG > 0)
			{
				foreach($getAmmountBG as $row) :
					$BGAmmount	= $row->BGAmmount;
				endforeach;
			}
			else
			{
				$BGAmmount		= 0;
			}
			
			// check Used BG Amount
			if($CB_CHEQNO != '')
			{
				$recUseCounrBG		= $this->m_bank_payment->count_all_UseBG($CB_CHEQNO);
				$getUseAmmountBG 	= $this->m_bank_payment->getAmmountUseBG($CB_CHEQNO)->result();
				if($recUseCounrBG > 0)
				{
					foreach($getUseAmmountBG as $row2) :
						$UseBGAmmount	= $row2->TotUsedAmmount;
					endforeach;
				}
				else
				{
					$UseBGAmmount		= 0;
				}
			}
			else
			{
				$UseBGAmmount	= 0;
			}
			
			$RemBGAmmount			= $BGAmmount - $UseBGAmmount;
			if($RemBGAmmount == 0)
			{
				print $RemBGAmmount;
			}
			else
			{
				print number_format($RemBGAmmount, $decFormat);
			}
		}
		else
		{
			redirect('login');
		}
	}
	
	function pall180c2cinv()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$SPLCODE	= $_GET['id'];
			$SPLCODE	= $this->url_encryption_helper->decode_url($SPLCODE);
			
			$data['title'] 		= $appName;
			$data['SPLCODE'] 	= $SPLCODE;
			
			$data['countINV'] 	= $this->m_bank_payment->count_all_INV($SPLCODE);
			$data['vwINV'] 		= $this->m_bank_payment->view_all_INV($SPLCODE)->result();
					
			$this->load->view('v_finance/v_bank_payment/v_select_inv', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function sgejbp0c07180851()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$CB_NUM	= $_GET['id'];
			$CB_NUM	= $this->url_encryption_helper->decode_url($CB_NUM);
			
			$data['title'] 		= $appName;
			$data['CB_NUM'] 	= $CB_NUM;
			
			$data['countGEJ'] 	= $this->m_bank_payment->count_all_GEJ();
			$data['vwGEJ'] 		= $this->m_bank_payment->view_all_GEJ()->result();
					
			$this->load->view('v_finance/v_bank_payment/v_select_gej', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function genCode() // OK
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$CB_TYPE	= $this->input->post('CB_TYPE');
		$PattCode	= $this->input->post('Pattern_Code');
		$PattLength	= $this->input->post('Pattern_Length');
		$useYear	= $this->input->post('useYear');
		$useMonth	= $this->input->post('useMonth');
		$useDate	= $this->input->post('useDate');
		
		$CBDate		= date('Y-m-d',strtotime($this->input->post('CBDate')));
		$year		= date('Y',strtotime($this->input->post('CBDate')));
		$month 		= (int)date('m',strtotime($this->input->post('CBDate')));
		$date 		= (int)date('d',strtotime($this->input->post('CBDate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_pr_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_bp_header
					WHERE Patt_Year = $year AND CB_TYPE = '$CB_TYPE'";
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
	
	function add_process() // USE
	{
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$CREATED 		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$JournalH_Code	= $this->input->post('CB_NUM');
			$CB_NUM	 		= $this->input->post('CB_NUM');
			$CB_CODE	 	= $this->input->post('CB_NUM');
			$PRJCODE	 	= $this->input->post('PRJCODE');
			$CB_CURRID	 	= $this->input->post('CB_CURRID');
			$CB_CURRCONV 	= $this->input->post('CB_CURRCONV');
			$Acc_ID	 		= $this->input->post('selAccount');
			$CB_DATE	 	= date('Y-m-d', strtotime($this->input->post('CB_DATE')));
			$CB_TYPE		= 'BP';
			$CB_PAYFOR	 	= $this->input->post('CB_PAYFOR');
			$CB_PAYEE	 	= $this->input->post('CB_PAYFOR');
			$CB_CHEQNO	 	= $this->input->post('CB_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$CB_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$CB_CHEQDAT		= $BGDate;
			$CB_DOCTYPE		= 'PINV';
			$CB_TOTAM	 	= $this->input->post('CB_TOTAM');
			$CB_TOTAM_PPn 	= $this->input->post('CB_TOTAM_PPn');
			$CB_NOTES	 	= $this->input->post('CB_NOTES');
			$CB_STAT		= $this->input->post('CB_STAT');	// default new = 1. New, 2. Confirm, 3.Approve
			$CB_APPSTAT		= 0;
			$CB_CREATER		= $DefEmp_ID;
			$CB_CREATED		= $CREATED;
			$Company_ID		= "NKE";
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$Patt_Year		= date('Y',strtotime($this->input->post('CB_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('CB_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('CB_DATE')));
			
			$inBankPay 		= array('JournalH_Code' => $CB_NUM,
									'CB_NUM' 		=> $CB_NUM,
									'CB_CODE' 		=> $CB_CODE,
									'PRJCODE' 		=> $PRJCODE,
									'CB_DATE' 		=> $CB_DATE,
									'CB_TYPE' 		=> $CB_TYPE,
									'CB_CURRID' 	=> $CB_CURRID,
									'CB_CURRCONV' 	=> $CB_CURRCONV,
									'Acc_ID' 		=> $Acc_ID,
									'CB_PAYFOR' 	=> $CB_PAYFOR,
									'CB_PAYEE' 		=> $CB_PAYEE,
									'CB_CHEQNO' 	=> $CB_CHEQNO,
									'CB_CHEQDAT' 	=> $CB_CHEQDAT,
									'CB_DOCTYPE' 	=> $CB_DOCTYPE,
									'CB_STAT' 		=> $CB_STAT,
									'CB_TOTAM' 		=> $CB_TOTAM,
									'CB_TOTAM_PPn' 	=> $CB_TOTAM_PPn,
									'CB_NOTES' 		=> $CB_NOTES,
									'CB_CREATER' 	=> $CB_CREATER,
									'CB_CREATED' 	=> $CB_CREATED,
									'Company_ID' 	=> $Company_ID,
									'Patt_Year' 	=> $Patt_Year,
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date' 	=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_bank_payment->add($inBankPay);
			
			foreach($_POST['data'] as $d)
			{
				/*$PRJCODE 		= $PRJCODE;
				$RR_Number 		= $RR_Number;
				$LPMCODE 		= $LPMCODE;
				$ITM_CODE 		= $d['ITM_CODE'];
				$ITM_QTY 		= $d['ITM_QTY'];
				$ITM_PRICE 		= $d['ITM_PRICE'];*/
				
				$this->db->insert('tbl_bp_detail',$d);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('CB_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $CB_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "CB",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_bp_header",	// TABLE NAME
										'KEY_NAME'		=> "CB_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "CB_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $CB_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_CB",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_CB_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_CB_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_CB_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_CB_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_CB_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_CB_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN144';
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
			
			$url	= site_url('c_finance/c_bp0c07180851/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_bp0c07180851/update_process');
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/getbp0c07180851/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$MenuCode 			= 'MN144';
			$data["MenuCode"] 	= 'MN144';
			$proj_Currency		= 'IDR';
			$data['countAcc'] 	= $this->m_bank_payment->count_all_Acc($proj_Currency);
			$data['vwAcc'] 		= $this->m_bank_payment->view_all_Acc($proj_Currency)->result();
			$data['countSPL'] 	= $this->m_bank_payment->count_all_SPL_up(); // ProjCode nya dihilangkan
			$countSPL 			= $this->m_bank_payment->count_all_SPL_up(); // ProjCode nya dihilangkan
			$data['vwSPL'] 		= $this->m_bank_payment->view_all_SPL_up()->result();
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$getpurreq 						= $this->m_bank_payment->get_CB_by_number($JournalH_Code)->row();
			$data['default']['CB_NUM'] 		= $getpurreq->CB_NUM;
			$data['default']['CB_CODE'] 	= $getpurreq->CB_CODE;
			$data['default']['CB_DATE']		= $getpurreq->CB_DATE;
			$data['default']['CB_TYPE'] 	= $getpurreq->CB_TYPE;
			$data['default']['CB_CURRID'] 	= $getpurreq->CB_CURRID;
			$data['default']['CB_CURRCONV'] = $getpurreq->CB_CURRCONV;
			$data['default']['Acc_ID'] 		= $getpurreq->Acc_ID;
			$data['default']['ACC_NUM'] 	= $getpurreq->Acc_ID;
			$data['default']['CB_PAYFOR'] 	= $getpurreq->CB_PAYFOR;
			$data['default']['CB_CHEQNO'] 	= $getpurreq->CB_CHEQNO;
			$data['CB_CHEQNO'] 				= $getpurreq->CB_CHEQNO;
			$data['default']['CB_DOCTYPE']	= $getpurreq->CB_DOCTYPE;
			$data['default']['CB_STAT']		= $getpurreq->CB_STAT;
			$data['default']['CB_TOTAM'] 	= $getpurreq->CB_TOTAM;
			$data['default']['CB_TOTAM_PPn']= $getpurreq->CB_TOTAM_PPn;
			$data['default']['CB_NOTES'] 	= $getpurreq->CB_NOTES;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN144';
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
			
			$this->load->view('v_finance/v_bank_payment/v_bank_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // USE
	{
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$CREATED 		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$JournalH_Code	= $this->input->post('CB_NUM');
			$CB_NUM	 		= $this->input->post('CB_NUM');
			$CB_CODE	 	= $this->input->post('CB_NUM');
			$PRJCODE	 	= $this->input->post('PRJCODE');
			$CB_CURRID	 	= $this->input->post('CB_CURRID');
			$CB_CURRCONV 	= $this->input->post('CB_CURRCONV');
			$Acc_ID	 		= $this->input->post('selAccount');
			$CB_DATE	 	= date('Y-m-d', strtotime($this->input->post('CB_DATE')));
			$CB_TYPE		= 'BP';
			$CB_PAYFOR	 	= $this->input->post('CB_PAYFOR');
			$CB_PAYEE	 	= $this->input->post('CB_PAYFOR');
			$CB_CHEQNO	 	= $this->input->post('CB_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$CB_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$CB_CHEQDAT		= $BGDate;
			$CB_DOCTYPE		= 'PINV';
			$CB_TOTAM	 	= $this->input->post('CB_TOTAM');
			$CB_TOTAM_PPn 	= $this->input->post('CB_TOTAM_PPn');
			$CB_NOTES	 	= $this->input->post('CB_NOTES');
			$CB_STAT		= $this->input->post('CB_STAT');
			$CB_APPSTAT		= 0;
			$CB_CREATER		= $DefEmp_ID;
			$CB_CREATED		= $CREATED;
			$Company_ID		= "NKE";
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$Patt_Year		= date('Y',strtotime($this->input->post('CB_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('CB_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('CB_DATE')));
			
			$inBankPay 		= array('CB_DATE' 		=> $CB_DATE,
									'CB_TYPE' 		=> $CB_TYPE,
									'CB_CURRID' 	=> $CB_CURRID,
									'CB_CURRCONV' 	=> $CB_CURRCONV,
									'Acc_ID' 		=> $Acc_ID,
									'CB_PAYFOR' 	=> $CB_PAYFOR,
									'CB_PAYEE' 		=> $CB_PAYEE,
									'CB_CHEQNO' 	=> $CB_CHEQNO,
									'CB_CHEQDAT' 	=> $CB_CHEQDAT,
									'CB_DOCTYPE' 	=> $CB_DOCTYPE,
									'CB_STAT' 		=> $CB_STAT,
									'CB_TOTAM' 		=> $CB_TOTAM,
									'CB_TOTAM_PPn' 	=> $CB_TOTAM_PPn,
									'CB_NOTES' 		=> $CB_NOTES,
									'CB_CREATER' 	=> $CB_CREATER,
									'CB_CREATED' 	=> $CB_CREATED,
									'Company_ID' 	=> $Company_ID,
									'Patt_Year' 	=> $Patt_Year,
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date' 	=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);									
			$this->m_bank_payment->update($JournalH_Code, $inBankPay);
			
			$this->m_bank_payment->deleteDetail($JournalH_Code);
			
			foreach($_POST['data'] as $d)
			{
				/*$PRJCODE 		= $PRJCODE;
				$RR_Number 		= $RR_Number;
				$LPMCODE 		= $LPMCODE;
				$ITM_CODE 		= $d['ITM_CODE'];
				$ITM_QTY 		= $d['ITM_QTY'];
				$ITM_PRICE 		= $d['ITM_PRICE'];*/
				
				$this->db->insert('tbl_bp_detail',$d);
			}
			
			if($CB_STAT == 3)
			{
				// UPDATE TO COA
				$this->m_bank_payment->updateCOA($JournalH_Code, $Acc_ID, $CB_TOTAM, $CB_TOTAM_PPn);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $CB_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "CB",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_bp_header",	// TABLE NAME
										'KEY_NAME'		=> "CB_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "CB_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $CB_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_CB",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_CB_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_CB_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_CB_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_CB_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_CB_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_CB_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN144';
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
			
			$url	= site_url('c_finance/c_bp0c07180851/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
 	function inb0c07180851() // OK
	{
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_bp0c07180851/inb0c07180851a/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function inb0c07180851a() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$appName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($appName);
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			$data['title'] 		= $appName;
			$data['appName'] 	= $appName;
			$data["MenuCode"] 	= 'MN145';
			//$data['addURL'] 	= site_url('c_finance/c_bp0c07180851/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/inb0c07180851a/?id='.$this->url_encryption_helper->encode_url($appName));
			//$data['form_action']= site_url('c_finance/c_bp0c07180851/add_process');
			$data["countPRJ"]	= $this->m_projectlist->count_all_project($EmpID);
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($EmpID)->result();
			
			$data["countBP"] 	= $this->m_bank_payment->count_all_BP_inb();
			$data['vwBP'] 		= $this->m_bank_payment->get_last_BP_inb()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN145';
				$TTR_CATEG		= 'APP-L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_finance/v_bank_payment/v_inb_bank_payment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function uinb0c07180851a()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_bp0c07180851/update_process_inb');
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/inb0c07180851a/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$MenuCode 			= 'MN145';
			$data["MenuCode"] 	= 'MN145';
			$proj_Currency		= 'IDR';
			$data['countAcc'] 	= $this->m_bank_payment->count_all_Acc($proj_Currency);
			$data['vwAcc'] 		= $this->m_bank_payment->view_all_Acc($proj_Currency)->result();
			$data['countSPL'] 	= $this->m_bank_payment->count_all_SPL(); // ProjCode nya dihilangkan
			$countSPL 			= $this->m_bank_payment->count_all_SPL(); // ProjCode nya dihilangkan
			$data['vwSPL'] 		= $this->m_bank_payment->view_all_SPL()->result();
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$getpurreq 						= $this->m_bank_payment->get_CB_by_number($JournalH_Code)->row();
			$data['default']['CB_NUM'] 		= $getpurreq->CB_NUM;
			$data['default']['CB_CODE'] 	= $getpurreq->CB_CODE;
			$data['default']['CB_DATE']		= $getpurreq->CB_DATE;
			$data['default']['CB_TYPE'] 	= $getpurreq->CB_TYPE;
			$data['default']['CB_CURRID'] 	= $getpurreq->CB_CURRID;
			$data['default']['CB_CURRCONV'] = $getpurreq->CB_CURRCONV;
			$data['default']['Acc_ID'] 		= $getpurreq->Acc_ID;
			$data['default']['ACC_NUM'] 	= $getpurreq->Acc_ID;
			$data['default']['CB_PAYFOR'] 	= $getpurreq->CB_PAYFOR;
			$data['default']['CB_CHEQNO'] 	= $getpurreq->CB_CHEQNO;
			$data['CB_CHEQNO'] 				= $getpurreq->CB_CHEQNO;
			$data['default']['CB_DOCTYPE']	= $getpurreq->CB_DOCTYPE;
			$data['default']['CB_STAT']		= $getpurreq->CB_STAT;
			$data['default']['CB_TOTAM'] 	= $getpurreq->CB_TOTAM;
			$data['default']['CB_TOTAM_PPn']= $getpurreq->CB_TOTAM_PPn;
			$data['default']['CB_NOTES'] 	= $getpurreq->CB_NOTES;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN145';
				$TTR_CATEG		= 'APP-U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK	
			
			$this->load->view('v_finance/v_bank_payment/v_inb_bank_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
				date_default_timezone_set("Asia/Jakarta");
				
				$CREATED 		= date('Y-m-d H:i:s');
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				
				$JournalH_Code	= $this->input->post('CB_NUM');
				$CB_NUM	 		= $this->input->post('CB_NUM');
				$CB_CODE	 	= $this->input->post('CB_NUM');
				$CB_CURRID	 	= $this->input->post('CB_CURRID');
				$CB_CURRCONV 	= $this->input->post('CB_CURRCONV');
				$Acc_ID	 		= $this->input->post('selAccount');
				$CB_DATE	 	= date('Y-m-d', strtotime($this->input->post('CB_DATE')));
				$CB_TYPE		= 'BP';
				$CB_PAYFOR	 	= $this->input->post('CB_PAYFOR');
				$CB_PAYEE	 	= $this->input->post('CB_PAYFOR');
				$CB_CHEQNO	 	= $this->input->post('CB_CHEQNO');
					$BGDate		= '';
					$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$CB_CHEQNO'";
					$resBG		= $this->db->query($sqlBG)->result();
					foreach($resBG as $rowBG) :
						$BGDate = $rowBG->BGDate;
					endforeach;
				$CB_CHEQDAT		= $BGDate;
				$CB_DOCTYPE		= 'PINV';
				$CB_TOTAM	 	= $this->input->post('CB_TOTAM');
				$CB_TOTAM_PPn 	= $this->input->post('CB_TOTAM_PPn');
				$CB_NOTES	 	= $this->input->post('CB_NOTES');
				$CB_STAT		= $this->input->post('CB_STAT');
				$CB_APPSTAT		= 0;
				$CB_CREATER		= $DefEmp_ID;
				$CB_CREATED		= $CREATED;
				$Company_ID		= "NKE";
				$Patt_Number	= $this->input->post('Patt_Number');
				
				$Patt_Year		= date('Y',strtotime($this->input->post('CB_DATE')));
				$Patt_Month		= date('m',strtotime($this->input->post('CB_DATE')));
				$Patt_Date		= date('d',strtotime($this->input->post('CB_DATE')));
				
				
				$inBankPay 		= array('CB_STAT' 		=> $CB_STAT,
										'CB_UPDATER' 	=> $DefEmp_ID,
										'CB_LASTUPD' 	=> date('Y-m-d H:i:s'));									
				$this->m_bank_payment->update($JournalH_Code, $inBankPay);
				
				if($CB_STAT == 3)
				{
					// UPDATE TO COA FOR BANK
					//***$this->m_bank_payment->updateCOA($JournalH_Code, $Acc_ID, $CB_TOTAM, $CB_TOTAM_PPn);
					
					// UPDATE INV STAT
					$TOT_AMOUNT		= 0;
					$TOT_AMOUNT_PPn	= 0;
					$DocumentNoC	= '';
					foreach($_POST['data'] as $d)
					{
						$DocumentNo 	= $d['DocumentNo'];
						$DocumentNoC	= "$DocumentNoC;$DocumentNo";
						$Amount 		= $d['Amount'];
						$Amount_PPn		= $d['Amount_PPn'];
						$TOT_AMOUNT		= $TOT_AMOUNT + $Amount;
						$TOT_AMOUNT_PPn	= $TOT_AMOUNT_PPn + $Amount_PPn;
						
						$this->m_bank_payment->updatePINV($DocumentNo, $Amount, $Amount_PPn);
					}
				
					// BUATKAN JURNAL PEMBALIK ATAS HUTANG
						$TAXCODE1		= '';
						if($TOT_AMOUNT_PPn > 0)
							$TAXCODE1	= 'TAX01';
							
					// START : JOURNAL HEADER						
						$JournalH_Code	= $JournalH_Code;
						$JournalType	= 'BP';
						$JournalH_Date	= $CB_DATE;
						$Company_ID		= 'NKE';
						$DOCSource		= $DocumentNo;
						$LastUpdate		= $CREATED;
						$WH_CODE		= 'KTR';
						$Refer_Number	= $DocumentNoC;
						$RefType		= 'BP';
						$PRJCODE		= '';
						
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
				
					// START : JOURNAL DETAIL
						// JOURNAL DETAIL PADA SAAT PAYMENT HANYA TERBENTUK SEKALI PUTARAN
						// GET PRJCODE
							$INV_NUM	= '';
							$PRJCODE	= '';
							$sqlPRJ		= "SELECT INV_NUM, PRJCODE FROM tbl_pinv_header WHERE INV_NUM = '$DocumentNo'";
							$resPRJ		= $this->db->query($sqlPRJ)->result();
							foreach($resPRJ as $rowPRJ) :
								$INV_NUM = $rowPRJ->INV_NUM;
								$PRJCODE = $rowPRJ->PRJCODE;
							endforeach;
					
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BP';
							$JournalH_Date	= $CB_DATE;
							$Company_ID		= 'NKE';
							$Currency_ID	= 'IDR';
							$DOCSource		= $DocumentNo;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $INV_NUM;
							$RefType		= 'BP';
							$JSource		= 'PINV';
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= $DocumentNo;
							$ACC_ID 		= $Acc_ID;
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $TOT_AMOUNT;
							$ITM_DISC 		= '';
							$TAXCODE1 		= $TAXCODE1;
							$TAXPRICE1		= $TOT_AMOUNT_PPn;
						
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
												'TRANS_CATEG' 		=> 'BP',				// BP = BANK PAYMENT
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
					// END : JOURNAL DETAIL
				}
				
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $CB_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "CB",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_bp_header",	// TABLE NAME
											'KEY_NAME'		=> "CB_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "CB_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $CB_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_CB",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_CB_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_CB_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_CB_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_CB_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_CB_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_CB_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= '';
					$TTR_REFDOC		= $JournalH_Code;
					$MenuCode 		= 'MN145';
					$TTR_CATEG		= 'APP-UP';
					
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
			
			$url	= site_url('c_finance/c_bp0c07180851/inb0c07180851/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$JournalH_Code		= $_GET['id'];
		$JournalH_Code		= $this->url_encryption_helper->decode_url($JournalH_Code);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getpurreq 						= $this->m_bank_payment->get_CB_by_number($JournalH_Code)->row();
			$data['default']['CB_NUM'] 		= $getpurreq->CB_NUM;
			$data['default']['CB_CODE'] 	= $getpurreq->CB_CODE;
			$data['default']['CB_DATE']		= $getpurreq->CB_DATE;
			$data['default']['CB_TYPE'] 	= $getpurreq->CB_TYPE;
			$data['default']['CB_CURRID'] 	= $getpurreq->CB_CURRID;
			$data['default']['CB_CURRCONV'] = $getpurreq->CB_CURRCONV;
			$data['default']['Acc_ID'] 		= $getpurreq->Acc_ID;
			$data['default']['CB_PAYFOR'] 	= $getpurreq->CB_PAYFOR;
			$data['default']['CB_CHEQNO'] 	= $getpurreq->CB_CHEQNO;
			$data['CB_CHEQNO'] 				= $getpurreq->CB_CHEQNO;
			$data['default']['CB_DOCTYPE']	= $getpurreq->CB_DOCTYPE;
			$data['default']['CB_STAT']		= $getpurreq->CB_STAT;
			$data['default']['CB_TOTAM'] 	= $getpurreq->CB_TOTAM;
			$data['default']['CB_TOTAM_PPn']= $getpurreq->CB_TOTAM_PPn;
			$data['default']['CB_NOTES'] 	= $getpurreq->CB_NOTES;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
							
			$this->load->view('v_finance/v_bank_payment/v_print_bpayment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}