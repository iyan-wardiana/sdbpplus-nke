<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 03 Juli 2018
 * File Name	= C_40wnp1ymt.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_40wnp1ymt extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
	
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
		
			//$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
 	function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_40wnp1ymt/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN348';
				$data["MenuCode"] 	= 'MN348';
				$data["MenuApp"] 	= 'MN349';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN348';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_40wnp1ymt/g4ll_40wnp1ymt/?id=";
			
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

	function g4ll_40wnp1ymt() // G
	{
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['PRJCODE'] 		= $PRJCODE;
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN347';
				$data["MenuApp"] 	= 'MN348';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['secAddURL'] 		= site_url('c_finance/c_40wnp1ymt/add_40wnp1ymt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 		= site_url('c_finance/c_40wnp1ymt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data["MenuCode"] 		= 'MN348';
			$num_rows 				= $this->m_down_payment->count_all_dp($PRJCODE);
			$data['countdp'] 		= $num_rows;
	 
			$data['viewdp'] 		= $this->m_down_payment->get_all_dp($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN347';
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
			
			$this->load->view('v_finance/v_down_payment/down_payment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

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
			
			$columns_valid 	= array("",
									"DP_CODE", 
									"DP_DATE", 
									"B.SPLDESC",
									"DP_NOTES",
									"DP_AMOUNT",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_down_payment->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_down_payment->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$DP_NUM 		= $dataI['DP_NUM'];
				$DP_CODE		= $dataI['DP_CODE'];
				$DP_DATE		= $dataI['DP_DATE'];
				$DP_DATEV		= date('d M Y', strtotime($DP_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$SPLCODE		= $dataI['SPLCODE'];
				$SPLDESC		= $dataI['SPLDESC'];
				$DP_AMOUNT		= $dataI['DP_AMOUNT'];
				$DP_AMOUNT_USED	= $dataI['DP_AMOUNT_USED'];
				$DP_AMOUN_TOT 	= $dataI['DP_AMOUN_TOT'];
				$DP_NOTES		= $dataI['DP_NOTES'];
				$DP_STAT		= $dataI['DP_STAT'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);

				$REMAIN_DP 		= $DP_AMOUN_TOT - $DP_AMOUNT_USED;
				
				$CollCode		= "$PRJCODE~$DP_NUM";
				$secUpd			= site_url('c_finance/c_40wnp1ymt/u4_40wnp1ymt/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secPrint1		= site_url('c_finance/c_40wnp1ymt/p_R1n7/?id='.$this->url_encryption_helper->encode_url($DP_NUM));
				$CollID			= "DPP~$DP_NUM~$PRJCODE";
				$secDelIcut 	= base_url().'index.php/__l1y/trashDPP/?id=';
				$delID 			= "$secDelIcut~tbl_dp_header~tbl_dp_detail~DP_NUM~$DP_NUM~PRJCODE~$PRJCODE";

				$VOID_REASON	= "";
				$JRN_SRC 		= "PINV";
				$secVoid 		= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$voidID 		= "$secVoid~tbl_dp_header~tbl_dp_detail~DP_NUM~$DP_NUM~PRJCODE~$PRJCODE~$VOID_REASON~$JRN_SRC";
						
				if($DP_STAT == 1 || $DP_STAT == 4) 
				{
					$secPrint	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				else
				{
					$secPrint	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
                                        <i class='glyphicon glyphicon-print'></i>
                                    </a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>$DP_CODE</div>",
										  $DP_DATEV,
										  $SPLDESC,
										  $DP_NOTES,
										  number_format($DP_AMOUN_TOT,2),
										  number_format($DP_AMOUNT_USED,2),
										  number_format($REMAIN_DP,2),
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add_40wnp1ymt() // G
	{
		$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN348';
				$data["MenuCode"] 	= 'MN348';
				$data["MenuApp"] 	= 'MN349';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_finance/c_40wnp1ymt/add_process');
			$data['backURL'] 		= site_url('c_finance/c_40wnp1ymt/g4ll_40wnp1ymt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['countVend']	= $this->m_down_payment->count_all_num_rowsVend();
			$data['vwvendor'] 	= $this->m_down_payment->viewvendor()->result();
			
			$MenuCode 			= 'MN348';
			$data["MenuCode"] 	= 'MN348';
			$data["MenuCode1"] 	= 'MN349';
			$data['vwDocPatt'] 	= $this->m_down_payment->getDataDocPat($MenuCode)->result();
			
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN348';
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
			
			$this->load->view('v_finance/v_down_payment/down_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llW_0() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
			
			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countWO']	= $this->m_down_payment->count_all_WO($PRJCODE);
			$data['vwWO'] 		= $this->m_down_payment->view_all_WO($PRJCODE)->result();
			$data['countPO']	= $this->m_down_payment->count_all_PO($PRJCODE);
			$data['vwPO'] 		= $this->m_down_payment->view_all_PO($PRJCODE)->result();
					
			$this->load->view('v_finance/v_down_payment/down_payment_selWO', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function s3l4llW_0L() // GOOD
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
			
			$columns_valid 	= array("",
									"WO_CODE", 
									"WO_DATE", 
									"WO_NOTE",
									"WO_STARTD",
									"WO_ENDD",
									"WO_VALUE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_down_payment->get_AllDataWOC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_down_payment->get_AllDataWOL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$WO_NUM 		= $dataI['WO_NUM'];
				$WO_CODE 		= $dataI['WO_CODE'];
				$WO_DATE 		= $dataI['WO_DATE'];
				$WO_DATE		= date('d M Y', strtotime($WO_DATE));
				$WO_STARTD 		= $dataI['WO_STARTD'];
				$WO_STARTD		= date('d M Y', strtotime($WO_STARTD));
				$WO_ENDD 		= $dataI['WO_ENDD'];
				$WO_ENDD		= date('d M Y', strtotime($WO_ENDD));
				$PRJCODE 		= $dataI['PRJCODE'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$SPLCODE 		= $dataI['SPLCODE'];
				$SPLDESC 		= $dataI['SPLDESC'];
				$WO_NOTE 		= wordwrap($dataI['WO_NOTE'], 60, "<br>", TRUE);
				$WO_VALUE 		= $dataI['WO_VALUE'];
				$WO_VALPPN 		= $dataI['WO_VALPPN'];
				$WO_VALPPH 		= $dataI['WO_VALPPH'];
				$WO_VALPPHP 	= round($dataI['WO_VALPPHP'],2);
				$WO_CATEG 		= $dataI['WO_CATEG'];
				$REF_TYPE		= 'WO';
				$WO_DPPER 		= $dataI['WO_DPPER'];
				if($WO_DPPER == '' || $WO_DPPER == 0)
					$WO_DPPER 	= 10; 					// default DP 10%
				
				if($WO_CATEG == 'MDR')
				{
					$WO_CATEGD	= 'Mandor';
				}
				elseif($WO_CATEG == 'SUB')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				else
				{
					$WO_CATEGD 	= 'Sewa Alat';
				}
				
				// GET TTK NUMBER
					$TTK_NUM	= '';
					$TTK_CODE	= '';
					$TTK_PPNVAL = 0;
					$TTK_PPHVAL = 0;
					$s_TTK		= "SELECT TTK_NUM, TTK_CODE, TTK_AMOUNT_PPN, TTK_AMOUNT_PPH FROM tbl_ttk_header WHERE TTK_DP_REFNUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
					$r_TTK		= $this->db->query($s_TTK)->result();
					foreach($r_TTK as $rw_TTK) :
						$TTK_NUM	= $rw_TTK->TTK_NUM;
						$TTK_CODE	= $rw_TTK->TTK_CODE;
						$TTK_PPNVAL = $rw_TTK->TTK_AMOUNT_PPN;
						$TTK_PPHVAL = $rw_TTK->TTK_AMOUNT_PPH;
					endforeach;
				
				// GET JOB DETAIL
					$JOBDESC		= '';
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESC	= $rowJOBDESC->JOBDESC;
					endforeach;

				$chkBox			= "<input type='radio' name='chk1' value='".$WO_NUM."|".$WO_CODE."|".$REF_TYPE."|".$WO_VALUE."|".$WO_DPPER."|".$TTK_NUM."|".$TTK_CODE."|".$TTK_PPNVAL."|".$SPLCODE."|".$SPLDESC."|".$TTK_PPHVAL."|".$WO_VALPPHP."' onClick='pickThis1(this);' />";
				
				$output['data'][] 	= array("$chkBox",
										  	"<div style='white-space:nowrap'>$WO_CODE</div>",
										  	$WO_DATE,
										 	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-user margin-r-5'></i> $SPLDESC / $WO_CATEGD</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$WO_NOTE."
										  	</div>",
										  	"<div style='white-space:nowrap'>$WO_STARTD</div>",
										  	"<div style='white-space:nowrap'>$WO_ENDD</div>",
										  	number_format($WO_VALUE,2));
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function s3l4llP_0L() // GOOD
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
			
			$columns_valid 	= array("",
									"PO_CODE", 
									"PO_DATE", 
									"PO_NOTES",
									"PO_DUED",
									"PO_TOTCOST");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_down_payment->get_AllDataPOC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_down_payment->get_AllDataPOL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PO_NUM 		= $dataI['PO_NUM'];
				$PO_CODE 		= $dataI['PO_CODE'];
				$PO_DATE 		= $dataI['PO_DATE'];
				$PO_DATE		= date('d M Y', strtotime($PO_DATE));
				$PO_DUED 		= $dataI['PO_DUED'];
				$PO_DUED		= date('d M Y', strtotime($PO_DUED));
				$PRJCODE 		= $dataI['PRJCODE'];
				$SPLCODE 		= $dataI['SPLCODE'];
				$SPLDESC 		= $dataI['SPLDESC'];
				$PO_NOTES 		= $dataI['PO_NOTES'];
				$PO_TOTCOST 	= $dataI['PO_TOTCOST'];

				$s_PO			= "SELECT SUM(PO_COST) AS PO_TOTCOST FROM tbl_po_detail WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
				$r_PO			= $this->db->query($s_PO)->result();
				foreach($r_PO as $rw_PO) :
					$PO_TOTCOST	= $rw_PO->PO_TOTCOST;
				endforeach;

				$PO_TAXAMN 		= $dataI['PO_TAXAMN'];
				// $PO_DPPAMN 	= $PO_TOTCOST - $PO_TAXAMN;
				$PO_DPPAMN 		= $PO_TOTCOST;				// SEHARUSNYA DIAMBIL DARI TOTAL TTK
				$REF_TYPE		= 'PO';
				$PO_DPPER 		= $dataI['PO_DPPER'];
				if($PO_DPPER == '' || $PO_DPPER == 0)
					$PO_DPPER 	= 10; 					// default DP 10%
				
				// GET TTK NUMBER
					$TTK_NUM	= '';
					$TTK_CODE	= '';
					$TTK_INVAMN = 0;
					$TTK_PPNVAL = 0;
					$TTK_PPHVAL = 0;
					$s_TTK		= "SELECT TTK_NUM, TTK_CODE, TTK_GTOTAL, TTK_AMOUNT_PPN, TTK_AMOUNT_PPH FROM tbl_ttk_header WHERE TTK_DP_REFNUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
					$r_TTK		= $this->db->query($s_TTK)->result();
					foreach($r_TTK as $rw_TTK) :
						$TTK_NUM	= $rw_TTK->TTK_NUM;
						$TTK_CODE	= $rw_TTK->TTK_CODE;
						$TTK_INVAMN = $rw_TTK->TTK_GTOTAL;
						$TTK_PPNVAL = $rw_TTK->TTK_AMOUNT_PPN;
						$TTK_PPHVAL = $rw_TTK->TTK_AMOUNT_PPH;
						$PO_TAXAMN 	= $TTK_PPNVAL;
					endforeach;

					// $PO_DPPAMN 		= $TTK_INVAMN;

				$PO_VALPPH 		= 0;
				$PO_VALPPHP 	= 0;

				//$chkBox			= "<input type='radio' name='chk1' value='".$PO_NUM."|".$PO_CODE."|".$REF_TYPE."|".$PO_TOTCOST."|".$PO_DPPER."|".$TTK_NUM."|".$TTK_CODE."|".$TTK_PPNVAL."|".$SPLCODE."|".$SPLDESC."|".$PO_VALPPH."|".$PO_VALPPHP."' onClick='pickThis1(this);' />";
				$chkBox			= "<input type='radio' name='chk1' value='".$PO_NUM."|".$PO_CODE."|".$REF_TYPE."|".$PO_DPPAMN."|".$PO_DPPER."|".$TTK_NUM."|".$TTK_CODE."|".$TTK_PPNVAL."|".$SPLCODE."|".$SPLDESC."|".$TTK_PPHVAL."|".$PO_VALPPHP."' onClick='pickThis1(this);' />";
				
				$output['data'][] 	= array("$chkBox",
										  	"<div style='white-space:nowrap'>$PO_CODE</div>",
										  	$PO_DATE,
										 	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-user margin-r-5'></i> $SPLDESC </strong>
											</div>",
										  	$PO_NOTES,
										  	number_format($PO_TOTCOST,2));
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add_process() // G
	{
		$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
				
			$DP_DATE	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('DP_DATE'))));
			$year		= (int)date('Y',strtotime(str_replace('/', '-', $this->input->post('DP_DATE'))));
			$month 		= (int)date('m',strtotime(str_replace('/', '-', $this->input->post('DP_DATE'))));
			$date 		= (int)date('d',strtotime(str_replace('/', '-', $this->input->post('DP_DATE'))));
			
			$Patt_Year	= date('Y',strtotime($this->input->post('DP_DATE')));
			
			$DP_NUM		= $this->input->post('DP_NUM');
			$DP_CODE	= $this->input->post('DP_CODE');
			$PRJCODE	= $this->input->post('PRJCODE');
			$DP_STAT	= $this->input->post('DP_STAT');
			$DP_REFNUM	= $this->input->post('DP_REFNUM');
			$DP_AMOUNT	= $this->input->post('DP_AMOUNT');

			$TTK_NUM	= $this->input->post('TTK_NUM');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN348';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code 	= $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				//$DP_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PATTCODE 		= $this->input->post('PATTCODE');
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN348',
										'DOCTYPE' 		=> 'DPSPL',
										'DOCNUM' 		=> $DP_NUM,
										'DOCCODE'		=> $DP_CODE,
										'DOCDATE'		=> $DP_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $DP_CODE 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			$TTK_NUM		= $this->input->post('TTK_NUM');
			$TOT_TTKTAX 	= 0;
			$TOT_TTKTAXP 	= 0;
			$s_00 			= "SELECT IFNULL(SUM(TTKT_TAXAMOUNT),0) AS TOT_TTKTAX FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM' AND PRJCODE = '$PRJCODE'";
			$r_00 			= $this->db->query($s_00)->result();
			foreach($r_00 as $rw_00):
				$TOT_TTKTAX 	= $rw_00->TOT_TTKTAX;
				$TOT_TTKTAXP 	= $TOT_TTKTAX / $DP_AMOUNT * 100;
			endforeach;
			
			$dpheader = array('DP_NUM' 			=> $DP_NUM,
							'DP_CODE'			=> $this->input->post('DP_CODE'),
							'DP_DATE'			=> $DP_DATE,
							'PRJCODE'			=> $this->input->post('PRJCODE'),
							'SPLCODE'			=> $this->input->post('SPLCODE'),
							'DP_REFNUM'			=> $this->input->post('DP_REFNUM'),
							'DP_REFCODE'		=> $this->input->post('DP_REFCODE'),
							'DP_REFAMOUNT'		=> $this->input->post('DP_REFAMOUNT'),
							'DP_PERC'			=> $this->input->post('DP_PERC'),
							'DP_AMOUNT'			=> $this->input->post('DP_AMOUNT'),
							'DP_AMOUNT_USED'	=> $this->input->post('DP_AMOUNT_USED'),
							'TTK_NUM'			=> $this->input->post('TTK_NUM'),
							'TTK_CODE'			=> $this->input->post('TTK_CODE'),
							'DP_NOTES'			=> htmlspecialchars($this->input->post('DP_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
							'DP_AMOUNT_PPN'		=> $TOT_TTKTAX,
							'DP_AMOUNT_PPNP'	=> $TOT_TTKTAXP,
							'DP_AMOUNT_PPH'		=> $this->input->post('DP_AMOUNT_PPH'),
							'DP_AMOUNT_PPHP'	=> $this->input->post('DP_AMOUNT_PPHP'),
							'DP_AMOUN_TOT'		=> $this->input->post('DP_AMOUN_TOT'),
							'DP_STAT'			=> $this->input->post('DP_STAT'),
							'DP_CREATED'		=> date('Y-m-d H:i:s'),
							'DP_CREATER'		=> $DefEmp_ID,
							'Patt_Year'			=> $year,
							'Patt_Month'		=> $month,
							'Patt_Date'			=> $date,
							'Patt_Number'		=> $this->input->post('Patt_Number'));
			$this->m_down_payment->add($dpheader);

			// UPDATE TTK STATUS
				$s_01		= "UPDATE tbl_ttk_header SET INV_STAT = 'FI', INV_CREATED = 1, INV_CODE = '$DP_CODE', INV_DATE = '$DP_DATE'
								WHERE TTK_NUM = '$TTK_NUM'";
				$this->db->query($s_01);

			if($DP_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$DP_VAL 	= $DP_AMOUNT;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $DP_DATE,
										'FVAL'		=> $DP_VAL,
										'FNAME'		=> "DP_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}

			if($DP_STAT != 1)
			{
				// UPDATE WO - PO HEADER DP_STAT
					$s_01 	= "UPDATE tbl_wo_header SET WO_DPSTAT = 1 WHERE WO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);

					$s_01 	= "UPDATE tbl_po_header SET PO_DPSTAT = 1 WHERE PO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);
			}

			// START : CEK PPN SESUAI NOMOR FAKTUR
				$TTK_NUM	= $this->input->post('TTK_NUM');
				$s_00 		= "SELECT IFNULL(SUM(TTKT_TAXAMOUNT),0) AS TOT_TTKTAX FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM' AND PRJCODE = '$PRJCODE'";
				$r_00 		= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00):
					$TOT_TTKTAX = $rw_00->TOT_TTKTAX;
			
					$dpPPN 	= array('DP_AMOUNT_PPN'		=> $TOT_TTKTAX);
					$this->m_down_payment->update($DP_NUM, $dpPPN);
					$DP_AMOUNT_PPNP = $TOT_TTKTAX / $DP_AMOUNT * 100;
			
					$dpPPN 	= array('DP_AMOUNT_PPN'		=> $TOT_TTKTAX,
									'DP_AMOUNT_PPNP'	=> $DP_AMOUNT_PPNP);
				endforeach;
			// END : CEK PPN SESUAI NOMOR FAKTUR
				
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "DP_NUM",
										'DOC_CODE' 		=> $DP_NUM,
										'DOC_STAT' 		=> $DP_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_dp_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('DP_NUM');
				$MenuCode 		= 'MN348';
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
			
			$url			= site_url('c_finance/c_40wnp1ymt/g4ll_40wnp1ymt?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u4_40wnp1ymt() // G
	{
		$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$DP_NUM		= $EXTRACTCOL[1];
			
			$getDP 		= $this->m_down_payment->get_dp_by_code($DP_NUM)->row();
			
			$data['default']['DP_NUM'] 			= $getDP->DP_NUM;
			$data['default']['DP_CODE']			= $getDP->DP_CODE;
			$data['default']['DP_DATE']			= $getDP->DP_DATE;
			$data['default']['PRJCODE']			= $getDP->PRJCODE;
			$PRJCODE							= $getDP->PRJCODE;
			$data['default']['SPLCODE']			= $getDP->SPLCODE;
			$data['default']['DP_REFNUM']		= $getDP->DP_REFNUM;
			$data['default']['DP_REFCODE']		= $getDP->DP_REFCODE;
			$data['default']['DP_REFAMOUNT']	= $getDP->DP_REFAMOUNT;
			$data['default']['DP_PERC']			= $getDP->DP_PERC;
			$data['default']['DP_AMOUNT']		= $getDP->DP_AMOUNT;
			$data['default']['DP_AMOUNT_PPN']	= $getDP->DP_AMOUNT_PPN;
			$data['default']['DP_AMOUNT_USED']	= $getDP->DP_AMOUNT_USED;
			$data['default']['TTK_NUM']			= $getDP->TTK_NUM;
			$data['default']['TTK_CODE']		= $getDP->TTK_CODE;
			$data['default']['DP_NOTES']		= $getDP->DP_NOTES;
			$data['default']['DP_NOTES2']		= $getDP->DP_NOTES2;
			$data['default']['DP_STAT']			= $getDP->DP_STAT;
			$data['default']['DP_AMOUNT_PPH']	= $getDP->DP_AMOUNT_PPH;
			$data['default']['DP_AMOUNT_PPHP']	= $getDP->DP_AMOUNT_PPHP;
			$data['default']['DP_AMOUN_TOT']	= $getDP->DP_AMOUN_TOT;
			$data['default']['Patt_Number']		= $getDP->Patt_Number;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN348';
				$data["MenuCode"] 	= 'MN348';
				$data["MenuApp"] 	= 'MN349';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']	= site_url('c_finance/c_40wnp1ymt/update_process');
			$data['backURL'] 		= site_url('c_finance/c_40wnp1ymt/g4ll_40wnp1ymt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['countVend']	= $this->m_down_payment->count_all_num_rowsVend();
			$data['vwvendor'] 	= $this->m_down_payment->viewvendor()->result();
			
			$MenuCode 			= 'MN348';
			$data["MenuCode"] 	= 'MN348';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getDP->DP_NUM;
				$MenuCode 		= 'MN348';
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
			
			$this->load->view('v_finance/v_down_payment/down_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
			
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DTTIME 	= date('Y-m-d H:i:s');
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

			$DP_DATE	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('DP_DATE'))));
			$year		= (int)date('Y',strtotime(str_replace('/', '-', $this->input->post('DP_DATE'))));
			$month 		= (int)date('m',strtotime(str_replace('/', '-', $this->input->post('DP_DATE'))));
			$date 		= (int)date('d',strtotime(str_replace('/', '-', $this->input->post('DP_DATE'))));
			
			$DP_NUM		= $this->input->post('DP_NUM');
			$DP_CODE	= $this->input->post('DP_CODE');
			$PRJCODE	= $this->input->post('PRJCODE');
			$DP_STAT	= $this->input->post('DP_STAT');
			$DP_REFNUM	= $this->input->post('DP_REFNUM');
			$DP_AMOUNT	= $this->input->post('DP_AMOUNT');

			$TTK_NUM	= $this->input->post('TTK_NUM');

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN348',
										'DOCNUM' 		=> $DP_NUM,
										'DOCCODE'		=> $DP_CODE,
										'DOCDATE'		=> $DP_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			$TTK_NUM		= $this->input->post('TTK_NUM');
			$TOT_TTKTAX 	= 0;
			$TOT_TTKTAXP 	= 0;
			$s_00 			= "SELECT IFNULL(SUM(TTKT_TAXAMOUNT),0) AS TOT_TTKTAX FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM' AND PRJCODE = '$PRJCODE'";
			$r_00 			= $this->db->query($s_00)->result();
			foreach($r_00 as $rw_00):
				$TOT_TTKTAX 	= $rw_00->TOT_TTKTAX;
				$TOT_TTKTAXP 	= $TOT_TTKTAX / $DP_AMOUNT * 100;
			endforeach;
			
			$dpheader 	= array('DP_CODE'			=> $DP_CODE,
								'DP_DATE'			=> $DP_DATE,
								'PRJCODE'			=> $this->input->post('PRJCODE'),
								'SPLCODE'			=> $this->input->post('SPLCODE'),
								'DP_REFNUM'			=> $this->input->post('DP_REFNUM'),
								'DP_REFCODE'		=> $this->input->post('DP_REFCODE'),
								'DP_REFAMOUNT'		=> $this->input->post('DP_REFAMOUNT'),
								'DP_PERC'			=> $this->input->post('DP_PERC'),	
								'DP_AMOUNT'			=> $this->input->post('DP_AMOUNT'),
								'DP_AMOUNT_USED'	=> $this->input->post('DP_AMOUNT_USED'),
								'TTK_NUM'			=> $this->input->post('TTK_NUM'),
								'TTK_CODE'			=> $this->input->post('TTK_CODE'),
								'DP_NOTES'			=> htmlspecialchars($this->input->post('DP_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
								'DP_AMOUNT_PPN'		=> $TOT_TTKTAX,
								'DP_AMOUNT_PPNP'	=> $TOT_TTKTAXP,
								'DP_AMOUNT_PPH'		=> $this->input->post('DP_AMOUNT_PPH'),
								'DP_AMOUNT_PPHP'	=> $this->input->post('DP_AMOUNT_PPHP'),
								'DP_AMOUN_TOT'		=> $this->input->post('DP_AMOUN_TOT'),
								'DP_STAT'			=> $this->input->post('DP_STAT'),
								'DP_CREATED'		=> date('Y-m-d H:i:s'),
								'DP_CREATER'		=> $DefEmp_ID,
								'Patt_Year'			=> $year,
								'Patt_Month'		=> $month,
								'Patt_Date'			=> $date);
			$this->m_down_payment->update($DP_NUM, $dpheader);

			// UPDATE TTK STATUS
			$s_01		= "UPDATE tbl_ttk_header SET INV_STAT = 'FI', INV_CREATED = 1, INV_CODE = '$DP_CODE', INV_DATE = '$DP_DATE'
						WHERE TTK_NUM = '$TTK_NUM'";
			$this->db->query($s_01);

			if($DP_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$DP_VAL 	= $DP_AMOUNT;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $DP_DATE,
										'FVAL'		=> $DP_VAL,
										'FNAME'		=> "DP_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			}

			if($DP_STAT != 1 && $DP_STAT != 9)
			{
				// UPDATE WO - PO HEADER DP_STAT
					$s_01 	= "UPDATE tbl_wo_header SET WO_DPSTAT = 1 WHERE WO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);

					$s_01 	= "UPDATE tbl_po_header SET PO_DPSTAT = 1 WHERE PO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);
			}

			if($DP_STAT == 9)
			{
				$delJRH 	= "UPDATE tbl_journalheader SET GEJ_STAT = 9, Close_Notes = 'Void by $DefEmp_ID $DTTIME' WHERE JournalH_Code = '$DP_NUM'";
				$this->db->query($delJRH);
				$delJRD 	= "UPDATE tbl_journaldetail SET GEJ_STAT = 9, oth_reason = 'Void by $DefEmp_ID $DTTIME' WHERE JournalH_Code = '$DP_NUM'";
				$this->db->query($delJRD);
			}

			// START : CEK PPN SESUAI NOMOR FAKTUR
				$TTK_NUM	= $this->input->post('TTK_NUM');
				$s_00 		= "SELECT IFNULL(SUM(TTKT_TAXAMOUNT),0) AS TOT_TTKTAX FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM' AND PRJCODE = '$PRJCODE'";
				$r_00 		= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00):
					$TOT_TTKTAX 	= $rw_00->TOT_TTKTAX;
					$DP_AMOUNT_PPNP = $TOT_TTKTAX / $DP_AMOUNT * 100;
			
					$dpPPN 	= array('DP_AMOUNT_PPN'		=> $TOT_TTKTAX,
									'DP_AMOUNT_PPNP'	=> $DP_AMOUNT_PPNP);
					$this->m_down_payment->update($DP_NUM, $dpPPN);
				endforeach;
			// END : CEK PPN SESUAI NOMOR FAKTUR
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "DP_NUM",
										'DOC_CODE' 		=> $DP_NUM,
										'DOC_STAT' 		=> $DP_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_dp_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $DP_NUM;
				$MenuCode 		= 'MN348';
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
			
			$url			= site_url('c_finance/c_40wnp1ymt/g4ll_40wnp1ymt?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function inbox() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_40wnp1ymt/prj180c21l_1n2/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l_1n2() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN349';
				$data["MenuApp"] 	= 'MN349';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN348';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_40wnp1ymt/in20x_40wnp1ymt/?id=";
			
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
	
	function in20x_40wnp1ymt() // G
	{
		$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
		
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
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN349';
				$data["MenuCode"] 	= 'MN349';
				$data["MenuApp"] 	= 'MN349';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_finance/c_40wnp1ymt/inbox/');
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 				= $this->m_down_payment->count_all_dpinb($DefEmp_ID);
			$data['countdp'] 		= $num_rows;	 
			$data['viewdp'] 		= $this->m_down_payment->get_all_dpinb($DefEmp_ID)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN349';
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
			
			$this->load->view('v_finance/v_down_payment/inb_down_payment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData_1n2() // GOOD
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
			
			$columns_valid 	= array("",
									"DP_CODE", 
									"DP_DATE", 
									"B.SPLDESC",
									"DP_NOTES",
									"DP_AMOUNT",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_down_payment->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_down_payment->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$DP_NUM 		= $dataI['DP_NUM'];
				$DP_CODE		= $dataI['DP_CODE'];
				$DP_DATE		= $dataI['DP_DATE'];
				$DP_DATEV		= date('d M Y', strtotime($DP_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$SPLCODE		= $dataI['SPLCODE'];
				$SPLDESC		= $dataI['SPLDESC'];
				$DP_AMOUNT		= $dataI['DP_AMOUNT'];
				$DP_AMOUNT_USED	= $dataI['DP_AMOUNT_USED'];
				$DP_NOTES		= $dataI['DP_NOTES'];
				$DP_STAT		= $dataI['DP_STAT'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$CollCode		= "$PRJCODE~$DP_NUM";
				$secUpd			= site_url('c_finance/c_40wnp1ymt/u4_40wnp1ymt1n2/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secPrint1		= site_url('c_finance/c_40wnp1ymt/p_R1n7/?id='.$this->url_encryption_helper->encode_url($DP_NUM));
						
				if($DP_STAT == 1) 
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
										  "<div style='white-space:nowrap'>$DP_CODE</div>",
										  $DP_DATEV,
										  $SPLDESC,
										  $DP_NOTES,
										  number_format($DP_AMOUNT,2),
										  number_format($DP_AMOUNT_USED,2),
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function u4_40wnp1ymt1n2() // G
	{
		$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$DP_NUM		= $EXTRACTCOL[1];
			
			$getDP 		= $this->m_down_payment->get_dp_by_code($DP_NUM)->row();
			
			$data['default']['DP_NUM'] 			= $getDP->DP_NUM;
			$data['default']['DP_CODE']			= $getDP->DP_CODE;
			$data['default']['DP_DATE']			= $getDP->DP_DATE;
			$data['default']['PRJCODE']			= $getDP->PRJCODE;
			$PRJCODE							= $getDP->PRJCODE;
			$data['default']['SPLCODE']			= $getDP->SPLCODE;
			$data['default']['DP_REFNUM']		= $getDP->DP_REFNUM;
			$data['default']['DP_REFCODE']		= $getDP->DP_REFCODE;
			$data['default']['DP_REFAMOUNT']	= $getDP->DP_REFAMOUNT;
			$data['default']['DP_PERC']			= $getDP->DP_PERC;
			$data['default']['DP_AMOUNT']		= $getDP->DP_AMOUNT;
			$data['default']['DP_AMOUNT_PPN']	= $getDP->DP_AMOUNT_PPN;
			$data['default']['DP_AMOUNT_USED']	= $getDP->DP_AMOUNT_USED;
			$data['default']['TTK_NUM']			= $getDP->TTK_NUM;
			$data['default']['TTK_CODE']		= $getDP->TTK_CODE;
			$data['default']['DP_NOTES']		= $getDP->DP_NOTES;
			$data['default']['DP_NOTES2']		= $getDP->DP_NOTES2;
			$data['default']['DP_AMOUNT_PPH']	= $getDP->DP_AMOUNT_PPH;
			$data['default']['DP_AMOUNT_PPHP']	= $getDP->DP_AMOUNT_PPHP;
			$data['default']['DP_AMOUN_TOT']	= $getDP->DP_AMOUN_TOT;
			$data['default']['DP_STAT']			= $getDP->DP_STAT;
			$data['default']['Patt_Number']		= $getDP->Patt_Number;							
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN349';
				$data["MenuCode"] 	= 'MN349';
				$data["MenuApp"] 	= 'MN349';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_finance/c_40wnp1ymt/update_process_inb');
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['countVend']		= $this->m_down_payment->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_down_payment->viewvendor()->result();
			
			$MenuCode 			= 'MN349';
			$data["MenuCode"] 	= 'MN349';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getDP->DP_NUM;
				$MenuCode 		= 'MN349';
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
			
			$this->load->view('v_finance/v_down_payment/inb_down_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // G
	{
		$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
			
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$comp_init 	= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
				
			// $DP_DATE	= date('Y-m-d',strtotime($this->input->post('DP_DATE')));
			$DP_DATE	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('DP_DATE'))));
			$year		= date('Y',strtotime($this->input->post('DP_DATE')));
			$month 		= (int)date('m',strtotime($this->input->post('DP_DATE')));
			$date 		= (int)date('d',strtotime($this->input->post('DP_DATE')));
			
			$DP_NUM			= $this->input->post('DP_NUM');
			$DP_CODE		= $this->input->post('DP_CODE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$DP_STAT		= $this->input->post('DP_STAT');
			$DP_REFNUM		= $this->input->post('DP_REFNUM');
			$TTK_NUM		= $this->input->post('TTK_NUM');
			$DP_AMOUNT		= $this->input->post('DP_AMOUNT');
			$DP_AMOUNT_PPN	= $this->input->post('DP_AMOUNT_PPN');
			$DP_AMOUNT_PPH	= $this->input->post('DP_AMOUNT_PPH');
			$DP_AMOUN_TOT	= $this->input->post('DP_AMOUN_TOT');
			$SPLCODE		= $this->input->post('SPLCODE');
			$TTK_CODE		= $this->input->post('TTK_CODE');
			
			$AH_CODE		= $DP_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= htmlspecialchars($this->input->post('DP_NOTES2'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$dpheader 		= array('DP_NOTES2'	=> htmlspecialchars($this->input->post('DP_NOTES2'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
									'DP_STAT'	=> 7);					
			$this->m_down_payment->update($DP_NUM, $dpheader);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "DP_NUM",
										'DOC_CODE' 		=> $DP_NUM,
										'DOC_STAT' 		=> 7,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_dp_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : CEK PPN SESUAI NOMOR FAKTUR
				$TTK_NUM	= $this->input->post('TTK_NUM');
				$s_00 		= "SELECT IFNULL(SUM(TTKT_TAXAMOUNT),0) AS TOT_TTKTAX FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM' AND PRJCODE = '$PRJCODE'";
				$r_00 		= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00):
					$TOT_TTKTAX = $rw_00->TOT_TTKTAX;
			
					$dpPPN 	= array('DP_AMOUNT_PPN'		=> $TOT_TTKTAX);
					$this->m_down_payment->update($DP_NUM, $dpPPN);
				endforeach;
			// END : CEK PPN SESUAI NOMOR FAKTUR
			
			// SAVE APPROVE HISTORY
				if($DP_STAT == 3)
				{
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
					
					// UNTUK KEPERLUAN FINANCIAL TRACK
					// NILAI HUTANG SEBENARNYA = NILAI INVOICE (SBL DIPOTONG) - POTONGAN + PPN;
					// SAMA DENGAN FM_TOTVAL = (INV_VAL + PPN - POT - RET - PPH) + RET + PPH;
					
					$DP_AMOUNT		= $this->input->post('DP_AMOUNT');
					// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
					// HARUS DI KURANGI SAAT PEMBUATAN INVOICE, BERUBAH MENJADI HUTANG (AP)
						$this->load->model('m_updash/m_updash', '', TRUE);
						$paramFT = array('DOC_NUM' 		=> $DP_NUM,
										'DOC_DATE' 		=> $DP_DATE,
										'DOC_EDATE' 	=> $DP_DATE,
										'PRJCODE' 		=> $PRJCODE,
										'FIELD_NAME1' 	=> 'FT_AP',
										'FIELD_NAME2' 	=> 'FM_AP',
										'TOT_AMOUNT'	=> $DP_AMOUNT);
						$this->m_updash->finTrack($paramFT);
					// END : TRACK FINANCIAL TRACK
				}
			// END : SAVE APPROVE HISTORY
			
			if($AH_ISLAST == 1 && $DP_STAT == 3)
			{
				$dpheader 	= array('DP_STAT'	=> $DP_STAT);
				$this->m_down_payment->update($DP_NUM, $dpheader);

				// UPDATE WO - PO HEADER DP_STAT
					$s_01 	= "UPDATE tbl_wo_header SET WO_DPSTAT = 1 WHERE WO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);

					$s_01 	= "UPDATE tbl_po_header SET PO_DPSTAT = 1 WHERE PO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "DP_NUM",
											'DOC_CODE' 		=> $DP_NUM,
											'DOC_STAT' 		=> $DP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_dp_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				//GET SUPPLIER CATEG
					$SPLCAT		= '';
					$SPLDESC 	= '';
					$sqlSPLC	= "SELECT SPLCAT, SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
					$resSPLC	= $this->db->query($sqlSPLC)->result();
					foreach($resSPLC as $rowSPLC) :
						$SPLCAT 	= $rowSPLC->SPLCAT;
						$SPLDESC 	= $rowSPLC->SPLDESC;
					endforeach;
						
				// START : JOURNAL HEADER
					$JournalH_Code	= $DP_NUM;
					$JournalType	= 'DP';
					$JournalH_Date	= $DP_DATE;
					$Company_ID		= $comp_init;
					$DOCSource		= $DP_NUM;
					$LastUpdate		= date('Y-m-d H:i:s');
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= $TTK_NUM;
					$RefType		= 'DP';;
					$PRJCODE		= $PRJCODE;
					$CB_NOTES 		= htmlspecialchars($this->input->post('DP_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Desc' 	=> $CB_NOTES,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $Company_ID,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE,
										'Manual_No'			=> $DP_CODE,
										'SPLCODE'			=> $SPLCODE,
										'SPLDESC'			=> $SPLDESC);
					// $this->m_journal->createJournalH($JournalH_Code, $parameters); // Saat Voucher DP tidak membentuk jurnal, pembentukan jurnal ketika sudah ada pembayaran DP
				// END : JOURNAL HEADER
						
				// START : JOURNAL DETAIL
					// CARI AKUN PPN dan PPH pada PO/SPK yang berelasi ke -> TTK -> DP
						$TAXCODE1 	= "";
						$TAXCODE2 	= "";
						$s_ACCID 	= "SELECT DISTINCT A.TAXCODE1, A.TAXCODE2 FROM tbl_wo_detail A
										WHERE A.WO_CODE IN (SELECT B.DP_REFCODE FROM tbl_dp_header B WHERE B.DP_NUM = '$DP_NUM')
											AND A.PRJCODE = '$PRJCODE'
										UNION
										SELECT DISTINCT A.TAXCODE1, A.TAXCODE2 FROM tbl_po_detail A
										WHERE A.PO_CODE IN (SELECT B.DP_REFCODE FROM tbl_dp_header B WHERE B.DP_NUM = '$DP_NUM')
											AND A.PRJCODE = '$PRJCODE'";
						$r_ACCID 	= $this->db->query($s_ACCID)->result();
						foreach($r_ACCID as $rw_ACCID):
							$TAXCODE1 	= $rw_ACCID->TAXCODE1;
							$TAXCODE2 	= $rw_ACCID->TAXCODE2;
						endforeach;
					
					$parameters 	= array('JournalH_Code' 	=> $DP_NUM,
											'JournalType'		=> "DP",
											'JournalH_Date' 	=> $DP_DATE,
											'Company_ID' 		=> $comp_init,
											'Currency_ID' 		=> "IDR",
											'Source'			=> $DP_NUM,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $PRJCODE,
											'Reference_Number'	=> $DP_NUM,
											'RefType'			=> "DP",
											'PRJCODE'			=> $PRJCODE,
											'JSource'			=> "DP",
											'TRANS_CATEG' 		=> "DP~$SPLCAT",
											'ITM_CODE' 			=> "",
											'ACC_ID' 			=> "",
											'ITM_UNIT' 			=> "",
											'ITM_QTY' 			=> 1,
											'ITM_PRICE' 		=> $DP_AMOUNT,
											'ITM_DISC' 			=> 0,
											'TAXCODE1' 			=> $TAXCODE1,
											'TAXPRICE1' 		=> $DP_AMOUNT_PPN,
											'TAXCODE2' 			=> $TAXCODE2,
											'TAXPRICE2' 		=> $DP_AMOUNT_PPH,
											'PPhTax' 			=> '',
											'PPhAmount' 		=> $DP_AMOUNT_PPH,
											'DiscAmount' 		=> 0,
											'Notes' 			=> "$CB_NOTES - $DP_CODE ($TTK_CODE)");
					// $this->m_journal->createJournalD($JournalH_Code, $parameters); // Saat Voucher DP tidak membentuk jurnal, pembentukan jurnal ketika sudah ada pembayaran DP
				// END : JOURNAL DETAIL

				$sqlTAX	= "SELECT TTKT_DATE, TTKT_TAXNO, TTKT_SPLINVDATE, TTKT_SPLINVNO FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM'";
				$resTAX	= $this->db->query($sqlTAX)->result();
				foreach($resTAX as $rowTAX) :
					$TTKT_DATE 			= $rowTAX->TTKT_DATE;
					$TTKT_TAXNO 		= $rowTAX->TTKT_TAXNO;
					$TTKT_SPLINVDATE 	= $rowTAX->TTKT_SPLINVDATE;
					$TTKT_SPLINVNO 		= $rowTAX->TTKT_SPLINVNO;

					$sqlUpdJH	= "UPDATE tbl_journaldetail SET TAX_NO = '$TTKT_TAXNO', TAX_DATE = '$TTKT_DATE', Kwitansi_Date = '$TTKT_SPLINVDATE', Kwitansi_No = '$TTKT_SPLINVNO',
										SPLCODE = '$SPLCODE', SPLDESC = '$SPLDESC'
									WHERE JournalH_Code = '$DP_NUM'";
					// $this->db->query($sqlUpdJH);
				endforeach;
			}

			if($DP_STAT == 4)
			{
				$dpheader 		= array('DP_NOTES2'	=> htmlspecialchars($this->input->post('DP_NOTES2'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
										'DP_STAT'	=> 4);					
				$this->m_down_payment->update($DP_NUM, $dpheader);

				// UPDATE WO - PO HEADER DP_STAT
					$s_01 	= "UPDATE tbl_wo_header SET WO_DPSTAT = 1 WHERE WO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);

					$s_01 	= "UPDATE tbl_po_header SET PO_DPSTAT = 1 WHERE PO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "DP_NUM",
											'DOC_CODE' 		=> $DP_NUM,
											'DOC_STAT' 		=> 4,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_dp_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

      		if($DP_STAT == 5)
			{
				$dpheader 	= array('DP_STAT'	=> $DP_STAT);
				$this->m_down_payment->update($DP_NUM, $dpheader);

				// UPDATE WO - PO HEADER DP_STAT
					$s_01 	= "UPDATE tbl_wo_header SET WO_DPSTAT = 0 WHERE WO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);

					$s_01 	= "UPDATE tbl_po_header SET PO_DPSTAT = 0 WHERE PO_NUM = '$DP_REFNUM'";
					$this->db->query($s_01);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "DP_NUM",
											'DOC_CODE' 		=> $DP_NUM,
											'DOC_STAT' 		=> $DP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_dp_header");
					$this->m_updash->updateStatus($paramStat);
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $DP_NUM;
				$MenuCode 		= 'MN348';
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
			
			$url			= site_url('c_finance/c_40wnp1ymt/in20x_40wnp1ymt?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function r34l_dp() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_40wnp1ymt/r34l_prjl/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function r34l_prjl() // G
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
				$data["h1_title"] 	= "Uang Muka";
			}
			else
			{
				$data["h1_title"] 	= "Down Payment";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_40wnp1ymt/g4ll_r34l40wnp1ymt/?id=";
			
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

	function g4ll_r34l40wnp1ymt() // G
	{
		$this->load->model('m_finance/m_down_payment/m_down_payment', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		
		{
			$PRJCODE				= $_GET['id'];
			$PRJCODE				= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['PRJCODE'] 		= $PRJCODE;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Uang Muka";
				$data['h2_title'] 	= "Realisasi";
			}
			else
			{
				$data["h1_title"] 	= "Down Payment";
				$data['h2_title'] 	= "Realization";
			}
			$data['secAddURL'] 		= site_url('c_finance/c_40wnp1ymt/add_r34l40wnp1ymt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data["MenuCode"] 		= 'MN350';
			$num_rows 				= $this->m_down_payment->count_all_rdp($PRJCODE);
			$data['countdp'] 		= $num_rows;
	 
			$data['viewdp'] 		= $this->m_down_payment->get_all_rdp($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN347';
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
			
			$this->load->view('v_finance/v_down_payment/down_payment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function p_R1n7()
	{
		//$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$DP_NUM1	= $_GET['id'];
		$DP_NUM		= $this->url_encryption_helper->decode_url($DP_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			$data['DP_NUM'] = $DP_NUM;
			$data['title'] 	= $appName;

			$getDP 		= $this->m_down_payment->get_dp_by_code($DP_NUM)->row();
			
			$data['DP_NUM'] 		= $getDP->DP_NUM;
			$data['DP_CODE']		= $getDP->DP_CODE;
			$data['DP_DATE']		= $getDP->DP_DATE;
			$data['PRJCODE']		= $getDP->PRJCODE;
			$data['SPLCODE']		= $getDP->SPLCODE;
			$data['DP_REFNUM']		= $getDP->DP_REFNUM;
			$data['DP_REFCODE']		= $getDP->DP_REFCODE;
			$data['TTK_CODE'] 		= $getDP->TTK_CODE;
			$data['DP_REFAMOUNT']	= $getDP->DP_REFAMOUNT;
			$data['DP_PERC']		= $getDP->DP_PERC;
			$data['DP_AMOUNT']		= $getDP->DP_AMOUNT;
			$data['DP_AMOUNT_USED']	= $getDP->DP_AMOUNT_USED;
			$data['DP_AMOUNT_PPN']	= $getDP->DP_AMOUNT_PPN;
			$data['TAXCODE_PPN']	= $getDP->TAXCODE_PPN;
			$data['DP_AMOUNT_PPH']	= $getDP->DP_AMOUNT_PPH;
			$data['TAXCODE_PPH']	= $getDP->TAXCODE_PPH;
			$data['DP_NOTES']		= $getDP->DP_NOTES;
			$data['DP_NOTES2']		= $getDP->DP_NOTES2;
			$data['DP_STAT']		= $getDP->DP_STAT;
			$data['Patt_Number']	= $getDP->Patt_Number;
			
			$this->load->view('v_finance/v_down_payment/down_payment_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataTTKTAX() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$collID1	= $_GET['id'];
		$collID		= explode("~", $collID1);
		$TTK_NUM	= $collID[0];
		$PRJCODE	= $collID[1];

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
			
			$columns_valid 	= array("",
									"TTKT_DATE",
									"TTKT_TAXNO",
									"TTKT_AMOUNT",
									"TTKT_TAXAMOUNT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_down_payment->get_AllDataCTTKTAX($PRJCODE, $TTK_NUM, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_down_payment->get_AllDataLTTKTAX($PRJCODE, $TTK_NUM, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PRJCODE			= $dataI['PRJCODE'];
				$SPLCODE			= $dataI['SPLCODE'];
				$TTK_NUM			= $dataI['TTK_NUM'];
				$TTK_CODE			= $dataI['TTK_CODE'];
				$TTK_DATE			= $dataI['TTK_DATE'];
				$TTK_DATEV			= date('d M Y', strtotime($TTK_DATE));
				$TTK_DUEDATE		= $dataI['TTK_DUEDATE'];
				$TTK_DUEDATEV		= date('d M Y', strtotime($TTK_DUEDATE));
				$TTK_ESTDATE		= $dataI['TTK_ESTDATE'];
				$TTK_ESTDATEV		= date('d M Y', strtotime($TTK_ESTDATE));

				$TTKT_TAXNO			= $dataI['TTKT_TAXNO'];
				$TTKT_DATE			= $dataI['TTKT_DATE'];
				$TTKT_DATEV			= strftime('%d %B %Y', strtotime($TTKT_DATE));
				$TTKT_AMOUNT		= $dataI['TTKT_AMOUNT'];
				$TTKT_AMOUNTV		= number_format($TTKT_AMOUNT,2);
				$TTKT_TAXAMOUNT		= $dataI['TTKT_TAXAMOUNT'];
				$TTKT_TAXAMOUNTV	= number_format($TTKT_TAXAMOUNT,2);

				$TTKT_SPLINVNO		= $dataI['TTKT_SPLINVNO'];
				$TTKT_SPLINVDATE 	= $dataI['TTKT_SPLINVDATE'];
				$TTKT_SPLINVDATEV	= strftime('%d %B %Y', strtotime($TTKT_SPLINVDATE));
				$TTKT_SPLINVVAL		= number_format($dataI['TTKT_SPLINVVAL'],2);

				if($TTKT_SPLINVDATE == '') $TTKT_SPLINVDATEV = "-";

				$output['data'][] = array("<div style='white-space:nowrap'>&nbsp;</div>",
										  "<div style='white-space:nowrap'>$TTKT_SPLINVNO</div>",
										  "<div style='white-space:nowrap'>$TTKT_SPLINVDATEV</div>",
										  "<div style='white-space:nowrap'>$TTKT_SPLINVVAL</div>",
										  "<div style='white-space:nowrap'>$TTKT_TAXNO</div>",
										  "<div style='white-space:nowrap'>$TTKT_DATEV</div>",
										  $TTKT_AMOUNTV,
										  $TTKT_TAXAMOUNTV);
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("$collTTK",
									  "A",
									  "A",
									  "A");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}