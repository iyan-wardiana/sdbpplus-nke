<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Februari 2019
 * File Name	= v_inb_prodprocess_form.php
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

$currRow = 0;
$isSetDocNo = 1;

$STF_NUM 		= $default['STF_NUM'];
$DocNumber		= $default['STF_NUM'];
$STF_CODE 		= $default['STF_CODE'];
$STF_DATE 		= $default['STF_DATE'];
$STF_DATE		= date('m/d/Y', strtotime($STF_DATE));
$PRJCODE 		= $default['PRJCODE'];
$PRJCODE		= $default['PRJCODE'];
$JO_NUM 		= $default['JO_NUM'];
$JO_CODE 		= $default['JO_CODE'];
$SO_NUM 		= $default['SO_NUM'];
$SO_CODE 		= $default['SO_CODE'];
$CCAL_NUM 		= $default['CCAL_NUM'];
$CCAL_CODE 		= $default['CCAL_CODE'];
$BOM_NUM 		= $default['BOM_NUM'];
$BOM_CODE 		= $default['BOM_CODE'];
$CUST_CODE 		= $default['CUST_CODE'];
$CUST_DESC		= $default['CUST_DESC'];
$STF_TYPE 		= $default['STF_TYPE'];
$STF_FROM 		= $default['STF_FROM'];
$STF_DEST 		= $default['STF_DEST'];
$STF_NOTES 		= $default['STF_NOTES'];
$STF_NOTES1 	= $default['STF_NOTES1'];
$STF_STAT 		= $default['STF_STAT'];
$Patt_Year 		= $default['Patt_Year'];
$Patt_Month 	= $default['Patt_Month'];
$Patt_Date 		= $default['Patt_Date'];
$Patt_Number 	= $default['Patt_Number'];
$lastPatternNumb1	= $default['Patt_Number'];

$PRJNAME	= '';
$sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ 	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME = $rowPRJ->PRJNAME;
endforeach;

$WH_CODE	= '';
$sqlWH 		= "SELECT WH_CODE FROM tbl_warehouse WHERE PRJCODE = '$PRJCODE' AND ISWHPROD = 1";
$resWH 		= $this->db->query($sqlWH)->result();
foreach($resWH as $rowWH) :
	$WH_CODE = $rowWH->WH_CODE;
endforeach;

