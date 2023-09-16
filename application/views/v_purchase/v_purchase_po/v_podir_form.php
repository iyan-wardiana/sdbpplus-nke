<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 November 2017
 * File Name	= v_po_form.php
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
	$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
	
	$PO_NUM			= '';
	$PR_NUM			= '';
	$PR_CODE		= '';
	$PO_CODE 		= '';
	$PO_TYPE 		= 1; // Internal
	$PO_CAT			= 1;
	$PO_DATE		= '';
	$PRJNAME 		= '';
	$SPLCODE 		= '0';
	$SPLDESC 		= '';
	$SPLADD1 		= '';
	$PO_CURR 		= 'IDR';
	$PO_CURRATE		= 1;
	$PO_TAXCURR 	= 'IDR';
	$PO_TAXRATE 	= 1;
	$PO_TOTCOST		= 0;
	$DP_CODE		= '';
	$DP_PPN_		= 0;
	$DP_JUML		= 0;
	$PO_PAYTYPE 	= 'Cash';
	$PO_TENOR 		= 0;
	$PO_STAT 		= 1;
	$PO_INVSTAT		= 0;					
	$PO_NOTES		= '';
	$PO_MEMO 		= '';
	$IR_VOLM		= 0;
	
	$totalAmount 		= '0.00';
	$BtotalAmount 		= '0.00';
	$totalDiscAmount 	= '0.00';
	$totalAmountAfDisc 	= '0.00';
	$totTaxPPnAmount 	= '0.00';
	$totTaxPPhAmount 	= '0.00';
	$GtotalAmount 		= '0.00';
	$Base_ITM_COST		= '0.00';
	$totDiscPrice 		= '0.00';
	$BtotDiscPrice 		= '0.00';
	$ITM_COST 			= '0.00';					
	$proj_Number 		= '';
	$Currency_USD		= 13000;
	
	foreach($viewDocPattern as $row) :
		$Pattern_Code 			= $row->Pattern_Code;
		$Pattern_Position 		= $row->Pattern_Position;
		$Pattern_YearAktive 	= $row->Pattern_YearAktive;
		$Pattern_MonthAktive 	= $row->Pattern_MonthAktive;
		$Pattern_DateAktive 	= $row->Pattern_DateAktive;
		$Pattern_Length 		= $row->Pattern_Length;
		$useYear 				= $row->useYear;
		$useMonth 				= $row->useMonth;
		$useDate 				= $row->useDate;
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
	$this->db->where('Patt_Month', $month);
	$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_po_header');
	
	$sql 		= "SELECT MAX(Patt_Number) as maxNumber FROM tbl_po_header WHERE Patt_Year = $year AND PRJCODE = '$PRJCODE'";
	$result 	= $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax 	= $row->maxNumber;
			$myMax 	= $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	$thisMonth 		= $month;
	
	$lenMonth 		= strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth 		= $nolMonth.$thisMonth;
	

	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
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
	
		
	$lastPatternNumb 	= $myMax;
	$lastPatternNumb1 	= $myMax;
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
	if($PO_TYPE == 1) 
	{
		$POType = '01';
	}
	else if($PO_TYPE == 2) 
	{
		$POType = '02';
	}
	
	$year			= date('y');
	$month			= date('m');
	$days			= date('d');
	$DocNumber1		= "$Pattern_Code$PRJCODE$year$month$days$POType-$lastPatternNumb";
	$DocNumber		= "$DocNumber1"."-D";
	$PO_NUM			= $DocNumber;
	$PO_CODE		= "$lastPatternNumb"; // OP MANUAL
	
	$POCODE			= substr($lastPatternNumb, -4);
	$POYEAR			= date('y');
	$POMONTH		= date('m');
	$PO_CODE		= "PO.$POCODE.$POYEAR.$POMONTH-D"; // MANUAL CODE
	
	$PO_DATEY 		= date('Y');
	$PO_DATEM 		= date('m');
	$PO_DATED 		= date('d');
	$PO_DATE 		= "$PO_DATEM/$PO_DATED/$PO_DATEY";
	$ETD			= $PO_DATE;
	$PO_PLANIRY	= date('Y');
	$PO_PLANIRM	= date('m');
	$PO_PLANIRD	= date('d');
	$PO_PLANIR		= "$PO_PLANIRM/$PO_PLANIRD/$PO_PLANIRY";
	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	
	 $sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resultPRJ 		= $this->db->query($sqlPRJ)->result();
	
	foreach($resultPRJ as $rowPRJ) :
		$PRJCODE1 	= $rowPRJ->PRJCODE;
		$PRJNAME1 	= $rowPRJ->PRJNAME;
	endforeach;
	$PR_NUMX		= $DocNumber;
	
	$PO_RECEIVLOC	= '';
	$PO_RECEIVCP	= '';
	$PO_SENTROLES	= '';
	$PO_REFRENS		= '';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_po_header~$Pattern_Length";
	$dataTarget		= "PO_CODE";
}
else
{
	$isSetDocNo = 1;
	$PO_NUM 		= $default['PO_NUM'];
	$DocNumber		= $PO_NUM;
	$PO_CODE 		= $default['PO_CODE'];
	$PO_TYPE 		= $default['PO_TYPE'];
	$PO_CAT 		= $default['PO_CAT'];
	$PO_DATE 		= $default['PO_DATE'];
	$PO_DATE		= date('m/d/Y', strtotime($PO_DATE));
	$PRJCODE 		= $default['PRJCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$PR_NUM 		= $default['PR_NUM'];
	$PR_NUMX		= $PR_NUM;
	$PO_CURR 		= $default['PO_CURR'];
	$PO_CURRATE 	= $default['PO_CURRATE'];
	$PO_TOTCOST 	= $default['PO_TOTCOST'];
	$PO_PAYTYPE 	= $default['PO_PAYTYPE'];
	$PO_TENOR 		= $default['PO_TENOR'];
	$PO_PLANIR 		= $default['PO_PLANIR'];
	$PO_NOTES 		= $default['PO_NOTES'];
	$PRJNAME1 		= $default['PRJNAME'];
	$PO_MEMO 		= $default['PO_MEMO'];
	$PO_STAT 		= $default['PO_STAT'];
	$lastPatternNumb1= $default['Patt_Number'];
	
	$PO_TAXRATE			= 1;
	$totTaxPPnAmount	= 1;
	$totTaxPPhAmount	= 1;
	
	$PO_RECEIVLOC	= $default['PO_RECEIVLOC'];
	$PO_RECEIVCP	= $default['PO_RECEIVCP'];
	$PO_SENTROLES 	= $default['PO_SENTROLES'];
	$PO_REFRENS 	= $default['PO_REFRENS'];
}
//echo $PR_NUMX;
$secGenCode	= base_url().'index.php/c_purchase/c_p180c21o/genCode/'; // Generate Code
?>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
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
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
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
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'AddItem')$AddItem = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Cancel')$Cancel = $LangTransl;
		if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
		if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
		if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
		if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$alert1		= 'Jumlah pemesanan tidak boleh kosong';
		$alert2		= 'Silahkan pilih nama supplier';
		$isManual	= "Centang untuk kode manual.";
	}
	else
	{
		$alert1		= 'Qty order can not be empty';
		$alert2		= 'Please select a supplier name';
		$isManual	= "Check to manual code.";
	}
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
                	<!-- Mencari Kode Purchase Request Number -->
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
           				<input type="hidden" name="rowCount" id="rowCount" value="0">
                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
                        <input type="hidden" name="PO_TYPE" id="PO_TYPE" value="<?php echo $PO_TYPE; ?>">
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
                                <input type="text" class="form-control" style="max-width:195px" name="PO_NUM1" id="PO_NUM1" value="<?php echo $DocNumber; ?>" disabled >
                       			<input type="hidden" class="textbox" name="PO_NUM" id="PO_NUM" size="30" value="<?php echo $DocNumber; ?>" />
                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode ?> </label>
                          	<div class="col-sm-10">
                                <label>
                                    <input type="text" class="form-control" style="min-width:width:150px; max-width:150px" name="PO_CODE" id="PO_CODE" value="<?php echo $PO_CODE; ?>" >
                                </label>
                                <label>
                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual" checked>
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
                                    <input type="text" name="PO_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $PO_DATE; ?>" style="width:150px" onChange="getPO_NUM(this.value)">
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
                        <div class="form-group" style="display:none">
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
							$url_selPR_CODE		= site_url('c_purchase/c_p180c21o/popupallPR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
                            	<select name="SPLCODE" id="SPLCODE" class="form-control select2" style="max-width:350px">
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
                            	<select name="PO_CURR" id="PO_CURR" class="form-control" style="max-width:75px">
                                	<option value="IDR" <?php if($PO_CURR == 'IDR') { ?> selected <?php } ?>>IDR</option>
                                	<option value="USD" <?php if($PO_CURR == 'USD') { ?> selected <?php } ?>>USD</option>    
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                       	  <label for="inputName" class="col-sm-2 control-label"><?php echo $PaymentType ?> </label>
                          	<div class="col-sm-10">
                                <select name="PO_PAYTYPE" id="PO_PAYTYPE" class="form-control select2" style="max-width:85px" onChange="selPO_PAYTYPE(this.value)">
                                    <option value="1" <?php if($PO_PAYTYPE == 'Cash') { ?> selected="selected" <?php } ?>>Cash</option>
                                    <option value="2" <?php if($PO_PAYTYPE == 'Credit') { ?> selected="selected" <?php } ?>>Credit</option>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PaymentTerm ?> </label>
                          	<div class="col-sm-10">
                                <select name="PO_TENOR" id="PO_TENOR" class="form-control select2" style="max-width:100px" onChange="selPO_TENOR(this.value)">
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
                        <script>
							function selPO_PAYTYPE(theValue)
							{
								if(theValue == 1)
								{
									document.getElementById('PO_TENOR').value = 0;
								}
								else
								{
									document.getElementById('PO_TENOR').value = 7;
								}
							}
							
							function selPO_TENOR(theValue)
							{
								if(theValue > 0)
								{
									document.getElementById('PO_PAYTYPE').value = 2;
								}
								else
								{
									document.getElementById('PO_PAYTYPE').value = 1;
								}
							}
						</script>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptDate ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="PO_PLANIR" class="form-control pull-left" id="datepicker2" value="<?php echo $PO_PLANIR; ?>" style="width:120px">
                                </div>
                          	</div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptLoc; ?></label>
                            <div class="col-sm-10">
                                <label>
                                <input type="text" class="form-control" style="max-width:200px;" name="PO_RECEIVLOC" id="PO_RECEIVLOC" size="30" value="<?php echo $PO_RECEIVLOC; ?>" />
                                </label>
                                <label>
                                <input type="text" class="form-control" style="max-width:150px;" name="PO_RECEIVCP" id="PO_RECEIVCP" size="30" value="<?php echo $PO_RECEIVCP; ?>" />
                              </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $SentRoles; ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:350px;" name="PO_SENTROLES" id="PO_SENTROLES" value="<?php echo $PO_SENTROLES; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $ReferenceNumber; ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:200px;" name="PO_REFRENS" id="PO_REFRENS" size="30" value="<?php echo $PO_REFRENS; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?> </label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="PO_NOTES"  id="PO_NOTES" style="max-width:350px;height:70px"><?php echo $PO_NOTES; ?></textarea>
                                <input type="hidden" name="PO_TOTCOST" id="PO_TOTCOST" value="<?php echo $PO_TOTCOST; ?>">
                          	</div>
                        </div>
                        <div id="revMemo" class="form-group" <?php if($PO_MEMO == '') { ?> style="display:none" <?php } ?>>
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $reviseNotes; ?></label>
                            <div class="col-sm-10">
                                <textarea name="PO_MEMO" class="form-control" style="max-width:350px;" id="PO_MEMO" cols="30" disabled><?php echo $PO_MEMO; ?></textarea>                        
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
                                <?php
									$isDisabled = 1;
									if($PO_STAT == 1 || $PO_STAT == 4)
									{
										$isDisabled = 0;
									}
								?>
                                <select name="PO_STAT" id="PO_STAT" class="form-control" style="max-width:100px" onChange="chkSTAT(this.value)">
                                    <?php
										$disableBtn	= 0;
										if($PO_STAT == 5 || $PO_STAT == 6 || $PO_STAT == 9)
										{
											$disableBtn	= 1;
										}
										if($PO_STAT != 1 AND $PO_STAT != 4) 
										{
											?>
												<option value="1"<?php if($PO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
												<option value="2"<?php if($PO_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
												<option value="3"<?php if($PO_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
												<option value="4"<?php if($PO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
												<option value="5"<?php if($PO_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
												<option value="6"<?php if($PO_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
												<option value="7"<?php if($PO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
												<option value="9"<?php if($PO_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
											<?php
										}
										else
										{
											?>
												<option value="1"<?php if($PO_STAT == 1) { ?> selected <?php } ?>>New</option>
												<option value="2"<?php if($PO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
											<?php
										}
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php if($PO_STAT == 3) 
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
                        <div class="form-group">
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
                                <button class="btn btn-success" type="button" onClick="selectitem();">
                                <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
                                </button>
                                </div>
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
                                      <th width="7%" style="text-align:center" nowrap><?php echo $BudgetQty; ?></th>
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
										if($task == 'edit')
										{																
											$sqlDET		= "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PRJCODE, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, 
																A.ITM_CODE, A.ITM_UNIT,
																A.PR_VOLM, A.PO_VOLM, A.PO_VOLM AS POVOLM, A.IR_VOLM, A.IR_AMOUNT, A.IR_PAVG,
																A.PO_PRICE AS PR_PRICE, A.PO_PRICE AS ITM_PRICE, A.PO_DISP, A.PO_DISC, A.PO_COST, A.PO_PRICE,
																A.PO_DESC, A.PO_DESC1, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
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
										else
										{
											?>
                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                            <?php
										}
											
										$i			= 0;
										$j			= 0;
										if($resultC > 0)
										{
											$GT_ITMPRICE	= 0;
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
												
												// GET ITEM BUDGET
												$BUDGET_QTY		= 0;
												$BUDGET_AMOUNT	= 0;
												
												if($PRJCODE == 'KTR')
												{
													$sqlBUDGET	= "SELECT ITM_VOLM, SUM(ITM_VOLM * ITM_PRICE) AS BUDGET_AMOUNT
																	FROM tbl_item
																	WHERE Z.PRJCODE = '$PRJCODE'";
												}
												else
												{
													$sqlBUDGET	= "SELECT A.ITM_VOLM, A.ITM_BUDG AS BUDGET_AMOUNT
																	FROM tbl_joblist_detail A
																		INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																			AND B.PRJCODE = '$PRJCODE'
																	WHERE A.PRJCODE = '$PRJCODE' AND JOBCODEDET = '$JOBCODEDET'";
												}
												$resBUDGET		= $this->db->query($sqlBUDGET)->result();
												foreach($resBUDGET as $rowBUDGET) :
													$BUDGET_QTY  	= $rowBUDGET->ITM_VOLM;
													$BUDGET_AMOUNT 	= $rowBUDGET->BUDGET_AMOUNT;
												endforeach;
												
												$PO_VOLM 		= $row->PO_VOLM;
												if($PO_VOLM == '')
													$PO_VOLM	= 0;
												$PO_PRICE 		= $row->PO_PRICE;
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
												$PO_COST 		= $row->PO_COST;
												$ITM_TOTAL		= $PO_COST;
												$PR_DESC		= $row->PO_DESC;
												$PO_DES1		= $row->PO_DESC1;
												$TAXCODE1		= $row->TAXCODE1;
												$TAXCODE2		= $row->TAXCODE2;
												$TAXPRICE1		= $row->TAXPRICE1;
												$TAXPRICE2		= $row->TAXPRICE2;
												$itemConvertion	= 1;
												
												$ITM_REMAIN		= $BUDGET_QTY - $ORDERED_QTY;
												
												if($TAXCODE1 == 'TAX01')
												{
													$GT_ITMPRICE	= $GT_ITMPRICE + $ITM_TOTAL + $TAXPRICE1;
												}
												if($TAXCODE1 == 'TAX02')
												{
													$GT_ITMPRICE	= $GT_ITMPRICE + $ITM_TOTAL - $TAXPRICE1;
												}
												
												//$ITM_TOTAL		= $PO_VOLM * $ITM_PRICE;
									
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
												<td width="7%" nowrap style="text-align:right"><?php print number_format($BUDGET_QTY, $decFormat); ?></td>
                                                
                                                <!-- ITEM REMAIN -->
											  	<td width="7%" nowrap style="text-align:right">
                                                	<?php print number_format($ITM_REMAIN, $decFormat); ?>
                                                    <input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_REMAIN<?php echo $currentRow; ?>" id="ITM_REMAIN<?php echo $currentRow; ?>" value="<?php echo $ITM_REMAIN; ?>" ></td>
                                                    
                                                <!-- ITEM ORDER NOW -->  
											  	<td width="12%" style="text-align:right">
													<input type="text" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="PO_VOLM<?php echo $currentRow; ?>" id="PO_VOLM<?php echo $currentRow; ?>" value="<?php print number_format($PO_VOLM, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $currentRow; ?>);" >
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
											  		<input type="text" class="form-control" style="text-align:right; min-width:100px" name="PO_PRICE<?php echo $currentRow; ?>" id="PO_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $currentRow; ?>);">
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_PRICE]" id="data<?php echo $currentRow; ?>PO_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>"></td>
                                                 
                                                <!-- ITEM DISCOUNT PERCENTATION -->
												<td width="6%" style="text-align:right">
													<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="PO_DISP<?php echo $currentRow; ?>" id="PO_DISP<?php echo $currentRow; ?>" value="<?php print number_format($PO_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $currentRow; ?>);" >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_DISP]" id="data<?php echo $currentRow; ?>PO_DISP" value="<?php echo $PO_DISP; ?>"></td>
													
												<!-- ITEM DISCOUNT -->
												<td width="12%" style="text-align:left">
													<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="PO_DISC<?php echo $currentRow; ?>" id="PO_DISC<?php echo $currentRow; ?>" value="<?php print number_format($PO_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $currentRow; ?>);" >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_DISC]" id="data<?php echo $currentRow; ?>PO_DISC" value="<?php echo $PO_DISC; ?>"></td>
                                                    
												<!-- ITEM TAX -->
												<td width="9%" style="text-align:left">
                                               	  <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" class="form-control" id="data<?php echo $currentRow; ?>TAXCODE1"  onChange="getValuePO(this, <?php echo $currentRow; ?>);" style="min-width:100px; max-width:150px">
														<option value=""> --- </option>
														<option value="TAX01" <?php if ($TAXCODE1 == "TAX01") { ?> selected <?php } ?>>PPn 10%</option>
														<option value="TAX02" <?php if ($TAXCODE1 == "TAX02") { ?> selected <?php } ?> disabled>PPh</option>
													</select></td>
                                                
											  <!-- ITEM TOTAL COST -->
												<td width="12%" style="text-align:left">
                                           	    <input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="PO_COST<?php echo $currentRow; ?>" id="PO_COST<?php echo $currentRow; ?>" value="<?php print number_format($PO_COST, $decFormat); ?>" >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][PO_COST]" id="data<?php echo $currentRow; ?>PO_COST" value="<?php echo $PO_COST; ?>">
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>">
                                                    </td>
												
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
											<?php
										}
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
										if(($PO_STAT == 1 || $PO_STAT == 4) && $ISCREATE == 1)
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
										if(($PO_STAT == 1 || $PO_STAT == 4) && $ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary" >
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
												</button>&nbsp;
											<?php
										}
									}
									$backURL	= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		
	function getValuePO(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		//tempTotMax1 		= parseFloat(document.getElementById('tempTotMax'+row).value);
		//ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);
		//PR_PRICE			= parseFloat(document.getElementById('data'+row+'PR_PRICE').value);
		
		PO_VOLM1			= document.getElementById('PO_VOLM'+row);
		PO_VOLM 			= parseFloat(eval(PO_VOLM1).value.split(",").join(""));
		ITM_REMAIN			= parseFloat(document.getElementById('ITM_REMAIN'+row).value);
		
		if(PO_VOLM > ITM_REMAIN)
		{
			swal('PO Qty more than Remain Qty.');
			document.getElementById('PO_VOLM'+row).focus();
			document.getElementById('PO_VOLM'+row).value = ITM_REMAIN;
			return false;
		}
		
		var PO_VOLM 		= parseFloat(document.getElementById('PO_VOLM'+row).value);
		document.getElementById('data'+row+'PO_VOLM').value 	= parseFloat(Math.abs(PO_VOLM));
		document.getElementById('PO_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_VOLM)),decFormat));
		
		// PO_PRICE
		PO_PRICE1			= document.getElementById('PO_PRICE'+row);
		PO_PRICE 			= parseFloat(eval(PO_PRICE1).value.split(",").join(""));
		document.getElementById('data'+row+'PO_PRICE').value 	= parseFloat(Math.abs(PO_PRICE));
		document.getElementById('PO_PRICE'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_PRICE)),decFormat));
		
		// PO DISCOUNT 
		thisValDISC			= document.getElementById('PO_DISC'+row);
		PO_DISC				= parseFloat(eval(thisValDISC).value.split(",").join(""));
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat((PO_VOLM * PO_PRICE) - PO_DISC);
		
		// PO TAX		
		TAXCODE1			= document.getElementById('data'+row+'TAXCODE1').value;
		if(TAXCODE1 == 'TAX01')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.1);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			PO_COST			= parseFloat(ITMPRICE_TEMP + TAX1VAL);
		}
		if(TAXCODE1 == 'TAX02')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.03);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			PO_COST			= parseFloat(ITMPRICE_TEMP - TAX1VAL);
		}
		if(TAXCODE1 == '')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			PO_COST			= parseFloat(ITMPRICE_TEMP);
		}
		document.getElementById('data'+row+'PO_COST').value 	= parseFloat(Math.abs(PO_COST));
		document.getElementById('PO_COST'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COST)),decFormat));
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICP				= document.getElementById('PO_DISP'+row);
		PO_DISP				= parseFloat(eval(valDICP).value.split(",").join(""));
		
		document.getElementById('data'+row+'PO_DISP').value 	= parseFloat(Math.abs(PO_DISP));
		document.getElementById('PO_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISP)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('PO_VOLM'+row);
		PO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// PO_PRICE
		PO_PRICE			= parseFloat(document.getElementById('data'+row+'PO_PRICE').value);
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(PO_VOLM * PO_PRICE);
		DISCOUNT			= parseFloat(PO_DISP * ITMPRICE_TEMP / 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('PO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		getValuePO(thisVal, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICC				= document.getElementById('PO_DISC'+row);
		PO_DISC				= parseFloat(eval(valDICC).value.split(",").join(""));
		
		document.getElementById('data'+row+'PO_DISC').value 	= parseFloat(Math.abs(PO_DISC));
		document.getElementById('PO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISC)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('PO_VOLM'+row);
		PO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// PO_PRICE
		PO_PRICE			= parseFloat(document.getElementById('data'+row+'PO_PRICE').value);
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(PO_VOLM * PO_PRICE);
		DISCOUNTP			= parseFloat(PO_DISC / ITMPRICE_TEMP * 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'PO_DISP').value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('PO_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		
		getValuePO(thisVal, row)
	}

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];
		
		PR_NUM		= arrItem[0];
		//swal(PR_NUM);
		//document.getElementById("PO_NUM1").value = PO_NUM;
		document.getElementById("PR_NUMX").value = PR_NUM;
		document.frmsrch.submitSrch.click();
	}
	
	function checkInp()
	{
		totRow	= document.getElementById('totalrow').value;		
		SPLCODE	= document.getElementById('SPLCODE').value;
		
		if(SPLCODE == 0)
		{
			swal("<?php echo $alert2; ?>");
			document.getElementById('SPLCODE').focus();
			return false;	
		}
		
		if(totRow == 0)
		{
			swal('<?php echo $qtyDetail; ?>');
			return false;
		}	
		
		var PO_TCOST	= 0;
		for(i=1;i<=totRow;i++)
		{
			var PO_COST = parseFloat(document.getElementById('data'+i+'PO_COST').value);
			PO_TCOST	= parseFloat(PO_TCOST) + parseFloat(PO_COST);
				
		}
		document.getElementById('PO_TOTCOST').value = PO_TCOST;
		
		for(i=1;i<=totRow;i++)
		{
			var PO_VOLM = parseFloat(document.getElementById('data'+i+'PO_VOLM').value);
			if(PO_VOLM == 0)
			{
				swal("<?php echo $alert1; ?>");
				document.getElementById('PO_VOLM'+i).focus();
				return false;	
			}
		}
	}
	
	function changeValue(thisVal, theRow)
	{
		var ITM_QTY_MIN	= document.getElementById('ITM_QTY_MIN'+theRow).value;
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");
		if(parseFloat(ITM_QTYx) > parseFloat(ITM_QTY_MIN))
		{
			swal('Qty can not greater then '+ ITM_QTY_MIN);
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY_MIN));
			document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_MIN)),decFormat));
		}
		else
		{
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTYx));
			document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYx)),decFormat));
		}
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
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
	
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var PO_NUM 		= "<?php echo $DocNumber; ?>";
		var PO_CODE 	= "<?php echo $PO_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEDET 		= arrItem[0];
		JOBCODEID 		= arrItem[1];
		JOBCODE 		= arrItem[2];
		PRJCODE 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_SN			= arrItem[6];
		ITM_UNIT 		= arrItem[7];
		ITM_PRICE 		= arrItem[8];
		ITM_VOLM 		= arrItem[9];
		ITM_BUDGQTY		= ITM_VOLM;
		ITM_STOCK 		= arrItem[10];
		ITM_USED 		= arrItem[11];
		itemConvertion	= arrItem[12];
		TotPrice		= arrItem[13];
		tempTotMax		= arrItem[14];
		ITM_BUDG		= arrItem[15];
		TOTPO_QTY		= arrItem[16];
		TOTPO_AMOUNT	= arrItem[17];
		
		// ITEM REMAIN => Budget Qty - PO Qty
		if(TOTPO_QTY == '')
			TOTPO_QTY		= 0;
		if(TOTPO_AMOUNT == '')
			TOTPO_AMOUNT	= 0;
			
		REMAIN_QTY		= parseFloat(ITM_BUDGQTY) - parseFloat(TOTPO_QTY);
		REMAIN_AMOUNT	= parseFloat(ITM_BUDG) - parseFloat(TOTPO_AMOUNT);
		
		PO_COST			= 0;
		//swal(unit_type_code);
		
		//swal(Unit_Price);
		objTable 		= document.getElementById('tbl');
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
		
		// ITM_CODE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PO_NUM" name="data['+intIndex+'][PO_NUM]" value="'+PO_NUM+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PO_CODE" name="data['+intIndex+'][PO_CODE]" value="'+PO_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PR_NUM" name="data['+intIndex+'][PR_NUM]" value="" width="10" size="15"><input type="hidden" id="data'+intIndex+'JOBCODEDET" name="data['+intIndex+'][JOBCODEDET]" value="'+JOBCODEDET+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// ITEM BUDGET
		ITM_BUDGQTY		= parseFloat(Math.abs(ITM_BUDGQTY));
		ITM_BUDGQTYV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_BUDGQTY)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+ITM_BUDGQTYV+'<input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_BUDGQTY'+intIndex+'" id="ITM_BUDGQTY'+intIndex+'" value="'+ITM_BUDGQTY+'" >';
				
		// ITM REMAIN
		REMAIN_QTY		= parseFloat(Math.abs(REMAIN_QTY));
		REMAIN_QTYV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REMAIN_QTY)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+REMAIN_QTYV+'<input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_REMAIN'+intIndex+'" id="ITM_REMAIN'+intIndex+'" value="'+REMAIN_QTY+'" >';
		
		// ITM ORDER NOW
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="max-width:200px; text-align:right" name="PO_VOLM'+intIndex+'" id="PO_VOLM'+intIndex+'" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, '+intIndex+');" ><input type="hidden" id="data'+intIndex+'PR_VOLM" name="data['+intIndex+'][PR_VOLM]" value="0"><input type="hidden" id="data'+intIndex+'PO_VOLM" name="data['+intIndex+'][PO_VOLM]" value="0">';	
		
		// ITM UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';		
		
		// ITM PRICE
		ITM_PRICE		= parseFloat(Math.abs(ITM_PRICE));
		ITM_PRICEV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right; min-width:100px" name="PO_PRICE'+intIndex+'" id="PO_PRICE'+intIndex+'" size="10" value="'+ITM_PRICEV+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, '+intIndex+');"><input type="hidden" style="text-align:right" name="data['+intIndex+'][PO_PRICE]" id="data'+intIndex+'PO_PRICE" size="6" value="'+ITM_PRICE+'">';
		
		// ITM DISCP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="PO_DISP'+intIndex+'" id="PO_DISP'+intIndex+'" value="0.00" onBlur="countDisp(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][PO_DISP]" id="data'+intIndex+'PO_DISP" value="0.00">';
		
		// ITM DISC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="PO_DISC'+intIndex+'" id="PO_DISC'+intIndex+'" value="0.00" onBlur="countDisc(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][PO_DISC]" id="data'+intIndex+'PO_DISC" value="0.00>">';
		// ITEM TAX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="data'+intIndex+'TAXCODE1" class="form-control" style="max-width:150px" onChange="getValuePO(this, '+intIndex+');"><option value=""> --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';

		// ITEM TOTAL COST
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="PO_COST'+intIndex+'" id="PO_COST'+intIndex+'" value="0.00" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][PO_COST]" id="data'+intIndex+'PO_COST" value="007"><input style="text-align:right" type="hidden" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" value="0"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
				
		//document.getElementById('ITM_BUDGQTY'+intIndex).value 		= parseFloat(Math.abs(ITM_BUDGQTY));
		//document.getElementById('ITM_BUDGQTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_BUDGQTY)),decFormat));
		
		//document.getElementById('ITM_REMAIN'+intIndex).value 		= parseFloat(Math.abs(REMAIN_QTY));
		//document.getElementById('ITM_REMAINx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REMAIN_QTY)),decFormat));
		
		//document.getElementById('PO_PRICE'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		//document.getElementById('data'+intIndex+'PO_PRICE').value 	= parseFloat(Math.abs(ITM_PRICE));
		
		//document.getElementById('PO_DISP'+intIndex).value 			= parseFloat(Math.abs(PO_DISP));
		//document.getElementById('data'+intIndex+'PO_DISP').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISP)),decFormat));
		
		//document.getElementById('PO_DISCx'+intIndex).value 			= parseFloat(Math.abs(PO_DISC));
		//document.getElementById('data'+intIndex+'PO_DISC').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_DISC)),decFormat));
		
		document.getElementById('PO_COST'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PO_COST)),decFormat));
		document.getElementById('data'+intIndex+'PO_COST').value 	= parseFloat(Math.abs(PO_COST));
		
		document.getElementById('totalrow').value = intIndex;
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>