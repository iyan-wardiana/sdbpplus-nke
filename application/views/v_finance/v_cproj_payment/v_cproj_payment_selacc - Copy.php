<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Februari 2017
 * File Name	= gej_entry_selacc.php 
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
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Value')$Value = $LangTransl;
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

        <form class="form-horizontal" name="frm_01" method="post" action="" style="display:none">
            <input type="text" name="List_Type" id="List_Type" value="<?php echo $List_Type; ?>" />
            <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
        </form>
        
        <section class="content">
            <div class="row">
                 <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li <?php echo $Active2Cls; ?>><a href="#profPicture" data-toggle="tab" onClick="setType(2)"><?php echo $ItemList; ?></a></li>      <!-- Tab 2 -->
                            <li <?php echo $Active1Cls; ?>><a href="#settings" data-toggle="tab" onClick="setType(1)"><?php echo $AccountList; ?></a></li>      <!-- Tab 1 -->
                        </ul>
                        <!-- Biodata -->
                        <div class="tab-content">
	                	<?php
							if($List_Type == 2) // Daftar Material
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
	                                                        <th width="2%" rowspan="2" style="text-align:center">&nbsp;</th>
	                                                        <th width="7%" rowspan="2" style="text-align:center; vertical-align:middle" nowrap><?php echo $Account; ?></th>
	                                                        <th width="6%" rowspan="2" nowrap style="text-align:center; vertical-align:middle"><?php echo $ItemCode; ?></th>
	                                                        <th width="65%" rowspan="2" nowrap style="text-align:center; vertical-align:middle"><?php echo $Description; ?> </th>
	                                                        <th width="2%" rowspan="2" nowrap style="text-align:center; vertical-align:middle">Unit</th>
	                                                        <th colspan="2" nowrap style="text-align:center"><?php echo $Budget; ?></th>
	                                                        <th colspan="2" nowrap style="text-align:center"><?php echo $Used; ?></th>
	                                                        <th colspan="2" nowrap style="text-align:center"><?php echo $Remain; ?></th>
	                                                  </tr>
	                                                    <tr>
	                                                      <th width="2%" nowrap style="text-align:center">Vol</th>
	                                                      <th width="2%" nowrap style="text-align:center"><?php echo $Value; ?></th>
	                                                      <th width="2%" nowrap style="text-align:center">Vol</th>
	                                                      <th width="2%" nowrap style="text-align:center"><?php echo $Value; ?></th>
	                                                      <th width="8%" nowrap style="text-align:center">Vol</th>
	                                                      <th width="2%" nowrap style="text-align:center"><?php echo $Value; ?></th>
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
	                                                            $JOBCODEID 		= $row->JOBCODEID;
	                                                            $PRJCODE 		= $PRJCODE;
																$JOBDESC		= $row->JOBDESC;
	                                                            $ITM_GROUP 		= $row->ITM_GROUP;
	                                                            $ITM_CODE 		= $row->ITM_CODE;
	                                                            $ITM_UNIT 		= strtoupper($row->ITM_UNIT);
	                                                            $ITM_VOLMBG1	= $row->ITM_VOLMBG;
	                                                            $ITM_PRICE 		= $row->ITM_PRICE;
	                                                            $ITM_BUDG 		= $row->ITM_BUDG;
	                                                            $ADD_VOLM 		= $row->ADD_VOLM;
	                                                            $ADD_JOBCOST 	= $row->ADD_JOBCOST;
	                                                            $ADDM_VOLM 		= $row->ADDM_VOLM;
	                                                            $ADDM_JOBCOST 	= $row->ADDM_JOBCOST;
	                                                            $PR_VOLM 		= $row->PR_VOLM;
	                                                            $PR_AMOUNT 		= $row->PR_AMOUNT;
	                                                            $PO_VOLM 		= $row->PO_VOLM;
	                                                            $PO_AMOUNT 		= $row->PO_AMOUNT;
	                                                            $ITM_USED 		= $row->ITM_USED;
	                                                            $ITM_USED_AM	= $row->ITM_USED_AM;
	                                                            $ITM_STOCK 		= $row->ITM_STOCK;
	                                                            $ITM_LASTP 		= $row->ITM_LASTP;
																if($ITM_LASTP == 0)
																	$ITM_LASTP	= $ITM_PRICE;
																	
	                                                            $ITM_NAME 		= $row->ITM_NAME;
	                                                            $ACC_ID 		= $row->ACC_ID;
	                                                            $FRM 			= $row->FRM;
																
																if($JOBDESC	== '')
																{
																	$sqlJOBDESC		= "SELECT JOBDESC
																							FROM tbl_joblist_detail
																						WHERE JOBCODEID = '$JOBCODEID'
																							AND PRJCODE = '$PRJCODE' LIMIT 1";
																	$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
																	foreach($resJOBDESC as $rowJOBDESC) :
																		$JOBDESC	= $rowJOBDESC->JOBDESC;
																	endforeach;
																}
																
																$JOBDESCPAR		= '';
																$sqlJOBPAR		= "SELECT JOBDESC from tbl_joblist_detail
																						WHERE JOBCODEID IN (SELECT X.JOBPARENT from tbl_joblist_detail X 
																							WHERE X.JOBCODEID = '$JOBCODEID' AND X.PRJCODE = '$PRJCODE')
																					LIMIT 1";
																$resJOBPAR		= $this->db->query($sqlJOBPAR)->result();
																foreach($resJOBPAR as $rowJOBPAR) :
																	$JOBDESCPAR	= $rowJOBPAR->JOBDESC;
																endforeach;
										
																// RESERVE
																$ITM_USEDR			= 0;
																$ITM_USEDR_AM		= 0;
																$sqlJOBDR			= "SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
																						FROM tbl_journaldetail
																						WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE'
																							AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT IN (2,7)";
																$resJOBDR			= $this->db->query($sqlJOBDR)->result();
																foreach($resJOBDR as $rowJOBDR) :
																	$ITM_USEDR		= $rowJOBDR->TOTVOL;
																	$ITM_USEDR_AM	= $rowJOBDR->TOTAMN;
																endforeach;
																
																$ITM_VOLMBG		= $ITM_VOLMBG1 + $ADD_VOLM;
																$BUDG_REMVOLM	= $ITM_VOLMBG - $ITM_USED - $ITM_USEDR;
																//$BUDG_REMAMNT	= ($BUDG_REMVOLM * $ITM_LASTP) - $ITM_USED_AM;
																//$BUDG_REMVOLM	= $ITM_VOLMBG + $ADD_VOLM - $ITM_USED;
																//$BUDG_REMAMNT	= ($ITM_BUDG) + $ADD_JOBCOST - $ITM_USED_AM;
																$BUDG_REMAMNT	= ($ITM_BUDG) + $ADD_JOBCOST - $ITM_USED_AM - $ITM_USEDR_AM;
																
	                                                            $disabledB		= 0;
																$VOLM_DESC		= "success";
																$AMN_DESC		= "success";
	                                                           	if($BUDG_REMVOLM <= 0 && $BUDG_REMAMNT <= 0)
																{
	                                                                $disabledB	= 1;
																	$VOLM_DESC	= "danger";
																	$AMN_DESC	= "danger";
																} 
																elseif($BUDG_REMAMNT <= 0)
																{
	                                                                $disabledB	= 1;
																	$AMN_DESC	= "danger";
																}
	                                                           	elseif($BUDG_REMVOLM <= 0)
																{
																	$VOLM_DESC	= "danger";
																}                                                            
																
																$JONDESCRIP	= "$JOBDESCPAR : " . cut_text($ITM_NAME, 50);
																
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
	                                                            	<input type="radio" name="chk" id="chk_<?php echo $totRow; ?>" value="<?php echo $ACC_ID;?>|<?php echo $JONDESCRIP;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_GROUP;?>|<?php echo $JOBCODEID;?>|<?php echo $BUDG_REMVOLM;?>|<?php echo $BUDG_REMAMNT;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_LASTP;?>|<?php echo $THEROW;?>" onClick="getRow('<?php echo $totRow; ?>')" <?php if($ACC_ID == '' || $disabledB == 1) { ?> disabled <?php } ?> />
	                                                            </td>
	                                                            <td nowrap>
	                                                            	<?php 
																		if($ACC_ID != '') 
																		{
																			echo "$ACC_ID";
																		}
																		else
																		{
																			echo "--- not set ---";
																		}
																	?>
	                                                            </td>
	                                                            <td nowrap>
																<?php
																	if($SOURCE == 'O')
																		echo "$ITM_CODE : $JOBCODEID"; 
																	else
																		echo "$ITM_CODE : $JOBCODEID"; 
																?>
	                                                            </td>
	                                                            <td>
	                                                                <?php echo "$JOBDESCPAR : " . cut_text($ITM_NAME, 50); ?>
	                                                            </td>
	                                                            <td nowrap><?php echo $ITM_UNIT; ?></td>
	                                                            <td style="text-align:right" nowrap>
																<?php
																	// BUDGET VOLUME
																	if(strtoupper($ITM_UNIT) == 'LS')
																	{
																		echo number_format($ITM_PRICE, 2);
																	}
																	else
																	{
																		echo number_format($ITM_VOLMBG, 2);
																	}
	                                                            ?>
	                                                            </td>
	                                                            <td style="text-align:right; font-style:italic" nowrap>hidden
																<?php
																	// BUDGET AMOUNT
																		//echo number_format($ITM_BUDG, 2);
	                                                            ?>
	                                                            </td>
	                                                            <td style="text-align:right" nowrap>
																<?php
																	// USED VOLUME
																	if(strtoupper($ITM_UNIT) == 'LS')
																	{
	                                                            		echo number_format($ITM_PRICE, 2);
																	}
																	else
																	{
																		echo number_format($ITM_USED, 2);
																	}
	                                                            ?>
	                                                            </td>
	                                                            <td style="text-align:right; font-style:italic" nowrap>hidden
																<?php
																	// USED AMOUNT
																		//echo number_format($ITM_USED_AM, 2);
	                                                            ?>
	                                                            </td>
	                                                            <td nowrap style="text-align:right">
																<?php
																	// REMAIN VOLUME
																	if(strtoupper($ITM_UNIT) == 'LS')
																	{
	                                                            		?>
	                                                                    <span class="label label-<?php echo $AMN_DESC; ?>" style="font-size:11px">
																			<?php
	                                                                            echo number_format($ITM_PRICE, 2);
	                                                                        ?>
	                                                                    </span>
	                                                                    <?php
																	}
																	else
																	{
																		?>
	                                                                    <span class="label label-<?php echo $VOLM_DESC; ?>" style="font-size:11px">
																			<?php
	                                                                            echo number_format($BUDG_REMVOLM, 2);
	                                                                        ?>
	                                                                    </span>
	                                                                    <?php
																	}
	                                                            ?>
	                                                            </td>
	                                                            <td nowrap style="text-align:right; font-style:italic;">hidden
	                                                                <span class="label label-<?php echo $AMN_DESC; ?>" style="font-size:11px; display:none">
	                                                                    <?php
	                                                                        echo number_format($BUDG_REMAMNT, 2);
	                                                                    ?>
	                                                                </span>
	                                                            </td>
	                                                        </tr>
	                                                        <?php
	                                                        endforeach;
	                                                    }
	                                                ?>
	                                                </tbody>
	                                                <tr>
	                                                  <td colspan="11" nowrap>
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
						?>
					</div>
                </div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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