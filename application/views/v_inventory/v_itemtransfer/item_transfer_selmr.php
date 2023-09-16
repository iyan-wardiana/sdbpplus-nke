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
    		if($TranslCode == 'Select')$Select = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Substitute')$Substitute = $LangTransl;
    		if($TranslCode == 'List')$List = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Amount')$Amount = $LangTransl;
    		
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
    		if($TranslCode == 'Amount')$Amount = $LangTransl;
    		if($TranslCode == 'Unit')$Unit = $LangTransl;
    		if($TranslCode == 'Select')$Select = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'CustName')$CustName = $LangTransl;
    		if($TranslCode == 'OrderNum')$OrderNum = $LangTransl;
    		if($TranslCode == 'JobCode')$JobCode = $LangTransl;
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

        <form class="form-horizontal" name="frm_01" method="post" action="" style="display:none">
        	<input type="text" name="List_Type" id="List_Type" value="<?php echo $List_Type; ?>" />
            <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
        </form>

        <section class="content">
        	<div class="row">
            	 <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li <?php echo $Active2Cls; ?>><a href="#profPicture" data-toggle="tab" onClick="setType(2)"><?php echo $List; ?> MR</a></li>		<!-- Tab 2 -->
                            <li <?php echo $Active1Cls; ?>><a href="#settings" data-toggle="tab" onClick="setType(1)" style="display:none"><?php echo $Substitute; ?></a></li> 		<!-- Tab 1 -->
                        </ul>
                        <!-- Biodata -->
                        <div class="tab-content">
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
                                                    <th width="10%" nowrap><?php echo $Code; ?></th>
                                                    <th width="4%" nowrap><?php echo $Date; ?> </th>
                                                    <th width="11%" nowrap><?php echo $OrderNum; ?></th>
                                                    <th width="26%" nowrap><?php echo $CustName; ?>  </th>
                                                    <th width="39%" nowrap><?php echo $Description; ?></th>
                                                    <th width="8%" nowrap><?php echo $Amount; ?>  </th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $i = 0;
                                                $j = 0;
                                                if($cAllMTR>0)
                                                {
                                                    $totRow	= 0;
                                                    foreach($vAllMTR as $row) :
                                                        $MR_NUM 		= $row->MR_NUM;			// 0
                                                        $MR_CODE 		= $row->MR_CODE;		// 1
                                                        $MR_DATE 		= $row->MR_DATE;
                                                        $JO_NUM 		= $row->JO_NUM;			// 2
                                                        $JO_CODE 		= $row->JO_CODE;
                                                        $SO_NUM 		= $row->SO_NUM;			// 3
                                                        $SO_CODE 		= $row->SO_CODE;
        												$CCAL_NUM		= $row->CCAL_NUM;
                                                        $CUST_DESC 		= $row->CUST_DESC;		
                                                        $MR_NOTE		= $row->MR_NOTE;
                                                        $MR_AMOUNT 		= $row->MR_AMOUNT;
                                                        $MR_NOTE 		= $row->MR_NOTE;
                                                        
                                                        $totRow			= $totRow + 1;
                                                        
                                                        if ($j==1) {
                                                            echo "<tr class=zebra1>";
                                                            $j++;
                                                        } else {
                                                            echo "<tr class=zebra2>";
                                                            $j--;
                                                        }
                                                        ?>
                                                        <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $MR_NUM;?>|<?php echo $MR_CODE;?>|<?php echo $JO_NUM;?>|<?php echo $SO_NUM;?>|<?php echo $CCAL_NUM;?>" onClick="pickThis(this);"></td>
                                                        <td><?php echo $MR_CODE; ?></td>
                                                        <td nowrap style="text-align:center">
        													<?php echo date('d M Y', strtotime($MR_DATE)); ?>
                                                        </td>
                                                        <td nowrap><?php echo cut_text($JO_CODE, 50); ?></td>
                                                        <td nowrap><?php echo cut_text($CUST_DESC, 50); ?></td>
                                                        <td nowrap><?php echo cut_text($MR_NOTE, 50); ?></td>
                                                        <td nowrap style="text-align:center; font-style: italic;">
        													hidden<?php //echonumber_format($MR_AMOUNT, $decFormat); ?>&nbsp;
                                                        </td>
                                                        </tr>
                                                    <?php
                                                    endforeach;
                                                }
                                            ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                              <td colspan="7" nowrap>
                                                <input type="hidden" name="rowCheck" id="rowCheck" value="">
                                                <button class="btn btn-primary" type="button" onClick="get_item();">
                                                <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?></button> 
                                                <button class="btn btn-danger" type="button" onClick="window.close()">
                                                <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?></button></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
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