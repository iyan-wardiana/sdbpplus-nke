<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= C_I73mc05tc4l.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_I73mc05tc4l extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_production/m_itemcal', '', TRUE);
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
		$this->load->model('m_production/m_itemcal', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_I73mc05tc4l/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN368';
				$data["MenuApp"] 	= 'MN368';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN368';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_I73mc05tc4l/glIt3mc4l/?id=";
			
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

	function glIt3mc4l() // G
	{
		$this->load->model('m_production/m_itemcal', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN368';
			$data["MenuApp"] 	= 'MN368';
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
				$data["url_search"] = site_url('c_production/c_I73mc05tc4l/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_itemcal->count_all_CCAL($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_itemcal->get_all_CCAL($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Kalkulasi Biaya";
				$data['h2_title'] 	= 'produksi';
			}
			else
			{
				$data["h1_title"] 	= "Cost Calculation";
				$data['h2_title'] 	= 'prodcution';
			}
			
			$data['addURL'] 	= site_url('c_production/c_I73mc05tc4l/a44_it3mc4l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_I73mc05tc4l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;

			$data["MenuCode"] 	= 'MN368';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN368';
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
			
			$this->load->view('v_production/v_itemcal/v_itemcal', $data);
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
			$url			= site_url('c_production/c_I73mc05tc4l/glIt3mc4l/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("CCAL_ID",
									"CCAL_CODE", 
									"BOM_CODE", 
									"CCAL_NAME",
									"CUST_DESC", 
									"CCAL_ITMPRICE",
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
			$num_rows 		= $this->m_itemcal->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_itemcal->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$CCAL_NUM 		= $dataI['CCAL_NUM'];
				$CCAL_CODE 		= $dataI['CCAL_CODE'];
				$CCAL_NAME	 	= $dataI['CCAL_NAME'];
				$CCAL_DESC 		= $dataI['CCAL_DESC'];
				$CUST_CODE 		= $dataI['CUST_CODE'];
				$CUST_DESC 		= $dataI['CUST_DESC'];
				$CCAL_STAT 		= $dataI['CCAL_STAT'];
				$PRJCODE 		= $dataI['PRJCODE'];
				$CCAL_CREATER	= $dataI['CCAL_CREATER'];
				$CCAL_CREATED	= $dataI['CCAL_CREATED'];
				$CCAL_TOTCOST	= $dataI['CCAL_TOTCOST'];
				$CCAL_ITMPRICE	= $dataI['CCAL_ITMPRICE'];
				$BOM_CODE		= $dataI['BOM_CODE'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$secUpd		= site_url('c_production/c_I73mc05tc4l/u775o_it3mc4l/?id='.$this->url_encryption_helper->encode_url($CCAL_NUM));
				$secPrint	= site_url('c_production/c_I73mc05tc4l/prnt180d0bdocro/?id='.$this->url_encryption_helper->encode_url($CCAL_NUM));
								
				$CollID		= "CCAL~$CCAL_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_ccal_header~tbl_ccal_detail~CCAL_NUM~$CCAL_NUM~PRJCODE~$PRJCODE";
                                    
				if($CCAL_STAT == 1) 
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
										  $dataI['CCAL_CODE'],
										  $BOM_CODE,
										  "$CCAL_NAME - $CCAL_DESC",
										  $CUST_DESC,
										  "<div style='white-space:nowrap'>
										  <span class='label label-success' style='font-size:12px'>". number_format($CCAL_ITMPRICE, 2) ."</span>
										   <span class='label label-warning' style='font-size:12px'>". number_format($CCAL_TOTCOST, 2) ."</span></div>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_it3mc4l() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_itemcal', '', TRUE);
		$this->load->model('m_sales/m_offering', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN368';
			$data["MenuApp"] 	= 'MN368';
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
				$data["h1_title"] 	= "Kalkulasi Biaya";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Cost Calculation";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_I73mc05tc4l/add_process');
			$cancelURL				= site_url('c_production/c_I73mc05tc4l/glIt3mc4l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_itemcal->count_all_CUSTBOM();
			$data['vwCUST'] 		= $this->m_itemcal->get_all_CUSTBOM()->result();
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN368';
			$data["MenuCode"] 		= 'MN368';
			$data['viewDocPattern'] = $this->m_itemcal->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN368';
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
	
			$this->load->view('v_production/v_itemcal/v_itemcal_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function g3t_4llB0m() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$collDATA	= $_GET['id'];
			
			$url	= site_url('c_production/c_I73mc05tc4l/g3t_4llB0m1/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function g3t_4llB0m1() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 		= $this->session->userdata('Emp_ID');
			
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
			$data['PRJCODE']	= $PRJCODE;
					
			$this->load->view('v_production/v_itemcal/v_itemcal_sel_bom', $data);
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
			$this->load->model('m_production/m_itemcal', '', TRUE);
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
			$data['form_action']	= site_url('c_production/c_I73mc05tc4l/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_production/c_I73mc05tc4l/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_itemcal->count_all_item($PRJCODE);
			$data['vwAllItem'] 		= $this->m_itemcal->get_all_item($PRJCODE)->result();
					
			$this->load->view('v_production/v_itemcal/v_itemcal_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4ll07hc() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_itemcal', '', TRUE);
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
			$data['form_action']	= site_url('c_production/c_I73mc05tc4l/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_production/c_I73mc05tc4l/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_itemcal->count_all_othc($PRJCODE);
			$data['vwAllItem'] 		= $this->m_itemcal->get_all_othc($PRJCODE)->result();
					
			$this->load->view('v_production/v_itemcal/v_itemcal_selothc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_production/m_itemcal', '', TRUE);
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
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN368';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$CCAL_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			// GET PRJPERIODE ACTIVE
				$getGPRJP 	= $this->m_updash->get_PRJPER($PRJCODE)->row();
				$PRJPERIOD	= $getGPRJP->PRJPERIOD;
			
			$CCAL_CODE 		= $this->input->post('CCAL_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$BOM_NUM 		= $this->input->post('BOM_NUM');
			$BOM_CODE 		= $this->input->post('BOM_CODE');
			$CCAL_NAME 		= $this->input->post('CCAL_NAME');
			$CCAL_DESC 		= addslashes($this->input->post('CCAL_DESC'));
			$CCAL_VOLM 		= $this->input->post('CCAL_VOLM');
			$CCAL_RMCOST	= $this->input->post('CCAL_RMCOST');
			$CCAL_OTHCOST	= $this->input->post('CCAL_OTHCOST');
			$CCAL_TOTCOST	= $this->input->post('CCAL_TOTCOST');
			$CCAL_PROFIT	= $this->input->post('CCAL_PROFIT');
			$CCAL_PROFITAM	= $this->input->post('CCAL_PROFITAM');
			$CCAL_ITMPRICE	= $this->input->post('CCAL_ITMPRICE');
			$CCAL_STAT		= $this->input->post('CCAL_STAT');

			$CUST_DESC	= '';
			$sqlCust	= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
			$resCust	= $this->db->query($sqlCust)->result();
			foreach($resCust as $rowCust) :
				$CUST_DESC	= $rowCust->CUST_DESC;
			endforeach;
			
			$AddCCAL		= array('CCAL_NUM' 		=> $CCAL_NUM,
									'CCAL_CODE' 	=> $CCAL_CODE,
									'PRJCODE'		=> $PRJCODE,
									'PRJPERIOD'		=> $PRJPERIOD,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC'		=> $CUST_DESC,
									'BOM_NUM' 		=> $BOM_NUM,
									'BOM_CODE' 		=> $BOM_CODE,
									'CCAL_NAME' 	=> $CCAL_NAME,
									'CCAL_DESC'		=> $CCAL_DESC,
									'CCAL_VOLM'		=> $CCAL_VOLM,
									'CCAL_RMCOST'	=> $CCAL_RMCOST,
									'CCAL_OTHCOST'	=> $CCAL_OTHCOST,
									'CCAL_TOTCOST'	=> $CCAL_TOTCOST,
									'CCAL_PROFIT'	=> $CCAL_PROFIT,
									'CCAL_PROFITAM'	=> $CCAL_PROFITAM,
									'CCAL_ITMPRICE'	=> $CCAL_ITMPRICE,
									'CCAL_STAT'		=> $CCAL_STAT,
									'CCAL_CREATER'	=> $DefEmp_ID,
									'CCAL_CREATED'	=> date('Y-m-d H:i:s'));
			$this->m_itemcal->add($AddCCAL);
			
			$i_fg			= 0;
			$ITM_CODECOLL	= '';
			foreach($_POST['data_fg'] as $dFG)
			{
				$i_fg				= $i_fg + 1;
				$dFG['CCAL_NUM']	= $CCAL_NUM;
				$ITM_CATEG			= $dFG['ITM_CATEG'];
				$ITM_CODE			= $dFG['ITM_CODE'];
				if($i_fg == 1)
					$ITM_CODECOLL	= $ITM_CODE;
				elseif($i_fg > 1)
					$ITM_CODECOLL	= $ITM_CODECOLL."~".$ITM_CODE;
					
				$this->db->insert('tbl_ccal_detail',$dFG);
			}
			
			if(!empty($_POST['data_rm']))
			{
				foreach($_POST['data_rm'] as $dRM)
				{
					$dRM['CCAL_NUM']	= $CCAL_NUM;
					$this->db->insert('tbl_ccal_detail',$dRM);
				}
			}
			
			if(!empty($_POST['data_oth']))
			{
				foreach($_POST['data_oth'] as $dOTH)
				{
					$dOTH['CCAL_NUM']	= $CCAL_NUM;
					$this->db->insert('tbl_ccal_detail',$dOTH);
				}
			}
			
			$UpdCCAL		= array('BOM_FG'=> $ITM_CODECOLL);
			$this->m_itemcal->updateCCAL($CCAL_NUM, $UpdCCAL);
			
			if($CCAL_STAT == 3)
			{
				$sqlUsedC	= "UPDATE tbl_bom_header SET UsedC = UsedC + 1 WHERE BOM_NUM = '$BOM_NUM'";
				$resUsedC	= $this->db->query($sqlUsedC);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('CCAL_STAT');			// IF "ADD" CONDITION ALWAYS = SO_STAT
				$parameters 	= array('DOC_CODE' 		=> $CCAL_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "CCAL",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ccal_header",	// TABLE NAME
										'KEY_NAME'		=> "CCAL_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "CCAL_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $CCAL_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_CCAL",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_CCAL_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_CCAL_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_CCAL_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_CCAL_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_CCAL_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_CCAL_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "CCAL_NUM",
										'DOC_CODE' 		=> $CCAL_NUM,
										'DOC_STAT' 		=> $CCAL_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ccal_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $CCAL_NUM;
				$MenuCode 		= 'MN368';
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
			
			$url			= site_url('c_production/c_I73mc05tc4l/glIt3mc4l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u775o_it3mc4l() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_itemcal', '', TRUE);
		$this->load->model('m_sales/m_offering', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN368';
			$data["MenuApp"] 	= 'MN368';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CCAL_NUM	= $this->url_encryption_helper->decode_url($CollID);
								
			$getBOMH				= $this->m_itemcal->get_CCAL_by_number($CCAL_NUM)->row();
			$PRJCODE				= $getBOMH->PRJCODE;
			$data["MenuCode"] 		= 'MN368';
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Kalkulasi Biaya";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Cost Calculation";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_I73mc05tc4l/update_process');
			$cancelURL				= site_url('c_production/c_I73mc05tc4l/glIt3mc4l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 				= 'MN368';
			$data["MenuCode"] 		= 'MN368';
			
			$getBOM 							= $this->m_itemcal->get_CCAL_by_number($CCAL_NUM)->row();
			$data['default']['CCAL_NUM'] 		= $getBOM->CCAL_NUM;
			$data['default']['CCAL_CODE'] 		= $getBOM->CCAL_CODE;
			$data['default']['PRJCODE'] 		= $getBOM->PRJCODE;
			$data['PRJCODE'] 					= $getBOM->PRJCODE;
			$data['default']['CUST_CODE'] 		= $getBOM->CUST_CODE;
			$data['default']['BOM_NUM'] 		= $getBOM->BOM_NUM;
			$data['default']['BOM_CODE'] 		= $getBOM->BOM_CODE;
			$data['default']['CCAL_NAME'] 		= $getBOM->CCAL_NAME;
			$data['default']['CCAL_DESC'] 		= $getBOM->CCAL_DESC;
			$data['default']['CCAL_VOLM'] 		= $getBOM->CCAL_VOLM;
			$data['default']['CCAL_RMCOST'] 	= $getBOM->CCAL_RMCOST;
			$data['default']['CCAL_OTHCOST']	= $getBOM->CCAL_OTHCOST;
			$data['default']['CCAL_TOTCOST']	= $getBOM->CCAL_TOTCOST;
			$data['default']['CCAL_PROFIT']		= $getBOM->CCAL_PROFIT;
			$data['default']['CCAL_PROFITAM']	= $getBOM->CCAL_PROFITAM;
			$data['default']['CCAL_ITMPRICE']	= $getBOM->CCAL_ITMPRICE;
			$data['default']['CCAL_STAT'] 		= $getBOM->CCAL_STAT;
			$data['default']['CCAL_CREATER'] 	= $getBOM->CCAL_CREATER;
			$data['default']['CCAL_CREATED'] 	= $getBOM->CCAL_CREATED;
			
			$data['countCUST']		= $this->m_itemcal->count_all_CUST();
			$data['vwCUST'] 		= $this->m_itemcal->get_all_CUST()->result();
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getBOM->BOM_NUM;
				$MenuCode 		= 'MN368';
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
			
			$this->load->view('v_production/v_itemcal/v_itemcal_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_production/m_itemcal', '', TRUE);
		
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
			
			$CCAL_NUM 		= $this->input->post('CCAL_NUM');
			$CCAL_CODE 		= $this->input->post('CCAL_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');			
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$BOM_NUM 		= $this->input->post('BOM_NUM');
			$BOM_CODE 		= $this->input->post('BOM_CODE');
			$CCAL_NAME 		= $this->input->post('CCAL_NAME');
			$CCAL_DESC 		= addslashes($this->input->post('CCAL_DESC'));
			$CCAL_VOLM 		= $this->input->post('CCAL_VOLM');
			$CCAL_RMCOST	= $this->input->post('CCAL_RMCOST');
			$CCAL_OTHCOST	= $this->input->post('CCAL_OTHCOST');
			$CCAL_TOTCOST	= $this->input->post('CCAL_TOTCOST');
			$CCAL_PROFIT	= $this->input->post('CCAL_PROFIT');
			$CCAL_PROFITAM	= $this->input->post('CCAL_PROFITAM');
			$CCAL_ITMPRICE	= $this->input->post('CCAL_ITMPRICE');
			$CCAL_STAT		= $this->input->post('CCAL_STAT');
			
			// GET PRJPERIODE ACTIVE
				$getGPRJP 	= $this->m_updash->get_PRJPERIODE($PRJCODE)->row();
				$PRJPERIOD	= $getGPRJP->PRJPERIOD;
			
			$CUST_DESC	= '';
			$sqlCust	= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
			$resCust	= $this->db->query($sqlCust)->result();
			foreach($resCust as $rowCust) :
				$CUST_DESC	= $rowCust->CUST_DESC;
			endforeach;
			
			$UpdCCAL		= array('CCAL_NUM' 		=> $CCAL_NUM,
									'CCAL_CODE' 	=> $CCAL_CODE,
									'PRJCODE'		=> $PRJCODE,
									'PRJPERIOD'		=> $PRJPERIOD,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC'		=> $CUST_DESC,
									'BOM_NUM' 		=> $BOM_NUM,
									'BOM_CODE' 		=> $BOM_CODE,
									'CCAL_NAME' 	=> $CCAL_NAME,
									'CCAL_DESC'		=> $CCAL_DESC,
									'CCAL_VOLM'		=> $CCAL_VOLM,
									'CCAL_RMCOST'	=> $CCAL_RMCOST,
									'CCAL_OTHCOST'	=> $CCAL_OTHCOST,
									'CCAL_TOTCOST'	=> $CCAL_TOTCOST,
									'CCAL_PROFIT'	=> $CCAL_PROFIT,
									'CCAL_PROFITAM'	=> $CCAL_PROFITAM,
									'CCAL_ITMPRICE'	=> $CCAL_ITMPRICE,
									'CCAL_STAT'		=> $CCAL_STAT);
			$this->m_itemcal->updateCCAL($CCAL_NUM, $UpdCCAL);
			
			$this->m_itemcal->deleteCCAL($CCAL_NUM);
			
			$i_fg			= 0;
			$ITM_CODECOLL	= '';
			foreach($_POST['data_fg'] as $dFG)
			{
				$i_fg				= $i_fg + 1;
				$dFG['CCAL_NUM']	= $CCAL_NUM;
				$ITM_CATEG			= $dFG['ITM_CATEG'];
				$ITM_CODE			= $dFG['ITM_CODE'];
				if($i_fg == 1)
					$ITM_CODECOLL	= $ITM_CODE;
				elseif($i_fg > 1)
					$ITM_CODECOLL	= $ITM_CODECOLL."~".$ITM_CODE;
					
				$this->db->insert('tbl_ccal_detail',$dFG);
			}
			
			if(!empty($_POST['data_rm']) )
			{
				foreach($_POST['data_rm'] as $dRM)
				{
					$dRM['CCAL_NUM']	= $CCAL_NUM;
					$this->db->insert('tbl_ccal_detail',$dRM);
				}
			}
			
			if(!empty($_POST['data_oth']))
			{
				foreach($_POST['data_oth'] as $dOTH)
				{
					$dOTH['CCAL_NUM']	= $CCAL_NUM;
					$this->db->insert('tbl_ccal_detail',$dOTH);
				}
			}
			
			$UpdCCAL		= array('BOM_FG'=> $ITM_CODECOLL);
			$this->m_itemcal->updateCCAL($CCAL_NUM, $UpdCCAL);
			
			if($CCAL_STAT == 3)
			{
				$sqlUsedC	= "UPDATE tbl_bom_header SET UsedC = UsedC + 1 WHERE BOM_NUM = '$BOM_NUM'";
				$resUsedC	= $this->db->query($sqlUsedC);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('CCAL_STAT');			// IF "ADD" CONDITION ALWAYS = SO_STAT
				$parameters 	= array('DOC_CODE' 		=> $CCAL_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "CCAL",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_ccal_header",	// TABLE NAME
										'KEY_NAME'		=> "CCAL_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "CCAL_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $CCAL_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_CCAL",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_CCAL_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_CCAL_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_CCAL_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_CCAL_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_CCAL_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_CCAL_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "CCAL_NUM",
										'DOC_CODE' 		=> $CCAL_NUM,
										'DOC_STAT' 		=> $CCAL_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_ccal_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $CCAL_NUM;
				$MenuCode 		= 'MN368';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				//$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_production/c_I73mc05tc4l/glIt3mc4l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt180d0bdocro()
	{
		$this->load->model('m_production/m_itemcal', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$SO_NUM		= $_GET['id'];
		$SO_NUM		= $this->url_encryption_helper->decode_url($SO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			/*$getSO 			= $this->m_itemcal->get_ro_by_number($SO_NUM)->row();
			
			$data['SO_NUM'] 	= $getSO->SO_NUM;
			$data['SO_CODE'] 	= $getSO->SO_CODE;
			$data['PR_CODE'] 	= $getSO->WO_CODE;
			$data['SO_DATE'] 	= $getSO->SO_DATE;
			$data['SO_DUED'] 	= $getSO->SO_DUED;
			$data['PRJCODE'] 	= $getSO->PRJCODE;
			$data['CUST_CODE'] 	= $getSO->CUST_CODE;
			$data['PR_NUM'] 	= $getSO->PR_NUM;
			$data['SO_PAYTYPE'] = $getSO->SO_PAYTYPE;
			$data['SO_TENOR'] 	= $getSO->SO_TENOR;
			$data['SO_TERM'] 	= $getSO->SO_TERM;
			$data['SO_NOTES'] 	= $getSO->SO_NOTES;
			$data['PRJNAME'] 	= $getSO->PRJNAME;
			$data['SO_STAT'] 	= $getSO->SO_STAT;
			$data['SO_RECEIVLOC']= $getSO->SO_RECEIVLOC;
			$data['SO_RECEIVCP'] = $getSO->SO_RECEIVCP;
			$data['SO_SENTROLES']= $getSO->SO_SENTROLES;
			$data['SO_REFRENS']	= $getSO->SO_REFRENS;*/
							
			//$this->load->view('v_production/v_itemcal/v_itemcal_print', $data);
			
			$this->load->view('blank', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}