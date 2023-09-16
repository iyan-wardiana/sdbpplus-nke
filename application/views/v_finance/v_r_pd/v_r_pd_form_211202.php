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
$JournalH_Date		= date('d/m/Y',strtotime($JournalH_Date));
$JournalH_Date_PD	= date('d/m/Y',strtotime($JournalH_Date_PD));
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
	$docalert3	= 'Anda belum men-setting akun untuk dokumen Pengeluaran Kas kategori Pinjaman Dinas. Silahkan atur akun Pinjaman Dinas dari menu pengaturan umum pada Menu Pengaturan.';
}
else
{
	$docalert1	= 'Warning';
	$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
	$docalert3	= 'You have not set up an account for the Cash Expenditure document for the Personal Loan category. Please set up a Loan Account from the General Settings menu in the Settings Menu.';
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
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'UniqCode')$UniqCode = $LangTransl;
			if($TranslCode == 'othInfo')$othInfo = $LangTransl;
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'ItmList')$ItmList = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
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
			$alert13	= "Nama penerima pinjaman dinas tidak boleh kosong.";
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
                    <div class="col-md-6">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo "Dok. Pinjaman Dinas (PD)"; ?></h3>
                            </div>
                            <div class="box-body">
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $UniqCode; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" name="JournalH_Code1" id="JournalH_Code1" size="30" value="<?php echo $DocNumber; ?>" disabled />
				                    	<input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="JournalH_Code" id="JournalH_Code" size="30" value="<?php echo $DocNumber; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJCODE_HO" id="PRJCODE_HO" size="30" value="<?php echo $PRJCODE_HO; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:400px;text-align:right" name="PRJPERIOD" id="PRJPERIOD" size="30" value="<?php echo $PRJPERIOD; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo "$Code / $Date"; ?></label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" name="Manual_No" id="Manual_No" size="30" value="<?php echo $Manual_No; ?>" readonly />
				                    </div>
				                    <div class="col-sm-1">&nbsp;</div>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="JournalH_Date" class="form-control pull-left" id="datepicker" value="<?php echo $JournalH_Date; ?>" readonly></div>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Category; ?></label>
				                    <div class="col-sm-4">
				                    	<select name="ISPERSLX" id="ISPERSLX" class="form-control select2" onChange="chgCat(this.value)" disabled>
				                          	<option value="0" <?php if($ISPERSL == 0) { ?> selected <?php } ?>> Petty Cash </option>
				                          	<option value="1" <?php if($ISPERSL == 1) { ?> selected <?php } ?>> Pinj. Dinas (PD) </option>
				                       	</select>
				                    	<input type="hidden" class="form-control" name="ISPERSL" id="ISPERSL" size="30" value="<?php echo $ISPERSL; ?>" />
				                    </div>
				                    <div class="col-sm-5" id="isPersLoan" <?php if($ISPERSL == 0) { ?> style="display: none;" <?php } ?>>
				                    	<select name="PERSL_EMPIDX" id="PERSL_EMPIDX" class="form-control select2" style="width: 100%" onChange="chgEmp(this.value)" disabled>
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
				                    	<input type="hidden" class="form-control" name="PERSL_EMPID" id="PERSL_EMPID" size="30" value="<?php echo $PERSL_EMPID; ?>" />
				                    </div>
				                </div>
			                    <script type="text/javascript">
			                    	function chgCat(catVal)
			                    	{
			                    		if(catVal == 1)
			                    		{
			                    			ACCID_PL 	= $("#ACC_ID_PERSL").val();
			                    			if(ACCID_PL == '')
			                    			{
			                    				document.getElementById('setAccPLAlert').style.display 	= '';
			                    				document.getElementById('isPersLoan').style.display 	= 'none';
			                    				document.getElementById('Journal_AmountX').readOnly 	= true;
			                    				document.getElementById('btnSave').style.display 		= 'none';
			                    			}
			                    			else
			                    			{
			                    				document.getElementById('setAccPLAlert').style.display 	= 'none';
			                    				document.getElementById('isPersLoan').style.display 	= '';
			                    				document.getElementById('Journal_AmountX').readOnly 	= false;
			                    				document.getElementById('btnSave').style.display 		= '';
			                    			}

			                    			$('#btnSelAcc').attr('disabled','disabled');
			                    		}
			                    		else
			                    		{
			                    			document.getElementById('setAccPLAlert').style.display 	= 'none';
			                    			document.getElementById('isPersLoan').style.display 	= 'none';
			                    			document.getElementById('Journal_AmountX').readOnly 	= true;
			                    			$('#btnSelAcc').removeAttr('disabled');
			                    		}
			                    	}

			                    	function chgEmp(empVal)
			                    	{
			                    		$("#acc_number").val('').trigger('change');
			                    	}
			                    </script>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Description; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="JournalH_Desc" class="form-control" id="JournalH_Desc" cols="30" readonly><?php echo $JournalH_Desc; ?></textarea>
				                        <input type="hidden" name="Journal_AmountD" id="Journal_AmountD" value="">
				                        <input type="hidden" name="Journal_AmountK" id="Journal_AmountK" value="">
				                    </div>
				                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
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
				                        	$url_AddItem	= site_url('c_finance/c_cho70d18/puSA0b28t18/?id=');
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
	                            	<div class="col-sm-8">
	                                    <label for="exampleInputEmail1"><?php echo $srcPayment; ?></label>
				                    	<select name="acc_numberX" id="acc_numberX" class="form-control select2" onChange="chgAccCB(this.value)" disabled>
				                          	<option value=""> --- </option>
				                          	<?php
											  	$theProjCode 	= $PRJCODE;
					                        	$url_AddItem	= site_url('c_finance/c_cho70d18/puSA0b28t18yXP/?id=');
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
														if($Account_Class == 3 || $balanceVal <= 0)
															$isDisabled		= 1;
														elseif($Account_Class == 4 || $balanceVal <= 0)
															$isDisabled		= 1;
					                                    ?>
					                                  <option value="<?php echo $Account_Number; ?>" <?php if($Account_Number == $acc_number) { ?> selected <?php } if($isDisabled == 1) { ?> disabled <?php } ?>>
													  	<?php
															$space1	 = 15 - strlen($Account_Number);
															$space1a = '';
															for($i=0; $i<=$space1;$i++)
															{
																$space1a = $space1a."&nbsp;";
															}
															echo "$Account_Number $space1a $Account_NameId";
														?>
					                                  </option>
					                                  <?php
					                                endforeach;
					                            }
				                            ?>
				                        </select>
				                    	<input type="hidden" class="form-control" id="acc_number" name="acc_number" size="20" value="<?php echo $acc_number; ?>" />
		                            </div>
	                            	<div class="col-sm-4">
	                                    <label for="exampleInputEmail1">Saldo</label>
				                    	<input type="text" style="text-align: right;" class="form-control" name="ACC_OPBAL" id="ACC_OPBAL" size="30" value="<?php echo number_format($ACC_OPBAL, 2); ?>" readonly />
		                            </div>
				                </div>
				                <?php
				                	$getAcc = base_url().'index.php/c_finance/c_cho70d18/getACCID/?id='.$PRJCODE;
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

						                var url     = "<?php echo $getAcc; ?>";

						                $.ajax({
						                    type: 'POST',
						                    url: url,
						                    data: {AccID: AccID},
						                    success: function(response)
						                    {
						                        document.getElementById('ACC_OPBAL').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(response)), 2));
						                    }
						                });
				                	}
				                </script>
				                <div class="form-group" <?php if($JournalH_Desc2 == '') { ?> style="display:none" <?php } ?>>
	                            	<div class="col-sm-12">
					                    <label for="inputName"><?php echo $Notes; ?></label>
					                    <textarea name="JournalH_Desc2" class="form-control" id="JournalH_Desc2" cols="30"><?php echo $JournalH_Desc2; ?></textarea>
	                            	</div>
	                            </div>
	                            <?php
	                            	if($resCAPPH > 0)
				                	{
	                            		?>
						                <div class="form-group">
			                            	<div class="col-sm-4">
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
																			<option value="2"<?php if($GEJ_STAT_PD == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																			<option value="3"<?php if($GEJ_STAT_PD == 3) { ?> selected <?php } ?> >Approved</option>
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
																			<option value="3"<?php if($GEJ_STAT_PD == 3) { ?> selected <?php } ?> >Approved</option>
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
																			<option value="3"<?php if($GEJ_STAT_PD == 3) { ?> selected <?php } ?> >Approved</option>
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
																			<option value="3"<?php if($GEJ_STAT_PD == 3) { ?> selected <?php } ?> >Approved</option>
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
			                            	<div class="col-sm-4">
							                    <label for="inputName"><?=$Amount?></label>
				                        		<input type="hidden" class="form-control" style="text-align:right" name="Journal_Amount" id="Journal_Amount" value="<?php echo $Journal_Amount; ?>" />
				                        		<input type="text" class="form-control" style="text-align:right" name="Journal_AmountX" id="Journal_AmountX" value="<?php echo number_format($Journal_Amount, 2); ?>" title="Total Jurnal" onBlur="chgAmn(this)" readonly />
			                            	</div>
			                            	<div class="col-sm-4">
							                    <label for="inputName">&nbsp;</label>
			                            		<?php
													$edited	= 0;
													if ($GEJ_STAT_PD == 1)
													{
														$edited	= 1;
													}
													elseif ($GEJ_STAT_PD == 4)
													{
														$edited	= 1;
													}

								                    if ($edited == 1)
								                    {
								                    	?>
								                            <div>
								                                <script>
								                                    var url = "<?php echo $url_AddItem;?>";
								                                    function selectAccount(theRow)
								                                    {
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
																    		"ordering": false,
							    											"bDestroy": true,
																	        "processing": true,
																	        "serverSide": true,
																			//"scrollX": false,
																			"autoWidth": true,
																			"filter": true,
																	        "ajax": "<?php echo site_url('c_finance/c_cho70d18/get_AllDataITM/?id='.$PRJCODE.'&THEROW=')?>"+theRow,
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

								                                        document.getElementById('btnModal').click();
								                                    }
								                                </script>
								                                <button class="btn btn-success" type="button" onClick="add_listAcc();" id="btnSelAcc">
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
			                            </div>
			                            <?php
			                        }
			                    ?>
			                    <script>
			                    	function chgAmn(thisval)
			                    	{
			                    		var Journal_Amount	= parseFloat(eval(thisval).value.split(",").join(""));
			                    		document.getElementById('Journal_Amount').value 	= parseFloat(Journal_Amount);
			                    		document.getElementById('Journal_AmountX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Journal_Amount)), 2));
			                    	}
								</script>
								<br>
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

                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
		                              	<th width="2%" height="25" style="text-align:left">&nbsp;</th>
		                              	<th width="8%" style="text-align:center"><?php echo $AccountNo; ?> </th>
		                              	<th width="18%" style="text-align:center"><?php echo $Description; ?> </th>
		                              	<th style="text-align:center; display:none" nowrap><?php echo $AccountPosition; ?> </th>
		                              	<th style="text-align:center; display:none" nowrap><?php echo $Tax; ?></th>
		                              	<th width="11%" style="text-align:center"><?php echo $RefNumber; ?> </th>
		                              	<th width="4%" style="text-align:center">Unit</th>
		                              	<th width="12%" style="text-align:center">Volume</th>
		                              	<th width="11%" style="text-align:center"><?php echo $Amount; ?></th>
		                              	<th width="11%" style="text-align:center"><?php echo $Remain; ?></th>
		                              	<th width="14%" style="text-align:center"><?php echo $Remarks; ?></th>
		                          	</tr>
		                            <?php					
		                            if($task == 'edit')
		                            {
		                                $sqlDET	= "SELECT A.JournalH_Code, A.Acc_Id, A.proj_Code, A.JOBCODEID,
														A.JournalD_Debet, A.JournalD_Debet_tax,
														A.JournalD_Kredit, A.JournalD_Kredit_tax, A.isDirect, A.Notes, A.ITM_CODE,
														A.Ref_Number, A.Other_Desc, A.Journal_DK, A.isTax, A.ITM_CATEG, A.ITM_VOLM, 
														A.ITM_PRICE, A.ITM_UNIT
													FROM tbl_journaldetail A
													WHERE JournalH_Code = '$JournalH_Code' 
														AND A.proj_Code = '$PRJCODE'
														AND A.Journal_DK = 'D' AND A.ISPERSL = 1";
		                                $result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;
										
										foreach($result as $row) :
											$currentRow  		= ++$i;
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

											$sqlJOBD1			= "SELECT ITM_PRICE, JOBDESC, JOBPARENT
																	FROM tbl_joblist_detail
																	WHERE JOBCODEID = '$JOBCODEID'
																		AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
											$resJOBD1			= $this->db->query($sqlJOBD1)->result();
											foreach($resJOBD1 as $rowJOBD1) :
												$ITM_PRICE		= $rowJOBD1->ITM_PRICE;
												$JODBDESC		= $rowJOBD1->JOBDESC;
												$JOBPARENT		= $rowJOBD1->JOBPARENT;
											endforeach;

											$ITM_UNIT 			= $row->ITM_UNIT;
											$Ref_Number 		= $row->Ref_Number;
											$Other_Desc 		= $row->Other_Desc;
											$Journal_DK 		= $row->Journal_DK;
											$isTax 				= $row->isTax;
											
											$ITM_VOLMBG			= 0;
											$ITM_BUDG			= 0;
											$ITM_USED			= 0;
											$ITM_USED_AM		= 0;
											$sqlJOBD			= "SELECT ITM_VOLM AS ITM_VOLMBG, ITM_BUDG,
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
												$sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'";
												$resITM 	= $this->db->query($sqlITM)->result();
												foreach($resITM as $rowITM) :
													$ITM_NAME 	= $rowITM->ITM_NAME;
												endforeach;
											}
											else
											{
												$sqlITM		= "SELECT Account_NameId FROM tbl_chartaccount WHERE Account_Number = '$Acc_Id'";
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
											
											$BUDG_REMVOLM	= $BUDG_REMVOLM - $ITM_USEDR;
											$BUDG_REMAMNT	= $BUDG_REMAMN - $ITM_USEDR_AM;

											$JobView		= "$JOBCODEID - $JODBDESC";
											$JobView 		= wordwrap($JobView, 50, "<br>", TRUE);

											$JOBDESCH		= "";
											$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
											$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
											foreach($resJOBDESC as $rowJOBDESC) :
												$JOBDESCH	= $rowJOBDESC->JOBDESC;
											endforeach;

											$JOBDESCH 		= wordwrap($JOBDESCH, 50, "<br>", TRUE);

											$disButtonR		= 0;
											if($BUDG_REMAMNT < $AmountV)
											{
												$disButtonR	= 1;
											}
											
											if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}
											?> 
		                                    <tr id="tr_<?php echo $currentRow; ?>">
											<td style="text-align:center; vertical-align: middle;">
												<?php
													if($GEJ_STAT_PD == 1 || $GEJ_STAT_PD == 4)
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
										 	<td style="text-align:center; vertical-align: middle;">
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
												  	<div style="margin-left: 15px; font-style: italic;">
												  		<i class="text-muted fa fa-rss"></i>&nbsp;&nbsp;<?php echo $JOBDESCH; ?>
												  	</div>
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
										  	<td style="text-align:left; vertical-align: middle;">
										 		<?php if ($edited == 1) { ?>
										 			<input type="text" name="data[<?php echo $currentRow; ?>][Ref_Number]" id="data<?php echo $currentRow; ?>Ref_Number" value="<?php echo $Ref_Number; ?>" class="form-control" style="max-width:200px;" placeholder="<?php echo $RefNumber; ?>" >
										 		<?php } else { ?>
										 			<?php echo $Ref_Number; ?>
										 			<input type="hidden" name="data[<?php echo $currentRow; ?>][Ref_Number]" id="data<?php echo $currentRow; ?>Ref_Number" value="<?php echo $Ref_Number; ?>" class="form-control" style="max-width:200px;" placeholder="<?php echo $RefNumber; ?>" >
										 		<?php } ?>
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
										 			<input type="text" class="form-control" style="text-align:right" name="ITM_VOLM<?php echo $currentRow; ?>" id="ITM_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_VOLM, 2); ?>" placeholder="Volume" onBlur="chgVolume(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);">
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
										 			<input type="text" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $currentRow; ?>" id="JournalD_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($AmountV, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);">
										 		<?php } else { ?>
										 			<?php echo number_format($AmountV, 2); ?>
										 			<input type="hidden" class="form-control" style="text-align:right" name="JournalD_Amount<?php echo $currentRow; ?>" id="JournalD_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($AmountV, 2); ?>" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,<?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);">
										 		<?php } ?>
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JournalD_Amount]" id="data<?php echo $currentRow; ?>JournalD_Amount" value="<?php echo $AmountV; ?>" class="form-control" style="max-width:150px;" >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][isDirect]" id="data<?php echo $currentRow; ?>isDirect" value="1" class="form-control" style="max-width:150px;" >
		                                    </td>
		                                    <td style="text-align:right; vertical-align: middle; <?php if($disButtonR == 1) { ?> background-color:#F00<?php } ?>">
										 		<?php if ($edited == 1) { ?>
										 			<input type="text" class="form-control" style="text-align:right" name="JournalD_AmountR<?php echo $currentRow; ?>" id="JournalD_AmountR<?php echo $currentRow; ?>" value="<?php echo number_format($BUDG_REMAMNT, 2); ?>" placeholder="<?php echo $Remain; ?>" onKeyPress="return isIntOnlyNew(event);" disabled>
										 		<?php } else { ?>
										 			<?php echo number_format($BUDG_REMAMNT, 2); ?>
										 			<input type="hidden" class="form-control" style="text-align:right" name="JournalD_AmountR<?php echo $currentRow; ?>" id="JournalD_AmountR<?php echo $currentRow; ?>" value="<?php echo number_format($BUDG_REMAMNT, 2); ?>" placeholder="<?php echo $Remain; ?>" onKeyPress="return isIntOnlyNew(event);" disabled>
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
		                                  <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
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

										if($ISAPPROVE == 1)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
										}
									
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
						                    	<a href="#itm1" data-toggle="tab"><?php echo $ItmList; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
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
                                                    	<button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>&nbsp;
                                      					<button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
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
	                        <?php echo "PERHATIKAN! Daftar item yang ditampilkan hanyalah item yang (1) Item bukan berkategori Material (M) dan Alat (T).<br>(2) Sudah disetting Akun Penggunaan di Daftar Material."; ?>
	                    </div>
				    </div>
				</div>

				<script type="text/javascript">
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
							console.log('aA')
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
	
	function add_listAcc(strItem) 
	{
		var acc_number		= document.getElementById('acc_number').value;
		if(acc_number == '')
		{
			swal('<?php echo $alert11; ?>',
			{
				icon: "warning",
			});
			document.getElementById('acc_number').focus();
			return false;;
		}
		
		var objTable, objTR, objTD, intIndex;
		
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Account Number
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = '<input type="hidden" name="data['+intIndex+'][JournalH_Code]" id="data'+intIndex+'JournalH_Code" value="<?php echo $JournalH_Code; ?>" class="form-control" style="max-width:150px;" ><input type="text" name="data['+intIndex+'][Acc_Id]" id="data'+intIndex+'Acc_Id" value="'+Acc_Id+'" class="form-control" style="max-width:150px;" onClick="selectAccount('+intIndex+');" placeholder="<?php echo $SelectAccount; ?>" ><input type="hidden" name="data['+intIndex+'][proj_Code]" id="data'+intIndex+'proj_Code" value="<?php echo $proj_Code; ?>" class="form-control" style="max-width:150px;" >';
		
		// Account Description
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = '<div id="div_jdesc_'+intIndex+'" style="display: none;"></div><input type="text" name="data['+intIndex+'][JournalD_Desc]" id="data'+intIndex+'JournalD_Desc" value="'+JournalD_Desc+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $Description; ?>" readonly><input type="hidden" name="data['+intIndex+'][ITM_CODE]" id="data'+intIndex+'ITM_CODE" value="'+ITM_CODE+'" class="form-control" style="max-width:500px;" placeholder="<?php echo $ItemCode; ?>"><input type="hidden" name="data['+intIndex+'][ITM_CATEG]" id="data'+intIndex+'ITM_CATEG" value="'+ITM_GROUP+'" class="form-control" style="max-width:500px;" ><input type="text" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" style="max-width:500px;" >';
		
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
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Ref_Number]" id="data'+intIndex+'Ref_Number" value="'+Ref_Number+'" class="form-control" style="max-width:200px;" placeholder="<?php echo $RefNumber; ?>" >';
		
		// Item Unit
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = '<div id="div_unit_'+intIndex+'" style="display: none;"></div><input type="text" name="data'+intIndex+'ITM_UNIT" id="ITM_UNIT'+intIndex+'" value="'+ITM_UNIT+'" class="form-control" style="min-width:50px;" readonly><input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:150px;" >';
		
		// Volume
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right" name="ITM_VOLM'+intIndex+'" id="ITM_VOLM'+intIndex+'" value="'+ITM_VOLM+'" placeholder="Volume" onBlur="chgVolume(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][ITM_VOLM]" id="data'+intIndex+'ITM_VOLM" value="'+ITM_VOLM+'" class="form-control" style="max-width:150px;"><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:150px;" ><input type="hidden" name="data'+intIndex+'ITM_REM" id="data'+intIndex+'ITM_REM" value="0" class="form-control" style="max-width:150px;" ><input type="hidden" name="data'+intIndex+'ITM_REMAMN" id="data'+intIndex+'ITM_REMAMN" value="0" class="form-control" style="max-width:150px;" >'; 
		
		// Account Amount
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right" name="JournalD_Amount'+intIndex+'" id="JournalD_Amount'+intIndex+'" value="'+JournalD_Debet+'" placeholder="<?php echo $Amount; ?>" onBlur="chgAmount(this,'+intIndex+')" onKeyPress="return isIntOnlyNew(event);"><input type="hidden" name="data['+intIndex+'][JournalD_Amount]" id="data'+intIndex+'JournalD_Amount" value="'+JournalD_Debet+'" class="form-control" style="max-width:150px;" ><input type="hidden" name="data['+intIndex+'][isDirect]" id="data'+intIndex+'isDirect" value="1" class="form-control" style="max-width:150px;" >';
		
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
		document.getElementById('ITM_VOLM'+intIndex).value 					= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)),decFormat));
		document.getElementById('totalrow').value = intIndex;
	}
	
	function chgAmount(thisval, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var Acc_Amount		= parseFloat(eval(thisval).value.split(",").join(""));
		var Rem_BudAmn		= parseFloat(document.getElementById('data'+row+'ITM_REMAMN').value);
		var ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
		
		if(Acc_Amount > Rem_BudAmn)
		{
			/*var r = confirm("Out of budget, ignore?");
			if(r == true)
			{
				Acc_Volume	= parseFloat(Acc_Amount / ITM_PRICE);
			}
			else
			{
				Acc_Volume	= parseFloat(Rem_BudAmn / ITM_PRICE);
				Acc_Amount	= parseFloat(Rem_BudAmn);
			}*/
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
	}
	
	function chgVolume(thisval, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var Acc_Volume		= parseFloat(eval(thisval).value.split(",").join(""));
		var Rem_Budget		= parseFloat(document.getElementById('data'+row+'ITM_REM').value);
		var ITM_PRICE		= parseFloat(document.getElementById('data'+row+'ITM_PRICE').value);
		
		if(Acc_Volume > Rem_Budget)
		{
			/*var r = confirm("Out of budget, ignore?");
			if(r == true)
			{
				Acc_Volume	= parseFloat(Acc_Volume);
			}
			else
			{
				Acc_Volume	= parseFloat(Rem_Budget);
			}
			*/
			// PERUBAHAN BERDASARKAN MS.201962000021
			var Rem_BudgetV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Rem_Budget)),decFormat));
			swal("Out of budget, max volm "+Rem_BudgetV)
			Acc_Volume	= parseFloat(Rem_Budget);
		}
		
		var TOTPRC			= parseFloat(ITM_PRICE) * parseFloat(Acc_Volume);
		
		document.getElementById('ITM_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Acc_Volume)),decFormat));
		document.getElementById('data'+row+'ITM_VOLM').value 	= parseFloat(Math.abs(Acc_Volume));
		document.getElementById('JournalD_Amount'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRC)),decFormat));
		document.getElementById('data'+row+'JournalD_Amount').value 	= parseFloat(Math.abs(TOTPRC));

		countAmount()
	}
	
	function countAmount()
	{
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
		document.getElementById('Journal_AmountX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totAmountD)), 2));
		document.getElementById('Journal_AmountD').value 	= totAmountD;
		document.getElementById('Journal_AmountK').value 	= totAmountK;
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
		document.getElementById('data'+theRow+'ITM_PRICE').value		= ITM_PRICE;
		document.getElementById('data'+theRow+'JOBCODEID').value		= JOBCODEID;
		document.getElementById('data'+theRow+'JournalD_Desc').value	= JournalD_Desc;
		document.getElementById('div_jdesc_'+theRow).innerHTML			= JournalD_Desc;
		if(ITM_UNIT == 'LS')
		{
			document.getElementById('ITM_VOLM'+theRow).disabled = true;
		}
		else
		{
			document.getElementById('ITM_VOLM'+theRow).disabled = false;
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
				var Acc_Id 			= document.getElementById('data'+i+'Acc_Id').value;
				var JournalD_Desc 	= document.getElementById('data'+i+'JournalD_Desc').value;
				var JournalD_Pos 	= document.getElementById('data'+i+'JournalD_Pos').value;
				var isTax			= document.getElementById('data'+i+'isTax').value;
				var Ref_Number		= document.getElementById('data'+i+'Ref_Number').value;
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
				
				if(Ref_Number == '')
				{
					swal('<?php echo $alert5; ?>',
					{
						icon: "warning",
					});
					document.getElementById('data'+i+'Ref_Number').focus();
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