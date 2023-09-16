<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 02 Oktober 2019
 * File Name	= v_bom_selitemWIP.php
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
		if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
		if($TranslCode == 'CustName')$CustName = $LangTransl;
		if($TranslCode == 'Remain')$Remain = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'ProductionVolume')$ProductionVolume = $LangTransl;
		if($TranslCode == 'Substitute')$Substitute = $LangTransl;
		if($TranslCode == 'ItemList')$ItemList = $LangTransl;
		if($TranslCode == 'WIPList')$WIPList = $LangTransl;
		if($TranslCode == 'FinGoods')$FinGoods = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
		if($TranslCode == 'QRCList')$QRCList = $LangTransl;
	endforeach;
	$ISCREATE	= 1;
	
	$WH_CODE	= '';
	$sqlWH 		= "SELECT WH_CODE FROM tbl_warehouse 
					WHERE PRJCODE = '$PRJCODE' AND ISWHPROD = 1";
	$resWH 		= $this->db->query($sqlWH)->result();
	foreach($resWH as $rowWH) :
		$WH_CODE = $rowWH->WH_CODE;
	endforeach;

	if(isset($_POST['submit1']))
	{
		$List_Type 		= $this->input->post('List_Type');
		if($List_Type == 1)
		{
			$Active1		= "active";
			$Active2		= "";
			$Active5		= "";
			$Active1Cls		= "class='active'";
			$Active2Cls		= "";
			$Active5Cls		= "";
		
			$cDataItm		= $this->db->count_all("tbl_item WHERE PRJCODE = '$PRJCODE' AND ISWIP = 1 AND STATUS = 1");
		}
		elseif($List_Type == 2)
		{
			//$sqlDataItm	= "tbl_item_collh A INNER JOIN tbl_item B ON A.ICOLL_FG = B.ITM_CODE AND B.PRJCODE = '$PRJCODE' WHERE A.ICOLL_STAT = '2'";
			$sqlDataItm		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ISFG = 1 AND STATUS = 1";
			$cDataItm		= $this->db->count_all($sqlDataItm);
			
			$Active1		= "";
			$Active2		= "active";
			$Active5		= "";
			$Active1Cls		= "";
			$Active2Cls		= "class='active'";
			$Active5Cls		= "";
		}
		else
		{
			//$sqlDataItm	= "tbl_item_collh A INNER JOIN tbl_item B ON A.ICOLL_FG = B.ITM_CODE AND B.PRJCODE = '$PRJCODE' WHERE A.ICOLL_STAT = '2'";
			$sqlDataItm		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND (ISFG = 1 OR ISWIP = 1) AND STATUS = 1";
			$cDataItm		= $this->db->count_all($sqlDataItm);
			
			$Active1		= "";
			$Active2		= "active";
			$Active5		= "";
			$Active1Cls		= "";
			$Active2Cls		= "class='active'";
			$Active5Cls		= "";
		}
	}
	else
	{
		$List_Type		= 1;
		$Active1		= "active";
		$Active2		= "";
		$Active5		= "";
		$Active1Cls		= "class='active'";
		$Active2Cls		= "";
		$Active5Cls		= "";
		
		//$cDataItm		= $this->db->count_all("tbl_item_whqty WHERE PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'");
		//$cDataItm		= $this->db->count_all("tbl_item_whqty WHERE PRJCODE = '$PRJCODE'");
		$cDataItm		= $this->db->count_all("tbl_item WHERE PRJCODE = '$PRJCODE' AND ISWIP = 1 AND STATUS = 1");
		/*$sqlDataItm		= "SELECT PRJCODE, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLM, ITM_PRICE,
								ITM_IN, ITM_OUT, ACC_ID, ACC_ID_UM
							FROM tbl_item_whqty WHERE PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
		$vDataItm		= $this->db->query($sqlDataItm)->result();*/
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
                    <li <?php echo $Active1Cls; ?>><a href="#wiplist" data-toggle="tab" onClick="setType(1)"><?php echo $WIPList; ?></a></li>		<!-- Tab 2 -->
                    <li <?php echo $Active2Cls; ?>><a href="#fglist" data-toggle="tab" onClick="setType(2)"><?php echo $FinGoods; ?></a></li> 		<!-- Tab 1 -->
                    <li <?php echo $Active5Cls; ?> style="display: none;"><a href="#others" data-toggle="tab" onClick="setType(5)"><?php echo $ItemList; ?></a></li> 		<!-- Tab 1 -->
                </ul>
                <script type="text/javascript">
                	function checkRow(theVal)
                	{
                		document.getElementById('DET_'+theVal).style.display = '';
                	}
                </script>
                <!-- Biodata -->
                <div class="tab-content">
                	<?php
						if($List_Type == 1)
						{
							?>
                                <div class="<?php echo $Active1; ?> tab-pane" id="wiplist">
                                  <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                        	<form method="post" name="frmSearch" action="">
                                            <table id="tree-table" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="2%" style="text-align:center">&nbsp;</th>
                                                        <th width="9%" style="text-align:center" nowrap>
														<?php echo $ItemCode; ?></th>
                                                        <th width="73%" style="text-align:center" nowrap>
														<?php echo $ItemName; ?> </th>
                                                        <th width="4%" style="text-align:center" nowrap>
														<?php echo $Unit; ?></th>
                                                        <th width="6%" style="text-align:center" nowrap>
														<?php echo $StockQuantity; ?>  </th>
                                                        <th width="2%" style="text-align:center; display:none" nowrap>
														<?php echo $ProductionVolume; ?></th>
                                                        <th width="2%" style="text-align:center; display:none" nowrap>
														<?php echo $Remain; ?>  </th>
                                                        <th width="2%" style="text-align:center; display:none" nowrap>
														<?php echo $StockQuantity; ?></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $i = 0;
                                                    $j = 0;
                                                    if($cDataItm>0)
													{
														$sqlItm	= "SELECT PRJCODE, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLM, ITM_PRICE,
																		ITM_IN, ITM_OUT, ACC_ID, ACC_ID_UM, NEEDQRC
																	FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ISWIP = 1 AND STATUS = 1";
														$resItm	= $this->db->query($sqlItm)->result();
														$totRow	= 0;
														foreach($resItm as $row) :								
															$PRJCODE 		= $row->PRJCODE;			// 0
															$ITM_CODE 		= $row->ITM_CODE;			// 1
															$ITM_GROUP 		= $row->ITM_GROUP;			// 2
															$ITM_CATEG 		= $row->ITM_CATEG;			//
															$ITM_NAME 		= $row->ITM_NAME;			// 3
															$ITM_UNIT 		= $row->ITM_UNIT;			// 4
															$ITM_VOLM 		= $row->ITM_VOLM;			// 5	- Stock PER PROJECT
															$ITM_PRICE 		= $row->ITM_PRICE;			// 7
															$ITM_IN 		= $row->ITM_IN;				//
															$ITM_OUT		= $row->ITM_OUT;			//
															$ACC_ID 		= $row->ACC_ID;				// 8
															$ACC_ID_UM		= $row->ACC_ID_UM;			// 9
															$NEEDQRC		= $row->NEEDQRC;			// 9
															$JO_QTY			= 0;
															$SOURCE			= "ITM";
															
															$MR_VOLM		= 0;
															$IRM_VOLM		= 0;
															
															$REM_QTY		= $MR_VOLM - $IRM_VOLM;		// 6
															
															// CEK STOCK STOCK DIDAPATKAN HANYA DI GUDANG PRODUKSI
															// TIDAK WAJIB ADA STOCK KARENA MASIH RESEP, JUST INFO
																$ITM_STOCK	= 0;
																$sqlWHSTOCK	= "SELECT ITM_VOLM AS ITM_STOCK FROM tbl_item_whqty
																				WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'
																					AND WH_CODE = '$WH_CODE'";
																$resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
																foreach($resWHSTOCK as $rowSTOCK) :
																	$ITM_STOCK	= $rowSTOCK->ITM_STOCK;
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
															<td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_GROUP;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_VOLM;?>|<?php echo $REM_QTY;?>|<?php echo $ITM_PRICE;?>|<?php echo $ACC_ID;?>|<?php echo $ACC_ID_UM;?>|<?php echo $ITM_STOCK;?>|<?php echo $PRODS_STEP;?>|<?php echo $NEEDQRC;?>|<?php echo $JO_QTY;?>|<?php echo $SOURCE;?>" onClick="pickThis(this);" /></td>
															<td nowrap=""><?php echo $ITM_CODE; ?></td>
															<td ><?php echo cut_text($ITM_NAME, 50); ?></td>
															<td style="text-align:center"><?php echo $ITM_UNIT; ?></td>
															<td nowrap style="text-align:right">
															<?php echo number_format($ITM_VOLM, $decFormat); ?>&nbsp;</td>
															<td nowrap style="text-align:right; display:none">
															<?php echo number_format($IRM_VOLM, $decFormat); ?></td>
															<td nowrap style="text-align:right; display:none">
															<?php echo number_format($REM_QTY, $decFormat); ?>&nbsp;</td>
															<td nowrap style="text-align:right; display:none">
															<?php echo number_format($ITM_VOLM, $decFormat); ?></td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    }
                                                ?>
                                                </tbody>
                                                <tr>
                                                  <td colspan="10" nowrap>
                                                  	<input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                    <button class="btn btn-primary" type="button" onClick="get_item();">
                                                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                                    <button class="btn btn-danger" type="button" onClick="window.close()">
                                                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
                                                </tr>
                                            </table>
                                            </form>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
						if($List_Type == 2)
						{
							?>
                                <div class="<?php echo $Active2; ?> tab-pane" id="fglist">
                                  <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                        	<form method="post" name="frmSearch" action="">
                                            <table id="tree-table" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="2%" style="text-align:center">&nbsp;</th>
                                                        <th width="9%" style="text-align:center" nowrap>
														<?php echo $ItemCode; ?></th>
                                                        <th width="73%" style="text-align:center" nowrap>
														<?php echo $ItemName; ?> </th>
                                                        <th width="4%" style="text-align:center" nowrap>
														<?php echo $Unit; ?></th>
                                                        <th width="6%" style="text-align:center" nowrap>
														<?php echo $StockQuantity; ?>  </th>
                                                        <th width="2%" style="text-align:center; display:none" nowrap>
														<?php echo $ProductionVolume; ?></th>
                                                        <th width="2%" style="text-align:center; display:none" nowrap>
														<?php echo $Remain; ?>  </th>
                                                        <th width="2%" style="text-align:center; display:none" nowrap>
														<?php echo $StockQuantity; ?></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $i = 0;
                                                    $j = 0;
                                                    if($cDataItm>0)
													{
														$sqlItm	= "SELECT PRJCODE, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLM, ITM_PRICE,
																		ITM_IN, ITM_OUT, ACC_ID, ACC_ID_UM, NEEDQRC
																	FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ISFG = 1 AND STATUS = 1";
														$resItm	= $this->db->query($sqlItm)->result();
														$totRow	= 0;
														foreach($resItm as $row) :								
															$PRJCODE 		= $row->PRJCODE;			// 0
															$ITM_CODE 		= $row->ITM_CODE;			// 1
															$ITM_GROUP 		= $row->ITM_GROUP;			// 2
															$ITM_CATEG 		= $row->ITM_CATEG;			//
															$ITM_NAME 		= $row->ITM_NAME;			// 3
															$ITM_UNIT 		= $row->ITM_UNIT;			// 4
															$ITM_VOLM 		= $row->ITM_VOLM;			// 5	- Stock PER PROJECT
															$ITM_PRICE 		= $row->ITM_PRICE;			// 7
															$ITM_IN 		= $row->ITM_IN;				//
															$ITM_OUT		= $row->ITM_OUT;			//
															$ACC_ID 		= $row->ACC_ID;				// 8
															$ACC_ID_UM		= $row->ACC_ID_UM;			// 9
															$NEEDQRC		= $row->NEEDQRC;			// 9
															$JO_QTY			= 0;
															$SOURCE			= "ITM";
															
															$MR_VOLM		= 0;
															$IRM_VOLM		= 0;
															
															$REM_QTY		= $MR_VOLM - $IRM_VOLM;		// 6
															
															// CEK STOCK STOCK DIDAPATKAN HANYA DI GUDANG PRODUKSI
															// TIDAK WAJIB ADA STOCK KARENA MASIH RESEP, JUST INFO
																$ITM_STOCK	= 0;
																$sqlWHSTOCK	= "SELECT ITM_VOLM AS ITM_STOCK FROM tbl_item_whqty
																				WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'
																					AND WH_CODE = '$WH_CODE'";
																$resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
																foreach($resWHSTOCK as $rowSTOCK) :
																	$ITM_STOCK	= $rowSTOCK->ITM_STOCK;
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
															<td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_GROUP;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_VOLM;?>|<?php echo $REM_QTY;?>|<?php echo $ITM_PRICE;?>|<?php echo $ACC_ID;?>|<?php echo $ACC_ID_UM;?>|<?php echo $ITM_STOCK;?>|<?php echo $PRODS_STEP;?>|<?php echo $NEEDQRC;?>|<?php echo $JO_QTY;?>|<?php echo $SOURCE;?>" onClick="pickThis(this);" /></td>
															<td nowrap=""><?php echo $ITM_CODE; ?></td>
															<td ><?php echo cut_text($ITM_NAME, 50); ?></td>
															<td style="text-align:center"><?php echo $ITM_UNIT; ?></td>
															<td nowrap style="text-align:right">
															<?php echo number_format($ITM_VOLM, $decFormat); ?>&nbsp;</td>
															<td nowrap style="text-align:right; display:none">
															<?php echo number_format($IRM_VOLM, $decFormat); ?></td>
															<td nowrap style="text-align:right; display:none">
															<?php echo number_format($REM_QTY, $decFormat); ?>&nbsp;</td>
															<td nowrap style="text-align:right; display:none">
															<?php echo number_format($ITM_VOLM, $decFormat); ?></td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    }
                                                ?>
                                                </tbody>
                                                <tr>
                                                  <td colspan="10" nowrap>
                                                  	<input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                    <button class="btn btn-primary" type="button" onClick="get_item();">
                                                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                                    <button class="btn btn-danger" type="button" onClick="window.close()">
                                                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
                                                </tr>
                                            </table>
                                            </form>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
					?>
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
<script language="javascript" src="<?php echo base_url() . 'assets/js/javascript.js'; ?>"></script>
<script>
	$(function () 
	{
		$('#tree-table').DataTable({
		  "paging": true,
		  "lengthChange": true,
		  "searching": true,
		  "ordering": false,
		  "info": true,
		  "autoWidth": false
		});
	});
	  
	$(function ()
	{
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
  
	var selectedRows = 0;
	
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

					window.opener.add_itemFG(document.frmSearch.chk[i].value);				
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_itemFG(document.frmSearch.chk.value);
				//alert('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//alert(arrItem)
				for(z=1;z<=5;z++)
				{
					alert('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_itemRM(B,'child');
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