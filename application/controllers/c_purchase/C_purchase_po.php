<?php
/*  
 * Author		= Hendar Permana
 * Create Date	= 26 Mei 2017
 * Updated		= Dian Hermanto - 11 November 2017
 * File Name	= C_purchase_po.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_purchase_po extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_purchase_po/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
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
			$data["secURL"] 	= "c_purchase/c_purchase_po/get_last_po/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_po() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_purchase/c_purchase_po/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_purchase_po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_purchase_po->count_all_num_rowsPO($PRJCODE);
			$data["countPO"] 	= $num_rows;
	 
			$data['vwPO'] = $this->m_purchase_po->get_all_PO($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN019';
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
			
			$this->load->view('v_purchase/v_purchase_po/v_po_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			$data['form_action']	= site_url('c_purchase/c_purchase_po/add_process');
			$cancelURL				= site_url('c_purchase/c_purchase_po/get_last_po/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN019';
			$data["MenuCode"] 		= 'MN019';
			$data['viewDocPattern'] = $this->m_purchase_po->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN019';
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
	
			$this->load->view('v_purchase/v_purchase_po/v_po_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$PO_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$PO_NUM 		= $this->input->post('PO_NUM');
			$PO_CODE 		= $this->input->post('PO_CODE');
			$PO_DATE		= date('Y-m-d',strtotime($this->input->post('PO_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$Patt_Year		= date('Y',strtotime($this->input->post('PO_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			// START : CHECK THE CODE	
				$DOC_NUM		= $PO_NUM;
				$DOC_CODE		= $PO_CODE;
				$DOC_DATE		= $PO_DATE;
				$MenuCode 		= 'MN019';
				$TABLE_NAME		= 'tbl_po_header';
				$FIELD_NAME		= 'PO_NUM';
				
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
				
				$PO_NUM			= $DOC_NUM;
				$PO_CODE		= $DOC_CODE;
			// END : CHECK CODE
			
			if($PRJCODE == 'KTR')
			{
				$PO_TYPE	= 1;
			}
			else
			{
				$PO_TYPE	= 2;
			}
			$PO_CAT			= 0;								// In Direct
			$PO_CAT			= $PO_CAT;						
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= $this->input->post('PR_NUM');			
			$PO_CURR 		= $this->input->post('PO_CURR'); 	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PO_PLANIR		= date('Y-m-d',strtotime($this->input->post('PO_PLANIR'))); // Tanggal Terima
			$PO_NOTES 		= $this->input->post('PO_NOTES');
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$Patt_Year		= date('Y',strtotime($this->input->post('PO_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('PO_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('PO_DATE')));
			
			$AddPO			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
									'PO_TYPE' 		=> $PO_TYPE,
									'PO_CAT'		=> $PO_CAT,
									'PO_DATE'		=> $PO_DATE,
									'PO_DUED'		=> $PO_DUED,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE' 		=> $SPLCODE,
									'PR_NUM' 		=> $PR_NUM,
									'PO_CURR' 		=> $PO_CURR, 
									'PO_CURRATE' 	=> $PO_CURRATE,									
									'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
									'PO_TENOR' 		=> $PO_TENOR, 
									'PO_PLANIR'		=> $PO_PLANIR,
									'PO_NOTES' 		=> $PO_NOTES,
									'PO_CREATER'	=> $DefEmp_ID,
									'PO_CREATED'	=> $PO_CREATED,
									'PO_STAT'		=> $PO_STAT,
									'PO_INVSTAT'	=> 0,									
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);								
			$this->m_purchase_po->add($AddPO); // Insert tb_po_header
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_po_detail',$d);
			}
			
			// UPDATE DETAIL
				$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('PO_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN019';
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
			
			$url			= site_url('c_purchase/c_purchase_po/get_all_PO/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function addDir() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			$data['form_action']	= site_url('c_purchase/c_purchase_po/addDir_process');
			$cancelURL				= site_url('c_purchase/c_purchase_po/get_last_po/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN019';
			$data["MenuCode"] 		= 'MN019';
			$data['viewDocPattern'] = $this->m_purchase_po->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN019';
				$TTR_CATEG		= 'L-DIR';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
	
			$this->load->view('v_purchase/v_purchase_po/v_podir_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function addDir_process()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$PO_CREATED 		= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$PO_NUM 		= $this->input->post('PO_NUM');
			$PO_CODE 		= $this->input->post('PO_CODE');
			$PO_DATE		= date('Y-m-d',strtotime($this->input->post('PO_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$Patt_Year		= date('Y',strtotime($this->input->post('PO_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			// START : CHECK THE CODE	
				$DOC_NUM		= $PO_NUM;
				$DOC_CODE		= $PO_CODE;
				$DOC_DATE		= $PO_DATE;
				$MenuCode 		= 'MN019';
				$TABLE_NAME		= 'tbl_po_header';
				$FIELD_NAME		= 'PO_NUM';
				
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
				
				$PO_NUM			= $DOC_NUM;
				$PO_CODE		= $DOC_CODE;
			// END : CHECK CODE
			
			if($PRJCODE == 'KTR')
			{
				$PO_TYPE	= 1;
			}
			else
			{
				$PO_TYPE	= 2;
			}
			$PO_CAT			= 1;								// Direct
			$PO_CAT			= $PO_CAT;						
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= $this->input->post('PR_NUM');			
			$PO_CURR 		= $this->input->post('PO_CURR'); 	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PO_PLANIR		= date('Y-m-d',strtotime($this->input->post('PO_PLANIR'))); // Tanggal Terima
			$PO_NOTES 		= $this->input->post('PO_NOTES');
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$Patt_Year		= date('Y',strtotime($this->input->post('PO_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('PO_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('PO_DATE')));
			
			$AddPOD			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
									'PO_TYPE' 		=> $PO_TYPE,
									'PO_CAT'		=> $PO_CAT,
									'PO_DATE'		=> $PO_DATE,
									'PO_DUED'		=> $PO_DUED,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE' 		=> $SPLCODE,
									//'PR_NUM' 		=> $PR_NUM,
									'PO_CURR' 		=> $PO_CURR, 
									'PO_CURRATE' 	=> $PO_CURRATE,									
									'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
									'PO_TENOR' 		=> $PO_TENOR, 
									'PO_PLANIR'		=> $PO_PLANIR,
									'PO_NOTES' 		=> $PO_NOTES,
									'PO_CREATER'	=> $DefEmp_ID,
									'PO_CREATED'	=> $PO_CREATED,
									'PO_STAT'		=> $PO_STAT,
									'PO_INVSTAT'	=> 0,
									'ISDIRECT'		=> 1,									
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);								
			$this->m_purchase_po->add($AddPOD); // Insert tb_po_header
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_po_detail',$d);
			}
			
			// UPDATE DETAIL
				$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('PO_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "POD",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN019';
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
			
			$url			= site_url('c_purchase/c_purchase_po/get_all_PO/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_all_PO() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_purchase/c_purchase_po/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_purchase_po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_purchase_po->count_all_num_rowsPO($PRJCODE);
			$data["countPO"] 	= $num_rows;
	 
			$data['vwPO'] = $this->m_purchase_po->get_all_PO($PRJCODE)->result();
			
			$this->load->view('v_purchase/v_purchase_po/v_po_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
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
			$PO_NUM		= $splitCode[0];
			$ISDIRECT	= $splitCode[1];
								
			$getpo_head				= $this->m_purchase_po->get_po_by_number($PO_NUM)->row();
			$PRJCODE				= $getpo_head->PRJCODE;
			$data["MenuCode"] 		= 'MN019';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$EmpID 					= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';
			$cancelURL				= site_url('c_purchase/c_purchase_po/get_last_po/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN019';
			$data["MenuCode"] 		= 'MN019';
		
			$getPO 							= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();
			$data['default']['PO_NUM'] 		= $getPO->PO_NUM;
			$data['default']['PO_CODE'] 	= $getPO->PO_CODE;
			$data['default']['PO_TYPE'] 	= $getPO->PO_TYPE;
			$data['default']['PO_CAT'] 		= $getPO->PO_CAT;
			$data['default']['PO_DATE'] 	= $getPO->PO_DATE;
			$data['default']['PO_DUED'] 	= $getPO->PO_DUED;
			$data['default']['PRJCODE'] 	= $getPO->PRJCODE;
			$data['default']['SPLCODE'] 	= $getPO->SPLCODE;
			$data['default']['PR_NUM'] 		= $getPO->PR_NUM;
			$data['default']['PO_CURR'] 	= $getPO->PO_CURR;
			$data['default']['PO_CURRATE'] 	= $getPO->PO_CURRATE;
			$data['default']['PO_PAYTYPE'] 	= $getPO->PO_PAYTYPE;
			$data['default']['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['default']['PO_PLANIR'] 	= $getPO->PO_PLANIR;
			$data['default']['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['default']['PRJNAME'] 	= $getPO->PRJNAME;
			$data['default']['PO_STAT'] 	= $getPO->PO_STAT;
			$data['default']['Patt_Year'] 	= $getPO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getPO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getPO->Patt_Date;
			$data['default']['Patt_Number'] = $getPO->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getPO->PO_NUM;
				$MenuCode 		= 'MN019';
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
				$data['form_action']		= site_url('c_purchase/c_purchase_po/update_process');
				$this->load->view('v_purchase/v_purchase_po/v_po_form', $data);	
			}
			else
			{
				$data['form_action']		= site_url('c_purchase/c_purchase_po/updateDir_process');
				$this->load->view('v_purchase/v_purchase_po/v_podir_form', $data);	
			}
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
			
			$PO_NUM 	= $this->input->post('PO_NUM');
			$PO_CODE 	= $this->input->post('PO_CODE');
			$PO_DATE	= date('Y-m-d',strtotime($this->input->post('PO_DATE')));
			$PRJCODE 	= $this->input->post('PRJCODE');
			
			if($PRJCODE == 'KTR')
			{
				$PO_TYPE	= 1;
			}
			else
			{
				$PO_TYPE	= 2;
			}
			$PO_CAT			= 1;								// In Direct					
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= $this->input->post('PR_NUM');			
			$PO_CURR 		= $this->input->post('PO_CURR');	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar 
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PO_PLANIR		= date('Y-m-d',strtotime($this->input->post('PO_PLANIR'))); // Tanggal Terima
			$PO_NOTES 		= $this->input->post('PO_NOTES');
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$Patt_Year		= date('Y',strtotime($this->input->post('PO_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('PO_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('PO_DATE')));
			
			$updPO 			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
									'PO_TYPE' 		=> $PO_TYPE,
									'PO_CAT'		=> $PO_CAT,
									'PO_DATE'		=> $PO_DATE,
									'PO_DUED'		=> $PO_DUED,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE' 		=> $SPLCODE,
									'PR_NUM' 		=> $PR_NUM,
									'PO_CURR' 		=> $PO_CURR, 
									'PO_CURRATE' 	=> $PO_CURRATE,									
									'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
									'PO_TENOR' 		=> $PO_TENOR, 
									'PO_PLANIR'		=> $PO_PLANIR,
									'PO_NOTES' 		=> $PO_NOTES,
									'PO_STAT'		=> $PO_STAT,
									'PO_INVSTAT'	=> 0);
			$this->m_purchase_po->updatePO($PO_NUM, $updPO);
			
			$this->m_purchase_po->deletePODetail($PO_NUM);	
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_po_detail',$d);
			}
			
			// UPDATE DETAIL
				$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN019';
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
			
			$url			= site_url('c_purchase/c_purchase_po/get_all_PO/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function updateDir_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
			
			$PO_NUM 		= $this->input->post('PO_NUM');
			$PO_CODE 		= $this->input->post('PO_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PO_DATE		= date('Y-m-d',strtotime($this->input->post('PO_DATE')));
			
			if($PRJCODE == 'KTR')
			{
				$PO_TYPE	= 1;
			}
			else
			{
				$PO_TYPE	= 2;
			}
			$PO_CAT			= 1;								// Direct
			$PO_CAT			= $PO_CAT;						
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= $this->input->post('PR_NUM');			
			$PO_CURR 		= $this->input->post('PO_CURR'); 	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE');	//Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); 	// Jangka Waktu Bayar
			$PO_PLANIR		= date('Y-m-d',strtotime($this->input->post('PO_PLANIR'))); // Tanggal Terima
			$PO_NOTES 		= $this->input->post('PO_NOTES');
			$PO_STAT		= $this->input->post('PO_STAT');	// New, Confirm, Approve
			$Patt_Year		= date('Y',strtotime($this->input->post('PO_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('PO_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('PO_DATE')));
			
			$updPO			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
									'PO_TYPE' 		=> $PO_TYPE,
									'PO_CAT'		=> $PO_CAT,
									'PO_DATE'		=> $PO_DATE,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE' 		=> $SPLCODE,
									//'PR_NUM' 		=> $PR_NUM,
									'PO_CURR' 		=> $PO_CURR, 
									'PO_CURRATE' 	=> $PO_CURRATE,									
									'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
									'PO_TENOR' 		=> $PO_TENOR, 
									'PO_PLANIR'		=> $PO_PLANIR,
									'PO_NOTES' 		=> $PO_NOTES,
									'PO_CREATER'	=> $DefEmp_ID,
									'PO_CREATED'	=> $PO_CREATED,
									'PO_STAT'		=> $PO_STAT,
									'PO_INVSTAT'	=> 0,
									'ISDIRECT'		=> 1);
			$this->m_purchase_po->updatePO($PO_NUM, $updPO);
			
			$this->m_purchase_po->deletePODetail($PO_NUM);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_po_detail', $d);
			}
			
			// UPDATE DETAIL
				$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "POD",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN019';
				$TTR_CATEG		= 'UP-DIR';
				
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
			
			$url			= site_url('c_purchase/c_purchase_po/get_all_PO/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function inbox() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_purchase_po/inbox1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function inbox1() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= '';
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_purchase_po->count_all_num_rowsPOInb($DefEmp_ID);
			$data["countPO"] 	= $num_rows;	 
			$data['vwPO'] 		= $this->m_purchase_po->get_all_POInb($DefEmp_ID)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN020';
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
			
			$this->load->view('v_purchase/v_purchase_po/v_inb_po_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_inb() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$CollID			= $_GET['id'];
		$CollID			= $this->url_encryption_helper->decode_url($CollID);
		
		$splitCode 		= explode("~", $CollID);
		$PO_NUM			= $splitCode[0];
		$ISDIRECT		= $splitCode[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_purchase/c_purchase_po/update_process_inb');
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $this->input->post('PRJCODE');
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			
			$getPO	 						= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();			
			$data['default']['PO_NUM'] 		= $getPO->PO_NUM;
			$data['default']['PO_CODE'] 	= $getPO->PO_CODE;
			$data['default']['PO_TYPE'] 	= $getPO->PO_TYPE;
			$data['default']['PO_CAT'] 		= $getPO->PO_CAT;
			$data['default']['PO_DATE'] 	= $getPO->PO_DATE;
			$data['default']['PO_DUED'] 	= $getPO->PO_DUED;
			$data['default']['PRJCODE'] 	= $getPO->PRJCODE;
			$PRJCODE						= $getPO->PRJCODE;
			$data['default']['SPLCODE'] 	= $getPO->SPLCODE;
			$data['default']['PR_NUM'] 		= $getPO->PR_NUM;
			$data['default']['PO_CURR'] 	= $getPO->PO_CURR;
			$data['default']['PO_CURRATE'] 	= $getPO->PO_CURRATE;
			$data['default']['PO_PAYTYPE'] 	= $getPO->PO_PAYTYPE;
			$data['default']['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['default']['PO_PLANIR'] 	= $getPO->PO_PLANIR;
			$data['default']['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['default']['PO_NOTES1'] 	= $getPO->PO_NOTES1;
			$data['default']['PRJNAME'] 	= $getPO->PRJNAME;
			$data['default']['PO_STAT'] 	= $getPO->PO_STAT;
			$data['default']['Patt_Year'] 	= $getPO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getPO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getPO->Patt_Date;
			$data['default']['Patt_Number'] = $getPO->Patt_Number;
			
			$MenuCode 						= 'MN020';
			$data["MenuCode"] 				= 'MN020';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getPO->PO_NUM;
				$MenuCode 		= 'MN020';
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
			
			if($ISDIRECT == 0)
			{
				$data['form_action']		= site_url('c_purchase/c_purchase_po/update_process_inb');
				$this->load->view('v_purchase/v_purchase_po/v_inb_po_form', $data);
			}
			else
			{
				$data['form_action']		= site_url('c_purchase/c_purchase_po/update_process_inb');
				$this->load->view('v_purchase/v_purchase_po/v_inb_poDir_form', $data);	
			}
			
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PO_APPROVED 	= date('Y-m-d H:i:s');
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$PO_NUM 		= $this->input->post('PO_NUM');
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DATE 		= date('Y-m-d',strtotime($this->input->post('PO_DATE')));
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PO_TOTCOST		= $this->input->post('PO_TOTCOST');
			$PO_STAT 		= $this->input->post('PO_STAT');
			$ISDIRECT 		= $this->input->post('ISDIRECT');
			
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $PO_NUM;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= $PO_APPROVED;
				$AH_NOTES		= $this->input->post('PO_NOTES1');
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
										'AH_APPLEV'		=> $AH_APPLEV,
										'AH_APPROVER'	=> $AH_APPROVER,
										'AH_APPROVED'	=> $AH_APPROVED,
										'AH_NOTES'		=> $AH_NOTES,
										'AH_ISLAST'		=> $AH_ISLAST);										
				$this->m_updash->insAppHist($insAppHist);
			
				$updPO 			= array('PO_STAT'	=> 7);										
				$this->m_purchase_po->updatePOInb($PO_NUM, $updPO);
			// END : SAVE APPROVE HISTORY
			
			if($AH_ISLAST == 1)
			{
				$updPO 	= array('PO_TOTCOST'	=> $PO_TOTCOST,
								'PO_APPROVER'	=> $DefEmp_ID,
								'PO_APPROVED'	=> $PO_APPROVED,
								'PO_NOTES1'		=> $this->input->post('PO_NOTES1'),
								'PO_STAT'		=> $this->input->post('PO_STAT'));
				$this->m_purchase_po->updatePOInb($PO_NUM, $updPO);
				
				// UPDATE JOBDETAIL ITEM
				if($PO_STAT == 3)
				{
					$this->m_purchase_po->updatePRDet($PO_NUM, $PRJCODE, $PR_NUM, $ISDIRECT); // UPDATE JOBD ETAIL DAN PR
					
					// START : TRACK FINANCIAL TRACK
						$this->load->model('m_updash/m_updash', '', TRUE);
						$paramFT = array('DOC_NUM' 		=> $PO_NUM,
										'DOC_DATE' 		=> $PO_DATE,
										'DOC_EDATE' 	=> $PO_DUED,
										'PRJCODE' 		=> $PRJCODE,
										'FIELD_NAME1' 	=> 'FT_COP',
										'FIELD_NAME2' 	=> 'FM_COP',
										'TOT_AMOUNT'	=> $PO_TOTCOST);
						//$this->m_updash->finTrack($paramFT);
					// END : TRACK FINANCIAL TRACK
				}
				
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
											'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $PO_NUM;
					$MenuCode 		= 'MN020';
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
			}
							
			$url			= site_url('c_purchase/c_purchase_po/inbox/');
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallPR()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
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
			
			$data['title'] 				= $appName;
			$data['pageFrom']			= 'PR';
			$data['PRJCODE']			= $PRJCODE;
					
			$this->load->view('v_purchase/v_purchase_po/v_po_sel_req', $data);
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
			$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['form_action']	= site_url('c_purchase/c_purchase_po/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_purchase/c_purchase_po/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_purchase_po->count_all_num_rowsAllItem($PRJCODE);
			$data['vwAllItem'] 		= $this->m_purchase_po->viewAllItemMatBudget($PRJCODE)->result();
					
			$this->load->view('v_purchase/v_purchase_po/v_podir_selitem', $data);
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
		
		$PODate		= date('Y-m-d',strtotime($this->input->post('PODate')));
		$year		= date('Y',strtotime($this->input->post('PODate')));
		$month 		= (int)date('m',strtotime($this->input->post('PODate')));
		$date 		= (int)date('d',strtotime($this->input->post('PODate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_po_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_po_header
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
	
	function printirlist()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$PO_NUM		= $_GET['id'];
		$PO_NUM		= $this->url_encryption_helper->decode_url($PO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 	= $appName;
			$PO_DATE 		= "";
			$PO_NOTES 		= "";	
			$sqlPR 			= "SELECT PO_DATE, PO_NOTES FROM tbl_po_header WHERE PO_NUM = '$PO_NUM'";
			$resultaPR 		= $this->db->query($sqlPR)->result();
			foreach($resultaPR as $rowPR) :
				$PO_DATE 	= $rowPR->PO_DATE;
				$PO_NOTES 	= $rowPR->PO_NOTES;		
			endforeach;
			
			$data['PO_NUM'] 	= $PO_NUM;
			$data['PO_DATE'] 	= $PO_DATE;
			$data['PO_NOTES'] 	= $PO_NOTES;
			
			$data['countIR']	= $this->m_purchase_po->count_all_IR($PO_NUM);
			$data['vwIR'] 		= $this->m_purchase_po->get_all_IR($PO_NUM)->result();	
							
			$this->load->view('v_purchase/v_purchase_po/print_irlist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$PO_NUM		= $_GET['id'];
		$PO_NUM		= $this->url_encryption_helper->decode_url($PO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getPO 			= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();
			
			$data['default']['PO_NUM'] 		= $getPO->PO_NUM;
			$data['default']['PO_CODE'] 	= $getPO->PO_CODE;
			$data['default']['PO_TYPE'] 	= $getPO->PO_TYPE;
			$data['default']['PO_CAT'] 		= $getPO->PO_CAT;
			$data['default']['PO_DATE'] 	= $getPO->PO_DATE;
			$data['default']['PO_DUED'] 	= $getPO->PO_DUED;
			$data['default']['PRJCODE'] 	= $getPO->PRJCODE;
			$data['default']['SPLCODE'] 	= $getPO->SPLCODE;
			$data['default']['PR_NUM'] 		= $getPO->PR_NUM;
			$data['default']['PO_CURR'] 	= $getPO->PO_CURR;
			$data['default']['PO_CURRATE'] 	= $getPO->PO_CURRATE;
			$data['default']['PO_PAYTYPE'] 	= $getPO->PO_PAYTYPE;
			$data['default']['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['default']['PO_PLANIR'] 	= $getPO->PO_PLANIR;
			$data['default']['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['default']['PRJNAME'] 	= $getPO->PRJNAME;
			$data['default']['PO_STAT'] 	= $getPO->PO_STAT;
							
			$this->load->view('v_purchase/v_purchase_po/print_po', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}