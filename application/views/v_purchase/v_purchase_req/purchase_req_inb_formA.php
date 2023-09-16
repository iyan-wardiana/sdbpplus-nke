<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 24 Oktober 2017
	* File Name	= purchase_req_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$DEPCODE 		= $this->session->userdata['DEPCODE'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{	
	foreach($vwDocPatt as $row) :
		$Pattern_Code = $row->Pattern_Code;
		$Pattern_Position = $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive = $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		$Pattern_Length = $row->Pattern_Length;
		$useYear = $row->useYear;
		$useMonth = $row->useMonth;
		$useDate = $row->useDate;
	endforeach;
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_pr_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_pr_header
			WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	// group year, month and date
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$year$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$year";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$pattMonth";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$pattDate";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "";
	
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
	$len = strlen($lastPatternNumb);
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
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
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
	}
	$lastPatternNumb = $nol.$lastPatternNumb;
	
	
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$PR_NUM			= "$DocNumber";
	$PR_CODE		= "$lastPatternNumb"; // MANUAL CODE
	
	$TRXDATEY 		= date('Y');
	$TRXDATEM 		= date('m');
	$TRXDATED 		= date('d');
	//$PR_DATE		= "$TRXDATEM/$TRXDATED/$TRXDATEY";
	$PR_DATE		= date('m/d/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$JOBCODE		= '';
	$PR_NOTE		= '';
	$PR_STAT 		= 1;	
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;	
	$PR_VALUE		= 0;
	$PR_VALUEAPP	= 0;
	$PR_REFNO		= '';
	$PR_MEMO		= '';
}
else
{	
	$PR_NUM				= $default['PR_NUM'];
	$DocNumber			= $default['PR_NUM'];
	$PR_CODE			= $default['PR_CODE'];
	$PR_DATED			= $default['PR_DATE'];

	$PR_DATE			= date('d/m/Y',strtotime($PR_DATED));
	$PR_RECEIPTD		= $default['PR_RECEIPTD'];
	$PR_RECEIPTD		= date('d/m/Y',strtotime($PR_RECEIPTD));
	$PRJCODE			= $default['PRJCODE'];
	$DEPCODE			= $default['DEPCODE'];
	$SPLCODE			= $default['SPLCODE'];
	$PR_DEPT			= $default['PR_DEPT'];
	$PR_NOTE			= $default['PR_NOTE'];
	$PR_NOTE2			= $default['PR_NOTE2'];
	$PR_STAT			= $default['PR_STAT'];
	$PR_MEMO			= $default['PR_MEMO'];
	$PR_VALUE			= $default['PR_VALUE'];
	$PR_VALUEAPP		= $default['PR_VALUEAPP'];
	$PR_REFNO			= $default['PR_REFNO'];
	$Patt_Year			= $default['Patt_Year'];
	$Patt_Month			= $default['Patt_Month'];
	$Patt_Date			= $default['Patt_Date'];
	$Patt_Number		= $default['Patt_Number'];
	$lastPatternNumb1	= $default['Patt_Number'];
	
	$dataSessSrc = array(
			'selSearchproj_Code' => $PRJCODE,
			'selSearchType' => $this->input->post('selSearchType'),
			'txtSearch' => $this->input->post('txtSearch'));
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
}
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk  = $rowcss->cssjs_lnk;
              ?>
                  <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
              <?php
          endforeach;

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk1  = $rowcss->cssjs_lnk;
              ?>
                  <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
              <?php
          endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'RequestNo')$RequestNo = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'PR_CODE')$PR_CODE = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;

			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'PR_Code')$PR_Code = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'inpMRD')$inpMRD = $LangTransl;
			if($TranslCode == 'inpMRQTY')$inpMRQTY = $LangTransl;
			if($TranslCode == 'budEmpt')$budEmpt = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
			if($TranslCode == 'greaterBud')$greaterBud = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$subTitleH	= "Persetujuan";
			$subTitleD	= "permintaan pembelian";
			
			$alert1		= "Silahkan pilih status persetujuan permintaan.";
			$alert2		= "Silahkan tulis catatan persetujuan dokumen.";
			$alert3		= "Dokumenn ini memerlukan persetujuan khusus.";
			$alert4		= "Silakan tulis catatan dokumen ini ditolak.";
		}
		else
		{
			$subTitleH	= "Approval";
			$subTitleD	= "purchase request";
			
			$alert1		= "Please select an approval status.";
			$alert2		= "Please write the document approval note.";
			$alert3		= "This document needs a special approval.";
			$alert4		= "Please write a note this document was rejected.";
		}
		
		$secAddURL	= site_url('c_purchase/c_pr180d0c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_purchase/c_pr180d0c/genCode/'; // Generate Code
		
		$isLoadDone_1	= 1;
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];

			// DocNumber - PR_VALUE
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV' AND POSCODE = '$DEPCODE'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$MAX_STEP		= $rowAPP->MAX_STEP;
					$APPROVER_1		= $rowAPP->APPROVER_1;
					if($APPROVER_1 != '')
					{
						$EMPN_1		= '';
						$sqlEMPC_1	= "tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1'";
						$resEMPC_1	= $this->db->count_all($sqlEMPC_1);
						if($resEMPC_1 > 0)
						{
							$sqlEMP_1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1' LIMIT 1";
							$resEMP_1	= $this->db->query($sqlEMP_1)->result();
							foreach($resEMP_1 as $rowEMP) :
								$FN_1	= $rowEMP->First_Name;
								$LN_1	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_1		= "$FN_1 $LN_1";
						}
					}
					$APPROVER_2	= $rowAPP->APPROVER_2;
					if($APPROVER_2 != '')
					{
						$EMPN_2		= '';
						$sqlEMPC_2	= "tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1'";
						$resEMPC_2	= $this->db->count_all($sqlEMPC_2);
						if($resEMPC_2 > 0)
						{
							$sqlEMP_2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1' LIMIT 1";
							$resEMP_2	= $this->db->query($sqlEMP_2)->result();
							foreach($resEMP_2 as $rowEMP) :
								$FN_2	= $rowEMP->First_Name;
								$LN_2	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_2		= "$FN_2 $LN_2";
						}
					}
					$APPROVER_3	= $rowAPP->APPROVER_3;
					if($APPROVER_3 != '')
					{
						$EMPN_3		= '';

						$sqlEMPC_3	= "tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1'";
						$resEMPC_3	= $this->db->count_all($sqlEMPC_3);
						if($resEMPC_3 > 0)
						{
							$sqlEMP_3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1' LIMIT 1";
							$resEMP_3	= $this->db->query($sqlEMP_3)->result();
							foreach($resEMP_3 as $rowEMP) :
								$FN_3	= $rowEMP->First_Name;
								$LN_3	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_3		= "$FN_3 $LN_3";
						}
					}
					$APPROVER_4	= $rowAPP->APPROVER_4;
					if($APPROVER_4 != '')
					{
						$EMPN_4		= '';
						$sqlEMPC_4	= "tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1'";
						$resEMPC_4	= $this->db->count_all($sqlEMPC_4);
						if($resEMPC_4 > 0)
						{
							$sqlEMP_4	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1' LIMIT 1";
							$resEMP_4	= $this->db->query($sqlEMP_4)->result();
							foreach($resEMP_4 as $rowEMP) :
								$FN_4	= $rowEMP->First_Name;
								$LN_4	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_4		= "$FN_4 $LN_4";
						}
					}
					$APPROVER_5	= $rowAPP->APPROVER_5;
					if($APPROVER_5 != '')
					{
						$EMPN_5		= '';
						$sqlEMPC_5	= "tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1'";
						$resEMPC_5	= $this->db->count_all($sqlEMPC_5);
						if($resEMPC_5 > 0)
						{
							$sqlEMP_5	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1' LIMIT 1";
							$resEMP_5	= $this->db->query($sqlEMP_5)->result();
							foreach($resEMP_5 as $rowEMP) :
								$FN_5	= $rowEMP->First_Name;
								$LN_5	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_5		= "$FN_5 $LN_5";
						}
					}
				endforeach;
				$disableAll	= 0;
			
				// CHECK AUTH APPROVE TYPE
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				
				$BefStepApp	= $APP_STEP - 1;
				if($resC_App == $BefStepApp)
				{
					$canApprove	= 1;
				}
				elseif($resC_App == $APP_STEP)
				{
					$canApprove	= 0;
					$descApp	= "You have Approved";
					$statcoloer	= "success";
				}
				else
				{
					$canApprove	= 0;
					$descApp	= "Awaiting";
					$statcoloer	= "warning";
				}
							 
				if($APP_STEP == $MAX_STEP)
					$IS_LAST		= 1;
				else
					$IS_LAST		= 0;
				
				// Mungkin dengan tahapan approval lolos, check kembali total nilai jika dan HANYA JIKA Type Approval Step is 1 = Ammount
				// This roles are for All Approval. Except PR and Receipt
				// NOTES
				// $APPLIMIT_1 		= Maximum Limit to Approve
				// $APPROVE_AMOUNT	= Amount must be Approved
				$APPROVE_AMOUNT 	= $PR_VALUE;
				//$APPROVE_AMOUNT	= 10000000000;
				//$DOCAPP_TYPE	= 1;
				if($DOCAPP_TYPE == 1)
				{
					if($APPLIMIT_1 < $APPROVE_AMOUNT)
					{
						$canApprove	= 0;
						$descApp	= "You can not approve caused of the max limit.";
						$statcoloer	= "danger";
					}
				}
			}
			else
			{
				$canApprove	= 0;
				$descApp	= "You can not approve this document.";
				$statcoloer	= "danger";
				$IS_LAST	= 0;
				$APP_STEP	= 0;
			}
			
			$APP_LEVEL	= $APP_STEP;
		// END : APPROVE PROCEDURE

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
		    <small><?php echo $PRJNAME; ?></small>  </h1>
		  <?php /*?><ol class="breadcrumb">
		    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		    <li><a href="#">Tables</a></li>
		    <li class="active">Data tables</li>
		  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="box box-primary">
		    	<div class="box-body chart-responsive">
		        <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">		
		            <table>
		                <tr>
		                    <td>
		                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
		                        <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
		                        <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
		                        <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
		                        <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
		                        <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
		                        <input type="hidden" name="PRDate" id="PRDate" value="">
		                    </td>
		                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
		                </tr>
		            </table>
		        </form>
		        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
		            	<input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
		            	<input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
		                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
		                <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
		                <input type="Hidden" name="rowCount" id="rowCount" value="0">
		            	<div class="form-group" style="display:none">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $RequestNo; ?></label>
		                    <div class="col-sm-10">
		                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="PR_NUM1" id="PR_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
		                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PR_NUM" id="PR_NUM" size="30" value="<?php echo $DocNumber; ?>" />
		                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PR_Code; ?></label>
		                    <div class="col-sm-10">
		                    	<input type="text" class="form-control" style="text-align:left" id="PR_CODE1" name="PR_CODE1" size="5" value="<?php echo $PR_CODE; ?>" disabled />
		                    	<input type="hidden" class="form-control" style="min-width:150px; max-width:150px; text-align:left" id="PR_CODE" name="PR_CODE" size="5" value="<?php echo $PR_CODE; ?>" />
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
		                    <div class="col-sm-10">
		                    	<div class="input-group date">
		                        	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                        	<input type="text" name="PR_DATE1" class="form-control pull-left" id="datepicker" value="<?php echo $PR_DATE; ?>" style="width:106px" onChange="getPR_NUM(this.value)" disabled>
		                    	</div>
		                    	<input type="hidden" class="form-control" style="min-width:150px; max-width:150px; text-align:left" id="PR_DATE" name="PR_DATE" size="5" value="<?php echo $PR_DATE; ?>" />
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?>&nbsp;</label>
		                    <div class="col-sm-10">
		                    	<select name="PRJCODE" id="PRJCODE" class="form-control" onChange="chooseProject()" disabled>
		                          <option value="none"> --- </option>
		                          <?php echo $i = 0;
		                            if($countPRJ > 0)
		                            {
		                                foreach($vwPRJ as $row) :
		                                    $PRJCODE1 	= $row->PRJCODE;
		                                    $PRJNAME 	= $row->PRJNAME;
		                                    ?>
		                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME"; ?></option>
		                                  <?php
		                                endforeach;
		                            }
		                            ?>
		                        </select>
		                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />                        
		                    </div>
		                </div>
		            	<div class="form-group" style="display: none;">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $JobName; ?></label>
		                    <div class="col-sm-10">
		                    	<input type="hidden" class="form-control" style="max-width:350px;" id="PR_REFNO" name="PR_REFNO" size="20" value="<?php echo $PR_REFNO; ?>" />
		                        <select name="PR_REFNOX" id="PR_REFNOX" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" onBlur="selJOB(this.value)" disabled>
		                        	<option value=""> --- </option>
									<?php
		                                /*$Disabled_1	= 0;
		                                $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                $resJob_1	= $this->db->query($sqlJob_1)->result();
		                                foreach($resJob_1 as $row_1) :
		                                    $JOBCODEID_1	= $row_1->JOBCODEID;
		                                    $JOBPARENT_1	= $row_1->JOBPARENT;
		                                    $JOBLEV_1		= $row_1->JOBLEV;
		                                    $JOBDESC_1		= $row_1->JOBDESC;
											
		                                    if($JOBLEV_1 == 1)
											{
												$space_level_1	= "";
											}
											elseif($JOBLEV_1 == 2)
											{
												$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											}
											elseif($JOBLEV_1 == 3)
											{
												$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											}
											elseif($JOBLEV_1 == 4)
											{
												$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											}
											elseif($JOBLEV_1 == 5)
											{
												$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											}
											
											$JIDExplode = explode('~', $PR_REFNO);
											$JOBCODE1	= '';
											$SELECTED	= 0;
											foreach($JIDExplode as $i => $key)
											{
												$JOBCODE1	= $key;
												if($JOBCODEID_1 == $JOBCODE1)
												{
													$SELECTED	= 1;
												}
											}
											
		                                    $sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                    $resC_2 		= $this->db->count_all($sqlC_2);
		                                    if($resC_2 > 0)
		                                        $Disabled_1 = 1;
											else
												$Disabled_1 = 0;
		                                    ?>
		                                    <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
		                                        <?php echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1"; ?>
		                                    </option>
		                                    <?php
		                                endforeach;*/
		                            ?>
		                        </select>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptDate ?> </label>
		                    <div class="col-sm-10">
		                        <div class="input-group date">
		                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                            <input type="text" name="PR_RECEIPTD1" class="form-control pull-left" id="datepicker1" value="<?php echo $PR_RECEIPTD; ?>" style="width:100px" disabled>
		                        </div>
		                    	<input type="hidden" class="form-control" style="min-width:150px; max-width:150px; text-align:left" id="PR_RECEIPTD" name="PR_RECEIPTD" size="5" value="<?php echo $PR_RECEIPTD; ?>" />
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
		                    <div class="col-sm-10">
		                    	<textarea name="PR_NOTE" class="form-control" id="PR_NOTE" cols="30" disabled><?php echo $PR_NOTE; ?></textarea>                        
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes; ?></label>
		                    <div class="col-sm-10">
		                    	<textarea name="PR_NOTE2" class="form-control" id="PR_NOTE2" cols="30"><?php echo $PR_NOTE2; ?></textarea>                        
		                    </div>
		                </div>
		            	<div id="revMemo" class="form-group" <?php if($PR_MEMO == '') { ?> style="display:none" <?php } ?>>
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $reviseNotes; ?></label>
		                    <div class="col-sm-10">
		                    	<textarea name="PR_MEMO" class="form-control" id="PR_MEMO" cols="30"><?php echo $PR_MEMO; ?></textarea>                        
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
		                    <div class="col-sm-10">
		                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $PR_STAT; ?>">
								<?php
									// START : FOR ALL APPROVAL FUNCTION
										if($disableAll == 0)
										{
											if($canApprove == 1)
											{
												$disButton	= 0;
												$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$PR_NUM' AND AH_APPROVER = '$DefEmp_ID'";
												$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
												if($resCAPPHE > 0)
													$disButton	= 1;
												?>
													<select name="PR_STAT" id="PR_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
														<option value="0"> --- </option>
														<option value="3"<?php if($PR_STAT == 3) { ?> selected <?php } ?>>Approved</option>
														<option value="4"<?php if($PR_STAT == 4) { ?> selected <?php } ?>>Revised</option>
														<option value="5"<?php if($PR_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
														<option value="7"<?php if($PR_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
													</select>
												<?php
											}
											else
											{
												?>
													<a href="" class="btn btn-<?php echo $statcoloer; ?> btn-xs" title="ssss">
														<?php echo $descApp; ?>
													</a>
												<?php
											}
										}
										else
										{
											?>
												<a href="" class="btn btn-danger btn-xs">
													Step approval not set.
												</a>
											<?php
										}
									// END : FOR ALL APPROVAL FUNCTION
									$theProjCode 	= $PRJCODE;
		                        	$url_AddItem	= site_url('c_purchase/c_pr180d0c/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
		                        ?>
		                        <input type="hidden" name="PR_VALUE" id="PR_VALUE" value="<?php echo $PR_VALUE; ?>">
		                      	<input type="hidden" name="PR_VALUEAPP" id="PR_VALUEAPP" value="<?php echo $PR_VALUEAPP; ?>">
		                    </div>
		                </div>
		                <script>
							function selStat(PRSTAT)
							{
								if(PRSTAT == 4 || PRSTAT == 5)
								{
									document.getElementById('revMemo').style.display = '';
								}
								else
								{
									document.getElementById('revMemo').style.display = 'none';
								}
							}
						</script>
		                <div class="form-group" style="display:none">
		                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                    <div class="col-sm-10">
		                        <script>
		                            var url = "<?php echo $url_AddItem;?>";
		                            function selectitem()
		                            {
		                                title = 'Select Item';
		                                w = 1000;
		                                h = 550;
		                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                var left = (screen.width/2)-(w/2);
		                                var top = (screen.height/2)-(h/2);
		                                return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                            }
		                        </script>
		                        <button class="btn btn-success" type="button" onClick="selectitem();">
		                        <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
		                        </button>
		                        </div>
		                </div>
		                <div class="row">
		                    <div class="col-md-12">
		                      <div class="box box-primary">
		                        <div class="search-table-outter">
		                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                                <tr style="background:#CCCCCC">
		                                  <th width="3%" height="25" rowspan="2" style="text-align:left; vertical-align: middle;">&nbsp;</th>
		                                  <th width="3%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $ItemCode ?> </th>
		                                  <th width="33%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
		                                  <th colspan="3" style="text-align:center; vertical-align: middle;"><?php echo $ItemQty; ?> </th>
		                                  <th rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Unit ?> </th>
		                                  <th width="24%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
		                                </tr>
		                                <tr style="background:#CCCCCC">
		                                  <th style="text-align:center;"><?php echo $Planning ?> </th>
		                                  <th style="text-align:center;"><?php echo $Requested ?> </th>
		                                  <th style="text-align:center"><?php echo $RequestNow ?></th>
		                                </tr>
		                                <?php					
		                                if($task == 'edit')
		                                {
		                                    $sqlDET	= "SELECT A.PR_NUM, A.PR_CODE, A.PRJCODE, A.PR_REFNO, A.ITM_CODE, A.SNCODE, A.ITM_UNIT, 
		                                                    A.PR_VOLM, A.PR_VOLM2, A.PO_VOLM, A.IR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC, 
		                                                    A.JOBCODEDET, A.JOBCODEID, A.ITM_VOLMBG, A.ITM_BUDG, A.JOBPARDESC,
		                                                    B.ITM_CODE_H, B.ITM_NAME, B.ISMAJOR, B.ITM_TYPE
		                                                FROM tbl_pr_detail A
		                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
		                                                        AND B.PRJCODE = '$PRJCODE'
		                                                WHERE PR_NUM = '$PR_NUM' 
		                                                    AND B.PRJCODE = '$PRJCODE'";
		                                    $result = $this->db->query($sqlDET)->result();
		                                    $i		= 0;
		                                    $j		= 0;
		                                    
		                                    $TOTMAJOR	= 0;
		                                    foreach($result as $row) :
		                                        $currentRow  	= ++$i;
		                                        $PR_NUM 		= $row->PR_NUM;
		                                        $PR_CODE 		= $row->PR_CODE;
		                                        $PRJCODE 		= $row->PRJCODE;
		                                        $PR_REFNO 		= $row->PR_REFNO;
		                                        $ITM_CODE_H		= $row->ITM_CODE_H;
		                                        $ITM_CODE 		= $row->ITM_CODE;
		                                        $ITM_NAME 		= $row->ITM_NAME;
		                                        $JOBPARDESC 	= $row->JOBPARDESC;
		                                        $SNCODE 		= $row->SNCODE;
		                                        $ITM_UNIT 		= $row->ITM_UNIT;
		                                        $PR_VOLM 		= $row->PR_VOLM;
		                                        $PR_VOLM2 		= $row->PR_VOLM2;
		                                        $PO_VOLM 		= $row->PO_VOLM;
		                                        $IR_VOLM 		= $row->IR_VOLM;
		                                        $PR_PRICE 		= $row->PR_PRICE;
		                                        $PR_TOTAL 		= $row->PR_TOTAL;
		                                        $PR_DESC 		= $row->PR_DESC;
		                                        $JOBCODEDET		= $row->JOBCODEDET;
		                                        $JOBCODEID 		= $row->JOBCODEID;
		                                        $ITM_VOLMBG		= $row->ITM_VOLMBG;
		                                        $ITM_BUDG 		= $row->ITM_BUDG;
		                                        $ISMAJOR 		= $row->ISMAJOR;
		                                        $ITM_TYPE 		= $row->ITM_TYPE;
		                                        $itemConvertion	= 1;
		                                        if($ISMAJOR == 1)
		                                        {
		                                            $TOTMAJOR	= $TOTMAJOR + 1;
		                                        }

		                                        if($JOBPARDESC == '')
		                                        {
	                                                $sqlJPAR	= "SELECT A.JOBDESC FROM tbl_joblist_detail A 
																	WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
																		WHERE B.JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE')";
	                                                $resJPAR	= $this->db->query($sqlJPAR)->result();
	                                                foreach($resJPAR as $rowJPAR) :
	                                                    $JOBPARENT	= $rowJPAR->JOBDESC;
	                                                    $JOBPARDESC	= $rowJPAR->JOBDESC;
	                                                endforeach;
		                                        }
		                            
		                                    /*	if ($j==1) {
		                                            echo "<tr class=zebra1>";
		                                            $j++;
		                                        } else {
		                                            echo "<tr class=zebra2>";
		                                            $j--;
		                                        }*/
		                                        ?> 
		                                        <tr id="tr_<?php echo $currentRow; ?>">
		                                        <td style="text-align:center; vertical-align: middle;">
		                                          <?php
		                                            if($PR_STAT == 1)
		                                            {
		                                                ?>
		                                                <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
		                                                <?php
		                                            }
		                                            else
		                                            {
		                                                echo "$currentRow.";
		                                            }
		                                          ?>
		                                        <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
		                                            <!-- Checkbox -->
		                                        </td>
		                                        <td style="text-align:left; vertical-align: middle;" nowrap> <!-- Item Code -->
		                                          <?php echo $ITM_CODE; ?>                                      
		                                          <input type="hidden" id="data<?php echo $currentRow; ?>PR_NUM" name="data[<?php echo $currentRow; ?>][PR_NUM]" value="<?php echo $PR_NUM; ?>" class="form-control" style="max-width:300px;">
		                                          <input type="hidden" id="data<?php echo $currentRow; ?>PR_CODE" name="data[<?php echo $currentRow; ?>][PR_CODE]" value="<?php echo $PR_CODE; ?>" class="form-control" style="max-width:300px;">
		                                          <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
		                                          <input type="hidden" id="data<?php echo $currentRow; ?>SNCODE" name="data[<?php echo $currentRow; ?>][SNCODE]" value="<?php echo $SNCODE; ?>" class="form-control" style="max-width:300px;">
		                                      	</td>
		                                      	<td style="text-align:left; vertical-align: middle;">
		                                        	<?php echo $ITM_NAME; ?> <!-- Item Name -->
		                                        	<div style="font-style: italic;">
												  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;<?=$JOBPARDESC?>
												  	</div>
		                                      	</td>
		                                        <?php
		                                            // CARI TOTAL REGUSEST BUDGET APPROVED
		                                            $TOTPRAMOUNT	= 0;
		                                            $TOTPRQTY		= 0;
		                                            $sqlTOTBUDG		= "SELECT DISTINCT SUM(A.PR_VOLM * A.PR_PRICE) AS TOTPRAMOUNT, 
		                                                                    SUM(A.PR_VOLM) AS TOTPRQTY
		                                                                FROM tbl_pr_detail A
		                                                                INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
		                                                                    AND B.PRJCODE = '$PRJCODE'
		                                                                WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' 
		                                                                    AND A.JOBCODEDET = '$JOBCODEDET' AND B.PR_STAT IN ('2','3','6')
		                                                                    AND A.PR_NUM != '$PR_NUM'";
		                                            $resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
		                                            foreach($resTOTBUDG as $rowTOTBUDG) :
		                                                $TOTPRAMOUNT	= $rowTOTBUDG->TOTPRAMOUNT;
		                                                $TOTPRQTY		= $rowTOTBUDG->TOTPRQTY;
		                                            endforeach;						
		                                            if($ITM_TYPE == 'SUBS')
		                                            {
		                                                $REQ_VOLM	= 0;
		                                                $REQ_AMOUNT	= 0;
		                                                $sqlREQ		= "SELECT REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail 
		                                                                WHERE PRJCODE = '$PRJCODE'
		                                                                    AND ITM_CODE = '$ITM_CODE_H'";
		                                                $resREQ		= $this->db->query($sqlREQ)->result();
		                                                foreach($resREQ as $rowREQ) :
		                                                    $TOTPRQTY		= $rowREQ->REQ_VOLM;
		                                                    $TOTPRAMOUNT	= $rowREQ->REQ_AMOUNT;
		                                                endforeach;												
		                                            }
		                                            
		                                            $TOT_USEBUDG	= $TOTPRAMOUNT;					// 15
		                                            $ITM_BUDG		= $row->ITM_BUDG;				// 16
		                                            if($ITM_BUDG == '')
		                                                $ITM_BUDG	= 0;										
		                                            
		                                            $ITM_VOLM	= 0;
		                                            $ADD_VOLM	= 0;
		                                            
		                                            $sqlITM		= "SELECT A.ITM_VOLM, A.ADD_VOLM
		                                                            FROM tbl_joblist_detail A
		                                                            INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
		                                                                AND B.PRJCODE = '$PRJCODE'
		                                                            WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','I','T') 
		                                                            AND A.ITM_CODE = '$ITM_CODE'
		                                                            AND A.JOBPARENT = '$JOBCODEID'";
		                                                            
		                                            $BUDG_VOLM		= 0;
		                                            $ITM_BUDQTY		= 0;
		                                            $resITM			= $this->db->query($sqlITM)->result();
		                                            foreach($resITM as $rowITM) :
		                                                $ITM_VOLM	= $rowITM->ITM_VOLM;
		                                                $ADD_VOLM	= $rowITM->ADD_VOLM;
		                                                $BUDG_VOLM	= $ITM_VOLM + $ADD_VOLM;
		                                            endforeach;
		                                            $BUDG_VOLM		= $ITM_VOLMBG + $ADD_VOLM;									
		                                            $ITM_BUDQTY		= $BUDG_VOLM;
		                                            
		                                            // SISA QTY PR
		                                            $REMPRQTY		= $ITM_BUDQTY - $TOTPRQTY;
		                                        ?>
		                                        <td style="text-align:right; vertical-align: middle;"> <!-- Item Bdget -->
		                                        	<?php print number_format($ITM_BUDQTY, $decFormat); ?>
		                                          	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_BUDGQTYx<?php echo $currentRow; ?>" id="ITM_BUDGQTYx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_BUDQTY, $decFormat); ?>" disabled >
		                                          	<input type="hidden" style="text-align:right" name="ITM_BUDGQTY<?php echo $currentRow; ?>" id="ITM_BUDGQTY<?php echo $currentRow; ?>" value="<?php echo $ITM_BUDQTY; ?>" >
		                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_BUDG" name="data[<?php echo $currentRow; ?>][ITM_BUDG]" value="<?php echo $ITM_BUDG; ?>">
		                                      	</td>
		                                     	<td style="text-align:right; vertical-align: middle;">  <!-- Item Requested FOR INFORMATION ONLY -->
		                                     		<?php print number_format($TOTPRQTY, $decFormat); ?>
		                                        	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTED<?php echo $currentRow; ?>" id="ITM_REQUESTED<?php echo $currentRow; ?>" value="<?php print $TOTPRAMOUNT; ?>" >
		                                        	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTPRQTY<?php echo $currentRow; ?>" id="TOTPRQTY<?php echo $currentRow; ?>" value="<?php print $TOTPRQTY; ?>" >
		                                        	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTPRQTYx<?php echo $currentRow; ?>" id="TOTPRQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRQTY, $decFormat); ?>" disabled >
		                                        	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTEDx<?php echo $currentRow; ?>" id="ITM_REQUESTEDx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRAMOUNT, $decFormat); ?>" disabled ></td>
		                                     	<td style="text-align:right; vertical-align: middle;"> <!-- Item Request Now -- PR_VOLM -->
		                                     		<?php print number_format($PR_VOLM, $decFormat); ?>
		                                       		<input type="hidden" name="PR_VOLM<?php echo $currentRow; ?>" id="PR_VOLM<?php echo $currentRow; ?>" value="<?php print number_format($PR_VOLM, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" disabled >
		                                       		<input type="hidden" name="data[<?php echo $currentRow; ?>][PR_VOLM]" id="data<?php echo $currentRow; ?>PR_VOLM" value="<?php print $PR_VOLM; ?>" class="form-control" style="max-width:300px;" ><input type="hidden" name="data[<?php echo $currentRow; ?>][PR_PRICE]" id="data<?php echo $currentRow; ?>PR_PRICE" value="<?php print $PR_PRICE; ?>" class="form-control" style="max-width:300px;" >
		                                       		<input type="hidden" name="data[<?php echo $currentRow; ?>][PR_TOTAL]" id="data<?php echo $currentRow; ?>PR_TOTAL" value="<?php print $PR_TOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                       		<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
		                                        </td>
		                                        <td style="text-align:left; vertical-align: middle;">
		                                          	<?php echo $ITM_UNIT; ?>
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
		                                        	<!-- Item Unit Type -- ITM_UNIT --></td>
		                                       <td style="text-align:left; vertical-align: middle;">
		                                        	<?php print $PR_DESC; ?>
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][PR_DESC]" id="data<?php echo $currentRow; ?>PR_DESC" size="20" value="<?php print $PR_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" disabled>
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
		                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>"></td>
		                                  	</tr>
		                                    <?php
		                                    endforeach;
		                                }
		                                if($task == 'add')
		                                {
		                                    ?>
		                                      <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                                  <?php
		                                }
		                                ?>
		                                <input type="hidden" name="TOTMAJOR" id="TOTMAJOR" value="<?php echo $TOTMAJOR; ?>">
		                            </table>
		                      	</div>
		                      </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <div class="col-sm-offset-2 col-sm-10">
		                        <?php
									if($disableAll == 0)
									{
										if(($PR_STAT == 2 || $PR_STAT == 7) && $canApprove == 1)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
		                            $backURL	= site_url('c_purchase/c_pr180d0c/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                            echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
					</form>
			        <div class="col-md-12">
						<?php
                            $DOC_NUM	= $PR_NUM;
                            $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                            $resCAPPH	= $this->db->count_all($sqlCAPPH);
							$sqlAPP		= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
											AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
							$resAPP		= $this->db->query($sqlAPP)->result();
							foreach($resAPP as $rowAPP) :
								$MAX_STEP		= $rowAPP->MAX_STEP;
								$APPROVER_1		= $rowAPP->APPROVER_1;
								$APPROVER_2		= $rowAPP->APPROVER_2;
								$APPROVER_3		= $rowAPP->APPROVER_3;
								$APPROVER_4		= $rowAPP->APPROVER_4;
								$APPROVER_5		= $rowAPP->APPROVER_5;
							endforeach;
							
                        	if($resCAPP == 0)
                        	{
                        		if($LangID == 'IND')
								{
									$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
								}
								else
								{
									$zerSetApp	= "There are no arrangements for the approval of this document.";
								}
                        		?>
                        			<div class="alert alert-warning alert-dismissible">
					                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                <?php echo $zerSetApp; ?>
					              	</div>
                        		<?php
                        	}
                        ?>
		                <div class="row">
		                    <div class="col-md-12">
		                        <div class="box box-danger collapsed-box">
		                            <div class="box-header with-border">
		                                <h3 class="box-title"><?php echo $Approval; ?></h3>
		                                <div class="box-tools pull-right">
		                                    <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
		                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                                    </button>
		                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
		                                    </button>
		                                </div>
		                            </div>
		                            <div class="box-body">
		                            <?php
										$SHOWOTH		= 0;
										$AH_ISLAST		= 0;
										$APPROVER_1A	= 0;
										$APPROVER_2A	= 0;
										$APPROVER_3A	= 0;
										$APPROVER_4A	= 0;
										$APPROVER_5A	= 0;
		                                if($APPROVER_1 != '')
		                                {
		                                    $boxCol_1	= "red";
		                                    $sqlCAPPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
		                                    $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
		                                    if($resCAPPH_1 > 0)
		                                    {
		                                        $boxCol_1	= "green";
		                                        $Approver	= $Approved;
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_1	= "SELECT AH_APPROVED, AH_ISLAST 
																FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
		                                        $resAPPH_1	= $this->db->query($sqlAPPH_1)->result();
		                                        foreach($resAPPH_1 as $rowAPPH_1):
		                                            $APPROVED_1	= $rowAPPH_1->AH_APPROVED;
		                                            $AH_ISLAST	= $rowAPPH_1->AH_ISLAST;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_1 == 0)
		                                    {
												$Approver	= $NotYetApproved;
												$class		= "glyphicon glyphicon-remove-sign";
												$APPROVED_1	= "Not Set";
												
												$sqlCAPPH_1A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
												$resCAPPH_1A	= $this->db->count_all($sqlCAPPH_1A);
												if($resCAPPH_1A > 0)
												{
													$SHOWOTH	= 1;
													$APPROVER_1A= 1;
													$EMPN_1A	= '';
													$AH_ISLAST1A=0;
													$APPROVED_1A= '0000-00-00';
													$boxCol_1A	= "green";
													$Approver1A	= $Approved;
													$class1A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_1A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
													$resAPPH_1A	= $this->db->query($sqlAPPH_1A)->result();
													foreach($resAPPH_1A as $rowAPPH_1A):
														$EMPN_1A		= $rowAPPH_1A->COMPNAME;
														$AH_ISLAST1A	= $rowAPPH_1A->AH_ISLAST;
														$APPROVED_1A	= $rowAPPH_1A->AH_APPROVED;
													endforeach;
												}
		                                    }
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_1; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">

															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_1", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_1; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_2 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_2	= "red";
		                                    $sqlCAPPH_2	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
		                                    $resCAPPH_2	= $this->db->count_all($sqlCAPPH_2);
		                                    if($resCAPPH_2 > 0)
		                                    {
		                                        $boxCol_2	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_2	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
		                                        $resAPPH_2	= $this->db->query($sqlAPPH_2)->result();
		                                        foreach($resAPPH_2 as $rowAPPH_2):
		                                            $APPROVED_2	= $rowAPPH_2->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_2 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_2	= "Not Set";
												
												$sqlCAPPH_2A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
												$resCAPPH_2A	= $this->db->count_all($sqlCAPPH_2A);
												if($resCAPPH_2A > 0)
												{
													$APPROVER_2A= 1;
													$EMPN_2A	= '';
													$AH_ISLAST2A=0;
													$APPROVED_2A= '0000-00-00';
													$boxCol_2A	= "green";
													$Approver2A	= $Approved;
													$class2A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_2A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
													$resAPPH_2A	= $this->db->query($sqlAPPH_2A)->result();
													foreach($resAPPH_2A as $rowAPPH_2A):
														$EMPN_2A		= $rowAPPH_2A->COMPNAME;
														$AH_ISLAST2A	= $rowAPPH_2A->AH_ISLAST;
														$APPROVED_2A	= $rowAPPH_2A->AH_APPROVED;
													endforeach;
												}
											}
		                                    
		                                    /*if($resCAPPH == 0)
		                                    {
		                                        $Approver	= $Awaiting;
		                                        $boxCol_2	= "yellow";
		                                        $class		= "glyphicon glyphicon-info-sign";
		                                    }*/
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_2; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_2", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_2; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_3 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_3	= "red";
		                                    $sqlCAPPH_3	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
		                                    $resCAPPH_3	= $this->db->count_all($sqlCAPPH_3);
		                                    if($resCAPPH_3 > 0)
		                                    {
		                                        $boxCol_3	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_3	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
		                                        $resAPPH_3	= $this->db->query($sqlAPPH_3)->result();
		                                        foreach($resAPPH_3 as $rowAPPH_3):
		                                            $APPROVED_3	= $rowAPPH_3->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_3 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_3	= "Not Set";
												
												$sqlCAPPH_3A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
												$resCAPPH_3A	= $this->db->count_all($sqlCAPPH_3A);
												if($resCAPPH_3A > 0)
												{
													$APPROVER_3A= 1;
													$EMPN_3A	= '';
													$AH_ISLAST3A=0;
													$APPROVED_3A= '0000-00-00';
													$boxCol_3A	= "green";
													$Approver3A	= $Approved;
													$class3A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_3A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
													$resAPPH_3A	= $this->db->query($sqlAPPH_3A)->result();
													foreach($resAPPH_3A as $rowAPPH_3A):
														$EMPN_3A		= $rowAPPH_3A->COMPNAME;
														$AH_ISLAST3A	= $rowAPPH_3A->AH_ISLAST;
														$APPROVED_3A	= $rowAPPH_3A->AH_APPROVED;
													endforeach;
												}
		                                    }
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_3; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_3", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_3; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_4 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_4	= "red";
		                                    $sqlCAPPH_4	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
		                                    $resCAPPH_4	= $this->db->count_all($sqlCAPPH_4);
		                                    if($resCAPPH_4 > 0)
		                                    {
		                                        $boxCol_4	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_4	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
		                                        $resAPPH_4	= $this->db->query($sqlAPPH_4)->result();
		                                        foreach($resAPPH_4 as $rowAPPH_4):
		                                            $APPROVED_4	= $rowAPPH_4->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_4 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_4	= "Not Set";
												
												$sqlCAPPH_4A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
												$resCAPPH_4A	= $this->db->count_all($sqlCAPPH_4A);
												if($resCAPPH_4A > 0)
												{
													$APPROVER_4A= 1;
													$EMPN_4A	= '';
													$AH_ISLAST4A=0;
													$APPROVED_4A= '0000-00-00';
													$boxCol_4A	= "green";
													$Approver4A	= $Approved;
													$class4A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_4A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
													$resAPPH_4A	= $this->db->query($sqlAPPH_4A)->result();
													foreach($resAPPH_4A as $rowAPPH_4A):
														$EMPN_4A		= $rowAPPH_4A->COMPNAME;
														$AH_ISLAST4A	= $rowAPPH_4A->AH_ISLAST;
														$APPROVED_4A	= $rowAPPH_4A->AH_APPROVED;
													endforeach;
												}
		                                    }
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_4; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_4", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_4; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_5 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_5	= "red";
		                                    $sqlCAPPH_5	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
		                                    $resCAPPH_5	= $this->db->count_all($sqlCAPPH_5);
		                                    if($resCAPPH_5 > 0)
		                                    {
		                                        $boxCol_5	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_5	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
		                                        $resAPPH_5	= $this->db->query($sqlAPPH_5)->result();
		                                        foreach($resAPPH_5 as $rowAPPH_5):
		                                            $APPROVED_5	= $rowAPPH_5->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_5 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_5	= "Not Set";
												
												$sqlCAPPH_5A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
												$resCAPPH_5A	= $this->db->count_all($sqlCAPPH_5A);
												if($resCAPPH_5A > 0)
												{
													$APPROVER_5A= 1;
													$EMPN_5A	= '';
													$AH_ISLAST5A=0;
													$APPROVED_5A= '0000-00-00';
													$boxCol_5A	= "green";
													$Approver5A	= $Approved;
													$class5A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_5A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
													$resAPPH_5A	= $this->db->query($sqlAPPH_5A)->result();
													foreach($resAPPH_5A as $rowAPPH_5A):
														$EMPN_5A		= $rowAPPH_5A->COMPNAME;
														$AH_ISLAST5A	= $rowAPPH_5A->AH_ISLAST;
														$APPROVED_5A	= $rowAPPH_5A->AH_APPROVED;
													endforeach;
												}
		                                    }
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_5; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_5", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_5; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                            ?>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <?php if($SHOWOTH == 1) { ?>
		                    <div class="row">
		                        <div class="col-md-12">
		                            <div class="box box-danger collapsed-box">
		                                <div class="box-header with-border">
		                                    <h3 class="box-title"><?php echo $InOthSett; ?></h3>
		                                    <div class="box-tools pull-right">
		                                        <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
		                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                                        </button>
		                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
		                                        </button>
		                                    </div>
		                                </div>
		                                <div class="box-body">
		                                <?php
		                                    if($APPROVER_1A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_1A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class1A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver1A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_1A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_1A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_2A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_2A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class2A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver2A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_2A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_2A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_3A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_3A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class3A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver3A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_3A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_3A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_4A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_4A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class4A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver4A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_4A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_4A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_5A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_5A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class5A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver5A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_5A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_5A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                ?>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                <?php } ?>
			        </div>
                	<?php
						$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                		if($DefEmp_ID == 'D15040004221')
                        	echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
		    	</div>
		    </div>
		</section>
	</body>
</html>

<script>
	$(function () {
	//Initialize Select2 Elements
	$(".select2").select2();
	
	//Datemask dd/mm/yyyy
	$("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
	//Datemask2 mm/dd/yyyy
	$("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
	//Money Euro
	$("[data-mask]").inputmask();
	
	//Date range picker
	$('#reservation').daterangepicker();
	//Date range picker with time picker
	$('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
	//Date range as a button
	$('#daterange-btn').daterangepicker(
		{
		  ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		  },
		  startDate: moment().subtract(29, 'days'),
		  endDate: moment()
		},
		function (start, end) {
		  $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		}
	);
	
	//Date picker
	$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	$('#datepicker').datepicker({
	  autoclose: true,
	  format: 'dd/mm/yyyy'
	});
	
	//Date picker
	$('#datepicker1').datepicker({
	  autoclose: true,
	  format: 'dd/mm/yyyy'
	});
	
	//iCheck for checkbox and radio inputs
	$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
	  checkboxClass: 'icheckbox_minimal-blue',
	  radioClass: 'iradio_minimal-blue'
	});
	//Red color scheme for iCheck
	$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
	  checkboxClass: 'icheckbox_minimal-red',
	  radioClass: 'iradio_minimal-red'
	});
	//Flat red color scheme for iCheck
	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	  checkboxClass: 'icheckbox_flat-green',
	  radioClass: 'iradio_flat-green'
	});
	
	//Colorpicker
	$(".my-colorpicker1").colorpicker();
	//color picker with addon
	$(".my-colorpicker2").colorpicker();
	
	//Timepicker
	$(".timepicker").timepicker({
	  showInputs: false
	});
	});
  
	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec); 
	}
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}

	var selectedRows = 0;
	function pickThis(thisobj,ke)
	{
		if(thisobj.checked)
		{
			document.getElementById('chk'+thisobj.value).checked = true;
		}
		else
		{
			document.getElementById('chk'+thisobj.value).checked = false;
		}
		
		objTable = document.getElementById('tbl');
		intTable = objTable.rows.length;
		var NumOfRows = intTable-1;
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		
		if (selectedRows==NumOfRows) 
		{
			document.frm.HChkAllItem.checked = true;
		}
		else
		{
			document.frm.HChkAllItem.checked = false;
		}
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function checkForm()
	{
		PR_STAT		= document.getElementById('PR_STAT').value;
		PR_NOTE2	= document.getElementById('PR_NOTE2').value;
		if(PR_NOTE2 == '')
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#PR_NOTE2').focus();
            });
			return false;
		}
		
		if(PR_STAT == 0)
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			});
			document.getElementById('PR_STAT').focus();
			return false;
		}

		if(PR_STAT == 4 || PR_STAT == 5)
		{
			PR_MEMO		= document.getElementById('PR_MEMO').value;
			if(PR_MEMO == '')
			{
				swal('<?php echo $alert4; ?>',
				{
					icon: "warning",
				})
				.then(function()
	            {
	                swal.close();
	                $('#PR_MEMO').focus();
	            });
				return false;
			}
		}

		var variable = document.getElementById('btnSave');
		if (typeof variable !== 'undefined' && variable !== null)
		{
			document.getElementById('btnSave').style.display 	= 'none';
		}
	}
	
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
	
	function validateDouble(vcode, SNCODE) 
	{
		var thechk=new Array();
		var duplicate = false;
		
		var jumchk = document.getElementsByName('chk').length;
		if (jumchk!=null) 
		{
			thechk=document.getElementsByName('chk');
			panjang = parseInt(thechk.length);
		} 
		else 
		{
			thechk[0]=document.getElementsByName('chk');
			panjang = 0;
		}
		var panjang = panjang + 1;
		for (var i=0;i<panjang;i++) 
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1= document.getElementById('data'+i+'ITM_CODE').value;
				var iparent= document.getElementById('data'+i+'SNCODE').value;
				if (elitem1 == vcode && iparent == SNCODE)
				{
					if (elitem1 == vcode) 
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		return duplicate;
	}
	
	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		thisVal 			= parseFloat(Math.abs(thisVal1.value))
		//tempTotMax1 		= parseFloat(document.getElementById('tempTotMax'+row).value);
		ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);
		PR_PRICE			= parseFloat(document.getElementById('data'+row+'PR_PRICE').value);
		
		// Start : 7 Mei 2015 : Permintaan tidak boleh melebihi		
		reqQty1				= document.getElementById('PR_VOLM'+row).value;
		reqQty1x 			= parseFloat(reqQty1);
		document.getElementById('data'+row+'PR_VOLM').value = reqQty1x;
		document.getElementById('PR_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
		
		itemConvertion		= document.getElementById('itemConvertion'+row).value;
		
		reqQty2 			= reqQty1 * itemConvertion;
		
		//document.getElementById('PR_VOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty2)),decFormat));
		
		//tempTotMaxx = parseFloat(Math.abs(tempTotMax1));
		totITMPrice	= parseFloat(reqQty1x) * parseFloat(PR_PRICE);
		//if(reqQty1x > tempTotMaxx)
		var TOTPRQTY		= document.getElementById('TOTPRQTY'+row).value;
		var remItmQty		= parseFloat(ITM_BUDG) - parseFloat(TOTPRQTY);
		
		if(totITMPrice > ITM_BUDG)
		{
			vtotITMPrice	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totITMPrice)),decFormat));
			swal('<?php echo $greaterBud; ?> '+vtotITMPrice,
			{
				icon: "warning",
			});
			document.getElementById('data'+row+'PR_VOLM').value = TOTPRQTY;
			document.getElementById('PR_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRQTY)),decFormat))
			//document.getElementById('PR_VOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(tempTotMaxx)),decFormat))
			return false;
		}
		document.getElementById('data'+row+'PR_VOLM').value = reqQty1x;
		document.getElementById('PR_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
		//document.getElementById('PR_VOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function submitForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		//var venCode 	= document.getElementById('Vend_Code').value;
		var isApproved 	= document.getElementById('isApproved').value;
		
		if(isApproved == 0)
		{
			for(i=1;i<=totrow;i++)
			{
				var PR_VOLM = parseFloat(document.getElementById('PR_VOLM'+i).value);
				if(PR_VOLM == 0)
				{
					swal('<?php echo $inpMRQTY; ?>',
					{
						icon: "warning",
					});
					document.getElementById('PR_VOLM'+i).value = '0';
					document.getElementById('PR_VOLM'+i).focus();
					return false;
				}
			}
			/*if(venCode == 0)
			{
				swal('Please select a Vendor.');
				document.getElementById('selVend_Code').focus();
				return false;
			}*/
			if(totrow == 0)
			{
				swal('<?php echo $inpMRD; ?>',
				{
					icon: "warning",
				});
				return false;		
			}
			else
			{
				document.frm.submit();
			}
		}
		else
		{
			swal('Can not update this document. The document has Confirmed.');
			return false;
		}
	}
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

	// Right side column. contains the Control Panel
	//______$this->load->view('template/aside');

	//______$this->load->view('template/js_data');

	//______$this->load->view('template/foot');
?>