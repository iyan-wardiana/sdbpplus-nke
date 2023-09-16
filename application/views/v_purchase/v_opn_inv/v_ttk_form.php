<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2018
 * File Name	= v_ttk_form.php
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
	$year = (int)$Pattern_YearAktive;
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_ttk_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_ttk_header
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
	$lastPattNumb = $myMax;
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
	
	$DocNumber 	= "$PRJCODE.$Pattern_Code$groupPattern-$lastPatternNumb";
	$TTK_NUM	= $DocNumber;
	$TTK_CODE	= $lastPatternNumb;
	
	$TTK_DATEY 		= date('Y');
	$TTK_DATEM		= date('m');
	$TTK_DATED 		= date('d');
	$TTK_DATE 		= date('m/d/Y');
	$Patt_Year 		= date('Y');
	$TTK_DUEDATE	= date('m/d/Y');
	$TTK_CATEG		= 'IR';
	$TTK_NOTES		= '';
	$TTK_STAT		= 1;

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
	$TTK_AMOUNT		= 0;
	$TTK_AMOUNT_PPN	= 0;
	$TTK_GTOTAL		= 0;
}
else
{
	$TTK_NUM 		= $default['TTK_NUM'];
	$TTK_CODE 		= $default['TTK_CODE'];
	$TTK_DATE 		= date('m/d/Y', strtotime($default['TTK_DATE']));
	$TTK_DUEDATE	= date('m/d/Y', strtotime($default['TTK_DUEDATE']));
	$TTK_AMOUNT		= $default['TTK_AMOUNT'];
	$TTK_AMOUNT_PPN	= $default['TTK_AMOUNT_PPN'];
	$TTK_GTOTAL		= $default['TTK_GTOTAL'];
	$TTK_NOTES 		= $default['TTK_NOTES'];
	$TTK_CATEG		= $default['TTK_CATEG'];
	$PRJCODE 		= $default['PRJCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$TTK_STAT 		= $default['TTK_STAT'];
	$Patt_Number 	= $default['Patt_Number'];
	$lastPattNumb	= $Patt_Number;
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
		if($TranslCode == 'TTKNumber')$TTKNumber = $LangTransl;
		if($TranslCode == 'TTKCode')$TTKCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
		if($TranslCode == 'Category')$Category = $LangTransl;
		if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
		if($TranslCode == 'Search')$Search = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
		if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
		if($TranslCode == 'BillDate')$BillDate = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Total')$Total = $LangTransl;
		if($TranslCode == 'Tax')$Tax = $LangTransl;
		if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;				
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Save')$Save = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Cancel')$Cancel = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'SuplInvNo')$SuplInvNo = $LangTransl;
		if($TranslCode == 'InvList')$InvList = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
		if($TranslCode == 'ReceiptSel')$ReceiptSel = $LangTransl;
		if($TranslCode == 'ItemAcceptence')$ItemAcceptence = $LangTransl;
		if($TranslCode == 'Others')$Others = $LangTransl;
		if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h1_title 	= "Tambah";
		$h2_title 	= "Tanda Terima Faktur";
		$h3_title	= "Penerimaan Barang";
	}
	else
	{
		$h1_title 	= "Add";
		$h2_title 	= "Invoice Receipt";
		$h3_title	= "Receiving Goods";
	}
	
	$PRJCODE1	= $PRJCODE;
	$SPLCODE1	= $SPLCODE;
	$TTK_CATEG1	= $TTK_CATEG;
	if(isset($_POST['PRJCODE1']))
	{
		$PRJCODE1	= $_POST['PRJCODE1'];
		$SPLCODE1	= $_POST['SPLCODE1'];
		$TTK_CATEG1	= $_POST['TTK_CATEG1'];
	}
	
	$SPLADD1	= '';
	$sqlSUPL	= "SELECT SPLADD1 FROM tbl_supplier WHERE SPLCODE = '$SPLCODE1' AND SPLSTAT = '1' LIMIT 1";
	$resSUPL	= $this->db->query($sqlSUPL)->result();
	foreach($resSUPL as $rowSUPL):
		$SPLADD1	= $rowSUPL->SPLADD1;
	endforeach;
	
	if(isset($_POST['IR_NUM1']))
	{
		$IR_NUM1	= $_POST['IR_NUM1'];
	}
	
	$sqlPRJ1 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
	$restPRJ1 	= $this->db->query($sqlPRJ1)->result();	
	foreach($restPRJ1 as $rowPRJ1) :
		$PRJNAME1 	= $rowPRJ1->PRJNAME;
	endforeach;
	
	if(isset($_POST['submitSrch1']))
	{
		$myTTKCATEG		= $_POST['TTK_CATEG1'];
		$TTK_CATEG		= $myTTKCATEG;
		if($TTK_CATEG == 'IR')
		{
			$sqlSUPLC	= "tbl_ir_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.APPROVE = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.INVSTAT NOT IN ('FI')
								AND A.TTK_CREATED = '0'";
			$countSUPL	= $this->db->count_all($sqlSUPLC);
			
			$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
							FROM tbl_ir_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.APPROVE = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.INVSTAT NOT IN ('FI')
								AND A.TTK_CREATED = '0'";
			$vwSUPL	= $this->db->query($sqlSUPL)->result();
		}
		elseif($TTK_CATEG == 'OPN')
		{
			$sqlSUPLC	= "tbl_opn_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.OPNH_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.TTK_CREATED = '0'";
			$countSUPL	= $this->db->count_all($sqlSUPLC);
			
			$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
							FROM tbl_opn_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.OPNH_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.TTK_CREATED = '0'";
			$vwSUPL	= $this->db->query($sqlSUPL)->result();
		}
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
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
                        <input type="text" name="TTK_CATEG1" id="TTK_CATEG1" value="<?php echo $TTK_CATEG1; ?>" />
                        <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                    </form>
                    <!-- End -->
                    
                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                        <input type="hidden" name="rowCount" id="rowCount" value="0">
                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPattNumb; ?>">
                        <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $TTKNumber; ?></label>
                          	<div class="col-sm-10">
                        		<input type="hidden" class="textbox" name="TTK_NUM" id="TTK_NUM" size="30" value="<?php echo $TTK_NUM; ?>" />
                                <input type="text" class="form-control" style="max-width:195px" name="TTK_NUM1" id="TTK_NUM1" value="<?php echo $TTK_NUM; ?>" readonly >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $TTKCode; ?></label>
                          	<div class="col-sm-10">
                                <input type="text" class="form-control" style="max-width:195px" name="TTK_CODE" id="TTK_CODE" value="<?php echo $TTK_CODE; ?>" >
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="TTK_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $TTK_DATE; ?>" style="width:150px">
                                </div>

                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $DueDate; ?></label>
                          	<div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                    <input type="text" name="TTK_DUEDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $TTK_DUEDATE; ?>" style="width:150px">
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
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Category; ?></label>
                          	<div class="col-sm-10">
                            	<select name="TTK_CATEG" id="TTK_CATEG" class="form-control" style="max-width:250px" onChange="getSUPPLIER(this.value)">
                                    <option value="IR" <?php if($TTK_CATEG1 == 'IR') { ?> selected <?php } ?>><?php echo $ItemAcceptence; ?> - IR</option>
                                    <option value="OPN" <?php if($TTK_CATEG1 == 'OPN') { ?> selected <?php } ?>>Opname - OPN</option>
                                    <option value="OTH" <?php if($TTK_CATEG1 == 'OTH') { ?> selected <?php } ?>><?php echo $Others; ?> - OTH</option>
                                </select>
                          	</div>
                        </div>
                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SupplierName; ?></label>
                          	<div class="col-sm-10">
                            	<select name="SPLCODE" id="SPLCODE" class="form-control" style="max-width:250px" onChange="getSUPPLIER(this.value)" <?php if($TTK_STAT != 1) { ?> disabled <?php } ?>>
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
								TTK_CATEG	= document.getElementById("TTK_CATEG").value
								document.getElementById("TTK_CATEG1").value = TTK_CATEG;
								SPLCODE	= document.getElementById("SPLCODE").value
								document.getElementById("SPLCODE1").value = SPLCODE;
								PRJCODE	= document.getElementById("PRJCODE").value
								document.getElementById("PRJCODE1").value = PRJCODE;
								document.frmsrch1.submitSrch1.click();
							}
						</script>
                        <div class="form-group" style="display:none">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
                          	<div class="col-sm-10">
                            	<input type="hidden" class="form-control" style="max-width:195px" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
                            	<select name="selPRJCODE" id="selPRJCODE" class="form-control" style="max-width:250px" <?php if($TTK_STAT != 1) { ?> disabled <?php } ?>>
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
                       	  <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
                          	<div class="col-sm-10">
                                <textarea class="form-control" name="TTK_NOTES"  id="TTK_NOTES" style="max-width:350px;height:70px"><?php echo $TTK_NOTES; ?></textarea>
                          	</div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Cek Total</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                      <input type="checkbox" name="chkTotal" id="chkTotal" onClick="checkTotalTTK()">
                                    </span>
                                    <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT" id="TTK_AMOUNT" value="<?php echo $TTK_AMOUNT; ?>" >
                                    <input type="text" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNTX" id="TTK_AMOUNTX" value="<?php echo number_format($TTK_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" >
                        		</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">PPn</label>
                            <div class="col-sm-10">
                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_PPN" id="TTK_AMOUNT_PPN" value="<?php echo $TTK_AMOUNT_PPN; ?>" >
                                <input type="text" class="form-control" style="max-width:160px; text-align:right" name="TTK_AMOUNT_PPNX" id="TTK_AMOUNT_PPNX" value="<?php echo number_format($TTK_AMOUNT_PPN, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Grand Total</label>
                            <div class="col-sm-10">
                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_GTOTAL" id="TTK_GTOTAL" value="<?php echo $TTK_GTOTAL; ?>" >
                                <input type="text" class="form-control" style="max-width:160px; text-align:right" name="TTK_GTOTALX" id="TTK_GTOTALX" value="<?php echo number_format($TTK_GTOTAL, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" readonly >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
                            <div class="col-sm-10">
                                <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $TTK_STAT; ?>">
                                <select name="TTK_STAT" id="TTK_STAT" class="form-control" style="max-width:100px" onChange="selStat(this.value)">
                                    <option value="1"<?php if($TTK_STAT == 1) { ?> selected <?php } ?>>New</option>
                                    <option value="2"<?php if($TTK_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                    <?php
                                    if($ISAPPROVE == 1) 
                                    {
                                        ?>
                                            <option value="6"<?php if($TTK_STAT == 6) { ?> selected <?php } ?> style="display:none">Close</option>
                                            <option value="3"<?php if($TTK_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
							$theProjCode 	= "$PRJCODE~$SPLCODE1~$TTK_CATEG1";
							$url_SelectINV	= site_url('c_purchase/c_pi180c23/pall180dIR/?id='.$this->url_encryption_helper->encode_url($theProjCode));
								
							if($TTK_STAT == 1)
							{
						?>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                                <div class="col-sm-10">
                                    <script>
                                        var url = "<?php echo $url_SelectINV;?>";
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
                                        <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $ReceiptSel; ?>
                                    </button>
                                </div>
                            </div>
                        <?php
							}
							?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-primary">
                                <br>
                                	<table width="100%" border="1" id="tbl">
                                    <tr style="background:#CCCCCC">
                                        <th width="2%" height="25" style="text-align:center">&nbsp;</th>
                                        <th width="12%" style="text-align:center" nowrap><?php echo $ReceiptCode; ?></th>
                                        <th width="5%" style="text-align:center" nowrap><?php echo $Date; ?></th>
                                        <th width="8%" style="text-align:center" nowrap><?php echo $ReferenceNumber; ?></th>
                                        <th width="44%" style="text-align:center" nowrap><?php echo $Description; ?></th>
                                        <th width="9%" style="text-align:center" nowrap><?php echo $AmountReceipt; ?></th>
                                        <th width="2%" style="text-align:center" nowrap>&nbsp;</th>
                                        <th width="7%" style="text-align:center" nowrap>PPn</th>
                                        <th width="11%" style="text-align:center" nowrap><?php echo $TotAmount; ?></th>
                                    </tr>
									<?php
									$TOT_AMOUNT2	= 0;
                                    if($task = 'edit')
                                    {					
                                        // count data
                                        $sqlIRc		= "tbl_ttk_detail WHERE TTK_NUM = '$TTK_NUM'";
                                        $resIRc 	= $this->db->count_all($sqlIRc);
                                        // End count data
                                        
                                        // 1. Ambil detail IR
                                        $sqlIR		= "SELECT A.TTK_NUM, A.TTK_REF1, A.TTK_REF1_DATE, A.TTK_REF1_DATED, 
															A.TTK_REF1_AM, A.TTK_REF1_PPN, A.TTK_REF2, A.TTK_REF2_DATE,
															A.TTK_DESC
														FROM tbl_ttk_detail A
															INNER JOIN tbl_ttk_header C ON C.TTK_NUM = A.TTK_NUM
																AND C.PRJCODE = '$PRJCODE'
														WHERE A.TTK_NUM = '$TTK_NUM'";
                                        $resIRc 	= $this->db->query($sqlIR)->result();
                                        
                                        if($resIRc > 0)
                                        {
                                            foreach($resIRc as $row) :
                                                $currentRow  	= ++$i;
                                                $TTK_REF1 		= $row->TTK_REF1;
                                                $TTK_REF1_DATE	= $row->TTK_REF1_DATE;
												$TTK_REF1_DATED	= $row->TTK_REF1_DATED;
                                                $TTK_REF1_AM	= $row->TTK_REF1_AM;
                                                $TTK_REF1_PPN	= $row->TTK_REF1_PPN;
												$TTK_TOTAL_AM	= $TTK_REF1_AM + $TTK_REF1_PPN;
                                                $TTK_REF2 		= $row->TTK_REF2;
                                                $TTK_REF2_DATE	= $row->TTK_REF2_DATE;
                                                $TTK_DESC 		= $row->TTK_DESC;
                                                ?>
                                                    <tr>
                                                        <td width="2%" height="25" style="text-align:left">
															<?php
																if($TTK_STAT == 1)
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
                                                        </td>
                                                        <td width="12%" style="text-align:left" nowrap>
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1" name="data[<?php echo $currentRow; ?>][TTK_REF1]" value="<?php echo $TTK_REF1; ?>" class="form-control" style="max-width:300px;">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_NUM" name="data[<?php echo $currentRow; ?>][TTK_NUM]" value="<?php echo $TTK_NUM; ?>" class="form-control" style="max-width:300px;">
                                                            <?php print $TTK_REF1; ?>
                                                        </td>
                                                        <td width="5%" style="text-align:center" nowrap>
															<?php print $TTK_REF1_DATE; ?>
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_DATE" name="data[<?php echo $currentRow; ?>][TTK_REF1_DATE]" value="<?php echo $TTK_REF1_DATE; ?>" class="form-control" style="max-width:300px;">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_DATED" name="data[<?php echo $currentRow; ?>][TTK_REF1_DATED]" value="<?php echo $TTK_REF1_DATED; ?>" class="form-control" style="max-width:300px;">
                                                        </td>
                                                        <td width="8%" style="text-align:center" nowrap>
															<?php print $TTK_REF2; ?>
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF2" name="data[<?php echo $currentRow; ?>][TTK_REF2]" value="<?php echo $TTK_REF2; ?>" class="form-control" style="max-width:300px;">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF2_DATE" name="data[<?php echo $currentRow; ?>][TTK_REF2_DATE]" value="<?php echo $TTK_REF2_DATE; ?>" class="form-control" style="max-width:300px;">
                                                        </td>
                                                        <td width="44%" nowrap>
															<?php print $TTK_DESC; ?>
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_DESC" name="data[<?php echo $currentRow; ?>][TTK_DESC]" value="<?php echo $TTK_DESC; ?>" class="form-control" style="max-width:300px;">
                                                        </td>
                                                        <td width="9%" style="text-align:right">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_AM" name="data[<?php echo $currentRow; ?>][TTK_REF1_AM]" value="<?php echo $TTK_REF1_AM; ?>" class="form-control" style="max-width:300px;">
                                                            <input type="text" id="TTK_REF1_AM<?php echo $currentRow; ?>" name="TTK_REF1_AM<?php echo $currentRow; ?>" value="<?php echo number_format($TTK_REF1_AM, 2); ?>" class="form-control" style="min-width:120px; max-width:300px; text-align:right;" readonly>
                                                        </td>
                                                        <td width="2%" style="text-align:right">
                                                            <input type="checkbox" name="chkTotal<?php echo $currentRow; ?>" id="chkTotal<?php echo $currentRow; ?>" onClick="checkPPn(<?php echo $currentRow; ?>)">
                                                        </td>
                                                        <td width="7%" style="text-align:right">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_PPN" name="data[<?php echo $currentRow; ?>][TTK_REF1_PPN]" value="<?php echo $TTK_REF1_PPN; ?>" class="form-control" style="max-width:300px;">
                                                            <input type="text" id="TTK_REF1_PPN<?php echo $currentRow; ?>" name="TTK_REF1_PPN<?php echo $currentRow; ?>" value="<?php echo number_format($TTK_REF1_PPN, 2); ?>" class="form-control" style="min-width:120px; max-width:300px; text-align:right;">
                                                        </td>
                                                        <td width="11%" style="text-align:right">
                                                            <input type="text" id="TTK_REF1_TOT<?php echo $currentRow; ?>" name="TTK_REF1_TOT<?php echo $currentRow; ?>" value="<?php echo number_format($TTK_GTOTAL, 2); ?>" class="form-control" style="min-width:120px; max-width:300px; text-align:right;">
                                                        </td>
                                                    </tr>
                                                <?php
                                            endforeach;
                                        }
                                    }
                                    /*if($task == 'add')
                                    {*/
                                        ?>
                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
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
									if($ISAPPROVE == 1 && $TTK_STAT == 2)
									{
										?>
											<button class="btn btn-primary">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
											</button>&nbsp;
										<?php
									}
									if($ISCREATE == 1 && $TTK_STAT == 1)
									{
										?>
											<button class="btn btn-primary">
												<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
											</button>&nbsp;
										<?php
									}
									$backURL	= site_url('c_purchase/c_pi180c23/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
								?>
                            </div>
                        </div>
                        <br>
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

	function add_header(IR_NUM) 
	{
		//alert(IR_NUM)
		//document.getElementById("IR_NUM1").value = IR_NUM;
		//document.frmsrch.submitSrch.click();
	}
	
	function checkInp()
	{
		totalrow	= document.getElementById('totalrow').value;
		TTK_AMOUNT	= 0;
		for(i=1; i<=totalrow; i++)
		{
			IR_AMOUNTnT	= document.getElementById('data'+i+'TTK_REF1_AM').value;
			TTK_AMOUNT	= parseFloat(TTK_AMOUNT) + parseFloat(IR_AMOUNTnT);
			document.getElementById('TTK_AMOUNT').value = TTK_AMOUNT;
		}
	}
	
	function add_IR(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		ilvl = arrItem[1];
		
		var TTK_NUM		= '<?php echo $TTK_NUM; ?>';
		
		TTK_REF1		= arrItem[0];
		TTK_REF1_DATE	= arrItem[1];
		TTK_REF1_DATED	= arrItem[2];	// Due Date
		if(TTK_REF1_DATED == '')
			TTK_REF1_DATED	= TTK_REF1_DATE;
		TTK_REF2		= arrItem[3];
		TTK_REF2_DATE	= arrItem[4];
		TTK_REF1_AM		= arrItem[5];	// Total Non PPN
		TTK_REF1_PPN	= arrItem[6];	// PPn
		TTK_REF1_TOT	= arrItem[7];	// Total and Tax/PPN
		TTK_DESC		= arrItem[8];
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		
		intIndex = parseInt(objTable.rows.length);
		document.frm.rowCount.value = intIndex;
		
		objTR 		= objTable.insertRow(intTable);
		objTR.id 	= 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
		
		// Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = ''+TTK_REF1+'<input type="hidden" id="data'+intIndex+'TTK_REF1" name="data['+intIndex+'][TTK_REF1]" value="'+TTK_REF1+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_NUM" name="data['+intIndex+'][TTK_NUM]" value="'+TTK_NUM+'" class="form-control" style="max-width:300px;">';
		
		// TTK_REF1_DATE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = ''+TTK_REF1_DATE+'<input type="hidden" id="data'+intIndex+'TTK_REF1_DATE" name="data['+intIndex+'][TTK_REF1_DATE]" value="'+TTK_REF1_DATE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF1_DATED" name="data['+intIndex+'][TTK_REF1_DATED]" value="'+TTK_REF1_DATED+'" class="form-control" style="max-width:300px;">';
		
		// TTK_REF2_DATE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+TTK_REF2+'<input type="hidden" id="data'+intIndex+'TTK_REF2" name="data['+intIndex+'][TTK_REF2]" value="'+TTK_REF2+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF2_DATE" name="data['+intIndex+'][TTK_REF2_DATE]" value="'+TTK_REF2_DATE+'" class="form-control" style="max-width:300px;">';
		
		// TTK_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+TTK_DESC+'<input type="hidden" id="data'+intIndex+'TTK_DESC" name="data['+intIndex+'][TTK_DESC]" value="'+TTK_DESC+'" class="form-control" style="max-width:300px;">';
		
		// IR_AMOUNT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="hidden" id="data'+intIndex+'TTK_REF1_AM" name="data['+intIndex+'][TTK_REF1_AM]" value="'+TTK_REF1_AM+'" class="form-control" style="max-width:300px;"><input type="text" id="TTK_REF1_AM'+intIndex+'" name="TTK_REF1_AM'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_AM)),2))+'" class="form-control" style="min-width:120px; max-width:300px; text-align:right;" readonly>';
		
		// CHECK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="checkbox" name="chkTotal'+intIndex+'" id="chkTotal'+intIndex+'" onClick="checkPPn('+intIndex+')">';
		
		// GTTax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="hidden" id="data'+intIndex+'TTK_REF1_PPN" name="data['+intIndex+'][TTK_REF1_PPN]" value="'+TTK_REF1_PPN+'" class="form-control" style="max-width:300px;"><input type="text" id="TTK_REF1_PPN'+intIndex+'" name="TTK_REF1_PPN'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_PPN)),2))+'" class="form-control" style="min-width:120px; max-width:300px; text-align:right;">';
		
		// GTotal
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = '<input type="text" id="TTK_REF1_TOT'+intIndex+'" name="TTK_REF1_TOT'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_TOT)),2))+'" class="form-control" style="min-width:120px; max-width:300px; text-align:right;">';
		
		document.getElementById('totalrow').value = intIndex;
	}
	
	function checkTotalTTK()
	{		
		totalrow		= document.getElementById('totalrow').value;
		TTK_AMOUNT		= 0;
		TTK_AMOUNT_PPN	= 0;
		TTK_GTOTAL		= 0;
		for(i=1; i<=totalrow; i++)
		{
			TTK_REF1_AM		= document.getElementById('data'+i+'TTK_REF1_AM').value;
			TTK_AMOUNT		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_REF1_AM);
			
			TTK_REF1_PPN	= document.getElementById('data'+i+'TTK_REF1_PPN').value;
			TTK_AMOUNT_PPN	= parseFloat(TTK_AMOUNT_PPN) + parseFloat(TTK_REF1_PPN);
			
			document.getElementById('TTK_AMOUNT').value 		= TTK_AMOUNT;
			document.getElementById('TTK_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT)),2));
			document.getElementById('TTK_AMOUNT_PPN').value 	= TTK_AMOUNT_PPN;
			document.getElementById('TTK_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_PPN)),2));
		}
		document.getElementById('chkTotal').checked = true;
		TTK_GTOTAL		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN);
		document.getElementById('TTK_GTOTAL').value 	= TTK_GTOTAL;
		document.getElementById('TTK_GTOTALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
	}
	
	function checkPPn(row)
	{
		chkTotal		= document.getElementById('chkTotal'+row).checked;
		if(chkTotal == true)
		{
			TTK_REF1_AM		= document.getElementById('data'+row+'TTK_REF1_AM').value;
			TTK_REF1_PPN	= parseFloat(TTK_REF1_AM) * 0.1;
			document.getElementById('data'+row+'TTK_REF1_PPN').value = TTK_REF1_PPN;
			document.getElementById('TTK_REF1_PPN'+row).value		 = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_PPN)),2));
			TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) + parseFloat(TTK_REF1_PPN);
			document.getElementById('TTK_REF1_TOT'+row).value		 = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_TOT)),2));
		}
		else
		{
			TTK_REF1_AM		= document.getElementById('data'+row+'TTK_REF1_AM').value;
			TTK_REF1_PPN	= parseFloat(TTK_REF1_AM) * 0;
			document.getElementById('data'+row+'TTK_REF1_PPN').value = TTK_REF1_PPN;
			document.getElementById('TTK_REF1_PPN'+row).value		 = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_PPN)),2));
			TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) + parseFloat(TTK_REF1_PPN);
			document.getElementById('TTK_REF1_TOT'+row).value		 = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_TOT)),2));
		}
		
		totalrow		= document.getElementById('totalrow').value;
		TTK_AMOUNT		= 0;
		TTK_AMOUNT_PPN	= 0;
		TTK_GTOTAL		= 0;
		for(i=1; i<=totalrow; i++)
		{
			TTK_REF1_AM		= document.getElementById('data'+i+'TTK_REF1_AM').value;
			TTK_AMOUNT		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_REF1_AM);
			
			TTK_REF1_PPN	= document.getElementById('data'+i+'TTK_REF1_PPN').value;
			TTK_AMOUNT_PPN	= parseFloat(TTK_AMOUNT_PPN) + parseFloat(TTK_REF1_PPN);
			
			document.getElementById('TTK_AMOUNT').value 		= TTK_AMOUNT;
			document.getElementById('TTK_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT)),2));
			document.getElementById('TTK_AMOUNT_PPN').value 	= TTK_AMOUNT_PPN;
			document.getElementById('TTK_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_PPN)),2));
		}
		document.getElementById('chkTotal').checked = true;
		TTK_GTOTAL		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN);
		document.getElementById('TTK_GTOTAL').value 	= TTK_GTOTAL;
		document.getElementById('TTK_GTOTALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
		
		objTable 		= document.getElementById('tblINV');
		intTable 		= objTable.rows.length;
		
		document.getElementById('IR_NUM1').value = '';
		
		for(i=1; i<=intTable; i++)
		{
			INV_CODEH	= document.getElementById('INV_CODEH'+i).value;
			IR_NUM1 		= document.getElementById('IR_NUM1').value;
			if(IR_NUM1 == '')
				document.getElementById('IR_NUM1').value = INV_CODEH;
			else
				document.getElementById('IR_NUM1').value = IR_NUM1+'~'+INV_CODEH;
		}
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