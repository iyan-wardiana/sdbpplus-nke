<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Maret 2017
 * File Name	= bcaupload.php
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
			
		if($TranslCode == 'ExportToTxt')$ExportToTxt = $LangTransl;
		if($TranslCode == 'CheckReport')$CheckReport = $LangTransl;
		if($TranslCode == 'Upload')$Upload = $LangTransl;
	
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small>finance</small>
  </h1>
  <br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

<div class="box">
<!-- /.box-header -->
<div class="box-body">
    <div class="search-table-outter">
    	<form name="myformupload" id="myformupload" enctype="multipart/form-data" method="post" action="<?php echo $myFormAction; ?>" onSubmit="return chcekFile();" >
            <table width="100%" border="0">
                <tr height="20">
                    <td valign="top">&nbsp;<input type="text" style="display:none" name="isUploaded" id="isUploaded" value="<?php echo $isUploaded; ?>" /></td>
                    <td valign="top">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="20" id="tblUpload">
                    <td width="20%" valign="top"><b>Upload File 1</b></td>
                    <td width="2%" valign="top"><b>:</b></td>
                    <td width="78%">  
                        <?php
                            if($error == 'Error')
                            {
                                echo "Upload error... !!! Pilih file txt.";
                                echo "<br /><br />";
                            }
                            elseif($error == 'Sukses')
                            {
                                $empID	= $this->session->userdata('Emp_ID');
                                $SQL = "DELETE FROM tbl_uploadbca_data WHERE empID = '$empID'";
                                //$SQL = "DELETE FROM tuploadbca_data";
                               $this->db->query($SQL);
                                                            
                                echo "Upload File $myFileNameShow Sukses... !!! Silahkan klik tombol Check Report atau Export to Excel.";
                                echo "<br /><br />";
                                
                                $sqlgetData	= "SELECT * FROM tbl_currentupbca WHERE empID = '$empID'";
                                $resultgetData = $this->db->query($sqlgetData)->result();
                                foreach($resultgetData as $nRow) :
                                    $FileUpName = $nRow->FileUpName;
                                    $empID = $nRow->empID;
                                    $IPAddress = $nRow->IPAddress;
                                    $myPath = "bca_upload/$FileUpName";
                                    $file = file(base_url() . "$myPath"); # read file into array
                                    $count = count($file);
                                    if($count > 0) # file is not empty
                                    {
                                        $milestone_query = "INSERT into tbl_uploadbca_data(data_desc, empID) values";
                                        $i = 1;
                                        foreach($file as $row)
                                        {
                                            $milestone 	= explode('|',$row);
                                            $dataDesc 	= $milestone[0];
                                            $myCountAlph= strlen($dataDesc);
                                            if($myCountAlph < 256)
                                            {
                                                $jmlAwal	= $myCountAlph - 3;
                                                $tambahan1 	= substr($dataDesc, 0, $jmlAwal);
                                                $tambahan2 	= substr($dataDesc, -3,3);
                                                $tambahan3 	= "  $tambahan2";
                                                $tambahan4 	= "$tambahan1$tambahan3";
                                            }
                                            $milestone_query .= "('$tambahan4', '$empID')";
                                            $milestone_query .= $i < $count ? ',':'';
                                            $i++;
                                        }
										$this->db->query($milestone_query);
                                    }
                                endforeach;
                                //return false;                    
                            }
                        ?>
                        <input type="file" name="userfile[]" id="userfile1" size="20" />
                    </td>
                </tr>
            </table>
            <table width="100%" border="0">
                <tr height="20">
                    <td width="20%" valign="top">&nbsp;</td>
                    <td width="2%" valign="top">&nbsp;</td>
                    <td width="78%">&nbsp;</td>
              </tr>
                <tr height="20">
                    <td width="20%" valign="top">&nbsp;</td>
                    <td width="2%" valign="top">&nbsp;</td>
                    <td width="78%">
                        <!--<input type="submit" value="Upload File" class="btn btn-warning" style="width:120px;" />&nbsp;&nbsp;
                        <input name="btnDl" type="button" id="btnDl" style="width:120px;" value="Export to TXT" class="btn btn-primary" onclick="checkReport(2);" />-->
                        
                        <button class="btn btn-warning" ><i class="cus-upload-16x16"></i>&nbsp;&nbsp;<?php echo "$Upload File"; ?></button>&nbsp;
                        <button type="button" class="btn btn-primary" onClick="checkReport(2);"><i class="cus-export-txt-16x16"></i>&nbsp;&nbsp;<?php echo $ExportToTxt; ?></button>&nbsp;
                    </td>
                </tr>
                <tr height="20">
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                  <td>&nbsp;</td>
              </tr>
            </table>
        </form>
        <script>
			function chcekFile()
			{
				var userfile1 = document.getElementById('userfile1').value;
				
				//if(userfile1 == '' && userfile2 == '' && userfile3 == '')
				if(userfile1 == '')
				{
					alert('Please insert file(s) txt which will you be uploaded.');
					return false;
				}
				
				if(userfile1 != '')
				{
					var isTXTFile1 = userfile1.substr(-3,3);
					if(isTXTFile1 != 'TXT')
					{
						alert('You can upload TXT File Type only. On File 1.');
						return false;
					}
				}		
			}
	
			function checkReport(myVal)
			{
				hasUploadFile = document.getElementById('isUploaded').value;
				if(hasUploadFile == 0)
				{
					if(myVal == 1)
					{
						alert('Please upload file(s) before you Check Report.');
					}
					else
					{
						alert('Please upload file(s) before you Export File.');
					}
					return false;
				}
				var url = "<?php echo base_url().'index.php/c_finance/bcaupload/export_txt/';?>"+myVal;
				title = 'Report';
				w = 780;
				h = 550;
				//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				var left = (screen.width/2)-(w/2);
				var top = (screen.height/2)-(h/2);
				return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left+', myVal = '+myVal);
			}
		</script>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
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
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>