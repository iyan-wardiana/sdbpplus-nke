<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 2 Desember 2021
	* File Name		= v_r_pd_form.php
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

$isSetDocNo = 1;
$DocNumber			= $JournalH_Code;
/*$JournalH_Code	= $default['JournalH_Code'];
$Manual_No			= $default['Manual_No'];
$DocNumber			= $default['JournalH_Code'];
$JournalH_Date		= $default['JournalH_Date'];*/
$JournalH_DateX		= $JournalH_Date;
$JournalH_DateY		= date('d/m/Y',strtotime($JournalH_Date));
// echo "$JournalH_Date_PD";
// $JournalH_Date_PD	= date('d/m/Y',strtotime($JournalH_Date_PD)); // diambil dari controll C_r_pd
/*$JournalH_Desc	= $default['JournalH_Desc'];
$JournalH_Desc2		= $default['JournalH_Desc2'];
$proj_Code			= $default['proj_Code'];
$PRJCODE_HO			= $default['proj_CodeHO'];
$PRJPERIOD			= $default['PRJPERIOD'];
$proj_Code			= $default['proj_Code'];*/
$PRJCODE			= $proj_Code;
/*$GEJ_STAT_PD		= $default['GEJ_STAT_PD'];
$acc_number			= $default['acc_number'];
$Journal_Amount		= $default['Journal_Amount'];
$ISPERSL			= $default['ISPERSL'];
$PERSL_EMPID		= $default['PERSL_EMPID'];*/

// GET Journal Lock
	$disabled 	= 0;
	$getJLock 	= "SELECT * FROM tbl_journal_lock 
					WHERE LockY = $JournalY AND LockM = $JournalM AND isLock = 1 AND UserLock != '$DefEmp_ID'";
	$resJLock 	= $this->db->query($getJLock);
	$countJLock = $resJLock->num_rows();
	if($countJLock == 1) $disabled = 1;

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
		
if($LangID == 'IND')
{
	$docalert1	= 'Peringatan';
	$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
	$docalert3	= 'Anda belum men-setting akun untuk dokumen Pengeluaran Kas kategori Pembayaran Dimuka. Silahkan atur akun Pembayaran Dimuka dari menu pengaturan umum pada Menu Pengaturan.';
}
else
{
	$docalert1	= 'Warning';
	$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
	$docalert3	= 'You have not set up an account for the Cash Expenditure document for the Personal Loan category. Please set up a Loan Account from the General Settings menu in the Settings Menu.';
}

$img_filenameX 	= "";
$sqlGetIMG		= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$PERSL_EMPID'";
$resGetIMG 		= $this->db->query($sqlGetIMG)->result();
foreach($resGetIMG as $rowGIMG) :
	$imgemp_filename 	= $rowGIMG ->imgemp_filename;
	$img_filenameX 		= $rowGIMG ->imgemp_filenameX;
