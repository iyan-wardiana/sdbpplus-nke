<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Maret 2018
 * File Name	= itemusage_form.php
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
		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
		if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
		if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'IsVoid')$IsVoid = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$h_title	= 'Penggunaan Material';
		$sureDelete	= "Anda yakin akan menghapus data ini?";
	}
	else
	{
		$h_title	= 'Material Usage';
		$sureDelete	= "Are your sure want to delete?";
	}
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
                      	<th width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                      	<th width="11%"><?php echo $ReceiptNumber ?>  </th>
                      	<th width="7%"><?php echo $ReceiptDate ?> </th>
                      	<th width="12%"><?php echo $ReferenceNumber ?> </th>
                      	<th width="26%"><?php echo $Supplier; ?> </th>
                      	<th width="26%"><?php echo $Description ?></th>
                     	<th width="10%"><?php echo $Status ?> </th>
						<th width="2%" nowrap><?php echo $IsVoid ?> </th>
						<th width="3%" nowrap>&nbsp;</th>
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
							$UM_NUM 		= $row->UM_NUM;
							$UM_CODE 		= $row->UM_CODE;
							$UM_DATE		= $row->UM_DATE;
							$PRJCODE 		= $row->PRJCODE;
							$JOBCODEID 		= $row->JOBCODEID;
							$JOBDESC		= 'none';
							$sqlJOB			= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
							$resJOB			= $this->db->query($sqlJOB)->result();
							foreach($resJOB as $rowJOB) :
								$JOBDESC	= $rowJOB->JOBDESC;
							endforeach;
							$UM_NOTE		= $row->UM_NOTE;
							$UM_STAT		= $row->UM_STAT;
							
							$UM_STAT	= $row->UM_STAT;							
							if($UM_STAT == 0)
							{
								$UM_STATDes	= 'Open';
								$STATCOL	= 'warning';
							}
							elseif($UM_STAT == 1)
							{
								$UM_STATDes	= 'New';
								$STATCOL	= 'warning';
							}
							elseif($UM_STAT == 2)
							{
								$UM_STATDes	= 'Confirm';
								$STATCOL	= 'primary';
							}
							elseif($UM_STAT == 3)
							{
								$UM_STATDes	= 'Approve';
								$STATCOL	= 'success';
							}
							elseif($UM_STAT == 4)
							{
								$UM_STATDes	= 'Close';
								$STATCOL	= 'danger';
							}
							elseif($UM_STAT == 5)
							{
								$UM_STATDes	= 'Reject';
								$STATCOL	= 'danger';
							}
							
							$ISVOID			= $row->ISVOID;														
							if($ISVOID == 0)
							{
								$ISVOIDDes	= 'No';
								$STATCOLV	= 'success';
							}
							elseif($ISVOID == 1)
							{
								$ISVOIDDes	= 'New';
								$STATCOLV	= 'danger';
							}
							
							$REVMEMO		= $row->REVMEMO;
							
							$CollID			= "$UM_NUM";
							$secUpd			= site_url('c_inventory/c_iu180c16/ui180c18box/?id='.$this->url_encryption_helper->encode_url($CollID));
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                                    <td> <?php print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$UM_NUM.'" style="display:none" />'; ?> <?php echo $myNewNo; ?>. </td>
                                    <td nowrap> <?php print $UM_CODE; ?> </td>
                                    <td nowrap> <?php print $UM_DATE; ?> </td>
                                    <td nowrap>&nbsp;-&nbsp;</td>
                                    <td nowrap><?php echo $JOBDESC; ?></td>
                                    <td nowrap><?php print $UM_NOTE; ?></td>
                                    <td nowrap style="text-align:center">
                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                            <?php
                                                echo $UM_STATDes;
                                             ?>
                                         </span>
                                    </td>
                                    <td nowrap style="text-align:center">
                                        <span class="label label-<?php echo $STATCOLV; ?>" style="font-size:11px">
                                            <?php
                                                echo $ISVOIDDes;
                                             ?>
                                         </span>
                                    <td nowrap style="text-align:center">
                                        <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                            <i class="glyphicon glyphicon-print"></i>
                                        </a>
                                        <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($UM_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                            <i class="glyphicon glyphicon-trash"></i>
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