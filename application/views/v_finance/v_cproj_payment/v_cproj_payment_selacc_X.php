<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Februari 2017
 * File Name	= gej_entry_selacc.php
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
		if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'Requested')$Requested = $LangTransl;
		if($TranslCode == 'AccountList')$AccountList = $LangTransl;
		if($TranslCode == 'ItemList')$ItemList = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
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
    	 <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li <?php echo $Active2Cls; ?>><a href="#profPicture" data-toggle="tab" onClick="setType(2)"><?php echo $ItemList; ?></a></li>		<!-- Tab 2 -->
                    <li <?php echo $Active1Cls; ?>><a href="#settings" data-toggle="tab" onClick="setType(1)" style="display:none"><?php echo $AccountList; ?></a></li> 		<!-- Tab 1 -->
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
                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="2%">&nbsp;</th>
                                                        <th width="13%" nowrap><?php echo $AccountCode; ?></th>
                                                        <th width="13%" nowrap><?php echo $ItemCode; ?></th>
                                                        <th width="72%" nowrap><?php echo $Description; ?> </th>
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
                                                            $JOBCODEDET 	= $row->JOBCODEDET;
                                                            $JOBCODEID 		= $row->JOBCODEID;
                                                            $JOBCODE 		= $row->JOBCODE;
                                                            $PRJCODE 		= $row->PRJCODE;
                                                            $ITM_CODE 		= $row->ITM_CODE;
															$Account_Number	= $row->ACC_ID;				// 0
															$ACCNUM			= $row->ACC_ID;				// 0
                                                            $ITM_NAME 		= $row->ITM_NAME;			// 1
                                                            $serialNumber	= '';
                                                            $ITM_UNIT 		= $row->ITM_UNIT;
                                                            $ITM_GROUP 		= $row->ITM_GROUP;
                                                            $ITM_CATEG 		= $row->ITM_CATEG;
                                                            $ITM_PRICE 		= $row->ITM_PRICE;
                                                            $ITM_VOLM 		= $row->ITM_VOLM;
                                                            $ITM_STOCK 		= $row->ITM_STOCK;
                                                            $ITM_USED 		= $row->ITM_USED;
                                                            $itemConvertion	= 1;
                                                            $ITM_AMOUNT		= $ITM_PRICE * $ITM_VOLM;
                                                            $tempTotMax		= $ITM_VOLM - $ITM_USED;
                                                            $REQ_VOLM 		= $row->REQ_VOLM;
                                                            $REQ_AMOUNT		= $row->REQ_AMOUNT;
                                                            $PO_AMOUNT		= $row->PO_AMOUNT;
                                                            $PO_VOLM 		= $row->PO_VOLM;
                                                            $IR_VOLM 		= $row->IR_VOLM;
                                                            $IR_AMOUNT		= $row->IR_AMOUNT;							
                                                            $ITM_BUDG		= $ITM_VOLM;
                                                            if($ITM_BUDG == '')
                                                                $ITM_BUDG	= 0;
                                                            $PO_VOLM		= $row->PO_VOLM;
                                                            $PO_AMOUNT		= $row->PO_AMOUNT;
                                                            $ITM_BUDG		= $ITM_AMOUNT;
                                                            $TOT_USEDQTY	= $REQ_VOLM;
                                                            if($TOT_USEDQTY == '')
                                                                $TOT_USEDQTY = 0;
                                                            
                                                            // GET JOB DETAIL
                                                            $JOBDESC		= '';
                                                            $sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
                                                            $resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
                                                            foreach($resJOBDESC as $rowJOBDESC) :
                                                                $JOBDESC	= $rowJOBDESC->JOBDESC;
                                                            endforeach;
                                                            $REMREQ_QTY		= $ITM_VOLM - $REQ_VOLM;
                                                            $REMREQ_AMN		= ($ITM_VOLM * $ITM_PRICE) - ($REQ_AMOUNT);
                                                            //echo "REMREQ_QTY = $REMREQ_QTY, REMREQ_AMN = $REMREQ_AMN<br>";
                                                            $disabledB		= 0;
                                                            if($REMREQ_QTY <= 0)
                                                                $disabledB	= 1;
                                                            if($REMREQ_AMN <= $PO_AMOUNT)
                                                                $disabledB	= 1;
                                                            
                                                            $totRow		= $totRow + 1;
                                                        
                                                            if ($j==1) {
                                                                echo "<tr class=zebra1>";
                                                                $j++;
                                                            } else {
                                                                echo "<tr class=zebra2>";
                                                                $j--;
                                                            }
                                                            ?>
                                                            <td style="text-align:center">
                                                            	<input type="radio" name="chk" id="chk_<?php echo $totRow; ?>" value="<?php echo $Account_Number;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_CODE;?>|<?php echo $THEROW;?>" onClick="getRow('<?php echo $totRow; ?>')" />
                                                            </td>
                                                            <?php if($ACCNUM != '') { ?>
                                                            	<td><?php echo $Account_Number; ?></td>
                                                            <?php
															}
															else
															{ ?>
                                                            	<td style="text-align:center; font-weight:bold; font-style:italic; color:#F00">--- not set ---</td>
                                                            <?php
															}
															?>
                                                            <td><?php echo $ITM_CODE; ?></td>
                                                            <td nowrap><?php echo cut_text($ITM_NAME, 50); ?></td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    }
                                                ?>
                                                </tbody>
                                                <tr>
                                                  <td colspan="4" nowrap>
                                                  	<input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                    <button class="btn btn-primary" type="button" onClick="get_item();">
                                                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                                    <button class="btn btn-danger" type="button" onClick="window.close()">
                                                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  </td>
                                                </tr>
                                            </table>
                                      	</div>
                                    </div>
                                </div>
                            <?php
						}
						elseif($List_Type == 1)
						{
							?>
                                <div class="<?php echo $Active1; ?> tab-pane" id="settings">
                                    <div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="2%">&nbsp;</th>
                                                        <th width="14%" nowrap><?php echo $AccountCode; ?></th>
                                                        <th width="70%" nowrap><?php echo $Description; ?></th>
                                                        <th width="14%" nowrap><?php echo $Amount; ?></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $i = 0;
                                                    $j = 0;
                                                    if($countAllCOA>0)
                                                    {
                                                        $totRow		= 0;
                                                        foreach($vwAllCOA as $row) :
                                                            $Account_Number 	= $row->Account_Number;		// 0
                                                            $Account_NameEn		= $row->Account_NameEn;
                                                            $Account_NameId		= $row->Account_NameId;		// 1
                                                            //$PRJCODE 			= $row->PRJCODE;
                                                            $Account_Class		= $row->Account_Class;
															
                                                            $Base_OpeningBalance= $row->Base_OpeningBalance;
															$Base_Debet 		= $row->Base_Debet;
															$Base_Kredit 		= $row->Base_Kredit;
															$balanceVal 		= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
															
															$isDisabled			= 0;
															if($Account_Class == 4 && $balanceVal == 0)
																$isDisabled		= 1;
															
															$ITM_CODE			= '';
                                                            
                                                            $totRow		= $totRow + 1;
                                                        
                                                            if ($j==1) {
                                                                echo "<tr class=zebra1>";
                                                                $j++;
                                                            } else {
                                                                echo "<tr class=zebra2>";
                                                                $j--;
                                                            }
                                                            ?>
                                                            <td style="text-align:center">
                                                            	<input type="radio" name="chk" id="chk_<?php echo $totRow; ?>" value="<?php echo $Account_Number;?>|<?php echo $Account_NameId;?>|<?php echo $ITM_CODE;?>|<?php echo $THEROW;?>" onClick="getRow('<?php echo $totRow; ?>')" <?php if($isDisabled == 1) { ?> disabled <?php } ?>/>
                                                            </td>
                                                            <td><?php echo $Account_Number; ?></td>
                                                            <td nowrap><?php echo cut_text($Account_NameId, 50); ?></td>
                                                            <td style="text-align:right" nowrap><?php echo number_format($balanceVal, 2); ?></td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    }
                                                ?>
                                                </tbody>
                                                <tr>
                                                    <tfoot>
                                                      <td colspan="4" nowrap>
                                                        <input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                        <button class="btn btn-primary" type="button" onClick="get_item();">
                                                        <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                                        <button class="btn btn-danger" type="button" onClick="window.close()">
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>                  									</td>
                                                    </tfoot>
                                                </tr>
                                            </table>
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
	
	function getRow(thisobj) 
	{
		document.getElementById('rowCheck').value = thisobj;
	}
	
	function get_item() 
	{
		rowChecked	= document.getElementById('rowCheck').value;
		window.opener.add_item(document.getElementById('chk_'+rowChecked).value);
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