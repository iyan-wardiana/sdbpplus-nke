<?php
/*  
 * Author		= Hendar Permana
 * Create Date	= 19 Maret 2018
 * Updated		= Dian Hermanto
 * File Name	= C_po180c19ret.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_po180c19ret extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
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
	
 	function index() // G
	{
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
		
		$sqlApp = "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url	= site_url('c_purchase/c_po180c19ret/prjl180c19/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl180c19() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN411';
				$data["MenuCode"] 	= 'MN411';	
				$data["MenuApp"] 	= 'MN412';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows; 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_po180c19ret/gl180c19ret/?id=";
			
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

	function gl180c19ret() // G
	{
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
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
				$data["url_search"] = site_url('c_purchase/c_po180c19ret/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_purchase_ret->count_all_ret($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_purchase_ret->get_all_ret($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN411';
				$data["MenuCode"] 	= 'MN411';	
				$data["MenuApp"] 	= 'MN412';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['addURL'] 	= site_url('c_purchase/c_po180c19ret/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_po180c19ret/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN411';
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
			
			$this->load->view('v_purchase/v_purchase_ret/v_ret_list', $data);
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
			$url			= site_url('c_purchase/c_po180c19ret/gl180c19ret/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("RET_CODE",
									"SPLCODE",
									"IR_CODE",
									"SPLDESC",
									"RET_NOTES",
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
			$num_rows 		= $this->m_purchase_ret->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_ret->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$RET_NUM	= $dataI['RET_NUM'];
				$RET_CODE	= $dataI['RET_CODE'];
				$RET_DATE	= $dataI['RET_DATE'];
				$RET_DATEV	= date('d M Y', strtotime($RET_DATE));
				
				$SPLCODE	= $dataI['SPLCODE'];
				$SPLDESC	= $dataI['SPLDESC'];
				$IR_CODE	= $dataI['IR_CODE'];
				$PO_CODE	= $dataI['PO_CODE'];
				$RET_STAT	= $dataI['RET_STAT'];
				$RET_NOTES	= $dataI['RET_NOTES'];
				$JOBCODEID	= $dataI['JOBCODEID'];
				
				
				$RET_DESC	= "$RET_NOTES<br>$PO_CODE";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$PRJCODE~$RET_NUM";

               	$secUpd		= site_url('c_purchase/c_po180c19ret/u744t3/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_purchase/c_po180c19ret/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($RET_NUM));
				
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_ret_header~tbl_ret_detail~RET_NUM~$RET_NUM~PRJCODE~$PRJCODE";
				
				if($RET_STAT == 1 || $RET_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
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
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array($noU.".",
										  "<label style='white-space:nowrap'>".$dataI['RET_NUM']."</label>",
										  $RET_DATEV,
										  $IR_CODE,
										  $SPLDESC,
										  $RET_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
		
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
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN411';
				$data["MenuCode"] 	= 'MN411';	
				$data["MenuApp"] 	= 'MN412';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['task']			= 'add';
			$data['form_action']	= site_url('c_purchase/c_po180c19ret/add_process');
			$cancelURL				= site_url('c_purchase/c_po180c19ret/gl180c19ret/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_ret->count_all_num_rowsVend($PRJCODE);
			$data['vwvendor'] 		= $this->m_purchase_ret->viewvendor($PRJCODE)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN411';
			$data["MenuCode"] 		= 'MN411';
			$data['viewDocPattern'] = $this->m_purchase_ret->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN411';
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
	
			$this->load->view('v_purchase/v_purchase_ret/v_ret_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallIR() // G
	{
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$collCODE	= $_GET['id'];
			$collCODE	= $this->url_encryption_helper->decode_url($collCODE);
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$splitCode 	= explode("~", $collCODE);
			$PRJCODE	= $splitCode[0];
			$SPLCODE	= $splitCode[1];
			
			$data['title'] 				= $appName;
			$data['PRJCODE']			= $PRJCODE;
			$data['SPLCODE']			= $SPLCODE;
					
			$this->load->view('v_purchase/v_purchase_ret/v_ret_sel_ir', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function p0p1t3m()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$collData	= $_GET['id'];
			$collData	= $this->url_encryption_helper->decode_url($collData);
			$explData	= explode("~", $collData);
			$PRJCODE 	= $explData[0];
			$SPLCODE 	= $explData[1];
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Material";
			}
			else
			{
				$data["h2_title"] 	= "List Item";
			}

			$data['PRJCODE'] 		= $PRJCODE;			
			$data['countAllItem'] 	= $this->m_purchase_ret->count_all_num_rowsAllItem($PRJCODE, $SPLCODE);
			$data['vwAllItem'] 		= $this->m_purchase_ret->viewAllItemMatBudget($PRJCODE, $SPLCODE)->result();
					
			$this->load->view('v_purchase/v_purchase_ret/v_ret_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN411';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$RET_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$RET_CODE 		= $this->input->post('RET_CODE');
			$RET_DATE		= date('Y-m-d',strtotime($this->input->post('RET_DATE')));
			$RET_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('RET_DATE'))));
			$Patt_Year		= date('Y',strtotime($RET_DATE));
			$Patt_Month		= date('m',strtotime($RET_DATE));
			$Patt_Date		= date('d',strtotime($RET_DATE));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$RET_TYPE 		= $this->input->post('RET_TYPE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$RET_NOTES 		= $this->input->post('RET_NOTES');
			$RET_STAT		= $this->input->post('RET_STAT'); // New, Confirm, Approve	
			$JOBCODEID 		= $this->input->post('JOBCODEID');
			
			$NOURT 	= 0;
			$IRNUM 	= "";
			$IRCODE = "";
			$PONUM 	= "";
			$POCODE = "";
			foreach($_POST['data'] as $d)
			{
				$d['RET_NUM']	= $RET_NUM;
				$d['RET_CODE']	= $RET_CODE;
				$d['RET_DATE']	= $RET_DATE;
				$d['PRJCODE']	= $PRJCODE;
				$ITM_QTY		= $d['ITM_QTY'];
				$ITM_PRICE		= $d['ITM_PRICE'];
				$d['RET_COST']	= $ITM_QTY * $ITM_PRICE;

				if($ITM_QTY > 0)
				{
					$NOURT 		= $NOURT + 1;
					$IR_NUM		= $d['IR_NUM'];
					$IR_CODE	= $d['IR_CODE'];
					$PO_NUM		= $d['PO_NUM'];
					$PO_CODE	= $d['PO_CODE'];
					if($NOURT == 1)
					{
						$IRNUM 	= $IR_NUM;
						$IRCODE = $IR_CODE;
						$PONUM 	= $PO_NUM;
						$POCODE	= $PO_CODE;
					}
					else
					{
						$IRNUM 	= $IRNUM.",".$IR_NUM;
						$IRCODE = $IRCODE.",".$IR_CODE;
						$PONUM 	= $PONUM.",".$PO_NUM;
						$POCODE	= $POCODE.",".$PO_CODE;
					}
					$this->db->insert('tbl_ret_detail',$d);
				}
			}
			
			$AddRETH		= array('RET_NUM' 		=> $RET_NUM,
									'RET_CODE' 		=> $RET_CODE,
									'RET_DATE'		=> $RET_DATE,
									'RET_TYPE'		=> $RET_TYPE,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE' 		=> $SPLCODE,
									'IR_NUM' 		=> $IRNUM,
									'IR_CODE' 		=> $IRCODE,
									'PO_NUM' 		=> $PONUM,
									'PO_CODE' 		=> $POCODE,
									'RET_NOTES' 	=> $RET_NOTES,
									'RET_CREATER'	=> $DefEmp_ID,
									'RET_CREATED'	=> date('Y-m-d H:i:s'),
									'RET_STAT'		=> $RET_STAT,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_purchase_ret->add($AddRETH); // Insert tb_RET_header
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('RET_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $RET_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "RET",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ret_header",	// TABLE NAME
										'KEY_NAME'		=> "RET_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "RET_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $RET_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_RET",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_RET_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_RET_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_RET_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_RET_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_RET_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_RET_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $RET_NUM;
				$MenuCode 		= 'MN411';
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "RET_NUM",
										'DOC_CODE' 		=> $RET_NUM,
										'DOC_STAT' 		=> $RET_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ret_header");
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
			
			$url			= site_url('c_purchase/c_po180c19ret/gl180c19ret/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u744t3() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$RET_NUM	= $EXTRACTCOL[1];
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN411';
				$data["MenuCode"] 	= 'MN411';	
				$data["MenuApp"] 	= 'MN412';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$getpo_head				= $this->m_purchase_ret->get_ret_by_number($RET_NUM)->row();
			$PRJCODE				= $getpo_head->PRJCODE;
			$data["MenuCode"] 		= 'MN411';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$EmpID 					= $this->session->userdata('Emp_ID');
			
			$data['task']			= 'edit';
			$cancelURL				= site_url('c_purchase/c_po180c19ret/gl180c19ret/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_ret->count_all_num_rowsVend($PRJCODE);
			$data['vwvendor'] 		= $this->m_purchase_ret->viewvendor($PRJCODE)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN411';
			$data["MenuCode"] 		= 'MN411';
		
			$getRET 						= $this->m_purchase_ret->get_ret_by_number($RET_NUM)->row();									
			$data['default']['RET_NUM'] 	= $getRET->RET_NUM;
			$data['default']['RET_CODE'] 	= $getRET->RET_CODE;
			$data['default']['RET_DATE'] 	= $getRET->RET_DATE;
			$data['default']['RET_TYPE'] 	= $getRET->RET_TYPE;
			$data['default']['PRJCODE'] 	= $getRET->PRJCODE;
			$data['default']['SPLCODE'] 	= $getRET->SPLCODE;
			$data['default']['IR_NUM'] 		= $getRET->IR_NUM;
			$data['default']['IR_CODE'] 	= $getRET->IR_CODE;
			$data['default']['RET_NOTES'] 	= $getRET->RET_NOTES;
			$data['default']['RET_NOTES1'] 	= $getRET->RET_NOTES1;
			$data['default']['PRJNAME'] 	= $getRET->PRJNAME;
			$data['default']['JOBCODEID'] 	= $getRET->JOBCODEID;
			$data['default']['RET_STAT'] 	= $getRET->RET_STAT;
			$data['default']['Patt_Year'] 	= $getRET->Patt_Year;
			$data['default']['Patt_Month'] 	= $getRET->Patt_Month;
			$data['default']['Patt_Date'] 	= $getRET->Patt_Date;
			$data['default']['Patt_Number'] = $getRET->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getRET->RET_NUM;
				$MenuCode 		= 'MN411';
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
			
			$ISDIRECT	= 0;	// DEFAULT TO 0
			if($ISDIRECT == 0)
			{
				$data['form_action']		= site_url('c_purchase/c_po180c19ret/update_process');
				$this->load->view('v_purchase/v_purchase_ret/v_ret_form', $data);	
			}
			else
			{
				$data['form_action']		= site_url('c_purchase/c_po180c19ret/updateDir_process');
				$this->load->view('v_purchase/v_purchase_ret/v_retdir_form', $data);	
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
		
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

			$RET_NUM 		= $this->input->post('RET_NUM');
			$RET_CODE 		= $this->input->post('RET_CODE');
			$RET_DATE		= date('Y-m-d',strtotime($this->input->post('RET_DATE')));
			$RET_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('RET_DATE'))));
			$Patt_Year		= date('Y',strtotime($RET_DATE));
			$Patt_Month		= date('m',strtotime($RET_DATE));
			$Patt_Date		= date('d',strtotime($RET_DATE));
			
			$RET_TYPE 		= $this->input->post('RET_TYPE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$RET_NOTES 		= $this->input->post('RET_NOTES');
			$RET_STAT		= $this->input->post('RET_STAT'); // New, Confirm, Approve	
			$JOBCODEID 		= $this->input->post('JOBCODEID');
			
			$this->m_purchase_ret->deleteRETDetail($RET_NUM);
			
			$NOURT 	= 0;
			$IRNUM 	= "";
			$IRCODE = "";
			$PONUM 	= "";
			$POCODE = "";
			foreach($_POST['data'] as $d)
			{
				$d['RET_NUM']	= $RET_NUM;
				$d['RET_CODE']	= $RET_CODE;
				$d['RET_DATE']	= $RET_DATE;
				$d['PRJCODE']	= $PRJCODE;
				$ITM_QTY		= $d['ITM_QTY'];
				$ITM_PRICE		= $d['ITM_PRICE'];
				$d['RET_COST']	= $ITM_QTY * $ITM_PRICE;

				if($ITM_QTY > 0)
				{
					$NOURT 		= $NOURT + 1;
					$IR_NUM		= $d['IR_NUM'];
					$IR_CODE	= $d['IR_CODE'];
					$PO_NUM		= $d['PO_NUM'];
					$PO_CODE	= $d['PO_CODE'];
					if($NOURT == 1)
					{
						$IRNUM 	= $IR_NUM;
						$IRCODE = $IR_CODE;
						$PONUM 	= $PO_NUM;
						$POCODE	= $PO_CODE;
					}
					else
					{
						$IRNUM 	= $IRNUM.",".$IR_NUM;
						$IRCODE = $IRCODE.",".$IR_CODE;
						$PONUM 	= $PONUM.",".$PO_NUM;
						$POCODE	= $POCODE.",".$PO_CODE;
					}
					$this->db->insert('tbl_ret_detail',$d);
				}
			}
			
			$updRETH		= array('RET_CODE' 		=> $RET_CODE,
									'RET_DATE'		=> $RET_DATE,
									'RET_TYPE'		=> $RET_TYPE,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE' 		=> $SPLCODE,
									'IR_NUM' 		=> $IRNUM,
									'IR_CODE' 		=> $IRCODE,
									'PO_NUM' 		=> $PONUM,
									'PO_CODE' 		=> $POCODE,
									'RET_NOTES' 	=> $RET_NOTES,
									'RET_STAT'		=> $RET_STAT);
			$this->m_purchase_ret->updateRET($RET_NUM, $updRETH);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('RET_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $RET_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "RET",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ret_header",	// TABLE NAME
										'KEY_NAME'		=> "RET_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "RET_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $RET_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_RET",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_RET_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_RET_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_RET_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_RET_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_RET_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_RET_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $RET_NUM;
				$MenuCode 		= 'MN411';
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "RET_NUM",
										'DOC_CODE' 		=> $RET_NUM,
										'DOC_STAT' 		=> $RET_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ret_header");
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
			
			$url			= site_url('c_purchase/c_po180c19ret/gl180c19ret/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		
		$url			= site_url('c_purchase/c_po180c19ret/p07_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function p07_l5t_x1() // G
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
				$mnCode				= 'MN412';
				$data["MenuCode"] 	= 'MN412';	
				$data["MenuApp"] 	= 'MN412';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_po180c19ret/gl180c19retinb/?id=";
			
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
	
	function gl180c19retinb() // G
	{
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
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
				$data["url_search"] = site_url('c_purchase/c_po180c19ret/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_purchase_ret->count_all_retInb($PRJCODE, $key, $DefEmp_ID);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_purchase_ret->get_all_RETInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN412';
				$data["MenuCode"] 	= 'MN412';	
				$data["MenuApp"] 	= 'MN412';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN412';
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
			$data['backURL'] 	= site_url('c_purchase/c_po180c19ret/inbox?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$this->load->view('v_purchase/v_purchase_ret/v_inb_ret_list', $data);
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
			$url			= site_url('c_purchase/c_po180c19ret/gl180c19retinb/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("RET_CODE",
									"SPLCODE",
									"IR_CODE",
									"SPLDESC",
									"RET_NOTES",
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
			$num_rows 		= $this->m_purchase_ret->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_ret->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$RET_NUM	= $dataI['RET_NUM'];
				$RET_CODE	= $dataI['RET_CODE'];
				$RET_DATE	= $dataI['RET_DATE'];
				$RET_DATEV	= date('d M Y', strtotime($RET_DATE));
				
				$SPLCODE	= $dataI['SPLCODE'];
				$SPLDESC	= $dataI['SPLDESC'];
				$IR_CODE	= $dataI['IR_CODE'];
				$PO_CODE	= $dataI['PO_CODE'];
				$RET_STAT	= $dataI['RET_STAT'];
				$RET_NOTES	= $dataI['RET_NOTES'];
				$JOBCODEID	= $dataI['JOBCODEID'];
				
				
				$RET_DESC	= "$RET_NOTES<br>$PO_CODE";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$PRJCODE~$RET_NUM";

               	$secUpd		= site_url('c_purchase/c_po180c19ret/up4_gl180c19retinb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_purchase/c_po180c19ret/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($RET_NUM));
				
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_ret_header~tbl_ret_detail~RET_NUM~$RET_NUM~PRJCODE~$PRJCODE";
                                        
				$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";

				$output['data'][] = array($noU.".",
										  "<label style='white-space:nowrap'>".$dataI['RET_NUM']."</label>",
										  $RET_DATEV,
										  $IR_CODE,
										  $SPLDESC,
										  $RET_NOTES,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	
	function up4_gl180c19retinb() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
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
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$RET_NUM	= $EXTRACTCOL[1];
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN412';
				$data["MenuCode"] 	= 'MN412';	
				$data["MenuApp"] 	= 'MN412';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$getpo_head				= $this->m_purchase_ret->get_ret_by_number($RET_NUM)->row();
			$PRJCODE				= $getpo_head->PRJCODE;
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$EmpID 					= $this->session->userdata('Emp_ID');
			
			$data['task']			= 'edit';
			$cancelURL				= site_url('c_purchase/c_po180c19ret/gl180c19retinb/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_ret->count_all_num_rowsVend($PRJCODE);
			$data['vwvendor'] 		= $this->m_purchase_ret->viewvendor($PRJCODE)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN412';
		
			$getRET 						= $this->m_purchase_ret->get_ret_by_number($RET_NUM)->row();									
			$data['default']['RET_NUM'] 	= $getRET->RET_NUM;
			$data['default']['RET_CODE'] 	= $getRET->RET_CODE;
			$data['default']['RET_DATE'] 	= $getRET->RET_DATE;
			$data['default']['RET_TYPE'] 	= $getRET->RET_TYPE;
			$data['default']['PRJCODE'] 	= $getRET->PRJCODE;
			$data['default']['SPLCODE'] 	= $getRET->SPLCODE;
			$data['default']['IR_NUM'] 		= $getRET->IR_NUM;
			$data['default']['IR_CODE'] 	= $getRET->IR_CODE;
			$data['default']['PO_NUM'] 		= $getRET->PO_NUM;
			$data['default']['PO_CODE'] 	= $getRET->PO_CODE;
			$data['default']['RET_NOTES'] 	= $getRET->RET_NOTES;
			$data['default']['RET_NOTES1'] 	= $getRET->RET_NOTES1;
			$data['default']['PRJNAME'] 	= $getRET->PRJNAME;
			$data['default']['JOBCODEID'] 	= $getRET->JOBCODEID;
			$data['default']['RET_TOTCOST'] = $getRET->RET_TOTCOST;
			$data['default']['RET_STAT'] 	= $getRET->RET_STAT;
			$data['default']['Patt_Year'] 	= $getRET->Patt_Year;
			$data['default']['Patt_Month'] 	= $getRET->Patt_Month;
			$data['default']['Patt_Date'] 	= $getRET->Patt_Date;
			$data['default']['Patt_Number'] = $getRET->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getRET->RET_NUM;
				$MenuCode 		= 'MN412';
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
			
			$data['form_action']		= site_url('c_purchase/c_po180c19ret/update_process_inb');
			$this->load->view('v_purchase/v_purchase_ret/v_inb_ret_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // G
	{
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		date_default_timezone_set("Asia/Jakarta");
		
		$APPDATE 			= date('Y-m-d H:i:s');
			
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();

			$RET_NUM 		= $this->input->post('RET_NUM');
			$RET_CODE 		= $this->input->post('RET_CODE');
			$RET_DATE		= date('Y-m-d',strtotime($this->input->post('RET_DATE')));
			$RET_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('RET_DATE'))));
			$Patt_Year		= date('Y',strtotime($RET_DATE));
			$Patt_Month		= date('m',strtotime($RET_DATE));
			$Patt_Date		= date('d',strtotime($RET_DATE));
			
			$RET_TYPE 		= $this->input->post('RET_TYPE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$IR_NUM 		= $this->input->post('IR_NUM');
			$IR_CODE 		= $this->input->post('IR_CODE');
			$PO_NUM 		= $this->input->post('PO_NUM');
			$PO_CODE 		= $this->input->post('PO_CODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$RET_NOTES 		= $this->input->post('RET_NOTES');
			$RET_STAT		= $this->input->post('RET_STAT'); // New, Confirm, Approve	
			$JOBCODEID 		= $this->input->post('JOBCODEID');
			$RET_NOTES1 	= $this->input->post('RET_NOTES1');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$SPLDESC		= '';
			$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier
								WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= $rowSPL->SPLDESC;
			endforeach;
			
			$updRETH		= array('RET_NOTES1'	=> $RET_NOTES1);
			$this->m_purchase_ret->updateRET($RET_NUM, $updRETH);
						
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $RET_NUM;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= $APPDATE;
				$AH_NOTES		= $this->input->post('RET_NOTES1');
				
				if($RET_STAT == 3)
				{
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
												'AH_APPLEV'		=> $AH_APPLEV,
												'AH_APPROVER'	=> $AH_APPROVER,
												'AH_APPROVED'	=> $AH_APPROVED,
												'AH_NOTES'		=> $AH_NOTES,
												'AH_ISLAST'		=> $AH_ISLAST);										
						$this->m_updash->insAppHist($insAppHist);
					
					$updRETH 	= array('RET_STAT'	=> 7);										
					$this->m_purchase_ret->updateRET($RET_NUM, $updRETH);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "RET_NUM",
												'DOC_CODE' 		=> $RET_NUM,
												'DOC_STAT' 		=> $RET_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_ret_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			// END : SAVE APPROVE HISTORY
			
			if($RET_STAT == 3 && $AH_ISLAST == 1)
			{
				// START : SETTING L/R
					$this->load->model('m_updash/m_updash', '', TRUE);
					$PERIODED	= $RET_DATE;
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
				
				$RET_APPROVED	= date('Y-m-d H:i:s');
				$RET_APPROVER	= $DefEmp_ID;
					
				$updRETH 	= array('RET_STAT'		=> $RET_STAT,
									'RET_APPROVED'	=> $RET_APPROVED,
									'RET_APPROVER'	=> $RET_APPROVER);
				$this->m_purchase_ret->updateRET($RET_NUM, $updRETH);

				// START : JOURNAL HEADER
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $RET_NUM;
					$JournalType	= 'PURC-RET';
					$JournalH_Date	= $RET_DATE;
					$Company_ID		= $comp_init;
					$DOCSource		= $IR_NUM;
					$LastUpdate		= $RET_APPROVED;
					$WH_CODE		= $PRJCODE;
					$Refer_Number	= $IR_NUM;
					$RefType		= 'PURC-RET';
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
										'SPLCODE'			=> $SPLCODE,
										'SPLDESC'			=> $SPLDESC,
										'PRJCODE'			=> $PRJCODE);
					$this->m_journal->createJournalH_NEW($JournalH_Code, $parameters);
				// END : JOURNAL HEADER
				
				// START : JOURNAL DETAIL
					$TOT_AMOUNT		= 0;
					$TOT_AMOUNT_PPN	= 0;
					$TOT_AMOUNT_PPh	= 0;
					foreach($_POST['data'] as $d)
					{
						$RET_COST 		= $d['RET_COST'];
						$TOT_AMOUNT		= $TOT_AMOUNT + $RET_COST;
					}
					
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
						$TAXCODE1 		= 0;
						$TAXPRICE1 		= 0;
						$JournalH_Code	= $RET_NUM;
						$JournalType	= 'PURC-RET';
						$JournalH_Date	= $RET_DATE;
						$Company_ID		= $comp_init;
						$Currency_ID	= 'IDR';
						$LastUpdate		= $RET_APPROVED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= $IR_NUM;
						$RefType		= 'PURC-RET';
						$JSource		= 'PURC-RET';
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
											'TRANS_CATEG' 		=> 'PURC-RET',		// RET = PURC-RET
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
												'JOBCODEID'	=> $JOBCODEID,
												'IR_NUM' 	=> $RET_NUM,
												'IR_CODE' 	=> $RET_CODE,
												'ITM_CODE' 	=> $ITM_CODE,
												'ITM_QTY' 	=> $ITM_QTY,
												'ITM_PRICE' => $ITM_PRICE);
							$this->m_purchase_ret->updateITM_Min($parameters1);
						// START : UPDATE STOCK
						
						// START : RECORD TO ITEM HISTORY
							$this->m_journal->createITMHistMin($JournalH_Code, $parameters);
						// START : RECORD TO ITEM HISTORY
					}
				// END : JOURNAL DETAIL
				
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('RET_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $RET_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "RET",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_ret_header",	// TABLE NAME
											'KEY_NAME'		=> "RET_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "RET_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $RET_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_RET",		// GRAND TOT. TRANSACTION STATUS tbl_dash_data
											'FIELD_NM_N'	=> "TOT_RET_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_RET_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_RET_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_RET_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_RET_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_RET_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $IR_NUM;
					$MenuCode 		= 'MN412';
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

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "RET_NUM",
											'DOC_CODE' 		=> $RET_NUM,
											'DOC_STAT' 		=> $RET_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_ret_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "RET_NUM",
											'DOC_CODE' 		=> $RET_NUM,
											'DOC_STAT' 		=> $RET_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_ret_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_purchase/c_po180c19ret/gl180c19retinb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
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
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
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
			
			$data['countIR']	= $this->m_purchase_ret->count_all_IR($PO_NUM);
			$data['vwIR'] 		= $this->m_purchase_ret->get_all_IR($PO_NUM)->result();	
							
			$this->load->view('v_purchase/v_purchase_po/print_irlist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_purchase/m_purchase_ret/m_purchase_ret', '', TRUE);
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
			
			$getPO 			= $this->m_purchase_ret->get_ret_by_number($PO_NUM)->row();
			
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
			redirect('__l1y');
		}
	}
}