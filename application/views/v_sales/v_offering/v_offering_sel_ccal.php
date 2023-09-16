<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 2 Desember 2018
 * File Name	= v_offering_sel_ccal.php
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

$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;

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
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
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

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">   
    </head>

	<?php
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl	= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'calculation')$calculation = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'BOMCode')$BOMCode = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'ProductionVolume')$ProductionVolume = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Deviation')$Deviation = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1		= "Pilih salah satu kode kalkulasi biaya.";
		}
		else
		{
			$alert1		= "Select one of cost calculation code.";
		}
	?>

    <body class="<?php echo $appBody; ?>">
		<section class="content-header">
		</section>
		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
		</style>
		<!-- Main content -->

        <section class="content">
            <div class="row">
				<div class="box-body">
				    <div class="callout callout-success" style="vertical-align:top">
				        <?php echo "$PRJCODE - $PRJNAME"; ?>
				    </div>
					<div class="search-table-outter">
				        <form method="post" name="frmSearch" action="">
				            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
				                <thead>
				                    <tr>
				                        <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
				                        <th width="4%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Code; ?></th>
				                        <th width="2%" style="vertical-align:middle; text-align:center" nowrap>
											<?php
												echo $Date; 
											?>
				                        </th>
				                        <th width="2%" style="vertical-align:middle; text-align:center" nowrap>
											Resep
				                        </th>
				                        <th width="35%" nowrap style="text-align:center">
											<?php
												echo $CustName; 
											?>
				                        </th>
				                        <th width="39%" nowrap style="text-align:center">
											<?php 
												//echo $UnitPrice; 
												echo $ItemName; 
											?>
				                        </th>
				                        <th width="8%" nowrap style="text-align:center">
											<?php 
												//echo $UnitPrice; 
												//echo $ProductionVolume; 
												echo "Volume"; 
											?>
				                        </th>
				                        <th width="8%" nowrap style="text-align:center"><?php echo $Price; ?></th>
				                    </tr>
				                </thead>
				                <tbody>
				                <?php
				                    $i = 0;
				                    $j = 0;
				                    $sqlReqC	= "tbl_ccal_header A
													INNER JOIN tbl_item B ON A.BOM_FG = B.ITM_CODE
														AND B.PRJCODE = '$PRJCODE'
													WHERE A.PRJCODE = '$PRJCODE'									
														AND A.CUST_CODE = '$CUST_CODE'
														AND A.CCAL_STAT = 3";				
				                    $resReqC 	= $this->db->count_all($sqlReqC);
				                
				                    $sql 		= "SELECT A.CCAL_NUM, A.CCAL_CREATED, A.CCAL_CODE, B.ITM_CODE, B.ITM_UNIT, A.CCAL_VOLM AS ITM_QTY,
														A.CCAL_ITMPRICE AS ITM_PRICE, A.BOM_NUM, A.BOM_CODE, A.CUST_CODE, B.ITM_NAME
													FROM tbl_ccal_header A
														INNER JOIN tbl_item B ON A.BOM_FG = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
													WHERE A.PRJCODE = '$PRJCODE'									
														AND A.CUST_CODE = '$CUST_CODE'
														AND A.CCAL_STAT = 3";
				                    $viewAllMR 	= $this->db->query($sql)->result();
				                    if($resReqC > 0)
				                    {
				                        $totRow	= 0;
				                        foreach($viewAllMR as $row) :
				                            $CCAL_NUM 		= $row->CCAL_NUM;
				                            $CCAL_CREATED	= $row->CCAL_CREATED;
				                            $CCAL_CODE 		= $row->CCAL_CODE;
				                            $ITM_CODE 		= $row->ITM_CODE;
				                            $ITM_NAME 		= $row->ITM_NAME;
				                            $ITM_UNIT 		= $row->ITM_UNIT;
				                            $ITM_QTY 		= $row->ITM_QTY;
				                            $ITM_PRICE 		= $row->ITM_PRICE;
				                            $ITM_TOTAL		= $ITM_QTY * $ITM_PRICE;
				                            $CUST_CODE		= $row->CUST_CODE;
				                            $BOM_NUM		= $row->BOM_NUM;
				                            $BOM_CODE		= $row->BOM_CODE;
											
											$CUST_DESC		= '';
											$sqlCST 		= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
											$resultCST 		= $this->db->query($sqlCST)->result();
											foreach($resultCST as $rowCST) :
												$CUST_DESC 	= $rowCST->CUST_DESC;
											endforeach;
				                            
				                            $totRow			= $totRow + 1;
				                        
				                            if ($j==1) {
				                                echo "<tr class=zebra1>";
				                                $j++;
				                            } else {
				                                echo "<tr class=zebra2>";
				                                $j--;
				                            }
				                            ?>
				                            <!-- <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $CCAL_NUM;?>|<?php echo $CCAL_CODE;?>|<?php echo $BOM_NUM;?>|<?php echo $BOM_CODE;?>|<?php echo $CUST_CODE;?>" onClick="pickThis(this);" /></td> -->
				                            <td style="text-align:center"><input type="checkbox" id="chk1" name="chk1" value="<?php echo $CCAL_NUM;?>" onClick="pickThis(this);" /></td>
				                            <td nowrap>
				                                <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
				                                <?php echo $CCAL_CODE; ?></a> 
				                                <input type="hidden" name="CCAL_NUM<?php echo $totRow; ?>" id="CCAL_NUM<?php echo $totRow; ?>" value="<?php echo $CCAL_NUM; ?>" />
				                            </td>
				                            <td nowrap><?php echo date('d M Y', strtotime($CCAL_CREATED)); ?></td>
				                            <td nowrap><?php echo $BOM_CODE; ?></td>
				                            <td><?php echo $CUST_DESC; ?></td>
				                            <td nowrap><?php echo $ITM_NAME; ?></td>
				                            <td style="text-align:right" nowrap><?php echo number_format($ITM_QTY, 2); ?></td>
				                            <td style="text-align:right" nowrap>
												<span class="label label-success" style="font-size:11px">
													<?php echo number_format($ITM_TOTAL, 2); ?>
				                                </span>
				                            </td>
				                            </tr>
				                        <?php
				                        endforeach;
				                    }
				                ?>
				                </tbody>
				                <tfoot>
				                <tr>
				                    <td colspan="8" nowrap>
				                    	<input type="checkbox" id="chk" name="chk" value="" style="display: none;" />
					                    <button class="btn btn-primary" type="button" onClick="get_req();">
					                        <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
					                    </button> 
					                        <button class="btn btn-danger" type="button" onClick="window.close()">
					                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
					                    </button>
				                    </td>
				                </tr>
				                </tfoot>
						  </table>
				        </form>
				                
				    </div>
				    <!-- /.box-body -->
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
	var tCHK 		= thisobj.value;
	var nCHK 		= document.getElementById("chk").value;

	if(nCHK == '')
		var nCHK 	= tCHK;
	else
		var nCHK 	= nCHK+'~'+tCHK;

	var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
	if (thisobj!= '') 
	{
		if (thisobj.checked) selectedRows++;
		else selectedRows--;
	}
	if(selectedRows > 1)
	{
		/*swal('Please select one Request');
		return false;*/
	}

	document.getElementById("chk").value	= nCHK;

	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}

function get_req() 
	{
		var chkMW = document.querySelector('input[name = "chk1"]:checked');
		if(chkMW != null)
		{
			if(typeof(document.frmSearch.chk1[0]) == 'object') 
			{
				for(i=0;i<document.frmSearch.chk1.length;i++) 
				{
					if(document.frmSearch.chk1[i].checked) 
					{
						A = document.frmSearch.chk1[i].value
						arrItem = A.split('|');
						arrparent = document.frmSearch.chk1[i].value.split('|');
	
						window.opener.add_header(document.frmSearch.chk.value);				
					}
				}
			}
			else
			{
				if(document.frmSearch.chk1.checked)
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
						//window.opener.add_item(B,'child');
						swal(B)
					}*/
				}
			}
			window.close();		
		}
		else
		{
			swal('<?php echo $alert1; ?>');
			return false;
		}
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