<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 20 Desember 2017
 * File Name	= C_bank_receipt.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_bank_receipt extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_bank_receipt/get_all_BR/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function get_all_BR() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
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
			$data["MenuCode"] 	= 'MN148';
			$data['addURL'] 	= site_url('c_finance/c_bank_receipt/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 	= site_url('c_finance/c_bank_receipt/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action']= site_url('c_finance/c_bank_receipt/add_process');
			$data["countPRJ"]	= $this->m_projectlist->count_all_project($EmpID);
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($EmpID)->result();
			
			$data["countBP"] 	= $this->m_bank_receipt->count_all_BP();
			$data['vwBP'] 		= $this->m_bank_receipt->get_last_BP()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'Bank-Receipt';
				$MenuCode 		= 'MN148';
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
			
			$this->load->view('v_finance/v_bank_receipt/v_bank_receipt', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
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
			$data['form_action']= site_url('c_finance/c_bank_receipt/add_process');
			$data['backURL'] 	= site_url('c_finance/c_bank_receipt/get_all_BR/?id='.$this->url_encryption_helper->encode_url($appName));			
			
			$proj_Currency		= 'IDR';
			$data['countAcc'] 	= $this->m_bank_receipt->count_all_Acc($proj_Currency);
			$data['vwAcc'] 		= $this->m_bank_receipt->view_all_Acc($proj_Currency)->result();
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN148';
			$data["MenuCode"] 	= 'MN148';
			$data['vwDocPatt'] 	= $this->m_bank_receipt->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'Bank-Receipt';
				$MenuCode 		= 'MN148';
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
			
			$this->load->view('v_finance/v_bank_receipt/v_bank_receipt_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallitem()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$collID	= $_GET['id'];
			$collID	= $this->url_encryption_helper->decode_url($collID);
			
			$splitCode 	= explode("~", $collID);
			$BR_RECTYPE	= $splitCode[0];
			$BR_PAYFROM	= $splitCode[1];
			
			$data['title'] 		= $appName;
			
			if($BR_RECTYPE == 'PRJ')
			{
				$data['SPLCODE'] 	= $SPLCODE;			
				$data['countINV'] 	= $this->m_bank_receipt->count_all_INV_OWN($BR_PAYFROM);
				$data['vwINV'] 		= $this->m_bank_receipt->view_all_INV_OWN($BR_PAYFROM)->result();
			}
			else
			{
			}
					
			$this->load->view('v_finance/v_bank_payment/v_select_inv', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function genCode() // OK
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$BR_TYPE	= $this->input->post('BR_TYPE');
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
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_br_header
					WHERE Patt_Year = $year AND BR_TYPE = '$BR_TYPE'";
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
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
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
			
			$JournalH_Code	= $this->input->post('BR_NUM');
			$BR_NUM	 		= $this->input->post('BR_NUM');
			$BR_CODE	 	= $this->input->post('BR_NUM');
			$PRJCODE	 	= $this->input->post('PRJCODE');
			$BR_CURRID	 	= $this->input->post('BR_CURRID');
			$BR_CURRCONV 	= $this->input->post('BR_CURRCONV');
			$Acc_ID	 		= $this->input->post('selAccount');
			$BR_DATE	 	= date('Y-m-d', strtotime($this->input->post('BR_DATE')));
			$BR_TYPE		= 'BR';
			$BR_RECTYPE	 	= $this->input->post('BR_RECTYPE');
			$BR_PAYFROM	 	= $this->input->post('BR_PAYFROM');
			$BR_PAYEE	 	= $this->input->post('BR_PAYFROM');
			$BR_CHEQNO	 	= $this->input->post('BR_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$BR_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$BR_CHEQDAT		= $BGDate;
			$BR_DOCTYPE		= 'PINV';
			$BR_TOTAM	 	= $this->input->post('BR_TOTAM');
			$BR_TOTAM_PPn 	= $this->input->post('BR_TOTAM_PPn');
			$BR_NOTES	 	= $this->input->post('BR_NOTES');
			$BR_STAT		= 1;	// default new = 1. New, 2. Confirm, 3.Approve
			$BR_APPSTAT		= 0;
			$BR_CREATER		= $DefEmp_ID;
			$BR_CREATED		= $CREATED;
			$Company_ID		= "NKE";
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$Patt_Year		= date('Y',strtotime($this->input->post('BR_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('BR_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('BR_DATE')));
			
			$inBankPay 		= array('JournalH_Code' => $BR_NUM,
									'BR_NUM' 		=> $BR_NUM,
									'BR_CODE' 		=> $BR_CODE,
									'PRJCODE' 		=> $PRJCODE,
									'BR_DATE' 		=> $BR_DATE,
									'BR_TYPE' 		=> $BR_TYPE,
									'BR_RECTYPE'	=> $BR_RECTYPE,
									'BR_CURRID' 	=> $BR_CURRID,
									'BR_CURRCONV' 	=> $BR_CURRCONV,
									'Acc_ID' 		=> $Acc_ID,
									'BR_PAYFROM' 	=> $BR_PAYFROM,
									'BR_PAYEE' 		=> $BR_PAYEE,
									'BR_CHEQNO' 	=> $BR_CHEQNO,
									'BR_CHEQDAT' 	=> $BR_CHEQDAT,
									'BR_DOCTYPE' 	=> $BR_DOCTYPE,
									'BR_STAT' 		=> $BR_STAT,
									'BR_TOTAM' 		=> $BR_TOTAM,
									'BR_TOTAM_PPn' 	=> $BR_TOTAM_PPn,
									'BR_NOTES' 		=> $BR_NOTES,
									'BR_CREATER' 	=> $BR_CREATER,
									'BR_CREATED' 	=> $BR_CREATED,
									'Company_ID' 	=> $Company_ID,
									'Patt_Year' 	=> $Patt_Year,
									'Patt_Year' 	=> $Patt_Month,
									'Patt_Year' 	=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_bank_receipt->add($inBankPay);
			
			foreach($_POST['data'] as $d)
			{
				/*$PRJCODE 		= $PRJCODE;
				$RR_Number 		= $RR_Number;
				$LPMCODE 		= $LPMCODE;
				$ITM_CODE 		= $d['ITM_CODE'];
				$ITM_QTY 		= $d['ITM_QTY'];
				$ITM_PRICE 		= $d['ITM_PRICE'];*/
				
				$this->db->insert('tbl_br_detail',$d);
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $BR_NUM;
				$MenuCode 		= 'MN148';
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
			
			$url	= site_url('c_finance/c_bank_receipt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
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
			$data['form_action']= site_url('c_finance/c_bank_receipt/update_process');
			$data['backURL'] 	= site_url('c_finance/c_bank_receipt/get_all_BR/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$MenuCode 			= 'MN148';
			$data["MenuCode"] 	= 'MN148';
			$proj_Currency		= 'IDR';
			$data['countAcc'] 	= $this->m_bank_receipt->count_all_Acc($proj_Currency);
			$data['vwAcc'] 		= $this->m_bank_receipt->view_all_Acc($proj_Currency)->result();
			$data['countSPL'] 	= $this->m_bank_receipt->count_all_SPL(); // ProjCode nya dihilangkan
			$countSPL 			= $this->m_bank_receipt->count_all_SPL(); // ProjCode nya dihilangkan
			$data['vwSPL'] 		= $this->m_bank_receipt->view_all_SPL()->result();
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$getpurreq 						= $this->m_bank_receipt->get_BR_by_number($JournalH_Code)->row();
			$data['default']['BR_NUM'] 		= $getpurreq->BR_NUM;
			$data['default']['BR_CODE'] 	= $getpurreq->BR_CODE;
			$data['default']['BR_DATE']		= $getpurreq->BR_DATE;
			$data['default']['BR_TYPE'] 	= $getpurreq->BR_TYPE;
			$data['default']['BR_RECTYPE'] 	= $getpurreq->BR_RECTYPE;
			$data['default']['BR_CURRID'] 	= $getpurreq->BR_CURRID;
			$data['default']['BR_CURRCONV'] = $getpurreq->BR_CURRCONV;
			$data['default']['Acc_ID'] 		= $getpurreq->Acc_ID;
			$data['default']['BR_PAYFROM'] 	= $getpurreq->BR_PAYFROM;
			$data['default']['BR_CHEQNO'] 	= $getpurreq->BR_CHEQNO;
			$data['BR_CHEQNO'] 				= $getpurreq->BR_CHEQNO;
			$data['default']['BR_DOCTYPE']	= $getpurreq->BR_DOCTYPE;
			$data['default']['BR_STAT']		= $getpurreq->BR_STAT;
			$data['default']['BR_TOTAM'] 	= $getpurreq->BR_TOTAM;
			$data['default']['BR_TOTAM_PPn']= $getpurreq->BR_TOTAM_PPn;
			$data['default']['BR_NOTES'] 	= $getpurreq->BR_NOTES;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN148';
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
			
			$this->load->view('v_finance/v_bank_receipt/v_bank_receipt_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // USE
	{
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
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
			
			$JournalH_Code	= $this->input->post('BR_NUM');
			$BR_NUM	 		= $this->input->post('BR_NUM');
			$BR_CODE	 	= $this->input->post('BR_NUM');
			$BR_CURRID	 	= $this->input->post('BR_CURRID');
			$BR_CURRCONV 	= $this->input->post('BR_CURRCONV');
			$Acc_ID	 		= $this->input->post('selAccount');
			$BR_DATE	 	= date('Y-m-d', strtotime($this->input->post('BR_DATE')));
			$BR_TYPE		= 'BR';
			$BR_RECTYPE	 	= $this->input->post('BR_RECTYPE');
			$BR_PAYFROM	 	= $this->input->post('BR_PAYFROM');
			$BR_PAYEE	 	= $this->input->post('BR_PAYFROM');
			$BR_CHEQNO	 	= $this->input->post('BR_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$BR_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$BR_CHEQDAT		= $BGDate;
			$BR_DOCTYPE		= 'PINV';
			$BR_TOTAM	 	= $this->input->post('BR_TOTAM');
			$BR_TOTAM_PPn 	= $this->input->post('BR_TOTAM_PPn');
			$BR_NOTES	 	= $this->input->post('BR_NOTES');
			$BR_STAT		= $this->input->post('BR_STAT');
			$BR_APPSTAT		= 0;
			$BR_CREATER		= $DefEmp_ID;
			$BR_CREATED		= $CREATED;
			$Company_ID		= "NKE";
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$Patt_Year		= date('Y',strtotime($this->input->post('BR_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('BR_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('BR_DATE')));
			
			$inBankPay 		= array('BR_DATE' 		=> $BR_DATE,
									'BR_RECTYPE' 	=> $BR_RECTYPE,
									'BR_TYPE' 		=> $BR_TYPE,
									'BR_CURRID' 	=> $BR_CURRID,
									'BR_CURRCONV' 	=> $BR_CURRCONV,
									'Acc_ID' 		=> $Acc_ID,
									'BR_PAYFROM' 	=> $BR_PAYFROM,
									'BR_PAYEE' 		=> $BR_PAYEE,
									'BR_CHEQNO' 	=> $BR_CHEQNO,
									'BR_CHEQDAT' 	=> $BR_CHEQDAT,
									'BR_DOCTYPE' 	=> $BR_DOCTYPE,
									'BR_STAT' 		=> $BR_STAT,
									'BR_TOTAM' 		=> $BR_TOTAM,
									'BR_TOTAM_PPn' 	=> $BR_TOTAM_PPn,
									'BR_NOTES' 		=> $BR_NOTES,
									'BR_CREATER' 	=> $BR_CREATER,
									'BR_CREATED' 	=> $BR_CREATED,
									'Company_ID' 	=> $Company_ID,
									'Patt_Year' 	=> $Patt_Year,
									'Patt_Year' 	=> $Patt_Month,
									'Patt_Year' 	=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);									
			$this->m_bank_receipt->update($JournalH_Code, $inBankPay);
			
			$this->m_bank_receipt->deleteDetail($JournalH_Code);
			
			foreach($_POST['data'] as $d)
			{
				/*$PRJCODE 		= $PRJCODE;
				$RR_Number 		= $RR_Number;
				$LPMCODE 		= $LPMCODE;
				$ITM_CODE 		= $d['ITM_CODE'];
				$ITM_QTY 		= $d['ITM_QTY'];
				$ITM_PRICE 		= $d['ITM_PRICE'];*/
				
				$this->db->insert('tbl_br_detail',$d);
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN148';
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
			
			if($BR_STAT == 3)
			{
				// UPDATE TO COA
				$this->m_bank_receipt->updateCOA($JournalH_Code, $Acc_ID, $BR_TOTAM, $BR_TOTAM_PPn);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url	= site_url('c_finance/c_bank_receipt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
}