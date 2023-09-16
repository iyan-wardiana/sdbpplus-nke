<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 30 Januari 2018
	* File Name	= gej_entry_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$comp_color	= $this->session->userdata['comp_color'];

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
$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row->PRJNAME;
endforeach;
if($PRJCODE == $PRJCODE_HO)
	$PRJNAMEHO	= "";
else
	$PRJNAMEHO	= "$PRJNAME : ";

$currentRow = 0;
if($task == 'add')
{
	$PRJPERIOD	= $PRJCODE;
	//$sql 		= "SELECT PRJCODE, PRJPERIOD FROM tbl_project_budg WHERE PRJCODE_HO = '$PRJCODE_HO' AND PRJSTAT = 1 AND BUDG_LEVEL = 2";
	$sql 		= "SELECT PRJCODE, PRJPERIOD FROM tbl_project WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1 AND BUDG_LEVEL = 2";
	$result 	= $this->db->query($sql)->result();
	foreach($result as $row) :
		$PRJCODE 	= $row->PRJCODE;
		$PRJPERIOD 	= $row->PRJPERIOD;
	endforeach;

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

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_pr_header');

	$JMY 	= date('y');
	$JMM 	= date('m');
	
	$sql 	= "tbl_journalheader_gj WHERE JournalType = 'GEJ' AND YEAR(JournalH_Date) = $JMY AND MONTH(JournalH_Date) = '$JMM'";
	$result = $this->db->count_all($sql);
	$myMax 	= $result+1;
	
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
	//echo "lastPatternNumb = $lastPatternNumb<br>";
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
	
	$theTimeCode	= date('YmdHis');
	$DocNumber 		= "$Pattern_Code$PRJCODE$theTimeCode";
	$Manual_No		= "$Pattern_Code-$JMY$JMM$lastPatternNumb";
	//echo "Manual_No = $Manual_No";
	$JournalH_Code	= "$DocNumber";
	$JournalH_Date	= date('d/m/Y');
	$JournalY 		= date('Y');
	$JournalM 		= date('n');
	$JournalH_Desc	= $SOURCEDOC;
	$JournalH_Desc2	= '';
	$REF_NUM		= '';
	if($JournalH_Desc != '')
	{
		$sql1 = "SELECT FPA_CODE FROM tbl_fpa_header
					WHERE FPA_NUM = '$SOURCEDOC'";
		$res1 = $this->db->query($sql1)->result();
		foreach($res1 as $row1) :
			$JournalH_Desc = $row1->FPA_CODE;
		endforeach;
		$REF_NUM	= $SOURCEDOC;
	}
	$PRJCODE		= $PRJCODE;
	$PRJCODE_HO		= $PRJCODE_HO;
	$PRJPERIOD		= $PRJPERIOD;
	$GEJ_STAT		= 1;
	$Journal_Amount	= 0;
	$Pattern_Type	= 'GEJ';
	$SPLCODE		= '';
}
else
{
	$isSetDocNo = 1;
	$JournalH_Code		= $default['JournalH_Code'];
	$DocNumber			= $default['JournalH_Code'];
	$Manual_No			= $default['Manual_No'];
	$JournalH_Date		= $default['JournalH_Date'];
	$JournalY 			= date('Y', strtotime($JournalH_Date));
	$JournalM 			= date('n', strtotime($JournalH_Date));
	$JournalH_Date		= date('d/m/Y',strtotime($JournalH_Date));
	$JournalH_Desc		= $default['JournalH_Desc'];
	$JournalH_Desc2		= $default['JournalH_Desc2'];
	$PRJCODE			= $default['proj_Code'];
	$PRJCODE_HO			= $default['proj_CodeHO'];
	$PRJPERIOD			= $default['PRJPERIOD'];
	$PRJCODE			= $PRJCODE;
	$GEJ_STAT			= $default['GEJ_STAT'];
	$Journal_Amount		= $default['Journal_Amount'];
	$REF_NUM			= $default['REF_NUM'];
	$Pattern_Type		= $default['Pattern_Type'];
	$SPLCODE			= $default['SPLCODE'];
}

// GET Journal Lock
	$disabled 	= 0;
	$getJLock 	= "SELECT * FROM tbl_journal_lock 
					WHERE LockY = $JournalY AND LockM = $JournalM AND isLock = 1 AND UserLock != '$DefEmp_ID'";
	$resJLock 	= $this->db->query($getJLock);
	$countJLock = $resJLock->num_rows();
	if($countJLock == 1) $disabled = 1;
