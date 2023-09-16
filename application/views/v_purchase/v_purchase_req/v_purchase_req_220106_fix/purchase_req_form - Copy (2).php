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
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$DEPCODE 	= $this->session->userdata['DEPCODE'];

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
	$LangID 	= $this->session->userdata['LangID'];
	if(isset($Pattern_Position))
	{
		$isSetDocNo = 1;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive 	= date('Y');
			$Pattern_MonthAktive 	= date('m');
			$Pattern_DateAktive 	= date('d');
		}
		$year 						= (int)$Pattern_YearAktive;
		$month 						= (int)$Pattern_MonthAktive;
		$date 						= (int)$Pattern_DateAktive;
	}
	else
	{
		$isSetDocNo = 0;
		$Pattern_Code 			= "XXX";
		$Pattern_Length 		= "5";
		$useYear 				= 1;
		$useMonth 				= 1;
		$useDate 				= 1;
		
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		$year 					= (int)$Pattern_YearAktive;
		$month 					= (int)$Pattern_MonthAktive;
		$date 					= (int)$Pattern_DateAktive;
		
		if($LangID == 'IND')
		{
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
		}
		else
		{
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
		}
	}
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;
	
	$yearCur	= date('Y');
	$sqlC		= "tbl_pr_header WHERE Patt_Year = $yearCur AND PRJCODE = '$PRJCODE'";
	$myCount 	= $this->db->count_all($sqlC);
	
	/*$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_pr_header
			WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$myMax 		= $myCount+1;
	$thisMonth 	= $month;
	
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
		if($len==1) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==4)
	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==5)
	{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0"; else $nol="";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0"; else $nol="";
	}
	$lastPatternNumb = $nol.$lastPatternNumb;
	
	
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;
	
	//$PACODE			= substr($lastPatternNumb, -4);
	$PACODE			= $lastPatternNumb;
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$PR_NUM			= "$DocNumber";
	
	//$PRCODE			= substr($lastPatternNumb, -4);
	$PRCODE			= $lastPatternNumb;
	$PRYEAR			= date('y');
	$PRMONTH		= date('m');
	$PR_CODE		= "PP.$PRCODE.$PRYEAR.$PRMONTH"; // MANUAL CODE
	
	$TRXDATEY 		= date('Y');
	$TRXDATEM 		= date('m');
	$TRXDATED 		= date('d');
	//$PR_DATE		= "$TRXDATEM/$TRXDATED/$TRXDATEY";
	$PR_DATE		= date('d/m/Y');
	$PR_RECEIPTD	= date('d/m/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$JOBCODE		= '';
	$PR_NOTE		= '';
	$PR_NOTE2		= '';
	$PR_STAT 		= 1;	
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;
	$PR_VALUE		= 0;
	$PR_VALUEAPP	= 0;
	$PR_REFNO		= '';
	$JOBCODE1		= $PR_REFNO;
	$PR_MEMO		= '';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_pr_header~$Pattern_Length";
	$dataTarget		= "PR_CODE";
}
else
{
	$isSetDocNo = 1;
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
	$JOBCODE1			= $PR_REFNO;
	$Patt_Year			= $default['Patt_Year'];
	$Patt_Month			= $default['Patt_Month'];
	$Patt_Date			= $default['Patt_Date'];
	$Patt_Number		= $default['Patt_Number'];
	$lastPatternNumb1	= $default['Patt_Number'];
}
	
if(isset($_POST['JOBCODE1']))
{
	$PR_REFNO	= $_POST['JOBCODE1'];
}

// REJECT FUNCTION
	// CEK ACCESS OTORIZATION
		$resAPP	= 0;
		$sqlAPP	= "tbl_docstepapp_det WHERE MENU_CODE = 'MN018'
					AND PRJCODE = '$PRJCODE' AND APPROVER_1 = '$DefEmp_ID'";
		$resAPP	= $this->db->count_all($sqlAPP);
	// CEK IR
		$DOC_NO	= '';
		$sqlIRC	= "tbl_po_header WHERE PR_NUM = '$PR_NUM' AND PO_STAT NOT IN (5,9)";
		$isUSED	= $this->db->count_all($sqlIRC);
		if($isUSED > 0)
		{
			$sqlPO 	= "SELECT PO_CODE FROM tbl_po_header WHERE PR_NUM = '$PR_NUM' AND PO_STAT != 5 LIMIT 1";
			$resPO	= $this->db->query($sqlPO)->result();
			foreach($resPO as $rowPO):
				$DOC_NO	= $rowPO->PO_CODE;
			endforeach;
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
            $vers   = $this->session->userdata['vers'];

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
		$ISDELETE	= $this->session->userdata['ISDELETE'];
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
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
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
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'BudName')$BudName = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'inpMRD')$inpMRD = $LangTransl;
			if($TranslCode == 'inpMRQTY')$inpMRQTY = $LangTransl;
			if($TranslCode == 'greaterBud')$greaterBud = $LangTransl;
			if($TranslCode == 'budEmpt')$budEmpt = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;

			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;

			if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'Substitute')$Substitute = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$subTitleH	= "Tambah";
			$subTitleD	= "permintaan pembelian";
			$isManual	= "Centang untuk kode manual.";
			$alertREJ	= "Tidak dapat direject. Sudah digunakan oleh Dokumen No.: ";
			$alert1		= "Silahkan pilih ".$BudName."";
			$alert2		= "Silahkan pilih salah satu item yang akan diminta";
		}
		else
		{
			$subTitleH	= "Add";
			$subTitleD	= "purchase request";
			$isManual	= "Check to manual code.";
			$alertREJ	= "Can not be rejected. Used by document No.: ";
			$alert1		= "Please select ".$BudName."";
			$alert2		= "Please select an item will be requested";
		}
		
		$secAddURL	= site_url('c_purchase/c_pr180d0c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_purchase/c_pr180d0c/genCode/'; // Generate Code
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - PR_VALUE
			$EMPN_1 	= "";
			$EMPN_2 	= "";
			$EMPN_3 	= "";
			$EMPN_4		= "";
			$EMPN_5 	= "";

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
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $subTitleH; ?>
			    <small><?php echo "$mnName - $PRJNAME"; ?></small>
			</h1>
		</section>

		<section class="content">
		    <div class="row">
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
	            <!-- after get Supplier code -->
	            <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
	                <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE; ?>" />
	                <input type="text" name="JOBCODE1" id="JOBCODE1" value="<?php echo $PR_REFNO; ?>" />
	                <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
	            </form>
	            <!-- End -->
	            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
	            	<div class="col-md-12">
	                	<div class="box box-primary">
	                		<div class="box-body">
		                		<br>
				            	<input type="hidden" name="DEPCODE" id="DEPCODE" value="<?php echo $DEPCODE; ?>">
				                <input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
				                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
				                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
				                <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
				                <input type="Hidden" name="rowCount" id="rowCount" value="0">
				                <?php if($isSetDocNo == 0) { ?>
					                <div class="form-group">
					                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
					                    <div class="col-sm-10">
					                        <div class="alert alert-danger alert-dismissible">
					                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
					                            <?php echo $docalert2; ?>
					                        </div>
					                    </div>
					                </div>
				                <?php } ?>
				                <div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $RequestNo; ?></label>
				                    <div class="col-sm-10">
				                        <input type="text" class="form-control" style="max-width:200px;text-align:left" name="PR_NUM1" id="PR_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
				                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PR_NUM" id="PR_NUM" size="30" value="<?php echo $DocNumber; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PR_Code; ?></label>
				                    <div class="col-sm-10">
				                    	<input type="hidden" class="form-control" style="text-align:left" id="PR_CODE" name="PR_CODE" size="5" value="<?php echo $PR_CODE; ?>" />
				                    	<input type="text" class="form-control" style="text-align:left" id="PR_CODEX" name="PR_CODEX" size="5" value="<?php echo $PR_CODE; ?>" disabled />
				                    </div>
				                </div>
				                <div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PR_Code; ?></label>
				                    <div class="col-sm-10">
				                        <label>
				                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
				                        </label>
				                        <label style="font-style:italic">
				                            <?php echo $isManual; ?>
				                        </label>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
				                    <div class="col-sm-10">
				                        <div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PR_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $PR_DATE; ?>" style="width:106px" onChange="getPR_NUM(this.value)"></div>
				                    </div>
				                </div>
				                <script>
				                    function getPR_NUM(selDate)
				                    {
				                        document.getElementById('PRDate').value = selDate;
				                        document.getElementById('dateClass').click();
				                    }
				    
				                    /*$(document).ready(function()
				                    {
				                        $(".tombol-date").click(function()
				                        {
				                            var add_PR	= "<?php echo $secGenCode; ?>";
				                            var formAction 	= $('#sendDate')[0].action;
				                            var data = $('.form-user').serialize();
				                            $.ajax({
				                                type: 'POST',
				                                url: formAction,
				                                data: data,
				                                success: function(response)
				                                {
				                                    var myarr = response.split("~");
				                                    document.getElementById('PR_NUM1').value = myarr[0];
				                                    document.getElementById('PR_CODE').value = myarr[1];
				                                    document.getElementById('PR_CODEX').value = myarr[1];
				                                }
				                            });
				                        });
				                    });*/
				                </script>
				                <div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
				                    <div class="col-sm-10">
				                        <select name="PRJCODE_X" id="PRJCODE_X" class="form-control" onChange="chooseProject()" disabled>
				                          <option value="none">--- None ---</option>
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
				                            else
				                            {
				                                ?>
				                                  <option value="none">--- No Unit Found ---</option>
				                              <?php
				                            }
				                            ?>
				                        </select>
				                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />                        
				                    </div>
				                </div>
				                <div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $BudName; ?></label>
				                    <div class="col-sm-10">
				                        <select name="PR_REFNO[]" id="PR_REFNO" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $BudName; ?>" onKeyPress="getJob(event);" onBlur="selJOB(this.value)">
				                            <option value="0"> --- </option>
				                            <?php
				                                /*$Disabled_1	= 0;
				                                $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1' AND PRJCODE = '$PRJCODE' LIMIT 1000";
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
				                                    
				                                    //$sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
				                                    // seharusnya mendeteksi apakah header ini memiliki turunan detail atau tidak, kalau ada wajib di enabled.
				                                    $sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND PRJCODE = '$PRJCODE'";
				                                    $resC_2 		= $this->db->count_all($sqlC_2);
				                                    if($resC_2 == 0)
				                                        $Disabled_1 = 0;
				                                    else
				                                        $Disabled_1 = 1;
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
				                <script>
				                	<?php
				                		$secJList 	= base_url().'index.php/c_purchase/c_pr180d0c/JList/?id=';
										$JList 		= "$secJList~PRJCODE~$PRJCODE";
				                	?>
				                	/*$( "#PR_REFNO" ).keypress(function() {
									  	console.log( "Handler for .keypress() called." );
									});*/

									/*$(document).ready(function(){
										var collID	= "<?php echo $JList; ?>";
								        var myarr 	= collID.split("~");

								        var url 	= myarr[0];

								        $.ajax({
								            type: 'POST',
								            url: url,
								            data: {collID: collID},
								            success: function(response)
								            {
								            	swal(response, 
												{
													icon: "success",
												});
								                //$('#example').DataTable().ajax.reload();
								            }
								        });
									});*/

				                    function selJOB(PR_REFNO) 
				                    {
				                        document.getElementById("JOBCODE1").value = PR_REFNO;
				                        PRJCODE	= document.getElementById("PRJCODE").value
				                        document.getElementById("PRJCODE1").value = PRJCODE;
				                        /*document.frmsrch1.submitSrch1.click();*/

			                            /*var add_PR	= "";
			                            var formAction 	= $('#sendDate')[0].action;
			                            var data = $('.form-user').serialize();
			                            $.ajax({
			                                type: 'POST',
			                                url: formAction,
			                                data: data,
			                                success: function(response)
			                                {
			                                    var myarr = response.split("~");
			                                    document.getElementById('PR_NUM1').value = myarr[0];
			                                    document.getElementById('PR_CODE').value = myarr[1];
			                                    document.getElementById('PR_CODEX').value = myarr[1];
			                                }
			                            });*/
				                    }
				                </script>
				                <div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptDate ?> </label>
				                    <div class="col-sm-10">
				                        <div class="input-group date">
				                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                            <input type="text" name="PR_RECEIPTD" class="form-control pull-left" id="datepicker1" value="<?php echo $PR_RECEIPTD; ?>" style="width:100px">
				                        </div>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-10">
				                        <textarea name="PR_NOTE" class="form-control" id="PR_NOTE" cols="30"><?php echo $PR_NOTE; ?></textarea>                        
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes; ?></label>
				                    <div class="col-sm-10">
				                        <textarea name="PR_NOTE2" class="form-control" id="PR_NOTE2" disabled><?php echo $PR_NOTE2; ?></textarea>                        
				                    </div>
				                </div>
				                <div id="revMemo" class="form-group" <?php if($PR_MEMO == '') { ?> style="display:none" <?php } ?>>
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $reviseNotes; ?></label>
				                    <div class="col-sm-10">
				                        <textarea name="PR_MEMO" class="form-control" id="PR_MEMO" disabled><?php echo $PR_MEMO; ?></textarea>                        
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-10">
				                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $PR_STAT; ?>">
				                        <?php
				                            $isDisabled = 1;
				                            if($PR_STAT == 1 || $PR_STAT == 4)
				                            {
				                                $isDisabled = 0;
				                            }
				                            ?>
				                                <select name="PR_STAT" id="PR_STAT" class="form-control select2" onChange="chkSTAT(this.value)">
				                                    <?php
				                                    $disableBtn	= 0;
				                                    if($PR_STAT == 5 || $PR_STAT == 6 || $PR_STAT == 9)
				                                    {
				                                        $disableBtn	= 1;
				                                    }
				                                    if($PR_STAT != 1 AND $PR_STAT != 4)
				                                    {
				                                        ?>
				                                            <option value="1"<?php if($PR_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
				                                            <option value="2"<?php if($PR_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
				                                            <option value="3"<?php if($PR_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
				                                            <option value="4"<?php if($PR_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
				                                            <option value="5"<?php if($PR_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
				                                            <option value="6"<?php if($PR_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
				                                            <option value="7"<?php if($PR_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
				                                            <option value="9"<?php if($PR_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
				                                        <?php
				                                    }
				                                    else
				                                    {
				                                        ?>
				                                            <option value="1"<?php if($PR_STAT == 1) { ?> selected <?php } ?>>New</option>
				                                            <option value="2"<?php if($PR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
				                                        <?php
				                                    }
				                                    ?>
				                                </select>
				                            <?php
				                            $theProjCode 	= "$PRJCODE~$PR_REFNO";
				                            //$url_AddItem	= site_url('c_purchase/c_pr180d0c/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
				                            $url_AddItem	= site_url('c_purchase/c_pr180d0c/popupallitem/?id=');
				                        ?>
				                        <input type="hidden" name="PR_VALUE" id="PR_VALUE" value="<?php echo $PR_VALUE; ?>">
				                        <input type="hidden" name="PR_VALUEAPP" id="PR_VALUEAPP" value="<?php echo $PR_VALUEAPP; ?>">
				                    </div>
				                </div>
				                <script>
				                    function chkSTAT(selSTAT)
				                    {
										var STAT_BEFORE	= document.getElementById('STAT_BEFORE').value;
				                        if(selSTAT == 5 || selSTAT == 9)
				                        {
				                            var isUSED	= document.getElementById('isUSED').value;
				                            if(isUSED > 0)
				                            {
				                                swal('<?php echo $alertREJ; ?>'+' <?php echo $DOC_NO; ?>',
												{
													icon: "warning",
												});
				                                return false;
				                            }
				                            else
				                            {
				                                document.getElementById('btnREJECT').style.display 	= '';
				                                document.getElementById('PR_NOTE2').disabled		= false;
				                            }
				                        }
				                        else if(selSTAT == 6)
				                        {
				                            document.getElementById('btnREJECT').style.display 	= '';
				                            //document.getElementById('AppNotes').style.display 	= '';
				                        }
				                        else
				                        {
											/*if(STAT_BEFORE == 3)
				                            	document.getElementById('AppNotes').style.display 	= 'none';*/
				                        }
				                    }
				                </script>
				                <?php if($PR_STAT == 1 || $PR_STAT == 4) { ?>
					                <div class="form-group">
					                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
					                    <div class="col-sm-10">
					                        <script>
					                            var url = "<?php echo $url_AddItem;?>";
					                            function selectitem()
					                            {	
					                                /*PR_REFNO1	= $('#PR_REFNO').val();
					                                if(PR_REFNO1 == null)
					                                {
					                                    swal('<?php echo $alert1; ?>',
														{
															icon: "warning",
														});
					                                    return false;
					                                }*/
					                                                            
					                                //PR_REFNO 	= PR_REFNO1.join("~");
					                                PR_REFNO 	= '';
					                                PRJCODE		= document.getElementById('PRJCODE').value;
					                                
					                               /* if(PR_REFNO == '')
					                                {
					                                    swal('<?php echo $alert1; ?>',
														{
															icon: "warning",
														});
					                                    return false;
					                                }*/
					                                title = 'Select Item';
					                                w = 1000;
					                                h = 550;
					                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
					                                var left = (screen.width/2)-(w/2);
					                                var top = (screen.height/2)-(h/2);
					                                //return window.open(url+PR_REFNO+'&pr1h0ec0JcoDe='+PRJCODE, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
					                                /*return window.open(url+PR_REFNO+'&pr1h0ec0JcoDe='+PRJCODE, title, "top=0,left=0,width=" + window.screen.width + ",height=" + window.screen.height);*/
					                                return window.open(url+PR_REFNO+'&pr1h0ec0JcoDe='+PRJCODE, title, 'toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbar=0, resizable=0, width='+w+', height='+h+', top='+top+', left='+left);
					                            }
					                        </script>
					                        <!-- <button class="btn btn-success" type="button" onClick="selectitem();">
					                        	<i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
					                        </button> -->
					                        <div class="pull-left">
					                        	<a class="btn btn-sm btn-success" data-toggle="modal" data-target="#mdl_addItm">
					                        		<i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
					                        	</a>
					                        </div>
					                   	</div>
					                </div>
				            	<?php } ?>
				            </div>
			            </div>
		        	</div>

	                <div class="col-md-12">
	                    <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
	                                <tr style="background:#CCCCCC">
	                                  	<th width="3%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
	                                  	<th width="3%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $ItemCode ?> </th>
	                                  	<th width="33%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
	                                  	<th colspan="3" style="text-align:center"><?php echo $ItemQty; ?> </th>
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
	                                                    B.ITM_CODE_H, B.ITM_NAME, B.ITM_TYPE
	                                                FROM tbl_pr_detail A
	                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
	                                                        AND B.PRJCODE = '$PRJCODE'
	                                                WHERE PR_NUM = '$PR_NUM' 
	                                                    AND B.PRJCODE = '$PRJCODE'";
	                                    $result = $this->db->query($sqlDET)->result();
	                                    $i		= 0;
	                                    $j		= 0;
	                                    
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
	                                        $ITM_TYPE 		= $row->ITM_TYPE;
	                                        $itemConvertion	= 1;

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
	                                        <tr id="tr_<?php echo $currentRow; ?>" style="vertical-align: middle;">
	                                        <td style="text-align:center; vertical-align: middle;">
	                                          <?php
	                                            if($PR_STAT == 1 || $PR_STAT == 4)
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
												if($TOTPRQTY == '')
												{
													$TOTPRAMOUNT	= 0;
													$TOTPRQTY		= 0;
												}
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
	                                        	<?php if($PR_STAT == 1 || $PR_STAT == 4) { ?>
	                                          		<input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_VOLMBGx<?php echo $currentRow; ?>" id="ITM_VOLMBGx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_BUDQTY, $decFormat); ?>" disabled >
	                                      		<?php } else { print number_format($ITM_BUDQTY, $decFormat); ?> <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_VOLMBGx<?php echo $currentRow; ?>" id="ITM_VOLMBGx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_BUDQTY, $decFormat); ?>" disabled >
	                                      		<?php } ?>
	                                          	<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_VOLMBG]" id="ITM_VOLMBG<?php echo $currentRow; ?>" value="<?php echo $ITM_BUDQTY; ?>" >
	                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_BUDG" name="data[<?php echo $currentRow; ?>][ITM_BUDG]" value="<?php echo $ITM_BUDG; ?>">
	                                      	</td>
	                                      	<td style="text-align:right; vertical-align: middle;">  <!-- Item Requested FOR INFORMATION ONLY -->
	                                      		<?php if($PR_STAT == 1 || $PR_STAT == 4) { ?>
	                                          		<input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTPRQTYx<?php echo $currentRow; ?>" id="TOTPRQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRQTY, $decFormat); ?>" disabled >
	                                      		<?php } else { print number_format($TOTPRQTY, $decFormat); ?> <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTPRQTYx<?php echo $currentRow; ?>" id="TOTPRQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRQTY, $decFormat); ?>" disabled >
	                                      		<?php } ?>
		                                        <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTED<?php echo $currentRow; ?>" id="ITM_REQUESTED<?php echo $currentRow; ?>" value="<?php print $TOTPRAMOUNT; ?>" >
		                                        <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTPRQTY<?php echo $currentRow; ?>" id="TOTPRQTY<?php echo $currentRow; ?>" value="<?php print $TOTPRQTY; ?>" >
		                                        <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTEDx<?php echo $currentRow; ?>" id="ITM_REQUESTEDx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRAMOUNT, $decFormat); ?>" disabled ></td>
	                                     	<td style="text-align:right; vertical-align: middle;"> <!-- Item Request Now -- PR_VOLM -->
	                                      		<?php if($PR_STAT == 1 || $PR_STAT == 4) { ?>
	                                          		<input type="text" name="PR_VOLM<?php echo $currentRow; ?>" id="PR_VOLM<?php echo $currentRow; ?>" value="<?php print number_format($PR_VOLM, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> >
	                                      		<?php } else { print number_format($PR_VOLM, $decFormat); ?> <input type="hidden" name="PR_VOLM<?php echo $currentRow; ?>" id="PR_VOLM<?php echo $currentRow; ?>" value="<?php print number_format($PR_VOLM, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> >
	                                      		<?php } ?>
												<input type="hidden" name="data[<?php echo $currentRow; ?>][PR_VOLM]" id="data<?php echo $currentRow; ?>PR_VOLM" value="<?php print $PR_VOLM; ?>" class="form-control" style="max-width:300px;" ><input type="hidden" name="data[<?php echo $currentRow; ?>][PR_PRICE]" id="data<?php echo $currentRow; ?>PR_PRICE" value="<?php print $PR_PRICE; ?>" class="form-control" style="max-width:300px;" >
												<input type="hidden" name="data[<?php echo $currentRow; ?>][PR_TOTAL]" id="data<?php echo $currentRow; ?>PR_TOTAL" value="<?php print $PR_TOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
												<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
	                                        </td>
	                                        <td style="text-align:center; vertical-align: middle;" nowrap>
	                                          <?php echo $ITM_UNIT; ?>
	                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
	                                        <!-- Item Unit Type -- ITM_UNIT --></td>
	                                        <td style="text-align:left; vertical-align: middle;">
	                                      		<?php if($PR_STAT == 1 || $PR_STAT == 4) { ?>
	                                          		<input type="text" name="data[<?php echo $currentRow; ?>][PR_DESC]" id="data<?php echo $currentRow; ?>PR_DESC" size="20" value="<?php print $PR_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left"<?php if($isDisabled == 1) { ?> disabled <?php } ?>>
	                                      		<?php } else { print $PR_DESC; ?> <input type="hidden" name="data[<?php echo $currentRow; ?>][PR_DESC]" id="data<?php echo $currentRow; ?>PR_DESC" size="20" value="<?php print $PR_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left"<?php if($isDisabled == 1) { ?> disabled <?php } ?>>
	                                      		<?php } ?>
	                                            
	                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
	                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" ></td>
	                                  </tr>
	                                    <?php
	                                    endforeach;
	                                }
	                                ?>
	                                <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
	                            </table>
	                        </div>
	                  	</div>
		            </div>
	                <br>
	                <div class="col-md-12">
		                <div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                    <div class="col-sm-10">
		                        <?php
		                            if($task=='add')
		                            {
		                                if($resCAPP > 0 && (($PR_STAT == 1 || $PR_STAT == 4) && $ISCREATE == 1))
		                                {
		                                    ?>
		                                        <button class="btn btn-primary" id="btnSave">
		                                        <i class="fa fa-save"></i></button>&nbsp;
		                                    <?php
		                                }
		                            }
		                            else
		                            {
		                                if(($PR_STAT == 1 || $PR_STAT == 4) && $ISCREATE == 1)
		                                {
		                                    ?>
		                                        <button class="btn btn-primary" id="btnSave">
		                                        <i class="fa fa-save"></i></button>&nbsp;
		                                    <?php
		                                }
		                                elseif($PR_STAT == 3)
		                                {
		                                    ?>
		                                        <button class="btn btn-primary" id="btnREJECT" style="display:none" >
		                                        <i class="fa fa-save"></i></button>&nbsp;
		                                    <?php
		                                }
		                                /*elseif($resAPP == 1)
		                                {
		                                    ?>
		                                        <button class="btn btn-primary" id="btnREJECT" style="display:none" >
		                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
		                                        </button>&nbsp;
		                                    <?php
		                                }*/
		                            }
		                            $backURL	= site_url('c_purchase/c_pr180d0c/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                            echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
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
	        </div>

	    	<!-- ============ START MODAL =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?php echo $ItemList; ?></a>
						                    </li>	
						                    <li id="li2">
						                    	<a href="#itm2" data-toggle="tab" onClick="setType(2)"><?php echo $Substitute; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
			                                                        <th width="2%">&nbsp;</th>
			                                                        <th width="40%" nowrap><?php echo $ItemName; ?></th>
			                                                        <th width="10%" nowrap><?php echo $Unit; ?></th>
			                                                        <th width="10%" nowrap><?php echo $BudgetQty; ?>  </th>
			                                                        <th width="10%" nowrap><?php echo $Requested; ?></th>
			                                                        <th width="10%" nowrap><?php echo $Ordered; ?> </th>
			                                                        <th width="10%" nowrap><?php echo $StockQuantity; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>

							            	<div class="<?php echo $Active2; ?> tab-pane" id="itm2" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch2" id="frmSearch2" action="">
			                                            <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
		                                                        <tr>
			                                                        <th width="2%">&nbsp;</th>
			                                                        <th width="44%" nowrap><?php echo $ItemName; ?> </th>
			                                                        <th width="3%" nowrap><?php echo $Unit; ?></th>
			                                                        <th width="6%" nowrap><?php echo $BudgetQty; ?>  </th>
			                                                        <th width="6%" nowrap><?php echo $Requested; ?></th>
			                                                        <th width="6%" nowrap><?php echo $Ordered; ?> </th>
			                                                        <th width="9%" nowrap><?php echo $StockQuantity; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose2" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                      	<button type="button" id="idClose" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					function setType(tabType)
					{
						if(tabType == 1)
						{
							document.getElementById('itm1').style.display	= '';
							document.getElementById('itm2').style.display	= 'none';
						}
						else
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= '';
						}
					}

					$(document).ready(function()
					{
				    	$('#example1').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_purchase/c_pr180d0c/get_AllDataITM/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [2,4,5], className: 'dt-body-center' },
											{ "width": "100px", "targets": [1] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

				    	$('#example2').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_purchase/c_pr180d0c/get_AllDataITMS/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [2,4,5], className: 'dt-body-center' },
											{ "width": "100px", "targets": [1] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					});

					var selectedRows = 0;
					function pickThis1(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    .val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });

					   	$("#btnDetail2").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk2']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    .val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL =============== -->

        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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
		  endDate: '+1d'
	    });
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		  startDate: '+0d'
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
	
	<?php
	if($task == 'add')
	{
		?>
		$(document).ready(function()
		{
			setInterval(function(){getNewCode()}, 1000);
		});
		
		function getNewCode()
		{
			var	PRJCODE		= '<?php echo $dataColl; ?>';
			var isManual	= document.getElementById('isManual').checked;
			
			if(window.XMLHttpRequest)
			{
				//code for IE7+,Firefox,Chrome,Opera,Safari
				xmlhttpTask=new XMLHttpRequest();
			}
			else
			{
				xmlhttpTask=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttpTask.onreadystatechange=function()
			{
				if(xmlhttpTask.readyState==4&&xmlhttpTask.status==200)
				{
					if(xmlhttpTask.responseText != '')
					{
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = xmlhttpTask.responseText;
					}
					else
					{
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = '';
					}
				}
			}
			xmlhttpTask.open("GET","<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>"+PRJCODE,true);
			xmlhttpTask.send();
		}
		<?php
	}
	?>
  
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
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var PR_NUMx 	= "<?php echo $DocNumber; ?>";
		
		var PR_CODEx 	= "<?php echo $PR_CODE; ?>";
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEDET 		= arrItem[0];
		JOBCODEID 		= arrItem[1];
		JOBCODE 		= arrItem[2];
		PRJCODE 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_SN			= arrItem[6];
		ITM_UNIT 		= arrItem[7];
		ITM_PRICE 		= arrItem[8];
		ITM_VOLM 		= arrItem[9];
		ITM_VOLMBG		= parseFloat(ITM_VOLM);
		ITM_STOCK 		= arrItem[10];
		ITM_USED 		= arrItem[11];
		itemConvertion	= arrItem[12];
		TotPrice		= arrItem[13];
		tempTotMax		= arrItem[14];
		TOT_USEBUDG		= arrItem[15];
		//ITM_BUDG		= arrItem[16];
		ITM_BUDG		= TotPrice;
		TOT_USEDQTY		= arrItem[17];
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'PR_NUM" name="data['+intIndex+'][PR_NUM]" value="'+PR_NUMx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PR_CODE" name="data['+intIndex+'][PR_CODE]" value="'+PR_CODEx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SNCODE" name="data['+intIndex+'][SNCODE]" value="'+ITM_SN+'" class="form-control" style="max-width:300px;">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// Item Budget
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_VOLMBGx'+intIndex+'" id="ITM_VOLMBGx'+intIndex+'" value="'+ITM_VOLMBG+'" disabled ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_VOLMBG]" id="ITM_VOLMBG'+intIndex+'" value="'+ITM_VOLMBG+'" ><input type="hidden" id="data'+intIndex+'ITM_BUDG" name="data['+intIndex+'][ITM_BUDG]" value="'+ITM_BUDG+'">';
		
		// Item Requested FOR INFORMATION ONLY
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_REQUESTED'+intIndex+'" id="ITM_REQUESTED'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTEDx'+intIndex+'" id="ITM_REQUESTEDx'+intIndex+'" value="'+TOT_USEBUDG+'" ><input type="hidden" class="form-control" style="text-align:right" name="TOTPRQTY'+intIndex+'" id="TOTPRQTY'+intIndex+'" value="'+TOT_USEDQTY+'" ><input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTPRQTY'+intIndex+'" id="TOTPRQTYx'+intIndex+'" value="'+TOT_USEDQTY+'" disabled >';
		
		// Item Request Now -- PR_VOLM
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="PR_VOLM'+intIndex+'" id="PR_VOLM'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][PR_VOLM]" id="data'+intIndex+'PR_VOLM" value="0.00" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][PR_PRICE]" id="data'+intIndex+'PR_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][PR_TOTAL]" id="data'+intIndex+'PR_TOTAL" value="'+TotPrice+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" value="'+itemConvertion+'" >';
		
		// Item Unit Type -- ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';
		
		// Remarks -- PR_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][PR_DESC]" id="data'+intIndex+'PR_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left"><input type="hidden" name="data['+intIndex+'][JOBCODEDET]" id="data'+intIndex+'JOBCODEDET" value="'+JOBCODEDET+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		var decFormat												= document.getElementById('decFormat').value;
		var PPMat_Budget											= document.getElementById('ITM_VOLMBG'+intIndex).value
		document.getElementById('ITM_VOLMBG'+intIndex).value 		= parseFloat(Math.abs(PPMat_Budget));
		document.getElementById('ITM_VOLMBGx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Budget)),decFormat));
		var ITM_REQUESTED											= document.getElementById('ITM_REQUESTED'+intIndex).value;
		document.getElementById('TOTPRQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('ITM_REQUESTED'+intIndex).value 	= parseFloat(Math.abs(ITM_REQUESTED));
		document.getElementById('ITM_REQUESTEDx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REQUESTED)),decFormat));
		document.getElementById('TOTPRQTY'+intIndex).value 			= parseFloat(Math.abs(TOT_USEDQTY));
		document.getElementById('TOTPRQTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_USEDQTY)),decFormat));
		document.getElementById('totalrow').value = intIndex;
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
		
		thisVal 			= eval(thisVal1).value.split(",").join("");
		itemConvertion		= document.getElementById('itemConvertion'+row).value;	
		PR_PRICE			= parseFloat(document.getElementById('data'+row+'PR_PRICE').value);			// Item Price
		ITM_VOLMBG			= eval(document.getElementById('ITM_VOLMBG'+row)).value.split(",").join("");// Budget Qty
		ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);			// Budget Amount
		TOTPRQTY			= eval(document.getElementById('TOTPRQTY'+row)).value.split(",").join("");	// Total Requested
		TOTPRAMOUNT			= parseFloat(TOTPRQTY) * parseFloat(PR_PRICE);								// Total Requested Amount
		
		REQ_NOW_QTY1		= eval(document.getElementById('PR_VOLM'+row)).value.split(",").join("");	// Request Qty - Now
		//swal('REQ_NOW_QTY1 = '+REQ_NOW_QTY1)	// 10K
		REQ_NOW_QTY2		= REQ_NOW_QTY1 * itemConvertion;											// Request Qty 2 - Now
		REQ_NOW_AMOUNT		= parseFloat(REQ_NOW_QTY1) * parseFloat(PR_PRICE);							// Request Qty Amount - Now
		//swal('PR_PRICE = '+PR_PRICE)	// 100K
		//swal('REQ_NOW_AMOUNT = '+REQ_NOW_AMOUNT)
		//swal('ITM_BUDG = '+ITM_BUDG)	// 12K
		//swal('TOTPRAMOUNT = '+TOTPRAMOUNT)	// 0
		/*swal('ITM_VOLMBG = '+ITM_VOLMBG)
		swal('ITM_BUDG = '+ITM_BUDG)
		swal('TOTPRQTY = '+TOTPRQTY)
		swal('TOTPRAMOUNT = '+TOTPRAMOUNT)
		swal('REQ_NOW_QTY1 = '+REQ_NOW_QTY1)
		swal('REQ_NOW_AMOUNT = '+REQ_NOW_AMOUNT)*/
		// REMAIN
		REM_PR_QTY			= parseFloat(ITM_VOLMBG) - parseFloat(TOTPRQTY);
		REM_PR_AMOUNT		= parseFloat(ITM_BUDG) - parseFloat(TOTPRAMOUNT);
		//swal('REM_PR_AMOUNT = '+REM_PR_AMOUNT)	// 12086.69
		//swal(REQ_NOW_AMOUNT)
		//if(REQ_NOW_AMOUNT > REM_PR_AMOUNT)
		if(REQ_NOW_QTY1 > REM_PR_QTY)
		{
			REM_PR_QTYV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_PR_QTY)),decFormat));
			REM_PR_AMOUNTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_PR_AMOUNT)),decFormat));
            swal('<?php echo $greaterBud; ?> '+REM_PR_QTYV,
			{
				icon: "warning",
			});
			document.getElementById('data'+row+'PR_VOLM').value = REM_PR_QTY;
			document.getElementById('PR_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_PR_QTY)),decFormat));
			return false;
		}
		
		document.getElementById('data'+row+'PR_TOTAL').value 	= REQ_NOW_AMOUNT;
		document.getElementById('data'+row+'PR_VOLM').value 	= REQ_NOW_QTY1;
		document.getElementById('PR_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_QTY1)),decFormat))
		//document.getElementById('PR_VOLM2'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function checkForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		var PR_REFNO 	= document.getElementById('PR_REFNO').value;
		var PR_NOTE 	= document.getElementById('PR_NOTE').value;
		//var PR_STAT 	= document.getElementById('PR_STAT').value;
		//var isApproved 	= 0;
		var STAT_BEFORE	= document.getElementById('STAT_BEFORE').value;
		var PR_STAT 	= document.getElementById('PR_STAT').value;
		
		/*if(PR_REFNO == "")
		{
			swal('<?php echo $budEmpt; ?>',
			{
				icon: "warning",
			});
			return false;
		}*/
		
		if(PR_NOTE == "")
		{
			swal('<?php echo $docNotes; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#PR_NOTE').focus();
            });
			return false;
		}

		if(STAT_BEFORE == 1 || STAT_BEFORE == 4)
		{
			for(i=1;i<=totrow;i++)
			{
				var PR_VOLM = parseFloat(document.getElementById('PR_VOLM'+i).value);
				if(PR_VOLM == 0)
				{
					swal('<?php echo $inpMRQTY; ?>',
					{
						icon: "warning",
					})
					.then(function()
		            {
		                swal.close();
						document.getElementById('PR_VOLM'+i).value = '0';
		                $('#PR_VOLM'+i).focus();
		            });
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
				var variable = document.getElementById('btnSave');
				if (typeof variable !== 'undefined' && variable !== null)
				{
					document.getElementById('btnSave').style.display 	= 'none';
				}

				document.frm.submit();
			}
		}
		else
		{
			//swal('Can not update this document. The document has Confirmed.');
			//return false;
		}
	
		function getJob(event)
		{
			var chCode = ('charCode' in event) ? event.charCode : event.keyCode;
            alert ("The Unicode character code is: " + chCode);
		}
	}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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