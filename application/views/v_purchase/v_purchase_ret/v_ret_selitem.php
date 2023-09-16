<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 Juni 2020
 * File Name	= v_ret_selitem.php
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
    <title><?php echo $appName; ?>SW</title>
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
	$LangID 		= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
		if($TranslCode == 'Ordered')$Ordered = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'Requested')$Requested = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
        if($TranslCode == 'Description')$Description = $LangTransl;
        if($TranslCode == 'ReceiptQty')$ReceiptQty = $LangTransl;
        if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
        if($TranslCode == 'PONo')$PONo = $LangTransl;
        if($TranslCode == 'receiptNo')$receiptNo = $LangTransl;
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
                <?php echo $PleaseSelectItem; ?>
            </div>
                <div class="search-table-outter">
                    <form method="post" name="frmSearch" action="">
                          <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                            <thead>
                                <tr>
                                    <th width="4%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
                                    <th width="5%" nowrap><?php echo $ItemCode; ?> </th>
                                    <th width="30%" nowrap><?php echo $ItemName; ?> </th>
                                    <th width="13%" nowrap><?php echo $receiptNo; ?>  </th>
                                    <th width="13%" nowrap><?php echo $PONo; ?>  </th>
                                    <th width="10%" nowrap><?php echo $Ordered; ?></th>
                                    <th width="10%" nowrap><?php echo $ReceiptQty; ?></th>
                                    <th width="10%" nowrap><?php echo $StockQuantity; ?>  </th>
                                    <th width="5%" nowrap><?php echo $Unit; ?> </th>
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
                                        $IR_ID 	        = $row->IR_ID;
                                        $PRJCODE 		= $row->PRJCODE;
                                        $DEPCODE 		= $row->DEPCODE;
                                        $IR_NUM 		= $row->IR_NUM;
                                        $IR_CODE 		= $row->IR_CODE;
                                        $IR_DATE 		= $row->IR_DATE;
                                        $ACC_ID 		= $row->ACC_ID;	
                                        $POD_ID         = $row->POD_ID;
                                        $PO_NUM 		= $row->PO_NUM;
                                        $PO_CODE 		= $row->PO_CODE;
                                        $JOBCODEID 		= $row->JOBCODEID;
										$WH_CODE 		= $row->WH_CODE;
										$ITM_CODE		= $row->ITM_CODE;
                                        $ITM_NAME       = $row->ITM_NAME;
										$ITM_UNIT 		= $row->ITM_UNIT;
										$ITM_GROUP		= $row->ITM_GROUP;
                                        $PO_VOLM        = $row->PO_VOLM;           // PO QTY
										$ITM_QTY 		= $row->ITM_QTY;           // IR QTY
                                        $ITM_PRICE      = $row->ITM_PRICE;
                                        $ITM_TOTAL      = $row->ITM_TOTAL;
                                        $ITM_DISC       = $row->ITM_DISC;
                                        $RET_VOLM       = $row->RET_VOLM;
                                        $RET_AMOUNT     = $row->RET_AMOUNT;
                                        $ITM_VOLM       = $row->ITM_VOLM;           // STOCK
                                        
                                        // GET JOB DETAIL
                                        $JOBDESC		= '';
                                        $sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
                                        $resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
                                        foreach($resJOBDESC as $rowJOBDESC) :
                                            $JOBDESC	= $rowJOBDESC->JOBDESC;
                                        endforeach;
                                        
                                        $totRow		= $totRow + 1;
                                    
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                        ?> 
                                        <td style="text-align:center">
                                            <input type="checkbox" name="chk" value="<?php echo $IR_ID;?>|<?php echo $PRJCODE;?>|<?php echo $IR_NUM;?>|<?php echo $ACC_ID;?>|<?php echo $POD_ID;?>|<?php echo $PO_NUM;?>|<?php echo $JOBCODEID;?>|<?php echo $WH_CODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_GROUP;?>|<?php echo $ITM_QTY;?>|<?php echo $ITM_PRICE;?>|<?php echo $ITM_TOTAL;?>|<?php echo $ITM_VOLM;?>|<?php echo $IR_CODE;?>|<?php echo $PO_CODE;?>" onClick="pickThis(this);" />
                                        </td>
                                        <td><?php echo $ITM_CODE; ?></td>
                                        <td><?php echo $ITM_NAME; ?></td>
                                        <td><?php echo $IR_CODE; ?></td>
                                        <td><?php echo $PO_CODE; ?></td>
                                        <td nowrap style="text-align:right"><?php echo number_format($PO_VOLM, $decFormat); ?></td>
                                        <td nowrap style="text-align:right"><?php echo number_format($ITM_QTY, $decFormat); ?></td>
                                        <td nowrap style="text-align:right"><?php echo number_format($ITM_VOLM, $decFormat); ?>&nbsp;</td>
                                        <td nowrap style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                                    </tr>
                                    <?php
                                    endforeach;
                                }
                            ?>
                            </tbody>
                            <tr>
                              <td colspan="9" nowrap>
                                <button class="btn btn-primary" type="button" onClick="get_item();">
                                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                    <button class="btn btn-danger" type="button" onClick="window.close()">
                                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
                           		</td>
                            </tr>
                        </table>
                  </form>
            </div>
        </div>
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
	//swal("test1");
	if (selectedRows==NumOfRows) 
	{
		//swal("test2");
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