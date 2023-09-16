<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 04 Juli 2018
 * File Name	= v_select_dp.php
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

		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'DPCode')$DPCode = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'PPn')$PPn = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$header_1	= "Silahkan pilih nomor Down Payment di bawah ini.";
		}
		else
		{
			$header_1	= "Please select Down Payment Number below.";
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

	<div class="box">
	    <!-- /.box-header -->
		<div class="box-body">
		    <div class="callout callout-success">
		        <?php echo $header_1; ?>
		    </div>
			<div class="search-table-outter">
		        <form method="post" name="frmSearch" action="">
		              <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                <thead>
		                    <tr>
		                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
		                        <th width="17%"><?php echo $DPCode; ?> </th>
		                        <th width="43%"><?php echo $Description; ?> </th>
		                        <th width="5%" style="text-align:center" nowrap><?php echo $DueDate; ?>  </th>
		                        <th width="13%" style="text-align:center" nowrap><?php echo $Amount; ?></th>
		                        <th width="8%" style="text-align:center" nowrap><?php echo $PPn; ?></th>
		                        <th width="11%" style="text-align:center" nowrap>Total</th>
		                    </tr>
		                </thead>
		                <tbody>
		                <?php
		                    $i = 0;
		                    $j = 0;
		                    //if($countINV>0)
		                    //{
		                        $totRow	= 0;
		                        foreach($vwINV as $row) :
		                            $INV_NUM 		= $row->INV_NUM;			// 0
		                            $INV_CODE 		= $row->INV_CODE;			// 1
		                            $INV_DUEDATE	= $row->INV_DUEDATE;
		                            $PO_NUM 		= $row->PO_NUM;
		                            $PRJCODE 		= $row->PRJCODE;			// 2
		                            $INV_AMOUNT 	= $row->ITM_AMOUNT;			// 3
									//$PPnAmount		= 0.1 * $INV_AMOUNT;	// 4
		                            $PPnAmount 		= $row->TAX_AMOUNT_PPn1;	// 4
		                            $IR_NUM			= $row->IR_NUM;				// 5
		                            $INV_NOTES	 	= $row->INV_NOTES;			// 6
		                            $SPLCODE	 	= $row->SPLCODE;
		                            $INV_CATEG	 	= $row->INV_CATEG;
		                            $INV_TOTAL	 	= $INV_AMOUNT + $PPnAmount;
									if($INV_NOTES == '')
									{
										$SPLDESC		= '-';
										$sqlSUPL		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
										$resSUPL		= $this->db->query($sqlSUPL)->result();
										foreach($resSUPL as $rowSUPL) :
											$SPLDESC	= $rowSUPL->SPLDESC;
										endforeach;
										$INV_NOTES		= $SPLDESC;
									}
									
									// GET OTHERS PAYMENT
									$Amount			= 0;
									$Amount_PPn		= 0;
									$sqlPAY			= "SELECT A.Amount, A.Amount_PPn FROM tbl_bp_detail A
														INNER JOIN tbl_bp_header B ON A.CB_NUM = B.CB_NUM
														WHERE A.DocumentNo = '$INV_NUM' AND B.CB_STAT = 3";
									$resPAY			= $this->db->query($sqlPAY)->result();
									foreach($resPAY as $rowPAY) :
										$Amount		= $rowPAY->Amount;
										$Amount_PPn	= $rowPAY->Amount_PPn;
									endforeach;
									$INV_REM		= $INV_AMOUNT - $Amount;
									$INV_PPNREM		= $PPnAmount - $Amount_PPn;
									$INV_TOTALREM 	= $INV_REM + $INV_PPNREM;
									
									$INV_AMOUNT_RET	= 0;
									$INV_AMOUNT_PPN	= 0;
									$INV_PPHVAL		= 0;
									
		                            $totRow		= $totRow + 1;
								
									if ($j==1) {
										echo "<tr class=zebra1>";
										$j++;
									} else {
										echo "<tr class=zebra2>";
										$j--;
									}
									?>
		                            <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $INV_NUM;?>|<?php echo $INV_CODE;?>|<?php echo $PRJCODE;?>|<?php echo $INV_AMOUNT;?>|<?php echo $PPnAmount;?>|<?php echo $IR_NUM;?>|<?php echo $INV_NOTES;?>|<?php echo $INV_REM;?>|<?php echo $INV_PPNREM;?>|<?php echo $INV_CATEG;?>|<?php echo $INV_TOTALREM;?>|<?php echo $PRJCODE;?>|<?php echo $INV_AMOUNT_RET;?>|<?php echo $INV_AMOUNT_PPN;?>|<?php echo $INV_PPHVAL;?>" onClick="pickThis(this);" /></td>
		                            <td><?php echo $INV_CODE; ?></td>
		                            <td><?php echo $INV_NOTES; ?></td>
		                            <td nowrap style="text-align:center"><?php echo $INV_DUEDATE; ?>&nbsp;</td>
		                            <td nowrap style="text-align:right"><?php echo number_format($INV_REM, $decFormat); ?>&nbsp;</td>
		                            <td nowrap style="text-align:right"><?php echo number_format($INV_PPNREM, $decFormat); ?>&nbsp;</td>
		                            <td nowrap style="text-align:right"><?php echo number_format($INV_TOTALREM, $decFormat); ?>&nbsp;</td>
		                        </tr>
		                        <?php
		                        endforeach;
		                    //}
		                ?>
		                </tbody>
		                <tfoot>
		                <tr>
		                  	<td colspan="7" nowrap>
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