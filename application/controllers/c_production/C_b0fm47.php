<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= c_b0fm47.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_b0fm47 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		$this->load->model('m_production/m_matreq', '', TRUE);
	
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
		$this->load->model('m_production/m_bom', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_b0fm47/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN367';
				$data["MenuApp"] 	= 'MN367';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN367';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_b0fm47/gl20M/?id=";
			
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
				//$PRJCODE	= $this->session->userdata['proj_Code'];
				//$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				//redirect($url);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gl20M() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN367';
			$data["MenuCode"] 	= 'MN367';
			$data["MenuApp"] 	= 'MN367';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
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
				$data["url_search"] = site_url('c_production/c_b0fm47/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_bom->count_all_BOM($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_bom->get_all_BOM($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 	= $appName;	
				
			$MenuCode 		= 'MN367';			
			$getMENU 		= $this->m_menu->get_menu($MenuCode)->row();
			$MenuName_ID 	= $getMENU->menu_name_IND;
			$MenuName_EN 	= $getMENU->menu_name_ENG;
			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= $MenuName_ID;
				$data['h2_title'] 	= 'produksi';
			}
			else
			{
				$data["h1_title"] 	= $MenuName_EN;
				$data['h2_title'] 	= 'prodcution';
			}
			
			$data['addURL'] 	= site_url('c_production/c_b0fm47/a44_b0m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_b0fm47/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN367';
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
			
			$this->load->view('v_production/v_bom/v_bom', $data);
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
			$url			= site_url('c_production/c_b0fm47/gl20M/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("BOM_ID",
									"BOM_CODE", 
									"BOM_NAME", 
									"BOM_DESC",
									"CUST_DESC", 
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
			$num_rows 		= $this->m_bom->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bom->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$BOM_NUM 		= $dataI['BOM_NUM'];
				$BOM_CODE 		= $dataI['BOM_CODE'];
				$BOM_NAME	 	= $dataI['BOM_NAME'];
				$BOM_DESC 		= $dataI['BOM_DESC'];
				$BOM_STAT 		= $dataI['BOM_STAT'];
				$PRJCODE 		= $dataI['PRJCODE'];
				$CUST_CODE 		= $dataI['CUST_CODE'];
				$CUST_DESC 		= $dataI['CUST_DESC'];
				$BOM_CREATER	= $dataI['BOM_CREATER'];
				$BOM_CREATED	= $dataI['BOM_CREATED'];
				$BOM_DATE		= $dataI['BOM_CREATED'];

				if($CUST_DESC == '')
				{
					$CUST_DESC	= "-";
				}
				
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID 	= "$PRJCODE~$BOM_NUM";
				$secUpd		= site_url('c_production/c_b0fm47/u775o_b0fm47/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_production/c_b0fm47/prt_b0fm47/?id='.$this->url_encryption_helper->encode_url($BOM_NUM));
				$CollID		= "BOM~$BOM_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_bom_header~tbl_bom_detail~BOM_NUM~$BOM_NUM~PRJCODE~$PRJCODE";
                                    
				if($BOM_STAT == 1 || $BOM_STAT == 4) 
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				
				$output['data'][] = array("$noU.",
										  $dataI['BOM_CODE'],
										  $BOM_NAME,
										  $BOM_DESC,
										  $CUST_DESC,
										  "<div style='white-space:nowrap'>$BOM_CREATED</div>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_b0m() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN367';
			$data["MenuApp"] 	= 'MN367';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_b0fm47/add_process');
			$cancelURL				= site_url('c_production/c_b0fm47/gl20M/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_bom->count_all_CUST();
			$data['vwCUST'] 		= $this->m_bom->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN367';
			$data["MenuCode"] 		= 'MN367';
			$data['viewDocPattern'] = $this->m_bom->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN367';
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
	
			$this->load->view('v_production/v_bom/v_bom_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llF9() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_bom', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);

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
			$data['form_action']	= site_url('c_production/c_b0fm47/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_production/c_b0fm47/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_bom->count_all_itemFG($PRJCODE);
			$data['vwAllItem'] 		= $this->m_bom->get_all_itemFG($PRJCODE)->result();
					
			$this->load->view('v_production/v_bom/v_bom_selitemFG', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llit3m() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_bom', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE_HO	= $_GET['id'];
			$PRJCODE_HO	= $this->url_encryption_helper->decode_url($PRJCODE_HO);
			
		// GET PRJPERIOD ACT
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getPRJPER			= $this->m_updash->get_PRJPER($PRJCODE_HO)->row();
			$data["PRJCODE"] 	= $getPRJPER->PRJCODE;
			$PRJCODE			= $getPRJPER->PRJCODE;
				
			
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
			$data['form_action']	= site_url('c_production/c_b0fm47/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_production/c_b0fm47/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_bom->count_all_item($PRJCODE);
			$data['vwAllItem'] 		= $this->m_bom->get_all_item($PRJCODE)->result();
					
			$this->load->view('v_production/v_bom/v_bom_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
	
		date_default_timezone_set("Asia/Jakarta");
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN367';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$BOM_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$BOM_CODE 		= $this->input->post('BOM_CODE');
			$BOM_NAME 		= addslashes($this->input->post('BOM_NAME'));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$BOM_FG 		= $this->input->post('BOM_FG');
			$CUST_CODE1		= $this->input->post('CUST_CODE');
			$BOM_DESC 		= addslashes($this->input->post('BOM_DESC'));
			$BOM_STAT 		= $this->input->post('BOM_STAT');
			
			// GET PRJPERIODE ACTIVE
				$getGPRJP 	= $this->m_updash->get_PRJPER($PRJCODE)->row();
				$PRJPERIOD	= $getGPRJP->PRJPERIOD;
			
			$selStep		= 0;
			$CUST_CODE		= '';
			if($CUST_CODE1 != '')
			{
				foreach ($CUST_CODE1 as $sel_cust)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to	= explode ("|",$sel_cust);
						$cust_ID	= $user_to[0];
						$CUST_CODE	= $cust_ID;
						//$coll_MADD	= $user_ADD;
					}
					else
					{					
						$user_to	= explode ("|",$sel_cust);
						$cust_ID	= $user_to[0];			
						$CUST_CODE	= "$CUST_CODE;$cust_ID";
						//$coll_MADD	= "$coll_MADD;$user_ADD";
					}
				}
			}
			$CUST_DESC		= '';
			$sqlCUST 		= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
			$resCUST 		= $this->db->query($sqlCUST)->result();
			foreach($resCUST as $rowCUST) :
				$CUST_DESC = $rowCUST->CUST_DESC;		
			endforeach;
		
			$AddBOM			= array('BOM_NUM' 		=> $BOM_NUM,
									'BOM_CODE' 		=> $BOM_CODE,
									'BOM_NAME' 		=> $BOM_NAME,
									'PRJCODE'		=> $PRJCODE,
									'PRJPERIOD'		=> $PRJPERIOD,
									'BOM_FG'		=> $BOM_FG,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC'		=> $CUST_DESC,
									'BOM_DESC'		=> $BOM_DESC,
									'BOM_STAT'		=> $BOM_STAT,
									'BOM_UC'		=> $TRXTIME1,
									'BOM_CREATER'	=> $DefEmp_ID,
									'BOM_CREATED'	=> date('Y-m-d H:i:s'),
									'Patt_Year'		=> date('Y'));
			$this->m_bom->add($AddBOM);
				
			/*foreach($_POST['data'] as $d)
			{
				$d['BOM_NUM']	= $BOM_NUM;
				$this->db->insert('tbl_bom_detail',$d);
			}*/
			
			// START : INSERT DETAIL PROCESS
				foreach($_POST['selSTEP'] as $dSTEP)
				{
					$P_STEP			= $dSTEP['P_STEP'];
					$BOMSTF_ORD		= $dSTEP['BOMSTF_ORD'];
					if(isset($_POST["dataRM$P_STEP"]))
					{
						$RMROW	= 0;
						foreach($_POST["dataRM$P_STEP"] as $dRM1)
						{
							$RMROW				= $RMROW + 1;
							$dRM['BOMSTF_NUM']	= "I-$P_STEP-$TRXTIME1-$RMROW";
							$dRM['PRJCODE']		= $PRJCODE;
							$dRM['BOM_NUM']		= $BOM_NUM;
							$dRM['BOM_CODE']	= $BOM_CODE;
							$dRM['BOMSTF_ORD']	= $BOMSTF_ORD;
							$dRM['JO_NUM']		= '';
							$dRM['JO_CODE']		= '';
							$dRM['SO_NUM']		= '';
							$dRM['SO_CODE']		= '';
							$dRM['CCAL_NUM']	= '';
							$dRM['CCAL_CODE']	= '';
							$dRM['BOMSTF_STEP']	= $P_STEP;
							$dRM['BOMSTF_TYPE']	= $dRM1['ITM_TYPE'];
							$dRM['ITM_CODE']	= $dRM1['ITM_CODE'];
							$dRM['ITM_GROUP']	= $dRM1['ITM_GROUP'];
							$dRM['ITM_NAME']	= $dRM1['ITM_NAME'];
							$dRM['ITM_UNIT']	= $dRM1['ITM_UNIT'];
							$dRM['ITM_QTY']		= $dRM1['ITM_QTY'];
							$dRM['ITM_PRICE']	= $dRM1['ITM_PRICE'];
							$dRM['NEEDQRC']		= $dRM1['NEEDQRC'];
							$dRM['BOMSTF_UC']	= $TRXTIME1;
							$NEEDQRC			= $dFG1['NEEDQRC'];
							$this->db->insert('tbl_bom_stfdetail',$dRM);
						}
					}
					
					if(isset($_POST["dataFG$P_STEP"]))
					{
						$FGROW	= 0;
						foreach($_POST["dataFG$P_STEP"] as $dFG1)
						{
							$FGROW				= $FGROW + 1;
							$dFG['BOMSTF_NUM']	= "O-$P_STEP-$TRXTIME1-$FGROW";
							$dFG['PRJCODE']		= $PRJCODE;
							$dFG['BOM_NUM']		= $BOM_NUM;
							$dFG['BOM_CODE']	= $BOM_CODE;
							$dFG['BOMSTF_ORD']	= $BOMSTF_ORD;
							$dFG['JO_NUM']		= '';
							$dFG['JO_CODE']		= '';
							$dFG['SO_NUM']		= '';
							$dFG['SO_CODE']		= '';
							$dFG['CCAL_NUM']	= '';
							$dFG['CCAL_CODE']	= '';
							$dFG['BOMSTF_STEP']	= $P_STEP;
							$dFG['BOMSTF_TYPE']	= $dFG1['ITM_TYPE'];
							$dFG['ITM_CODE']	= $dFG1['ITM_CODE'];
							$dFG['ITM_GROUP']	= $dFG1['ITM_GROUP'];
							$dFG['ITM_NAME']	= $dFG1['ITM_NAME'];
							$dFG['ITM_UNIT']	= $dFG1['ITM_UNIT'];
							$dFG['ITM_QTY']		= $dFG1['ITM_QTY'];
							$dFG['ITM_PRICE']	= $dFG1['ITM_PRICE'];
							$dFG['NEEDQRC']		= $dFG1['NEEDQRC'];
							$dFG['BOMSTF_UC']	= $TRXTIME1;
							$NEEDQRC			= $dFG1['NEEDQRC'];
							$this->db->insert('tbl_bom_stfdetail',$dFG);
						}
					}
				}
			// END : INSERT DETAIL PROCESS
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('BOM_STAT');			// IF "ADD" CONDITION ALWAYS = BOM_STAT
				$parameters 	= array('DOC_CODE' 		=> $BOM_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "BOM",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_bom_header",// TABLE NAME
										'KEY_NAME'		=> "BOM_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "BOM_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $BOM_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_BOM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_BOM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_BOM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_BOM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_BOM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_BOM_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_BOM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "BOM_NUM",
										'DOC_CODE' 		=> $BOM_NUM,
										'DOC_STAT' 		=> $BOM_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_bom_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $BOM_NUM;
				$MenuCode 		= 'MN367';
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
			
			$url			= site_url('c_production/c_b0fm47/gl20M/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u775o_b0fm47() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN367';
			$data["MenuApp"] 	= 'MN367';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
				
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$BOM_NUM	= $EXTRACTCOL[1];
								
			$getBOMH				= $this->m_bom->get_bom_by_number($BOM_NUM)->row();
			$PRJCODE				= $getBOMH->PRJCODE;
			$data["MenuCode"] 		= 'MN367';
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_b0fm47/update_process');
			$cancelURL				= site_url('c_production/c_b0fm47/gl20M/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 				= 'MN367';
			$data["MenuCode"] 		= 'MN367';
			$data["PRJCODE"] 		= $PRJCODE;
			
			$data['countCUST']		= $this->m_bom->count_all_CUST();
			$data['vwCUST'] 		= $this->m_bom->get_all_CUST()->result();
			
			$getBOM 						= $this->m_bom->get_bom_by_number($BOM_NUM)->row();
			$data['default']['BOM_NUM'] 	= $getBOM->BOM_NUM;
			$data['default']['BOM_CODE'] 	= $getBOM->BOM_CODE;
			$data['default']['BOM_NAME'] 	= $getBOM->BOM_NAME;
			$data['default']['PRJCODE'] 	= $getBOM->PRJCODE;
			$data['default']['BOM_FG'] 		= $getBOM->BOM_FG;
			$data['default']['CUST_CODE'] 	= $getBOM->CUST_CODE;
			$data['default']['BOM_DESC'] 	= $getBOM->BOM_DESC;
			$data['default']['BOM_STAT'] 	= $getBOM->BOM_STAT;
			$data['default']['BOM_CREATER'] = $getBOM->BOM_CREATER;
			$data['default']['BOM_CREATED'] = $getBOM->BOM_CREATED;
			$data['default']['BOM_UC'] 		= $getBOM->BOM_UC;
			$data['default']['Patt_Number'] = $getBOM->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getBOM->BOM_NUM;
				$MenuCode 		= 'MN367';
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
			
			$this->load->view('v_production/v_bom/v_bom_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G 
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		
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
			
			$BOM_NUM 		= $this->input->post('BOM_NUM');
			$BOM_CODE 		= $this->input->post('BOM_CODE');
			$BOM_NAME 		= addslashes($this->input->post('BOM_NAME'));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$BOM_FG 		= $this->input->post('BOM_FG');
			$CUST_CODE1		= $this->input->post('CUST_CODE');
			$BOM_DESC 		= addslashes($this->input->post('BOM_DESC'));
			$BOM_STAT 		= $this->input->post('BOM_STAT');
			$BOM_UC 		= $this->input->post('BOM_UC');
			
			// GET PRJPERIODE ACTIVE
				$getGPRJP 	= $this->m_updash->get_PRJPER($PRJCODE)->row();
				$PRJPERIOD	= $getGPRJP->PRJPERIOD;
			
			$selStep		= 0;
			$CUST_CODE		= '';
			if($CUST_CODE1 != '')
			{
				foreach ($CUST_CODE1 as $sel_cust)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to	= explode ("|",$sel_cust);
						$cust_ID	= $user_to[0];
						$CUST_CODE	= $cust_ID;
						//$coll_MADD	= $user_ADD;
					}
					else
					{					
						$user_to	= explode ("|",$sel_cust);
						$cust_ID	= $user_to[0];			
						$CUST_CODE	= "$CUST_CODE;$cust_ID";
						//$coll_MADD	= "$coll_MADD;$user_ADD";
					}
				}
			}
			$CUST_DESC		= '';
			$sqlCUST 		= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
			$resCUST 		= $this->db->query($sqlCUST)->result();
			foreach($resCUST as $rowCUST) :
				$CUST_DESC = $rowCUST->CUST_DESC;		
			endforeach;

			$UpdBOM			= array('BOM_CODE' 		=> $BOM_CODE,
									'BOM_NAME' 		=> $BOM_NAME,
									'PRJCODE'		=> $PRJCODE,
									'PRJPERIOD'		=> $PRJPERIOD,
									'BOM_FG'		=> $BOM_FG,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC'		=> $CUST_DESC,
									'BOM_DESC'		=> $BOM_DESC,
									'BOM_STAT'		=> $BOM_STAT);
			$this->m_bom->updateBOM($BOM_NUM, $UpdBOM);
			
			//$this->m_bom->deleteBOMD($BOM_NUM);
				
			//foreach($_POST['data'] as $d)
			//{
				//$this->db->insert('tbl_bom_detail',$d);
			//}
			
			// CLEAR ALL DETAIL STEP
				$this->m_bom->deleteBOMSTDF($BOM_NUM);

			// START : INSERT DETAIL PROCESS
				foreach($_POST['selSTEP'] as $dSTEP)
				{
					$P_STEP			= $dSTEP['P_STEP'];
					$BOMSTF_ORD		= $dSTEP['BOMSTF_ORD'];
					if(isset($_POST["dataRM$P_STEP"]))
					{
						$RMROW	= 0;
						foreach($_POST["dataRM$P_STEP"] as $dRM1)
						{
							$RMROW				= $RMROW + 1;
							$dRM['BOMSTF_NUM']	= "I-$P_STEP-$BOM_UC-$RMROW";
							$dRM['PRJCODE']		= $PRJCODE;
							$dRM['BOM_NUM']		= $BOM_NUM;
							$dRM['BOM_CODE']	= $BOM_CODE;
							$dRM['BOMSTF_ORD']	= $BOMSTF_ORD;
							$dRM['JO_NUM']		= '';
							$dRM['JO_CODE']		= '';
							$dRM['SO_NUM']		= '';
							$dRM['SO_CODE']		= '';
							$dRM['CCAL_NUM']	= '';
							$dRM['CCAL_CODE']	= '';
							$dRM['BOMSTF_STEP']	= $P_STEP;
							$dRM['BOMSTF_TYPE']	= $dRM1['ITM_TYPE'];
							$dRM['ITM_CODE']	= $dRM1['ITM_CODE'];
							$dRM['ITM_GROUP']	= $dRM1['ITM_GROUP'];
							$dRM['ITM_NAME']	= $dRM1['ITM_NAME'];
							$dRM['ITM_UNIT']	= $dRM1['ITM_UNIT'];
							$dRM['ITM_QTY']		= $dRM1['ITM_QTY'];
							$dRM['ITM_PRICE']	= $dRM1['ITM_PRICE'];
							$dRM['NEEDQRC']		= $dRM1['NEEDQRC'];
							$dRM['BOMSTF_UC']	= $BOM_UC;
							$NEEDQRC			= $dRM1['NEEDQRC'];
							$this->db->insert('tbl_bom_stfdetail',$dRM);

							$ITM_CODE 	= $dRM1['ITM_CODE'];
							$ISGRP		= substr($ITM_CODE, 0, 3);
							if($ISGRP == 'GRP')
							{
								$sqlUCollH	= "UPDATE tbl_item_collh SET BOM_NUM = '$BOM_NUM', BOM_CODE = '$BOM_CODE'
												WHERE ICOLL_CODE = '$ITM_CODE'";
								$resUCollH	= $this->db->query($sqlUCollH);
							}
						}
					}
					
					if(isset($_POST["dataFG$P_STEP"]))
					{
						$FGROW	= 0;
						foreach($_POST["dataFG$P_STEP"] as $dFG1)
						{
							$FGROW				= $FGROW + 1;
							$dFG['BOMSTF_NUM']	= "O-$P_STEP-$BOM_UC-$FGROW";
							$dFG['PRJCODE']		= $PRJCODE;
							$dFG['BOM_NUM']		= $BOM_NUM;
							$dFG['BOM_CODE']	= $BOM_CODE;
							$dFG['BOMSTF_ORD']	= $BOMSTF_ORD;
							$dFG['JO_NUM']		= '';
							$dFG['JO_CODE']		= '';
							$dFG['SO_NUM']		= '';
							$dFG['SO_CODE']		= '';
							$dFG['CCAL_NUM']	= '';
							$dFG['CCAL_CODE']	= '';
							$dFG['BOMSTF_STEP']	= $P_STEP;
							$dFG['BOMSTF_TYPE']	= $dFG1['ITM_TYPE'];
							$dFG['ITM_CODE']	= $dFG1['ITM_CODE'];
							$dFG['ITM_GROUP']	= $dFG1['ITM_GROUP'];
							$dFG['ITM_NAME']	= $dFG1['ITM_NAME'];
							$dFG['ITM_UNIT']	= $dFG1['ITM_UNIT'];
							$dFG['ITM_QTY']		= $dFG1['ITM_QTY'];
							$dFG['ITM_PRICE']	= $dFG1['ITM_PRICE'];
							$dFG['NEEDQRC']		= $dFG1['NEEDQRC'];
							$dRM['BOMSTF_UC']	= $BOM_UC;
							$NEEDQRC			= $dFG1['NEEDQRC'];
							$this->db->insert('tbl_bom_stfdetail',$dFG);
						}
					}
					$LASSTEP	= $P_STEP;
				}

				// UPDATE ISLAST
					$sqlUPD	= "UPDATE tbl_bom_stfdetail SET ISLAST = 1 WHERE BOM_NUM = '$BOM_NUM' AND BOMSTF_STEP = '$LASSTEP'";
					$this->db->query($sqlUPD);
			// END : INSERT DETAIL PROCESS

			if($BOM_STAT == 3)
			{
				// INSERT INTO BOM DETAIL
					$sqlBOMD 	= "SELECT A.*, B.ISFG, B.ITM_CATEG FROM tbl_bom_stfdetail A LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
									WHERE BOM_NUM = '$BOM_NUM'";
					$resBOMD 	= $this->db->query($sqlBOMD)->result();
					foreach($resBOMD as $bomD) :
						$BOM_NUM	= $bomD->BOM_NUM;
						$BOM_CODE	= $bomD->BOM_CODE;
						$ITM_CODE	= $bomD->ITM_CODE;
						$ITM_UNIT	= $bomD->ITM_UNIT;
						$ITM_QTY	= $bomD->ITM_QTY;
						$ITM_PRICE	= $bomD->ITM_PRICE;
						//if($ITM_PRICE == 0) $ITM_PRICE = 1;
						$ITM_NOTES	= $bomD->ITM_NAME;
						$BOMSTFTYPE	= $bomD->BOMSTF_TYPE;			// IN OR OUT
						$ISLAST		= $bomD->ISLAST;
						$ISFG		= $bomD->ISFG;
						$ITM_CATEG	= $bomD->ITM_CATEG;
						if($ITM_CATEG == 'DY')
                        {
                        	$ITM_TOTAL 	= $ITM_QTY * $ITM_PRICE / 100;
                        }
                        else
                        {
							$ITM_TOTAL 	= $ITM_QTY * $ITM_PRICE;
						}
						if($ISLAST == 1 && $BOMSTFTYPE == 'OUT')
							$BOM_TYPE 	= 'OUT';
						else
							$BOM_TYPE 	= 'IN';

						$sqlCITM	= "tbl_bom_detail WHERE BOM_NUM = '$BOM_NUM' AND ITM_CODE = '$ITM_CODE'";
						$resCITM	= $this->db->count_all($sqlCITM);
						if($resCITM == 0)
						{
							$sqlINS	= "INSERT INTO tbl_bom_detail (BOM_NUM, BOM_CODE, PRJCODE, ITM_CODE, ITM_UNIT, ITM_QTY, ITM_PRICE, 
											BOM_TYPE, ITM_TOTAL, ITM_NOTES) VALUES
										('$BOM_NUM', '$BOM_CODE', '$PRJCODE', '$ITM_CODE', '$ITM_UNIT', $ITM_QTY, $ITM_PRICE, 
											'$BOM_TYPE', $ITM_TOTAL, '$ITM_NOTES')";
							$this->db->query($sqlINS);
						}
						else
						{
							$sqlUPD	= "UPDATE tbl_bom_detail SET ITM_QTY = ITM_QTY + $ITM_QTY, ITM_PRICE = $ITM_PRICE,
											ITM_TOTAL = ITM_TOTAL + $ITM_TOTAL
										WHERE BOM_NUM = '$BOM_NUM' AND ITM_CODE = '$ITM_CODE'";
							$this->db->query($sqlUPD);
						}
					endforeach;

					// START : UPDATE STATUS
						$AH_CODE		= $BOM_NUM;
						$AH_APPLEV		= $this->input->post('APP_LEVEL');
						$AH_APPROVER	= $DefEmp_ID;
						$AH_APPROVED	= date('Y-m-d H:i:s');
						$AH_NOTES		= $this->input->post('JournalH_Desc');
						$AH_ISLAST		= $this->input->post('IS_LAST');
						$completeName 	= $this->session->userdata['completeName'];

						$paramStat 		= array('PM_KEY' 		=> "BOM_NUM",
												'DOC_CODE' 		=> $BOM_NUM,
												'DOC_STAT' 		=> $BOM_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_bom_header");
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
			}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = BOM_STAT
				$parameters 	= array('DOC_CODE' 		=> $BOM_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "BOM",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_bom_header",	// TABLE NAME
										'KEY_NAME'		=> "BOM_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "BOM_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $BOM_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_BOM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_BOM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_BOM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_BOM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_BOM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_BOM_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_BOM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "BOM_NUM",
										'DOC_CODE' 		=> $BOM_NUM,
										'DOC_STAT' 		=> $BOM_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_bom_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $BOM_NUM;
				$MenuCode 		= 'MN367';
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
			
			$url			= site_url('c_production/c_b0fm47/gl20M/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prt_b0fm47()
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$BOM_NUM		= $_GET['id'];
		$BOM_NUM		= $this->url_encryption_helper->decode_url($BOM_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getBOM 						= $this->m_bom->get_bom_by_number($BOM_NUM)->row();
			$data['default']['BOM_NUM'] 	= $getBOM->BOM_NUM;
			$data['default']['BOM_CODE'] 	= $getBOM->BOM_CODE;
			$data['default']['BOM_NAME'] 	= $getBOM->BOM_NAME;
			$data['default']['PRJCODE'] 	= $getBOM->PRJCODE;
			$data['default']['BOM_DESC'] 	= $getBOM->BOM_DESC;
			$data['default']['BOM_STAT'] 	= $getBOM->BOM_STAT;
			$data['default']['BOM_CREATER'] = $getBOM->BOM_CREATER;
			$data['default']['BOM_CREATED'] = $getBOM->BOM_CREATED;
							
			//$this->load->view('v_sales/v_so/print_ro', $data);
			
			$this->load->view('blank', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function gRp_GrG() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_b0fm47/prji4xIC0ll/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prji4xIC0ll() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		$MenuCode 		= 'MN367';			
		$getMENU 		= $this->m_menu->get_menu($MenuCode)->row();
		$MenuName_ID 	= $getMENU->menu_name_IND;
		$MenuName_EN 	= $getMENU->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];

			// GET MENU DESC
				$mnCode				= 'MN413';
				$data["MenuApp"] 	= 'MN413';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN413';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_b0fm47/gli4xIC0ll/?id=";

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
				// jika ingin tanpa memilih proyek/anggaran
				//$PRJCODE	= $this->session->userdata['proj_Code'];
				//$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				//redirect($url);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gli4xIC0ll() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN413';
			$data["MenuApp"] 	= 'MN413';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
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
				$data["url_search"] = site_url('c_production/c_b0fm47/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_bom->count_all_BOM($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_bom->get_all_BOM($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 	= $appName;	
			
			$data["MenuCode"] 	= 'MN413';
			$MenuCode 		= 'MN413';			
			$getMENU 		= $this->m_menu->get_menu($MenuCode)->row();
			$MenuName_ID 	= $getMENU->menu_name_IND;
			$MenuName_EN 	= $getMENU->menu_name_ENG;
			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= $MenuName_ID;
				$data['h2_title'] 	= 'produksi';
			}
			else
			{
				$data["h1_title"] 	= $MenuName_EN;
				$data['h2_title'] 	= 'prodcution';
			}

			$data['addURL'] 	= site_url('c_production/c_b0fm47/a44_i4xIC0ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_b0fm47/gRp_GrG/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN413';
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
			
			$this->load->view('v_production/v_item_coll/v_item_coll', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataIcOll() // G
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
			
			$columns_valid 	= array("ICOLL_ID",
									"ICOLL_CODE", 
									"PRJCODE", 
									"ICOLL_FG", 
									"CUST_DESC",
									"ICOLL_REFNUM", 
									"ICOLL_CREATER");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bom->get_AllDataICOLLC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bom->get_AllDataICOLLL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$ICOLL_NUM 		= $dataI['ICOLL_NUM'];
				$ICOLL_CODE 	= $dataI['ICOLL_CODE'];
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO 	= $dataI['PRJCODE_HO'];
				$ICOLL_NOTES 	= $dataI['ICOLL_NOTES'];
				$CUST_CODE 		= $dataI['CUST_CODE'];
				$CUST_DESC 		= $dataI['CUST_DESC'];
				$ICOLL_FG		= $dataI['ICOLL_FG'];
				$ICOLL_QTYTOT	= $dataI['ICOLL_QTYTOT'];
				$ICOLL_REFNUM	= $dataI['ICOLL_REFNUM'];
				$ICOLL_CREATER	= $dataI['ICOLL_CREATER'];
				$ICOLL_CREATED	= $dataI['ICOLL_CREATED'];
				$CompName		= $dataI['CompName'];
				$empName		= cut_text2 ("$CompName", 15);
				$ICOLL_STAT		= $dataI['ICOLL_STAT'];
				
				// GET PRJPERIODE ACTIVE
					$getGPRJP 	= $this->m_updash->get_PRJPER($PRJCODE)->row();
					$PRJPERIOD	= $getGPRJP->PRJPERIOD;
				
				$CollID 		= "$PRJCODE~$ICOLL_NUM";
				$secPrintQR		= site_url('c_production/c_b0fm47/printQR/?id='.$this->url_encryption_helper->encode_url($ICOLL_NUM));
				$secUpd			= site_url('c_production/c_b0fm47/u775o_IcOll/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_production/c_b0fm47/prt_IcOll/?id='.$this->url_encryption_helper->encode_url($ICOLL_NUM));
				$CollID			= "ICOLL~$ICOLL_NUM~$PRJCODE";
				$secDel_DOC 	= base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDelIcut~tbl_item_collh~tbl_item_colld~ICOLL_NUM~$ICOLL_NUM~PRJCODE~$PRJCODE";

				$isDis		= "disabled='disabled'";
                $colITM		= "danger";
				if($ICOLL_STAT == 2)
				{
					$isDis	= "";
					$colITM	= "success";
				}
				else
				{
					$colITM	= "danger";
				}

				
				if($ICOLL_STAT == 1 || $ICOLL_STAT == 4)
				{
					$imgLoc		= base_url('assets/AdminLTE-2.0.5/dist/img/icon/dot_yellow1.png');
					$STATCOL	= "success";
					$STATDESC	= "Ready";
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='avascript:void(null);' class='btn btn-".$colITM." btn-xs' ".$isDis.">
										<i class='glyphicon glyphicon-qrcode'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					if($ICOLL_STAT == 2)
						$imgLoc		= base_url('assets/AdminLTE-2.0.5/dist/img/icon/dot_green1.png');
					elseif($ICOLL_STAT == 3)
						$imgLoc		= base_url('assets/AdminLTE-2.0.5/dist/img/icon/dot_blue1.png');
						
					$STATCOL	= "primary";
					$STATDESC	= "Used";
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<input type='hidden' name='urlPrintQR".$noU."' id='urlPrintQR".$noU."' value='".$secPrintQR."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='avascript:void(null);' class='btn btn-".$colITM." btn-xs' title='Show QRC' onClick='printQR(".$noU.")'>
										<i class='glyphicon glyphicon-qrcode'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled' style='display:none'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array("$noU.",
										  $ICOLL_CODE,
										  "$PRJCODE - $PRJPERIOD",
										  "$ICOLL_FG <br>$CUST_DESC",
										  $ICOLL_REFNUM,
										  $CompName,
										  "<div style='text-align:center; white-space:nowrap'>".str_replace(" ","<br>",$ICOLL_CREATED)."</div>",
										  "<img class='direct-chat-img' src='".$imgLoc."' style='max-width:30px; max-height:30px'>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_i4xIC0ll() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN413';
			$data["MenuApp"] 	= 'MN413';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_b0fm47/addIColl_process');
			$cancelURL				= site_url('c_production/c_b0fm47/gli4xIC0ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_bom->count_all_CUST();
			$data['vwCUST'] 		= $this->m_bom->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN413';
			$data["MenuCode"] 		= 'MN413';
			$data['viewDocPattern'] = $this->m_bom->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN413';
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
	
			$this->load->view('v_production/v_item_coll/v_item_coll_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llQRCM() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_bom', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			/*$PRJCODE_HO	= $_GET['id'];
			$PRJCODE_HO	= $this->url_encryption_helper->decode_url($PRJCODE_HO);*/
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXP_COLLD	= explode('~', $COLLDATA);
			$PRJCODE	= $EXP_COLLD[0];
			$IR_CODE	= $EXP_COLLD[1];
			$IR_CODE	= $_GET['IRC'];

		// GET PRJPERIOD ACT
			/*$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getPRJPER			= $this->m_updash->get_PRJPER($PRJCODE_HO)->row();
			$data["PRJCODE"] 	= $getPRJPER->PRJCODE;
			$PRJCODE			= $getPRJPER->PRJCODE;*/
			
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
			$data['form_action']	= site_url('c_production/c_b0fm47/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_production/c_b0fm47/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_bom->count_all_qrc($PRJCODE, $IR_CODE);
			$data['vwAllItem'] 		= $this->m_bom->get_all_qrc($PRJCODE, $IR_CODE)->result();
			
			$data['countAllRIB'] 	= $this->m_bom->count_all_qrcRIB($PRJCODE, $IR_CODE);
			$data['vwAllRIB'] 		= $this->m_bom->get_all_qrcRIB($PRJCODE, $IR_CODE)->result();
					
			$this->load->view('v_production/v_item_coll/v_item_coll_selectqrm', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llQRC() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_bom', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			/*$PRJCODE_HO	= $_GET['id'];
			$PRJCODE_HO	= $this->url_encryption_helper->decode_url($PRJCODE_HO);*/
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXP_COLLD	= explode('~', $COLLDATA);
			$PRJCODE	= $EXP_COLLD[0];
			$IR_CODE	= $EXP_COLLD[1];
			$IR_CODE	= $_GET['IRC'];

		// GET PRJPERIOD ACT
			/*$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getPRJPER			= $this->m_updash->get_PRJPER($PRJCODE_HO)->row();
			$data["PRJCODE"] 	= $getPRJPER->PRJCODE;
			$PRJCODE			= $getPRJPER->PRJCODE;*/
			
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
			$data['form_action']	= site_url('c_production/c_b0fm47/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_production/c_b0fm47/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_bom->count_all_qrc($PRJCODE, $IR_CODE);
			$data['vwAllItem'] 		= $this->m_bom->get_all_qrc($PRJCODE, $IR_CODE)->result();
			
			$data['countAllRIB'] 	= $this->m_bom->count_all_qrcRIB($PRJCODE, $IR_CODE);
			$data['vwAllRIB'] 		= $this->m_bom->get_all_qrcRIB($PRJCODE, $IR_CODE)->result();
					
			$this->load->view('v_production/v_item_coll/v_item_coll_selectqr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addIColl_process() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
	
		date_default_timezone_set("Asia/Jakarta");
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$ICOLL_NUM 		= "GRP".date('YmdHis');
			$ICOLL_CODE		= $this->input->post('ICOLL_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_HO		= $this->data['PRJCODE_HO'];
			$JO_NUM 		= $this->input->post('JO_NUM');
			$ICOLL_NOTES	= $this->input->post('ICOLL_NOTES');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$ICOLL_FG 		= $this->input->post('ICOLL_FG');
			$ICOLL_REFSJ 	= $this->input->post('ICOLL_REFSJ');
			$ICOLL_QTYTOT 	= $this->input->post('ICOLL_QTYTOT');
			$ICOLL_STAT		= $this->input->post('ICOLL_STAT');
			
			// GET PRJPERIODE ACTIVE
				$getGPRJP 	= $this->m_updash->get_PRJPER($PRJCODE)->row();
				$PRJPERIOD	= $getGPRJP->PRJPERIOD;
			
			$CUST_DESC		= '';
			$sqlCUST 		= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
			$resCUST 		= $this->db->query($sqlCUST)->result();
			foreach($resCUST as $rowCUST) :
				$CUST_DESC = $rowCUST->CUST_DESC;		
			endforeach;

			$JO_CODE 		= '';
			$sqlJO			= "SELECT JO_CODE FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM'";
			$resJO 			= $this->db->query($sqlJO)->result();
			foreach($resJO as $row) :
				$JO_CODE 	= $row->JO_CODE;
			endforeach;
			
			$row	= 0;
			$TOTQTY	= 0;
			foreach($_POST['data'] as $d)
			{
				$d['ICOLL_NUM']		= $ICOLL_NUM;
				$d['ICOLL_CODE']	= $ICOLL_CODE;
				$d['PRJCODE']		= $PRJCODE;
				$QRC_NUM			= $d['QRC_NUM'];
				$QRC_QTY			= $d['QRC_QTY'];
				$TOTQTY				= $TOTQTY + $QRC_QTY;
				
				$row				= $row + 1;
				if($row == 1)
				{
					$QRC_CODEV1		= $d['QRC_CODEV'];
				}
				else
				{
					$QRC_CODEV2		= $d['QRC_CODEV'];
					$QRC_CODEV1		= "$QRC_CODEV1; $QRC_CODEV2";
				}
				$this->db->insert('tbl_item_colld',$d);
				
				if($ICOLL_STAT == 2)
				{
					$this->m_bom->updQRCSTAT($QRC_NUM, $ICOLL_CODE, $JO_NUM, $JO_CODE);
				}
			}
		
			$AddICOLL		= array('ICOLL_NUM' 	=> $ICOLL_NUM,
									'ICOLL_CODE' 	=> $ICOLL_CODE,
									'PRJCODE' 		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'JO_NUM' 		=> $JO_NUM,
									'JO_CODE'		=> $JO_CODE,
									'ICOLL_NOTES'	=> $ICOLL_NOTES,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC'		=> $CUST_DESC,
									'ICOLL_FG'		=> $ICOLL_FG,
									'ICOLL_QTYTOT'	=> $TOTQTY,
									'ICOLL_STAT'	=> $ICOLL_STAT,
									'ICOLL_REFSJ'	=> $ICOLL_REFSJ,
									'ICOLL_CREATER'	=> $DefEmp_ID,
									'ICOLL_CREATED'	=> date('Y-m-d H:i:s'));
			$this->m_bom->addICOLL($AddICOLL);
			
			$updICOLL		= array('ICOLL_REFNUM' 	=> $QRC_CODEV1);
			$this->m_bom->updICOLL($ICOLL_NUM, $updICOLL);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ICOLL_NUM;
				$MenuCode 		= 'MN413';
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
			
			$url			= site_url('c_production/c_b0fm47/gli4xIC0ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u775o_IcOll() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN413';
			$data["MenuApp"] 	= 'MN413';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$ICOLL_NUM	= $EXTRACTCOL[1];
									
			$getICOLL 						= $this->m_bom->get_icoll_by_number($ICOLL_NUM)->row();
			$data['default']['ICOLL_NUM'] 	= $getICOLL->ICOLL_NUM;
			$data['default']['ICOLL_CODE'] 	= $getICOLL->ICOLL_CODE;
			$data['default']['PRJCODE'] 	= $getICOLL->PRJCODE;
			$PRJCODE						= $getICOLL->PRJCODE;
			$data['default']['PRJCODE_HO'] 	= $getICOLL->PRJCODE_HO;
			$data['default']['JO_NUM'] 		= $getICOLL->JO_NUM;
			$data['default']['ICOLL_NOTES'] = $getICOLL->ICOLL_NOTES;
			$data['default']['CUST_CODE'] 	= $getICOLL->CUST_CODE;
			$data['default']['CUST_DESC'] 	= $getICOLL->CUST_DESC;
			$data['default']['ICOLL_FG'] 	= $getICOLL->ICOLL_FG;
			$data['default']['ICOLL_REFSJ']	= $getICOLL->ICOLL_REFSJ;
			$data['default']['ICOLL_QTYTOT']= $getICOLL->ICOLL_QTYTOT;
			$data['default']['ICOLL_REFNUM']= $getICOLL->ICOLL_REFNUM;
			$data['default']['ICOLL_STAT'] 	= $getICOLL->ICOLL_STAT;
			
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';
			
			$data['form_action']	= site_url('c_production/c_b0fm47/upIColl_process');
			$cancelURL				= site_url('c_production/c_b0fm47/gli4xIC0ll/gli4xIC0ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_bom->count_all_CUST();
			$data['vwCUST'] 		= $this->m_bom->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN413';
			$data["MenuCode"] 		= 'MN413';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getICOLL->ICOLL_NUM;
				$MenuCode 		= 'MN413';
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
			
			$this->load->view('v_production/v_item_coll/v_item_coll_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function upIColl_process() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
	
		date_default_timezone_set("Asia/Jakarta");
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$ICOLL_NUM 		= $this->input->post('ICOLL_NUM');
			$ICOLL_CODE		= $this->input->post('ICOLL_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_HO		= $this->data['PRJCODE_HO'];
			$JO_NUM 		= $this->input->post('JO_NUM');
			$ICOLL_NOTES	= $this->input->post('ICOLL_NOTES');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$ICOLL_FG 		= $this->input->post('ICOLL_FG');
			$ICOLL_REFSJ 	= $this->input->post('ICOLL_REFSJ');
			$ICOLL_QTYTOT 	= $this->input->post('ICOLL_QTYTOT');
			$ICOLL_STAT		= $this->input->post('ICOLL_STAT');
			
			// GET PRJPERIODE ACTIVE
				$getGPRJP 	= $this->m_updash->get_PRJPER($PRJCODE)->row();
				$PRJPERIOD	= $getGPRJP->PRJPERIOD;
			
			$CUST_DESC		= '';
			$sqlCUST 		= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
			$resCUST 		= $this->db->query($sqlCUST)->result();
			foreach($resCUST as $rowCUST) :
				$CUST_DESC = $rowCUST->CUST_DESC;		
			endforeach;

			$JO_CODE 		= '';
			$sqlJO			= "SELECT JO_CODE FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM'";
			$resJO 			= $this->db->query($sqlJO)->result();
			foreach($resJO as $row) :
				$JO_CODE 	= $row->JO_CODE;
			endforeach;
			
			$this->m_bom->deleteIc0ll($ICOLL_NUM);
			
			$row			= 0;
			$TOTQTY			= 0;
			foreach($_POST['data'] as $d)
			{
				$d['ICOLL_NUM']		= $ICOLL_NUM;
				$d['ICOLL_CODE']	= $ICOLL_CODE;
				$d['PRJCODE']		= $PRJCODE;
				$QRC_NUM			= $d['QRC_NUM'];
				$QRC_QTY			= $d['QRC_QTY'];
				$TOTQTY				= $TOTQTY + $QRC_QTY;
				
				$row				= $row + 1;
				if($row == 1)
				{
					$QRC_CODEV1		= $d['QRC_CODEV'];
				}
				else
				{
					$QRC_CODEV2		= $d['QRC_CODEV'];
					$QRC_CODEV1		= "$QRC_CODEV1; $QRC_CODEV2";
				}
				$this->db->insert('tbl_item_colld',$d);
				
				if($ICOLL_STAT == 2)
					$this->m_bom->updQRCSTAT($QRC_NUM, $ICOLL_CODE, $JO_NUM, $JO_CODE);
			}
		
			$updICOLL		= array('ICOLL_NUM' 	=> $ICOLL_NUM,
									'ICOLL_CODE' 	=> $ICOLL_CODE,
									'PRJCODE' 		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'JO_NUM'		=> $JO_NUM,
									'JO_CODE'		=> $JO_CODE,
									'ICOLL_NOTES'	=> $ICOLL_NOTES,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC'		=> $CUST_DESC,
									'ICOLL_FG'		=> $ICOLL_FG,
									'ICOLL_QTYTOT'	=> $TOTQTY,
									'ICOLL_REFSJ'	=> $ICOLL_REFSJ,
									'ICOLL_STAT'	=> $ICOLL_STAT,
									'ICOLL_CREATER'	=> $DefEmp_ID);
			$this->m_bom->updICOLL($ICOLL_NUM, $updICOLL);

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ICOLL_NUM;
				$MenuCode 		= 'MN413';
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
			
			$url			= site_url('c_production/c_b0fm47/gli4xIC0ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a44QRA0_pR04uctpr0535() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$PRJCODE	= $splitCode[0];
			$PRODS_STEP	= $splitCode[1];
			
			
			$EmpID 			= $this->session->userdata('Emp_ID');
						
			$data['title'] 			= $appName;
			$data['task']			= 'add';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Transfer Proses";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Transfer Process";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_pR04uctpr0535/add_process_qrc');
			$cancelURL				= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			//$data['countCUST']		= $this->m_prodprocess->count_all_CUST();
			//$data['vwCUST'] 		= $this->m_prodprocess->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			$data['PRODS_STEP']		= $PRODS_STEP;
			
			$MenuCode 				= 'MN413';
			$data["MenuCode"] 		= 'MN413';
			$data['viewDocPattern'] = $this->m_prodprocess->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN413';
				$TTR_CATEG		= 'SELQR';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
	
			$this->load->view('v_production/v_prodprocess/v_prodprocess_form_qr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llRM() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$BOM_NUM	= $_GET['BOMNUM'];
			$PRODS_STEP	= $_GET['PRODSTEP'];
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
			
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
			
			$data['BOM_NUM'] 		= $BOM_NUM;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['PRODS_STEP'] 	= $PRODS_STEP;
			
			$this->load->view('v_production/v_bom/v_bom_selitemRM', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llW1p() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_prodprocess', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$BOM_NUM		= $_GET['BOMNUM'];
			$PRODS_STEP	= $_GET['PRODSTEP'];
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
			
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
			
			$data['BOM_NUM'] 		= $BOM_NUM;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['PRODS_STEP'] 	= $PRODS_STEP;
			//$data['cAllItemPrm']	= $this->m_joborder->count_all_prim($PRJCODE, $BOM_NUM, $PRODS_STEP);
			//$data['vwAllItemPrm'] = $this->m_joborder->view_all_prim($PRJCODE, $BOM_NUM, $PRODS_STEP)->result();
			
			//$data['cAllItemSubs']	= $this->m_joborder->count_all_subs($PRJCODE, $BOM_NUM, $PRODS_STEP);
			//$data['vwAllItemSubs'] 	= $this->m_joborder->view_all_subs($PRJCODE, $BOM_NUM, $PRODS_STEP)->result();
					
			//$this->load->view('v_production/v_joborder/v_joborder_selitem_req', $data);
			$this->load->view('v_production/v_bom/v_bom_selitemWIP', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function cU7_9R3193() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_b0fm47/prjcU7_9R3193/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjcU7_9R3193() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		$MenuCode 		= 'MN367';			
		$getMENU 		= $this->m_menu->get_menu($MenuCode)->row();
		$MenuName_ID 	= $getMENU->menu_name_IND;
		$MenuName_EN 	= $getMENU->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];

			// GET MENU DESC
				$mnCode				= 'MN414';
				$data["MenuApp"] 	= 'MN414';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN414';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_b0fm47/glcU7_9R3193/?id=";

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
				// jika ingin tanpa memilih proyek/anggaran
				//$PRJCODE	= $this->session->userdata['proj_Code'];
				//$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				//redirect($url);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function glcU7_9R3193() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN414';
			$data["MenuApp"] 	= 'MN414';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
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
				$data["url_search"] = site_url('c_production/c_b0fm47/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_bom->count_all_BOM($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_bom->get_all_BOM($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 	= $appName;	
			
			$data["MenuCode"] 	= 'MN414';
			$MenuCode 			= 'MN414';			
			$getMENU 			= $this->m_menu->get_menu($MenuCode)->row();
			$MenuName_ID 		= $getMENU->menu_name_IND;
			$MenuName_EN 		= $getMENU->menu_name_ENG;
			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= $MenuName_ID;
				$data['h2_title'] 	= 'produksi';
			}
			else
			{
				$data["h1_title"] 	= $MenuName_EN;
				$data['h2_title'] 	= 'prodcution';
			}
			
			$data['addURL'] 	= site_url('c_production/c_b0fm47/a44_i4xIcut/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_b0fm47/prjcU7_9R3193/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN413';
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
			
			$this->load->view('v_production/v_item_cut/v_item_cut', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataIcu7() // G
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
			
			$columns_valid 	= array("ICUT_ID",
									"ICUT_CODE", 
									"PRJCODE", 
									"ICUT_QRCN", 
									"CUST_DESC",
									"JO_CODE", 
									"ICUT_CREATER");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bom->get_AllDataICUTC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bom->get_AllDataICUTL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$ICUT_NUM 		= $dataI['ICUT_NUM'];
				$ICUT_CODE 		= $dataI['ICUT_CODE'];
				$PRJCODE 		= $dataI['PRJCODE'];
				$PRJCODE_HO 	= $dataI['PRJCODE_HO'];
				$ICUT_NOTES 	= $dataI['ICUT_NOTES'];
				$CUST_CODE 		= $dataI['CUST_CODE'];
				$CUST_DESC 		= $dataI['CUST_DESC'];
				$ICUT_QRCN		= $dataI['ICUT_QRCN'];
				$ISCREATJO		= $dataI['ISCREATJO'];
				$JO_CODE		= $dataI['JO_CODE'];
				$ICUT_REFNUM	= $dataI['ICUT_REFNUM'];
				$ICUT_CREATER	= $dataI['ICUT_CREATER'];
				$ICUT_CREATED	= $dataI['ICUT_CREATED'];
				$CompName		= $dataI['CompName'];
				$empName		= cut_text2 ("$CompName", 15);
				$ICUT_STAT		= $dataI['ICUT_STAT'];
				$STATCOL		= $dataI['STATCOL'];
				$STATDESC		= $dataI['STATDESC'];
				if($ISCREATJO == 1)
					$STATDESC	= "Used";
				
				// GET PRJPERIODE ACTIVE
					$getGPRJP 	= $this->m_updash->get_PRJPER($PRJCODE)->row();
					$PRJPERIOD	= $getGPRJP->PRJPERIOD;
				
				$CollID 		= "$PRJCODE~$ICUT_NUM";
				$secPrintQR		= site_url('c_production/c_b0fm47/printQRCUT/?id='.$this->url_encryption_helper->encode_url($ICUT_NUM));
				$secUpd			= site_url('c_production/c_b0fm47/u775o_ICUT/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_production/c_b0fm47/prt_ICUT/?id='.$this->url_encryption_helper->encode_url($ICUT_NUM));
				$CollID			= "ICUT~$ICUT_NUM~$PRJCODE";
				$secDel_DOC 	= base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDelIcut~tbl_item_cuth~tbl_item_cutd~ICUT_NUM~$ICUT_NUM~PRJCODE~$PRJCODE";

				$colITM			= "danger";
				if($ICUT_STAT == 3)
				{
					$isDis		= "";
					$colITM		= "success";
				}
				else
				{
					$colITM		= "danger";
				}
				
				if($ICUT_STAT == 1)
				{
					$imgLoc		= base_url('assets/AdminLTE-2.0.5/dist/img/icon/dot_yellow1.png');
					//$STATCOL	= "success";
					//$STATDESC	= "Ready";
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
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
					$qrDis 			= "";
					if($ICUT_STAT == 2)
					{
						$imgLoc		= base_url('assets/AdminLTE-2.0.5/dist/img/icon/dot_green1.png');
						$qrDis 		= " disabled='disabled'";
					}
					elseif($ICUT_STAT == 3)
					{
						$imgLoc		= base_url('assets/AdminLTE-2.0.5/dist/img/icon/dot_blue1.png');
					}
						
					//$STATCOL	= "primary";
					//$STATDESC	= "Used";
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									 <input type='hidden' name='urlPrintQR".$noU."' id='urlPrintQR".$noU."' value='".$secPrintQR."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='avascript:void(null);' class='btn btn-".$colITM." btn-xs' title='Show QRC' onClick='printQR(".$noU.")' ".$qrDis.">
										<i class='glyphicon glyphicon-qrcode'></i>
									</a>
									<a href='#' title='Delete' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array("$noU.",
										  $ICUT_CODE,
										  "$PRJCODE - $PRJPERIOD",
										  "$ICUT_QRCN <br>$CUST_DESC",
										  $JO_CODE,
										  "<div style='text-align:center;'>".$CompName."</div>",
										  "<div style='text-align:center; white-space:nowrap'>".str_replace(" ","<br>",$ICUT_CREATED)."</div>",
										  //"<img class='direct-chat-img' src='".$imgLoc."' style='max-width:30px; max-height:30px'>",
										  "<div style='text-align:center; white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span></div>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function d3lICut()
	{		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$ICUT_NUM	= $EXTRACTCOL[1];
		}
	}
	
	function a44_i4xIcut() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN414';
			$data["MenuApp"] 	= 'MN414';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_b0fm47/addIcut_process');
			$cancelURL				= site_url('c_production/c_b0fm47/gli4xIcut/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_bom->count_all_CUST();
			$data['vwCUST'] 		= $this->m_bom->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN414';
			$data["MenuCode"] 		= 'MN414';
			$data['viewDocPattern'] = $this->m_bom->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN414';
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
	
			$this->load->view('v_production/v_item_cut/v_item_cut_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4ll5J() // G 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_bom', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);

			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Surat Jalan";
			}
			else
			{
				$data["h2_title"] 	= "Delivery Order List";
			}

			$data['PRJCODE'] 		= $PRJCODE;			
			$data['countAllSJ'] 	= $this->m_bom->count_all_itemSJ($PRJCODE);
			$data['vwAllSJ'] 		= $this->m_bom->get_all_itemSJ($PRJCODE)->result();
					
			$this->load->view('v_production/v_item_cut/v_item_selSJ', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llR1B() // G 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_bom', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$CUST_SJNO	= $_GET['sJc4'];

			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);

			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Greige RIB";
			}
			else
			{
				$data["h2_title"] 	= "Greige RIB List";
			}

			$data['PRJCODE'] 		= $PRJCODE;			
			$data['countAllItem'] 	= $this->m_bom->count_all_itemRIB($PRJCODE, $CUST_SJNO);
			$data['vwAllItem'] 		= $this->m_bom->get_all_itemRIB($PRJCODE, $CUST_SJNO)->result();
					
			$this->load->view('v_production/v_item_cut/v_item_selFG', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addIcut_process() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
	
		date_default_timezone_set("Asia/Jakarta");
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$ICUT_NUM		= "$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$ICUT_CODE 		= $this->input->post('ICUT_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_HO 	= $this->data['PRJCODE_HO'];
			$CUST_SJNO		= $this->input->post('CUST_SJNO');
			$ICUT_QRCN		= $this->input->post('ICUT_QRCN');
			$ICUT_NOTES 	= $this->input->post('ICUT_NOTES');
			$ICUT_QTY 		= $this->input->post('ICUT_QTY');
			$ICUT_STAT 		= $this->input->post('ICUT_STAT');

			// GET ITM_CODE
				$ITM_CODE 	= '';
				$sqlITM 	= "SELECT ITM_CODE from tbl_qrc_detail where QRC_NUM = '$ICUT_QRCN'";
				$resITM 	= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM) :
					$ITM_CODE = $rowITM->ITM_CODE;		
				endforeach;
		
			$AddCUT			= array('ICUT_NUM' 		=> $ICUT_NUM,
									'ICUT_CODE' 	=> $ICUT_CODE,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'ITM_CODE'		=> $ITM_CODE,
									'CUST_SJNO' 	=> $CUST_SJNO,
									'ICUT_QRCN'		=> $ICUT_QRCN,
									'ICUT_NOTES'	=> $ICUT_NOTES,
									'ICUT_QTY'		=> $ICUT_QTY,
									'ICUT_STAT'		=> $ICUT_STAT,
									'ICUT_CREATER'	=> $DefEmp_ID,
									'ICUT_CREATED'	=> date('Y-m-d H:i:s'));
			$this->m_bom->addCUT($AddCUT);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "ICUT_NUM",
										'DOC_CODE' 		=> $ICUT_NUM,
										'DOC_STAT' 		=> $ICUT_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_item_cuth");
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
			
			$url			= site_url('c_production/c_b0fm47/glcU7_9R3193/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u775o_Icut() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_bom', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN414';
			$data["MenuApp"] 	= 'MN414';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$ICOLL_NUM	= $EXTRACTCOL[1];
									
			$getICUT 						= $this->m_bom->get_icut_by_number($ICOLL_NUM)->row();
			$data['default']['ICUT_NUM'] 	= $getICUT->ICUT_NUM;
			$data['default']['ICUT_CODE'] 	= $getICUT->ICUT_CODE;
			$data['default']['PRJCODE'] 	= $getICUT->PRJCODE;
			$PRJCODE						= $getICUT->PRJCODE;
			$data['default']['PRJCODE_HO'] 	= $getICUT->PRJCODE_HO;
			$data['default']['CUST_SJNO'] 	= $getICUT->CUST_SJNO;
			$data['default']['ICUT_NOTES'] 	= $getICUT->ICUT_NOTES;
			$data['default']['CUST_CODE'] 	= $getICUT->CUST_CODE;
			$data['default']['CUST_DESC'] 	= $getICUT->CUST_DESC;
			$data['default']['ICUT_QRCN'] 	= $getICUT->ICUT_QRCN;
			$data['default']['ICUT_REFNUM']	= $getICUT->ICUT_REFNUM;
			$data['default']['ICUT_QTY'] 	= $getICUT->ICUT_QTY;
			$data['default']['ICUT_STAT'] 	= $getICUT->ICUT_STAT;
			
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';
			
			$data['form_action']	= site_url('c_production/c_b0fm47/upIcut_process');
			$cancelURL				= site_url('c_production/c_b0fm47/glcU7_9R3193/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_bom->count_all_CUST();
			$data['vwCUST'] 		= $this->m_bom->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN414';
			$data["MenuCode"] 		= 'MN414';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getICUT->ICUT_NUM;
				$MenuCode 		= 'MN414';
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
			
			$this->load->view('v_production/v_item_cut/v_item_cut_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function upIcut_process() // G
	{
		$this->load->model('m_production/m_bom', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
	
		date_default_timezone_set("Asia/Jakarta");
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$ICUT_NUM 		= $this->input->post('ICUT_NUM');
			$ICUT_CODE 		= $this->input->post('ICUT_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE_HO 	= $this->data['PRJCODE_HO'];
			$CUST_SJNO		= $this->input->post('CUST_SJNO');
			$ICUT_QRCN		= $this->input->post('ICUT_QRCN');
			$ICUT_NOTES 	= $this->input->post('ICUT_NOTES');
			$ICUT_QTY 		= $this->input->post('ICUT_QTY');
			$ICUT_STAT 		= $this->input->post('ICUT_STAT');

			// GET ITM_CODE
				$ITM_CODE 	= '';
				$sqlITM 	= "SELECT ITM_CODE from tbl_qrc_detail where QRC_NUM = '$ICUT_QRCN'";
				$resITM 	= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM) :
					$ITM_CODE = $rowITM->ITM_CODE;		
				endforeach;

			$LASTPATT		= 0;
			$sqlLST			= "tbl_item_cuth WHERE ICUT_QRCN = '$ICUT_QRCN' AND ICUT_STAT = 3";
			$resLST 		= $this->db->count_all($sqlLST);
			$RN 			= $resLST+1;
			$len 			= strlen($RN);
			if($len==1) $nol="00";
			else if($len==2) $nol="0";
			else $nol="";

			$RN1 			= $nol.$RN;
			$SNCODE2		= substr($ICUT_QRCN,-16);
			$QRC_CODEV		= "$SNCODE2 $RN1";
		
			$updICUT		= array('ICUT_CODE' 	=> $ICUT_CODE,
									'PRJCODE'		=> $PRJCODE,
									'PRJCODE_HO'	=> $PRJCODE_HO,
									'ITM_CODE'		=> $ITM_CODE,
									'CUST_SJNO' 	=> $CUST_SJNO,
									'ICUT_QRCN'		=> $ICUT_QRCN,
									'ICUT_NOTES'	=> $ICUT_NOTES,
									'ICUT_QTY'		=> $ICUT_QTY,
									'ICUT_QRCNV'	=> $QRC_CODEV,
									'ICUT_STAT'		=> $ICUT_STAT);
			$this->m_bom->updICUT($ICUT_NUM, $updICUT);

			if($ICUT_STAT == 3)
			{
				// CREATE NEW ITEM
					$LASTNO			= 1;
					$sqlITML 		= "SELECT MAX(LASTNO) AS LASTNO FROM tbl_item WHERE PRJCODE = '$PRJCODE'";
					$resITML 		= $this->db->query($sqlITML)->result();
					foreach($resITML as $rowL) :
						$LASTNO 	= $rowL->LASTNO;
					endforeach;
					$LASTNO			= $LASTNO + 1;


					$IR_NUM 		= '';
					$IR_CODE 		= '';
					$IR_CODE_REF	= '';
					$sqlITM 		= "SELECT A.ITM_CODE, B.ITM_NAME, B.ITM_TYPE, B.ITM_UNIT, B.ACC_ID, B.ACC_ID_UM, B.STATUS, B.JOBCODEID,
											B.ITM_CATEG, B.ITM_GROUP, B.PRJPERIOD, B.ITM_CODE_H, B.ISFG, B.ISWIP, B.ISRM, B.ISRIB,
											A.IR_NUM, A.IR_CODE, A.IR_CODE_REF
										FROM tbl_qrc_detail A  INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
											AND B.PRJCODE = '$PRJCODE'
										WHERE A.QRC_NUM = '$ICUT_QRCN'";
					$resITM 		= $this->db->query($sqlITM)->result();
					foreach($resITM as $row) :
						$ITM_CODE 	= $row->ITM_CODE;
						$ITM_NAME 	= $row->ITM_NAME;
						$ITM_TYPE 	= $row->ITM_TYPE;
						$ITM_UNIT 	= $row->ITM_UNIT;
						$ACC_ID 	= $row->ACC_ID;
						$ACC_ID_UM 	= $row->ACC_ID_UM;
						$STATUS 	= $row->STATUS;
						$ITM_CATEG 	= $row->ITM_CATEG;
						$ITM_GROUP 	= $row->ITM_GROUP;
						$PRJPERIOD 	= $row->PRJPERIOD;
						$ITM_CODE_H = $row->ITM_CODE_H;
						$JOBCODEID 	= $row->JOBCODEID;
						$IR_NUM 	= $row->IR_NUM;
						$IR_CODE 	= $row->IR_CODE;
						$IR_CODE_REF= $row->IR_CODE_REF;
					endforeach;
				
					$ITM_KIND			= 'ISRIB';
					if($ITM_KIND == 'ISRIB')
					{
						$ISRIB		= 1;
						$ITM_KIND	= 12;
					}
					
					$ITM_VOLMBG		= $ICUT_QTY;
					$ITM_VOLM		= $ICUT_QTY;
					$ITM_PRICE		= 1;
					
					$ITM_IN			= $ITM_VOLM;
					$ITM_INP		= $ITM_IN * $ITM_PRICE;
					$ITM_TOTALP		= $ITM_VOLM * $ITM_PRICE;
					
					$itemPar 	= array('PRJCODE' 		=> $PRJCODE,
										'PRJCODE_HO' 	=> $PRJCODE_HO,
										'PRJPERIOD' 	=> $PRJPERIOD,
										'JOBCODEID' 	=> $JOBCODEID,
										'ITM_CODE'		=> $ICUT_CODE,
										'ITM_CODE_H'	=> $ITM_CODE,
										'JOBCODEID'		=> $JOBCODEID,
										'ITM_GROUP'		=> $ITM_GROUP,
										'ITM_CATEG'		=> $ITM_CATEG,
										'ITM_NAME'		=> $ITM_NAME,
										'ITM_DESC'		=> $ITM_NAME,
										'ITM_TYPE'		=> $ITM_TYPE,
										'ITM_UNIT'		=> $ITM_UNIT,
										'UMCODE'		=> $ITM_UNIT,
										'ITM_CURRENCY'	=> 'IDR',
										'ITM_PRICE'		=> $ITM_PRICE,
										'ITM_LASTP'		=> $ITM_PRICE,
										'ITM_TOTALP'	=> $ITM_TOTALP,
										'ITM_VOLMBG'	=> $ITM_VOLMBG,
										'ITM_VOLMBGR'	=> $ITM_VOLMBG,
										'ITM_VOLM'		=> $ITM_VOLM,
										'ACC_ID'		=> $ACC_ID,
										'ACC_ID_UM'		=> $ACC_ID_UM,
										'ITM_IN'		=> $ITM_IN,
										'ITM_INP'		=> $ITM_INP,
										'STATUS'		=> $STATUS,
										'ISRIB'			=> $ISRIB,
										'ITM_KIND'		=> $ITM_KIND,
										'LAST_TRXNO'	=> $ICUT_NUM,
										'LASTNO'		=> $LASTNO);
					$this->m_itemlist->add($itemPar);

					// CREATE QRCODE
						if(!is_dir('qrcodelist/'.$PRJCODE.'/'))
							echo mkdir('qrcodelist/'.$PRJCODE.'/', 0777, TRUE);

						include APPPATH.'views/phpqrcode/qrlib.php';
			    		$qrcDir   = 'qrcodelist/'.$PRJCODE.'/';

						$SN_CODE	= $ICUT_QRCN.$RN1;
						$qrc_fill   = $SN_CODE;
			          	$qrc_name   = "$SN_CODE.png";
						$SNCODE1	= $PRJCODE;
						$SNCODE2	= substr($ICUT_QRCN,-16);
						$QRC_CODEV	= "$SNCODE2 $RN1";
			          	$qrc_qlty   = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
			          	$qrc_size   = 4; //batasan 1 paling kecil, 10 paling besar
			          	$qrc_padd   = 0;
			          	QRCode::png($qrc_fill, $qrcDir.'/'.$qrc_name, $qrc_qlty, $qrc_size, $qrc_padd);

						$insSN 		= array('PRJCODE' 		=> $PRJCODE,
											'QRC_NUM' 		=> $SN_CODE,
											'QRC_CODEV'		=> $QRC_CODEV,
											'QRC_DATE'		=> date('Y-m-d'),
											'REC_FROM'		=> $ICUT_QRCN,
											'REC_DESC'		=> "induk QR: $ICUT_QRCN",
											'IR_NUM'		=> $IR_NUM,
											'IR_CODE'		=> $IR_CODE,
											'IR_CODE_REF'	=> $ICUT_NUM,
											'ITM_CODE'		=> $ITM_CODE,
											'ITM_NAME'		=> $ITM_NAME,
											'ITM_UNIT'		=> $ITM_UNIT,
											'ITM_QTY'		=> $ITM_VOLM,
											'ITM_QTY2'		=> 1,
											'QRC_PATT'		=> $RN1);
						$this->m_itemreceipt->addSN($insSN);

					// UPDATE QRCODE TO HEADER
						$sqlUPDQRC 		= "UPDATE tbl_item_cuth SET ICUT_QRC = '$SN_CODE' WHERE ICUT_NUM = '$ICUT_NUM'";
						$this->db->query($sqlUPDQRC);

			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ICUT_NUM;
				$MenuCode 		= 'MN414';
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
				$paramStat 		= array('PM_KEY' 		=> "ICUT_NUM",
										'DOC_CODE' 		=> $ICUT_NUM,
										'DOC_STAT' 		=> $ICUT_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_item_cuth");
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
			
			$url			= site_url('c_production/c_b0fm47/glcU7_9R3193/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printQR()
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ICOLL_NUM	= $_GET['id'];
		$ICOLL_NUM	= $this->url_encryption_helper->decode_url($ICOLL_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getICOLL 				= $this->m_bom->get_icoll_by_number($ICOLL_NUM)->row();
			$data['ICOLL_NUM'] 		= $getICOLL->ICOLL_NUM;
			$data['ICOLL_CODE']	 	= $getICOLL->ICOLL_CODE;
			$data['PRJCODE'] 		= $getICOLL->PRJCODE;
			$data['PRJCODE_HO'] 	= $getICOLL->PRJCODE_HO;
			$data['ICOLL_NOTES']	= $getICOLL->ICOLL_NOTES;
			$data['CUST_CODE'] 		= $getICOLL->CUST_CODE;
			$data['CUST_DESC'] 		= $getICOLL->CUST_DESC;
			$data['ICOLL_FG'] 		= $getICOLL->ICOLL_FG;
			$data['ICOLL_CREATED']	= $getICOLL->ICOLL_CREATED;
			$data['ICOLL_STAT'] 	= $getICOLL->ICOLL_STAT;
							
			$this->load->view('v_production/v_item_coll/print_qr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printQRCUT()
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$ICUT_NUM	= $_GET['id'];
		$ICUT_NUM	= $this->url_encryption_helper->decode_url($ICUT_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getICUT 			= $this->m_bom->get_icut_by_number($ICUT_NUM)->row();
			$data['ICUT_NUM'] 	= $getICUT->ICUT_NUM;
			$data['ICUT_QRC'] 	= $getICUT->ICUT_QRC;
			$data['ICUT_CODE'] 	= $getICUT->ICUT_CODE;
			$data['PRJCODE'] 	= $getICUT->PRJCODE;
			$PRJCODE			= $getICUT->PRJCODE;
			$data['PRJCODE_HO'] = $getICUT->PRJCODE_HO;
			$data['ICUT_NOTES'] = $getICUT->ICUT_NOTES;
			$data['CUST_CODE'] 	= $getICUT->CUST_CODE;
			$data['CUST_DESC'] 	= $getICUT->CUST_DESC;
			$data['ICUT_QRCN'] 	= $getICUT->ICUT_QRCN;
			$data['ICUT_REFNUM']= $getICUT->ICUT_REFNUM;
			$data['ICUT_QTY'] 	= $getICUT->ICUT_QTY;
			$data['ICUT_STAT'] 	= $getICUT->ICUT_STAT;
			$data['ICUT_CREATED'] 	= $getICUT->ICUT_CREATED;
			
			$this->load->view('v_production/v_item_cut/print_qr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function s3l4llj0() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$COLLID		= $_GET['id'];
		$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
		
		$url		= site_url('c_production/c_mr180d0c/s3l4llj0_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}
	
	function s3l4llj0_x1() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
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
			$data['cAllJO']			= $this->m_matreq->count_all_JO($PRJCODE);
			$data['vAllJO'] 		= $this->m_matreq->view_all_JO($PRJCODE)->result();
					
			$this->load->view('v_production/v_material_req/v_material_req_seljo', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getDetJO() // OK
	{
		$JO_NUM		= $_POST['JO_NUM'];
		$PRJCODE 	= '';
		$CUST_CODE 	= '';
		$CUST_DESC 	= '';
		$ITM_CODE 	= '';
		$ITM_NAME 	= '';
		$ITM_QTY 	= '';
		$ITM_UNIT 	= '';
		$IRREF_ARR	= '';
		$JOTOP 		= 0;
		$JOTOPD 	= 0;
		$sqlJOC		= "tbl_jo_header WHERE JO_NUM = '$JO_NUM'";
		$resJOC 	= $this->db->count_all($sqlJOC);
		if($resJOC > 0)
		{
			$sqlJO	= "SELECT PRJCODE, JO_NUM, JO_CODE, CUST_CODE, CUST_DESC FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM'";
			$resJO 	= $this->db->query($sqlJO)->result();
			foreach($resJO as $row) :
				$PRJCODE 	= $row->PRJCODE;
				$JO_NUM 	= $row->JO_NUM;
				$JO_CODE 	= $row->JO_CODE;
				$CUST_CODE 	= $row->CUST_CODE;
				$CUST_DESC 	= $row->CUST_DESC;
			endforeach;

			// GET FINISH GOOD
			$sqlITM	= "SELECT A.ITM_CODE, B.ITM_NAME, A.ITM_QTY, A.ITM_UNIT
						FROM tbl_jo_detail A 
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND A.PRJCODE = B.PRJCODE
						WHERE A.JO_NUM = '$JO_NUM'";
			$resITM	= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM) :
				$ITM_CODE 	= $rowITM->ITM_CODE;
				$ITM_NAME 	= $rowITM->ITM_NAME;
				$ITM_QTY 	= $rowITM->ITM_QTY;
				$ITM_UNIT 	= $rowITM->ITM_UNIT;
			endforeach;
		}
		$JODETAIL	= "$CUST_CODE~$CUST_DESC~$ITM_CODE~$ITM_NAME~$ITM_QTY~$ITM_UNIT";
		echo json_encode($JODETAIL);
		//echo json_encode($IRREF_ARR);
	}
	
	function getDetJO1() // OK
	{
		$JO_NUM		= $_POST['JO_NUM'];
		$sqlJO		= "SELECT PRJCODE, JO_NUM, JO_CODE, CUST_CODE, CUST_DESC FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM'";
		$resJO 		= $this->db->query($sqlJO)->result();
		foreach($resJO as $row) :
			$PRJCODE 	= $row->PRJCODE;
			$JO_NUM 	= $row->JO_NUM;
			$JO_CODE 	= $row->JO_CODE;
			$CUST_CODE 	= $row->CUST_CODE;
			$CUST_DESC 	= $row->CUST_DESC;
		endforeach;

		$sqlIR_1	= "SELECT IR_CODE, IR_REFER2 FROM tbl_ir_header
        				WHERE IR_SOURCE = 4 AND PRJCODE = '$PRJCODE' AND IR_STAT = 3 AND SPLCODE = '$CUST_CODE'";
        $resIR_1	= $this->db->query($sqlIR_1)->result();
        foreach($resIR_1 as $rowIR_1) :
            $IR_CODE_1		= $rowIR_1->IR_CODE;
            $IR_REFER2_1	= $rowIR_1->IR_REFER2;
            $IRREF_ARR[] 	= array("IRCODE" => $IR_CODE_1, "IRREF" => $IR_REFER2_1);
        endforeach;
		echo json_encode($IRREF_ARR);
	}

  	function get_AllDataISJ() // G
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
			 
			$columns_valid 	= array("SPLDESC",
									"IR_CODE",
									"IR_REFER2", 
									"B.WH_NAME",
									"IR_LOC",
									"IR_DATE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bom->get_AllDataISJ($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bom->get_AllDataISJL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$IR_CODE 	= $dataI['IR_CODE'];
				$IR_DATE 	= date('d M Y', strtotime($dataI['IR_DATE']));
				$SPLDESC 	= $dataI['SPLDESC'];
				$IR_REFER2 	= $dataI['IR_REFER2'];
				$WH_NAME 	= $dataI['WH_NAME'];
				$IR_LOC 	= $dataI['IR_LOC'];

				$chkBox		= "<input type='radio' name='chk' value='".$IR_REFER2."' />";

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div style='text-align:left;'>".$SPLDESC."</div>",
										  	$IR_CODE,
										  	$IR_REFER2,
										  	"<div style='text-align:left;'>".$WH_NAME."</div>",
										  	$IR_DATE);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}