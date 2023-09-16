<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 04 Juli 2018
 * File Name	= v_pinv_sel_dp.php
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

$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
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
</head>
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

<?php
	$LangID 		= $this->session->userdata['LangID'];
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Select')$Select = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
	endforeach;
?>
<div class="box">
    <!-- /.box-header -->
<div class="box-body">
    <div class="callout callout-success" style="vertical-align:top">
        <?php echo "$PRJCODE - $PRJNAME"; ?>
    </div>
	<div class="search-table-outter">
        <form method="post" name="frmSearch" action="">
        <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
            <thead>
                <tr>
                    <th width="3%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
                    <th width="12%" style="vertical-align:middle; text-align:center" nowrap><?php echo $DocNumber; ?></th>
                    <th width="13%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
                    <th width="12%" nowrap style="text-align:center"><?php echo $DueDate; ?></th>
                  <th width="49%" nowrap style="text-align:center"><?php echo $Description; ?></th>
                  <th width="11%" nowrap style="text-align:center"><?php echo $TotAmount; ?></th>
              </tr>
            </thead>
            <tbody>
            <?php
                $i = 0;
                $j = 0;
                if($countAllDP > 0)
                {
                    $totRow	= 0;
                    foreach($viewAllDP as $row) :
						$DP_NUM 		= $row->DP_NUM;
						$DP_CODE 		= $row->DP_CODE;
						$DP_DATE 		= $row->DP_DATE;
						$DP_DATE1		= date_create($DP_DATE);
                        $DP_DATEV 		= date_format($DP_DATE1,"d/m/Y");
						$TTK_DUEDATE 	= '';
						$TTK_DUEDATE1	= '';;
                        $TTK_DUEDATEV 	= '';
						$DP_NOTES		= $row->DP_NOTES;
						$DP_AMOUNT		= $row->DP_AMOUNT;
						$DP_AMOUNT_USED	= $row->DP_AMOUNT_USED;
						$DP_AMOUNT_REM	= $DP_AMOUNT - $DP_AMOUNT_USED;
						$DP_STAT		= $row->DP_STAT;
						$PRJCODE	 	= $row->PRJCODE;
						$SPLCODE 		= $row->SPLCODE;
                        $SPLDESC		= $row->SPLDESC;
                        $compName 		= "";
                        
                        $totRow			= $totRow + 1;
                    
                        if ($j==1) {
                            echo "<tr class=zebra1>";
                            $j++;
                        } else {
                            echo "<tr class=zebra2>";
                            $j--;
                        }
                        ?>
                        <td style="text-align:center">
                        	<input type="radio" name="chk" value="<?php echo "$DP_NUM~$DP_AMOUNT_REM";?>" onClick="pickThis(this, <?php echo $totRow; ?>);" />
                        </td>
                        <td nowrap>
                            <a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
                            <?php echo $DP_CODE; ?></a>
                        </td>
                        <td style="text-align:center"><?php echo $DP_DATE; ?></td>
                        <td style="text-align:center"><?php echo $TTK_DUEDATE; ?></td>
                        <td><?php echo $DP_NOTES; ?></td>
                        <td><?php echo number_format($DP_AMOUNT_REM, $decFormat); ?></td>
                        </tr>
                    <?php
                    endforeach;
                }
            ?>
            </tbody>
            <tr>
                <td colspan="6" nowrap>
                <button class="btn btn-primary" type="button" onClick="get_ir();">
                    <i class="cus-check-green-16x16"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                </button> 
                <button class="btn btn-danger" type="button" onClick="window.close()">
                    <i class="cus-delete-16x16"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                </button>
                </td>
            </tr>
        </table>
        </form>
                
    </div>
    <!-- /.box-body -->
</div>
  <!-- /.box -->
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

function pickThis(thisobj, row) 
{
	var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
	if (thisobj!= '') 
	{
		if (thisobj.checked) selectedRows++;
		else selectedRows--;
	}
	if(selectedRows > 1)
	{
		alert('Please select one Request');
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
	

function get_ir() 
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
					window.opener.add_DP(document.frmSearch.chk[i].value);				
				}
			}
		}
		else
		{
			if(document.frmSearch.chk.checked)
			{
				arrparent = document.frmSearch.chk.value;
				window.opener.add_DP(arrparent);
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
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>