<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2017
 * File Name	= jobopname.php
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

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
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
		if($TranslCode == 'SPKCode')$SPKCode = $LangTransl;
		if($TranslCode == 'SPKType')$SPKType = $LangTransl;
		if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'SPKCost')$SPKCost = $LangTransl;
		if($TranslCode == 'PrintOpname')$PrintOpname = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$AlertD	= "Pilih salah satu SPK...";
		$AlertS	= "Pilih salah satu tahapan opname ...";
	}
	else
	{
		$AlertD	= "Please check one of SPK Code...";
		$AlertS	= "Please choose a Opname Step ...";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $subTitle; ?>
    <small><?php echo $PRJNAME; ?></small>
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
        <?php /*?><div class="box-header with-border">
            <h3 class="box-title"><?php echo $PRJNAME; ?></h3>
        </div><?php */?>
    <!-- /.box-header -->
<div class="box-body">
	<div class="search-table-outter">
      <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		<thead>
            <tr>
                <th width="3%" nowrap="nowrap">&nbsp;</th>
                <th width="7%" nowrap="nowrap"><?php echo $SPKCode; ?></th>
                <th width="6%" nowrap="nowrap"><?php echo $SPKType; ?></th>
                <th width="29%" nowrap="nowrap"><?php echo $ProjectName; ?></th>
                <th width="37%"><?php echo $SupplierName; ?></th>
                <th width="2%" nowrap="nowrap"><?php echo $Date; ?></th>
                <th width="2%" nowrap="nowrap"><?php echo $SPKCost; ?></th>
                <th width="14%" nowrap="nowrap"><?php echo $PrintOpname; ?></th>
        	</tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;
			$myNewNo = 0;
			if($countOPN >0)
			{
				foreach($viewOPN as $row) : 
					$myNewNo	= $myNewNo + 1;
					$SPKCODE	= $row->SPKCODE;
					$SPKTYPE	= $row->SPKTYPE;
					$TRXDATE	= $row->TRXDATE;
					$PRJCODE	= $row->PRJCODE;
					$SPLCODE	= $row->SPLCODE;
					$TRXDATE	= $row->TRXDATE;
					$SPKCOST	= $row->SPKCOST;
					
					$secUpd	= site_url($secURL.$this->url_encryption_helper->encode_url($PRJCODE));
					
					if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}
						?> 
								<td nowrap>&nbsp;
                                    <input name="chckSPKCode<?php echo $myNewNo; ?>" id="chckSPKCode<?php echo $myNewNo; ?>" type="radio" value="<?php echo $SPKCODE; ?>" onclick="chkSPK(<?php echo $myNewNo; ?>)" />
                                    <input name="chckSPKCodex_<?php echo $myNewNo; ?>" id="chckSPKCodex_<?php echo $myNewNo; ?>" type="radio" value="<?php echo $SPKCODE; ?>" style="display:none"/>   
                                    <input type="hidden" name="totrow" id="totrow" value="<?php echo $myNewNo; ?>">           	
                                </td>
                                <td nowrap><?php print anchor('c_project/listproject/update/'.$SPKCODE,$SPKCODE,array('class' => 'update')).' '; ?> </td>
                                <td nowrap><?php print $SPKTYPE; ?> </td>
                                <td nowrap><?php print "$PRJNAME"; ?> </td>
                                <?php
                                    $SPLDESC		= " - ";
                                ?>
                                <td nowrap><?php print "$SPLDESC"; ?> </td>
                                <td style="text-align:center" nowrap><?php print $TRXDATE; ?> </td>
                                <td style="text-align:right" nowrap><?php print number_format($SPKCOST, $decFormat); ?></td>
                                <td style="text-align:center">
                                    <select name="opStep_<?php echo $myNewNo; ?>" id="opStep_<?php echo $myNewNo; ?>" class="form-control" style="max-width:350px" onchange="chgSubmit('<?php echo $myNewNo; ?>', this.value);">
                                        <option value="0">None</option>
                                        <?php
                                            $sqlCOpN	= "tbl_jobopname WHERE SPKCODE = '$SPKCODE'";
                                            $qtyRow = $this->db->count_all($sqlCOpN);
                                            
                                            if($qtyRow > 0)
                                            {
                                                $qGetOpname		= "SELECT DISTINCT SPKCODE, OPSTEP FROM tbl_jobopname
                                                                    WHERE SPKCODE = '$SPKCODE'";						
                                                $resultOpN = $this->db->query($qGetOpname)->result();
                                                foreach($resultOpN as $rowOpN) :
                                                    $SPKCODE = $rowOpN->SPKCODE;
                                                    $OPSTEP = $rowOpN->OPSTEP;
                                        ?>
                                                    <option value="<?php echo $OPSTEP; ?>">Opname <?php echo $OPSTEP; ?></option>
                                        <?php
                                                endforeach;
                                            }
                                        ?>
                                    </select>
                                </td>
							</tr>
						<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tr>
          <td colspan="8">
          	<input type="hidden" name="SPKChecked" id="SPKChecked" value="">
          	<input type="hidden" name="SPKStepChk" id="SPKStepChk" value="">
          	<input type="hidden" name="SPKStepChkNo" id="SPKStepChkNo" value="">
            <button class="btn btn-primary" type="button" name="submitAddOpn" id="submitAddOpn" onClick="addOpname();">
                <i class="cus-add-16x16"></i>
                &nbsp;&nbsp;<?php echo $Add; ?>
            </button>&nbsp;&nbsp;
            <button class="btn btn-warning" onClick="printOpname()">
                <i class="cus-print-16x16"></i>
                &nbsp;&nbsp;<?php echo $Print; ?>
            </button>
            </td>
        </tr>
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
  
	function chkSPK(chkThis)
	{
		var SPKCODE	= document.getElementById('chckSPKCodex_'+chkThis).value;
		document.getElementById('SPKChecked').value	= SPKCODE;
	}
		
	function addOpname()
	{
		var SPKCODE	= document.getElementById('SPKChecked').value;
		if(SPKCODE == '')
		{
			alert('<?php echo $AlertD; ?>');
			return false;
		}
		else
		{
			var url = "<?php echo $addOPN; ?>";
			title = 'Select Item';
			w = 1200;
			h = 550;
			var left = (screen.width/2)-(w/2);
			var top = (screen.height/2)-(h/2);
			window.open('<?php echo $addOPN; ?>/'+SPKCODE, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		}
	}
	
	function chgSubmit(theRow, theStepOpn)
	{
		//GET SPKCODE SELECTED FROM THE ROW
		var SPKCODE	= document.getElementById('chckSPKCodex_'+theRow).value;
		document.getElementById('SPKChecked').value				= SPKCODE;
		document.getElementById('SPKStepChkNo').value			= theStepOpn;
		document.getElementById('chckSPKCode'+theRow).checked 	= true;
		collData	= SPKCODE+'~'+theStepOpn;
		document.getElementById('SPKStepChk').value = collData;
	}
		
	function printOpname()
	{
		var SPKCODE		= document.getElementById('SPKChecked').value;
		var opnStep		= document.getElementById('SPKStepChkNo').value;
		var SPKCOD_STEP	= document.getElementById('SPKStepChk').value;
		
		if(SPKCODE == '')
		{
			alert('<?php echo $AlertD; ?>');
			return false;
		}
		if(opnStep == '')
		{
			alert('<?php echo $AlertS; ?>');
			return false;
		}
		else
		{
			title = 'Select Item';
			w = 1200;
			h = 550;
			var left = (screen.width/2)-(w/2);
			var top = (screen.height/2)-(h/2);
			window.open('<?php echo $printOPN; ?>/'+SPKCOD_STEP, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		}
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>