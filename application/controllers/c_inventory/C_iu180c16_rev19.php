<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Desember 2017
 * File Name	= C_iu180c16.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_iu180c16  extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		function cut_text2($var, $len = 200, $txt_titik = "-") 
		{
			$var1	= explode("</p>",$var);
			$var	= $var1[0];
			if (strlen ($var) < $len) 
			{ 
				return $var; 
			}
			if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
			{
				return $match [1] . $txt_titik;
			}
			else
			{
				return substr ($var, 0, $len) . $txt_titik;
			}
		}
	}
	
 	function index() // G
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_iu180c16/prjl180c17/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl180c17() // G
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
				$data["h1_title"] 	= "Penggunaan Material";
			}
			else
			{
				$data["h1_title"] 	= "Use Material";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_inventory/c_iu180c16/gum180c16/?id=";

			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gum180c16() // G
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			// -------------------- START : SEARCHING METHOD --------------------

				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
				$EXP_COLLD		= explode('~', $EXP_COLLD1);
				$C_COLLD1		= count($EXP_COLLD);

				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$mxLS		= $EXP_COLLD[2];
					$end		= $EXP_COLLD[3];
					$start		= 0;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_inventory/c_iu180c16/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				$num_rows 			= $this->m_itemusage->count_all_UM($PRJCODE, $key);
				$data["cData"] 		= $num_rows;
				$data['vData']		= $this->m_itemusage->get_last_ten_UM($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];

			$data['title'] 			= $appName;
			$data['backURL'] 		= site_url('c_inventory/c_iu180c16/prjl180c17/?id='.$this->url_encryption_helper->encode_url($appName));

			$data["PRJCODE"] 		= $PRJCODE;
	 		$data["MenuCode"] 		= 'MN189';
			
			$this->load->view('v_inventory/v_itemusage/itemusage', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function add() // G
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
			$data['form_action']	= site_url('c_inventory/c_iu180c16/add_process');
			
			$data['backURL'] 		= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
			redirect('__l1y');
		}
	}
	
	function add_process() // G
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
					
			//$UM_NUM		= $this->input->post('UM_NUM');			
			$UM_CODE		= $this->input->post('UM_CODE');
			$UM_DATE		= date('Y-m-d',strtotime($this->input->post('UM_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('UM_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('UM_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('UM_DATE')));
			$PRJCODE		= $this->input->post('PRJCODE');
			
			$PRREFNO		= $this->input->post('UM_REFNO');	
			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $UM_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLUM_REFNO	= "$UM_REFNO";
					}
					else
					{
						$COLUM_REFNO	= "$COLUM_REFNO$UM_REFNO";
					}
				}
			}
			else
			{
				$COLUM_REFNO	= '';
			}
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN189';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$UM_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$UM_STAT		= $this->input->post('UM_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$UM_NOTE		= $this->input->post('UM_NOTE');
			$WH_CODE		= $this->input->post('WH_CODE');
			$UM_CREATED		= date('Y-m-d H:i:s');
			$UM_CREATER		= $DefEmp_ID;
			
			$insUM = array('UM_NUM' 		=> $UM_NUM,
							'UM_CODE' 		=> $UM_CODE,
							'UM_DATE'		=> $UM_DATE,
							'PRJCODE'		=> $PRJCODE,
							'JOBCODEID'		=> $COLUM_REFNO,
							'UM_STAT'		=> $UM_STAT,
							'UM_NOTE'		=> $UM_NOTE,
							'WH_CODE'		=> $WH_CODE,
							'UM_CREATED'	=> $UM_CREATED,
							'UM_CREATER'	=> $UM_CREATER,
							'Patt_Date'		=> $Patt_Date,
							'Patt_Month'	=> $Patt_Month,
							'Patt_Year'		=> $Patt_Year,
							'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_itemusage->add($insUM);
			
			foreach($_POST['data'] as $d)
			{
				$d['UM_NUM']	= $UM_NUM;
				$d['PRJCODE']	= $PRJCODE;
				$ITM_QTY		= $d['ITM_QTY'];
				$ITM_PRICE		= $d['ITM_PRICE'];
				$d['ITM_TOTAL']	= $ITM_QTY * $ITM_PRICE;
				$this->db->insert('tbl_um_detail',$d);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('UM_STAT');			// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $UM_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "UM",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_um_header",	// TABLE NAME
										'KEY_NAME'		=> "UM_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "UM_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $UM_STAT,		// TRANSACTION STATUS
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
										'DOC_CODE' 		=> $UM_NUM,
										'DOC_STAT' 		=> $UM_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_um_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // G
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$UM_NUM	= $_GET['id'];
			$UM_NUM	= $this->url_encryption_helper->decode_url($UM_NUM);
				
			$getrr 					= $this->m_itemusage->get_um_by_number($UM_NUM)->row();
			$PRJCODE				= $getrr->PRJCODE;
			$data["MenuCode"] 		= 'MN189';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Use Material';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_iu180c16/update_process');
			$data['backURL'] 		= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['default']['UM_NUM'] 		= $getrr->UM_NUM;
			$data['default']['UM_CODE'] 	= $getrr->UM_CODE;
			$data['default']['UM_DATE'] 	= $getrr->UM_DATE;
			$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
			$data['default']['JOBCODEID'] 	= $getrr->JOBCODEID;
			$data['default']['UM_STAT'] 	= $getrr->UM_STAT;
			$data['default']['UM_NOTE'] 	= $getrr->UM_NOTE;
			$data['default']['UM_NOTE2'] 	= $getrr->UM_NOTE2;
			$data['default']['REVMEMO']		= $getrr->REVMEMO;
			$data['default']['WH_CODE']		= $getrr->WH_CODE;
			$data['default']['Patt_Date'] 	= $getrr->Patt_Date;
			$data['default']['Patt_Month'] 	= $getrr->Patt_Month;
			$data['default']['Patt_Year'] 	= $getrr->Patt_Year;
			$data['default']['Patt_Number'] = $getrr->Patt_Number;
			
			$this->load->view('v_inventory/v_itemusage/itemusage_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
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
					
			$UM_NUM			= $this->input->post('UM_NUM');			
			$UM_CODE		= $this->input->post('UM_CODE');
			$UM_DATE		= date('Y-m-d',strtotime($this->input->post('UM_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('UM_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('UM_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('UM_DATE')));
			$PRJCODE		= $this->input->post('PRJCODE');
			
			$PRREFNO		= $this->input->post('UM_REFNO');	
			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $UM_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLUM_REFNO	= "$UM_REFNO";
					}
					else
					{
						$COLUM_REFNO	= "$COLUM_REFNO$UM_REFNO";
					}
				}
			}
			else
			{
				$COLUM_REFNO	= '';
			}
			$UM_STAT		= $this->input->post('UM_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$UM_NOTE		= $this->input->post('UM_NOTE');
			$WH_CODE		= $this->input->post('WH_CODE');
			$UM_CREATED		= date('Y-m-d H:i:s');
			$UM_CREATER		= $DefEmp_ID;
			
			$updUM = array('UM_CODE' 		=> $UM_CODE,
							'UM_DATE'		=> $UM_DATE,
							'PRJCODE'		=> $PRJCODE,
							'JOBCODEID'		=> $COLUM_REFNO,
							'UM_STAT'		=> $UM_STAT,
							'UM_NOTE'		=> $UM_NOTE,
							'WH_CODE'		=> $WH_CODE);
			$this->m_itemusage->updateUM($UM_NUM, $updUM);
			
			$this->m_itemusage->deleteUMDetail($UM_NUM);	
			
			foreach($_POST['data'] as $d)
			{
				$d['UM_NUM']	= $UM_NUM;
				$d['PRJCODE']	= $PRJCODE;
				$ITM_QTY		= $d['ITM_QTY'];
				$ITM_PRICE		= $d['ITM_PRICE'];
				$d['ITM_TOTAL']	= $ITM_QTY * $ITM_PRICE;
				$this->db->insert('tbl_um_detail',$d);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $UM_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "UM",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_um_header",	// TABLE NAME
										'KEY_NAME'		=> "UM_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "UM_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $UM_STAT,		// TRANSACTION STATUS
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
										'DOC_CODE' 		=> $UM_NUM,
										'DOC_STAT' 		=> $UM_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_um_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallpo() // G
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
			redirect('__l1y');
		}
	}
	
	function genCode() // G
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
	
	function pall180c16itm() // G
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
			redirect('__l1y');
		}
	}
	
    function in180c18b0x() // G
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_iu180c16/uM7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function uM7_l5t_x1() // G
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
				$data["h1_title"] 	= "Persetujuan Penerimaan";
			}
			else
			{
				$data["h1_title"] 	= "Receipt Approval";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_inventory/c_iu180c16/in180c18b0xx/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

    function in180c18b0xx() // G
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
			
			// -------------------- START : SEARCHING METHOD --------------------
				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$mxLS		= $EXP_COLLD[2];
					$end		= $EXP_COLLD[3];
					$start		= 0;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_inventory/c_ir180c15/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_itemusage->count_all_UM_OUT($PRJCODE, $key, $DefEmp_ID);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_itemusage->get_all_UM_OUT($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Use Material';
			$data['h3_title'] 	= 'approval';
	 		$data["MenuCode"] 	= 'MN190';
			
			$this->load->view('v_inventory/v_itemusage/inb_itemusage', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_inventory/c_iu180c16/in180c18b0xx/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function ui180c18box() // G
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$UM_NUM	= $_GET['id'];
			$UM_NUM	= $this->url_encryption_helper->decode_url($UM_NUM);
			
			$getrr 					= $this->m_itemusage->get_um_by_number($UM_NUM)->row();
			$PRJCODE				= $getrr->PRJCODE;
			$data["MenuCode"] 		= 'MN190';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Use Material';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_iu180c16/update_inbox_process');
			$data['backURL'] 		= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['default']['UM_NUM'] 		= $getrr->UM_NUM;
			$data['default']['UM_CODE'] 	= $getrr->UM_CODE;
			$data['default']['UM_DATE'] 	= $getrr->UM_DATE;
			$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
			$data['default']['JOBCODEID'] 	= $getrr->JOBCODEID;
			$data['default']['UM_STAT'] 	= $getrr->UM_STAT;
			$data['default']['UM_NOTE'] 	= $getrr->UM_NOTE;
			$data['default']['UM_NOTE2'] 	= $getrr->UM_NOTE2;
			$data['default']['REVMEMO']		= $getrr->REVMEMO;
			$data['default']['WH_CODE']		= $getrr->WH_CODE;
			$data['default']['Patt_Date'] 	= $getrr->Patt_Date;
			$data['default']['Patt_Month'] 	= $getrr->Patt_Month;
			$data['default']['Patt_Year'] 	= $getrr->Patt_Year;
			$data['default']['Patt_Number'] = $getrr->Patt_Number;
			
			$this->load->view('v_inventory/v_itemusage/inb_itemusage_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  function printdocument()
  {
    $this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);

    $sqlApp 		= "SELECT * FROM tappname";
    $resultaApp = $this->db->query($sqlApp)->result();
    foreach($resultaApp as $therow) :
      $appName = $therow->app_name;
    endforeach;

    if ($this->session->userdata('login') == TRUE)
    {
      $callData	= $_GET['id'];
      $callData	= $this->url_encryption_helper->decode_url($callData);
	  
	  $explDATA	= explode('~', $callData);
	  $PRJCODE	= $explDATA[0];
	  $UM_NUM	= $explDATA[1];
	  
	  $data['PRJCODE'] 	= $PRJCODE;
	  $data['UM_NUM'] 	= $UM_NUM;
	  
      $this->load->view('v_inventory/v_itemusage/print_itemuseage', $data);
    }
    else
    {
      redirect('__l1y');
    }
  }

	function update_inbox_process() // G
	{
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		date_default_timezone_set("Asia/Jakarta");
		$APPDATE 				= date('Y-m-d H:i:s');
			
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
					
			$UM_NUM			= $this->input->post('UM_NUM');			
			$UM_CODE		= $this->input->post('UM_CODE');
			$UM_DATE		= date('Y-m-d',strtotime($this->input->post('UM_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('UM_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('UM_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('UM_DATE')));
			$PRJCODE		= $this->input->post('PRJCODE');
			
			$PRREFNO		= $this->input->post('UM_REFNO');	
			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $UM_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLUM_REFNO	= "$UM_REFNO";
					}
					else
					{
						$COLUM_REFNO	= "$COLUM_REFNO$UM_REFNO";
					}
				}
			}
			else
			{
				$COLUM_REFNO	= '';
			}
			
			$UM_STAT		= $this->input->post('UM_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$UM_NOTE		= $this->input->post('UM_NOTE');
			$UM_NOTE2		= $this->input->post('UM_NOTE2');
			$WH_CODE		= $this->input->post('WH_CODE');
			$UM_APPROVED	= date('Y-m-d H:i:s');
			$UM_APPROVER	= $DefEmp_ID;
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $UM_NUM;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= $APPDATE;
				$AH_NOTES		= $this->input->post('UM_NOTE2');
				
				// UPDATE JOBDETAIL ITEM
				if($UM_STAT == 3)
				{
					// START : SAVE APPROVE HISTORY
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
												'AH_APPLEV'		=> $AH_APPLEV,
												'AH_APPROVER'	=> $AH_APPROVER,
												'AH_APPROVED'	=> $AH_APPROVED,
												'AH_NOTES'		=> $AH_NOTES,
												'AH_ISLAST'		=> $AH_ISLAST);										
						$this->m_updash->insAppHist($insAppHist);
					// END : SAVE APPROVE HISTORY
				}				
				
				$updUM = array('UM_STAT'		=> 7);
				$this->m_itemusage->updateUM($UM_NUM, $updUM);
			// END : SAVE APPROVE HISTORY
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
										'DOC_CODE' 		=> $UM_NUM,
										'DOC_STAT' 		=> 7,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_um_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			if($AH_ISLAST == 1)
			{
				$updUM = array('UM_STAT'		=> $UM_STAT);
				$this->m_itemusage->updateUM($UM_NUM, $updUM);
				
				if($UM_STAT == 3 || $UM_STAT == 4 || $UM_STAT == 5)
				{
					// START : SETTING L/R
						$this->load->model('m_updash/m_updash', '', TRUE);
						$PERIODED	= $UM_DATE;
						$PERIODM	= date('m', strtotime($PERIODED));
						$PERIODY	= date('Y', strtotime($PERIODED));
						$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
										AND YEAR(PERIODE) = '$PERIODY'";
						$resLR		= $this->db->count_all($getLR);
						if($resLR	== 0)
						{
							$this->m_updash->createLR($PRJCODE, $PERIODED);
						}
					// END : SETTING L/R
					
					$UM_AMOUNT 		= $this->m_itemusage->get_totam($UM_NUM);
					
					// START : TRACK FINANCIAL TRACK
						$this->load->model('m_updash/m_updash', '', TRUE);
						$paramFT = array('DOC_NUM' 		=> $UM_NUM,
										'DOC_DATE' 		=> $UM_DATE,
										'DOC_EDATE' 	=> $UM_DATE,
										'PRJCODE' 		=> $PRJCODE,
										'FIELD_NAME1' 	=> 'FT_UM',
										'FIELD_NAME2' 	=> 'FM_UM',
										'TOT_AMOUNT'	=> $UM_AMOUNT);
						$this->m_updash->finTrack($paramFT);
					// END : TRACK FINANCIAL TRACK
					
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $UM_NUM;
						$JournalType	= 'UM';
						$JournalH_Date	= $UM_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $UM_NUM;
						$LastUpdate		= $UM_APPROVED;
						$WH_CODE		= $WH_CODE;
						$Refer_Number	= '';
						$RefType		= 'WBSD';
						$PRJCODE		= $PRJCODE;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Desc'		=> $UM_NOTE2,
											'JournalH_Date' 	=> $JournalH_Date,
											'Company_ID' 		=> $Company_ID,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'Manual_No'			=> $UM_CODE,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE);
						$this->m_journal->createJournalH($JournalH_Code, $parameters); // OK
					// END : JOURNAL HEADER
					
					// START : JOURNAL DETAIL
						foreach($_POST['data'] as $d)
						{
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$ITM_CODE 		= $d['ITM_CODE'];
							$JOBCODEID 		= $d['JOBCODEID'];
							$ACC_ID 		= $d['ACC_ID'];
							$ITM_UNIT 		= $d['ITM_UNIT'];
							$ITM_GROUP 		= $d['ITM_GROUP'];
							$ITM_TYPE 		= $d['ITM_TYPE'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_PRICE'];
							$ITM_DISC 		= 0;
							$TAXCODE1 		= '';
							$TAXPRICE1 		= 0;
							
							$JournalH_Code	= $UM_NUM;
							$JournalType	= 'UM';
							$JournalH_Date	= $UM_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$LastUpdate		= $UM_APPROVED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= '';
							$RefType		= 'UM';
							$JSource		= 'UM';
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
												'JSource'			=> $JSource,
												'TRANS_CATEG' 		=> 'UM',			// UM = Use Material
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_GROUP' 		=> $ITM_GROUP,
												'ITM_TYPE' 			=> $ITM_TYPE,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1);
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
								
							// START : UPDATE STOCK
								$parameters1 = array('PRJCODE' 	=> $PRJCODE,
													'WH_CODE'	=> $WH_CODE,
													'JOBCODEID'	=> $JOBCODEID,
													'UM_NUM' 	=> $UM_NUM,
													'UM_CODE' 	=> $UM_CODE,
													'ITM_CODE' 	=> $ITM_CODE,
													'ITM_GROUP'	=> $ITM_GROUP,
													'ITM_QTY' 	=> $ITM_QTY,
													'ITM_PRICE' => $ITM_PRICE);
								$this->m_itemusage->updateITM_Min($parameters1);
							// START : UPDATE STOCK
							
							// START : RECORD TO ITEM HISTORY
								$this->m_journal->createITMHistMin($JournalH_Code, $parameters);
							// START : RECORD TO ITEM HISTORY
						}
					// END : JOURNAL DETAIL
			
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
												'DOC_CODE' 		=> $UM_NUM,
												'DOC_STAT' 		=> $UM_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_um_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $UM_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "UM",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_um_header",	// TABLE NAME
										'KEY_NAME'		=> "UM_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "UM_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $UM_STAT,		// TRANSACTION STATUS
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
			
			$url			= site_url('c_inventory/c_iu180c16/in180c18b0x/');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getProjName($myLove_the_an) // G
	{ 
		// check exixtensi projcewt code
		$getProj_Name 	= $this->m_itemusage_sd->getProjName($myLove_the_an);
		echo $getProj_Name;
	}
}