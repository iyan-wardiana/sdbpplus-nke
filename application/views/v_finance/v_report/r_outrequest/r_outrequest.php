<?php
/* 
 * Author		   = Dian Hermanto
 * Create Date = 30 Januari 2019
 * File Name	 = r_outrequest.phpj
 * Location		 = -
*/
?>
<?php 
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
$decFormat		= 2;

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateM/$Start_DateD/$Start_DateY";	
$End_Date 		= "$Start_DateM/$Start_DateD/$Start_DateY";	

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$getproject 	= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
					         WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') ORDER BY A.PRJCODE";
$qProject 		= $this->db->query($getproject)->result();
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
		if($TranslCode == 'DisplayReport')$DisplayReport = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'All')$All = $LangTransl;
		if($TranslCode == 'ViewType')$ViewType = $LangTransl;
		if($TranslCode == 'WebViewer')$WebViewer = $LangTransl;
		if($TranslCode == 'Excel')$Excel = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<!-- Main content -->
<section class="content">
    <div class="box box-primary"><br>
   	  <form method="post" name="frm" id="frm" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);">
          <table width="100%">
            <tr>
              <td width="14%" nowrap>&nbsp;&nbsp;<?php echo $ProjectName ?></td>
              <td width="1%" nowrap>:</td>
              <td colspan="2" nowrap>
              	<select class="form-control pull-left" id="viewProj" name="viewProj" style="width:300px">
                	<option value="">-- Select Option --</option>
                    <?php 
                        foreach($qProject as $rowPRJ) :
                            $PRJCODE 	= $rowPRJ->PRJCODE;
                            $PRJNAME	= $rowPRJ->PRJNAME;
                            ?>
                                <option value="<?php echo $PRJCODE; ?>"><?php echo "$PRJCODE - $PRJNAME";?></option>
                            <?php
                        endforeach;
                    ?>
                </select>
              </td>
            </tr>
            <tr id="TransDate">
              <td>&nbsp;&nbsp;Periode Laporan</td>
              <td nowrap>:</td>
              <td colspan="2" nowrap>
               		  <div class="input-group date">
                          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          <input type="hidden" name="End_Date" class="form-control pull-left" id="datepicker" value="<?php echo $End_Date; ?>" size="10" style="width:150px" >
                          <input type="text" name="datePeriod" class="form-control pull-right" id="reservation" style="width:260px">
                      </div>              </td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;Type</td>
                <td nowrap>:</td>
                <td colspan="2" nowrap>
              	<select class="form-control pull-left" id="CFType" name="CFType" style="max-width:110px">
                	<option value="1" style="display: none;">Summary</option>
                	<option value="2" selected>Detail</option>
                </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;Status</td>
                <td nowrap>:</td>
                <td colspan="2" nowrap>
              	<select class="form-control pull-left" id="CFStat" name="CFStat" style="max-width:110px">
                	<option value="0"> - </option>
                	<option value="1">New</option>
                	<option value="2">Confirm</option>
                	<option value="3">Approve</option>
                	<option value="4">Revise</option>
                	<option value="5">Reject</option>
                	<option value="6">Close</option>
                	<option value="7">Awaiting</option>
                </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;<?php echo $ViewType ?>  </td>
                <td nowrap>:</td>
                <td colspan="2" nowrap>
                <label>
                    <input type="radio" name="viewType" id="viewType" value="0" checked /> 
                    <?php echo $WebViewer ?> 
                    <input type="radio" name="viewType" id="viewType" value="1" /> 
                    <?php echo $Excel ?>         </label>    	</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td nowrap>&nbsp;</td>
              <td colspan="2" nowrap>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td nowrap>&nbsp;</td>
                <td colspan="2" nowrap>
                    <!--<input type="submit" class="btn btn-primary" name="submit" id="submit" value="Display Report"/>--> 
                    <button class="btn btn-primary"><i class="cus-display-report-16x16"></i>&nbsp;&nbsp;<?php echo $DisplayReport; ?></button>&nbsp;
                </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td nowrap>&nbsp;</td>
              <td colspan="2" nowrap><hr /></td>
            </tr>
        </table>
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
	
	function target_popup(form)
	{
		var url = "<?php echo $form_action; ?>";
		PRJCODE = $('#viewProj').val();
		if(PRJCODE == '')
		{
			alert("Pleace select one project");
			$('#viewProj').focus();
			return false;
		}
		title = 'Select Item';
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	var url = "<?php echo base_url().'index.php/c_itmng/uploadtxt/export_txt';?>";
	function exporttoexcel()
	{
		window.open(url,'window_baru','width=800','height=200','scrollbars=yes,resizable=yes,location=no,status=yes')
	}
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>