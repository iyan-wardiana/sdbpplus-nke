<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 05 April 2019
 * File Name	= v_selsn.php
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

$CUST_DESC	= '';
$sqlCUST	= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
$resCUST 	= $this->db->query($sqlCUST)->result();
foreach($resCUST as $rowCUST) :
	$CUST_DESC 	= $rowCUST->CUST_DESC;
endforeach;
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
	$ISREAD 	= $this->session->userdata['ISREAD'];
	$ISCREATE 	= $this->session->userdata['ISCREATE'];
	$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
	$ISDWONL 	= $this->session->userdata['ISDWONL'];
	$LangID 	= $this->session->userdata['LangID'];

	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		if($TranslCode == 'PONumber')$PONumber = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
		if($TranslCode == 'MatchCode')$MatchCode = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'POList')$POList = $LangTransl;
		if($TranslCode == 'ShipmentList')$ShipmentList = $LangTransl;
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
		if($TranslCode == 'SOCode')$SOCode = $LangTransl;
		if($TranslCode == 'SODate')$SODate = $LangTransl;
		if($TranslCode == 'Vehicle')$Vehicle = $LangTransl;
		if($TranslCode == 'Driver')$Driver = $LangTransl;
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
                    <li <?php echo $Active2Cls; ?> style="display:none"><a href="#profPicture" data-toggle="tab" onClick="return false"><?php echo $POList; ?></a></li>		<!-- Tab 2 -->
                    <li <?php echo $Active1Cls; ?>><a href="#settings" data-toggle="tab" onClick="setType(1)"><?php echo "$ShipmentList - $CUST_DESC"; ?></a></li> 		<!-- Tab 1 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                	<form method="post" name="frmSearch" action="">
                	<?php
                    	if($List_Type == 1)
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
                                                        <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                                                        <th width="10%" style="vertical-align:middle; text-align:center" nowrap>
															<?php echo $DocNumber; ?>
                                                        </th>
                                                        <th width="5%" style="vertical-align:middle; text-align:center">
															<?php echo $Date; ?>
                                                        </th>
                                                        <th width="10%" nowrap style="text-align:center">
															<?php echo $SOCode; ?>
                                                        </th>
                                                        <th width="33%" nowrap style="text-align:center">
                                                        	<?php echo $SODate; ?>
                                                        </th>
                                                        <th width="33%" nowrap style="text-align:center">
                                                        	<?php echo $Vehicle; ?>
                                                        </th>
                                                        <th width="66%" nowrap style="text-align:center">
                                                        	<?php echo $Driver; ?>
                                                        </th>
                                                        <th width="66%" nowrap style="text-align:center">&nbsp;</th>
                                                        <th width="6%" nowrap style="text-align:center;"><span style="text-align:center"><?php echo $MatchCode; ?></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $i = 0;
                                                    $j = 0;
                                                    $sqlPOC		= "tbl_offering_h A
                                                                        INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
                                                                    WHERE A.OFF_STAT = 3 AND A.PRJCODE = '$PRJCODE' AND A.ISCLOSE = 0
																		AND A.CUST_CODE = '$CUST_CODE'";				
                                                    $resPOC 	= $this->db->count_all($sqlPOC);
                                                    if($resPOC > 0)
                                                    {
														$sqlPOV		= "SELECT A.OFF_NUM, A.OFF_CODE, A.OFF_DATE, A.OFF_NOTES, A.OFF_STAT,
																			A.BOM_CODE, A.CUST_CODE,
																			B.CUST_DESC
																		FROM tbl_offering_h A
																			INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
																		WHERE A.OFF_STAT = 3 AND A.PRJCODE = '$PRJCODE' AND A.ISCLOSE = 0
																			AND A.CUST_CODE = '$CUST_CODE'
																		ORDER BY A.OFF_NUM ASC";
														$viewAllPOV	= $this->db->query($sqlPOV)->result();
                                                        $totRow	= 0;
                                                        foreach($viewAllPOV as $row) :
                                                            $OFF_NUM 		= $row->OFF_NUM;
                                                            $OFF_CODE 		= $row->OFF_CODE;
                                                            $OFF_DATE 		= $row->OFF_DATE;
                                                            $OFF_NOTES 		= $row->OFF_NOTES;
                                                            $OFF_STAT 		= $row->OFF_STAT;
                                                            $BOM_CODE 		= $row->BOM_CODE;
                                                            $CUST_CODE		= $row->CUST_CODE;
                                                            $CUST_DESC		= $row->CUST_DESC;
                                                                                    
                                                            $totRow			= $totRow + 1;
                                                        
                                                            if ($j==1) {
                                                                echo "<tr class=zebra1>";
                                                                $j++;
                                                            } else {
                                                                echo "<tr class=zebra2>";
                                                                $j--;
                                                            }
                                                            ?>
                                                            <td style="text-align:center"><input type="radio" name="chk" value="<?php echo $OFF_NUM;?>|<?php echo $OFF_CODE;?>|SO" onClick="pickThis(this);" /></td>
                                                            <td nowrap>
                                                                <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
                                                                <?php echo $OFF_CODE; ?></a> 
                                                                <input type="hidden" name="OFF_NUM<?php echo $totRow; ?>" id="OFF_NUM<?php echo $totRow; ?>" value="<?php echo $OFF_NUM; ?>" />
                                                            </td>
                                                            <td><?php echo date('d M Y', strtotime($OFF_DATE)); ?></td>
                                                            <td><?php echo $CUST_DESC; ?></td>
                                                            <td><?php echo $OFF_NOTES; ?></td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td style="text-align:center"><?php echo $BOM_CODE; ?></td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    }
                                                ?>
                                                </tbody>
                                                <tr>
                                                    <td colspan="9" nowrap>                
                                                        <button class="btn btn-primary" type="button" onClick="get_req();">
                                                        	<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    									</button> 
                                                        <button class="btn btn-danger" type="button" onClick="window.close()">
                                                        	<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    									</button>
                                                    </td>
                                                </tr>
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
	
	function pickThisA(thisobj) 
	{
		var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
		//swal(NumOfRows)
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		if(selectedRows > 1)
		{
			swal('Please select one Request');
			return false;
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
	

	function get_req() 
	{
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
					//window.opener.add_item(B,'child');
					swal(B)
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