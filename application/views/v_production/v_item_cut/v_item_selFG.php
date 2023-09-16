<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Januari 2019
 * File Name	= v_so_selitemFG.php
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
    <title>Pilih Kain</title>
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
		if($TranslCode == 'Remain')$Remain = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'Used')$Used = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
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
                <?php echo $PleaseSelectItem; ?>
            </div>
                <div class="search-table-outter">
                    <form method="post" name="frmSearch" action="">
                          <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                    <th width="7%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
                                    <th width="22%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
                                  	<th width="8%" style="text-align:center" nowrap>Qty</th>
                                  	<th width="5%" style="text-align:center" nowrap><?php echo $Used; ?></th>
                                  	<th width="6%" style="text-align:center" nowrap><?php echo $Remain; ?></th>
                                  	<th width="6%" style="text-align:center" nowrap>&nbsp;</th>
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
                                        $QRC_NUM 		= $row->QRC_NUM;				// 2
                                        $QRC_CODEV 		= $row->QRC_CODEV;				// 3
                                        $ITM_CODE 		= $row->ITM_CODE;				// 4
                                        $ITM_NAME 		= $row->ITM_NAME;				// 5
                                        $ITM_UNIT 		= $row->ITM_UNIT;				// 7
                                        $ITM_QTY 		= $row->ITM_QTY;				// 8
                                        $USED_QTY 		= $row->USED_QTY;				// 9

                                        // GET ORDERED QTY
	                                        $TOT_ORD		= 0;
	                                        $sqlORD			= "SELECT SUM(ICUT_QTY) TOT_ORD FROM tbl_item_cuth
	                                        					WHERE PRJCODE = '$PRJCODE' AND ICUT_QRCN = '$QRC_NUM'";
	                                        $resORD			= $this->db->query($sqlORD)->result();
	                                        foreach ($resORD as $key)
	                                        {
	                                        	$TOT_ORD	= $key->TOT_ORD;
	                                        }
										
                                        $REM_QTY		= $ITM_QTY - $TOT_ORD;			// 14
                                        
                                      	$imgQRC  = base_url("qrcodelist/$PRJCODE/$QRC_NUM.png");


										$disabledB		= 0;
										if($REM_QTY <=  0)
											$disabledB	= 1;
                                        
                                        $totRow		= $totRow + 1;
                                    
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                        if($REM_QTY > 0)
                                        {
	                                        ?>
	                                        <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $PRJCODE;?>|<?php echo $QRC_NUM;?>|<?php echo $QRC_CODEV;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_QTY;?>|<?php echo $USED_QTY;?>|<?php echo $REM_QTY;?>" onClick="pickThis(this);" <?php if($disabledB == 1) { ?> disabled <?php } ?> /></td>
	                                        <td><?php echo $QRC_CODEV; ?></td>
	                                        <td><?php echo $ITM_NAME; ?></td>
	                                        <td nowrap style="text-align:right;"><?php echo number_format($ITM_QTY, $decFormat); ?>&nbsp;</td>
	                                        <td nowrap style="text-align:right;"><?php echo number_format($TOT_ORD, $decFormat); ?></td>
	                                        <td nowrap style="text-align:right"><?php echo number_format($REM_QTY, $decFormat); ?></td>
	                                        <td nowrap style="text-align:center">
				                                <div class="timeline-body">
				                                	<img src="<?php echo $imgQRC; ?>" class="user-image;" alt="User Image" style="width: 40px; height: 40px"/>
				                                </div>
	                                        </td>
	                                    </tr>
	                                    <?php
	                                	}
                                    endforeach;
                                }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                              <td colspan="8" nowrap>
                                <button class="btn btn-primary" type="button" onClick="get_item();">
                                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                    <button class="btn btn-danger" type="button" onClick="window.close()">
                                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
                           		</td>
                            </tr>
                            </tfoot>
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
	//alert("test1");
	if (selectedRows==NumOfRows) 
	{
		//alert("test2");
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

					window.opener.add_FG(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_FG(document.frmSearch.chk.value);
				//alert('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//alert(arrItem)
				for(z=1;z<=5;z++)
				{
					alert('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_FG(B,'child');
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