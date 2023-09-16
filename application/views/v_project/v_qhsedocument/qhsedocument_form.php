<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Februari 2017
 * File Name	= hrdocument_form.php
 * Location		= -
*/
?>
<?php
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

if($task == 'add')
{
	$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		
	foreach($viewDocPattern as $row) :
		$Pattern_Code 		= $row->Pattern_Code;
		$Pattern_Position 	= $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive= $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		$Pattern_Length 	= $row->Pattern_Length;
		$useYear 			= $row->useYear;
		$useMonth 			= $row->useMonth;
		$useDate 			= $row->useDate;
	endforeach;
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	$yearx		= substr((int)$Pattern_YearAktive, -2);
	$year 		= (int)$Pattern_YearAktive;
	$month 		= (int)$Pattern_MonthAktive;
	$date 		= (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	$this->db->where('Patt_Month', $month);
	$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_qhsedoc_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_qhsedoc_header
			WHERE Patt_Year = $year";
	$result = $this->db->query($sql)->result();
	
	if($result>0)
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
		$groupPattern = "$yearx$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$yearx$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$yearx$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$yearx";
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
	$lastPatternNumb 	= $nol.$lastPatternNumb;
	$DocNumber 			= "$Pattern_Code$DOCCODE-$lastPatternNumb";
	$HRDOCNO			= "$Pattern_Code$DOCCODE-$lastPatternNumb";
	
	$HRDOCNO			= $HRDOCNO;
	$DocNumber			= $HRDOCNO;
	$HRDOCCODE			= '';
	$DOCCODE			= $DOCCODE;
	$HRDOCTYPE			= '';
	$YearD 				= date('Y');
	$MonthD 			= date('m');
	$DaysD 				= date('d');
	$TRXDATE			= "$MonthD/$DaysD/$YearD";
	$PRJCODE			= '';
	$OWNER_CODE			= '';
	$OWNER_DESC			= '';
	$OWNER_ADD			= '';
	$HRDOCCOSTX			= 0;
	$HRDOCCOST 			= str_replace(',', '', $HRDOCCOSTX);
	$HRDOCJNS			= 1;
	$HRDOCJML			= 1;
	$HRDOCLBR			= 1;
	$HRDOCLOK			= '';
	$TRXUSER			= $DefEmp_ID;
	$START_DATE			= $TRXDATE;
	$END_DATE			= $TRXDATE;
	if($START_DATE == '')
	{
		$SHOW_SE_DATE	= 0;
		$START_DATE		= date("m/d/Y", strtotime($TRXDATEX));
		$END_DATE		= date("m/d/Y", strtotime($TRXDATEX));
	}
	else
	{
		$SHOW_SE_DATE	= 1;
		$START_DATE		= date("m/d/Y", strtotime($START_DATE));
		$END_DATE		= date("m/d/Y", strtotime($END_DATE));
	
	}
	$HRDOCSTAT			= 1;
	$HRDOC_NAME			= '';
	$PM_EMPCODE			= '';
	$PM_NAME			= '';
	$PM_STATUS			= '';
	$DIR_EMPCODE		= '';
	$DIR_NAME			= '';
	$STATUS_DOK			= 'Ready';
	$BORROW_EMP			= '';
	$PEMILIK_MODAL		= '';
	$HRDOC_NOTE			= '';	
	$Patt_Year 			= date('Y');
	$Patt_Month			= date('m');
	$Patt_Date 			= date('Y');
	$Patt_Number		= $myMax;
	$SHOW_SE_DATE		= 1;
	$lastPatternNumb1	= $lastPatternNumb1;
	
	$PRJNAME 			= '';
	$sql 				= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$result 			= $this->db->query($sql)->result();
	foreach($result as $row) :
		$PRJNAME 		= $row ->PRJNAME;
	endforeach;
	$isuserfile			= 1;
}
else
{
	$HRDOCNO			= $default['HRDOCNO'];
	$DocNumber			= $default['HRDOCNO'];
	$HRDOCCODE			= $default['HRDOCCODE'];
	$DOCCODE			= $default['DOCCODE'];
	$HRDOCTYPE			= $default['HRDOCTYPE'];
	$TRXDATEX			= $default['TRXDATE'];
	$SHOW_SE_DATE		= $default['SHOW_SE_DATE'];
	$TRXDATE			= date("m/d/Y", strtotime($TRXDATEX));
	$PRJCODE			= $default['PRJCODE'];
	$OWNER_CODE			= $default['OWNER_CODE'];
	$OWNER_DESC			= $default['OWNER_DESC'];
	$OWNER_ADD			= $default['OWNER_ADD'];
	$HRDOCCOSTX			= $default['HRDOCCOST'];
	$HRDOCCOST 			= str_replace(',', '', $HRDOCCOSTX);
	$HRDOCJNS			= $default['HRDOCJNS'];
	$HRDOCJML			= $default['HRDOCJML'];
	$HRDOCLBR			= $default['HRDOCLBR'];
	$HRDOCLOK			= $default['HRDOCLOK'];
	$TRXUSER			= $default['TRXUSER'];
	$START_DATE			= $default['START_DATE'];
	$END_DATE			= $default['END_DATE'];
	if($START_DATE == '')
	{
		$START_DATE		= date("m/d/Y", strtotime($TRXDATEX));
		$END_DATE		= date("m/d/Y", strtotime($TRXDATEX));
	}
	else
	{
		$START_DATE		= date("m/d/Y", strtotime($START_DATE));
		$END_DATE		= date("m/d/Y", strtotime($END_DATE));
	
	}
	$HRDOCSTAT			= $default['HRDOCSTAT'];
	$HRDOC_NAME			= $default['HRDOC_NAME'];
	
	$isuserfile			= 0;
	if($HRDOC_NAME != '')
		$isuserfile		= 1;
		
	$HRDOC_TEMPL		= $default['HRDOC_TEMPL'];
	$PM_EMPCODE			= $default['PM_EMPCODE'];
	$PM_NAME			= $default['PM_NAME'];
	$PM_STATUS			= $default['PM_STATUS'];
	$DIR_EMPCODE		= $default['DIR_EMPCODE'];
	$DIR_NAME			= $default['DIR_NAME'];
	$STATUS_DOK			= $default['STATUS_DOK'];
	$BORROW_EMP			= $default['BORROW_EMP'];
	$PEMILIK_MODAL		= $default['PEMILIK_MODAL'];
	$HRDOC_NOTE			= $default['HRDOC_NOTE'];
	$Patt_Date 			= $default['Patt_Date'];
	$Patt_Month 		= $default['Patt_Month'];
	$Patt_Year 			= $default['Patt_Year'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPatternNumb1	= $default['Patt_Number'];
	
	$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	foreach($result as $row) :
		$PRJNAME = $row ->PRJNAME;
	endforeach;
}
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
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small><?php echo "$doc_name"; ?></small>  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Main Information</h3>
        </div>
<form class="form-horizontal" name="frm" id="frm" method="POST" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
	<input type="Hidden" name="rowCount" id="rowCount" value="0">
                <table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
                    <tr>
                        <td width="14%" align="left" class="style1">&nbsp;&nbsp;Doc. Code</td>
                        <td width="1%" align="left" class="style1">:</td>
                        <td align="left" nowrap class="style1">
                            <input type="text" name="DocNumber" id="DocNumber" value="<?php echo "$DocNumber"; ?>" class="form-control" style="max-width:150px" disabled>
                            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>" class="form-control">
                            <input type="hidden" name="HRDOCNO" id="HRDOCNO" value="<?php echo $HRDOCNO; ?>" class="form-control">
                            <input type="hidden" name="DOCCODE" id="DOCCODE" value="<?php echo $DOCCODE; ?>" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td width="14%" align="left" class="style1">&nbsp;&nbsp;Doc. No.</td>
                        <td width="1%" align="left" class="style1">:</td>
                        <td align="left" nowrap class="style1">
                            <input type="text" name="HRDOCCODE" id="HRDOCCODE" value="<?php echo $HRDOCCODE; ?>" class="form-control" style="max-width:250px">                </td>
                    </tr>
                    <tr>
                        <td width="14%" align="left" class="style1">&nbsp;&nbsp;Doc. Date</td>
                        <td width="1%" align="left" class="style1">:</td>
                        <td align="left" nowrap class="style1">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                <input type="text" name="TRXDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $TRXDATE; ?>" style="width:150px">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1">
                            <input type="checkbox" name="SHOW_SE_DATE" id="SHOW_SE_DATE" value="<?php echo $SHOW_SE_DATE; ?>" <?php if($SHOW_SE_DATE == 1) { ?> checked <?php } ?> onClick="showSEdATE(this);">
                            &nbsp;&nbsp;Check to Show Start and End Date.
                        </td>
                    </tr>
                    <script>
                        function showSEdATE(thisVal)
                        {
                            if(thisVal.checked)
                            {
                                document.getElementById("s_Date").style.display = '';
                                document.getElementById("e_Date").style.display = '';
                            }
                            else
                            {
                                document.getElementById("s_Date").style.display = 'none';
                                document.getElementById("e_Date").style.display = 'none';
                            }
                        }
                    </script>
                    <tr id="s_Date" <?php if($SHOW_SE_DATE == 0) { ?> style="display:none" <?php } ?>>
                        <td width="14%" align="left" class="style1">&nbsp;&nbsp;Start Date</td>
                        <td width="1%" align="left" class="style1">:</td>
                        <td align="left" nowrap class="style1">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                <input type="text" name="START_DATE" class="form-control pull-left" id="datepicker2" value="<?php echo $START_DATE; ?>" style="width:150px">
                            </div>
                        </td>
                    </tr>
                    <tr id="e_Date" <?php if($SHOW_SE_DATE == 0) { ?> style="display:none" <?php } ?>>
                        <td width="14%" align="left" class="style1">&nbsp;&nbsp;End Date</td>
                        <td width="1%" align="left" class="style1">:</td>
                        <td align="left" nowrap class="style1">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                <input type="text" name="END_DATE" class="form-control pull-left" id="datepicker3" value="<?php echo $END_DATE; ?>" style="width:150px">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;Doc. Type</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" nowrap><span class="style1">
                          <select name="HRDOCTYPE" id="HRDOCTYPE" class="form-control" style="max-width:100px">
                            <option value="1" <?php if($HRDOCTYPE == 1) { ?>selected <?php } ?>> ASLI </option>
                            <option value="2" <?php if($HRDOCTYPE == 2) { ?>selected <?php } ?>> COPY </option>
                          </select>
                        </span></td> 
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;Doc. Page Qty</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" nowrap>
                            <input type="text" class="form-control" name="HRDOCJMLX" id="HRDOCJMLX" style="text-align:right; max-width:50px" value="<?php echo number_format($HRDOCJML, 0); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getHRDOCJML(this)">
                            <input type="hidden" class="form-control" name="HRDOCJML" id="HRDOCJML" style="text-align:right" value="<?php echo $HRDOCJML; ?>" size="10">            </td> 
                    </tr>
                    <script>
                        function getHRDOCJML(thisVal)
                        {
                            var decFormat	= document.getElementById('decFormat').value;
                            var thisVal		= eval(thisVal).value.split(",").join("");
                            HRDOCJML			= thisVal;
                            document.getElementById('HRDOCJML').value 	= HRDOCJML;
                            document.getElementById('HRDOCJMLX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(HRDOCJML)),decFormat));
                        }
                    </script>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;Doc. Group</td>
                        <td align="left" class="style1">:</td>
                        <td align="left">
                          <select name="HRDOCLBR" id="HRDOCLBR" class="form-control" style="max-width:120px">
                                <option value="1" <?php if($HRDOCLBR == 1) { ?>selected <?php } ?>> LEMBAR </option>
                                <option value="2" <?php if($HRDOCLBR == 2) { ?>selected <?php } ?>> BUKU </option>
                                <option value="3" <?php if($HRDOCLBR == 3) { ?>selected <?php } ?>> BUKU TIPIS </option>
                          </select>
                        </td> 
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;Location</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" class="style1">
                            <input type="text" class="form-control" name="HRDOCLOK" id="HRDOCLOK" value="<?php print $HRDOCLOK; ?>" style="max-width:250px" >				</td> 
                    </tr>
                </table><BR>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Others Information</h3>
                </div>
                <table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
                    <tr>
                        <td width="14%" align="left" class="style1">&nbsp;&nbsp;Project Name</td>
                        <td width="1%" align="left" class="style1">:</td>
                        <td align="left" class="style1">
                            <select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px">
                            	 <option value="0">--- None ---</option>
                                <?php
                                    $own_Code 	= '';
                                    $CountPRJ 	= $this->db->count_all('tbl_project');
                                    $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project";
                                    $resultPRJ = $this->db->query($sqlPRJ)->result();
                                    if($CountPRJ > 0)
                                    {
                                        foreach($resultPRJ as $rowPRJ) :
                                            $PRJCODEA = $rowPRJ->PRJCODE;
                                            $PRJNAMEA = $rowPRJ->PRJNAME;
                                            ?>
                                                <option value="<?php echo $PRJCODEA; ?>" <?php if($PRJCODEA == $PRJCODE) { ?>selected <?php } ?>>
                                                    <?php echo "$PRJCODEA - $PRJNAMEA"; ?>                                                </option>
                                            <?php
                                         endforeach;
                                     }
                                ?>
                            </select>                        </td>
                    </tr>
					<?php
                        if($task == 'add')
                        {
                        ?>
                            <tr>
                                <td align="left" class="style1">&nbsp;&nbsp;Owner Name</td>
                                <td align="left" class="style1">:</td>
                                <td align="left" class="style1">
                                    <select name="OWNER_CODE" id="OWNER_CODE" class="form-control" style="max-width:350px">
                                        <option value="">--- None ---</option>
                                        <?php
                                            $CountOWN 	= $this->db->count_all('tbl_owner');
                                            $sqlOWN 	= "SELECT own_Code, own_Name FROM tbl_owner ORDER BY own_Name ASC";
                                            $resultOWN = $this->db->query($sqlOWN)->result();
                                            if($CountOWN > 0)
                                            {
                                                foreach($resultOWN as $rowOWN) :
                                                    $own_CodeA 	= $rowOWN->own_Code;
                                                    $own_Name = $rowOWN->own_Name;
                                                    ?>
                                                        <option value="<?php echo $own_CodeA; ?>" <?php if($own_CodeA == $OWNER_CODE) { ?>selected <?php } ?>>
                                                            <?php echo "$own_Name"; ?>
                                                        </option>
                                                    <?php
                                                 endforeach;
                                             }
                                        ?>
                                    </select>
                                    <input type="hidden" name="OWNER_DESC" id="OWNER_DESC" size="15" value="<?php echo $OWNER_DESC; ?>" class="form-control" />
                                </td>
                            </tr>                                    
                        <?php
                        }
                        else
                        {
                            if($OWNER_CODE != '')
                            {
                            ?>
                                <tr>
                                    <td align="left" class="style1">&nbsp;&nbsp;Owner Name</td>
                                    <td align="left" class="style1">:</td>
                                    <td align="left" class="style1">
                                        <select name="OWNER_CODE" id="OWNER_CODE" class="form-control" style="max-width:350px">
                                            <option value="">--- None ---</option>
                                            <?php
                                                $CountOWN 	= $this->db->count_all('tbl_owner');
												$sqlOWN 	= "SELECT own_Code, own_Name FROM tbl_owner ORDER BY own_Name ASC";
												$resultOWN = $this->db->query($sqlOWN)->result();
												if($CountOWN > 0)
												{
													foreach($resultOWN as $rowOWN) :
														$own_CodeB 	= $rowOWN->own_Code;
														$own_NameB 	= $rowOWN->own_Name;
														?>
															<option value="<?php echo $own_CodeB; ?>" <?php if($own_CodeB == $OWNER_CODE) { ?>selected <?php } ?>>
																<?php echo "$own_NameB"; ?>
															</option>
														<?php
													 endforeach;
												 }
                                            ?>
                                        </select>
                                        <input type="hidden" name="OWNER_DESC" id="OWNER_DESC" size="15" value="<?php echo $OWNER_DESC; ?>" class="form-control" />
                                    </td>
                                </tr>
                            <?php
                            }
                            else
                            {
                            ?>
                                <tr>
                                    <td align="left" class="style1">&nbsp;&nbsp;Owner Name</td>
                                    <td align="left" class="style1">:</td>
                                    <td align="left" class="style1">
                                        <select name="OWNER_CODE" id="OWNER_CODE" class="form-control" style="max-width:350px">
                                            <option value="">--- None ---</option>
                                            <?php
                                                $$CountOWN 	= $this->db->count_all('tbl_owner');
												$sqlOWN 	= "SELECT own_Code, own_Name FROM tbl_owner ORDER BY own_Name ASC";
												$resultOWN = $this->db->query($sqlOWN)->result();
												if($CountOWN > 0)
												{
													foreach($resultOWN as $rowOWN) :
														$own_CodeC 	= $rowOWN->own_Code;
														$own_NameC 	= $rowOWN->own_Name;
														?>
															<option value="<?php echo $own_CodeC; ?>" <?php if($own_CodeC == $OWNER_CODE) { ?>selected <?php } ?>>
																<?php echo "$own_NameC"; ?>
															</option>
														<?php
													 endforeach;
												 }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" class="style1">&nbsp;&nbsp;PM Description</td>
                                    <td align="left" class="style1">:</td>
                                    <td align="left" class="style1">
                                        <input type="text" name="OWNER_DESC" id="OWNER_DESC" size="15" value="<?php echo $OWNER_DESC; ?>" class="form-control" style="max-width:350px" />
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                    ?>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;Owner Address</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" class="style1">
                            <textarea class="form-control" name="OWNER_ADD"  id="OWNER_ADD" style="max-width:350px;height:70px"><?php echo $OWNER_ADD; ?></textarea>                        </td>
                 	</tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;Document Value (IDR)</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" class="style1">
                            <input type="text" name="HRDOCCOSTX" id="HRDOCCOSTX" size="15" value="<?php echo number_format($HRDOCCOST, 0); ?>" class="form-control" style="text-align:right; max-width:150px" onKeyPress="return isIntOnlyNew(event);" onBlur="getHRDOCCOST(this)" />
                            <input type="hidden" name="HRDOCCOST" id="HRDOCCOST" size="15" value="<?php echo $HRDOCCOST; ?>" class="form-control" style="text-align:right" />                        </td>
                    </tr>
                    <tr>
                        <td align="left" class="style1" style="vertical-align:top">&nbsp;&nbsp;Description</td>
                        <td align="left" class="style1" style="vertical-align:top">:</td>
                        <td align="left" class="style1">
                            <textarea class="form-control" name="HRDOC_NOTE"  id="HRDOC_NOTE" style="max-width:350px;height:70px"><?php echo $HRDOC_NOTE; ?></textarea>                        </td>
                    </tr>
					<?php
                        if($task == 'add')
                        {
                        ?>
                            <tr>
                                <td align="left" class="style1">&nbsp;&nbsp;PM Name</td>
                                <td align="left" class="style1">:</td>
                                <td align="left" class="style1">
                                    <select name="PM_EMPCODE" id="PM_EMPCODE" class="form-control" style="max-width:350px">
                                        <option value="">--- None ---</option>
                                        <?php
                                            $CountEMP 	= $this->db->count_all('tbl_employee');
                                            $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee";
                                            $resultEMP = $this->db->query($sqlEMP)->result();
                                            if($CountEMP > 0)
                                            {
                                                foreach($resultEMP as $rowEMP) :
                                                    $EMP_IDA 	= $rowEMP->EMP_ID;
                                                    $First_Name = $rowEMP->First_Name;
                                                    $Last_Name 	= $rowEMP->Last_Name;
                                                    ?>
                                                        <option value="<?php echo $EMP_IDA; ?>" <?php if($EMP_IDA == $PM_EMPCODE) { ?>selected <?php } ?>>
                                                            <?php echo "$First_Name $Last_Name"; ?>
                                                        </option>
                                                    <?php
                                                 endforeach;
                                             }
                                        ?>
                                    </select>
                                    <input type="hidden" name="PM_NAME" id="PM_NAME" size="15" value="<?php echo $PM_NAME; ?>" class="form-control" />
                                </td>
                            </tr>                                    
                        <?php
                        }
                        else
                        {
                            if($PM_EMPCODE != '')
                            {
                            ?>
                                <tr>
                                    <td align="left" class="style1">&nbsp;&nbsp;PM Name</td>
                                    <td align="left" class="style1">:</td>
                                    <td align="left" class="style1">
                                        <select name="PM_EMPCODE" id="PM_EMPCODE" class="form-control" style="max-width:350px">
                                            <option value="">--- None ---</option>
                                            <?php
                                                $CountEMP 	= $this->db->count_all('tbl_employee');
                                                $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee";
                                                $resultEMP = $this->db->query($sqlEMP)->result();
                                                if($CountEMP > 0)
                                                {
                                                    foreach($resultEMP as $rowEMP) :
                                                        $EMP_IDA 	= $rowEMP->EMP_ID;
                                                        $First_Name = $rowEMP->First_Name;
                                                        $Last_Name 	= $rowEMP->Last_Name;
                                                        ?>
                                                            <option value="<?php echo $EMP_IDA; ?>" <?php if($EMP_IDA == $PM_EMPCODE) { ?>selected <?php } ?>>
                                                                <?php echo "$First_Name $Last_Name"; ?>
                                                            </option>
                                                        <?php
                                                     endforeach;
                                                 }
                                            ?>
                                        </select>
                                        <input type="hidden" name="PM_NAME" id="PM_NAME" size="15" value="<?php echo $PM_NAME; ?>" class="form-control" />
                                    </td>
                                </tr>
                            <?php
                            }
                            else
                            {
                            ?>
                                <tr>
                                    <td align="left" class="style1">&nbsp;&nbsp;PM Name</td>
                                    <td align="left" class="style1">:</td>
                                    <td align="left" class="style1">
                                        <select name="PM_EMPCODE" id="PM_EMPCODE" class="form-control" style="max-width:350px">
                                            <option value="">--- None ---</option>
                                            <?php
                                                $CountEMP 	= $this->db->count_all('tbl_employee');
                                                $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee";
                                                $resultEMP = $this->db->query($sqlEMP)->result();
                                                if($CountEMP > 0)
                                                {
                                                    foreach($resultEMP as $rowEMP) :
                                                        $EMP_IDA 	= $rowEMP->EMP_ID;
                                                        $First_Name = $rowEMP->First_Name;
                                                        $Last_Name 	= $rowEMP->Last_Name;
                                                        ?>
                                                            <option value="<?php echo $EMP_IDA; ?>" <?php if($EMP_IDA == $PM_EMPCODE) { ?>selected <?php } ?>>
                                                                <?php echo "$First_Name $Last_Name"; ?>
                                                            </option>
                                                        <?php
                                                     endforeach;
                                                 }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" class="style1">&nbsp;&nbsp;PM Description</td>
                                    <td align="left" class="style1">:</td>
                                    <td align="left" class="style1">
                                        <input type="text" name="PM_NAME" id="PM_NAME" size="15" value="<?php echo $PM_NAME; ?>" class="form-control" style="max-width:350px" />
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                    ?>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;PM Status</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" class="style1">
                           <input type="text" name="PM_STATUS" id="PM_STATUS" size="15" value="<?php echo $PM_STATUS; ?>" class="form-control" style="max-width:250px">
						</td>
                    </tr>
					<?php
                        if($task == 'add')
                        {
                        ?>
                            <tr>
                                <td align="left" class="style1">&nbsp;&nbsp;Dir. Name</td>
                                <td align="left" class="style1">:</td>
                                <td align="left" class="style1">
                                    <select name="DIR_EMPCODE" id="DIR_EMPCODE" class="form-control" style="max-width:350px">
                                        <option value="">--- None ---</option>
                                        <?php
                                            $CountEMP 	= $this->db->count_all('tbl_employee');
                                            $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee";
                                            $resultEMP = $this->db->query($sqlEMP)->result();
                                            if($CountEMP > 0)
                                            {
                                                foreach($resultEMP as $rowEMP) :
                                                    $EMP_IDA 	= $rowEMP->EMP_ID;
                                                    $First_Name = $rowEMP->First_Name;
                                                    $Last_Name 	= $rowEMP->Last_Name;
                                                    ?>
                                                        <option value="<?php echo $EMP_IDA; ?>" <?php if($EMP_IDA == $DIR_EMPCODE) { ?>selected <?php } ?>>
                                                            <?php echo "$First_Name $Last_Name"; ?>
                                                        </option>
                                                    <?php
                                                 endforeach;
                                             }
                                        ?>
                                    </select>
                                    <input type="hidden" name="DIR_NAME" id="DIR_NAME" size="15" value="<?php echo $DIR_NAME; ?>" class="form-control" />
                                </td>
                            </tr>                                    
                        <?php
                        }
                        else
                        {
                            if($DIR_EMPCODE != '')
                            {
                            ?>
                                <tr>
                                    <td align="left" class="style1">&nbsp;&nbsp;Dir. Name</td>
                                    <td align="left" class="style1">:</td>
                                    <td align="left" class="style1">
                                        <select name="DIR_EMPCODE" id="DIR_EMPCODE" class="form-control" style="max-width:350px">
                                            <option value="">--- None ---</option>
                                            <?php
                                                $CountEMP 	= $this->db->count_all('tbl_employee');
                                                $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee";
                                                $resultEMP = $this->db->query($sqlEMP)->result();
                                                if($CountEMP > 0)
                                                {
                                                    foreach($resultEMP as $rowEMP) :
                                                        $EMP_IDA 	= $rowEMP->EMP_ID;
                                                        $First_Name = $rowEMP->First_Name;
                                                        $Last_Name 	= $rowEMP->Last_Name;
                                                        ?>
                                                            <option value="<?php echo $EMP_IDA; ?>" <?php if($EMP_IDA == $DIR_EMPCODE) { ?>selected <?php } ?>>
                                                                <?php echo "$First_Name $Last_Name"; ?>
                                                            </option>
                                                        <?php
                                                     endforeach;
                                                 }
                                            ?>
                                        </select>
                                        <input type="hidden" name="DIR_NAME" id="DIR_NAME" size="15" value="<?php echo $DIR_NAME; ?>" class="form-control" />
                                    </td>
                                </tr>
                            <?php
                            }
                            else
                            {
                            ?>
                                <tr>
                                    <td align="left" class="style1">&nbsp;&nbsp;Dir. Name</td>
                                    <td align="left" class="style1">:</td>
                                    <td align="left" class="style1">
                                        <select name="DIR_EMPCODE" id="DIR_EMPCODE" class="form-control" style="max-width:350px">
                                            <option value="">--- None ---</option>
                                            <?php
                                                $CountEMP 	= $this->db->count_all('tbl_employee');
                                                $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee";
                                                $resultEMP = $this->db->query($sqlEMP)->result();
                                                if($CountEMP > 0)
                                                {
                                                    foreach($resultEMP as $rowEMP) :
                                                        $EMP_IDA 	= $rowEMP->EMP_ID;
                                                        $First_Name = $rowEMP->First_Name;
                                                        $Last_Name 	= $rowEMP->Last_Name;
                                                        ?>
                                                            <option value="<?php echo $EMP_IDA; ?>" <?php if($EMP_IDA == $DIR_EMPCODE) { ?>selected <?php } ?>>
                                                                <?php echo "$First_Name $Last_Name"; ?>
                                                            </option>
                                                        <?php
                                                     endforeach;
                                                 }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" class="style1">&nbsp;&nbsp;Dir. Description</td>
                                    <td align="left" class="style1">:</td>
                                    <td align="left" class="style1">
                                        <input type="text" name="DIR_NAME" id="DIR_NAME" size="15" value="<?php echo $DIR_NAME; ?>" class="form-control" style="max-width:350px" />
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                    ?>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;Dok. Position</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" nowrap><span class="style1">
                        <select name="HRDOCSTAT" id="HRDOCSTAT" class="form-control" style="max-width:150px" onChange="showEMPID(this.value)">
                            <option value="1" <?php if($HRDOCSTAT == 1) { ?>selected <?php } ?>> READY </option>
                            <option value="2" <?php if($HRDOCSTAT == 2) { ?>selected <?php } ?>> BORROWED </option>
                            <option value="2" <?php if($HRDOCSTAT == 3) { ?>selected <?php } ?>> DISAPPEAR </option>
                        </select>
                        </span></td> 
                    </tr>
                    <tr id="showBorrowed" <?php if($BORROW_EMP == '') { ?> style="display:none" <?php } ?>>
                        <td align="left" class="style1">&nbsp;&nbsp;Borrower</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" class="style1">                            
                            <select name="BORROW_EMP" id="BORROW_EMP" class="form-control" style="max-width:350px">
                                <option value="">--- None ---</option>
                                <?php
                                    $CountEMP 	= $this->db->count_all('tbl_employee');
                                    $sqlEMP 	= "SELECT EMP_ID, First_Name, Last_Name FROM tbl_employee";
                                    $resultEMP = $this->db->query($sqlEMP)->result();
                                    if($CountEMP > 0)
                                    {
                                        foreach($resultEMP as $rowEMP) :
                                            $EMP_IDX 	= $rowEMP->EMP_ID;
                                            $First_Name = $rowEMP->First_Name;
                                            $Last_Name 	= $rowEMP->Last_Name;
                                            ?>
                                                <option value="<?php echo $EMP_IDX; ?>" <?php if($EMP_IDX == $BORROW_EMP) { ?>selected <?php } ?>>
                                                    <?php echo "$First_Name $Last_Name"; ?>
                                                </option>
                                            <?php
                                         endforeach;
                                     }
                                ?>
                            </select>
						</td>
                  	</tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;Doc. File</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" class="style1">
                        	<input type="file" name="userfile" id="userfile" class="filestyle" data-buttonName="btn-primary"/>
                        	<input type="hidden" name="istask" id="istask" value="<?php echo $task; ?>"/>
                        	<input type="hidden" name="isuserfile" id="isuserfile" value="<?php echo $isuserfile; ?>"/>     				
                        </td> 
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;&nbsp;Upload Template</td>
                        <td align="left" class="style1">:</td>
                        <td align="left" class="style1"><input type="file" name="userfile1" id="userfile1" class="filestyle" data-buttonName="btn-primary" accept="rar/zip"/>        				</td> 
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1"><hr></td>
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1">
                        <input type="submit" class="btn btn-primary" name="submit" id="submit" value="<?php if($task=='add')echo 'save'; else echo 'update';?>" align="left" />&nbsp;
                        <?php 
                                if ( ! empty($link))
                                {
                                    foreach($link as $links)
                                    {
                                        echo $links;
                                    }
                                }
                            ?></td>
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1"><hr></td>
                    </tr>
            	</table>
			</div>
      </form>
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
	function showEMPID(thisVal)
	{
		document.getElementById("showBorrowed").style.display = '';
	}
	
	function getHRDOCCOST(thisVal)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var thisVal		= eval(thisVal).value.split(",").join("");
		HRDOCCOST		= thisVal;
		document.getElementById('HRDOCCOST').value 	= HRDOCCOST;
		document.getElementById('HRDOCCOSTX').value 	= doDecimalFormatxx(RoundNDecimal(parseFloat(Math.round(HRDOCCOST)),decFormat));
	}
						
	function validateInData()
	{
		HRDOCCODE = document.getElementById('HRDOCCODE').value;
		if(HRDOCCODE == '')
		{
			alert('Please input Document No.');
			document.getElementById('HRDOCCODE').value = '';
			document.getElementById('HRDOCCODE').focus();
			return false;
		}
		
		userfile 	= document.getElementById('userfile').value;
		isuserfile 	= document.getElementById('isuserfile').value;
		istask 		= document.getElementById('istask').value;
		
		if(istask == 'edit')
		{
			if(isuserfile == 0 && userfile == '')
			{
				alert('Please select a File or Document.');
				document.getElementById('userfile').focus();
				return false;
			}
		}
		else
		{
			if(userfile == '')
			{
				alert('Please select a File or Document.');
				document.getElementById('userfile').focus();
				return false;
			}
		}
	}
	
	var decFormat		= 2;
	
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