$secGenCode	= base_url().'index.php/c_production/c_b0fm47/genCode/'; // Generate Code
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
			if($TranslCode == 'PONumber')$PONumber = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'Currency')$Currency = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'AdditAddress')$AdditAddress = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ProcessStatus')$ProcessStatus = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Purchase')$Purchase = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'RawMtr')$RawMtr = $LangTransl;
			if($TranslCode == 'Product')$Product = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
			if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
			if($TranslCode == 'ProcessCode')$ProcessCode = $LangTransl;
			if($TranslCode == 'TsfFrom')$TsfFrom = $LangTransl;
			if($TranslCode == 'TsfTo')$TsfTo = $LangTransl;
			if($TranslCode == 'Section')$Section = $LangTransl;
			if($TranslCode == 'Warehouse')$Warehouse = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= 'Jumlah ';
			$alert2		= ' tidak boleh nol.';
			$alert3		= 'Tahapan produksi tidak boleh sama.';
			$alert4		= 'No. JO tidak boleh kosong.';
			$alert5		= 'Tentukan tahapan produksi.';
			$alert6		= 'Tentukan tahapan produksi selanjutnya.';
			$alert7		= "Silahkan pilih status persetujuan.";
			$alert8		= "Silahkan tulis catatan persetujuan dokumen.";
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$alert1		= 'Qty of ';
			$alert2		= ' can not be empty.';
			$alert3		= 'Production stages cannot be the same..';
			$alert4		= 'JO Number can not ve empty.';
			$alert5		= 'Select the step of production.';
			$alert6		= 'Select the next step of production.';
			$alert7		= "Please select an approval status.";
			$alert8		= "Plese input approval notes of this document.";
			$isManual	= "Check to manual code.";
		}
		
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
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND PRJCODE = '$PRJCODE_HO'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_HO')";
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
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_HO')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_HO')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_HO')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$STF_NUM'";
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
				$APPROVE_AMOUNT 	= 0;		// No Total Amount Field
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
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
			<section class="content-header">
				<h1>
				    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/secttransfer.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
				    <small><?php echo $PRJNAME; ?></small>  </h1>
				  <?php /*?><ol class="breadcrumb">
				    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				    <li><a href="#">Tables</a></li>
				    <li class="active">Data tables</li>
				  </ol><?php */?>
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
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PONumber ?> </label>
			                          	<div class="col-sm-10">
			                                <input type="text" class="form-control" style="max-width:195px" name="STF_NUM1" id="STF_NUM1" value="<?php echo $DocNumber; ?>" disabled >
			                       			<input type="hidden" class="textbox" name="STF_NUM" id="STF_NUM" size="30" value="<?php echo $DocNumber; ?>" />
			                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ProcessCode; ?> </label>
			                          	<div class="col-sm-10">
			                                    <input type="text" class="form-control" name="STF_CODE1" id="STF_CODE1" value="<?php echo $STF_CODE; ?>" disabled >
			                                    <input type="hidden" class="form-control" style="min-width:100px" name="STF_CODE" id="STF_CODE" value="<?php echo $STF_CODE; ?>" >
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?> </label>
			                          	<div class="col-sm-10">
			                                <div class="input-group date">
			                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                    <input type="text" name="STF_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $STF_DATE; ?>" style="width:100px" disabled>
			                                    <input type="hidden" name="STF_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $STF_DATE; ?>" style="width:100px">
			                                </div>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> JO</label>
			                            <div class="col-sm-10">
			                                <div class="input-group">
			                                    <div class="input-group-btn">
			                                        <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
			                                    </div>
			                                    <input type="hidden" class="form-control" name="JO_NUM" id="JO_NUM" style="max-width:350px;" value="<?php echo $JO_NUM; ?>" />
			                                    <input type="hidden" class="form-control" name="JO_CODE" id="JO_CODE" style="max-width:350px;" value="<?php echo $JO_CODE; ?>" />
			                                    <input type="text" class="form-control" name="JO_CODE1" id="JO_CODE1" value="<?php echo $JO_CODE; ?>" onClick="getJOCODE();" disabled>
			                                </div>
			                            </div>
			                        </div>
									<?php
			                            $url_selJO	= site_url('c_production/c_pR04uctpr0535/s3l4llj0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			                        ?>
			                        <script>
			                            var url1 = "<?php echo $url_selJO;?>";
			                            function getJOCODE()
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
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $TsfFrom ?> </label>
			                          	<div class="col-sm-10">
			                            	<select name="STF_FROM1" id="STF_FROM1" class="form-control" onChange="chk1Stp()" disabled>
			                                	<option value="" >--- None ---</option>
			                                    <?php
													$sqlSTEPC	= "tbl_prodstep";
													$resSTEPC	= $this->db->count_all($sqlSTEPC);
													
													if($resSTEPC > 0)
													{
														$sqlSTEP	= "SELECT * FROM tbl_prodstep ORDER BY PRODS_STEP ASC";
														$resSTEP	= $this->db->query($sqlSTEP)->result();
														foreach($resSTEP as $row) :
															$PRODS_ID	= $row->PRODS_ID;
															$PRODS_CODE	= $row->PRODS_CODE;
															$PRODS_STEP	= $row->PRODS_STEP;
															$PRODS_NAME	= $row->PRODS_NAME;
															?>
																<option value="<?php echo $PRODS_STEP; ?>" <?php if($PRODS_STEP == $STF_FROM) { ?> selected <?php } ?>><?php echo $PRODS_NAME; ?></option>
															<?php
														endforeach;
													}
			                                    ?>
			                                </select>
			                                <input type="hidden" class="form-control" name="STF_FROM" id="STF_FROM" style="max-width:350px;" value="<?php echo $STF_FROM; ?>" />
			                          	</div>
			                        </div>
			                        <script>
										function getTSfType(thisVal)
										{
											if(thisVal == 1)
											{
												document.getElementById('tsf').style.display 	= '';
												document.getElementById('wh').style.display 	= 'none';
											}
											else if(thisVal == 2)
											{
												document.getElementById('tsf').style.display 	= 'none';
												document.getElementById('wh').style.display 	= '';
											}
										}
									</script>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Type; ?> </label>
			                          	<div class="col-sm-10">
			                                <label>
			                                    <select name="STF_TYPE" id="STF_TYPE" class="form-control" style="max-width:100px" onChange="getTSfType(this.value)">
			                                        <option value="" > --</option>
			                                        <option value="1" selected ><?php echo $Section; ?></option>
			                                        <option value="2" ><?php echo $Warehouse; ?></option>
			                                    </select>
			                                </label>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $TsfTo ?> </label>
			                          	<div class="col-sm-10">
			                                    <select name="STF_DEST1" id="STF_DEST" class="form-control" onChange="chkStep(this.value)" disabled>
			                                        <option value="" >  -- <?php echo $Section; ?> -- </option>
													<?php
			                                            $sqlSTEP	= "SELECT * FROM tbl_prodstep";
			                                            $resSTEP	= $this->db->query($sqlSTEP)->result();
			                                            if($resSTEPC > 0)
			                                            {
			                                                foreach($resSTEP as $row) :
			                                                    $PRODS_ID	= $row->PRODS_ID;
			                                                    $PRODS_CODE	= $row->PRODS_CODE;
			                                                    $PRODS_STEP	= $row->PRODS_STEP;
			                                                    $PRODS_NAME	= $row->PRODS_NAME;
			                                                    ?>
			                                                        <option value="<?php echo $PRODS_STEP; ?>" <?php if($PRODS_STEP == $STF_DEST) { ?> selected <?php } ?>><?php echo $PRODS_NAME; ?></option>
			                                                    <?php
			                                                endforeach;
			                                            }
			                                        ?>
			                                    </select>
			                                	<input type="hidden" class="form-control" name="STF_DEST" id="STF_DEST" style="max-width:350px;" value="<?php echo $STF_DEST; ?>" />
			                          	</div>
			                        </div>
			                        <script>
										function chk1Stp()
										{
											var firstStep	= $("#STF_FROM").val();
											var JO_NUM		= $("#JO_NUM").val();
											if(JO_NUM == '')
											{
												swal('<?php echo $alert4; ?>');
												$("#STF_FROM").val(0);
												$("#JO_CODE1").focus();
												return false;
											}
										}
										
										function chkStep(thisVal)
										{
											var firstStep	= $("#STF_FROM").val();
											var nextStep	= $("#STF_DEST").val();
											var JO_NUM		= $("#JO_NUM").val();
											
											if(JO_NUM == '')
											{
												swal('<?php echo $alert4; ?>');
												$("#STF_DEST").val(0);
												$("#JO_CODE1").focus();
												return false;
											}
											
											if(firstStep == nextStep)
											{
												swal('<?php echo $alert3; ?>');
												$("#STF_DEST").val(0);
												$("#STF_DEST").focus();
												return false;
											}
										}
									</script>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?> </label>
			                          	<div class="col-sm-10">
			                            	<select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
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
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
			                          	<div class="col-sm-10">
			                                <textarea class="form-control" name="STF_NOTESX"  id="STF_NOTESX" style="height:70px" disabled><?php echo $STF_NOTES; ?></textarea>
			                                <textarea class="form-control" name="STF_NOTES"  id="STF_NOTES" style="max-width:350px;height:70px; display:none"><?php echo $STF_NOTES; ?></textarea>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes ?> </label>
			                          	<div class="col-sm-10">
			                                <textarea class="form-control" name="STF_NOTES1"  id="STF_NOTES1" style="height:70px"><?php echo $STF_NOTES1; ?></textarea>
			                          	</div>
			                        </div>
			                        <!--
			                        	APPROVE STATUS
			                            1 - New
			                            2 - Confirm
			                            3 - Approve
			                        -->
			                        <div class="form-group" >
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
			                          	<div class="col-sm-10">
			                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $STF_STAT; ?>">
											<?php
			                                    // START : FOR ALL APPROVAL FUNCTION
			                                        if($disableAll == 0)
			                                        {
			                                            if($canApprove == 1)
			                                            {
			                                                $disButton	= 0;
			                                                $sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$STF_NUM' AND AH_APPROVER = '$DefEmp_ID'";
			                                                $resCAPPHE	= $this->db->count_all($sqlCAPPHE);
			                                                if($resCAPPHE > 0)
			                                                    $disButton	= 1;
			                                                ?>
			                                                    <select name="STF_STAT" id="STF_STAT" class="form-control select2" style="max-width:120px" <?php if($disButton == 1) { ?> disabled <?php } ?> >
			                                                        <option value="0"> -- </option>
			                                                        <option value="3"<?php if($STF_STAT == 3) { ?> selected <?php } ?>>Approved</option>
			                                                        <option value="4"<?php if($STF_STAT == 4) { ?> selected <?php } ?>>Revised</option>
			                                                        <option value="5"<?php if($STF_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
			                                                        <option value="7"<?php if($STF_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
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
			                        <?php
									$url_WIP	= site_url('c_production/C_pR04uctpr0535/s3l4llW1p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									$url_RM		= site_url('c_production/C_pR04uctpr0535/s3l4llRM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			                        ?>
			                        <div class="row">
			                            <!-- START : OUTPUT ITEM -->
			                                <div class="col-md-6">
			                                    <div class="box box-success">
			                                        <br>
			                                        <div class="form-group" style="display:none">
			                                            <div class="col-sm-10">
			                                                <script>
			                                                    var urlWIP = "<?php echo $url_WIP; ?>";
			                                                    function selectWIP()
			                                                    {
			                                                        var JONUM	= $("#JO_NUM").val();
			                                                        var JOCODE	= $("#JO_CODE").val();
			                                                        var STFFROM	= $("#STF_FROM").val();
			                                                        var STFDEST	= $("#STF_DEST").val();
			                                                        
			                                                        if(JONUM == '')
			                                                        {
			                                                            swal('<?php echo $alert4; ?>');
			                                                            $("#JO_CODE1").focus();
			                                                            return false;
			                                                        }
			                                                        
			                                                        if(STFFROM == '')
			                                                        {
			                                                            swal('<?php echo $alert5; ?>');
			                                                            $("#STF_FROM").focus();
			                                                            return false;
			                                                        }
			                                                        
			                                                        if(STFDEST == '')
			                                                        {
			                                                            swal('<?php echo $alert6; ?>');
			                                                            $("#STF_DEST").focus();
			                                                            return false;
			                                                        }
			                                                        
			                                                        title = 'Select Item';
			                                                        w = 1000;
			                                                        h = 550;
			                                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
			                                                        var left = (screen.width/2)-(w/2);
			                                                        var top = (screen.height/2)-(h/2);
			                                                        return window.open(urlWIP+'&JONUM='+JONUM+'&JOCODE='+JOCODE, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			                                                    }
			                                                </script>
			                                                <button class="btn btn-success" type="button" onClick="selectWIP();">
			                                                <i class="fa fa-cube"></i>&nbsp;&nbsp;<?php echo $Product; ?>
			                                                </button>
			                                            </div>
			                                        </div>
			                                        <table width="100%" border="1" id="tbl_OUT">
			                                            <tr style="background:#CCCCCC">
			                                                <th width="4%" height="25" style="text-align:center">No.</th>
			                                                <th width="9%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
			                                                <th width="69%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
			                                                <th width="5%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
			                                                <th width="13%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th>
			                                            </tr>
			                                            <?php
			                                                $resOUTC	= 0;
			                                                if($task == 'edit')
			                                                {																
			                                                    $sqlOUT		= "SELECT A.ITM_TYPE, A.ITM_CODE, A.ITM_GROUP, B.ITM_NAME, B.ITM_UNIT, 
			                                                                        A.STF_VOLM, A.STF_PRICE, A.STF_TOTAL, A.ACC_ID, A.ACC_ID_UM
			                                                                    FROM tbl_stf_detail A
			                                                                        INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
			                                                                            AND B.PRJCODE = '$PRJCODE'
			                                                                    WHERE A.STF_NUM = '$STF_NUM'
																					AND A.ITM_TYPE = 'OUT'";
			                                                    $resOUT 	= $this->db->query($sqlOUT)->result();
			                                                    
			                                                    $sqlOUTC	= "tbl_stf_detail A
			                                                                        INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
			                                                                            AND B.PRJCODE = '$PRJCODE'
			                                                                    WHERE A.STF_NUM = '$STF_NUM'
																					AND A.ITM_TYPE = 'OUT'";
			                                                    $resOUTC 	= $this->db->count_all($sqlOUTC);
			                                                }
			                                                    
			                                                $i			= 0;
			                                                $j			= 0;
			                                                if($resOUTC > 0)
			                                                {
			                                                    foreach($resOUT as $rowOUT) :
			                                                        $currRow  		= ++$i;																
			                                                        $STF_NUM 		= $STF_NUM;
			                                                        $STF_CODE 		= $STF_CODE;
			                                                        $PRJCODE		= $PRJCODE;
			                                                        $ITM_TYPE		= $rowOUT->ITM_TYPE;
			                                                        $ITM_CODE		= $rowOUT->ITM_CODE;
			                                                        $ITM_GROUP		= $rowOUT->ITM_GROUP;
			                                                        $ITM_NAME 		= $rowOUT->ITM_NAME;
			                                                        $ITM_UNIT 		= $rowOUT->ITM_UNIT;
			                                                        $STF_VOLM 		= $rowOUT->STF_VOLM;
			                                                        $STF_PRICE 		= $rowOUT->STF_PRICE;
			                                                        $STF_TOTAL 		= $rowOUT->STF_TOTAL;
			                                                        $ACC_ID 		= $rowOUT->ACC_ID;
			                                                        $ACC_ID_UM 		= $rowOUT->ACC_ID_UM;
			                                                        
			                                                        // CEK STOCK PER WH
			                                                            $ITM_STOCK	= 0;
			                                                            $sqlWHSTOCK	= "SELECT ITM_VOLM AS ITM_STOCK FROM tbl_item_whqty
			                                                                            WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'
			                                                                                AND WH_CODE = '$WH_CODE'";
			                                                            $resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
			                                                            foreach($resWHSTOCK as $rowSTOCK) :
			                                                                $ITM_STOCK	= $rowSTOCK->ITM_STOCK;
			                                                            endforeach;
			                                            
			                                                        if ($j==1) {
			                                                            echo "<tr class=zebra1>";
			                                                            $j++;
			                                                        } else {
			                                                            echo "<tr class=zebra2>";
			                                                            $j--;
			                                                        }
			                                                        ?> 
			                                                        <tr>
			                                                            <!-- NO URUT -->
			                                                            <td width="4%" height="25" style="text-align:left" nowrap>
			                                                                <?php
			                                                                    if($STF_STAT == 1)
			                                                                    {
			                                                                        ?>
			                                                                            <a href="#" onClick="deleteRow(<?php echo $currRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
			                                                                        <?php
			                                                                    }
			                                                                    else
			                                                                    {
			                                                                        echo "$currRow.";
			                                                                    }
			                                                                ?>
			                                                                <input style="display:none" type="Checkbox" id="data[<?php echo $currRow; ?>][chk]" name="data[<?php echo $currRow; ?>][chk]" value="<?php echo $currRow; ?>" onClick="pickThis(this,<?php echo $currRow; ?>)">
			                                                                <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >                                       					</td>
			                                                            <!-- ITM_CODE, ITM_TYPE, ITM_GROUP -->
			                                                            <td width="9%" style="text-align:left" nowrap>
			                                                                <?php print $ITM_CODE; ?>
			                                                                <input type="hidden" id="data<?php echo $currRow; ?>ITM_TYPE" name="data[<?php echo $currRow; ?>][ITM_TYPE]" value="OUT" width="10" size="15">
			                                                                <input type="hidden" id="data<?php echo $currRow; ?>ITM_CODE" name="data[<?php echo $currRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
			                                                                <input type="hidden" id="data<?php echo $currRow; ?>ITM_GROUP" name="data[<?php echo $currRow; ?>][ITM_GROUP]" value="<?php print $ITM_GROUP; ?>" width="10" size="15">
			                                                            </td>
			                                                            <!-- ITM_NAME -->
			                                                            <td width="69%" style="text-align:left">
			                                                                <?php echo $ITM_NAME; ?>
			                                                                <input type="hidden" id="data<?php echo $currRow; ?>ITM_NAME" name="data[<?php echo $currRow; ?>][ITM_NAME]" value="<?php print $ITM_NAME; ?>">
			                                                            </td>
			                                                            <!-- ITM_UNIT -->  
			                                                            <td width="5%" style="text-align:center">
			                                                                <?php echo $ITM_UNIT; ?>
			                                                                <input type="hidden" id="data<?php echo $currRow; ?>ITM_NAME" name="data[<?php echo $currRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
			                                                            </td>
			                                                            <!-- STF_VOLM, STF_PRICE -->
			                                                            <td width="13%" style="text-align:right" nowrap>
			                                                            	<?php echo number_format($STF_VOLM, 2); ?>
			                                                                <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM<?php echo $currRow; ?>" id="OUT<?php echo $currRow; ?>STF_VOLM" value="<?php echo number_format($STF_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLM(this, <?php echo $currRow; ?>);" >
			                                                                <input style="text-align:right" type="hidden" name="data[<?php echo $currRow; ?>][STF_VOLM]" id="data<?php echo $currRow; ?>STF_VOLM" value="<?php echo $STF_VOLM; ?>">
			                                                                <input type="hidden" style="text-align:right" name="data[<?php echo $currRow; ?>][STF_PRICE]" id="data<?php echo $currRow; ?>STF_PRICE" size="6" value="<?php echo $STF_PRICE; ?>">
			                                                          		<input type="hidden" style="text-align:right" name="data[<?php echo $currRow; ?>][ACC_ID]" id="data<?php echo $currRow; ?>ACC_ID" size="6" value="<?php echo $ACC_ID; ?>">
			                                                                <input type="hidden" style="text-align:right" name="data[<?php echo $currRow; ?>][ACC_ID_UM]" id="data<?php echo $currRow; ?>ACC_ID_UM" size="6" value="<?php echo $ACC_ID_UM; ?>">
			                                                            </td>
			                                                        </tr>
			                                                        <?php
			                                                    endforeach;
			                                                }
			                                            ?>
			                                            <input type="hidden" name="totalrowOUT" id="totalrowOUT" value="<?php echo $currRow; ?>">
			                                        </table>
			                                    </div>
			                                </div>
			                            <!-- END : OUTPUT ITEM -->
			                            
			                            <!-- START : RM NEEDED -->
			                                <div class="col-md-6">
			                                    <div class="box box-warning">
			                                        <br>
			                                        <div class="form-group" style="display:none">
			                                            <div class="col-sm-10">
			                                                <script>
			                                                    var urlRM = "<?php echo $url_RM;?>";
			                                                    function selectRM()
			                                                    {
			                                                        var JONUM	= $("#JO_NUM").val();
			                                                        var JOCODE	= $("#JO_CODE").val();
			                                                        var STFFROM	= $("#STF_FROM").val();
			                                                        var STFDEST	= $("#STF_DEST").val();
			                                                        
			                                                        if(JONUM == '')
			                                                        {
			                                                            swal('<?php echo $alert4; ?>');
			                                                            $("#JO_CODE1").focus();
			                                                            return false;
			                                                        }
			                                                        
			                                                        if(STFFROM == '')
			                                                        {
			                                                            swal('<?php echo $alert5; ?>');
			                                                            $("#STF_FROM").focus();
			                                                            return false;
			                                                        }
			                                                        
			                                                        if(STFDEST == '')
			                                                        {
			                                                            swal('<?php echo $alert6; ?>');
			                                                            $("#STF_DEST").focus();
			                                                            return false;
			                                                        }
			                                                        
			                                                        title = 'Select Item';
			                                                        w = 1000;
			                                                        h = 550;
			                                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
			                                                        var left = (screen.width/2)-(w/2);
			                                                        var top = (screen.height/2)-(h/2);
			                                                        return window.open(urlRM+'&JONUM='+JONUM+'&JOCODE='+JOCODE, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			                                                    }
			                                                </script>
			                                                <button class="btn btn-warning" type="button" onClick="selectRM();">
			                                                <i class="fa fa-cubes"></i>&nbsp;&nbsp;<?php echo $RawMtr; ?>
			                                                </button>
			                                            </div>
			                                        </div>
			                                        <table width="100%" border="1" id="tbl_IN">
			                                            <tr style="background:#CCCCCC">
			                                                <th width="4%" height="25" style="text-align:center">No.</th>
			                                                <th width="9%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
			                                                <th width="69%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
			                                                <th width="5%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
			                                                <th width="13%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th>
			                                            </tr>
			                                            <?php
			                                                $resRMC	= 0;
			                                                if($task == 'edit')
			                                                {																
			                                                    $sqlRM		= "SELECT A.ITM_TYPE, A.ITM_CODE, A.ITM_GROUP, B.ITM_NAME, B.ITM_UNIT, 
			                                                                        A.STF_VOLM, A.STF_PRICE, A.STF_TOTAL, A.ACC_ID, A.ACC_ID_UM
			                                                                    FROM tbl_stf_detail A
			                                                                        INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
			                                                                            AND B.PRJCODE = '$PRJCODE'
			                                                                    WHERE A.STF_NUM = '$STF_NUM'
																					AND A.ITM_TYPE = 'IN'";
			                                                    $resRM 	= $this->db->query($sqlRM)->result();
			                                                    
			                                                    $sqlRMC	= "tbl_stf_detail A
			                                                                        INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
			                                                                            AND B.PRJCODE = '$PRJCODE'
			                                                                    WHERE A.STF_NUM = '$STF_NUM'
																					AND A.ITM_TYPE = 'IN'";
			                                                    $resRMC 	= $this->db->count_all($sqlRMC);
			                                                }
			                                                    
			                                                $i			= 0;
			                                                $j			= 0;
			                                                if($resRMC > 0)
			                                                {
			                                                    foreach($resRM as $rowRM) :
			                                                        $currRow  		= ++$i;																
			                                                        $STF_NUM 		= $STF_NUM;
			                                                        $STF_CODE 		= $STF_CODE;
			                                                        $PRJCODE		= $PRJCODE;
			                                                        $ITM_TYPE		= $rowRM->ITM_TYPE;
			                                                        $ITM_CODE		= $rowRM->ITM_CODE;
			                                                        $ITM_GROUP		= $rowRM->ITM_GROUP;
			                                                        $ITM_NAME 		= $rowRM->ITM_NAME;
			                                                        $ITM_UNIT 		= $rowRM->ITM_UNIT;
			                                                        $STF_VOLM 		= $rowRM->STF_VOLM;
			                                                        $STF_PRICE 		= $rowRM->STF_PRICE;
			                                                        $STF_TOTAL 		= $rowRM->STF_TOTAL;
			                                                        $ACC_ID 		= $rowRM->ACC_ID;
			                                                        $ACC_ID_UM 		= $rowRM->ACC_ID_UM;
			                                                        
			                                                        // CEK STOCK PER WH
			                                                            $ITM_STOCK	= 0;
			                                                            $sqlWHSTOCK	= "SELECT ITM_VOLM AS ITM_STOCK FROM tbl_item_whqty
			                                                                            WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'
			                                                                                AND WH_CODE = '$WH_CODE'";
			                                                            $resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
			                                                            foreach($resWHSTOCK as $rowSTOCK) :
			                                                                $ITM_STOCK	= $rowSTOCK->ITM_STOCK;
			                                                            endforeach;
			                                            
			                                                        if ($j==1) {
			                                                            echo "<tr class=zebra1>";
			                                                            $j++;
			                                                        } else {
			                                                            echo "<tr class=zebra2>";
			                                                            $j--;
			                                                        }
			                                                        ?> 
			                                                        <tr>
			                                                            <!-- NO URUT -->
			                                                            <td width="4%" height="25" style="text-align:left" nowrap>
			                                                                <?php
			                                                                    if($STF_STAT == 1)
			                                                                    {
			                                                                        ?>
			                                                                            <a href="#" onClick="deleteRow(<?php echo $currRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
			                                                                        <?php
			                                                                    }
			                                                                    else
			                                                                    {
			                                                                        echo "$currRow.";
			                                                                    }
			                                                                ?>
			                                                                <input style="display:none" type="Checkbox" id="dataRM[<?php echo $currRow; ?>][chk]" name="dataRM[<?php echo $currRow; ?>][chk]" value="<?php echo $currRow; ?>" onClick="pickThis(this,<?php echo $currRow; ?>)">
			                                                                <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >                                       					</td>
			                                                            <!-- ITM_CODE, ITM_TYPE, ITM_GROUP -->
			                                                            <td width="9%" style="text-align:left" nowrap>
			                                                                <?php print $ITM_CODE; ?>
			                                                                <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_TYPE" name="dataRM[<?php echo $currRow; ?>][ITM_TYPE]" value="IN" width="10" size="15">
			                                                                <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_CODE" name="dataRM[<?php echo $currRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
			                                                                <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_GROUP" name="dataRM[<?php echo $currRow; ?>][ITM_GROUP]" value="<?php print $ITM_GROUP; ?>" width="10" size="15">
			                                                            </td>
			                                                            <!-- ITM_NAME -->
			                                                            <td width="69%" style="text-align:left">
			                                                                <?php echo $ITM_NAME; ?>
			                                                                <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_NAME" name="dataRM[<?php echo $currRow; ?>][ITM_NAME]" value="<?php print $ITM_NAME; ?>">
			                                                            </td>
			                                                            <!-- ITM_UNIT -->  
			                                                            <td width="5%" style="text-align:center">
			                                                                <?php echo $ITM_UNIT; ?>
			                                                                <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_NAME" name="dataRM[<?php echo $currRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
			                                                            </td>
			                                                            <!-- STF_VOLM, STF_PRICE -->
			                                                            <td width="13%" style="text-align:right" nowrap>
			                                                            	<?php echo number_format($STF_VOLM, 2); ?>
			                                                                <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM<?php echo $currRow; ?>" id="IN<?php echo $currRow; ?>STF_VOLM" value="<?php echo number_format($STF_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, <?php echo $currRow; ?>);" >
			                                                                <input style="text-align:right" type="hidden" name="dataRM[<?php echo $currRow; ?>][STF_VOLM]" id="dataRM<?php echo $currRow; ?>STF_VOLM" value="<?php echo $STF_VOLM; ?>">
			                                                                <input type="hidden" style="text-align:right" name="dataRM[<?php echo $currRow; ?>][STF_PRICE]" id="dataRM<?php echo $currRow; ?>STF_PRICE" size="6" value="<?php echo $STF_PRICE; ?>">
			                                                                <input type="hidden" style="text-align:right" name="dataRM[<?php echo $currRow; ?>][ACC_ID]" id="dataRM<?php echo $currRow; ?>ACC_ID" size="6" value="<?php echo $ACC_ID; ?>">
			                                                                <input type="hidden" style="text-align:right" name="dataRM[<?php echo $currRow; ?>][ACC_ID_UM]" id="dataRM<?php echo $currRow; ?>ACC_ID_UM" size="6" value="<?php echo $ACC_ID_UM; ?>">
			                                                            </td>
			                                                        </tr>
			                                                        <?php
			                                                    endforeach;
			                                                }
			                                            ?>
			                                            <input type="hidden" name="totalrowRM" id="totalrowRM" value="<?php echo $currRow; ?>">
			                                        </table>
			                                    </div>
			                                </div>
			                            <!-- END : RM NEEDED -->
			                        </div>
			                        <br>
			                        <div class="form-group">
			                            <div class="col-md-12">
			                            	<?php
												if($disableAll == 0)
												{
													if(($STF_STAT == 2 || $STF_STAT == 7) && $canApprove == 1)
													{
														?>
															<button class="btn btn-primary" >
															<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
															</button>&nbsp;
														<?php
													}
												}
												echo anchor("$cancelURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
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

<?php
	if($LangID == 'IND')
	{
		$qtyDetOUT	= 'Material keluaran tidak boleh kosong.';
		$qtyDetRM	= 'Detail bahan yang digunakan tidak boleh kosong.';
		$volmAlert	= 'Qty order tidak boleh nol.';
	}
	else
	{
		$qtyDetOUT	= 'Output material can not be empty.';
		$qtyDetRM	= 'The details of the material used cannot be empty.';
		$volmAlert	= 'Order qty can not be zero.';
	}
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
  
	var decFormat		= 2;
	
	function add_header(strItem) 
	{
		arrItem = strItem.split('|');
		JO_NUM	= arrItem[0];
		JO_CODE	= arrItem[1];
		
		$(document).ready(function(e) {
			$("#JO_NUM").val(JO_NUM);
            $("#JO_CODE").val(JO_CODE);
            $("#JO_CODE1").val(JO_CODE);
        });
	}
	
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_GROUP 		= arrItem[2];
		ITM_NAME 		= arrItem[3];
		ITM_UNIT 		= arrItem[4];
		STF_VOLM 		= arrItem[5];
		REM_QTY			= arrItem[6];
		STF_PRICE 		= arrItem[7];
		ACC_ID 			= arrItem[8];
		ACC_ID_UM 		= arrItem[9];
		
		objTable 		= document.getElementById('tbl_OUT');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// ITM_CODE, ITM_TYPE, ITM_GROUP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_TYPE" name="data['+intIndex+'][ITM_TYPE]" value="OUT" width="10" size="15"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="data'+intIndex+'ITM_NAME" name="data['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'">';
				
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// STF_VOLM, STF_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM'+intIndex+'" id="OUT'+intIndex+'STF_VOLM" value="0.00" onBlur="cVOLM(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][STF_VOLM]" id="data'+intIndex+'STF_VOLM" value="0.00"><input type="hidden" style="text-align:right" name="data['+intIndex+'][STF_PRICE]" id="data'+intIndex+'STF_PRICE" size="6" value="'+STF_PRICE+'"><input type="hidden" style="text-align:right" name="data['+intIndex+'][ACC_ID]" id="data'+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="data['+intIndex+'][ACC_ID_UM]" id="data'+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		
		document.getElementById('totalrowOUT').value = intIndex;
	}
	
	function add_itemRM(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_GROUP 		= arrItem[2];
		ITM_NAME 		= arrItem[3];
		ITM_UNIT 		= arrItem[4];
		STF_VOLM 		= arrItem[5];
		REM_QTY			= arrItem[6];
		STF_PRICE 		= arrItem[7];
		ACC_ID 			= arrItem[8];
		ACC_ID_UM 		= arrItem[9];
		
		objTable 		= document.getElementById('tbl_IN');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="dataRM['+intIndex+'][chk]" name="dataRM['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// ITM_CODE, ITM_TYPE, ITM_GROUP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="dataRM'+intIndex+'ITM_TYPE" name="dataRM['+intIndex+'][ITM_TYPE]" value="IN" width="10" size="15"><input type="hidden" id="dataRM'+intIndex+'ITM_CODE" name="dataRM['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="dataRM'+intIndex+'ITM_GROUP" name="dataRM['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="dataRM'+intIndex+'ITM_NAME" name="dataRM['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'">';
				
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="dataRM'+intIndex+'ITM_UNIT" name="dataRM['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// STF_VOLM, STF_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM'+intIndex+'" id="IN'+intIndex+'STF_VOLM" value="0.00" onBlur="cVOLMRM(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="dataRM['+intIndex+'][STF_VOLM]" id="dataRM'+intIndex+'STF_VOLM" value="0.00"><input type="hidden" style="text-align:right" name="dataRM['+intIndex+'][STF_PRICE]" id="dataRM'+intIndex+'STF_PRICE" size="6" value="'+STF_PRICE+'"><input type="hidden" style="text-align:right" name="dataRM['+intIndex+'][ACC_ID]" id="dataRM'+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="dataRM['+intIndex+'][ACC_ID_UM]" id="dataRM'+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		
		document.getElementById('totalrowRM').value = intIndex;
	}
	
	function cVOLM(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		STF_VOLM1		= document.getElementById('OUT'+row+'STF_VOLM');
		STF_VOLM 		= parseFloat(eval(STF_VOLM1).value.split(",").join(""));
		
		document.getElementById('data'+row+'STF_VOLM').value 	= parseFloat(Math.abs(STF_VOLM));
		document.getElementById('OUT'+row+'STF_VOLM').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(STF_VOLM)),decFormat));
	}
	
	function cVOLMRM(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		STF_VOLM1		= document.getElementById('IN'+row+'STF_VOLM');
		STF_VOLM 		= parseFloat(eval(STF_VOLM1).value.split(",").join(""));
		
		document.getElementById('dataRM'+row+'STF_VOLM').value 	= parseFloat(Math.abs(STF_VOLM));
		document.getElementById('IN'+row+'STF_VOLM').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(STF_VOLM)),decFormat));
	}
	
	function checkInp()
	{
		STF_NOTES1	= document.getElementById('STF_NOTES1').value;	
		STF_STAT	= document.getElementById('STF_STAT').value;
		
		if(STF_NOTES1 == '')
		{
			swal('<?php echo $alert8; ?>');
			document.getElementById('STF_NOTES1').focus();
			return false;
		}
		
		if(STF_STAT == 0)
		{
			swal('<?php echo $alert7; ?>');
			document.getElementById('STF_STAT').focus();
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