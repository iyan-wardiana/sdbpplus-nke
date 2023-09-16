<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Februari 2017
 * File Name	= spp_idx_journal.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

$this->load->view('template/topbar');
$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;


$sql 		= "SELECT PRJCODE, PRJNAME, PRJLOCT FROM sd_tproject WHERE PRJCODE = '$PRJCODE'";
$sqlR		= $this->db->query($sql)->result();
foreach($sqlR as $rowR) :
	$PRJCODE		= $rowR->PRJCODE;
	$PRJNAME		= $rowR->PRJNAME;
	$PRJLOCT		= $rowR->PRJLOCT;
endforeach;

$hideSrch			= 0;
$SPPCODE			= '';
if (isset($_POST['submitSrch']))
{
	$SPPCODE 		= $_POST['txtSearch'];
	$hideSrch		= 1;
}
elseif (isset($_POST['btnSrch']))
{
	$SPPCODE 		= "";
	$hideSrch		= 0;
}
elseif (isset($_POST['submitSrchAgain']))
{
	$SPPCODE 		= "";
	$hideSrch		= 0;
}
?>
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
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small><?php echo "$PRJCODE - $PRJNAME"; ?></small>
  </h1><br>
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
<?php
	if($hideSrch == 0)
	{
		?>
        <form id="frmSrch" action="" method=POST onSubmit="return checkSearch();">
            <table width="100%" border="0">
                <tr height="20">
                  <td style="text-align:center; vertical-align:middle">Search SPP Code</td>
                </tr>
                <tr height="20">
                    <td style="text-align:center; vertical-align:top">
                        <label><input type="text" name="txtSearch" id="txtSearch" class="form-control" style="max-width:150px; text-align:center" value="<?php echo $SPPCODE; ?>" />
                        </label></td>
                </tr>
                <tr height="20">
                  <td style="text-align:center; vertical-align:middle"><input type="submit" class="btn btn-primary" name="submitSrch" id="submitSrch" value=" search SPP " /></td>
                </tr>
                <tr height="20">
                  <td style="text-align:center; vertical-align:middle"><hr style="max-width:350px;"></td>
                </tr>
            </table>
        </form>
		<?php
	}
?>
<form name="isfrmSrch" id="isfrmSrch" action="" method=POST style="display:none">
	<input type="text" name="showSrch" id="showSrch" class="form-control" style="max-width:150px; text-align:center" value="1" />
    <input type="submit" class="btn btn-primary" name="submitSrchAgain" id="submitSrchAgain" value=" search SPP " />
</form>
<?php
	if (isset($_POST['submitSrch']))
	{
		$SPPCODE 		= $_POST['txtSearch'];
		?>
		<form name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
			<table id="example1" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th width="3%">&nbsp;</th>
						<th width="3%">No.</th>
						<th width="12%">SPP Code</th>
						<th width="3%">SPP Date</th>
						<th width="39%">Supplier</th>
						<th width="40%">Schedule Material On Site</th>
					</tr>
				</thead>
				<tbody>
				<?php								
					$myNewNoc = 0;
					$i = 0;
					$sqlC 		= "SPPHD WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE' AND SPPCODE  LIKE '%$SPPCODE%'";
					$ressqlC	= $this->db->count_all($sqlC);
					if($ressqlC > 0)
					{
						$sqlSPP		= "SELECT SPPCODE, TRXDATE, PRJCODE FROM SPPHD 
										WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE' AND SPPCODE LIKE '%$SPPCODE%'";
						$viewSPP		= $this->db->query($sqlSPP)->result();
						foreach($viewSPP as $row) :
							$SPPCODE	= $row->SPPCODE;
							$TRXDATE	= $row->TRXDATE;
							$PRJCODE	= $row->PRJCODE;
							$SPLCODE	= '';
							$SPLDESC	= '';
						
							$SchedMatOSY = date('Y');
							$SchedMatOSM = date('m');
							$SchedMatOSD = date('d');
							$SchedMatOS = "$SchedMatOSY-$SchedMatOSM-$SchedMatOSD";
							?>        	
								<tr>
									<td style="text-align:center">
										<input type="radio" name="SPPCODE" id="SPPCODE" value="<?php echo $SPPCODE; ?>" checked />
										<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
										<input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
										<input type="hidden" name="isCheck" id="isCheck" value="1" />
									</td>
									<td> <?php print ++$i; ?>.</td>
									<td nowrap> <?php print $SPPCODE; ?> </td>
									<td nowrap> <?php print date('d M Y', strtotime($TRXDATE)); ?></td>
									<td nowrap> <?php print "$SPLCODE - $SPLDESC"; ?> </td>
									<td nowrap> <input type="hidden" name="SchedMatOS" id="SchedMatOS" value="<?php echo $SchedMatOS; ?>" /><?php echo $SchedMatOS;?> </td>
								</tr>
							<?php 
						endforeach; 
					}
				?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6" style="text-align:left"><input type="submit" name="btnPrint" id="btnPrint" class="btn btn-primary" value="Print Journal" />&nbsp;
						<?php 
							if ( ! empty($link))
							{
								foreach($link as $links)
								{
									echo $links;
								}
							}
						?>&nbsp;<input type="button" name="btnSrch" id="btnSrch" class="btn btn-primary" value=" Search SPP Again " onClick="showSrchAgain()" /></td>
					</tr>
				</tfoot>
			</table>
		</form>
		<?php
	}
?>
		
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
	function showSrchAgain()
	{
		document.isfrmSrch.submitSrchAgain.click();
	}
	
	var url = "<?php echo $form_action; ?>";
	function target_popup(form)
	{
		isCheck = document.getElementById('isCheck').value;
		
		if(isCheck == 0)
		{
			alert('Please search and check one of SPP Number');
			document.getElementById('txtSearch').focus();
			return false;
		}
		else
		{
			title = 'Select Item';
			w = 900;
			h = 550;
			var left = (screen.width/2)-(w/2);
			var top = (screen.height/2)-(h/2);
			window.open('url', 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
			form.target = 'formpopup';
		}
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>