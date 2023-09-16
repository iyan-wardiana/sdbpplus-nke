<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 10 April 2022
	* File Name		= gej_bookTF_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$comp_color	= $this->session->userdata['comp_color'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

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
	$PRJPERIOD		= $PRJCODE;	

	$TRXTIME		= date('ymdHis');
	$JournalH_Code	= "$PATTCODE$PRJCODE.$TRXTIME";
	$DocNumber 		= "";
	$Manual_No		= $DocNumber;

	$JournalH_Date	= date('d/m/Y');
	$JournalY 		= date('Y');
	$JournalM 		= date('n');
	$JournalH_Desc	= $SOURCEDOC;
	$JournalH_Desc2	= '';
	$REF_NUM		= '';
	$REF_CODE 		= '';
	$PRJCODE		= $PRJCODE;
	$PRJCODE_HO		= $PRJCODE_HO;
	$PRJPERIOD		= $PRJPERIOD;
	$GEJ_STAT		= 1;
	$Journal_Amount	= 0;
	$Pattern_Type	= 'PINBUK';
	$SPLCODE		= '';
	$Source 		= 0;
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
	$Source				= $default['Source'];
	$REF_NUM			= $default['REF_NUM'];
	$REF_CODE			= $default['REF_CODE'];
}

// GET Journal Lock
	$disabled 	= 0;
	$getJLock 	= "SELECT * FROM tbl_journal_lock 
					WHERE LockY = $JournalY AND LockM = $JournalM AND isLock = 1 AND UserLock != '$DefEmp_ID'";
	$resJLock 	= $this->db->query($getJLock);
	$countJLock = $resJLock->num_rows();
	if($countJLock == 1) $disabled = 1;

