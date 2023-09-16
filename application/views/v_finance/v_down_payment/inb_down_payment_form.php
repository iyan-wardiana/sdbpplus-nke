<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 04 Juli 2018
	* File Name		= inb_down_payment_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows 	= $row->Display_Rows;
	$decFormat 		= $row->decFormat;
	$APPLEV 		= $row->APPLEV;
endforeach;
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$currentRow = 0;

$DP_NUM 		= $default['DP_NUM'];
$DocNumber		= $default['DP_NUM'];
$DP_CODE		= $default['DP_CODE'];
$DP_DATE		= $default['DP_DATE'];
$JournalY 		= date('Y', strtotime($DP_DATE));
$JournalM 		= date('n', strtotime($DP_DATE));
// $DP_DATE		= date('m/d/Y', strtotime($DP_DATE));
$DP_DATE		= date('d/m/Y', strtotime($DP_DATE));
$PRJCODE		= $default['PRJCODE'];
$SPLCODE		= $default['SPLCODE'];
$DP_REFNUM		= $default['DP_REFNUM'];
$DP_REFCODE		= $default['DP_REFCODE'];
$DP_AMOUNT		= $default['DP_AMOUNT'];
$DP_AMOUNT_PPN	= $default['DP_AMOUNT_PPN'];
$DP_AMOUNT_USED	= $default['DP_AMOUNT_USED'];
$TTK_NUM		= $default['TTK_NUM'];
$TTK_CODE		= $default['TTK_CODE'];
$DP_NOTES		= $default['DP_NOTES'];
$DP_NOTES2		= $default['DP_NOTES2'];
$DP_STAT		= $default['DP_STAT'];
$lastPattNo		= $default['Patt_Number'];

$DP_PERC		= $default['DP_PERC'];
$DP_REFAMOUNT	= $default['DP_REFAMOUNT'];

$DP_AMOUNT_PPH	= $default['DP_AMOUNT_PPH'];
$DP_AMOUNT_PPHP	= $default['DP_AMOUNT_PPHP'];
$DP_AMOUN_TOT	= $default['DP_AMOUN_TOT'];

// GET Journal Lock
	$disabled 	= 0;
	$getJLock 	= "SELECT * FROM tbl_journal_lock 
					WHERE LockY = $JournalY AND LockM = $JournalM AND isLock = 1 AND UserLock != '$DefEmp_ID'";
	$resJLock 	= $this->db->query($getJLock);
	$countJLock = $resJLock->num_rows();
	if($countJLock == 1) $disabled = 1;

$SPLDESC		= "";
$s_SPL			= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
$r_SPL			= $this->db->query($s_SPL)->result();
foreach($r_SPL as $rw_SPL):
    $SPLDESC	= $rw_SPL->SPLDESC;
