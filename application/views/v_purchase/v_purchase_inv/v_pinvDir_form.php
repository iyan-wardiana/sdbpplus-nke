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
	$INV_TYPE 			= 1;
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
	$REF_NOTES			= '';
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
	$RRSource			= 'OTH';
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
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$year$pattMonth$pattDate";
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
	$DocNumber 	= "$PRJCODE.$Pattern_Code$groupPattern-$lastPatternNumb-D";
	$INV_NUM	= $DocNumber;
	$INV_CODE	= $lastPatternNumb;
	
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
	$INV_CURRENCY		= $default['INV_CURRENCY'];
	$INV_TAXCURR	 	= $default['INV_TAXCURR'];
	$INV_AMOUNT 		= $default['INV_AMOUNT'];
	$INV_AMOUNT_BASE	= $default['INV_AMOUNT_BASE'];
	$INV_LISTTAX		= $default['INV_LISTTAX'];
	$INV_LISTTAXVAL		= $default['INV_LISTTAXVAL'];
	$INV_TERM 			= $default['INV_TERM'];
	$INV_STAT 			= $default['INV_STAT'];
	$INV_PAYSTAT 		= $default['INV_PAYSTAT'];
	$COMPANY_ID 		= $default['COMPANY_ID'];
	$VENDINV_NUM		= $default['VENDINV_NUM'];
	$INV_NOTES 			= $default['INV_NOTES'];
	$REF_NOTES			= $default['REF_NOTES'];
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
		if($TranslCode == 'AddInvoice')$AddInvoice = $LangTransl;
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
		if($TranslCode == 'ReceiptNumber')$ReceiptNumber = $LangTransl;
		if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
		if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
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
		$sqlTTK1 	= "SELECT TTK_NOTES, TTK_AMOUNT, TTK_AMOUNT_PPN FROM tbl_ttk_header WHERE TTK_NUM = '$TTK_NUM1' LIMIT 1";
		$resTTK1 	= $this->db->query($sqlTTK1)->result();	
		foreach($resTTK1 as $rowTK1) :
			$REF_NOTES 		= $rowTK1->TTK_NOTES;
			$INV_AMOUNT 	= $rowTK1->TTK_AMOUNT;
			$INV_LISTTAXVAL = $rowTK1->TTK_AMOUNT_PPN;
		endforeach;
	}
	
	$sqlPRJ1 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
	$restPRJ1 	= $this->db->query($sqlPRJ1)->result();	
	foreach($restPRJ1 as $rowPRJ1) :
		$PRJNAME1 	= $rowPRJ1->PRJNAME;
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $AddInvoice; ?> (Direct)
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
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $InvoiceNumber; ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:195px" name="INV_NUMx" id="INV_NUMx" value="<?php echo $INV_NUM; ?>" disabled >
                        		<input type="hidden" class="textbox" name="INV_NUM" id="INV_NUM" size="30" value="<?php echo $INV_NUM; ?>" />
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:195px" name="INV_CODE" id="INV_CODE" value="<?php echo $INV_CODE; ?>" >
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
                                        <option value="DIR" <?php if($INV_TYPE == '1') { ?> selected <?php } ?>>Direct</option>    
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
                        <div class="row">
                            <div class="col-md-12">
                              <div class="box box-danger collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $RefNumber; ?></h3>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $RefNumber; ?></label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-primary"><?php echo $Search; ?></button>
                                                </div>
                                                <input type="text" class="form-control" name="Ref_Number" id="Ref_Number" style="max-width:180px" value="<?php echo $TTK_NUM1; ?>" onClick="selectIR();">
                                                <?php
                                                    $collID			= "$PRJCODE~$SPLCODE1";
                                                    $url_popLPM		= site_url('c_purchase/c_pi180c23/popupall_TTKdir/?id='.$this->url_encryption_helper->encode_url($collID));
                                                ?>       
                                                <script>
                                                    var urlLPM 	= "<?php echo $url_popLPM;?>";
                                                    function selectIR()
                                                    {
                                                        SPLCODE	= document.getElementById('SPLCODE').value;
                                                        if(SPLCODE == 'none')
                                                        {
                                                            swal('Please select a Supplier');
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
                                    <div class="form-group">
                                      <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?> (TTK)</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="REF_NOTES"  id="REF_NOTES" style="max-width:350px;height:70px" readonly><?php echo $REF_NOTES; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $TotAmount; ?> (TTK)</label>
                                        <div class="col-sm-10">
                                            <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNT" id="INV_AMOUNT" value="<?php echo $INV_AMOUNT; ?>" >
                                            <input type="text" class="form-control" style="max-width:120px; text-align:right" name="INV_AMOUNTX" id="INV_AMOUNTX" value="<?php echo number_format($INV_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">PPn</label>
                                        <div class="col-sm-10">
                                            <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="INV_LISTTAXVAL" id="INV_LISTTAXVAL" value="<?php echo $INV_LISTTAXVAL; ?>" >
                                            <input type="text" class="form-control" style="max-width:120px; text-align:right" name="INV_LISTTAXVALX" id="INV_LISTTAXVALX" value="<?php echo number_format($INV_LISTTAXVAL, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" >
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
                                    <div class="form-group" style="display:none">
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
                                    <div class="form-group" >
                                        <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?> </label>
                                        <div class="col-sm-10">
                                            <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $INV_STAT; ?>">
                                            <select name="INV_STAT" id="INV_STAT" class="form-control" style="max-width:100px">
                                                <option value="1" <?php if($INV_STAT == 1) { ?> selected <?php } ?>>New</option>
                                                <option value="2" <?php if($INV_STAT == 2) { ?> selected <?php } ?> style="display:none">Confirm</option>
                                                <?php if($ISAPPROVE == 1) { ?>
                                                <option value="3" <?php if($INV_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                        		</div>
                            </div>
                        </div>
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
										if($INV_STAT == 1)
										{
											?>
												<button class="btn btn-primary" >
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
</script>

<script>
	var decFormat		= 2;

	function add_header(TTK_NUM) 
	{
		document.getElementById("TTK_NUM1").value = TTK_NUM;
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