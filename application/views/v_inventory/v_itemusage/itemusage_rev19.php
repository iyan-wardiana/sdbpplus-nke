<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Desember 2017
 * File Name	= itemusage.php
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
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'UsageCode')$UsageCode = $LangTransl;
		if($TranslCode == 'UsageDate')$UsageDate = $LangTransl;
		if($TranslCode == 'JobName')$JobName = $LangTransl;
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
        <?php echo $h_title; ?>
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
            <table id="example1" class="table table-bordered table-striped" width="100%">
            	<thead>
               	  <tr>
                      	<th width="2%" style="display:none; text-align:center">
                        	<input name="chkAll" id="chkAll" type="checkbox" value="" />
                        </th>
                      	<th width="17%" style="text-align:center"><?php echo $UsageCode ?>  </th>
                      	<th width="11%" style="text-align:center" nowrap><?php echo $UsageDate ?> </th>
                      	<th style="text-align:center"><?php echo $JobName ?></th>
                      	<th width="9%" style="text-align:center"><?php echo $Status ?> </th>
						<th width="4%" style="text-align:center" nowrap>&nbsp;</th>
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
							$SPLCODE 		= '';
							$PO_NUM			= '';
							$IR_REFER		= '';
							$IR_AMOUNT		= 0;
							$APPROVE		= '';
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
								$UM_STATDes	= 'Revise';
								$STATCOL	= 'danger';
							}
							elseif($UM_STAT == 5)
							{
								$UM_STATDes	= 'Reject';
								$STATCOL	= 'danger';
							}
							elseif($UM_STAT == 6)
							{
								$UM_STATDes	= 'Close';
								$STATCOL	= 'success';
							}
							elseif($UM_STAT == 7)
							{
								$UM_STATDes	= 'waiting';
								$STATCOL	= 'warning';
							}
							elseif($UM_STAT == 9)
							{
								$UM_STATDes	= 'void';
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
							
							$secUpd		= site_url('c_inventory/c_iu180c16/update/?id='.$this->url_encryption_helper->encode_url($UM_NUM));
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                                    <td style="display:none"> <?php print '<input name="chkDetail" id="chkDetail" type="checkbox" value="'.$UM_NUM.'" />'; ?> </td>
                                    <td nowrap> <?php print $UM_CODE; ?> </td>
                                    <td style="text-align:center" nowrap> <?php print $UM_DATE; ?> </td>
                                    <td nowrap> <?php print $UM_NOTE; ?></td>
                                    <td nowrap style="text-align:center">
                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                            <?php
                                                echo $UM_STATDes;
                                             ?>
                                         </span>
                                    </td>
									<?php
										$callData	= "$PRJCODE~$UM_NUM";
                                        $secPrint	= site_url('c_inventory/c_iu180c16/printdocument/?id='.$this->url_encryption_helper->encode_url($callData));
                                    ?>         
                                    <input type="hidden" name="urlPrint<?php echo $myNewNo; ?>" id="urlPrint<?php echo $myNewNo; ?>" value="<?php echo $secPrint; ?>">
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
					$secAddURL 		= site_url('c_inventory/c_iu180c16/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					$secAddURLDir	= site_url('c_inventory/c_iu180c16/addDir/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                    ?> 
                </tbody>
                <tr>
                    <td colspan="6">
                    	<?php
							if($ISCREATE == 1)
							{
								echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;UM</button>&nbsp;&nbsp;');
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
							}
						?>
					</td>
			    </tr>                           
            </table>
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
	
	function printDocument(row)
	{
		var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>