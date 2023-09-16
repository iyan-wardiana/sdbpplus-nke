<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 Februari 2019
 * File Name	= C_4djU5m3ntI7m.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_4djU5m3ntI7m extends CI_Controller  
{
 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_4djU5m3ntI7m/pR7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function pR7_l5t_x1($offset=0) // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];		
		if ($this->session->userdata('login') == TRUE)
		{			
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Penyesuaian Item";
				$data["h2_title"] 	= "Daftar Proyek";
			}
			else
			{
				$data["h1_title"] 	= "Item Adjustment";
				$data["h2_title"] 	= "Project List";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN227';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_inventory/c_4djU5m3ntI7m/I7m4djU5m3nt_i4x/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function I7m4djU5m3nt_i4x()
	{
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// -------------------- START : SEARCHING METHOD --------------------
				// $chg_url		= 'c_finance/c_f180p0/cp2b0d18_all'
				
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
				$EXP_COLLD		= explode('~', $EXP_COLLD1);	
				$C_COLLD1		= count($EXP_COLLD);
				
				if($C_COLLD1 > 1)
				{
					$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
					$key		= $EXP_COLLD[0];
					$PRJCODE	= $EXP_COLLD[1];
					$start		= 0;
					$end		= 30;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_inventory/c_4djU5m3ntI7m/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_adjustment->count_all_4dj($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_adjustment->get_all_4dj($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
					
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Penyesuaian Item";
				$data["h3_title"] 	= "Penyesuaian Item";
			}
			else
			{
				$data["h2_title"] 	= "Material Transfer";
				$data["h3_title"] 	= "Material Transfer";
			}
			
			$linkBack			= site_url('c_inventory/c_4djU5m3ntI7m/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['addURL'] 	= site_url('c_inventory/c_4djU5m3ntI7m/a44_1stF0rM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['backURL'] 	= $linkBack;
			$data['PRJCODE']	= $PRJCODE;			
	 		$data["MenuCode"] 	= 'MN394';
			
			$this->load->view('v_inventory/v_asdjustment/item_adjustment', $data);
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
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE";
			$url			= site_url('c_inventory/c_4djU5m3ntI7m/I7m4djU5m3nt_i4x/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function a44_1stF0rM() // G
	{	
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Tambah";
				$data["h3_title"] 	= "Penyesuaian Item";
			}
			else
			{
				$data["h2_title"] 	= "Add";
				$data["h3_title"] 	= "Material Transfer";
			}
			
			$docPatternPosition		= 'Especially';
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_inventory/c_4djU5m3ntI7m/add_process');
			$linkBack				= site_url('c_inventory/c_4djU5m3ntI7m/I7m4djU5m3nt_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['backURL'] 		= $linkBack;
			$data['PRJCODE']		= $PRJCODE;	
			$MenuCode 				= 'MN394';
			$data["MenuCode"] 		= 'MN394';
			$data['vwDocPatt'] 		= $this->m_adjustment->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_inventory/v_asdjustment/item_adjustment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function p0p_4llM7R() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$COLLID		= $_GET['id'];
			$COLLID		= $this->url_encryption_helper->decode_url($COLLID);
			$plitWord	= explode('~', $COLLID);
			$PRJCODE	= $plitWord[0];
			$JOBCODE	= $plitWord[1];
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Material";
			}
			else
			{
				$data["h2_title"] 	= "Material List";
			}
			
			$data['PRJCODE'] 		= $PRJCODE;			
			$data['cAllItem']		= $this->m_adjustment->count_all_item($PRJCODE, $JOBCODE);
			$data['vwAllItem'] 	= $this->m_adjustment->get_all_item($PRJCODE, $JOBCODE)->result();
					
			$this->load->view('v_inventory/v_asdjustment/item_adjustment_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN394';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$ITMTSF_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$ITMTSF_CODE 	= $this->input->post('ITMTSF_CODE');
			
			//setting ITMTSF Date
			$ITMTSF_DATE	= date('Y-m-d',strtotime($this->input->post('ITMTSF_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('ITMTSF_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('ITMTSF_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('ITMTSF_DATE')));
			$ITMTSF_SENDD	= date('Y-m-d',strtotime($this->input->post('ITMTSF_SENDD')));
			$ITMTSF_RECD	= date('Y-m-d',strtotime($this->input->post('ITMTSF_SENDD')));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_DEST	= $this->input->post('PRJCODE_DEST');
			//$JOBCODEID 	= $this->input->post('ITMTSF_REFNO');
			$JOBCODEID 		= '';
			$ITMTSF_NOTE	= $this->input->post('ITMTSF_NOTE');
			$ITMTSF_NOTE2	= $this->input->post('ITMTSF_NOTE2');
			$ITMTSF_REVMEMO	= $this->input->post('ITMTSF_REVMEMO');
			$ITMTSF_SENDER 	= $this->input->post('ITMTSF_SENDER');
			$ITMTSF_RECEIVER= $this->input->post('ITMTSF_RECEIVER');
			$ITMTSF_STAT 	= $this->input->post('ITMTSF_STAT');
			
			$ITMTSF_CREATER	= $DefEmp_ID;
			$ITMTSF_CREATED = date('Y-m-d H:i:s');	
			
			$insITMTSFH		= array('ITMTSF_NUM' 	=> $ITMTSF_NUM,
									'ITMTSF_CODE'	=> $ITMTSF_CODE,
									'ITMTSF_DATE'	=> $ITMTSF_DATE,
									'ITMTSF_SENDD'	=> $ITMTSF_SENDD,
									'ITMTSF_RECD'	=> $ITMTSF_RECD,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_DEST'	=> $PRJCODE_DEST,
									'JOBCODEID'		=> $JOBCODEID,
									'ITMTSF_NOTE'	=> $ITMTSF_NOTE,
									'ITMTSF_NOTE2'	=> $ITMTSF_NOTE2,
									'ITMTSF_REVMEMO'=> $ITMTSF_REVMEMO,
									'ITMTSF_SENDER'	=> $ITMTSF_SENDER,
									'ITMTSF_RECEIVER'=> $ITMTSF_RECEIVER,
									'ITMTSF_STAT'	=> $ITMTSF_STAT,
									'ITMTSF_CREATER'=> $ITMTSF_CREATER,
									'ITMTSF_CREATED'=> $ITMTSF_CREATED,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_adjustment->add($insITMTSFH);
			
			foreach($_POST['data'] as $d)
			{
				$d['ITMTSF_NUM']	= $ITMTSF_NUM;
				$d['ITMTSF_CODE']	= $ITMTSF_CODE;
				$d['ITMTSF_DATE']	= $ITMTSF_DATE;
				$d['PRJCODE_DEST']	= $PRJCODE_DEST;
				$this->db->insert('tbl_item_tsfd',$d);
				
				/* HOLDED ON 28 JAN 2019
				if($ITMTSF_STAT == 3 && $AH_ISLAST == 1)
				{
					// UPDATE LAST POSITION
					$AS_CODE	= $d['AS_CODE'];
					$updLASTPOS	= array('AS_LASTPOS' 	=> $PRJCODE);
					$this->m_adjustment->updateLP($AS_CODE, $updLASTPOS);
				}*/
			}
			
			// UPDATE DETAIL
				// $this->m_adjustment->updateDet($ITMTSF_NUM, $PRJCODE, $ITMTSF_DATE);	// HOLDED ON 28 JAN 2019
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('ITMTSF_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $ITMTSF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "ITMTSF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_item_tsfh",// TABLE NAME
										'KEY_NAME'		=> "ITMTSF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "ITMTSF_STAT",	// NAMA FIELD STATUS
										'STATDOC' 		=> $ITMTSF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_TSFM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_TSFM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_TSFM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_TSFM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_TSFM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_TSFM_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_TSFM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITMTSF_NUM;
				$MenuCode 		= 'MN394';
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
			
			$url			= site_url('c_inventory/c_4djU5m3ntI7m/I7m4djU5m3nt_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // G
	{	
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ITMTSF_NUM		= $_GET['id'];
		$ITMTSF_NUM		= $this->url_encryption_helper->decode_url($ITMTSF_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_inventory/c_4djU5m3ntI7m/update_process');
			
			$getITMTSF 			= $this->m_adjustment->get_ITMTSF_by_number($ITMTSF_NUM)->row();									
									
			$data['default']['ITMTSF_NUM'] 		= $getITMTSF->ITMTSF_NUM;
			$data['default']['ITMTSF_CODE'] 	= $getITMTSF->ITMTSF_CODE;
			$data['default']['ITMTSF_DATE'] 	= $getITMTSF->ITMTSF_DATE;
			$data['default']['ITMTSF_SENDD'] 	= $getITMTSF->ITMTSF_SENDD;
			$data['default']['ITMTSF_RECD'] 	= $getITMTSF->ITMTSF_RECD;
			$data['default']['PRJCODE'] 		= $getITMTSF->PRJCODE;
			$data['PRJCODE']					= $getITMTSF->PRJCODE;
			$PRJCODE 							= $getITMTSF->PRJCODE;
			$data['default']['PRJCODE_DEST']	= $getITMTSF->PRJCODE_DEST;
			$data['default']['JOBCODEID'] 		= $getITMTSF->JOBCODEID;
			$data['default']['ITMTSF_NOTE'] 	= $getITMTSF->ITMTSF_NOTE;
			$data['default']['ITMTSF_NOTE2'] 	= $getITMTSF->ITMTSF_NOTE2;
			$data['default']['ITMTSF_REVMEMO'] 	= $getITMTSF->ITMTSF_REVMEMO;
			$data['default']['ITMTSF_STAT'] 	= $getITMTSF->ITMTSF_STAT;
			$data['default']['ITMTSF_AMOUNT'] 	= $getITMTSF->ITMTSF_AMOUNT;
			$data['default']['ITMTSF_SENDER'] 	= $getITMTSF->ITMTSF_SENDER;
			$data['default']['ITMTSF_RECEIVER'] = $getITMTSF->ITMTSF_RECEIVER;
			$data['default']['Patt_Year'] 		= $getITMTSF->Patt_Year;
			$data['default']['Patt_Month'] 		= $getITMTSF->Patt_Month;
			$data['default']['Patt_Date'] 		= $getITMTSF->Patt_Date;
			$data['default']['Patt_Number']		= $getITMTSF->Patt_Number;
			
			$data['MenuCode']					= 'MN394';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getITMTSF->ITMTSF_NUM;
				$MenuCode 		= 'MN394';
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
			
			$this->load->view('v_inventory/v_asdjustment/item_adjustment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$ITMTSF_NUM 	= $this->input->post('ITMTSF_NUM');
			$ITMTSF_CODE 	= $this->input->post('ITMTSF_CODE');
			
			//setting ITMTSF Date
			$ITMTSF_DATE	= date('Y-m-d',strtotime($this->input->post('ITMTSF_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('ITMTSF_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('ITMTSF_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('ITMTSF_DATE')));
			$ITMTSF_SENDD	= date('Y-m-d',strtotime($this->input->post('ITMTSF_SENDD')));
			$ITMTSF_RECD	= date('Y-m-d',strtotime($this->input->post('ITMTSF_SENDD')));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_DEST	= $this->input->post('PRJCODE_DEST');
			//$JOBCODEID 	= $this->input->post('ITMTSF_REFNO');
			$JOBCODEID 		= '';
			$ITMTSF_NOTE	= $this->input->post('ITMTSF_NOTE');
			$ITMTSF_NOTE2	= $this->input->post('ITMTSF_NOTE2');
			$ITMTSF_REVMEMO	= $this->input->post('ITMTSF_REVMEMO');
			$ITMTSF_SENDER 	= $this->input->post('ITMTSF_SENDER');
			$ITMTSF_RECEIVER= $this->input->post('ITMTSF_RECEIVER');
			$ITMTSF_STAT 	= $this->input->post('ITMTSF_STAT');
			
			$ITMTSF_CREATER	= $DefEmp_ID;
			$ITMTSF_CREATED = date('Y-m-d H:i:s');	
			
			$updITMTSFH		= array('ITMTSF_CODE'	=> $ITMTSF_CODE,
									'ITMTSF_DATE'	=> $ITMTSF_DATE,
									'ITMTSF_SENDD'	=> $ITMTSF_SENDD,
									'ITMTSF_RECD'	=> $ITMTSF_RECD,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_DEST'	=> $PRJCODE_DEST,
									'JOBCODEID'		=> $JOBCODEID,
									'ITMTSF_NOTE'	=> $ITMTSF_NOTE,
									'ITMTSF_NOTE2'	=> $ITMTSF_NOTE2,
									'ITMTSF_REVMEMO'=> $ITMTSF_REVMEMO,
									'ITMTSF_SENDER'	=> $ITMTSF_SENDER,
									'ITMTSF_RECEIVER'=> $ITMTSF_RECEIVER,
									'ITMTSF_STAT'	=> $ITMTSF_STAT);
			$this->m_adjustment->update($ITMTSF_NUM, $updITMTSFH);
			
			$this->m_adjustment->deleteDetail($ITMTSF_NUM);
						
			foreach($_POST['data'] as $d)
			{
				$d['ITMTSF_NUM']	= $ITMTSF_NUM;
				$d['ITMTSF_CODE']	= $ITMTSF_CODE;
				$d['ITMTSF_DATE']	= $ITMTSF_DATE;
				$d['PRJCODE_DEST']	= $PRJCODE_DEST;
				$this->db->insert('tbl_item_tsfd',$d);
			}
			
			// UPDATE DETAIL
				// $this->m_adjustment->updateDet($ITMTSF_NUM, $PRJCODE, $ITMTSF_DATE);	// HOLDED ON 28 JAN 2019
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('ITMTSF_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $ITMTSF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "ITMTSF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_item_tsfh",// TABLE NAME
										'KEY_NAME'		=> "ITMTSF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "ITMTSF_STAT",	// NAMA FIELD STATUS
										'STATDOC' 		=> $ITMTSF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_TSFM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_TSFM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_TSFM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_TSFM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_TSFM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_TSFM_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_TSFM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITMTSF_NUM;
				$MenuCode 		= 'MN394';
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
			
			$url			= site_url('c_inventory/c_4djU5m3ntI7m/I7m4djU5m3nt_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function inb0x() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_4djU5m3ntI7m/t5f_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function t5f_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Pemindahan";
			}
			else
			{
				$data["h1_title"] 	= "Transfer Approval";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN227';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_inventory/c_4djU5m3ntI7m/iN20_x1/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function iN20_x1() // OK
	{
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
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
					$start		= 0;
					$end		= 30;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_inventory/c_4djU5m3ntI7m/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				
				$num_rows 			= $this->m_adjustment->count_all_4djInb($PRJCODE, $key, $DefEmp_ID);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_adjustment->get_all_4djInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
					
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Persetujuan Pemindahan";
				$data["h3_title"] 	= "Persetujuan Material";
			}
			else
			{
				$data["h2_title"] 	= "Transfer Approval";
				$data["h3_title"] 	= "Transfer Approval";
			}

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_inventory/c_4djU5m3ntI7m/inb0x/');
			$data['PRJCODE'] 	= $PRJCODE;

			$MenuCode 			= 'MN394';
			$data["MenuCode"] 	= 'MN394';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN394';
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
			
			$this->load->view('v_inventory/v_itemtransfer/item_inb_transfer', $data);
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
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE";
			$url			= site_url('c_inventory/c_4djU5m3ntI7m/iN20_x1/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function up180djinb() // G
	{	
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ITMTSF_NUM		= $_GET['id'];
		$ITMTSF_NUM		= $this->url_encryption_helper->decode_url($ITMTSF_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_inventory/c_4djU5m3ntI7m/update_process_inb');
			
			$getITMTSF 			= $this->m_adjustment->get_ITMTSF_by_number($ITMTSF_NUM)->row();									
									
			$data['default']['ITMTSF_NUM'] 		= $getITMTSF->ITMTSF_NUM;
			$data['default']['ITMTSF_CODE'] 	= $getITMTSF->ITMTSF_CODE;
			$data['default']['ITMTSF_DATE'] 	= $getITMTSF->ITMTSF_DATE;
			$data['default']['ITMTSF_SENDD'] 	= $getITMTSF->ITMTSF_SENDD;
			$data['default']['ITMTSF_RECD'] 	= $getITMTSF->ITMTSF_RECD;
			$data['default']['PRJCODE'] 		= $getITMTSF->PRJCODE;
			$data['PRJCODE']					= $getITMTSF->PRJCODE;			
			$PRJCODE 							= $getITMTSF->PRJCODE;
			$data['default']['PRJCODE_DEST']	= $getITMTSF->PRJCODE_DEST;
			$data['default']['JOBCODEID'] 		= $getITMTSF->JOBCODEID;
			$data['default']['ITMTSF_NOTE'] 	= $getITMTSF->ITMTSF_NOTE;
			$data['default']['ITMTSF_NOTE2'] 	= $getITMTSF->ITMTSF_NOTE2;
			$data['default']['ITMTSF_REVMEMO'] 	= $getITMTSF->ITMTSF_REVMEMO;
			$data['default']['ITMTSF_STAT'] 	= $getITMTSF->ITMTSF_STAT;
			$data['default']['ITMTSF_AMOUNT'] 	= $getITMTSF->ITMTSF_AMOUNT;
			$data['default']['ITMTSF_SENDER'] 	= $getITMTSF->ITMTSF_SENDER;
			$data['default']['ITMTSF_RECEIVER'] = $getITMTSF->ITMTSF_RECEIVER;
			$data['default']['Patt_Year'] 		= $getITMTSF->Patt_Year;
			$data['default']['Patt_Month'] 		= $getITMTSF->Patt_Month;
			$data['default']['Patt_Date'] 		= $getITMTSF->Patt_Date;
			$data['default']['Patt_Number']		= $getITMTSF->Patt_Number;
			
			$MenuCode 							= 'MN229';
			$data["MenuCode"] 					= 'MN229';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getITMTSF->ITMTSF_NUM;
				$MenuCode 		= 'MN229';
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
			
			$this->load->view('v_inventory/v_itemtransfer/item_inb_transfer_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // G
	{
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$ITMTSF_NUM 	= $this->input->post('ITMTSF_NUM');
			$ITMTSF_CODE 	= $this->input->post('ITMTSF_CODE');
			
			//setting ITMTSF Date
			$ITMTSF_DATE	= date('Y-m-d',strtotime($this->input->post('ITMTSF_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('ITMTSF_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('ITMTSF_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('ITMTSF_DATE')));
			$ITMTSF_SENDD	= date('Y-m-d',strtotime($this->input->post('ITMTSF_SENDD')));
			$ITMTSF_RECD	= date('Y-m-d',strtotime($this->input->post('ITMTSF_SENDD')));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_DEST	= $this->input->post('PRJCODE_DEST');
			//$JOBCODEID 	= $this->input->post('ITMTSF_REFNO');
			$JOBCODEID 		= '';
			$ITMTSF_NOTE	= $this->input->post('ITMTSF_NOTE');
			$ITMTSF_NOTE2	= $this->input->post('ITMTSF_NOTE2');
			$ITMTSF_REVMEMO	= $this->input->post('ITMTSF_REVMEMO');
			$ITMTSF_SENDER 	= $this->input->post('ITMTSF_SENDER');
			$ITMTSF_RECEIVER= $this->input->post('ITMTSF_RECEIVER');
			$ITMTSF_STAT 	= $this->input->post('ITMTSF_STAT');
			
			$ITMTSF_CREATER	= $DefEmp_ID;
			$ITMTSF_CREATED = date('Y-m-d H:i:s');	
			
			$updITMTSFH		= array('ITMTSF_CODE'	=> $ITMTSF_CODE,
									'ITMTSF_DATE'	=> $ITMTSF_DATE,
									'ITMTSF_SENDD'	=> $ITMTSF_SENDD,
									'ITMTSF_RECD'	=> $ITMTSF_RECD,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_DEST'	=> $PRJCODE_DEST,
									'JOBCODEID'		=> $JOBCODEID,
									'ITMTSF_NOTE'	=> $ITMTSF_NOTE,
									'ITMTSF_NOTE2'	=> $ITMTSF_NOTE2,
									'ITMTSF_REVMEMO'=> $ITMTSF_REVMEMO,
									'ITMTSF_SENDER'	=> $ITMTSF_SENDER,
									'ITMTSF_RECEIVER'=> $ITMTSF_RECEIVER,
									'ITMTSF_STAT'	=> $ITMTSF_STAT);
			$this->m_adjustment->update($ITMTSF_NUM, $updITMTSFH);
									
			foreach($_POST['data'] as $d)
			{
				$ITM_CODE 			= $d['ITM_CODE'];
				$DEST_EXIST 		= $d['DEST_EXIST'];
				$ITMTSF_VOLM 		= $d['ITMTSF_VOLM'];
				$ITMTSF_PRICE 		= $d['ITMTSF_PRICE'];

				if($DEST_EXIST == 0)	// INSERT INTO TABLE
				{
					$this->m_adjustment->createITM($ITMTSF_NUM, $ITM_CODE, $PRJCODE, $PRJCODE_DEST, $ITMTSF_VOLM);
				}
				else
				{
					$this->m_adjustment->updateITM($ITMTSF_NUM, $ITM_CODE, $PRJCODE, $PRJCODE_DEST, $ITMTSF_VOLM);
				}
			}
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('ITMTSF_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $ITMTSF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "ITMTSF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_item_tsfh",// TABLE NAME
										'KEY_NAME'		=> "ITMTSF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "ITMTSF_STAT",	// NAMA FIELD STATUS
										'STATDOC' 		=> $ITMTSF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_TSFM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_TSFM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_TSFM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_TSFM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_TSFM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_TSFM_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_TSFM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITMTSF_NUM;
				$MenuCode 		= 'MN394';
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
			
			$url			= site_url('c_inventory/c_4djU5m3ntI7m/t5f_l5t_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_inventory/m_adjustment/m_adjustment', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ITMTSF_NUM		= $_GET['id'];
		$ITMTSF_NUM		= $this->url_encryption_helper->decode_url($ITMTSF_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "DAFTAR LALU LINTAS ALAT";
			}
			else
			{
				$data["h1_title"] 	= "TOOLS TRANSFER LIST";
			}
			$getITMTSF 				= $this->m_adjustment->get_ITMTSF_by_number($ITMTSF_NUM)->row();
			$data['ITMTSF_NUM'] 		= $getITMTSF->ITMTSF_NUM;
			$data['ITMTSF_CODE'] 	= $getITMTSF->ITMTSF_CODE;
			$data['ITMTSF_DATE'] 	= $getITMTSF->ITMTSF_DATE;
			$data['ITMTSF_SENDD']	= $getITMTSF->ITMTSF_SENDD;
			$data['ITMTSF_RECD'] 	= $getITMTSF->ITMTSF_RECD;
			$data['PRJCODE'] 		= $getITMTSF->PRJCODE;
			$PRJCODE 				= $getITMTSF->PRJCODE;
			$data['JOBCODEID'] 		= $getITMTSF->JOBCODEID;
			$data['ITMTSF_NOTE'] 	= $getITMTSF->ITMTSF_NOTE;
			$data['ITMTSF_NOTE2']	= $getITMTSF->ITMTSF_NOTE2;
			$data['ITMTSF_REVMEMO'] 	= $getITMTSF->ITMTSF_REVMEMO;
			$data['ITMTSF_STAT'] 	= $getITMTSF->ITMTSF_STAT;
			$data['ITMTSF_AMOUNT'] 	= $getITMTSF->ITMTSF_AMOUNT;
			$data['ITMTSF_SENDER'] 	= $getITMTSF->ITMTSF_SENDER;
			$data['ITMTSF_RECEIVER'] = $getITMTSF->ITMTSF_RECEIVER;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITMTSF_NUM;
				$MenuCode 		= 'MN394';
				$TTR_CATEG		= 'PRINT';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_inventory/v_asdjustment/item_adjustment_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}