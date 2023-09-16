<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 Maret 2018
 * File Name	= v_select_invr.php
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

$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'RealisasiNumber')$RealisasiNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		if($TranslCode == 'PPn')$PPn = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'ReceiptFrom')$ReceiptFrom = $LangTransl;
		if($TranslCode == 'Paid')$Paid = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$h1_title	= "Silahkan pilih faktur di bawah ini.";
	}
	else
	{
		$h1_title	= "Please select an invoice below.";
	}
	
	if($BR_RECTYPE == 'SAL')
	{
		$OWNDESC	= '-';
		$sqlOWN		= "SELECT '' AS own_Title, CUST_CODE AS own_Name FROM tbl_customer WHERE CUST_CODE = '$BR_PAYFROM'";
		$resOWN		= $this->db->query($sqlOWN)->result();
		foreach($resOWN as $rowOWN) :
			$own_Title	= $rowOWN->own_Title;
			if($own_Title != '')
				$own_Title 	= ", $own_Title";
			else
				$own_Title	= "";
			$own_Name	= $rowOWN->own_Name;
		endforeach;
	}
	elseif($BR_RECTYPE == 'PRJ')
	{
		$OWNDESC	= '-';
		$sqlOWN		= "SELECT own_Title, own_Name FROM tbl_owner WHERE own_Code = '$BR_PAYFROM'";
		$resOWN		= $this->db->query($sqlOWN)->result();
		foreach($resOWN as $rowOWN) :
			$own_Title	= $rowOWN->own_Title;
			if($own_Title != '')
				$own_Title 	= ", $own_Title";
			else
				$own_Title	= "";
			$own_Name	= $rowOWN->own_Name;
		endforeach;
	}
	elseif($BR_RECTYPE == 'DP')
	{
		$OWNDESC	= '-';
		$sqlOWN		= "SELECT own_Title, own_Name FROM tbl_owner WHERE own_Code = '$BR_PAYFROM'";
		$resOWN		= $this->db->query($sqlOWN)->result();
		foreach($resOWN as $rowOWN) :
			$own_Title	= $rowOWN->own_Title;
			if($own_Title != '')
				$own_Title 	= ", $own_Title";
			else
				$own_Title	= "";
			$own_Name	= $rowOWN->own_Name;
		endforeach;
		$OWNDESC	= "$own_Name$own_Title";
	}
	else
	{
		$OWNDESC	= '-';
		$own_Title	= "";
	}
	$OWNDESC	= "$own_Name$own_Title";
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
        <?php echo $h1_title; ?>
    </div>
	<div class="search-table-outter">
        <form method="post" name="frmSearch" action="">
              <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                <thead>
                    <tr>
                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
                        <th width="10%" nowrap><?php echo $RealisasiNumber; ?> </th>
                        <th width="5%"><?php echo $Date; ?> </th>
                        <th width="22%" style="text-align:center" nowrap><?php echo $ReceiptFrom; ?>  </th>
                        <th width="37%" style="text-align:center" nowrap><?php echo $Description; ?></th>
                        <th width="14%" style="text-align:center" nowrap><?php echo $Amount; ?></th>
                        <th width="4%" style="text-align:center; display:none" nowrap><?php echo $Paid; ?></th>
                        <th width="5%" style="text-align:center;" nowrap><?php echo $Paid; ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    $j = 0;
                    if($countINV>0)
                    {
                        $totRow	= 0;
                        foreach($vwINV as $row) :							
							$PINV_CODE 			= $row->PINV_CODE;
							$PINV_MANNO			= $row->PINV_MANNO;
							$PINV_DATE			= $row->PINV_DATE;
							$PINV_ENDDATE		= $row->PINV_ENDDATE;
							$PINV_CAT			= $row->PINV_CAT;
							$PINV_DPVAL			= $row->PINV_DPVAL;
							$PINV_DPVALPPn		= $row->PINV_DPVALPPn;
							$PINV_AMOUNT 		= $row->PINV_TOTVAL;
							$PINV_AMOUNT_PPn	= $row->PINV_TOTVALPPn;
							if($PINV_CAT == 1)
							{
								$PINV_AMOUNT 		= $PINV_DPVAL;
								$PINV_AMOUNT_PPn	= $PINV_DPVALPPn;
							}
							$TOTNPPN			= $PINV_AMOUNT + $PINV_AMOUNT_PPn;
							$PINV_AMOUNT_PPh	= 0.03 * $PINV_AMOUNT;
							$PINV_AMOUNT_OTH	= 0;
							$GTOTNPPN			= $TOTNPPN - $PINV_AMOUNT_PPh;
							$PINV_NOTES 		= $row->PINV_NOTES;
							$PINV_Number 		= $row->PINV_CODE;
							$PINV_STEP			= $row->PINV_STEP;
							$PINV_STEP			= $row->PINV_STEP;
							$OWN_NAME			= $row->OWN_NAME;
							$GPINV_TOTVAL		= $row->GPINV_TOTVAL;
							$PINV_PAIDAM		= $row->PINV_PAIDAM;
							$GPINV_REMAIN		= $GPINV_TOTVAL - $PINV_PAIDAM;
							
							if($PINV_CAT == 1)
								$PINV_CATD	= "Pembayaran DP termin ke - $PINV_STEP";
							elseif($PINV_CAT == 2)
								$PINV_CATD	= "Pembayaran MC termin ke -  $PINV_STEP";
							elseif($PINV_CAT == 2)
								$PINV_CATD	= "Pembayaran SI termin ke -  $PINV_STEP";
							else
								$PINV_CATD	= $PINV_NOTES;
							
							$PRINV_DESC 		= $PINV_CATD;
							
                            $totRow		= $totRow + 1;
						
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							
							
							?>
                            <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $PINV_CODE;?>|<?php echo $PINV_CATD;?>|<?php echo $PINV_AMOUNT;?>|<?php echo $PINV_AMOUNT_PPn;?>|<?php echo $PINV_AMOUNT_PPh;?>|<?php echo $PINV_AMOUNT_OTH;?>|<?php echo $PINV_CATD;?>|<?php echo $PINV_Number;?>|<?php echo $PINV_MANNO;?>|<?php echo $GPINV_TOTVAL;?>|<?php echo $GPINV_REMAIN;?>" onClick="pickThis(this);" /></td>
                            <td nowrap><?php echo $PINV_MANNO; ?></td>
                            <td><?php echo $PINV_DATE; ?></td>
                            <td nowrap><?php echo $OWN_NAME; ?>&nbsp;</td>
                            <td nowrap><?php echo $PINV_NOTES; ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($GPINV_TOTVAL, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:right; display:none"><?php echo number_format($PINV_AMOUNT_PPn, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:right;"><?php echo number_format($PINV_PAIDAM, $decFormat); ?></td>
                        </tr>
                        <?php
                        endforeach;
                    }
                ?>
                </tbody>
                <tfoot>
                <tr>
                  <td colspan="8" nowrap>
                    <button class="btn btn-primary" type="button" onClick="get_item();">
                    <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                    </button>&nbsp;
                    <button class="btn btn-danger" type="button" onClick="window.close()">
                    <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                    </button>
                  </td>
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
		//alert(document.frmSearch.chk.length) 
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
				//alert('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//alert(arrItem)
				for(z=1;z<=5;z++)
				{
					alert('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					alert(B)
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