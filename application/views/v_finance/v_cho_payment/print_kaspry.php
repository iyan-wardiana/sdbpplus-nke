<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Januari 2018
 * File Name	= print_ir.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateY-$Start_DateM-$Start_DateD";

$LangID 		= $this->session->userdata['LangID'];
if($LangID == 'IND')
{
	$Transl_01	= "Halaman ini merupakan contoh untuk mencetak dokumen permintaan pembelian. Silahkan ajukan kepada kami untuk membuat halaman yang sebenarnya.";
}
else
{
	$Transl_01	= "This page is an example to print a purchase request document. Please feel free to ask us to create an actual page.";
}

$sql_01	= "SELECT * FROM tappname";
$res_01	= $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
	$comp_name	= $row_01->comp_name;
	$comp_add	= $row_01->comp_add;
	$comp_phone	= $row_01->comp_phone;
	$comp_mail	= $row_01->comp_mail;
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

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>


<?php
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'From')$From = $LangTransl;
		if($TranslCode == 'To_x')$To_x = $LangTransl;
		if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
		if($TranslCode == 'OrderID')$OrderID = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'To_x')$To_x = $LangTransl;
		if($TranslCode == 'To_x')$To_x = $LangTransl;
	endforeach
?>

<body class="hold-transition skin-blue sidebar-mini">

<style type="text/css">
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>
    <div class="pad margin no-print">
        <div class="callout callout-info" style="margin-bottom: 0!important; display:none">
            <h4><i class="fa fa-info"></i> Note:</h4>
            <?php echo $Transl_01; ?>
        </div>
    </div>
    <!-- Main content -->
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" >
                    <?php /*?><i class="fa fa-globe"></i> <?php echo $comp_name; ?><?php */?>
                    <small class="pull-right" style="display:none">
                    No.RR.001.18.02
                    <?php /*?>Date: <?php echo date('Y/m/d'); ?><?php */?>
                    </small>
                </h2>
            </div>
            <div class="col-xs-12" style="text-align:center; text-decoration:underline; font-weight:bold; font-size:16px">
            JURNAL KEUANGAN
            </div>
           	<div class="col-xs-12" style="text-align:center; font-weight:bold">
            KAS OPERASIONAL
            </div>
            
            <div class="col-xs-12 table-responsive">
            	<table width="100%" style="font-size:14px">
                	<tr>
                    	<td width="16%">PERIODE</td>
                        <td width="1%">:</td>
                        <td width="83%">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="row invoice-info" style="display:none">
            <div class="col-sm-4 invoice-col">
            	<?php echo $From; ?> :
                <address>
                    <strong><?php echo $comp_name; ?></strong><br>
                    <?php echo $comp_add; ?><br>
                    <?php echo $comp_phone; ?><br>
                    <?php echo $comp_mail; ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
				<?php echo $To_x; ?> :
                <address>
                    <strong>John Doe</strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    Phone: (555) 539-1037<br>
                    Email: john.doe@example.com
                </address>
            </div>
        
       		<div class="col-sm-4 invoice-col">
                <b><?php echo $InvoiceNo; ?> : 007612</b><br>
                <br>
                <b><?php echo $OrderID; ?> :</b> 4F3S8J<br>
                <b><?php echo $DueDate; ?> :</b> 2/22/2014<br>
                <b>Account:</b> 968-34567
        	</div>
        </div>
            
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-bordered table-striped" width="100%" border="1">
                    <thead>
                        <tr style="background-color:#999">
                            <th style="background-color:#999; text-align:center">NO.</th>
                            <th style="background-color:#999; text-align:center">Tanggal</th>
                            <th style="background-color:#999; text-align:center">Deskripsi</th>
                            <th style="background-color:#999; text-align:center">Debet</th>
                            <th style="background-color:#999; text-align:center">Kredit</th>
                            <th style="background-color:#999; text-align:center">Saldo</th>
                            <th style="background-color:#999; text-align:center">Keterangan</th>
                        </tr>
					</thead>
                    <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 table-responsive">
            	&nbsp;
            </div>
            <div class="col-xs-6 table-responsive">
            	<table width="100%" border="1">
                	<tr>
                    	<td colspan="3">Ditandatangani oleh</td>
                        <td style="text-align:center">Dibuat :</td>
                    </tr>
                    <tr>
                    	<td style="text-align:center">Dir Operasional</td>
                        <td style="text-align:center">M. Finance</td>
                        <td style="text-align:center">Ass. M. finance</td>
                        <td style="text-align:center">Finance</td>
                    </tr>
                    <tr height="60px">
                    	<td style="text-align:center">&nbsp;</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td style="text-align:center">&nbsp;</td>
                        <td style="text-align:center">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <!-- /.row -->

      	<div class="row" style="display:none">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <p class="lead">Payment Methods:</p>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                </p>
            </div>
        <!-- /.col -->
        <div class="col-xs-6">
        	<p class="lead"><?php echo $DueDate; ?> : <?php echo date('Y/m/d'); ?></p>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td style="widtd:50%">Subtotal:</td>
                        <td>$250.30</td>
                    </tr>
                    <tr>
                        <td>Tax (9.3%)</td>
                        <td>$10.34</td>
                    </tr>
                    <tr>
                        <td>Shipping:</td>
                        <td>$5.80</td>
                    </tr>
                    <tr>
                        <td>Total:</td>
                        <td>$265.24</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
          <button type="button" class="btn btn-success pull-right" style="display:none"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button>
        </div>
      </div>
    </section>
    <!-- /.content -->
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

<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>