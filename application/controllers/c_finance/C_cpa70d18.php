<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 28 April 2018
 * File Name	= C_cpa70d18.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_cpa70d18 extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
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
	
 	function index() // OK
	{
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_cpa70d18/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj_l15t4ll() // OK
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
				$data["h1_title"] 	= "Penggunaan Kas Proyek";
			}
			else
			{
				$data["h1_title"] 	= "Project Cash Payment";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN147';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_finance/c_cpa70d18/cp2b0d18_all/?id=";
			
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

	function cp2b0d18_all() // OK 
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			// GET MENU DESC
				$mnCode				= 'MN147';
				$data["MenuApp"] 	= 'MN147';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			// -------------------- START : SEARCHING METHOD --------------------
				// $chg_url		= 'c_finance/c_cpa70d18/cp2b0d18_all'
				
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
					$end		= 100;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_finance/c_cpa70d18/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_cproj_payment->count_all_GEJ($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_cproj_payment->get_all_GEJ($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN147';
			$data['addURL'] 	= site_url('c_finance/c_cpa70d18/adda70d18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_finance/c_cpa70d18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN147';
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
			
			
			$this->load->view('v_finance/v_cproj_payment/v_cproj_payment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : FUNCTION TO SEARCH ENGINE --------------------
		function f4n7_5rcH()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE";
			$url			= site_url('c_finance/c_cpa70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : FUNCTION TO SEARCH ENGINE --------------------

  	function get_AllData() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("JournalH_Code",
									"Manual_No", 
									"JournalH_Date", 
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cproj_payment->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cproj_payment->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);
				
				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd		= site_url('c_finance/c_cpa70d18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secPrint1	= site_url('c_finance/c_cpa70d18/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				if($GEJ_STAT == 1) 
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   </a>";
				}
				else
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
                                        <i class='glyphicon glyphicon-print'></i>
                                    </a>
									</label>";
				}
				
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>$Manual_No</div>",
										  $JournalH_DateV,
										  $dataI['JournalH_Desc'],
										  $Journal_Amount,
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function adda70d18() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN147';
			$data["MenuCode"] 	= 'MN147';
			$data["MenuApp"] 	= 'MN147';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			// GET PRJCODE_HO
				$getGPRJP 			= $this->m_updash->get_PRJHO($PRJCODE)->row();
				$PRJCODE_HO			= $getGPRJP->PRJCODE_HO;
				$PRJPERIOD			= $getGPRJP->PRJPERIOD;
				$data['PRJCODE_HO'] = $PRJCODE_HO;
				$data['PRJPERIOD'] 	= $PRJPERIOD;
			
			// GET MENU DESC
				$mnCode				= 'MN147';
				$data["MenuApp"] 	= 'MN147';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$acc_number			= '';
			$data['form_action']= site_url('c_finance/c_cpa70d18/add_process');
			$data['backURL'] 	= site_url('c_finance/c_cpa70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cproj_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cproj_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$MenuCode 			= 'MN147';
			$data["MenuCode"] 	= 'MN147';
			$data['vwDocPatt'] 	= $this->m_cproj_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN147';
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
			
			$this->load->view('v_finance/v_cproj_payment/v_cproj_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function puSA0b28t18() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$THEROW		= $_GET['theRow'];
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['THEROW'] 		= $THEROW;
			$data['SOURCE'] 		= "O";
			$acc_number				= '';
			
			$data['countAllCOA']	= $this->m_cproj_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 		= $this->m_cproj_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$data['countAllItem']	= $this->m_cproj_payment->count_all_Account($PRJCODE);
			$data['vwAllItem'] 		= $this->m_cproj_payment->view_all_Account($PRJCODE)->result();
					
			$this->load->view('v_finance/v_cproj_payment/v_cproj_payment_selacc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$accYr			= date('Y', strtotime($JournalH_Date));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN147';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('JournalH_Desc'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> 'CPRJ',	// Cash Project
									'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'CPRJ',
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Created'			=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'proj_CodeHO'		=> $proj_CodeHO,
									'PRJPERIOD'			=> $PRJPERIOD,
									'GEJ_STAT'			=> $GEJ_STAT,
									'acc_number'		=> $acc_number);
			$this->m_cproj_payment->add($projGEJH);
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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
			
			$Base_DebetTOT		= 0;
			$Base_DebetTOT_Tax	= 0;
			foreach($_POST['data'] as $d)
			{
				$JournalH_Code	= $JournalH_Code;
				$Acc_Id			= $d['Acc_Id'];
				$ITM_CODE		= $d['ITM_CODE'];
				$proj_Code		= $d['proj_Code'];
				$JOBCODEID		= $d['JOBCODEID'];
				$JournalD_Pos	= $d['JournalD_Pos'];
				$isTax			= $d['isTax'];
				$ITM_GROUP		= $d['ITM_CATEG'];
				$ITM_VOLM		= $d['ITM_VOLM'];
				$ITM_UNIT		= $d['ITM_UNIT'];
				$ITM_PRICE		= $d['ITM_PRICE'];
				
				$PRJCODE		= $d['proj_Code'];
				$ACC_NUM		= $d['Acc_Id'];			// Detail Account
				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
				
				$ITM_Amount	= $d['JournalD_Amount'];
				$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;
				if($isTax == 0)
				{
					$Journal_Type		= '';
					$isTax				= 0;
					if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
					{
						$JournalD_Debet	= $d['JournalD_Amount'];
						$Base_Debet		= $d['JournalD_Amount'];
						$COA_Debet		= $d['JournalD_Amount'];
						$JournalD_Kredit= 0;
						$Base_Kredit	= 0;
						$COA_Kredit		= 0;
						
						$Base_DebetTOT		= $Base_DebetTOT + $Base_Debet;
					}
					
					$JournalD_Debet_tax		= 0;
					$Base_Debet_tax			= 0;
					$COA_Debet_tax			= 0;
					$JournalD_Kredit_tax	= 0;
					$Base_Kredit_tax		= 0;
					$COA_Kredit_tax			= 0;
				}
				else
				{
					$Journal_Type		= 'TAX';
					$isTax				= 1;
					if($JournalD_Pos = 'D') // Always Debit and Credit is for Cash/Bank Acount
					{
						$JournalD_Debet_tax		= $d['JournalD_Amount'];
						$Base_Debet_tax			= $d['JournalD_Amount'];
						$COA_Debet_tax			= $d['JournalD_Amount'];
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
						
						$Base_DebetTOT_Tax	= $Base_DebetTOT_Tax + $Base_Debet_tax;
					}
					
					$JournalD_Debet		= 0;
					$Base_Debet			= 0;
					$COA_Debet			= 0;
					$JournalD_Kredit	= 0;
					$Base_Kredit		= 0;
					$COA_Kredit			= 0;
				}
				
				$curr_rate				= 1;
				$isDirect				= 1;
				$Ref_Number				= $d['Ref_Number'];
				$Other_Desc				= $d['Other_Desc'];
				$Journal_DK				= $JournalD_Pos;
				$Journal_Type			= $Journal_Type;
				$isTax					= $isTax;
				
				// Insert into tbl_journal_detail (D) for All Expenses
				$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, JOBCODEID, Currency_id, JournalD_Debet, 
								JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
								Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
								curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_UNIT, ITM_PRICE, Ref_Number, Other_Desc,
								Journal_DK, Journal_Type, isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD) VALUE
								('$JournalH_Code', '$Acc_Id', '$proj_Code', '$JOBCODEID', 'IDR', $JournalD_Debet, 
								$JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, 
								$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
								'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$Ref_Number', '$Other_Desc', 'D', 
								'$Journal_Type', $isTax, $GEJ_STAT, '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD')";
				$this->db->query($insSQL);
			}
			
			$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
			
			// Insert into tbl_journal_detail (K) for Cash
			$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
								JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
								curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
								isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD) VALUE
								('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
								$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$Ref_Number', 
								'$Other_Desc', 'K', '$Journal_Type', $isTax, $GEJ_STAT, '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD')";
			$this->db->query($insSQLK);
			
			$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJH3);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN147';
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
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "CPRJ",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_journalheader",	// TABLE NAME
										'KEY_NAME'		=> "JournalH_Code",	// KEY OF THE TABLE
										'STAT_NAME' 	=> "GEJ_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $GEJ_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_CPRJ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_CPRJ_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_CPRJ_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_CPRJ_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_CPRJ_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_CPRJ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_CPRJ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			$url			= site_url('c_finance/c_cpa70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up0b28t18() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN147';
			$data["MenuCode"] 	= 'MN147';
			$data["MenuApp"] 	= 'MN147';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 							= $this->m_cproj_payment->get_CPRJ_by_number($JournalH_Code)->row();
			$data['default']['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['default']['Manual_No'] 		= $getGEJ->Manual_No;
			$data['default']['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['default']['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['default']['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['default']['proj_Code'] 		= $getGEJ->proj_Code;
			$data['default']['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['default']['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['default']['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE							= $getGEJ->proj_Code;
			$PRJCODE_HO							= $getGEJ->proj_CodeHO;
			$data['default']['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['default']['acc_number'] 		= $getGEJ->acc_number;
			$acc_number							= $getGEJ->acc_number;
			$data['default']['Journal_Amount'] 	= $getGEJ->Journal_Amount;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_cpa70d18/update_process');
			$data['backURL'] 	= site_url('c_finance/c_cpa70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cproj_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cproj_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$MenuCode 			= 'MN147';
			$data["MenuCode"] 	= 'MN147';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN147';
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
			
			$this->load->view('v_finance/v_cproj_payment/v_cproj_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$accYr			= date('Y', strtotime($JournalH_Date));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			$PRJCODE 		= $this->input->post('proj_Code');
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('JournalH_Desc2'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJHA);
			
			// UPDATE JOBDETAIL ITEM
				/*if($GEJ_STAT == 3)
				{
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
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
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> 7,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
					
					if($AH_ISLAST == 1)
					{
						$upJH2	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($upJH2);
					
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
													'DOC_CODE' 		=> $JournalH_Code,
													'DOC_STAT' 		=> $GEJ_STAT,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> $completeName,
													'TBLNAME'		=> "tbl_journalheader");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}
				}*/
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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
			
			if($GEJ_STAT == 3)
			{
				$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH);
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
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if($AH_ISLAST == 1)
				{
					$upJH2	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH2);
				
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			
			if($GEJ_STAT == 9) // Void
			{
				$JournalH_Codex	= $JournalH_Code;
				$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH);
				
				$upJD	= "UPDATE tbl_journaldetail SET isVoid = 1 WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJD);
				
				$JournalH_Code2	= "V$JournalH_Code";
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code2,
										'JournalH_Desc' 	=> $AH_NOTES,
										'Manual_No' 		=> $Manual_No,
										'JournalType' 		=> 'VCPRJ',	// Cash Project
										'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
										'JournalH_Date'		=> $JournalH_Date,
										'Company_ID'		=> $comp_init,
										'Reference_Type'	=> 'VCPRJ',
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $GEJ_CREATED,
										'Created'			=> $GEJ_CREATED,
										'Wh_id'				=> $PRJCODE,
										'proj_Code'			=> $PRJCODE,
										'GEJ_STAT'			=> $GEJ_STAT,
										'acc_number'		=> $acc_number);
				$this->m_cproj_payment->add($projGEJH);
			
				$Base_KreditTOT		= 0;
				$Base_KreditTOT_Tax	= 0;
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $JournalH_Code2;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$proj_Code		= $d['proj_Code'];
					$JOBCODEID		= $d['JOBCODEID'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$ITM_GROUP		= $d['ITM_CATEG'];
					$ITM_VOLM		= $d['ITM_VOLM'];
					$ITM_UNIT		= $d['ITM_UNIT'];
					
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					if($isTax == 0)
					{
						$Journal_Type		= '';
						$isTax				= 0;
						
						if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet	= 0;
							$Base_Debet		= 0;
							$COA_Debet		= 0;
							$JournalD_Kredit= $d['JournalD_Amount'];
							$Base_Kredit	= $d['JournalD_Amount'];
							$COA_Kredit		= $d['JournalD_Amount'];
							
							// START : Update to COA - Debit
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
															Base_Kredit2 = Base_Kredit2+$Base_Kredit, BaseK_$accYr = BaseK_$accYr+$Base_Kredit
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
							// START : UPDATE L/R
								if($ITM_GROUP == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'SC')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'GE')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
								
							$Base_KreditTOT		= $Base_KreditTOT + $Base_Kredit;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
					}
					else
					{
						$Journal_Type		= 'TAX';
						$isTax				= 1;
						if($JournalD_Pos = 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet_tax		= 0;
							$Base_Debet_tax			= 0;
							$COA_Debet_tax			= 0;
							$JournalD_Kredit_tax	= $d['JournalD_Amount'];
							$Base_Kredit_tax		= $d['JournalD_Amount'];
							$COA_Kredit_tax			= $d['JournalD_Amount'];
							
							// START : Update to COA - Debit
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOAD	= "UPDATE tbl_chartaccount 
														SET Base_Kredit_tax = Base_Kredit_tax+$Base_Kredit_tax, 
															Base_Kredit_tax2 = Base_Kredit_tax2+$Base_Kredit_tax
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
														
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
							
							// START : UPDATE L/R
								if($ITM_GROUP == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'SC')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit_tax
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'GE')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
								
							$Base_KreditTOT_Tax	= $Base_KreditTOT_Tax + $Base_Kredit_tax;
						}
						
						$JournalD_Debet		= 0;
						$Base_Debet			= 0;
						$COA_Debet			= 0;
						$JournalD_Kredit	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
					}
					
					$curr_rate				= 1;
					$isDirect				= 1;
					$Ref_Number				= $d['Ref_Number'];
					$Other_Desc				= $d['Other_Desc'];
					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					// Insert into tbl_journal_detail (D) for All Expenses
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax, JOBCODEID) VALUE
									('$JournalH_Code', '$Acc_Id', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
									$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit,
									$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'D', '$Journal_Type', $isTax, '$JOBCODEID')";
					$this->db->query($insSQL);
					
					// UPDATE INTO USE JOBLISTDETAIL
					/*$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = 1, ITM_USED_AM = ITM_USED_AM-$JournalD_Kredit
								WHERE JOBCODEID = '$JOBCODEID'";*/
					$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED - $ITM_VOLM, ITM_USED_AM = ITM_USED_AM-$JournalD_Kredit
								WHERE JOBCODEID = '$JOBCODEID'";
					//$this->db->query($upJL1);
				}
				$BaseKreditTOT	= $Base_KreditTOT + $Base_KreditTOT_Tax;
				
				// Insert into tbl_journal_detail (D) for Cash/Bank
				$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet,
									JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax) 
									VALUE
									('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_KreditTOT, $Base_KreditTOT_Tax,
									$Base_KreditTOT, $Base_KreditTOT_Tax, $Base_KreditTOT, $Base_KreditTOT_Tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'D', '$Journal_Type', $isTax)";
				$this->db->query($insSQLK);
				
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseKreditTOT 
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
				
				$ACC_NUM		= $acc_number;				
				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
				
				if($jmD > 0)
				{
					$SYNC_PRJ	= '';
					for($i=0; $i < $jmD; $i++)
					{
						$SYNC_PRJ	= $dataPecah[$i];
						$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$BaseKreditTOT, 
											Base_Debet2 = Base_Debet2+$BaseKreditTOT, BaseD_$accYr = BaseD_$accYr+$BaseKreditTOT
										WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$acc_number'";
						$this->db->query($sqlUpdCOA);
					}
				}
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Codex,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($GEJ_STAT == 4)
			{
				$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
									WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJHA);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
					
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($JournalH_Code);
				// END : DELETE HISTORY
			}
			elseif($GEJ_STAT == 5)
			{
				$upJH2	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH2);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else
			{
				$projGEJH 	= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> 'CPRJ',	// Cash Project
									'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'CPRJ',
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'acc_number'		=> $acc_number);									
				$this->m_cproj_payment->updateCPRJ($JournalH_Code, $projGEJH);
				
				$this->m_cproj_payment->deleteCPRJDetail($JournalH_Code);
				
				$Base_DebetTOT		= 0;
				$Base_DebetTOT_Tax	= 0;
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $JournalH_Code;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$proj_Code		= $d['proj_Code'];
					$JOBCODEID		= $d['JOBCODEID'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$ITM_GROUP		= $d['ITM_CATEG'];
					$ITM_VOLM		= $d['ITM_VOLM'];
					$ITM_UNIT		= $d['ITM_UNIT'];
					
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					$ITM_Amount	= $d['JournalD_Amount'];
					$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;
					if($isTax == 0)
					{
						$Journal_Type		= '';
						$isTax				= 0;
						
						if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet	= $d['JournalD_Amount'];
							$Base_Debet		= $d['JournalD_Amount'];
							$COA_Debet		= $d['JournalD_Amount'];
							$JournalD_Kredit= 0;
							$Base_Kredit	= 0;
							$COA_Kredit		= 0;
							if($GEJ_STAT == 3 && $AH_ISLAST == 1)
							{
								// START : Update to COA - Debit
									/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
															Base_Debet2 = Base_Debet2+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOA);*/
									
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
																Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
								
								// START : UPDATE L/R
									if($ITM_GROUP == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'SC')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'GE')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
								
								// START : Update ITM Used
									// 1. UPDATE JOBLIST
										$JOBCODEID	= $d['JOBCODEID'];
										$ITM_CODE	= $d['ITM_CODE'];
										$ITM_VOLM	= $d['ITM_VOLM'];
										
										$ITM_USED	= 0;
										$ITM_USEDAM	= 0;
										$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
															WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
																AND ITM_CODE = '$ITM_CODE'";
										$resUSED1	= $this->db->query($sqlUSED1)->result();
										foreach($resUSED1 as $rowUSED1):
											$ITM_USED	= $rowUSED1->ITM_USED;
											$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
										endforeach;
										
										$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
															ITM_LASTP	= $ITM_PRICE,
															ITM_USED 	= ITM_USED+$ITM_VOLM, 
															ITM_USED_AM = ITM_USED_AM+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
															AND ITM_CODE = '$ITM_CODE'";
										$this->db->query($sqlUpdJOBL);
										
									// 2. UPDATE ITEM LIST
										$ITM_OUT	= 0;
										$UM_VOLM	= 0;
										$UM_AMOUNT	= 0;
										$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
															WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
																AND ITM_CODE = '$ITM_CODE'";
										$resUSED1	= $this->db->query($sqlUSED1)->result();
										foreach($resUSED1 as $rowUSED1):
											$ITM_USED	= $rowUSED1->ITM_USED;
											$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
										endforeach;
										$sqlUpdITML	= "UPDATE tbl_item SET
															ITM_LASTP	= $ITM_PRICE,
															ITM_OUT 	= ITM_OUT+$ITM_VOLM,
															UM_VOLM 	= UM_VOLM+$ITM_VOLM,
															UM_AMOUNT 	= UM_AMOUNT+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
										$this->db->query($sqlUpdITML);			
								// END : Update ITM Used
							}
							$Base_DebetTOT		= $Base_DebetTOT + $Base_Debet;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
					}
					else
					{
						$Journal_Type		= 'TAX';
						$isTax				= 1;
						if($JournalD_Pos = 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet_tax		= $d['JournalD_Amount'];
							$Base_Debet_tax			= $d['JournalD_Amount'];
							$COA_Debet_tax			= $d['JournalD_Amount'];
							$JournalD_Kredit_tax	= 0;
							$Base_Kredit_tax		= 0;
							$COA_Kredit_tax			= 0;
							if($GEJ_STAT == 3 && $AH_ISLAST == 1)
							{
								// START : Update to COA - Debit
									/*$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET 
															Base_Debet_tax = Base_Debet_tax+$Base_Debet_tax, 
															Base_Debet_tax2 = Base_Debet_tax2+$Base_Debet_tax
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOAD);*/
									
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET 
															Base_Debet_tax = Base_Debet_tax+$Base_Debet_tax, 
															Base_Debet_tax2 = Base_Debet_tax2+$Base_Debet_tax
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
								
								// START : UPDATE L/R
									if($ITM_GROUP == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'SC')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet_tax
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'GE')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
							}
							$Base_DebetTOT_Tax	= $Base_DebetTOT_Tax + $Base_Debet_tax;
						}
						
						$JournalD_Debet		= 0;
						$Base_Debet			= 0;
						$COA_Debet			= 0;
						$JournalD_Kredit	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
					}
					
					$curr_rate				= 1;
					$isDirect				= 1;
					$Ref_Number				= $d['Ref_Number'];
					$Other_Desc				= $d['Other_Desc'];
					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					// Insert into tbl_journal_detail (D) for All Expenses
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE, ITM_UNIT, Ref_Number, Other_Desc,
									Journal_DK, Journal_Type, isTax, JOBCODEID)
									VALUE
									('$JournalH_Code', '$Acc_Id', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
									$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit,
									$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_PRICE', '$ITM_UNIT', '$Ref_Number', '$Other_Desc', 'D', 
									'$Journal_Type', $isTax, '$JOBCODEID')";
					$this->db->query($insSQL);
					
					// UPDATE INTO USE JOBLISTDETAIL
					/*$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = 1, ITM_USED_AM = ITM_USED_AM+$JournalD_Debet
								WHERE JOBCODEID = '$JOBCODEID'";*/
					$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM+$JournalD_Debet
								WHERE JOBCODEID = '$JOBCODEID'";
					//$this->db->query($upJL1);
				}
				
				$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
				
				// Insert into tbl_journal_detail (K) for Cash
				$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
									JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax) VALUE
									('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
									$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$ITM_CODE', 
									'$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'K', '$Journal_Type', $isTax)";
				$this->db->query($insSQLK);
				
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					//$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if($GEJ_STAT == 3 && $AH_ISLAST == 1)
				{
					/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$BaseDebetTOT, 
											Base_Kredit2 = Base_Kredit2+$BaseDebetTOT
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number'";
					$this->db->query($sqlUpdCOA);*/
					
					$ACC_NUM		= $acc_number;				
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$BaseDebetTOT, 
												Base_Kredit2 = Base_Kredit2+$BaseDebetTOT, BaseK_$accYr = BaseK_$accYr+$BaseDebetTOT
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$acc_number'";
							$this->db->query($sqlUpdCOA);
						}
					}
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				elseif($GEJ_STAT == 2 || $GEJ_STAT == 5)
				{
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			
			$upJHA			= "UPDATE tbl_journaldetail SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			//$this->db->query($upJHA);
			
			if($GEJ_STAT != 3)
			{	
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN147';
				$TTR_CATEG		= 'U-P';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "CPRJ",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_journalheader",	// TABLE NAME
										'KEY_NAME'		=> "JournalH_Code",	// KEY OF THE TABLE
										'STAT_NAME' 	=> "GEJ_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $GEJ_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_CPRJ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_CPRJ_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_CPRJ_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_CPRJ_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_CPRJ_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_CPRJ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_CPRJ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			$url			= site_url('c_finance/c_cpa70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		//$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$CB_NUM1	= $_GET['id'];
		$CB_NUM		= $this->url_encryption_helper->decode_url($CB_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			$data['CB_NUM'] = $CB_NUM;
			$data['title'] 	= $appName;
			
			$this->load->view('v_finance/v_cproj_payment/print_kaspry', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function cpothb80da8() // OK
	{
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_cpa70d18/cpothb80da8_pr7l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function cpothb80da8_pr7l() // OK
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
				$data["h1_title"] 	= "Biaya Rupa-Rupa";
			}
			else
			{
				$data["h1_title"] 	= "Others Expenses";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN353';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_finance/c_cpa70d18/cpothb80da8_4ll/?id=";
			
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

	function cpothb80da8_4ll() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			// -------------------- START : SEARCHING METHOD --------------------
				// $chg_url		= 'c_finance/c_cpa70d18/cp2b0d18_all'
				
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
					$end		= 100;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_finance/c_cpa70d18/f4n7_5rcH07h/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_cproj_payment->count_all_GEJOTH($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_cproj_payment->get_all_GEJOTH($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN353';
			$data['addURL'] 	= site_url('c_finance/c_cpa70d18/addcpothb80da8/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_finance/c_cpa70d18/cpothb80da8_pr7l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN353';
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
			
			$this->load->view('v_finance/v_expense_oth/v_expense_oth', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : FUNCTION TO SEARCH ENGINE --------------------
		function f4n7_5rcH07h()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE";
			$url			= site_url('c_finance/c_cpa70d18/cpothb80da8_4ll/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : FUNCTION TO SEARCH ENGINE --------------------

  	function get_AllData_i() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("JournalH_Code",
									"Manual_No", 
									"JournalH_Date", 
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cproj_payment->get_AllDataC_i($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cproj_payment->get_AllDataL_i($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);
				
				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd		= site_url('c_finance/c_cpa70d18/upothb80da8/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secPrint1	= site_url('c_finance/c_cpa70d18/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
									
									
				if($GEJ_STAT == 1) 
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   </a>";
				}
				else
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
                                        <i class='glyphicon glyphicon-print'></i>
                                    </a>
									</label>";
				}
				
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>$Manual_No</div>",
										  $JournalH_DateV,
										  $dataI['JournalH_Desc'],
										  $Journal_Amount,
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function addcpothb80da8() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		
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
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$task				= '';
			$acc_number			= '';
			$data['form_action']= site_url('c_finance/c_cpa70d18/addcpothb80da8_process');
			$data['backURL'] 	= site_url('c_finance/c_cpa70d18/cpothb80da8_4ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cproj_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cproj_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$MenuCode 			= 'MN353';
			$data["MenuCode"] 	= 'MN353';
			$data['vwDocPatt'] 	= $this->m_cproj_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN353';
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
			
			$this->load->view('v_finance/v_expense_oth/v_expense_oth_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addcpothb80da8_process() // OK
	{
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			
			// START - PEMBENTUKAN GENERATE CODE				
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN353';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('JournalH_Desc'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> 'O-EXP',	// Cash Project
									'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'O-EXP',
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Created'			=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'GEJ_STAT'			=> $GEJ_STAT,
									'acc_number'		=> $acc_number);
			$this->m_cproj_payment->add($projGEJH);
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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
			
			$Base_DebetTOT		= 0;
			$Base_DebetTOT_Tax	= 0;
			foreach($_POST['data'] as $d)
			{
				$JournalH_Code	= $JournalH_Code;
				$Acc_Id			= $d['Acc_Id'];
				$ITM_CODE		= $d['ITM_CODE'];
				$proj_Code		= $d['proj_Code'];
				$JOBCODEID		= $d['JOBCODEID'];
				$JournalD_Pos	= $d['JournalD_Pos'];
				$isTax			= $d['isTax'];
				$ITM_GROUP		= $d['ITM_CATEG'];
				$ITM_VOLM		= $d['ITM_VOLM'];
				$ITM_UNIT		= $d['ITM_UNIT'];
				$ITM_PRICE		= $d['ITM_PRICE'];
				
				$PRJCODE		= $d['proj_Code'];
				$ACC_NUM		= $d['Acc_Id'];			// Detail Account
				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
				
				$ITM_Amount	= $d['JournalD_Amount'];
				if($ITM_VOLM == 0)
				{
					echo "Ada pembagian dengan angka nol pada item $ITM_CODE.";
					return false;
				}
				$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;
				if($isTax == 0)
				{
					$Journal_Type		= '';
					$isTax				= 0;
					if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
					{
						$JournalD_Debet	= $d['JournalD_Amount'];
						$Base_Debet		= $d['JournalD_Amount'];
						$COA_Debet		= $d['JournalD_Amount'];
						$JournalD_Kredit= 0;
						$Base_Kredit	= 0;
						$COA_Kredit		= 0;
						
						$Base_DebetTOT		= $Base_DebetTOT + $Base_Debet;
					}
					
					$JournalD_Debet_tax		= 0;
					$Base_Debet_tax			= 0;
					$COA_Debet_tax			= 0;
					$JournalD_Kredit_tax	= 0;
					$Base_Kredit_tax		= 0;
					$COA_Kredit_tax			= 0;
				}
				else
				{
					$Journal_Type		= 'TAX';
					$isTax				= 1;
					if($JournalD_Pos = 'D') // Always Debit and Credit is for Cash/Bank Acount
					{
						$JournalD_Debet_tax		= $d['JournalD_Amount'];
						$Base_Debet_tax			= $d['JournalD_Amount'];
						$COA_Debet_tax			= $d['JournalD_Amount'];
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
						
						$Base_DebetTOT_Tax	= $Base_DebetTOT_Tax + $Base_Debet_tax;
					}
					
					$JournalD_Debet		= 0;
					$Base_Debet			= 0;
					$COA_Debet			= 0;
					$JournalD_Kredit	= 0;
					$Base_Kredit		= 0;
					$COA_Kredit			= 0;
				}
				
				$curr_rate		= 1;
				$isDirect		= 1;
				$Ref_Number		= $d['Ref_Number'];
				$Other_Desc		= $d['Other_Desc'];
				$Journal_DK		= $JournalD_Pos;
				$Journal_Type	= $Journal_Type;
				$isTax			= $isTax;
				
				// Insert into tbl_journal_detail (D) for All Expenses
				$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, JOBCODEID, Currency_id, JournalD_Debet, 
								JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
								Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
								curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_UNIT, ITM_PRICE, Ref_Number, Other_Desc,
								Journal_DK, Journal_Type, isTax, GEJ_STAT) VALUE
								('$JournalH_Code', '$Acc_Id', '$proj_Code', '$JOBCODEID', 'IDR', $JournalD_Debet, 
								$JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, 
								$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
								'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$Ref_Number', '$Other_Desc', 'D', 
								'$Journal_Type', $isTax, $GEJ_STAT)";
				$this->db->query($insSQL);
			}
			
			$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
			
			// Insert into tbl_journal_detail (K) for Cash
			$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
								JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
								curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
								isTax, GEJ_STAT) VALUE
								('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
								$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$Ref_Number', 
								'$Other_Desc', 'K', '$Journal_Type', $isTax, $GEJ_STAT)";
			$this->db->query($insSQLK);
			
			$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJH3);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN353';
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
			
			$url			= site_url('c_finance/c_cpa70d18/cpothb80da8_pr7l/?id='.$this->url_encryption_helper->encode_url($proj_Code));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function upothb80da8() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		
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
			
			$getGEJ 							= $this->m_cproj_payment->get_CPRJ_by_number($JournalH_Code)->row();
			$data['default']['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['default']['Manual_No'] 		= $getGEJ->Manual_No;
			$data['default']['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['default']['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['default']['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['default']['proj_Code'] 		= $getGEJ->proj_Code;
			$data['default']['PRJCODE'] 		= $getGEJ->proj_Code;
			$PRJCODE							= $getGEJ->proj_Code;
			$data['default']['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['default']['acc_number'] 		= $getGEJ->acc_number;
			$acc_number							= $getGEJ->acc_number;
			$data['default']['Journal_Amount'] 	= $getGEJ->Journal_Amount;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_cpa70d18/upothb80da8_process');
			$data['backURL'] 	= site_url('c_finance/c_cpa70d18/cpothb80da8_4ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cproj_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cproj_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$MenuCode 			= 'MN353';
			$data["MenuCode"] 	= 'MN353';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN353';
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
			
			$this->load->view('v_finance/v_expense_oth/v_expense_oth_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function upothb80da8_process() // OK
	{
		$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$accYr			= date('Y', strtotime($JournalH_Date));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			$PRJCODE 		= $this->input->post('proj_Code');
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('JournalH_Desc2'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJHA);
			
			// UPDATE JOBDETAIL ITEM
				/*if($GEJ_STAT == 3)
				{
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
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
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> 7,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
					
					if($AH_ISLAST == 1)
					{
						$upJH2	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($upJH2);
					
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
													'DOC_CODE' 		=> $JournalH_Code,
													'DOC_STAT' 		=> $GEJ_STAT,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> $completeName,
													'TBLNAME'		=> "tbl_journalheader");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}
				}*/
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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
			
			if($GEJ_STAT == 3)
			{
				$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH);
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
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if($AH_ISLAST == 1)
				{
					$upJH2	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH2);
				
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			
			if($GEJ_STAT == 9) // Void
			{
				$JournalH_Codex	= $JournalH_Code;
				$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH);
				
				$upJD	= "UPDATE tbl_journaldetail SET isVoid = 1 WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJD);
				
				$JournalH_Code2	= "V$JournalH_Code";
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code2,
										'JournalH_Desc' 	=> $AH_NOTES,
										'Manual_No' 		=> $Manual_No,
										'JournalType' 		=> 'VO-EXP',	// Cash Project
										'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
										'JournalH_Date'		=> $JournalH_Date,
										'Company_ID'		=> $comp_init,
										'Reference_Type'	=> 'VO-EXP',
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $GEJ_CREATED,
										'Created'			=> $GEJ_CREATED,
										'Wh_id'				=> $PRJCODE,
										'proj_Code'			=> $PRJCODE,
										'GEJ_STAT'			=> $GEJ_STAT,
										'acc_number'		=> $acc_number);
				$this->m_cproj_payment->add($projGEJH);
			
				$Base_KreditTOT		= 0;
				$Base_KreditTOT_Tax	= 0;
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $JournalH_Code2;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$proj_Code		= $d['proj_Code'];
					$JOBCODEID		= $d['JOBCODEID'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$ITM_GROUP		= $d['ITM_CATEG'];
					$ITM_VOLM		= $d['ITM_VOLM'];
					$ITM_UNIT		= $d['ITM_UNIT'];
					
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					if($isTax == 0)
					{
						$Journal_Type		= '';
						$isTax				= 0;
						
						if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet	= 0;
							$Base_Debet		= 0;
							$COA_Debet		= 0;
							$JournalD_Kredit= $d['JournalD_Amount'];
							$Base_Kredit	= $d['JournalD_Amount'];
							$COA_Kredit		= $d['JournalD_Amount'];
							
							// START : Update to COA - Debit
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
															Base_Kredit2 = Base_Kredit2+$Base_Kredit, BaseK_$accYr = BaseK_$accYr+$Base_Kredit
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
							// START : UPDATE L/R
								if($ITM_GROUP == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'SC')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'GE')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
								
							$Base_KreditTOT		= $Base_KreditTOT + $Base_Kredit;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
					}
					else
					{
						$Journal_Type		= 'TAX';
						$isTax				= 1;
						if($JournalD_Pos = 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet_tax		= 0;
							$Base_Debet_tax			= 0;
							$COA_Debet_tax			= 0;
							$JournalD_Kredit_tax	= $d['JournalD_Amount'];
							$Base_Kredit_tax		= $d['JournalD_Amount'];
							$COA_Kredit_tax			= $d['JournalD_Amount'];
							
							// START : Update to COA - Debit
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOAD	= "UPDATE tbl_chartaccount 
														SET Base_Kredit_tax = Base_Kredit_tax+$Base_Kredit_tax, 
															Base_Kredit_tax2 = Base_Kredit_tax2+$Base_Kredit_tax
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
														
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
							
							// START : UPDATE L/R
								if($ITM_GROUP == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'SC')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit_tax
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'GE')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
								
							$Base_KreditTOT_Tax	= $Base_KreditTOT_Tax + $Base_Kredit_tax;
						}
						
						$JournalD_Debet		= 0;
						$Base_Debet			= 0;
						$COA_Debet			= 0;
						$JournalD_Kredit	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
					}
					
					$curr_rate				= 1;
					$isDirect				= 1;
					$Ref_Number				= $d['Ref_Number'];
					$Other_Desc				= $d['Other_Desc'];
					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					// Insert into tbl_journal_detail (D) for All Expenses
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax, JOBCODEID) VALUE
									('$JournalH_Code', '$Acc_Id', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
									$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit,
									$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'D', '$Journal_Type', $isTax, '$JOBCODEID')";
					$this->db->query($insSQL);
					
					// UPDATE INTO USE JOBLISTDETAIL
					/*$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_VOLM, ITM_USED_AM = ITM_USED_AM-$JournalD_Kredit
								WHERE JOBCODEID = '$JOBCODEID'";*/
					$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED - $ITM_VOLM, ITM_USED_AM = ITM_USED_AM-$JournalD_Kredit
								WHERE JOBCODEID = '$JOBCODEID'";
					//$this->db->query($upJL1);
				}
				$BaseKreditTOT	= $Base_KreditTOT + $Base_KreditTOT_Tax;
				
				// Insert into tbl_journal_detail (D) for Cash/Bank
				$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet,
									JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax) 
									VALUE
									('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_KreditTOT, $Base_KreditTOT_Tax,
									$Base_KreditTOT, $Base_KreditTOT_Tax, $Base_KreditTOT, $Base_KreditTOT_Tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'D', '$Journal_Type', $isTax)";
				$this->db->query($insSQLK);
				
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseKreditTOT 
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
				
				$ACC_NUM		= $acc_number;				
				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
				
				if($jmD > 0)
				{
					$SYNC_PRJ	= '';
					for($i=0; $i < $jmD; $i++)
					{
						$SYNC_PRJ	= $dataPecah[$i];
						$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$BaseKreditTOT, 
											Base_Debet2 = Base_Debet2+$BaseKreditTOT, BaseD_$accYr = BaseD_$accYr+$BaseKreditTOT
										WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$acc_number'";
						$this->db->query($sqlUpdCOA);
					}
				}
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Codex,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($GEJ_STAT == 4)
			{
				$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
									WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJHA);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
					
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($JournalH_Code);
				// END : DELETE HISTORY
			}
			else
			{
				$projGEJH 	= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> 'O-EXP',	// Cash Project
									'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'O-EXP',
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'acc_number'		=> $acc_number);									
				$this->m_cproj_payment->updateCPRJ($JournalH_Code, $projGEJH);
				
				$this->m_cproj_payment->deleteCPRJDetail($JournalH_Code);
				
				$Base_DebetTOT		= 0;
				$Base_DebetTOT_Tax	= 0;
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $JournalH_Code;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$proj_Code		= $d['proj_Code'];
					$JOBCODEID		= $d['JOBCODEID'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$ITM_GROUP		= $d['ITM_CATEG'];
					$ITM_VOLM		= $d['ITM_VOLM'];
					$ITM_UNIT		= $d['ITM_UNIT'];
					
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					$ITM_Amount	= $d['JournalD_Amount'];
					$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;
					if($isTax == 0)
					{
						$Journal_Type		= '';
						$isTax				= 0;
						
						if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet	= $d['JournalD_Amount'];
							$Base_Debet		= $d['JournalD_Amount'];
							$COA_Debet		= $d['JournalD_Amount'];
							$JournalD_Kredit= 0;
							$Base_Kredit	= 0;
							$COA_Kredit		= 0;
							if($GEJ_STAT == 3 && $AH_ISLAST == 1)
							{
								// START : Update to COA - Debit
									/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
															Base_Debet2 = Base_Debet2+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOA);*/
									
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
																Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
								
								// START : UPDATE L/R
									if($ITM_GROUP == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'SC')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'GE')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
								
								// START : Update ITM Used
									// 1. UPDATE JOBLIST
										$JOBCODEID	= $d['JOBCODEID'];
										$ITM_CODE	= $d['ITM_CODE'];
										$ITM_VOLM	= $d['ITM_VOLM'];
										
										$ITM_USED	= 0;
										$ITM_USEDAM	= 0;
										$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
															WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
																AND ITM_CODE = '$ITM_CODE'";
										$resUSED1	= $this->db->query($sqlUSED1)->result();
										foreach($resUSED1 as $rowUSED1):
											$ITM_USED	= $rowUSED1->ITM_USED;
											$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
										endforeach;
										
										$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
															ITM_LASTP	= $ITM_PRICE,
															ITM_USED 	= $ITM_USED+$ITM_VOLM, 
															ITM_USED_AM = $ITM_USEDAM+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
															AND ITM_CODE = '$ITM_CODE'";
										$this->db->query($sqlUpdJOBL);
										
									// 2. UPDATE ITEM LIST
										$ITM_OUT	= 0;
										$UM_VOLM	= 0;
										$UM_AMOUNT	= 0;
										$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
															WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
																AND ITM_CODE = '$ITM_CODE'";
										$resUSED1	= $this->db->query($sqlUSED1)->result();
										foreach($resUSED1 as $rowUSED1):
											$ITM_USED	= $rowUSED1->ITM_USED;
											$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
										endforeach;
										$sqlUpdITML	= "UPDATE tbl_item SET
															ITM_LASTP	= $ITM_PRICE,
															ITM_OUT 	= $ITM_OUT+$ITM_VOLM,
															UM_VOLM 	= $UM_VOLM+$ITM_VOLM,
															UM_AMOUNT 	= $UM_AMOUNT+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
										$this->db->query($sqlUpdITML);			
								// END : Update ITM Used
							}
							$Base_DebetTOT		= $Base_DebetTOT + $Base_Debet;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
					}
					else
					{
						$Journal_Type		= 'TAX';
						$isTax				= 1;
						if($JournalD_Pos = 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet_tax		= $d['JournalD_Amount'];
							$Base_Debet_tax			= $d['JournalD_Amount'];
							$COA_Debet_tax			= $d['JournalD_Amount'];
							$JournalD_Kredit_tax	= 0;
							$Base_Kredit_tax		= 0;
							$COA_Kredit_tax			= 0;
							if($GEJ_STAT == 3 && $AH_ISLAST == 1)
							{
								// START : Update to COA - Debit
									/*$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET 
															Base_Debet_tax = Base_Debet_tax+$Base_Debet_tax, 
															Base_Debet_tax2 = Base_Debet_tax2+$Base_Debet_tax
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOAD);*/
									
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET 
															Base_Debet_tax = Base_Debet_tax+$Base_Debet_tax, 
															Base_Debet_tax2 = Base_Debet_tax2+$Base_Debet_tax
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
								
								// START : UPDATE L/R
									if($ITM_GROUP == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'SC')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet_tax
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'GE')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
							}
							$Base_DebetTOT_Tax	= $Base_DebetTOT_Tax + $Base_Debet_tax;
						}
						
						$JournalD_Debet		= 0;
						$Base_Debet			= 0;
						$COA_Debet			= 0;
						$JournalD_Kredit	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
					}
					
					$curr_rate				= 1;
					$isDirect				= 1;
					$Ref_Number				= $d['Ref_Number'];
					$Other_Desc				= $d['Other_Desc'];
					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					// Insert into tbl_journal_detail (D) for All Expenses
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE, ITM_UNIT, Ref_Number, Other_Desc,
									Journal_DK, Journal_Type, isTax, JOBCODEID)
									VALUE
									('$JournalH_Code', '$Acc_Id', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
									$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit,
									$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_PRICE', '$ITM_UNIT', '$Ref_Number', '$Other_Desc', 'D', 
									'$Journal_Type', $isTax, '$JOBCODEID')";
					$this->db->query($insSQL);
					
					// UPDATE INTO USE JOBLISTDETAIL
					/*$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = 1, ITM_USED_AM = ITM_USED_AM+$JournalD_Debet
								WHERE JOBCODEID = '$JOBCODEID'";*/
					$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM+$JournalD_Debet
								WHERE JOBCODEID = '$JOBCODEID'";
					//$this->db->query($upJL1);
				}
				
				$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
				
				// Insert into tbl_journal_detail (K) for Cash
				$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
									JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax) VALUE
									('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
									$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$ITM_CODE', 
									'$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'K', '$Journal_Type', $isTax)";
				$this->db->query($insSQLK);
				
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					//$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if($GEJ_STAT == 3 && $AH_ISLAST == 1)
				{
					/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$BaseDebetTOT, 
											Base_Kredit2 = Base_Kredit2+$BaseDebetTOT
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number'";
					$this->db->query($sqlUpdCOA);*/
					
					$ACC_NUM		= $acc_number;				
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$BaseDebetTOT, 
												Base_Kredit2 = Base_Kredit2+$BaseDebetTOT, BaseK_$accYr = BaseK_$accYr+$BaseDebetTOT
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$acc_number'";
							$this->db->query($sqlUpdCOA);
						}
					}
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				elseif($GEJ_STAT == 2 || $GEJ_STAT == 5)
				{
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			$upJHA			= "UPDATE tbl_journaldetail SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			//$this->db->query($upJHA);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN147';
				$TTR_CATEG		= 'U-P';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_finance/c_cpa70d18/cpothb80da8_pr7l/?id='.$this->url_encryption_helper->encode_url($proj_Code));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function puSA0b28t18yXP() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$THEROW		= $_GET['theRow'];
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['SOURCE'] 		= "I";
			$data['THEROW'] 		= $THEROW;
			$acc_number				= '';
			
			//$data['countAllCOA']	= $this->m_cproj_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			//$data['vwAllCOA'] 		= $this->m_cproj_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			//$data['countAllItem']	= $this->m_cproj_payment->count_all_AccountyXP($PRJCODE);
			//$data['vwAllItem'] 		= $this->m_cproj_payment->view_all_AccountyXP($PRJCODE)->result();
					
			$this->load->view('v_finance/v_cproj_payment/v_cproj_payment_selacc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function puSA0b28t18yXI() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_finance/m_cproj_payment/m_cproj_payment', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$THEROW		= $_GET['theRow'];
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['SOURCE'] 		= "I";
			$data['THEROW'] 		= $THEROW;
			$acc_number				= '';
			
			$data['countAllCOA']	= $this->m_cproj_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 		= $this->m_cproj_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$data['countAllItem']	= $this->m_cproj_payment->count_all_AccountyXI($PRJCODE);
			$data['vwAllItem'] 		= $this->m_cproj_payment->view_all_AccountyXI($PRJCODE)->result();
					
			$this->load->view('v_finance/v_cproj_payment/v_cproj_payment_selacc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataITM() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$CData		= $_GET['id'];
		$CData1		= explode('~', $CData);
		$PRJCODE 	= $CData1[0];
		$THEROW 	= $CData1[1];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("JOBCODEID", 
									"ITM_UNIT", 
									"JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cproj_payment->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_cproj_payment->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$theRow 		= 0;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$theRow 		= $theRow+1;
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$JOBVOLM		= $dataI['ITM_VOLM'];
				$ITM_VOLMBG		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
				$REQ_VOLM		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				$PO_VOLM		= $dataI['PO_VOLM'];
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];
				$IR_VOLM		= $dataI['IR_VOLM'];
				$IR_AMOUNT		= $dataI['IR_AMOUNT'];
				$ITM_USED		= $dataI['ITM_USED'];
				$ITM_USED_AM	= $dataI['ITM_USED_AM'];
				$ITM_STOCK		= $dataI['ITM_STOCK'];
				$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
				$ADDM_VOLM 		= $dataI['ADDM_VOLM'];
				$ADDM_JOBCOST	= $dataI['ADDM_JOBCOST'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];
				$ACC_ID			= $dataI['ACC_ID'];
				$ITM_NAME		= $dataI['ITM_NAME'];
				$ITM_TYPE		= $dataI['ITM_TYPE'];

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_VOLMBG 	= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNTBG	= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);

				$JOBUNIT 		= strtoupper($ITM_UNIT);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';

				// GET PR DETAIL
					$TOT_PRVOL		= $REQ_VOLM;
					$TOT_PRAMN		= $REQ_AMOUNT;
					/*$sqlITMVC		= "SELECT SUM(A.PR_VOLM) AS TOT_REQ, 
											SUM(A.PR_TOTAL) AS TOT_REQAM 
										FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE B.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT = 2";
					$resITMVC		= $this->db->query($sqlITMVC)->result();
					foreach($resITMVC as $rowITMVC) :
						$TOT_PRVOL	= $rowITMVC->TOT_REQ;
						$TOT_PAMN	= $rowITMVC->TOT_REQAM;
					endforeach;
					if($TOT_PRVOL == '')
						$TOT_PRVOL	= 0;
					if($TOT_PRAMN == '')
						$TOT_PRAMN	= 0;*/

				// LS PROCEDURE 1
					if($JOBUNIT == 'LS')
					{
						//$TOT_REQ 	= $REQ_AMOUNT + $TOT_PAMN;
						$TOT_REQ 	= $REQ_AMOUNT;
						$MAX_REQ	= $TOT_AMOUNTBG - $TOT_REQ;		// 14
						$PO_VOLM	= $PO_AMOUNT;
						$TOT_BUDG	= $TOT_AMOUNTBG;
					}
					else
					{
						//$TOT_REQ 	= $REQ_VOLM;
						$TOT_REQ 	= $REQ_VOLM + $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TOT_REQ;			// 14
						$PO_VOLM	= $PO_VOLM;
						$TOT_BUDG	= $TOT_VOLMBG;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLMBG - $TOT_REQ;
					$REMREQ_AMN		= $TOT_AMOUNTBG - $TOT_BUDG;
					
					$disabledB		= 0;
					if($JOBUNIT == 'LS')
					{
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
						
						$ITM_PRICE		= 1;
						$TOT_VOLMBG		= $TOT_AMOUNTBG;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
					}
				
					if($ITM_TYPE == 'SUBS')
					{
						$disabledB	= 0;															
					}

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
						$statRem 	= "";
						$JOBUNITV 	= "";
						$JOBVOLMV 	= "";
						$TOT_REQV 	= "";
						$PO_VOLMV	= "";
					}
					else
					{
						$strLEN 	= strlen($JOBDESC);
						$JOBDESCA	= substr("$JOBDESC", 0, 50);
						$JOBDESC1 	= $JOBDESCA;
						if($strLEN > 60)
							$JOBDESC1 	= $JOBDESCA."...";
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";

						$JOBUNITV 	= $JOBUNIT;
						$JOBVOLMV	= number_format($JOBVOLM, 2);
						$TOT_REQV	= number_format($TOT_REQ, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($REMREQ_QTY, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_QTY, 2)."</span>";
					}

					$ACCDESC 	= "";
					if($ACC_ID == '')
					{
						$disabledB 	= 1;
						$ACCDESC 	= "acc_not_set";
					}


				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox		= "<input type='checkbox' name='chk1' value='".$ACC_ID."|".$JOBDESC."|".$ITM_CODE."|".$ITM_GROUP."|".$JOBCODEID."|".$REMREQ_QTY."|".$REMREQ_AMN."|".$ITM_UNIT."|".$ITM_LASTP."|".$THEROW."' onClick='pickThis1(this);'/>";
					}
					else
					{
						$chkBox		= $ACCDESC;
					}

					$JOBDESCH		= "";
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESCH	= $rowJOBDESC->JOBDESC;
					endforeach;
					$strLENH 	= strlen($JOBDESCH);
					$JOBDESCHA	= substr("$JOBDESCH", 0, 50);
					$JOBDESCH 	= $JOBDESCHA;
					if($strLENH > 50)
						$JOBDESCH 	= $JOBDESCHA."...";

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";
					$JobView		= "$JOBCODEID - $JOBDESC1";

					$ADDVOL_VW 		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobView."</span></p>
										  	</div>
										  	<div style='font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JOBDESCH."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITMS() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("ITM_CODE", 
									"ITM_UNIT", 
									"ITM_NAME");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_req->get_AllDataITMSC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_req->get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TotBOQ			= 0;
			$TotBUD			= 0;
			$TotADD			= 0;
			$TotADD2		= 0;
			$TotADD3		= 0;
			$TotADD4		= 0;
			$TotADD5		= 0;
			$TotALL			= 0;
			$TotREM			= 0;
			$REMAIN2		= 0;
			$TotUSE			= 0;
			$TotPC			= 0;	// Total Project Complete
			foreach ($query->result_array() as $dataI) 
			{
				$ORD_ID 		= $dataI['ORD_ID'];
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];

				$JOBVOLM		= $dataI['ITM_VOLM'];
				$ITM_VOLMBG		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
				$REQ_VOLM		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				$PO_VOLM		= $dataI['PO_VOLM'];
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];
				$IR_VOLM		= $dataI['IR_VOLM'];
				$IR_AMOUNT		= $dataI['IR_AMOUNT'];
				$ITM_USED		= $dataI['ITM_USED'];
				$ITM_USED_AM	= $dataI['ITM_USED_AM'];
				$ITM_STOCK		= $dataI['ITM_STOCK'];
				$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
				// $ADDM_VOLM 		= $dataI['ADDM_VOLM'];
				// $ADDM_JOBCOST	= $dataI['ADDM_JOBCOST'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$ITMBUDGVOL 	= 0;
					$sqlITMNM		= "SELECT ITM_NAME, ITM_CODE_H, ITM_TYPE, ITM_UNIT, ITM_VOLMBG
										FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' 
											AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_NAME	= $rowITMNM->ITM_NAME;			// 5
						$ITM_CODE_H	= $rowITMNM->ITM_CODE_H;
						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
						$ITM_UNIT	= $rowITMNM->ITM_UNIT;
						$ITMBUDGVOL	= $rowITMNM->ITM_VOLMBG;
					endforeach;

				// 21-03-27 ITEM STATUS SUBPUN TETAP AKAN MENGIKUTI BUDGET INDUKNYA
				// 21-06-27 DIBUKA LAGI
				if($ITM_TYPE == 'SUBS')
				{
					$disabledB	= 0;
					$JOBVOLM 	= $ITMBUDGVOL;
					$ITM_VOLMBG = $ITMBUDGVOL;
				}

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_VOLMBG 	= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNTBG	= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);

				$JOBUNIT 		= strtoupper($ITM_UNIT);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';
				$JOBLEV			= $dataI['IS_LEVEL'];

				// GET PR DETAIL
					$TOT_PRVOL		= 0;
					$TOT_PRAMN		= 0;
					$sqlITMVC		= "SELECT SUM(A.PR_VOLM) AS TOT_REQ, 
											SUM(A.PR_TOTAL) AS TOT_REQAM 
										FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE B.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT = 2";
					$resITMVC		= $this->db->query($sqlITMVC)->result();
					foreach($resITMVC as $rowITMVC) :
						$TOT_PRVOL	= $rowITMVC->TOT_REQ;
						$TOT_PAMN	= $rowITMVC->TOT_REQAM;
					endforeach;
					if($TOT_PRVOL == '')
						$TOT_PRVOL	= 0;
					if($TOT_PRAMN == '')
						$TOT_PRAMN	= 0;

				// LS PROCEDURE 1
					if($JOBUNIT == 'LS')
					{
						$TOT_REQ 	= $REQ_AMOUNT + $TOT_PAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TOT_REQ;		// 14
						$PO_VOLM	= $PO_AMOUNT;
						$TOT_BUDG	= $TOT_AMOUNTBG;
					}
					else
					{
						$TOT_REQ 	= $REQ_VOLM + $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TOT_REQ;			// 14
						$PO_VOLM	= $PO_VOLM;
						$TOT_BUDG	= $TOT_VOLMBG;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLMBG - $TOT_REQ;
					$REMREQ_AMN		= $TOT_AMOUNTBG - $TOT_BUDG;
					
					$disabledB		= 0;
					if($JOBUNIT == 'LS')
					{
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
						
						$ITM_PRICE		= 1;
						$TOT_VOLMBG		= $TOT_AMOUNTBG;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
					}

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
						$statRem 	= "";
						$JOBUNITV 	= "";
						$JOBVOLMV 	= "";
						$TOT_REQV 	= "";
						$PO_VOLMV	= "";
					}
					else
					{
						$strLEN 	= strlen($JOBDESC);
						$JOBDESCA	= substr("$JOBDESC", 0, 50);
						$JOBDESC1 	= $JOBDESCA;
						if($strLEN > 60)
							$JOBDESC1 	= $JOBDESCA."...";
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";

						$JOBUNITV 	= $JOBUNIT;
						$JOBVOLMV	= number_format($JOBVOLM, 2);
						$TOT_REQV	= number_format($TOT_REQ, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($REMREQ_QTY, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_QTY, 2)."</span>";
					}

				// SPACE
					/*if($JOBLEV == 1)
						$spaceLev 	= "";
					elseif($JOBLEV == 2)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 3)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";*/

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox		= "<input type='checkbox' name='chk2' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' onClick='pickThis2(this);'/>";
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' style='display: none' />";
					}

					$JOBDESCH		= "";
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESCH	= $rowJOBDESC->JOBDESC;
					endforeach;
					$strLENH 	= strlen($JOBDESCH);
					$JOBDESCHA	= substr("$JOBDESCH", 0, 50);
					$JOBDESCH 	= $JOBDESCHA;
					if($strLENH > 50)
						$JOBDESCH 	= $JOBDESCHA."...";

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";
					$JobView		= "$JOBCODEID - $JOBDESC1";

					$ADDVOL_VW 		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobView."</span></p>
										  	</div>
										  	<div style='margin-left: 15px; font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JOBDESCH."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}