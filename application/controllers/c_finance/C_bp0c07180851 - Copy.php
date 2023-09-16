<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 12 November 2017
 * File Name	= C_bp0c07180851.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_bp0c07180851 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
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
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
 	function index() // G
	{
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_bp0c07180851/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN144';
				$data["MenuApp"] 	= 'MN145';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN126';		// MN126 = Daftar Proyek 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_bp0c07180851/G37Bp4YMn/?id=";
			
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

	function G37Bp4YMn() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN144';
			$data["MenuApp"] 	= 'MN145';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
			
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
				$data["url_search"] = site_url('c_finance/c_bp0c07180851/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_bank_payment->count_all_BP($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_bank_payment->get_last_BP($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			
			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;
			$data["MenuCode"] 	= 'MN144';
			$data['addURL'] 	= site_url('c_finance/c_bp0c07180851/a44Bp4YMn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['form_action']= site_url('c_finance/c_bp0c07180851/add_process');
			//$data["countPRJ"]	= $this->m_projectlist->count_all_project($EmpID);
			//$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($EmpID)->result();
			
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
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_finance/c_bp0c07180851/G37Bp4YMn/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

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
			
			$columns_valid 	= array("CB_CODE",
									"CB_DATE", 
									"B.SPLDESC", 
									"C.Account_NameId",
									"A.CB_TOTAM",
									"A.CB_NOTES",
									"A.CB_TOTAM");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bank_payment->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code	= $dataI['JournalH_Code'];
				$CB_CODE		= $dataI['CB_CODE'];
				$CB_DATE		= $dataI['CB_DATE'];
				$CB_DATEV		= date('d M Y', strtotime($CB_DATE));
				$CB_TYPE		= $dataI['CB_TYPE'];
				$Account_Name	= $dataI['Account_Name'];
				$CB_PAYFOR		= $dataI['CB_PAYFOR'];
				$SPLDESC		= $dataI['SPLDESC'];
					
				$CB_NOTES		= addslashes($dataI['CB_NOTES']);
				$CB_STAT		= $dataI['CB_STAT'];
				$ISVOID			= $dataI['ISVOID'];
				if($ISVOID == 1)
				{
					$CISVOIDD 		= 'yes';
					$STATVCOL		= 'danger';
				}
				else
				{
					$CISVOIDD 		= 'no';
					$STATVCOL		= 'success';
				}

				$CB_TOTAM		= $dataI['CB_TOTAM'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$CollID			= "$PRJCODE~$JournalH_Code";
				$secUpd			= site_url('c_finance/c_bp0c07180851/update/?id='.$this->url_encryption_helper->encode_url($CollID));
                $secPrint		= site_url('c_finance/c_bp0c07180851/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 		= base_url().'index.php/__l1y/trashBP/?id=';
				$delID 			= "$secDelIcut~tbl_bp_header~tbl_bp_detail~CB_NUM~$JournalH_Code~PRJCODE~$PRJCODE";
				$voidID 		= "$secVoid~tbl_bp_header~tbl_bp_detail~CB_NUM~$JournalH_Code~PRJCODE~$PRJCODE";
                                    
				if($CB_STAT == 1 || $CB_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($CB_STAT == 3)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOC(".$noU.")' title='Void'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				$output['data'][] = array("<div style='white-space:nowrap'>$CB_CODE</div>",
										  $CB_DATEV,
										  "<span style='white-space:nowrap'>$SPLDESC</span>",
										  "<span style='white-space:nowrap'>$Account_Name</span>",
										  number_format($CB_TOTAM, 2),
										  $CB_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}
								
			/*$output['data'][] 	= array("A",
										"A",
										"A",
										"A",
										"A",
										"A",
										"A",
										"A");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function a44Bp4YMn() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN144';
			$data["MenuApp"] 	= 'MN145';
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
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_finance/c_bp0c07180851/add_process');
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/G37Bp4YMn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			$proj_Currency		= 'IDR';
			$data['PRJCODE'] 	= $PRJCODE; 
			$data['countAcc'] 	= $this->m_bank_payment->count_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE);
			$data['vwAcc'] 		= $this->m_bank_payment->view_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE)->result();
			//$data['countSPL'] = $this->m_bank_payment->count_all_SPL($PRJCODE); // ProjCode nya dihilangkan
			//$data['vwSPL'] 	= $this->m_bank_payment->view_all_SPL()->result();
			
			//$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			//$data['vwPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN144';
			$data["MenuCode"] 	= 'MN144';
			$data["MenuCode1"] 	= 'MN145';
			$data['PRJCODE_HO'] = $this->data['PRJCODE_HO'];
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
	
	function pall180c2cinv() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$SPLCODE1	= $_GET['id'];
			$SPLCODE1	= $this->url_encryption_helper->decode_url($SPLCODE1);
			$data 		= explode("~" , $SPLCODE1);
			$SPLCODE	= $data[0];
			$PAGEFORM	= $data[1];
			$PRJCODE	= $data[2];
			
			$data['title'] 		= $appName;
			$data['SPLCODE'] 	= $SPLCODE;
			
			//$data['countINV'] 	= $this->m_bank_payment->count_all_INV($SPLCODE);
			//$data['vwINV'] 		= $this->m_bank_payment->view_all_INV($SPLCODE)->result();
			
			if($PAGEFORM == "PINV")
			{
				$data['countINV'] 	= $this->m_bank_payment->count_all_INV($SPLCODE, $PRJCODE);
				$data['vwINV'] 		= $this->m_bank_payment->view_all_INV($SPLCODE, $PRJCODE)->result();
				$this->load->view('v_finance/v_bank_payment/v_select_inv', $data);
			}
			elseif($PAGEFORM == "DP")
			{
				$data['countINV'] 	= $this->m_bank_payment->count_all_DP($SPLCODE, $PRJCODE);
				$data['vwINV'] 		= $this->m_bank_payment->view_all_DP($SPLCODE, $PRJCODE)->result();
				$this->load->view('v_finance/v_bank_payment/v_select_dp', $data);
			}
			else
			{
				$data['countINV'] 	= $this->m_bank_payment->count_all_INV($SPLCODE, $PRJCODE);
				$data['vwINV'] 		= $this->m_bank_payment->view_all_INV($SPLCODE, $PRJCODE)->result();
				$this->load->view('v_finance/v_bank_payment/v_select_inv', $data);
			}
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
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$CREATED 		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

			$CB_NUM	 		= $this->input->post('CB_NUM');
			$CB_CODE	 	= $this->input->post('CB_CODE');
			$PRJCODE	 	= $this->input->post('PRJCODE');
			$CB_DATE	 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('CB_DATE'))));
			$CB_TYPE		= 'BP';
			$CB_RECTYPE	 	= $this->input->post('CB_RECTYPE');
			$CB_DOCTYPE		= $this->input->post('CB_SOURCE');
			$CB_SOURCE		= $this->input->post('CB_SOURCE');
			$CB_SOURCENO	= $this->input->post('CB_SOURCENO');
			$CB_CURRID	 	= $this->input->post('CB_CURRID');
			$CB_CURRCONV 	= $this->input->post('CB_CURRCONV');
			$CB_ACCID	 	= $this->input->post('CB_ACCID');
			$CB_PAYFOR	 	= $this->input->post('CB_PAYFOR');
			$CB_PAYEE	 	= $this->input->post('CB_PAYFOR');

			/*$CB_CHEQNO	 	= $this->input->post('CB_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$CB_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$CB_CHEQDAT		= $BGDate;
			$CB_CHEQDAT		= "0000-00-00";*/

			$CB_STAT		= $this->input->post('CB_STAT');
			$CB_APPSTAT		= 0;
			$CB_TOTAM	 	= $this->input->post('CB_TOTAM');
			$CB_TOTAM_PPN 	= $this->input->post('CB_TOTAM_PPN');
			$CB_TOTAM_DISC 	= $this->input->post('CB_TOTAM_DISC');
			//$CB_DPAMOUNT 	= $this->input->post('CB_DPAMOUNT');
			$CB_DPAMOUNT 	= 0;
			$CB_NOTES	 	= addslashes($this->input->post('CB_NOTES'));
			$CB_CREATER		= $DefEmp_ID;
			$CB_CREATED		= $CREATED;
			$Company_ID		= $comp_init;
			$Patt_Number	= $this->input->post('Patt_Number');
			$Patt_Year		= date('Y',strtotime($CB_DATE));
			$Patt_Month		= date('m',strtotime($CB_DATE));
			$Patt_Date		= date('d',strtotime($CB_DATE));
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN144';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				//$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$CB_TOTAM		= 0;
			$CB_TOTAM_DISC	= 0;
			$CB_TOTAM_PPN 	= 0;
			foreach($_POST['data'] as $d)
			{
				$CB_TOTAM		= $CB_TOTAM + $d['CBD_AMOUNT'];
				$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $d['CBD_AMOUNT_DISC'];
				$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $d['INV_AMOUNT_PPN'];
			}
			
			$CB_NUM			= $JournalH_Code;
			$inBankPay 		= array('JournalH_Code'	=> $CB_NUM,
									'CB_NUM' 		=> $CB_NUM,
									'CB_CODE' 		=> $CB_CODE,
									'PRJCODE' 		=> $PRJCODE,
									'CB_DATE' 		=> $CB_DATE,
									'CB_TYPE' 		=> $CB_TYPE,
									'CB_RECTYPE'	=> $CB_RECTYPE,
									'CB_CURRID' 	=> $CB_CURRID,
									'CB_SOURCE' 	=> $CB_SOURCE,
									'CB_SOURCENO' 	=> $CB_SOURCENO,
									'CB_CURRCONV' 	=> 1,
									'CB_ACCID' 		=> $CB_ACCID,
									'CB_PAYFOR' 	=> $CB_PAYFOR,
									'CB_PAYEE' 		=> $CB_PAYEE,
									//'CB_CHEQNO' 	=> $CB_CHEQNO,
									//'CB_CHEQDAT' 	=> $CB_CHEQDAT,
									'CB_DOCTYPE' 	=> $CB_DOCTYPE,
									'CB_STAT' 		=> $CB_STAT,
									'CB_TOTAM' 		=> $CB_TOTAM,
									'CB_TOTAM_PPN' 	=> $CB_TOTAM_PPN,
									'CB_TOTAM_DISC'	=> $CB_TOTAM_DISC,
									'CB_DPAMOUNT'	=> $CB_DPAMOUNT,
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
				
				$CBD_DOCNO 	= $d['CBD_DOCNO'];
				
				// UPDATE STATUS DP
					if($CB_SOURCE == 'DP')
					{
						$updDP 	= array('DP_PAID' 	=> 1);									
						$this->m_bank_payment->updateDP($CBD_DOCNO, $updDP);
					}
				
				$d['JournalH_Code']	= $CB_NUM;
				$d['CB_NUM']		= $CB_NUM;
				$d['CB_CODE']		= $CB_CODE;
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "CB_NUM",
										'DOC_CODE' 		=> $CB_NUM,
										'DOC_STAT' 		=> $CB_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_bp_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
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
			
			$url	= site_url('c_finance/c_bp0c07180851/G37Bp4YMn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
			
		// GET MENU DESC
			$mnCode				= 'MN144';
			$data["MenuApp"] 	= 'MN145';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA		= $_GET['id'];
			$COLLDATA		= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL		= explode("~", $COLLDATA);
			$PRJCODE		= $EXTRACTCOL[0];
			$JournalH_Code	= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_bp0c07180851/update_process');
			
			$MenuCode 			= 'MN144';
			$data["MenuCode"] 	= 'MN144';
			$data["MenuCode1"] 	= 'MN145';
			$data['PRJCODE_HO'] = $this->data['PRJCODE_HO'];
			$proj_Currency		= 'IDR';
			
			$getbankpay 					= $this->m_bank_payment->get_CB_by_number($JournalH_Code)->row();
			$data['default']['CB_NUM'] 		= $getbankpay->CB_NUM;
			$data['default']['CB_CODE'] 	= $getbankpay->CB_CODE;
			$data['default']['PRJCODE'] 	= $getbankpay->PRJCODE;
			$data['PRJCODE']				= $getbankpay->PRJCODE;
			$PRJCODE 						= $getbankpay->PRJCODE;
			$data['default']['CB_DATE']		= $getbankpay->CB_DATE;
			$data['default']['CB_TYPE'] 	= $getbankpay->CB_TYPE;
			$data['default']['CB_SOURCE']	= $getbankpay->CB_SOURCE;
			$data['default']['CB_SOURCENO']	= $getbankpay->CB_SOURCENO;
			$data['default']['CB_CURRID'] 	= $getbankpay->CB_CURRID;
			$data['default']['CB_CURRCONV'] = $getbankpay->CB_CURRCONV;
			$data['default']['CB_ACCID'] 	= $getbankpay->CB_ACCID;
			$data['default']['ACC_NUM'] 	= $getbankpay->CB_ACCID;
			$data['default']['CB_PAYFOR'] 	= $getbankpay->CB_PAYFOR;

			$data['default']['CB_RECTYPE'] 	= $getbankpay->CB_RECTYPE;
			$data['default']['CB_CHEQNO'] 	= $getbankpay->CB_CHEQNO;
			$data['CB_CHEQNO'] 				= $getbankpay->CB_CHEQNO;
			$data['default']['CB_DOCTYPE']	= $getbankpay->CB_DOCTYPE;
			$data['default']['CB_STAT']		= $getbankpay->CB_STAT;
			$data['default']['CB_TOTAM'] 	= $getbankpay->CB_TOTAM;
			$data['default']['CB_TOTAM_PPN']	= $getbankpay->CB_TOTAM_PPN;
			$data['default']['CB_TOTAM_DISC']	= $getbankpay->CB_TOTAM_DISC;
			$data['default']['CB_DPAMOUNT']		= $getbankpay->CB_DPAMOUNT;
			$data['default']['CB_NOTES'] 		= $getbankpay->CB_NOTES;
			$data['default']['VOID_REASON'] 	= $getbankpay->VOID_REASON;
			$data['default']['Patt_Year'] 		= $getbankpay->Patt_Year;
			$data['default']['Patt_Month'] 		= $getbankpay->Patt_Month;
			$data['default']['Patt_Date'] 		= $getbankpay->Patt_Date;
			$data['default']['Patt_Number']		= $getbankpay->Patt_Number;
			
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/G37Bp4YMn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['countAcc'] 	= $this->m_bank_payment->count_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE);
			$data['vwAcc'] 		= $this->m_bank_payment->view_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE)->result();
			
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
	
	function update_process() // G
	{
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$CREATED 		= date('Y-m-d H:i:s');

			$CB_NUM	 		= $this->input->post('CB_NUM');
			$CB_CODE	 	= $this->input->post('CB_CODE');
			$PRJCODE	 	= $this->input->post('PRJCODE');
			$CB_DATE	 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('CB_DATE'))));
			$CB_TYPE		= 'BP';
			$CB_RECTYPE	 	= $this->input->post('CB_RECTYPE');
			$CB_DOCTYPE		= $this->input->post('CB_SOURCE');
			$CB_SOURCE		= $this->input->post('CB_SOURCE');
			$CB_SOURCENO	= $this->input->post('CB_SOURCENO');
			$CB_CURRID	 	= $this->input->post('CB_CURRID');
			$CB_CURRCONV 	= $this->input->post('CB_CURRCONV');
			$CB_ACCID	 	= $this->input->post('CB_ACCID');
			$CB_PAYFOR	 	= $this->input->post('CB_PAYFOR');
			$CB_PAYEE	 	= $this->input->post('CB_PAYFOR');

			/*$CB_CHEQNO	 	= $this->input->post('CB_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$CB_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$CB_CHEQDAT		= $BGDate;
			$CB_CHEQDAT		= "0000-00-00";*/

			$CB_STAT		= $this->input->post('CB_STAT');
			$CB_APPSTAT		= 0;
			$CB_TOTAM	 	= $this->input->post('CB_TOTAM');
			$CB_TOTAM_PPN 	= $this->input->post('CB_TOTAM_PPN');
			$CB_TOTAM_DISC 	= $this->input->post('CB_TOTAM_DISC');
			//$CB_DPAMOUNT 	= $this->input->post('CB_DPAMOUNT');
			$CB_DPAMOUNT 	= 0;
			$CB_NOTES	 	= addslashes($this->input->post('CB_NOTES'));
			$CB_CREATER		= $DefEmp_ID;
			$CB_CREATED		= $CREATED;
			$Company_ID		= $comp_init;
			$Patt_Number	= $this->input->post('Patt_Number');
			$Patt_Year		= date('Y',strtotime($CB_DATE));
			$Patt_Month		= date('m',strtotime($CB_DATE));
			$Patt_Date		= date('d',strtotime($CB_DATE));
			
			$CB_TOTAM		= 0;
			$CB_TOTAM_DISC	= 0;
			$CB_TOTAM_PPN 	= 0;
			foreach($_POST['data'] as $d)
			{
				$CB_TOTAM		= $CB_TOTAM + $d['CBD_AMOUNT'];
				$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $d['CBD_AMOUNT_DISC'];
				$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $d['INV_AMOUNT_PPN'];
			}
			
			$JournalH_Code 	= $CB_NUM;
			$inBankPay 		= array('JournalH_Code'	=> $CB_NUM,
									'CB_NUM' 		=> $CB_NUM,
									'CB_CODE' 		=> $CB_CODE,
									'PRJCODE' 		=> $PRJCODE,
									'CB_DATE' 		=> $CB_DATE,
									'CB_TYPE' 		=> $CB_TYPE,
									'CB_RECTYPE'	=> $CB_RECTYPE,
									'CB_CURRID' 	=> $CB_CURRID,
									'CB_SOURCE' 	=> $CB_SOURCE,
									'CB_SOURCENO' 	=> $CB_SOURCENO,
									'CB_CURRCONV' 	=> 1,
									'CB_ACCID' 		=> $CB_ACCID,
									'CB_PAYFOR' 	=> $CB_PAYFOR,
									'CB_PAYEE' 		=> $CB_PAYEE,
									//'CB_CHEQNO' 	=> $CB_CHEQNO,
									//'CB_CHEQDAT' 	=> $CB_CHEQDAT,
									'CB_DOCTYPE' 	=> $CB_DOCTYPE,
									'CB_STAT' 		=> $CB_STAT,
									'CB_TOTAM' 		=> $CB_TOTAM,
									'CB_TOTAM_PPN' 	=> $CB_TOTAM_PPN,
									'CB_TOTAM_DISC'	=> $CB_TOTAM_DISC,
									'CB_DPAMOUNT'	=> $CB_DPAMOUNT,
									'CB_NOTES' 		=> $CB_NOTES,
									'CB_CREATER' 	=> $CB_CREATER,
									'CB_CREATED' 	=> $CB_CREATED,
									'Company_ID' 	=> $Company_ID);									
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
				
				$CBD_DOCNO 	= $d['CBD_DOCNO'];
				
				// UPDATE STATUS DP
					if($CB_SOURCE == 'DP')
					{
						$updDP 	= array('DP_PAID' 	=> 1);									
						$this->m_bank_payment->updateDP($CBD_DOCNO, $updDP);
					}
				
				$d['JournalH_Code']	= $CB_NUM;
				$d['CB_NUM']		= $CB_NUM;
				$d['CB_CODE']		= $CB_CODE;
				$this->db->insert('tbl_bp_detail',$d);
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "CB_NUM",
										'DOC_CODE' 		=> $CB_NUM,
										'DOC_STAT' 		=> $CB_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_bp_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
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
			
			$url	= site_url('c_finance/c_bp0c07180851/G37Bp4YMn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
 	function inb0c07180851() // G
	{
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_bp0c07180851/p4Y7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function p4Y7_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN145';
				$data["MenuApp"] 	= 'MN145';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN126';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_bp0c07180851/G37Bp4YMn_1n/?id=";
			
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

	function G37Bp4YMn_1n() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN145';
			$data["MenuApp"] 	= 'MN145';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
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
				$data["url_search"] = site_url('c_finance/c_bp0c07180851/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_bank_payment->count_all_BP_inb($PRJCODE, $key, $EmpID);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_bank_payment->get_last_BP_inb($PRJCODE, $start, $end, $key, $EmpID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data['appName'] 	= $appName;
			$data['PRJCODE'] 	= $PRJCODE;
			$data["MenuCode"] 	= 'MN145';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/inb0c07180851/?id='.$this->url_encryption_helper->encode_url($appName));
			
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
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_finance/c_bp0c07180851/G37Bp4YMn_1n/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

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
			
			$columns_valid 	= array("CB_CODE",
									"CB_DATE", 
									"B.SPLDESC", 
									"C.Account_NameId",
									"A.CB_TOTAM",
									"A.CB_NOTES",
									"A.CB_TOTAM");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bank_payment->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code	= $dataI['JournalH_Code'];
				$CB_CODE		= $dataI['CB_CODE'];
				$CB_DATE		= $dataI['CB_DATE'];
				$CB_DATEV		= date('d M Y', strtotime($CB_DATE));
				$CB_TYPE		= $dataI['CB_TYPE'];
				$Account_Name	= $dataI['Account_Name'];
				$CB_PAYFOR		= $dataI['CB_PAYFOR'];
				$SPLDESC		= $dataI['SPLDESC'];
					
				$CB_NOTES		= addslashes($dataI['CB_NOTES']);
				$CB_STAT		= $dataI['CB_STAT'];
				$ISVOID			= $dataI['ISVOID'];
				if($ISVOID == 1)
				{
					$CISVOIDD 		= 'yes';
					$STATVCOL		= 'danger';
				}
				else
				{
					$CISVOIDD 		= 'no';
					$STATVCOL		= 'success';
				}

				$CB_TOTAM		= $dataI['CB_TOTAM'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$CollID			= "$PRJCODE~$JournalH_Code";
				$secUpd			= site_url('c_finance/c_bp0c07180851/uG37Bp4YMn_1n/?id='.$this->url_encryption_helper->encode_url($CollID));
                $secPrint		= site_url('c_finance/c_bp0c07180851/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
                                    
				if($CB_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('')' title='Delete file' class='btn btn-danger btn-xs'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>

									</a>
									</label>";
				}
								
				$output['data'][] = array("<div style='white-space:nowrap'>$CB_CODE</div>",
										  $CB_DATEV,
										  $SPLDESC,
										  "<span style='white-space:nowrap'>$Account_Name</span>",
										  number_format($CB_TOTAM, 2),
										  $CB_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function uG37Bp4YMn_1n()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN145';
			$data["MenuApp"] 	= 'MN145';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA		= $_GET['id'];
			$COLLDATA		= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL		= explode("~", $COLLDATA);
			$PRJCODE		= $EXTRACTCOL[0];
			$JournalH_Code	= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_bp0c07180851/update_process_inb');
			
			$MenuCode 			= 'MN145';
			$data["MenuCode"] 	= 'MN145';
			$proj_Currency		= 'IDR';
			
			$getbankpay 					= $this->m_bank_payment->get_CB_by_number($JournalH_Code)->row();
			$data['default']['CB_NUM'] 		= $getbankpay->CB_NUM;
			$data['default']['CB_CODE'] 	= $getbankpay->CB_CODE;
			$data['default']['PRJCODE'] 	= $getbankpay->PRJCODE;
			$data['PRJCODE']				= $getbankpay->PRJCODE;
			$PRJCODE 						= $getbankpay->PRJCODE;
			$data['default']['CB_DATE']		= $getbankpay->CB_DATE;
			$data['default']['CB_TYPE'] 	= $getbankpay->CB_TYPE;
			$data['default']['CB_SOURCE']	= $getbankpay->CB_SOURCE;
			$data['default']['CB_SOURCENO']	= $getbankpay->CB_SOURCENO;
			$data['default']['CB_CURRID'] 	= $getbankpay->CB_CURRID;
			$data['default']['CB_CURRCONV'] = $getbankpay->CB_CURRCONV;
			$data['default']['CB_ACCID'] 	= $getbankpay->CB_ACCID;
			$data['default']['ACC_NUM'] 	= $getbankpay->CB_ACCID;
			$data['default']['CB_PAYFOR'] 	= $getbankpay->CB_PAYFOR;

			$data['default']['CB_RECTYPE'] 	= $getbankpay->CB_RECTYPE;
			$data['default']['CB_CHEQNO'] 	= $getbankpay->CB_CHEQNO;
			$data['CB_CHEQNO'] 				= $getbankpay->CB_CHEQNO;
			$data['default']['CB_DOCTYPE']	= $getbankpay->CB_DOCTYPE;
			$data['default']['CB_STAT']		= $getbankpay->CB_STAT;
			$data['default']['CB_TOTAM'] 	= $getbankpay->CB_TOTAM;
			$data['default']['CB_TOTAM_PPN']	= $getbankpay->CB_TOTAM_PPN;
			$data['default']['CB_TOTAM_DISC']	= $getbankpay->CB_TOTAM_DISC;
			$data['default']['CB_DPAMOUNT']		= $getbankpay->CB_DPAMOUNT;
			$data['default']['CB_NOTES'] 		= $getbankpay->CB_NOTES;
			$data['default']['VOID_REASON'] 	= $getbankpay->VOID_REASON;
			$data['default']['Patt_Year'] 		= $getbankpay->Patt_Year;
			$data['default']['Patt_Month'] 		= $getbankpay->Patt_Month;
			$data['default']['Patt_Date'] 		= $getbankpay->Patt_Date;
			$data['default']['Patt_Number']		= $getbankpay->Patt_Number;
			
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/G37Bp4YMn_1n/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['countAcc'] 	= $this->m_bank_payment->count_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE); 
			$data['vwAcc'] 		= $this->m_bank_payment->view_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE)->result();

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
		$comp_init 	= $this->session->userdata('comp_init');
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");

			$CB_NUM	 		= $this->input->post('CB_NUM');
			$JournalH_Code 	= $CB_NUM;
			$CB_CODE	 	= $this->input->post('CB_CODE');
			$PRJCODE	 	= $this->input->post('PRJCODE');
			$CB_DATE	 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('CB_DATE'))));
			$CB_TYPE		= 'BP';
			$CB_RECTYPE	 	= $this->input->post('CB_RECTYPE');
			$CB_DOCTYPE		= $this->input->post('CB_SOURCE');
			$CB_SOURCE		= $this->input->post('CB_SOURCE');
			$CB_SOURCENO	= $this->input->post('CB_SOURCENO');
			$CB_CURRID	 	= $this->input->post('CB_CURRID');
			$CB_CURRCONV 	= $this->input->post('CB_CURRCONV');
			$CB_ACCID	 	= $this->input->post('CB_ACCID');
			$CB_PAYFOR	 	= $this->input->post('CB_PAYFOR');
			$CB_PAYEE	 	= $this->input->post('CB_PAYFOR');

			/*$CB_CHEQNO	 	= $this->input->post('CB_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$CB_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$CB_CHEQDAT		= $BGDate;
			$CB_CHEQDAT		= "0000-00-00";*/

			$CB_STAT		= $this->input->post('CB_STAT');
			$CB_APPSTAT		= 0;
			$CB_TOTAM	 	= $this->input->post('CB_TOTAM');
			$CB_TOTAM_PPN 	= $this->input->post('CB_TOTAM_PPN');
			$CB_TOTAM_DISC 	= $this->input->post('CB_TOTAM_DISC');
			//$CB_DPAMOUNT 	= $this->input->post('CB_DPAMOUNT');
			$CB_DPAMOUNT 	= 0;
			$CB_NOTES	 	= addslashes($this->input->post('CB_NOTES'));
			$CB_CREATER		= $DefEmp_ID;
			$CB_CREATED		= date('Y-m-d H:i:s');
			$Company_ID		= $comp_init;
			$Patt_Number	= $this->input->post('Patt_Number');
			$Patt_Year		= date('Y',strtotime($CB_DATE));
			$Patt_Month		= date('m',strtotime($CB_DATE));
			$Patt_Date		= date('d',strtotime($CB_DATE));
		
			$AH_CODE		= $CB_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('CB_NOTES');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			//GET SUPPLIER CATEG
				$SPLCAT		= '';
				$sqlSPLC	= "SELECT SPLCAT FROM tbl_supplier WHERE SPLCODE = '$CB_PAYFOR'";
				$resSPLC	= $this->db->query($sqlSPLC)->result();
				foreach($resSPLC as $rowSPLC) :
					$SPLCAT = $rowSPLC->SPLCAT;
				endforeach;
					
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $CB_DATE;
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
			
			if($CB_STAT == 3)
			{
				// DEFAULT STATUS FOR APPROVE
					$inBankPay 		= array('CB_STAT'		=> 7);					
					$this->m_bank_payment->update($JournalH_Code, $inBankPay);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "CB_NUM",
											'DOC_CODE' 		=> $CB_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_bp_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('PRJCODE'		=> $PRJCODE,
											'AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $CB_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY

				if($AH_ISLAST == 1)
				{
					$CB_TOTAM		= 0;
					$CB_TOTAM_DISC	= 0;
					$CB_TOTAM_PPN 	= 0;
					foreach($_POST['data'] as $d)
					{
						$CB_TOTAM		= $CB_TOTAM + $d['CBD_AMOUNT'];
						$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $d['CBD_AMOUNT_DISC'];
						$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $d['INV_AMOUNT_PPN'];
					}

					$TOT_PAYMENT 	= $CB_TOTAM + $CB_TOTAM_DISC;

					$inBankPay 	= array('CB_STAT' 		=> $CB_STAT,
										'CB_UPDATER' 	=> $DefEmp_ID,
										'CB_LASTUPD' 	=> date('Y-m-d H:i:s'));									
					$this->m_bank_payment->update($JournalH_Code, $inBankPay);
					
					// UPDATE INV STAT
					$TOT_AMOUNT		= 0;
					$TOT_AMOUNT_POT	= 0;
					$TOT_AMOUNT_PPn	= 0;
					$CBD_DOCNOC		= '';
					
					foreach($_POST['data'] as $d)
					{
						$PRJCODE		= $d['PRJCODE'];
						$CBD_DOCNO 		= $d['CBD_DOCNO'];
						$CBD_DOCNOC		= "$CBD_DOCNOC;$CBD_DOCNO";
						$INV_AMOUNT 	= $d['INV_AMOUNT'];
						$CBD_AMOUNT 	= $d['CBD_AMOUNT'];
						$CBD_AMOUNT_DISC= $d['CBD_AMOUNT_DISC'];
						$AMOUNT_DP		= $d['AMOUNT_DP'];
						
						$TOT_AMOUNT		= $TOT_AMOUNT + $CBD_AMOUNT;
						$TOT_AMOUNT_POT = $TOT_AMOUNT_POT + $CBD_AMOUNT_DISC;
						
						// UPDATE STATUS DP
						if($CB_SOURCE == 'DP')
						{
							/*$updDP 	= array('DP_PAID' 			=> 2,
											'DP_AMOUNT_USED' 	=> $TOTAMount);	*/
							$updDP 	= array('DP_PAID' 			=> 2);									
							$this->m_bank_payment->updateDP($CBD_DOCNO, $updDP); 		// OK
						}
						else
						{
							$CBD_AMOUNT		= $d['CBD_AMOUNT'];
							//echo "$Inv_Amount, $Amount, $Amount_PPn, $PPhTax, $PPhAmount, $DiscAmount, $DPAmount<br>";
							/*$paramPINV 	= array('Inv_Amount' 	=> $Inv_Amount,		// Nilai Invoice (+PPn - Potongan (all))
												'Amount' 		=> $Amount,			// Nilai Invoice Pembayaran
												'Amount_PPn' 	=> $Amount_PPn,
												'PPhTax'	 	=> $PPhTax,
												'PPhAmount' 	=> $PPhAmount,
												'DiscAmount' 	=> $DiscAmount,
												'DPAmount' 		=> $DPAmount,
												'InvAmount_PPn'	=> $InvAmount_PPn,
												'InvAmount_PPh'	=> $POTINV_PPh,
												'InvAmount_Ret' => $POTINV_Ret,
												'InvAmount_Disc'=> $POTINV_Disc);*/

							$paramPINV 	= array('INV_AMOUNT' 		=> $INV_AMOUNT,			// NILAI FAKTUR
												'CBD_AMOUNT' 		=> $CBD_AMOUNT,			// NILAI BAYAR
												'CBD_AMOUNT_DISC'	=> $CBD_AMOUNT_DISC);
							$this->m_bank_payment->updatePINV_NEW($CBD_DOCNO, $paramPINV, $JournalH_Code); // OK
						}
					}
						
					// START : JOURNAL HEADER
						$JournalH_Code	= $JournalH_Code;
						$JournalType	= 'BP';
						$JournalH_Date	= $CB_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $CBD_DOCNOC;
						$LastUpdate		= $CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $CBD_DOCNOC;
						$RefType		= 'BP';
						$PRJCODE		= $PRJCODE;
						
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
											'PRJCODE'			=> $PRJCODE);
						$this->m_journal->createJournalHINV($JournalH_Code, $parameters);
					// END : JOURNAL HEADER
					
					// START : JOURNAL DETAIL
						$LA_ACCID	= '';
						$sqlSPL		= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE  = '$SPLCAT' AND LA_CATEG = 'BP' AND LA_DK = 'D' LIMIT 1";
						$resSPL		= $this->db->query($sqlSPL)->result();
						foreach($resSPL as $rowSPL) :
							$LA_ACCID= $rowSPL->LA_ACCID;
						endforeach;

						// UNTUK DI SISI DEBET HANYA HUTANG USAHA
						// DI SISI KREDIT, BISA AKUN KAS BANK DAN POTONGN

				
						$JournalH_Code	= $JournalH_Code;
						$JournalType	= 'BP';
						$JournalH_Date	= $CB_DATE;
						$Company_ID		= $comp_init;
						$Currency_ID	= 'IDR';
						$DOCSource		= "";
						$LastUpdate		= date('Y-m-d H:i:s');
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $INV_NUM;
						$RefType		= 'BP';
						$JSource		= 'PINV';
						$PRJCODE		= $PRJCODE;
						
						$ITM_CODE 		= "";
						$ACC_ID 		= $LA_ACCID;					// AKUN KAS BANK
						$ITM_UNIT 		= '';
						$ITM_QTY 		= 1;
						$ITM_PRICE 		= $CB_TOTAM;
						$ITM_DISC 		= $CB_TOTAM_DISC;
						$TAXCODE1 		= "";
						$TAXPRICE1		= 0;
						
						if($CB_SOURCE == 'DP')
						{
							$TRANS_CATEG 	= "DP~$SPLCAT";
						}
						else
						{
							$TRANS_CATEG 	= "BP-NEW~$SPLCAT";
						}

						// BUATKAN PROSEDUR UNTUK PEMBAYARAN PER INVOICE
						foreach($_POST['data'] as $d)
						{
							$PRJCODE		= $d['PRJCODE'];
							$CBD_DOCNO		= $d['CBD_DOCNO'];			// INV NUMBER
							$INV_AMOUNT		= $d['INV_AMOUNT'];
							$CBD_AMOUNT		= $d['CBD_AMOUNT'];
							$CBD_AMOUNT_DISC= $d['CBD_AMOUNT_DISC'];
							
							$CB_CATEG		= $d['CB_CATEG'];
							
							if($CB_CATEG == "OPN-RET")
							{
								$JournalType	= 'BP-RET';
								$RefType		= 'BP-RET';
								$TRANS_CATEG	= "BP-RET~$SPLCAT";
							}
							
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
												'Reference_Number'	=> $CBD_DOCNO,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'JSource'			=> $JSource,
												'TRANS_CATEG' 		=> $TRANS_CATEG,		// BP = BANK PAYMENT
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $CB_ACCID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $CBD_AMOUNT,
												'ITM_DISC' 			=> 0,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'PPhTax' 			=> 0,
												'PPhAmount' 		=> 0,
												'DiscAmount' 		=> $CBD_AMOUNT_DISC,
												'DPAmount' 			=> 0,
												'InvAmount_PPn' 	=> 0,
												'InvAmount_PPh' 	=> 0,
												'InvAmount_Ret' 	=> 0,
												'InvAmount_Disc' 	=> 0,
												'Ref_Number' 		=> $CB_CODE,
												'CB_NOTES' 			=> $AH_NOTES,
												'SPLCAT'			=> $SPLCAT,
												'INV_AMOUNT'		=> $INV_AMOUNT,
												'CBD_AMOUNT'		=> $CBD_AMOUNT,
												'CBD_AMOUNT_DISC'	=> $CBD_AMOUNT_DISC);
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
							
							// UNTUK KEPERLUAN FINANCIAL TRACK
							// NILAI HUTANG SEBENARNYA = NILAI INVOICE (SBL DIPOTONG) - POTONGAN + PPN;
							// SAMA DENGAN FM_TOTVAL = (INV_VAL + PPN - POT - RET - PPH) + RET + PPH;
							//$FM_TOTVAL	= $Amount - $DiscAmount - $DPAmount;
							$FM_TOTVAL		= $CBD_AMOUNT + $CBD_AMOUNT_DISC;
								// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
								// HARUS DI KURANGI SAAT PEMBUATAN INVOICE, BERUBAH MENJADI HUTANG (AP)
									$this->load->model('m_updash/m_updash', '', TRUE);
									$paramFT = array('DOC_NUM' 		=> $JournalH_Code,
													'DOC_DATE' 		=> $JournalH_Date,
													'DOC_EDATE' 	=> $JournalH_Date,
													'PRJCODE' 		=> $PRJCODE,
													'FIELD_NAME1' 	=> 'FT_CO',
													'FIELD_NAME2' 	=> 'FM_CO',
													'TOT_AMOUNT'	=> $FM_TOTVAL);
									$this->m_updash->finTrack($paramFT);
								// END : TRACK FINANCIAL TRACK
								
							// MENGURANGI ATAS INVOICE
								if($CBD_DOCNO != '')
								{
									if($CB_CATEG == 'DP')
									{
										$sqlDP	= "SELECT DP_DATE, PRJCODE
													FROM tbl_dp_header
													WHERE DP_NUM = '$CBD_DOCNO' LIMIT 1";
										$resDP	= $this->db->query($sqlDP)->result();
										foreach($resDP as $rowDP):
											$DP_DATE	= $rowDP->DP_DATE;
											$DP_DUEDATE	= $rowDP->DP_DATE;
											$PRJCODEX	= $rowDP->PRJCODE;
											
											// MENGURANGI NILAI FT_COP SAAT DP
												$this->load->model('m_updash/m_updash', '', TRUE);
												$paramFT = array('DOC_NUM' 		=> $CBD_DOCNO,
																'DOC_DATE' 		=> $DP_DATE,
																'DOC_EDATE' 	=> $DP_DUEDATE,
																'PRJCODE' 		=> $PRJCODEX,
																'FIELD_NAME1' 	=> 'FT_APM',
																'FIELD_NAME2' 	=> 'FM_APM',
																'TOT_AMOUNT'	=> $FM_TOTVAL);
												$this->m_updash->finTrack($paramFT);
											// END : TRACK FINANCIAL TRACK
										endforeach;
									}
									else
									{
										$sqlINV	= "SELECT INV_DATE, INV_DUEDATE, PRJCODE
													FROM tbl_pinv_header
													WHERE INV_NUM = '$CBD_DOCNO' LIMIT 1";
										$resINV	= $this->db->query($sqlINV)->result();
										foreach($resINV as $rowINV):
											$INV_DATE		= $rowINV->INV_DATE;
											$INV_DUEDATE	= $rowINV->INV_DUEDATE;
											$PRJCODEX		= $rowINV->PRJCODE;
											
											// MENGURANGI NILAI FT_COP SAAT INV
												$this->load->model('m_updash/m_updash', '', TRUE);
												$paramFT = array('DOC_NUM' 		=> $CBD_DOCNO,
																'DOC_DATE' 		=> $INV_DATE,
																'DOC_EDATE' 	=> $INV_DUEDATE,
																'PRJCODE' 		=> $PRJCODEX,
																'FIELD_NAME1' 	=> 'FT_APM',
																'FIELD_NAME2' 	=> 'FM_APM',
																'TOT_AMOUNT'	=> $FM_TOTVAL);
												$this->m_updash->finTrack($paramFT);
											// END : TRACK FINANCIAL TRACK
										endforeach;
									}
								}
						}
					// END : JOURNAL DETAIL
					
					// START : UPDATE LR
						$PERIODM	= date('m', strtotime($CB_DATE));
						$PERIODY	= date('Y', strtotime($CB_DATE));
						if($CB_SOURCE == 'PINV')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_PLAN = BPP_MTR_PLAN+$TOT_PAYMENT 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
					// END : UPDATE LR
					
					// BUATKAN JURNAL JIKA PEMBAYARAN MENGGUNAKAN DP
					if($CB_DPAMOUNT > 0)
					{
						// START : JOURNAL HEADER						
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BP-DP';
							$JournalH_Date	= $CB_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $CBD_DOCNO;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $CBD_DOCNOC;
							$RefType		= 'BP-DP';
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
							//$this->m_journal->createJournalH($JournalH_Code, $parameters); 
							// HOLDED : TIDAK PERLU LG BUAT JURNAL BARU
						// END : JOURNAL HEADER
						
						// START : JOURNAL DETAIL
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BP-DP';
							$JournalH_Date	= $CB_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $CBD_DOCNO;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= '';
							$RefType		= 'BP-DP';
							$JSource		= 'DP';
							$PRJCODE		= $PRJCODE;
							$ITM_CODE 		= $CBD_DOCNO;
							$ACC_ID 		= $Acc_ID;
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $CB_DPAMOUNT;
							$ITM_DISC 		= '';
							$TAXCODE1 		= '';
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "BP-DP~$SPLCAT";
							
							$parameters 	= array('JournalH_Code' => $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Currency_ID' 		=> $Currency_ID,
												'Source'			=> $DOCSource,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $CBD_DOCNO,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'JSource'			=> $JSource,
												'TRANS_CATEG' 		=> $TRANS_CATEG,
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $CB_DPAMOUNT,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'PPhTax' 			=> '',
												'PPhAmount' 		=> 0,
												'DiscAmount' 		=> 0,
												'Notes' 			=> "$AH_NOTES- $CB_CODE");
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						// END : JOURNAL DETAIL
						
						// PENAMBAHAN NILAI BANK KARENA DIPOOTNG OLEH DP
						// HOLDED, BELUM JELAS
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Bank_Acc_ID' LIMIT 1";
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
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$CB_DPAMOUNT,
														Base_Debet2 = Base_Debet2+$CB_DPAMOUNT, BaseD_$accYr = BaseD_$accYr+$CB_DPAMOUNT
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Bank_Acc_ID'";
									//$this->db->query($sqlUpdCOA); HOLDED ON 27 DES 2018
								}
							}
					}
					
					// BUATKAN JURNAL JIKA PEMBAYARAN TERDAPAT DISKON / POTONGAN	
					// SUDAH DI CATEGORI "BP"				
					/*if($CB_TOTAM_Disc > 0)
					{
						// START : JOURNAL DETAIL
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BP-POT';
							$JournalH_Date	= $CB_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $CBD_DOCNO;
							$LastUpdate		= $CREATED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= '';
							$RefType		= 'BP-POT';
							$JSource		= 'POT';
							$PRJCODE		= $PRJCODE;
							$ITM_CODE 		= $CBD_DOCNO;
							$ACC_ID 		= $Acc_ID;
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $CB_TOTAM_Disc;
							$ITM_DISC 		= '';
							$TAXCODE1 		= '';
							$TAXPRICE1		= 0;
							
							$TRANS_CATEG 	= "BP-POT~$SPLCAT";
							
							$parameters 	= array('JournalH_Code' => $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Currency_ID' 		=> $Currency_ID,
												'Source'			=> $DOCSource,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $CBD_DOCNO,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'JSource'			=> $JSource,
												'TRANS_CATEG' 		=> $TRANS_CATEG,
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'PPhTax' 			=> '',
												'PPhAmount' 		=> 0,
												'DiscAmount' 		=> 0);
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						// END : JOURNAL DETAIL
					}*/

					$inBankPay 	= array('CB_STAT'	=> $CB_STAT);					
					$this->m_bank_payment->update($JournalH_Code, $inBankPay);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "CB_NUM",
												'DOC_CODE' 		=> $CB_NUM,
												'DOC_STAT' 		=> $CB_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'	=> '',
												'TBLNAME'		=> "tbl_bp_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			elseif($CB_STAT == 4)
			{
				$inBankPay 		= array('CB_STAT'	=> $CB_STAT);					
				$this->m_bank_payment->update($JournalH_Code, $inBankPay);
				
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($JournalH_Code);
				// END : DELETE HISTORY
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "CB_NUM",
											'DOC_CODE' 		=> $CB_NUM,
											'DOC_STAT' 		=> $CB_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> '',
											'TBLNAME'		=> "tbl_bp_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($CB_STAT == 5)
			{
				$inBankPay 		= array('CB_STAT'	=> $CB_STAT);					
				$this->m_bank_payment->update($JournalH_Code, $inBankPay);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "CB_NUM",
											'DOC_CODE' 		=> $CB_NUM,
											'DOC_STAT' 		=> $CB_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> '',
											'TBLNAME'		=> "tbl_bp_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET

			// START : UPDATE AKUN LAWAN
				/*$Acc_Cr 		= "";
				$sqlACC_Cr 		= "SELECT Acc_Id FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE' AND Journal_DK = 'K' AND JournalH_Code = '$JournalH_Code'";
				$resACC_Cr		= $this->db->query($sqlACC_Cr)->result();
				foreach($resACC_Cr as $row_Cr):
					$Acc_Cr		= $row_Cr->Acc_Id;
				endforeach;
				$Acc_Cr 		= $Bank_Acc_ID;		// LANGSUNG DARI AKUN KAS/BANK YANG DIGUNAKAN

				$updAcc_Db		= "UPDATE tbl_journaldetail SET Acc_Id_Cross = '$Acc_Cr' WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'";
				$this->db->query($updAcc_Db);*/
			// END : UPDATE AKUN LAWAN
			
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
				
				$parameters 	= array('PRJCODE' 		=> $PRJCODE,			// PROJECT
										'TR_TYPE'		=> "PINV",				// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_pinv_header",	// TABLE NAME
										'KEY_NAME'		=> "INV_NUM",			// KEY OF THE TABLE
										'STAT_NAME' 	=> "INV_STAT",			// NAMA FIELD STATUS
										'FIELD_NM_ALL'	=> "TOT_PINV",			// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_PINV_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_PINV_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_PINV_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_PINV_HP",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_PINV_R",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_PINV_FP");		// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashStatDoc($parameters);
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
			$PRJCODE	= '';
			$url	= site_url('c_finance/c_bp0c07180851/G37Bp4YMn_1n/?id='.$this->url_encryption_helper->encode_url($PRJCODE_PRIM));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
 	function inb0c07180851_pRj() // G
	{
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_bp0c07180851/p4Y7_l5t_x1pRj/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function p4Y7_l5t_x1pRj() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Pembayaran";
			}
			else
			{
				$data["h1_title"] 	= "Payment Approval";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN126';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_bp0c07180851/G37Bp4YMn_1npRj/?id=";
			
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

	function G37Bp4YMn_1npRj() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN398';
			$data["MenuApp"] 	= 'MN398';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
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
				$data["url_search"] = site_url('c_finance/c_bp0c07180851/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_bank_payment->count_all_BP_inb($PRJCODE, $key, $EmpID);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_bank_payment->get_last_BP_inb($PRJCODE, $start, $end, $key, $EmpID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data['appName'] 	= $appName;
			$data['PRJCODE'] 	= $PRJCODE;
			$data["MenuCode"] 	= 'MN398';
			$data["urListData"] = 'get_AllData_1n2pRj';
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/inb0c07180851/?id='.$this->url_encryption_helper->encode_url($appName));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN398';
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

  	function get_AllData_1n2pRj() // OK
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
			
			$columns_valid 	= array("CB_CODE",
									"CB_DATE", 
									"SPLDESC", 
									"CB_NOTES",
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
			$num_rows 		= $this->m_bank_payment->get_AllDataC_1n2pRj($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataL_1n2pRj($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code	= $dataI['JournalH_Code'];
				$CB_CODE		= $dataI['CB_CODE'];
				$CB_DATE		= $dataI['CB_DATE'];
				$CB_DATEV		= date('d M Y', strtotime($CB_DATE));
				$CB_TYPE		= $dataI['CB_TYPE'];
				$Account_Name	= $dataI['Account_Name'];
				$CB_PAYFOR		= $dataI['CB_PAYFOR'];
					$SPLDESC	= '';
					$sqlSPL		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE  = '$CB_PAYFOR' LIMIT 1";
					$resSPL		= $this->db->query($sqlSPL)->result();
					foreach($resSPL as $rowSPL) :
						$SPLDESC= $rowSPL->SPLDESC;
					endforeach;
					
				$CB_NOTES		= addslashes($dataI['CB_NOTES']);
				$CB_STAT		= $dataI['CB_STAT'];
				$ISVOID			= $dataI['ISVOID'];
				if($ISVOID == 0)
				{
					$CISVOIDD 		= 'no';
					$STATVCOL		= 'success';
				}
				elseif($CB_STAT == 1)
				{
					$CISVOIDD 		= 'yes';
					$STATVCOL		= 'danger';
				}
				$CB_STAT		= $dataI['CB_STAT'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$CollID			= "$PRJCODE~$JournalH_Code";
				$secUpd			= site_url('c_finance/c_bp0c07180851/uG37Bp4YMn_1npRj/?id='.$this->url_encryption_helper->encode_url($CollID));
                $secPrint		= site_url('c_finance/c_bp0c07180851/printdocument/?id='.$this->url_encryption_helper->encode_url($CollID));
                                    
				if($CB_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('')' title='Delete file' class='btn btn-danger btn-xs'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>

									</a>
									</label>";
				}
								
				$output['data'][] = array("<div style='white-space:nowrap'>$CB_CODE</div>",
										  $CB_DATEV,
										  $SPLDESC,
										  $Account_Name,
										  $CB_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  "<span class='label label-".$STATVCOL."' style='font-size:12px'>".$CISVOIDD."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function uG37Bp4YMn_1npRj()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_payment/m_bank_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN398';
			$data["MenuApp"] 	= 'MN398';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			/*$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);*/

			$COLLDATA		= $_GET['id'];
			$COLLDATA		= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL		= explode("~", $COLLDATA);
			$PRJCODE		= $EXTRACTCOL[0];
			$JournalH_Code	= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_bp0c07180851/update_process_inb');
			
			$MenuCode 			= 'MN398';
			$data["MenuCode"] 	= 'MN398';
			$proj_Currency		= 'IDR';
			
			$getbankpay 					= $this->m_bank_payment->get_CB_by_number($JournalH_Code)->row();
			$data['default']['CB_NUM'] 		= $getbankpay->CB_NUM;
			$data['default']['CB_CODE'] 	= $getbankpay->CB_CODE;
			$data['default']['CB_DATE']		= $getbankpay->CB_DATE;
			$data['default']['CB_TYPE'] 	= $getbankpay->CB_TYPE;
			$data['default']['PRJCODE'] 	= $getbankpay->PRJCODE;
			$data['PRJCODE']				= $getbankpay->PRJCODE;
			$PRJCODE 						= $getbankpay->PRJCODE;
			$data['default']['CB_CURRID'] 	= $getbankpay->CB_CURRID;
			$data['default']['CB_CURRCONV'] = $getbankpay->CB_CURRCONV;
			$data['default']['Acc_ID'] 		= $getbankpay->Acc_ID;
			$data['default']['ACC_NUM'] 	= $getbankpay->Acc_ID;
			$data['default']['CB_PAYFOR'] 	= $getbankpay->CB_PAYFOR;
			$data['default']['CB_CHEQNO'] 	= $getbankpay->CB_CHEQNO;
			$data['CB_CHEQNO'] 				= $getbankpay->CB_CHEQNO;
			$data['default']['CB_SOURCE']	= $getbankpay->CB_SOURCE;
			$data['default']['CB_SOURCENO']	= $getbankpay->CB_SOURCENO;
			$data['default']['CB_DOCTYPE']	= $getbankpay->CB_DOCTYPE;
			$data['default']['CB_STAT']		= $getbankpay->CB_STAT;
			$data['default']['CB_TOTAM'] 	= $getbankpay->CB_TOTAM;
			$data['default']['CB_TOTAM_PPn']= $getbankpay->CB_TOTAM_PPn;
			$data['default']['CB_DPAMOUNT']	= $getbankpay->CB_DPAMOUNT;
			$data['default']['CB_NOTES'] 	= $getbankpay->CB_NOTES;
			$data['default']['Patt_Year'] 	= $getbankpay->Patt_Year;
			$data['default']['Patt_Month'] 	= $getbankpay->Patt_Month;
			$data['default']['Patt_Date'] 	= $getbankpay->Patt_Date;
			$data['default']['Patt_Number']	= $getbankpay->Patt_Number;
			
			$data['backURL'] 	= site_url('c_finance/c_bp0c07180851/G37Bp4YMn_1n/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['countAcc'] 	= $this->m_bank_payment->count_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE);
			$data['vwAcc'] 		= $this->m_bank_payment->view_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE)->result();
			//$data['countSPL'] = $this->m_bank_payment->count_all_SPL($PRJCODE); // ProjCode nya dihilangkan
			//$data['vwSPL'] 	= $this->m_bank_payment->view_all_SPL()->result();
			
			//$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			//$data['vwPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN398';
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
			$data['title'] 			= $appName;
      		$proj_Currency  		= 'IDR';

			$getbankpay 			= $this->m_bank_payment->get_CB_by_number($JournalH_Code)->row();
			$data['CB_NUM'] 		= $getbankpay->CB_NUM;
      		$CB_NUM             	= $getbankpay->CB_NUM;
			$data['CB_CODE'] 		= $getbankpay->CB_CODE;
			$data['PRJCODE'] 		= $getbankpay->PRJCODE;
      		$PRJCODE            	= $getbankpay->PRJCODE;
      		$CB_SOURCE          	= $getbankpay->CB_SOURCE;
			$data['CB_DATE']		= $getbankpay->CB_DATE;
      		$data['CB_DATEY']   	= date('Y', strtotime($getbankpay->CB_DATE));
      		$data['CB_DATEM']   	= date('m', strtotime($getbankpay->CB_DATE));
			$data['CB_TYPE'] 		= $getbankpay->CB_TYPE;
			$data['CB_CURRID'] 		= $getbankpay->CB_CURRID;
			$data['CB_CURRCONV'] 	= $getbankpay->CB_CURRCONV;
			$data['Acc_ID'] 		= $getbankpay->Acc_ID;
      		$Acc_ID            		= $getbankpay->Acc_ID;
			$data['CB_PAYFOR'] 		= $getbankpay->CB_PAYFOR;
      		$CB_PAYFOR            	= $getbankpay->CB_PAYFOR;
			$data['CB_CHEQNO'] 		= $getbankpay->CB_CHEQNO;
			$data['CB_CHEQNO'] 		= $getbankpay->CB_CHEQNO;
			$data['CB_DOCTYPE']		= $getbankpay->CB_DOCTYPE;
			$data['CB_STAT']		= $getbankpay->CB_STAT;
			$data['CB_TOTAM'] 		= $getbankpay->CB_TOTAM;
			$data['CB_TOTAM_PPn']	= $getbankpay->CB_TOTAM_PPn;
			$data['CB_DPAMOUNT']	= $getbankpay->CB_DPAMOUNT;
			$data['CB_NOTES'] 		= $getbankpay->CB_NOTES;
			$data['Patt_Year'] 		= $getbankpay->Patt_Year;
			$data['Patt_Month'] 	= $getbankpay->Patt_Month;
			$data['Patt_Date'] 		= $getbankpay->Patt_Date;
			$data['Patt_Number']	= $getbankpay->Patt_Number;

	      	//create by iyan
	      		$data['countAccName']	= $this->m_bank_payment->count_Acc_Name($proj_Currency, $DefEmp_ID, $PRJCODE, $Acc_ID);
	      		$data['vwAccName'] 		= $this->m_bank_payment->view_Acc_Name($proj_Currency, $DefEmp_ID, $PRJCODE, $Acc_ID)->result();
	
	      		$data['countDocInv']    = $this->m_bank_payment->count_Doc_Inv($CB_SOURCE, $CB_NUM, $PRJCODE, $CB_PAYFOR);
	      		$data['vwDocInv']    	= $this->m_bank_payment->view_Doc_Inv($CB_SOURCE, $CB_NUM, $PRJCODE, $CB_PAYFOR)->result();
	      	// ----------------------------------------------------------------------------------------------------- //

			$this->load->view('v_finance/v_bank_payment/v_print_bpayment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataINV() // GOOD
	{
		$collID1	= $_GET['id'];
		$collID		= explode("~", $collID1);
		$SPLCODE	= $collID[0];
		$PAGEFORM	= $collID[1];
		$PRJCODE	= $collID[2];
		
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
									"INV_CODE",
									"INV_NOTES",
									"",
									"",
									"",
									"");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bank_payment->get_AllDataINVC($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataINVL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$INV_NUM			= $dataI['INV_NUM'];
				$INV_CODE			= $dataI['INV_CODE'];
				$INV_TYPE			= $dataI['INV_TYPE'];
				$INV_CATEG			= $dataI['INV_CATEG'];
				$PO_NUM				= $dataI['PO_NUM'];
				$IR_NUM				= $dataI['IR_NUM'];
				$INV_DATE			= $dataI['INV_DATE'];
				$INV_DATEV			= date('d M Y', strtotime($INV_DATE));
				$INV_DUEDATE		= $dataI['INV_DUEDATE'];
				$INV_DUEDATEV		= date('d M Y', strtotime($INV_DUEDATE));
				$SPLCODE			= $dataI['SPLCODE'];
				$INV_AMOUNT			= $dataI['INV_AMOUNT'];
				$INV_AMOUNT_PPN		= $dataI['INV_AMOUNT_PPN'];
				$INV_AMOUNT_PPH		= $dataI['INV_AMOUNT_PPH'];
				$INV_AMOUNT_DPB		= $dataI['INV_AMOUNT_DPB'];
				$INV_AMOUNT_RET		= $dataI['INV_AMOUNT_RET'];
				$INV_AMOUNT_POT		= $dataI['INV_AMOUNT_POT'];
				$INV_AMOUNT_OTH		= $dataI['INV_AMOUNT_OTH'];
				$INV_AMOUNT_TOT		= $dataI['INV_AMOUNT_TOT'];
				$INV_AMOUNT_PAID	= $dataI['INV_AMOUNT_PAID'];
				$INV_ACC_OTH		= $dataI['INV_ACC_OTH'];
				$INV_PPN			= $dataI['INV_PPN'];
				$PPN_PERC			= $dataI['PPN_PERC'];
				$INV_PPH			= $dataI['INV_PPH'];
				$PPH_PERC			= $dataI['PPH_PERC'];
				$INV_NOTES			= $dataI['INV_NOTES'];
				$INV_NOTES1			= $dataI['INV_NOTES1'];
				$REF_NOTES			= $dataI['REF_NOTES'];

				$SPLDESC		= '-';
				$sqlSUPL		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSUPL		= $this->db->query($sqlSUPL)->result();
				foreach($resSUPL as $rowSUPL) :
					$SPLDESC	= $rowSUPL->SPLDESC;
				endforeach;
				if($INV_NOTES == '')
				{
					$INV_NOTES		= $SPLDESC;
				}
				else
				{
					$INV_NOTES		= "$SPLDESC - $INV_NOTES";
				}

				$INV_REMAMN 		= $INV_AMOUNT_TOT - $INV_AMOUNT_PAID;

				$chkBox			= "<input type='checkbox' name='chk0' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."' onClick='pickThis0(this);'/>";

				$output['data'][] 	= array($chkBox,
										  	$INV_CODE,
										  	$INV_NOTES,
										  	$INV_DUEDATEV,
										  	number_format($INV_AMOUNT, 2),
										  	number_format($INV_AMOUNT_PPN, 2),
										  	number_format($INV_AMOUNT_PPH, 2),
										  	number_format($INV_AMOUNT_RET, 2),
										  	number_format($INV_AMOUNT_PAID, 2),
											number_format($INV_REMAMN, 2));
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataDP() // GOOD
	{
		$collID1	= $_GET['id'];
		$collID		= explode("~", $collID1);
		$SPLCODE	= $collID[0];
		$PAGEFORM	= $collID[1];
		$PRJCODE	= $collID[2];
		
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
									"INV_CODE",
									"INV_NOTES",
									"",
									"",
									"",
									"");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bank_payment->get_AllDataDPC($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataDPL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $INV_NUM 			= $dataI['INV_NUM'];			// 0
                $INV_CODE 			= $dataI['INV_CODE'];			// 1
                $PRJCODE 			= $dataI['PRJCODE'];			// 2
                $INV_AMOUNT 		= $dataI['ITM_AMOUNT'];			// 3
				$INV_AMOUNT_PPN		= $dataI['TAX_AMOUNT_PPn1'];	// 4
				$INV_AMOUNT_PPH 	= 0;
				$INV_AMOUNT_DPB 	= 0;
				$INV_AMOUNT_RET		= 0;
				$INV_AMOUNT_POT 	= 0;
				$INV_AMOUNT_OTH 	= 0;
				$INV_AMOUNT_TOT 	= 0;
				$INV_AMOUNT_PAID 	= 0;
				$INV_ACC_OTH 		= '';
				$INV_PPN 			= 0;
				$PPN_PERC 			= 0;
				$INV_PPH 			= 0;
				$PPH_PERC 			= 0;

                $INV_DUEDATE		= $dataI['INV_DUEDATE'];
				$INV_DUEDATEV		= date('d M Y', strtotime($INV_DUEDATE));
                $PO_NUM 			= $dataI['PO_NUM'];
                $IR_NUM				= $dataI['IR_NUM'];				// 5
                $INV_NOTES	 		= $dataI['INV_NOTES'];			// 6
                $SPLCODE	 		= $dataI['SPLCODE'];
                $INV_CATEG	 		= $dataI['INV_CATEG'];
                $INV_TOTAL	 		= $INV_AMOUNT + $INV_AMOUNT_PPN;
				if($INV_NOTES == '')
				{
					$SPLDESC		= '-';
					$sqlSUPL		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
					$resSUPL		= $this->db->query($sqlSUPL)->result();
					foreach($resSUPL as $rowSUPL) :
						$SPLDESC	= $rowSUPL->SPLDESC;
					endforeach;
					$INV_NOTES		= $SPLDESC;
				}
				
				// GET OTHERS PAYMENT
					/*$Amount			= 0;
					$Amount_PPn		= 0;
					$sqlPAY			= "SELECT A.INV_AMOUNT, A.INV_AMOUNT_PPN FROM tbl_bp_detail A
											INNER JOIN tbl_bp_header B ON A.CB_NUM = B.CB_NUM
										WHERE A.CBD_DOCNO = '$INV_NUM' AND B.CB_STAT = 3";
					$resPAY			= $this->db->query($sqlPAY)->result();
					foreach($resPAY as $rowPAY) :
						$Amount		= $rowPAY->INV_AMOUNT;
						$Amount_PPn	= $rowPAY->INV_AMOUNT_PPN;
					endforeach;
					$INV_REM		= $INV_AMOUNT - $Amount;
					$INV_PPNREM		= $INV_AMOUNT_PPN - $Amount_PPn;
					$INV_TOTALREM 	= $INV_REM + $INV_PPNREM;*/

				/*$chkBox				= "<input type='checkbox' name='chk0' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."' onClick='pickThis0(this);'/>";

				$output['data'][] 	= array($chkBox,
										  	$INV_CODE,
										  	$INV_NOTES,
										  	$INV_DUEDATEV,
										  	number_format($INV_REM, 2),
										  	number_format($INV_PPNREM, 2),
										  	number_format($INV_TOTALREM, 2));*/
				/*$output['data'][] 	= array($chkBox,
										  	$INV_CODE,
										  	$INV_NOTES,
										  	$INV_DUEDATEV,
										  	number_format($INV_AMOUNT, 2),
										  	number_format($INV_AMOUNT_PPN, 2),
										  	number_format($INV_AMOUNT_PPH, 2),
										  	number_format($INV_AMOUNT_RET, 2),
										  	number_format($INV_AMOUNT_PAID, 2),
											number_format($INV_REMAMN, 2));*/
				
				$noU		= $noU + 1;
			}

			$output['data'][] = array("$INV_NUM",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A");

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}