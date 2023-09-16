<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Oktober 2018
 * File Name	= v_so_selitem.php
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
    <title><?php echo $appName; ?></title>
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
		if($TranslCode == 'Stock')$Stock = $LangTransl;
		if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
		if($TranslCode == 'Ordered')$Ordered = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'Request')$Request = $LangTransl;
		if($TranslCode == 'Ordered')$Ordered = $LangTransl;
		if($TranslCode == 'Requer')$Requer = $LangTransl;
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
                                    <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
                                    <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ItemCode; ?> </th>
                                    <th width="22%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ItemName; ?> </th>
                                  	<th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Stock; ?>  </th>
                                  	<th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Ordered; ?></th>
                                  	<th width="6%" style="text-align:center; vertical-align:middle"><?php echo $Request; ?></th>
                                  	<th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Requer; ?>  </th>
                                  	<th width="6%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Unit; ?> </th>
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
                                        $OFF_NUM 		= $row->OFF_NUM;				// 0
                                        $OFF_CODE 		= $row->OFF_CODE;				// 1
                                        $PRJCODE 		= $row->PRJCODE;				// 2
                                        $ITM_CODE 		= $row->ITM_CODE;				// 3
                                        $ITM_NAME 		= $row->ITM_NAME;				// 4
                                        $ITM_UNIT 		= $row->ITM_UNIT;				// 5
                                        $OFF_VOLM 		= $row->OFF_VOLM;				// 6
                                        $OFF_PRICE 		= $row->OFF_PRICE;				// 7
                                        $OFF_COST 		= $row->OFF_COST;				// 8
                                        $OFF_DISC 		= $row->OFF_DISC;				// 9
                                        $OFF_DISCP 		= $row->OFF_DISCP;				// 10
                                        $TAXCODE1 		= $row->TAXCODE1;				// 11
                                        $TAXPRICE1 		= $row->TAXPRICE1;				// 12
                                        $OFF_TOTCOST 	= $row->OFF_TOTCOST;			// 13
                                        $ITM_VOLM 		= $row->ITM_VOLM;				// 14
                                        $itemConvertion	= 1;							// 15
                                        //$ITM_AMOUNT		= $ITM_PRICE * $ITM_VOLM;		// 16
										$disabledB		= 0;
                                        
                                        // GET RESERVE SO
                                        $TOTSO_RES	= 0;
                                        $sqlSO_RES	= "SELECT SUM(A.SO_VOLM) AS TOTSO_RES 
														FROM tbl_so_detail A
															INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
														WHERE B.PRJCODE = '$PRJCODE' AND B.SO_STAT = 2";
                                        $resSO_RES		= $this->db->query($sqlSO_RES)->result();
                                        foreach($resSO_RES as $rowSO_RES) :
                                            $TOTSO_RES	= $rowSO_RES->TOTSO_RES;
                                        endforeach;
                                        
                                        // GET PLAN : FROM PENAWARAN (APPROVED)
                                        $TOT_PLAN	= 0;
                                        $sql_PLAN	= "SELECT SUM(A.OFF_VOLM) AS TOT_PLAN 
														FROM tbl_offering_d A
														WHERE PRJCODE = '$PRJCODE' AND OFF_STAT = 3";
                                        $res_PLAN		= $this->db->query($sql_PLAN)->result();
                                        foreach($res_PLAN as $row_PLAN) :
                                            $TOT_PLAN	= $row_PLAN->TOT_PLAN;
                                        endforeach;
										
										$ITM_NEEDED	= $ITM_VOLM - $TOTSO_RES - $TOT_PLAN;
										if($ITM_NEEDED < 0)
											$ITM_NEEDED = $ITM_NEEDED;
										else
											$ITM_NEEDED = 0;
                                        
                                        $totRow		= $totRow + 1;
                                    
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                        ?> 
                                        <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $OFF_NUM;?>|<?php echo $OFF_CODE;?>|<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>|<?php echo $OFF_VOLM;?>|<?php echo $OFF_PRICE;?>|<?php echo $OFF_COST;?>|<?php echo $OFF_DISC;?>|<?php echo $OFF_DISCP;?>|<?php echo $TAXCODE1;?>|<?php echo $TAXPRICE1;?>|<?php echo $OFF_TOTCOST;?>|<?php echo $ITM_VOLM;?>" onClick="pickThis(this);" <?php if($disabledB == 1) { ?> disabled <?php } ?> /></td>
                                        <td><?php echo $ITM_CODE; ?></td>
                                        <td><?php echo $ITM_NAME; ?></td>
                                        <td nowrap style="text-align:right"><?php echo number_format($ITM_VOLM, $decFormat); ?>&nbsp;</td>
                                        <td nowrap style="text-align:right"><?php echo number_format($TOTSO_RES, $decFormat); ?></td>
                                        <td nowrap style="text-align:right"><?php echo number_format($TOT_PLAN, $decFormat); ?></td>
                                        <td nowrap style="text-align:right"><?php echo number_format($ITM_NEEDED, $decFormat); ?>&nbsp;</td>
                                        <td nowrap style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                                    </tr>
                                    <?php
                                    endforeach;
                                }
                            ?>
                            </tbody>
                            <tr>
                              <td colspan="8" nowrap>
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