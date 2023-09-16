<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Oktober 2018
	* File Name	= v_shipment_form.php
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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
$isSetDocNo = 1;
$SN_NUM 		= $default['SN_NUM'];
$DocNumber		= $SN_NUM;
$SN_CODE 		= $default['SN_CODE'];
$SN_TYPE 		= $default['SN_TYPE'];
$SN_DATE 		= $default['SN_DATE'];
$SN_DATE		= date('m/d/Y', strtotime($SN_DATE));
$SN_RECEIVED	= $default['SN_RECEIVED'];
$SN_RECEIVED	= date('m/d/Y', strtotime($SN_RECEIVED));
$PRJCODE 		= $default['PRJCODE'];
$CUST_CODE 		= $default['CUST_CODE'];
$CUST_ADDRESS	= $default['CUST_ADDRESS'];
$SO_NUM 		= $default['SO_NUM'];
$SO_NUMX		= $SO_NUM;
$SO_CODE 		= $default['SO_CODE'];
$SO_DATE 		= $default['SO_DATE'];
$SN_TOTVOLM 	= $default['SN_TOTVOLM'];
$SN_TOTCOST 	= $default['SN_TOTCOST'];
$SN_TOTPPN 		= $default['SN_TOTPPN'];
$SN_DRIVER 		= $default['SN_DRIVER'];
$VEH_CODE 		= $default['VEH_CODE'];
$VEH_NOPOL 		= $default['VEH_NOPOL'];
$SN_RECEIVER 	= $default['SN_RECEIVER'];
$SN_NOTES 		= $default['SN_NOTES'];
$SN_NOTES1		= $default['SN_NOTES1'];
$SN_STAT 		= $default['SN_STAT'];
$SN_REFNO		= $default['SN_REFNO'];
$Patt_Year 		= $default['Patt_Year'];
$Patt_Month 	= $default['Patt_Month'];
$Patt_Date 		= $default['Patt_Date'];
$Patt_Number 	= $default['Patt_Number'];
	
$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();

foreach($resultPRJ as $rowPRJ) :
	$PRJCODE1 	= $rowPRJ->PRJCODE;
	$PRJNAME1 	= $rowPRJ->PRJNAME;
endforeach;
$PRJNAME		= $PRJNAME1;

if(isset($_POST['SO_NUMX']))
{
	$SO_NUM		= $_POST['SO_NUMX'];
	$SO_NUMX	= $_POST['SO_NUMX'];
	$SO_CODE	= $SO_CODE;
	$CUST_CODE	= $CUST_CODE;
	$CUST_DESC	= '';
	$sqlCUST 	= "SELECT SO_CODE, CUST_CODE, SO_DATE, CUST_ADDRESS FROM tbl_so_header WHERE SO_NUM = '$SO_NUM'";
	$resCUST 	= $this->db->query($sqlCUST)->result();
	foreach($resCUST as $rowCUST) :
		$SO_CODE 		= $rowCUST->SO_CODE;
		$CUST_CODE 		= $rowCUST->CUST_CODE;
		$SO_DATE 		= $rowCUST->SO_DATE;
		$CUST_ADDRESS 	= $rowCUST->CUST_ADDRESS;
	endforeach;
}
//echo $SC_NUMX;
$secGenCode	= base_url().'index.php/c_sales/c_sh1pn0t35/genCode/'; // Generate Code
	