?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers 	= $this->session->userdata['vers'];

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
		$ISDELETE 	= $this->session->userdata['ISDELETE'];
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
			if($TranslCode == 'Account')$Account = $LangTransl;
			if($TranslCode == 'PR_CODE')$PR_CODE = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'SelectAccount')$SelectAccount = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'AccountNo')$AccountNo = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'AccountPosition')$AccountPosition = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
			if($TranslCode == 'JournalCode')$JournalCode = $LangTransl;
			if($TranslCode == 'JournalType')$JournalType = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Reason')$Reason = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Loan')$Loan = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'inpJTrans')$inpJTrans = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'AccountList')$AccountList = $LangTransl;
			if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
			if($TranslCode == 'JobNm')$JobNm = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$docalert1	= 'Peringatan';
			$subTitleH	= "Tambah";
			$subTitleD	= "permintaan pembelian";
			$alert0		= "Masukan deskripsi jurnal transaksi.";
			$alert1		= "Silahkan pilih Nomor Akun.";
			$alert2		= "Silahkan tuliskan deskripsi jurnal.";
			$alert3		= "Tentukan posisi akun; Debit atau Kredit.";
			$alert4		= "Tentukan posisi jenis transaksi, pajak atau bukan.";
			$alert5		= "Masukan nomor referensi jurnal transaksi.";
			$alert6		= "Nilai transaksi tidak boleh 0.";
			$alert7		= "Belum ada akun untuk sisi Debit.";
			$alert8		= "Belum ada akun untuk sisi Kredit.";
			$alert9		= "Nilai Debit dan Kredit tidak sama.";
			$alert10	= "Masukan alasan mengapa dokumen ini di-revise / reject.";
			$alert11	= "Silahkan pilih nama supplier.";
			$alert12	= "Apakah ingin merubah kode manual dengan";
			$alert13	= "Jumlah Voucher LK tidak boleh melebihi sisa realisasi";
			$alert14 	= "Silahkan pilih akun dan posisi akun";
			$cantEmpty 	= "tidak boleh kosong";
			$alertCls1	= "Anda yakin?";
			$alertCls2	= "Sistem akan mengosongkan data inputan Anda.";
			$alertCls3	= "Data Anda aman.";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$docalert1	= 'Warning';
			$subTitleH	= "Add";
			$subTitleD	= "purchase request";
			$alert0		= "Please input description of journal transaction.";
			$alert1		= "Please select account number.";
			$alert2		= "Please write of journal description";
			$alert3		= "Set the Account Position; Debit or Credit.";
			$alert4		= "Set the type transaction, Tax or Non Tax.";
			$alert5		= "Insert Reference Number of transaction";
			$alert6		= "The amount cen not be zero.";
			$alert7		= "Debit Side transaction can not be empty.";
			$alert8		= "Credit Side transaction can not be empty";
			$alert9		= "Debit dan Credit Amount must be same.";
			$alert10	= "Input the reason why you revise/reject this document.";
			$alert11	= "Please select a supplier.";
			$alert12	= "Do you want to change manual code with";
			$alert13	= "The number of Vouchers LK must not exceed the remaining realization";
			$alert14 	= "Please choose account name and account position";
			$cantEmpty 	= "can not empty";
			$alertCls1	= "Are you sure?";
			$alertCls2	= "The system will empty the data you entered.";
			$alertCls3	= "Your data is safe.";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - Journal_Amount
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
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
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
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
				$APPROVE_AMOUNT = $Journal_Amount;
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
		
		$url_AddItem	= site_url('c_gl/cgeje0b28t18/puSA0b28t18/?id=');
	?>

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>
     
    <body class="<?php echo $appBody; ?>">
        <div style="background-color: <?=$comp_color?>">
			<section class="content-header">
				<h1>
				    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/journal.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
				    <small><?php echo $PRJNAME; ?></small>  </h1>
				  <?php /*?><ol class="breadcrumb">
				    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				    <li><a href="#">Tables</a></li>
				    <li class="active">Data tables</li>
				  </ol><?php */?>
			</section>
			<!-- Main content -->
			<section class="content">
		    	<div class="row">
			        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
			            <input type="hidden" name="SEL_ROW" id="SEL_ROW" value="<?php echo $IS_LAST; ?>" data-toggle="modal" data-target="#mdl_addACC">
			            <input type="hidden" name="modalTarget" id="modalTarget" value="" data-toggle="modal">
			            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
			            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
			            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
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
						<div class="col-md-7">
							<div class="box box-primary">
								<div class="box-header with-border" style="display: none;">
									<i class="fa fa-cloud-upload"></i>
									<h3 class="box-title">&nbsp;</h3>
								</div>
								<div class="box-body">
									<?php if($isSetDocNo == 0) { ?>
						                <div class="form-group">
						                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
						                    <div class="col-sm-9">
						                        <div class="alert alert-danger alert-dismissible">
						                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
						                            <?php echo $docalert2; ?>
						                        </div>
						                    </div>
						                </div>
					                <?php } ?>
					            	<div class="form-group" style="display: none;">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $JournalCode; ?></label>
					                    <div class="col-sm-9">
					                    	<input type="text" class="form-control" style="text-align:left" name="JournalH_Code1" id="JournalH_Code1" size="30" value="<?php echo $DocNumber; ?>" disabled />
					                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="JournalH_Code" id="JournalH_Code" size="30" value="<?php echo $DocNumber; ?>" />
					                        <input type="text" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
					                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="proj_CodeHO" id="proj_CodeHO" size="30" value="<?php echo $PRJCODE_HO; ?>" />
					                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJPERIOD" id="PRJPERIOD" size="30" value="<?php echo $PRJPERIOD; ?>" />
					                    </div>
					                </div>
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?></label>
					                    <div class="col-sm-3">
					                    	<input type="text" class="form-control" name="Manual_No" id="Manual_No" size="30" value="<?php echo $Manual_No; ?>" />
					                    	<!-- <input type="hidden" class="form-control" name="Manual_No" id="Manual_No" size="30" value="<?php // echo $Manual_No; ?>" /> -->
					                    </div>
					                    <div class="col-sm-1">
					                    	<label><input type="checkbox" class="minimal-red" id="chkManual" name="chkManual" placeholder="Centang jika ingin membuat kode manual"></label>
					                    	<input type="hidden" class="form-control" name="isManual" id="isManual" size="30" value="0" />
					                    </div>
					                    <div class="col-sm-5">
					                    	<label for="inputName" class="control-label"><?php echo $JournalType; ?></label>
					                    </div>
					                </div>
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
					                    <div class="col-sm-4">
					                    	<div class="input-group date">
					                        <div class="input-group-addon">
					                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="JournalH_Date" class="form-control pull-left" id="datepicker" value="<?php echo $JournalH_Date; ?>" onChange="addUCODE()"></div>
					                    </div>
					                    <div class="col-sm-5">
					                    	<select name="Pattern_Type1" id="Pattern_Type1" class="form-control select2" onChange="chkSPL(this.value)">
					                          <option value="GEJ" <?php if($Pattern_Type == 'GEJ') { ?> selected <?php } ?>><?php echo $GeneralJournal; ?></option>
					                          <option value="TLK" <?php if($Pattern_Type == 'TLK') { ?> selected <?php } ?>><?php echo "Transfer LK"; ?></option>
					                          <option hidden value="LOAN" <?php if($Pattern_Type == 'LOAN') { ?> selected <?php } ?>><?php echo $Loan; ?></option>
					                        </select>
					                    	<input type="hidden" class="form-control" id="proj_Code" name="proj_Code" size="20" value="<?php echo $PRJCODE; ?>" />
					                    	<input type="hidden" class="form-control" id="Pattern_Type" name="Pattern_Type" size="20" value="<?php echo $Pattern_Type; ?>" />
					                    </div>
						                <script>
											function chkSPL(GEJTYPE)
											{
												let totalrow 		= $('#totalrow').val();

												$('#Pattern_Type').val(GEJTYPE);
												if(GEJTYPE == 'GEJ' || GEJTYPE == 'TLK')
												{
													document.getElementById('splID').style.display = 'none';
													if(totalrow > 0)
													{
														for(let i=1;i<=totalrow;i++) {
															$('#tr_'+i).closest('tr').remove();
															$('#totalrow').val(0);
															// $('#data'+i+'Ref_Number').attr('type', 'text');
															// $('#Ref_Number'+i).attr('type', 'hidden');
														}
													}
												}
												else
												{
													document.getElementById('splID').style.display = '';
													if(totalrow > 0)
													{
														for(let i=1;i<=totalrow;i++) {
															$('#tr_'+i).closest('tr').remove();
															$('#totalrow').val(0);
															// $('#data'+i+'Ref_Number').attr('type', 'text');
															// $('#Ref_Number'+i).attr('type', 'hidden');
														}
													}
												}
											}
										</script>
					                </div>   
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Budget; ?></label>
					                    <div class="col-sm-9">
					                    	<select name="PRJCODEX" id="PRJCODEX" class="form-control" disabled>
					                          <option value="none">---</option>
					                          <?php
					                            if($countPRJ > 0)
					                            {
					                                foreach($vwPRJ as $row) :
					                                    $PRJCODE1 	= $row->PRJCODE;
					                                    $PRJNAME1 	= $row->PRJNAME;
					                                    ?>
					                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJNAME1"; ?></option>
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
					                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="proj_Code" name="proj_Code" size="20" value="<?php echo $PRJCODE; ?>" />                        
					                    </div>
					                </div>
					                <?php
										$sqlSPL	= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = '1'";
										$resSPL	= $this->db->query($sqlSPL)->result();
										foreach($resSPL as $rowSPL) :
											$SPLCODE1	= $rowSPL->SPLCODE;
											$SPLDESC1	= $rowSPL->SPLDESC;
										endforeach;
									?>
					            	<div class="form-group" id="splID" <?php if($Pattern_Type == 'GEJ' || $Pattern_Type == 'TLK') { ?> style="display:none" <?php } ?>>
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Budget; ?></label>
					                    <div class="col-sm-9">
					                        <select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $SupplierName; ?>" style="width:350px;">
					                        	<option value=""> --- </option>
												<?php
					                            $i = 0;
												$sqlSPLC	= "tbl_supplier WHERE SPLSTAT = '1'";
												$resSPLC	= $this->db->count_all($sqlSPLC);
					                            if($resSPLC > 0)
					                            {
													$sqlSPL	= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = '1'";
													$resSPL	= $this->db->query($sqlSPL)->result();
					                                foreach($resSPL as $rowSPL) :
					                                    $SPLCODE1	= $rowSPL->SPLCODE;
					                                    $SPLDESC1	= $rowSPL->SPLDESC;
					                                    ?>
					                                        <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLDESC1 - $SPLCODE1"; ?></option>
					                                    <?php
					                                endforeach;
					                            }
					                            else
					                            {
					                                ?>
					                                    <option value="">--- No Vendor Found ---</option>
					                                <?php
					                            }
					                            ?>
					                        </select>                      
					                    </div>
					                </div>
					            	<div class="form-group" style="display:none">
					                    <label for="inputName" class="col-sm-3 control-label">Referen.</label>
					                    <div class="col-sm-9">
					                    	<input type="text" class="form-control" style="max-width:180px;" name="REF_NUM" id="REF_NUM" size="30" value="<?php echo $REF_NUM; ?>" />
					                    </div>
					                </div>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="box box-warning">
								<div class="box-header with-border" style="display: none;">
									<i class="fa fa-cloud-upload"></i>
									<h3 class="box-title">&nbsp;</h3>
								</div>
								<div class="box-body">
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Description; ?></label>
					                    <div class="col-sm-9">
					                    	<textarea name="JournalH_Desc" class="form-control" id="JournalH_Desc" style="height: 82px"><?php echo $JournalH_Desc; ?></textarea>
					                        <input type="hidden" name="DefEmp_ID" id="DefEmp_ID" value="<?php echo $DefEmp_ID; ?>">
					                        <input type="hidden" name="Journal_Amount" id="Journal_Amount" value="<?php echo $Journal_Amount; ?>">
					                        <input type="hidden" name="Journal_AmountD" id="Journal_AmountD" value="<?php echo $Journal_Amount; ?>">
					                        <input type="hidden" name="Journal_AmountK" id="Journal_AmountK" value="<?php echo $Journal_Amount; ?>">
					                    </div>
					                </div>
					            	<div class="form-group" <?php if($JournalH_Desc2 == '' ) { ?> style="display:none" <?php } ?> id="tblReason">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Reason; ?></label>
					                    <div class="col-sm-9">
					                    	<textarea name="JournalH_Desc2" class="form-control" id="JournalH_Desc2" cols="30"><?php echo $JournalH_Desc2; ?></textarea>  
					                    </div>
					                </div>
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
					                    <div class="col-sm-6">
					                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $GEJ_STAT; ?>">
											<?php
												// START : FOR ALL APPROVAL FUNCTION
													$disButton	= 0;
													$disButtonE	= 0;
													if($task == 'add')
													{
														if($ISCREATE == 1 || $ISAPPROVE == 1)
														{
															?>
																<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" >
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
													}
													else
													{
														$disButton	= 1;
														if(($ISCREATE == 1 && $ISAPPROVE == 1))
														{
															if($GEJ_STAT == 1 || $GEJ_STAT == 4)
															{
																$disButton	= 0;
																?>
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" >
																		<option value="1">New</option>
																		<option value="2">Confirm</option>
																	</select>
																<?php
															}
															elseif($GEJ_STAT == 2 || $GEJ_STAT == 7)
															{
																$disButton	= 0;
																if($canApprove == 0)
																	$disButton	= 1;
																
																$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
																$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
																if($resCAPPHE > 0)
																	$disButton	= 1;
															
																?>
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																		<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																		<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																		<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?> >Approved</option>
																		<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																		<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																		<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																		<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																		<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
																		<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?>>Void</option>
																		<?php } ?>
																	</select>
																<?php
															}
															elseif($GEJ_STAT == 3)
															{
																$disButton	= 0;
																if($canApprove == 0)
																	$disButton	= 1;

																$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
																$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
																if($resCAPPHE > 0)
																	$disButton	= 1;
																if($ISDELETE == 1 || $ISAPPROVE == 1)
																	$disButton	= 0;

																?>
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																		<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																		<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																		<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																		<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
																		<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																		<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																		<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																		<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?>>Void</option>
																	</select>
																<?php
															}
														}
														elseif($ISAPPROVE == 1)
														{
															if($GEJ_STAT == 1 || $GEJ_STAT == 4)
															{
																$disButton	= 1;
																?>
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																		<option value="1">New</option>
																		<option value="2">Confirm</option>
																	</select>
																<?php
															}
															else if($GEJ_STAT == 2 || $GEJ_STAT == 3 || $GEJ_STAT == 7)
															{
																$disButton	= 0;
																if($canApprove == 0)
																	$disButton	= 1;
																	
																$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
																$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
																if($resCAPPHE > 0)
																	$disButton	= 1;
															
																?>
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																		<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																		<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																		<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																		<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																		<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																		<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																		<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																		<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
																		<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> >Void</option>
																		<?php } ?>
																	</select>
																<?php
															}
														}
														elseif($ISCREATE == 1)
														{
															if($GEJ_STAT == 1 || $GEJ_STAT == 4)
															{
																$disButton	= 0;
																?>
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																		<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> >New</option>
																		<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																		<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																		<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
																		<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
																		<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																		<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																		<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
																		<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																		<?php } ?>
																	</select>
																<?php
															}
															else
															{
																$disButton	= 1;
																?>
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2"  onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																		<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																		<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> disabled >Confirm</option>
																		<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																		<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
																		<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
																		<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																		<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																		<?php if($GEJ_STAT == 3 || $GEJ_STAT == 9) { ?>
																		<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																		<?php } ?>
																	</select>
																<?php
															}
														}
													}
												// END : FOR ALL APPROVAL FUNCTION
					                        ?>
					                    </div>
					                    <div class="col-sm-3">
											<?php
							                   if($ISCREATE == 1 && ($GEJ_STAT == 1 || $GEJ_STAT == 4))
							                    {
							                        ?>
						                                <button class="btn btn-success pull-right" type="button" onClick="add_listAcc();">
						                                <?php echo $Account; ?>&nbsp;&nbsp;[ <i class="glyphicon glyphicon-plus"></i> ]
						                                </button>
													<?php
							                    }
							                ?>
					                    </div>
						                <script>
											function selStat(thisValue)
											{
												if(thisValue == 4 || thisValue == 5 || thisValue == 9)
												{
													//document.getElementById('btnSave').style.display = '';
													document.getElementById('tblReason').style.display = '';
												}
												else
												{
													//document.getElementById('btnSave').style.display = 'none';
													document.getElementById('tblReason').style.display = 'none';
												}
											}

						                    var url = "<?php echo $url_AddItem;?>";
						                    function selectAccount(theRow)
						                    {
						                        collData	= '<?php echo $PRJCODE;?>~'+theRow;
												document.getElementById('SEL_ROW').value 	= theRow;

												showAllData(collData)
						                        /*title = 'Select Item';
						                        w = 800;
						                        h = 550;
						                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
						                        var left = (screen.width/2)-(w/2);
						                        var top = (screen.height/2)-(h/2);
						                        return window.open(url+PRJCODE+'&theRow='+theRow, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);*/
						                    }

						                    function selectRefNUM(thisRow)
						                    {
						                    	let PRJCODE			= $('#PRJCODE').val();
						                    	let JournalH_Code	= $('#JournalH_Code').val();
						                    	let collData 		= PRJCODE+'~'+JournalH_Code+'~'+thisRow;

						                    	// alert(collData);

						                    	showModalData(collData);
						                    }
										</script>
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
                        	<div class="search-table-outter">
		                       	<table id="tbl" class="table table-bordered table-striped">
		                        <!-- <table width="100%" border="1" id="tbl"> -->
		                        	<tr style="background:#CCCCCC">
		                              	<th width="2%" height="25" style="text-align:left">&nbsp;</th>
		                              	<th width="10%" style="text-align:center"><?php echo $AccountNo; ?> </th>
		                              	<th width="30%" style="text-align:center"><?php echo $AccountName; ?> </th>
		                              	<th width="10%" style="text-align:center">Proyek</th>
		                              	<th width="30%" style="text-align:center"><?php echo $Remarks; ?></th>
		                              	<th width="0%" style="text-align:center; display: none;"><?php echo $RefNumber; ?> </th>
		                              	<th width="13%" style="text-align:center"><?php echo $Amount; ?></th>
		                              	<th width="5%" style="text-align:center" nowrap><?php echo $AccountPosition; ?> </th>
		                          	</tr>
		                            <?php
		                            $editable	= 0;
									$TotD 	= 0;
									$TotK 	= 0;
		                            if($GEJ_STAT == 1 || $GEJ_STAT == 4)
                                    {
                                    	$editable	= 1;
                                    }
		                            if($task == 'edit')
		                            {
		                                $sqlDET	= "SELECT A.JournalH_Code, A.Acc_Id, A.proj_Code, A.JOBCODEID, A.JournalD_Debet, 
														A.JournalD_Kredit, A.isDirect, 
														A.Notes, A.ITM_CODE, A.Ref_Number, A.Other_Desc, A.Journal_DK, A.isTax,
														A.ITM_CATEG
													FROM tbl_journaldetail_gj A
													WHERE JournalH_Code = '$JournalH_Code'";
		                                $result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;
										
										foreach($result as $row) :
											$currentRow  		= ++$i;
											$JournalH_Code 		= $row->JournalH_Code;
											$Acc_Id 			= $row->Acc_Id;
											$JOBCODEID 			= $row->JOBCODEID;
											$projCode 			= $row->proj_Code;
											$JournalD_Debet 	= $row->JournalD_Debet;
											$JournalD_Debet_tax = 0;
											$JournalD_Kredit 	= $row->JournalD_Kredit;
											$JournalD_Kredit_tax= 0;
											$isDirect 			= $row->isDirect;
											$Notes 				= $row->Notes;
											$ITM_CODE 			= $row->ITM_CODE;
											$ITM_CATEG 			= $row->ITM_CATEG;
											$Ref_Number 		= $row->Ref_Number;
											$Other_Desc 		= $row->Other_Desc;
											$Journal_DK 		= $row->Journal_DK;
											$isTax 				= $row->isTax;

											// get TLK_CODE / Voucher Code :
											$this->db->select("TLK_CODE");
											$this->db->from("tbl_tsflk");
											$this->db->where(["TLK_NUM" => $Ref_Number, "PRJCODE" => $PRJCODE]);
											$Ref_Number1 = $this->db->get()->row("TLK_CODE");

											$prjCodeVW 			= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

											$JOBPARENT 			= "";
											$JOBDESCPR      	= "";
							                $sqlJOBPAR      	= "SELECT JOBCODEID AS JOBPARENT, JOBDESC FROM tbl_joblist_detail_$prjCodeVW
							                						WHERE JOBCODEID IN (SELECT A.JOBPARENT FROM tbl_joblist_detail_$prjCodeVW A
							                						WHERE JOBCODEID = '$JOBCODEID')";
							                $resJOBPAR      	= $this->db->query($sqlJOBPAR)->result();
							                foreach($resJOBPAR as $rowJP) :
							                    $JOBPARENT  	= $rowJP->JOBPARENT;
							                    $JOBDESCPR  	= strtoupper($rowJP->JOBDESC);
							                endforeach;

							                if($JOBCODEID == "")
							                {
							                	$addVw1 		= "";
							                	$addVw2a 		= "";
							                	$addVw2b 		= "";
							                }
							                else
							                {
							                	$addVw1 		= 	"<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
																  		<label style='white-space:nowrap'>".$Account." : ".$Acc_Id."</label>
																  	</div>";
							                	$addVw2a 		= 	"$JOBCODEID : ";
							                	$addVw2b 		=  	"<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
																  		<label style='white-space:nowrap'>".$JobNm." : ".$JOBPARENT." ".$JOBDESCPR."</label>
																  	</div>";
							                }
											
											if($Journal_DK == 'D')
											{
												$AmountV		= $JournalD_Debet;
												$TotD 			= $TotD + $JournalD_Debet;
											}
											else
											{
												$AmountV		= $JournalD_Kredit;
												$TotK 			= $TotK + $JournalD_Kredit;
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
												$ITM_CODEV 	= $ITM_CODE;
												$sqlITM		= "SELECT ITM_NAME FROM tbl_item_$prjCodeVW WHERE ITM_CODE = '$ITM_CODE'";
												$resITM 	= $this->db->query($sqlITM)->result();
												foreach($resITM as $rowITM) :
													$ITM_NAME 	= $rowITM->ITM_NAME;
												endforeach;
											}
											else
											{
												$ITM_CODEV 	= $Acc_Id;
												$sqlITM		= "SELECT Account_NameId FROM tbl_chartaccount_$prjCodeVW 
																WHERE Account_Number = '$Acc_Id'";
												$resITM 	= $this->db->query($sqlITM)->result();
												foreach($resITM as $rowITM) :
													$ITM_NAME 	= $rowITM->Account_NameId;
												endforeach;
											}
											
											/*if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}*/
											?> 
		                                    <tr id="tr_<?php echo $currentRow; ?>">
		                                        <td height="25" style="text-align:center;" nowrap>
		                                            <?php
		                                                if($GEJ_STAT == 1 || $GEJ_STAT == 4)
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
		                                        </td>
		                                        <td style="text-align:left" nowrap>
		                                        	<?php if($editable == 1) { ?>
		                                            	<input type="text" name="data[<?php echo $currentRow; ?>][Acc_Id]" id="data<?php echo $currentRow; ?>Acc_Id" value="<?php echo $Acc_Id; ?>" class="form-control" style="max-width:150px;" onClick="selectAccount(<?php echo $currentRow; ?>);" placeholder="<?php echo $SelectAccount; ?>" >
		                                        	<?php } else { ?>
		                                        		<?php echo $ITM_CODEV; ?>
													  	<?php echo $addVw1; ?>
		                                            	<input type="hidden" name="data[<?php echo $currentRow; ?>][Acc_Id]" id="data<?php echo $currentRow; ?>Acc_Id" value="<?php echo $Acc_Id; ?>" class="form-control" style="max-width:150px;" onClick="selectAccount(<?php echo $currentRow; ?>);" placeholder="<?php echo $SelectAccount; ?>" >
		                                        	<?php } ?>

		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalH_Code]" id="data<?php echo $currentRow; ?>JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][proj_Code]" id="data<?php echo $currentRow; ?>proj_Code" value="<?php echo $projCode; ?>" class="form-control" style="max-width:150px;" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][proj_CodeHO]" id="data<?php echo $currentRow; ?>proj_CodeHO" value="<?php echo $PRJCODE_HO; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data[<?php echo $currentRow; ?>][PRJPERIOD]" id="data<?php echo $currentRow; ?>PRJPERIOD" value="<?php echo $PRJPERIOD; ?>" class="form-control" style="max-width:150px;" >
		                                        </td>
		                                        <td style="text-align:left" nowrap>
		                                        	<?php if($editable == 1) { ?>
		                                            	<input type="text" name="data[<?php echo $currentRow; ?>][JournalD_Desc]" id="data<?php echo $currentRow; ?>JournalD_Desc" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>">
		                                        	<?php } else { ?>
		                                        		<?php echo $addVw2a.ucwords(strtolower($ITM_NAME)); ?>
													  	<?php echo $addVw2b; ?>
		                                            	<input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Desc]" id="data<?php echo $currentRow; ?>JournalD_Desc" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>">
		                                        	<?php } ?>

		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CODE]" id="data<?php echo $currentRow; ?>ITM_CODE" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $ItemCode; ?>">
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CATEG]" id="data<?php echo $currentRow; ?>ITM_CATEG" value="<?php echo $ITM_CATEG; ?>" class="form-control" style="max-width:500px;">
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" style="max-width:500px;">
		                                        </td>
		                                        <td style="text-align:left" nowrap>
		                                        	<?php if($editable == 1) { ?>
			                                        	<select name="data[<?php echo $currentRow; ?>][proj_Code]" id="proj_Code<?php echo $currentRow; ?>" class="form-control" style="min-width: 80px; max-width:150px">
			                                        		<option value=""> --- </option>
		                                        			<?php
		                                        				$s_01 	= "SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' ORDER BY proj_Code";
		                                        				$r_01 	= $this->db->query($s_01)->result();
		                                        				foreach($r_01 as $rw_01):
		                                        					$proj_Code 	= $rw_01->proj_Code;
		                                        					?>
		                                        						<option value="<?php echo $proj_Code?>" <?php if($proj_Code == $projCode) { ?> selected <?php } ?>><?php echo $proj_Code?></option>
		                                        					<?php
		                                        				endforeach;
		                                        			?>
		                                        		</select>
		                                        	<?php } else { ?>
		                                        		<?php echo $projCode; ?>
			                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][proj_Code]" id="data<?php echo $currentRow; ?>proj_Code" size="20" value="<?php echo $projCode; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
		                                        	<?php } ?>
		                                        </td>
		                                        <td style="text-align:left" nowrap>
		                                        	<?php if($editable == 1) { ?>
			                                            <input type="text" name="data[<?php echo $currentRow; ?>][Other_Desc]" id="data<?php echo $currentRow; ?>Other_Desc" size="20" value="<?php echo $Other_Desc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
		                                        	<?php } else { ?>
		                                        		<?php echo $Other_Desc; ?>
			                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][Other_Desc]" id="data<?php echo $currentRow; ?>Other_Desc" size="20" value="<?php echo $Other_Desc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
		                                        	<?php } ?>
		                                            
		                                        </td>
		                                        <td nowrap style="text-align:center; display:none" nowrap>
		                                            <select name="data[<?php echo $currentRow; ?>][isTax]" id="data<?php echo $currentRow; ?>isTax" class="form-control" style="max-width:100px" onChange="countAmount()">
		                                                <option value="" <?php if($isTax == "") { ?> selected <?php } ?>>--</option>
		                                                <option value="0" selected >N</option>
		                                                <option value="1" <?php if($isTax == '1') { ?> selected <?php } ?>>Y</option>
		                                            </select>
		                                        </td>
		                                        <td style="text-align:left; display: none;" nowrap>
		                                        	<?php if($editable == 1) { ?>
			                                            <input type="<?php if($Pattern_Type == 'TLK' && $Journal_DK == 'K') echo "text"; else echo "hidden";?>" name="Ref_Number<?php echo $currentRow; ?>" id="Ref_Number<?php echo $currentRow; ?>" value="<?php echo $Ref_Number1; ?>" class="form-control" style="max-width:150px;" placeholder="<?php echo $RefNumber; ?>" onClick="selectRefNUM(<?php echo $currentRow; ?>);">
			                                            <input type="<?php if($Journal_DK == 'D') echo "text"; elseif($Pattern_Type != 'TLK' && $Journal_DK == 'K') echo "text"; else echo "hidden";?>" name="data[<?php echo $currentRow; ?>][Ref_Number]" id="data<?php echo $currentRow; ?>Ref_Number" value="<?php echo $Ref_Number; ?>" class="form-control" style="max-width:150px;" placeholder="<?php echo $RefNumber; ?>" <?php if($Pattern_Type == 'TLK' && $Journal_DK == 'D') echo "disabled" ?>>
		                                        	<?php } else { ?>
		                                        		<?php if($Pattern_Type == 'TLK') echo $Ref_Number1;
		                                        		else echo $Ref_Number; ?>
			                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][Ref_Number]" id="data<?php echo $currentRow; ?>Ref_Number" value="<?php echo $Ref_Number; ?>" class="form-control" style="max-width:150px;" placeholder="<?php echo $RefNumber; ?>" >
		                                        	<?php } ?>
		                                        </td>
		                                        <td style="text-align:right" nowrap>
		                                        	<?php if($editable == 1) { ?>
			                                            <input type="text" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $currentRow; ?>" id="JournalD_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($AmountV, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" title="<?php echo number_format($AmountV, 2); ?>">
		                                        	<?php } else { ?>
		                                        		<?php echo number_format($AmountV, 2); ?>
			                                            <input type="hidden" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $currentRow; ?>" id="JournalD_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($AmountV, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" title="<?php echo number_format($AmountV, 2); ?>">
		                                        	<?php } ?>
		                                            
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Amount]" id="data<?php echo $currentRow; ?>JournalD_Amount" value="<?php echo $AmountV; ?>" class="form-control" style="max-width:150px;" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][isDirect]" id="data<?php echo $currentRow; ?>isDirect" value="1" class="form-control" style="max-width:150px;" >
		                                        </td>
		                                        <td nowrap style="text-align:center" nowrap>
		                                        	<?php if($editable == 1) { ?>
			                                            <select name="data[<?php echo $currentRow; ?>][JournalD_Pos]" id="data<?php echo $currentRow; ?>JournalD_Pos" class="form-control select2" style="max-width:100px" onChange="countAmount()">
			                                                <option value="" <?php if($Journal_DK == "") { ?> selected <?php } ?>>--</option>
			                                                <option value="D" <?php if($Journal_DK == "D") { ?> selected <?php } ?>>D</option>
			                                                <option value="K" <?php if($Journal_DK == "K") { ?> selected <?php } ?>>K</option>
			                                            </select>
		                                        	<?php } else { ?>
		                                        		<?php echo $Journal_DK; ?>
			                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Pos]" id="data<?php echo $currentRow; ?>JournalD_Pos" value="<?php echo $Journal_DK; ?>" class="form-control">
		                                        	<?php } ?>
		                                        </td>
							          		</tr>
		                              	<?php
		                             	endforeach;
									}
									?>
									<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                        </table>
		                    </div>
						</div>
						<div class="col-md-12">
	                        <?php
			                   if($ISCREATE == 1 && ($GEJ_STAT == 1 || $GEJ_STAT == 4))
			                    {
			                        ?>
		                                <button class="btn btn-success" type="button" onClick="add_listAcc();">
		                                <?php echo $Account; ?>&nbsp;&nbsp;[ <i class="glyphicon glyphicon-plus"></i> ]
		                                </button>
									<?php
			                    }
			                ?>
						</div>
		                <br>
						<div class="col-md-12">
							<label for="inputName" class="col-md-8 control-label">&nbsp;</label>
							<div class="col-md-2">
								<label for="inputName" class="control-label">Total Debet</label>
								<input type="text" name="JournalTot_D" id="JournalTot_D" value="<?=number_format($TotD,2);?>" class="form-control" style="text-align: right;" disabled>
							</div>
							<div class="col-md-2">
								<label for="inputName" class="control-label">Total Kredit</label>
								<input type="text" name="JournalTot_K" id="JournalTot_K" value="<?=number_format($TotK,2);?>" class="form-control" style="text-align: right;" disabled>
							</div>
						</div>
		                <div class="col-md-6">
			                <div class="form-group">
			                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
			                    <div class="col-sm-9">
			                        <?php
										$backURL	= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
										if($task=='add')
										{
											if($GEJ_STAT == 1 && $ISCREATE == 1 && $disButton == 0 && $resCAPP != 0)
											{
												?>
													<button class="btn btn-primary" id="btnSave">
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
										else
										{
											if($disButton == 0 && $resCAPP != 0)
											{
												?>
			                                        <button class="btn btn-primary" id="btnSave">
			                                        <i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
										
										//echo anchor("$backURL",'<button class="btn btn-danger" id="btnBack" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Back.'</button>');
			                        ?>
			                        <button class="btn btn-danger" id="btnBack" type="button"><i class="fa fa-reply"></i></button>
			                    </div>
			                </div>
			            </div>

		                <script type="text/javascript">
		                	$('#btnBack').on('click',function(e) 
							{
								var task = "<?php echo $task; ?>";
								var totR = $('#totalrow').val();
								if(totR > 0 && task == "add")
								{
								    swal({
										  title: "<?php echo $alertCls1; ?>",
										  text: "<?php echo $alertCls2; ?>",
										  //icon: "warning",
										  buttons: ["No", "Yes"],
										  //dangerMode: true,
										})
										.then((willDelete) => {
										if (willDelete) 
										{
										    window.location = "<?php echo $backURL; ?>";
										}
									});
								}
								else
								{
									window.location = "<?php echo $backURL; ?>";
								}
							});
		                </script>
					</form>

			        <div class="col-md-12">
						<?php
		                    $DOC_NUM	= $JournalH_Code;
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
							            <div class="box-body no-padding">
			                        		<div class="search-table-outter">
								              	<table id="tbl" class="table table-striped" width="100%" border="0">
													<?php
														$s_STEP		= "SELECT DISTINCT APP_STEP FROM tbl_docstepapp_det
																		WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' ORDER BY APP_STEP";
														$r_STEP		= $this->db->query($s_STEP)->result();
														foreach($r_STEP as $rw_STEP) :
															$STEP	= $rw_STEP->APP_STEP;
															?>
												                <tr>
												                  	<td style="width: 10%" nowrap>Tahap <?=$STEP?></td>
																	<?php
																		$s_00	= "SELECT DISTINCT A.APPROVER_1,
																						CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																					FROM tbl_docstepapp_det A INNER JOIN tbl_employee B ON A.APPROVER_1 = B.Emp_ID
																					WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' AND APP_STEP = $STEP";
																		$r_00	= $this->db->query($s_00)->result();
																		foreach($r_00 as $rw_00) :
																			$APP_EMP_1	= $rw_00->APPROVER_1;
																			$APP_NME_1	= $rw_00->complName;
																			$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
										                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
										                                    if($r_APPH_1 > 0)
										                                    {
										                                    	$s_01	= "SELECT AH_APPROVED FROM tbl_approve_hist
										                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
										                                        $r_01	= $this->db->query($s_01)->result();
										                                        foreach($r_01 as $rw_01):
										                                            $APPDT	= $rw_01->AH_APPROVED;
										                                        endforeach;

										                                    	$APPCOL = "success";
										                                    	$APPIC 	= "check";
										                                    }
										                                    else
										                                    {
										                                    	$APPCOL = "danger";
										                                    	$APPIC 	= "close";
										                                    	$APPDT 	= "-";
										                                    }
																			?>
																				<td style="width: 2%;">
																					<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																						<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																					</div>
																				</td>
																				<td>
																					<?=$APP_NME_1?><br>
																					<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT?></span>
																				</td>
																			<?php
																		endforeach;
																	?>
																</tr>
															<?php
														endforeach;
													?>
								              	</table>
							              	</div>
							            </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
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
						    min-width: 600px;   
						}

						.datatable td span{
						    width: 80%;
						    display: block;
						    overflow: hidden;
						}
			    	</style>
			    	<?php
						$Active1		= "";
						$Active2		= "active";
						$Active1Cls		= "";
						$Active2Cls		= "class='active'";
			    	?>
			        <div class="modal fade" id="mdl_addACC" name='mdl_addACC' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			            <div class="modal-dialog">
				            <div class="modal-content">
				                <div class="modal-body">
									<div class="row">
								    	<div class="col-md-12">
							              	<ul class="nav nav-tabs">
							                    <li id="li1" <?php echo $Active1Cls; ?> style="display: none;">
							                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?php echo $ItemList; ?></a>
							                    </li>	
							                    <li id="li2" <?php echo $Active2Cls; ?>>
							                    	<a href="#itm2" data-toggle="tab" onClick="setType(2)"><?php echo $AccountList; ?></a>
							                    </li>
							                </ul>
								            <div class="box-body">
								            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1" style="display: none;">
			                                        <div class="form-group">
			                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
	                        								<div class="search-table-outter">
					                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					                                                <thead>
					                                                    <tr>
					                                                        <th width="2%">&nbsp;</th>
			                                                                <th width="30%" nowrap><?php echo $ItemCode; ?></th>
			                                                                <th width="68%" nowrap><?php echo $Description; ?> </th>
					                                                  	</tr>
					                                                </thead>
					                                                <tbody>
					                                                </tbody>
					                                            </table>
					                                        </div>
					                                        <br>
	                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
	                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
	                                                    	</button>
	                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
	                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
	                                                    	</button>
	                                      					<button class="btn btn-warning" type="button" id="idRefresh1" title="Refresh" >
	                                                    		<i class="glyphicon glyphicon-refresh"></i>
	                                                    	</button>
			                                            </form>
			                                      	</div>
			                                    </div>

								            	<div class="<?php echo $Active2; ?> tab-pane" id="itm2">
			                                        <div class="form-group">
			                                        	<form method="post" name="frmSearch2" id="frmSearch2" action="">
	                        								<div class="search-table-outter">
					                                            <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					                                                <thead>
				                                                        <tr>
					                                                        <th width="2%" style="text-align: center;">&nbsp;</th>
			                                                                <th width="15%" style="text-align: center;" nowrap><?php echo $AccountCode; ?></th>
			                                                                <th width="68%" style="text-align: center;" nowrap><?php echo $Description; ?></th>
			                                                                <th width="15%" style="text-align: center;" nowrap>Saldo</th>
					                                                  </tr>
					                                                </thead>
					                                                <tbody>
					                                                </tbody>
					                                            </table>
					                                        </div>
					                                        <br>
	                                                    	<button class="btn btn-primary" type="button" id="btnDetail2" name="btnDetail2">
	                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
	                                                    	</button>
	                                      					<button type="button" id="idClose2" class="btn btn-danger" data-dismiss="modal">
	                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
	                                                    	</button>
	                                      					<button class="btn btn-warning" type="button" id="idRefresh2" title="Refresh" >
	                                                    		<i class="glyphicon glyphicon-refresh"></i>
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
		            		<div class="alert alert-info alert-dismissible">
		                        <?php echo "Daftar item yang ditampilkan hanyalah item yang sudah memiliki pengaturan Akun Penggunaan.<br>Untuk mengecek, silahkan buka di Daftar Material."; ?>
		                    </div>
					    </div>
					</div>

					<div class="modal fade" id="mdl_RefNUM" name='mdl_RefNUM' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" class="active">
						                    	<a href="#itm1" data-toggle="tab"><?php echo $ReferenceNumber; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="active tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example3" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
			                                                        <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
			                                                        <th width="10%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Code; ?></th>
			                                                        <th width="10%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Date; ?> </th>
			                                                        <th width="48%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Description; ?></th>
			                                                        <th width="10%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Amount; ?>  </th>
			                                                        <th width="10%" style="vertical-align: middle; text-align: center;" nowrap><?php echo $Remain; ?>  </th>
			                                                  </tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail3" name="btnDetail3">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose3" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh3" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck3" id="rowCheck3" value="0">
                                      	<button type="button" id="idClose3" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

					<script type="text/javascript">
						function hideSelect2Option(data, container) {
						    if(data.element) {
						        $(container).addClass($(data.element).attr("class"));
						        $(container).attr('hidden',$(data.element).attr("hidden"));
						    }
						    return data.text;
						}

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

						function showAllData(collData)
						{
							console.log('PRJCODE = '+collData);
							var myarr 	= collData.split("~");
							PRJCODE 	= myarr[0];
							SELROW 		= myarr[1];

							document.getElementById('SEL_ROW').click();

							$('#example1').DataTable(
					    	{
					    		"bDestroy": true,
						        "processing": true,
					            "serverSide": true,
					            //"scrollX": false,
					            "autoWidth": true,
					            "filter": true,
					            "ajax": "<?php echo site_url('c_gl/cgeje0b28t18/get_AllDataITMS/?id=')?>"+collData, 
					            "type": "POST",
					            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
					            "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
					            "columnDefs": [ { targets: [0], className: 'dt-body-center' },
					                          ],
					            "language": {
					                "infoFiltered":"",
					                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					            },
							});

					    	$('#example2').DataTable(
					    	{
					    		"bDestroy": true,
						        "processing": true,
					            "serverSide": true,
					            //"scrollX": false,
					            "autoWidth": true,
					            "filter": true,
					            "ajax": "<?php echo site_url('c_gl/cgeje0b28t18/get_AllDataCOA/?id=')?>"+collData,
					            "type": "POST",
					            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
					            "lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
					            "columnDefs": [ { targets: [0], className: 'dt-body-center' },
					                            { targets: [3], className: 'dt-body-right' }
					                          ],
					            "language": {
					                "infoFiltered":"",
					                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					            },
							});

					    
						   	$("#idRefresh1").click(function()
						    {
								$('#example1').DataTable().ajax.reload();
						    });
					    
						   	$("#idRefresh2").click(function()
						    {
								$('#example2').DataTable().ajax.reload();
						    });
						}

						function showModalData(collData)
						{
							$('#modalTarget').attr("data-target", "#mdl_RefNUM");
							$('#modalTarget').click();

							$('#example3').DataTable(
					    	{
					    		"bDestroy": true,
						        "processing": true,
					            "serverSide": true,
					            //"scrollX": false,
					            "autoWidth": true,
					            "filter": true,
					            "ajax": "<?php echo site_url('c_gl/cgeje0b28t18/get_AllDataTLK/?id=')?>"+collData,
					            "type": "POST",
					            //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
					            "lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
					            "columnDefs": [ { targets: [0,1,2], className: 'dt-body-center' },
					                            { targets: [4,5], className: 'dt-body-right' }
					                          ],
					            "language": {
					                "infoFiltered":"",
					                "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					            },
							}).ajax.reload();

							$("#idRefresh3").click(function()
						    {
								$('#example3').DataTable().ajax.reload();
						    });
						}

						var selectedRows = 0;
						function pickThis1(thisobj) 
						{
							var favorite = [];
							$.each($("input[name='chk_']:checked"), function() {
						      	favorite.push($(this).val());
						    });
						    $("#rowCheck").val(favorite.length);
						}

						function pickThis3(thisobj) 
						{
							var favorite = [];
							$.each($("input[name='chk_tlk']:checked"), function() {
						      	favorite.push($(this).val());
						    });
						    // alert(favorite);
						    $("#rowCheck3").val(favorite.length);
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

							    $.each($("input[name='chk_']:checked"), function()
							    {
							      	add_item($(this).val());
							    });

							    $('#mdl_addItm').on('hidden.bs.modal', function () {
								    $(this)
									    .find("input,textarea,select")
										    //.val('')
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

							    $.each($("input[name='chk_']:checked"), function()
							    {
							      	add_item($(this).val());
							    });

							    $('#mdl_addItm').on('hidden.bs.modal', function () {
								    $(this)
									    .find("input,textarea,select")
										    //.val('')
										    .end()
									    .find("input[type=checkbox], input[type=radio]")
									       .prop("checked", "")
									       .end();
								});
	                        	document.getElementById("idClose").click()
						    });

						    $("#btnDetail3").click(function()
						    {
							    $.each($("input[name='chk_tlk']:checked"), function()
							    {
							      	add_RefD($(this).val());
							    });

							    $('#mdl_RefNUM').on('hidden.bs.modal', function () {
								    $(this)
									    .find("input,textarea,select")
										    //.val('')
										    .end()
									    .find("input[type=checkbox], input[type=radio]")
									       .prop("checked", "")
									       .end();
								});
	                        	document.getElementById("idClose3").click()
						    });
						});
					</script>
		    	<!-- ============ END MODAL =============== -->

                <?php
                    $DefID      = $this->session->userdata['Emp_ID'];
                    $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    if($DefID == 'D15040004221')
                        echo "<font size='1'><i>$act_lnk</i></font>";
                ?>
			</section>
		</div>
	</body>
</html>

<script>
  	$(function ()
  	{
  		let DEF_EMP 	= $('#DefEmp_ID').val();

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
	    // LOCK DATE
	    /*$('#datepicker').datepicker({
			autoclose: true,
			startDate: '-6d',
			endDate: '+6d'
	    });*/
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
		if(DEF_EMP == 'L14030003372')
		{
		    $('#datepicker').datepicker({
				autoclose: true,
				startDate: '-1000d',
				endDate: '+0d'
		    });
		}
		else
		{
		    $('#datepicker').datepicker({
				autoclose: true,
				startDate: '-60d',
				endDate: '+0d'
		    });
		}

	    //Date picker
	    // LOCK DATE
	    /*$('#datepicker2').datepicker({
	      autoclose: true,
		  startDate: '-6d'
	    });*/
	    $('#datepicker2').datepicker({
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

	    $('#Pattern_Type1').select2({templateResult: hideSelect2Option});

	    $('input:checkbox[name="chkManual"]').on('ifChanged', function(e) {
	    	if(e.target.checked == true)
	    		$("#isManual").val(1)
	    	else
	    		$("#isManual").val(0)
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
			let DEF_EMP 	= $('#DefEmp_ID').val(); 			// L14030003372
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

					if(isLockJ == 1 && DEF_EMP != 'L14030003372')
					{
						$('#alrtLockJ').css('display','');
						document.getElementById('divAlert').style.display   = 'none';
						$('#GEJ_STAT>option[value="3"]').attr('disabled','disabled');
						// document.getElementById('btnSave').style.display    = '';
					}
					else
					{
						$('#alrtLockJ').css('display','none');
						document.getElementById('divAlert').style.display   = 'none';
						$('#GEJ_STAT>option[value="3"]').removeAttr('disabled','disabled');
						// document.getElementById('btnSave').style.display    = '';
					}

					if(isLockT == 1 && DEF_EMP != 'L14030003372')
					{
						if(LockCateg == 1 && DEF_EMP != 'L14030003372')
						{
							$('#alrtLockJ').css('display','');
							document.getElementById('divAlert').style.display   = 'none';
							$('#GEJ_STAT').removeAttr('disabled','disabled');
							$('#GEJ_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = '';
							$('#GEJ_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#GEJ_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1 && DEF_EMP != 'L14030003372')
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = 'none';
							$('#GEJ_STAT').removeAttr('disabled','disabled');
							$('#GEJ_STAT>option[value="3"]').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = 'none';
							$('#GEJ_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#GEJ_STAT').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}
				}
			});
		}
    // END : LOCK PROCEDURE

	<?php
		// START : GENERATE MANUAL CODE
			if($task == 'add')
			{
				?>
					$(document).ready(function()
					{
						setInterval(function(){addUCODE()}, 1000);
					});
				<?php
			}
		// END : GENERATE MANUAL CODE
	?>

	function addUCODE()
	{
		var task 		= "<?=$task?>";
		var isManual	= document.getElementById('isManual').value;
		var DOCNUM		= document.getElementById('JournalH_Code').value;
		var DOCCODE		= document.getElementById('Manual_No').value;
		var DOCDATE		= document.getElementById('datepicker').value;
		var ACC_ID		= "";
		var PDManNo 	= "";

		var formData 	= {
							PRJCODE 		: "<?=$PRJCODE?>",
							MNCODE 			: "<?=$MenuCode?>",
							DOCNUM 			: DOCNUM,
							DOCCODE 		: DOCCODE,
							DOCCODE2		: PDManNo,
							DOCDATE 		: DOCDATE,
							ACC_ID 			: ACC_ID,
							DOCTYPE 		: 'GEJ'
						};
		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('__l1y/getLastDocNum')?>",
            data: formData,
            success: function(response)
            {
            	var arrVar 	= response.split('~');
            	var docNum 	= arrVar[0];
            	var docCode	= arrVar[1];
            	var payCode = arrVar[2];
            	var ACCBAL 	= arrVar[3];

            	if(isManual == 0)
            	{
	            	$('#Manual_No').val(docCode);
	            	// $('#Manual_No1').val(docCode);
	            }
            }
        });
	}
  
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
	
	function add_listAcc(strItem) 
	{
		var objTable, objTR, objTD, intIndex;
		let Pattern_Type = $('#Pattern_Type').val();
		
		JournalH_Code		= '';
		Acc_Id				= '';
		ITM_CODE			= '';
		JOBCODEID			= '';
		JournalD_Desc		= '';
		Currency_id			= 'IDR';
		JournalD_Debet		= 0;
		JournalD_Debet_tax	= 0;
		JournalD_Kredit		= 0;
		JournalD_Kredit_tax	= 0;
		Base_Debet 			= 0;
		Base_Debet_tax		= 0;
		Base_Kredit			= 0;
		Base_Kredit_tax		= 0;
		COA_Debet 			= 0;
		COA_Debet_tax		= 0;
		COA_Kredit 			= 0;
		COA_Kredit_tax		= 0;
		Ref_Number			= '';
		JournalD_Pos		= 'D';
		
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrow').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl');
			intTable 		= objTable.rows.length;

			// check row before
			let rowBF 	= intIndex - 1;
			if(intIndex > 1)
			{
				// check pos kredit Ref_Number can not empty
				let Acc_Id 			= $('#data'+rowBF+'Acc_Id').val();
				let JournalD_Pos 	= $('#data'+rowBF+'JournalD_Pos').val();
				let Ref_Number 		= $('#data'+rowBF+'Ref_Number').val();
				if(JournalD_Pos == '' || Acc_Id == '')
				{
					swal("<?php echo $alert14; ?>");
					return false;
				}
				else
				{
					if(Pattern_Type == 'TLK')
					{
						if(JournalD_Pos == 'K' && Ref_Number == '')
						{
							swal("<?php "$ReferenceNumber $cantEmpty"; ?>", 
							{
								icon: "warning"
							});
							return false;
						}
					}
				}
			}

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		//intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		
		// Account Icon
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Account Number
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][JournalH_Code]" id="data'+intIndex+'JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][proj_Code]" id="data'+intIndex+'proj_Code" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][proj_CodeHO]" id="data'+intIndex+'proj_CodeHO" value="<?php echo $PRJCODE_HO; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][PRJPERIOD]" id="data'+intIndex+'PRJPERIOD" value="<?php echo $PRJPERIOD; ?>" class="form-control" style="max-width:150px;" ><input type="text" name="data['+intIndex+'][Acc_Id]" id="data'+intIndex+'Acc_Id" value="'+Acc_Id+'" class="form-control" onClick="selectAccount('+intIndex+');" placeholder="<?php echo $SelectAccount; ?>" >';
		
		// Account Description
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][ITM_CODE]" id="data'+intIndex+'ITM_CODE" value="'+ITM_CODE+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $ItemCode; ?>"><input type="text" name="data['+intIndex+'][JournalD_Desc]" id="data'+intIndex+'JournalD_Desc" value="'+JournalD_Desc+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>"><input type="hidden" name="data['+intIndex+'][ITM_CATEG]" id="data'+intIndex+'ITM_CATEG" value="'+JournalD_Desc+'" class="form-control" style="max-width:500px;"><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" style="max-width:500px;">';
		
		// Project Code
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = '<select name="data['+intIndex+'][proj_Code]" id="proj_Code'+intIndex+'" class="form-control" style="min-width: 80px; max-width:150px"><option value=""> --- </option><?php $s_01 	= "SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' ORDER BY proj_Code"; $r_01 	= $this->db->query($s_01)->result(); foreach($r_01 as $rw_01): $proj_Code 	= $rw_01->proj_Code; ?> <option value="<?php echo $proj_Code?>"><?php echo $proj_Code?></option> <?php endforeach; ?></select>';
		
		// Remarks
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Other_Desc]" id="data'+intIndex+'Other_Desc" value="" class="form-control" style="text-align:left" placeholder="<?php echo $Remarks; ?>">';
		
		// Account is Tax
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.display = 'none';
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<select name="data['+intIndex+'][isTax]" id="data'+intIndex+'isTax" class="form-control" style="max-width:100px" onChange="countAmount()"><option value="">--</option><option value="0" selected>N</option><option value="1">Y</option></select>';
		
		// Account Reference
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.style.display = 'none';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="hidden" name="Ref_Number'+intIndex+'" id="Ref_Number'+intIndex+'" value="'+Ref_Number+'" class="form-control" placeholder="<?php echo $RefNumber; ?>" onClick="selectRefNUM('+intIndex+')"><input type="text" name="data['+intIndex+'][Ref_Number]" id="data'+intIndex+'Ref_Number" value="'+Ref_Number+'" class="form-control" placeholder="<?php echo $RefNumber; ?>">';
		
		// Account Amount
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right" name="JournalD_Amount'+intIndex+'" id="JournalD_Amount'+intIndex+'" value="'+JournalD_Debet+'" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][JournalD_Amount]" id="data'+intIndex+'JournalD_Amount" value="'+JournalD_Debet+'" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][isDirect]" id="data'+intIndex+'isDirect" value="1" class="form-control" style="max-width:150px;" >';
		
		// Account Position
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'left';
			objTD.innerHTML = '<select name="data['+intIndex+'][JournalD_Pos]" id="data'+intIndex+'JournalD_Pos" class="form-control select2" style="width:100%" onChange="countAmount()"><option value="">--</option><option value="D">D</option><option value="K">K</option></select>';
		
		var decFormat														= document.getElementById('decFormat').value;
		var JournalD_Amount													= document.getElementById('JournalD_Amount'+intIndex).value
		document.getElementById('JournalD_Amount'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(JournalD_Amount)),decFormat));
		document.getElementById('data'+intIndex+'JournalD_Amount').value 	= parseFloat(Math.abs(JournalD_Amount));
		document.getElementById('totalrow').value = intIndex;

		if(Pattern_Type == 'TLK')
		{
			$('#data'+intIndex+'Ref_Number').attr('type', 'hidden');
			$('#Ref_Number'+intIndex).attr('type', 'text');
			$('#Ref_Number'+intIndex).prop('disabled', true);
		}
		else
		{
			$('#data'+intIndex+'Ref_Number').attr('type', 'text');
			$('#Ref_Number'+intIndex).attr('type', 'hidden');
			$('#Ref_Number'+intIndex).prop('disabled', false);
		}
	}
	
	function chgAmount(thisval, row)
	{
		var decFormat													= document.getElementById('decFormat').value;
		var Acc_Amount		= parseFloat(eval(thisval).value.split(",").join(""));
		document.getElementById('JournalD_Amount'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat));
		document.getElementById('data'+row+'JournalD_Amount').value 	= parseFloat(Math.abs(Acc_Amount));
		
		var totRow = document.getElementById('totalrow').value;
		var totAmount	= 0;
		var totAmountD	= 0;
		var totAmountK	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'JournalD_Pos');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ values)
			
			if(values != null)
			{
				JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
				totAmountA 		= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
				//totAmount 	= parseFloat(totAmount) + parseFloat(totAmountA);
				if(JournalD_Pos == 'D')
					totAmountD	= parseFloat(totAmountD) + parseFloat(totAmountA);
				else
					totAmountK	= parseFloat(totAmountK) + parseFloat(totAmountA);
			}
		}
		// alert(totAmountD+'='+totAmountK);
		document.getElementById('Journal_Amount').value = totAmountD;
		document.getElementById('Journal_AmountD').value = totAmountD;
		document.getElementById('Journal_AmountK').value = totAmountK;
		
		if(JournalD_Pos == 'D')
		{
			document.getElementById('JournalTot_D').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountD)),decFormat));
			document.getElementById('JournalTot_K').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountK)),decFormat));
		}
		else if(JournalD_Pos == 'K')
		{
			document.getElementById('JournalTot_D').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountD)),decFormat));
			document.getElementById('JournalTot_K').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountK)),decFormat));
		}

		chkamn_tlk(thisval, row);
	}
	
	function countAmount()
	{
		var totRow 		= document.getElementById('totalrow').value;
		let decFormat	= document.getElementById('decFormat').value;
		var totAmount	= 0;
		var totAmountD	= 0;
		var totAmountK	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'JournalD_Pos');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ values)
			
			if(values != null)
			{
				JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
				totAmountA 		= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
				//totAmount 	= parseFloat(totAmount) + parseFloat(totAmountA);
				if(JournalD_Pos == 'D')
				{
					totAmountD	= parseFloat(totAmountD) + parseFloat(totAmountA);
					if($('#Pattern_Type').val() == 'TLK')
					{
						$('#data'+i+'Ref_Number').attr('type', 'hidden');
						$('#Ref_Number'+i).attr('type', 'text');
						$('#Ref_Number'+i).prop('disabled', true);
					}
					else
					{
						$('#data'+i+'Ref_Number').attr('type', 'text');
						$('#Ref_Number'+i).attr('type', 'hidden');
						$('#Ref_Number'+i).prop('disabled', false);
					}
				}
				else
				{
					totAmountK	= parseFloat(totAmountK) + parseFloat(totAmountA);
					if($('#Pattern_Type').val() == 'TLK')
					{
						$('#data'+i+'Ref_Number').attr('type', 'hidden');
						$('#Ref_Number'+i).attr('type', 'text');
						$('#Ref_Number'+i).prop('disabled', false);
					}
					else
					{
						$('#data'+i+'Ref_Number').attr('type', 'text');
						$('#Ref_Number'+i).attr('type', 'hidden');
						$('#Ref_Number'+i).prop('disabled', false);
					}
				}
			}
		}
		document.getElementById('Journal_Amount').value = totAmountD;
		document.getElementById('Journal_AmountD').value = totAmountD;
		document.getElementById('Journal_AmountK').value = totAmountK;

		if(JournalD_Pos == 'D')
		{
			document.getElementById('JournalTot_D').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountD)),decFormat));
			document.getElementById('JournalTot_K').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountK)),decFormat));
		}
		else if(JournalD_Pos == 'K')
		{
			document.getElementById('JournalTot_D').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountD)),decFormat));
			document.getElementById('JournalTot_K').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountK)),decFormat));
		}
	}

	function chkamn_tlk(thisval, row)
	{
		let decFormat													= document.getElementById('decFormat').value;
		let Acc_Amount		= parseFloat(eval(thisval).value.split(",").join(""));
		document.getElementById('JournalD_Amount'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat));
		document.getElementById('data'+row+'JournalD_Amount').value 	= parseFloat(Math.abs(Acc_Amount));
		
		let totRow = document.getElementById('totalrow').value;
		for(let i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'JournalD_Pos');
			let values 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ values)
			
			if(values != null)
			{
				JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
				Ref_Number 		= document.getElementById('data'+i+'Ref_Number').value;
				if(JournalD_Pos == 'K' && Ref_Number)
				{
					let JournalD_Kredit = parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
					let PRJCODE 		= $('#PRJCODE').val();
					$.ajax({
						url: "<?php echo base_url("c_gl/cgeje0b28t18/chkAMNTLK") ?>",
						type: "POST",
						dataType: "JSON",
						data: {TLK_NUM:Ref_Number, PRJCODE:PRJCODE},
						success: function(data) {
							console.log(data);
							let lnTLK 		= data.length;
							let TTLK_AMOUNT = 0;
							let TTLK_REALIZ	= 0;
							let TREM_AMOUNT = 0;
							console.log(lnTLK);
							for(let j=0;j<lnTLK;j++) {
								console.log(data[j].TLK_AMOUNT)
								TTLK_AMOUNT = parseFloat(TTLK_AMOUNT) + parseFloat(data[j].TLK_AMOUNT);
								TTLK_REALIZ	= parseFloat(TTLK_REALIZ) + parseFloat(data[j].TLK_REALIZ);
							}
							console.log(TTLK_AMOUNT+'+'+TTLK_REALIZ);
							TREM_AMOUNT = parseFloat(TTLK_AMOUNT) - parseFloat(TTLK_REALIZ);
							console.log(TREM_AMOUNT);
							if(JournalD_Kredit > TREM_AMOUNT)
							{
								swal("<?php echo $alert13; ?>", {
									icon: "warning",
								}).then(function(){
									$('#JournalD_Amount'+i).focus();
									document.getElementById('JournalD_Amount'+i).value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TREM_AMOUNT)),decFormat));
									document.getElementById('data'+i+'JournalD_Amount').value 	= parseFloat(Math.abs(TREM_AMOUNT));
								});
							}
						}
					});
				}
			}
		}
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		Acc_Id 			= arrItem[0];
		JournalD_Desc 	= arrItem[1];
		Item_Code 		= arrItem[2];
		Item_Categ		= arrItem[3];
		theRow 			= arrItem[4];
		JOBCODEID		= arrItem[5];
		PRJCODE			= $('#PRJCODE').val();
		
		document.getElementById('data'+theRow+'Acc_Id').value			= Acc_Id;
		document.getElementById('data'+theRow+'ITM_CODE').value			= Item_Code;
		document.getElementById('data'+theRow+'JournalD_Desc').value	= JournalD_Desc;
		document.getElementById('data'+theRow+'ITM_CATEG').value		= Item_Categ;
		document.getElementById('data'+theRow+'JOBCODEID').value		= JOBCODEID;

		if(theRow == 1)
		{
			ACC_ID = $('#data'+theRow+'Acc_Id').val();
			// Create Code Manual_No =>
			$.ajax({
				url: "<?php echo base_url("c_gl/cgeje0b28t18/getManualNo"); ?>",
				type: "POST",
				data: {Acc_Id:ACC_ID, PRJCODE:PRJCODE},
				success: function(data) {
					console.log(data);
					swal("<?php echo $alert12; ?> "+data+" ?", {
						icon: 'warning',
						buttons: {
							cancel: "No",
							catch: {
						      	text: "Yes",
						      	value: true,
						    },
						}
					}).then((value) => {
						console.log(value);
						if(value) {
							$('#Manual_No').val(data);
							// $('#Manual_No1').val(data);
						} else {
							return false;
						}
					});
				}
			});
		}
	}

	function validateDoubleREF(code)
	{
		let duplicate 		= false;
		let objTable 		= document.getElementById('tbl');
		let intTable 		= objTable.rows.length;
		let amountRow 		= intTable - 1;
		if(amountRow > 0)
		{
			for(let i=1;i<amountRow;i++) {
				let Ref_Number = $('#data'+i+'Ref_Number').val();
				if(code == Ref_Number)
				{
					duplicate = true;
					break;
				}
			}
		}

		return duplicate;
	}

	function add_RefD(arrRef)
	{
		let totalrow = document.getElementById('totalrow').value;	

		arrItem = arrRef.split('|');
		let TLK_NUM 	= arrItem[0];
		let TLK_CODE 	= arrItem[1];
		let TLK_DATE 	= arrItem[2];
		let TLK_DESC 	= arrItem[3];
		let TREM_AMOUNT	= arrItem[4];
		let TLK_REALZ 	= arrItem[5];
		let theRow 		= arrItem[6];
		let selectRow	= arrItem[7];

		console.log(validateDoubleREF(arrItem[0]));
		if(validateDoubleREF(arrItem[0]))
		{
			swal("Double <?php echo $ReferenceNumber; ?> : " + arrItem[1]);
			return false;
		}

		let JournalD_Pos = document.getElementById('data'+selectRow+'JournalD_Pos').value;
		if(JournalD_Pos == 'K')
		{
			document.getElementById('Ref_Number'+selectRow).value 				= TLK_CODE;
			document.getElementById('data'+selectRow+'Ref_Number').value 		= TLK_NUM;
			document.getElementById('JournalD_Amount'+selectRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TREM_AMOUNT)), 2));
			document.getElementById('data'+selectRow+'JournalD_Amount').value 	= parseFloat(Math.abs(TREM_AMOUNT));
			document.getElementById('data'+selectRow+'Other_Desc').value 		= TLK_DESC;
		}

		// chgAmount
		var totAmount	= 0;
		var totAmountD	= 0;
		var totAmountK	= 0;
		for(let i=1;i<=totalrow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'JournalD_Pos');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ values)
			console.log(i+' = '+ values)
			
			if(values != null)
			{
				JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
				totAmountA 		= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
				//totAmount 	= parseFloat(totAmount) + parseFloat(totAmountA);
				if(JournalD_Pos == 'D')
					totAmountD	= parseFloat(totAmountD) + parseFloat(totAmountA);
				else
					totAmountK	= parseFloat(totAmountK) + parseFloat(totAmountA);
			}
		}
		// alert(totAmountD+'='+totAmountK);
		document.getElementById('Journal_Amount').value = totAmountD;
		document.getElementById('Journal_AmountD').value = totAmountD;
		document.getElementById('Journal_AmountK').value = totAmountK;
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
	
	function validateInData()
	{
		var totrow 			= document.getElementById('totalrow').value;
		var JournalH_Desc 	= document.getElementById('JournalH_Desc').value;
		var totAmountD		= parseFloat(document.getElementById('Journal_AmountD').value);
		var totAmountK		= parseFloat(document.getElementById('Journal_AmountK').value);
		
		Pattern_Type	= document.getElementById("Pattern_Type").value;
		if(Pattern_Type == 'LOAN')
		{
			SPLCODE	= document.getElementById("SPLCODE").value;
			if(SPLCODE == '')
			{
				swal('<?php echo $alert11; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					$('#SPLCODE').focus();
				});
				return false;
			}
		}
		
		if(JournalH_Desc == '')
		{
			swal('<?php echo $alert0; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#JournalH_Desc').focus();
			});
			return false;
		}
		
		var rowD		= 0;
		var rowK		= 0;
		for(let i=1;i<=totrow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'JournalD_Pos');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ values)
			
			if(values != null)
			{
				var Acc_Id 			= document.getElementById('data'+i+'Acc_Id').value;
				var JournalD_Desc 	= document.getElementById('data'+i+'JournalD_Desc').value;
				var JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
				var isTax			= document.getElementById('data'+i+'isTax').value;
				var Ref_Number		= document.getElementById('data'+i+'Ref_Number').value;
				var JournalD_Amount	= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
				var Other_Desc		= document.getElementById('data'+i+'Other_Desc').value;
				
				if(Acc_Id == '')
				{
					swal('<?php echo $alert1; ?>',
					{
						icon: "warning",
					})
					.then(function()
					{
						swal.close();
						document.getElementById('data'+i+'Acc_Id').focus();
					});
					return false;
				}
				
				if(JournalD_Desc == '')
				{
					swal('<?php echo $alert2; ?>',
					{
						icon: "warning",
					})
					.then(function()
					{
						swal.close();
						document.getElementById('data'+i+'JournalD_Desc').focus();
					});
					return false;
				}
				
				if(JournalD_Pos == '')
				{
					swal('<?php echo $alert3; ?>',
					{
						icon: "warning",
					})
					.then(function()
					{
						swal.close();
						document.getElementById('data'+i+'JournalD_Pos').focus();
					});
					return false;
				}
				
				if(isTax == '')
				{
					swal('<?php echo $alert4; ?>',
					{
						icon: "warning",
					})
					.then(function()
					{
						swal.close();
						document.getElementById('data'+i+'isTax').focus();
					});
					return false;
				}
				
				if(Ref_Number == '' && Pattern_Type == 'TLK' && JournalD_Pos == 'K')
				{
					swal('<?php echo $alert5; ?>',
					{
						icon: "warning"
					})
					.then(function(){
						$('#Ref_Number'+i).attr("data-target","#mdl_RefNUM");
						$('#Ref_Number'+i).click();
					});
					return false;
				}
				
				if(JournalD_Amount == 0)
				{
					swal('<?php echo $alert6; ?>',
					{
						icon: "warning",
					})
					.then(function()
					{
						swal.close();
						document.getElementById('JournalD_Amount'+i).focus();
					});
					return false;
				}
				
				if(JournalD_Pos == 'D')
				{
					var rowD	= parseFloat(rowD) + 1;
					var lastD 	= i;
				}
				else
				{
					var rowK	= parseFloat(rowK) + 1;
					var lastK 	= i;
				}
			}
		}
		
		if(rowD == 0)
		{
			swal('<?php echo $alert7; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		
		if(rowK == 0)
		{
			swal('<?php echo $alert8; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		
		var exDK 	= 0;
		var totD 	= parseFloat(totAmountD);
		var totK 	= parseFloat(totAmountK);


		if(totD != totK)
		{
			swal("<?php echo $alert9; ?>", 
			{
				icon: "error",
			})
			.then(function()
			{
				if(parseFloat(totD) > parseFloat(totK))
				{
					for(let j=1;j<=totrow;j++) {
						let JournalD_Pos = document.getElementById('data'+j+'JournalD_Pos').value;
						if(JournalD_Pos == 'D')
						{
							exDK 	= parseFloat(totD - totK);
							newDV	= parseFloat(totD - exDK);
							/* ------------- HOLD ----------------
							document.getElementById('JournalD_Amount'+j).value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(newDV)), 2));
							document.getElementById('data'+j+'JournalD_Amount').value = parseFloat(Math.abs(newDV));
							---------------- END HOLD -------------- */
						}
					}

					/* ------------- HOLD ----------------
					document.getElementById('Journal_Amount').value  = parseFloat(Math.abs(newDV));
					document.getElementById('Journal_AmountD').value = parseFloat(Math.abs(newDV));
					---------------- END HOLD -------------- */


					/* --------------------------------- old proedure -----------------------------------------------------------------
						console.log('aA')
						exDK = parseFloat(totD - totK);
						// START : ADDED TO LAST ROW OF CREDIT
							for(i=1;i<=totrow;i++)
							{
								console.log('aB = '+lastK+' = '+i)
								if(lastK == i)
								{
									let myObj 	= document.getElementById('data'+i+'JournalD_Pos');
									var values 	= typeof myObj !== 'undefined' ? myObj : '';
									console.log('aE = '+i)
									if(values != null)
									{
										console.log('aF = '+i)
										var lastP 	= document.getElementById('data'+i+'JournalD_Pos').value;
										console.log('aG = '+lastP)
										if(lastP == 'K')
										{
											console.log('a1')
											var lastKV 	= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
											console.log('a')
											var newKV 	= parseFloat(lastKV + exDK);
											console.log('b')
											document.getElementById('JournalD_Amount'+i).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(newKV)), 2));
											console.log('c')
											document.getElementById('data'+i+'JournalD_Amount').value 	= parseFloat(Math.abs(newKV));
											console.log('d')
											document.getElementById('Journal_AmountK').value 	= parseFloat(Math.abs(newKV));
										}
									}
								}
							}
						// END : ADDED TO LAST ROW OF CREDIT
					--------------------------------------------------- end old prosedure ----------------------------------------------- */
				}
				else
				{
					for(let j=1;j<=totrow;j++) {
						let JournalD_Pos = document.getElementById('data'+j+'JournalD_Pos').value;
						if(JournalD_Pos == 'D')
						{
							exDK 	= parseFloat(totK - totD);
							newDV	= parseFloat(totD + exDK);
							/* ------------- HOLD ----------------
							document.getElementById('JournalD_Amount'+j).value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(newDV)), 2));
							document.getElementById('data'+j+'JournalD_Amount').value = parseFloat(Math.abs(newDV));
							---------------- END HOLD ---------------- */
						}
					}

					/* ----------------- HOLD ---------------
					document.getElementById('Journal_Amount').value  = parseFloat(Math.abs(newDV));
					document.getElementById('Journal_AmountD').value = parseFloat(Math.abs(newDV));
					---------------- END HOLD -------------- */

					/* ----------------------------------------------------- old prosedure ----------------------------------------------
						exDK = parseFloat(totK - totD);
						// START : ADDED TO LAST ROW OF DEBET
							for(i=1;i<=totrow;i++)
							{
								console.log('aB = '+lastK+' = '+i)
								if(lastD == i)
								{
									let myObj 	= document.getElementById('data'+i+'JournalD_Pos');
									var values 	= typeof myObj !== 'undefined' ? myObj : '';
									console.log('aE = '+i)
									if(values != null)
									{
										console.log('aF = '+i)
										var lastP 	= document.getElementById('data'+i+'JournalD_Pos').value;
										console.log('aG = '+lastP)
										if(lastP == 'D')
										{
											console.log('a1')
											var lastDV 	= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
											console.log('a')
											var newDV 	= parseFloat(lastDV + exDK);
											console.log('b')
											document.getElementById('JournalD_Amount'+i).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(newDV)), 2));
											console.log('c')
											document.getElementById('data'+i+'JournalD_Amount').value 	= parseFloat(Math.abs(newDV));
											console.log('d')
											document.getElementById('Journal_Amount').value 	= parseFloat(Math.abs(newDV));
											document.getElementById('Journal_AmountD').value 	= parseFloat(Math.abs(newDV));
										}
									}
								}
							}
						// END : ADDED TO LAST ROW OF DEBET
					---------------------------------------------------------- end old prosedure ------------------------------------------- */
				}
			});
			return false;
		}
		
		GEJ_STAT	= document.getElementById("GEJ_STAT").value;
		if(GEJ_STAT == 4 || GEJ_STAT == 5 || GEJ_STAT == 9)
		{
			JournalH_Desc2	= document.getElementById('JournalH_Desc2').value;
			if(JournalH_Desc2 == '')
			{
				swal('<?php echo $alert10; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					document.getElementById('JournalH_Desc2').focus();
				});
				return false;
			}
		}
		
		if(totrow == 0)
		{
			swal('<?php echo $inpJTrans; ?>',
			{
				icon: "warning",
			});
			return false;		
		}
		else
		{
			document.getElementById('btnSave').style.display = 'none';
			document.getElementById('btnBack').style.display = 'none';
			document.frm.submit();
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