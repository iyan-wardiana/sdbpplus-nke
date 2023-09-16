<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 September 2019
 * File Name	= v_item_coll_selqrc.php
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
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/css/bootstrapxxx.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/css/style.css'; ?>">
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
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
		if($TranslCode == 'Ordered')$Ordered = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'PleaseSelectItem')$PleaseSelectItem = $LangTransl;
		if($TranslCode == 'Requested')$Requested = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$alert1	= "Pilih salah satu item.";
	}
	else
	{
		$alert1	= "Please select an item.";
	}

    if(isset($_POST['submit1']))
    {
        $List_Type      = $this->input->post('List_Type');
        if($List_Type == 1)
        {
            $Active1        = "active";
            $Active2        = "";
            $Active1Cls     = "class='active'";
            $Active2Cls     = "";
        }
        else
        {
            $Active1        = "";
            $Active2        = "active";
            $Active1Cls     = "";
            $Active2Cls     = "class='active'";
        }
    }
    else
    {
        $List_Type      = 1;
        $Active1        = "active";
        $Active2        = "";
        $Active1Cls     = "class='active'";
        $Active2Cls     = "";
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
                    <li <?php echo $Active1Cls; ?>><a href="#wiplist" data-toggle="tab" onClick="setType(1)">Greige</a></li>  <!-- Tab 2 -->
                    <li <?php echo $Active2Cls; ?>><a href="#others" data-toggle="tab" onClick="setType(2)">RIB</a></li>     <!-- Tab 1 -->
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
                                                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
                                                            <th width="14%" style="text-align:center" nowrap><?php echo $Code; ?> </th>
                                                            <th width="33%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
                                                            <th width="46%" style="text-align:center" nowrap><?php echo $ItemName; ?>  </th>
                                                            <th width="5%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $i = 0;
                                                        $j = 0;
                                                        if($countAllItem>0)
                                                        {
                                                            $totRow = 0;
                                                            foreach($vwAllItem as $row) :
                                                                $QRC_NUM        = $row->QRC_NUM;                // 0
                                                                $QRC_CODEV      = $row->QRC_CODEV;              // 1
                                                                $ITM_CODE       = $row->ITM_CODE;               // 2
                                                                $ITM_NAME       = $row->ITM_NAME;               // 3
                                                                $ITM_UNIT       = $row->ITM_UNIT;               // 4
                                                                
                                                                $totRow         = $totRow + 1;
                                                            
                                                                if ($j==1) {
                                                                    echo "<tr class=zebra1>";
                                                                    $j++;
                                                                } else {
                                                                    echo "<tr class=zebra2>";
                                                                    $j--;
                                                                }
                                                                ?> 
                                                                <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $QRC_NUM;?>|<?php echo $QRC_CODEV;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>" onClick="pickThis(this);" /></td>
                                                                <td nowrap><?php echo $QRC_CODEV; ?></td>
                                                                <td nowrap><?php echo "$ITM_CODE - $QRC_NUM"; ?></td>
                                                                <td nowrap><?php echo $ITM_NAME; ?></td>
                                                                <td nowrap style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                                                            </tr>
                                                            <?php
                                                            endforeach;
                                                        }
                                                    ?>
                                                    </tbody>
                                                    <tr>
                                                        <td colspan="8" nowrap>
                                                            <button class="btn btn-primary" type="button" onClick="get_item();">
                                                            <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?></button>&nbsp;
                                                            <button class="btn btn-warning" type="button" onClick="get_qrc();">
                                                            <i class="glyphicon glyphicon-qrcode"></i>&nbsp;&nbsp;QR</button>&nbsp;
                                                            <button class="btn btn-danger" type="button" onClick="window.close()">
                                                            <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
                                                        </td>
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
                                                            <th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" /></th>
                                                            <th width="14%" style="text-align:center" nowrap><?php echo $Code; ?> </th>
                                                            <th width="33%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
                                                            <th width="46%" style="text-align:center" nowrap><?php echo $ItemName; ?>  </th>
                                                            <th width="5%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $i = 0;
                                                        $j = 0;
                                                        if($countAllRIB>0)
                                                        {
                                                            $totRow = 0;
                                                            foreach($vwAllRIB as $row) :
                                                                $QRC_NUM        = $row->ICUT_NUM;                // 0
                                                                $QRC_CODEV      = $row->ICUT_CODE;              // 1
                                                                $ITM_CODE       = $row->ITM_CODE;               // 2
                                                                $ITM_NAME       = $row->ICUT_NOTES;               // 3
                                                                $ITM_UNIT       = $row->ITM_UNIT;               // 4
                                                                
                                                                $totRow         = $totRow + 1;
                                                            
                                                                if ($j==1) {
                                                                    echo "<tr class=zebra1>";
                                                                    $j++;
                                                                } else {
                                                                    echo "<tr class=zebra2>";
                                                                    $j--;
                                                                }
                                                                ?> 
                                                                <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $QRC_NUM;?>|<?php echo $QRC_CODEV;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>" onClick="pickThis(this);" /></td>
                                                                <td nowrap><?php echo $QRC_CODEV; ?></td>
                                                                <td nowrap><?php echo "$ITM_CODE - $QRC_NUM"; ?></td>
                                                                <td nowrap><?php echo $ITM_NAME; ?></td>
                                                                <td nowrap style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                                                            </tr>
                                                            <?php
                                                            endforeach;
                                                        }
                                                    ?>
                                                    </tbody>
                                                    <tr>
                                                        <td colspan="8" nowrap>
                                                            <button class="btn btn-primary" type="button" onClick="get_item();">
                                                            <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?></button>&nbsp;
                                                            <button class="btn btn-warning" type="button" onClick="get_qrc();">
                                                            <i class="glyphicon glyphicon-qrcode"></i>&nbsp;&nbsp;QR</button>&nbsp;
                                                            <button class="btn btn-danger" type="button" onClick="window.close()">
                                                            <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
                                                        </td>
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

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>


<script type="text/javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/js/filereader.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/js/qrcodelib.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/js/webcodecamjs.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/js/main.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
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
	//alert("test1");
	if (selectedRows==NumOfRows) 
	{
		//alert("test2");
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}
	

function get_item() 
	{
		var totSel	= 0;
		var j		= 0;
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				//alert(document.frmSearch.chk[i].checked)
				if(document.frmSearch.chk[i].checked) 
				{
					j = j+1;
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');
					
					window.opener.add_item(document.frmSearch.chk[i].value);
					totSel = totSel+j;
				}
			}
		} 
		else 
		{
			if(document.frmSearch.chk.checked)
			{
				totSel = 1;
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

		if(totSel == 0) 
		{
			alert('<?php echo $alert1; ?>');
			return false;
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