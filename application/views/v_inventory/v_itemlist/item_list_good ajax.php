<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= item_list.php
 * Location		= -
*/

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$PRJCODE		= $PRJCODE;
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
</head>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
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
		
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
		if($TranslCode == 'ReceiptQty')$ReceiptQty = $LangTransl;
		if($TranslCode == 'RemBudget')$RemBudget = $LangTransl;
		if($TranslCode == 'Used')$Used = $LangTransl;
		if($TranslCode == 'OnHand')$OnHand = $LangTransl;
		if($TranslCode == 'Price')$Price = $LangTransl;
		if($TranslCode == 'UnitName')$UnitName = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'ItemList')$ItemList = $LangTransl;
		if($TranslCode == 'Upload')$Upload = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
	endforeach;

$selPRJSYNC	= '';
if(isset($_POST['submitSYNC']))
{
	$selPRJSYNC = $_POST['selPRJSYNC'];
	
	$sqlITMSYN	= "SELECT ITM_CODE, ITM_UNIT FROM tbl_item WHERE PRJCODE = '$selPRJSYNC' AND STATUS = 1";
	$resITMSYN 	= $this->db->query($sqlITMSYN)->result();
	foreach($resITMSYN as $rowSYNC) :
		$ITM_CODE 	= $rowSYNC->ITM_CODE;
		$ITM_UNIT 	= $rowSYNC->ITM_UNIT;
		
		// GET ALL BUDGET FROM JOBLIST DETAIL
		$TOT_BUDVOLM	= 0;
		$TOT_BUDAM 		= 0;
		$PRICE_AVG		= 0;
		if($ITM_UNIT == 'LS' || $ITM_UNIT == 'ls')
		{
			$sqlJLD	= "SELECT SUM(ITM_VOLM) AS TOT_BUDVOLM, SUM(ITM_BUDG) AS TOT_BUDAM,
								SUM(ADD_VOLM) AS TOT_ADDVOLM, SUM(ADD_JOBCOST) AS TOT_ADDCOST,
								SUM(ADDM_VOLM) AS TOT_ADDMVOLM, SUM(ADDM_JOBCOST) AS TOT_ADDMCOST
							FROM
								tbl_joblist_detail
							WHERE PRJCODE = '$selPRJSYNC' AND ITM_CODE = '$ITM_CODE' AND ITM_UNIT = '$ITM_UNIT'";
			$resJLD	= $this->db->query($sqlJLD)->result();
			foreach($resJLD as $rowJLD) :
				$TOT_BUDVOLM1 	= $rowJLD->TOT_BUDVOLM;
				$TOT_BUDAM1 	= $rowJLD->TOT_BUDAM;
				$TOT_ADDVOLM1 	= $rowJLD->TOT_ADDVOLM;
				$TOT_ADDCOST1 	= $rowJLD->TOT_ADDCOST;
				$TOT_ADDMVOLM1 	= $rowJLD->TOT_ADDMVOLM;
				$TOT_ADDMCOST1 	= $rowJLD->TOT_ADDMCOST;
				$TOT_BUDVOLM	= 1;
				$TOT_BUDAM		= $TOT_BUDAM1 + $TOT_ADDCOST1 - $TOT_ADDMCOST1;
				$PRICE_AVG		= $TOT_BUDAM / $TOT_BUDVOLM;
			endforeach;
		}
		else
		{
			$sqlJLD	= "SELECT SUM(ITM_VOLM) AS TOT_BUDVOLM, SUM(ITM_BUDG) AS TOT_BUDAM,
								SUM(ADD_VOLM) AS TOT_ADDVOLM, SUM(ADD_JOBCOST) AS TOT_ADDCOST,
								SUM(ADDM_VOLM) AS TOT_ADDMVOLM, SUM(ADDM_JOBCOST) AS TOT_ADDMCOST
							FROM
								tbl_joblist_detail
							WHERE PRJCODE = '$selPRJSYNC' AND ITM_CODE = '$ITM_CODE' AND ITM_UNIT = '$ITM_UNIT'";
			$resJLD	= $this->db->query($sqlJLD)->result();
			foreach($resJLD as $rowJLD) :
				$TOT_BUDVOLM1 	= $rowJLD->TOT_BUDVOLM;
				$TOT_BUDAM1 	= $rowJLD->TOT_BUDAM;
				$TOT_ADDVOLM1 	= $rowJLD->TOT_ADDVOLM;
				$TOT_ADDCOST1 	= $rowJLD->TOT_ADDCOST;
				$TOT_ADDMVOLM1 	= $rowJLD->TOT_ADDMVOLM;
				$TOT_ADDMCOST1 	= $rowJLD->TOT_ADDMCOST;
				$TOT_BUDVOLM	= $TOT_BUDVOLM1 + $TOT_ADDVOLM1 - $TOT_ADDMVOLM1;
				$TOT_BUDAM		= $TOT_BUDAM1 + $TOT_ADDCOST1 - $TOT_ADDMCOST1;
				$PRICE_AVG		= $TOT_BUDAM / $TOT_BUDVOLM;
			endforeach;
		}
		
		$sqlUpdCOA		= "UPDATE tbl_item SET 
								ITM_VOLMBG = $TOT_BUDVOLM
							WHERE PRJCODE = '$selPRJSYNC' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sqlUpdCOA);
	endforeach;
}
$isLoadDone	= 0;
$isSyncDone	= 0;
?>
<script>
	function syncJournal()
	{
		document.getElementById('loading_1').style.display = '';
		document.frmsync.submitSYNC.click();
	}
</script>
<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <?php echo $ItemList; ?>
        <small><?php echo $PRJNAME; ?></small>
        </h1><br>
        <?php /*?><ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Data tables</li>
        </ol><?php */?>
    </section>

    <!-- Main content -->
    
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
          <form name="frmsync" id="frmsync" action="" method=POST>
                <input type="hidden" name="selPRJSYNC" id="selPRJSYNC" value="<?php echo $PRJCODE; ?>">
                <input type="submit" name="submitSYNC" id="submitSYNC" style="display:none">
            </form>
            <table id="example" class="table table-bordered table-striped" width="100%">
            	<thead>
                <tr>
                    <th width="10%" style="vertical-align:middle; text-align:center"><?php echo $ItemCode ?> </th>
                    <th width="31%" style="vertical-align:middle; text-align:center"><?php echo $ItemName ?> </th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
      </div>
      	<!-- /.box -->
    </div>
    <div id="loading_1" class="overlay" style="display:none">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    
    <?php 
		$isLoadDone = 1;
		if($isLoadDone == 1)
		{
			?>
				<script>
					document.getElementById('loading_1').style.display = 'none';
                </script>
    		<?php
		}
	?>
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
	$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "<?php echo site_url('c_inventory/c_it180e2elst/get_data_item')?>",
        "type": "POST"
    } );
} );
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>