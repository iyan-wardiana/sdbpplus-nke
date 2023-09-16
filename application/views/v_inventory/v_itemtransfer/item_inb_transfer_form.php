<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 28 Januari 2019
	* File Name	= item_inb_transfer_form.php
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
$decFormat	= 2;

$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
	
$isSetDocNo 		= 1;
$ITMTSF_NUM 		= $default['ITMTSF_NUM'];
$ITMTSF_CODE 		= $default['ITMTSF_CODE'];
$ITMTSF_DATE 		= $default['ITMTSF_DATE'];
$ITMTSF_DATE		= date('m/d/Y',strtotime($ITMTSF_DATE));
$ITMTSF_SENDD 		= $default['ITMTSF_SENDD'];
$ITMTSF_SENDD		= date('m/d/Y',strtotime($ITMTSF_SENDD));
$ITMTSF_RECD 		= $default['ITMTSF_RECD'];
$ITMTSF_RECD		= date('m/d/Y');
$ITMTSF_RECD		= date('m/d/Y',strtotime($ITMTSF_RECD));
$PRJCODE 			= $default['PRJCODE'];
$ITMTSF_TYPE		= $default['ITMTSF_TYPE'];
$ITMTSF_ORIGIN		= $default['ITMTSF_ORIGIN'];
$PRJCODE_DEST		= $default['PRJCODE_DEST'];
$ITMTSF_DEST		= $default['ITMTSF_DEST'];
$JOBCODEID 			= $default['JOBCODEID'];
$ITMTSF_REFNO 		= $default['ITMTSF_REFNO'];
$ITMTSF_REFNO1 		= $default['ITMTSF_REFNO1'];
$ITMTSF_NOTE 		= $default['ITMTSF_NOTE'];
$ITMTSF_NOTE2 		= $default['ITMTSF_NOTE2'];
$ITMTSF_REVMEMO 	= $default['ITMTSF_REVMEMO'];
$ITMTSF_SENDER 		= $default['ITMTSF_SENDER'];
$ITMTSF_RECEIVER 	= $default['ITMTSF_RECEIVER'];
$ITMTSF_STAT 		= $default['ITMTSF_STAT'];
$ITMTSF_AMOUNT 		= $default['ITMTSF_AMOUNT'];
$Patt_Year 			= $default['Patt_Year'];
$Patt_Month 		= $default['Patt_Month'];
$Patt_Date 			= $default['Patt_Date'];
$Patt_Number		= $default['Patt_Number'];

// Project List
$DefEmp_ID	= $this->session->userdata['Emp_ID'];
$sqlPLC		= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
$resPLC		= $this->db->count_all($sqlPLC);

$sqlPL 		= "SELECT PRJCODE, PRJNAME
				FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY PRJNAME";
