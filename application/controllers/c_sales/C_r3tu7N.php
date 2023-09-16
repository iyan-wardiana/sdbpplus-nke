<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 22 Oktober 2018
 * File Name	= C_r3tu7N.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_r3tu7N extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_salesret', '', TRUE);
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
	
 	function index() // GOOD
	{
		$this->load->model('m_sales/m_salesret', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_r3tu7N/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN043';
				$data["MenuApp"] 	= 'MN044';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN043';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_sales/c_r3tu7N/gl54l35r3tu7N/?id=";
			
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

	function gl54l35r3tu7N() // GOOD
	{
		$this->load->model('m_sales/m_salesret', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN043';
				$data["MenuCode"] 	= 'MN043';
				$data["MenuApp"] 	= 'MN044';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['addURL'] 	= site_url('c_sales/c_r3tu7N/a44_sr3tu7N/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_sales/c_r3tu7N/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			//$num_rows 			= $this->m_salesret->count_all_sr($PRJCODE);
			//$data["countSR"] 	= $num_rows;
	 
			//$data['vwSR'] = $this->m_salesret->get_all_sr($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN043';
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
			
			$this->load->view('v_sales/v_salesret/v_salesret', $data);
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
			
			$columns_valid 	= array("SR_NUM",
									"SR_CODE", 
									"SR_DATE", 
									"CUST_DESC",
									"SO_CODE",
									"SN_CODE",
									"SR_NOTES", 
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
			$num_rows 		= $this->m_salesret->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_salesret->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SR_NUM 		= $dataI['SR_NUM'];
				$SR_CODE 		= $dataI['SR_CODE'];
				$SR_TYPE	 	= $dataI['SR_TYPE'];
				$SR_DATE 		= $dataI['SR_DATE'];
				$SR_DATE 		= $dataI['SR_DATE'];
				$SR_DATEV		= date('d M Y', strtotime($SR_DATE));
				$PRJCODE 		= $dataI['PRJCODE'];
				$SR_STAT 		= $dataI['SR_STAT'];
				$CUST_CODE 		= $dataI['CUST_CODE'];
				$CUST_DESC 		= $dataI['CUST_DESC'];
				$SO_CODE 		= $dataI['SO_CODE'];
				$SN_CODE 		= $dataI['SN_CODE'];
				$SR_NOTES		= $dataI['SR_NOTES'];
				$SR_TOTCOST		= $dataI['SR_TOTCOST'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$secUpd		= site_url('c_sales/c_r3tu7N/u77r3tu7N/?id='.$this->url_encryption_helper->encode_url($SR_NUM));
				$secPrint	= site_url('c_sales/c_r3tu7N/prt_r3tu7N/?id='.$this->url_encryption_helper->encode_url($SR_NUM));
				$secDel 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDel~tbl_sr_header~tbl_sr_detail~SR_NUM~$SR_NUM~PRJCODE~$PRJCODE~SR_CODE";
                                    
				if($SR_STAT == 1) 
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['SR_CODE']."</div>",
										  "<div style='white-space:nowrap'>".$SR_DATEV."</div>",
										  $CUST_DESC,
										  $SR_NOTES,
										  "<div style='white-space:nowrap'>".$dataI['SO_CODE']."</div>",
										  "<div style='white-space:nowrap'>".$dataI['SN_CODE']."</div>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_sr3tu7N() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_salesret', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'add';		
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN043';
				$data["MenuCode"] 	= 'MN043';
				$data["MenuApp"] 	= 'MN044';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_sales/c_r3tu7N/add_process');
			$cancelURL				= site_url('c_sales/c_r3tu7N/gl54l35r3tu7N/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_salesret->count_all_CUST($PRJCODE);
			$data['vwCUST'] 		= $this->m_salesret->get_all_CUST($PRJCODE)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN043';
			$data["MenuCode"] 		= 'MN043';
			$data['viewDocPattern'] = $this->m_salesret->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN043';
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
	
			$this->load->view('v_sales/v_salesret/v_salesret_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function g3t4llSn() // GOOD
	{
		$this->load->model('m_sales/m_salesret', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CUST_CODE	= $_GET['CST'];
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$data['title'] 				= $appName;
			$data['pageFrom']			= 'SR';
			$data['PRJCODE']			= $PRJCODE;
			$data['CUST_CODE']			= $CUST_CODE;
					
			$this->load->view('v_sales/v_salesret/v_selsn', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
        
	function getSNLIST($dataColl) // GOOD
	{
		$data		= explode("~", $dataColl);
		$PRJCODE	= $data[0];
		$CUST_CODE	= $data[1];
		
		$sqlSN	= "SELECT SN_NUM, SN_CODE, DATE_FORMAT(SN_DATE,'%d %b %Y') AS SN_DATE, SO_CODE FROM tbl_sn_header 
					WHERE PRJCODE  = '$PRJCODE' AND CUST_CODE = '$CUST_CODE' AND SN_STAT IN (3,6)";
		$resSN	= $this->db->query($sqlSN)->result();
		echo json_encode($resSN);
	}
	
	function s3l4llit3m() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_sales/m_salesret', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$d4t30ll	= $_GET['d4t30ll'];
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			$dtSplit	= explode("~", $d4t30ll);
			$PRJCODE	= $dtSplit[0];
			$CUST_CODE	= $dtSplit[1];
			$SN_NUM		= $dtSplit[2];
			
			$sqlSN 		= "SELECT SN_CODE, CUST_DESC, CUST_ADDRESS FROM tbl_sn_header WHERE CUST_CODE = '$CUST_CODE'";
			$resSN 		= $this->db->query($sqlSN)->result();
			foreach($resSN as $rowSN) :
				$data["SN_CODE"] 	= $rowSN->SN_CODE;
				$data["CUST_DESC"]	= $rowSN->CUST_DESC;
				$data["CUST_ADDR"]	= $rowSN->CUST_ADDRESS;		
			endforeach;
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pengiriman ke ";
			}
			else
			{
				$data["h1_title"] 	= "Shipment to ";
			}
			$data['form_action']	= site_url('c_sales/c_r3tu7N/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['CUST_CODE'] 		= $CUST_CODE;
			$data['SN_NUM'] 		= $SN_NUM;
			
			$data['countAllItem'] 	= $this->m_salesret->count_all_item($PRJCODE, $CUST_CODE, $SN_NUM);
			$data['vwAllItem'] 		= $this->m_salesret->get_all_item($PRJCODE, $CUST_CODE, $SN_NUM)->result();
					
			$this->load->view('v_sales/v_salesret/v_salesret_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // GOOD
	{
		$this->load->model('m_sales/m_salesret', '', TRUE);
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
				$MenuCode 		= 'MN043';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$SR_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$SR_CODE 		= $this->input->post('SR_CODE');
			$SR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('SR_DATE'))));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$SR_TYPE 		= $this->input->post('SR_TYPE');
			
			// CUSTOMER DETAILS
				$CUST_DESC 	= '';
				$CUST_ADD 	= '';
				$sqlCUST 	= "SELECT CUST_DESC, CUST_ADD1 FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
				$resCUST 	= $this->db->query($sqlCUST)->result();
				foreach($resCUST as $rowCUST) :
					$CUST_DESC 	= $rowCUST->CUST_DESC;
					$CUST_ADD 	= $rowCUST->CUST_ADD1;
				endforeach;
			
			$SR_NOTES 		= $this->input->post('SR_NOTES');
			$SR_CREATER		= $DefEmp_ID;
			$SR_CREATED		= date('Y-m-d H:i:s');
			$SR_STAT		= $this->input->post('SR_STAT');
			
			$Patt_Year		= date('Y',strtotime($SR_DATE));
			$Patt_Month		= date('m',strtotime($SR_DATE));
			$Patt_Date		= date('d',strtotime($SR_DATE));
			$Patt_Number	= $this->input->post('Patt_Number');

			$AddSR			= array('SR_NUM' 		=> $SR_NUM,
									'SR_CODE' 		=> $SR_CODE,
									'SR_DATE'		=> $SR_DATE,
									'SR_TYPE' 		=> $SR_TYPE,
									'PRJCODE'		=> $PRJCODE,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC'		=> $CUST_DESC,
									'CUST_ADD' 		=> $CUST_ADD,
									/*'SO_NUM'		=> $SO_NUM,
									'SO_CODE' 		=> $SO_CODE,
									'SO_DATE'		=> $SO_DATE,
									'SN_NUM'		=> $SN_NUM,
									'SN_CODE' 		=> $SN_CODE,
									'SN_DATE'		=> $SN_DATE, */
									'SR_NOTES' 		=> $SR_NOTES,
									'SR_CREATER'	=> $DefEmp_ID,
									'SR_CREATED'	=> $SR_CREATED,
									'SR_STAT'		=> $SR_STAT,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_salesret->add($AddSR); // Insert tb_po_header
			
			$SR_TOTCOST	= 0;
			$TVOLM_RET	= 0;
			foreach($_POST['dataQRC'] as $dQRC)
			{
				$SN_NUM				= $dQRC['SN_NUM'];
				$QRC_NUM			= $dQRC['QRC_NUM'];
				$QRC_VOLM_RET		= $dQRC['QRC_VOLM_RET'];
				$QRC_NOTE			= $dQRC['QRC_NOTE'];
				$TVOLM_RET 			= $TVOLM_RET + $QRC_VOLM_RET;
					
				// GET FG CODE
					$ITM_CODEFG		= "";
					$sqlFG 			= "SELECT A.ICOLL_FG FROM tbl_item_collh A 
										INNER JOIN tbl_item_colld B ON A.ICOLL_NUM = B.ICOLL_NUM WHERE B.QRC_NUM = '$QRC_NUM'";
                    $resFG 			= $this->db->query($sqlFG)->result();
                    foreach($resFG as $rowFG) :
						$ITM_CODEFG = $rowFG->ICOLL_FG;
					endforeach;

				$SRTOTVOLM			= 0;
				$SOPRICE 			= 0;
				$sqlSNDQRC 			= "SELECT * FROM tbl_sn_detail_qrc WHERE SN_NUM = '$SN_NUM' AND QRC_NUM = '$QRC_NUM'";
                $resSNDQRC 			= $this->db->query($sqlSNDQRC)->result();
                foreach($resSNDQRC as $rowSNDQRC) :
					$dQRCRT['SR_NUM']		= $SR_NUM;
					$dQRCRT['SR_CODE']		= $SR_CODE;
					$dQRCRT['SR_DATE']		= $SR_DATE;
					$dQRCRT['SN_NUM']		= $SN_NUM;
					$dQRCRT['SN_CODE']		= $rowSNDQRC->SN_CODE;
					$dQRCRT['SN_DATE']		= $rowSNDQRC->SN_DATE;
					$dQRCRT['PRJCODE']		= $PRJCODE;
					$dQRCRT['CUST_CODE']	= $rowSNDQRC->CUST_CODE;
					$dQRCRT['SO_NUM']		= $rowSNDQRC->SO_NUM;
					$dQRCRT['SO_CODE']		= $rowSNDQRC->SO_CODE;
					$dQRCRT['JO_NUM']		= $rowSNDQRC->JO_NUM;
					$JO_NUM					= $rowSNDQRC->JO_NUM;
					$dQRCRT['JO_CODE']		= $rowSNDQRC->JO_CODE;
					$dQRCRT['ITM_CODEFG']	= $ITM_CODEFG;
					$dQRCRT['ITM_CODE']		= $rowSNDQRC->ITM_CODE;
					$dQRCRT['ITM_UNIT']		= $rowSNDQRC->ITM_UNIT;
					$dQRCRT['QRC_NUM']		= $rowSNDQRC->QRC_NUM;
					$dQRCRT['QRC_CODEV']	= $rowSNDQRC->QRC_CODEV;
					$dQRCRT['QRC_VOLM']		= $rowSNDQRC->QRC_VOLM;
					$dQRCRT['QRC_VOLM_RET']	= $QRC_VOLM_RET;
					$dQRCRT['QRC_PRICE']	= $rowSNDQRC->QRC_PRICE;
					$dQRCRT['QRC_NOTE']		= $QRC_NOTE;
					$SRTOTVOLM				= $SRTOTVOLM + $rowSNDQRC->QRC_VOLM;

					$SO_NUM					= $rowSNDQRC->SO_NUM;
					$SO_CODE				= $rowSNDQRC->SO_CODE;
					$SO_CODE				= $rowSNDQRC->SO_CODE;

					$JO_CODE 				= '';
					$sqlSO					= "SELECT A.JO_CODE FROM tbl_jo_header A WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
					$resSO					= $this->db->query($sqlSO)->result();
					foreach($resSO as $rowSO) :
						$JO_CODE	= $rowSO->JO_CODE;
					endforeach;
					
					$this->db->insert('tbl_sr_detail_qrc',$dQRCRT);

					// UPDATE RETUR STATUS, 
						$sqlUPD	= "UPDATE tbl_sn_detail_qrc SET QRC_ISRET = 1, QRC_VOLM_RET = $QRC_VOLM_RET WHERE SN_NUM = '$SN_NUM' AND QRC_NUM = '$QRC_NUM'";
						$this->db->query($sqlUPD);
				endforeach;
			}

			// INSERT DETAIL
				$SR_TOTCOST	= 0;
				$SR_TOTPPN	= 0;
				$SR_TOTDISC	= 0;
				$sqlSRD 	= "SELECT DISTINCT SN_NUM FROM tbl_sr_detail_qrc WHERE SR_NUM = '$SR_NUM'";
                $resSRD 	= $this->db->query($sqlSRD)->result();
                foreach($resSRD as $rowSRD) :
					$SN_NUM		= $rowSRD->SN_NUM;

					$sqlSND 	= "SELECT A.*, B.SO_NUM, B.SO_CODE, B.SO_DATE FROM tbl_sn_detail A INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM
									WHERE A.SN_NUM = '$SN_NUM'";
	                $resSND 	= $this->db->query($sqlSND)->result();
	                foreach($resSND as $rowSND) :
						$dSRD['SR_NUM']			= $SR_NUM;
						$dSRD['SR_CODE']		= $SR_CODE;
						$dSRD['SR_DATE']		= $SR_DATE;
						$dSRD['SN_NUM']			= $SN_NUM;
						$dSRD['SN_CODE']		= $rowSND->SN_CODE;
						$dSRD['SN_DATE']		= $rowSND->SN_DATE;
						$dSRD['PRJCODE']		= $PRJCODE;
						$dSRD['CUST_CODE']		= $rowSND->CUST_CODE;
						$dSRD['ITM_CODE']		= $rowSND->ITM_CODE;
						$ITM_CODE				= $rowSND->ITM_CODE;
						$dSRD['ITM_UNIT']		= $rowSND->ITM_UNIT;
						$dSRD['SN_VOLM']		= $rowSND->SN_VOLM;


						$sqlSRD2 	= "SELECT SUM(QRC_VOLM_RET) AS TOTVOLM, SUM(QRC_VOLM_RET * QRC_PRICE) AS TOTPRICE
										FROM tbl_sr_detail_qrc WHERE SN_NUM = '$SN_NUM' AND ITM_CODEFG = '$ITM_CODE'";
		                $resSRD2 	= $this->db->query($sqlSRD2)->result();
		                foreach($resSRD2 as $rowSRD2) :
							$TOTVOLM			= $rowSRD2->TOTVOLM;
							$TOTPRICE			= $rowSRD2->TOTPRICE;
						endforeach;
						$TOTVOLMP 				= $TOTVOLM;
						if($TOTVOLM == '')
						{
							$TOTVOLM 			= 0;
							$TOTVOLMP			= 1;
						}
						if($TOTPRICE == '')
							$TOTPRICE			= 0;

						$SR_PRICE				= $TOTPRICE / $TOTVOLMP;

						$dSRD['SR_VOLM']		= $TOTVOLM;
						$dSRD['SR_PRICE']		= $SR_PRICE;
						$dSRD['SR_DISP']		= $rowSND->SN_DISP;
						$SR_DISP				= $rowSND->SN_DISP;
						$SR_DISC 				= $SR_DISP * $TOTPRICE / 100;
						$dSRD['SR_DISC']		= $SR_DISC;

						$dSRD['SR_TOTAL']		= $TOTPRICE;
						$dSRD['SR_DESC']		= $rowSND->SN_DESC;
						$dSRD['SR_DESC1']		= $rowSND->SN_DESC1;
						$dSRD['TAXCODE1']		= $rowSND->TAXCODE1;
						$dSRD['TAXCODE2']		= $rowSND->TAXCODE2;
						$dSRD['TAXPRICE1']		= $rowSND->TAXPRICE1;
						$dSRD['TAXPRICE2']		= $rowSND->TAXPRICE2;

						$SN_CODE				= $rowSND->SN_CODE;
						$SN_DATE				= $rowSND->SN_DATE;
						$SO_NUM					= $rowSND->SO_NUM;
						$SO_CODE				= $rowSND->SO_CODE;
						$SO_DATE				= $rowSND->SO_DATE;

						$SR_TOTCOST				= $SR_TOTCOST + $TOTPRICE;
						$SR_TOTPPN				= $SR_TOTPPN + $rowSND->TAXPRICE1;
						$SR_TOTDISC				= $SR_TOTDISC + $SR_DISC;

						$this->db->insert('tbl_sr_detail',$dSRD);
	                endforeach;
				endforeach;
			
			// UPDATE HEADER
				$updSRH			= array('SO_NUM' 		=> $SO_NUM,
										'SO_CODE' 		=> $SO_CODE,
										'SO_DATE' 		=> $SO_DATE,
										'JO_NUM'		=> $JO_NUM,
										'JO_CODE'		=> $JO_CODE,
										'SN_NUM'	 	=> $SN_NUM,
										'SN_CODE' 		=> $SN_CODE,
										'SN_DATE' 		=> $SN_DATE,
										'SR_TOTVOLM' 	=> $SRTOTVOLM,
										'SR_TOTCOST' 	=> $SR_TOTCOST,
										'SR_TOTPPN' 	=> $SR_TOTPPN,
										'SR_TOTDISC' 	=> $SR_TOTDISC);
				$this->m_salesret->updateSRH($SR_NUM, $updSRH);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('SR_STAT');			// IF "ADD" CONDITION ALWAYS = SR_STAT
				$parameters 	= array('DOC_CODE' 		=> $SR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "SR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_sr_header",	// TABLE NAME
										'KEY_NAME'		=> "SR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $SR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_SR",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_SR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_SR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_SR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_SR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_SR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_SR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SR_NUM;
				$MenuCode 		= 'MN043';
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
				$paramStat 		= array('PM_KEY' 		=> "SR_NUM",
										'DOC_CODE' 		=> $SR_NUM,
										'DOC_STAT' 		=> $SR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_sr_header");
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
			
			$url			= site_url('c_sales/c_r3tu7N/gl54l35r3tu7N/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u77r3tu7N() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_salesret', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$SR_NUM		= $this->url_encryption_helper->decode_url($CollID);
			
			/*$splitCode 	= explode("~", $CollID);
			$PO_NUM		= $splitCode[0];
			$ISDIRECT	= $splitCode[1];*/
								
			$getSR					= $this->m_salesret->get_sr_by_number($SR_NUM)->row();
			$PRJCODE				= $getSR->PRJCODE;
			$data["MenuCode"] 		= 'MN043';
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';		
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN043';
				$data["MenuCode"] 	= 'MN043';
				$data["MenuApp"] 	= 'MN044';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_sales/c_r3tu7N/update_process');
			$cancelURL				= site_url('c_sales/c_r3tu7N/gl54l35r3tu7N/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_salesret->count_all_CUST($PRJCODE);
			$data['vwCUST'] 		= $this->m_salesret->get_all_CUST($PRJCODE)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN043';
			$data["MenuCode"] 		= 'MN043';
			
			$data['default']['SR_NUM'] 		= $getSR->SR_NUM;
			$data['default']['SR_CODE'] 	= $getSR->SR_CODE;
			$data['default']['SR_DATE']		= $getSR->SR_DATE;
			$data['default']['SR_TYPE'] 	= $getSR->SR_TYPE;
			$data['default']['PRJCODE']		= $getSR->PRJCODE;
			$data['default']['CUST_CODE']	= $getSR->CUST_CODE;
			$data['default']['CUST_DESC']	= $getSR->CUST_DESC;
			$data['default']['CUST_ADD'] 	= $getSR->CUST_ADD;
			$data['default']['SO_NUM']		= $getSR->SO_NUM;
			$data['default']['SO_CODE'] 	= $getSR->SO_CODE;
			$data['default']['SO_DATE']		= $getSR->SO_DATE;
			$data['default']['SN_NUM']		= $getSR->SN_NUM;
			$data['default']['SN_CODE'] 	= $getSR->SN_CODE;
			$data['default']['SN_DATE']		= $getSR->SN_DATE;
			$data['default']['SR_TOTVOLM']	= $getSR->SR_TOTVOLM;
			$data['default']['SR_TOTCOST']	= $getSR->SR_TOTCOST;
			$data['default']['SR_TOTPPN']	= $getSR->SR_TOTPPN;
			$data['default']['SR_TOTDISC']	= $getSR->SR_TOTDISC;
			$data['default']['VEH_CODE']	= $getSR->VEH_CODE;
			$data['default']['VEH_NOPOL']	= $getSR->VEH_NOPOL;
			$data['default']['SN_DRIVER']	= $getSR->SN_DRIVER;
			$data['default']['SR_NOTES'] 	= $getSR->SR_NOTES;
			$data['default']['SR_NOTES1'] 	= $getSR->SR_NOTES1;
			$data['default']['SR_CREATER']	= $DefEmp_ID;
			$data['default']['SR_CREATED']	= $getSR->SR_CREATED;
			$data['default']['SR_STAT']		= $getSR->SR_STAT;
			$data['default']['Patt_Year']	= $getSR->Patt_Year; 
			$data['default']['Patt_Month'] 	= $getSR->Patt_Month;
			$data['default']['Patt_Date']	= $getSR->Patt_Date;
			$data['default']['Patt_Number']	= $getSR->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSR->SR_NUM;
				$MenuCode 		= 'MN043';
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
			
			$this->load->view('v_sales/v_salesret/v_salesret_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // GOOD
	{
		$this->load->model('m_sales/m_salesret', '', TRUE);
		
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
			
			$SR_NUM 		= $this->input->post('SR_NUM');
			$SR_CODE 		= $this->input->post('SR_CODE');
			$SR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('SR_DATE'))));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$SR_TYPE 		= $this->input->post('SR_TYPE');
			
			// CUSTOMER DETAILS
				$CUST_DESC 	= '';
				$CUST_ADD 	= '';
				$sqlCUST 	= "SELECT CUST_DESC, CUST_ADD1 FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
				$resCUST 	= $this->db->query($sqlCUST)->result();
				foreach($resCUST as $rowCUST) :
					$CUST_DESC 	= $rowCUST->CUST_DESC;
					$CUST_ADD 	= $rowCUST->CUST_ADD1;
				endforeach;
			
			$SR_NOTES 		= $this->input->post('SR_NOTES');
			$SR_CREATER		= $DefEmp_ID;
			$SR_CREATED		= date('Y-m-d H:i:s');
			$SR_STAT		= $this->input->post('SR_STAT');
			
			$Patt_Year		= date('Y',strtotime($SR_DATE));
			$Patt_Month		= date('m',strtotime($SR_DATE));
			$Patt_Date		= date('d',strtotime($SR_DATE));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$UpdSR			= array('SR_NUM' 		=> $SR_NUM,
									'SR_CODE' 		=> $SR_CODE,
									'SR_DATE'		=> $SR_DATE,
									'SR_TYPE' 		=> $SR_TYPE,
									'PRJCODE'		=> $PRJCODE,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC'		=> $CUST_DESC,
									'CUST_ADD' 		=> $CUST_ADD,
									/*'SO_NUM'		=> $SO_NUM,
									'SO_CODE' 		=> $SO_CODE,
									'SO_DATE'		=> $SO_DATE,
									'SN_NUM'		=> $SN_NUM,
									'SN_CODE' 		=> $SN_CODE,
									'SN_DATE'		=> $SN_DATE, */
									'SR_NOTES' 		=> $SR_NOTES,
									'SR_CREATER'	=> $DefEmp_ID,
									'SR_CREATED'	=> $SR_CREATED,
									'SR_STAT'		=> $SR_STAT,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date);
			$this->m_salesret->updateSRH($SR_NUM, $UpdSR);
			
			// UPDATE JOBDETAIL ITEM
			if($SR_STAT == 6)
			{
				// BUATKAN IN THE NEXT TIME
				/*foreach($_POST['data'] as $d)
				{
					$SR_NUM		= $d['SR_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					$this->m_salesret->updateVolBud($PO_NUM, $PRJCODE, $ITM_CODE);
				}*/
			}
			elseif($SR_STAT == 5)	// REJECTED
			{
				$UpdSRH	= array('SR_STAT'	=> $SR_STAT);
				$this->m_salesret->updateSRH($SR_NUM, $updSRH);
			}
			else
			{
				$this->m_salesret->deleteSRDetail($SR_NUM);
				$this->m_salesret->deleteSRDetail($SR_NUM);
				
				$SR_TOTCOST	= 0;
				$TVOLM_RET 	= 0;
				foreach($_POST['dataQRC'] as $dQRC)
				{
					$SN_NUM				= $dQRC['SN_NUM'];
					$QRC_NUM			= $dQRC['QRC_NUM'];
					$QRC_VOLM_RET		= $dQRC['QRC_VOLM_RET'];
					$QRC_NOTE			= $dQRC['QRC_NOTE'];
					$TVOLM_RET 			= $TVOLM_RET + $QRC_VOLM_RET;
					
					// GET FG CODE
						$ITM_CODEFG		= "";
						$sqlFG 			= "SELECT A.ICOLL_FG FROM tbl_item_collh A 
											INNER JOIN tbl_item_colld B ON A.ICOLL_NUM = B.ICOLL_NUM WHERE B.QRC_NUM = '$QRC_NUM'";
	                    $resFG 			= $this->db->query($sqlFG)->result();
	                    foreach($resFG as $rowFG) :
							$ITM_CODEFG = $rowFG->ICOLL_FG;
						endforeach;

					$SRTOTVOLM			= 0;
					$SOPRICE 			= 0;
					$sqlSNDQRC 			= "SELECT * FROM tbl_sn_detail_qrc WHERE SN_NUM = '$SN_NUM' AND QRC_NUM = '$QRC_NUM'";
	                $resSNDQRC 			= $this->db->query($sqlSNDQRC)->result();
	                foreach($resSNDQRC as $rowSNDQRC) :
						$dQRCRT['SR_NUM']		= $SR_NUM;
						$dQRCRT['SR_CODE']		= $SR_CODE;
						$dQRCRT['SR_DATE']		= $SR_DATE;
						$dQRCRT['SN_NUM']		= $SN_NUM;
						$dQRCRT['SN_CODE']		= $rowSNDQRC->SN_CODE;
						$dQRCRT['SN_DATE']		= $rowSNDQRC->SN_DATE;
						$dQRCRT['PRJCODE']		= $PRJCODE;
						$dQRCRT['CUST_CODE']	= $rowSNDQRC->CUST_CODE;
						$dQRCRT['SO_NUM']		= $rowSNDQRC->SO_NUM;
						$dQRCRT['SO_CODE']		= $rowSNDQRC->SO_CODE;
						$dQRCRT['JO_NUM']		= $rowSNDQRC->JO_NUM;
						$dQRCRT['JO_CODE']		= $rowSNDQRC->JO_CODE;
						$dQRCRT['ITM_CODEFG']	= $ITM_CODEFG;
						$dQRCRT['ITM_CODE']		= $rowSNDQRC->ITM_CODE;
						$dQRCRT['ITM_UNIT']		= $rowSNDQRC->ITM_UNIT;
						$dQRCRT['QRC_NUM']		= $rowSNDQRC->QRC_NUM;
						$dQRCRT['QRC_CODEV']	= $rowSNDQRC->QRC_CODEV;
						$dQRCRT['QRC_VOLM']		= $rowSNDQRC->QRC_VOLM;
						$dQRCRT['QRC_VOLM_RET']	= $QRC_VOLM_RET;
						$dQRCRT['QRC_PRICE']	= $rowSNDQRC->QRC_PRICE;
						$dQRCRT['QRC_NOTE']		= $QRC_NOTE;
						$SRTOTVOLM				= $SRTOTVOLM + $rowSNDQRC->QRC_VOLM;

						$SO_NUM					= $rowSNDQRC->SO_NUM;
						$SO_CODE				= $rowSNDQRC->SO_CODE;
						$SO_CODE				= $rowSNDQRC->SO_CODE;

						$this->db->insert('tbl_sr_detail_qrc',$dQRCRT);

						// UPDATE RETUR STATUS
							$sqlUPD	= "UPDATE tbl_sn_detail_qrc SET QRC_ISRET = 1, QRC_VOLM_RET = $QRC_VOLM_RET WHERE SN_NUM = '$SN_NUM' AND QRC_NUM = '$QRC_NUM'";
							$this->db->query($sqlUPD);
					endforeach;
				}

				// INSERT DETAIL
					$SR_TOTCOST	= 0;
					$SR_TOTPPN	= 0;
					$SR_TOTDISC	= 0;
					$sqlSRD 	= "SELECT DISTINCT SN_NUM FROM tbl_sr_detail_qrc WHERE SR_NUM = '$SR_NUM'";
	                $resSRD 	= $this->db->query($sqlSRD)->result();
	                foreach($resSRD as $rowSRD) :
						$SN_NUM		= $rowSRD->SN_NUM;

						$sqlSND 	= "SELECT A.*, B.SO_NUM, B.SO_CODE, B.SO_DATE FROM tbl_sn_detail A INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM
										WHERE A.SN_NUM = '$SN_NUM'";
		                $resSND 	= $this->db->query($sqlSND)->result();
		                foreach($resSND as $rowSND) :
							$dSRD['SR_NUM']			= $SR_NUM;
							$dSRD['SR_CODE']		= $SR_CODE;
							$dSRD['SR_DATE']		= $SR_DATE;
							$dSRD['SN_NUM']			= $SN_NUM;
							$dSRD['SN_CODE']		= $rowSND->SN_CODE;
							$dSRD['SN_DATE']		= $rowSND->SN_DATE;
							$dSRD['PRJCODE']		= $PRJCODE;
							$dSRD['CUST_CODE']		= $rowSND->CUST_CODE;
							$dSRD['ITM_CODE']		= $rowSND->ITM_CODE;
							$ITM_CODE				= $rowSND->ITM_CODE;
							$dSRD['ITM_UNIT']		= $rowSND->ITM_UNIT;
							$dSRD['SN_VOLM']		= $rowSND->SN_VOLM;

							$sqlSRD2 	= "SELECT SUM(QRC_VOLM_RET) AS TOTVOLM, SUM(QRC_VOLM_RET * QRC_PRICE) AS TOTPRICE
											FROM tbl_sr_detail_qrc WHERE SN_NUM = '$SN_NUM' AND ITM_CODEFG = '$ITM_CODE'";
			                $resSRD2 	= $this->db->query($sqlSRD2)->result();
			                foreach($resSRD2 as $rowSRD2) :
								$TOTVOLM			= $rowSRD2->TOTVOLM;
								$TOTPRICE			= $rowSRD2->TOTPRICE;
							endforeach;
							$TOTVOLMP 				= $TOTVOLM;
							if($TOTVOLM == '')
							{
								$TOTVOLM 			= 0;
								$TOTVOLMP			= 1;
							}
							if($TOTPRICE == '')
								$TOTPRICE			= 0;

							$SR_PRICE				= $TOTPRICE / $TOTVOLMP;

							$dSRD['SR_VOLM']		= $TOTVOLM;
							$dSRD['SR_PRICE']		= $SR_PRICE;
							$dSRD['SR_DISP']		= $rowSND->SN_DISP;
							$SR_DISP				= $rowSND->SN_DISP;
							$SR_DISC 				= $SR_DISP * $TOTPRICE / 100;
							$dSRD['SR_DISC']		= $SR_DISC;

							$dSRD['SR_TOTAL']		= $TOTPRICE;
							$dSRD['SR_DESC']		= $rowSND->SN_DESC;
							$dSRD['SR_DESC1']		= $rowSND->SN_DESC1;
							$dSRD['TAXCODE1']		= $rowSND->TAXCODE1;
							$dSRD['TAXCODE2']		= $rowSND->TAXCODE2;
							$dSRD['TAXPRICE1']		= $rowSND->TAXPRICE1;
							$dSRD['TAXPRICE2']		= $rowSND->TAXPRICE2;

							$SN_CODE				= $rowSND->SN_CODE;
							$SN_DATE				= $rowSND->SN_DATE;
							$SO_NUM					= $rowSND->SO_NUM;
							$SO_CODE				= $rowSND->SO_CODE;
							$SO_DATE				= $rowSND->SO_DATE;

							$SR_TOTCOST				= $SR_TOTCOST + $TOTPRICE;
							$SR_TOTPPN				= $SR_TOTPPN + $rowSND->TAXPRICE1;
							$SR_TOTDISC				= $SR_TOTDISC + $SR_DISC;

							$this->db->insert('tbl_sr_detail',$dSRD);
		                endforeach;
					endforeach;
				
				// UPDATE HEADER
					$updSRH			= array('SO_NUM' 		=> $SO_NUM,
											'SO_CODE' 		=> $SO_CODE,
											'SO_DATE' 		=> $SO_DATE,
											'SN_NUM'	 	=> $SN_NUM,
											'SN_CODE' 		=> $SN_CODE,
											'SN_DATE' 		=> $SN_DATE,
											'SR_TOTVOLM' 	=> $SRTOTVOLM,
											'SR_TOTCOST' 	=> $SR_TOTCOST,
											'SR_TOTPPN' 	=> $SR_TOTPPN,
											'SR_TOTDISC' 	=> $SR_TOTDISC);
					$this->m_salesret->updateSRH($SR_NUM, $updSRH);
			}
			
			// CEK SN IN SR DOCUMENT
				$this->m_salesret->chkSN($SR_NUM, $SN_NUM, $PRJCODE);
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('SR_STAT');			// IF "ADD" CONDITION ALWAYS = SR_STAT
				$parameters 	= array('DOC_CODE' 		=> $SR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "SR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_sr_header",	// TABLE NAME
										'KEY_NAME'		=> "SR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $SR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_SR",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_SR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_SR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_SR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_SR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_SR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_SR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SR_NUM;
				$MenuCode 		= 'MN043';
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
				$paramStat 		= array('PM_KEY' 		=> "SR_NUM",
										'DOC_CODE' 		=> $SR_NUM,
										'DOC_STAT' 		=> $SR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_sr_header");
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
			
			$url			= site_url('c_sales/c_r3tu7N/gl54l35r3tu7N/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function inbox() // GOOD
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_r3tu7N/prj180c21l_1n2/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l_1n2() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN044';
				$data["MenuApp"] 	= 'MN044';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN043';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_sales/c_r3tu7N/gl54l35r3tu7N_1n2/?id=";
			
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
	
	function gl54l35r3tu7N_1n2() // GOOD
	{
		$this->load->model('m_sales/m_salesret', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN044';
				$data["MenuCode"] 	= 'MN044';
				$data["MenuApp"] 	= 'MN044';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['backURL'] 	= site_url('c_sales/c_r3tu7N/inbox/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN044';
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
			
			$this->load->view('v_sales/v_salesret/v_inb_salesret', $data);
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
			
			$columns_valid 	= array("SR_NUM",
									"SR_CODE", 
									"SR_DATE", 
									"CUST_DESC",
									"SO_CODE",
									"SN_CODE",
									"SR_NOTES", 
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
			$num_rows 		= $this->m_salesret->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_salesret->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SR_NUM 		= $dataI['SR_NUM'];
				$SR_CODE 		= $dataI['SR_CODE'];
				$SR_TYPE	 	= $dataI['SR_TYPE'];
				$SR_DATE 		= $dataI['SR_DATE'];
				$SR_DATE 		= $dataI['SR_DATE'];
				$SR_DATEV		= date('d M Y', strtotime($SR_DATE));
				$PRJCODE 		= $dataI['PRJCODE'];
				$SR_STAT 		= $dataI['SR_STAT'];
				$CUST_CODE 		= $dataI['CUST_CODE'];
				$CUST_DESC 		= $dataI['CUST_DESC'];
				$SO_CODE 		= $dataI['SO_CODE'];
				$SN_CODE 		= $dataI['SN_CODE'];
				$SR_NOTES		= $dataI['SR_NOTES'];
				$SR_TOTCOST		= $dataI['SR_TOTCOST'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$secUpd		= site_url('c_sales/c_r3tu7N/u77r3tu7N_1n2/?id='.$this->url_encryption_helper->encode_url($SR_NUM));
				$secPrint	= site_url('c_sales/c_r3tu7N/prt_r3tu7N/?id='.$this->url_encryption_helper->encode_url($SR_NUM));
				$CollID		= "SR~$SR_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				
				$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								</label>";
								
				
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['SR_CODE']."</div>",
										  "<div style='white-space:nowrap'>".$SR_DATEV."</div>",
										  $CUST_DESC,
										  $SR_NOTES,
										  "<div style='white-space:nowrap'>".$dataI['SO_CODE']."</div>",
										  "<div style='white-space:nowrap'>".$dataI['SN_CODE']."</div>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function u77r3tu7N_1n2() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_salesret', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$SR_NUM		= $this->url_encryption_helper->decode_url($CollID);
			
			/*$splitCode 	= explode("~", $CollID);
			$PO_NUM		= $splitCode[0];
			$ISDIRECT	= $splitCode[1];*/
								
			$getSR					= $this->m_salesret->get_sr_by_number($SR_NUM)->row();
			$PRJCODE				= $getSR->PRJCODE;
			$data["MenuCode"] 		= 'MN044';
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';		
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$mnCode				= 'MN044';
				$data["MenuCode"] 	= 'MN044';
				$data["MenuApp"] 	= 'MN044';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_sales/c_r3tu7N/update_process_inb');
			$cancelURL				= site_url('c_sales/c_r3tu7N/gl54l35r3tu7N/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_salesret->count_all_CUST($PRJCODE);
			$data['vwCUST'] 		= $this->m_salesret->get_all_CUST($PRJCODE)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$data['default']['SR_NUM'] 		= $getSR->SR_NUM;
			$data['default']['SR_CODE'] 	= $getSR->SR_CODE;
			$data['default']['SR_DATE']		= $getSR->SR_DATE;
			$data['default']['SR_TYPE'] 	= $getSR->SR_TYPE;
			$data['default']['PRJCODE']		= $getSR->PRJCODE;
			$data['default']['CUST_CODE']	= $getSR->CUST_CODE;
			$data['default']['CUST_DESC']	= $getSR->CUST_DESC;
			$data['default']['CUST_ADD'] 	= $getSR->CUST_ADD;
			$data['default']['SO_NUM']		= $getSR->SO_NUM;
			$data['default']['SO_CODE'] 	= $getSR->SO_CODE;
			$data['default']['SO_DATE']		= $getSR->SO_DATE;
			$data['default']['SN_NUM']		= $getSR->SN_NUM;
			$data['default']['SN_CODE'] 	= $getSR->SN_CODE;
			$data['default']['SN_DATE']		= $getSR->SN_DATE;
			$data['default']['SR_TOTVOLM']	= $getSR->SR_TOTVOLM;
			$data['default']['SR_TOTCOST']	= $getSR->SR_TOTCOST;
			$data['default']['SR_TOTPPN']	= $getSR->SR_TOTPPN;
			$data['default']['SR_TOTDISC']	= $getSR->SR_TOTDISC;
			$data['default']['VEH_CODE']	= $getSR->VEH_CODE;
			$data['default']['VEH_NOPOL']	= $getSR->VEH_NOPOL;
			$data['default']['SN_DRIVER']	= $getSR->SN_DRIVER;
			$data['default']['SR_NOTES'] 	= $getSR->SR_NOTES;
			$data['default']['SR_NOTES1'] 	= $getSR->SR_NOTES1;
			$data['default']['SR_CREATER']	= $DefEmp_ID;
			$data['default']['SR_CREATED']	= $getSR->SR_CREATED;
			$data['default']['SR_STAT']		= $getSR->SR_STAT;
			$data['default']['Patt_Year']	= $getSR->Patt_Year; 
			$data['default']['Patt_Month'] 	= $getSR->Patt_Month;
			$data['default']['Patt_Date']	= $getSR->Patt_Date;
			$data['default']['Patt_Number']	= $getSR->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSR->SR_NUM;
				$MenuCode 		= 'MN044';
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
			
			$this->load->view('v_sales/v_salesret/v_inb_salesret_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_sales/m_salesret', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');

		date_default_timezone_set("Asia/Jakarta");
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SR_NUM 		= $this->input->post('SR_NUM');
			$SR_CODE 		= $this->input->post('SR_CODE');
			$SR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('SR_DATE'))));

			$PRJCODE 		= $this->input->post('PRJCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$SR_TYPE 		= $this->input->post('SR_TYPE');
			
			// CUSTOMER DETAILS
				$CUST_DESC 	= '';
				$CUST_ADD 	= '';
				$sqlCUST 	= "SELECT CUST_DESC, CUST_ADD1 FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
				$resCUST 	= $this->db->query($sqlCUST)->result();
				foreach($resCUST as $rowCUST) :
					$CUST_DESC 	= $rowCUST->CUST_DESC;
					$CUST_ADD 	= $rowCUST->CUST_ADD1;
				endforeach;
			
			$SR_NOTES 		= $this->input->post('SR_NOTES');
			$SR_APPROVER	= $DefEmp_ID;
			$SR_APPROVED	= date('Y-m-d H:i:s');
			$SR_STAT		= $this->input->post('SR_STAT');
			
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $SR_NUM;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $SR_APPROVER;
				$AH_APPROVED	= $SR_APPROVED;
				$AH_NOTES		= $this->input->post('SR_NOTES1');
				$AH_ISLAST		= $this->input->post('IS_LAST');
				// Karena dengan adanya regulasi Persetujuan berdasarkan jumlah Total Pembelian, maka siapa yang bisa meng-approve,
				// maka itu pasti Last Step.
				
				if($SR_STAT == 3)
				{
					// START : SAVE APPROVE HISTORY
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$insAppHist 	= array('AH_CODE'		=> $SR_NUM,
												'AH_APPLEV'		=> $AH_APPLEV,
												'AH_APPROVER'	=> $AH_APPROVER,
												'AH_APPROVED'	=> $AH_APPROVED,
												'AH_NOTES'		=> $AH_NOTES,
												'PRJCODE' 		=> $PRJCODE,
												'AH_ISLAST'		=> $AH_ISLAST);										
						$this->m_updash->insAppHist($insAppHist);
					// END : SAVE APPROVE HISTORY

					$updSRH 	= array('SR_NOTES1'	=> $this->input->post('SR_NOTES1'), 'SR_STAT'	=> 7);

					// START : UPDATE STATUS
						$this->load->model('m_updash/m_updash', '', TRUE);
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "SR_NUM",
												'DOC_CODE' 		=> $SR_NUM,
												'DOC_STAT' 		=> 7,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_sr_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
			
					if($AH_ISLAST == 1)
					{
						// CARI SETTINGAN AKUN UNTUK MATERIAL PENERIMAAN DARI RETUR
							$sqlL_		= "SELECT ACC_ID_IRRET, ACC_ID_SALRET FROM tglobalsetting";
							$resL_ 		= $this->db->query($sqlL_)->result();					
							foreach($resL_ as $rowL_):
								$ACC_IRRET	= $rowL_->ACC_ID_IRRET;
								$ACC_SALRET	= $rowL_->ACC_ID_SALRET;
							endforeach;

							if($ACC_IRRET == '')
								$ACC_IRRET 	= '9X_IRRET';
							if($ACC_SALRET == '')
								$ACC_SALRET = '9X_SALRET';

						// UPDATE STATUS
							$updSRH 	= array('SR_APPROVER'	=> $SR_APPROVER,
												'SR_APPROVED'	=> $SR_APPROVED,
												'SR_NOTES1'		=> $this->input->post('SR_NOTES1'),
												'SR_STAT'		=> $this->input->post('SR_STAT'));
							$this->m_salesret->updateSRH($SR_NUM, $updSRH);

							// START : UPDATE STATUS
								$this->load->model('m_updash/m_updash', '', TRUE);
								$completeName 	= $this->session->userdata['completeName'];
								$paramStat 		= array('PM_KEY' 		=> "SR_NUM",
														'DOC_CODE' 		=> $SR_NUM,
														'DOC_STAT' 		=> $SR_STAT,
														'PRJCODE' 		=> $PRJCODE,
														//'CREATERNM'		=> '',
														'TBLNAME'		=> "tbl_sr_header");
								$this->m_updash->updateStatus($paramStat);
							// END : UPDATE STATUS

						// START : UPDATE DEATIL
							$SR_TOTCOST	= 0;
							$TVOLM_RET 	= 0;
							foreach($_POST['dataQRC'] as $dQRC)
							{
								$SN_NUM			= $dQRC['SN_NUM'];
								$QRC_NUM		= $dQRC['QRC_NUM'];
								$QRC_VOLM_RET	= $dQRC['QRC_VOLM_RET'];
								$TVOLM_RET 		= $TVOLM_RET + $QRC_VOLM_RET;

								$SRTOTVOLM		= 0;
								$SOPRICE 		= 0;
								$sqlSNDQRC 		= "SELECT * FROM tbl_sn_detail_qrc WHERE SN_NUM = '$SN_NUM' AND QRC_NUM = '$QRC_NUM'";
				                $resSNDQRC 		= $this->db->query($sqlSNDQRC)->result();
				                foreach($resSNDQRC as $rowSNDQRC) :
									$ITM_CODE	= $rowSNDQRC->ITM_CODEFG;
									$SR_VOLM	= $rowSNDQRC->QRC_VOLM;
									$SR_PRICE	= $rowSNDQRC->QRC_PRICE;
									$ITM_UNIT	= $rowSNDQRC->ITM_UNIT;
									$SR_TOTAL	= $SR_VOLM * $SR_PRICE;

									$this->m_salesret->updateSNDET($PRJCODE, $SN_NUM, $ITM_CODE, $SR_VOLM, $SR_PRICE, $SR_TOTAL);
								endforeach;

								// UPDATE RETUR QRC STATUS
									$sqlUPD	= "UPDATE tbl_sn_detail_qrc SET QRC_ISRET = 1, QRC_VOLM_RET = $QRC_VOLM_RET WHERE SN_NUM = '$SN_NUM' AND QRC_NUM = '$QRC_NUM'";
									$this->db->query($sqlUPD);

								// UPDATE QRC STATUS
									$sqlUPD	= "UPDATE tbl_qrc_detail SET GRP_CODE = '', JO_CREATED = 0, JO_NUM = '', JO_CODE = '', SN_NUM = '', SN_CODE = '',
													QRC_SOPRC = 0, QRC_PRODPRC = 0, PROD_STEP = 0, QRC_STAT = 0, QRC_STATU = 0, PROD_STAT = 0
												WHERE QRC_NUM = '$QRC_NUM'";
									$this->db->query($sqlUPD);

								$TOHARGA_ITM 	= $QRC_VOLM_RET * $SR_PRICE;
								$SR_TOTCOST 	= $SR_TOTCOST + $TOHARGA_ITM;

								// START : JOURNAL DETAIL DEBET
									// JOURNAL RETUR PENJUALAN
									/*		INVENTORY / PERSEDIAAN			XXX
												RETUR PENJUALAN						XXX

										JIKA DIGANTI DENGAN BARANG BARU
											RETURN PENJUALAN				XXX
												INVENTORY / PERSEDIAAN				XXX
									*/
									
									$ITM_CODE 		= $ITM_CODE;
									$ITM_NAME 		= "";
									$JOBCODEID 		= "";
									$ACC_ID 		= $ACC_IRRET;
									$ITM_UNIT 		= $ITM_UNIT;
									$ITM_GROUP 		= 'M';
									$ITM_TYPE 		= '';
									$ITM_QTY 		= $QRC_VOLM_RET;
									$ITM_PRICE 		= $SR_PRICE;
									$ITM_DISC 		= 0;
									$ITM_DISCX 		= 0;
									$TAXCODE1 		= '';
									$TAXPRICE1 		= 0;			
									$JournalH_Code	= $SR_NUM;
									$JournalType	= 'SALRET';
									$JournalH_Date	= $SR_DATE;
									$Company_ID		= $comp_init;
									$Currency_ID	= 'IDR';
									$LastUpdate		= $SR_APPROVED;
									$WH_CODE		= $PRJCODE;
									$Refer_Number	= $SN_NUM;
									$RefType		= 'SALRET';
									$JSource		= 'SALRET';
									$PRJCODE		= $PRJCODE;
									$TOT_PRICE		= $ITM_QTY * $ITM_PRICE;
									
									$parameters = array('JournalH_Code' 	=> $JournalH_Code,
														'JournalType'		=> $JournalType,
														'JournalH_Date' 	=> $JournalH_Date,
														'Company_ID' 		=> $Company_ID,
														'Currency_ID' 		=> $Currency_ID,
														'Source'			=> $SN_NUM,
														'Emp_ID'			=> $DefEmp_ID,
														'LastUpdate'		=> $LastUpdate,	
														'KursAmount_tobase'	=> 1,
														'WHCODE'			=> $WH_CODE,
														'Reference_Number'	=> $Refer_Number,
														'RefType'			=> $RefType,
														'PRJCODE'			=> $PRJCODE,
														'JSource'			=> $JSource,
														'TRANS_CATEG' 		=> 'SALRET',			// SALRET = SALES RETUR
														'ITM_CODE' 			=> $ITM_CODE,
														'ACC_ID' 			=> $ACC_IRRET,
														'ITM_UNIT' 			=> $ITM_UNIT,
														'ITM_GROUP' 		=> $ITM_GROUP,
														'ITM_TYPE' 			=> $ITM_TYPE,
														'ITM_QTY' 			=> $ITM_QTY,
														'ITM_PRICE' 		=> $ITM_PRICE,
														'ITM_DISC' 			=> $ITM_DISC,
														'TAXCODE1' 			=> $TAXCODE1,
														'TAXPRICE1' 		=> $TAXPRICE1,
														'JOBCODEID'			=> $JOBCODEID,
														'Notes'				=> 'Sales Retur',
														'Other_Desc'		=> $QRC_NUM);
									$this->m_journal->createJournalD($JournalH_Code, $parameters);
								// END : JOURNAL DETAIL DEBET
							}

							$TOTVOLM 		= 1;
							$TOTCOST 		= $SR_TOTCOST;
						// END : UPDATE DEATIL

						// START : JOURNAL DETAIL KREDIT - AKMULUASI DARI TOTAL MASUK KE DEBET
							// JOURNAL RETUR PENJUALAN
							/*		INVENTORY / PERSEDIAAN			XXX
										RETUR PENJUALAN						XXX

								JIKA DIGANTI DENGAN BARANG BARU
									RETURN PENJUALAN				XXX
										INVENTORY / PERSEDIAAN				XXX
							*/

							// CARI SETTINGAN AKUN UNTUK MATERIAL PENERIMAAN DARI RETUR
								$sqlL_		= "SELECT ACC_ID_IRRET, ACC_ID_SALRET FROM tglobalsetting";
								$resL_ 		= $this->db->query($sqlL_)->result();					
								foreach($resL_ as $rowL_):
									$ACC_IRRET	= $rowL_->ACC_ID_IRRET;
									$ACC_SALRET	= $rowL_->ACC_ID_SALRET;
								endforeach;

								if($ACC_IRRET == '')
									$ACC_IRRET 	= '9X_IRRET';
								if($ACC_SALRET == '')
									$ACC_SALRET = '9X_SALRET';

							$ITM_CODE 		= $ITM_CODE;
							$ITM_NAME 		= "";
							$JOBCODEID 		= "";
							$ACC_ID 		= $ACC_SALRET;
							$ITM_UNIT 		= $ITM_UNIT;
							$ITM_GROUP 		= 'M';
							$ITM_TYPE 		= '';
							$ITM_QTY 		= $TOTVOLM;
							$ITM_PRICE 		= $TOTCOST;
							$ITM_DISC 		= 0;
							$ITM_DISCX 		= 0;
							$TAXCODE1 		= '';
							$TAXPRICE1 		= 0;			
							$JournalH_Code	= $SR_NUM;
							$JournalType	= 'SALRET-K';
							$JournalH_Date	= $SR_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$LastUpdate		= $SR_APPROVED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $SN_NUM;
							$RefType		= 'SALRET-K';
							$JSource		= 'SALRET-K';
							$PRJCODE		= $PRJCODE;
							$TOT_PRICE		= $ITM_QTY * $ITM_PRICE;
							
							$parameters = array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Currency_ID' 		=> $Currency_ID,
												'Source'			=> $SN_NUM,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $Refer_Number,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'JSource'			=> $JSource,
												'TRANS_CATEG' 		=> 'SALRET-K',			// SALRET-K = SALES RETUR KREDIT SIDE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_SALRET,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_GROUP' 		=> $ITM_GROUP,
												'ITM_TYPE' 			=> $ITM_TYPE,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'JOBCODEID'			=> $JOBCODEID,
												'Notes'				=> 'Sales Retur',
												'Other_Desc'		=> "");
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						// END : JOURNAL DETAIL DEBET

						$SO_CODE 		= "";
						$CUSTCODE 		= "";
						$CUSTDESC 		= "";
						$sqlSO 			= "SELECT SO_CODE, CUST_CODE, CUST_DESC FROM tbl_sn_header WHERE SN_NUM = '$SN_NUM'";
						$resSO			= $this->db->query($sqlSO)->result();
						foreach($resSO as $rowSO):
							$SO_CODE	= $rowSO->SO_CODE;
							$CUSTCODE	= $rowSO->CUST_CODE;
							$CUSTDESC	= $rowSO->CUST_DESC;
						endforeach;

						// START : JOURNAL HEADER
							$JournalH_Code	= $SR_NUM;
							$JournalType	= 'SALRET';			// Sles Return
							$JournalH_Date	= $SR_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $SN_NUM;
							$LastUpdate		= $SR_APPROVED;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $SO_CODE;
							$RefType		= 'RETURN';
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
												'CUSTCODE'			=> $CUSTCODE,
												'CUSTDESC'			=> $CUSTDESC,
												'PRJCODE'			=> $PRJCODE);
							$this->m_journal->createJournalH_SALRET($JournalH_Code, $parameters);
						// END : JOURNAL HEADER
						
						// START : UPDATE TO TRANS-COUNT
							$this->load->model('m_updash/m_updash', '', TRUE);
					
							$STAT_BEFORE	= $this->input->post('SR_STAT');			// IF "ADD" CONDITION ALWAYS = SR_STAT
							$parameters 	= array('DOC_CODE' 		=> $SR_NUM,			// TRANSACTION CODE
													'PRJCODE' 		=> $PRJCODE,		// PROJECT
													'TR_TYPE'		=> "SR",			// TRANSACTION TYPE
													'TBL_NAME' 		=> "tbl_sr_header",	// TABLE NAME
													'KEY_NAME'		=> "SR_NUM",		// KEY OF THE TABLE
													'STAT_NAME' 	=> "SR_STAT",		// NAMA FIELD STATUS
													'STATDOC' 		=> $SR_STAT,		// TRANSACTION STATUS
													'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
													'FIELD_NM_ALL'	=> "TOT_SR",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
													'FIELD_NM_N'	=> "TOT_SR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
													'FIELD_NM_C'	=> "TOT_SR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
													'FIELD_NM_A'	=> "TOT_SR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
													'FIELD_NM_R'	=> "TOT_SR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
													'FIELD_NM_RJ'	=> "TOT_SR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
													'FIELD_NM_CL'	=> "TOT_SR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
							$this->m_updash->updateDashData($parameters);
						// END : UPDATE TO TRANS-COUNT
					}
				}
				else
				{
					$updSRH 	= array('SR_NOTES1'	=> $this->input->post('SR_NOTES1'), 'SR_STAT'	=> $SR_STAT);

					// START : UPDATE STATUS
						$this->load->model('m_updash/m_updash', '', TRUE);
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "SR_NUM",
												'DOC_CODE' 		=> $SR_NUM,
												'DOC_STAT' 		=> $SR_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_sr_header");
						$this->m_updash->updateStatus($paramStat);
				}
				$this->m_salesret->updateSRH($SR_NUM, $updSRH);
			// END : SAVE APPROVE HISTORY

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SR_NUM;
				$MenuCode 		= 'MN044';
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
			
			$url			= site_url('c_sales/c_r3tu7N/gl54l35r3tu7N_1n2/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt180d0bdoc()
	{
		$this->load->model('m_sales/m_salesret', '', TRUE);
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
			
			$getPO 			= $this->m_salesret->get_PO_by_number($PO_NUM)->row();
			
			$data['PO_NUM'] 	= $getPO->PO_NUM;
			$data['PO_CODE'] 	= $getPO->PO_CODE;
			$data['PR_CODE'] 	= $getPO->PR_CODE;
			$data['PO_DATE'] 	= $getPO->PO_DATE;
			$data['PO_DUED'] 	= $getPO->PO_DUED;
			$data['PRJCODE'] 	= $getPO->PRJCODE;
			$data['SPLCODE'] 	= $getPO->SPLCODE;
			$data['PR_NUM'] 	= $getPO->PR_NUM;
			$data['PO_PAYTYPE'] = $getPO->PO_PAYTYPE;
			$data['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['PO_TERM'] 	= $getPO->PO_TERM;
			$data['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['PRJNAME'] 	= $getPO->PRJNAME;
			$data['SR_STAT'] 	= $getPO->SR_STAT;
			$data['PO_RECEIVLOC']= $getPO->PO_RECEIVLOC;
			$data['PO_RECEIVCP'] = $getPO->PO_RECEIVCP;
			$data['PO_SENTROLES']= $getPO->PO_SENTROLES;
			$data['PO_REFRENS']	= $getPO->PO_REFRENS;
							
			$this->load->view('v_sales/v_so/print_po', $data);
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

			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			
			$data['title'] 			= $appName;
			$data['PRJCODE'] 		= $PRJCODE;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar QRC";
			}
			else
			{
				$data["h2_title"] 	= "QRC List";
			}
			
			$this->load->view('v_sales/v_salesret/v_salesret_selectqr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}