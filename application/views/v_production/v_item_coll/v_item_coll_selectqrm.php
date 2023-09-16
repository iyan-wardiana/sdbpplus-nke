<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 07 September 2019
 * File Name    = v_item_coll_selqrc.php
 * Location     = -
*/
$this->load->view('template/head');

$appName    = $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
    $decFormat      = 2;
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
    $LangID         = $this->session->userdata['LangID'];

    $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    $resTransl      = $this->db->query($sqlTransl)->result();
    foreach($resTransl as $rowTransl) :
        $TranslCode = $rowTransl->MLANG_CODE;
        $LangTransl = $rowTransl->LangTransl;
        
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
        $alert1 = "Pilih salah satu item.";
    }
    else
    {
        $alert1 = "Please select an item.";
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

    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <div class="callout callout-success">
                <?php echo $PleaseSelectItem; ?>
            </div>
                <div class="search-table-outter">
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
                                        $ITM_QTY        = $row->ITM_QTY;                // 5
                                        
                                        $totRow         = $totRow + 1;
                                    
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                        ?> 
                                        <td style="text-align:center"><input type="checkbox" name="chk" value="<?php echo $QRC_NUM;?>|<?php echo $QRC_CODEV;?>|<?php echo $ITM_CODE;?>|<?php echo $ITM_NAME;?>|<?php echo $ITM_UNIT;?>|<?php echo $ITM_QTY;?>" onClick="pickThis(this);" /></td>
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
                                    <i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
                                    <?php
                                        $url_addQRCode  = site_url('c_production/c_pR04uctpr0535/a44QRA0_pR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                        echo anchor("$url_addQRCode",'<button class="btn btn-warning" type="button" onClick="selectitem();"><i class="glyphicon glyphicon-qrcode"></i>&nbsp;&nbsp;QR</button>&nbsp;&nbsp;');
                                    ?>
                                    <button class="btn btn-danger" type="button" onClick="window.close()">
                                    <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
                                </td>
                            </tr>
                        </table>
                  </form>
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
        var totSel  = 0;
        var j       = 0;
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
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>