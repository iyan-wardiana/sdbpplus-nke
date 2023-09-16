<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2017
 * File Name	= C_ir180c15.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_ir180c15  extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
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
	
 	function index() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_ir180c15/prjl180c15/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl180c15() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN067';
				$data["MenuApp"] 	= 'MN068';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN067'; 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_inventory/c_ir180c15/gir180c15/?id=";
			
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

	function gir180c15() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN067';
			$data["MenuCode"] 	= 'MN067';
			$data["MenuApp"] 	= 'MN068';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
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
				$data["url_search"] = site_url('c_inventory/c_ir180c15/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_itemreceipt->count_all_num_rows($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_itemreceipt->get_last_ten_IR($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] = "Penerimaan Material";
				$data["h3_title"] = "inventaris";
			}
			else
			{
				$data["h2_title"] = "Item Receipt";
				$data["h3_title"] = "inventory";
			}
			
			$data['backURL'] 	= site_url('c_inventory/c_ir180c15/prjl180c15/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data["PRJCODE"] 	= $PRJCODE;
	 		$data["MenuCode"] 	= 'MN067';
			
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN067';
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
			
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gir180c15xxx() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
			
		
		$num_rows 			= $this->m_itemreceipt->count_all_num_rows($PRJCODE, $key);
		$data["cData"] 		= $num_rows;	 
		$data['vData']		= $this->m_itemreceipt->get_last_ten_IR($PRJCODE, $start, $end, $key)->result();
		
		$this->load->view('v_inventory/v_itemreceipt/itemreceipt_tbl_only', $data);
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
			$url			= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("ID",
									"IR_CODE",
									"IR_DATE",
									"SPLDESC",
									"IR_NOTE",
									"STATDESC",
									"INVSTAT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_itemreceipt->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_itemreceipt->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$IR_NUM		= $dataI['IR_NUM'];
				$IR_CODE	= $dataI['IR_CODE'];
				
				$IR_DATE	= $dataI['IR_DATE'];
				$IR_DATEV	= date('d M Y', strtotime($IR_DATE));
				
				$PRJCODE	= $dataI['PRJCODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$SPLDESC	= $dataI['SPLDESC'];
				$PO_NUM		= $dataI['PO_NUM'];
				$IR_REFER	= $dataI['IR_REFER'];
				$IR_AMOUNT	= $dataI['IR_AMOUNT'];
				$IR_NOTE	= $dataI['IR_NOTE'];
				$IR_STAT	= $dataI['IR_STAT'];
				
				if($SPLDESC	== '')
				{
					$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
					$resultS 	= $this->db->query($sqlS)->result();
					foreach($resultS as $rowS) :
						$SPLDESC = $rowS->SPLDESC;
					endforeach;
				}
				
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$INVSTAT	= $dataI['INVSTAT'];									
				if($INVSTAT == 'NI')
				{
					$INVSTATDes	= 'No';
					$STATCOLV	= 'danger';
				}
				elseif($INVSTAT == 'HI')
				{
					$INVSTATDes	= 'Half';
					$STATCOLV	= 'warning';
				}
				elseif($INVSTAT == 'FI')
				{
					$INVSTATDes	= 'Full';
					$STATCOLV	= 'success';
				}
				
				$REVMEMO		= $dataI['REVMEMO'];	
				
				$CollID1		= "$PRJCODE~$IR_NUM";
				$secUpd		= site_url('c_inventory/c_ir180c15/up180c15dt/?id='.$this->url_encryption_helper->encode_url($CollID1));
				$secPrint	= site_url('c_inventory/c_ir180c15/printdocument/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
				$secPrintQR	= site_url('c_inventory/c_ir180c15/printQR/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
				//$secVoid	= site_url('c_inventory/c_ir180c15/voiddocument/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
				$CollID		= "IR~$IR_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_ir_header~tbl_ir_detail~IR_NUM~$IR_NUM~PRJCODE~$PRJCODE";
				$secVoid 	= base_url().'index.php/__l1y/trashIR/?id=';
				$voidID 	= "$secVoid~tbl_ir_header~tbl_ir_detail~IR_NUM~$IR_NUM~PRJCODE~$PRJCODE";

				// CEK TTK
					$sqlTTKC	= "tbl_ttk_detail A INNER JOIN tbl_ttk_header B ON B.TTK_NUM = A.TTK_NUM
									WHERE B.PRJCODE = '$PRJCODE' AND A.TTK_REF1_NUM = '$IR_NUM'";
					$resTTKC 	= $this->db->count_all($sqlTTKC);

					if($resTTKC > 0)
					{
						$disCl 	= "voidDOCX";
						$disV 	= "disabled='disabled'";
					}
					else
					{
						$disCl 	= "voidDOC";
						$disV 	= "";
					}

				if($IR_STAT == 1 || $IR_STAT == 4)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<input type='hidden' name='urlPrintQR".$noU."' id='urlPrintQR".$noU."' value='".$secPrintQR."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printDocument(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='avascript:void(null);' class='btn btn-warning btn-xs' title='Show QRC' onClick='printQR(".$noU.")' style='display:none'>
										<i class='glyphicon glyphicon-qrcode'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($IR_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<input type='hidden' name='urlPrintQR".$noU."' id='urlPrintQR".$noU."' value='".$secPrintQR."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='avascript:void(null);' class='btn btn-warning btn-xs' title='Show QRC' onClick='printQR(".$noU.")' style='display:none'>
										<i class='glyphicon glyphicon-qrcode'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='".$disCl."(".$noU.")' title='Void' ".$disV.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOCX(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<input type='hidden' name='urlPrintQR".$noU."' id='urlPrintQR".$noU."' value='".$secPrintQR."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='avascript:void(null);' class='btn btn-warning btn-xs' title='Show QRC' onClick='printQR(".$noU.")' style='display:none'>
										<i class='glyphicon glyphicon-qrcode'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOCX(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
												
				
				$output['data'][] = array("$noU.",
										  $dataI['IR_CODE'],
										  $IR_DATEV,
										  $SPLDESC,
										  $dataI['IR_NOTE'],
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  "<span class='label label-".$STATCOLV."' style='font-size:12px'>".$INVSTATDes."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
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
	
	function a180c15dd() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN067';
			$data["MenuApp"] 	= 'MN068';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Penerimaan Material";
				$data["h3_title"] 	= "inventaris";
			}
			else
			{
				$data["h2_title"] 	= "Item Receipt";
				$data["h3_title"] 	= "inventory";
			}
			
			$data['form_action']	= site_url('c_inventory/c_ir180c15/add_process');
			
			$data['backURL'] 		= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL']		= $this->m_itemreceipt->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemreceipt->viewvendor()->result();
			$data['countCUST']		= $this->m_itemreceipt->count_all_Cust();
			$data['vwCUST'] 		= $this->m_itemreceipt->viewcustomer()->result();
			
			$MenuCode 				= 'MN067';
			$data["MenuCode"] 		= 'MN067';
			$data["MenuCode1"] 		= 'MN068';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			$data['viewDocPattern'] = $this->m_itemreceipt->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN067';
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
			
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180c15dd50() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN067';
			$data["MenuApp"] 	= 'MN068';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Penerimaan Greige";
				$data["h3_title"] 	= "inventaris";
			}
			else
			{
				$data["h2_title"] 	= "Greige Receipt";
				$data["h3_title"] 	= "inventory";
			}
			
			$data['form_action']	= site_url('c_inventory/c_ir180c15/add_process50');
			
			$data['backURL'] 		= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data["MenuCode"] 		= 'MN067';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL']		= $this->m_itemreceipt->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemreceipt->viewvendor()->result();
			$data['countCUST']		= $this->m_itemreceipt->count_all_CustG();
			$data['vwCUST'] 		= $this->m_itemreceipt->viewcustomerG()->result();
			
			$MenuCode 				= 'MN067';
			$data["MenuCode1"] 		= 'MN068';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			$data['viewDocPattern'] = $this->m_itemreceipt->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN067';
				$TTR_CATEG		= 'A-SO';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt_form_so', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}		
	
	function add_process() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
					
			$IR_NUM		= $this->input->post('IR_NUM');			
			$IR_CODE		= $this->input->post('IR_CODE');
			$IR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$TERM_PAY		= $this->input->post('TERM_PAY');
			$IR_DUEDATE1	= strtotime ("+$TERM_PAY day", strtotime ($IR_DATE));
			$IR_DUEDATE		= date('Y-m-d', $IR_DUEDATE1);
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$accYr			= date('Y', strtotime($IR_DATE));
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$DEPCODE		= $this->input->post('DEPCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			$PO_NUM			= $this->input->post('PO_NUM');
			$PO_CODE		= $this->input->post('PO_CODE');
			$PR_NUM			= $this->input->post('PR_NUM');
			$PR_CODE		= $this->input->post('PR_CODE');
			$PR_CREATE		= $this->input->post('PR_CREATE');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN067';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				//$IR_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$SPLDESC		= '';
			$PageFrom		= $this->input->post('PageFrom');
			if($PageFrom == 'PO')
				$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			else
				$sqlSPL 		= "SELECT CUST_DESC AS SPLDESC FROM tbl_customer WHERE CUST_CODE = '$SPLCODE' LIMIT 1";
				
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= $rowSPL->SPLDESC;
			endforeach;
			
			if($IR_SOURCE == 1)		// Direct
			{
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$PR_NUM			= '';
				$PR_CODE		= '';
			}
			elseif($IR_SOURCE == 2)	// MR
			{
				$Ref_Number 	= $this->input->post('Ref_NumberMR');
				$PO_NUM 		= $this->input->post('Ref_NumberMR');
				$PR_NUM			= $this->input->post('Ref_NumberMR');
				$PR_CODE		= $this->input->post('Ref_NumberMR');
			}
			elseif($IR_SOURCE == 3)	// PO
			{
				$Ref_Number 	= $this->input->post('PO_NUM');
				$PR_NUM			= '';
				$PR_CODE		= '';
				$getRefMRfPO	= "SELECT PR_NUM, PR_CODE
									FROM tbl_po_header
									WHERE PO_NUM = '$Ref_Number'
									AND PRJCODE = '$PRJCODE'";
				$resgetMRfPO 	= $this->db->query($getRefMRfPO)->result();
				foreach($resgetMRfPO as $rowPO) :
					$PR_NUM 	= $rowPO->PR_NUM;
					$PR_CODE 	= $rowPO->PR_CODE;
				endforeach;
				$PO_NUM			= $Ref_Number;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PR_CODE;
			}
			else
			{
				$Ref_Number 	= $PO_NUM;
				$PO_NUM			= $Ref_Number;
				$PO_CODE		= $PO_CODE;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PR_CODE;
			}
			
			$IR_REFER		= $this->input->post('IR_REFER');
			$IR_REFER1		= $this->input->post('IR_REFER1');
			$IR_STAT		= $this->input->post('IR_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$INVSTAT		= 'NI';
			$IR_NOTE		= addslashes($this->input->post('IR_NOTE'));	
			$WH_CODE		= $this->input->post('WH_CODE');
			$IR_LOC			= $this->input->post('IR_LOC');

			$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
			$IR_DISC		= $this->input->post('IR_DISC');
			$IR_PPN			= $this->input->post('IR_PPN');
			$IR_AMOUNT_NETT	= $this->input->post('IR_AMOUNT_NETT');
			$TAXCODE_PPN	= $this->input->post('TAXCODE_PPN');
			$TAXCODE_PPH	= $this->input->post('TAXCODE_PPH');

			$IR_CREATED		= date('Y-m-d H:i:s');
			$IR_CREATER		= $DefEmp_ID;
			
			$insRR = array('IR_NUM' 		=> $IR_NUM,
							'IR_CODE' 		=> $IR_CODE,
							'IR_DATE'		=> $IR_DATE,
							'IR_DUEDATE'	=> $IR_DUEDATE,
							'IR_SOURCE'		=> $IR_SOURCE,
							'PRJCODE'		=> $PRJCODE,
							'DEPCODE'		=> $DEPCODE,
							'SPLCODE'		=> $SPLCODE,
							'SPLDESC'		=> $SPLDESC,
							'PO_NUM'		=> $PO_NUM,
							'PO_CODE'		=> $PO_CODE,
							'PR_NUM'		=> $PR_NUM,
							'PR_CODE'		=> $PR_CODE,
							'IR_REFER'		=> $IR_REFER,
							'IR_REFER1'		=> $IR_REFER1,
							'IR_AMOUNT'		=> $IR_AMOUNT,
							'IR_DISC'		=> $IR_DISC,
							'IR_PPN'		=> $IR_PPN,
							'IR_AMOUNT_NETT'=> $IR_AMOUNT_NETT,
							'TAXCODE_PPN'	=> $TAXCODE_PPN,
							'TAXCODE_PPH'	=> $TAXCODE_PPH,
							'TERM_PAY'		=> $TERM_PAY,
							'IR_STAT'		=> $IR_STAT,
							'INVSTAT'		=> $INVSTAT,
							'IR_NOTE'		=> $IR_NOTE,
							'WH_CODE'		=> $WH_CODE,
							'IR_LOC'		=> $IR_LOC,
							'IR_CREATED'	=> $IR_CREATED,
							'IR_CREATER'	=> $IR_CREATER,
							'PR_CREATE'		=> $PR_CREATE,
							'Patt_Date'		=> $Patt_Date,
							'Patt_Month'	=> $Patt_Month,
							'Patt_Year'		=> $Patt_Year,
							'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_itemreceipt->add($insRR);

			$POD_ID	= 0;
			$maxNo	= 0;
			$sqlMax = "SELECT MAX(IR_ID) AS maxNo FROM tbl_ir_detail";
			$resMax = $this->db->query($sqlMax)->result();
			foreach($resMax as $rowMax) :
				$maxNo = $rowMax->maxNo;		
			endforeach;

			$TOT_UA 			= 0;
			foreach($_POST['data'] as $d)
			{
				$maxNo			= $maxNo + 1;
				//$d['IR_ID']		= $maxNo;
				$d['IR_NUM']	= $IR_NUM;
				$d['WH_CODE']	= $WH_CODE;
				$d['IR_DATE']	= $IR_DATE;
				$d['IR_SOURCE']	= $IR_SOURCE;
				$d['PO_CODE']	= $PO_CODE;
				$d['PR_NUM']	= $PR_NUM;
				$d['PR_CODE']	= $PR_CODE;
				$d['DEPCODE']	= $DEPCODE;

				$ITMCODE		= $d['ITM_CODE'];
				$ITM_CATEG 		= "M";
				$ACC_ID_UM 		= "";
				$sqlITMNM		= "SELECT ITM_CATEG, ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITMCODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resITMNM		= $this->db->query($sqlITMNM)->result();
				foreach($resITMNM as $rowITMNM) :
					$ITM_CATEG	= $rowITMNM->ITM_CATEG;
					$ACC_ID_UM	= $rowITMNM->ACC_ID_UM;
				endforeach;

				$ISCOST 			= 0;
				if($ITM_CATEG == 'UA')
				{
					$d['ACC_ID']= $ACC_ID_UM;
					$ITM_TOTAL	= $d['ITM_TOTAL'];
					$TOT_UA 	= $TOT_UA + $ITM_TOTAL;
					$ISCOST 	= 1;
				}

				$d['ISCOST']		= $ISCOST;

				$this->db->insert('tbl_ir_detail',$d);
			}
			
			$IR_AMOUNT 			= $IR_AMOUNT - $TOT_UA;
			$IR_AMOUNT_NETT2 	= $IR_AMOUNT_NETT - $TOT_UA;
			$updIR 	= array('IR_AMOUNT' 		=> $IR_AMOUNT,
							'IR_AMOUNT_NETT'	=> $IR_AMOUNT_NETT2);
			$this->m_itemreceipt->updateIR($IR_NUM, $updIR);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('IR_STAT');			// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
										'DOC_CODE' 		=> $IR_NUM,
										'DOC_STAT' 		=> $IR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ir_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $IR_NUM;
				$MenuCode 		= 'MN067';
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

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "IR",
										'TR_DATE' 		=> $IR_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process50() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
					
			//$IR_NUM		= $this->input->post('IR_NUM');			
			$IR_CODE		= $this->input->post('IR_CODE');
			$IR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$TERM_PAY		= $this->input->post('TERM_PAY');
			$IR_DUEDATE1	= strtotime ("+$TERM_PAY day", strtotime ($IR_DATE));
			$IR_DUEDATE		= date('Y-m-d', $IR_DUEDATE1);
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$accYr			= date('Y', strtotime($IR_DATE));
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$DEPCODE		= $this->input->post('DEPCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			$PO_NUM			= $this->input->post('PO_NUM');
			$PO_CODE		= $this->input->post('PO_CODE');
			$PR_NUM			= $this->input->post('PR_NUM');
			$PR_CODE		= $this->input->post('PR_CODE');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN067';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$IR_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$SPLDESC		= '';
			$PageFrom		= $this->input->post('PageFrom');
			if($PageFrom == 'PO')
				$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			else
				$sqlSPL 		= "SELECT CUST_DESC AS SPLDESC FROM tbl_customer WHERE CUST_CODE = '$SPLCODE' LIMIT 1";
				
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= $rowSPL->SPLDESC;
			endforeach;
			
			if($IR_SOURCE == 1)		// Direct
			{
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$PR_NUM			= '';
				$PR_CODE		= '';
			}
			elseif($IR_SOURCE == 2)	// MR
			{
				$Ref_Number 	= $this->input->post('Ref_NumberMR');
				$PO_NUM 		= $this->input->post('Ref_NumberMR');
				$PR_NUM			= $this->input->post('Ref_NumberMR');
				$PR_CODE		= $this->input->post('Ref_NumberMR');
			}
			elseif($IR_SOURCE == 3)	// PO
			{
				$Ref_Number 	= $this->input->post('PO_NUM');
				$PR_NUM			= '';
				$PR_CODE		= '';
				$getRefMRfPO	= "SELECT PR_NUM, PO_CODE
									FROM tbl_po_header
									WHERE PO_NUM = '$Ref_Number'
									AND PRJCODE = '$PRJCODE'";
				$resgetMRfPO 	= $this->db->query($getRefMRfPO)->result();
				foreach($resgetMRfPO as $rowPO) :
					$PR_NUM 	= $rowPO->PR_NUM;
					$PO_CODE 	= $rowPO->PO_CODE;
				endforeach;
				$PO_NUM			= $Ref_Number;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PO_CODE;
			}
			else
			{
				$Ref_Number 	= $PO_NUM;
				$PO_NUM			= $Ref_Number;
				$PO_CODE		= $PO_CODE;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PR_CODE;
			}
			
			$IR_REFER		= $this->input->post('IR_REFER');
			$IR_REFER1		= $this->input->post('IR_REFER1');
			$IR_REFER2		= $this->input->post('IR_REFER2');
			$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
			$IR_STAT		= $this->input->post('IR_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$INVSTAT		= 'NI';
			$IR_NOTE		= addslashes($this->input->post('IR_NOTE'));	
			$WH_CODE		= $this->input->post('WH_CODE');	
			$IR_LOC			= $this->input->post('IR_LOC');
			$IR_CREATED		= date('Y-m-d H:i:s');
			$IR_CREATER		= $DefEmp_ID;
			
			$insRR = array('IR_NUM' 		=> $IR_NUM,
							'IR_CODE' 		=> $IR_CODE,
							'IR_DATE'		=> $IR_DATE,
							'IR_DUEDATE'	=> $IR_DUEDATE,
							'IR_SOURCE'		=> $IR_SOURCE,
							'PRJCODE'		=> $PRJCODE,
							'DEPCODE'		=> $DEPCODE,
							'SPLCODE'		=> $SPLCODE,
							'SPLDESC'		=> $SPLDESC,
							'PO_NUM'		=> $PO_NUM,
							'PO_CODE'		=> $PO_CODE,
							'PR_NUM'		=> $PR_NUM,
							'PR_CODE'		=> $PR_CODE,
							'IR_REFER'		=> $IR_REFER,
							'IR_REFER1'		=> $IR_REFER1,
							'IR_REFER2'		=> $IR_REFER2,
							'IR_AMOUNT'		=> $IR_AMOUNT,
							'TERM_PAY'		=> $TERM_PAY,
							'IR_STAT'		=> $IR_STAT,
							'INVSTAT'		=> $INVSTAT,
							'IR_NOTE'		=> $IR_NOTE,
							'WH_CODE'		=> $WH_CODE,
							'IR_LOC'		=> $IR_LOC,
							'IR_CREATED'	=> $IR_CREATED,
							'IR_CREATER'	=> $IR_CREATER,
							'Patt_Date'		=> $Patt_Date,
							'Patt_Month'	=> $Patt_Month,
							'Patt_Year'		=> $Patt_Year,
							'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_itemreceipt->add($insRR);
			
			$POD_ID	= 0;
			$maxNo	= 0;
			$sqlMax = "SELECT MAX(IR_ID) AS maxNo FROM tbl_ir_detail";
			$resMax = $this->db->query($sqlMax)->result();
			foreach($resMax as $rowMax) :
				$maxNo = $rowMax->maxNo;		
			endforeach;
			foreach($_POST['data'] as $d)
			{
				$maxNo			= $maxNo + 1;
				$d['IR_ID']		= $maxNo;
				$d['IR_NUM']	= $IR_NUM;
				$d['IR_CODE']	= $IR_CODE;
				$d['WH_CODE']	= $WH_CODE;
				$d['IR_SOURCE']	= $IR_SOURCE;
				$d['IR_DATE']	= $IR_DATE;
				$d['PO_CODE']	= $PO_CODE;
				$d['DEPCODE']	= $DEPCODE;
				$this->db->insert('tbl_ir_detail',$d);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('IR_STAT');			// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
										'DOC_CODE' 		=> $IR_NUM,
										'DOC_STAT' 		=> $IR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ir_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $IR_NUM;
				$MenuCode 		= 'MN067';
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
			
			$url			= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up180c15dt() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN067';
			$data["MenuApp"] 	= 'MN068';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$IR_NUM		= $EXTRACTCOL[1];
					
			$getrr 					= $this->m_itemreceipt->get_IR_by_number($IR_NUM)->row();			
			$PRJCODE				= $getrr->PRJCODE;
			$data["MenuCode"] 		= 'MN067';
			$data["MenuCode1"] 		= 'MN068';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Item Receipt';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_ir180c15/update_process');
			//$linkBack				= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 		= array('link_back' => anchor("$linkBack",'<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL']		= $this->m_itemreceipt->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemreceipt->viewvendor()->result();
			
			$data['default']['IR_NUM'] 		= $getrr->IR_NUM;
			$data['default']['IR_CODE'] 	= $getrr->IR_CODE;
			$data['default']['IR_DATE'] 	= $getrr->IR_DATE;
			$data['default']['IR_DUEDATE'] 	= $getrr->IR_DUEDATE;
			$data['default']['IR_SOURCE'] 	= $getrr->IR_SOURCE;
			$IR_SOURCE						= $getrr->IR_SOURCE;
			$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
			$data['default']['DEPCODE'] 	= $getrr->DEPCODE;
			$data['default']['SPLCODE'] 	= $getrr->SPLCODE;
			$SPLCODE						= $getrr->SPLCODE;
			$data['default']['PO_NUM'] 		= $getrr->PO_NUM;
			$data['default']['PO_CODE'] 	= $getrr->PO_CODE;
			$data['default']['PO_NUMX'] 	= $getrr->PO_NUM;
			$data['default']['PR_NUM'] 		= $getrr->PR_NUM;
			$data['default']['PR_CODE'] 	= $getrr->PR_CODE;
			$data['default']['IR_REFER'] 	= $getrr->IR_REFER;
			$data['default']['IR_REFER1'] 	= $getrr->IR_REFER1;
			$data['default']['IR_REFER2'] 	= $getrr->IR_REFER2;
			$data['default']['IR_AMOUNT'] 	= $getrr->IR_AMOUNT;
			$data['default']['IR_AMOUNT'] 	= $getrr->IR_AMOUNT;
			$data['default']['TERM_PAY'] 	= $getrr->TERM_PAY;
			$data['default']['TRXUSER'] 	= $getrr->TRXUSER;
			$data['default']['APPROVE'] 	= $getrr->APPROVE;
			$data['default']['IR_STAT'] 	= $getrr->IR_STAT;
			$data['default']['INVSTAT'] 	= $getrr->INVSTAT;
			$data['default']['IR_NOTE'] 	= $getrr->IR_NOTE;
			$data['default']['IR_LOC'] 		= $getrr->IR_LOC;
			$data['default']['IR_LOC'] 		= $getrr->IR_LOC;
			$data['default']['IR_NOTE2'] 	= $getrr->IR_NOTE2;
			$data['default']['REVMEMO']		= $getrr->REVMEMO;
			$data['default']['WH_CODE']		= $getrr->WH_CODE;
			$data['default']['PR_CREATE']	= $getrr->PR_CREATE;
			$data['default']['Patt_Year'] 	= $getrr->Patt_Year;
			$data['default']['Patt_Number'] = $getrr->Patt_Number;
			
			$data['countCUST']		= $this->m_itemreceipt->count_all_CustG();
			$data['vwCUST'] 		= $this->m_itemreceipt->viewcustomerG()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getrr->IR_NUM;
				$MenuCode 		= 'MN067';
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
			
			if($IR_SOURCE == 1)
				$this->load->view('v_inventory/v_itemreceipt/itemreceipt_form_dir', $data);
			elseif($IR_SOURCE == 3)
				$this->load->view('v_inventory/v_itemreceipt/itemreceipt_form', $data);
			elseif($IR_SOURCE == 4)
				$this->load->view('v_inventory/v_itemreceipt/itemreceipt_form_so', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		date_default_timezone_set("Asia/Jakarta");
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
			
			$IR_NUM			= $this->input->post('IR_NUM');			
			$IR_CODE		= $this->input->post('IR_CODE');
			$IR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$TERM_PAY		= $this->input->post('TERM_PAY');
			$IR_DUEDATE1	= strtotime ("+$TERM_PAY day", strtotime ($IR_DATE));
			$IR_DUEDATE		= date('Y-m-d', $IR_DUEDATE1);
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$accYr			= date('Y', strtotime($IR_DATE));
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$DEPCODE		= $this->input->post('DEPCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			$PO_NUM			= $this->input->post('PO_NUM');
			$PO_CODE		= $this->input->post('PO_CODE');
			$PR_NUM			= $this->input->post('PR_NUM');
			$PR_CODE		= $this->input->post('PR_CODE');
			$PR_CREATE		= $this->input->post('PR_CREATE');

			$SPLDESC		= '';
			$sqlSPLC 		= "tbl_supplier WHERE SPLCODE = '$SPLCODE'";
			$resSPLC		= $this->db->count_all($sqlSPLC);
			if($resSPLC > 0)
				$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			else
				$sqlSPL 		= "SELECT CUST_DESC AS SPLDESC FROM tbl_customer WHERE CUST_CODE = '$SPLCODE' LIMIT 1";
				
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= $rowSPL->SPLDESC;
			endforeach;
			
			if($IR_SOURCE == 1)		// Direct
			{
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$PR_NUM			= '';
				$PR_CODE		= '';
			}
			elseif($IR_SOURCE == 2)	// MR
			{
				$Ref_Number 	= $this->input->post('Ref_NumberMR');
				$PO_NUM 		= $this->input->post('Ref_NumberMR');
				$PR_NUM			= $this->input->post('Ref_NumberMR');
				$PR_CODE		= $this->input->post('Ref_NumberMR');
			}
			elseif($IR_SOURCE == 3)	// PO
			{
				$Ref_Number 	= $this->input->post('PO_NUM');
				$PR_NUM			= '';
				$PR_CODE		= '';
				$getRefMRfPO	= "SELECT PR_NUM, PR_CODE
									FROM tbl_po_header
									WHERE PO_NUM = '$Ref_Number'
									AND PRJCODE = '$PRJCODE'";
				$resgetMRfPO 	= $this->db->query($getRefMRfPO)->result();
				foreach($resgetMRfPO as $rowPO) :
					$PR_NUM 	= $rowPO->PR_NUM;
					$PR_CODE 	= $rowPO->PR_CODE;
				endforeach;
				$PO_NUM			= $Ref_Number;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PR_CODE;
			}
			else
			{
				$Ref_Number 	= $PO_NUM;
				$PO_NUM			= $Ref_Number;
				$PO_CODE		= $PO_CODE;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PR_CODE;
			}
			
			$IR_REFER		= $this->input->post('IR_REFER');
			$IR_REFER1		= $this->input->post('IR_REFER1');
			$IR_STAT		= $this->input->post('IR_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$INVSTAT		= 'NI';
			$IR_NOTE		= addslashes($this->input->post('IR_NOTE'));			
			$WH_CODE		= $this->input->post('WH_CODE');			
			$IR_LOC			= $this->input->post('IR_LOC');

			$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
			$IR_DISC		= $this->input->post('IR_DISC');
			$IR_PPN			= $this->input->post('IR_PPN');
			$IR_AMOUNT_NETT	= $this->input->post('IR_AMOUNT_NETT');
			$TAXCODE_PPN	= $this->input->post('TAXCODE_PPN');
			$TAXCODE_PPH	= $this->input->post('TAXCODE_PPH');

			/*$IR_CREATED		= date('Y-m-d H:i:s');
			$IR_CREATER		= $DefEmp_ID;*/
			
			$updIR = array('IR_CODE' 		=> $IR_CODE,
							'IR_DATE'		=> $IR_DATE,
							'IR_DUEDATE'	=> $IR_DUEDATE,
							'IR_SOURCE'		=> $IR_SOURCE,
							'PRJCODE'		=> $PRJCODE,
							'DEPCODE'		=> $DEPCODE,
							'SPLCODE'		=> $SPLCODE,
							'SPLDESC'		=> $SPLDESC,
							'PO_NUM'		=> $PO_NUM,
							'PO_CODE'		=> $PO_CODE,
							'PR_NUM'		=> $PR_NUM,
							'PR_CODE'		=> $PR_CODE,
							'IR_REFER'		=> $IR_REFER,
							'IR_REFER1'		=> $IR_REFER1,
							'IR_AMOUNT'		=> $IR_AMOUNT,
							'IR_DISC'		=> $IR_DISC,
							'IR_PPN'		=> $IR_PPN,
							'IR_AMOUNT_NETT'=> $IR_AMOUNT_NETT,
							'TAXCODE_PPN'	=> $TAXCODE_PPN,
							'TAXCODE_PPH'	=> $TAXCODE_PPH,
							'TERM_PAY'		=> $TERM_PAY,
							'IR_STAT'		=> $IR_STAT,
							'INVSTAT'		=> $INVSTAT,
							'IR_NOTE'		=> $IR_NOTE,
							'IR_LOC'		=> $IR_LOC,
							'PR_CREATE'		=> $PR_CREATE,
							'WH_CODE'		=> $WH_CODE);
			$this->m_itemreceipt->updateIR($IR_NUM, $updIR);
			
			if($IR_STAT == 9)
			{
				$IR_NOTE2			= addslashes($this->input->post('IR_NOTE2'));

				$updIR 				= array('IR_NOTE2'		=> $IR_NOTE2);
				$this->m_itemreceipt->updateIR($IR_NUM, $updIR);

				// 1. UPDATE STATUS	
					$DOCSource		= '';
					if($IR_SOURCE == 2)		// MR
					{
						$DOCSource		= "MRXXXXXXXX";
					}
					elseif($IR_SOURCE == 3)	// PO
					{
						$Ref_Number 	= $this->input->post('PO_NUM');
						$PO_NUM			= $Ref_Number;
						$DOCSource		= $PO_NUM;
						//$this->m_itemreceipt->updatePO($IR_NUM, $PRJCODE, $PO_NUM, $ISDIRECT); // UPDATE PO
						//$this->m_itemreceipt->updatePO($IR_NUM, $PRJCODE, $PO_NUM); // UPDATE PO
					}
					elseif($IR_SOURCE == 1)	// DIRECTS
					{
						$Ref_Number 	= '';
						$PO_NUM			= '';
						$DOCSource		= 'Direct';
						$this->m_itemreceipt->updateJOBDET($IR_NUM, $PRJCODE); // UPDATE JOB DETAIL
					}

				// 2. UPDATE STATUS
					$paramSTAT 	= array('JournalHCode' 	=> $IR_NUM);
					$this->m_updash->updSTATJD($paramSTAT);

					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 9, STATCOL = 'danger', STATDESC = 'Void' WHERE JournalH_Code = '$IR_NUM'";
					$this->db->query($upJH);

					$upJD	= "UPDATE tbl_journaldetail SET GEJ_STAT = 9, isVoid = 1 WHERE JournalH_Code = '$IR_NUM'";
					$this->db->query($upJD);

				// 3. MEMBUAT JURNAL PEMBALIK
					$sqlDET 	= "SELECT Acc_Id, proj_Code, JOBCODEID, ITM_CODE, ITM_GROUP, ITM_VOLM, ITM_PRICE, Base_Debet, Base_Kredit, Journal_DK
									FROM tbl_journaldetail WHERE JournalH_Code = '$IR_NUM'";
					$resDET 	= $this->db->query($sqlDET)->result();
					foreach($resDET as $rowDET) :
						$ACC_NUM 		= $rowDET->Acc_Id;
						$PRJCODE 		= $rowDET->proj_Code;
						$JOBCODEID 		= $rowDET->JOBCODEID;
						$ITM_CODE 		= $rowDET->ITM_CODE;
						$ITM_GROUP 		= $rowDET->ITM_GROUP;
						$ITM_VOLM 		= $rowDET->ITM_VOLM;
						$ITM_PRICE 		= $rowDET->ITM_PRICE;
						$Base_Debet 	= $rowDET->Base_Debet;
						$Base_Kredit 	= $rowDET->Base_Kredit;
						$Journal_DK 	= $rowDET->Journal_DK;

						$ITM_TYPE 	= $this->m_updash->get_itmType($PRJCODE, $ITM_CODE);
						if($ITM_TYPE == 0)
							$ITM_TYPE	= 1;

						$PRJCODE		= $PRJCODE;
						$JOURN_DATE		= $IR_DATE;
						$ITM_GROUP		= $ITM_GROUP;
						$ITM_TYPE		= $ITM_TYPE;
						$ITM_QTY 		= $ITM_VOLM;
						if($ITM_QTY == 0 || $ITM_QTY == '')
							$ITM_QTY	= 1;

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
								if($Journal_DK == 'D')
								{
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
														Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
								}
								else
								{
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
														Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
								}
								$this->db->query($sqlUpdCOA);
							}
						}
					endforeach;

				// 4. UPDATE DETAIL PR, PO
					$sqlIRDET 	= "SELECT IR_CODE, IR_DATE, ACC_ID, IR_SOURCE, PO_NUM, JOBCODEID, WH_CODE, ITM_CODE, ITM_GROUP, ITM_QTY, ITM_PRICE, ITM_TOTAL,
										POD_ID, ITM_UNIT, ITM_QTY_BONUS, ITM_DISC, TAXCODE1, TAXPRICE1, PR_NUM, PRD_ID
									FROM tbl_ir_detail WHERE IR_NUM = '$IR_NUM' AND  PRJCODE = '$PRJCODE'";
					$resIRDET 	= $this->db->query($sqlIRDET)->result();
					foreach($resIRDET as $rowIRDET) :
						$IR_CODE 		= $rowIRDET->IR_CODE;
						$IR_DATE 		= $rowIRDET->IR_DATE;
						$ACC_ID 		= $rowIRDET->ACC_ID;
						$IR_SOURCE 		= $rowIRDET->IR_SOURCE;
						$PO_NUM 		= $rowIRDET->PO_NUM;
						$POD_ID 		= $rowIRDET->POD_ID;
						$PR_NUM 		= $rowIRDET->PR_NUM;
						$PRD_ID 		= $rowIRDET->PRD_ID;
						$JOBCODEID 		= $rowIRDET->JOBCODEID;
						$WH_CODE 		= $rowIRDET->WH_CODE;
						$ITM_CODE 		= $rowIRDET->ITM_CODE;
						$ITM_GROUP 		= $rowIRDET->ITM_GROUP;
						$ITM_UNIT 		= $rowIRDET->ITM_UNIT;
						$ITM_QTY 		= $rowIRDET->ITM_QTY;
						$ITM_PRICE 		= $rowIRDET->ITM_PRICE;
						$ITM_TOTAL 		= $rowIRDET->ITM_TOTAL;
						$ITM_QTY_BONUS 	= $rowIRDET->ITM_QTY_BONUS;
						$ITM_DISC 		= $rowIRDET->ITM_DISC;
						$TAXCODE1 		= $rowIRDET->TAXCODE1;
						$TAXPRICE1 		= $rowIRDET->TAXPRICE1;

						$ITM_TYPE 	= $this->m_updash->get_itmType($PRJCODE, $ITM_CODE);
						if($ITM_TYPE == 0)
							$ITM_TYPE	= 1;
					
						$param2		= array('JournalH_Code' 	=> $IR_NUM,
											'IR_DATE'			=> $IR_DATE,
											'JOBCODEID' 		=> $JOBCODEID,
											'JOBCODEID' 		=> $JOBCODEID,
											'PO_NUM'			=> $PO_NUM,
											'POD_ID'			=> $POD_ID,
											'PR_NUM'			=> $PR_NUM,
											'PRD_ID'			=> $PRD_ID,
											'PRJCODE'			=> $PRJCODE,
											'ITM_CODE' 			=> $ITM_CODE,
											'ACC_ID' 			=> $ACC_ID,
											'ITM_UNIT' 			=> $ITM_UNIT,
											'ITM_GROUP' 		=> $ITM_GROUP,
											'ITM_QTY' 			=> $ITM_QTY,
											'ITM_QTY_BONUS' 	=> $ITM_QTY_BONUS,
											'ITM_PRICE' 		=> $ITM_PRICE,
											'ITM_DISC' 			=> $ITM_DISC,
											'TAXCODE1' 			=> $TAXCODE1,
											'TAXPRICE1' 		=> $TAXPRICE1,
											'WH_CODE'			=> $WH_CODE,
											'IR_NOTE2'			=> $IR_NOTE2);
						$this->m_itemreceipt->updateIR_PO($IR_NUM, $param2);

						$PERIODM	= date('m', strtotime($IR_DATE));
						$PERIODY	= date('Y', strtotime($IR_DATE));
						$JOURN_VAL 	= $ITM_TOTAL;

						if($ITM_GROUP == 'M')
						{
							// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFUEL, 5.ISLUBTICANT, 6.ISFASTM, 7.ISWAGE, 8.ISRM, 9.ISWIP, 10.ISFG, 11.ISCOST
							if($ITM_TYPE == 1 || $ITM_TYPE == 8)
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$JOURN_VAL
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
							elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$JOURN_VAL
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
							elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$JOURN_VAL
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
							elseif($ITM_TYPE == 9)
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$JOURN_VAL
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
							elseif($ITM_TYPE == 10)
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$JOURN_VAL
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
						}
						elseif($ITM_GROUP == 'T')
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$JOURN_VAL
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
					endforeach;
				
				$upJD		= "UPDATE tbl_journaldetail SET isVoid = 1 WHERE JournalH_Code = '$IR_NUM'";
				$this->db->query($upJD);
				
				// START : UPDATE TO DOC. COUNT
					$parameters 	= array('DOC_CODE' 		=> $IR_NUM,
											'PRJCODE' 		=> $PRJCODE,
											'DOC_TYPE'		=> "IR",
											'DOC_QTY' 		=> "DOC_IRQ",
											'DOC_VAL' 		=> "DOC_IRV",
											'DOC_STAT' 		=> 'VOID');
					$this->m_updash->updateDocC($parameters);
				// END : UPDATE TO DOC. COUNT
			}
			else
			{
				$this->m_itemreceipt->deleteIRDetail($IR_NUM);

				$POD_ID	= 0;
				$maxNo	= 0;
				$sqlMax = "SELECT MAX(IR_ID) AS maxNo FROM tbl_ir_detail";
				$resMax = $this->db->query($sqlMax)->result();
				foreach($resMax as $rowMax) :
					$maxNo = $rowMax->maxNo;		
				endforeach;
				foreach($_POST['data'] as $d)
				{
					$maxNo			= $maxNo + 1;
					$d['IR_ID']		= $maxNo;
					$d['WH_CODE']	= $WH_CODE;
					$d['IR_CODE']	= $IR_CODE;
					$d['IR_DATE']	= $IR_DATE;
					$d['IR_SOURCE']	= $IR_SOURCE;
					$d['PO_CODE']	= $PO_CODE;
					$d['PR_NUM']	= $PR_NUM;
					$d['PR_CODE']	= $PR_CODE;
					$d['DEPCODE']	= $DEPCODE;

					$ITMCODE		= $d['ITM_CODE'];
					$ITM_CATEG 		= "M";
					$ACC_ID_UM 		= "";
					$sqlITMNM		= "SELECT ITM_CATEG, ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITMCODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_CATEG	= $rowITMNM->ITM_CATEG;
						$ACC_ID_UM	= $rowITMNM->ACC_ID_UM;
					endforeach;

					$ISCOST 			= 0;
					if($ITM_CATEG == 'UA')
					{
						$d['ACC_ID']= $ACC_ID_UM;
						$ITM_TOTAL	= $d['ITM_TOTAL'];
						$TOT_UA 	= $TOT_UA + $ITM_TOTAL;
						$ISCOST 	= 1;
					}

					$ISCOST 			= 0;
					if($ITM_CATEG == 'UA')
						$ISCOST 		= 1;

					$d['ISCOST']		= $ISCOST;

					$this->db->insert('tbl_ir_detail',$d);
				}
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
										'DOC_CODE' 		=> $IR_NUM,
										'DOC_STAT' 		=> $IR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ir_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $IR_NUM);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $IR_NUM;
				$MenuCode 		= 'MN067';
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

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "IR",
										'TR_DATE' 		=> $IR_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			// DELETE temporary table
				if($IR_STAT != 1 && $IR_STAT != 4)
				{
					$s_01		= "DELETE FROM tbl_ir_detail_tmp WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function all180c15po() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
					
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt_sel_po', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function all180c155o() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
					
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt_sel_source', $data);
		}
		else
		{
			redirect('__l1y');
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
		$ISDIRECT	= $this->input->post('ISDIRECT');
		
		$IRDate		= date('Y-m-d',strtotime($this->input->post('IRDate')));
		$year		= date('Y',strtotime($this->input->post('IRDate')));
		$month 		= (int)date('m',strtotime($this->input->post('IRDate')));
		$date 		= (int)date('d',strtotime($this->input->post('IRDate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_ir_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_ir_header
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
	
	function a180c15ddDir() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN067';
			$data["MenuApp"] 	= 'MN068';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Item Receipt';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_ir180c15/addDir_process');
			
			$data['backURL'] 		= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data["MenuCode"] 		= 'MN067';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countSUPL']		= $this->m_itemreceipt->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemreceipt->viewvendor()->result();
			
			$MenuCode 				= 'MN067';
			$data['viewDocPattern'] = $this->m_itemreceipt->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN067';
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
			
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt_form_dir', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pop180c22all() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Material";
				$data["h3_title"] 	= "inventaris";
			}
			else
			{
				$data["h2_title"] 	= "List Item";
				$data["h3_title"] 	= "inventory";
			}
			
			$data['PRJCODE'] 			= $PRJCODE;
			
			$data['countAllItem'] 	= $this->m_itemreceipt->count_allItem($PRJCODE);
			$data['vwAllItem'] 		= $this->m_itemreceipt->viewAllItem($PRJCODE)->result();
			$data['countAllItemS'] 	= $this->m_itemreceipt->count_allItemSubs($PRJCODE);
			$data['vwAllItemS'] 	= $this->m_itemreceipt->viewAllItemSubs($PRJCODE)->result();
			$data['countAllItemO'] 	= $this->m_itemreceipt->count_allItemOth($PRJCODE);
			$data['vwAllItemO'] 	= $this->m_itemreceipt->viewAllItemOth($PRJCODE)->result();
					
			$this->load->view('v_inventory/v_itemreceipt/item_list_selectitem_dir', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pop180c22all_f9() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Material";
				$data["h3_title"] 	= "inventaris";
			}
			else
			{
				$data["h2_title"] 	= "List Item";
				$data["h3_title"] 	= "inventory";
			}
			
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['countAllItem'] 	= $this->m_itemreceipt->count_allItemG($PRJCODE);
			$data['vwAllItem'] 		= $this->m_itemreceipt->viewAllItemG($PRJCODE)->result();
			$data['countAllItemS'] 	= $this->m_itemreceipt->count_allItemSubsG($PRJCODE);
			$data['vwAllItemS'] 	= $this->m_itemreceipt->viewAllItemSubsG($PRJCODE)->result();
			$data['countAllItemO'] 	= $this->m_itemreceipt->count_allItemOthG($PRJCODE);
			$data['vwAllItemO'] 	= $this->m_itemreceipt->viewAllItemOthG($PRJCODE)->result();
			
			$data['countAllItemG'] 	= $this->m_itemreceipt->count_allIGreige($PRJCODE);
			$data['vwAllItemG'] 	= $this->m_itemreceipt->viewAllIGreige($PRJCODE)->result();
					
			$this->load->view('v_inventory/v_itemreceipt/item_list_selectitem_dir_so', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pop4dD_QC() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Tambah Kode Serial";
				$data["h3_title"] 	= "inventaris";
			}
			else
			{
				$data["h2_title"] 	= "Add Serial Code";
				$data["h3_title"] 	= "inventory";
			}
			
			$data['PRJCODE'] 			= $PRJCODE;
			
			$data['countAllItem'] 	= $this->m_itemreceipt->count_allItem($PRJCODE);
			$data['vwAllItem'] 		= $this->m_itemreceipt->viewAllItem($PRJCODE)->result();
			$data['countAllItemS'] 	= $this->m_itemreceipt->count_allItemSubs($PRJCODE);
			$data['vwAllItemS'] 	= $this->m_itemreceipt->viewAllItemSubs($PRJCODE)->result();
			$data['countAllItemO'] 	= $this->m_itemreceipt->count_allItemOth($PRJCODE);
			$data['vwAllItemO'] 	= $this->m_itemreceipt->viewAllItemOth($PRJCODE)->result();
					
			$this->load->view('v_inventory/v_itemreceipt/create_qrcode', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addDir_process() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
					
			//$IR_NUM		= $this->input->post('IR_NUM');			
			$IR_CODE		= $this->input->post('IR_CODE');
			$IR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$TERM_PAY		= $this->input->post('TERM_PAY');
			$IR_DUEDATE1	= strtotime ("+$TERM_PAY day", strtotime ($IR_DATE));
			$IR_DUEDATE		= date('Y-m-d', $IR_DUEDATE1);
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$accYr			= date('Y', strtotime($IR_DATE));
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			
			// START : CHECK THE CODE	
				/*$DOC_NUM		= $IR_NUM;
				$DOC_CODE		= $IR_CODE;
				$DOC_DATE		= $IR_DATE;
				$MenuCode 		= 'MN067';
				$TABLE_NAME		= 'tbl_ir_header';
				$FIELD_NAME		= 'IR_NUM';
				
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
					$DOC_NUM1	= $splitCode[0];
					$DOC_NUM	= "$DOC_NUM1-D";
					if($DOC_CODE == '')
					{
						$DOC_CODE	= $splitCode[1];
					}
					$Patt_Number= $splitCode[2];
				}
				
				$IR_NUM			= $DOC_NUM;
				$IR_CODE		= $DOC_CODE;*/
			// END : CHECK CODE
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN067';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$IR_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1-D";
			// END - PEMBENTUKAN GENERATE CODE
			
			$SPLDESC		= '';
			$PageFrom		= $this->input->post('PageFrom');
			if($PageFrom == 'PO')
				$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			else
				$sqlSPL 		= "SELECT CUST_DESC AS SPLDESC FROM tbl_customer WHERE CUST_CODE = '$SPLCODE' LIMIT 1";
				
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= $rowSPL->SPLDESC;
			endforeach;
				
			if($IR_SOURCE == 1)		// Direct
			{
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$PR_NUM			= '';
				$PR_CODE		= '';
			}
			elseif($IR_SOURCE == 2)	// MR
			{
				$Ref_Number 	= $this->input->post('Ref_NumberMR');
				$PO_NUM 		= $this->input->post('Ref_NumberMR');
				$PR_NUM			= $this->input->post('Ref_NumberMR');
				$PR_CODE		= $this->input->post('Ref_NumberMR');
			}
			elseif($IR_SOURCE == 3)	// PO
			{
				$Ref_Number 	= $this->input->post('PO_NUM');
				$PR_NUM			= '';
				$PR_CODE		= '';
				$getRefMRfPO	= "SELECT PR_NUM, PR_CODE
									FROM tbl_po_header
									WHERE PO_NUM = '$Ref_Number'
									AND PRJCODE = '$PRJCODE'";
				$resgetMRfPO 	= $this->db->query($getRefMRfPO)->result();
				foreach($resgetMRfPO as $rowPO) :
					$PR_NUM 	= $rowPO->PR_NUM;
					$PR_CODE 	= $rowPO->PR_CODE;
				endforeach;
				$PO_NUM			= $Ref_Number;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PR_CODE;
			}
			$IR_REFER		= "Direct";
			$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
			$IR_STAT		= $this->input->post('IR_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$INVSTAT		= 'NI';
			$IR_NOTE		= addslashes($this->input->post('IR_NOTE'));			
			$WH_CODE		= $this->input->post('WH_CODE');			
			$IR_LOC			= $this->input->post('IR_LOC');
			$IR_CREATED		= date('Y-m-d H:i:s');
			$IR_CREATER		= $DefEmp_ID;
			
			$insRR = array('IR_NUM' 		=> $IR_NUM,
							'IR_CODE' 		=> $IR_CODE,
							'IR_DATE'		=> $IR_DATE,
							'IR_DUEDATE'	=> $IR_DUEDATE,
							'IR_SOURCE'		=> $IR_SOURCE,
							'PRJCODE'		=> $PRJCODE,
							'SPLCODE'		=> $SPLCODE,
							'SPLDESC'		=> $SPLDESC,
							'PO_NUM'		=> $PO_NUM,
							'PR_NUM'		=> $PR_NUM,
							'IR_REFER'		=> $IR_CODE,
							'IR_AMOUNT'		=> $IR_AMOUNT,
							'TERM_PAY'		=> $TERM_PAY,
							'IR_STAT'		=> $IR_STAT,
							'INVSTAT'		=> $INVSTAT,
							'IR_NOTE'		=> $IR_NOTE,
							'WH_CODE'		=> $WH_CODE,
							'IR_LOC'		=> $IR_LOC,
							'IR_CREATED'	=> $IR_CREATED,
							'IR_CREATER'	=> $IR_CREATER,
							'Patt_Date'		=> $Patt_Date,
							'Patt_Month'	=> $Patt_Month,
							'Patt_Year'		=> $Patt_Year,
							'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_itemreceipt->add($insRR);
			
			$POD_ID	= 0;
			foreach($_POST['data'] as $d)
			{
				$d['IR_NUM']	= $IR_NUM;
				$this->db->insert('tbl_ir_detail',$d);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('IR_STAT');			// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
										'DOC_CODE' 		=> $IR_NUM,
										'DOC_STAT' 		=> $IR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ir_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $IR_NUM;
				$MenuCode 		= 'MN067';
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

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "IR",
										'TR_DATE' 		=> $IR_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
 	function indexInb() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_ir180c15/iR7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function iR7_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN068';
				$data["MenuApp"] 	= 'MN068';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
				
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
			$data["MenuCode"] 	= 'MN068';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_inventory/c_ir180c15/in180c15box/?id=";
			
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

    function in180c15box() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN068';
			$data["MenuApp"] 	= 'MN068';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
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
			
				$num_rows 			= $this->m_itemreceipt->count_all_IR_OUT($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_itemreceipt->get_all_IR_OUT($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
						
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Penerimaan Material";
				$data["h3_title"] 	= "persetujuan";
			}
			else
			{
				$data["h2_title"] 	= "Item Receipt";
				$data["h3_title"] 	= "approval";
			}
			
	 		$data["PRJCODE"] 	= $PRJCODE;
	 		$data["MenuCode"] 	= 'MN068';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';				
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN068';
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
			
			$this->load->view('v_inventory/v_itemreceipt/inb_itemreceipt', $data);
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
			$url			= site_url('c_inventory/c_ir180c15/in180c15box/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("ID",
									"IR_CODE",
									"IR_DATE",
									"SPLDESC",
									"IR_NOTE",
									"STATDESC",
									"INVSTAT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_itemreceipt->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_itemreceipt->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$IR_NUM		= $dataI['IR_NUM'];
				$IR_CODE	= $dataI['IR_CODE'];
				
				$IR_DATE	= $dataI['IR_DATE'];
				$IR_DATEV	= date('d M Y', strtotime($IR_DATE));
				
				$PRJCODE	= $dataI['PRJCODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$SPLDESC	= $dataI['SPLDESC'];
				$PO_NUM		= $dataI['PO_NUM'];
				$IR_REFER	= $dataI['IR_REFER'];
				$IR_AMOUNT	= $dataI['IR_AMOUNT'];
				$IR_NOTE	= $dataI['IR_NOTE'];
				$IR_STAT	= $dataI['IR_STAT'];
				
				if($SPLDESC	== '')
				{
					$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
					$resultS 	= $this->db->query($sqlS)->result();
					foreach($resultS as $rowS) :
						$SPLDESC = $rowS->SPLDESC;
					endforeach;
				}
				
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$INVSTAT	= $dataI['INVSTAT'];									
				if($INVSTAT == 'NI')
				{
					$INVSTATDes	= 'No';
					$STATCOLV	= 'danger';
				}
				elseif($INVSTAT == 'HI')
				{
					$INVSTATDes	= 'Half';
					$STATCOLV	= 'warning';
				}
				elseif($INVSTAT == 'FI')
				{
					$INVSTATDes	= 'Full';
					$STATCOLV	= 'success';
				}
				
				$REVMEMO		= $dataI['REVMEMO'];
				$ISDIRECT		= $dataI['IR_SOURCE'];	
				
				$CollID			= "$PRJCODE~$IR_NUM";
				$secUpd			= site_url('c_inventory/c_ir180c15/up180c15dtinb/?id='.$this->url_encryption_helper->encode_url($CollID));
							
				$secPrint	= site_url('c_inventory/c_ir180c15/printdocument/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
				$secPrintQR	= site_url('c_inventory/c_ir180c15/printQR/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
				$secVoid	= site_url('c_inventory/c_ir180c15/voiddocument/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
				
				$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <input type='hidden' name='urlPrintQR".$noU."' id='urlPrintQR".$noU."' value='".$secPrintQR."'>
							   <input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$secVoid."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
							   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printDocument(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								<a href='avascript:void(null);' class='btn btn-warning btn-xs' title='Show QRC' onClick='printQR(".$noU.")' style='display:none'>
									<i class='glyphicon glyphicon-qrcode'></i>
								</a>
								</label>";								
				
				$output['data'][] = array("$noU.",
										  $dataI['IR_CODE'],
										  $IR_DATEV,
										  $SPLDESC,
										  $dataI['IR_NOTE'],
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  "<span class='label label-".$STATCOLV."' style='font-size:12px'>".$INVSTATDes."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function up180c15dtinb() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN068';
			$data["MenuApp"] 	= 'MN068';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$IR_NUM		= $EXTRACTCOL[1];
		
		if ($this->session->userdata('login') == TRUE)
		{					
			$getrr 					= $this->m_itemreceipt->get_IR_by_number($IR_NUM)->row();			
			$PRJCODE				= $getrr->PRJCODE;
			$data['countSUPL']		= $this->m_itemreceipt->count_all_num_rowsVend();
			$data['vwSUPL'] 		= $this->m_itemreceipt->viewvendor()->result();
			$data['countCUST']		= $this->m_itemreceipt->count_all_CustG();
			$data['vwCUST'] 		= $this->m_itemreceipt->viewcustomerG()->result();
			$data["MenuCode"] 		= 'MN068';
			$data["PRJCODE"] 		= $PRJCODE;
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Approve IR';
			$data['h3_title']		= 'inventory';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Penerimaan Material";
				$data["h3_title"] 	= "persetujuan";
			}
			else
			{
				$data["h2_title"] 	= "Item Receipt";
				$data["h3_title"] 	= "approval";
			}
			
			$data['backURL'] 		= site_url('c_inventory/c_ir180c15/in180c15box/');
			
			$data['default']['IR_NUM'] 		= $getrr->IR_NUM;
			$data['default']['IR_CODE'] 	= $getrr->IR_CODE;
			$data['default']['IR_DATE'] 	= $getrr->IR_DATE;
			$IR_DATE 						= $getrr->IR_DATE;
			$data['default']['IR_DUEDATE'] 	= $getrr->IR_DUEDATE;
			$data['default']['IR_SOURCE'] 	= $getrr->IR_SOURCE;
			$IR_SOURCE						= $getrr->IR_SOURCE;
			$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
			$data['default']['DEPCODE'] 	= $getrr->DEPCODE;
			$data['PRJCODE_HO']				= $this->data['PRJCODE_HO'];
			$data["PRJCODE"] 				= $PRJCODE;
			$data['default']['SPLCODE'] 	= $getrr->SPLCODE;
			$SPLCODE						= $getrr->SPLCODE;
			$data['default']['PO_NUM'] 		= $getrr->PO_NUM;
			$data['default']['PO_NUMX'] 	= $getrr->PO_NUM;
			$data['default']['PO_CODE'] 	= $getrr->PO_CODE;
			$data['default']['PR_NUM'] 		= $getrr->PR_NUM;
			$data['default']['PR_CODE'] 	= $getrr->PR_CODE;
			$data['default']['IR_REFER'] 	= $getrr->IR_REFER;
			$data['default']['IR_REFER2'] 	= $getrr->IR_REFER2;
			$data['default']['IR_AMOUNT'] 	= $getrr->IR_AMOUNT;
			$data['default']['TERM_PAY'] 	= $getrr->TERM_PAY;
			$data['default']['TRXUSER'] 	= $getrr->TRXUSER;
			$data['default']['APPROVE'] 	= $getrr->APPROVE;
			$data['default']['IR_STAT'] 	= $getrr->IR_STAT;
			$data['default']['INVSTAT'] 	= $getrr->INVSTAT;
			$data['default']['IR_NOTE'] 	= $getrr->IR_NOTE;
			$data['default']['IR_NOTE2'] 	= $getrr->IR_NOTE2;
			$data['default']['IR_LOC'] 		= $getrr->IR_LOC;
			$data['default']['REVMEMO']		= $getrr->REVMEMO;
			$data['default']['WH_CODE']		= $getrr->WH_CODE;
			$data['default']['PR_CREATE']	= $getrr->PR_CREATE;
			$data['default']['Patt_Year'] 	= $getrr->Patt_Year;
			$data['default']['Patt_Number'] = $getrr->Patt_Number;

			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $IR_DATE;
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

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getrr->IR_NUM;
				$MenuCode 		= 'MN068';
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
			
			if($IR_SOURCE == 1)
			{
				$data['form_action']	= site_url('c_inventory/c_ir180c15/update_inbox_process');
				$this->load->view('v_inventory/v_itemreceipt/inb_itemreceipt_form_dir', $data);
			}
			else
			{
				$data['form_action']	= site_url('c_inventory/c_ir180c15/update_inbox_process');
				if($IR_SOURCE == 4)
				{
					$this->load->view('v_inventory/v_itemreceipt/inb_itemreceipt_form_so', $data);
				}
				else
				{
					$this->load->view('v_inventory/v_itemreceipt/inb_itemreceipt_form', $data);
				}
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_inbox_process() // U
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		date_default_timezone_set("Asia/Jakarta");
		
		$APPDATE 				= date('Y-m-d H:i:s');
		$IR_APPROVED 			= date('Y-m-d H:i:s');
			
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
			
			$IR_NUM			= $this->input->post('IR_NUM');
			$IR_CODE		= $this->input->post('IR_CODE');
			$IR_DATE		= date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('IR_DATE'))));
			$IR_DUEDATE		= date('Y-m-d', strtotime($this->input->post('IR_DUEDATE')));
			$accYr			= date('Y', strtotime($IR_DATE));
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			$ISDIRECT		= $this->input->post('ISDIRECT');
			$IR_STAT		= $this->input->post('IR_STAT');
			$WH_CODE		= $this->input->post('WH_CODE');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
			$TERM_PAY		= $this->input->post('TERM_PAY');
			$IR_STAT		= $this->input->post('IR_STAT'); 	// 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			$INVSTAT		= 'NI';
			$PO_NUM			= $this->input->post('PO_NUM');
			$PO_CODE		= $this->input->post('PO_CODE');
			$PR_NUM			= $this->input->post('PR_NUM');
			$PR_CODE		= $this->input->post('PR_CODE');

			$PR_CREATE		= addslashes($this->input->post('PR_CREATE'));
			$IR_NOTE		= addslashes($this->input->post('IR_NOTE'));
			$IR_NOTE2		= addslashes($this->input->post('IR_NOTE2'));

			$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
			$IR_DISC		= $this->input->post('IR_DISC');
			$IR_PPN			= $this->input->post('IR_PPN');
			$IR_AMOUNT_NETT	= $this->input->post('IR_AMOUNT_NETT');
			$TAXCODE_PPN	= $this->input->post('TAXCODE_PPN');
			$TAXCODE_PPH	= $this->input->post('TAXCODE_PPH');
			$IR_APPROVER	= $DefEmp_ID;
			
			$SPLDESC		= '';
			$PageFrom		= $this->input->post('PageFrom');
			if($PageFrom == 'PO')
				$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			else
				$sqlSPL 		= "SELECT CUST_DESC AS SPLDESC FROM tbl_customer WHERE CUST_CODE = '$SPLCODE' LIMIT 1";
				
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= $rowSPL->SPLDESC;
			endforeach;
			
			// START : SPECIAL FOR SASMITO
				$TOTMAJOR	= $this->input->post('TOTMAJOR');
				// IF TOTMAJOR > 0, MAKA HARUS ADA STEP APPROVAL KHUSUS DARI MENU SETTING
				// MELALUI TABEL tbl_major_app
				$sqlMJREMP	= "SELECT * FROM tbl_major_app";
				$resMJREMP	= $this->db->query($sqlMJREMP)->result();
				foreach($resMJREMP as $rowMJR) :
					$Emp_ID1	= $rowMJR->Emp_ID1;
					$Emp_ID2	= $rowMJR->Emp_ID2;
				endforeach;
				$yesAPP		= 0;
				if($TOTMAJOR > 0)
				{
					if(($DefEmp_ID == $Emp_ID1) || ($DefEmp_ID == $Emp_ID2))
					{
						$yesAPP		= 1;
						$AH_ISLAST	= 1;
					}
					else
					{
						$yesAPP	= 0;
					}
				}
				else
				{
					$yesAPP		= 1;
				}
			// END : SPECIAL FOR SASMITO
			
			$this->load->model('m_updash/m_updash', '', TRUE);
			
			$AH_CODE		= $IR_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $APPDATE;
			$AH_NOTES		= addslashes($this->input->post('IR_NOTE2'));
			
			if($IR_SOURCE == 1)		// Direct
			{
				$DOCSource 		= "";
				$IR_REFER		= "DIRXXXXXXXX";
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$PR_NUM			= '';
				$PR_CODE		= '';
			}
			elseif($IR_SOURCE == 2)		// MR
			{
				$DOCSource		= "MRXXXXXXXX";
			}
			elseif($IR_SOURCE == 3)	// PO
			{
				$IR_REFER		= $this->input->post('IR_REFER');
				$Ref_Number 	= $this->input->post('PO_NUM');
				$PO_NUM			= $Ref_Number;
				$DOCSource		= $PO_NUM;
				$PR_NUM			= '';
				$PR_CODE		= '';
				$getRefMRfPO	= "SELECT PR_NUM, PR_CODE
									FROM tbl_po_header
									WHERE PO_NUM = '$Ref_Number'
									AND PRJCODE = '$PRJCODE'";
				$resgetMRfPO 	= $this->db->query($getRefMRfPO)->result();
				foreach($resgetMRfPO as $rowPO) :
					$PR_NUM 	= $rowPO->PR_NUM;
					$PR_CODE 	= $rowPO->PR_CODE;
				endforeach;
				$PR_NUM			= $PR_NUM;
				$PR_CODE		= $PR_CODE;
			}

			if($IR_STAT == 3)
			{
				// DEFAULT STATUS
					$updIR 	= array('IR_STAT'	=> 7,
									'IR_NOTE2'	=> $IR_NOTE2);										
					$this->m_itemreceipt->updateIR($IR_NUM, $updIR);
					
				// START : UPDATE DEFAULT STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
											'DOC_CODE' 		=> $IR_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_ir_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : SAVE APPROVE HISTORY
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

				if($AH_ISLAST == 1 && $yesAPP == 1)
				{
					// UPDATE IR HEADER
						$updIR = array('IR_STAT'		=> $IR_STAT,
										'APPROVE'		=> $IR_STAT,
										'IR_NOTE2'		=> $IR_NOTE2,
										'IR_APPROVED'	=> $IR_APPROVED,
										'IR_APPROVER'	=> $IR_APPROVER);
						$this->m_itemreceipt->updateIR($IR_NUM, $updIR);
						
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
												'DOC_CODE' 		=> $IR_NUM,
												'DOC_STAT' 		=> $IR_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_ir_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS

					$DOCSource	= '';
					if($IR_SOURCE == 2)		// MR
					{
						$DOCSource		= "MRXXXXXXXX";
					}
					elseif($IR_SOURCE == 3)	// PO
					{
						$Ref_Number 	= $this->input->post('PO_NUM');
						$PO_NUM			= $Ref_Number;
						$DOCSource		= $PO_NUM;
						$this->m_itemreceipt->updatePO($IR_NUM, $PRJCODE, $PO_NUM, $ISDIRECT); // UPDATE PO : PROCEDURE CLOSE PO
					}
					elseif($IR_SOURCE == 1)	// DIRECTS
					{
						$Ref_Number 	= '';
						$PO_NUM			= '';
						$DOCSource		= 'Direct';
						$this->m_itemreceipt->updateJOBDET($IR_NUM, $PRJCODE, $ISDIRECT); // UPDATE JOB DETAIL
					}

					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $IR_NUM;
						$JournalType	= 'IR';
						$JournalH_Date	= $IR_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $DOCSource;
						$LastUpdate		= $IR_APPROVED;
						$WH_CODE		= $WH_CODE;
						$Refer_Number	= $PR_NUM;
						$RefType		= 'WBSD';
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
											'PRJCODE'			=> $PRJCODE,
											'ManualNo'			=> $IR_CODE);
						$this->m_journal->createJournalH_NEW($JournalH_Code, $parameters);
					// END : JOURNAL HEADER

					if($PR_CREATE == 1)
					{
						// START : CREATE SPP PROCEDURE
							$paramSPP 	= array('IR_NUM' 		=> $IR_NUM,
												'PR_NUM' 		=> $PR_NUM,
												'PR_CODE' 		=> $PR_CODE,
												'PO_NUM' 		=> $PO_NUM,
												'PO_CODE' 		=> $PO_CODE,
												'PRJCODE' 		=> $PRJCODE);
							$this->m_itemreceipt->createSPP($paramSPP);
						// END : CREATE SPP PROCEDURE
					}
					
					// START : JOURNAL DETAIL
						$TOT_AMOUNT			= 0;
						$TOT_AMOUNT_PPN		= 0;
						$TOT_AMOUNT_PPh		= 0;
						$TOT_AMOUNT_POT		= 0;
						foreach($_POST['data'] as $d)
						{
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$IR_ID 			= $d['IR_ID'];
							$ITM_CODE 		= $d['ITM_CODE'];
							$ITM_NAME 		= $d['ITM_NAME'];
							$JOBCODEID 		= $d['JOBCODEID'];
							$ACC_ID 		= $d['ACC_ID'];
							$ITM_UNIT 		= $d['ITM_UNIT'];
							$ITM_GROUP 		= $d['ITM_GROUP'];
							$ITM_TYPE 		= $d['ITM_TYPE'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_PRICE 		= $d['ITM_PRICE'];
							$ITM_DISC 		= $d['ITM_DISC'];
							$ITM_DISCX 		= $d['ITM_DISC'];
							$TAXCODE1 		= $d['TAXCODE1'];
							$TAXPRICE1 		= $d['TAXPRICE1'];
							$PO_NUM 		= $d['PO_NUM'];	
							$POD_ID 		= $d['POD_ID'];
							$ITM_NOTES 		= $d['NOTES'];
							$ISITMCOST 		= $d['ISCOST'];
							$JournalH_Code	= $IR_NUM;
							$JournalType	= 'IR';
							$JournalH_Date	= $IR_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$LastUpdate		= $IR_APPROVED;
							$WH_CODE		= $WH_CODE;
							$Refer_Number	= $PR_NUM;
							$RefType		= 'IR';
							$JSource		= 'IR';
							$PRJCODE		= $PRJCODE;
							$TOT_PRICE		= $ITM_QTY * $ITM_PRICE;
							
							if($ISITMCOST == 0)
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
													'Reference_Number'	=> $Refer_Number,
													'RefType'			=> $RefType,
													'PRJCODE'			=> $PRJCODE,
													'JSource'			=> $JSource,
													'TRANS_CATEG' 		=> 'IR',			// REC = RECEIPT
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
													'JOBCODEID'			=> $JOBCODEID,
													'ITM_NAME'			=> $ITM_NAME,
													'ITM_NOTES'			=> $ITM_NOTES,
													'IR_NOTE'			=> $IR_NOTE,
													'IR_CODE'			=> $IR_CODE);
								$this->m_journal->createJournalD($JournalH_Code, $parameters);

								// START : UPDATE PROFIT AND LOSS
									$this->load->model('m_updash/m_updash', '', TRUE);

									$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
									$PERIODED	= $JournalH_Date;
									$FIELDNME	= "";
									$FIELDVOL	= $ITM_QTY;
									$FIELDPRC	= $ITM_PRICE;
									$ADDTYPE	= "PLUS";		// PENGURANGAN KARENA SEBAGAI BAHAN MASUKAN

									$parameters1 = array('PERIODED' 	=> $PERIODED,
														'FIELDNME'		=> $FIELDNME,
														'FIELDVOL' 		=> $FIELDVOL,
														'FIELDPRC' 		=> $FIELDPRC,
														'ADDTYPE' 		=> $ADDTYPE,
														'ITM_CODE'		=> $ITM_CODE,
														'ITM_TYPE'		=> $ITM_TYPE);
									$this->m_updash->updateLR_NForm($PRJCODE, $parameters1); 
								// END : UPDATE PROFIT AND LOSS
							
								// UPDATE AMOUNT JOURNAL HEADER
									$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = Journal_Amount+$TOT_PRICE
													WHERE JournalH_Code = '$JournalH_Code'";
									$this->db->query($sqlUpdJH);
									
								// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
									// DI HOLDED KARENA AKAN DILAKUKAN DISAAT TTK
									$this->load->model('m_updash/m_updash', '', TRUE);
									$paramFT = array('DOC_NUM' 		=> $IR_NUM,
													'DOC_DATE' 		=> $IR_DATE,
													'DOC_EDATE' 	=> $IR_DUEDATE,
													'PRJCODE' 		=> $PRJCODE,
													'FIELD_NAME1' 	=> 'FT_COP',
													'FIELD_NAME2' 	=> 'FM_COP',
													'TOT_AMOUNT'	=> $TOT_PRICE);
									//$this->m_updash->finTrack($paramFT); HOLDED ON 11 JAN 19
								// END : TRACK FINANCIAL TRACK	
								
								// START : UPDATE STOCK DAN JOBLIST
									$parameters1 = array('PRJCODE' 	=> $PRJCODE,
														'WH_CODE'	=> $WH_CODE,
														'JOBCODEID'	=> $JOBCODEID,
														'IR_NUM' 	=> $IR_NUM,
														'IR_CODE' 	=> $IR_CODE,
														'ITM_CODE' 	=> $ITM_CODE,
														'ITM_NAME'	=> $ITM_NAME,
														'ITM_UNIT'	=> $ITM_UNIT,
														'ITM_GROUP'	=> $ITM_GROUP,
														'ITM_QTY' 	=> $ITM_QTY,
														'ITM_PRICE' => $ITM_PRICE);
									//$this->m_itemreceipt->updateITM($parameters1);
									$this->m_itemreceipt->updateITM_NEW($parameters1);
								// START : UPDATE STOCK
								
								// START : JOURNAL DETAIL DEBIT - KHUSUS PPn Masukan
								// 30 Nop 2021 : PPn tidak dilibatkan dalam transaksi
									$this->load->model('m_journal/m_journal', '', TRUE);
									//GET SUPPLIER CATEG
										$SPLCAT		= '';
										$sqlSPLC	= "SELECT SPLCAT FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
										$resSPLC	= $this->db->query($sqlSPLC)->result();
										foreach($resSPLC as $rowSPLC) :
											$SPLCAT = $rowSPLC->SPLCAT;
										endforeach;
									
									$isTax 		= 0;
									$sTaxPPn 	= "tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";	// Tax PPn
									$rTaxPPn	= $this->db->count_all($sTaxPPn);
									if($rTaxPPn > 0)
										$isTax 	= 1;

									$sTaxPPh 	= "tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE1'";	// Tax PPh
									$rTaxPPh	= $this->db->count_all($sTaxPPh);
									if($rTaxPPh > 0)
										$isTax 	= 1;

									/*if($isTax == 1)
									{
										$JournalH_Code	= $IR_NUM;
										$JournalType	= 'IR';
										$JournalH_Date	= $IR_DATE;
										$Company_ID		= $comp_init;
										$Currency_ID	= 'IDR';
										$DOCSource		= $IR_NUM;
										$LastUpdate		= date('Y-m-d H:i:s');
										$WH_CODE		= $WH_CODE;
										$Refer_Number	= $Ref_Number;
										$RefType		= 'IR';
										$JSource		= 'IR';
										$PRJCODE		= $PRJCODE;
										$Notes			= "PPn Masukan $IR_CODE";
										
										$ITM_CODE 		= $ITM_CODE;
										$ACC_ID 		= '';
										$ITM_UNIT 		= '';
										$ITM_QTY 		= 1;
										$ITM_PRICE 		= $TAXPRICE1;
										$ITM_DISC 		= 0;
										//$TAXCODE1 	= "";
										//$TAXPRICE1	= 0;
										//echo "ITM_PRICE = $ITM_PRICE ==> ";
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
															'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV2 = PURCHASE INVOICE
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
										
										// UPDATE AMOUNT JOURNAL HEADER
											$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = Journal_Amount+$ITM_PRICE
															WHERE JournalH_Code = '$JournalH_Code'";
											$this->db->query($sqlUpdJH);
									}*/
								// START : JOURNAL DETAIL DEBIT - KHUSUS PPn Masukan
								
								/*	31 Maret 2021
									BERDASARKAN https://www.jurnal.id/id/blog/perhitungan-terbaru-diskon-pembelian-di-jurnal-efektif-per-tanggal-1-januari-2017/
								 	PERHITUNGAN LAMA
									STOCK 					1000
									DISKON 							100
									HUTANG 							900

									PERHITUNGAN ABRU
									STOCK 					900
									HUTANG 							900

									KESIMPULAN
									Pada saat penerimaan, tidak terbentuk akun potongan pembelian. Karena biasanya ada potongan yang bersyarat
									seperti 2/10, n/30 (ketika pelunasan dilakukan dalam kurun waktu sepuluh hari, maka pembeli akan mendapatkan diskon 2%. Jika pelunasan dalam kurun waktu tiga puluh hari, maka pembeli tidak mendapatkan diskon). Berikut jurnalnya s.d. pembayaran

									IR 	Persediaan 			1000
									IR 	Hut. Blm Difaktur			1000

									INV Hut. Blm Difaktur	1000
									INV Hutang Usaha 				900
									INV Pot. Pembelian 				100

									BP 	Hutang Usaha 		900
									BP 	Kas 						900

									SEHINGGA UNTUK SAAT INI JURNAL POTONGAN AKAN DI HIDDEN
								*/

								// START : JOURNAL DETAIL KREDIT - JIKA ADA DISC DI PO
								// TIDAK DIBUATKAN JURNAL POTONGAN KARENA POTONGAN TERSEBUT LANGSUNG MENGURANGI NILAI INVETORI
									/*if($ITM_DISCX > 0)
									{
										$JournalH_Code	= $IR_NUM;
										$JournalType	= 'IR';
										$JournalH_Date	= $IR_DATE;
										$Company_ID		= $comp_init;
										$Currency_ID	= 'IDR';
										$DOCSource		= $IR_NUM;
										$LastUpdate		= date('Y-m-d H:i:s');
										$WH_CODE		= $WH_CODE;
										$Refer_Number	= $Ref_Number;
										$RefType		= 'IR';
										$JSource		= 'IR';
										$PRJCODE		= $PRJCODE;
										$Notes			= "Potongan Pembelian $IR_CODE";
										
										$ITM_CODE 		= $ITM_CODE;
										$ACC_ID 		= '';
										$ITM_UNIT 		= '';
										$ITM_QTY 		= 1;
										$ITM_PRICE 		= $ITM_DISCX;
										$ITM_DISC 		= 0;
										//$TAXCODE1 	= "";
										//$TAXPRICE1	= 0;
										//echo "ITM_PRICE = $ITM_PRICE ==> ";
										$TRANS_CATEG 	= "IR-DISC~$SPLCAT";									// PERHATIKAN DI SINI. PENTING
										
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
															'TRANS_CATEG' 		=> $TRANS_CATEG,			// PINV2 = PURCHASE INVOICE
															'ITM_GROUP' 		=> $ITM_GROUP,
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
										
										// UPDATE AMOUNT JOURNAL HEADER
											$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = Journal_Amount-$ITM_PRICE
															WHERE JournalH_Code = '$JournalH_Code'";
											$this->db->query($sqlUpdJH);
									}*/
								// START : JOURNAL DETAIL DEBIT - KHUSUS PPn Masukan

								// START : UPDATE PR
									// SUDAH DI updatePO()
									//$this->m_itemreceipt->updatePR($IR_NUM, $IR_ID, $PO_NUM, $POD_ID, $ITM_CODE, $PRJCODE, $ITM_QTY, $TOT_PRICE);
									$this->m_itemreceipt->updateIRDET($IR_NUM, $IR_ID, $PO_NUM, $POD_ID, $ITM_CODE, $PRJCODE, $ITM_QTY, $TOT_PRICE);
								// END : UPDATE PR
							}
						}
					// END : JOURNAL DETAIL

					// START : INSERT BONUS
						foreach($_POST['data'] as $d)
						{
							$IR_NUM 		= $IR_NUM;
							$ITM_CODE 		= $d['ITM_CODE'];
							$JOBCODEID 		= $d['JOBCODEID'];
							$ISITMCOST 		= $d['ISCOST'];
							$ITM_QTY_BONUS	= $d['ITM_QTY_BONUS'];
							if($ITM_QTY_BONUS == '')
								$ITM_QTY_BONUS = 0;
							
							if($ISITMCOST == 0)
							{
								if($ITM_QTY_BONUS > 0)
									$this->m_itemreceipt->updateIRD($IR_NUM, $PRJCODE, $ITM_CODE, $ITM_QTY_BONUS);

								// UNTUK HARGA CARI DARI RATA2 HARGA (TOTAL IN / VOL IN)
								$this->load->model('m_updash/m_updash', '', TRUE);
								$ITM_PRICE 		= $this->m_updash->get_itmAVG($PRJCODE, $ITM_CODE);

								// UPDATE ITM AND UM
									$sqlUPDITM	= "UPDATE tbl_item SET ITM_AVGP = $ITM_PRICE WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
									$this->db->query($sqlUPDITM);
							}
						}
					// END : INSERT BONUS

					// UPDATE STATUS IN DETAIL
						$this->load->model('m_journal/m_journal', '', TRUE);
						$GEJ_NUM	= $IR_NUM;
						$GEJ_STAT	= $IR_STAT;
						$updGEJ 	= array('GEJ_STAT' 		=> $GEJ_STAT);
						$this->m_journal->updateGEJ($GEJ_NUM, $updGEJ);
					
					// START : UPDATE TO TRANS-COUNT
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
						$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
												'PRJCODE' 		=> $PRJCODE,		// PROJECT
												'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
												'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
												'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
												'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
												'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
												'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
												'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
												'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
												'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
												'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
												'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
												'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
												'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
						$this->m_updash->updateDashData($parameters);
					// END : UPDATE TO TRANS-COUNT
				
					// START : UPDATE TO DOC. COUNT
						$parameters 	= array('DOC_CODE' 		=> $IR_NUM,
												'PRJCODE' 		=> $PRJCODE,
												'DOC_TYPE'		=> "IR",
												'DOC_QTY' 		=> "DOC_IRQ",
												'DOC_VAL' 		=> "DOC_IRV",
												'DOC_STAT' 		=> 'ADD');
						$this->m_updash->updateDocC($parameters);
					// END : UPDATE TO DOC. COUNT
				}
			}
			elseif($IR_STAT == 4)
			{
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($IR_NUM);
				// END : DELETE HISTORY
				
				$updIR = array('IR_STAT'		=> $IR_STAT,
								'APPROVE'		=> $IR_STAT,
								'IR_NOTE2'		=> $AH_NOTES,
								'IR_APPROVED'	=> $IR_APPROVED,
								'IR_APPROVER'	=> $IR_APPROVER);
				$this->m_itemreceipt->updateIR($IR_NUM, $updIR);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
											'DOC_CODE' 		=> $IR_NUM,
											'DOC_STAT' 		=> $IR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_ir_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($IR_STAT == 5)
			{
				$updIR = array('IR_STAT'		=> $IR_STAT,
								'APPROVE'		=> $IR_STAT,
								'IR_NOTE2'		=> $AH_NOTES,
								'IR_APPROVED'	=> $IR_APPROVED,
								'IR_APPROVER'	=> $IR_APPROVER);
				$this->m_itemreceipt->updateIR($IR_NUM, $updIR);					// Update Status
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
											'DOC_CODE' 		=> $IR_NUM,
											'DOC_STAT' 		=> $IR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_ir_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
					
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $IR_NUM;
				$MenuCode 		= 'MN068';
				$TTR_CATEG		= 'APP-UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC,
										'TTR_NOTES'		=> "$IR_NOTE2 (Stat $GEJ_STAT)");
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $IR_NUM,
									'PRJCODE' 		=> $PRJCODE,
									'PRJCODE_HO'	=> $this->data['PRJCODE_HO'],
									'APPROVED'		=> $IR_APPROVED);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "IR",
										'TR_DATE' 		=> $IR_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
					
			// START : UPDATE TO TRANS-COUNT
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
				$parameters 	= array('PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
										'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashStatDoc($parameters);
				
				$parameters 	= array('PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashStatDoc($parameters);
			// END : UPDATE ACUM. TO DASHBOARD
				
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_ir180c15/in180c15box/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_inbox_process_SO() // U
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
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
			
			$IR_NUM			= $this->input->post('IR_NUM');
			$IR_CODE		= $this->input->post('IR_CODE');
			$IR_DATE		= date('Y-m-d', strtotime($this->input->post('IR_DATE')));
			$IR_DUEDATE		= date('Y-m-d', strtotime($this->input->post('IR_DUEDATE')));
			$accYr			= date('Y', strtotime($IR_DATE));
			$IR_SOURCE		= $this->input->post('IR_SOURCE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$SPLCODE		= $this->input->post('SPLCODE');
			$IR_REFER2		= $this->input->post('IR_REFER2');
			$PO_NUM			= $this->input->post('PO_NUM');
			$PO_CODE		= $this->input->post('PO_CODE');
			$PR_NUM			= $this->input->post('PR_NUM');
			$PR_CODE		= $this->input->post('PR_CODE');
			$ISDIRECT		= $this->input->post('ISDIRECT');
			$IR_STAT		= $this->input->post('IR_STAT');
			$WH_CODE		= $this->input->post('WH_CODE');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$SPLDESC		= '';
			$sqlCUST 		= "SELECT CUST_DESC FROM tbl_customer
								WHERE CUST_CODE = '$SPLCODE' LIMIT 1";
			$resCUST			= $this->db->query($sqlCUST)->result();
			foreach($resCUST as $rowCUST):
				$SPLDESC	= $rowCUST->CUST_DESC;
			endforeach;

			if(!is_dir('qrcodelist/'.$PRJCODE.'/'))
				echo mkdir('qrcodelist/'.$PRJCODE.'/', 0777, TRUE);

			include APPPATH.'views/phpqrcode/qrlib.php';
    		$qrcDir   = 'qrcodelist/'.$PRJCODE.'/';
			
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $IR_NUM;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= $APPDATE;
				$AH_NOTES		= addslashes($this->input->post('IR_NOTE2'));
				
				if($IR_STAT == 3)
				{
					// START : SAVE APPROVE HISTORY
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
				
				$updIR 	= array('IR_STAT'	=> 7);										
				$this->m_itemreceipt->updateIR($IR_NUM, $updIR);					// Update Status
			// END : SAVE APPROVE HISTORY
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
										'DOC_CODE' 		=> $IR_NUM,
										'DOC_STAT' 		=> 7,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_ir_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			if($AH_ISLAST == 1)
			{
				$IR_AMOUNT		= $this->input->post('IR_AMOUNT');
				$TERM_PAY		= $this->input->post('TERM_PAY');
				$IR_STAT		= $this->input->post('IR_STAT'); 	// 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
				$INVSTAT		= 'NI';
				$IR_NOTE		= addslashes($this->input->post('IR_NOTE'));
				$IR_NOTE2		= addslashes($this->input->post('IR_NOTE2'));
				$WH_CODE		= $this->input->post('WH_CODE');
				$IR_APPROVED	= date('Y-m-d H:i:s');
				$IR_APPROVER	= $DefEmp_ID;
					
				$updIR 	= array('IR_STAT'		=> $IR_STAT,
								'APPROVE'		=> $IR_STAT,
								'IR_NOTE2'		=> $IR_NOTE2,
								'IR_APPROVED'	=> $IR_APPROVED,
								'IR_APPROVER'	=> $IR_APPROVER);
				$this->m_itemreceipt->updateIR($IR_NUM, $updIR);					// Update Status
				
				$DOCSource	= '';				
				if($IR_STAT == 3)
				{
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $IR_NUM;
						$JournalType	= 'IR-SO';
						$JournalH_Date	= $IR_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $DOCSource;
						$LastUpdate		= $IR_APPROVED;
						$WH_CODE		= $WH_CODE;
						$Refer_Number	= $PO_NUM;
						$RefType		= 'WBSD';
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
						$TOT_AMOUNT			= 0;
						$TOT_AMOUNT_PPN		= 0;
						$TOT_AMOUNT_PPh		= 0;
						$RNI				= 0;
						foreach($_POST['data'] as $d)
						{
							$RNI 		= $RNI+1;
							$lenI 		= strlen($RNI);
							if($lenI==1) $nolI="0";
							else if($lenI==2) $nolI="";
							
							$RNI 		= $nolI.$RNI;

							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$IR_ID 			= $d['IR_ID'];
							$ITM_CODE 		= $d['ITM_CODE'];
							$ITM_NAME 		= $d['ITM_NAME'];
							$JOBCODEID 		= $d['JOBCODEID'];
							$ACC_ID 		= $d['ACC_ID'];
							$ITM_UNIT 		= $d['ITM_UNIT'];
							$ITM_GROUP 		= $d['ITM_GROUP'];
							$ITM_TYPE 		= $d['ITM_TYPE'];
							$ITM_QTY 		= $d['ITM_QTY'];
							$ITM_QTY2 		= $d['ITM_QTY2'];
							$ITM_PRICE 		= $d['ITM_PRICE'];
							$ITM_DISC 		= $d['ITM_DISC'];
							$TAXCODE1 		= $d['TAXCODE1'];
							$TAXPRICE1 		= $d['TAXPRICE1'];
							$NOTES 			= $d['NOTES'];					
							$JournalH_Code	= $IR_NUM;
							$JournalType	= 'IR-SO';
							$JournalH_Date	= $IR_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$LastUpdate		= $IR_APPROVED;
							$WH_CODE		= $WH_CODE;
							$Refer_Number	= $PO_NUM;
							$RefType		= 'IR-SO';
							$JSource		= 'IR-SO';
							$PRJCODE		= $PRJCODE;
							$TOT_PRICE		= $ITM_QTY * $ITM_PRICE;
							
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
												'TRANS_CATEG' 		=> 'IR-SO',			// REC = RECEIPT
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
												'ITM_NAME'			=> $ITM_NAME,
												'ITM_NOTES'			=> $NOTES);
							$this->m_journal->createJournalD($JournalH_Code, $parameters);

							// START : UPDATE PROFIT AND LOSS
								$this->load->model('m_updash/m_updash', '', TRUE);

								$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
								$PERIODED	= $JournalH_Date;
								$FIELDNME	= "";
								$FIELDVOL	= $ITM_QTY;
								$FIELDPRC	= $ITM_PRICE;
								$ADDTYPE	= "PLUS";		// PENGURANGAN KARENA SEBAGAI BAHAN MASUKAN

								$parameters1 = array('PERIODED' 	=> $PERIODED,
													'FIELDNME'		=> $FIELDNME,
													'FIELDVOL' 		=> $FIELDVOL,
													'FIELDPRC' 		=> $FIELDPRC,
													'ADDTYPE' 		=> $ADDTYPE,
													'ITM_CODE'		=> $ITM_CODE,
													'ITM_TYPE'		=> $ITM_TYPE);
								$this->m_updash->updateLR_NForm($PRJCODE, $parameters1);
							// END : UPDATE PROFIT AND LOSS
						
							// UPDATE AMOUNT JOURNAL HEADER
								$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = Journal_Amount+$TOT_PRICE
												WHERE JournalH_Code = '$JournalH_Code'";
								$this->db->query($sqlUpdJH);	
							
							// START : UPDATE STOCK DAN JOBLIST DAN WHQTY
								$parameters1 = array('PRJCODE' 	=> $PRJCODE,
													'WH_CODE'	=> $WH_CODE,
													'JOBCODEID'	=> $JOBCODEID,
													'IR_NUM' 	=> $IR_NUM,
													'IR_CODE' 	=> $IR_CODE,
													'ITM_CODE' 	=> $ITM_CODE,
													'ITM_NAME'	=> $ITM_NAME,
													'ITM_UNIT'	=> $ITM_UNIT,
													'ITM_GROUP'	=> $ITM_GROUP,
													'ITM_QTY' 	=> $ITM_QTY,
													'ITM_PRICE' => $ITM_PRICE);
								$this->m_itemreceipt->updateITMSO($parameters1);
							// START : UPDATE STOCK
							
							// START : MEMBUAT SERIAL KHUSUS - Untuk penerimaan barang Greige PT Frans
								$sqlITMUPD		= "UPDATE tbl_item SET NEEDQRC = 1 WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
								$this->db->query($sqlITMUPD);
								
								$CREATEQRC		= $d['CREATEQRC'];

								if($CREATEQRC > 0)
								{
									// create prosedure input serial code per IR Per Qty Item
									$RN = 0;
									
									// RATA-RATA PER ROLL
									$RATA2	= $ITM_QTY / $ITM_QTY2;

									for($i=0; $i<$ITM_QTY2; $i++)
									{
										$RN 		= $RN+1;
										$len 		= strlen($RN);
										if($len==1) $nol="00";
										else if($len==2) $nol="0";
										else $nol="";
										
										$ITMQTY1 	= number_format($ITM_QTY,2);
										$ITMQTY		= str_replace('.', '', $ITMQTY1);

										$RN1 		= $nol.$RN;
										//$SN_CODE	= $IR_NUM.$RNI.$RN1;
										$IR_NUM2	= substr($IR_NUM,-12);
										$SN_CODE	= $comp_init.$IR_NUM2.$RNI.$RN1;
										$qrc_fill   = $SN_CODE;
					                  	$qrc_name   = "$SN_CODE.png";
										$SNCODE1	= $this->data['PRJCODE_HO'];
										$SNCODE2	= substr($IR_NUM,-6);
										//$QRC_CODEV	= "$SNCODE1 $SNCODE2 $RNI $RN1";
										$QRC_CODEV	= "$NOTES $ITMQTY $RNI $RN1";

					                  	$qrc_qlty   = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
					                  	$qrc_size   = 4; //batasan 1 paling kecil, 10 paling besar
					                  	$qrc_padd   = 0;
					                  	QRCode::png($qrc_fill, $qrcDir.'/'.$qrc_name, $qrc_qlty, $qrc_size, $qrc_padd);

										$insSN 		= array('PRJCODE' 		=> $PRJCODE,
															'IR_ID'			=> $IR_ID,
															'QRC_NUM' 		=> $SN_CODE,
															'QRC_CODEV'		=> $QRC_CODEV,
															'QRC_DATE'		=> $IR_DATE,
															'REC_FROM'		=> $SPLCODE,
															'REC_DESC'		=> $SPLDESC,
															'IR_NUM'		=> $IR_NUM,
															'IR_CODE'		=> $IR_CODE,
															'IR_CODE_REF'	=> $IR_REFER2,
															'ITM_CODE'		=> $ITM_CODE,
															'ITM_NAME'		=> $ITM_NAME,
															'ITM_UNIT'		=> $ITM_UNIT,
															'ITM_QTY'		=> $RATA2,
															'ITM_QTY2'		=> 1,
															'QRC_PATT'		=> $RN1);
										$this->m_itemreceipt->addSN($insSN);
									}
								}
							// END : MEMBUAT SERIAL KHUSUS
						}
					// END : JOURNAL DETAIL

					// INSERT BONUS
					foreach($_POST['data'] as $d)
					{
						$IR_NUM 		= $IR_NUM;
						$ITM_CODE 		= $d['ITM_CODE'];
						$JOBCODEID 		= $d['JOBCODEID'];
						$ITM_QTY_BONUS	= $d['ITM_QTY_BONUS'];
						if($ITM_QTY_BONUS == '')
							$ITM_QTY_BONUS = 0;
						
						if($ITM_QTY_BONUS > 0)
							$this->m_itemreceipt->updateIRD($IR_NUM, $PRJCODE, $ITM_CODE, $ITM_QTY_BONUS);
					}
				}

				// UPDATE STATUS IN DETAIL
					$this->load->model('m_journal/m_journal', '', TRUE);
					$GEJ_NUM	= $IR_NUM;
					$GEJ_STAT	= $IR_STAT;
					$updGEJ 	= array('GEJ_STAT' 		=> $GEJ_STAT);
					$this->m_journal->updateGEJ($GEJ_NUM, $updGEJ);
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
					$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
											'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
											'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
											'DOC_CODE' 		=> $IR_NUM,
											'DOC_STAT' 		=> $IR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_ir_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $IR_NUM;
					$MenuCode 		= 'MN068';
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
			
			if($IR_STAT == 5)
			{
				$updIR = array('IR_STAT'		=> $IR_STAT,
								'APPROVE'		=> $IR_STAT,
								'IR_NOTE2'		=> $AH_NOTES,
								'IR_APPROVED'	=> $IR_APPROVED,
								'IR_APPROVER'	=> $IR_APPROVER);
				$this->m_itemreceipt->updateIR($IR_NUM, $updIR);					// Update Status
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "IR_NUM",
											'DOC_CODE' 		=> $IR_NUM,
											'DOC_STAT' 		=> $IR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_ir_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "IR",
										'TR_DATE' 		=> $IR_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
				
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_ir180c15/in180c15box/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getProjName($myLove_the_an) // U
	{
		// check exixtensi projcewt code
		$getProj_Name 	= $this->M_itemreceipt_sd->getProjName($myLove_the_an);
		echo $getProj_Name;
	}
	
	function printdocument()
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$IR_NUM		= $_GET['id'];
		$IR_NUM		= $this->url_encryption_helper->decode_url($IR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getIR 			= $this->m_itemreceipt->get_IR_by_number($IR_NUM)->row();
			
			$data['default']['IR_NUM'] 		= $getIR->IR_NUM;
			$data['default']['IR_CODE'] 	= $getIR->IR_CODE;
			$data['default']['IR_DATE'] 	= $getIR->IR_DATE;
			$data['default']['IR_DUEDATE'] 	= $getIR->IR_DUEDATE;
			$data['default']['IR_SOURCE'] 	= $getIR->IR_SOURCE;
			$IR_SOURCE						= $getIR->IR_SOURCE;
			$data['default']['PRJCODE'] 	= $getIR->PRJCODE;
			$data['default']['SPLCODE'] 	= $getIR->SPLCODE;
			$SPLCODE						= $getIR->SPLCODE;
			$data['default']['PO_NUM'] 		= $getIR->PO_NUM;
			$data['default']['PO_NUMX'] 	= $getIR->PO_NUM;
			$data['default']['PR_NUM'] 		= $getIR->PR_NUM;
			$data['default']['IR_REFER'] 	= $getIR->IR_REFER;
			$data['default']['IR_AMOUNT'] 	= $getIR->IR_AMOUNT;
			$data['default']['TERM_PAY'] 	= $getIR->TERM_PAY;
			$data['default']['TRXUSER'] 	= $getIR->TRXUSER;
			$data['default']['APPROVE'] 	= $getIR->APPROVE;
			$data['default']['IR_STAT'] 	= $getIR->IR_STAT;
			$data['default']['INVSTAT'] 	= $getIR->INVSTAT;
			$data['default']['IR_NOTE'] 	= $getIR->IR_NOTE;
			$data['default']['IR_NOTE2'] 	= $getIR->IR_NOTE2;
			$data['default']['REVMEMO']		= $getIR->REVMEMO;
			$data['default']['WH_CODE']		= $getIR->WH_CODE;
			$data['default']['IR_LOC'] 		= $getIR->IR_LOC;
			
			$this->load->view('v_inventory/v_itemreceipt/print_ir', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printQR()
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$IR_NUM		= $_GET['id'];
		$IR_NUM		= $this->url_encryption_helper->decode_url($IR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getIR 			= $this->m_itemreceipt->get_IR_by_number($IR_NUM)->row();
			
			$data['default']['IR_NUM'] 		= $getIR->IR_NUM;
			$data['default']['IR_CODE'] 	= $getIR->IR_CODE;
			$data['default']['IR_DATE'] 	= $getIR->IR_DATE;
			$data['default']['IR_DUEDATE'] 	= $getIR->IR_DUEDATE;
			$data['default']['IR_SOURCE'] 	= $getIR->IR_SOURCE;
			$IR_SOURCE						= $getIR->IR_SOURCE;
			$data['default']['PRJCODE'] 	= $getIR->PRJCODE;
			$data['default']['SPLCODE'] 	= $getIR->SPLCODE;
			$SPLCODE						= $getIR->SPLCODE;
			$data['default']['PO_NUM'] 		= $getIR->PO_NUM;
			$data['default']['PO_NUMX'] 	= $getIR->PO_NUM;
			$data['default']['PO_CODE'] 	= $getIR->PO_CODE;
			$data['default']['PR_NUM'] 		= $getIR->PR_NUM;
			$data['default']['PR_CODE'] 	= $getIR->PR_CODE;
			$data['default']['IR_REFER'] 	= $getIR->IR_REFER;
			$data['default']['IR_AMOUNT'] 	= $getIR->IR_AMOUNT;
			$data['default']['TERM_PAY'] 	= $getIR->TERM_PAY;
			$data['default']['TRXUSER'] 	= $getIR->TRXUSER;
			$data['default']['APPROVE'] 	= $getIR->APPROVE;
			$data['default']['IR_STAT'] 	= $getIR->IR_STAT;
			$data['default']['INVSTAT'] 	= $getIR->INVSTAT;
			$data['default']['IR_NOTE'] 	= $getIR->IR_NOTE;
			$data['default']['IR_NOTE2'] 	= $getIR->IR_NOTE2;
			$data['default']['IR_LOC'] 		= $getIR->IR_LOC;
			$data['default']['REVMEMO']		= $getIR->REVMEMO;
			$data['default']['WH_CODE']		= $getIR->WH_CODE;

							
			$this->load->view('v_inventory/v_itemreceipt/print_qr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printQRDET()
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$collDET	= $_GET['id'];
		$collDET	= $this->url_encryption_helper->decode_url($collDET);
		$splitDATA 	= explode('~', $collDET);
		$IR_NUM 	= $splitDATA[0];
		$ITM_CODE 	= $splitDATA[1];
		$IR_ID 		= $splitDATA[2];

		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getIR 			= $this->m_itemreceipt->get_IR_by_number($IR_NUM)->row();
			
			$data['default']['IR_NUM'] 		= $getIR->IR_NUM;
			$data['default']['IR_NUM'] 		= $getIR->IR_NUM;
			$data['default']['IR_CODE'] 	= $getIR->IR_CODE;
			$data['default']['IR_DATE'] 	= $getIR->IR_DATE;
			$data['default']['IR_DUEDATE'] 	= $getIR->IR_DUEDATE;
			$data['default']['IR_SOURCE'] 	= $getIR->IR_SOURCE;
			$IR_SOURCE						= $getIR->IR_SOURCE;
			$data['default']['PRJCODE'] 	= $getIR->PRJCODE;
			$PRJCODE						= $getIR->PRJCODE;
			$data['default']['SPLCODE'] 	= $getIR->SPLCODE;
			$SPLCODE						= $getIR->SPLCODE;
			$data['default']['PO_NUM'] 		= $getIR->PO_NUM;
			$data['default']['PO_NUMX'] 	= $getIR->PO_NUM;
			$data['default']['PO_CODE'] 	= $getIR->PO_CODE;
			$data['default']['PR_NUM'] 		= $getIR->PR_NUM;
			$data['default']['PR_CODE'] 	= $getIR->PR_CODE;
			$data['default']['IR_REFER'] 	= $getIR->IR_REFER;
			$data['default']['IR_AMOUNT'] 	= $getIR->IR_AMOUNT;
			$data['default']['TERM_PAY'] 	= $getIR->TERM_PAY;
			$data['default']['TRXUSER'] 	= $getIR->TRXUSER;
			$data['default']['APPROVE'] 	= $getIR->APPROVE;
			$data['default']['IR_STAT'] 	= $getIR->IR_STAT;
			$data['default']['INVSTAT'] 	= $getIR->INVSTAT;
			$data['default']['IR_NOTE'] 	= $getIR->IR_NOTE;
			$data['default']['IR_NOTE2'] 	= $getIR->IR_NOTE2;
			$data['default']['REVMEMO']		= $getIR->REVMEMO;
			$data['default']['WH_CODE']		= $getIR->WH_CODE;
			$data['default']['IR_LOC'] 		= $getIR->IR_LOC;
			$data['default']['ITM_CODE']	= $ITM_CODE;
			$data['default']['IR_ID']		= $IR_ID;

			//if(!is_dir('qrcodelist/'.$PRJCODE.'/'))
				//echo mkdir('qrcodelist/'.$PRJCODE.'/', 0777, TRUE);

			$this->load->view('v_inventory/v_itemreceipt/print_qrdet', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function voiddocument()
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$IR_NUM		= $_GET['id'];
		$IR_NUM		= $this->url_encryption_helper->decode_url($IR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 		= $appName;
			$data['countSUPL']	= $this->m_itemreceipt->count_all_num_rowsVend();
			$data['vwSUPL'] 	= $this->m_itemreceipt->viewvendor()->result();
			$data['form_action']= site_url('c_inventory/c_ir180c15/void_process');
			
			$getIR 				= $this->m_itemreceipt->get_IR_by_number($IR_NUM)->row();
			
			$data['IR_NUM'] 	= $getIR->IR_NUM;
			$data['IR_CODE'] 	= $getIR->IR_CODE;
			$data['IR_DATE'] 	= $getIR->IR_DATE;
			$data['IR_DUEDATE'] = $getIR->IR_DUEDATE;
			$data['IR_SOURCE'] 	= $getIR->IR_SOURCE;
			$IR_SOURCE			= $getIR->IR_SOURCE;
			$data['PRJCODE'] 	= $getIR->PRJCODE;
			$data['SPLCODE'] 	= $getIR->SPLCODE;
			$data['IR_STAT'] 	= $getIR->IR_STAT;
			$data['IR_NOTE'] 	= $getIR->IR_NOTE;
			$data['IR_NOTE2'] 	= $getIR->IR_NOTE2;
			$data['ISVOID']		= $getIR->ISVOID;
			$data['VOID_NOTE']	= $getIR->VOID_NOTE;
			
			$this->load->view('v_inventory/v_itemreceipt/itemreceipt_void', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function void_process() // U
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
			
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			$comp_init 	= $this->session->userdata('comp_init');
			
			$this->db->trans_begin();
			
			$IR_NUM			= $this->input->post('IR_NUM');
			$ISVOID			= 1;
			$VOID_DATE 		= date('Y-m-d H:i:s');
			$VOID_NOTE		= $this->input->post('VOID_NOTE');
			$accYr			= date('Y', strtotime($VOID_DATE));
			
			$getIR 			= $this->m_itemreceipt->get_IR_by_number($IR_NUM)->row();			
			$IR_NUM 		= $getIR->IR_NUM;
			$IR_CODE 		= $getIR->IR_CODE;
			$IR_DATE 		= $getIR->IR_DATE;
			$IR_DUEDATE 	= $getIR->IR_DUEDATE;
			$IR_SOURCE 		= $getIR->IR_SOURCE;
			$PRJCODE 		= $getIR->PRJCODE;
			$SPLCODE		= $getIR->SPLCODE;
			$PO_NUM 		= $getIR->PO_NUM;
			$PO_NUMX 		= $getIR->PO_NUM;
			$PR_NUM 		= $getIR->PR_NUM;
			$IR_REFER 		= $getIR->IR_REFER;
			$IR_AMOUNT 		= $getIR->IR_AMOUNT;
			$TERM_PAY 		= $getIR->TERM_PAY;
			$TRXUSER 		= $getIR->TRXUSER;
			$APPROVE 		= $getIR->APPROVE;
			$IR_STAT		= $getIR->IR_STAT;
			$INVSTAT 		= $getIR->INVSTAT;
			$IR_NOTE 		= $getIR->IR_NOTE;
			$IR_NOTE2 		= $getIR->IR_NOTE2;
			$REVMEMO		= $getIR->REVMEMO;
			$WH_CODE		= $getIR->WH_CODE;
			
			$SPLDESC		= '';
			$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier
								WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= $rowSPL->SPLDESC;
			endforeach;
				
			$updIR 			= array('IR_NUM'		=> $IR_NUM,
									'ISVOID'		=> $ISVOID,
									'VOID_DATE'		=> $VOID_DATE,
									'VOID_NOTE'		=> $VOID_NOTE);
			$this->m_itemreceipt->updateIR($IR_NUM, $updIR);
			
			$DOCSource		= '';
			if($IR_SOURCE == 2)		// MR
			{
				$ISDIRECT		= 2;
				$DOCSource		= "MRXXXXXXXX";
			}
			elseif($IR_SOURCE == 3)	// PO
			{
				$ISDIRECT		= 3;
				$this->m_itemreceipt->updatePO_VOID($IR_NUM, $PRJCODE, $PO_NUM, $ISDIRECT);
			}
			elseif($IR_SOURCE == 1)	// DIRECTS
			{
				$ISDIRECT		= 1;
				$Ref_Number 	= '';
				$PO_NUM			= '';
				$DOCSource		= 'Direct';
				$this->m_itemreceipt->updateJOBDET($IR_NUM, $PRJCODE, $ISDIRECT); // UPDATE JOBD ETAIL DAN PR
			}
			
			// START : TRACK FINANCIAL TRACK
			// DI HOLDED KARENA AKAN DILAKUKAN DISAAT TTK
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramFT = array('DOC_NUM' 		=> $IR_NUM,
								'DOC_DATE' 		=> $IR_DATE,
								'DOC_EDATE' 	=> $IR_DUEDATE,
								'PRJCODE' 		=> $PRJCODE,
								'FIELD_NAME1' 	=> 'FT_COP',
								'FIELD_NAME2' 	=> 'FM_COP',
								'TOT_AMOUNT'	=> $IR_AMOUNT);
				//$this->m_updash->finTrack($paramFT); HOLDED ON 11 JAN 19
			// END : TRACK FINANCIAL TRACK
							
			// START : JOURNAL HEADER
				$this->load->model('m_journal/m_journal', '', TRUE);
				
				$JournalH_Code	= "V$IR_NUM";
				$JournalType	= 'VIR';
				$JournalH_Date	= $IR_DATE;
				$Company_ID		= $comp_init;
				$DOCSource		= $DOCSource;
				$LastUpdate		= $IR_APPROVED;
				$WH_CODE		= $WH_CODE;
				$Refer_Number	= $PR_NUM;
				$RefType		= 'WBSD';
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
				foreach($_POST['data'] as $d)
				{
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$ITM_CODE 		= $d['ITM_CODE'];
					$JOBCODEID 		= $d['JOBCODEID'];
					$ACC_ID 		= $d['ACC_ID'];
					$ITM_UNIT 		= $d['ITM_UNIT'];
					$ITM_QTY 		= $d['ITM_QTY'];
					$ITM_PRICE 		= $d['ITM_PRICE'];
					$ITM_DISC 		= $d['ITM_DISC'];
					$TAXCODE1 		= $d['TAXCODE1'];
					$TAXPRICE1 		= $d['TAXPRICE1'];
					
					$JournalH_Code	= $IR_NUM;
					$JournalType	= 'IR';
					$JournalH_Date	= $IR_DATE;
					$Company_ID		= $comp_init;
					$Currency_ID	= 'IDR';
					$LastUpdate		= $IR_APPROVED;
					$WH_CODE		= $WH_CODE;
					$Refer_Number	= $PR_NUM;
					$RefType		= 'WBSD';
					$JSource		= 'WBSD';
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
										'ITM_CODE' 			=> $ITM_CODE,
										'ACC_ID' 			=> $ACC_ID,
										'ITM_UNIT' 			=> $ITM_UNIT,
										'ITM_QTY' 			=> $ITM_QTY,
										'ITM_PRICE' 		=> $ITM_PRICE,
										'ITM_DISC' 			=> $ITM_DISC,
										'TAX_CODE' 			=> $TAXCODE1,
										'TAX_AMOUNT' 		=> $TAXPRICE1);
					$this->m_journal->createJournalDMin($JournalH_Code, $parameters);
					
					// START : UPDATE STOCK
						$parameters1 = array('PRJCODE' 	=> $PRJCODE,
											'JOBCODEID'	=> $JOBCODEID,
											'IR_NUM' 	=> $IR_NUM,
											'IR_CODE' 	=> $IR_CODE,
											'ITM_CODE' 	=> $ITM_CODE,
											'ITM_QTY' 	=> $ITM_QTY,
											'ITM_PRICE' => $ITM_PRICE);
						$this->m_itemreceipt->updateITM_Min($parameters1);
					// START : UPDATE STOCK
					
					// START : RECORD TO ITEM HISTORY
						$this->m_journal->createITMHistMin($JournalH_Code, $parameters);
					// START : RECORD TO ITEM HISTORY
				}
			// END : JOURNAL DETAIL
			
			// START : UPDATE TO TRANS-COUNT
				if($ISVOID == 0)
				{
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $STAT_BEFORE;								// IF "ADD" CONDITION ALWAYS = IR_STAT
					$parameters 	= array('DOC_CODE' 		=> $IR_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "IR",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_ir_header",	// TABLE NAME
											'KEY_NAME'		=> "IR_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "IR_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $IR_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_IR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
											'FIELD_NM_N'	=> "TOT_IR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_C'	=> "TOT_IR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_A'	=> "TOT_IR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_R'	=> "TOT_IR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_RJ'	=> "TOT_IR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_CL'	=> "TOT_IR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
					$this->m_updash->updateDashData($parameters);
				}
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $IR_NUM;
				$MenuCode 		= 'MN068';
				$TTR_CATEG		= 'VOID-UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "IR",
										'TR_DATE' 		=> $IR_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_ir180c15/in180c15box/');
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataPOList() 
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
									"B.SPLDESC",
									"PO_NOTES");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_itemreceipt->get_AllDataPOC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_itemreceipt->get_AllDataPOL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$PO_NOTES	= $dataI['PO_NOTES'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$SPLCODE	= $dataI['SPLCODE'];
				$SPLDESC	= $dataI['SPLDESC'];

				$PO_NOTESV 	= wordwrap($PO_NOTES, 60, "<br>", TRUE);

				$chkBox		= "<input type='radio' name='chk0' value='".$PO_NUM."|".$PO_CODE."' onClick='pickThis0(this);'/>";
												
				
				$output['data'][] = array($chkBox,
										  $PO_CODE,
										  $PO_DATEV,
										  "<span style='white-space:nowrap'>$SPLDESC</span>",
										  "<span style='white-space:nowrap'>$PO_NOTESV</span>",
										  $PO_PLANIR);
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
									  "B",
									  "C",
									  "D",
									  "E",
									  "F");
			*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataIRDet() 				// AFTER REFRESH / SELECT OP
	{
		$PRJCODE		= $_GET['id'];
		$task			= $_GET['task'];
		$PO_NUM			= $_GET['PO_NUM'];
		$collDt 		= explode("~", $_GET['collDt']);
		$IR_NUM 		= $collDt[0];
		$IR_CODE 		= $collDt[1];
		
		$LangID 		= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alertAcc 	= "Belum diset kode akun penerimaan.";
		}
		else
		{
			$alertAcc 	= "Not set account receipt.";
		}
		
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
									"B.SPLDESC",
									"PO_NOTES");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			/*$sql_01 		= "DELETE FROM tbl_ir_detail_tmp WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql_01);*/
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_itemreceipt->get_AllDataPODC($PRJCODE, $PO_NUM, $IR_NUM, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_itemreceipt->get_AllDataPODL($PRJCODE, $PO_NUM, $IR_NUM, $search, $length, $start, $order, $dir);

			$maxNo	= 0;
			$sqlMax = "SELECT MAX(IR_ID) AS maxNo FROM tbl_ir_detail WHERE IR_NUM = '$IR_NUM'";
			$resMax = $this->db->query($sqlMax)->result();
			foreach($resMax as $rowMax) :
				$maxNo = $rowMax->maxNo;
			endforeach;
	
			// START : SHOW DETAIL
				$IR_AMOUNT			= 0;
				$IR_DISC			= 0;
				$IR_PPN				= 0;
				$IR_AMOUNT_NETT		= 0;
				$TAXCODE_PPN 		= "";
				$TAXCODE_PPH		= "";
				$noU				= $start + 1;
				foreach ($query->result_array() as $dataI) 
				{
					/*$maxNo			= $maxNo + 1;
					$IR_ID			= $maxNo;*/

					$IR_NUM 		= $IR_NUM;
					$IR_CODE 		= $IR_CODE;
					$PO_NUM 		= $PO_NUM;
					$PRJCODE		= $PRJCODE;
					$DEPCODE 		= $dataI['DEPCODE'];
					$DEPCODE 		= $dataI['DEPCODE'];
					$JOBCODEDET 	= $dataI['JOBCODEDET'];
					$JOBCODEID		= $dataI['JOBCODEID'];
					$PR_NUM 		= $dataI['PR_NUM'];
					$ACC_ID 		= $dataI['ACC_ID'];
					$ACC_ID_UM 		= $dataI['ACC_ID_UM'];
					$ITM_CODE 		= $dataI['ITM_CODE'];
					$ITM_UNIT 		= $dataI['ITM_UNIT'];
					$ITM_QTY_REM 	= $dataI['ITM_QTY_REM'];
					$ITM_QTY1 		= $dataI['ITM_QTY'];
					$PO_VOLM 		= $dataI['PO_VOLM'];			// 0
					$POD_ID 		= $dataI['POD_ID'];
					$ITM_QTY_BONUS	= $dataI['ITM_QTY_BONUS'];
					$ITM_PRICE 		= $dataI['ITM_PRICE'];
					$ITM_TOTAL 		= $dataI['ITM_TOTAL'];
					$ITM_DISP 		= $dataI['ITM_DISP'];
					$JOBPARDESC		= $dataI['JOBPARDESC'];
					$JOBPARENT		= $dataI['JOBPARENT'];
					$ITM_DISC 		= $dataI['ITM_DISC'];
					$ITM_DESC 		= $dataI['NOTES'];
					$TAXCODE1		= $dataI['TAXCODE1'];
					$TAXCODE2		= $dataI['TAXCODE2'];
					$TAXPRICE1		= $dataI['TAXPRICE1'];
					$TAXPRICE2		= $dataI['TAXPRICE2'];
					$ISPRCREATE 	= $dataI['ISPRCREATE'];
					$ADD_PRVOLM 	= $dataI['ADD_PRVOLM'];
					$ITM_NAME 		= $dataI['ITM_NAME'];
					$ITM_GROUP 		= $dataI['ITM_GROUP'];
					$ITM_CATEG 		= $dataI['ITM_CATEG'];
					$ISMTRL 		= $dataI['ISMTRL'];
					$ISRENT 		= $dataI['ISRENT'];
					$ISPART 		= $dataI['ISPART'];
					$ISFUEL 		= $dataI['ISFUEL'];
					$ISLUBRIC 		= $dataI['ISLUBRIC'];
					$ISFASTM 		= $dataI['ISFASTM'];
					$ISWAGE 		= $dataI['ISWAGE'];
					if($ISMTRL == 1)
						$ITM_TYPE	= 1;
					elseif($ISRENT == 1)
						$ITM_TYPE	= 2;
					elseif($ISPART == 1)
						$ITM_TYPE	= 3;
					elseif($ISFUEL == 1)
						$ITM_TYPE	= 4;
					elseif($ISLUBRIC == 1)
						$ITM_TYPE	= 5;
					elseif($ISFASTM == 1)
						$ITM_TYPE	= 6;
					else
						$ITM_TYPE	= 1;

					$ITM_QTY 		= $ITM_QTY1;
					//$IR_VOLM 		= $dataI['IR_VOLM'];
					//$IR_AMOUNT 	= $dataI['IR_AMOUNT'];
					$itemConvertion	= 1;

					if($JOBPARENT == '')
					{
						$sqlJDP 	= "SELECT A.JOBCODEID, A.JOBDESC FROM tbl_joblist_detail A
										WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
											WHERE B.JOBCODEID = '$JOBCODEID')";
						$resJDP 	= $this->db->query($sqlJDP)->result();
						foreach($resJDP as $rowJDP) :
							$JOBPARENT 	= $rowJDP->JOBCODEID;
							$JOBPARDESC = $rowJDP->JOBDESC;
						endforeach;
					}
					
					// GET REMAIN
						$TOT_IRQTY		= 0;
						$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY 
										FROM tbl_ir_detail A
											INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
										WHERE 
											B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
											AND A.IR_NUM != '$IR_NUM' AND IR_STAT IN (1,2,3)
											AND A.POD_ID = $POD_ID
											AND A.PO_NUM = '$PO_NUM'";
						$resQTY 	= $this->db->query($sqlQTY)->result();
						foreach($resQTY as $row1a) :
							$TOT_IRQTY 	= $row1a->TOT_IRQTY;
						endforeach;
						if($TOT_IRQTY == '')
							$TOT_IRQTY	= 0;

					if($TAXCODE1 != '')
						$TAXCODE_PPN	= $TAXCODE1;

					if($TAXCODE2 != '')
						$TAXCODE_PPH	= $TAXCODE2;

					$REMAINQTY		= $ITM_QTY1 - $TOT_IRQTY;
					
						$ITM_QTY 	= $REMAINQTY;
					
						$ITM_TOTAL 	= $ITM_QTY * $ITM_PRICE;

					$IR_AMOUNT 		= $IR_AMOUNT + $ITM_TOTAL;
					$IR_DISC 		= $IR_DISC + $ITM_DISC;					// TOTAL DISKON
					$IR_PPN 		= $IR_PPN + $TAXPRICE1;					// TOTAL PAJAK
					$IR_AMOUNTNET 	= $ITM_TOTAL - $ITM_DISC + $TAXPRICE1;
					$IR_AMOUNT_NETT	= $IR_AMOUNT_NETT + $IR_AMOUNTNET;

					$ItmCol1	= '';
					$ItmCol2	= '';
					$ttl 		= '';
					$divDesc 	= '';

					if($ITM_CATEG == 'UA' && $ACC_ID_UM == '')
					{
						$disBtn 	= 1;
						$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
						$ItmCol2	= '</span>';
						$ttl 		= 'Item ongkos angkut ini belum disetting Kode Akun';
						$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
						$isDisabled = 1;
					}
					elseif($ACC_ID == '' && $ITM_CATEG != 'UA')
					{
						$disBtn 	= 1;
						$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
						$ItmCol2	= '</span>';
						$ttl 		= 'Belum disetting kode akun penerimaan';
						$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
						$isDisabled = 1;
					}

					$ItmCol0a	= '';
					$ItmCol1a	= '';
					$ItmCol2a	= '';
					$ttla 		= '';
					$divDesca 	= '';
					if($ADD_PRVOLM > 0)
					{
						$ItmCol0a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
						$ItmCol1a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
						$ItmCol2a	= '</span>';
						$ttla 		= $excRec;
						$divDesca 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;$excRec : $ADD_PRVOLM $ITM_UNIT";
					}

					$chkCopy 		= "$IR_NUM|$IR_CODE|$PO_NUM|$PRJCODE|$POD_ID|$ITM_CODE|$ACC_ID|$JOBCODEDET|$JOBCODEID|$JOBPARENT|$JOBPARDESC|$ITM_NAME|$ITM_CATEG|$ITM_QTY|$ITM_UNIT|$ITM_GROUP|$ITM_CATEG|$ITM_TYPE|$ITM_PRICE|$ITM_TOTAL|$ITM_DISP|$ITM_DISC|$TAXCODE1|$TAXPRICE1|$IR_AMOUNTNET|$ITM_QTY_BONUS|$ITM_DESC|$ISPRCREATE|$ADD_PRVOLM";

					if($ITM_CATEG == 'UA')
					{
						$ITMUNIT 	= "hidden";
							
						$ITMQTY 	= "hidden<input type='hidden' class='form-control' style='text-align:right; max-width:100px' name='ITM_QTYX".$noU."' id='ITM_QTYX".$noU."' value='".number_format($ITM_QTY, 2)."' onBlur='changeValue(this, ".$noU.")' onKeyPress='return isIntOnlyNew(event);' size='20' >";
					}
					else
					{
						$ITMUNIT 	= $ITM_UNIT;

						$ITMQTY 	= "<input type='text' class='form-control' style='text-align:right; max-width:100px' name='ITM_QTYX".$noU."' id='ITM_QTYX".$noU."' value='".number_format($ITM_QTY, 2)."' onBlur='changeValue(this, ".$noU.")' onKeyPress='return isIntOnlyNew(event);' size='20' >";
					}
					
					/*$sql_01 		= 	"INSERT INTO tbl_ir_detail_tmp
										(IR_ID, PRJCODE, DEPCODE, IR_NUM, IR_CODE, ACC_ID, IR_SOURCE, PR_NUM, PO_NUM, JOBCODEDET, JOBCODEID,
											JOBPARENT, JOBPARDESC, POD_ID, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_TYPE, ITM_GROUP,
											ITM_QTY_REM, ITM_QTY, ITM_PRICE, ITM_TOTAL, ITM_DISP, ITM_DISC, TAXCODE1, TAXCODE2,
											TAXPRICE1, TAXPRICE2, ITM_TOTAL_NETT, NOTES)
										VALUES
										($IR_ID, '$PRJCODE', '$DEPCODE', '$IR_NUM', '$IR_CODE', '$ACC_ID', 3, '$PR_NUM', '$PO_NUM', '$JOBCODEDET', '$JOBCODEID',
											'$JOBPARENT', '$JOBPARDESC', '$POD_ID', '$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', '$ITM_TYPE', '$ITM_GROUP',
											'$ITM_QTY_REM', '$ITM_QTY', '$ITM_PRICE', '$ITM_TOTAL', '$ITM_DISP', '$ITM_DISC', '$TAXCODE1', '$TAXCODE2',
											'$TAXPRICE1', '$TAXPRICE2', '$IR_AMOUNTNET', '$ITM_DESC')";
					$this->db->query($sql_01);*/

					$secDelROW 	= base_url().'index.php/c_inventory/c_ir180c15/delROW/?id=';
					$delROW 	= "$secDelROW~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE";
					$secCopy 	= base_url().'index.php/c_inventory/c_ir180c15/copyROW/?id=';
					$copyROW 	= "$secCopy~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE~$IR_ID";

					if($REMAINQTY > 0)
					{
						$maxNo			= $maxNo + 1;
						$IR_ID			= $maxNo;

						$output['data'][] 	= array("<label style='white-space:nowrap'>
														<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delROW."'>
														<input type='hidden' name='urlCopy".$noU."' id='urlCopy".$noU."' value='".$copyROW."'>
														<input type='hidden' name='chkCopy".$noU."' id='chkCopy".$noU."' value='".$chkCopy."'>
			                                            <a onClick='deleteRow(".$noU.")' title='Delete' class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a>
			                                            <a onClick='copyRow(".$noU.")' title='Copy' class='btn btn-warning btn-xs'><i class='glyphicon glyphicon-copy'></i></a>
				                                        </label>
				                                        <input type='Checkbox' id='data[".$noU."][chk]' name='data[".$noU."][chk]' value='".$noU."' onClick='pickThis(this,".$noU.")' style='display:none'>
														<input type='Checkbox' style='display:none' id='chk".$noU."' name='chk".$noU."' value=''>
														<input type='hidden' id='chk' name='chk' value='".$noU."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
														<input type='hidden' id='data".$noU."IR_ID' name='data[".$noU."][IR_ID]' value='".$IR_ID."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
														<input type='hidden' id='data".$noU."IR_NUM' name='data[".$noU."][IR_NUM]' value='".$IR_NUM."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
														<input type='hidden' id='data".$noU."IR_CODE' name='data[".$noU."][IR_CODE]' value='".$IR_CODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
														<input type='hidden' id='data".$noU."PO_NUM' name='data[".$noU."][PO_NUM]' value='".$PO_NUM."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
														<input type='hidden' id='data".$noU."PRJCODE' name='data[".$noU."][PRJCODE]' value='".$PRJCODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>",
												  	"<label style='white-space:nowrap'>
													  		".$ITM_CODE." : ".$ITM_NAME."
					                                    	<div style='font-style: italic;'>
														  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;".$JOBPARDESC."
														  	</div>
														  	".$ItmCol1.$divDesc.$ItmCol2."
													  		".$ItmCol1a.$divDesca.$ItmCol2a."
												  		</label>
														<input type='hidden' class='form-control' name='itemname".$noU."' id='itemname".$noU."' value='".$ITM_NAME."' >
														
														<input type='hidden' id='data".$noU."POD_ID' name='data[".$noU."][POD_ID]' value='".$POD_ID."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
														<input type='hidden' id='data".$noU."ITM_CODE' name='data[".$noU."][ITM_CODE]' value='".$ITM_CODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
														<input type='hidden' id='data".$noU."ACC_ID' name='data[".$noU."][ACC_ID]' value='".$ACC_ID."' width='10' size='15' readonly class='form-control'>
														<input type='hidden' id='data".$noU."JOBCODEDET' name='data[".$noU."][JOBCODEDET]' value='".$JOBCODEDET."' width='10' size='15' readonly class='form-control'>
														<input type='hidden' id='data".$noU."JOBCODEID' name='data[".$noU."][JOBCODEID]' value='".$JOBCODEID."' width='10' size='15' readonly class='form-control'>
				                                        <input type='hidden' id='data".$noU."JOBPARENT' name='data[".$noU."][JOBPARENT]' value='".$JOBPARENT."' width='10' size='15'>
				                                        <input type='hidden' id='data".$noU."JOBPARDESC' name='data[".$noU."][JOBPARDESC]' value='".$JOBPARDESC."' width='10' size='15'>",
												  	"<input type='text' id='data".$noU."SJ_NUM' name='data[".$noU."][SJ_NUM]' value='' width='10' size='15' class='form-control' style='max-width:350px;' onChange='changeSJ(this.value, ".$IR_ID.", ".$noU.")'>",
												  	"$ITMQTY
														<input type='hidden' style='text-align:right' id='ITM_QTY_PO".$noU."' name='data[".$noU."][ITM_QTY_PO]' size='10' value='".$ITM_QTY_PO."' >
												  		<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_QTY]' id='ITM_QTY".$noU."' size='10' value='".$ITM_QTY."' >
														<input type='hidden' style='text-align:right' id='REMAINQTY".$noU."' size='10' value='".$ITM_QTY_REM."' >
														<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_QTY_REM]' id='ITM_QTY_REM".$noU."' size='10' value='".$ITM_QTY_REM."' >",
												  	"$ITMUNIT
												  		<input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_UNIT]' id='ITM_UNIT".$noU."' size='10' value='".$ITM_UNIT."' >
			                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_GROUP]' id='ITM_GROUP".$noU."' size='10' value='".$ITM_GROUP."' >
			                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' id='ITM_CATEG".$noU."' size='10' value='".$ITM_CATEG."' >
			                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_TYPE]' id='ITM_TYPE".$noU."' size='10' value='".$ITM_TYPE."' >",
												  	"hidden
														<input type='hidden' class='form-control' style='min-width:100px; max-width:350px; text-align:right;' name='ITM_PRICEX".$noU."' id='ITM_PRICEX".$noU."' value='".number_format($ITM_PRICE, 2)."' onBlur='changeValuePrc(this, ".$noU.")' disabled >
														<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_PRICE]' id='ITM_PRICE".$noU."' size='10' value='".$ITM_PRICE."' >
														<!-- Item Price -->
														<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_TOTAL]' id='ITM_TOTAL".$noU."' size='10' value='".$ITM_TOTAL."' >",
												  	"hidden
														<input type='hidden' class='form-control' size='10'  style=' min-width:65px; max-width:150px; text-align:right;' name='ITM_DISPx".$noU."' id='ITM_DISPx".$noU."' value='".number_format($ITM_DISP, 2)."' onBlur='countDisp(this, ".$noU.");' disabled >
														<input style='text-align:right' type='hidden' name='data[".$noU."][ITM_DISP]' id='ITM_DISP".$noU."' value='".$ITM_DISP."'>",
												  	"hidden
														<input type='text' class='form-control' style='min-width:100px; max-width:350px; text-align:right; display:none' name='ITM_DISCX".$noU."' id='ITM_DISCX".$noU."' value='".number_format($ITM_DISC, 2)."' onBlur='countDisc(this, ".$noU.");' disabled >
														<input style='text-align:right' type='hidden' name='data[".$noU."][ITM_DISC]' id='ITM_DISC".$noU."' value='".$ITM_DISC."'>",
												  	"hidden
			                                        	<input type='hidden' name='data[".$noU."][TAXCODE1]' id='TAXCODE1".$noU."' value='".$TAXCODE1."'>
														<input type='hidden' style='text-align:right' name='data[".$noU."][TAXPRICE1]' id='TAXPRICE1".$noU."' size='10' value='".$TAXPRICE1."' >",
												  	"hidden 
												  		<input type='hidden' class='form-control' style='min-width:100px; max-width:350px; text-align:right;' name='ITM_TOTAL_NETT".$noU."' id='ITM_TOTAL_NETT".$noU."' value='".number_format($IR_AMOUNTNET, 2)."' disabled >",
												  	"".$ITM_DESC."<input type='hidden' name='data[".$noU."][NOTES]' id='data".$noU."NOTES' value='".$ITM_DESC."' class='form-control' style='text-align:left'><input type='hidden' name='data[".$noU."][ISPRCREATE]' id='ISPRCREATE".$noU."' value='".$ISPRCREATE."' class='form-control'>
														<input type='hidden' name='data[".$noU."][ADD_PRVOLM]' id='ADD_PRVOLM".$noU."' value='".$ADD_PRVOLM."' class='form-control'>");
						

						$noU		= $noU + 1;
					}
				}
			// END : SHOW DETAIL

			/*$output['data'][] = array("$PO_NUM",
									  "$IR_NUM",
									  "$PRJCODE",
									  "D",
									  "E",
									  "F",
									  "F",
									  "F",
									  "F",
									  "F",
									  "F");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataIRDetEdit() 			// AUTO LOAD ON EDIT
	{
		$PRJCODE		= $_GET['id'];
		$PO_NUM			= $_GET['PO_NUM'];
		$collDt 		= explode("~", $_GET['collDt']);
		$IR_NUM 		= $collDt[0];
		$IR_CODE 		= $collDt[1];
		
		$LangID 		= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;

			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'recMore')$recMore = $LangTransl;
			if($TranslCode == 'excRec')$excRec = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alertAcc 	= "Belum diset kode akun penerimaan.";
		}
		else
		{
			$alertAcc 	= "Not set account receipt.";
		}
		
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
									"B.SPLDESC",
									"PO_NOTES");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$IR_STAT 		= 1;
			$sql_01 		= "SELECT IR_STAT FROM tbl_ir_header WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
			$res_01 		= $this->db->query($sql_01)->result();
			foreach($res_01 as $rw_01):
				$IR_STAT 	= $rw_01->IR_STAT;
			endforeach;
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_itemreceipt->get_AllDataPODIREDC($PRJCODE, $PO_NUM, $IR_NUM, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_itemreceipt->get_AllDataPODIREDL($PRJCODE, $PO_NUM, $IR_NUM, $search, $length, $start, $order, $dir);

			$IR_AMOUNT			= 0;
			$IR_DISC			= 0;
			$IR_PPN				= 0;
			$IR_AMOUNT_NETT		= 0;
			$TAXCODE_PPN 		= "";
			$TAXCODE_PPH		= "";
			$noU				= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$IR_NUM 		= $IR_NUM;
				$IR_CODE 		= $IR_CODE;
				$PO_NUM 		= $PO_NUM;

				$PR_NUM 		= $dataI['PR_NUM'];
				$IR_ID			= $dataI['IR_ID'];
				$SJ_NUM			= $dataI['SJ_NUM'];
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$JOBPARENT		= $dataI['JOBPARENT'];
				$JOBPARDESC		= $dataI['JOBPARDESC'];
				$ACC_ID 		= $dataI['ACC_ID'];
				$ACC_ID_UM 		= $dataI['ACC_ID_UM'];
				$POD_ID 		= $dataI['POD_ID'];
				$ITM_CODE 		= $dataI['ITM_CODE'];
				$ITM_UNIT 		= $dataI['ITM_UNIT'];
				$ITM_GROUP 		= $dataI['ITM_GROUP'];
				$ITM_CATEG 		= $dataI['ITM_CATEG'];
				$ITM_NAME 		= $dataI['ITM_NAME'];
				$ITM_QTY_REM 	= $dataI['ITM_QTY_REM'];

				if($JOBPARENT == '')
				{
					$sqlJDP 	= "SELECT A.JOBCODEID, A.JOBDESC FROM tbl_joblist_detail A
									WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
										WHERE B.JOBCODEID = '$JOBCODEID')";
					$resJDP 	= $this->db->query($sqlJDP)->result();
					foreach($resJDP as $rowJDP) :
						$JOBPARENT 	= $rowJDP->JOBCODEID;
						$JOBPARDESC = $rowJDP->JOBDESC;
					endforeach;
				}
				
				$ITM_QTY 		= $dataI['ITM_QTY'];
				$PO_VOLM 		= $dataI['PO_VOLM'];
				$ITM_QTY_PO		= $dataI['PO_VOLM'];
				$ITM_QTY_BONUS	= $dataI['ITM_QTY_BONUS'];
				$ITM_PRICE 		= $dataI['ITM_PRICE'];
				$ITM_DISP 		= $dataI['ITM_DISP'];
				$ITM_DISC 		= $dataI['ITM_DISC'];
				$ITM_TOTAL 		= $dataI['ITM_TOTAL'];
				$ITM_DESC 		= $dataI['NOTES'];
				$TAXCODE1		= $dataI['TAXCODE1'];
				$TAXCODE2		= $dataI['TAXCODE2'];
				$TAXPRICE1		= $dataI['TAXPRICE1'];
				$TAXPRICE2		= $dataI['TAXPRICE2'];

				if($TAXCODE1 != '')
					$TAXCODE_PPN	= $TAXCODE1;

				if($TAXCODE2 != '')
					$TAXCODE_PPH	= $TAXCODE2;

				$ISPRCREATE 	= $dataI['ISPRCREATE'];
				$ADD_PRVOLM 	= $dataI['ADD_PRVOLM'];
				$itemConvertion	= 1;
				$ISMTRL 		= $dataI['ISMTRL'];
				$ISRENT 		= $dataI['ISRENT'];
				$ISPART 		= $dataI['ISPART'];
				$ISFUEL 		= $dataI['ISFUEL'];
				$ISLUBRIC 		= $dataI['ISLUBRIC'];
				$ISFASTM 		= $dataI['ISFASTM'];
				$ISWAGE 		= $dataI['ISWAGE'];
				if($ISMTRL == 1)
					$ITM_TYPE	= 1;
				elseif($ISRENT == 1)
					$ITM_TYPE	= 2;
				elseif($ISPART == 1)
					$ITM_TYPE	= 3;
				elseif($ISFUEL == 1)
					$ITM_TYPE	= 4;
				elseif($ISLUBRIC == 1)
					$ITM_TYPE	= 5;
				elseif($ISFASTM == 1)
					$ITM_TYPE	= 6;
				else
					$ITM_TYPE	= 1;

				// GET TOTAL LPM AND REMAIN
					$s_01 	= "SELECT SUM(ITM_QTY) AS TOT_VOL FROM tbl_ir_detail_tmp
								WHERE ITM_CODE = '$ITM_CODE' AND IR_ID != $IR_ID AND IR_NUM = '$IR_NUM'";
					$r_01 	= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$TOT_VOL = $rw_01->TOT_VOL;
					endforeach;
					if($TOT_VOL == '')
						$TOT_VOL= 0;
				
				// TOTAL IR CONFIRMED AND APPROVED
					$TOT_IRQTY		= 0;
					$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY 
									FROM tbl_ir_detail A
										INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
									WHERE 
										B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
										AND A.IR_NUM != '$IR_NUM' AND IR_STAT IN (1,2,3)
										AND A.POD_ID = $POD_ID
										AND A.PO_NUM = '$PO_NUM'";
					$resQTY 	= $this->db->query($sqlQTY)->result();
					foreach($resQTY as $row1a) :
						$TOT_IRQTY 	= $row1a->TOT_IRQTY;
					endforeach;
					if($TOT_IRQTY == '')
						$TOT_IRQTY	= 0;

				$ITM_QTY_REM 	= $ITM_QTY_PO - $TOT_VOL - $TOT_IRQTY;

				$ITM_TOTAL 		= $dataI['ITM_TOTAL'];						// Non-PPn

				$IR_AMOUNT 		= $IR_AMOUNT + $ITM_TOTAL;
				$IR_DISC 		= $IR_DISC + $ITM_DISC;					// TOTAL DISKON
				$IR_PPN 		= $IR_PPN + $TAXPRICE1;					// TOTAL PAJAK
				$IR_AMOUNTNET 	= $ITM_TOTAL - $ITM_DISC + $TAXPRICE1;
				$IR_AMOUNT_NETT	= $IR_AMOUNT_NETT + $IR_AMOUNTNET;

				if($IR_STAT == 1 || $IR_STAT == 4)
				{
					$isEditable = 1;
					$readOnly 	= "";
					$disAble 	= "";
					$hiDden 	= "";
					$btnCpyDel 	= "	<a onClick='deleteRow(".$noU.")' title='Delete' class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a>
	                               	<a onClick='copyRow(".$noU.")' title='Copy' class='btn btn-warning btn-xs'><i class='glyphicon glyphicon-copy'></i></a>";
					$textSJ 	= "<input type='text' id='data".$noU."SJ_NUM' name='data[".$noU."][SJ_NUM]' value='".$SJ_NUM."' width='10' size='15' class='form-control' style='max-width:350px;' onChange='changeSJ(this.value, ".$IR_ID.", ".$noU.")'>";
				}
				else
				{
					$readOnly 	= "readonly";
					$disAble 	= "disabled";
					$hiDden 	= "; display: none";
					$isEditable = 0;
					$btnCpyDel 	= "$noU.";
					$textSJ 	= $SJ_NUM."<input type='hidden' id='data".$noU."SJ_NUM' name='data[".$noU."][SJ_NUM]' value='".$SJ_NUM."' width='10' size='15' class='form-control' style='max-width:350px;' onChange='changeSJ(this.value, ".$IR_ID.", ".$noU.")'>";
					$textVol 	= $SJ_NUM."<input type='hidden' id='data".$noU."SJ_NUM' name='data[".$noU."][SJ_NUM]' value='".$SJ_NUM."' width='10' size='15' class='form-control' style='max-width:350px;' onChange='changeSJ(this.value, ".$IR_ID.", ".$noU.")'>";
				}

				$ItmCol1	= '';
				$ItmCol2	= '';
				$ttl 		= '';
				$divDesc 	= '';

				if($ITM_CATEG == 'UA' && $ACC_ID_UM == '')
				{
					$disBtn 	= 1;
					$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol2	= '</span>';
					$ttl 		= 'Item ongkos angkut ini belum disetting Kode Akun';
					$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
					$isDisabled = 1;
				}
				elseif($ACC_ID == '' && $ITM_CATEG != 'UA')
				{
					$disBtn 	= 1;
					$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol2	= '</span>';
					$ttl 		= 'Belum disetting kode akun penerimaan';
					$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
					$isDisabled = 1;
				}

				$ItmCol0a	= '';
				$ItmCol1a	= '';
				$ItmCol2a	= '';
				$ttla 		= '';
				$divDesca 	= '';
				if($ADD_PRVOLM > 0)
				{
					$ItmCol0a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol1a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol2a	= '</span>';
					$ttla 		= $excRec;
					$divDesca 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;$excRec : $ADD_PRVOLM $ITM_UNIT";
				}

				if($ITM_CATEG == 'UA')
				{
					$ITMUNIT 	= "hidden";
						
					$ITMQTY 	= "hidden<input type='hidden' class='form-control' style='text-align:right; max-width:100px' name='ITM_QTYX".$noU."' id='ITM_QTYX".$noU."' value='".number_format($ITM_QTY, 2)."' onBlur='changeValue(this, ".$noU.")' onKeyPress='return isIntOnlyNew(event);' size='20' >";
				}
				else
				{
					$ITMUNIT 	= $ITM_UNIT;
					
					if($IR_STAT == 1 || $IR_STAT == 4)
						$ITMQTY 	= "<input type='text' class='form-control' style='text-align:right; max-width:100px' name='ITM_QTYX".$noU."' id='ITM_QTYX".$noU."' value='".number_format($ITM_QTY, 2)."' onBlur='changeValue(this, ".$noU.")' onKeyPress='return isIntOnlyNew(event);' size='20' >";
					else
						$ITMQTY 	= number_format($ITM_QTY, 2)."<input type='hidden' class='form-control' style='text-align:right; max-width:100px' name='ITM_QTYX".$noU."' id='ITM_QTYX".$noU."' value='".number_format($ITM_QTY, 2)."' onBlur='changeValue(this, ".$noU.")' onKeyPress='return isIntOnlyNew(event);' size='20' >";
				}

				$chkCopy 		= "$IR_ID|$IR_NUM|$IR_CODE|$PO_NUM|$PRJCODE|$POD_ID|$ITM_CODE|$ACC_ID|$JOBCODEDET|$JOBCODEID|$JOBPARENT|$JOBPARDESC|$ITM_NAME|$ITM_CATEG|$ITM_QTY|$ITM_UNIT|$ITM_GROUP|$ITM_CATEG|$ITM_TYPE|$ITM_PRICE|$ITM_TOTAL|$ITM_DISP|$ITM_DISC|$TAXCODE1|$TAXPRICE1|$IR_AMOUNTNET|$ITM_QTY_BONUS|$ITM_DESC|$ISPRCREATE|$ADD_PRVOLM";

				$secDelROW 	= base_url().'index.php/c_inventory/c_ir180c15/delROW/?id=';
				$delROW 	= "$secDelROW~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE";
				$secCopy 	= base_url().'index.php/c_inventory/c_ir180c15/copyROW/?id=';
				$copyROW 	= "$secCopy~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE~$IR_ID";

				$output['data'][] 	= array(
											"<label style='white-space:nowrap'>
												<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delROW."'>
												<input type='hidden' name='urlCopy".$noU."' id='urlCopy".$noU."' value='".$copyROW."'>
												<input type='hidden' name='chkCopy".$noU."' id='chkCopy".$noU."' value='".$chkCopy."'>
	                                            $btnCpyDel
		                                        </label>
		                                        <input type='Checkbox' id='data[".$noU."][chk]' name='data[".$noU."][chk]' value='".$noU."' onClick='pickThis(this,".$noU.")' style='display:none'>
												<input type='Checkbox' style='display:none' id='chk".$noU."' name='chk".$noU."' value=''>
												<input type='hidden' id='chk' name='chk' value='".$noU."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."IR_ID' name='data[".$noU."][IR_ID]' value='".$IR_ID."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."IR_NUM' name='data[".$noU."][IR_NUM]' value='".$IR_NUM."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."IR_CODE' name='data[".$noU."][IR_CODE]' value='".$IR_CODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."PO_NUM' name='data[".$noU."][PO_NUM]' value='".$PO_NUM."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."PRJCODE' name='data[".$noU."][PRJCODE]' value='".$PRJCODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>",
										  	"<label style='white-space:nowrap'>
											  		".$ITM_CODE." : ".$ITM_NAME."
			                                    	<div style='font-style: italic;'>
												  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;".$JOBPARDESC."
												  	</div>
												  	".$ItmCol1.$divDesc.$ItmCol2."
											  		".$ItmCol1a.$divDesca.$ItmCol2a."
										  		</label>
												<input type='hidden' class='form-control' name='itemname".$noU."' id='itemname".$noU."' value='".$ITM_NAME."' >
												
												<input type='hidden' id='data".$noU."POD_ID' name='data[".$noU."][POD_ID]' value='".$POD_ID."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."ITM_CODE' name='data[".$noU."][ITM_CODE]' value='".$ITM_CODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."ACC_ID' name='data[".$noU."][ACC_ID]' value='".$ACC_ID."' width='10' size='15' readonly class='form-control'>
												<input type='hidden' id='data".$noU."JOBCODEDET' name='data[".$noU."][JOBCODEDET]' value='".$JOBCODEDET."' width='10' size='15' readonly class='form-control'>
												<input type='hidden' id='data".$noU."JOBCODEID' name='data[".$noU."][JOBCODEID]' value='".$JOBCODEID."' width='10' size='15' readonly class='form-control'>
		                                        <input type='hidden' id='data".$noU."JOBPARENT' name='data[".$noU."][JOBPARENT]' value='".$JOBPARENT."' width='10' size='15'>
		                                        <input type='hidden' id='data".$noU."JOBPARDESC' name='data[".$noU."][JOBPARDESC]' value='".$JOBPARDESC."' width='10' size='15'>",
										  	"$textSJ",
										  	"$ITMQTY
												<input type='hidden' style='text-align:right' id='ITM_QTY_PO".$noU."' name='data[".$noU."][ITM_QTY_PO]' size='10' value='".$PO_VOLM."' >
										  		<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_QTY]' id='ITM_QTY".$noU."' size='10' value='".$ITM_QTY."' >
												<input type='hidden' style='text-align:right' id='REMAINQTY".$noU."' size='10' value='".$ITM_QTY_REM."' >
												<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_QTY_REM]' id='ITM_QTY_REM".$noU."' size='10' value='".$ITM_QTY_REM."' >",
										  	"$ITMUNIT
										  		<input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_UNIT]' id='ITM_UNIT".$noU."' size='10' value='".$ITM_UNIT."' >
	                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_GROUP]' id='ITM_GROUP".$noU."' size='10' value='".$ITM_GROUP."' >
	                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' id='ITM_CATEG".$noU."' size='10' value='".$ITM_CATEG."' >
	                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_TYPE]' id='ITM_TYPE".$noU."' size='10' value='".$ITM_TYPE."' >",
										  	"hidden
												<input type='hidden' class='form-control' style='min-width:100px; max-width:350px; text-align:right;' name='ITM_PRICEX".$noU."' id='ITM_PRICEX".$noU."' value='".number_format($ITM_PRICE, 2)."' onBlur='changeValuePrc(this, ".$noU.")' disabled >
												<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_PRICE]' id='ITM_PRICE".$noU."' size='10' value='".$ITM_PRICE."' >
												<!-- Item Price -->
												<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_TOTAL]' id='ITM_TOTAL".$noU."' size='10' value='".$ITM_TOTAL."' >",
										  	"hidden
												<input type='hidden' class='form-control' size='10'  style=' min-width:65px; max-width:150px; text-align:right;' name='ITM_DISPx".$noU."' id='ITM_DISPx".$noU."' value='".number_format($ITM_DISP, 2)."' onBlur='countDisp(this, ".$noU.");' disabled >
												<input style='text-align:right' type='hidden' name='data[".$noU."][ITM_DISP]' id='ITM_DISP".$noU."' value='".$ITM_DISP."'>",
										  	"hidden
												<input type='text' class='form-control' style='min-width:100px; max-width:350px; text-align:right; display:none' name='ITM_DISCX".$noU."' id='ITM_DISCX".$noU."' value='".number_format($ITM_DISC, 2)."' onBlur='countDisc(this, ".$noU.");' disabled >
												<input style='text-align:right' type='hidden' name='data[".$noU."][ITM_DISC]' id='ITM_DISC".$noU."' value='".$ITM_DISC."'>",
										  	"hidden
	                                        	<input type='hidden' name='data[".$noU."][TAXCODE1]' id='TAXCODE1".$noU."' value='".$TAXCODE1."'>
												<input type='hidden' style='text-align:right' name='data[".$noU."][TAXPRICE1]' id='TAXPRICE1".$noU."' size='10' value='".$TAXPRICE1."' >",
										  	"hidden 
										  		<input type='hidden' class='form-control' style='min-width:100px; max-width:350px; text-align:right;' name='ITM_TOTAL_NETT".$noU."' id='ITM_TOTAL_NETT".$noU."' value='".number_format($IR_AMOUNTNET, 2)."' disabled >",
										  	"".$ITM_DESC."<input type='hidden' name='data[".$noU."][NOTES]' id='data".$noU."NOTES' value='".$ITM_DESC."' class='form-control' style='text-align:left'><input type='hidden' name='data[".$noU."][ISPRCREATE]' id='ISPRCREATE".$noU."' value='".$ISPRCREATE."' class='form-control'>
												<input type='hidden' name='data[".$noU."][ADD_PRVOLM]' id='ADD_PRVOLM".$noU."' value='".$ADD_PRVOLM."' class='form-control'>");
				

				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
									  "B",
									  "C",
									  "D",
									  "E",
									  "F",
									  "F",
									  "F",
									  "F",
									  "F",
									  "F");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataIRDetAftCpy() 			// AFTER COPY ROW
	{
		$PRJCODE		= $_GET['id'];
		$PO_NUM			= $_GET['PO_NUM'];
		$collDt 		= explode("~", $_GET['collDt']);
		$IR_NUM 		= $collDt[0];
		$IR_CODE 		= $collDt[1];
		
		$LangID 		= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alertAcc 	= "Belum diset kode akun penerimaan.";
		}
		else
		{
			$alertAcc 	= "Not set account receipt.";
		}
		
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
									"B.SPLDESC",
									"PO_NOTES");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			/*$sql_01 		= "DELETE FROM tbl_ir_detail_tmp WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql_01);*/
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_itemreceipt->get_AllDatatTmpIRC($PRJCODE, $PO_NUM, $IR_NUM, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_itemreceipt->get_AllDatatTmpIRL($PRJCODE, $PO_NUM, $IR_NUM, $search, $length, $start, $order, $dir);

			$IR_AMOUNT			= 0;
			$IR_DISC			= 0;
			$IR_PPN				= 0;
			$IR_AMOUNT_NETT		= 0;
			$TAXCODE_PPN 		= "";
			$TAXCODE_PPH		= "";
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$IR_NUM 		= $IR_NUM; 
				$IR_CODE 		= $IR_CODE;
				$PO_NUM 		= $PO_NUM;
				$PRJCODE		= $PRJCODE;
				$IR_ID 			= $dataI['IR_ID'];
				$SJ_NUM			= $dataI['SJ_NUM'];
				$DEPCODE 		= $dataI['DEPCODE'];
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$PR_NUM 		= $dataI['PR_NUM'];
				$ACC_ID 		= $dataI['ACC_ID'];
				$ACC_ID_UM 		= $dataI['ACC_ID_UM'];
				$ITM_CODE 		= $dataI['ITM_CODE'];
				$ITM_UNIT 		= $dataI['ITM_UNIT'];
				$ITM_QTY_REM 	= $dataI['ITM_QTY_REM'];
				$ITM_QTY 		= $dataI['ITM_QTY'];
				$PO_VOLM 		= $dataI['PO_VOLM'];
				$ITM_QTY_PO 	= $dataI['PO_VOLM'];
				$POD_ID 		= $dataI['POD_ID'];
				$ITM_QTY_BONUS	= $dataI['ITM_QTY_BONUS'];
				$ITM_PRICE 		= $dataI['ITM_PRICE'];
				$ITM_TOTAL 		= $dataI['ITM_TOTAL'];
				$ITM_DISP 		= $dataI['ITM_DISP'];
				$JOBPARDESC		= $dataI['JOBPARDESC'];
				$JOBPARENT		= $dataI['JOBPARENT'];
				$ITM_DISC 		= $dataI['ITM_DISC'];
				$ITM_DESC 		= $dataI['NOTES'];
				$TAXCODE1		= $dataI['TAXCODE1'];
				$TAXCODE2		= $dataI['TAXCODE2'];
				$TAXPRICE1		= $dataI['TAXPRICE1'];
				$TAXPRICE2		= $dataI['TAXPRICE2'];
				$ISPRCREATE 	= $dataI['ISPRCREATE'];
				$ADD_PRVOLM 	= $dataI['ADD_PRVOLM'];
				$ITM_NAME 		= $dataI['ITM_NAME'];
				$ITM_GROUP 		= $dataI['ITM_GROUP'];
				$ITM_CATEG 		= $dataI['ITM_CATEG'];
				$ISMTRL 		= $dataI['ISMTRL'];
				$ISRENT 		= $dataI['ISRENT'];
				$ISPART 		= $dataI['ISPART'];
				$ISFUEL 		= $dataI['ISFUEL'];
				$ISLUBRIC 		= $dataI['ISLUBRIC'];
				$ISFASTM 		= $dataI['ISFASTM'];
				$ISWAGE 		= $dataI['ISWAGE'];
				if($ISMTRL == 1)
					$ITM_TYPE	= 1;
				elseif($ISRENT == 1)
					$ITM_TYPE	= 2;
				elseif($ISPART == 1)
					$ITM_TYPE	= 3;
				elseif($ISFUEL == 1)
					$ITM_TYPE	= 4;
				elseif($ISLUBRIC == 1)
					$ITM_TYPE	= 5;
				elseif($ISFASTM == 1)
					$ITM_TYPE	= 6;
				else
					$ITM_TYPE	= 1;

				//$IR_VOLM 		= $dataI['IR_VOLM'];
				//$IR_AMOUNT 	= $dataI['IR_AMOUNT'];
				$itemConvertion	= 1;

				if($JOBPARENT == '')
				{
					$sqlJDP 	= "SELECT A.JOBCODEID, A.JOBDESC FROM tbl_joblist_detail A
									WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
										WHERE B.JOBCODEID = '$JOBCODEID')";
					$resJDP 	= $this->db->query($sqlJDP)->result();
					foreach($resJDP as $rowJDP) :
						$JOBPARENT 	= $rowJDP->JOBCODEID;
						$JOBPARDESC = $rowJDP->JOBDESC;
					endforeach;
				}

				// GET TOTAL LPM AND REMAIN
					$s_01 	= "SELECT SUM(ITM_QTY) AS TOT_VOL FROM tbl_ir_detail_tmp
								WHERE ITM_CODE = '$ITM_CODE' AND IR_ID != $IR_ID AND IR_NUM = '$IR_NUM'";
					$r_01 	= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$TOT_VOL = $rw_01->TOT_VOL;
					endforeach;
					if($TOT_VOL == '')
						$TOT_VOL= 0;
				
				// TOTAL IR CONFIRMED AND APPROVED
					$TOT_IRQTY		= 0;
					$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY 
									FROM tbl_ir_detail A
										INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
									WHERE 
										B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
										AND A.IR_NUM != '$IR_NUM' AND IR_STAT IN (1,2,3)
										AND A.POD_ID = $POD_ID
										AND A.PO_NUM = '$PO_NUM'";
					$resQTY 	= $this->db->query($sqlQTY)->result();
					foreach($resQTY as $row1a) :
						$TOT_IRQTY 	= $row1a->TOT_IRQTY;
					endforeach;
					if($TOT_IRQTY == '')
						$TOT_IRQTY	= 0;

				$ITM_QTY_REM 	= $ITM_QTY_PO - $TOT_VOL - $TOT_IRQTY;
				
				$ITM_TOTAL 		= $ITM_QTY * $ITM_PRICE;

				if($TAXCODE1 != '')
					$TAXCODE_PPN	= $TAXCODE1;

				if($TAXCODE2 != '')
					$TAXCODE_PPH	= $TAXCODE2;

				$IR_AMOUNT 		= $IR_AMOUNT + $ITM_TOTAL;
				$IR_DISC 		= $IR_DISC + $ITM_DISC;					// TOTAL DISKON
				$IR_PPN 		= $IR_PPN + $TAXPRICE1;					// TOTAL PAJAK
				$IR_AMOUNTNET 	= $ITM_TOTAL - $ITM_DISC + $TAXPRICE1;
				$IR_AMOUNT_NETT	= $IR_AMOUNT_NETT + $IR_AMOUNTNET;

				$ItmCol1	= '';
				$ItmCol2	= '';
				$ttl 		= '';
				$divDesc 	= '';

				if($ITM_CATEG == 'UA' && $ACC_ID_UM == '')
				{
					$disBtn 	= 1;
					$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol2	= '</span>';
					$ttl 		= 'Item ongkos angkut ini belum disetting Kode Akun';
					$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
					$isDisabled = 1;
				}
				elseif($ACC_ID == '' && $ITM_CATEG != 'UA')
				{
					$disBtn 	= 1;
					$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol2	= '</span>';
					$ttl 		= 'Belum disetting kode akun penerimaan';
					$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
					$isDisabled = 1;
				}

				$ItmCol0a	= '';
				$ItmCol1a	= '';
				$ItmCol2a	= '';
				$ttla 		= '';
				$divDesca 	= '';
				if($ADD_PRVOLM > 0)
				{
					$ItmCol0a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol1a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol2a	= '</span>';
					$ttla 		= $excRec;
					$divDesca 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;$excRec : $ADD_PRVOLM $ITM_UNIT";
				}

				$chkCopy 		= "$IR_NUM|$IR_CODE|$PO_NUM|$PRJCODE|$POD_ID|$ITM_CODE|$ACC_ID|$JOBCODEDET|$JOBCODEID|$JOBPARENT|$JOBPARDESC|$ITM_NAME|$ITM_CATEG|$ITM_QTY|$ITM_UNIT|$ITM_GROUP|$ITM_CATEG|$ITM_TYPE|$ITM_PRICE|$ITM_TOTAL|$ITM_DISP|$ITM_DISC|$TAXCODE1|$TAXPRICE1|$IR_AMOUNTNET|$ITM_QTY_BONUS|$ITM_DESC|$ISPRCREATE|$ADD_PRVOLM";

				if($ITM_CATEG == 'UA')
				{
					$ITMUNIT 	= "hidden";
						
					$ITMQTY 	= "hidden<input type='hidden' class='form-control' style='text-align:right; max-width:100px' name='ITM_QTYX".$noU."' id='ITM_QTYX".$noU."' value='".number_format($ITM_QTY, 2)."' onBlur='changeValue(this, ".$noU.")' onKeyPress='return isIntOnlyNew(event);' size='20' >";
				}
				else
				{
					$ITMUNIT 	= $ITM_UNIT;

					$ITMQTY 	= "<input type='text' class='form-control' style='text-align:right; max-width:100px' name='ITM_QTYX".$noU."' id='ITM_QTYX".$noU."' value='".number_format($ITM_QTY, 2)."' onBlur='changeValue(this, ".$noU.")' onKeyPress='return isIntOnlyNew(event);' size='20' >";
				}
				
				/*$sql_01 		= 	"INSERT INTO tbl_ir_detail_tmp
									(IR_ID, PRJCODE, DEPCODE, IR_NUM, IR_CODE, ACC_ID, IR_SOURCE, PR_NUM, PO_NUM, JOBCODEDET, JOBCODEID,
										JOBPARENT, JOBPARDESC, POD_ID, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_TYPE, ITM_GROUP,
										ITM_QTY_REM, ITM_QTY, ITM_PRICE, ITM_TOTAL, ITM_DISP, ITM_DISC, TAXCODE1, TAXCODE2,
										TAXPRICE1, TAXPRICE2, ITM_TOTAL_NETT, NOTES)
									VALUES
									($IR_ID, '$PRJCODE', '$DEPCODE', '$IR_NUM', '$IR_CODE', '$ACC_ID', 3, '$PR_NUM', '$PO_NUM', '$JOBCODEDET', '$JOBCODEID',
										'$JOBPARENT', '$JOBPARDESC', '$POD_ID', '$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', '$ITM_TYPE', '$ITM_GROUP',
										'$ITM_QTY_REM', '$ITM_QTY', '$ITM_PRICE', '$ITM_TOTAL', '$ITM_DISP', '$ITM_DISC', '$TAXCODE1', '$TAXCODE2',
										'$TAXPRICE1', '$TAXPRICE2', '$IR_AMOUNTNET', '$ITM_DESC')";
				$this->db->query($sql_01);*/

				$secDelROW 	= base_url().'index.php/c_inventory/c_ir180c15/delROW/?id=';
				$delROW 	= "$secDelROW~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE";
				$secCopy 	= base_url().'index.php/c_inventory/c_ir180c15/copyROW/?id=';
				$copyROW 	= "$secCopy~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE~$IR_ID";

				$output['data'][] 	= array("<label style='white-space:nowrap'>
												<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delROW."'>
												<input type='hidden' name='urlCopy".$noU."' id='urlCopy".$noU."' value='".$copyROW."'>
												<input type='hidden' name='chkCopy".$noU."' id='chkCopy".$noU."' value='".$chkCopy."'>
	                                            <a onClick='deleteRow(".$noU.")' title='Delete' class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a>
	                                            <a onClick='copyRow(".$noU.")' title='Copy' class='btn btn-warning btn-xs'><i class='glyphicon glyphicon-copy'></i></a>
		                                        </label>
		                                        <input type='Checkbox' id='data[".$noU."][chk]' name='data[".$noU."][chk]' value='".$noU."' onClick='pickThis(this,".$noU.")' style='display:none'>
												<input type='Checkbox' style='display:none' id='chk".$noU."' name='chk".$noU."' value=''>
												<input type='hidden' id='chk' name='chk' value='".$noU."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."IR_ID' name='data[".$noU."][IR_ID]' value='".$IR_ID."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."IR_NUM' name='data[".$noU."][IR_NUM]' value='".$IR_NUM."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."IR_CODE' name='data[".$noU."][IR_CODE]' value='".$IR_CODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."PO_NUM' name='data[".$noU."][PO_NUM]' value='".$PO_NUM."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."PRJCODE' name='data[".$noU."][PRJCODE]' value='".$PRJCODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>",
										  	"<label style='white-space:nowrap'>
											  		".$ITM_CODE." : ".$ITM_NAME."
			                                    	<div style='font-style: italic;'>
												  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;".$JOBPARDESC."
												  	</div>
												  	".$ItmCol1.$divDesc.$ItmCol2."
											  		".$ItmCol1a.$divDesca.$ItmCol2a."
										  		</label>
												<input type='hidden' class='form-control' name='itemname".$noU."' id='itemname".$noU."' value='".$ITM_NAME."' >
												
												<input type='hidden' id='data".$noU."POD_ID' name='data[".$noU."][POD_ID]' value='".$POD_ID."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."ITM_CODE' name='data[".$noU."][ITM_CODE]' value='".$ITM_CODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."ACC_ID' name='data[".$noU."][ACC_ID]' value='".$ACC_ID."' width='10' size='15' readonly class='form-control'>
												<input type='hidden' id='data".$noU."JOBCODEDET' name='data[".$noU."][JOBCODEDET]' value='".$JOBCODEDET."' width='10' size='15' readonly class='form-control'>
												<input type='hidden' id='data".$noU."JOBCODEID' name='data[".$noU."][JOBCODEID]' value='".$JOBCODEID."' width='10' size='15' readonly class='form-control'>
		                                        <input type='hidden' id='data".$noU."JOBPARENT' name='data[".$noU."][JOBPARENT]' value='".$JOBPARENT."' width='10' size='15'>
		                                        <input type='hidden' id='data".$noU."JOBPARDESC' name='data[".$noU."][JOBPARDESC]' value='".$JOBPARDESC."' width='10' size='15'>",
										  	"<input type='text' id='data".$noU."SJ_NUM' name='data[".$noU."][SJ_NUM]' value='".$SJ_NUM."' width='10' size='15' class='form-control' style='max-width:350px;' onChange='changeSJ(this.value, ".$IR_ID.", ".$noU.")'>",
										  	"$ITMQTY
												<input type='hidden' style='text-align:right' id='ITM_QTY_PO".$noU."' name='data[".$noU."][ITM_QTY_PO]' size='10' value='".$ITM_QTY_PO."' >
										  		<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_QTY]' id='ITM_QTY".$noU."' size='10' value='".$ITM_QTY."' >
												<input type='hidden' style='text-align:right' id='REMAINQTY".$noU."' size='10' value='".$ITM_QTY_REM."' >
												<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_QTY_REM]' id='ITM_QTY_REM".$noU."' size='10' value='".$ITM_QTY_REM."' >",
										  	"$ITMUNIT
										  		<input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_UNIT]' id='ITM_UNIT".$noU."' size='10' value='".$ITM_UNIT."' >
	                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_GROUP]' id='ITM_GROUP".$noU."' size='10' value='".$ITM_GROUP."' >
	                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' id='ITM_CATEG".$noU."' size='10' value='".$ITM_CATEG."' >
	                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_TYPE]' id='ITM_TYPE".$noU."' size='10' value='".$ITM_TYPE."' >",
										  	"hidden
												<input type='hidden' class='form-control' style='min-width:100px; max-width:350px; text-align:right;' name='ITM_PRICEX".$noU."' id='ITM_PRICEX".$noU."' value='".number_format($ITM_PRICE, 2)."' onBlur='changeValuePrc(this, ".$noU.")' disabled >
												<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_PRICE]' id='ITM_PRICE".$noU."' size='10' value='".$ITM_PRICE."' >
												<!-- Item Price -->
												<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_TOTAL]' id='ITM_TOTAL".$noU."' size='10' value='".$ITM_TOTAL."' >",
										  	"hidden
												<input type='hidden' class='form-control' size='10'  style=' min-width:65px; max-width:150px; text-align:right;' name='ITM_DISPx".$noU."' id='ITM_DISPx".$noU."' value='".number_format($ITM_DISP, 2)."' onBlur='countDisp(this, ".$noU.");' disabled >
												<input style='text-align:right' type='hidden' name='data[".$noU."][ITM_DISP]' id='ITM_DISP".$noU."' value='".$ITM_DISP."'>",
										  	"hidden
												<input type='text' class='form-control' style='min-width:100px; max-width:350px; text-align:right; display:none' name='ITM_DISCX".$noU."' id='ITM_DISCX".$noU."' value='".number_format($ITM_DISC, 2)."' onBlur='countDisc(this, ".$noU.");' disabled >
												<input style='text-align:right' type='hidden' name='data[".$noU."][ITM_DISC]' id='ITM_DISC".$noU."' value='".$ITM_DISC."'>",
										  	"hidden
	                                        	<input type='hidden' name='data[".$noU."][TAXCODE1]' id='TAXCODE1".$noU."' value='".$TAXCODE1."'>
												<input type='hidden' style='text-align:right' name='data[".$noU."][TAXPRICE1]' id='TAXPRICE1".$noU."' size='10' value='".$TAXPRICE1."' >",
										  	"hidden 
										  		<input type='hidden' class='form-control' style='min-width:100px; max-width:350px; text-align:right;' name='ITM_TOTAL_NETT".$noU."' id='ITM_TOTAL_NETT".$noU."' value='".number_format($IR_AMOUNTNET, 2)."' disabled >",
										  	"".$ITM_DESC."<input type='hidden' name='data[".$noU."][NOTES]' id='data".$noU."NOTES' value='".$ITM_DESC."' class='form-control' style='text-align:left'><input type='hidden' name='data[".$noU."][ISPRCREATE]' id='ISPRCREATE".$noU."' value='".$ISPRCREATE."' class='form-control'>
												<input type='hidden' name='data[".$noU."][ADD_PRVOLM]' id='ADD_PRVOLM".$noU."' value='".$ADD_PRVOLM."' class='form-control'>");
				

				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
									  "B",
									  "C",
									  "D",
									  "E",
									  "F",
									  "F",
									  "F",
									  "F",
									  "F");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataIRDetAftDel() 			// AFTER DELETE ROW
	{
		$PRJCODE		= $_GET['id'];
		$PO_NUM			= $_GET['PO_NUM'];
		$collDt 		= explode("~", $_GET['collDt']);
		$IR_NUM 		= $collDt[0];
		$IR_CODE 		= $collDt[1];
		
		$LangID 		= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alertAcc 	= "Belum diset kode akun penerimaan.";
		}
		else
		{
			$alertAcc 	= "Not set account receipt.";
		}
		
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
									"B.SPLDESC",
									"PO_NOTES");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_itemreceipt->get_AllDatatTmpIRC($PRJCODE, $PO_NUM, $IR_NUM, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_itemreceipt->get_AllDatatTmpIRL($PRJCODE, $PO_NUM, $IR_NUM, $search, $length, $start, $order, $dir);

			$IR_AMOUNT			= 0;
			$IR_DISC			= 0;
			$IR_PPN				= 0;
			$IR_AMOUNT_NETT		= 0;
			$TAXCODE_PPN 		= "";
			$TAXCODE_PPH		= "";
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$IR_NUM 		= $IR_NUM;
				$IR_CODE 		= $IR_CODE;
				$PO_NUM 		= $PO_NUM;
				$PRJCODE		= $PRJCODE;
				$IR_ID 			= $dataI['IR_ID'];
				$SJ_NUM			= $dataI['SJ_NUM'];
				$DEPCODE 		= $dataI['DEPCODE'];
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$PR_NUM 		= $dataI['PR_NUM'];
				$ACC_ID 		= $dataI['ACC_ID'];
				$ACC_ID_UM 		= $dataI['ACC_ID_UM'];
				$ITM_CODE 		= $dataI['ITM_CODE'];
				$ITM_UNIT 		= $dataI['ITM_UNIT'];
				$ITM_QTY_REM 	= $dataI['ITM_QTY_REM'];
				$ITM_QTY 		= $dataI['ITM_QTY'];
				$PO_VOLM 		= $dataI['PO_VOLM'];
				$ITM_QTY_PO 	= $dataI['PO_VOLM'];
				$POD_ID 		= $dataI['POD_ID'];
				$ITM_QTY_BONUS	= $dataI['ITM_QTY_BONUS'];
				$ITM_PRICE 		= $dataI['ITM_PRICE'];
				$ITM_TOTAL 		= $dataI['ITM_TOTAL'];
				$ITM_DISP 		= $dataI['ITM_DISP'];
				$JOBPARDESC		= $dataI['JOBPARDESC'];
				$JOBPARENT		= $dataI['JOBPARENT'];
				$ITM_DISC 		= $dataI['ITM_DISC'];
				$ITM_DESC 		= $dataI['NOTES'];
				$TAXCODE1		= $dataI['TAXCODE1'];
				$TAXCODE2		= $dataI['TAXCODE2'];
				$TAXPRICE1		= $dataI['TAXPRICE1'];
				$TAXPRICE2		= $dataI['TAXPRICE2'];
				$ISPRCREATE 	= $dataI['ISPRCREATE'];
				$ADD_PRVOLM 	= $dataI['ADD_PRVOLM'];
				$ITM_NAME 		= $dataI['ITM_NAME'];
				$ITM_GROUP 		= $dataI['ITM_GROUP'];
				$ITM_CATEG 		= $dataI['ITM_CATEG'];
				$ISMTRL 		= $dataI['ISMTRL'];
				$ISRENT 		= $dataI['ISRENT'];
				$ISPART 		= $dataI['ISPART'];
				$ISFUEL 		= $dataI['ISFUEL'];
				$ISLUBRIC 		= $dataI['ISLUBRIC'];
				$ISFASTM 		= $dataI['ISFASTM'];
				$ISWAGE 		= $dataI['ISWAGE'];
				if($ISMTRL == 1)
					$ITM_TYPE	= 1;
				elseif($ISRENT == 1)
					$ITM_TYPE	= 2;
				elseif($ISPART == 1)
					$ITM_TYPE	= 3;
				elseif($ISFUEL == 1)
					$ITM_TYPE	= 4;
				elseif($ISLUBRIC == 1)
					$ITM_TYPE	= 5;
				elseif($ISFASTM == 1)
					$ITM_TYPE	= 6;
				else
					$ITM_TYPE	= 1;

				//$IR_VOLM 		= $dataI['IR_VOLM'];
				//$IR_AMOUNT 	= $dataI['IR_AMOUNT'];
				$itemConvertion	= 1;

				if($JOBPARENT == '')
				{
					$sqlJDP 	= "SELECT A.JOBCODEID, A.JOBDESC FROM tbl_joblist_detail A
									WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
										WHERE B.JOBCODEID = '$JOBCODEID')";
					$resJDP 	= $this->db->query($sqlJDP)->result();
					foreach($resJDP as $rowJDP) :
						$JOBPARENT 	= $rowJDP->JOBCODEID;
						$JOBPARDESC = $rowJDP->JOBDESC;
					endforeach;
				}

				// GET TOTAL LPM AND REMAIN
					$s_01 	= "SELECT SUM(ITM_QTY) AS TOT_VOL FROM tbl_ir_detail_tmp
								WHERE ITM_CODE = '$ITM_CODE' AND IR_ID != $IR_ID AND IR_NUM = '$IR_NUM'";
					$r_01 	= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$TOT_VOL = $rw_01->TOT_VOL;
					endforeach;
					if($TOT_VOL == '')
						$TOT_VOL= 0;
				
				// TOTAL IR CONFIRMED AND APPROVED
					$TOT_IRQTY		= 0;
					$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY 
									FROM tbl_ir_detail A
										INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
									WHERE 
										B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
										AND A.IR_NUM != '$IR_NUM' AND IR_STAT IN (1,2,3)
										AND A.POD_ID = $POD_ID
										AND A.PO_NUM = '$PO_NUM'";
					$resQTY 	= $this->db->query($sqlQTY)->result();
					foreach($resQTY as $row1a) :
						$TOT_IRQTY 	= $row1a->TOT_IRQTY;
					endforeach;
					if($TOT_IRQTY == '')
						$TOT_IRQTY	= 0;

				$ITM_QTY_REM 	= $ITM_QTY_PO - $TOT_VOL - $TOT_IRQTY;
				
				$ITM_TOTAL 		= $ITM_QTY * $ITM_PRICE;

				if($TAXCODE1 != '')
					$TAXCODE_PPN	= $TAXCODE1;

				if($TAXCODE2 != '')
					$TAXCODE_PPH	= $TAXCODE2;

				$IR_AMOUNT 		= $IR_AMOUNT + $ITM_TOTAL;
				$IR_DISC 		= $IR_DISC + $ITM_DISC;					// TOTAL DISKON
				$IR_PPN 		= $IR_PPN + $TAXPRICE1;					// TOTAL PAJAK
				$IR_AMOUNTNET 	= $ITM_TOTAL - $ITM_DISC + $TAXPRICE1;
				$IR_AMOUNT_NETT	= $IR_AMOUNT_NETT + $IR_AMOUNTNET;

				$ItmCol1	= '';
				$ItmCol2	= '';
				$ttl 		= '';
				$divDesc 	= '';

				if($ITM_CATEG == 'UA' && $ACC_ID_UM == '')
				{
					$disBtn 	= 1;
					$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol2	= '</span>';
					$ttl 		= 'Item ongkos angkut ini belum disetting Kode Akun';
					$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
					$isDisabled = 1;
				}
				elseif($ACC_ID == '' && $ITM_CATEG != 'UA')
				{
					$disBtn 	= 1;
					$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol2	= '</span>';
					$ttl 		= 'Belum disetting kode akun penerimaan';
					$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
					$isDisabled = 1;
				}

				$ItmCol0a	= '';
				$ItmCol1a	= '';
				$ItmCol2a	= '';
				$ttla 		= '';
				$divDesca 	= '';
				if($ADD_PRVOLM > 0)
				{
					$ItmCol0a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol1a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
					$ItmCol2a	= '</span>';
					$ttla 		= $excRec;
					$divDesca 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;$excRec : $ADD_PRVOLM $ITM_UNIT";
				}

				$chkCopy 		= "$IR_NUM|$IR_CODE|$PO_NUM|$PRJCODE|$POD_ID|$ITM_CODE|$ACC_ID|$JOBCODEDET|$JOBCODEID|$JOBPARENT|$JOBPARDESC|$ITM_NAME|$ITM_CATEG|$ITM_QTY|$ITM_UNIT|$ITM_GROUP|$ITM_CATEG|$ITM_TYPE|$ITM_PRICE|$ITM_TOTAL|$ITM_DISP|$ITM_DISC|$TAXCODE1|$TAXPRICE1|$IR_AMOUNTNET|$ITM_QTY_BONUS|$ITM_DESC|$ISPRCREATE|$ADD_PRVOLM";

				if($ITM_CATEG == 'UA')
				{
					$ITMUNIT 	= "hidden";
						
					$ITMQTY 	= "hidden<input type='hidden' class='form-control' style='text-align:right; max-width:100px' name='ITM_QTYX".$noU."' id='ITM_QTYX".$noU."' value='".number_format($ITM_QTY, 2)."' onBlur='changeValue(this, ".$noU.")' onKeyPress='return isIntOnlyNew(event);' size='20' >";
				}
				else
				{
					$ITMUNIT 	= $ITM_UNIT;

					$ITMQTY 	= "<input type='text' class='form-control' style='text-align:right; max-width:100px' name='ITM_QTYX".$noU."' id='ITM_QTYX".$noU."' value='".number_format($ITM_QTY, 2)."' onBlur='changeValue(this, ".$noU.")' onKeyPress='return isIntOnlyNew(event);' size='20' >";
				}
				
				/*$sql_01 		= 	"INSERT INTO tbl_ir_detail_tmp
									(IR_ID, PRJCODE, DEPCODE, IR_NUM, IR_CODE, ACC_ID, IR_SOURCE, PR_NUM, PO_NUM, JOBCODEDET, JOBCODEID,
										JOBPARENT, JOBPARDESC, POD_ID, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_TYPE, ITM_GROUP,
										ITM_QTY_REM, ITM_QTY, ITM_PRICE, ITM_TOTAL, ITM_DISP, ITM_DISC, TAXCODE1, TAXCODE2,
										TAXPRICE1, TAXPRICE2, ITM_TOTAL_NETT, NOTES)
									VALUES
									($IR_ID, '$PRJCODE', '$DEPCODE', '$IR_NUM', '$IR_CODE', '$ACC_ID', 3, '$PR_NUM', '$PO_NUM', '$JOBCODEDET', '$JOBCODEID',
										'$JOBPARENT', '$JOBPARDESC', '$POD_ID', '$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', '$ITM_TYPE', '$ITM_GROUP',
										'$ITM_QTY_REM', '$ITM_QTY', '$ITM_PRICE', '$ITM_TOTAL', '$ITM_DISP', '$ITM_DISC', '$TAXCODE1', '$TAXCODE2',
										'$TAXPRICE1', '$TAXPRICE2', '$IR_AMOUNTNET', '$ITM_DESC')";
				$this->db->query($sql_01);*/

				$secDelROW 	= base_url().'index.php/c_inventory/c_ir180c15/delROW/?id=';
				$delROW 	= "$secDelROW~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE";
				$secCopy 	= base_url().'index.php/c_inventory/c_ir180c15/copyROW/?id=';
				$copyROW 	= "$secCopy~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE~$IR_ID";

				$output['data'][] 	= array("<label style='white-space:nowrap'>
												<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delROW."'>
												<input type='hidden' name='urlCopy".$noU."' id='urlCopy".$noU."' value='".$copyROW."'>
												<input type='hidden' name='chkCopy".$noU."' id='chkCopy".$noU."' value='".$chkCopy."'>
	                                            <a onClick='deleteRow(".$noU.")' title='Delete' class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a>
	                                            <a onClick='copyRow(".$noU.")' title='Copy' class='btn btn-warning btn-xs'><i class='glyphicon glyphicon-copy'></i></a>
		                                        </label>
		                                        <input type='Checkbox' id='data[".$noU."][chk]' name='data[".$noU."][chk]' value='".$noU."' onClick='pickThis(this,".$noU.")' style='display:none'>
												<input type='Checkbox' style='display:none' id='chk".$noU."' name='chk".$noU."' value=''>
												<input type='hidden' id='chk' name='chk' value='".$noU."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."IR_ID' name='data[".$noU."][IR_ID]' value='".$IR_ID."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."IR_NUM' name='data[".$noU."][IR_NUM]' value='".$IR_NUM."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."IR_CODE' name='data[".$noU."][IR_CODE]' value='".$IR_CODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."PO_NUM' name='data[".$noU."][PO_NUM]' value='".$PO_NUM."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."PRJCODE' name='data[".$noU."][PRJCODE]' value='".$PRJCODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>",
										  	"<label style='white-space:nowrap'>
											  		".$ITM_CODE." : ".$ITM_NAME."
			                                    	<div style='font-style: italic;'>
												  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;".$JOBPARDESC."
												  	</div>
												  	".$ItmCol1.$divDesc.$ItmCol2."
											  		".$ItmCol1a.$divDesca.$ItmCol2a."
										  		</label>
												<input type='hidden' class='form-control' name='itemname".$noU."' id='itemname".$noU."' value='".$ITM_NAME."' >
												
												<input type='hidden' id='data".$noU."POD_ID' name='data[".$noU."][POD_ID]' value='".$POD_ID."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."ITM_CODE' name='data[".$noU."][ITM_CODE]' value='".$ITM_CODE."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
												<input type='hidden' id='data".$noU."ACC_ID' name='data[".$noU."][ACC_ID]' value='".$ACC_ID."' width='10' size='15' readonly class='form-control'>
												<input type='hidden' id='data".$noU."JOBCODEDET' name='data[".$noU."][JOBCODEDET]' value='".$JOBCODEDET."' width='10' size='15' readonly class='form-control'>
												<input type='hidden' id='data".$noU."JOBCODEID' name='data[".$noU."][JOBCODEID]' value='".$JOBCODEID."' width='10' size='15' readonly class='form-control'>
		                                        <input type='hidden' id='data".$noU."JOBPARENT' name='data[".$noU."][JOBPARENT]' value='".$JOBPARENT."' width='10' size='15'>
		                                        <input type='hidden' id='data".$noU."JOBPARDESC' name='data[".$noU."][JOBPARDESC]' value='".$JOBPARDESC."' width='10' size='15'>",
										  	"<input type='text' id='data".$noU."SJ_NUM' name='data[".$noU."][SJ_NUM]' value='".$SJ_NUM."' width='10' size='15' class='form-control' style='max-width:350px;' onChange='changeSJ(this.value, ".$IR_ID.", ".$noU.")'>",
										  	"$ITMQTY
												<input type='hidden' style='text-align:right' id='ITM_QTY_PO".$noU."' name='data[".$noU."][ITM_QTY_PO]' size='10' value='".$ITM_QTY_PO."' >
										  		<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_QTY]' id='ITM_QTY".$noU."' size='10' value='".$ITM_QTY."' >
												<input type='hidden' style='text-align:right' id='REMAINQTY".$noU."' size='10' value='".$ITM_QTY_REM."' >
												<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_QTY_REM]' id='ITM_QTY_REM".$noU."' size='10' value='".$ITM_QTY_REM."' >",
										  	"$ITMUNIT
										  		<input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_UNIT]' id='ITM_UNIT".$noU."' size='10' value='".$ITM_UNIT."' >
	                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_GROUP]' id='ITM_GROUP".$noU."' size='10' value='".$ITM_GROUP."' >
	                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' id='ITM_CATEG".$noU."' size='10' value='".$ITM_CATEG."' >
	                                            <input type='hidden' class='form-control' style='max-width:350px; text-align:right' name='data[".$noU."][ITM_TYPE]' id='ITM_TYPE".$noU."' size='10' value='".$ITM_TYPE."' >",
										  	"hidden
												<input type='hidden' class='form-control' style='min-width:100px; max-width:350px; text-align:right;' name='ITM_PRICEX".$noU."' id='ITM_PRICEX".$noU."' value='".number_format($ITM_PRICE, 2)."' onBlur='changeValuePrc(this, ".$noU.")' disabled >
												<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_PRICE]' id='ITM_PRICE".$noU."' size='10' value='".$ITM_PRICE."' >
												<!-- Item Price -->
												<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_TOTAL]' id='ITM_TOTAL".$noU."' size='10' value='".$ITM_TOTAL."' >",
										  	"hidden
												<input type='hidden' class='form-control' size='10'  style=' min-width:65px; max-width:150px; text-align:right;' name='ITM_DISPx".$noU."' id='ITM_DISPx".$noU."' value='".number_format($ITM_DISP, 2)."' onBlur='countDisp(this, ".$noU.");' disabled >
												<input style='text-align:right' type='hidden' name='data[".$noU."][ITM_DISP]' id='ITM_DISP".$noU."' value='".$ITM_DISP."'>",
										  	"hidden
												<input type='text' class='form-control' style='min-width:100px; max-width:350px; text-align:right; display:none' name='ITM_DISCX".$noU."' id='ITM_DISCX".$noU."' value='".number_format($ITM_DISC, 2)."' onBlur='countDisc(this, ".$noU.");' disabled >
												<input style='text-align:right' type='hidden' name='data[".$noU."][ITM_DISC]' id='ITM_DISC".$noU."' value='".$ITM_DISC."'>",
										  	"hidden
	                                        	<input type='hidden' name='data[".$noU."][TAXCODE1]' id='TAXCODE1".$noU."' value='".$TAXCODE1."'>
												<input type='hidden' style='text-align:right' name='data[".$noU."][TAXPRICE1]' id='TAXPRICE1".$noU."' size='10' value='".$TAXPRICE1."' >",
										  	"hidden 
										  		<input type='hidden' class='form-control' style='min-width:100px; max-width:350px; text-align:right;' name='ITM_TOTAL_NETT".$noU."' id='ITM_TOTAL_NETT".$noU."' value='".number_format($IR_AMOUNTNET, 2)."' disabled >",
										  	"".$ITM_DESC."<input type='hidden' name='data[".$noU."][NOTES]' id='data".$noU."NOTES' value='".$ITM_DESC."' class='form-control' style='text-align:left'><input type='hidden' name='data[".$noU."][ISPRCREATE]' id='ISPRCREATE".$noU."' value='".$ISPRCREATE."' class='form-control'>
												<input type='hidden' name='data[".$noU."][ADD_PRVOLM]' id='ADD_PRVOLM".$noU."' value='".$ADD_PRVOLM."' class='form-control'>");
				

				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
									  "B",
									  "C",
									  "D",
									  "E",
									  "F",
									  "F",
									  "F",
									  "F",
									  "F");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function delROW()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $IR_NUM		= $colExpl[1];
        $IR_ID		= $colExpl[2];
        $POD_ID		= $colExpl[3];
        $ITM_CODE	= $colExpl[4];
        $ITM_NAME	= $colExpl[5];
        $PRJCODE	= $colExpl[6];

        $sqlDel		= "DELETE FROM tbl_ir_detail_tmp WHERE IR_NUM = '$IR_NUM' AND IR_ID = '$IR_ID' AND POD_ID = '$POD_ID'
        				AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
        $this->db->query($sqlDel);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Item $ITM_NAME telah dihapus.";
		}
		else
		{
			$alert1	= "Item $ITM_NAME has been deleted.";
		}
		echo "$alert1";
	}

	function copyROW()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $IR_NUM		= $colExpl[1];
        $IR_ID		= $colExpl[2];
        $POD_ID		= $colExpl[3];
        $ITM_CODE	= $colExpl[4];
        $ITM_NAME	= $colExpl[5];
        $PRJCODE	= $colExpl[6];
        $IR_ID		= $colExpl[7];

		$maxNo	= 0;
		$sqlMax = "SELECT MAX(IR_ID) AS maxNo FROM tbl_ir_detail_tmp WHERE IR_NUM = '$IR_NUM'";
		$resMax = $this->db->query($sqlMax)->result();
		foreach($resMax as $rowMax) :
			$maxNo = $rowMax->maxNo;
		endforeach;

        $s_01		= "SELECT A.PRJCODE, A.IR_ID, A.DEPCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
							A.ACC_ID, A.PR_NUM, A.PO_NUM, A.PO_CODE, A.ITM_CODE, A.ITM_UNIT,
							A.ITM_QTY_REM, A.ITM_QTY, A.ITM_QTY_PO AS PO_VOLM, A.POD_ID,
							A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, A.JOBPARENT, A.JOBPARDESC,
							A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
							A.ISPRCREATE, A.ADD_PRVOLM,
							B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG,
							B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
							B.ISFASTM, B.ISWAGE
						FROM tbl_ir_detail_tmp A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
						WHERE 
							A.IR_ID = $IR_ID AND A.IR_NUM = '$IR_NUM' 
							AND A.PRJCODE = '$PRJCODE' ORDER BY B.ITM_NAME";
		$r_01 		= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$maxNo 			= $maxNo+1;
			//$IR_ID 		= $rw_01->IR_ID;
			$IR_ID 			= $maxNo;
			$IR_CODE 		= $rw_01->IR_CODE;
			$PO_NUM 		= $rw_01->PO_NUM;
			$PO_CODE 		= $rw_01->PO_CODE;
			$DEPCODE 		= $rw_01->DEPCODE;
			$JOBCODEDET 	= $rw_01->JOBCODEDET;
			$JOBCODEID		= $rw_01->JOBCODEID;
			$PR_NUM 		= $rw_01->PR_NUM;
			$ACC_ID 		= $rw_01->ACC_ID;
			$ACC_ID_UM 		= $rw_01->ACC_ID_UM;
			$ITM_CODE 		= $rw_01->ITM_CODE;
			$ITM_UNIT 		= $rw_01->ITM_UNIT;
			$ITM_QTY_REM 	= $rw_01->ITM_QTY_REM;
			$ITM_QTY 		= $rw_01->ITM_QTY;
			$PO_VOLM 		= $rw_01->PO_VOLM;
			$ITM_QTY_PO 	= $rw_01->PO_VOLM;
			$POD_ID 		= $rw_01->POD_ID;
			$ITM_QTY_BONUS	= $rw_01->ITM_QTY_BONUS;
			$ITM_PRICE 		= $rw_01->ITM_PRICE;
			$ITM_TOTAL 		= $rw_01->ITM_TOTAL;
			$ITM_DISP 		= $rw_01->ITM_DISP;
			$JOBPARDESC		= $rw_01->JOBPARDESC;
			$JOBPARENT		= $rw_01->JOBPARENT;
			$ITM_DISC 		= $rw_01->ITM_DISC;
			$ITM_DESC 		= $rw_01->NOTES;
			$TAXCODE1		= $rw_01->TAXCODE1;
			$TAXCODE2		= $rw_01->TAXCODE2;
			$TAXPRICE1		= $rw_01->TAXPRICE1;
			$TAXPRICE2		= $rw_01->TAXPRICE2;
			$ISPRCREATE 	= $rw_01->ISPRCREATE;
			$ADD_PRVOLM 	= $rw_01->ADD_PRVOLM;
			$ITM_NAME 		= $rw_01->ITM_NAME;
			$ITM_GROUP 		= $rw_01->ITM_GROUP;
			$ITM_CATEG 		= $rw_01->ITM_CATEG;
			$ISMTRL 		= $rw_01->ISMTRL;
			$ISRENT 		= $rw_01->ISRENT;
			$ISPART 		= $rw_01->ISPART;
			$ISFUEL 		= $rw_01->ISFUEL;
			$ISLUBRIC 		= $rw_01->ISLUBRIC;
			$ISFASTM 		= $rw_01->ISFASTM;
			$ISWAGE 		= $rw_01->ISWAGE;
			if($ISMTRL == 1)
				$ITM_TYPE	= 1;
			elseif($ISRENT == 1)
				$ITM_TYPE	= 2;
			elseif($ISPART == 1)
				$ITM_TYPE	= 3;
			elseif($ISFUEL == 1)
				$ITM_TYPE	= 4;
			elseif($ISLUBRIC == 1)
				$ITM_TYPE	= 5;
			elseif($ISFASTM == 1)
				$ITM_TYPE	= 6;
			else
				$ITM_TYPE	= 1;
		endforeach;
		//$ITM_QTY_REM2 = $ITM_QTY_REM - $ITM_QTY;

		// GET TOTAL LPM AND REMAIN
			$s_01 	= "SELECT SUM(ITM_QTY) AS TOT_VOL FROM tbl_ir_detail_tmp
						WHERE ITM_CODE = '$ITM_CODE' AND IR_ID != $IR_ID AND IR_NUM = '$IR_NUM'";
			$r_01 	= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$TOT_VOL = $rw_01->TOT_VOL;
			endforeach;
			if($TOT_VOL == '')
				$TOT_VOL= 0;
		
		// TOTAL IR CONFIRMED AND APPROVED
			$TOT_IRQTY		= 0;
			$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY 
							FROM tbl_ir_detail A
								INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
							WHERE 
								B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
								AND A.IR_NUM != '$IR_NUM' AND IR_STAT IN (1,2,3)
								AND A.POD_ID = $POD_ID
								AND A.PO_NUM = '$PO_NUM'";
			$resQTY 	= $this->db->query($sqlQTY)->result();
			foreach($resQTY as $row1a) :
				$TOT_IRQTY 	= $row1a->TOT_IRQTY;
			endforeach;
			if($TOT_IRQTY == '')
				$TOT_IRQTY	= 0;

		$ITM_QTY_REM 	= $ITM_QTY_PO - $TOT_VOL - $TOT_IRQTY;
		
		$ITM_TOTAL 		= $ITM_QTY * $ITM_PRICE;

		$sqlJDP 		= "tbl_ir_detail WHERE ITM_CODE = '$ITM_CODE' AND IR_ID = $IR_ID AND PRJCODE = '$PRJCODE'";
		$resJDP 		= $this->db->count_all($sqlJDP);

		// JIKA SUDAH ADA DI IR_DETAIL (TERSIMPAN), LEWATI
		if($resJDP == 0)
		{
    		$sql_01 	= 	"INSERT INTO tbl_ir_detail_tmp
							(IR_ID, PRJCODE, DEPCODE, IR_NUM, IR_CODE, ACC_ID, IR_SOURCE, PR_NUM, PO_NUM, PO_CODE, JOBCODEDET, JOBCODEID,
								JOBPARENT, JOBPARDESC, POD_ID, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_TYPE, ITM_GROUP,
								ITM_QTY_PO, ITM_QTY_REM, ITM_QTY, ITM_PRICE, ITM_TOTAL, ITM_DISP, ITM_DISC, TAXCODE1, TAXCODE2,
								TAXPRICE1, TAXPRICE2, ITM_TOTAL_NETT, NOTES)
							VALUES
							($IR_ID, '$PRJCODE', '$DEPCODE', '$IR_NUM', '$IR_CODE', '$ACC_ID', 3, '$PR_NUM', '$PO_NUM', '$PO_CODE', '$JOBCODEDET', '$JOBCODEID',
								'$JOBPARENT', '$JOBPARDESC', '$POD_ID', '$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', '$ITM_TYPE', '$ITM_GROUP',
								'$ITM_QTY_PO', '$ITM_QTY_REM', '$ITM_QTY_REM', '$ITM_PRICE', '$ITM_TOTAL', '$ITM_DISP', '$ITM_DISC', '$TAXCODE1', '$TAXCODE2',
								'$TAXPRICE1', '$TAXPRICE2', '$IR_AMOUNTNET', '$ITM_DESC')";
			$this->db->query($sql_01);
		}

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Item $ITM_NAME telah ditambahkan.";
		}
		else
		{
			$alert1	= "Item $ITM_NAME has been added.";
		}
		echo "$alert1";
	}

	function getPO()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collDt 	= explode("~", $_POST['collDt']);
		$IR_NUM 	= $collDt[0];
		$IR_CODE 	= $collDt[1];
		$PO_NUM 	= $collDt[2];

        $PO_CODE	= '';
		$PR_NUM		= '';
		$SPLCODE	= '';
		$TERM_PAY	= 0;
		$s_01		= "SELECT PRJCODE, PO_CODE, PR_NUM, SPLCODE, PO_TENOR FROM tbl_po_header WHERE PO_NUM = '$PO_NUM'";
		$r_01 	= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$PRJCODE	= $rw_01->PRJCODE;
			$PO_CODE	= $rw_01->PO_CODE;
			$PR_NUM		= $rw_01->PR_NUM;
			$SPLCODE	= $rw_01->SPLCODE;
			$TERM_PAY	= $rw_01->PO_TENOR;
		endforeach;

		$maxNo	= 0;
		$sqlMax = "SELECT MAX(IR_ID) AS maxNo FROM tbl_ir_detail WHERE IR_NUM = '$IR_NUM'";
		$resMax = $this->db->query($sqlMax)->result();
		foreach($resMax as $rowMax) :
			$maxNo = $rowMax->maxNo;
		endforeach;

		$IR_AMOUNT			= 0;
		$IR_DISC			= 0;
		$IR_PPN				= 0;
		$IR_AMOUNT_NETT		= 0;
		$TAXCODE_PPN 		= "";
		$TAXCODE_PPH		= "";
		$TOTROW 			= 0;
		$s_02 	= "SELECT A.DEPCODE, A.PO_NUM, A.PO_CODE, A.PRD_ID, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, 
					A.ITM_UNIT, A.PO_VOLM AS ITM_QTY, A.PO_VOLM AS ITM_QTY_REM,
					A.PO_VOLM AS PO_VOLM, 0 AS ITM_QTY_BONUS, A.PO_PRICE AS ITM_PRICE, 
					A.PO_DISP AS ITM_DISP, A.PO_DISC AS ITM_DISC, A.JOBPARENT, A.JOBPARDESC,
					A.PO_COST AS ITM_TOTAL, A.PO_DESC AS NOTES, A.IR_VOLM, A.IR_AMOUNT,
					A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2, A.PO_ID AS POD_ID,
					B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG,
					B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
					B.ISFASTM, B.ISWAGE, 0 AS ISPRCREATE, 0 AS ADD_PRVOLM
				FROM tbl_po_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
				WHERE PO_NUM = '$PO_NUM' 
					AND B.PRJCODE = '$PRJCODE' AND (A.PO_VOLM - A.IR_VOLM) > 0 ORDER BY B.ITM_NAME";
		$r_02 	= $this->db->query($s_02)->result();
		foreach($r_02 as $rw_02):
			$maxNo			= $maxNo + 1;
			$IR_ID			= $maxNo;
			$DEPCODE 		= $rw_02->DEPCODE;
			$JOBCODEDET 	= $rw_02->JOBCODEDET;
			$JOBCODEID		= $rw_02->JOBCODEID;
			$PRD_ID 		= $rw_02->PRD_ID;
			$PR_NUM 		= $rw_02->PR_NUM;
			$ACC_ID 		= $rw_02->ACC_ID;
			$ACC_ID_UM 		= $rw_02->ACC_ID_UM;
			$ITM_CODE 		= $rw_02->ITM_CODE;
			$ITM_UNIT 		= $rw_02->ITM_UNIT;
			$ITM_QTY_REM 	= $rw_02->ITM_QTY_REM;
			$ITM_QTY 		= $rw_02->ITM_QTY;
			$PO_VOLM 		= $rw_02->PO_VOLM;
			$POD_ID 		= $rw_02->POD_ID;
			$ITM_QTY_BONUS	= $rw_02->ITM_QTY_BONUS;
			$ITM_PRICE 		= $rw_02->ITM_PRICE;
			$ITM_TOTAL 		= $rw_02->ITM_TOTAL;
			$ITM_DISP 		= $rw_02->ITM_DISP;
			$JOBPARDESC		= $rw_02->JOBPARDESC;
			$JOBPARENT		= $rw_02->JOBPARENT;
			$ITM_DISC 		= $rw_02->ITM_DISC;
			$ITM_DESC 		= $rw_02->NOTES;
			$TAXCODE1		= $rw_02->TAXCODE1;
			$TAXCODE2		= $rw_02->TAXCODE2;
			$TAXPRICE1		= $rw_02->TAXPRICE1;
			$TAXPRICE2		= $rw_02->TAXPRICE2;
			$ISPRCREATE 	= $rw_02->ISPRCREATE;
			$ADD_PRVOLM 	= $rw_02->ADD_PRVOLM;
			$ITM_NAME 		= $rw_02->ITM_NAME;
			$ITM_GROUP 		= $rw_02->ITM_GROUP;
			$ITM_CATEG 		= $rw_02->ITM_CATEG;
			$ISMTRL 		= $rw_02->ISMTRL;
			$ISRENT 		= $rw_02->ISRENT;
			$ISPART 		= $rw_02->ISPART;
			$ISFUEL 		= $rw_02->ISFUEL;
			$ISLUBRIC 		= $rw_02->ISLUBRIC;
			$ISFASTM 		= $rw_02->ISFASTM;
			$ISWAGE 		= $rw_02->ISWAGE;
			if($ISMTRL == 1)
				$ITM_TYPE	= 1;
			elseif($ISRENT == 1)
				$ITM_TYPE	= 2;
			elseif($ISPART == 1)
				$ITM_TYPE	= 3;
			elseif($ISFUEL == 1)
				$ITM_TYPE	= 4;
			elseif($ISLUBRIC == 1)
				$ITM_TYPE	= 5;
			elseif($ISFASTM == 1)
				$ITM_TYPE	= 6;
			else
				$ITM_TYPE	= 1;

			$itemConvertion	= 1;

			if($JOBPARENT == '')
			{
				$sqlJDP 	= "SELECT A.JOBCODEID, A.JOBDESC FROM tbl_joblist_detail A
								WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
									WHERE B.JOBCODEID = '$JOBCODEID')";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARENT 	= $rowJDP->JOBCODEID;
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
			}

			// GET TOTAL LPM AND REMAIN
				$s_01 	= "SELECT SUM(ITM_QTY) AS TOT_VOL FROM tbl_ir_detail_tmp
							WHERE ITM_CODE = '$ITM_CODE' AND IR_ID != $maxNo AND IR_NUM = '$IR_NUM'";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01):
					$TOT_VOL = $rw_01->TOT_VOL;
				endforeach;
				if($TOT_VOL == '')
					$TOT_VOL= 0;
			
			// TOTAL IR CONFIRMED AND APPROVED
				$TOT_IRQTY		= 0;
				$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY 
								FROM tbl_ir_detail A
									INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
								WHERE 
									B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
									AND A.IR_NUM != '$IR_NUM' AND IR_STAT IN (1,2,3)
									AND A.POD_ID = $POD_ID
									AND A.PO_NUM = '$PO_NUM'";
				$resQTY 	= $this->db->query($sqlQTY)->result();
				foreach($resQTY as $row1a) :
					$TOT_IRQTY 	= $row1a->TOT_IRQTY;
				endforeach;
				if($TOT_IRQTY == '')
					$TOT_IRQTY	= 0;

			$ITM_QTY_REM 	= $PO_VOLM - $TOT_VOL - $TOT_IRQTY;
			
			$ITM_TOTAL 		= $ITM_QTY * $ITM_PRICE;

			if($TAXCODE1 != '')
				$TAXCODE_PPN	= $TAXCODE1;

			if($TAXCODE2 != '')
				$TAXCODE_PPH	= $TAXCODE2;

			$IR_AMOUNT 		= $IR_AMOUNT + $ITM_TOTAL;
			$IR_DISC 		= $IR_DISC + $ITM_DISC;					// TOTAL DISKON
			$IR_PPN 		= $IR_PPN + $TAXPRICE1;					// TOTAL PAJAK
			$IR_AMOUNTNET 	= $ITM_TOTAL - $ITM_DISC + $TAXPRICE1;
			$IR_AMOUNT_NETT	= $IR_AMOUNT_NETT + $IR_AMOUNTNET;

			if($ITM_QTY_REM > 0)
			{
				$TOTROW 	= $TOTROW+1;
				$sql_01 	= 	"INSERT INTO tbl_ir_detail_tmp
								(IR_ID, PRJCODE, DEPCODE, IR_NUM, IR_CODE, ACC_ID, IR_SOURCE, PR_NUM, PO_NUM, PO_CODE,
									JOBCODEDET, JOBCODEID, JOBPARENT, JOBPARDESC,
									PRD_ID, POD_ID, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_TYPE, ITM_GROUP,
									ITM_QTY_PO, ITM_QTY_REM, ITM_QTY, ITM_PRICE, ITM_TOTAL, ITM_DISP, ITM_DISC, TAXCODE1, TAXCODE2,
									TAXPRICE1, TAXPRICE2, ITM_TOTAL_NETT, NOTES)
								VALUES
								($IR_ID, '$PRJCODE', '$DEPCODE', '$IR_NUM', '$IR_CODE', '$ACC_ID', 3, '$PR_NUM', '$PO_NUM', '$PO_CODE',
									'$JOBCODEDET','$JOBCODEID', '$JOBPARENT', '$JOBPARDESC',
									'$PRD_ID', '$POD_ID', '$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', '$ITM_TYPE', '$ITM_GROUP',
									'$PO_VOLM', '$ITM_QTY_REM', '$ITM_QTY', '$ITM_PRICE', '$ITM_TOTAL', '$ITM_DISP', '$ITM_DISC', '$TAXCODE1', '$TAXCODE2',
									'$TAXPRICE1', '$TAXPRICE2', '$IR_AMOUNTNET', '$ITM_DESC')";
				$this->db->query($sql_01);
			}
		endforeach;
		$totRow 	= $TOTROW;

		echo "$PO_CODE~$PR_NUM~$SPLCODE~$TERM_PAY~$totRow";
	}

	function getTRowC()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$IR_NUM 	= $_POST['IR_NUM'];

		$s_01		= "tbl_ir_detail_tmp WHERE IR_NUM = '$IR_NUM'";
		$r_01 		= $this->db->count_all($s_01);

		echo "$r_01";
	}

	function getTRowE()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$IR_NUM 	= $_POST['IR_NUM'];

		$s_01		= "tbl_ir_detail WHERE IR_NUM = '$IR_NUM'";
		$r_01 		= $this->db->count_all($s_01);

		echo "$r_01";
	}

	function getTRowAll()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$IR_NUM 	= $_POST['IR_NUM'];

		$s_01		= "tbl_ir_detail_tmp WHERE IR_NUM = '$IR_NUM'";
		$r_01 		= $this->db->count_all($s_01);

		echo "$r_01";
	}

	function updSJ()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= explode("~", $_POST['collID']);
		$IR_ID 		= $collID[0];
		$IR_NUM 	= $collID[1];
		$SJ_NUM 	= $collID[2];
		$IR_VOL 	= $collID[3];
		$ITM_CODE 	= $collID[4];
		$ITM_REM 	= $collID[5];

		// GET TOTAL VOLUME
			/*$TOT_VOL= 0;
			$s_01 	= "SELECT SUM(ITM_QTY) AS TOT_VOL FROM tbl_ir_detail_tmp
						WHERE ITM_CODE = '$ITM_CODE' AND IR_NUM = '$IR_NUM'";
			$r_01 	= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$TOT_VOL = $rw_01->TOT_VOL;
			endforeach;
			$QTYREM = $ITM_REM - $TOT_VOL;*/

		$s_02		= "UPDATE tbl_ir_detail_tmp SET SJ_NUM = '$SJ_NUM' WHERE IR_NUM = '$IR_NUM' AND IR_ID = $IR_ID";
		$this->db->query($s_02);

		$s_03		= "UPDATE tbl_ir_detail SET SJ_NUM = '$SJ_NUM' WHERE IR_NUM = '$IR_NUM' AND IR_ID = $IR_ID";
		$this->db->query($s_03);

		echo "$s_02";
	}

	function updVOL()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= explode("~", $_POST['collREM']);
		$IR_ID 		= $collID[0];
		$IR_NUM 	= $collID[1];
		$IR_VOL 	= $collID[2];
		$ITM_CODE 	= $collID[3];
		$ITM_REM 	= $collID[4];
		$ITM_PO 	= $collID[5];

		$s_01		= "UPDATE tbl_ir_detail_tmp SET ITM_QTY = '$IR_VOL' WHERE IR_NUM = '$IR_NUM' AND IR_ID = $IR_ID";
		$this->db->query($s_01);

		$s_01		= "UPDATE tbl_ir_detail SET ITM_QTY = '$IR_VOL' WHERE IR_NUM = '$IR_NUM' AND IR_ID = $IR_ID";
		$this->db->query($s_01);

		// GET TOTAL VOLUME
			$PO_VOL = $ITM_PO;
			$TOT_VOL= 0;
			/*$s_01 	= "SELECT SUM(ITM_QTY) AS TOT_VOL FROM tbl_ir_detail_tmp
						WHERE ITM_CODE = '$ITM_CODE' AND IR_NUM = '$IR_NUM'";*/
			$s_01 	= "SELECT SUM(ITM_QTY) AS TOT_VOL FROM tbl_ir_detail_tmp
						WHERE ITM_CODE = '$ITM_CODE' AND IR_ID != $IR_ID AND IR_NUM = '$IR_NUM'";
			$r_01 	= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$TOT_VOL = $rw_01->TOT_VOL;
			endforeach;
			if($TOT_VOL == '')
				$TOT_VOL= 0;
			//$QTYREM 	= $ITM_REM - $TOT_VOL;
			$QTYREM 	= $PO_VOL - $TOT_VOL;

		//$s_02		= "UPDATE tbl_ir_detail_tmp SET ITM_QTY_REM = $QTYREM WHERE IR_NUM = '$IR_NUM' AND IR_ID = $IR_ID";
		$s_02		= "UPDATE tbl_ir_detail_tmp SET ITM_QTY_REM = $QTYREM WHERE IR_NUM = '$IR_NUM'";
		$this->db->query($s_02);

		//$s_02		= "UPDATE tbl_ir_detail SET ITM_QTY_REM = $QTYREM WHERE IR_NUM = '$IR_NUM' AND IR_ID = $IR_ID";
		$s_02		= "UPDATE tbl_ir_detail SET ITM_QTY_REM = $QTYREM WHERE IR_NUM = '$IR_NUM'";
		$this->db->query($s_02);

		echo "$IR_ID~$IR_NUM~$QTYREM";
	}
}