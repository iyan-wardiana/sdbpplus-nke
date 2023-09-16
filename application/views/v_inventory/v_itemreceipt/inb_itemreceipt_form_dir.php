<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Maret 2017
 * File Name	= inb_itemreceipt_form_dir.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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

$currentRow = 0;
if($task == 'add')
{
	foreach($viewDocPattern as $row) :
		$Pattern_Code = $row->Pattern_Code;
		$Pattern_Position = $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive = $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		$Pattern_Length = $row->Pattern_Length;
		$useYear = $row->useYear;
		$useMonth = $row->useMonth;
		$useDate = $row->useDate;
	endforeach;
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$year 	= (int)$Pattern_YearAktive;
	$month 	= (int)$Pattern_MonthAktive;
	$date 	= (int)$Pattern_DateAktive;
	
	$year		= substr($year, 2, 4);
	$Patt_Year 	= (int)$Pattern_YearAktive;

	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_ir_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_ir_header
			WHERE Patt_Year = $Patt_Year AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	// group year, month and date
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$PRJCODE$year$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$PRJCODE$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$PRJCODE$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$PRJCODE$year";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$pattMonth";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$pattDate";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "";
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
	$len = strlen($lastPatternNumb);
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0";
	}
	elseif($Pattern_Length==4)
	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
	}
	elseif($Pattern_Length==5)
	{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
	}
	
	$lastPatternNumb = $nol.$lastPatternNumb;
	
	$DocNumber		= "$Pattern_Code$groupPattern-$lastPatternNumb"."-D";
	
	$IR_NUM 		= $DocNumber;
	$IR_NUM_BEF		= '';
	$IR_CODE 		= $lastPatternNumb;
	$IR_SOURCE		= 1;
	$IR_DATE		= date('m/d/Y');
	$JournalY 		= date('Y');
	$JournalM 		= date('n');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$IR_REFER		= '';
	$PO_NUM			= '';
	$IR_AMOUNT		= 0;
	$APPROVE		= 0;
	$IR_STAT		= 0;
	$IR_NOTE		= '';
	$REVMEMO		= '';
	$WH_CODE		= '';
	$IR_STAT		= '';
	$Patt_Number	= $lastPatternNumb1;
	$ISDIRECT		= 1;
	$TERM_PAY		= 30;
}
else
{
	$IR_NUM 		= $default['IR_NUM'];
	$IR_NUM_BEF		= $IR_NUM;
	$IR_CODE 		= $default['IR_CODE'];
	$IR_SOURCE 		= $default['IR_SOURCE'];
	$IR_DATE 		= $default['IR_DATE'];
	$JournalY 		= date('Y', strtotime($IR_DATE));
	$JournalM 		= date('n', strtotime($IR_DATE));
	$IR_DUEDATE		= $default['IR_DUEDATE'];
	$IR_DATE		= date('m/d/Y', strtotime($IR_DATE));
	$PRJCODE 		= $default['PRJCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$IR_REFER 		= $default['IR_REFER'];
	$PO_NUM 		= $default['PO_NUM'];
	$IR_AMOUNT 		= $default['IR_AMOUNT'];
	$TERM_PAY 		= $default['TERM_PAY'];
	$TRXUSER 		= $default['TRXUSER'];
	$APPROVE 		= $default['APPROVE'];
	$IR_STAT 		= $default['IR_STAT'];
	$INVSTAT 		= $default['INVSTAT'];
	$IR_NOTE 		= $default['IR_NOTE'];
	$REVMEMO		= $default['REVMEMO'];
	$WH_CODE		= $default['WH_CODE'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Number	= $default['Patt_Number'];
	$ISDIRECT		= 1;
}

// GET Journal Lock
	$disabled 	= 0;
	$getJLock 	= "SELECT * FROM tbl_journal_lock 
					WHERE LockY = $JournalY AND LockM = $JournalM AND isLock = 1 AND UserLock != '$DefEmp_ID'";
	$resJLock 	= $this->db->query($getJLock);
	$countJLock = $resJLock->num_rows();
	if($countJLock == 1) $disabled = 1;

// Project List
$DefEmp_ID	= $this->session->userdata['Emp_ID'];
$sqlPLC		= "tbl_project";
$resPLC		= $this->db->count_all($sqlPLC);

$sqlPL 		= "SELECT PRJCODE, PRJNAME
				FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY PRJNAME";
$resPL		= $this->db->query($sqlPL)->result();

// Warehouse List
$sqlWHC		= "tbl_warehouse";
$resWHC		= $this->db->count_all($sqlWHC);

$sqlWH 		= "SELECT WH_CODE, WH_NAME
				FROM tbl_warehouse ORDER BY WH_NAME";
$resWH		= $this->db->query($sqlWH)->result();

$sqlTAX		= "SELECT WH_CODE, WH_NAME
				FROM tbl_warehouse ORDER BY WH_NAME";
$resTAX		= $this->db->query($sqlTAX)->result();
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
		if($TranslCode == 'AddItem')$AddItem = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Cancel')$Cancel = $LangTransl;
		if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
		if($TranslCode == 'Discount')$Discount = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
		if($TranslCode == 'WHLocation')$WHLocation = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'Receipt')$Receipt = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
		if($TranslCode == 'Total')$Total = $LangTransl;
		if($TranslCode == 'Remarks')$Remarks = $LangTransl;
		if($TranslCode == 'PPn')$PPn = $LangTransl;
		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
		if($TranslCode == 'Discount')$Discount = $LangTransl;
		if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
		if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'Approver')$Approver = $LangTransl;
		if($TranslCode == 'Approved')$Approved = $LangTransl;
		if($TranslCode == 'Approval')$Approval = $LangTransl;
		if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
		if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
	endforeach;
	$secGenCode	= base_url().'index.php/c_inventory/c_ir180c15/genCode/'; // Generate Code
	if($LangID == 'IND')
	{
		$h1_title	= 'Terima Item';
		$h2_title	= 'Inventaris';
		$alert1		= 'Jumlah pemesanan tidak boleh kosong';
		$alert2		= 'Silahkan pilih nama supplier';
		$alert3		= "Silahkan pilih status persetujuan...!";
	}
	else
	{
		$h1_title	= 'Item Receipt';
		$h2_title	= 'Inventory';
		$alert1		= 'Qty order can not be empty';
		$alert2		= 'Please select a supplier name';
		$alert3		= "Please select approval status...!";
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
		$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND PRJCODE = '$PRJCODE'";
		$resCAPP	= $this->db->count_all($sqlCAPP);
		if($resCAPP > 0)
		{
			$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
						AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
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
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
			$resAPPT	= $this->db->query($sqlAPP)->result();
			foreach($resAPPT as $rowAPPT) :
				$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
			endforeach;
		}
		
		$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
						AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
		if($resSTEPAPP > 0)
		{
			$canApprove	= 1;
			$APPLIMIT_1	= 0;
			
			$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
						AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
			$resAPP	= $this->db->query($sqlAPP)->result();
			foreach($resAPP as $rowAPP) :
				$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
				$APP_STEP	= $rowAPP->APP_STEP;
				$MAX_STEP	= $rowAPP->MAX_STEP;
			endforeach;
			
			$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM'";
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
			$APPROVE_AMOUNT = $IR_AMOUNT;
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
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>  </h1>
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
                                    <input type="hidden" name="IRDate" id="IRDate" value="">
                                    <input type="hidden" name="ISDIRECT" id="ISDIRECT" value="<?php echo $ISDIRECT; ?>">
                                </td>
                                <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                            </tr>
                        </table>
                    </form>
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                    	<input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
                        <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
                        <input type="hidden" name="IR_SOURCE" id="IR_SOURCE" value="<?php echo $ISDIRECT; ?>">
           				<input type="hidden" name="rowCount" id="rowCount" value="0">
                        <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptCode ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" name="IR_NUM1" id="IR_NUM1" value="<?php echo $IR_NUM; ?>" class="form-control" style="max-width:175px" disabled >
                                <input type="hidden" name="Patt_Year" id="Patt_Year" value="<?php echo $Patt_Year; ?>">
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
                                <input type="hidden" name="IR_NUM" id="IR_NUM" value="<?php echo $IR_NUM; ?>" >
                                <input type="hidden" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" >
                                <input type="hidden" name="IR_NUM_BEF" id="IR_NUM_BEF" value="<?php echo $IR_NUM_BEF; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?> </label>
                          	<div class="col-sm-10">
                                <input type="hidden" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" class="form-control" style="max-width:150px" >
                                <input type="text" name="IR_CODEx" id="IR_CODEx" value="<?php echo $IR_CODE; ?>" class="form-control" style="max-width:200px" disabled >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <?php
										if($task == 'add')
										{
											?>
                                            <input type="text" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px" onChange="getIR_NUM(this.value)">
                                            <?php
										}
										else
										{
											?>
                                            <input type="hidden" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px">
                                            <input type="text" name="IR_DATEx" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px" disabled>
                                            <?php
										}
									?>
                                    
                            	</div>
                            </div>
                        </div>
						<script>
                            function getIR_NUM(selDate)
                            {
                                document.getElementById('IRDate').value = selDate;
                                document.getElementById('dateClass').click();
                            }
	
							$(document).ready(function()
							{
								$(".tombol-date").click(function()
								{
									var add_IR	= "<?php echo $secGenCode; ?>";
									var formAction 	= $('#sendDate')[0].action;
									var data = $('.form-user').serialize();
									$.ajax({
										type: 'POST',
										url: formAction,
										data: data,
										success: function(response)
										{
											var myarr = response.split("~");
											document.getElementById('IR_NUM1').value 	= myarr[0];
											document.getElementById('IR_NUM').value 	= myarr[0];
											document.getElementById('IR_CODE').value 	= myarr[1];
										}
									});
								});
							});
						</script>
                        <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SourceDocument; ?> </label>
                          	<div class="col-sm-10">
                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE1" value="1" <?php if($IR_SOURCE == 1) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;Direct&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php /*?><input type="radio" name="IR_SOURCE" id="IR_SOURCE2" value="2" <?php if($IR_SOURCE == 2) { ?> checked <?php } ?> disabled>
                                &nbsp;&nbsp;MR&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE3" value="3" <?php if($IR_SOURCE == 3) { ?> checked <?php } ?>>
                                &nbsp;&nbsp;PO<?php */?>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?> </label>
                          	<div class="col-sm-10">
                            <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
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
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SupplierName; ?> </label>
                          	<div class="col-sm-10">
                            	<select name="SPLCODE" id="SPLCODE" class="form-control" style="max-width:350px" onChange="getVendName(this.value)" disabled>
                                    <?php
                                    $i = 0;
                                    if($countSUPL > 0)
                                    {
                                        foreach($vwSUPL as $row) :
                                            $SPLCODE1	= $row->SPLCODE;
                                            $SPLDESC1	= $row->SPLDESC;
                                            ?>
                                                <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLDESC1 - $SPLCODE1"; ?></option>
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
                                            <option value="0">--- No Vendor Found ---</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PaymentTerm; ?> </label>
                          	<div class="col-sm-10">
                                <select name="TERM_PAY" id="TERM_PAY" class="form-control" style="max-width:100px" disabled>
                                    <option value="0" <?php if($TERM_PAY == 0) { ?> selected <?php } ?>>Cash</option>
                                    <option value="7" <?php if($TERM_PAY == 7) { ?> selected <?php } ?>>7 Days</option>
                                    <option value="15" <?php if($TERM_PAY == 15) { ?> selected <?php } ?>>15 Days</option>
                                    <option value="30" <?php if($TERM_PAY == 30) { ?> selected <?php } ?>>30 Days</option>
                                    <option value="45" <?php if($TERM_PAY == 45) { ?> selected <?php } ?>>45 Days</option>
                                    <option value="60" <?php if($TERM_PAY == 60) { ?> selected <?php } ?>>60 Days</option>
                                    <option value="75" <?php if($TERM_PAY == 75) { ?> selected <?php } ?>>75 Days</option>
                                    <option value="90" <?php if($TERM_PAY == 90) { ?> selected <?php } ?>>90 Days</option>
                                    <option value="120" <?php if($TERM_PAY == 120) { ?> selected <?php } ?>>120 Days</option>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $WHLocation ?> </label>
                          	<div class="col-sm-10">
                            	<input type="hidden" name="WH_CODE" id="WH_CODE" value="<?php echo $WH_CODE; ?>">
                                <select name="WH_CODEx" id="WH_CODEx" class="form-control" style="max-width:350px" disabled >
                                  	<?php
                                        if($resWHC > 0)
                                        {
                                            foreach($resWH as $rowWH) :
                                                $WH_CODE1 = $rowWH->WH_CODE;
                                                $WH_NAME1 = $rowWH->WH_NAME;
                                                ?>
                                  				<option value="<?php echo $WH_CODE1; ?>" <?php if($WH_CODE1 == $WH_CODE) { ?> selected <?php } ?>><?php echo "$WH_CODE1 - $WH_NAME1"; ?></option>
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
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?> </label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="IR_NOTE"  id="IR_NOTE" style="max-width:350px;height:70px" disabled><?php echo $IR_NOTE; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes; ?> </label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="IR_NOTE"  id="IR_NOTE" style="max-width:350px;height:70px"><?php echo $IR_NOTE; ?></textarea>
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
                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
								<?php
									// START : FOR ALL APPROVAL FUNCTION
										if($disableAll == 0)
										{
											if($canApprove == 1)
											{
												$disButton	= 0;
												$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$DefEmp_ID'";
												$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
												if($resCAPPHE > 0)
													$disButton	= 1;
												?>
													<select name="IR_STAT" id="IR_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
														<option value="0"> -- </option>
														<option value="3"<?php if($IR_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
														<option value="4"<?php if($IR_STAT == 4) { ?> selected <?php } ?>>Revised</option>
														<option value="5"<?php if($IR_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
														<option value="6"<?php if($IR_STAT == 6) { ?> selected <?php } ?>>Closed</option>
														<option value="7"<?php if($IR_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option>
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
						<?php
							$url_AddItem	= site_url('c_inventory/c_ir180c15/pop180c22all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
								<!--<a href="javascript:void(null);" onClick="selectitem();">
									Add Item [+]
                                </a>-->
                                
                                <button class="btn btn-success" type="button" onClick="selectitem();">
                                    <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
                                </button><br>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-primary">
                                <br>
                                    <table width="100%" border="1" id="tbl">
                                        <tr style="background:#CCCCCC">
                                            <th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                                          	<th width="7%" rowspan="2" style="text-align:center"><?php echo $ItemCode; ?> </th>
                                          	<th width="21%" rowspan="2" style="text-align:center"><?php echo $ItemName; ?> </th>
                                          	<th colspan="3" style="text-align:center"><?php echo $Receipt; ?> </th>
                                            <th rowspan="2" style="text-align:center"><?php echo $Discount; ?></th>
                                            <th rowspan="2" style="text-align:center"><?php echo $PPn; ?></th>
                                            <th rowspan="2" style="text-align:center"><?php echo $Total; ?></th>
                                            <th width="22%" rowspan="2" style="text-align:center"><?php echo $Remarks ?> </th>
                                      	</tr>
                                        <tr style="background:#CCCCCC">
                                            <th style="text-align:center;"><?php echo $Quantity; ?> </th>
                                            <th style="text-align:center;"><?php echo $Unit; ?> </th>
                                            <th style="text-align:center"><?php echo $Price; ?> </th>
                                        </tr>
                                        <?php
											$sqlDET		= "SELECT A.PRJCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
																A.ACC_ID, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY, 
																A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, 
																A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, 
																A.TAXPRICE2, B.ITM_NAME, B.ACC_ID, B.ITM_GROUP, 
																B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
																B.ISFASTM, B.ISWAGE
															FROM tbl_ir_detail A
																INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	AND B.PRJCODE = '$PRJCODE'
															WHERE 
															A.IR_NUM = '$IR_NUM' 
															AND A.PRJCODE = '$PRJCODE'";
											$result = $this->db->query($sqlDET)->result();
                                            // count data
											$sqlDETC	= "tbl_ir_detail A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                            WHERE 
															A.IR_NUM = '$IR_NUM' 
                                                            AND B.PRJCODE = '$PRJCODE'";
											$resultC 	= $this->db->count_all($sqlDETC);
                                            $i		= 0;
                                            $j		= 0;
                                            if($resultC > 0)
                                            {
                                                foreach($result as $row) :
                                                    $currentRow  	= ++$i;
                                                    $IR_NUM 		= $row->IR_NUM;
                                                    $IR_CODE 		= $row->IR_CODE;
                                                    $ITM_CODE 		= $row->ITM_CODE;
                                                    $JOBCODEDET		= $row->JOBCODEDET;
                                                    $JOBCODEID 		= $row->JOBCODEID;
                                                    $ACC_ID 		= $row->ACC_ID;
                                                    $ITM_NAME 		= $row->ITM_NAME;
                                                    $PRJCODE		= $row->PRJCODE;
                                                    $ITM_QTY 		= $row->ITM_QTY;
													$ITM_QTY_BONUS	= 0;
                                                    $ITM_UNIT 		= $row->ITM_UNIT;
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
														
                                                    $ITM_PRICE 		= $row->ITM_PRICE;
                                                    $ITM_TOTAL 		= $row->ITM_TOTAL;
                                                    $ITM_DISP 		= $row->ITM_DISP;
                                                    $ITM_DISC 		= $row->ITM_DISC;
                                                    $TAXCODE1		= $row->TAXCODE1;
                                                    $TAXPRICE1		= $row->TAXPRICE1;
                                                    $NOTES			= $row->NOTES;
                                                    $itemConvertion	= 1;
													
													$ITM_TOTAL		= $ITM_QTY * $ITM_PRICE - $ITM_DISC;
													
													$GT_ITMPRICE	= $ITM_TOTAL;
													if($TAXCODE1 == 'TAX01')
													{
														$GT_ITMPRICE= $ITM_TOTAL + $TAXPRICE1;
													}
													if($TAXCODE1 == 'TAX02')
													{
														$GT_ITMPRICE= $ITM_TOTAL - $TAXPRICE1;
													}
                                        
                                                    if ($j==1) {
                                                        echo "<tr class=zebra1>";
                                                        $j++;
                                                    } else {
                                                        echo "<tr class=zebra2>";
                                                        $j--;
                                                    }
                                                    ?> 
                                                    <tr><td width="2%" height="25" style="text-align:left">
                                                        <?php
															if($IR_STAT == 1)
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
                                                        <input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onclick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
                                                        <input type="Checkbox" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="" style="display:none" ><input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                                        <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>IR_NUM" name="data[<?php echo $currentRow; ?>][IR_NUM]" value="<?php echo $IR_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>IR_CODE" name="data[<?php echo $currentRow; ?>][IR_CODE]" value="<?php echo $IR_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                    	<!-- Checkbox -->
                                                    </td>
                                               	  	<td width="7%" style="text-align:left" nowrap>
                                                      	<?php echo $ITM_CODE; ?>
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" width="10" size="15" readonly class="form-control">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php echo $JOBCODEDET; ?>" class="form-control">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" class="form-control">
                                                        <!-- Item Code -->
                                                    </td>
                                               	  	<td width="21%" style="text-align:left">
                                                      	<?php echo $ITM_NAME; ?>
                                                        <input type="hidden" class="form-control" name="itemname<?php echo $currentRow; ?>" id="itemname<?php echo $currentRow; ?>" value="<?php echo $ITM_NAME; ?>" >
														<!-- Item Name -->                                                    </td>
                                               	  	<td width="12%" style="text-align:right" nowrap>
                                                        <input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onBlur="changeValue(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="data<?php echo $currentRow; ?>ITM_QTY" size="10" value="<?php echo $ITM_QTY; ?>" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY_BONUS]" id="ITM_QTY_BONUS<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY_BONUS; ?>" >
                                                        <!-- Item Qty -->                                                    </td>
                                               	  	<td width="3%" nowrap style="text-align:center">
                                                      	<?php echo $ITM_UNIT; ?>
                                                        <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="ITM_UNIT<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT; ?>" >
                                                        <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" id="ITM_GROUP<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_GROUP; ?>" >
                                                        <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TYPE]" id="ITM_TYPE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TYPE; ?>" >
                                                      	<!-- Item Unit -->                                                    </td>
                                               	  	<td width="12%" nowrap style="text-align:center">
                                                    	<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php print number_format($ITM_PRICE, $decFormat); ?>" onBlur="changeValuePrc(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" size="10" value="<?php echo $ITM_PRICE; ?>" >
                                                        <!-- Item Price -->                                                    </td>
                                               	  	<td width="6%" nowrap style="text-align:center">
                                                    	<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_DISC<?php echo $currentRow; ?>" id="ITM_DISC<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISC, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="changeValueDisc(this, <?php echo $currentRow; ?>)" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISP]" id="data<?php echo $currentRow; ?>ITM_DISP" size="10" value="<?php echo $ITM_DISP; ?>" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISC]" id="data<?php echo $currentRow; ?>ITM_DISC" size="10" value="<?php echo $ITM_DISC; ?>" >
                                                    </td>
                                               	  	<td width="6%" nowrap style="text-align:center">
                                                    	<select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="data<?php echo $currentRow; ?>TAXCODE1" class="form-control" style="min-width:100px; max-width:150px" onChange="changeValueTax(this, <?php echo $currentRow; ?>)">
                                                        	<option value=""> --- no tax --- </option>
                                                        	<option value="TAX01" <?php if($TAXCODE1 == 'TAX01') { ?> selected <?PHP } ?>>PPn 10% </option>
                                                        	<option value="TAX02" <?php if($TAXCODE1 == 'TAX02') { ?> selected <?PHP } ?>>PPh 3%</option>
                                                    	</select>
                                                        <!-- Item Price Total PPn --></td>
                                               	  	<td width="9%" nowrap style="text-align:center"> 
                                                    	<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTAL<?php echo $currentRow; ?>" id="ITM_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($ITM_TOTAL, $decFormat); ?>" >
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="data<?php echo $currentRow; ?>ITM_TOTAL" value="<?php echo $ITM_TOTAL; ?>" >
                                                        <input type="hidden" style="min-width:130px; max-width:350px; text-align:right" name="GT_ITMPRICE<?php echo $currentRow; ?>" id="data<?php echo $currentRow; ?>GT_ITMPRICE" value="<?php echo $GT_ITMPRICE; ?>">
                                                        <!-- Item Price Total -->
                                                    </td>
                                               	  	<td width="22%" style="text-align:center">
                                           				<input type="text" name="data[<?php echo $currentRow; ?>][NOTES]" id="data<?php echo $currentRow; ?>NOTES" value="<?php echo $NOTES; ?>" class="form-control" style="max-width:350px;text-align:left">
                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>">
                                                        <!-- Notes -->													</td>
													<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                              </tr>
                                          		<?php
                                                endforeach;
                                            }
                                        ?>
                                    </table>
                              </div>
                            </div>
                        </div>
                        <input type="hidden" name="IR_AMOUNT" id="IR_AMOUNT" value="<?php echo $IR_AMOUNT; ?>">
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            	<?php
									if($disableAll == 0)
									{
										if(($IR_STAT == 2 || $IR_STAT == 7) && ($ISAPPROVE == 1 && $canApprove == 1))
										{
											?>
												<button class="btn btn-primary" >
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
									}
                                    $backURL	= site_url('c_inventory/c_ir180c15/indexInb/');
                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
								?>
                            </div>
                        </div>
						<?php
                            $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM'";
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
                                            $boxCol_1	= "red";
                                            $sqlCAPPH_1	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_1'";
                                            $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
                                            if($resCAPPH_1 > 0)
                                            {
                                                $boxCol_1	= "green";
                                                $class		= "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_1	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_1'";
                                                $resAPPH_1	= $this->db->query($sqlAPPH_1)->result();
                                                foreach($resAPPH_1 as $rowAPPH_1):
                                                    $APPROVED_1	= $rowAPPH_1->AH_APPROVED;
                                                endforeach;
                                            }
                                            elseif($resCAPPH_1 == 0)
                                            {
                                                $Approver	= $NotYetApproved;
                                                $class		= "glyphicon glyphicon-remove-sign";
                                                $APPROVED_1	= "Not Set";
                                            }
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_1; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo $EMPN_1; ?></span>
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
                                            $boxCol_2	= "red";
                                            $sqlCAPPH_2	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_2'";
                                            $resCAPPH_2	= $this->db->count_all($sqlCAPPH_2);
                                            if($resCAPPH_2 > 0)
                                            {
                                                $boxCol_2	= "green";
                                                $class		= "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_2	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_2'";
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
                                            }
                                            
                                            if($resCAPPH_1 < 2)
                                            {
                                                $Approver	= $Awaiting;
                                                $boxCol_2	= "yellow";
                                                $class		= "glyphicon glyphicon-info-sign";
                                            }
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_2; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo $EMPN_2; ?></span>
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
                                            $boxCol_3	= "red";
                                            $sqlCAPPH_3	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_3'";
                                            $resCAPPH_3	= $this->db->count_all($sqlCAPPH_3);
                                            if($resCAPPH_3 > 0)
                                            {
                                                $boxCol_3	= "green";
                                                $class		= "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_3	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_3'";
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
                                            }
                                            
                                            if($resCAPPH_2 < 3)
                                            {
                                                $Approver	= $Awaiting;
                                                $boxCol_3	= "yellow";
                                                $class		= "glyphicon glyphicon-info-sign";
                                            }
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_3; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo $EMPN_3; ?></span>
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
                                            $boxCol_4	= "red";
                                            $sqlCAPPH_4	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_4'";
                                            $resCAPPH_4	= $this->db->count_all($sqlCAPPH_4);
                                            if($resCAPPH_4 > 0)
                                            {
                                                $boxCol_4	= "green";
                                                $class		= "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_4	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_4'";
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
                                            }
                                            
                                            if($resCAPPH_3 < 4)
                                            {
                                                $Approver	= $Awaiting;
                                                $boxCol_4	= "yellow";
                                                $class		= "glyphicon glyphicon-info-sign";
                                            }
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_4; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo $EMPN_4; ?></span>
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
                                            $boxCol_5	= "red";
                                            $sqlCAPPH_5	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_5'";
                                            $resCAPPH_5	= $this->db->count_all($sqlCAPPH_5);
                                            if($resCAPPH_5 > 0)
                                            {
                                                $boxCol_5	= "green";
                                                $class		= "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_5	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$APPROVER_5'";
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
                                            }
                                            
                                            if($resCAPPH_4 < 5)
                                            {
                                                $Approver	= $Awaiting;
                                                $boxCol_5	= "yellow";
                                                $class		= "glyphicon glyphicon-info-sign";
                                            }
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_5; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo $EMPN_5; ?></span>
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
                </div>
            </div>
        </div>
    </div>
</section>
</body>

</html>

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
</script>

<script>
	var decFormat		= 2;	
	
	function checkInp()
	{
		IR_STAT		= document.getElementById("IR_STAT").value;
		
		if(IR_STAT == 0)
		{
			alert("<?php echo $alert3; ?>");
			document.getElementById("IR_STAT").focus();
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