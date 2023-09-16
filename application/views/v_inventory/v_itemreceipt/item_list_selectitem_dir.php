<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Februari 2019
 * File Name	= item_transfer_selmr.php
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
		if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Substitute')$Substitute = $LangTransl;
		if($TranslCode == 'Others')$Others = $LangTransl;
		if($TranslCode == 'ItemList')$ItemList = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Request')$Request = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Received')$Received = $LangTransl;
		if($TranslCode == 'Remain')$Remain = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$alert1		= "Sisa yang harus dikirim sudah kosong";
		$alert2		= "Stock kosong";
	}
	else
	{
		$alert1		= "Qty must be sent is empty";
		$alert2		= "Empty stock";
	}
	$ISCREATE	= 1;
	
	if(isset($_POST['submit1']))
	{
		$List_Type 		= $this->input->post('List_Type');
		if($List_Type == 1)
		{
			$Active1		= "active";
			$Active2		= "";
			$Active3		= "";
			$Active1Cls		= "class='active'";
			$Active2Cls		= "";
			$Active3Cls		= "";
		}
		elseif($List_Type == 2)
		{
			$Active1		= "";
			$Active2		= "active";
			$Active3		= "";
			$Active1Cls		= "";
			$Active2Cls		= "class='active'";
			$Active3Cls		= "";
		}
		else
		{
			$Active1		= "";
			$Active2		= "";
			$Active3		= "active";
			$Active1Cls		= "";
			$Active2Cls		= "";
			$Active3Cls		= "class='active'";
		}
	}
	else
	{
		$List_Type		= 1;
		$Active1		= "active";
		$Active2		= "";
		$Active3		= "";
		$Active1Cls		= "class='active'";
		$Active2Cls		= "";
		$Active3Cls		= "";
	}
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

<form class="form-horizontal" name="frm_01" method="post" action="" style="display:none">
	<input type="text" name="List_Type" id="List_Type" value="<?php echo $List_Type; ?>" />
    <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
</form>
<section class="content">
	<div class="row">
    	 <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li <?php echo $Active1Cls; ?>><a href="#primaryitem" data-toggle="tab" onClick="setType(1)"><?php echo $ItemList; ?></a></li>		<!-- Tab 1 -->
                    <li <?php echo $Active2Cls; ?>><a href="#substitute" data-toggle="tab" onClick="setType(2)"><?php echo $Substitute; ?></a></li> 		<!-- Tab 2 -->
                    <li <?php echo $Active3Cls; ?>><a href="#others" data-toggle="tab" onClick="setType(3)"><?php echo $Others; ?></a></li> 		<!-- Tab 3 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                	<?php if($List_Type == 1) { ?>
                        <div class="<?php echo $Active1; ?> tab-pane" id="primaryitem">
                          	<div class="box box-success">
                                <div>
                                    &nbsp;
                                </div>
                                <div class="form-group">
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
                            </div>
                        </div>
                    <?php } else if($List_Type == 2) { ?>
                        <div class="<?php echo $Active2; ?> tab-pane" id="substitute">
                          	<div class="box box-success">
                                <div>
                                    &nbsp;
                                </div>
                                <div class="form-group">
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
                                                if($countAllItemS>0)
                                                {
                                                    $totRow	= 0;
                                                    foreach($vwAllItemS as $row) :
                                                        $PRJCODE 		= $row->PRJCODE;
                                                        $ITM_CODE 		= $row->ITM_CODE;				// 0
                                                        $serialNumber	= '';							// 1
                                                        $ITM_NAME 		= $row->ITM_NAME;				// 2
                                                        $JOB_DESC 		= $row->JOBDESC;
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
                                                        <td nowrap><?php echo "$ITM_NAME - $JOB_DESC"; ?></td>
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
                            </div>
                        </div>
                    <?php } else {?>
                        <div class="<?php echo $Active3; ?> tab-pane" id="others">
                          	<div class="box box-success">
                                <div>
                                    &nbsp;
                                </div>
                                <div class="form-group">
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
                                                if($countAllItemO>0)
                                                {
                                                    $totRow	= 0;
                                                    foreach($vwAllItemO as $row) :
                                                        $PRJCODE 		= $row->PRJCODE;
                                                        $ITM_CODE 		= $row->ITM_CODE;				// 0
                                                        $serialNumber	= '';							// 1
                                                        $ITM_NAME 		= $row->ITM_NAME;				// 2
                                                        $JOB_DESC 		= $row->JOBDESC;
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
                                                        <td nowrap><?php echo "$ITM_NAME - $JOB_DESC"; ?></td>
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
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
		 </div>
	</div>
</section>
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
	  
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
  
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
	
	function setType(thisValue)
	{
		if(thisValue == 1)
		{
			document.getElementById('List_Type').value = thisValue;
		}
		else
		{
			document.getElementById('List_Type').value = thisValue;
		}
		document.frm_01.submit1.click();
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>