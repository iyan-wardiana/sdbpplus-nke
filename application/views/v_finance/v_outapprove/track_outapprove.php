<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 Februari 2017
 * File Name	= track_outapprove.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$sql 		= "SELECT PRJCODE, PRJNAME, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$sqlR		= $this->db->query($sql)->result();
foreach($sqlR as $rowR) :
	$PRJCODE		= $rowR->PRJCODE;
	$PRJNAME		= $rowR->PRJNAME;
	$PRJLOCT		= $rowR->PRJLOCT;
endforeach;

$this->db->select('Display_Rows,decFormat,isUpdOutApp');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows 	= $row->Display_Rows;
	$decFormat 		= $row->decFormat;
	$isUpdOutApp 	= $row->isUpdOutApp;
endforeach;
$empID = $this->session->userdata('Emp_ID');

$sqlGLU		= "SELECT OA_Update FROM tout_approve GROUP BY OA_Update";
$sqlResGLU	= $this->db->query($sqlGLU)->result();
foreach($sqlResGLU as $rowGLU) :
	$OA_Update	= $rowGLU->OA_Update;
endforeach;
$lastUpdate 	= new DateTime($OA_Update);
$lastUpdateDate	= $lastUpdate->format('F d, Y');
$lastUpdateTime	= $lastUpdate->format('H:i:s');
$lastUpdateDesc	= "$lastUpdateDate. Time $lastUpdateTime";

$empID = $this->session->userdata('Emp_ID');
$LHint = $this->session->userdata('log_passHint');

if($selSearchTypex == 'DP_HD') { $dbDesc = "DP"; }
elseif($selSearchTypex == 'LPMHD') { $dbDesc = "LPM"; }
elseif($selSearchTypex == 'OP_HD') { $dbDesc = "OP"; }
elseif($selSearchTypex == 'OPNHD') { $dbDesc = "OPNAME"; }
elseif($selSearchTypex == 'PD_HD') { $dbDesc = "PD"; }
elseif($selSearchTypex == 'SPKHD') { $dbDesc = "SPK"; }
elseif($selSearchTypex == 'SPPHD') { $dbDesc = "SPP"; }
elseif($selSearchTypex == 'VLKHD') { $dbDesc = "VLK"; }
elseif($selSearchTypex == 'VOCHD') { $dbDesc = "VOUCHER"; }
elseif($selSearchTypex == 'VOTHD') { $dbDesc = "VOT"; }
elseif($selSearchTypex == 'VTPHD') { $dbDesc = "VTP"; }
else{ $dbDesc = ""; }

$showAppDate		= 0;
if($selSearchTypex == 'OP_HD' && $SelIsApprove == 1)
{
	$showAppDate	= 1;
}
if($selSearchTypex == 'SPPHD' && $SelIsApprove == 1)
{
	$showAppDate	= 1;
}

