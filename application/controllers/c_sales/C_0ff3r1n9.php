<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 28 November 2018
 * File Name	= c_0ff3r1n9.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_0ff3r1n9 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_sales/m_offering', '', TRUE);
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
		$this->load->model('m_sales/m_offering', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_0ff3r1n9/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
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
			
			$MenuCode 		= 'MN388';			
			$getMENU 		= $this->m_menu->get_menu($MenuCode)->row();
			$MenuName_ID 	= $getMENU->menu_name_IND;
			$MenuName_EN 	= $getMENU->menu_name_ENG;
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN388';
				$data["MenuApp"] 	= 'MN389';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN388';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_sales/c_0ff3r1n9/gl0ff3r1n9/?id=";
			
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

	function gl0ff3r1n9() // G
	{
		$this->load->model('m_sales/m_offering', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN388';
			$data["MenuCode"] 	= 'MN388';
			$data["MenuApp"] 	= 'MN389';
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
				//$data["url_search"] = site_url('c_sales/c_0ff3r1n9/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_offering->count_all_off($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_offering->get_all_off($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 	= $appName;
			$MenuCode 		= 'MN388';
			$getMENU 		= $this->m_menu->get_menu($MenuCode)->row();
			$MenuName_ID 	= $getMENU->menu_name_IND;
			$MenuName_EN 	= $getMENU->menu_name_ENG;
			$LangID 		= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= $MenuName_ID;
				$data['h2_title'] 	= '';
			}
			else
			{
				$data["h1_title"] 	= $MenuName_EN;
				$data['h2_title'] 	= '';
			}
			
			$data['addURL'] 	= site_url('c_sales/c_0ff3r1n9/a44_0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_sales/c_0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN388';
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
			
			$this->load->view('v_sales/v_offering/v_offering', $data);
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
			$url			= site_url('c_sales/c_0ff3r1n9/gl0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("OFF_NUM",
									"OFF_CODE", 
									"OFF_DATE", 
									"CUST_CODE", 
									"CCAL_CODE",
									"CREATERNM", 
									"STATDESC",
									"STATDESC",
									"STATDESC",
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
			$num_rows 		= $this->m_offering->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_offering->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$OFF_NUM		= $dataI['OFF_NUM'];
				$OFF_CODE		= $dataI['OFF_CODE'];				
				$OFF_DATE		= $dataI['OFF_DATE'];
				$OFF_DATEV		= date('d M Y', strtotime($OFF_DATE));				
				$PRJCODE		= $dataI['PRJCODE'];
				$CUST_CODE		= $dataI['CUST_CODE'];
				$CCAL_CODE		= $dataI['CCAL_CODE'];
				$BOM_CODE		= $dataI['BOM_CODE'];
				$OFF_TOTCOST	= $dataI['OFF_TOTCOST'];
				$OFF_TOTDISC	= $dataI['OFF_TOTDISC'];
				$OFF_STAT		= $dataI['OFF_STAT'];
				$CUST_DESC		= $dataI['CUST_DESC'];
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$CollID			= "$OFF_NUM~$PRJCODE";
				$secUpd			= site_url('c_sales/c_0ff3r1n9/u77_0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_sales/c_0ff3r1n9/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($OFF_NUM));
				$secDel_DOC 	= base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDelIcut~tbl_offering_h~tbl_offering_d~OFF_NUM~$OFF_NUM~PRJCODE~$PRJCODE";
                                    
				if($OFF_STAT == 1) 
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
										  "<div style='white-space:nowrap'>".$dataI['OFF_CODE']."</div>",
										  $OFF_DATEV,
										  $CUST_DESC,
										  number_format($OFF_TOTCOST, 2),
										  number_format($OFF_TOTDISC, 2),
										  $BOM_CODE,
										  "<div style='white-space:nowrap'>".$empName."</div>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_0ff3r1n9() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_offering', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN388';
			$data["MenuCode"] 	= 'MN388';
			$data["MenuApp"] 	= 'MN389';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
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
			
			$data['form_action']	= site_url('c_sales/c_0ff3r1n9/add_process');
			$cancelURL				= site_url('c_sales/c_0ff3r1n9/gl0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_offering->count_all_CUST();
			$data['vwCUST'] 		= $this->m_offering->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN388';
			$data["MenuCode"] 		= 'MN388';
			$data["MenuCode1"]		= 'MN389';
			$data['viewDocPattern'] = $this->m_offering->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN388';
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
	
			$this->load->view('v_sales/v_offering/v_offering_form', $data);
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
			$this->load->model('m_sales/m_offering', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$collDATA	= $_GET['id'];
			
			$url	= site_url('c_sales/c_0ff3r1n9/s3l4llit3m1/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llit3m1() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_sales/m_offering', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$collDATA1	= $_GET['id'];
			$collDATA1	= $this->url_encryption_helper->decode_url($collDATA1);
			$collDATA	= explode('~', $collDATA1);
			$PRJCODE	= $collDATA[0];
			$CUST_CODE	= $collDATA[1];
			
			$CUST_DESC	= '';
			$sqlCust	= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
			$resCust	= $this->db->query($sqlCust)->result();
			foreach($resCust as $rowCust) :
				$CUST_DESC	= $rowCust->CUST_DESC;
			endforeach;
			$data["CUST_CODE"] 	= $CUST_CODE;
			$data["CUST_DESC"] 	= $CUST_DESC;
			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Daftar Warna";
			}
			else
			{
				$data["h1_title"] 	= "Daftar Warna";
			}
			$data['form_action']	= site_url('c_sales/c_0ff3r1n9/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_sales/c_0ff3r1n9/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			//$data['countAllItem'] 	= $this->m_offering->count_all_item($PRJCODE);
			//$data['vwAllItem'] 		= $this->m_offering->get_all_item($PRJCODE)->result();
					
			$this->load->view('v_sales/v_offering/v_offering_sel_ccal', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_sales/m_offering', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$OFF_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN388';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$OFF_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$OFF_CODE 		= $this->input->post('OFF_CODE');
			$OFF_DATE		= date('Y-m-d',strtotime($this->input->post('OFF_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$DEPCODE 		= $this->input->post('DEPCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');
			$CCAL_NUM 		= $this->input->post('CCAL_NUM');
			$CCAL_CODE 		= $this->input->post('CCAL_CODE');
			$BOM_NUM 		= $this->input->post('BOM_NUM');
			$BOM_CODE 		= $this->input->post('BOM_CODE');
			$OFF_TOTCOST	= $this->input->post('OFF_TOTCOST');
			$OFF_TOTPPN		= $this->input->post('OFF_TOTPPN');
			$OFF_DISCP		= $this->input->post('OFF_DISCP');
			$OFF_DISC		= $this->input->post('OFF_DISC');
			$OFF_NOTES		= addslashes($this->input->post('OFF_NOTES'));
			$OFF_NOTES1		= addslashes($this->input->post('OFF_NOTES1'));
			$OFF_STAT		= $this->input->post('OFF_STAT');
			$Patt_Year		= date('Y',strtotime($this->input->post('OFF_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('OFF_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('OFF_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$AddOFF			= array('OFF_NUM' 		=> $OFF_NUM,
									'OFF_CODE' 		=> $OFF_CODE,
									'OFF_DATE' 		=> $OFF_DATE,
									'PRJCODE'		=> $PRJCODE,
									'DEPCODE'		=> $DEPCODE,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_ADDRESS'	=> $CUST_ADDRESS,
									'CCAL_NUM'		=> $CCAL_NUM,
									'CCAL_CODE'		=> $CCAL_CODE,
									'BOM_NUM'		=> $BOM_NUM,
									'BOM_CODE' 		=> $BOM_CODE,
									'OFF_NOTES' 	=> $OFF_NOTES,
									'OFF_NOTES1' 	=> $OFF_NOTES1,
									'OFF_CREATER'	=> $DefEmp_ID,
									'OFF_CREATED'	=> $OFF_CREATED,
									'OFF_STAT'		=> $OFF_STAT,
									'OFF_SOSTAT'	=> 0,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);								
			$this->m_offering->add($AddOFF);
			
			$OFF_TOTCOST	= 0;	
			$OFF_TOTDISC	= 0;
			$OFF_TOTPPN		= 0;	
			foreach($_POST['data'] as $d)
			{
				$d['OFF_NUM']	= $OFF_NUM;
				$d['OFF_CODE']	= $OFF_CODE;
				$d['OFF_DATE']	= $OFF_DATE;
				$d['OFF_STAT']	= $OFF_STAT;
				$d['DEPCODE']	= $DEPCODE;
				$d['BOM_NUM'] 	= $BOM_NUM;
				$OFFTOTCOST		= $d['OFF_COST'];
				$OFFTOTDISC		= $d['OFF_DISC'];
				$TAXPRICE1		= $d['TAXPRICE1'];
				
				$OFF_TOTCOST	= $OFF_TOTCOST + $OFFTOTCOST;
				$OFF_TOTDISC	= $OFF_TOTDISC + $OFFTOTDISC;
				$OFF_TOTPPN		= $OFF_TOTPPN + $TAXPRICE1;
				$this->db->insert('tbl_offering_d',$d);
			}
			
			$updOFFH			= array('OFF_TOTCOST'	=> $OFF_TOTCOST,
										'OFF_TOTDISC' 	=> $OFF_TOTDISC,
										'OFF_TOTPPN'	=> $OFF_TOTPPN);
			$this->m_offering->updateOFFH($OFF_NUM, $updOFFH);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('OFF_STAT');			// IF "ADD" CONDITION ALWAYS = OFF_STAT
				$parameters 	= array('DOC_CODE' 		=> $OFF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "OFF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_offering_h",// TABLE NAME
										'KEY_NAME'		=> "OFF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "OFF_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $OFF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_OFF",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_OFF_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_OFF_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_OFF_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_OFF_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_OFF_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_OFF_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "OFF_NUM",
										'DOC_CODE' 		=> $OFF_NUM,
										'DOC_STAT' 		=> $OFF_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_offering_h");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $OFF_NUM;
				$MenuCode 		= 'MN388';
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
			
			$url			= site_url('c_sales/c_0ff3r1n9/gl0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u77_0ff3r1n9() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_offering', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
			
		// GET MENU DESC
			$mnCode				= 'MN388';
			$data["MenuCode"] 	= 'MN388';
			$data["MenuApp"] 	= 'MN389';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$OFF_NUM	= $splitCode[0];
			$PRJCODE	= $splitCode[1];
			
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
			
			$data['form_action']	= site_url('c_sales/c_0ff3r1n9/update_process');
			$cancelURL				= site_url('c_sales/c_0ff3r1n9/gl0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_offering->count_all_CUSTUPD($OFF_NUM);
			$data['vwCUST'] 		= $this->m_offering->get_all_CUSTUPD($OFF_NUM)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
		
			$getOFF							= $this->m_offering->get_off_by_number($OFF_NUM)->row();
			$data['default']['OFF_NUM'] 	= $getOFF->OFF_NUM;
			$data['default']['OFF_CODE'] 	= $getOFF->OFF_CODE;
			$data['default']['OFF_DATE'] 	= $getOFF->OFF_DATE;
			$data['default']['PRJCODE'] 	= $getOFF->PRJCODE;
			$data['default']['DEPCODE'] 	= $getOFF->DEPCODE;
			$data['default']['CUST_CODE'] 	= $getOFF->CUST_CODE;
			$data['default']['CUST_ADDRESS']= $getOFF->CUST_ADDRESS;
			$data['default']['CCAL_NUM'] 	= $getOFF->CCAL_NUM;
			$data['default']['CCAL_CODE']	= $getOFF->CCAL_CODE;
			$data['default']['BOM_NUM'] 	= $getOFF->BOM_NUM;
			$data['default']['BOM_CODE'] 	= $getOFF->BOM_CODE;
			$data['default']['SO_NUM'] 		= $getOFF->SO_NUM;
			$data['default']['OFF_TOTCOST'] = $getOFF->OFF_TOTCOST;
			$data['default']['OFF_TOTDISC'] = $getOFF->OFF_TOTDISC;
			$data['default']['OFF_TOTPPN'] 	= $getOFF->OFF_TOTPPN;
			$data['default']['OFF_NOTES'] 	= $getOFF->OFF_NOTES;
			$data['default']['OFF_NOTES1'] 	= $getOFF->OFF_NOTES1;
			$data['default']['OFF_MEMO'] 	= $getOFF->OFF_MEMO;
			$data['default']['PRJNAME'] 	= $getOFF->PRJNAME;
			$data['default']['OFF_STAT'] 	= $getOFF->OFF_STAT;
			$data['default']['OFF_SOSTAT'] 	= $getOFF->OFF_SOSTAT;
			$data['default']['Patt_Year'] 	= $getOFF->Patt_Year;
			$data['default']['Patt_Month'] 	= $getOFF->Patt_Month;
			$data['default']['Patt_Date'] 	= $getOFF->Patt_Date;
			$data['default']['Patt_Number'] = $getOFF->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getOFF->OFF_NUM;
				$MenuCode 		= 'MN388';
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
			
			$this->load->view('v_sales/v_offering/v_offering_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_sales/m_offering', '', TRUE);
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
			
			$OFF_NUM 		= $this->input->post('OFF_NUM');
			$OFF_CODE 		= $this->input->post('OFF_CODE');
			$OFF_DATE		= date('Y-m-d',strtotime($this->input->post('OFF_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$DEPCODE 		= $this->input->post('DEPCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');
			$CCAL_NUM 		= $this->input->post('CCAL_NUM');
			$CCAL_CODE 		= $this->input->post('CCAL_CODE');
			$BOM_NUM 		= $this->input->post('BOM_NUM');
			$BOM_CODE 		= $this->input->post('BOM_CODE');
			$OFF_TOTCOST	= $this->input->post('OFF_TOTCOST');
			$OFF_TOTPPN		= $this->input->post('OFF_TOTPPN');
			$OFF_DISCP		= $this->input->post('OFF_DISCP');
			$OFF_DISC		= $this->input->post('OFF_DISC');
			$OFF_NOTES		= addslashes($this->input->post('OFF_NOTES'));
			//$OFF_NOTES1		= $this->input->post('OFF_NOTES1');
			$OFF_STAT		= $this->input->post('OFF_STAT');
			$Patt_Year		= date('Y',strtotime($this->input->post('OFF_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('OFF_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('OFF_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$UppOFF			= array('OFF_CODE' 		=> $OFF_CODE,
									'OFF_DATE' 		=> $OFF_DATE,
									'PRJCODE'		=> $PRJCODE,
									'DEPCODE'		=> $DEPCODE,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_ADDRESS'	=> $CUST_ADDRESS,
									'CCAL_NUM'		=> $CCAL_NUM,
									'CCAL_CODE'		=> $CCAL_CODE,
									'BOM_NUM'		=> $BOM_NUM,
									'BOM_CODE' 		=> $BOM_CODE,
									'OFF_NOTES' 	=> $OFF_NOTES,
									//'OFF_NOTES1' 	=> $OFF_NOTES1,
									'OFF_STAT'		=> $OFF_STAT,
									'OFF_SOSTAT'	=> 0,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_offering->updateOFF($OFF_NUM, $UppOFF);
			
			// UPDATE JOBDETAIL ITEM
			if($OFF_STAT == 1 || $OFF_STAT == 2)
			{
				$this->m_offering->deleteOFFDetail($OFF_NUM);
				
				$OFF_TOTCOST	= 0;
				$OFF_TOTDISC	= 0;	
				$OFF_TOTPPN		= 0;	
				foreach($_POST['data'] as $d)
				{
					$d['OFF_NUM']	= $OFF_NUM;
					$d['OFF_CODE']	= $OFF_CODE;
					$d['OFF_DATE']	= $OFF_DATE;
					$d['OFF_STAT']	= $OFF_STAT;
					$d['DEPCODE']	= $DEPCODE;
					$OFFTOTCOST		= $d['OFF_COST'];
					$OFFTOTDISC		= $d['OFF_DISC'];
					$TAXPRICE1		= $d['TAXPRICE1'];
					
					$OFF_TOTCOST	= $OFF_TOTCOST + $OFFTOTCOST;
					$OFF_TOTDISC	= $OFF_TOTDISC + $OFFTOTDISC;
					$OFF_TOTPPN		= $OFF_TOTPPN + $TAXPRICE1;
					$this->db->insert('tbl_offering_d',$d);
				}
				
				$updOFFH	= array('OFF_TOTCOST' => $OFF_TOTCOST, 'OFF_TOTDISC' => $OFF_TOTDISC, 'OFF_TOTPPN' => $OFF_TOTPPN);
				$this->m_offering->updateOFFH($OFF_NUM, $updOFFH);
				
				// UPDATE DETAIL
					$this->m_offering->updateDet($OFF_NUM, $PRJCODE, $OFF_DATE);
			}
			elseif($OFF_STAT == 6)
			{
				/*foreach($_POST['data'] as $d)
				{
					$OFF_NUM	= $d['OFF_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					//$this->m_offering->updateVolBud($OFF_NUM, $PRJCODE, $ITM_CODE); // HOLD
				}*/
			}
			elseif($OFF_STAT == 5)	// REJECTED
			{
				/*$OFF_NUM	= $d['OFF_NUM'];
				$ITM_CODE	= $d['ITM_CODE'];*/
				//$this->m_offering->updREJECT($OFF_NUM, $PRJCODE, $ITM_CODE);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = OFF_STAT
				$parameters 	= array('DOC_CODE' 		=> $OFF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "OFFL",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_offering_h",// TABLE NAME
										'KEY_NAME'		=> "OFF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "OFF_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $OFF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_OFF",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_OFF_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_OFF_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_OFF_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_OFF_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_OFF_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_OFF_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "OFF_NUM",
										'DOC_CODE' 		=> $OFF_NUM,
										'DOC_STAT' 		=> $OFF_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_offering_h");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SO_NUM;
				$MenuCode 		= 'MN388';
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
			
			$url			= site_url('c_sales/c_0ff3r1n9/gl0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		
		$url			= site_url('c_sales/c_0ff3r1n9/iN2_0xpl/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function iN2_0xpl() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN389';
				$data["MenuCode"] 	= 'MN389';
				$data["MenuApp"] 	= 'MN389';
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
			$data["secURL"] 	= "c_sales/c_0ff3r1n9/gl0ff3r1n9iN2_0x/?id=";
			
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
	
	function gl0ff3r1n9iN2_0x() // G
	{
		$this->load->model('m_sales/m_offering', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN389';
			$data["MenuCode"] 	= 'MN389';
			$data["MenuApp"] 	= 'MN389';
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
				//$data["url_search"] = site_url('c_sales/c_0ff3r1n9/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_offering->count_all_offinb($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_offering->get_all_offinb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 			= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Dokumen";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Document Approval";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['backURL'] 	= site_url('c_sales/c_0ff3r1n9/iN2_0xpl/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
						
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN389';
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
			
			$this->load->view('v_sales/v_offering/v_inb_offering', $data);
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
			$url			= site_url('c_sales/c_0ff3r1n9/gl0ff3r1n9iN2_0x/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("OFF_NUM",
									"OFF_CODE", 
									"OFF_DATE", 
									"CUST_CODE", 
									"CCAL_CODE",
									"CREATERNM", 
									"STATDESC",
									"STATDESC",
									"STATDESC",
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
			$num_rows 		= $this->m_offering->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_offering->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$OFF_NUM		= $dataI['OFF_NUM'];
				$OFF_CODE		= $dataI['OFF_CODE'];				
				$OFF_DATE		= $dataI['OFF_DATE'];
				$OFF_DATEV		= date('d M Y', strtotime($OFF_DATE));				
				$PRJCODE		= $dataI['PRJCODE'];
				$CUST_CODE		= $dataI['CUST_CODE'];
				$CCAL_CODE		= $dataI['CCAL_CODE'];
				$BOM_CODE		= $dataI['BOM_CODE'];
				$OFF_TOTCOST	= $dataI['OFF_TOTCOST'];
				$OFF_TOTDISC	= $dataI['OFF_TOTDISC'];
				$OFF_STAT		= $dataI['OFF_STAT'];
				$CUST_DESC		= $dataI['CUST_DESC'];
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$CollID			= "$OFF_NUM~$PRJCODE";
				$secUpd			= site_url('c_sales/c_0ff3r1n9/up1N2_0x/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_sales/c_0ff3r1n9/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($OFF_NUM));
				$secDel_DOC 	= base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
                                    
				if($OFF_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs'>
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
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['OFF_CODE']."</div>",
										  $OFF_DATEV,
										  $CUST_DESC,
										  number_format($OFF_TOTCOST, 2),
										  number_format($OFF_TOTDISC, 2),
										  $BOM_CODE,
										  "<div style='white-space:nowrap'>".$empName."</div>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function up1N2_0x() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_offering', '', TRUE);
		$this->load->model('m_menu/m_menu', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN389';
			$data["MenuApp"] 	= 'MN389';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
				
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$MenuCode 		= 'MN389';
		$getMENU 		= $this->m_menu->get_menu($MenuCode)->row();
		$MenuName_ID 	= $getMENU->menu_name_IND;
		$MenuName_EN 	= $getMENU->menu_name_ENG;
		$LangID 		= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$data["h1_title"] 	= $MenuName_ID;
			$data['h2_title'] 	= '';
		}
		else
		{
			$data["h1_title"] 	= $MenuName_EN;
			$data['h2_title'] 	= '';
		}
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$OFF_NUM	= $splitCode[0];
			$PRJCODE	= $splitCode[1];
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= $MenuName_ID;
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= $MenuName_EN;
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_sales/c_0ff3r1n9/update_process_inb');
			$cancelURL				= site_url('c_sales/c_0ff3r1n9/gl0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_offering->count_all_CUSTUPD($OFF_NUM);
			$data['vwCUST'] 		= $this->m_offering->get_all_CUSTUPD($OFF_NUM)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			$data['MenuCode']		= 'MN389';
		
			$getOFF							= $this->m_offering->get_off_by_number($OFF_NUM)->row();
			$data['default']['OFF_NUM'] 	= $getOFF->OFF_NUM;
			$data['default']['OFF_CODE'] 	= $getOFF->OFF_CODE;
			$data['default']['OFF_DATE'] 	= $getOFF->OFF_DATE;
			$data['default']['PRJCODE'] 	= $getOFF->PRJCODE;
			$data['default']['DEPCODE'] 	= $getOFF->DEPCODE;
			$data['default']['CUST_CODE'] 	= $getOFF->CUST_CODE;
			$data['default']['CUST_ADDRESS']= $getOFF->CUST_ADDRESS;
			$data['default']['CCAL_NUM'] 	= $getOFF->CCAL_NUM;
			$data['default']['CCAL_CODE']	= $getOFF->CCAL_CODE;
			$data['default']['BOM_CODE'] 	= $getOFF->BOM_CODE;
			$data['default']['SO_NUM'] 		= $getOFF->SO_NUM;
			$data['default']['OFF_TOTCOST'] = $getOFF->OFF_TOTCOST;
			$data['default']['OFF_TOTDISC'] = $getOFF->OFF_TOTDISC;
			$data['default']['OFF_TOTPPN'] 	= $getOFF->OFF_TOTPPN;
			$data['default']['OFF_NOTES'] 	= $getOFF->OFF_NOTES;
			$data['default']['OFF_NOTES1'] 	= $getOFF->OFF_NOTES1;
			$data['default']['OFF_MEMO'] 	= $getOFF->OFF_MEMO;
			$data['default']['PRJNAME'] 	= $getOFF->PRJNAME;
			$data['default']['OFF_STAT'] 	= $getOFF->OFF_STAT;
			$data['default']['OFF_SOSTAT'] 	= $getOFF->OFF_SOSTAT;
			$data['default']['Patt_Year'] 	= $getOFF->Patt_Year;
			$data['default']['Patt_Month'] 	= $getOFF->Patt_Month;
			$data['default']['Patt_Date'] 	= $getOFF->Patt_Date;
			$data['default']['Patt_Number'] = $getOFF->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getOFF->OFF_NUM;
				$MenuCode 		= 'MN389';
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
			
			$this->load->view('v_sales/v_offering/v_inb_offering_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // G
	{
		$this->load->model('m_sales/m_offering', '', TRUE);
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
			
			$SO_APPROVED 	= date('Y-m-d H:i:s');
			
			$OFF_NUM 		= $this->input->post('OFF_NUM');
			$OFF_CODE 		= $this->input->post('OFF_CODE');
			$OFF_DATE		= date('Y-m-d',strtotime($this->input->post('OFF_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');
			$CCAL_NUM 		= $this->input->post('CCAL_NUM');
			$CCAL_CODE 		= $this->input->post('CCAL_CODE');
			$BOM_CODE 		= $this->input->post('BOM_CODE');
			$OFF_TOTCOST	= $this->input->post('OFF_TOTCOST');
			$OFF_TOTPPN		= $this->input->post('OFF_TOTPPN');
			$OFF_DISCP		= $this->input->post('OFF_DISCP');
			$OFF_DISC		= $this->input->post('OFF_DISC');
			$OFF_NOTES		= addslashes($this->input->post('OFF_NOTES'));
			$OFF_NOTES1		= addslashes($this->input->post('OFF_NOTES1'));
			$OFF_STAT		= $this->input->post('OFF_STAT');
			
			$AH_CODE		= $OFF_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('OFF_NOTES1'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$UppOFF			= array('OFF_STAT'		=> 7,
									'OFF_NOTES1'	=> $OFF_NOTES1);
			$this->m_offering->updateOFFInb($OFF_NUM, $UppOFF);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "OFF_NUM",
										'DOC_CODE' 		=> $OFF_NUM,
										'DOC_STAT' 		=> 7,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_offering_h");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// UPDATE DETAIL STATUS
				$UppOFFD		= array('OFF_STAT'		=> 7);
				$this->m_offering->updateOFFDET($OFF_NUM, $UppOFFD);
			
			if($OFF_STAT == 3)
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
			
			if($AH_ISLAST == 1 && $OFF_STAT == 3)
			{
				$UppOFF		= array('OFF_STAT'	=> $OFF_STAT);
				$this->m_offering->updateOFFInb($OFF_NUM, $UppOFF);
			
				// UPDATE DETAIL STATUS
					$UppOFFD		= array('OFF_STAT'		=> $OFF_STAT);
					$this->m_offering->updateOFFDET($OFF_NUM, $UppOFFD);
				
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = OFF_STAT
					$parameters 	= array('DOC_CODE' 		=> $OFF_NUM,		// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "OFF",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_offering_h",// TABLE NAME
											'KEY_NAME'		=> "OFF_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "OFF_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $OFF_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_OFF",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_OFF_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_OFF_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_OFF_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_OFF_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_OFF_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_OFF_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "OFF_NUM",
											'DOC_CODE' 		=> $OFF_NUM,
											'DOC_STAT' 		=> $OFF_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_offering_h");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $OFF_NUM;
					$MenuCode 		= 'MN389';
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
			
			// UPDATE JOBDETAIL ITEM
			if($OFF_STAT == 4 || $OFF_STAT == 5 || $OFF_STAT == 6)
			{
				$UppOFF			= array('OFF_STAT'		=> $OFF_STAT,
										'OFF_NOTES1'	=> $OFF_NOTES1);
				$this->m_offering->updateOFFInb($OFF_NUM, $UppOFF);
			
				// UPDATE DETAIL STATUS
					$UppOFFD		= array('OFF_STAT'		=> $OFF_STAT);
					$this->m_offering->updateOFFDET($OFF_NUM, $UppOFFD);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "OFF_NUM",
											'DOC_CODE' 		=> $OFF_NUM,
											'DOC_STAT' 		=> $OFF_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_offering_h");
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
			
			$url			= site_url('c_sales/c_0ff3r1n9/gl0ff3r1n9iN2_0x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt180d0bdoc() // G
	{
		$this->load->model('m_sales/m_offering', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$OFF_NUM		= $_GET['id'];
		$OFF_NUM		= $this->url_encryption_helper->decode_url($OFF_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getOFF							= $this->m_offering->get_off_by_number($OFF_NUM)->row();
			$data['default']['OFF_NUM'] 	= $getOFF->OFF_NUM;
			$data['default']['OFF_CODE'] 	= $getOFF->OFF_CODE;
			$data['default']['OFF_DATE'] 	= $getOFF->OFF_DATE;
			$data['default']['PRJCODE'] 	= $getOFF->PRJCODE;
			$data['default']['DEPCODE'] 	= $getOFF->DEPCODE;
			$data['default']['CUST_CODE'] 	= $getOFF->CUST_CODE;
			$data['default']['CUST_ADDRESS']= $getOFF->CUST_ADDRESS;
			$data['default']['CCAL_NUM'] 	= $getOFF->CCAL_NUM;
			$data['default']['CCAL_CODE']	= $getOFF->CCAL_CODE;
			$data['default']['BOM_NUM'] 	= $getOFF->BOM_NUM;
			$data['default']['BOM_CODE'] 	= $getOFF->BOM_CODE;
			$data['default']['SO_NUM'] 		= $getOFF->SO_NUM;
			$data['default']['OFF_TOTCOST'] = $getOFF->OFF_TOTCOST;
			$data['default']['OFF_TOTDISC'] = $getOFF->OFF_TOTDISC;
			$data['default']['OFF_TOTPPN'] 	= $getOFF->OFF_TOTPPN;
			$data['default']['OFF_NOTES'] 	= $getOFF->OFF_NOTES;
			$data['default']['OFF_NOTES1'] 	= $getOFF->OFF_NOTES1;
			$data['default']['OFF_MEMO'] 	= $getOFF->OFF_MEMO;
			$data['default']['PRJNAME'] 	= $getOFF->PRJNAME;
			$data['default']['OFF_STAT'] 	= $getOFF->OFF_STAT;
			$data['default']['OFF_SOSTAT'] 	= $getOFF->OFF_SOSTAT;
							
			$this->load->view('v_sales/v_offering/v_offering_print', $data);
							
			//$this->load->view('blank', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}