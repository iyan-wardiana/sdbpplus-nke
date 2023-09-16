<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Juli 2018
 * File Name	= v_expense_oth.php
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
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.css'; ?>">
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
		if($TranslCode == 'JournalType')$JournalType = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
		if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
		if($TranslCode == 'AddNew')$AddNew = $LangTransl;
		if($TranslCode == 'Print')$Print = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
		if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
	endforeach;
	if($LangID == 'IND')
	{
		$subTitleD	= "Biaya Rupa-Rupa";
		$sureDelete	= "Anda yakin akan menghapus data ini?";
	}
	else
	{
		$subTitleD	= "Other Expenses";
		$sureDelete	= "Are your sure want to delete?";
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $subTitleD; ?>
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
      <table id="example" class="table table-bordered table-striped" width="100%">
		<thead>
            <tr>
                <th style="vertical-align:middle; text-align:center" width="3%"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                <th style="vertical-align:middle; text-align:center" width="14%" nowrap><?php echo $Code; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="9%" nowrap><?php echo $Date; ?>  </th>
                <th width="48%" nowrap="nowrap" style="vertical-align:middle; text-align:center"><?php echo $Description; ?>  </th>
                <th width="10%" style="vertical-align:middle; text-align:center"><?php echo $TotalAmount; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="8%" nowrap><?php echo $CreatedBy; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="4%" nowrap><?php echo $Status; ?> </th>
                <th style="vertical-align:middle; text-align:center" width="4%" nowrap>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tr>
          <td colspan="8">
            <?php
                echo anchor("$addURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>');
			?>&nbsp;
                <input type="hidden" name="myMR_Number" id="myMR_Number" value="" />
            <?php
				echo anchor("$backURL",'<button class="btn btn-danger"><i class="fa fa-reply"></i></button>');
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
		$secIndex_PR	= site_url('c_purchase/c_purchase_req/get_all_PR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
	$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
		//"scrollX": false,
		"autoWidth": true,
		"filter": true,
        "ajax": "<?php echo site_url('c_finance/c_cpa70d18/get_AllData_i/?id='.$PRJCODE)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [1,5,6], className: 'dt-body-center' },
						{ targets: [4], className: 'dt-body-right' },
						{ "width": "100px", "targets": [1] }
					  ],
		"order": [[ 2, "desc" ]],
		"language": {
            "infoFiltered":"",
            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
        },
		} );
	} );
  
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
			var index_Mail	= "<?php echo $secIndex_PR; ?>";
			var formAction 	= $('#sendDelete')[0].action;
			var data = $('.form-user').serialize();
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
	
	function printPOList(row)
	{
		var url	= document.getElementById('urlPOList'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function printD(row)
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