<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 12 Januari 2018
 * File Name	= v_inb_bank_payment.php
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

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
//$PRJCODE		= $PRJCODE;


$PRJNAME	= '';
$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
$resPRJ 	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
{
	$PRJNAME = $rowPRJ->PRJNAME;
}
endforeach;
?>

<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
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
		if($TranslCode == 'INVCode')$INVCode = $LangTransl;
		if($TranslCode == 'INVNo')$INVNo = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'SupplierCode')$SupplierCode = $LangTransl;
		if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
		if($TranslCode == 'Amount')$Amount = $LangTransl;
		if($TranslCode == 'PPn')$PPn = $LangTransl;
		if($TranslCode == 'DueDate')$DueDate = $LangTransl;
		if($TranslCode == 'Approve')$Approve = $LangTransl;
		if($TranslCode == 'User')$User = $LangTransl;
		if($TranslCode == 'BankPayment')$BankPayment = $LangTransl;
		if($TranslCode == 'Finance')$Finance = $LangTransl;
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'Approval')$Approval = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Payee')$Payee = $LangTransl;
		if($TranslCode == 'BankAccount')$BankAccount = $LangTransl;
		if($TranslCode == 'Payment')$Payment = $LangTransl;
		if($TranslCode == 'Memo')$Memo = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'IsVoid')$IsVoid = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$paymentApp	= "Persetujuan Pembayaran";
		$sureDelete	= "Anda yakin akan menghapus data ini?";
	}
	else
	{
		$paymentApp	= "Payment Approval";
		$sureDelete	= "Are your sure want to delete?";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <?php echo $paymentApp; ?>
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
                        <th width="3%" style="display:none"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                        <th width="9%" style="text-align:center" nowrap><?php echo $Code; ?></th>
                        <th width="19%" style="text-align:center" nowrap><?php echo $Payee; ?></th>
                        <th width="7%" style="text-align:center" nowrap><?php echo $Date; ?></th>
                        <th width="32%" style="text-align:center"><?php echo $BankAccount; ?></th>
                        <th width="16%" style="text-align:center"><?php echo $Payment; ?></th>
                        <th width="3%" style="text-align:center; display:none" nowrap><?php echo $Memo; ?></th>
                        <th width="6%" style="text-align:center" nowrap><?php echo $Status; ?></th>
                        <th width="2%" style="text-align:center" nowrap><?php echo $IsVoid; ?></th>
                        <th width="3%" style="text-align:center" nowrap>&nbsp;</th>
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
                                $JournalH_Code	= $row->JournalH_Code;
								$CB_CODE		= $row->CB_CODE;
								$CB_DATE		= $row->CB_DATE;
								$CB_TYPE		= $row->CB_TYPE; 
								$Account_Name	= $row->Account_Name; 
								$CB_PAYFOR		= $row->CB_PAYFOR;
									$SPLDESC	= '';
									$sqlSPL		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE  = '$CB_PAYFOR' LIMIT 1";
									$resSPL		= $this->db->query($sqlSPL)->result();
									foreach($resSPL as $rowSPL) :
										$SPLDESC= $rowSPL->SPLDESC;
									endforeach;
								
								$CB_NOTES		= $row->CB_NOTES;
								$CB_STAT		= $row->CB_STAT;
								$ISVOID			= $row->ISVOID;
							
								if($CB_STAT == 0)
								{
									$CB_STATD 	= 'fake';
									$STATCOL	= 'danger';
								}
								elseif($CB_STAT == 1)
								{
									$CB_STATD 	= 'New';
									$STATCOL	= 'warning';
								}
								elseif($CB_STAT == 2)
								{
									$CB_STATD 	= 'Confirm';
									$STATCOL	= 'primary';
								}
								elseif($CB_STAT == 3)
								{
									$CB_STATD 	= 'Approved';
									$STATCOL	= 'success';
								}
								elseif($CB_STAT == 4)
								{
									$CB_STATD 	= 'Revise';
									$STATCOL	= 'danger';
								}
								elseif($CB_STAT == 5)
								{
									$CB_STATD 	= 'Rejected';
									$STATCOL	= 'danger';
								}
								elseif($CB_STAT == 6)
								{
									$CB_STATD 	= 'Close';
									$STATCOL	= 'danger';
								}
								elseif($CB_STAT == 7)
								{
									$CB_STATD 	= 'Awaiting';
									$STATCOL	= 'warning';
								}
								else
								{
									$CB_STATD 	= 'Awaiting';
									$STATCOL	= 'warning';
								}
							
								if($ISVOID == 0)
								{
									$CISVOIDD 		= 'no';
									$STATVCOL		= 'success';
								}
								elseif($CB_STAT == 1)
								{
									$CISVOIDD 		= 'yes';
									$STATVCOL		= 'danger';
								}
								
								$secUpd			= site_url('c_finance/c_bp0c07180851/uG37Bp4YMn_1n/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
                                
                                if ($j==1) {
                                    echo "<tr class=zebra1>";
                                    $j++;
                                } else {
                                    echo "<tr class=zebra2>";
                                    $j--;
                                }
                                
                        		?>
                                    <td style="text-align:center; display:none"> <?php print '<input name="chkDetail" id="chkDetail" type="radio" value="'.$JournalH_Code.'" />'; ?> </td>
                                    <td nowrap><?php echo $CB_CODE;?></td>
                                    <td> <?php echo "$CB_PAYFOR - $SPLDESC"; ?></td>
                                    <td style="text-align:center"> <?php print $CB_DATE; ?> </td>
                                    <td> <?php print $Account_Name; ?> </td>
                                    <td>&nbsp;</td>
                                    <td style="display:none">&nbsp;</td>
                                    <td style="text-align:center">
                                    <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
										<?php 
                                            echo "&nbsp;&nbsp;$CB_STATD&nbsp;&nbsp;";
                                         ?>
                                    </span>
                                    </td>
                                    <td style="text-align:center">
                                    <span class="label label-<?php echo $STATVCOL; ?>" style="font-size:12px">
										<?php 
                                            echo "&nbsp;&nbsp;$CISVOIDD&nbsp;&nbsp;";
                                         ?>
                                    </span>
                                    </td>
                                    <td style="text-align:center" nowrap>
                                        <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                            <i class="glyphicon glyphicon-print"></i>
                                        </a>
                                        <a href="" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('<?php echo $sureDelete; ?>')" <?php if($CB_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                           	    </tr>
                            	<?php 
                   			endforeach;
                		}
                        ?> 
                    </tbody>
                    <tr>
                        <td colspan="10">
                        	<?php 
								echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
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
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>