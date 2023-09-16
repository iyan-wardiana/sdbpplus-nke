<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 November 2018
 * File Name	= C_pi180c23o_pn.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_pi180c23o_pn extends CI_Controller  
{
 	function index() // G
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_purchase/c_pi180c23o_pn/ix180c23/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function ix180c23() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Faktur Opname";
			}
			else
			{
				$data["h1_title"] 	= "Opname Invoice";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN135';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_pi180c23o_pn/gall180c23inv/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function gall180c23inv() // G
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Faktur Opname";
				$data['h2_title']	= 'Opname';
			}
			else
			{
				$data["h1_title"] 	= "Opname Invoice";
				$data['h2_title']	= 'Opname';
			}
			
			$data['PRJCODE']	= $PRJCODE;
			$data["MenuCode"] 	= 'MN135';
			$data['backURL'] 	= site_url('c_purchase/c_pi180c23o_pn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$num_rows 			= $this->m_opn_inv->count_all_pinv($PRJCODE);
			$data["reccount"]	= $num_rows;	 
			$data['viewpinv']	= $this->m_opn_inv->get_all_pinv($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN135';
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
			
			$this->load->view('v_purchase/v_opn_inv/v_opninv_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180c23dd() // G - INVOICING
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			$data['form_action']	= site_url('c_purchase/c_pi180c23o_pn/add_process');
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_opn_inv->count_all_vend($PRJCODE);
			$data['vwSUPL'] 		= $this->m_opn_inv->view_all_vend($PRJCODE)->result();
			
			$MenuCode 				= 'MN135';
			$data["MenuCode"] 		= 'MN135';
			$data['viewDocPattern'] = $this->m_opn_inv->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN135';
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
	
			$this->load->view('v_purchase/v_opn_inv/v_opninv_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK - ADD INVOICING PROCESS
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			$INV_NUM 		= $this->input->post('INV_NUM');
			$INV_CODE 		= $this->input->post('INV_CODE');
			$INV_DATE		= date('Y-m-d',strtotime($this->input->post('INV_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$Patt_Year		= date('Y',strtotime($this->input->post('INV_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN135';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$INV_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$INV_DUEDATE	= date('Y-m-d',strtotime($this->input->post('INV_DUEDATE')));
			$INV_CODE		= $this->input->post('INV_CODE');
			$INV_TYPE		= $this->input->post('INV_TYPE');
			$INV_CATEG		= $this->input->post('INV_CATEG');
			$PO_NUM			= $this->input->post('PO_NUM');
			$IR_NUM			= $this->input->post('Ref_Number'); // TTK NUMBER
			$SPLCODE 		= $this->input->post('SPLCODE');
			$INV_CURRENCY	= 'IDR';
			$INV_TAXCURR	= 'IDR';
			$DP_NUM			= $this->input->post('DP_NUM');
			$DP_AMOUNT		= $this->input->post('DP_AMOUNT');
			$INV_AMOUNT		= $this->input->post('INV_AMOUNT');
			$INV_LISTTAXVAL	= $this->input->post('INV_LISTTAXVAL');
			$INV_PPH		= $this->input->post('INV_PPH');
			$INV_PPHVAL		= $this->input->post('INV_PPHVAL');
			$INV_TERM		= $this->input->post('INV_TERM');
			$INV_STAT 		= $this->input->post('INV_STAT');
			$INV_PAYSTAT	= 'NP';
			$COMPANY_ID		= $COMPANY_ID;
			$VENDINV_NUM	= $this->input->post('VENDINV_NUM');
			$INV_NOTES		= $this->input->post('INV_NOTES');
			$REF_NOTES		= $this->input->post('REF_NOTES');
			$CREATED		= date('Y-m-d H:i:s');
			$CREATER		= $DefEmp_ID;
			
			$INV_AMOUNTTOT	= $INV_AMOUNT + $INV_LISTTAXVAL - $INV_PPHVAL - $DP_AMOUNT;
			
			$TAXCODE1		= "";
			if($INV_LISTTAXVAL > 0)
				$TAXCODE1	= "TAX01";
			
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
			
			//GET SUPPLIER CATEG
				$SPLCAT		= '';
				$sqlSPLC	= "SELECT SPLCAT FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
				$resSPLC	= $this->db->query($sqlSPLC)->result();
				foreach($resSPLC as $rowSPLC) :
					$SPLCAT = $rowSPLC->SPLCAT;
				endforeach;
			
			$s = strtotime($INV_DATE);
			$e = strtotime($INV_DUEDATE);
			
			$INV_TERM 	= ($e - $s)/ (24 * 3600);
								
			$insertINV 	= array('INV_NUM' 			=> $INV_NUM,
								'INV_CODE'			=> $INV_CODE,
								'INV_TYPE'			=> $INV_TYPE,
								'INV_CATEG'			=> $INV_CATEG,
								'PO_NUM'			=> $PO_NUM,
								'IR_NUM'			=> $IR_NUM,
								'PRJCODE'			=> $PRJCODE,
								'INV_DATE'			=> $INV_DATE,
								'INV_DUEDATE'		=> $INV_DUEDATE,
								'SPLCODE'			=> $SPLCODE,
								'DP_NUM'			=> $DP_NUM,
								'DP_AMOUNT'			=> $DP_AMOUNT,
								'INV_CURRENCY'		=> $INV_CURRENCY,
								'INV_TAXCURR'		=> $INV_TAXCURR,
								'INV_AMOUNT'		=> $INV_AMOUNTTOT,
								'INV_AMOUNT_BASE'	=> $INV_AMOUNTTOT,
								'INV_LISTTAXVAL'	=> $INV_LISTTAXVAL,
								'INV_PPH'			=> $INV_PPH,
								'INV_PPHVAL'		=> $INV_PPHVAL,
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
			$this->m_opn_inv->add($insertINV);			
			
			// Pembuatan Journal Header
				if($INV_STAT == 3)
				{
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $INV_NUM;
						$JournalType	= 'PINV';
						$JournalH_Date	= $INV_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $IR_NUM;
						$LastUpdate		= $CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $PO_NUM;
						$RefType		= 'TTK';
						$PRJCODE		= $PRJCODE;
						$Journal_Amount	= $INV_AMOUNTTOT;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Date' 	=> $JournalH_Date,
											'Company_ID' 		=> $comp_init,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE,
											'Journal_Amount'	=> $Journal_Amount);
						$this->m_journal->createJournalH($JournalH_Code, $parameters);
					// END : JOURNAL HEADER
					
					// UPDATE DP HEADER
						if($DP_NUM != '')
						{
							$updDP 	= array('DP_AMOUNT_USED' 	=> $DP_AMOUNT);
							$this->m_opn_inv->updateDP($DP_NUM, $updDP);
						}
				
					// Pada saat pembuatan Faktur Pembelian, hanya mencatat
					/*
						Hutang blm difaktur		xxxx				(Jika ada. Karena tidak ada, maka diabaikan)
						PPn Masukan 			xxxx
							Hutang Supplier				xxxx
					*/
					// START : JOURNAL DETAIL DEBIT - KHUSUS PPn Masukan
						if($TAXCODE1 == 'TAX01')
						{
							$JournalH_Code	= $INV_NUM;
							$JournalType	= 'PINV2';
							$JournalH_Date	= $INV_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $IR_NUM;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PO_NUM;
							$RefType		= 'TTK';
							$JSource		= 'PINV2';
							$PRJCODE		= $PRJCODE;
							$Notes			= "PPn Masukan";
							
							$ITM_CODE 		= $INV_NUM;
							$ACC_ID 		= '';
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $INV_LISTTAXVAL;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PINV2~$SPLCAT";									// PERHATIKAN DI SINI. PENTING
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'Notes' 			=> $Notes);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						}
					// START : JOURNAL DETAIL DEBIT - KHUSUS PPn Masukan
				}
				
			// Pembuatan Journal Detail
				if($INV_TYPE == 0)
				{
					foreach($_POST['data1'] as $d)
					{
						$d['INV_NUM']	= $INV_NUM;
						$this->db->insert('tbl_pinv_detail',$d);
						
						// Pembuatan Journal Detail antara Invoice dengan lawannya
						if($INV_STAT == 3)
						{					
							$this->load->model('m_journal/m_journal', '', TRUE);
						
							$JournalH_Code	= $INV_NUM;
							$JournalType	= 'PINV';
							$JournalH_Date	= $INV_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $IR_NUM;
							$LastUpdate		= $UPDATER;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PO_NUM;
							$RefType		= 'TTK';
							$JSource		= 'PINV';
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= $d['ITM_CODE'];
							$ACC_ID 		= $d['ACC_ID'];
							$ITM_UNIT 		= $d['ITM_UNIT'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_UNITP'];
							$ITM_DISC 		= $d['ITM_DISC'];
							//$TAXCODE1 	= $d['TAXCODE1'];
							//$TAXPRICE1	= $d['TAX_AMOUNT_PPn1'];
							$TAXCODE1 		= "";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PINV~$SPLCAT";
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
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
				}
				else
				{
					// Pembuatan Journal Detail antara Invoice dengan lawannya
					if($INV_STAT == 3)
					{					
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $INV_NUM;
						$JournalType	= 'PINV';
						$JournalH_Date	= $INV_DATE;
						$Company_ID		= $comp_init;
						$Currency_ID	= 'IDR';
						$DOCSource		= $IR_NUM;
						$LastUpdate		= $CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $PO_NUM;
						$RefType		= 'TTK';
						$JSource		= 'PINV';
						$PRJCODE		= $PRJCODE;
						
						$ITM_CODE 		= '';
						$ACC_ID 		= '';
						$ITM_UNIT 		= 'LS';
						$ITM_QTY 		= 1;
						$ITM_PRICE 		= $INV_AMOUNT;
						$ITM_DISC 		= 0;
						$TAXCODE1 		= $TAXCODE1;
						$TAXPRICE1		= $INV_LISTTAXVAL;
						$TRANS_CATEG 	= "PINVD~$SPLCAT";
						
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
											'TRANS_CATEG' 		=> "PINVD",			// PINV = PURCHASE INVOICE
											'ITM_CODE' 			=> $ITM_CODE,
											'ACC_ID' 			=> $ACC_ID,
											'ITM_UNIT' 			=> $ITM_UNIT,
											'ITM_QTY' 			=> $ITM_QTY,
											'ITM_PRICE' 		=> $ITM_PRICE,
											'ITM_DISC' 			=> $ITM_DISC,
											'TAXCODE1' 			=> $TAXCODE1,
											'TAXPRICE1' 		=> $TAXPRICE1);												
						//$this->m_journal->createJournalD($JournalH_Code, $parameters);
					}
				}
			
			// START : JOURNAL DETAIL - KHUSUS PPh
				if($INV_PPH != '')
				{
					$this->load->model('m_journal/m_journal', '', TRUE);
					// START : JOURNAL DETAIL -- KHUSUS PPH (JIKA ADA)
						$JournalH_Code	= $INV_NUM;
						$JournalType	= 'PINV3';
						$JournalH_Date	= $INV_DATE;
						$Company_ID		= $comp_init;
						$Currency_ID	= 'IDR';
						$DOCSource		= $IR_NUM;
						$LastUpdate		= date('Y-m-d H:i:s');
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $PO_NUM;
						$RefType		= 'TTK';
						$JSource		= 'PINV3';
						$PRJCODE		= $PRJCODE;
						
						$ITM_CODE 		= $INV_NUM;
						$ACC_ID 		= '';
						$ITM_UNIT 		= '';
						$ITM_QTY 		= 1;
						$ITM_PRICE 		= $INV_PPHVAL;
						$ITM_DISC 		= 0;
						$TAXCODE1 		= "";
						$TAXPRICE1		= 0;
						
						$TRANS_CATEG 	= "PINV3~$SPLCAT";
						
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
											'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
											'ITM_CODE' 			=> $ITM_CODE,
											'ACC_ID' 			=> $ACC_ID,
											'ITM_UNIT' 			=> $ITM_UNIT,
											'ITM_QTY' 			=> $ITM_QTY,
											'ITM_PRICE' 		=> $ITM_PRICE,
											'ITM_DISC' 			=> $ITM_DISC,
											'TAXCODE1' 			=> $TAXCODE1,
											'TAXPRICE1' 		=> $TAXPRICE1,
											'PPhTax'			=> $INV_PPH,
											'PPhAmount'			=> $INV_PPHVAL);												
						$this->m_journal->createJournalD($JournalH_Code, $parameters);
					// START : JOURNAL DETAIL
				}
			// END : JOURNAL DETAIL - KHUSUS PPh
			
			// Update status PO dan RR bahwa sudah dibuatkan invoice
			if($INV_STAT == 3)
			{
				$parameters = array(
					'INV_STAT' 	=> $INV_STAT,
					'PO_NUM' 	=> $PO_NUM,
					'IR_NUM' 	=> $IR_NUM,
					'PRJCODE' 	=> $PRJCODE
				);
				$this->m_opn_inv->updatePO_RR($INV_NUM, $parameters);
				
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
				
				// PEMBUATAN JURNAL PEMBAYARAN DARI DP JIKA DP_AMOUNT > 0
				if($DP_AMOUNT > 0)
				{
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $DP_NUM;
						$JournalType	= 'DPP';
						$JournalH_Date	= $INV_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $INV_NUM;
						$LastUpdate		= $CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $PO_NUM;
						$RefType		= 'PINV';
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
					
					// START : JOURNAL DETAIL
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $DP_NUM;
						$JournalType	= 'DP';
						$JournalH_Date	= $INV_DATE;
						$Company_ID		= $comp_init;
						$Currency_ID	= 'IDR';
						$DOCSource		= $INV_NUM;
						$LastUpdate		= $CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $IR_NUM;
						$RefType		= 'PINV';
						$JSource		= 'PINV';
						$PRJCODE		= $PRJCODE;
						
						$ITM_CODE 		= '';
						$ACC_ID 		= '';
						$ITM_UNIT 		= 'LS';
						$ITM_QTY 		= 1;
						$ITM_PRICE 		= $DP_AMOUNT;
						$ITM_DISC 		= 0;
						$TAXCODE1 		= '';
						$TAXPRICE1		= 0;
						$TRANS_CATEG 	= "DPP~$SPLCAT";		// DPP DP PAYMENT
						
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
											'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
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
				$MenuCode 		= 'MN135';
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
			
			$url			= site_url('c_purchase/c_pi180c23o_pn/gall180c23inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function popupall_IR() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
		
		$data['countAllIR'] 	= $this->m_opn_inv->count_all_IR($SPLCODE, $PRJCODE);
		$data['viewAllIR'] 		= $this->m_opn_inv->viewAllIR($SPLCODE, $PRJCODE)->result();
				
		$this->load->view('v_purchase/v_opn_inv/v_opninv_sel_ir', $data);
	}
	
	function update() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			$INV_CATEG	= $splitCode[1];
			$ISDIRECT	= $splitCode[2];
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pi180c23o_pn/update_process');
			
			$MenuCode 			= 'MN135';
			$data["MenuCode"] 	= 'MN135';
			
			
			$getINV				= $this->m_opn_inv->get_INV_by_number($INV_NUM)->row();
				
			$data['default']['INV_NUM'] 	= $getINV->INV_NUM;
			$data['default']['INV_CODE'] 	= $getINV->INV_CODE;
			$data['default']['INV_TYPE'] 	= $getINV->INV_TYPE;
			$data['default']['PO_NUM'] 		= $getINV->PO_NUM;
			$data['default']['IR_NUM'] 		= $getINV->IR_NUM;
			$IR_NUM							= $getINV->IR_NUM;
			$data['default']['PRJCODE'] 	= $getINV->PRJCODE;
			$PRJCODE						= $getINV->PRJCODE;
			$data['default']['INV_DATE'] 	= $getINV->INV_DATE;
			$data['default']['INV_DUEDATE'] = $getINV->INV_DUEDATE;
			
			$data['default']['SPLCODE'] 	= $getINV->SPLCODE;
			$SPLCODE						= $getINV->SPLCODE;
			
			$data['default']['DP_NUM'] 		= $getINV->DP_NUM;
			$data['default']['DP_AMOUNT'] 	= $getINV->DP_AMOUNT;
			$data['default']['INV_CURRENCY']= $getINV->INV_CURRENCY;
			$data['default']['INV_TAXCURR'] = $getINV->INV_TAXCURR;
			$data['default']['INV_AMOUNT'] 	= $getINV->INV_AMOUNT;
			$data['default']['INV_AMOUNT_BASE'] = $getINV->INV_AMOUNT_BASE;
			$data['default']['INV_LISTTAX'] = $getINV->INV_LISTTAX;
			$data['default']['INV_LISTTAXVAL'] = $getINV->INV_LISTTAXVAL;
			$data['default']['INV_TERM'] 	= $getINV->INV_TERM;
			$data['default']['INV_STAT'] 	= $getINV->INV_STAT;
			$data['default']['INV_PAYSTAT'] = $getINV->INV_PAYSTAT;
			$data['default']['COMPANY_ID'] 	= $getINV->COMPANY_ID;
			$data['default']['VENDINV_NUM'] = $getINV->VENDINV_NUM;
			$data['default']['INV_NOTES'] 	= $getINV->INV_NOTES;
			$data['default']['INV_NOTES1'] 	= $getINV->INV_NOTES1;
			$data['default']['REF_NOTES'] 	= $getINV->REF_NOTES;
			$data['default']['INV_PPH'] 	= $getINV->INV_PPH;
			$data['default']['INV_PPHVAL'] 	= $getINV->INV_PPHVAL;
			$data['default']['Patt_Number'] = $getINV->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getINV->INV_NUM;
				$MenuCode 		= 'MN135';
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
				$data['countSUPL'] 	= $this->m_opn_inv->count_all_vendUP($SPLCODE);
				$data['vwSUPL'] 	= $this->m_opn_inv->view_all_vendUP($SPLCODE)->result();
				
				if($INV_CATEG == 'OPN')
				{
					$data['countSUPL'] 	= $this->m_opn_inv->count_all_vendUPOPN($SPLCODE, $PRJCODE);
					$data['vwSUPL'] 	= $this->m_opn_inv->view_all_vendUPOPN($SPLCODE, $PRJCODE)->result();
				}
				$this->load->view('v_purchase/v_opn_inv/v_opninv_form', $data);	
			}
			else
			{
				$data['countSUPL'] 	= $this->m_opn_inv->count_all_vendDir($PRJCODE, $SPLCODE);
				$data['vwSUPL'] 	= $this->m_opn_inv->view_all_vendDir($PRJCODE, $SPLCODE)->result();
				$this->load->view('v_purchase/v_opn_inv/v_opninvDir_form', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			$INV_NUM 		= $this->input->post('INV_NUM');
			$INV_CODE 		= $this->input->post('INV_CODE');
			$INV_DATE		= date('Y-m-d',strtotime($this->input->post('INV_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			
			$INV_DUEDATE	= date('Y-m-d',strtotime($this->input->post('INV_DUEDATE')));
			$INV_CODE		= $this->input->post('INV_CODE');
			$INV_TYPE		= $this->input->post('INV_TYPE');
			$INV_CATEG		= $this->input->post('INV_CATEG');
			$PO_NUM			= $this->input->post('PO_NUM');
			$IR_NUM			= $this->input->post('Ref_Number'); // TTK NUMBER
			$SPLCODE 		= $this->input->post('SPLCODE');
			$INV_CURRENCY	= 'IDR';
			$INV_TAXCURR	= 'IDR';
			$DP_NUM			= $this->input->post('DP_NUM');
			$DP_AMOUNT		= $this->input->post('DP_AMOUNT');
			$INV_AMOUNT		= $this->input->post('INV_AMOUNT');
			$INV_LISTTAXVAL	= $this->input->post('INV_LISTTAXVAL');
			$INV_PPH		= $this->input->post('INV_PPH');
			$INV_PPHVAL		= $this->input->post('INV_PPHVAL');
			$INV_TERM		= $this->input->post('INV_TERM');
			$INV_STAT 		= $this->input->post('INV_STAT');
			$INV_PAYSTAT	= 'NP';
			$COMPANY_ID		= $COMPANY_ID;
			$VENDINV_NUM	= $this->input->post('VENDINV_NUM');
			$INV_NOTES		= $this->input->post('INV_NOTES');
			$INV_NOTES1		= $this->input->post('INV_NOTES1');
			$REF_NOTES		= $this->input->post('REF_NOTES');
			$UPDATED		= date('Y-m-d H:i:s');
			$UPDATER		= $DefEmp_ID;
			
			$INV_AMOUNTTOT	= $INV_AMOUNT + $INV_LISTTAXVAL - $INV_PPHVAL - $DP_AMOUNT;
			
			$TAXCODE1		= "";
			if($INV_LISTTAXVAL > 0)
				$TAXCODE1	= "TAX01";
			
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
				
			//GET SUPPLIER CATEG
				$SPLCAT		= '';
				$sqlSPLC	= "SELECT SPLCAT FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
				$resSPLC	= $this->db->query($sqlSPLC)->result();
				foreach($resSPLC as $rowSPLC) :
					$SPLCAT = $rowSPLC->SPLCAT;
				endforeach;
			
			$s = strtotime($INV_DATE);
			$e = strtotime($INV_DUEDATE);
			
			$INV_TERM 	= ($e - $s)/ (24 * 3600);
			
			if($INV_STAT == 6)
			{
				$updINV 	= array('INV_STAT'		=> $INV_STAT,
									'INV_NOTES1'	=> $INV_NOTES1,
									'INV_PAYSTAT'	=> 'FP');
				$this->m_opn_inv->updateINV($INV_NUM, $updINV);
			}
			else
			{
				$updINV 	= array('INV_NUM' 			=> $INV_NUM,
									'INV_CODE'			=> $INV_CODE,
									'INV_TYPE'			=> $INV_TYPE,
									'INV_CATEG'			=> $INV_CATEG,
									'PO_NUM'			=> $PO_NUM,
									'IR_NUM'			=> $IR_NUM,
									'PRJCODE'			=> $PRJCODE,
									'INV_DATE'			=> $INV_DATE,
									'INV_DUEDATE'		=> $INV_DUEDATE,
									'SPLCODE'			=> $SPLCODE,
									'DP_NUM'			=> $DP_NUM,
									'DP_AMOUNT'			=> $DP_AMOUNT,
									'INV_CURRENCY'		=> $INV_CURRENCY,
									'INV_TAXCURR'		=> $INV_TAXCURR,
									'INV_AMOUNT'		=> $INV_AMOUNT,
									'INV_AMOUNT_BASE'	=> $INV_AMOUNT,
									'INV_LISTTAXVAL'	=> $INV_LISTTAXVAL,
									'INV_PPH'			=> $INV_PPH,
									'INV_PPHVAL'		=> $INV_PPHVAL,
									'INV_TERM'			=> $INV_TERM,
									'INV_STAT'			=> $INV_STAT,
									'INV_PAYSTAT'		=> $INV_PAYSTAT,
									'COMPANY_ID'		=> $VENDINV_NUM,
									'VENDINV_NUM'		=> $VENDINV_NUM,
									'INV_NOTES'			=> $INV_NOTES,
									'CREATED'			=> $UPDATED,
									'CREATER'			=> $UPDATER);
				$this->m_opn_inv->updateINV($INV_NUM, $updINV);
				
				$this->m_opn_inv->deleteINVDet($INV_NUM);
				
				// Pembuatan Journal Header
				if($INV_STAT == 3)
				{
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $INV_NUM;
						$JournalType	= 'PINV';
						$JournalH_Date	= $INV_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $IR_NUM;
						$LastUpdate		= $UPDATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $PO_NUM;
						$RefType		= 'TTK';
						$PRJCODE		= $PRJCODE;
						$Journal_Amount	= $INV_AMOUNTTOT;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Date' 	=> $JournalH_Date,
											'Company_ID' 		=> $comp_init,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE,
											'Journal_Amount'	=> $Journal_Amount);
						$this->m_journal->createJournalH($JournalH_Code, $parameters);
					// END : JOURNAL HEADER
					
					// UPDATE DP HEADER
						if($DP_NUM != '')
						{
							$updDP 	= array('DP_AMOUNT_USED' 	=> $DP_AMOUNT);
							$this->m_opn_inv->updateDP($DP_NUM, $updDP);
						}
					
					// Pada saat pembuatan Faktur Pembelian, hanya mencatat
					/*
						Hutang blm difaktur		xxxx				(Jika ada. Karena tidak ada, maka diabaikan)
						PPn Masukan 			xxxx
							Hutang Supplier				xxxx
					*/
					// START : JOURNAL DETAIL DEBIT - KHUSUS PPn Masukan
						if($TAXCODE1 == 'TAX01')
						{
							$JournalH_Code	= $INV_NUM;
							$JournalType	= 'PINV2';
							$JournalH_Date	= $INV_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $IR_NUM;
							$LastUpdate		= $UPDATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PO_NUM;
							$RefType		= 'TTK';
							$JSource		= 'PINV2';
							$PRJCODE		= $PRJCODE;
							$Notes			= "PPn Masukan";
							
							$ITM_CODE 		= $INV_NUM;
							$ACC_ID 		= '';
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $INV_LISTTAXVAL;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PINV2~$SPLCAT";									// PERHATIKAN DI SINI. PENTING
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'Notes' 			=> $Notes);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						}
					// START : JOURNAL DETAIL DEBIT - KHUSUS PPn Masukan
				}
				
				// Pembuatan Journal Detail
				if($INV_TYPE == 0)
				{
					foreach($_POST['data1'] as $d)
					{
						$this->db->insert('tbl_pinv_detail',$d);
						
						// Pembuatan Journal Detail antara Invoice dengan lawannya
						if($INV_STAT == 3)
						{
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$JournalH_Code	= $INV_NUM;
							$JournalType	= 'PINV';
							$JournalH_Date	= $INV_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $IR_NUM;
							$LastUpdate		= $UPDATER;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PO_NUM;
							$RefType		= 'TTK';
							$JSource		= 'PINV';
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= $d['ITM_CODE'];
							$ACC_ID 		= $d['ACC_ID'];
							$ITM_UNIT 		= $d['ITM_UNIT'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_UNITP'];
							$ITM_DISC 		= $d['ITM_DISC'];
							//$TAXCODE1 	= $d['TAXCODE1'];
							//$TAXPRICE1	= $d['TAX_AMOUNT_PPn1'];
							$TAXCODE1 		= "";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PINV~$SPLCAT";
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
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
				}
				else
				{
					// Pembuatan Journal Detail antara Invoice dengan lawannya
					if($INV_STAT == 3)
					{					
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $INV_NUM;
						$JournalType	= 'PINV';
						$JournalH_Date	= $INV_DATE;
						$Company_ID		= $comp_init;
						$Currency_ID	= 'IDR';
						$DOCSource		= $IR_NUM;
						$LastUpdate		= $UPDATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $PO_NUM;
						$RefType		= 'TTK';
						$JSource		= 'PINV';
						$PRJCODE		= $PRJCODE;
						$ITM_CODE 		= '';
						$ACC_ID 		= '';
						$ITM_UNIT 		= 'LS';
						$ITM_QTY 		= 1;
						$ITM_PRICE 		= $INV_AMOUNT;
						$ITM_DISC 		= 0;
						$TAXCODE1 		= $TAXCODE1;
						$TAXPRICE1		= $INV_LISTTAXVAL;
						$TRANS_CATEG 	= "PINVD~$SPLCAT";
						
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
											'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
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
				
				// START : JOURNAL DETAIL - KHUSUS PPh
					if($INV_PPH != '')
					{
						$this->load->model('m_journal/m_journal', '', TRUE);
						// START : JOURNAL DETAIL -- KHUSUS PPH (JIKA ADA)
							$JournalH_Code	= $INV_NUM;
							$JournalType	= 'PINV3';
							$JournalH_Date	= $INV_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $IR_NUM;
							$LastUpdate		= date('Y-m-d H:i:s');
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PO_NUM;
							$RefType		= 'TTK';
							$JSource		= 'PINV3';
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= $INV_NUM;
							$ACC_ID 		= '';
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $INV_PPHVAL;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "";
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "PINV3~$SPLCAT";
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'PPhTax'			=> $INV_PPH,
												'PPhAmount'			=> $INV_PPHVAL);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						// START : JOURNAL DETAIL
					}
				// END : JOURNAL DETAIL - KHUSUS PPh
				
				// Update status PO dan RR bahwa sudah dibuatkan invoice
				if($INV_STAT == 3)
				{
					$parameters = array(
						'INV_STAT' 	=> $INV_STAT,
						'PO_NUM' 	=> $PO_NUM,
						'IR_NUM' 	=> $IR_NUM,
						'PRJCODE' 	=> $PRJCODE
					);
					$this->m_opn_inv->updatePO_RR($INV_NUM, $parameters);
					
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
					
					// PEMBUATAN JURNAL PEMBAYARAN DARI DP JIKA DP_AMOUNT > 0
					if($DP_AMOUNT > 0)
					{
						// START : JOURNAL HEADER
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$JournalH_Code	= $DP_NUM;
							$JournalType	= 'DPP';
							$JournalH_Date	= $INV_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $INV_NUM;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $PO_NUM;
							$RefType		= 'PINV';
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
						
						// START : JOURNAL DETAIL
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$JournalH_Code	= $DP_NUM;
							$JournalType	= 'DPD';
							$JournalH_Date	= $INV_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $INV_NUM;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $IR_NUM;
							$RefType		= 'PINV';
							$JSource		= 'PINV';
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= '';
							$ACC_ID 		= '';
							$ITM_UNIT 		= 'LS';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $DP_AMOUNT;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= '';
							$TAXPRICE1		= 0;
							$TRANS_CATEG 	= "DPP~$SPLCAT";		// DPP DP PAYMENT
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV = PURCHASE INVOICE
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
				}
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
				$MenuCode 		= 'MN135';
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
			
			$url			= site_url('c_purchase/c_pi180c23o_pn/gall180c23inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_180604() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$COMPANY_ID		= $this->session->userdata['COMPANY_ID'];
			
			$INV_NUM 		= $this->input->post('INV_NUM');
			$INV_CODE 		= $this->input->post('INV_CODE');
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
			$REF_NOTES		= $this->input->post('REF_NOTES');
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
								'REF_NOTES'			=> $REF_NOTES,
								'CREATED'			=> $CREATED,
								'CREATER'			=> $CREATER,
								'Patt_Number'		=> $Patt_Number,
								'Patt_Year'			=> $Patt_Year);
			$this->m_opn_inv->updateINV($INV_NUM, $updINV);
			
			$this->m_opn_inv->deleteINVDet($INV_NUM);	
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_pinv_detail',$d);
			}		
			
			// Pembuatan Journal Header
			if($INV_STAT == 2 || $INV_STAT == 3)
			{
				// START : JOURNAL HEADER
					/*$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $INV_NUM;
					$JournalType	= 'PINV';
					$JournalH_Date	= $INV_DATE;
					$Company_ID		= $comp_init;
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
					$Company_ID		= $comp_init;
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
					$Company_ID		= $comp_init;
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
				$this->m_opn_inv->updatePO_RR($INV_NUM, $parameters);
				
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
				$MenuCode 		= 'MN135';
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
			
			$url			= site_url('c_purchase/c_pi180c23o_pn/gall180c23inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
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
			
			$getINV			= $this->m_opn_inv->get_INV_by_number($INV_NUM)->row();
			
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
			$data['default']['DP_NUM'] 		= $getINV->DP_NUM;
			$data['default']['DP_AMOUNT'] 	= $getINV->DP_AMOUNT;
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
	
	function printTTK()
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$TTK_NUM	= $_GET['id'];
			$TTK_NUM	= $this->url_encryption_helper->decode_url($TTK_NUM);
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$data['title'] 		= $appName;			
			$MenuCode 			= 'MN338';
			$data["MenuCode"] 	= 'MN338';
			
			$getINV				= $this->m_opn_inv->get_ttk_by_number($TTK_NUM)->row();
			$data['default']['TTK_NUM'] 		= $getINV->TTK_NUM;
			$data['default']['TTK_CODE'] 		= $getINV->TTK_CODE;
			$data['default']['TTK_DATE'] 		= $getINV->TTK_DATE;
			$data['default']['TTK_DUEDATE'] 	= $getINV->TTK_DUEDATE;
			$data['default']['TTK_ESTDATE'] 	= $getINV->TTK_ESTDATE;
			$data['default']['TTK_CHECKER'] 	= $getINV->TTK_CHECKER;
			$data['default']['TTK_NOTES'] 		= $getINV->TTK_NOTES;
			$data['default']['TTK_NOTES1'] 		= $getINV->TTK_NOTES1;
			$data['default']['TTK_AMOUNT'] 		= $getINV->TTK_AMOUNT;
			$data['default']['TTK_AMOUNT_PPN'] 	= $getINV->TTK_AMOUNT_PPN;
			$data['default']['TTK_GTOTAL'] 		= $getINV->TTK_GTOTAL;
			$data['default']['TTK_CATEG'] 		= $getINV->TTK_CATEG;
			$data['default']['PRJCODE'] 		= $getINV->PRJCODE;
			$PRJCODE							= $getINV->PRJCODE;
			$data['default']['SPLCODE'] 		= $getINV->SPLCODE;
			$SPLCODE							= $getINV->SPLCODE;
			$data['default']['TTK_STAT'] 		= $getINV->TTK_STAT;
			$data['default']['Patt_Number'] 	= $getINV->Patt_Number;
			
			$countTKP 							= $this->m_opn_inv->count_ttkp_by_number($TTK_NUM);
			$getTTKP							= $this->m_opn_inv->get_ttkp_by_number($TTK_NUM)->row();
			if($countTKP > 0)
			{
				$data['default1']['TTKP_RECDATE'] 	= $getTTKP->TTKP_RECDATE;
				$data['default1']['TTKP_DENIED'] 	= $getTTKP->TTKP_DENIED;
				$data['default1']['TTKP_DOCTYPE'] 	= $getTTKP->TTKP_DOCTYPE;
				$data['default1']['TTKP_KWITPEN'] 	= $getTTKP->TTKP_KWITPEN;
				$data['default1']['TTKP_FAKPAJAK'] 	= $getTTKP->TTKP_FAKPAJAK;
				$data['default1']['TTKP_COPYPO'] 	= $getTTKP->TTKP_COPYPO;
				$data['default1']['TTKP_BAKEM']		= $getTTKP->TTKP_BAKEM;
				$data['default1']['TTKP_LKEM']		= $getTTKP->TTKP_LKEM;
				$data['default1']['TTKP_BAPRES'] 	= $getTTKP->TTKP_BAPRES;
				$data['default1']['TTKP_SJ']		= $getTTKP->TTKP_SJ;
				$data['default1']['TTKP_KMA'] 		= $getTTKP->TTKP_KMA;
				$data['default1']['TTKP_KPPSA'] 	= $getTTKP->TTKP_KPPSA;
				$data['default1']['TTKP_SI']		= $getTTKP->TTKP_SI;
				$data['default1']['TTKP_PKER']		= $getTTKP->TTKP_PKER;
				$data['default1']['TTKP_LPK']		= $getTTKP->TTKP_LPK;
				$data['default1']['TTKP_KDAB'] 		= $getTTKP->TTKP_KDAB;
				$data['default1']['TTKP_FPMSM'] 	= $getTTKP->TTKP_FPMSM;
				$data['default1']['TTKP_BOL'] 		= $getTTKP->TTKP_BOL;
				$data['default1']['TTKP_LPP'] 		= $getTTKP->TTKP_LPP;
				$data['default1']['TTKP_LPA'] 		= $getTTKP->TTKP_LPA;
				$data['default1']['TTKP_KPM'] 		= $getTTKP->TTKP_KPM;
				$data['default1']['TTKP_JAMSER'] 	= $getTTKP->TTKP_JAMSER;
				$data['default1']['TTKP_TDPO'] 		= $getTTKP->TTKP_TDPO;
				$data['default1']['TTKP_JUM'] 		= $getTTKP->TTKP_JUM;
				$data['default1']['TTKP_JPEL'] 		= $getTTKP->TTKP_JPEL;
				$data['default1']['TTKP_MPEL'] 		= $getTTKP->TTKP_MPEL;
				$data['default1']['TTKP_LPD'] 		= $getTTKP->TTKP_LPD;
				$data['default1']['TTKP_LHP'] 		= $getTTKP->TTKP_LHP;
				$data['default1']['TTKP_JADPEL'] 	= $getTTKP->TTKP_JADPEL;
				$data['default1']['TTKP_STRO'] 		= $getTTKP->TTKP_STRO;
				$data['default1']['TTKP_SCURVE'] 	= $getTTKP->TTKP_SCURVE;
			}
			else
			{
				$data['default1']['TTKP_RECDATE'] 	= '';
				$data['default1']['TTKP_DENIED'] 	= '';
				$data['default1']['TTKP_DOCTYPE'] 	= '';
				$data['default1']['TTKP_KWITPEN'] 	= '';
				$data['default1']['TTKP_FAKPAJAK'] 	= '';
				$data['default1']['TTKP_COPYPO'] 	= '';
				$data['default1']['TTKP_BAKEM']		= '';
				$data['default1']['TTKP_LKEM']		= '';
				$data['default1']['TTKP_BAPRES'] 	= '';
				$data['default1']['TTKP_SJ']		= '';
				$data['default1']['TTKP_KMA'] 		= '';
				$data['default1']['TTKP_KPPSA'] 	= '';
				$data['default1']['TTKP_SI']		= '';
				$data['default1']['TTKP_PKER']		= '';
				$data['default1']['TTKP_LPK']		= '';
				$data['default1']['TTKP_KDAB'] 		= '';
				$data['default1']['TTKP_FPMSM'] 	= '';
				$data['default1']['TTKP_BOL'] 		= '';
				$data['default1']['TTKP_LPP'] 		= '';
				$data['default1']['TTKP_LPA'] 		= '';
				$data['default1']['TTKP_KPM'] 		= '';
				$data['default1']['TTKP_JAMSER'] 	= '';
				$data['default1']['TTKP_TDPO'] 		= '';
				$data['default1']['TTKP_JUM'] 		= '';
				$data['default1']['TTKP_JPEL'] 		= '';
				$data['default1']['TTKP_MPEL'] 		= '';
				$data['default1']['TTKP_LPD'] 		= '';
				$data['default1']['TTKP_LHP'] 		= '';
				$data['default1']['TTKP_JADPEL'] 	= '';
				$data['default1']['TTKP_STRO'] 		= '';
				$data['default1']['TTKP_SCURVE'] 	= '';
			}
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_opn_inv->count_allVend($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_opn_inv->view_allVend($PRJCODE, $SPLCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getINV->TTK_NUM;
				$MenuCode 		= 'MN338';
				$TTR_CATEG		= 'P';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_purchase/v_purchase_ttk/update_ttk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printTTKP()
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$TTK_NUM	= $_GET['id'];
			$TTK_NUM	= $this->url_encryption_helper->decode_url($TTK_NUM);
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$data['title'] 		= $appName;			
			$MenuCode 			= 'MN338';
			$data["MenuCode"] 	= 'MN338';
			
			$getINV				= $this->m_opn_inv->get_ttk_by_number($TTK_NUM)->row();
			$data['default']['TTK_NUM'] 		= $getINV->TTK_NUM;
			$data['default']['TTK_CODE'] 		= $getINV->TTK_CODE;
			$data['default']['TTK_DATE'] 		= $getINV->TTK_DATE;
			$data['default']['TTK_DUEDATE'] 	= $getINV->TTK_DUEDATE;
			$data['default']['TTK_ESTDATE'] 	= $getINV->TTK_ESTDATE;
			$data['default']['TTK_CHECKER'] 	= $getINV->TTK_CHECKER;
			$data['default']['TTK_NOTES'] 		= $getINV->TTK_NOTES;
			$data['default']['TTK_NOTES1'] 		= $getINV->TTK_NOTES1;
			$data['default']['TTK_AMOUNT'] 		= $getINV->TTK_AMOUNT;
			$data['default']['TTK_AMOUNT_PPN'] 	= $getINV->TTK_AMOUNT_PPN;
			$data['default']['TTK_GTOTAL'] 		= $getINV->TTK_GTOTAL;
			$data['default']['TTK_CATEG'] 		= $getINV->TTK_CATEG;
			$data['default']['PRJCODE'] 		= $getINV->PRJCODE;
			$PRJCODE							= $getINV->PRJCODE;
			$data['default']['SPLCODE'] 		= $getINV->SPLCODE;
			$SPLCODE							= $getINV->SPLCODE;
			$data['default']['TTK_STAT'] 		= $getINV->TTK_STAT;
			$data['default']['Patt_Number'] 	= $getINV->Patt_Number;
			
			$countTKP 							= $this->m_opn_inv->count_ttkp_by_number($TTK_NUM);
			$getTTKP							= $this->m_opn_inv->get_ttkp_by_number($TTK_NUM)->row();
			if($countTKP > 0)
			{
				$data['default1']['TTKP_RECDATE'] 	= $getTTKP->TTKP_RECDATE;
				$data['default1']['TTKP_DENIED'] 	= $getTTKP->TTKP_DENIED;
				$data['default1']['TTKP_DOCTYPE'] 	= $getTTKP->TTKP_DOCTYPE;
				$data['default1']['TTKP_KWITPEN'] 	= $getTTKP->TTKP_KWITPEN;
				$data['default1']['TTKP_FAKPAJAK'] 	= $getTTKP->TTKP_FAKPAJAK;
				$data['default1']['TTKP_COPYPO'] 	= $getTTKP->TTKP_COPYPO;
				$data['default1']['TTKP_BAKEM']		= $getTTKP->TTKP_BAKEM;
				$data['default1']['TTKP_LKEM']		= $getTTKP->TTKP_LKEM;
				$data['default1']['TTKP_BAPRES'] 	= $getTTKP->TTKP_BAPRES;
				$data['default1']['TTKP_SJ']		= $getTTKP->TTKP_SJ;
				$data['default1']['TTKP_KMA'] 		= $getTTKP->TTKP_KMA;
				$data['default1']['TTKP_KPPSA'] 	= $getTTKP->TTKP_KPPSA;
				$data['default1']['TTKP_SI']		= $getTTKP->TTKP_SI;
				$data['default1']['TTKP_PKER']		= $getTTKP->TTKP_PKER;
				$data['default1']['TTKP_LPK']		= $getTTKP->TTKP_LPK;
				$data['default1']['TTKP_KDAB'] 		= $getTTKP->TTKP_KDAB;
				$data['default1']['TTKP_FPMSM'] 	= $getTTKP->TTKP_FPMSM;
				$data['default1']['TTKP_BOL'] 		= $getTTKP->TTKP_BOL;
				$data['default1']['TTKP_LPP'] 		= $getTTKP->TTKP_LPP;
				$data['default1']['TTKP_LPA'] 		= $getTTKP->TTKP_LPA;
				$data['default1']['TTKP_KPM'] 		= $getTTKP->TTKP_KPM;
				$data['default1']['TTKP_JAMSER'] 	= $getTTKP->TTKP_JAMSER;
				$data['default1']['TTKP_TDPO'] 		= $getTTKP->TTKP_TDPO;
				$data['default1']['TTKP_JUM'] 		= $getTTKP->TTKP_JUM;
				$data['default1']['TTKP_JPEL'] 		= $getTTKP->TTKP_JPEL;
				$data['default1']['TTKP_MPEL'] 		= $getTTKP->TTKP_MPEL;
				$data['default1']['TTKP_LPD'] 		= $getTTKP->TTKP_LPD;
				$data['default1']['TTKP_LHP'] 		= $getTTKP->TTKP_LHP;
				$data['default1']['TTKP_JADPEL'] 	= $getTTKP->TTKP_JADPEL;
				$data['default1']['TTKP_STRO'] 		= $getTTKP->TTKP_STRO;
				$data['default1']['TTKP_SCURVE'] 	= $getTTKP->TTKP_SCURVE;
			}
			else
			{
				$data['default1']['TTKP_RECDATE'] 	= '';
				$data['default1']['TTKP_DENIED'] 	= '';
				$data['default1']['TTKP_DOCTYPE'] 	= '';
				$data['default1']['TTKP_KWITPEN'] 	= '';
				$data['default1']['TTKP_FAKPAJAK'] 	= '';
				$data['default1']['TTKP_COPYPO'] 	= '';
				$data['default1']['TTKP_BAKEM']		= '';
				$data['default1']['TTKP_LKEM']		= '';
				$data['default1']['TTKP_BAPRES'] 	= '';
				$data['default1']['TTKP_SJ']		= '';
				$data['default1']['TTKP_KMA'] 		= '';
				$data['default1']['TTKP_KPPSA'] 	= '';
				$data['default1']['TTKP_SI']		= '';
				$data['default1']['TTKP_PKER']		= '';
				$data['default1']['TTKP_LPK']		= '';
				$data['default1']['TTKP_KDAB'] 		= '';
				$data['default1']['TTKP_FPMSM'] 	= '';
				$data['default1']['TTKP_BOL'] 		= '';
				$data['default1']['TTKP_LPP'] 		= '';
				$data['default1']['TTKP_LPA'] 		= '';
				$data['default1']['TTKP_KPM'] 		= '';
				$data['default1']['TTKP_JAMSER'] 	= '';
				$data['default1']['TTKP_TDPO'] 		= '';
				$data['default1']['TTKP_JUM'] 		= '';
				$data['default1']['TTKP_JPEL'] 		= '';
				$data['default1']['TTKP_MPEL'] 		= '';
				$data['default1']['TTKP_LPD'] 		= '';
				$data['default1']['TTKP_LHP'] 		= '';
				$data['default1']['TTKP_JADPEL'] 	= '';
				$data['default1']['TTKP_STRO'] 		= '';
				$data['default1']['TTKP_SCURVE'] 	= '';
			}
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_opn_inv->count_allVend($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_opn_inv->view_allVend($PRJCODE, $SPLCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getINV->TTK_NUM;
				$MenuCode 		= 'MN338';
				$TTR_CATEG		= 'P';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_purchase/v_purchase_ttk/print_ttk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function i180dah() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_purchase/c_pi180c23o_pn/i1dah80Idx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function i1dah80Idx() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Tanda Terima Dokumen";
			}
			else
			{
				$data["h1_title"] 	= "Document Receipt";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN338';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_pi180c23o_pn/galli180dah/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function galli180dah() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Tanda Terima Dokumen";
				$data["h3_title"]	= "Penerimaan Barang";
			}
			else
			{
				$data["h2_title"] 	= "Document Receipt";
				$data["h3_title"]	= "Receiving Goods";
			}
			$data['PRJCODE']	= $PRJCODE;
			$data["MenuCode"] 	= 'MN338';
			$data['backURL'] 	= site_url('c_purchase/c_pi180c23o_pn/i180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$num_rows 			= $this->m_opn_inv->count_all_ttk($PRJCODE);
			$data["reccount"]	= $num_rows;	 
			$data['viewpttk']	= $this->m_opn_inv->get_all_ttk($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN338';
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
			
			$this->load->view('v_purchase/v_purchase_ttk/v_ttk_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function i180dahdd() // OK - TTK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			$data['form_action']	= site_url('c_purchase/c_pi180c23o_pn/addttk_process');
			
			// Untuk penomoran secara systemik
			$SPLCODE				= '';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_opn_inv->count_allVend($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_opn_inv->view_allVend($PRJCODE, $SPLCODE)->result();
			
			$MenuCode 				= 'MN338';
			$data["MenuCode"] 		= 'MN338';
			$data['viewDocPattern'] = $this->m_opn_inv->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN338';
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
	
			$this->load->view('v_purchase/v_purchase_ttk/v_ttk_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pall180dIR() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
			$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$getNewCode	= $_GET['id'];
			$getNewCode	= $this->url_encryption_helper->decode_url($getNewCode);
			$splitCode 	= explode("~", $getNewCode);
			$PRJCODE	= $splitCode[0];
			$SPLCODE	= $splitCode[1];
			$TTK_CATEG	= $splitCode[2];
			
			$data['title'] 			= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Penerimaan";
				$data["h3_title"]	= "Penerimaan Barang";
			}
			else
			{
				$data["h2_title"] 	= "Item Receipt List";
				$data["h3_title"]	= "Receiving Goods";
			}
			
			$data['TTK_CATEG'] 		= $TTK_CATEG;	
			$data['PRJCODE'] 		= $PRJCODE;			
			$data['countAllIR'] 	= $this->m_opn_inv->count_all_IRTTK($SPLCODE, $PRJCODE, $TTK_CATEG);
			$data['viewAllIR'] 		= $this->m_opn_inv->viewAllIRTTK($SPLCODE, $PRJCODE, $TTK_CATEG)->result();
					
			$this->load->view('v_purchase/v_purchase_ttk/v_ttk_sel_source', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function popupall_TTK() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			
		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$data["h2_title"] 	= "Pilih Tanda Terima Kwitansi";
			$data["h3_title"]	= "Penerimaan Barang";
		}
		else
		{
			$data["h2_title"] 	= "Select Invoice Receipt";
			$data["h3_title"]	= "Receiving Goods";
		}
		
		$data['txtRefference'] 	= '';
		$data['resultCount']	= 0;
		$data['pageFrom']		= 'IR';
		$data['SPLCODE']		= $SPLCODE;
		$data['PRJCODE']		= $PRJCODE;
		
		$data['countAllTTK'] 	= $this->m_opn_inv->count_allTTK($SPLCODE, $PRJCODE);
		$data['viewAllTTK'] 	= $this->m_opn_inv->view_allTTK($SPLCODE, $PRJCODE)->result();
				
		$this->load->view('v_purchase/v_opn_inv/v_opninv_sel_ttk', $data);
	}
	
	function addttk_process() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			
			$TTK_CODE 		= $this->input->post('TTK_CODE');
			$TTK_DATE		= date('Y-m-d',strtotime($this->input->post('TTK_DATE')));
			$TTK_DUEDATE	= date('Y-m-d',strtotime($this->input->post('TTK_DUEDATE')));
			$TTK_ESTDATE	= date('Y-m-d',strtotime($this->input->post('TTK_ESTDATE')));
			$TTK_CHECKER	= $this->input->post('TTK_CHECKER');
			$TTK_CATEG 		= $this->input->post('TTK_CATEG');
			$TTK_NOTES 		= $this->input->post('TTK_NOTES');
			$TTK_NOTES1		= $this->input->post('TTK_NOTES1');
			$TTK_AMOUNT		= $this->input->post('TTK_AMOUNT');
			$TTK_AMOUNT_PPN	= $this->input->post('TTK_AMOUNT_PPN');
			$TTK_GTOTAL		= $this->input->post('TTK_GTOTAL');
			$TTK_STAT 		= $this->input->post('TTK_STAT');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$TTK_CREATER	= $DefEmp_ID;
			$TTK_CREATED	= date('Y-m-d H:i:s');
			$Patt_Year		= date('Y',strtotime($this->input->post('TTK_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			$TTK_GRPCODE	= date('ymnHis');
			$TTK_NUM 		= "$PRJCODE.TTK$TTK_GRPCODE";
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN338';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$TTK_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$insertTTK 	= array('TTK_NUM' 		=> $TTK_NUM,
								'TTK_CODE'		=> $TTK_CODE,
								'TTK_DATE'		=> $TTK_DATE,
								'TTK_DUEDATE'	=> $TTK_DUEDATE,
								'TTK_ESTDATE'	=> $TTK_ESTDATE,
								'TTK_CHECKER'	=> $TTK_CHECKER,
								'TTK_CATEG'		=> $TTK_CATEG,
								'TTK_NOTES'		=> $TTK_NOTES,
								'TTK_NOTES1'	=> $TTK_NOTES1,
								'TTK_AMOUNT'	=> $TTK_AMOUNT,
								'TTK_AMOUNT_PPN'=> $TTK_AMOUNT_PPN,
								'TTK_GTOTAL'	=> $TTK_GTOTAL,
								'TTK_STAT'		=> $TTK_STAT,
								'PRJCODE'		=> $PRJCODE,
								'SPLCODE'		=> $SPLCODE,
								'TTK_CREATER'	=> $TTK_CREATER,
								'TTK_CREATED'	=> $TTK_CREATED,
								'Patt_Year'		=> $Patt_Year,
								'Patt_Number'	=> $Patt_Number);
			$this->m_opn_inv->addTTK($insertTTK);
				
			foreach($_POST['data'] as $d)
			{
				$TTK_NUM 		= $TTK_NUM;
				$TTK_REF1 		= $d['TTK_REF1'];
				$d['TTK_NUM']	= $TTK_NUM;
				$TTKD_STAT 		= 1; // 0 = Not invoice, 1 = Invoiced
				$TTK_REF1_DATED	= $d['TTK_REF1_DATED'];
				if($TTK_REF1_DATED == '')
					$d['TTK_REF1_DATED']	= $d['TTK_REF1_DATE'];
					
				$TTK_REF2_DATE1	= $d['TTK_REF2_DATE'];
				$TTK_REF2_DATE	= date('Y-m-d',strtotime($TTK_REF2_DATE1));
				
				$d['TTK_REF2_DATE']	= $TTK_REF2_DATE;
				if($TTK_REF2_DATE == '')
					$d['TTK_REF2_DATE']	= "0000-00-00";
				elseif($TTK_REF2_DATE == 'Not Set')
					$d['TTK_REF2_DATE']	= "0000-00-00";
				
				$this->db->insert('tbl_ttk_detail',$d);
				
				if($TTK_STAT == 3)
				{
					if($TTK_CATEG == 'IR')
					{
						$updIR 	= array('IR_NUM'		=> $TTK_REF1,
										'TTK_CREATED' 	=> 1);												
						$this->m_opn_inv->updIR($TTK_REF1, $updIR);
					}
					else if($TTK_CATEG == 'OPN')
					{
						$updOPN = array('TTK_CREATED' 	=> 1);												
						$this->m_opn_inv->updOPN($TTK_REF1, $updOPN);
					}
				}
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $TTK_NUM;
				$MenuCode 		= 'MN338';
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
			
			$url			= site_url('c_purchase/c_pi180c23o_pn/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u_4te77k() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$TTK_NUM	= $_GET['id'];
			$TTK_NUM	= $this->url_encryption_helper->decode_url($TTK_NUM);
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pi180c23o_pn/updatettk_process');
			
			$MenuCode 			= 'MN338';
			$data["MenuCode"] 	= 'MN338';
			
			$getINV				= $this->m_opn_inv->get_ttk_by_number($TTK_NUM)->row();
			$data['default']['TTK_NUM'] 		= $getINV->TTK_NUM;
			$data['default']['TTK_CODE'] 		= $getINV->TTK_CODE;
			$data['default']['TTK_DATE'] 		= $getINV->TTK_DATE;
			$data['default']['TTK_DUEDATE'] 	= $getINV->TTK_DUEDATE;
			$data['default']['TTK_ESTDATE'] 	= $getINV->TTK_ESTDATE;
			$data['default']['TTK_CHECKER'] 	= $getINV->TTK_CHECKER;
			$data['default']['TTK_NOTES'] 		= $getINV->TTK_NOTES;
			$data['default']['TTK_NOTES1'] 		= $getINV->TTK_NOTES1;
			$data['default']['TTK_AMOUNT'] 		= $getINV->TTK_AMOUNT;
			$data['default']['TTK_AMOUNT_PPN'] 	= $getINV->TTK_AMOUNT_PPN;
			$data['default']['TTK_GTOTAL'] 		= $getINV->TTK_GTOTAL;
			$data['default']['TTK_CATEG'] 		= $getINV->TTK_CATEG;
			$data['default']['PRJCODE'] 		= $getINV->PRJCODE;
			$PRJCODE							= $getINV->PRJCODE;
			$data['default']['SPLCODE'] 		= $getINV->SPLCODE;
			$SPLCODE							= $getINV->SPLCODE;
			$data['default']['TTK_STAT'] 		= $getINV->TTK_STAT;
			$data['default']['Patt_Number'] 	= $getINV->Patt_Number;
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_opn_inv->count_allVend($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_opn_inv->view_allVend($PRJCODE, $SPLCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getINV->TTK_NUM;
				$MenuCode 		= 'MN338';
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
			
			$this->load->view('v_purchase/v_purchase_ttk/v_ttk_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updatettk_process() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			
			$TTK_NUM		= $this->input->post('TTK_NUM');			
			$TTK_CODE 		= $this->input->post('TTK_CODE');
			$TTK_DATE		= date('Y-m-d',strtotime($this->input->post('TTK_DATE')));
			$TTK_DUEDATE	= date('Y-m-d',strtotime($this->input->post('TTK_DUEDATE')));
			$TTK_ESTDATE	= date('Y-m-d',strtotime($this->input->post('TTK_ESTDATE')));
			$TTK_CHECKER	= $this->input->post('TTK_CHECKER');
			$TTK_CATEG 		= $this->input->post('TTK_CATEG');
			$TTK_NOTES 		= $this->input->post('TTK_NOTES');
			$TTK_NOTES1		= $this->input->post('TTK_NOTES1');
			$TTK_AMOUNT		= $this->input->post('TTK_AMOUNT');
			$TTK_AMOUNT_PPN	= $this->input->post('TTK_AMOUNT_PPN');
			$TTK_GTOTAL		= $this->input->post('TTK_GTOTAL');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$TTK_STAT 		= $this->input->post('TTK_STAT');
			$SPLCODE 		= $this->input->post('SPLCODE');
			
			$TTK_CREATER	= $DefEmp_ID;
			
			if($TTK_STAT == 6)
			{
				$updateTTK		= array('TTK_STAT'		=> $TTK_STAT);
				$this->m_opn_inv->updateTTKCLS($TTK_NUM, $updateTTK);
			}
			else
			{
				$updateTTK		= array('TTK_CODE'		=> $TTK_CODE,
										'TTK_DATE'		=> $TTK_DATE,
										'TTK_DUEDATE'	=> $TTK_DUEDATE,
										'TTK_ESTDATE'	=> $TTK_ESTDATE,
										'TTK_CHECKER'	=> $TTK_CHECKER,
										'TTK_CATEG'		=> $TTK_CATEG,
										'TTK_NOTES'		=> $TTK_NOTES,
										'TTK_NOTES1'	=> $TTK_NOTES1,
										'TTK_AMOUNT'	=> $TTK_AMOUNT,
										'TTK_AMOUNT_PPN'=> $TTK_AMOUNT_PPN,
										'TTK_GTOTAL'	=> $TTK_GTOTAL,
										'TTK_STAT'		=> $TTK_STAT,
										'PRJCODE'		=> $PRJCODE,
										'SPLCODE'		=> $SPLCODE);
				$this->m_opn_inv->updateTTK($TTK_NUM, $updateTTK);
				
				$this->m_opn_inv->deleteTTKDet($TTK_NUM);	
			
				foreach($_POST['data'] as $d)
				{
					$TTK_NUM 		= $TTK_NUM;
					$TTK_REF1 		= $d['TTK_REF1'];
					$d['TTK_NUM']	= $TTK_NUM;
					$TTKD_STAT 		= 1; // 0 = Not invoice, 1 = Invoiced
					$TTK_REF1_DATED	= $d['TTK_REF1_DATED'];
					if($TTK_REF1_DATED == '')
						$d['TTK_REF1_DATED']	= $d['TTK_REF1_DATE'];
						
					$TTK_REF2_DATE	= $d['TTK_REF2_DATE'];
					if($TTK_REF2_DATE == '')
						$d['TTK_REF2_DATE']	= "0000-00-00";
					elseif($TTK_REF2_DATE == 'Not Set')
						$d['TTK_REF2_DATE']	= "0000-00-00";
					
					$this->db->insert('tbl_ttk_detail',$d);
					
					if($TTK_STAT == 3)
					{
						if($TTK_CATEG == 'IR')
						{
							$updIR 	= array('IR_NUM'		=> $TTK_REF1,
											'TTK_CREATED' 	=> 1);												
							$this->m_opn_inv->updIR($TTK_REF1, $updIR);
						}
						else if($TTK_CATEG == 'OPN')
						{
							$updOPN = array('TTK_CREATED' 	=> 1);												
							$this->m_opn_inv->updOPN($TTK_REF1, $updOPN);
						}
					}
				}
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $TTK_NUM;
				$MenuCode 		= 'MN338';
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
			
			$url			= site_url('c_purchase/c_pi180c23o_pn/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// DIRECT	
	function i180dahdir() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			$data['form_action']	= site_url('c_purchase/c_pi180c23o_pn/addttk_processdir');
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_opn_inv->count_allVendDir($PRJCODE);
			$data['vwSUPL'] 		= $this->m_opn_inv->view_allvendDir($PRJCODE)->result();
			
			$MenuCode 				= 'MN338';
			$data["MenuCode"] 		= 'MN338';
			$data['viewDocPattern'] = $this->m_opn_inv->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN338';
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
	
			$this->load->view('v_purchase/v_purchase_ttk/v_ttk_form_dir', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addttk_processdir() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			
			$TTK_CODE 		= $this->input->post('TTK_CODE');
			$TTK_DATE		= date('Y-m-d',strtotime($this->input->post('TTK_DATE')));
			$TTK_DUEDATE	= date('Y-m-d',strtotime($this->input->post('TTK_DUEDATE')));
			$TTK_ESTDATE	= date('Y-m-d',strtotime($this->input->post('TTK_ESTDATE')));
			$TTK_CHECKER	= $this->input->post('TTK_CHECKER');
			$TTK_CATEG 		= $this->input->post('TTK_CATEG');
			$TTK_NOTES 		= $this->input->post('TTK_NOTES');
			$TTK_AMOUNT		= $this->input->post('TTK_AMOUNT');
			$TTK_AMOUNT_PPN	= $this->input->post('TTK_AMOUNT_PPN');
			$TTK_GTOTAL		= $this->input->post('TTK_GTOTAL');
			$TTK_STAT 		= $this->input->post('TTK_STAT');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$TTK_CREATER	= $DefEmp_ID;
			$TTK_CREATED	= date('Y-m-d H:i:s');
			$Patt_Year		= date('Y',strtotime($this->input->post('INV_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			$TTK_GRPCODE	= date('ymnHis');
			$TTK_NUM 		= "$PRJCODE.TTK$TTK_GRPCODE-D";
			
			$insertTTK 	= array('TTK_NUM' 		=> $TTK_NUM,
								'TTK_CODE'		=> $TTK_CODE,
								'TTK_DATE'		=> $TTK_DATE,
								'TTK_DUEDATE'	=> $TTK_DUEDATE,
								'TTK_ESTDATE'	=> $TTK_ESTDATE,
								'TTK_CHECKER'	=> $TTK_CHECKER,
								'TTK_CATEG'		=> $TTK_CATEG,
								'TTK_NOTES'		=> $TTK_NOTES,
								'TTK_AMOUNT'	=> $TTK_AMOUNT,
								'TTK_AMOUNT_PPN'=> $TTK_AMOUNT_PPN,
								'TTK_GTOTAL'	=> $TTK_GTOTAL,
								'TTK_STAT'		=> $TTK_STAT,
								'PRJCODE'		=> $PRJCODE,
								'SPLCODE'		=> $SPLCODE,
								'TTK_CREATER'	=> $TTK_CREATER,
								'TTK_CREATED'	=> $TTK_CREATED,
								'Patt_Year'		=> $Patt_Year,
								'Patt_Number'	=> $Patt_Number);
			$this->m_opn_inv->addTTK($insertTTK);
				
			/*foreach($_POST['data'] as $d)
			{
				$TTK_NUM 		= $TTK_NUM;
				$TTK_REF1 		= $d['TTK_REF1'];
				$d['TTK_NUM']	= $TTK_NUM;
				$TTKD_STAT 		= 1; // 0 = Not invoice, 1 = Invoiced
				
				$this->db->insert('tbl_ttk_detail',$d);
				
				if($TTK_STAT == 3)
				{
					// UPDATE IR
					$updIR 	= array('IR_NUM'		=> $TTK_REF1,
									'TTK_CREATED' 	=> 1);												
					$this->m_opn_inv->updIR($TTK_REF1, $updIR);					
				}
			}*/
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $TTK_NUM;
				$MenuCode 		= 'MN338';
				$TTR_CATEG		= 'C-DIR';
				
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
			
			$url			= site_url('c_purchase/c_pi180c23o_pn/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u_4te77kdir() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$TTK_NUM	= $_GET['id'];
			$TTK_NUM	= $this->url_encryption_helper->decode_url($TTK_NUM);
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pi180c23o_pn/updatettk_process');
			
			$MenuCode 			= 'MN338';
			$data["MenuCode"] 	= 'MN338';
			
			$getINV				= $this->m_opn_inv->get_ttk_by_number($TTK_NUM)->row();
			$data['default']['TTK_NUM'] 		= $getINV->TTK_NUM;
			$data['default']['TTK_CODE'] 		= $getINV->TTK_CODE;
			$data['default']['TTK_DATE'] 		= $getINV->TTK_DATE;
			$data['default']['TTK_DUEDATE'] 	= $getINV->TTK_DUEDATE;
			$data['default']['TTK_ESTDATE'] 	= $getINV->TTK_ESTDATE;
			$data['default']['TTK_CHECKER'] 	= $getINV->TTK_CHECKER;
			$data['default']['TTK_NOTES'] 		= $getINV->TTK_NOTES;
			$data['default']['TTK_AMOUNT'] 		= $getINV->TTK_AMOUNT;
			$data['default']['TTK_AMOUNT_PPN'] 	= $getINV->TTK_AMOUNT_PPN;
			$data['default']['TTK_GTOTAL'] 		= $getINV->TTK_GTOTAL;
			$data['default']['TTK_CATEG'] 		= $getINV->TTK_CATEG;
			$data['default']['PRJCODE'] 		= $getINV->PRJCODE;
			$PRJCODE							= $getINV->PRJCODE;
			$data['default']['SPLCODE'] 		= $getINV->SPLCODE;
			$SPLCODE							= $getINV->SPLCODE;
			$data['default']['TTK_STAT'] 		= $getINV->TTK_STAT;
			$data['default']['Patt_Number'] 	= $getINV->Patt_Number;
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_opn_inv->count_allVendDir($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_opn_inv->view_allvendDir($PRJCODE, $SPLCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getINV->TTK_NUM;
				$MenuCode 		= 'MN338';
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
			
			$this->load->view('v_purchase/v_purchase_ttk/v_ttk_form_dir', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180c23ddDir() // OK - INVOICING DIRECT
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			$data['form_action']	= site_url('c_purchase/c_pi180c23o_pn/add_process');
			
			// Untuk penomoran secara systemik
			$SPLCODE				= '';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_opn_inv->count_all_vendDir($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_opn_inv->view_all_vendDir($PRJCODE, $SPLCODE)->result();
			
			$MenuCode 				= 'MN135';
			$data["MenuCode"] 		= 'MN135';
			$data['viewDocPattern'] = $this->m_opn_inv->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN135';
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
	
			$this->load->view('v_purchase/v_opn_inv/v_opninvDir_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function popupall_TTKdir() // OK
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
		
		$data['txtRefference'] 	= '';
		$data['resultCount']	= 0;
		$data['pageFrom']		= 'OTH';
		$data['SPLCODE']		= $SPLCODE;
		$data['PRJCODE']		= $PRJCODE;
		
		$data['countAllTTK'] 	= $this->m_opn_inv->count_allTTKdir($SPLCODE, $PRJCODE);
		$data['viewAllTTK'] 	= $this->m_opn_inv->view_allTTKdir($SPLCODE, $PRJCODE)->result();
				
		$this->load->view('v_purchase/v_opn_inv/v_opninvDir_sel_ttk', $data);
	}
		
	function popupall_DP() // G
	{
		$this->load->model('m_purchase/m_opn_inv/m_opn_inv', '', TRUE);
		
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
			
		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$data["h2_title"] 	= "Pilih Uang Muka";
			$data["h3_title"]	= "Faktur";
		}
		else
		{
			$data["h2_title"] 	= "Select DP";
			$data["h3_title"]	= "Invoice";
		}
		
		$data['txtRefference'] 	= '';
		$data['resultCount']	= 0;
		$data['pageFrom']		= 'DP';
		$data['SPLCODE']		= $SPLCODE;
		$data['PRJCODE']		= $PRJCODE;
		
		$data['countAllDP'] 	= $this->m_opn_inv->count_allDP($SPLCODE, $PRJCODE);
		$data['viewAllDP'] 	= $this->m_opn_inv->view_allDP($SPLCODE, $PRJCODE)->result();
				
		$this->load->view('v_purchase/v_opn_inv/v_opninv_sel_dp', $data);
	}
}