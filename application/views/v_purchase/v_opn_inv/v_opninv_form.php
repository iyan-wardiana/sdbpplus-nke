<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 November 2017
 * File Name	= v_pinv_form.php
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
	$INV_TYPE 			= 0;
	$Ref_Number 		= '';
	$PONumberRef		= '';
	$INVTaxType 		= 2;
	$PRJCODE 			= $PRJCODE;
	$SPLCODE 			= '';
	$SPLDESC 			= '';
	$SPLADD1 			= '';
	$SPLADD1 			= '';
	$Currency_ID 		= 'IDR';
	$Currency_Rate 		= 1;
	$INV_TAXCURR 	= 'IDR';
	$Tax_Currency_Rate 	= 1;
	$INV_NOTES 			= '';
	$INV_NOTES1			= '';
	$INV_STATUS 		= 1;
	$Payment_Tenor 		= 30;
	$Approval_Status 	= 1;
	$totalAmount 		= '0.00';
	$BtotalAmount 		= '0.00';
	$totalDiscAmount 	= '0.00';
	$totalAmountAfDisc 	= '0.00';
	$totTaxPPnAmount 	= '0.00';
	$totTaxPPhAmount 	= '0.00';
	$GtotalAmount 		= '0.00';
	$Base_TotalPrice	= '0.00';
	$totAkumRow 		= '0.00';
	$TotalPrice 		= '0.00';					
	$proj_Number  		= '';
	$Memo_Revisi 		= '';
	$WH_ID 				= 0;
	$RRSource			= '';
	$mySPLCODE			= '';
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

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_pinv_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_pinv_header
			WHERE Patt_Year = $year";
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
	
	//echo $pattMonth;
	//echo "&nbsp;";
	//echo $pattDate;
	
	// group year, month and date
	$yearG = date('y');
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$yearG$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$year";
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
	if($INV_TYPE == 1) 
	{
		$INVType = '01';
	}
	else if($INV_TYPE == 2)
	{
		$INVType = '02';
	}
	
	//$DocNumber = "$Pattern_Code$groupPattern$INVType-$lastPatternNumb";
	$DocNumber 	= "$PRJCODE.$Pattern_Code$groupPattern-$lastPatternNumb";
	$INV_NUM	= $DocNumber;
	$INV_CODE	= $lastPatternNumb;
	
	$VOCCODE		= substr($lastPatternNumb, -4);
	$VOCYEAR		= date('y');
	$VOCMONTH		= date('m');
	$INV_CODE		= "VOC.$VOCCODE.$VOCYEAR.$VOCMONTH"; // MANUAL CODE
	
	$INV_DATEY = date('Y');
	$INV_DATEM = date('m');
	$INV_DATED = date('d');
	$INV_DATE = "$INV_DATEM/$INV_DATED/$INV_DATEY";
	//$ETD = $INV_DATE;
	$Patt_Year = date('Y');
	$INV_DUEDATE = $INV_DATE;

	if(isset($_POST['submitSrch1']))
	{
		$mySPLCODE 		= $_POST['SPLCODE1'];
		$SPLCODE		= $mySPLCODE;
	}
	else
	{
		$mySPLCODE		= '';
		$SPLCODE		= $mySPLCODE;
	}
	$INV_STAT			= 1;
	$INV_TERM			= 30;
	$VENDINV_NUM		= '';
	$PO_NUM				= '';
	$TTK_NUM1			= '';
	$INV_AMOUNT 		= 0;
	$INV_AMOUNT_BASE	= 0;
	$INV_LISTTAX		= 0;
	$INV_LISTTAXVAL		= 0;
	$INV_PPH			= '';
	$INV_PPHVAL			= 0;
	$DP_NUM				= '';
	$DP_AMOUNT			= 0;
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_pinv_header~$Pattern_Length";
	$dataTarget		= "INV_CODE";
}
else
{
	$isSetDocNo = 1;
	$INV_NUM 			= $default['INV_NUM'];
	$INV_CODE 			= $default['INV_CODE'];
	$INV_TYPE 			= $default['INV_TYPE'];
	$PO_NUM 			= $default['PO_NUM'];
	$IR_NUM 			= $default['IR_NUM'];
	$TTK_NUM1			= $IR_NUM;
	$PRJCODE 			= $default['PRJCODE'];
	$INV_DATE1 			= $default['INV_DATE'];
	$INV_DATE			= date('m/d/Y', strtotime($INV_DATE1));
	$INV_DUEDATE1 		= $default['INV_DUEDATE'];
	$INV_DUEDATE		= date('m/d/Y', strtotime($INV_DUEDATE1));
	$SPLCODE 			= $default['SPLCODE'];
	$SPLCODE1 			= $default['SPLCODE'];
	$DP_NUM				= $default['DP_NUM'];
	$DP_AMOUNT			= $default['DP_AMOUNT'];
	$INV_CURRENCY		= $default['INV_CURRENCY'];
	$INV_TAXCURR	 	= $default['INV_TAXCURR'];
	$INV_AMOUNT 		= $default['INV_AMOUNT'];
	$INV_AMOUNT_BASE	= $default['INV_AMOUNT_BASE'];
	$INV_LISTTAX		= $default['INV_LISTTAX'];
	$INV_LISTTAXVAL		= $default['INV_LISTTAXVAL'];
	$INV_PPH			= $default['INV_PPH'];
	$INV_PPHVAL			= $default['INV_PPHVAL'];
	$INV_TERM 			= $default['INV_TERM'];
	$INV_STAT 			= $default['INV_STAT'];
	$INV_PAYSTAT 		= $default['INV_PAYSTAT'];
	$COMPANY_ID 		= $default['COMPANY_ID'];
	$VENDINV_NUM		= $default['VENDINV_NUM'];
	$INV_NOTES 			= $default['INV_NOTES'];
	$INV_NOTES1			= $default['INV_NOTES1'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $Patt_Number;
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    
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
		if($TranslCode == 'Invoice')$Invoice = $LangTransl;
		if($TranslCode == 'InvoiceNumber')$InvoiceNumber = $LangTransl;
		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
		if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
		if($TranslCode == 'Search')$Search = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'ReceiptQty')$ReceiptQty = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
		if($TranslCode == 'Discount')$Discount = $LangTransl;
		if($TranslCode == 'Total')$Total = $LangTransl;
		if($TranslCode == 'Tax')$Tax = $LangTransl;
		if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;				
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Cancel')$Cancel = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'SuplInvNo')$SuplInvNo = $LangTransl;
		if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
		if($TranslCode == 'IRList')$IRList = $LangTransl;
		if($TranslCode == 'OPNList')$OPNList = $LangTransl;
		if($TranslCode == 'ReceiptNumber')$ReceiptNumber = $LangTransl;
		if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
		if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
		if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
		if($TranslCode == 'DPCode')$DPCode = $LangTransl;
		if($TranslCode == 'Reason')$Reason = $LangTransl;
	endforeach;
	
	$PRJCODE1	= $PRJCODE;
	$SPLCODE1	= $SPLCODE;
	if(isset($_POST['PRJCODE1']))
	{
		$PRJCODE1	= $_POST['PRJCODE1'];
		$SPLCODE1	= $_POST['SPLCODE1'];
	}
	
	$SPLADD1	= '';
	$sqlSUPL	= "SELECT SPLADD1 FROM tbl_supplier WHERE SPLCODE = '$SPLCODE1' AND SPLSTAT = '1' LIMIT 1";
	$resSUPL	= $this->db->query($sqlSUPL)->result();
	foreach($resSUPL as $rowSUPL):
		$SPLADD1	= $rowSUPL->SPLADD1;
	endforeach;
	
	if(isset($_POST['TTK_NUM1']))
	{
		$TTK_NUM1	= $_POST['TTK_NUM1'];
	}
	
	$sqlPRJ1 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
	$restPRJ1 	= $this->db->query($sqlPRJ1)->result();	
	foreach($restPRJ1 as $rowPRJ1) :
		$PRJNAME1 	= $rowPRJ1->PRJNAME;
	endforeach;
	
	if($LangID == 'IND')
	{
		$alert1		= "Silahkan centang Cek Total.";
		$alert2		= "Jumlah Uang Muka yang dimasukan terlalu besar.";
		$alert3		= "Masukan alasan mengapa dokumen ini di-close.";
		$isManual	= "Centang untuk kode manual.";
	}
	else
	{
		$alert1		= "Please Check the Total Checkbox.";
		$alert2		= "Total of DP Amount is too large.";
		$alert3		= "Input the reason why you close this document.";
		$isManual	= "Check to manual code.";
	}
	
	$TTK_CODE	= '';
	$TTK_CATEG	= 'IR';
	$sqlTTK	= "SELECT TTK_CODE, TTK_CATEG FROM tbl_ttk_header WHERE TTK_NUM = '$TTK_NUM1' AND PRJCODE = '$PRJCODE' LIMIT 1";
	$resTTK	= $this->db->query($sqlTTK)->result();
	foreach($resTTK as $rowTTK):
		$TTK_CODE	= $rowTTK->TTK_CODE;
		$TTK_CATEG	= $rowTTK->TTK_CATEG;
	endforeach;
	
	if($TTK_CATEG == 'IR')
		$IRList	= $IRList;
	else
		$IRList	= $OPNList;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/invoice.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $Invoice; ?>
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
                	<!-- after get Supplier code -->
                    <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                        <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE1; ?>" />
                        <input type="text" name="SPLCODE1" id="SPLCODE1" value="<?php echo $SPLCODE1; ?>" />
                        <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                    </form>
                    <!-- End -->
                	<!-- Mencari Kode Purchase Request Number -->
                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE1; ?>" />
                        <input type="text" name="SPLCODE1" id="SPLCODE1" value="<?php echo $SPLCODE1; ?>" />
                        <input type="text" name="TTK_NUM1" id="TTK_NUM1" value="<?php echo $TTK_NUM1; ?>" />
                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
                    <!-- End -->
                    
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <input type="hidden" name="rowCount" id="rowCount" value="0">
                        <input type="hidden" class="textbox" name="INV_TYPE" id="INV_TYPE" size="30" value="<?php echo $INV_TYPE; ?>" />
                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $InvoiceNumber; ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:195px" name="INV_NUMx" id="INV_NUMx" value="<?php echo $INV_NUM; ?>" disabled >
                        		<input type="hidden" class="textbox" name="INV_NUM" id="INV_NUM" size="30" value="<?php echo $INV_NUM; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?></label>
                          	<div class="col-sm-10">
                                <label>
                                    <input type="text" class="form-control" style="max-width:150px" name="INV_CODE" id="INV_CODE" value="<?php echo $INV_CODE; ?>" >
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="INV_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $INV_DATE; ?>" style="width:150px">
                                </div>

                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $DueDate; ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="INV_DUEDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $INV_DUEDATE; ?>" style="width:150px">
                                </div>

                          	</div>
                        </div>
                        <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SourceDocument; ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group">
                                    <select name="RRSource" id="RRSource" class="form-control" style="max-width:150px">
                                        <option value="IR" >Item Receipt</option>
                                        <option value="OTH" style="display:none">Other</option>    
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SupplierName; ?></label>
                          	<div class="col-sm-10">
                            	<select name="SPLCODE" id="SPLCODE" class="form-control" style="max-width:250px" onChange="getSUPPLIER(this.value)" <?php if($INV_STATUS != 1) { ?> disabled <?php } ?>>
                                    <option value="none"> --- </option>
                                    <?php echo $i = 0;
                                    if($countSUPL > 0)
                                    {
                                        foreach($vwSUPL as $row) :
                                        ?>
                                            <option value="<?php echo $row->SPLCODE; ?>" <?php if($SPLCODE1 == $row->SPLCODE) { ?> selected <?php } ?>>
                                        		<?php echo $row->SPLDESC; ?>
                                        	</option>
                                        <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                          	</div>
                        </div>
                        <script>
							function getSUPPLIER(SPLCODE) 
							{
								document.getElementById("SPLCODE1").value = SPLCODE;
								PRJCODE	= document.getElementById("PRJCODE").value
								document.getElementById("PRJCODE1").value = PRJCODE;
								document.frmsrch1.submitSrch1.click();
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $VendAddress; ?></label>
                          	<div class="col-sm-10">
                            	<?php if($SPLADD1 != '') { ?>
                                <textarea name="SPLADD1" id="SPLADD1" cols="50" class="form-control"  style="max-width:350px;height:70px"><?php echo $SPLADD1; ?></textarea>
                                <?php }?>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $RefNumber; ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-btn">
										<button type="button" class="btn btn-primary"><?php echo $Search; ?></button>
                                    </div>
                                    <input type="hidden" class="form-control" name="Ref_Number" id="Ref_Number" style="max-width:160px" value="<?php echo $TTK_NUM1; ?>" onClick="selectTTK();">
                                    <input type="text" class="form-control" name="TTK_CODE" id="TTK_CODE" style="max-width:160px" value="<?php echo $TTK_CODE; ?>" onClick="selectTTK();">
                                    <?php
										$collID			= "$PRJCODE~$SPLCODE1";
										$url_popTTK		= site_url('c_purchase/c_pi180c23/popupall_TTK/?id='.$this->url_encryption_helper->encode_url($collID));
									?>       
									<script>
										var urlTTK 	= "<?php echo $url_popTTK;?>";
										function selectTTK()
										{
											SPLCODE	= document.getElementById('SPLCODE').value;
											if(SPLCODE == 'none')
											{
												alert('Please select a Supplier');
												document.getElementById('SPLCODE').focus();
												return false;
											}
											title = 'Select Item Receive';
											w = 780;
											h = 550;
											
											var left = (screen.width/2)-(w/2);
											var top = (screen.height/2)-(h/2);
											//return window.open(urlLPM+VC, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
											return window.open(urlTTK, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
									</script>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
                          	<div class="col-sm-10">
                            	<input type="hidden" class="form-control" style="max-width:195px" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
                            	<select name="selPRJCODE" id="selPRJCODE" class="form-control" style="max-width:250px" <?php if($INV_STATUS != 1) { ?> disabled <?php } ?>>
                                <?php
                                    $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
                                    $resultPRJ 	= $this->db->query($sqlPRJ)->result();
									
									foreach($resultPRJ as $rowPRJ) :
										$PRJCODE1 	= $rowPRJ->PRJCODE;
										$PRJNAME1 	= $rowPRJ->PRJNAME;
										?>
											<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?>selected <?php } ?>>
												<?php echo $PRJNAME1; ?>
											</option>
										<?php
									 endforeach;
										 
                                ?>
                            </select>
                          	</div>
                        </div>
                        <div class="form-group">
                       	  <label for="inputName" class="col-sm-2 control-label"><?php echo $SuplInvNo; ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:250px" name="VENDINV_NUM" id="VENDINV_NUM" value="<?php echo $VENDINV_NUM; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                       	  <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="INV_NOTES"  id="INV_NOTES" style="max-width:350px;height:70px"><?php echo $INV_NOTES; ?></textarea>
                          	</div>
                        </div>
                        <div class="form-group" <?php if($INV_STAT != 6 ) { ?> style="display:none" <?php } ?> id="tblReason">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Reason; ?></label>
                            <div class="col-sm-10">
                                <textarea name="INV_NOTES1" class="form-control" style="max-width:350px;" id="INV_NOTES1" cols="30"><?php echo $INV_NOTES1; ?></textarea>                        
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Cek Total</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                      <input type="checkbox" name="chkTotal" id="chkTotal" onClick="checkTotalTTK()">
                                    </span>
                                    <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT" id="INV_AMOUNT" value="<?php echo $INV_AMOUNT; ?>" >
                                    <input type="text" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNTX" id="INV_AMOUNTX" value="<?php echo number_format($INV_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" >
                                    <textarea class="form-control" name="REF_NOTES"  id="REF_NOTES" style="max-width:350px;height:70px; display:none" readonly></textarea>
                        		</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">PPn</label>
                            <div class="col-sm-10">
                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_LISTTAXVAL" id="INV_LISTTAXVAL" value="<?php echo $INV_LISTTAXVAL; ?>" >
                                <input type="text" class="form-control" style="max-width:160px; text-align:right" name="INV_LISTTAXVALX" id="INV_LISTTAXVALX" value="<?php echo number_format($INV_LISTTAXVAL, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">PPh</label>
                            <div class="col-sm-10">
                            <label>
                                <select name="INV_PPH" id="INV_PPH" class="form-control" style="max-width:160px" onChange="selPPhStat(this.value)">
                                	<option value=""> --- none ---</option>
                                	<?php
										$sqlTLA 	= "SELECT TAXLA_NUM, TAXLA_CODE FROM tbl_tax_la";
										$resTLA 	= $this->db->query($sqlTLA)->result(); 
										foreach($resTLA as $rowTLA): 
											$TAXLA_NUM	= $rowTLA->TAXLA_NUM;
											$TAXLA_CODE	= $rowTLA->TAXLA_CODE;
											?>
											<option value="<?php echo $TAXLA_NUM; ?>"<?php if($TAXLA_NUM == $INV_PPH) { ?> selected <?php } ?>><?php echo $TAXLA_CODE; ?></option>
											<?php
										endforeach;
									?>
                                </select>
                            </label>
                            <label id="showPPh" <?php if($INV_PPH == '') { ?> style="display:none" <?php } ?>>
                            	<input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_PPHVAL" id="INV_PPHVAL" value="<?php echo $INV_PPHVAL; ?>" >
                                <input type="text" class="form-control" style="max-width:120px; text-align:right" name="INV_PPHVALX" id="INV_PPHVALX" value="<?php echo number_format($INV_PPHVAL, 2); ?>" onBlur="getAmountPPh(this)" onKeyPress="return isIntOnlyNew(event);" >
                            </label>
                            </div>
                        </div>
                        <script>
							function selPPhStat(thisVal)
							{
								if(thisVal == '')
									document.getElementById('showPPh').style.display = 'none';
								else
									document.getElementById('showPPh').style.display = '';
							}
							
							function getAmountPPn(thisVal)
							{
								
								INV_PPNVAL	= parseFloat(eval(thisVal).value.split(",").join(""));
								document.getElementById('INV_LISTTAXVAL').value 	= INV_PPNVAL;
								document.getElementById('INV_LISTTAXVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_PPNVAL)), 2));
							}
							
							function getAmountPPh(thisVal)
							{
								
								INV_PPHVAL	= parseFloat(eval(thisVal).value.split(",").join(""));
								document.getElementById('INV_PPHVAL').value 	= INV_PPHVAL;
								document.getElementById('INV_PPHVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_PPHVAL)), 2));
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $DPCode; ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-btn">
										<button type="button" class="btn btn-primary"><?php echo $Search; ?></button>
                                    </div>
                                    <input type="text" class="form-control" name="DP_NUM" id="DP_NUM" style="max-width:160px" value="<?php echo $DP_NUM; ?>" onClick="selectIR();">
                                    <?php
										$collID			= "$PRJCODE~$SPLCODE1";
										$url_popLPM		= site_url('c_purchase/c_pi180c23/popupall_DP/?id='.$this->url_encryption_helper->encode_url($collID));
									?>       
									<script>
										var urlLPM 	= "<?php echo $url_popLPM;?>";
										function selectIR()
										{
											SPLCODE	= document.getElementById('SPLCODE').value;
											if(SPLCODE == 'none')
											{
												alert('Please select a Supplier');
												document.getElementById('SPLCODE').focus();
												return false;
											}
											title = 'Select Item Receive';
											w = 780;
											h = 550;
											
											var left = (screen.width/2)-(w/2);
											var top = (screen.height/2)-(h/2);
											//return window.open(urlLPM+VC, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
											return window.open(urlLPM, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
									</script>
                                </div>
                            </div>
                        </div>
                        <?php
							// DP REMAIN
							$DP_AMOUNT_REM	= $DP_AMOUNT;
							$DP_AMOUNT_USED	= 0;
							$selDP			= "tbl_dp_header WHERE DP_NUM = '$DP_NUM'";
							$resDP			= $this->db->count_all($selDP);
							if($resDP > 0)
							{
								$selDP1	= "SELECT DP_AMOUNT, DP_AMOUNT_USED FROM tbl_dp_header WHERE DP_NUM = '$DP_NUM'";
								$resDP1	= $this->db->query($selDP1)->result();
								foreach($resDP1 as $rowDP1):
									$DP_AMOUNT1		= $rowDP1->DP_AMOUNT;
									$DP_AMOUNT_USED	= $rowDP1->DP_AMOUNT_USED;
								endforeach;
								$DP_AMOUNT_REM	= $DP_AMOUNT1 - $DP_AMOUNT_USED;
							}
						?>
                        <div class="form-group">
                       	  <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:160px; text-align:right" name="DP_AMOUNT1" id="DP_AMOUNT1" value="<?php echo number_format($DP_AMOUNT_REM, 2); ?>" onBlur="getDPAmount(this);" >
                                <input type="hidden" class="form-control" style="max-width:160px; text-align:right" name="DP_AMOUNT" id="DP_AMOUNT" value="<?php echo $DP_AMOUNT_REM; ?>" >
                          	</div>
                        </div>
                        <script>
							function getDPAmount(thisVal)
							{
								var decFormat		= document.getElementById('decFormat').value;
								
								DP_AMOUNT		= document.getElementById('DP_AMOUNT').value;
								DP_AMOUNT_REM	= parseFloat(eval(thisVal).value.split(",").join(""));
								if(DP_AMOUNT_REM > DP_AMOUNT)
								{
									alert('<?php echo $alert2; ?>');
									document.getElementById("DP_AMOUNT1").value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DP_AMOUNT)),decFormat));
									document.getElementById("DP_AMOUNT").value 	= parseFloat(Math.abs(DP_AMOUNT));
								}
								else
								{
									document.getElementById("DP_AMOUNT1").value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DP_AMOUNT_REM)),decFormat));
									document.getElementById("DP_AMOUNT").value 	= parseFloat(Math.abs(DP_AMOUNT_REM));
								}
							}
						</script>
                        <div class="form-group" >
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?> </label>
                          	<div class="col-sm-10">
                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $INV_STAT; ?>">
                                <select name="INV_STAT" id="INV_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
                                	<option value="1" <?php if($INV_STAT == 1) { ?> selected <?php } ?>>New</option>
                                	<option value="2" <?php if($INV_STAT == 2) { ?> selected <?php } ?> style="display:none">Confirm</option>
                                    <?php if($ISAPPROVE == 1) { ?>
                                	<option value="3" <?php if($INV_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                    <?php } ?>
                                    <?php if($INV_STAT == 3) { ?>
                                	<option value="6" <?php if($INV_STAT == 6) { ?> selected <?php } ?>>Close</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
						<script>
                            function selStat(thisValue)
                            {
                                if(thisValue == 6)
                                {
                                    document.getElementById('tblClose').style.display 	= '';
                                    document.getElementById('tblReason').style.display 	= '';
                                }
                                else
                                {
                                    document.getElementById('tblClose').style.display 	= 'none';
                                    document.getElementById('tblReason').style.display 	= 'none';
                                }
                            }
                        </script>
                        <?php
							$sqlIRHeaderC	= "tbl_ttk_detail A
												INNER JOIN tbl_ir_header B ON A.TTK_REF1 = B.IR_NUM
													AND B.PRJCODE = '$PRJCODE'
												WHERE TTK_NUM = '$TTK_NUM1' AND PRJCODE = '$PRJCODE'";
							$resIRHeaderC	= $this->db->count_all($sqlIRHeaderC);
							$sqlIRHeader	= "SELECT A.TTK_NUM, A.TTK_REF1, A.TTK_REF1_DATE, A.TTK_REF1_DATED, 
															A.TTK_REF1_AM, A.TTK_REF1_PPN, A.TTK_REF2, A.TTK_REF2_DATE,
															A.TTK_DESC
														FROM tbl_ttk_detail A
															INNER JOIN tbl_ttk_header C ON C.TTK_NUM = A.TTK_NUM
																AND C.PRJCODE = '$PRJCODE'
														WHERE A.TTK_NUM = '$TTK_NUM1'";
									$resIRHeader 	= $this->db->query($sqlIRHeader)->result();
						?>
                        <div class="row">
                            <div class="col-md-12">
                              <div class="box box-danger collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $IRList; ?></h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                    <table width="100%" border="1" id="tbl">
                                    <tr style="background:#CCCCCC">
                                        <td width="3%" height="25" style="text-align:center">No.</td>
                                        <td width="5%" style="text-align:center" nowrap><?php echo $ReceiptNumber; ?></td>
                                        <td width="5%" style="text-align:center; display:none" nowrap><?php echo $ReceiptCode; ?></td>
                                        <td width="2%" style="text-align:center" nowrap><?php echo $ReceiptDate; ?></td>
                                        <td width="5%" style="text-align:center" nowrap><?php echo $ReferenceNumber; ?></td>
                                        <td width="50%" style="text-align:center" nowrap><?php echo $Description; ?></td>
                                        <td width="9%" style="text-align:center" nowrap><?php echo $AmountReceipt; ?></td>
                                        <td width="8%" style="text-align:center" nowrap>PPn</td>
                                        <td width="13%" style="text-align:center" nowrap><?php echo $TotAmount; ?></td>
                                    </tr>
									<?php
									$TOT_AMOUNT2	= 0;
									$resIRHeaderC	= 0;
									
									$refNumber 		= $TTK_NUM1;						
									// count data
									$sqlIRHeaderC	= "tbl_ttk_detail A
															INNER JOIN tbl_ttk_header C ON C.TTK_NUM = A.TTK_NUM
																AND C.PRJCODE = '$PRJCODE'
														WHERE A.TTK_NUM = '$refNumber'";
									$resIRHeaderC	= $this->db->count_all($sqlIRHeaderC);
									// End count data
									
									// 1. Ambil detail IR
									$sqlIRHeader	= "SELECT A.TTK_NUM, A.TTK_REF1, A.TTK_REF1_DATE, A.TTK_REF1_DATED, 
															A.TTK_REF1_AM, A.TTK_REF1_PPN, A.TTK_REF2, A.TTK_REF2_DATE,
															A.TTK_DESC, C.TTK_CATEG
														FROM tbl_ttk_detail A
															INNER JOIN tbl_ttk_header C ON C.TTK_NUM = A.TTK_NUM
																AND C.PRJCODE = '$PRJCODE'
														WHERE A.TTK_NUM = '$refNumber'";
									$resIRHeader 	= $this->db->query($sqlIRHeader)->result();
									
									$collIR			= '';
									$TTK_CATEG		= 'IR';		
									if($resIRHeaderC > 0)
									{
										$TOT_AMOUNT1	= 0;
										$TOT_AMOUNT2	= 0;
										$TOT_DISC		= 0;
										$TOT_TAX1		= 0;
										$TOT_TAX2		= 0;
										$collIR			= '';
										foreach($resIRHeader as $row) :
											$currentRow  	= ++$i;											
											$TTK_REF1 		= $row->TTK_REF1;
											$TTK_REF1_DATE	= $row->TTK_REF1_DATE;
											$TTK_REF1_AM	= $row->TTK_REF1_AM;
											$TTK_REF1_PPN	= $row->TTK_REF1_PPN;
											$TTK_TOTAL_AM	= $TTK_REF1_AM + $TTK_REF1_PPN;
											$TTK_REF2 		= $row->TTK_REF2;
											$TTK_REF2_DATE	= $row->TTK_REF2_DATE;
											$TTK_DESC 		= $row->TTK_DESC;
											$TTK_CATEG		= $row->TTK_CATEG;
											
											if($currentRow == 1)
											{
												$collIR		= $TTK_REF1;
											}
											else
											{
												$collIR		= "$collIR','$TTK_REF1";
											}
											?>
												<tr>
													<td width="3%" height="25" style="text-align:left">
														<?php echo $currentRow; ?>.
													</td>
													<td width="5%" style="text-align:left" nowrap>
														<?php print $TTK_REF1; ?>
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>INV_NUM" name="data1[<?php echo $currentRow; ?>][INV_NUM]" value="<?php print $INV_NUM; ?>">
                                                       <input type="hidden" id="data1<?php echo $currentRow; ?>INV_CODE" name="data1[<?php echo $currentRow; ?>][INV_CODE]" value="<?php print $INV_CODE; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>IR_NUM" name="data1[<?php echo $currentRow; ?>][IR_NUM]" value="<?php print $TTK_REF1; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>PRJCODE" name="data1[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>">
														<!-- IR_NUM -->
													</td>
													<td width="5%" style="text-align:left; display:none" nowrap>
														<?php //print date('d-m-Y', strtotime($TTK_REF1_DATE)); ?>
														<!-- IR_CODE -->
													</td>
													<td width="2%" style="text-align:center" nowrap>
														<?php print date('d-m-Y', strtotime($TTK_REF1_DATE)); ?>
														<!-- PO_NUM -->
													</td>
													<td width="5%" style="text-align:center" nowrap>
														<?php echo $TTK_REF2; ?>
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>REF_NUM" name="data1[<?php echo $currentRow; ?>][REF_NUM]" value="<?php print $TTK_REF2; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_CODE" name="data1[<?php echo $currentRow; ?>][ITM_CODE]" value="">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ACC_ID" name="data1[<?php echo $currentRow; ?>][ACC_ID]" value="">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_QTY" name="data1[<?php echo $currentRow; ?>][ITM_QTY]" value="1">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_QTY" name="data1[<?php echo $currentRow; ?>][ITM_QTY]" value="1">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_UNIT" name="data1[<?php echo $currentRow; ?>][ITM_UNIT]" value="LS">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_UNITP" name="data1[<?php echo $currentRow; ?>][ITM_UNITP]" value="<?php print $TTK_REF1_AM; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_DISC" name="data1[<?php echo $currentRow; ?>][ITM_DISC]" value="0">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_UNITP_BASE" name="data1[<?php echo $currentRow; ?>][ITM_UNITP_BASE]" value="<?php print $TTK_REF1_AM; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_UNITP_BASE" name="data1[<?php echo $currentRow; ?>][ITM_UNITP_BASE]" value="<?php print $TTK_REF1_AM; ?>">
														<!-- PO_NUM -->
													</td>
													<td>
														<?php print $TTK_DESC; ?>
														<!-- IR_NOTE -->
													</td>
												  <td style="text-align:right">
														<?php print number_format($TTK_REF1_AM, $decFormat); ?>&nbsp;
												  		<input type="hidden" id="TTK_REF1_AM<?php echo $currentRow; ?>" name="TTK_REF1_AM<?php echo $currentRow; ?>" value="<?php print $TTK_REF1_AM; ?>">
                                                    	<input type="hidden" id="data1<?php echo $currentRow; ?>ITM_AMOUNT" name="data1[<?php echo $currentRow; ?>][ITM_AMOUNT]" value="<?php print $TTK_REF1_AM; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_AMOUNT_BASE" name="data1[<?php echo $currentRow; ?>][ITM_AMOUNT_BASE]" value="<?php print $TTK_REF1_AM; ?>"></td>
													<td style="text-align:right">
                                                    	<?php
															$TAXCODE1			= '';
															$TAX_AMOUNT_PPn1	= 0;
															if($TTK_REF1_PPN > 0)
															{
																$TAXCODE1			= 'TAX01';
																$TAX_AMOUNT_PPn1	= $TTK_REF1_PPN;
															}
                                                        ?>
														<?php print number_format($TTK_REF1_PPN, $decFormat); ?>&nbsp;
													  	<input type="hidden" id="TTK_REF1_PPN<?php echo $currentRow; ?>" name="TTK_REF1_PPN<?php echo $currentRow; ?>" value="<?php print $TTK_REF1_PPN; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>TAXCODE1" name="data1[<?php echo $currentRow; ?>][TAXCODE1]" value="<?php print $TAXCODE1; ?>">
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>TAX_AMOUNT_PPn1" name="data1[<?php echo $currentRow; ?>][TAX_AMOUNT_PPn1]" value="<?php print $TAX_AMOUNT_PPn1; ?>">
                                                    </td>
													<td style="text-align:right">
														<?php print number_format($TTK_TOTAL_AM, $decFormat); ?>&nbsp;
                                                        <input type="hidden" id="data1<?php echo $currentRow; ?>ITM_AMOUNT1" name="data1[<?php echo $currentRow; ?>][ITM_AMOUNT1]" value="<?php print $TTK_TOTAL_AM; ?>">
                                                    </td>
												</tr>
											<?php
										endforeach;
									}
									$collIR = "'$collIR'";
                                    ?>   
                                    <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                </table>
                                    </div>
                              	</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                              <div class="box box-primary">
                                <br>
                               	  <table width="100%" border="1" id="tbl">
                                    <tr style="background:#CCCCCC">
                                        <td width="3%" height="25" style="text-align:center">No.</td>
                                        <td width="2%" style="text-align:center" nowrap><?php echo $ItemCode; ?></td>
                                        <td width="46%" style="text-align:center" nowrap><?php echo $ItemName; ?></td>
                                        <td width="8%" style="text-align:center" nowrap><?php echo $ReceiptQty; ?></td>
                                        <td width="2%" style="text-align:center" nowrap><?php echo $Unit; ?></td>
                                        <td width="11%" style="text-align:center" nowrap><?php echo $Price; ?></td>
                                        <td width="4%" style="text-align:center; display:none" nowrap><?php echo $Discount; ?> <br>(%)</td>
                                        <td width="4%" style="text-align:center; display:none" nowrap><?php echo $Discount; ?></td>
                                        <td width="9%" style="text-align:center; display:none" nowrap><?php echo $Tax; ?> 1</td>
                                        <td width="9%" style="text-align:center; display:none" nowrap><?php echo $Tax; ?> 2</td>
                                        <td width="2%" style="text-align:center; display:none" nowrap><?php echo $Total; ?></td>
                                    </tr>
                                    <input type="hidden" name="INV_CATEG" id="INV_CATEG" value="<?php echo $TTK_CATEG; ?>">
									<?php
									$TOT_AMOUNT2	= 0;
									$CountsqlItem	= 0;
									
									if($TTK_CATEG == 'IR')
									{
										$sqlIRItemc		= "tbl_ir_detail A
																INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
																	AND B.PRJCODE = '$PRJCODE'
																INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																	AND C.PRJCODE = '$PRJCODE'
															WHERE A.IR_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'";
										$CountsqlItem 	= $this->db->count_all($sqlIRItemc);
										// End count data
										
										// 1. Ambil detail IR
										$sqlIRItem		= "SELECT B.IR_NUM, B.IR_CODE, B.IR_DATE, B.IR_DUEDATE, B.PO_NUM, 
																A.ITM_CODE, A.ITM_UNIT,
																SUM(A.ITM_QTY) AS TOTQTY,
																A.ITM_PRICE, A.ITM_DISP,
																SUM(A.ITM_DISC) AS TOTDISC,
																A.TAXCODE1, A.ITM_TOTAL,
																C.ITM_NAME, C.ACC_ID
															FROM tbl_ir_detail A
																INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
																	AND B.PRJCODE = '$PRJCODE'
																INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																	AND C.PRJCODE = '$PRJCODE'
															WHERE A.IR_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'
															GROUP BY A.ITM_CODE";
										$ressqlIRItem 		= $this->db->query($sqlIRItem)->result();
									}
									else if($TTK_CATEG == 'OPN')
									{
										$sqlIRItemc		= "tbl_opn_detail A
																INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																	AND B.PRJCODE = '$PRJCODE'
																INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																	AND C.PRJCODE = '$PRJCODE'
															WHERE A.OPNH_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'";
										$CountsqlItem 	= $this->db->count_all($sqlIRItemc);
										// End count data
										
										// 1. Ambil detail IR
										$sqlIRItem		= "SELECT B.OPNH_NUM AS IR_NUM, B.OPNH_CODE AS IR_CODE,
																B.OPNH_DATE AS IR_DATE, '' AS IR_DUEDATE, B.WO_NUM AS PO_NUM, 
																A.ITM_CODE, A.ITM_UNIT,
																SUM(A.OPND_VOLM) AS TOTQTY,
																A.OPND_ITMPRICE AS ITM_PRICE, 0 AS ITM_DISP,
																0 AS TOTDISC,
																A.TAXCODE1, A.OPND_ITMTOTAL AS ITM_TOTAL,
																C.ITM_NAME, C.ACC_ID
															FROM tbl_opn_detail A
																INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																	AND B.PRJCODE = '$PRJCODE'
																INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
																	AND C.PRJCODE = '$PRJCODE'
															WHERE A.OPNH_NUM IN ($collIR) AND B.PRJCODE = '$PRJCODE'
															GROUP BY A.ITM_CODE";
										$ressqlIRItem 		= $this->db->query($sqlIRItem)->result();
									}
									$totalAmount		= 0;
									$totalDiscAmount 	= 0;
									$totTaxPPnAmount	= 0;
									$totTaxPPhAmount	= 0;
									if($CountsqlItem > 0)
									{												
										$TOT_AMOUNT1	= 0;
										$TOT_AMOUNT2	= 0;
										$TOT_DISC		= 0;
										$TOT_TAX1		= 0;
										$TOT_TAX2		= 0;
                                    	$currentRow		= 0;
										$i				= 0;
										foreach($ressqlIRItem as $row) :
											$currentRow  	= ++$i;
											$IR_NUM 		= $row->IR_NUM;
											$PO_NUM			= $row->PO_NUM;
											$ITM_CODE 		= $row->ITM_CODE;
											$ITM_UNIT 		= $row->ITM_UNIT;
											$ITM_NAME 		= $row->ITM_NAME;
											$ACC_ID 		= $row->ACC_ID;
											$ITM_QTY		= $row->TOTQTY;
											$ITM_UNITP 		= $row->ITM_PRICE;
											$ITM_DISP 		= $row->ITM_DISP;
											$ITM_DISC 		= $row->TOTDISC;
											if($ITM_DISC == '')
												$ITM_DISC	= 0;
											$TAXCODE1 		= $row->TAXCODE1;
											$ITM_TOTAL 		= $row->ITM_TOTAL;
											$itemConvertion = 1;
											$PRJCODE 		= $PRJCODE;
											$RRSource 		= 'PO';
											$Ref_Number 	= $PO_NUM; // No PO apabila referensi IR ber resources PO
											$Disc_percentage= 0;
											$Disc_UnitPrice = 0;
											$Tax_Code1 		= '';
											$Tax_Code2 		= '';
											
											// GET ITEM AMOUNT AFTER DISCOUNT
											//$ITM_AMOUNT1	= $ITM_TOTAL - $ITM_DISC;
											$ITM_AMOUNT1	= ($ITM_QTY * $ITM_UNITP) - $ITM_DISC;
											
											// GET ITEM AMOUNT AFTER TAX											
											if($TAXCODE1 == 'TAX01')
											{
												$Tax_AmountPPn1	= $ITM_AMOUNT1 * 10 / 100;
												$Tax_AmountPPh1 = 0;
											}
											else if($TAXCODE1 == 'TAX02')
											{
												$Tax_AmountPPn1	= 0;
												$Tax_AmountPPh1 = (-1) * $ITM_AMOUNT1 * 3 / 100;
											}
											else
											{
												$Tax_AmountPPn1	= 0;
												$Tax_AmountPPh1 = 0;
											}
											$ITM_AMOUNT2		= $ITM_AMOUNT1 + $Tax_AmountPPn1 - $Tax_AmountPPh1;
											$ITM_TOT_AMOUNT		= $ITM_AMOUNT2;
											
											$TOT_AMOUNT1		= $TOT_AMOUNT1 + $ITM_AMOUNT1;	// Before +- Tax
											$TOT_AMOUNT2		= $TOT_AMOUNT2 + $ITM_AMOUNT2;	// After +- Tax
											$TOT_DISC			= $TOT_DISC + $ITM_DISC;
											$TOT_TAX2			= $TOT_TAX2 + $Tax_AmountPPh1;
											$TOT_TAX1			= $TOT_TAX1 + $Tax_AmountPPn1;
											$TOT_TAX2			= $TOT_TAX2 + $Tax_AmountPPh1;
											
											$ITM_CONV			= 1;	// Default
											
											// Deafult
											$Tax_AmountPPn2		= 0;
											$Tax_AmountPPh2		= 0;
											?>
											  <tr>
												  <td width="3%" height="25" style="text-align:left">
													  <?php echo $currentRow; ?>.
												  </td>
												  <td width="2%" style="text-align:left">
													  <input type="hidden" id="data[<?php echo $currentRow; ?>][INV_NUM]" name="data[<?php echo $currentRow; ?>][INV_NUM]" value="<?php print $INV_NUM; ?>">
													<input type="hidden" id="data[<?php echo $currentRow; ?>][INV_CODE]" name="data[<?php echo $currentRow; ?>][INV_CODE]" value="<?php print $INV_CODE; ?>">
													<input type="hidden" id="data[<?php echo $currentRow; ?>][IR_NUM]" name="data[<?php echo $currentRow; ?>][IR_NUM]" value="<?php print $IR_NUM; ?>">
													<input type="hidden" id="data[<?php echo $currentRow; ?>][REF_NUM]" name="data[<?php echo $currentRow; ?>][REF_NUM]" value="<?php print $PO_NUM; ?>">
													<input type="hidden" id="data[<?php echo $currentRow; ?>][PRJCODE]" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>">
													  <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php print $ITM_CODE; ?>">
													  <input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php print $ACC_ID; ?>">
													  <?php print $ITM_CODE; ?>
													  <!-- ITM_CODE -->
												  </td>
												  <td width="46%" style="text-align:left">
													  <?php print $ITM_NAME; ?>
													  <!-- ITM_NAME -->
												  </td>
												  <td width="8%" style="text-align:right">
													  <input type="text" class="form-control" style="text-align:right; max-width:120px" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" size="15" disabled>
													  <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="data<?php echo $currentRow; ?>ITM_QTY" size="6" value="<?php print $ITM_QTY; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" >
													  <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_QTY2]" id="data<?php echo $currentRow; ?>ITM_QTY2" size="6" value="<?php print $ITM_QTY; ?>" class="textbox" onKeyPress="return isIntOnlyNew(event);" >
													  <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_CONV]" id="data<?php echo $currentRow; ?>ITM_CONV" size="6" value="<?php print $ITM_CONV; ?>" >
													  <!-- ITM_QTY, ITM_QTY2, ITM_CONV -->
												  </td>
												  <td width="2%" style="text-align:center">
													  <?php print $ITM_UNIT; ?>
													  <input type="hidden" id="data[<?php echo $currentRow; ?>][ITM_UNIT]" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
													  <!-- ITM_UNIT -->
												  </td>
												  <td width="11%" style="text-align:right">
													  <input type="text" class="form-control"  style="text-align:right; min-width:100px; max-width:200px" name="ITM_UNITPX<?php echo $currentRow; ?>" id="ITM_UNITPX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_UNITP, $decFormat); ?>" size="20" disabled >
													  <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNITP" name="data[<?php echo $currentRow; ?>][ITM_UNITP]" value="<?php print $ITM_UNITP; ?>">
													  <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNITP_BASE" name="data[<?php echo $currentRow; ?>][ITM_UNITP_BASE]" value="<?php print $ITM_UNITP; ?>">
													  <!-- ITM_UNITP, ITM_UNITP_BASE -->
												  </td>
												  <td width="4%" style="text-align:right; display:none" nowrap>
													<input type="hidden" style="text-align:right; min-width:70px; max-width:150px" name="ITM_DISP<?php echo $currentRow; ?>" id="ITM_DISP<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISP, $decFormat); ?>" class="form-control" onBlur="countDisp(this, <?php echo $currentRow; ?>);" />
													  <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISP]" id="data<?php echo $currentRow; ?>ITM_DISP" value="<?php print $ITM_DISP; ?>" />
													  <!-- ITM_DISP -->
												  </td>
												  <td width="4%" style="text-align:right; display:none" nowrap>
													  <input type="hidden" style="text-align:right; max-width:150px" name="ITM_DISCX<?php echo $currentRow; ?>" id="ITM_DISCX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISC, $decFormat); ?>" class="form-control" onBlur="countDisc(this, <?php echo $currentRow; ?>);" />
													  <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISC]" id="data<?php echo $currentRow; ?>ITM_DISC" value="<?php print $ITM_DISC; ?>" />
													  <!-- ITM_DISC -->
												</td>
												  <td width="9%" style="text-align:center; display:none">
													<select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="data<?php echo $currentRow; ?>TAXCODE1" onChange="changeValue(this, <?php echo $currentRow; ?>)" class="form-control" style="max-width:120px">
														  <option value="">--- None --</option>
														  <option value="TAX01"<?php if($TAXCODE1 == 'TAX01') { ; ?> selected <?php } ?>>PPn 10%</option>
														  <option value="TAX02"<?php if($TAXCODE1 == 'TAX02') { ; ?> selected <?php } ?>>PPh</option>
													  </select>
													  <input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPn1]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPn1"value="<?php print $Tax_AmountPPn1; ?>" />
													  <input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPh1]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPh1"value="<?php print $Tax_AmountPPh1; ?>" />
													  <!-- TAXCODE1 -->
												  </td>
												  <td width="9%" style="text-align:center; display:none">
													  <select name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="data<?php echo $currentRow; ?>TAXCODE2" onChange="calcTax2(this.value)" class="listmenu">
														  <option value="">--- None --</option>
														  <option value="TAX01"<?php if($TAXCODE2 == 'TAX01') { ; ?> selected <?php } ?>>PPn 10%</option>
														  <option value="TAX02"<?php if($TAXCODE2 == 'TAX02') { ; ?> selected <?php } ?>>PPh</option>
													  </select>
													  <input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPn2]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPn2"value="<?php print $Tax_AmountPPn2; ?>" />
													  <input type="hidden" name="data[<?php echo $currentRow; ?>][TAX_AMOUNT_PPh2]" id="data<?php echo $currentRow; ?>TAX_AMOUNT_PPh1"value="<?php print $Tax_AmountPPh2; ?>" />
													  <!-- TAXCODE2 -->
												  </td>
												  <td width="2%" style="text-align:right; display:none">
													<input type="hidden" class="form-control" style="text-align:right;max-width:150px" name="ITM_AMOUNTX<?php echo $currentRow; ?>" id="ITM_AMOUNTX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_TOT_AMOUNT, $decFormat); ?>" />
													  <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_AMOUNT]" id="data<?php echo $currentRow; ?>ITM_AMOUNT" value="<?php print $ITM_AMOUNT1; ?>" />
													  <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_AMOUNT_BASE]" id="data<?php echo $currentRow; ?>ITM_AMOUNT_BASE" value="<?php print $ITM_AMOUNT1; ?>" />
													  <!-- ITM_AMOUNT, ITM_AMOUNT_BASE -->
												  </td>
											  </tr>
										  <?php
										endforeach;
									}
                                    
                                    /*if($task == 'add')
                                    {*/
                                        ?>
                                          <input type="hidden" name="PO_NUM" id="PO_NUM" value="<?php echo $PO_NUM; ?>">
                                      <?php
                                    /*}*/
                                    ?>                                    
                                </table>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            	<?php
									if($task=='add')
									{
										if($INV_STAT == 1)
										{
											?>
												<button class="btn btn-primary">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
												</button>&nbsp;
											<?php
										}
									}
									else
									{
										if($INV_STAT == 3)
										{
											?>
												<button class="btn btn-primary" style="display:none" id="tblClose">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
										if($INV_STAT == 1)
										{
											?>
												<button class="btn btn-primary">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
									}
									$backURL	= site_url('c_purchase/c_pi180c23/gall180c23inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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

	function add_DP(strItem) 
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		arrItem 	= strItem.split('~');
		DP_NUM 		= arrItem[0];
		DP_AMOUNT	= arrItem[1];
		document.getElementById("DP_NUM").value 	= DP_NUM;
		document.getElementById("DP_AMOUNT1").value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DP_AMOUNT)),decFormat));
		document.getElementById("DP_AMOUNT").value 	= parseFloat(Math.abs(DP_AMOUNT));
	}

	function add_header(IR_NUM) 
	{
		document.getElementById("TTK_NUM1").value = IR_NUM;
		document.frmsrch.submitSrch.click();
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISP		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('ITM_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISP)),decFormat));
		document.getElementById('data'+row+'ITM_DISP').value 	= parseFloat(Math.abs(ITM_DISP));
		
		var ITM_QTY			= document.getElementById('ITM_QTY'+row).value;
		var ITM_UNITP		= document.getElementById('data'+row+'ITM_UNITP').value;
		var ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_UNITP);
		var DISCOUNT		= parseFloat(ITM_DISP * ITM_TOTAL / 100);
		
		document.getElementById('data'+row+'ITM_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISC		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		var ITM_QTY			= document.getElementById('ITM_QTY'+row).value;
		var ITM_UNITP		= document.getElementById('data'+row+'ITM_UNITP').value;
		var ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_UNITP);
		
		var DISCOUNTP		= parseFloat(ITM_DISC / ITM_TOTAL * 100);
		
		document.getElementById('ITM_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		document.getElementById('data'+row+'ITM_DISP').value 	= parseFloat(Math.abs(DISCOUNTP));
		
		document.getElementById('data'+row+'ITM_DISC').value 	= parseFloat(Math.abs(ITM_DISC));
		document.getElementById('ITM_DISCX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function changeValue(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		//var ITM_QTYx 		= eval(thisVal).value.split(",").join("");
		ITM_QTY1			= document.getElementById('ITM_QTY'+row);
		ITM_QTY 			= parseFloat(eval(ITM_QTY1).value.split(",").join(""));
		document.getElementById('data'+row+'ITM_QTY').value = parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTY'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		//var ITM_DISP			= document.getElementById('ITM_DISP'+row).value;
		var ITM_QTY				= document.getElementById('ITM_QTY'+row).value;
		var ITM_UNITP			= document.getElementById('data'+row+'ITM_UNITP').value;
		var ITM_TOTAL			= parseFloat(ITM_QTY) * parseFloat(ITM_UNITP);
		
		var DISCOUNT			= parseFloat(document.getElementById('data'+row+'ITM_DISC').value);
		var TOT_ITMTEMP			= parseFloat(ITM_TOTAL - DISCOUNT);
		
		var theTAX				= document.getElementById('data'+row+'TAXCODE1').value;
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.1;
			G_itmTot	= parseFloat(TOT_ITMTEMP) + parseFloat(itmTax);
			document.getElementById('data'+row+'TAX_AMOUNT_PPn1').value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+row+'TAX_AMOUNT_PPh1').value 	= 0;
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.03;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+row+'TAX_AMOUNT_PPh1').value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+row+'TAX_AMOUNT_PPn1').value 	= 0;
		}
		else
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+row+'TAX_AMOUNT_PPn1').value 	= 0;
			document.getElementById('data'+row+'TAX_AMOUNT_PPh1').value 	= 0;
		}
		document.getElementById('data'+row+'ITM_AMOUNT').value 		= parseFloat(Math.abs(G_itmTot));
		document.getElementById('data'+row+'ITM_AMOUNT_BASE').value = parseFloat(Math.abs(G_itmTot));
		document.getElementById('ITM_AMOUNTX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat));
		
		totalrow		= document.getElementById("totalrow").value;	
		INV_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			GT_ITMPRICE		= document.getElementById('data'+i+'ITM_AMOUNT').value;
			INV_TOTAL_AM	= parseFloat(INV_TOTAL_AM) + parseFloat(GT_ITMPRICE);
		}
		
		document.getElementById('INV_AMOUNT').value = INV_TOTAL_AM;
	}
	
	function checkTotalTTK()
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		totalrow		= document.getElementById('totalrow').value;
		TTK_TOT_AM		= 0;
		TTK_TOT_PPN		= 0;
		TTK_GTOTAL		= 0;
		for (i = 1; i <= totalrow; i++) 
		{
			TTK_REF1_AM		= document.getElementById('TTK_REF1_AM'+i).value;
			TTK_REF1_PPN	= document.getElementById('TTK_REF1_PPN'+i).value;
			TTK_TOT_AM		= parseFloat(TTK_TOT_AM) + parseFloat(TTK_REF1_AM);
			TTK_TOT_PPN		= parseFloat(TTK_TOT_PPN) + parseFloat(TTK_REF1_PPN);
		}
		TTK_GTOTAL	= parseFloat(TTK_TOT_AM) + parseFloat(TTK_TOT_PPN);
		document.getElementById('INV_AMOUNT').value 	= TTK_TOT_AM;
		document.getElementById('INV_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_TOT_AM)),decFormat));
		
		document.getElementById('INV_LISTTAXVAL').value 	= TTK_TOT_PPN;
		document.getElementById('INV_LISTTAXVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_TOT_PPN)),decFormat));
	}
	
	function checkInp()
	{
		INV_STAT		= document.getElementById("INV_STAT").value;
		if(INV_STAT == 6)
		{
			INV_NOTES1		= document.getElementById('INV_NOTES1').value;
			if(INV_NOTES1 == '')
			{
				alert('<?php echo $alert3; ?>');
				document.getElementById('INV_NOTES1').focus();
				return false;
			}
		}
		
		chkTotal	= document.getElementById('chkTotal').checked;
		if(chkTotal == false)
		{
			alert('<?php echo $alert1; ?>');
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