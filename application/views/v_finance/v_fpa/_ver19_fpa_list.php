<?php
/* 
 * Author		= Wardiana
 * Create Date	= 28 Agustus 2018
 * File Name	= fpa_list.php
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

$selProject = '';
if(isset($_POST['submit']))
{
	$selProject = $_POST['selProject'];
}

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
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
		if($TranslCode == 'NoFPA')$NoFPA = $LangTransl;
		if($TranslCode == 'TFDate')$TFDate = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
		if($TranslCode == 'WorkOrder')$WorkOrder = $LangTransl;
		if($TranslCode == 'AddNew')$AddNew = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
		if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
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
    <?php echo "FPA"; ?>
    <small><?php echo $PRJNAME; ?></small>
  </h1><br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style type="text/css">
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
	
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>
<!-- Main content -->

  <div class="box">
        <?php /*?><div class="box-header with-border">
            <h3 class="box-title"><?php echo $PRJNAME; ?></h3>
        </div><?php */?>
    <!-- /.box-header -->
<div class="box-body">
	<div class="search-table-outter">
      <table id="example1" class="table table-bordered table-striped" width="100%">
		<thead>
            <tr>
                <th style="vertical-align:middle; text-align:center; display:none" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                <th style="vertical-align:middle; text-align:center" width="6%" nowrap><?php echo $NoFPA; ?> </th>
                <th width="4%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Date; ?>  </th>
                <th style="vertical-align:middle; text-align:center;" width="4%" nowrap><?php echo $TFDate; ?>  </th>
                <th width="67%" style="vertical-align:middle; text-align:center"><?php echo $Description; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="8%" nowrap><?php echo $CreatedBy; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="4%" nowrap><?php echo $Status; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="4%" nowrap>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
		<?php
			$i = 0;
			$j = 0;						
			if($cData >0)
			{
				foreach($vData as $row) :				
					$myNewNo1 		= ++$i;
					$FPA_NUM		= $row->FPA_NUM;
					$FPA_CODE		= $row->FPA_CODE;
					$FPA_DATE		= $row->FPA_DATE;
					$FPA_DATEV		= date('d M Y', strtotime($FPA_DATE));
					$PRJCODE		= $row->PRJCODE;
					$PRJNAME		= '';
					$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
					$resPRJ			= $this->db->query($sqlPRJ)->result();
					foreach($resPRJ as $rowPRJ) :
						$PRJNAME 	= $rowPRJ->PRJNAME;
					endforeach;
					
					$FPA_NOTE		= $row->FPA_NOTE;
					$FPA_NOTED		= '';
					if($FPA_NOTE != '')
						$FPA_NOTED	= " : $FPA_NOTE";
					$FPA_STAT		= $row->FPA_STAT;
					$JOBCODEID		= $row->JOBCODEID;
					$FPA_CREATER	= $row->FPA_CREATER;
					$FPA_STATD		= $row->STATDESC;
					$STATCOL		= $row->STATCOL;
					$CREATERNM		= $row->CREATERNM;							
					$empName		= cut_text ("$CREATERNM", 15);
					
					$JOBCODEID		= $row->JOBCODEID;
					
					// CARI TOTAL REGUSEST BUDGET APPROVED
						$JOBDESC		= '';
						$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
						$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
						foreach($resJOBDESC as $rowJOBDESC) :
							$JOBDESC	= $rowJOBDESC->JOBDESC;
						endforeach;
					
					if ($j==1) {
						echo "<tr class=zebra1>";
						$j++;
					} else {
						echo "<tr class=zebra2>";
						$j--;
					}
						?>
								<td style="text-align:center; display:none">
									<input type="radio" name="chkDetail" id="chkDetail" value="<?php echo $FPA_NUM;?>" onClick="getValueNo(this);" />
                                </td>
								<td nowrap>
									<?php echo $FPA_CODE;?>
                                </td>
								<td nowrap> <?php print $FPA_DATEV; ?></td>
								<td style="text-align:center" nowrap><?php
										$date = new DateTime($FPA_DATE);
									   	//echo $FPA_DATE;
									?></td>
								<td><?php print "$JOBDESC$FPA_NOTED"; ?> </td>
								<td style="text-align:center" nowrap><?php echo $empName; ?></td>
								<td style="text-align:center">
                                <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
									<?php 
                                        echo "&nbsp;&nbsp;$FPA_STATD&nbsp;&nbsp;";
                                     ?>
                                </span>
                                </td>
                                <?php
									$secUpd		= site_url('c_finance/c_f180p0/up180d0bdt/?id='.$this->url_encryption_helper->encode_url($FPA_NUM));
									$secPrint	= site_url('c_finance/c_f180p0/printdocument/?id='.$this->url_encryption_helper->encode_url($FPA_NUM));
									$CollID		= "$FPA_NUM~$PRJCODE";
									$secDel_WO 	= base_url().'index.php/c_finance/c_f180p0/trash_WO/?id='.$FPA_NUM;
								?>                          
                                <input type="hidden" name="urlPrint<?php echo $myNewNo1; ?>" id="urlPrint<?php echo $myNewNo1; ?>" value="<?php echo $secPrint; ?>">
								<td style="text-align:center" nowrap>
                                	<?php if($ISCREATE == 1 || $ISAPPROVE == 1) { ?>
                                    <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <?php } ?>
                                    <a href="javascript:void(null);" class="btn btn-warning btn-xs" title="Opname" onClick="printDocument('<?php echo $myNewNo1; ?>')" style="display:none">
                                        <i class="glyphicon glyphicon-export"></i>
                                    </a>
                                    <a href="javascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo1; ?>')" style="display:none">
                                        <i class="glyphicon glyphicon-print"></i>
                                    </a>
                                    <a href="#" onClick="deleteMail('<?php echo $secDel_WO; ?>')" title="Delete file" class="btn btn-danger btn-xs" <?php if($FPA_STAT > 1) { ?>disabled="disabled" <?php } ?> style="display:none">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
						    </tr>
						<?php 
				endforeach; 
			}
		?>
        </tbody>
        <tr>
          <td colspan="8">
            <?php
                echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;'.$Add.'</button>');
			?>&nbsp;
                <input type="hidden" name="myMR_Number" id="myMR_Number" value="" />
            <?php
				echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-mail-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
            ?>
            </td>
        </tr>
   	</table>
    
    <form method="post" name="sendDelete" id="sendDelete" class="form-user" action="" style="display:none">		
        <table>
            <tr>
                <td></td>
                <td><a class="tombol-delete" id="delClass">Simpan</a></td>
            </tr>
        </table>
    </form>
    <?php
		$secIndex_PR	= site_url('c_finance/c_f180p0/get_all_WO/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	?>
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
  
	function deleteMail(thisVal)
	{
		//alert(thisVal)
		//document.sendDelete.action = thisVal;
		//document.sendDelete.submit();
		//document.getElementById('delClass').click();
		document.sendDelete.action = thisVal;
		document.getElementById('delClass').click();
	}
	
	$(document).ready(function()
	{
		$(".tombol-delete").click(function()
		{
			alert('a')
			var index_Mail	= "<?php echo $secIndex_PR; ?>";
			var formAction 	= $('#sendDelete')[0].action;
			alert('b')
			var data = $('.form-user').serialize();
			alert('c')
			$.ajax({
				type: 'POST',
				url: formAction,
				data: data,
				success: function(response)
				{
					$( "#example1" ).load( ""+index_Mail+" #example1" );
				}
			});
			
		});
	});
  
	function getValueNo(thisVal)
	{
		myValue = thisVal.value;myMR_Number
		document.getElementById('myMR_Number').value = myValue;
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