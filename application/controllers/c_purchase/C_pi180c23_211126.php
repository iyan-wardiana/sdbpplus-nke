<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 November 2017
 * File Name	= C_pi180c23.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_pi180c23 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		$this->load->model('m_journal/m_journal', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		$this->data['nSELP']		= $this->session->userdata['nSELP'];
		
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
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_purchase/c_pi180c23/ix180c23/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function ix180c23() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
				
			// GET MENU DESC
				$mnCode				= 'MN009';
				$data["MenuApp"] 	= 'MN009';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN009';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_pi180c23/gall180c23inv/?id=";
			
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
	
	function gall180c23inv() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN009';
			$data["MenuApp"] 	= 'MN009';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 		= $this->session->userdata('Emp_ID');

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
				//$data["url_search"] = site_url('c_purchase/c_pi180c23/f4n7_5rcH1Nv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_purchase_inv->count_all_pinv($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_purchase_inv->get_all_pinv($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Faktur Pembelian";
				$data['h2_title']	= 'Pembelian';
			}
			else
			{
				$data["h1_title"] 	= "Purchase Invoice";
				$data['h2_title']	= 'Purchase';
			}
			
			$data['PRJCODE']	= $PRJCODE;
			$data["MenuCode"] 	= 'MN009';
			$data['backURL'] 	= site_url('c_purchase/c_pi180c23/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN009';
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
			
			$this->load->view('v_purchase/v_purchase_inv/v_pinv_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1Nv()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_purchase/c_pi180c23/gall180c23inv/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("INV_CODE",
									"INV_DATE",
									"INV_DUEDATE",
									"B.SPLDESC",
									"INV_NOTES",
									"STATDESC",
									"INV_PAYSTAT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_inv->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_inv->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$INV_NUM		= $dataI['INV_NUM'];
				$INV_CODE		= $dataI['INV_CODE'];
				$INV_CATEG		= $dataI['INV_CATEG'];
				$INV_TYPE		= $dataI['INV_TYPE'];
				$PO_NUM			= $dataI['PO_NUM'];
				$IR_NUM			= $dataI['IR_NUM'];
				$SPLCODE		= $dataI['SPLCODE'];
				$SPLDESC		= $dataI['SPLDESC'];
				$INV_AMOUNT		= number_format($dataI['INV_AMOUNT'],2);
				$INV_TERM		= $dataI['INV_TERM'];
				$INV_STAT		= $dataI['INV_STAT'];
				$INV_PAYSTAT	= $dataI['INV_PAYSTAT'];
				$INV_NOTES		= $dataI['INV_NOTES'];
				$VOID_REASON	= $dataI['VOID_REASON'];
				
				$INV_DATE		= $dataI['INV_DATE'];
				$INV_DATEV		= date('d M Y', strtotime($INV_DATE));
				
				$INV_DUEDATE	= $dataI['INV_DUEDATE'];
				$INV_DUEDATEV	= date('d M Y', strtotime($INV_DUEDATE));
								
				/*$SPLDESC		= '';
				$sqlSPL			= "SELECT SPLDESC FROM tbl_supplier A WHERE SPLCODE = '$SPLCODE'";
				$ressqlSPL 		= $this->db->query($sqlSPL)->result();
				
				foreach($ressqlSPL as $rowSPL) :
					$SPLDESC 	= $rowSPL->SPLDESC;
				endforeach;*/
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				if($INV_CATEG == 'OPN-RET')
				{
					$STATDESC	= 'Approve';
					$STATCOL	= 'success';
				}
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$invPayStatDesc = "Not Payment";
				$invPayStatCol	= 'danger';
				if($INV_PAYSTAT == 'NP')
				{
					$invPayStatDesc = "Not Payment";
					$invPayStatCol	= 'danger';
				}
				elseif($INV_PAYSTAT == 'HP')
				{
					$invPayStatDesc = "Half Payment";
					$invPayStatCol	= 'warning';
				}
				elseif($INV_PAYSTAT == 'FP')
				{
					$invPayStatDesc = "Full Payment";
					$invPayStatCol	= 'success';
				}
				
				//$CollID		= "$INV_NUM~$INV_CATEG~$INV_TYPE";
				$CollID			= "$PRJCODE~$INV_NUM~$INV_CATEG~$INV_TYPE";
                $secUpd			= site_url('c_purchase/c_pi180c23/update/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_purchase/c_pi180c23/printdocument/?id='.$this->url_encryption_helper->encode_url($INV_NUM));
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDelIcut~tbl_pinv_header~tbl_pinv_detail~INV_NUM~$INV_NUM~PRJCODE~$PRJCODE";
				
				$secVoid 		= base_url().'index.php/__l1y/trashINV/?id=';
				$voidID 		= "$secVoid~tbl_pinv_header~tbl_pinv_detail~INV_NUM~$INV_NUM~PRJCODE~$PRJCODE";
                                    
				if($INV_STAT == 1 || $INV_STAT == 4) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($INV_STAT == 3 && $INV_PAYSTAT == 'NP')
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
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				$output['data'][] = array("<div style='white-space:nowrap'>$INV_CODE</div>",
										  $INV_DATEV,
										  $INV_DUEDATEV,
										  $SPLDESC,
										  $INV_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  "<span class='label label-".$invPayStatCol."' style='font-size:12px'>".$invPayStatDesc."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a180c23dd() // OK - INVOICING
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN009';
			$data["MenuApp"] 	= 'MN009';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_purchase/c_pi180c23/add_process');
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['PRJCODE_HO'] 	= $this->data['PRJCODE_HO'];
			$data['countSUPL'] 		= $this->m_purchase_inv->count_all_vend($PRJCODE);
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_all_vend($PRJCODE)->result();
			
			$MenuCode 				= 'MN009';
			$data["MenuCode"] 		= 'MN009';
			$data['viewDocPattern'] = $this->m_purchase_inv->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN009';
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
	
			$this->load->view('v_purchase/v_purchase_inv/v_pinv_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK - ADD INVOICING PROCESS
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
			$PRJCODE 		= $this->input->post('PRJCODE');
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('INV_DATE'))));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN009';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$INV_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$INV_CODE		= $this->input->post('INV_CODE');
			$INV_TYPE		= $this->input->post('INV_TYPE');
			$INV_CATEG		= $this->input->post('INV_CATEG');
			//$PO_NUM		= $this->input->post('PO_NUM');
			$PO_NUM			= "";
			$IR_NUM			= $this->input->post('Ref_Number'); 		// TTK NUMBER
			$INV_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('INV_DATE'))));
			$INV_DUEDATE	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('INV_DUEDATE'))));
			$SPLCODE 		= $this->input->post('SPLCODE');
			$INV_CURRENCY	= 'IDR';
			$INV_TAXCURR	= 'IDR';

			$DP_NUM			= $this->input->post('DP_NUM');
			$DP_AMOUNT		= $this->input->post('DP_AMOUNT');
			$INV_AMOUNT		= $this->input->post('INV_AMOUNT');
			$INV_AMOUNT_PPN		= $this->input->post('INV_AMOUNT_PPN');
			$INV_AMOUNT_PPH		= $this->input->post('INV_AMOUNT_PPH');
			$INV_AMOUNT_DPB		= $this->input->post('INV_AMOUNT_DPB');
			$INV_AMOUNT_RET		= $this->input->post('INV_AMOUNT_RET');
			$INV_AMOUNT_POT		= $this->input->post('INV_AMOUNT_POT');
			$INV_AMOUNT_OTH		= $this->input->post('INV_AMOUNT_OTH');
			$INV_AMOUNT_POTUM	= $this->input->post('INV_AMOUNT_POTUM');
			$INV_AMOUNT_TOT		= $this->input->post('INV_AMOUNT_TOT');
			$INV_ACC_OTH	= $this->input->post('INV_ACC_OTH');
			$INV_PPN		= $this->input->post('INV_PPN');
			$PPN_PERC		= $this->input->post('PPN_PERC');
			$INV_PPH		= $this->input->post('INV_PPH');
			$PPH_PERC		= $this->input->post('PPH_PERC');
			$INV_TERM		= $this->input->post('INV_TERM');
			$INV_STAT 		= $this->input->post('INV_STAT');
			$INV_PAYSTAT	= 'NP';
			$COMPANY_ID		= $COMPANY_ID;
			$VENDINV_NUM	= $this->input->post('VENDINV_NUM');
			$INV_NOTES		= addslashes($this->input->post('INV_NOTES'));
			//$INV_NOTES1	= addslashes($this->input->post('INV_NOTES1'));
			$CREATED		= date('Y-m-d H:i:s');
			$CREATER		= $DefEmp_ID;
			
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
								'INV_CURRENCY'		=> $INV_CURRENCY,
								'INV_TAXCURR'		=> $INV_TAXCURR,
								// 'DP_NUM'			=> $DP_NUM,
								// 'DP_AMOUNT'		=> $DP_AMOUNT,
								'INV_AMOUNT'		=> $INV_AMOUNT,
								'INV_AMOUNT_PPN'	=> $INV_AMOUNT_PPN,
								'INV_AMOUNT_PPH'	=> $INV_AMOUNT_PPH,
								'INV_AMOUNT_DPB'	=> $INV_AMOUNT_DPB,
								'INV_AMOUNT_RET'	=> $INV_AMOUNT_RET,
								'INV_AMOUNT_POT'	=> $INV_AMOUNT_POT,
								'INV_AMOUNT_OTH'	=> $INV_AMOUNT_OTH,
								'INV_AMOUNT_POTUM'	=> $INV_AMOUNT_POTUM,
								'INV_AMOUNT_TOT'	=> $INV_AMOUNT_TOT,
								'INV_ACC_OTH'		=> $INV_ACC_OTH,
								'INV_PPN'			=> $INV_PPN,
								'PPN_PERC'			=> $PPN_PERC,
								'INV_PPH'			=> $INV_PPH,
								'PPH_PERC'			=> $PPH_PERC,
								'INV_TERM'			=> $INV_TERM,
								'INV_STAT'			=> $INV_STAT,
								'INV_PAYSTAT'		=> $INV_PAYSTAT,
								'COMPANY_ID'		=> $comp_init,
								'VENDINV_NUM'		=> $VENDINV_NUM,
								'INV_NOTES'			=> $INV_NOTES,
								'CREATED'			=> $CREATED,
								'CREATER'			=> $CREATER,
								'Patt_Number'		=> $Patt_Number,
								'Patt_Year'			=> $Patt_Year);
			$this->m_purchase_inv->add($insertINV);
			
			foreach($_POST['data'] as $d)
			{
				$d['INV_NUM']		= $INV_NUM;
				$d['INV_CODE']		= $INV_CODE;
				$REF_CATEG			= $d['REF_CATEG'];
				$this->db->insert('tbl_pinv_detail',$d);
			}

			$updINV 	= array('INV_CATEG'	=> $REF_CATEG);
			$this->m_purchase_inv->updateINV($INV_NUM, $updINV);
			
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
										'FIELD_NM_A'	=> "TOT_PINV_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_PINV_HP",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_PINV_R",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_PINV_FP");		// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "INV_NUM",
										'DOC_CODE' 		=> $INV_NUM,
										'DOC_STAT' 		=> $INV_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_pinv_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $INV_NUM;
				$MenuCode 		= 'MN009';
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
			
			$url			= site_url('c_purchase/c_pi180c23/gall180c23inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function popupall_IR() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
		
		$data['countAllIR'] 	= $this->m_purchase_inv->count_all_IR($SPLCODE, $PRJCODE); 
		$data['viewAllIR'] 		= $this->m_purchase_inv->viewAllIR($SPLCODE, $PRJCODE)->result();
				
		$this->load->view('v_purchase/v_purchase_inv/v_pinv_sel_ir', $data);
	}
	
	function update() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN009';
			$data["MenuApp"] 	= 'MN009';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			$splitCode 	= explode("~", $CollID);
			$PRJCODE	= $splitCode[0];
			$INV_NUM	= $splitCode[1];
			$INV_CATEG	= $splitCode[2];
			$ISDIRECT	= $splitCode[3];
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pi180c23/update_process');
			
			$MenuCode 			= 'MN009';
			$data["MenuCode"] 	= 'MN009';
			
			
			$getINV				= $this->m_purchase_inv->get_INV_by_number($INV_NUM)->row();

			$data['default']['INV_NUM'] 		= $getINV->INV_NUM;
			$data['default']['INV_CODE'] 		= $getINV->INV_CODE;
			$data['default']['INV_TYPE'] 		= $getINV->INV_TYPE;
			$data['default']['PO_NUM'] 			= $getINV->PO_NUM;
			$data['default']['IR_NUM'] 			= $getINV->IR_NUM;
			$IR_NUM								= $getINV->IR_NUM;
			$data['default']['PRJCODE'] 		= $getINV->PRJCODE;
			$PRJCODE							= $getINV->PRJCODE;
			$data['PRJCODE'] 					= $PRJCODE;
			$data['default']['INV_DATE'] 		= $getINV->INV_DATE;
			$data['default']['INV_DUEDATE'] 	= $getINV->INV_DUEDATE;
			$data['default']['SPLCODE'] 		= $getINV->SPLCODE;
			$SPLCODE							= $getINV->SPLCODE;
			$data['default']['DP_NUM']			= $getINV->DP_NUM;
			$data['default']['DP_AMOUNT']		= $getINV->DP_AMOUNT;
			$data['default']['INV_CURRENCY']	= $getINV->INV_CURRENCY;
			$data['default']['INV_TAXCURR'] 	= $getINV->INV_TAXCURR;
			$data['default']['INV_AMOUNT'] 		= $getINV->INV_AMOUNT;
			$data['default']['INV_AMOUNT_PPN'] 	= $getINV->INV_AMOUNT_PPN;
			$data['default']['INV_AMOUNT_PPH'] 	= $getINV->INV_AMOUNT_PPH;
			$data['default']['INV_AMOUNT_DPB'] 	= $getINV->INV_AMOUNT_DPB;
			$data['default']['INV_AMOUNT_RET'] 	= $getINV->INV_AMOUNT_RET;
			$data['default']['INV_AMOUNT_POT'] 	= $getINV->INV_AMOUNT_POT;
			$data['default']['INV_AMOUNT_OTH'] 	= $getINV->INV_AMOUNT_OTH;
			$data['default']['INV_AMOUNT_POTUM']= $getINV->INV_AMOUNT_POTUM;
			$data['default']['INV_AMOUNT_TOT'] 	= $getINV->INV_AMOUNT_TOT;
			$data['default']['INV_ACC_OTH'] 	= $getINV->INV_ACC_OTH;
			$data['default']['INV_PPN'] 		= $getINV->INV_PPN;
			$data['default']['PPN_PERC'] 		= $getINV->PPN_PERC;
			$data['default']['INV_PPH'] 		= $getINV->INV_PPH;
			$data['default']['PPH_PERC'] 		= $getINV->PPH_PERC;
			$data['default']['INV_TERM'] 		= $getINV->INV_TERM;
			$data['default']['INV_STAT'] 		= $getINV->INV_STAT;
			$data['default']['INV_PAYSTAT'] 	= $getINV->INV_PAYSTAT;
			$data['default']['COMPANY_ID'] 		= $getINV->COMPANY_ID;
			$data['default']['VENDINV_NUM'] 	= $getINV->VENDINV_NUM;
			$data['default']['INV_NOTES'] 		= $getINV->INV_NOTES;
			$data['default']['INV_NOTES1'] 		= $getINV->INV_NOTES1;
			$data['default']['REF_NOTES'] 		= $getINV->REF_NOTES;
			$data['default']['Patt_Number'] 	= $getINV->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getINV->INV_NUM;
				$MenuCode 		= 'MN009';
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
				$data['countSUPL'] 	= $this->m_purchase_inv->count_all_vendUP($SPLCODE);
				$data['vwSUPL'] 	= $this->m_purchase_inv->view_all_vendUP($SPLCODE)->result();
				
				if($INV_CATEG == 'OPN')
				{
					$data['countSUPL'] 	= $this->m_purchase_inv->count_all_vendUPOPN($SPLCODE, $PRJCODE);
					$data['vwSUPL'] 	= $this->m_purchase_inv->view_all_vendUPOPN($SPLCODE, $PRJCODE)->result();
				}
				elseif($INV_CATEG == 'OTH')
				{
					$data['countSUPL'] 	= $this->m_purchase_inv->count_all_vendUPOTH($SPLCODE, $PRJCODE);
					$data['vwSUPL'] 	= $this->m_purchase_inv->view_all_vendUPOTH($SPLCODE, $PRJCODE)->result();
				}
				$this->load->view('v_purchase/v_purchase_inv/v_pinv_form', $data);	
			}
			else
			{
				$data['countSUPL'] 	= $this->m_purchase_inv->count_all_vendDir($PRJCODE, $SPLCODE);
				$data['vwSUPL'] 	= $this->m_purchase_inv->view_all_vendDir($PRJCODE, $SPLCODE)->result();
				$this->load->view('v_purchase/v_purchase_inv/v_pinvDir_form', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
			$INV_CODE		= $this->input->post('INV_CODE');
			$INV_TYPE		= $this->input->post('INV_TYPE');
			$INV_CATEG		= "";
			//$PO_NUM		= $this->input->post('PO_NUM');
			$PO_NUM			= "";
			$IR_NUM			= $this->input->post('Ref_Number'); 		// TTK NUMBER
			$PRJCODE 		= $this->input->post('PRJCODE');
			$INV_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('INV_DATE'))));
			$INV_DUEDATE	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('INV_DUEDATE'))));
			$SPLCODE 		= $this->input->post('SPLCODE');
			$INV_CURRENCY	= 'IDR';
			$INV_TAXCURR	= 'IDR';

			$DP_NUM			= $this->input->post('DP_NUM');
			$DP_AMOUNT		= $this->input->post('DP_AMOUNT');
			$INV_AMOUNT		= $this->input->post('INV_AMOUNT');
			$INV_AMOUNT_PPN		= $this->input->post('INV_AMOUNT_PPN');
			$INV_AMOUNT_PPH		= $this->input->post('INV_AMOUNT_PPH');
			$INV_AMOUNT_DPB		= $this->input->post('INV_AMOUNT_DPB');
			$INV_AMOUNT_RET		= $this->input->post('INV_AMOUNT_RET');
			$INV_AMOUNT_POT		= $this->input->post('INV_AMOUNT_POT');
			$INV_AMOUNT_OTH		= $this->input->post('INV_AMOUNT_OTH');
			$INV_AMOUNT_POTUM	= $this->input->post('INV_AMOUNT_POTUM');
			$INV_AMOUNT_TOT		= $this->input->post('INV_AMOUNT_TOT');
			$INV_ACC_OTH	= $this->input->post('INV_ACC_OTH');
			$INV_PPN		= $this->input->post('INV_PPN');
			$PPN_PERC		= $this->input->post('PPN_PERC');
			$INV_PPH		= $this->input->post('INV_PPH');
			$PPH_PERC		= $this->input->post('PPH_PERC');
			$INV_TERM		= $this->input->post('INV_TERM');
			$INV_STAT 		= $this->input->post('INV_STAT');
			$INV_PAYSTAT	= 'NP';
			$COMPANY_ID		= $COMPANY_ID;
			$VENDINV_NUM	= $this->input->post('VENDINV_NUM');
			$INV_NOTES		= addslashes($this->input->post('INV_NOTES'));
			//$INV_NOTES1	= addslashes($this->input->post('INV_NOTES1'));
			$UPDATED		= date('Y-m-d H:i:s');
			$UPDATER		= $DefEmp_ID;
			
			$s = strtotime($INV_DATE);
			$e = strtotime($INV_DUEDATE);
			
			$INV_TERM 	= ($e - $s)/ (24 * 3600);

			$SPLCAT 	= "";
			$SPLDESC 	= "";
			$sqlSPL 	= "SELECT SPLCAT, SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resSPL		= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLCAT 	= $rowSPL->SPLCAT;
				$SPLDESC 	= $rowSPL->SPLDESC;
			endforeach;

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
								'INV_CURRENCY'		=> $INV_CURRENCY,
								'INV_TAXCURR'		=> $INV_TAXCURR,
								// 'DP_NUM'			=> $DP_NUM,
								// 'DP_AMOUNT'		=> $DP_AMOUNT,
								'INV_AMOUNT'		=> $INV_AMOUNT,
								'INV_AMOUNT_PPN'	=> $INV_AMOUNT_PPN,
								'INV_AMOUNT_PPH'	=> $INV_AMOUNT_PPH,
								'INV_AMOUNT_DPB'	=> $INV_AMOUNT_DPB,
								'INV_AMOUNT_RET'	=> $INV_AMOUNT_RET,
								'INV_AMOUNT_POT'	=> $INV_AMOUNT_POT,
								'INV_AMOUNT_OTH'	=> $INV_AMOUNT_OTH,
								'INV_AMOUNT_POTUM'	=> $INV_AMOUNT_POTUM,
								'INV_AMOUNT_TOT'	=> $INV_AMOUNT_TOT,
								'INV_ACC_OTH'		=> $INV_ACC_OTH,
								'INV_PPN'			=> $INV_PPN,
								'PPN_PERC'			=> $PPN_PERC,
								'INV_PPH'			=> $INV_PPH,
								'PPH_PERC'			=> $PPH_PERC,
								'INV_TERM'			=> $INV_TERM,
								'INV_STAT'			=> $INV_STAT,
								'INV_PAYSTAT'		=> $INV_PAYSTAT,
								'COMPANY_ID'		=> $comp_init,
								'VENDINV_NUM'		=> $VENDINV_NUM,
								'INV_NOTES'			=> $INV_NOTES,
								'CREATED'			=> $UPDATED,
								'CREATER'			=> $UPDATER);
			$this->m_purchase_inv->updateINV($INV_NUM, $updINV);

			if($INV_STAT == 1 || $INV_STAT == 2)
			{
				$this->m_purchase_inv->deleteINVDet($INV_NUM);
			
				foreach($_POST['data'] as $d)
				{
					$d['INV_NUM']	= $INV_NUM;
					$d['INV_CODE']	= $INV_CODE;
					$REF_CATEG		= $d['REF_CATEG'];
					$this->db->insert('tbl_pinv_detail',$d);
				}

				$updINV 	= array('INV_CATEG'	=> $REF_CATEG);
				$this->m_purchase_inv->updateINV($INV_NUM, $updINV);
			}
			if($INV_STAT == 3)
			{
				// UM COLLECTION
					$nUML 	= 0;
					$UM_COLL= "";
					foreach($_POST['dataUM'] as $dUML)
					{
						$UM_NUM_SEL		= $d['UM_NUM_SEL'];
						if($UM_NUM_SEL != '')
						{
							$nUML 		= $nUML+1;
							if($nUML == 1)
								$UM_COLL = $UM_NUM_SEL;
							else
								$UM_COLL = $UM_COLL."~".$UM_NUM_SEL;
						}
					}

				// START : JOURNAL HEADER
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $INV_NUM;
					$JournalType	= 'PINV';
					$JournalH_Date	= $INV_DATE;
					$Company_ID		= $comp_init;
					$DOCSource		= "";
					$LastUpdate		= $UPDATED;
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= "";
					$RefType		= 'TTK';
					$PRJCODE		= $PRJCODE;
					$Journal_Amount	= $INV_AMOUNT_TOT;
					
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
										'SPLCODE'			=> $SPLCODE,
										'SPLDESC'			=> $SPLDESC,
										'Journal_Amount'	=> $Journal_Amount);
					$this->m_journal->createJournalH_NEW($JournalH_Code, $parameters);
				// END : JOURNAL HEADER

				// START : JOURNAL DETAIL : Hutang Lain(D) dan Hutang Supplier (K)
					$JournalH_Code	= $INV_NUM;
					$JournalType	= 'PINV';
					$JournalH_Date	= $INV_DATE;
					$Company_ID		= $comp_init;
					$Currency_ID	= 'IDR';
					$DOCSource		= "";
					$LastUpdate		= $UPDATER;
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= "";
					$RefType		= 'TTK';
					$JSource		= 'PINV';
					$PRJCODE		= $PRJCODE;
					
					$ITM_CODE 		= $INV_NUM;
					$ACC_ID 		= '';
					$ITM_UNIT 		= '';
					$ITM_QTY 		= 1;
					$ITM_PRICE		= $INV_AMOUNT;
					$ITM_DISC 		= 0;
					$TAXCODE1 		= '';
					$TAXPRICE1		= 0;
					$ITM_PRICE1		= $INV_AMOUNT_PPN;
					$ITM_PRICE2		= $INV_AMOUNT_PPH;
					$ITM_PRICE3		= $INV_AMOUNT_DPB;
					$ITM_PRICE4		= $INV_AMOUNT_RET;
					$ITM_PRICE5		= $INV_AMOUNT_POT;
					$ITM_PRICE6		= $INV_AMOUNT_OTH;
					$ITM_PRICE7		= $INV_AMOUNT_TOT;
					
					$TRANS_CATEG 	= "PINV_NEW~$SPLCAT";

					//$FM_TOTVAL= $TOTINV_ORI + $TOTINV_PPN - $TOTINV_POT + $INV_AMOUNT_OTH;
					$FM_TOTVAL	= $INV_AMOUNT_TOT;
					
					// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
					// HARUS DI KURANGI SAAT PEMBUATAN INVOICE, BERUBAH MENJADI HUTANG (AP)
						$this->load->model('m_updash/m_updash', '', TRUE);
						$paramFT = array('DOC_NUM' 		=> $INV_NUM,
										'DOC_DATE' 		=> $INV_DATE,
										'DOC_EDATE' 	=> $INV_DUEDATE,
										'PRJCODE' 		=> $PRJCODE,
										'FIELD_NAME1' 	=> 'FT_AP',
										'FIELD_NAME2' 	=> 'FM_AP',
										'TOT_AMOUNT'	=> $FM_TOTVAL);
						$this->m_updash->finTrack($paramFT);
					// END : TRACK FINANCIAL TRACK
			
					// MENGURANGI ATAS TTK
						foreach($_POST['data'] as $d)
						{
							$TTK_NUM	= $d['TTK_NUM'];
							$TTK_NUM	= $d['TTK_NUM'];
							$sqlTTK		= "SELECT TTK_DATE, TTK_DUEDATE, PRJCODE, TTK_AMOUNT
											FROM tbl_ttk_header
											WHERE TTK_NUM = '$TTK_NUM' LIMIT 1";
							$resTTK		= $this->db->query($sqlTTK)->result();
							foreach($resTTK as $rowTTK):
								$TTK_DATE		= $rowTTK->TTK_DATE;
								$TTK_DUEDATE	= $rowTTK->TTK_DUEDATE;
								$PRJCODEX		= $rowTTK->PRJCODE;
								$TTK_AMOUNTX	= $rowTTK->TTK_AMOUNT;
								
								// MENGURANGI NILAI FT_COP SAAT TTK
									$this->load->model('m_updash/m_updash', '', TRUE);
									$paramFT 	= array('DOC_NUM' 		=> $TTK_NUM,
														'DOC_DATE' 		=> $TTK_DATE,
														'DOC_EDATE' 	=> $TTK_DUEDATE,
														'PRJCODE' 		=> $PRJCODEX,
														'FIELD_NAME1' 	=> 'FT_COPM',
														'FIELD_NAME2' 	=> 'FM_COPM',
														'TOT_AMOUNT'	=> $TTK_AMOUNTX);
									$this->m_updash->finTrack($paramFT);
								// END : TRACK FINANCIAL TRACK
							endforeach;
						}

					// START : CREATE JOURNAL DETAIL
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Date' 	=> $JournalH_Date,
											'Company_ID' 		=> $comp_init,
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
											'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV_NEW~$SPLCAT
											'ITM_CODE' 			=> $ITM_CODE,
											'ACC_ID' 			=> $ACC_ID,
											'ITM_UNIT' 			=> $ITM_UNIT,
											'ITM_QTY' 			=> $ITM_QTY,
											'ITM_PRICE' 		=> $ITM_PRICE,
											'ITM_DISC' 			=> $ITM_DISC,
											'TAXCODE1' 			=> $TAXCODE1,
											'TAXPRICE1' 		=> $TAXPRICE1,
											'INV_AMOUNT' 		=> $INV_AMOUNT,
											'INV_AMOUNT_PPN' 	=> $INV_AMOUNT_PPN,
											'INV_AMOUNT_PPH'	=> $INV_AMOUNT_PPH,
											'INV_AMOUNT_DPB'	=> $INV_AMOUNT_DPB,
											'INV_AMOUNT_RET'	=> $INV_AMOUNT_RET,
											'INV_AMOUNT_POT'	=> $INV_AMOUNT_POT,
											'INV_AMOUNT_OTH'	=> $INV_AMOUNT_OTH,
											'INV_AMOUNT_POTUM'	=> $INV_AMOUNT_POTUM,
											'INV_AMOUNT_TOT'	=> $INV_AMOUNT_TOT,
											'Ref_Number'		=> $INV_CODE,
											'Other_Desc'		=> $INV_NOTES,
											'ITEM_TOT'			=> $INV_AMOUNT_TOT,
											'ITEM_PPN'			=> $INV_AMOUNT_PPN,
											'ITEM_RET'			=> $INV_AMOUNT_RET,
											'ITEM_POT'			=> $INV_AMOUNT_POT,
											'INV_PPN' 			=> $INV_PPN,
											'INV_PPH'			=> $INV_PPH,
											'SPL_CATEG'			=> $SPLCAT,
											'REF_CODE'			=> $INV_CODE,
											'INV_ACC_OTH'		=> $INV_ACC_OTH,
											'UM_REF'			=> $UM_COLL);
						$this->m_journal->createJournalD($JournalH_Code, $parameters);
					// END : CREATE JOURNAL DETAIL
				// END : JOURNAL DETAIL

				// START : UPDATE STAT DET
					$this->load->model('m_updash/m_updash', '', TRUE);				
					$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
					$this->m_updash->updSTATJD($paramSTAT);
				// END : UPDATE STAT DET

				/*$parameters = array('INV_STAT' 	=> $INV_STAT,
									'PO_NUM' 	=> $PO_NUM,
									'IR_NUM' 	=> $IR_NUM,
									'PRJCODE' 	=> $PRJCODE);
				$this->m_purchase_inv->updatePO_RR($INV_NUM, $parameters);*/
				
				// CREATE INVOICE RETENSI : DI-HOLD KARENA JURNAL RETENSI SUDAH TERBENTUK SAAT OPNAME
					if($INV_AMOUNT_RET > 0)	// HOLDED : 26 11 2021 
					{
						$INV_NUMRET		= "$INV_NUM-RET";
						$INV_CODERET	= "$INV_CODE-RET";
						$INV_NOTESRET	= "Invoice Retensi";
						// START : CREATE INVOICE
							$insertINV 	= array('INV_NUM' 			=> $INV_NUMRET,
												'INV_CODE'			=> $INV_CODERET,
												'INV_TYPE'			=> $INV_TYPE,
												'INV_CATEG'			=> "$INV_CATEG-RET",
												'PO_NUM'			=> "",
												'IR_NUM'			=> $IR_NUM,
												'PRJCODE'			=> $PRJCODE,
												'INV_DATE'			=> $INV_DATE,
												'INV_DUEDATE'		=> $INV_DUEDATE,
												'SPLCODE'			=> $SPLCODE,
												'DP_NUM'			=> '',
												'DP_AMOUNT'			=> 0,
												'INV_CURRENCY'		=> $INV_CURRENCY,
												'INV_TAXCURR'		=> $INV_TAXCURR,
												'INV_AMOUNT'		=> $INV_AMOUNT_RET,
												'INV_AMOUNT_RET'	=> 0,
												'INV_LISTTAXVAL'	=> 0,
												'INV_PPH'			=> '',
												'INV_PPHVAL'		=> 0,
												'INV_STAT'			=> $INV_STAT,
												'INV_PAYSTAT'		=> $INV_PAYSTAT,
												'COMPANY_ID'		=> $comp_init,
												'VENDINV_NUM'		=> $VENDINV_NUM,
												'INV_NOTES'			=> $INV_NOTESRET,
												'CREATED'			=> $UPDATED,
												'CREATER'			=> $UPDATER);
							// HOLDED : 26 11 2021 $this->m_purchase_inv->add($insertINV);
							
							$ACC_NUM1	= '';
							$sqlL_K		= "SELECT LA_ACCID FROM tbl_link_account WHERE LA_ITM_CODE = '$SPLCAT' AND LA_CATEG = 'RET' AND LA_DK = 'K'";
							$resL_K 	= $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM1	= $rowL_K->LA_ACCID;
							endforeach;
							foreach($_POST['data1'] as $dc1)
							{
								$dc1['INV_NUM']			= $INV_NUMRET;
								$dc1['INV_CODE']		= $INV_CODERET;
								$dc1['ACC_ID']			= $ACC_NUM1;
								$dc1['ITM_UNITP']		= $INV_AMOUNT_RET;
								$dc1['ITM_UNITP_BASE']	= $INV_AMOUNT_RET;
								$dc1['ITM_AMOUNT']		= $INV_AMOUNT_RET;
								$dc1['ITM_AMOUNT_BASE']	= $INV_AMOUNT_RET;
								$dc1['ITM_AMOUNT1']		= $INV_AMOUNT_RET;
								// HOLDED : 26 11 2021 $this->db->insert('tbl_pinv_detail',$dc1);
							}
						// END : CREATE INVOICE
		
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "INV_NUM",
													'DOC_CODE' 		=> $INV_NUMRET,
													'DOC_STAT' 		=> $INV_STAT,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> $completeName,
													'TBLNAME'		=> "tbl_pinv_header");
							// HOLDED : 26 11 2021 $this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}

				// START : SAVE APPROVE HISTORY
					$AH_ISLAST		= $this->input->post('IS_LAST');
					$AH_APPLEV		= $this->input->post('APP_LEVEL');

					$AH_CODE		= $INV_NUM;
					$AH_APPLEV		= $AH_APPLEV;
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= date('Y-m-d H:i:s');
					$AH_NOTES		= addslashes($this->input->post('INV_NOTES'));

					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('PRJCODE'		=> $PRJCODE,
											'AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
			}
			if($INV_STAT == 4) // CLOSE
			{
				$updINV 	= array('INV_STAT'		=> $INV_STAT,
									'INV_NOTES'		=> $INV_NOTES,
									'INV_NOTES1'	=> $INV_NOTES1);
				$this->m_purchase_inv->updateINV($INV_NUM, $updINV);
			}
			elseif($INV_STAT == 6) // CLOSE
			{
				$updINV 	= array('INV_STAT'		=> $INV_STAT,
									'INV_NOTES1'	=> $INV_NOTES1);
				$this->m_purchase_inv->updateINV($INV_NUM, $updINV);
			}

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "INV_NUM",
										'DOC_CODE' 		=> $INV_NUM,
										'DOC_STAT' 		=> $INV_STAT,
										'PRJCODE' 		=> $PRJCODE,
										//'CREATERNM'	=> $completeName,
										'TBLNAME'		=> "tbl_pinv_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $INV_NUM);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
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
										'FIELD_NM_A'	=> "TOT_PINV_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_PINV_HP",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_PINV_R",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_PINV_FP");		// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
				
				$parameters 	= array('PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashStatDoc($parameters);
			// END : UPDATE ACUM. TO DASHBOARD
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $INV_NUM;
				$MenuCode 		= 'MN009';
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
			
			$url			= site_url('c_purchase/c_pi180c23/gall180c23inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
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
			
			$getINV			= $this->m_purchase_inv->get_INV_by_number($INV_NUM)->row();
			
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

			//create by iyan
            $data['countSUPL'] 	= $this->m_purchase_inv->count_all_vendUP($SPLCODE);
            $data['vwSUPL'] 	= $this->m_purchase_inv->view_all_vendUP($SPLCODE)->result();
            // ----------------------------------------------------------------------------//

			$this->load->view('v_purchase/v_purchase_inv/print_inv', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printTTK()
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
			
			$getINV				= $this->m_purchase_inv->get_ttk_by_number($TTK_NUM)->row();
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
			$data['default']['TTK_AMOUNT_RET'] 	= $getINV->TTK_AMOUNT_RET;
			$data['default']['TTK_AMOUNT_POT'] 	= $getINV->TTK_AMOUNT_POT;
			$data['default']['TTK_GTOTAL'] 		= $getINV->TTK_GTOTAL;
			$data['default']['TTK_CATEG'] 		= $getINV->TTK_CATEG;
			$data['default']['PRJCODE'] 		= $getINV->PRJCODE;
			$PRJCODE							= $getINV->PRJCODE;
			$data['default']['SPLCODE'] 		= $getINV->SPLCODE;
			$SPLCODE							= $getINV->SPLCODE;
			$data['default']['TTK_STAT'] 		= $getINV->TTK_STAT;
			$data['default']['Patt_Number'] 	= $getINV->Patt_Number;
			
			$countTKP 							= $this->m_purchase_inv->count_ttkp_by_number($TTK_NUM);
			$getTTKP							= $this->m_purchase_inv->get_ttkp_by_number($TTK_NUM)->row();
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
			$data['countSUPL'] 		= $this->m_purchase_inv->count_allVend($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_allVend($PRJCODE, $SPLCODE)->result();
			
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
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
			
			$getINV				= $this->m_purchase_inv->get_ttk_by_number($TTK_NUM)->row();
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
			$data['default']['TTK_AMOUNT_RET'] 	= $getINV->TTK_AMOUNT_RET;
			$data['default']['TTK_AMOUNT_POT'] 	= $getINV->TTK_AMOUNT_POT;
			$data['default']['TTK_GTOTAL'] 		= $getINV->TTK_GTOTAL;
			$data['default']['TTK_CATEG'] 		= $getINV->TTK_CATEG;
			$data['default']['PRJCODE'] 		= $getINV->PRJCODE;
			$PRJCODE							= $getINV->PRJCODE;
			$data['default']['SPLCODE'] 		= $getINV->SPLCODE;
			$SPLCODE							= $getINV->SPLCODE;
			$data['default']['TTK_STAT'] 		= $getINV->TTK_STAT;
			$data['default']['Patt_Number'] 	= $getINV->Patt_Number;
			
			$countTKP 							= $this->m_purchase_inv->count_ttkp_by_number($TTK_NUM);
			$getTTKP							= $this->m_purchase_inv->get_ttkp_by_number($TTK_NUM)->row();
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
			$data['countSUPL'] 		= $this->m_purchase_inv->count_allVend($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_allVend($PRJCODE, $SPLCODE)->result();
			
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
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_purchase/c_pi180c23/i1dah80Idx/?id='.$this->url_encryption_helper->encode_url($appName));
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
			
		// GET MENU DESC
			$mnCode				= 'MN338';
			$data["MenuApp"] 	= 'MN338';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN338';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_pi180c23/galli180dah/?id=";
			
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
	
	function galli180dah() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN338';
			$data["MenuApp"] 	= 'MN338';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 		= $this->session->userdata('Emp_ID');

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
				$data["url_search"] = site_url('c_purchase/c_pi180c23/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//sss$num_rows 			= $this->m_purchase_inv->count_all_ttk($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_purchase_inv->get_all_ttk($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
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
			$data['backURL'] 	= site_url('c_purchase/c_pi180c23/i180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
						
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
	
	function i180dah_partn() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
		// GET MENU DESC
			$mnCode				= 'MN005';
			$data["MenuApp"] 	= 'MN005';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = "Daftar Proyek";
			else
				$data["mnName"] = "Project List";

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN005';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_pi180c23/galli180dah_partn/?id=";
			$data["SPLCODE"] 	= $num_rows;
			
			$data["secVIEW"]	= 'v_projectlist/project_list_partn';
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
	
	function galli180dah_partn() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN338';
			$data["MenuApp"] 	= 'MN338';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 		= $this->session->userdata('Emp_ID');

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
				$data["url_search"] = site_url('c_purchase/c_pi180c23/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//sss$num_rows 			= $this->m_purchase_inv->count_all_ttk($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_purchase_inv->get_all_ttk($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
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
			$data['backURL'] 	= site_url('c_purchase/c_pi180c23/i180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
						
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
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_purchase/c_pi180c23/galli180dah/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllDataTTK() // GOOD
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
			
			$columns_valid 	= array("TTK_CODE",
									"TTK_DATE",
									"TTK_DUEDATE",
									"B.SPLDESC",
									"TTK_NOTES",
									"TTK_GTOTAL",
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
			$num_rows 		= $this->m_purchase_inv->get_AllDataTTKC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_inv->get_AllDataTTKL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$TTK_NUM		= $dataI['TTK_NUM'];
				$TTK_CODE		= $dataI['TTK_CODE'];
				
				$TTK_DATE		= $dataI['TTK_DATE'];
				$TTK_DATEV		= date('d M Y', strtotime($TTK_DATE));
				
				$TTK_DUEDATE	= $dataI['TTK_DUEDATE'];
				$TTK_DUEDATEV	= date('d M Y', strtotime($TTK_DUEDATE));
				
				$TTK_AMOUNT		= $dataI['TTK_AMOUNT'];
				$TTK_AMOUNT_RET	= $dataI['TTK_AMOUNT_RET'];
				$TTK_AMOUNT_PPN	= $dataI['TTK_AMOUNT_PPN'];
				$TTK_AMOUNT_POT	= $dataI['TTK_AMOUNT_POT'];
				$TTK_GTOTAL		= $dataI['TTK_GTOTAL'];
				//$TTK_GTOTALA	= $TTK_AMOUNT - $TTK_AMOUNT_RET - $TTK_AMOUNT_POT;
				$TTK_GTOTALA	= $TTK_GTOTAL;
				$TTK_GTOTAL		= number_format($TTK_GTOTALA,2);
				//$TTK_GTOTAL		= number_format($dataI['TTK_GTOTAL'],2);
				$TTK_NOTES		= $dataI['TTK_NOTES'];
				$TTK_STAT		= $dataI['TTK_STAT'];
				$PRJCODE		= $dataI['PRJCODE'];
				$SPLCODE		= $dataI['SPLCODE'];				
				$SPLDESC		= $dataI['SPLDESC'];
				/*$sqlSPL			= "SELECT SPLDESC FROM tbl_supplier A WHERE SPLCODE = '$SPLCODE'";
				$ressqlSPL 		= $this->db->query($sqlSPL)->result();
				
				foreach($ressqlSPL as $rowSPL) :
					$SPLDESC 	= $rowSPL->SPLDESC;
				endforeach;*/
				
				$TTK_CATEG		= $dataI['TTK_CATEG'];
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				if($TTK_CATEG == 'OTH')
				{
					$secUpd		= site_url('c_purchase/c_pi180c23/u_4te77k_dir/?id='.$this->url_encryption_helper->encode_url($TTK_NUM));
				}
				else
				{
					$secUpd		= site_url('c_purchase/c_pi180c23/u_4te77k/?id='.$this->url_encryption_helper->encode_url($TTK_NUM));
				}
				$secPrint		= site_url('c_purchase/c_pi180c23/printTTK/?id='.$this->url_encryption_helper->encode_url($TTK_NUM));
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDelIcut~tbl_ttk_header~tbl_ttk_detail~TTK_NUM~$TTK_NUM~PRJCODE~$PRJCODE";
				$secVoid 		= base_url().'index.php/__l1y/trashTTK/?id=';
				$voidID 		= "$secVoid~tbl_ttk_header~tbl_ttk_detail_itm~TTK_NUM~$TTK_NUM~PRJCODE~$PRJCODE";
                                    
				if($TTK_STAT == 1 || $TTK_STAT == 4)
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
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				elseif($TTK_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
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
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				
				$output['data'][] = array($dataI['TTK_CODE'],
										  $TTK_DATEV,
										  $TTK_DUEDATEV,
										  $SPLDESC,
										  $TTK_NOTES,
										  $TTK_GTOTAL,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function i180dahdd() // OK - TTK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN338';
			$data["MenuApp"] 	= 'MN338';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_purchase/c_pi180c23/addttk_process');
			
			// Untuk penomoran secara systemik
			$SPLCODE				= '';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_purchase_inv->count_allVend($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_allVend($PRJCODE, $SPLCODE)->result();
			
			$MenuCode 				= 'MN338';
			$data["MenuCode"] 		= 'MN338';
			$data['viewDocPattern'] = $this->m_purchase_inv->getDataDocPat($MenuCode)->result();
			
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
			$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
			$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
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
				if($TTK_CATEG == 'IR')
				{
					$data["h2_title"] 	= "Daftar Penerimaan";
					$data["h3_title"]	= "Penerimaan Barang";
				}
				elseif($TTK_CATEG == 'OPN')
				{
					$data["h2_title"] 	= "Daftar Opname";
					$data["h3_title"]	= "Opname";
				}
				else
				{
					$data["h2_title"] 	= "Lainnya";
					$data["h3_title"]	= "Lainnya";
				}
			}
			else
			{
				if($TTK_CATEG == 'IR')
				{
					$data["h2_title"] 	= "Item Receipt List";
					$data["h3_title"]	= "Receiving Goods";
				}
				elseif($TTK_CATEG == 'OPN')
				{
					$data["h2_title"] 	= "Opname List";
					$data["h3_title"]	= "Opname List";
				}
				else
				{
					$data["h2_title"] 	= "Others";
					$data["h3_title"]	= "Others";
				}
			}
			
			$data['TTK_CATEG'] 		= $TTK_CATEG;	
			$data['PRJCODE'] 		= $PRJCODE;			
			$data['countAllIR'] 	= $this->m_purchase_inv->count_all_IRTTK($SPLCODE, $PRJCODE, $TTK_CATEG);
			$data['viewAllIR'] 		= $this->m_purchase_inv->viewAllIRTTK($SPLCODE, $PRJCODE, $TTK_CATEG)->result();

			$this->load->view('v_purchase/v_purchase_ttk/v_ttk_sel_source', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function popupall_TTK() // OK 
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
		
		$data['countAllTTK'] 	= $this->m_purchase_inv->count_allTTK($SPLCODE, $PRJCODE);
		$data['viewAllTTK'] 	= $this->m_purchase_inv->view_allTTK($SPLCODE, $PRJCODE)->result();
				
		$this->load->view('v_purchase/v_purchase_inv/v_pinv_sel_ttk', $data);
	}
	
	function addttk_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
			$TTK_AMOUNT_PPH	= $this->input->post('TTK_AMOUNT_PPH');
			$TTK_AMOUNT_DPB	= $this->input->post('TTK_AMOUNT_DPB');
			$TTK_AMOUNT_RET	= $this->input->post('TTK_AMOUNT_RET');
			$TTK_AMOUNT_POT	= $this->input->post('TTK_AMOUNT_POT');
			$TTK_AMOUNT_OTH	= $this->input->post('TTK_AMOUNT_OTH');
			$TTK_GTOTAL		= $this->input->post('TTK_GTOTAL');
			$TTK_ACC_OTH	= $this->input->post('TTK_ACC_OTH');
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
								'TTK_AMOUNT_PPH'=> $TTK_AMOUNT_PPH,
								'TTK_AMOUNT_DPB'=> $TTK_AMOUNT_DPB,
								'TTK_AMOUNT_RET'=> $TTK_AMOUNT_RET,
								'TTK_AMOUNT_POT'=> $TTK_AMOUNT_POT,
								'TTK_AMOUNT_OTH'=> $TTK_AMOUNT_OTH,
								'TTK_GTOTAL'	=> $TTK_GTOTAL,
								'TTK_ACC_OTH'	=> $TTK_ACC_OTH,
								'TTK_STAT'		=> $TTK_STAT,
								'PRJCODE'		=> $PRJCODE,
								'SPLCODE'		=> $SPLCODE,
								'TTK_CREATER'	=> $TTK_CREATER,
								'TTK_CREATED'	=> $TTK_CREATED,
								'Patt_Year'		=> $Patt_Year,
								'Patt_Number'	=> $Patt_Number);
			$this->m_purchase_inv->addTTK($insertTTK);
			
			if($TTK_STAT == 9)
			{
				// UNTUK KEPERLUAN FINANCIAL TRACK (SEHINGGA TIDAK PERLU LAGI SAAT OPNAME ATAU IR)
				// NILAI HUTANG SEBENARNYA = NILAI TTK (SBL DIPOTONG) + PPN - POTONGAN;
				// SAMA DENGAN TTK_AMOUNT = (TTK_VAL + PPN - POT - RET - PPH) + RET + PPH;
				$TTK_AMOUNT = $TTK_AMOUNT + $TTK_AMOUNT_PPN + $TTK_AMOUNT_OTH - $TTK_AMOUNT_POT;
				// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
				// HARUS DI KURANGI SAAT PEMBUATAN INVOICE, BERUBAH MENJADI HUTANG (AP)
					$this->load->model('m_updash/m_updash', '', TRUE);
					$paramFT = array('DOC_NUM' 		=> $TTK_NUM,
									'DOC_DATE' 		=> $TTK_DATE,
									'DOC_EDATE' 	=> $TTK_DUEDATE,
									'PRJCODE' 		=> $PRJCODE,
									'FIELD_NAME1' 	=> 'FT_TTKM',			// Pengurangan
									'FIELD_NAME2' 	=> 'FM_TTKM',
									'TOT_AMOUNT'	=> $TTK_AMOUNT);
					$this->m_updash->VfinTrack($paramFT);
				// END : TRACK FINANCIAL TRACK
			}
			else
			{
				if($TTK_STAT == 3)	
				{
					// UNTUK KEPERLUAN FINANCIAL TRACK (SEHINGGA TIDAK PERLU LAGI SAAT OPNAME ATAU IR)
					// NILAI HUTANG SEBENARNYA = NILAI TTK (SBL DIPOTONG) + PPN - POTONGAN;
					// SAMA DENGAN TTK_AMOUNT = (TTK_VAL + PPN - POT - RET - PPH) + RET + PPH;
					$TTK_AMOUNT = $TTK_AMOUNT + $TTK_AMOUNT_PPN + $TTK_AMOUNT_OTH - $TTK_AMOUNT_POT;
					// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
					// HARUS DI KURANGI SAAT PEMBUATAN INVOICE, BERUBAH MENJADI HUTANG (AP)
						$this->load->model('m_updash/m_updash', '', TRUE);
						$paramFT = array('DOC_NUM' 		=> $TTK_NUM,
										'DOC_DATE' 		=> $TTK_DATE,
										'DOC_EDATE' 	=> $TTK_DUEDATE,
										'PRJCODE' 		=> $PRJCODE,
										'FIELD_NAME1' 	=> 'FT_TTK',
										'FIELD_NAME2' 	=> 'FM_TTK',
										'TOT_AMOUNT'	=> $TTK_AMOUNT);
						$this->m_updash->finTrack($paramFT);
					// END : TRACK FINANCIAL TRACK
				}
				
				foreach($_POST['data'] as $d)
				{
					$TTK_NUM 		= $TTK_NUM;
					$TTK_REF1_NUM 	= $d['TTK_REF1_NUM'];
					$d['TTK_NUM']	= $TTK_NUM;
					$TTKD_STAT 		= 1; 											// 0 = Not invoice, 1 = Invoiced
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
					

					$d['TTK_CODE']	= $TTK_CODE;
					$d['PRJCODE']	= $PRJCODE;
					$this->db->insert('tbl_ttk_detail',$d);
					
					if($TTK_STAT == 3)
					{
						if($TTK_CATEG == 'IR')
						{
							$updIR 	= array('IR_NUM'		=> $TTK_REF1_NUM,
											'TTK_CREATED' 	=> 1);												
							$this->m_purchase_inv->updIR($TTK_REF1_NUM, $updIR);
						}
						else if($TTK_CATEG == 'OPN')
						{
							$updOPN = array('TTK_CREATED' 	=> 1);												
							$this->m_purchase_inv->updOPN($TTK_REF1_NUM, $updOPN);
						}
						else if($TTK_CATEG == 'OTH')
						{
							$updOTH = array('TTK_CREATED' 	=> 1);												
							$this->m_purchase_inv->updOTH($TTK_REF1_NUM, $updOTH);
						}
					}
				}
			}
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "TTK_NUM",
										'DOC_CODE' 		=> $TTK_NUM,
										'DOC_STAT' 		=> $TTK_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ttk_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
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
			
			$url			= site_url('c_purchase/c_pi180c23/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u_4te77k() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN338';
			$data["MenuApp"] 	= 'MN338';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$TTK_NUM	= $_GET['id'];
			$TTK_NUM	= $this->url_encryption_helper->decode_url($TTK_NUM);
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pi180c23/updatettk_process');
			
			$MenuCode 			= 'MN338';
			$data["MenuCode"] 	= 'MN338';
			
			$getINV				= $this->m_purchase_inv->get_ttk_by_number($TTK_NUM)->row();
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
			$data['default']['TTK_AMOUNT_PPH'] 	= $getINV->TTK_AMOUNT_PPH;
			$data['default']['TTK_AMOUNT_DPB'] 	= $getINV->TTK_AMOUNT_DPB;
			$data['default']['TTK_AMOUNT_RET'] 	= $getINV->TTK_AMOUNT_RET;
			$data['default']['TTK_AMOUNT_POT'] 	= $getINV->TTK_AMOUNT_POT;
			$data['default']['TTK_AMOUNT_OTH'] 	= $getINV->TTK_AMOUNT_OTH;
			$data['default']['TTK_GTOTAL'] 		= $getINV->TTK_GTOTAL;
			$data['default']['TTK_ACC_OTH'] 	= $getINV->TTK_ACC_OTH;
			$data['default']['TTK_CATEG'] 		= $getINV->TTK_CATEG;
			$data['default']['PRJCODE'] 		= $getINV->PRJCODE;
			$PRJCODE							= $getINV->PRJCODE;
			$data['default']['SPLCODE'] 		= $getINV->SPLCODE;
			$SPLCODE							= $getINV->SPLCODE;
			$data['default']['TTK_STAT'] 		= $getINV->TTK_STAT;
			$data['default']['Patt_Number'] 	= $getINV->Patt_Number;
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_purchase_inv->count_allVend($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_allVend($PRJCODE, $SPLCODE)->result();
			
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
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
			$TTK_AMOUNT_PPH	= $this->input->post('TTK_AMOUNT_PPH');
			$TTK_AMOUNT_DPB	= $this->input->post('TTK_AMOUNT_DPB');
			$TTK_AMOUNT_RET	= $this->input->post('TTK_AMOUNT_RET');
			$TTK_AMOUNT_POT	= $this->input->post('TTK_AMOUNT_POT');
			$TTK_AMOUNT_OTH	= $this->input->post('TTK_AMOUNT_OTH');
			$TTK_GTOTAL		= $this->input->post('TTK_GTOTAL');
			$TTK_ACC_OTH	= $this->input->post('TTK_ACC_OTH');
			$TTK_STAT 		= $this->input->post('TTK_STAT');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			
			$TTK_CREATER	= $DefEmp_ID;
			
			if($TTK_STAT == 9)
			{
				$updateTTK		= array('TTK_STAT'		=> $TTK_STAT);
				$this->m_purchase_inv->updateTTKCLS($TTK_NUM, $updateTTK);
						
				// UNTUK KEPERLUAN FINANCIAL TRACK (SEHINGGA TIDAK PERLU LAGI SAAT OPNAME ATAU IR)
				// NILAI HUTANG SEBENARNYA = NILAI TTK (SBL DIPOTONG) + PPN - POTONGAN;
				// SAMA DENGAN TTK_AMOUNT = (TTK_VAL + PPN - POT - RET - PPH) + RET + PPH;
					//$TTK_AMOUNT 	= $TTK_AMOUNT + $TTK_AMOUNT_PPN + $TTK_AMOUNT_OTH - $TTK_AMOUNT_POT;

				// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
				// HARUS DI KURANGI SAAT PEMBUATAN INVOICE, BERUBAH MENJADI HUTANG (AP)
					$this->load->model('m_updash/m_updash', '', TRUE);
					$paramFT = array('DOC_NUM' 		=> $TTK_NUM,
									'DOC_DATE' 		=> $TTK_DATE,
									'DOC_EDATE' 	=> $TTK_DUEDATE,
									'PRJCODE' 		=> $PRJCODE,
									'FIELD_NAME1' 	=> 'FT_COP',
									'FIELD_NAME2' 	=> 'FM_COP',
									'TOT_AMOUNT'	=> $TTK_AMOUNT);
					$this->m_updash->VfinTrack($paramFT);
				// END : TRACK FINANCIAL TRACK
			}
			elseif($TTK_STAT == 3)
			{
				$updateTTK 		= array('TTK_CODE'		=> $TTK_CODE,
										'TTK_DATE'		=> $TTK_DATE,
										'TTK_DUEDATE'	=> $TTK_DUEDATE,
										'TTK_ESTDATE'	=> $TTK_ESTDATE,
										'TTK_CHECKER'	=> $TTK_CHECKER,
										'TTK_CATEG'		=> $TTK_CATEG,
										'TTK_NOTES'		=> $TTK_NOTES,
										'TTK_NOTES1'	=> $TTK_NOTES1,
										'TTK_AMOUNT'	=> $TTK_AMOUNT,
										'TTK_AMOUNT_PPN'=> $TTK_AMOUNT_PPN,
										'TTK_AMOUNT_PPH'=> $TTK_AMOUNT_PPH,
										'TTK_AMOUNT_DPB'=> $TTK_AMOUNT_DPB,
										'TTK_AMOUNT_RET'=> $TTK_AMOUNT_RET,
										'TTK_AMOUNT_POT'=> $TTK_AMOUNT_POT,
										'TTK_AMOUNT_OTH'=> $TTK_AMOUNT_OTH,
										'TTK_GTOTAL'	=> $TTK_GTOTAL,
										'TTK_ACC_OTH'	=> $TTK_ACC_OTH,
										'TTK_STAT'		=> $TTK_STAT,
										'PRJCODE'		=> $PRJCODE,
										'SPLCODE'		=> $SPLCODE);
				$this->m_purchase_inv->updateTTK($TTK_NUM, $updateTTK);
						
				// UNTUK KEPERLUAN FINANCIAL TRACK (SEHINGGA TIDAK PERLU LAGI SAAT OPNAME ATAU IR)
				// NILAI HUTANG SEBENARNYA = NILAI TTK (SBL DIPOTONG) + PPN - POTONGAN;
				// SAMA DENGAN TTK_AMOUNT = (TTK_VAL + PPN - POT - RET - PPH) + RET + PPH;
				$TTK_AMOUNT = $TTK_AMOUNT + $TTK_AMOUNT_PPN + $TTK_AMOUNT_OTH - $TTK_AMOUNT_POT;
					// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
					// HARUS DI KURANGI SAAT PEMBUATAN INVOICE, BERUBAH MENJADI HUTANG (AP)
						$this->load->model('m_updash/m_updash', '', TRUE);
						$paramFT = array('DOC_NUM' 		=> $TTK_NUM,
										'DOC_DATE' 		=> $TTK_DATE,
										'DOC_EDATE' 	=> $TTK_DUEDATE,
										'PRJCODE' 		=> $PRJCODE,
										'FIELD_NAME1' 	=> 'FT_COP',
										'FIELD_NAME2' 	=> 'FM_COP',
										'TOT_AMOUNT'	=> $TTK_AMOUNT);
						$this->m_updash->finTrack($paramFT);
					// END : TRACK FINANCIAL TRACK
				
				$this->m_purchase_inv->deleteTTKDet($TTK_NUM);	
			
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
							$updIR 	= array('IR_NUM'		=> $TTK_REF1_NUM,
											'TTK_CREATED' 	=> 1);												
							$this->m_purchase_inv->updIR($TTK_REF1_NUM, $updIR);
						}
						else if($TTK_CATEG == 'OPN')
						{
							$updOPN = array('TTK_CREATED' 	=> 1);												
							$this->m_purchase_inv->updOPN($TTK_REF1_NUM, $updOPN);
						}
						else if($TTK_CATEG == 'OTH')
						{
							$updOTH = array('TTK_CREATED' 	=> 1);												
							$this->m_purchase_inv->updOTH($TTK_REF1, $updOTH);
								
							// PREPARE TO CREATE JOURNAL UNTUK PERMINTAAN KEUANGAN TIPE "LAINNYA"
							$FPA_NUM	= $TTK_REF1;
							
							$sqlCFPA 	= "tbl_fpa_header WHERE FPA_NUM = '$FPA_NUM' AND FPA_STAT = 3";
							$resCFPA	= $this->db->count_all($sqlCFPA);
							
							if($resCFPA > 0)
							{
								// GET HEADER
									$this->load->model('m_finance/m_fpa/m_fpa', '', TRUE);
									$getFPA_H 						= $this->m_fpa->get_FPA_by_number($FPA_NUM)->row();
									$FPA_NUM 	= $getFPA_H->FPA_NUM;
									$FPA_DATE 	= $getFPA_H->FPA_DATE;
									$PRJCODE	= $getFPA_H->PRJCODE;
									$SPLCODE 	= $getFPA_H->SPLCODE;
									$SPLDESC	= '';
									$sqlSPL 	= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
									$resSPL		= $this->db->query($sqlSPL)->result();
									foreach($resSPL as $rowSPL):
										$SPLDESC= $rowSPL->SPLDESC;
									endforeach;
								
								// START : JOURNAL HEADER
									$this->load->model('m_journal/m_journal', '', TRUE);
									$JournalH_Code	= $FPA_NUM;
									$JournalType	= 'FPA-OTH';
									$JournalH_Date	= $FPA_DATE;
									$Company_ID		= $comp_init;
									$DOCSource		= 'FPA-OTH';
									$LastUpdate		= date('Y-m-d H:i:s');
									$WH_CODE		= $PRJCODE;
									$Refer_Number	= $FPA_NUM;
									$RefType		= 'WBSD';
									$PRJCODE		= $PRJCODE;
									
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
														'SPLCODE'			=> $SPLCODE,
														'SPLDESC'			=> $SPLDESC,
														'PRJCODE'			=> $PRJCODE);
									$this->m_journal->createJournalH_NEW($JournalH_Code, $parameters);
								// END : JOURNAL HEADER
								
								// START : JOURNAL DETAIL
									
									$TOT_AMOUNT			= 0;
									$TOT_AMOUNT_PPN		= 0;
									$TOT_AMOUNT_PPh		= 0;
									$sqlFPAD 	= "SELECT A.FPA_CODE, A.ITM_CODE, A.JOBCODEID, A.FPA_VOLM, A.ITM_PRICE,
														B.ITM_UNIT, B.ITM_GROUP, B.ITM_TYPE, B.ACC_ID_UM AS ACC_ID
													FROM tbl_fpa_detail A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
													WHERE A.FPA_NUM = '$FPA_NUM' AND A.PRJCODE = '$PRJCODE'";
									$resFPAD	= $this->db->query($sqlFPAD)->result();
									foreach($resFPAD as $rowFPAD):
										$FPA_CODE 		= $rowFPAD->FPA_CODE;
										$ITM_CODE 		= $rowFPAD->ITM_CODE;
										$JOBCODEID 		= $rowFPAD->JOBCODEID;
										$ACC_ID 		= $rowFPAD->ACC_ID;
										$ITM_UNIT 		= $rowFPAD->ITM_UNIT;
										$ITM_GROUP 		= $rowFPAD->ITM_GROUP;
										$ITM_TYPE 		= $rowFPAD->ITM_TYPE;
										$ITM_QTY 		= $rowFPAD->FPA_VOLM;
										$ITM_PRICE 		= $rowFPAD->ITM_PRICE;
										$ITM_DISC 		= 0;
										$TAXCODE1 		= '';
										$TAXPRICE1 		= 0;						
										$JournalH_Code	= $FPA_NUM;
										$JournalType	= 'FPA-OTH';
										$JournalH_Date	= $FPA_DATE;
										$Company_ID		= $comp_init;
										$Currency_ID	= 'IDR';
										$LastUpdate		= date('Y-m-d H:i:s');
										$WH_CODE		= $PRJCODE;
										$Refer_Number	= $FPA_CODE;
										$RefType		= 'FPA-OTH';
										$JSource		= 'FPA-OTH';
										$PRJCODE		= $PRJCODE;
										$TOT_PRICE		= $ITM_QTY * $ITM_PRICE;
										
										$parameters = array('JournalH_Code' 	=> $JournalH_Code,
															'JournalType'		=> $JournalType,
															'JournalH_Date' 	=> $JournalH_Date,
															'Company_ID' 		=> $comp_init,
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
															'TRANS_CATEG' 		=> 'FPA-OTH',			// REC = RECEIPT
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
									
										// UPDATE AMOUNT JOURNAL HEADER
											$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = Journal_Amount+$TOT_PRICE
															WHERE JournalH_Code = '$JournalH_Code'";
											$this->db->query($sqlUpdJH);
												
										// START : UPDATE STOCK AND JOBLIST
											$parameters1 = array('PRJCODE' 	=> $PRJCODE,
																'JOBCODEID'	=> $JOBCODEID,
																'UM_NUM' 	=> $FPA_NUM,
																'UM_CODE' 	=> $FPA_CODE,
																'ITM_CODE' 	=> $ITM_CODE,
																'ITM_QTY' 	=> $ITM_QTY,
																'ITM_PRICE' => $ITM_PRICE);
											$this->m_itemusage->updateITM_Min($parameters1);
										// START : UPDATE STOCK
										
										// START : RECORD TO ITEM HISTORY
											$this->m_journal->createITMHistMin($JournalH_Code, $parameters);
										// START : RECORD TO ITEM HISTORY
									endforeach;
								// END : JOURNAL DETAIL
							}
						}
					}
				}
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
										'TTK_AMOUNT_RET'=> $TTK_AMOUNT_RET,
										'TTK_AMOUNT_POT'=> $TTK_AMOUNT_POT,
										'TTK_AMOUNT_PPN'=> $TTK_AMOUNT_PPN,
										'TTK_AMOUNT_OTH'=> $TTK_AMOUNT_OTH,
										'TTK_ACC_OTH'	=> $TTK_ACC_OTH,
										'TTK_GTOTAL'	=> $TTK_GTOTAL,
										'TTK_STAT'		=> $TTK_STAT,
										'PRJCODE'		=> $PRJCODE,
										'SPLCODE'		=> $SPLCODE);
				$this->m_purchase_inv->updateTTK($TTK_NUM, $updateTTK);
				
				$this->m_purchase_inv->deleteTTKDet($TTK_NUM);	
			
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
					
					if($TTK_CATEG == 'IR')
					{
						$updIR 	= array('IR_NUM'		=> $TTK_REF1,
										'TTK_CREATED' 	=> 0);												
						$this->m_purchase_inv->updIR($TTK_REF1, $updIR);
					}
					else if($TTK_CATEG == 'OPN')
					{
						$updOPN = array('TTK_CREATED' 	=> 0);												
						$this->m_purchase_inv->updOPN($TTK_REF1, $updOPN);
					}
					else if($TTK_CATEG == 'OTH')
					{
						$updOTH = array('TTK_CREATED' 	=> 0);												
						$this->m_purchase_inv->updOTH($TTK_REF1, $updOTH);
					}
				}
			}
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "TTK_NUM",
										'DOC_CODE' 		=> $TTK_NUM,
										'DOC_STAT' 		=> $TTK_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ttk_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
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
			
			$url			= site_url('c_purchase/c_pi180c23/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
				
			// GET MENU DESC
				$mnCode				= 'MN338';
				$data["MenuApp"] 	= 'MN338';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_purchase/c_pi180c23/addttk_processdir');
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_purchase_inv->count_allVendDir($PRJCODE);
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_allvendDir($PRJCODE)->result();
			
			$MenuCode 				= 'MN338';
			$data["MenuCode"] 		= 'MN338';
			$data['viewDocPattern'] = $this->m_purchase_inv->getDataDocPat($MenuCode)->result();
			
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
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$TTK_CODE 		= $this->input->post('TTK_CODE');
			$TTK_DATE		= date('Y-m-d',strtotime($this->input->post('TTK_DATE')));
			$TTK_DUEDATE	= date('Y-m-d',strtotime($this->input->post('TTK_DUEDATE')));
			$TTK_CATEG 		= "OTH";
			$SPLCODE 		= $this->input->post('SPLCODE');
			$TTK_AMOUNT		= $this->input->post('TTK_AMOUNT');
			$TTK_AMOUNT_PPN	= $this->input->post('TTK_AMOUNT_PPN');
			$TTK_GTOTAL		= $this->input->post('TTK_GTOTAL');
			$TTK_NOTES 		= $this->input->post('TTK_NOTES');
			$TTK_STAT 		= $this->input->post('TTK_STAT');
			$STAT_BEFORE 	= $this->input->post('STAT_BEFORE');
			$TTK_CREATER	= $DefEmp_ID;
			$TTK_CREATED	= date('Y-m-d H:i:s');
			$Patt_Year		= date('Y',strtotime($this->input->post('TTK_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			$TTK_GRPCODE	= date('ymnHis');
			$TTK_NUM 		= "$PRJCODE.TTK$TTK_GRPCODE"."-D";
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN338';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				if($Pattern_Code != '')
					$TTK_NUM 	= "$PRJCODE.$Pattern_Code$TTK_GRPCODE"."-D";
				
			// END - PEMBENTUKAN GENERATE CODE

			$insertTTK 	= array('TTK_NUM' 		=> $TTK_NUM,
								'TTK_CODE'		=> $TTK_CODE,
								'TTK_DATE'		=> $TTK_DATE,
								'TTK_DUEDATE'	=> $TTK_DUEDATE,
								'TTK_CHECKER'	=> $DefEmp_ID,
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
			$this->m_purchase_inv->addTTK($insertTTK);
			
			// START : INSERT DETAIL
				//$this->db->insert('tbl_ttk_detail_itm',$data);
				$maxNo	= 0;
				$sqlMax = "SELECT MAX(TTK_ID) AS maxNo FROM tbl_ttk_detail_itm";
				$resMax = $this->db->query($sqlMax)->result();
				foreach($resMax as $rowMax) :
					$maxNo = $rowMax->maxNo;		
				endforeach;

				$COLPRREFNO 		= "";
				$rowDet 			= 0;
				$JOBIDH 			= "";
				foreach($_POST['data'] as $d)
				{
					$rowDet 		= $rowDet + 1;
					$maxNo			= $maxNo + 1;
					$d['TTK_ID']	= $maxNo;
					$d['TTK_NUM']	= $TTK_NUM;
					$d['TTK_CODE']	= $TTK_CODE;
					$d['TTK_DATE']	= $TTK_DATE;
					$d['PRJCODE']	= $PRJCODE;

					// GET HEADER
						$JOBID 		= $d['JOBCODEID'];
						$JOBIDH		= "";
						$JOBDSH 	= "";
							$sqlJID = "SELECT JOBCODEID AS JOBPARENT, JOBDESC FROM tbl_joblist_detail 
	                					WHERE JOBCODEID = (SELECT JOBPARENT FROM tbl_joblist_detail 
	                						WHERE JOBCODEID = '$JOBID' AND PRJCODE = '$PRJCODE' LIMIT 1)
	                					AND PRJCODE = '$PRJCODE' LIMIT 1";
							$reJID	= $this->db->query($sqlJID)->result();
							foreach($reJID as $rowID) :
								$JOBIDH	= $rowID->JOBPARENT;
								$JOBDSH	= $rowID->JOBDESC;
							endforeach;

						if($rowDet == 1)
							$COLPRREFNO = $JOBIDH;
						else
							$COLPRREFNO = "$COLPRREFNO~$JOBIDH";

					$d['JOBPARENT']		= $JOBIDH;
					$d['JOBPARDESC']	= $JOBDSH;
					$this->db->insert('tbl_ttk_detail_itm',$d);
				}
			// END : INSERT DETAIL
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "TTK_NUM",
										'DOC_CODE' 		=> $TTK_NUM,
										'DOC_STAT' 		=> $TTK_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ttk_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
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
			
			$url			= site_url('c_purchase/c_pi180c23/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u_4te77k_dir() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		// GET MENU DESC
			$mnCode				= 'MN338';
			$data["MenuApp"] 	= 'MN338';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$TTK_NUM	= $_GET['id'];
			$TTK_NUM	= $this->url_encryption_helper->decode_url($TTK_NUM);
			$EmpID 		= $this->session->userdata('Emp_ID');

			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pi180c23/updatettk_process_dir');
			
			// Untuk penomoran secara systemik
			$data['countSUPL'] 		= $this->m_purchase_inv->count_allVendDir();
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_allvendDir()->result();
			
			$MenuCode 				= 'MN338';
			$data["MenuCode"] 		= 'MN338';
			$data['viewDocPattern'] = $this->m_purchase_inv->getDataDocPat($MenuCode)->result();
			
			$getINV					= $this->m_purchase_inv->get_ttk_by_number($TTK_NUM)->row();
			$data['default']['TTK_NUM'] 		= $getINV->TTK_NUM;
			$data['default']['TTK_CODE'] 		= $getINV->TTK_CODE;
			$data['default']['TTK_DATE'] 		= $getINV->TTK_DATE;
			$data['default']['TTK_DUEDATE'] 	= $getINV->TTK_DUEDATE;
			$data['default']['TTK_ESTDATE'] 	= $getINV->TTK_ESTDATE;
			$data['default']['TTK_CHECKER'] 	= $getINV->TTK_CHECKER;
			$data['default']['TTK_NOTES'] 		= $getINV->TTK_NOTES;
			$data['default']['TTK_NOTES1'] 		= $getINV->TTK_NOTES1;
			$data['default']['TTK_AMOUNT'] 		= $getINV->TTK_AMOUNT;
			$data['default']['TTK_AMOUNT_RET'] 	= $getINV->TTK_AMOUNT_RET;
			$data['default']['TTK_AMOUNT_POT'] 	= $getINV->TTK_AMOUNT_POT;
			$data['default']['TTK_AMOUNT_PPN'] 	= $getINV->TTK_AMOUNT_PPN;
			$data['default']['TTK_AMOUNT_OTH'] 	= $getINV->TTK_AMOUNT_OTH;
			$data['default']['TTK_GTOTAL'] 		= $getINV->TTK_GTOTAL;
			$data['default']['TTK_ACC_OTH'] 	= $getINV->TTK_ACC_OTH;
			$data['default']['TTK_CATEG'] 		= $getINV->TTK_CATEG;
			$data['default']['PRJCODE'] 		= $getINV->PRJCODE;
			$PRJCODE							= $getINV->PRJCODE;
			$data['PRJCODE'] 					= $PRJCODE;
			$data['default']['SPLCODE'] 		= $getINV->SPLCODE;
			$SPLCODE							= $getINV->SPLCODE;
			$data['default']['TTK_STAT'] 		= $getINV->TTK_STAT;
			$data['default']['Patt_Number'] 	= $getINV->Patt_Number;
			
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
	
	function updatettk_process_dir() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$TTK_NUM 		= $this->input->post('TTK_NUM');
			$TTK_CODE 		= $this->input->post('TTK_CODE');
			$TTK_DATE		= date('Y-m-d',strtotime($this->input->post('TTK_DATE')));
			$TTK_DUEDATE	= date('Y-m-d',strtotime($this->input->post('TTK_DUEDATE')));
			$TTK_CATEG 		= "OTH";
			$SPLCODE 		= $this->input->post('SPLCODE');
			$TTK_AMOUNT		= $this->input->post('TTK_AMOUNT');
			$TTK_AMOUNT_PPN	= $this->input->post('TTK_AMOUNT_PPN');
			$TTK_GTOTAL		= $this->input->post('TTK_GTOTAL');
			$TTK_NOTES 		= $this->input->post('TTK_NOTES');
			$TTK_STAT 		= $this->input->post('TTK_STAT');
			$STAT_BEFORE 	= $this->input->post('STAT_BEFORE');

			$SPLDESC		= '';
			$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC 	= $rowSPL->SPLDESC;
			endforeach;

			$updateTTK		= array('TTK_NUM' 		=> $TTK_NUM,
									'TTK_CODE'		=> $TTK_CODE,
									'TTK_DATE'		=> $TTK_DATE,
									'TTK_DUEDATE'	=> $TTK_DUEDATE,
									'TTK_CHECKER'	=> $DefEmp_ID,
									'TTK_CATEG'		=> $TTK_CATEG,
									'TTK_NOTES'		=> $TTK_NOTES,
									'TTK_AMOUNT'	=> $TTK_AMOUNT,
									'TTK_AMOUNT_PPN'=> $TTK_AMOUNT_PPN,
									'TTK_GTOTAL'	=> $TTK_GTOTAL,
									'TTK_STAT'		=> $TTK_STAT,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE);
			$this->m_purchase_inv->updateTTK($TTK_NUM, $updateTTK);
				
			$this->m_purchase_inv->deleteTTKDet_dir($TTK_NUM);
			
			// START : INSERT DETAIL
				//$this->db->insert('tbl_ttk_detail_itm',$data);
				$maxNo	= 0;
				$sqlMax = "SELECT MAX(TTK_ID) AS maxNo FROM tbl_ttk_detail_itm";
				$resMax = $this->db->query($sqlMax)->result();
				foreach($resMax as $rowMax) :
					$maxNo = $rowMax->maxNo;		
				endforeach;

				$COLPRREFNO 		= "";
				$rowDet 			= 0;
				$JOBIDH 			= "";
				foreach($_POST['data'] as $d)
				{
					$rowDet 		= $rowDet + 1;
					$maxNo			= $maxNo + 1;
					$d['TTK_ID']	= $maxNo;
					$d['TTK_NUM']	= $TTK_NUM;
					$d['TTK_CODE']	= $TTK_CODE;
					$d['TTK_DATE']	= $TTK_DATE;
					$d['PRJCODE']	= $PRJCODE;

					// START : GET HEADER
						$JOBID 		= $d['JOBCODEID'];
						$JOBIDH		= "";
						$JOBDSH 	= "";
							$sqlJID = "SELECT JOBCODEID AS JOBPARENT, JOBDESC FROM tbl_joblist_detail 
	                					WHERE JOBCODEID = (SELECT JOBPARENT FROM tbl_joblist_detail 
	                						WHERE JOBCODEID = '$JOBID' AND PRJCODE = '$PRJCODE' LIMIT 1)
	                					AND PRJCODE = '$PRJCODE' LIMIT 1";
							$reJID	= $this->db->query($sqlJID)->result();
							foreach($reJID as $rowID) :
								$JOBIDH	= $rowID->JOBPARENT;
								$JOBDSH	= $rowID->JOBDESC;
							endforeach;

						if($rowDet == 1)
							$COLPRREFNO = $JOBIDH;
						else
							$COLPRREFNO = "$COLPRREFNO~$JOBIDH";
					// END : GET HEADER

					$d['JOBPARENT']		= $JOBIDH;
					$d['JOBPARDESC']	= $JOBDSH;
					$this->db->insert('tbl_ttk_detail_itm',$d);
				}
			// END : INSERT DETAIL

			if($TTK_STAT == 3)
			{
				// START : JOURNAL HEADER
					$JournalH_Code	= $TTK_NUM;
					$JournalType	= 'TTK-D';
					$JournalH_Date	= $TTK_DATE;
					$Company_ID		= $comp_init;
					$DOCSource		= 'TTK-D';
					$LastUpdate		= date('Y-m-d H:i:s');
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= $TTK_NUM;
					$RefType		= 'WBSD';
					$PRJCODE		= $PRJCODE;
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Date' 	=> $JournalH_Date,
										'JournalH_Desc'		=> $JOBDSH.". ".$TTK_NOTES,
										'Company_ID' 		=> $comp_init,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'RefType'			=> $RefType,
										'SPLCODE'			=> $SPLCODE,
										'SPLDESC'			=> $SPLDESC,
										'PRJCODE'			=> $PRJCODE);
					$this->m_journal->createJournalH_NEW($JournalH_Code, $parameters);
				// END : JOURNAL HEADER

				// START : JOURNAL DETAIL
					$TOT_AMOUNT			= 0;
					$TOT_AMOUNT_PPN		= 0;
					$TOT_AMOUNT_PPh		= 0;
					$sqlFPAD 			= "SELECT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC, A.ITM_CODE, A.TTK_VOLM, A.TTK_PRICE, A.TTK_TOTAL,
												A.TAXCODE1, A.TAXPRICE1, A.TTK_DESC,
												B.ITM_UNIT, B.ITM_GROUP, B.ITM_TYPE, B.ACC_ID_UM AS ACC_ID
											FROM tbl_ttk_detail_itm A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
													AND B.PRJCODE = '$PRJCODE'
											WHERE A.TTK_NUM = '$TTK_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resFPAD			= $this->db->query($sqlFPAD)->result();
					foreach($resFPAD as $rowFPAD):
						$JournalH_Code	= $TTK_NUM;
						$JournalType	= 'TTK-D';
						$JournalH_Date	= $TTK_DATE;
						$Company_ID		= $comp_init;
						$Currency_ID	= 'IDR';
						$DOCSource		= $TTK_NUM;
						$LastUpdate		= date('Y-m-d H:i:s');
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $TTK_CODE;
						$RefType		= 'WBD';
						$JSource		= 'TTK-D';
						$PRJCODE		= $PRJCODE;

						$TTK_CODE 		= $rowFPAD->TTK_CODE;
						$ITM_CODE 		= $rowFPAD->ITM_CODE;
						$JOBCODEID 		= $rowFPAD->JOBCODEID;
						$ACC_ID 		= $rowFPAD->ACC_ID;
						$ITM_UNIT 		= $rowFPAD->ITM_UNIT;
						$ITM_GROUP 		= $rowFPAD->ITM_GROUP;
						$ITM_TYPE 		= $rowFPAD->ITM_TYPE;
						$ITM_QTY 		= $rowFPAD->TTK_VOLM;
						$ITM_PRICE 		= $rowFPAD->TTK_PRICE;
						$ITM_DISC 		= 0;
						$TAXCODE1 		= $rowFPAD->TAXCODE1;
						$TAXPRICE1 		= $rowFPAD->TAXPRICE1;
						$TTK_TOTAL 		= $rowFPAD->TTK_TOTAL;
						$TTK_DESC 		= $rowFPAD->TTK_DESC;
						$parameters 	= array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $comp_init,
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
												'TRANS_CATEG' 		=> 'TTK-D',
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_GROUP' 		=> $ITM_GROUP,
												'ITM_TYPE' 			=> $ITM_TYPE,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'oth_reason'		=> $TTK_DESC);
						$this->m_journal->createJournalD($JournalH_Code, $parameters);
									
						// UPDATE AMOUNT JOURNAL HEADER
							$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = Journal_Amount+$TTK_TOTAL
											WHERE JournalH_Code = '$JournalH_Code'";
							$this->db->query($sqlUpdJH);

						$sqlUPITM 	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $ITM_QTY, UM_VOLM = UM_VOLM + $ITM_QTY,
											UM_AMOUNT = UM_AMOUNT + $TTK_TOTAL
										WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$this->db->query($sqlUPITM);

						// START : PENGGUNAAN MATERIAL / ANGGARAN
							$parameters1 	= array('PRJCODE' 	=> $PRJCODE,
													'JOBCODEID'	=> $JOBID,
													'UM_NUM' 	=> $TTK_NUM,
													'UM_CODE' 	=> $TTK_CODE,
													'ITM_CODE' 	=> $ITM_CODE,
													'ITM_QTY' 	=> $ITM_QTY,
													'ITM_PRICE' => $ITM_PRICE);
							$this->m_itemusage->updateBUDG_Min($parameters1);
						// END : PENGGUNAAN MATERIAL / ANGGARAN
					
						// START : RECORD TO ITEM HISTORY
							$this->m_journal->createITMHistMin($TTK_NUM, $parameters);
						// START : RECORD TO ITEM HISTORY
					endforeach;
				// END : JOURNAL DETAIL

				// START : PENAMBAHAN RENCANA HUTANG PADA FINANCIAL TRACK / MONITORING
					$this->load->model('m_updash/m_updash', '', TRUE);
					$paramFT = array('DOC_NUM' 		=> $TTK_NUM,
									'DOC_DATE' 		=> $TTK_DATE,
									'DOC_EDATE' 	=> $TTK_DUEDATE,
									'PRJCODE' 		=> $PRJCODE,
									'FIELD_NAME1' 	=> 'FT_TTK',
									'FIELD_NAME2' 	=> 'FM_TTK',
									'TOT_AMOUNT'	=> $TTK_AMOUNT);
					$this->m_updash->finTrack($paramFT);
				// END : PENAMBAHAN RENCANA HUTANG PADA FINANCIAL TRACK / MONITORING
			}

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "TTK_NUM",
										'DOC_CODE' 		=> $TTK_NUM,
										'DOC_STAT' 		=> $TTK_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ttk_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
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
			
			$url			= site_url('c_purchase/c_pi180c23/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180c23ddDir() // OK - INVOICING DIRECT
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
			$data['form_action']	= site_url('c_purchase/c_pi180c23/add_process');
			
			// Untuk penomoran secara systemik
			$SPLCODE				= '';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL'] 		= $this->m_purchase_inv->count_all_vendDir($PRJCODE, $SPLCODE);
			$data['vwSUPL'] 		= $this->m_purchase_inv->view_all_vendDir($PRJCODE, $SPLCODE)->result();
			
			$MenuCode 				= 'MN009';
			$data["MenuCode"] 		= 'MN009';
			$data['viewDocPattern'] = $this->m_purchase_inv->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN009';
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
	
			$this->load->view('v_purchase/v_purchase_inv/v_pinvDir_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function popupall_TTKdir() // OK
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
		
		$data['countAllTTK'] 	= $this->m_purchase_inv->count_allTTKdir($SPLCODE, $PRJCODE);
		$data['viewAllTTK'] 	= $this->m_purchase_inv->view_allTTKdir($SPLCODE, $PRJCODE)->result();
				
		$this->load->view('v_purchase/v_purchase_inv/v_pinvDir_sel_ttk', $data);
	}
		
	function popupall_DP() // G
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		
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
		
		$data['countAllDP'] 	= $this->m_purchase_inv->count_allDP($SPLCODE, $PRJCODE);
		$data['viewAllDP'] 	= $this->m_purchase_inv->view_allDP($SPLCODE, $PRJCODE)->result();
				
		$this->load->view('v_purchase/v_purchase_inv/v_pinv_sel_dp', $data);
	}

  	function get_AllDataITM() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$PRJCODE		= $_GET['id'];

		$LangID     	= $this->session->userdata['LangID'];
        
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
			$num_rows 		= $this->m_purchase_inv->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_inv->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
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

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_VOLMBG 	= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNTBG		= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$sqlITMNM		= "SELECT ITM_NAME, ITM_CODE_H, ITM_TYPE, ITM_UNIT
										FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' 
											AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_NAME	= $rowITMNM->ITM_NAME;			// 5
						$ITM_CODE_H	= $rowITMNM->ITM_CODE_H;
						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
						$ITM_UNIT	= $rowITMNM->ITM_UNIT;
					endforeach;

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

				// OTHER SETT
						if($disabledB == 0)
						{
							$chkBox		= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' onClick='pickThis1(this);'/>";
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
										  	<div style='font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JOBDESCH."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataIR() // GOOD
	{
		$collData	= $_GET['id'];
		$splitCode 	= explode("~", $collData);
		$PRJCODE	= $splitCode[0];
		$SPLCODE	= $splitCode[1];
		$TTK_CATEG	= $splitCode[2];
		
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
									"IR_CODE", 
									"IR_DATE", 
									"", 
									"IR_AMOUNT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_inv->get_AllDataIRC($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_inv->get_AllDataIRL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$IR_NUM 		= $dataI['IR_NUM'];
				$IR_CODE 		= $dataI['IR_CODE'];
				$IR_DATE		= $dataI['IR_DATE'];
				$IR_DATEV		= date('d M Y', strtotime($dataI['IR_DATE']));
				$IR_DUEDATE		= $dataI['IR_DUEDATE'];
				$IR_DUEDATEV	= date('d M Y', strtotime($dataI['IR_DUEDATE']));
				$PR_NUM			= $dataI['IR_REFER'];
				$SPLCODE 		= $dataI['SPLCODE'];
				$PO_NUM			= $dataI['PO_NUM'];
				$IR_AMOUNT		= $dataI['IR_AMOUNT'];
				$IR_NOTE 		= $dataI['IR_NOTE'];
				$IR_NOTE2		= $dataI['IR_NOTE2'];
				$SPLDESC		= $dataI['SPLDESC'];
				$IR_RETAMN 		= 0;
				$IR_DPVAL 		= 0;

				$PO_CODE 		= "";
				$PO_DATEV 		= "";
				$sqlPO			= "SELECT PO_CODE, PO_DATE FROM tbl_po_header WHERE PO_NUM = '$PO_NUM'";
				$resPO			= $this->db->query($sqlPO)->result();
				foreach($resPO as $rowPO) :
					$PO_CODE	= $rowPO->PO_CODE;
					$PO_DATE	= $rowPO->PO_DATE;
					$PO_DATE1	= date_create($PO_DATE);
                	$PO_DATEV	= date_format($PO_DATE1,"d-m-Y");
				endforeach;

				$TotIRAmn 		= 0;
				$IR_AMOUNTPPN	= 0;
				$IR_AMOUNTPPH	= 0;
				$TotDisc		= 0;
				$sqlTAX			= "SELECT SUM(ITM_TOTAL) AS TotIRAmn, SUM(ITM_DISC) AS TotDisc,
										SUM(TAXPRICE1) AS TotPPn, SUM(TAXPRICE2) AS TotPPh
									FROM tbl_ir_detail
									WHERE IR_NUM = '$IR_NUM'";
				$resTAX			= $this->db->query($sqlTAX)->result();
				foreach($resTAX as $rowTAX) :
					$TotIRAmn		= $rowTAX->TotIRAmn;
					$IR_AMOUNTPPN	= $rowTAX->TotPPn;
					$IR_AMOUNTPPH	= $rowTAX->TotPPh;
					$TotDisc		= $rowTAX->TotDisc;
				endforeach;
				$IR_DPVAL 		= 0;
				$IR_RETAMN 		= 0;
				$IR_POT 		= $TotDisc;

				$GTTax 			= $IR_AMOUNTPPN - $IR_AMOUNTPPH;
				$IR_TOTAMN 		= $IR_AMOUNT + $GTTax - $IR_DPVAL - $IR_POT - $IR_RETAMN;

				/*$chkBox			= "<input type='checkbox' name='chk1' value='".$IR_NUM."|".$IR_DATE."|".$IR_DATEV."|".$PO_NUM."|".$PO_DATEV."|".$IR_AMOUNT."|".$GTTax."|".$IR_AMOUNT."|".$IR_NOTE."|".$IR_CODE."|".$PO_CODE."|".$IR_RETAMN."|".$TotDisc."|".$IR_DPVAL."' onClick='pickThis1(this);'/>";*/

				$chkBox			= "<input type='checkbox' name='chk1' value='".$IR_NUM."|".$IR_CODE."|".$IR_DATE."|".$IR_DATEV."|".$IR_NOTE."|".$PO_NUM."|".$PO_CODE."|".$PO_DATEV."|".$IR_AMOUNT."|".$IR_AMOUNTPPN."|".$IR_AMOUNTPPH."|".$IR_RETAMN."|".$IR_DPVAL."|".$IR_POT."' onClick='pickThis1(this);'/>";


				$output['data'][] = array($chkBox,
										  "<div style='white-space:nowrap'>".$IR_CODE."</div>",
										  $IR_DATE,
										  $IR_NOTE.' '.$IR_NOTE2,
										  number_format($IR_AMOUNT,2),
										  number_format($IR_AMOUNTPPN,2),
										  number_format($IR_AMOUNTPPH,2),
										  number_format($IR_DPVAL,2),
										  number_format($IR_RETAMN,2),
										  number_format($IR_POT,2),
										  number_format($IR_TOTAMN,2));
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
									  "A");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataOPN() // GOOD
	{
		$collData	= $_GET['id'];
		$splitCode 	= explode("~", $collData);
		$PRJCODE	= $splitCode[0];
		$SPLCODE	= $splitCode[1];
		$TTK_CATEG	= $splitCode[2];
		
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
									"OPNH_CODE", 
									"OPNH_DATE", 
									"", 
									"OPNH_AMOUNT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"]; 
			$num_rows 		= $this->m_purchase_inv->get_AllDataOPNC($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_inv->get_AllDataOPNL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$OPNH_NUM 		= $dataI['OPNH_NUM'];
				$OPNH_CODE 		= $dataI['OPNH_CODE'];
				$OPNH_DATE		= $dataI['OPNH_DATE'];
				$OPNH_DATEV		= date('d M Y', strtotime($dataI['OPNH_DATE']));
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$SPLCODE 		= $dataI['SPLCODE'];
				$SPLDESC		= $dataI['SPLDESC'];
				$OPNH_NOTE 		= $dataI['OPNH_NOTE'];
				$OPNH_NOTE2		= $dataI['OPNH_NOTE2'];
				$OPNH_AMOUNT	= $dataI['OPNH_AMOUNT'];
				$OPNH_AMOUNTPPN	= $dataI['OPNH_AMOUNTPPN'];
				$OPNH_AMOUNTPPH	= $dataI['OPNH_AMOUNTPPH'];
				$OPNH_RETAMN	= $dataI['OPNH_RETAMN'];
				$OPNH_DPVAL		= $dataI['OPNH_DPVAL'];
				$OPNH_POT		= $dataI['OPNH_POT'];

				$sqlWO			= "SELECT WO_DATE, WO_STARTD, WO_ENDD FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resWO			= $this->db->query($sqlWO)->result();
				foreach($resWO as $rowWO) :
					$WO_DATE	= $rowWO->WO_DATE;
					$WO_DATE1	= date_create($WO_DATE);
                	$WO_DATEV	= date_format($WO_DATE1,"d-m-Y");
				endforeach;

				$GTTax 			= $OPNH_AMOUNTPPN - $OPNH_AMOUNTPPH;
				$OPNH_TOTAMN 	= $OPNH_AMOUNT + $GTTax - $OPNH_DPVAL - $OPNH_POT - $OPNH_RETAMN;

				$chkBox			= "<input type='checkbox' name='chk1' value='".$OPNH_NUM."|".$OPNH_CODE."|".$OPNH_DATE."|".$OPNH_DATEV."|".$OPNH_NOTE."|".$WO_NUM."|".$WO_CODE."|".$WO_DATEV."|".$OPNH_AMOUNT."|".$OPNH_AMOUNTPPN."|".$OPNH_AMOUNTPPH."|".$OPNH_RETAMN."|".$OPNH_DPVAL."|".$OPNH_POT."' onClick='pickThis1(this);'/>";

				$output['data'][] = array($chkBox,
										  "<div style='white-space:nowrap'>".$OPNH_CODE."</div>",
										  $OPNH_DATE,
										  $OPNH_NOTE.' '.$OPNH_NOTE2,
										  number_format($OPNH_AMOUNT,2),
										  number_format($OPNH_AMOUNTPPN,2),
										  number_format($OPNH_AMOUNTPPH,2),
										  number_format($OPNH_DPVAL,2),
										  number_format($OPNH_RETAMN,2),
										  number_format($OPNH_POT,2),
										  number_format($OPNH_TOTAMN,2));
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
									  "A");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataOPN_RET() // GOOD
	{
		$collData	= $_GET['id'];
		$splitCode 	= explode("~", $collData);
		$PRJCODE	= $splitCode[0];
		$SPLCODE	= $splitCode[1];
		$TTK_CATEG	= $splitCode[2];
		
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
									"OPNH_CODE", 
									"OPNH_DATE", 
									"", 
									"OPNH_AMOUNT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_inv->get_AllDataOPNRC($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_inv->get_AllDataOPNRL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$OPNH_NUM 		= $dataI['OPNH_NUM'];
				$OPNH_CODE 		= $dataI['OPNH_CODE'];
				$OPNH_DATE		= $dataI['OPNH_DATE'];
				$OPNH_DATEV		= date('d M Y', strtotime($dataI['OPNH_DATE']));
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$SPLCODE 		= $dataI['SPLCODE'];
				$SPLDESC		= $dataI['SPLDESC'];
				$OPNH_NOTE 		= $dataI['OPNH_NOTE'];
				$OPNH_NOTE2		= $dataI['OPNH_NOTE2'];
				$OPNH_AMOUNT	= $dataI['OPNH_AMOUNT'];
				$OPNH_AMOUNTPPN	= $dataI['OPNH_AMOUNTPPN'];
				$OPNH_AMOUNTPPH	= $dataI['OPNH_AMOUNTPPH'];
				$OPNH_RETAMN	= $dataI['OPNH_RETAMN'];
				$OPNH_DPVAL		= $dataI['OPNH_DPVAL'];
				$OPNH_POT		= $dataI['OPNH_POT'];

				$sqlWO			= "SELECT WO_DATE, WO_STARTD, WO_ENDD FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resWO			= $this->db->query($sqlWO)->result();
				foreach($resWO as $rowWO) :
					$WO_DATE	= $rowWO->WO_DATE;
					$WO_DATE1	= date_create($WO_DATE);
                	$WO_DATEV	= date_format($WO_DATE1,"d-m-Y");
				endforeach;

				$GTTax 			= $OPNH_AMOUNTPPN - $OPNH_AMOUNTPPH;
				$OPNH_TOTAMN 	= $OPNH_AMOUNT + $GTTax - $OPNH_DPVAL - $OPNH_POT - $OPNH_RETAMN;

				$chkBox			= "<input type='checkbox' name='chk1' value='".$OPNH_NUM."|".$OPNH_CODE."|".$OPNH_DATE."|".$OPNH_DATEV."|".$OPNH_NOTE."|".$WO_NUM."|".$WO_CODE."|".$WO_DATEV."|".$OPNH_AMOUNT."|".$OPNH_AMOUNTPPN."|".$OPNH_AMOUNTPPH."|".$OPNH_RETAMN."|".$OPNH_DPVAL."|".$OPNH_POT."' onClick='pickThis1(this);'/>";

				$output['data'][] = array($chkBox,
										  "<div style='white-space:nowrap'>".$OPNH_CODE."</div>",
										  $OPNH_DATE,
										  $OPNH_NOTE.' '.$OPNH_NOTE2,
										  number_format($OPNH_AMOUNT,2),
										  number_format($OPNH_AMOUNTPPN,2),
										  number_format($OPNH_AMOUNTPPH,2),
										  number_format($OPNH_DPVAL,2),
										  number_format($OPNH_RETAMN,2),
										  number_format($OPNH_POT,2),
										  number_format($OPNH_TOTAMN,2));
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
									  "A");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataTTKL() // GOOD
	{
		$collID1	= $_GET['id'];
		$collID		= explode("~", $collID1);
		$PRJCODE	= $collID[0];
		$SPLCODE	= $collID[1];
		
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
			
			$columns_valid 	= array("TTK_CODE",
									"TTK_DATE",
									"TTK_DUEDATE",
									"B.SPLDESC",
									"TTK_NOTES",
									"TTK_GTOTAL",
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
			$num_rows 		= $this->m_purchase_inv->get_AllDataCTTK($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_inv->get_AllDataLTTK($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$TTK_NUM		= $dataI['TTK_NUM'];
				$TTK_CODE		= $dataI['TTK_CODE'];
				$TTK_DATE		= $dataI['TTK_DATE'];
				$TTK_DATEV		= date('d M Y', strtotime($TTK_DATE));
				$TTK_DUEDATE	= $dataI['TTK_DUEDATE'];
				$TTK_DUEDATEV	= date('d M Y', strtotime($TTK_DUEDATE));
				$TTK_ESTDATE	= $dataI['TTK_ESTDATE'];
				$TTK_ESTDATEV	= date('d M Y', strtotime($TTK_ESTDATE));
				$TTK_CHECKER	= $dataI['TTK_CHECKER'];
				$TTK_NOTES		= $dataI['TTK_NOTES'];
				$TTK_NOTES1		= $dataI['TTK_NOTES1'];
				$TTK_CATEG		= $dataI['TTK_CATEG'];
				$TTK_AMOUNT		= $dataI['TTK_AMOUNT'];
				$TTK_AMOUNT1	= $dataI['TTK_AMOUNT'];
				$TTK_AMOUNT_PPN	= $dataI['TTK_AMOUNT_PPN'];
				$TTK_AMOUNT_PPH	= $dataI['TTK_AMOUNT_PPH'];
				$TTK_AMOUNT_DPB	= $dataI['TTK_AMOUNT_DPB'];
				$TTK_AMOUNT_RET	= $dataI['TTK_AMOUNT_RET'];
				$TTK_AMOUNT_POT	= $dataI['TTK_AMOUNT_POT'];
				$TTK_AMOUNT_OTH	= $dataI['TTK_AMOUNT_OTH'];
				$TTK_ACC_OTH	= $dataI['TTK_ACC_OTH'];
				$TTK_GTOTAL		= $dataI['TTK_GTOTAL'];
				$TTK_GTOTALV	= number_format($TTK_GTOTAL,2);
				$TTK_STAT		= $dataI['TTK_STAT'];
				$TTK_CATEG		= $dataI['TTK_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$SPLCODE		= $dataI['SPLCODE'];				
				$SPLDESC		= $dataI['SPLDESC'];

				if($TTK_CATEG == 'OPN-RET')
					$TTK_CATEGD	= "-RET";
				else
					$TTK_CATEGD	= "";
				
				$chkBox			= "<input type='checkbox' name='chk0' value='".$TTK_NUM."|".$TTK_CODE."|".$TTK_DATEV."|".$TTK_DUEDATEV."|".$TTK_NOTES."|".$TTK_CATEG."|".$TTK_AMOUNT."|".$TTK_AMOUNT_PPN."|".$TTK_AMOUNT_PPH."|".$TTK_AMOUNT_DPB."|".$TTK_AMOUNT_RET."|".$TTK_AMOUNT_POT."|".$TTK_AMOUNT_OTH."|".$TTK_GTOTAL."|".$PRJCODE."|".$SPLCODE."|".$TTK_DATE."|".$TTK_DUEDATE."' onClick='pickThis0(this);'/>";

				$output['data'][] = array($chkBox,
										  "<label style='white-space:nowrap'>$TTK_CODE.$TTK_CATEGD</label>",
										  $TTK_DATEV,
										  $TTK_DUEDATEV,
										  $TTK_NOTES,
										  $TTK_GTOTALV);
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

  	function getTax() // GOOD
	{
		$TAX_CODE 	= $_POST['taxCode'];

		$TAXPER 	= 0;
		$s_01 		= "SELECT TAXLA_PERC FROM tbl_tax_la WHERE TAXLA_NUM = '$TAX_CODE' LIMIT 1";
		$r_01 		= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$TAXPER = $rw_01->TAXLA_PERC;
		endforeach;
		echo $TAXPER;
	}

  	function getTaxPPn() // GOOD
	{
		$TAX_CODE 	= $_POST['taxCode'];

		$TAXPER 	= 0;
		$s_01 		= "SELECT TAXLA_PERC FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAX_CODE' LIMIT 1";
		$r_01 		= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$TAXPER = $rw_01->TAXLA_PERC;
		endforeach;
		echo $TAXPER;
	}
	
	function procUM() // OK
	{
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];		// tbl_um_header
        $tblNameD 	= $colExpl[2];		// tbl_um_detail
        $DocNm		= $colExpl[3];		// UM_NUM
        $UM_NUM		= $colExpl[4];		// UM_NUM
        $PrjNm		= $colExpl[5];		// PRJCODE
        $PRJCODE	= $colExpl[6];		// PRJCODE
		
		date_default_timezone_set("Asia/Jakarta");
		
		$ISSELECTED		= date('Y-m-d H:i:s');
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		$s_01			= "SELECT A.JOBCODEID, A.ITM_QTY, A.ITM_TOTAL
							FROM tbl_um_detail A
							WHERE DocNm = '$UM_NUM' AND PRJCODE = '$PRJCODE'";
		$r_01 			= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$JOBCODEID	= $rw_01->JOBCODEID;
			$ITM_QTY	= $rw_01->ITM_QTY;
			$ITM_TOTAL	= $rw_01->ITM_TOTAL;
		endforeach;

		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= $PRJCODE;
			$TTR_REFDOC		= $INV_NUM0;
			$MenuCode 		= 'MN141';
			$TTR_CATEG		= 'PROC_PINV';
			
			$this->load->model('m_updash/m_updash', '', TRUE);				
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $DocNum telah diproses.";
		}
		else
		{
			$alert1	= "Document no. $DocNum has been processed.";
		}
		echo "$alert1";
	}
}