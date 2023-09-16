<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Februari 2017
 * File Name	= lpm_idx_journal.php
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


$sql 		= "SELECT PRJCODE, PRJNAME, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$sqlR		= $this->db->query($sql)->result();
foreach($sqlR as $rowR) :
	$PRJCODE		= $rowR->PRJCODE;
	$PRJNAME		= $rowR->PRJNAME;
	$PRJLOCT		= $rowR->PRJLOCT;
endforeach;

$hideSrch			= 0;
$LPMCODE			= '';
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
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
<?php
	if($hideSrch == 0)
	{
		?>
        <form action="" method=POST onSubmit="return checkSearch();">
            <table width="100%" border="0">
                <tr height="20">
                  <td style="text-align:center; vertical-align:middle">Search OP Code</td>
                </tr>
                <tr height="20">
                    <td style="text-align:center; vertical-align:top">
                        <label><input type="text" name="txtSearch" id="txtSearch" class="form-control" style="max-width:150px; text-align:center" value="<?php echo $LPMCODE; ?>" />
                        </label></td>
                </tr>
                <tr height="20">
                  <td style="text-align:center; vertical-align:middle"><input type="submit" class="btn btn-primary" name="submitSrch" id="submitSrch" value=" search OP " /></td>
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
		$OP_CODE 		= $_POST['txtSearch'];
		
		$myNewNoc = 0;	
		/*if($OP_CODE == '')
		{
			$sql0	= "OP_HD A
							LEFT JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE
							LEFT JOIN tbl_project C ON C.PRJCODE = A.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND APPROVE = 1";
		}
		else
		{
			$sql0	= "OP_HD A
							LEFT JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE
							LEFT JOIN tbl_project C ON C.PRJCODE = A.PRJCODE
						WHERE A.OP_CODE = '$OP_CODE' AND A.PRJCODE = '$PRJCODE' AND APPROVE = 1";
		}
		$jumlah	= $this->db->count_all($sql0);*/
		$odbc 			= odbc_connect ('DBaseNKE4', '', '') or die('Could Not Connect to ODBC Database!');
		$hasilC			= "SELECT COUNT(*) AS COUNTME FROM OP_HD.DBF WHERE OP_CODE = '$OP_CODE' AND APPROVE = 1";
		$qrhasilC		= odbc_exec($odbc, $hasilC) or die (odbc_errormsg());
		while($hasilC = odbc_fetch_array($qrhasilC))
		{
			$jumlah		= $hasilC['COUNTME'];
		}
		?>
		<form name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return target_popup(this);" >
        <div class="search-table-outter">
			<table id="example1" class="table table-bordered table-striped table-responsive search-table inner">
				<thead>
					<tr>
						<th width="3%">&nbsp;</th>
						<th width="3%">No.</th>
						<th width="12%">OP Code</th>
						<th width="3%">OP Date</th>
						<th width="39%">Supplier</th>
						<th width="40%">Project</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$i = 0;
					$j = 0;
					if($jumlah > 0)
					{
						/*if($OP_CODE == '')
						{
							$sql1 			= "SELECT A.OP_CODE, A.TRXDATE, A.PRJCODE, A.SPLCODE, A.SPPCODE, A.OP_COST,
												B.SPLDESC, C.PRJNAME
												FROM OP_HD A
													LEFT JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE
													LEFT JOIN tbl_project C ON C.PRJCODE = A.PRJCODE
												WHERE A.PRJCODE = '$PRJCODE'";
						}
						else
						{
							$sql1 			= "SELECT A.OP_CODE, A.TRXDATE, A.PRJCODE, A.SPLCODE, A.SPPCODE, A.OP_COST,
												B.SPLDESC, C.PRJNAME
												FROM OP_HD A
													LEFT JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE
													LEFT JOIN tbl_project C ON C.PRJCODE = A.PRJCODE
												WHERE A.OP_CODE = '$OP_CODE' AND A.PRJCODE = '$PRJCODE'";
						}
						$result1 		= $this->db->query($sql1)->result();
						foreach($result1 as $row1) :
							$i			= $i +1;
							$OP_CODE 	= $row1 ->OP_CODE;
							$TRXDATE 	= $row1 ->TRXDATE;
							$PRJCODE 	= $row1 ->PRJCODE;
							$SPLCODE 	= $row1 ->SPLCODE;
							$SPPCODE 	= $row1 ->SPPCODE;
							$OP_COST 	= $row1 ->OP_COST;
							$SPLDESC 	= $row1 ->SPLDESC;
							$PRJNAME 	= $row1 ->PRJNAME;*/
										
						$getHD		= "SELECT OP_CODE, TRXDATE, PRJCODE, SPLCODE, SPPCODE, OP_COST,
											DELIVDT, CONDITI, TRXUSER, APPRUSR
										FROM OP_HD.DBF
										WHERE OP_CODE = '$OP_CODE' AND APPROVE = 1";
						$qrHD		= odbc_exec($odbc, $getHD) or die (odbc_errormsg());
						while($vHD	= odbc_fetch_array($qrHD))
						{
							$i			= $i +1;
							$OP_CODE	= $vHD['OP_CODE'];
							$TRXDATE	= $vHD['TRXDATE'];
							$PRJCODE	= $vHD['PRJCODE'];
							$SPLCODE	= $vHD['SPLCODE'];
							$SPPCODE	= $vHD['SPPCODE'];
							$OP_COST	= $vHD['OP_COST'];
							$DELIVDT	= $vHD['DELIVDT'];
							$CONDITI	= $vHD['CONDITI'];
							$TRXUSER	= $vHD['TRXUSER'];
							$APPRUSR	= $vHD['APPRUSR'];
							
							$SPLDESC 	= '';
							$PRJNAME 	= '';
							
							$sqlsp4		= "SELECT SPLCODE, SPLDESC, SPLADD1, SPLTELP, SPLNPWP FROM supplier WHERE SPLCODE = '$SPLCODE'";
							$sqlsp4R	= $this->db->query($sqlsp4)->result();
							foreach($sqlsp4R as $rowsp4) :
								$SPLCODE		= $rowsp4->SPLCODE;
								$SPLDESC		= $rowsp4->SPLDESC;
								$SPLADD1		= $rowsp4->SPLADD1;
								$SPLTELP		= $rowsp4->SPLTELP;
								$SPLNPWP		= $rowsp4->SPLNPWP;
							endforeach;
					
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
								?> 
									<td style="text-align:center">
                                        <input type="radio" name="OPCode" id="OPCode" value="<?php echo $OP_CODE; ?>" checked />
                                        <input type="hidden" name="OP_CODE" id="OP_CODE" value="<?php echo $OP_CODE; ?>" />
                                        <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                                        <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
                                        <input type="hidden" name="DELIVDT" id="DELIVDT" value="<?php echo $DELIVDT; ?>" />
                                        <input type="hidden" name="CONDITI" id="CONDITI" value="<?php echo $CONDITI; ?>" />
                                        <input type="hidden" name="TRXUSER" id="TRXUSER" value="<?php echo $TRXUSER; ?>" />
                                        <input type="hidden" name="APPRUSR" id="APPRUSR" value="<?php echo $APPRUSR; ?>" />
                                        <input type="hidden" name="SPPCODE" id="SPPCODE" value="<?php echo $SPPCODE; ?>" />
                                        <input type="hidden" name="TRXDATE" id="TRXDATE" value="<?php echo $TRXDATE; ?>" />
                                        <input type="hidden" name="isCheck" id="isCheck" value="1" />                         </td>
                                    <td> <?php print $i; ?>.</td>
                                    <td nowrap> <?php print $OP_CODE; ?> </td>
                                    <td nowrap> <?php print date('d M Y', strtotime($TRXDATE)); ?></td>
                                    <td nowrap> <?php print "$SPLCODE - $SPLDESC"; ?> </td>
                                    <td nowrap> <?php print "$PRJCODE - $PRJNAME"; ?> </td>
								</tr>
							<?php
						}
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
						?>&nbsp;<input type="button" name="btnSrch" id="btnSrch" class="btn btn-primary" value=" Search OP Again " onClick="showSrchAgain()" /></td>
					</tr>
				</tfoot>
			</table>
        </div>
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
			alert('Please search and check one of LPM Number');
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