$resPL		= $this->db->query($sqlPL)->result();
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
			if($TranslCode == 'TsfNo')$TsfNo = $LangTransl;
			if($TranslCode == 'Sender')$Sender = $LangTransl;
			if($TranslCode == 'Receiver')$Receiver = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'SendDate')$SendDate = $LangTransl;
			if($TranslCode == 'Destination')$Destination = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Volume')$Volume = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'PerMonth')$PerMonth = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'TsfFrom')$TsfFrom = $LangTransl;
			if($TranslCode == 'TsfVol')$TsfVol = $LangTransl;
			if($TranslCode == 'Request')$Request = $LangTransl;
			if($TranslCode == 'Received')$Received = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$subTitleH	= "Persetujuan";
			$subTitleD	= "pemindahan material";
			
			$alert1		= "Masukan jumlah item yang akan dikirim.";
			$alert2		= "Pilih salah satu Aset.";
			$alert3		= "Silahkan pilih status persetujuan.";
			$alert4		= "Jumlah yang ditransfer lebih besar dari stok.";
			$alert5		= "Pilih sumber pengiriman.";
			$alert6		= "Pilih tujuan pengiriman.";
			$alert7		= "Sisa yang harus dikirim melebihi permintaan.";
			$alert8		= "Sisa stock kurang dari jumlah yang dikirim.";
			$alert9		= "Penerima tidak boleh kosong.";
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$subTitleH	= "Approval";
			$subTitleD	= "material transfer";
			
			$alert1		= "Please input Transfer Qty.";
			$alert2		= "Please select asset.";
			$alert3		= "Please select an approval status.";
			$alert4		= "The Qty Transferred is greater than the Qty Stock.";
			$alert5		= "Select shipment origin.";
			$alert6		= "Select shipment destination.";
			$alert7		= "Qty must be sent is more then request qty.";
			$alert8		= "Remaining stock is less than sent qty.";
			$alert9		= "Receiver can not be empty.";
			$isManual	= "Check to manual code.";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// ITMTSF_NUM - ITMTSF_AMOUNT
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$ITMTSF_NUM'";
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
				$APPROVE_AMOUNT 	= $ITMTSF_AMOUNT;
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

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $subTitleH; ?>
			    <small><?php echo $subTitleD; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
		        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkInData()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
					            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
					            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
					            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
					            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
					            <input type="Hidden" name="rowCount" id="rowCount" value="0">
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
					            	<div class="form-group" style="display:none">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $TsfNo; ?></label>
					                    <div class="col-sm-9">
					                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="ITMTSF_NUM1" id="ITMTSF_NUM1" size="30" value="<?php echo $ITMTSF_NUM; ?>" disabled />
					                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="ITMTSF_NUM" id="ITMTSF_NUM" size="30" value="<?php echo $ITMTSF_NUM; ?>" />
					                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
					                    </div>
					                </div>
					                <div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $TsfNo; ?> </label>
					                    <div class="col-sm-9">
					                    	<input type="text" class="form-control" name="ITMTSF_CODE1" id="ITMTSF_CODE1" value="<?php echo $ITMTSF_CODE; ?>" disabled >
					                        <input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="ITMTSF_CODE" id="ITMTSF_CODE" value="<?php echo $ITMTSF_CODE; ?>" >
					                    </div>
					                </div>
					                <div class="form-group" style="display:none">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $TsfNo; ?> </label>
					                    <div class="col-sm-9">
					                        <label style="display:none">
					                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
					                        </label>
					                        <label style="font-style:italic; display:none">
					                            <?php echo $isManual; ?>
					                        </label>
					                    </div>
					                </div>
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
					                    <div class="col-sm-9">
					                    	<div class="input-group date">
					                        <div class="input-group-addon">
					                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="ITMTSF_DATE1" class="form-control pull-left" id="datepicker" value="<?php echo $ITMTSF_DATE; ?>" style="width:106px" disabled><input type="hidden" name="ITMTSF_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $ITMTSF_DATE; ?>" style="width:106px"></div>
					                    </div>
					                </div>
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $SendDate; ?></label>
					                    <div class="col-sm-9">
					                    	<div class="input-group date">
					                        <div class="input-group-addon">
					                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="ITMTSF_SENDD1" class="form-control pull-left" id="datepicker1" value="<?php echo $ITMTSF_SENDD; ?>" style="width:106px" disabled><input type="hidden" name="ITMTSF_SENDD" class="form-control pull-left" id="datepicker1" value="<?php echo $ITMTSF_SENDD; ?>" style="width:106px"></div>
					                    </div>
					                </div>
					            	<div class="form-group" style="display:none">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
					                    <div class="col-sm-9">
					                        <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" disabled >
												<?php
					                                if($resPLC > 0)
					                                {
					                                    foreach($resPL as $rowPL) :
					                                        $PRJCODE1 = $rowPL->PRJCODE;
					                                        $PRJNAME1 = $rowPL->PRJNAME;
					                                        ?>
					                                        <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME1"; ?></option>
					                            			<?php
					                                    endforeach;
					                                }
					                                else
					                                {
					                                    ?>
					                                        <option value="none">--- No Project Found ---</option>
					                            		<?php
					                                }
					                            ?>
					                        </select>
					                    	<input type="hidden" class="form-control" id="PRJCODE" name="PRJCODE" value="<?php echo $PRJCODE; ?>" />
					                    </div>
					                </div>
					                <div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $RefNumber; ?></label>
					                    <div class="col-sm-9">
					                        <div class="input-group">
					                            <div class="input-group-btn">
					                                <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
					                            </div>
					                            <input type="hidden" class="form-control" name="ITMTSF_REFNO" id="ITMTSF_REFNO" style="max-width:350px;" value="<?php echo $ITMTSF_REFNO; ?>" />
					                            <input type="hidden" class="form-control" name="ITMTSF_REFNO1" id="ITMTSF_REFNO1" style="max-width:350px;" value="<?php echo $ITMTSF_REFNO1; ?>" />
					                            <input type="text" class="form-control" name="ITMTSF_REFNOX" id="ITMTSF_REFNOX" value="<?php echo $ITMTSF_CODE; ?>" onClick="getREFNO();" <?php if($ITMTSF_STAT != 1 && $ITMTSF_STAT != 4) { ?> disabled <?php } ?>>
					                        </div>
					                    </div>
					                </div>
					                <?php
					                    $url_selJO	= site_url('c_inventory/c_tr4n5p3r/s3l_MR3q_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					                ?>
					                <script>
					                    var url1 = "<?php echo $url_selJO;?>";
					                    function getREFNO()
					                    {
					                        PRJCODE	= document.getElementById('PRJCODE').value;
					                        
					                        title 	= 'Select Item';
					                        w = 1000;
					                        h = 550;
					                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
					                        var left = (screen.width/2)-(w/2);
					                        var top = (screen.height/2)-(h/2);
					                        return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
					                    }
					                </script>
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $TsfFrom; ?></label>
					                    <div class="col-sm-9">
					                        <select name="ITMTSF_ORIGIN1" id="ITMTSF_ORIGIN1" class="form-control" onChange="chgORIG(this.value)" disabled>
					                        	<option value=""> --- </option>
												<?php
													$sqlWHC	= "tbl_warehouse WHERE PRJCODE = '$PRJCODE_HO'";
													$resWHC	= $this->db->count_all($sqlWHC);
					                                if($resWHC > 0)
					                                {
														$sqlWH	= "SELECT * FROM tbl_warehouse WHERE PRJCODE = '$PRJCODE_HO'";
														$resWH	= $this->db->query($sqlWH)->result();
					                                    foreach($resWH as $rowWH) :
					                                        $WH_ID 		= $rowWH->WH_ID;
					                                        $WH_CODE 	= $rowWH->WH_CODE;
					                                        $WH_NAME 	= $rowWH->WH_NAME;
					                                        $WH_LOC		= $rowWH->WH_LOC;
					                                        ?>
					                                        <option value="<?php echo $WH_CODE; ?>" <?php if($WH_CODE == $ITMTSF_ORIGIN) { ?> selected <?php } ?>><?php echo "$WH_NAME"; ?></option>
					                            			<?php
					                                    endforeach;
					                                }
					                            ?>
					                        </select>
					                    	<input type="hidden" id="ITMTSF_ORIGIN" name="ITMTSF_ORIGIN" value="<?php echo $ITMTSF_ORIGIN; ?>" />
					                    </div>
					                </div>
					                <script>
										function chgORIG(thisVal)
										{
											document.getElementById('ITMTSF_ORIGIN').value = thisVal;
										}
									</script>
					            	<div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Destination; ?></label>
					                    <div class="col-sm-9">
					                        <select name="ITMTSF_DEST1" id="ITMTSF_DEST1" class="form-control" onChange="chgDEST(this.value)" disabled>
					                        	<option value=""> --- </option>
												<?php
													$sqlWHC	= "tbl_warehouse WHERE PRJCODE = '$PRJCODE_HO'";
													$resWHC	= $this->db->count_all($sqlWHC);
					                                if($resWHC > 0)
					                                {
														$sqlWH	= "SELECT A.*, B.PRJNAME FROM tbl_warehouse A
																	INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE ORDER BY B.PRJNAME";
														$resWH	= $this->db->query($sqlWH)->result();
					                                    foreach($resWH as $rowWH) :
					                                        $WH_ID 		= $rowWH->WH_ID;
					                                        $WH_CODE 	= $rowWH->WH_CODE;
					                                        $WH_NAME 	= $rowWH->WH_NAME;
					                                        $WH_LOC		= $rowWH->WH_LOC;
					                                        $PRJNAME	= $rowWH->PRJNAME;
					                                        ?>
					                                        <option value="<?php echo $WH_CODE; ?>" <?php if($WH_CODE == $ITMTSF_DEST) { ?> selected <?php } ?>><?php echo "$WH_NAME - $PRJNAME"; ?></option>
					                            			<?php
					                                    endforeach;
					                                }
					                            ?>
					                        </select>
					                    	<input type="hidden" id="PRJCODE_DEST" name="PRJCODE_DEST" value="<?php echo $PRJCODE_DEST; ?>" />
					                    	<input type="hidden" id="ITMTSF_DEST" name="ITMTSF_DEST" value="<?php echo $ITMTSF_DEST; ?>" />
					                    </div>
					                </div>
					                <script>
										function chgDEST(thisVal)
										{
											document.getElementById('ITMTSF_DEST').value = thisVal;
										}
									</script>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
				                <?php
								$isHidden	= 1;
								if($isHidden == 0)
								{
									?>
									<div class="form-group" style="display:none">
										<label for="inputName" class="col-sm-3 control-label"><?php echo $JobName; ?></label>
										<div class="col-sm-9">
											<select name="ITMTSF_REFNO" id="ITMTSF_REFNO" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" style="max-width:350px;" onChange="selJOB(this.value)">
												<option value="">--- None ---</option>
												<?php
													/*$Disabled_1	= 0;
													$sqlJob_1	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBLEV = 1 AND PRJCODE = '$PRJCODE'";
													$resJob_1	= $this->db->query($sqlJob_1)->result();
													foreach($resJob_1 as $row_1) :
														$JOBCODEID_1	= $row_1->JOBCODEID;
														$JOBDESC_1		= $row_1->JOBDESC;
														$space_level_1	= "";
														
														$sqlC_2		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
														$resC_2 	= $this->db->count_all($sqlC_2);
														if($resC_2 > 0)
															$Disabled_1 = 1;
														?>
														<option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($JOBCODEID_1 == $ITMTSF_REFNO) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
															<?php echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1"; ?>
														</option>
														<?php
														if($resC_2 > 0)
														{
															$Disabled_2	= 0;
															$sqlJob_2	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
															$resJob_2	= $this->db->query($sqlJob_2)->result();
															foreach($resJob_2 as $row_2) :
																$JOBCODEID_2	= $row_2->JOBCODEID;
																$JOBDESC_2		= $row_2->JOBDESC;
																$space_level_2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																
																$sqlC_3		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
																$resC_3 	= $this->db->count_all($sqlC_3);
																if($resC_3 > 0)
																	$Disabled_2 = 1;
																else
																	$Disabled_2 = 0;
																?>
																<option value="<?php echo "$JOBCODEID_2"; ?>" <?php if($JOBCODEID_2 == $ITMTSF_REFNO) { ?> selected <?php } if($Disabled_2 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_2; ?>">
																	<?php echo "$space_level_2 $JOBCODEID_2 : $JOBDESC_2"; ?>
																</option>
																<?php
																if($resC_3 > 0)
																{
																	$sqlJob_3	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
																	$resJob_3	= $this->db->query($sqlJob_3)->result();
																	foreach($resJob_3 as $row_3) :
																		$JOBCODEID_3	= $row_3->JOBCODEID;
																		$JOBDESC_3		= $row_3->JOBDESC;
																		$space_level_3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																		
																		$sqlC_4		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
																		$resC_4 	= $this->db->count_all($sqlC_4);
																		if($resC_4 > 0)
																			$Disabled_3 = 1;
																		else
																			$Disabled_3 = 0;
																		?>
																		<option value="<?php echo "$JOBCODEID_3"; ?>" <?php if($JOBCODEID_3 == $ITMTSF_REFNO) { ?> selected <?php } if($Disabled_3 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_3; ?>">
																			<?php echo "$space_level_3 $JOBCODEID_3 : $JOBDESC_3"; ?>
																		</option>
																		<?php
																		if($resC_4 > 0)
																		{
																			$Disabled_4	= 0;
																			$sqlJob_4	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
																			$resJob_4	= $this->db->query($sqlJob_4)->result();
																			foreach($resJob_4 as $row_4) :
																				$JOBCODEID_4	= $row_4->JOBCODEID;
																				$JOBDESC_4		= $row_4->JOBDESC;
																				$space_level_4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																				
																				$sqlC_5		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
																				$resC_5 	= $this->db->count_all($sqlC_5);
																				if($resC_5 > 0)
																					$Disabled_4 = 1;
																				else
																					$Disabled_4 = 0;
																				?>
																				<option value="<?php echo "$JOBCODEID_4"; ?>" <?php if($JOBCODEID_4 == $ITMTSF_REFNO) { ?> selected <?php } if($Disabled_4 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_4; ?>">
																					<?php echo "$space_level_4 $JOBCODEID_4 : $JOBDESC_4"; ?>
																				</option>
																				<?php
																				if($resC_5 > 0)
																				{
																					$Disabled_5	= 0;
																					$sqlJob_5	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
																					$resJob_5	= $this->db->query($sqlJob_5)->result();
																					foreach($resJob_5 as $row_5) :
																						$JOBCODEID_5	= $row_5->JOBCODEID;
																						$JOBDESC_5		= $row_5->JOBDESC;
																						$space_level_5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																						
																						$sqlC_6		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
																						$resC_6 	= $this->db->count_all($sqlC_6);
																						if($resC_6 > 0)
																							$Disabled_5 = 1;
																						else
																							$Disabled_5 = 0;
																						?>
																						<option value="<?php echo "$JOBCODEID_5"; ?>" <?php if($JOBCODEID_5 == $ITMTSF_REFNO) { ?> selected <?php } if($Disabled_5 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_5; ?>">
																							<?php echo "$space_level_5 $JOBCODEID_5 : $JOBDESC_5"; ?>
																						</option>
																						<?php
																						if($resC_6 > 0)
																						{
																							$Disabled_6	= 0;
																							$sqlJob_6	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
																							$resJob_6	= $this->db->query($sqlJob_6)->result();
																							foreach($resJob_6 as $row_6) :
																								$JOBCODEID_6	= $row_6->JOBCODEID;
																								$JOBDESC_6		= $row_6->JOBDESC;
																								$space_level_6	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																								
																								$sqlC_7		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_6' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
																								$resC_7 	= $this->db->count_all($sqlC_7);
																								if($resC_7 > 0)
																									$Disabled_6 = 1;
																								else
																									$Disabled_6 = 0;
																								?>
																								<option value="<?php echo "$JOBCODEID_6"; ?>" <?php if($JOBCODEID_6 == $ITMTSF_REFNO) { ?> selected <?php } if($Disabled_6 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_6; ?>">
																									<?php echo "$space_level_6 $JOBCODEID_6 : $JOBDESC_6"; ?>
																								</option>
																								<?php
																							endforeach;
																						}
																					endforeach;
																				}
																			endforeach;
																		}
																	endforeach;
																}
															endforeach;
														}
													endforeach;*/
												?>
											</select>
										</div>
									</div>
									<?php
								}
								?>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Sender; ?> </label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" name="ITMTSF_SENDER1" id="ITMTSF_SENDER1" value="<?php echo $ITMTSF_SENDER; ?>" placeholder="<?php echo $Sender; ?>" disabled >
				                    	<input type="hidden" class="form-control" style="max-width:350px" name="ITMTSF_SENDER" id="ITMTSF_SENDER" value="<?php echo $ITMTSF_SENDER; ?>" placeholder="<?php echo $Sender; ?>" >
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-9">
				                        <textarea name="ITMTSF_NOTE1" class="form-control" id="ITMTSF_NOTE1" cols="30" disabled><?php echo $ITMTSF_NOTE; ?></textarea>  
				                        <textarea name="ITMTSF_NOTE" class="form-control" style="max-width:350px; display:none" id="ITMTSF_NOTE" cols="30"><?php echo $ITMTSF_NOTE; ?></textarea>                        
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Receiver; ?> </label>
				                    <div class="col-sm-9">
				                        <input type="text" class="form-control" name="ITMTSF_RECEIVER" id="ITMTSF_RECEIVER" value="<?php echo $ITMTSF_RECEIVER; ?>" placeholder="<?php echo $Receiver; ?>" >
				                    </div>
				                </div>
				                <div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptDate; ?> </label>
				                    <div class="col-sm-9">
				                        <div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="ITMTSF_RECD" class="form-control pull-left" id="datepicker2" value="<?php echo $ITMTSF_RECD; ?>" style="width:106px" ></div>
				                    </div>
				                </div>
				                <?php					
									if($ITMTSF_NOTE2 != '')
									{
										?>
										<div class="form-group">
											<label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes; ?></label>
											<div class="col-sm-9">
												<textarea name="ITMTSF_NOTE2" class="form-control" style="max-width:350px;" id="ITMTSF_NOTE2" cols="30" disabled><?php echo $ITMTSF_NOTE2; ?></textarea>                        
											</div>
										</div>
										<?php
									}
								?>
				            	<div id="revMemo" class="form-group" <?php if($ITMTSF_REVMEMO == '') { ?> style="display:none" <?php } ?>>
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $reviseNotes; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="ITMTSF_REVMEMO" class="form-control" style="max-width:350px;" id="ITMTSF_REVMEMO" cols="30" disabled><?php echo $ITMTSF_REVMEMO; ?></textarea>                        
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $ITMTSF_STAT; ?>">
										<?php
											// START : FOR ALL APPROVAL FUNCTION
												if($disableAll == 0)
												{
													if($canApprove == 1)
													{
														$disButton	= 0;
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$ITMTSF_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
														?>
															<select name="ITMTSF_STAT" id="ITMTSF_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																<option value="0"> --- </option>
																<option value="3"<?php if($ITMTSF_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																<option value="4"<?php if($ITMTSF_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																<option value="5"<?php if($ITMTSF_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																<!-- <option value="6"<?php if($ITMTSF_STAT == 6) { ?> selected <?php } ?>>Closed</option> -->
																<option value="7"<?php if($ITMTSF_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
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
				                        <input type="hidden" name="ITMTSF_AMOUNT" id="ITMTSF_AMOUNT" value="<?php echo $ITMTSF_AMOUNT; ?>">
				                    </div>
				                </div>
				                <?php
									if($ITMTSF_STAT == 1 || $ITMTSF_STAT == 4)
									{
										$theProjCode 	= "$PRJCODE~$ITMTSF_REFNO";
										$url_AddAset	= site_url('c_inventory/c_tr4n5p3r/p0p_4llM7R/?id='.$this->url_encryption_helper->encode_url($theProjCode));
										?>
				                        <div class="form-group">
				                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                            <div class="col-sm-9">
				                                <script>
				                                    var url = "<?php echo $url_AddAset;?>";
				                                    function selectitem()
				                                    {
				                        				MRNM	= document.getElementById('ITMTSF_REFNO').value;
				                        				JONM	= document.getElementById('ITMTSF_REFNO1').value;
														
														ORIG	= document.getElementById('ITMTSF_ORIGIN1').value;
														DEST	= document.getElementById('ITMTSF_DEST1').value;
														
														if(ORIG == '')
														{
															//swal('<?php echo $alert5; ?>');
															//document.getElementById('ITMTSF_ORIGIN1').focus();
															//return false;
														}
														
														if(DEST == '')
														{
															//swal('<?php echo $alert6; ?>');
															//document.getElementById('ITMTSF_DEST1').focus();
															//return false;
														}
				                                        
				                                        title = 'Select Item';
				                                        w = 1000;
				                                        h = 550;
				                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                                        var left = (screen.width/2)-(w/2);
				                                        var top = (screen.height/2)-(h/2);
				                                        return window.open(url+'&MRNM='+MRNM+'&JONM='+JONM+'&ORIG='+ORIG, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                                    }
				                                </script>
				                                <button class="btn btn-success" type="button" onClick="selectitem();">
				                                <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
				                                </button>
				                            </div>
				                        </div>
				                        <?php
									}
								?>
							</div>
						</div>
					</div>
                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
                                    <tr style="background:#CCCCCC">
										<th width="2%" height="25" style="text-align:left">&nbsp;</th>
										<th width="13%" style="text-align:center" nowrap><?php echo $ItemCode ?> </th>
										<th width="34%" style="text-align:center"><?php echo $ItemName ?> </th>
										<th width="6%" style="text-align:center"><?php echo $Unit ?></th>
										<th width="7%" style="text-align:center">Stock</th>
										<th width="7%" style="text-align:center"><?php echo $Request ?></th>
										<th width="9%" style="text-align:center"><?php echo $Received; ?></th>
										<th width="9%"  style="text-align:center"><?php echo $TsfVol; ?> </th>
										<th width="13%"  style="text-align:center" nowrap><?php echo $Description ?> </th>
		                            </tr>
		                            <?php					
		                            if($task == 'edit')
		                            {
		                                $sqlDET	= "SELECT A.ITMTSF_NUM, A.ITMTSF_CODE, A.ITMTSF_DATE, A.PRJCODE, A.ITMTSF_DEST, A.JOBCODEID, 
														A.ITM_CODE, A.ITM_GROUP, A.ITM_NAME, A.ITM_UNIT, A.ITMTSF_VOLM, 
														A.ITMTSF_PRICE, A.ITMTSF_DESC
													FROM tbl_item_tsfd A
													WHERE A.ITMTSF_NUM = '$ITMTSF_NUM' AND A.PRJCODE = '$PRJCODE'";
		                                $resDET = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;
										
										foreach($resDET as $row) :
											$currentRow  	= ++$i;
											$ITMTSF_NUM 	= $row->ITMTSF_NUM;
											$ITMTSF_CODE 	= $row->ITMTSF_CODE;
											$ITMTSF_DATE 	= $row->ITMTSF_DATE;
											$PRJCODE 		= $row->PRJCODE;
											$ITMTSF_DEST	= $row->ITMTSF_DEST;
											$JOBCODEID 		= $row->JOBCODEID;									
											$ITM_CODE 		= $row->ITM_CODE;
											$ITM_GROUP 		= $row->ITM_GROUP;
											$ITM_NAME 		= $row->ITM_NAME;
											$ITM_UNIT 		= $row->ITM_UNIT;
											$ITMTSF_VOLM	= $row->ITMTSF_VOLM;
											$ITMTSF_PRICE	= $row->ITMTSF_PRICE;
											$ITMTSF_DESC	= $row->ITMTSF_DESC;
                                                            
                                            // CEK STOCK PER WH
                                                $ITM_STOCK	= 0;
                                                $sqlWHSTOCK	= "SELECT ITM_VOLM AS ITM_STOCK FROM tbl_item_whqty
                                                                WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
                                                $resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
                                                foreach($resWHSTOCK as $rowSTOCK) :
                                                    $ITM_STOCK	= $rowSTOCK->ITM_STOCK;
                                                endforeach;

                                                if($ITM_STOCK == 0)
                                                {
                                                    $sqlWHSTOCK = "SELECT ITM_VOLM AS ITM_STOCK, ITM_OUT, MR_VOLM FROM tbl_item
                                                                    WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
                                                    $resWHSTOCK = $this->db->query($sqlWHSTOCK)->result();
                                                    foreach($resWHSTOCK as $rowSTOCK) :
                                                        $ITM_STOCK1 = $rowSTOCK->ITM_STOCK;
                                                        $ITM_OUT    = $rowSTOCK->ITM_OUT;
                                                        $MR_VOLM    = $rowSTOCK->MR_VOLM;
                                                        $ITM_STOCK  = $ITM_STOCK1 - $ITM_OUT - $MR_VOLM;
                                                    endforeach;
                                                }
											
											// CEK MR
												$ITM_MRQTY	= 0;
												$ITM_IRQTY	= 0;
												$sqlMRQTY	= "SELECT MR_VOLM AS ITM_MRQTY, IRM_VOLM AS ITM_IRQTY FROM tbl_mr_detail
																WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'
																	AND MR_NUM = '$ITMTSF_REFNO'";
												$resMRQTY	= $this->db->query($sqlMRQTY)->result();
												foreach($resMRQTY as $rowMRQTY) :
													$ITM_MRQTY	= $rowMRQTY->ITM_MRQTY;
													$ITM_IRQTY	= $rowMRQTY->ITM_IRQTY;
												endforeach;
											
											$ITM_REMQTY		= $ITM_MRQTY - $ITM_IRQTY;
								
										/*	if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}*/
											?> 
		                                    <tr id="tr_<?php echo $currentRow; ?>">
											<td width="2%" height="25" style="text-align:left">
											  	<?php
													if($ITMTSF_STAT == 1)
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
		                                 	</td>
										 	<td width="13%" style="text-align:left" nowrap>
											  	<?php echo $ITM_CODE; ?>
		                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" class="form-control" style="max-width:300px;">
		                                      	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
		                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" class="form-control" style="max-width:300px;">
		                                  	</td>
										  	<td style="text-align:left">
												<?php echo $ITM_NAME; ?>
		                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_NAME" name="data[<?php echo $currentRow; ?>][ITM_NAME]" value="<?php echo $ITM_NAME; ?>" class="form-control" style="max-width:300px;">
		                                 	</td>
										  	<td style="text-align:center">
		                                    	<?php echo $ITM_UNIT; ?>
		                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;">
		                                    </td>
										  	<td style="text-align:right">
												<?php echo number_format($ITM_STOCK, 2); ?>
		                                        <input type="hidden" name="data<?php echo $currentRow; ?>ITM_STOCK" id="data<?php echo $currentRow; ?>ITM_STOCK" value="<?php echo $ITM_STOCK; ?>" class="form-control" >
		                                    </td>
										  	<td style="text-align:right">
												<?php echo number_format($ITM_MRQTY, 2); ?>
		                                        <input type="hidden" name="data<?php echo $currentRow; ?>ITM_MRQTY" id="data<?php echo $currentRow; ?>ITM_MRQTY" value="<?php echo $ITM_MRQTY; ?>" class="form-control" >
		                                    </td>
										  	<td style="text-align:right">
												<?php echo number_format($ITM_IRQTY, 2); ?>
		                                        <input type="hidden" name="data<?php echo $currentRow; ?>ITM_IRQTY" id="data<?php echo $currentRow; ?>ITM_IRQTY" value="<?php echo $ITM_IRQTY; ?>" class="form-control" >
		                                        <input type="hidden" name="data<?php echo $currentRow; ?>ITM_REMQTY" id="data<?php echo $currentRow; ?>ITM_REMQTY" value="<?php echo $ITM_REMQTY; ?>" class="form-control" >
		                                    </td>
											<td width="9%" style="text-align:right" nowrap>
		                                    	<?php echo number_format($ITMTSF_VOLM, 2); ?>
		                                    	<input type="hidden" name="ITMTSF_VOLM<?php echo $currentRow; ?>" id="ITMTSF_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($ITMTSF_VOLM, 2); ?>" class="form-control" style="max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" size="10" >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITMTSF_VOLM]" id="data<?php echo $currentRow; ?>ITMTSF_VOLM" value="<?php echo $ITMTSF_VOLM; ?>" class="form-control" >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITMTSF_PRICE]" id="data<?php echo $currentRow; ?>ITMTSF_PRICE" value="<?php echo $ITMTSF_PRICE; ?>" class="form-control" >
		                                  	</td>
										  	<td width="13%" style="text-align:left">
		                                    	<?php echo $ITMTSF_DESC; ?>
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][ITMTSF_DESC]" id="data<?php echo $currentRow; ?>ITMTSF_DESC" value="<?php echo $ITMTSF_DESC; ?>" class="form-control" >
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
	                    <div class="col-sm-offset-2 col-sm-9">
	                        <?php
								if($ISAPPROVE == 1)
									$ISCREATE = 1;
									
								if(($ITMTSF_STAT == 2 || $ITMTSF_STAT == 7) && $ISAPPROVE == 1 && $canApprove == 1)
								{
									?>
										<button class="btn btn-primary">
										<i class="fa fa-save"></i>
										</button>&nbsp;
									<?php
								}
								$backURL	= site_url('c_inventory/c_tr4n5p3r/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
	                        ?>
	                    </div>
	                </div>
				</form>
		        <div class="col-md-12">
					<?php
                        $DOC_NUM	= $ITMTSF_NUM;
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
		            <?php
		                $DefID      = $this->session->userdata['Emp_ID'];
		                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		                if($DefID == 'D15040004221')
		                    echo "<font size='1'><i>$act_lnk</i></font>";
		            ?>
		        </div>
		    </div>
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
	  autoclose: true,
	  endDate: '+0d'
	});
	
	//Date picker
	$('#datepicker1').datepicker({
	  autoclose: true,
	  endDate: '+0d'
	});
	
	//Date picker
	$('#datepicker2').datepicker({
	  autoclose: true,
	  endDate: '+0d'
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
	
	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		MR_NUM		= arrItem[0];
		MR_CODE		= arrItem[1];
		JO_NUM		= arrItem[2];
		SO_NUM		= arrItem[3];
		CCAL_NUM	= arrItem[4];
		
		ITMTSF_REFNO1	= JO_NUM+'~'+SO_NUM+'~'+CCAL_NUM;
		
		$(document).ready(function(e) {
			$("#ITMTSF_REFNO").val(MR_NUM);
            $("#ITMTSF_REFNOX").val(MR_CODE);
            $("#ITMTSF_REFNO1").val(ITMTSF_REFNO1);
        });
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
				
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/	
		
		JOBCODEID 		= arrItem[0];		// OK
		PRJCODE 		= arrItem[1];		// OK
		ITM_CODE 		= arrItem[2];		// OK
		ITM_CODE_H 		= arrItem[3];
		ITM_NAME 		= arrItem[4];		// OK
		ITM_DESC 		= arrItem[5];
		ITM_GROUP		= arrItem[6];		// OK
		ITM_CATEG 		= arrItem[7];
		ITM_UNIT 		= arrItem[8];		// OK
		ITM_STOCK 		= arrItem[9];		// OK	- WH STOCK
		ITM_MRQTY 		= arrItem[10];		// OK	- MR QTY
		ITM_IRQTY 		= arrItem[11];		// OK	- IR QTY
		ITM_REMQTY 		= arrItem[12];		// OK	- REM QTY
		ITM_PRICE 		= arrItem[13];		// OK	- ITM PRICE
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// Item Code	: JOBCODEID|ITM_CODE|ITM_GROUP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" class="form-control" style="max-width:300px;">';
		
		// Item Name	: ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" name="data['+intIndex+'][ITM_NAME]" id="data'+intIndex+'ITM_NAME" value="'+ITM_NAME+'" class="form-control" >';
		
		// Item Unit	: ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" >';
				
		// Vol. Stock	: ITM_STOCK - Just Info
		var ITM_STOCKV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_STOCK)), 2))
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_STOCKV+'<input type="hidden" name="data'+intIndex+'ITM_STOCK" id="data'+intIndex+'ITM_STOCK" value="'+ITM_STOCK+'" class="form-control" >';
				
		// Vol. Req.	: ITM_MRQTY - Just Info
		var ITM_MRQTYV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_MRQTY)), 2))
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_MRQTYV+'<input type="hidden" name="data'+intIndex+'ITM_MRQTY" id="data'+intIndex+'ITM_MRQTY" value="'+ITM_MRQTY+'" class="form-control" >';
				
		// Vol. Rec.	: ITM_IRQTY|ITM_REMQTY - Just Info
		var ITM_IRQTYV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_IRQTY)), 2))
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_IRQTYV+'<input type="hidden" name="data'+intIndex+'ITM_IRQTY" id="data'+intIndex+'ITM_IRQTY" value="'+ITM_IRQTY+'" class="form-control" ><input type="hidden" name="data'+intIndex+'ITM_REMQTY" id="data'+intIndex+'ITM_REMQTY" value="'+ITM_REMQTY+'" class="form-control" >';
		
		// Vol. Tsf		: ITMTSF_VOLM|ITMTSF_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		//objTD.style.display = 'none';
		objTD.innerHTML = '<input type="text" name="ITMTSF_VOLM'+intIndex+'" id="ITMTSF_VOLM'+intIndex+'" value="0.0" class="form-control" style="max-width:100px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITMTSF_VOLM]" id="data'+intIndex+'ITMTSF_VOLM" value="0.0" class="form-control" ><input type="hidden" name="data['+intIndex+'][ITMTSF_PRICE]" id="data'+intIndex+'ITMTSF_PRICE" value="'+ITM_PRICE+'" class="form-control" >';
		
		// Remarks		: ITMTSF_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][ITMTSF_DESC]" id="data'+intIndex+'ITMTSF_DESC" value="" class="form-control" >';
		
		totRow	= parseFloat(intIndex+1);
		document.getElementById('totalrow').value = parseFloat(totRow);
	}
	
	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		var ITMTSF_VOLM		= parseFloat(eval(thisVal1).value.split(",").join(""));
		
		var ITM_STOCK		= parseFloat(document.getElementById('data'+row+'ITM_STOCK').value);
		var ITM_RMQTY		= parseFloat(document.getElementById('data'+row+'ITM_MRQTY').value);
		var ITM_IRQTY		= parseFloat(document.getElementById('data'+row+'ITM_IRQTY').value);
		var ITM_REMQTY		= parseFloat(document.getElementById('data'+row+'ITM_REMQTY').value);
				
		if(ITMTSF_VOLM > ITM_REMQTY)
		{
			swal("<?php echo $alert7; ?>",
			{
				icon: "warning",
			});
			var ITMTSF_VOLM	= parseFloat(ITM_REMQTY);
		}
		
		if(ITMTSF_VOLM > ITM_STOCK)
		{
			swal("<?php echo $alert8; ?>",
			{
				icon: "warning",
			});
			var ITMTSF_VOLM	= parseFloat(ITM_STOCK);
		}
		
		document.getElementById('data'+row+'ITMTSF_VOLM').value 	= ITMTSF_VOLM;
		document.getElementById('ITMTSF_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMTSF_VOLM)),decFormat));
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
	
	function checkInData(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		var RECEIVER 	= document.getElementById('ITMTSF_RECEIVER').value;
		var ITMTSF_STAT = document.getElementById('ITMTSF_STAT').value;
		
		if(RECEIVER == '')
		{
			swal("<?php echo $alert9; ?>",
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#ITMTSF_RECEIVER').focus();
            });
			return false;
		}
		
		if(ITMTSF_STAT == 0)
		{
			swal("<?php echo $alert3; ?>",
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#ITMTSF_STAT').focus();
            });
			return false;
		}
		
		if(ITMTSF_STAT == 4)
		{
			ITMTSF_NOTE2	= document.getElementById('ITMTSF_NOTE2').value;
			if(ITMTSF_NOTE2 == '')
			{
				swal("<?php echo $alert5; ?>",
				{
					icon: "warning",
				})
	            .then(function()
	            {
	                swal.close();
	                $('#ITMTSF_NOTE2').focus();
	            });
				return false;
			}
		}
		
		if(totrow == 0)
		{
			swal("<?php echo $alert2; ?>",
			{
				icon: "warning",
			});
			return false;		  
		}
		else
		{
			//document.frm.submit();
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