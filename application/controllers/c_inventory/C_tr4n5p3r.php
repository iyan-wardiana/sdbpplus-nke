<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Januari 2019
 * File Name	= C_tr4n5p3r.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_tr4n5p3r extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
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
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_tr4n5p3r/prjl180c15/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN228';
				$data["MenuApp"] 	= 'MN229';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN228';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_inventory/c_tr4n5p3r/tr4n5p3r_i4x/?id=";
			
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
	
	function tr4n5p3r_i4x()
	{
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN228';
			$data["MenuApp"] 	= 'MN229';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
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
				$data["url_search"] = site_url('c_inventory/c_tr4n5p3r/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_transfer->count_all_tsf($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_transfer->get_all_tsf($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
					
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Pemindahan Material";
				$data["h3_title"] 	= "Pemindahan Material";
			}
			else
			{
				$data["h2_title"] 	= "Material Transfer";
				$data["h3_title"] 	= "Material Transfer";
			}
			
			$linkBack			= site_url('c_inventory/c_tr4n5p3r/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['addURL'] 	= site_url('c_inventory/c_tr4n5p3r/a44_1stF0rM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['backURL'] 	= $linkBack;
			$data['PRJCODE']	= $PRJCODE;			
	 		$data["MenuCode"] 	= 'MN228';
			
			$this->load->view('v_inventory/v_itemtransfer/item_transfer', $data);
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
			$url			= site_url('c_inventory/c_tr4n5p3r/tr4n5p3r_i4x/?id='.$this->url_encryption_helper->encode_url($collDATA));
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

			$columns_valid 	= array("ITMTSF_NUM",
									"ITMTSF_CODE",
									"ITMTSF_DATE",
									"ITMTSF_DEST",
									"ITMTSF_NOTE",
									"STATDESC",
									"CREATERNM");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_transfer->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_transfer->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$ITMTSF_NUM		= $dataI['ITMTSF_NUM'];
				$ITMTSF_CODE	= $dataI['ITMTSF_CODE'];
				
				$ITMTSF_DATE	= $dataI['ITMTSF_DATE'];
				$ITMTSF_DATEV	= date('d M Y', strtotime($ITMTSF_DATE));
				
				$PRJCODE		= $dataI['PRJCODE'];
				$ITMTSF_ORIGIN	= $dataI['ITMTSF_ORIGIN'];
				$ITMTSF_DEST	= $dataI['ITMTSF_DEST'];
				$ITMTSF_REFNO	= $dataI['ITMTSF_REFNO'];
				$ITMTSF_NOTE	= $dataI['ITMTSF_NOTE'];
				$JO_CODE		= $dataI['JO_CODE'];
				$ITMTSF_STAT 	= $dataI['ITMTSF_STAT'];
				
				$TSFFROM    = '-';
                $sqlWH      = "SELECT WH_NAME FROM tbl_warehouse WHERE WH_CODE = '$ITMTSF_ORIGIN' LIMIT 1";
                $resWH      = $this->db->query($sqlWH)->result();
                foreach($resWH as $rowWH) :
                    $TSFFROM    = $rowWH->WH_NAME;
                endforeach;

                $TSFDEST    = '-';
                $sqlWH      = "SELECT WH_NAME FROM tbl_warehouse WHERE WH_CODE = '$ITMTSF_DEST' LIMIT 1";
                $resWH      = $this->db->query($sqlWH)->result();
                foreach($resWH as $rowWH) :
                    $TSFDEST    = $rowWH->WH_NAME;
                endforeach;
				
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$PRJCODE~$ITMTSF_NUM";
				$secUpd		= site_url('c_inventory/c_tr4n5p3r/update/?id='.$this->url_encryption_helper->encode_url($CollID));
                $secPrint	= site_url('c_inventory/c_tr4n5p3r/printdocument/?id='.$this->url_encryption_helper->encode_url($ITMTSF_NUM));
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secVoid 	= base_url().'index.php/__l1y/trashMTSF/?id=';
				$voidID 	= "$secVoid~tbl_item_tsfh~tbl_item_tsfd~ITMTSF_NUM~$ITMTSF_NUM~PRJCODE~$PRJCODE";
                                    
				if($ITMTSF_STAT == 1 || $ITMTSF_STAT == 4)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   		<label style='white-space:nowrap'>
								   		<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
								   		</a>
										<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
											<i class='glyphicon glyphicon-off'></i>
										</a>
								   		<a href='javascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printDocument(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
									</label>";
				}
				elseif($ITMTSF_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
							   		<label style='white-space:nowrap'>
								   		<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
								   		</a>
										<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void'>
											<i class='glyphicon glyphicon-off'></i>
										</a>
								   		<a href='javascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printDocument(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   		<label style='white-space:nowrap'>
								   		<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
								   		</a>
										<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
											<i class='glyphicon glyphicon-off'></i>
										</a>
								   		<a href='javascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printDocument(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
									</label>";
				}

				$output['data'][] = array("$noU.",
										  $ITMTSF_CODE,
										  $ITMTSF_DATEV,
										  $JO_CODE,
										  "$TSFFROM - $TSFDEST",
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);

				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_1stF0rM() // G
	{
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
		// GET MENU DESC
			$mnCode				= 'MN228';
			$data["MenuApp"] 	= 'MN229';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
			
			$docPatternPosition		= 'Especially';
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_inventory/c_tr4n5p3r/add_process');
			$linkBack				= site_url('c_inventory/c_tr4n5p3r/tr4n5p3r_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['backURL'] 		= $linkBack;
			$data['PRJCODE']		= $PRJCODE;	
			$MenuCode 				= 'MN228';
			$data["MenuCode"] 		= 'MN228';
			$data['vwDocPatt'] 		= $this->m_transfer->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_inventory/v_itemtransfer/item_transfer_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l_MR3q_x1() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Daftar JO";
			}
			else
			{
				$data["h1_title"] 	= "Job Order List";
			}
			
			$data['PRJCODE'] 		= $PRJCODE;			
			$data['cAllMTR']		= $this->m_transfer->count_all_MTR($PRJCODE);
			$data['vAllMTR'] 		= $this->m_transfer->view_all_MTR($PRJCODE)->result();
					
			$this->load->view('v_inventory/v_itemtransfer/item_transfer_selmr', $data);
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
			$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
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
			
			$MRNM		= $_GET['MRNM'];
			$ORIG		= $_GET['ORIG'];
			
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
			$data['ORIG'] 			= $ORIG;
			
			// FROM MR DETAIL
			$data['cItmPrm']		= $this->m_transfer->count_all_prim($PRJCODE, $MRNM);
			$data['vItmPrm'] 		= $this->m_transfer->view_all_prim($PRJCODE, $MRNM)->result();
			$data['cItmOth'] 		= $this->m_transfer->count_allItemOth($PRJCODE, $MRNM);
			$data['vItmOth'] 		= $this->m_transfer->viewAllItemOth($PRJCODE, $MRNM)->result();
			
			
			/*$data['cAllItem']		= $this->m_transfer->count_all_prim($PRJCODE, $CCALNUM);
			$data['vwAllItem'] 		= $this->m_transfer->view_all_prim($PRJCODE, $CCALNUM)->result();*/
			
			//$data['cAllItemSubs']	= $this->m_matreq->count_all_subs($PRJCODE, $JOBCODE);
			//$data['vwAllItemSubs'] 	= $this->m_matreq->view_all_subs($PRJCODE, $JOBCODE)->result();
					
			$this->load->view('v_inventory/v_itemtransfer/item_transfer_selitm', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$completeName 	= $this->session->userdata['completeName'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN228';
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
			$ITMTSF_TYPE	= 'WH';										// HARDCODE DULU
			$ITMTSF_ORIGIN 	= $this->input->post('ITMTSF_ORIGIN');
			$PRJCODE_DEST 	= $this->input->post('PRJCODE_DEST');
			$ITMTSF_DEST 	= $this->input->post('ITMTSF_DEST');
			//$JOBCODEID 	= $this->input->post('ITMTSF_REFNO');
			$JOBCODEID 		= '';
			$ITMTSF_REFNO 	= $this->input->post('ITMTSF_REFNO');
			$ITMTSF_REFNO1 	= $this->input->post('ITMTSF_REFNO1');
			$ITMTSF_NOTE	= addslashes($this->input->post('ITMTSF_NOTE'));
			$ITMTSF_NOTE2	= addslashes($this->input->post('ITMTSF_NOTE2'));
			$ITMTSF_REVMEMO	= $this->input->post('ITMTSF_REVMEMO');
			$ITMTSF_SENDER 	= $this->input->post('ITMTSF_SENDER');
			$ITMTSF_RECEIVER= $this->input->post('ITMTSF_RECEIVER');
			$ITMTSF_STAT 	= $this->input->post('ITMTSF_STAT');
			
			$ITMTSF_CREATER	= $DefEmp_ID;
			$ITMTSF_CREATED = date('Y-m-d H:i:s');	
			
			$insITMTSFH		= array('ITMTSF_NUM' 		=> $ITMTSF_NUM,
									'ITMTSF_CODE'		=> $ITMTSF_CODE,
									'ITMTSF_DATE'		=> $ITMTSF_DATE,
									'ITMTSF_SENDD'		=> $ITMTSF_SENDD,
									'ITMTSF_RECD'		=> $ITMTSF_RECD,
									'PRJCODE'			=> $PRJCODE,
									'ITMTSF_TYPE'		=> $ITMTSF_TYPE,
									'ITMTSF_ORIGIN'		=> $ITMTSF_ORIGIN,
									'PRJCODE_DEST'		=> $PRJCODE_DEST,
									'ITMTSF_DEST'		=> $ITMTSF_DEST,
									'JOBCODEID'			=> $JOBCODEID,
									'ITMTSF_REFNO'		=> $ITMTSF_REFNO,
									'ITMTSF_REFNO1'		=> $ITMTSF_REFNO1,
									'ITMTSF_NOTE'		=> $ITMTSF_NOTE,
									'ITMTSF_SENDER'		=> $ITMTSF_SENDER,
									'ITMTSF_RECEIVER'	=> $ITMTSF_RECEIVER,
									'ITMTSF_STAT'		=> $ITMTSF_STAT,
									'ITMTSF_CREATER'	=> $ITMTSF_CREATER,
									'ITMTSF_CREATED'	=> $ITMTSF_CREATED,
									'Patt_Year'			=> $Patt_Year,
									'Patt_Month'		=> $Patt_Month,
									'Patt_Date'			=> $Patt_Date,
									'Patt_Number'		=> $this->input->post('Patt_Number'));
			$this->m_transfer->add($insITMTSFH);
			
			$ITMTSF_AMOUNT			= 0;
			foreach($_POST['data'] as $d)
			{
				$d['ITMTSF_NUM']	= $ITMTSF_NUM;
				$d['ITMTSF_CODE']	= $ITMTSF_CODE;
				$d['ITMTSF_DATE']	= $ITMTSF_DATE;
				$d['PRJCODE']		= $PRJCODE;
				$d['ITMTSF_TYPE']	= 'WH';
				$d['ITMTSF_ORIGIN']	= $ITMTSF_ORIGIN;
				$d['PRJCODE_DEST']	= $PRJCODE_DEST;
				$d['ITMTSF_DEST']	= $ITMTSF_DEST;
				$d['ITMTSF_REFNO']	= $ITMTSF_REFNO;
				$d['ITMTSF_REFNO1']	= $ITMTSF_REFNO1;
				$ITMTSF_VOLM		= $d['ITMTSF_VOLM'];
				$ITMTSF_PRICE		= $d['ITMTSF_PRICE'];
				$ITMTSF_AMOUNTX		= $ITMTSF_VOLM * $ITMTSF_PRICE;
				$ITMTSF_AMOUNT		= $ITMTSF_AMOUNT + $ITMTSF_AMOUNTX;
				$this->db->insert('tbl_item_tsfd',$d);
				
				/* HOLDED ON 28 JAN 2019
				if($ITMTSF_STAT == 3 && $AH_ISLAST == 1)
				{
					// UPDATE LAST POSITION
					$AS_CODE	= $d['AS_CODE'];
					$updLASTPOS	= array('AS_LASTPOS' 	=> $PRJCODE);
					$this->m_transfer->updateLP($AS_CODE, $updLASTPOS);
				}*/
			}
			
			// UPDATE HEADER
				// $this->m_transfer->updateDet($ITMTSF_NUM, $PRJCODE, $ITMTSF_DATE);	// HOLDED ON 28 JAN 2019
				$updITMTSFH	= array('ITMTSF_AMOUNT'	=> $ITMTSF_AMOUNT);
				$this->m_transfer->update($ITMTSF_NUM, $updITMTSFH);
				
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "ITMTSF_NUM",
										'DOC_CODE' 		=> $ITMTSF_NUM,
										'DOC_STAT' 		=> $ITMTSF_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_item_tsfh");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITMTSF_NUM;
				$MenuCode 		= 'MN228';
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
			
			$url			= site_url('c_inventory/c_tr4n5p3r/tr4n5p3r_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // G
	{
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA		= $_GET['id'];
			$COLLDATA		= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL		= explode("~", $COLLDATA);
			$PRJCODE		= $EXTRACTCOL[0];
			$ITMTSF_NUM		= $EXTRACTCOL[1];
			
			// GET MENU DESC
				$mnCode				= 'MN228';
				$data["MenuCode"] 	= 'MN228';
				$data["MenuApp"] 	= 'MN229';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_inventory/c_tr4n5p3r/update_process');
			
			$getITMTSF 			= $this->m_transfer->get_ITMTSF_by_number($ITMTSF_NUM)->row();									
			
			$data['default']['ITMTSF_NUM'] 		= $getITMTSF->ITMTSF_NUM;
			$data['default']['ITMTSF_CODE'] 	= $getITMTSF->ITMTSF_CODE;
			$data['default']['ITMTSF_DATE'] 	= $getITMTSF->ITMTSF_DATE;
			$data['default']['ITMTSF_SENDD'] 	= $getITMTSF->ITMTSF_SENDD;
			$data['default']['ITMTSF_RECD'] 	= $getITMTSF->ITMTSF_RECD;
			$data['default']['PRJCODE'] 		= $getITMTSF->PRJCODE;
			$data['PRJCODE']					= $getITMTSF->PRJCODE;
			$PRJCODE 							= $getITMTSF->PRJCODE;
			$data['default']['ITMTSF_TYPE']		= $getITMTSF->ITMTSF_TYPE;
			$data['default']['ITMTSF_ORIGIN']	= $getITMTSF->ITMTSF_ORIGIN;
			$data['default']['PRJCODE_DEST']	= $getITMTSF->PRJCODE_DEST;
			$data['default']['ITMTSF_DEST']		= $getITMTSF->ITMTSF_DEST;
			$data['default']['JOBCODEID'] 		= $getITMTSF->JOBCODEID;
			$data['default']['ITMTSF_REFNO'] 	= $getITMTSF->ITMTSF_REFNO;
			$data['default']['ITMTSF_REFNO1'] 	= $getITMTSF->ITMTSF_REFNO1;
			$data['default']['ITMTSF_NOTE'] 	= $getITMTSF->ITMTSF_NOTE;
			$data['default']['ITMTSF_NOTE2'] 	= $getITMTSF->ITMTSF_NOTE2;
			$data['default']['ITMTSF_REVMEMO'] 	= $getITMTSF->ITMTSF_REVMEMO;
			$data['default']['ITMTSF_SENDER'] 	= $getITMTSF->ITMTSF_SENDER;
			$data['default']['ITMTSF_RECEIVER'] = $getITMTSF->ITMTSF_RECEIVER;
			$data['default']['ITMTSF_STAT'] 	= $getITMTSF->ITMTSF_STAT;
			$data['default']['ITMTSF_AMOUNT'] 	= $getITMTSF->ITMTSF_AMOUNT;
			$data['default']['Patt_Year'] 		= $getITMTSF->Patt_Year;
			$data['default']['Patt_Month'] 		= $getITMTSF->Patt_Month;
			$data['default']['Patt_Date'] 		= $getITMTSF->Patt_Date;
			$data['default']['Patt_Number']		= $getITMTSF->Patt_Number;
			
			$data['MenuCode']					= 'MN228';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getITMTSF->ITMTSF_NUM;
				$MenuCode 		= 'MN228';
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
			
			$this->load->view('v_inventory/v_itemtransfer/item_transfer_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
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
			$ITMTSF_TYPE	= 'WH';										// HARDCODE DULU
			$ITMTSF_ORIGIN 	= $this->input->post('ITMTSF_ORIGIN');
			$PRJCODE_DEST 	= $this->input->post('PRJCODE_DEST');
			$ITMTSF_DEST 	= $this->input->post('ITMTSF_DEST');
			//$JOBCODEID 	= $this->input->post('ITMTSF_REFNO');
			$JOBCODEID 		= '';
			$ITMTSF_REFNO 	= $this->input->post('ITMTSF_REFNO');
			$ITMTSF_REFNO1 	= $this->input->post('ITMTSF_REFNO1');
			$ITMTSF_NOTE	= addslashes($this->input->post('ITMTSF_NOTE'));
			$ITMTSF_NOTE2	= addslashes($this->input->post('ITMTSF_NOTE2'));
			$ITMTSF_REVMEMO	= $this->input->post('ITMTSF_REVMEMO');
			$ITMTSF_SENDER 	= $this->input->post('ITMTSF_SENDER');
			$ITMTSF_RECEIVER= $this->input->post('ITMTSF_RECEIVER');
			$ITMTSF_STAT 	= $this->input->post('ITMTSF_STAT');
			
			$ITMTSF_CREATER	= $DefEmp_ID;
			$ITMTSF_CREATED = date('Y-m-d H:i:s');	
			
			$updITMTSFH		= array('ITMTSF_CODE'		=> $ITMTSF_CODE,
									'ITMTSF_DATE'		=> $ITMTSF_DATE,
									'ITMTSF_SENDD'		=> $ITMTSF_SENDD,
									'ITMTSF_RECD'		=> $ITMTSF_RECD,
									'PRJCODE'			=> $PRJCODE,
									'ITMTSF_TYPE'		=> $ITMTSF_TYPE,
									'ITMTSF_ORIGIN'		=> $ITMTSF_ORIGIN,
									'PRJCODE_DEST'		=> $PRJCODE_DEST,
									'ITMTSF_DEST'		=> $ITMTSF_DEST,
									'JOBCODEID'			=> $JOBCODEID,
									'ITMTSF_REFNO'		=> $ITMTSF_REFNO,
									'ITMTSF_REFNO1'		=> $ITMTSF_REFNO1,
									'ITMTSF_NOTE'		=> $ITMTSF_NOTE,
									'ITMTSF_SENDER'		=> $ITMTSF_SENDER,
									'ITMTSF_RECEIVER'	=> $ITMTSF_RECEIVER,
									'ITMTSF_STAT'		=> $ITMTSF_STAT,
									'Patt_Year'			=> $Patt_Year,
									'Patt_Month'		=> $Patt_Month,
									'Patt_Date'			=> $Patt_Date);
			$this->m_transfer->update($ITMTSF_NUM, $updITMTSFH);

			if($ITMTSF_STAT == 9)
			{
				// 0. CEK REM QTY
					$retFalse 	= 0;
					$DOCCODE 	= "";
					$sqlSTOCK	= "SELECT PRJCODE, ITMTSF_DEST, ITM_CODE FROM tbl_item_tsfd WHERE ITMTSF_NUM = '$ITMTSF_NUM' AND PRJCODE = '$PRJCODE'";
					$resSTOCK	= $this->db->query($sqlSTOCK)->result();
					foreach($resSTOCK as $rowSTOCK) :
						$PRJCODE	= $rowSTOCK->PRJCODE;
						$ITMTSFDEST	= $rowSTOCK->ITMTSF_DEST;
						$ITM_CODE	= $rowSTOCK->ITM_CODE;

						$REM_QTY	= 0;
						$sqlSTOCK 	= "SELECT SUM(ITM_IN - ITM_OUT) AS REM_QTY FROM tbl_item_whqty
										WHERE PRJCODE = '$PRJCODE' AND WH_CODE = '$ITMTSFDEST' AND ITM_CODE = '$ITM_CODE'";
						$resSTOCK 	= $this->db->query($sqlSTOCK)->result();
						foreach($resSTOCK as $rowSTOCK) :
							$REM_QTY	= $rowSTOCK->REM_QTY;
						endforeach;
						if($REM_QTY == 0)
							$retFalse	= $retFalse+1;
					endforeach;

				// 1. OPERATION
					if($retFalse == 0)
					{
						// 1. UPDATE STATUS
							$sqlUPH		= "UPDATE tbl_item_tsfh SET ITMTSF_STAT = '9', STATDESC = 'Void', STATCOL = 'danger' WHERE ITMTSF_NUM = '$ITMTSF_NUM'";
							$this->db->query($sqlUPH);

						// 2. UPDATE ITEM
							$DOCCODE 	= "";
							$sqlTSF		= "SELECT ITMTSF_NUM, ITMTSF_CODE, PRJCODE, PRJCODE_DEST, ITMTSF_ORIGIN, ITMTSF_DEST, ITMTSF_REFNO,
												ITM_CODE, ITM_GROUP, ITMTSF_VOLM, ITMTSF_PRICE
											FROM tbl_item_tsfd WHERE ITMTSF_NUM = '$ITMTSF_NUM' AND PRJCODE = '$PRJCODE'";
							$resTSF		= $this->db->query($sqlTSF)->result();
							foreach($resTSF as $rowTSF) :
								$ITMTSF_NUM		= $rowTSF->ITMTSF_NUM;
								$DOCCODE		= $rowTSF->ITMTSF_CODE;
								$PRJCODE		= $rowTSF->PRJCODE;
								$PRJCODE_DEST	= $rowTSF->PRJCODE_DEST;
								$ITMTSF_ORIGIN	= $rowTSF->ITMTSF_ORIGIN;
								$ITMTSF_DEST	= $rowTSF->ITMTSF_DEST;
								$ITMTSF_REFNO	= $rowTSF->ITMTSF_REFNO;
								$ITM_CODE		= $rowTSF->ITM_CODE;
								$ITM_GROUP		= $rowTSF->ITM_GROUP;
								$ITMTSF_VOLM	= $rowTSF->ITMTSF_VOLM;
								$ITMTSF_PRICE	= $rowTSF->ITMTSF_PRICE;
								$ITM_TOTALP		= $ITMTSF_VOLM * $ITMTSF_PRICE;

								// START : UPDATE WH_QTY IN ORIGIN
									if(($PRJCODE_DEST != $PRJCODE) && $PRJCODE_DEST != '') // 1. JIKA BEDA WH, MAKA TAMBAHKAN DI WH ASAL ('$PRJCODE')
									{
										$sqlUpdORIG	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITMTSF_VOLM, ITM_OUT = ITM_OUT - $ITMTSF_VOLM,
															ITM_OUTP = ITM_OUTP - $ITM_TOTALP
														WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
										$this->db->query($sqlUpdORIG);
									}
									else // 2. TAMBAHKAN DI WAREHOUSE ASAL ('$PRJCODE')
									{
										$sqlUpWHORI	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITMTSF_VOLM,
															ITM_OUT = ITM_OUT - $ITMTSF_VOLM, ITM_OUTP = ITM_OUTP - $ITM_TOTALP
														WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$ITMTSF_ORIGIN'";
										$this->db->query($sqlUpWHORI);
									}
								// END : UPDATE WH_QTY IN ORIGIN
									
								// START : UPDATE WH_QTY IN DESTINATION	
									if(($PRJCODE_DEST != $PRJCODE) && $PRJCODE_DEST != '') // 1. JIKA BEDA WH, MAKA KURANGI DI WH TUJUAN ('$PRJCODE_DEST')
									{
										$sqlUpdDEST	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM - $ITMTSF_VOLM, ITM_IN = ITM_IN - $ITMTSF_VOLM,
															ITM_INP = ITM_INP - $ITM_TOTALP
														WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE_DEST'";
										$this->db->query($sqlUpdDEST);
									}
									else // 2. KURANGI DI WAREHOUSE TUJUAN ('$PRJCODE_DEST')
									{
										$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM - $ITMTSF_VOLM,
															ITM_IN = ITM_IN - $ITMTSF_VOLM, ITM_INP = ITM_INP - $ITM_TOTALP
														WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$ITMTSF_DEST'";
										$this->db->query($sqlUpWH);
									}
								// END : UPDATE WH_QTY IN DESTINATION

								// UPDATE PENERIMAAN TIAP MR
									$sqlUPDTMR	= "UPDATE tbl_mr_detail SET IRM_VOLM = IRM_VOLM - $ITMTSF_VOLM,
														IRM_AMOUNT = IRM_AMOUNT - $ITMTSF_PRICE
													WHERE MR_NUM = '$ITMTSF_REFNO' AND ITM_CODE = '$ITM_CODE'";
									$this->db->query($sqlUPDTMR);

									$sqlUPDTMR	= "UPDATE tbl_item SET MR_VOLM = MR_VOLM - $ITMTSF_VOLM, MR_AMOUNT = MR_AMOUNT - $ITMTSF_PRICE
													WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
									$this->db->query($sqlUPDTMR);
							endforeach;
					}
			}
			else
			{
				$this->m_transfer->deleteDetail($ITMTSF_NUM);
							
				foreach($_POST['data'] as $d)
				{
					$d['ITMTSF_NUM']	= $ITMTSF_NUM;
					$d['ITMTSF_CODE']	= $ITMTSF_CODE;
					$d['ITMTSF_DATE']	= $ITMTSF_DATE;
					$d['PRJCODE']		= $PRJCODE;
					$d['ITMTSF_TYPE']	= 'WH';
					$d['ITMTSF_ORIGIN']	= $ITMTSF_ORIGIN;
					$d['ITMTSF_DEST']	= $ITMTSF_DEST;
					$d['ITMTSF_REFNO']	= $ITMTSF_REFNO;
					$d['ITMTSF_REFNO1']	= $ITMTSF_REFNO1;
					$ITMTSF_VOLM		= $d['ITMTSF_VOLM'];
					$ITMTSF_PRICE		= $d['ITMTSF_PRICE'];
					$ITMTSF_AMOUNTX		= $ITMTSF_VOLM * $ITMTSF_PRICE;
					$ITMTSF_AMOUNT		= $ITMTSF_AMOUNT + $ITMTSF_AMOUNTX;
					$this->db->insert('tbl_item_tsfd',$d);
				}
				
				// UPDATE HEADER
					// $this->m_transfer->updateDet($ITMTSF_NUM, $PRJCODE, $ITMTSF_DATE);	// HOLDED ON 28 JAN 2019
					$updITMTSFH	= array('ITMTSF_AMOUNT'	=> $ITMTSF_AMOUNT);
					$this->m_transfer->update($ITMTSF_NUM, $updITMTSFH);
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "ITMTSF_NUM",
										'DOC_CODE' 		=> $ITMTSF_NUM,
										'DOC_STAT' 		=> $ITMTSF_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_item_tsfh");
				$this->m_updash->updateStatus($paramStat);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITMTSF_NUM;
				$MenuCode 		= 'MN228';
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
			
			$url			= site_url('c_inventory/c_tr4n5p3r/tr4n5p3r_i4x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		
		$url			= site_url('c_inventory/c_tr4n5p3r/t5f_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function t5f_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];

			// GET MENU DESC
				$mnCode				= 'MN229';
				$data["MenuApp"] 	= 'MN229';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_inventory/c_tr4n5p3r/iN20_x1/?id=";
			
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
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
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
				$data["url_search"] = site_url('c_inventory/c_tr4n5p3r/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				
				$num_rows 			= $this->m_transfer->count_all_tsfInb($PRJCODE, $key, $DefEmp_ID);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_transfer->get_all_tsfInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
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
			$data['backURL'] 	= site_url('c_inventory/c_tr4n5p3r/inb0x/');
			$data['PRJCODE'] 	= $PRJCODE;

			$MenuCode 			= 'MN229';
			$data["MenuCode"] 	= 'MN229';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN229';
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
			$url			= site_url('c_inventory/c_tr4n5p3r/iN20_x1/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function up180djinb() // G
	{
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ITMTSF_NUM		= $_GET['id'];
		$ITMTSF_NUM		= $this->url_encryption_helper->decode_url($ITMTSF_NUM);

		// GET MENU DESC
			$mnCode				= 'MN229';
			$data["MenuApp"] 	= 'MN229';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_inventory/c_tr4n5p3r/update_process_inb');
			
			$getITMTSF 			= $this->m_transfer->get_ITMTSF_by_number($ITMTSF_NUM)->row();									
			
			$data['default']['ITMTSF_NUM'] 		= $getITMTSF->ITMTSF_NUM;
			$data['default']['ITMTSF_CODE'] 	= $getITMTSF->ITMTSF_CODE;
			$data['default']['ITMTSF_DATE'] 	= $getITMTSF->ITMTSF_DATE;
			$data['default']['ITMTSF_SENDD'] 	= $getITMTSF->ITMTSF_SENDD;
			$data['default']['ITMTSF_RECD'] 	= $getITMTSF->ITMTSF_RECD;
			$data['default']['PRJCODE'] 		= $getITMTSF->PRJCODE;
			$data['PRJCODE']					= $getITMTSF->PRJCODE;
			$PRJCODE 							= $getITMTSF->PRJCODE;
			$data['default']['ITMTSF_TYPE']		= $getITMTSF->ITMTSF_TYPE;
			$data['default']['ITMTSF_ORIGIN']	= $getITMTSF->ITMTSF_ORIGIN;
			$data['default']['PRJCODE_DEST']	= $getITMTSF->PRJCODE_DEST;
			$data['default']['ITMTSF_DEST']		= $getITMTSF->ITMTSF_DEST;
			$data['default']['JOBCODEID'] 		= $getITMTSF->JOBCODEID;
			$data['default']['ITMTSF_REFNO'] 	= $getITMTSF->ITMTSF_REFNO;
			$data['default']['ITMTSF_REFNO1'] 	= $getITMTSF->ITMTSF_REFNO1;
			$data['default']['ITMTSF_NOTE'] 	= $getITMTSF->ITMTSF_NOTE;
			$data['default']['ITMTSF_NOTE2'] 	= $getITMTSF->ITMTSF_NOTE2;
			$data['default']['ITMTSF_REVMEMO'] 	= $getITMTSF->ITMTSF_REVMEMO;
			$data['default']['ITMTSF_SENDER'] 	= $getITMTSF->ITMTSF_SENDER;
			$data['default']['ITMTSF_RECEIVER'] = $getITMTSF->ITMTSF_RECEIVER;
			$data['default']['ITMTSF_STAT'] 	= $getITMTSF->ITMTSF_STAT;
			$data['default']['ITMTSF_AMOUNT'] 	= $getITMTSF->ITMTSF_AMOUNT;
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
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
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
			$ITMTSF_ORIGIN 	= $this->input->post('ITMTSF_ORIGIN');
			$PRJCODE_DEST 	= $this->input->post('PRJCODE_DEST');
			$ITMTSF_DEST 	= $this->input->post('ITMTSF_DEST');
			/*echo "PRJCODE = $PRJCODE<br>
			ITMTSF_ORIGIN = $ITMTSF_ORIGIN<br>
			PRJCODE_DEST = $PRJCODE_DEST<br>
			ITMTSF_DEST = $ITMTSF_DEST<br>";
			return false;*/
			//$JOBCODEID 	= $this->input->post('ITMTSF_REFNO');
			$JOBCODEID 		= '';
			$ITMTSF_REFNO 	= $this->input->post('ITMTSF_REFNO');
			$ITMTSF_NOTE	= addslashes($this->input->post('ITMTSF_NOTE'));
			$ITMTSF_NOTE2	= addslashes($this->input->post('ITMTSF_NOTE2'));
			$ITMTSF_REVMEMO	= $this->input->post('ITMTSF_REVMEMO');
			$ITMTSF_SENDER 	= $this->input->post('ITMTSF_SENDER');
			$ITMTSF_RECEIVER= $this->input->post('ITMTSF_REFNO');
			$ITMTSF_STAT 	= $this->input->post('ITMTSF_STAT');
			
			$ITMTSF_CREATER	= $DefEmp_ID;
			$ITMTSF_CREATED = date('Y-m-d H:i:s');

			if($ITMTSF_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $ITMTSF_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= $ITMTSF_CREATED;
					$AH_NOTES		= addslashes($this->input->post('ITMTSF_NOTE'));
					$AH_ISLAST		= $this->input->post('IS_LAST');
				
					$updITMTSFH		= array('ITMTSF_STAT'	=> 7); // Default step approval
					$this->m_transfer->update($ITMTSF_NUM, $updITMTSFH);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'PRJCODE'		=> $PRJCODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "ITMTSF_NUM",
											'DOC_CODE' 		=> $ITMTSF_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_item_tsfh");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				if($AH_ISLAST == 1)
				{
					$updITMTSFH		= array('ITMTSF_CODE'	=> $ITMTSF_CODE,
											'ITMTSF_DATE'	=> $ITMTSF_DATE,
											'ITMTSF_SENDD'	=> $ITMTSF_SENDD,
											'ITMTSF_RECD'	=> $ITMTSF_RECD,
											'JOBCODEID'		=> $JOBCODEID,
											'ITMTSF_NOTE'	=> $ITMTSF_NOTE,
											'ITMTSF_NOTE2'	=> $ITMTSF_NOTE2,
											'ITMTSF_REVMEMO'=> $ITMTSF_REVMEMO,
											'ITMTSF_SENDER'	=> $ITMTSF_SENDER,
											'ITMTSF_RECEIVER'=> $ITMTSF_RECEIVER,
											'ITMTSF_STAT'	=> $ITMTSF_STAT);
					$this->m_transfer->update($ITMTSF_NUM, $updITMTSFH);

					foreach($_POST['data'] as $d)
					{
						$PRJCODE_DEST		= $PRJCODE_DEST;
						$ITMTSF_DEST		= $ITMTSF_DEST;
						$ITM_CODE 			= $d['ITM_CODE'];
						$ITMTSF_VOLM 		= $d['ITMTSF_VOLM'];
						$ITMTSF_PRICE 		= $d['ITMTSF_PRICE'];
						$ITM_NAME 			= $d['ITM_NAME'];
						$ITM_UNIT 			= $d['ITM_UNIT'];
						$ITM_GROUP 			= $d['ITM_GROUP'];

						/*if($DEST_EXIST == 0)	// INSERT INTO TABLE
						{*/
							$parameters1 	= array('PRJCODE' 		=> $PRJCODE,
													'ITMTSF_NUM'	=> $ITMTSF_NUM,
													'ITMTSF_ORIGIN'	=> $ITMTSF_ORIGIN,
													'PRJCODE_DEST' 	=> $PRJCODE_DEST,
													'ITMTSF_DEST' 	=> $ITMTSF_DEST,
													'ITM_CODE' 		=> $ITM_CODE,
													'ITM_NAME'		=> $ITM_NAME,
													'ITM_UNIT'		=> $ITM_UNIT,
													'ITM_GROUP'		=> $ITM_GROUP,
													'ITM_QTY' 		=> $ITMTSF_VOLM,
													'ITM_PRICE' 	=> $ITMTSF_PRICE);
							$this->m_transfer->createITM($parameters1);
							//$this->m_transfer->createITM($ITMTSF_NUM, $ITM_CODE, $PRJCODE, $PRJCODE_DEST, $ITMTSF_DEST, $ITMTSF_VOLM); 
						/*}
						else
						{
							$this->m_transfer->updateITM($ITMTSF_NUM, $ITM_CODE, $PRJCODE, $PRJCODE_DEST, $ITMTSF_VOLM);
						}*/

						// UPDATE PENERIMAAN TIAP MR
						$sqlUPDTMR	= "UPDATE tbl_mr_detail SET IRM_VOLM = IRM_VOLM + $ITMTSF_VOLM, IRM_AMOUNT = IRM_AMOUNT + $ITMTSF_PRICE
										WHERE MR_NUM = '$ITMTSF_REFNO' AND ITM_CODE = '$ITM_CODE'";
						$this->db->query($sqlUPDTMR);

						$sqlUPDTMR	= "UPDATE tbl_item SET MR_VOLM = MR_VOLM + $ITMTSF_VOLM, MR_AMOUNT = MR_AMOUNT + $ITMTSF_PRICE
										WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sqlUPDTMR);
					}
				}
			}
			else
			{
				$updITMTSFH		= array('ITMTSF_NOTE'	=> $ITMTSF_NOTE,
										'ITMTSF_NOTE2'	=> $ITMTSF_NOTE2,
										'ITMTSF_REVMEMO'=> $ITMTSF_REVMEMO,
										'ITMTSF_STAT'	=> $ITMTSF_STAT);
				$this->m_transfer->update($ITMTSF_NUM, $updITMTSFH);
			}
			
			// CHECK CLOSE DOCUMENT
				$this->m_transfer->checkMR($PRJCODE, $ITMTSF_REFNO, $ITMTSF_NUM);

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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "ITMTSF_NUM",
										'DOC_CODE' 		=> $ITMTSF_NUM,
										'DOC_STAT' 		=> $ITMTSF_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_item_tsfh");
				$this->m_updash->updateStatus($paramStat);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITMTSF_NUM;
				$MenuCode 		= 'MN228';
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
			
			$url			= site_url('c_inventory/c_tr4n5p3r/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_inventory/m_transfer/m_transfer', '', TRUE);
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
			$getITMTSF 				= $this->m_transfer->get_ITMTSF_by_number($ITMTSF_NUM)->row();
			$data['ITMTSF_NUM'] 	= $getITMTSF->ITMTSF_NUM;
			$data['ITMTSF_CODE'] 	= $getITMTSF->ITMTSF_CODE;
			$data['ITMTSF_DATE'] 	= $getITMTSF->ITMTSF_DATE;
			$data['ITMTSF_SENDD']	= $getITMTSF->ITMTSF_SENDD;
			$data['ITMTSF_RECD'] 	= $getITMTSF->ITMTSF_RECD;
			$data['PRJCODE'] 		= $getITMTSF->PRJCODE;
			$PRJCODE 				= $getITMTSF->PRJCODE;
			$data['JOBCODEID'] 		= $getITMTSF->JOBCODEID;
			$data['ITMTSF_NOTE'] 	= $getITMTSF->ITMTSF_NOTE;
			$data['ITMTSF_NOTE2']	= $getITMTSF->ITMTSF_NOTE2;
			$data['ITMTSF_REVMEMO'] = $getITMTSF->ITMTSF_REVMEMO;
			$data['ITMTSF_STAT'] 	= $getITMTSF->ITMTSF_STAT;
			$data['ITMTSF_AMOUNT'] 	= $getITMTSF->ITMTSF_AMOUNT;
			$data['ITMTSF_SENDER'] 	= $getITMTSF->ITMTSF_SENDER;
			$data['ITMTSF_RECEIVER'] = $getITMTSF->ITMTSF_RECEIVER;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITMTSF_NUM;
				$MenuCode 		= 'MN228';
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
			
			//$this->load->view('v_inventory/v_itemtransfer/item_transfer_print', $data);
			$this->load->view('blank', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}