$disSel 	= "disabled";
if($Source == 1)
	$disSel 	= "";
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
			if($TranslCode == 'AccountPosition')$AccountPosition = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
			if($TranslCode == 'JournalCode')$JournalCode = $LangTransl;
			if($TranslCode == 'JournalType')$JournalType = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
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
			if($TranslCode == 'Category')$Category = $LangTransl;
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
			$alert15 	= "Silahkan pilih proposal sirkulasi dana";
			$alert16	= "Silahkan pilih akun kas/bank ";
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
			$alert15 	= "Please select Circulation Fund Proposal";
			$alert16	= "Please select account cash/bank ";
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
						<input type="hidden" name="DefEmp_ID" id="DefEmp_ID" value="<?php echo $DefEmp_ID; ?>">
			            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
			            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
			            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
			            <input type="hidden" name="task" id="task" value="<?php echo $task; ?>" />
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
					                    	<input type="text" class="form-control" style="text-align:left" name="JournalH_Code1" id="JournalH_Code1" size="30" value="<?php echo $JournalH_Code; ?>" disabled />
					                    	<input type="text" class="form-control" style="max-width:350px;text-align:right" name="JournalH_Code" id="JournalH_Code" size="30" value="<?php echo $JournalH_Code; ?>" />
					                        <input type="text" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
					                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="proj_CodeHO" id="proj_CodeHO" size="30" value="<?php echo $PRJCODE_HO; ?>" />
					                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJPERIOD" id="PRJPERIOD" size="30" value="<?php echo $PRJPERIOD; ?>" />
					                    <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="Pattern_Code" id="Pattern_Code" size="30" value="" />
					                    </div>
					                </div>
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?></label>
					                    <div class="col-sm-4">
					                    	<input type="text" class="form-control" name="Manual_No1" id="Manual_No1" size="30" value="<?php echo $Manual_No; ?>" onblur="chgManualNo(this.value)" disabled/>
					                    	<input type="hidden" class="form-control" name="Manual_No" id="Manual_No" size="30" value="<?php echo $Manual_No; ?>" />
					                    </div>
					                    <div class="col-sm-5" style="display: none;">
					                    	<label for="inputName" class="control-label"><?php echo $JournalType; ?></label>
					                    </div>
										<div class="col-sm-5">
											<input type="checkbox" id="stopAutoCode" title="stop auto number, please check" onchange="stopAuto_Code()">
										</div>
					                </div>
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
					                    <div class="col-sm-4">
					                    	<div class="input-group date">
					                        <div class="input-group-addon">
					                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="JournalH_Date" class="form-control pull-left" id="datepicker" value="<?php echo $JournalH_Date; ?>" onChange="addUCODE()"></div>
					                    </div>
					                    <div class="col-sm-5" style="display: none;">
					                    	<select name="Pattern_Type1" id="Pattern_Type1" class="form-control select2" onChange="chkSPL(this.value)">
					                          <option value="GEJ" <?php if($Pattern_Type == 'GEJ') { ?> selected <?php } ?>><?php echo $GeneralJournal; ?></option>
					                          <option value="TLK" <?php if($Pattern_Type == 'TLK') { ?> selected <?php } ?>><?php echo "Transfer LK"; ?></option>
					                          <option hidden value="LOAN" <?php if($Pattern_Type == 'LOAN') { ?> selected <?php } ?>><?php echo $Loan; ?></option>
					                          <option value="PINBUK" <?php if($Pattern_Type == 'PINBUK') { ?> selected <?php } ?>><?php echo "Pindah Buku"; ?></option>
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
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Category; ?></label>
					                    <div class="col-sm-4">
					                    	<select name="Source" id="Source" class="form-control select2" onChange="selSrc(this.value)">
					                          	<option value="0" <?php if($Source == 0) { ?> selected <?php } ?>>---</option>
					                          	<!-- <option value="1" <?php if($Source == 1) { ?> selected <?php } ?>>Proposal Dana Sirkulasi</option> -->
					                          	<option value="2" <?php if($Source == 2) { ?> selected <?php } ?>>Voucher Transfer LK</option>
						                    </select>
						                </div>
					                    <div class="col-sm-5">
					                    	<div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary" id="btnREFNUM" data-toggle="modal" data-target="#mdl_addJRN" <?=$disSel?>><i class="glyphicon glyphicon-search"></i></button>
			                                    </div>
			                                    <input type="text" class="form-control" name="REF_NUMX" id="REF_NUMX" value="<?php echo $REF_CODE; ?>" data-toggle="modal" data-target="#mdl_addPR" <?=$disSel?>>
						                    	<input type="hidden" class="form-control" id="REF_NUM" name="REF_NUM" value="<?php echo $REF_NUM; ?>" />
						                    	<input type="hidden" class="form-control" id="REF_CODE" name="REF_CODE" value="<?php echo $REF_CODE; ?>" />
			                                </div>
					                    </div>
						                <script type="text/javascript">
						                	function selSrc(theVal)
						                	{
						                		if(theVal == 0)
						                		{
						                			document.getElementById('btnREFNUM').disabled 	= true;
						                			document.getElementById('REF_NUMX').disabled 	= true;
						                			document.getElementById('REF_NUMX').value 		= '';
						                			document.getElementById('REF_NUM').value 		= '';
						                			document.getElementById('REF_CODE').value 		= '';
						                		}
						                		else
						                		{
						                			document.getElementById('btnREFNUM').disabled 	= false;
						                			document.getElementById('REF_NUMX').disabled 	= false;
						                		}
						                	}

						                	function selDocSrc(theVal)
						                	{
						                		document.getElementById('REF_NUM').value = theVal;
						                	}
						                </script>
					                    <input type="hidden" class="form-control" id="proj_Code" name="proj_Code" value="<?php echo $PRJCODE; ?>" />
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
					                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="3">
					                        <input type="hidden" name="Journal_Amount" id="Journal_Amount" value="<?php echo $Journal_Amount; ?>">
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
					                    <div class="col-sm-9">
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
																<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2">
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
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2">
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
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2"<?php if($disButton == 1) { ?> disabled <?php } ?>>
																		<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																		<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																		<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
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
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2"<?php if($disButton == 1){ ?> disabled <?php } ?>>
																		<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																		<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																		<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
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
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2"<?php if($disButton == 1) { ?> disabled <?php } ?>>
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
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2"<?php if($disButton == 1) { ?> disabled <?php } ?>>
																		<option value="1"<?php if($GEJ_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																		<option value="2"<?php if($GEJ_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																		<option value="3"<?php if($GEJ_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
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
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2"<?php if($disButton == 1) { ?> disabled <?php } ?>>
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
																	<select name="GEJ_STAT" id="GEJ_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
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
		                        	<tr style="background:#CCCCCC">
		                              	<th width="300" style="text-align:center"><?php echo $AccountNo; ?> </th>
		                              	<th style="text-align:center"><?php echo $Description; ?> </th>
		                              	<th width="150" style="text-align:center"><?php echo "Debet"; ?></th>
		                              	<th width="150" style="text-align:center"><?php echo "Kredit"; ?></th>
		                              	<th width="250" style="text-align:center"><?php echo $Remarks; ?></th>
		                          	</tr>
		                            <?php
				                	$getAcc 	= base_url().'index.php/c_finance/c_cho_pd/getACCID/?id='.$PRJCODE;
		                            $editable	= 0;
		                            if($GEJ_STAT == 1 || $GEJ_STAT == 4)
                                    {
                                    	$editable	= 1;
                                    }
                                    if($task == 'edit')
                                    {
                                    	$s_00 	= "SELECT Acc_ID, Acc_Name, Notes, Base_Debet, Other_Desc FROM tbl_journaldetail_pb
                                    				WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'";
                                    	$r_00 	= $this->db->query($s_00)->result();
                                    	foreach($r_00 as $rw_00):
                                    		$AccId_D 	= $rw_00->Acc_ID;
                                    		$AccD_Name 	= $rw_00->Acc_Name;
                                    		$JrnD_Desc 	= $rw_00->Notes;
                                    		$JrnD_Amoun	= $rw_00->Base_Debet;
                                    		$OthD_Desc 	= $rw_00->Other_Desc;
                                    	endforeach;

                                    	$s_01 	= "SELECT Acc_ID, Acc_Name, Notes, Base_Kredit, Other_Desc FROM tbl_journaldetail_pb
                                    				WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'";
                                    	$r_01 	= $this->db->query($s_01)->result();
                                    	foreach($r_01 as $rw_01):
                                    		$AccId_K 	= $rw_01->Acc_ID;
                                    		$AccK_Name 	= $rw_01->Acc_Name;
                                    		$JrnK_Desc 	= $rw_01->Notes;
                                    		$JrnK_Amoun	= $rw_01->Base_Kredit;
                                    		$OthK_Desc 	= $rw_01->Other_Desc;
                                    	endforeach;
                                    }
                                    else
                                    {
                                		$AccId_D 	= "";
                                		$AccD_Name 	= "";
                                		$JrnD_Desc 	= "";
                                		$JrnD_Amoun	= 0;
                                		$OthD_Desc 	= "";

                                		$AccId_K 	= "";
                                		$AccK_Name 	= "";
                                		$JrnK_Desc 	= "";
                                		$JrnK_Amoun	= 0;
                                		$OthK_Desc 	= "";
                                    }
									?>
									<tr>
                                        <td style="text-align:left" nowrap>
											<input type="radio" name="chkBankCode" id="chkBankCode0" class="flat-red" value="0">
                                        	<select name="AccId_D" id="AccId_D" class="form-control select2" style="width: 300px;">
				                            	<option value=""> --- </option>
				                                <?php echo $i = 0;
					                                if($countAcc > 0)
					                                {
														foreach($vwAcc as $row) :
															$Acc_ID					= $row->Acc_ID;
															$Account_Category		= $row->Account_Category;
															$Account_Class			= $row->Account_Class;
															$Account_Number 		= $row->Account_Number;
															$Account_Name 			= cut_text ($row->Account_Name, 50);
															$Base_OpeningBalance 	= $row->Base_OpeningBalance;
															$Base_Debet 			= $row->Base_Debet;
															$Base_Kredit 			= $row->Base_Kredit;
															$BalAcc					= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;

															if($BalAcc <= 0)
																$disable = 0; // untuk sementara, diperbolehkan minus update date => 2022-03-23 14.08
															else
																$disable = 0;
															//$disable 	= 0;
															
															if($Account_Class == 2)
																$disable = 0;
															?>
																<option value="<?php echo $Account_Number; ?>" <?php if($disable == 1) { ?> style="color:#999" disabled <?php } else { ?> style="color:#00F; font-weight:bold" <?php } if($AccId_D == $Account_Number) { ?> selected <?php } ?>>
																	<?php echo "$Account_Number &nbsp;&nbsp;$Account_Name"; ?>
																</option>
														 	<?php
														endforeach;
					                                }
				                                ?>
				                            </select>
                                        </td>
                                        <td style="text-align:left" nowrap>
											<input type="text" class="form-control" id="JournalD_Desc" name="JournalD_Desc" value="<?=$JrnD_Desc;?>">
										</td>
                                        <td style="text-align:right" nowrap>
                                        	<input type="hidden" class="form-control" style="text-align:right" name="JournalD_Amount" id="JournalD_Amount" value="<?=$JrnD_Amoun;?>" placeholder="0.00">
                                        	<input type="text" class="form-control" style="text-align:right" name="JournalD_AmountV" id="JournalD_AmountV" value="<?php echo number_format($JrnD_Amoun, 2);?>" placeholder="0.00" onBlur="chgAmountD(this)" onKeyPress="return isIntOnlyNew(event);">
                                        </td>
										<td style="text-align:center" nowrap>
											<input type="text" class="form-control" style="text-align:right" name="JournalK_Amountx" id="JournalK_Amountx" value="" placeholder="0.00" onBlur="chgAmountD(this)" onKeyPress="return isIntOnlyNew(event);" disabled>
										</td>
                                        <td style="text-align:left" nowrap>
											<input type="text" class="form-control" id="OtherD_Desc" name="OtherD_Desc" value="<?=$OthD_Desc;?>">
										</td>
					          		</tr>
					          		<tr>
                                        <td style="text-align:left" nowrap>
											<input type="radio" name="chkBankCode" id="chkBankCode1" class="flat-red" value="1">
                                        	<select name="AccId_K" id="AccId_K" class="form-control select2" style="width: 300px;">
				                            	<option value=""> --- </option>
				                                <?php echo $i = 0;
					                                if($countAcc > 0)
					                                {
														foreach($vwAcc as $row) :
															$Acc_ID					= $row->Acc_ID;
															$Account_Category		= $row->Account_Category;
															$Account_Class			= $row->Account_Class;
															$Account_Number 		= $row->Account_Number;
															$Account_Name 			= cut_text ($row->Account_Name, 50);
															$Base_OpeningBalance 	= $row->Base_OpeningBalance;
															$Base_Debet 			= $row->Base_Debet;
															$Base_Kredit 			= $row->Base_Kredit;
															$BalAcc					= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;

															if($BalAcc <= 0)
																$disable = 0; // untuk sementara, diperbolehkan minus update date => 2022-03-23 14.08
															else
																$disable = 0;
															//$disable 	= 0;
															
															if($Account_Class == 2)
																$disable = 0;
															?>
																<option value="<?php echo $Account_Number; ?>" <?php if($disable == 1) { ?> style="color:#999" disabled <?php } else { ?> style="color:#00F; font-weight:bold" <?php } if($AccId_K == $Account_Number) { ?> selected <?php } ?>>
																	<?php echo "$Account_Number &nbsp;&nbsp;$Account_Name"; ?>
																</option>
														 	<?php
														endforeach;
					                                }
				                                ?>
				                            </select>
                                        </td>
                                        <td style="text-align:left" nowrap>
											<input type="text" class="form-control" id="JournalK_Desc" name="JournalK_Desc" value="<?=$JrnK_Desc;?>">
										</td>
                                        <td style="text-align:right" nowrap>
											<input type="text" class="form-control" style="text-align:right" name="JournalK_Amountx" id="JournalK_Amountx" value="" placeholder="0.00" onBlur="chgAmountD(this)" onKeyPress="return isIntOnlyNew(event);" disabled>
                                        </td>
										<td style="text-align:center" nowrap>
                                        	<input type="hidden" class="form-control" style="text-align:right" name="JournalK_Amount" id="JournalK_Amount" value="<?=$JrnK_Amoun;?>" placeholder="0.00">
                                        	<input type="text" class="form-control" style="text-align:right" name="JournalK_AmountV" id="JournalK_AmountV" value="<?php echo number_format($JrnK_Amoun, 2);?>" placeholder="0.00" onBlur="chgAmountK(this)" onKeyPress="return isIntOnlyNew(event);">
										</td>
                                        <td style="text-align:left" nowrap>
											<input type="text" class="form-control" id="OtherK_Desc" name="OtherK_Desc" value="<?=$OthK_Desc;?>">
										</td>
					          		</tr>
					                <script type="text/javascript">
					                	function chgAccCB(AccID)
					                	{
							                /*var url     	= "<?php echo $getAcc; ?>";
											var jrnType 	= "PINBUK";

											var task 		= document.getElementById('task').value;
											var jrnCode 	= document.getElementById('Manual_No').value;
											var PRJCODE 	= "<?=$PRJCODE?>";
											console.log('b')

											if(task == 'add')
												var pattCode= document.getElementById('Pattern_Code').value;
											else
												var pattCode= "";

											var collDt 		= jrnCode+'~'+pattCode+'~'+PRJCODE+'~'+task+'~'+jrnType+'~'+AccID;
											console.log('c')

					                		console.log(collDt)
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

							                    	document.getElementById('Manual_No').value 		= Manual_CB;
							                    	document.getElementById('Manual_No1').value 	= Manual_CB;
							                    }
							                });*/
							                addUCODE();
					                	}
					                </script>
									<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                        </table>
		                    </div>
						</div>
		                <br>
		                <div class="col-md-6">
			                <div class="form-group">
			                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
			                    <div class="col-sm-9">
			                        <?php
										$backURL	= site_url('c_gl/c_pinbook/gejpinbook/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
			                                        <button class="btn btn-primary" id="btnSave" <?php if($GEJ_STAT == 3){ ?> style="display: none;" <?php } ?>>
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
				</script>

                <?php
                    $DefID      = $this->session->userdata['Emp_ID'];
                    $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    if($DefID == 'D15040004221')
                        echo "<font size='1'><i>$act_lnk</i></font>";
                ?>
			</section>

	    	<!-- ============ START MODAL JRN LIST =============== -->
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
		        <div class="modal fade" id="mdl_addJRN" name='mdl_addJRN' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab">Propoal Dana Sirkulasi</a>
						                    </li>
						                </ul>

							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="2%" style="text-align:center; vertical-align: middle;">&nbsp;</th>
											                        <th width="18%" style="text-align:center; vertical-align: middle;" nowrap>No. Proposal</th>
											                        <th width="40%" style="text-align:center; vertical-align: middle;"><?php echo $Description; ?> </th>
											                        <th width="30%" style="text-align:center; vertical-align: middle;" nowrap>Pengaju</th>
											                        <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Amount; ?>
											                        </th>
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
                                      					<button class="btn btn-warning" type="button" id="idRefresh1" >
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
				<div id='loader' style="display: none;">
				  <img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>'/>
				</div>
				
				<?php
					$url1 	= site_url('c_gl/c_pinbook/get_AllDataPDS/?id='.$PRJCODE);
					$url2 	= site_url('c_gl/c_pinbook/get_AllDataVTLK/?id='.$PRJCODE);
				?>
				<script type="text/javascript">
					$(document).ready(function()
					{
				    	$('#example1').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo $url2?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
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

					function pickThis1(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck0").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail1").click(function()
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

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	add_header($(this).val());
						    });

						    $('#mdl_addJRN').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose1").click()
					    });
	    
					   	$("#idRefresh1").click(function()
					    {
							$('#example1').DataTable().ajax.reload();
					    });
					});
				</script>
	    	<!-- ============ END MODAL JRN LIST =============== -->
		</div>
	</body>
</html>

<script>
  	$(function ()
  	{
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
	    $('#datepicker').datepicker({
			autoclose: true,
			//startDate: '-6d',
			endDate: '+0d'
	    });

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

		$('input:radio[name="chkBankCode"]').on('ifChecked', function(e) {
			if(e.target.checked == true)
			{
				if(e.target.value == 0)
				{
					let AccId 		= $('#AccId_D').val();
					if(AccId != '') {
						addUCODE(AccId);
					}
				}
				else
				{
					let AccId 		= $('#AccId_K').val();
					if(AccId != '') {
						addUCODE(AccId);
					}
				}
			}
		})

		$('#AccId_D').on('change', function(e) {
			let isChecked 	= $("#chkBankCode0").prop("checked");
			let AccId 		= $(this).val();
			if(isChecked == true)
			{
				addUCODE(AccId);
			}
		});

		$('#AccId_K').on('change', function(e) {
			let isChecked 	= $("#chkBankCode1").prop("checked");
			let AccId 		= $(this).val();
			if(isChecked == true)
			{
				addUCODE(AccId);
			}
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
					console.log("isLockT ="+isLockT+" isLockJ = "+isLockJ+" LockCateg = "+LockCateg);

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
						if(LockCateg == 1)
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
							// document.getElementById('btnSave').style.display    = 'none';
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
				// const Auto_Code = setInterval(addUCODE(), 1000);
				$(document).ready(function()
				{
					// const Auto_Code = setInterval(function() {addUCODE()}, 1000);
				});
			<?php
		}
		// END : GENERATE MANUAL CODE
	?>

	function chgManualNo(thisVal)
	{
		var checkBox = document.getElementById("stopAutoCode");
		if(checkBox.checked == true)
		{
			document.getElementById("Manual_No").value = thisVal;
		}
	}

	function stopAuto_Code()
	{
		// clearInterval(Auto_Code);
		// document.getElementById('stopAutoCode').disabled = true;
		let ischk = document.getElementById('stopAutoCode').checked;
		if(ischk == true)
		{
			$('#Manual_No1').val('');
			$('#Manual_No').val('');
			$('#Manual_No1').prop('disabled', false);
			$('input:radio[name="chkBankCode"]').iCheck('uncheck');
			$('input:radio[name="chkBankCode"]').prop('disabled', true);
		}
		else
		{
			$('#Manual_No1').prop('disabled', true);
			$('#chkBankCode0').iCheck('check');
			$('input:radio[name="chkBankCode"]').prop('disabled', false);
		}
	}

	function addUCODE(thisAccId)
	{
		var task 		= "<?=$task?>";
		var DOCNUM		= document.getElementById('JournalH_Code').value;
		var DOCCODE		= document.getElementById('Manual_No').value;
		var DOCDATE		= document.getElementById('datepicker').value;
		// var ACC_ID		= document.getElementById('AccId_K').value;
		var ACC_ID		= thisAccId;
		var PDManNo 	= "";

		var formData 	= {
							PRJCODE 		: "<?=$PRJCODE?>",
							MNCODE 			: "<?=$MenuCode?>",
							DOCNUM 			: DOCNUM,
							DOCCODE 		: DOCCODE,
							DOCCODE2		: PDManNo,
							DOCDATE 		: DOCDATE,
							ACC_ID 			: ACC_ID,
							DOCTYPE 		: 'PINBUK'
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

            	$('#Manual_No').val(payCode);
            	$('#Manual_No1').val(payCode);
            }
        });
	}

	function add_header(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;

		arrItem 		= strItem.split('|');
		
		PROP_NUM		= arrItem[0];
		PROP_CODE		= arrItem[1];
		PRJCODE			= arrItem[2];
		EMP_ID			= arrItem[3];
		PROP_NOTE		= arrItem[4];
		PROP_VALUE		= arrItem[5];
		PROP_TRANSFERED	= arrItem[6];
		PROP_REM		= parseFloat(PROP_VALUE - PROP_TRANSFERED);

		document.getElementById("REF_NUM").value 			= PROP_NUM;
		document.getElementById("REF_NUMX").value 			= PROP_CODE;
		document.getElementById("REF_CODE").value 			= PROP_CODE;

		document.getElementById("JournalD_Amount").value 	= PROP_REM;
		document.getElementById("JournalD_AmountV").value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_REM)),2));

		document.getElementById("JournalK_Amount").value 	= PROP_REM;
		document.getElementById("JournalK_AmountV").value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PROP_REM)),2));
	}

	function chgAmountD(thisval)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var AccId_D			= $("#AccId_D").val();
		if(AccId_D == "")
		{
			swal("Silahkan tentukan kas/bank terlebih dahulu.",
			{
				icon:"warning"
			})
			.then(function()
			{
				swal.close();
				$('#JournalD_Amount').val(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
				$('#JournalD_AmountV').val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat)));
				return false;
			});
		}
		var Acc_Amount										= eval(thisval).value.split(",").join("");
		document.getElementById('Journal_Amount').value 	= RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat);
		document.getElementById('JournalD_Amount').value 	= RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat);
		document.getElementById('JournalD_AmountV').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat));
	}

	function chgAmountK(thisval)
	{
		var decFormat										= document.getElementById('decFormat').value;
		var AccId_K											= $("#AccId_K").val();
		if(AccId_K == "")
		{
			swal("Silahkan tentukan kas/bank terlebih dahulu.",
			{
				icon:"warning"
			})
			.then(function()
			{
				swal.close();
				$('#JournalK_Amount').val(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
				$('#JournalK_AmountV').val(doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat)));
				return false;
			});
		}
		var Acc_Amount										= eval(thisval).value.split(",").join("");
		document.getElementById('Journal_Amount').value 	= RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat);
		document.getElementById('JournalK_Amount').value 	= RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat);
		document.getElementById('JournalK_AmountV').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Amount)),decFormat));
	}
	
	function validateInData()
	{
		var JournalD_Desc 	= document.getElementById('JournalD_Desc').value;
		var JournalK_Desc 	= document.getElementById('JournalK_Desc').value;
		var AccId_D 		= document.getElementById('AccId_D').value;
		var AccId_K 		= document.getElementById('AccId_K').value;
		var AmountD			= parseFloat(document.getElementById('JournalD_Amount').value);
		var AmountK			= parseFloat(document.getElementById('JournalK_Amount').value);
		var Source 			= document.getElementById('Source').value;
		var REF_NUM 		= document.getElementById('REF_NUM').value;

		// if(Source == 1 && REF_NUM == '')
		if(Source == 2 && REF_NUM == '')
		{
			swal('<?php echo $alert15; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#REF_NUMX').focus();
			});
			return false;
		}
				
		if(JournalD_Desc == '')
		{
			swal('<?php echo $alert0; ?> (Debet)',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#JournalD_Desc').focus();
			});
			return false;
		}
				
		if(JournalK_Desc == '')
		{
			swal('<?php echo $alert0; ?> (Kredit)',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#JournalK_Desc').focus();
			});
			return false;
		}

		if(AccId_D == '')
		{
			swal('<?php echo $alert16; ?> (Debet)',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#AccId_D').focus();
			});
			return false;
		}
				
		if(AccId_K == '')
		{
			swal('<?php echo $alert16; ?> (Kredit)',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#AccId_K').focus();
			});
			return false;
		}
		
		if(AmountD != AmountK)
		{
			swal('<?php echo $alert9; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#AmountD').focus();
			});
			return false;
		}

		document.getElementById('btnSave').style.display = 'none';
		document.getElementById('btnBack').style.display = 'none';
		document.frm.submit();
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