<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2017
 * File Name	= item_list_selectitem.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

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
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata['vers'];

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk1  = $rowcss->cssjs_lnk;
                ?>
                    <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
                <?php
            endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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

    <style type="text/css">
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
        
        a[disabled="disabled"] {
            pointer-events: none;
        }
    </style>
    
    <body class="<?php echo $appBody; ?>">
			<section class="content">
				<div class="row">
			    	 <div class="col-md-12">
					    <div class="callout callout-success">
					        Please select items below.
					    </div>
						<div class="search-table-outter">
					        <form method="post" name="frmSearch" action="">
					              <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					                <thead>
					                    <tr>
					                        <th width="2%" rowspan="2"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
					                        <th width="11%" rowspan="2" style="vertical-align:middle; text-align:center" nowrap><?php echo $ItemCode ?> </th>
					                   	 	 <th width="57%" rowspan="2" style="vertical-align:middle; text-align:center"><?php echo $ItemName ?> </th>
					                   	  	<th colspan="5" nowrap style="text-align:center"><?php echo $ItemQty ?> </th>
					                      	<th width="2%" rowspan="2" nowrap><?php echo $Unit ?> </th>
					                  </tr>
					                    <tr>
					                      <th width="6%" nowrap><?php echo $BudgetQty; ?>  </th>
					                      <th width="5%" nowrap><?php echo $ReceiptQty; ?></th>
					                      <th width="6%" nowrap><?php echo $Used; ?></th>
					                      <th width="6%" nowrap><?php echo $Ordered; ?></th>
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
					                            $JOBCODEDET 	= $row->JOBCODEDET;				// 0
												$JOBCODEID 		= $row->JOBCODEID;				// 1
												$JOBCODE 		= $row->JOBCODE;				// 2
												$JOBPARENT 		= $row->JOBPARENT;				// 2
												$PRJCODE 		= $row->PRJCODE;				// 3
												$ITM_CODE 		= $row->ITM_CODE;				// 4
												$ACC_ID 		= $row->ACC_ID;
												$ITM_NAME 		= $row->ITM_NAME;				// 5
												$serialNumber	= '';							// 6
												$ITM_UNIT 		= $row->ITM_UNIT;				// 7
												$ITM_PRICE 		= $row->ITM_PRICE;				// 8
												$ITM_PRICE 		= $row->ITM_LASTP;				// 8	// FROM LAST PRICE
												$ITM_PRICE		= $row->ITM_AVGP;				// 		// RATA - RATA
												$ITM_VOLM 		= $row->ITM_VOLM;				// 9	// VOLUME BUDGET
												$ITM_STOCK 		= $row->ITM_STOCK;				// 10
												$ITM_USED 		= $row->ITM_USED;				// 11
												$itemConvertion	= 1;							// 12
												$ITM_AMOUNT		= $ITM_PRICE * $ITM_VOLM;		// 13
												$tempTotMax		= $ITM_VOLM - $ITM_USED;		// 14
												$REQ_VOLM 		= $row->REQ_VOLM;
												$REQ_AMOUNT		= $row->REQ_AMOUNT;
												$PO_AMOUNT		= $row->PO_AMOUNT;
												$PO_VOLM 		= $row->PO_VOLM;
												$IR_VOLM 		= $row->IR_VOLM;
												$IR_AMOUNT		= $row->IR_AMOUNT;
												$ITM_BUDG		= $ITM_VOLM;
												if($ITM_BUDG == '')
													$ITM_BUDG	= 0;
												$ITM_IN			= $row->ITM_IN;
												$ITM_OUT		= $row->ITM_OUT;
												$PO_VOLM		= $row->PO_VOLM;
												$PO_AMOUNT		= $row->PO_AMOUNT;				// 15
					                            $ITM_BUDG		= $ITM_AMOUNT;					// 16
												$TOT_USEDQTY	= $REQ_VOLM;					// 17
												if($TOT_USEDQTY == '')
													$TOT_USEDQTY = 0;
												
												// GET JOB DETAIL
												$JOBDESC		= '';
												$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist 
																	WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
												$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
												foreach($resJOBDESC as $rowJOBDESC) :
													$JOBDESC	= $rowJOBDESC->JOBDESC;
												endforeach;
												$REMREQ_QTY		= $ITM_VOLM - $REQ_VOLM;
												$REMREQ_AMN		= ($ITM_VOLM * $ITM_PRICE) - ($REQ_AMOUNT);
												//echo "REMREQ_QTY = $REMREQ_QTY, REMREQ_AMN = $REMREQ_AMN<br>";
												/*$disabledB		= 0;
												if($REMREQ_QTY <= 0)
													$disabledB	= 1;
												if($REMREQ_AMN <= $PO_AMOUNT)
													$disabledB	= 1;*/
												
												// UNTUK STOCK DIAMBIL DARI TBL_ITEM
													$ITMSTOCK		= 0;
													$sqlITMSTOCK	= "SELECT ITM_VOLM AS ITMSTOCK, ACC_ID FROM tbl_item
																		WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
													$resITMSTOCK		= $this->db->query($sqlITMSTOCK)->result();
													foreach($resITMSTOCK as $rowITMSTOCK) :
														$ITMSTOCK	= $rowITMSTOCK->ITMSTOCK;
														$ACC_ID		= $rowITMSTOCK->ACC_ID;
													endforeach;
													$ITM_STOCK		= $ITMSTOCK;
												
												// GET RESERVE ITEM
													$ITMRSV		= 0;
													$sqlITMRSV	= "SELECT SUM(A.ITM_QTY) AS ITMRESERVE FROM tbl_um_detail A
																	INNER JOIN tbl_um_header B ON A.UM_NUM = B.UM_NUM
																	WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE' AND B.UM_STAT IN (2,7)";
													$resITMRSV	= $this->db->query($sqlITMRSV)->result();
													foreach($resITMRSV as $rowITMRSV) :
														$ITMRSV	= $rowITMRSV->ITMRESERVE;
														if($ITMRSV == '')
															$ITMRSV	= 0;
													endforeach;
													//$ITM_STOCK	= $ITMSTOCK - $ITMRSV;
													$ITM_OUT1	= $ITM_OUT;
													//$ITM_STOCK	= $ITM_IN - $ITM_OUT1 - $ITMRSV;
													// MS.2019100100004 : persediaan minus
													$ITM_STOCK	= $ITM_IN - $ITM_OUT1;
													$ITM_READY	= $ITM_IN - $ITM_OUT1 - $ITMRSV;
												
					                            $totRow		= $totRow + 1;
												
												if ($j==1) {
													echo "<tr class=zebra1>";
													$j++;
												} else {
													echo "<tr class=zebra2>";
													$j--;
												}
												?> 
					                            <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $JOBCODEDET;?>|<?php echo $JOBCODEID;?>|<?php echo $JOBCODE;?>|<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_PRICE;?>|<?php echo $ITM_VOLM;?>|<?php echo $ITM_STOCK;?>|<?php echo $ACC_ID;?>" onClick="pickThis(this);" <?php if($ITM_READY <= 0) { ?> disabled <?php } ?> /></td>
					                            <td nowrap><?php echo $ITM_CODE; ?></td>
					                            <td nowrap><?php echo "$ITM_NAME - $JOBDESC"; ?></td>
					                            <td nowrap style="text-align:right"><?php echo number_format($ITM_VOLM, $decFormat); ?>&nbsp;</td>
					                            <td nowrap style="text-align:right"><?php echo number_format($ITM_IN, $decFormat); ?></td>
					                            <td nowrap style="text-align:right"><?php echo number_format($ITM_OUT1, $decFormat); ?></td>
					                            <td nowrap style="text-align:right"><?php echo number_format($ITMRSV, $decFormat); ?></td>
					                            <td nowrap style="text-align:right"><?php echo number_format($ITM_STOCK, $decFormat); ?>&nbsp;</td>
					                            <td nowrap style="text-align:center"><?php echo $ITM_UNIT; ?></td>
					                        </tr>
					                        <?php
					                        endforeach;
					                    }
					                ?>
					                </tbody>
					                <tfoot>
					                <tr>
					                  	<td colspan="10" nowrap>
					                   	<!-- <input type="button" name="btnSubmit" id="btnSubmit" class="btn btn-primary" value=" Select" onClick="get_item()" />&nbsp;
					                    <input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value=" Close " onClick="window.close()" />  -->               
					                 	
					                    <button class="btn btn-primary" type="button" onClick="get_item();">
					                        <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
					                    
					                    <button class="btn btn-danger" type="button" onClick="window.close()">
					                        <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
					                </tr>
					                </tfoot>
					            </table>
					      </form>
					    </div>
					    <!-- /.box-body -->
					</div>
				</div>
	            <?php
	                $DefID      = $this->session->userdata['Emp_ID'];
	                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	                if($DefID == 'D15040004221')
	                    echo "<font size='1'><i>$act_lnk</i></font>";
	            ?>
			</section>
		</div>
	</body>
</html>

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
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

    // Right side column. contains the Control Panel
    //$this->load->view('template/aside');

    //$this->load->view('template/js_data');

    //$this->load->view('template/foot');
?>