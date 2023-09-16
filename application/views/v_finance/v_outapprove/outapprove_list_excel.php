<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 Februari 2017
 * File Name	= outapprove_list.php
 * Location		= -
*/
if($isexcel == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}$appName 	= $this->session->userdata('appName');

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

$PRJNAME	= 'All';
$sql 		= "SELECT PRJCODE, PRJNAME, PRJLOCT FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$sqlR		= $this->db->query($sql)->result();
foreach($sqlR as $rowR) :
	$PRJCODE		= $rowR->PRJCODE;
	$PRJNAME		= $rowR->PRJNAME;
	$PRJLOCT		= $rowR->PRJLOCT;
endforeach;

$empID = $this->session->userdata('Emp_ID');
$LHint = $this->session->userdata('log_passHint');

$showAppDate	= 0;
if($selSearchTypex == 'OP_HD' && $SelIsApprove == 1)
{
	$showAppDate	= 1;
}
if($selSearchTypex == 'SPPHD' && $SelIsApprove == 1)
{
	$showAppDate	= 1;
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
<div class="search-table-outter">
<form name="isfrmSrch" id="isfrmSrch" action="<?php echo $srch_again; ?>" method=POST>
  <table id="example1" class="table table-bordered table-striped table-responsive search-table inner">
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
        <th width="54%">ID</th>
      </tr>
    </thead>
    <tbody>
      <?php
		$i 			= 0;
		$PRJNAME 	= '';
		$myNewNo 	= 0;
		$TOTCOUNT 	= 0;
		$sqlRes		= 0;
		$url_updtDoc	= site_url('c_finance/c_outapprove/openDocument/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		
		odbc_close_all();
		//if($selSearchTypex == "DP_HD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN')) // QUERY - 1
		if($selSearchTypex == "DP_HD") // QUERY - 1
		{
			$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
			if($def_ProjCode == "ALL")
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM DP_HD.DBF WHERE DAPPROVE = FALSE AND DP_CODE LIKE'%". $txtSearch ."%'");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];

			}
			else
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM DP_HD.DBF WHERE DAPPROVE = FALSE AND DP_CODE LIKE'%". $txtSearch ."%' AND PRJCODE  = '$def_ProjCode'");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];
			}
			if($TOTCOUNT > 0)
			{
				$dateStarta	= date('Y-m-d H:i:s');
				$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
				$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
				if($def_ProjCode == "ALL")
				{
					$getTID		= "SELECT DP_CODE, DP_DATE, PRJCODE, TRXUSER FROM DP_HD.DBF WHERE DAPPROVE = FALSE AND DP_CODE LIKE '%$txtSearch%'";
				}
				else
				{
					$getTID		= "SELECT DP_CODE, DP_DATE, PRJCODE, TRXUSER FROM DP_HD.DBF WHERE DAPPROVE = FALSE AND DP_CODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
				}
				
				$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());			
				while($vTID = odbc_fetch_array($qTID))
				{
					$OA_Code		= $vTID['DP_CODE'];
					$date			= $vTID['DP_DATE'];
					$proj_Code		= $vTID['PRJCODE'];
					$TRXUSER		= $vTID['TRXUSER'];
					$OA_CategoryDesc= "DP";
					$myNewNo		= $myNewNo + 1;
					$sqlRes			= $sqlRes + 1;
					?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td ><?php echo $date; ?> </td>
        <td ><?php print $proj_Code; ?></td>
        <td >&nbsp;</td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
				}
				$dateNow	= date('Y-m-d H:i:s');
				odbc_close_all();
			}
		}
		//if($selSearchTypex == "LPMHD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'MD' || $LHint == 'MG' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR' || $LHint == 'EA' || $LHint == 'UP')) // QUERY - 2
		if($selSearchTypex == "LPMHD") // QUERY - 2
		{
			$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
			if($def_ProjCode == "ALL")
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM LPMHD.DBF WHERE APPROVE = FALSE AND LPMCODE LIKE'%". $txtSearch ."%'");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];

			}
			else
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM LPMHD.DBF WHERE APPROVE = FALSE AND LPMCODE LIKE'%". $txtSearch ."%' AND PRJCODE  = '$def_ProjCode'");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];
			}
			if($TOTCOUNT > 0)
			{
				$dateStarta	= date('Y-m-d H:i:s');
				$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
				$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
				if($def_ProjCode == "ALL")
				{
					$getTID		= "SELECT LPMCODE, TRXDATE, PRJCODE, TRXUSER FROM LPMHD.DBF WHERE APPROVE = FALSE AND LPMCODE LIKE '%$txtSearch%'";
				}
				else
				{
					$getTID		= "SELECT LPMCODE, TRXDATE, PRJCODE, TRXUSER FROM LPMHD.DBF WHERE APPROVE = FALSE AND LPMCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
				}
				
				$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
				$totDP_HD	= 0;			
				while($vTID = odbc_fetch_array($qTID))
				{
					$OA_Code		= $vTID['LPMCODE'];
					$date			= $vTID['TRXDATE'];
					$proj_Code		= $vTID['PRJCODE'];
					$TRXUSER		= $vTID['TRXUSER'];
					$OA_CategoryDesc= "LPM";
					$myNewNo		= $myNewNo + 1;
					$sqlRes			= $sqlRes + 1;
					
					$getLPMDT		= "SELECT LPMDES1 FROM LPMDT.DBF WHERE LPMCODE = '$OA_Code'";
					$qLPMDT 		= odbc_exec($odbc, $getLPMDT) or die (odbc_errormsg());		
					$rowLPMDT		= 0;	
					while($vLPMDT = odbc_fetch_array($qLPMDT))
					{
						$rowLPMDT	= $rowLPMDT + 1;
						if($rowLPMDT == 1)
						{
							$LPMDES1		= $vLPMDT['LPMDES1'];
							break;
						}
					}
					?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap><?php print $LPMDES1; ?></td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
				}
				odbc_close_all();
			}
		}
		//if($selSearchTypex == "OP_HD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'MD' || $LHint == 'MG' || $LHint == 'UP' || $LHint == 'EA' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR' || $LHint == 'OP')) // QUERY - 2
		if($selSearchTypex == "OP_HD") // QUERY - 2
		{
			$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
			if($def_ProjCode == "ALL")
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM OP_HD.DBF");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];

			}
			else
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM OP_HD.DBF WHERE APPROVE = FALSE AND OP_CODE LIKE'%". $txtSearch ."%' AND PRJCODE  = '$def_ProjCode'");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];
			}
			if($showAppDate == 0)
			{
				if($TOTCOUNT > 0)
				{
					$dateStarta	= date('Y-m-d H:i:s');
					$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
					$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
					if($def_ProjCode == "ALL")
					{
						$getTID		= "SELECT OP_CODE, TRXDATE, PRJCODE, TRXUSER FROM OP_HD WHERE APPROVE = FALSE AND OP_CODE LIKE '%$txtSearch%'";
					}
					else
					{
						$getTID		= "SELECT OP_CODE, TRXDATE, PRJCODE, TRXUSER FROM OP_HD WHERE APPROVE = FALSE AND OP_CODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
					}
					
					$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
					$totDP_HD	= 0;			
					while($vTID = odbc_fetch_array($qTID))
					{
						$OA_Code		= $vTID['OP_CODE'];
						$date			= $vTID['TRXDATE'];
						$proj_Code		= $vTID['PRJCODE'];
						$TRXUSER		= $vTID['TRXUSER'];
						$OA_CategoryDesc= "OP";
						$myNewNo		= $myNewNo + 1;	
						$sqlRes			= $sqlRes + 1;
						
						$OP_DESC		= '';
						$getTIDDES		= "SELECT OP_DESC FROM OP_DT WHERE OP_CODE = '$OA_Code'";
						$qTIDDES		= odbc_exec($odbc, $getTIDDES) or die (odbc_errormsg());
						while($vTIDES = odbc_fetch_array($qTIDDES))
						{
							$OP_DESC		= $vTIDES['OP_DESC'];
						}
						?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap><?php print $OP_DESC; ?></td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
					}
					odbc_close_all();
				}
			}
			elseif($showAppDate == 1)
			{
				if($TOTCOUNT > 0)
				{
					$dateStarta	= date('Y-m-d H:i:s');
					$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
					$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
					if($def_ProjCode == "ALL")
					{
						$getTID		= "SELECT OP_CODE, TRXDATE, PRJCODE, APVDATE, TRXUSER FROM OP_HD WHERE APPROVE = TRUE AND OP_CODE LIKE '%$txtSearch%'";
					}
					else
					{
						$getTID		= "SELECT OP_CODE, TRXDATE, PRJCODE, APVDATE, TRXUSER FROM OP_HD 
										WHERE APPROVE = TRUE AND OP_CODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
					}
					
					$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
					$totDP_HD	= 0;			
					while($vTID = odbc_fetch_array($qTID))
					{
						$OA_Code		= $vTID['OP_CODE'];
						$date			= $vTID['TRXDATE'];
						$proj_Code		= $vTID['PRJCODE'];
						$APVDATE		= $vTID['APVDATE'];
						$TRXUSER		= $vTID['TRXUSER'];
						$OA_CategoryDesc= "OP";
						$myNewNo		= $myNewNo + 1;	
						$sqlRes			= $sqlRes + 1;
						
						$OP_DESC		= '';
						$getTIDDES		= "SELECT OP_DESC FROM OP_DT WHERE OP_CODE = '$OA_Code'";
						$qTIDDES		= odbc_exec($odbc, $getTIDDES) or die (odbc_errormsg());
						while($vTIDES = odbc_fetch_array($qTIDDES))
						{
							$OP_DESC		= $vTIDES['OP_DESC'];
						}
						?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $APVDATE; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap><?php print $OP_DESC; ?></td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
					}
					odbc_close_all();
				}
			}
		}
		//if($selSearchTypex == "OPNHD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'MD' || $LHint == 'MG' || $LHint == 'UP' || $LHint == 'EA' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR' || $LHint == 'OP')) // QUERY - 3
		if($selSearchTypex == "OPNHD") // QUERY - 3
		{
			$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
			if($def_ProjCode == "ALL")
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM OP_HD.DBF");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];

			}
			else
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM OP_HD.DBF WHERE APPROVE = FALSE AND OP_CODE LIKE'%". $txtSearch ."%' AND PRJCODE  = '$def_ProjCode'");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];
			}
			if($TOTCOUNT > 0)
			{
				$dateStarta	= date('Y-m-d H:i:s');
				$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
				$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
				if($def_ProjCode == "ALL")
				{
					$getTID		= "SELECT OPNCODE, TRXDATE, PRJCODE, TRXUSER FROM OPNHD.DBF WHERE APPROVE = FALSE AND OPNCODE LIKE '%$txtSearch%'";
				}
				else
				{
					$getTID		= "SELECT OPNCODE, TRXDATE, PRJCODE, TRXUSER FROM OPNHD.DBF WHERE APPROVE = FALSE AND OPNCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
				}
				
				$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
				$totDP_HD	= 0;			
				while($vTID = odbc_fetch_array($qTID))
				{
					$OA_Code		= $vTID['OPNCODE'];
					$date			= $vTID['TRXDATE'];
					$proj_Code		= $vTID['PRJCODE'];
					$TRXUSER		= $vTID['TRXUSER'];
					$OA_CategoryDesc= "OPNAME";
					$myNewNo		= $myNewNo + 1;	
					$sqlRes			= $sqlRes + 1;
					?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap>&nbsp;</td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
				}
				odbc_close_all();
			}
		}
		//if($selSearchTypex == "PD_HD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN')) // QUERY - 4
		if($selSearchTypex == "PD_HD") // QUERY - 4
		{
			$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
			if($def_ProjCode == "ALL")
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM PD_HD.DBF WHERE APPROVE = FALSE AND TDPCODE LIKE'%". $txtSearch ."%'");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];

			}
			else
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM PD_HD.DBF WHERE APPROVE = FALSE AND TDPCODE LIKE'%". $txtSearch ."%' AND PRJCODE  = '$def_ProjCode'");
				$arr 		= odbc_fetch_array($rs);
    			$TOTCOUNT	= $arr['counter'];
			}
			if($TOTCOUNT > 0)
			{
				$dateStarta	= date('Y-m-d H:i:s');
				$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
				$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
				if($def_ProjCode == "ALL")
				{
					$getTID		= "SELECT TDPCODE, TDPDATE, PRJCODE, TRXUSER FROM PD_HD.DBF WHERE APPROVE = FALSE AND TDPCODE LIKE '%$txtSearch%'";
				}
				else
				{
					$getTID		= "SELECT TDPCODE, TDPDATE, PRJCODE, TRXUSER FROM PD_HD.DBF WHERE APPROVE = FALSE AND TDPCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
				}
				//echo "Step 17 = $TOTCOUNT<br>";
								
				$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
				//echo "Step 17 = $TOTCOUNT<br>";
				$totDP_HD	= 0;			
				while($vTID = odbc_fetch_array($qTID))
				{
					$OA_Code		= $vTID['TDPCODE'];
					$date			= $vTID['TDPDATE'];
					$proj_Code		= $vTID['PRJCODE'];
					$TRXUSER		= $vTID['TRXUSER'];
					$OA_CategoryDesc= "PD";
					$myNewNo		= $myNewNo + 1;	
					$sqlRes			= $sqlRes + 1;
					?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap>&nbsp;</td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
				}
				odbc_close_all();
			}
		}
		//if($selSearchTypex == "SPKHD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF' || $LHint == 'UP' || $LHint == 'EA')) // QUERY - 5
		if($selSearchTypex == "SPKHD") // QUERY - 5
		{
			//if($selSearchTypex == "SPKHD")
			//{
				/*$getTIDC		= "SELECT COUNT(*) AS TOTCOUNT FROM SPKHD.DBF WHERE APPROVE = FALSE AND SPKCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
				$qTIDC 			= odbc_exec($odbc, $getTIDC) or die (odbc_errormsg());			
				while($vTIDC = odbc_fetch_array($qTIDC))
				{
					$TOTCOUNT		= $vTIDC['TOTCOUNT'];
				}*/
				//$sqlRes		= 0;
				$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
				if($def_ProjCode == "ALL")
				{
					$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM SPKHD.DBF WHERE APPROVE = FALSE AND SPKCODE LIKE'%". $txtSearch ."%'");
					$arr 		= odbc_fetch_array($rs);
					$TOTCOUNT	= $arr['counter'];
	
				}
				else
				{
					$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM SPKHD.DBF WHERE APPROVE = FALSE AND SPKCODE LIKE'%". $txtSearch ."%' AND PRJCODE  = '$def_ProjCode'");
					$arr 		= odbc_fetch_array($rs);
					$TOTCOUNT	= $arr['counter'];
				}
				if($TOTCOUNT > 0)
				{
					$dateStarta	= date('Y-m-d H:i:s');
					$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
					$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
					if($def_ProjCode == "ALL")
					{
						$getTID		= "SELECT SPKCODE, TRXDATE, PRJCODE, TRXUSER FROM SPKHD.DBF WHERE APPROVE = FALSE AND SPKCODE LIKE '%$txtSearch%'";
					}
					else
					{
						$getTID		= "SELECT SPKCODE, TRXDATE, PRJCODE, TRXUSER FROM SPKHD.DBF WHERE APPROVE = FALSE AND SPKCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
					}
					
					$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
					$totDP_HD	= 0;			
					while($vTID = odbc_fetch_array($qTID))
					{
						$OA_Code		= $vTID['SPKCODE'];
						$date			= $vTID['TRXDATE'];
						$proj_Code		= $vTID['PRJCODE'];
						$TRXUSER		= $vTID['TRXUSER'];
						$OA_CategoryDesc= "SPK";
						$myNewNo		= $myNewNo + 1;	
						$sqlRes			= $sqlRes + 1;
						?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap>&nbsp;</td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
					}
					odbc_close_all();
				}
				if($sqlRes == 0)
				{
					?>
      <tr>
        <td colspan="6" style="text-align:center">--- None ---</td>
      </tr>
      <?php
				}
			//}
		}
		//if($selSearchTypex == "SPPHD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF' || $LHint == 'UP' || $LHint == 'EA')) // QUERY - 6
		if($selSearchTypex == "SPPHD") // QUERY - 6
		{
			$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
			if($def_ProjCode == "ALL")
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM SPPHD.DBF WHERE APPROVE = FALSE AND SPPCODE LIKE '%". $txtSearch ."%'");
				$arr 		= odbc_fetch_array($rs);
				$TOTCOUNT	= $arr['counter'];

			}
			else
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM SPPHD.DBF WHERE APPROVE = 0 AND SPPCODE LIKE '%". $txtSearch ."%' AND PRJCODE LIKE '%$def_ProjCode%'");
				$arr 		= odbc_fetch_array($rs);
				$TOTCOUNT	= $arr['counter'];
			}
			if($showAppDate == 0)
			{
				if($TOTCOUNT > 0)
				{
					$dateStarta	= date('Y-m-d H:i:s');
					$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
					$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
					if($def_ProjCode == "ALL")
					{
						$getTID		= "SELECT SPPCODE, TRXDATE, PRJCODE, TRXUSER FROM SPPHD.DBF WHERE APPROVE = FALSE AND SPPCODE LIKE '%$txtSearch%'";
					}
					else
					{
						$getTID		= "SELECT SPPCODE, TRXDATE, PRJCODE, TRXUSER FROM SPPHD.DBF WHERE APPROVE = FALSE AND SPPCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
					}
					
					$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
					$totDP_HD	= 0;			
					while($vTID = odbc_fetch_array($qTID))
					{
						$OA_Code		= $vTID['SPPCODE'];
						$date			= $vTID['TRXDATE'];
						$proj_Code		= $vTID['PRJCODE'];
						$TRXUSER		= $vTID['TRXUSER'];
						$OA_CategoryDesc= "SPP";
						$myNewNo		= $myNewNo + 1;	
						$sqlRes			= $sqlRes + 1;
						
						$SPPDESC		= '';
						$getTIDDES		= "SELECT SPPDESC FROM SPPDT WHERE SPPCODE = '$OA_Code'";
						$qTIDDES		= odbc_exec($odbc, $getTIDDES) or die (odbc_errormsg());
						while($vTIDES = odbc_fetch_array($qTIDDES))
						{
							$SPPDESC		= $vTIDES['SPPDESC'];
						}
						?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap><?php print $SPPDESC; ?></td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
					}
					odbc_close_all();
				}
			}
			elseif($showAppDate == 1)
			{
				if($TOTCOUNT > 0)
				{
					$dateStarta	= date('Y-m-d H:i:s');
					$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
					$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
					if($def_ProjCode == "ALL")
					{
						$getTID		= "SELECT SPPCODE, TRXDATE, PRJCODE, TRXPDAT, TRXUSER FROM SPPHD.DBF WHERE APPROVE = TRUE AND SPPCODE LIKE '%$txtSearch%'";
					}
					else
					{
						$getTID		= "SELECT SPPCODE, TRXDATE, PRJCODE, APVDATE, TRXUSER FROM SPPHD.DBF 
										WHERE APPROVE = TRUE AND SPPCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
					}
					
					$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
					$totDP_HD	= 0;			
					while($vTID = odbc_fetch_array($qTID))
					{
						$OA_Code		= $vTID['SPPCODE'];
						$date			= $vTID['TRXDATE'];
						$proj_Code		= $vTID['PRJCODE'];
						$APVDATE		= $vTID['APVDATE'];
						$TRXUSER		= $vTID['TRXUSER'];
						
						$OA_CategoryDesc= "SPP";
						$myNewNo		= $myNewNo + 1;	
						$sqlRes			= $sqlRes + 1;
						
						$SPPDESC		= '';
						$getTIDDES		= "SELECT SPPDESC FROM SPPDT WHERE SPPCODE = '$OA_Code'";
						$qTIDDES		= odbc_exec($odbc, $getTIDDES) or die (odbc_errormsg());
						while($vTIDES = odbc_fetch_array($qTIDDES))
						{
							$SPPDESC		= $vTIDES['SPPDESC'];
						}
						?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $APVDATE; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap><?php print $SPPDESC; ?></td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
					}
					odbc_close_all();
				}
			}
		}
		//if($selSearchTypex == "VLKHD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'UP' || $LHint == 'EA')) // QUERY - 7
		if($selSearchTypex == "VLKHD") // QUERY - 7
		{
			$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
			if($def_ProjCode == "ALL")
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM VLKHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE'%". $txtSearch ."%'");
				$arr 		= odbc_fetch_array($rs);
				$TOTCOUNT	= $arr['counter'];

			}
			else
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM VLKHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE'%". $txtSearch ."%' AND PRJCODE  = '$def_ProjCode'");
				$arr 		= odbc_fetch_array($rs);
				$TOTCOUNT	= $arr['counter'];
			}
			if($TOTCOUNT > 0)
			{
				$dateStarta		= date('Y-m-d H:i:s');
				$dateStart 		= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
				$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
				if($def_ProjCode == "ALL")
				{
					$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE, TRXUSER FROM VLKHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE '%$txtSearch%'";
				}
				else
				{
					$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE, TRXUSER FROM VLKHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
				}
				
				$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
				$totDP_HD	= 0;
				while($vTID = odbc_fetch_array($qTID))
				{
					$OA_Code		= $vTID['VOCCODE'];
					$date			= $vTID['TRXDATE'];
					$proj_Code		= $vTID['PRJCODE'];
					$TRXUSER		= $vTID['TRXUSER'];
					$OA_CategoryDesc= "VLK";
					$myNewNo		= $myNewNo + 1;	
					$sqlRes			= $sqlRes + 1;
					
					$getVLKDT		= "SELECT LPODESC FROM VLKDT.DBF WHERE VOCCODE = '$OA_Code'";
					$qVLKDT 		= odbc_exec($odbc, $getVLKDT) or die (odbc_errormsg());		
					$rowVLKDT		= 0;	
					while($vVLKDT = odbc_fetch_array($qVLKDT))
					{
						$rowVLKDT	= $rowVLKDT + 1;
						if($rowVLKDT == 1)
						{
							$LPODESC		= $vVLKDT['LPODESC'];
							break;
						}
					}
					?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap><?php print $LPODESC; ?></td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
				}
				odbc_close_all();
			}
		}
		//if($selSearchTypex == "VOCHD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'MD' || $LHint == 'MG' || $LHint == 'UP' || $LHint == 'EA' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR')) // QUERY - 8
		if($selSearchTypex == "VOCHD") // QUERY - 8
		{
			//$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
			if($def_ProjCode == "ALL")
			{
				//$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM VOCHD.DBF");
				//$arr 		= odbc_fetch_array($rs);
				$sql		= "vochd WHERE APPROVE = 0";
				$TOTCOUNT	= $this->db->count_all($sql);

			}
			else
			{
				$sql		= "vochd WHERE APPROVE = 0 AND VOCCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
				$TOTCOUNT	= $this->db->count_all($sql);
			}
			if($TOTCOUNT > 0)
			{
				$dateStarta	= date('Y-m-d H:i:s');
				$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
				$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
				if($def_ProjCode == "ALL")
				{
					//$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE, TRXUSER FROM VOCHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE '%$txtSearch%'";
					$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE, TRXUSER FROM VOCHD/WHERE APPROVE = FALSE AND VOCCODE LIKE '%$txtSearch%'";
				}
				else
				{
					//$getTID	= "SELECT VOCCODE, TRXDATE, PRJCODE, TRXUSER FROM VOCHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
					$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE, TRXUSER FROM VOCHD WHERE APPROVE = 0 AND VOCCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
				}
				
				//$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
				$qTID		= $this->db->query($getTID)->result();
				$totDP_HD	= 0;			
				//while($vTID = odbc_fetch_array($qTID))
				foreach($qTID as $vTID) :
				{
					$OA_Code		= $vTID->VOCCODE;
					$date			= $vTID->TRXDATE;
					$proj_Code		= $vTID->PRJCODE;
					$TRXUSER		= $vTID->TRXUSER;
					$OA_CategoryDesc= "VOUCHER";
					$myNewNo		= $myNewNo + 1;	
					$sqlRes			= $sqlRes + 1;
					
					$getVOCDT		= "SELECT LPODESC FROM VOCDT.DBF WHERE VOCCODE = '$OA_Code'";
					$qVOCDT 		= odbc_exec($odbc, $getVOCDT) or die (odbc_errormsg());		
					$rowVOCDT		= 0;	
					while($vVOCDT = odbc_fetch_array($qVOCDT))
					{
						$rowVOCDT	= $rowVOCDT + 1;
						if($rowVOCDT == 1)
						{
							$LPODESC		= $vVOCDT['LPODESC'];
							break;
						}
					}
					?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap><?php print $LPODESC; ?></td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
				}
				endforeach;
				//odbc_close_all();
			}
		}
		//if($selSearchTypex == "VOTHD" && ($LHint == 'DH' || $LHint == 'DUA' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'MD' || $LHint == 'MG' || $LHint == 'UP' || $LHint == 'EA' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR')) // QUERY - 9
		if($selSearchTypex == "VOTHD") // QUERY - 9
		{
			$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
			if($def_ProjCode == "ALL")
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM VOTHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE'%". $txtSearch ."%'");
				$arr 		= odbc_fetch_array($rs);
				$TOTCOUNT	= $arr['counter'];

			}
			else
			{
				$rs 		= odbc_exec($odbc, "SELECT Count(*) AS counter FROM VOTHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE'%". $txtSearch ."%' AND PRJCODE  = '$def_ProjCode'");
				$arr 		= odbc_fetch_array($rs);
				$TOTCOUNT	= $arr['counter'];
			}
			if($TOTCOUNT > 0)
			{
				$dateStarta	= date('Y-m-d H:i:s');
				$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
				$odbc 			= odbc_connect ('DBaseNKE2', '', '') or die('Could Not Connect to ODBC Database!');
				if($def_ProjCode == "ALL")
				{
					$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE, TRXUSER FROM VOTHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE '%$txtSearch%'";
				}
				else
				{
					$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE, TRXUSER FROM VOTHD.DBF WHERE APPROVE = FALSE AND VOCCODE LIKE '%$txtSearch%' AND PRJCODE  = '$def_ProjCode'";
				}
				
				$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
				$totDP_HD	= 0;			
				while($vTID = odbc_fetch_array($qTID))
				{
					$OA_Code		= $vTID['VOCCODE'];
					$date			= $vTID['TRXDATE'];
					$proj_Code		= $vTID['PRJCODE'];
					$TRXUSER		= $vTID['TRXUSER'];
					$OA_CategoryDesc= "VOT";
					$myNewNo		= $myNewNo + 1;	
					$sqlRes			= $sqlRes + 1;
					?>
      <tr>
        <td><?php print $myNewNo; ?>. </td>
        <td nowrap><?php print anchor("$url_updtDoc",$OA_CategoryDesc,array('class' => 'update', 'onClick' => 'testing();')).' '; ?> </td>
        <td nowrap><?php print $OA_Code; ?> </td>
        <td nowrap><?php print $date; ?> </td>
        <td nowrap><?php print $proj_Code; ?></td>
        <td nowrap>&nbsp;</td>
        <td ><?php echo $TRXUSER; ?></td>
      </tr>
      <?php
				}
				odbc_close_all();
			}
		}
    ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="6" style="text-align:left"><input type="submit" name="btnSrch" id="btnSrch" class="btn btn-primary" value=" Search Again " onClick="showSrchAgain()" /></td>
      </tr>
    </tfoot>
  </table>
</form>
</div>
<!-- /.box-body -->
</div>
  <!-- /.box -->
</div>
</body>