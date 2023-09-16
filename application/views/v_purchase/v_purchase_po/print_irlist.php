<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 21 Januari 2018
	* File Name		= print_irlist.php
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
		if($TranslCode == 'IRList')$IRList = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'Unit')$Unit = $LangTransl;
		if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
		if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
	endforeach;
	
	$qOPH       = "SELECT * FROM tbl_po_header WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE' AND PO_STAT IN (1,2,3)";
	$resOPH     = $this->db->query($qOPH);
	if($resOPH->num_rows() > 0)
	{
	    foreach($resOPH->result() as $rowH):
	        $PO_NUM     = $rowH->PO_NUM;
	        $PO_CODE    = $rowH->PO_CODE;
	        $PO_DATE    = $rowH->PO_DATE;
	        $PO_DATEV   = date('d/m/Y', strtotime($PO_DATE));
	    endforeach;
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
        <?php echo "$IRList : $PO_CODE ($PO_DATE)"; ?>
    </div>
	<div class="search-table-outter">
        <form class="form-horizontal" name="frm" method="post" action="" enctype="multipart/form-data" onSubmit="return validateInData()">
              <table id="example11" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                <thead>
                    <tr>
                        <th nowrap style="text-align: center; width: 5%">No.</th>
                        <th nowrap style="text-align: center; width: 10%"><?php echo $ItemCode; ?></th>
                        <th nowrap style="text-align: center; width: 20%"><?php echo $ItemName; ?></th>
                        <th nowrap style="text-align: center; width: 30%"><?php echo "Keterangan"; ?></th>
                        <th nowrap style="text-align: center; width: 5%"><?php echo $Unit; ?></th>
                        <th nowrap style="text-align: center; width: 10%"><?php echo "Vol. OP"; ?>  </th>
                        <th nowrap style="text-align: center; width: 10%"><?php echo "Vol. LPM"; ?>  </th>
                        <th nowrap style="text-align: center; width: 10%"><?php echo "Sisa OP"; ?>  </th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $qOP    = "SELECT A.PO_NUM,	A.PO_CODE, A.PO_DATE, A.PRJCODE, A.PR_NUM, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,	
                                A.PRD_ID, A.ITM_CODE, A.ITM_UNIT, A.PR_VOLM, A.PR_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.PO_VOLM, A.PO_PRICE,
                                A.PO_DISP, A.PO_DISC, A.PO_COST, A.PO_DESC_ID, A.PO_DESC, A.PO_DESC1, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2
                                FROM tbl_po_detail A
                                WHERE A.PO_NUM = '$PO_NUM' AND A.PRJCODE = '$PRJCODE'";
                    $resOP  = $this->db->query($qOP);
                    if($resOP->num_rows() > 0)
                    {
                        $totRow	= 0;
                        $j = 0;
                        foreach($resOP->result() as $row) :
                            $j          = $j + 1;
                            $totRow		= $totRow + 1;
                            $PO_NUM     = $row->PO_NUM;
                            $PO_CODE    = $row->PO_CODE;
                            $PRJCODE    = $row->PRJCODE;
                            $PO_DATE    = $row->PO_DATE;
                            $PO_VOLM    = $row->PO_VOLM;
                            $PO_PRICE   = $row->PO_PRICE;
                            $PO_COST    = $row->PO_COST;
                            $ITM_CODE   = $row->ITM_CODE;
                            $IR_VOLM    = $row->IR_VOLM;
                            $IR_AMOUNT  = $row->IR_AMOUNT;
                            $PO_DESC    = $row->PO_DESC;
                            
                            $REM_OP     = $PO_COST - $IR_AMOUNT;
                            
                        // GET ITM_NAME
        					$ITM_NAME		= '';
        					$ITM_CODE_H		= '';
        					$ITM_TYPE		= '';
        					$sqlITMNM		= "SELECT ITM_NAME, ITM_CODE_H, ITM_TYPE, ITM_UNIT
        										FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' 
        											AND PRJCODE = '$PRJCODE' LIMIT 1";
        					$resITMNM		= $this->db->query($sqlITMNM)->result();
        					foreach($resITMNM as $rowITMNM) :
        						$ITM_NAME	= $rowITMNM->ITM_NAME;			// 5
        						$ITM_CODE_H	= $rowITMNM->ITM_CODE_H;
        						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
        						$ITM_UNIT	= $rowITMNM->ITM_UNIT;
        					endforeach;
						
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                            <td style="text-align:center" nowrap><?php echo $totRow; ?>.</td>
                            <td nowrap style="text-align: center;"><?php echo $ITM_CODE; ?></td>
                            <td ><?php echo $ITM_NAME; ?></td>
                            <td><?php echo $PO_DESC; ?></td>
                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                            <td nowrap style="text-align: right;"><?php echo number_format($PO_VOLM, 3); ?></td>
                            <td nowrap style="text-align: right;"><?php echo number_format($IR_VOLM, 3); ?></td>
                            <td nowrap style="text-align: right;"><?php echo number_format($REM_OP, 2); ?></td>
                        </tr>
                        <?php
                        endforeach;
                    }
                ?>
                </tbody>
                <tfoot>
	                <tr>
	                  	<td colspan="4" nowrap>
		                    <button class="btn btn-danger" type="button" onClick="window.close()">
		                    	<i class="fa fa-close"></i>&nbsp;&nbsp;<?php echo $Close; ?>
		                    </button>
	                    </td>
	                </tr>
                </tfoot>
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
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>