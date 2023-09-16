<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 05 Maret 2022
	* File Name		= v_cho_pd_form.php
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

$jrnType 	= 'CHO-PD';

$s_PattC	= "tbl_docpattern WHERE menu_code = '$MenuCode'";
$r_PattC 	= $this->db->count_all($s_PattC);
if($r_PattC > 0)
	$isSetDocNo = 1;
else
	$isSetDocNo = 0;

$s_PattC	= "tbl_docpattern WHERE menu_code = '$MenuCode'";
$r_PattC 	= $this->db->count_all($s_PattC);
if($r_PattC > 0)
{
	$isSetDocNo = 1;
	$s_Patt		= "SELECT Pattern_Code, Pattern_Length FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
	$r_Patt 	= $this->db->query($s_Patt)->result();
	foreach($r_Patt as $row) :
		$PATTCODE 	= $row->Pattern_Code;
	endforeach;
}
else
{
	$PATTCODE 		= "XXX";
}

if($task == 'add')
{	
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;
	
	$TRXTIME		= date('ymdHis');
	$JournalH_Code	= "$PATTCODE$PRJCODE.$TRXTIME";
	$DocNumber 		= "";
	$Manual_No		= $DocNumber;

	$JournalH_Date	= date('d/m/Y');
	$PD_Date		= date('d/m/Y');
	$PlanRDate		= date('d/m/Y');
	$JournalH_Desc	= '';
	$JournalH_Desc2	= '';
	$proj_Code		= $PRJCODE;
	$GEJ_STAT		= 1;
	$acc_number		= '';
	$Journal_Amount	= 0;

	$ACC_OPBAL 		= 0;

	$PERSL_EMPID 	= "";
	$SPLCODE 		= "";
	$PERSL_STAT 	= 1;	// 1. PD, 2. OP, 3. SPK
	$REF_NUM		= ""; 	// Nomor OP / SPK
	$REF_CODE 		= ""; 	// Kode OP / SPK
	$Reference_Number 	= "";
	$JournalH_Desc3 	= "";

	$disablePayD 	= '';
	$disablePD_D 	= '';
}
else
{
	$isSetDocNo 		= 1;
	$DocNumber			= $JournalH_Code;
	/*$JournalH_Code	= $default['JournalH_Code'];
	$Manual_No			= $default['Manual_No'];
	$DocNumber			= $default['JournalH_Code'];
	$JournalH_Date		= $default['JournalH_Date'];*/
	$JournalH_Date		= date('d/m/Y',strtotime($JournalH_Date));
	$PD_Date			= date('d/m/Y',strtotime($PD_Date));
	$PlanRDate			= date('d/m/Y',strtotime($PlanRDate));
	/*$JournalH_Desc	= $default['JournalH_Desc'];
	$JournalH_Desc2		= $default['JournalH_Desc2'];
	$proj_Code			= $default['proj_Code'];
	$PRJCODE_HO			= $default['proj_CodeHO'];
	$PRJPERIOD			= $default['PRJPERIOD'];
	$proj_Code			= $default['proj_Code'];*/
	$PRJCODE			= $proj_Code;
	/*$GEJ_STAT			= $default['GEJ_STAT'];
	$acc_number			= $default['acc_number'];
	$Journal_Amount		= $default['Journal_Amount'];
	$ISPERSL			= $default['ISPERSL'];
	$PERSL_EMPID		= $default['PERSL_EMPID'];*/
	
	$disablePayD 	= '';
	$disablePD_D 	= '';

	$ACC_OPBAL	= 0;
	$sqlBAL 	= "SELECT Base_OpeningBalance, Base_Debet, Base_Kredit
					FROM tbl_chartaccount
					WHERE Account_Number = '$acc_number' AND PRJCODE = '$PRJCODE'";
	$resBAL 	= $this->db->query($sqlBAL)->result();
	foreach($resBAL as $rowBAL):
		$Base_OB 	= $rowBAL->Base_OpeningBalance;
		$Base_D 	= $rowBAL->Base_Debet;
		$Base_K 	= $rowBAL->Base_Kredit;
		$ACC_OPBAL 	= $Base_OB + $Base_D - $Base_K;
	endforeach;
}
		
