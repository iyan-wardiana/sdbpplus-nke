<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 November 2017
 * File Name	= v_inb_po_list_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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

$PO_NUM 		= $default['PO_NUM'];
$DocNumber		= $PO_NUM;
$PO_CODE 		= $default['PO_CODE'];
$PO_TYPE 		= $default['PO_TYPE'];
$PO_CAT 		= $default['PO_CAT'];
$PO_DATE 		= $default['PO_DATE'];
$PO_DUED 		= $default['PO_DUED'];
$PO_DATE		= date('m/d/Y', strtotime($PO_DATE));
$PO_DUED		= date('m/d/Y', strtotime($PO_DUED));
$PRJCODE 		= $default['PRJCODE'];
$SPLCODE 		= $default['SPLCODE'];
$PR_NUM 		= $default['PR_NUM'];
$PR_NUMX		= $PR_NUM;
$PO_CURR 		= $default['PO_CURR'];
$PO_CURRATE 	= $default['PO_CURRATE'];
$PO_PAYTYPE 	= $default['PO_PAYTYPE'];
$PO_TENOR 		= $default['PO_TENOR'];
$PO_PLANIR 		= $default['PO_PLANIR'];
$PO_NOTES 		= $default['PO_NOTES'];
$PO_PAYNOTES 	= $default['PO_PAYNOTES'];
$PO_NOTES1		= '';
$PRJNAME1 		= $default['PRJNAME'];
$PO_TOTCOST		= $default['PO_TOTCOST'];
$PO_STAT 		= $default['PO_STAT'];
$lastPatternNumb1= $default['Patt_Number'];

$PO_TAXRATE			= 1;
$totTaxPPnAmount	= 1;
$totTaxPPhAmount	= 1;

$PR_NUMX		= $PR_NUMX;

//echo $PR_NUMX;

