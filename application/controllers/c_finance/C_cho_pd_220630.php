<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 05 MAret 2022
	* File Name		= C_cho_pd.php
	* Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_cho_pd extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_cho_payment/m_cho_pd', '', TRUE);
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
	
 	function index() 				// Y
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_cho_pd/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj_l15t4ll() 			// Y
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$jrnType	= $_GET['id'];
			$jrnType	= $this->url_encryption_helper->decode_url($jrnType);
			$jrnType	= 'CHO-PD';
			
			$LangID 	= $this->session->userdata['LangID'];

			$mnCode				= 'MN359';
			$data["MenuCode"] 	= 'MN359';
			$data["MenuApp"] 	= 'MN359';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["jrnType"] 	= $jrnType;
			$data["countPRJ"] 	= $num_rows;
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_finance/c_cho_pd/cp2b0d18_all/?id=";
			
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

	function cp2b0d18_all() 		// Y
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
				// $chg_url		= 'c_finance/c_cho_pd/cp2b0d18_all'
				
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
				$data["url_search"] = site_url('c_finance/c_cho_pd/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_cho_pd->count_all_GEJ($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_cho_pd->get_all_GEJ($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$jrnType			= $_GET['jrnType'];

			$data['jrnType'] 	= $jrnType;
			$collData 			= "$PRJCODE~$jrnType";
			$data['addURL'] 	= site_url('c_finance/c_cho_pd/adda70d18/?id='.$this->url_encryption_helper->encode_url($collData));
			$data['PRJCODE'] 	= $PRJCODE;

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

			$data['backURL'] 	= site_url('c_finance/c_cho_pd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$this->load->view('v_finance/v_cho_payment/v_cho_pd', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() 			// Y
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
									"PD_Date",
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
			$num_rows 		= $this->m_cho_pd->get_AllDataC($PRJCODE, $jrnType, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_pd->get_AllDataL($PRJCODE, $jrnType, $search, $length, $start, $order, $dir);
								
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
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				$PD_Date			= $dataI['PD_Date'];
				$PD_DateV			= date('d M Y', strtotime($PD_Date));
				
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

				$ISPERSLD 			= "Pinjaman Dinas (PD)";
				$EMP_NAME 			= "";
				$s_emp				=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
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

				$secPrint1		= site_url('c_finance/c_cho_pd/printdocument_PD/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secViewpaid	= site_url('c_finance/c_cho_pd/viewpaid_voucher_pd/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail_pd A
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
				$Acc_Name 			= $Acc_Name;

				if($ISPERSL == 1)
					$Acc_NameD 		= "<div><strong>$Acc_Name</strong></div>";
				else
					$Acc_NameD 		= "";

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho_pd/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 			= base_url().'index.php/__l1y/trashCHO_NEW/?id=';
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
										  	$PD_DateV,
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

  	function get_AllDataGRP() 			// Y
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
		$SPLCODE		= $_GET['SPLC'];
		$GEJ_STAT		= $_GET['DSTAT'];
		$GEJ_CATEG		= $_GET['SRC'];
		
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
									"PD_Date",
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
			$num_rows 		= $this->m_cho_pd->get_AllDataPDGRPC($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $GEJ_CATEG, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_pd->get_AllDataPDGRPL($PRJCODE, $jrnType, $SPLCODE, $GEJ_STAT, $GEJ_CATEG, $search, $length, $start, $order, $dir);
								
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
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				$PD_Date			= $dataI['PD_Date'];
				$PD_DateV			= date('d M Y', strtotime($PD_Date));
				
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

				$ISPERSLD 			= "Pinjaman Dinas (PD)";
				$EMP_NAME 			= "";
				$s_emp				=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
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

				$secPrint1		= site_url('c_finance/c_cho_pd/printdocument_PD/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secViewpaid	= site_url('c_finance/c_cho_pd/viewpaid_voucher_pd/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail_pd A
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
				$Acc_Name 			= $Acc_Name;

				if($ISPERSL == 1)
					$Acc_NameD 		= "<div><strong>$Acc_Name</strong></div>";
				else
					$Acc_NameD 		= "";

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho_pd/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 			= base_url().'index.php/__l1y/trashCHO_NEW/?id=';
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
										  	$PD_DateV,
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

  	function get_AllDataGRPA() 			// Y
	{
		$PRJCODE		= $_GET['id'];
		$jrnType		= $_GET['jrnType'];
			
		/*$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$PO_STAT		= $_GET['DSTAT'];
		$PO_CATEG		= $_GET['SRC'];
		$jrnType		= $_GET['jrnType'];*/
			
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
									"PD_Date",
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
			$num_rows 		= $this->m_cho_pd->get_AllDataGRPC($PRJCODE, $jrnType, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_pd->get_AllDataGRPL($PRJCODE, $jrnType, $search, $length, $start, $order, $dir);
								
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
				$GJournal_Total 	= number_format($dataI['GJournal_Total'],2);
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				$PD_Date			= $dataI['PD_Date'];
				$PD_DateV			= date('d M Y', strtotime($PD_Date));
				
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

				$ISPERSLD 			= "Pinjaman Dinas (PD)";
				$EMP_NAME 			= "";
				$s_emp				=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
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

				$secPrint1		= site_url('c_finance/c_cho_pd/printdocument_PD/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secViewpaid	= site_url('c_finance/c_cho_pd/viewpaid_voucher_pd/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));

				$Acc_Name 			= "-";
				$noUAcc 			= 0;
				$sqlISHO 			= "SELECT B.Account_NameId AS Acc_Name FROM tbl_journaldetail_pd A
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
				$Acc_Name 			= $Acc_Name;

				if($ISPERSL == 1)
					$Acc_NameD 		= "<div><strong>$Acc_Name</strong></div>";
				else
					$Acc_NameD 		= "";

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_cho_pd/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 			= base_url().'index.php/__l1y/trashCHO_NEW/?id=';
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
										  	$PD_DateV,
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
	
	function adda70d18() 			// Y
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

		$data['form_action']= site_url('c_finance/c_cho_pd/add_process');
		
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
			//$data['form_action']= site_url('c_finance/c_cho_pd/add_process');
			$data['backURL'] 	= site_url('c_finance/c_cho_pd/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_pd->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_pd->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			$data['vwDocPatt'] 	= $this->m_cho_pd->getDataDocPat($MenuCode)->result();
			
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
			else
				$this->load->view('v_finance/v_cho_payment/v_cho_pd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getACCID()				// Y
	{
		$collD 		= $_POST['collDt'];
		$collDt 	= explode("~", $collD);
		$jrnCode 	= $collDt[0];
		$pattCode 	= $collDt[1];
		$PRJCODE 	= $collDt[2];
		$task 		= $collDt[3];
		$jrnType 	= $collDt[4];
		$ACC_ID 	= $collDt[5];

		$CB_DATE 	= date('Y-m-d');
		$YEAR 		= date('Y');
		$MONTH 		= date('m');
		$DATE 		= date('d');

		$ACC_BAL	= 0;
		$ACC_CLASS 	= 3;			// DEFAULTT KAS
		$sqlBAL 	= "SELECT Base_OpeningBalance, Base_Debet, Base_Kredit, Account_Class
						FROM tbl_chartaccount
						WHERE Account_Number = '$ACC_ID' AND PRJCODE = '$PRJCODE'";
		$resBAL 	= $this->db->query($sqlBAL)->result();
		foreach($resBAL as $rowBAL):
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

			// START : PENOMORAN DOKUMEN PD
				$sql 	= "tbl_journalheader WHERE YEAR(JournalH_Date) = $yearC AND proj_Code = '$PRJCODE' AND JournalType = '$jrnType'";
				$result = $this->db->count_all($sql);
				$myMax 	= $result+1;

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
				$Man_No		= "$PATTCLS$pattCode-$PRJCODE-$lastNum";
			// END : PENOMORAN DOKUMEN PD

			// START : PENOMORAN CASH/BANK
				$s_03 		= "tbl_bp_header WHERE CB_ACCID = '$ACC_ID' AND PRJCODE = '$PRJCODE' AND CB_DATE = '$CB_DATE'";
				$r_03 		= $this->db->count_all($s_03);
				$myMax 		= $r_03+1;

				$BANK_PATT 	= "";
				$s_02 		= "SELECT LEFT(Account_NameId,3) AS BANK_PATT FROM tbl_chartaccount WHERE Account_Number = '$ACCID' AND PRJCODE = '$PRJCODE'";
				$r_02 		= $this->db->query($s_02)->result();
				foreach($r_02 as $rw_02):
					$BANK_PATT = $rw_02->BANK_PATT;
				endforeach;
				
				$Pattern_Length	= 3;
				$len = strlen($myMax);
				$nol = '';
				if($Pattern_Length==3)
				{if($len==1) $nol="00";else if($len==2) $nol="0";
				}
				elseif($Pattern_Length==4)
				{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
				}
				elseif($Pattern_Length==5)
				{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
				}
				elseif($Pattern_Length==6)
				{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
				}
				
				$lastPatt 	= $nol.$myMax;

				$DocCBNo 	= "$BANK_PATT$YEAR$MONTH$DATE$lastPatt";
			// END : PENOMORAN CASH/BANK

			echo "$ACC_BAL~$Man_No~$DocCBNo";
		}
		else if($jrnType == 'CHO-PD' && $task == 'add')
		{
			$pattY 	= date('Y');
			$yearC	= (int)$pattY;

			// START : PENOMORAN DOKUMEN PD
				$sql 	= "tbl_journalheader WHERE YEAR(JournalH_Date) = $yearC AND proj_Code = '$PRJCODE' AND JournalType = '$jrnType'";
				$result = $this->db->count_all($sql);
				$myMax 	= $result+1;

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
				$Man_No		= "$PATTCLS$pattCode-$PRJCODE-$lastNum";
			// END : PENOMORAN DOKUMEN PD

			// START : PENOMORAN CASH/BANK
				$s_03 		= "tbl_bp_header WHERE CB_ACCID = '$ACC_ID' AND PRJCODE = '$PRJCODE' AND CB_DATE = '$CB_DATE'";
				$r_03 		= $this->db->count_all($s_03);
				$myMax 		= $r_03+1;

				$BANK_PATT 	= "";
				$s_02 		= "SELECT LEFT(Account_NameId,3) AS BANK_PATT FROM tbl_chartaccount WHERE Account_Number = '$ACC_ID' AND PRJCODE = '$PRJCODE'";
				$r_02 		= $this->db->query($s_02)->result();
				foreach($r_02 as $rw_02):
					$BANK_PATT = $rw_02->BANK_PATT;
				endforeach;
				
				$Pattern_Length	= 3;
				$len = strlen($myMax);
				$nol = '';
				if($Pattern_Length==3)
				{if($len==1) $nol="00";else if($len==2) $nol="0";
				}
				elseif($Pattern_Length==4)
				{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
				}
				elseif($Pattern_Length==5)
				{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
				}
				elseif($Pattern_Length==6)
				{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
				}
				
				$lastPatt 	= $nol.$myMax;

				$DocCBNo 	= "$BANK_PATT$YEAR$MONTH$DATE$lastPatt";
			// END : PENOMORAN CASH/BANK

			echo "$ACC_BAL~$Man_No~$DocCBNo";
		}
		else
		{
			// START : PENOMORAN CASH/BANK
				$s_03 		= "tbl_bp_header WHERE CB_ACCID = '$ACC_ID' AND PRJCODE = '$PRJCODE' AND CB_DATE = '$CB_DATE'";
				$r_03 		= $this->db->count_all($s_03);
				$myMax 		= $r_03+1;

				$BANK_PATT 	= "";
				$s_02 		= "SELECT LEFT(Account_NameId,3) AS BANK_PATT FROM tbl_chartaccount WHERE Account_Number = '$ACC_ID' AND PRJCODE = '$PRJCODE'";
				$r_02 		= $this->db->query($s_02)->result();
				foreach($r_02 as $rw_02):
					$BANK_PATT = $rw_02->BANK_PATT;
				endforeach;
				
				$Pattern_Length	= 3;
				$len = strlen($myMax);
				$nol = '';
				if($Pattern_Length==3)
				{if($len==1) $nol="00";else if($len==2) $nol="0";
				}
				elseif($Pattern_Length==4)
				{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
				}
				elseif($Pattern_Length==5)
				{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
				}
				elseif($Pattern_Length==6)
				{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
				}
				
				$lastPatt 	= $nol.$myMax;

				$DocCBNo 	= "$BANK_PATT$YEAR$MONTH$DATE$lastPatt";
			// END : PENOMORAN CASH/BANK

			echo "$ACC_BAL~$jrnCode~$DocCBNo";
		}
	}
	
	function add_process_2220604() 			// Y
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$JournalType 	= $this->input->post('JournalType');
		$jrnType 		= $this->input->post('JournalType');

		$MenuCode		= 'MN359';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$PD_Date		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PD_Date'))));
			$PlanRDate		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PlanRDate'))));

			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 		= $this->input->post('JournalH_Code');
			$Reference_Number 	= $this->input->post('Reference_Number');
			$proj_Code 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PERSL_STAT 	= $this->input->post('PERSL_STAT');
			$REF_NUM 		= $this->input->post('REF_NUM');
			$REF_CODE 		= $this->input->post('REF_CODE');

			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			$PPNH_Amount 	= $this->input->post('PPNH_Amount');
			if($PPNH_Amount == '')
				$PPNH_Amount = 0;
			$PPHH_Amount 	= $this->input->post('PPHH_Amount');
			if($PPHH_Amount == '')
				$PPHH_Amount = 0;
			$GJournal_Total = $this->input->post('Journal_Amount');
			if($GJournal_Total == '')
				$GJournal_Total = 0;
			$JOURNL_DESC	= addslashes($this->input->post('JournalH_Desc'));

			$SPLCODE 		= $this->input->post('SPLCODE');
			$SPLDESC 		= "";
			$s_01 			= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
      		$r_01 			= $this->db->query($s_01)->result();
      		foreach($r_01 as $rw_01):
      			$SPLDESC 	= $rw_01->SPLDESC;
      		endforeach;
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code 	= $this->input->post('Pattern_Code');
				
				$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				//$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			// START : SETTING L/R 
				/* ---------------- L/R terbentuk setelah PD Dibayar: update date-> 220407_2231
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
				------------------------------ end hidden ------------------------------------- */
			// END : SETTING L/R

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> "MN359",
										'DOCTYPE' 		=> 'CHO-PD',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $PD_Date,
										'PAYDATE'		=> $JournalH_Date,
										'ACC_ID'		=> $acc_number,
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $Manual_No 		= $colExpl[1];
		        $pay_ManCode	= $colExpl[2];
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
			// START - HEADER
				$AH_CODE		= $JournalH_Code;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= date('Y-m-d H:i:s');
				$AH_NOTES		= addslashes($this->input->post('JournalH_Desc'));
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
										'Reference_Number'	=> $Reference_Number,
										//'JournalH_Desc' 	=> $AH_NOTES,
										'Manual_No' 		=> $Manual_No,
										'SPLCODE' 			=> $SPLCODE,
										'SPLDESC' 			=> $SPLDESC,
										'JournalType' 		=> $JournalType,
										'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
										'JournalH_Desc3'	=> addslashes($pay_ManCode),
										'JournalH_Date'		=> $JournalH_Date,
										'PD_Date'			=> $PD_Date,
										'PlanRDate'			=> $PlanRDate,
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
										'PERSL_STAT'		=> $PERSL_STAT,
										'REF_NUM'			=> $REF_NUM,
										'REF_CODE'			=> $REF_CODE,
										'PERSL_EMPID'		=> $PERSL_EMPID,
										'acc_number'		=> $acc_number,
										'PPNH_Amount'		=> $PPNH_Amount,
										'PPHH_Amount'		=> $PPHH_Amount,
										'GJournal_Total'	=> $GJournal_Total);
				$this->m_cho_pd->add_pd($projGEJH);
			// END - HEADER

			// START - DETAIL
				$PattNum 		= 0;

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

				// START : DEBET - PD TABLE
					$insSQLD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
										JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUE
										('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, 0, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
					$this->db->query($insSQLD);
				// END : DEBET - PD TABLE

				// START : KREDIT - PD TABLE
					$insSQLK	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
										JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUE
										('$JournalH_Code', '$acc_number', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, 0, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
					$this->db->query($insSQLK);
				// END : KREDIT - PD TABLE
			// END - DETAIL

			$BaseDebetTOT 	= $Journal_Amount;
		
			// UPDATE AMOUNT HEADER
				$upJH3	= "UPDATE tbl_journalheader_pd SET Journal_Amount = $BaseDebetTOT, GJournal_Total = $BaseDebetTOT
							WHERE JournalH_Code = '$JournalH_Code'";
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
			
			$url			= site_url('c_finance/c_cho_pd/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() 			// Y
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$JournalType 	= $this->input->post('JournalType');
		$jrnType 		= $this->input->post('JournalType');

		$MenuCode		= 'MN359';
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$PD_Date		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$PlanRDate		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PlanRDate'))));

			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= "";
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 		= $this->input->post('JournalH_Code');
			$Reference_Number 	= $this->input->post('Reference_Number');
			$proj_Code 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PERSL_STAT 	= $this->input->post('PERSL_STAT');
			$REF_NUM 		= $this->input->post('REF_NUM');
			$REF_CODE 		= $this->input->post('REF_CODE');

			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			$PPNH_Amount 	= $this->input->post('PPNH_Amount');
			if($PPNH_Amount == '')
				$PPNH_Amount = 0;
			$PPHH_Amount 	= $this->input->post('PPHH_Amount');
			if($PPHH_Amount == '')
				$PPHH_Amount = 0;
			$GJournal_Total = $this->input->post('Journal_Amount');
			if($GJournal_Total == '')
				$GJournal_Total = 0;
			$JOURNL_DESC	= addslashes($this->input->post('JournalH_Desc'));

			$SPLCODE 		= $this->input->post('SPLCODE');
			$SPLDESC 		= "";
			$s_01 			= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
      		$r_01 			= $this->db->query($s_01)->result();
      		foreach($r_01 as $rw_01):
      			$SPLDESC 	= $rw_01->SPLDESC;
      		endforeach;
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code 	= $this->input->post('Pattern_Code');
				
				$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				//$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			// START : SETTING L/R 
				/* ---------------- L/R terbentuk setelah PD Dibayar: update date-> 220407_2231
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
				------------------------------ end hidden ------------------------------------- */
			// END : SETTING L/R

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> "MN359",
										'DOCTYPE' 		=> 'CHO-PD',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $JournalH_Date,
										'PAYDATE'		=> $JournalH_Date,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $Manual_No 		= $colExpl[1];
		        $pay_ManCode	= "";
			// END : MEMBUAT LIST NUMBER / tbl_doclist
		   	
			// START - HEADER
				$AH_CODE		= $JournalH_Code;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= date('Y-m-d H:i:s');
				$AH_NOTES		= addslashes($this->input->post('JournalH_Desc'));
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
										'Reference_Number'	=> $Reference_Number,
										//'JournalH_Desc' 	=> $AH_NOTES,
										'Manual_No' 		=> $Manual_No,
										'SPLCODE' 			=> $SPLCODE,
										'SPLDESC' 			=> $SPLDESC,
										'JournalType' 		=> $JournalType,
										'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
										'JournalH_Desc3'	=> addslashes($pay_ManCode),
										'JournalH_Date'		=> $JournalH_Date,
										'PD_Date'			=> $PD_Date,
										'PlanRDate'			=> $PlanRDate,
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
										'PERSL_STAT'		=> $PERSL_STAT,
										'REF_NUM'			=> $REF_NUM,
										'REF_CODE'			=> $REF_CODE,
										'PERSL_EMPID'		=> $PERSL_EMPID,
										'acc_number'		=> "",
										'PPNH_Amount'		=> $PPNH_Amount,
										'PPHH_Amount'		=> $PPHH_Amount,
										'GJournal_Total'	=> $GJournal_Total);
				$this->m_cho_pd->add_pd($projGEJH);
			// END - HEADER

			// START - DETAIL
				$PattNum 		= 0;

				$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');
				$ACC_ID_EMPAP 	= $this->input->post('ACC_ID_EMPAP');

				// AKUN DI SISI DEBET
					$Acc_Name1 	= "-";
					$sqlACCNm1 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_PERSL' LIMIT 1";
					$resACCNm1		= $this->db->query($sqlACCNm1)->result();
					foreach($resACCNm1 as $rowACCNm1):
						$Acc_Name1	= $rowACCNm1->Acc_Name;
					endforeach;

				// AKUN DI SISI KREDIT
					$Acc_Name2 	= "-";
					$sqlACCNm2 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_EMPAP' LIMIT 1";
					$resACCNm2		= $this->db->query($sqlACCNm2)->result();
					foreach($resACCNm2 as $rowACCNm2):
						$Acc_Name2	= $rowACCNm2->Acc_Name;
					endforeach;

				$PattNumD 		= $PattNum+1;
				$PattNumK 		= $PattNum+1;

				// START : DEBET - PD TABLE
					$insSQLD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
										JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
										curr_rate, isDirect, Manual_No, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUE
										('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, 0, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
					$this->db->query($insSQLD);
				// END : DEBET - PD TABLE

				// START : KREDIT - PD TABLE
					$insSQLK	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
										JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
										curr_rate, isDirect, Manual_No, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUE
										('$JournalH_Code', '$ACC_ID_EMPAP', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, 0, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
					$this->db->query($insSQLK);
				// END : KREDIT - PD TABLE
			// END - DETAIL

			$BaseDebetTOT 	= $Journal_Amount;
		
			// UPDATE AMOUNT HEADER
				$upJH3	= "UPDATE tbl_journalheader_pd SET Journal_Amount = $BaseDebetTOT, GJournal_Total = $BaseDebetTOT
							WHERE JournalH_Code = '$JournalH_Code'";
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
			
			$url			= site_url('c_finance/c_cho_pd/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up0b28t18() 			// Y
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
			$data['vwDocPatt'] 	= $this->m_cho_pd->getDataDocPat($MenuCode)->result();

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 					= $this->m_cho_pd->get_CHO_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 		= $getGEJ->JournalH_Code;
			$data['Reference_Number'] 	= $getGEJ->Reference_Number;
			$data['Manual_No'] 			= $getGEJ->Manual_No;
			$data['JournalH_Date'] 		= $getGEJ->JournalH_Date;
			$data['SPLCODE'] 			= $getGEJ->SPLCODE;
			$data['PD_Date'] 			= $getGEJ->PD_Date;
			$data['PlanRDate'] 			= $getGEJ->PlanRDate;
			$data['JournalH_Desc']		= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']		= $getGEJ->JournalH_Desc2;
			$data['JournalH_Desc3']		= $getGEJ->JournalH_Desc3;
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
			$data['GJournal_Total'] 	= $getGEJ->GJournal_Total;
			$data['ISPERSL'] 			= $getGEJ->ISPERSL;
			$data['PERSL_STAT'] 		= $getGEJ->PERSL_STAT;
			$data['REF_NUM'] 			= $getGEJ->REF_NUM;
			$data['REF_CODE'] 			= $getGEJ->REF_CODE;
			$data['PERSL_EMPID'] 		= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 			= $getGEJ->JournalType;
			$jrnType					= $getGEJ->JournalType;

			$data["ISPERSL"] 			= 1;
			$mnCode						= 'MN359';
			$MenuCode					= 'MN359';
			$data["MenuCode"] 			= 'MN359';
			$data["MenuApp"] 			= 'MN359';
			$data['PRJCODE_HO']			= $this->data['PRJCODE_HO'];
			$getMN						= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] 	= $getMN->menu_name_IND;
			else
				$data["mnName"] 	= $getMN->menu_name_ENG;

			$data['form_action']= site_url('c_finance/c_cho_pd/update_process');
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			//$data['form_action']= site_url('c_finance/c_cho_pd/update_process');
			$data['backURL'] 	= site_url('c_finance/c_cho_pd/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_cho_pd->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_cho_pd->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			$data['vwDocPatt'] 	= $this->m_cho_pd->getDataDocPat($MenuCode)->result();
			
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

			$this->load->view('v_finance/v_cho_payment/v_cho_pd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() 		// Y
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
			
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));

			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$PD_Date		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$PlanRDate		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PlanRDate'))));

			$accYr			= date('Y', strtotime($JournalH_Date));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$acc_number		= "";
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');

			$JournalH_Code 		= $this->input->post('JournalH_Code');
			$Reference_Number 	= $this->input->post('Reference_Number');
			$proj_Code 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PERSL_STAT 	= $this->input->post('PERSL_STAT');
			$REF_NUM 		= $this->input->post('REF_NUM');
			$REF_CODE 		= $this->input->post('REF_CODE');
			$PPNH_Amount 	= $this->input->post('PPNH_Amount');
			$PPHH_Amount 	= $this->input->post('PPHH_Amount');
			$GJournal_Total = $this->input->post('Journal_Amount');

			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');
			$JournalH_Desc 	= addslashes($this->input->post('JournalH_Desc'));
			$JOURNL_DESC	= addslashes($this->input->post('JournalH_Desc'));

			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('JournalH_Desc2'));
			$AH_ISLAST		= $this->input->post('IS_LAST');

			$SPLCODE 		= $this->input->post('SPLCODE');
			$SPLDESC 		= "";
			$s_01 			= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
      		$r_01 			= $this->db->query($s_01)->result();
      		foreach($r_01 as $rw_01):
      			$SPLDESC 	= $rw_01->SPLDESC;
      		endforeach;
			
			$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($upJHA);
			
			// START : SETTING L/R
				/* ---------------- L/R terbentuk setelah PD Dibayar: update date-> 220407_2231
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
				------------------------------- end hidden ------------------------------------- */
			// END : SETTING L/R

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN359',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $JournalH_Date,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
			// START : UPDATE HEADER
				$projGEJH 	= array('JournalH_Code' 	=> $JournalH_Code,
									'Reference_Number'	=> $Reference_Number,
									//'JournalH_Desc' 	=> $AH_NOTES,
									'SPLCODE' 			=> $SPLCODE,
									'SPLDESC' 			=> $SPLDESC,
									'Manual_No' 		=> $Manual_No,
									'JournalType' 		=> $JournalType,
									'JournalH_Desc'		=> addslashes($this->input->post('JournalH_Desc')),
									'JournalH_Desc3'	=> addslashes($this->input->post('JournalH_Desc3')),
									'JournalH_Date'		=> $JournalH_Date,
									'PD_Date'			=> $PD_Date,
									'PlanRDate' 		=> $PlanRDate,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> $JournalType,
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'ISPERSL'			=> $ISPERSL,
									'PERSL_STAT'		=> $PERSL_STAT,
									'REF_NUM'			=> $REF_NUM,
									'REF_CODE'			=> $REF_CODE,
									'PERSL_EMPID'		=> $PERSL_EMPID,
									'acc_number'		=> "",
									'Journal_Amount'	=> $Journal_Amount,
									'PPNH_Amount'		=> $PPNH_Amount,
									'PPHH_Amount'		=> $PPHH_Amount,
									'GJournal_Total'	=> $GJournal_Total);
				$this->m_cho_pd->updateCHO_pd($JournalH_Code, $projGEJH);
			// END : UPDATE HEADER

			if($GEJ_STAT == 3)
			{
				// DEFAULT STATUS IF APPROVE
					$upJH	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);

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

				$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');
				$ACC_ID_EMPAP 	= $this->input->post('ACC_ID_EMPAP');

				// AKUN DI SISI DEBET
					$Acc_Name1 	= "-";
					$sqlACCNm1 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_PERSL' LIMIT 1";
					$resACCNm1		= $this->db->query($sqlACCNm1)->result();
					foreach($resACCNm1 as $rowACCNm1):
						$Acc_Name1	= $rowACCNm1->Acc_Name;
					endforeach;

				// AKUN DI SISI KREDIT
					$Acc_Name2 	= "-";
					$sqlACCNm2 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_EMPAP' LIMIT 1";
					$resACCNm2		= $this->db->query($sqlACCNm2)->result();
					foreach($resACCNm2 as $rowACCNm2):
						$Acc_Name2	= $rowACCNm2->Acc_Name;
					endforeach;

					$BaseDebetTOT 	= $Journal_Amount;
				
				if($AH_ISLAST == 1)
				{
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

					$this->m_cho_pd->add($projGEJH);

					$upJHA	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);

					$upJHA	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJHA);

					$PattNumD 		= 1;
					$PattNumK 		= 2;

					// START : DEBET
						$insSQLD	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
											JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
											curr_rate, isDirect, Manual_No, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUE
											('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, 0, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
						$this->db->query($insSQLD);
					// END : DEBET

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
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Journal_Amount, 
													Base_Debet2 = Base_Debet2+$Journal_Amount, BaseD_$accYr = BaseD_$accYr+$Journal_Amount
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_PERSL'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : UPDATE LAWAN KAS/BANK

					// START : KREDIT -- Insert into tbl_journal_detail (K) for Akun Kas/Bank
						$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
											JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, Manual_No, Other_Desc, Journal_DK, Journal_Type,
											isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUE
											('$JournalH_Code', '$ACC_ID_EMPAP', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
											0, $Journal_Amount, 0, $Journal_Amount, 0,
											1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
											0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
						$this->db->query($insSQLK);
					// END : KREDIT

					// START : UPDATE KAS/BANK
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_EMPAP' LIMIT 1";
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
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Journal_Amount, 
													Base_Kredit2 = Base_Kredit2+$Journal_Amount, BaseK_$accYr = BaseK_$accYr+$Journal_Amount
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_EMPAP'";
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

				// UPDATE AMOUNT HEADER
					$upJH3	= "UPDATE tbl_journalheader SET Journal_Amount = $Journal_Amount, GJournal_Total = $Journal_Amount WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
					
					$upJH3	= "UPDATE tbl_journalheader_pd SET Journal_Amount = $Journal_Amount, GJournal_Total = $Journal_Amount WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH3);
				
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
					$this->m_cho_pd->deleteCPRJDetail($JournalH_Code);

				// UPDATE STATUS HEADER
					$upJH	= "UPDATE tbl_journalheader_pd SET GEJ_STAT = $GEJ_STAT WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);

				// RESET DETAIL
					$this->m_cho_pd->deleteCPRJDetail_pd($JournalH_Code);

				$ACC_ID_PERSL 	= $this->input->post('ACC_ID_PERSL');
				$ACC_ID_EMPAP 	= $this->input->post('ACC_ID_EMPAP');

				// AKUN DI SISI DEBET
					$Acc_Name1 	= "-";
					$sqlACCNm1 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_PERSL' LIMIT 1";
					$resACCNm1		= $this->db->query($sqlACCNm1)->result();
					foreach($resACCNm1 as $rowACCNm1):
						$Acc_Name1	= $rowACCNm1->Acc_Name;
					endforeach;

				// AKUN DI SISI KREDIT
					$Acc_Name2 	= "-";
					$sqlACCNm2 	= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_EMPAP' LIMIT 1";
					$resACCNm2		= $this->db->query($sqlACCNm2)->result();
					foreach($resACCNm2 as $rowACCNm2):
						$Acc_Name2	= $rowACCNm2->Acc_Name;
					endforeach;

				$PattNumD 		= 1;
				$PattNumK 		= 2;

				// START : DEBET - PD TABLE
					$insSQLD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Debet,
										JournalD_Debet_tax, Base_Debet, Base_Debet_tax, COA_Debet, COA_Debet_tax,
										curr_rate, isDirect, Manual_No, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUE
										('$JournalH_Code', '$ACC_ID_PERSL', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, 0, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'D', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name1', 1, $PattNumD)";
					$this->db->query($insSQLD);
				// END : DEBET - PD TABLE

				// START : KREDIT - PD TABLE
					$insSQLK	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code, Currency_id, JournalD_Kredit,
										JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax, COA_Kredit, COA_Kredit_tax,
										curr_rate, isDirect, Manual_No, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum) VALUE
										('$JournalH_Code', '$ACC_ID_EMPAP', '$JournalType', '$PRJCODE', 'IDR', $Journal_Amount,
										0, $Journal_Amount, 0, $Journal_Amount, 0,
										1, 1, '$Manual_No', '$JOURNL_DESC', 'K', '$JournalType',
										0, '$GEJ_STAT', '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name2', 1, $PattNumK)";
					$this->db->query($insSQLK);
				// END : KREDIT - PD TABLE

				$BaseDebetTOT 	= $Journal_Amount;
		
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
			
			$url			= site_url('c_finance/c_cho_pd/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function printdocument_PD()	// Y
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
			
			$getGEJ 				= $this->m_cho_pd->get_CHO_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Reference_Number'] 	= $getGEJ->Reference_Number;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['SPLCODE'] 		= $getGEJ->SPLCODE;
			$data['REF_CODE'] 		= $getGEJ->REF_CODE;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PD_Date'] 		= $getGEJ->PD_Date;
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
			
			$this->load->view('v_finance/v_cho_payment/print_PD', $data);
			//$this->load->view('page_uc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function viewpaid_voucher_pd()	// ?
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

			$getGEJ 				= $this->m_cho_pd->get_CHO_by_number($JournalH_Code)->row();

			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['PD_Date'] 		= $getGEJ->PD_Date;
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

  	function get_AllDataPOList()	// Y
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
			$num_rows 		= $this->m_cho_pd->get_AllDataPOC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_pd->get_AllDataPOL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
										  "<span style='white-space:nowrap'>$PO_NOTESV</span>");
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
									  "B",
									  "C",
									  "D",
									  "E",
									  "F");*/
			
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataWO() 		// Y
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'Periode')$Periode = $LangTransl;
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
			
			$columns_valid 	= array("",
									"WO_CODE",
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_cho_pd->get_AllDataWOC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cho_pd->get_AllDataWOL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_DATEV		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_STARTD		= $dataI['WO_STARTD'];
				$WO_STARTDV		= strftime('%d %b %Y', strtotime($WO_STARTD));
				$WO_ENDD		= $dataI['WO_ENDD'];
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));
				$WO_NOTE		= $dataI['WO_NOTE'];
				$PRJNAME		= $dataI['PRJNAME'];
				$complName		= $dataI['complName'];

				$SPLCODE		= $dataI['SPLCODE'];
				$SPLDESC 		= "";
				$s_spl			=  "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
				$r_spl 			= $this->db->query($s_spl)->result();
				foreach($r_spl as $rw_spl) :
					$SPLDESC	= $rw_spl->SPLDESC;
				endforeach;

				if($WO_NOTE == '')
					$WO_NOTE 	= "-";
				
				// GET TOTAL SPK QTY
					$TOTWO_VOL		= 0;
					$TOTWO_AMN		= 0;
					$TOTWO_PPN		= 0;
					$TOTWO_PPH		= 0;
					$TOTOPN_VOL		= 0;
					$TOTOPN_AMN		= 0;
					$TOTREM_VOL		= 0;
					$TOTREM_VOL		= 0;
					$sqlQtyWO 		= "SELECT 	SUM(WO_VOLM) 	AS TOTWO_VOL, 
												SUM(WO_TOTAL) 	AS TOTWO_AMN, 
												SUM(TAXPRICE1) 	AS TOTWO_PPN,
												SUM(TAXPRICE2) 	AS TOTWO_PPH,
												SUM(OPN_VOLM) 	AS TOTOPN_VOL,
												SUM(OPN_AMOUNT) AS TOTOPN_AMN
										FROM tbl_wo_detail
										WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
					$resQtyWO 		= $this->db->query($sqlQtyWO)->result();
					foreach($resQtyWO as $rowWOQty):
						$TOTWO_VOL		= $rowWOQty->TOTWO_VOL;
						$TOTWO_AMN		= $rowWOQty->TOTWO_AMN;
						$TOTWO_PPN		= $rowWOQty->TOTWO_PPN;
						$TOTWO_PPH		= $rowWOQty->TOTWO_PPH;
						$TOTOPN_VOL		= $rowWOQty->TOTOPN_VOL;
						$TOTOPN_AMN		= $rowWOQty->TOTOPN_AMN;
					endforeach;

					// OPN - CONFIRMED
						$TOTOPNAMN	= 0;
						$TOTOPNVOL	= 0;
						$sqlTOT_OPN	= "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPN_AMN,
											SUM(A.OPND_VOLM) AS TOTOPN_VOL
										FROM tbl_opn_detail A
										INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
											AND B.PRJCODE = '$PRJCODE'
										WHERE B.WO_NUM = '$WO_NUM'
											AND A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT = 2";
						$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
						foreach($resTOT_OPN as $rowTOT_OPN) :
							$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
							$TOTOPNVOL	= $rowTOT_OPN->TOTOPN_VOL;
							if($TOTOPNAMN == '')
								$TOTOPNAMN	= 0;
							if($TOTOPNVOL == '')
								$TOTOPNVOL	= 0;
						endforeach;

					$confDesc 		= "";
					if($TOTOPNAMN > 0)
					{
						$confDesc 	= "<br><div style='white-space:nowrap' title='Confirmed'>
										  		<span class='label label-primary' style='font-size:12px'>".number_format($TOTOPNVOL,2)."</span>&nbsp;
										  		<span class='label label-warning' style='font-size:12px'>".number_format($TOTOPNAMN,2)."</span>
										  	</div>";
					}

					$TOTREM_VOL		= $TOTWO_VOL - $TOTOPN_VOL - $TOTOPNVOL;
					$TOTREM_AMN		= $TOTWO_AMN - $TOTOPN_AMN - $TOTOPNAMN;

					$disabledB 			= 0;
					if($TOTREM_VOL <= 0 || $TOTREM_AMN <= 0)
						$disabledB 		= 1;

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='radio' name='chk0' value='".$WO_NUM."|".$WO_CODE."' onClick='pickThis0(this);'/>";
					}
					else
					{
						$chkBox	= "<input type='radio' name='chk0' value='' style='display: none' />";
					}
				
				$output['data'][] 	= array($chkBox,
										  	"<div style='white-space:nowrap'>
										  		".$WO_CODE."
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<p class='text-muted' style='margin-left: 20px; white-space:nowrap'>
										  			".$WO_DATEV."
										  		</p>
										  	</div>",
										  	"<span style='white-space:nowrap'>$SPLDESC</span>",
										  	"<strong><i class='fa fa-commenting margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 20px'>
										  		$WO_NOTE
										  	</div>
										  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Periode." </strong>
									  		<p class='text-muted' style='margin-left: 20px'>
									  			".$WO_STARTDV." - ".$WO_ENDDV."
									  		</p>");
				$noU		= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F",
										"G");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

    function chgSTATPD()
    {
		$JRN_NUM 	= $_POST['JRN_NUM'];
		$JRN_CODE 	= $_POST['JRN_CODE'];
		$PRJCODE 	= $_POST['PRJCODE'];

		$up_JRN		= "UPDATE tbl_journalheader_pd SET GEJ_STAT_PD = 9 WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
		$this->db->query($up_JRN);

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1 	= "Dana PD $JRN_CODE sudah dapat dikembalian melalui Penerimaan kas/Bank.";
		}
		else
		{
			$alert1 	= "PD $JRN_CODE funds can be returned via Cash/Bank Receipts.";
		}

		echo $alert1;
    }
}