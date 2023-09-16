<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 11 November 2017
 * File Name	= C_i180d0fsel.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_i180d0fsel extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
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
			$collDATA		= isset($_GET['id']);
			$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE_HO	= $EXP_COLLD[1];
			}
			else
			{
				$PRJCODE_HO	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE_HO = '$PRJCODE_HO' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
 	function index() // OK
	{
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_i180d0fsel/i180d0fdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function i180d0fdx() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN141';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_i180d0fsel/gAlL80d0finv/?id=";
			
			// GET MENU DESC
				$mnCode				= 'MN141';
				$data["MenuApp"] 	= 'MN141';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN141';
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
	
	function gAlL80d0finv() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN141';
			$data["MenuApp"] 	= 'MN141';
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
				$data["url_search"] = site_url('c_finance/c_i180d0fsel/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_invoice_selection->count_all_INV($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_invoice_selection->get_last_INV($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			
			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN141';
			$data["PRJCODE"] 	= $PRJCODE;
			$data['procURL'] 	= site_url('c_finance/c_i180d0fsel/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_finance/c_i180d0fsel/');
			$data['form_action']= site_url('c_finance/c_i180d0fsel/add_process');
			$data["countPRJ"]	= $this->m_projectlist->count_all_project($EmpID);
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($EmpID)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN141';
				$TTR_CATEG		= 'INV-ALL';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_finance/v_invoice_selection/v_invoice_selection', $data);
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
			
			$columns_valid 	= array("INV_CODE",
									"INV_DATE",
									"INV_DUEDATE",
									"SPLCODE",
									"INV_AMOUNT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_invoice_selection->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_invoice_selection->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir); 
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$INV_NUM 		= $dataI['INV_NUM'];
                $INV_CODE 		= $dataI['INV_CODE'];
                $INV_DATE 		= $dataI['INV_DATE'];
				$INV_DATEV		= date('d M Y', strtotime($INV_DATE));
                $INV_DUEDATE 	= $dataI['INV_DUEDATE'];
				$INV_DUEDATEV	= date('d M Y', strtotime($INV_DUEDATE));
                $SPLCODE 		= $dataI['SPLCODE'];
                $INV_AMOUNT 	= $dataI['INV_AMOUNT'];
                $INV_AMOUNT_PPN = $dataI['INV_AMOUNT_PPN'];
                $INV_AMOUNT_PPH = $dataI['INV_AMOUNT_PPH'];
                $INV_LISTTAXVAL	= $dataI['INV_LISTTAXVAL'];
                $INV_AMOUNT_PAID= $dataI['INV_AMOUNT_PAID'];
                $INV_NOTES 		= $dataI['INV_NOTES'];
                $INV_STAT 		= $dataI['INV_STAT'];
                $INV_PAYSTAT	= $dataI['INV_PAYSTAT'];
                $DP_AMOUNT		= $dataI['DP_AMOUNT'];
                $PINTYPE		= $dataI['PINTYPE'];

				$SPLDESC1		= '';
				$sqlS			= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 		= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLCODE1 	= $rowS->SPLCODE;
					$SPLDESC1 	= $rowS->SPLDESC;
				endforeach;
				if($SPLDESC1 == "")
				{
					$sqlS			= "SELECT CONCAT(First_Name,' ', Last_Name) AS SPLDESC
										FROM tbl_employee WHERE Emp_ID = '$SPLCODE' LIMIT 1";
					$resultS 		= $this->db->query($sqlS)->result();
					foreach($resultS as $rowS) :
						$SPLDESC1 	= $rowS->SPLDESC;
					endforeach;
				}
				
				$CollID			= "PINV~$INV_NUM~$PRJCODE";
				$secDelIcut 	= base_url().'index.php/c_finance/c_i180d0fsel/procINV/?id=';

				if($PINTYPE == 1) 				// From INV
					$procD 		= "$secDelIcut~tbl_pinv_header~tbl_pinv_detail~INV_NUM~$INV_NUM~PRJCODE~$PRJCODE";
				else
					$procD 		= "$secDelIcut~tbl_journalheader_vcash~tbl_journaldetail_vcash~JournalH_Code~$INV_NUM~proj_Code~$PRJCODE";

				$secAction		= "<input type='hidden' name='urlProc".$noU."' id='urlProc".$noU."' value='".$procD."'>
								   	<label style='white-space:nowrap'>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='procINV(".$noU.")' title='Proses'>
										<i class='glyphicon glyphicon-ok'></i>
									</a>
									</label>";

				$output['data'][] = array($secAction,
										  $INV_CODE,
										  $INV_DATEV,
										  $INV_DUEDATEV,
										  "$SPLCODE - $SPLDESC1",
										  number_format($INV_AMOUNT, 2),
										  number_format($INV_AMOUNT_PPN, 2),
										  number_format($DP_AMOUNT, 2));
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
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
			$url			= site_url('c_finance/c_i180d0fsel/gAlL80d0finv/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function add_process() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$selectedD 		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$PRJCODE 		= $this->input->post('PRJCODE');
			$colSelINV 		= $this->input->post('colSelINV');
			
			$cArray			= count(explode("~", $colSelINV));
			$INV_IDX		= explode("~", $colSelINV);
			$INV_ID0		= $INV_IDX[0];
			
			$collData		= '';
			
			if($cArray == 1)
			{
				$istextINV	= substr($INV_ID0, -2);
				if($istextINV == '-F')
					$isOPN	= 1;
				else
					$isOPN	= 0;
				
				if($isOPN == 0)
				{
					$updINV 	= "UPDATE tbl_pinv_header SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD'
									WHERE INV_NUM = '$INV_ID0'";
					$this->db->query($updINV);
				}
				else
				{
					$updINV 	= "UPDATE tbl_opn_inv SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD'
									WHERE OPNI_NUM = '$INV_ID0'";
					$this->db->query($updINV);
				}
				$collData	= $INV_ID0;	
			}
			else
			{
				$theRow		= 0;
				foreach ($INV_IDX as $valINVID) :
					$theRow		= $theRow + 1;
					$INV_ID	= $valINVID;
					if($theRow == 1)
						$collData	= $INV_ID;
					else
						$collData	= "$collData~$INV_ID";					
					
					$istextINV	= substr($INV_ID0, -2);
					
					if($istextINV == '-F')
						$isOPN	= 1;
					else
						$isOPN	= 0;
					
					if($isOPN == 0)
					{
						$updINV 	= "UPDATE tbl_pinv_header SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD'
										WHERE INV_NUM = '$INV_ID'";
						$this->db->query($updINV);
					}
					else
					{
						$updINV 	= "UPDATE tbl_opn_inv SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD'
										WHERE OPNI_NUM = '$INV_ID0'";
						$this->db->query($updINV);
					}
				endforeach;
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $collData;
				$MenuCode 		= 'MN141';
				$TTR_CATEG		= 'PROC';
				
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
			
			$url	= site_url('c_finance/c_i180d0fsel/gAlL80d0finv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function select_process() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_invoice_selection/m_invoice_selection', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
						
			$selectedD 		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$PRJCODE		= $_GET['PRJCODE'];
			$colSelINV		= $_GET['collINV'];
			
			$cArray			= count(explode("~", $colSelINV));
			$INV_IDX		= explode("~", $colSelINV);
			$INV_ID0		= $INV_IDX[0];
			
			$collData		= '';
			
			/*if($cArray == 1)
			{
				$istextINV	= substr($INV_ID0, -2);
				if($istextINV == '-F')
					$isOPN	= 1;
				else
					$isOPN	= 0;
				
				if($isOPN == 0)
				{
					$updINV 	= "UPDATE tbl_pinv_header SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD'
									WHERE INV_NUM = '$INV_ID0'";
					$this->db->query($updINV);
				}
				else
				{
					$updINV 	= "UPDATE tbl_opn_inv SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD'
									WHERE OPNI_NUM = '$INV_ID0'";
					$this->db->query($updINV);
				}
				$collData	= $INV_ID0;	
			}
			else
			{
				$theRow		= 0;
				foreach ($INV_IDX as $valINVID) :
					$theRow		= $theRow + 1;
					$INV_ID	= $valINVID;
					if($theRow == 1)
						$collData	= $INV_ID;
					else
						$collData	= "$collData~$INV_ID";					
					
					$istextINV	= substr($INV_ID0, -2);
					
					if($istextINV == '-F')
						$isOPN	= 1;
					else
						$isOPN	= 0;
					
					if($isOPN == 0)
					{
						$updINV 	= "UPDATE tbl_pinv_header SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD'
										WHERE INV_NUM = '$INV_ID'";
						$this->db->query($updINV);
					}
					else
					{
						$updINV 	= "UPDATE tbl_opn_inv SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$selectedD'
										WHERE OPNI_NUM = '$INV_ID0'";
						$this->db->query($updINV);
					}
				endforeach;
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $collData;
				$MenuCode 		= 'MN141';
				$TTR_CATEG		= 'PROC';
				
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
			}*/
			$key		= '';
			$PRJCODE	= $PRJCODE;
			$start		= 0;
			$end		= 50;
			
			$num_rows 			= $this->m_invoice_selection->count_all_INV($PRJCODE, $key);
			$data["cData"] 		= $num_rows;	 
			$data['vData']		= $this->m_invoice_selection->get_last_INV($PRJCODE, $start, $end, $key)->result();
			$vData				= $this->m_invoice_selection->get_last_INV($PRJCODE, $start, $end, $key)->result();
			
			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN141';
			$data["PRJCODE"] 	= $PRJCODE;
			$data['procURL'] 	= site_url('c_finance/c_i180d0fsel/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_finance/c_i180d0fsel/');
			$data['form_action']= site_url('c_finance/c_i180d0fsel/add_process');
			$data["countPRJ"]	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN141';
				$TTR_CATEG		= 'INV-ALL';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			return $vData;
			
			/*$url	= site_url('c_finance/c_i180d0fsel/gAlL80d0finv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));*/
			
			//echo $colSelINV;
		}
		else
		{
			redirect('login');
		}
	}
	
	function procINV() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_joselect', '', TRUE);

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];	//
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];	// JO_NUM
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];
		
		date_default_timezone_set("Asia/Jakarta");
		
		$ISSELECTED		= date('Y-m-d H:i:s');
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$INV_NUM0		= $DocNum;
		$PRJCODE		= $PrjCode;

		if($tblNameH == 'tbl_journalheader_vcash')
		{
			$updVCASH 	= "UPDATE tbl_journalheader SET GEJ_STAT_VCASH = 1 WHERE JournalH_Code = '$DocNum' AND proj_Code = '$PRJCODE'";
			$this->db->query($updVCASH);

			$updVCASH 	= "UPDATE tbl_journalheader_vcash SET GEJ_STAT_VCASH = 1 WHERE JournalH_Code = '$DocNum' AND proj_Code = '$PRJCODE'";
			$this->db->query($updVCASH);
		}
		else
		{
			$updINV 	= "UPDATE tbl_pinv_header SET selectedINV = 1, selectedBY = '$DefEmp_ID', selectedD = '$ISSELECTED'
								WHERE INV_NUM = '$INV_NUM0'";
			$this->db->query($updINV);
		}

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