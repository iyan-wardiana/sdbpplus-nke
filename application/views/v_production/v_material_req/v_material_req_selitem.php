<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Februari 2017
 * File Name	= purchase_req_selitem.php
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
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'Requested')$Requested = $LangTransl;
		if($TranslCode == 'Substitute')$Substitute = $LangTransl;
		if($TranslCode == 'ItemList')$ItemList = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		
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
	$ISCREATE	= 1;
	
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
		$List_Type		= 2;
		$Active1		= "";
		$Active2		= "active";
		$Active1Cls		= "";
		$Active2Cls		= "class='active'";
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
                    <li <?php echo $Active2Cls; ?>><a href="#profPicture" data-toggle="tab" onClick="setType(2)"><?php echo $ItemList; ?></a></li>		<!-- Tab 2 -->
                    <li <?php echo $Active1Cls; ?>><a href="#settings" data-toggle="tab" onClick="setType(1)"><?php echo $Substitute; ?></a></li> 		<!-- Tab 1 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                	<?php
						if($List_Type == 2)
						{
							?>
                                <div class="<?php echo $Active2; ?> tab-pane" id="profPicture">
                                  <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                        	<form method="post" name="frmSearch" action="">
                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="2%">&nbsp;</th>
                                                        <th width="7%" nowrap><?php echo $ItemCode; ?></th>
                                                        <th width="44%" nowrap><?php echo $ItemName; ?> </th>
                                                        <th width="16%" style="display: none;" nowrap><?php echo $JobName; ?></th>
                                                        <th width="3%" nowrap><?php echo $Unit; ?></th>
                                                        <th width="6%" nowrap><?php echo $BudgetQty; ?>  </th>
                                                        <th width="6%" nowrap><?php echo $Requested; ?></th>
                                                        <th width="9%" nowrap><?php echo $StockQuantity; ?>  </th>
                                                        <th width="6%" nowrap><?php echo $Unit; ?> </th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php
													$JOBDESC	= '';
													/*$sqlJOBDESC	= "SELECT JOBCODEDET, JOBCODEID, JOBCODE, JOBDESC 
																	FROM tbl_joblist_detail
																	WHERE JOBCODEID = '$JOBCODE' AND PRJCODE = '$PRJCODE'";*/
													$sqlJOBDESC	= "SELECT JOBCODEDET, JOBCODEID, JOBCODE, JOBDESC 
																	FROM tbl_joblist_detail
																	WHERE PRJCODE = '$PRJCODE'";
													$resJOBDESC	= $this->db->query($sqlJOBDESC)->result();
													foreach($resJOBDESC as $rowJOBDESC) :
														$JOBCODEDET	= $rowJOBDESC->JOBCODEDET;
														$JOBCODEID	= $rowJOBDESC->JOBCODEID;
														$JOBCODE	= $rowJOBDESC->JOBCODE;
														$JOBDESC	= $rowJOBDESC->JOBDESC;
													endforeach;
                                                    $i = 0;
                                                    $j = 0;
                                                    if($cAllItemPrm>0)
													{
														$totRow	= 0;
														foreach($vwAllItemPrm as $row) :
															$PRJCODE 		= $row->PRJCODE;				// 0
															$ITM_CODE 		= $row->ITM_CODE;				// 1
															$ITM_CODE_H		= $row->ITM_CODE_H;
															$ITM_NAME 		= $row->ITM_NAME;				// 2
															$serialNumber	= '';							// 3
															$ITM_UNIT 		= $row->ITM_UNIT;				// 4
															$ITM_CATEG 		= $row->ITM_CATEG;				// 5
															$ITM_TYPE 		= $row->ITM_TYPE;				// 6
															$ITM_QTY 		= $row->ITM_QTY;				// 7	// NEED QTY IN CCAL
															$ITM_PRICE 		= $row->ITM_PRICE;				// 8
															$ITM_TOTAL		= $row->ITM_TOTAL;				// 9
															$ADD_VOLM 		= 0;
															$ADD_PRICE 		= 0;
															$MR_QTY 		= $row->MR_QTY;					// 10
															$MR_PRICE		= $row->MR_PRICE;				// 11
															$MR_AMOUNT		= $MR_QTY * $MR_PRICE;
															$IRM_VOLM 		= $row->IRM_VOLM;				// 12
															$IRM_AMOUNT		= $row->IRM_AMOUNT;				// 13
															$USED_VOLM		= $row->USED_VOLM;				// 14
															$USED_AMOUNT	= $row->USED_AMOUNT;			// 15
															$itemConvertion	= 1;							// 16
															
															// GET JO DESC
															$JO_DESC		= '';
															$sqlJODESC		= "SELECT JO_DESC FROM tbl_jo_header
																				WHERE JO_NUM = '$JO_NUM' AND PRJCODE = '$PRJCODE'";
															$resJODESC		= $this->db->query($sqlJODESC)->result();
															foreach($resJODESC as $rowJODESC) :
																$JO_DESC	= $rowJODESC->JO_DESC;
															endforeach;
															
															$REMREQ_QTY		= $ITM_QTY - $MR_QTY;			// 17
															$REMREQ_AMOUNT	= $ITM_TOTAL - $MR_AMOUNT;
															
															$disabledB		= 0;
															if($REMREQ_QTY <= 0)
																$disabledB	= 1;
															
															$totRow			= $totRow + 1;
															
															if ($j==1) {
																echo "<tr class=zebra1>";
																$j++;
															} else {
																echo "<tr class=zebra2>";
																$j--;
															}
															?>
															<td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $serialNumber;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_CATEG;?>|<?php echo $ITM_TYPE;?>|<?php echo $ITM_QTY;?>|<?php echo $ITM_PRICE;?>|<?php echo $ITM_TOTAL;?>|<?php echo $MR_QTY;?>|<?php echo $MR_AMOUNT;?>|<?php echo $IRM_VOLM;?>|<?php echo $IRM_AMOUNT;?>|<?php echo $USED_VOLM;?>|<?php echo $USED_AMOUNT;?>|<?php echo $itemConvertion;?>|<?php echo $REMREQ_QTY;?>" onClick="pickThis(this);" <?php if($disabledB == 1) { ?> disabled <?php } ?>/></td>
															<td><?php echo $ITM_CODE; ?></td>
															<td nowrap><?php echo cut_text($ITM_NAME, 50); ?></td>
															<td style="display: none;" nowrap><?php echo cut_text($JOBDESC, 30); ?></td>
															<td style="text-align:center"><?php echo $ITM_UNIT; ?></td>
															<td nowrap style="text-align:right;<?php if($disabledB == 1) { ?> background:#CC9900 <?php } ?>"><?php echo number_format($ITM_QTY, $decFormat); ?>&nbsp;</td>
															<td nowrap style="text-align:right"><?php echo number_format($MR_QTY, $decFormat); ?></td>
															<td nowrap style="text-align:right"><?php echo number_format($USED_VOLM, $decFormat); ?>&nbsp;</td>
															<td nowrap><?php echo $ITM_UNIT; ?></td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    }
                                                ?>
                                                </tbody>
                                                <tr>
                                                  <td colspan="9" nowrap>
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
						else
						{
							?>
                                <div class="<?php echo $Active1; ?> tab-pane" id="profPicture">
                                  <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                        	<form method="post" name="frmSearch" action="">
                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="2%">&nbsp;</th>
                                                        <th width="7%" nowrap><?php echo $ItemCode; ?></th>
                                                        <th width="44%" nowrap><?php echo $ItemName; ?> </th>
                                                        <th width="16%" nowrap><?php echo $JobName; ?></th>
                                                        <th width="3%" nowrap><?php echo $Unit; ?></th>
                                                        <th width="6%" nowrap><?php echo $BudgetQty; ?>  </th>
                                                        <th width="6%" nowrap><?php echo $Requested; ?></th>
                                                        <th width="9%" nowrap><?php echo $StockQuantity; ?>  </th>
                                                        <th width="6%" nowrap><?php echo $Unit; ?> </th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php
													$JOBDESC	= '';
													/*$sqlJOBDESC	= "SELECT JOBCODEDET, JOBCODEID, JOBCODE, JOBDESC 
																	FROM tbl_joblist_detail
																	WHERE JOBCODEID = '$JOBCODE' AND PRJCODE = '$PRJCODE'";*/
													$sqlJOBDESC	= "SELECT JOBCODEDET, JOBCODEID, JOBCODE, JOBDESC 
																	FROM tbl_joblist_detail
																	WHERE PRJCODE = '$PRJCODE'";
													$resJOBDESC	= $this->db->query($sqlJOBDESC)->result();
													foreach($resJOBDESC as $rowJOBDESC) :
														$JOBCODEDET	= $rowJOBDESC->JOBCODEDET;
														$JOBCODEID	= $rowJOBDESC->JOBCODEID;
														$JOBCODE	= $rowJOBDESC->JOBCODE;
														$JOBDESC	= $rowJOBDESC->JOBDESC;
													endforeach;
                                                    $i = 0;
                                                    $j = 0;
                                                    if($cAllItemSubs>0)
													{
														$totRow	= 0;
														foreach($vwAllItemSubs as $row) :
															$PRJCODE 		= $row->PRJCODE;				// 0
															$ITM_CODE 		= $row->ITM_CODE;				// 1
															$ITM_CODE_H		= $row->ITM_CODE_H;
															$ITM_NAME 		= $row->ITM_NAME;				// 2
															$serialNumber	= '';							// 3
															$ITM_UNIT 		= $row->ITM_UNIT;				// 4
															$ITM_CATEG 		= $row->ITM_CATEG;				// 5
															$ITM_TYPE 		= $row->ITM_TYPE;				// 6
															$ITM_QTY 		= $row->ITM_QTY;				// 7	// NEED QTY IN CCAL
															$ITM_PRICE 		= $row->ITM_PRICE;				// 8
															$ITM_TOTAL		= $row->ITM_TOTAL;				// 9
															$ADD_VOLM 		= 0;
															$ADD_PRICE 		= 0;
															$MR_VOLM 		= $row->MR_VOLM;				// 10
															$MR_AMOUNT		= $row->MR_AMOUNT;				// 11
															$IRM_VOLM 		= $row->IRM_VOLM;				// 12
															$IRM_AMOUNT		= $row->IRM_AMOUNT;				// 13
															$USED_VOLM		= $row->USED_VOLM;				// 14
															$USED_AMOUNT	= $row->USED_AMOUNT;			// 15
															$itemConvertion	= 1;							// 16
															
															// GET JO DESC
															$JO_DESC		= '';
															$sqlJODESC		= "SELECT JO_DESC FROM tbl_jo_header
																				WHERE JO_NUM = '$JO_NUM' AND PRJCODE = '$PRJCODE'";
															$resJODESC		= $this->db->query($sqlJODESC)->result();
															foreach($resJODESC as $rowJODESC) :
																$JO_DESC	= $rowJODESC->JO_DESC;
															endforeach;
															
															$REMREQ_QTY		= $ITM_QTY - $MR_VOLM;			// 17
															$REMREQ_AMOUNT	= $ITM_TOTAL - $MR_AMOUNT;
															
															$disabledB		= 0;
															if($REMREQ_QTY <= 0)
																$disabledB	= 1;
															
															$totRow			= $totRow + 1;
															
															if ($j==1) {
																echo "<tr class=zebra1>";
																$j++;
															} else {
																echo "<tr class=zebra2>";
																$j--;
															}
															?>
															<td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $serialNumber;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_CATEG;?>|<?php echo $ITM_TYPE;?>|<?php echo $ITM_QTY;?>|<?php echo $ITM_PRICE;?>|<?php echo $ITM_TOTAL;?>|<?php echo $MR_VOLM;?>|<?php echo $MR_AMOUNT;?>|<?php echo $IRM_VOLM;?>|<?php echo $IRM_AMOUNT;?>|<?php echo $USED_VOLM;?>|<?php echo $USED_AMOUNT;?>|<?php echo $itemConvertion;?>|<?php echo $REMREQ_QTY;?>" onClick="pickThis(this);" <?php if($disabledB == 1) { ?> disabled <?php } ?>/></td>
															<td><?php echo $ITM_CODE; ?></td>
															<td nowrap><?php echo cut_text($ITM_NAME, 50); ?></td>
															<td nowrap><?php echo cut_text($JOBDESC, 30); ?></td>
															<td style="text-align:center"><?php echo $ITM_UNIT; ?></td>
															<td nowrap style="text-align:right;<?php if($disabledB == 1) { ?> background:#CC9900 <?php } ?>"><?php echo number_format($ITM_QTY, $decFormat); ?>&nbsp;</td>
															<td nowrap style="text-align:right"><?php echo number_format($MR_VOLM, $decFormat); ?></td>
															<td nowrap style="text-align:right"><?php echo number_format($USED_VOLM, $decFormat); ?>&nbsp;</td>
															<td nowrap><?php echo $ITM_UNIT; ?></td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    }
                                                ?>
                                                </tbody>
                                                <tr>
                                                  <td colspan="9" nowrap>
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