if($LangID == 'IND')
{
	$docalert1	= 'Peringatan';
	$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
	$docalert3	= 'Anda belum men-setting akun untuk dokumen Pinjaman Dinas. Silahkan atur akun Pinjaman Dinas dari menu pengaturan umum.';
}
else
{
	$docalert1	= 'Warning';
	$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
	$docalert3	= 'You have not set up an account for the Cash Expenditure document for the Personal Loan category. Please set up a Loan Account from the General Settings menu.';
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
			if($TranslCode == 'POList')$POList = $LangTransl;
			if($TranslCode == 'PONumber')$PONumber = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'CashAccount')$CashAccount = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'SelectAccount')$SelectAccount = $LangTransl;
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
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'UniqCode')$UniqCode = $LangTransl;
			if($TranslCode == 'othInfo')$othInfo = $LangTransl;
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'PaymentNo')$PaymentNo = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'ItmList')$ItmList = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'sourceDoc')$sourceDoc = $LangTransl;
			if($TranslCode == 'paidDate')$paidDate = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$subTitleD	= "Penggunaan Kas";
			$alert0		= "Masukan deskripsi jurnal transaksi.";
			$alert1		= "Silahkan pilih Nomor Akun.";
			$alert2		= "Silahkan tuliskan deskripsi jurnal.";
			$alert3		= "Tentukan posisi akun; di Debit atau Kredit.";
			$alert4		= "Tentukan posisi jenis transaksi, pajak atau bukan.";
			$alert5		= "Masukan nomor referensi jurnal transaksi.";
			$alert6		= "Masukan jumlah/nilai penggunaan kas.";
			$alert7		= "Belum ada akun untuk sisi Debit.";
			$alert8		= "Belum ada akun untuk sisi Kredit.";
			$alert9		= "Nilai Debit dan Kredit tidak sama.";
			$alert10	= "Masukan alasan mengapa dokumen ini di batalkan.";
			$alert11	= "Pilih sumber pembayaran.";
			$alert12	= "Saldo tidak cukup.";
			$alert13	= "Nama penerima Perjalanan Dinas (PD) tidak boleh kosong.";
			$alert14	= "Nilai PD tidak boleh kosong.";
			$alert15	= "Melebihi sisa volume anggaran. Maksimum sisa volume ";
			$alert16	= "Melebihi sisa anggaran. Maksimum sisa ";
			$alert17	= "Silahkan pilih nomor OP / SPK";
			$sureChg	= "Anda yakin akan mengembalikan dana PD ini?";
		}
		else
		{
			$subTitleH	= "Add";
			$subTitleD	= "Cash Payment";
			$alert0		= "Please input description of journal transaction.";
			$alert1		= "Please select account number.";
			$alert2		= "Please write of journal description";
			$alert3		= "Set the Account Position; Debit or Credit.";
			$alert4		= "Set the type transaction, Tax or Non Tax.";
			$alert5		= "Insert Reference Number of transaction";
			$alert6		= "Input the amount / value of cash usage.";
			$alert7		= "Debit Side transaction can not be empty.";
			$alert8		= "Credit Side transaction can not be empty";
			$alert9		= "Debit dan Credit Amount must be same.";
			$alert10	= "Please write a comment why you void this document.";
			$alert11	= "Please select source of payment.";
			$alert12	= "Insufficient cash account balance..";
			$alert13	= "The name of the official loan recipient cannot be empty.";
			$alert14	= "PD Value cannot be empty.";
			$alert15	= "Exceeded the remaining budget volume. Maximum remaining volume ";
			$alert16	= "Exceeded the remaining budget. Maximum remaining ";
			$alert17	= "Please select an OP / SPK Document";
			$sureChg	= "Are you sure you will refund this PD?";
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
				
				if($GEJ_STAT == 3 && $resC_App > 0)
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

		$comp_color = $this->session->userdata('comp_color');
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
	        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
	            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
	            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
	            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
	            <input type="hidden" name="JournalType" id="JournalType" value="<?php echo $jrnType; ?>" />
	            <input type="Hidden" name="rowCount" id="rowCount" value="0">
                <div class="row">
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
					            <div class="col-sm-12" id="divAlert" <?php if($app_stat == 0) { ?> style="display: none;" <?php } ?>>
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
                        <div class="box box-success">
                            <div class="box-header with-border" style="display: none;">
                                <h3 class="box-title"><?php // echo $DetInfo; ?></h3>
                            </div>
                            <div class="box-body">
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $UniqCode; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" name="JournalH_Code1" id="JournalH_Code1" size="30" value="<?php echo $JournalH_Code; ?>" disabled />
				                    	<input type="text" class="form-control" style="max-width:400px;text-align:right" name="JournalH_Code" id="JournalH_Code" size="30" value="<?php echo $JournalH_Code; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE_HO" id="PRJCODE_HO" size="30" value="<?php echo $PRJCODE_HO; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJPERIOD" id="PRJPERIOD" size="30" value="<?php echo $PRJPERIOD; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo "$Code"; ?></label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" name="Manual_No" id="Manual_No" size="30" value="<?php echo $Manual_No; ?>" readonly />
				                    	<input type="hidden" class="form-control" name="task" id="task" size="30" value="<?php echo $task; ?>" />
					                    <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="Pattern_Code" id="Pattern_Code" size="30" value="" />
				                    </div>
				                    <div class="col-sm-3">
				                    	<input type="text" class="form-control" name="Reference_Number" id="Reference_Number" size="30" value="<?php echo $Reference_Number; ?>" placeholder="No Referensi" />
				                    </div>
				                    <label for="inputName" class="col-sm-2 control-label" style="display: none;"><?php echo "$Date"; ?></label>
				                    <div class="col-sm-3">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PD_Date" class="form-control pull-left" id="datepicker" value="<?php echo $PD_Date; ?>"></div>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo "OP / SPK"; ?></label>
		                          	<div class="col-sm-4">
				                    	<!-- Karena dipastikan pinjaman dina, maka ISPERSL = 1 -->
				                    	<input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="ISPERSL" id="ISPERSL" size="30" value="1" />
				                    	<select name="PERSL_STAT" id="PERSL_STAT" class="form-control select2" onChange="chgType(this.value)">
				                          	<option value="2" <?php if($PERSL_STAT == 2) { ?> selected <?php } ?>> OP </option>
				                          	<option value="3" <?php if($PERSL_STAT == 3) { ?> selected <?php } ?>> SPK </option>
				                          	<option value="1" <?php if($PERSL_STAT == 1) { ?> selected <?php } ?>> Lainnya </option>
				                       	</select>
		                            </div>
		                          	<div class="col-sm-6">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary" onClick="pleaseCheck()" id="btnSrch"><i class="glyphicon glyphicon-search"></i></button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="REF_NUM" id="REF_NUM" style="max-width:160px" value="<?php echo $REF_NUM; ?>" >
		                                    <input type="hidden" class="form-control" name="REF_CODE" id="REF_CODE" style="max-width:160px" value="<?php echo $REF_CODE; ?>" >
		                                    <input type="text" class="form-control" name="REF_CODE1" id="REF_CODE1" value="<?php echo "$REF_CODE"; ?>" onClick="pleaseCheck();" <?php if($PERSL_STAT == 1) { ?> readonly <?php } ?>>
		                                    <input type="hidden" class="form-control" name="REF_CODE2" id="REF_CODE2" value="<?php echo $REF_CODE; ?>" data-toggle="modal" data-target="#mdl_addJList">
		                                </div>
		                            </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label">Nama Kary.</label>
				                    <div class="col-sm-4" id="isPersLoan" <?php if($ISPERSL == 0) { ?> style="display: none;" <?php } ?>>
				                    	<select name="PERSL_EMPID" id="PERSL_EMPID" class="form-control select2" style="width: 100%" onChange="chgEmp(this.value)">
				                          	<option value=""> --- </option>
				                          	<?php
				                          		$s_01 	= "SELECT Emp_ID, CONCAT(First_Name,' ', Last_Name) AS EMPNAME FROM tbl_employee
				                          					WHERE Employee_status = 1 ORDER BY EMPNAME ASC";
				                          		$r_01 	= $this->db->query($s_01)->result();
				                          		foreach($r_01 as $rw_01):
				                          			$EMPID 	= $rw_01->Emp_ID;
				                          			$EMPNM 	= $rw_01->EMPNAME;
				                          			?>
				                          				<option value="<?=$EMPID?>" <?php if($EMPID == $PERSL_EMPID) { ?> selected <?php } ?>>
				                          					<?php echo "$EMPNM $EMPID"; ?>
				                          				</option>
				                          			<?php
				                          		endforeach;
				                          	?>
				                       	</select>
				                    </div>
				                    <label for="inputName" class="col-sm-1 control-label">Supplier</label>
				                    <div class="col-sm-5" id="isPersLoan" <?php if($ISPERSL == 0) { ?> style="display: none;" <?php } ?>>
				                    	<select name="SPLCODE" id="SPLCODE" class="form-control select2" style="width: 100%" onChange="chgEmp(this.value)">
				                          	<option value=""> --- </option>
				                          	<?php
				                          		$s_01 	= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = 1 ORDER BY SPLDESC ASC";
				                          		$r_01 	= $this->db->query($s_01)->result();
				                          		foreach($r_01 as $rw_01):
				                          			$SPLCODE1 	= $rw_01->SPLCODE;
				                          			$SPLDESC 	= $rw_01->SPLDESC;
				                          			?>
				                          				<option value="<?=$SPLCODE1?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>>
				                          					<?php echo "$SPLCODE1 $SPLDESC"; ?>
				                          				</option>
				                          			<?php
				                          		endforeach;
				                          	?>
				                       	</select>
				                    </div>
				                </div>
			                    <script type="text/javascript">
			                    	<?php
				                    	if($PERSL_STAT == 2)
				                    	{
				                    		?>
					                    		$(document).ready(function()
												{
						                    		$('#example0').DataTable(
											    	{
												        "destroy": true,
												        "processing": true,
												        "serverSide": true,
														//"scrollX": false,
														"autoWidth": true,
														"filter": true,
												        "ajax": "<?php echo site_url('c_finance/c_cho_pd/get_AllDataPOList/?id='.$PRJCODE)?>",
												        "type": "POST",
														//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
														"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
														"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
																	  ],
							        					"order": [[ 2, "desc" ]],
														"language": {
												            "infoFiltered":"",
												            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
												        },
													});
													
													document.getElementById('hdName').innerHTML 	= "Daftar OP";
				                    				document.getElementById('REF_CODE1').readOnly 	= false;
				                    				document.getElementById('btnSrch').disabled 	= false;
												});
				                    		<?php
				                    	}
				                    	elseif($PERSL_STAT == 3)
				                    	{
				                    		?>
					                    		$(document).ready(function()
												{
					                    			$('#example0').DataTable(
											    	{
												        "destroy": true,
												        "processing": true,
												        "serverSide": true,
														//"scrollX": false,
														"autoWidth": true,
														"filter": true,
												        "ajax": "<?php echo site_url('c_project/c_o180d0bpnm/get_AllDataWO/?id='.$PRJCODE)?>",
												        "type": "POST",
														//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
														"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
														"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
																	  ],
							        					"order": [[ 2, "desc" ]],
														"language": {
												            "infoFiltered":"",
												            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
												        },
													});
													
													document.getElementById('hdName').innerHTML 	= "Daftar SPK";
				                    				document.getElementById('REF_CODE1').readOnly 	= false;
				                    				document.getElementById('btnSrch').disabled 	= false;
												});
				                    		<?php
				                    	}
				                    ?>

			                    	function chgType(typeVal)
			                    	{
			                    		if(typeVal == 1)
			                    		{
		                    				document.getElementById('REF_CODE1').readOnly 	= true;
		                    				document.getElementById('btnSrch').disabled 	= true;
			                    		}
			                    		else if(typeVal == 2)
			                    		{
									    	$('#example0').DataTable(
									    	{
										        "destroy": true,
										        "processing": true,
										        "serverSide": true,
												//"scrollX": false,
												"autoWidth": true,
												"filter": true,
										        "ajax": "<?php echo site_url('c_finance/c_cho_pd/get_AllDataPOList/?id='.$PRJCODE)?>",
										        "type": "POST",
												//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
												"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
												"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
															  ],
					        					"order": [[ 2, "desc" ]],
												"language": {
										            "infoFiltered":"",
										            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
										        },
											});
											
											document.getElementById('hdName').innerHTML 	= "Daftar OP";
		                    				document.getElementById('REF_CODE1').readOnly 	= false;
		                    				document.getElementById('btnSrch').disabled 	= false;
			                    		}
			                    		else if(typeVal == 3)
			                    		{
									    	$('#example0').DataTable(
									    	{
										        "destroy": true,
										        "processing": true,
										        "serverSide": true,
												//"scrollX": false,
												"autoWidth": true,
												"filter": true,
										        "ajax": "<?php echo site_url('c_project/c_o180d0bpnm/get_AllDataWO/?id='.$PRJCODE)?>",
										        "type": "POST",
												//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
												"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
												"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
															  ],
					        					"order": [[ 2, "desc" ]],
												"language": {
										            "infoFiltered":"",
										            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
										        },
											});
											
											document.getElementById('hdName').innerHTML 	= "Daftar SPK";
		                    				document.getElementById('REF_CODE1').readOnly 	= false;
		                    				document.getElementById('btnSrch').disabled 	= false;
			                    		}
			                    	}

									function pleaseCheck()
									{
				                        document.getElementById('REF_CODE2').click();
									}

			                    	function chgEmp(empVal)
			                    	{
			                    		var accCode 	= $("#acc_number").val();
			                    		chgAccCB(accCode)
			                    	}
			                    </script>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Description; ?></label>
				                    <div class="col-sm-6">
				                    	<textarea name="JournalH_Desc" class="form-control" id="JournalH_Desc" style="height: 60px"><?php echo $JournalH_Desc; ?></textarea>
				                        <input type="hidden" name="Journal_AmountD" id="Journal_AmountD" value="">
				                        <input type="hidden" name="Journal_AmountK" id="Journal_AmountK" value="">
				                    </div>
				                    <div class="col-sm-4">
			                    		<label for="inputName">Rencana Penyelesaian</label>
				                    	<div class="input-group date">
				                        	<div class="input-group-addon">
				                        	<i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="PlanRDate" class="form-control pull-left" id="datepicker1" value="<?php echo $PlanRDate; ?>">
				                        </div>
				                    </div>
				                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="box box-warning">
                            <div class="box-header with-border" style="display: none;">
                                <h3 class="box-title"><?php echo $othInfo; ?></h3>
                            </div>
                            <div class="box-body">
				            	<div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
				                    <div class="col-sm-9">
				                    	<select name="proj_Code" id="proj_Code" class="form-control" disabled>
				                          <option value="none"> --- </option>
				                          <?php
										  	$theProjCode 	= $PRJCODE;
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
				                    <input type="hidden" class="form-control" style="max-width:400px;text-align:right" id="proj_Code" name="proj_Code" size="20" value="<?php echo $proj_Code; ?>" />                        
				                    </div>
				                </div>
				                <div class="form-group">
	                            	<div class="col-sm-12">
	                                    <label for="exampleInputEmail1"><?php echo $srcPayment; ?></label>
				                    	<select name="acc_number" id="acc_number" class="form-control select2" onChange="chgAccCB(this.value)">
				                          	<option value=""> --- </option>
				                          	<?php
											  	$theProjCode 	= $PRJCODE;
					                            if($cAllCOA>0)
												{
													$totRow		= 0;
													foreach($vwAllCOA as $row) :
														$isDisabled			= 0;
														$Account_Number 	= $row->Account_Number;		// 0
														$Account_NameEn		= $row->Account_NameEn;
														$Account_NameId		= $row->Account_NameId;		// 1
														//$PRJCODE 			= $row->PRJCODE;
														$Account_Class		= $row->Account_Class;
														
														$Base_OpeningBalance= $row->Base_OpeningBalance;
														$Base_Debet 		= $row->Base_Debet;
														$Base_Kredit 		= $row->Base_Kredit;
														$balanceVal 		= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
														
														$isDisabled			= 0;
														/*if($Account_Class == 3 && $balanceVal <= 0)
															$isDisabled		= 1;
														elseif($Account_Class == 4 && $balanceVal <= 0)
															$isDisabled		= 1;*/
					                                    ?>
					                                  <option value="<?php echo $Account_Number; ?>" <?php if($Account_Number == $acc_number) { ?> selected <?php } if($isDisabled == 1) { ?> disabled <?php } ?>>
													  	<?php
															$space1	 = 15 - strlen($Account_Number);
															$space1a = '';
															for($i=0; $i<=$space1;$i++)
															{
																$space1a = $space1a."&nbsp;";
															}
															//echo $space1a.$Account_NameId ."\t\t\t". $Account_Number;
															echo $Account_NameId ."\t\t\t". $Account_Number;
														?>
					                                  </option>
					                                  <?php
					                                endforeach;
					                            }
				                            ?>
				                        </select>
				                    	<input type="hidden" class="form-control" style="max-width:400px;text-align:right" id="proj_Code" name="proj_Code" size="20" value="<?php echo $proj_Code; ?>" />
		                            </div>
				                </div>
				                <div class="form-group">
				                    <div class="col-sm-4">
				                		<label for="inputName">Saldo</label>
				                    	<input type="text" style="text-align: right;" class="form-control" name="ACC_OPBAL" id="ACC_OPBAL" size="30" value="<?php echo number_format($ACC_OPBAL, 2); ?>" readonly />
				                    </div>
				                	<div class="col-sm-4">
	                                    <label for="exampleInputEmail1"><?php echo $PaymentNo; ?></label>
	                            		<input type="text" class="form-control" name="JournalH_Desc3" id="JournalH_Desc3" size="30" value="<?php echo $JournalH_Desc3; ?>" placeholder="No Pembayaran" />
		                            </div>
				                    <div class="col-sm-4">
				                		<label for="inputName"><?php echo "$paidDate"; ?></label>
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="JournalH_Date" class="form-control pull-left" id="datepicker2" value="<?php echo $JournalH_Date; ?>" onChange="addUCODE(this.value)" autocomplete="off" <?=$disablePayD?>></div>
				                    </div>
				                </div>
				                <?php
				                	$getAcc 	= base_url().'index.php/c_finance/c_cho_pd/getACCID/?id='.$PRJCODE;
									$genNUM 	= base_url().'index.php/__l1y/genNUMBER/?id='.$PRJCODE;
									$genNUME 	= base_url().'index.php/__l1y/genNUMBER2/?id='.$PRJCODE;

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
				                <script type="text/javascript">
				                	function chgAccCB(AccID)
				                	{
				                		// CHECK CATEGORY
				                		ISPERSL 	= document.getElementById('ISPERSL').value;
				                		if(ISPERSL == 1)
				                		{
				                			PERSL_EMPID 	= document.getElementById('PERSL_EMPID').value;
				                			if(PERSL_EMPID == '')
				                			{
												swal('<?php echo $alert13; ?>',
												{
													icon: "warning",
												})
												.then(function()
									            {
									                swal.close();
									                $('#acc_number').val('');
									            });
												return false;
				                			}
				                		}

						                var task 		= "<?=$task?>";
										if(task == 'add')
											var url     = "<?php echo $genNUM; ?>";
										else
											var url     = "<?php echo $genNUME; ?>";

										var AccID 		= document.getElementById('acc_number').value;
										var jrnDate		= document.getElementById('datepicker').value;
										var jrnType 	= document.getElementById('JournalType').value;
										var task 		= "<?=$task?>";
										var jrnCode 	= document.getElementById('Manual_No').value;
										var jrnNumb 	= document.getElementById('JournalH_Code').value;
										var pattCode 	= document.getElementById('Pattern_Code').value;
										var PRJCODE 	= "<?=$PRJCODE?>";

										var collDt 		= jrnCode+'~'+pattCode+'~'+PRJCODE+'~'+task+'~'+jrnType+'~'+AccID+'~'+jrnDate+'~'+jrnNumb;
										//console.log(collDt)
								        $.ajax({
								            type: 'POST',
								            url: url,
								            data: {collDt: collDt},
								            success: function(response)
								            {
						                    	arrAcc 		= response.split('~');
						                    	ACCBAL 		= arrAcc[0];
						                    	Manual_No	= arrAcc[1];
						                    	Manual_CB	= arrAcc[2];

						                    	//document.getElementById('Manual_No').value 		= Manual_No;
						                    	document.getElementById('JournalH_Desc3').value = Manual_CB;
						                        document.getElementById('ACC_OPBAL').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ACCBAL)), 2));
								            }
								        });
				                	}
				                </script>
				                <div class="form-group">
	                            	<div class="col-sm-4">
					                    <label for="inputName"><?=$Amount?></label>
		                        		<input type="hidden" class="form-control" style="text-align:right" name="Journal_Amount" id="Journal_Amount" value="<?php echo $Journal_Amount; ?>" />
		                        		<input type="text" class="form-control" style="text-align:right" name="Journal_AmountX" id="Journal_AmountX" value="<?php echo number_format($Journal_Amount, 2); ?>" title="Total Jurnal" onBlur="chgAmn(this)" <?php if($ISPERSL == 0) { ?> readonly <?php } ?> />
		                            </div>
	                            	<div class="col-sm-8">
					                    <label for="inputName"><?php echo $Status; ?></label>
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $GEJ_STAT; ?>">
										<?php
											// START : FOR ALL APPROVAL FUNCTION
												//$disButton	= 0;
												if($task == 'add')
												{
													if($ISCREATE == 1)
													{
														?>
															<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
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
														if($GEJ_STAT == 1 || $GEJ_STAT == 4)
														{
															//$disButton	= 0;
															?>
																<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														elseif($GEJ_STAT == 2 || $GEJ_STAT == 7)
														{
															//$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;										
															?>
																<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" >
																	<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
																	<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																	<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> >Waiting</option>
																	<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
														elseif($GEJ_STAT == 3)
														{
															//$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;	
															if($ISDELETE == 1)
																$disButton	= 0;				
														
															?>
																<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" >
																	<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																	<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
																	<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																	<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
													}
													elseif($ISAPPROVE == 1)
													{
														if($GEJ_STAT == 1 || $GEJ_STAT == 4)
														{
															//$disButton	= 1;
															?>
																<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" >
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														elseif($GEJ_STAT == 2 || $GEJ_STAT == 7)
														{
															//$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;						
														
															?>
																<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" >
																	<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
																	<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																	<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?>>Waiting</option>
																	<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
														elseif($GEJ_STAT == 3)
														{
															//$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;					
														
															?>
																<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" onChange="selStat(this.value)" >
																	<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																	<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																	<option value="4"<?php if($GEJ_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
																	<option value="5"<?php if($GEJ_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																	<option value="6"<?php if($GEJ_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($GEJ_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<option value="9"<?php if($GEJ_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
													}
												}
											// END : FOR ALL APPROVAL FUNCTION
				                        ?>
		                            </div>
				                    <script>
										function selStat(thisValue)
										{
											if(thisValue == 4 || thisValue == 5)
											{
												document.getElementById('divRev').style.display = '';
											}
											else
											{
												document.getElementById('divRev').style.display = 'none';
												document.getElementById('divRev').value 		= '';
											}
										}
									</script>
				                </div>
			                    <script>
			                    	function chgAmn(thisval)
			                    	{
			                    		var Journal_Amount	= parseFloat(eval(thisval).value.split(",").join(""));
			                    		document.getElementById('Journal_Amount').value 	= parseFloat(Journal_Amount);
			                    		document.getElementById('Journal_AmountX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Journal_Amount)), 2));
			                    	}
								</script>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" id="divRev" <?php if($JournalH_Desc2 == '') { ?> style="display:none" <?php } ?>>
                        <div class="box box-warning">
                            <div class="box-header with-border" style="display: none;">
                                <h3 class="box-title"><?php echo $Notes; ?></h3>
                            </div>
                            <div class="box-body">
		                    	<div class="col-sm-12">
				                    <label for="inputName"><?php echo $Notes; ?></label>
				                    <textarea name="JournalH_Desc2" class="form-control" id="JournalH_Desc2" cols="30"><?php echo $JournalH_Desc2; ?></textarea>
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

                    <?php
                    	$ACC_ID_PERSL	= "";
                    	$s_01 			= "SELECT A.ACC_ID_PERSL FROM tglobalsetting A
                    						INNER JOIN tbl_chartaccount B ON A.ACC_ID_PERSL = B.Account_Number";
                    	$r_01 			= $this->db->query($s_01)->result();
                    	foreach($r_01 as $rw_01):
                    		$ACC_ID_PERSL 	= $rw_01->ACC_ID_PERSL;
                    	endforeach;
                    ?>
                    <input type="hidden" name="ACC_ID_PERSL" id="ACC_ID_PERSL" value="<?php echo $ACC_ID_PERSL; ?>" />
                    <!-- <div class="col-sm-12" id="setAccPLAlert" style="display: none;"> -->
                    <div class="col-sm-12" id="setAccPLAlert" style="display: none;">
                        <div class="alert alert-danger alert-dismissible">
                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
                            <?php echo $docalert3; ?>
                        </div>
                    </div>
                   	<?php
                   		$GEJ_STATPD 	= 3;
						$s_02 			= "SELECT GEJ_STAT_PD FROM tbl_journalheader_pd WHERE JournalH_Code = '$JournalH_Code'";
                    	$r_02 			= $this->db->query($s_02)->result();
                    	foreach($r_02 as $rw_02):
                    		$GEJ_STATPD = $rw_02->GEJ_STAT_PD;
                    	endforeach;
						if($GEJ_STATPD == 9)
						{
							?>
								<div class="col-sm-12">
		                			<div class="alert alert-warning alert-dismissible">
						                Dana PD ini sudah dikembalikan tanpa adanya realisasi.
					              	</div>
					            </div>
							<?php
						}
					?>

                    <div class="col-md-6">
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-3 control-label"><?php //echo $Project; ?></label>
		                    <div class="col-sm-9">
		                        <?php
									/*if($ISAPPROVE == 1)
										$ISCREATE = 1;*/

									if(($GEJ_STAT == 1 || $GEJ_STAT == 2 || $GEJ_STAT == 4) && $disButton == 0)
										$btnShow = 1;
									else
										$btnShow = 0;

									if($ACC_ID_PERSL == "")
										$btnShow = 0;

									if($canApprove == 1)
										$btnShow = 1;
								?>
								<input type="hidden" name="btnShow" id="btnShow" value="<?=$btnShow?>">
								<button class="btn btn-primary" id="btnSave" <?php if($btnShow == 0) { ?> style="display: none;" <?php } ?> >
								<i class="fa fa-save"></i>
								</button>&nbsp;
								<?php									
									//$backURL	= site_url('c_finance/c_cho_pd/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" id="btnBack" type="button"><i class="fa fa-reply"></i></button>&nbsp;&nbsp;');
									if($GEJ_STAT == 3 && $GEJ_STATPD != 9)
										echo '<button class="btn btn-warning" id="btnBack" type="button" title="Pembatalan Penggunaan" onClick="canclPD()"><i class="fa fa-ban"></i></button>';
		                        ?>
		                    </div>
		                </div>
                    </div>
	            </div>
			</form>
			<script type="text/javascript">
				function canclPD()
				{
			        swal({
			            text: "<?php echo $sureChg; ?>",
			            icon: "warning",
			            buttons: ["No", "Yes"],
			        })
			        .then((willDelete) => 
			        {
			            if (willDelete) 
			            {
							JRN_NUM 		= document.getElementById('JournalH_Code').value;
							JRN_CODE 		= document.getElementById('Manual_No').value;

							var formData 	= 	{
													JRN_NUM		: JRN_NUM,
													JRN_CODE	: JRN_CODE,
													PRJCODE		: "<?=$PRJCODE?>"
												};
							$.ajax({
					            type: 'POST',
					            url: "<?php echo site_url('c_finance/c_cho_pd/chgSTATPD')?>",
					            data: formData,
					            success: function(response)
					            {
					            	swal(response,
									{
										icon: "warning",
									})
									.then(function()
						            {
						                swal.close();
						            });
					            }
					        });
			            } 
			            else 
			            {
			                //...
			            }
			        });
				}
			</script>

	    	<!-- ============ START MODAL PO/SPK LIST =============== -->
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
		        <div class="modal fade" id="mdl_addJList" name='mdl_addJList' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab"><span id="hdName"></span></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example0" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="2%">&nbsp;</th>
							                                        <th width="15%" style="vertical-align:middle; text-align:center" nowrap="nowrap">Dok. No.</th>
							                                        <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
							                                        <th width="30%" style="vertical-align:middle; text-align:center" nowrap><?php echo $SupplierName; ?></th>
							                                        <th width="48%" style="vertical-align:middle; text-align:center" nowrap><span style="text-align:center;"><?php echo $Description; ?></span></th>
											                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh0" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>
		                                </div>
                                      	<input type="hidden" name="rowCheck0" id="rowCheck0" value="0">
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					$("#idRefresh0").click(function()
					{
						$('#example0').DataTable().ajax.reload();
					});

					var selectedRows = 0;
					function pickThis0(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk0']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck0").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail0").click(function()
					    {
							var totChck 	= $("#rowCheck0").val();
							
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

						    $.each($("input[name='chk0']:checked"), function()
						    {
						      	add_header($(this).val());
						    });

						    $('#mdl_addJList').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose0").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL PO/SPK LIST =============== -->

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
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker').datepicker({
	      	autoclose: true,
			startDate: '-3d',
			endDate: '+0d'
	    });

	    //Date picker
	    $('#datepicker1').datepicker({
	      autoclose: true,
	    });

	    $('#datepicker2').datepicker({
	      autoclose: true,
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
		var DOCNUM		= document.getElementById('JournalH_Code').value;
		var DOCCODE		= document.getElementById('Manual_No').value;
		var DOCDATE		= document.getElementById('datepicker').value;
		var PAYDATE		= document.getElementById('datepicker2').value;
		var ACC_ID		= document.getElementById('acc_number').value;
		var PDManNo 	= document.getElementById('Reference_Number').value;

		var formData 	= {
							PRJCODE 		: "<?=$PRJCODE?>",
							MNCODE 			: "<?=$MenuCode?>",
							DOCNUM 			: DOCNUM,
							DOCCODE 		: DOCCODE,
							DOCCODE2		: "", // PDManNo => Tidak merubah Manual_No // UP: 2022-06-02
							DOCDATE 		: DOCDATE,
							PAYDATE 		: PAYDATE,
							ACC_ID 			: ACC_ID,
							DOCTYPE 		: 'CHO-PD'
						};
		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('__l1y/getLastDocNum')?>",
            data: formData,
            success: function(response)
            {
            	console.log(response)
            	var arrVar 	= response.split('~');
            	var docNum 	= arrVar[0];
            	var docCode	= arrVar[1];
            	var payCode = arrVar[2];
            	var ACCBAL 	= arrVar[3];

            	if(isNaN(ACCBAL) == true)
            		ACCBAL 	= 0;

            	$('#Manual_No').val(docCode);
            	$('#JournalH_Desc3').val(payCode);
            	document.getElementById('ACC_OPBAL').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ACCBAL)), 2));
            }
        });
	}

	// START : LOCK PROCEDURE
		$(document).ready(function()
		{
			setInterval(function(){chkAppStat()}, 1000);
		});

		function chkAppStat()
		{
			var url 		= "<?php echo site_url('lck/appStat')?>";
		        
            $.ajax({
                type: 'POST',
                url: url,
                success: function(response)
                {
                	var arrVar 		= response.split('~');
                	var arrStat 	= arrVar[0];
                	var arrAlert 	= arrVar[1];
                	var ACCID_PERS 	= arrVar[2];
                	var app_stat 	= document.getElementById('app_stat').value;
                	var GEJ_STAT 	= document.getElementById('STAT_BEFORE').value;
                	var btnShow 	= document.getElementById('btnShow').value;


                	if(arrStat == 1 || app_stat == 1)
                	{
                		$('#app_stat').val(arrStat);
                		swal(arrAlert, 
	                    {
	                        icon: "success",
	                    })
	                    .then(function()
	                    {
	                    	swal.close();
	                    	document.getElementById('btnSave').style.display 	= 'none';
	                    	document.getElementById('divAlert').style.display 	= '';
	                    })
                	}
                	else if(arrStat == 1 && app_stat == 1 && btnShow == 1)
                	{
                    	document.getElementById('btnSave').style.display 	= '';
                    	document.getElementById('divAlert').style.display 	= 'none';
                	}

            		if(ACCID_PERS == '')
            		{
                		document.getElementById('setAccPLAlert').style.display 	= '';
                		document.getElementById('btnSave').style.display 		= 'none';
                	}
                	else
                	{
                		document.getElementById('setAccPLAlert').style.display 	= 'none';
                		document.getElementById('btnSave').style.display 		= '';
                	}

                	if(GEJ_STAT == 3 || GEJ_STAT == 5 || GEJ_STAT == 6 || GEJ_STAT == 9)
                	{
                		document.getElementById('btnSave').style.display 		= 'none';
                	}

            		if(btnShow == 0)
            		{
                		document.getElementById('btnSave').style.display 		= 'none';
                	}
                }
            });
		}
	// END : LOCK PROCEDURE

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

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		
		PO_NUM		= arrItem[0];
		PO_CODE		= arrItem[1];

		document.getElementById("REF_NUM").value 	= PO_NUM;
		document.getElementById("REF_CODE").value 	= PO_CODE;
		document.getElementById("REF_CODE1").value 	= PO_CODE;
		document.getElementById("REF_CODE2").value 	= PO_CODE;
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();

		countAmount();
	}
	
	function add_listAcc(strItem) 
	{
		var acc_number		= document.getElementById('acc_number').value;
		var GEJ_STAT		= document.getElementById('GEJ_STAT').value;
		var IS_LAST			= document.getElementById('IS_LAST').value;

		if(GEJ_STAT == 3 && IS_LAST == 1 && acc_number == '')
		{
			swal('<?php echo $alert11; ?>',
			{
				icon: "warning",
			});
			document.getElementById('acc_number').focus();
			return false;;
		}
		
		var objTable, objTR, objTD, intIndex;
		
		// VARIABLES
			JournalH_Code		= '';
			Acc_Id				= '';
			proj_Code 			= '';
			ITM_CODE			= '';
			ITM_GROUP			= '';
			ITM_VOLM			= 0;
			ITM_UNIT			= '';
			ITM_PRICE			= 0;
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

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX
		
		// Account Icon
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Account Number
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][JournalH_Code]" id="data'+intIndex+'JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" ><input type="text" name="data['+intIndex+'][Acc_Id]" id="data'+intIndex+'Acc_Id" value="'+Acc_Id+'" class="form-control" style="max-width:150px;" onClick="selectAccount('+intIndex+');" placeholder="<?php echo $SelectAccount; ?>" ><input type="hidden" name="data['+intIndex+'][proj_Code]" id="data'+intIndex+'proj_Code" value="<?php echo $proj_Code; ?>" class="form-control" style="max-width:150px;" >';
		
		// Account Description
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = '<div id="div_jdesc_'+intIndex+'" style="display: none;"></div><input type="text" name="data['+intIndex+'][JournalD_Desc]" id="data'+intIndex+'JournalD_Desc" value="'+JournalD_Desc+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>" readonly><input type="hidden" name="data['+intIndex+'][ITM_CODE]" id="data'+intIndex+'ITM_CODE" value="'+ITM_CODE+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $ItemCode; ?>"><input type="hidden" name="data['+intIndex+'][ITM_CATEG]" id="data'+intIndex+'ITM_CATEG" value="'+ITM_GROUP+'" class="form-control" style="max-width:500px;" ><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" style="max-width:500px;" >';
		
		// Account Position
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.style.display = 'none';
			objTD.innerHTML = '<select name="data['+intIndex+'][JournalD_Pos]" id="data'+intIndex+'JournalD_Pos" class="form-control" style="max-width:100px" onChange="countAmount()"><option value="">--</option><option value="D" selected>D</option><option value="K">K</option></select>';
		
		// Account is Tax
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.style.display = 'none';
			objTD.innerHTML = '<select name="data['+intIndex+'][isTax]" id="data'+intIndex+'isTax" class="form-control" style="max-width:100px" onChange="countAmount()"><option value="0" selected>No</option><option value="1">Yes</option></select>';
		
		// Account Reference
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.style.display = 'none';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Ref_Number]" id="data'+intIndex+'Ref_Number" value="'+Ref_Number+'" class="form-control" style="max-width:200px;" placeholder="<?php echo $RefNumber; ?>" >';
		
		// Item Unit
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = '<div id="div_unit_'+intIndex+'" style="display: none;"></div><input type="text" name="data'+intIndex+'ITM_UNIT" id="ITM_UNIT'+intIndex+'" value="'+ITM_UNIT+'" class="form-control" style="min-width:50px;" readonly><input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:150px;" >';
		
		// Volume
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right" name="ITM_VOLM'+intIndex+'" id="ITM_VOLM'+intIndex+'" value="'+ITM_VOLM+'" placeholder="Volume" onBlur="chgVolume(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][ITM_VOLM]" id="data'+intIndex+'ITM_VOLM" value="'+ITM_VOLM+'" class="form-control" style="max-width:150px;"><input type="hidden" name="data'+intIndex+'ITM_REM" id="data'+intIndex+'ITM_REM" value="0" class="form-control" style="max-width:150px;" ><input type="hidden" name="data'+intIndex+'ITM_REMAMN" id="data'+intIndex+'ITM_REMAMN" value="0" class="form-control" style="max-width:150px;" >'; 
		
		// Price
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+ITM_PRICE+'" placeholder="<?php echo $Price; ?>" onBlur="chgPrice(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:150px;">'; 
		
		// Total Amount
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right" name="JournalD_Amount'+intIndex+'" id="JournalD_Amount'+intIndex+'" value="'+JournalD_Debet+'" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);" readonly><input type="hidden" name="data['+intIndex+'][JournalD_Amount]" id="data'+intIndex+'JournalD_Amount" value="'+JournalD_Debet+'" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][isDirect]" id="data'+intIndex+'isDirect" value="1" class="form-control" style="max-width:150px;" >';
		
		// Remain Amount
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right" name="JournalD_AmountR'+intIndex+'" id="JournalD_AmountR'+intIndex+'" value="0.00" placeholder="<?php echo $Remain; ?>" onKeyPress="return isIntOnlyNew(event);" disabled>';
		
		// Remarks
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Other_Desc]" id="data'+intIndex+'Other_Desc" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">';
		
		var decFormat														= document.getElementById('decFormat').value;
		var JournalD_Amount													= document.getElementById('JournalD_Amount'+intIndex).value
		document.getElementById('JournalD_Amount'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(JournalD_Amount)),decFormat));
		document.getElementById('data'+intIndex+'JournalD_Amount').value 	= parseFloat(Math.abs(JournalD_Amount));
		document.getElementById('ITM_PRICE'+intIndex).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		document.getElementById('ITM_VOLM'+intIndex).value 					= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)),decFormat));
		document.getElementById('totalrow').value = intIndex;
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
		ITM_GROUP		= arrItem[3];
		JOBCODEID		= arrItem[4];
		Rem_Budget		= arrItem[5];
		Rem_BudAmn		= arrItem[6];
		ITM_UNIT		= arrItem[7];
		ITM_PRICE		= arrItem[8];
		theRow 			= arrItem[9];

		document.getElementById('data'+theRow+'Acc_Id').value			= Acc_Id;
		document.getElementById('data'+theRow+'ITM_CODE').value			= Item_Code;
		document.getElementById('data'+theRow+'ITM_CATEG').value		= ITM_GROUP;
		document.getElementById('data'+theRow+'ITM_REM').value			= Rem_Budget;
		document.getElementById('data'+theRow+'ITM_REMAMN').value		= Rem_BudAmn;
		document.getElementById('JournalD_AmountR'+theRow).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Rem_BudAmn)),2));
		document.getElementById('data'+theRow+'ITM_UNIT').value			= ITM_UNIT;
		document.getElementById('ITM_UNIT'+theRow).value				= ITM_UNIT;
		document.getElementById('div_unit_'+theRow).innerHTML			= ITM_UNIT;
		document.getElementById('ITM_PRICE'+theRow).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),2));
		document.getElementById('data'+theRow+'ITM_PRICE').value		= ITM_PRICE;
		document.getElementById('data'+theRow+'JOBCODEID').value		= JOBCODEID;
		document.getElementById('data'+theRow+'JournalD_Desc').value	= JournalD_Desc;
		document.getElementById('div_jdesc_'+theRow).innerHTML			= JournalD_Desc;
		/*if(ITM_UNIT == 'LS')
		{
			document.getElementById('ITM_VOLM'+theRow).disabled = true;
		}
		else
		{
			document.getElementById('ITM_VOLM'+theRow).disabled = false;
		}*/

		/* ------ START NEW PROSEDUR ------
			PEMBICARAAN PAK DEDE UBAY DAN PAK EDI WONG 06 DESEMBER 2021
			KHUSUS UNTUK ITEM "LS" MAKA BISA INPUT VOLUME BERAPAPUN UNTUK BAHAN PENCATATAN, NAMUN TIDAK MEMPENGARUHI RAP VOLUME
		/* ------ START NEW PROSEDUR ------ */
	}
	
	function chgVolume(thisval, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_VOLM		= parseFloat(eval(thisval).value.split(",").join(""));

		var ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
		var ITM_REMVOL		= parseFloat(document.getElementById('data'+row+'ITM_REM').value);
		var ITM_REMAMN		= parseFloat(document.getElementById('data'+row+'ITM_REMAMN').value);
		
		var ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		var ITM_UNIT 		= ITMUNIT.toUpperCase();

		if(ITM_UNIT == 'LS')
		{
			var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

			if(TOTPRC > ITM_REMAMN)
			{
				var ITM_REMVOLV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMVOL)), decFormat));

				swal('<?php echo $alert15; ?>'+ITM_REMVOLV,
				{
					icon: "warning",
				})
				.then(function()
	            {
	                swal.close();
					ITM_VOLM		= parseFloat(ITM_REMVOL);
	                document.getElementById('ITM_VOLM'+row).focus();

					var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

					document.getElementById('ITM_VOLM'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), decFormat));
					document.getElementById('data'+row+'ITM_VOLM').value 		= parseFloat(Math.abs(ITM_VOLM));
					document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
					document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

					countAmount();
	            });
			}
			else
			{
				var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

				document.getElementById('ITM_VOLM'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), decFormat));
				document.getElementById('data'+row+'ITM_VOLM').value 		= parseFloat(Math.abs(ITM_VOLM));
				document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
				document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

				countAmount();
			}
		}
		else
		{
			if(ITM_VOLM > ITM_REMVOL)
			{
				var ITM_REMVOLv	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMVOL)), decFormat));

				swal('<?php echo $alert15; ?>'+ITM_REMVOLv,
				{
					icon: "warning",
				})
				.then(function()
	            {
	                swal.close();
					ITM_VOLM		= parseFloat(ITM_REMVOL);
	                document.getElementById('ITM_VOLM'+row).focus();

					var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

					document.getElementById('ITM_VOLM'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), decFormat));
					document.getElementById('data'+row+'ITM_VOLM').value 		= parseFloat(Math.abs(ITM_VOLM));
					document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
					document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

					countAmount();
	            });
			}
			else
			{
				var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

				document.getElementById('ITM_VOLM'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), decFormat));
				document.getElementById('data'+row+'ITM_VOLM').value 		= parseFloat(Math.abs(ITM_VOLM));
				document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
				document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

				countAmount();
			}
		}
	}
	
	function chgPrice(thisval, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_PRICE		= parseFloat(eval(thisval).value.split(",").join(""));

		var ITM_VOLM		= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);
		var ITM_REMVOL		= parseFloat(document.getElementById('data'+row+'ITM_REM').value);
		var ITM_REMAMN		= parseFloat(document.getElementById('data'+row+'ITM_REMAMN').value);
		
		var ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		var ITM_UNIT 		= ITMUNIT.toUpperCase();

		var TOTPRC			= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

		if(TOTPRC > ITM_REMAMN)
		{
			var ITM_REMPRC 	= parseFloat(ITM_REMAMN) / parseFloat(ITM_VOLM);
			var ITM_REMPRCV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMPRC)), decFormat));

			swal('<?php echo $alert15; ?>'+ITM_REMPRCV,
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
				ITM_PRICE		= parseFloat(ITM_REMPRC);
                document.getElementById('ITM_PRICE'+row).focus();

				var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

				document.getElementById('ITM_PRICE'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)), decFormat));
				document.getElementById('data'+row+'ITM_PRICE').value 		= parseFloat(Math.abs(ITM_PRICE));
				document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
				document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

				countAmount();
            });
		}
		else
		{
			var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

			document.getElementById('ITM_PRICE'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)), decFormat));
			document.getElementById('data'+row+'ITM_PRICE').value 		= parseFloat(Math.abs(ITM_PRICE));
			document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
			document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

			countAmount();
		}
	}
	
	function chgAmount(thisval, row)			// HOLD
	{
		var decFormat		= document.getElementById('decFormat').value;
		var Acc_Amount		= parseFloat(eval(thisval).value.split(",").join(""));
		var Rem_BudAmn		= parseFloat(document.getElementById('data'+row+'ITM_REMAMN').value);
		var ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
		var ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		var ITM_UNIT 		= ITMUNIT.toUpperCase();
		
		if(ITM_UNIT == 'LS')
		{
			Acc_Volume		= parseFloat(Acc_Amount / ITM_PRICE);
			Acc_Amount		= parseFloat(Acc_Amount);
		}
		else
		{
			if(Acc_Amount > Rem_BudAmn)
			{
				// PERUBAHAN BERDASARKAN MS.201962000021
				var Rem_BudAmnV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Rem_BudAmn)),decFormat));
				swal("Out of budget, max amount "+Rem_BudAmnV)
				Acc_Volume	= parseFloat(Rem_BudAmn / ITM_PRICE);
				Acc_Amount	= parseFloat(Rem_BudAmn);
			}
			else
			{
				Acc_Volume	= parseFloat(Acc_Amount / ITM_PRICE);
				Acc_Amount	= parseFloat(Acc_Amount);
			}
		}
		
		document.getElementById('ITM_VOLM'+row).value 					= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Volume)),decFormat));
		document.getElementById('data'+row+'ITM_VOLM').value 			= parseFloat(Math.abs(Acc_Volume));
		
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
		document.getElementById('Journal_Amount').value 	= totAmountD;
		document.getElementById('Journal_AmountX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountD)),decFormat));
		document.getElementById('Journal_AmountD').value 	= totAmountD;
		document.getElementById('Journal_AmountK').value 	= totAmountK;
	}
	
	function countAmount()
	{
		var decFormat	= document.getElementById('decFormat').value;
		var totRow 		= document.getElementById('totalrow').value;
		var acc_bal		= parseFloat(eval(document.getElementById('ACC_OPBAL')).value.split(",").join(""));
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

			if(parseFloat(totAmountD) > parseFloat(acc_bal))
			{
				/*swal('<?php echo $alert12; ?>',
				{
					icon: "warning",
				})
				.then(function()
	            {
	                swal.close();
	                document.getElementById('ITM_VOLM'+i).focus()
	                document.getElementById('ITM_VOLM'+i).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
					document.getElementById('data'+i+'ITM_VOLM').value 			= parseFloat(Math.abs(0));
					document.getElementById('JournalD_Amount'+i).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
					document.getElementById('data'+i+'JournalD_Amount').value 	= parseFloat(Math.abs(0));
	            });
				return false;*/
			}
		}

		document.getElementById('Journal_Amount').value 	= totAmountD;
		document.getElementById('Journal_AmountX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountD)), 2));
		document.getElementById('Journal_AmountD').value 	= totAmountD;
		document.getElementById('Journal_AmountK').value 	= totAmountK;
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
	
	function validateInData(value)
	{
		var ISPERSL 		= document.getElementById('ISPERSL').value;
		var JournalH_Desc 	= document.getElementById('JournalH_Desc').value;
		var PERSL_STAT 		= document.getElementById('PERSL_STAT').value;
		var REF_CODE 		= document.getElementById('REF_CODE').value;
		var GEJ_STAT 		= document.getElementById('GEJ_STAT').value;
		var IS_LAST 		= document.getElementById('IS_LAST').value;
		var acc_number		= document.getElementById('acc_number').value;
		var totAmountD		= parseFloat(document.getElementById('Journal_AmountD').value);
		var totAmountK		= parseFloat(document.getElementById('Journal_AmountK').value);
		var Journal_Amount	= parseFloat(document.getElementById('Journal_Amount').value);
		var acc_balance		= parseFloat(eval(document.getElementById('ACC_OPBAL')).value.split(",").join(""));
		
		/*document.getElementById('btnSave').style.display 		= 'none';
		document.getElementById('btnBack').style.display 		= 'none';*/

		if((PERSL_STAT == 2 || PERSL_STAT == 3) && REF_CODE == '')
		{
			swal('<?php echo $alert17; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#REF_CODE1').focus();
            });
			return false;
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

		if(Journal_Amount <= 0)
		{
			swal('<?php echo $alert14; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#Journal_AmountX').focus();
            });
			return false;
		}

		if(GEJ_STAT == 4)
		{
			//var JournalH_Desc2 	= document.getElementById('JournalH_Desc2').value;
			var JournalH_Desc2 	= document.getElementById('JournalH_Desc').value;
			if(JournalH_Desc2 == '')
			{
				swal('<?php echo $alert10; ?>',
				{
					icon: "warning",
				})
				.then(function()
	            {
	                swal.close();
	                $('#JournalH_Desc2').val('');
	            });
				return false;
			}
		}
		else if(GEJ_STAT == 5)
		{
			//var JournalH_Desc2 	= document.getElementById('JournalH_Desc2').value;
			var JournalH_Desc2 	= document.getElementById('JournalH_Desc').value;
			if(JournalH_Desc2 == '')
			{
				swal('<?php echo $alert10; ?>',
				{
					icon: "warning",
				});
				document.getElementById('JournalH_Desc2').focus();
				return false;
			}
		}
		else if(GEJ_STAT == 9)
		{
			//var JournalH_Desc2 	= document.getElementById('JournalH_Desc2').value;
			var JournalH_Desc2 	= document.getElementById('JournalH_Desc').value;
			if(JournalH_Desc2 == '')
			{
				swal('<?php echo $alert10; ?>',
				{
					icon: "warning",
				})
				.then(function()
	            {
	                swal.close();
	                $('#JournalH_Desc2').val('');
	            });
				return false;
			}
		}

		if(parseFloat(Journal_Amount) > parseFloat(acc_balance))
		{
			/*swal('<?php echo $alert12; ?>',
			{
				icon: "warning",
			});
			return false;*/
		}

		if(GEJ_STAT == 3 && IS_LAST == 1 && acc_number == '')
		{
			swal('<?php echo $alert11; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#acc_number').focus();
            });
			return false;
		}

		$('#datepicker').prop('disabled', false);
		$('#datepicker1').prop('disabled', false);
		$('#datepicker2').prop('disabled', false);
		
		document.getElementById('btnSave').style.display 		= 'none';
		document.getElementById('btnBack').style.display 		= 'none';

		document.frm.submit();
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
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