endforeach;
$SPLCODED 		= "$SPLCODE - $SPLDESC";

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
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
			if($TranslCode == 'DocNumber')$Doc_Number = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'DPAkum')$DPAkum = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'WOList')$WOList = $LangTransl;
			if($TranslCode == 'POList')$POList = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
            if($TranslCode == 'TaxSerList')$TaxSerList = $LangTransl;
            if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
            if($TranslCode == 'PPNValue')$PPNValue = $LangTransl;
			if($TranslCode == 'splInvNo')$splInvNo = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Peringatan';
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$alert1		= 'Silahkan pilih nama supplier.';
			$alert2		= 'Masukan nilai Uang Muka.';
			$alert3		= "Silahkan pilih status persetujuan.";
			$alert4		= "Silahkan tulis alasan revisi dokumen.";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Warning';
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$alert1	= 'Please select a supplier name.';
			$alert2	= 'Please input Down Payment Amount.';
			$alert3	= "Please select an approval status.";
			$alert4	= "Plese input the reason why you revise the document.";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];

			$PRJCODE_LEV 	= $PRJCODE;
			
			// DP_NUM - DP_AMOUNT
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DP_NUM'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				//$appReady	= $APP_STEP;
				//if($resC_App == 0)
				//echo "APP_STEP = $APP_STEP = $resC_App = $MAX_STEP";
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
				$APPROVE_AMOUNT = $DP_AMOUNT;
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
		    <?php echo "$mnName ($PRJCODE)"; ?>
		    <small><?php echo $PRJNAME; ?></small>
		    </h1>
		</section>

		<section class="content">
            <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                <input type="text" name="DP_REFNUM" id="DP_REFNUM" value="<?php echo $DP_REFNUM; ?>" />
                <input type="text" name="REF_TYPE" id="REF_TYPE" value="" />
                <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
            </form>

            <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
                <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
   				<input type="hidden" name="rowCount" id="rowCount" value="0">
                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
            	<input type="hidden" class="form-control" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
            	<input type="hidden" class="form-control" id="DP_AMOUNT_PPN" name="DP_AMOUNT_PPN" size="20" value="<?php echo $DP_AMOUNT_PPN; ?>" />
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
                    <div class="col-md-6">
                        <div class="box box-warning">
                            <div class="box-header with-border" style="display: none;">
                                <h3 class="box-title"><?php //echo $DetInfo; ?></h3>
                            </div>
                            <div class="box-body">
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Doc_Number ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" style="max-width:195px" name="DP_NUM1" id="DP_NUM1" value="<?php echo $DP_NUM; ?>" disabled >
		                       			<input type="text" class="textbox" name="DP_NUM" id="DP_NUM" size="30" value="<?php echo $DP_NUM; ?>" />
		                                <input type="text" name="Patt_Number" id="Patt_Number" value="">
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
		                          	<div class="col-sm-5">
		                                <input type="text" class="form-control" name="DP_CODE" id="DP_CODE" value="<?php echo $DP_CODE; ?>" readonly >
		                          	</div>
		                          	<div class="col-sm-4 pull-right">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="DP_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $DP_DATE; ?>" readonly>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">SPK/OP</label>
		                          	<div class="col-sm-4">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
		                                        <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
		                                    </div>
		                                    <input type="text" class="form-control" name="DP_REFCODE" id="DP_REFCODE" value="<?php echo $DP_REFCODE; ?>"  data-toggle="modal" data-target="#mdl_addSPK" <?php if($DP_STAT != 1 && $DP_STAT != 6) { ?> disabled <?php } ?>>
		                                    <input type="hidden" class="form-control" name="DP_REFNUM" id="DP_REFNUM" style="max-width:200px" value="<?php echo $DP_REFNUM; ?>" >
		                                </div>
		                          	</div>
		                          	<div class="col-sm-1">
		                          		<label for="inputName" class="control-label">TTK</label>
		                            </div>
		                          	<div class="col-sm-4">
		                                <input type="hidden" class="form-control" name="TTK_NUM" id="TTK_NUM" value="<?php echo $TTK_NUM; ?>">
		                                <input type="hidden" class="form-control" name="TTK_CODE" id="TTK_CODE" value="<?php echo $TTK_CODE; ?>">
		                                <input type="text" class="form-control" name="TTK_CODE1" id="TTK_CODE1" value="<?php echo $TTK_CODE; ?>" readonly>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SupplierName ?> </label>
		                          	<div class="col-sm-6">
		                                <input type="hidden" class="form-control" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" >
		                                <input type="text" class="form-control" name="SPLCODED" id="SPLCODED" value="<?php echo $SPLCODED; ?>" readonly >
		                          	</div>
		                          	<div class="col-sm-3">
		                                <input type="text" class="form-control" style="text-align: right;" name="DP_REFAMOUNT1" id="DP_REFAMOUNT1" value="<?php echo number_format($DP_REFAMOUNT, 2); ?>" title="Nilai SPK/OP" readonly>
		                                <input type="hidden" class="form-control" style="text-align: right;" name="DP_REFAMOUNT" id="DP_REFAMOUNT" value="<?php echo $DP_REFAMOUNT; ?>">
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">DP / <?php echo $Used ?></label>
		                          	<div class="col-sm-2">
		                                <input type="text" class="col-sm-2 form-control" style="text-align:right" name="DP_PERC1" id="DP_PERC1" value="<?php echo number_format($DP_PERC, 2); ?>" onBlur="getPercDP(this);" readonly title="Persentase" >
		                                <input type="hidden" class="form-control" name="DP_PERC" id="DP_PERC" value="<?php echo $DP_PERC; ?>" >
		                          	</div>
		                          	<div class="col-sm-4">
		                                <input type="text" class="col-sm-3 form-control" style="text-align:right" name="DP_AMOUNT1" id="DP_AMOUNT1" value="<?php echo number_format($DP_AMOUNT, 2); ?>" onBlur="getValueDP(this);" readonly title="Nilai DP" >
		                                <input type="hidden" class="form-control" name="DP_AMOUNT" id="DP_AMOUNT" value="<?php echo $DP_AMOUNT; ?>" >
		                          	</div>
		                          	<div class="col-sm-3">
		                                <input type="text" class="form-control" style="text-align:right" name="DP_AMOUNT_USED1" id="DP_AMOUNT_USED1" value="<?php echo number_format($DP_AMOUNT_USED, 2); ?>" title="<?php echo $Used ?>" disabled >
		                                <input type="hidden" class="form-control" name="DP_AMOUNT_USED" id="DP_AMOUNT_USED" value="<?php echo $DP_AMOUNT_USED; ?>" >
		                            </div>
		                        </div>
		                        <?php
									$url_selWO	= site_url('c_finance/c_40wnp1ymt/s3l4llW_0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
								?>
								<script>
		                            var url1 = "<?php echo $url_selWO;?>";
		                            function pleaseCheck()
		                            {
		                                title = 'Select Item';
		                                w = 1000;
		                                h = 550;
		                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                var left = (screen.width/2)-(w/2);
		                                var top = (screen.height/2)-(h/2);
										
										return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                            }
		                        </script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">PPh / Total DP </label>
		                          	<div class="col-sm-2">
		                                <input type="text" class="col-sm-2 form-control" style="text-align:right" name="DP_AMOUNT_PPHP1" id="DP_AMOUNT_PPHP1" value="<?php echo number_format($DP_AMOUNT_PPHP, 2); ?>" title="PPh (%)" readonly >
		                                <input type="hidden" class="form-control" name="DP_AMOUNT_PPHP" id="DP_AMOUNT_PPHP" value="<?=$DP_AMOUNT_PPHP?>" >
		                            </div>
		                          	<div class="col-sm-4">
		                                <input type="text" class="col-sm-2 form-control" style="text-align:right" name="DP_AMOUNT_PPH1" id="DP_AMOUNT_PPH1" value="<?php echo number_format($DP_AMOUNT_PPH, 2); ?>" title="PPh Nilai" readonly >
		                                <input type="hidden" class="form-control" name="DP_AMOUNT_PPH" id="DP_AMOUNT_PPH" value="<?=$DP_AMOUNT_PPH?>" >
		                            </div>
		                          	<div class="col-sm-3">
		                                <input type="text" class="col-sm-2 form-control" style="text-align:right" name="DP_AMOUN_TOT1" id="DP_AMOUN_TOT1" value="<?php echo number_format($DP_AMOUN_TOT, 2); ?>" title="Total DP" readonly >
		                                <input type="hidden" class="form-control" name="DP_AMOUN_TOT" id="DP_AMOUN_TOT" value="<?=$DP_AMOUN_TOT?>" >
		                          	</div>
		                        </div>
	                    	</div>
	                	</div>
	                </div>
                    <div class="col-md-6">
                        <div class="box box-success">
                            <div class="box-header with-border" style="display: none;">
                                <h3 class="box-title"><?php //echo $DetInfo; ?></h3>
                            </div>
                            <div class="box-body">
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="DP_NOTES1"  id="DP_NOTES1" style="height:60px" disabled><?php echo $DP_NOTES; ?></textarea>
		                                <textarea class="form-control" name="DP_NOTES"  id="DP_NOTES" style="height:70px; display: none;"><?php echo $DP_NOTES; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="DP_NOTES2"  id="DP_NOTES2" style="height:55px"><?php echo $DP_NOTES2; ?></textarea>
		                          	</div>
		                        </div>
		                        <!--
		                        	APPROVE STATUS
		                            1 - New
		                            2 - Confirm
		                            3 - Approve
		                        -->
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $DP_STAT; ?>">
		                                <?php
											// START : FOR ALL APPROVAL FUNCTION								
											if($disableAll == 0)
											{
												if($canApprove == 1)
												{
													$disButton	= 0;
													$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$DP_NUM' AND AH_APPROVER = '$DefEmp_ID'";
													$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
													if($resCAPPHE > 0)
														$disButton	= 1;
													?>
														<select name="DP_STAT" id="DP_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
															<option value="0"> --- </option>
															<option value="3"<?php if($DP_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
															<option value="4"<?php if($DP_STAT == 4) { ?> selected <?php } ?>>Revised</option>
															<option value="5"<?php if($DP_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
															<!-- <option value="6"<?php if($DP_STAT == 6) { ?> selected <?php } ?>>Closed</option> -->
															<option value="7"<?php if($DP_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
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
		                        <div class="form-group">
		                        	<label for="inputName" class="col-sm-3 control-label"><?php //echo $Status ?> </label>
		                          	<div class="col-sm-9">
		                            	<?php
											if($disableAll == 0)
											{
												if(($DP_STAT == 2 || $DP_STAT == 7) && $canApprove == 1)
												{
													?>
														<button class="btn btn-primary" id="btnSave">
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
											}
											$backURL	= site_url('c_finance/c_40wnp1ymt/in20x_40wnp1ymt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
											echo anchor("$backURL",'<button class="btn btn-danger" id="btnBack" type="button"><i class="fa fa-reply"></i></button>');
										?>
		                            </div>
		                        </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<i class="fa fa-qrcode"></i>
								<h3 class="box-title"><?=$TaxSerList?></h3>

					          	<div class="box-tools pull-right">
						            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					          	</div>
							</div>
							<div class="box-body">
		                        <div class="search-table-outter">
		                            <table id="tbl_tax" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                        <thead>
                                            <tr>
												<th width="80" style="text-align:center"><?php echo "Tgl. Faktur Pajak"; ?></th>
												<th width="150" style="text-align:center" nowrap><?php echo "No. Seri Pajak"; ?></th>
												<th width="80" style="text-align:center"><?php echo "Tgl. Kwitansi"; ?></th>
												<th width="150" style="text-align:center" nowrap><?php echo $splInvNo; ?></th>
												<th width="100" style="text-align:center" nowrap><?php echo $AmountReceipt; ?></th>
												<th width="100" style="text-align:center" nowrap><?php echo $PPNValue; ?></th>
						                  	</tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
		                
				<?php
                    $DOC_NUM	= $DP_NUM;
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
                            <?php
                                if($APPROVER_1 != '')
                                {
                                    $CN_1		= $EMPN_1;
                                    $boxCol_1	= "red";
                                    $sqlCAPPH_1	= "tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '1'";
                                    $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
                                    if($resCAPPH_1 == 0)
                                    {
                                        $Approver	= $NotYetApproved;
                                        $class		= "glyphicon glyphicon-remove-sign";
                                        $APPROVED_1	= "Not Set";
                                    }
                                    else
                                    {
                                        $Approver	= $Approved;
                                        $boxCol_1	= "green";
                                        $class		= "glyphicon glyphicon-ok-sign";
                                        
                                        $APPROVED_1	= '';
                                        $AH_STAT_1	= 0;
                                        $sqlAPPH_1	= "SELECT A.AH_APPROVED, A.AH_STAT, B.First_Name, B.Last_Name FROM tbl_approve_hist A 
                                                            INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                        WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '1'";
                                        $resAPPH_1	= $this->db->query($sqlAPPH_1)->result();
                                        foreach($resAPPH_1 as $rowAPPH_1):
                                            $APPROVED_1	= $rowAPPH_1->AH_APPROVED;
                                            $AH_STAT_1	= $rowAPPH_1->AH_STAT;
                                            $FN_1		= $rowAPPH_1->First_Name;
                                            $LN_1		= $rowAPPH_1->Last_Name;
                                            $CN_1		= $FN_1." ".$LN_1;
                                        endforeach;
                                        if($APPROVED_1 == '')
                                        {
                                            $boxCol_1	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_1	= "Not Set";
                                        }
                                        if($DP_STAT == 5)
                                        {
                                            $boxCol_1	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_1	= "Not Set";
                                            $Approver	= $rejected;
                                        }
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_1; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $CN_1; ?></span>
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
                                if($APPROVER_2 != '')
                                {
                                    $CN_2		= $EMPN_2;
                                    $boxCol_2	= "red";
                                    $sqlCAPPH_2	= "tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '2'";
                                    $resCAPPH_2	= $this->db->count_all($sqlCAPPH_2);
                                    if($resCAPPH_2 == 0)
                                    {
                                        $Approver	= $NotYetApproved;
                                        $class		= "glyphicon glyphicon-remove-sign";
                                        $APPROVED_2	= "Not Set";
                                    }
                                    else
                                    {
                                        $Approver	= $Approved;
                                        $boxCol_2	= "green";
                                        $class		= "glyphicon glyphicon-ok-sign";
                                        
                                        $APPROVED_2	= '';
                                        $AH_STAT_2	= 0;
                                        $sqlAPPH_2	= "SELECT A.AH_APPROVED, A.AH_STAT, B.First_Name, B.Last_Name FROM tbl_approve_hist A 
                                                            INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                        WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '2'";
                                        $resAPPH_2	= $this->db->query($sqlAPPH_2)->result();
                                        foreach($resAPPH_2 as $rowAPPH_2):
                                            $APPROVED_2	= $rowAPPH_2->AH_APPROVED;
                                            $AH_STAT_2	= $rowAPPH_2->AH_STAT;
                                            $FN_2		= $rowAPPH_2->First_Name;
                                            $LN_2		= $rowAPPH_2->Last_Name;
                                            $CN_2		= $FN_2." ".$LN_2;
                                        endforeach;
                                        if($APPROVED_2 == '')
                                        {
                                            $boxCol_2	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_2	= "Not Set";
                                        }
                                        if($DP_STAT == 5)
                                        {
                                            $boxCol_2	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_2	= "Not Set";
                                            $Approver	= $rejected;
                                        }
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_2; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $CN_2; ?></span>
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
                                if($APPROVER_3 != '')
                                {
                                    $CN_3		= $EMPN_3;
                                    $boxCol_3	= "red";
                                    $sqlCAPPH_3	= "tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '3'";
                                    $resCAPPH_3	= $this->db->count_all($sqlCAPPH_3);
                                    if($resCAPPH_3 == 0)
                                    {
                                        $Approver	= $NotYetApproved;
                                        $class		= "glyphicon glyphicon-remove-sign";
                                        $APPROVED_3	= "Not Set";
                                    }
                                    else
                                    {
                                        $Approver	= $Approved;
                                        $boxCol_3	= "green";
                                        $class		= "glyphicon glyphicon-ok-sign";
                                        
                                        $APPROVED_3	= '';
                                        $AH_STAT_3	= 0;
                                        $sqlAPPH_3	= "SELECT A.AH_APPROVED, A.AH_STAT, B.First_Name, B.Last_Name FROM tbl_approve_hist A 
                                                            INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                        WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '3'";
                                        $resAPPH_3	= $this->db->query($sqlAPPH_3)->result();
                                        foreach($resAPPH_3 as $rowAPPH_3):
                                            $APPROVED_3	= $rowAPPH_3->AH_APPROVED;
                                            $AH_STAT_3	= $rowAPPH_3->AH_STAT;
                                            $FN_3		= $rowAPPH_3->First_Name;
                                            $LN_3		= $rowAPPH_3->Last_Name;
                                            $CN_3		= $FN_3." ".$LN_3;
                                        endforeach;
                                        if($APPROVED_3 == '')
                                        {
                                            $boxCol_3	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_3	= "Not Set";
                                        }
                                        if($DP_STAT == 5)
                                        {
                                            $boxCol_3	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_3	= "Not Set";
                                            $Approver	= $rejected;
                                        }
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_3; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $CN_3; ?></span>
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
                                if($APPROVER_4 != '')
                                {
                                    $CN_4		= $EMPN_4;
                                    $boxCol_4	= "red";
                                    $sqlCAPPH_4	= "tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '4'";
                                    $resCAPPH_4	= $this->db->count_all($sqlCAPPH_4);
                                    if($resCAPPH_4 == 0)
                                    {
                                        $Approver	= $NotYetApproved;
                                        $class		= "glyphicon glyphicon-remove-sign";
                                        $APPROVED_4	= "Not Set";
                                    }
                                    else
                                    {
                                        $Approver	= $Approved;
                                        $boxCol_4	= "green";
                                        $class		= "glyphicon glyphicon-ok-sign";
                                        
                                        $APPROVED_4	= '';
                                        $AH_STAT_4	= 0;
                                        $sqlAPPH_4	= "SELECT A.AH_APPROVED, A.AH_STAT, B.First_Name, B.Last_Name FROM tbl_approve_hist A 
                                                            INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                        WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '4'";
                                        $resAPPH_4	= $this->db->query($sqlAPPH_4)->result();
                                        foreach($resAPPH_4 as $rowAPPH_4):
                                            $APPROVED_4	= $rowAPPH_4->AH_APPROVED;
                                            $AH_STAT_4	= $rowAPPH_4->AH_STAT;
                                            $FN_4		= $rowAPPH_4->First_Name;
                                            $LN_4		= $rowAPPH_4->Last_Name;
                                            $CN_4		= $FN_4." ".$LN_4;
                                        endforeach;
                                        if($APPROVED_4 == '')
                                        {
                                            $boxCol_4	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_4	= "Not Set";
                                        }
                                        if($DP_STAT == 5)
                                        {
                                            $boxCol_4	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_4	= "Not Set";
                                            $Approver	= $rejected;
                                        }
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_4; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $CN_4; ?></span>
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
                                if($APPROVER_5 != '')
                                {
                                    $CN_5		= $EMPN_5;
                                    $boxCol_5	= "red";
                                    $sqlCAPPH_5	= "tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '5'";
                                    $resCAPPH_5	= $this->db->count_all($sqlCAPPH_5);
                                    if($resCAPPH_5 == 0)
                                    {
                                        $Approver	= $NotYetApproved;
                                        $class		= "glyphicon glyphicon-remove-sign";
                                        $APPROVED_5	= "Not Set";
                                    }
                                    else
                                    {
                                        $Approver	= $Approved;
                                        $boxCol_5	= "green";
                                        $class		= "glyphicon glyphicon-ok-sign";
                                        
                                        $APPROVED_5	= '';
                                        $AH_STAT_5	= 0;
                                        $sqlAPPH_5	= "SELECT A.AH_APPROVED, A.AH_STAT, B.First_Name, B.Last_Name FROM tbl_approve_hist A 
                                                            INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                        WHERE A.AH_CODE = '$DOC_NUM' AND A.AH_APPLEV = '5'";
                                        $resAPPH_5	= $this->db->query($sqlAPPH_5)->result();
                                        foreach($resAPPH_5 as $rowAPPH_5):
                                            $APPROVED_5	= $rowAPPH_5->AH_APPROVED;
                                            $AH_STAT_5	= $rowAPPH_5->AH_STAT;
                                            $FN_5		= $rowAPPH_5->First_Name;
                                            $LN_5		= $rowAPPH_5->Last_Name;
                                            $CN_5		= $FN_5." ".$LN_5;
                                        endforeach;
                                        if($APPROVED_5 == '')
                                        {
                                            $boxCol_5	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_5	= "Not Set";
                                        }
                                        if($DP_STAT == 5)
                                        {
                                            $boxCol_5	= "yellow";
                                            $class		= "glyphicon glyphicon-info-sign";
                                            $APPROVED_5	= "Not Set";
                                            $Approver	= $rejected;
                                        }
                                    }
                                ?>
                                    <div class="col-md-3">
                                        <div class="info-box bg-<?php echo $boxCol_5; ?>">
                                            <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $Approver; ?></span>
                                                <span class="info-box-number"><?php echo $CN_5; ?></span>
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
            </form>
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
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker1').datepicker({
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
			let DOC_DATE 	= $('#datepicker1').val();
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
					// 	// $('#DP_STAT>option[value="3"]').attr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }
					// else
					// {
					// 	// $('#alrtLockJ').css('display','none'); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#DP_STAT>option[value="3"]').removeAttr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#DP_STAT').removeAttr('disabled','disabled');
							// $('#DP_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = '';
							// $('#DP_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#DP_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#DP_STAT').removeAttr('disabled','disabled');
							// $('#DP_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							// $('#DP_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#DP_STAT').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}
				}
			});
		}
    // END : LOCK PROCEDURE
	
	$(document).ready(function()
	{
		TTK_NUM 	= "<?=$TTK_NUM?>";
		PRJCODE 	= "<?=$PRJCODE?>";
		collREF 	= TTK_NUM+'~'+PRJCODE;

    	$('#tbl_tax').DataTable(
    	{
    		"destroy": true,
    		"searching": false,
    		"paging": false,
    		"ordering": false,
	        "processing": true,
	        "serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
	        "ajax": "<?php echo site_url('c_finance/c_40wnp1ymt/get_AllDataTTKTAX/?id=')?>"+collREF,
	        "type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [0,1,2,3], className: 'dt-body-center' },
							{ targets: [4,5], className: 'dt-body-right' }
						  ],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
    });
  
	var decFormat		= 2;
	
	function getValueDP(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		DP_AMOUNT 		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('DP_AMOUNT').value 	= parseFloat(Math.abs(DP_AMOUNT));
		document.getElementById('DP_AMOUNT1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DP_AMOUNT)),decFormat));
	}
	
	function checkInp()
	{
		DP_STAT		= document.getElementById('DP_STAT').value;
		if(DP_STAT == 0)
		{
			swal('<?php echo $alert3; ?>',
			{
				icon:"warning",
			})
			.then(function ()
			{
				document.getElementById('DP_STAT').focus();
			});
			return false;
		}
		
		if(DP_STAT == 4)
		{
			DP_NOTES2		= document.getElementById('DP_NOTES2').value;
			if(DP_NOTES2 == '')
			{
				swal('<?php echo $alert4; ?>');
				document.getElementById('DP_NOTES2').focus();
				return false;
			}
		}

		frm.addEventListener('submit', (e) => {
			console.log(e)
			document.getElementById('btnSave').style.display 	= 'none';
			document.getElementById('btnBack').style.display 	= 'none';
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