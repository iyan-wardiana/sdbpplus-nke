<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= v_bom_form.php
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
	
	$SO_NUM			= '';
	$SC_NUM			= '';
	$SO_CODE 		= '';
	$SO_TYPE 		= 1; // Internal
	$SO_CAT			= 1;
	$SO_DATE		= '';
	$PRJNAME 		= '';
	$CUST_CODE 		= '0';
	$CUST_DESC 		= '';
	$CUST_ADD1 		= '';
	$SO_CURR 		= 'IDR';
	$SO_CURRATE		= 1;
	$SO_TAXCURR 	= 'IDR';
	$SO_TAXRATE 	= 1;
	$SO_TOTCOST		= 0;
	$DP_CODE		= '';
	$DP_JUML		= 0;
	$SO_PAYTYPE 	= 'Cash';
	$SO_TENOR 		= 0;
	$SO_STAT 		= 1;
	$SO_INVSTAT		= 0;					
	$SO_NOTES		= '';
	$SO_MEMO 		= '';
	
	/*$totalAmount 		= '0.00';
	$BtotalAmount 		= '0.00';
	$totalDiscAmount 	= '0.00';
	$totalAmountAfDisc 	= '0.00';
	$totTaxPPnAmount 	= '0.00';
	$totTaxPPhAmount 	= '0.00';
	$GtotalAmount 		= '0.00';
	$Base_ITM_COST		= '0.00';
	$totDiscPrice 		= '0.00';
	$BtotDiscPrice 		= '0.00';
	$ITM_COST 			= '0.00';*/
	
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
	$myCount = $this->db->count_all('tbl_so_header');
	
	$sql 		= "SELECT MAX(Patt_Number) as maxNumber FROM tbl_so_header WHERE Patt_Year = $year AND PRJCODE = '$PRJCODE'";
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
	if($SO_TYPE == 1) 
	{
		$POType = '01';
	}
	else if($SO_TYPE == 2) 
	{
		$POType = '02';
	}
	
	$year			= date('y');
	$month			= date('m');
	$days			= date('d');
	$DocNumber1		= "$Pattern_Code$PRJCODE$year$month$days$POType-$lastPatternNumb";
	//$DocNumber	= "$DocNumber1"."-D";
	$DocNumber		= "$DocNumber1";
	$SO_NUM			= $DocNumber;
	$SO_CODE		= "$lastPatternNumb"; // OP MANUAL
	
	$POCODE			= substr($lastPatternNumb, -4);
	$POYEAR			= date('y');
	$POMONTH		= date('m');
	//$SO_CODE		= "$Pattern_Code.$POCODE.$POYEAR.$POMONTH-D"; // MANUAL CODE
	$SO_CODE		= "$Pattern_Code.$POCODE.$POYEAR.$POMONTH"; // MANUAL CODE
	
	$SO_DATEY 		= date('Y');
	$SO_DATEM 		= date('m');
	$SO_DATED 		= date('d');
	$SO_DATE 		= "$SO_DATEM/$SO_DATED/$SO_DATEY";
	$ETD			= $SO_DATE;
	$SO_PRODDY		= date('Y');
	$SO_PRODDM		= date('m');
	$SO_PRODDD		= date('d');
	$SO_PRODD		= "$SO_PRODDM/$SO_PRODDD/$SO_PRODDY";
	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	
	 $sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resultPRJ 		= $this->db->query($sqlPRJ)->result();
	
	foreach($resultPRJ as $rowPRJ) :
		$PRJCODE1 	= $rowPRJ->PRJCODE;
		$PRJNAME1 	= $rowPRJ->PRJNAME;
	endforeach;
	$SC_NUMX		= $DocNumber;
	
	$SO_RECEIVLOC	= '';
	$SO_RECEIVCP	= '';
	$SO_SENTROLES	= '';
	$SO_REFRENS		= '';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_so_header~$Pattern_Length";
	$dataTarget		= "SO_CODE";
}
else
{
	$isSetDocNo = 1;
	$SO_NUM 		= $default['SO_NUM'];
	$DocNumber		= $SO_NUM;
	$SO_CODE 		= $default['SO_CODE'];
	$SO_TYPE 		= $default['SO_TYPE'];
	$SO_CAT 		= $default['SO_CAT'];
	$SO_DATE 		= $default['SO_DATE'];
	$SO_DATE		= date('m/d/Y', strtotime($SO_DATE));
	$PRJCODE 		= $default['PRJCODE'];
	$CUST_CODE 		= $default['CUST_CODE'];
	$SC_NUM 		= $default['SC_NUM'];
	$SC_NUMX		= $SC_NUM;
	$SO_CURR 		= $default['SO_CURR'];
	$SO_CURRATE 	= $default['SO_CURRATE'];
	$SO_TOTCOST 	= $default['SO_TOTCOST'];
	$SO_PAYTYPE 	= $default['SO_PAYTYPE'];
	$SO_TENOR 		= $default['SO_TENOR'];
	$SO_PRODD 		= $default['SO_PRODD'];
	$SO_NOTES 		= $default['SO_NOTES'];
	$PRJNAME1 		= $default['PRJNAME'];
	$SO_STAT 		= $default['SO_STAT'];
	$lastPatternNumb1= $default['Patt_Number'];
	
	$SO_TAXRATE			= 1;
	$totTaxPPnAmount	= 1;
	$totTaxPPhAmount	= 1;
	
	$SO_RECEIVLOC	= $default['SO_RECEIVLOC'];
	$SO_RECEIVCP	= $default['SO_RECEIVCP'];
	$SO_SENTROLES 	= $default['SO_SENTROLES'];
	$SO_REFRENS 	= $default['SO_REFRENS'];
}
//echo $SC_NUMX;
$secGenCode	= base_url().'index.php/c_production/c_b0fm47/genCode/'; // Generate Code
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
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Custom Theme Style -->
        <link href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/build/css/custom.min.css'; ?>" rel="stylesheet">
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
            if($TranslCode == 'CustName')$CustName = $LangTransl;
            if($TranslCode == 'Currency')$Currency = $LangTransl;
            if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
            if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
            if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
            if($TranslCode == 'ProdTotal')$ProdTotal = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'AdditAddress')$AdditAddress = $LangTransl;
            if($TranslCode == 'Status')$Status = $LangTransl;
            if($TranslCode == 'ProcessStatus')$ProcessStatus = $LangTransl;
            if($TranslCode == 'Search')$Search = $LangTransl;
            
            if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
            if($TranslCode == 'ItemName')$ItemName = $LangTransl;
            if($TranslCode == 'Remain')$Remain = $LangTransl;
            if($TranslCode == 'Quantity')$Quantity = $LangTransl;
            if($TranslCode == 'CustName')$CustName = $LangTransl;
            if($TranslCode == 'Unit')$Unit = $LangTransl;
            if($TranslCode == 'Price')$Price = $LangTransl;
            if($TranslCode == 'Discount')$Discount = $LangTransl;
            if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
            if($TranslCode == 'Tax')$Tax = $LangTransl;
            if($TranslCode == 'Purchase')$Purchase = $LangTransl;
            if($TranslCode == 'Tax')$Tax = $LangTransl;
            if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
            if($TranslCode == 'RawMtr')$RawMtr = $LangTransl;
            if($TranslCode == 'Save')$Save = $LangTransl;
            if($TranslCode == 'Update')$Update = $LangTransl;
            if($TranslCode == 'Cancel')$Cancel = $LangTransl;
            if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
            if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'Step1')$Step1 = $LangTransl;
            if($TranslCode == 'Step2')$Step2 = $LangTransl;
        endforeach;
        if($LangID == 'IND')
        {
            $alert1		= 'Jumlah pemesanan tidak boleh kosong';
            $alert2		= 'Silahkan pilih nama supplier';
            $isManual	= "Centang untuk kode manual.";
            $Step1Des	= "Pilih Orde Penjualan";
            $Step2Des	= "Pilih Produk";
        }
        else
        {
            $alert1		= 'Qty order can not be empty';
            $alert2		= 'Please select a supplier name';
			$isManual	= "Check to manual code.";
            $Step1Des	= "Select Sales Order";
            $Step2Des	= "Select Finish Goods";
        }
    ?>


