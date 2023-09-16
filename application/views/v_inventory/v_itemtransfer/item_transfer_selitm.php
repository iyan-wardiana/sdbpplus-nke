<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Februari 2019
 * File Name	= item_transfer_selmr.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata('vers');

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
    		if($TranslCode == 'AccountCode')$AccountCode = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
    		if($TranslCode == 'Amount')$Amount = $LangTransl;
    		if($TranslCode == 'Unit')$Unit = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Others')$Others = $LangTransl;
    		if($TranslCode == 'List')$List = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
    		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
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
    ?>
    
    <body class="<?php echo $appBody; ?>">
        <style>
        	.search-table, td, th {
        		border-collapse: collapse;
        	}
        	.search-table-outter { overflow-x: scroll; }
        </style>

        <form class="form-horizontal" name="frm_01" method="post" action="" style="display:none">
        	<input type="text" name="List_Type" id="List_Type" value="<?php echo $List_Type; ?>" />
            <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
        </form>

        <section class="content">
        	<div class="row">
            	 <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li <?php echo $Active1Cls; ?>><a href="#primaryitem" data-toggle="tab" onClick="setType(1)"><?php echo $List; ?> MR</a></li>		<!-- Tab 2 -->
                            <li <?php echo $Active2Cls; ?>><a href="#others" data-toggle="tab" onClick="setType(2)"><?php echo $Others; ?></a></li> 		<!-- Tab 1 -->
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
                                                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                                        <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ItemCode; ?></th>
                                                        <th width="49%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ItemName; ?> </th>
                                                        <th width="7%" style="text-align:center; vertical-align:middle" nowrap>Unit</th>
                                                        <th width="8%" style="text-align:center; vertical-align:middle" nowrap>Stock</th>
                                                        <th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Request; ?></th>
                                                        <th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Received; ?></th>
                                                        <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Remain; ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $i = 0;
                                                        $j = 0; 
                                                        if($cItmPrm>0)
                                                        {
                                                            $totRow	= 0;
                                                            foreach($vItmPrm as $row) :
                                                                $JOBCODEID 		= $row->JOBCODEID;		// 0 	- 
                                                                $PRJCODE 		= $row->PRJCODE;		// 1 	-
                                                                $ITM_CODE 		= $row->ITM_CODE;		// 2	-
                                                                $ITM_CODE_H		= $row->ITM_CODE_H;		// 3	-
                                                                $ITM_NAME 		= $row->ITM_NAME;		// 4	- 
                                                                $ITM_DESC		= $row->ITM_DESC;		// 5	- MR_DESC
                                                                $ITM_GROUP 		= $row->ITM_GROUP;		// 6	- 
                                                                $ITM_CATEG 		= $row->ITM_CATEG;		// 7	- 
                                                                $ITM_UNIT		= $row->ITM_UNIT;		// 8	- 
                                                                $MR_VOLM 		= $row->MR_VOLM;		// 9 	- MR QTY
                                                                $MR_PRICE 		= $row->MR_PRICE;		// 10 	- MR PRICE
                                                                $IRM_VOLM 		= $row->IRM_VOLM;		// 9 	- IRM QTY
                                                                $IRM_AMOUNT		= $row->IRM_AMOUNT;		// 10 	- IRM AMOUNT
                                                                $REM_MRQTY		= $MR_VOLM - $IRM_VOLM;
                                                                
                                                                // CEK STOCK PER WH
                                                                    $ITM_STOCK	= 0;
                                                                    $sqlWHSTOCK	= "SELECT ITM_VOLM AS ITM_STOCK FROM tbl_item_whqty
                                                                                    WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
                                                                    $resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
                                                                    foreach($resWHSTOCK as $rowSTOCK) :
                                                                        $ITM_STOCK	= $rowSTOCK->ITM_STOCK;
                                                                    endforeach;

                                                                    if($ITM_STOCK == 0)
                                                                    {
                                                                        $sqlWHSTOCK = "SELECT ITM_VOLM AS ITM_STOCK, ITM_OUT, MR_VOLM FROM tbl_item
                                                                                        WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
                                                                        $resWHSTOCK = $this->db->query($sqlWHSTOCK)->result();
                                                                        foreach($resWHSTOCK as $rowSTOCK) :
                                                                            $ITM_STOCK1 = $rowSTOCK->ITM_STOCK;
                                                                            $ITM_OUT    = $rowSTOCK->ITM_OUT;
                                                                            $MRVOLM    = $rowSTOCK->MR_VOLM;
                                                                            $ITM_STOCK  = $ITM_STOCK1 - $ITM_OUT - $MRVOLM;
                                                                        endforeach;
                                                                    }
                                                                    
                                                                $isDisabled		= 0;
                                                                if($REM_MRQTY <= 0)
                                                                {
                                                                    $isDisabled	= 1;
                                                                    $descDis	= $alert1;
                                                                }
                                                                
                                                                if($ITM_STOCK <= 0)
                                                                {
                                                                    $isDisabled	= 1;
                                                                    $descDis	= $alert2;
                                                                    $descCol	= "danger";
                                                                }
                                                                else
                                                                {
                                                                    $descCol	= "success";
                                                                }
                                                                
                                                                $totRow		= $totRow + 1;
                                                            
                                                                if ($j==1) {
                                                                    echo "<tr class=zebra1>";
                                                                    $j++;
                                                                } else {
                                                                    echo "<tr class=zebra2>";
                                                                    $j--;
                                                                }
                                                                ?>
                                                                <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $JOBCODEID;?>|<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_CODE_H;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_DESC;?>|<?php echo $ITM_GROUP;?>|<?php echo $ITM_CATEG;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_STOCK;?>|<?php echo $MR_VOLM;?>|<?php echo $IRM_VOLM;?>|<?php echo $REM_MRQTY;?>|<?php echo $MR_PRICE;?>" onClick="pickThis(this);" <?php if($isDisabled == 1) { ?> disabled title="<?php echo $descDis; ?>" <?php } ?> /></td>
                                                                <td nowrap><?php echo $ITM_CODE; ?></td>
                                                                <td nowrap><?php echo "$ITM_NAME - $ITM_DESC"; ?></td>
                                                                <td nowrap style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                                                                <td nowrap style="text-align:right">
                                                                    <span class="label label-<?php echo $descCol; ?>" style="font-size:12px">
                                                                        <?php echo number_format($ITM_STOCK, 2); ?>
                                                                    </span>
                                                                </td>
                                                                <td nowrap style="text-align:right"><?php echo number_format($MR_VOLM, 2); ?></td>
                                                                <td nowrap style="text-align:right"><?php echo number_format($IRM_VOLM, 2); ?></td>
                                                                <td nowrap style="text-align:right"><?php echo number_format($REM_MRQTY, 2); ?></td>
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
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button></td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } else {?>
                                <div class="<?php echo $Active2; ?> tab-pane" id="others">
                                  	<div class="box box-success">
                                        <div>
                                            &nbsp;
                                        </div>
                                        <div class="form-group">
                                            <form method="post" name="frmSearch" action="">
                                                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                                        <th width="7%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ItemCode; ?></th>
                                                        <th width="49%" style="text-align:center; vertical-align:middle" nowrap><?php echo $ItemName; ?> </th>
                                                        <th width="7%" style="text-align:center; vertical-align:middle" nowrap>Unit</th>
                                                        <th width="8%" style="text-align:center; vertical-align:middle" nowrap>Stock</th>
                                                        <th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Request; ?></th>
                                                        <th width="9%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Received; ?></th>
                                                        <th width="8%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Remain; ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $i = 0;
                                                        $j = 0;
                                                        if($cItmOth>0)
                                                        {
                                                            $totRow	= 0;
                                                            foreach($vItmOth as $row) :
                                                                $JOBCODEID 		= $row->JOBCODEID;		// 0 	- 
                                                                $PRJCODE 		= $row->PRJCODE;		// 1 	-
                                                                $ITM_CODE 		= $row->ITM_CODE;		// 2	-
                                                                $ITM_CODE_H		= $row->ITM_CODE_H;		// 3	-
                                                                $ITM_NAME 		= $row->ITM_NAME;		// 4	- 
                                                                $ITM_DESC		= $row->ITM_DESC;		// 5	- MR_DESC
                                                                $ITM_GROUP 		= $row->ITM_GROUP;		// 6	- 
                                                                $ITM_CATEG 		= $row->ITM_CATEG;		// 7	- 
                                                                $ITM_UNIT		= $row->ITM_UNIT;		// 8	- 
                                                                $MR_VOLM 		= $row->MR_VOLM;		// 9 	- MR QTY
                                                                $MR_PRICE 		= $row->MR_PRICE;		// 10 	- MR PRICE
                                                                $IRM_VOLM 		= $row->IRM_VOLM;		// 9 	- IRM QTY
                                                                $IRM_AMOUNT		= $row->IRM_AMOUNT;		// 10 	- IRM AMOUNT
                                                                
                                                                // CEK STOCK PER WH
                                                                    $ITM_STOCK	= 0;
                                                                    $sqlWHSTOCK	= "SELECT ITM_VOLM AS ITM_STOCK FROM tbl_item_whqty
                                                                                    WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'
                                                                                        AND WH_CODE = '$ORIG'";
                                                                    $resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
                                                                    foreach($resWHSTOCK as $rowSTOCK) :
                                                                        $ITM_STOCK	= $rowSTOCK->ITM_STOCK;
                                                                    endforeach;
        															
                                                                $REM_MRQTY		= $ITM_STOCK - $IRM_VOLM;
        														
                                                                $isDisabled		= 0;
                                                                if($REM_MRQTY <= 0)
                                                                {
                                                                    $isDisabled	= 1;
                                                                    $descDis	= $alert1;
                                                                }
                                                                
                                                                if($ITM_STOCK <= 0)
                                                                {
                                                                    $isDisabled	= 1;
                                                                    $descDis	= $alert2;
                                                                    $descCol	= "danger";
                                                                }
                                                                else
                                                                {
                                                                    $descCol	= "success";
                                                                }
                                                                
                                                                $totRow		= $totRow + 1;
                                                            
                                                                if ($j==1) {
                                                                    echo "<tr class=zebra1>";
                                                                    $j++;
                                                                } else {
                                                                    echo "<tr class=zebra2>";
                                                                    $j--;
                                                                }
                                                                ?>
                                                                <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $JOBCODEID;?>|<?php echo $PRJCODE;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_CODE_H;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_DESC;?>|<?php echo $ITM_GROUP;?>|<?php echo $ITM_CATEG;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_STOCK;?>|<?php echo $MR_VOLM;?>|<?php echo $IRM_VOLM;?>|<?php echo $REM_MRQTY;?>|<?php echo $MR_PRICE;?>" onClick="pickThis(this);" <?php if($isDisabled == 1) { ?> disabled title="<?php echo $descDis; ?>" <?php } ?> /></td>
                                                                <td nowrap><?php echo $ITM_CODE; ?></td>
                                                                <td nowrap><?php echo "$ITM_NAME - $ITM_DESC"; ?></td>
                                                                <td nowrap style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                                                                <td nowrap style="text-align:right">
                                                                    <span class="label label-<?php echo $descCol; ?>" style="font-size:12px">
                                                                        <?php echo number_format($ITM_STOCK, 2); ?>
                                                                    </span>
                                                                </td>
                                                                <td nowrap style="text-align:right"><?php echo number_format($MR_VOLM, 2); ?></td>
                                                                <td nowrap style="text-align:right"><?php echo number_format($IRM_VOLM, 2); ?></td>
                                                                <td nowrap style="text-align:right"><?php echo number_format($REM_MRQTY, 2); ?></td>
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
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button></td>
                                                    </tr>
                                                    </tfoot>
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