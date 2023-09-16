<?php
/* 
 	* Author		= Dian Hermanto
 	* Create Date	= 28 Oktober 2018
 	* File Name		= v_so_selitem.php
 	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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
    	$LangID 		= $this->session->userdata['LangID'];

    	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    	$resTransl		= $this->db->query($sqlTransl)->result();
    	foreach($resTransl as $rowTransl) :
    		$TranslCode	= $rowTransl->MLANG_CODE;
    		$LangTransl	= $rowTransl->LangTransl;
    		
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'CustName')$CustName = $LangTransl;
    		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
    		if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
    		if($TranslCode == 'Unit')$Unit = $LangTransl;
    		if($TranslCode == 'Select')$Select = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    		if($TranslCode == 'PleaseSelectSO')$PleaseSelectSO = $LangTransl;
    		if($TranslCode == 'SOValue')$SOValue = $LangTransl;
    		if($TranslCode == 'JobName')$JobName = $LangTransl;
    		if($TranslCode == 'Select')$Select = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    	endforeach;

    	// RESET SENT STATUS
    		$sqlSO 	= "SELECT DISTINCT A.SO_NUM FROM tbl_so_detail X
						INNER JOIN tbl_so_header A ON A.SO_NUM = X.SO_NUM
						WHERE X.SN_VOLM <= X.SO_VOLM";
			$resSO 	= $this->db->query($sqlSO)->result();
    ?>
    
    <body class="<?php echo $appBody; ?>">
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
                        <?php echo $PleaseSelectSO; ?>
                    </div>
                    <div class="search-table-outter">
                        <form method="post" name="frmSearch" action="">
                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                <thead>
                                    <tr style="vertical-align:middle">
                                        <th width="5%" height="29" style="text-align:center; vertical-align:middle" nowrap><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
                                        <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $DocNumber; ?> </th>
                                        <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date; ?> </th>
                                      	<th width="33%" style="text-align:center; vertical-align:middle" nowrap><?php echo $CustName; ?>  </th>
                                      	<th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $SOValue; ?></th>
                                      	<th width="39%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Notes; ?></th>
                                  	</tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 0;
                                    $j = 0;
                                    if($countCUST>0)
                                    {
                                        $totRow	= 0;
                                        foreach($vwCUST as $row) :
                                            $SO_NUM 		= $row->SO_NUM;				// 0
                                            $SO_CODE 		= $row->SO_CODE;			// 1
                                            $SO_DATE 		= $row->SO_DATE;			// 
    										$SO_DATEV		= date('d M Y', strtotime($SO_DATE));
                                            $SO_DUED 		= $row->SO_DUED;			// 
                                            $SO_PRODD 		= $row->SO_PRODD;			// 
                                            $CUST_CODE 		= $row->CUST_CODE;			// 2
                                            $CUST_ADDRESS	= $row->CUST_ADDRESS;		// 3
                                            $SO_TOTCOST		= $row->SO_TOTCOST;			// 
                                            $SO_TOTPPN 		= $row->SO_TOTPPN;			// 
                                            $CUST_DESC 		= $row->CUST_DESC;			// 4
                                            $SO_NOTES 		= $row->SO_NOTES;			// 
                                            
                                            $totRow		= $totRow + 1;
                                        
                                            if ($j==1) {
                                                echo "<tr class=zebra1>";
                                                $j++;
                                            } else {
                                                echo "<tr class=zebra2>";
                                                $j--;
                                            }
                                            ?> 
                                            <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $SO_NUM;?>|<?php echo $SO_CODE;?>|<?php echo $CUST_CODE;?>|<?php echo $CUST_ADDRESS;?>|<?php echo $CUST_DESC;?>" onClick="pickThis(this);" /></td>
                                            <td><?php echo $SO_CODE; ?></td>
                                            <td nowrap><?php echo $SO_DATEV; ?></td>
                                            <td nowrap style="text-align:left;"><?php echo $CUST_DESC; ?>&nbsp;</td>
                                            <td nowrap style="text-align:center; font-style: italic;">
                                                hidden
                                                <?php // echo number_format($SO_TOTCOST, $decFormat); ?>
                                            </td>
                                            <td style="text-align:left;"><?php echo $SO_NOTES; ?></td>
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
        </section>
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

					window.opener.add_header(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_header(document.frmSearch.chk.value);
				//swal('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//swal(arrItem)
				for(z=1;z<=5;z++)
				{
					swal('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_header(B,'child');
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