<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2017
 * File Name	= item_list_selectitem.php
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
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'ReceiptQty')$ReceiptQty = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'TotalPrice')$TotalPrice = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Stock')$Stock = $LangTransl;
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
        Please select items below.
    </div>
	<div class="search-table-outter">
        <form method="post" name="frmSearch" action="">
              <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                <thead>
                    <tr>
                        <th width="2%" rowspan="2"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
                        <th width="11%" rowspan="2" style="vertical-align:middle; text-align:center" nowrap><?php echo $ItemCode ?> </th>
                   	 	 <th width="57%" rowspan="2" style="vertical-align:middle; text-align:center"><?php echo $ItemName ?> </th>
                   	  	<th colspan="5" nowrap style="text-align:center"><?php echo $ItemQty ?> </th>
                      	<th width="2%" rowspan="2" nowrap><?php echo $Unit ?> </th>
                  </tr>
                    <tr>
                      <th width="6%" nowrap><?php echo $BudgetQty; ?>  </th>
                      <th width="5%" nowrap><?php echo $Ordered; ?></th>
                      <th width="6%" nowrap><?php echo $ReceiptQty; ?>  </th>
                      <th width="6%" nowrap><?php echo $Used; ?>  </th>
                      <th width="5%" nowrap><?php echo $Stock; ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    $j = 0;
                    if($countAllItem>0)
                    {
                        $totRow	= 0;
                        foreach($vwAllItem as $row) :
                            $PRJCODE 		= $row->PRJCODE;
                            $ITM_CODE 		= $row->ITM_CODE;				// 0
                            $serialNumber	= '';							// 1
                            $ITM_NAME 		= $row->ITM_NAME;				// 2
                            $ITM_UNIT 		= $row->ITM_UNIT;				// 3
                            $Unit_Type_Name = $row->ITM_UNIT;				// 4
                            $ITM_UNIT2		= $row->ITM_UNIT;				// 5
                            $Unit_Type_Name2= $row->ITM_UNIT;				// 6
                            $itemConvertion	= 1;							// 9
                            $ITM_VOLM		= $row->ITM_VOLM;				// 10
                            $ITM_PRICE 		= $row->ITM_PRICE;				// 11
                            $Price 			= $row->ITM_PRICE;
							$ITM_TOTALP		= $ITM_VOLM * $ITM_PRICE;
                            $PO_VOLM 		= $row->PO_VOLM;
                            $PO_AMOUNT 		= $row->PO_AMOUNT;
                            $ITM_IN 		= $row->IR_VOLM;
                            $ITM_OUT 		= $row->ITM_USED;
                            $ITM_STOCK 		= $row->ITM_STOCK;
                            $ACC_ID 		= $row->ACC_ID;					// 12
                            $JOBCODEDET		= $row->JOBCODEDET;				// 13
                            $JOBCODEID 		= $row->JOBCODEID;				// 14
                            $totRow			= $totRow + 1;
							
							//$TotQTYIN		= $ITM_VOLM - $ITM_IN + $ITM_OUT + $ITM_IN;
							$TotQTYIN		= $ITM_IN + $ITM_OUT;
																				
							// Mencari nilai pengeluaran dari tabel Asset Usage Detail berstatus 3 (Approve)
							/*$AkumQty_UP			= 0;
							$sqlTotReserve		= "SELECT SUM(A.ITM_QTY) AS AkumQty_UM, SUM(A.ITM_QTY * A.ITM_PRICE) AS AkumQty_UP
													FROM tbl_asset_usagedet A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
														INNER JOIN tbl_asset_usage C ON C.AU_CODE = A.AU_CODE
													WHERE
														A.ITM_CODE = '$ITM_CODE'
														AND A.AU_PRJCODE = '$PRJCODE'
														AND C.AU_STAT = 3";
							$ressqlTotReserve	= $this->db->query($sqlTotReserve)->result();
							foreach($ressqlTotReserve as $rowTR) :
								$AkumQty_UM 	= $rowTR->AkumQty_UM;
								$AkumQty_UP 	= $rowTR->AkumQty_UP;
							endforeach;							
							$ITM_OUT			= $AkumQty_UM;*/
							
							//$ITM_ONHAND		= $ITM_IN - $ITM_OUT;
							///$ITM_ONHAND		= $ITM_VOLM - $ITM_OUT;
							
							$ITM_ONHAND			= $TotQTYIN - $ITM_OUT;
							//$ITM_ONHANDP		= $ITM_TOTALP - $AkumQty_UP;
							//$AVGPRICE			= $ITM_ONHANDP / $ITM_ONHAND;
							// REMAIN QTY
							//$REM_QTY			= $ITM_VOLM - $TOT_USEDQTY;
							$REM_QTY			= $ITM_ONHAND;
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?> 
                            <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $ITM_CODE;?>|<?php echo $serialNumber;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>|<?php echo $Unit_Type_Name;?>|<?php echo $ITM_UNIT2;?>|<?php echo $Unit_Type_Name2;?>|0|0|<?php echo $itemConvertion;?>|<?php echo $REM_QTY;?>|<?php echo $ITM_PRICE;?>|<?php echo $ACC_ID;?>|<?php echo $JOBCODEDET;?>|<?php echo $JOBCODEID;?>" onClick="pickThis(this);" /></td>
                            <td nowrap><?php echo $ITM_CODE; ?></td>
                            <td nowrap><?php echo $ITM_NAME; ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($ITM_VOLM, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:right"><?php echo number_format($PO_VOLM, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($ITM_IN, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:right"><?php echo number_format($ITM_OUT, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:right"><?php echo number_format($ITM_STOCK, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                        </tr>
                        <?php
                        endforeach;
                    }
                ?>
                </tbody>
                <tr>
                  <td colspan="10" nowrap>
                   <!-- <input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value=" Select" onClick="get_item()" />&nbsp;
                    <input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value=" Close " onClick="window.close()" />  -->               
                 	
                    <button class="btn btn-primary" type="button" onClick="get_item();">
                        <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                    
                    <button class="btn btn-danger" type="button" onClick="window.close()">
                        <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
                </tr>
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