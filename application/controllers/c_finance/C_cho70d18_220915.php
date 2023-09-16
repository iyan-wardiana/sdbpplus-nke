<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 28 April 2018
	* File Name		= C_cho70d18.php
	* Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_cho70d18 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
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
		
			//$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
 	function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_cho70d18/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
 	function cho() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$jrnType 		= "CHO";
		
		$url			= site_url('c_finance/c_cho70d18/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($jrnType));
		redirect($url);
	}
	
 	function cprj() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$jrnType 		= "CPRJ";
		
		$url			= site_url('c_finance/c_cho70d18/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($jrnType));
		redirect($url);
	}
	
 	function vcash() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$jrnType 		= "VCASH";
		
		$url			= site_url('c_finance/c_cho70d18/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($jrnType));
		redirect($url);
	}
	
	function prj_l15t4ll() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$jrnType	= $_GET['id'];
			$jrnType	= $this->url_encryption_helper->decode_url($jrnType);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			if($jrnType == 'CHO')
			{
				$mnCode				= 'MN359';
				$data["MenuCode"] 	= 'MN359';
				$data["MenuApp"] 	= 'MN359';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			}
			elseif($jrnType == 'VCASH')
			{
				$mnCode				= 'MN045';
				$data["MenuCode"] 	= 'MN045';
				$data["MenuApp"] 	= 'MN045';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			}
			elseif($jrnType == 'CPRJ')
			{
				$mnCode				= 'MN147';
				$data["MenuCode"] 	= 'MN147';
				$data["MenuApp"] 	= 'MN147';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			}
			elseif($jrnType == 'JRNREV')
			{
				$mnCode				= 'MN410';
				$data["MenuCode"] 	= 'MN410';
				$data["MenuApp"] 	= 'MN410';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			}
			elseif($jrnType == 'JREVISION')
			{
				$mnCode				= 'MN058';
				$data["MenuCode"] 	= 'MN058';
				$data["MenuApp"] 	= 'MN058';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			}
			else
			{
				$mnCode				= 'MN147';
				$data["MenuCode"] 	= 'MN147';
				$data["MenuApp"] 	= 'MN147';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			}

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["jrnType"] 	= $jrnType;
			$data["countPRJ"] 	= $num_rows;
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_finance/c_cho70d18/cp2b0d18_all/?id=";
			
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

	function cp2b0d18_all() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$EmpID 			= $this->session->userdata('Emp_ID');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			// -------------------- START : SEARCHING METHOD --------------------
				// $chg_url		= 'c_finance/c_cho70d18/cp2b0d18_all'
				
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
					$end		= 100;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_finance/c_cho70d18/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_cho_payment->count_all_GEJ($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_cho_payment->get_all_GEJ($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$jrnType			= $_GET['jrnType'];

			$data['jrnType'] 	= $jrnType;
			$collData 			= "$PRJCODE~$jrnType";
			$data['addURL'] 	= site_url('c_finance/c_cho70d18/adda70d18/?id='.$this->url_encryption_helper->encode_url($collData));
			$data['PRJCODE'] 	= $PRJCODE;
			
			if($jrnType == 'CHO')
			{
				$mnCode				= 'MN359';
				$data["MenuCode"] 	= 'MN359';
				$data["MenuApp"] 	= 'MN359';
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
					$MenuCode 		= 'MN359';
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

				$data['backURL'] 	= site_url('c_finance/c_cho70d18/cho/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$this->load->view('v_finance/v_cho_payment/v_cho_payment', $data);
			}
			elseif($jrnType == 'VCASH')
			{
				$mnCode				= 'MN045';
				$data["MenuCode"] 	= 'MN045';
				$data["MenuApp"] 	= 'MN045';
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
					$MenuCode 		= 'MN045';
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

				$data['backURL'] 	= site_url('c_finance/c_cho70d18/vcash/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$this->load->view('v_finance/v_cho_payment/v_cho_vcash', $data);
			}
			elseif($jrnType == 'CPRJ')
			{
				$mnCode				= 'MN147';
				$data["MenuCode"] 	= 'MN147';
				$data["MenuApp"] 	= 'MN147';
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
					$MenuCode 		= 'MN045';
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

				$data['backURL'] 	= site_url('c_finance/c_cho70d18/cprj/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				if($EmpID = 'D15040004221')
				{
					$this->load->view('v_finance/v_cho_payment/v_cho_cprj', $data);
				}
				else
				{
					$this->load->view('v_finance/v_cho_payment/v_cho_cprj', $data);
					//$this->load->view('dashboard1_uc', $data);
				}
			}
			elseif($jrnType == 'JRNREV')
			{
				$mnCode				= 'MN410';
				$data["MenuCode"] 	= 'MN410';
				$data["MenuApp"] 	= 'MN410';
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
					$MenuCode 		= 'MN410';
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

				$data['backURL'] 	= site_url('c_finance/c_cho70d18/jrnrev/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$this->load->view('v_finance/v_cho_payment/v_cho_jrnrev', $data);
			}
			elseif($jrnType == 'JREVISION')
			{
				$mnCode				= 'MN058';
				$data["MenuCode"] 	= 'MN058';
				$data["MenuApp"] 	= 'MN058';
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
					$MenuCode 		= 'MN058';
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

				$data['backURL'] 	= site_url('c_finance/c_cho70d18/jrnrevision/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$this->load->view('v_finance/v_cho_payment/v_cho_jrnrevision', $data);
			}
			else
			{
				$mnCode				= 'MN147';
				$data["MenuCode"] 	= 'MN147';
				$data["MenuApp"] 	= 'MN147';
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
					$MenuCode 		= 'MN147';
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

				$data['backURL'] 	= site_url('c_finance/c_cho70d18/cprj/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$this->load->view('v_finance/v_cho_payment/v_cho_payment', $data); 
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : FUNCTION TO SEARCH ENGINE --------------------
		function f4n7_5rcH()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE";
			$url			= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : FUNCTION TO SEARCH ENGINE --------------------

  	function get_AllData() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("JournalH_Id",
									"JournalH_Code",
									"JournalH_Date",
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cho_payment->get_AllDataC($PRJCODE, $jrnType, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataL($PRJCODE, $jrnType, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$isLock				= $dataI['isLock'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$acc_number			= $dataI['acc_number'];
				$realizD_Amn		= $dataI['Journal_AmountReal'];
				$isCanceled 		= $dataI['isCanceled'];

				$fa_iconRelz = "fa-check-square-o";
				if($isCanceled == 1)
				{
					$RealizationValue 	= "Reversed";
					$fa_iconRelz 		= "fa-exchange";
				}


				if($ISPERSL == 1)
				{
					$ISPERSLD 		= "Pinjaman Dinas (PD)";
					$EMP_NAME 		= "";
					$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
										UNION
										SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
					$r_emp 			= $this->db->query($s_emp)->result();
					foreach($r_emp as $rw_emp) :
						$EMP_NAME	= $rw_emp->EMP_NAME;
					endforeach;

					if($realizD_Amn == 0)
					{
						$realizD_Amn = number_format($realizD_Amn, 2);
					}
					else
					{
						$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid_pd(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
					}

					$realizD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$RealizationValue."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".$realizD_Amn."
									  	</div>";
					$realizR 		= 	"<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>";

					$secPrint1		= site_url('c_finance/c_cho70d18/printdocument_CHO/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
					$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher_pd/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				}
				else
				{
					$ISPERSLD 		= "Petty Cash";
					$EMP_NAME 		= "";

					$realizD 		= 	"";
					$realizR 		= 	"";
					if($JournalType == 'VCASH')
					{
						$ISPERSLD 		= "Voucher Cash";
						$secPrint1		= site_url('c_finance/c_cho70d18/printdocument_VCASH/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
					}
					elseif($JournalType == 'CPRJ')
					{
						$ISPERSLD 		= "Penggunaan Kas Proyek";
						$secPrint1		= site_url('c_finance/c_cho70d18/printdocument_CPRJ/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
					}

					$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
										UNION
										SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
					$r_emp 			= $this->db->query($s_emp)->result();
					foreach($r_emp as $rw_emp) :
						$EMP_NAME	= $rw_emp->EMP_NAME;
					endforeach;

					if($realizD_Amn == 0)
					{
						$realizD_Amn = number_format($realizD_Amn, 2);
					}
					else
					{
						$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
					}

					$realizD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$RealizationValue."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".$realizD_Amn."
									  	</div>";

					if($isCanceled == 1)
					{
						$realizD 	= 	"<div style='white-space:nowrap'>
										<strong><i class='fa fa-exchange margin-r-5'></i> Reversed</strong>
									</div>";
					}

					$realizR 		= 	"<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>";

					$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				}

				/*$Acc_Name 			= "-";
				$sqlISHO 			= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$Acc_Name		= $rowISHO->Acc_Name;
				endforeach;*/
				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail A
										LEFT JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE
										WHERE A.proj_Code = '$PRJCODE' AND A.JournalH_Code = '$JournalH_Code'";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$noUAcc 		= $noUAcc+1;
					if($noUAcc == 1)
						$Acc_Name	= $rowISHO->Acc_Name;
					else
						$Acc_Name	= $Acc_Name.", ".$rowISHO->Acc_Name;
				endforeach;
				$Acc_Name 			= ucwords(strtolower($Acc_Name));

				if($ISPERSL == 1)
					$Acc_NameD 		= "<div><strong>$Acc_Name</strong></div>";
				else
					$Acc_NameD 		= "";

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 			= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				$isLockD 		= "";
				if($GEJ_STAT == 1 || $GEJ_STAT == 4) 
				{
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				elseif($GEJ_STAT == 3 && $isLock == 1)
				{
					$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
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
				elseif($GEJ_STAT == 3)
				{
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$isLockD $Manual_No</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$ISPERSLD." </strong></div>",
										  	$JournalH_DateV,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$JournalH_Desc."
									  		<div style='margin-left: 20px; font-style: italic;'>
									  			$Acc_NameD
										  		$realizR
										  	</div>",
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$GJournal_Total."</strong></div>
										  	$realizD",
										  	"<div style='text-align:right; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataCHO() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("JournalH_Id",
									"Manual_No",
									"JournalH_Date",
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cho_payment->get_AllDataC($PRJCODE, $jrnType, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataL($PRJCODE, $jrnType, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$GEJ_STAT_VCASH		= $dataI['GEJ_STAT_VCASH'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				if($GEJ_STAT_VCASH == 6)
				{
					$STATDESC		= "Closed";
					$STATCOL		= "info";
				}

				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$isLock				= $dataI['isLock'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$EmpID 				= $dataI['Emp_ID'];
				$acc_number			= $dataI['acc_number'];
				$realizD_Amn		= $dataI['Journal_AmountReal'];
				$isCanceled 		= $dataI['isCanceled'];

				$realizD 			= 	"";
				$realizR 			= 	"";

				/*if($jrnType == 'CPRJ')
					$PERSL_EMPID = $EmpID;*/

				$ISPERSLD 		= "Voucher Cash";
				$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
									UNION
									SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
				$r_emp 			= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$EMP_NAME	= $rw_emp->EMP_NAME;
				endforeach;

				if($realizD_Amn == 0)
				{
					$realizD_Amn = number_format($realizD_Amn, 2);
				}
				else
				{
					$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
				}

				$realizD 		= 	"<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$Paid."</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".$realizD_Amn."
								  	</div>";

				if($isCanceled == 1)
				{
					$realizD 	= 	"<div style='white-space:nowrap'>
									<strong><i class='fa fa-exchange margin-r-5'></i> Reversed</strong>
								</div>";
				}

				$realizR 		= 	"<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>";

				$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				
				/*$Acc_Name 			= "-";
				$sqlISHO 			= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$Acc_Name		= $rowISHO->Acc_Name;
				endforeach;*/
				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail_vcash A
										LEFT JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE
										WHERE A.proj_Code = '$PRJCODE' AND A.JournalH_Code = '$JournalH_Code'";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$noUAcc 		= $noUAcc+1;
					if($noUAcc == 1)
						$Acc_Name	= $rowISHO->Acc_Name;
					else
						$Acc_Name	= $Acc_Name.", ".$rowISHO->Acc_Name;
				endforeach;
				$Acc_Name 			= ucwords(strtolower($Acc_Name));

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				if($jrnType == 'VCASH')
				{
					$secPrint1		= site_url('c_finance/c_cho70d18/printdocument_VCASH/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
					$secUpd			= site_url('c_finance/c_cho70d18/up0b28t18VCASH/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				}
				elseif($jrnType == 'CPRJ')
				{
					$secPrint1		= site_url('c_finance/c_cho70d18/printdocument_CPRJ/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
					$secUpd			= site_url('c_finance/c_cho70d18/up0b28t18CPRJ/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				}

				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 			= base_url().'index.php/__l1y/trashCHO_NEW/?id=';
				$secVoid 			= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader_vcash~tbl_journaldetail_vcash~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				$isLockD 		= "";
				if($GEJ_STAT == 1 || $GEJ_STAT == 4) 
				{
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				elseif($GEJ_STAT == 3 && $isLock == 1)
				{
					$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
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
				elseif($GEJ_STAT == 3 && $isLock == 0)
				{
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				elseif($GEJ_STAT == 3 && $GEJ_STAT_VCASH == 0)
				{
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$isLockD $Manual_No</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$ISPERSLD." </strong></div>",
										  	$JournalH_DateV,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$JournalH_Desc."
									  		<div style='margin-left: 20px; font-style: italic;'>
										  		<div><strong>$Acc_Name</strong></div>
										  		$realizR
										  	</div>",
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$GJournal_Total."</strong></div>
										  	$realizD",
										  	"<div style='text-align:right; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataCHOGRP() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
		$SPLCODE		= $_GET['SPLC'];
		$GEJ_STAT		= $_GET['GSTAT'];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("JournalH_Id",
									"JournalH_Code",
									"JournalH_Date",
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cho_payment->get_AllDataCHOGRPC($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataCHOGRPL($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$GEJ_STAT_VCASH		= $dataI['GEJ_STAT_VCASH'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				if($GEJ_STAT_VCASH == 6)
				{
					$STATDESC		= "Closed";
					$STATCOL		= "info";
				}

				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$isLock				= $dataI['isLock'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$EmpID 				= $dataI['Emp_ID'];
				$acc_number			= $dataI['acc_number'];
				$realizD_Amn		= $dataI['Journal_AmountReal'];

				$realizD 			= 	"";
				$realizR 			= 	"";

				if($jrnType == 'CPRJ')
					$PERSL_EMPID = $EmpID;

				$ISPERSLD 		= "Voucher Cash";
				$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
									UNION
									SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
				$r_emp 			= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$EMP_NAME	= $rw_emp->EMP_NAME;
				endforeach;

				if($realizD_Amn == 0)
				{
					$realizD_Amn = number_format($realizD_Amn, 2);
				}
				else
				{
					$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
				}

				$realizD 		= 	"<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$Paid."</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".$realizD_Amn."
								  	</div>";
				$realizR 		= 	"<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>";

				$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				
				/*$Acc_Name 			= "-";
				$sqlISHO 			= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$Acc_Name		= $rowISHO->Acc_Name;
				endforeach;*/
				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail_vcash A
										LEFT JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE
										WHERE A.proj_Code = '$PRJCODE' AND A.JournalH_Code = '$JournalH_Code'";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$noUAcc 		= $noUAcc+1;
					if($noUAcc == 1)
						$Acc_Name	= $rowISHO->Acc_Name;
					else
						$Acc_Name	= $Acc_Name.", ".$rowISHO->Acc_Name;
				endforeach;
				$Acc_Name 			= ucwords(strtolower($Acc_Name));

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				if($jrnType == 'VCASH')
				{
					$secPrint1		= site_url('c_finance/c_cho70d18/printdocument_VCASH/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
					$secUpd			= site_url('c_finance/c_cho70d18/up0b28t18VCASH/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				}
				elseif($jrnType == 'CPRJ')
				{
					$secPrint1		= site_url('c_finance/c_cho70d18/printdocument_CPRJ/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
					$secUpd			= site_url('c_finance/c_cho70d18/up0b28t18CPRJ/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				}

				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 			= base_url().'index.php/__l1y/trashCHO_NEW/?id=';
				$secVoid 			= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader_vcash~tbl_journaldetail_vcash~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				$isLockD 		= "";
				if($GEJ_STAT == 1 || $GEJ_STAT == 4) 
				{
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				elseif($GEJ_STAT == 3 && $isLock == 1)
				{
					$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
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
				elseif($GEJ_STAT == 3 && $GEJ_STAT_VCASH == 0)
				{
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
					$isLockD 	= "";
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$isLockD $Manual_No</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$ISPERSLD." </strong></div>",
										  	$JournalH_DateV,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$JournalH_Desc."
									  		<div style='margin-left: 20px; font-style: italic;'>
										  		<div><strong>$Acc_Name</strong></div>
										  		$realizR
										  	</div>",
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$GJournal_Total."</strong></div>
										  	$realizD",
										  	"<div style='text-align:right; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("",
									  	"",
									  	"",
									  	"$PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search",
									  	"",
									  	"",
									  	"",
									  	"");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataREVGRP() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
		$SPLCODE		= $_GET['SPLC'];
		$GEJ_STAT		= $_GET['GSTAT'];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("JournalH_Id",
									"JournalH_Code",
									"JournalH_Date",
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cho_payment->get_AllDataREVGRPC($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataREVGRPL($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$GEJ_STAT_VCASH		= $dataI['GEJ_STAT_VCASH'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				if($GEJ_STAT_VCASH == 6)
				{
					$STATDESC		= "Closed";
					$STATCOL		= "info";
				}

				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$acc_number			= $dataI['acc_number'];
				$realizD_Amn		= $dataI['Journal_AmountReal'];

				$realizD 			= 	"";
				$realizR 			= 	"";

				$ISPERSLD 		= "Voucher Cash";
				$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
									UNION
									SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
				$r_emp 			= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$EMP_NAME	= $rw_emp->EMP_NAME;
				endforeach;

				if($realizD_Amn == 0)
				{
					$realizD_Amn = number_format($realizD_Amn, 2);
				}
				else
				{
					$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
				}

				$realizD 		= 	"<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$Paid."</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".$realizD_Amn."
								  	</div>";
				$realizR 		= 	"<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>";

				$secPrint1			= site_url('c_finance/c_cho70d18/printdocument_VCASH/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				
				/*$Acc_Name 			= "-";
				$sqlISHO 			= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$Acc_Name		= $rowISHO->Acc_Name;
				endforeach;*/
				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail A
										LEFT JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE
										WHERE A.proj_Code = '$PRJCODE' AND A.JournalH_Code = '$JournalH_Code'";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$noUAcc 		= $noUAcc+1;
					if($noUAcc == 1)
						$Acc_Name	= $rowISHO->Acc_Name;
					else
						$Acc_Name	= $Acc_Name.", ".$rowISHO->Acc_Name;
				endforeach;
				$Acc_Name 			= ucwords(strtolower($Acc_Name));

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 			= base_url().'index.php/__l1y/trashCHO/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				if($GEJ_STAT == 1 || $GEJ_STAT == 4) 
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				elseif($GEJ_STAT == 3 && $GEJ_STAT_VCASH == 0)
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$Manual_No</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$ISPERSLD." </strong></div>",
										  	$JournalH_DateV,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$JournalH_Desc."
									  		<div style='margin-left: 20px; font-style: italic;'>
										  		<div><strong>$Acc_Name</strong></div>
										  		$realizR
										  	</div>",
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$Journal_Amount."</strong></div>
										  	$realizD",
										  	"<div style='text-align:center; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("",
									  	"",
									  	"",
									  	"$PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search",
									  	"",
									  	"",
									  	"",
									  	"");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function adda70d18() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		$collData			= $_GET['id'];
		$collData			= $this->url_encryption_helper->decode_url($collData);

		$EXPL_DATA 			= explode("~", $collData);
		$PRJCODE 			= $EXPL_DATA[0];
		$jrnType 			= $EXPL_DATA[1];
		$data["jrnType"] 	= $jrnType;
		
		if($jrnType == 'CHO')
		{
			$data["ISPERSL"] 	= 1;
			$mnCode				= 'MN359';
			$data["MenuCode"] 	= 'MN359';
			$data["MenuApp"] 	= 'MN359';
			$MenuCode 			= 'MN359';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']= site_url('c_finance/c_cho70d18/add_process');
		}
		elseif($jrnType == 'VCASH')
		{
			$data["ISPERSL"] 	= 2;
			$mnCode				= 'MN045';
			$data["MenuCode"] 	= 'MN045';
			$data["MenuApp"] 	= 'MN045';
			$MenuCode 			= 'MN045';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']= site_url('c_finance/c_cho70d18/add_processVCASH');
		}
		elseif($jrnType == 'CPRJ')
		{
			$data["ISPERSL"] 	= 3;
			$mnCode				= 'MN147';
			$data["MenuCode"] 	= 'MN147';
			$data["MenuApp"] 	= 'MN147';
			$MenuCode 			= 'MN147';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			//$data['form_action']= site_url('c_finance/c_cho70d18/add_process');
			$data['form_action']= site_url('c_finance/c_cho70d18/add_processCPRJ');
		}
		elseif($jrnType == 'JRNREV')
		{
			$data["ISPERSL"] 	= 3;
			$mnCode				= 'MN410';
			$data["MenuCode"] 	= 'MN410';
			$data["MenuApp"] 	= 'MN410';
			$MenuCode 			= 'MN410';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']= site_url('c_finance/c_cho70d18/add_processJRNREV');
		}
		elseif($jrnType == 'JREVISION')
		{
			$data["ISPERSL"] 	= 3;
			$mnCode				= 'MN058';
			$data["MenuCode"] 	= 'MN058';
			$data["MenuApp"] 	= 'MN058';
			$MenuCode 			= 'MN058';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']= site_url('c_finance/c_cho70d18/add_processJRNREVISION');
		}
		else
		{
			$data["ISPERSL"] 	= 0;
			$mnCode				= 'MN147';
			$data["MenuCode"] 	= 'MN147';
			$data["MenuApp"] 	= 'MN147';
			$MenuCode 			= 'MN147';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']= site_url('c_finance/c_cho70d18/add_process');
		}
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			// GET PRJCODE_HO
				$getGPRJP 			= $this->m_updash->get_PRJHO($PRJCODE)->row();
				$PRJCODE_HO			= $getGPRJP->PRJCODE_HO;
				$PRJPERIOD			= $getGPRJP->PRJPERIOD;
				$data['PRJCODE_HO'] = $PRJCODE_HO;
				$data['PRJPERIOD'] 	= $PRJPERIOD;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$acc_number			= '';
			//$data['form_action']= site_url('c_finance/c_cho70d18/add_process');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= $MenuCode;
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

			if($jrnType == 'VCASH')
				$this->load->view('v_finance/v_cho_payment/v_cho_vcash_form', $data);
			else if($jrnType == 'CPRJ')
			{
				if($DefEmp_ID == 'D15040004221')
					$this->load->view('v_finance/v_cho_payment/v_cho_cprj_form', $data);
				else
				{
					$this->load->view('v_finance/v_cho_payment/v_cho_cprj_form', $data);
					//$this->load->view('page_uc', $data);
				}
			}
			else if($jrnType == 'JRNREV')
				$this->load->view('v_finance/v_cho_payment/v_cho_jrnrev_form', $data);
			else if($jrnType == 'JREVISION')
				$this->load->view('v_finance/v_cho_payment/v_cho_jrnrevision_form', $data);
			else
				$this->load->view('v_finance/v_cho_payment/v_cho_vcash_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function puSA0b28t18() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$THEROW		= $_GET['theRow'];
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['THEROW'] 		= $THEROW;
			$data['SOURCE'] 		= "O";
			$acc_number				= '';
			
			$data['countAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 		= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			

			$data['countAllItem']	= $this->m_cho_payment->count_all_Account($PRJCODE);
			$data['vwAllItem'] 		= $this->m_cho_payment->view_all_Account($PRJCODE)->result();
					
			$this->load->view('v_finance/v_cho_payment/v_cho_payment_selacc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_processVCASH() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$JournalType 	= $this->input->post('JournalType');
		$jrnType 		= $this->input->post('JournalType');

		$MenuCode		= 'MN045';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			$PPNH_Amount 	= $this->input->post('PPNH_Amount');
			$PPHH_Amount 	= $this->input->post('PPHH_Amount');
			$GJournal_Total = $this->input->post('GJournal_Total');
			$JOURNL_DESC	= $this->input->post('JournalH_Desc');
			$Reference_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Reference_Date'))));
			$Reference_Numb	= $this->input->post('Reference_Number');

			$SPLCODE 		= $PERSL_EMPID;
			$SPLDESC 		= "";
			$s_emp			=  "SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
			$r_emp 			= $this->db->query($s_emp)->result();
			foreach($r_emp as $rw_emp) :
				$SPLDESC	= $rw_emp->EMP_NAME;
			endforeach;
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code 	= $this->input->post('Pattern_Code');
				
				$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				//$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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
			
			// START - HEADER
				$AH_CODE		= $JournalH_Code;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= date('Y-m-d H:i:s');
				$AH_NOTES		= $this->input->post('JournalH_Desc');
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
										'JournalH_Desc' 	=> $AH_NOTES,
										'Manual_No' 		=> $Manual_No,
										'Reference_Date'	=> $Reference_Date,
										'Reference_Number' 	=> $Reference_Numb,
										'JournalType' 		=> $JournalType,
										'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
										'JournalH_Date'		=> $JournalH_Date,
										'Company_ID'		=> $comp_init,
										'Reference_Type'	=> $JournalType,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $GEJ_CREATED,
										'Created'			=> $GEJ_CREATED,
										'Wh_id'				=> $PRJCODE,
										'proj_Code'			=> $PRJCODE,
										'proj_CodeHO'		=> $proj_CodeHO,
										'PRJPERIOD'			=> $PRJPERIOD,
										'GEJ_STAT'			=> $GEJ_STAT,
										'ISPERSL'			=> $ISPERSL,
										'PERSL_EMPID'		=> $PERSL_EMPID,
										'acc_number'		=> $acc_number,
										'PPNH_Amount'		=> $PPNH_Amount,
										'PPHH_Amount'		=> $PPHH_Amount,
										'GJournal_Total'	=> $GJournal_Total); 
				//$this->m_cho_payment->add($projGEJH); // SEBELUM APPROVE TIDAK MASUK KE tbl_jorunaldetail
			// END - HEADER

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PATTCODE 		= $this->input->post('PATTCODE');
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN045',
										'DOCTYPE' 		=> 'VC',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $JournalH_Date,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $Manual_No 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			// ADD JOURNAL HEADER
				$this->m_cho_payment->add_vcash($projGEJH);

			// START : DETAIL JOURNAL
				$Base_DebetTOT		= 0;
				$Base_DebetTOT_Tax	= 0;
				$PattNum 			= 0;
				$TAX_DATE 			= '0000-00-00';
				$TAX_NO 			= '';
				foreach($_POST['data'] as $d)
				{
					$PattNum 		= $PattNum+1;
					$JournalH_Code	= $JournalH_Code;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$proj_Code		= $d['proj_Code'];
					$JOBCODEID		= $d['JOBCODEID'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$ITM_GROUP		= $d['ITM_CATEG'];
					$ITM_VOLM		= $d['ITM_VOLM'];
					$ITM_UNIT		= $d['ITM_UNIT'];
					$ITM_PRICE		= $d['ITM_PRICE'];
					
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					$isHO			= 0;
					$syncPRJ		= '';
					$Acc_Name 		= "-";
					$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
						$Acc_Name	= $rowISHO->Acc_Name;
					endforeach;

					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					$ITM_Amount	= $d['JournalD_Amount'];
					$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

					$Journal_Type		= $jrnType;
					$isTax				= 0;

					$JournalD_Debet		= $d['JournalD_Amount'];
					$Base_Debet			= $d['JournalD_Amount'];
					$COA_Debet			= $d['JournalD_Amount'];
					$JournalD_Kredit 	= 0;
					$Base_Kredit		= 0;
					$COA_Kredit			= 0;
					
					$JournalD_Debet_tax	= 0;
					$Base_Debet_tax		= 0;
					$COA_Debet_tax		= 0;
					$JournalD_Kredit_tax= 0;
					$Base_Kredit_tax	= 0;
					$COA_Kredit_tax		= 0;

					
					$curr_rate			= 1;
					$isDirect			= 1;
					$TAX_DATE			= addslashes($d['TAX_DATE']);
					$TAX_NO				= addslashes($d['TAX_NO']);
					$Other_Desc			= addslashes($d['Other_Desc']);
					$Journal_DK			= $JournalD_Pos;
					$Journal_Type		= $Journal_Type;
					$isTax				= $isTax;
					$PPN_Code			= $d['PPN_Code'];
					$PPN_Perc			= $d['PPN_Perc'];
					$PPN_Amount			= $d['PPN_Amount'];
					$PPH_Code			= $d['PPH_Code'];
					$PPH_Perc			= $d['PPH_Perc'];
					$PPH_Amount			= $d['PPH_Amount'];
					$isVerified			= $d['isVerified'];

					/*
						PERHATIAN ...!!! (2022-02-11) BY DIAN HERMANTO
						JIKA ADA PPN DAN PPH MAKA
						1. PADA TABEL tbl_journal_detail akan dilakukan pemisahan antara DPP dengan PPN, PPH
						2. PADA TABEL tbl_journal_detail_vcash akan tetap disatukan
						3. PADA SAAT PENARIKAAN JURNAL UNTUK PEMBAYARAN WAJIB DIAMBIL DARI TABEL tbl_journal_detail BUKAN tbl_journal_detail_vcash
						4. PPN DAN PPH TERBENTUK SAAT APPROVE
					*/

					// START : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET
						$insSQL	= "INSERT INTO tbl_journaldetail_vcash (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
										Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
										Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
										COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
										ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
										GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
										PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, Manual_No, isVerified) VALUES
										('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID',
										'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
										$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
										$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
										'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'D', '$Journal_Type', $isTax, 
										'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2,
										'$PPN_Code', $PPN_Perc, $PPN_Amount, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$Manual_No', $isVerified)";
						$this->db->query($insSQL);
					// END : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET

					// START : PROCEDURE UPDATE JOBLISTDETAIL
						$JOBID 		= $d['JOBCODEID'];
						$ITM_CODE	= $d['ITM_CODE'];
						$DOC_VOLM	= $d['ITM_VOLM'];
						$DOC_TOTAL	= $Base_Debet;
						$compVAR 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $JournalH_Date,
											'JOBID'		=> $JOBID,
											'ITM_CODE'	=> $ITM_CODE,
											'DOC_VOLM'	=> $DOC_VOLM,
											'DOC_TOTAL'	=> $DOC_TOTAL,
											'VAR_VOL_R'	=> "VCASH_VOL_R",
											'VAR_VAL_R'	=> "VCASH_VAL_R");
						$this->m_updash->updJOBP($compVAR);
					// END : PROCEDURE UPDATE JOBLISTDETAIL
				}

				// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT
					$Acc_Name 		= "-";
					$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
					$resACCNm		= $this->db->query($sqlACCNm)->result();
					foreach($resACCNm as $rowACCNm):
						$Acc_Name	= $rowACCNm->Acc_Name;
					endforeach;

					$totHUTSPL 			= $Journal_Amount+$PPNH_Amount-$PPHH_Amount;
					$JournalD_Kredit 	= $totHUTSPL;
					$Base_Kredit		= $totHUTSPL;
					$COA_Kredit			= $totHUTSPL;
					$isTax 				= 0;
					$PattNumK 			= $PattNum+1;

					// START : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
						$insSQLK	= "INSERT INTO tbl_journaldetail_vcash (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, Manual_No) VALUES
											('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $JournalD_Kredit,
											0, $JournalD_Kredit, 0, $JournalD_Kredit, 0,
											1, 1, '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'K', '$Journal_Type',
											$isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2, $PattNumK, '$Manual_No')";
						$this->db->query($insSQLK);
					// END : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
				// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT

				if($GEJ_STAT == 2)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$VCASH_VAL 	= $JournalD_Kredit;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $JournalH_Date,
											'FVAL'		=> $VCASH_VAL,
											'FNAME'		=> "VCASH_VAL");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}
			// END : DETAIL JOURNAL

			// START : UPDATE JOURNAL HEADER
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
								GJournal_Total = $GJournal_Total
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);

				$upJH3	= "UPDATE tbl_journalheader_vcash SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
								GJournal_Total = $GJournal_Total
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);

				$upJH3	= "UPDATE tbl_journaldetail SET Kwitansi_Date = '$Reference_Date', Kwitansi_No = '$Reference_Numb', SPLCODE = '$SPLCODE', SPLDESC = '$SPLDESC'
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);

				$upJH3	= "UPDATE tbl_journaldetail_vcash SET Kwitansi_Date = '$Reference_Date', Kwitansi_No = '$Reference_Numb', SPLCODE = '$SPLCODE', SPLDESC = '$SPLDESC'
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
			// END : UPDATE JOURNAL HEADER
		
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader_vcash");
				$this->m_updash->updateStatus($paramStat);

			// DELETE TABLE TEMPORARY
				$s_delTMP 	= "DELETE FROM tbl_journaldetail_tmp WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE'";
				$this->db->query($s_delTMP);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				//$MenuCode 	= 'MN359';
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
			
			if($GEJ_STAT == 2)
			{
				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "VCASH",
										'AS_MNCODE'		=> "MN045",
										'AS_DOCNUM'		=> $JournalH_Code,
										'AS_DOCCODE'	=> $Manual_No,
										'AS_DOCDATE'	=> $JournalH_Date,
										'AS_EXPDATE'	=> $JournalH_Date);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
			}

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_processCPRJ() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$JournalType 	= $this->input->post('JournalType');
		$jrnType 		= $this->input->post('JournalType');

		$MenuCode		= 'MN147';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			$PPNH_Amount 	= $this->input->post('PPNH_Amount');
			$PPHH_Amount 	= $this->input->post('PPHH_Amount');
			$GJournal_Total = $this->input->post('GJournal_Total');
			$JOURNL_DESC	= $this->input->post('JournalH_Desc');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code 	= $this->input->post('Pattern_Code');
				
				$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				//$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> "MN147",
										'DOCTYPE' 		=> 'CPRJ',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $JournalH_Date,
										'ACC_ID'		=> $acc_number,
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $Manual_No 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist 
			
			// START - HEADER
				$AH_CODE		= $JournalH_Code;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= date('Y-m-d H:i:s');
				$AH_NOTES		= $this->input->post('JournalH_Desc');
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
										'JournalH_Desc' 	=> $AH_NOTES,
										'Manual_No' 		=> $Manual_No,
										'JournalType' 		=> $JournalType,
										'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
										'JournalH_Date'		=> $JournalH_Date,
										'Company_ID'		=> $comp_init,
										'Reference_Type'	=> $JournalType,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $GEJ_CREATED,
										'Created'			=> $GEJ_CREATED,
										'Wh_id'				=> $PRJCODE,
										'proj_Code'			=> $PRJCODE,
										'proj_CodeHO'		=> $proj_CodeHO,
										'PRJPERIOD'			=> $PRJPERIOD,
										'GEJ_STAT'			=> $GEJ_STAT,
										'ISPERSL'			=> $ISPERSL,
										'PERSL_EMPID'		=> $PERSL_EMPID,
										'acc_number'		=> $acc_number,
										'PPNH_Amount'		=> $PPNH_Amount,
										'PPHH_Amount'		=> $PPHH_Amount,
										'GJournal_Total'	=> $GJournal_Total); 
				//$this->m_cho_payment->add($projGEJH); // SEBELUM APPROVE TIDAK MASUK KE tbl_jorunaldetail
			// END - HEADER

			// ADD JOURNAL HEADER
				$this->m_cho_payment->add_cprj($projGEJH);

			// START : DETAIL JOURNAL
				$Base_DebetTOT		= 0;
				$Base_DebetTOT_Tax	= 0;
				$PattNum 			= 0;
				$TAX_DATE 			= '';
				$TAX_NO 			= '';
				foreach($_POST['data'] as $d)
				{
					$PattNum 		= $PattNum+1;
					$JournalH_Code	= $JournalH_Code;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$proj_Code		= $d['proj_Code'];
					$JOBCODEID		= $d['JOBCODEID'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$ITM_GROUP		= $d['ITM_CATEG'];
					$ITM_VOLM		= $d['ITM_VOLM'];
					$ITM_UNIT		= $d['ITM_UNIT'];
					$ITM_PRICE		= $d['ITM_PRICE'];
					
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					$isHO			= 0;
					$syncPRJ		= '';
					$Acc_Name 		= "-";
					$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
						$Acc_Name	= $rowISHO->Acc_Name;
					endforeach;

					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					$ITM_Amount	= $d['JournalD_Amount'];
					$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

					$Journal_Type		= $jrnType;
					$isTax				= 0;

					$JournalD_Debet		= $d['JournalD_Amount'];
					$Base_Debet			= $d['JournalD_Amount'];
					$COA_Debet			= $d['JournalD_Amount'];
					$JournalD_Kredit 	= 0;
					$Base_Kredit		= 0;
					$COA_Kredit			= 0;
					
					$JournalD_Debet_tax	= 0;
					$Base_Debet_tax		= 0;
					$COA_Debet_tax		= 0;
					$JournalD_Kredit_tax= 0;
					$Base_Kredit_tax	= 0;
					$COA_Kredit_tax		= 0;

					
					$curr_rate			= 1;
					$isDirect			= 1;
					$TAX_DATE			= addslashes($d['TAX_DATE']);
					$TAX_NO				= addslashes($d['TAX_NO']);
					$Other_Desc			= addslashes($d['Other_Desc']);
					$Journal_DK			= $JournalD_Pos;
					$Journal_Type		= $Journal_Type;
					$isTax				= $isTax;
					$PPN_Code			= $d['PPN_Code'];
					$PPN_Perc			= $d['PPN_Perc'];
					$PPN_Amount			= $d['PPN_Amount'];
					$PPH_Code			= $d['PPH_Code'];
					$PPH_Perc			= $d['PPH_Perc'];
					$PPH_Amount			= $d['PPH_Amount'];

					/*
						PERHATIAN ...!!! (2022-02-11) BY DIAN HERMANTO
						JIKA ADA PPN DAN PPH MAKA
						1. PADA TABEL tbl_journal_detail akan dilakukan pemisahan antara DPP dengan PPN, PPH
						2. PADA TABEL tbl_journal_detail_vcash akan tetap disatukan
						3. PADA SAAT PENARIKAAN JURNAL UNTUK PEMBAYARAN WAJIB DIAMBIL DARI TABEL tbl_journal_detail BUKAN tbl_journal_detail_vcash
						4. PPN DAN PPH TERBENTUK SAAT APPROVE
					*/

					// START : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET
						$insSQL	= "INSERT INTO tbl_journaldetail_cprj (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
										Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
										Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
										COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
										ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
										GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
										PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, Manual_No) VALUES
										('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID',
										'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
										$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
										$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
										'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'D', '$Journal_Type', $isTax, 
										'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2,
										'$PPN_Code', $PPN_Perc, $PPN_Amount, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$Manual_No')";
						$this->db->query($insSQL);
					// END : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET

					// START : PROCEDURE UPDATE JOBLISTDETAIL
						$JOBID 		= $d['JOBCODEID'];
						$ITM_CODE	= $d['ITM_CODE'];
						$DOC_VOLM	= $d['ITM_VOLM'];
						$DOC_TOTAL	= $Base_Debet;
						$compVAR 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $JournalH_Date,
											'JOBID'		=> $JOBID,
											'ITM_CODE'	=> $ITM_CODE,
											'DOC_VOLM'	=> $DOC_VOLM,
											'DOC_TOTAL'	=> $DOC_TOTAL,
											'VAR_VOL_R'	=> "VLK_VOL_R",
											'VAR_VAL_R'	=> "VLK_VAL_R");
						$this->m_updash->updJOBP($compVAR);
					// END : PROCEDURE UPDATE JOBLISTDETAIL
				}

				// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT
					$Acc_Name 		= "-";
					$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
					$resACCNm		= $this->db->query($sqlACCNm)->result();
					foreach($resACCNm as $rowACCNm):
						$Acc_Name	= $rowACCNm->Acc_Name;
					endforeach;

					$totHUTSPL 			= $Journal_Amount+$PPNH_Amount-$PPHH_Amount;
					$JournalD_Kredit 	= $totHUTSPL;
					$Base_Kredit		= $totHUTSPL;
					$COA_Kredit			= $totHUTSPL;
					$isTax 				= 0;
					$PattNumK 			= $PattNum+1;

					// START : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
						$insSQLK	= "INSERT INTO tbl_journaldetail_cprj (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, Manual_No) VALUES
											('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $JournalD_Kredit,
											0, $JournalD_Kredit, 0, $JournalD_Kredit, 0,
											1, 1, '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'K', '$Journal_Type',
											$isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2, $PattNumK, '$Manual_No')";
						$this->db->query($insSQLK);
					// END : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
				// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT

				if($GEJ_STAT == 2)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$VLK_VAL 	= $JournalD_Kredit;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $JournalH_Date,
											'FVAL'		=> $VLK_VAL,
											'FNAME'		=> "VLK_VAL");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}
			// END : DETAIL JOURNAL

			// START : UPDATE JOURNAL HEADER
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
								GJournal_Total = $GJournal_Total
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);

				$upJH3	= "UPDATE tbl_journalheader_cprj SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
								GJournal_Total = $GJournal_Total
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
			// END : UPDATE JOURNAL HEADER
		
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader_cprj");
				$this->m_updash->updateStatus($paramStat);

			// DELETE TABLE TEMPORARY
				$s_delTMP 	= "DELETE FROM tbl_journaldetail_tmp WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE'";
				$this->db->query($s_delTMP);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader_cprj");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				//$MenuCode 	= 'MN359';
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
			
			$url			= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$JournalType 	= $this->input->post('JournalType');
		$jrnType 		= $this->input->post('JournalType');
		if($jrnType == 'CHO')
			$MenuCode			= 'MN359';
		elseif($jrnType == 'VCASH')
			$MenuCode			= 'MN045';
		elseif($jrnType == 'CPRJ')
			$MenuCode			= 'MN147';
		else
			$MenuCode			= 'MN147';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');

			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			$PPNH_Amount 	= $this->input->post('PPNH_Amount');
			$PPHH_Amount 	= $this->input->post('PPHH_Amount');
			$GJournal_Total = $this->input->post('GJournal_Total');
			if($GJournal_Total == '')
				$GJournal_Total = 0;
			$JOURNL_DESC	= $this->input->post('JournalH_Desc');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code 	= $this->input->post('Pattern_Code');
				
				$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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
			
			// START - HEADER
				$AH_CODE		= $JournalH_Code;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= date('Y-m-d H:i:s');
				$AH_NOTES		= $this->input->post('JournalH_Desc');
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
										'JournalH_Desc' 	=> $AH_NOTES,
										'Manual_No' 		=> $Manual_No,
										'JournalType' 		=> $JournalType,
										'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
										'JournalH_Date'		=> $JournalH_Date,
										'Company_ID'		=> $comp_init,
										'Reference_Type'	=> $JournalType,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $GEJ_CREATED,
										'Created'			=> $GEJ_CREATED,
										'Wh_id'				=> $PRJCODE,
										'proj_Code'			=> $PRJCODE,
										'proj_CodeHO'		=> $proj_CodeHO,
										'PRJPERIOD'			=> $PRJPERIOD,
										'GEJ_STAT'			=> $GEJ_STAT,
										'ISPERSL'			=> $ISPERSL,
										'PERSL_EMPID'		=> $PERSL_EMPID,
										'acc_number'		=> $acc_number,
										'PPNH_Amount'		=> $PPNH_Amount,
										'PPHH_Amount'		=> $PPHH_Amount,
										'GJournal_Total'	=> $GJournal_Total); 
				$this->m_cho_payment->add($projGEJH);
			// END - HEADER

			if($ISPERSL == 1)								// PINJAMAN DINAS
			{
				// ADD - HEADER
					$this->m_cho_payment->add_pd($projGEJH);

				$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');

				// Account Detail Lawan Kas Bank
					$Acc_Name1 	= "-";
					$sqlACCNm1 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_PERSL' LIMIT 1";
					$resACCNm1		= $this->db->query($sqlACCNm1)->result();
					foreach($resACCNm1 as $rowACCNm1):
						$Acc_Name1	= $rowACCNm1->Acc_Name;
					endforeach;

				// Account Detail Kas Bank
					$Acc_Name2 	= "-";
					$sqlACCNm2 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
					$resACCNm2		= $this->db->query($sqlACCNm2)->result();
					foreach($resACCNm2 as $rowACCNm2):
						$Acc_Name2	= $rowACCNm2->Acc_Name;
					endforeach;

				$PattNumD 		= $PattNum+1;
				$PattNumK 		= $PattNum+1;
				// START : DEBET
					$insSQLD	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
										JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
										('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
					$this->db->query($insSQLD);
				// END : DEBET

				// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
					$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
										JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
										('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
					$this->db->query($insSQLK);
				// END : KREDIT

				// START : DEBET - PD TABLE
					$insSQLD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
										JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
										('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
					$this->db->query($insSQLD);
				// END : DEBET - PD TABLE

				// START : KREDIT - PD TABLE
					$insSQLK	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
										JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
										('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
					$this->db->query($insSQLK);
				// END : KREDIT - PD TABLE

				$BaseDebetTOT 	= $Journal_Amount;

				if($GEJ_STAT == 2)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$PD_VAL 	= $BaseDebetTOT;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $JournalH_Date,
											'FVAL'		=> $PD_VAL,
											'FNAME'		=> "PD_VAL");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}
			
				// UPDATE AMOUNT HEADER
					$upJH3	= "UPDATE tbl_journalheader_pd SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader_pd");
					$this->m_updash->updateStatus($paramStat);
			}
			else 											// SELAIN PINJAMAN DINAS
			{
				// START : DETAIL JOURNAL
					// START : DEBET
						$Base_DebetTOT		= 0;
						$Base_DebetTOT_Tax	= 0;
						$PattNum 			= 0;
						foreach($_POST['data'] as $d)
						{
							$PattNum 		= $PattNum+1;
							$JournalH_Code	= $JournalH_Code;
							$Acc_Id			= $d['Acc_Id'];
							$ITM_CODE		= $d['ITM_CODE'];
							$proj_Code		= $d['proj_Code'];
							$JOBCODEID		= $d['JOBCODEID'];
							$JournalD_Pos	= $d['JournalD_Pos'];
							$isTax			= $d['isTax'];
							$ITM_GROUP		= $d['ITM_CATEG'];
							$ITM_VOLM		= $d['ITM_VOLM'];
							$ITM_UNIT		= $d['ITM_UNIT'];
							$ITM_PRICE		= $d['ITM_PRICE'];
							
							$PRJCODE		= $d['proj_Code'];
							$ACC_NUM		= $d['Acc_Id'];			// Detail Account
							$isHO			= 0;
							$syncPRJ		= '';
							$Acc_Name 		= "-";
							$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
								$Acc_Name	= $rowISHO->Acc_Name;
							endforeach;

							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
							
							$ITM_Amount	= $d['JournalD_Amount'];
							$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

							$Journal_Type		= '';
							$isTax				= 0;

							$JournalD_Debet		= $d['JournalD_Amount'];
							$Base_Debet			= $d['JournalD_Amount'];
							$COA_Debet			= $d['JournalD_Amount'];
							$JournalD_Kredit 	= 0;
							$Base_Kredit		= 0;
							$COA_Kredit			= 0;
							
							$Base_DebetTOT		= $Base_DebetTOT + $Base_Debet;
							
							$JournalD_Debet_tax	= 0;
							$Base_Debet_tax		= 0;
							$COA_Debet_tax		= 0;
							$JournalD_Kredit_tax= 0;
							$Base_Kredit_tax	= 0;
							$COA_Kredit_tax		= 0;

							
							$curr_rate			= 1;
							$isDirect			= 1;
							$Ref_Number			= addslashes($d['Ref_Number']);
							$Other_Desc			= addslashes($d['Other_Desc']);
							$Journal_DK			= $JournalD_Pos;
							$Journal_Type		= $Journal_Type;
							$isTax				= $isTax;

							// Insert into tbl_journal_detail (D) for All Expenses
							$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
											Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
											Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
											COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
											ITM_VOLM, ITM_UNIT, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
											GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, PattNum) VALUES
											('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID',
											'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
											$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
											$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
											'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$Ref_Number', '$Other_Desc', 'D', '$Journal_Type', $isTax, 
											'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $PattNum)";
							$this->db->query($insSQL);
						}
					
						$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
					// END : DEBET
				
					$Acc_Name 		= "-";
					$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
					$resACCNm		= $this->db->query($sqlACCNm)->result();
					foreach($resACCNm as $rowACCNm):
						$Acc_Name	= $rowACCNm->Acc_Name;
					endforeach;

					$PattNumK 		= $PattNum+1;
					// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
						$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, PattNum) VALUES
											('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Base_DebetTOT,
											$Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax,
											1, 1, '$Ref_Number', '$Other_Desc', 'K', '$Journal_Type',
											$isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $PattNumK)";
						$this->db->query($insSQLK);
					// END : KREDIT
				// END : DETAIL JOURNAL
			}

			// DELETE TABLE TEMPORARY
				$s_delTMP 	= "DELETE FROM tbl_journaldetail_tmp WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE'";
				$this->db->query($s_delTMP);
			
			// UPDATE AMOUNT HEADER
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount, GJournal_Total = $GJournal_Total WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				//$MenuCode 	= 'MN359';
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
			
			$url			= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up0b28t18() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$MenuCode 			= 'MN045';
			$data["MenuCode"] 	= 'MN045';
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			// $getGEJ 				= $this->m_cho_payment->get_VCASH_by_number($JournalH_Code)->row();
			$getGEJ 				= $this->m_cho_payment->get_CPRJ_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['PPNH_Amount'] 	= $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] 	= $getGEJ->PPHH_Amount;
			$data['GJournal_Total'] = $getGEJ->GJournal_Total;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;

			if($jrnType == 'CHO')
			{
				$data["ISPERSL"] 	= 1;
				$mnCode				= 'MN359';
				$MenuCode			= 'MN359';
				$data["MenuCode"] 	= 'MN359';
				$data["MenuApp"] 	= 'MN359';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

				$data['form_action']= site_url('c_finance/c_cho70d18/update_process');
			}
			elseif($jrnType == 'VCASH')
			{
				$data["ISPERSL"] 	= 2;
				$mnCode				= 'MN045';
				$data["MenuCode"] 	= 'MN045';
				$data["MenuApp"] 	= 'MN045';
				$MenuCode 			= 'MN045';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

				$data['form_action']= site_url('c_finance/c_cho70d18/update_processVCASH');
			}
			elseif($jrnType == 'CPRJ')
			{
				$data["ISPERSL"] 	= 3;
				$mnCode				= 'MN147';
				$data["MenuCode"] 	= 'MN147';
				$data["MenuApp"] 	= 'MN147';
				$MenuCode 			= 'MN147';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

				$data['form_action']= site_url('c_finance/c_cho70d18/update_processCPRJ');
			}
			else
			{
				$data["ISPERSL"] 	= 0;
				$mnCode				= 'MN147';
				$MenuCode			= 'MN147';
				$data["MenuCode"] 	= 'MN147';
				$data["MenuApp"] 	= 'MN147';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

				$data['form_action']= site_url('c_finance/c_cho70d18/update_process');
			}
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			//$data['form_action']= site_url('c_finance/c_cho70d18/update_process');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				//$MenuCode 	= 'MN359';
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

			if($jrnType == 'VCASH')
				$this->load->view('v_finance/v_cho_payment/v_cho_vcash_form', $data);
			else
				$this->load->view('v_finance/v_cho_payment/v_cho_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$JournalType 	= $this->input->post('JournalType');
		$jrnType 		= $this->input->post('JournalType');
		if($jrnType == 'CHO')
			$MenuCode			= 'MN359';
		else
			$MenuCode			= 'MN147';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$accYr			= date('Y', strtotime($JournalH_Date));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');

			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PPNH_Amount 	= $this->input->post('PPNH_Amount');
			$PPHH_Amount 	= $this->input->post('PPHH_Amount');
			$GJournal_Total = $this->input->post('GJournal_Total');

			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			$JournalH_Desc 	= $this->input->post('JournalH_Desc');
			$JOURNL_DESC	= $this->input->post('JournalH_Desc');

			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('JournalH_Desc2');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJHA);
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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
			
			// START : UPDATE HEADER
				$projGEJH 	= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> $JournalType,
									'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> $JournalType,
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'ISPERSL'			=> $ISPERSL,
									'PERSL_EMPID'		=> $PERSL_EMPID,
									'acc_number'		=> $acc_number,
									'Journal_Amount'	=> $Journal_Amount,
									'PPNH_Amount'		=> $PPNH_Amount,
									'PPHH_Amount'		=> $PPHH_Amount,
									'GJournal_Total'	=> $GJournal_Total);
				$this->m_cho_payment->updateCHO($JournalH_Code, $projGEJH);
			// END : UPDATE HEADER

			if($GEJ_STAT == 3)
			{
				// DEFAULT STATUS IF APPROVE
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'PRJCODE'		=> $PRJCODE,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				if($ISPERSL == 1)
				{
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> 7,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_pd");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
						
					$this->m_cho_payment->updateCHO_pd($JournalH_Code, $projGEJH);

					$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');

					// Account Detail Lawan Kas Bank
						$Acc_Name1 	= "-";
						$sqlACCNm1 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_PERSL' LIMIT 1";
						$resACCNm1		= $this->db->query($sqlACCNm1)->result();
						foreach($resACCNm1 as $rowACCNm1):
							$Acc_Name1	= $rowACCNm1->Acc_Name;
						endforeach;

					// Account Detail Kas Bank
						$Acc_Name2 	= "-";
						$sqlACCNm2 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
						$resACCNm2		= $this->db->query($sqlACCNm2)->result();
						foreach($resACCNm2 as $rowACCNm2):
							$Acc_Name2	= $rowACCNm2->Acc_Name;
						endforeach;

					// START : RESET DETAIL
						$this->m_cho_payment->deleteCPRJDetail($JournalH_Code);
						$this->m_cho_payment->deleteCPRJDetail_pd($JournalH_Code);

					$PattNumD 		= 1;
					$PattNumK 		= 2;
					// START : DEBET
						$insSQLD	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
											JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
						$this->db->query($insSQLD);
					// END : DEBET

					// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
						$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERS, PattNumL) VALUES
											('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
						$this->db->query($insSQLK);
					// END : KREDIT

					// START : DEBET - PD TABLE
						$insSQLD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
											JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
						$this->db->query($insSQLD);
					// END : DEBET - PD TABLE

					// START : KREDIT - PD TABLE
						$insSQLK	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
						$this->db->query($insSQLK);
					// END : KREDIT - PD TABLE

					$BaseDebetTOT 	= $Journal_Amount;
				}
				else
				{
					// START : RESET DETAIL
						$this->m_cho_payment->deleteCPRJDetail($JournalH_Code);

					// START : DEBET
						$Base_DebetTOT		= 0;
						$Base_DebetTOT_Tax	= 0;
						$PattNum 			= 0;
						foreach($_POST['data'] as $d)
						{
							$PattNum 		= $PattNum+1;
							$JournalH_Code	= $JournalH_Code;
							$Acc_Id			= $d['Acc_Id'];
							$ITM_CODE		= $d['ITM_CODE'];
							$proj_Code		= $d['proj_Code'];
							$JOBCODEID		= $d['JOBCODEID'];
							$JournalD_Pos	= $d['JournalD_Pos'];
							$isTax			= $d['isTax'];
							$ITM_GROUP		= $d['ITM_CATEG'];
							$ITM_VOLM		= $d['ITM_VOLM'];
							$ITM_UNIT		= $d['ITM_UNIT'];
							
							$PRJCODE		= $d['proj_Code'];
							$ACC_NUM		= $d['Acc_Id'];			// Detail Account
							$isHO			= 0;
							$syncPRJ		= '';
							$Acc_Name 		= "-";
							$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
								$Acc_Name	= $rowISHO->Acc_Name;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
							
							$ITM_Amount	= $d['JournalD_Amount'];
							$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

							$Journal_Type		= '';
							$isTax				= 0;
							
							if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
							{
								$JournalD_Debet	= $d['JournalD_Amount'];
								$Base_Debet		= $d['JournalD_Amount'];
								$COA_Debet		= $d['JournalD_Amount'];
								$JournalD_Kredit= 0;
								$Base_Kredit	= 0;
								$COA_Kredit		= 0;

								if($AH_ISLAST == 1)
								{
									// UPDATE FINAL STATUS
										$upJH2	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
										$this->db->query($upJH2);

									// START : Update to COA - Debit
										if($jmD > 0)
										{
											$SYNC_PRJ	= '';
											for($i=0; $i < $jmD; $i++)
											{
												$SYNC_PRJ	= $dataPecah[$i];
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
																	Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
												$this->db->query($sqlUpdCOA);
											}
										}
									// END : Update to COA - Debit
									
									// START : UPDATE L/R
										$JOBCODEID	= $d['JOBCODEID'];
										$ITM_CODE	= $d['ITM_CODE'];
										$ITM_VOLM	= $d['ITM_VOLM'];

										if($ITM_CODE != '')
										{
											$ITM_GROUP 	= '';
											$ITM_CATEG 	= '';
											$ITM_LR 	= '';
											$sqlLITMLR	= "SELECT ITM_GROUP, ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
											$resLITMLR = $this->db->query($sqlLITMLR)->result();					
											foreach($resLITMLR as $rowLITMLR):
												$ITM_GROUP	= $rowLITMLR->ITM_GROUP;
												$ITM_CATEG	= $rowLITMLR->ITM_CATEG;
												$ITM_LR		= $rowLITMLR->ITM_LR;
											endforeach;

											// START : ITEM HISTORY
												$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
																		QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
																		JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
																	VALUES ('$JournalH_Code', '$PRJCODE', '$JournalH_Date', '$ITM_CODE', $Base_Debet, 0, 
																		0, 0, 'CHO', $Base_Debet, '$comp_init', 'IDR', 
																		'$JOBCODEID', 3, '$Base_Debet', '$ITM_CATEG', '$JournalH_Desc')";
												$this->db->query($sqlHist);
											// END : ITEM HISTORY

											// L/R MANUFACTUR
												if($ITM_LR != '')
												{
													$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR+$Base_Debet 
																WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
													$this->db->query($updLR);
												}

											// L/R CONTRACTOR
												if($ITM_GROUP == 'ADM')
												{
													$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet
																WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
																	AND YEAR(PERIODE) = '$PERIODY'";
													$this->db->query($updLR);
												}
												elseif($ITM_GROUP == 'GE')
												{
													$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
																WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
																	AND YEAR(PERIODE) = '$PERIODY'";
													$this->db->query($updLR);
												}
												elseif($ITM_GROUP == 'I')
												{
													$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet 
																WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
																	AND YEAR(PERIODE) = '$PERIODY'";
													$this->db->query($updLR);
												}
												elseif($ITM_GROUP == 'O')
												{
													$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet
																WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
																	AND YEAR(PERIODE) = '$PERIODY'";
													$this->db->query($updLR);
												}
												elseif($ITM_GROUP == 'SC')
												{
													$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
																WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
																	AND YEAR(PERIODE) = '$PERIODY'";
													$this->db->query($updLR);
												}
												elseif($ITM_GROUP == 'T')
												{
													$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
																WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
																	AND YEAR(PERIODE) = '$PERIODY'";
													$this->db->query($updLR);
												}
												elseif($ITM_GROUP == 'U')
												{
													$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
																WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
																	AND YEAR(PERIODE) = '$PERIODY'";
													$this->db->query($updLR);
												}

											// START : Update ITM Used
												// 1. UPDATE JOBLIST
													$ITM_USED	= 0;
													$ITM_USEDAM	= 0;
													$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
																		WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
																			AND ITM_CODE = '$ITM_CODE'";
													$resUSED1	= $this->db->query($sqlUSED1)->result();
													foreach($resUSED1 as $rowUSED1):
														$ITM_USED	= $rowUSED1->ITM_USED;
														$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
													endforeach;
													
													$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
																		ITM_LASTP	= $ITM_PRICE,
																		ITM_USED 	= ITM_USED+$ITM_VOLM, 
																		ITM_USED_AM = ITM_USED_AM+$Base_Debet,
																		REQ_VOLM 	= REQ_VOLM+$ITM_VOLM, 
																		REQ_AMOUNT 	= REQ_AMOUNT+$Base_Debet
																	WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
																		AND ITM_CODE = '$ITM_CODE'";
													$this->db->query($sqlUpdJOBL);
													
												// 2. UPDATE ITEM LIST
													$ITM_OUT	= 0;
													$UM_VOLM	= 0;
													$UM_AMOUNT	= 0;
													$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
																		WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
																			AND ITM_CODE = '$ITM_CODE'";
													$resUSED1	= $this->db->query($sqlUSED1)->result();
													foreach($resUSED1 as $rowUSED1):
														$ITM_USED	= $rowUSED1->ITM_USED;
														$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
													endforeach;
													$sqlUpdITML	= "UPDATE tbl_item SET
																		ITM_LASTP	= $ITM_PRICE,
																		ITM_OUT 	= ITM_OUT+$ITM_VOLM,
																		ITM_OUTP 	= $ITM_PRICE,
																		UM_VOLM 	= UM_VOLM+$ITM_VOLM,
																		UM_AMOUNT 	= UM_AMOUNT+$Base_Debet
																	WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
													$this->db->query($sqlUpdITML);
											// END : Update ITM Used
										}
									// END : UPDATE L/R
									
								}
								$Base_DebetTOT		= $Base_DebetTOT + $Base_Debet;
							}
							
							$JournalD_Debet_tax		= 0;
							$Base_Debet_tax			= 0;
							$COA_Debet_tax			= 0;
							$JournalD_Kredit_tax	= 0;
							$Base_Kredit_tax		= 0;
							$COA_Kredit_tax			= 0;
							
							$curr_rate				= 1;
							$isDirect				= 1;
							$Ref_Number				= addslashes($d['Ref_Number']);
							$Other_Desc				= addslashes($d['Other_Desc']);
							if($Other_Desc == '')
								$Other_Desc			= $JournalH_Desc;

							$Journal_DK				= $JournalD_Pos;
							$Journal_Type			= $Journal_Type;
							$isTax					= $isTax;
							
							// Insert into tbl_journal_detail (D) for All Expenses
							$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet, 
											JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
											Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE, ITM_UNIT, Ref_Number, Other_Desc,
											Journal_DK, Journal_Type, isTax, JOBCODEID, Acc_Name, PattNum)
											VALUES
											('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
											$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit,
											$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
											'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_PRICE', '$ITM_UNIT', '$Ref_Number', '$Other_Desc', 'D', 
											'$Journal_Type', $isTax, '$JOBCODEID', '$Acc_Name', $PattNum)";
							$this->db->query($insSQL);
						}
						
						$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
					// END : DEBET
			
					$Acc_Name 		= "-";
					$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
					$resACCNm		= $this->db->query($sqlACCNm)->result();
					foreach($resACCNm as $rowACCNm):
						$Acc_Name	= $rowACCNm->Acc_Name;
					endforeach;

					$PattNumK 		= $PattNum+1;
					// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
						$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, Acc_Name, ISPERSL, PattNum) VALUES
											('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
											$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$ITM_CODE', 
											'$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'K', '$Journal_Type', $isTax, '$Acc_Name', $ISPERSL, $PattNumK)";
						$this->db->query($insSQLK);
					// END : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
				}

				// UPDATE AMOUNT HEADER
					$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount, GJournal_Total = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
				
				if($AH_ISLAST == 1)
				{
					$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);


					if($ISPERSL == 1)
					{
						$upJHA	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($upJHA);

						// START : UPDATE LAWAN KAS/BANK
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_PERSL' LIMIT 1";
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
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$BaseDebetTOT, 
														Base_Debet2 = Base_Debet2+$BaseDebetTOT, BaseD_$accYr = BaseD_$accYr+$BaseDebetTOT
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_PERSL'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : UPDATE KAS/BANK

						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
													'DOC_CODE' 		=> $JournalH_Code,
													'DOC_STAT' 		=> $GEJ_STAT,
													'PRJCODE' 		=> $PRJCODE,
													//'CREATERNM'		=> '',
													'TBLNAME'		=> "tbl_journalheader");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS

						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
													'DOC_CODE' 		=> $JournalH_Code,
													'DOC_STAT' 		=> $GEJ_STAT,
													'PRJCODE' 		=> $PRJCODE,
													//'CREATERNM'		=> '',
													'TBLNAME'		=> "tbl_journalheader_pd");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}
					else
					{
						// START : UPDATE KAS/BANK
							$ACC_NUM		= $acc_number;				
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
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$BaseDebetTOT, 
														Base_Kredit2 = Base_Kredit2+$BaseDebetTOT, BaseK_$accYr = BaseK_$accYr+$BaseDebetTOT
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$acc_number'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : UPDATE KAS/BANK

						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
													'DOC_CODE' 		=> $JournalH_Code,
													'DOC_STAT' 		=> $GEJ_STAT,
													'PRJCODE' 		=> $PRJCODE,
													//'CREATERNM'		=> '',
													'TBLNAME'		=> "tbl_journalheader");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}
				}
				
				// START : UPDATE STAT DET
					$this->load->model('m_updash/m_updash', '', TRUE);				
					$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
					$this->m_updash->updSTATJD($paramSTAT);
				// END : UPDATE STAT DET
			}
			elseif($GEJ_STAT == 4)
			{
				$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJHA);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
					
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($JournalH_Code);
				// END : DELETE HISTORY

				if($ISPERSL == 1)
				{
					$upJHA	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
						
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_pd");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			elseif($GEJ_STAT == 5)
			{
				$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJHA);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				if($ISPERSL == 1)
				{
					$upJHA	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
						
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_pd");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			else 		// IF NEW CONFIRMED
			{
				// UPDATE STATUS HEADER
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);

				// RESET DETAIL
					$this->m_cho_payment->deleteCPRJDetail($JournalH_Code);

				if($ISPERSL == 0)
				{
					// START : DETAIL JOURNAL
						// START : DEBET
							$Base_DebetTOT		= 0;
							$Base_DebetTOT_Tax	= 0;
							$PattNum 			= 0;
							foreach($_POST['data'] as $d)
							{
								$PattNum 		= $PattNum+1;
								$JournalH_Code	= $JournalH_Code;
								$Acc_Id			= $d['Acc_Id'];
								$ITM_CODE		= $d['ITM_CODE'];
								$proj_Code		= $d['proj_Code'];
								$JOBCODEID		= $d['JOBCODEID'];
								$JournalD_Pos	= $d['JournalD_Pos'];
								$isTax			= $d['isTax'];
								$ITM_GROUP		= $d['ITM_CATEG'];
								$ITM_VOLM		= $d['ITM_VOLM'];
								$ITM_UNIT		= $d['ITM_UNIT'];
								$ITM_PRICE		= $d['ITM_PRICE'];
								
								$PRJCODE		= $d['proj_Code'];
								$ACC_NUM		= $d['Acc_Id'];			// Detail Account
								$isHO			= 0;
								$syncPRJ		= '';
								$Acc_Name 		= "-";
								$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
									$Acc_Name	= $rowISHO->Acc_Name;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
								
								$ITM_Amount	= $d['JournalD_Amount'];
								$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

								$Journal_Type		= '';
								$isTax				= 0;

								$JournalD_Debet		= $d['JournalD_Amount'];
								$Base_Debet			= $d['JournalD_Amount'];
								$COA_Debet			= $d['JournalD_Amount'];
								$JournalD_Kredit 	= 0;
								$Base_Kredit		= 0;
								$COA_Kredit			= 0;
								
								$Base_DebetTOT		= $Base_DebetTOT + $Base_Debet;
								
								$JournalD_Debet_tax	= 0;
								$Base_Debet_tax		= 0;
								$COA_Debet_tax		= 0;
								$JournalD_Kredit_tax= 0;
								$Base_Kredit_tax	= 0;
								$COA_Kredit_tax		= 0;

								
								$curr_rate			= 1;
								$isDirect			= 1;
								$Ref_Number			= addslashes($d['Ref_Number']);
								$Other_Desc				= addslashes($d['Other_Desc']);
								if($Other_Desc == '')
									$Other_Desc			= $JournalH_Desc;
								
								$Journal_DK			= $JournalD_Pos;
								$Journal_Type		= $Journal_Type;
								$isTax				= $isTax;
								
								// Insert into tbl_journal_detail (D) for All Expenses
								$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID, Currency_id, JournalD_Debet, 
												JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
												Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
												curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_UNIT, ITM_PRICE, Ref_Number, Other_Desc,
												Journal_DK, Journal_Type, isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, PattNum) VALUES
												('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID', 'IDR', $JournalD_Debet, 
												$JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, 
												$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
												'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$Ref_Number', '$Other_Desc', 'D', 
												'$Journal_Type', $isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $PattNum)";
								$this->db->query($insSQL);
							}
						
							$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
						// END : DEBET
						
						$Acc_Name 		= "-";
						$sqlACCNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
						$resACCNm		= $this->db->query($sqlACCNm)->result();
						foreach($resACCNm as $rowACCNm):
							$Acc_Name	= $rowACCNm->Account_NameId;
						endforeach;

						$PattNumK 		= $PattNum+1;
						// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
							$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
												JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
												curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
												isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, PattNum) VALUES
												('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
												$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$Ref_Number', 
												'$Other_Desc', 'K', '$Journal_Type', $isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $PattNumK)";
							$this->db->query($insSQLK);
						// END : KREDIT
					// END : DETAIL JOURNAL
			
					// UPDATE AMOUNT HEADER
						$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($upJH3);
			
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				elseif($ISPERSL == 1)
				{
					// UPDATE STATUS HEADER
						$upJH	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($upJH);

					// RESET DETAIL
						$this->m_cho_payment->deleteCPRJDetail_pd($JournalH_Code);

					$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');

					// Account Detail Lawan Kas Bank
						$Acc_Name1 	= "-";
						$sqlACCNm1 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_PERSL' LIMIT 1";
						$resACCNm1		= $this->db->query($sqlACCNm1)->result();
						foreach($resACCNm1 as $rowACCNm1):
							$Acc_Name1	= $rowACCNm1->Acc_Name;
						endforeach;

					// Account Detail Kas Bank
						$Acc_Name2 	= "-";
						$sqlACCNm2 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
						$resACCNm2		= $this->db->query($sqlACCNm2)->result();
						foreach($resACCNm2 as $rowACCNm2):
							$Acc_Name2	= $rowACCNm2->Acc_Name;
						endforeach;

					// START : RESET DETAIL
						$this->m_cho_payment->deleteCPRJDetail_pd($JournalH_Code);

					$PattNumD 		= 1;
					$PattNumK 		= 2;
					// START : DEBET
						$insSQLD	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
											JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
						$this->db->query($insSQLD);
					// END : DEBET

					// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
						$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
						$this->db->query($insSQLK);
					// END : KREDIT

					// START : DEBET - PD TABLE
						$insSQLD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
											JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
						$this->db->query($insSQLD);
					// END : DEBET - PD TABLE

					// START : KREDIT - PD TABLE
						$insSQLK	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
											('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, $Journal_Amount, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
						$this->db->query($insSQLK);
					// END : KREDIT - PD TABLE

					$BaseDebetTOT 	= $Journal_Amount;

					if($GEJ_STAT == 2)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$PD_VAL 	= $BaseDebetTOT;
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $JournalH_Date,
												'FVAL'		=> $PD_VAL,
												'FNAME'		=> "PD_VAL");										
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}
			
					// UPDATE AMOUNT HEADER
						$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($upJH3);
			
					// UPDATE AMOUNT HEADER
						$upJH3	= "UPDATE tbl_journalheader_pd SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($upJH3);
			
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_pd");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				
				// START : UPDATE STAT DET
					$this->load->model('m_updash/m_updash', '', TRUE);				
					$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
					$this->m_updash->updSTATJD($paramSTAT);
				// END : UPDATE STAT DET
			}
			
			// DELETE TABLE TEMPORARY
				$s_delTMP 	= "DELETE FROM tbl_journaldetail_tmp WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE'";
				$this->db->query($s_delTMP);

			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET

			// START : UPDATE AKUN LAWAN
				$Acc_Cr 		= "";
				$sqlACC_Cr 		= "SELECT Acc_Id FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE' AND Journal_DK = 'K' AND JournalH_Code = '$JournalH_Code'";
				$resACC_Cr		= $this->db->query($sqlACC_Cr)->result();
				foreach($resACC_Cr as $row_Cr):
					$Acc_Cr		= $row_Cr->Acc_Id;
				endforeach;
				$Acc_Cr 		= $acc_number;		// LANGSUNG DARI AKUN KAS/BANK YANG DIGUNAKAN

				$updAcc_Db		= "UPDATE tbl_journaldetail SET Acc_Id_Cross = '$Acc_Cr' WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'";
				$this->db->query($updAcc_Db);
			// END : UPDATE AKUN LAWAN
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN359';
				$TTR_CATEG		= 'U-P';
				
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
			
			$url			= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up0b28t18VCASH() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$MenuCode 			= 'MN045';
			$data["MenuCode"] 	= 'MN045';
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 				= $this->m_cho_payment->get_VCASH_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['Reference_Number']	= $getGEJ->Reference_Number;
			$data['Reference_Date']	= $getGEJ->Reference_Date;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['PPNH_Amount'] 	= $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] 	= $getGEJ->PPHH_Amount;
			$data['GJournal_Total'] = $getGEJ->GJournal_Total;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;

			$mnCode					= 'MN045';
			$data["MenuCode"] 		= 'MN045';
			$data["MenuApp"] 		= 'MN045';
			$MenuCode 				= 'MN045';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			$getMN					= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] 	= $getMN->menu_name_IND;
			else
				$data["mnName"] 	= $getMN->menu_name_ENG;

			$data['form_action']	= site_url('c_finance/c_cho70d18/update_processVCASH');

			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			//$data['form_action']= site_url('c_finance/c_cho70d18/update_process');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				//$MenuCode 	= 'MN359';
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

			$this->load->view('v_finance/v_cho_payment/v_cho_vcash_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_processVCASH() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$JournalType 	= $this->input->post('JournalType');
		$jrnType 		= $this->input->post('JournalType');

		$MenuCode		= 'MN045';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');

			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$accYr			= date('Y', strtotime($JournalH_Date));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$ManualNo		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			$PPNH_Amount 	= $this->input->post('PPNH_Amount');
			$PPHH_Amount 	= $this->input->post('PPHH_Amount');
			$GJournal_Total = $this->input->post('GJournal_Total');

			$JournalH_Desc 	= $this->input->post('JournalH_Desc');
			$JOURNL_DESC	= $this->input->post('JournalH_Desc');
			$JOURNL_DESC	= $this->input->post('JournalH_Desc');
			$Reference_Number	= $this->input->post('Reference_Number');
			$Reference_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Reference_Date'))));
			$Reference_Numb	= $this->input->post('Reference_Number');

			$SPLCODE 		= $PERSL_EMPID;
			$SPLDESC 		= "";
			$s_emp			=  "SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
			$r_emp 			= $this->db->query($s_emp)->result();
			foreach($r_emp as $rw_emp) :
				$SPLDESC	= $rw_emp->EMP_NAME;
			endforeach;

			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('JournalH_Desc2');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJHA);
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN045',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $JournalH_Date,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
			// START : UPDATE HEADER
				$projGEJH 	= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> $JournalType,
									'Reference_Number'	=> $Reference_Number,
									'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> $JournalType,
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'ISPERSL'			=> $ISPERSL,
									'PERSL_EMPID'		=> $PERSL_EMPID,
									'SPLCODE'			=> $SPLCODE,
									'SPLDESC'			=> $SPLDESC,
									'acc_number'		=> $acc_number,
									'Journal_Amount'	=> $Journal_Amount,
									'PPNH_Amount'		=> $PPNH_Amount,
									'PPHH_Amount'		=> $PPHH_Amount,
									'GJournal_Total'	=> $GJournal_Total);
				$this->m_cho_payment->updateCHO_vcash($JournalH_Code, $projGEJH);
			// END : UPDATE HEADER

			if($GEJ_STAT == 3)
			{
				// DEFAULT STATUS IF APPROVE
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'PRJCODE'		=> $PRJCODE,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY

				// UPDATE STATUS HEADER
					/*$upJH	= "UPDATE tbl_journalheader_vcash SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);*/

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader_vcash");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// $this->m_cho_payment->updateCHO_vcash($JournalH_Code, $projGEJH);
				
				if($AH_ISLAST == 0)
				{
					// START : PROCEDURE UPDATE JOBLISTDETAIL
						$compVAR 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $JournalH_Date,
											'DOC_NUM'	=> $JournalH_Code,
											'DOC_CATEG'	=> "VCASH");
						$this->m_updash->updJOBM($compVAR);
					// END : PROCEDURE UPDATE JOBLISTDETAIL
				}

				// RESET DETAIL
					$this->m_cho_payment->deleteCPRJDetail($JournalH_Code);
					$this->m_cho_payment->deleteCPRJDetail_vcash($JournalH_Code);

				$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');
				$acc_number 	= $this->input->post('acc_number');

				// START : DETAIL JOURNAL
					$Base_DebetTOT		= 0;
					$Base_DebetTOT_Tax	= 0;
					$PattNum 			= 0;
					$TAX_DATE 			= '';
					$TAX_NO 			= '';
					foreach($_POST['data'] as $d)
					{
						$PattNum 		= $PattNum+1;
						$JournalH_Code	= $JournalH_Code;
						$Acc_Id			= $d['Acc_Id'];
						$ITM_CODE		= $d['ITM_CODE'];
						$proj_Code		= $d['proj_Code'];
						$JOBCODEID		= $d['JOBCODEID'];
						$JournalD_Pos	= $d['JournalD_Pos'];
						$isTax			= $d['isTax'];
						$ITM_GROUP		= $d['ITM_CATEG'];
						$ITM_VOLM		= $d['ITM_VOLM'];
						$ITM_UNIT		= $d['ITM_UNIT'];
						$ITM_PRICE		= $d['ITM_PRICE'];
						
						$PRJCODE		= $d['proj_Code'];
						$ACC_NUM		= $d['Acc_Id'];			// Detail Account
						$isHO			= 0;
						$syncPRJ		= '';
						$Acc_Name 		= "-";
						$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
							$Acc_Name	= $rowISHO->Acc_Name;
						endforeach;

						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
						
						$ITM_Amount	= $d['JournalD_Amount'];
						$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

						$Journal_Type		= $jrnType;
						$isTax				= 0;

						$JournalD_Debet		= $d['JournalD_Amount'];
						$Base_Debet			= $d['JournalD_Amount'];
						$COA_Debet			= $d['JournalD_Amount'];
						$JournalD_Kredit 	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
						
						$JournalD_Debet_tax	= 0;
						$Base_Debet_tax		= 0;
						$COA_Debet_tax		= 0;
						$JournalD_Kredit_tax= 0;
						$Base_Kredit_tax	= 0;
						$COA_Kredit_tax		= 0;

						
						$curr_rate			= 1;
						$isDirect			= 1;
						$TAX_DATE			= addslashes($d['TAX_DATE']);
						$TAX_NO				= addslashes($d['TAX_NO']);
						$Other_Desc			= addslashes($d['Other_Desc']);
						$Journal_DK			= $JournalD_Pos;
						$Journal_Type		= $Journal_Type;
						$isTax				= $isTax;
						$PPN_Code			= $d['PPN_Code'];
						$PPN_Perc			= $d['PPN_Perc'];
						$PPN_Amount			= $d['PPN_Amount'];
						$PPH_Code			= $d['PPH_Code'];
						$PPH_Perc			= $d['PPH_Perc'];
						$PPH_Amount			= $d['PPH_Amount'];
						$isVerified			= $d['isVerified'];
						$Other_DescD 		= "$SPLDESC. $Other_Desc $ManualNo";

						// GET ITM_NAME
							$ITM_NAME		= '';
							$ITM_CODE_H		= '';
							$ITM_TYPE		= '';
							$ACC_ID 		= '';
							$ITM_GROUP 		= '';
							$ITM_CATEG 		= '';
							$sqlITMNM		= "SELECT ITM_NAME, ITM_TYPE, ITM_UNIT, ACC_ID_UM AS ACC_ID, ITM_GROUP
												FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
							$resITMNM		= $this->db->query($sqlITMNM)->result();
							foreach($resITMNM as $rowITMNM) :
								$ITM_NAME	= $rowITMNM->ITM_NAME;
								$ITM_TYPE	= $rowITMNM->ITM_TYPE;
								$ITM_UNIT	= $rowITMNM->ITM_UNIT;
								$ACC_ID		= $rowITMNM->ACC_ID;
								$ITM_GROUP	= $rowITMNM->ITM_GROUP;
							endforeach;

						/*
							PERHATIAN ...!!! (2022-02-11) BY DIAN HERMANTO
							JIKA ADA PPN DAN PPH MAKA
							1. PADA TABEL tbl_journal_detail akan dilakukan pemisahan antara DPP dengan PPN, PPH
							2. PADA TABEL tbl_journal_detail_vcash akan tetap disatukan
							3. PADA SAAT PENARIKAAN JURNAL UNTUK PEMBAYARAN WAJIB DIAMBIL DARI TABEL tbl_journal_detail BUKAN tbl_journal_detail_vcash
							4. PPN DAN PPH TERBENTUK SAAT APPROVE
						*/

						// START : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET
							$insSQL	= "INSERT INTO tbl_journaldetail_vcash (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
											Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
											Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
											COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
											ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
											GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
											PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, SPLCODE, SPLDESC, isVerified) VALUES
											('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID',
											'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
											$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
											$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
											'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'D', '$Journal_Type', $isTax, 
											'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2,
											'$PPN_Code', $PPN_Perc, $PPN_Amount, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$SPLCODE', '$SPLDESC', $isVerified)";
							$this->db->query($insSQL);
						// END : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET

						if($AH_ISLAST == 1)
						{
							/*
								BEB. KONSULTAN	 	1,000,000.00 	
								PPN		 			  100,000.00 
								PPH	 				  				 20,000.00 	
								HUT. SUPPLIER		 			  1,080,000.00 ---->>>> (1,000,000.00 + 100,000.00 - 20,000.00)
							*/

							// START : POSTING BEBAN
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$syncPRJ	= $rowISHO->syncPRJ;
									$Acc_Name	= $rowISHO->Acc_Name;
								endforeach;

								// START : INSERT INTO JOURNAL DETAIL (BEBAN)					--- DEBET
									$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
													Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
													Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
													COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
													ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
													GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
													PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, SPLCODE, SPLDESC, isVerified) VALUES
													('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID',
													'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
													$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
													$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
													'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'D', '$Journal_Type', $isTax, 
													'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2,
													'$PPN_Code', $PPN_Perc, $PPN_Amount, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$SPLCODE', '$SPLDESC', $isVerified)";
									$this->db->query($insSQL);
								// END : INSERT INTO JOURNAL DETAIL (BEBAN)						--- DEBET

								// START : PROCEDURE UPDATE JOBLISTDETAIL
									$compVAR 	= array('PRJCODE'	=> $PRJCODE,
														'PERIODE'	=> $JournalH_Date,
														'DOC_NUM'	=> $JournalH_Code,
														'DOC_CATEG'	=> "VCASH");
									$this->m_updash->updJOBPAPP($compVAR);
								// END : PROCEDURE UPDATE JOBLISTDETAIL

								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
								
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
															Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : POSTING BEBAN

							// START : INSERT PPN (JIKA ADA)								--- DEBET
								if($PPN_Amount > 0)
								{
									$ACC_PPN		= '';
									$s_PPN 			= "SELECT TAXLA_LINKOUT FROM tbl_tax_ppn WHERE TAXLA_NUM = '$PPN_Code'";
									$r_PPN			= $this->db->query($s_PPN)->result();
									foreach($r_PPN as $rw_PPN):
										$ACC_PPN	= $rw_PPN->TAXLA_LINKOUT;
									endforeach;

									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_PPN' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$syncPRJ	= $rowISHO->syncPRJ;
										$Acc_Name	= $rowISHO->Acc_Name;
									endforeach;

									$JournalD_Debet		= $d['PPN_Amount'];
									$Base_Debet			= $d['PPN_Amount'];
									$COA_Debet			= $d['PPN_Amount'];
									$JournalD_Kredit 	= 0;
									$Base_Kredit		= 0;
									$COA_Kredit			= 0;
									$isTax 				= 1;
									$PPN_D 				= "PPN - $Other_Desc";
									$insSQL2	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
														Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
														Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
														COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
														ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
														GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
														PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, SPLCODE, SPLDESC, isVerified) VALUES
														('$JournalH_Code', '$ACC_PPN', '$JournalType', '$proj_Code', '$JOBCODEID',
														'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
														$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
														$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
														'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_DescD', 'D', '$Journal_Type', $isTax, 
														'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2,
														'$PPN_Code', $PPN_Perc, $PPN_Amount, '', 0, 0, $PattNum, '$SPLCODE', '$SPLDESC', $isVerified)";
									$this->db->query($insSQL2);

									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
									
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$PPN_Amount, 
																Base_Debet2 = Base_Debet2+$PPN_Amount, BaseD_$accYr = BaseD_$accYr+$PPN_Amount
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_PPN'";
											$this->db->query($sqlUpdCOA);
										}
									}
								}
							// END : INSERT PPN (JIKA ADA)									--- DEBET

							// START : INSERT PPH (JIKA ADA)								--- KREDIT
								if($PPH_Amount > 0)
								{
									// PERHATIKAN ..... !!!! JIKA ADA PPH_FINAL
									// APAKAH AKUN PPH INI TERMASUK PPH FINAL ATAU NON FINAL
									// JIKA PPH FINAL, MAKA DI JOURNAL PADA SAAT PEMBAYARAN
									// JIKA PPH NON FINAL, DIBENTUK PADA SAAT VOUCHER/FAKTUR

									$ACC_PPH		= '';
									$s_PPN 			= "SELECT TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$PPH_Code'";
									$r_PPN			= $this->db->query($s_PPN)->result();
									foreach($r_PPN as $rw_PPN):
										$ACC_PPH	= $rw_PPN->TAXLA_LINKOUT;
									endforeach;

									$isPPhFin 		= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name, isPPhFinal FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_PPH' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$syncPRJ	= $rowISHO->syncPRJ;
										$Acc_Name	= $rowISHO->Acc_Name;
										$isPPhFin	= $rowISHO->isPPhFinal;
									endforeach;

									if($isPPhFin == 0)
									{
										$JournalD_Debet		= 0;
										$Base_Debet			= 0;
										$COA_Debet			= 0;
										$JournalD_Kredit 	= $d['PPH_Amount'];
										$Base_Kredit		= $d['PPH_Amount'];
										$COA_Kredit			= $d['PPH_Amount'];
										$isTax 				= 1;
										$insSQL2	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
															Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
															Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
															COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
															ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
															GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
															PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, SPLCODE, SPLDESC, isVerified) VALUES
															('$JournalH_Code', '$ACC_PPH', '$JournalType', '$proj_Code', '$JOBCODEID',
															'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
															$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
															$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
															'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_DescD', 'K', '$Journal_Type', $isTax, 
															'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2,
															'', 0, 0, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$SPLCODE', '$SPLDESC', $isVerified)";
										$this->db->query($insSQL2);

										$dataPecah 	= explode("~",$syncPRJ);
										$jmD 		= count($dataPecah);
										
										if($jmD > 0)
										{
											$SYNC_PRJ	= '';
											for($i=0; $i < $jmD; $i++)
											{
												$SYNC_PRJ	= $dataPecah[$i];
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PPH_Amount, 
																	Base_Kredit2 = Base_Kredit2+$PPH_Amount, BaseK_$accYr = BaseK_$accYr+$PPH_Amount
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_PPH'";
												$this->db->query($sqlUpdCOA);
											}
										}
									}
								}
							// END : INSERT PPH (JIKA ADA)									--- KREDIT

							// START : UPDATE ITEM USED
								// 1. UPDATE JOBLIST
									$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
														ITM_LASTP	= $ITM_PRICE,
														ITM_USED 	= ITM_USED+$ITM_VOLM, 
														ITM_USED_AM = ITM_USED_AM+$Base_Debet,
														REQ_VOLM 	= REQ_VOLM+$ITM_VOLM, 
														REQ_AMOUNT 	= REQ_AMOUNT+$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
														AND ITM_CODE = '$ITM_CODE'";
									$this->db->query($sqlUpdJOBL);

								// 2. UPDATE ITEM LIST
									$sqlUpdITML	= "UPDATE tbl_item SET
														ITM_LASTP	= $ITM_PRICE,
														ITM_OUT 	= ITM_OUT+$ITM_VOLM,
														ITM_OUTP 	= $ITM_PRICE,
														UM_VOLM 	= UM_VOLM+$ITM_VOLM,
														UM_AMOUNT 	= UM_AMOUNT+$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
									$this->db->query($sqlUpdITML);			
							// END : Update ITM Used

							// START : UPDATE L/R
								if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'SC')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'GE')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								
								if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
								{
									// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
									if($ITM_TYPE == 1)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 9)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 10)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								}
								elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
								{
									$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
						}
						else
						{
							// START : PROCEDURE UPDATE JOBLISTDETAIL
								$JOBID 		= $d['JOBCODEID'];
								$ITM_CODE	= $d['ITM_CODE'];
								$DOC_VOLM	= $d['ITM_VOLM'];
								$DOC_TOTAL	= $Base_Debet;
								$compVAR 	= array('PRJCODE'	=> $PRJCODE,
													'PERIODE'	=> $JournalH_Date,
													'JOBID'		=> $JOBID,
													'ITM_CODE'	=> $ITM_CODE,
													'DOC_VOLM'	=> $DOC_VOLM,
													'DOC_TOTAL'	=> $DOC_TOTAL,
													'VAR_VOL_R'	=> "VCASH_VOL_R",
													'VAR_VAL_R'	=> "VCASH_VAL_R");
								$this->m_updash->updJOBP($compVAR);
							// END : PROCEDURE UPDATE JOBLISTDETAIL
						}
					}

					// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT
						/*
							BEB. KONSULTAN	 	1,000,000.00 	
							PPN		 			  100,000.00 
							PPH	 				  				 20,000.00 	
							HUT. SUPPLIER		 			  1,080,000.00 ---->>>> (1,000,000.00 + 100,000.00 - 20,000.00)

							$totHUTSPL 	= $Journal_Amount + $PPNH_Amount - $PPHH_Amount;
						*/

						$Acc_Name 		= "-";
						$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
						$resACCNm		= $this->db->query($sqlACCNm)->result();
						foreach($resACCNm as $rowACCNm):
							$Acc_Name	= $rowACCNm->Acc_Name;
						endforeach;

						$totHUTSPL 			= $Journal_Amount + $PPNH_Amount - $PPHH_Amount;
						$JournalD_Kredit 	= $totHUTSPL;
						$Base_Kredit		= $totHUTSPL;
						$COA_Kredit			= $totHUTSPL;
						$isTax 				= 0;
						$PattNumK 			= $PattNum+1;
						$Other_Desc 		= "$SPLDESC. $JOURNL_DESC $ManualNo";

						// START : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
							$insSQLK	= "INSERT INTO tbl_journaldetail_vcash (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
												JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
												curr_rate, isDirect, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type,
												isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, SPLCODE, SPLDESC) VALUES
												('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $JournalD_Kredit,
												0, $JournalD_Kredit, 0, $JournalD_Kredit, 0,
												1, 1, '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'K', '$Journal_Type',
												$isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2, $PattNumK, '$SPLCODE', '$SPLDESC')";
							$this->db->query($insSQLK);
						// END : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT

						if($AH_ISLAST == 1)
						{
							// START : INSERT INTO JOURNAL DETAIL ((HUTANG SUPPLIER)						--- KREDIT
								$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
													JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
													curr_rate, isDirect, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type,
													isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, SPLCODE, SPLDESC) VALUES
													('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $JournalD_Kredit,
													0, $JournalD_Kredit, 0, $JournalD_Kredit, 0,
													1, 1, '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'K', '$Journal_Type',
													$isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2, $PattNumK, '$SPLCODE', '$SPLDESC')";
								$this->db->query($insSQLK);
							// END : KREDIT

							// START : POSTING HUTANG SUPPLIER
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
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
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$totHUTSPL, 
																Base_Kredit2 = Base_Kredit2+$totHUTSPL, BaseK_$accYr = BaseK_$accYr+$totHUTSPL
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$acc_number'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : UPDATE KAS/BANK
						}
					// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT
				// END : DETAIL JOURNAL

				// START : UPDATE JOURNAL HEADER
					$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
									GJournal_Total = $GJournal_Total
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);

					$upJH3	= "UPDATE tbl_journalheader_vcash SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
									GJournal_Total = $GJournal_Total
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
				// END : UPDATE JOURNAL HEADER
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader_vcash");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				if($AH_ISLAST == 1)
				{
					// START - HEADER
						$AH_CODE		= $JournalH_Code;
						$AH_APPLEV		= $this->input->post('APP_LEVEL');
						$AH_APPROVER	= $DefEmp_ID;
						$AH_APPROVED	= date('Y-m-d H:i:s');
						$AH_NOTES		= $this->input->post('JournalH_Desc');
						$AH_ISLAST		= $this->input->post('IS_LAST');
						
						$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
												'JournalH_Desc' 	=> $AH_NOTES,
												'Manual_No' 		=> "J".$Manual_No,
												'JournalType' 		=> $JournalType,
												'Reference_Number' 	=> $Reference_Number,
												'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
												'JournalH_Date'		=> $JournalH_Date,
												'Company_ID'		=> $comp_init,
												'Reference_Type'	=> $JournalType,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $GEJ_CREATED,
												'Created'			=> $GEJ_CREATED,
												'Wh_id'				=> $PRJCODE,
												'proj_Code'			=> $PRJCODE,
												'proj_CodeHO'		=> $proj_CodeHO,
												'PRJPERIOD'			=> $PRJPERIOD,
												'GEJ_STAT'			=> $GEJ_STAT,
												'ISPERSL'			=> $ISPERSL,
												'PERSL_EMPID'		=> $PERSL_EMPID,
												'SPLCODE'			=> $SPLCODE,
												'SPLDESC'			=> $SPLDESC,
												'acc_number'		=> $acc_number,
												'PPNH_Amount'		=> $PPNH_Amount,
												'PPHH_Amount'		=> $PPHH_Amount,
												'GJournal_Total'	=> $GJournal_Total); 
						$this->m_cho_payment->add($projGEJH);
					// END - HEADER

					$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);

					$upJHA	= "UPDATE tbl_journalheader_vcash SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_vcash");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				elseif($AH_ISLAST == 0)
				{
					// START : CREATE ALERT LIST
						$APP_LEVEL 	= $this->input->post('APP_LEVEL');
						$alertVar 	= array('PRJCODE'	=> $PRJCODE,
										'AS_CATEG'		=> "VCASH",
										'AS_MNCODE'		=> "MN045",
										'AS_DOCNUM'		=> $JournalH_Code,
										'AS_DOCCODE'	=> $Manual_No,
										'AS_DOCDATE'	=> $JournalH_Date,
										'AS_EXPDATE'	=> $JournalH_Date,
										'APP_LEVEL'		=> $APP_LEVEL);
					$this->m_updash->updAALERT($alertVar);
					// END : CREATE ALERT LIST
				}
			}
			elseif($GEJ_STAT == 4)
			{
				$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJHA);

				$upJHA	= "UPDATE tbl_journalheader_vcash SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader_vcash");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
					
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($JournalH_Code);
				// END : DELETE HISTORY

				if($ISPERSL == 1)
				{
					$upJHA	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
						
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_pd");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			elseif($GEJ_STAT == 5)
			{
				$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJHA);

				$upJHA	= "UPDATE tbl_journalheader_vcash SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader_vcash");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				if($ISPERSL == 1)
				{
					$upJHA	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
						
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_pd");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}

				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $JournalH_Date,
										'DOC_NUM'	=> $JournalH_Code,
										'DOC_CATEG'	=> "VCASH");
					$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL
			}
			else 		// IF NEW OR CONFIRMED
			{
				// UPDATE STATUS HEADER
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);

					$upJH	= "UPDATE tbl_journalheader_vcash SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
				
				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $JournalH_Date,
										'DOC_NUM'	=> $JournalH_Code,
										'DOC_CATEG'	=> "VCASH");
					$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL

				// RESET DETAIL
					$this->m_cho_payment->deleteCPRJDetail($JournalH_Code);

				// RESET DETAIL
					$this->m_cho_payment->deleteCPRJDetail_vcash($JournalH_Code);

				// START : DETAIL JOURNAL
					$Base_DebetTOT		= 0;
					$Base_DebetTOT_Tax	= 0;
					$PattNum 			= 0;
					$TAX_DATE 			= '';
					$TAX_NO 			= '';
					foreach($_POST['data'] as $d)
					{
						$PattNum 		= $PattNum+1;
						$JournalH_Code	= $JournalH_Code;
						$Acc_Id			= $d['Acc_Id'];
						$ITM_CODE		= $d['ITM_CODE'];
						$proj_Code		= $d['proj_Code'];
						$JOBCODEID		= $d['JOBCODEID'];
						$JournalD_Pos	= $d['JournalD_Pos'];
						$isTax			= $d['isTax'];
						$ITM_GROUP		= $d['ITM_CATEG'];
						$ITM_VOLM		= $d['ITM_VOLM'];
						$ITM_UNIT		= $d['ITM_UNIT'];
						$ITM_PRICE		= $d['ITM_PRICE'];
						
						$PRJCODE		= $d['proj_Code'];
						$ACC_NUM		= $d['Acc_Id'];			// Detail Account
						$isHO			= 0;
						$syncPRJ		= '';
						$Acc_Name 		= "-";
						$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
							$Acc_Name	= $rowISHO->Acc_Name;
						endforeach;

						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
						
						$ITM_Amount	= $d['JournalD_Amount'];
						$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

						$Journal_Type		= $jrnType;
						$isTax				= 0;

						$JournalD_Debet		= $d['JournalD_Amount'];
						$Base_Debet			= $d['JournalD_Amount'];
						$COA_Debet			= $d['JournalD_Amount'];
						$JournalD_Kredit 	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
						
						$JournalD_Debet_tax	= 0;
						$Base_Debet_tax		= 0;
						$COA_Debet_tax		= 0;
						$JournalD_Kredit_tax= 0;
						$Base_Kredit_tax	= 0;
						$COA_Kredit_tax		= 0;

						
						$curr_rate			= 1;
						$isDirect			= 1;
						$TAX_DATE			= addslashes($d['TAX_DATE']);
						$TAX_NO				= addslashes($d['TAX_NO']);
						$Other_Desc			= addslashes($d['Other_Desc']);
						$Journal_DK			= $JournalD_Pos;
						$Journal_Type		= $Journal_Type;
						$isTax				= $isTax;
						$PPN_Code			= $d['PPN_Code'];
						$PPN_Perc			= $d['PPN_Perc'];
						$PPN_Amount			= $d['PPN_Amount'];
						$PPH_Code			= $d['PPH_Code'];
						$PPH_Perc			= $d['PPH_Perc'];
						$PPH_Amount			= $d['PPH_Amount'];
						$isVerified			= $d['isVerified'];

						/*
							PERHATIAN ...!!! (2022-02-11) BY DIAN HERMANTO
							JIKA ADA PPN DAN PPH MAKA
							1. PADA TABEL tbl_journal_detail akan dilakukan pemisahan antara DPP dengan PPN, PPH
							2. PADA TABEL tbl_journal_detail_vcash akan tetap disatukan
							3. PADA SAAT PENARIKAAN JURNAL UNTUK PEMBAYARAN WAJIB DIAMBIL DARI TABEL tbl_journal_detail BUKAN tbl_journal_detail_vcash
							4. PPN DAN PPH TERBENTUK SAAT APPROVE
						*/

						// START : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET
							$insSQL	= "INSERT INTO tbl_journaldetail_vcash (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
											Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
											Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
											COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
											ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
											GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
											PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, SPLCODE, SPLDESC, isVerified) VALUES
											('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID',
											'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
											$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
											$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
											'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'D', '$Journal_Type', $isTax, 
											'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2,
											'$PPN_Code', $PPN_Perc, $PPN_Amount, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$SPLCODE', '$SPLDESC', $isVerified)";
							$this->db->query($insSQL);
						// END : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET

						// START : PROCEDURE UPDATE JOBLISTDETAIL
							$JOBID 		= $d['JOBCODEID'];
							$ITM_CODE	= $d['ITM_CODE'];
							$DOC_VOLM	= $d['ITM_VOLM'];
							$DOC_TOTAL	= $Base_Debet;
							$compVAR 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $JournalH_Date,
												'JOBID'		=> $JOBID,
												'ITM_CODE'	=> $ITM_CODE,
												'DOC_VOLM'	=> $DOC_VOLM,
												'DOC_TOTAL'	=> $DOC_TOTAL,
												'VAR_VOL_R'	=> "VCASH_VOL_R",
												'VAR_VAL_R'	=> "VCASH_VAL_R");
							$this->m_updash->updJOBP($compVAR);
						// END : PROCEDURE UPDATE JOBLISTDETAIL
					}

					// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT
						$Acc_Name 		= "-";
						$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
						$resACCNm		= $this->db->query($sqlACCNm)->result();
						foreach($resACCNm as $rowACCNm):
							$Acc_Name	= $rowACCNm->Acc_Name;
						endforeach;

						$totHUTSPL 			= $Journal_Amount+$PPNH_Amount-$PPHH_Amount;
						$JournalD_Kredit 	= $totHUTSPL;
						$Base_Kredit		= $totHUTSPL;
						$COA_Kredit			= $totHUTSPL;
						$isTax 				= 0;
						$PattNumK 			= $PattNum+1;

						// START : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
							$insSQLK	= "INSERT INTO tbl_journaldetail_vcash (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
												JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
												curr_rate, isDirect, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type,
												isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, SPLCODE, SPLDESC) VALUES
												('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $JournalD_Kredit,
												0, $JournalD_Kredit, 0, $JournalD_Kredit, 0,
												1, 1, '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'K', '$Journal_Type',
												$isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 2, $PattNumK, '$SPLCODE', '$SPLDESC')";
							$this->db->query($insSQLK);
						// END : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
					// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT

					if($GEJ_STAT == 2)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$VCASH_VAL 	= $JournalD_Kredit;
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $JournalH_Date,
												'FVAL'		=> $VCASH_VAL,
												'FNAME'		=> "VCASH_VAL");										
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}
				// END : DETAIL JOURNAL

				// START : UPDATE JOURNAL HEADER
					$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
									GJournal_Total = $GJournal_Total, JournalH_Desc = '$JOURNL_DESC'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);

					$upJH3	= "UPDATE tbl_journalheader_vcash SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
									GJournal_Total = $GJournal_Total, JournalH_Desc = '$JOURNL_DESC'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
				// END : UPDATE JOURNAL HEADER
		
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader_vcash");
					$this->m_updash->updateStatus($paramStat);
				
				// START : UPDATE STAT DET
					$this->load->model('m_updash/m_updash', '', TRUE);				
					$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
					$this->m_updash->updSTATJD($paramSTAT);
				// END : UPDATE STAT DET
			}
			
			// DELETE TABLE TEMPORARY
				$s_delTMP 	= "DELETE FROM tbl_journaldetail_tmp WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE'";
				$this->db->query($s_delTMP);

			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET

			// START : UPDATE AKUN LAWAN
				$Acc_Cr 		= "";
				$sqlACC_Cr 		= "SELECT Acc_Id FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE' AND Journal_DK = 'K' AND JournalH_Code = '$JournalH_Code'";
				$resACC_Cr		= $this->db->query($sqlACC_Cr)->result();
				foreach($resACC_Cr as $row_Cr):
					$Acc_Cr		= $row_Cr->Acc_Id;
				endforeach;
				$Acc_Cr 		= $acc_number;		// LANGSUNG DARI AKUN KAS/BANK YANG DIGUNAKAN

				$upJH3			= "UPDATE tbl_journaldetail SET Acc_Id_Cross = '$Acc_Cr', Kwitansi_Date = '$Reference_Date', Kwitansi_No = '$Reference_Numb',
										SPLCODE = '$SPLCODE', SPLDESC = '$SPLDESC'
									WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);

				$upJH3			= "UPDATE tbl_journaldetail_vcash SET Acc_Id_Cross = '$Acc_Cr', Kwitansi_Date = '$Reference_Date', Kwitansi_No = '$Reference_Numb',
										SPLCODE = '$SPLCODE', SPLDESC = '$SPLDESC'
									WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
			// END : UPDATE AKUN LAWAN
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN359';
				$TTR_CATEG		= 'U-P';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if($GEJ_STAT == 2)
			{
				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "VCASH",
										'AS_MNCODE'		=> "MN045",
										'AS_DOCNUM'		=> $JournalH_Code,
										'AS_DOCCODE'	=> $Manual_No,
										'AS_DOCDATE'	=> $JournalH_Date,
										'AS_EXPDATE'	=> $JournalH_Date);
					$this->m_updash->updCALERT($alertVar);
				// END : CREATE ALERT LIST
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up0b28t18CPRJ() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$MenuCode 			= 'MN147';
			$data["MenuCode"] 	= 'MN147';
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 				= $this->m_cho_payment->get_CPRJ_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['PPNH_Amount'] 	= $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] 	= $getGEJ->PPHH_Amount;
			$data['GJournal_Total'] = $getGEJ->GJournal_Total;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;

			$mnCode					= 'MN147';
			$data["MenuCode"] 		= 'MN147';
			$data["MenuApp"] 		= 'MN147';
			$MenuCode 				= 'MN147';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			$getMN					= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] 	= $getMN->menu_name_IND;
			else
				$data["mnName"] 	= $getMN->menu_name_ENG;

			$data['form_action']	= site_url('c_finance/c_cho70d18/update_processCPRJ');

			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			//$data['form_action']= site_url('c_finance/c_cho70d18/update_process');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				//$MenuCode 	= 'MN359';
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

			if($DefEmp_ID == 'D15040004221')
				$this->load->view('v_finance/v_cho_payment/v_cho_cprj_form', $data);
			else
			{
				$this->load->view('v_finance/v_cho_payment/v_cho_cprj_form', $data);
				//$this->load->view('page_uc', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_processCPRJ() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$JournalType 	= $this->input->post('JournalType');
		$jrnType 		= $this->input->post('JournalType');

		$MenuCode		= 'MN147';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');

			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$accYr			= date('Y', strtotime($JournalH_Date));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');
			$acc_number		= $this->input->post('acc_number');

			$Manual_No		= $this->input->post('Manual_No');
			$ManualNo		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$ISPERSL 		= 3;
			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			$PPNH_Amount 	= $this->input->post('PPNH_Amount');
			$PPHH_Amount 	= $this->input->post('PPHH_Amount');
			$GJournal_Total = $this->input->post('GJournal_Total');

			$JournalH_Desc 	= $this->input->post('JournalH_Desc');
			$JOURNL_DESC	= $this->input->post('JournalH_Desc');
			$JOURNL_DESC	= $this->input->post('JournalH_Desc');

			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('JournalH_Desc2');
			$AH_ISLAST		= $this->input->post('IS_LAST');

			$SPLCODE 		= $PERSL_EMPID;
			$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$SPLCODE'
								UNION
								SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
			$r_emp 			= $this->db->query($s_emp)->result();
			foreach($r_emp as $rw_emp) :
				$SPLDESC	= $rw_emp->EMP_NAME;
			endforeach;
			
			$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJHA);
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN147',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $JournalH_Date,
										'ACC_ID'		=> $acc_number,
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
			// START : UPDATE HEADER
				$projGEJH 	= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> $JournalType,
									'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> $JournalType,
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'ISPERSL'			=> $ISPERSL,
									'PERSL_EMPID'		=> $PERSL_EMPID,
									'SPLCODE'			=> $SPLCODE,
									'SPLDESC'			=> $SPLDESC,
									'acc_number'		=> $acc_number,
									'Journal_Amount'	=> $Journal_Amount,
									'PPNH_Amount'		=> $PPNH_Amount,
									'PPHH_Amount'		=> $PPHH_Amount,
									'GJournal_Total'	=> $GJournal_Total);
				$this->m_cho_payment->updateCHO_cprj($JournalH_Code, $projGEJH);
			// END : UPDATE HEADER
			
			if($GEJ_STAT == 3)
			{
				// DEFAULT STATUS IF APPROVE
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'PRJCODE'		=> $PRJCODE,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY

				// UPDATE STATUS HEADER
					/*$upJH	= "UPDATE tbl_journalheader_vcash SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);*/

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader_cprj");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// $this->m_cho_payment->updateCHO_vcash($JournalH_Code, $projGEJH);
				
				// RESET DETAIL
					// 19 April 2022 $this->m_cho_payment->deleteCPRJDetail($JournalH_Code);
					// 19 April 2022 $this->m_cho_payment->deleteCPRJDetail_cprj($JournalH_Code);

				$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');
				$acc_number 	= $this->input->post('acc_number');

				// START : DETAIL JOURNAL
					$Base_DebetTOT		= 0;
					$Base_DebetTOT_Tax	= 0;
					$PattNum 			= 0;
					foreach($_POST['data'] as $d)
					{
						$PattNum 		= $PattNum+1;
						$JournalH_Code	= $JournalH_Code;
						$Acc_Id			= $d['Acc_Id'];
						$ITM_CODE		= $d['ITM_CODE'];
						$proj_Code		= $d['proj_Code'];
						$JOBCODEID		= $d['JOBCODEID'];
						$JournalD_Pos	= $d['JournalD_Pos'];
						$isTax			= $d['isTax'];
						$ITM_GROUP		= $d['ITM_CATEG'];
						$ITM_VOLM		= $d['ITM_VOLM'];
						$ITM_UNIT		= $d['ITM_UNIT'];
						$ITM_PRICE		= $d['ITM_PRICE'];
						
						$PRJCODE		= $d['proj_Code'];
						$ACC_NUM		= $d['Acc_Id'];			// Detail Account
						$isHO			= 0;
						$syncPRJ		= '';
						$Acc_Name 		= "-";
						$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
							$Acc_Name	= $rowISHO->Acc_Name;
						endforeach;

						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
						
						$ITM_Amount	= $d['JournalD_Amount'];
						$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

						$Journal_Type		= $jrnType;
						$isTax				= 0;

						$JournalD_Debet		= $d['JournalD_Amount'];
						$Base_Debet			= $d['JournalD_Amount'];
						$COA_Debet			= $d['JournalD_Amount'];
						$JournalD_Kredit 	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
						
						$JournalD_Debet_tax	= 0;
						$Base_Debet_tax		= 0;
						$COA_Debet_tax		= 0;
						$JournalD_Kredit_tax= 0;
						$Base_Kredit_tax	= 0;
						$COA_Kredit_tax		= 0;

						
						$curr_rate			= 1;
						$isDirect			= 1;
						$TAX_DATE			= addslashes($d['TAX_DATE']);
						$TAX_NO				= addslashes($d['TAX_NO']);
						$Other_Desc			= addslashes($d['Other_Desc']);
						$Journal_DK			= $JournalD_Pos;
						$Journal_Type		= $Journal_Type;
						$isTax				= $isTax;
						$PPN_Code			= $d['PPN_Code'];
						$PPN_Perc			= $d['PPN_Perc'];
						$PPN_Amount			= $d['PPN_Amount'];
						$PPH_Code			= $d['PPH_Code'];
						$PPH_Perc			= $d['PPH_Perc'];
						$PPH_Amount			= $d['PPH_Amount'];

						/*
							PERHATIAN ...!!! (2022-02-11) BY DIAN HERMANTO
							JIKA ADA PPN DAN PPH MAKA
							1. PADA TABEL tbl_journal_detail akan dilakukan pemisahan antara DPP dengan PPN, PPH
							2. PADA TABEL tbl_journal_detail_vcash akan tetap disatukan
							3. PADA SAAT PENARIKAAN JURNAL UNTUK PEMBAYARAN WAJIB DIAMBIL DARI TABEL tbl_journal_detail BUKAN tbl_journal_detail_vcash
							4. PPN DAN PPH TERBENTUK SAAT APPROVE
						*/

						// START : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET
							$insSQL	= "INSERT INTO tbl_journaldetail_cprj (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
											Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
											Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
											COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
											ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
											GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, 
											PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, Manual_No, SPLCODE, SPLDESC) VALUES
											('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID',
											'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
											$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
											$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
											'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'D', '$Journal_Type', $isTax, 
											'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $ISPERSL,
											'$PPN_Code', $PPN_Perc, $PPN_Amount, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$Manual_No', '$SPLCODE', '$SPLDESC')";
							// 19 April 2022 $this->db->query($insSQL);
						// END : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET

						if($AH_ISLAST == 1)
						{
							/*
								BEB. KONSULTAN	 	1,000,000.00 	
								PPN		 			  100,000.00 
								PPH	 				  				 20,000.00 	
								HUT. SUPPLIER		 			  1,080,000.00 ---->>>> (1,000,000.00 + 100,000.00 - 20,000.00)
							*/

							// START : POSTING BEBAN
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$syncPRJ	= $rowISHO->syncPRJ;
									$Acc_Name	= $rowISHO->Acc_Name;
								endforeach;

								$Other_DescD	= "$SPLDESC. $Other_Desc $ManualNo";

								// START : INSERT INTO JOURNAL DETAIL (BEBAN)					--- DEBET
									$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
													Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
													Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
													COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
													ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
													GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
													PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, Manual_No, SPLCODE, SPLDESC) VALUES
													('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID',
													'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
													$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
													$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
													'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'D', '$Journal_Type', $isTax, 
													'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $ISPERSL,
													'$PPN_Code', $PPN_Perc, $PPN_Amount, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$Manual_No', '$SPLCODE', '$SPLDESC')";
									$this->db->query($insSQL);
								// END : INSERT INTO JOURNAL DETAIL (BEBAN)						--- DEBET

								// START : PROCEDURE UPDATE JOBLISTDETAIL
									$compVAR 	= array('PRJCODE'	=> $PRJCODE,
														'PERIODE'	=> $JournalH_Date,
														'DOC_NUM'	=> $JournalH_Code,
														'DOC_CATEG'	=> "CPRJ");
									$this->m_updash->updJOBPAPP($compVAR);
								// END : PROCEDURE UPDATE JOBLISTDETAIL

								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
								
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
															Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : POSTING BEBAN

							// START : INSERT PPN (JIKA ADA)								--- DEBET
								if($PPN_Amount > 0)
								{
									$ACC_PPN		= '';
									$s_PPN 			= "SELECT TAXLA_LINKOUT FROM tbl_tax_ppn WHERE TAXLA_NUM = '$PPN_Code'";
									$r_PPN			= $this->db->query($s_PPN)->result();
									foreach($r_PPN as $rw_PPN):
										$ACC_PPN	= $rw_PPN->TAXLA_LINKOUT;
									endforeach;

									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_PPN' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$syncPRJ	= $rowISHO->syncPRJ;
										$Acc_Name	= $rowISHO->Acc_Name;
									endforeach;

									$JournalD_Debet		= $d['PPN_Amount'];
									$Base_Debet			= $d['PPN_Amount'];
									$COA_Debet			= $d['PPN_Amount'];
									$JournalD_Kredit 	= 0;
									$Base_Kredit		= 0;
									$COA_Kredit			= 0;
									$isTax 				= 1;
									$PPN_D 				= "PPN - $Other_Desc";
									$insSQL2	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
														Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
														Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
														COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
														ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
														GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
														PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, Manual_No, SPLCODE, SPLDESC) VALUES
														('$JournalH_Code', '$ACC_PPN', '$JournalType', '$proj_Code', '',
														'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
														$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
														$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
														'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_DescD', 'D', '$Journal_Type', $isTax, 
														'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $ISPERSL,
														'$PPN_Code', $PPN_Perc, $PPN_Amount, '', 0, 0, $PattNum, '$Manual_No', '$SPLCODE', '$SPLDESC')";
									$this->db->query($insSQL2);

									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
									
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$PPN_Amount, 
																Base_Debet2 = Base_Debet2+$PPN_Amount, BaseD_$accYr = BaseD_$accYr+$PPN_Amount
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_PPN'";
											$this->db->query($sqlUpdCOA);
										}
									}
								}
							// END : INSERT PPN (JIKA ADA)									--- DEBET

							// START : INSERT PPH (JIKA ADA)								--- KREDIT
								if($PPH_Amount > 0)
								{	
									$ACC_PPH		= '';
									$s_PPN 			= "SELECT TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$PPH_Code'";
									$r_PPN			= $this->db->query($s_PPN)->result();
									foreach($r_PPN as $rw_PPN):
										$ACC_PPH	= $rw_PPN->TAXLA_LINKOUT;
									endforeach;

									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_PPH' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$syncPRJ	= $rowISHO->syncPRJ;
										$Acc_Name	= $rowISHO->Acc_Name;
									endforeach;

									$JournalD_Debet		= 0;
									$Base_Debet			= 0;
									$COA_Debet			= 0;
									$JournalD_Kredit 	= $d['PPH_Amount'];
									$Base_Kredit		= $d['PPH_Amount'];
									$COA_Kredit			= $d['PPH_Amount'];
									$isTax 				= 1;
									$insSQL2	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
														Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
														Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
														COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
														ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
														GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
														PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, Manual_No) VALUES
														('$JournalH_Code', '$ACC_PPH', '$JournalType', '$proj_Code', '',
														'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
														$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
														$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
														'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_DescD', 'K', '$Journal_Type', $isTax, 
														'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $ISPERSL,
														'', 0, 0, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$Manual_No')";
									$this->db->query($insSQL2);

									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
									
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PPH_Amount, 
																Base_Kredit2 = Base_Kredit2+$PPH_Amount, BaseK_$accYr = BaseK_$accYr+$PPH_Amount
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_PPH'";
											$this->db->query($sqlUpdCOA);
										}
									}
								}
							// END : INSERT PPH (JIKA ADA)									--- KREDIT

							// START : UPDATE ITEM USED
								// 1. UPDATE JOBLIST
									$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
														ITM_LASTP	= $ITM_PRICE,
														ITM_USED 	= ITM_USED+$ITM_VOLM, 
														ITM_USED_AM = ITM_USED_AM+$Base_Debet,
														REQ_VOLM 	= REQ_VOLM+$ITM_VOLM, 
														REQ_AMOUNT 	= REQ_AMOUNT+$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
														AND ITM_CODE = '$ITM_CODE'";
									$this->db->query($sqlUpdJOBL);

								// 2. UPDATE ITEM LIST
									$sqlUpdITML	= "UPDATE tbl_item SET
														ITM_LASTP	= $ITM_PRICE,
														ITM_OUT 	= ITM_OUT+$ITM_VOLM,
														ITM_OUTP 	= $ITM_PRICE,
														UM_VOLM 	= UM_VOLM+$ITM_VOLM,
														UM_AMOUNT 	= UM_AMOUNT+$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
									$this->db->query($sqlUpdITML);			
							// END : Update ITM Used

							// START : UPDATE L/R
								if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'GE')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								
								if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
								{
									// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
									if($ITM_TYPE == 1)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 9)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 10)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								}
								elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
								{
									$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
						}
					}

					// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT
						/*
							BEB. KONSULTAN	 	1,000,000.00 	
							PPN		 			  100,000.00 
							PPH	 				  				 20,000.00 	
							HUT. SUPPLIER		 			  1,080,000.00 ---->>>> (1,000,000.00 + 100,000.00 - 20,000.00)

							$totHUTSPL 	= $Journal_Amount + $PPNH_Amount - $PPHH_Amount;
						*/

						$Acc_Name 		= "-";
						$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
						$resACCNm		= $this->db->query($sqlACCNm)->result();
						foreach($resACCNm as $rowACCNm):
							$Acc_Name	= $rowACCNm->Acc_Name;
						endforeach;

						$totHUTSPL 			= $Journal_Amount + $PPNH_Amount - $PPHH_Amount;
						$JournalD_Kredit 	= $totHUTSPL;
						$Base_Kredit		= $totHUTSPL;
						$COA_Kredit			= $totHUTSPL;
						$isTax 				= 0;
						$PattNumK 			= $PattNum+1;
						$Other_DescD		= "$SPLDESC. $Other_Desc $ManualNo";

						// START : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
							$insSQLK	= "INSERT INTO tbl_journaldetail_cprj (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
												JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
												curr_rate, isDirect, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type,
												isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, SPLCODE, SPLDESC) VALUES
												('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $JournalD_Kredit,
												0, $JournalD_Kredit, 0, $JournalD_Kredit, 0,
												1, 1, '$TAX_DATE', '$TAX_NO', '$Other_DescD', 'K', '$Journal_Type',
												$isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $ISPERSL, $PattNumK, '$SPLCODE', '$SPLDESC')";
							// 19 April 2022 $this->db->query($insSQLK);
						// END : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT

						if($AH_ISLAST == 1)
						{
							// START : INSERT INTO JOURNAL DETAIL ((HUTANG SUPPLIER)						--- KREDIT
								$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
													JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
													curr_rate, isDirect, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type,
													isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, Manual_No,
													SPLCODE, SPLDESC) VALUES
													('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $JournalD_Kredit,
													0, $JournalD_Kredit, 0, $JournalD_Kredit, 0,
													1, 1, '$TAX_DATE', '$TAX_NO', '$Other_DescD', 'K', '$Journal_Type',
													$isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $ISPERSL, $PattNumK, '$Manual_No',
													'$SPLCODE', '$SPLDESC')";
								$this->db->query($insSQLK);
							// END : KREDIT

							// START : POSTING HUTANG SUPPLIER
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
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
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$totHUTSPL, 
																Base_Kredit2 = Base_Kredit2+$totHUTSPL, BaseK_$accYr = BaseK_$accYr+$totHUTSPL
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$acc_number'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : UPDATE KAS/BANK
						}
					// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT
				// END : DETAIL JOURNAL

				// START : UPDATE JOURNAL HEADER
					$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
									GJournal_Total = $GJournal_Total
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);

					$upJH3	= "UPDATE tbl_journalheader_cprj SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
									GJournal_Total = $GJournal_Total
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
				// END : UPDATE JOURNAL HEADER
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader_cprj");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				if($AH_ISLAST == 1)
				{
					// START - HEADER
						$AH_CODE		= $JournalH_Code;
						$AH_APPLEV		= $this->input->post('APP_LEVEL');
						$AH_APPROVER	= $DefEmp_ID;
						$AH_APPROVED	= date('Y-m-d H:i:s');
						$AH_NOTES		= $this->input->post('JournalH_Desc');
						$AH_ISLAST		= $this->input->post('IS_LAST');
						
						$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
												'JournalH_Desc' 	=> $AH_NOTES,
												// 'Manual_No' 		=> "J".$Manual_No, 
												'Manual_No' 		=> $Manual_No, //KODE KAS BANK tidak menggunakan awalan J -> Req. Accounting 18-08-2022
												'JournalType' 		=> $JournalType,
												'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
												'JournalH_Date'		=> $JournalH_Date,
												'Company_ID'		=> $comp_init,
												'Reference_Type'	=> $JournalType,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $GEJ_CREATED,
												'Created'			=> $GEJ_CREATED,
												'Wh_id'				=> $PRJCODE,
												'proj_Code'			=> $PRJCODE,
												'proj_CodeHO'		=> $proj_CodeHO,
												'PRJPERIOD'			=> $PRJPERIOD,
												'GEJ_STAT'			=> $GEJ_STAT,
												'ISPERSL'			=> $ISPERSL,
												'PERSL_EMPID'		=> $PERSL_EMPID,
												'SPLCODE'			=> $SPLCODE,
												'SPLDESC'			=> $SPLDESC,
												'Journal_Amount'	=> $Journal_Amount,
												'acc_number'		=> $acc_number,
												'PPNH_Amount'		=> $PPNH_Amount,
												'PPHH_Amount'		=> $PPHH_Amount,
												'REF_NUM' 			=> $JournalH_Code,
												'REF_CODE' 			=> $Manual_No,
												'GJournal_Total'	=> $GJournal_Total); 
						$this->m_cho_payment->add($projGEJH);
					// END - HEADER

					$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);

					$upJHA	= "UPDATE tbl_journalheader_cprj SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_cprj");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			elseif($GEJ_STAT == 4)
			{
				$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJHA);

				$upJHA	= "UPDATE tbl_journalheader_cprj SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader_cprj");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
					
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($JournalH_Code);
				// END : DELETE HISTORY

				if($ISPERSL == 3)
				{
					$upJHA	= "UPDATE tbl_journalheader_cprj SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
						
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_cprj");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			elseif($GEJ_STAT == 5)
			{
				$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJHA);

				$upJHA	= "UPDATE tbl_journalheader_cprj SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader_cprj");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				if($ISPERSL == 3)
				{
					$upJHA	= "UPDATE tbl_journalheader_cprj SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);
						
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_journalheader_cprj");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				
				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $JournalH_Date,
										'DOC_NUM'	=> $JournalH_Code,
										'DOC_CATEG'	=> "CPRJ");
					$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL
			}
			else 		// IF NEW OR CONFIRMED
			{
				// UPDATE STATUS HEADER
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);

					$upJH	= "UPDATE tbl_journalheader_cprj SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
				
				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $JournalH_Date,
										'DOC_NUM'	=> $JournalH_Code,
										'DOC_CATEG'	=> "CPRJ");
					$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL

				// RESET DETAIL
					$this->m_cho_payment->deleteCPRJDetail($JournalH_Code);

				// RESET DETAIL
					$this->m_cho_payment->deleteCPRJDetail_cprj($JournalH_Code);

				// START : DETAIL JOURNAL
					$Base_DebetTOT		= 0;
					$Base_DebetTOT_Tax	= 0;
					$PattNum 			= 0;
					$TAX_DATE 			= '';
					$TAX_NO 			= '';
					foreach($_POST['data'] as $d)
					{
						$PattNum 		= $PattNum+1;
						$JournalH_Code	= $JournalH_Code;
						$Acc_Id			= $d['Acc_Id'];
						$ITM_CODE		= $d['ITM_CODE'];
						$proj_Code		= $d['proj_Code'];
						$JOBCODEID		= $d['JOBCODEID'];
						$JournalD_Pos	= $d['JournalD_Pos'];
						$isTax			= $d['isTax'];
						$ITM_GROUP		= $d['ITM_CATEG'];
						$ITM_VOLM		= $d['ITM_VOLM'];
						$ITM_UNIT		= $d['ITM_UNIT'];
						$ITM_PRICE		= $d['ITM_PRICE'];
						
						$PRJCODE		= $d['proj_Code'];
						$ACC_NUM		= $d['Acc_Id'];			// Detail Account
						$isHO			= 0;
						$syncPRJ		= '';
						$Acc_Name 		= "-";
						$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
							$Acc_Name	= $rowISHO->Acc_Name;
						endforeach;

						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
						
						$ITM_Amount	= $d['JournalD_Amount'];
						$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

						$Journal_Type		= $jrnType;
						$isTax				= 0;

						$JournalD_Debet		= $d['JournalD_Amount'];
						$Base_Debet			= $d['JournalD_Amount'];
						$COA_Debet			= $d['JournalD_Amount'];
						$JournalD_Kredit 	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
						
						$JournalD_Debet_tax	= 0;
						$Base_Debet_tax		= 0;
						$COA_Debet_tax		= 0;
						$JournalD_Kredit_tax= 0;
						$Base_Kredit_tax	= 0;
						$COA_Kredit_tax		= 0;

						
						$curr_rate			= 1;
						$isDirect			= 1;
						$TAX_DATE			= addslashes($d['TAX_DATE']);
						$TAX_NO				= addslashes($d['TAX_NO']);
						$Other_Desc			= addslashes($d['Other_Desc']);
						$Journal_DK			= $JournalD_Pos;
						$Journal_Type		= $Journal_Type;
						$isTax				= $isTax;
						$PPN_Code			= $d['PPN_Code'];
						$PPN_Perc			= $d['PPN_Perc'];
						$PPN_Amount			= $d['PPN_Amount'];
						$PPH_Code			= $d['PPH_Code'];
						$PPH_Perc			= $d['PPH_Perc'];
						$PPH_Amount			= $d['PPH_Amount'];

						/*
							PERHATIAN ...!!! (2022-02-11) BY DIAN HERMANTO
							JIKA ADA PPN DAN PPH MAKA
							1. PADA TABEL tbl_journal_detail akan dilakukan pemisahan antara DPP dengan PPN, PPH
							2. PADA TABEL tbl_journal_detail_vcash akan tetap disatukan
							3. PADA SAAT PENARIKAAN JURNAL UNTUK PEMBAYARAN WAJIB DIAMBIL DARI TABEL tbl_journal_detail BUKAN tbl_journal_detail_vcash
							4. PPN DAN PPH TERBENTUK SAAT APPROVE
						*/

						// START : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET
							$insSQL	= "INSERT INTO tbl_journaldetail_cprj (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
											Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
											Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
											COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
											ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type, isTax,
											GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
											PPN_Code, PPN_Perc, PPN_Amount, PPH_Code, PPH_Perc, PPH_Amount, PattNum, SPLCODE, SPLDESC) VALUES
											('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID',
											'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
											$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
											$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP',
											'$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$TAX_DATE', '$TAX_NO', '$Other_Desc', 'D', '$Journal_Type', $isTax, 
											'$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $ISPERSL,
											'$PPN_Code', $PPN_Perc, $PPN_Amount, '$PPH_Code', $PPH_Perc, $PPH_Amount, $PattNum, '$SPLCODE', '$SPLDESC')";
							$this->db->query($insSQL);
						// END : INSERT INTO JOURNAL DETAIL VCASH (BEBAN)					--- DEBET

						// START : PROCEDURE UPDATE JOBLISTDETAIL
							$JOBID 		= $d['JOBCODEID'];
							$ITM_CODE	= $d['ITM_CODE'];
							$DOC_VOLM	= $d['ITM_VOLM'];
							$DOC_TOTAL	= $Base_Debet;
							$compVAR 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $JournalH_Date,
												'JOBID'		=> $JOBID,
												'ITM_CODE'	=> $ITM_CODE,
												'DOC_VOLM'	=> $DOC_VOLM,
												'DOC_TOTAL'	=> $DOC_TOTAL,
												'VAR_VOL_R'	=> "VLK_VOL_R",
												'VAR_VAL_R'	=> "VLK_VAL_R");
							$this->m_updash->updJOBP($compVAR);
						// END : PROCEDURE UPDATE JOBLISTDETAIL
					}

					// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT
						$Acc_Name 		= "-";
						$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
						$resACCNm		= $this->db->query($sqlACCNm)->result();
						foreach($resACCNm as $rowACCNm):
							$Acc_Name	= $rowACCNm->Acc_Name;
						endforeach;

						$totHUTSPL 			= $Journal_Amount+$PPNH_Amount-$PPHH_Amount;
						$JournalD_Kredit 	= $totHUTSPL;
						$Base_Kredit		= $totHUTSPL;
						$COA_Kredit			= $totHUTSPL;
						$isTax 				= 0;
						$PattNumK 			= $PattNum+1;
						$Other_DescD 		= "$SPLDESC. $JOURNL_DESC $ManualNo";

						// START : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
							$insSQLK	= "INSERT INTO tbl_journaldetail_cprj (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
												JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
												curr_rate, isDirect, TAX_DATE, TAX_NO, Other_Desc, Journal_DK, Journal_Type,
												isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, SPLCODE, SPLDESC) VALUES
												('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $JournalD_Kredit,
												0, $JournalD_Kredit, 0, $JournalD_Kredit, 0,
												1, 1, '$TAX_DATE', '$TAX_NO', '$Other_DescD', 'K', '$Journal_Type',
												$isTax, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', $ISPERSL, $PattNumK, '$SPLCODE', '$SPLDESC')";
							$this->db->query($insSQLK);
						// END : INSERT INTO JOURNAL DETAIL VCASH ((HUTANG SUPPLIER)					--- KREDIT
					// START : INSERT INTO JOURNAL DETAIL VCASH (HUTANG SUPPLIER)	--- KREDIT

					if($GEJ_STAT == 2)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$VLK_VAL 	= $JournalD_Kredit;
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $JournalH_Date,
												'FVAL'		=> $VLK_VAL,
												'FNAME'		=> "VLK_VAL");										
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}
				// END : DETAIL JOURNAL

				// START : UPDATE JOURNAL HEADER
					$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
									GJournal_Total = $GJournal_Total, JournalH_Desc = '$JOURNL_DESC'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);

					$upJH3	= "UPDATE tbl_journalheader_cprj SET Journal_Amount = $Journal_Amount,	PPNH_Amount = $PPNH_Amount,	PPHH_Amount = $PPHH_Amount,
									GJournal_Total = $GJournal_Total, JournalH_Desc = '$JOURNL_DESC'
								WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
				// END : UPDATE JOURNAL HEADER
		
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader_cprj");
					$this->m_updash->updateStatus($paramStat);
				
				// START : UPDATE STAT DET
					$this->load->model('m_updash/m_updash', '', TRUE);				
					$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
					$this->m_updash->updSTATJD($paramSTAT);
				// END : UPDATE STAT DET
			}
			
			// DELETE TABLE TEMPORARY
				$s_delTMP 	= "DELETE FROM tbl_journaldetail_tmp WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE'";
				$this->db->query($s_delTMP);

			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET

			// START : UPDATE AKUN LAWAN
				$Acc_Cr 		= "";
				$sqlACC_Cr 		= "SELECT Acc_Id FROM tbl_journaldetail WHERE proj_Code = '$PRJCODE' AND Journal_DK = 'K' AND JournalH_Code = '$JournalH_Code'";
				$resACC_Cr		= $this->db->query($sqlACC_Cr)->result();
				foreach($resACC_Cr as $row_Cr):
					$Acc_Cr		= $row_Cr->Acc_Id;
				endforeach;
				$Acc_Cr 		= $acc_number;		// LANGSUNG DARI AKUN KAS/BANK YANG DIGUNAKAN

				$updAcc_Db		= "UPDATE tbl_journaldetail SET Acc_Id_Cross = '$Acc_Cr' WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'";
				$this->db->query($updAcc_Db);
			// END : UPDATE AKUN LAWAN
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN359';
				$TTR_CATEG		= 'U-P';
				
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
			
			$url			= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		// $CB_NUM1	= $_GET['id'];
		// $CB_NUM		= $this->url_encryption_helper->decode_url($CB_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			// $data['CB_NUM'] = $CB_NUM;
			$data['title'] 	= $appName;

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 				= $this->m_cho_payment->get_CHO_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;
			
			$this->load->view('v_finance/v_cproj_payment/print_kaspry', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function printdocument_VCASH()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		// $CB_NUM1	= $_GET['id'];
		// $CB_NUM		= $this->url_encryption_helper->decode_url($CB_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			// $data['CB_NUM'] = $CB_NUM;
			$data['title'] 	= $appName;

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 					= $this->m_cho_payment->get_VCASH_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 		= $getGEJ->JournalH_Code;
			$data['Manual_No'] 			= $getGEJ->Manual_No;
			$data['JournalH_Date'] 		= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 			= $getGEJ->PlanRDate;
			$data['SPLDESC'] 			= $getGEJ->SPLDESC;
			$data['JournalH_Desc']		= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']		= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 			= $getGEJ->proj_Code;
			$data['PRJCODE'] 			= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 		= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 			= $getGEJ->PRJPERIOD;
			$PRJCODE					= $getGEJ->proj_Code;
			$PRJCODE_HO					= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 			= $getGEJ->GEJ_STAT;
			$data['acc_number'] 		= $getGEJ->acc_number;
			$acc_number					= $getGEJ->acc_number;
			$data['Journal_Amount'] 	= $getGEJ->Journal_Amount;
			$data['PPNH_Amount'] 		= $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] 		= $getGEJ->PPHH_Amount;
			$data['ISPERSL'] 			= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 		= $getGEJ->PERSL_EMPID;
			$data['Reference_Number'] 	= $getGEJ->Reference_Number; // nomor kwitansi
			$data['jrnType'] 			= $getGEJ->JournalType;
			$jrnType					= $getGEJ->JournalType;
			
			$this->load->view('v_finance/v_cho_payment/print_VCASH', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function printdocument_VTLK()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		// $CB_NUM1	= $_GET['id'];
		// $CB_NUM		= $this->url_encryption_helper->decode_url($CB_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			// $data['CB_NUM'] = $CB_NUM;
			$data['title'] 	= $appName;

			$TLK_NUM	= $_GET['id'];
			$TLK_NUM	= $this->url_encryption_helper->decode_url($TLK_NUM);
			
			$getTLK 			= $this->m_cho_payment->get_VTLK_by_number($TLK_NUM)->row();
			$data['PRJCODE'] 	= $getTLK->PRJCODE;
			$PRJCODE			= $getTLK->PRJCODE;
			$data['PROP_NUM'] 	= $getTLK->PROP_NUM;
			$data['PROP_CODE'] 	= $getTLK->PROP_CODE;
			$data['PROP_VALUE'] = $getTLK->PROP_VALUE;
			$data['TLK_NUM'] 	= $getTLK->TLK_NUM;
			$data['TLK_CODE'] 	= $getTLK->TLK_CODE;
			$data['TLK_CATEG'] 	= $getTLK->TLK_CATEG;
			$data['TLK_DATE'] 	= $getTLK->TLK_DATE;
			$data['TLK_DATES'] 	= $getTLK->TLK_DATES;
			$data['TLK_DATEE'] 	= $getTLK->TLK_DATEE;
			$data['TLK_DATSEUS']= $getTLK->TLK_DATSEUS;
			$data['TLK_DATSEUE']= $getTLK->TLK_DATSEUE;
			$data['TLK_AMOUNTU']= $getTLK->TLK_AMOUNTU;
			$data['TLK_AMOUNT']	= $getTLK->TLK_AMOUNT;
			$data['TLK_DESC']	= $getTLK->TLK_DESC;
			$data['TLK_STATUS'] = $getTLK->TLK_STATUS;
			$data['TLK_REALIZ']	= $getTLK->TLK_REALIZ;
			
			$this->load->view('v_finance/v_cho_payment/print_VTLK', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function printdocument_CHO()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		// $CB_NUM1	= $_GET['id'];
		// $CB_NUM		= $this->url_encryption_helper->decode_url($CB_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			// $data['CB_NUM'] = $CB_NUM;
			$data['title'] 	= $appName;

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 				= $this->m_cho_payment->get_CHO_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;
			
			$this->load->view('v_finance/v_cho_payment/print_CHO', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function printdocument_CPRJ()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		// $CB_NUM1	= $_GET['id'];
		// $CB_NUM		= $this->url_encryption_helper->decode_url($CB_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			// $data['CB_NUM'] = $CB_NUM;
			$data['title'] 	= $appName;

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 				= $this->m_cho_payment->get_CPRJ_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['SPLDESC'] 		= $getGEJ->SPLDESC;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['PPNH_Amount'] = $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] = $getGEJ->PPHH_Amount;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['Emp_ID']			= $getGEJ->Emp_ID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;
			
			$this->load->view('v_finance/v_cho_payment/print_CPRJ', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function printdocument_JRNREV()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		// $CB_NUM1	= $_GET['id'];
		// $CB_NUM		= $this->url_encryption_helper->decode_url($CB_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			// $data['CB_NUM'] = $CB_NUM;
			$data['title'] 	= $appName;

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 				= $this->m_cho_payment->get_JRNREV_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['SPLDESC'] 		= $getGEJ->SPLDESC;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['PPNH_Amount'] = $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] = $getGEJ->PPHH_Amount;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;
			
			$this->load->view('v_finance/v_cho_payment/print_JRNREV', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function viewpaid_voucher()
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$JournalH_Code	= $_GET['id'];
		$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);

		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 	= $appName;

			$getGEJ 				= $this->m_cho_payment->get_CHO_by_number($JournalH_Code)->row();

			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['GJournal_Total'] = $getGEJ->GJournal_Total;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;

			$this->load->view('v_finance/v_cho_payment/viewHist_voucherpaid', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function viewpaid_voucher_pd()
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$JournalH_Code	= $_GET['id'];
		$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);

		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 	= $appName;

			$getGEJ 				= $this->m_cho_payment->get_CHO_by_number($JournalH_Code)->row();

			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;

			$this->load->view('v_finance/v_cho_payment/viewHist_voucherpaid_pd', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function viewpaid_voucher_vtlk()
	{
		$this->load->model('m_purchase/m_purchase_inv/m_purchase_inv', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$TLK_NUM	= $_GET['id'];
		$TLK_NUM	= $this->url_encryption_helper->decode_url($TLK_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 	= $appName;

			$getTLK 				= $this->m_cho_payment->get_TLK_by_number($TLK_NUM)->row();

			$data['TLK_NUM'] 	= $getTLK->TLK_NUM;
			$data['TLK_CODE'] 	= $getTLK->TLK_CODE;
			$data['TLK_DATE'] 	= $getTLK->TLK_DATE;
			$data['TLK_DESC']	= $getTLK->TLK_DESC;
			$data['TLK_DESC2']	= $getTLK->TLK_DESC2;
			$data['TLK_AMOUNT']	= $getTLK->TLK_AMOUNT;
			$data['TLK_REALIZ']	= $getTLK->TLK_REALIZ;
			$data['PRJCODE'] 	= $getTLK->PRJCODE;
			$data['TLK_STATUS'] = $getTLK->TLK_STATUS;

			$this->load->view('v_finance/v_voucher_tlk/viewHist_voucherpaid_tlk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function cpothb80da8() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_cho70d18/cpothb80da8_pr7l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function cpothb80da8_pr7l() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Biaya Rupa-Rupa";
			}
			else
			{
				$data["h1_title"] 	= "Others Expenses";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN353';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_finance/c_cho70d18/cpothb80da8_4ll/?id=";
			
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

	function cpothb80da8_4ll() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			// -------------------- START : SEARCHING METHOD --------------------
				// $chg_url		= 'c_finance/c_cho70d18/cp2b0d18_all'
				
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
					$end		= 100;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_finance/c_cho70d18/f4n7_5rcH07h/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_cho_payment->count_all_GEJOTH($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_cho_payment->get_all_GEJOTH($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN353';
			$data['addURL'] 	= site_url('c_finance/c_cho70d18/addcpothb80da8/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cpothb80da8_pr7l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN353';
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
			
			$this->load->view('v_finance/v_expense_oth/v_expense_oth', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : FUNCTION TO SEARCH ENGINE --------------------
		function f4n7_5rcH07h()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE";
			$url			= site_url('c_finance/c_cho70d18/cpothb80da8_4ll/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : FUNCTION TO SEARCH ENGINE --------------------
	
	function addcpothb80da8() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$task				= '';
			$acc_number			= '';
			$data['form_action']= site_url('c_finance/c_cho70d18/addcpothb80da8_process');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cpothb80da8_4ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$MenuCode 			= 'MN353';
			$data["MenuCode"] 	= 'MN353';
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN353';
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
			
			$this->load->view('v_finance/v_expense_oth/v_expense_oth_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addcpothb80da8_process() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			
			// START - PEMBENTUKAN GENERATE CODE				
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN353';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('JournalH_Desc');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> 'O-EXP',	// Cash Project
									'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'O-EXP',
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Created'			=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'GEJ_STAT'			=> $GEJ_STAT,
									'acc_number'		=> $acc_number);
			$this->m_cho_payment->add($projGEJH);
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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
			
			$Base_DebetTOT		= 0;
			$Base_DebetTOT_Tax	= 0;
			foreach($_POST['data'] as $d)
			{
				$JournalH_Code	= $JournalH_Code;
				$Acc_Id			= $d['Acc_Id'];
				$ITM_CODE		= $d['ITM_CODE'];
				$proj_Code		= $d['proj_Code'];
				$JOBCODEID		= $d['JOBCODEID'];
				$JournalD_Pos	= $d['JournalD_Pos'];
				$isTax			= $d['isTax'];
				$ITM_GROUP		= $d['ITM_CATEG'];
				$ITM_VOLM		= $d['ITM_VOLM'];
				$ITM_UNIT		= $d['ITM_UNIT'];
				$ITM_PRICE		= $d['ITM_PRICE'];
				
				$PRJCODE		= $d['proj_Code'];
				$ACC_NUM		= $d['Acc_Id'];			// Detail Account
				$isHO			= 0;
				$syncPRJ		= '';
				$Acc_Name 		= "-";
				$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
					$Acc_Name	= $rowISHO->Acc_Name;
				endforeach;

				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
				
				$ITM_Amount	= $d['JournalD_Amount'];
				$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;
				if($isTax == 0)
				{
					$Journal_Type		= '';
					$isTax				= 0;
					if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
					{
						$JournalD_Debet	= $d['JournalD_Amount'];
						$Base_Debet		= $d['JournalD_Amount'];
						$COA_Debet		= $d['JournalD_Amount'];
						$JournalD_Kredit= 0;
						$Base_Kredit	= 0;
						$COA_Kredit		= 0;
						
						$Base_DebetTOT		= $Base_DebetTOT + $Base_Debet;
					}
					
					$JournalD_Debet_tax		= 0;
					$Base_Debet_tax			= 0;
					$COA_Debet_tax			= 0;
					$JournalD_Kredit_tax	= 0;
					$Base_Kredit_tax		= 0;
					$COA_Kredit_tax			= 0;
				}
				else
				{
					$Journal_Type		= 'TAX';
					$isTax				= 1;
					if($JournalD_Pos = 'D') // Always Debit and Credit is for Cash/Bank Acount
					{
						$JournalD_Debet_tax		= $d['JournalD_Amount'];
						$Base_Debet_tax			= $d['JournalD_Amount'];
						$COA_Debet_tax			= $d['JournalD_Amount'];
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
						
						$Base_DebetTOT_Tax	= $Base_DebetTOT_Tax + $Base_Debet_tax;
					}
					
					$JournalD_Debet		= 0;
					$Base_Debet			= 0;
					$COA_Debet			= 0;
					$JournalD_Kredit	= 0;
					$Base_Kredit		= 0;
					$COA_Kredit			= 0;
				}
				
				$curr_rate		= 1;
				$isDirect		= 1;
				$Ref_Number		= addslashes($d['Ref_Number']);
				$Other_Desc		= addslashes($d['Other_Desc']);
				$Journal_DK		= $JournalD_Pos;
				$Journal_Type	= $Journal_Type;
				$isTax			= $isTax;
				
				// Insert into tbl_journal_detail (D) for All Expenses
				$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, JOBCODEID, Currency_id, JournalD_Debet, 
								JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
								Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
								curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_UNIT, ITM_PRICE, Ref_Number, Other_Desc,
								Journal_DK, Journal_Type, isTax, GEJ_STAT, Acc_Name) VALUES
								('$JournalH_Code', '$Acc_Id', '$proj_Code', '$JOBCODEID', 'IDR', $JournalD_Debet, 
								$JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, 
								$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
								'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$Ref_Number', '$Other_Desc', 'D', 
								'$Journal_Type', $isTax, '$GEJ_STAT', '$Acc_Name')";
				$this->db->query($insSQL);
			}
			
			$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
			
			$Acc_Name 		= "-";
			$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
			$resACCNm		= $this->db->query($sqlACCNm)->result();
			foreach($resACCNm as $rowACCNm):
				$Acc_Name	= $rowACCNm->Acc_Name;
			endforeach;
			
			// Insert into tbl_journal_detail (K) for Cash
			$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
								JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
								curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
								isTax, GEJ_STAT, Acc_Name) VALUES
								('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
								$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$Ref_Number', 
								'$Other_Desc', 'K', '$Journal_Type', $isTax, '$GEJ_STAT', '$Acc_Name')";
			$this->db->query($insSQLK);
			
			$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJH3);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN359';
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
			
			$url			= site_url('c_finance/c_cho70d18/cpothb80da8_4ll/?id='.$this->url_encryption_helper->encode_url($proj_Code));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function upothb80da8() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 							= $this->m_cho_payment->get_CPRJ_by_number($JournalH_Code)->row();
			$data['default']['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['default']['Manual_No'] 		= $getGEJ->Manual_No;
			$data['default']['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['default']['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['default']['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['default']['proj_Code'] 		= $getGEJ->proj_Code;
			$data['default']['PRJCODE'] 		= $getGEJ->proj_Code;
			$PRJCODE							= $getGEJ->proj_Code;
			$data['default']['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['default']['acc_number'] 		= $getGEJ->acc_number;
			$acc_number							= $getGEJ->acc_number;
			$data['default']['Journal_Amount'] 	= $getGEJ->Journal_Amount;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_cho70d18/upothb80da8_process');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cpothb80da8_4ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$MenuCode 			= 'MN353';
			$data["MenuCode"] 	= 'MN353';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN353';
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
			
			$this->load->view('v_finance/v_expense_oth/v_expense_oth_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function upothb80da8_process() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$accYr			= date('Y', strtotime($JournalH_Date));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			$PRJCODE 		= $this->input->post('proj_Code');
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('JournalH_Desc2');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJHA);
			
			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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
			
			if($GEJ_STAT == 3)
			{
				$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH);
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if($AH_ISLAST == 1)
				{
					$upJH2	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH2);
				
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			
			if($GEJ_STAT == 9) // Void
			{
				$JournalH_Codex	= $JournalH_Code;
				$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH);
				
				$upJD	= "UPDATE tbl_journaldetail SET isVoid = 1 WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJD);
				
				$JournalH_Code2	= "V$JournalH_Code";
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code2,
										'JournalH_Desc' 	=> $AH_NOTES,
										'Manual_No' 		=> $Manual_No,
										'JournalType' 		=> 'VO-EXP',	// Cash Proj)ect
										'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
										'JournalH_Date'		=> $JournalH_Date,
										'Company_ID'		=> $comp_init,
										'Reference_Type'	=> 'VO-EXP',
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $GEJ_CREATED,
										'Created'			=> $GEJ_CREATED,
										'Wh_id'				=> $PRJCODE,
										'proj_Code'			=> $PRJCODE,
										'GEJ_STAT'			=> $GEJ_STAT,
										'acc_number'		=> $acc_number);
				$this->m_cho_payment->add($projGEJH);
			
				$Base_KreditTOT		= 0;
				$Base_KreditTOT_Tax	= 0;
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $JournalH_Code2;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$proj_Code		= $d['proj_Code'];
					$JOBCODEID		= $d['JOBCODEID'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$ITM_GROUP		= $d['ITM_CATEG'];
					$ITM_VOLM		= $d['ITM_VOLM'];
					$ITM_UNIT		= $d['ITM_UNIT'];
					
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					
					$isHO			= 0;
					$syncPRJ		= '';
					$Acc_Name 		= "-";
					$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
						$Acc_Name	= $rowISHO->Acc_Name;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					if($isTax == 0)
					{
						$Journal_Type		= '';
						$isTax				= 0;
						
						if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet	= 0;
							$Base_Debet		= 0;
							$COA_Debet		= 0;
							$JournalD_Kredit= $d['JournalD_Amount'];
							$Base_Kredit	= $d['JournalD_Amount'];
							$COA_Kredit		= $d['JournalD_Amount'];
							
							// START : Update to COA - Debit
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
															Base_Kredit2 = Base_Kredit2+$Base_Kredit, BaseK_$accYr = BaseK_$accYr+$Base_Kredit
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
							// START : UPDATE L/R
								if($ITM_GROUP == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'GE')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
								
							$Base_KreditTOT		= $Base_KreditTOT + $Base_Kredit;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
					}
					else
					{
						$Journal_Type		= 'TAX';
						$isTax				= 1;
						if($JournalD_Pos = 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet_tax		= 0;
							$Base_Debet_tax			= 0;
							$COA_Debet_tax			= 0;
							$JournalD_Kredit_tax	= $d['JournalD_Amount'];
							$Base_Kredit_tax		= $d['JournalD_Amount'];
							$COA_Kredit_tax			= $d['JournalD_Amount'];
							
							// START : Update to COA - Debit
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOAD	= "UPDATE tbl_chartaccount 
														SET Base_Kredit_tax = Base_Kredit_tax+$Base_Kredit_tax, 
															Base_Kredit_tax2 = Base_Kredit_tax2+$Base_Kredit_tax
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
														
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
							
							// START : UPDATE L/R
								if($ITM_GROUP == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'SC')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit_tax
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'GE')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
								
							$Base_KreditTOT_Tax	= $Base_KreditTOT_Tax + $Base_Kredit_tax;
						}
						
						$JournalD_Debet		= 0;
						$Base_Debet			= 0;
						$COA_Debet			= 0;
						$JournalD_Kredit	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
					}
					
					$curr_rate				= 1;
					$isDirect				= 1;
					$Ref_Number				= addslashes($d['Ref_Number']);
					$Other_Desc				= addslashes($d['Other_Desc']);
					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					// Insert into tbl_journal_detail (D) for All Expenses
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax, JOBCODEID, Acc_Name) VALUES
									('$JournalH_Code', '$Acc_Id', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
									$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit,
									$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'D', '$Journal_Type', $isTax, '$JOBCODEID', '$Acc_Name')";
					$this->db->query($insSQL);
					
					// UPDATE INTO USE JOBLISTDETAIL
					/*$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_VOLM, ITM_USED_AM = ITM_USED_AM-$JournalD_Kredit
								WHERE JOBCODEID = '$JOBCODEID'";*/
					$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED - $ITM_VOLM, ITM_USED_AM = ITM_USED_AM-$JournalD_Kredit
								WHERE JOBCODEID = '$JOBCODEID'";
					//$this->db->query($upJL1);
				}
				$BaseKreditTOT	= $Base_KreditTOT + $Base_KreditTOT_Tax;
			
				$Acc_Name 		= "-";
				$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resACCNm		= $this->db->query($sqlACCNm)->result();
				foreach($resACCNm as $rowACCNm):
					$Acc_Name	= $rowACCNm->Acc_Name;
				endforeach;
				
				// Insert into tbl_journal_detail (D) for Cash/Bank
				$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet,
									JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax, Acc_Name) 
									VALUES
									('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_KreditTOT, $Base_KreditTOT_Tax,
									$Base_KreditTOT, $Base_KreditTOT_Tax, $Base_KreditTOT, $Base_KreditTOT_Tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'D', '$Journal_Type', $isTax, '$Acc_Name')";
				$this->db->query($insSQLK);
				
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount, GJournal_Total = $BaseKreditTOT
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
				
				$ACC_NUM		= $acc_number;				
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
						$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$BaseKreditTOT, 
											Base_Debet2 = Base_Debet2+$BaseKreditTOT, BaseD_$accYr = BaseD_$accYr+$BaseKreditTOT
										WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$acc_number'";
						$this->db->query($sqlUpdCOA);
					}
				}
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Codex,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($GEJ_STAT == 4)
			{
				$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT', JournalH_Desc2 = '$AH_NOTES'
									WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJHA);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
					
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($JournalH_Code);
				// END : DELETE HISTORY
			}
			else
			{
				$projGEJH 	= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> 'O-EXP',	// Cash Project
									'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'O-EXP',
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'acc_number'		=> $acc_number);									
				$this->m_cho_payment->updateCHO($JournalH_Code, $projGEJH);
				
				$this->m_cho_payment->deleteCPRJDetail($JournalH_Code);
				
				$Base_DebetTOT		= 0;
				$Base_DebetTOT_Tax	= 0;
				$Acc_Id2 			= "";
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $JournalH_Code;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$proj_Code		= $d['proj_Code'];
					$JOBCODEID		= $d['JOBCODEID'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$ITM_GROUP		= $d['ITM_CATEG'];
					$ITM_VOLM		= $d['ITM_VOLM'];
					$ITM_UNIT		= $d['ITM_UNIT'];
					
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					$isHO			= 0;
					$syncPRJ		= '';
					$Acc_Name 		= "-";
					$sqlISHO 		= "SELECT isHO, syncPRJ, Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
						$Acc_Name	= $rowISHO->Acc_Name;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					$ITM_Amount	= $d['JournalD_Amount'];
					$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;
					if($isTax == 0)
					{
						$Journal_Type		= '';
						$isTax				= 0;
						
						if($JournalD_Pos == 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet	= $d['JournalD_Amount'];
							$Base_Debet		= $d['JournalD_Amount'];
							$COA_Debet		= $d['JournalD_Amount'];
							$JournalD_Kredit= 0;
							$Base_Kredit	= 0;
							$COA_Kredit		= 0;
							if($GEJ_STAT == 3 && $AH_ISLAST == 1)
							{
								// START : Update to COA - Debit
									/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
															Base_Debet2 = Base_Debet2+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOA);*/
									
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
																Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
								
								// START : UPDATE L/R
									if($ITM_GROUP == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'SC')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'GE')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
								
								// START : Update ITM Used
									// 1. UPDATE JOBLIST
										$JOBCODEID	= $d['JOBCODEID'];
										$ITM_CODE	= $d['ITM_CODE'];
										$ITM_VOLM	= $d['ITM_VOLM'];
										
										$ITM_USED	= 0;
										$ITM_USEDAM	= 0;
										$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
															WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
																AND ITM_CODE = '$ITM_CODE'";
										$resUSED1	= $this->db->query($sqlUSED1)->result();
										foreach($resUSED1 as $rowUSED1):
											$ITM_USED	= $rowUSED1->ITM_USED;
											$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
										endforeach;
										
										$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
															ITM_LASTP	= $ITM_PRICE,
															ITM_USED 	= ITM_USED+$ITM_VOLM, 
															ITM_USED_AM = ITM_USED_AM+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
															AND ITM_CODE = '$ITM_CODE'";
										$this->db->query($sqlUpdJOBL);
										
									// 2. UPDATE ITEM LIST
										$ITM_OUT	= 0;
										$UM_VOLM	= 0;
										$UM_AMOUNT	= 0;
										$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
															WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
																AND ITM_CODE = '$ITM_CODE'";
										$resUSED1	= $this->db->query($sqlUSED1)->result();
										foreach($resUSED1 as $rowUSED1):
											$ITM_USED	= $rowUSED1->ITM_USED;
											$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
										endforeach;
										$sqlUpdITML	= "UPDATE tbl_item SET
															ITM_LASTP	= $ITM_PRICE,
															ITM_OUT 	= ITM_OUT+$ITM_VOLM,
															ITM_OUTP 	= $ITM_PRICE,
															UM_VOLM 	= UM_VOLM+$ITM_VOLM,
															UM_AMOUNT 	= UM_AMOUNT+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
										$this->db->query($sqlUpdITML);			
								// END : Update ITM Used
							}
							$Base_DebetTOT		= $Base_DebetTOT + $Base_Debet;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
					}
					else
					{
						$Journal_Type		= 'TAX';
						$isTax				= 1;
						if($JournalD_Pos = 'D') // Always Debit and Credit is for Cash/Bank Acount
						{
							$JournalD_Debet_tax		= $d['JournalD_Amount'];
							$Base_Debet_tax			= $d['JournalD_Amount'];
							$COA_Debet_tax			= $d['JournalD_Amount'];
							$JournalD_Kredit_tax	= 0;
							$Base_Kredit_tax		= 0;
							$COA_Kredit_tax			= 0;
							if($GEJ_STAT == 3 && $AH_ISLAST == 1)
							{
								// START : Update to COA - Debit
									/*$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET 
															Base_Debet_tax = Base_Debet_tax+$Base_Debet_tax, 
															Base_Debet_tax2 = Base_Debet_tax2+$Base_Debet_tax
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOAD);*/
									
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET 
															Base_Debet_tax = Base_Debet_tax+$Base_Debet_tax, 
															Base_Debet_tax2 = Base_Debet_tax2+$Base_Debet_tax
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
								
								// START : UPDATE L/R
									if($ITM_GROUP == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'SC')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet_tax
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_GROUP == 'GE')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet_tax 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
							}
							$Base_DebetTOT_Tax	= $Base_DebetTOT_Tax + $Base_Debet_tax;
						}
						
						$JournalD_Debet		= 0;
						$Base_Debet			= 0;
						$COA_Debet			= 0;
						$JournalD_Kredit	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
					}
					
					$curr_rate				= 1;
					$isDirect				= 1;
					$Ref_Number				= addslashes($d['Ref_Number']);
					$Other_Desc				= addslashes($d['Other_Desc']);
					if($Acc_Id == $Acc_Id)
						$oth_reason 		= "$oth_reason. $Other_Desc";
					else
						$oth_reason 		= $Other_Desc;

					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					// Insert into tbl_journal_detail (D) for All Expenses
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE, ITM_UNIT, Ref_Number, Other_Desc,
									Journal_DK, Journal_Type, isTax, JOBCODEID, Acc_Name, oth_reason)
									VALUES
									('$JournalH_Code', '$Acc_Id', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
									$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit,
									$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_PRICE', '$ITM_UNIT', '$Ref_Number', '$Other_Desc', 'D', 
									'$Journal_Type', $isTax, '$JOBCODEID', '$Acc_Name', '$oth_reason')";
					$this->db->query($insSQL);
					
					// UPDATE INTO USE JOBLISTDETAIL
					/*$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = 1, ITM_USED_AM = ITM_USED_AM+$JournalD_Debet
								WHERE JOBCODEID = '$JOBCODEID'";*/
					$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM+$JournalD_Debet
								WHERE JOBCODEID = '$JOBCODEID'";
					//$this->db->query($upJL1);

					$Acc_Id2 	= $Acc_Id;
				}
				
				$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
			
				$Acc_Name 		= "-";
				$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resACCNm		= $this->db->query($sqlACCNm)->result();
				foreach($resACCNm as $rowACCNm):
					$Acc_Name	= $rowACCNm->Acc_Name;
				endforeach;
				
				// Insert into tbl_journal_detail (K) for Cash
				$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
									JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax, Acc_Name) VALUES
									('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
									$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$ITM_CODE', 
									'$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'K', '$Journal_Type', $isTax, '$Acc_Name')";
				$this->db->query($insSQLK);
				
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount, GJournal_Total = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH3);
					
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					//$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if($GEJ_STAT == 3 && $AH_ISLAST == 1)
				{
					/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$BaseDebetTOT, 
											Base_Kredit2 = Base_Kredit2+$BaseDebetTOT
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number'";
					$this->db->query($sqlUpdCOA);*/
					
					$ACC_NUM		= $acc_number;				
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
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$BaseDebetTOT, 
												Base_Kredit2 = Base_Kredit2+$BaseDebetTOT, BaseK_$accYr = BaseK_$accYr+$BaseDebetTOT
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$acc_number'";
							$this->db->query($sqlUpdCOA);
						}
					}
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				elseif($GEJ_STAT == 2 || $GEJ_STAT == 5)
				{
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			$upJHA			= "UPDATE tbl_journaldetail SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			//$this->db->query($upJHA);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN359';
				$TTR_CATEG		= 'U-P';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_finance/c_cho70d18/cpothb80da8_4ll/?id='.$this->url_encryption_helper->encode_url($proj_Code));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function puSA0b28t18yXP() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$THEROW		= $_GET['theRow'];
			
			// GET PRJCODE_HO
				$getGPRJP 			= $this->m_updash->get_PRJHO($PRJCODE)->row();
				$PRJCODE_HO			= $getGPRJP->PRJCODE_HO;
				$data['PRJCODE_HO'] = $PRJCODE_HO;
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['SOURCE'] 		= "I";
			$data['THEROW'] 		= $THEROW;
			$acc_number				= '';
			
			$data['countAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 		= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$data['countAllItem']	= $this->m_cho_payment->count_all_AccountyXP($PRJCODE);
			$data['vwAllItem'] 		= $this->m_cho_payment->view_all_AccountyXP($PRJCODE)->result();
					
			$this->load->view('v_finance/v_cho_payment/v_cho_payment_selacc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function puSA0b28t18yXI() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$THEROW		= $_GET['theRow'];
			
			// GET PRJCODE_HO
				$getGPRJP 			= $this->m_updash->get_PRJHO($PRJCODE)->row();
				$PRJCODE_HO			= $getGPRJP->PRJCODE_HO;
				$data['PRJCODE_HO'] = $PRJCODE_HO;
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['PRJCODE'] 		= $PRJCODE;
			$data['SOURCE'] 		= "I";
			$data['THEROW'] 		= $THEROW;
			$acc_number				= '';
			
			$data['countAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 		= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$data['countAllItem']	= $this->m_cho_payment->count_all_AccountyXI($PRJCODE);
			$data['vwAllItem'] 		= $this->m_cho_payment->view_all_AccountyXI($PRJCODE)->result();
					
			$this->load->view('v_finance/v_cho_payment/v_cho_payment_selacc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataITM() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$THEROW			= $_GET['THEROW'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');
		date_default_timezone_set("Asia/Jakarta");

		$MonthAgo 		= date("Y-m-t", strtotime ('-1 month'));
		$C_YEAR 		= date("Y");
		$C_MONTH 		= date("m");

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
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
			$num_rows 		= $this->m_cho_payment->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_cho_payment->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$ITM_USED1		= $dataI['ITM_USED'];
				$ITM_USED_AM1	= $dataI['ITM_USED_AM'];
				$ITM_STOCK		= $dataI['ITM_STOCK'];
				$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				$serialNumber		= '';
				$itemConvertion		= 1;
				$TOT_VOLMBG 		= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNTBG		= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);
															
				$JOBDESCPAR		= '';
				/*$sqlJOBPAR		= "SELECT JOBDESC from tbl_joblist_detail
										WHERE JOBCODEID IN (SELECT X.JOBPARENT from tbl_joblist_detail X 
											WHERE X.JOBCODEID = '$JOBCODEID' AND X.PRJCODE = '$PRJCODE') LIMIT 1";*/
				$sqlJOBPAR		= "SELECT JOBDESC from tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJOBPAR		= $this->db->query($sqlJOBPAR)->result();
				foreach($resJOBPAR as $rowJOBPAR) :
					$JOBDESCPAR	= $rowJOBPAR->JOBDESC;
				endforeach;

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$ACC_ID 		= '';
					$ITM_GROUP 		= '';
					$ITM_CATEG 		= '';
					$sqlITMNM		= "SELECT ITM_NAME, ITM_TYPE, ITM_UNIT, ACC_ID_UM AS ACC_ID, ITM_GROUP
										FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_NAME	= $rowITMNM->ITM_NAME;
						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
						$ITM_UNIT	= $rowITMNM->ITM_UNIT;
						$ACC_ID		= $rowITMNM->ACC_ID;
						$ITM_GROUP	= $rowITMNM->ITM_GROUP;
					endforeach;

				$JOBUNIT 		= strtoupper($ITM_UNIT);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';

				$JOBLEV			= $dataI['IS_LEVEL'];

				// RESERVE
					$ITM_USEDR			= 0;
					$ITM_USEDR_AM		= 0;
					$sqlJOBDR			= "SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
											FROM tbl_journaldetail_pd
											WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE'
												AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT IN (1,2,7)
											UNION
											SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
											FROM tbl_journaldetail_vcash
											WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE'
												AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT IN (1,2,7)";
					$resJOBDR			= $this->db->query($sqlJOBDR)->result();
					foreach($resJOBDR as $rowJOBDR) :
						$ITM_USEDR		= $ITM_USEDR+$rowJOBDR->TOTVOL;
						$ITM_USEDR_AM	= $ITM_USEDR_AM+$rowJOBDR->TOTAMN;
					endforeach;

					$RES_VW 		= "";
					if($ITM_USEDR_AM > 0)
					{
						$RES_VW		= 	"<div style='white-space:nowrap;'>
											<span class='text-red' style='white-space:nowrap;'><i class='glyphicon glyphicon-chevron-down'></i>
									  		".number_format($ITM_USEDR_AM, 2)."</span>
									  	</div>";
					}

				$s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				$r_isLS 	= $this->db->count_all($s_isLS);

				//if($JOBUNIT == 'LS' || $JOBUNIT == 'LOT')
				if($r_isLS > 0)
				{
					$ITM_LASTP 		= 0;
					$ITM_USED 		= 1;
					$ITM_USED_AM 	= $ITM_USED_AM1 + $ITM_USEDR_AM;
					$BUDG_REMVOLM	= 1;
					$BUDG_REMAMNT	= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
				}
				elseif($JOBUNIT == 'BLN')
				{
					// Khusus untuk satuan Bulan, maka volume akan tetap 1 jika masih dlm bulan yg sama
						$TV_BM 		= 0;				// Total Bulan Sebelumnya
						$s_BVOL 	= "SELECT SUM(ITM_VOLM) AS TV_BM FROM tbl_journaldetail
										WHERE proj_Code = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND JournalH_Date <= '$MonthAgo'";
						$r_BVOL 	= $this->db->query($s_BVOL)->result();
						foreach($r_BVOL as $rw_BVOL):
							$TV_BM 	= $rw_BVOL->TV_BM;
						endforeach;
						if($TV_BM > 0)
							$TV_BM 	= 1;
						else
							$TV_BM 	= 0;

						$TV_CM 		= 0;				// Total Bulan Saat Ini
						$s_CVOL 	= "SELECT SUM(ITM_VOLM) AS TV_CM FROM tbl_journaldetail
										WHERE proj_Code = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND YEAR(JournalH_Date) = '$C_YEAR'
											 AND MONTH(JournalH_Date) = '$C_MONTH'";
						$r_CVOL 	= $this->db->query($s_CVOL)->result();
						foreach($r_CVOL as $rw_CVOL):
							$TV_CM 	= $rw_CVOL->TV_CM;
						endforeach;
						if($TV_CM > 0)
							$TV_CM 	= 1;
						else
							$TV_CM 	= 0;

					$ITM_LASTP 		= 0;
					$ITM_USED 		= 1;
					$ITM_USED_AM 	= $ITM_USED_AM1 + $ITM_USEDR_AM;
					$BUDG_REMVOLM	= 1;
					$BUDG_REMAMNT	= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
				}
				else
				{
					$ITM_USED 		= $ITM_USED1 + $ITM_USEDR;
					$ITM_USED_AM 	= $ITM_USED_AM1 + $ITM_USEDR_AM;
					$BUDG_REMVOLM	= $ITM_VOLMBG + $ADD_VOLM - $ITM_USED;
					$BUDG_REMAMNT	= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
				}

				$disabledB		= 0;
				$VOLM_DESC		= "success";
				$AMN_DESC		= "success";
               	if($BUDG_REMVOLM <= 0 && $BUDG_REMAMNT <= 0)
				{
                    $disabledB	= 1;
					$VOLM_DESC	= "danger";
					$AMN_DESC	= "danger";
				} 
				elseif($BUDG_REMAMNT <= 0)
				{
                    $disabledB	= 1;
					$AMN_DESC	= "danger";
				}
               	elseif($BUDG_REMVOLM <= 0)
				{
					$VOLM_DESC	= "danger";
				}                                                            
				
				$JONDESCRIP	= "$JOBDESCPAR : $ITM_NAME";

				/*$REMREQ_QTY	= $TOT_VOLMBG - $ITM_USED;
				$REMREQ_AMN		= $TOT_AMOUNTBG - $ITM_USED_AM;*/
				$REMREQ_QTY		= $BUDG_REMVOLM;
				$REMREQ_AMN		= $BUDG_REMAMNT;
			
				if($ITM_TYPE == 'SUBS')
				{
					$disabledB	= 0;															
				}

				$STATCOL		= 'success';
				$CELL_COL		= "style='white-space:nowrap'";

				$JOBUNITV 		= $JOBUNIT;
				$TOT_VOLMBGV	= number_format($TOT_VOLMBG, 2);
				$TOT_AMNBGV		= number_format($TOT_AMOUNTBG, 2);
				$JOBVOLMV		= number_format($JOBVOLM, 2);
				$TOT_REQV		= number_format(0, 2);
				//$PO_VOLMV		= number_format($PO_VOLM, 2);
				//$PO_AMOUNTV		= number_format($PO_AMOUNT, 2);
				$UM_VOLMV		= number_format($ITM_USED, 2);
				$UM_AMOUNTV		= number_format($ITM_USED_AM, 2);
				if($disabledB == 0)
				{
					$REM_VOL = number_format($REMREQ_QTY, 2);
					$REM_AMN = number_format($REMREQ_AMN, 2);
				}
				else
				{
					$REM_VOL = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_QTY, 2)."</span>";
					$REM_AMN = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_AMN, 2)."</span>";
				}

				// OTHER SETT
					$ISVERIFY 	= 1;
					if($disabledB == 0)
					{
						$chkBox		= "<input type='radio' name='chk0' id='chk0".$noU."' value='".$ACC_ID."|".$JONDESCRIP."|".$ITM_CODE."|".$ITM_GROUP."|".$JOBCODEID."|".$BUDG_REMVOLM."|".$BUDG_REMAMNT."|".$ITM_UNIT."|".$ITM_LASTP."|".$THEROW."|".$ISVERIFY."' onClick='pickThis0(".$noU.");'/>";
						/*$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' onClick='pickThis(this);'/>";*/
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$ACC_ID."|".$JONDESCRIP."|".$ITM_CODE."|".$ITM_GROUP."|".$JOBCODEID."|".$BUDG_REMVOLM."|".$BUDG_REMAMNT."|".$ITM_UNIT."|".$ITM_LASTP."|".$noU."' style='display: none' />";
						/*$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' style='display: none' />";*/
					}

					$JOBDESCH		= $JOBDESCPAR;
					/*$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESCH	= $rowJOBDESC->JOBDESC;
					endforeach;*/

					$JOBDESCHV 		= wordwrap($JOBDESCH, 30, "<br>", TRUE);

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC";
					$JobView		= "$JOBCODEID - $JOBDESC";
					$JobViewV 		= wordwrap($JobView, 45, "<br>", TRUE);

					$ADDVOL_VW 		= "";
					$ADDAMN_VW		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
						$ADDAMN_VW 	= 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_JOBCOST, 2)."</p>
									  	</div>";
					}

				//$UM_AMOUNTV		= number_format($ITM_USED_AM, 2);
				$UM_AMOUNT 			= $ITM_USED_AM - $ITM_USEDR_AM;
				$UM_AMOUNTV			= number_format($UM_AMOUNT, 2);
				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div style='text-align:center;'>".$ACC_ID."</span></div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobViewV."</span></p>
										  	</div>
										  	<div style='margin-left: 15px; font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JOBDESCHV."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right; white-space:nowrap'><span ".$CELL_COL.">".$TOT_VOLMBGV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right; white-space:nowrap'><span ".$CELL_COL.">".$TOT_AMNBGV.$ADDAMN_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$UM_VOLMV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$UM_AMOUNTV.$RES_VW."</span></div>",
											"<div style='text-align:right;'>".$REM_VOL."</div>",
											"<div style='text-align:right;'>".$REM_AMN."</div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function getACCID()
	{
		$collD 		= $_POST['collDt'];
		$collDt 	= explode("~", $collD);
		$jrnCode 	= $collDt[0];
		$pattCode 	= $collDt[1];
		$PRJCODE 	= $collDt[2];
		$task 		= $collDt[3];
		$jrnType 	= $collDt[4];
		$ACC_ID 	= $collDt[5];

		// SESUAI DENGAN KEBIJAKAN HASIL DISKUSI DENGAN PAK EDY WONG TGL 21 MARET 2022, KODE BANK TIDAK PERLU DIMASUKAN
		$ACC_BAL	= 0;
		$ACC_CLASS 	= 3;			// DEFAULTT KAS
		$sqlBAL 	= "SELECT LEFT(Account_NameId,3) AS BANK_PATT, Base_OpeningBalance, Base_Debet, Base_Kredit, Account_Class
						FROM tbl_chartaccount
						WHERE Account_Number = '$ACC_ID' AND PRJCODE = '$PRJCODE'";
		$resBAL 	= $this->db->query($sqlBAL)->result();
		foreach($resBAL as $rowBAL):
			$BANK_PATT 	= $rowBAL->BANK_PATT;
			$Base_OB 	= $rowBAL->Base_OpeningBalance;
			$Base_D 	= $rowBAL->Base_Debet;
			$Base_K 	= $rowBAL->Base_Kredit;
			$ACC_CLASS 	= $rowBAL->Account_Class;
			$ACC_BAL 	= $Base_OB + $Base_D - $Base_K;
		endforeach;

		if($ACC_CLASS == 3)
			$PATTCLS 	= "K";
		else
			$PATTCLS 	= "B";

		if($jrnType == 'CPRJ' && $task == 'add')
		{
			$pattY 	= date('Y');
			$yearC	= (int)$pattY;

			$sql 	= "tbl_journalheader_cprj WHERE YEAR(JournalH_Date) = $yearC AND proj_Code = '$PRJCODE' AND JournalType = '$jrnType'";
			$result = $this->db->count_all($sql);
			$myMax 	= $result+1;

			$lastNum 	= $myMax;
			$lastNum1 	= $myMax;
			$len 		= strlen($lastNum);

			$pattLgth = 6;
			
			if($pattLgth==2)
			{
				if($len==1) $nol="0";
			}
			elseif($pattLgth==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($pattLgth==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($pattLgth==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($pattLgth==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($pattLgth==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			else
			{
				$nol 	= "0";
			}
			$lastNum 	= $nol.$lastNum;
			//$Man_No	= "$BANK_PATT$PATTCLS$pattCode.$PRJCODE.$lastNum";
			$Man_No		= "$pattCode.$PRJCODE.$lastNum";

			echo "$ACC_BAL~$Man_No";
		}
		else if($jrnType == 'CPRJ')
		{
			$pattY 	= date('Y');
			$yearC	= (int)$pattY;

			$sql 	= "tbl_journalheader_cprj WHERE YEAR(JournalH_Date) = $yearC AND proj_Code = '$PRJCODE' AND JournalType = '$jrnType'";
			$result = $this->db->count_all($sql);
			$myMax 	= $result+1;

			$lastNum 	= $myMax;
			$lastNum1 	= $myMax;
			$len 		= strlen($lastNum);

			$pattLgth = 6;
			
			if($pattLgth==2)
			{
				if($len==1) $nol="0";
			}
			elseif($pattLgth==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($pattLgth==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($pattLgth==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($pattLgth==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($pattLgth==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			else
			{
				$nol 	= "0";
			}
			$lastNum 	= $nol.$lastNum;
			//$Man_No		= "$BANK_PATT$PATTCLS$pattCode-$PRJCODE-$lastNum";
			$Man_No		= "$pattCode.$PRJCODE.$lastNum";

			echo "$ACC_BAL~$Man_No";
		}
		else
		{
			echo "$ACC_BAL~$jrnCode";
		}
	}

	function getACCID_VLK()
	{
		$collD 		= $_POST['collDt'];
		$collDt 	= explode("~", $collD);
		$jrnCode 	= $collDt[0];
		$pattCode 	= $collDt[1];
		$PRJCODE 	= $collDt[2];
		$task 		= $collDt[3];
		$jrnType 	= $collDt[4];
		$ACC_ID 	= $collDt[5];
		$jrnDate1 	= $collDt[6];
		$jrnDateY	= date('Y',strtotime(str_replace('/', '-', $jrnDate1)));
		$jrnDateM	= date('m',strtotime(str_replace('/', '-', $jrnDate1)));

		// SESUAI DENGAN KEBIJAKAN HASIL DISKUSI DENGAN PAK EDY WONG TGL 21 MARET 2022, KODE BANK TIDAK PERLU DIMASUKAN
		$ACC_BAL	= 0;
		$ACC_CLASS 	= 3;			// DEFAULTT KAS
		$BANK_PATT 	= "XXX";
		$sqlBAL 	= "SELECT LEFT(Account_NameId,3) AS BANK_PATT, Base_OpeningBalance, Base_Debet, Base_Kredit, Account_Class
						FROM tbl_chartaccount
						WHERE Account_Number = '$ACC_ID' AND PRJCODE = '$PRJCODE'";
		$resBAL 	= $this->db->query($sqlBAL)->result();
		foreach($resBAL as $rowBAL):
			$BANK_PATT 	= $rowBAL->BANK_PATT;
			$Base_OB 	= $rowBAL->Base_OpeningBalance;
			$Base_D 	= $rowBAL->Base_Debet;
			$Base_K 	= $rowBAL->Base_Kredit;
			$ACC_CLASS 	= $rowBAL->Account_Class;
			$ACC_BAL 	= $Base_OB + $Base_D - $Base_K;
		endforeach;

		$PATTCODE 	= "XXX";
		$PATTLGTH 	= 6;
		$s_Patt		= "SELECT Pattern_Code, Pattern_Length FROM tbl_docpattern WHERE menu_code = 'MN147'";
		$r_Patt 	= $this->db->query($s_Patt)->result();
		foreach($r_Patt as $row) :
			$PATTCODE 	= $row->Pattern_Code;
			$PATTLGTH	= $row->Pattern_Length;
		endforeach;

		if($ACC_CLASS == 3)
			$PATTCLS 	= "K";
		else
			$PATTCLS 	= "B";

		if($task == 'add')
		{
			$PRJLK_NUM 	= "";
			$s_PRJ 		= "SELECT PRJLK_NUM FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$r_PRJ 		= $this->db->query($s_PRJ)->result();
			foreach($r_PRJ as $rw_PRJ):
				$PRJLK_NUM 	= $rw_PRJ->PRJLK_NUM;
			endforeach;

			//$patty 	= date('y');
			$pattY 	= date('Y');
			$pattM 	= date('m');
			$yearC	= (int)$pattY;
			$patty 	= date('y',strtotime(str_replace('/', '-', $jrnDate1)));
			$pattM 	= date('m',strtotime(str_replace('/', '-', $jrnDate1)));

			//$sql 	= "tbl_journalheader_cprj WHERE YEAR(JournalH_Date) = $jrnDateY AND MONTH(JournalH_Date) = $jrnDateM AND proj_Code = '$PRJCODE' AND JournalType = '$jrnType'";
			$MAXNUM = 0;
			$sMAX 	= "SELECT IFNULL(MAX(PAYORD), 0) AS MAXNUM FROM tbl_doclist WHERE YEAR(PAYDATE) = $jrnDateY AND MONTH(PAYDATE) = '$jrnDateM'
						AND PRJCODE = '$PRJCODE' AND PATTCODE = 'VLK'";
			$rMAX 	= $this->db->query($sMAX)->result();
			foreach($rMAX as $rw_MAX):
				$MAXNUM = $rw_MAX->MAXNUM;
			endforeach;
			$myMax 	= $MAXNUM+1;

			$lastNum 	= $myMax;
			$lastNum1 	= $myMax;
			$len 		= strlen($lastNum);

			$pattLgth = $PATTLGTH;
			
			if($pattLgth==2)
			{
				if($len==1) $nol="0";
			}
			elseif($pattLgth==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($pattLgth==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($pattLgth==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($pattLgth==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($pattLgth==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			else
			{
				$nol 	= "0";
			}
			$lastNum 	= $nol.$lastNum;
			//$Man_No	= "$BANK_PATT$PATTCLS$pattCode.$PRJCODE.$lastNum";
			//$Man_No	= "$PATTCLS$PRJLK_NUM.$PRJCODE.$patty$pattM.$lastNum";
			$Man_No		= "$BANK_PATT.$PRJCODE.$patty$pattM.$lastNum";

			echo "$ACC_BAL~$Man_No";
		}
		else
		{
			$strTot 	= strlen($jrnCode);
			$str2 		= substr($jrnCode,3,$strTot);
			/*$PRJLK_NUM 	= "";
			$s_PRJ 		= "SELECT PRJLK_NUM FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$r_PRJ 		= $this->db->query($s_PRJ)->result();
			foreach($r_PRJ as $rw_PRJ):
				$PRJLK_NUM 	= $rw_PRJ->PRJLK_NUM;
			endforeach;

			//$patty 	= date('y');
			$pattY 	= date('Y');
			$pattM 	= date('m');
			$yearC	= (int)$pattY;
			$patty 	= date('y',strtotime(str_replace('/', '-', $jrnDate1)));
			$pattM 	= date('m',strtotime(str_replace('/', '-', $jrnDate1)));

			//$sql 	= "tbl_journalheader_cprj WHERE YEAR(JournalH_Date) = $jrnDateY AND MONTH(JournalH_Date) = $jrnDateM AND proj_Code = '$PRJCODE' AND JournalType = '$jrnType'";
			$MAXNUM = 0;
			$sMAX 	= "SELECT IFNULL(MAX(PAYORD), 0) AS MAXNUM FROM tbl_doclist WHERE YEAR(PAYDATE) = $jrnDateY AND MONTH(PAYDATE) = '$jrnDateM'
						AND PRJCODE = '$PRJCODE' AND PATTCODE = 'VLK'";
			$rMAX 	= $this->db->query($sMAX)->result();
			foreach($rMAX as $rw_MAX):
				$MAXNUM = $rw_MAX->MAXNUM;
			endforeach;
			$myMax 	= $MAXNUM+1;

			$lastNum 	= $myMax;
			$lastNum1 	= $myMax;
			$len 		= strlen($lastNum);

			$pattLgth = $PATTLGTH;
			
			if($pattLgth==2)
			{
				if($len==1) $nol="0";
			}
			elseif($pattLgth==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($pattLgth==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($pattLgth==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($pattLgth==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($pattLgth==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			else
			{
				$nol 	= "0";
			}
			$lastNum 	= $nol.$lastNum;
			$Man_No		= "$BANK_PATT.$PRJCODE.$patty$pattM.$lastNum";

			echo "$ACC_BAL~$Man_No~$PATTLGTH";*/
			$Man_No 	= $BANK_PATT.$str2;
			echo "$ACC_BAL~$Man_No";
		}
	}

	function getACCID_VLK2()
	{
		$collD 		= $_POST['collDt'];
		$collDt 	= explode("~", $collD);
		$jrnCode 	= $collDt[0];
		$pattCode 	= $collDt[1];
		$PRJCODE 	= $collDt[2];
		$task 		= $collDt[3];
		$jrnType 	= $collDt[4];
		$ACC_ID 	= $collDt[5];
		$jrnDate1 	= $collDt[6];
		$jrnNumb 	= $collDt[7];
		$jrnDateY	= date('Y',strtotime(str_replace('/', '-', $jrnDate1)));
		$jrnDateM	= date('m',strtotime(str_replace('/', '-', $jrnDate1)));

		// SESUAI DENGAN KEBIJAKAN HASIL DISKUSI DENGAN PAK EDY WONG TGL 21 MARET 2022, KODE BANK TIDAK PERLU DIMASUKAN
		$ACC_BAL	= 0;
		$ACC_CLASS 	= 3;			// DEFAULTT KAS
		$BANK_PATT 	= "XXX";
		$sqlBAL 	= "SELECT LEFT(Account_NameId,3) AS BANK_PATT, Base_OpeningBalance, Base_Debet, Base_Kredit, Account_Class
						FROM tbl_chartaccount
						WHERE Account_Number = '$ACC_ID' AND PRJCODE = '$PRJCODE'";
		$resBAL 	= $this->db->query($sqlBAL)->result();
		foreach($resBAL as $rowBAL):
			$BANK_PATT 	= $rowBAL->BANK_PATT;
			$Base_OB 	= $rowBAL->Base_OpeningBalance;
			$Base_D 	= $rowBAL->Base_Debet;
			$Base_K 	= $rowBAL->Base_Kredit;
			$ACC_CLASS 	= $rowBAL->Account_Class;
			$ACC_BAL 	= $Base_OB + $Base_D - $Base_K;
		endforeach;

		if($ACC_CLASS == 3)
			$PATTCLS 	= "K";
		else
			$PATTCLS 	= "B";

		// DAPATKAN TGL. SEBELUM DIGANTI
			$JRNDATE 	= $jrnDate1;
			$s_DT 		= "SELECT JournalH_Date FROM tbl_journalheader_cprj WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$jrnNumb'";
			$r_DT 		= $this->db->query($s_DT)->result();
			foreach($r_DT as $rw_DT):
				$JRNDATE 		= $rw_DT->JournalH_Date;
				$jrnDateMOLD	= date('m', strtotime($JRNDATE));
			endforeach;

		if($jrnDateMOLD == $jrnDateM)
		{
			echo "$ACC_BAL~$jrnCode";
		}
		else
		{
			$PRJLK_NUM 	= "";
			$s_PRJ 		= "SELECT PRJLK_NUM FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$r_PRJ 		= $this->db->query($s_PRJ)->result();
			foreach($r_PRJ as $rw_PRJ):
				$PRJLK_NUM 	= $rw_PRJ->PRJLK_NUM;
			endforeach;

			$//patty 	= date('y');
			$pattY 	= date('Y');
			$pattM 	= date('m');
			$yearC	= (int)$pattY;
			$patty 	= date('y',strtotime(str_replace('/', '-', $jrnDate1)));
			$pattM 	= date('m',strtotime(str_replace('/', '-', $jrnDate1)));

			//$sql 	= "tbl_journalheader_cprj WHERE YEAR(JournalH_Date) = $jrnDateY AND MONTH(JournalH_Date) = $jrnDateM AND proj_Code = '$PRJCODE' AND JournalType = '$jrnType'";
			$sql 	= "tbl_doclist WHERE YEAR(DOCDATE) = $jrnDateY AND MONTH(DOCDATE) = '$jrnDateM' AND PRJCODE = '$PRJCODE'";
			$sMAX 	= "SELECT IFNULL(MAX(RUNUM), 0) AS MAXNUM FROM tbl_doclist WHERE YEAR(DOCDATE) = $jrnDateY AND MONTH(DOCDATE) = '$jrnDateM'
						AND PRJCODE = '$PRJCODE'";
			$rMAX 	= $this->db->query($sMAX)->result();
			foreach($rMAX as $rw_MAX):
				$MAXNUM 	= $rw_MAX->MAXNUM;
			endforeach;
			$myMax 	= $MAXNUM+1;

			$lastNum 	= $myMax;
			$lastNum1 	= $myMax;
			$len 		= strlen($lastNum);

			$pattLgth = 4;
			
			if($pattLgth==2)
			{
				if($len==1) $nol="0";
			}
			elseif($pattLgth==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($pattLgth==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($pattLgth==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($pattLgth==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($pattLgth==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			else
			{
				$nol 	= "0";
			}
			$lastNum 	= $nol.$lastNum;
			//$Man_No	= "$BANK_PATT$PATTCLS$pattCode.$PRJCODE.$lastNum";
			//$Man_No	= "$PATTCLS$PRJLK_NUM.$PRJCODE.$patty$pattM.$lastNum";
			$Man_No		= "$BANK_PATT.$PRJCODE.$patty$pattM.$lastNum";

			echo "$ACC_BAL~$Man_No";
		}
	}

	function getACCIDSPL()
	{
		$PRJCODE 	= $_GET['id'];
		$splID 		= $_POST['splID'];

		// 1. CEK TERLEBIH DAHULU SETTINGAN KAUN UTANG PER SUPPLIER
			$ACCID_AP	= "";
			$s_01 		= "SELECT SPL_INVACC FROM tbl_supplier WHERE SPLCODE = '$splID'";
			$r_01 		= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$ACCID_AP 	= $rw_01->SPL_INVACC;
			endforeach;

		// 2. JIKA BELUM DISETTING PER SUPPLIER
			if($ACCID_AP == "")
			{
				$s_01 		= "SELECT VC_LA_PAYINV FROM tbl_vendcat WHERE VendCat_Code = (SELECT SPLCAT FROM tbl_supplier WHERE SPLCODE = '$splID')";
				$r_01 		= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01):
					$ACCID_AP 	= $rw_01->VC_LA_PAYINV;
				endforeach;
			}

		$alert1 	= "";
		if($ACCID_AP == '')
		{
			$alert1 	= "Anda belum mengatur akun hutang untuk supplier ini. Silahkan atur di daftar supplier atau di kategori supplier!";
		}

		if($ACCID_AP == '')
			$isSett = 0;
		else
			$isSett = 1;

		echo "$isSett~$ACCID_AP~$alert1";
	}

	function getACCIDEMP()
	{
		$PRJCODE 	= $_GET['id'];
		$EmpID 		= $_POST['EmpID'];

		$ACCID_AR	= "";
		$s_01 		= "SELECT ACC_ID_AR FROM tbl_employee WHERE Emp_ID = '$EmpID'";
		$r_01 		= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$ACCID_AR 	= $rw_01->ACC_ID_AR;
		endforeach;

		if($ACCID_AR == '')
		{
			$s_02 	= "SELECT ACC_ID_PERSL FROM tglobalsetting";
			$r_02 	= $this->db->query($s_02)->result();
			foreach($r_02 as $rw_02):
				$ACCID_AR 	= $rw_02->ACC_ID_PERSL;
			endforeach;
		}
		if($ACCID_AR == '')
			$isSett = 0;
		else
			$isSett = 1;

		echo "$isSett~$ACCID_AR";
	}

	function addItmTmp()
	{
		$strItem 	= $_POST['collDt'];
		$arrItem 	= explode("|", $strItem);

		$Acc_Id 		= $arrItem[0];
		$JournalD_Desc 	= $arrItem[1];
		$ITM_CODE 		= $arrItem[2];
		$ITM_GROUP		= $arrItem[3];
		$JOBCODEID		= $arrItem[4];
		$Rem_Budget		= $arrItem[5];
		$Rem_BudAmn		= $arrItem[6];
		$ITM_UNIT		= $arrItem[7];
		$ITM_PRICE		= $arrItem[8];
		$theRow 		= $arrItem[9];
		$JRN_NUM 		= $arrItem[10];
		$PRJCODE 		= $arrItem[11];
		$PattNum 		= $theRow;			// untuk mengidentifikasi baris

		// START : DEBET
			$insSQL	= "INSERT INTO tbl_journaldetail_tmp (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID,
							Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, 
							Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, 
							COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG,
							ITM_VOLM, ITM_UNIT, ITM_PRICE, TAX_NO, Other_Desc, Journal_DK, isTax,
							GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUES
							('$JRN_NUM', '$Acc_Id', 'VCASH', '$PRJCODE', '$JOBCODEID',
							'IDR', 0, 0, 0, 0,
							0, 0, 0, 0, 0, 0,
							0, 0, 1, 1, '$ITM_CODE', '$ITM_GROUP',
							0, '$ITM_UNIT', $ITM_PRICE, '', '', 'D', 0, 
							1, '', '', '', '', 2, $PattNum)";
			$this->db->query($insSQL);
		// END : DEBET
		echo "berhasil";
	}

	function ClrItmTmp()
	{
		$JRN_NUM 	= $_POST['JRN_NUM'];

		$clrSQL		= "DELETE FROM tbl_journaldetail_tmp WHERE JournalH_Code = '$JRN_NUM'";
		$this->db->query($clrSQL);

		echo "berhasil = $clrSQL";
	}

	function chgVOLUME()
	{
		$strItem 	= $_POST['collDt'];
		$arrItem 	= explode("|", $strItem);

		$PRJCODE 	= $arrItem[0];
		$JRN_NUM 	= $arrItem[1];
		$JOBCODEID 	= $arrItem[2];
		$ITMV 		= $arrItem[3];
		$ITMP 		= $arrItem[4];
		$theRow 	= $arrItem[5];
		$Base_Debet = $ITMV * $ITMP;

		$updSQL		= "UPDATE tbl_journaldetail_tmp SET ITM_VOLM = $ITMV, ITM_PRICE = $ITMP, Base_Debet = $Base_Debet
						WHERE JournalH_Code = '$JRN_NUM' AND PattNum = $theRow AND proj_Code = '$PRJCODE'";
		$this->db->query($updSQL);

		// DAPATKAN BUDGET
			$TOT_BVOL 	= 0;
			$TOT_BVAL 	= 0;
			$TOT_UVOL 	= 0;
			$TOT_UVAL 	= 0;
			$s_00		= "SELECT (ITM_VOLM + ADD_VOLM) AS BUDG_VOLM, ITM_PRICE, (ITM_BUDG + ADD_JOBCOST) AS BUDG_AMN, REQ_VOLM, REQ_AMOUNT,
								ITM_USED, ITM_USED_AM
							FROM tbl_joblist_detail
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$r_00		= $this->db->query($s_00)->result();
			foreach($r_00 as $rw_00) :
				$TOT_BVOL	= $rw_00->BUDG_VOLM;
				$ITM_PRICE	= $rw_00->ITM_PRICE;
				$TOT_BVAL	= $rw_00->BUDG_AMN;
				$REQ_VOLM	= $rw_00->REQ_VOLM;
				$REQ_AMOUNT	= $rw_00->REQ_AMOUNT;
				$TOT_UVOL	= $rw_00->ITM_USED;
				$TOT_UVAL 	= $rw_00->ITM_USED_AM;
			endforeach;

		// DAPATKAN TOTAL DIMINTA
			$s_02	= "SELECT SUM(ITM_VOLM) AS TOT_REQ, SUM(Base_Debet) AS TOT_REQAMN FROM tbl_journaldetail_tmp
						WHERE JournalH_Code = '$JRN_NUM' AND JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE' AND PattNum != $theRow";
			$r_02	= $this->db->query($s_02)->result();
			foreach($r_02 as $rw_02) :
				$TOT_RVOL	= $rw_02->TOT_REQ;
				$TOT_RVAL	= $rw_02->TOT_REQAMN;
			endforeach;
			if($TOT_RVOL == '')
				$TOT_RVOL 		= 0;
			if($TOT_RVAL == '')
				$TOT_RVAL 	= 0;

		// RESERVE
			$ITM_USEDR			= 0;
			$ITM_USEDR_AM		= 0;
			$sqlJOBDR			= "SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
									FROM tbl_journaldetail_pd
									WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE' AND GEJ_STAT IN (1,2,7)
									UNION
									SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
									FROM tbl_journaldetail_vcash
									WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE' AND GEJ_STAT IN (1,2,7)";
			$resJOBDR			= $this->db->query($sqlJOBDR)->result();
			foreach($resJOBDR as $rowJOBDR) :
				$ITM_USEDR		= $ITM_USEDR+$rowJOBDR->TOTVOL;
				$ITM_USEDR_AM	= $ITM_USEDR_AM+$rowJOBDR->TOTAMN;
			endforeach;

			$RES_VW 		= "";
			if($ITM_USEDR_AM > 0)
			{
				$RES_VW		= 	"<div style='white-space:nowrap;'>
									<span class='text-red' style='white-space:nowrap;'><i class='glyphicon glyphicon-chevron-down'></i>
							  		".number_format($ITM_USEDR_AM, 2)."</span>
							  	</div>";
			}

			$GTOT_REQV 		= $TOT_UVOL + $TOT_RVOL + $ITM_USEDR;
			$GTOT_REQAMN 	= $TOT_UVAL + $TOT_RVAL + $ITM_USEDR_AM;

			$REM_VOLM 		= $TOT_BVOL - $GTOT_REQV;
			$REM_AMN 		= $TOT_BVAL - $GTOT_REQAMN;

		echo "$REM_VOLM~$REM_AMN";
	}

	function delROW()
	{
		$strItem 	= $_POST['collID'];
		$arrItem 	= explode("|", $strItem);

		$JRN_ID 	= $arrItem[1];
		$JRN_NUM 	= $arrItem[2];
		$PRJCODE 	= $arrItem[3];

		$delSQL		= "DELETE FROM tbl_journaldetail WHERE JournalD_Id = $JRN_ID AND JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
		$this->db->query($delSQL);

		$delSQL		= "DELETE FROM tbl_journaldetail_tmp WHERE JournalD_Id = $JRN_ID AND JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
		$this->db->query($delSQL);

		echo "Komponen sudah kami hapus.";
	}

	function vtlk()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_cho70d18/prj_l15t4ll_vtlk/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj_l15t4ll_vtlk() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{			
			$LangID 	= $this->session->userdata['LangID'];
			
			$mnCode				= 'MN010';
			$data["MenuCode"] 	= 'MN010';
			$data["MenuApp"] 	= 'MN010';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_finance/c_cho70d18/vtlk_all/?id=";
			
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

	function vtlk_all() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;

		$PRJCODE			= $_GET['id'];
		$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
		
		$EmpID 			= $this->session->userdata('Emp_ID');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			// -------------------- START : SEARCHING METHOD --------------------
				// $chg_url		= 'c_finance/c_cho70d18/cp2b0d18_all'
				
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
					$end		= 100;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_finance/c_cho70d18/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_cho_payment->count_all_GEJ($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_cho_payment->get_all_GEJ($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['addURL'] 	= site_url('c_finance/c_cho70d18/addvtlk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;

			$mnCode				= 'MN010';
			$data["MenuCode"] 	= 'MN010';
			$data["MenuApp"] 	= 'MN010';
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
				$MenuCode 		= 'MN010';
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

			$data['backURL'] 	= site_url('c_finance/c_cho70d18/vtlk_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$this->load->view('v_finance/v_voucher_tlk/v_voucher_tlk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function get_AllData_VTLK() // GOOD
	{
		$PRJCODE		= $_GET['id'];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("TLK_NUM",
									"TLK_CODE",
									"TLK_DATE",
									"PRJCODE",
									"TLK_DESC",
									"TLK_AMOUNT",
									"TLK_STATUS",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cho_payment->get_AllData_VTLKC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllData_VTLKL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$TLK_NUM 			= $dataI['TLK_NUM'];
				$TLK_CODE 			= $dataI['TLK_CODE'];
				$TLK_DATE 			= date('d M Y', strtotime($dataI['TLK_DATE']));
				$PRJCODE 			= $dataI['PRJCODE'];
				$TLK_DESC 			= $dataI['TLK_DESC'];
				$TLK_DESC2 			= $dataI['TLK_DESC2'];
				$TLK_AMOUNT 		= number_format($dataI['TLK_AMOUNT'], 2);
				$realizD_Amn 		= $dataI['TLK_REALIZ'];
				$TLK_USTATUS 		= $dataI['TLK_USTATUS'];
				$TLK_STATUS 		= $dataI['TLK_STATUS'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);
				$TLK_CREATED 		= $dataI['TLK_CREATED'];
				$TLK_CREATER 		= $dataI['TLK_CREATER'];

				if($realizD_Amn == 0)
				{
					$realizD_Amn = number_format($realizD_Amn, 2);
				}
				else
				{
					$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid_tlk(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
				}

				$realizD 			= 	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$RealizationValue."</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$realizD_Amn."
										  	</div>";

				

				$secPrint		= site_url('c_finance/c_cho70d18/printdocument_VTLK/?id='.$this->url_encryption_helper->encode_url($TLK_NUM));
				$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher_vtlk/?id='.$this->url_encryption_helper->encode_url($TLK_NUM));

				$secUpd				= site_url('c_finance/c_cho70d18/upVTLK/?id='.$this->url_encryption_helper->encode_url($TLK_NUM));
				// $secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				// $secVoid 			= base_url().'index.php/__l1y/trashCHO/?id=';
				// $delID 				= "$secDelIcut~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				// $voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				if($TLK_STATUS == 1 || $TLK_STATUS == 4) 
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				elseif($TLK_STATUS == 3)
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
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
				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$TLK_CODE</div>",
										  	$TLK_DATE,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$TLK_DESC,
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$TLK_AMOUNT."</strong></div>
										  	$realizD",
										  	"<div style='text-align:right; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function addvtlk() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		$PRJCODE			= $_GET['id'];
		$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			
		$mnCode				= 'MN010';
		$data["MenuCode"] 	= 'MN010';
		$data["MenuApp"] 	= 'MN010';
		$MenuCode 			= 'MN010';
		$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
		$getMN				= $this->m_updash->get_menunm($mnCode)->row();
		if($this->data['LangID'] == 'IND')
			$data["mnName"] = $getMN->menu_name_IND;
		else
			$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];

		if ($this->session->userdata('login') == TRUE)
		{
			// GET PRJCODE_HO
				$getGPRJP 			= $this->m_updash->get_PRJHO($PRJCODE)->row();
				$PRJCODE_HO			= $getGPRJP->PRJCODE_HO;
				$PRJPERIOD			= $getGPRJP->PRJPERIOD;
				$data['PRJCODE_HO'] = $PRJCODE_HO;
				$data['PRJPERIOD'] 	= $PRJPERIOD;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_finance/c_cho70d18/add_process_vtlk');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/vtlk_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= $MenuCode;
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

			$this->load->view('v_finance/v_voucher_tlk/v_voucher_tlk_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataPROP() // GOOD
	{
		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
		
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
			
			$columns_valid 	= array("PROP_DATE", 
									"PROP_CODE", 
									"PROP_DATE",
									"PROP_NOTE",
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
			$num_rows 		= $this->m_cho_payment->get_AllDataPROP($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_cho_payment->get_AllDataPROPL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
								

				$PROP_NUM 		= $dataI['PROP_NUM'];
				$PROP_CODE 		= $dataI['PROP_CODE'];
				$PROP_DATE 		= $dataI['PROP_DATE'];
				$PROP_DATE		= date('d M Y', strtotime($PROP_DATE));
				$PROP_NOTE		= $dataI['PROP_NOTE'];
				$PROP_VALUE		= $dataI['PROP_VALUE'];
				$PROP_TSF		= $dataI['PROP_TRANSFERED'];
				$REQ_NAME		= $dataI['complName'];

				$PROP_NOTE 		= wordwrap($PROP_NOTE, 60, "<br>", TRUE);

				$PROP_AMN 		= 	"<div style='white-space:nowrap'>
										<strong><i class='fa fa-money margin-r-5'></i>Proposal</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".number_format($PROP_VALUE, 2)."
								  	</div>
								  	<div style='white-space:nowrap'>
										<strong><i class='fa fa-money margin-r-5'></i>Ditransfer</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".number_format($PROP_TSF, 2)."
								  	</div>";

				$chkBox			= "<input type='radio' name='chk1' value='".$PROP_NUM."|".$PROP_CODE."|".$PROP_DATE."|".$PROP_VALUE."' onClick='pickThis1(this);' />";

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<spin style='white-space:nowrap'>".$PROP_CODE."</spin>",
											"$PROP_DATE",
											"<spin style='white-space:nowrap'>$PROP_NOTE</spin>
											$PROP_AMN");

				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function add_process_vtlk() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$MenuCode			= 'MN010';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$PRJCODE 		= $this->input->post('PRJCODE');
			$PROP_NUM 		= $this->input->post('PROP_NUM');
			$PROP_CODE 		= $this->input->post('PROP_CODE');
			$PROP_VALUE 	= $this->input->post('PROP_VALUE');
			$TLK_NUM 		= $this->input->post('TLK_NUM');
			$TLK_CODE		= $this->input->post('TLK_CODE');
			$TLK_CATEG		= $this->input->post('TLK_CATEG');
			$TLK_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATE'))));
			$TLK_DATES		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATES'))));
			$TLK_DATEE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATEE'))));
			$TLK_DATSEUS	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATSEUS'))));
			$TLK_DATSEUE	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATSEUE'))));
			$TLK_AMOUNTU	= $this->input->post('TLK_AMOUNTU');
			$TLK_AMOUNT		= $this->input->post('TLK_AMOUNT');
			$TLK_DESC		= $this->input->post('TLK_DESC');
			$TLK_STATUS		= $this->input->post('TLK_STATUS');

			$TLK_CREATED	= date('Y-m-d H:i:s');
			
			$itmTLK 		= array('PRJCODE' 		=> $PRJCODE,
									'PROP_NUM' 		=> $PROP_NUM,
									'PROP_CODE' 	=> $PROP_CODE,
									'PROP_VALUE' 	=> $PROP_VALUE,
									'TLK_NUM' 		=> $TLK_NUM,
									'TLK_CODE' 		=> $TLK_CODE,
									'TLK_CATEG' 	=> $TLK_CATEG,
									'TLK_DATE' 		=> $TLK_DATE,
									'TLK_DATES' 	=> $TLK_DATES,
									'TLK_DATEE' 	=> $TLK_DATEE,
									'TLK_DATSEUS' 	=> $TLK_DATSEUS,
									'TLK_DATSEUE' 	=> $TLK_DATSEUE,
									'TLK_AMOUNTU'	=> $TLK_AMOUNTU,
									'TLK_AMOUNT'	=> $TLK_AMOUNT,
									'TLK_DESC' 		=> $TLK_DESC,
									'TLK_STATUS'	=> $TLK_STATUS,
									'TLK_CREATER'	=> $DefEmp_ID,
									'TLK_CREATED'	=> $TLK_CREATED); 
			$this->m_cho_payment->addTLK($itmTLK);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "TLK_NUM",
										'DOC_CODE' 		=> $TLK_NUM,
										'DOC_STAT' 		=> $TLK_STATUS,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_tsflk");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $TLK_NUM;
				$MenuCode 		= 'MN010';
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
			
			$url			= site_url('c_finance/c_cho70d18/vtlk_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function upVTLK() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$TLK_NUM	= $_GET['id'];
			$TLK_NUM	= $this->url_encryption_helper->decode_url($TLK_NUM);
			
			$getTLK 						= $this->m_cho_payment->get_TLK_by_number($TLK_NUM)->row();
			$data['PRJCODE'] 	= $getTLK->PRJCODE;
			$PRJCODE			= $getTLK->PRJCODE;
			$data['PROP_NUM'] 	= $getTLK->PROP_NUM;
			$data['PROP_CODE'] 	= $getTLK->PROP_CODE;
			$data['PROP_VALUE'] = $getTLK->PROP_VALUE;
			$data['TLK_NUM'] 	= $getTLK->TLK_NUM;
			$data['TLK_CODE'] 	= $getTLK->TLK_CODE;
			$data['TLK_DATE'] 	= $getTLK->TLK_DATE;
			$data['TLK_DATES'] 	= $getTLK->TLK_DATES;
			$data['TLK_DATEE'] 	= $getTLK->TLK_DATEE;
			$data['TLK_DATSEUS']= $getTLK->TLK_DATSEUS;
			$data['TLK_DATSEUE']= $getTLK->TLK_DATSEUE;
			$data['TLK_AMOUNTU']= $getTLK->TLK_AMOUNTU;
			$data['TLK_AMOUNT']	= $getTLK->TLK_AMOUNT;
			$data['TLK_DESC']	= $getTLK->TLK_DESC;
			$data['TLK_STATUS'] = $getTLK->TLK_STATUS;
			$data['TLK_REALIZ']	= $getTLK->TLK_REALIZ;

			$mnCode				= 'MN010';
			$MenuCode			= 'MN010';
			$data["MenuCode"] 	= 'MN010';
			$data["MenuApp"] 	= 'MN010';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['PRJCODE'] 	= $PRJCODE;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_cho70d18/update_process_tlk');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/vtlk_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				//$MenuCode 	= 'MN359';
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

			$this->load->view('v_finance/v_voucher_tlk/v_voucher_tlk_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_tlk() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$MenuCode			= 'MN010';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$PRJCODE 		= $this->input->post('PRJCODE');
			$PROP_NUM 		= $this->input->post('PROP_NUM');
			$PROP_CODE 		= $this->input->post('PROP_CODE');
			$PROP_VALUE 	= $this->input->post('PROP_VALUE');
			$TLK_NUM 		= $this->input->post('TLK_NUM');
			$TLK_CODE		= $this->input->post('TLK_CODE');
			$TLK_CATEG		= $this->input->post('TLK_CATEG');
			$TLK_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATE'))));
			$TLK_DATES		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATES'))));
			$TLK_DATEE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATEE'))));
			$TLK_DATSEUS	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATSEUS'))));
			$TLK_DATSEUE	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('TLK_DATSEUE'))));
			$TLK_AMOUNTU	= $this->input->post('TLK_AMOUNTU');
			$TLK_AMOUNT		= $this->input->post('TLK_AMOUNT');
			$TLK_DESC		= $this->input->post('TLK_DESC');
			$TLK_STATUS		= $this->input->post('TLK_STATUS');
			
			$itmTLK 		= array('PRJCODE' 		=> $PRJCODE,
									'PROP_NUM' 		=> $PROP_NUM,
									'PROP_CODE' 	=> $PROP_CODE,
									'PROP_VALUE' 	=> $PROP_VALUE,
									'TLK_CATEG' 	=> $TLK_CATEG,
									'TLK_DATE' 		=> $TLK_DATE,
									'TLK_DATES' 	=> $TLK_DATES,
									'TLK_DATEE' 	=> $TLK_DATEE,
									'TLK_DATSEUS' 	=> $TLK_DATSEUS,
									'TLK_DATSEUE' 	=> $TLK_DATSEUE,
									'TLK_AMOUNTU'	=> $TLK_AMOUNTU,
									'TLK_AMOUNT'	=> $TLK_AMOUNT,
									'TLK_DESC' 		=> $TLK_DESC,
									'TLK_STATUS'	=> $TLK_STATUS,
									'TLK_CREATER'	=> $DefEmp_ID); 
			$this->m_cho_payment->updateTLK($itmTLK, $TLK_NUM);

			if($TLK_STATUS == 3)
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "TLK_NUM",
											'DOC_CODE' 		=> $TLK_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_tsflk");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'PRJCODE'		=> $PRJCODE,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				if($AH_ISLAST == 1)
				{
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "TLK_NUM",
												'DOC_CODE' 		=> $TLK_NUM,
												'DOC_STAT' 		=> $TLK_STATUS,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_tsflk");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			elseif($TLK_STATUS == 4)
			{		
				$upTLK	= "UPDATE tbl_tsflk SET TLK_STATUS = '$TLK_STATUS', TLK_DESC2 = '$AH_NOTES'
							WHERE TLK_NUM = '$TLK_NUM'";
				$this->db->query($upTLK);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "TLK_NUM",
											'DOC_CODE' 		=> $TLK_NUM,
											'DOC_STAT' 		=> $TLK_STATUS,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_tsflk");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($TLK_STATUS == 5)
			{
				$upTLK	= "UPDATE tbl_tsflk SET TLK_STATUS = '$TLK_STATUS', TLK_DESC2 = '$AH_NOTES'
							WHERE TLK_NUM = '$TLK_NUM'";
				$this->db->query($upTLK);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "TLK_NUM",
											'DOC_CODE' 		=> $TLK_NUM,
											'DOC_STAT' 		=> $TLK_STATUS,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_tsflk");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else 		// IF NEW CONFIRMED
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "TLK_NUM",
											'DOC_CODE' 		=> $TLK_NUM,
											'DOC_STAT' 		=> $TLK_STATUS,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_tsflk");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $TLK_NUM;
				$MenuCode 		= 'MN010';
				$TTR_CATEG		= 'U-P';
				
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
			
			$url			= site_url('c_finance/c_cho70d18/vtlk_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function chkCode()
	{
		$collD 		= $_POST['collDt'];
		$collDt 	= explode("~", $collD);
		$jrnCode 	= $collDt[0];
		$pattCode 	= $collDt[1];
		$PRJCODE 	= $collDt[2];
		$jrnType 	= "VCASH";

		$s_jrnC 	= "tbl_journalheader_vcash WHERE Manual_No = '$jrnCode'";
		$r_jrnC 	= $this->db->count_all($s_jrnC);
		if($r_jrnC > 0)
		{
			$pattY 	= date('Y');
			$yearC	= (int)$pattY;

			$sql 	= "tbl_journalheader WHERE YEAR(JournalH_Date) = $yearC AND proj_Code = '$PRJCODE' AND JournalType = '$jrnType'";
			$result = $this->db->count_all($sql);
			$myMax 	= $result+1;

			$lastNum = $myMax;
			$lastNum1 = $myMax;
			$len = strlen($lastNum);

			$pattLgth = 4;
			
			if($pattLgth==2)
			{
				if($len==1) $nol="0";
			}
			elseif($pattLgth==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($pattLgth==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($pattLgth==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($pattLgth==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($pattLgth==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			else
			{
				$nol 	= "0";
			}
			$lastNum 	= $nol.$lastNum;
			$Man_No		= "$pattCode-$PRJCODE-$lastNum";

			$alert1 = "Nomor dokumen $jrnCode sudah digunakan oleh dokumen lain. Sistem akan mengganti secara otomatis ke $Man_No";
			echo "1~$alert1~$Man_No";
		}
		else
		{
			$Man_No = $jrnCode;
			$alert1 = "Nomor dokumen $jrnCode sudah digunakan oleh dokumen lain. Sistem akan mengganti secara otomatis ke $jrnType";
			echo "0~$alert1~$Man_No";
		}
	}

	function genCodeCPRJ()
	{
		$collD 		= $_POST['collDt'];
		$collDt 	= explode("~", $collD);
		$jrnCode 	= $collDt[0];
		$pattCode 	= $collDt[1];
		$PRJCODE 	= $collDt[2];
		$jrnType 	= "CPRJ";

		$s_jrnC 	= "tbl_journalheader_vcash WHERE Manual_No = '$jrnCode'";
		$r_jrnC 	= $this->db->count_all($s_jrnC);
		if($r_jrnC > 0)
		{
			$pattY 	= date('Y');
			$yearC	= (int)$pattY;

			$sql 	= "tbl_journalheader WHERE YEAR(JournalH_Date) = $yearC AND proj_Code = '$PRJCODE' AND JournalType = '$jrnType'";
			$result = $this->db->count_all($sql);
			$myMax 	= $result+1;

			$lastNum = $myMax;
			$lastNum1 = $myMax;
			$len = strlen($lastNum);

			$pattLgth = 4;
			
			if($pattLgth==2)
			{
				if($len==1) $nol="0";
			}
			elseif($pattLgth==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($pattLgth==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($pattLgth==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($pattLgth==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($pattLgth==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			else
			{
				$nol 	= "0";
			}
			$lastNum 	= $nol.$lastNum;
			$Man_No		= "$pattCode-$PRJCODE-$lastNum";

			$alert1 = "Nomor dokumen $jrnCode sudah digunakan oleh dokumen lain. Sistem akan mengganti secara otomatis ke $Man_No";
			echo "1~$alert1~$Man_No";
		}
		else
		{
			$Man_No = $jrnCode;
			$alert1 = "Nomor dokumen $jrnCode sudah digunakan oleh dokumen lain. Sistem akan mengganti secara otomatis ke $jrnType";
			echo "0~$alert1~$Man_No";
		}
	}

	function delRVCASH()
	{
		$strItem 	= $_POST['collID'];
		$arrItem 	= explode("~", $strItem);

		$PATT_ID 	= $arrItem[1];
		$JRN_NUM 	= $arrItem[2];
		$PRJCODE 	= $arrItem[3];

		$delSQL		= "DELETE FROM tbl_journaldetail WHERE JournalD_Id = $PATT_ID AND JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
		$this->db->query($delSQL);

		$delSQL		= "DELETE FROM tbl_journaldetail_tmp WHERE JournalD_Id = $PATT_ID AND JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
		$this->db->query($delSQL);

		$delSQL		= "DELETE FROM tbl_journaldetail_vcash WHERE JournalD_Id = $PATT_ID AND JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
		$this->db->query($delSQL);

		echo "Komponen sudah kami hapus.";
	}
	
 	function jrnrev() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$jrnType 		= "JRNREV";
		
		$url			= site_url('c_finance/c_cho70d18/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($jrnType));
		redirect($url);
	}

  	function get_AllDataJRNREV() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("JournalH_Id",
									"Manual_No",
									"JournalH_Date",
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cho_payment->get_AllDataJRNREVC($PRJCODE, $jrnType, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataJRNREVL($PRJCODE, $jrnType, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$GEJ_STAT_VCASH		= $dataI['GEJ_STAT_VCASH'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				if($GEJ_STAT_VCASH == 6)
				{
					$STATDESC		= "Closed";
					$STATCOL		= "info";
				}

				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$isLock				= $dataI['isLock'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$EmpID 				= $dataI['Emp_ID'];
				$acc_number			= $dataI['acc_number'];
				$realizD_Amn		= $dataI['Journal_AmountReal'];

				$realizD 			= 	"";
				$realizR 			= 	"";

				if($jrnType == 'CPRJ')
					$PERSL_EMPID = $EmpID;

				$ISPERSLD 		= "Voucher Cash";
				$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
									UNION
									SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
				$r_emp 			= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$EMP_NAME	= $rw_emp->EMP_NAME;
				endforeach;

				if($realizD_Amn == 0)
				{
					$realizD_Amn = number_format($realizD_Amn, 2);
				}
				else
				{
					$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
				}

				$realizD 		= 	"<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$Paid."</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".$realizD_Amn."
								  	</div>";
				$realizR 		= 	"<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>";

				$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail_vcash A
										LEFT JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE
										WHERE A.proj_Code = '$PRJCODE' AND A.JournalH_Code = '$JournalH_Code'";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$noUAcc 		= $noUAcc+1;
					if($noUAcc == 1)
						$Acc_Name	= $rowISHO->Acc_Name;
					else
						$Acc_Name	= $Acc_Name.", ".$rowISHO->Acc_Name;
				endforeach;
				$Acc_Name 			= ucwords(strtolower($Acc_Name));

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$secPrint1			= site_url('c_finance/c_cho70d18/printdocument_JRNREV/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18JRNREV/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));


				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 			= base_url().'index.php/__l1y/trashCHO_NEW/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader_vcash~tbl_journaldetail_vcash~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				$isLockD 			= "";

				$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";
				$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
								<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
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
								</label>";

				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$isLockD $Manual_No</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$ISPERSLD." </strong></div>",
										  	$JournalH_DateV,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$JournalH_Desc."
									  		<div style='margin-left: 20px; font-style: italic;'>
										  		<div><strong>$Acc_Name</strong></div>
										  		$realizR
										  	</div>",
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$GJournal_Total."</strong></div>
										  	$realizD",
										  	"<div style='text-align:right; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}
								
			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F",
										"G",
										"H");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataJRNREVGRP() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
		$SPLCODE		= $_GET['SPLC'];
		$GEJ_STAT		= $_GET['GSTAT'];
			
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("JournalH_Id",
									"Manual_No",
									"JournalH_Date",
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cho_payment->get_AllDataJRNREVGRPC($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataJRNREVGRPL($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$GEJ_STAT_VCASH		= $dataI['GEJ_STAT_VCASH'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				if($GEJ_STAT_VCASH == 6)
				{
					$STATDESC		= "Closed";
					$STATCOL		= "info";
				}

				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$isLock				= $dataI['isLock'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$EmpID 				= $dataI['Emp_ID'];
				$acc_number			= $dataI['acc_number'];
				$realizD_Amn		= $dataI['Journal_AmountReal'];

				$realizD 			= 	"";
				$realizR 			= 	"";

				if($jrnType == 'CPRJ')
					$PERSL_EMPID = $EmpID;

				$ISPERSLD 		= "Voucher Cash";
				$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
									UNION
									SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
				$r_emp 			= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$EMP_NAME	= $rw_emp->EMP_NAME;
				endforeach;

				if($realizD_Amn == 0)
				{
					$realizD_Amn = number_format($realizD_Amn, 2);
				}
				else
				{
					$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
				}

				$realizD 		= 	"<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$Paid."</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".$realizD_Amn."
								  	</div>";
				$realizR 		= 	"<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>";

				$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail_vcash A
										LEFT JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE
										WHERE A.proj_Code = '$PRJCODE' AND A.JournalH_Code = '$JournalH_Code'";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$noUAcc 		= $noUAcc+1;
					if($noUAcc == 1)
						$Acc_Name	= $rowISHO->Acc_Name;
					else
						$Acc_Name	= $Acc_Name.", ".$rowISHO->Acc_Name;
				endforeach;
				$Acc_Name 			= ucwords(strtolower($Acc_Name));

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$secPrint1			= site_url('c_finance/c_cho70d18/printdocument_VCASH/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18JRNREV/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));


				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 			= base_url().'index.php/__l1y/trashCHO_NEW/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader_vcash~tbl_journaldetail_vcash~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				$isLockD 			= "";

				$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";
				$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
								<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
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
								</label>";

				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$isLockD $Manual_No</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$ISPERSLD." </strong></div>",
										  	$JournalH_DateV,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$JournalH_Desc."
									  		<div style='margin-left: 20px; font-style: italic;'>
										  		<div><strong>$Acc_Name</strong></div>
										  		$realizR
										  	</div>",
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$GJournal_Total."</strong></div>
										  	$realizD",
										  	"<div style='text-align:right; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}
								
			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F",
										"G",
										"H");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataVC() // GOOD
	{
		$PRJCODE	= $_GET['id'];

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
			$num_rows 		= $this->m_cho_payment->get_AllDataVCC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataVCL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$INV_NOTES 			= wordwrap($dataI['INV_NOTES'], 50, "<br>", TRUE);
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
				$s_00 		= "SELECT SPLDESC, SPLSTAT FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
				$r_00 		= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00):
					$SPLDESC 	= $rw_00->SPLDESC;
					$SPLSTAT 	= $rw_00->SPLSTAT;
				endforeach;

				if($INV_NOTES == '')
				{
					$INV_NOTES	= "-";
				}

				$this->db->select_sum("A.CBD_AMOUNT", "INV_AMOUNT_PAID");
				$this->db->from("tbl_bp_detail A");
				$this->db->join("tbl_bp_header B", "B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE");
				$this->db->where(["A.CBD_DOCNO" => $INV_NUM, "A.PRJCODE" => $PRJCODE, "B.CB_PAYFOR" => $SPLCODE]);
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
					$chkBox			= "<input type='radio' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_DUEDATE."|".$SPLDESC."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_TOT."' onClick='pickThis1(this);' disabled/>";
				}
				else
				{
					$chkBox			= "<input type='radio' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_DUEDATE."|".$SPLDESC."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_TOT."' onClick='pickThis1(this);'/>";
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

  	function get_AllDataVCDET() // GOOD
	{
		$PRJCODE	= $_GET['id'];
		$refNum 	= $_GET['refNum'];

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
			$num_rows 		= $this->m_cho_payment->get_AllDataJRNREVDC($PRJCODE, $refNum, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataJRNREVDL($PRJCODE, $refNum, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				/*$JournalH_Code		= $dataI['JournalH_Code'];
				$JournalType		= $dataI['JournalType'];
				$Acc_Id				= $dataI['Acc_Id'];
				$Acc_Name			= $dataI['Acc_Name'];
				$JOBCODEID			= $dataI['JOBCODEID'];
				$JournalD_Debet		= $dataI['JournalD_Debet'];
				$JournalD_Kredit	= $dataI['JournalD_Kredit'];
				$ITM_CODE			= $dataI['ITM_CODE'];
				$ITM_CATEG			= $dataI['ITM_CATEG'];
				$ITM_PRICE			= $dataI['ITM_PRICE'];
				$ITM_UNIT			= $dataI['ITM_UNIT'];
				$Other_Desc			= $dataI['Other_Desc'];
				$Journal_DK			= $dataI['Journal_DK'];
				$PPN_Amount			= $dataI['PPN_Amount'];
				$PPH_Amount			= $dataI['PPH_Amount'];*/

				$JournalD_Id 		= $dataI['JournalD_Id'];
				$JournalH_Code 		= $dataI['JournalH_Code'];
				$Acc_Id 			= $dataI['Acc_Id'];
				$JOBCODEID 			= $dataI['JOBCODEID'];
				$JournalD_Debet 	= $dataI['JournalD_Debet'];
				$JournalD_Debet_tax = $dataI['JournalD_Debet_tax'];
				$JournalD_Kredit 	= $dataI['JournalD_Kredit'];
				$JournalD_Kredit_tax= $dataI['JournalD_Kredit_tax'];
				$isDirect 			= $dataI['isDirect'];
				$Notes 				= $dataI['Notes'];
				$ITM_CODE 			= $dataI['ITM_CODE'];
				$ITM_CATEG 			= $dataI['ITM_CATEG'];
				$ITM_VOLM 			= $dataI['ITM_VOLM'];
				$ITM_PRICE 			= $dataI['ITM_PRICE'];
				$PPN_Code 			= $dataI['PPN_Code'];
				$PPN_Perc 			= $dataI['PPN_Perc'];
				$PPN_Amount 		= $dataI['PPN_Amount'];
				$PPH_Code 			= $dataI['PPH_Code'];
				$PPH_Perc 			= $dataI['PPH_Perc'];
				$PPH_Amount 		= $dataI['PPH_Amount'];
				$isVoid 			= $dataI['isVoid'];

				if($PPN_Code != '')
				{
					$ACC_PPN		= '';
					$s_PPN 			= "SELECT TAXLA_LINKOUT FROM tbl_tax_ppn WHERE TAXLA_NUM = '$PPN_Code'";
					$r_PPN			= $this->db->query($s_PPN)->result();
					foreach($r_PPN as $rw_PPN):
						$ACC_PPN	= $rw_PPN->TAXLA_LINKOUT;
					endforeach;
					if($ACC_PPN == '')
					{
						$disButton 	= 1;
						$INCPPN 	= 1;
						$PPNDES 	= "Belum ada pengaturan kode akun untuk PPn";
					}
				}

				if($PPH_Code != '')
				{
					$ACC_PPH		= '';
					$s_PPH 			= "SELECT TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$PPH_Code'";
					$r_PPH			= $this->db->query($s_PPH)->result();
					foreach($r_PPH as $rw_PPH):
						$ACC_PPH	= $rw_PPH->TAXLA_LINKOUT;
					endforeach;
					if($ACC_PPH == '')
					{
						//$disButton 	= 1;
						//$INCPPH 	= 1;	// sementara dihide
						$disButton 	= 0;
						$INCPPH 	= 0;
						$PPHDES 	= "Belum ada pengaturan kode akun untuk PPh";
					}
				}

				$JODBDESC 			= "";
				$sqlJOBD1			= "SELECT ITM_PRICE, JOBDESC, JOBPARENT
										FROM tbl_joblist_detail
										WHERE JOBCODEID = '$JOBCODEID'
											AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJOBD1			= $this->db->query($sqlJOBD1)->result();
				foreach($resJOBD1 as $rowJOBD1) :
					//$ITM_PRICE		= $rowJOBD1->ITM_PRICE;
					$JODBDESC		= $rowJOBD1->JOBDESC;
					$JOBPARENT		= $rowJOBD1->JOBPARENT;
				endforeach;

				if($JODBDESC == '')
				{
					$JODBDESC 		= "";
					$JOBPARENT 		= "";
				}

				$ITM_UNIT 			= $dataI['ITM_UNIT'];
				$Ref_Number 		= $dataI['Ref_Number'];
				$Other_Desc 		= $dataI['Other_Desc'];
				$Journal_DK 		= $dataI['Journal_DK'];
				$isTax 				= $dataI['isTax'];
				
				$ITM_VOLMBG			= 0;
				$ITM_BUDG			= 0;
				$ITM_USED			= 0;
				$ITM_USED_AM		= 0;
				$sqlJOBD			= "SELECT ITM_VOLM AS ITM_VOLMBG, ITM_BUDG,
											ITM_USED, ITM_USED_AM
										FROM tbl_joblist_detail
										WHERE JOBCODEID = '$JOBCODEID'
											AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJOBD			= $this->db->query($sqlJOBD)->result();
				foreach($resJOBD as $rowJOBD) :
					$ITM_VOLMBG		= $rowJOBD->ITM_VOLMBG;
					$ITM_BUDG		= $rowJOBD->ITM_BUDG;
					$ITM_USED		= $rowJOBD->ITM_USED;
					$ITM_USED_AM	= $rowJOBD->ITM_USED_AM;
				endforeach;
				$BUDG_REMVOLM		= $ITM_VOLMBG - $ITM_USED;
				$BUDG_REMAMN		= $ITM_BUDG - $ITM_USED_AM;
				
				if($Journal_DK == 'D')
				{
					$AmountV		= $JournalD_Debet;
				}
				else
				{
					$AmountV		= $JournalD_Kredit;
				}
					
				if($isTax == 1)
				{
					if($Journal_DK == 'D')
					{
						$AmountV		= $JournalD_Debet_tax;
					}
					else
					{
						$AmountV		= $JournalD_Kredit_tax;
					}
					$isTaxD			= 'Tax';
				}
				else
				{
					$isTaxD			= 'No';
				}
				
				$ITM_NAME			= '';
				if($ITM_CODE != '')
				{
					$sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$resITM 	= $this->db->query($sqlITM)->result();
					foreach($resITM as $rowITM) :
						$ITM_NAME 	= $rowITM->ITM_NAME;
					endforeach;
				}
				else
				{
					$sqlITM		= "SELECT Account_NameId FROM tbl_chartaccount WHERE Account_Number = '$Acc_Id' AND PRJCODE = '$PRJCODE'";
					$resITM 	= $this->db->query($sqlITM)->result();
					foreach($resITM as $rowITM) :
						$ITM_NAME 	= $rowITM->Account_NameId;
					endforeach;
				}
				
				// RESERVE
				$ITM_USEDR			= 0;
				$ITM_USEDR_AM		= 0;

				$sqlJOBDR			= "SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
										FROM tbl_journaldetail
										WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE'
											AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT IN (1,2,7)
											AND JournalD_Id != $JournalD_Id";
				$resJOBDR			= $this->db->query($sqlJOBDR)->result();
				foreach($resJOBDR as $rowJOBDR) :
					$ITM_USEDR		= $rowJOBDR->TOTVOL;
					$ITM_USEDR_AM	= $rowJOBDR->TOTAMN;
				endforeach;
				
				$BUDG_REMVOLM	= $BUDG_REMVOLM - $ITM_USEDR;
				$BUDG_REMAMNT	= $BUDG_REMAMN - $ITM_USEDR_AM;

				$JobView		= "$ITM_CODE - $JODBDESC ($Acc_Id)";
				$JobView 		= wordwrap($JobView, 50, "<br>", TRUE);

				$JOBDESCH		= "";
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				foreach($resJOBDESC as $rowJOBDESC) :
					$JOBDESCH	= $rowJOBDESC->JOBDESC;
				endforeach;

				$JOBDESCH1 		= wordwrap("$JOBPARENT : $JOBDESCH", 50, "<br>", TRUE);
				$JOBDESCH 		= '<div style="margin-left: 15px; font-style: italic;">
								  		<i class="text-muted fa fa-rss"></i>&nbsp;&nbsp;'.$JOBDESCH1.'
								  	</div>';

				
				$JOBDESCH1 		= $JOBDESCH;
				$disButton 		= 0;
				if($JOBPARENT == '' && $Journal_DK == 'D')
				{
					$disButton 		= 1;
					$JOBDESCH1 		= "Kode komponen ini belum terkunci atau sedang dibuka dalam daftar RAP. Silahkan hubungi pihak yang memiliki otorisasi mengunci RAP. $sqlJOBD1";
					$JOBDESCH2 		= wordwrap("$JOBDESCH1", 50, "<br>", TRUE);
					$JOBDESCH 		= '<span class="label label-danger" style="font-size:12px; font-style: italic;">'.$JOBDESCH2.'</span>';
				}

				if($Journal_DK == 'K')
				{
					$JobView		= "$Acc_Id - $ITM_NAME";
					$JobView 		= wordwrap($JobView, 50, "<br>", TRUE);

					$JOBDESCH1 		= wordwrap("$JobView", 50, "<br>", TRUE);
					$JOBDESCH 		= '';
				}

				$secDel 		= base_url().'index.php/c_finance/c_cho70d18/revTrx/?id=';
				$delID 			= "$secDel~$JournalD_Id~$JournalH_Code~$JOBCODEID~$ITM_CODE~$ITM_NAME~$PRJCODE";

				$secAction 		= "";
				if($isVoid == 0)
				{
					$secAction	= "<input type='hidden' name='urlRev".$noU."' id='urlRev".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
										<a href='javascript:void(null);' class='btn bg-red btn-xs' onClick='createRev(".$noU.")' title='Hapus'>
											<i class='fa fa-undo'></i>
										</a>
									</label>";
				}

				$output['data'][] 	= array($secAction,
										  	"<div style='white-space:nowrap'>".$JobView.$JOBDESCH."</div>",
										  	"$ITM_UNIT
										  	<input type='hidden' id='ITMDESC".$noU."' value='($ITM_CODE) : $ITM_NAME'>
										  	<input type='hidden' id='ISDIS".$noU."' value='".$disButton."'>
										  	<input type='hidden' id='totalrow' name='totalrow' value='".$noU."'>",
											number_format($ITM_VOLM, 2),
											number_format($ITM_PRICE, 2),
											number_format($AmountV, 2),
											number_format($BUDG_REMAMNT, 2),
											number_format($PPN_Amount, 2),
											number_format($PPH_Amount, 2),
											$Ref_Number,
											$Other_Desc);
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A = $PRJCODE",
									  "B = $refNum",
									  "C",
									  "D",
									  "E",
									  "F",
									  "G",
									  "H",
									  "I",
									  "J",
									  "K");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function jrnREVJRN() // GOOD
	{
		$JRN_NUM	= $_POST['JRN_NUM'];
		$JRN_MNUM	= $_POST['JRN_MNUM'];
		$JRN_MREF	= $_POST['JRN_MREF'];
		$PRJCODE 	= $_POST['PRJCODE'];
		$CREATER 	= $_POST['CREATER'];

		$Created 	= date('Y-m-d H:i:s');

		$JRN_NUM2 	= $JRN_NUM."r";
		$JRN_MNUM2 	= $JRN_MNUM;

		// COPY KE TABEL JURNAL REVERSAL
			$s_00 	= "INSERT INTO tbl_journalheader_revers (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Emp_ID, Created,
							LastUpdate, KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
							Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, Manual_No, GEJ_STAT, GEJ_STAT_PD, 
							GEJ_STAT_VCASH, STATDESC, STATCOL, Reference_Number, REF_NUM, REF_CODE)
						SELECT '$JRN_NUM2', 'JRNREV', JournalH_Desc, JournalH_Date, Company_ID, Emp_ID, '$Created',
							'$Created', KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
							Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, '$JRN_MNUM2', GEJ_STAT, GEJ_STAT_PD, 
							GEJ_STAT_VCASH, STATDESC, STATCOL, '$JRN_NUM', '$JRN_NUM', '$JRN_MREF' FROM tbl_journalheader_vcash
						WHERE JournalH_Code = '$JRN_NUM'";
			$this->db->query($s_00);

			$s_01 	= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Emp_ID, Created,
							LastUpdate, KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
							Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, Manual_No, GEJ_STAT, GEJ_STAT_PD, 
							GEJ_STAT_VCASH, STATDESC, STATCOL, Reference_Number, REF_NUM, REF_CODE)
						SELECT '$JRN_NUM2', 'JRNREV', JournalH_Desc, JournalH_Date, Company_ID, Emp_ID, '$Created',
							'$Created', KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
							Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, 'J$JRN_MNUM2', GEJ_STAT, GEJ_STAT_PD, 
							GEJ_STAT_VCASH, STATDESC, STATCOL, '$JRN_NUM', '$JRN_NUM', '$JRN_MREF' FROM tbl_journalheader_vcash
						WHERE JournalH_Code = '$JRN_NUM'";
			$this->db->query($s_01);

			$s_01 	= "SELECT * FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM'";
			$r_01 	= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$JRND_ID 	= $rw_01->JournalD_Id;
				$accYr 		= date('Y', strtotime($rw_01->JournalH_Date));
				$Acc_Id 	= $rw_01->Acc_Id;
				$BaseD 		= $rw_01->Base_Debet;
				$BaseK 		= $rw_01->Base_Kredit;
				$JRND_DK 	= $rw_01->Journal_DK;
				if($JRND_DK == 'D')
					$JRNDDK = 'K';
				else
					$JRNDDK = 'D';

				/*$s_01a 		= "INSERT INTO tbl_journaldetail_vcash (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
									COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
								SELECT '$JRN_NUM2', JournalH_Date, 'JRNREV', Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, $BaseK, $BaseD, $BaseK, $BaseD,
									$BaseK, $BaseD, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, '$JRN_MNUM2', Other_Desc, '$JRNDDK', Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
									FROM tbl_journaldetail
								WHERE JournalH_Code = '$JRN_NUM' AND JournalD_Id = $JRND_ID";
				$this->db->query($s_01a);*/

				$s_01b 		= "INSERT INTO tbl_journaldetail_revers (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
									COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
								SELECT '$JRN_NUM2', JournalH_Date, 'JRNREV', Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, $BaseK, $BaseD, $BaseK, $BaseD,
									$BaseK, $BaseD, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, '$JRN_MNUM2', Other_Desc, '$JRNDDK', Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
									FROM tbl_journaldetail
								WHERE JournalH_Code = '$JRN_NUM' AND JournalD_Id = $JRND_ID";
				$this->db->query($s_01b);

				$s_01c 		= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
									COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
								SELECT '$JRN_NUM2', JournalH_Date, 'JRNREV', Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, $BaseK, $BaseD, $BaseK, $BaseD,
									$BaseK, $BaseD, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, '$JRN_MNUM2', Other_Desc, '$JRNDDK', Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
									FROM tbl_journaldetail
								WHERE JournalH_Code = '$JRN_NUM' AND JournalD_Id = $JRND_ID";
				$this->db->query($s_01c);

				// START : UPDATE LAWAN KAS/BANK
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id' LIMIT 1";
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
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET
												Base_Debet 		= Base_Debet+$BaseK, 	Base_Kredit 	= Base_Kredit+$BaseD,
												Base_Debet2 	= Base_Debet2+$BaseK, 	Base_Kredit2 	= Base_Kredit2+$BaseD,
												BaseD_$accYr 	= BaseD_$accYr+$BaseK, 	BaseK_$accYr 	= BaseK_$accYr+$BaseD 
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : UPDATE KAS/BANK
			endforeach;

		// UPDATE JOURNAL STATUS TO BE CANCEL
			$completeName 	= $this->session->userdata['completeName'];
			$s_02 			= "UPDATE tbl_journalheader_vcash SET isCanceled = 1 WHERE JournalH_Code = '$JRN_NUM'";
			$this->db->query($s_02);

			$s_03 			= "UPDATE tbl_journalheader_revers SET CREATERNM = '$completeName' WHERE JournalH_Code = '$JRN_NUM2'";
			$this->db->query($s_03);
			
		$alert1 	= "Voucher no $JRN_MNUM berhasil dibuatkan jurnal pembalik";

		echo "$alert1";
	}

	function revTrx()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $JRND_ID 	= $colExpl[1];
        $JRND_NUM	= $colExpl[2];
        $JOB_ID		= $colExpl[3];
        $ITM_CODE	= $colExpl[4];
        $ITM_NAME	= $colExpl[5];
        $PRJCODE	= $colExpl[6];
        $JRND_MNUM	= $colExpl[7];

        $s_MAXNO	= "tbl_journalheader_revers WHERE JournalH_Code = '$JRND_NUM' AND proj_Code = '$PRJCODE'";
        $NXT_NO 	= $s_MAXNO+1;

		$Created 	= date('Y-m-d H:i:s');

		$JRN_NUM 	= $JRND_NUM;
		$JRN_NUM2 	= $JRND_NUM."r";
		$JRN_MNUM2 	= $JRND_MNUM."r-".$NXT_NO;

		// START : COPY KE TABEL JURNAL REVERSAL- SISI DEBET PINDAH KE KREDIT
			$s_00 	= "INSERT INTO tbl_journalheader_revers (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Emp_ID, Created,
							LastUpdate, KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
							Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, Manual_No, GEJ_STAT, GEJ_STAT_PD, 
							GEJ_STAT_VCASH, STATDESC, STATCOL)
						SELECT '$JRN_NUM2', 'JRNREV', JournalH_Desc, JournalH_Date, Company_ID, Emp_ID, '$Created',
							'$Created', KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
							Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, '$JRN_MNUM2', GEJ_STAT, GEJ_STAT_PD, 
							GEJ_STAT_VCASH, STATDESC, STATCOL FROM tbl_journalheader
						WHERE JournalH_Code = '$JRND_NUM'";
			$this->db->query($s_00);

			$s_01 	= "SELECT * FROM tbl_journaldetail WHERE JournalD_Id = '$JRND_ID'";
			$r_01 	= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$JRND_ID 	= $rw_01->JournalD_Id;
				$accYr 		= date('Y', strtotime($rw_01->JournalH_Date));
				$Acc_Id 	= $rw_01->Acc_Id;
				$Acc_Cross 	= $rw_01->Acc_Id_Cross;
				$BaseD 		= $rw_01->Base_Debet;
				$BaseK 		= $rw_01->Base_Kredit;
				$JRND_DK 	= $rw_01->Journal_DK;
				if($JRND_DK == 'D')
					$JRNDDK = 'K';
				else
					$JRNDDK = 'D';

				// START : KREDIT
					$s_01a 		= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
										COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
									SELECT '$JRN_NUM2', JournalH_Date, 'JRNREV', Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, $BaseK, $BaseD, $BaseK, $BaseD,
										$BaseK, $BaseD, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, '$JRN_MNUM2', Other_Desc, '$JRNDDK', Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
										FROM tbl_journaldetail
									WHERE JournalH_Code = '$JRN_NUM' AND JournalD_Id = $JRND_ID";
					$this->db->query($s_01a);

					$s_01b 		= "INSERT INTO tbl_journaldetail_revers (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
										COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
									SELECT '$JRN_NUM2', JournalH_Date, 'JRNREV', Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, $BaseK, $BaseD, $BaseK, $BaseD,
										$BaseK, $BaseD, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, '$JRN_MNUM2', Other_Desc, '$JRNDDK', Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
										FROM tbl_journaldetail
									WHERE JournalH_Code = '$JRN_NUM' AND JournalD_Id = $JRND_ID";
					$this->db->query($s_01b);


					// UPDATE JOURNAL STATUS TO BE CANCEL
						$s_02 	= "UPDATE tbl_journaldetail_vcash SET isVoid = 1 WHERE JournalH_Code = '$JRN_NUM' AND JournalD_Id = $JRND_ID";
						$this->db->query($s_02);

					// START : UPDATE LAWAN KAS/BANK
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id' LIMIT 1";
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
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET
													Base_Debet 		= Base_Debet+$BaseK, 	Base_Kredit 	= Base_Kredit+$BaseD,
													Base_Debet2 	= Base_Debet2+$BaseK, 	Base_Kredit2 	= Base_Kredit2+$BaseD,
													BaseD_$accYr 	= BaseD_$accYr+$BaseK, 	BaseK_$accYr 	= BaseK_$accYr+$BaseD 
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : UPDATE KAS/BANK
				// END : KREDIT

				// START : DEBET
					$s_01a 		= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
										COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
									SELECT '$JRN_NUM2', JournalH_Date, 'JRNREV', Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, $BaseD, $BaseK, $BaseD, $BaseK,
										$BaseD, $BaseK, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, '$JRN_MNUM2', Other_Desc, 'D', Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
										FROM tbl_journaldetail
									WHERE JournalH_Code = '$JRN_NUM' AND Journal_DK = 'K' AND Acc_Id = '$Acc_Cross'";
					$this->db->query($s_01a);

					$s_01b 		= "INSERT INTO tbl_journaldetail_revers (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
										COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
									SELECT '$JRN_NUM2', JournalH_Date, 'JRNREV', Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, $BaseD, $BaseK, $BaseD, $BaseK,
										$BaseD, $BaseK, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, '$JRN_MNUM2', Other_Desc, 'D', Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
										FROM tbl_journaldetail
									WHERE JournalH_Code = '$JRN_NUM' AND Journal_DK = 'K' AND Acc_Id = '$Acc_Cross'";
					$this->db->query($s_01b);

					// START : UPDATE LAWAN KAS/BANK
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Cross' LIMIT 1";
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
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET
													Base_Debet 		= Base_Debet+$BaseD, 	Base_Kredit 	= Base_Kredit+$BaseK,
													Base_Debet2 	= Base_Debet2+$BaseD, 	Base_Kredit2 	= Base_Kredit2+$BaseK,
													BaseD_$accYr 	= BaseD_$accYr+$BaseD, 	BaseK_$accYr 	= BaseK_$accYr+$BaseK 
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Cross'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : UPDATE KAS/BANK
				// END : DEBET
			endforeach;
		// END : COPY KE TABEL JURNAL REVERSAL- SISI DEBET PINDAH KE KREDIT

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Transaksi ($ITM_CODE) : $ITM_NAME sudah dibuatkan jurnal pembalik.";
		}
		else
		{
			$alert1	= "Reversal Journal for $ITM_NAME has been created.";
		}
		echo "$alert1";
	}
	
	function up0b28t18JRNREV() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$MenuCode 			= 'MN045';
			$data["MenuCode"] 	= 'MN045';
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 				= $this->m_cho_payment->get_JRNREV_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['Reference_Number'] = $getGEJ->Reference_Number;
			$data['REF_NUM'] 		= $getGEJ->REF_NUM;
			$data['REF_CODE'] 		= $getGEJ->REF_CODE;
			$data['PPNH_Amount'] 	= $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] 	= $getGEJ->PPHH_Amount;
			$data['GJournal_Total'] = $getGEJ->GJournal_Total;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;

			$mnCode					= 'MN045';
			$data["MenuCode"] 		= 'MN045';
			$data["MenuApp"] 		= 'MN045';
			$MenuCode 				= 'MN045';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			$getMN					= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] 	= $getMN->menu_name_IND;
			else
				$data["mnName"] 	= $getMN->menu_name_ENG;

			$data['form_action']	= site_url('c_finance/c_cho70d18/update_processVCASH');

			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			//$data['form_action']= site_url('c_finance/c_cho70d18/update_process');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				//$MenuCode 	= 'MN359';
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

			$this->load->view('v_finance/v_cho_payment/v_cho_jrnrev_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function jrnrevision() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$jrnType 		= "JREVISION";
		
		$url			= site_url('c_finance/c_cho70d18/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($jrnType));
		redirect($url);
	}

  	function get_AllDataJRNREVISION() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("JournalH_Id",
									"Manual_No",
									"JournalH_Date",
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cho_payment->get_AllDataJRNREVISIONC($PRJCODE, $jrnType, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataJRNREVISIONL($PRJCODE, $jrnType, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$GEJ_STAT_VCASH		= $dataI['GEJ_STAT_VCASH'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				if($GEJ_STAT_VCASH == 6)
				{
					$STATDESC		= "Closed";
					$STATCOL		= "info";
				}

				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$isLock				= $dataI['isLock'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$EmpID 				= $dataI['Emp_ID'];
				$acc_number			= $dataI['acc_number'];
				$realizD_Amn		= $dataI['Journal_AmountReal'];

				$realizD 			= 	"";
				$realizR 			= 	"";

				if($jrnType == 'CPRJ')
					$PERSL_EMPID = $EmpID;

				$ISPERSLD 		= "Voucher Cash";
				$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
									UNION
									SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
				$r_emp 			= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$EMP_NAME	= $rw_emp->EMP_NAME;
				endforeach;

				if($realizD_Amn == 0)
				{
					$realizD_Amn = number_format($realizD_Amn, 2);
				}
				else
				{
					$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
				}

				$realizD 		= 	"<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$Paid."</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".$realizD_Amn."
								  	</div>";
				$realizR 		= 	"<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>";

				$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail_vcash A
										LEFT JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE
										WHERE A.proj_Code = '$PRJCODE' AND A.JournalH_Code = '$JournalH_Code'";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$noUAcc 		= $noUAcc+1;
					if($noUAcc == 1)
						$Acc_Name	= $rowISHO->Acc_Name;
					else
						$Acc_Name	= $Acc_Name.", ".$rowISHO->Acc_Name;
				endforeach;
				$Acc_Name 			= ucwords(strtolower($Acc_Name));

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18JREVISION/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$secPrint1			= site_url('c_finance/c_cho70d18/printdocument_JRNREV/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18JREVISION/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));


				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 			= base_url().'index.php/__l1y/trashCHO_NEW/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader_vcash~tbl_journaldetail_vcash~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				$isLockD 			= "";

				$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";
				$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
								<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
							   	<label style='white-space:nowrap'>
							   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
							   		<i class='fa fa-eye'></i>
							   	</a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
									<i class='glyphicon glyphicon-off'></i>
								</a>
								</label>";

				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$isLockD $Manual_No</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$ISPERSLD." </strong></div>",
										  	$JournalH_DateV,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$JournalH_Desc."
									  		<div style='margin-left: 20px; font-style: italic;'>
										  		<div><strong>$Acc_Name</strong></div>
										  		$realizR
										  	</div>",
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$GJournal_Total."</strong></div>
										  	$realizD",
										  	"<div style='text-align:right; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}
								
			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F",
										"G",
										"H");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataJRNREVISIONGRP() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
		$SPLCODE		= $_GET['SPLC'];
		$GEJ_STAT		= $_GET['GSTAT'];
			
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("JournalH_Id",
									"Manual_No",
									"JournalH_Date",
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cho_payment->get_AllDataJRNREVGRPC($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataJRNREVGRPL($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$GEJ_STAT_VCASH		= $dataI['GEJ_STAT_VCASH'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				if($GEJ_STAT_VCASH == 6)
				{
					$STATDESC		= "Closed";
					$STATCOL		= "info";
				}

				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$isLock				= $dataI['isLock'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$EmpID 				= $dataI['Emp_ID'];
				$acc_number			= $dataI['acc_number'];
				$realizD_Amn		= $dataI['Journal_AmountReal'];

				$realizD 			= 	"";
				$realizR 			= 	"";

				if($jrnType == 'CPRJ')
					$PERSL_EMPID = $EmpID;

				$ISPERSLD 		= "Voucher Cash";
				$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
									UNION
									SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
				$r_emp 			= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$EMP_NAME	= $rw_emp->EMP_NAME;
				endforeach;

				if($realizD_Amn == 0)
				{
					$realizD_Amn = number_format($realizD_Amn, 2);
				}
				else
				{
					$realizD_Amn = "<a href='javascript:void(null);' id='paidval' title='click to view history voucher' onClick='viewHistpaid(".$noU.")'>".number_format($realizD_Amn, 2)."</a>";
				}

				$realizD 		= 	"<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$Paid."</strong>
									</div>
								  	<div style='margin-left: 20px;'>
								  		".$realizD_Amn."
								  	</div>";
				$realizR 		= 	"<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>";

				$secViewpaid	= site_url('c_finance/c_cho70d18/viewpaid_voucher/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail_vcash A
										LEFT JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE
										WHERE A.proj_Code = '$PRJCODE' AND A.JournalH_Code = '$JournalH_Code'";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$noUAcc 		= $noUAcc+1;
					if($noUAcc == 1)
						$Acc_Name	= $rowISHO->Acc_Name;
					else
						$Acc_Name	= $Acc_Name.", ".$rowISHO->Acc_Name;
				endforeach;
				$Acc_Name 			= ucwords(strtolower($Acc_Name));

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$secPrint1			= site_url('c_finance/c_cho70d18/printdocument_VCASH/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18JRNREV/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));


				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 			= base_url().'index.php/__l1y/trashCHO_NEW/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader_vcash~tbl_journaldetail_vcash~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				$isLockD 			= "";

				$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";
				$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								<input type='hidden' name='urlViewpaid".$noU."' id='urlViewpaid".$noU."' value='".$secViewpaid."'>
								<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
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
								</label>";

				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$isLockD $Manual_No</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$ISPERSLD." </strong></div>",
										  	$JournalH_DateV,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$JournalH_Desc."
									  		<div style='margin-left: 20px; font-style: italic;'>
										  		<div><strong>$Acc_Name</strong></div>
										  		$realizR
										  	</div>",
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$GJournal_Total."</strong></div>
										  	$realizD",
										  	"<div style='text-align:right; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}
								
			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F",
										"G",
										"H");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function up0b28t18JREVISION() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$MenuCode 			= 'MN058';
			$data["MenuCode"] 	= 'MN058';
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			// $getGEJ 				= $this->m_cho_payment->get_VCASH_by_number($JournalH_Code)->row();
			$getGEJ 				= $this->m_cho_payment->get_REVISION_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PlanRDate'] 		= $getGEJ->PlanRDate;
			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['PPNH_Amount'] 	= $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] 	= $getGEJ->PPHH_Amount;
			$data['GJournal_Total'] = $getGEJ->GJournal_Total;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['Reference_Number'] 	= $getGEJ->Reference_Number;
			$data['REF_NUM'] 		= $getGEJ->REF_NUM;
			$data['REF_CODE'] 		= $getGEJ->REF_CODE;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;

			$data["ISPERSL"] 	= 0;
			$mnCode				= 'MN058';
			$MenuCode			= 'MN058';
			$data["MenuCode"] 	= 'MN058';
			$data["MenuApp"] 	= 'MN058';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']= site_url('c_finance/c_cho70d18/update_process');
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			//$data['form_action']= site_url('c_finance/c_cho70d18/update_process');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			$data['vwDocPatt'] 	= $this->m_cho_payment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				//$MenuCode 	= 'MN359';
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

			if($jrnType == 'VCASH')
				$this->load->view('v_finance/v_cho_payment/v_cho_vcash_form', $data);
			else if($jrnType == 'JREVISION')
				$this->load->view('v_finance/v_cho_payment/v_cho_jrnrevision_form', $data);
			else
				$this->load->view('v_finance/v_cho_payment/v_cho_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataJRN() // GOOD
	{
		$PRJCODE	= $_GET['id'];

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
			$num_rows 		= $this->m_cho_payment->get_AllDataJRNC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataJRNL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$INV_NOTES 			= wordwrap($dataI['INV_NOTES'], 50, "<br>", TRUE);
				$INV_NOTES2			= $dataI['INV_NOTES2'];
				$SPLCODE			= $dataI['SPLCODE'];
				$SPLDESC			= $dataI['SPLDESC'];

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
				$s_00 		= "SELECT SPLDESC, SPLSTAT FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
				$r_00 		= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00):
					$SPLDESC 	= $rw_00->SPLDESC;
					$SPLSTAT 	= $rw_00->SPLSTAT;
				endforeach;

				if($INV_NOTES == '')
				{
					$INV_NOTES	= "-";
				}

				$this->db->select_sum("A.CBD_AMOUNT", "INV_AMOUNT_PAID");
				$this->db->from("tbl_bp_detail A");
				$this->db->join("tbl_bp_header B", "B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE");
				$this->db->where(["A.CBD_DOCNO" => $INV_NUM, "A.PRJCODE" => $PRJCODE, "B.CB_PAYFOR" => $SPLCODE]);
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

				/*if($INV_REMAMN <= 0)
				{
					$chkBox			= "<input type='radio' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_DUEDATE."|".$SPLDESC."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_TOT."' onClick='pickThis1(this);' disabled/>";
				}
				else
				{*/
					$chkBox			= "<input type='radio' name='chk1' value='".$INV_NUM."|".$INV_CODE."|".$PRJCODE."|".$INV_DUEDATE."|".$SPLDESC."|".$INV_AMOUNT."|".$INV_AMOUNT_PPN."|".$INV_AMOUNT_PPH."|".$INV_AMOUNT_TOT."' onClick='pickThis1(this);'/>";
				//}

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
										  	"<div style='white-space:nowrap'>
										  		<strong><i class='fa fa-user margin-r-5'></i>".$SPLCODE."</strong>
											  	<div style='margin-left: 15px;'>
											  		".$SPLDESC."
											  	</div>
										  	</div>",
											number_format($INV_AMOUNT, 2));
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

  	function get_AllDataJRNDET() // GOOD
	{
		$PRJCODE	= $_GET['id'];
		$refNum 	= $_GET['refNum'];

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
			$num_rows 		= $this->m_cho_payment->get_AllDataJRNREVISIONDC($PRJCODE, $refNum, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_payment->get_AllDataJRNREVISIONDL($PRJCODE, $refNum, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalD_Id 		= $dataI['JournalD_Id'];
				$JournalH_Code 		= $dataI['JournalH_Code'];
				$Acc_Id 			= $dataI['Acc_Id'];
				$JOBCODEID 			= $dataI['JOBCODEID'];
				$JournalD_Debet 	= $dataI['JournalD_Debet'];
				$JournalD_Debet_tax = $dataI['JournalD_Debet_tax'];
				$JournalD_Kredit 	= $dataI['JournalD_Kredit'];
				$JournalD_Kredit_tax= $dataI['JournalD_Kredit_tax'];
				$isDirect 			= $dataI['isDirect'];
				$Notes 				= $dataI['Notes'];
				$ITM_CODE 			= $dataI['ITM_CODE'];
				$ITM_CATEG 			= $dataI['ITM_CATEG'];
				$ITM_VOLM 			= $dataI['ITM_VOLM'];
				$ITM_PRICE 			= $dataI['ITM_PRICE'];
				$PPN_Code 			= $dataI['PPN_Code'];
				$PPN_Perc 			= $dataI['PPN_Perc'];
				$PPN_Amount 		= $dataI['PPN_Amount'];
				$PPH_Code 			= $dataI['PPH_Code'];
				$PPH_Perc 			= $dataI['PPH_Perc'];
				$PPH_Amount 		= $dataI['PPH_Amount'];
				$isVoid 			= $dataI['isVoid'];
				$isLock 			= $dataI['isLock'];

				$JODBDESC 			= "";
				$sqlJOBD1			= "SELECT ITM_PRICE, JOBDESC, JOBPARENT
										FROM tbl_joblist_detail
										WHERE JOBCODEID = '$JOBCODEID'
											AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJOBD1			= $this->db->query($sqlJOBD1)->result();
				foreach($resJOBD1 as $rowJOBD1) :
					//$ITM_PRICE		= $rowJOBD1->ITM_PRICE;
					$JODBDESC		= $rowJOBD1->JOBDESC;
					$JOBPARENT		= $rowJOBD1->JOBPARENT;
				endforeach;

				if($JODBDESC == '')
				{
					$JODBDESC 		= "";
					$JOBPARENT 		= "";
				}

				$ITM_UNIT 			= $dataI['ITM_UNIT'];
				$Ref_Number 		= $dataI['Ref_Number'];
				$Other_Desc 		= $dataI['Other_Desc'];
				$Journal_DK 		= $dataI['Journal_DK'];
				$isTax 				= $dataI['isTax'];
				
				$ITM_VOLMBG			= 0;
				$ITM_BUDG			= 0;
				$ITM_USED			= 0;
				$ITM_USED_AM		= 0;
				$sqlJOBD			= "SELECT ITM_VOLM AS ITM_VOLMBG, ITM_BUDG,
											ITM_USED, ITM_USED_AM
										FROM tbl_joblist_detail
										WHERE JOBCODEID = '$JOBCODEID'
											AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJOBD			= $this->db->query($sqlJOBD)->result();
				foreach($resJOBD as $rowJOBD) :
					$ITM_VOLMBG		= $rowJOBD->ITM_VOLMBG;
					$ITM_BUDG		= $rowJOBD->ITM_BUDG;
					$ITM_USED		= $rowJOBD->ITM_USED;
					$ITM_USED_AM	= $rowJOBD->ITM_USED_AM;
				endforeach;
				$BUDG_REMVOLM		= $ITM_VOLMBG - $ITM_USED;
				$BUDG_REMAMN		= $ITM_BUDG - $ITM_USED_AM;
				
				if($Journal_DK == 'D')
				{
					$AmountV		= $JournalD_Debet;
				}
				else
				{
					$AmountV		= $JournalD_Kredit;
				}
					
				if($isTax == 1)
				{
					if($Journal_DK == 'D')
					{
						$AmountV		= $JournalD_Debet_tax;
					}
					else
					{
						$AmountV		= $JournalD_Kredit_tax;
					}
					$isTaxD			= 'Tax';
				}
				else
				{
					$isTaxD			= 'No';
				}
				
				$ITM_NAME			= '';
				$ACC_NAME 			= "";
				if($ITM_CODE != '')
				{
					$sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$resITM 	= $this->db->query($sqlITM)->result();
					foreach($resITM as $rowITM) :
						$ITM_NAME 	= $rowITM->ITM_NAME;
					endforeach;

					$sqlACC		= "SELECT Account_NameId FROM tbl_chartaccount WHERE Account_Number = '$Acc_Id' AND PRJCODE = '$PRJCODE'";
					$resACC 	= $this->db->query($sqlACC)->result();
					foreach($resACC as $rowACC) :
						$ACC_NAME 	= $rowACC->Account_NameId;
					endforeach;
				}
				else
				{
					$sqlITM		= "SELECT Account_NameId FROM tbl_chartaccount WHERE Account_Number = '$Acc_Id' AND PRJCODE = '$PRJCODE'";
					$resITM 	= $this->db->query($sqlITM)->result();
					foreach($resITM as $rowITM) :
						$ITM_NAME 	= $rowITM->Account_NameId;
					endforeach;
				}
				
				// RESERVE
				$ITM_USEDR			= 0;
				$ITM_USEDR_AM		= 0;

				$sqlJOBDR			= "SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
										FROM tbl_journaldetail
										WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE'
											AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT IN (1,2,7)
											AND JournalD_Id != $JournalD_Id";
				$resJOBDR			= $this->db->query($sqlJOBDR)->result();
				foreach($resJOBDR as $rowJOBDR) :
					$ITM_USEDR		= $rowJOBDR->TOTVOL;
					$ITM_USEDR_AM	= $rowJOBDR->TOTAMN;
				endforeach;
				
				$BUDG_REMVOLM	= $BUDG_REMVOLM - $ITM_USEDR;
				$BUDG_REMAMNT	= $BUDG_REMAMN - $ITM_USEDR_AM;

				$JobView		= "$ITM_CODE - $JODBDESC";
				$JobView 		= wordwrap($JobView, 50, "<br>", TRUE);

				$JOBDESCH		= "";
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				foreach($resJOBDESC as $rowJOBDESC) :
					$JOBDESCH	= $rowJOBDESC->JOBDESC;
				endforeach;

				$JOBDESCH1 		= wordwrap("$Acc_Id : $ACC_NAME", 50, "<br>", TRUE);
				$JOBDESCH 		= '<div style="font-weight: bold">
								  		<i class="text-muted fa fa-book"></i>&nbsp;&nbsp;'.$JOBDESCH1.'  
								  	</div>';

				
				$JOBDESCH1 		= $JOBDESCH;
				$disButton 		= 0;
				if($JOBPARENT == '' && $Journal_DK == 'D')
				{
					$disButton 		= 1;
					$JOBDESCH1 		= "Kode komponen ini belum terkunci atau sedang dibuka dalam daftar RAP. Silahkan hubungi pihak yang memiliki otorisasi mengunci RAP.";
					$JOBDESCH2 		= wordwrap("$JOBDESCH1", 50, "<br>", TRUE);
					$JOBDESCH 		= '<span class="label label-danger" style="font-size:12px;">'.$JOBDESCH2.'</span>';
				}

				if($Journal_DK == 'K')
				{
					$JobView		= "$Acc_Id - $ITM_NAME";
					$JobView 		= wordwrap($JobView, 50, "<br>", TRUE);

					$JOBDESCH1 		= wordwrap("$JobView", 50, "<br>", TRUE);
					$JOBDESCH 		= '';
				}

				$secDel 		= base_url().'index.php/c_finance/c_cho70d18/revTrx/?id=';
				$delID 			= "$secDel~$JournalD_Id~$JournalH_Code~$JOBCODEID~$ITM_CODE~$ITM_NAME~$PRJCODE";

				$secAction 		= "<a href='javascript:void(null);' class='btn bg-green btn-xs'><i class='fa fa-lock'></i></a>";

				$ACC_NAMEV 		= addslashes($ACC_NAME);
				$urlsvAcc 		= site_url('c_finance/c_cho70d18/upItmAcc/?id=');
				$svItmAcc 		= "$urlsvAcc~$PRJCODE~$ITM_CODE~$ITM_NAME~$ACC_NAMEV~$Acc_Id~$Other_Desc~$JournalD_Id";

				if($isLock == 0)
				{
					$secAction	= "<input type='hidden' name='urlRev".$noU."' id='urlRev".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
										<a href='javascript:void(null);' class='btn bg-red btn-xs' onClick='setAccUM(\"".$svItmAcc."\");' title='Hapus'>
											<i class='fa fa-exchange'></i>
										</a>
									</label>";
				}

				$output['data'][] 	= array($secAction,
										  	"<div style='white-space:nowrap'>".$JOBDESCH.$JobView."</div>",
										  	"$ITM_UNIT
										  	<input type='hidden' id='ITMDESC".$noU."' value='($ITM_CODE) : $ITM_NAME'>
										  	<input type='hidden' id='ISDIS".$noU."' value='".$disButton."'>
										  	<input type='hidden' id='totalrow' name='totalrow' value='".$noU."'>",
											number_format($ITM_VOLM, 2),
											number_format($ITM_PRICE, 2),
											number_format($AmountV, 2),
											number_format($BUDG_REMAMNT, 2),
											number_format($PPN_Amount, 2),
											number_format($PPH_Amount, 2),
											$Ref_Number,
											$Other_Desc);
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A = $PRJCODE",
									  "B = $refNum",
									  "C",
									  "D",
									  "E",
									  "F",
									  "G",
									  "H",
									  "I",
									  "J",
									  "K");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function upItmAcc()
	{
		date_default_timezone_set("Asia/Jakarta");
		$this->db->trans_begin();

		$PRJCODE 	= $_POST['PRJCODE'];
		$ITM_CODE 	= $_POST['ITM_CODE'];
		$ACC_ID		= $_POST['ACC_ID'];
		$ACC_ID_UM	= $_POST['ACC_ID_UM'];
		$PROC_STAT 	= $_POST['PROC_STAT'];
		$JRN_NUM 	= $_POST['JRNNUM'];
		$JRNDESC 	= $_POST['JRNDESC'];
		$JRN_ID 	= $_POST['JRN_ID'];
		$JRN_CODE 	= $_POST['JRN_CODE'];
		$Created 	= date('Y-m-d H:i:s');

		$REFCODE 	= "";
		$sqlJRNA	= "SELECT DISTINCT Manual_No, JournalType FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
		$resJRNA	= $this->db->query($sqlJRNA)->result();
		foreach($resJRNA as $rowJRNA) :
			$REFCODE 	= $rowJRNA->Manual_No;
			$jrnTyp 	= $rowJRNA->JournalType;
		endforeach;

		// START : ADD TO HEADER
			$s_00 	= "INSERT INTO tbl_journalheader_revision (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Emp_ID, Created,
							LastUpdate, KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
							Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, Manual_No, GEJ_STAT, GEJ_STAT_PD, 
							GEJ_STAT_VCASH, STATDESC, STATCOL, Reference_Number, REF_NUM, REF_CODE)
						SELECT '$JRN_NUM', 'JREVISION', JournalH_Desc, '$Created', Company_ID, Emp_ID, '$Created',
							'$Created', KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
							Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, '$JRN_CODE', GEJ_STAT, GEJ_STAT_PD, 
							GEJ_STAT_VCASH, STATDESC, STATCOL, '$JRN_NUM', '$JRN_NUM', '$REFCODE' FROM tbl_journalheader
						WHERE JournalH_Code = '$JRN_NUM'";
			$this->db->query($s_00);
		// END : ADD TO HEADER

		if($PROC_STAT == 1)		// hanya detil akun terpilih (1 row)
		{
			if($jrnTyp == 'VCASH')
			{
				$noA 		= 0;
				$sqlJRNA	= "SELECT JournalD_Id FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
				$resJRNA	= $this->db->query($sqlJRNA)->result();
				foreach($resJRNA as $rowJRNA) :
					$noA 	= $noA+1;
					$JRDID 	= $rowJRNA->JournalD_Id;
					$sUPDA 	= "UPDATE tbl_journaldetail SET PattNum = $noA WHERE JournalD_Id = $JRDID";
					$this->db->query($sUPDA);
				endforeach;

				$noB 		= 0;
				$sqlJRNB	= "SELECT JournalD_Id FROM tbl_journaldetail_vcash WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
				$resJRNB	= $this->db->query($sqlJRNB)->result();
				foreach($resJRNB as $rowJRNB) :
					$noB 	= $noB+1;
					$JRDID 	= $rowJRNB->JournalD_Id;
					$sUPDB 	= "UPDATE tbl_journaldetail_vcash SET PattNum = $noB WHERE JournalD_Id = $JRDID";
					$this->db->query($sUPDB);
				endforeach;
			}
			elseif($jrnTyp == 'CPRJ')
			{
				$noA 		= 0;
				$sqlJRNA	= "SELECT JournalD_Id FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
				$resJRNA	= $this->db->query($sqlJRNA)->result();
				foreach($resJRNA as $rowJRNA) :
					$noA 	= $noA+1;
					$JRDID 	= $rowJRNA->JournalD_Id;
					$sUPDA 	= "UPDATE tbl_journaldetail SET PattNum = $noA WHERE JournalD_Id = $JRDID";
					$this->db->query($sUPDA);
				endforeach;

				$noB 		= 0;
				$sqlJRNB	= "SELECT JournalD_Id FROM tbl_journaldetail_cprj WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
				$resJRNB	= $this->db->query($sqlJRNB)->result();
				foreach($resJRNB as $rowJRNB) :
					$noB 	= $noB+1;
					$JRDID 	= $rowJRNB->JournalD_Id;
					$sUPDB 	= "UPDATE tbl_journaldetail_cprj SET PattNum = $noB WHERE JournalD_Id = $JRDID";
					$this->db->query($sUPDB);
				endforeach;
			}
			elseif($jrnTyp == 'CHO-PD')
			{
				$noA 		= 0;
				$sqlJRNA	= "SELECT JournalD_Id FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
				$resJRNA	= $this->db->query($sqlJRNA)->result();
				foreach($resJRNA as $rowJRNA) :
					$noA 	= $noA+1;
					$JRDID 	= $rowJRNA->JournalD_Id;
					$sUPDA 	= "UPDATE tbl_journaldetail SET PattNum = $noA WHERE JournalD_Id = $JRDID";
					$this->db->query($sUPDA);
				endforeach;

				$noB 		= 0;
				$sqlJRNB	= "SELECT JournalD_Id FROM tbl_journaldetail_pd WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
				$resJRNB	= $this->db->query($sqlJRNB)->result();
				foreach($resJRNB as $rowJRNB) :
					$noB 	= $noB+1;
					$JRDID 	= $rowJRNB->JournalD_Id;
					$sUPDB 	= "UPDATE tbl_journaldetail_pd SET PattNum = $noA WHERE JournalD_Id = $JRDID";
					$this->db->query($sUPDB);
				endforeach;
			}

			$sqlJRN		= "SELECT JournalType, Base_Debet, JournalH_Date, PattNum FROM tbl_journaldetail
							WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND JournalD_Id = $JRN_ID";
			$resJRN		= $this->db->query($sqlJRN)->result();
			foreach($resJRN as $rowJRN) :
				$PattNo = $rowJRN->PattNum;
				$jrnTyp = $rowJRN->JournalType;
				$jrnDeb = $rowJRN->Base_Debet;
				$jrnTgl = $rowJRN->JournalH_Date;
				$accYr	= date('Y', strtotime($jrnTgl));

				$AccName 		= "";
				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT Account_NameId, isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_UM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$AccName	= $rowISHO->Account_NameId;
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);

				$s_01 		= "INSERT INTO tbl_journaldetail_revision (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
									COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
								SELECT '$JRN_NUM', '$Created', 'JREVISION', '$ACC_ID_UM', '$ACC_ID', '$AccName', proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
									COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, '$JRN_CODE', '$JRNDESC', Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
									FROM tbl_journaldetail
								WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND JournalD_Id = $JRN_ID";
				$this->db->query($s_01);

				// START : MENGURANGI NILAI AKUN
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID' LIMIT 1";
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
							$sqlUpdCOA1	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$jrnDeb,
												Base_Debet2 = Base_Debet2-$jrnDeb, BaseD_$accYr = BaseD_$accYr-$jrnDeb
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID'";
							$this->db->query($sqlUpdCOA1);
						}
					}
				// END : MENGURANGI NILAI AKUN

				// START : MENAMBAHKAN NILAI AKUN
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA2	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$jrnDeb,
												Base_Debet2 = Base_Debet2+$jrnDeb, BaseD_$accYr = BaseD_$accYr+$jrnDeb
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_UM'";
							$this->db->query($sqlUpdCOA2);
						}
					}
				// END : MENAMBAHKAN NILAI AKUN

				$updJRN		= "UPDATE tbl_journalheader SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
								AND proj_Code = '$PRJCODE'";
				$this->db->query($updJRN);

				$updJRND	= "UPDATE tbl_journaldetail SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
									oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
								WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
									AND isLock = 0 AND PattNum = $PattNo";
				$this->db->query($updJRND);

				if($jrnTyp == 'IR')
				{
					// VCASH
				}
				elseif($jrnTyp == 'OPN')
				{
					//
				}
				elseif($jrnTyp == 'VOC')
				{
					//
				}
				elseif($jrnTyp == 'VCASH')
				{
					$updJRNH	= "UPDATE tbl_journalheader_vcash SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
									AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_vcash SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
										oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
									WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
										AND isLock = 0 AND PattNum = $PattNo";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'CPRJ')
				{
					$updJRNH	= "UPDATE tbl_journalheader_cprj SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
									AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_cprj SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
										oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
									WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
										AND isLock = 0 AND PattNum = $PattNo";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'BP')
				{
					$updJRNH	= "UPDATE tbl_bp_header SET CB_MEMO = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$JRN_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($updJRNH);
				}
				elseif($jrnTyp == 'BR')
				{
					$updJRNH	= "UPDATE tbl_br_header SET BR_MEMO = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$JRN_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($updJRNH);
				}
				elseif($jrnTyp == 'PINBUK')
				{
					$updJRNH	= "UPDATE tbl_journalheader_pb SET JournalH_Desc2 = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$JRN_NUM'
									AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_pb SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName'
									WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE' AND isLock = 0";
					//$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'CHO-PD')
				{
					$updJRNH	= "UPDATE tbl_journalheader_pd SET JournalH_Desc2 = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$JRN_NUM'
									AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_pd SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName'
									WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
										AND isLock = 0 AND PattNum = $PattNo";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'JRNREV')
				{
					//
				}
				elseif($jrnTyp == 'PINBUK-V')
				{
					//
				}
			endforeach;
		}
		elseif($PROC_STAT == 2)		// hanya dokumen jurnal terpilih
		{
			$updJRN		= "UPDATE tbl_journalheader SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
							AND proj_Code = '$PRJCODE'";
			$this->db->query($updJRN);

			$sqlJRN		= "SELECT JournalH_Code, JournalD_Id, JournalType, Base_Debet, JournalH_Date FROM tbl_journaldetail
							WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND isLock = 0";
			$resJRN		= $this->db->query($sqlJRN)->result();
			foreach($resJRN as $rowJRN) :
				$jrnNUM = $rowJRN->JournalH_Code;
				$jrnIDD = $rowJRN->JournalD_Id;
				$jrnTyp = $rowJRN->JournalType;
				$jrnDeb = $rowJRN->Base_Debet;
				$jrnTgl = $rowJRN->JournalH_Date;
				$accYr	= date('Y', strtotime($jrnTgl));

				// START : MENGURANGI NILAI AKUN
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID' LIMIT 1";
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
							$sqlUpdCOA1	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$jrnDeb,
												Base_Debet2 = Base_Debet2-$jrnDeb, BaseD_$accYr = BaseD_$accYr-$jrnDeb
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID'";
							$this->db->query($sqlUpdCOA1);
						}
					}
				// END : MENGURANGI NILAI AKUN

				// START : MENAMBAHKAN NILAI AKUN
					$AccName 		= "";
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT Account_NameId, isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_UM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$AccName	= $rowISHO->Account_NameId;
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;

					$s_01 	= "INSERT INTO tbl_journaldetail_revision (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
									COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
								SELECT JournalH_Code, '$Created', 'JREVISION', '$ACC_ID_UM', '$ACC_ID', '$AccName', proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
									COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, '$JRN_CODE', '$JRNDESC', Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
									FROM tbl_journaldetail
								WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND JournalD_Id = $jrnIDD";
					$this->db->query($s_01);

					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA2	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$jrnDeb,
												Base_Debet2 = Base_Debet2+$jrnDeb, BaseD_$accYr = BaseD_$accYr+$jrnDeb
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_UM'";
							$this->db->query($sqlUpdCOA2);
						}
					}
				// END : MENAMBAHKAN NILAI AKUN

				$updJRN		= "UPDATE tbl_journalheader SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
								AND proj_Code = '$PRJCODE'";
				//$this->db->query($updJRN);

				$updJRND	= "UPDATE tbl_journaldetail SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
									oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
								WHERE JournalH_Code = '$jrnNUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
									AND isLock = 0";
				$this->db->query($updJRND);

				if($jrnTyp == 'IR')
				{
					// VCASH
				}
				elseif($jrnTyp == 'OPN')
				{
					//
				}
				elseif($jrnTyp == 'VOC')
				{
					//
				}
				elseif($jrnTyp == 'VCASH')
				{
					$updJRNH	= "UPDATE tbl_journalheader_vcash SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
									AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_vcash SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
										oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
									WHERE JournalH_Code = '$jrnNUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
										AND isLock = 0";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'CPRJ')
				{
					$updJRNH	= "UPDATE tbl_journalheader_cprj SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
									AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_cprj SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
										oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
									WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
										AND isLock = 0 AND PattNum = $PattNo";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'BP')
				{
					$updJRNH	= "UPDATE tbl_bp_header SET CB_MEMO = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$jrnNUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($updJRNH);
				}
				elseif($jrnTyp == 'BR')
				{
					$updJRNH	= "UPDATE tbl_br_header SET BR_MEMO = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$jrnNUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($updJRNH);
				}
				elseif($jrnTyp == 'PINBUK')
				{
					$updJRNH	= "UPDATE tbl_journalheader_pb SET JournalH_Desc2 = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM'
									WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_pb SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName'
									WHERE JournalH_Code = '$jrnNUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE' AND isLock = 0";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'CHO-PD')
				{
					$updJRNH	= "UPDATE tbl_journalheader_pd SET JournalH_Desc2 = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM'
									WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_pd SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName'
									WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
										AND isLock = 0";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'JRNREV')
				{
					//
				}
				elseif($jrnTyp == 'PINBUK-V')
				{
					//
				}
			endforeach;
		}
		elseif($PROC_STAT == 3)		// semua dokumen jurnal
		{
			$sqlJRN		= "SELECT JournalH_Code, JournalD_Id, JournalType, Base_Debet, JournalH_Date FROM tbl_journaldetail
							WHERE proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND GEJ_STAT = 3 AND isLock = 0";
			$resJRN		= $this->db->query($sqlJRN)->result();
			foreach($resJRN as $rowJRN) :
				$jrnNUM = $rowJRN->JournalH_Code;
				$jrnIDD = $rowJRN->JournalD_Id;
				$jrnTyp = $rowJRN->JournalType;
				$jrnDeb = $rowJRN->Base_Debet;
				$jrnTgl = $rowJRN->JournalH_Date;
				$accYr	= date('Y', strtotime($jrnTgl));

				// START : MENGURANGI NILAI AKUN
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID' LIMIT 1";
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
							$sqlUpdCOA1	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$jrnDeb,
												Base_Debet2 = Base_Debet2-$jrnDeb, BaseD_$accYr = BaseD_$accYr-$jrnDeb
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID'";
							$this->db->query($sqlUpdCOA1);
						}
					}
				// END : MENGURANGI NILAI AKUN

				// START : MENAMBAHKAN NILAI AKUN
					$AccName 		= "";
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT Account_NameId, isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_UM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$AccName	= $rowISHO->Account_NameId;
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;

					$s_01 	= "INSERT INTO tbl_journaldetail_revision (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
									COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
								SELECT JournalH_Code, '$Created', 'JREVISION', '$ACC_ID_UM', '$ACC_ID', '$AccName', proj_Code,
									proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
									COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
									ITM_UNIT, '$JRN_CODE', '$JRNDESC', Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
									FROM tbl_journaldetail
								WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND JournalD_Id = $jrnIDD";
					$this->db->query($s_01);

					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA2	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$jrnDeb,
												Base_Debet2 = Base_Debet2+$jrnDeb, BaseD_$accYr = BaseD_$accYr+$jrnDeb
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_UM'";
							$this->db->query($sqlUpdCOA2);
						}
					}
				// END : MENAMBAHKAN NILAI AKUN

				$updJRN		= "UPDATE tbl_journalheader SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
								AND proj_Code = '$PRJCODE'";
				//$this->db->query($updJRN);

				$updJRND	= "UPDATE tbl_journaldetail SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
									oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
								WHERE JournalH_Code = '$jrnNUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
									AND isLock = 0";
				$this->db->query($updJRND);

				if($jrnTyp == 'IR')
				{
					// VCASH
				}
				elseif($jrnTyp == 'OPN')
				{
					//
				}
				elseif($jrnTyp == 'VOC')
				{
					//
				}
				elseif($jrnTyp == 'VCASH')
				{
					$updJRNH	= "UPDATE tbl_journalheader_vcash SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
									AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_vcash SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
										oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
									WHERE JournalH_Code = '$jrnNUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
										AND isLock = 0";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'CPRJ')
				{
					$updJRNH	= "UPDATE tbl_journalheader_cprj SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
									AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_cprj SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
										oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
									WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
										AND isLock = 0 AND PattNum = $PattNo";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'BP')
				{
					$updJRNH	= "UPDATE tbl_bp_header SET CB_MEMO = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$jrnNUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($updJRNH);
				}
				elseif($jrnTyp == 'BR')
				{
					$updJRNH	= "UPDATE tbl_br_header SET BR_MEMO = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$jrnNUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($updJRNH);
				}
				elseif($jrnTyp == 'PINBUK')
				{
					$updJRNH	= "UPDATE tbl_journalheader_pb SET JournalH_Desc2 = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM'
									WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_pb SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName'
									WHERE JournalH_Code = '$jrnNUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE' AND isLock = 0";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'CHO-PD')
				{
					$updJRNH	= "UPDATE tbl_journalheader_pd SET JournalH_Desc2 = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM'
									WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRNH);

					$updJRND	= "UPDATE tbl_journaldetail_pd SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName'
									WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
										AND isLock = 0";
					$this->db->query($updJRND);
				}
				elseif($jrnTyp == 'JRNREV')
				{
					//
				}
				elseif($jrnTyp == 'PINBUK-V')
				{
					//
				}
			endforeach;
		}

		$sqlUpd		= "UPDATE tbl_item SET ACC_ID_UM = '$ACC_ID_UM' WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		//$this->db->query($sqlUpd);

		// START : UPDATE STATUS
			$completeName 	= $this->session->userdata['completeName'];
			$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
									'DOC_CODE' 		=> $JRN_CODE,
									'DOC_STAT' 		=> 3,
									'PRJCODE' 		=> $PRJCODE,
									'CREATERNM'		=> $completeName,
									'TBLNAME'		=> "tbl_journalheader_revision");
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

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1 	= "Akun item sudah diubah";
		}
		else
		{
			$alert1 	= "Item account has been changed";
		}

		echo $alert1;
	}

	function getTVCASH()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collData'];
		$colExpl	= explode("~", $collID);
		$PRJCODE	= $colExpl[0];
        $DATE_S 	= $colExpl[1];
        $DATE_E 	= $colExpl[2];

        $DATE_S		= date('Y-m-d',strtotime(str_replace('/', '-', $DATE_S)));
        $DATE_E		= date('Y-m-d',strtotime(str_replace('/', '-', $DATE_E)));

        $TOT_USE 	= 0;
		$s_00 		= "tbl_journaldetail WHERE JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
						AND (JournalH_Date >= '$DATE_S' AND JournalH_Date <= '$DATE_E') AND Journal_DK = 'D'";
		$r_00		= $this->db->count_all($s_00);
		if($r_00 > 0)
		{
			$s_01 	= "SELECT SUM(Base_Debet) AS TOT_USE FROM tbl_journaldetail WHERE JournalType = 'CPRJ' AND proj_Code = '$PRJCODE'
						AND (JournalH_Date >= '$DATE_S' AND JournalH_Date <= '$DATE_E') AND Journal_DK = 'D'";
			$r_01 	= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$TOT_USE 	= $rw_01->TOT_USE;
			endforeach;
		}

		echo "$TOT_USE";
	}
}