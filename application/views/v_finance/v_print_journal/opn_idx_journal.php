<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 Februari 2017
 * File Name	= opn_idx_journal.php
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
        <form id="frmSrch" action="" method=POST onSubmit="return checkSearch();">
            <table width="100%" border="0">
                <tr height="20">
                  <td style="text-align:center; vertical-align:middle">Search Opname Code</td>
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
	$odbc = odbc_connect ('DBaseNKE3', '', '') or die('Could Not Connect to ODBC Database!');
	if (isset($_POST['submitSrch']))
	{			
		$myOPNCode 	= $_POST['txtSearch'];
		$totHrf		= strlen($myOPNCode);
		$totHrf2	= $totHrf - 2;
		$newSPKCode = substr($myOPNCode,0,-2);
		$theStep 	= substr($myOPNCode,-2);
		$theStep2 	= intval($theStep);
		$selStep 	= "OP$theStep2";
		//$DBFname = "OPNHD.DBF";
		//$db=dbase_open('./assets/files/'.$DBFname,0);
		//$db			=dbase_open('C:/DBaseNKE/SDBP/'.$DBFname,0);
		//$jumlah		=dbase_numrecords($db);			
		
		$hasilC		= "SELECT COUNT(*) AS COUNTME FROM OPNHD.DBF WHERE SPKCODE LIKE '%$newSPKCode%'";
		$qrhasilC	= odbc_exec($odbc, $hasilC) or die (odbc_errormsg());
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
                        <th width="2%">&nbsp;</th>
                        <th width="2%">No.</th>
                        <th width="11%">OPNAME Code</th>
                        <th width="7%" nowrap>OPNAME Date</th>
                        <th width="39%">Supplier</th>
                        <th width="35%">Project</th>
                        <th width="4%" nowrap>Print Opname</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$myNewNo 	= 0;
					$myNewNoc 	= 0;
					$i = 0;
					$j = 0;
					if($jumlah > 0)
					{
						/*for($x=1;$x<=$jumlah;$x++)
						{*/
						$strsql= "SELECT OPNCODE, TRXDATE, PRJCODE, SPLCODE, SPKCODE FROM OPNHD.DBF WHERE SPKCODE LIKE '%$newSPKCode%'";
						$query = odbc_exec($odbc, $strsql) or die (odbc_errormsg());
						while($row1 = odbc_fetch_array($query))
						{			
							//$hasil=dbase_get_record_with_names($db,$x);
							$myNewNoc 	= $myNewNoc + 1;
							$myNewNo	= $myNewNo + 1;
							$OPNCODE	= $row1['OPNCODE'];
							$TRXDATE	= $row1['TRXDATE'];
							$PRJCODE	= $row1['PRJCODE'];
							$SPLCODE	= $row1['SPLCODE'];
							$SPKCODE	= $row1['SPKCODE'];
							if($OPNCODE == $myOPNCode)
							{
								$sqlSPL		= "SELECT SPLCODE, SPLDESC, SPLTELP, SPLNPWP FROM SUPPLIER WHERE SPLCODE = '$SPLCODE'";
								$qrySPL		= $this->db->query($sqlSPL)->result();
								foreach($qrySPL as $rowSPL) :
									$SPLCODE	= $rowSPL->SPLCODE;
									$SPLDESC	= $rowSPL->SPLDESC;
									$SPLTELP	= $rowSPL->SPLTELP;
									$SPLNPWP	= $rowSPL->SPLNPWP;
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
                                        <input type="radio" name="OPNCODE" id="OPNCODE" value="<?php echo $OPNCODE; ?>" checked />
                                        <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                                        <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" /> 
                                        <input type="hidden" name="SPKCODE" id="SPKCODE" value="<?php echo $SPKCODE; ?>" />
                                         <input type="hidden" name="isCheck" id="isCheck" value="1" />
									</td>
									<td> <?php print $myNewNo; ?>.</td>
                                    <td nowrap> <?php print $OPNCODE; ?> </td>
                                    <td nowrap> <?php print date('d M Y', strtotime($TRXDATE)); ?></td>
                                    <td nowrap> <?php print "$SPLCODE - $SPLDESC"; ?> </td>
                                    <td nowrap> <?php print "$PRJCODE - $PRJNAME"; ?> </td>
                                    <td nowrap style="text-align:center">              
                                    <select name="opStep_<?php echo $myNewNo; ?>" id="opStep_<?php echo $myNewNo; ?>" onChange="chgSubmit(<?php echo $myNewNo; ?>);">
                                        <option value="0">None</option>
                                        <?php
                                            for($xs=1;$xs<=$myNewNoc;$xs++)
                                            {
                                                $OPStep = "OP$xs";
                                                ?>
                                                <option value="OP<?php echo $xs; ?>" <?php if ($OPStep == $selStep) { ?> selected <?php } ?>>Opname <?php echo $xs; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                    </td>
								</tr>
							<?php
							}
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
						?>&nbsp;<input type="button" name="btnSrch" id="btnSrch" class="btn btn-primary" value=" Search Opname Again " onClick="showSrchAgain()" /></td>
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