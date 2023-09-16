<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 12 Desember 2021
	* File Name		= C_r_pd.php
	* Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_r_pd extends CI_Controller  
{
  	function __construct() 						// 1
	{
		parent::__construct();
		
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_r_pd/m_r_pd', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_finance/m_cho_payment/m_cho_payment', '', TRUE);
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
		
			//$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}
	
 	function index() 							// 2
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_r_pd/prj_l15t4ll/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj_l15t4ll() 						// 3
	{
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$jrnType	= $_GET['id'];
			$jrnType	= $this->url_encryption_helper->decode_url($jrnType);
			
			$LangID 	= $this->session->userdata['LangID'];

			$mnCode				= 'MN047';
			$data["MenuCode"] 	= 'MN047';
			$data["MenuApp"] 	= 'MN047';
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
			$data["secURL"] 	= "c_finance/c_r_pd/c_rpd_all/?id=";
			
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

	function c_rpd_all() 						// 4
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
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
			// -------------------- END : SEARCHING METHOD --------------------
			
			$jrnType			= $_GET['jrnType'];

			$data['jrnType'] 	= $jrnType;
			$collData 			= "$PRJCODE~$jrnType";
			$data['addURL'] 	= site_url('c_finance/c_r_pd/adda70d18/?id='.$this->url_encryption_helper->encode_url($collData));
			$data['PRJCODE'] 	= $PRJCODE;

			$mnCode				= 'MN047';
			$data["MenuCode"] 	= 'MN047';
			$data["MenuApp"] 	= 'MN047';
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
				$MenuCode 		= 'MN047';
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

			$data['backURL'] 	= site_url('c_finance/c_r_pd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			$this->load->view('v_finance/v_r_pd/v_cb_payment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() 						// 5
	{
		$PRJCODE		= $_GET['id'];
		//$jrnType		= $_GET['jrnType'];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'pdRealiz')$pdRealiz = $LangTransl;
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
			$num_rows 		= $this->m_r_pd->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_r_pd->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$Journal_AmountReal	= number_format($dataI['Journal_AmountReal'],2);
				$Journal_AmountRem	= $dataI['Journal_Amount'] - $dataI['Journal_AmountReal'];
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$GEJ_STAT_PD		= $dataI['GEJ_STAT_PD'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$acc_number			= $dataI['acc_number'];

				$ISPERSLD 			= "Pinjaman Dinas (PD)";
				$EMP_NAME 			= "";
				$s_emp				= "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'";
				$r_emp 				= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$EMP_NAME		= $rw_emp->EMP_NAME;
				endforeach;

				$Acc_Name 			= "-";
				$sqlISHO 			= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$Acc_Name		= $rowISHO->Acc_Name;
				endforeach;

				if($GEJ_STAT_PD == 1)
				{
					$GEJ_STAT_PDC 	= "warning";
					$GEJ_STAT_PDD 	= "New";
				}
				elseif($GEJ_STAT_PD == 2)
				{
					$GEJ_STAT_PDC 	= "primary";
					$GEJ_STAT_PDD 	= "Confirmed";
				}
				elseif($GEJ_STAT_PD == 3)
				{
					$GEJ_STAT_PDC 	= "success";
					$GEJ_STAT_PDD 	= "Approved";
				}
				elseif($GEJ_STAT_PD == 4)
				{
					$GEJ_STAT_PDC 	= "warning";
					$GEJ_STAT_PDD 	= "Revised";
				}
				elseif($GEJ_STAT_PD == 5)
				{
					$GEJ_STAT_PDC 	= "danger";
					$GEJ_STAT_PDD 	= "Rejected";
				}
				elseif($GEJ_STAT_PD == 6)
				{
					$GEJ_STAT_PDC 	= "info";
					$GEJ_STAT_PDD 	= "Closed";
				}
				elseif($GEJ_STAT_PD == 7)
				{
					$GEJ_STAT_PDC 	= "info";
					$GEJ_STAT_PDD 	= "Awaiting";
				}
				else
				{
					$GEJ_STAT_PDC 	= "danger";
					$GEJ_STAT_PDD 	= "Fake";
				}

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_r_pd/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secPrint1			= site_url('c_finance/c_r_pd/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 			= base_url().'index.php/__l1y/trashCHO/?id=';
				$secVoid 			= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				// GET ALL REALIZATION
					$stpRealD 		= "";
					$s_SR 			= "SELECT DISTINCT ISPERSL_STEP, GEJ_STAT_PD FROM tbl_journaldetail_pd
											WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE' AND ISPERSL_REALIZ = 1";
					$r_SR		= $this->db->query($s_SR)->result();
					foreach($r_SR as $rw_SR):
						$stpReal	= $rw_SR->ISPERSL_STEP;
						$stpStat	= $rw_SR->GEJ_STAT_PD;
						$disBtnR 	= 1;
						if($stpStat == 1)
							$stpStatD 	= "warning";
						elseif($stpStat == 2)
							$stpStatD 	= "primary";
						elseif($stpStat == 3)
						{
							$stpStatD 	= "success";
							$disBtnR 	= 0;
						}
						else
							$stpStatD 	= "danger";

						$collDtUpR 		= "$JournalH_Code~$stpReal";
						$secUpd_up		= site_url('c_finance/c_r_pd/up0b28t18_upd/?id='.$this->url_encryption_helper->encode_url($collDtUpR));

						$stpRealD 	= $stpRealD."<a href='".$secUpd_up."' class='btn btn-".$stpStatD." btn-xs'>&nbsp;".$stpReal."&nbsp;</a>";
					endforeach;

					$s_SRC 			= "tbl_journaldetail_pd WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE' AND ISPERSL_REALIZ = 1";
					$r_SRC			= $this->db->count_all($s_SRC);
					if($r_SRC == 0)
						$disBtnR 	= 0;

				if($GEJ_STAT == 1 || $GEJ_STAT == 4) 
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-success btn-xs' title='".$pdRealiz."' disabled='disabled'>
									   		<i class='glyphicon glyphicon-plus'></i>
									   	</a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
											<i class='glyphicon glyphicon-off'></i>
										</a>
										<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
											<i class='fa fa-trash-o'></i>
										</a>
									</label>";
				}
				elseif($GEJ_STAT == 3)
				{
					$disBtnRD 	= "";
					if($disBtnR == 1 || $Journal_AmountRem <= 0)
						$disBtnRD 	= "disabled='disabled'";

					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-success btn-xs' title='".$pdRealiz."' ".$disBtnRD.">
									   		<i class='glyphicon glyphicon-plus'></i>
									   	</a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
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
									   	<a href='".$secUpd."' class='btn btn-success btn-xs' title='".$pdRealiz."' disabled='disabled'>
									   		<i class='glyphicon glyphicon-plus'></i>
									   	</a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
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
										  		$Journal_AmountReal
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<div class='timeline-footer'>
								                  	".$stpRealD."
								                </div>
							                </div>",
										  	"<div><span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span></div>",
										  	$secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataGRP() 						// 5
	{
		$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$GEJ_STAT		= $_GET['DSTAT'];
		$GEJ_CATEG		= $_GET['SRC'];
		//$jrnType		= $_GET['jrnType'];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'pdRealiz')$pdRealiz = $LangTransl;
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
			$num_rows 		= $this->m_r_pd->get_AllDataGRPC($PRJCODE, $SPLCODE, $GEJ_STAT, $GEJ_CATEG, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_r_pd->get_AllDataGRPL($PRJCODE, $SPLCODE, $GEJ_STAT, $GEJ_CATEG, $search, $length, $start, $order, $dir);
								
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
				$Journal_AmountReal	= number_format($dataI['Journal_AmountReal'],2);
				$Journal_AmountRem	= $dataI['Journal_Amount'] - $dataI['Journal_AmountReal'];
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$GEJ_STAT_PD		= $dataI['GEJ_STAT_PD'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);

				$ISPERSL			= $dataI['ISPERSL'];
				$PERSL_EMPID		= $dataI['PERSL_EMPID'];
				$acc_number			= $dataI['acc_number'];

				$ISPERSLD 			= "Pinjaman Dinas (PD)";
				$EMP_NAME 			= "";
				$s_emp				= "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'";
				$r_emp 				= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$EMP_NAME		= $rw_emp->EMP_NAME;
				endforeach;

				$Acc_Name 			= "-";
				$sqlISHO 			= "SELECT Account_NameId AS Acc_Name FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$acc_number' LIMIT 1";
				$resISHO			= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$Acc_Name		= $rowISHO->Acc_Name;
				endforeach;

				if($GEJ_STAT_PD == 1)
				{
					$GEJ_STAT_PDC 	= "warning";
					$GEJ_STAT_PDD 	= "New";
				}
				elseif($GEJ_STAT_PD == 2)
				{
					$GEJ_STAT_PDC 	= "primary";
					$GEJ_STAT_PDD 	= "Confirmed";
				}
				elseif($GEJ_STAT_PD == 3)
				{
					$GEJ_STAT_PDC 	= "success";
					$GEJ_STAT_PDD 	= "Approved";
				}
				elseif($GEJ_STAT_PD == 4)
				{
					$GEJ_STAT_PDC 	= "warning";
					$GEJ_STAT_PDD 	= "Revised";
				}
				elseif($GEJ_STAT_PD == 5)
				{
					$GEJ_STAT_PDC 	= "danger";
					$GEJ_STAT_PDD 	= "Rejected";
				}
				elseif($GEJ_STAT_PD == 6)
				{
					$GEJ_STAT_PDC 	= "info";
					$GEJ_STAT_PDD 	= "Closed";
				}
				elseif($GEJ_STAT_PD == 7)
				{
					$GEJ_STAT_PDC 	= "info";
					$GEJ_STAT_PDD 	= "Awaiting";
				}
				else
				{
					$GEJ_STAT_PDC 	= "danger";
					$GEJ_STAT_PDD 	= "Fake";
				}

				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_finance/c_r_pd/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secPrint1			= site_url('c_finance/c_r_pd/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 			= base_url().'index.php/__l1y/trashCHO/?id=';
				$secVoid 			= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				$voidID 			= "$secVoid~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";

				// GET ALL REALIZATION
					$stpRealD 		= "";
					$s_SR 			= "SELECT DISTINCT ISPERSL_STEP, GEJ_STAT_PD FROM tbl_journaldetail_pd
											WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE' AND ISPERSL_REALIZ = 1";
					$r_SR		= $this->db->query($s_SR)->result();
					foreach($r_SR as $rw_SR):
						$stpReal	= $rw_SR->ISPERSL_STEP;
						$stpStat	= $rw_SR->GEJ_STAT_PD;
						$disBtnR 	= 1;
						if($stpStat == 1)
							$stpStatD 	= "warning";
						elseif($stpStat == 2)
							$stpStatD 	= "primary";
						elseif($stpStat == 3)
						{
							$stpStatD 	= "success";
							$disBtnR 	= 0;
						}
						else
							$stpStatD 	= "danger";

						$collDtUpR 		= "$JournalH_Code~$stpReal";
						$secUpd_up		= site_url('c_finance/c_r_pd/up0b28t18_upd/?id='.$this->url_encryption_helper->encode_url($collDtUpR));

						$stpRealD 	= $stpRealD."<a href='".$secUpd_up."' class='btn btn-".$stpStatD." btn-xs'>&nbsp;".$stpReal."&nbsp;</a>";
					endforeach;

					$s_SRC 			= "tbl_journaldetail_pd WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE' AND ISPERSL_REALIZ = 1";
					$r_SRC			= $this->db->count_all($s_SRC);
					if($r_SRC == 0)
						$disBtnR 	= 0;

				if($GEJ_STAT == 1 || $GEJ_STAT == 4) 
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-success btn-xs' title='".$pdRealiz."' disabled='disabled'>
									   		<i class='glyphicon glyphicon-plus'></i>
									   	</a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
											<i class='glyphicon glyphicon-off'></i>
										</a>
										<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
											<i class='fa fa-trash-o'></i>
										</a>
									</label>";
				}
				elseif($GEJ_STAT == 3)
				{
					$disBtnRD 	= "";
					if($disBtnR == 1 || $Journal_AmountRem <= 0)
						$disBtnRD 	= "disabled='disabled'";

					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-success btn-xs' title='".$pdRealiz."' ".$disBtnRD.">
									   		<i class='glyphicon glyphicon-plus'></i>
									   	</a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
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
									   	<a href='".$secUpd."' class='btn btn-success btn-xs' title='".$pdRealiz."' disabled='disabled'>
									   		<i class='glyphicon glyphicon-plus'></i>
									   	</a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
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
										  		$Journal_AmountReal
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<div class='timeline-footer'>
								                  	".$stpRealD."
								                </div>
							                </div>",
										  	"<div><span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span></div>",
										  	$secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function up0b28t18() 						// 6
	{
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
			
			$getGEJ 				= $this->m_r_pd->get_CHO_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['JournalH_Date_PD']= $getGEJ->JournalH_Date_PD;
			$JournalH_Date_PD		= $getGEJ->JournalH_Date_PD;
			if($JournalH_Date_PD == '0000-00-00' || $JournalH_Date_PD == '')
				$data['JournalH_Date_PD'] = date('d/m/Y');

			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['GEJ_STAT_PD'] 	= 0;						// New
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;
			$data['OverRPD'] 		= $getGEJ->OverRPD;

			$data['ISPERSL_STEP'] 	= 0;

			$mnCode				= 'MN047';
			$MenuCode			= 'MN047';
			$data["MenuCode"] 	= 'MN047';
			$data["MenuApp"] 	= 'MN047';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_finance/c_r_pd/update_process');
			$data['backURL'] 	= site_url('c_finance/c_r_pd/c_rpd_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_r_pd->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_r_pd->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				//$MenuCode 	= 'MN047';
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
			
			$this->load->view('v_finance/v_r_pd/v_r_pd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up0b28t18_upd() 					// 7
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$collDtUpR		= $_GET['id'];
			$collDtUpR		= $this->url_encryption_helper->decode_url($collDtUpR);

			$explDtupR 		= explode("~", $collDtUpR);
			$JournalH_Code 	= $explDtupR[0];
			$ISPERSL_STEP 	= $explDtupR[1];
			
			$getGEJ 				= $this->m_r_pd->get_CHO_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['ISPERSL_STEP'] 	= $ISPERSL_STEP;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['JournalH_Date_PD']= $getGEJ->JournalH_Date_PD;
			$JournalH_Date_PD		= $getGEJ->JournalH_Date_PD;
			if($JournalH_Date_PD == '0000-00-00' || $JournalH_Date_PD == '')
				$data['JournalH_Date_PD'] = date('d/m/Y');

			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['GEJ_STAT_PD'] 	= $getGEJ->GEJ_STAT_PD;
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['PPNH_Amount'] 	= $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] 	= $getGEJ->PPHH_Amount;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;
			$data['OverRPD'] 		= $getGEJ->OverRPD;

			$mnCode				= 'MN047';
			$MenuCode			= 'MN047';
			$data["MenuCode"] 	= 'MN047';
			$data["MenuApp"] 	= 'MN047';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $PRJCODE_HO;	
			$data['proj_Code'] 	= $PRJCODE;	
			$data['proj_CodeHO']= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_r_pd/update_process');
			// $data['secPrint']	= site_url('c_finance/c_r_pd/printdocument_PD/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
			$data['backURL'] 	= site_url('c_finance/c_r_pd/c_rpd_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE).'&jrnType='.$jrnType);
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['cAllCOA']	= $this->m_r_pd->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 	= $this->m_r_pd->view_all_COA($PRJCODE, $DefEmp_ID, $acc_number)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				//$MenuCode 	= 'MN047';
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
			
			$this->load->view('v_finance/v_r_pd/v_r_pd_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() 					// 8
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
			$JournalH_Date_PD	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date_PD'))));
			$accYr			= date('Y', strtotime($JournalH_Date));
			//$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$task			= $this->input->post('task');
			$GEJ_STAT_PD	= $this->input->post('GEJ_STAT_PD');
			$acc_number		= $this->input->post('acc_number');
			$Manual_No		= $this->input->post('Manual_No');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_PD 	= $this->input->post('JournalH_Code1');
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			$PRJCODE 		= $this->input->post('proj_Code');
			$proj_CodeHO	= $this->input->post('PRJCODE_HO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$JournalH_Desc 	= addslashes($this->input->post('JournalH_Desc'));
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('JournalH_Desc2'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$ISPERSL 		= $this->input->post('ISPERSL');
			$PERSL_EMPID 	= $this->input->post('PERSL_EMPID');

			$upJHA			= "UPDATE tbl_journalheader SET GEJ_STAT_PD = '$GEJ_STAT_PD' WHERE JournalH_Code = '$JournalH_PD'";
			$this->db->query($upJHA);

			$upJHA			= "UPDATE tbl_journalheader_pd SET GEJ_STAT_PD = '$GEJ_STAT_PD' WHERE JournalH_Code = '$JournalH_PD'";
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
				$projGEJH 	= array('JournalH_Date_PD' 	=> $JournalH_Date_PD);									
				$this->m_r_pd->updateCHO($JournalH_Code, $projGEJH);
			// END : UPDATE HEADER

			if($task == "add")
			{
				// GET LAST NUMBER OF REALIZATION
					$LASTSTEP 	= 0;
					$s_gLast 	= "SELECT MAX(ISPERSL_STEP) AS LASTSTEP FROM tbl_journaldetail_pd
									WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE' AND ISPERSL_REALIZ = 1";
					$r_gLast	= $this->db->query($s_gLast)->result();
					foreach($r_gLast as $rw_gLast):
						$LASTSTEP 	= $rw_gLast->LASTSTEP;
					endforeach;
					$nextStep 	= $LASTSTEP + 1;
			}
			else
			{
				$nextStep 	= $this->input->post('ISPERSL_STEP');
			}

			// START : UPDATE STATUS
			if($GEJ_STAT_PD == 3)
			{
				$AH_ISLAST 	= 1; 			// TIDAK ADA STEP APPROVAL

				// START : RESET DETAIL UNTUK DI SISI DEBET. SISI KREDIT DIAMBIL DARI LAWAN KAS BANK SAAT PEMBUATAN PENGELUARAN KAS
					$this->m_r_pd->delPDRDet($JournalH_Code, $nextStep);

				// GET CREDIT ACCOUNT
					$AccId 			= "-";
					$AccName 		= "-";
					$sqlACCNm 		= "SELECT Acc_Id, Acc_Name FROM tbl_journaldetail
										WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_PD' AND Journal_DK = 'D'
											AND ISPERSL = 1 AND ISPERSL_STEP = 0";
					$resACCNm		= $this->db->query($sqlACCNm)->result();
					foreach($resACCNm as $rowACCNm):
						$AccId		= $rowACCNm->Acc_Id;
						$AccName	= $rowACCNm->Acc_Name;
					endforeach;

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
						$Other_Desc		= $d['Other_Desc'];
						$isExtra		= $d['isExtra'];
						
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
						$ITM_PRICE	= $d['ITM_PRICE'];
						//$ITM_PRICE	= $ITM_Amount / $ITM_VOLM;

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

									// START : UPDATE ITEM HISTORY AND L/R
										$paramLR = array('JournalH_Code' 	=> $JournalH_Code,
														'PRJCODE'			=> $PRJCODE,
														'JournalH_Date' 	=> $JournalH_Date,
														'PERIODM' 			=> $PERIODM,
														'PERIODY' 			=> $PERIODY,
														'ITM_CODE' 			=> $ITM_CODE,
														'ITM_VOLM' 			=> $ITM_VOLM,
														'ITM_PRICE' 		=> $ITM_PRICE,
														'Base_Debet'		=> $Base_Debet,
														'Base_Kredit'		=> $Base_Kredit,
														'Transaction_Type'	=> "CHO",
														'comp_init'			=> $comp_init,
														'JOBCODEID'			=> $JOBCODEID,
														'ITM_CATEG'			=> $ITM_CATEG,
														'JournalH_Desc' 	=> $JournalH_Desc,
														'Other_Desc'		=> "$JournalH_Desc - Realisasi PD");
										$this->m_journal->updateVLR_NEW($JournalH_Code, $paramLR);
									// END : UPDATE ITEM HISTORY AND L/R
								}
							// END : UPDATE L/R

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
						if($Other_Desc == "")
							$Other_Desc			= $JournalH_Desc;

						$Journal_DK				= $JournalD_Pos;
						$Journal_Type			= $Journal_Type;
						$isTax					= $isTax;
						
						// Insert into tbl_journal_detail (D) for All Expenses
							$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalH_Date, JournalType, proj_Code,
											Currency_id, JournalD_Debet, JournalD_Debet_tax,
											JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
											Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
											curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE, ITM_UNIT, Ref_Number,
											Other_Desc, Journal_DK, Journal_Type, isTax, JOBCODEID, Acc_Name, ISPERSL, GEJ_STAT_PD,
											ISPERSL_STEP, Manual_No, isExtra, LastUpdate)
											VALUE
											('$JournalH_Code', '$ACC_NUM', '$JournalH_Date', '$JournalType', '$proj_Code', 
											'IDR', $JournalD_Debet, $JournalD_Debet_tax, 
											$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax,
											$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax,
											1, 1, '$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_PRICE', '$ITM_UNIT', '$Ref_Number',
											'$Other_Desc', 'D', '$Journal_Type', $isTax, '$JOBCODEID', '$Acc_Name', 1,
											3, '$nextStep', '$Manual_No', '$isExtra', '$AH_APPROVED')";
							$this->db->query($insSQL);
						
						// Insert into tbl_journal_detail (D) for All Expenses
							$insSQLPD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalH_Date, JournalType, proj_Code,
											Currency_id, JournalD_Debet, JournalD_Debet_tax, JournalD_Kredit, JournalD_Kredit_tax,
											Base_Debet, Base_Debet_tax, Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax,
											COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
											ITM_UNIT, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax, JOBCODEID,
											Acc_Name, ISPERSL, GEJ_STAT_PD, ISPERSL_STEP, Manual_No, isExtra, LastUpdate)
											VALUE
											('$JournalH_Code', '$ACC_NUM', '$JournalH_Date', '$JournalType', '$proj_Code', 
											'IDR', $JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax,
											$Base_Debet, $Base_Debet_tax, $Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax,
											$COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_PRICE',
											'$ITM_UNIT', '$Ref_Number', '$Other_Desc', 'D', '$Journal_Type', $isTax, '$JOBCODEID', 
											'$Acc_Name', 1, 3, '$nextStep', '$Manual_No', $isExtra, '$AH_APPROVED')";
							$this->db->query($insSQLPD);
					}
					
					$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
				// END : DEBET

				// DI SISI KREDIT, BERASAL DARI AKUN LAWAN DARI KAS BANK PADA HEADER
					$insSQLK	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, JournalType, proj_Code,
										Currency_id, JournalD_Kredit, JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax,
										COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT_PD, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
										ISPERSL_REALIZ, ISPERSL_STEP, Manual_No, LastUpdate)
									VALUE
										('$JournalH_Code', '$AccId', '$JournalType', '$PRJCODE',
										'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax,
										$Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$Ref_Number', '$Other_Desc', 'K', '$Journal_Type',
										$isTax, $GEJ_STAT_PD, '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$AccName', 1,
										1, '$nextStep', '$Manual_No', '$AH_APPROVED')";
					$this->db->query($insSQLK);
			
					$insSQLKPD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code,
										Currency_id, JournalD_Kredit, JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax,
										COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT_PD, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
										ISPERSL_REALIZ, ISPERSL_STEP, Manual_No, LastUpdate)
									VALUE
										('$JournalH_Code', '$AccId', '$JournalType', '$PRJCODE',
										'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax,
										$Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$Ref_Number', '$Other_Desc', 'K', '$Journal_Type',
										$isTax, $GEJ_STAT_PD, '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$AccName', 1,
										1, '$nextStep', '$Manual_No', '$AH_APPROVED')";
					$this->db->query($insSQLKPD);
				// END : KREDIT

				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$AccId' LIMIT 1";
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
										WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$AccId'";
						$this->db->query($sqlUpdCOA);
					}
				}

				// $upJH1	= "UPDATE tbl_journalheader SET Journal_AmountReal = $BaseDebetTOT,
				// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
				// 			WHERE JournalH_Code = '$JournalH_PD'";
				
				// Update date 16-05-2022 by Iyan:
				$upJH1	= "UPDATE tbl_journalheader SET Journal_AmountReal = $BaseDebetTOT,
								GEJ_STAT_PPD = IF(Journal_Amount = Journal_AmountReal, 3, IF(Journal_Amount < Journal_AmountReal, 2, 1))
							WHERE JournalH_Code = '$JournalH_PD'";
				$this->db->query($upJH1);

				// $upJH2	= "UPDATE tbl_journalheader_pd SET Journal_AmountReal = $BaseDebetTOT,
				// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
				// 			WHERE JournalH_Code = '$JournalH_PD'";

				// Update date 16-05-2022 by Iyan:
				$upJH2	= "UPDATE tbl_journalheader_pd SET Journal_AmountReal = $BaseDebetTOT,
								GEJ_STAT_PPD = IF(Journal_Amount = Journal_AmountReal, 3, IF(Journal_Amount < Journal_AmountReal, 2, 1))
							WHERE JournalH_Code = '$JournalH_PD'";
				$this->db->query($upJH2);
			}
			else 		// IF NEW / CONFIRMED
			{
				// RESET DETAIL
					$this->m_r_pd->delPDRDet($JournalH_Code, $nextStep);

				// GET CREDIT ACCOUNT
					$AccId 			= "-";
					$AccName 		= "-";
					$sqlACCNm 		= "SELECT Acc_Id, Acc_Name FROM tbl_journaldetail
										WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
											AND ISPERSL = 1 AND ISPERSL_STEP = 0";
					$resACCNm		= $this->db->query($sqlACCNm)->result();
					foreach($resACCNm as $rowACCNm):
						$AccId		= $rowACCNm->Acc_Id;
						$AccName	= $rowACCNm->Acc_Name;
					endforeach;
		
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
							$Other_Desc		= $d['Other_Desc'];
							$isExtra		= $d['isExtra'];
							
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
							if($Other_Desc == "")
								$Other_Desc		= $JournalH_Desc;

							$Journal_DK			= $JournalD_Pos;
							$Journal_Type		= $Journal_Type;
							$isTax				= $isTax;
							
							// Insert into tbl_journal_detail (D) for All Expenses
								$insSQLPD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code,
												JOBCODEID, Currency_id, JournalD_Debet, JournalD_Debet_tax,
												JournalD_Kredit, JournalD_Kredit_tax, Base_Debet, Base_Debet_tax, 
												Base_Kredit, Base_Kredit_tax, COA_Debet, COA_Debet_tax, COA_Kredit, COA_Kredit_tax,
												curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_UNIT, ITM_PRICE, Ref_Number,
												Other_Desc, Journal_DK, Journal_Type, isTax, JournalH_Date, proj_CodeHO,
												PRJPERIOD, Acc_Name, ISPERSL, ISPERSL_REALIZ, GEJ_STAT_PD, ISPERSL_STEP, Manual_No,
												isExtra) VALUE
												('$JournalH_Code', '$Acc_Id', '$JournalType', '$proj_Code',
												'$JOBCODEID', 'IDR', $JournalD_Debet, $JournalD_Debet_tax,
												$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, 
												$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax,
												1, 1, '$ITM_CODE', '$ITM_GROUP', '$ITM_VOLM', '$ITM_UNIT', '$ITM_PRICE', '$JournalH_PD', 
												'$Other_Desc', 'D', '$Journal_Type', $isTax, '$JournalH_Date', '$proj_CodeHO',
												'$PRJPERIOD', '$Acc_Name', 1, 1, $GEJ_STAT_PD, '$nextStep', '$Manual_No',
												$isExtra)";
								$this->db->query($insSQLPD);
						}
					
						$BaseDebetTOT	= $Base_DebetTOT + $Base_DebetTOT_Tax;
					// END : DEBET

					// DI SISI KREDIT, BERASAL DARI AKUN LAWAN DARI KAS BANK PADA HEADER
						$insSQLKPD	= "INSERT INTO tbl_journaldetail_pd (JournalH_Code, Acc_Id, JournalType, proj_Code,
										Currency_id, JournalD_Kredit, JournalD_Kredit_tax, Base_Kredit, Base_Kredit_tax,
										COA_Kredit, COA_Kredit_tax, curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type,
										isTax, GEJ_STAT_PD, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL,
										ISPERSL_REALIZ, ISPERSL_STEP, Manual_No)
									VALUE
										('$JournalH_Code', '$AccId', '$JournalType', '$proj_Code',
										'IDR', $Base_DebetTOT, $Base_DebetTOT_Tax, $Base_DebetTOT, $Base_DebetTOT_Tax,
										$Base_DebetTOT, $Base_DebetTOT_Tax, 1, 1, '$JournalH_PD', '$Other_Desc', 'K', '$Journal_Type',
										$isTax, $GEJ_STAT_PD, '$JournalH_Date', '$proj_CodeHO', '$PRJPERIOD', '$AccName', 1,
										1, '$nextStep', '$Manual_No')";
						$this->db->query($insSQLKPD);
					// END : KREDIT
				// END : DETAIL JOURNAL

				// $upJH1	= "UPDATE tbl_journalheader SET Journal_AmountReal = $BaseDebetTOT,
				// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
				// 			WHERE JournalH_Code = '$JournalH_PD'";

				// Update date 16-05-2022 by Iyan:
				$upJH1	= "UPDATE tbl_journalheader SET Journal_AmountReal = $BaseDebetTOT,
								GEJ_STAT_PPD = IF(Journal_Amount = Journal_AmountReal, 3, IF(Journal_Amount < Journal_AmountReal, 2, 1))
							WHERE JournalH_Code = '$JournalH_PD'";
				$this->db->query($upJH1);

				// $upJH2	= "UPDATE tbl_journalheader_pd SET Journal_AmountReal = $BaseDebetTOT,
				// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
				// 			WHERE JournalH_Code = '$JournalH_PD'";

				// Update date 16-05-2022 by Iyan:
				$upJH2	= "UPDATE tbl_journalheader_pd SET Journal_AmountReal = $BaseDebetTOT,
								GEJ_STAT_PPD = IF(Journal_Amount = Journal_AmountReal, 3, IF(Journal_Amount < Journal_AmountReal, 2, 1))
							WHERE JournalH_Code = '$JournalH_PD'";
				$this->db->query($upJH2);
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN047';
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
			
			$url			= site_url('c_finance/c_r_pd/c_rpd_all/?id='.$this->url_encryption_helper->encode_url($proj_Code).'&jrnType='.$jrnType);
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
		
		// $CB_NUM1	= $_GET['id'];
		// $CB_NUM		= $this->url_encryption_helper->decode_url($CB_NUM1);
				
		if ($this->session->userdata('login') == TRUE)
		{
			// $data['CB_NUM'] = $CB_NUM;
			$data['title'] 	= $appName;

			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 				= $this->m_r_pd->get_CHO_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['Manual_No'] 		= $getGEJ->Manual_No;
			$data['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['JournalH_Date_PD']= $getGEJ->JournalH_Date_PD;
			$JournalH_Date_PD		= $getGEJ->JournalH_Date_PD;
			if($JournalH_Date_PD == '0000-00-00' || $JournalH_Date_PD == '')
				$data['JournalH_Date_PD'] = date('d/m/Y');

			$data['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 		= $getGEJ->proj_Code;
			$data['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE				= $getGEJ->proj_Code;
			$PRJCODE_HO				= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['GEJ_STAT_PD'] 	= 0;						// New
			$data['acc_number'] 	= $getGEJ->acc_number;
			$acc_number				= $getGEJ->acc_number;
			$data['Journal_Amount'] = $getGEJ->Journal_Amount;
			$data['ISPERSL'] 		= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 	= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 		= $getGEJ->JournalType;
			$jrnType				= $getGEJ->JournalType;
			$data['OverRPD'] 		= $getGEJ->OverRPD;
			
			// $this->load->view('v_finance/v_r_pd/print_pd', $data);
			$this->load->view('v_finance/v_r_pd/print_realisasiPD', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function printdocument_PD()
	{
		//$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
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
			
			$getGEJ 					= $this->m_r_pd->get_CHO_by_number($JournalH_Code)->row();
			$data['JournalH_Code'] 		= $getGEJ->JournalH_Code;
			$data['Manual_No'] 			= $getGEJ->Manual_No;
			$data['JournalH_Date'] 		= $getGEJ->JournalH_Date;
			$data['JournalH_Date_PD']	= $getGEJ->JournalH_Date_PD;
			$JournalH_Date_PD			= $getGEJ->JournalH_Date_PD;
			if($JournalH_Date_PD == '0000-00-00' || $JournalH_Date_PD == '')
				$data['JournalH_Date_PD'] = date('d/m/Y');

			$data['JournalH_Desc']		= $getGEJ->JournalH_Desc;
			$data['JournalH_Desc2']		= $getGEJ->JournalH_Desc2;
			$data['proj_Code'] 			= $getGEJ->proj_Code;
			$data['PRJCODE'] 			= $getGEJ->proj_Code;
			$data['proj_CodeHO'] 		= $getGEJ->proj_CodeHO;
			$data['PRJPERIOD'] 			= $getGEJ->PRJPERIOD;
			$PRJCODE					= $getGEJ->proj_Code;
			$PRJCODE_HO					= $getGEJ->proj_CodeHO;
			$data['GEJ_STAT'] 			= $getGEJ->GEJ_STAT;
			$data['GEJ_STAT_PD'] 		= 0;						// New
			$data['acc_number'] 		= $getGEJ->acc_number;
			$acc_number					= $getGEJ->acc_number;
			$data['Journal_Amount'] 	= $getGEJ->Journal_Amount;
			$data['Journal_AmountReal'] = $getGEJ->Journal_AmountReal;
			$data['PPNH_Amount'] 		= $getGEJ->PPNH_Amount;
			$data['PPHH_Amount'] 		= $getGEJ->PPHH_Amount;
			$data['ISPERSL'] 			= $getGEJ->ISPERSL;
			$data['PERSL_EMPID'] 		= $getGEJ->PERSL_EMPID;
			$data['jrnType'] 			= $getGEJ->JournalType;
			$jrnType					= $getGEJ->JournalType;
			$data['OverRPD'] 			= $getGEJ->OverRPD;
			
			// $this->load->view('v_finance/v_r_pd/print_pd', $data);
			$this->load->view('v_finance/v_r_pd/print_penyelesaianPD', $data);
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
			$num_rows 		= $this->m_r_pd->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_r_pd->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
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
}