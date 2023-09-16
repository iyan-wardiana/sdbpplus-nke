<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 08 Maret 2017
 * File Name	= bank_guarantee_cc.php
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
		
$dateNow = date('Y-m-d');
$dateNow2 = date('Y-m-d', strtotime('+30 days', strtotime($dateNow))); // mengurangi 
//echo $dateNow2;

$empID = $this->session->userdata('Emp_ID');
//$sql 	= "tbl_bnkgrs_cc WHERE emp_ID = '$empID' AND grsdate2 <= '$dateNow2'";
$sql 	= "tbl_bnkgrs_cc WHERE grsdate2 <= '$dateNow2'";
$sqlRes	= $this->db->count_all($sql);

$dateNow = date('Y-m-d');
$dateNow2 = date('Y-m-d', strtotime('+30 days', strtotime($dateNow))); // Menentukan tgl maksimum untuk batas due date dari tgl hari ini 
?>
<script type="text/javascript">
	function loadDuedateGuarantee() 
	{
        totRow = document.getElementById('countRow').value;
		if(totRow > 0)
		{
			showNotification({
				type : "error",
				message: "Ada ",
				message1: totRow,
				message2: " Bank Garansi yang Akan segera berakhir. Klik ",
				message3: "menampilkan semua Data."
			}); 
		}
	}
	window.onload = loadDuedateGuarantee;
	
	window.setTimeout(function()
	{ 
		loadDuedateGuarantee();loadDuedateGuaranteex(); 
	}, 50000);	
	
	function loadDuedateGuaranteex()
	{
		window.setTimeout(function()
		{ 
			loadDuedateGuarantee();loadDuedateGuaranteex()
		}, 50000);
		
	}
	
	function gotoallduedate()
	{
		var thisform = document.getElementById('gotoAllDueD').click();
	}
