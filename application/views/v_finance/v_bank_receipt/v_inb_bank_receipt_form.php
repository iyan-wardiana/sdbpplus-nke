<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 27 Maret 2018
	* File Name	= v_inb_bank_receipt_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

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
	$myCount = $this->db->count_all('tbl_br_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_br_header
			WHERE Patt_Year = $yearC AND BR_TYPE = 'BP'";
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
	
	
	/*$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;*/
	$DocNumber 		= "$Pattern_Code$groupPattern-$lastPatternNumb";
	$BR_NUM			= "$DocNumber";
	$BR_CODE		= "$lastPatternNumb"; // MANUAL CODE
	$BR_DATE		= date('d/m/Y');
	$JournalY 		= date('Y');
	$JournalM 		= date('n');
	$BR_TYPE		= 'BR';
	$BR_RECTYPE		= 'PRJ';
	$BR_CURRID		= 'IDR';
	$BR_CURRCONV	= 1;
	$BankAcc_ID		= '';
	$BR_PAYFROM		= '';
	$BR_CHEQNO		= '';
	$BR_NOTES		= '';
	$BR_STAT 		= 1;	
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;
	$BR_TOTAM		= 0;
	$BR_TOTAM_PPn	= 0;
}
else
{
	$BR_NUM 		= $default['BR_NUM'];
	$JournalH_Code	= $BR_NUM;
	$DocNumber		= $BR_NUM;
	$BR_CODE 		= $default['BR_CODE'];
	$BR_DATE1		=  $default['BR_DATE'];
	$JournalY 		= date('Y', strtotime($BR_DATE1));
	$JournalM 		= date('n', strtotime($BR_DATE1));
	$BR_DATE		= date('d/m/Y',strtotime($BR_DATE1));
	$BR_TYPE 		= $default['BR_TYPE'];
	$BR_RECTYPE		= $default['BR_RECTYPE'];
	$BR_CURRID 		= $default['BR_CURRID'];
	$BR_CURRCONV	= $default['BR_CURRCONV'];
	$BankAcc_ID		= $default['Acc_ID'];
	$BR_PAYFROM 	= $default['BR_PAYFROM'];
	$BR_CHEQNO 		= $default['BR_CHEQNO'];
	$BR_TOTAM 		= $default['BR_TOTAM'];
	$BR_TOTAM_PPn	= $default['BR_TOTAM_PPn'];
	$BR_NOTES 		= $default['BR_NOTES'];
	$BR_STAT		= $default['BR_STAT'];
	$Acc_ID 		= $default['Acc_ID'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date 		= $default['Patt_Date'];
	$Patt_Number	= $default['Patt_Number'];
}

// GET Journal Lock
	$disabled 	= 0;
	$getJLock 	= "SELECT * FROM tbl_journal_lock 
					WHERE LockY = $JournalY AND LockM = $JournalM AND isLock = 1 AND UserLock != '$DefEmp_ID'";
	$resJLock 	= $this->db->query($getJLock);
	$countJLock = $resJLock->num_rows();
	if($countJLock == 1) $disabled = 1;
	
if(isset($_POST['submit1']))
{
	$SelCurr 		= $this->input->post('BR_CURRIDA');
	$selAccount 	= $this->input->post('AccSelected');
	$BR_PAYFROM 	= $this->input->post('SPLSelected');
	$BR_RECTYPE 	= $this->input->post('BRTSelected');
}
else
{
	$SelCurr 		= 'IDR';
	$selAccount	 	= $BankAcc_ID;
	$BR_PAYFROM 	= $BR_PAYFROM;
	$BR_RECTYPE		= $BR_RECTYPE;
}

/*if($BR_RECTYPE == 'SAL')
{
	$PRJCODE	= '';
	$sqlPRJ 	= "SELECT PRJCODE FROM tbl_sinv_header WHERE CUST_CODE = '$BR_PAYFROM'";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE = $rowPRJ->PRJCODE;
	endforeach;
}
elseif($BR_RECTYPE == 'PRJ')
{
	$PRJCODE	= '';
	$sqlPRJ 	= "SELECT PRJCODE FROM tbl_projinv_header A
					WHERE PINV_OWNER = '$BR_PAYFROM'";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE = $rowPRJ->PRJCODE;
	endforeach;
}
else
{
	$PRJCODE	= '';
	$sqlPRJ 	= "SELECT PRJCODE FROM tbl_projinv_header A
					WHERE PINV_OWNER = '$BR_PAYFROM'";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE = $rowPRJ->PRJCODE;
	endforeach;
}*/
?>
<!DOCTYPE html>

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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

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
			if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
			if($TranslCode == 'Description')$Description_ = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;		
			if($TranslCode == 'ChooseInvoice')$ChooseInvoice = $LangTransl;
			if($TranslCode == 'BankReceipt')$BankReceipt = $LangTransl;
			if($TranslCode == 'Finance')$Finance = $LangTransl;		
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'PPn')$PPn = $LangTransl;		
			if($TranslCode == 'Receipt')$Receipt = $LangTransl;
			
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'PaymentNow')$PaymentNow = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'BR_CODE')$BR_CODE = $LangTransl;
			if($TranslCode == 'InvoiceAmount')$InvoiceAmount = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ReceiptFrom')$ReceiptFrom = $LangTransl;
			if($TranslCode == 'Amount')$Amount_ = $LangTransl;
			if($TranslCode == 'BankAccount')$BankAccount = $LangTransl;
			if($TranslCode == 'ActualBalance')$ActualBalance = $LangTransl;
			if($TranslCode == 'Reserved')$Reserved = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Sales')$Sales = $LangTransl;
			if($TranslCode == 'DetInfo')$DetInfo = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'IRList')$IRList = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
			if($TranslCode == 'Account')$Account = $LangTransl;
			if($TranslCode == 'AccountNo')$AccountNo = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'SelectAccount')$SelectAccount = $LangTransl;
			if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
			if($TranslCode == 'AccountList')$AccountList = $LangTransl;
			if($TranslCode == 'OthRecCB')$OthRecCB = $LangTransl;
			if($TranslCode == 'Received')$Received = $LangTransl;
		endforeach;
		
		if($LangID = 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Peringatan';
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$alert1		= "Masukan data detail penerimaan.";
			$alert2		= "Nilai penerimaan tidak boleh kosong.";
			$Others		= "Lainnya";
			$alert3		= "Silahkan pilih status persetujuan.";
			$RecDP		= "Uang Muka";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Warning';
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$alert1		= "Please insert an receipt detail.";
			$alert2		= "Receipt amount can not be empty.";
			$Others		= "Others";
			$alert3		= "Please select approval status.";
			$RecDP		= "Down Payment";
		}
		
		$secGenCode	= base_url().'index.php/c_finance/c_bank_payment/genCode/'; // Generate Code
		
		// START : APPROVE PROCEDURE
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$MAX_STEP	= $rowAPP->MAX_STEP;
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
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$BR_NUM'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				$appReady	= $APP_STEP - 1;
				if($resC_App == $appReady)
				{
					$canApprove	= 1;
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
				$APPROVE_AMOUNT = $BR_TOTAM + $BR_TOTAM_PPn;
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
	?>

	<style>
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inpdim {border:none;background-color:white;}
	</style>
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/bank_receipt.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$Approval ($PRJCODE)"; ?>
			    <small><?php echo $BankReceipt; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
		        <form class="form-horizontal" name="frm1" method="post" action="" style="display:none">
		        	<input type="text" name="BR_CURRIDA" id="BR_CURRIDA" value="<?php echo $SelCurr; ?>" />
		        	<input type="text" name="AccSelected" id="AccSelected" value="<?php echo $selAccount; ?>" />
		        	<input type="text" name="SPLSelected" id="SPLSelected" value="<?php echo $BR_PAYFROM; ?>" />
		        	<input type="text" name="BRTSelected" id="BRTSelected" value="<?php echo $BR_RECTYPE; ?>" />
		            <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
		        </form>
		        <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
		            <table>
		                <tr>
		                    <td>
		                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="">
		                        <input type="TEXT" name="BR_TYPE" id="BR_TYPE" value="<?php echo $BR_TYPE; ?>">
		                        <input type="TEXT" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
		                        <input type="TEXT" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
		                        <input type="TEXT" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
		                        <input type="TEXT" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
		                        <input type="TEXT" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
		                        <input type="TEXT" name="CBDate" id="CBDate" value="">
		                    </td>
		                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
		                </tr>
		            </table>
		        </form>

                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
		            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
		            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
		            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
		            <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
		            <input type="Hidden" name="rowCount" id="rowCount" value="0">
					<?php
						// START : LOCK PROCEDURE
							$app_stat 	= $this->session->userdata['app_stat'];
							if($LangID == 'IND')
							{
								$appAlert1	= 'Terkunci!';
								$appAlert2	= 'Mohon maaf, saat ini transaksi penjurnalan sedang terkunci.';
							}
							else
							{
								$appAlert1	= 'Locked!';
								$appAlert2	= 'Sorry, the journalizing transaction is currently locked.';
							}
							?>
								<input type="hidden" name="app_stat" id="app_stat" value="<?php echo $app_stat; ?>">
					            <div class="col-sm-12" id="divAlert" style="display: none;">
					                <div class="form-group">
					                    <div class="col-sm-12">
					                        <div class="alert alert-danger alert-dismissible">
					                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                            <h4><i class="icon fa fa-ban"></i> <?php echo $appAlert1; ?>!</h4>
					                            <?php echo $appAlert2; ?>
					                        </div>
					                    </div>
					                </div>
					            </div>
						    <?php
	                	// END : LOCK PROCEDURE
	                ?>
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><?php echo $DetInfo; ?></h3>
							</div>
							<div class="box-body">
                                <div class="form-group"> <!-- CB DOCUMENT -->
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Code ?> / <?php echo $Date ?> </label>
		                            <div class="col-sm-5">
		                                    <input type="text" class="form-control" name="BR_CODE" id="BR_CODE" value="<?php echo $BR_CODE; ?>" />
				                            <input type="hidden" name="BR_NUM" id="BR_NUM" value="<?php echo $DocNumber; ?>">
				                    </div>
		                            <div class="col-sm-4">
	                                    <div class="input-group date">
			                            	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                <?php
												if($task == 'add')
												{
													?>
			                            			<input type="text" name="BR_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $BR_DATE; ?>" style="width:105px" onChange="getBR_NUM(this.value)">
			                                        <?php
												}
												else
												{
													?>
			                            			<input type="text" name="BR_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $BR_DATE; ?>" style="width:105px">
			                                        <?php
												}
											?>
			                            </div>
		                          	</div>
                                </div>
								<script>
		                            function getBR_NUM(selDate)
		                            {
		                                document.getElementById('CBDate').value = selDate;
		                                document.getElementById('dateClass').click();
		                            }
		            
		                            $(document).ready(function()
		                            {
		                                $(".tombol-date").click(function()
		                                {
		                                    var add_CB	= "<?php echo $secGenCode; ?>";
		                                    var formAction 	= $('#sendDate')[0].action;
		                                    var data = $('.form-user').serialize();
		                                    $.ajax({
		                                        type: 'POST',
		                                        url: formAction,
		                                        data: data,
		                                        success: function(response)
		                                        {
		                                            var myarr = response.split("~");
		                                            document.getElementById('BR_NUM1').value = myarr[0];
		                                            document.getElementById('BR_NUM').value = myarr[1];
		                                        }
		                                    });
		                                });
		                            });
		                        </script>
                                <div class="form-group" style="display: none;"> <!-- TYPE SOURCE -->
                                    <label for="exampleInputEmail1"><?php echo $SourceDocument; ?></label>
                                    <select name="BR_RECTYPE" id="BR_RECTYPE" class="form-control select2" onChange="selectAccount(this.value)">
		                                <option value="DP" <?php if($BR_RECTYPE == 'DP'){ ?> selected <?php } ?>>DP</option>
		                                <!-- <option value="PRJ" <?php if($BR_RECTYPE == 'PRJ'){ ?> selected <?php } ?>>Project</option> -->
		                                <option value="SAL" <?php if($BR_RECTYPE == 'SAL'){ ?> selected <?php } ?>><?php echo $Sales; ?></option>
		                                <option value="OTH" <?php if($BR_RECTYPE == 'OTH'){ ?> selected <?php } ?>>Others</option>
		                            </select>
                                </div>
		                    	<?php
									if($BR_RECTYPE == 'SAL')
									{
										$countOWN	= "tbl_sinv_header A
														INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
														WHERE A.SINV_STAT = '3' AND A.SINV_PAYSTAT != 'FR'";
										if($task == 'edit')
										{
											/*$countOWN	= "tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT IN (3,6) AND A.SINV_PAYSTAT != 'FR'";*/
											$countOWN	= "tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT IN (3,6)";
										}
										$resCOWN	= $this->db->count_all($countOWN);
										if($resCOWN > 0)
										{
											$sqlOWN		= "SELECT DISTINCT B.CUST_CODE AS own_Code, '' AS own_Title,
																B.CUST_DESC AS own_Name
															FROM tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT = '3' AND A.SINV_PAYSTAT != 'FR'";
											if($task == 'edit')
											{
												/*$sqlOWN	= "SELECT DISTINCT B.CUST_CODE AS own_Code, '' AS own_Title,
																B.CUST_DESC AS own_Name
															FROM tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT IN (3,6) AND A.SINV_PAYSTAT != 'FR'";*/
												$sqlOWN	= "SELECT DISTINCT B.CUST_CODE AS own_Code, '' AS own_Title,
																B.CUST_DESC AS own_Name
															FROM tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT IN (3,6)";
											}
											$resOWN		= $this->db->query($sqlOWN)->result();
										}
									}
									elseif($BR_RECTYPE == 'PRJ')
									{
										$countOWN	= "tbl_projinv_header A
														INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
														WHERE A.PINV_STAT = '3' AND A.PINV_CAT != '1'";
										if($task == 'edit')
										{
											$countOWN	= "tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT != '1'";
										}
										$resCOWN	= $this->db->count_all($countOWN);
										if($resCOWN > 0)
										{
											$sqlOWN		= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
															FROM tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT = '3' AND A.PINV_CAT != '1'";
											if($task == 'edit')
											{
												$sqlOWN	= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
															FROM tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT != '1'";
											}
											$resOWN		= $this->db->query($sqlOWN)->result();
										}
									}
									elseif($BR_RECTYPE == 'DP')
									{
										$countOWN	= "tbl_projinv_header A
														INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
														WHERE A.PINV_STAT = '3' AND A.PINV_CAT = '1'";
										if($task == 'edit')
										{
											$countOWN	= "tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT = '1'";
											
										}
										$resCOWN	= $this->db->count_all($countOWN);
										if($resCOWN > 0)
										{
											$sqlOWN		= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
														FROM tbl_projinv_header A
															INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
														WHERE A.PINV_STAT = '3' AND A.PINV_CAT = '1'";
											if($task == 'edit')
											{
												$sqlOWN	= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
															FROM tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT = '1'";
											}
											$resOWN		= $this->db->query($sqlOWN)->result();
										}
									}
									elseif($BR_RECTYPE == 'PPD')
									{
										$countOWN	= "tbl_journalheader_pd A
															INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID WHERE A.GEJ_STAT = '3'
																AND A.proj_Code = '$PRJCODE'";
										if($task == 'edit')
										{
											$countOWN	= "tbl_journalheader_pd A
															INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID WHERE A.GEJ_STAT IN ('3','6')
																AND A.proj_Code = '$PRJCODE'";
										}
										$resCOWN	= $this->db->count_all($countOWN);
										if($resCOWN > 0)
										{
											$sqlOWN		= "SELECT DISTINCT B.Emp_ID AS own_Code, '' AS own_Title,
																CONCAT(B.First_Name, ' ', B.Last_Name) AS own_Name
															FROM tbl_journalheader_pd A
															INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID WHERE A.GEJ_STAT = '3'
																AND A.proj_Code = '$PRJCODE'";
											if($task == 'edit')
											{
												$sqlOWN	= "SELECT DISTINCT B.Emp_ID AS own_Code, '' AS own_Title,
																CONCAT(B.First_Name, ' ', B.Last_Name) AS own_Name
															FROM tbl_journalheader_pd A
															INNER JOIN tbl_employee B ON A.PERSL_EMPID = B.Emp_ID WHERE A.GEJ_STAT IN ('3','6')
																AND A.proj_Code = '$PRJCODE'";
											}
											$resOWN		= $this->db->query($sqlOWN)->result();
										}
									}
									elseif($BR_RECTYPE == 'PD')
									{
										$countOWN	= "tbl_journalheader_pd A WHERE A.GEJ_STAT = '3' AND proj_Code = '$PRJCODE'";
										if($task == 'edit')
										{
											$countOWN	= "tbl_journalheader_pd A WHERE A.GEJ_STAT IN ('3','6') AND proj_Code = '$PRJCODE'";
											
										}
										$resCOWN	= $this->db->count_all($countOWN);
										if($resCOWN > 0)
										{
											$sqlOWN		= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
															FROM tbl_journalheader_pd A 
																	INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.GEJ_STAT = '3' AND A.proj_Code = '$PRJCODE'";
											if($task == 'edit')
											{
												$sqlOWN	= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
															FROM tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT = '1'";
											}
											$resOWN		= $this->db->query($sqlOWN)->result();
										}
									}
									elseif($BR_RECTYPE == 'OTH')
									{
										$resCOWN	= 1;

										$sqlOWN		= "SELECT Emp_ID AS own_Code, CONCAT(First_Name, ' ',Last_Name) AS own_Name, '' AS own_Title FROM tbl_employee WHERE Employee_status = 1
														UNION
														SELECT SPLCODE AS own_Code, SPLDESC AS own_Name, '' AS own_TitleE FROM tbl_supplier WHERE SPLSTAT = 1
														ORDER BY own_Name";
										$resOWN		= $this->db->query($sqlOWN)->result();
									}
								?>
                                <div class="form-group"> <!-- RECEIPT FROM -->
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptFrom ?> </label>
		                          	<div class="col-sm-3">
	                                    <select name="BR_RECTYPE" id="BR_RECTYPE" class="form-control select2" onChange="selectAccount(this.value)">
		                                	<option value="DP" <?php if($BR_RECTYPE == 'DP'){ ?> selected <?php } ?>>DP</option>
		                                	<option value="PRJ" <?php if($BR_RECTYPE == 'PRJ'){ ?> selected <?php } ?>>Project</option></option>
		                                	<option value="PPD" <?php if($BR_RECTYPE == 'PPD'){ ?> selected <?php } ?>>Penyelesaian PD</option>
		                                	<option value="OTH" <?php if($BR_RECTYPE == 'OTH'){ ?> selected <?php } ?>>Lainnya</option>
			                            </select>
		                          	</div>
		                          	<div class="col-sm-6">
		                          		<select name="BR_PAYFROM" id="BR_PAYFROM" class="form-control select2" onChange="selectAccount(this.value)">
				                            <option value=""> --- </option>
				                            <?php
				                            if($resCOWN > 0)
				                            {
				                            	foreach($resOWN as $row) :
													 $own_Code1		= $row->own_Code;
													 $own_Title1	= $row->own_Title;
													 $own_Name1		= $row->own_Name;
													 $compName		= "$own_Name1 $own_Title1 - $own_Code1";
				                            		?>
				                            <option value="<?php echo $own_Code1; ?>" <?php if($own_Code1 == $BR_PAYFROM) { ?> selected <?php } ?>><?php echo $compName; ?></option>
				                            <?php
				                            	endforeach;
				                            }
				                            ?>
			                          	</select>
		                          	</div>
                                </div>
                                <div class="form-group"> <!-- NOTES -->
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
		                          	<div class="col-sm-9">
		                          		<textarea name="BR_NOTES" class="form-control" id="BR_NOTES" cols="30" style="height: 65px"><?php echo $BR_NOTES; ?></textarea>
		                          	</div>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<h3 class="box-title">Tujuan Penerimaan</h3>
							</div>
							<div class="box-body">
                            	<div class="form-group"> <!-- CB_CURRID, Acc_ID -->
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $BankAccount ?> </label>
		                          	<div class="col-sm-9">
			                            <select name="BR_CURRID" id="BR_CURRID" class="form-control" style="display: none;" onChange="selectAccount(this.value)">
			                                <option value="IDR" <?php if($SelCurr == 'IDR'){ ?> selected <?php } ?>>IDR</option>
			                                <option value="USD" <?php if($SelCurr == 'USD'){ ?> selected <?php } ?>>USD</option>
			                            </select>
			                            <select name="selAccount" id="selAccount" class="form-control select2" onChange="selectAccount(this.value)">
			                            	<option value=""> --- </option>
			                                <?php echo $i = 0;
				                                if($countAcc > 0)
				                                {
					                                foreach($vwAcc as $row) :
					                                    $Acc_ID					= $row->Acc_ID;
					                                    $Account_Category 		= $row->Account_Category;
					                                    $Account_Number 		= $row->Account_Number;
					                                    $Account_Name 			= $row->Account_Name;
					                                	?>
						                                    <option value="<?php echo $Account_Number; ?>" <?php if($Account_Number == $selAccount){ ?> selected <?php } ?> >
						                                        <?php echo "$Account_Number &nbsp;&nbsp;$Account_Name"; ?>
						                                    </option>
					                                 	<?php
				                                 	endforeach;
				                                 }
				                                 else
				                                 {
				                                 	?>
				                                    	<option value="none"> --- </option>
				                                    <?php
				                                 }
			                                ?>
			                            </select>
				                        <script>
				                            function selectAccount()
				                            {
												selAccount	= document.getElementById('selAccount').value;
												document.getElementById('AccSelected').value = selAccount;
												BR_CURRIDA	= document.getElementById('BR_CURRID').value;
												document.getElementById('BR_CURRIDA').value = BR_CURRIDA;
												SPLSelected	= document.getElementById('BR_PAYFROM').value;
												document.getElementById('SPLSelected').value = SPLSelected;
												
												BR_RECTYPE	= document.getElementById('BR_RECTYPE').value;
												document.getElementById('BRTSelected').value = BR_RECTYPE;
												
				                                document.frm1.submit1.click();
				                            }
				                        </script>
			                        </div>
			                        <?php
			                            $sql1C 		= "tbl_chartaccount A
			                                            INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
			                                            WHERE A.Account_Class IN (3,4)
			                                            AND A.Account_Number = '$selAccount'
			                                            Order by A.Account_Category, A.Account_Number";
			                            $retSQL1C 	= $this->db->count_all($sql1C);
			                            
			                            if($retSQL1C >0)
			                            {
			                                $sql1 = "SELECT A.Base_OpeningBalance, A.Base_Debet, A.Base_Kredit
			                                        FROM tbl_chartaccount A
			                                        INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
			                                        WHERE A.Account_Class IN (3,4)
			                                        AND A.Account_Number = '$selAccount'
			                                        Order by A.Account_Category, A.Account_Number";
			                                $retSQL1 	= $this->db->query($sql1)->result();
			                                foreach($retSQL1 as $row1):
			                                    $opBal		= $row1->Base_OpeningBalance;
			                                    $BaseDebet	= $row1->Base_Debet;
			                                    $BaseKredit	= $row1->Base_Kredit;
			                                endforeach;
			                            }
			                            else
			                            {
			                                $opBal		= 0;
			                                $BaseDebet	= 0;
			                                $BaseKredit	= 0;
			                            }
			                            $ActBal	= $opBal + $BaseDebet - $BaseKredit;
			                            //print "$SelCurr "; print number_format($ActBal, $decFormat);
			                            // Untuk jumlah reserve, cari dari Bank Payment yang belum di Approve dan tidak reject
			                            // Perhatikan IDR atau USD
										$BR_TOTAM		= 0;
										$BR_TOTAM_PPn	= 0;
			                            $sql2 			= "SELECT SUM(BR_TOTAM) AS Tot_AM, SUM(BR_TOTAM_PPn) AS Tot_AMPPn
															FROM tbl_br_header
															WHERE BR_STAT NOT IN (3,5)
															AND BR_CURRID = '$SelCurr'
															AND Acc_ID = '$selAccount'";
			                            $retSQL2 	= $this->db->query($sql2)->result();
			                            foreach($retSQL2 as $row2):
			                                $BR_TOTAM		= $row2->Tot_AM;
			                                $BR_TOTAM_PPn	= $row2->Tot_AMPPn;
			                            endforeach;
			                            $TotReserve		= $BR_TOTAM + $BR_TOTAM_PPn;
			                            // Total Ammount : Total nilai yang saat ini akan dibayarkan
			                            $TotAmmount	= 0;
			                            
			                            // Total Remain
			                            //$TotRemain	= $ActBal - $TotReserve - $TotAmmount;		
			                            $TotRemain	= $ActBal;				 
			                        ?>
			                    </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">&nbsp; </label>
		                          	<div class="col-sm-9">
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label><?php echo $ActualBalance; ?></label>
	                                            <div>
	                                            	<a href="" class="btn btn-primary btn-xs">
			                                            <?php echo number_format($ActBal, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label><?php echo $Reserved; ?></label>
	                                            <div>
	                                            	<a href="" class="btn btn-danger btn-xs">
			                                            <?php echo number_format($TotReserve, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label><?php echo $Amount; ?></label>
	                                            <div>
	                                            	<a href="" class="btn btn-warning btn-xs">
			                                            <?php echo number_format($TotAmmount, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label><?php echo $Remain; ?></label>
	                                            <div>
	                                            	<a href="" class="btn btn-success btn-xs">
			                                            <?php echo number_format($TotRemain, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                            <input type="hidden" name="TotRemAccount" id="TotRemAccount" value="<?php echo $TotRemain; ?>" class="form-control" style="max-width:80px; text-align:right">
	                                        </div>
	                                    </div>
	                                </div>
                                </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
		                          	<div class="col-sm-9">
		                          		<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $BR_STAT; ?>" />
			                        	<?php
											// START : FOR ALL APPROVAL FUNCTION
												if($disableAll == 0)
												{
													if($canApprove == 1)
													{
														$disButton	= 0;
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$BR_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
														?>
															<select name="BR_STAT" id="BR_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																<option value="0"> --- </option>
																<option value="3"<?php if($BR_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
																<option value="4"<?php if($BR_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																<option value="5"<?php if($BR_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																<option value="7"<?php if($BR_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
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
				                        ?>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
		                          	<div class="col-sm-9">
		                          		<?php
											$collID		= "$BR_RECTYPE~$BR_PAYFROM";
											$selSource	= site_url('c_finance/c_br180c2cd0d/pall180c2ginvr/?id='.$this->url_encryption_helper->encode_url($collID));
										?>
										<div class="form-group has-error">
	                                        <span class="help-block" style="font-style: italic;">Silahkan pilih dokumen sumber penerimaan</span>
	                                    </div>
                                        <button class="btn btn-warning" type="button" onClick="selectitem();">
			                        		<i class="fa fa-folder-open"></i>&nbsp;&nbsp;<?php echo $ChooseInvoice; ?>
			                        	</button>
			                        	<input type="hidden" name="BR_TOTAM" id="BR_TOTAM" value="<?php echo $BR_TOTAM; ?>">
			                            <input type="hidden" name="BR_TOTAM_PPn" id="BR_TOTAM_PPn" value="<?php echo $BR_TOTAM_PPn; ?>">
		                          	</div>
		                       	</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12" id="alrtLockJ" style="display: none;">
						<div class="alert alert-warning alert-dismissible col-md-12">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-warning"></i> <?php echo $docalert1; ?>!</h4>
							<?php echo $docalert4; ?>
						</div>
					</div>

                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                            <tr style="background:#CCCCCC">
		                              	<th width="2%" height="25" style="text-align:left; vertical-align: middle;">No.</th>
		                              	<th width="10%"A.PINV_TOTVALPPh style="text-align:center; vertical-align: middle;">
		                              		<?php
		                              			if($BR_RECTYPE == 'PPD')
		                              				echo "No PD";
		                              			else
		                              				echo $InvoiceNo;
		                              		?>
		                              	</th>
		                              	<th width="33%"A.PINV_TOTVALPPh style="text-align:center; vertical-align: middle;"><?php echo $Description_; ?> </th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $InvoiceAmount; ?> </th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Received; ?> </th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Receipt; ?> </th>
		                              	<th width="25%" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
		                            </tr>
		                            <?php
		                            $totREMAMN 	= 0;
		                            if($task == 'edit')
		                            {
		                                $sqlDET	= "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.DocumentNo, A.DocumentRef, 
														A.Description, A.DebCred, A.Acc_ID, A.Inv_Amount, A.Inv_Amount_PPn,
														A.GInv_Amount, A.Amount, A.Amount_PPn, A.GAmount, A.Notes
													FROM tbl_br_detail A
														INNER JOIN tbl_br_header B ON A.JournalH_Code = B.JournalH_Code
													WHERE A.JournalH_Code = '$JournalH_Code'";
		                                // count data
		                                    $resultCount = $this->db->where('JournalH_Code', $JournalH_Code);
		                                    $resultCount = $this->db->count_all_results('tbl_br_detail');
		                                // End count data
		                                $result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;

		                                if($resultCount > 0)
		                                {
		                                    foreach($result as $row) :
		                                        $currentRow  	= ++$i;
		                                        $JournalH_Code 	= $row->JournalH_Code;
		                                        $BR_NUM 		= $row->BR_NUM;
		                                        $BR_CODE 		= $row->BR_CODE;
		                                        $DocumentNo		= $row->DocumentNo;
		                                        $DocumentRef	= $row->DocumentRef;
		                                        $Description	= $row->Description;
		                                        $DebCred 		= $row->DebCred;
		                                        $Acc_ID 		= $row->Acc_ID;
		                                        $Inv_Amount		= $row->Inv_Amount;
		                                        $Inv_Amount_PPn	= $row->Inv_Amount_PPn;
		                                        $GInv_Amount	= $row->GInv_Amount;
		                                        $Amount 		= $row->Amount;
		                                        $Amount_PPn		= $row->Amount_PPn;
		                                        $GAmount		= $row->GAmount;
		                                        $Notes			= $row->Notes;

		                                        $ManNOPD	= $DocumentNo;
												$sql 		= "SELECT Manual_No FROM tbl_journalheader_pd WHERE proj_Code = '$PRJCODE'
																AND JournalH_Code = '$DocumentNo'";
												$result 	= $this->db->query($sql)->result();
												foreach($result as $row) :
													$ManNOPD = $row->Manual_No;
												endforeach;
												
												$PINVPAIDAM		= 0;
												$sql2 			= "SELECT SUM(A.PINV_PAIDAM) AS PINV_PAIDAM
																	FROM tbl_projinv_header A
																		INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
																	WHERE A.PINV_OWNER = '$BR_PAYFROM' AND A.PINV_STAT = '3'
																		AND PINV_CODE = '$DocumentRef'";
												$retSQL2 	= $this->db->query($sql2)->result();
												foreach($retSQL2 as $row2):
													$PINVPAIDAM	= $row2->PINV_PAIDAM;
												endforeach;
												$GPINV_REMAIN	= $GInv_Amount - $PINVPAIDAM;
												$totREMAMN 		= $totREMAMN+$GPINV_REMAIN;
		                            
		                                        if ($j==1) {
		                                            echo "<tr class=zebra1>";
		                                            $j++;
		                                        } else {
		                                            echo "<tr class=zebra2>";
		                                            $j--;
		                                        }
		                                        ?>
		                                        <!-- JournalH_Code, BR_NUM -->
		                                        <tr><td  height="25" style="text-align:left">
													<?php
		                                            if($BR_STAT == 1)
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
		                                            <input type="hidden" id="data<?php echo $currentRow; ?>JournalH_Code" name="data[<?php echo $currentRow; ?>][JournalH_Code]" value="<?php echo $BR_NUM; ?>" class="form-control" style="max-width:300px;" readonly>
		                                            <input type="hidden" id="data<?php echo $currentRow; ?>BR_NUM" name="data[<?php echo $currentRow; ?>][BR_NUM]" value="<?php echo $BR_NUM; ?>" class="form-control" style="max-width:300px;" readonly>
		                                      	</td>
		                                        <!-- BR_CODE, DocumentNo, DocumentRef -->
		                                      	<td style="text-align:left" nowrap>
													<?php echo $ManNOPD; ?>
		                                        	<input type="hidden" id="data[<?php echo $currentRow; ?>][BR_CODE]" name="data[<?php echo $currentRow; ?>][BR_CODE]" value="<?php echo $BR_CODE; ?>" class="form-control" style="max-width:300px;" readonly>
		                                            <input type="hidden" id="data<?php echo $currentRow; ?>DocumentNo" name="data[<?php echo $currentRow; ?>][DocumentNo]" value="<?php echo $DocumentNo; ?>" class="form-control" style="max-width:300px;" readonly>
		                                            <input type="hidden" id="data<?php echo $currentRow; ?>DocumentRef" name="data[<?php echo $currentRow; ?>][DocumentRef]" value="<?php echo $DocumentRef; ?>" class="form-control" style="max-width:300px;" readonly>										</td>
		                                        <!-- Description -->
		                                      	<td style="text-align:left">
		                                        	<?php echo $Description; ?>
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][Description]" id="data<?php echo $currentRow; ?>Description" value="<?php echo $Description; ?>" class="form-control" style="max-width:600px;" >                                      	</td>
		                                        <td style="text-align:right" nowrap>
		                                        	<?php echo number_format($GInv_Amount, $decFormat); ?>
		                                        	<input type="hidden" name="INV_Amount<?php echo $currentRow; ?>" id="INV_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($Inv_Amount, $decFormat); ?>" class="form-control" style="min-width:120px; max-width:200px; text-align:right" size="20" disabled >
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][Inv_Amount]" id="data<?php echo $currentRow; ?>Inv_Amount" value="<?php echo $Inv_Amount; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][GInv_Amount]" id="data<?php echo $currentRow; ?>GInv_Amount" size="10" value="<?php echo $GInv_Amount; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                        </td>
		                                        <td style="text-align:right;" nowrap>
		                                        	<?php
		                                        		echo number_format($PINVPAIDAM, $decFormat);
		                                        	?>
		                                        	<input type="hidden" name="GTOT_PAIDAMN<?php echo $currentRow; ?>" id="GTOT_PAIDAMN<?php echo $currentRow; ?>" value="<?php echo $PINVPAIDAM; ?>" size="15" class="form-control" style="min-width:110px; max-width:200px; text-align:right" disabled >
		                                        	<input type="hidden" name="INV_Amount_PPn<?php echo $currentRow; ?>" id="INV_Amount_PPn<?php echo $currentRow; ?>" value="<?php echo number_format($Inv_Amount_PPn, $decFormat); ?>" size="15" class="form-control" style="min-width:110px; max-width:200px; text-align:right" disabled >
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][Inv_Amount_PPn]" id="data<?php echo $currentRow; ?>Inv_Amount_PPn" value="<?php echo $Inv_Amount_PPn; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
		                                        </td>
		                                        <td style="text-align:right; display:none" nowrap><input type="text" name="INV_Amount_PPn<?php echo $currentRow; ?>" id="INV_Amount_PPn<?php echo $currentRow; ?>" value="<?php echo number_format($Inv_Amount_PPn, $decFormat); ?>" size="15" class="form-control" style="min-width:110px; max-width:200px; text-align:right" disabled >
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][Inv_Amount_PPn]" id="data<?php echo $currentRow; ?>Inv_Amount_PPn" value="<?php echo $Inv_Amount_PPn; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
		                                        </td>
		                                      	<!-- Amount -->
		                                      	<td style="text-align:right" nowrap>
		                                      		<?php echo number_format($GAmount, 2); ?>
		                                        	<input type="hidden" name="GAmount<?php echo $currentRow; ?>" id="GAmount<?php echo $currentRow; ?>" value="<?php echo number_format($GAmount, 2); ?>" class="form-control" style="min-width:150px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgRecAmount(this,<?php echo $currentRow; ?>);" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][Amount]" id="data<?php echo $currentRow; ?>Amount" size="10" value="<?php echo $Amount; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][GAmount]" id="data<?php echo $currentRow; ?>GAmount" size="10" value="<?php echo $GAmount; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][GInv_Remain]" id="data<?php echo $currentRow; ?>GInv_Remain" size="10" value="<?php echo $GPINV_REMAIN; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                        </td>
		                                        <!-- Amount_PPn -->
		                                      	<td style="text-align:right; display:none" nowrap>
		                                        	<input type="text" name="Amount_PPn<?php echo $currentRow; ?>" id="Amount_PPn<?php echo $currentRow; ?>" value="<?php echo number_format($Amount_PPn, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:200px; text-align:right" onKeyPress="return isIntOnlyNew(event);"  onBlur="chgRecAmountPPn(this,<?php echo $currentRow; ?>);" size="15" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][Amount_PPn]" id="data<?php echo $currentRow; ?>Amount_PPn" value="<?php echo $Amount_PPn; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                        </td>
		                                      	<td style="text-align:left;">
		                                      		<?php echo $Notes; ?>
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][Notes]" id="data<?php echo $currentRow; ?>Notes" value="<?php echo $Notes; ?>" class="form-control" style="max-width:500px;" >
		                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                                        </td>
		                                  </tr>
		                              <?php
		                                    endforeach;
		                                }
		                            }
		                            ?>
		                            <input type="hidden" name="totREMAMN" id="totREMAMN" value="<?=$totREMAMN?>">
		                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                        </table>
		                    </div>
		                </div>
		            </div>

	                <div class="col-sm-12">
	                	<div class="box box-warning">
	                        <div class="box-header with-border">
	                        	<?php if($BR_STAT == 1 || $BR_STAT == 4) { ?>
									<button class="btn btn-warning" type="button" onClick="addAcc();">
	                                <?php echo $Account; ?>&nbsp;&nbsp;[ <i class="glyphicon glyphicon-plus"></i> ]
	                                </button>
	                        	<?php } else { ?>
	                        		<h3 class="box-title center"><?php echo $OthRecCB; ?></h3>
	                        	<?php } ?>
	                        </div>
	                        <div class="box-body">
					            <div class="search-table-outter">
					                <table id="tbl_acc" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                        	<tr style="background:#CCCCCC">
		                              	<th width="2%" height="25" style="text-align:left">&nbsp;</th>
		                              	<th width="10%" style="text-align:center"><?php echo $AccountNo; ?> </th>
		                              	<th width="33%" style="text-align:center"><?php echo $AccountName; ?> </th>
		                              	<th width="15%" style="text-align:center"><?php echo $RefNumber; ?> </th>
		                              	<th width="5%" style="text-align:center">Posisi</th>
		                              	<th width="10%" style="text-align:center"><?php echo $Amount_; ?></th>
		                              	<th width="30%" style="text-align:center"><?php echo $Remarks; ?></th>
		                          	</tr>
		                            <?php
		                            $accRow 	= 0;
		                            $editable	= 0;
		                            if($BR_STAT == 1 || $BR_STAT == 4)
                                    {
                                    	$editable	= 1;
                                    }
		                            if($task == 'edit')
		                            {
		                                $sqlDET	= "SELECT A.JournalH_Code, A.Acc_Id, A.Acc_Name, A.proj_Code, A.proj_CodeHO, A.PRJPERIOD,
		                                				A.Journal_DK, A.JournalD_Debet, A.JournalD_Kredit, A.Ref_Number, A.Other_Desc
													FROM tbl_journaldetail_br A
													WHERE JournalH_Code = '$JournalH_Code' 
														AND A.proj_Code = '$PRJCODE'";
		                                $result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;
										foreach($result as $row) :
											$accRow  	= ++$i;
											$JournalH_Code 	= $row->JournalH_Code;
											$Acc_Id 		= $row->Acc_Id;
											$Acc_Name 		= $row->Acc_Name;
											$PRJCODE 		= $row->proj_Code;
											$proj_CodeHO 	= $row->proj_CodeHO;
											$PRJPERIOD 		= $row->PRJPERIOD;
											$Journal_DK 	= $row->Journal_DK;
											if($Journal_DK == 'D')
												$Base_Kredit 	= $row->JournalD_Debet;
											else
												$Base_Kredit 	= $row->JournalD_Kredit;

											$Ref_Number 	= $row->Ref_Number;
											$Other_Desc 	= $row->Other_Desc;

	                                        $BR_TOTAM 		= $BR_TOTAM + $Base_Kredit;
											
											/*if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}*/
											?> 
		                                    <tr id="tr_<?php echo $accRow; ?>">
		                                        <td height="25" style="text-align:center;" nowrap>
		                                            <?php
		                                                if($BR_STAT == 1 || $BR_STAT == 4)
		                                                {

		                                                    ?>
		                                                    <a href="#" onClick="deleteRow(<?php echo $accRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
		                                                    <?php
		                                                }
		                                                else
		                                                {
		                                                    echo "$accRow.";
		                                                }
		                                            ?>
		                                        </td>
		                                        <td style="text-align:left" nowrap>
		                                        	<?php if($editable == 1) { ?>
		                                            	<input type="text" name="dataACC[<?php echo $accRow; ?>][Acc_Id]" id="dataACC<?php echo $accRow; ?>Acc_Id" value="<?php echo $Acc_Id; ?>" class="form-control" style="max-width:150px;" onClick="selectAcc(<?php echo $accRow; ?>);" placeholder="<?php echo $SelectAccount; ?>" >
		                                        	<?php } else { ?>
													  	<?php echo $Acc_Id; ?>
		                                            	<input type="hidden" name="dataACC[<?php echo $accRow; ?>][Acc_Id]" id="dataACC<?php echo $accRow; ?>Acc_Id" value="<?php echo $Acc_Id; ?>" class="form-control" style="max-width:150px;" onClick="selectAcc(<?php echo $accRow; ?>);" placeholder="<?php echo $SelectAccount; ?>" >
		                                        	<?php } ?>

		                                            <input type="hidden" name="dataACC[<?php echo $accRow; ?>][JournalH_Code]" id="dataACC<?php echo $accRow; ?>JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" >
		                                            <input type="hidden" name="dataACC[<?php echo $accRow; ?>][proj_Code]" id="dataACC<?php echo $accRow; ?>proj_Code" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:150px;" >
		                                            <input type="hidden" name="dataACC[<?php echo $accRow; ?>][proj_CodeHO]" id="dataACC<?php echo $accRow; ?>proj_CodeHO" value="<?php echo $proj_CodeHO; ?>" class="form-control" style="max-width:150px;" >
		                                            <input type="hidden" name="dataACC[<?php echo $accRow; ?>][PRJPERIOD]" id="dataACC<?php echo $accRow; ?>PRJPERIOD" value="<?php echo $PRJPERIOD; ?>" class="form-control" style="max-width:150px;" >
		                                        </td>
		                                        <td style="text-align:left" nowrap>
		                                        	<?php if($editable == 1) { ?>
		                                            	<input type="text" name="dataACC[<?php echo $accRow; ?>][JournalD_Desc]" id="dataACC<?php echo $accRow; ?>JournalD_Desc" value="<?php echo $Acc_Name; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description_; ?>">
		                                        	<?php } else { ?>
													  	<?php echo $Acc_Name; ?>
		                                            	<input type="hidden" name="dataACC[<?php echo $accRow; ?>][JournalD_Desc]" id="dataACC<?php echo $accRow; ?>JournalD_Desc" value="<?php echo $Acc_Name; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description_; ?>">
		                                        	<?php } ?>
		                                        </td>
		                                        <td style="text-align:left" nowrap>
		                                        	<?php if($editable == 1) { ?>
			                                            <input type="text" name="dataACC[<?php echo $accRow; ?>][Ref_Number]" id="dataACC<?php echo $accRow; ?>Ref_Number" value="<?php echo $Ref_Number; ?>" class="form-control" style="max-width:150px;" placeholder="<?php echo $RefNumber; ?>">
		                                        	<?php } else { ?>
		                                        		<?php echo $Ref_Number; ?>
			                                            <input type="hidden" name="dataACC[<?php echo $accRow; ?>][Ref_Number]" id="dataACC<?php echo $accRow; ?>Ref_Number" value="<?php echo $Ref_Number; ?>" class="form-control" style="max-width:150px;" placeholder="<?php echo $RefNumber; ?>" >
		                                        	<?php } ?>
		                                        </td>
		                                        <td style="text-align:center;" nowrap>
		                                        	<?php if($editable == 1) { ?>
			                                            <select name="dataACC[<?php echo $accRow; ?>][Journal_DK]" id="dataACC<?php echo $accRow; ?>Journal_DK" class="form-control select2" style="max-width:100px" onChange="countAmount()">
			                                                <option value="" <?php if($Journal_DK == "") { ?> selected <?php } ?>>--</option>
			                                                <option value="D" <?php if($Journal_DK == "D") { ?> selected <?php } ?>>D</option>
			                                                <option value="K" <?php if($Journal_DK == "K") { ?> selected <?php } ?>>K</option>
			                                            </select>
		                                        	<?php } else { ?>
		                                        		<?php echo $Journal_DK; ?>
			                                            <input type="hidden" name="dataACC[<?php echo $accRow; ?>][Journal_DK]" id="dataACC<?php echo $accRow; ?>Journal_DK" value="<?php echo $Journal_DK; ?>" class="form-control">
		                                        	<?php } ?>
		                                        </td>
		                                        <td style="text-align:right" nowrap>
		                                        	<?php if($editable == 1) { ?>
			                                            <input type="text" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $accRow; ?>" id="JournalD_Amount<?php echo $accRow; ?>" value="<?php echo number_format($Base_Kredit, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $accRow; ?>)" onKeyPress="return isIntOnlyNew(event);" title="<?php echo number_format($Base_Kredit, 2); ?>">
		                                        	<?php } else { ?>
		                                        		<?php echo number_format($Base_Kredit, 2); ?>
			                                            <input type="hidden" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $accRow; ?>" id="JournalD_Amount<?php echo $accRow; ?>" value="<?php echo number_format($Base_Kredit, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $accRow; ?>)" onKeyPress="return isIntOnlyNew(event);" title="<?php echo number_format($Base_Kredit, 2); ?>">
		                                        	<?php } ?>
		                                            
		                                            <input type="hidden" name="dataACC[<?php echo $accRow; ?>][JournalD_Amount]" id="dataACC<?php echo $accRow; ?>JournalD_Amount" value="<?php echo $Base_Kredit; ?>" class="form-control" style="max-width:150px;" >
		                                        </td>
		                                        <td style="text-align:left" nowrap>
		                                        	<?php if($editable == 1) { ?>
			                                            <input type="text" name="dataACC[<?php echo $accRow; ?>][Other_Desc]" id="dataACC<?php echo $accRow; ?>Other_Desc" size="20" value="<?php echo $Other_Desc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
		                                        	<?php } else { ?>
		                                        		<?php echo $Other_Desc; ?>
			                                            <input type="hidden" name="dataACC[<?php echo $accRow; ?>][Other_Desc]" id="dataACC<?php echo $accRow; ?>Other_Desc" size="20" value="<?php echo $Other_Desc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
		                                        	<?php } ?>
		                                            
		                                        </td>
							          		</tr>
		                              	<?php
		                             	endforeach;
									}
									?>
		                        	<input type="hidden" name="BR_TOTAM" id="BR_TOTAM" value="<?php echo $BR_TOTAM; ?>">
		                            <input type="hidden" name="BR_TOTAM_PPn" id="BR_TOTAM_PPn" value="<?php echo $BR_TOTAM_PPn; ?>">
		                          	<input type="hidden" name="totalrowAcc" id="totalrowAcc" value="<?php echo $accRow; ?>">
		                        </table>
		                    </div>
		                </div>
		            </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
							<?php								
								if($disableAll == 0)
								{
									if(($BR_STAT == 2 || $BR_STAT == 7) && $canApprove == 1)
									{
										?>
											<button class="btn btn-primary" id="btnSave">
											<i class="fa fa-save"></i></button>&nbsp;
										<?php
									}
								}
							
                                echo anchor("$backURL",'<button class="btn btn-danger" id="btnBack" type="button"><i class="fa fa-reply"></i></button>');
                            ?>
                        </div>
                    </div>
			    </form>
			</div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
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
		$('#datepicker').datepicker({
		autoclose: true
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

	// START : LOCK PROCEDURE
		$(document).ready(function()
		{
			setInterval(function(){chkAppStat()}, 1000);
		});

		function chkAppStat()
		{
			var url         = "<?php echo site_url('lck/appStat')?>";
			let DOC_DATE 	= $('#datepicker').val();
			console.log(DOC_DATE);
			
				
			$.ajax({
				type: 'POST',
				url: url,
				data: {DOC_DATE:DOC_DATE},
				dataType: "JSON",
				success: function(response)
				{
					// var arrVar      = response.split('~');
					// var arrStat     = arrVar[0];
					// var arrAlert    = arrVar[1];
					// var LockCateg 	= arrVar[2];
					// var app_stat    = document.getElementById('app_stat').value;

					let LockY		= response[0].LockY;	
					let LockM		= response[0].LockM;	
					let LockCateg	= response[0].LockCateg;	
					let isLockJ		= response[0].isLockJ;	
					let LockJDate	= response[0].LockJDate;	
					let UserJLock	= response[0].UserJLock;	
					let isLockT		= response[0].isLock;	
					let LockTDate	= response[0].LockDate;	
					let UserLockT	= response[0].UserLock;
					console.log("isLockT ="+isLockT+" isLockJ = "+isLockJ+" LockCateg = "+LockCateg);

					if(isLockJ == 1)
					{
						$('#alrtLockJ').css('display','');
						document.getElementById('divAlert').style.display   = 'none';
						$('#BR_STAT>option[value="3"]').attr('disabled','disabled');
						// document.getElementById('btnSave').style.display    = '';
					}
					else
					{
						$('#alrtLockJ').css('display','none');
						document.getElementById('divAlert').style.display   = 'none';
						$('#BR_STAT>option[value="3"]').removeAttr('disabled','disabled');
						// document.getElementById('btnSave').style.display    = '';
					}

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							$('#alrtLockJ').css('display','');
							document.getElementById('divAlert').style.display   = 'none';
							$('#BR_STAT').removeAttr('disabled','disabled');
							$('#BR_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = '';
							$('#BR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#BR_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = 'none';
							$('#BR_STAT').removeAttr('disabled','disabled');
							$('#BR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = 'none';
							$('#BR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#BR_STAT').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}
				}
			});
		}
    // END : LOCK PROCEDURE
  
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
		var BR_NUMx 	= "<?php echo $DocNumber; ?>";
		
		var BR_CODEx 	= "<?php echo $BR_CODE; ?>";
		ilvl = arrItem[1];
		
		//validateDouble(arrItem[0],arrItem[1])
		//if(validateDouble(arrItem[0],arrItem[1]))
		//{
			//swal("Double Item for " + arrItem[0]);
			//return;
		//}
		
		PRINV_NUM 			= arrItem[0];
		PRINV_DESC 			= arrItem[1];
		PRINV_AMOUNT 		= arrItem[2];
		PRINV_AMOUNT_PPn	= arrItem[3];
		PRINV_AMOUNT_PPh	= arrItem[4];
		PRINV_AMOUNT_OTH	= arrItem[5];
		PRINV_NOTES 		= arrItem[6];
		PINV_Number 		= arrItem[7];
		
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="data'+intIndex+'JournalH_Code" name="data['+intIndex+'][JournalH_Code]" value="'+BR_NUMx+'" class="form-control" style="max-width:300px;" readonly>';
		
		// No. Realisasi Faktur
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+PRINV_NUM+'<input type="hidden" id="data'+intIndex+'BR_NUM" name="data['+intIndex+'][BR_NUM]" value="'+BR_NUMx+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data['+intIndex+'][BR_CODE]" name="data['+intIndex+'][BR_CODE]" value="'+BR_CODEx+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'DocumentNo" name="data['+intIndex+'][DocumentNo]" value="'+PRINV_NUM+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'DocumentRef" name="data['+intIndex+'][DocumentRef]" value="'+PINV_Number+'" class="form-control" style="max-width:300px;" readonly>';
		
		// Deskripsi
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+PRINV_DESC+'<input type="hidden" name="data['+intIndex+'][Description]" id="data'+intIndex+'Description" size="10" value="'+PRINV_DESC+'" class="form-control" style="max-width:300px;" >';
		
		// Invoice Realization Amount
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="Inv_Amount'+intIndex+'" id="Inv_Amount'+intIndex+'" value="'+PRINV_AMOUNT+'" class="form-control" style="min-width:120px; max-width:150px; text-align:right" ><input type="hidden" name="data['+intIndex+'][Inv_Amount]" id="data'+intIndex+'Inv_Amount" size="10" value="'+PRINV_AMOUNT+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
		
		// Invoice Realization Amount PPn
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="Inv_Amount_PPn'+intIndex+'" id="Inv_Amount_PPn'+intIndex+'" value="'+PRINV_AMOUNT_PPn+'" class="form-control" style="min-width:110px; max-width:130px; text-align:right" ><input type="hidden" name="data['+intIndex+'][Inv_Amount_PPn]" id="data'+intIndex+'Inv_Amount_PPn" size="10" value="'+PRINV_AMOUNT_PPn+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
		
		// Receipt - Amount
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="Amount'+intIndex+'" id="Amount'+intIndex+'" value="0.00" class="form-control" style="min-width:120px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgRecAmount(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][Amount]" id="data'+intIndex+'Amount" size="10" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
		
		// Receipt - Amount PPn
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="Amount_PPn'+intIndex+'" id="Amount_PPn'+intIndex+'" size="10" value="0.00" class="form-control" style="min-width:120px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgRecAmountPPn(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][Amount_PPn]" id="data'+intIndex+'Amount_PPn" size="10" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
		
		// Keterangan
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Notes]" id="data'+intIndex+'Notes" size="15" value="" class="form-control" style="max-width:300px;" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		var decFormat											= document.getElementById('decFormat').value;
		document.getElementById('Inv_Amount'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRINV_AMOUNT)),decFormat));
		document.getElementById('Inv_Amount_PPn'+intIndex).value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRINV_AMOUNT_PPn)),decFormat));
		/*document.getElementById('Amount'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRINV_AMOUNT)),decFormat));
		document.getElementById('Amount_PPn'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PRINV_AMOUNT_PPn)),decFormat));*/
		document.getElementById('totalrow').value = intIndex;
	}
	
	function chgRecAmount(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRow 			= document.getElementById('totalrow').value;
		
		var thisVal			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'Amount').value 		= thisVal;
		document.getElementById('Amount'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		
		var AmountPPn		= 0.1 * parseFloat(thisVal);
		document.getElementById('data'+row+'Amount_PPn').value 	= AmountPPn;
		document.getElementById('Amount_PPn'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AmountPPn)),decFormat));
		
		var TOT_AMOUNT		= 0;
		var TOT_AMOUNT_PPn	= 0;
		for(i=1;i<=totRow;i++)
		{
			var Amount			= document.getElementById('data'+row+'Amount').value;
			var Amount_PPn		= document.getElementById('data'+row+'Amount_PPn').value;
			var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(Amount);
			var TOT_AMOUNT_PPn	= parseFloat(TOT_AMOUNT_PPn) + parseFloat(Amount_PPn);
		}
		document.getElementById('BR_TOTAM').value		= parseFloat(TOT_AMOUNT);
		document.getElementById('BR_TOTAM_PPn').value	= parseFloat(TOT_AMOUNT_PPn);		
	}
	
	function chgRecAmountPPn(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRow 			= document.getElementById('totalrow').value;
		
		var thisVal			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'Amount_PPn').value 	= thisVal;
		document.getElementById('Amount_PPn'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		
		var TOT_AMOUNT		= 0;
		var TOT_AMOUNT_PPn	= 0;
		for(i=1;i<=totRow;i++)
		{
			var Amount			= document.getElementById('data'+row+'Amount').value;
			var Amount_PPn		= document.getElementById('data'+row+'Amount_PPn').value;
			var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(Amount);
			var TOT_AMOUNT_PPn	= parseFloat(TOT_AMOUNT_PPn) + parseFloat(Amount_PPn);
		}
		document.getElementById('BR_TOTAM').value		= parseFloat(TOT_AMOUNT);
		document.getElementById('BR_TOTAM_PPn').value	= parseFloat(TOT_AMOUNT_PPn);
	}
	
	function checkInp(value)
	{
		BR_STAT	= document.getElementById('BR_STAT').value;
		if(BR_STAT == 0)
		{
			swal('<?php echo $alert3; ?>');
			document.getElementById('BR_STAT').focus();
			return false;
		}

		var variable = document.getElementById('btnSave');
		if (typeof variable !== 'undefined' && variable !== null)
		{
			document.getElementById('btnSave').style.display 	= 'none';
		}

		let frm = document.getElementById('frm');
		frm.addEventListener('submit', (e) => {
			console.log(e)
			document.getElementById('btnSave').style.display 	= 'none';
			document.getElementById('btnBack').style.display 	= 'none';
		});
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
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