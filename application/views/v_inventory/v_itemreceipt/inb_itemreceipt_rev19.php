<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 November 2017
 * File Name	= inb_itemreceipt.php
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
/*$PRJCODE		= $PRJCODE;
$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;*/
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
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
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
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'ReceiptNumber')$ReceiptNumber = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
		if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'IsVoid')$IsVoid = $LangTransl;

	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <?php echo $h2_title; ?>
        <small><?php echo $h3_title; ?></small>
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
        	<div class="search-table-outter">
            <table id="example1" class="table table-bordered table-striped" width="100%">
            	<thead>
               	  <tr>
                      	<th width="3%" style="text-align:center" nowrap><?php echo $Code ?>  </th>
                      	<th width="4%" style="text-align:center" nowrap><?php echo $Date ?> </th>
                      	<th width="7%" style="text-align:center" nowrap>Ref. No.</th>
                      	<th width="23%" style="text-align:center"><?php echo $Supplier; ?> </th>
                      	<th width="60%" style="text-align:center"><?php echo $Description ?></th>
                     	<th width="2%" style="text-align:center"><?php echo $Status ?> </th>
                     	<th width="1%" style="text-align:center">&nbsp;</th>
                	</tr>
                </thead>
                <tbody> 
                    <?php 
                    $i = 0;
					$j = 0;
                    if($cData > 0)
                    {
                        $Unit_Type_Name2	= '';
						foreach($vData as $row) :
							$myNewNo 		= ++$i;
							$IR_NUM 		= $row->IR_NUM;
							$IR_CODE 		= $row->IR_CODE;
							$IR_DATE		= $row->IR_DATE;
							$IR_SOURCE		= $row->IR_SOURCE;
							$PRJCODE 		= $row->PRJCODE;
							$SPLCODE 		= $row->SPLCODE;
							$SPLDESC 		= $row->SPLDESC;
							$PO_NUM			= $row->PO_NUM;
							$IR_REFER		= $row->IR_REFER;
							$IR_AMOUNT		= $row->IR_AMOUNT;
							$IR_NOTE		= $row->IR_NOTE;
							$IR_STAT		= $row->IR_STAT;
							$ISDIRECT		= $row->IR_SOURCE;
							$IR_STAT		= $row->IR_STAT;
                            $IR_STATD		= $row->STATDESC;
                            $STATCOL		= $row->STATCOL;
                            $CREATERNM		= $row->CREATERNM;							
							$empName		= cut_text ("$CREATERNM", 15);							
							$REVMEMO		= $row->REVMEMO;
							
							$CollID			= "$IR_NUM~$ISDIRECT";
							$secUpd			= site_url('c_inventory/c_ir180c15/up180c15dtinb/?id='.$this->url_encryption_helper->encode_url($CollID));
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                                    <td nowrap><?php print $IR_CODE; ?> </td>
                                    <td style="text-align:center" nowrap><?php print date('d M Y', strtotime($IR_DATE)); ?> </td>
                                    <td nowrap><?php print $IR_REFER; ?> </td>
                                    <td><?php echo $SPLDESC; ?></td>
                                    <td><?php print $IR_NOTE; ?></td>
                                    <td nowrap style="text-align:center">
                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                            <?php
                                                echo $IR_STATD;
                                             ?>
                                         </span>
                                    </td>
                                    <td nowrap style="text-align:center">
                                        <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                            <i class="glyphicon glyphicon-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                        endforeach;
                    }
                    ?> 
                </tbody>
            </table>
            </div>
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