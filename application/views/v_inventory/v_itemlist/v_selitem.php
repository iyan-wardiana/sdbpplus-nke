<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Juli 2018
 * File Name	= v_selitem.php
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
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'Requested')$Requested = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
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
                        <th width="5%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                        <th width="10%" nowrap><?php echo $ItemCode; ?></th>
                        <th width="50%" style="text-align: center;" nowrap><?php echo $ItemName; ?> </th>
                        <th width="10%" nowrap><?php echo $BudgetQty; ?>  </th>
                        <th width="10%" nowrap><?php echo $Requested; ?></th>
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
							$JOBCODEDET 	= $row->JOBCODEDET;				// 0
							$JOBCODEID 		= $row->JOBCODEID;				// 1
							$JOBCODE 		= $row->JOBCODE;				// 2
							$PRJCODE 		= $row->PRJCODE;				// 3
							$ITM_CODE 		= $row->ITM_CODE;				// 4
							if($ITM_CODE == '')
								$ITM_CODE	= $JOBCODEID;
							$ITM_NAME 		= $row->ITM_NAME;				// 5
							$serialNumber	= '';							// 6
							$ITM_UNIT 		= $row->ITM_UNIT;				// 7
							$ITM_UNITV 		= strtolower($ITM_UNIT);
							$ITM_PRICE 		= $row->ITM_PRICE;				// 8
							$ITM_VOLM 		= $row->ITM_VOLM;				// 9	// VOLUME BUDGET
							$ADD_VOLM 		= $row->ADD_VOLM;
							$ADD_PRICE 		= $row->ADD_PRICE;
							$ITM_STOCK 		= $row->ITM_STOCK;				// 10
							$ITM_USED 		= $row->ITM_USED;				// 11
							$itemConvertion	= 1;							// 12
							//$ITM_AMOUNT	= $ITM_PRICE * $ITM_VOLM;		// 13
							$ITM_AMOUNT		= ($ITM_PRICE * $ITM_VOLM) + ($ADD_VOLM * $ADD_PRICE);		// 13 
							$tempTotMax		= $ITM_VOLM + $ADD_VOLM - $ITM_USED;						// 14
							$REQ_VOLM 		= $row->REQ_VOLM;
							$REQ_AMOUNT		= $row->REQ_AMOUNT;
							$PO_AMOUNT		= $row->PO_AMOUNT;
							$PO_VOLM 		= $row->PO_VOLM;
							$IR_VOLM 		= $row->IR_VOLM;
							$IR_AMOUNT		= $row->IR_AMOUNT;			
							$BUDG_VOLM		= $ITM_VOLM + $ADD_VOLM;			
							$BUDG_VOLMAM	= ($ITM_VOLM * $ITM_PRICE) + ($ADD_VOLM * $ADD_PRICE);
							if($ITM_UNITV == 'ls')
							{
								$BUDG_VOLM	= $BUDG_VOLMAM;
							}
							
							if($BUDG_VOLM == '')
								$BUDG_VOLM	= 0;
							$PO_VOLM		= $row->PO_VOLM;
							$PO_AMOUNT		= $row->PO_AMOUNT;				// 15
                            $ITM_BUDG		= $ITM_AMOUNT;					// 16
							$TOT_USEDQTY	= $REQ_VOLM;					// 17
							if($TOT_USEDQTY == '')
								$TOT_USEDQTY = 0;
							
							// GET JOB DETAIL
							$JOBDESC		= '';
							/*$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
							$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
							foreach($resJOBDESC as $rowJOBDESC) :
								$JOBDESC	= $rowJOBDESC->JOBDESC;
							endforeach;*/
							$sqlJOBDESC		= "SELECT A.JOBDESC FROM tbl_joblist_detail A
												WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
														WHERE B.JOBCODEID = '$JOBCODEID' AND B.PRJCODE = '$PRJCODE')
													AND A.PRJCODE = '$PRJCODE'";
							$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
							foreach($resJOBDESC as $rowJOBDESC) :
								$JOBDESC	= $rowJOBDESC->JOBDESC;
							endforeach;
							$MAX_PRAMOUNT	= $ITM_AMOUNT;
							$REMREQ_QTY		= $BUDG_VOLM - $REQ_VOLM;
							//$REMREQ_AMN	= ($ITM_VOLM * $ITM_PRICE) - ($REQ_AMOUNT);
							$REMREQ_AMN		= ($ITM_AMOUNT) - ($REQ_AMOUNT);
							
							//$TOT_ESTPR		= ($REMREQ_QTY * $ITM_PRICE) + $REMREQ_AMN;
							
							$disabledB		= 0;
							if($REMREQ_QTY <= 0)
								$disabledB	= 1;
							//if($REMREQ_AMN <= $PO_AMOUNT)
							//if($MAX_PRAMOUNT <= $TOT_ESTPR)
								//$disabledB	= 1;
							
							if($ITM_UNITV == 'ls')
							{
								if($REMREQ_AMN <= 0)
									$disabledB	= 1;
								else
									$disabledB	= 0;
							}
							
                            $totRow		= $totRow + 1;
						
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                            <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $JOBCODEDET;?>|<?php echo $JOBCODEID;?>|<?php echo $JOBCODE;?>|<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $serialNumber;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_PRICE;?>|<?php echo $BUDG_VOLM;?>|<?php echo $ITM_STOCK;?>|<?php echo $ITM_USED;?>|<?php echo $itemConvertion;?>|<?php echo $ITM_AMOUNT;?>|<?php echo $tempTotMax;?>|<?php echo $PO_AMOUNT;?>|<?php echo $ITM_BUDG;?>|<?php echo $TOT_USEDQTY;?>" onClick="pickThis(this);" <?php if($disabledB == 1) { ?> disabled <?php } ?>/></td>
                            <td nowrap><?php echo "$ITM_CODE"; ?>
						  		<div style="font-style: italic;">
							  		<p class="text-muted"><?php echo $JOBCODEID; ?></p>
							  	</div>
                            </td>
                            <td nowrap>
                            	<?php echo cut_text($ITM_NAME, 50); ?>
						  		<div style="font-style: italic;">
							  		<p class="text-muted"><?php echo $JOBDESC; ?></p>
							  	</div>
                            </td>
                            <td nowrap style="text-align:right;<?php if($disabledB == 1) { ?> background:#CC9900 <?php } ?>"><?php echo number_format($BUDG_VOLM, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align:right"><?php echo number_format($REQ_VOLM, $decFormat); ?></td>
                            <td nowrap style="text-align:right"><?php echo number_format($ITM_USED, $decFormat); ?>&nbsp;</td>
                            <td nowrap style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
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
                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
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
				//swal(document.frmSearch.chk.value)
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