<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 November 2017
 * File Name	= v_vendor.php
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Name')$Name = $LangTransl;
		if($TranslCode == 'Address')$Address = $LangTransl;
		if($TranslCode == 'City')$City = $LangTransl;
		if($TranslCode == 'Phone')$Phone = $LangTransl;
		if($TranslCode == 'SupplierList')$SupplierList = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$sureDelete	= "Anda yakin akan menghapus data ini?";
	}
	else
	{
		$sureDelete	= "Are your sure want to delete?";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">

<h1>
    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/supplier.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $SupplierList; ?> 
    <small><?php echo $Project; ?></small>
  </h1><br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
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
	<div class="search-table-outter">
      <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
        <thead>
            <tr>
                <th width="2%" style="display:none">No.</th>
              	<th width="4%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code; ?></th>
       	  	  	<th width="30%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Name; ?></th>
           	  	<th width="42%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Address; ?></th>
           	  	<th width="12%" style="text-align:center; vertical-align:middle" nowrap><?php echo $City; ?></th>
           	  	<th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Phone; ?></th>
           	  	<th width="5%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
   	      </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($countSUPL >0)
			{
			foreach($vwSUPL as $row) :
				$myNewNo 		= ++$i;
				$SPLCODE 		= $row->SPLCODE;
				$SPLDESC 		= $row->SPLDESC;
				$SPLADD1 		= $row->SPLADD1;
				$SPLKOTA 		= $row->SPLKOTA;
				$SPLTELP 		= $row->SPLTELP;
				$secUpd			= site_url('c_purchase/c_vendor/update/?id='.$this->url_encryption_helper->encode_url($SPLCODE));
					
				if ($j==1) {
					echo "<tr class=zebra1>";
					$j++;
				} else {
					echo "<tr class=zebra2>";
					$j--;
				}
				?>
                            <td style="display:none"> <?php print $myNewNo; ?>.</td>
                            <td><?php echo $SPLCODE;?></td>
                            <td> <?php print $SPLDESC; ?> </td>
                            <td> <?php print $SPLADD1; ?> </td>
                            <td nowrap> <?php print $SPLKOTA; ?> </td>
                            <td nowrap> <?php print $SPLTELP; ?> </td>
                            <td nowrap>
                                <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update Project">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                </a>
                                <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
					<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7" style="text-align:left">
				<?php
					if($ISCREATE == 1)
					{
						echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;'.$Add.'</button>');
					}
				?>
            </td>
          </tr>
        </tfoot>
   	</table>
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
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>