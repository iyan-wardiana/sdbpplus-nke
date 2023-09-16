<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Agustus 2018
 * File Name	= v_inb_ret_form.php
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

$currentRow = 0;

$isSetDocNo = 1;
$RET_NUM 		= $default['RET_NUM'];
$DocNumber		= $RET_NUM;
$RET_CODE 		= $default['RET_CODE'];
$RET_DATE 		= $default['RET_DATE'];
$RET_TYPE 		= $default['RET_TYPE'];
$RET_DATE		= date('d/m/Y', strtotime($RET_DATE));
$PRJCODE 		= $default['PRJCODE'];
$SPLCODE 		= $default['SPLCODE'];
$IR_NUM 		= $default['IR_NUM'];
$IR_NUMX		= $IR_NUM;
$IR_CODE 		= $default['IR_CODE'];
$IR_CODEX		= $IR_CODE;

$PO_NUM 		= $default['PO_NUM'];
$PO_NUMX		= $PO_NUM;
$PO_CODE 		= $default['PO_CODE'];
$PO_CODEX		= $PO_CODE;

$RET_NOTES 		= $default['RET_NOTES'];
$RET_NOTES1		= $default['RET_NOTES1'];
$PRJNAME1 		= $default['PRJNAME'];
$RET_STAT 		= $default['RET_STAT'];
$JOBCODEID 		= $default['JOBCODEID'];
$RET_TOTCOST 	= $default['RET_TOTCOST'];
$lastPatternNumb1= $default['Patt_Number'];
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
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

	<?php
		$this->load->view('template/topbar');
		$this->load->view('template/sidebar');
		
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
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'IRCode')$IRCode = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'Currency')$Currency = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ProcessStatus')$ProcessStatus = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Stock')$Stock = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Received')$Received = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Purchase')$Purchase = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Request')$Request = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'Return')$return = $LangTransl;
			if($TranslCode == 'Disposal')$disposal = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1	= 'Silahkan pilih status persetujuan.';
			//$alert2	= "Silahkan tulis catatan dokumen.";
			$alert3	= "Dokumenn ini memerlukan persetujuan khusus.";
		}
		else
		{
			$alert1	= 'Please select approval status.';
			//$alert2	= "Plese input the reason of the document.";
			$alert3	= "This document needs a special approval.";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - PO_TOTCOST
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
				$APPROVE_AMOUNT 	= $RET_TOTCOST;
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
		
		$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
		$resultPRJ 		= $this->db->query($sqlPRJ)->result();
		
		foreach($resultPRJ as $rowPRJ) :
			$PRJNAMEHO 	= $rowPRJ->PRJNAME;
		endforeach;
	?>
    
    <body class="<?php echo $appBody; ?>">
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
				    <?php echo $mnName; ?>
				    <small><?php echo $PRJNAME1; ?></small>
				</h1>
			</section>

			<section class="content">
			    <div class="row">
			        <div class="col-md-12">
			            <div class="box box-primary">
			                <div class="box-header with-border" style="display:none">               
			              		<div class="box-tools pull-right">
			                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			                        </button>
			                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			                    </div>
			                </div>
			                <div class="box-body chart-responsive">
			                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
			                        <input type="text" name="IR_NUMX" id="IR_NUMX" class="textbox" value="<?php echo $IR_NUMX; ?>" />
			                        <input type="text" name="RET_TYPEX1" id="RET_TYPEX1" class="textbox" value="<?php echo $RET_TYPE; ?>" />
			                        <input type="text" name="IR_CODEX" id="IR_CODEX" class="textbox" value="<?php echo $IR_CODEX; ?>" />
			                        <input type="text" name="SPLCODEX1" id="SPLCODEX1" value="<?php echo $SPLCODE; ?>" />
			                        <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
			                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
			                    </form>
			                    <form class="form-horizontal" name="frm1" id="frm1" method="post" action="" style="display:none">
			                        <input type="text" name="SPLCODEX" id="SPLCODEX" value="<?php echo $SPLCODE; ?>" />
			                        <input type="text" name="RET_TYPEX" id="RET_TYPEX" class="textbox" value="<?php echo $RET_TYPE; ?>" />
			                        <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
			                    </form>
			                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
			                        <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
			                        <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
			           				<input type="hidden" name="rowCount" id="rowCount" value="0">
			                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
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
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> </label>
			                          	<div class="col-sm-10">
			                                <input type="text" class="form-control" name="RET_NUM1" id="RET_NUM1" value="<?php echo $DocNumber; ?>" disabled >
			                       			<input type="hidden" class="textbox" name="RET_NUM" id="RET_NUM" size="30" value="<?php echo $DocNumber; ?>" />
			                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode ?> </label>
			                          	<div class="col-sm-10">
			                                <input type="text" class="form-control" name="RET_CODE1" id="RET_CODE1" value="<?php echo $RET_CODE; ?>" disabled >
			                                <input type="hidden" class="form-control" name="RET_CODE" id="RET_CODE" value="<?php echo $RET_CODE; ?>" >
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?> </label>
			                          	<div class="col-sm-10">
			                                <div class="input-group date">
			                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                    <input type="text" name="RET_DATEX" class="form-control pull-left" id="datepicker1" value="<?php echo $RET_DATE; ?>" style="width:120px" disabled>
			                                    <input type="hidden" name="RET_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $RET_DATE; ?>" style="width:150px">
			                                </div>
			                          	</div>
			                        </div>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Type ?> </label>
			                          	<div class="col-sm-10">
			                            	<select name="RET_TYPE" id="RET_TYPE" class="form-control" style="max-width:150px">
			                                	<option value="RET" <?php if($RET_TYPE == 'RET') { ?> selected <?php } ?>>
													<?php echo $Return; ?>
			                                   	</option>
			                                	<option value="DIS" <?php if($RET_TYPE == 'DIS') { ?> selected <?php } ?> style="display:none">
													<?php // echo $disposal; ?>
			                                   	</option>
			                                </select>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SupplierName ?> </label>
			                          	<div class="col-sm-10">
			                            	<input type="hidden" class="form-control" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" >
			                            	<select name="SPLCODE1" id="SPLCODE1" class="form-control" disabled>
			                                	<option value="" <?php if($SPLCODE == 0) { ?> selected <?php } ?>>--- None ---</option>
			                                    <?php
			                                    $i = 0;
			                                    if($countVend > 0)
			                                    {
			                                        foreach($vwvendor as $row) :
			                                            $SPLCODE1	= $row->SPLCODE;
			                                            $SPLDESC1	= $row->SPLDESC;
			                                            ?>
			                                                <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLCODE1 - $SPLDESC1"; ?></option>
			                                            <?php
			                                        endforeach;
			                                    }
			                                    ?>
			                                </select>
			                          	</div>
			                        </div>
			                        <script>
			                            function getVend()
			                            {
											SPLCODE	= document.getElementById('SPLCODE').value;
											document.getElementById('SPLCODEX').value = SPLCODE;
			                                document.frm1.submit1.click();
			                            }
			                        </script>
			                        <div class="form-group" style="display: none;">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $IRCode ?> </label>
			                          	<div class="col-sm-10">
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
			                                    </div>
			                                    <input type="text" class="form-control" name="IR_NUM" id="IR_NUM" value="<?php echo $IR_NUMX; ?>" >
			                                    <input type="text" class="form-control" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODEX; ?>" >
			                                    <input type="text" class="form-control" name="PO_NUM" id="PO_NUM" value="<?php echo $PO_NUMX; ?>" >
			                                    <input type="text" class="form-control" name="PO_CODE" id="PO_CODE" value="<?php echo $PO_CODEX; ?>" >
			                                </div>
			                            </div>
			                        </div>
			                        <div class="form-group" style="display: none;">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?> </label>
			                          	<div class="col-sm-10">
			                            	<select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:400px" onChange="chooseProject()" disabled>
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
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?> </label>
			                          	<div class="col-sm-10">
			                                <textarea class="form-control" name="RET_NOTES"  id="RET_NOTES" style="height:70px" disabled><?php echo $RET_NOTES; ?></textarea>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes ?> </label>
			                          	<div class="col-sm-10">
			                                <textarea class="form-control" name="RET_NOTES1"  id="RET_NOTES1" style="height:70px"><?php echo $RET_NOTES1; ?></textarea>
			                          	</div>
			                        </div>
			                        <div class="form-group" >
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
			                          	<div class="col-sm-10">
			                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $RET_STAT; ?>">
			                            	<input type="hidden" name="RET_STATx" id="RET_STATx" value="<?php echo $RET_STAT; ?>">
											<?php
												// START : FOR ALL APPROVAL FUNCTION
													if($disableAll == 0)
													{
														if($canApprove == 1)
														{
															$disButton	= 0;
															$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$RET_NUM' AND AH_APPROVER = '$DefEmp_ID'";
															$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
															if($resCAPPHE > 0)
																$disButton	= 1;
															?>
																<select name="RET_STAT" id="RET_STAT" class="form-control select2" style="max-width:150px" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																	<option value="0"> -- </option>
																	<option value="3"<?php if($RET_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																	<option value="4"<?php if($RET_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																	<option value="5"<?php if($RET_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																	<option value="6"<?php if($RET_STAT == 6) { ?> selected <?php } ?>>Closed</option>
																	<option value="7"<?php if($RET_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
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
																Step approval not set;
															</a>
														<?php
													}
												// END : FOR ALL APPROVAL FUNCTION
			                                ?>
			                            </div>
			                        </div>
			                        <div class="row">
			                            <div class="col-md-12">
			                                <div class="box box-primary">
			                                <br>
			                                <table width="100%" border="1" id="tbl">
			                                    <tr style="background:#CCCCCC">
			                                        <th width="3%" height="25" style="text-align:center">No.</th>
			                                      	<th width="4%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
			                                      	<th width="19%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
			                                      	<th width="21%" style="text-align:center" nowrap><?php echo $Description; ?></th>
			                                      	<th width="8%" style="text-align:center" nowrap><?php echo $Received; ?></th>
			                                      	<th width="6%" style="text-align:center" nowrap><?php echo $Stock; ?> </th>
			                                        <th width="6%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th>
			                                      	<th width="7%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
			                                      	<th width="3%" style="text-align:center; display:none" nowrap><?php echo $Discount; ?><br>
			                                      	(%)</th>
			                                      	<th width="2%" style="text-align:center; display:none" nowrap><?php echo $Discount; ?> </th>
			                                      	<th width="2%" style="text-align:center; display:none" nowrap><?php echo $Tax; ?></th>
			                                      	<th width="19%" style="text-align:center" nowrap><?php echo $Remarks; ?></th>
			                                  </tr>
			                                    <?php
													$resultC	= 0;
													
													$sqlDET		= "SELECT A.IR_ID, A.IR_NUM, A.JOBCODEDET, A.JOBCODEID,
																		A.ITM_CODE, A.ITM_UNIT, 
																		A.IR_VOLM, A.IR_PRICE, A.IR_AMOUNT,
																		A.ITM_QTY AS RET_VOLM, A.ITM_PRICE AS RET_PRICE, 
																		A.RET_COST, A.TAXCODE1, A.TAXCODE2,
																		A.TAXPRICE1, A.TAXPRICE2, A.NOTES, A.RET_REMARKS,
																		B.ITM_NAME, B.ACC_ID, B.ITM_GROUP, 
																		B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
																		B.ISFASTM, B.ISWAGE
																	FROM tbl_ret_detail A
																		INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	WHERE 
																		A.RET_NUM = '$RET_NUM' 
																		AND B.PRJCODE = '$PRJCODE'";
													$result = $this->db->query($sqlDET)->result();
													
													$sqlDETC	= "tbl_ret_detail A
																		INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	WHERE 
																		A.RET_NUM = '$RET_NUM' 
																		AND B.PRJCODE = '$PRJCODE'";
													$resultC 	= $this->db->count_all($sqlDETC);
													
													$i			= 0;
													$j			= 0;
													if($resultC > 0)
													{
														$GT_ITMPRICE	= 0;
														foreach($result as $row) :
															$currentRow  	= ++$i;																
															$RET_NUM 		= $RET_NUM;
															$RET_CODE 		= $RET_CODE;
															$PRJCODE		= $PRJCODE;
															$IR_ID			= $row->IR_ID;
															$IR_NUM			= $row->IR_NUM;
															$JOBCODEDET		= $row->JOBCODEDET;
															$JOBCODEID		= $row->JOBCODEID;
															$ACC_ID 		= $row->ACC_ID;
															$ITM_CODE 		= $row->ITM_CODE;
															$ITM_NAME 		= $row->ITM_NAME;
															$ITM_UNIT 		= $row->ITM_UNIT;
															$ITM_GROUP 		= $row->ITM_GROUP;
															$IR_VOLM 		= $row->IR_VOLM;
															$IR_PRICE 		= $row->IR_PRICE;
															$IR_AMOUNT 		= $row->IR_AMOUNT;
															$RET_VOLM 		= $row->RET_VOLM;
															$RET_PRICE 		= $row->RET_PRICE;
															$RET_COST 		= $row->RET_COST;
															$TAXCODE1		= $row->TAXCODE1;
															$TAXCODE2		= $row->TAXCODE2;
															$TAXPRICE1		= $row->TAXPRICE1;
															$TAXPRICE2		= $row->TAXPRICE2;
															$NOTES			= $row->NOTES;
															$RET_REMARKS	= $row->RET_REMARKS;
															$itemConvertion	= 1;
															
															$ITM_GROUP 		= $row->ITM_GROUP;
															$ISMTRL 		= $row->ISMTRL;
															$ISRENT 		= $row->ISRENT;
															$ISPART 		= $row->ISPART;
															$ISFUEL 		= $row->ISFUEL;
															$ISLUBRIC 		= $row->ISLUBRIC;
															$ISFASTM 		= $row->ISFASTM;
															$ISWAGE 		= $row->ISWAGE;
															if($ISMTRL == 1)
																$ITM_TYPE	= 1;
															elseif($ISRENT == 1)
																$ITM_TYPE	= 2;
															elseif($ISPART == 1)
																$ITM_TYPE	= 3;
															elseif($ISFUEL == 1)
																$ITM_TYPE	= 4;
															elseif($ISLUBRIC == 1)
																$ITM_TYPE	= 5;
															elseif($ISFASTM == 1)
																$ITM_TYPE	= 6;
															else
																$ITM_TYPE	= 1;
															
															$ITM_VOLM		= 0;
															$sqlSTOCK		= "SELECT ITM_VOLM FROM tbl_item 
																				WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
															$resSTOCK		= $this->db->query($sqlSTOCK)->result();
															foreach($resSTOCK as $rowSTOCK):
																$ITM_VOLM	= $rowSTOCK->ITM_VOLM;
															endforeach;
															
															if($TAXCODE1 == 'TAX01')
															{
																$GT_ITMPRICE= $GT_ITMPRICE + $RET_COST + $TAXPRICE1;
															}
															if($TAXCODE1 == 'TAX02')
															{
																$GT_ITMPRICE= $GT_ITMPRICE + $RET_COST - $TAXPRICE1;
															}
															
															//$ITM_TOTAL	= $RET_VOLM * $ITM_PRICE;
															$ITM_TOTAL		= $IR_AMOUNT;
												
															/*if ($j==1) {
																echo "<tr class=zebra1>";
																$j++;
															} else {
																echo "<tr class=zebra2>";
																$j--;
															}*/
															?> 
			                                 				<tr id="tr_<?php echo $currentRow; ?>">
			                                                	<!-- NO. URUT -->
																<td width="3%" height="25" style="text-align:left">
																	<?php
			                                                            if($RET_STAT == 1)
			                                                            {
			                                                                ?>
			                                                                    <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs" style="display:none"><i class="fa fa-trash-o"></i></a>
			                                                                    
			                                                       				<a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">
			                                                                <?php
			                                                            }
			                                                            else
			                                                            {
			                                                                echo "$currentRow.";
			                                                            }
			                                                        ?>
			                                                	</td>
			                                                	<!-- ITEM CODE -->
			                                                    <td width="4%" style="text-align:left">
			                                                        <?php print $ITM_CODE; ?>
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>RET_NUM" name="data[<?php echo $currentRow; ?>][RET_NUM]" value="<?php print $DocNumber; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>RET_CODE" name="data[<?php echo $currentRow; ?>][RET_CODE]" value="<?php print $RET_CODE; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>IR_ID" name="data[<?php echo $currentRow; ?>][IR_ID]" value="<?php print $IR_ID; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>IR_NUM" name="data[<?php echo $currentRow; ?>][IR_NUM]" value="<?php print $IR_NUMX; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php print $JOBCODEDET; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php print $JOBCODEID; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
			                                                	</td>
			                                                    <!-- ITEM NAME -->
			                                                    <td width="19%" style="text-align:left"><?php echo $ITM_NAME; ?></td>
			                                                    <td width="21%" style="text-align:left">
																<?php print $NOTES; ?>
			                                                      <input type="hidden" class="form-control" id="data<?php echo $currentRow; ?>NOTES" name="data[<?php echo $currentRow; ?>][NOTES]" value="<?php print $NOTES; ?>">
			                                                    </td>
			                                                    <!-- ITEM REMAIN -->
			                                                    <td width="8%" style="text-align:right" nowrap>
			                                                        <?php print number_format($IR_VOLM, $decFormat); ?>
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>IR_VOLM" name="data[<?php echo $currentRow; ?>][IR_VOLM]" value="<?php print $IR_VOLM; ?>">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>IR_PRICE" name="data[<?php echo $currentRow; ?>][IR_PRICE]" value="<?php print $IR_PRICE; ?>">
			                                                    </td>
			                                                    <!-- QTY STOCK -->  
			                                                    <td width="6%" style="text-align:right" nowrap>
			                                                        <?php echo number_format($ITM_VOLM,2); ?>
			                                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:200px; text-align:right" name="ITM_STOCK<?php echo $currentRow; ?>" id="ITM_STOCK<?php echo $currentRow; ?>" value="<?php print $ITM_VOLM; ?>" >
			                                                    </td>
			                                                    <!-- QTY RET -->
			                                                    <td width="6%" style="text-align:right">
			                                                        <?php print number_format($RET_VOLM, $decFormat); ?>
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_QTY" name="data[<?php echo $currentRow; ?>][ITM_QTY]" value="<?php print $RET_VOLM; ?>">
			                                                    </td>
			                                                    <!-- ITEM UNIT -->
			                                                    <td width="7%" style="text-align:center;" nowrap>
			                                                        <?php print $ITM_UNIT; ?>  
			                                                         <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php print $ITM_GROUP; ?>">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_TYPE" name="data[<?php echo $currentRow; ?>][ITM_TYPE]" value="<?php print $ITM_TYPE; ?>">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_PRICE" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" value="<?php print $RET_PRICE; ?>">
			                                                    </td>
			                                                    <!-- ITEM DISCOUNT PERCENTATION -->
			                                                    <td width="3%" style="text-align:center; font-style:italic; display:none">&nbsp;</td>
			                                                    <!-- ITEM DISCOUNT -->
			                                                    <td width="2%" style="text-align:center; font-style:italic; display:none">&nbsp;</td>
			                                                    <!-- ITEM TAX -->
			                                                    <td width="2%" style="text-align:center; font-style:italic; display:none">&nbsp;</td>
			                                                    <!-- ITEM TOTAL COST -->
			                                                    <td width="19%" style="text-align:left;">
			                                                    	<?php print $RET_REMARKS; ?>
			                                                      	<input type="hidden" class="form-control" id="data<?php echo $currentRow; ?>RET_REMARKS" name="data[<?php echo $currentRow; ?>][RET_REMARKS]" value="<?php print $RET_REMARKS; ?>">
			                                                      	<input type="hidden" class="form-control" id="data<?php echo $currentRow; ?>RET_COST" name="data[<?php echo $currentRow; ?>][RET_COST]" value="<?php print $RET_COST; ?>">
			                                                      	<input type="hidden" class="form-control" id="data<?php echo $currentRow; ?>IR_VOLM" name="data[<?php echo $currentRow; ?>][IR_VOLM]" value="<?php print $IR_VOLM; ?>">
			                                                      	<input type="hidden" class="form-control" id="data<?php echo $currentRow; ?>IR_VOLM" name="data[<?php echo $currentRow; ?>][IR_VOLM]" value="<?php print $IR_VOLM; ?>">
			                                                      	<input type="hidden" class="form-control" id="data<?php echo $currentRow; ?>IR_AMOUNT" name="data[<?php echo $currentRow; ?>][IR_AMOUNT]" value="<?php print $IR_AMOUNT; ?>">
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
			                        <br>
			                        <div class="form-group">
			                            <div class="col-sm-offset-2 col-sm-10">
			                            	<?php
												if($canApprove == 1 && $disableAll == 0)
												{
													if(($RET_STAT == 2 || $RET_STAT == 7) && $ISAPPROVE == 1)
													{
														?>
															<button class="btn btn-primary" >
															<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
															</button>&nbsp;
														<?php
													}
												}
												$backURL	= site_url('c_purchase/c_po180c19ret/gl180c19retinb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
												echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
											?>
			                            </div>
			                        </div>
			                    </form>
			                </div>
			            </div>
			        </div>
			    </div>
			</section>
		</div>
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
  
	var decFormat		= 2;
	
	function checkInp()
	{
	
		RET_STAT	= document.getElementById('RET_STAT').value;
		if(RET_STAT == 0)
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			});
			document.getElementById('RET_STAT').focus();
			return false;
		}
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
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
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
	$this->load->view('template/aside');

	$this->load->view('template/js_data');

	$this->load->view('template/foot');
?>