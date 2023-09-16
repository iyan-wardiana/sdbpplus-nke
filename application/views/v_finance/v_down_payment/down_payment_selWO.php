<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 03 Januari 2019
 * File Name	= down_payment_selWO.php
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
	endforeach;
	
	if(isset($_POST['submit1']))
	{
		$List_Type 		= $this->input->post('List_Type');
		if($List_Type == 1)
		{
			$Active1		= "active";
			$Active2		= "";
			$Active1Cls		= "class='active'";
			$Active2Cls		= "";
		}
		else
		{
			$Active1		= "";
			$Active2		= "active";
			$Active1Cls		= "";
			$Active2Cls		= "class='active'";
		}
	}
	else
	{
		$List_Type		= 1;
		$Active1		= "active";
		$Active2		= "";
		$Active1Cls		= "class='active'";
		$Active2Cls		= "";
	}
	
	if($LangID == 'IND')
	{
		$alert1	= "Silahkan pilih Kode SPK atau Kode PO berikut.";
	}
	else
	{
		$alert1	= "Please select a WO Code or PO Code below.";
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
    <div class="callout callout-success">
        <?php echo $alert1; ?>
    </div>
	<div class="row">
    	 <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li <?php echo $Active1Cls; ?>><a href="#spklist" data-toggle="tab" onClick="setType(1)">SPK</a></li>		<!-- Tab 2 -->
                    <li <?php echo $Active2Cls; ?>><a href="#polist" data-toggle="tab" onClick="setType(2)">PO</a></li> 		<!-- Tab 1 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                	<form method="post" name="frmSearch" action="">
                	<?php
						if($List_Type == 1)
						{
							?>
                                <div class="<?php echo $Active1; ?> tab-pane" id="spklist">
                                  	<div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                                        <th width="7%" nowrap><?php echo $Code; ?></th>
                                                        <th width="8%" nowrap><?php echo $Date; ?> </th>
                                                        <th width="25%" nowrap><?php echo $JobName; ?></th>
                                                        <th width="30%" nowrap><?php echo $Description; ?></th>
                                                        <th width="6%" nowrap><?php echo $StartDate; ?></th>
                                                        <th width="9%" nowrap><?php echo $EndDate; ?>  </th>
                                                        <th width="9%" nowrap><?php echo $Amount; ?>  </th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php
													$i = 0;
													$j = 0;
													if($countWO>0)
													{
														$totRow	= 0;
														foreach($vwWO as $row) :
															$WO_NUM 		= $row->WO_NUM;	
															$WO_CODE 		= $row->WO_CODE;
															$WO_DATE 		= $row->WO_DATE;
															$WO_STARTD 		= $row->WO_STARTD;
															$WO_ENDD 		= $row->WO_ENDD;
															$PRJCODE 		= $row->PRJCODE;
															$JOBCODEID 		= $row->JOBCODEID;
															$WO_NOTE 		= $row->WO_NOTE;
															$WO_VALUE 		= $row->WO_VALUE;
															$WO_VALPPN 		= $row->WO_VALPPN;
															$REF_TYPE		= 'WO';
															
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
															<td style="text-align:center"><input type="radio" name="chk" value="<?php echo $WO_NUM;?>|<?php echo $WO_CODE;?>|<?php echo $REF_TYPE;?>" onClick="pickThis(this);"/></td>
															<td><?php echo $WO_CODE; ?></td>
															<td style="text-align:center" nowrap><?php echo $WO_DATE; ?></td>
															<td nowrap><?php echo cut_text($JOBDESC, 50); ?></td>
															<td style="text-align:left"><?php echo $WO_NOTE; ?></td>
															<td nowrap style="text-align:center"><?php echo $WO_STARTD; ?></td>
															<td nowrap style="text-align:center"><?php echo $WO_ENDD; ?></td>
															<td nowrap style="text-align:center"><?php echo number_format($WO_VALUE, $decFormat); ?></td>
														</tr>
														<?php
														endforeach;
													}
												?>
                                                </tbody>
                                                <tr>
                                                  	<td colspan="8" nowrap>
                                                        <input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                        <button class="btn btn-primary" type="button" onClick="get_item();">
                                                        <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?></button>
                                                        <button class="btn btn-danger" type="button" onClick="window.close()">
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?></button>
                                                   	</td>
                                                </tr>
                                            </table>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
						elseif($List_Type == 2)
						{
							?>
                                <div class="<?php echo $Active2; ?> tab-pane" id="polist">
                                    <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                                        <th width="8%" nowrap><?php echo $Code; ?></th>
                                                        <th width="8%" nowrap><?php echo $Date; ?> </th>
                                                        <th width="12%" nowrap><?php echo $SupplierName; ?></th>
                                                        <th width="31%" nowrap><?php echo $JobName; ?></th>
                                                        <th width="39%" nowrap><?php echo $Description; ?></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php
													$i = 0;
													$j = 0;
													if($countPO>0)
													{
														$totRow	= 0;
														foreach($vwPO as $row) :
															$PO_NUM 		= $row->PO_NUM;	
															$PO_CODE 		= $row->PO_CODE;
															$PO_DATE 		= $row->PO_DATE;
															$PO_DUED 		= $row->PO_DUED;
															$PRJCODE 		= $row->PRJCODE;
															$JOBCODEID1		= $row->JOBCODE;
															$PO_NOTES 		= $row->PO_NOTES;
															$PO_NOTES1 		= $row->PO_NOTES1;
															$SPLDESC 		= $row->SPLDESC;
															$REF_TYPE		= 'PO';
															$explJOB		= explode("~", $JOBCODEID1);
															$JOBCODEID		= $explJOB[0];
															
															// GET JOB DETAIL
															$JOBDESC		= '';
															$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist 
																				WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
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
															<td style="text-align:center"><input type="radio" name="chk" value="<?php echo $PO_NUM;?>|<?php echo $PO_CODE;?>|<?php echo $REF_TYPE;?>" onClick="pickThis(this);"/></td>
															<td nowrap><?php echo $PO_CODE; ?></td>
															<td style="text-align:center" nowrap><?php echo $PO_DATE; ?></td>
															<td nowrap><?php echo cut_text($SPLDESC, 50); ?></td>
															<td ><?php echo cut_text($JOBDESC, 50); ?></td>
															<td ><?php echo $PO_NOTES; ?></td>
														</tr>
														<?php
														endforeach;
													}
												?>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                  	<td colspan="6" nowrap>
                                                        <input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                        <button class="btn btn-primary" type="button" onClick="get_item();">
                                                        <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?></button>
                                                        <button class="btn btn-danger" type="button" onClick="window.close()">
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?></button>
                                                   	</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
					?>
                    </form>
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

					window.opener.add_WO(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_WO(document.frmSearch.chk.value);
				//alert('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//alert(arrItem)
				for(z=1;z<=5;z++)
				{
					alert('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_WO(B,'child');
					alert(B)
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