endforeach;
$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$PERSL_EMPID.'/'.$img_filenameX);
if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$PERSL_EMPID))
{
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
}
$secPrint = site_url('c_finance/c_r_pd/printdocument_PD/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">

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
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'UniqCode')$UniqCode = $LangTransl;
			if($TranslCode == 'othInfo')$othInfo = $LangTransl;
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'sourceDoc')$sourceDoc = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'ItmList')$ItmList = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Receiver')$Receiver = $LangTransl;
			if($TranslCode == 'OthNotes')$OthNotes = $LangTransl;
			if($TranslCode == 'pdRealiz')$pdRealiz = $LangTransl;
			if($TranslCode == 'pdVal')$pdVal = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'settVal')$settVal = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'ovPay')$ovPay = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'Reference')$Reference = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$subTitleD	= "Penggunaan Kas";
			$alert0		= "Masukan deskripsi jurnal transaksi.";
			$alert1		= "Silahkan pilih Nomor Akun.";
			$alert2		= "Silahkan tuliskan deskripsi jurnal.";
			$alert3		= "Tentukan posisi akun; di Debit atau Kredit.";
			$alert4		= "Tentukan posisi jenis transaksi, pajak atau bukan.";
			$alert5		= "Masukan tanggal faktur pajak.";
			$alert6		= "Masukan volume/harga realisasi.";
			$alert7		= "Belum ada akun untuk sisi Debit.";
			$alert8		= "Belum ada akun untuk sisi Kredit.";
			$alert9		= "Nilai Debit dan Kredit tidak sama.";
			$alert10	= "Masukan alasan mengapa dokumen ini di batalkan.";
			$alert11	= "Pilih sumber pembayaran.";
			$alert12	= "Saldo tidak cukup.";
			$alert13	= "Nama penerima Perjalanan Dinas (PD) tidak boleh kosong.";
			//$alert14	= "Total detail melebihi nilai Perjalanan Dinas (PD). Apabila tetap melanjutkan, sistem akan membuat voucher cash secara otomatis.";
			$alert14	= "Total detail melebihi nilai Perjalanan Dinas (PD). Apabila tetap melanjutkan?.";
			$alert15	= "Melebihi sisa volume anggaran. Maksimum sisa volume ";
			$alert16	= "Melebihi sisa anggaran. Maksimum sisa ";
			$alert17	= "Sisa realisasi melebihi batas nilai PD. Centang checkbox pada kolom isOver untuk dapat tetap melanjutkan pengisian.";
			$alert18	= "Total penyelesaian PD melebihi nilai Perjalanan Dinas (PD).";
			$alert19	= "Total penyelesaian PD melebihi nilai maksimum anggaran. Sisa anggaran ";
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
			$alert5		= "Insert tax date";
			$alert6		= "Input volume / price of realization.";
			$alert7		= "Debit Side transaction can not be empty.";
			$alert8		= "Credit Side transaction can not be empty";
			$alert9		= "Debit dan Credit Amount must be same.";
			$alert10	= "Please write a comment why you void this document.";
			$alert11	= "Please select source of payment.";
			$alert12	= "Insufficient cash account balance..";
			$alert13	= "The name of the official loan recipient cannot be empty.";
			$alert14	= "Total details exceed the value of PD Amount. Are you sure to continue?";
			$alert15	= "Exceeded the remaining budget volume. Maximum remaining volume ";
			$alert16	= "Exceeded the remaining budget. Maximum remaining ";
			$alert17	= "The remain of PD is up. Please check the checkbox in isOver column.";
			$alert18	= "Total details exceed the value of PD Amount";
			$alert19	= "Total details exceed the value of budget. Budget remain ";
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
				
				if($GEJ_STAT_PD == 3 && $resC_App > 0)
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

		textarea {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;

			width: 100%;
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

        <?php
        	$ACC_ID_PERSL	= "";
        	$ACC_ID_EMPAP	= "";
        	$s_01 			= "SELECT A.ACC_ID_PERSL, A.ACC_ID_EMPAP FROM tglobalsetting A";
        	$r_01 			= $this->db->query($s_01)->result();
        	foreach($r_01 as $rw_01):
        		$ACC_ID_PERSL 	= $rw_01->ACC_ID_PERSL;
        		$ACC_ID_EMPAP 	= $rw_01->ACC_ID_EMPAP;
        	endforeach;
		?>

		<section class="content">
	        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
	            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
	            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
	            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
	            <input type="hidden" name="JournalType" id="JournalType" value="<?php echo $jrnType; ?>" />
	            <input type="hidden" name="ISPERSL_STEP" id="ISPERSL_STEP" value="<?php echo $ISPERSL_STEP; ?>" />
	            <input type="hidden" name="ACC_ID_PERSL" id="ACC_ID_PERSL" value="<?php echo $ACC_ID_PERSL; ?>" />
	            <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
	            <input type="hidden" name="task" id="task" value="<?php echo $task; ?>" />
	            <input type="Hidden" name="rowCount" id="rowCount" value="0">
	            <input type="Hidden" name="selRow" id="selRow" value="0">
                <div class="row">
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
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo "Dok. Pembayaran Dimuka (PD)"; ?></h3>
                            </div>
                            <div class="box-body">
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $UniqCode; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" name="JournalH_Code1" id="JournalH_Code1" size="30" value="<?php echo $JournalH_Code; ?>" />
				                    	<input type="text" class="form-control" style="max-width:400px;text-align:right" name="JournalH_Code" id="JournalH_Code" size="30" value="<?php echo $DocNumber; ?>" />
				                    	<input type="text" class="form-control" style="max-width:400px;text-align:right" name="REF_CODE" id="REF_CODE" size="30" value="<?php echo $Manual_No; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE_HO" id="PRJCODE_HO" size="30" value="<?php echo $PRJCODE_HO; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJPERIOD" id="PRJPERIOD" size="30" value="<?php echo $PRJPERIOD; ?>" />
				                    </div>
				                </div>
				                <div class="row">
				                	<?php
			                    		$compName 	= "-";
			                    		$s_Empl		= "SELECT CONCAT(First_Name,' ', Last_Name) AS compName FROM tbl_employee
			                    						WHERE Emp_ID = '$PERSL_EMPID' LIMIT 1";
										$r_Empl 	= $this->db->query($s_Empl)->result();
										foreach($r_Empl as $rw_Empl) :
											$compName 	= $rw_Empl->compName;
										endforeach;
				                	?>
					                <div class="col-md-3" style="text-align: center;">
					                  	<img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture">
					                  	<?php echo "<br>$PERSL_EMPID<BR>$compName<br><br>"; ?>
					                </div>

			                    	<?php
			                    		$jrnDateV	= strftime('%d %b %Y', strtotime($JournalH_DateX));

			                    		$AccName 	= "-";
			                    		$s_Acc		= "SELECT Account_NameId FROM tbl_chartaccount
			                    						WHERE Account_Number = '$acc_number' AND PRJCODE = '$PRJCODE' LIMIT 1";
										$r_Acc 		= $this->db->query($s_Acc)->result();
										foreach($r_Acc as $rw_Acc) :
											$AccName 	= $rw_Acc->Account_NameId;
										endforeach;
			                    		$AccNameD 	= "$acc_number<br>$AccName";

			                    		/*$RealizVal 	= 0;
			                    		$s_RealVal	= "SELECT SUM(Base_Debet) AS RealizVal FROM tbl_journaldetail_pd
			                    						WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'
			                    							AND Journal_DK = 'D' AND GEJ_STAT_PD IN (2,3,7) AND ISPERSL_STEP = $ISPERSL_STEP";
										$r_RealVal 	= $this->db->query($s_RealVal)->result();
										foreach($r_RealVal as $rw_RealVal) :
											$RealizVal 	= $rw_RealVal->RealizVal;
										endforeach;*/

			                    		$totRealiz = 0;
			                    		/*$s_totReal	= "SELECT SUM(Base_Debet) AS totRealiz FROM tbl_journaldetail_pd
			                    						WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'
			                    							AND Journal_DK = 'D' AND GEJ_STAT_PD IN (2,3,7) AND (ISPERSL_STEP != 0 AND ISPERSL_STEP != $ISPERSL_STEP)";*/
			                    		$s_totReal	= "SELECT SUM(Base_Debet) AS totRealiz, SUM(PPN_Amount) AS totPPN_AM, SUM(PPH_Amount) AS totPPH_AM FROM tbl_journaldetail_pd
			                    						WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'
			                    							AND Journal_DK = 'D' AND GEJ_STAT_PD IN (1,2,3,7) AND (ISPERSL_STEP != 0)";
										$r_totReal 	= $this->db->query($s_totReal)->result();
										foreach($r_totReal as $rw_totReal) :
											$totRealiz 	= $rw_totReal->totRealiz;
											$totPPN_AM  = $rw_totReal->totPPN_AM;
											$totPPH_AM  = $rw_totReal->totPPH_AM;
										endforeach;
										$remRealiz 	= $Journal_Amount - ($totRealiz + $totPPN_AM - $totPPH_AM);

										if($remRealiz < 0)
										{
											$remRealiz 	= 0;
											$OverRPD 	= $Journal_Amount - ($totRealiz + $totPPN_AM - $totPPH_AM);
										}
			                    	?>

					                <div class="col-md-9">
					                  	<div class="row">
					                    	<div class="col-md-2">
					                    		<?php echo "<strong>$Code</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-10">
					                    		<?php echo "<span style='display: inline-block; padding-right: 90px;'>$Manual_No</span>
												<strong>$Date :</strong> $jrnDateV"; ?>
					                    	</div>
				                    	</div>
										<div class="row">
											<div class="col-md-2">
					                    		<?php echo "<strong>$Reference</strong>"; ?>
					                    	</div>
											<div class="col-md-10">
												<span><?=$Reference_Number?></span>
											</div>
										</div>
					                  	<div class="row" style="display: none;">
					                    	<div class="col-md-2">
					                    		<?php echo "<strong>$Description</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-10">
					                    		<?php echo "<strong>$srcPayment:</strong><br><i>$AccNameD</i><br>"; ?>
					                    	</div>
				                    	</div>
					                  	<div class="row">
					                    	<div class="col-md-2">
					                    		<?php echo "<strong>$Notes</strong>"; ?>
					                    	</div>
					                    	<div class="col-md-10">
					                    		<?php echo "<i>$JournalH_Desc</i><br>"; ?>
					                    	</div>
				                    	</div>
					                  	<div class="row">
					                    	<div class="col-md-2">
					                    		<?php echo ""; ?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php echo "<i>$RealizationValue:</i><br><i class='text-primary' style='font-size: 16px;'><strong>".number_format($Journal_Amount, 2)."</strong></i><br>"; ?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php echo "<i>$settVal:</i><br><i class='text-success' style='font-size: 16px;'><strong>".number_format(($totRealiz + $totPPN_AM - $totPPH_AM), 2)."</strong></i>"; ?>
					                    	</div>
				                    	</div>
					                  	<div class="row">
					                    	<div class="col-md-2">
					                    		<?php echo ""; ?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php echo "<i>Kurang Bayar:</i><br><i class='text-danger' style='font-size: 16px;' id='ovPay'><strong>".number_format($OverRPD, 2)."</strong></i>"; ?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php echo "<i>$Remain:</i><br><i class='text-yellow' style='font-size: 16px;'><strong>".number_format($remRealiz, 2)."</strong></i>"; ?>
					                    	</div>
				                    	</div>
					                  	<input type="hidden" class="form-control" id="proj_Code" name="proj_Code" value="<?php echo $proj_Code; ?>" />
				                    	<input type="hidden" name="JournalH_Date" id="datepicker" value="<?php echo $JournalH_Date; ?>">
				                    	<input type="hidden" name="acc_number" id="acc_number" value="<?php echo $acc_number; ?>" />
				                    	<input type="hidden" name="ISPERSL" id="ISPERSL" value="<?php echo $ISPERSL; ?>" />
				                    	<input type="hidden" name="PERSL_EMPID" id="PERSL_EMPID" value="<?php echo $PERSL_EMPID; ?>" />
				                    	<textarea name="JournalH_Desc" class="form-control" id="JournalH_Desc" style="display: none;"><?php echo $JournalH_Desc; ?></textarea>
				                        <input type="hidden" name="Journal_AmountD" id="Journal_AmountD" value="">
				                        <input type="hidden" name="Journal_AmountK" id="Journal_AmountK" value="">
				                        <input type="hidden" name="Journal_Amount" id="Journal_Amount" value="<?php echo $Journal_Amount; ?>" />
				                        <!-- <input type="hidden" name="Journal_AmountRemOri" id="Journal_AmountRemOri" value="<?php echo $remRealiz; ?>" /> -->
				                        <input type="hidden" name="Journal_AmountRemOri" id="Journal_AmountRemOri" value="<?php echo $Journal_Amount; ?>" />
				                        <input type="hidden" name="Journal_AmountRem" id="Journal_AmountRem" value="<?php echo $remRealiz; ?>" />
				                        <input type="hidden" name="Journal_TotRealiz" id="Journal_TotRealiz" value="0" />
				                        <input type="hidden" name="OverRPD" id="OverRPD" value="<?php echo $OverRPD; ?>" />
					                </div>
					            </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo "Penyelesaian PD";//$pdRealiz; ?></h3>
                            </div>
                            <div class="box-body">
				                <div class="form-group">
	                            	<div class="col-sm-7">
	                                    <label for="exampleInputEmail1">Kode Penyelesaian</label>
				                    	<input type="text" class="form-control" name="Manual_No" id="Manual_No" size="30" value="<?php echo "$Manual_No"; ?>" readonly />
		                            </div>
	                            	<div class="col-sm-5">
	                                    <label for="exampleInputEmail1"><?=$Date?></label>
				                    	<div class="input-group date">
				                        	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                        	<?php
				                        		if($GEJ_STAT_PD == 0 || $GEJ_STAT_PD == 1 || $GEJ_STAT_PD == 4)
				                        		{
				                        			?>
					                        		<input type="text" name="JournalH_Date_PD" class="form-control pull-left" id="datepicker1" value="<?php echo $JournalH_Date_PD; ?>">
					                        		<?php
					                        	}
					                        	else
					                        	{
				                        			?>
					                        		<input type="text" name="JournalH_Date_PD" class="form-control pull-left" id="datepicker1" value="<?php echo $JournalH_Date_PD; ?>" readonly>
					                        		<?php
					                        	}
					                        ?>
					                    </div>
		                            </div>
				                </div>
				                <?php
				                    $sqlCAPPH	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
				                    				AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
				                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
									
									$disButton 	= 1;
				                	if($resCAPPH == 0)
				                	{
				                		if($LangID == 'IND')
										{
											$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
										}
										else
										{
											$zerSetApp	= "There are no arrangements for the approval of this document.";
										}
				                	}
				                ?>

				                <div class="form-group">
	                            	<div class="col-sm-7">
					                    <label for="inputName"><?php echo $Status; ?></label>
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $GEJ_STAT_PD; ?>">
										<?php
											// START : FOR ALL APPROVAL FUNCTION
												$disButton	= 0;
												$disButtonE	= 0;
												if($task == 'add')
												{
													if($ISCREATE == 1)
													{
														?>
															<select name="GEJ_STAT_PD" id="GEJ_STAT_PD" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																<option value="1">New</option>
																<option value="2">Confirm</option>
															</select>
														<?php
													}
												}
												else
												{
													$disButton	= 1;
													if($ISCREATE == 1)
													{
														if($GEJ_STAT_PD == 1 || $GEJ_STAT_PD == 4)
														{
															$disButton	= 0;
															?>
																<select name="GEJ_STAT_PD" id="GEJ_STAT_PD" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														elseif($GEJ_STAT_PD == 2 || $GEJ_STAT_PD == 7)
														{
															$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;										
															?>
																<select name="GEJ_STAT_PD" id="GEJ_STAT_PD" class="form-control select2">
																	<option value="1"<?php if($GEJ_STAT_PD == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($GEJ_STAT_PD == 2) { ?> selected <?php } ?>>Confirm</option>
																	<option value="3"<?php if($GEJ_STAT_PD == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
																	<option value="4"<?php if($GEJ_STAT_PD == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($GEJ_STAT_PD == 5) { ?> selected <?php } ?> >Rejected</option>
																	<option value="6"<?php if($GEJ_STAT_PD == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($GEJ_STAT_PD == 7) { ?> selected <?php } ?> >Waiting</option>
																	<option value="9"<?php if($GEJ_STAT_PD == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
														elseif($GEJ_STAT_PD == 3)
														{
															$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;	
															if($ISDELETE == 1)
																$disButton	= 0;				
														
															?>
																<select name="GEJ_STAT_PD" id="GEJ_STAT_PD" class="form-control select2">
																	<option value="1"<?php if($GEJ_STAT_PD == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($GEJ_STAT_PD == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																	<option value="3"<?php if($GEJ_STAT_PD == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
																	<option value="4"<?php if($GEJ_STAT_PD == 4) { ?> selected <?php } ?> disabled>Revising</option>
																	<option value="5"<?php if($GEJ_STAT_PD == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																	<option value="6"<?php if($GEJ_STAT_PD == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($GEJ_STAT_PD == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<option value="9"<?php if($GEJ_STAT_PD == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
													}
													elseif($ISAPPROVE == 1)
													{
														if($GEJ_STAT_PD == 1 || $GEJ_STAT_PD == 4)
														{
															$disButton	= 1;
															?>
																<select name="GEJ_STAT_PD" id="GEJ_STAT_PD" class="form-control select2">
																	<option value="1">New</option>
																	<option value="2">Confirm</option>
																</select>
															<?php
														}
														elseif($GEJ_STAT_PD == 2 || $GEJ_STAT_PD == 7)
														{
															$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;						
														
															?>
																<select name="GEJ_STAT_PD" id="GEJ_STAT_PD" class="form-control select2" >
																	<option value="1"<?php if($GEJ_STAT_PD == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($GEJ_STAT_PD == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																	<option value="3"<?php if($GEJ_STAT_PD == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
																	<option value="4"<?php if($GEJ_STAT_PD == 4) { ?> selected <?php } ?> >Revising</option>
																	<option value="5"<?php if($GEJ_STAT_PD == 5) { ?> selected <?php } ?> >Rejected</option>
																	<option value="6"<?php if($GEJ_STAT_PD == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($GEJ_STAT_PD == 7) { ?> selected <?php } ?> disabled>Waiting</option>
																	<option value="9"<?php if($GEJ_STAT_PD == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
														elseif($GEJ_STAT_PD == 3)
														{
															$disButton	= 0;
															if($canApprove == 0)
																$disButton	= 1;
															
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$JournalH_Code' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;					
														
															?>
																<select name="GEJ_STAT_PD" id="GEJ_STAT_PD" class="form-control select2">
																	<option value="1"<?php if($GEJ_STAT_PD == 1) { ?> selected <?php } ?> disabled>New</option>
																	<option value="2"<?php if($GEJ_STAT_PD == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																	<option value="3"<?php if($GEJ_STAT_PD == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
																	<option value="4"<?php if($GEJ_STAT_PD == 4) { ?> selected <?php } ?> disabled>Revising</option>
																	<option value="5"<?php if($GEJ_STAT_PD == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																	<option value="6"<?php if($GEJ_STAT_PD == 6) { ?> selected <?php } ?> disabled>Closed</option>
																	<option value="7"<?php if($GEJ_STAT_PD == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																	<option value="9"<?php if($GEJ_STAT_PD == 9) { ?> selected <?php } ?> disabled>Void</option>
																</select>
															<?php
														}
													}
												}
											// END : FOR ALL APPROVAL FUNCTION
				                        ?>
	                            	</div>
	                            	<script type="text/javascript">
	                            		$(document).ready(function () {   
										    $('#GEJ_STAT_PD').change(function() {
											    countAmount();
											});
										}); 
	                            	</script>
	                            	<div class="col-sm-3">
					                    <label for="inputName">&nbsp;</label>
	                            		<?php
											$edited	= 0;
											if ($GEJ_STAT_PD == 0 || $GEJ_STAT_PD == 1 || $GEJ_STAT_PD == 4)
											{
												$edited	= 1;
											}

						                    if ($edited == 1)
						                    {
						                    	?>
						                            <div>
						                                <script>
						                                    var url = "<?php //echo $url_AddItem;?>";
						                                    function selectAccount(theRow)
						                                    {
						                                    	$("#selRow").val(theRow);
						                                        PRJCODE	= '<?php echo $PRJCODE;?>';
						                                        /*title = 'Select Item';
						                                        w = 1100;
						                                        h = 550;
						                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
						                                        var left = (screen.width/2)-(w/2);
						                                        var top = (screen.height/2)-(h/2);
																
						                                        return window.open(url+PRJCODE+'&theRow='+theRow, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);*/

							                                    $('#example0').DataTable(
														    	{
																	"dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>"+
																			"<'row'<'col-sm-12'tr>>",
														    		"ordering": false,
																	"bDestroy": true,
															        "processing": true,
															        "serverSide": true,
																	//"scrollX": false,
																	"autoWidth": true,
																	"filter": true,
															        //"ajax": "<?php echo site_url('c_finance/c_r_pd/get_AllDataITM/?id='.$PRJCODE.'&THEROW=')?>"+theRow,
						       										"ajax": "<?php echo site_url('c_finance/c_cho70d18/get_AllDataITM/?id='.$PRJCODE.'&THEROW=')?>"+theRow,
															        "type": "POST",
																	"lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
																	// "lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
																	"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
																					{ targets: [,4,5,6,7,8,9], className: 'dt-body-right' }
																				  ],
																	"language": {
															            "infoFiltered":"",
															            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
															        },
																});

						                                        document.getElementById('btnModal').click();
						                                    }
						                                </script>
						                                <button id="addAcc" class="btn btn-success" type="button" onClick="add_listAcc();" <?php if($isManualClose != 0) echo "disabled"; ?>>
						                                	<?php echo $Account; ?>&nbsp;&nbsp;[ <i class="glyphicon glyphicon-plus"></i> ]
						                                </button>
														<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_selITM" id="btnModal" style="display: none;">
							                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $ChooseInvoice; ?>
							                        	</a>
						                            </div>
												<?php
						                    }
						                ?>
	                            	</div>
									<div class="col-sm-2">
										<div style="margin-top: 30px; margin-left: -10px;">
											<input id="isCheckClose" name="isCheckClose" type="checkbox" class="minimal" value="0" <?php if($isManualClose != 0) echo "checked"; ?> <?php if($edited == 0) { echo "disabled"; } ?>>
										</div>
									</div>
	                            </div>
								<?php if($ISAPPROVE == 1): ?>
								<div class="form-group">
									<div class="col-sm-5" style="padding-left: 0;">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<input id="isManualClose_1" name="isManualClose" type="radio" class="flat-red" value="1" <?php if($isManualClose == 1) echo "checked"; ?> <?php if($edited == 0) { echo "disabled"; } ?>>
											<label>&nbsp;Close Tunai</label>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<input id="isManualClose_2" name="isManualClose" type="radio" class="flat-red" value="2" <?php if($isManualClose == 2) echo "checked"; ?> <?php if($edited == 0) { echo "disabled"; } ?>>
											<label>&nbsp;Close Lainnya</label>
										</div>
									</div>
									<div class="col-sm-7" style="padding-left: 0;">
										<textarea name="Close_Notes" id="Close_Notes" rows="2" placeholder="&nbsp;Catatan Manual Close" disabled><?php echo $Close_Notes; ?></textarea>
									</div>
								</div>
								<?php endif; ?>
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
                    <div class="col-md-12" <?php if($JournalH_Desc2 == '') { ?> style="display:none" <?php } ?>>
                    	<label for="inputName"><?php echo $Notes; ?></label>
		                <textarea name="JournalH_Desc2" class="form-control" id="JournalH_Desc2" cols="30"><?php echo $JournalH_Desc2; ?></textarea>
                    </div>

                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
		                              	<th width="2%" height="25" style="text-align:left">&nbsp;</th>
		                              	<th width="17%" style="text-align:center; vertical-align: middle;"><?php echo "Item"; ?> </th>
		                              	<th width="5%" style="text-align:center; vertical-align: middle;">Sat.</th>
		                              	<th width="7%" style="text-align:center; vertical-align: middle;">Vol.</th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Price; ?> </th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Amount; ?></th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Remain; ?></th>
		                              	<th width="5%" colspan="2" style="text-align:center; vertical-align: middle;">PPn</th>
		                              	<th width="5%" colspan="2" style="text-align:center; vertical-align: middle;">PPh</th>
		                              	<th width="5%" style="text-align:center; vertical-align: middle;">Tgl. FPajak</th>
		                              	<th width="8%" style="text-align:center; vertical-align: middle;">No. Seri Pajak</th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Remarks; ?></th>
		                              	<th width="1%" style="text-align:center">&nbsp;</th>
		                          	</tr>
		                            <?php	
									$INCPPN = 0; 
									$INCPPH = 0;
									$PPNDES	= "";
									$PPHDES = "";				
		                            if($task == 'edit')
		                            {
		                                $sqlDET	= "SELECT A.*
													FROM tbl_journaldetail_pd A
													WHERE JournalH_Code = '$JournalH_Code' 
														AND A.proj_Code = '$PRJCODE'
														AND A.Journal_DK = 'D' AND A.ISPERSL = 1 AND A.ISPERSL_STEP = $ISPERSL_STEP";
		                                $result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;
										
										foreach($result as $row) :
											$currentRow  		= ++$i;
											$JournalD_Id 		= $row->JournalD_Id;
											$JournalH_Code 		= $row->JournalH_Code;
											$Acc_Id 			= $row->Acc_Id;
											$JOBCODEID 			= $row->JOBCODEID;
											$JournalD_Debet 	= $row->JournalD_Debet;
											$JournalD_Debet_tax = $row->JournalD_Debet_tax;
											$JournalD_Kredit 	= $row->JournalD_Kredit;
											$JournalD_Kredit_tax= $row->JournalD_Kredit_tax;
											$isDirect 			= $row->isDirect;
											$Notes 				= $row->Notes;
											$ITM_CODE 			= $row->ITM_CODE;
											$ITM_CATEG 			= $row->ITM_CATEG;
											$ITM_VOLM 			= $row->ITM_VOLM;
											$ITM_PRICE 			= $row->ITM_PRICE;
											$PPN_Code 			= $row->PPN_Code;
											$PPN_Perc 			= $row->PPN_Perc;
											$PPN_Amount 		= $row->PPN_Amount;
											$PPH_Code 			= $row->PPH_Code;
											$PPH_Perc 			= $row->PPH_Perc;
											$PPH_Amount 		= $row->PPH_Amount;
											$isVerified 		= $row->isVerified;

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

											if($isVerified == 1)
											{
												$JODBDESC 		= "";
												$sqlJOBD1		= "SELECT ITM_PRICE, JOBDESC, JOBPARENT
																		FROM tbl_joblist_detail
																		WHERE JOBCODEID = '$JOBCODEID'
																			AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
												$resJOBD1		= $this->db->query($sqlJOBD1)->result();
												foreach($resJOBD1 as $rowJOBD1) :
													//$ITM_PRICE		= $rowJOBD1->ITM_PRICE;
													$JODBDESC	= $rowJOBD1->JOBDESC;
													$JOBPARENT	= $rowJOBD1->JOBPARENT;
												endforeach;
											}
											else
											{
												$JODBDESC 		= '';
												$JOBDESCPAR		= '';
												$sqlJOBPAR		= "SELECT ITM_NAME AS JOBDESC, PITM_CODE AS JOBPARENT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' LIMIT 1";
												$resJOBPAR		= $this->db->query($sqlJOBPAR)->result();
												foreach($resJOBPAR as $rowJOBPAR) :
													$JODBDESC	= $rowJOBPAR->JOBDESC;
													$JOBPARENT	= $rowJOBPAR->JOBPARENT;
												endforeach;
											}

											if($JODBDESC == '')
											{
												$JODBDESC 		= "";
												$JOBPARENT 		= "";
											}

											$ITM_UNIT 			= $row->ITM_UNIT;
											$TAX_DATE 			= $row->TAX_DATE;
											$TAX_NO 			= $row->TAX_NO;
											$Other_Desc 		= $row->Other_Desc;
											$Journal_DK 		= $row->Journal_DK;
											$isTax 				= $row->isTax;
											$isExtra 			= $row->isExtra;
											
											$ITM_VOLMBG			= 0;
											$ITM_BUDG			= 0;
											$ITM_USED			= 0;
											$ITM_USED_AM		= 0;
											$sqlJOBD			= "SELECT ITM_VOLM AS ITM_VOLMBG, (ITM_BUDG + ADD_JOBCOST - ADDM_JOBCOST) AS ITM_BUDG,
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
											if($isVerified == 0)
											{
												$ITM_VOLMBG		= 0;
												$ITM_BUDG		= 0;
												$ITM_USED		= 0;
												$ITM_USED_AM	= 0;
											}

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
																		AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT_PD IN (2,7)
																		AND JournalH_Code != '$JournalH_Code'";
											$resJOBDR			= $this->db->query($sqlJOBDR)->result();
											foreach($resJOBDR as $rowJOBDR) :
												$ITM_USEDR		= $rowJOBDR->TOTVOL;
												$ITM_USEDR_AM	= $rowJOBDR->TOTAMN;
											endforeach;
											if($isVerified == 0)
											{
												$ITM_USEDR		= 0;
												$ITM_USEDR_AM	= 0;
											}
											
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
											if($isVerified == 0)
											{
												$JOBDESCH		= '';
												$sqlJOBPAR		= "SELECT PITM_NAME AS JOBDESC FROM tbl_item_parent WHERE PITM_CODE = '$JOBPARENT' LIMIT 1";
												$resJOBPAR		= $this->db->query($sqlJOBPAR)->result();
												foreach($resJOBPAR as $rowJOBPAR) :
													$JOBDESCH	= $rowJOBPAR->JOBDESC;
												endforeach;
											}

											$JOBDESCH1 		= wordwrap("$JOBPARENT : $JOBDESCH", 50, "<br>", TRUE);
											$JOBDESCH 		= '<div style="margin-left: 15px; font-style: italic;">
															  		<i class="text-muted fa fa-rss"></i>&nbsp;&nbsp;'.$JOBDESCH1.'
															  	</div>';

											
											$JOBDESCH1 		= $JOBDESCH;
											if($JOBPARENT == '' && $isVerified == 1)
											{
												$disButton 		= 1;
												$JOBDESCH1 		= "Kode komponen ini belum terkunci atau sedang dibuka dalam daftar RAP. Silahkan hubungi pihak yang memiliki otorisasi mengunci RAP.";
												$JOBDESCH2 		= wordwrap("$JOBDESCH1", 50, "<br>", TRUE);
												$JOBDESCH 		= '<span class="label label-danger" style="font-size:12px; font-style: italic;">'.$JOBDESCH2.'</span>';
											}

											$disButtonR		= 0;
											if($BUDG_REMAMNT < $AmountV && $isVerified == 1)
											{
												$disButtonR	= 1;
											}

											$allUSED 		= $ITM_USED_AM + $ITM_USEDR_AM;
											$USEDINFO 		= '<div style="margin-left: 18px; font-style: italic;" title="Disediakan : '.number_format($ITM_USEDR_AM,2).'">
															  		<span class="text-red">Real. : '.number_format($allUSED,2).'</span> / <span class="text-green">'.number_format($ITM_BUDG, 2).'</span>
															  	</div>';
											if($isVerified == 0)
											{
												$USEDINFO 		= "";
											}
											
											//$delROW 	= base_url().'index.php/c_finance/c_cho70d18/delROW/?id=';
											$delROW 	= base_url().'index.php/c_finance/c_cho70d18/delRVCASH/?id=';
											$collDtDel 	= $delROW."~".$JournalD_Id."~".$JournalH_Code."~".$PRJCODE;

											if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}

											if($TAX_NO == '')
												$TAX_DATE 	= "";
											?> 
		                                    <tr id="tr_<?php echo $currentRow; ?>">
												<td style="text-align:center; vertical-align: middle;">
													<?php
														if($GEJ_STAT_PD == 1 || $GEJ_STAT_PD == 4)
														{
															?>
															<input type="hidden" name="urlDel<?=$currentRow?>" id="urlDel<?=$currentRow?>" value="<?=$collDtDel?>">
															<a href="#" onClick="delRow(<?php echo $currentRow; ?>)" title="Hapus" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
															<?php
														}
														else
														{
															echo "$currentRow.";
														}
			                                        ?>
			                                    </td>
											 	<td style="text-align:center; vertical-align: middle; display: none;">
											 		<?php if ($edited == 1) { ?>
											 			<input type="text" name="data[<?php echo $currentRow; ?>][Acc_Id]" id="data<?php echo $currentRow; ?>Acc_Id" value="<?php echo $Acc_Id; ?>" class="form-control" style="max-width:100px;" onClick="selectAccount(<?php echo $currentRow; ?>);" placeholder="<?php echo $SelectAccount; ?>" size="10" >
											 		<?php } else { ?>
											 			<?php echo $Acc_Id; ?>
											 			<input type="hidden" name="data[<?php echo $currentRow; ?>][Acc_Id]" id="data<?php echo $currentRow; ?>Acc_Id" value="<?php echo $Acc_Id; ?>" class="form-control" style="max-width:100px;" onClick="selectAccount(<?php echo $currentRow; ?>);" placeholder="<?php echo $SelectAccount; ?>" size="10" >
											 		<?php } ?>
			                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][JournalH_Code]" id="data<?php echo $currentRow; ?>JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][proj_Code]" id="data<?php echo $currentRow; ?>proj_Code" value="<?php echo $proj_Code; ?>" class="form-control" style="max-width:100px;" >
			                                    </td>
											  	<td style="text-align:left; vertical-align: middle;" nowrap>
											  		<div id="div_jdesc_<?php echo $currentRow; ?>">
													  	<?php echo $JobView; ?>
													  	<?php echo $JOBDESCH; ?>
													  	<?php echo $USEDINFO; ?>
													</div>
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Desc]" id="data<?php echo $currentRow; ?>JournalD_Desc" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>">
			                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CODE]" id="data<?php echo $currentRow; ?>ITM_CODE" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:500px;" placeholder="<?php echo $ItemCode; ?>">
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CATEG]" id="data<?php echo $currentRow; ?>ITM_CATEG" value="<?php echo $ITM_CATEG; ?>" class="form-control" style="max-width:500px;" >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" style="max-width:500px;" >
			                                    </td>
												<td width="3%" nowrap style="text-align:center; display:none">
			                                    	<select name="data[<?php echo $currentRow; ?>][JournalD_Pos]" id="data<?php echo $currentRow; ?>JournalD_Pos" class="form-control" style="max-width:100px;display:none" onChange="countAmount()" >
			                                            <option value="" <?php if($Journal_DK == "") { ?> selected <?php } ?>>--</option>
			                                            <option value="D" <?php if($Journal_DK == "D") { ?> selected <?php } ?>>D</option>
			                                            <option value="K" <?php if($Journal_DK == "K") { ?> selected <?php } ?>>K</option>
			                                        </select>
			                                    </td>
												<td width="6%" nowrap style="text-align:center; display:none">
			                                    	<select name="data[<?php echo $currentRow; ?>][isTax]" id="data<?php echo $currentRow; ?>isTax" class="form-control" style="max-width:100px" onChange="countAmount()">
			                                            <option value="" <?php if($isTax == "") { ?> selected <?php } ?>>--</option>
			                                            <option value="0" <?php if($isTax == 0) { ?> selected <?php } ?>>No</option>
			                                            <option value="1" <?php if($isTax == 1) { ?> selected <?php } ?>>Yes</option>
			                                        </select>
			                                    </td>
											  	<td style="text-align:center; vertical-align: middle;">
											  		<div id="div_unit_<?php echo $currentRow; ?>">
				                                    	<?php echo $ITM_UNIT; ?>
				                                    </div>
				                                    <input type="hidden" name="data<?php echo $currentRow; ?>ITM_UNIT" id="ITM_UNIT<?php echo $currentRow; ?>" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:150px;" readonly >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:150px;" >
			                                    </td>
											  	<td style="text-align:right; vertical-align: middle;">
											 		<?php if ($edited == 1) { ?>
											 			<input type="text" class="form-control" style="text-align:right;min-width:80px;" name="ITM_VOLM<?php echo $currentRow; ?>" id="ITM_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_VOLM, 2); ?>" placeholder="Volume" onBlur="chgVolume(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);">
											 		<?php } else { ?>
											 			<?php echo number_format($ITM_VOLM, 2); ?>
											 			<input type="hidden" class="form-control" style="text-align:right" name="ITM_VOLM<?php echo $currentRow; ?>" id="ITM_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_VOLM, 2); ?>" placeholder="Volume" onBlur="chgVolume(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);">
											 		<?php } ?>
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_VOLM]" id="data<?php echo $currentRow; ?>ITM_VOLM" value="<?php echo $ITM_VOLM; ?>" class="form-control" style="max-width:150px;" >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="max-width:150px;" >
			                                        <input type="hidden" name="data<?php echo $currentRow; ?>ITM_REM" id="data<?php echo $currentRow; ?>ITM_REM" value="<?php echo $BUDG_REMVOLM; ?>" class="form-control" style="max-width:150px;" >
			                                        <input type="hidden" name="data<?php echo $currentRow; ?>ITM_REMAMN" id="data<?php echo $currentRow; ?>ITM_REMAMN" value="<?php echo $BUDG_REMAMN; ?>" class="form-control" style="max-width:150px;" >
			                                    </td>
											  	<td style="text-align:right; vertical-align: middle;">
											 		<?php if ($edited == 1) { ?>
											  			<input type="text" class="form-control" style="text-align:right;min-width:100px;" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, 2); ?>" placeholder="<?php echo $Price; ?>" onBlur="chgPrice(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);">
											 		<?php } else { ?>
											 			<?php echo number_format($ITM_PRICE, 2); ?>
											  			<input type="hidden" class="form-control" style="text-align:right" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, 2); ?>" placeholder="<?php echo $Price; ?>" onBlur="chgPrice(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);">
											 		<?php } ?>
											  		<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="max-width:150px;">
			                                    </td>
											  	<td style="text-align:right; vertical-align: middle;">
											 		<?php if ($edited == 1) { ?>
											 			<input type="text" class="form-control" style="text-align:right;min-width:120px;" name="JournalD_Amount<?php echo $currentRow; ?>" id="JournalD_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($AmountV, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);">
											 		<?php } else { ?>
											 			<?php echo number_format($AmountV, 2); ?>
											 			<input type="hidden" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $currentRow; ?>" id="JournalD_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($AmountV, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);">
											 		<?php } ?>
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Amount]" id="data<?php echo $currentRow; ?>JournalD_Amount" value="<?php echo $AmountV; ?>" class="form-control" style="max-width:150px;" >
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][isDirect]" id="data<?php echo $currentRow; ?>isDirect" value="1" class="form-control" style="max-width:150px;" >
			                                    </td>
			                                    <td style="text-align:right; vertical-align: middle; <?php if($disButtonR == 1) { ?> background-color:#F00<?php } ?>">
											 		<?php if ($edited == 1) { ?>
											 			<input type="text" class="form-control" style="text-align:right;min-width:120px;" name="JournalD_AmountR<?php echo $currentRow; ?>" id="JournalD_AmountR<?php echo $currentRow; ?>" value="<?php echo number_format($BUDG_REMAMNT, 2); ?>" placeholder="<?php echo $Remain; ?>" onKeyPress="return isIntOnlyNew(event);" disabled>
											 		<?php } else { ?>
											 			<?php echo number_format($BUDG_REMAMNT, 2); ?>
											 			<input type="hidden" class="form-control" style="text-align:right" name="JournalD_AmountR<?php echo $currentRow; ?>" id="JournalD_AmountR<?php echo $currentRow; ?>" value="<?php echo number_format($BUDG_REMAMNT, 2); ?>" placeholder="<?php echo $Remain; ?>" onKeyPress="return isIntOnlyNew(event);" disabled>
											 		<?php } ?>
			                                    </td>
											  	<td nowrap style="text-align:right; vertical-align: middle;">
			                                     	<?php if($edited == 0) { ?>
			                                     		<?php //echo number_format($PPN_Amount, $decFormat); ?>
				                                        <select name="data[<?php echo $currentRow; ?>][PPN_Code]" id="PPN_Code<?php echo $currentRow; ?>" class="form-control" style="max-width:150px; display: none;" onChange="getPPN(this.value,<?php echo $currentRow; ?>);">
				                                        	<option value=""> --- </option>
				                                        	<?php
				                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_ppn";
				                                        		$r_01 	= $this->db->query($s_01)->result();
				                                        		foreach($r_01 as $rw_01):
				                                        			$PPN_NUM 	= $rw_01->TAXLA_NUM;
				                                        			$PPN_DESC = $rw_01->TAXLA_DESC;
				                                        			?>
				                                        				<option value="<?=$PPN_NUM?>" <?php if($PPN_Code == $PPN_NUM) { ?> selected <?php } ?>><?=$PPN_DESC?></option>
				                                        			<?php
				                                        		endforeach;
				                                        	?>
				                                        </select>
			                                     		<?php
			                                     			$PPNDESC= "-";
			                                        		$s_01a 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_ppn WHERE TAXLA_NUM = '$PPN_Code'";
			                                        		$r_01a 	= $this->db->query($s_01a)->result();
			                                        		foreach($r_01a as $rw_01a):
			                                        			$PPNDESC = $rw_01a->TAXLA_DESC;
			                                        		endforeach;
			                                     			echo $PPNDESC;
			                                     		?>
				                                    <?php } else { ?>
				                                        <select name="data[<?php echo $currentRow; ?>][PPN_Code]" id="PPN_Code<?php echo $currentRow; ?>" class="form-control" style="min-width: 80px; max-width:150px" onChange="getPPN(this.value,<?php echo $currentRow; ?>);">
				                                        	<option value=""> --- </option>
				                                        	<?php
				                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_ppn";
				                                        		$r_01 	= $this->db->query($s_01)->result();
				                                        		foreach($r_01 as $rw_01):
				                                        			$PPN_NUM 	= $rw_01->TAXLA_NUM;
				                                        			$PPN_DESC = $rw_01->TAXLA_DESC;
				                                        			?>
				                                        				<option value="<?=$PPN_NUM?>" <?php if($PPN_Code == $PPN_NUM) { ?> selected <?php } ?>><?=$PPN_DESC?></option>
				                                        			<?php
				                                        		endforeach;
				                                        	?>
				                                        </select>
				                                    <?php } ?>
			                                    </td>
			                                    <td style="text-align:right; vertical-align: middle;">
											 		<?php if ($edited == 1) { ?>
											 			<input type="text" class="form-control" style="text-align:right;min-width:100px" name="PPN_AmountX<?php echo $currentRow; ?>" id="PPN_AmountX<?php echo $currentRow; ?>" value="<?php echo number_format($PPN_Amount, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPPN(this,<?php echo $currentRow; ?>)">
											 		<?php } else { ?>
											 			<?php echo number_format($PPN_Amount, 2); ?>
											 			<input type="hidden" class="form-control" style="text-align:right" name="PPN_AmountX<?php echo $currentRow; ?>" id="PPN_AmountX<?php echo $currentRow; ?>" value="<?php echo number_format($PPN_Amount, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPPN(this,<?php echo $currentRow; ?>)">
											 		<?php } ?>
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PPN_Perc]" id="data<?php echo $currentRow; ?>PPN_Perc" size="20" value="<?php echo $PPN_Perc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PPN_Amount]" id="data<?php echo $currentRow; ?>PPN_Amount" size="20" value="<?php echo $PPN_Amount; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                    </td>
			                                    <td nowrap style="text-align:right; vertical-align: middle;">
			                                     	<?php if($edited == 0) { ?>
			                                     		<?php //echo number_format($PPH_Amount, $decFormat); ?>
					                                    <select name="data[<?php echo $currentRow; ?>][PPH_Code]" id="PPH_Code<?php echo $currentRow; ?>" class="form-control" style="max-width:150px; display: none;" onChange="getPPH(this.value,<?php echo $currentRow; ?>);">
				                                        	<option value=""> --- </option>
				                                        	<?php
				                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_la";
				                                        		$r_01 	= $this->db->query($s_01)->result();
				                                        		foreach($r_01 as $rw_01):
				                                        			$PPH_NUM 	= $rw_01->TAXLA_NUM;
				                                        			$PPH_DESC = $rw_01->TAXLA_DESC;
				                                        			?>
				                                        				<option value="<?=$PPH_NUM?>" <?php if($PPH_Code == $PPH_NUM) { ?> selected <?php } ?>><?=$PPH_DESC?></option>
				                                        			<?php
				                                        		endforeach;
				                                        	?>
				                                        </select>
			                                     		<?php
			                                     			$PPNDESC= "-";
			                                        		$s_01b 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_la WHERE TAXLA_NUM = '$PPH_Code'";
			                                        		$r_01b 	= $this->db->query($s_01b)->result();
			                                        		foreach($r_01b as $rw_01b):
			                                        			$PPNDESC = $rw_01b->TAXLA_DESC;
			                                        		endforeach;
			                                     			echo $PPNDESC;
			                                     		?>
				                                    <?php } else { ?>
					                                    <select name="data[<?php echo $currentRow; ?>][PPH_Code]" id="PPH_Code<?php echo $currentRow; ?>" class="form-control" style="min-width: 80px; max-width:150px" onChange="getPPH(this.value,<?php echo $currentRow; ?>);">
				                                        	<option value=""> --- </option>
				                                        	<?php
				                                        		$s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_la";
				                                        		$r_01 	= $this->db->query($s_01)->result();
				                                        		foreach($r_01 as $rw_01):
				                                        			$PPH_NUM 	= $rw_01->TAXLA_NUM;
				                                        			$PPH_DESC = $rw_01->TAXLA_DESC;
				                                        			?>
				                                        				<option value="<?=$PPH_NUM?>" <?php if($PPH_Code == $PPH_NUM) { ?> selected <?php } ?>><?=$PPH_DESC?></option>
				                                        			<?php
				                                        		endforeach;
				                                        	?>
				                                        </select>
				                                    <?php } ?>
			                                    </td>
			                                    <td style="text-align:right; vertical-align: middle;">
											 		<?php if ($edited == 1) { ?>
											 			<input type="text" class="form-control" style="text-align:right;min-width:100px" name="PPH_AmountX<?php echo $currentRow; ?>" id="PPH_AmountX<?php echo $currentRow; ?>" value="<?php echo number_format($PPH_Amount, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPPH(this,<?php echo $currentRow; ?>)">
											 		<?php } else { ?>
											 			<?php echo number_format($PPH_Amount, 2); ?>
											 			<input type="hidden" class="form-control" style="text-align:right" name="PPH_AmountX<?php echo $currentRow; ?>" id="PPH_AmountX<?php echo $currentRow; ?>" value="<?php echo number_format($PPH_Amount, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPPH(this,<?php echo $currentRow; ?>)">
											 		<?php } ?>
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PPH_Perc]" id="data<?php echo $currentRow; ?>PPH_Perc" size="20" value="<?php echo $PPH_Perc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PPH_Amount]" id="data<?php echo $currentRow; ?>PPH_Amount" size="20" value="<?php echo $PPH_Amount; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
			                                    </td>
											  	<td style="text-align:center; vertical-align: middle;" nowrap>
											 		<?php if ($edited == 1) { ?>
											 			<input type="text" name="data[<?php echo $currentRow; ?>][TAX_DATE]" id="data<?php echo $currentRow; ?>TAX_DATE" value="<?php echo $TAX_DATE; ?>" class="form-control" style="min-width:100px;max-width:150px;" placeholder="YYYY-MM-DD" title="Tgl. Faktur Pajak" >
											 		<?php } else { ?>
											 			<?php echo $TAX_DATE; ?>
											 			<input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_DATE]" id="data<?php echo $currentRow; ?>TAX_DATE" value="<?php echo $TAX_DATE; ?>" class="form-control" style="min-width:100px;max-width:150px;" placeholder="Tgl. Faktur Pajak" >
											 		<?php } ?>
			                                    </td>
											  	<td style="text-align:center; vertical-align: middle;">
											 		<?php if ($edited == 1) { ?>
											 			<input type="text" name="data[<?php echo $currentRow; ?>][TAX_NO]" id="data<?php echo $currentRow; ?>TAX_NO" value="<?php echo $TAX_NO; ?>" class="form-control" style="min-width:150px;max-width:300px;" placeholder="No. Faktur Pajak" title="No. Faktur Pajak" >
											 		<?php } else { ?>
											 			<?php echo $TAX_NO; ?>
											 			<input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_NO]" id="data<?php echo $currentRow; ?>TAX_NO" value="<?php echo $TAX_NO; ?>" class="form-control" style="min-width:150px;max-width:300px;" placeholder="No. Faktur Pajak" >
											 		<?php } ?>
			                                    </td>
											  	<td style="text-align:left; vertical-align: middle;">
											 		<?php if ($edited == 1) { ?>
											 			<input type="text" name="data[<?php echo $currentRow; ?>][Other_Desc]" id="data<?php echo $currentRow; ?>Other_Desc" size="20" value="<?php echo $Other_Desc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
											 		<?php } else { ?>
											 			<?php echo $Other_Desc; ?>
											 			<input type="hidden" name="data[<?php echo $currentRow; ?>][Other_Desc]" id="data<?php echo $currentRow; ?>Other_Desc" size="20" value="<?php echo $Other_Desc; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">
											 		<?php } ?>
			                                    </td>
											  	<td style="text-align:left; vertical-align: middle;">
											 		<input type="hidden" name="data[<?php echo $currentRow; ?>][isExtra]" id="data<?php echo $currentRow; ?>isExtra" value="<?php echo $isExtra; ?>" class="form-control" style="min-width: 100px">
											 		<?php if ($edited == 1) { ?>
											 			<input type="checkbox" id="chkExtra<?php echo $currentRow; ?>" onClick="chkExtra(this, <?php echo $currentRow; ?>)" title="Centang jika ingin memasukan nilai lebih besar dari PD" <?php if($isExtra == 1) { ?> checked <?php } ?>>
											 		<?php } else { ?>
											 			<input type="checkbox" id="chkExtra<?php echo $currentRow; ?>" onClick="chkExtra(this, <?php echo $currentRow; ?>)" title="Centang jika ingin memasukan nilai lebih besar dari PD" <?php if($isExtra == 1) { ?> checked <?php } ?> disabled>
											 		<?php } ?>
											 		<input type="hidden" name="data[<?php echo $currentRow; ?>][isVerified]" id="data<?php echo $currentRow; ?>isVerified" value="<?php echo $isVerified; ?>" class="form-control" style="min-width:150px;max-width:300px;" >
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
                    </div>

                    <div class="col-md-6">
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-3 control-label"><?php //echo $Project; ?></label>
		                    <div class="col-sm-9">
		                        <?php
									/*if($ISAPPROVE == 1)
										$ISCREATE = 1;*/

										if($ISCREATE == 1 || $ISAPPROVE == 1)
										{
											if ($GEJ_STAT_PD == 0 || $GEJ_STAT_PD == 1 || $GEJ_STAT_PD == 2 || $GEJ_STAT_PD == 4):
											?>
												<button class="btn btn-primary" id="btnSave" style="display: none;">
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
											endif;
										}

										?>
											<a class="btn btn-warning" id="btnPrint" onclick="printD();">
											<i class="fa fa-print"></i>
											</a>&nbsp;
										<?php
									
									//$backURL	= site_url('c_finance/c_cho70d18/cp2b0d18_all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
                    </div>
	            </div>
			</form>

	    	<!-- ============ START MODAL ITEM LIST =============== -->
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
		        <div class="modal fade" id="mdl_selITM" name='mdl_selITM' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?php echo $ItmList; ?></a>
						                    </li>
						                    <li id="li2" <?php echo $Active2Cls; ?>>
						                    	<a href="#itm2" data-toggle="tab" onClick="setType(2)">Non-Item</a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
														<div class="search-table-outter">
				                                            <table id="example0" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
				                                                <thead>
				                                                    <tr>
				                                                        <th rowspan="2" width="2%" style="text-align:center">&nbsp;</th>
				                                                        <th rowspan="2" width="5%" style="text-align:center; vertical-align:middle"><?php echo $Account; ?></th>
				                                                        <th rowspan="2" width="43%" style="text-align:center; vertical-align:middle"><?php echo $Description; ?> </th>
				                                                        <th rowspan="2" width="5%" style="text-align:center; vertical-align:middle">Unit</th>
				                                                        <th colspan="2" width="15%" style="text-align:center; vertical-align:middle"><?php echo $Budget; ?></th>
				                                                        <th colspan="2" width="15%" style="text-align:center; vertical-align:middle"><?php echo $Used; ?></th>
				                                                        <th colspan="2" width="15%" style="text-align:center; vertical-align:middle"><?php echo $Remain; ?></th>
												                  	</tr>
				                                                    <tr>
				                                                        <th style="text-align:center">Vol.</th>
				                                                        <th style="text-align:center; vertical-align:middle">Jumlah</th>
				                                                        <th style="text-align:center">Vol.</th>
				                                                        <th style="text-align:center; vertical-align:middle">Jumlah</th>
				                                                        <th style="text-align:center">Vol.</th>
				                                                        <th style="text-align:center; vertical-align:middle">Jumlah</th>
												                  	</tr>
				                                                </thead>
				                                                <tbody>
				                                                </tbody>
				                                            </table>
				                                        </div>
				                                        <br>
												        <div class="row">
															<div class="col-md-12" id="idprogbar0" style="display: none;">
																<div class="cssProgress">
															      	<div class="cssProgress">
																	    <div class="progress3">
																			<div id="progressbarXX0" style="text-align: center;">0%</div>
																		</div>
																		<span class="cssProgress-label" id="information" ></span>
																	</div>
															    </div>
															</div>
														</div>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
                                      					<button class="btn btn-warning" type="button" id="idRefresh0" >
                                                    		<i class="glyphicon glyphicon-refresh"></i>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>
							            	<div class="<?php echo $Active2; ?> tab-pane" id="itm2" style="display: none;">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch" id="frmSearch" action="">
														<div class="search-table-outter">
				                                            <table id="itmList_nonM" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
				                                                <thead>
				                                                    <tr>
				                                                        <th rowspan="2" width="2%" style="text-align:center">&nbsp;</th>
				                                                        <th rowspan="2" width="5%" style="text-align:center; vertical-align:middle"><?php echo $Account; ?></th>
				                                                        <th rowspan="2" width="43%" style="text-align:center; vertical-align:middle"><?php echo $Description; ?> </th>
				                                                        <th rowspan="2" width="5%" style="text-align:center; vertical-align:middle">Unit</th>
				                                                        <th colspan="2" width="15%" style="text-align:center; vertical-align:middle"><?php echo $Budget; ?></th>
				                                                        <th colspan="2" width="15%" style="text-align:center; vertical-align:middle"><?php echo $Used; ?></th>
				                                                        <th colspan="2" width="15%" style="text-align:center; vertical-align:middle"><?php echo $Remain; ?></th>
												                  	</tr>
				                                                    <tr>
				                                                        <th style="text-align:center">Vol.</th>
				                                                        <th style="text-align:center; vertical-align:middle">Jumlah</th>
				                                                        <th style="text-align:center">Vol.</th>
				                                                        <th style="text-align:center; vertical-align:middle">Jumlah</th>
				                                                        <th style="text-align:center">Vol.</th>
				                                                        <th style="text-align:center; vertical-align:middle">Jumlah</th>
												                  	</tr>
				                                                </thead>
				                                                <tbody>
				                                                </tbody>
				                                            </table>
				                                        </div>
				                                        <br>
												        <div class="row">
															<div class="col-md-12" id="idprogbar2" style="display: none;">
																<div class="cssProgress">
															      	<div class="cssProgress">
																	    <div class="progress3">
																			<div id="progressbarXX2" style="text-align: center;">0%</div>
																		</div>
																		<span class="cssProgress-label" id="information" ></span>
																	</div>
															    </div>
															</div>
														</div>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail2" name="btnDetail2">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose2" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
                                      					<button class="btn btn-warning" type="button" id="idRefresh2" >
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
	            		<div class="alert alert-info alert-dismissible">
	                        <?php echo "PERHATIKAN! Daftar item yang ditampilkan hanyalah item yang bukan berkategori Material (M) dan Alat (T), serta sudah disetting Akun Penggunaan di Daftar Material."; ?>
	                    </div>
				    </div>
				</div>

				<script type="text/javascript">

					function setType(tabType)
					{
						var theRow 		= $("#selRow").val();
						if(tabType == 1)
						{
							document.getElementById('itm1').style.display	= '';
							document.getElementById('itm2').style.display	= 'none';

							$('#example0').DataTable(
					    	{
					    		"dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>"+
										"<'row'<'col-sm-12'tr>>",
					    		"ordering": false,
								"bDestroy": true,
						        "processing": true,
						        "serverSide": true,
								//"scrollX": false,
								"autoWidth": true,
								"filter": true,
						        //"ajax": "<?php echo site_url('c_finance/c_r_pd/get_AllDataITM/?id='.$PRJCODE.'&THEROW=')?>"+theRow,
						        "ajax": "<?php echo site_url('c_finance/c_cho70d18/get_AllDataITM/?id='.$PRJCODE.'&THEROW=')?>"+theRow,
						        "type": "POST",
								"lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
								// "lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
								"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
												{ targets: [,4,5,6,7,8,9], className: 'dt-body-right' }
											  ],
								"language": {
						            "infoFiltered":"",
						            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
						        },
							});
						}
						else if(tabType == 2)
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= '';

							$('#itmList_nonM').DataTable(
					    	{
					    		"ordering": false,
								"bDestroy": true,
						        "processing": true,
						        "serverSide": true,
								//"scrollX": false,
								"autoWidth": true,
								"filter": true,
						        "ajax": "<?php echo site_url('c_finance/c_r_pd/get_AllDataITM_NON/?id='.$PRJCODE.'&THEROW=')?>"+theRow,
						        "type": "POST",
								//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
								"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
								"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
												{ targets: [,4,5,6,7,8,9], className: 'dt-body-right' }
											  ],
								"language": {
						            "infoFiltered":"",
						            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
						        },
							});
						}
					}

					var selectedRows = 0;
					function pickThis0(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk0']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck0").val(favorite.length);
					}

					function pickThis2(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk2']:checked"), function() {
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
						      	add_item($(this).val());
						    });

						    $('#mdl_selITM').on('hidden.bs.modal', function () {
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

					   	$("#btnDetail2").click(function()
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

						    $.each($("input[name='chk2']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_selITM').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose2").click()
					    });
					    
					   	$("#idRefresh").click(function()
					    {
							$('#example0').DataTable().ajax.reload();
					    });
					    
					   	$("#idRefresh2").click(function()
					    {
							$('#itmList_nonM').DataTable().ajax.reload();
					    });
					});
				</script>
	    	<!-- ============ END MODAL ITEM LIST =============== -->

			<?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>
<?php
	$AddItm 	= base_url().'index.php/c_finance/c_cho70d18/addItmTmp/?id=';
	$chgVOL 	= base_url().'index.php/c_finance/c_cho70d18/chgVOLUME/?id=';
	$secGetPPn	= base_url().'index.php/c_project/c_s180d0bpk/getPPN/'; 	// Generate Code
	$secGetPPh	= base_url().'index.php/c_project/c_s180d0bpk/getPPH/'; 	// Generate Code
	$chkCode 	= base_url().'index.php/c_finance/c_cho70d18/chkCode/?id=';
	$delRVCASH 	= base_url().'index.php/c_finance/c_cho70d18/delRVCASH/?id=';
?>
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
	      	autoclose: true
	    });

	    //Date picker
	    $('#datepicker1').datepicker({
	        format: 'dd/mm/yyyy',
	        autoclose: true,
			startDate: '-3d',
			endDate: '+1d'
	    });

	    //Date picker
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

		$('#isCheckClose').on('ifChanged', function(event) {
			let totRow = $('#totalrow').val();
			if(event.target.checked == true)
			{
				$('#addAcc').prop('disabled', true);
				$("#isManualClose_1").iCheck('check');
				$("input[name='isManualClose']").iCheck('enable');
				if(totRow != 0)
				{
					for(let i=1;i<=totRow;i++) {
						$("#tr_"+i).remove();
					}
				}
			}
			else
			{
				$('#addAcc').prop('disabled', false);
				$("input[name='isManualClose']").iCheck('uncheck');
				$("input[name='isManualClose']").iCheck('disable');
				$('#Close_Notes').prop('disabled', false);
			}
		});

		$("input[name='isManualClose']").on("ifChecked", function(e){
			if(e.target.checked == true)
			{
				if(e.target.value == 2) $('#Close_Notes').prop('disabled', false);
				else $('#Close_Notes').prop('disabled', false);
			}
		})
  	});

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
                	var app_stat 	= document.getElementById('app_stat').value;

                	if(arrStat == 1 && app_stat == 0)
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
                	else if(arrStat == 1 && app_stat == 1)
                	{
                		$('#app_stat').val(arrStat);
                		document.getElementById('btnSave').style.display 	= 'none';
                		document.getElementById('divAlert').style.display 	= '';
                	}
                	else
                	{
                		$('#app_stat').val(arrStat);
                		document.getElementById('btnSave').style.display 	= '';
                		document.getElementById('divAlert').style.display 	= 'none';
                	}
                }
            });
		}
	// END : LOCK PROCEDURE

	function recountItm(row)
	{
		var collID  = document.getElementById('urlRec'+row).value;
		myarr 		= collID.split('~');
    	url			= myarr[0];

		document.getElementById('btnDetail0').style.display 		= 'none';
		document.getElementById('idClose0').style.display 			= 'none';
		document.getElementById('idRefresh0').style.display 		= 'none';

		document.getElementById('idprogbar0').style.display 		= '';
	    document.getElementById("progressbarXX0").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Please wait, we are processing synchronization ...</span></div>";

        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
                swal(response, 
                {
                    icon: "success",
                })
                .then(function()
                {
                	swal.close();
                	//location.reload();
                	$('#example0').DataTable().ajax.reload();

					document.getElementById('btnDetail0').style.display 		= '';
					document.getElementById('idClose0').style.display 			= '';
					document.getElementById('idRefresh0').style.display 		= '';

					document.getElementById('idprogbar0').style.display 		= 'none';
				    document.getElementById("progressbarXX0").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Please wait, we are processing synchronization ...</span></div>";
                })
            }
        });
	}

	function printD()
	{
		let url = '<?php echo $secPrint; ?>';
		// var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
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
		let totalrow = document.getElementById('totalrow').value;
		let row = document.getElementById("tr_" + btn);
		let countRow = parseInt(totalrow) - 1;
		document.getElementById('totalrow').value = countRow;
		row.remove();
	}
	
	function add_listAcc(strItem) 
	{
		var acc_number		= document.getElementById('acc_number').value;
		/* ----------------- hidden date 10.06.2022 ---------
		if(acc_number == '')
		{
			swal('<?php // echo $alert11; ?>',
			{
				icon: "warning",
			});
			document.getElementById('acc_number').focus();
			return false;;
		}
		------------------- end hidden ----------------------- */
		
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
			Base_PPn			= 0;
			Base_PPh			= 0;
			TAX_DATE 			= '';
			TAX_NO				= '';
			JournalD_Pos		= 'D';
			isVerified 			= 1;
			
		// TABLE COMPONENT
			objTable 		= document.getElementById('tbl');
			intTable 		= objTable.rows.length;
			//swal('intTable = '+intTable)
			//intIndex = parseInt(document.frm.rowCount.value) + 1;
			intIndex = parseInt(objTable.rows.length);
			//intIndex = intTable;
			document.frm.rowCount.value = intIndex;
			
			objTR = objTable.insertRow(intTable);
			objTR.id = 'tr_' + intIndex;
		
		// Account Icon
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">&nbsp;<a href="#" onClick="selectAccount('+intIndex+')" title="<?php echo $SelectItem; ?>" class="btn btn-success btn-xs"><i class="fa fa-book"></i></a>';
		
		// Account Number
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][JournalH_Code]" id="data'+intIndex+'JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][Acc_Id]" id="data'+intIndex+'Acc_Id" value="'+Acc_Id+'" class="inplabel"><input type="hidden" name="data['+intIndex+'][proj_Code]" id="data'+intIndex+'proj_Code" value="<?php echo $proj_Code; ?>" class="form-control" style="max-width:150px;" ><div id="AccID'+intIndex+'"></div>';
		
		// Account Description
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.style.display = 'none';
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
		
		// Item Unit
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = '<div id="div_unit_'+intIndex+'"></div><input type="hidden" name="data'+intIndex+'ITM_UNIT" id="ITM_UNIT'+intIndex+'" value="'+ITM_UNIT+'" class="form-control" style="min-width:50px;" readonly><input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:150px;" >';
		
		// Volume
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right;min-width:80px" name="ITM_VOLM'+intIndex+'" id="ITM_VOLM'+intIndex+'" value="'+ITM_VOLM+'" placeholder="Volume" onBlur="chgVolume(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][ITM_VOLM]" id="data'+intIndex+'ITM_VOLM" value="'+ITM_VOLM+'" class="form-control" style="max-width:150px;"><input type="hidden" name="data'+intIndex+'ITM_REM" id="data'+intIndex+'ITM_REM" value="0" class="form-control" style="max-width:150px;" ><input type="hidden" name="data'+intIndex+'ITM_REMAMN" id="data'+intIndex+'ITM_REMAMN" value="0" class="form-control" style="max-width:150px;" >'; 
		
		// Price
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right;min-width:100px" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+ITM_PRICE+'" placeholder="<?php echo $Price; ?>" onBlur="chgPrice(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:150px;">'; 
		
		// Total Amount
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right;min-width:100px" name="JournalD_Amount'+intIndex+'" id="JournalD_Amount'+intIndex+'" value="'+JournalD_Debet+'" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][JournalD_Amount]" id="data'+intIndex+'JournalD_Amount" value="'+JournalD_Debet+'" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][isDirect]" id="data'+intIndex+'isDirect" value="1" class="form-control" style="max-width:150px;" >';
		
		// Remain Amount
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right;min-width:100px" name="JournalD_AmountR'+intIndex+'" id="JournalD_AmountR'+intIndex+'" value="0.00" placeholder="<?php echo $Remain; ?>" onKeyPress="return isIntOnlyNew(event);" disabled>';

		// Tax PPN -- PPN_Code
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<select name="data['+intIndex+'][PPN_Code]" id="PPN_Code'+intIndex+'" class="form-control" style="min-width: 80px; max-width:150px" onChange="getPPN(this.value,'+intIndex+');"><option value=""> --- </option><?php $s_01 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_ppn"; $r_01 	= $this->db->query($s_01)->result(); foreach($r_01 as $rw_01): $PPN_NUM 	= $rw_01->TAXLA_NUM; $PPN_DESC = $rw_01->TAXLA_DESC; ?> <option value="<?php echo $PPN_NUM?>"><?php echo $PPN_DESC?></option> <?php endforeach; ?></select>';
		
		// PPN_Amount
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right;min-width:100px" name="PPN_AmountX'+intIndex+'" id="PPN_AmountX'+intIndex+'" value="0.00" onBlur="chgPPN(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][PPN_Perc]" id="data'+intIndex+'PPN_Perc"  value="0" class="form-control"><input type="hidden" name="data['+intIndex+'][PPN_Amount]" id="data'+intIndex+'PPN_Amount" size="20" value="0" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		// Tax PPH -- PPH_Code, 
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<select name="data['+intIndex+'][PPH_Code]" id="PPH_Code'+intIndex+'" class="form-control" style="min-width: 80px; max-width:150px" onChange="getPPH(this.value,'+intIndex+');"><option value=""> --- </option><?php $s_02 	= "SELECT TAXLA_NUM, TAXLA_DESC FROM tbl_tax_la"; $r_02 	= $this->db->query($s_02)->result(); foreach($r_02 as $rw_02): $PPH_NUM 	= $rw_02->TAXLA_NUM; $PPH_DESC = $rw_02->TAXLA_DESC; ?> <option value="<?php echo $PPH_NUM?>"><?php echo $PPH_DESC?></option> <?php endforeach; ?></select>';
		
		// PPH_Amount
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right;min-width:100px" name="PPH_AmountX'+intIndex+'" id="PPH_AmountX'+intIndex+'" value="0.00" onBlur="chgPPH(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][PPH_Perc]" id="data'+intIndex+'PPH_Perc"  value="0" class="form-control"><input type="hidden" name="data['+intIndex+'][PPH_Amount]" id="data'+intIndex+'PPH_Amount" size="20" value="0" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';
		
		// Account Reference
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			//objTD.style.display = 'none';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][TAX_DATE]" id="data'+intIndex+'TAX_DATE" value="'+TAX_DATE+'" class="form-control" style="min-width:100px;max-width:150px;" placeholder="YYYY-MM-DD" title="Tgl. Faktur Pajak" >';
		
		// Account Reference
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			//objTD.style.display = 'none';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][TAX_NO]" id="data'+intIndex+'TAX_NO" value="'+TAX_NO+'" class="form-control" style="min-width:150px;max-width:300px;" placeholder="No. Seri Pajak" title="No. Seri Pajak" >';
		
		// Remarks
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Other_Desc]" id="data'+intIndex+'Other_Desc" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left" placeholder="<?php echo $Remarks; ?>">';
		
		// STATUS : Digunakan untuk menunjukan apakah kelebihan penggunaan atau tidak. Hijau (Tidak), Merah (Lebih)
		// Sehingga, jika ada yang kelebihan penggunaan uang (versi lapangan), maka akan membentuk Piutang Karyawan yang hrs dibayar perusahaan
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][isExtra]" id="data'+intIndex+'isExtra" value="0" class="form-control" style="min-width: 100px"><input type="checkbox" id="chkExtra'+intIndex+'" onClick="chkExtra(this, '+intIndex+')" title="Centang jika ingin memasukan nilai lebih besar dari PD"><input type="hidden" name="data['+intIndex+'][isVerified]" id="data'+intIndex+'isVerified" value="'+isVerified+'" class="form-control" style="min-width:150px;max-width:300px;" >';
		
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
		JRN_NUM 		= document.getElementById('JournalH_Code').value;
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
		ISVERIFY 		= arrItem[10];
		PRJCODE 		= "<?=$PRJCODE?>";

		// START : ADD ITEM TEMPORARY
			collDt 			= strItem+'|'+JRN_NUM+'|'+PRJCODE;

			url 			= "<?=$AddItm?>";
			$.ajax({
	            type: 'POST',
	            url: url,
	            data: {collDt: collDt},
	            success: function(response)
	            {
	            	//swal(response)
	            }
	        });
		// END : ADD ITEM TEMPORARY

        var itmDesc 	= "<strong>"+Acc_Id+"</strong><br><span class='text-muted' style='margin-left: 18px'>"+Item_Code+' : '+JournalD_Desc+"</span>";

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
		document.getElementById('AccID'+theRow).innerHTML				= itmDesc;
		document.getElementById('data'+theRow+'isVerified').value		= ISVERIFY;
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

	function getPPN(TAX_NUM, row)
	{
		var decFormat	= document.getElementById('decFormat').value;

		if(TAX_NUM == '')
		{
			//ITM_VOLM		= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);
			//ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
			PPH_VAL			= parseFloat(document.getElementById('data'+row+'PPH_Amount').value);

			//ITM_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);
			ITM_TOTAL 		= parseFloat(document.getElementById('data'+row+'JournalD_Amount').value);
			PPN_VAL 		= parseFloat(0);

			document.getElementById('data'+row+'PPN_Perc').value	= parseFloat(0);
			document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(0);
			document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

			//ITMTOTAL 		= parseFloat(ITM_TOTAL + PPN_VAL - PPH_VAL);
			//document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITMTOTAL)), 2);
			countAmount();
		}
		else
		{
			//console.log('cc')
			var url			= "<?php echo $secGetPPn; ?>";
			$.ajax({
				type: 'POST',
				url: url,
				data: {TAX_NUM:TAX_NUM},
				success: function(response)
				{
					//ITM_VOLM		= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);
					//ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
					PPH_VAL			= parseFloat(document.getElementById('data'+row+'PPH_Amount').value);

					//ITM_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);
					ITM_TOTAL 		= parseFloat(document.getElementById('data'+row+'JournalD_Amount').value);
					PPN_VAL 		= parseFloat(ITM_TOTAL) * parseFloat(response) / 100;

					document.getElementById('data'+row+'PPN_Perc').value	= parseFloat(response);
					document.getElementById('data'+row+'PPN_Amount').value	= PPN_VAL;
					document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_VAL)), 2));

					//ITMTOTAL 		= parseFloat(ITM_TOTAL + PPN_VAL - PPH_VAL);
					//document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITMTOTAL)), 2);
					countAmount();
				}
			});
		}
	}

	function chgPPN(thisval, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var PPN_Amount		= parseFloat(eval(thisval).value.split(",").join(""));
		var TAX_NUM 		= document.getElementById('PPN_Code'+row).value;
		var ITM_TOTAL 		= parseFloat(document.getElementById('data'+row+'JournalD_Amount').value);

		if(TAX_NUM == '' && PPN_Amount != 0)
		{
			swal("Silahkan pilih PPN terlebih dahulu",
			{
				icon:"warning"
			});

			document.getElementById('data'+row+'PPN_Perc').value	= parseFloat(0);
			document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(0);
			document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

			countAmount();
		}
		else
		{
			document.getElementById('data'+row+'PPN_Amount').value	= PPN_Amount;
			document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_Amount)), 2));

			countAmount();
		}
	}

	function getPPH(TAX_NUM, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		if(TAX_NUM == '')
		{
			//ITM_VOLM		= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);
			//ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
			PPN_VAL			= parseFloat(document.getElementById('data'+row+'PPN_Amount').value);

			//ITM_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);
			ITM_TOTAL 		= parseFloat(document.getElementById('data'+row+'JournalD_Amount').value);
			PPH_VAL 		= parseFloat(0);


			document.getElementById('data'+row+'PPH_Perc').value	= parseFloat(0);
			document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(0);
			document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

			//ITMTOTAL 		= parseFloat(ITM_TOTAL + PPN_VAL - PPH_VAL);
			//document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITMTOTAL)), 2);
			
			countAmount();
		}
		else
		{
			var url			= "<?php echo $secGetPPh; ?>";
			$.ajax({
				type: 'POST',
				url: url,
				data: {TAX_NUM:TAX_NUM},
				success: function(response)
				{
					//ITM_VOLM		= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);
					//ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
					PPN_VAL			= parseFloat(document.getElementById('data'+row+'PPN_Amount').value);

					//ITM_TOTAL 	= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);
					ITM_TOTAL 		= parseFloat(document.getElementById('data'+row+'JournalD_Amount').value);
					PPH_VAL 		= parseFloat(ITM_TOTAL) * parseFloat(response) / 100;

					document.getElementById('data'+row+'PPH_Perc').value	= parseFloat(response);
					document.getElementById('data'+row+'PPH_Amount').value	= PPH_VAL;
					document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_VAL)), 2));

					//ITMTOTAL 		= parseFloat(ITM_TOTAL + PPN_VAL - PPH_VAL);
					//document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITMTOTAL)), 2);
					
					countAmount();
				}
			});
		}
	}

	function chgPPH(thisval, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var PPH_Amount		= parseFloat(eval(thisval).value.split(",").join(""));
		var TAX_NUM 		= document.getElementById('PPH_Code'+row).value;
		var ITM_TOTAL 		= parseFloat(document.getElementById('data'+row+'JournalD_Amount').value);

		if(TAX_NUM == '' && PPH_Amount != 0)
		{
			swal("Silahkan pilih PPH terlebih dahulu",
			{
				icon:"warning"
			});

			document.getElementById('data'+row+'PPH_Perc').value	= parseFloat(0);
			document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(0);
			document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

			countAmount();
		}
		else
		{
			document.getElementById('data'+row+'PPH_Amount').value	= PPH_Amount;
			document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_Amount)), 2));

			countAmount();
		}
	}
	
	function chgVolume(thisval, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_VOLM		= parseFloat(eval(thisval).value.split(",").join(""));

		var isVerified		= parseFloat(document.getElementById('data'+row+'isVerified').value);
		var ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
		var ITM_REMVOL		= parseFloat(document.getElementById('data'+row+'ITM_REM').value);
		var ITM_REMAMN		= parseFloat(document.getElementById('data'+row+'ITM_REMAMN').value);
		var PPN_Perc 		= parseFloat(document.getElementById('data'+row+'PPN_Perc').value);
		var PPH_Perc 		= parseFloat(document.getElementById('data'+row+'PPH_Perc').value);
		
		var ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		var ITM_UNIT 		= ITMUNIT.toUpperCase();

		if(isVerified == 1)
		{
			if(ITM_UNIT == 'LS' || ITM_UNIT == 'BLN' || ITM_UNIT == 'LOT')
			{
				var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

				if(ITM_REMAMN < 0)
				{
					swal('Budget sudah habis',
					{
						icon: "warning",
					})
					.then(function()
		            {
		                swal.close();
						document.getElementById('ITM_VOLM'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
						document.getElementById('data'+row+'ITM_VOLM').value 		= parseFloat(Math.abs(0));
						document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
						document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(0));
					});

			        countAmount();
				}
				else if(TOTPRC > ITM_REMAMN)
				{
					var ITM_REMVOLV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMVOL)), decFormat));

					swal('<?php echo $alert16; ?>'+ITM_REMVOLV,
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

						var PPN_Amount	= parseFloat(PPN_Perc/100) * parseFloat(TOTPRC);
						var PPH_Amount	= parseFloat(PPH_Perc/100) * parseFloat(TOTPRC);
						document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(PPN_Amount);
						document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_Amount)), 2));
						document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(PPH_Amount);
						document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_Amount)), 2));

						PRJCODE 	= "<?=$PRJCODE?>";
						JRN_NUM 	= "<?=$JournalH_Code?>";
						JOBCODEID 	= document.getElementById('data'+row+'JOBCODEID').value;
						ITMV 		= parseFloat(ITM_VOLM);
						ITMP 		= parseFloat(ITM_PRICE);
						strItem 	= PRJCODE+'|'+JRN_NUM+'|'+JOBCODEID+'|'+ITMV+'|'+ITMP+'|'+row;

						url 			= "<?=$chgVOL?>";
						$.ajax({
				            type: 'POST',
				            url: url,
				            data: {collDt: strItem},
				            success: function(response)
				            {
				            	arrAcc 		= response.split('~');
		                    	ITM_REM		= arrAcc[0];
		                    	ITM_REMAMN	= arrAcc[1];
								document.getElementById('data'+row+'ITM_REM').value 	= parseFloat(ITM_REM);
								document.getElementById('data'+row+'ITM_REMAMN').value 	= parseFloat(ITM_REMAMN);
								document.getElementById('JournalD_AmountR'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMAMN)),2));

								countAmount();
				            }
				        });
		            });
				}
				else
				{
					var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

					document.getElementById('ITM_VOLM'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), decFormat));
					document.getElementById('data'+row+'ITM_VOLM').value 		= parseFloat(Math.abs(ITM_VOLM));
					document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
					document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

					PRJCODE 	= "<?=$PRJCODE?>";
					JRN_NUM 	= "<?=$JournalH_Code?>";
					JOBCODEID 	= document.getElementById('data'+row+'JOBCODEID').value;
					ITMV 		= parseFloat(ITM_VOLM);
					ITMP 		= parseFloat(ITM_PRICE);
					strItem 	= PRJCODE+'|'+JRN_NUM+'|'+JOBCODEID+'|'+ITMV+'|'+ITMP+'|'+row;

					url 			= "<?=$chgVOL?>";
					$.ajax({
			            type: 'POST',
			            url: url,
			            data: {collDt: strItem},
			            success: function(response)
			            {
			            	arrAcc 		= response.split('~');
	                    	ITM_REM		= arrAcc[0];
	                    	ITM_REMAMN	= arrAcc[1];
							document.getElementById('data'+row+'ITM_REM').value 	= parseFloat(ITM_REM);
							document.getElementById('data'+row+'ITM_REMAMN').value 	= parseFloat(ITM_REMAMN);
							document.getElementById('JournalD_AmountR'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMAMN)),2));

							countAmount();
			            }
			        });
				}
			}
			else
			{
				if(ITM_REMVOL < 0)
				{
					swal('Budget sudah habis',
					{
						icon: "warning",
					})
					.then(function()
		            {
		                swal.close();
						document.getElementById('data'+row+'ITM_REM').value 	= parseFloat(0);
						document.getElementById('data'+row+'ITM_REMAMN').value 	= parseFloat(0);
						document.getElementById('JournalD_AmountR'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),2));
					});

			        countAmount();
				}
				else if(ITM_VOLM > ITM_REMVOL)
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

						var PPN_Amount	= parseFloat(PPN_Perc/100) * parseFloat(TOTPRC);
						var PPH_Amount	= parseFloat(PPH_Perc/100) * parseFloat(TOTPRC);
						document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(PPN_Amount);
						document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_Amount)), 2));
						document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(PPH_Amount);
						document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_Amount)), 2));

						PRJCODE 	= "<?=$PRJCODE?>";
						JRN_NUM 	= "<?=$JournalH_Code?>";
						JOBCODEID 	= document.getElementById('data'+row+'JOBCODEID').value;
						ITMV 		= parseFloat(ITM_VOLM);
						ITMP 		= parseFloat(ITM_PRICE);
						strItem 	= PRJCODE+'|'+JRN_NUM+'|'+JOBCODEID+'|'+ITMV+'|'+ITMP+'|'+row;

						url 			= "<?=$chgVOL?>";
						$.ajax({
				            type: 'POST',
				            url: url,
				            data: {collDt: strItem},
				            success: function(response)
				            {
				            	arrAcc 		= response.split('~');
		                    	ITM_REM		= arrAcc[0];
		                    	ITM_REMAMN	= arrAcc[1];
								document.getElementById('data'+row+'ITM_REM').value 	= parseFloat(ITM_REM);
								document.getElementById('data'+row+'ITM_REMAMN').value 	= parseFloat(ITM_REMAMN);
								document.getElementById('JournalD_AmountR'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMAMN)),2));

								countAmount();
				            }
				        });
		            });
				}
				else
				{
					var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

					document.getElementById('ITM_VOLM'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), decFormat));
					document.getElementById('data'+row+'ITM_VOLM').value 		= parseFloat(Math.abs(ITM_VOLM));
					document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
					document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

					var PPN_Amount	= parseFloat(PPN_Perc/100) * parseFloat(TOTPRC);
					var PPH_Amount	= parseFloat(PPH_Perc/100) * parseFloat(TOTPRC);
					document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(PPN_Amount);
					document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_Amount)), 2));
					document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(PPH_Amount);
					document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_Amount)), 2));

					PRJCODE 	= "<?=$PRJCODE?>";
					JRN_NUM 	= "<?=$JournalH_Code?>";
					JOBCODEID 	= document.getElementById('data'+row+'JOBCODEID').value;
					ITMV 		= parseFloat(ITM_VOLM);
					ITMP 		= parseFloat(ITM_PRICE);
					strItem 	= PRJCODE+'|'+JRN_NUM+'|'+JOBCODEID+'|'+ITMV+'|'+ITMP+'|'+row;

					url 			= "<?=$chgVOL?>";
					$.ajax({
			            type: 'POST',
			            url: url,
			            data: {collDt: strItem},
			            success: function(response)
			            {
			            	arrAcc 		= response.split('~');
	                    	ITM_REM		= arrAcc[0];
	                    	ITM_REMAMN	= arrAcc[1];
							document.getElementById('data'+row+'ITM_REM').value 	= parseFloat(ITM_REM);
							document.getElementById('data'+row+'ITM_REMAMN').value 	= parseFloat(ITM_REMAMN);
							document.getElementById('JournalD_AmountR'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMAMN)),2));

							countAmount();
			            }
			        });
				}
			}
		}
		else
		{
			var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);
			document.getElementById('ITM_VOLM'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)), decFormat));
			document.getElementById('data'+row+'ITM_VOLM').value 		= parseFloat(Math.abs(ITM_VOLM));
			document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
			document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

			var PPN_Amount	= parseFloat(PPN_Perc/100) * parseFloat(TOTPRC);
			var PPH_Amount	= parseFloat(PPH_Perc/100) * parseFloat(TOTPRC);
			document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(PPN_Amount);
			document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_Amount)), 2));
			document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(PPH_Amount);
			document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_Amount)), 2));
		}
	}
	
	function chgPrice(thisval, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_PRICE		= parseFloat(eval(thisval).value.split(",").join(""));

		var isVerified		= parseFloat(document.getElementById('data'+row+'isVerified').value);
		var ITM_VOLM		= parseFloat(document.getElementById('data'+row+'ITM_VOLM').value);
		var ITM_REMVOL		= parseFloat(document.getElementById('data'+row+'ITM_REM').value);
		var ITM_REMAMN		= parseFloat(document.getElementById('data'+row+'ITM_REMAMN').value);
		var PPN_Perc 		= parseFloat(document.getElementById('data'+row+'PPN_Perc').value);
		var PPH_Perc 		= parseFloat(document.getElementById('data'+row+'PPH_Perc').value);
		
		var ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		var ITM_UNIT 		= ITMUNIT.toUpperCase();

		var TOTPRC			= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

		if(isVerified == 1)
		{
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

					var PPN_Amount	= parseFloat(PPN_Perc/100) * parseFloat(TOTPRC);
					var PPH_Amount	= parseFloat(PPH_Perc/100) * parseFloat(TOTPRC);
					document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(PPN_Amount);
					document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_Amount)), 2));
					document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(PPH_Amount);
					document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_Amount)), 2));

					PRJCODE 	= "<?=$PRJCODE?>";
					JRN_NUM 	= "<?=$JournalH_Code?>";
					JOBCODEID 	= document.getElementById('data'+row+'JOBCODEID').value;
					ITMV 		= parseFloat(ITM_VOLM);
					ITMP 		= parseFloat(ITM_PRICE);
					strItem 	= PRJCODE+'|'+JRN_NUM+'|'+JOBCODEID+'|'+ITMV+'|'+ITMP+'|'+row;

					url 			= "<?=$chgVOL?>";
					$.ajax({
			            type: 'POST',
			            url: url,
			            data: {collDt: strItem},
			            success: function(response)
			            {
			            	arrAcc 		= response.split('~');
	                    	ITM_REM		= arrAcc[0];
	                    	ITM_REMAMN	= arrAcc[1];
							document.getElementById('data'+row+'ITM_REM').value 	= parseFloat(ITM_REM);
							document.getElementById('data'+row+'ITM_REMAMN').value 	= parseFloat(ITM_REMAMN);
							document.getElementById('JournalD_AmountR'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMAMN)),2));

							countAmount();
			            }
			        });
	            });
			}
			else
			{
				var TOTPRC		= parseFloat(ITM_VOLM) * parseFloat(ITM_PRICE);

				document.getElementById('ITM_PRICE'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)), decFormat));
				document.getElementById('data'+row+'ITM_PRICE').value 		= parseFloat(Math.abs(ITM_PRICE));
				document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
				document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

				var PPN_Amount	= parseFloat(PPN_Perc/100) * parseFloat(TOTPRC);
				var PPH_Amount	= parseFloat(PPH_Perc/100) * parseFloat(TOTPRC);
				document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(PPN_Amount);
				document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_Amount)), 2));
				document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(PPH_Amount);
				document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_Amount)), 2));

				PRJCODE 	= "<?=$PRJCODE?>";
				JRN_NUM 	= "<?=$JournalH_Code?>";
				JOBCODEID 	= document.getElementById('data'+row+'JOBCODEID').value;
				ITMV 		= parseFloat(ITM_VOLM);
				ITMP 		= parseFloat(ITM_PRICE);
				strItem 	= PRJCODE+'|'+JRN_NUM+'|'+JOBCODEID+'|'+ITMV+'|'+ITMP+'|'+row;

				url 			= "<?=$chgVOL?>";
				$.ajax({
		            type: 'POST',
		            url: url,
		            data: {collDt: strItem},
		            success: function(response)
		            {
		            	arrAcc 		= response.split('~');
	                	ITM_REM		= arrAcc[0];
	                	ITM_REMAMN	= arrAcc[1];
						document.getElementById('data'+row+'ITM_REM').value 	= parseFloat(ITM_REM);
						document.getElementById('data'+row+'ITM_REMAMN').value 	= parseFloat(ITM_REMAMN);
						document.getElementById('JournalD_AmountR'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_REMAMN)),2));

						countAmount();
		            }
		        });
			}
		}
		else
		{
			document.getElementById('ITM_PRICE'+row).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)), decFormat));
			document.getElementById('data'+row+'ITM_PRICE').value 		= parseFloat(Math.abs(ITM_PRICE));
			document.getElementById('JournalD_Amount'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)), decFormat));
			document.getElementById('data'+row+'JournalD_Amount').value = parseFloat(Math.abs(TOTPRC));

			var PPN_Amount	= parseFloat(PPN_Perc/100) * parseFloat(TOTPRC);
			var PPH_Amount	= parseFloat(PPH_Perc/100) * parseFloat(TOTPRC);
			document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(PPN_Amount);
			document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_Amount)), 2));
			document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(PPH_Amount);
			document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_Amount)), 2));
		}
	}
	
	function chgAmount(thisval, row)			// HOLD
	{
		var decFormat		= document.getElementById('decFormat').value;
		var Acc_Amount		= parseFloat(eval(thisval).value.split(",").join(""));
		var Rem_BudAmn		= parseFloat(document.getElementById('data'+row+'ITM_REMAMN').value);
		var ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
		var ITM_VOLM		= document.getElementById('data'+row+'ITM_VOLM').value;
		var PPN_Perc 		= parseFloat(document.getElementById('data'+row+'PPN_Perc').value);
		var PPH_Perc 		= parseFloat(document.getElementById('data'+row+'PPH_Perc').value);

		var ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		var ITM_UNIT 		= ITMUNIT.toUpperCase();
		
		var isVerified		= parseFloat(document.getElementById('data'+row+'isVerified').value);

		if(ITM_UNIT == 'LS' || ITM_UNIT == 'BLN' || ITM_UNIT == 'LOT')
		{
			//Acc_Volume	= parseFloat(Acc_Amount / ITM_PRICE);
			// SESUAI REQUEST BU RENI TGL. 24 JANUARI 2022, SAAT INPUT TOTAL, YANG BERUBAH ADALAH HARGA, VOLUME TETAP
			Acc_Volume 		= parseFloat(ITM_VOLM);
			Acc_Price		= parseFloat(Acc_Amount / ITM_VOLM);
			Acc_Amount		= parseFloat(Acc_Amount);
		}
		else
		{
			if(Acc_Amount > Rem_BudAmn)
			{
				// PERUBAHAN BERDASARKAN MS.201962000021
				var Rem_BudAmnV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Rem_BudAmn)),decFormat));
				swal("Out of budget, max amount "+Rem_BudAmnV,
				{
					icon:"warning",
				});
				//Acc_Volume= parseFloat(Rem_BudAmn / ITM_PRICE);
				// SESUAI REQUEST BU RENI TGL. 24 JANUARI 2022, SAAT INPUT TOTAL, YANG BERUBAH ADALAH HARGA, VOLUME TETAP
				Acc_Volume 	= parseFloat(ITM_VOLM);
				Acc_Price	= parseFloat(Rem_BudAmn / ITM_VOLM);
				Acc_Amount	= parseFloat(Rem_BudAmn);
				document.getElementById('JournalD_Amount'+row).focus();
			}
			else
			{
				//Acc_Volume	= parseFloat(Acc_Amount / ITM_PRICE);
				// SESUAI REQUEST BU RENI TGL. 24 JANUARI 2022, SAAT INPUT TOTAL, YANG BERUBAH ADALAH HARGA, VOLUME TETAP
				Acc_Volume 	= parseFloat(ITM_VOLM);
				Acc_Price	= parseFloat(Acc_Amount / ITM_VOLM);
				Acc_Amount	= parseFloat(Acc_Amount);
			}
		}
		
		document.getElementById('ITM_VOLM'+row).value 					= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Volume)),decFormat));
		document.getElementById('data'+row+'ITM_VOLM').value 			= parseFloat(Math.abs(Acc_Volume));
		
		// SESUAI REQUEST BU RENI TGL. 24 JANUARI 2022, SAAT INPUT TOTAL, YANG BERUBAH ADALAH HARGA, VOLUME TETAP
		document.getElementById('ITM_PRICE'+row).value 					= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Price)),decFormat));
		document.getElementById('data'+row+'ITM_PRICE').value 			= parseFloat(Math.abs(Acc_Price));
		
		document.getElementById('JournalD_Amount'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat));
		document.getElementById('data'+row+'JournalD_Amount').value 	= parseFloat(Math.abs(Acc_Amount));

		var PPN_Amount	= parseFloat(PPN_Perc/100) * parseFloat(Acc_Amount);
		var PPH_Amount	= parseFloat(PPH_Perc/100) * parseFloat(Acc_Amount);
		document.getElementById('data'+row+'PPN_Amount').value	= parseFloat(PPN_Amount);
		document.getElementById('PPN_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_Amount)), 2));
		document.getElementById('data'+row+'PPH_Amount').value	= parseFloat(PPH_Amount);
		document.getElementById('PPH_AmountX'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_Amount)), 2));
		
		var totRow = document.getElementById('totalrow').value;
		var totAmount	= 0;
		var totAmountD	= 0;
		var totAmountK	= 0;
		for(i=1;i<=totRow;i++)
		{
			JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
			totAmountA 		= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
			//totAmount 	= parseFloat(totAmount) + parseFloat(totAmountA);
			if(JournalD_Pos == 'D')
				totAmountD	= parseFloat(totAmountD) + parseFloat(totAmountA);
			else
				totAmountK	= parseFloat(totAmountK) + parseFloat(totAmountA);
		}
		document.getElementById('Journal_Amount').value 	= totAmountD;
		document.getElementById('Journal_AmountX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountD)),decFormat));
		document.getElementById('Journal_AmountD').value 	= totAmountD;
		document.getElementById('Journal_AmountK').value 	= totAmountK;

		countAmount();
	}

	function delRow(row) 
	{
        swal({
            text: "<?php echo $alert17; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				var collID  	= document.getElementById('urlDel'+row).value;
		        var url 		= "<?=$delRVCASH?>";
		        
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {collID: collID},
                    success: function(response)
                    {
                        swal(response, 
                        {
                            icon: "success",
                        })
                        .then(function()
                        {
                        	swal.close();
                        	location.reload();
                        })
                    }
                });
            } 
            else 
            {
                //...
            }
        });
	}
	
	function countAmount_220526()
	{
		var decFormat	= document.getElementById('decFormat').value;
		var totRow 		= document.getElementById('totalrow').value;
		var totAmount	= 0;
		var totAmountD	= 0;
		var totAmountK	= 0;
		var totPPN 		= 0;
		var totPPH 		= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'JournalD_Pos');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';

			////****console.log(i+' = '+ values)
			
			if(values != null)
			{
				JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
				totAmountA 		= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
				//totAmount 	= parseFloat(totAmount) + parseFloat(totAmountA);
				if(JournalD_Pos == 'D')
					totAmountD	= parseFloat(totAmountD) + parseFloat(totAmountA);
				else
					totAmountK	= parseFloat(totAmountK) + parseFloat(totAmountA);

				// GET TOTAL PPN & PPH
					PPN_Amn 	= document.getElementById('data'+i+'PPN_Amount').value;
					PPH_Amn 	= document.getElementById('data'+i+'PPH_Amount').value;
					totPPN 		= parseFloat(totPPN) + parseFloat(PPN_Amn);
					totPPH 		= parseFloat(totPPH) + parseFloat(PPH_Amn);
			}
		}
		document.getElementById('Journal_Amount').value 	= totAmountD;
		document.getElementById('Journal_AmountX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountD)), 2));
		document.getElementById('Journal_AmountD').value 	= totAmountD;
		document.getElementById('Journal_AmountK').value 	= totAmountK;
		document.getElementById('PPNH_Amount').value 		= totPPN;
		document.getElementById('PPNH_AmountX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totPPN)), 2));
		document.getElementById('PPHH_Amount').value 		= totPPH;
		document.getElementById('PPHH_AmountX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totPPH)), 2));
		var GTotal 	= parseFloat(totAmountD) + parseFloat(totPPN) - parseFloat(totPPH);
		document.getElementById('GJournal_Total').value 	= parseFloat(GTotal);
		document.getElementById('GJournal_TotalX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTotal)), 2));
	}
	
	function countAmount()
	{
		totRealizRem 	= parseFloat(document.getElementById('Journal_AmountRemOri').value);
		var totRow 		= document.getElementById('totalrow').value;
		var totAmount	= 0;
		var totAmountD	= 0;
		var totAmountK	= 0;
		var totOverPay	= 0;
		var realizRem 	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'JournalD_Pos');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';

			if(values != null)
			{
				JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
				totAmountA 		= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);

				//totAmount 	= parseFloat(totAmount) + parseFloat(totAmountA);
				if(JournalD_Pos == 'D')
					totAmountD	= parseFloat(totAmountD) + parseFloat(totAmountA);
				else
					totAmountK	= parseFloat(totAmountK) + parseFloat(totAmountA);

				isExtra 		= document.getElementById('data'+i+'isExtra').value;

				/*console.log('totAmountD = '+totAmountD+' = '+i)
				console.log('totRealizRem = '+totRealizRem+' = '+i)*/
				if(parseFloat(totRealizRem) > parseFloat(totAmountD))
				{
					itmTotal 		= document.getElementById('data'+i+'JournalD_Amount').value;
					totRealizRem 	= parseFloat(totRealizRem - itmTotal);
					document.getElementById('Journal_AmountRem').value 	= totRealizRem;
				}
				else if(parseFloat(totAmountD) > parseFloat(totRealizRem))
				{
				    //console.log(totAmountA+'>'+totRealizRem+" i = "+i);
					if(isExtra == 1)
					{
						document.getElementById('btnSave').style.display 	= 'none';
						swal({
				            text: "<?php echo $alert14; ?>",
				            icon: "warning",
				            buttons: ["No", "Yes"],
				        })
				        .then((willDelete) => 
				        {
				            if (willDelete) 
				            {
								itmTotal 		= document.getElementById('data'+i+'JournalD_Amount').value;
								totRealizRem 	= parseFloat(totRealizRem - itmTotal);
								overPay 		= parseFloat(totRealizRem) - parseFloat(totAmountA);
								totOverPay 		= parseFloat(totOverPay) + parseFloat(overPay);

								document.getElementById('OverRPD').value 			= RoundNDecimal(parseFloat(Math.abs(totOverPay)), 2);
								document.getElementById('ovPay').innerHTML			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totOverPay)), 2));

								document.getElementById('data'+i+'isExtra').value 	= 1;
								document.getElementById('chkExtra'+i).checked 		= true;

								document.getElementById('Journal_AmountRem').value 	= totRealizRem;

								document.getElementById('btnSave').style.display 	= '';

				               /* document.getElementById('ITM_VOLM'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(1)), 2));
								document.getElementById('data'+i+'ITM_VOLM').value 	= parseFloat(Math.abs(1));
								document.getElementById('ITM_PRICE'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totRealizRem)), 2));
								document.getElementById('data'+i+'ITM_PRICE').value = parseFloat(Math.abs(totRealizRem));
								document.getElementById('JournalD_Amount'+i).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totRealizRem)), 2));*/

								/*document.getElementById('data'+i+'isExtra').value 	= 1;
								document.getElementById('hrefID'+i).className 		= 'btn btn-danger btn-xs';*/
				            } 
				            else
				            {
								//document.getElementById('OverRPD').value 			= RoundNDecimal(parseFloat(Math.abs(0)), 2);
								//document.getElementById('ovPay').innerHTML			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

								document.getElementById('ITM_VOLM'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(1)), 2));
								document.getElementById('data'+i+'ITM_VOLM').value 	= parseFloat(Math.abs(1));
								document.getElementById('ITM_PRICE'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totRealizRem)), 2));
								document.getElementById('data'+i+'ITM_PRICE').value = parseFloat(Math.abs(totRealizRem));
								document.getElementById('JournalD_Amount'+i).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totRealizRem)), 2));
								document.getElementById('data'+i+'JournalD_Amount').value = parseFloat(Math.abs(totRealizRem));

								itmTotal 		= document.getElementById('data'+i+'JournalD_Amount').value;
								totRealizRem 	= parseFloat(totRealizRem - itmTotal);
								//console.log('totRealizRem = '+totRealizRem)

								document.getElementById('Journal_AmountRem').value 	= totRealizRem;

								document.getElementById('data'+i+'isExtra').value 	= 0;
								document.getElementById('chkExtra'+i).disabled 		= true;

								document.getElementById('btnSave').style.display 	= '';
				            }
				        });
				    }
				    else
				    {
				    	swal('<?php echo $alert16; ?>'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totRealizRem)), 2)),
						{
							icon: "warning",
						})
						.then(function()
						{
							//console.log('000')
							swal.close();
							//console.log('a = '+i)
							document.getElementById('ITM_VOLM'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(1)), 2));
							//console.log('b')
							document.getElementById('data'+i+'ITM_VOLM').value 	= parseFloat(Math.abs(1));
							//console.log('c')
							document.getElementById('ITM_PRICE'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totRealizRem)), 2));
							document.getElementById('data'+i+'ITM_PRICE').value = parseFloat(Math.abs(totRealizRem));
							document.getElementById('JournalD_Amount'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totRealizRem)), 2));
							document.getElementById('data'+i+'JournalD_Amount').value = parseFloat(Math.abs(totRealizRem));

							document.getElementById('data'+i+'isExtra').value 	= 0;
							document.getElementById('chkExtra'+i).disabled 		= true;
									
							itmTotal 		= document.getElementById('data'+i+'JournalD_Amount').value;
							totRealizRem 	= parseFloat(totRealizRem - itmTotal);
							//console.log('totRealizRem = '+totRealizRem)

							document.getElementById('Journal_AmountRem').value 	= totRealizRem;

							document.getElementById('btnSave').style.display 	= '';

							//document.getElementById('OverRPD').value 			= RoundNDecimal(parseFloat(Math.abs(0)), 2);
							//document.getElementById('ovPay').innerHTML		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

							/*document.getElementById('data'+i+'isExtra').value 	= 0;
							document.getElementById('hrefID'+i).className 		= 'btn btn-success btn-xs';*/
						});
				    	return false;
				    }
				}
				else
				{
				    // console.log('cek'+i)
					//console.log('bbb')
					/*swal('<?php echo $alert17; ?>',
					{
						icon: "warning",
					})
					.then(function()
					{
						swal.close();
						console.log('ITM_VOLM'+i)
						document.getElementById('ITM_VOLM'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
						document.getElementById('data'+i+'ITM_VOLM').value 	= parseFloat(Math.abs(0));
						document.getElementById('ITM_PRICE'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
						document.getElementById('data'+i+'ITM_PRICE').value = parseFloat(Math.abs(0));
						document.getElementById('JournalD_Amount'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
						document.getElementById('data'+i+'JournalD_Amount').value = parseFloat(Math.abs(0));

						document.getElementById('btnSave').style.display 	= 'none';
					});
				    return false;*/
				}
			}
		}

		document.getElementById('Journal_AmountD').value 	= totAmountD;
		document.getElementById('Journal_AmountK').value 	= totAmountK;
	}

	function chkExtra(thisObj, row)
	{
		if(thisObj.checked == true)
			document.getElementById('data'+row+'isExtra').value 	= 1;
		else
			document.getElementById('data'+row+'isExtra').value 	= 0;
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function validateInData(value)
	{
		var totrow 			= document.getElementById('totalrow').value;
		var ISPERSL 		= document.getElementById('ISPERSL').value;
		var JournalH_Desc 	= document.getElementById('JournalH_Desc').value;
		var GEJ_STAT_PD 		= document.getElementById('GEJ_STAT_PD').value;
		var totAmountD		= parseFloat(document.getElementById('Journal_AmountD').value);
		var totAmountK		= parseFloat(document.getElementById('Journal_AmountK').value);
		var Journal_Amount	= parseFloat(document.getElementById('Journal_Amount').value);
		var acc_balance		= parseFloat(eval(document.getElementById('ACC_OPBAL')).value.split(",").join(""));
		
		if(JournalH_Desc == '')
		{
			swal('<?php echo $alert0; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#JournalH_Desc').val('');
            });
			return false;
		}

		if(GEJ_STAT_PD == 4)
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
		else if(GEJ_STAT_PD == 5)
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
		else if(GEJ_STAT_PD == 9)
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
			swal('<?php echo $alert12; ?>',
			{
				icon: "warning",
			});
			return false;
		}

		if(ISPERSL == 0)
		{
			var rowD		= 0;
			var rowK		= 0;
			for(i=1;i<=totrow;i++)
			{
				let myObj 	= document.getElementById('data'+i+'Acc_Id');
				var values 	= typeof myObj !== 'undefined' ? myObj : '';

				////****console.log(i+' = '+ values)
				
				if(values != null)
				{
					var Acc_Id 			= document.getElementById('data'+i+'Acc_Id').value;
					var JournalD_Desc 	= document.getElementById('data'+i+'JournalD_Desc').value;
					var JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
					var isTax			= document.getElementById('data'+i+'isTax').value;
					var TAX_NO			= document.getElementById('data'+i+'TAX_NO').value;
					var JournalD_Amount	= parseFloat(document.getElementById('data'+i+'JournalD_Amount').value);
					var Other_Desc		= document.getElementById('data'+i+'Other_Desc').value;
					var ITM_VOLM		= document.getElementById('data'+i+'ITM_VOLM').value;
					
					if(Acc_Id == '')
					{
						swal('<?php echo $alert1; ?>',
						{
							icon: "warning",
						});
						document.getElementById('data'+i+'Acc_Id').focus();
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
					
					/*if(JournalD_Pos == '')
					{
						swal('<?php echo $alert3; ?>');
						document.getElementById('data'+i+'JournalD_Pos').focus();
						return false;
					}*/
					
					if(isTax == '')
					{
						swal('<?php echo $alert4; ?>',
						{
							icon: "warning",
						});
						document.getElementById('data'+i+'isTax').focus();
						return false;
					}
					
					if(TAX_NO != '' && TAX_DATE == '')
					{
						swal('<?php echo $alert5; ?>',
						{
							icon: "warning",
						});
						document.getElementById('data'+i+'TAX_DATE').focus();
						return false;
					}
					
					if(ITM_VOLM == 0)
					{
						swal('<?php echo $alert6; ?>',
						{
							icon: "warning",
						});
						document.getElementById('data'+i+'ITM_VOLM').focus();
						return false;
					}
					
					/*if(JournalD_Amount == 0)
					{
						swal('<?php echo $alert6; ?>');
						document.getElementById('JournalD_Amount'+i).focus();
						return false;
					}*/
					
					if(JournalD_Pos == 'D')
						var rowD	= parseFloat(rowD) + 1;
					else
						var rowK	= parseFloat(rowK) + 1;
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
			
			/*if(rowK == 0)
			{
				swal('<?php echo $alert8; ?>')
				return false;
			}
				
			if(totAmountD != totAmountK)
			{
				swal('<?php echo $alert9; ?>');
				return false;
			}*/

			if(totrow == 0)
			{
				swal('Please input detail Material Request.',
					{
						icon: "warning",
					});
				return false;		
			}

		}

		document.frm.submit();
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