<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/bom.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
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
            	<div class="box-body chart-responsive">
                    <!-- Smart Wizard -->
                        <div id="wizard" class="form_wizard wizard_horizontal">
                            <ul class="wizard_steps">
                                <li>
                                    <a href="#step-1">
                                    <span class="step_no">1</span>
                                    <span class="step_descr">
                                        <?php echo $Step1; ?><br />
                                        <small><?php echo $Step1Des; ?></small>
                                    </span>
                                </a>
                                </li>
                                <li>
                                    <a href="#step-2">
                                        <span class="step_no">2</span>
                                        <span class="step_descr">
                                        <?php echo $Step2; ?><br />
                                        <small><?php echo $Step2Des; ?></small>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#step-3">
                                    <span class="step_no">3</span>
                                    <span class="step_descr">
                                        Step 3<br />
                                        <small>Step 3 description</small>
                                    </span>
                                    </a>
                                </li>
                                <li>
                                <a href="#step-4">
                                    <span class="step_no">4</span>
                                    <span class="step_descr">
                                    Step 4<br />
                                    <small>Step 4 description</small>
                                    </span>
                                </a>
                                </li>
                            </ul>
                            <div id="step-1">
                                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                <thead>
                                    <tr>
                                        <th width="4%" height="40" style="text-align:center"></th>
                                        <th width="14%" style="text-align:center" nowrap><?php echo $Code; ?> SO</th>
                                        <th width="3%" style="text-align:center" nowrap><?php echo $Date; ?> </th>
                                        <th width="26%" style="text-align:center" nowrap><?php echo $CustName; ?> </th> 
                                        <!-- Input Manual -->
                                        <th width="40%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
                                        <th width="13%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="4%" height="25" style="text-align:center" nowrap>
                                            	<input type="checkbox" class="flat-red" checked>
                                            </td>
                                            <td width="14%" style="text-align:left" nowrap>
                                                S0-098760912
                                            </td>
                                            <td width="3%" style="text-align:left" nowrap>
                                                20 Okt 2018
                                            </td>  
                                            <td width="26%" style="text-align:left">
                                                PT Dian Hermanto
                                            </td>
                                            <td width="40%" style="text-align:left">
                                                Item name 1
                                            </td>
                                            <td width="13%" style="text-align:right">
                                                1,000.00
                                            </td>
                                        </tr>
                                	</tbody>
                                </table>
                            </div>
                            <div id="step-2">                                
                                <form class="form-horizontal form-label-left">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"><?php echo $Code; ?> JO <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label>
                                                <input type="text" class="form-control" style="min-width:width:150px; max-width:150px" name="SO_CODE" id="SO_CODE" value="<?php echo $SO_CODE; ?>" >
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
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?php echo $Date ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="input-group date">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                                <input type="text" name="SO_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $SO_DATE; ?>" style="width:150px" onChange="getSO_NUM(this.value)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $ProdPlan ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="input-group date">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                                <input type="text" name="SO_PRODD" class="form-control pull-left" id="datepicker2" value="<?php echo $SO_PRODD; ?>" style="width:150px">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $Project ?></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div id="gender" class="btn-group" data-toggle="buttons">
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
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $Description ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="SO_NOTES"  id="SO_NOTES" style="max-width:350px;height:70px"><?php echo $SO_NOTES; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $ProdTotal ?> <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" name="SO_PRODD" class="form-control pull-left" id="SO_PRODD" value="<?php echo number_format(0, 2); ?>" style="width:100px; text-align:right">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="step-3">
                                <h2 class="StepTitle">Step 3 Content</h2>
                                <p>
                                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </p>
                            </div>
                            <div id="step-4">
                                <h2 class="StepTitle">Step 4 Content</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                </p>
                            </div>
                    <!-- End SmartWizard Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>