</script>
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'GuaranteeType')$GuaranteeType = $LangTransl;
		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
		if($TranslCode == 'GuaranteeCost')$GuaranteeCost = $LangTransl;
		if($TranslCode == 'Kurs')$Kurs = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'ClaimDate')$ClaimDate = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small><?php echo $h3_title; ?></small>
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
        <form action="" onsubmit="confirmDelete();" method=POST>
            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner">
            	<thead>
                    <tr>
                      <th width="14%" style="text-align:center" nowrap><?php echo $Code ?>  </td>
                      <th width="11%" style="text-align:center" ><?php echo $ProjectName ?> </td>
                      <th width="9%" style="text-align:center" ><?php echo $GuaranteeType ?> </td>
                      <th width="12%" style="text-align:center" nowrap><?php echo $Supplier ?> </td>
                      <th width="10%" style="text-align:center" nowrap><?php echo $GuaranteeCost ?> <br />(USD)</td>
                      <th width="5%" style="text-align:center" nowrap><?php echo $Kurs ?> </td>
                      <th width="9%" style="text-align:center" nowrap><?php echo $GuaranteeCost ?> <br />(Rp)</td>
                      <th width="9%" style="text-align:center" nowrap><?php echo $StartDate ?> </td>
                      <th width="8%" style="text-align:center" nowrap><?php echo $EndDate ?> </td>
                      <th width="10%" style="text-align:center" nowrap><?php echo $ClaimDate ?> </td>  
                     </tr>
                </thead>
        		<tbody>
                <?php 
                $i = 0;
				$j = 0;
                $PRJNAME = '';
                if($recordcount >0)
                {
					foreach($viewbankguar as $row) :
						$myNewNo 		= ++$i;
						$grscode		= $row->GRSCODE;
						$prjcode		= $row->PRJCODE;
						$grsrefr		= $row->GRSREFR;
						$splcode		= $row->SPLCODE;
						$grstype		= $row->GRSTYPE;
						$grsdesc		= $row->GRSDESC;
						$grscost		= $row->GRSCOST;
						$grsdate1		= $row->GRSDATE1;
						$grsdate2		= $row->GRSDATE2;
						$grsfinal		= $row->GRSFINAL;
						$DP_TRXC		= $row->DP_TRXC;
						$PRJNAME = '';
						$sql = "SELECT * FROM tbl_project WHERE PRJCODE = '$prjcode'";
						$result = $this->db->query($sql)->result();
									
						foreach($result as $row) :
							$PRJCODE		= $row->PRJCODE;
							$PRJNAME		= $row->PRJNAME;
						endforeach;
						$grsdate1a 	= date('d M Y', strtotime($grsdate1));
						$grsdate2a 	= date('d M Y', strtotime($grsdate2));
											
						if($grstype == '1')
						{
							$grstypeName	= 'Jaminan Uang Muka';
						}
						elseif($grstype == '2')
						{
							$grstypeName	= 'Jaminan Pelaksanaan';
						}
						elseif($grstype == '3')
						{
							$grstypeName	= 'ADVANCE PAYMENT BOND';
						}
						elseif($grstype == '4')
						{
							$grstypeName	= 'CONSTRUCTION ALL RISK';
						}
						elseif($grstype == '5')
						{
							$grstypeName	= 'MAINTENANCE BOND';
						}
						elseif($grstype == '')
						{
							$grstypeName	= 'NONE';
						}
						
						$bankpublisher 	= '';
						$grscostUSD		= 0;
						$grskurs		= 0;
						$grsdateclaima	= '';
						
						$len = strlen($grscode);
						$Pattern_Length = 6;
						$grscode2	= $grscode;
						
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
						{
							if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0"; else $nol = '';
						}
						elseif($Pattern_Length==7)
						{
							if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
						}
						else
						{
							$nol = '';
						}
						
						$grscode = $nol.$grscode2;
				
						if ($j==1) {
							echo "<tr class=zebra1>";
							$j++;
						} else {
							echo "<tr class=zebra2>";
							$j--;
						}
							?> 
							  	<td nowrap><?php echo $grscode; ?> </td>
								<td nowrap><?php echo "$prjcode - $PRJNAME"; ?> </td>
								<td nowrap><?php echo $grstypeName; ?></td>
								<?php 
									$sqlSPLC = "tbl_supplier WHERE SPLCODE = '$splcode'";
									$resultSPLC = $this->db->count_all($sqlSPLC);
									
									if($resultSPLC > 0)
									{
										$sqlSPL = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$splcode'";
										$resultSPL = $this->db->query($sqlSPL)->result();
													
										foreach($resultSPL as $rowSPL) :
											$SPLDESC		= $rowSPL->SPLDESC;
										endforeach;
										?>
										<td nowrap>
											<?php
												echo "$splcode - $SPLDESC";
											?>
										</td>
										<?php
									}
									else
									{
										$SPLDESC		= "No Name";
									?>
										<td nowrap style="font-style:italic">
											<?php
												echo "$splcode - $SPLDESC";
											?>
										</td>
									<?php
									}
								?>
								<td style="text-align:right" nowrap>&nbsp;<?php echo number_format($grscostUSD, $decFormat); ?>&nbsp;</td>
								<td style="text-align:right" nowrap>&nbsp;<?php echo number_format($grskurs, $decFormat); ?>&nbsp;</td>
								<td style="text-align:right" nowrap>&nbsp;<?php echo number_format($grscost, $decFormat); ?>&nbsp;</td>
								<td style="text-align:center" nowrap><?php echo $grsdate1a; ?> </td>
								<td style="text-align:center" nowrap><?php echo $grsdate2a; ?> </td>
								<td width="3%" nowrap style="text-align:center"><?php echo $grsdateclaima; ?> </td>
							</tr>
						<?php
					endforeach; 
                }
                ?>
                </tbody>
            </table>
            <input type="text" style="display:none" name="countRow" id="countRow" value="<?php echo $sqlRes; ?>">
        </form>
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