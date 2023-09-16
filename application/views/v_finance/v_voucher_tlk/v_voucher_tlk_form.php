<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 10 Desember 2021
	* File Name		= v_cho_vcash_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
$LangID 	= $this->session->userdata['LangID'];

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

$sql = "SELECT PRJNAME FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
// HO
$sqlHO 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resHO 	= $this->db->query($sqlHO)->result();
foreach($resHO as $rowHO) :
	$PRJNAME1 = $rowHO->PRJNAME;
endforeach;
if($PRJCODE != $PRJCODE_HO)
	$PRJNAME1	= " ($PRJNAME1)";
else
	$PRJNAME1	= '';

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
	}
	
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;
	
	$sql 	= "tbl_tsflk WHERE YEAR(TLK_DATE) = $yearC AND PRJCODE = '$PRJCODE'";
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
	$theTimeCode	= date('YmdHis');
	$DocNumber 		= "$Pattern_Code$PRJCODE$theTimeCode";
	$TLK_CODE		= "$Pattern_Code$PRJCODE-$lastPatternNumb";
	$TLK_NUM		= "$DocNumber";
	$TLK_DATE		= date('d/m/Y');
	$DATE_NOW 		= date('Y-m-d');
	$JournalY 		= date('Y');
	$JournalM 		= date('n');
	$TLK_DESC		= '';
	$TLK_DESC2 		= '';
	$PRJCODE		= $PRJCODE;
	$TLK_STATUS		= 1;
	$TLK_AMOUNT		= 0;
	$TLK_REALIZ 	= 0;
	$PROP_NUM 		= "";
	$PROP_CODE	 	= "";
	$PROP_VALUE 	= 0;
	$TLK_DATES 		= date('d/m/Y');
	$TLK_DATEE 		= date('d/m/Y');
	$TLK_DATSEUS 	= date('d/m/Y');
	$TLK_DATSEUE 	= date('d/m/Y');
	$PROP_DATE 		= date('d/m/Y');
	$TLK_AMOUNTU 	= 0;
}
else
{
	$isSetDocNo 	= 1;
	$TLK_DATE		= date('d/m/Y', strtotime($TLK_DATE));
	$TLK_DATES		= date('d/m/Y', strtotime($TLK_DATES));
	$TLK_DATEE		= date('d/m/Y', strtotime($TLK_DATEE));
	$TLK_DATSEUS	= date('d/m/Y', strtotime($TLK_DATSEUS));
	$TLK_DATSEUE	= date('d/m/Y', strtotime($TLK_DATSEUE));
	$JournalY 		= date('Y', strtotime($TLK_DATE));
	$JournalM 		= date('n', strtotime($TLK_DATE));
	$DocNumber 		= $TLK_NUM;
}
$DATE_NOW 		= date('Y-m-d');
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
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'CashAccount')$CashAccount = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'SelectAccount')$SelectAccount = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'AccountNo')$AccountNo = $LangTransl;
			if($TranslCode == 'AccountPosition')$AccountPosition = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
			if($TranslCode == 'JournalCode')$JournalCode = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'JournalType')$JournalType = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'UniqCode')$UniqCode = $LangTransl;
			if($TranslCode == 'othInfo')$othInfo = $LangTransl;
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'empName')$empName = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'ItmList')$ItmList = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Supplier')$Supplier = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Peringatan';
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$subTitleD	= "Penggunaan Kas";
			$alert0		= "Masukan deskripsi transaksi.";
			$alert1		= "Silahkan pilih Nomor Proposal.";
			$alert2		= "Silahkan tuliskan deskripsi jurnal.";
			$alert3		= "Tentukan posisi akun; di Debit atau Kredit.";
			$alert4		= "Tentukan posisi jenis transaksi, pajak atau bukan.";
			$alert5		= "Masukan nomor referensi jurnal transaksi.";
			$alert6		= "Masukan jumlah/nilai penggunaan kas.";
			$alert7		= "Belum ada akun untuk sisi Debit.";
			$alert8		= "Belum ada akun untuk sisi Kredit.";
			$alert9		= "Nilai Debit dan Kredit tidak sama.";
			$alert10	= "Masukan alasan mengapa dokumen ini di batalkan.";
			$alert11	= "Pilih nama supplier.";
			$alert12	= "Saldo tidak cukup.";
			$alert13	= "Nama penerima Perjalanan Dinas (PD) tidak boleh kosong.";
			$alert14	= "Nilai voucher tidak boleh kosong.";
			$alert15	= "Melebihi sisa volume anggaran. Maksimum sisa volume ";
			$alert16	= "Melebihi sisa anggaran. Maksimum sisa ";
			$alert17	= "Anda yakin akan menghapus komponen ini?";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Warning';
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$subTitleH	= "Add";
			$subTitleD	= "Cash Payment";
			$alert0		= "Please input description of transaction.";
			$alert1		= "Please select proposal number.";
			$alert2		= "Please write of journal description";
			$alert3		= "Set the Account Position; Debit or Credit.";
			$alert4		= "Set the type transaction, Tax or Non Tax.";
			$alert5		= "Insert Reference Number of transaction";
			$alert6		= "Input the amount / value of cash usage.";
			$alert7		= "Debit Side transaction can not be empty.";
			$alert8		= "Credit Side transaction can not be empty";
			$alert9		= "Debit dan Credit Amount must be same.";
			$alert10	= "Please write a comment why you void this document.";
			$alert11	= "Please select an supplier.";
			$alert12	= "Insufficient cash account balance..";
			$alert13	= "The name of the official loan recipient cannot be empty.";
			$alert14	= "Voucher Value cannot be empty.";
			$alert15	= "Exceeded the remaining budget volume. Maximum remaining volume ";
			$alert16	= "Exceeded the remaining budget. Maximum remaining ";
			$alert17	= "Are you sure wanto delete this component?";
		}
		
		$secAddURL	= site_url('c_purchase/c_purchase_req/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $PRJCODE;

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
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND PRJCODE  = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
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
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				//$appReady	= $APP_STEP;
				//if($resC_App == 0)
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
				
				if($TLK_STATUS == 3 && $resC_App > 0)
				{
					$canApprove = 1;
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
				$APPROVE_AMOUNT = $TLK_AMOUNT;
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
			    <?php echo $mnName; ?>
			    <small><?php echo "$PRJNAME"; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
	        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
	            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
	            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
	            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
	            <input type="Hidden" name="rowCount" id="rowCount" value="0">
                <div class="row">
					<?php
                        // START : LOCK PROCEDURE
                            $app_stat   = $this->session->userdata['app_stat'];
                            if($LangID == 'IND')
                            {
                                $appAlert1  = "Terkunci!";
                                $appAlert2  = "Mohon maaf, saat ini transaksi bulan $MonthVw $JournalY sedang terkunci.";
                            }
                            else
                            {
                                $appAlert1  = "Locked!";
                                $appAlert2  = "Sorry, the transaction month $MonthVw $JournalY is currently locked.";
                            }
                            ?>
                                <input type="hidden" name="app_stat" id="app_stat" value="<?php echo $app_stat; ?>">
                                <div class="col-sm-12" id="divAlert" style="display:none;">
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
					<?php if($isSetDocNo == 0) { ?>
			            <div class="col-sm-12">
			                <div class="form-group">
			                    <div class="col-sm-12">
			                        <div class="alert alert-danger alert-dismissible">
			                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
			                            <?php echo $docalert2; ?>
			                        </div>
			                    </div>
			                </div>
			            </div>
	                <?php } ?>
                    <div class="col-md-6">
                        <div class="box box-primary">
                            <div class="box-body">
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $UniqCode; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" name="TLK_NUM1" id="TLK_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
				                    	<input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="TLK_NUM" id="TLK_NUM" size="30" value="<?php echo $DocNumber; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE_HO" id="PRJCODE_HO" size="30" value="<?php echo $PRJCODE_HO; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo "$Code"; ?></label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" name="TLK_CODE1" id="TLK_CODE1" value="<?php echo $TLK_CODE; ?>" disabled />
				                    	<input type="hidden" class="form-control" name="TLK_CODE" id="TLK_CODE" size="30" value="<?php echo $TLK_CODE; ?>" />
				                    </div>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
				                        	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                        	<input type="text" name="TLK_DATE1" class="form-control pull-left" id="datepicker" value="<?php echo $TLK_DATE; ?>" disabled>
				                        </div>
				                    	<input type="hidden" name="TLK_DATE" class="form-control" id="TLK_DATE" value="<?php echo $TLK_DATE; ?>">
				                    </div>
				                    <div class="col-sm-2">
										<select name="TLK_CATEG" id="TLK_CATEG" class="form-control select2">
											<option value="1">PDA/K</option>
											<option value="2">PDS</option>
										</select>
				                    </div>
				                </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label">Periode</label>
		                          	<div class="col-sm-4">
				                    	<div class="input-group date">
				                        	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                        	<input type="text" name="TLK_DATES" class="form-control pull-left" id="datepicker2" value="<?php echo $TLK_DATES; ?>">
				                        </div>
		                            </div>
		                          	<div class="col-sm-4">
				                    	<div class="input-group date">
				                        	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                        	<input type="text" name="TLK_DATEE" class="form-control pull-left" id="datepicker3" value="<?php echo $TLK_DATEE; ?>">
				                        </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label">Proposal</label>
		                          	<div class="col-sm-6">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mdl_addPR" title="Cari SPP"><i class="glyphicon glyphicon-search"></i></button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="PROP_NUM" id="PROP_NUM" value="<?php echo $PROP_NUM; ?>" >
		                                    <input type="hidden" class="form-control" name="PROP_CODE" id="PROP_CODE" value="<?php echo $PROP_CODE; ?>" >
		                                    <input type="text" class="form-control" name="PROP_CODEX" id="PROP_CODEX" value="<?php echo $PROP_CODE; ?>" data-toggle="modal" data-target="#mdl_addPR" disabled>
		                                </div>
		                            </div>
		                          	<div class="col-sm-4">
		                        		<input type="hidden" class="form-control" style="text-align:right" name="PROP_VALUE" id="PROP_VALUE" value="<?php echo $PROP_VALUE; ?>" />
		                        		<input type="text" class="form-control" style="text-align:right" name="PROP_VALUEX" id="PROP_VALUEX" value="<?php echo number_format($PROP_VALUE, 2); ?>" title="Nilai Proposal" disabled />
		                            </div>
		                        </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Description; ?></label>
				                    <div class="col-sm-10">
				                    	<textarea name="TLK_DESC" class="form-control" id="TLK_DESC" style="height: 75px"><?php echo $TLK_DESC; ?></textarea>
				                    </div>
				                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">Penggunaan & Nilai Transfer</h3>
                            </div>
                            <div class="box-body">
		                        <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">Periode Pengg.</label>
		                          	<div class="col-sm-4">
				                    	<div class="input-group date">
				                        	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                        	<input type="text" name="TLK_DATSEUS" class="form-control pull-left" id="datepicker4" value="<?php echo $TLK_DATSEUS; ?>" onChange="chgUPer();">
				                        </div>
		                            </div>
		                          	<div class="col-sm-4">
				                    	<div class="input-group date">
				                        	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                        	<input type="text" name="TLK_DATSEUE" class="form-control pull-left" id="datepicker5" value="<?php echo $TLK_DATSEUE; ?>" onChange="chgUPer();">
				                        </div>
		                            </div>
		                        </div>
                            	<div class="form-group">
				                	<label for="exampleInputEmail1" class="col-sm-3 control-label">Total</label>
	                            	<div class="col-sm-3">
		                        		<input type="hidden" class="form-control" style="text-align:right" name="TLK_AMOUNTU" id="TLK_AMOUNTU" value="<?php echo $TLK_AMOUNTU; ?>" />
		                        		<input type="text" class="form-control" style="text-align:right" name="TLK_AMOUNTUX" id="TLK_AMOUNTUX" value="<?php echo number_format($TLK_AMOUNTU, 2); ?>" title="Total" disabled />
		                            </div>
				                	<label for="exampleInputEmail1" class="col-sm-3 control-label">Transfer Saat Ini</label>
	                            	<div class="col-sm-3">
		                        		<input type="hidden" class="form-control" style="text-align:right" name="TLK_AMOUNT" id="TLK_AMOUNT" value="<?php echo $TLK_AMOUNT; ?>" />
		                        		<input type="text" class="form-control" style="text-align:right" name="TLK_AMOUNTX" id="TLK_AMOUNTX" value="<?php echo number_format($TLK_AMOUNT, 2); ?>" title="Total" onBlur="chgAmn(this)" onKeyPress="return isIntOnlyNew(event);"  autocomplete="off" placeholder="0.00" />
		                            </div>
									<?php
										$secGTot 	= base_url().'index.php/c_finance/c_cho70d18/getTVCASH/?id=';
									?>
				                    <script>
				                    	function chgAmn(thisval)
				                    	{
				                    		console.log('a')
				                    		let decFormat		= document.getElementById('decFormat').value;
											let TLK_AMOUNT		= parseFloat(eval(thisval).value.split(",").join(""));
											document.getElementById('TLK_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TLK_AMOUNT)),decFormat));
											document.getElementById('TLK_AMOUNT').value 	= parseFloat(Math.abs(TLK_AMOUNT));
				                    	}
									</script>
				                </div>
				                <?php
				                	$sqlCAPPH	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
				                    				AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
				                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
									
									//$disButton 	= 1;
				                	if($resCAPPH == 0)
				                	{
				                		$disButton 		= 1;
				                		if($LangID == 'IND')
										{
											$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
										}
										else
										{
											$zerSetApp	= "There are no arrangements for the approval of this document.";
										}
				                	}
				                	else
										$disButton 	= 0;
				                ?>
				                <div class="form-group">
				                	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
	                            	<div class="col-sm-5">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $TLK_STATUS; ?>">
										<?php
											// START : FOR ALL APPROVAL FUNCTION
												//$disButton	= 0;
												if($task == 'add')
												{
													if($ISCREATE == 1)
													{
														?>
															<select name="TLK_STATUS" id="TLK_STATUS" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																<option value="1">New</option>
																<option value="2">Confirm</option>
															</select>
														<?php
													}
												}
												else
												{
													//$disButton	= 1;
													if($ISCREATE == 1)
													{
														if($TLK_STATUS == 1 || $TLK_STATUS == 4)
														{
															//$disButton	= 0;
															?>
																<select name="TLK_STATUS" id="TLK_STATUS" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														elseif($TLK_STATUS == 2 || $TLK_STATUS == 7)
														{
															//$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;										
															?>
																<select name="TLK_STATUS" id="TLK_STATUS" class="form-control select2" onChange="selStat(this.value)" >
																	<option value="1"<?php if($TLK_STATUS == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($TLK_STATUS == 2) { ?> selected <?php } ?>>Confirm</option>
																	<option value="3"<?php if($TLK_STATUS == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($TLK_STATUS == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($TLK_STATUS == 5) { ?> selected <?php } ?> >Rejected</option>
																	<option value="6"<?php if($TLK_STATUS == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($TLK_STATUS == 7) { ?> selected <?php } ?> >Waiting</option>
																	<option value="9"<?php if($TLK_STATUS == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
														elseif($TLK_STATUS == 3)
														{
															//$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$TLK_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;	
															if($ISDELETE == 1)
																$disButton	= 0;				
														
															?>
																<select name="TLK_STATUS" id="TLK_STATUS" class="form-control select2" >
																	<option value="1"<?php if($TLK_STATUS == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($TLK_STATUS == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																	<option value="3"<?php if($TLK_STATUS == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($TLK_STATUS == 4) { ?> selected <?php } ?> disabled>Revising</option>
																	<option value="5"<?php if($TLK_STATUS == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																	<option value="6"<?php if($TLK_STATUS == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($TLK_STATUS == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<option value="9"<?php if($TLK_STATUS == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
													}
													elseif($ISAPPROVE == 1)
													{
														if($TLK_STATUS == 1 || $TLK_STATUS == 4)
														{
															//$disButton	= 1;
															?>
																<select name="TLK_STATUS" id="TLK_STATUS" class="form-control select2" >
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														elseif($TLK_STATUS == 2 || $TLK_STATUS == 7)
														{
															//$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$TLK_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;						
														
															?>
																<select name="TLK_STATUS" id="TLK_STATUS" class="form-control select2" onChange="selStat(this.value)" >
																	<option value="1"<?php if($TLK_STATUS == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($TLK_STATUS == 2) { ?> selected <?php } ?>>Confirm</option>
																	<option value="3"<?php if($TLK_STATUS == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($TLK_STATUS == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($TLK_STATUS == 5) { ?> selected <?php } ?> >Rejected</option>
																	<option value="6"<?php if($TLK_STATUS == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($TLK_STATUS == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																	<option value="9"<?php if($TLK_STATUS == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
														elseif($TLK_STATUS == 3)
														{
															//$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$TLK_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;					
														
															?>
																<select name="TLK_STATUS" id="TLK_STATUS" class="form-control select2" onChange="selStat(this.value)" >
																	<option value="1"<?php if($TLK_STATUS == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($TLK_STATUS == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																	<option value="3"<?php if($TLK_STATUS == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($TLK_STATUS == 4) { ?> selected <?php } ?> disabled>Revising</option>
																	<option value="5"<?php if($TLK_STATUS == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																	<option value="6"<?php if($TLK_STATUS == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($TLK_STATUS == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<option value="9"<?php if($TLK_STATUS == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
													}
												}
											// END : FOR ALL APPROVAL FUNCTION
				                        ?>
	                            	</div>
	                            </div>
				                <div class="form-group">
				                	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
	                            	<div class="col-sm-9">
		                            	<?php
											/*if($ISAPPROVE == 1)
												$ISCREATE = 1;*/

												if($task == 'add')
												{
													?>
														<button class="btn btn-primary" id="btnSave" <?php if($disButton == 1){ ?> disabled <?php } ?>>
														<i class="fa fa-save"></i>
														</button>&nbsp;
													<?php
												}
												else if(($TLK_STATUS == 1 || $TLK_STATUS == 2 || $TLK_STATUS == 4) && $disButton == 0)
												{
													?>
														<button class="btn btn-primary" id="btnSave" <?php if($disButton == 1){ ?> disabled <?php } ?>>
														<i class="fa fa-save"></i>
														</button>&nbsp;
													<?php
												}
											
											//$backURL	= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
											echo anchor("$backURL",'<button class="btn btn-danger" id="btnBack" type="button"><i class="fa fa-reply"></i></button>');
				                        ?>
	                            	</div>
				                    <script>
										function selStat(thisValue)
										{
											if(thisValue == 4 || thisValue == 5)
											{
												document.getElementById('divRev').style.display = 'none';
											}
											else
											{
												document.getElementById('divRev').style.display = 'none';
												document.getElementById('divRev').value 		= '';
											}
										}
									</script>
	                            </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    	if($resCAPPH == 0)
	                	{
                    		?>
                        		<div class="col-sm-12">
		                			<div class="alert alert-warning alert-dismissible">
						                <?php echo $zerSetApp; ?>
					              	</div>
					            </div>
                            <?php
                        }
                    ?>
	            </div>
				<?php
                    $DOC_NUM	= $TLK_NUM;
                    $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
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
													$HIDE 	= 0;
													?>
										                <tr>
										                  	<td style="width: 10%" nowrap>Tahap <?=$STEP?></td>
															<?php
																$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP'";
							                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
							                                    if($r_APPH_1 > 0)
							                                    {
																	$s_00	= "SELECT DISTINCT A.AH_APPROVER, A.AH_APPROVED,
																					CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																				FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																				WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = $STEP";
																	$r_00	= $this->db->query($s_00)->result();
																	foreach($r_00 as $rw_00) :
																		$APP_EMP_1	= $rw_00->AH_APPROVER;
																		$APP_NME_1	= $rw_00->complName;
																		$APP_DAT_1	= $rw_00->AH_APPROVED;

								                                    	$APPCOL 	= "success";
								                                    	$APPIC 		= "check";
																		?>
																			<td style="width: 2%;">
																				<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																					<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																				</div>
																			</td>
																			<td>
																				<?=$APP_NME_1?><br>
																				<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APP_DAT_1?></span>
																			</td>
																		<?php
																	endforeach;
																}
																else
																{
																	$s_00	= "SELECT DISTINCT A.APPROVER_1,
																					CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																				FROM tbl_docstepapp_det A INNER JOIN tbl_employee B ON A.APPROVER_1 = B.Emp_ID
																				WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' AND APP_STEP = $STEP";
																	$r_00	= $this->db->query($s_00)->result();
																	foreach($r_00 as $rw_00) :
																		$APP_EMP_1	= $rw_00->APPROVER_1;
																		$APP_NME_1	= $rw_00->complName;
																		$OTHAPP 	= 0;
																		$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
									                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
									                                    if($r_APPH_1 > 0)
									                                    {
									                                    	$HIDE 	= 1;
									                                    	$s_01	= "SELECT AH_APPROVED FROM tbl_approve_hist
									                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
									                                        $r_01	= $this->db->query($s_01)->result();
									                                        foreach($r_01 as $rw_01):
									                                            $APPDT	= $rw_01->AH_APPROVED;
									                                        endforeach;

									                                    	$APPCOL 	= "success";
									                                    	$APPIC 		= "check";
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
									                                    }
									                                    else
									                                    {
									                                    	$APPCOL 	= "danger";
									                                    	$APPIC 		= "close";
									                                    	$APPDT 		= "-";
									                                    	$s_APPH_O	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
										                                    $r_APPH_O	= $this->db->count_all($s_APPH_O);
										                                    if($r_APPH_O > 0)
										                                    	$OTHAPP = 1;
									                                    }
									                                    if($HIDE == 0)
									                                    {
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
																		}

																		if($OTHAPP > 0)
																		{
																			$APPDT_OTH 	= "-";
																			$APPNM_OTH 	= "-";
									                                    	$s_01	= "SELECT A.AH_APPROVED, A.AH_APPLEV,
									                                    					CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS COMPLNAME
									                                    				FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
									                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
									                                        $r_01	= $this->db->query($s_01)->result();
									                                        foreach($r_01 as $rw_01):
									                                            $APPDT_LEV	= $rw_01->AH_APPLEV;
									                                            $APPDT_OTH	= $rw_01->AH_APPROVED;
									                                            $APPNM_OTH	= $rw_01->COMPLNAME;

										                                    	$APPCOL 	= "success";
										                                    	$APPIC 		= "check";
																				?>
																	                <tr>
																	                  	<td style="width: 10%" nowrap>&nbsp;</td>
																						<td style="width: 2%;">
																							<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																								<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																							</div>
																						</td>
																						<td>
																							<?=$APPNM_OTH?><br>
																							<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT_OTH?></span>
																						</td>
																					</tr>
																				<?php
									                                        endforeach;
									                                    }
																	endforeach;
																}
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
			</form>

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
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addPR" name='mdl_addPR' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
							            <div class="box-header">
							              	<ul class="nav nav-tabs">
							                    <li id="li1" <?php echo $Active1Cls; ?>>
							                    	<a href="#" data-toggle="tab">Daftar Proposal</a>
							                    </li>
							                </ul>
							            </div>
							            <div class="box-body">
	                                        <div class="form-group">
	                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
		                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                                                <thead>
		                                                    <tr>
		                                                        <th width="2%">&nbsp;</th>
																<th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Code; ?></th>
																<th width="10%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Date; ?></th>
																<th width="78%" nowrap style="vertical-align: middle; text-align:center"><?php echo $Description; ?></th>
		                                                  	</tr>
		                                                </thead>
		                                                <tbody>
		                                                </tbody>
		                                            </table>
                                  					<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                                	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                	</button>
                                                	<button type="button" id="idClose" class="btn btn-danger" data-dismiss="modal">
                                                		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                	</button>
													<button class="btn btn-warning" type="button" id="idRefresh1" title="Refresh" >
														<i class="glyphicon glyphicon-refresh"></i>
													</button>
	                                            </form>
	                                      	</div>
		                                </div>
		                            </div>
		                        </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
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
				    	$('#example1').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_finance/c_cho70d18/get_AllDataPROP/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0,1], className: 'dt-body-center' },
											{ "width": "5px", "targets": [0] },
											{ "width": "20px", "targets": [1] }
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

					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert1; ?>',
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
						      	add_header($(this).val());
						      	//console.log($(this).val());
						    });

						    $('#mdl_addPR').on('hidden.bs.modal', function () {
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
	</body>
</html>
<script>
  	$(function () {
		var DateTRX  = <?php echo $DATE_NOW; ?>;
		if(DateTRX != '')
			var startTRX = moment().subtract(DateTRX, 'days');
		else
			var startTRX = moment().subtract(1, 'month');

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

		$('#datePeriod').daterangepicker({
			locale: {
				format: 'DD/MM/YYYY'
			},
			ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			// 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			'Last Transaction': [startTRX.startOf('month'), moment().subtract(1, 'month').endOf('month')],
			'All Periode': [startTRX.startOf('month'), moment()]
			}
		});

		$('#datePeriod2').daterangepicker({
			locale: {
				format: 'DD/MM/YYYY'
			},
			ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			// 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			'Last Transaction': [startTRX.startOf('month'), moment().subtract(1, 'month').endOf('month')],
			'All Periode': [startTRX.startOf('month'), moment()]
			}
		});
		
		//Date picker
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker').datepicker({
	      autoclose: true
	    });

	    //Date picker
	    $('#datepicker2').datepicker({
	      autoclose: true
	    });

	    //Date picker
	    $('#datepicker3').datepicker({
	      autoclose: true
	    });

	    //Date picker
	    $('#datepicker4').datepicker({
	      autoclose: true
	    });

	    //Date picker
	    $('#datepicker5').datepicker({
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

					// if(isLockJ == 1)
					// {
					// 	// $('#alrtLockJ').css('display',''); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#TLK_STATUS>option[value="3"]').attr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }
					// else
					// {
					// 	// $('#alrtLockJ').css('display','none'); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#TLK_STATUS>option[value="3"]').removeAttr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#TLK_STATUS').removeAttr('disabled','disabled');
							// $('#TLK_STATUS>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = '';
							// $('#TLK_STATUS>option[value="3"]').removeAttr('disabled','disabled');
							$('#TLK_STATUS').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#TLK_STATUS').removeAttr('disabled','disabled');
							// $('#TLK_STATUS>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							// $('#TLK_STATUS>option[value="3"]').removeAttr('disabled','disabled');
							$('#TLK_STATUS').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}
				}
			});
		}
    // END : LOCK PROCEDURE

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];
		
		PROP_NUM		= arrItem[0];
		PROP_CODE		= arrItem[1];
		PROP_DATE		= arrItem[2];
		PROP_VALUE		= arrItem[3];
		document.getElementById("PROP_NUM").value 		= PROP_NUM;
		document.getElementById("PROP_CODE").value 		= PROP_CODE;
		document.getElementById("PROP_CODEX").value 	= PROP_CODE;
		document.getElementById("PROP_VALUE").value 	= PROP_VALUE;
		document.getElementById("PROP_VALUEX").value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_VALUE)),2));
	}

	function chgUPer()
	{
		var url 		= "<?php echo $secGTot; ?>";
		var PRJCODE 	= $("#PRJCODE").val();
		var DATE_USES 	= $("#datepicker4").val();
		var DATE_USEE 	= $("#datepicker5").val();
		var collData 	= PRJCODE+'~'+DATE_USES+'~'+DATE_USEE;
		
		$.ajax({
			type: "POST",
			url: url,
			data: {collData : collData},
			success: function(response)
			{
				document.getElementById("TLK_AMOUNTU").value 	= response;
				document.getElementById("TLK_AMOUNTUX").value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(response)),2));
			}
		})
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
	
	function validateInData(value)
	{
		let TLK_AMOUNT 	= $('#TLK_AMOUNT').val();
		let TLK_DESC 	= $('#TLK_DESC').val();

		if(TLK_DESC == '')
		{
			swal('<?php echo $alert0; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#TLK_DESC').focus();
            });
			return false;
		}

		if(TLK_AMOUNT <= 0)
		{
			swal('<?php echo $alert14; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#TLK_AMOUNTX').focus();
            });
			return false;
		}

		$('input').prop('disabled', false);
	    $('textarea').prop('disabled', false);
		
		document.getElementById('btnSave').style.display 	= 'none';
		document.getElementById('btnBack').style.display 	= 'none';
		document.frm.submit();

		let frm = document.getElementById('frm');
		frm.addEventListener('submit', (e) => {
			console.log(e)
			document.getElementById('btnSave').style.display 	= 'none';
			document.getElementById('btnBack').style.display 	= 'none';
		});
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