<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Oktober 2018
 * File Name	= v_so_selitem.php
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
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

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
			if($TranslCode == 'ReqPlan')$ReqPlan = $LangTransl;
			if($TranslCode == 'Reserved')$Reserved = $LangTransl;
			if($TranslCode == 'Requer')$Requer = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
		endforeach;
	?>
	
	<body class="<?php echo $appBody; ?>">
		<div class="content-wrapper">
			<style>
				.search-table, td, th {
					border-collapse: collapse;
				}
				.search-table-outter { overflow-x: scroll; }
			</style>

            <section class="content">
			    <div class="box">
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
			                                    <th width="7%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
			                                    <th width="22%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
			                                  	<th width="8%" style="text-align:center" nowrap><?php echo $Stock; ?>  </th>
			                                  	<th width="5%" style="text-align:center" nowrap><?php echo $Reserved; ?></th>
			                                  	<th width="6%" style="text-align:center"><?php echo $ReqPlan; ?></th>
			                                  	<th width="9%" style="text-align:center" nowrap><?php echo $Requer; ?>  </th>
			                                  	<th width="6%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
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
			                                        $PRJCODE 		= $row->PRJCODE;				// 3
			                                        $ITM_CODE 		= $row->ITM_CODE;				// 4
			                                        $ITM_NAME 		= $row->ITM_NAME;				// 5
			                                        $serialNumber	= '';							// 6
			                                        $ITM_UNIT 		= $row->ITM_UNIT;				// 7
			                                        $ITM_PRICE 		= $row->ITM_PRICE;				// 8
			                                        $ITM_VOLM 		= $row->ITM_VOLM;				// 9
			                                        $ITM_STOCK 		= $row->ITM_VOLM;				// 10
			                                        $ITM_USED 		= $row->ITM_OUT;				// 11
			                                        $itemConvertion	= 1;							// 12
			                                        $ITM_AMOUNT		= $ITM_PRICE * $ITM_VOLM;		// 13
													$PR_VOLM 		= $row->PR_VOLM;
													$PR_AMOUNT		= $row->PR_AMOUNT;
													$PO_VOLM 		= $row->PO_VOLM;
													$PO_AMOUNT		= $row->PO_AMOUNT;
													$IR_VOLM 		= $row->IR_VOLM;
													$IR_AMOUNT		= $row->IR_AMOUNT;
													$SO_VOLM 		= $row->SO_VOLM;
													$SO_AMOUNT		= $row->SO_AMOUNT;
													$PROD_VOLM 		= $row->PROD_VOLM;
													$PROD_AMOUNT	= $row->PROD_AMOUNT;                                        
			                                        $ITM_BUDG		= $row->ITM_VOLMBG;				// 15
			                                        $PO_VOLM		= $row->PO_VOLM;				// 16
			                                        $PO_AMOUNT		= $row->PO_AMOUNT;				// 17
			                                        
													$disabledB		= 0;
													if(($ITM_VOLM == $PO_VOLM) && $ITM_VOLM > 0)
														$disabledB	= 1;
													if(($ITM_AMOUNT == $PO_AMOUNT) && $ITM_AMOUNT > 0)
														$disabledB	= 1;
													
													$disabledPR		= 0;
													if(($PR_VOLM == $PO_VOLM) && $PR_VOLM > 0)
														$disabledPR	= 1;
													if($PR_AMOUNT == $PO_AMOUNT && $PR_AMOUNT > 0)
														$disabledPR	= 1;
			                                        
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
			                                        
			                                        // GET SO PLAN
			                                        $TOTSO_PLAN	= 0;
			                                        $sqlSO_PLAN	= "SELECT SUM(A.SO_VOLM) AS TOTSO_PLAN 
																	FROM tbl_so_detail A
																		INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
																	WHERE B.PRJCODE = '$PRJCODE' AND B.SO_STAT = 1";
			                                        $resSO_PLAN		= $this->db->query($sqlSO_PLAN)->result();
			                                        foreach($resSO_PLAN as $rowSO_PLAN) :
			                                            $TOTSO_PLAN	= $rowSO_PLAN->TOTSO_PLAN;
			                                        endforeach;
													
													$ITM_NEEDED	= $ITM_VOLM - $TOTSO_RES - $TOTSO_PLAN;
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
			                                        <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $serialNumber;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_PRICE;?>|<?php echo $ITM_VOLM;?>|<?php echo $ITM_STOCK;?>|<?php echo $ITM_USED;?>|<?php echo $itemConvertion;?>|<?php echo $ITM_AMOUNT;?>|<?php echo $ITM_BUDG;?>" onClick="pickThis(this);" <?php if($disabledB == 1) { ?> disabled <?php } ?> /></td>
			                                        <td><?php echo $ITM_CODE; ?></td>
			                                        <td><?php echo $ITM_NAME; ?></td>
			                                        <td nowrap style="text-align:right;<?php if($disabledB == 1) { ?> background:#CC9900 <?php } ?>"><?php echo number_format($ITM_VOLM, $decFormat); ?>&nbsp;</td>
			                                        <td nowrap style="text-align:right;<?php if($disabledPR == 1) { ?> background:#CC9900 <?php } ?>"><?php echo number_format($TOTSO_RES, $decFormat); ?></td>
			                                        <td nowrap style="text-align:right"><?php echo number_format($TOTSO_PLAN, $decFormat); ?></td>
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