$secGenCode	= base_url().'index.php/c_purchase/c_p180c21o/genCode/'; // Generate Code

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/iCheck/flat/blue.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/morris/morris.css') ?>" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ilmudetil.css') ?>">
    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/highcharts.js') ?>" type="text/javascript"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
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
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Cancel')$Cancel = $LangTransl;
		if($TranslCode == 'PONumber')$PONumber = $LangTransl;
		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
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
		if($TranslCode == 'Request')$Request = $LangTransl;
		if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'Approver')$Approver = $LangTransl;
		if($TranslCode == 'Approved')$Approved = $LangTransl;
		if($TranslCode == 'Approval')$Approval = $LangTransl;
		if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
		if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
	endforeach;
	
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
			$APPROVE_AMOUNT 	= $PO_TOTCOST;
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

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $Purchase; ?>
    <small><?php echo $PRJNAME1; ?></small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
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
                	<!-- Mencari Kode Purchase Requase Number -->
                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="text" name="PR_NUMX" id="PR_NUMX" class="textbox" value="<?php echo $PR_NUMX; ?>" />
                        <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
                    <!-- End -->
                    
                    <!-- Mencari Kode Purchase Requase Number -->
                    <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
                        <table>
                            <tr>
                                <td>
                                    <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
                                    <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
                                    <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
                                    <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
                                    <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
                                    <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
                                    <input type="hidden" name="PODate" id="PODate" value="">
                                </td>
                                <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                            </tr>
                        </table>
                    </form>
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                        <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
                        <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
           				<input type="hidden" name="rowCount" id="rowCount" value="0">
           				<input type="Hidden" name="ISDIRECT" id="ISDIRECT" value="0">
                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PONumber ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:195px" name="PO_NUM1" id="PO_NUM1" value="<?php echo $DocNumber; ?>" disabled >
                       			<input type="hidden" class="textbox" name="PO_NUM" id="PO_NUM" size="30" value="<?php echo $DocNumber; ?>" />
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:195px" name="PO_CODE" id="PO_CODE" value="<?php echo $PO_CODE; ?>" disabled >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="hidden" name="PO_DATE" id="PO_DATE" class="form-control pull-left" value="<?php echo $PO_DATE; ?>" style="width:150px" onChange="getPO_NUM(this.value)">
                                    <input type="hidden" name="PO_DUED" id="PO_DUED" class="form-control pull-left" value="<?php echo $PO_DUED; ?>" style="width:150px">
                                    <input type="text" name="PO_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $PO_DATE; ?>" style="width:150px" onChange="getPO_NUM(this.value)" disabled>
                                </div>
                          	</div>
                        </div>
                        <script>
                            function getPO_NUM(selDate)
                            {
                                document.getElementById('PODate').value = selDate;
                                document.getElementById('dateClass').click();
                            }
	
							$(document).ready(function()
							{
								$(".tombol-date").click(function()
								{
									var add_PO	= "<?php echo $secGenCode; ?>";
									var formAction 	= $('#sendDate')[0].action;
									var data = $('.form-user').serialize();
									$.ajax({
										type: 'POST',
										url: formAction,
										data: data,
										success: function(response)
										{
											var myarr = response.split("~");
											document.getElementById('PO_NUM1').value 	= myarr[0];
											document.getElementById('PO_NUM').value 	= myarr[0];
											document.getElementById('PO_CODE').value 	= myarr[1];
										}
									});
								});
							});
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $RequestCode ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-btn">
										<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
                                    </div>
                                    <input type="hidden" class="form-control" name="PR_NUM" id="PR_NUM" style="max-width:160px" value="<?php echo $PR_NUMX; ?>" >
                                    <input type="text" class="form-control" name="PR_NUM1" id="PR_NUM1" style="max-width:180px" value="<?php echo $PR_NUMX; ?>" onClick="pleaseCheck();" <?php if($task != 'add') { ?> disabled <?php } ?>>
                                </div>
                            </div>
                        </div>
						<?php
							//$url_selAURCODE	= site_url('c_asset/c_asset_usage/popupallaur/?id='.$this->url_encryption_helper->encode_url($AU_PRJCODE));
							$url_selPR_CODE		= site_url('c_purchase/c_p180c21o/popupallmr/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        ?>
                        <script>
							var url1 = "<?php echo $url_selPR_CODE;?>";
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SupplierName ?> </label>
                          	<div class="col-sm-10">
                            	<select name="SPLCODE" id="SPLCODE" class="form-control" style="max-width:250px" onChange="getVendName(this.value)" disabled>
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
                                        if($task == 'add')
                                        {
                                            ?>
                                                <option value="0" <?php if($SPLCODE == 0) { ?> selected <?php } ?>>--- None ---</option>
                                            <?php
                                        }
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Currency ?> </label>
                          	<div class="col-sm-10">
                            	<select name="PO_CURR" id="PO_CURR" class="form-control" style="max-width:75px" disabled>
                                	<option value="IDR" <?php if($PO_CURR == 'IDR') { ?> selected <?php } ?>>IDR</option>
                                	<option value="USD" <?php if($PO_CURR == 'USD') { ?> selected <?php } ?>>USD</option>    
                                </select>
                          	</div>
                        </div>
                        <div class="form-group" style="display:none">
                       	  <label for="inputName" class="col-sm-2 control-label"><?php echo $PaymentType ?> </label>
                          	<div class="col-sm-10">
                                <select name="PO_PAYTYPE" id="PO_PAYTYPE" class="form-control" style="max-width:85px" onChange="selPO_PAYTYPE(this)" disabled>
                                    <option value="1" <?php if($PO_PAYTYPE == 'Cash') { ?> selected="selected" <?php } ?>>Cash</option>
                                    <option value="2" <?php if($PO_PAYTYPE == 'Credit') { ?> selected="selected" <?php } ?>>Credit</option>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PaymentTerm ?> </label>
                          	<div class="col-sm-10">
                                <select name="PO_TENOR" id="PO_TENOR" class="form-control" style="max-width:100px" onChange="selPO_TENOR(this)" disabled>
                                    <option value="0" <?php if($PO_TENOR == 0) { ?> selected <?php } ?>>Cash</option>
                                    <option value="7" <?php if($PO_TENOR == 7) { ?> selected <?php } ?>>7 Days</option>
                                    <option value="15" <?php if($PO_TENOR == 15) { ?> selected <?php } ?>>15 Days</option>
                                    <option value="30" <?php if($PO_TENOR == 30) { ?> selected <?php } ?>>30 Days</option>
                                    <option value="45" <?php if($PO_TENOR == 45) { ?> selected <?php } ?>>45 Days</option>
                                    <option value="60" <?php if($PO_TENOR == 60) { ?> selected <?php } ?>>60 Days</option>
                                    <option value="75" <?php if($PO_TENOR == 75) { ?> selected <?php } ?>>75 Days</option>
                                    <option value="90" <?php if($PO_TENOR == 90) { ?> selected <?php } ?>>90 Days</option>
                                    <option value="120" <?php if($PO_TENOR == 120) { ?> selected <?php } ?>>120 Days</option>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptDate ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="PO_PLANIR" class="form-control pull-left" id="datepicker2" value="<?php echo $PO_PLANIR; ?>" style="width:150px" disabled>
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?> </label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="PO_NOTES"  id="PO_NOTES" style="max-width:350px;height:70px" disabled><?php echo $PO_NOTES; ?></textarea>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes; ?> </label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="PO_NOTES1"  id="PO_NOTES1" style="max-width:350px;height:70px" disabled><?php echo $PO_NOTES1; ?></textarea>
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
                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $PO_STAT; ?>">
                            	<input type="hidden" name="PO_STATx" id="PO_STATx" value="<?php echo $PO_STAT; ?>">
								<?php
									if($canApprove == 1 && $disableAll == 0)
									{
										if($ISAPPROVE == 1)
										{
											$disButton	= 0;
											$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$PO_NUM' AND AH_APPROVER = '$DefEmp_ID'";
											$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
											if($resCAPPHE > 0)
												$disButton	= 1;
											?>
												<select name="PO_STAT" id="PO_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" disabled >
													<option value="0"> -- </option>
													<option value="3"<?php if($PO_STAT == 3) { ?> selected <?php } ?>>Approved</option>
													<option value="4"<?php if($PO_STAT == 4) { ?> selected <?php } ?>>Revised</option>
													<option value="5"<?php if($PO_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
													<option value="6"<?php if($PO_STAT == 6) { ?> selected <?php } ?>>Closed</option>
													<option value="7"<?php if($PO_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
												</select>
											<?php
										}
									}
									else
									{
										echo "You can not approve this document.";
									}
                                ?>
                            </div>
                        </div>
                        <script>
							function selStat(thisVal)
							{
								document.getElementById('PO_STATx').value = thisVal;
							}
						</script>
                        <?php if($PO_STAT == 9) 
						{ 
							?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ProcessStatus ?> </label>
                                    <div class="col-sm-10">
                                        <select name="OP_PROCS" id="OP_PROCS" class="form-control" style="max-width:130px" <?php if($OP_PROCS != 1) { ?> disabled <?php } ?>>
                                            <option value="1" <?php if($OP_PROCS == 1) { ?> selected <?php } ?>>Processing</option>
                                            <option value="2" <?php if($OP_PROCS == 2) { ?> selected <?php } ?>>Finish</option>
                                            <option value="3" <?php if($OP_PROCS == 3) { ?> selected <?php } ?>>Canceled</option>
                                        </select>
                                    </div>
                                </div>
							<?php
						}
						$url_AddItem	= site_url('c_purchase/c_p180c21o/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        ?>
                        <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                          	<div class="col-sm-10">
								<script>
									var url = "<?php echo $url_AddItem;?>";
									function selectitem()
									{
										title = 'Select Item';
										w = 1000;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
									}
								</script>
								<a href="javascript:void(null);" onClick="selectitem();">
									Add Item [+]
                                </a></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-primary">
                                <br>
                                <table width="100%" border="1" id="tbl">
                                    <tr style="background:#CCCCCC">
                                        <th width="2%" height="25" style="text-align:center">No.</th>
                                      <th width="7%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
                                      <th width="16%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
                                      <th width="7%" style="text-align:center" nowrap><?php echo $Request; ?></th>
                                      <th width="7%" style="text-align:center" nowrap><?php echo $Remain; ?></th>
                                      <th width="12%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th> 
                              <!-- Input Manual -->
                                        <th width="5%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
                                      <th width="5%" style="text-align:center" nowrap><?php echo $UnitPrice; ?> </th>
                                      <th width="6%" style="text-align:center" nowrap><?php echo $Discount; ?><br>
                                      (%)</th>
                                      <th width="12%" style="text-align:center" nowrap><?php echo $Discount; ?> </th>
                                      <th width="9%" style="text-align:center" nowrap><?php echo $Tax; ?></th>
                                      <th width="12%" style="text-align:center" nowrap><?php echo $Price; ?></th>
                                  </tr>
                                    <?php
										$resultC	= 0;
										if($task == 'add' && $PR_NUMX != '')
										{
											$sqlDETPR	= "SELECT A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, A.SNCODE, A.ITM_UNIT, A.PR_VOLM,
																0 AS PO_VOLM, '' AS IR_VOLM, 0 AS IR_AMOUNT, 0 AS IR_PAVG, 0 AS PO_DISP, 0 AS PO_DISC,
																A.PO_VOLM AS ORDERED_QTY,
																A.PR_PRICE AS ITM_PRICE, A.PR_TOTAL, A.PR_DESC AS PO_DESC, A.PR_DESC1 AS PO_DESC1,
																A.TAXCODE1, A.TAXCODE2,	A.TAXPRICE1, A.TAXPRICE2,
																B.ITM_NAME
															FROM tbl_pr_detail A
																INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	AND B.PRJCODE = '$PRJCODE'
															WHERE PR_NUM = '$PR_NUMX' 
																AND B.PRJCODE = '$PRJCODE'";
											$result 	= $this->db->query($sqlDETPR)->result();
											
											$sqlDETC	= "tbl_pr_detail A
																INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	AND B.PRJCODE = '$PRJCODE'
															WHERE PR_NUM = '$PR_NUMX' 
																AND B.PRJCODE = '$PRJCODE'";
											$resultC 	= $this->db->count_all($sqlDETC);
										}
										else
										{
											if($task == 'edit')
											{											
												//*from data
												$sqlDET		= "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PRJCODE, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, 
																A.PR_VOLM, A.PO_VOLM, A.PO_VOLM AS POVOLM, A.IR_VOLM, A.IR_AMOUNT, A.IR_PAVG,
																A.PO_PRICE AS PR_PRICE, A.PO_PRICE AS ITM_PRICE, A.PO_DISP, A.PO_DISC, A.PO_COST, A.PO_DESC, A.PO_DESC1,
																A.TAXCODE1, A.TAXCODE2,	A.TAXPRICE1, A.TAXPRICE2,
																B.ITM_NAME
																FROM tbl_po_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																WHERE 
																	A.PO_NUM = '$PO_NUM' 
																	AND B.PRJCODE = '$PRJCODE'";
												$result = $this->db->query($sqlDET)->result();
												// count data
												$sqlDETC	= "tbl_po_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																WHERE 
																	A.PO_NUM = '$PO_NUM' 
																	AND B.PRJCODE = '$PRJCODE'";
												$resultC 	= $this->db->count_all($sqlDETC);
											}
										}
										$i			= 0;
										$j			= 0;
										if($resultC > 0)
										{
											$GTITMPRICE		= 0;
											$PO_TOTCOST		= 0;
											foreach($result as $row) :
												$currentRow  	= ++$i;																
												$PO_NUM 		= $PO_NUM;
												$PO_CODE 		= $PO_CODE;
												$PRJCODE		= $PRJCODE;
												$PR_NUM			= $row->PR_NUM;
												$JOBCODEDET		= $row->JOBCODEDET;
												$JOBCODEID		= $row->JOBCODEID;
												$ITM_CODE 		= $row->ITM_CODE;
												$ITM_NAME 		= $row->ITM_NAME;
												$ITM_UNIT 		= $row->ITM_UNIT;
												$ITM_PRICE 		= $row->ITM_PRICE;
												$PR_VOLM 		= $row->PR_VOLM;
												$PO_VOLM 		= $row->PO_VOLM;
												$IR_VOLM 		= $row->IR_VOLM;
												$IR_AMOUNT 		= $row->IR_AMOUNT;
												$IR_PAVG 		= $row->IR_PAVG;
												$PO_PRICE 		= $row->ITM_PRICE;
												$PO_DISP 		= $row->PO_DISP;
												$PO_DISC 		= $row->PO_DISC;
												
												// GET TOTAL ORDERED
												$ORDERED_QTY	= 0;
												$sqlTOTORD		= "SELECT SUM(A.PO_VOLM) AS ORDERED_QTY 
																	FROM tbl_po_detail A
																		INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
																	WHERE B.PRJCODE = '$PRJCODE' AND A.JOBCODEDET = '$JOBCODEDET' AND A.JOBCODEID = '$JOBCODEID'
																	AND A.PO_NUM != '$PO_NUM' AND B.PO_STAT = '3'";
												$resTOTORD		= $this->db->query($sqlTOTORD)->result();
												foreach($resTOTORD as $rowTOTORD) :
													$ORDERED_QTY  	= $rowTOTORD->ORDERED_QTY;;
												endforeach;
												
												$ORDERED_QTY	= $ORDERED_QTY;
												$ITM_TOTAL 		= $PO_VOLM * $ITM_PRICE;
												$PR_DESC		= $row->PO_DESC;
												$PO_DES1		= $row->PO_DESC1;
												$TAXCODE1		= $row->TAXCODE1;
												$TAXCODE2		= $row->TAXCODE2;
												$TAXPRICE1		= $row->TAXPRICE1;
												$TAXPRICE2		= $row->TAXPRICE2;
												$itemConvertion	= 1;
												
												$ITM_REMAIN		= $PR_VOLM - $ORDERED_QTY;
												
												$PO_ITMCOST		= $ITM_TOTAL - $PO_DISC;
												$GT_ITMPRICE	= $PO_ITMCOST;
												if($TAXCODE1 == 'TAX01')
												{
													$GTITMPRICE	= $GTITMPRICE + $PO_ITMCOST + $TAXPRICE1;
													$GT_ITMPRICE= $GTITMPRICE;
												}
												if($TAXCODE1 == 'TAX02')
												{
													$GTITMPRICE	= $GTITMPRICE + $PO_ITMCOST - $TAXPRICE1;
													$GT_ITMPRICE= $GTITMPRICE;
												}
												
												// COUNT PO_TOTCOST
												$PO_TOTCOST		= $PO_TOTCOST + $GT_ITMPRICE;
																					
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
												<td width="2%" height="25" style="text-align:left">
													<?php
                                                        if($PO_STAT == 1)
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
													<input style="display:none" type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)">
													<input type="Checkbox" style="display:none" id="chk" name="chk" value="" >                                       			</td>
                                                <!-- ITEM CODE -->
                                                <td width="7%" style="text-align:left">
													<?php print $ITM_CODE; ?>
													<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
													<input type="hidden" id="data<?php echo $currentRow; ?>PO_NUM" name="data[<?php echo $currentRow; ?>][PO_NUM]" value="<?php print $DocNumber; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>PO_CODE" name="data[<?php echo $currentRow; ?>][PO_CODE]" value="<?php print $PO_CODE; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>PR_NUM" name="data[<?php echo $currentRow; ?>][PR_NUM]" value="<?php print $PR_NUMX; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php print $JOBCODEDET; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php print $JOBCODEID; ?>" width="10" size="15"></td>
                                                <!-- ITEM NAME -->
												<td width="16%" style="text-align:left"><?php echo $ITM_NAME; ?></td>
                                                
                                                <!-- ITEM BUDGET -->
												<td width="7%" nowrap style="text-align:right"><?php print number_format($PR_VOLM, $decFormat); ?></td>
                                                
                                                <!-- ITEM REMAIN -->
											  	<td width="7%" nowrap style="text-align:right">
                                                	<?php print number_format($ITM_REMAIN, $decFormat); ?>
                                                    <input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_REMAIN<?php echo $currentRow; ?>" id="ITM_REMAIN<?php echo $currentRow; ?>" value="<?php echo $ITM_REMAIN; ?>" ></td>
                                                    
                                                <!-- ITEM ORDER NOW -->  
											  	<td width="12%" style="text-align:right">
													<input type="text" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="PO_VOLMx<?php echo $currentRow; ?>" id="PO_VOLMx<?php echo $currentRow; ?>" value="<?php print number_format($PO_VOLM, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $currentRow; ?>);" disabled >
                                                     <input type="hidden" id="data<?php echo $currentRow; ?>PR_VOLM" name="data[<?php echo $currentRow; ?>][PR_VOLM]" value="<?php print $PR_VOLM; ?>">
                                                     <input type="hidden" id="data<?php echo $currentRow; ?>PO_VOLM" name="data[<?php echo $currentRow; ?>][PO_VOLM]" value="<?php print $PO_VOLM; ?>"></td>
                                                    
                                                <!-- ITEM UNIT -->
												<td width="5%" style="text-align:center">
													<?php print $ITM_UNIT; ?>  
                                                     <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>"></td>
												<?php
													/* Perhitungan ........
													$totPriceItem	= $PR_VOLM * $CSTPUNT;
													$totalAmount	= $totalAmount + $totPriceItem;
													$BtotalAmount	= $BtotalAmount + ($totPriceItem * $PO_CURRATE);
													End */
												?>
                                                
                                                <!-- ITEM PRICE -->
												<td width="5%" style="text-align:left">
											  		<input type="text" class="form-control" style="text-align:right; min-width:100px" name="PO_PRICEx<?php echo $currentRow; ?>" id="PO_PRICEx<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $currentRow; ?>);" disabled>
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_PRICE]" id="data<?php echo $currentRow; ?>PO_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>"></td>
                                                 
                                                <!-- ITEM DISCOUNT PERCENTATION -->
												<td width="6%" style="text-align:right">
													<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="PO_DISPx<?php echo $currentRow; ?>" id="PO_DISPx<?php echo $currentRow; ?>" value="<?php print number_format($PO_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $currentRow; ?>);" disabled >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_DISP]" id="data<?php echo $currentRow; ?>PO_DISP" value="<?php echo $PO_DISP; ?>"></td>
													
												<!-- ITEM DISCOUNT -->
												<td width="12%" style="text-align:left">
													<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="PO_DISC<?php echo $currentRow; ?>" id="PO_DISC<?php echo $currentRow; ?>" value="<?php print number_format($PO_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $currentRow; ?>);" disabled >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_DISC]" id="data<?php echo $currentRow; ?>PO_DISC" value="<?php echo $PO_DISC; ?>"></td>
                                                    
												<!-- ITEM TAX -->
												<td width="9%" style="text-align:left">
                                               	  <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" class="form-control" id="data<?php echo $currentRow; ?>TAXCODE1"  onChange="getValuePO(this, <?php echo $currentRow; ?>);" style="min-width:100px; max-width:150px" disabled>
														<option value="">--- None ---</option>
														<option value="Tax001" <?php if ($TAXCODE1 == "TAX01") { ?> selected <?php } ?>>PPn 10%</option>
														<option value="Tax002" <?php if ($TAXCODE1 == "TAX02") { ?> selected <?php } ?> disabled>PPh</option>
													</select></td>
                                                
											  <!-- ITEM TOTAL COST -->
												<td width="12%" style="text-align:left">
                                           	    <input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="PO_COSTx<?php echo $currentRow; ?>" id="PO_COSTx<?php echo $currentRow; ?>" value="<?php print number_format($GT_ITMPRICE, $decFormat); ?>" disabled >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_COST]" id="data<?php echo $currentRow; ?>PO_COST" value="<?php echo $ITM_TOTAL; ?>"></td>
												
								  <?php
													$TAXPRICE1	= 0; // Karena Add selalu 0
													$BTAXPRICE1	= $TAXPRICE1 * $PO_TAXRATE;
													$TAXCODE1	= "Tax001";
													if($TAXCODE1 == "Tax001")
													{
														$totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICE1;
														$TAXPRICEPPh1		= 0;
														$totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICEPPh1;
													}
													else
													{
														$totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICE1;
														$TAXPRICEPPn1		= 0;
														$totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICEPPn1;
													}
												?>
											  <?php
													/*
													$TAXPRICE2 	= 0;
													$TAXCODE2 	= "Tax001";
													$BTAXPRICE2	= $TAXPRICE2 * $PO_TAXRATE;
													if($TAXCODE2 == "Tax001")
													{
														$totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICE2;
														$TAXPRICEPPh2		= 0;
														$totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICEPPh2;
													}
													else
													{
														$totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICE2;
														$TAXPRICEPPn2		= 0;
														$totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICEPPn2;
													}
													*/
												?>
											</tr>
												<?php
											endforeach;
											?>
											<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
											<input type="hidden" name="PO_TOTCOST" id="PO_TOTCOST" value="<?php echo $PO_TOTCOST; ?>">
											<?php
										}
                                    ?>
                                </table>
                              </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            	<?php
									/*if($canApprove == 1 && $disableAll == 0)
									{
										if(($PO_STAT == 2 || $PO_STAT == 7) && $ISAPPROVE == 1)
										{
											?>
												<button class="btn btn-primary" >
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
									}*/
									$backURL	= site_url('c_purchase/c_p180c21o/inbox/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
								?>
                            </div>
                        </div>
						<?php
							$DOC_NUM	= $PO_NUM;
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
		                                    
		                                    /*if($resCAPPH == 1)
		                                    {
		                                        $Approver	= $Awaiting;
		                                        $boxCol_3	= "yellow";
		                                        $class		= "glyphicon glyphicon-info-sign";
		                                    }*/
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
		                                    
		                                    /*if($resCAPPH == 2)
		                                    {
		                                        $Approver	= $Awaiting;
		                                        $boxCol_4	= "yellow";
		                                        $class		= "glyphicon glyphicon-info-sign";
		                                    }*/
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
		                                    
		                                    /*if($resCAPPH == 3)
		                                    {
		                                        $Approver	= $Awaiting;
		                                        $boxCol_5	= "yellow";
		                                        $class		= "glyphicon glyphicon-info-sign";
		                                    }*/
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</body>

</html>

<?php
	if($LangID == 'IND')
	{
		$alert1	= 'Silahkan pilih status pemesanan.';
	}
	else
	{
		$alert1	= 'Please select order status.';
	}
?>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
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
		PO_STAT		= document.getElementById('PO_STAT').value;
		
		if(PO_STAT == 0)
		{
			swal('<?php echo $alert1; ?>');
			document.getElementById('PO_STAT').focus();
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
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>