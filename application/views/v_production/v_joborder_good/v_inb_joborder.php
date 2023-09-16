<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 11 Februari 2019
 * File Name	= v_inb_joborder.php
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

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
?>

<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>

<!DOCTYPE html>
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
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'CustName')$CustName = $LangTransl;
		if($TranslCode == 'Product')$Product = $LangTransl;
		if($TranslCode == 'Value')$Value = $LangTransl;
		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
		if($TranslCode == 'Term')$Term = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'User')$User = $LangTransl;
		if($TranslCode == 'PurchaseOrder')$PurchaseOrder = $LangTransl;
		if($TranslCode == 'Purchase')$Purchase = $LangTransl;
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'PODirect')$PODirect = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
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
        <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
        <small><?php echo $PRJNAME; ?></small>
        </h1><br>
    </section>

    <!-- Main content -->
    
    <div class="box">
        <!-- /.box-header -->
		<div class="box-body">
            <div class="search-table-outter">
                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                    <thead>
                    <tr>
                        <!--<th width="4%" rowspan="2" style="vertical-align:middle; text-align:center"><input name="chkAll" id="chkAll" type="checkbox" value="" /></th>-->
                        <th width="4%" style="vertical-align:middle; text-align:center">&nbsp;</th>
                        <th width="11%" style="vertical-align:middle; text-align:center; display:none"><?php echo $Code ?> </th>
                        <th width="8%" style="vertical-align:middle; text-align:center"><?php echo $ManualCode ?> </th>
                        <th width="6%" style="vertical-align:middle; text-align:center"><?php echo $Date ?> </th>
                        <th width="28%" style="vertical-align:middle; text-align:center"><?php echo $CustName  ?> </th>
                        <th width="28%" style="vertical-align:middle; text-align:center"><?php echo $Product ?> </th>
                        <th width="8%" style="vertical-align:middle; text-align:center"><?php echo $Value ?> </th>
                        <th width="4%" style="vertical-align:middle; text-align:center">Status</th>
                        <th width="3%" style="vertical-align:middle; text-align:center">&nbsp;</th>
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
                                $JO_NUM 		= $row->JO_NUM;
                                $JO_CODE 		= $row->JO_CODE;
                                $PRJCODE 		= $row->PRJCODE;
                                $JO_DATE 		= $row->JO_DATE;
								$JO_PRODD		= $row->JO_PRODD;
                                $CUST_CODE 		= $row->CUST_CODE;
                                $CUST_DESC 		= $row->CUST_DESC;
								$CUST_DESC 		= strtolower($CUST_DESC);
								$CUST_DESC 		= ucwords($CUST_DESC);
                                $JO_DESC 		= $row->JO_DESC;
                                $JO_VOLM		= $row->JO_VOLM;
                                $JO_AMOUNT		= $row->JO_AMOUNT;
                                $JO_NOTES		= $row->JO_NOTES;
								$JO_NOTES2		= $row->JO_NOTES2;
                                $JO_STAT 		= $row->JO_STAT;
													
								if($JO_STAT == 0)
								{
									$JO_STATD 	= 'fake';
									$STATCOL	= 'danger';
								}
								elseif($JO_STAT == 1)
								{
									$JO_STATD 	= 'New';
									$STATCOL	= 'warning';
								}
								elseif($JO_STAT == 2)
								{
									$JO_STATD 	= 'Confirm';
									$STATCOL	= 'primary';
								}
								elseif($JO_STAT == 3)
								{
									$JO_STATD 	= 'Approved';
									$STATCOL	= 'success';
								}
								elseif($JO_STAT == 4)
								{
									$JO_STATD 	= 'Revise';
									$STATCOL	= 'danger';
								}
								elseif($JO_STAT == 5)
								{
									$JO_STATD 	= 'Rejected';
									$STATCOL	= 'danger';
								}
								elseif($JO_STAT == 6)
								{
									$JO_STATD 	= 'Close';
									$STATCOL	= 'danger';
								}
								elseif($JO_STAT == 7)
								{
									$JO_STATD 	= 'Awaiting';
									$STATCOL	= 'warning';
								}
								else
								{
									$JO_STATD 	= 'Not Range';
									$STATCOL	= 'warning';
								}
                                
								$CollID		= "$JO_NUM~$PRJCODE";
								
								$secUpd		= site_url('c_production/c_j0b0rd3r/up180djinb/?id='.$this->url_encryption_helper->encode_url($CollID));
								$secPrint	= site_url('c_production/c_j0b0rd3r/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($JO_NUM));
                                
                                if ($j==1) {
                                    echo "<tr class=zebra1>";
                                    $j++;
                                } else {
                                    echo "<tr class=zebra2>";
                                    $j--;
                                }
                        		?>
                                    <td><?php print $myNewNo; ?>.</td>                   
                                    <td nowrap style="text-align:left; display:none"> <?php print $JO_NUM; ?> </td>                   
                                    <td nowrap style="text-align:left"> <?php print $JO_CODE; ?></td>
                                    <td style="text-align:center" nowrap><?php print $JO_DATE; ?></td>
                                    <?php
										$CUST_CODE2	= '';
										$CUST_DESC2	= '';
										$sqlCUST	= "SELECT CUST_CODE, CUST_DESC FROM tbl_customer
														WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
										$resCUST 	= $this->db->query($sqlCUST)->result();
										foreach($resCUST as $rowCUST) :
											$CUST_CODE2 = $rowCUST->CUST_CODE;
											$CUST_DESC2 = $rowCUST->CUST_DESC;
										endforeach;
                                    ?>
                                    <td style="text-align:left" nowrap><?php print $CUST_DESC; ?></td>
                                    <td style="text-align:left" nowrap><?php print $JO_DESC; ?></td>
                                    <td style="text-align:right"><?php print number_format($JO_AMOUNT, $decFormat); ?></td>
                                    <td style="text-align:center">
                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                            <?php
                                                echo $JO_STATD;
                                             ?>
                                         </span>
                                    </td>
									<?php
										$secPIRList	= site_url('c_production/c_j0b0rd3r/printirlist/?id='.$this->url_encryption_helper->encode_url($JO_NUM));
                                    ?>
                                	<input type="hidden" name="urlIRList<?php echo $myNewNo; ?>" id="urlIRList<?php echo $myNewNo; ?>" value="<?php echo $secPIRList; ?>">                        
                                    <input type="hidden" name="urlPrint<?php echo $myNewNo; ?>" id="urlPrint<?php echo $myNewNo; ?>" value="<?php echo $secPrint; ?>">
                                    <td style="text-align:center" nowrap>
                                        <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a href="javascript:void(null);" class="btn btn-warning btn-xs" title="View Receipt" onClick="printIRList('<?php echo $myNewNo; ?>')" style="display:none">
                                            <i class="glyphicon glyphicon-list"></i>
                                        </a>
                                        <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                            <i class="glyphicon glyphicon-print"></i>
                                        </a>
                                        <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($JO_STAT > 1) { ?>disabled="disabled" <?php } ?> style="display:none">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                	</td>
                            	</tr>
                            	<?php 
                   			endforeach;
                		}
                        $url_add 	= site_url('c_production/c_j0b0rd3r/a44_j0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        //$url_addRO 	= site_url('c_production/c_j0b0rd3r/a44p180c21o_r0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        //$url_addDir	= site_url('c_production/c_j0b0rd3r/addDir/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        ?> 
                    </tbody>
                    <tr>
                        <td colspan="11">
                            <?php
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
								if ( ! empty($link))
								{
									foreach($link as $links)
									{
										echo $links;
									}
								}
							?>
                        </td>
                    </tr>
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
	
	function printIRList(row)
	{
		var url	= document.getElementById('urlIRList'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
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