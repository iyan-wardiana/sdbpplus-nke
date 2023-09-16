<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Juli 2019
 * File Name	= v_vendor_aplist.php
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

function cut_text($var, $len = 200, $txt_titik = "...") 
{
	$var1	= explode("</p>",$var);
	$var	= $var1[0];
	if (strlen ($var) < $len) 
	{ 
		return $var; 
	}
	if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
	{
		return $match [1] . $txt_titik;
	}
	else
	{
		return substr ($var, 0, $len) . $txt_titik;
	}
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
</head>

<?php
	$SPLDESC	= '';
	$sqlSPLD	= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
	$resSPLD	= $this->db->query($sqlSPLD)->result();
	foreach($resSPLD as $rowSPLD) :
		$SPLDESC	= $rowSPLD->SPLDESC;
	endforeach;
	$LangID 		= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		if($TranslCode == 'payableList')$payableList = $LangTransl;
		if($TranslCode == 'SupplierCode')$SupplierCode = $LangTransl;
		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
		if($TranslCode == 'SupplierCode')$SupplierCode = $LangTransl;
		if($TranslCode == 'SupplierCode')$SupplierCode = $LangTransl;
		if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'InvoiceAmount')$InvoiceAmount = $LangTransl;
		if($TranslCode == 'Paid')$Paid = $LangTransl;
		if($TranslCode == 'Remain')$Remain = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
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
    <div class="callout callout-success">
        <?php //echo "$payableList : $SPLDESC"; ?>
        <table width="100%">
            <tr>
                <td width="4%" nowrap><?php echo $SupplierCode; ?></td>
                <td width="1%" nowrap>:</td
                ><td width="95%" nowrap><?php echo $SPLCODE; ?></td>
          	</tr>
         	<tr>
                <td width="4%" nowrap><?php echo $SupplierName; ?></td>
                <td width="1%" nowrap>:</td>
                <td width="95%" style="font-size:16px" nowrap><?php echo $SPLDESC; ?></td>
          	</tr>
         	<tr>
                <td width="4%" nowrap>Total</td>
                <td width="1%" nowrap>:</td
                ><td width="95%" style="font-size:16px" nowrap><?php echo number_format($REMTOT, 2); ?></td>
          	</tr>
    	</table>
    </div>
	<div class="search-table-outter">
        <form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return validateInData()">
              <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center; vertical-align:middle" width="2%" nowrap>No.</th>
                        <th style="text-align:center; vertical-align:middle" width="4%" nowrap><?php echo $InvoiceNo; ?></th>
                        <th style="text-align:center; vertical-align:middle" width="3%" nowrap><?php echo $Date; ?></th>
                        <th style="text-align:center; vertical-align:middle" width="12%" nowrap><?php echo $DueDate; ?>  </th>
                        <th style="text-align:center; vertical-align:middle" width="50%" nowrap><?php echo $Notes; ?></th>
                        <th style="text-align:center; vertical-align:middle" width="11%" nowrap><?php echo "Nominal"; ?></th>
                        <th style="text-align:center; vertical-align:middle" width="10%" nowrap><?php echo $Paid; ?>  </th>
                        <th style="text-align:center; vertical-align:middle" width="8%" nowrap><?php echo $Remain; ?>  </th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    $j = 0;
                    if($countAP>0)
                    {
                        $totRow	= 0;
						$totREM	= 0;
                        foreach($vwAP as $row) :
							$i			= $i + 1;
							$INV_NUM 		= $row->INV_NUM;
							$INV_CODE 		= $row->INV_CODE;
							$INV_CATEG 		= $row->INV_CATEG;
							$PO_NUM 		= $row->PO_NUM;
							$IR_NUM 		= $row->IR_NUM;
							$PRJCODE 		= $row->PRJCODE;
							$INV_DATE 		= $row->INV_DATE;
							$INV_DUEDATE	= $row->INV_DUEDATE;
							$INV_AMOUNTBASE = $row->INV_AMOUNT_BASE;
							$INV_AMOUNTPAID = $row->INV_AMOUNT_PAID;
							$REM_INV_AMOUNT	= $INV_AMOUNTBASE - $INV_AMOUNTPAID;
							$totREM			= $totREM + $REM_INV_AMOUNT;
							$INV_TERM 		= $row->INV_TERM;
							$INV_NOTES 		= $row->INV_NOTES;
							
                            $totRow		= $totRow + 1;
							
							$isDD	= 0;
							$bgCol	= "#FC0";
							if($INV_DUEDATE < date('Y-m-d'))
							{
								$isDD	= 1;
								$bgCol	= "FC0";
							}
							if ($j==1) {
								echo "<tr class=zebra1 style='background-color:".$bgCol."'";
								$j++;
							} else {
								echo "<tr class=zebra2 style='background-color:".$bgCol."'>";
								$j--;
							}
							?>
                            <td style="text-align:center; background-color:" nowrap><?php echo $i; ?>.</td>
                            <td nowrap style="text-align:center"><?php echo $INV_CODE; ?></td>
                            <td style="text-align:center" nowrap><?php echo $INV_DATE; ?></td>
                            <td style="text-align:center" nowrap><?php echo $INV_DUEDATE; ?></td>
                            <td nowrap><?php echo $INV_NOTES; ?></td>
                            <td style="text-align:right" nowrap><?php echo number_format($INV_AMOUNTBASE, 2); ?></td>
                            <td style="text-align:right" nowrap><?php echo number_format($INV_AMOUNTPAID, 2); ?></td>
                            <td style="text-align:right" nowrap><?php echo number_format($REM_INV_AMOUNT, 2); ?></td>
                        </tr>
                        <?php
                        endforeach;
                    }
                ?>
                </tbody>
            </table>
      </form>
    </div>
    <br>
    <button class="btn btn-danger" type="button" onClick="window.close()">
    	<i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
    </button>
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
var selectedRows = 0
function check_all(chk) {
	if(chk.checked) {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = true;
			}
		} else {
			document.frmSearch.chk.checked = true;
		}
		selectedRows = document.frmSearch.chk.length;
	} else {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = false;
			}
		} else {
			document.frmSearch.chk.checked = false;
		}
		selectedRows = 0;
	}
}

function pickThis(thisobj) 
{
	var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
	if (thisobj!= '') 
	{
		if (thisobj.checked) selectedRows++;
		else selectedRows--;
	}
	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}
	

function get_item() 
	{ 
		//swal(document.frmSearch.chk.length) 
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				if(document.frmSearch.chk[i].checked) 
				{
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');

					window.opener.add_item(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_item(document.frmSearch.chk.value);
				//swal('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//swal(arrItem)
				for(z=1;z<=5;z++)
				{
					swal('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					swal(B)
				}*/
			}
		}
		window.close();		
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>