<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2017
 * File Name	= itemreceipt_form.php
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
	$LangID 	= $this->session->userdata['LangID'];
	if(isset($Pattern_Position))
	{
		$isSetDocNo = 1;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive 	= date('Y');
			$Pattern_MonthAktive 	= date('m');
			$Pattern_DateAktive 	= date('d');
		}
		$year 						= (int)$Pattern_YearAktive;
		$month 						= (int)$Pattern_MonthAktive;
		$date 						= (int)$Pattern_DateAktive;
	}
	else
	{
		$isSetDocNo = 0;
		$Pattern_Code 			= "XXX";
		$Pattern_Length 		= "5";
		$useYear 				= 1;
		$useMonth 				= 1;
		$useDate 				= 1;
		
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		$year 					= (int)$Pattern_YearAktive;
		$month 					= (int)$Pattern_MonthAktive;
		$date 					= (int)$Pattern_DateAktive;
		
		if($LangID == 'IND')
		{
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
		}
		else
		{
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
		}
	}
	
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
	
	$DocNumber		= "$Pattern_Code$groupPattern-$lastPatternNumb";
	
	$IR_NUM 		= $DocNumber;
	$IR_NUM_BEF		= '';
	$IR_CODE 		= $lastPatternNumb;
	
	$IRCODE			= substr($lastPatternNumb, -4);
	$IRYEAR			= date('y');
	$IRMONTH		= date('m');
	$IR_CODE		= "$Pattern_Code.$IRCODE.$IRYEAR.$IRMONTH"; // MANUAL CODE
	
	$IR_SOURCE		= 1;
	$IR_DATE		= date('m/d/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$IR_REFER		= '';
	$PO_NUM			= '';
	$PO_CODE		= '';
	$IR_AMOUNT		= 0;
	$APPROVE		= 0;
	$IR_STAT		= 1;
	$IR_NOTE		= '';
	$IR_NOTE2		= '';
	$REVMEMO		= '';
	$WH_CODE		= '';
	$Patt_Number	= $lastPatternNumb1;
	$PO_NUMX		= '';
	$SOURCE_DOC		= 'PO';					// PO, SO
	
	if(isset($_POST['PO_NUMX']))
	{
		$PO_NUMX		= $_POST['PO_NUMX'];
		$SOURCE_DOC		= $_POST['SOURCE_DOC'];
	}

	$PO_CODE	= '';
	$PR_NUM		= '';
	$SPLCODE	= '';
	$TERM_PAY	= 0;
	
	if($SOURCE_DOC == 'PO')
	{
		$sqlPOH		= "SELECT PO_CODE, PR_NUM, SPLCODE, PO_TENOR 
						FROM tbl_po_header WHERE PO_NUM = '$PO_NUMX' AND PRJCODE = '$PRJCODE'";
		$resPOH 	= $this->db->query($sqlPOH)->result();
		foreach($resPOH as $row1):
			$PO_CODE	= $row1->PO_CODE;
			$PR_NUM		= $row1->PR_NUM;
			$SPLCODE	= $row1->SPLCODE;
			$TERM_PAY	= $row1->PO_TENOR;
		endforeach;
	}
	else
	{
		$sqlPOH		= "SELECT SO_CODE, SC_NUM, CUST_CODE, 0 AS SO_TENOR 
						FROM tbl_so_header WHERE SO_NUM = '$PO_NUMX' AND PRJCODE = '$PRJCODE'";
		$resPOH 	= $this->db->query($sqlPOH)->result();
		foreach($resPOH as $row1):
			$PO_CODE	= $row1->SO_CODE;
			$PR_NUM		= $row1->SC_NUM;
			$SPLCODE	= $row1->CUST_CODE;
			$TERM_PAY	= $row1->SO_TENOR;
		endforeach;
	}
	$GT_TOTAMOUNT	= 0;
	//echo $TERM_PAY;
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_ir_header~$Pattern_Length";
	$dataTarget		= "IR_CODE";
}
else
{
	$isSetDocNo = 1;
	$IR_NUM 		= $default['IR_NUM'];
	$IR_NUM_BEF		= $IR_NUM;
	$IR_CODE 		= $default['IR_CODE'];
	$IR_SOURCE 		= $default['IR_SOURCE'];
	$IR_DATE 		= $default['IR_DATE'];
	$IR_DUEDATE		= $default['IR_DUEDATE'];
	$IR_DATE		= date('m/d/Y', strtotime($IR_DATE));
	$PRJCODE 		= $default['PRJCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$IR_REFER 		= $default['IR_REFER'];
	$PR_NUM			= $IR_REFER;
	$PO_NUM 		= $default['PO_NUM'];
	$PO_CODE 		= $default['PO_CODE'];
	$PO_NUMX 		= $default['PO_NUM'];
	$PR_NUM 		= $default['PR_NUM'];
	$IR_AMOUNT 		= $default['IR_AMOUNT'];
	$TERM_PAY 		= $default['TERM_PAY'];
	$TRXUSER 		= $default['TRXUSER'];
	$APPROVE 		= $default['APPROVE'];
	$IR_STAT 		= $default['IR_STAT'];
	$INVSTAT 		= $default['INVSTAT'];
	$IR_NOTE 		= $default['IR_NOTE'];
	$IR_NOTE2 		= $default['IR_NOTE2'];
	$REVMEMO		= $default['REVMEMO'];
	$WH_CODE		= $default['WH_CODE'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Number	= $default['Patt_Number'];
	$GT_TOTAMOUNT	= $IR_AMOUNT;
}

// Project List
$DefEmp_ID	= $this->session->userdata['Emp_ID'];
$sqlPLC		= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
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

// REJECT FUNCTION
	// CEK ACCESS OTORIZATION
		$resAPP	= 0;
		$sqlAPP	= "tbl_docstepapp_det WHERE MENU_CODE = 'MN020'
					AND PRJCODE = '$PRJCODE' AND APPROVER_1 = '$DefEmp_ID'";
		//$resAPP	= $this->db->count_all($sqlAPP);
	// CEK IR
		$DOC_NO		= '';
		$isUSED		= 0;
		$sqlIRC		= "SELECT TTK_CREATED FROM tbl_ir_header WHERE IR_NUM = '$IR_NUM'";
		$resIRC		= $this->db->query($sqlIRC)->result();
		foreach($resIRC as $rowIRC):
			$isUSED	= $rowIRC->TTK_CREATED;
		endforeach;
		if($isUSED == 1)
		{
			$sqlIR 	= "SELECT TTK_NUM FROM tbl_ttk_detail WHERE TTK_REF1 = '$IR_NUM' LIMIT 1";
			$resIR	= $this->db->query($sqlIR)->result();
			foreach($resIR as $rowIR):
				$DOC_NO	= $rowIR->TTK_NUM;
			endforeach;
		}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
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
<style type="text/css">
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>

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
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
		if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
		if($TranslCode == 'Search')$Search = $LangTransl;
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
	endforeach;
	$secGenCode	= base_url().'index.php/c_inventory/c_ir180c15/genCode/'; // Generate Code
	
	if($LangID == 'IND')
	{
		$alert1		= "Jumlah yang akan dipesan lebih besar dari sisa permintaan";
		$alert2		= "Silahkan Tentukan No. PO.";
		$alert3		= "Belum ada detail item.";
		$alert4		= "Silahkan tulis alasan revisi/tolak/membatalkan dokumen.";
		$isManual	= "Centang untuk kode manual.";
		$alertREJ	= "Tidak dapat diproses. Sudah digunakan oleh Dokumen No.: ";
	}
	else
	{
		$alert1		= "Remain Qty is greather then Remain  Request QTY";
		$alert2		= "Please select PO Number";
		$alert3		= "Item can not be empty";
		$alert4		= "Plese input the reason why you revise/reject/void the document.";
		$isManual	= "Check to manual code.";
		$alertREJ	= "Can not be processed. Used by document No.: ";
	}
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
                	<!-- Mencari Kode Purchase Order Number -->
                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="text" name="PO_NUMX" id="PO_NUMX" class="textbox" value="<?php echo $PO_NUMX; ?>" />
                        <input type="text" name="SOURCE_DOC" id="SOURCE_DOC" class="textbox" value="<?php echo $SOURCE_DOC; ?>" />
                        <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
                    <!-- End -->
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
                                </td>
                                <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                            </tr>
                        </table>
                    </form>
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                    	<input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
           				<input type="hidden" name="rowCount" id="rowCount" value="0">
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptCode ?> </label>
                          	<div class="col-sm-10">
                                <input type="text" name="IR_NUM1" id="IR_NUM1" value="<?php echo $IR_NUM; ?>" class="form-control" style="max-width:175px" disabled >
                                <input type="hidden" name="Patt_Year" id="Patt_Year" value="<?php echo $Patt_Year; ?>">
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
                                <input type="hidden" name="IR_NUM" id="IR_NUM" value="<?php echo $IR_NUM; ?>" >
                                <input type="hidden" name="IR_NUM_BEF" id="IR_NUM_BEF" value="<?php echo $IR_NUM_BEF; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?> </label>
                          	<div class="col-sm-10">
                                <label>
                                    <input type="text" class="form-control" style="min-width:width:150px; max-width:150px" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" >
                                </label>
                                <label>
                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
                                </label>
                                <label style="font-style:italic">
                                    <?php echo $isManual; ?>
                                </label>
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
                                            <input type="text" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px">
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
                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE1" value="1" <?php if($IR_SOURCE == 1) { ?> checked <?php } ?> disabled>
                                &nbsp;&nbsp;Direct&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE2" value="2" <?php if($IR_SOURCE == 2) { ?> checked <?php } ?> disabled>
                                &nbsp;&nbsp;MR&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE3" value="3" checked>
                                &nbsp;&nbsp;PO
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $RefNumber; ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-btn">
										<button type="button" class="btn btn-primary"><?php echo $Search; ?> </button>
                                    </div>
                                    <input type="hidden" class="form-control" name="Ref_NumberPO" id="Ref_NumberPO" style="max-width:160px" value="<?php echo $PO_NUMX; ?>" >
                                    <input type="hidden" class="form-control" name="IR_REFER" id="IR_REFER" style="max-width:160px" value="<?php echo $PR_NUM; ?>" >
                                    <input type="hidden" class="form-control" name="PO_CODE" id="PO_CODE" style="max-width:160px" value="<?php echo $PO_CODE; ?>" >
                                    <input type="text" class="form-control" name="PO_NUM1" id="PO_NUM1" style="max-width:200px" value="<?php echo $PO_CODE; ?>" onClick="pleaseCheck();" <?php if($task != 'add') { ?> disabled <?php } ?>>
                                </div>
                            </div>
                        </div>
						<?php
							$url_selIR_CODE		= site_url('c_inventory/c_ir180c15/all180c15po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        ?>
                        <script>
							var url1 = "<?php echo $url_selIR_CODE;?>";
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
                           		<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
                                <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:300px" disabled >
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
                        		<select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $SupplierName; ?>" style="max-width:350px;">
                                	<option value="0" > --- </option>
                                    <?php
									if($SOURCE_DOC == 'PO')
									{
										if($countSUPL > 0)
										{
											foreach($vwSUPL as $row) :
												$SPLCODE1	= $row->SPLCODE;
												$SPLDESC1	= $row->SPLDESC;
												?>
													<option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLDESC1 - $SPLCODE1"; ?></option>
												<?php
											endforeach;
										}
									}
									else
									{
										if($countCUST > 0)
										{
											foreach($vwCUST as $row) :
												$CUST_CODE1	= $row->CUST_CODE;
												$CUST_DESC1	= $row->CUST_DESC;
												?>
													<option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$CUST_CODE1 - $CUST_DESC1"; ?></option>
												<?php
											endforeach;
										}
									}
                                    ?>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group" <?php if($SOURCE_DOC == 'SO') { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PaymentTerm; ?> </label>
                          	<div class="col-sm-10">
                                <select name="TERM_PAY" id="TERM_PAY" class="form-control" style="max-width:100px">
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
                        <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $WHLocation ?> </label>
                          	<div class="col-sm-10">
                                <select name="WH_CODE" id="WH_CODE" class="form-control" style="max-width:200px" >
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?> </label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="IR_NOTE"  id="IR_NOTE" style="max-width:350px;height:70px"><?php echo $IR_NOTE; ?></textarea>
                            </div>
                        </div>
                        <div id="IRNOTE2" class="form-group" <?php if($IR_NOTE2 == '') { ?> style="display:none" <?php } ?>>
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes ?> </label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="IR_NOTE2"  id="IR_NOTE2" style="max-width:350px;height:70px" disabled><?php echo $IR_NOTE2; ?></textarea>
                            </div>
                        </div>
                        <!--
                        	APPROVE STATUS
                            1 - New
                            2 - Confirm
                            3 - Approve
                        -->
                        <?php
							$isDisabled = 1;
							if($IR_STAT == 0 || $IR_STAT == 1 || $IR_STAT == 4)
							{
								$isDisabled = 0;
							}
						?>
                        <div class="form-group" >
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
                          	<div class="col-sm-10">
                                <select name="IR_STAT" id="IR_STAT" class="form-control" style="max-width:100px" onChange="chkSTAT(this.value)">
                                    <?php
										$disableBtn	= 0;
										if($IR_STAT == 5 || $IR_STAT == 6 || $IR_STAT == 9)
										{
											$disableBtn	= 1;
										}
										if($IR_STAT != 1 AND $IR_STAT != 4) 
										{
											?>
                                                <option value="1"<?php if($IR_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
                                                <option value="2"<?php if($IR_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
                                                <option value="3"<?php if($IR_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
                                                <option value="4"<?php if($IR_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
                                                <option value="5"<?php if($IR_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
                                                <option value="6"<?php if($IR_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
                                                <option value="7"<?php if($IR_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
                                                <option value="9"<?php if($IR_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
											<?php
										}
										else
										{
											?>
												<option value="1"<?php if($IR_STAT == 1) { ?> selected <?php } ?>>New</option>
												<option value="2"<?php if($IR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
											<?php
										}
									?>
                                </select>
                            </div>
                        </div>
						<?php
							$url_AddItem	= site_url('c_inventory/c_ir180c15/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        ?>
                        <script>
							function chkSTAT(selSTAT)
							{
								if(selSTAT == 5 || selSTAT == 9)
								{
									document.getElementById('IRNOTE2').style.display = '';
									document.getElementById("IR_NOTE2").disabled = false;
									
									var isUSED	= document.getElementById('isUSED').value;
									if(isUSED > 0)
									{
										alert('<?php echo $alertREJ; ?>'+' <?php echo $DOC_NO; ?>');
										return false;
									}
									else
									{
										document.getElementById('btnREJECT').style.display = '';
									}
								}
								else if(selSTAT == 6)
								{
									document.getElementById('IRNOTE2').style.display = '';
									document.getElementById("IR_NOTE2").disabled = false;
									
									document.getElementById('btnREJECT').style.display = '';
								}
								else
								{
									document.getElementById('btnREJECT').style.display = 'none';
									document.getElementById("IR_NOTE2").disabled = true;
								}
							}
						</script>
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
                                <div class="search-table-outter">
                                    <table width="100%" border="1" id="tbl">
                                        <tr style="background:#CCCCCC">
                                            <th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                                          	<th width="7%" rowspan="2" style="text-align:center"><?php echo $ItemCode; ?> </th>
                                          	<th width="21%" rowspan="2" style="text-align:center"><?php echo $ItemName; ?> </th>
                                          	<th colspan="3" style="text-align:center"><?php echo $Receipt; ?> </th>
                                            <th rowspan="2" style="text-align:center"><?php echo $Discount; ?><br>(%)</th>
                                            <th rowspan="2" style="text-align:center"><?php echo $Discount; ?></th>
                                            <th rowspan="2" style="text-align:center"><?php echo $PPn; ?></th>
                                            <th rowspan="2" style="text-align:center"><?php echo $Total; ?></th>
                                            <th width="9%" rowspan="2" style="text-align:center">Bonus<br>(Qty)</th>
                                            <th width="12%" rowspan="2" style="text-align:center"><?php echo $Remarks ?></th>
                                      	</tr>
                                        <tr style="background:#CCCCCC">
                                            <th style="text-align:center;"><?php echo $Quantity; ?> </th>
                                            <th style="text-align:center;"><?php echo $Unit; ?> </th>
                                            <th style="text-align:center"><?php echo $Price; ?> </th>
                                        </tr>
                                        <?php
										$resultC	= 0;
										if($task == 'add' && $PO_NUMX != '')
										{
											if($SOURCE_DOC == 'PO')
											{
												$sqlDETPO	= "SELECT A.PO_NUM, A.PO_CODE, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, 
																	A.ITM_UNIT, A.PO_VOLM AS ITM_QTY, A.PO_VOLM AS PO_VOLM,
																	0 AS ITM_QTY_BONUS, 
																	A.PO_PRICE AS ITM_PRICE, 
																	A.PO_DISP AS ITM_DISP, A.PO_DISC AS ITM_DISC,
																	A.PO_COST AS ITM_TOTAL, A.PO_DESC AS NOTES, A.IR_VOLM, A.IR_AMOUNT,
																	A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2, A.ID AS POD_ID,
																	B.ITM_NAME, B.ACC_ID, B.ITM_GROUP,
																	B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
																	B.ISFASTM, B.ISWAGE
																FROM tbl_po_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																WHERE PO_NUM = '$PO_NUMX' 
																	AND B.PRJCODE = '$PRJCODE'";
												$result 	= $this->db->query($sqlDETPO)->result();
												
												$sqlDETC	= "tbl_po_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																WHERE PO_NUM = '$PO_NUMX' 
																	AND B.PRJCODE = '$PRJCODE'";
												$resultC 	= $this->db->count_all($sqlDETC);
											}
											else
											{
												$sqlDETPO	= "SELECT A.SO_NUM AS PO_NUM, A.SO_CODE AS PO_CODE, A.OFF_NUM AS PR_NUM, 
																	A.OFF_NUM AS JOBCODEDET, A.OFF_NUM AS JOBCODEID, A.ITM_CODE, 
																	A.ITM_UNIT, A.SO_VOLM AS ITM_QTY, A.SO_VOLM AS PO_VOLM,
																	0 AS ITM_QTY_BONUS, 
																	A.SO_PRICE AS ITM_PRICE, 
																	A.SO_DISP AS ITM_DISP, A.SO_DISC AS ITM_DISC,
																	A.SO_COST AS ITM_TOTAL, A.SO_DESC AS NOTES, A.JO_VOLM AS IR_VOLM, 
																	A.JO_AMOUNT AS IR_AMOUNT,
																	A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2, A.ID AS POD_ID,
																	B.ITM_NAME, B.ACC_ID, B.ITM_GROUP,
																	B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
																	B.ISFASTM, B.ISWAGE
																FROM tbl_so_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																WHERE SO_NUM = '$PO_NUMX' 
																	AND B.PRJCODE = '$PRJCODE'";
												$result 	= $this->db->query($sqlDETPO)->result();
												
												$sqlDETC	= "tbl_so_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																WHERE SO_NUM = '$PO_NUMX' 
																	AND B.PRJCODE = '$PRJCODE'";
												$resultC 	= $this->db->count_all($sqlDETC);
											}
										}
										else
										{
											if($task == 'edit')
											{
												$sqlDET		= "SELECT A.PRJCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
																A.ACC_ID, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT,
																A.ITM_QTY, 0 AS PO_VOLM, A.POD_ID,
																A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, 
																A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
																B.ITM_NAME, B.ACC_ID, B.ITM_GROUP,
																B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
																B.ISFASTM, B.ISWAGE,
																C.PR_NUM, C.PO_NUM
																FROM tbl_ir_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																	INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
																		AND C.PRJCODE = '$PRJCODE'
																WHERE 
																A.IR_NUM = '$IR_NUM' 
																AND A.PRJCODE = '$PRJCODE'";
												$result = $this->db->query($sqlDET)->result();
												// count data
												$sqlDETC	= "tbl_ir_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																WHERE 
																A.IR_NUM = '$IR_NUM' 
																AND A.PRJCODE = '$PRJCODE'";
												$resultC 	= $this->db->count_all($sqlDETC);
											}
										}
										
										$i		= 0;
										$j		= 0;
										if($resultC > 0)
										{
											$GT_TOTAMOUNT		= 0;
											foreach($result as $row) :
												$currentRow  	= ++$i;
												$IR_NUM 		= $IR_NUM;
												$IR_CODE 		= $IR_CODE;
												$PO_NUM 		= $PO_NUM;
												if($task == 'add' && $PO_NUMX != '')
												{
													$PR_NUM 	= $row->PR_NUM;
													$PO_NUM 	= $row->PO_NUM;
													$ADDQIERY	= "AND A.PO_NUM = '$PO_NUM'";
												}
												elseif($PO_NUMX != '')
												{
													$PR_NUM 	= $row->PR_NUM;
													$PO_NUM 	= $row->PO_NUM;
													$ADDQIERY	= "AND A.PO_NUM = '$PO_NUM'";
												}
												else
												{
													$PR_NUM 	= $PR_NUM;
													$PO_NUM 	= $PO_NUM;
													$ADDQIERY	= "";
												}
												
												$PRJCODE		= $PRJCODE;
												$JOBCODEDET 	= $row->JOBCODEDET;
												$JOBCODEID		= $row->JOBCODEID;
												$ACC_ID 		= $row->ACC_ID;
												$POD_ID 		= $row->POD_ID;
												$ITM_CODE 		= $row->ITM_CODE;
												$ITM_UNIT 		= $row->ITM_UNIT;
												$ITM_GROUP 		= $row->ITM_GROUP;
												$ITM_NAME 		= $row->ITM_NAME;
												$ITM_QTY1 		= $row->ITM_QTY;
												
												// GET REMAIN
												$TOT_IRQTY	= $ITM_QTY1;
												$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY 
																FROM tbl_ir_detail A
																	INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
																WHERE 
																	B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
																	AND A.IR_NUM != '$IR_NUM' AND IR_STAT = '3'
																	AND A.POD_ID = $POD_ID
																	$ADDQIERY";
												$resQTY 	= $this->db->query($sqlQTY)->result();
												foreach($resQTY as $row1a) :
													$TOT_IRQTY 	= $row1a->TOT_IRQTY;
												endforeach;
												
												$ITM_QTY 		= $ITM_QTY1 - $TOT_IRQTY;
												$PO_VOLM 		= $row->PO_VOLM;
												$ITM_QTY_BONUS	= $row->ITM_QTY_BONUS;
												$ITM_PRICE 		= $row->ITM_PRICE;
												$ITM_DISP 		= $row->ITM_DISP;
												$ITM_DISC 		= $row->ITM_DISC;
												$ITM_TOTAL 		= $row->ITM_TOTAL;
												$ITM_DESC 		= $row->NOTES;
												//$IR_VOLM 		= $row->IR_VOLM;
												//$IR_AMOUNT 	= $row->IR_AMOUNT;
												$TAXCODE1		= $row->TAXCODE1;
												$TAXCODE2		= $row->TAXCODE2;
												$TAXPRICE1		= $row->TAXPRICE1;
												$TAXPRICE2		= $row->TAXPRICE2;
												$itemConvertion	= 1;
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
												
												if($task == 'add')
												{
													$ITM_TOTAL 	= $ITM_QTY * $ITM_PRICE;
												}
												else
												{
													$ITM_TOTAL 	= $row->ITM_TOTAL;			// Non-PPn
												}
												$GT_ITMPRICE	= $ITM_TOTAL - $ITM_DISC;
												
												if($TAXCODE1 == 'TAX01')
												{
													$TAXPRICE1	= $ITM_TOTAL * 0.1;
												}
												
												$TOTITMPRICE	= $GT_ITMPRICE;
												if($TAXCODE1 == 'TAX01')
												{
													$TOTITMPRICE	= $GT_ITMPRICE + $TAXPRICE1;
												}
												if($TAXCODE1 == 'TAX02')
												{
													$TOTITMPRICE	= $GT_ITMPRICE - $TAXPRICE1;
												}
												
												$ITM_TOTALnPPn	= $TOTITMPRICE;	
												
												$GT_TOTAMOUNT	= $GT_TOTAMOUNT + $ITM_TOTALnPPn;
												
												/*$sqlDETPO	= "SELECT A.PO_VOLM
																FROM tbl_po_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		AND B.PRJCODE = '$PRJCODE'
																WHERE PO_NUM = '$PO_NUM' 
																	AND B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'";	
												$resDETPO 	= $this->db->query($sqlDETPO)->result();	
												foreach($resDETPO as $rowDETPO) :
													$PO_VOLM 	= $rowDETPO->PO_VOLM;
												endforeach;*/
												
												$REMAINQTY	= $ITM_QTY1 - $TOT_IRQTY;
												//$REMAINQTY	= $PO_VOLM;
												
												//echo $REMAINQTY;
												if($task == 'add')
													$ITM_QTY 	= $REMAINQTY;
												else
													$ITM_QTY 	= $ITM_QTY;
													
												if ($j==1) {
													echo "<tr class=zebra1>";
													$j++;
												} else {
													echo "<tr class=zebra2>";
													$j--;
												}
												?> 
												<tr><td width="2%" height="25" style="text-align:left">
													<?php echo "$currentRow."; ?>
													<input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
													<input type="Checkbox" style="display:none" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="">
													<input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>IR_NUM" name="data[<?php echo $currentRow; ?>][IR_NUM]" value="<?php echo $IR_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>IR_CODE" name="data[<?php echo $currentRow; ?>][IR_CODE]" value="<?php echo $IR_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>PO_NUM" name="data[<?php echo $currentRow; ?>][PO_NUM]" value="<?php echo $PO_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<!-- Checkbox -->
												</td>
												<td width="9%" style="text-align:left" nowrap>
													<?php echo $ITM_CODE; ?>
													<input type="hidden" id="data<?php echo $currentRow; ?>POD_ID" name="data[<?php echo $currentRow; ?>][POD_ID]" value="<?php echo $POD_ID; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" width="10" size="15" readonly class="form-control">
													<input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php echo $JOBCODEDET; ?>" width="10" size="15" readonly class="form-control">
													<input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" width="10" size="15" readonly class="form-control">
													<!-- Item Code -->												</td>
												<td width="20%" style="text-align:left">
													<?php echo $ITM_NAME; ?>
													<input type="hidden" class="form-control" name="itemname<?php echo $currentRow; ?>" id="itemname<?php echo $currentRow; ?>" value="<?php echo $ITM_NAME; ?>" >
													<!-- Item Name -->												</td>
												<td width="11%" style="text-align:right">
													<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTYx<?php echo $currentRow; ?>" id="ITM_QTYx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onBlur="changeValue(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> size="20" >
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="ITM_QTY<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" >
													<input type="hidden" style="text-align:right" id="REMAINQTY<?php echo $currentRow; ?>" size="10" value="<?php echo $REMAINQTY; ?>" >
													<!-- Item Qty -->												</td>
												<td width="5%" nowrap style="text-align:center">
													<?php echo $ITM_UNIT; ?>
													<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="ITM_UNIT<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT; ?>" >
                                                    <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" id="ITM_GROUP<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_GROUP; ?>" >
                                                    <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TYPE]" id="ITM_TYPE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TYPE; ?>" >
													<!-- Item Unit -->												</td>
												<td width="6%" nowrap style="text-align:center; font-style:italic">
                                                	hidden
													<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right;" name="ITM_PRICEx<?php echo $currentRow; ?>" id="ITM_PRICEx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_PRICE, $decFormat); ?>" onBlur="changeValuePrc(this, <?php echo $currentRow; ?>)" disabled >
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="ITM_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_PRICE; ?>" >
													<!-- Item Price -->												</td>
												<td width="6%" nowrap style="text-align:center; font-style:italic">
                                                	hidden
													<input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right;" name="ITM_DISPx<?php echo $currentRow; ?>" id="ITM_DISPx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $currentRow; ?>);" disabled >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_DISP]" id="ITM_DISP<?php echo $currentRow; ?>" value="<?php echo $ITM_DISP; ?>"></td>
												<td width="6%" nowrap style="text-align:center; font-style:italic">
                                                	hidden
													<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right; display:none" name="ITM_DISCx<?php echo $currentRow; ?>" id="ITM_DISCx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $currentRow; ?>);" disabled >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_DISC]" id="ITM_DISC<?php echo $currentRow; ?>" value="<?php echo $ITM_DISC; ?>"></td>
												<td width="6%" nowrap style="text-align:center; font-style:italic">
                                                	hidden
                                                	<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" value="<?php echo $TAXCODE1; ?>">
                                                    <select name="data<?php echo $currentRow; ?>TAXCODE1" class="form-control" id="TAXCODE1<?php echo $currentRow; ?>"  onChange="getValueIR(this, <?php echo $currentRow; ?>);" style="min-width:100px; max-width:150px; display:none" disabled>
														<option value=""> --- no tax --- </option>
														<option value="TAX01" <?php if($TAXCODE1 == 'TAX01') { ?> selected <?PHP } ?>>PPn 10% </option>
														<option value="TAX02" <?php if($TAXCODE1 == 'TAX02') { ?> selected <?PHP } ?>>PPh 3%</option>
													</select>
													<!-- Item Price Total PPn --></td>
												<td width="8%" nowrap style="text-align:center; font-style:italic">
                                                	hidden
                                                    <input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALx<?php echo $currentRow; ?>'" id="ITM_TOTALx<?php echo $currentRow; ?>" value="<?php echo number_format($GT_ITMPRICE, 2); ?>" >
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="ITM_TOTAL<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TOTAL; ?>" >
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="TAXPRICE1<?php echo $currentRow; ?>" size="10" value="<?php echo $TAXPRICE1; ?>" >
                                                    <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right;" name="GT_ITMPRICE<?php echo $currentRow; ?>" id="GT_ITMPRICE<?php echo $currentRow; ?>" value="<?php print number_format($ITM_TOTALnPPn, $decFormat); ?>" disabled >
													<!-- Item Price Total --></td>
												<td width="9%" style="text-align:center">
                                                	<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTY_BONUSx<?php echo $currentRow; ?>" id="ITM_QTY_BONUSx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY_BONUS, $decFormat); ?>" onBlur="changeValueQtyBonus(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> size="20" >
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY_BONUS]" id="ITM_QTY_BONUS<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY_BONUS; ?>" ></td>
												<td width="12%" style="text-align:center"><input type="text" name="data[<?php echo $currentRow; ?>][NOTES]" id="data<?php echo $currentRow; ?>NOTES" value="<?php echo $ITM_DESC; ?>" class="form-control" style="max-width:250px;text-align:left" size="10"></td>
										  </tr>
											<?php
											endforeach;
											?>
											<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
											<?php
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
                        </div>
                        <script>
							function changeValueQtyBonus(thisVal, theRow)
							{
								var decFormat	= document.getElementById('decFormat').value;	
								var ITM_QTY_BNS	= eval(thisVal).value.split(",").join("");
								
								document.getElementById('ITM_QTY_BONUS'+theRow).value 	= parseFloat(Math.abs(ITM_QTY_BNS));
								document.getElementById('ITM_QTY_BONUSx'+theRow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_BNS)),decFormat));
							}
						</script>
                        <input type="hidden" name="IR_AMOUNT" id="IR_AMOUNT" value="<?php echo $GT_TOTAMOUNT; ?>">
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            	<?php
									$showBtn	= 0;
									if($IR_STAT == 2 || $IR_STAT == 3)
									{
										$showBtn	= 0;
									}
									else
									{
										$showBtn	= 1;
									}
									if($ISCREATE == 1 && $showBtn == 1)
									{
										if($task=='add')
										{
											?>
												<button class="btn btn-primary">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
												</button>&nbsp;
											<?php
										}
										else
										{
											?>
												<button class="btn btn-primary" >
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
									}
									?>
                                   		<button class="btn btn-primary" id="btnREJECT" style="display:none" >
                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                        </button>
                                   	<?php
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
	
	function checkInp()
	{
		totalrow	= document.getElementById("totalrow").value;
		PO_NUM1		= document.getElementById("PO_NUM1").value;
		IR_STAT		= document.getElementById("IR_STAT").value;
		
		if(PO_NUM1 == '')
		{
			alert("<?php echo $alert2; ?>");
			return false;
		}
		
		if(IR_STAT == 5 || IR_STAT == 6 || IR_STAT == 9)
		{
			IR_NOTE2		= document.getElementById("IR_NOTE2").value;
			if(IR_NOTE2 == '')
			{
				alert("<?php echo $alert4; ?>");
				document.getElementById("IR_NOTE2").focus();
				return false;
			}
		}
		
		if(totalrow == 0)
		{
			alert("<?php echo $alert3; ?>");
			return false;
		}
		
		for(i=1; i<=totalrow; i++)
		{
			ITM_QTY	= document.getElementById('ITM_QTY'+i).value;
			ITM_NM	= document.getElementById('itemname'+i).value;
			if(ITM_QTY == 0)
			{
				alert('Item '+ ITM_NM +' qty can not be empty.');
				document.getElementById('ITM_QTYx'+i).focus();
				return false;
			}
		}
	}
	
	function changeValue(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var REMAINQTY	= parseFloat(document.getElementById('REMAINQTY'+theRow).value);
		
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");

		//alert('ITM_QTYx = '+ITM_QTYx)	
		//alert('REMAINQTY = '+REMAINQTY)	
		if(ITM_QTYx > REMAINQTY)
		{
			alert('<?php echo $alert1; ?>');
			document.getElementById('ITM_QTYx'+theRow).value 	= REMAINQTY;
			document.getElementById('ITM_QTY'+theRow).value 	= parseFloat(Math.abs(REMAINQTY));
			document.getElementById('ITM_QTYx'+theRow).focus();
			return false;
		}
		
		document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTYx));
		document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYx)),decFormat));
		
		var ITM_DISP			= document.getElementById('ITM_DISP'+theRow).value;
		var ITM_QTY				= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE			= document.getElementById('ITM_PRICE'+theRow).value;
		var ITM_TOTAL			= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		var DISCOUNT			= parseFloat(ITM_DISP * ITM_TOTAL / 100);		
		var TOT_ITMTEMP			= parseFloat(ITM_TOTAL - DISCOUNT);
		
		document.getElementById('ITM_DISC'+theRow).value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCx'+theRow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(TOT_ITMTEMP));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_ITMTEMP)),decFormat));
		
		var theTAX				= document.getElementById('TAXCODE1'+theRow).value;
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.1;
			G_itmTot	= parseFloat(TOT_ITMTEMP) + parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.03;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		//document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(G_itmTot));
		//document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat));
		
		totalrow	= document.getElementById("totalrow").value;	
		IR_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			GT_ITMPRICE		= document.getElementById('GT_ITMPRICE'+i).value;
			IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(GT_ITMPRICE);
		}
		document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISP		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('ITM_DISP'+row).value 	= parseFloat(Math.abs(ITM_DISP));
		document.getElementById('ITM_DISPx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISP)),decFormat));
		
		var ITM_QTY			= document.getElementById('ITM_QTY'+row).value;
		var ITM_PRICE		= document.getElementById('ITM_PRICE'+row).value;
		var ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		var DISCOUNT		= parseFloat(ITM_DISP * ITM_TOTAL / 100);
		
		document.getElementById('ITM_DISC'+row).value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISC		= parseFloat(eval(thisVal).value.split(",").join(""));
				
		var GT_ITMPRICE 	= document.getElementById('GT_ITMPRICE'+row).value
		var DISCOUNTP		= parseFloat(ITM_DISC / GT_ITMPRICE * 100);
		
		document.getElementById('ITM_DISP'+row).value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('ITM_DISPx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		
		document.getElementById('ITM_DISC'+row).value 	= parseFloat(Math.abs(ITM_DISC));
		document.getElementById('ITM_DISCx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function getValueIR(thisVal, row)
	{
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function changeValueTax(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		//var ITM_QTY_MIN	= document.getElementById('ITM_QTY_MIN'+theRow).value;
		//var ITM_QTY		= eval(document.getElementById('ITM_QTY'+theRow)).value.split(",").join("");
		/*if(parseFloat(ITM_QTYx) > parseFloat(ITM_QTY_MIN))
		{
			alert('Qty can not greater then '+ ITM_QTY_MIN);
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY_MIN));
			document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_MIN)),decFormat));
		}
		else
		{*/
			//document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY));
			//document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		//}
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		// PAJAK
		theTAX			= document.getElementById('TAXCODE1'+theRow).value;
		
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0.1;
			G_itmTot	= parseFloat(ITM_TOTAL) + parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0.03;
			G_itmTot	= parseFloat(ITM_TOTAL) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0;
			G_itmTot	= parseFloat(ITM_TOTAL) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		
		totalrow	= document.getElementById("totalrow").value;	
		IR_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			GT_ITMPRICE		= document.getElementById('GT_ITMPRICE'+i).value;
			IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(GT_ITMPRICE);
		}
		document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
	}
	
	function changeValuePrc(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEx 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('ITM_PRICE'+theRow).value 		= parseFloat(Math.abs(ITM_PRICEx));
		document.getElementById('ITM_PRICEx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEx)),decFormat));
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];
		
		PR_NUM		= arrItem[0];
		SOURCE_DOC	= arrItem[2];
		//alert(PR_NUM);
		//document.getElementById("PO_NUM1").value = PO_NUM;
		document.getElementById("PO_NUMX").value 	= PR_NUM;
		document.getElementById("SOURCE_DOC").value = SOURCE_DOC;
		document.frmsrch.submitSrch.click();
	}

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var IR_NUM 	= "<?php echo $IR_NUM; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		ilvl = arrItem[1];
		
		validateDouble(arrItem[0], PRJCODE)
		if(validateDouble(arrItem[0], PRJCODE))
		{
			alert("Double Item for " + arrItem[0]);
			return false;
		}
		
		itemcode 		= arrItem[0];
		itemserial 		= arrItem[1];
		itemname 		= arrItem[2];
		itemUnit 		= arrItem[3];
		itemUnitName 	= arrItem[4];
		itemUnit2 		= arrItem[5];
		itemUnitName2 	= arrItem[6];
		itemConvertion 	= arrItem[9];
		itemQty 		= 0;
		itemPrice 		= arrItem[11];
		Acc_Id 			= arrItem[12];
		
		ITM_TOTAL		= itemQty * itemPrice;
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//alert('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = ''+intIndex+'<input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onClick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value=""><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_NUM" name="data['+intIndex+'][IR_NUM]" value="'+IR_NUM+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_CODE" name="data['+intIndex+'][IR_CODE]" value="'+IR_NUM+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';	
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ACC_ID" name="data['+intIndex+'][ACC_ID]" value="'+Acc_Id+'" width="10" size="15" readonly class="form-control">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+itemname+'<input type="hidden" class="form-control" name="itemname'+intIndex+'" id="itemname'+intIndex+'" value="'+itemname+'" >';
		
		// Item Qty
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTYx'+intIndex+'" id="ITM_QTYx'+intIndex+'" value="'+itemQty+'" onBlur="changeValue(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY]" id="ITM_QTY'+intIndex+'" size="10" value="'+itemQty+'" >';
		
		// Item Unit
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+itemUnitName+'<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" >';
		
		// Item Price
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICEx'+intIndex+'" id="ITM_PRICEx'+intIndex+'" value="'+itemPrice+'" onBlur="changeValuePrc(this, '+intIndex+')" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="ITM_PRICE'+intIndex+'" size="10" value="'+itemPrice+'" >';
		
		// Item Price Total
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALx'+intIndex+'" id="ITM_TOTALx'+intIndex+'" value="'+ITM_TOTAL+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_TOTAL]" id="ITM_TOTAL'+intIndex+'" size="10" value="'+ITM_TOTAL+'" >';
		
		// Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="TAXCODE1'+intIndex+'" class="form-control" style="max-width:150px" onChange="changeValueTax(this, '+intIndex+')"><option value=""> --- no tax --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';
		
		// Notes
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][NOTES]" id="data'+intIndex+'NOTES" value="" class="form-control" style="max-width:450px;text-align:left"><input type="hidden" style="text-align:right" name="data['+intIndex+'][TAXPRICE1]" id="TAXPRICE1'+intIndex+'" value=""><input type="hidden" style="text-align:right" name="GT_ITMPRICE'+intIndex+'" id="GT_ITMPRICE'+intIndex+'" value="">';
		
		var decFormat											= document.getElementById('decFormat').value;
		var ITM_QTY												= document.getElementById('ITM_QTY'+intIndex).value
		document.getElementById('ITM_QTY'+intIndex).value 		= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		var ITM_PRICE											= document.getElementById('ITM_PRICE'+intIndex).value
		document.getElementById('ITM_PRICE'+intIndex).value 	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICEx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		var ITM_TOTAL											= document.getElementById('ITM_TOTAL'+intIndex).value
		document.getElementById('ITM_TOTAL'+intIndex).value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		document.getElementById('totalrow').value 				= intIndex;
	}	
	
	function validateDouble(vcode,PRJCODE) 
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
				var elitem1		= document.getElementById('data'+i+'ITM_CODE').value;
				var PRJCODE1	= document.getElementById('data'+i+'PRJCODE').value;
				if (elitem1 == vcode && PRJCODE == PRJCODE)
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