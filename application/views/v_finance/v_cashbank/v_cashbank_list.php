<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Desember 2017
 * File Name	= v_cashbank_list.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

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
		if($TranslCode == 'Phone')$Phone = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h_title	= 'Daftar Kas Bank';
	}
	else
	{
		$h_title	= 'Cash Bank List';
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h_title; ?>
    <small>project</small>
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
            <tr style="background:#CCCCCC">
                <th style="vertical-align:middle; text-align:center" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>
                <th style="vertical-align:middle; text-align:center" width="7%"><?php echo $Code ?></th>
                <th style="vertical-align:middle; text-align:center" width="17%"><?php echo $Name ?></th>
                <th style="vertical-align:middle; text-align:center; display:none" width="21%" ><?php echo $Project ?></th>
                <th style="vertical-align:middle; text-align:center" width="33%"><?php echo $Address ?></th>
                <th style="vertical-align:middle; text-align:center" width="9%"><?php echo $Phone ?></th>
                <th style="vertical-align:middle; text-align:center" width="6%"><?php echo $Status ?></th>
            </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			if($recordcount > 0)
			{
				foreach($viewcashbank as $row) :
					$myNewNo 	= ++$i;
					$B_CODE		= $row->B_CODE;
					$B_NAME		= $row->B_NAME;
					$B_DESC		= $row->B_DESC;
					$B_REKNO	= $row->B_REKNO;
					$B_BRAND	= $row->B_BRAND;
					$B_LOC		= $row->B_LOC;
					$B_STAT		= $row->B_STAT;
					$proj_Name	= '';
					
					if($B_STAT = 1)
					{
						$B_STATDesc	= 'Active';
						$STATCOL		= 'success';
					}
					else
					{
						$B_STATDesc	= 'In Active';
						$STATCOL		= 'danger';
					}
					
					$secUpd			= site_url('c_finance/c_cashbank_list/update/?id='.$this->url_encryption_helper->encode_url($B_CODE));
						
					if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}
					?> 
                            <td style="text-align:center"> <?php echo $myNewNo; ?>.</td>
                            <td nowrap><?php echo anchor($secUpd,$B_CODE);?></td>
                            <td nowrap> <?php print $B_NAME; ?> </td>
                            <td nowrap style="display:none"> <?php print $B_BRAND; ?> </td>
                            <td> <?php print $B_REKNO; ?> </td>
                            <td nowrap> <?php print $B_LOC; ?> </td>
                            <td style="text-align:center">                            
                            <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
								<?php 
                                    echo "&nbsp;&nbsp;$B_STATDesc&nbsp;&nbsp;";
                                 ?>
                            </span>
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
						echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="cus-add-16x16"></i>&nbsp;&nbsp;'.$Add.'</button>');
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

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>