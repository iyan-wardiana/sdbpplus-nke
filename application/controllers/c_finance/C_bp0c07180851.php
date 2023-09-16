<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 12 November 2017
	* File Name		= C_bp0c07180851.php
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
		
			//$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			// $sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
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
		$PRJCODE	= $_GET['id'];
	    $date_s1    = date('Y-m-d');
	    $date_s     = date('Y-m-d', strtotime('-15 days', strtotime($date_s1)));
	    $date_e     = date('Y-m-d');

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
									"CB_DATE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bank_payment->get_AllDataC($PRJCODE, $date_s, $date_e, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataL($PRJCODE, $date_s, $date_e, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code	= $dataI['JournalH_Code'];
				$CB_CODE		= $dataI['CB_CODE'];
				$CB_DATE		= $dataI['CB_DATE'];
				$CB_DATEV		= date('d M Y', strtotime($CB_DATE));
				$CB_TYPE		= $dataI['CB_TYPE'];
				$CB_SOURCE		= $dataI['CB_SOURCE'];
				$Account_Name	= $dataI['Account_Name'];
				$CB_PAYFOR		= $dataI['CB_PAYFOR'];
				$SPLDESC		= $dataI['SPLDESC'];
				$complName		= $dataI['complName'];
				if($SPLDESC == '')
					$SPLDESC 	= $complName;
					
				$CB_NOTES		= $dataI['CB_NOTES'];
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

				$CB_SOURCESD 	= "";
				if($CB_SOURCE == 'PINV')
				{
					$CB_SOURCESD = "Faktur";
				}
				elseif($CB_SOURCE == 'DP')
				{
					$CB_SOURCESD = "Down Payment";
				}
				elseif($CB_SOURCE == 'VCASH')
				{
					$CB_SOURCESD = "Voucher Cash";
				}
				elseif($CB_SOURCE == 'PD')
				{
					$CB_SOURCESD = "Pembayaran Dimuka";
				}
				elseif($CB_SOURCE == 'PPD')
				{
					$CB_SOURCESD = "Penyelesaian Pembayaran Dimuka";
				}
				else
				{
					$CB_SOURCESD = "Pembayaran Lainnya";
				}

				$s_isLock 		= "tbl_journalheader WHERE JournalH_Code = '$JournalH_Code' AND isLock = '1'";
				$r_isLock 		= $this->db->count_all($s_isLock);

				$CollID			= "$PRJCODE~$JournalH_Code";
				$secUpd			= site_url('c_finance/c_bp0c07180851/update/?id='.$this->url_encryption_helper->encode_url($CollID));
                $secPrint		= site_url('c_finance/c_bp0c07180851/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 		= base_url().'index.php/__l1y/trashBP/?id=';
				$secVoid 		= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$delID 			= "$secDelIcut~tbl_bp_header~tbl_bp_detail~CB_NUM~$JournalH_Code~PRJCODE~$PRJCODE";
				$voidID 		= "$secVoid~tbl_bp_header~tbl_bp_detail~CB_NUM~$JournalH_Code~PRJCODE~$PRJCODE";
                
                $isLockD 		= "";             
				if($CB_STAT == 1 || $CB_STAT == 4) 
				{
					$isLockD 	= "";
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
				elseif($CB_STAT == 3 && $r_isLock == 1)
				{
					$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";
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
				elseif($CB_STAT == 3)
				{
					$isLockD 	= "";
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
					$isLockD 	= "";
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				$output['data'][] 	= array("<div style='white-space:nowrap'>$isLockD $CB_CODE</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$CB_SOURCESD." </strong></div>",
										  	$CB_DATEV,
										  	"<span>$SPLDESC</span>",
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

  	function get_AllDataBPGRP() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$CB_STAT		= $_GET['GSTAT'];
		$PERIOD1		= $_GET['PERIOD'];
		$CB_SOURCE		= $_GET['SRC'];
		$SELPRJ			= $_GET['PROJECT'];
		if(!empty($SELPRJ))
			$PRJCODE 	= $SELPRJ;

		$PERIOD			= explode(" - ", $PERIOD1);
		$date_s			= date('Y-m-d', strtotime(str_replace('/', '-', $PERIOD[0])));
		$date_e			= date('Y-m-d', strtotime(str_replace('/', '-', $PERIOD[1])));
		
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
			$num_rows 		= $this->m_bank_payment->get_AllDataGRPC($PRJCODE, $SPLCODE, $CB_STAT, $CB_SOURCE, $date_s, $date_e, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataGRPL($PRJCODE, $SPLCODE, $CB_STAT, $CB_SOURCE, $date_s, $date_e, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code	= $dataI['JournalH_Code'];
				$CB_CODE		= $dataI['CB_CODE'];
				$CB_DATE		= $dataI['CB_DATE'];
				$CB_DATEV		= date('d M Y', strtotime($CB_DATE));
				$CB_TYPE		= $dataI['CB_TYPE'];
				$CB_SOURCE		= $dataI['CB_SOURCE'];
				$Account_Name	= $dataI['Account_Name'];
				$CB_PAYFOR		= $dataI['CB_PAYFOR'];
				$SPLDESC		= $dataI['SPLDESC'];
				$complName		= $dataI['complName'];
				if($SPLDESC == '')
					$SPLDESC 	= $complName;
					
				$CB_NOTES		= $dataI['CB_NOTES'];
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

				$CB_SOURCESD 	= "";
				if($CB_SOURCE == 'PINV')
				{
					$CB_SOURCESD = "Faktur";
				}
				elseif($CB_SOURCE == 'DP')
				{
					$CB_SOURCESD = "Down Payment";
				}
				elseif($CB_SOURCE == 'VCASH')
				{
					$CB_SOURCESD = "Voucher Cash";
				}
				elseif($CB_SOURCE == 'PD')
				{
					$CB_SOURCESD = "Pembayaran Dimuka";
				}
				elseif($CB_SOURCE == 'PPD')
				{
					$CB_SOURCESD = "Penyelesaian Pembayaran Dimuka";
				}
				else
				{
					$CB_SOURCESD = "Pembayaran Lainnya";
				}

				$s_isLock 		= "tbl_journalheader WHERE JournalH_Code = '$JournalH_Code' AND isLock = '1'";
				$r_isLock 		= $this->db->count_all($s_isLock);

				$CollID			= "$PRJCODE~$JournalH_Code";
				$secUpd			= site_url('c_finance/c_bp0c07180851/update/?id='.$this->url_encryption_helper->encode_url($CollID));
                $secPrint		= site_url('c_finance/c_bp0c07180851/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 		= base_url().'index.php/__l1y/trashBP/?id=';
				$secVoid 		= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$delID 			= "$secDelIcut~tbl_bp_header~tbl_bp_detail~CB_NUM~$JournalH_Code~PRJCODE~$PRJCODE";
				$voidID 		= "$secVoid~tbl_bp_header~tbl_bp_detail~CB_NUM~$JournalH_Code~PRJCODE~$PRJCODE";
                
                $isLockD 		= "";             
				if($CB_STAT == 1 || $CB_STAT == 4) 
				{
					$isLockD 	= "";
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
				elseif($CB_STAT == 3 && $r_isLock == 1)
				{
					$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";
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
				elseif($CB_STAT == 3)
				{
					$isLockD 	= "";
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
					$isLockD 	= "";
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				$output['data'][] 	= array("<div style='white-space:nowrap'>$isLockD $CB_CODE</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$CB_SOURCESD." </strong></div>",
										  	$CB_DATEV,
										  	"<span style='white-space:nowrap'>$SPLDESC</span>",
										  	"<span style='white-space:nowrap'>$Account_Name</span>",
										  	number_format($CB_TOTAM, 2),
										  	$CB_NOTES,
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secAction);
				$noU		= $noU + 1;
			}
								
			/*$output['data'][] 	= array("A = $SPLCODE = $CB_STAT = $CB_SOURCE",
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
			$PRJCODE	= $collID[0];
			$SPLCODE	= $collID[1];
			$PAGEFORM	= $collID[2];
			
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
			$JournalH_Code 	= $this->input->post('CB_NUM');
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
			$CB_PAYTYPE	 	= $this->input->post('CB_PAYTYPE');

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
			$CB_TOTAM_PPH 	= $this->input->post('CB_TOTAM_PPH');
			$CB_TOTAM_DISC 	= $this->input->post('CB_TOTAM_DISC');
			//$CB_DPAMOUNT 	= $this->input->post('CB_DPAMOUNT');
			$CB_DPAMOUNT 	= 0;
			$CB_NOTES	 	= htmlentities($this->input->post('CB_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
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
				//$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$CB_TOTAM		= 0;
			$CB_TOTAM_DISC	= 0;
			$CB_TOTAM_PPN 	= 0;
			$CB_TOTAM_PPH 	= 0;
			
			if(isset($_POST['data']))
			{
				foreach($_POST['data'] as $d)
				{
					$CB_TOTAM		= $CB_TOTAM + $d['CBD_AMOUNT'];
					$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $d['CBD_AMOUNT_DISC'];
					$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $d['INV_AMOUNT_PPN'];
					$CB_TOTAM_PPH	= $CB_TOTAM_PPH + $d['INV_AMOUNT_PPH'];
				}
			}
			
			if(isset($_POST['dataACC']))
			{
				$TBase_D = 0;
				$TBase_K = 0;
				foreach($_POST['dataACC'] as $d)
				{
					$Journal_DK 	= $d['Journal_DK'];
					if($Journal_DK == 'D')
					{
						$Base_D 	= $d['JournalD_Amount'];
						$TBase_D	= $TBase_D + $Base_D;
					}
					else
					{
						$Base_K 	= $d['JournalD_Amount'];
						$TBase_K	= $TBase_K + $Base_K;
					}

					// $CB_TOTAM		= $CB_TOTAM + $dACC['JournalD_Amount'];
					$CB_TOTAM		= $TBase_D - $TBase_K;
					// $CB_TOTAM		= $CB_TOTAM + $CB_TOTAM1;
				}
			}

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$ACC_CLASS 		= 3; 			// Default
				$CBACCID 		= $CB_ACCID;
				if($CB_PAYTYPE == 'PD')
					$CBACCID 	= "BA.PD";
				else
				{
					$s_ACCCB 	= "SELECT Account_Class FROM tbl_chartaccount WHERE Account_Number = '$CB_ACCID' AND PRJCODE = '$PRJCODE'";
					$r_ACCCB 	= $this->db->query($s_ACCCB)->result();
					foreach($r_ACCCB as $rw_ACCCB):
						$ACC_CLASS 	= $rw_ACCCB->Account_Class;
						if($ACC_CLASS == 2)		// UNTUK KONDISI JIKA PEMBAYARAN MENGGUNAKAN SELAIN KAS/BANK. REQ BU RACHMA 03 08 2022 AGAR DISAMAKAN KODENYA MENJADI "BA."
							$CBACCID = "BA.PD";
					endforeach;

				}

				$this->load->model('m_updash/m_updash', '', TRUE);
				$PATTCODE 		= $this->input->post('PATTCODE');
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN144',
										'DOCTYPE' 		=> 'BP',
										'DOCNUM' 		=> $CB_NUM,
										'DOCCODE'		=> $CB_CODE,
										'DOCDATE'		=> $CB_DATE,
										'ACC_ID'		=> $CBACCID,
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $CB_CODE 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
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
									'CB_PAYTYPE' 	=> $CB_PAYTYPE,
									'CB_DOCTYPE' 	=> $CB_DOCTYPE,
									'CB_STAT' 		=> $CB_STAT,
									'CB_TOTAM' 		=> $CB_TOTAM,
									'CB_TOTAM_PPN' 	=> $CB_TOTAM_PPN,
									'CB_TOTAM_PPH' 	=> $CB_TOTAM_PPH,
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

			if($CB_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$BP_VAL 	= $CB_TOTAM;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $CB_DATE,
										'FVAL'		=> $BP_VAL,
										'FNAME'		=> "BP_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD

				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "BP",
										'AS_MNCODE'		=> "MN145",
										'AS_DOCNUM'		=> $CB_NUM,
										'AS_DOCCODE'	=> $CB_CODE,
										'AS_DOCDATE'	=> $CB_DATE,
										'AS_EXPDATE'	=> $CB_DATE);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
			}
			
			if(isset($_POST['data']))
			{
				foreach($_POST['data'] as $d)
				{
					/*$PRJCODE 		= $PRJCODE;
					$RR_Number 		= $RR_Number;
					$LPMCODE 		= $LPMCODE;
					$ITM_CODE 		= $d['ITM_CODE'];
					$ITM_QTY 		= $d['ITM_QTY'];
					$ITM_PRICE 		= $d['ITM_PRICE'];*/
					
					$CBD_DOCNO 		= $d['CBD_DOCNO'];				// VOUCHER NUMBER
					$CBD_DOCCODE 	= $d['CBD_DOCCODE'];			// VOUCHER CODE
					
					// UPDATE STATUS DP
						if($CB_SOURCE == 'DP')
						{
							$updDP 	= array('DP_PAID' 	=> 1);									
							$this->m_bank_payment->updateDP($CBD_DOCNO, $updDP);

							$s_01 	= "SELECT DP_REFNUM, DP_REFCODE, TTK_NUM, TTK_CODE FROM tbl_dp_header WHERE DP_NUM = '$CBD_DOCNO'";
							$r_01 	= $this->db->query($s_01)->result();
							foreach($r_01 as $rw_01) :
								$d['TTK_NUM'] 	= $rw_01->TTK_NUM;			// SPK / OP NUM
								$d['TTK_CODE'] 	= $rw_01->TTK_CODE;			// SPK / OP CODE
								$d['OPNIR_NUM'] = $rw_01->DP_REFNUM;
								$d['OPNIR_CODE']= $rw_01->DP_REFCODE;
								$d['SRC_NUM'] 	= $rw_01->DP_REFNUM;
								$d['SRC_CODE'] 	= $rw_01->DP_REFCODE;
							endforeach;
						}
						elseif($CB_SOURCE == 'PINV')
						{
							$s_01 	= "SELECT TTK_NUM, TTK_CODE, TTK_REF1_NUM, TTK_REF1_CODE, TTK_REF2_NUM, TTK_REF2_CODE FROM tbl_ttk_detail
										WHERE TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail WHERE INV_NUM = '$CBD_DOCNO')";
							$r_01 	= $this->db->query($s_01)->result();
							foreach($r_01 as $rw_01) :
								$d['TTK_NUM'] 	= $rw_01->TTK_NUM;
								$d['TTK_CODE'] 	= $rw_01->TTK_CODE;
								$d['OPNIR_NUM'] = $rw_01->TTK_REF1_NUM;		// OPN / LPM NUM
								$d['OPNIR_CODE']= $rw_01->TTK_REF1_CODE;	// OPN / LPM NUM
								$d['SRC_NUM'] 	= $rw_01->TTK_REF2_NUM;		// SPK / OP NUM
								$d['SRC_CODE'] 	= $rw_01->TTK_REF2_CODE;	// SPK / OP NUM
							endforeach;
						}
					
					$d['JournalH_Code']	= $CB_NUM;
					$d['CB_NUM']		= $CB_NUM;
					$d['CB_CODE']		= $CB_CODE;
					$this->db->insert('tbl_bp_detail',$d);

					// UPDATE VOUCHER/TTK/LPM/OPN
						$s_01a		= "UPDATE tbl_pinv_header SET BP_CODE = '$CB_CODE', BP_DATE = '$CB_DATE' WHERE INV_NUM = '$CBD_DOCNO'";
						$this->db->query($s_01a);

						$s_01b		= "UPDATE tbl_journalheader_vcash SET BP_CODE = '$CB_CODE', BP_DATE = '$CB_DATE' WHERE JournalH_Code = '$CBD_DOCNO'";
						$this->db->query($s_01b);

						$s_01c		= "UPDATE tbl_journalheader_pd SET BP_CODE = '$CB_CODE', BP_DATE = '$CB_DATE' WHERE JournalH_Code = '$CBD_DOCNO'";
						$this->db->query($s_01c);

						$s_02		= "UPDATE tbl_ttk_header A, tbl_pinv_header B SET A.BP_CODE = '$CB_CODE', A.BP_DATE = '$CB_DATE' 
										WHERE A.PRJCODE = B.PRJCODE AND A.INV_CODE = B.INV_CODE AND B.INV_CODE = '$CBD_DOCCODE'";
						$this->db->query($s_02);

						$s_03		= "UPDATE tbl_ir_header A, tbl_pinv_header B SET A.BP_CODE = '$CB_CODE', A.BP_DATE = '$CB_DATE' 
										WHERE A.PRJCODE = B.PRJCODE AND A.INV_CODE = B.INV_CODE AND B.INV_CODE = '$CBD_DOCCODE'";
						$this->db->query($s_03);

						$s_03		= "UPDATE tbl_opn_header A, tbl_pinv_header B SET A.BP_CODE = '$CB_CODE', A.BP_DATE = '$CB_DATE' 
										WHERE A.PRJCODE = B.PRJCODE AND A.INV_CODE = B.INV_CODE AND B.INV_CODE = '$CBD_DOCCODE'";
						$this->db->query($s_03);
				}
			}

			$PRJPERIOD			= $PRJCODE;
			$proj_CodeHO		= $PRJCODE;
			$sqlPRJHO 			= "SELECT isHO, PRJCODE_HO, PRJPERIOD FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJHO			= $this->db->query($sqlPRJHO)->result();
			foreach($resPRJHO as $rowPRJHO):
				$proj_CodeHO	= $rowPRJHO->PRJCODE_HO;
				$PRJPERIOD		= $rowPRJHO->PRJPERIOD;
			endforeach;
			
			if(isset($_POST['dataACC']))
			{
				$jrnRow 		= 0;
				foreach($_POST['dataACC'] as $d)
				{
					$Acc_Id 		= $d['Acc_Id'];
					$Acc_Name 		= $d['JournalD_Desc'];
					// $Base_Debet		= $d['JournalD_Amount'];
					$Journal_Amount	= $d['JournalD_Amount'];
					$Journal_DK		= $d['Journal_DK'];
					$Ref_Number		= $d['Ref_Number'];
					$Other_Desc		= $d['Other_Desc'];
					$d['CB_CODE']		= $CB_CODE;

					if($Journal_Amount > 0)
					{
						$jrnRow 		= $jrnRow+1;
						if($Journal_DK == 'D')
						{
							$insSQL	= "INSERT INTO tbl_journaldetail_bp (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
											Currency_id, JournalD_Debet, Base_Debet, COA_Debet, curr_rate, isDirect,
											ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
											GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$CB_NUM', '$Acc_Id', '$CB_ACCID', 'BP', '$PRJCODE',
											'IDR', $Journal_Amount, $Journal_Amount, $Journal_Amount, 1, 1, 
											1, '$Journal_Amount', '$Ref_Number', '$Other_Desc', 'D', 'BP', 0, 
											'$CB_STAT', '$CB_DATE', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, $jrnRow)";
							$this->db->query($insSQL);
						}
						else
						{
							$isTax = 0;
							$s_tax = "tbl_tax_la WHERE TAXLA_LINKOUT = '$Acc_Id'";
							$r_tax = $this->db->count_all($s_tax);
							if($r_tax > 0) $isTax = 1;

							$insSQL	= "INSERT INTO tbl_journaldetail_bp (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
											Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, curr_rate, isDirect,
											ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
											GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$CB_NUM', '$Acc_Id', '$CB_ACCID', 'BP', '$PRJCODE',
											'IDR', $Journal_Amount, $Journal_Amount, $Journal_Amount, 1, 1, 
											1, '$Journal_Amount', '$Ref_Number', '$Other_Desc', 'K', 'BP', $isTax, 
											'$CB_STAT', '$CB_DATE', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, $jrnRow)";
							$this->db->query($insSQL);
						}

						// $insSQL	= "INSERT INTO tbl_journaldetail_bp (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
						// 				Currency_id, JournalD_Debet, Base_Debet, COA_Debet, curr_rate, isDirect,
						// 				ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
						// 				GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
						// 				('$CB_NUM', '$Acc_Id', '$CB_ACCID', 'BP', '$PRJCODE',
						// 				'IDR', $Base_Debet, $Base_Debet, $Base_Debet, 1, 1, 
						// 				1, '$Base_Debet', '$Ref_Number', '$Other_Desc', '$Journal_DK', 'BP', 0, 
						// 				'$CB_STAT', '$CB_DATE', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, $jrnRow)";
						// $this->db->query($insSQL);
					}
				}
			}
			
			if($CB_PAYTYPE == 'PD' && isset($_POST['dataPD']))
			{
				foreach($_POST['dataPD'] as $d)
				{
					$d['PRJCODE']		= $PRJCODE;
					$d['CB_NUM']		= $CB_NUM;
					$d['CB_CODE']		= $CB_CODE;
					$this->db->insert('tbl_bp_detail_pd',$d);
				}
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

			// START : ADD DOC HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				date_default_timezone_set("Asia/Jakarta");
				$paramTrack 	= array('REF_NUM' 		=> $CB_NUM,
										'TBLNAME' 		=> "tbl_bp_header",
										'FLDCODE'		=> "CB_CODE",
										'FLDNAME'		=> "CB_NUM",
										'HISTTYPE'		=> "Penambahan ($CB_STAT)");
				$this->m_updash->uDocH($paramTrack);
			// END : ADD DOC HISTORY
			
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
			$data['default']['CB_PAYTYPE'] 	= $getbankpay->CB_PAYTYPE;
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
			$data['default']['CB_TOTAM_PPH']	= $getbankpay->CB_TOTAM_PPH;
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
			$JournalH_Code	 = $this->input->post('CB_NUM');
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
			$CB_PAYTYPE	 	= $this->input->post('CB_PAYTYPE');

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
			$CB_TOTAM_PPH 	= $this->input->post('CB_TOTAM_PPH');
			$CB_TOTAM_DISC 	= $this->input->post('CB_TOTAM_DISC');
			//$CB_DPAMOUNT 	= $this->input->post('CB_DPAMOUNT');
			$CB_DPAMOUNT 	= 0;
			$CB_NOTES	 	= htmlentities($this->input->post('CB_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
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
			$CB_TOTAM_PPH 	= 0;
			if(isset($_POST['data']))
			{
				foreach($_POST['data'] as $d)
				{
					$CB_TOTAM		= $CB_TOTAM + $d['CBD_AMOUNT'];
					$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $d['CBD_AMOUNT_DISC'];
					$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $d['INV_AMOUNT_PPN'];
					$CB_TOTAM_PPH	= $CB_TOTAM_PPH + $d['INV_AMOUNT_PPH'];
				}
			}
			
			if(isset($_POST['dataACC']))
			{
				$TBase_D 		= 0;
				$TBase_K 		= 0;
				foreach($_POST['dataACC'] as $dACC)
				{
					$Journal_DK 	= $dACC['Journal_DK'];
					if($Journal_DK == 'D')
					{
						$Base_D 	= $dACC['JournalD_Amount'];
						$TBase_D	= $TBase_D + $Base_D;
					}
					else
					{
						$Base_K 	= $dACC['JournalD_Amount'];
						$TBase_K	= $TBase_K + $Base_K;
					}

					// $CB_TOTAM		= $CB_TOTAM + $dACC['JournalD_Amount'];
					$CB_TOTAM		= $TBase_D - $TBase_K;
					// $CB_TOTAM		= $CB_TOTAM + $CB_TOTAM1;
				}
			}

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$ACC_CLASS 		= 3; 			// Default
				$CBACCID 		= $CB_ACCID;
				if($CB_PAYTYPE == 'PD')
					$CBACCID 	= "BA.PD";
				else
				{
					$s_ACCCB 	= "SELECT Account_Class FROM tbl_chartaccount WHERE Account_Number = '$CB_ACCID' AND PRJCODE = '$PRJCODE'";
					$r_ACCCB 	= $this->db->query($s_ACCCB)->result();
					foreach($r_ACCCB as $rw_ACCCB):
						$ACC_CLASS 	= $rw_ACCCB->Account_Class;
						if($ACC_CLASS == 2)		// UNTUK KONDISI JIKA PEMBAYARAN MENGGUNAKAN SELAIN KAS/BANK. REQ BU RACHMA 03 08 2022 AGAR DISAMAKAN KODENYA MENJADI "BA."
							$CBACCID = "BA.PD";
					endforeach;
				}
				
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN144',
										'DOCNUM' 		=> $CB_NUM,
										'DOCCODE'		=> $CB_CODE,
										'DOCDATE'		=> $CB_DATE,
										'ACC_ID'		=> $CBACCID,
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist

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
									'CB_PAYTYPE' 	=> $CB_PAYTYPE,
									'CB_DOCTYPE' 	=> $CB_DOCTYPE,
									'CB_STAT' 		=> $CB_STAT,
									'CB_TOTAM' 		=> $CB_TOTAM,
									'CB_TOTAM_PPN' 	=> $CB_TOTAM_PPN,
									'CB_TOTAM_PPH' 	=> $CB_TOTAM_PPH,
									'CB_TOTAM_DISC'	=> $CB_TOTAM_DISC,
									'CB_DPAMOUNT'	=> $CB_DPAMOUNT,
									'CB_NOTES' 		=> $CB_NOTES,
									'CB_CREATER' 	=> $CB_CREATER,
									'CB_CREATED' 	=> $CB_CREATED,
									'Company_ID' 	=> $Company_ID);									
			$this->m_bank_payment->update($JournalH_Code, $inBankPay);

			if($CB_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$BP_VAL 	= $CB_TOTAM;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $CB_DATE,
										'FVAL'		=> $BP_VAL,
										'FNAME'		=> "BP_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD

				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "BP",
										'AS_MNCODE'		=> "MN145",
										'AS_DOCNUM'		=> $CB_NUM,
										'AS_DOCCODE'	=> $CB_CODE,
										'AS_DOCDATE'	=> $CB_DATE,
										'AS_EXPDATE'	=> $CB_DATE);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
			}

			if(isset($_POST['data']))
			{
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

							$s_01 	= "SELECT DP_REFNUM, DP_REFCODE, TTK_NUM, TTK_CODE FROM tbl_dp_header WHERE DP_NUM = '$CBD_DOCNO'";
							$r_01 	= $this->db->query($s_01)->result();
							foreach($r_01 as $rw_01) :
								$d['TTK_NUM'] 	= $rw_01->TTK_NUM;			// SPK / OP NUM
								$d['TTK_CODE'] 	= $rw_01->TTK_CODE;			// SPK / OP CODE
								$d['OPNIR_NUM'] = $rw_01->DP_REFNUM;
								$d['OPNIR_CODE']= $rw_01->DP_REFCODE;
								$d['SRC_NUM'] 	= $rw_01->DP_REFNUM;
								$d['SRC_CODE'] 	= $rw_01->DP_REFCODE;
							endforeach;
						}
						elseif($CB_SOURCE == 'PINV')
						{
							$s_01 	= "SELECT TTK_NUM, TTK_CODE, TTK_REF1_NUM, TTK_REF1_CODE, TTK_REF2_NUM, TTK_REF2_CODE FROM tbl_ttk_detail
										WHERE TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail WHERE INV_NUM = '$CBD_DOCNO')";
							$r_01 	= $this->db->query($s_01)->result();
							foreach($r_01 as $rw_01) :
								$d['TTK_NUM'] 	= $rw_01->TTK_NUM;
								$d['TTK_CODE'] 	= $rw_01->TTK_CODE;
								$d['OPNIR_NUM'] = $rw_01->TTK_REF1_NUM;		// OPN / LPM NUM
								$d['OPNIR_CODE']= $rw_01->TTK_REF1_CODE;	// OPN / LPM NUM
								$d['SRC_NUM'] 	= $rw_01->TTK_REF2_NUM;		// SPK / OP NUM
								$d['SRC_CODE'] 	= $rw_01->TTK_REF2_CODE;	// SPK / OP NUM
							endforeach;
						}
					
					$d['JournalH_Code']	= $CB_NUM;
					$d['CB_NUM']		= $CB_NUM;
					$d['CB_CODE']		= $CB_CODE;
					$this->db->insert('tbl_bp_detail',$d);
				}
			}
			
			if(isset($_POST['dataACC']))
			{
				$s_DELJRN 		= "DELETE FROM tbl_journaldetail_bp WHERE JournalH_Code = '$CB_NUM' AND proj_Code = '$PRJCODE'";
				$this->db->query($s_DELJRN);

				$jrnRow 		= 0;
				foreach($_POST['dataACC'] as $d)
				{
					$jrnRow 		= $jrnRow+1;
					$Acc_Id 		= $d['Acc_Id'];
					$Acc_Name 		= $d['JournalD_Desc'];
					// $Base_Debet		= $d['JournalD_Amount'];
					$Journal_Amount	= $d['JournalD_Amount']; // Amount Debet/Kredit
					$Journal_DK		= $d['Journal_DK'];
					$Ref_Number		= $d['Ref_Number'];
					$Other_Desc		= $d['Other_Desc'];
					$proj_CodeHO	= $d['proj_CodeHO'];
					$PRJPERIOD		= $d['PRJPERIOD'];

					if($Journal_Amount > 0)
					{
						if($Journal_DK == 'D')
						{
							$insSQL	= "INSERT INTO tbl_journaldetail_bp (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
											Currency_id, JournalD_Debet, Base_Debet, COA_Debet, curr_rate, isDirect,
											ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
											GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$CB_NUM', '$Acc_Id', '$CB_ACCID', 'BP', '$PRJCODE',
											'IDR', $Journal_Amount, $Journal_Amount, $Journal_Amount, 1, 1, 
											1, '$Journal_Amount', '$Ref_Number', '$Other_Desc', 'D', 'BP', 0, 
											'$CB_STAT', '$CB_DATE', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, $jrnRow)";
							$this->db->query($insSQL);
						}
						else
						{
							$isTax = 0;
							$s_tax = "tbl_tax_la WHERE TAXLA_LINKOUT = '$Acc_Id'";
							$r_tax = $this->db->count_all($s_tax);
							if($r_tax > 0) $isTax = 1;

							$insSQL	= "INSERT INTO tbl_journaldetail_bp (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
											Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, curr_rate, isDirect,
											ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
											GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$CB_NUM', '$Acc_Id', '$CB_ACCID', 'BP', '$PRJCODE',
											'IDR', $Journal_Amount, $Journal_Amount, $Journal_Amount, 1, 1, 
											1, '$Journal_Amount', '$Ref_Number', '$Other_Desc', 'K', 'BP', $isTax, 
											'$CB_STAT', '$CB_DATE', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, $jrnRow)";
							$this->db->query($insSQL);
						}

						// $insSQL	= "INSERT INTO tbl_journaldetail_bp (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
						// 				Currency_id, JournalD_Debet, Base_Debet, COA_Debet, curr_rate, isDirect,
						// 				ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
						// 				GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
						// 				('$CB_NUM', '$Acc_Id', '$CB_ACCID', 'BP', '$PRJCODE',
						// 				'IDR', $Base_Debet, $Base_Debet, $Base_Debet, 1, 1, 
						// 				1, '$Base_Debet', '$Ref_Number', '$Other_Desc', '$Journal_DK', 'BP', 0, 
						// 				'$CB_STAT', '$CB_DATE', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, $jrnRow)";
						// $this->db->query($insSQL);
					}

				}
			}
			
			if($CB_PAYTYPE == 'PD' && isset($_POST['dataPD']))
			{
				$s_DELPD 		= "DELETE FROM tbl_bp_detail_pd WHERE CB_NUM = '$CB_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_DELPD);

				foreach($_POST['dataPD'] as $d)
				{
					$d['PRJCODE']		= $PRJCODE;
					$d['CB_NUM']		= $CB_NUM;
					$d['CB_CODE']		= $CB_CODE;
					$d['CB_CODE']		= $CB_CODE;
					$PD_PAYMENT 		= $d['PD_PAYMENT'];
					$this->db->insert('tbl_bp_detail_pd',$d);

					if($CB_STAT == 2)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$PPD_VAL 	= $PD_PAYMENT;
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $CB_DATE,
												'FVAL'		=> $PPD_VAL,
												'FNAME'		=> "PPD_VAL");
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}
				}
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

			// START : ADD DOC HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				date_default_timezone_set("Asia/Jakarta");
				$paramTrack 	= array('REF_NUM' 		=> $CB_NUM,
										'TBLNAME' 		=> "tbl_bp_header",
										'FLDCODE'		=> "CB_CODE",
										'FLDNAME'		=> "CB_NUM",
										'HISTTYPE'		=> "Update ($CB_STAT)");
				$this->m_updash->uDocH($paramTrack);
			// END : ADD DOC HISTORY
			
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
				$CB_SOURCE		= $dataI['CB_SOURCE'];
				$Account_Name	= $dataI['Account_Name'];
				$CB_PAYFOR		= $dataI['CB_PAYFOR'];
				$SPLDESC		= $dataI['SPLDESC'];
				$complName		= $dataI['complName'];
				if($SPLDESC == '')
					$SPLDESC 	= $complName;
					
				$CB_NOTES		= $dataI['CB_NOTES'];
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

				$CB_SOURCESD 	= "";
				if($CB_SOURCE == 'PINV')
				{
					$CB_SOURCESD = "Faktur";
				}
				elseif($CB_SOURCE == 'DP')
				{
					$CB_SOURCESD = "Down Payment";
				}
				elseif($CB_SOURCE == 'VCASH')
				{
					$CB_SOURCESD = "Voucher Cash";
				}
				elseif($CB_SOURCE == 'PD')
				{
					$CB_SOURCESD = "Pembayaran Dimuka";
				}
				elseif($CB_SOURCE == 'PPD')
				{
					$CB_SOURCESD = "Penyelesaian Pembayaran Dimuka";
				}
				else
				{
					$CB_SOURCESD = "Pembayaran Lainnya";
				}
				
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
				
								
				$output['data'][] 	= array("<div style='white-space:nowrap'>$CB_CODE</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$CB_SOURCESD." </strong></div>",
										  	$CB_DATEV,
										  	"<span style='white-space:nowrap'>$SPLDESC</span>",
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
			$data['default']['CB_PAYTYPE'] 	= $getbankpay->CB_PAYTYPE;

			$data['default']['CB_RECTYPE'] 	= $getbankpay->CB_RECTYPE;
			$data['default']['CB_CHEQNO'] 	= $getbankpay->CB_CHEQNO;
			$data['CB_CHEQNO'] 				= $getbankpay->CB_CHEQNO;
			$data['default']['CB_DOCTYPE']	= $getbankpay->CB_DOCTYPE;
			$data['default']['CB_STAT']		= $getbankpay->CB_STAT;
			$data['default']['CB_TOTAM'] 	= $getbankpay->CB_TOTAM;
			$data['default']['CB_TOTAM_PPN']	= $getbankpay->CB_TOTAM_PPN;
			$data['default']['CB_TOTAM_PPH']	= $getbankpay->CB_TOTAM_PPH;
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
			$CB_PAYTYPE	 	= $this->input->post('CB_PAYTYPE');
			$SPLDESC		= '';
			$SPLCODE		= $CB_PAYFOR;
			$sqlSPL			= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE  = '$CB_PAYFOR' LIMIT 1";
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL) :
				$SPLDESC 	= htmlentities($rowSPL->SPLDESC, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			endforeach;

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
			$CB_TOTAM_PPH 	= $this->input->post('CB_TOTAM_PPH');
			$CB_TOTAM_DISC 	= $this->input->post('CB_TOTAM_DISC');
			//$CB_DPAMOUNT 	= $this->input->post('CB_DPAMOUNT');
			$CB_DPAMOUNT 	= 0;
			$CB_NOTES	 	= htmlentities($this->input->post('CB_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
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
					$CB_TOTAM_PPH 	= 0;

					if(isset($_POST['data']))
					{
						foreach($_POST['data'] as $d)
						{
							$CB_TOTAM		= $CB_TOTAM + $d['CBD_AMOUNT'];
							$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $d['CBD_AMOUNT_DISC'];
							$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $d['INV_AMOUNT_PPN'];
							$CB_TOTAM_PPH	= $CB_TOTAM_PPH + $d['INV_AMOUNT_PPH'];
						}
					}
			
					if(isset($_POST['dataACC']))
					{
						$TBase_D = 0;
						$TBase_K = 0;
						foreach($_POST['dataACC'] as $dACC)
						{
							$Journal_DK 	= $dACC['Journal_DK'];
							if($Journal_DK == 'D')
							{
								$Base_D 	= $dACC['JournalD_Amount'];
								$TBase_D	= $TBase_D + $Base_D;
							}
							else
							{
								$Base_K 	= $dACC['JournalD_Amount'];
								$TBase_K	= $TBase_K + $Base_K;
							}

							// $CB_TOTAM		= $CB_TOTAM + $dACC['JournalD_Amount'];
							$CB_TOTAM		= $TBase_D - $TBase_K;
							// $CB_TOTAM		= $CB_TOTAM + $CB_TOTAM1;
						}
					}

					$TOT_PAYMENT 	= $CB_TOTAM + $CB_TOTAM_DISC;

					$inBankPay 	= array('CB_STAT' 		=> $CB_STAT,
										'CB_UPDATER' 	=> $DefEmp_ID,
										'CB_LASTUPD' 	=> date('Y-m-d H:i:s'));									
					$this->m_bank_payment->update($JournalH_Code, $inBankPay);
					
					// UPDATE INV STAT
					$TOT_AMOUNT		= 0;
					$TOT_AMOUNT_DP	= 0;
					$TOT_AMOUNT_POT	= 0;
					$TOT_AMOUNT_PPn	= 0;
					$CBD_DOCNOC		= "";
					$CBD_DOCNO 		= "";
					$CBD_DOCCODE 	= "";
					$REF_REFCODE 	= "";
					$REF_REFNOTES 	= "";

					if(isset($_POST['data']))
					{
						foreach($_POST['data'] as $d)
						{
							$PRJCODE		= $d['PRJCODE'];
							$CBD_DOCNO 		= $d['CBD_DOCNO'];
							$CBD_DOCNOC		= "$CBD_DOCNOC;$CBD_DOCNO";
							$CBD_DOCCODE 	= $d['CBD_DOCCODE'];
							$INV_AMOUNT 	= $d['INV_AMOUNT'];
							$INV_AMOUNT_PPN	= $d['INV_AMOUNT_PPN'];
							$INV_AMOUNT_PPH	= $d['INV_AMOUNT_PPH'];
							$CBD_AMOUNT 	= $d['CBD_AMOUNT'];
							$CBD_AMOUNT_DISC= $d['CBD_AMOUNT_DISC'];
							$AMOUNT_DP		= $d['AMOUNT_DP'];
							$TOT_AMOUNT_DP 	= $TOT_AMOUNT_DP + $AMOUNT_DP;
							
							$TOT_AMOUNT		= $TOT_AMOUNT + $CBD_AMOUNT;
							$TOT_AMOUNT_POT = $TOT_AMOUNT_POT + $CBD_AMOUNT_DISC;
							
							$RefType 		= "BP";
							if($CB_SOURCE == 'DP')
							{
								/*$updDP 	= array('DP_PAID' 		=> 2,
												'DP_AMOUNT_USED' 	=> $TOTAMount);	*/
								$updDP 	= array('DP_PAID' 			=> 2,
												'BP_CODE' 			=> $CB_CODE,
												'BP_DATE' 			=> $CB_DATE,
												'DP_AMOUN_TOT' 		=> $INV_AMOUNT,
												'BP_AMOUNT' 		=> $TOT_AMOUNT);									
								$this->m_bank_payment->updateDP($CBD_DOCNO, $updDP); 		// OK
							}
							elseif($CB_SOURCE == 'VCASH')
							{
								$updVC1 	= "UPDATE tbl_journalheader SET GEJ_STAT_VCASH = 1, Journal_AmountReal = Journal_AmountReal + $CBD_AMOUNT
												WHERE JournalH_Code = '$CBD_DOCNO'";
								$this->db->query($updVC1);
								$updVC2 	= "UPDATE tbl_journalheader_vcash SET GEJ_STAT_VCASH = 1, Journal_AmountReal = Journal_AmountReal + $CBD_AMOUNT,
													BP_CODE = '$CB_CODE', BP_DATE = '$CB_DATE', BP_AMOUNT = BP_AMOUNT + $CBD_AMOUNT
												WHERE JournalH_Code = '$CBD_DOCNO'";
								$this->db->query($updVC2);

								$updSTAT1 	= "UPDATE tbl_journalheader SET GEJ_STAT_VCASH = IF((Journal_Amount = Journal_AmountReal AND Journal_AmountReal > 0),6,GEJ_STAT_VCASH) WHERE JournalH_Code = '$CBD_DOCNO'";
								$this->db->query($updSTAT1);

								$updSTAT2 	= "UPDATE tbl_journalheader_vcash SET GEJ_STAT_VCASH = IF((Journal_Amount = Journal_AmountReal AND Journal_AmountReal > 0),6,GEJ_STAT_VCASH) WHERE JournalH_Code = '$CBD_DOCNO'";
								$this->db->query($updSTAT2);
							}
							elseif($CB_SOURCE == 'PD')
							{
								$RefType= "PD";
								// Update date 16-05-2022 by Iyan:
								$upJH1	= "UPDATE tbl_journalheader SET Journal_AmountTsf = Journal_AmountTsf+$CBD_AMOUNT
											WHERE JournalH_Code = '$CBD_DOCNO'";
								$this->db->query($upJH1);

								// Update date 16-05-2022 by Iyan:
								$upJH1	= "UPDATE tbl_journalheader_pd SET Journal_AmountTsf = Journal_AmountTsf+$CBD_AMOUNT, BP_CODE = '$CB_CODE', BP_DATE = '$CB_DATE'
											WHERE JournalH_Code = '$CBD_DOCNO'";
								$this->db->query($upJH1);

								$ManNOPD	= '';
								$sql 		= "SELECT Manual_No FROM tbl_journalheader_pd WHERE proj_Code = '$PRJCODE'
												AND JournalH_Code = '$CBD_DOCNO'";
								$result 	= $this->db->query($sql)->result();
								foreach($result as $row) :
									$ManNOPD = $row->Manual_No;
								endforeach;

								if($CB_NOTES == '') $CB_NOTES = "Transfer Pinjaman Dinas (PD) $ManNOPD";
							}
							elseif($CB_SOURCE == 'PPD')
							{
								$RefType= "PPD";
								// $upJH1	= "UPDATE tbl_journalheader SET PDPaid_Amount = PDPaid_Amount+$CBD_AMOUNT,
								// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
								// 			WHERE JournalH_Code = '$CBD_DOCNO'";

								// Update date 16-05-2022 by Iyan:
								$upJH1	= "UPDATE tbl_journalheader SET PDPaid_Amount = PDPaid_Amount+$CBD_AMOUNT,
												GEJ_STAT_PD = IF((Journal_Amount+PDPaid_Amount) = Journal_AmountReal, 6, 3)
											WHERE JournalH_Code = '$CBD_DOCNO'";
								$this->db->query($upJH1);

								// $upJH1	= "UPDATE tbl_journalheader_pd SET PDPaid_Amount = PDPaid_Amount+$CBD_AMOUNT,
								// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
								// 			WHERE JournalH_Code = '$CBD_DOCNO'";

								// Update date 16-05-2022 by Iyan:
								$upJH1	= "UPDATE tbl_journalheader_pd SET PDPaid_Amount = PDPaid_Amount+$CBD_AMOUNT,
												GEJ_STAT_PD = IF((Journal_Amount+PDPaid_Amount) = Journal_AmountReal, 6, 3),
												GEJ_STAT = IF((Journal_Amount+PDPaid_Amount) = Journal_AmountReal, 6, 3),
												STATDESC = IF(GEJ_STAT = 6 OR GEJ_STAT_PD = 6, 'Closed', 'Approved'),
												STATCOL = IF(GEJ_STAT = 6 OR GEJ_STAT_PD = 6, 'info', 'success')
											WHERE JournalH_Code = '$CBD_DOCNO'";
								$this->db->query($upJH1);

								$ManNOPD	= '';
								$sql 		= "SELECT Manual_No FROM tbl_journalheader_pd WHERE proj_Code = '$PRJCODE'
												AND JournalH_Code = '$CBD_DOCNO'";
								$result 	= $this->db->query($sql)->result();
								foreach($result as $row) :
									$ManNOPD = $row->Manual_No;
								endforeach;

								if($CB_NOTES == '') $CB_NOTES = "Transfer kurang bayar PD $ManNOPD";
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

								$s_01a		= "UPDATE tbl_pinv_header SET BP_CODE = '$CB_CODE', BP_DATE = '$CB_DATE' WHERE INV_NUM = '$CBD_DOCNO'";
								$this->db->query($s_01a);

								// START : UPDATE PD REALISASI JIKA ADA PEMBAYARAN OLEH PD
									$REM_PD_Amount = 0;
									$s_RPD 	= "SELECT JournalH_Code, Invoice_Code, Journal_Amount FROM tbl_journalheader_pd_rinv WHERE Invoice_No = '$CBD_DOCNO'";
									$r_RPD 	= $this->db->query($s_RPD);
									if($r_RPD->num_rows() > 0)
									{
										foreach($r_RPD->result() AS $rPD):
											$JRN_CODE 		= $rPD->JournalH_Code;
											$InvoiceCode 	= $rPD->Invoice_Code;
											$JournalAmount 	= $rPD->Journal_Amount;
											$REF_PINV 		= $InvoiceCode." ($CB_CODE)";

											$s_02	= "UPDATE tbl_journalheader_pd SET Journal_AmountReal = Journal_AmountReal+$JournalAmount, REF_PINV = '$REF_PINV',
															SPLCODE = '$CB_PAYFOR', SPLDESC = '$SPLDESC',
															JournalH_Desc3 = 'Penyelesaian PD Melalui Faktur No. $CBD_DOCCODE'
														WHERE JournalH_Code = '$JRN_CODE' AND proj_Code = '$PRJCODE'";
											$this->db->query($s_02);
										endforeach;
									}
								// END : UPDATE PD REALISASI JIKA ADA PEMBAYARAN OLEH PD
							}

							// UPDATE TTK/LPM/OPN
								$s_02		= "UPDATE tbl_ttk_header A, tbl_pinv_header B SET A.BP_CODE = '$CB_CODE', A.BP_DATE = '$CB_DATE' 
												WHERE A.PRJCODE = B.PRJCODE AND A.INV_CODE = B.INV_CODE AND B.INV_CODE = '$CBD_DOCCODE'";
								$this->db->query($s_02);

								$s_03		= "UPDATE tbl_ir_header A, tbl_pinv_header B SET A.BP_CODE = '$CB_CODE', A.BP_DATE = '$CB_DATE' 
												WHERE A.PRJCODE = B.PRJCODE AND A.INV_CODE = B.INV_CODE AND B.INV_CODE = '$CBD_DOCCODE'";
								$this->db->query($s_03);

								$s_03		= "UPDATE tbl_opn_header A, tbl_pinv_header B SET A.BP_CODE = '$CB_CODE', A.BP_DATE = '$CB_DATE' 
												WHERE A.PRJCODE = B.PRJCODE AND A.INV_CODE = B.INV_CODE AND B.INV_CODE = '$CBD_DOCCODE'";
								$this->db->query($s_03);
						}
					}

					if(isset($_POST['dataACC']))
					{
						$RefType 	= "BP-ACC";
						$TBase_D 	= 0;
						$TBase_K 	= 0;
						foreach($_POST['dataACC'] as $d)
						{
							$Journal_DK 	= $d['Journal_DK'];
							if($Journal_DK == 'D')
							{
								$Base_D 	= $d['JournalD_Amount'];
								$TBase_D	= $TBase_D + $Base_D;
							}
							else
							{
								$Base_K 	= $d['JournalD_Amount'];
								$TBase_K	= $TBase_K + $Base_K;
							}

							// $CBD_AMOUNT 	= $d['JournalD_Amount'];
							// $TOT_AMOUNT		= $TOT_AMOUNT + $CBD_AMOUNT;
							$TOT_AMOUNT		= $TBase_D - $TBase_K;
							$TOT_AMOUNT_POT = $TOT_AMOUNT_POT + 0;
						}
					}

					// START : JOURNAL HEADER
						$JournalH_Code	= $JournalH_Code;
						$JournalType	= 'BP';
						$JournalH_Date	= $CB_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $CBD_DOCNOC;
						$LastUpdate		= $CB_CREATED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $CBD_DOCNOC;
						$RefType		= $RefType;
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
											'PRJCODE'			=> $PRJCODE,
											'Manual_No'			=> $CB_CODE);
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
						$Refer_Number	= $CBD_DOCNOC;
						$RefType		= $RefType;
						$JSource		= 'PINV';
						$PRJCODE		= $PRJCODE;
						
						$ITM_CODE 		= "";
						$ACC_ID 		= $LA_ACCID;					// AKUN KAS BANK
						$ITM_UNIT 		= '';
						$ITM_QTY 		= 1;
						$ITM_PRICE 		= $CB_TOTAM;
						$ITM_DISC 		= $CB_TOTAM_DISC;
						$TAXCODE1 		= "";
						$TAXCODE2       = "";
						$TAXPRICE1		= 0;
						
						if($CB_SOURCE == 'DP')
						{
							$TRANS_CATEG 	= "BPDP~$SPLCAT";
						}
						elseif($CB_SOURCE == 'PD')
						{
							$TRANS_CATEG 	= "BP-PD~$SPLCAT";
						}
						elseif($CB_SOURCE == 'PPD')
						{
							$TRANS_CATEG 	= "BP-PPD~$SPLCAT";
						}
						else
						{
							$TRANS_CATEG 	= "BP-NEW~$SPLCAT";
						}

						// BUATKAN PROSEDUR UNTUK PEMBAYARAN PER INVOICE
						$TOT_CBDAMOUNT 		= 0;
						$INV_AMOUNT_PPN 	= 0;
						$INV_AMOUNT_PPH 	= 0;
						$TOT_CBDAMOUNT_DISC = 0;
						$PPn_PAYSTAT 		= 0;
						$PPh_PAYSTAT 		= 0;

						if(isset($_POST['data']))
						{
							foreach($_POST['data'] as $d)
							{
								$PRJCODE		= $d['PRJCODE'];
								$CBD_DOCNO		= $d['CBD_DOCNO'];			// INV NUMBER
								$CBD_DOCCODE 	= $d['CBD_DOCCODE']; 		// INV CODE
								$INV_AMOUNT		= $d['INV_AMOUNT'];
								$CBD_AMOUNT		= $d['CBD_AMOUNT'];
								$AMOUNT_DP		= $d['AMOUNT_DP'];			// JIKA MEMILIKI DP KE SUPPLIER
								$INV_AMOUNT_PPN	= $d['INV_AMOUNT_PPN'];
								$INV_AMOUNT_PPH	= $d['INV_AMOUNT_PPH'];
								$CBD_AMOUNT_DISC= $d['CBD_AMOUNT_DISC'];
								$SRC_NUM 		= $d['SRC_NUM'];

								/*$TPPN_PAID 		= 0;
								$s_PPNPAID		= "SELECT SUM(Project_Kredit_Tax) AS TOT_PPN_PAID FROM tbl_journaldetail WHERE JournalH_Code = '$CBD_DOCNO'";
								$r_PPNPAID		= $this->db->query($s_PPNPAID)->result();
								foreach($r_PPNPAID as $rw_PPNPAID) :
									$TPPN_PAID 	= $rw_PPNPAID->TOT_PPN_PAID;
									// SISA PPN YANG HARUS DIBAYAR YANG AKAN MENJADI NILAI TAMBAH DI SISIH DEBET
								endforeach;*/

								$TPPH_AMN 		= 0;
								$s_PPPAID		= "SELECT IFNULL(SUM(ITM_AMOUNT_PPH),0) AS TPPH_AMN FROM tbl_pinv_detail WHERE INV_NUM = '$CBD_DOCNO'";
								$r_PPPAID		= $this->db->query($s_PPPAID)->result();
								foreach($r_PPPAID as $rw_PPPAID) :
									$TPPH_AMN 	= $rw_PPPAID->TPPH_AMN;
								endforeach;

								$TPPH_PAID 		= 0;
								$s_PPPAID		= "SELECT IFNULL(SUM(JournalD_Kredit),0) AS TPPH_PAID FROM tbl_journaldetail WHERE JournalH_Code IN (SELECT DISTINCT CB_NUM FROM tbl_bp_detail WHERE CBD_DOCNO = '$CBD_DOCNO' AND CB_STAT NOT IN (5,9) AND CB_NUM NOT IN ('$CB_NUM')) AND isTax = 1";
								$r_PPPAID		= $this->db->query($s_PPPAID)->result();
								foreach($r_PPPAID as $rw_PPPAID) :
									$TPPH_PAID 	= $rw_PPPAID->TPPH_PAID;
								endforeach;
								$INV_AMOUNT_PPH = $TPPH_AMN-$TPPH_PAID;

								$TOT_CBDAMOUNT 		= $TOT_CBDAMOUNT + $CBD_AMOUNT;
								$TOT_CBDAMOUNT_DISC	= $TOT_CBDAMOUNT_DISC + $CBD_AMOUNT_DISC;

								if($INV_AMOUNT_PPN > 0)
									$PPn_PAYSTAT = 1;

								if($INV_AMOUNT_PPH > 0)
									$PPh_PAYSTAT = 1;

								$DP_NUM 		= "";
								if($AMOUNT_DP > 0)
								{
									$s_INVCAT	= "UPDATE tbl_dp_header SET DP_AMOUNT_USED = DP_AMOUNT_USED+$AMOUNT_DP WHERE DP_REFNUM = '$SRC_NUM'";
									$this->db->query($s_INVCAT);

									$s_02		= "SELECT DP_NUM FROM tbl_dp_header WHERE DP_REFNUM = '$SRC_NUM'";
									$r_02		= $this->db->query($s_02)->result();
									foreach($r_02 as $rw_02) :
										$DP_NUM = $rw_02->DP_NUM;
									endforeach;
								}
								
								$CB_CATEG		= $d['CB_CATEG'];
								$CBD_DESC 		= htmlentities($d['CBD_DESC'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
								
								if($CB_CATEG == "OPN-RET")
								{
									$JournalType	= 'BP-RET';
									$RefType		= 'BP-RET';
									$TRANS_CATEG	= "BP-RET~$SPLCAT";
								}
								
								$PPH_PERC 		= 0;
								$AccId_D 		= "";
								$DP_AMOUNT      = 0;

								if($CB_SOURCE == 'VCASH')
								{
									$s_00 		= "UPDATE tbl_journaldetail_vcash A, tbl_journalheader_vcash B SET A.Manual_No = B.Manual_No
													WHERE A.JournalH_Code = B.JournalH_Code AND A.JournalH_Code = '$CBD_DOCNO'";
									$this->db->query($s_00);

									// CARI AKUN JOURNAL SISI KREDIT VOUCHER YANG DIPILIH
									$s_ACCID 	= "SELECT Acc_Id, Manual_No, Other_Desc FROM tbl_journaldetail_vcash WHERE JournalH_Code = '$CBD_DOCNO'
													AND Journal_DK = 'K'";
									$r_ACCID 	= $this->db->query($s_ACCID)->result();
									foreach($r_ACCID as $rw_ACCID):
										$AccId_D 		= $rw_ACCID->Acc_Id;
										$REF_REFCODE 	= $rw_ACCID->Manual_No;
										$REF_REFNOTES 	= htmlentities($rw_ACCID->Other_Desc, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
									endforeach;

									$s_01 		= "SELECT PPN_Code, PPH_Code, PPH_Amount FROM tbl_journaldetail_vcash WHERE JournalH_Code = '$CBD_DOCNO' AND JOBCODEID != ''";
									$r_01 		= $this->db->query($s_01);
									if($r_01->num_rows() > 0)
									{
										foreach($r_01->result() as $rw_01):
											$TAXCODE1 		= $rw_01->PPN_Code;
											$TAXCODE2 		= $rw_01->PPH_Code;
											$INV_AMOUNT_PPH = $rw_01->PPH_Amount;

											$s_01a 		= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE2'";
											$r_01a		= $this->db->query($s_01a);
											if($r_01a->num_rows() > 0)
											{
												foreach($r_01a->result() as $rw_01a):
													$TAXLA_LINKIN = $rw_01a->TAXLA_LINKIN;
												endforeach;

												$isPPhFinal = 0;
												$s_01b 	= "SELECT isPPhFinal FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN'";
												$r_01b 	= $this->db->query($s_01b);
												if($r_01b->num_rows() > 0)
												{
													foreach($r_01b->result() as $rw_01b):
														$isPPhFinal = $rw_01b->isPPhFinal;
													endforeach;

													if($isPPhFinal == 1) $PPh_PAYSTAT = 1;
												}
											}

											$s_01c		= "UPDATE tbl_journaldetail_vcash SET PPn_PAYSTAT = $PPn_PAYSTAT, PPhFin_PAYSTAT = $PPh_PAYSTAT 
															WHERE JournalH_Code = '$CBD_DOCNO' AND PPN_Code = '$TAXCODE1' AND PPH_Code = '$TAXCODE2'
															AND PPn_PAYSTAT = 0 AND PPhFin_PAYSTAT = 0";
											// $this->db->query($s_01c); di update setelah terbentuk jurnal
										endforeach;
									}

								}
								elseif($CB_SOURCE == 'PD')
								{
									$s_00 		= "UPDATE tbl_journaldetail_pd A, tbl_journalheader_pd B SET A.Manual_No = B.Manual_No
													WHERE A.JournalH_Code = B.JournalH_Code AND A.JournalH_Code = '$CBD_DOCNO'";
									$this->db->query($s_00);
									
									// CARI AKUN JOURNAL SISI KREDIT PD YANG DIPILIH
									$s_ACCID 	= "SELECT DISTINCT Acc_Id, Manual_No, Other_Desc FROM tbl_journaldetail_pd WHERE proj_Code = '$PRJCODE'
													AND JournalH_Code = '$CBD_DOCNO' AND ISPERSL_REALIZ = 0 AND Journal_DK = 'K'";
									$r_ACCID 	= $this->db->query($s_ACCID)->result();
									foreach($r_ACCID as $rw_ACCID):
										$AccId_D 		= $rw_ACCID->Acc_Id;
										$REF_REFCODE 	= $rw_ACCID->Manual_No;
										$REF_REFNOTES 	= htmlentities($rw_ACCID->Other_Desc, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
									endforeach;
								}
								elseif($CB_SOURCE == 'PPD')
								{
									$s_00 		= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.Manual_No = B.Manual_No
													WHERE A.JournalH_Code = B.JournalH_Code AND A.JournalH_Code = '$CBD_DOCNO'";
									$this->db->query($s_00);
									
									// CARI AKUN JOURNAL SISI KREDIT PPD YANG DIPILIH
									$s_ACCID 	= "SELECT DISTINCT Acc_Id, Manual_No, Other_Desc FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE'
													AND JournalH_Code = '$CBD_DOCNO' AND ISPERSL_REALIZ = 1 AND Journal_DK = 'K'";
									$r_ACCID 	= $this->db->query($s_ACCID)->result();
									foreach($r_ACCID as $rw_ACCID):
										$AccId_D 		= $rw_ACCID->Acc_Id;
										$REF_REFCODE 	= $rw_ACCID->Manual_No;
										$REF_REFNOTES 	= htmlentities($rw_ACCID->Other_Desc, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
									endforeach;

									$s_01 		= "SELECT PPN_Code, PPH_Code, PPH_Amount FROM tbl_journaldetail_pd WHERE JournalH_Code = '$CBD_DOCNO' AND JOBCODEID != ''";
									$r_01 		= $this->db->query($s_01);
									if($r_01->num_rows() > 0)
									{
										foreach($r_01->result() as $rw_01):
											$TAXCODE1 		= $rw_01->PPN_Code;
											$TAXCODE2 		= $rw_01->PPH_Code;
											$INV_AMOUNT_PPH = $rw_01->PPH_Amount;

											$s_01a 		= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE2'";
											$r_01a		= $this->db->query($s_01a);
											if($r_01a->num_rows() > 0)
											{
												foreach($r_01a->result() as $rw_01a):
													$TAXLA_LINKIN = $rw_01a->TAXLA_LINKIN;
												endforeach;

												$isPPhFinal = 0;
												$s_01b 	= "SELECT isPPhFinal FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN'";
												$r_01b 	= $this->db->query($s_01b);
												if($r_01b->num_rows() > 0)
												{
													foreach($r_01b->result() as $rw_01b):
														$isPPhFinal = $rw_01b->isPPhFinal;
													endforeach;

													if($isPPhFinal == 1) $PPh_PAYSTAT = 1;
												}
											}

											$s_01c		= "UPDATE tbl_journaldetail_pd SET PPn_PAYSTAT = $PPn_PAYSTAT, PPhFin_PAYSTAT = $PPh_PAYSTAT 
															WHERE JournalH_Code = '$CBD_DOCNO' AND PPN_Code = '$TAXCODE1' AND PPH_Code = '$TAXCODE2'
															AND PPn_PAYSTAT = 0 AND PPhFin_PAYSTAT = 0";
											// $this->db->query($s_01c); // di update setelah terbentuk jurnal
										endforeach;
									}
								}
								elseif($CB_SOURCE == 'DP')
								{
									// CARI AKUN PPN dan PPH pada PO/SPK yang berelasi ke -> TTK -> DP
										$s_ACCID 	= "SELECT DISTINCT A.TAXCODE1, A.TAXCODE2 FROM tbl_wo_detail A
														WHERE A.WO_CODE IN (SELECT B.DP_REFCODE FROM tbl_dp_header B WHERE B.DP_CODE = '$CBD_DOCCODE')
															AND A.PRJCODE = '$PRJCODE'
														UNION
														SELECT DISTINCT A.TAXCODE1, A.TAXCODE2 FROM tbl_po_detail A
														WHERE A.PO_CODE IN (SELECT B.DP_REFCODE FROM tbl_dp_header B WHERE B.DP_CODE = '$CBD_DOCCODE')
															AND A.PRJCODE = '$PRJCODE'";
										$r_ACCID 	= $this->db->query($s_ACCID)->result();
										foreach($r_ACCID as $rw_ACCID):
											$TAXCODE1 	= $rw_ACCID->TAXCODE1;
											$TAXCODE2 	= $rw_ACCID->TAXCODE2;
										endforeach;

									$s_DP	= "SELECT DP_AMOUNT, DP_AMOUNT_PPN, DP_AMOUNT_PPH, DP_AMOUN_TOT, DP_REFCODE, DP_NOTES, TTK_NUM
												FROM tbl_dp_header WHERE DP_NUM = '$CBD_DOCNO'";
									$r_DP = $this->db->query($s_DP)->result();					
									foreach($r_DP as $rw_DP):
										$TTK_NUM 		= $rw_DP->TTK_NUM;
										$TOT_PPN 		= 0;
										$s_TTK			= "SELECT IFNULL(SUM(TTKT_TAXAMOUNT), 0) AS TOT_PPN FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM'";
										$r_TTK 			= $this->db->query($s_TTK)->result();					
										foreach($r_TTK as $rw_TTK):
											$TOT_PPN	= $rw_TTK->TOT_PPN;
										endforeach;

										$DP_AMOUNT		= $rw_DP->DP_AMOUNT;
										$PPN_DP			= $TOT_PPN;
										$PPH_DP			= $rw_DP->DP_AMOUNT_PPH;
										$DP_AMOUN_TOT	= $rw_DP->DP_AMOUN_TOT;
										//$INV_AMOUNT_PPN = $PPN_DP * $CBD_AMOUNT / $DP_AMOUN_TOT;
										//$INV_AMOUNT_PPH = $PPH_DP * $CBD_AMOUNT / $DP_AMOUN_TOT;
										$INV_AMOUNT_PPN = $PPN_DP;
										$INV_AMOUNT_PPH = $PPH_DP;
										$REF_REFCODE 	= $rw_DP->DP_REFCODE;
										$REF_REFNOTES 	= $rw_DP->DP_NOTES;
									endforeach;

									$s_01b		= "UPDATE tbl_dp_header SET PPn_PAYSTAT = $PPn_PAYSTAT, PPhFin_PAYSTAT = $PPh_PAYSTAT WHERE DP_NUM = '$CBD_DOCNO'";
									// $this->db->query($s_01b); // di update setelah terbentuk jurnal
								}
								elseif($CB_SOURCE == 'PINV')
								{
									$REF_REFCODEX 		= "-";
									$REF_REFCODEY 		= "-";
									$noRef 				= 0;
									$s_TTK 	= "SELECT A.TTK_REF1_NUM, A.TTK_REF1_CODE, A.TTK_REF2_NUM, A.TTK_REF2_CODE, A.TTK_DESC, B.TTK_CATEG
												FROM tbl_ttk_detail A INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM
												WHERE A.TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail WHERE INV_NUM = '$CBD_DOCNO')";
									$r_TTK 	= $this->db->query($s_TTK);
									if($r_TTK->num_rows() > 0)
									{
										foreach($r_TTK->result() as $rw_TTK):
											$noRef 			= $noRef+1;
											$REF_REFNUM1 	= $rw_TTK->TTK_REF1_NUM;	// LPM/OPN
											$REF_REFCODE1 	= $rw_TTK->TTK_REF1_CODE;	// LPM/OPN
											$REF_REFNUM2 	= $rw_TTK->TTK_REF2_NUM;	// OP/SPK
											$REF_REFCODE2 	= $rw_TTK->TTK_REF2_CODE;	// OP/SPK
											$REF_REFNOTES 	= htmlentities($rw_TTK->TTK_DESC, ENT_QUOTES | ENT_SUBSTITUTE);
											$REF_REFCATEG 	= $rw_TTK->TTK_CATEG;
											if($noRef == 1)
											{
												$REF_REFCODEX 	= "$REF_REFCODE1";
												$REF_REFCODEY 	= "$REF_REFCODE2";
											}
											else
											{
												$REF_REFCODEX 	= "$REF_REFCODEX/$REF_REFCODE1";
												$REF_REFCODEY 	= "$REF_REFCODEY/$REF_REFCODE2";
											}
	
											if($REF_REFCATEG == 'OPN')
											{
												$s_PPH 	= "SELECT DISTINCT TAXCODE2 FROM tbl_wo_detail WHERE WO_NUM = '$REF_REFNUM2' AND TAXCODE2 != ''";
												$r_PPH 	= $this->db->query($s_PPH)->result();
												foreach($r_PPH as $rw_PPH):
													$TAXCODE2 	= $rw_PPH->TAXCODE2;
												endforeach;
											}
											else
											{
												$s_PPH 	= "SELECT DISTINCT TAXCODE2 FROM tbl_po_detail WHERE PO_NUM = '$REF_REFNUM2' AND TAXCODE2 != ''";
												$r_PPH 	= $this->db->query($s_PPH)->result();
												foreach($r_PPH as $rw_PPH):
													$TAXCODE2 	= $rw_PPH->TAXCODE2;
												endforeach;
											}
										endforeach;
									}
									else
									{
										$s_PPH	= "SELECT DISTINCT TAXCODE_PPH FROM tbl_pinv_detail WHERE INV_NUM = '$CBD_DOCNO' AND TAXCODE_PPH != ''";
										$r_PPH 	= $this->db->query($s_PPH)->result();					
										foreach($r_PPH as $rw_PPH):
											$TAXCODE2	= $rw_PPH->TAXCODE_PPH;
										endforeach;
									}

									$REF_REFCODE 		= "LPM/OPN : $REF_REFCODEX. OP/SPK : $REF_REFCODEY";

									$s_01b		= "UPDATE tbl_pinv_detail SET PPn_PAYSTAT = $PPn_PAYSTAT, PPhFin_PAYSTAT = $PPh_PAYSTAT WHERE INV_NUM = '$CBD_DOCNO'";
									// $this->db->query($s_01b); // di update setelah terbentuk jurnal
								}

								if($AH_NOTES == '')
									$AH_NOTES 	= "Pembayaran ke $SPLDESC. $REF_REFNOTES. Voc. : $CBD_DOCCODE. $REF_REFCODE";

								if($CBD_DESC == '')
									$CBD_DESC 	= "$SPLDESC. $REF_REFNOTES. Voc. : $CBD_DOCCODE. $REF_REFCODE";

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
													'ACCID_D'			=> $AccId_D,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $DP_AMOUNT,
													'ITM_DISC' 			=> 0,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $INV_AMOUNT_PPN,
													'TAXCODE2' 			=> $TAXCODE2,
													'TAXPRICE2' 		=> $INV_AMOUNT_PPH,
													'PPhTax' 			=> 0,
													'PPhAmount' 		=> 0,
													'DiscAmount' 		=> $CBD_AMOUNT_DISC,
													'DPAmount' 			=> 0,
													'InvAmount_PPn' 	=> 0,
													'InvAmount_PPh' 	=> 0,
													'InvAmount_Ret' 	=> 0,
													'InvAmount_Disc' 	=> 0,
													'Manual_No' 		=> $CB_CODE,
													'Ref_Number' 		=> '',
													'Faktur_No' 		=> $CBD_DOCNO,
													'Faktur_Code' 		=> $CBD_DOCCODE,
													'Other_Desc'		=> $CBD_DESC,
													'CB_NOTES' 			=> $AH_NOTES,
													'SPLCAT'			=> $SPLCAT,
													'INV_AMOUNT'		=> $INV_AMOUNT,
													'CBD_AMOUNT'		=> $CBD_AMOUNT,
													'AMOUNT_POT_DP'		=> $AMOUNT_DP,
													'DP_NUM'			=> $DP_NUM,
													'CBD_AMOUNT_DISC'	=> $CBD_AMOUNT_DISC,
													'TOT_CBDAMOUNT'		=> $TOT_CBDAMOUNT,
													'TOT_CBDAMOUNT_DISC'=> $TOT_CBDAMOUNT_DISC,
													'CB_SOURCE'			=> $CB_SOURCE,
													'SPLCODE'			=> $SPLCODE,
													'SPLDESC'			=> $SPLDESC);
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
										else if($CB_CATEG == 'PPD' || $CB_CATEG == 'PD')
										{
											// ... nothing
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
						}
						
						if(isset($_POST['dataACC']))
						{
							if($CBD_DOCNO == "") $CBD_DOCNO = $CB_CODE;
							$TBase_D = 0;
							$TBase_K = 0;
							foreach($_POST['dataACC'] as $d)
							{
								$AccId_D		= $d['Acc_Id'];
								$CBD_AMOUNT		= $d['JournalD_Amount'];
								$Other_Desc		= htmlentities($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
								$Acc_Id 		= $d['Acc_Id'];
								$Acc_Name 		= $d['JournalD_Desc'];
								$Base_Debet		= $d['JournalD_Amount'];
								$Journal_DK 	= $d['Journal_DK'];
								$Ref_Number		= $d['Ref_Number'];
								$Other_Desc		= htmlentities($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);

								$Journal_DK 	= $d['Journal_DK'];
								if($Journal_DK == 'D')
								{
									$Base_D 	= $d['JournalD_Amount'];
									$TBase_D	= $TBase_D + $Base_D;
								}
								else
								{
									$Base_K 	= $d['JournalD_Amount'];
									$TBase_K	= $TBase_K + $Base_K;
								}

								// $TOT_CBDAMOUNT 		= $TOT_CBDAMOUNT + $CBD_AMOUNT;
								$TOT_CBDAMOUNT 		= $TBase_D - $TBase_K;
								$TOT_CBDAMOUNT_DISC	= $TOT_CBDAMOUNT_DISC + 0;

								if($CBD_AMOUNT > 0)
								{
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
														'TRANS_CATEG' 		=> "BP-ACC~$SPLCAT",		// BP = BANK PAYMENT
														'ITM_CODE' 			=> $ITM_CODE,
														'ACC_ID' 			=> $CB_ACCID,
														'ACCID_D'			=> $AccId_D,
														'ITM_UNIT' 			=> $ITM_UNIT,
														'ITM_QTY' 			=> $ITM_QTY,
														'ITM_PRICE' 		=> $CBD_AMOUNT,
														'ITM_DISC' 			=> 0,
														'TAXCODE1' 			=> $TAXCODE1,
														'TAXPRICE1' 		=> $TAXPRICE1,
														'PPhTax' 			=> 0,
														'PPhAmount' 		=> 0,
														'DiscAmount' 		=> 0,
														'DPAmount' 			=> 0,
														'InvAmount_PPn' 	=> 0,
														'InvAmount_PPh' 	=> 0,
														'InvAmount_Ret' 	=> 0,
														'InvAmount_Disc' 	=> 0,
														'Manual_No' 		=> $CB_CODE,
														'Ref_Number' 		=> '',
														'Faktur_No' 		=> $CBD_DOCNO,
														'Other_Desc'		=> $Other_Desc,
														'CB_NOTES' 			=> $AH_NOTES,
														'SPLCAT'			=> $SPLCAT,
														'INV_AMOUNT'		=> 0,
														'CBD_AMOUNT'		=> $CBD_AMOUNT,
														'CBD_AMOUNT_DISC'	=> 0,
														'TOT_CBDAMOUNT'		=> $TOT_CBDAMOUNT,
														'TOT_CBDAMOUNT_DISC'=> 0,
														'CB_SOURCE'			=> $CB_SOURCE,
														'Journal_DK' 		=> $Journal_DK);
									$this->m_journal->createJournalD($JournalH_Code, $parameters);
								}
								
								// UNTUK KEPERLUAN FINANCIAL TRACK
								// NILAI HUTANG SEBENARNYA = NILAI INVOICE (SBL DIPOTONG) - POTONGAN + PPN;
								// SAMA DENGAN FM_TOTVAL = (INV_VAL + PPN - POT - RET - PPH) + RET + PPH;
								//$FM_TOTVAL	= $Amount - $DiscAmount - $DPAmount;
								$FM_TOTVAL		= $CBD_AMOUNT + 0;
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
									//$this->m_updash->finTrack($paramFT);
								// END : TRACK FINANCIAL TRACK
							}
						}
						
						$PRJ_isHO			= 0;
						$proj_CodeHO		= $PRJCODE;
						$sqlPRJHO 			= "SELECT isHO, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
						$resPRJHO			= $this->db->query($sqlPRJHO)->result();
						foreach($resPRJHO as $rowPRJHO):
							$PRJ_isHO		= $rowPRJHO->isHO;
							$proj_CodeHO	= $rowPRJHO->PRJCODE_HO;
						endforeach;

						if($CB_PAYTYPE == 'CB')
						{
							// ------------------------------- K R E D I T KAS / BANK (Total Nilai Bayar Hut. Supplier => tgl. 2022-02-18)
								$proj_Code 		= $PRJCODE;
								$Manual_No 		= $CB_CODE;
								$accYr			= date('Y', strtotime($JournalH_Date));
								$ACC_ID 		= $CB_ACCID;
								$transacValue1	= $TOT_CBDAMOUNT;
								$DiscAmount 	= $TOT_CBDAMOUNT_DISC;


								$ACC_ID_K	= $ACC_ID;				// BANK ACCOUNT SELECTED
								$ACC_NUM	= $ACC_ID;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code'
												AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								if($CB_NOTES == '')
									$JRN_NOTES 	= "Pembayaran ke $SPLDESC. $REF_REFNOTES ($REF_REFCODE)";
								else
									$JRN_NOTES 	= "$SPLDESC. $CB_NOTES ($REF_REFCODE)";

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Acc_Id = '$ACC_ID_K'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
										// START : Save Journal Detail - Kredit
											$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalType, JournalD_Kredit,
															Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc, Manual_No, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_ID_K', '$proj_Code', 'IDR','BP', $transacValue1, $transacValue1,
															$transacValue1, 'Default', 1, 0, 'K', '$JRN_NOTES', '$Manual_No', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDK);
										// END : Save Journal Detail - Kredit
									}
									else
									{
										/*$sqlUpdCOAK	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue1,
												Base_Kredit = Base_Kredit+$transacValue1, COA_Kredit = COA_Kredit+$transacValue1
											WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_ID_K'";*/
										$sqlUpdCOAK	= "UPDATE tbl_journaldetail SET JournalD_Kredit = $transacValue1,
												Base_Kredit = $transacValue1, COA_Kredit = $transacValue1
											WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_ID_K'";
										$this->db->query($sqlUpdCOAK);
									}
												
								// START : Update to COA - Kredit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K' LIMIT 1";
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
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue1, 
																Base_Kredit2 = Base_Kredit2+$transacValue1, BaseK_$accYr = BaseK_$accYr+$transacValue1
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Kredit

								// ------------------------------- K R E D I T POTONGAN PEMBAYARAN -------------------------------

									// START : 12-12-2018
									if($DiscAmount > 0)
									{
										$ACC_NUM	= "2111XX"; // Hutang Supplier/Sewa Belum Difakturkan
										$sqlCL_K	= "tglobalsetting";
										$resCL_K	= $this->db->count_all($sqlCL_K);
										if($resCL_K > 0)
										{
											$sqlL_K	= "SELECT ACC_ID_POT FROM tglobalsetting";
											$resL_K = $this->db->query($sqlL_K)->result();					
											foreach($resL_K as $rowL_K):
												$ACC_NUM	= $rowL_K->ACC_ID_POT;
												$Acc_Name 	= "-";
												$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
												$resNm		= $this->db->query($sqlNm)->result();
												foreach($resNm as $rowNm):
													$Acc_Name	= $rowNm->Account_NameId;
												endforeach;
												
												// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
													$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
																		AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
													$resCGEJ	= $this->db->count_all($sqlCGEJ);
													
													if($resCGEJ == 0)
													{
															$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
																			Currency_id, JournalD_Kredit, Base_Kredit, 
																			COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc, Manual_No, Acc_Name, proj_CodeHO)
																		VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $DiscAmount,
																		$DiscAmount, $DiscAmount, 'Default', 1, 0, 'K', '$JRN_NOTES (Disc. Payment)', '$Manual_No', '$Acc_Name',
																		'$proj_CodeHO')";
															$this->db->query($sqlGEJDD);
													}
													else
													{
															$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																				JournalD_Kredit = JournalD_Kredit+$DiscAmount
																				Base_Kredit = Base_Kredit+$DiscAmount, 
																				COA_Kredit = COA_Kredit+$DiscAmount
																			WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																				AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
															$this->db->query($sqlUpdCOAD);
													}
													
												// START : Update to COA - Debit
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
															$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$DiscAmount, 
																				Base_Kredit2 = Base_Kredit2+$DiscAmount, BaseK_$accYr = BaseK_$accYr+$DiscAmount
																			WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
															$this->db->query($sqlUpdCOA);
														}
													}
												// END : Update to COA - Debit
											endforeach;
										}
									}
									// END : 12-12-2018
								// ------------------------------- END K R E D I T POTONGAN PEMBAYARAN -------------------------------
							// ------------------------------- END K R E D I T KAS / BANK ------------------------------- */
						}
						else
						{
							if(isset($_POST['dataPD']))
							{
								$TOT_PDPAYMENT 		= 0;
								foreach($_POST['dataPD'] as $d)
								{
									$INV_NUM 		= $d['INV_NUM'];
									$INV_CODE 		= $d['INV_CODE'];
									$INV_DATE 		= $d['INV_DATE'];
									$PD_NUM 		= $d['PD_NUM'];
									$PD_CODE 		= $d['PD_CODE'];
									$PD_DESC 		= $d['PD_DESC'];
									$PD_REFCODE 	= $d['PD_REFCODE'];
									$PD_AMOUNT 		= $d['PD_AMOUNT'];
									$PD_PAYMENT 	= $d['PD_PAYMENT'];
									$PD_NOTES 		= $d['PD_NOTES'];

									$TOT_PDPAYMENT	= $TOT_PDPAYMENT + $PD_PAYMENT;

									$PD_DATE 		= "";
									$s_PD 			= "SELECT Journalh_Date FROM tbl_journalheader_pd
														WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$PD_NUM'";
									$r_PD 			= $this->db->query($s_PD)->result();
									foreach($r_PD as $rw_PD):
										$PD_DATE 	= $rw_PD->Journalh_Date;
									endforeach;

									$parameters = array('JournalH_Code' 	=> $JournalH_Code,
														'JournalType'		=> $JournalType,
														'JournalH_Date' 	=> $JournalH_Date,
														'Company_ID' 		=> $Company_ID,
														'Currency_ID' 		=> $Currency_ID,
														'Source'			=> 'PD',
														'Emp_ID'			=> $DefEmp_ID,
														'LastUpdate'		=> $LastUpdate,	
														'KursAmount_tobase'	=> 1,
														'WHCODE'			=> $WH_CODE,
														'Reference_Number'	=> "",
														'RefType'			=> $RefType,
														'PRJCODE'			=> $PRJCODE,
														'JSource'			=> $JSource,
														'TRANS_CATEG' 		=> "BP-BYPD~$SPLCAT",
														'ITM_CODE' 			=> "",
														'ACC_ID' 			=> "",
														'ACCID_K'			=> "",
														'ITM_UNIT' 			=> "",
														'ITM_QTY' 			=> 1,
														'ITM_PRICE' 		=> $PD_PAYMENT,
														'ITM_DISC' 			=> 0,
														'TAXCODE1' 			=> "",
														'TAXPRICE1' 		=> "",
														'PPhTax' 			=> 0,
														'PPhAmount' 		=> 0,
														'DiscAmount' 		=> 0,
														'DPAmount' 			=> 0,
														'InvAmount_PPn' 	=> 0,
														'InvAmount_PPh' 	=> 0,
														'InvAmount_Ret' 	=> 0,
														'InvAmount_Disc' 	=> 0,
														'Manual_No' 		=> $CB_CODE,
														'Ref_Number' 		=> $PD_NUM,
														'Faktur_No' 		=> $PD_NUM,
														'Faktur_Code' 		=> $PD_CODE,
														'Faktur_Date' 		=> $PD_DATE,
														'Other_Desc'		=> "$SPLDESC. $PD_NOTES ($PD_CODE / $PD_REFCODE). Voc. No : $INV_CODE.",
														'CB_NOTES' 			=> $AH_NOTES,
														'SPLCAT'			=> $SPLCAT,
														'INV_AMOUNT'		=> 0,
														'PD_PAYMENT'		=> $PD_PAYMENT,
														'CB_SOURCE'			=> $CB_SOURCE);
									$this->m_journal->createJournalD($JournalH_Code, $parameters);

									// START : UPDATE PD
										$PD_PAYMENTV= number_format($PD_PAYMENT,2);
										$s_UPPD 	= "UPDATE tbl_journalheader_pd SET Journal_AmountReal = Journal_AmountReal+$PD_PAYMENT,
															PPD_RemAmount = Journal_Amount-Journal_AmountReal,
															JournalH_Desc2 = 'Digunakan untuk pembayaran ke $SPLDESC sebesar $PD_PAYMENTV',
															GEJ_STAT = IF(Journal_Amount=Journal_AmountReal,6,3),
															GEJ_STAT_PD = IF(Journal_Amount=Journal_AmountReal,6,3),
															STATDESC = IF(Journal_Amount=Journal_AmountReal,'Closed','Approved'),
															STATCOL = IF(Journal_Amount=Journal_AmountReal,'info','success')
														WHERE JournalH_Code = '$PD_NUM' AND proj_Code = '$PRJCODE'";
										$this->db->query($s_UPPD);

										$s_UPJRNPD	= "UPDATE tbl_journalheader SET Journal_AmountReal = Journal_AmountReal-$PD_PAYMENT,
															PPD_RemAmount = Journal_Amount-Journal_AmountReal,
															JournalH_Desc2 = 'Digunakan untuk pembayaran ke $SPLDESC sebesar $PD_PAYMENTV'
													WHERE JournalH_Code = '$PD_NUM' AND proj_Code = '$PRJCODE'";
										$this->db->query($s_UPJRNPD);
									// END : UPDATE PD
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
							$LastUpdate		= $CB_CREATED;
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
							$LastUpdate		= $CB_CREATED;
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

					$upJH3		= "UPDATE tbl_journaldetail SET SPLCODE = '$SPLCODE', SPLDESC = '$SPLDESC' WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
				}
				/*else
				{*/
					// START : CREATE ALERT LIST
						$APP_LEVEL 	= $this->input->post('APP_LEVEL');
						$alertVar 	= array('PRJCODE'		=> $PRJCODE,
											'AS_CATEG'		=> "BP",
											'AS_MNCODE'		=> "MN145",
											'AS_DOCNUM'		=> $CB_NUM,
											'AS_DOCCODE'	=> $CB_CODE,
											'AS_DOCDATE'	=> $CB_DATE,
											'AS_EXPDATE'	=> $CB_DATE,
											'APP_LEVEL'		=> $APP_LEVEL);
					$this->m_updash->updAALERT($alertVar);
					// END : CREATE ALERT LIST
				//}
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
					
				// START : UPDATE FINANCIAL DASHBOARD
					$BP_VAL_M 	= $CB_TOTAM;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $CB_DATE,
										'FVAL'		=> $BP_VAL_M,
										'FNAME'		=> "BP_VAL_M");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
			
				if($CB_PAYTYPE == 'PD' && isset($_POST['dataPD']))
				{
					foreach($_POST['dataPD'] as $d)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$PPD_VAL_M 	= $$d['PD_PAYMENT'];
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $CB_DATE,
												'FVAL'		=> $PPD_VAL_M,
												'FNAME'		=> "PPD_VAL_M");
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}
				}
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
					
				// START : UPDATE FINANCIAL DASHBOARD
					$BP_VAL_M 	= $CB_TOTAM;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $CB_DATE,
										'FVAL'		=> $BP_VAL_M,
										'FNAME'		=> "BP_VAL_M");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD
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

			// START : ADD DOC HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				date_default_timezone_set("Asia/Jakarta");
				$paramTrack 	= array('REF_NUM' 		=> $CB_NUM,
										'TBLNAME' 		=> "tbl_bp_header",
										'FLDCODE'		=> "CB_CODE",
										'FLDNAME'		=> "CB_NUM",
										'HISTTYPE'		=> "Update approve ($CB_STAT)");
				$this->m_updash->uDocH($paramTrack);
			// END : ADD DOC HISTORY
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			//$PRJCODE	= '';
			$url	= site_url('c_finance/c_bp0c07180851/G37Bp4YMn_1n/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
					
				$CB_NOTES		= htmlentities($dataI['CB_NOTES'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
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
			$data['Acc_ID'] 		= $getbankpay->CB_ACCID;
      		$Acc_ID            		= $getbankpay->CB_ACCID;
			$data['CB_PAYFOR'] 		= $getbankpay->CB_PAYFOR;
      		$CB_PAYFOR            	= $getbankpay->CB_PAYFOR;
			$data['CB_SOURCE']		= $getbankpay->CB_SOURCE;
			$data['CB_CHEQNO'] 		= $getbankpay->CB_CHEQNO;
			$data['CB_CHEQNO'] 		= $getbankpay->CB_CHEQNO;
			$data['CB_DOCTYPE']		= $getbankpay->CB_DOCTYPE;
			$data['CB_STAT']		= $getbankpay->CB_STAT;
			$data['CB_TOTAM'] 		= $getbankpay->CB_TOTAM;
			$data['CB_TOTAM_PPn']	= $getbankpay->CB_TOTAM_PPN;
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
		$collID1	= $_GET['idINV'];
		$collID		= explode("~", $collID1);
		$PRJCODE	= $collID[0];
		$SPLCODE	= $collID[1];
		$PAGEFORM	= $collID[2];
		$CB_PAYTYPE = "";
		if(isset($collID[3]))
		{
			$CB_PAYTYPE	= $collID[3];
		}

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		$CB_NUM 	= $_GET['DocNumber'];
		
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
			$num_rows 		= $this->m_bank_payment->get_AllDataINVC($PRJCODE, $SPLCODE, $CB_PAYTYPE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataINVL($PRJCODE, $SPLCODE, $CB_PAYTYPE, $search, $length, $start, $order, $dir);
								
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
				$INV_AMOUNT_POTOTH	= $dataI['INV_AMOUNT_POTOTH'];
				$INV_AMOUNT_TOT		= $dataI['INV_AMOUNT_TOT'];
				$INV_AMOUNT_TOTA 	= $INV_AMOUNT_TOT;
				$INV_AMOUNT_PAID	= $dataI['INV_AMOUNT_PAID'];
				$INV_ACC_OTH		= $dataI['INV_ACC_OTH'];
				$INV_PPN			= $dataI['INV_PPN'];
				$PPN_PERC			= $dataI['PPN_PERC'];
				$INV_PPH			= $dataI['INV_PPH'];
				$PPH_PERC			= $dataI['PPH_PERC'];
				$INV_NOTES			= $dataI['INV_NOTES'];
				$INV_NOTES1			= $dataI['INV_NOTES1'];
				$REF_NOTES			= $dataI['REF_NOTES'];

				$disabledB 			= 0;

				// INGAT BAHWA ADA PPH FINAL DAN NON FINAL. PADA INVOUCE TSB MENGANDUNG PPH FINAL, MAKA NILAI INVOICE HARUS SUDAH DIPOTONG
					/*$TAXCODE_PPH 	= "";
					$s_PPH			= "SELECT TAXCODE_PPH FROM tbl_pinv_detail WHERE INV_NUM = '$INV_NUM' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_PPH			= $this->db->query($s_PPH)->result();
					foreach($r_PPH as $rw_PPH) :
						$TAXCODE_PPH	= $rw_PPH->TAXCODE_PPH;
					endforeach;
					$isPPhFin 		= 0;
					$s_ACCPPH 		= "SELECT isPPhFinal FROM tbl_chartaccount_$PRJCODEVW
										WHERE PRJCODE = '$PRJCODE' AND Account_Number IN (SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE_PPH') LIMIT 1";
					$r_ACCPPH		= $this->db->query($s_ACCPPH)->result();
					foreach($r_ACCPPH as $rw_ACCPPH):
						$isPPhFin	= $rw_ACCPPH->isPPhFinal;
					endforeach;
					if($isPPhFin == 1)
					{
						$INV_AMOUNT_TOT	= $INV_AMOUNT_TOTA - $INV_AMOUNT_PPH;
					}*/

				$SPLDESC		= '-';
				$sqlSUPL		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSUPL		= $this->db->query($sqlSUPL)->result();
				foreach($resSUPL as $rowSUPL) :
					$SPLDESC	= $rowSUPL->SPLDESC;
				endforeach;
				if($INV_NOTES == '')
				{
					$INV_NOTES	= $SPLDESC;
				}
				else
				{
					$INV_NOTES	= "$SPLDESC - $INV_NOTES";
				}

				$this->db->select_sum("A.CBD_AMOUNT", "INV_AMOUNT_PAID");
				$this->db->from("tbl_bp_detail A");
				$this->db->join("tbl_bp_header B", "B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE");
				$this->db->where(["A.CBD_DOCNO" => $INV_NUM, "A.PRJCODE" => $PRJCODE, "B.CB_PAYFOR" => $SPLCODE, "B.CB_NUM !=" => $CB_NUM]);
				$this->db->where_in("B.CB_STAT", [1,2,4]);
				$getPAID = $this->db->get();
				if($getPAID->num_rows() > 0)
				{
					foreach($getPAID->result() as $rPAID):
						$RsvPAID 	= $rPAID->INV_AMOUNT_PAID;
					endforeach;
					if($RsvPAID == '') $RsvPAID = 0;
				}

				$INT_PAIDTOT 	= $INV_AMOUNT_PAID + $RsvPAID;
				//$INV_REMAMN 	= $INV_AMOUNT_TOT - $INT_PAIDTOT;

				$RSVVW 		= "";
				if($RsvPAID > 0)
				{
					$RSVVW 	= "<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i>Confirmed</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".number_format($RsvPAID, 2)."
								  	</div>";
				}

				// PPN
					$PPNVW 		= "";
					if($INV_AMOUNT_PPN > 0)
					{
						$PPNVW 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>PPn</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_PPN, 2)."
									  	</div>";
					}

				$TOTIDX 		= 0;
				$min_01 		= "";
				$min_02 		= "";
				$min_03 		= "";
				$min_04 		= "";
				$min_05 		= "";
				// 01 PPH
					if($INV_AMOUNT_PPH > 0)
					{
						// GET TAXCODE_PPH Hasil Migrasi => TAXCODE_PPH di detail tidak boleh kosong (verifikasi manual)
						$ACC_IDVw 	= "";
						$s_01 		= "SELECT TAXCODE_PPH FROM tbl_pinv_detail 
										WHERE PRJCODE = '$PRJCODE' AND INV_NUM = '$INV_NUM' AND REF_CATEG = 'IMP'";
						$r_01 		= $this->db->query($s_01);
						if($r_01->num_rows() > 0)
						{
							foreach($r_01->result() as $rw_01):
								$ACC_ID_PPH = $rw_01->TAXCODE_PPH;
							endforeach;

							if($ACC_ID_PPH == '')
							{
								$disabledB 	= 1;
								$ACC_IDVw 	= "<div style='font-style: italic; font-size:7pt; color: red;'>NOT SET ACCOUNT PPH</div>";
							}
						}

						

						$TOTIDX 	= $TOTIDX+1;
						$min_01 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>PPh</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_PPH, 2)."
									  	</div>
										$ACC_IDVw";
					}
				// 02 RETENSI
					if($INV_AMOUNT_RET > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_02		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Retensi</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_RET, 2)."
									  	</div>";
					}
				// 03 Pot. UM
					if($INV_AMOUNT_DPB > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_03		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. UM</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_DPB, 2)."
									  	</div>";
					}
				// 04 Pot. Penggunaan Material oleh Supplier
					if($INV_AMOUNT_OTH > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_04		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. Pengg. Mtr</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_OTH, 2)."
									  	</div>";
					}
				// 05 Pot. Lainnya
					if($INV_AMOUNT_POTOTH > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_05 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. Lainnya</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_POTOTH, 2)."
									  	</div>";
					}

				if($TOTIDX == 0)
					$min_01 	= "-";

				/*$chkBox			= "<input type='checkbox' name='chk0' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."' onClick='pickThis0(this);'/>";*/

				// YANG AKAN DIBAYAR SEHARUSNYA SETELAH DIKURANGI PPH
				$INV_REMAMN 	= $INV_AMOUNT_TOT - $INT_PAIDTOT;

				// START: GET REM PD Amount
					$REM_PD_Amount 	= 0;
					$PAYPDVW 		= "";
					$s_RPD 	= "SELECT Journal_Amount AS REM_PD_Amount 
								FROM tbl_journalheader_pd_rinv WHERE Invoice_No = '$INV_NUM'
								ORDER BY JournalH_Id DESC LIMIT 1";
					$r_RPD 	= $this->db->query($s_RPD);
					if($r_RPD->num_rows() > 0)
					{
						foreach($r_RPD->result() AS $rPD):
							$REM_PD_Amount = $rPD->REM_PD_Amount;
						endforeach;
						// START: CEK Jika sudah dibayar dengan PD
							$s_PAYPD 	= "tbl_bp_detail A
											INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM AND A.CBD_DOCNO = '$INV_NUM' 
											AND B.CB_PAYTYPE = 'PD' AND B.CB_STAT NOT IN (5,9)";
							$r_PAYPD 	= $this->db->count_all($s_PAYPD);
							if($r_PAYPD == 0)
							{
								$PAYPDVW 		= "";
								if($REM_PD_Amount > 0 && $CB_PAYTYPE != 'PD')
								{
									$PAYPDVW 	= "<div style='white-space:nowrap; color: red;'>
														<strong><i class='fa fa-check-square-o margin-r-5'></i>Rencana Dibayar PD</strong>
													</div>
													<div style='margin-left: 20px; color: red;'>
														".number_format($REM_PD_Amount, 2)."
													</div>";
								}
							}
						// END: CEK Jika sudah dibayar dengan PD
					}
				// END: GET REM PD Amount

				$INV_REMAMN 	= $INV_AMOUNT_TOT - $INT_PAIDTOT - $REM_PD_Amount;

				if($disabledB == 0)
				{
					//$chkBox			= "<input type='checkbox' name='chk0' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INT_PAIDTOT."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."|".$REM_PD_Amount."' onClick='pickThis0(this);'/>";
					$chkBox			= "<input type='radio' name='chk0' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_REMAMN."|".$INT_PAIDTOT."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."|".$REM_PD_Amount."' onClick='pickThis0(this);'/>";
				}
				else
				{
					$chkBox	= "<input type='radio' name='chk1' value='' style='display: none' />";
				}

				$INV_NOTESV 		= wordwrap($INV_NOTES, 50, "<br>", TRUE);

				$output['data'][] 	= array($chkBox,
										  	"<div style='white-space:nowrap'>".$INV_CODE."</div>",
										  	"<div style='white-space:nowrap'>
										  		$INV_NOTESV<br>
												<strong><i class='fa fa-calendar margin-r-5'></i>Tanggal</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$INV_DUEDATEV."
										  	</div>",
										  	number_format($INV_AMOUNT, 2).
										  	$PPNVW,
										  	"$min_01
										  	$min_02
										  	$min_03
										  	$min_04
										  	$min_05",
										  	number_format($INV_AMOUNT_OTH, 2),
										  	number_format($INV_AMOUNT_PAID, 2).
										  	$RSVVW."".$PAYPDVW,
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

  	function get_AllDataVC() // GOOD
	{
		$collID1	= $_GET['idVC'];
		$collID		= explode("~", $collID1);
		$PRJCODE	= $collID[0];
		$SPLCODE	= $collID[1];
		$PAGEFORM	= $collID[2];

		$CB_NUM 	= $_GET['DocNumber'];
		
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
			$num_rows 		= $this->m_bank_payment->get_AllDataVCC($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataVCL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$INV_NUM			= $dataI['INV_NUM'];
				$INV_CODE			= $dataI['INV_CODE'];
				$INV_AMOUNT			= $dataI['INV_AMOUNT'];
				$INV_AMOUNT_PPN		= $dataI['INV_AMOUNT_PPN'];
				$INV_AMOUNT_PPH		= $dataI['INV_AMOUNT_PPH'];
				$INV_AMOUNT_DPB		= $dataI['INV_AMOUNT_DPB'];
				$INV_AMOUNT_RET		= $dataI['INV_AMOUNT_RET'];
				$INV_AMOUNT_POT		= $dataI['INV_AMOUNT_POT'];
				$INV_AMOUNT_OTH		= $dataI['INV_AMOUNT_OTH'];
				// $INV_AMOUNT_TOT		= $dataI['INV_AMOUNT_TOT'];
				$INV_AMOUNT_PAID	= $dataI['INV_AMOUNT_PAID'];
				$INV_ACC_OTH		= $dataI['INV_ACC_OTH'];
				$INV_PPN			= $dataI['INV_PPN'];
				$PPN_PERC			= $dataI['PPN_PERC'];
				$INV_PPH			= $dataI['INV_PPH'];
				$PPH_PERC			= $dataI['PPH_PERC'];
				$INV_NOTES			= $dataI['INV_NOTES'];
				$SPLCODE			= $dataI['SPLCODE'];

				$INV_AMOUNT_TOT		= $INV_AMOUNT + $INV_AMOUNT_PPN - $INV_AMOUNT_PPH - $INV_AMOUNT_DPB - $INV_AMOUNT_RET - $INV_AMOUNT_POT - $INV_AMOUNT_OTH;

				$INV_DUEDATE		= $dataI['INV_DUEDATE'];
				$INV_DUEDATEV		= date('d M Y', strtotime($INV_DUEDATE));

				$SPLDESC		= '-';
				$sqlSPL 		= "SELECT CONCAT(First_Name,' ', Last_Name) AS SPLDESC
	    							FROM tbl_employee WHERE Emp_ID = '$SPLCODE'";
                $resSPL 		= $this->db->query($sqlSPL)->result();
				foreach($resSPL as $rowSPL) :
					$SPLDESC 	= $rowSPL->SPLDESC;
				endforeach;
				if($INV_NOTES == '')
				{
					$INV_NOTES	= "-";
				}

				/*$this->db->select_sum("A.CBD_AMOUNT", "INV_AMOUNT_PAID");
				$this->db->from("tbl_bp_detail A");
				$this->db->join("tbl_bp_header B", "B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE");
				$this->db->where(["A.CBD_DOCNO" => $INV_NUM, "A.PRJCODE" => $PRJCODE, "B.CB_PAYFOR" => $SPLCODE, "B.CB_NUM !=" => $CB_NUM]);
				$this->db->where_in("B.CB_STAT", [1,2,4]);*/

				$this->db->select_sum("A.CBD_AMOUNT", "INV_AMOUNT_PAID");
				$this->db->from("tbl_bp_detail A");
				$this->db->join("tbl_bp_header B", "B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE");
				$this->db->where(["A.CBD_DOCNO" => $INV_NUM, "A.PRJCODE" => $PRJCODE, "B.CB_PAYFOR" => $SPLCODE, "B.CB_NUM =" => $CB_NUM]);
				$this->db->where_in("B.CB_STAT", [1,2,4]);
				$getPAID = $this->db->get();
				if($getPAID->num_rows() > 0)
				{
					foreach($getPAID->result() as $rPAID):
						$RsvPAID 	= $rPAID->INV_AMOUNT_PAID;
					endforeach;
					if($RsvPAID == '') $RsvPAID = 0;
				}

				$RSVVW 		= "";
				if($RsvPAID > 0)
				{
					$RSVVW 	= "<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i>Confirmed</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".number_format($RsvPAID, 2)."
								  	</div>";
				}

				$INV_AMOUNT_PAID 	= $INV_AMOUNT_PAID + $RsvPAID;
				$INV_REMAMN 		= $INV_AMOUNT_TOT - $INV_AMOUNT_PAID;

				if($INV_REMAMN <= 0)
				{
					$chkBox			= "<input type='checkbox' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."' onClick='pickThis1(this);' disabled/>";
				}
				else
				{
					$chkBox			= "<input type='checkbox' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."' onClick='pickThis1(this);'/>";
				}

				// PPN
					$PPNVW 		= "";
					if($INV_AMOUNT_PPN > 0)
					{
						$PPNVW 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>PPn</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_PPN, 2)."
									  	</div>";
					}

				$TOTIDX 		= 0;
				$min_01 		= "-";
				$min_02 		= "";
				$min_03 		= "";
				$min_04 		= "";
				$min_05 		= "";
				// 01 PPH
					if($INV_AMOUNT_PPH > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_01 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>PPh</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_PPH, 2)."
									  	</div>";
					}
				// 02 RETENSI
					if($INV_AMOUNT_RET > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_02		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Retensi</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_RET, 2)."
									  	</div>";
					}
				// 03 Pot. UM
					if($INV_AMOUNT_RET > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_03		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. UM</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_RET, 2)."
									  	</div>";
					}
				// 04 Pot. Penggunaan Material oleh Supplier
					if($INV_AMOUNT_DPB > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_04		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. UM</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_DPB, 2)."
									  	</div>";
					}
				// 05 Pot. Lainnya
					$INV_AMOUNT_POTOTH 	= 0;
					if($INV_AMOUNT_POTOTH > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_05 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. Lainnya</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_POTOTH, 2)."
									  	</div>";
					}

				if($TOTIDX == 0)
					$min_01 		= "-";

				$INV_NOTESV 		= wordwrap($INV_NOTES, 50, "<br>", true);

				$output['data'][] 	= array($chkBox,
										  	"<div style='white-space:nowrap'>".$INV_CODE."</div>",
										  	"<div style='white-space:nowrap'>
										  		$INV_NOTESV<br>
												<strong><i class='fa fa-calendar margin-r-5'></i>Tanggal</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$INV_DUEDATEV."
										  	</div>",
										  	number_format($INV_AMOUNT, 2).
										  	$PPNVW,
										  	"$min_01
										  	$min_02
										  	$min_03
										  	$min_04
										  	$min_05",
										  	number_format($INV_AMOUNT_OTH, 2),
										  	number_format($INV_AMOUNT_PAID, 2).
										  	$RSVVW,
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

  	function get_AllDataPPD() // GOOD
	{
		$collID1	= $_GET['idPPD'];
		$collID		= explode("~", $collID1);
		$PRJCODE	= $collID[0];
		$SPLCODE	= $collID[1];
		$PAGEFORM	= $collID[2];

		$CB_NUM 	= $_GET['DocNumber'];
		
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
			$num_rows 		= $this->m_bank_payment->get_AllDataPPDC($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataPPDL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$INV_NUM			= $dataI['INV_NUM'];
				$INV_CODE			= $dataI['INV_CODE'];
				$INV_AMOUNT			= $dataI['INV_AMOUNT'];
				$INV_AMOUNT_PPN		= $dataI['INV_AMOUNT_PPN'];
				$INV_AMOUNT_PPH		= $dataI['INV_AMOUNT_PPH'];
				$INV_AMOUNT_DPB		= $dataI['INV_AMOUNT_DPB'];
				$INV_AMOUNT_RET		= $dataI['INV_AMOUNT_RET'];
				$INV_AMOUNT_POT		= $dataI['INV_AMOUNT_POT'];
				$INV_AMOUNT_OTH		= $dataI['INV_AMOUNT_OTH'];
				// $INV_AMOUNT_TOT		= $dataI['INV_AMOUNT_TOT'];
				$INV_AMOUNT_PAID	= $dataI['INV_AMOUNT_PAID'];
				$INV_ACC_OTH		= $dataI['INV_ACC_OTH'];
				$INV_PPN			= $dataI['INV_PPN'];
				$PPN_PERC			= $dataI['PPN_PERC'];
				$INV_PPH			= $dataI['INV_PPH'];
				$PPH_PERC			= $dataI['PPH_PERC'];
				$INV_NOTES			= $dataI['INV_NOTES'];
				$SPLCODE			= $dataI['SPLCODE'];
				if($INV_AMOUNT_PPN == '')
					$INV_AMOUNT_PPN = 0;

				$INV_AMOUNT_TOT		= $INV_AMOUNT + $INV_AMOUNT_PPN - $INV_AMOUNT_PPH - $INV_AMOUNT_DPB - $INV_AMOUNT_RET - $INV_AMOUNT_POT - $INV_AMOUNT_OTH;

				$INV_DUEDATE		= $dataI['INV_DUEDATE'];
				$INV_DUEDATEV		= date('d M Y', strtotime($INV_DUEDATE));

				$SPLDESC		= '-';
				$sqlSPL 		= "SELECT CONCAT(First_Name,' ', Last_Name) AS SPLDESC
	    							FROM tbl_employee WHERE Emp_ID = '$SPLCODE'";
                $resSPL 		= $this->db->query($sqlSPL)->result();
				foreach($resSPL as $rowSPL) :
					$SPLDESC 	= $rowSPL->SPLDESC;
				endforeach;
				if($INV_NOTES == '')
				{
					$INV_NOTES	= "-";
				}

				$this->db->select_sum("A.CBD_AMOUNT", "INV_AMOUNT_PAID");
				$this->db->from("tbl_bp_detail A");
				$this->db->join("tbl_bp_header B", "B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE");
				$this->db->where(["A.CBD_DOCNO" => $INV_NUM, "A.PRJCODE" => $PRJCODE, "B.CB_PAYFOR" => $SPLCODE, "B.CB_NUM !=" => $CB_NUM]);
				$this->db->where_in("B.CB_STAT", [1,2,4]);
				$getPAID = $this->db->get();
				if($getPAID->num_rows() > 0)
				{
					foreach($getPAID->result() as $rPAID):
						$RsvPAID 	= $rPAID->INV_AMOUNT_PAID;
					endforeach;
					if($RsvPAID == '') $RsvPAID = 0;
				}

				$RSVVW 		= "";
				if($RsvPAID > 0)
				{
					$RSVVW 	= "<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i>Confirmed</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".number_format($RsvPAID, 2)."
								  	</div>";
				}

				$INV_AMOUNT_PAID 	= $INV_AMOUNT_PAID + $RsvPAID;
				$INV_REMAMN 		= $INV_AMOUNT_TOT - $INV_AMOUNT_PAID;
				$INV_REMAMNABS 		= abs($INV_REMAMN);

				if($INV_REMAMN <= 0)
				{
					$chkBox			= "<input type='checkbox' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."|".$INV_REMAMNABS."' onClick='pickThis2(this);'/>";
				}
				else
				{
					$chkBox			= "<input type='checkbox' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."|".$INV_REMAMNABS."' onClick='pickThis2(this);'/>";
				}

				// PPN
					$PPNVW 		= "";
					if($INV_AMOUNT_PPN > 0)
					{
						$PPNVW 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>PPn</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_PPN, 2)."
									  	</div>";
					}

				$TOTIDX 		= 0;
				$min_01 		= "-";
				$min_02 		= "";
				$min_03 		= "";
				$min_04 		= "";
				$min_05 		= "";
				// 01 PPH
					if($INV_AMOUNT_PPH > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_01 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>PPh</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_PPH, 2)."
									  	</div>";
					}
				// 02 RETENSI
					if($INV_AMOUNT_RET > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_02		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Retensi</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_RET, 2)."
									  	</div>";
					}
				// 03 Pot. UM
					if($INV_AMOUNT_RET > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_03		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. UM</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_RET, 2)."
									  	</div>";
					}
				// 04 Pot. Penggunaan Material oleh Supplier
					if($INV_AMOUNT_DPB > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_04		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. UM</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_DPB, 2)."
									  	</div>";
					}
				// 05 Pot. Lainnya
					$INV_AMOUNT_POTOTH 	= 0;
					if($INV_AMOUNT_POTOTH > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_05 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. Lainnya</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_POTOTH, 2)."
									  	</div>";
					}

				if($TOTIDX == 0)
					$min_01 		= "-";

				$output['data'][] 	= array($chkBox,
										  	"<div style='white-space:nowrap'>".$INV_CODE."</div>",
										  	"<div style='white-space:nowrap'>
										  		$INV_NOTES<br>
												<strong><i class='fa fa-calendar margin-r-5'></i>Tanggal</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$INV_DUEDATEV."
										  	</div>",
										  	number_format($INV_AMOUNT, 2).
										  	$PPNVW,
										  	"$min_01
										  	$min_02
										  	$min_03
										  	$min_04
										  	$min_05",
										  	number_format($INV_AMOUNT_OTH, 2),
										  	number_format($INV_AMOUNT_PAID, 2).
										  	$RSVVW,
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

  	function get_AllDataPD() // GOOD
	{
		$collID1	= $_GET['idPD'];
		$collID		= explode("~", $collID1);
		$PRJCODE	= $collID[0];
		$SPLCODE	= $collID[1];
		$PAGEFORM	= $collID[2];

		$CB_NUM 	= $_GET['DocNumber'];
		
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
			$num_rows 		= $this->m_bank_payment->get_AllDataPDC($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataPDL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$INV_NUM			= $dataI['INV_NUM'];
				$INV_CODE			= $dataI['INV_CODE'];
				$INV_AMOUNT			= $dataI['INV_AMOUNT'];
				$INV_AMOUNT_PPN		= $dataI['INV_AMOUNT_PPN'];
				$INV_AMOUNT_PPH		= $dataI['INV_AMOUNT_PPH'];
				$INV_AMOUNT_DPB		= $dataI['INV_AMOUNT_DPB'];
				$INV_AMOUNT_RET		= $dataI['INV_AMOUNT_RET'];
				$INV_AMOUNT_POT		= $dataI['INV_AMOUNT_POT'];
				$INV_AMOUNT_OTH		= $dataI['INV_AMOUNT_OTH'];
				// $INV_AMOUNT_TOT		= $dataI['INV_AMOUNT_TOT'];
				$INV_AMOUNT_PAID	= $dataI['INV_AMOUNT_PAID'];
				$INV_ACC_OTH		= $dataI['INV_ACC_OTH'];
				$INV_PPN			= $dataI['INV_PPN'];
				$PPN_PERC			= $dataI['PPN_PERC'];
				$INV_PPH			= $dataI['INV_PPH'];
				$PPH_PERC			= $dataI['PPH_PERC'];
				$INV_NOTES			= $dataI['INV_NOTES'];
				$SPLCODE			= $dataI['SPLCODE'];
				if($INV_AMOUNT_PPN == '')
					$INV_AMOUNT_PPN = 0;

				$INV_AMOUNT_TOT		= $INV_AMOUNT + $INV_AMOUNT_PPN - $INV_AMOUNT_PPH - $INV_AMOUNT_DPB - $INV_AMOUNT_RET - $INV_AMOUNT_POT - $INV_AMOUNT_OTH;

				$INV_DUEDATE		= $dataI['INV_DUEDATE'];
				$INV_DUEDATEV		= date('d M Y', strtotime($INV_DUEDATE));

				$SPLDESC		= '-';
				$sqlSPL 		= "SELECT CONCAT(First_Name,' ', Last_Name) AS SPLDESC
	    							FROM tbl_employee WHERE Emp_ID = '$SPLCODE'";
                $resSPL 		= $this->db->query($sqlSPL)->result();
				foreach($resSPL as $rowSPL) :
					$SPLDESC 	= $rowSPL->SPLDESC;
				endforeach;
				if($INV_NOTES == '')
				{
					$INV_NOTES	= "-";
				}

				$this->db->select_sum("A.CBD_AMOUNT", "INV_AMOUNT_PAID");
				$this->db->from("tbl_bp_detail A");
				$this->db->join("tbl_bp_header B", "B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE");
				$this->db->where(["A.CBD_DOCNO" => $INV_NUM, "A.PRJCODE" => $PRJCODE, "B.CB_PAYFOR" => $SPLCODE, "B.CB_NUM !=" => $CB_NUM]);
				$this->db->where_in("B.CB_STAT", [1,2,4]);
				$getPAID = $this->db->get();
				if($getPAID->num_rows() > 0)
				{
					foreach($getPAID->result() as $rPAID):
						$RsvPAID 	= $rPAID->INV_AMOUNT_PAID;
					endforeach;
					if($RsvPAID == '') $RsvPAID = 0;
				}

				$RSVVW 		= "";
				if($RsvPAID > 0)
				{
					$RSVVW 	= "<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i>Confirmed</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".number_format($RsvPAID, 2)."
								  	</div>";
				}

				$INV_AMOUNT_PAID 	= $INV_AMOUNT_PAID + $RsvPAID;
				$INV_REMAMN 		= $INV_AMOUNT_TOT - $INV_AMOUNT_PAID;
				$INV_REMAMNABS 		= abs($INV_REMAMN);

				if($INV_REMAMN <= 0)
				{
					$chkBox			= "<input type='checkbox' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."|".$INV_REMAMNABS."' onClick='pickThis2(this);'/>";
				}
				else
				{
					$chkBox			= "<input type='checkbox' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_AMOUNT_TOT."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."|".$INV_REMAMNABS."' onClick='pickThis2(this);'/>";
				}

				// PPN
					$PPNVW 		= "";
					if($INV_AMOUNT_PPN > 0)
					{
						$PPNVW 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>PPn</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_PPN, 2)."
									  	</div>";
					}

				$TOTIDX 		= 0;
				$min_01 		= "-";
				$min_02 		= "";
				$min_03 		= "";
				$min_04 		= "";
				$min_05 		= "";
				// 01 PPH
					if($INV_AMOUNT_PPH > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_01 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>PPh</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_PPH, 2)."
									  	</div>";
					}
				// 02 RETENSI
					if($INV_AMOUNT_RET > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_02		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Retensi</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_RET, 2)."
									  	</div>";
					}
				// 03 Pot. UM
					if($INV_AMOUNT_RET > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_03		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. UM</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_RET, 2)."
									  	</div>";
					}
				// 04 Pot. Penggunaan Material oleh Supplier
					if($INV_AMOUNT_DPB > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_04		= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. UM</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_DPB, 2)."
									  	</div>";
					}
				// 05 Pot. Lainnya
					$INV_AMOUNT_POTOTH 	= 0;
					if($INV_AMOUNT_POTOTH > 0)
					{
						$TOTIDX 	= $TOTIDX+1;
						$min_05 	= "<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i>Pot. Lainnya</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($INV_AMOUNT_POTOTH, 2)."
									  	</div>";
					}

				if($TOTIDX == 0)
					$min_01 		= "-";

				$output['data'][] 	= array($chkBox,
										  	"<div style='white-space:nowrap'>".$INV_CODE."</div>",
										  	"<div style='white-space:nowrap'>
										  		$INV_NOTES<br>
												<strong><i class='fa fa-calendar margin-r-5'></i>Tanggal</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$INV_DUEDATEV."
										  	</div>",
										  	number_format($INV_AMOUNT, 2).
										  	$PPNVW,
										  	"$min_01
										  	$min_02
										  	$min_03
										  	$min_04
										  	$min_05",
										  	number_format($INV_AMOUNT_OTH, 2),
										  	number_format($INV_AMOUNT_PAID, 2).
										  	$RSVVW,
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

  	function get_AllDataPD2() // GOOD
	{
		$collID1	= $_GET['id'];
		$collID		= explode("~", $collID1);
		$PRJCODE	= $collID[0];
		$SPLCODE	= $collID[1];
		$PAGEFORM	= $collID[2];

		$CB_NUM 	= $_GET['DocNumber'];
		$TROW 		= $_GET['tRow'];
		
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
			$num_rows 		= $this->m_bank_payment->get_AllDataPD2C($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_payment->get_AllDataPD2L($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PD_NUM			= $dataI['JournalH_Code'];
				$PD_CODE		= $dataI['Manual_No'];
				$PD_DESC		= $dataI['JournalH_Desc'];
				$PD_DATE		= $dataI['JournalH_Date'];
				$PD_DATEV		= date('d M Y', strtotime($PD_DATE));
				$PD_PERSON		= $dataI['PERSL_EMPID'];
				$SPLCODE		= $dataI['SPLCODE'];
				$Ref_Number		= $dataI['Reference_Number'];
				$PD_Amount		= $dataI['Journal_Amount'];
				$PD_AmountTsf	= $dataI['Journal_AmountTsf'];		// Transfer PD
				$PD_AmountReal	= $dataI['Journal_AmountReal'];		// Realisasi
				$PDPaid_Amount	= $dataI['PDPaid_Amount'];			// Transfer Kurang Bayar
				$PDRec_Amount	= $dataI['PDRec_Amount'];			// Terima Lebih Bayar
				$PPD_RemAmount	= $dataI['PPD_RemAmount'];

				$PD_REM_AMOUNT	= $PD_AmountTsf - $PD_AmountReal + $PDPaid_Amount - $PDRec_Amount;

				$SPLDESC		= '-';
				$sqlSPL 		= 	"SELECT CONCAT(First_Name,' ', Last_Name) AS SPLDESC FROM tbl_employee WHERE Emp_ID = '$SPLCODE'
									UNION
									SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
                $resSPL 		= $this->db->query($sqlSPL)->result();
				foreach($resSPL as $rowSPL) :
					$SPLDESC 	= $rowSPL->SPLDESC;
				endforeach;
				if($PD_DESC == '')
				{
					$PD_DESC	= "-";
				}

				$r_00 		= 0;
				$s_00 		= "tbl_journaldetail WHERE Faktur_No = '$PD_NUM' AND Journal_DK = 'D' AND JournalType = 'BP'";
				$r_00		= $this->db->count_all($s_00);

				$disDesc 	= "";
				if($PD_REM_AMOUNT <= 0 && $r_00 == 1)
				{
					$chkBox			= "<input type='checkbox' name='chkPD' value='".$PD_NUM."|".$PD_CODE."|".$PD_DESC."|".$PD_DATE."|".$SPLDESC."|".$Ref_Number."|".$PD_Amount."|".$PD_AmountTsf."|".$PD_AmountReal."|".$PDPaid_Amount."|".$PDRec_Amount."|".$PD_REM_AMOUNT."|".$TROW."' onClick='pickThisPD(this);' disabled/>";
				}
				else if($PD_REM_AMOUNT <= 0 && $r_00 == 0)
				{
					$chkBox			= "<input type='checkbox' name='chkPD' value='".$PD_NUM."|".$PD_CODE."|".$PD_DESC."|".$PD_DATE."|".$SPLDESC."|".$Ref_Number."|".$PD_Amount."|".$PD_AmountTsf."|".$PD_AmountReal."|".$PDPaid_Amount."|".$PDRec_Amount."|".$PD_REM_AMOUNT."|".$TROW."' onClick='pickThisPD(this);' disabled/>";
					$disDesc 		= "<span class='label label-danger' style='font-style: italic;'>Belum ada realisasi PD</span><br>";
				}
				else
				{
					$chkBox			= "<input type='checkbox' name='chkPD' value='".$PD_NUM."|".$PD_CODE."|".$PD_DESC."|".$PD_DATE."|".$SPLDESC."|".$Ref_Number."|".$PD_Amount."|".$PD_AmountTsf."|".$PD_AmountReal."|".$PDPaid_Amount."|".$PDRec_Amount."|".$PD_REM_AMOUNT."|".$TROW."' onClick='pickThisPD(this);'/>";
				}

				$output['data'][] 	= array($chkBox,
										  	"<div style='white-space:nowrap'>".$PD_CODE."</div>",
										  	"<div style='white-space:nowrap'>
										  		$disDesc$PD_DESC<br>
												<strong><i class='fa fa-calendar margin-r-5'></i>Tanggal</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$PD_DATEV."
										  	</div>",
										  	number_format($PD_Amount, 2),
										  	number_format($PD_AmountReal, 2),
											number_format($PD_REM_AMOUNT, 2));
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

  	function get_AllDataVOCPD() // GOOD
	{
		$collID1	= $_GET['id'];
		$collID		= explode("~", $collID1);
		$PRJCODE	= $collID[0];
		$SPLCODE	= $collID[1];
		$PAGEFORM	= $collID[2];

		$CB_NUM 	= $_GET['DocNumber'];
		$INV_NUM 	= $_GET['INV_NUM'];
		
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
			// $num_rows 		= $this->m_bank_payment->get_AllDataVOCPDC($PRJCODE, $SPLCODE, $INV_NUM, $search);
			$num_rows 		= $this->m_bank_payment->get_AllDataVOCPDC($SPLCODE, $INV_NUM, $search); // Multiple Proyek Pembayaran Voucher PD
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			// $query 			= $this->m_bank_payment->get_AllDataVOCPDL($PRJCODE, $SPLCODE, $INV_NUM, $search, $length, $start, $order, $dir);
			$query 			= $this->m_bank_payment->get_AllDataVOCPDL($SPLCODE, $INV_NUM, $search, $length, $start, $order, $dir); // Multiple Proyek Pembayaran Voucher PD
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$INV_NUM		= $dataI['Invoice_No'];
				$INV_CODE		= $dataI['Invoice_Code'];
				$INV_DATE		= $dataI['Invoice_Date'];
				$PD_NUM			= $dataI['JournalH_Code'];
				$PD_CODE		= $dataI['Manual_No'];
				$PD_DESC		= $dataI['JournalH_Desc'];
				$PD_DATE		= $dataI['JournalH_Date'];
				$PD_DATEV		= date('d M Y', strtotime($PD_DATE));
				$PD_PERSON		= $dataI['PERSL_EMPID'];
				$SPLCODE		= $dataI['SPLCODE'];
				$Ref_Number		= $dataI['Reference_Number'];
				$PD_Amount		= $dataI['Journal_Amount'];
				$PD_AmountV		= number_format($dataI['Journal_Amount'],2);
				$PD_AmountTsf	= $dataI['Journal_AmountTsf'];		// Transfer PD
				$PD_AmountReal	= $dataI['Journal_AmountReal'];		// Realisasi
				$PDPaid_Amount	= $dataI['PDPaid_Amount'];			// Transfer Kurang Bayar
				$PDRec_Amount	= $dataI['PDRec_Amount'];			// Terima Lebih Bayar
				$PPD_RemAmount	= $dataI['PPD_RemAmount'];
				$Invoice_Amount	= $dataI['Invoice_Amount'];
				$Invoice_AmountV= number_format($dataI['Invoice_Amount'],2);
				$REM_PD_Amount 	= $dataI['REM_PD_Amount'];
				$REM_PD_AmountV	= number_format($dataI['REM_PD_Amount'],2);

				$output['data'][] 	= array($noU,
										  	"<span id='PD_CODE_".$noU."'>".$PD_CODE."</span><input type='hidden' name='dataPD[".$noU."][INV_NUM]' id='dataPD".$noU."INV_NUM' value='".$INV_NUM."' class='form-control'><input type='hidden' name='dataPD[".$noU."][INV_CODE]' id='dataPD".$noU."INV_CODE' value='".$INV_CODE."' class='form-control'><input type='hidden' name='dataPD[".$noU."][INV_DATE]' id='dataPD".$noU."INV_DATE' value='".$INV_DATE."' class='form-control'><input type='hidden' name='dataPD[".$noU."][PD_NUM]' id='dataPD".$noU."PD_NUM' value='".$PD_NUM."' class='form-control'><input type='hidden' name='dataPD[".$noU."][PD_CODE]' id='dataPD".$noU."PD_CODE' value='".$PD_CODE."' class='form-control' onClick='selectPD(".$noU.");' placeholder='Pilih No. PD' >",
										  	"<span id='PD_DESC_".$noU."'>".$PD_DESC."</span><input type='hidden' name='dataPD[".$noU."][PD_DESC]' id='dataPD".$noU."PD_DESC' value='".$PD_DESC."' class='form-control' style='max-width:500px;'>",
										  	"<span id='PD_REFCODE_".$noU."'>".$Ref_Number."</span><input type='hidden' name='dataPD[".$noU."][PD_REFCODE]' id='dataPD".$noU."PD_REFCODE' value='".$Ref_Number."' class='form-control' >",
										  	"<span id='PD_AMOUNT_".$noU."'>".$PD_AmountV."</span><input type='hidden' name='dataPD[".$noU."][PD_AMOUNT]' id='dataPD".$noU."PD_AMOUNT' value='".$PD_Amount."' class='form-control' style='max-width:150px;' >",
										  	"".$REM_PD_AmountV."<input type='hidden' class='form-control' style='text-align:right' name='PD_REM_AMOUNT".$noU."' id='PD_REM_AMOUNT".$noU."' value='".$REM_PD_Amount."'><input type='hidden' class='form-control' style='text-align:right' name='PD_PAYMENT".$noU."' id='PD_PAYMENT".$noU."' value='".$REM_PD_Amount."' onBlur='chgAmountPD(this,".$noU.")' onKeyPress='return isIntOnlyNew(event);'><input type='hidden' name='dataPD[".$noU."][PD_PAYMENT]' id='dataPD".$noU."PD_PAYMENT' value='".$REM_PD_Amount."' class='form-control' style='max-width:150px;' >",
										  	"-<input type='hidden' name='dataPD[".$noU."][PD_NOTES]' id='dataPD".$noU."PD_NOTES' value='' class='form-control' style='min-width:110px; max-width:300px; text-align:left'>");
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
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
		$PRJCODE	= $collID[0];
		$SPLCODE	= $collID[1];
		$PAGEFORM	= $collID[2];
		
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
				$INV_AMOUNT_PPN		= $dataI['TAX_AMOUNT_PPN1'];	// 4
				$INV_AMOUNT_PPH 	= $dataI['TAX_AMOUNT_PPH1'];	// 4
				$INV_AMOUNT_DPB 	= 0;
				$INV_AMOUNT_RET		= 0;
				$INV_AMOUNT_POT 	= 0;
				$INV_AMOUNT_OTH 	= 0;
				$INV_AMOUNT_TOT 	= $dataI['ITM_AMOUNT'];
				$INV_AMOUNT_PAID 	= $dataI['INV_AMOUNT_PAID'];
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
               	$INV_TOTAL	 		= $INV_AMOUNT + $INV_AMOUNT_PPN - $INV_AMOUNT_PPH;
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

				//$INV_REMAMN 		= $INV_AMOUNT_TOT - $INV_AMOUNT_PAID;
				$INV_REMAMN 		= $INV_TOTAL - $INV_AMOUNT_PAID;
				
				$chkBox				= "<input type='radio' name='chk0' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_DPB."|".$INV_AMOUNT_RET."|".$INV_AMOUNT_POT."|".$INV_AMOUNT_OTH."|".$INV_REMAMN."|".$INV_AMOUNT_PAID."|".$INV_ACC_OTH."|".$INV_PPN."|".$PPN_PERC."|".$INV_PPH."|".$PPH_PERC."|".$INV_NOTES."|".$SPLCODE."' onClick='pickThis0(this);'/>";

				/*$output['data'][] 	= array($chkBox,
										  	$INV_CODE,
										  	$INV_NOTES,
										  	$INV_DUEDATEV,
										  	number_format($INV_REM, 2),
										  	number_format($INV_PPNREM, 2),
										  	number_format($INV_TOTALREM, 2));*/
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

			/*$output['data'][] = array("$INV_NUM",
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
	
	function getCBCode() // OK
	{
		$collData 	= $this->input->post('collData');
		$arrData 	= explode("~", $collData);
		$ACCID		= $arrData[0];
		$PRJCODE	= $arrData[1];
		$CB_DATE 	= date('Y-m-d');
		$YEAR 		= date('Y');
		$MONTH 		= date('m');
		$DATE 		= date('d');
		
		$s_01 		= "tbl_bp_header WHERE CB_ACCID = '$ACCID' AND PRJCODE = '$PRJCODE' AND CB_DATE = '$CB_DATE'";
		$r_01 		= $this->db->count_all($s_01);
		$myMax 		= $r_01+1;

		$BANK_PATT 	= "";
		$s_02 		= "SELECT LEFT(Account_NameId,3) AS BANK_PATT FROM tbl_chartaccount WHERE Account_Number = '$ACCID' AND PRJCODE = '$PRJCODE'";
		$r_02 		= $this->db->query($s_02)->result();
		foreach($r_02 as $rw_02):
			$BANK_PATT = $rw_02->BANK_PATT;
		endforeach;
		
		$Pattern_Length	= 3;
		$len = strlen($myMax);
		$nol = '';
		if($Pattern_Length==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($Pattern_Length==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($Pattern_Length==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($Pattern_Length==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		
		$lastPatt 	= $nol.$myMax;
		$DocNo 		= "$BANK_PATT$YEAR$MONTH$DATE$lastPatt";

		echo $DocNo;
	}
}