// START : APPROVE PROCEDURE
	if($APPLEV == 'HO')
		$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
	else
		$PRJCODE_LEV	= $this->data['PRJCODE'];
	
	// DocNumber - SN_TOTCOST
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
		$resAPPT	= $this->db->query($sqlAPPT)->result();
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
		$APPROVE_AMOUNT 	= $SN_TOTCOST;
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
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'DocNumber')$DocNumber1 = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'SalesOrder')$SalesOrder = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ShiptTo')$ShiptTo = $LangTransl;
			if($TranslCode == 'Vehicle')$Vehicle = $LangTransl;
			if($TranslCode == 'Currency')$Currency = $LangTransl;
			if($TranslCode == 'Driver')$Driver = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'AdditAddress')$AdditAddress = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ProcessStatus')$ProcessStatus = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Sales')$Sales = $LangTransl;
			if($TranslCode == 'refusal')$refusal = $LangTransl;
			if($TranslCode == 'Return')$Return = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			
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
			if($TranslCode == 'Product')$Product = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
			if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'shipped')$shipped = $LangTransl;
			if($TranslCode == 'OrdeList')$OrdeList = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'Sent')$Sent = $LangTransl;
			if($TranslCode == 'Stock')$Stock = $LangTransl;
			if($TranslCode == 'shiplist')$shiplist = $LangTransl;
			if($TranslCode == 'ProductionC')$ProductionC = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= 'Jumlah pemesanan tidak boleh kosong';
			$alert2		= 'Silahkan pilih nama supplier';
			$alert3		= "Silahkan pilih status persetujuan.";
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$alert1		= 'Qty order can not be empty';
			$alert2		= 'Please select a supplier name';
			$alert3		= "Please select an approval status.";
			$isManual	= "Check to manual code.";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/shipping.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $PRJNAME1; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">	
		    <div class="row">
                <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                    <input type="text" name="SO_NUMX" id="SO_NUMX" value="<?php echo $SO_NUMX; ?>" />
                    <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                </form>
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
                                <input type="hidden" name="SNDate" id="SNDate" value="">
                            </td>
                            <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                        </tr>
                    </table>
                </form>
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
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
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $DocNumber1 ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" style="max-width:195px" name="SN_NUM1" id="SN_NUM1" value="<?php echo $DocNumber; ?>" disabled >
		                       			<input type="hidden" class="textbox" name="SN_NUM" id="SN_NUM" size="30" value="<?php echo $DocNumber; ?>" />
		                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" name="SN_CODE1" id="SN_CODE1" value="<?php echo $SN_CODE; ?>" disabled >
		                                <input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="SN_CODE" id="SN_CODE" value="<?php echo $SN_CODE; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> / <?php echo $Type; ?></label>
		                          	<div class="col-sm-4">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="SN_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $SN_DATE; ?>" style="width:105px" disabled>
		                                    <input type="hidden" name="SN_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $SN_DATE; ?>" style="width:105px">
		                                </div>
		                          	</div>
		                          	<div class="col-sm-5">
		                                <!-- <select name="SN_TYPE1" id="SN_TYPE1" class="form-control select2" disabled>
		                                	<option value="1" <?php if($SN_TYPE == 1) { ?> selected <?php } ?>> <?php echo $Sales; ?> </option>
		                                	<option value="2" <?php if($SN_TYPE == 2) { ?> selected <?php } ?>> <?php echo $refusal; ?> </option>
		                                	<option value="3" <?php if($SN_TYPE == 3) { ?> selected <?php } ?>> <?php echo $Return; ?> </option>
		                                	<option value="4" <?php if($SN_TYPE == 4) { ?> selected <?php } ?>> <?php echo $Others; ?> </option>
		                                </select> -->
		                                <select name="SN_TYPE1" id="SN_TYPE1" class="form-control select2" disabled>
		                                	<!-- <option value="1" <?php if($SN_TYPE == 1) { ?> selected <?php } ?>> <?php echo $Sales; ?> </option>
		                                	<option value="2" <?php if($SN_TYPE == 2) { ?> selected <?php } ?>> <?php echo $refusal; ?> </option> -->
		                                	<!-- <option value="4" <?php if($SN_TYPE == 4) { ?> selected <?php } ?>> <?php echo $Others; ?> </option> -->
		                                	<!-- Tipe 1 apabila menggunakan dokumen penawaran. Karena ini adalah penjualan langsung,
		                                		maka didefaultkan ke tipe 4.
		                                	-->
		                                	<?php //$SN_TYPE = 4; ?>
		                                	<option value="4" <?php if($SN_TYPE == 4) { ?> selected <?php } ?>> <?php echo $Sales; ?> </option>
		                                	<option value="3" <?php if($SN_TYPE == 3) { ?> selected <?php } ?>> <?php echo $Return; ?> </option>
		                                </select>
		                                <input type="hidden" name="SN_TYPE" class="form-control pull-left" id="SN_TYPE" value="<?php echo $SN_TYPE; ?>">
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">No. SO </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
		                                    </div>
		                                    
		                                    <input type="hidden" class="form-control" name="SO_NUM" id="SO_NUM" style="max-width:160px" value="<?php echo $SO_NUMX; ?>" >
		                                    <input type="hidden" class="form-control" name="SO_CODE" id="SO_CODE" style="max-width:160px" value="<?php echo $SO_CODE; ?>" >
		                                    <input type="hidden" class="form-control" name="SO_DATE" id="SO_DATE" style="max-width:160px" value="<?php echo $SO_DATE; ?>" >
		                                    <input type="hidden" class="form-control" name="CUST_CODE" id="CUST_CODE" style="max-width:160px" value="<?php echo $CUST_CODE; ?>" >
		                                    <input type="text" class="form-control" name="SO_NUM1" id="SO_NUM1" value="<?php echo $SO_CODE; ?>" onClick="pleaseCheck();" disabled>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $CustName ?> </label>
		                          	<div class="col-sm-9">
		                            	<select name="CUST_CODE1" id="CUST_CODE1" class="form-control select2" onChange="getTOP(this.value)" disabled>
		                                	<option value="" >--- None ---</option>
		                                    <?php
		                                    	$i 			= 0;
												$sqlCUST	= "SELECT CUST_CODE, CUST_DESC, CUST_ADD1 FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
												$resCUST	= $this->db->query($sqlCUST)->result();
		                                        foreach($resCUST as $sqlCUST) :
		                                            $CUST_CODE1	= $sqlCUST->CUST_CODE;
		                                            $CUST_DESC1	= $sqlCUST->CUST_DESC;
		                                            ?>
		                                                <option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $CUST_CODE) { ?> selected <?php } ?>><?php echo "$CUST_DESC1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                    ?>
		                                </select>
		                          	</div>
		                        </div>
								<?php
									$url_selSO	= site_url('c_sales/c_sh1pn0t35/s3l4llS0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									var url1 = "<?php echo $url_selSO;?>";
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
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
		                          	<div class="col-sm-9">
		                            	<select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
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
		                                    ?>
		                                </select>
		                                <input type="text" class="form-control" style="max-width:350px;" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $AdditAddress ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="CUST_ADDRESS1"  id="CUST_ADDRESS1" style="height: 75px" disabled><?php echo $CUST_ADDRESS; ?></textarea>
		                                <textarea class="form-control" name="CUST_ADDRESS"  id="CUST_ADDRESS" style="display: none;"><?php echo $CUST_ADDRESS; ?></textarea>
		                          	</div>
		                        </div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                        <div class="form-group">
		                       	  	<label for="inputName" class="col-sm-3 control-label"><?php echo $Vehicle ?> </label>
		                          	<div class="col-sm-5">
		                                <select name="VEH_CODE" id="VEH_CODE" class="form-control select2" disabled>
		                                	<option value="none">--- None ---</option>
		                                    <?php
		                                        $sqlVEH		= "SELECT VH_CODE, VH_MEREK FROM tbl_vehicle";
		                                        $resVEH		= $this->db->query($sqlVEH)->result();
		                                        foreach($resVEH as $rowVH) :
		                                            $VH_CODE	= $rowVH->VH_CODE;
		                                            $VH_MEREK	= $rowVH->VH_MEREK;
		                                    ?>
		                                    <option value="<?php echo $VH_CODE; ?>" <?php if($VH_CODE == $VEH_CODE) { ?> selected="selected" <?php } ?>><?php echo $VH_MEREK; ?></option>
		                                    <?php
		                                        endforeach;
		                                    ?>
		                                </select>
		                            	<input type="hidden" class="form-control" name="VEH_NOPOL" id="VEH_NOPOL" placeholder="No Polisi" style="min-width:200px">
		                          	</div>
		                          	<div class="col-sm-4">
		                          		<input type="text" class="form-control" name="SN_DRIVER1" id="SN_DRIVER1" value="<?php echo $SN_DRIVER; ?>" placeholder="<?php echo $Driver; ?>" disabled>
		                          		<input type="hidden" class="form-control" name="SN_DRIVER" id="SN_DRIVER" value="<?php echo $SN_DRIVER; ?>" placeholder="<?php echo $Driver; ?>" >
		                                <!-- <select name="SN_DRIVER1" id="SN_DRIVER1" class="form-control select2" disabled>
		                                	<option value="none">--- None ---</option>
		                                    <?php
		                                        $sqlVEH		= "SELECT DRIV_CODE, DRIV_NAME FROM tbl_driver";
		                                        $resVEH		= $this->db->query($sqlVEH)->result();
		                                        foreach($resVEH as $rowVH) :
		                                            $DRIV_CODE	= $rowVH->DRIV_CODE;
		                                            $DRIV_NAME	= $rowVH->DRIV_NAME;
		                                    ?>
		                                    <option value="<?php echo $DRIV_CODE; ?>" <?php if($DRIV_CODE == $SN_DRIVER) { ?> selected="selected" <?php } ?>><?php echo $DRIV_NAME; ?></option>
		                                    <?php
		                                        endforeach;
		                                    ?>
		                                </select> -->
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ReceivePlan ?> </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="SN_RECEIVED" class="form-control pull-left" id="datepicker2" value="<?php echo $SN_RECEIVED; ?>" style="width:105px">
		                                </div>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="SN_NOTESX"  id="SN_NOTESX" placeholder="<?php echo $Notes; ?>" disabled><?php echo $SN_NOTES; ?></textarea>
		                                <textarea class="form-control" name="SN_NOTES"  id="SN_NOTES" style="display: none;"><?php echo $SN_NOTES; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="SN_NOTES1"  id="SN_NOTES1"><?php echo $SN_NOTES1; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">Total (Qty) </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" name="SN_TOTVOLM" id="SN_TOTVOLM" value="<?php echo $SN_TOTVOLM; ?>" style="display: none;" >
		                            	<input type="text" class="form-control" name="SN_TOTVOLM1" id="SN_TOTVOLM1" style="text-align: right;" value="<?php echo number_format($SN_TOTVOLM, 2); ?>" readonly >
		                          	</div>
		                        </div>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $SN_STAT; ?>">
		                                <?php
											// START : FOR ALL APPROVAL FUNCTION
												if($disableAll == 0)
												{
													if($canApprove == 1)
													{
														$disButton	= 0;
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$SN_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
														?>
															<select name="SN_STAT" id="SN_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																<option value="0"> --- </option>
																<option value="3"<?php if($SN_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																<option value="4"<?php if($SN_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																<option value="5"<?php if($SN_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																<!-- <option value="6"<?php if($SN_STAT == 6) { ?> selected <?php } ?>>Closed</option> -->
																<option value="7"<?php if($SN_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
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
								<script>
		                            /*function selStat(thisVal)
		                            {
										document.getElementById('SN_STAT').value = thisVal;
		                                if(thisVal == 4 || thisVal == 5)
		                                {
		                                    document.getElementById('btnrev').style.display = '';
		                                }
		                                else
		                                {
		                                    document.getElementById('btnrev').style.display = 'none';
		                                }
		                            }*/
		                        </script>
		                        <?php
									$url_AddItem	= site_url('c_sales/c_sh1pn0t35/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									if($SN_STAT == 1 | $SN_STAT == 4)
									{
		                        ?>
		                        <div class="form-group" style="display: none;">
		                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-9">
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
		                                <button class="btn btn-success" type="button" onClick="selectitem();">
		                                <i class="fa fa-cubes"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
		                                </button>
		                        	</div>
		                        </div>
		                    	<?php } ?>
							</div>
						</div>
					</div>
                    <div class="col-md-12">
                        <div class="box box-primary">
							<div class="box-body">
		                    	<div style="text-align: center; font-weight: bold;"><?php echo $OrdeList; ?></div>
		                        <table id="tbl" class="table table-bordered table-striped" width="100%">
                                    <tr style="background:#CCCCCC; font-weight: bold;">
                                        <th width="2%" height="25" style="text-align:center">No.</th>
                                      	<th width="10%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
                                      	<th width="25%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
                                      	<th width="8%" style="text-align:center" nowrap><?php echo $Ordered; ?> </th>
                                      	<th width="8%" style="text-align:center" nowrap><?php echo $Sent; ?> </th>
                                      	<th width="8%" style="text-align:center" nowrap><?php echo $Stock; ?> </th>
                                      	<th width="8%" style="text-align:center" nowrap><?php echo $shipped; ?> </th>
                                        <th width="5%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
                                      	<th width="5%" style="text-align:center; display: none;"><?php echo $UnitPrice; ?> </th>
                                      	<th width="6%" style="text-align:center; display: none;" nowrap><?php echo $Discount; ?><br>(%)</th>
                                      	<th width="2%" style="text-align:center; display: none;" nowrap><?php echo $Discount; ?> </th>
                                      	<th width="6%" style="text-align:center; display: none;" nowrap><?php echo $Tax; ?></th>
                                  	</tr>
                                    <?php
                                    	$disButtUpd	= 0;
                                        $resultC	= 0;
                                        $sqlDET		= "SELECT A.*, B.ITM_NAME
                                                        FROM tbl_sn_detail A
                                                            INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                        WHERE 
                                                            A.SN_NUM = '$SN_NUM' 
                                                            AND B.PRJCODE = '$PRJCODE'";
                                        $resSOD = $this->db->query($sqlDET)->result();
                                        // count data
                                        $sqlDETC	= "tbl_sn_detail A
                                                            INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                        WHERE 
                                                            A.SN_NUM = '$SN_NUM' 
                                                            AND B.PRJCODE = '$PRJCODE'";
                                        $resultC 	= $this->db->count_all($sqlDETC);
                                            
                                        $i			= 0;
                                        $j			= 0;
                                        if($resultC > 0)
                                        {
                                            $GT_ITMPRICE	= 0;
                                            foreach($resSOD as $row) :
                                                $currentRow  	= ++$i;																
                                                $SN_NUM 		= $SN_NUM;
                                                $SN_CODE 		= $SN_CODE;
                                                $PRJCODE		= $PRJCODE;
                                                $SN_DATE 		= $SN_DATE;
                                                $JOBCODEID 		= $row->JOBCODEID;
                                                $ITM_CODE 		= $row->ITM_CODE;
                                                $ITM_NAME 		= $row->ITM_NAME;
                                                $ITM_UNIT 		= $row->ITM_UNIT;
                                                $SO_VOLM 		= $row->SO_VOLM;
                                                $SO_PRICE 		= $row->SO_PRICE;
                                                $SN_VOLM 		= $row->SN_VOLM;
                                                if($SN_VOLM == '')
                                                    $SN_VOLM	= 0;
                                                $SN_PRICE 		= $row->SN_PRICE;												
                                                $SN_DISP 		= $row->SN_DISP;
                                                if($SN_DISP == '')
                                                    $SN_DISP	= 0;
                                                $SN_DISC 		= $row->SN_DISC;
                                                if($SN_DISC == '')
                                                    $SN_DISC	= 0;
                                                $SN_TOTAL 		= $row->SN_TOTAL;
                                                if($SN_TOTAL == '')
                                                    $SN_TOTAL	= 0;
                                                $SN_DESC 		= $row->SN_DESC;
                                                $TAXCODE1 		= $row->TAXCODE1;
                                                $TAXPRICE1 		= $row->TAXPRICE1;

                                                $TOT_SNVOLM		= 0;
                                                $sqlSN 			= "SELECT SUM(A.SN_VOLM) AS TOTSN FROM tbl_sn_detail A
                                                					INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM
																		AND B.PRJCODE = '$PRJCODE'
                                                                 	WHERE B.SO_NUM = '$SO_NUM' AND B.SN_STAT IN (2,3,7)
                                                                 		AND A.SN_NUM != '$SN_NUM'";
                                                $resSN 			= $this->db->query($sqlSN)->result();
                                                foreach($resSN as $rowSN) :
                                                	$TOT_SNVOLM	= $rowSN->TOTSN;
                                                endforeach;
                                                $REM_SN			= $SO_VOLM - $TOT_SNVOLM - $SN_VOLM;
                                                $REMSN_VW 		= "";
                                                if($REM_SN < 0)
												{
													$disButtUpd	= $disButtUpd+1;
													$REMSN_VW 	= 	"<div>
																		<p class='text-danger'><i class='glyphicon glyphicon-triangle-bottom'></i>
																  		".number_format($REM_SN, 2)."</p>
																  	</div>";
												}

                                                $ITM_STOCK		= 0;
                                                $sqlSTOCK		= "SELECT ITM_VOLM FROM tbl_item 
                                                					WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
                                                $resSTOCK 		= $this->db->query($sqlSTOCK)->result();
                                                foreach($resSTOCK as $rowSTOCK) :
                                                	$ITM_STOCK	= $rowSTOCK->ITM_VOLM;
                                                endforeach;
                                                //$REM_ST 		= $ITM_STOCK - $TOT_SNVOLM - $SN_VOLM;
                                                $REM_ST 		= $ITM_STOCK - $SN_VOLM;
                                                $REMST_VW 		= "";
                                                if($REM_ST < 0)
												{
													$disButtUpd	= $disButtUpd+1;
													$REMST_VW 	= 	"<div>
																		<p class='text-danger'><i class='glyphicon glyphicon-triangle-bottom'></i>
																  		".number_format($REM_ST, 2)."</p>
																  	</div>";
												}
                                    
                                                /*if ($j==1) {
                                                    echo "<tr class=zebra1>";
                                                    $j++;
                                                } else {
                                                    echo "<tr class=zebra2>";
                                                    $j--;
                                                }*/
                                            ?>
                                            <tr id="tr_<?php echo $currentRow; ?>">
                                                <!-- NO URUT -->
                                                <td width="2%" height="25" style="text-align: center;">
                                                    <?php
                                                        echo "$currentRow.";
                                                    ?>
                                                    <input style="display:none" type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)">
                                                    <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:300px;">
                                                </td>
                                                <!-- ITEM CODE -->
                                                <td width="10%" style="text-align:left" nowrap>
                                                    <?php print $ITM_CODE; ?>
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" width="10" size="15">
                                                </td>
                                                
                                                <!-- ITEM NAME -->
                                                <td width="25%" style="text-align:left"><?php echo $ITM_NAME; ?></td>
                                                
                                                <!-- ITEM ORDERED -->  
                                                <td width="8%" style="text-align:right">
                                                	<?php echo number_format($SO_VOLM, 2); ?><br><?php echo $REMSN_VW; ?>
                                                    <input type="text" class="form-control" style="max-width:200px; text-align:right; display: none;" name="SO_VOLM<?php echo $currentRow; ?>" id="SO_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($SO_VOLM, 2); ?>" readonly>
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>SO_VOLM" name="data[<?php echo $currentRow; ?>][SO_VOLM]" value="<?php echo $SO_VOLM; ?>">
                                                </td>
                                                
                                                <!-- ITEM SENT -->  
                                                <td width="8%" style="text-align:right">
                                                	<?php echo number_format($TOT_SNVOLM, 2); ?>
                                                    <input type="text" class="form-control" style="max-width:200px; text-align:right; display: none;" name="TOT_SNVOLM<?php echo $currentRow; ?>" id="TOT_SNVOLM<?php echo $currentRow; ?>" value="<?php echo number_format($TOT_SNVOLM, 2); ?>" readonly>
                                                    <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="REM_SN<?php echo $currentRow; ?>" id="REM_SN<?php echo $currentRow; ?>" value="<?php echo $REM_SN; ?>">
                                                </td>
                                                
                                                <!-- ITEM STOCK -->  
                                                <td width="8%" style="text-align:right">
                                                	<?php echo number_format($ITM_STOCK, 2); ?><br><?php echo $REMST_VW; ?>
                                                    <input type="text" class="form-control" style="max-width:200px; text-align:right; display: none;" name="ITM_STOCK<?php echo $currentRow; ?>" id="ITM_STOCK<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_STOCK, 2); ?>" readonly>
                                                </td>
                                                
                                                <!-- ITEM QTY NOW -->  
                                                <td width="8%" style="text-align:right;">
                                                	<?php echo number_format($SN_VOLM, 2); ?>
                                                    <input type="text" class="form-control" style="max-width:200px; text-align:right; display: none;" name="SN_VOLM<?php echo $currentRow; ?>" id="SN_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($SN_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="changeValue(this, <?php echo $currentRow; ?>);" >
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>SN_VOLM" name="data[<?php echo $currentRow; ?>][SN_VOLM]" value="<?php echo $SN_VOLM; ?>">
                                                </td>
                                                    
                                                <!-- ITEM UNIT -->
                                                <td width="5%" style="text-align:center">
                                                    <?php print $ITM_UNIT; ?>  
                                                     <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
                                                </td>
                                                
                                                <!-- ITEM PRICE -->
                                                <td width="5%" style="text-align:center; font-style:italic; display: none;"> hidden
                                                    <input type="hidden" class="form-control" style="text-align:right; min-width:100px" name="SN_PRICE<?php echo $currentRow; ?>" id="SN_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($SN_PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);">
                                                    <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][SN_PRICE]" id="data<?php echo $currentRow; ?>SN_PRICE" size="6" value="<?php echo $SN_PRICE; ?>">
                                                    <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][SO_PRICE]" id="data<?php echo $currentRow; ?>SO_PRICE" size="6" value="<?php echo $SO_PRICE; ?>">
                                                </td>
                                                 
                                                <!-- ITEM DISCOUNT PERCENTATION -->
                                                <td width="6%" style="text-align:center; font-style:italic; display: none;"> hidden
                                                    <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="SN_DISP<?php echo $currentRow; ?>" id="SN_DISP<?php echo $currentRow; ?>" value="<?php print number_format($SN_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $currentRow; ?>);" >
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][SN_DISP]" id="data<?php echo $currentRow; ?>SN_DISP" value="<?php echo $SN_DISP; ?>">
                                                </td>
                                                    
                                                <!-- ITEM DISCOUNT -->
                                                <td width="12%" style="text-align:center; font-style:italic; display: none;"> hidden
                                                    <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="SN_DISC<?php echo $currentRow; ?>" id="SN_DISC<?php echo $currentRow; ?>" value="<?php print number_format($SN_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $currentRow; ?>);" >
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][SN_DISC]" id="data<?php echo $currentRow; ?>SN_DISC" value="<?php echo $SN_DISC; ?>">
                                                </td>
                                                    
                                                <!-- ITEM TAX -->
                                                <td width="9%" style="text-align:center; font-style:italic; display: none;"> hidden
                                                    <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" class="form-control" id="data<?php echo $currentRow; ?>TAXCODE1" style="min-width:100px; max-width:150px; display:none">
                                                        <option value=""> --- </option>
                                                        <option value="TAX01" <?php if ($TAXCODE1 == "TAX01") { ?> selected <?php } ?>>PPn 10%</option>
                                                        <option value="TAX02" <?php if ($TAXCODE1 == "TAX02") { ?> selected <?php } ?> disabled>PPh</option>
                                                    </select>
                                                    <input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="SN_TOTAL<?php echo $currentRow; ?>" id="SN_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($SN_TOTAL, $decFormat); ?>" >
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][SN_TOTAL]" id="data<?php echo $currentRow; ?>SN_TOTAL" value="<?php echo $SN_TOTAL; ?>">
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>">
                                                </td>
                                            </tr>
                                                <?php
                                            endforeach;
                                            ?>
                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                            <?php
                                        }
                                        else
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
                    <div class="col-md-12" <?php if($SN_TYPE == 4) { ?> style="display: none;" <?php } ?>>
                        <div class="box box-success">
							<div class="box-body">
		                        <?php
		                        	$collDATA		= "$PRJCODE~$SO_NUM";
									$url_qrlist		= site_url('c_sales/c_sh1pn0t35/s3l4llQRC/?id='.$this->url_encryption_helper->encode_url($collDATA));
									if($SN_STAT == 1 | $SN_STAT == 4)
									{
		                        		?>
				                        <div class="form-group" style="display: none;">
				                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                            <div class="col-sm-9">
				                                <script>
				                                    var urlQR = "<?php echo $url_qrlist;?>";
				                                    function selectqr()
				                                    {
														title = 'Select QRC';
				                                        w = 1000;
				                                        h = 550;
				                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                                        var left = (screen.width/2)-(w/2);
				                                        var top = (screen.height/2)-(h/2);
				                                        return window.open(urlQR, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                                    }
				                                </script>
				                                <button class="btn btn-warning" type="button" onClick="selectqr();">
				                                <i class='glyphicon glyphicon-qrcode'></i>&nbsp;&nbsp;QR
				                                </button>
				                        	</div>
				                        </div>
					                    <?php 
					                }
					            ?>
                    			<div style="text-align: center; font-weight: bold;"><?php echo $shiplist; ?></div>
                    			<table id="tblQRC" class="table table-bordered table-striped" width="100%">
                                    <tr style="background:#CCCCCC">
                                        <th width="2%" height="25" style="text-align:center">No.</th>
                                      	<th width="10%" style="text-align:center" nowrap>No. Roll </th>
                                      	<th width="10%" style="text-align:center" nowrap><?php echo $ProductionC; ?> </th>
                                      	<th width="25%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
                                      	<th width="8%" style="text-align:center" nowrap>Qty </th>
                                      	<th width="5%" style="text-align:center" nowrap>Unit</th>
                                      	<th width="15%" style="text-align:center" nowrap><?php echo $Notes; ?></th>
                                  	</tr>
                                    <?php
                                        $resSNC	= 0;
                                        if($task == 'edit')
                                        {
                                            $sqlSN	= "SELECT A.*, B.ITM_NAME
                                                        FROM tbl_sn_detail_qrc A
                                                            INNER JOIN tbl_qrc_detail B ON A.QRC_NUM = B.QRC_NUM
                                                        WHERE 
                                                            A.SN_NUM = '$SN_NUM' 
                                                            AND B.PRJCODE = '$PRJCODE'";
                                            $resSN = $this->db->query($sqlSN)->result();
                                            // count data
                                            $sqlSNC	= "tbl_sn_detail_qrc A
                                                            INNER JOIN tbl_qrc_detail B ON A.QRC_NUM = B.QRC_NUM
                                                        WHERE 
                                                            A.SN_NUM = '$SN_NUM' 
                                                            AND B.PRJCODE = '$PRJCODE'";
                                            $resSNC = $this->db->count_all($sqlSNC);
                                        }
                                            
                                        $i			= 0;
                                        $j			= 0;
                                        if($resSNC > 0)
                                        {
                                            $GT_ITMPRICE	= 0;
                                            foreach($resSN as $rowSN) :
                                                $currentRow  	= ++$i;
                                                $SN_NUM 		= $SN_NUM;
                                                $SN_CODE 		= $SN_CODE;
                                                $SN_DATE 		= $SN_DATE;
                                                $PRJCODE		= $PRJCODE;
                                                $CUST_CODE 		= $CUST_CODE;
                                                $JO_NUM 		= $rowSN->JO_NUM;
                                                $JO_CODE 		= $rowSN->JO_CODE;
                                                $ITM_CODE 		= $rowSN->ITM_CODE;
                                                $ITM_NAME 		= $rowSN->ITM_NAME;
                                                $ITM_UNIT 		= $rowSN->ITM_UNIT;
                                                $QRC_NUM 		= $rowSN->QRC_NUM;
                                                $QRC_CODEV 		= $rowSN->QRC_CODEV;
                                                $QRC_VOLM 		= $rowSN->QRC_VOLM;
                                                $QRC_NOTE 		= $rowSN->QRC_NOTE;
                                    
                                                /*if ($j==1) {
                                                    echo "<tr class=zebra1>";
                                                    $j++;
                                                } else {
                                                    echo "<tr class=zebra2>";
                                                    $j--;
                                                }*/
                                            ?>
                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                            <tr id="trQRC_<?php echo $currentRow; ?>">
                                                <!-- NO URUT -->
                                                <td width="2%" height="25" style="text-align: center;">
                                                    <?php
                                                        if($SN_STAT == 1)
                                                        {
                                                            ?>
                                                                <a href="#" onClick="deleteRowQRC(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            echo "$currentRow.";
                                                        }
                                                    ?>
                                                    <input style="display:none" type="Checkbox" id="dataQRC[<?php echo $currentRow; ?>][chk]" name="dataQRC[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)">
                                                    <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
                                                </td>
                                                <!-- NO. ROLL : ITEM CODE, QRC_NUM, QRC_CODEV -->
                                                <td width="10%" style="text-align:left">
                                                    <?php print $QRC_CODEV; ?>
                                                    <input type="hidden" id="dataQRC<?php echo $currentRow; ?>QRC_NUM" name="dataQRC[<?php echo $currentRow; ?>][QRC_NUM]" value="<?php echo $QRC_NUM; ?>" width="10" size="15">
                                                    <input type="hidden" id="dataQRC<?php echo $currentRow; ?>QRC_CODEV" name="dataQRC[<?php echo $currentRow; ?>][QRC_CODEV]" value="<?php echo $QRC_CODEV; ?>" width="10" size="15">
                                                </td>
                                                
                                                <!-- JO_NUM, JO_CODE -->  
                                                <td width="10%" style="text-align:left;">
                                                	<?php echo $JO_CODE; ?>
                                                     <input type="hidden" id="dataQRC<?php echo $currentRow; ?>JO_NUM" name="dataQRC[<?php echo $currentRow; ?>][JO_NUM]" value="<?php print $JO_NUM; ?>">
                                                     <input type="hidden" id="dataQRC<?php echo $currentRow; ?>JO_CODE" name="dataQRC[<?php echo $currentRow; ?>][JO_CODE]" value="<?php print $JO_CODE; ?>">
                                                </td>
                                                
                                                <!-- ITEM NAME -->
                                                <td width="25%" style="text-align:left">
                                                	<?php echo $ITM_NAME; ?>
                                                	<input type="hidden" id="dataQRC<?php echo $currentRow; ?>ITM_CODE" name="dataQRC[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php print $ITM_CODE; ?>">
                                                </td>
                                                
                                                <!-- QRC_VOLM -->  
                                                <td width="8%" style="text-align:right">
                                                	<?php echo number_format($QRC_VOLM, 2); ?>
                                                    <input type="hidden" id="dataQRC<?php echo $currentRow; ?>QRC_VOLM" name="dataQRC[<?php echo $currentRow; ?>][QRC_VOLM]" value="<?php print $QRC_VOLM; ?>">
                                                </td>
                                                
                                                <!-- ITM_UNIT -->  
                                                <td width="5%" style="text-align:center;">
                                                	<?php echo $ITM_UNIT; ?>
                                                    <input type="hidden" id="dataQRC<?php echo $currentRow; ?>ITM_UNIT" name="dataQRC[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
                                                </td>
                                                
                                                <!-- QRC_NOTE -->  
                                                <td width="5%" style="text-align:left;">
                                                	<?php echo $QRC_NOTE; ?>
                                                    <input type="hidden" id="dataQRC<?php echo $currentRow; ?>QRC_NOTE" name="dataQRC[<?php echo $currentRow; ?>][QRC_NOTE]" value="<?php print $QRC_NOTE; ?>">
                                                </td>
                                            </tr>
                                                <?php
                                            endforeach;
                                            ?>
                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                            <?php
                                        }
                                        else
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
                    <br>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-9">
                        	<?php
								if(($SN_STAT == 2 || $SN_STAT == 7) && $ISAPPROVE == 1 && $canApprove == 1 && $disButtUpd == 0)
								{
									?>
										<button class="btn btn-primary" id="btnSave">
										<i class="fa fa-save"></i></button>&nbsp;
									<?php
								}
								else
								{
									?>
										<button id="btnrev" class="btn btn-primary" style="display: none;">
										<i class="fa fa-save"></i></button>&nbsp;
									<?php
								}
								$backURL	= site_url('c_sales/c_sh1pn0t35/gl5h1pm3nt_1n2/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
							?>
                        </div>
                    </div>
                </form>
            	<div class="col-md-12">
					<?php
                        $DOC_NUM	= $SN_NUM;
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

<?php
	if($LangID == 'IND')
	{
		$qtyDetail	= 'Detail item tidak boleh kosong.';
		$volmAlert	= 'Qty order tidak boleh nol.';
	}
	else
	{
		$qtyDetail	= 'Item Detail can not be empty.';
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
  
	var decFormat		= 2;
	
	function changeValue(thisVal, i)
	{
		var REM_SN	= document.getElementById('REM_SN'+i).value;
		var SN_VOLM = eval(thisVal).value.split(",").join("");

		if(parseFloat(SN_VOLM) > parseFloat(REM_SN))
		{
			var SN_VOLM 										= parseFloat(REM_SN);
			swal('Qty can not greater then '+ REM_SN);
			document.getElementById('data'+i+'SN_VOLM').value 	= parseFloat(Math.abs(REM_SN));
			document.getElementById('SN_VOLM'+i).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_SN)), decFormat));
		}
		else
		{
			document.getElementById('data'+i+'SN_VOLM').value 	= parseFloat(Math.abs(SN_VOLM));
			document.getElementById('SN_VOLM'+i).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SN_VOLM)), decFormat));
		}

		var ITM_STOCK	= eval(document.getElementById('ITM_STOCK'+i)).value.split(",").join("");

		if(parseFloat(SN_VOLM) > parseFloat(ITM_STOCK))
		{
			var SN_VOLM 										= parseFloat(ITM_STOCK);
			swal('Qty can not greater then stock qty = '+ ITM_STOCK);
			document.getElementById('data'+i+'SN_VOLM').value 	= parseFloat(Math.abs(ITM_STOCK));
			document.getElementById('SN_VOLM'+i).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_STOCK)), decFormat));
		}
		else
		{
			document.getElementById('data'+i+'SN_VOLM').value 	= parseFloat(Math.abs(SN_VOLM));
			document.getElementById('SN_VOLM'+i).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SN_VOLM)), decFormat));
		}

		var SN_PRICE	= eval(document.getElementById('SN_PRICE'+i)).value.split(",").join("");
		var TOT_SNITMP	= parseFloat(SN_VOLM) * parseFloat(SN_PRICE);

		var SN_DISP		= eval(document.getElementById('SN_DISP'+i)).value.split(",").join("");
		var SN_DISC 	= parseFloat(TOT_SNITMP * SN_DISP) / 100;

		var TOT_SNPRC1	= parseFloat(TOT_SNITMP) - parseFloat(SN_DISC);
		var TAXCODE1	= document.getElementById('data'+i+'TAXCODE1').value;
		var TAXPRC 		= 0;
		if(TAXCODE1 == 'TAX01')
		{
			var TAXPRC 	= parseFloat(TOT_SNPRC1 * 0.1);
		}
		if(TAXCODE1 == 'TAX02')
		{
			var TAXPRC 	= -1 * parseFloat(TOT_SNPRC1 * 0.03);
		}

		var TOT_SNPRC 	= parseFloat(TOT_SNPRC1 + TAXPRC);
		document.getElementById('SN_TOTAL'+i).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_SNPRC)),decFormat));
		document.getElementById('data'+i+'SN_TOTAL').value 	= TOT_SNPRC;
	}
	
	function checkInp()
	{
		totRow		= document.getElementById('totalrow').value;
		CUST_CODE	= document.getElementById('CUST_CODE').value;
		SN_STAT		= document.getElementById('SN_STAT').value;
		
		/*if(CUST_CODE == 0)
		{
			swal("<?php echo $alert2; ?>");
			document.getElementById('CUST_CODE').focus();
			return false;	
		}*/

		if(totRow == 0)
		{
			swal('<?php echo $qtyDetail; ?>',
			{
				icon: "warning",
			});
			return false;
		}	
		
		/*var SN_TCOST	= 0;
		for(i=1;i<=totRow;i++)
		{
			var SN_COST = parseFloat(document.getElementById('data'+i+'SN_COST').value);
			SN_TCOST	= parseFloat(SN_TCOST) + parseFloat(SN_COST);
				
		}
		document.getElementById('SN_TOTCOST').value = SN_TCOST;*/

		if(SN_STAT == 0)
		{
			swal('<?php echo $alert3; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		
		for(i=1;i<=totRow;i++)
		{
			var SN_VOLM = parseFloat(document.getElementById('data'+i+'SN_VOLM').value);
			if(SN_VOLM == 0)
			{
				swal("<?php echo $alert1; ?>");
				document.getElementById('SN_VOLM'+i).focus();
				return false;	
			}
		}

		var variable = document.getElementById('btnSave');
		if (typeof variable !== 'undefined' && variable !== null)
		{
			document.getElementById('btnSave').style.display 	= 'none';
		}
	}
	
	function changeValuePrc(thisVal, theRow)
	{
		var ITM_PRICEx 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('ITM_PRICE'+theRow).value 		= parseFloat(Math.abs(ITM_PRICEx));
		document.getElementById('ITM_PRICEx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEx)),decFormat));
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
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
	
	function deleteRowQRC(btn)
	{
		var row = document.getElementById("trQRC_" + btn);
		row.remove();
	}
	
	function add_header(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');
		
		SO_NUM 			= arrItem[0];
		SO_CODE 		= arrItem[1];
		CUST_CODE 		= arrItem[2];
		CUST_ADDRESS 	= arrItem[3];
		CUST_DESC 		= arrItem[4];
		
		document.getElementById('SO_NUM').value 		= SO_NUM;
		document.getElementById('SO_CODE').value 		= SO_CODE;
		document.getElementById('SO_NUM1').value 		= SO_CODE;
		document.getElementById('SO_NUMX').value 		= SO_NUM;
		document.getElementById('CUST_ADDRESS').value 	= CUST_ADDRESS;
		
		document.frmsrch1.submitSrch1.click();
	}
	
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		ICOLLQTYTOT		= document.getElementById('SN_TOTVOLM').value;

		QRC_NUM 		= arrItem[0];
		JO_NUM			= arrItem[1];
		JO_CODE			= arrItem[2];
		QRC_CODEV 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_UNIT 		= arrItem[6];
		QRC_VOLM 		= arrItem[7];
		QRC_VOLMV 		= doDecimalFormat(RoundNDecimal(QRC_VOLM, 2));
		SNQTYTOT2		= parseFloat(ICOLLQTYTOT) + parseFloat(QRC_VOLM);
		document.getElementById('SN_TOTVOLM').value 	= SNQTYTOT2;
		document.getElementById('SN_TOTVOLM1').value 	= doDecimalFormat(RoundNDecimal(SNQTYTOT2, 2));
		
		ITM_CODE1		= ITM_CODE+' '+QRC_NUM;
		
		//swal(Unit_Price);
		objTable 		= document.getElementById('tblQRC');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'trQRC_' + intIndex;
		
		// CHECKBOX
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// QRC_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+QRC_CODEV+'<input type="hidden" id="dataQRC'+intIndex+'QRC_NUM" name="dataQRC['+intIndex+'][QRC_NUM]" value="'+QRC_NUM+'" width="10" size="15"><input type="hidden" id="dataQRC'+intIndex+'QRC_CODEV" name="dataQRC['+intIndex+'][QRC_CODEV]" value="'+QRC_CODEV+'" width="10" size="15">';
		
		// JO_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+JO_CODE+'<input type="hidden" id="dataQRC'+intIndex+'JO_NUM" name="dataQRC['+intIndex+'][JO_NUM]" value="'+JO_NUM+'" width="10" size="15"><input type="hidden" id="dataQRC'+intIndex+'JO_CODE" name="dataQRC['+intIndex+'][JO_CODE]" value="'+JO_CODE+'" width="10" size="15">';
		
		// ITM_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="dataQRC'+intIndex+'ITM_CODE" name="dataQRC['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15">';
		
		// ITM_NAME
		/*objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'';*/
		
		// QRC QTY
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+QRC_VOLMV+'<input type="hidden" id="dataQRC'+intIndex+'QRC_VOLM" name="dataQRC['+intIndex+'][QRC_VOLM]" value="'+QRC_VOLM+'">';
		
		// ITM UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="dataQRC'+intIndex+'ITM_UNIT" name="dataQRC['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// QRC NOTES
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" class="form-control" name="dataQRC['+intIndex+'][QRC_NOTE]" id="dataQRC'+intIndex+'QRC_NOTE" value="">';
				
		document.getElementById('totalrow').value = intIndex;
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