$viewCond = 1;
if($LHint == 'DH' || $LHint == 'RN' || $LHint == 'HR' || $LHint == 'LN' || $LHint == 'PRY' || $LHint == 'DUA')
{
	$viewCond = "100";			// ALL
}
elseif($LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF')
{
	$viewCond = "99";			// SPP, SPK
}
elseif($LHint == 'MD' || $LHint == 'MG' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR')
{
	$viewCond = "98";			// LPM, OP, OPN, VOC, VOT
}
elseif($LHint == 'UP' || $LHint == 'EA')
{
	$viewCond = "97";			// ALL, EXCEPT PD, DP
}
elseif($LHint == 'OP')
{
	$viewCond = "96";			// PO ONLY
}

// Project List
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$sqlPLC	= "tbl_project";
$resPLC	= $this->db->count_all($sqlPLC);

$sqlPL 	= "SELECT PRJCODE, PRJNAME
			FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
			ORDER BY PRJCODE";
$resPL	= $this->db->query($sqlPL)->result();
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
	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'exporttoexcel')$exporttoexcel = $LangTransl;
		if($TranslCode == 'Search')$Search = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'FilterType')$FilterType = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
		if($TranslCode == 'InputDocNo')$InputDocNo = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small>project</small>
  </h1>
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
            <h3 class="box-title">Seacrh Filter</h3>
        </div>
        <div class="box-body">
            <form name="frm" id="frm" method="post" action="<?php echo $srch_url; ?>" onSubmit="return validateInData();">
                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                <input type="hidden" name="rowCount" id="rowCount" value="0">
                <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
                <input type="hidden" name="isexcel" id="isexcel" value="0">
                <table width="100%" border="0" style="size:auto" bgcolor="#FFFFFF">
                    <tr>
                        <td align="left" class="style1" nowrap><?php echo $Project ?></td>
                        <td align="left" class="style1">:</td>
                        <td colspan="4" align="left">
                            <select name="selSearchproj_Code" id="selSearchproj_Code" class="form-control" style="max-width:350px">
                                <?php
                                    //if($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'HR' || $LHint == 'RN')
                                    if($LHint != 'PRY')
                                    {
                                    ?>
                                        <option value="ALL"> --- All --- </option> 
                                    <?php
                                    }
									echo $i = 0;
                                    foreach($resPL as $rowPL) :
                                        $PRJCODE1 	= $rowPL->PRJCODE;
                                        $PRJNAME1 	= $rowPL->PRJNAME;
                                        ?>
                                        <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE == $PRJCODE1) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME1"; ?></option>
                                        <?php
                                    endforeach;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="11%" align="left" nowrap class="style1"><?php echo $FilterType ?></td>
                        <td width="1%" align="left" class="style1">:</td>
                        <td colspan="4" align="left" valign="middle" class="style1">
                            <select name="selSearchType" id="selSearchType" class="form-control" style="max-width:80px">
                                <option <?php if($viewCond == 99 || $viewCond == 97 || $viewCond == 96) { ?> style="display:none" <?php } ?> value="DP_HD" <?php if($selSearchTypex == 'DP_HD') { ?> selected <?php } ?>>DP</option>
                                <option <?php if($viewCond == 99 || $viewCond == 96) { ?> style="display:none" <?php } ?> value="LPMHD" <?php if($selSearchTypex == 'LPMHD') { ?> selected <?php } ?>>LPM</option>
                                <option <?php if($viewCond == 99) { ?> style="display:none" <?php } ?> value="OP_HD" <?php if($selSearchTypex == 'OP_HD') { ?> selected <?php } ?>>OP</option>
                                <option <?php if($viewCond == 99) { ?> style="display:none" <?php } ?> value="OPNHD" <?php if($selSearchTypex == 'OPNHD') { ?> selected <?php } ?>>OPN</option>
                                <option <?php if($viewCond == 99 || $viewCond == 97 || $viewCond == 96) { ?> style="display:none" <?php } ?> value="PD_HD" <?php if($selSearchTypex == 'PD_HD') { ?> selected <?php } ?>>PD</option>
                                <option <?php if($viewCond == 98 || $viewCond == 96) { ?> style="display:none" <?php } ?> value="SPKHD" <?php if($selSearchTypex == 'SPKHD') { ?> selected <?php } ?>>SPK</option>
                                <option <?php if($viewCond == 98 || $viewCond == 96) { ?> style="display:none" <?php } ?> value="SPPHD" <?php if($selSearchTypex == 'SPPHD') { ?> selected <?php } ?>>SPP</option>
                                <option <?php if($viewCond == 99 || $viewCond == 96) { ?> style="display:none" <?php } ?> value="VLKHD" <?php if($selSearchTypex == 'VLKHD') { ?> selected <?php } ?>>VLK</option>
                                <option <?php if($viewCond == 99 || $viewCond == 96) { ?> style="display:none" <?php } ?> value="VOCHD" <?php if($selSearchTypex == 'VOCHD') { ?> selected <?php } ?>>VOC</option>
                                <option <?php if($viewCond == 99 || $viewCond == 96) { ?> style="display:none" <?php } ?> value="VOTHD" <?php if($selSearchTypex == 'VOTHD') { ?> selected <?php } ?>>VOT</option>
                            </select>
                        </td> 
                    </tr>
                    <tr>
                        <td align="left" class="style1" nowrap><?php echo $Approve ?>?</td>
                        <td align="left" class="style1">:</td>
                        <td colspan="4" align="left">
                            <select name="SelIsApprove" id="SelIsApprove" class="form-control" style="max-width:80px">
                                <option <?php if($SelIsApprove == 0) { ?> selected <?php } ?> value="0">No</option> 
                                <option <?php if($SelIsApprove == 1) { ?> selected <?php } ?> value="1">Yes</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" class="style1" nowrap><?php echo $DocNumber ?>.</td>
                        <td align="left" class="style1">:</td>
                        <td colspan="4" align="left">
                            <div class="form-group has-warning">
                            <label class="control-label" for="inputWarning"><i class="fa fa-warning"></i> <?php echo $InputDocNo ?></label>
                            <input type="text" name="txtSearch" id="inputWarning" class="form-control" style="max-width:150px" value="" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1">&nbsp;</td>
                        <td colspan="4" align="left" class="style1"><hr></td>
                    </tr>
                    <tr>
                        <td align="left" class="style1">&nbsp;</td>
                        <td align="left" class="style1">&nbsp;</td>
                        <td width="33%" align="left" class="style1">
                            <!--<input type="submit" class="btn btn-primary" name="submit" id="submit" value="Search" align="left" />&nbsp;
                            <input type="button" class="btn btn-success" name="btnDl" id="btnDl" style="width:120px;" value="Export to Excel" onClick="checkReport(2);" />&nbsp;-->
                            
                            <button class="btn btn-primary"><i class="cus-check-report-16x16"></i>&nbsp;&nbsp;<?php echo $Search; ?></button>&nbsp;
                            <button class="btn btn-success"><i class="cus-excel-16x16" onClick="checkReport(2);"></i>&nbsp;&nbsp;<?php echo $exporttoexcel; ?></button>
                            
                            <?php 
                                echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="cus-back-16x16"></i>&nbsp;&nbsp;'.$Back.'</button>');
                            ?>                    
                        </td>
                            
                            
                        <td width="9%" align="left" class="style1">&nbsp;</td>
                        <td width="4%" align="left" class="style1">&nbsp;</td>
                        <td width="42%" align="left" nowrap style="font-weight:bold; font-style:italic; text-align:right">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="left" class="style1">&nbsp;</td>
                      <td align="left" class="style1">&nbsp;</td>
                      <td colspan="4" align="left" class="style1"><hr></td>
                    </tr>
                </table>
            </form>
        </div>
        <script>
			function checkReport(myVal)
			{
				document.getElementById("isexcel").value = 1;
				document.frm.submit.click();
			}
		</script>
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
    $('#datepicker').datepicker({
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
	function validateInData()
	{
		nextornot = document.getElementById('CheckThe_Code').value;
		if(nextornot > 0)
		{
			alert('Project Code Already Exist. Please Change.');
			document.getElementById('PRJCODE').value = '';
			document.getElementById('PRJCODE').focus();
			return false;
		}
		
		PRJNAME = document.getElementById('PRJNAME').value;
		if(PRJNAME == '')
		{
			alert('Project Name can not be empty');
			document.getElementById('PRJNAME').focus();
			return false;
		}
		
		PRJOWN = document.getElementById('PRJOWN').value;
		if(PRJOWN == 'none')
		{
			//alert('Please chose one of Owner Project.');
			//document.getElementById('PRJOWN').focus();
			//return false;
		}
		
		var PRJDATE = new Date(document.frm.PRJDATE.value);
		
		var PRJEDAT = new Date(document.frm.PRJEDAT.value);
		
		if(PRJEDAT < PRJDATE)
		{
			alert('End Date Project must be Greater than Start Date Project.');
			return false;
		}
		
		var ISCHANGE	= document.getElementById('ISCHANGEX').value;
		if(ISCHANGE == 1)
		{
			var REFCHGNO	= document.getElementById('REFCHGNO').value;
			if(REFCHGNO == '')
			{
				alert('Please input reference number.');
				document.getElementById('REFCHGNO').focus();
				return false;
			}
			
			var PRJCOST2 = eval(document.getElementById('PRJCOST22a')).value.split(",").join("");
			if(PRJCOST2 == 0)
			{
				alert('Please input new of Contract Value.');
				document.getElementById('PRJCOST22a').focus();
				return false;
			}
		}
		else
		{
			document.getElementById('REFCHGNO').value 	= '';
			document.getElementById('PRJCOST22a').value = '0.00';
			document.getElementById('PRJCOST22').value 	= '0';
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
	
	function validateDouble(vcode,serialNumber) 
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
				var elitem1= eval("document.frm.txtCode"+i).value;
				var iparent= eval("document.frm.txtSerialNumber"+i).value;
				//alert(''+iparent+ 'and ' +serialNumber)
				if (elitem1 == vcode && iparent == serialNumber)
				{
					if (elitem1 == vcode) 
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		//alert(duplicate)
		return duplicate;
	}
</script>
<?php 
$this->load->view('template/js');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>