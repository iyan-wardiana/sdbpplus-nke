<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Desember 2018
 * File Name	= itemreceipt_void.php
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
		if($TranslCode == 'Deviation')$Deviation = $LangTransl;
		if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Update')$Update = $LangTransl;
		if($TranslCode == 'Close')$Close = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$alert_1	= "Masukan alasan mengapa document ini akan dibatalkan.";
	}
	else
	{
		$alert_1	= "Enter the reason why this document will be canceled.";
	}
?>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
</section>
<!-- Main content -->

<div class="box">
    <!-- /.box-header -->
<div class="box-body">
    <div class="callout callout-success" style="vertical-align:top">
        <?php echo "$PRJCODE - $PRJNAME"; ?>
    </div>
	<div class="search-table-outter">
        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
            <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
            <input type="hidden" name="rowCount" id="rowCount" value="0">
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptCode ?> </label>
                <div class="col-sm-10">
                    <input type="text" name="IR_NUM1" id="IR_NUM1" value="<?php echo $IR_NUM; ?>" class="form-control" style="max-width:175px" disabled >
                    <input type="hidden" name="IR_NUM" id="IR_NUM" value="<?php echo $IR_NUM; ?>" >
                    <input type="hidden" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" >
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?> </label>
                <div class="col-sm-10">
                    <input type="text" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" class="form-control" style="max-width:175px" disabled >
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?> </label>
                <div class="col-sm-10">
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>                        
                        <input type="text" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:90px" disabled>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $SupplierName; ?> </label>
                <div class="col-sm-10">
                    <select name="SPLCODE" id="SPLCODE" class="form-control" style="max-width:300px" onChange="getVendName(this.value)" disabled>
                        <?php
                        $i = 0;
                        if($countSUPL > 0)
                        {
                            foreach($vwSUPL as $row) :
                                $SPLCODE1	= $row->SPLCODE;
                                $SPLDESC1	= $row->SPLDESC;
                                ?>
                                    <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLDESC1 - $SPLCODE1"; ?></option>
                                <?php
                            endforeach;
                        }
                        else
                        {
                            ?>
                                <option value=""> --- </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?> </label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="VOID_NOTE"  id="VOID_NOTE" style="max-width:350px;height:70px"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
                <div class="col-sm-10">
                	<?php
						if($ISVOID == 0)
						{
							?>
                                <button class="btn btn-primary">
                                <i class="cus-save-16x16"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                </button>
                            <?php
						}
					?>
                    <button class="btn btn-danger" type="button" onClick="window.close()"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?></button>
                </div>
            </div>
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
	
	function checkInp()
	{
		VOID_NOTE	= document.getElementById("VOID_NOTE").value;
		if(VOID_NOTE == '')
		{
			alert("<?php echo $alert_1; ?>");
			document.getElementById("VOID_NOTE").focus();
			return false;
		}
	}
</script>
<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
//$this->load->view('template/foot');
?>