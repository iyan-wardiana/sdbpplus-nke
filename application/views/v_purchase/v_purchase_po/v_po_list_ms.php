<?php
/*  
 * Author		= Hendar Permana
 * Create Date	= 26 Mei 2017
 * Updated		= Dian Hermanto - 11 November 2017
 * File Name	= v_po_list.php
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
		if($TranslCode == 'OPCode')$OPCode = $LangTransl;
		if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Supplier')$Supplier = $LangTransl;
		if($TranslCode == 'PRCode')$PRCode = $LangTransl;
		if($TranslCode == 'OPCost')$OPCost = $LangTransl;
		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
		if($TranslCode == 'Term')$Term = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'User')$User = $LangTransl;
		if($TranslCode == 'PurchaseOrder')$PurchaseOrder = $LangTransl;
		if($TranslCode == 'Purchase')$Purchase = $LangTransl;
		if($TranslCode == 'AddPO')$AddPO = $LangTransl;
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
        <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $PurchaseOrder; ?>
        <small><?php echo $Purchase; ?></small>
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
                        <th width="2%" style="vertical-align:middle; text-align:center">No</th>
                        <th width="10%" style="vertical-align:middle; text-align:center; display:none"><?php echo $OPCode ?> </th>
                        <th width="6%" style="vertical-align:middle; text-align:center"><?php echo $ManualCode ?> </th>
                        <th width="6%" style="vertical-align:middle; text-align:center"><?php echo $Date ?> </th>
                        <th width="22%" style="vertical-align:middle; text-align:center"><?php echo $Supplier  ?> </th>
                        <th width="7%" style="vertical-align:middle; text-align:center"><?php echo $PRCode ?> </th>
                        <th width="11%" style="vertical-align:middle; text-align:center"><?php echo $OPCost ?> </th>
                        <th width="8%" style="vertical-align:middle; text-align:center"><?php echo $ReceivePlan ?> </th>
                        <th width="25%" style="vertical-align:middle; text-align:center; display:none"><?php echo $Term ?> </th>                   
                        <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Approve ?> </th>
                        <th width="4%" style="vertical-align:middle; text-align:center">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody> 
                        <?php 
                        $i = 0;
                        $j = 0;
                        if($countPO > 0)
                        {
                            $Unit_Type_Name2	= '';
                            foreach($vwPO as $row) :
                                $myNewNo 		= ++$i;
                                $PO_NUM 		= $row->PO_NUM;
                                $PO_CODE 		= $row->PO_CODE;
                                $PO_TYPE 		= $row->PO_TYPE;
                                $PO_CAT 		= $row->PO_CAT;
                                $PO_DATE 		= $row->PO_DATE;
                                $PRJCODE 		= $row->PRJCODE;
                                $SPLCODE 		= $row->SPLCODE;
                                $PR_NUM 		= $row->PR_NUM;
                                $PR_CODE 		= $row->PR_CODE;
                                $PO_CURR 		= $row->PO_CURR;
                                $PO_CURRATE		= $row->PO_CURRATE;
                                $PO_TAXCURR		= $row->PO_TAXCURR;
                                $PO_TAXRATE 	= $row->PO_TAXRATE;
                                $PO_TOTCOST		= $row->PO_TOTCOST;
								$PO_CREATER		= $row->PO_CREATER;
								$PO_PLANIR		= $row->PO_PLANIR;
                                $PO_TERM 		= $row->PO_TERM;
                                $PO_PAYTYPE 	= $row->PO_PAYTYPE;
                                $PO_STAT 		= $row->PO_STAT;
                                $PO_INVSTAT		= $row->PO_INVSTAT;
                                $ISDIRECT		= $row->ISDIRECT;
                                $PO_NOTES		= $row->PO_NOTES;
                                $PO_MEMO		= $row->PO_MEMO;;
                                $JOBCODE		= $row->JOBCODE;
													
								if($PO_STAT == 0)
								{
									$PO_STATD 	= 'fake';
									$STATCOL	= 'danger';
								}
								elseif($PO_STAT == 1)
								{
									$PO_STATD 	= 'New';
									$STATCOL	= 'warning';
								}
								elseif($PO_STAT == 2)
								{
									$PO_STATD 	= 'Confirm';
									$STATCOL	= 'primary';
								}
								elseif($PO_STAT == 3)
								{
									$PO_STATD 	= 'Approved';
									$STATCOL	= 'success';
								}
								elseif($PO_STAT == 4)
								{
									$PO_STATD 	= 'Revise';
									$STATCOL	= 'danger';
								}
								elseif($PO_STAT == 5)
								{
									$PO_STATD 	= 'Rejected';
									$STATCOL	= 'danger';
								}
								elseif($PO_STAT == 6)
								{
									$PO_STATD 	= 'Close';
									$STATCOL	= 'info';
								}
								elseif($PO_STAT == 7)
								{
									$PO_STATD 	= 'Awaiting';
									$STATCOL	= 'warning';
								}
								elseif($PO_STAT == 9)
								{
									$PO_STATD 	= 'void';
									$STATCOL	= 'danger';
								}
								else
								{
									$PO_STATD 	= 'Not Ranger';
									$STATCOL	= 'danger';
								}
                                
								$CollID		= "$PO_NUM~$ISDIRECT";
								if($PO_TYPE == 1)
								{
                                	$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
									$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
								}
								else
								{
                                	$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_r0/?id='.$this->url_encryption_helper->encode_url($CollID));		
									$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdocro/?id='.$this->url_encryption_helper->encode_url($PO_NUM));							
								}
                                
                                if ($j==1) {
                                    echo "<tr class=zebra1>";
                                    $j++;
                                } else {
                                    echo "<tr class=zebra2>";
                                    $j--;
                                }
                        		?>
                                    <td style="text-align:center"><?php print $myNewNo; ?>.</td>                   
                                    <td nowrap style="text-align:left; display:none"> <?php print $PO_NUM; ?> </td>                   
                                    <td nowrap style="text-align:left"> <?php print $PO_CODE; ?> </td>
                                    <td style="text-align:center" nowrap><?php print $PO_DATE; ?> </td>
                                    
                                    <?php
									$SPLCODE2	= '';
									$SPLDESC2	= '';
                                    $sqlS		= "SELECT SPLCODE, SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
                                    $resultS 	= $this->db->query($sqlS)->result();
                                    foreach($resultS as $rowS) :
                                    {
                                        $SPLCODE2 = $rowS->SPLCODE;
                                        $SPLDESC2 = $rowS->SPLDESC;
                                    }
                                    endforeach;
                                    ?>
                                    <td style="text-align:left" nowrap><?php print "$SPLCODE2 - $SPLDESC2"; ?> </td>
                                    <td style="text-align:left" nowrap><?php print "$PR_CODE"; ?> </td>
                                    <td style="text-align:right"><?php print number_format($PO_TOTCOST, $decFormat); ?> </td>
                                    <td style="text-align:center"><?php print $PO_PLANIR; ?> </td>
                                    <td style="text-align:left; display:none"><?php print $PO_TERM; ?> </td>
                                    <td style="text-align:center">
                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                            <?php
                                                echo $PO_STATD;
                                             ?>
                                         </span>
                                    </td>
									<?php
                                        //$secUpd		= site_url('c_purchase/c_p180c21o/update/?id='.$this->url_encryption_helper->encode_url($PR_NUM));
										$secPIRList	= site_url('c_purchase/c_p180c21o/printirlist/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
										$secDel		= site_url('c_purchase/c_p180c21o/d3l180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
                                        
                                    ?>
                                	<input type="hidden" name="urlIRList<?php echo $myNewNo; ?>" id="urlIRList<?php echo $myNewNo; ?>" value="<?php echo $secPIRList; ?>">                        
                                    <input type="hidden" name="urlPrint<?php echo $myNewNo; ?>" id="urlPrint<?php echo $myNewNo; ?>" value="<?php echo $secPrint; ?>">
                                    <td style="text-align:center" nowrap>
                                        <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a href="javascript:void(null);" class="btn btn-warning btn-xs" title="View Receipt" onClick="printIRList('<?php echo $myNewNo; ?>')">
                                            <i class="glyphicon glyphicon-list"></i>
                                        </a>
                                        <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                            <i class="glyphicon glyphicon-print"></i>
                                        </a>
                                        <a href="<?php echo $secDel; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($PO_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                	</td>
                            	</tr>
                            	<?php 
                   			endforeach;
                		}
                        $url_add 	= site_url('c_purchase/c_p180c21o/a44p180c21o_p0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        $url_addRO 	= site_url('c_purchase/c_p180c21o/a44p180c21o_r0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        $url_addDir	= site_url('c_purchase/c_p180c21o/addDir/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        ?> 
                    </tbody>
                    <tr>
                        <td colspan="11">
                            <?php
								echo anchor("$url_add",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;'.$AddPO.'</button>&nbsp;&nbsp;');
								echo anchor("$url_addDir",'<button class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;POD</button>&nbsp;&nbsp;');
								echo anchor("$url_addRO",'<button class="btn btn-warning"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;RO</button>&nbsp;&nbsp;');
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
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