</html>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuerySW/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuerySW/js/jquery.smartWizard.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/build/js/custom.min.js'; ?>"></script>


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
		
	function getValueSO(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		SO_VOLM1			= document.getElementById('SO_VOLM'+row);
		SO_VOLM 			= parseFloat(eval(SO_VOLM1).value.split(",").join(""));
		
		var SO_VOLM 		= parseFloat(document.getElementById('SO_VOLM'+row).value);
		document.getElementById('data'+row+'SO_VOLM').value 	= parseFloat(Math.abs(SO_VOLM));
		document.getElementById('SO_VOLM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_VOLM)),decFormat));
		alert('a')
		// SO PRICE
		SO_PRICE1			= document.getElementById('SO_PRICE'+row);
		SO_PRICE 			= parseFloat(eval(SO_PRICE1).value.split(",").join(""));
		document.getElementById('data'+row+'SO_PRICE').value 	= parseFloat(Math.abs(SO_PRICE));
		document.getElementById('SO_PRICE'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(SO_PRICE),decFormat));
		
		// SO DISCOUNT 
		thisValDISC			= document.getElementById('SO_DISC'+row);
		SO_DISC				= parseFloat(eval(thisValDISC).value.split(",").join(""));
		
		alert('b')
		// SO TEMP
		ITMPRICE_TEMP		= parseFloat((SO_VOLM * SO_PRICE) - SO_DISC);
		
		// SO TAX		
		TAXCODE1			= document.getElementById('data'+row+'TAXCODE1').value;
		if(TAXCODE1 == 'TAX01')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.1);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			SO_COST			= parseFloat(ITMPRICE_TEMP + TAX1VAL);
		}
		if(TAXCODE1 == 'TAX02')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0.03);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			SO_COST			= parseFloat(ITMPRICE_TEMP - TAX1VAL);
		}
		if(TAXCODE1 == '')
		{
			TAX1VAL			= parseFloat(ITMPRICE_TEMP * 0);
			document.getElementById('data'+row+'TAXPRICE1').value 	= parseFloat(Math.abs(TAX1VAL));
			SO_COST			= parseFloat(ITMPRICE_TEMP);
		}
		document.getElementById('data'+row+'SO_COST').value 	= parseFloat(Math.abs(SO_COST));
		document.getElementById('SO_COST'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_COST)),decFormat));
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICP				= document.getElementById('SO_DISP'+row);
		SO_DISP				= parseFloat(eval(valDICP).value.split(",").join(""));
		
		document.getElementById('data'+row+'SO_DISP').value 	= parseFloat(Math.abs(SO_DISP));
		document.getElementById('SO_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_DISP)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('SO_VOLM'+row);
		SO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// SO_PRICE
		SO_PRICE			= parseFloat(document.getElementById('data'+row+'SO_PRICE').value);
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(SO_VOLM * SO_PRICE);
		DISCOUNT			= parseFloat(SO_DISP * ITMPRICE_TEMP / 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'SO_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('SO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		getValueSO(thisVal, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		valDICC				= document.getElementById('SO_DISC'+row);
		SO_DISC				= parseFloat(eval(valDICC).value.split(",").join(""));
		
		document.getElementById('data'+row+'SO_DISC').value 	= parseFloat(Math.abs(SO_DISC));
		document.getElementById('SO_DISC'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_DISC)),decFormat));
		
		// PO VOLM
		thisValVOLM			= document.getElementById('SO_VOLM'+row);
		SO_VOLM 			= parseFloat(eval(thisValVOLM).value.split(",").join(""));
		
		// SO_PRICE
		SO_PRICE			= parseFloat(document.getElementById('data'+row+'SO_PRICE').value);
		
		// PO TEMP
		ITMPRICE_TEMP		= parseFloat(SO_VOLM * SO_PRICE);
		DISCOUNTP			= parseFloat(SO_DISC / ITMPRICE_TEMP * 100);
		
		// PO DISCOUNT	
		document.getElementById('data'+row+'SO_DISP').value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('SO_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		
		getValueSO(thisVal, row)
	}
	
	function checkInp()
	{
		totRow	= document.getElementById('totalrow').value;		
		CUST_CODE	= document.getElementById('CUST_CODE').value;
		
		if(CUST_CODE == 0)
		{
			alert("<?php echo $alert2; ?>");
			document.getElementById('CUST_CODE').focus();
			return false;	
		}
		
		if(totRow == 0)
		{
			alert('<?php echo $qtyDetail; ?>');
			return false;
		}	
		
		var SO_TCOST	= 0;
		for(i=1;i<=totRow;i++)
		{
			var SO_COST = parseFloat(document.getElementById('data'+i+'SO_COST').value);
			SO_TCOST	= parseFloat(SO_TCOST) + parseFloat(SO_COST);
				
		}
		document.getElementById('SO_TOTCOST').value = SO_TCOST;
		
		for(i=1;i<=totRow;i++)
		{
			var SO_VOLM = parseFloat(document.getElementById('data'+i+'SO_VOLM').value);
			if(SO_VOLM == 0)
			{
				alert("<?php echo $alert1; ?>");
				document.getElementById('SO_VOLM'+i).focus();
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
			alert('Qty can not greater then '+ ITM_QTY_MIN);
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
		//alert(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var SO_NUM 		= "<?php echo $DocNumber; ?>";
		var SO_CODE 	= "<?php echo $SO_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			alert("Double Item for " + arrItem[0]);
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
		TOTSO_QTY		= arrItem[16];
		TOTSO_AMOUNT	= arrItem[17];
		
		// ITEM REMAIN => Budget Qty - PO Qty
		if(TOTSO_QTY == '')
			TOTSO_QTY		= 0;
		if(TOTSO_AMOUNT == '')
			TOTSO_AMOUNT	= 0;
			
		REMAIN_QTY		= parseFloat(ITM_BUDGQTY) - parseFloat(TOTSO_QTY);
		REMAIN_AMOUNT	= parseFloat(ITM_BUDG) - parseFloat(TOTSO_AMOUNT);
		
		SO_COST			= 0;
		//alert(unit_type_code);
		
		//alert(Unit_Price);
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//alert('intTable = '+intTable)
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
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'SO_NUM" name="data['+intIndex+'][SO_NUM]" value="'+SO_NUM+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'SO_CODE" name="data['+intIndex+'][SO_CODE]" value="'+SO_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'SC_NUM" name="data['+intIndex+'][SC_NUM]" value="" width="10" size="15"><input type="hidden" id="data'+intIndex+'JOBCODEDET" name="data['+intIndex+'][JOBCODEDET]" value="'+JOBCODEDET+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// ITM ORDER NOW
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="max-width:200px; text-align:right" name="SO_VOLM'+intIndex+'" id="SO_VOLM'+intIndex+'" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, '+intIndex+');" ><input type="hidden" id="data'+intIndex+'SO_VOLM" name="data['+intIndex+'][SO_VOLM]" value="0">';	
		
		// ITM UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';		
		
		// ITM PRICE
		ITM_PRICE		= parseFloat(Math.abs(ITM_PRICE));
		ITM_PRICEV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="text-align:right; min-width:100px" name="SO_PRICE'+intIndex+'" id="SO_PRICE'+intIndex+'" size="10" value="'+ITM_PRICEV+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueSO(this, '+intIndex+');"><input type="hidden" style="text-align:right" name="data['+intIndex+'][SO_PRICE]" id="data'+intIndex+'SO_PRICE" size="6" value="'+ITM_PRICE+'">';
		
		// ITM DISCP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="SO_DISP'+intIndex+'" id="SO_DISP'+intIndex+'" value="0.00" onBlur="countDisp(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][SO_DISP]" id="data'+intIndex+'SO_DISP" value="0.00">';
		
		// ITM DISC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="SO_DISC'+intIndex+'" id="SO_DISC'+intIndex+'" value="0.00" onBlur="countDisc(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][SO_DISC]" id="data'+intIndex+'SO_DISC" value="0.00>">';
		// ITEM TAX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="data'+intIndex+'TAXCODE1" class="form-control" style="max-width:150px" onChange="getValueSO(this, '+intIndex+');"><option value=""> --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';

		// ITEM TOTAL COST
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="SO_COST'+intIndex+'" id="SO_COST'+intIndex+'" value="0.00" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][SO_COST]" id="data'+intIndex+'SO_COST" value="007"><input style="text-align:right" type="hidden" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" value="0"><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
				
		//document.getElementById('ITM_BUDGQTY'+intIndex).value 		= parseFloat(Math.abs(ITM_BUDGQTY));
		//document.getElementById('ITM_BUDGQTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_BUDGQTY)),decFormat));
		
		//document.getElementById('ITM_REMAIN'+intIndex).value 		= parseFloat(Math.abs(REMAIN_QTY));
		//document.getElementById('ITM_REMAINx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REMAIN_QTY)),decFormat));
		
		//document.getElementById('SO_PRICE'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		//document.getElementById('data'+intIndex+'SO_PRICE').value 	= parseFloat(Math.abs(ITM_PRICE));
		
		//document.getElementById('SO_DISP'+intIndex).value 			= parseFloat(Math.abs(SO_DISP));
		//document.getElementById('data'+intIndex+'SO_DISP').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_DISP)),decFormat));
		
		//document.getElementById('SO_DISCx'+intIndex).value 			= parseFloat(Math.abs(SO_DISC));
		//document.getElementById('data'+intIndex+'SO_DISC').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_DISC)),decFormat));
		
		document.getElementById('SO_COST'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(SO_COST)),decFormat));
		document.getElementById('data'+intIndex+'SO_COST').value 	= parseFloat(Math.abs(SO_COST));
		
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