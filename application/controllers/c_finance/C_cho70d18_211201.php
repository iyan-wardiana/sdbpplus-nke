<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 28 April 2018
	* File Name	= C_cho70d18.php
	* Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_cho70d18 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
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
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
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
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
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
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$jrnType 		= "CPRJ";
		
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
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
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
			}

			$this->load->view('v_finance/v_cho_payment/v_cho_payment', $data);
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
				
				$JournalH_Desc		= addslashes($dataI['JournalH_Desc']);
				$JournalType		= $dataI['JournalType'];
				$Journal_Amount		= number_format($dataI['Journal_Amount'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$acc_number			= $dataI['acc_number'];

				if($ISPERSL == 1)
				{
					$ISPERSLD 		= "Pinjaman Dinas (PD)";
					$EMP_NAME 		= "";
					$s_emp			= "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'";
					$r_emp 			= $this->db->query($s_emp)->result();
					foreach($r_emp as $rw_emp) :
						$EMP_NAME	= $rw_emp->EMP_NAME;
					endforeach;
				}
				else
				{
					$ISPERSLD 		= "Petty Cash";
					$EMP_NAME 		= "";
				}

				$Acc_Name 			= "-";
				$sqlISHO 			= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$Acc_Name		= $rowISHO->Acc_Name;
				endforeach;

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho70d18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secPrint1			= site_url('c_finance/c_cho70d18/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$secVoid 			= base_url().'index.php/__l1y/trashCHO/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				if($GEJ_STAT == 1 || $GEJ_STAT == 4) 
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
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
				elseif($GEJ_STAT == 3)
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
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
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
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
				
				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>$Manual_No</div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-gg margin-r-5'></i> ".$ISPERSLD." </strong></div>",
										  	$JournalH_DateV,
										  	"<i class='fa fa-commenting margin-r-5'></i> ".$JournalH_Desc."
									  		<div style='margin-left: 20px; font-style: italic;'>
										  		<div><strong>$Acc_Name</strong></div>
										  		<div><strong><i class='fa fa-user margin-r-5'></i> $EMP_NAME ($PERSL_EMPID)</strong></div>
										  	</div>",
										  	"<div style='white-space:nowrap'><strong><i class='fa fa-money margin-r-5'></i> ".$Journal_Amount."</strong></div>
										  	<div style='white-space:nowrap'><strong><i class='fa fa-check-square-o margin-r-5'></i> ".$RealizationValue."</strong>
										  	</div>
										  	<div style='margin-left: 20px;'>
										  		0.00
										  	</div>",
										  	"<div style='text-align:right; white-space:nowrap'>$empName</div>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function adda70d18() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
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
		}
		else
		{
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
			$data['form_action']= site_url('c_finance/c_cho70d18/add_process');
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

			$this->load->view('v_finance/v_cho_payment/v_cho_payment_form', $data);
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
			$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
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
	
	function add_process() // OK
	{
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
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
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				//$MenuCode 		= 'MN359';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
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
				$AH_NOTES		= addslashes($this->input->post('JournalH_Desc'));
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
										'JournalH_Desc' 	=> $AH_NOTES,
										'Manual_No' 		=> $Manual_No,
										'JournalType' 		=> $JournalType,
										'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
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
										'acc_number'		=> $acc_number);
				$this->m_cho_payment->add($projGEJH);
			// END - HEADER
			
			// START : DETAIL JOURNAL
				// START : DEBET
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
						$Ref_Number			= $d['Ref_Number'];
						$Other_Desc			= $d['Other_Desc'];
						$Journal_DK			= $JournalD_Pos;
						$Journal_Type		= $Journal_Type;
						$isTax				= $isTax;

						// Insert into tbl_journal_detail (D) for All Expenses
						$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID, Currency_id, JournalD_Debet, 
										JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
										Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
										curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_UNIT, ITM_PRICE, Ref_Number, Other_Desc,
										Journal_DK, Journal_Type, isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name) VALUE
										('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID', 'IDR', $JournalD_Debet, 
										$JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, 
										$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
										'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$Ref_Number', '$Other_Desc', 'D', 
										'$Journal_Type', $isTax, $GEJ_STAT, '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name')";
						$this->db->query($insSQL);
					}
				
					$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
				// END : DEBET
			
				$Acc_Name 		= "-";
				$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resACCNm		= $this->db->query($sqlACCNm)->result();
				foreach($resACCNm as $rowACCNm):
					$Acc_Name	= $rowACCNm->Acc_Name;
				endforeach;

				// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
					$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
										JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name) VALUE
										('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
										$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$Ref_Number', 
										'$Other_Desc', 'K', '$Journal_Type', $isTax, $GEJ_STAT, '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name')";
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
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 							= $this->m_cho_payment->get_CHO_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
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

			if($jrnType == 'CHO')
			{
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
			}
			else
			{
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
			}
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_cho70d18/update_process');
			$data['backURL'] 	= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_payment->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_payment->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
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
			
			$this->load->view('v_finance/v_cho_payment/v_cho_payment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
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
			$JournalH_Desc 	= addslashes($this->input->post('JournalH_Desc'));
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('JournalH_Desc2'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			
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
									'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> $JournalType,
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'ISPERSL'			=> $ISPERSL,
									'PERSL_EMPID'		=> $PERSL_EMPID,
									'acc_number'		=> $acc_number);									
				$this->m_cho_payment->updateCHO($JournalH_Code, $projGEJH);
			// END : UPDATE HEADER

			// START : UPDATE STATUS
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
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY

				// START : RESET DETAIL
					$this->m_cho_payment->deleteCPRJDetail($JournalH_Code);

				// START : DEBET
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
																	ITM_USED 	= $ITM_USED+$ITM_VOLM, 
																	ITM_USED_AM = $ITM_USEDAM+$Base_Debet
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
																	ITM_OUT 	= $ITM_OUT+$ITM_VOLM,
																	UM_VOLM 	= $UM_VOLM+$ITM_VOLM,
																	UM_AMOUNT 	= $UM_AMOUNT+$Base_Debet
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
						$Ref_Number				= $d['Ref_Number'];
						$Other_Desc				= $d['Other_Desc']."- ".$JournalH_Desc;
						$Journal_DK				= $JournalD_Pos;
						$Journal_Type			= $Journal_Type;
						$isTax					= $isTax;
						
						// Insert into tbl_journal_detail (D) for All Expenses
						$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet, 
										JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
										Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
										curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE, ITM_UNIT, Ref_Number, Other_Desc,
										Journal_DK, Journal_Type, isTax, JOBCODEID, Acc_Name)
										VALUE
										('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
										$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit,
										$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
										'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_PRICE', '$ITM_UNIT', '$Ref_Number', '$Other_Desc', 'D', 
										'$Journal_Type', $isTax, '$JOBCODEID', '$Acc_Name')";
						$this->db->query($insSQL);
					}
					
					$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
				// END : DEBET
			
				$Acc_Name 		= "-";
				$sqlACCNm 		= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resACCNm		= $this->db->query($sqlACCNm)->result();
				foreach($resACCNm as $rowACCNm):
					$Acc_Name	= $rowACCNm->Acc_Name;
				endforeach;

				// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
					$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
										JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
										curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, Acc_Name) VALUE
										('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
										$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$ITM_CODE', 
										'$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'K', '$Journal_Type', $isTax, '$Acc_Name')";
					$this->db->query($insSQLK);
				// END : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
				
				// UPDATE AMOUNT HEADER
					$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
				
				if($AH_ISLAST == 1)
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
			}
			else 		// IF NEW CONFIRMED
			{
				// UPDATE STATUS HEADER
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);

				// RESET DETAIL
					$this->m_cho_payment->deleteCPRJDetail($JournalH_Code);

				// START : DETAIL JOURNAL
					// START : DEBET
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
							$Ref_Number			= $d['Ref_Number'];
							$Other_Desc			= $d['Other_Desc']."- ".$JournalH_Desc;
							$Journal_DK			= $JournalD_Pos;
							$Journal_Type		= $Journal_Type;
							$isTax				= $isTax;
							
							// Insert into tbl_journal_detail (D) for All Expenses
							$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, JOBCODEID, Currency_id, JournalD_Debet, 
											JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
											Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_UNIT, ITM_PRICE, Ref_Number, Other_Desc,
											Journal_DK, Journal_Type, isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name) VALUE
											('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code', '$JOBCODEID', 'IDR', $JournalD_Debet, 
											$JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, 
											$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
											'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$Ref_Number', '$Other_Desc', 'D', 
											'$Journal_Type', $isTax, $GEJ_STAT, '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name')";
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
				
					// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
						$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name) VALUE
											('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
											$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$Ref_Number', 
											'$Other_Desc', 'K', '$Journal_Type', $isTax, $GEJ_STAT, '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name')";
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
				
				// START : UPDATE STAT DET
					$this->load->model('m_updash/m_updash', '', TRUE);				
					$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
					$this->m_updash->updSTATJD($paramSTAT);
				// END : UPDATE STAT DET
			}
			
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
		//$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$CB_NUM1	= $_GET['id'];
		$CB_NUM		= $this->url_encryption_helper->decode_url($CB_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			$data['CB_NUM'] = $CB_NUM;
			$data['title'] 	= $appName;
			
			$this->load->view('v_finance/v_cproj_payment/print_kaspry', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function cpothb80da8() // OK
	{
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
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
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
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
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
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
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
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
			$AH_NOTES		= addslashes($this->input->post('JournalH_Desc'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
									'JournalH_Desc' 	=> $AH_NOTES,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> 'O-EXP',	// Cash Project
									'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
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
				$Ref_Number		= $d['Ref_Number'];
				$Other_Desc		= $d['Other_Desc'];
				$Journal_DK		= $JournalD_Pos;
				$Journal_Type	= $Journal_Type;
				$isTax			= $isTax;
				
				// Insert into tbl_journal_detail (D) for All Expenses
				$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, JOBCODEID, Currency_id, JournalD_Debet, 
								JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
								Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
								curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_UNIT, ITM_PRICE, Ref_Number, Other_Desc,
								Journal_DK, Journal_Type, isTax, GEJ_STAT, Acc_Name) VALUE
								('$JournalH_Code', '$Acc_Id', '$proj_Code', '$JOBCODEID', 'IDR', $JournalD_Debet, 
								$JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, 
								$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
								'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$Ref_Number', '$Other_Desc', 'D', 
								'$Journal_Type', $isTax, $GEJ_STAT, '$Acc_Name')";
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
								isTax, GEJ_STAT, Acc_Name) VALUE
								('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
								$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$Ref_Number', 
								'$Other_Desc', 'K', '$Journal_Type', $isTax, $GEJ_STAT, '$Acc_Name')";
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
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
		
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
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
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
			$AH_NOTES		= addslashes($this->input->post('JournalH_Desc2'));
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
										'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
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
								elseif($ITM_GROUP == 'SC')
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
					$Ref_Number				= $d['Ref_Number'];
					$Other_Desc				= $d['Other_Desc'];
					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					// Insert into tbl_journal_detail (D) for All Expenses
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
									isTax, JOBCODEID, Acc_Name) VALUE
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
									VALUE
									('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_KreditTOT, $Base_KreditTOT_Tax,
									$Base_KreditTOT, $Base_KreditTOT_Tax, $Base_KreditTOT, $Base_KreditTOT_Tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'D', '$Journal_Type', $isTax, '$Acc_Name')";
				$this->db->query($insSQLK);
				
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseKreditTOT 
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
									'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
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
															ITM_USED 	= $ITM_USED+$ITM_VOLM, 
															ITM_USED_AM = $ITM_USEDAM+$Base_Debet
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
															ITM_OUT 	= $ITM_OUT+$ITM_VOLM,
															UM_VOLM 	= $UM_VOLM+$ITM_VOLM,
															UM_AMOUNT 	= $UM_AMOUNT+$Base_Debet
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
					$Ref_Number				= $d['Ref_Number'];
					$Other_Desc				= $d['Other_Desc'];
					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					// Insert into tbl_journal_detail (D) for All Expenses
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE, ITM_UNIT, Ref_Number, Other_Desc,
									Journal_DK, Journal_Type, isTax, JOBCODEID, Acc_Name)
									VALUE
									('$JournalH_Code', '$Acc_Id', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
									$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit,
									$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, 
									'$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_PRICE', '$ITM_UNIT', '$Ref_Number', '$Other_Desc', 'D', 
									'$Journal_Type', $isTax, '$JOBCODEID', '$Acc_Name')";
					$this->db->query($insSQL);
					
					// UPDATE INTO USE JOBLISTDETAIL
					/*$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = 1, ITM_USED_AM = ITM_USED_AM+$JournalD_Debet
								WHERE JOBCODEID = '$JOBCODEID'";*/
					$upJL1	= "UPDATE tbl_joblist_detail SET ITM_USED = ITM_USED + $ITM_VOLM, ITM_USED_AM = ITM_USED_AM+$JournalD_Debet
								WHERE JOBCODEID = '$JOBCODEID'";
					//$this->db->query($upJL1);
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
									isTax, Acc_Name) VALUE
									('$JournalH_Code', '$acc_number', '$PRJCODE', 'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax,
									$Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$ITM_CODE', 
									'$ITM_GROUP', '$Ref_Number', '$Other_Desc', 'K', '$Journal_Type', $isTax, '$Acc_Name')";
				$this->db->query($insSQLK);
				
				$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $BaseDebetTOT WHERE JournalH_Code = '$JournalH_Code'";
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
			$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
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
			$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
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

		setlocale(LC_ALL, 'id-ID', 'id_ID');

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
				$sqlJOBPAR		= "SELECT JOBDESC from tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
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
											FROM tbl_journaldetail
											WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE'
												AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT IN (1,2,7)";
					$resJOBDR			= $this->db->query($sqlJOBDR)->result();
					foreach($resJOBDR as $rowJOBDR) :
						$ITM_USEDR		= $rowJOBDR->TOTVOL;
						$ITM_USEDR_AM	= $rowJOBDR->TOTAMN;
					endforeach;

				$ITM_USED 		= $ITM_USED1 + $ITM_USEDR;
				$ITM_USED_AM 	= $ITM_USED_AM1 + $ITM_USEDR_AM;

				$BUDG_REMVOLM	= $ITM_VOLMBG + $ADD_VOLM - $ITM_USED;
				$BUDG_REMAMNT	= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;

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
					if($disabledB == 0)
					{
						$chkBox		= "<input type='radio' name='chk0' id='chk0".$noU."' value='".$ACC_ID."|".$JONDESCRIP."|".$ITM_CODE."|".$ITM_GROUP."|".$JOBCODEID."|".$BUDG_REMVOLM."|".$BUDG_REMAMNT."|".$ITM_UNIT."|".$ITM_LASTP."|".$THEROW."' onClick='pickThis0(".$noU.");'/>";
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
											"<div style='text-align:right;'><span ".$CELL_COL.">".$UM_AMOUNTV."</span></div>",
											"<div style='text-align:right;'>".$REM_VOL."</div>",
											"<div style='text-align:right;'>".$REM_AMN."</div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function getACCID()
	{
		$PRJCODE 	= $_GET['id'];
		$ACC_ID 	= $_POST['AccID'];

		$ACC_BAL	= 0;
		$sqlBAL 	= "SELECT Base_OpeningBalance, Base_Debet, Base_Kredit
						FROM tbl_chartaccount
						WHERE Account_Number = '$ACC_ID' AND PRJCODE = '$PRJCODE'";
		$resBAL 	= $this->db->query($sqlBAL)->result();
		foreach($resBAL as $rowBAL):
			$Base_OB 	= $rowBAL->Base_OpeningBalance;
			$Base_D 	= $rowBAL->Base_Debet;
			$Base_K 	= $rowBAL->Base_Kredit;
			$ACC_BAL 	= $Base_OB + $Base_D - $Base_K;
		endforeach;
		echo "$ACC_BAL";
	}
}