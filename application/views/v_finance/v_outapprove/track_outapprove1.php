<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Februari 2017
 * File Name	= track_outapprove.php.php
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


$this->db->select('Display_Rows,decFormat,isUpdOutApp');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows 	= $row->Display_Rows;
	$decFormat 		= $row->decFormat;
	$isUpdOutApp 	= $row->isUpdOutApp;
endforeach;
$empID = $this->session->userdata('Emp_ID');
// GET LAST UPDATE
//if($recordcount >0)
//{
	$sqlGLU		= "SELECT OA_Update FROM tout_approve GROUP BY OA_Update";
	$sqlResGLU	= $this->db->query($sqlGLU)->result();
	foreach($sqlResGLU as $rowGLU) :
		$OA_Update	= $rowGLU->OA_Update;
	endforeach;
	$lastUpdate 	= new DateTime($OA_Update);
	$lastUpdateDate	= $lastUpdate->format('F d, Y');
	$lastUpdateTime	= $lastUpdate->format('H:i:s');
	$lastUpdateDesc	= "$lastUpdateDate. Time $lastUpdateTime";
//}
// Searching Function
$selSearchproj_Code1   	= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
$def_ProjCode   		= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
$selSearchType1     	= $this->session->userdata['dtSessSrc1']['selSearchType'];
$txtSearch1        		= $this->session->userdata['dtSessSrc1']['txtSearch'];
$SelIsApprove1     		= $this->session->userdata['dtSessSrc1']['SelIsApprove'];

$Emp_DeptCode 	= $this->session->userdata('Emp_DeptCode');	

	$empID = $this->session->userdata('Emp_ID');
	$LHint = $this->session->userdata('log_passHint');
	/*if($LHint != 'DH')
	{
		echo "Sorry, this page is under processing by Dian Hermanto.";
		return false;
	}*/
//$sqlRes = $recordcount;	
// Start : Searching Function --- Untuk delete session
$dataSessSrc = array(
		'selSearchproj_Code' => $selSearchproj_Code1,
		'selSearchType' => $selSearchType1,
		'txtSearch' => $txtSearch1,
		'SelIsApprove' => $SelIsApprove1);
$this->session->set_userdata('dtSessSrc1', $dataSessSrc);

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
if($selSearchTypex == 'OP_HD' && $SelIsApprove1 == 1)
{
	$showAppDate	= 1;
}
if($selSearchTypex == 'SPPHD' && $SelIsApprove1 == 1)
{
	$showAppDate	= 1;
}?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $appName; ?> | Data Tables</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minx.css'; ?>">
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
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small>finance</small>
  </h1>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>

<!-- Main content -->

  <div class="box">
    <!-- /.box-header -->
<div class="box-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="3%">No.</th>
                <th width="10%" nowrap>Transaction Category </th>
                <th width="12%" nowrap>Transaction Code</th>
                <th width="12%">Transaction Date</th>
                <?php
                    if($showAppDate == 1)
                    {
                    ?>
                        <th width="12%">Approve Date</th>
                    <?php
                    }
                ?>
                <th width="9%">Project Code</th>
                <th width="54%">Description</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="10" style="text-align:left">
				<?php //echo anchor($secAddURL,'<input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value="Add Project" />');?>
            </th>
          </tr>
        </tfoot>
   	</table>
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
</body>

</html>
<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal;
		document.getElementById('myProjCode').value = myValue;
		document.getElementById('selProject').value = myValue;
		chooseProject(thisVal);
	}
	
	function chooseProject(thisVal)
	{
		document.frmselect.submit.click();
	}
		
	function vProjPerform()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjPerF; ?>';
		title = 'Select Item';		
		
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+screen.width+', height='+screen.height);
	}
		
	function vInpProjDet()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjInDet; ?>';
		title = 'Select Item';		
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>